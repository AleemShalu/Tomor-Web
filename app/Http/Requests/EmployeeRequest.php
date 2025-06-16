<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules($id = null): array
    {
        return [
            'store_id' => ['required','numeric', 'exists:App\Models\Store,id'],
            'role_id' => ['required', 'numeric', 'exists:roles,id'],
            'name' => ['required', 'min:1', 'max:100'],
            'password' => ['required', 'string', 'confirmed', new Password],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:100', Rule::unique('users')->ignore($id)],
            'store_branch_id' => ['required', 'numeric', 'exists:store_branches,id'],
            'dial_code' => 'required|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'min:9',
                'max:15',
                'regex:/^[0-9]+$/',
                Rule::unique('users')->where(fn($query) => $query->where('dial_code', request('dial_code', null)))->ignore($id),
            ],
            'status' => [
                'nullable',
                Rule::in([0, 1]),
            ],
        ];
    }
}
