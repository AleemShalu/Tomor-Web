<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Returns time intervals.
 *
 * @param $data
 * @param \Illuminate\Support\Carbon $endDate
 *
 * @return array
 */

if (!function_exists('getJsonResponse')) {
    function getJsonResponse($data, ?string $code, int $status): JsonResponse
    {
        return response()->json(["message" => $data, "code" => $code], $status);
    }
}

if (!function_exists('getPaymentConfig')) {
    function getPaymentConfig($key, $default = null)
    {
        $config = DB::table('payment_configurations')->where('key', $key)->first();
        return $config ? $config->value : $default;
    }
}