<?php

namespace App\Http\Requests;

use App\Models\LocationConfig;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
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
        $config = LocationConfig::where('code', 'BR')->first();

        return [
            'name_ar' => 'required|string|min:1|max:100',
            'name_en' => 'required|string|min:1|max:100',
            // 'branch_serial_number' => 'nullable|alpha_dash|max:50',
            // 'qr_code' => 'nullable|file|image|max:1024|mimes:jpg,jpeg,bmp,png,gif,ico',
            'commercial_registration_no' => 'required|numeric|digits:10|unique:App\Models\Store,commercial_registration_no,' . $id,
            'commercial_registration_expiry' => 'required|date_format:Y-m-d',
            'commercial_registration_attachment' => 'required|file|mimes:pdf|max:1024', // up to 1 MB
            'bank_account_id' => 'nullable|numeric|exists:App\Models\BankAccount,id',
            'email' => 'nullable|email:rfc|max:100|unique:App\Models\StoreBranch,email,' . $id,
            'dial_code' => 'nullable|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => 'nullable|max:15|regex:/^[0-9]+$/',
            'city_id' => 'required|numeric|exists:App\Models\City,id',
            'default_branch' => [
                'nullable',
                Rule::in([0, 1]),
            ],
            'store_id' => 'required|numeric|exists:App\Models\Store,id',

            // 'location_description' => 'nullable|string|max:2000',
            'location_radius' => [
                'required',
                'numeric',
                'min:' . $config->min_radius,
                'max:' . $config->max_radius,
            ],
            'latitude' => 'required|string|min:1|max:200',
            'longitude' => 'required|string|min:1|max:200',
            'district' => 'required|string|min:1|max:250',
            // 'national_address' => 'required|string|min:1|max:250',
            'google_maps_url' => 'required|string|min:1|max:250',
        ];
    }
}
