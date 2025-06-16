<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StorePromoterResource;
use App\Models\StorePromoter;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StorePromoterController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // validate user credentials
        $user = $request->user();
        if (!$user) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {
            // Set the timezone to Asia/Riyadh
            Carbon::setLocale('en');
            Carbon::setToStringFormat('Y-m-d H:i:s');

            $currentDateTime = Carbon::now('Asia/Riyadh');

            $storePromoters = StorePromoter::where('status', true)
                ->where('end_date', '>', $currentDateTime)
                ->orderBy('id')
                ->get();

            if ($storePromoters->count()) {
                return response()->json([
                    "store_promoters" => StorePromoterResource::collection($storePromoters),
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "message" => "No active promoters found.",
                    "code" => "ACTIVE_PROMOTERS_NOT_FOUND",
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            Log::error('API:StorePromoterController:index: ' . $e->getMessage());
            return response()->json([
                "message" => "Error fetching store promoters.",
                "code" => "ERROR_FETCHING_DATA",
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(StorePromoter $storePromoter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StorePromoter $storePromoter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StorePromoter $storePromoter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StorePromoter $storePromoter)
    {
        //
    }
}
