<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequestWeb extends FormRequest
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
            'business_type_id' => 'required|numeric|exists:App\Models\BusinessType,id',
            'commercial_name_en' => 'required|string|min:1|max:100',
            'commercial_name_ar' => 'required|string|min:1|max:100|regex:/^[\p{Arabic} ]+$/u',
            'short_name_en' => 'required|string|min:1|max:20',
            'short_name_ar' => 'required|string|min:1|max:20|regex:/^[\p{Arabic} ]+$/u',
            'email' => 'email:rfc|max:100|unique:App\Models\Store,email,' . $id,
            'country_id' => 'required|numeric|exists:App\Models\Country,id',
            'dial_code' => 'nullable|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'max:15',
//                'regex:/^[0-9]+$/',
                Rule::unique('stores')->where(fn($query) => $query->where('dial_code', request('dial_code', null)))->ignore($id),
            ],
            'secondary_dial_code' => 'nullable|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'secondary_contact_no' => [
                'nullable',
                'max:15',
                'regex:/^[0-9]+$/',
                Rule::unique('stores')->where(fn($query) => $query->where('secondary_dial_code', request('secondary_dial_code', null)))->ignore($id),
            ],
            'tax_id_number' => 'required|numeric|digits:15',
            'tax_id_attachment' => 'nullable|file|mimes:pdf|max:1024', // up to 1 MB
            'commercial_registration_no' => 'required|numeric|digits:10',
            'commercial_registration_expiry' => 'required|date_format:Y-m-d',
            'commercial_registration_attachment' => 'nullable|file|mimes:pdf|max:1024', // up to 1 MB
            'municipal_license_no' => 'nullable|numeric|digits:11',
            'api_url' => 'nullable|url|max:255',
            'api_admin_url' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|file|image|mimes:jpg,jpeg,png,heic,raw,webp,bmp,gif|max:520', // up to 5 MB
            'description' => 'nullable|string|max:2000',
            'status' => [
                'nullable',
                Rule::in([0, 1]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'commercial_name_ar.regex' => 'The :attribute must contain only Arabic characters.',
            'short_name_ar.regex' => 'The :attribute must contain only Arabic characters.',
        ];
    }
}
