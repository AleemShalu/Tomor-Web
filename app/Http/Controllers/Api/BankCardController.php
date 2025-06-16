<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerBankCardsRequest;
use App\Http\Resources\BankCardResource;
use App\Models\BankCard;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;


class BankCardController extends Controller
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

        // // validate request fields
        // $validator = Validator::make($request->all(), [
        //     'customer_id' => 'required|numeric|exists:App\Models\User,id',
        // ]);

        // // redirect if validation fails
        // if ($validator->fails()) {
        //     return response()->json([
        //         "message" => $validator->errors()->messages(),
        //         "code" => "VALIDATION_ERROR"
        //     ], Response::HTTP_BAD_REQUEST);
        // }

        $customerBankCards = BankCard::with([
            'customer',
        ])->where('customer_id', auth()->user()->id)
            ->get();

        return BankCardResource::collection($customerBankCards);
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
        $customercustomerBankCardsValidationRules = new CustomerBankCardsRequest();
        $validator = Validator::make($request->all(), $customercustomerBankCardsValidationRules->rules());
        $customerID = auth()->user()->id; // Get the customer's ID from the authenticated user

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Create new instance of customer cards
            $customerBankCard = new BankCard();
            $customerBankCard->customer_id = $customerID;
            $customerBankCard->card_holder_name = request('card_holder_name') ? Str::upper(request('card_holder_name')) : null;
            $customerBankCard->card_number = request('card_number', null);
            $customerBankCard->card_expiry_year = request('card_expiry_year', null);
            $customerBankCard->card_expiry_month = request('card_expiry_month', null);
            $customerBankCard->card_cvv = request('card_cvv', null);
            $customerBankCard->default_card = request('default_card', 0);
            $customerBankCard->save();

            // Change `default_card` status for the current customer's cards
            if (request('default_card', 0) == 1) {
                BankCard::query()
                    ->where('customer_id', $customerID) // Filter by customer ID
                    ->where('id', '!=', $customerBankCard->id) // Exclude current row
                    ->update(['default_card' => 0]);
            }


            $customerBankCard = BankCard::with([
                'customer',
            ])->find($customerBankCard->id);

            return response()->json([
                "customer_bank_card" => BankCardResource::make($customerBankCard),
                "message" => __('locale.api.alert.model_created_successfully', ['model' => 'Bank Card']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:BankCardController:store: ' . $e->getMessage());
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

        // validate request fields
        $validator = Validator::make($request->all(), [
            'bank_card_id' => 'required|numeric|exists:App\Models\BankCard,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $customerBankCard = BankCard::with([
                'customer',
            ])->findOrFail(request('bank_card_id'));

            return new BankCardResource($customerBankCard);

        } catch (QueryException $e) {
            Log::error('API:BankCardController:show: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        // Get the customer ID from the authenticated user
        $customerID = $request->input('customer_id');

        // Get the bank card to be updated
        $bankCardID = $id;

        // Validate if the bank card belongs to the current customer
        $bankCard = BankCard::where('customer_id', $customerID)
            ->where('id', $bankCardID)
            ->first();

        if (!$bankCard) {
            return response()->json([
                "message" => "Bank card not found for the current customer.",
                "code" => "NOT_FOUND",
            ], Response::HTTP_NOT_FOUND);
        }


        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|numeric|exists:users,id',
            'card_holder_name' => 'required|string|min:1|max:50',
            'card_number' => [
                'required',
                'numeric',
                'digits:16',
                Rule::unique('bank_cards', 'card_number')->ignore($bankCard->id)->when($request->input('card_number') == $bankCard->card_number, function ($query) {
                    return $query->where('id', '!=', null);
                }),
            ],
            'card_expiry_year' => 'required|numeric|digits:2',
            'card_expiry_month' => 'required|numeric|digits:2',
            'card_cvv' => 'required|numeric|digits:3',
            'default_card' => [
                'nullable',
                Rule::in([0, 1]),
            ],
        ]);

        // Redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Update the bank card fields
            $bankCard->card_number = $request->input('card_number', $bankCard->card_number);
            $bankCard->card_holder_name = $request->input('card_holder_name') ? Str::upper(request('card_holder_name')) : $bankCard->card_holder_name;
            $bankCard->card_expiry_year = $request->input('card_expiry_year', $bankCard->card_expiry_year);
            $bankCard->card_expiry_month = $request->input('card_expiry_month', $bankCard->card_expiry_month);
            $bankCard->card_cvv = $request->input('card_cvv', $bankCard->card_cvv);
            $bankCard->save();

            // Change `default_card` status for the current customer's cards
            if (request('default_card', 0) == 1) {
                // Set the current card as the default
                $bankCard->default_card = 1;
                $bankCard->save();

                // Set all other cards for the current customer to not be the default
                BankCard::query()->where('customer_id', $customerID) // Filter by customer ID
                    ->where('id', '!=', $bankCard->id) // Exclude current row
                    ->update(['default_card' => 0]);

            } else if (request('default_card') == 0) {
                // Set the current card as the default
                $bankCard->default_card = 0;
                $bankCard->save();
            }

            return response()->json([
                "message" => "Bank card updated successfully.",
                "code" => "SUCCESS",
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:BankCardController:update: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }
//    public function update_default_bank_card(Request $request)
//    {
//        // Get the customer ID from the authenticated user
//        $customerID = auth()->user()->id;
//
//        // Get the bank card to be set as the default
//        $bankCardID = $request->input('bank_card_id');
//
//        // Validate if the bank card belongs to the current customer
//        $bankCard = BankCard::where('customer_id', $customerID)
//            ->where('id', $bankCardID)
//            ->first();
//
//        if (!$bankCard) {
//            return response()->json([
//                "message" => "Bank card not found for the current customer.",
//                "code" => "NOT_FOUND",
//            ], Response::HTTP_NOT_FOUND);
//        }
//
//        // Update the default_card status for the current customer's cards
//        BankCard::where('customer_id', $customerID)
//            ->update(['default_card' => 0]);
//
//        // Set the selected bank card as the default
//        $bankCard->update(['default_card' => 1]);
//
//        return response()->json([
//            "message" => "Default bank card updated successfully.",
//            "data" => new BankCardResource($bankCard),
//            "code" => "SUCCESS",
//        ], Response::HTTP_OK);
//    }


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

        // validate request fields
        $validator = Validator::make($request->all(), [
            'bank_card_id' => 'required|numeric|exists:App\Models\BankCard,id',
        ]);

        // redirect if validation fails
        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->messages(),
                "code" => "VALIDATION_ERROR",
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $customerBankCard = BankCard::findOrFail(request('bank_card_id'));
            $customerBankCard->delete(); // delete the customer bank card from DB

            return response()->json([
                "bank_card" => [],
                "message" => __('locale.api.alert.model_deleted_successfully', ['model' => 'Bank Card']),
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            Log::error('API:BankCardController:destroy: ' . $e->getMessage());
            return response()->json($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
