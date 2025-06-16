<?php

namespace App\Http\Controllers\Api;

use App\Models\BankAccount;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\BankAccountRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BankAccountResource;
use Symfony\Component\HttpFoundation\Response;

class BankAccountController extends Controller
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
        if ($request->user()->owner_stores()->where('id', $request->store_id)->doesntExist()) {
            return response()->json([
                "message" => __('locale.api.errors.user_is_forbidden_from_resource'),
                "code" => "FORBIDDEN_ERROR"
            ], Response::HTTP_FORBIDDEN);
        }

        // validate request fields
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR"
            ], Response::HTTP_BAD_REQUEST);
        }

        $list_of_store_bank_acccounts = BankAccount::with([
            'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
        ])->where('store_id', request('store_id'))
        ->orderBy('created_at', 'desc')
        ->get();

        return BankAccountResource::collection($list_of_store_bank_acccounts);
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

        $bankAccountRequest = new BankAccountRequest();
        $validator = Validator::make($request->all(), $bankAccountRequest->rules());

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            //  Create new instance of Bank Account
            $bankAccount = new BankAccount();
            $bankAccount->account_holder_name = request('account_holder_name');
            $bankAccount->iban_number = request('iban_number');
            $bankAccount->bank_name = request('bank_name');
            $bankAccount->swift_code = Str::upper(request('swift_code'), null);
            // $bankAccount->store_id = auth()->user()->store_id;
            $bankAccount->store_id = request('store_id', auth()->user()->store_id);
            $bankAccount->save();

            $bankAccount = BankAccount::with([
                'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
            ])->findOrFail($bankAccount->id);

            return response()->json([
                'bank_account' => BankAccountResource::make($bankAccount),
                'message' => __('locale.api.alert.model_created_successfully', ['model' => 'Bank Account']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:BankAccountController:store: ' . $e->getMessage());
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
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
            'bank_account_id' => 'required|numeric|exists:App\Models\BankAccount,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $bankAccount = BankAccount::with([
                'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
            ])->findOrFail($request->bank_account_id);

            return new BankAccountResource($bankAccount);

        } catch (QueryException $e) {
            Log::error('API:BankAccountController:show: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
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

        // find the bank account first
        $bankAccount = BankAccount::findOrFail($id);

        $bankAccountValidationRules = new BankAccountRequest();
        $validator = Validator::make($request->all(), $bankAccountValidationRules->rules($id));

        // validate form fields and return error message to request
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            //  Update instance of BankAccount
            $bankAccount->account_holder_name = request('account_holder_name');
            $bankAccount->iban_number = request('iban_number');
            $bankAccount->bank_name = request('bank_name');
            $bankAccount->swift_code = Str::upper(request('swift_code'), null);
            // $bankAccount->store_id = auth()->user()->store_id;
            $bankAccount->store_id = request('store_id', auth()->user()->store_id);
            $bankAccount->save();

            $bankAccount = bankAccount::with([
                'store:id,commercial_name_en,commercial_name_ar,short_name_en,short_name_ar,email',
            ])->findOrFail($bankAccount->id);

            return response()->json([
                'bank_account' => BankAccountResource::make($bankAccount),
                'message' => __('locale.api.alert.model_updated_successfully', ['model' => 'Bank Account']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:BankAccountController:update: ' . $e->getMessage());
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
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
            'bank_account_id' => 'required|numeric|exists:App\Models\BankAccount,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $bankAccount = BankAccount::findOrFail(request('bank_account_id'));
            $bankAccount->delete(); // delete the Bank Account from DB

            return response()->json([
                'bank_account' => [],
                'message' => __('locale.api.alert.model_deleted_successfully', ['model' => 'Bank Account']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:BankAccountController:destroy: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
