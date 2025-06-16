<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRatingRequest;
use App\Http\Resources\OrderRatingResource;
use App\Models\OrderRating;
use App\Models\OrderRatingType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class OrderRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // validate user credentials
        if (!$request->user()) {
            return response()->json([
                "message" => __('auth.failed'),
                "code" => "AUTHENTICATION_ERROR"
            ], Response::HTTP_UNAUTHORIZED);
        }

        // validate request fields
        $OrderRatingValidationRules = new OrderRatingRequest();
        $validator = Validator::make($request->all(), $OrderRatingValidationRules->rules());

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Check if the customer has already rated this order
            $existingRating = OrderRating::where('order_id', request('order_id'))
                ->where('customer_id', request('customer_id'))
                ->first();

            if ($existingRating) {
                return response()->json([
                    "message" => "You have already rated this order.",
                    "code" => "DUPLICATE_RATING",
                ], Response::HTTP_CONFLICT);
            }

            $orderRatingType = OrderRatingType::where('code', request('order_rating_type_code'))->first();
            // Create new instance
            $orderRating = new OrderRating();
            $orderRating->store_id = request('store_id');
            $orderRating->order_id = request('order_id');
            $orderRating->customer_id = request('customer_id');
            $orderRating->order_rating_type_id = $orderRatingType->id;
            $orderRating->body_massage = request('body_massage', null);
            $orderRating->rating = request('rating');
            $orderRating->save();


            // Assuming you have the $orderRatingId and $orderId available
            $orderRating = OrderRating::with([
                'customer', 'order_rating_type', 'order', 'store',
            ])->find($orderRating->id);

            if (!$orderRating) {
                return response()->json([
                    "message" => "OrderRating not found",
                ], Response::HTTP_NOT_FOUND);
            }

            // Check if the order relationship is loaded
            if (!$orderRating->order) {
                return response()->json([
                    "message" => "Order not found for the provided OrderRating",
                ], Response::HTTP_NOT_FOUND);
            }

            // Get the associated order's store_branch_id
            $storeBranchId = $orderRating->order->store_branch_id;

            // Check if the store relationship is loaded
            if (!$orderRating->store) {
                return response()->json([
                    "message" => "Store not found for the provided OrderRating",
                ], Response::HTTP_NOT_FOUND);
            }

            // Remove the "branches" information from the store object
            $orderRating->store->makeHidden('branches');

            // Get the associated store branch based on store_branch_id
            $storeBranch = $orderRating->store->branches->firstWhere('id', $storeBranchId);

            if (!$storeBranch) {
                return response()->json([
                    "message" => "Store branch not found for the provided store_branch_id",
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                "customer_order_rating" => OrderRatingResource::make($orderRating),
                "store_branch" => $storeBranch, // Include the specific store branch
                "message" => __('locale.api.alert.model_created_successfully', ['model' => 'Customer Order Rating']),
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            Log::error('API:OrderRatingController:store: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
}
