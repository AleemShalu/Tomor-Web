<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerVehicleRequest;
use App\Http\Resources\CustomerVehicleResource;
use App\Models\CustomerVehicle;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CustomerVehicleController extends Controller
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
            'customer_id' => 'required|numeric|exists:App\Models\User,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $customerVehicles = CustomerVehicle::with([
            'customer',
        ])->where('customer_id', request('customer_id'))
            ->paginate(request('limit', 10));

        return CustomerVehicleResource::collection($customerVehicles);
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

        // check if user is authorized to use the resource
        // if ($request->user()->store_id != $request->store_id) {
        //     return response()->json([
        //         "message" => __('locale.api.errors.user_is_forbidden_from_resource'),
        //         "code" => "FORBIDDEN_ERROR"
        //     ], Response::HTTP_FORBIDDEN);
        // }

        // validate request fields
        $customerVehicleValidationRules = new CustomerVehicleRequest();
        $validator = Validator::make($request->all(), $customerVehicleValidationRules->rules());

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        // find if customer reached the limit of storing the address
        if (CustomerVehicle::where('customer_id', request('customer_id'))->count() >= env('FRONT_CUSTOMER_VEHICLE_LIMIT', 5)) {
            return response()->json([
                "message" => __('locale.api.customer.vehicles.customer_vehicles_limit_error'),
                "code" => "VEHICLE_LIMIT_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            # id, customer_id, vehicle_manufacturer, vehicle_name, vehicle_model_year,
            // vehicle_color, vehicle_plate_number, vehicle_plate_letters_ar,
            // vehicle_plate_letters_en, default_vehicle, created_at, updated_at

            // Create new instance of customer vehicle
            $customerVehicle = new CustomerVehicle();
            $customerVehicle->customer_id = request('customer_id');
            $customerVehicle->vehicle_manufacturer = request('vehicle_manufacturer') ? Str::upper(request('vehicle_manufacturer')) : null;
            $customerVehicle->vehicle_name = request('vehicle_name') ? Str::upper(request('vehicle_name')) : null;
            $customerVehicle->vehicle_model_year = request('vehicle_model_year', null);
            $customerVehicle->vehicle_color = request('vehicle_color', null);
            $customerVehicle->vehicle_plate_number = request('vehicle_plate_number', null);
//            $customerVehicle->vehicle_plate_letters_ar = request('vehicle_plate_letters_ar', null);
            $customerVehicle->vehicle_plate_letters_en = request('vehicle_plate_letters_en') ? Str::upper(request('vehicle_plate_letters_en')) : null;
            $customerVehicle->default_vehicle = request('default_vehicle', 0);
            $customerVehicle->save();

            // Change `default_vehicle` status for all list
            if (request('default_vehicle', 0) == 1) {
                CustomerVehicle::query()
                    ->where('customer_id', request('customer_id'))
                    ->where('id', '!=', $customerVehicle->id) // Exclude current row
                    ->update(['default_vehicle' => 0]);
            }

            $customerVehicle = CustomerVehicle::with([
                'customer',
            ])->find($customerVehicle->id);

            return response()->json([
                "customer_vehicle" => CustomerVehicleResource::make($customerVehicle),
                "message" => __('locale.api.alert.model_created_successfully', ['model' => 'Customer Vehicle']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:CustomerVehicleController:store: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
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
            // 'customer_id' => 'required|numeric|exists:App\Models\Customer,id',
            'customer_vehicle_id' => 'required|numeric|exists:App\Models\CustomerVehicle,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $customerVehicle = CustomerVehicle::with([
                'customer',
            ])->findOrFail(request('customer_vehicle_id'));

            return new CustomerVehicleResource($customerVehicle);

        } catch (QueryException $e) {
            Log::error('API:CustomerVehicleController:show: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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

        // find customer vehicle first
        $customerVehicle = CustomerVehicle::findOrFail($id);

        // validate request fields
        $customerVehicleValidationRules = new CustomerVehicleRequest();
        $validator = Validator::make($request->all(), $customerVehicleValidationRules->rules($id));

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            // update existing instance of customer vehicle
            $customerVehicle->vehicle_manufacturer = request('vehicle_manufacturer') ? Str::upper(request('vehicle_manufacturer')) : null;
            $customerVehicle->vehicle_name = request('vehicle_name') ? Str::upper(request('vehicle_name')) : null;
            $customerVehicle->vehicle_model_year = request('vehicle_model_year', null);
            $customerVehicle->vehicle_color = request('vehicle_color', null);
            $customerVehicle->vehicle_plate_number = request('vehicle_plate_number', null);
//            $customerVehicle->vehicle_plate_letters_ar = request('vehicle_plate_letters_ar', null);
            $customerVehicle->vehicle_plate_letters_en = request('vehicle_plate_letters_en') ? Str::upper(request('vehicle_plate_letters_en')) : null;
            $customerVehicle->default_vehicle = request('default_vehicle', 0);
            $customerVehicle->save();

            // Change `default_vehicle` status for all records
            if (request('default_vehicle', 0) == 1) {
                CustomerVehicle::query()
                    ->where('customer_id', request('customer_id'))
                    ->where('id', '!=', $customerVehicle->id) // exclude current row
                    ->update(['default_vehicle' => 0]);
            }

            $customerVehicle = CustomerVehicle::with([
                'customer',
            ])->find($customerVehicle->id);

            return response()->json([
                "customer_vehicle" => CustomerVehicleResource::make($customerVehicle),
                "message" => __('locale.api.alert.model_updated_successfully', ['model' => 'Customer Vehicle']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:CustomerVehicleController:update: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
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
            'customer_vehicle_id' => 'required|numeric|exists:App\Models\CustomerVehicle,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $customerVehicle = CustomerVehicle::findOrFail(request('customer_vehicle_id'));
            $customerVehicle->delete(); // delete the customer vehicle from DB

            return response()->json([
                "customer_vehicle" => [],
                "message" => __('locale.api.alert.model_deleted_successfully', ['model' => 'Customer Vehicle']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:CustomerVehicleController:destroy: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
