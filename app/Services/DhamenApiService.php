<?php

namespace App\Services;

use App\Enums\Dhamen\NotificationTypeEnum;
use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\FirebasePushController;
use App\Models\ApiDhamenLog;
use App\Models\BankAccount;
use App\Models\Order;
use App\Models\PaymentConfiguration;
use App\Models\StoreBranch;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DhamenApiService
{
    protected $baseUrl;
    protected $appId;
    protected $appKey;
    protected $clientId;
    protected $apiVersion;

    public function __construct()
    {
        $this->baseUrl = config('services.dhamen.base_url');
        $this->appId = config('services.dhamen.app_id');
        $this->appKey = config('services.dhamen.app_key');
        $this->clientId = config('services.dhamen.client_id');
        $this->apiVersion = config('services.dhamen.api_version');
    }

    protected function validateData($data, $rules)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function generateUniqueIdentityNumber()
    {
        // Retrieve prefix options from the payment_configurations table
        $prefixOptions = PaymentConfiguration::where('key', 'identity_number_prefix_options')
            ->value('value');

        // Convert the comma-separated string to an array
        $prefixOptions = explode(',', $prefixOptions);

        // Get the last store branch ID (or use a different approach to get a unique ID)
        $lastId = StoreBranch::max('id') ?: 0;

        // Increment the ID to ensure it's unique
        $identityNumber = str_pad($lastId + 1, 9, '0', STR_PAD_LEFT);

        // Add a prefix that starts with one of the prefix options
        $prefix = $prefixOptions[array_rand($prefixOptions)];

        // Combine prefix with the generated identity number
        $identityNumber = $prefix . $identityNumber;

        // Ensure the generated identity number is unique
        while (StoreBranch::where('identity_number', $identityNumber)->exists()) {
            // Increment and generate a new identity number if the current one already exists
            $lastId++;
            $identityNumber = str_pad($lastId + 1, 9, '0', STR_PAD_LEFT);
            $identityNumber = $prefix . $identityNumber;
        }

        return $identityNumber;
    }


//    protected function generateUniqueIdentityNumber()
//    {
//        $baseIdentityNumber = '2485555'; // Fixed part of the identity number
//
//        do {
//            // Generate a random number for the last 3 digits
//            $lastThreeDigits = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
//            $identityNumber = $baseIdentityNumber . $lastThreeDigits;
//        } while (StoreBranch::where('identity_number', $identityNumber)->exists());
//
//        return $identityNumber;
//    }


    public function createSupplier(array $data)
    {
        // Validation rules
        $rules = [
            'store_branch_id' => 'required|exists:store_branches,id',
        ];

        // Validate provided data
        $this->validateData($data, $rules);

        // Retrieve StoreBranch and BankAccount models
        $storeBranch = StoreBranch::findOrFail($data['store_branch_id']);

        $storeData = Store::findOrFail($storeBranch->store_id);
        if($storeData->capacity === 'large')
        {
            $capacity_limit = 50000;
        }
        else
        {
            $capacity_limit = 3000; 
        }
        
        $bankAccount = BankAccount::where('store_id', $storeBranch->store_id)->firstOrFail();
        

        // Generate a unique identity number
        $identityNumber = $this->generateUniqueIdentityNumber();

        // Prepare data for the request
        $requestData = [
            'name' => $storeBranch->store->commercial_name_ar . '-' . $storeBranch->name_ar,
            'iban' => $bankAccount->iban_number,
            'identityNumber' => $identityNumber,
            'email' => $storeBranch->email ?? null,
            'payoutThresholdAmount' =>  $capacity_limit,
            'mobile' => isset($storeBranch->dial_code, $storeBranch->contact_no)
                ? $storeBranch->dial_code . $storeBranch->contact_no
                : null,
        ];

        // Send the request to the API
        $response = $this->request('/api/payments/create-supplier', 'post', $requestData);
       
        // Log the response
        Log::info('API Response:', ['response' => $response]);

        // Update StoreBranch with the identity number and supplierId
        if ($storeBranch) {
            // Check if 'supplierId' exists in the response
            if (array_key_exists('supplierId', $response)) {
                // Updating attributes
                $storeBranch->identity_number = $identityNumber;
                $storeBranch->supplier_id = $response['supplierId'];

                // Saving the model
                $storeBranch->save();
            } else {
                // Log the error and throw an exception with a specific message
                Log::error('Supplier ID missing from the API response.', ['response' => $response]);
                throw new \Exception(__('locale.supplier_creation_error'));
            }
        } else {
            throw new \Exception('Failed to create supplier. No supplier ID returned.');
        }

        return $response;
    }

    public function updateSupplier(array $data)
    {
        $rules = [
            'store_branch_id' => 'required|exists:store_branches,id',
        ];

        // Validate provided data
        $this->validateData($data, $rules);

        // Retrieve StoreBranch and BankAccount models
        $storeBranch = StoreBranch::findOrFail($data['store_branch_id']);
        $storeData = Store::findOrFail($storeBranch->store_id);
        if($storeData->capacity === 'large')
        {
            $capacity_limit = 50000;
        }
        else
        {
            $capacity_limit = 3000; 
        }
        $bankAccount = BankAccount::where('store_id', $storeBranch->store_id)->firstOrFail();

        // Prepare data for the request
        $requestData = [
            'supplierId' => $storeBranch->supplier_id,
            'name' => $storeBranch->store->commercial_name_ar . '-' . $storeBranch->name_ar,
            'iban' => $bankAccount->iban_number,
            'identityNumber' => $storeBranch->identity_number,
            'payoutThresholdAmount' =>  $capacity_limit,
            'email' => $storeBranch->email ?? null,
            'mobile' => isset($storeBranch->dial_code, $storeBranch->contact_no)
                ? $storeBranch->dial_code . $storeBranch->contact_no
                : null,
        ];

        // Send the request to the API
        $this->request('/api/payments/update-supplier', 'post', $requestData);
    }

    public function visitorPayment(array $data)
    {
        try {
            // Validate provided data
            $this->validateData($data, $this->getValidationRules());

            return DB::transaction(function () use ($data) {
                // Retrieve the necessary models
                $order = Order::findOrFail($data['order_id']);
                $storeBranch = StoreBranch::findOrFail($order->store_branch_id);
                $bankAccount = BankAccount::findOrFail($storeBranch->bank_account_id);

                // Generate a unique payment reference ID
                $paymentReferenceId = $this->generateUniquePaymentReferenceID();
                $this->prepareOrderForPayment($order, $paymentReferenceId);

                // Prepare and send the request to the external API
                $response = $this->request('/api/payments/customer-payment', 'post', $this->prepareRequestData($data, $storeBranch, $bankAccount, $paymentReferenceId));
                Log::alert('visitor-payment', $response);
                return $response;
            });
        } catch (\Exception $e) {
            $this->logError($e);
            throw $e;
        }
    }

    public function checkPaymentStatus(Order $order)
    {
        try {
            // Prepare data for the request
            $requestData = [
                'paymentReferenceId' => $order->payment_reference_id,
                'visitorIdentifier' => $order->store_branch->identity_number ?? null,
            ];

            // Send the request to check payment status
            $response = $this->request('/api/payments/customer-payment-status', 'post', $requestData);

            // Log the response
            Log::info('Payment Status Check Response:', ['response' => $response]);

            // Check if the response is an array and not empty
            if (is_array($response) && !empty($response)) {
                $paymentData = $response[0];

                // Validate the structure of the response
                if (!isset($paymentData['paymentStatus']) || !isset($paymentData['visitorIdentifier'])) {
                    throw new \Exception('Invalid response: missing paymentStatus or visitorIdentifier.');
                }

                $paymentStatus = $paymentData['paymentStatus'];
                $responseVisitorIdentifier = $paymentData['visitorIdentifier'];

                // Ensure visitorIdentifier matches the one in the system
                if ($responseVisitorIdentifier === $order->store_branch->identity_number) {

                    // Update the last payment check time
                    $order->update(['last_payment_check_at' => now()]);

                    // Retrieve the configurable time limit for payment status check
                    $timeLimit = DB::table('payment_configurations')
                        ->where('key', 'payment_check_time_limit')
                        ->value('value');

                    // Default to 5 minutes if the time limit is not set
                    $timeLimit = $timeLimit ? intval($timeLimit) : 5;

                    // Check if the order is unpaid and has a status of PENDING_PAYMENT
                    if (!$order->is_paid && $order->status == OrderStatusEnum::PENDING_PAYMENT->value) {
                        if ($paymentStatus == 1) {
                            // Update order as paid and completed
                            $order->update([
                                'is_paid' => true,
                                'status' => OrderStatusEnum::RECEIVED->value,
                            ]);


                            // generate service invoice
                            app(InvoiceController::class)->createInvoice($order->id);


                            // Get the latest device token for the customer
                            $userDevice = $order->customer->fcmTokens()->latest()->first();

                            // Handle the case where no device token is found
                            if ($userDevice) {
                                // Determine locale and create notification content
                                $locale = $userDevice->locale;
                                $orderStatus = OrderStatusEnum::RECEIVED->value;
                                $title = __('locale.api.orders.order_status.title.' . $orderStatus, [], $locale);
                                $body = __('locale.api.orders.order_status.body.' . $orderStatus, [], $locale);

                                // Send Firebase notification
                                app(FirebasePushController::class)->sendFirebaseNotification(new Request([
                                    'user_id' => $order->customer_id,
                                    'title' => $title,
                                    'body' => $body,
                                ]));
                            }

                            Log::info('Order payment received and status updated.', ['order_id' => $order->id]);
                        } elseif ($paymentStatus == 0) {
                            // Check if the configurable time limit has passed since last payment check
                            if (now()->diffInMinutes($order->created_at) >= $timeLimit) {
                                // Cancel the order
                                $order->update([
                                    'status' => OrderStatusEnum::CANCELLED->value,
                                ]);
                                Log::warning("Order payment not received after $timeLimit minutes. Order canceled.", ['order_id' => $order->id]);
                            } else {
                                Log::info("Order is still pending and within the $timeLimit-minute window.", ['order_id' => $order->id]);
                            }
                        }
                    }
                } else {
                    Log::error('Visitor Identifier mismatch.', [
                        'order_id' => $order->id,
                        'expected_visitor_identifier' => $order->store_branch->identity_number,
                        'response_visitor_identifier' => $responseVisitorIdentifier,
                    ]);
                    throw new \Exception('Visitor Identifier mismatch.');
                }
            } else {
                Log::warning('Payment status check returned an empty or invalid response.', ['order_id' => $order->id]);
                throw new \Exception('Payment status check returned an empty or invalid response.');
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('Error during payment status check: ' . $e->getMessage(), [
                'exception' => $e,
                'order_id' => $order->id,
            ]);
            return null;
        }
    }

    public function paymentRefund($endpoint, $method, $data = [])
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'App-key' => $this->appKey,
            'App-id' => $this->appId,
            'ClientId' => $this->clientId,
            'api-version' => $this->apiVersion,
        ])->{$method}($this->baseUrl . $endpoint, $data);

        // Log the request and response
        $this->logRequest($endpoint, $method, $data, $response->json(), $response->successful() ? 'success' : 'error');

        return $response->json();
    }

    public function paymentCapture($endpoint, $method, $data = [])
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'App-key' => $this->appKey,
            'App-id' => $this->appId,
            'ClientId' => $this->clientId,
            'api-version' => $this->apiVersion,
        ])->{$method}($this->baseUrl . $endpoint, $data);

        // Log the request and response
        $this->logRequest($endpoint, $method, $data, $response->json(), $response->successful() ? 'success' : 'error');

        return $response->json();
    }

    protected function getValidationRules()
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:1',
            'returnUrl' => 'nullable|url',
        ];
    }

    protected function prepareRequestData($data, $storeBranch, $bankAccount, $paymentReferenceId)
    {
        return [
            'paymentReferenceId' => $paymentReferenceId,
            'CustomerPayments' => [
                [
                    'name' => $storeBranch->store->commercial_name_ar . '-' . $storeBranch->name_ar,
                    'iban' => $bankAccount->iban_number,
                    'CustomerIdentifier' => $storeBranch->identity_number,
                    'amount' => $data['amount'],
                    'supplierId' => $storeBranch->supplier_id,
                    'returnUrl' => $data['returnUrl'] ?? url('/payment/success'),
                    'IsPreAuth' => true
                ],
            ],
        ];
    }

    protected function request($endpoint, $method, $data = [])
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'App-key' => $this->appKey,
            'App-id' => $this->appId,
            'ClientId' => $this->clientId,
            'api-version' => $this->apiVersion,
        ])->{$method}($this->baseUrl . $endpoint, $data);

        // Log the request and response
        $this->logRequest($endpoint, $method, $data, $response->json(), $response->successful() ? 'success' : 'error');

        return $response->json();
    }

    protected function logRequest($endpoint, $method, $requestData, $responseData, $status)
    {
        ApiDhamenLog::create([
            'action' => $endpoint . ' ' . $method,
            'request_data' => $requestData,
            'response_data' => $responseData,
            'status' => $status,
            'error_message' => $status === 'error' ? json_encode($responseData) : null,
        ]);
    }

    protected function generateUniquePaymentReferenceID()
    {
        do {
            $paymentReferenceID = Str::uuid()->toString();
        } while (Order::where('payment_reference_id', $paymentReferenceID)->exists());

        return $paymentReferenceID;
    }

    private function prepareOrderForPayment(Order $order, string $paymentReferenceId): void
    {
        $order->payment_reference_id = $paymentReferenceId;
        $order->save();
    }

    private function logError(\Exception $e): void
    {
        Log::error('visitor Payment error: ' . $e->getMessage(), ['exception' => $e]);
    }

    ///----------------------------------------------------------------/////
    public function handleNotification(array $notificationData)
    {
        // Validate the notification data
        $rules = [
            'Notifications' => 'required|array',
            'Notifications.*.NotificationId' => 'required|integer',
            'Notifications.*.NotificationType' => 'required|string',
            'Notifications.*.NotificationTime' => 'required',
            'Notifications.*.Supplier' => 'nullable|array',
            'Notifications.*.Transaction' => 'nullable|array',
        ];

        $this->validateData($notificationData, $rules);

        // Process each notification in the array
        foreach ($notificationData['Notifications'] as $notification) {

            // Process notification based on type
            switch ($notification['NotificationType']) {
                case NotificationTypeEnum::DEPOSIT_NOTIFICATION->value:
                    $this->handleDepositNotification($notification);
//                    Log::info('Notification Deposit_Notification', $notification);
                    break;

                case NotificationTypeEnum::FULL_PAYMENT_NOTIFICATION->value:
                    $this->handleFullPaymentNotification($notification);
//                    Log::info('Notification Full_Payment_Notification', $notification);
                    break;

                case NotificationTypeEnum::FUNDS_TRANSFERRING_NOTIFICATION->value:
                    $this->handleFundsTransferringNotification($notification);
//                    Log::info('Notification Funds_Transferring_Notification', $notification);
                    break;

                case NotificationTypeEnum::FAILURE_TRANSFER_NOTIFICATION->value:
                    $this->handleFailureTransferNotification($notification);
//                    Log::info('Notification Failure_Transfer_Notification', $notification);
                    break;

                case NotificationTypeEnum::RESEND_FAILURE_TRANSFER_NOTIFICATION->value:
                    $this->handleResendFailureTransferNotification($notification);
//                    Log::info('Notification Resend_Failure_Transfer_Notification', $notification);
                    break;

                default:
                    Log::warning('Unhandled notification type:', ['notificationData' => $notification]);
                    break;
            }
        }

        return response()->json(['status' => 'SUCCESS']);
    }

    protected function handleDepositNotification(array $notificationData)
    {
        // Process deposit notification

        // Extract data from notification
        $paymentReferenceId = $notificationData['Order']['OrderID'];
        $supplierID = $notificationData['Order']['SupplierID'];

        // Find the order and store branch based on provided IDs
        $order = Order::where('payment_reference_id', $paymentReferenceId)->first();
        $storeBranch = StoreBranch::where('supplier_id', $supplierID)->first();

        // Check if order and store branch exist
        if (!$order) {
            Log::warning('Order not found for payment reference ID:', ['paymentReferenceId' => $paymentReferenceId]);
            return;
        }

        if (!$storeBranch) {
            Log::warning('Store branch not found for supplier ID:', ['supplierID' => $supplierID]);
            return;
        }

        // Check if the store branch ID matches the order's store branch ID
        if ($order->store_branch_id !== $storeBranch->id) {
            Log::warning('Store branch ID mismatch:', [
                'orderStoreBranchId' => $order->store_branch_id,
                'notificationStoreBranchId' => $storeBranch->id
            ]);
            return;
        }

        // Check if the order has already been marked as received or paid
        if ($order->status == OrderStatusEnum::RECEIVED->value || $order->is_paid) {
            return;
        }

        // Update the order to mark it as paid
        $order->is_paid = true;
        $order->status = OrderStatusEnum::RECEIVED->value;
        $order->save();

        // generate service invoice
        app(InvoiceController::class)->createInvoice($order->id);

        // Get the latest device token for the customer
        $userDevice = $order->customer->fcmTokens()->latest()->first();

        // Handle the case where no device token is found
        if (!$userDevice) {
            return response()->json(['error' => 'User device not found'], 404);
        }

        // Determine locale and create notification content
        $locale = $userDevice->locale;
        $orderStatus = OrderStatusEnum::RECEIVED->value;
        $title = __('locale.api.orders.order_status.title.' . $orderStatus, [], $locale);
        $body = __('locale.api.orders.order_status.body.' . $orderStatus, [], $locale);

        // Send Firebase notification
        app(FirebasePushController::class)->sendFirebaseNotification(new Request([
            'user_id' => $order->customer_id,
            'title' => $title,
            'body' => $body,
        ]));
    }


    protected function handleFullPaymentNotification(array $notificationData)
    {
        // Process full payment notification
//        Log::info('Handling Full Payment Notification:', ['notificationData' => $notificationData]);

//        $order = Order::where('payment_reference_id', $notificationData['Order']['OrderID'])->first();
//        $order->is_paid = 1;
//        $order->save();
        // Example: Update the relevant record or log the notification
        // Your logic here
    }

    protected function handleFundsTransferringNotification(array $notificationData)
    {
        // Process funds transferring notification
//        Log::info('Handling Funds Transferring Notification:', ['notificationData' => $notificationData]);

        // Example: Update the relevant record or log the notification
        // Your logic here
    }

    protected function handleFailureTransferNotification(array $notificationData)
    {
        // Process failure transfer notification
//        Log::info('Handling Failure Transfer Notification:', ['notificationData' => $notificationData]);

        // Example: Update the relevant record or log the notification
        // Your logic here
    }

    protected function handleResendFailureTransferNotification(array $notificationData)
    {
        // Process resend failure transfer notification
//        Log::info('Handling Resend Failure Transfer Notification:', ['notificationData' => $notificationData]);

        // Example: Update the relevant record or log the notification
        // Your logic here
    }
}
