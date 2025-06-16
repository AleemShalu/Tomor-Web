<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\DhamenApiService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FindOrdersForRefundCheck extends Command
{
    protected $signature = 'orders:find-refund-eligible';
    protected $description = 'Find orders within 72 hours, paid, no refund request, and payment not captured';

    private $dhamenApiService;

    public function __construct(DhamenApiService $dhamenApiService)
    {
        parent::__construct();
        $this->dhamenApiService = $dhamenApiService;
    }

    public function handle()
    {
        // Get current time in Asia/Riyadh
        $now = Carbon::now('Asia/Riyadh');
        $threshold = $now->copy()->subHours(72);

        // Query orders with store_branch relationship
        $orders = Order::with('store_branch')
            ->where('order_date', '>=', $threshold)
            ->where('is_paid', true)
            ->where('refund_request', false)
            ->where('is_payment_captured', false)
            ->get();

        // Log the results
        if ($orders->isEmpty()) {
            Log::info('No eligible orders found for refund check.', [
                'timestamp' => $now->toDateTimeString(),
                'timezone' => 'Asia/Riyadh',
            ]);
        } else {
            $ordersData = $orders->map(function ($order) use ($now) {
                $apiResponse = null;
                $apiStatus = 'skipped';

                // Check if payment_reference_id and supplier_id are available
                if ($order->payment_reference_id && $order->store_branch && $order->store_branch->supplier_id) {
                    try {
                        $apiResponse = $this->dhamenApiService->paymentCapture('/api/payments/capture', 'put', [
                            'paymentReferenceId' => $order->payment_reference_id,
                            'customerIdentifier' => $order->store_branch->supplier_id,
                        ]);
                        $apiStatus = 'success';
                    } catch (\Exception $e) {
                        $apiStatus = 'failed';
                        Log::error('Payment capture API failed for order.', [
                            'order_id' => $order->id,
                            'payment_reference_id' => $order->payment_reference_id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                } else {
                    Log::warning('Skipping payment capture due to missing data.', [
                        'order_id' => $order->id,
                        'payment_reference_id' => $order->payment_reference_id,
                        'supplier_id' => $order->store_branch ? $order->store_branch->supplier_id : null,
                    ]);
                }

                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'order_date' => $order->order_date->setTimezone('Asia/Riyadh')->toDateTimeString(),
                    'grand_total' => $order->grand_total,
                    'store_branch_id' => $order->store_branch_id,
                    'supplier_id' => $order->store_branch ? $order->store_branch->supplier_id : null,
                    'payment_reference_id' => $order->payment_reference_id,
                    'api_status' => $apiStatus,
                    'api_response' => $apiResponse,
                ];
            })->toArray();

            Log::info('Eligible orders found for refund check.', [
                'timestamp' => $now->toDateTimeString(),
                'timezone' => 'Asia/Riyadh',
                'order_count' => $orders->count(),
                'orders' => $ordersData,
            ]);
        }

        // Output to console
        $this->info("Found {$orders->count()} eligible orders at {$now->toDateTimeString()}");

        return 0;
    }
}