<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FirebasePushController;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\StoreBranch;
use App\Services\OrderService;
use App\Services\OrderStatsService;
use App\Traits\Api\UserIsAuthorizedTrait;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    use UserIsAuthorizedTrait;
    use ApiResponseTrait;

    protected $orderService;
    protected $orderStatsService;

    public function __construct(OrderService $orderService, OrderStatsService $orderStatsService)
    {
        $this->orderService = $orderService;
        $this->orderStatsService = $orderStatsService;

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|numeric|exists:users,id',
            'currency_code' => 'required|string|in:SAR',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $listOfOrders = $this->orderService->getOrders($request);

        return OrderResource::collection($listOfOrders);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        $validator = Validator::make($request->all(), [
            'order_id' => 'nullable|numeric|exists:orders,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return $this->respondFailedValidation(message: $validator->errors()->first());
        }

        $order = $this->orderService->getOrderById($request->order_id);

        return new OrderResource($order);
    }

    // customer methods
    public function indexForCustomer(Request $request)
    {
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        if ($request->user()->id != $request->customer_id) {
            return response()->json([
                "message" => __('locale.api.errors.user_is_forbidden_from_resource'),
                "code" => "FORBIDDEN_ERROR"
            ], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|numeric|exists:users,id',
            'currency_code' => 'required|string|in:SAR',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $listOfOrders = $this->orderService->getOrdersForCustomer($request);

        return OrderResource::collection($listOfOrders);
    }

    public function getOrderFromFirebase(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'order_id' => 'nullable|numeric|exists:App\Models\Order,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return $this->respondFailedValidation(message: $validator->errors()->first());
        }

        $order = Order::with(['order_items', 'store', 'store_branch', 'customer', 'customer_vehicle', 'employee', 'invoice'])
            ->find(request('order_id'));

        return OrderResource::collection($order);
    }

    public function addOrderFromFirebase(Request $request)
    {

        $validationError = $this->validateRequest($request);
        if ($validationError) return $validationError;


        $items = $request->input('items');
        if (!$this->validateItems($items)) {
            return response()->json([
                "message" => "Items array is required and must not be empty",
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$this->isBranchBelongsToStore($request->store_id, $request->store_branch_id)) {
            return response()->json([
                "message" => "Store and branch are not related.",
                "code" => "RELATIONSHIP_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            $order = $this->orderService->createOrder($request, $items);
            $this->orderService->createOrderItems($order['data'], $items);
            $this->orderService->handleCouponRedemption($request, $order['data']);

            DB::commit();
            return response()->json([
                "message" => "Order created successfully",
                "code" => "SUCCESS",
                "data" => OrderResource::make($order['data']),
                'payment' => $order['payment'] ?? null,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API:OrderController:addOrderFromFirebase: ' . $e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|numeric|exists:App\Models\User,id',
            'customer_vehicle_id' => 'required|integer|min:1|exists:App\Models\CustomerVehicle,id',
            'checkout_method' => 'required|string',
            'store_branch_id' => 'required|numeric|exists:App\Models\StoreBranch,id',
            'employee_id' => 'required|numeric|exists:App\Models\User,id',
            'coupon_code' => 'nullable|string|exists:App\Models\Coupon,code',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return $this->respondFailedValidation(message: $validator->errors()->first());
        }

        return null;
    }

    private function validateItems($items)
    {
        if (!is_array($items) || count($items) === 0) return false;

        foreach ($items as $item) {
            // Convert 'empty' strings to null for validation purposes
            if (isset($item['voice_url']) && $item['voice_url'] === 'empty') {
                $item['voice_url'] = null;
            }

            if (isset($item['image_url']) && $item['image_url'] === 'empty') {
                $item['image_url'] = null;
            }

            $itemValidator = Validator::make($item, [
                'product_id' => 'nullable|numeric',
                'item_name' => 'nullable|string',
                'item_quantity' => 'required|integer|min:1',
                'note' => 'nullable|string',
                'voice_url' => 'nullable|url',
                'image_url' => 'nullable|url',
            ]);

            if ($itemValidator->fails()) return false;
        }

        return true;
    }


    // employee methods
    public function indexForEmployee(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if the user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['worker_supervisor', 'worker']);
        if ($error) return $error;

        // check if user is authorized to use the resource
        if ($request->user()->id != request('employee_id')) {
            return response()->json([
                "message" => __('locale.api.errors.user_is_forbidden_from_resource'),
                "code" => "FORBIDDEN_ERROR"
            ], Response::HTTP_FORBIDDEN);
        }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'employee_id' => 'nullable|numeric|exists:App\Models\User,id',
            'currency_code' => 'required|string|in:SAR',
            // 'currency_code'=> 'required|string|exists:App\Models\Currency,code',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return $this->respondFailedValidation(message: $validator->errors()->first());
        }

        // Define the status values you want to filter by
        $statusValues = OrderStatusEnum::except(['RECEIVED', 'PROCESSING', 'DELIVERING']);

        $listOfOrders = Order::with([
            'order_items',
            'invoice',
            // 'rating',
            'customer' => fn($q) => isset($request->employee_id),
            'customer_vehicle' => fn($q) => isset($request->employee_id),
            'employee:id,name,username,email,dial_code,contact_no,profile_photo_path',
            'store_branch:id,name_ar,name_en,branch_serial_number,email',
            'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
        ])->when(request('employee_id'), function ($query) {
            return $query->whereHas('employee', function ($query) {
                $query->where('id', request('employee_id'));
            });
        })->whereIn('status', $statusValues)
            ->orderby('order_date', 'desc')
            ->paginate(request('limit', 10));

        return OrderResource::collection($listOfOrders);
    }

    public function refundOrderList(Request $request)
    {
        // Validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // Check if the user has the right roles
        $error = $this->checkIfUserHasRightRoles($request, ['worker_supervisor']);
        if ($error) return $error;

        $request->employee_id = $request->user()->id;

        // Validate request input
        $validator = Validator::make($request->all(), [
            'currency_code' => 'required|string|in:SAR',
            'order_date' => 'nullable|date_format:Y-m-d',
            'limit' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->respondFailedValidation(message: $validator->errors()->first());
        }

        $limit = $request->get('limit', 10); // Default to 10 per page

        $listOfOrders = Order::with([
            'order_items',
            'invoice',
            'customer' => fn($q) => isset($request->employee_id),
            'customer_vehicle' => fn($q) => isset($request->employee_id),
            'employee:id,name,username,email,dial_code,contact_no,profile_photo_path',
            'store_branch:id,name_ar,name_en,branch_serial_number,email',
            'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
        ])
        ->when($request->employee_id, function ($query) use ($request) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('id', $request->employee_id);
            });
        })
        ->where('refund_request', 1)
        ->when($request->order_date, function ($query) use ($request) {
            $query->whereDate('order_date', $request->order_date);
        })
        ->orderBy('order_date', 'desc')
        ->paginate($limit); // Supports page automatically

        return OrderResource::collection($listOfOrders);
    }

    public function makingOrderPage(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if the user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['worker_supervisor', 'worker']);
        if ($error) return $error;

        // check if the worker_supervisor / worker user is authorized to use the resource
        $error = $this->checkIfUserBelongsToBranch($request);
        if ($error) return $error;

        // Validate request fields
        $validator = Validator::make($request->all(), [
            'store_branch_id' => 'nullable|numeric|exists:App\Models\StoreBranch,id',
            'employee_id' => 'nullable|numeric|exists:App\Models\User,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return $this->respondFailedValidation(message: $validator->errors()->first());
        }

        // Define the status values you want to filter by
        $statusValues = OrderStatusEnum::only(['RECEIVED', 'PROCESSING', 'DELIVERING']);

        // Query the orders based on the criteria
        $orders = Order::with([
            'store_branch' => ['location'],
        ])->when(request('employee_id') || request('store_branch_id'), function ($query) {
            $query->where(function ($subquery) {
                if (request('employee_id')) {
                    $subquery->whereHas('employee', function ($q) {
                        $q->where('id', request('employee_id'));
                    });
                }
                if (request('store_branch_id')) {
                    $subquery->whereHas('store_branch', function ($q) {
                        $q->where('id', request('store_branch_id'));
                    });
                }
            });
        })->whereIn('status', $statusValues)
            ->orderBy('customer_special_needs_qualified', 'desc') // Orders with 1 come first, then 0
            ->orderBy('created_at', 'desc') // Sort by time (you can use 'created_at' or 'updated_at' as needed)
            ->get();

        return OrderResource::collection($orders);
    }

    public function changeOrderStatus(Request $request)
    {
        // Validate user credentials and roles
        if ($error = $this->checkIfRequestHasAuthUser($request)) {
            return $error;
        }
        if ($error = $this->checkIfUserHasRightRoles($request, ['worker_supervisor', 'worker'])) {
            return $error;
        }

        // Validate request fields
        $validator = Validator::make($request->all(), [
            'order_id' => [
                'required',
                'numeric',
                Rule::exists('orders', 'id')->where('employee_id', $request->employee_id),
            ],
            'locale' => ['nullable', Rule::in(['en', 'ar'])],
            'employee_id' => 'required|numeric|exists:App\Models\User,id',
            'status' => 'required|string',
        ]);

        // Return validation errors
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        // Fetch and update the order status
        $order = Order::findOrFail($request->order_id);
        $order->update(['status' => $request->status]);

        // Get the latest device token for the customer
        $userDevice = $order->customer->fcmTokens()->latest()->first();

        // Handle the case where no device token is found
        if ($userDevice) {
            // Determine locale and create notification content
            $locale = $userDevice->locale;
            $orderStatus = $request->status;
            $title = __('locale.api.orders.order_status.title.' . $orderStatus, [], $locale);
            $body = __('locale.api.orders.order_status.body.' . $orderStatus, [], $locale);

            // Send Firebase notification
            $firebaseController = app(FirebasePushController::class);
            $firebaseRequest = new Request([
                'user_id' => $order->customer_id,
                'title' => $title,
                'body' => $body,
            ]);
            $firebaseController->sendFirebaseNotification($firebaseRequest);
        }

        // Return a response indicating success
        return response()->json([
            "message" => "Order status updated successfully!",
            "order" => $order,
            "code" => "SUCCESS",
        ], Response::HTTP_OK);
    }

    public function cancelOrder(Request $request)
    {
        // Check if the user is authenticated
        if ($error = $this->checkIfRequestHasAuthUser($request)) {
            return $error;
        }
    
        $authUser = $request->user(); // Authenticated user
    
        // Check user role
        if (!$authUser || $authUser->user_type !== 'customer') {
            return response()->json([
                "message" => __('locale.api.errors.unauthorized_invalid_roles'),
                "code" => "UNAUTHORIZED",
            ], Response::HTTP_UNAUTHORIZED);
        }
    
        // Validate request
        $validator = Validator::make($request->all(), [
            'order_id' => [
                'required',
                'numeric',
                Rule::exists('orders', 'id')->where(function ($query) use ($authUser) {
                    $query->where('customer_id', $authUser->id);
                }),
            ],
            'reason' => 'required|string|max:1000',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }
    
        // Fetch the order
        $order = Order::where('id', $request->order_id)
                      ->where('customer_id', $authUser->id)
                      ->first();
    
        if (!$order) {
            return response()->json([
                "message" => __('locale.api.errors.order_not_found'),
                "code" => "ORDER_NOT_FOUND",
            ], Response::HTTP_NOT_FOUND);
        }
    
        if ($order->status === 'canceled') {
            return response()->json([
                "message" => __('locale.api.errors.order_already_canecelled'),
                "code" => "ORDER_ALREADY_CANCELED",
            ], Response::HTTP_BAD_REQUEST);
        }
    
        if ($order->refund_request === 1) {
            return response()->json([
                "message" =>__('locale.api.errors.refund_resquest_already_progress'),
                "code" => "REFUND_ALREADY_REQUESTED",
            ], Response::HTTP_BAD_REQUEST);
        }
    
        if ($order->is_paid != 1) {
            return response()->json([
                "message" => __('locale.api.errors.order_is_not_eligible'),
                "code" => "ORDER_NOT_PAID",
            ], Response::HTTP_BAD_REQUEST);
        }
    
        $orderDate = Carbon::parse($order->order_date)->setTimezone('Asia/Riyadh');
        $now = Carbon::now('Asia/Riyadh');
    
        if ($now->diffInHours($orderDate) > 72) {
            return response()->json([
                "message" => __('locale.api.errors.order_cancelled_in_3_days'),
                "code" => "CANCEL_WINDOW_EXPIRED",
            ], Response::HTTP_BAD_REQUEST);
        }
    
        // Update refund details
        $order->refund_request = 1;
        $order->refund_status = 'pending';
        $order->refund_reason = $request->reason;
        $order->save();
    
        // Send notification to employee (if any)
        if ($order->employee_id) {
            $employee = User::find($order->employee_id);
            $userDevice = $employee?->fcmTokens()->latest()->first();
    
            if ($userDevice) {
                $locale = $userDevice->locale ?? 'en';
                $title = __('locale.api.orders.order_status.title.refund-request', [], $locale);
                $body = __('locale.api.orders.order_status.body.refund-request', [], $locale);
    
                $firebaseController = app(FirebasePushController::class);
                $firebaseRequest = new Request([
                    'user_id' => $authUser->id,
                    'title' => $title,
                    'body' => $body,
                ]);
                $firebaseController->sendFirebaseNotification($firebaseRequest);
            }
        }
    
        return response()->json([
            "message" => __('locale.api.errors.order_update_refund_request'),
            "order" => $order,
            "code" => "SUCCESS",
        ], Response::HTTP_OK);
    }

    public function countOrdersByWeekdayForEmployee(Request $request)
    {
        $error = $this->validateUser($request);
        if ($error) return $error;

        $validator = Validator::make($request->all(), [
            'store_branch_id' => 'nullable|numeric|exists:App\Models\StoreBranch,id',
            'employee_id' => 'nullable|numeric|exists:App\Models\User,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return $this->respondFailedValidation(message: $validator->errors()->first());
        }

        return $this->orderStatsService->countOrdersByWeekdayForEmployee($request);
    }

    public function countOrdersByWeeksForEmployee(Request $request)
    {
        $error = $this->validateUser($request);
        if ($error) return $error;

        $validator = Validator::make($request->all(), [
            'store_branch_id' => 'nullable|numeric|exists:App\Models\StoreBranch,id',
            'employee_id' => 'nullable|numeric|exists:App\Models\User,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return $this->respondFailedValidation(message: $validator->errors()->first());
        }

        return $this->orderStatsService->countOrdersByWeeksForEmployee($request);
    }

    public function countOrdersByThreeMonthsForEmployee(Request $request)
    {
        $error = $this->validateUser($request);
        if ($error) return $error;

        $validator = Validator::make($request->all(), [
            'store_branch_id' => 'nullable|numeric|exists:App\Models\StoreBranch,id',
            'employee_id' => 'nullable|numeric|exists:App\Models\User,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return $this->respondFailedValidation(message: $validator->errors()->first());
        }

        return $this->orderStatsService->countOrdersByThreeMonthsForEmployee($request);
    }

    private function validateUser(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // check if the user is authorized to use the resource
        $error = $this->checkIfUserHasRightRoles($request, ['worker_supervisor', 'worker']);
        if ($error) return $error;

        // check if the worker_supervisor / worker user is authorized to use the resource
        $error = $this->checkIfUserBelongsToBranch($request);
        if ($error) return $error;

        return null;
    }

    public function getOrderStatus(Request $request)
    {
        // validate user credentials
        $error = $this->checkIfRequestHasAuthUser($request);
        if ($error) return $error;

        // validate request fields
        $validator = Validator::make($request->all(), [
            'order_id' => 'nullable|numeric|exists:App\Models\Order,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return $this->respondFailedValidation(message: $validator->errors()->first());
        }

        $order = Order::findOrFail(request('order_id'));

        return response()->json([
            "status" => $order->status,
            "is_paid" => $order->is_paid,
        ], Response::HTTP_OK);
    }

    // Function to check if the store and branch are related
    private function isBranchBelongsToStore($storeId, $branchId)
    {
        return StoreBranch::where('id', $branchId)->where('store_id', $storeId)->exists();
    }

    public function salesByWeekdayOfStore(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // check if user is authorized to use the resource
        if ($request->user()->owner_stores()->where('id', $request->store_id)->doesntExist()) {
            return response()->json([
                "message" => __('locale.api.errors.user_is_forbidden_from_resource'),
                "code" => "FORBIDDEN_ERROR"
            ], Response::HTTP_FORBIDDEN);
        }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'nullable|numeric|exists:App\Models\Store,id',
        ]);

        // Redirect if validation for the main order fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        // Initialize counts for all days to 0
        $ordersCountPerDayOfWeek = [
            "Saturday" => 0,
            "Sunday" => 0,
            "Monday" => 0,
            "Tuesday" => 0,
            "Wednesday" => 0,
            "Thursday" => 0,
            "Friday" => 0,
        ];

        $orders = Order::when(request('store_id'), function ($query) {
            $query->whereIn('status', [OrderStatusEnum::DELIVERED->value]);
            $query->where(function ($subquery) {
                if (request('store_id')) {
                    $subquery->whereHas('store', function ($q) {
                        $q->where('id', request('store_id'));
                    });
                }
            });
        })->get();

        foreach ($orders as $order) {
            $dayOfWeekName = Carbon::parse($order->order_date)->format('l');
            if (isset($ordersCountPerDayOfWeek[$dayOfWeekName])) {
                $ordersCountPerDayOfWeek[$dayOfWeekName] += $order->grand_total;
            }
        }

        // Define the order of days starting from Saturday
        $daysOrder = ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];

        // Sort the data based on the defined order
        $sortedData = [];

        foreach ($daysOrder as $day) {
            $sortedData[$day] = $ordersCountPerDayOfWeek[$day];
        }

        return $sortedData;
    }

    public function salesByWeekOfStore(Request $request)
    {
        // Validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // check if user is authorized to use the resource
        if ($request->user()->owner_stores()->where('id', $request->store_id)->doesntExist()) {
            return response()->json([
                "message" => __('locale.api.errors.user_is_forbidden_from_resource'),
                "code" => "FORBIDDEN_ERROR"
            ], Response::HTTP_FORBIDDEN);
        }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'nullable|numeric|exists:App\Models\Store,id',
        ]);

        // Redirect if validation for the main order fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        // Calculate the start and end dates for the last 4 weeks
        $currentDate = Carbon::now();
        $endOfWeek = $currentDate->endOfWeek();
        $startOfWeek = $endOfWeek->copy()->subWeeks(3);

        // Initialize counts for each week to 0
        $weekCounts = [
            "Week 1" => 0,
            "Week 2" => 0,
            "Week 3" => 0,
            "Week 4" => 0,
        ];

        for ($i = 1; $i <= 4; $i++) {
            $weekStartDate = $startOfWeek->copy()->addWeeks($i - 1);
            $weekEndDate = $startOfWeek->copy()->addWeeks($i);
            // Fetch orders within each week
            $orders = Order::when(request('store_id'), function ($query) {
                $query->whereIn('status', [OrderStatusEnum::DELIVERED->value]);
                $query->where(function ($subquery) {
                    if (request('store_id')) {
                        $subquery->whereHas('store', function ($q) {
                            $q->where('id', request('store_id'));
                        });
                    }
                });
            })->whereBetween('order_date', [$weekStartDate, $weekEndDate])->sum('grand_total');

            $weekCounts["Week $i"] = (float)$orders;
        }

        return $weekCounts;
    }

    public function salesByThreeMonthsOfStore(Request $request)
    {
        // Validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // check if user is authorized to use the resource
        if ($request->user()->owner_stores()->where('id', $request->store_id)->doesntExist()) {
            return response()->json([
                "message" => __('locale.api.errors.user_is_forbidden_from_resource'),
                "code" => "FORBIDDEN_ERROR"
            ], Response::HTTP_FORBIDDEN);
        }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'nullable|numeric|exists:App\Models\Store,id',
        ]);

        // Redirect if validation for the main order fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        // Calculate the start and end dates for the last 3 months
        $currentDate = Carbon::now();
        $startOfLastMonth = $currentDate->copy()->subMonths(3)->startOfMonth();
        $endOfLastMonth = $currentDate->copy()->endOfMonth();

        // Initialize counts for each month to 0
        $monthCounts = [];

        for ($i = 0; $i < 3; $i++) {
            $startOfMonth = $startOfLastMonth->copy()->addMonths($i);
            $endOfMonth = $endOfLastMonth->copy()->addMonths($i);

            // Fetch orders within each month
            $orders = Order::when(request('store_id'), function ($query) {
                $query->whereIn('status', [OrderStatusEnum::DELIVERED->value]);
                $query->where(function ($subquery) {
                    if (request('store_id')) {
                        $subquery->whereHas('store', function ($q) {
                            $q->where('id', request('store_id'));
                        });
                    }
                });
            })->whereBetween('order_date', [$startOfMonth, $endOfMonth])->sum('grand_total');

            // Get the name of the month
            $monthName = $startOfMonth->format('F');

            $monthCounts[$monthName] = (float)$orders;
        }

        return $monthCounts;
    }

    public function orderProcessingCounts(Request $request)
    {
        // Validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_branch_id' => 'nullable|numeric|exists:App\Models\StoreBranch,id',
        ]);

        // Redirect if validation for the main order fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        $statuses = [
            OrderStatusEnum::RECEIVED->value,
            OrderStatusEnum::PROCESSING->value,
            OrderStatusEnum::DELIVERING->value,
        ];

        return Order::whereIn('status', $statuses)->where('store_branch_id', $request->store_branch_id)->count();
    }

    public function uploadOrderImages(Request $request)
    {
        // Validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|numeric|exists:App\Models\Order,id',
            'image_invoice' => 'required|file|mimes:jpg,jpeg,png',
            'imageorder' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);

        // Redirect if validation for the main order fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        // Find the order by ID, or return an error if not found and order_id is provided
        $order = Order::findOrFail($request->order_id);

        // Get the uploaded image files from the request
        $imageInvoice = $request->file('image_invoice');
        $imageOrder = $request->file('imageorder');

        try {
            // Use the service to store images and update the order if provided
            if ($order) {
                $this->orderService->storeOrderImages($order, $imageInvoice, $imageOrder);
            }

            // Return a successful response
            return response()->json(['message' => 'Images uploaded successfully'], 200);

        } catch (\Exception $exception) {
            // Handle any errors that occur during the process
            return response()->json(['error' => 'Failed to upload images', 'message' => $exception->getMessage()], 500);
        }
    }
}

