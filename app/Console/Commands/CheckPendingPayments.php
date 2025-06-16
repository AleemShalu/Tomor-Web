<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Services\DhamenApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckPendingPayments extends Command
{
    protected $signature = 'payments:check-pending';
    protected $description = 'Check the status of pending payments and update them accordingly';

    protected $dhamenApiService;

    public function __construct(DhamenApiService $dhamenApiService)
    {
        parent::__construct();
        $this->dhamenApiService = $dhamenApiService;
    }

    public function handle()
    {
        // Fetch all pending orders
        $pendingOrders = Order::where('status', OrderStatusEnum::PENDING_PAYMENT->value)->get();

        foreach ($pendingOrders as $order) {
            $this->dhamenApiService->checkPaymentStatus($order);
        }

        Log::info('Pending payments checked and updated.');
    }

}