<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerBankCardsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules($id = null): array
    {
        return [
            'customer_id' => 'required|numeric|exists:users,id',
            'card_holder_name' => 'required|string|min:1|max:50',
            'card_number' => 'required|numeric|digits:16|unique:bank_cards,card_number,' . $id,
            'card_expiry_year' => 'required|numeric|digits:2',
            'card_expiry_month' => 'required|numeric|digits:2',
            'card_cvv' => 'required|numeric|digits:3',
            'default_card' => [
                'nullable',
                Rule::in([0, 1]),
            ],
        ];
    }
}
