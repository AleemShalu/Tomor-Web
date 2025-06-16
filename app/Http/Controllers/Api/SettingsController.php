<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\LocationConfig;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\LocationConfigResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    public function getLocationConfig(Request $request)
    {
//        // validate user credentials
//        if (!$request->user()) {
//            return response()->json([
//                "message" => __('auth.failed'),
//                "code" => "AUTHENTICATION_ERROR"
//            ], Response::HTTP_UNAUTHORIZED);
//        }

        try {
            $LocationConfigs = LocationConfig::orderBy('id')->get();
            if ($LocationConfigs->count()) {
                return response()->json([
                    "location_configs" => LocationConfigResource::collection($LocationConfigs),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (QueryException $e) {
            Log::error('API:SettingsController:getLocationConfig: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getCustomerLocationConfig(Request $request)
    {
//        // validate user credentials
//        if (!$request->user()) {
//            return response()->json([
//                "message" => __('auth.failed'),
//                "code" => "AUTHENTICATION_ERROR"
//            ], Response::HTTP_UNAUTHORIZED);
//        }

        try {
            $customerLocationConfigs = LocationConfig::where('code', 'CR')->first();
            if ($customerLocationConfigs) {
                return response()->json([
                    "data" => LocationConfigResource::make($customerLocationConfigs),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (QueryException $e) {
            Log::error('API:SettingsController:getCustomerLocationConfig: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function getBranchLocationConfig(Request $request)
    {
//        // validate user credentials
//        if (!$request->user()) {
//            return response()->json([
//                "message" => __('auth.failed'),
//                "code" => "AUTHENTICATION_ERROR"
//            ], Response::HTTP_UNAUTHORIZED);
//        }

        try {
            $branchLocationConfigs = LocationConfig::where('code', 'BR')->first();
            if ($branchLocationConfigs) {
                return response()->json([
                    "data" => LocationConfigResource::make($branchLocationConfigs),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => __('locale.api.errors.not_found_record_message'),
                    "code" => "RECORD_NOT_FOUND",
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (QueryException $e) {
            Log::error('API:SettingsController:getBranchLocationConfig: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
