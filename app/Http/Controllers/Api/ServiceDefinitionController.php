<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceDefinitionResource;
use App\Models\Coupon;
use App\Models\CustomerWithSpecialNeeds;
use App\Models\ServiceDefinition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ServiceDefinitionController extends Controller
{
    public function show(Request $request, $service_definition_id): JsonResponse|ServiceDefinitionResource
    {
        // Validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], 401); // HTTP_UNAUTHORIZED
        }

        // Validate request fields
        $validator = Validator::make(
            [
                'service_definition_id' => $service_definition_id,
                'customer_id' => request('customer_id'),
            ],
            [
            'customer_id' => 'required|numeric|exists:App\Models\User,id',
            'service_definition_id' => 'required|numeric|exists:App\Models\ServiceDefinition,id',
        ]);

        // Respond if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], 400); // HTTP_BAD_REQUEST
        }

        $serviceDefinition = ServiceDefinition::find($service_definition_id);
        // $serviceDefinition->price = number_format((float)$serviceDefinition->price, 2, '.', '');
        // $serviceDefinition->rate = number_format((float)$serviceDefinition->rate, 2, '.', '');

        $customer_with_special_needs = CustomerWithSpecialNeeds::where('customer_id', request('customer_id'))->first();
        if ($customer_with_special_needs && $customer_with_special_needs->special_needs_qualified == 1) {
            // merge new field discount_percentage to the service definition instance
            $serviceDefinition->discount_percentage = 100;
            return new ServiceDefinitionResource($serviceDefinition);
        }

        if (request('coupon_code')) {
            // Log::info('Coupon code found in request: ' . $request->coupon_code);

            // Validate the coupon code
            $couponValidator = Validator::make($request->all(), [
                'coupon_code' => 'required|exists:App\Models\Coupon,code',
            ]);

            // Respond if validation fails
            if ($couponValidator->fails()) {
                return response()->json([
                    "data" => new ServiceDefinitionResource($serviceDefinition),
                    "message" => $couponValidator->errors()->messages(),
                    "code" => "COUPON_VALIDATION_ERROR"
                ], 400); // HTTP_BAD_REQUEST
            }


            // Retrieve coupon
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            // Log::info('Coupon retrieved from database: ' . json_encode($coupon));

            // Check if the user has already used this coupon
            $userCouponUsage = $coupon->users()->where('user_id', request('customer_id'))->first();

            // Check if the user has exceeded the max uses per user
            if ($userCouponUsage && $userCouponUsage->pivot->usage_count >= $coupon->max_uses_per_user) {
                return response()->json([
                    "data" => new ServiceDefinitionResource($serviceDefinition),
                    "message" => __('locale.api.orders.coupons.max_usage_for_single_user_reached'),
                    "code" => "COUPON_MAX_USAGE_EXCEEDED"
                ], 400); // HTTP_BAD_REQUEST
            }

            // Check coupon validity and apply discount
            if ($coupon && $coupon->enabled == 1) {
                // Log::info('Coupon is valid. Original price: ' . $serviceDefinition->price . ' Discount Amount: ' . $coupon->discount_percentage);
                // merge new field discount_percentage to the service definition instance
                $serviceDefinition->discount_percentage = $coupon->discount_percentage ?? 0;
            }
        }
        return new ServiceDefinitionResource($serviceDefinition);
    }

}
