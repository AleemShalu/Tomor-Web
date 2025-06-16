<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Http\Resources\OrderResource;
use App\Models\{Coupon,
    CouponRedemptionLog,
    CustomerVehicle,
    CustomerWithSpecialNeeds,
    Order,
    OrderItem,
    Product,
    ServiceDefinition,
    StoreBranch,
    User};
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log, Storage};
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class OrderService
{

    private $dhamenApiService;

    public function __construct(DhamenApiService $dhamenApiService)
    {
        $this->dhamenApiService = $dhamenApiService;
    }

    public function getOrders($request)
    {
        return Order::when($request->customer_id, function ($query, $customerId) {
            $query->where('customer_id', $customerId);
        })
            ->get();
    }

    public function getOrderById($orderId)
    {
        return Order::with(['order_items', 'store', 'store_branch', 'customer', 'customer_vehicle', 'employee', 'invoice'])
            ->findOrFail($orderId);
    }

    public function getOrdersForCustomer($request)
    {
        $listOfOrders = Order::with([
            'order_items',
            'invoice',
            // 'rating',
            'customer' => fn($q) => isset($request->customer_id),
            'customer_vehicle' => fn($q) => isset($request->customer_id),
            'employee:id,name,username,email,dial_code,contact_no,profile_photo_path',
            'store_branch:id,name_ar,name_en,branch_serial_number,email',
            'store_branch' => ['location'],
            'store',
        ])->when(request('customer_id'), function ($query) {
            return $query->whereHas('customer', function ($query) {
                $query->where('id', request('customer_id'));
            });
        })->orderby('order_date', 'desc')
            ->paginate(request('limit', 10));

        return OrderResource::collection($listOfOrders);
    }

    /**
     * @throws Exception
     */
    public function createOrder(Request $request, $items)
    {

        $now = Carbon::now();
        $branch = StoreBranch::find($request->store_branch_id);
        $storeId = $branch->store_id;
        $branchId = $branch->id;
        $customer = User::find($request->customer_id);

        $orderDetails = $this->generateOrderDetails($storeId, $branchId);
        $specialNeed = $this->getCustomerSpecialNeedsStatus($customer->id);
        $customerVehicle = CustomerVehicle::find($request->customer_vehicle_id);
        $serviceDefinition = ServiceDefinition::where('code', 1001.00)->first();
        $discountAmount = $this->calculateDiscount($request, $serviceDefinition->price);
        $itemsSubtotal = $this->calculateItemsSubtotal($items);
        $orderTotals = $this->calculateOrderTotals($serviceDefinition->price, $discountAmount, $itemsSubtotal);

        $orderData = array_merge(
            $orderDetails,
            $orderTotals,
            [
                'status' => OrderStatusEnum::PENDING_PAYMENT->value,
                'order_date' => $now,
                'items_count' => count($items),
                'items_quantity' => array_sum(array_column($items, 'item_quantity')),
                'base_currency_code' => config('app.currency'),
                'order_currency_code' => config('app.currency'),
                'checkout_method' => $request->checkout_method,
                'coupon_code' => $request->coupon_code ? Str::upper($request->coupon_code) : null,
                'store_id' => $storeId,
                'store_branch_id' => $branchId,
                'employee_id' => $request->employee_id,
                'customer_special_needs_qualified' => $specialNeed,
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_dial_code' => $customer->dial_code,
                'customer_contact_no' => $customer->contact_no,
                'customer_email' => $customer->email,
                'customer_vehicle_id' => $request->customer_vehicle_id,
                'customer_vehicle_manufacturer' => $customerVehicle->vehicle_manufacturer,
                'customer_vehicle_name' => $customerVehicle->vehicle_name,
                'customer_vehicle_model_year' => $customerVehicle->vehicle_model_year,
                'customer_vehicle_color' => $customerVehicle->vehicle_color,
                'customer_vehicle_plate_letters' => $customerVehicle->vehicle_plate_letters_en,
                'customer_vehicle_plate_number' => $customerVehicle->vehicle_plate_number,
            ]
        );

        $order = Order::create($orderData);

        // create payment order
        if (app()->environment('dev')) {
            $payment = $this->dhamenApiService->visitorPayment([
                'order_id' => $order->id,
                'amount' => $orderTotals['grand_total']
            ]);
        }

        return [
            'data' => $order,
            'payment' => $payment ?? null,
        ];
    }

    public function createOrderItems(Order $order, $items)
    {
        foreach ($items as $itemData) {
            if (isset($itemData['product_id']) && $itemData['product_id'] != 0) {
                $product = Product::find($itemData['product_id']);
                if ($product) {
                    $translation = $product->translations->where('locale', app()->getLocale())->first();
                    $item_quantity = $itemData['item_quantity'];
                    $item = new OrderItem([
                        'order_id' => $order->id,
                        'item_name' => $translation->name ?? null,
                        'product_id' => $product->id,
                        'item_unit_price' => $product->unit_price,
                        'item_quantity' => $item_quantity,
                        'item_total' => $product->unit_price * $item_quantity,
                        'item_status' => OrderStatusEnum::RECEIVED->value,
                        'note' => $itemData['note'] ?? null,
                        'voice_url' => $itemData['voice_url'] ?? null,
                    ]);
                }
            } else {
                $item = new OrderItem([
                    'order_id' => $order->id,
                    'item_name' => $itemData['item_name'] ?? 'Custom Item',
                    'product_id' => null, // Assuming 0 for custom items
                    'item_unit_price' => $itemData['item_unit_price'],
                    'item_quantity' => $itemData['item_quantity'],
                    'item_total' => $itemData['item_unit_price'] * $itemData['item_quantity'],
                    'item_status' => OrderStatusEnum::RECEIVED->value,
                    'note' => $itemData['note'] ?? null,
                    'voice_url' => $itemData['voice_url'] ?? null,
                    'image_url' => $itemData['image_url'] ?? null,
                ]);
            }

            if (isset($item)) {
                $item->save();

                if (!empty($item->voice_url)) {
                    $this->storeVoiceNote($item);
                }

                if (!empty($item->image_url)) {
                    $this->storeImage($item);
                }
            }
        }
    }

    public function handleCouponRedemption(Request $request, Order $order)
    {
        $code = $request->input('coupon_code');
        if ($code) {
            $coupon = Coupon::where('code', $code)->first();
            if ($coupon && $coupon->enabled && now()->between($coupon->start_date, $coupon->end_date) && $order->service_total >= $coupon->min_amount) {
                $userId = $request->customer_id;

                $existingUsage = DB::table('coupons_for_users')
                    ->where('coupon_id', $coupon->id)
                    ->where('user_id', $userId)
                    ->first();

                if ($existingUsage && $existingUsage->usage_count >= $coupon->max_uses_per_user) {
                    return response()->json([
                        "message" => __('locale.api.orders.coupons.max_usage_for_single_user_reached'),
                        "code" => "COUPON_MAX_USAGE_REACHED"
                    ], Response::HTTP_BAD_REQUEST);
                }

                if ($existingUsage) {
                    DB::table('coupons_for_users')
                        ->where('coupon_id', $coupon->id)
                        ->where('user_id', $userId)
                        ->increment('usage_count');
                } else {
                    $coupon->users()->attach($userId, ['usage_count' => 1]);
                }

                CouponRedemptionLog::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $userId,
                    'order_id' => $order->id,
                    'redemption_date' => now(),
                ]);
            }
        }
    }

    private function generateOrderDetails($storeId, $branchId)
    {
        $colors = ['#697602', '#2A2B69', '#E40000', '#000000'];

        $lastStoreOrder = Order::where('store_id', $storeId)
            ->where('store_branch_id', $branchId)
            ->whereNotNull('order_number')
            ->orderByRaw('CONVERT(order_number, SIGNED) desc')
            ->lockForUpdate()
            ->first();

        $colorIndex = $lastStoreOrder ? (array_search($lastStoreOrder->order_color, $colors) + 1) % count($colors) : 0;
        $orderNumber = $lastStoreOrder ? $lastStoreOrder->order_number + 1 : 1;
        $color = $colors[$colorIndex];
        $orderOnStore = "S$storeId-B$branchId-OR$orderNumber";
        $orderOnBranch = "B$branchId-OR$orderNumber";
        $waitingNumber = $lastStoreOrder && Carbon::parse($lastStoreOrder->created_at)->isToday() ? $lastStoreOrder->branch_queue_number + 1 : 1;

        return [
            'order_number' => $orderNumber,
            'order_color' => $color,
            'store_order_number' => $orderOnStore,
            'branch_order_number' => $orderOnBranch,
            'branch_queue_number' => $waitingNumber,
        ];
    }

    private function calculateDiscount(Request $request, $serviceCost)
    {
        $discountAmount = 0;

        $coupon = Coupon::where('code', $request->coupon_code)->first();
        if ($coupon) {
            if (isset($coupon->discount_percentage)) {
                $discountAmount = $serviceCost * ($coupon->discount_percentage / 100);
            } elseif (isset($coupon->discount_amount) && $coupon->discount_amount <= $serviceCost) {
                $discountAmount = $coupon->discount_amount;
            }
        }

//        $customerWithSpecialNeeds = CustomerWithSpecialNeeds::where('customer_id', $request->customer_id)->first();
//        if ($customerWithSpecialNeeds && $customerWithSpecialNeeds->special_needs_qualified == 1) {
//            $discountAmount = $serviceCost;
//        }

        return $discountAmount;
    }

    private function calculateItemsSubtotal($items)
    {
        $itemsSubtotal = 0;

        if (is_array($items) && !empty($items)) {
            foreach ($items as $item) {
                $itemQuantity = data_get($item, 'item_quantity');
                if (isset($item['product_id']) && $item['product_id'] != 0) {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $itemUnitPrice = data_get($item, 'unit_price') ?? $product->unit_price;
                        $itemSubtotal = $itemQuantity * calculateAmountExcludingVat($itemUnitPrice);
                        $itemsSubtotal += $itemSubtotal;
                    }
                } else {
                    $itemUnitPrice = data_get($item, 'item_unit_price');
                    if ($itemUnitPrice !== null) {
                        $itemSubtotal = $itemQuantity * calculateAmountExcludingVat($itemUnitPrice);
                        $itemsSubtotal += $itemSubtotal;
                    }
                }
            }
        }
        return $itemsSubtotal;
    }


    private function calculateOrderTotals($serviceCost, $discountAmount, $itemsSubtotal)
    {
        // taxable_total = ( subtotal + service_total + ....) - discount_total
        // tax_total = taxable_total * vat_rate
        // grand_total = taxable_total + tax_total
        // service_total_tax_amount  = service_total * vat_rate
        // service_total_including_tax = service_total + service_total_tax_amount

        $taxable_total = ($itemsSubtotal + $serviceCost) - $discountAmount;
        $tax_total = calculateVat($taxable_total);
        $grand_total = getAmountIncludingVate($taxable_total);
        $service_total_tax_amount = calculateVat($serviceCost);
        $service_total_including_tax = $serviceCost + $service_total_tax_amount;

        return [
            'discount_total' => $discountAmount,
            'tax_total' => $tax_total,
            'sub_total' => $itemsSubtotal,
            'service_total' => $serviceCost,
            'taxable_total' => $taxable_total,
            'grand_total' => $grand_total,
            'service_total_tax_amount' => $service_total_tax_amount,
            'service_total_including_tax' => $service_total_including_tax,
        ];
    }

    private function getCustomerSpecialNeedsStatus($customerId)
    {
        $customerWithSpecialNeeds = CustomerWithSpecialNeeds::where('customer_id', $customerId)->first();
        return $customerWithSpecialNeeds->special_needs_qualified ?? 0;
    }

    private function storeVoiceNote(OrderItem $item)
    {
        try {
            $fileContent = Http::withoutVerifying()->get($item->voice_url)->body();
            $filename = Str::uuid() . '.m4a';
            $now = Carbon::now()->format('Y_m_d_H_i_s');
            $productId = $item->product_id ?? 'custom-' . $item->id . '-' . $now . rand();
            $filePath = "stores/{$item->order->store_id}/orders/{$item->order_id}/voice_notes/{$productId}/{$filename}";
            Storage::disk(getSecondaryStorageDisk())->put($filePath, $fileContent);
            $item->voice_path = $filePath;
            $item->save();
        } catch (\Exception $exception) {
            Log::error("Failed to download or save voice note: {$exception->getMessage()}");
        }
    }

    private function storeImage(OrderItem $item)
    {
        try {
            // Get the content of the image from the URL
            $fileContent = Http::withoutVerifying()->get($item->image_url)->body();

            // Parse the URL and remove any query parameters
            $parsedUrl = parse_url($item->image_url);
            $path = $parsedUrl['path'];

            // Extract the file extension
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            // Generate a unique filename
            $filename = Str::uuid() . '.' . $extension;

            // Get the current timestamp and generate a unique product ID
            $now = Carbon::now()->format('Y_m_d_H_i_s');
            $productId = $item->product_id ?? 'custom-' . $item->id . '-' . $now . rand();

            // Construct the file path where the image will be saved
            $filePath = "stores/{$item->order->store_id}/orders/{$item->order_id}/images/{$productId}/{$filename}";

            // Save the file to the specified disk
            Storage::disk(getSecondaryStorageDisk())->put($filePath, $fileContent);

            // Update the item with the image path and save it
            $item->image_path = $filePath;
            $item->save();

        } catch (\Exception $exception) {
            // Log any errors that occur during the process
            Log::error("Failed to download or save image: {$exception->getMessage()}");
        }
    }


    public function storeOrderImages(Order $order, $imageInvoice, $imageOrder = null)
    {
        try {
            // Save the invoice image
            if ($imageInvoice) {
                $invoicePath = $this->saveImage($order, $imageInvoice, 'image_invoice');
                $order->image_invoice_path = $invoicePath;
            }

            // Save the order image if provided
            if ($imageOrder) {
                $orderPath = $this->saveImage($order, $imageOrder, 'image_order');
                $order->image_order_path = $orderPath;
            }

            // Save the updated order
            $order->save();

        } catch (\Exception $exception) {
            // Log any errors that occur during the process
            Log::error("Failed to save images: {$exception->getMessage()}");
            throw $exception;  // Re-throw the exception to let the controller handle it
        }
    }

    private function saveImage(Order $order, $image, $type)
    {
        // Generate a unique filename
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

        // Construct the file path where the image will be saved
        $filePath = "stores/{$order->store_id}/orders/{$order->id}/{$type}/{$filename}";

        // Save the file to the specified disk
        Storage::disk(getSecondaryStorageDisk())->put($filePath, file_get_contents($image->getRealPath()));

        return $filePath;
    }
}
