<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // check if user is authorized to use the resource
        // if ($request->user()->store_id != $request->store_id) {
        //     return response()->json([
        //         "message" => __('locale.api.errors.user_is_forbidden_from_resource'),
        //         "code" => "FORBIDDEN_ERROR"
        //     ], Response::HTTP_FORBIDDEN);
        // }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|numeric|exists:App\Models\User,id',
            'currency_code' => 'required|string|in:SAR',
            // 'currency_code'=> 'required|string|exists:App\Models\Currency,code',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $listOfCoupons = Coupon::with([
            'coupon_type',
            'discount_type',
            'stores',
        ])->when(request('customer_id'), function ($query) {
            return $query->whereHas('customer', function ($query) {
                $query->where('id', request('customer_id'));
            });
        })->orderby('order_date', 'desc')
        ->paginate(request('limit', 10));

        return CouponResource::collection($listOfCoupons);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function couponsForStores(Request $request)
    {
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // check if user is authorized to use the resource
        // if ($request->user()->store_id != $request->store_id) {
        //     return response()->json([
        //         "message" => __('locale.api.errors.user_is_forbidden_from_resource'),
        //         "code" => "FORBIDDEN_ERROR"
        //     ], Response::HTTP_FORBIDDEN);
        // }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'currency_code' => 'required|string|in:SAR',
            // 'currency_code'=> 'required|string|exists:App\Models\Currency,code',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $listOfCouponsForStores = Coupon::with([
            'coupon_type',
            'discount_type',
            'stores',
        ])->whereHas('stores', function ($query) {
            $query->where('status', 1); // only active stores.
        })->whereHas('coupon_type', function ($query) {
            $query->where('code', 'STOR_COUP'); // fetch only coupons with type 'store'
        })
        // ->where('enabled', 1) // fetch only enabled coupons
        ->orderby('end_date', 'desc')
        ->paginate(request('limit', 10));

        return CouponResource::collection($listOfCouponsForStores);
    }
}
