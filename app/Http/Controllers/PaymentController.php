<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\StoreBranch;
use App\Services\DhamenApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    private $dhamenApiService;

    public function __construct(DhamenApiService $dhamenApiService)
    {
        $this->dhamenApiService = $dhamenApiService;
    }

    public function paymentSuccess(Request $request)
    {
        // Retrieve data from the request
        $requestData = $request;

        // Log the entire request data
        Log::info('Payment response received:', (array)$requestData);

        // Return a JSON response for successful payment
        return response()->json([
            'status' => 'success',
            'message' => 'Payment successful',
        ]);
    }

    public function checkPaymentStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            // Fetch the order
            $order = Order::findOrFail($request->input('order_id'));

            $response = $this->dhamenApiService->checkPaymentStatus($order);

            // Log the response
            Log::info('Payment Status Check Response:', ['response' => $response]);

            // Return the response to the client
            return response()->json([
                'status' => 'success',
                'data' => $response,
            ]);

        } catch (\Exception $e) {
            Log::error('Error during payment status check: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while checking payment status.',
            ], 500);
        }
    }
    public function paymentRefund(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        try {

            $order = Order::findOrFail($request->input('order_id'));

            if (!$order->payment_reference_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('locale.payment.payment_reference_not_found'),
                ], 400);
            }

            // Check if store_branch_id and associated supplier_id exist
            if (!$order->store_branch_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('locale.payment.store_branch_not_found'),
                ], 400);
            }

            $storeBranch = StoreBranch::findOrFail($order->store_branch_id);

            if (!$storeBranch->supplier_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('locale.payment.supplier_id_not_found'),
                ], 400);
            }
            if ($order->refund_request !== true) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('locale.payment.no_refund_request_submitted'),
                ], 400);
            }


            $order->refund_status = 'accepted';
            $order->save();


            // Fetch payment_reference_id and supplier_id
            $paymentReferenceId = $order->payment_reference_id;
            $supplierId = $storeBranch->supplier_id;

            // Call the Dhamen API service for payment refund
            $response = $this->dhamenApiService->paymentRefund('/api/payments/reverse', 'put', [
                'paymentReferenceId' => $paymentReferenceId,
                'customerIdentifier' => $supplierId,
            ]);

            // Log the response
            Log::info('Payment Refund Response:', [
                'response' => $response,
                'paymentReferenceId' => $paymentReferenceId,
                'customerIdentifier' => $supplierId
            ]);

            // Return the response to the client
            return response()->json([
                'status' => 'success',
                'data' => $response,
            ]);

        } catch (\Exception $e) {
            Log::error('Error during payment refund: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'status' => 'error',
                'message' => __('locale.payment.eror_processing_the_payment_refund'),
            ], 500);
        }
    }

}