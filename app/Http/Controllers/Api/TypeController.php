<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessTypeCollection;
use App\Http\Resources\CityCollection;
use App\Http\Resources\CountryCollection;
use App\Http\Resources\CouponTypeCollection;
use App\Http\Resources\DiscountTypeCollection;
use App\Http\Resources\LanguageCollection;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\SpecialNeedsTypeCollection;
use App\Models\BusinessType;
use App\Models\City;
use App\Models\Country;
use App\Models\CouponType;
use App\Models\DiscountType;
use App\Models\Language;
use App\Models\SpecialNeedsType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends Controller
{
    public function getBusinessTypes(Request $request)
    {
//        // validate user credentials
//        if (!$request->user()) {
//            return response()->json([
//                "message" => __('auth.failed'),
//                "code" => "AUTHENTICATION_ERROR"
//            ], Response::HTTP_UNAUTHORIZED);
//        }

        try {
            $businessTypes = BusinessType::orderBy('id')->get();
            if ($businessTypes->count()) {
                return response()->json([
                    "business_types" => BusinessTypeCollection::make($businessTypes),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            Log::error('API:TypeController:getBusinessTypes: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getCountries(Request $request)
    {
//        // validate user credentials
//        if (!$request->user()) {
//            return response()->json([
//                "message" => __('auth.failed'),
//                "code" => "AUTHENTICATION_ERROR"
//            ], Response::HTTP_UNAUTHORIZED);
//        }

        try {
            $countries = Country::orderBy('id')->get();
            if ($countries->count()) {
                return response()->json([
                    "countries" => CountryCollection::make($countries),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            Log::error('API:TypeController:getCountries: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getCities(Request $request)
    {
//        // validate user credentials
//        if (!$request->user()) {
//            return response()->json([
//                "message" => __('auth.failed'),
//                "code" => "AUTHENTICATION_ERROR"
//            ], Response::HTTP_UNAUTHORIZED);
//        }

        try {
            $cities = City::orderBy('id')->get();
            if ($cities->count()) {
                return response()->json([
                    "cities" => CityCollection::make($cities),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            Log::error('API:TypeController:getCities: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getLanguages(Request $request)
    {
//        // validate user credentials
//        if (!$request->user()) {
//            return response()->json([
//                "message" => __('auth.failed'),
//                "code" => "AUTHENTICATION_ERROR"
//            ], Response::HTTP_UNAUTHORIZED);
//        }

        try {
            $languages = Language::orderBy('id')->get();
            if ($languages->count()) {
                return response()->json([
                    "languages" => LanguageCollection::make($languages),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            Log::error('API:TypeController:getLanguages: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getCouponTypes(Request $request)
    {
//        // validate user credentials
//        if (!$request->user()) {
//            return response()->json([
//                "message" => __('auth.failed'),
//                "code" => "AUTHENTICATION_ERROR"
//            ], Response::HTTP_UNAUTHORIZED);
//        }

        try {
            $couponTypes = CouponType::orderBy('id')->get();
            if ($couponTypes->count()) {
                return response()->json([
                    "coupon_types" => CouponTypeCollection::make($couponTypes),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            Log::error('API:TypeController:getCouponTypes: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getDiscountTypes(Request $request)
    {
//        // validate user credentials
//        if (!$request->user()) {
//            return response()->json([
//                "message" => __('auth.failed'),
//                "code" => "AUTHENTICATION_ERROR"
//            ], Response::HTTP_UNAUTHORIZED);
//        }

        try {
            $discountTypes = DiscountType::orderBy('id')->get();
            if ($discountTypes->count()) {
                return response()->json([
                    "discount_types" => DiscountTypeCollection::make($discountTypes),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            Log::error('API:TypeController:getDiscountTypes: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getSpecialNeedsTypes(Request $request)
    {
        // validate user credentials
        // if (!$request->user()) {
        //     return response()->json([
        //         "message" => __('auth.failed'),
        //         "code" => "AUTHENTICATION_ERROR"
        //     ], Response::HTTP_UNAUTHORIZED);
        // }

        try {
            $specialNeedTypes = SpecialNeedsType::orderBy('id')->get();
            if ($specialNeedTypes->count()) {
                return response()->json([
                    "special_needs_types" => SpecialNeedsTypeCollection::make($specialNeedTypes),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            Log::error('API:TypeController:getSpecialNeedsTypes: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getEmployeeRoles(Request $request)
    {
//        // validate user credentials
//        if (!$request->user()) {
//            return response()->json([
//                "message" => __('auth.failed'),
//                "code" => "AUTHENTICATION_ERROR"
//            ], Response::HTTP_UNAUTHORIZED);
//        }

        try {
            $roles = Role::whereIn('name', ['worker', 'worker_supervisor'])->orderBy('id')->get();
            if ($roles->count()) {
                return response()->json([
                    "roles" => RoleCollection::make($roles),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            Log::error('API:TypeController:getEmployeeRoles: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
