<?php

namespace App\Http\Requests;

use Intervention\Validation\Rules\Iban;
use Illuminate\Foundation\Http\FormRequest;

class BankAccountRequest extends FormRequest
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
            'store_id' => 'required|numeric|exists:App\Models\Store,id',
            'account_holder_name' => 'required|string|min:1|max:50',
            'iban_number' => ['required', 'string', new Iban()],
            'bank_name' => 'required|string|min:1|max:50',
            'swift_code' => 'nullable|string|min:8|max:11',
        ];
    }
}
