<?php

namespace App\Http\Requests\Web;

use App\Models\LocationConfig;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBranchRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Update with appropriate authorization logic if needed
    }

    public function rules()
    {
        $id = null; // Update with the ID if updating an existing branch
        $config = LocationConfig::where('code', 'BR')->first();

        return [
            'name_en' => 'required|string|min:1|max:100|regex:/^[a-zA-Z0-9 ]+$/',
            'name_ar' => 'required|string|min:1|max:100|regex:/^[0-9أ-ي ]+$/u',
            'commercial_registration_no' => 'required|numeric|digits:10',
            'commercial_registration_expiry' => 'required|date',
            'commercial_registration_attachment' => 'nullable|file|mimes:pdf|max:1024', // up to 1 MB
            'email' => ['email:rfc|max:100', Rule::unique('store_branches')->ignore($id)],
            'dial_code' => 'nullable|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'required',
                'max:15',
                Rule::unique('store_branches')->where(fn($query) => $query->where('dial_code', $this->input('dial_code')))->ignore($id),
            ],
            'city_id' => 'required|exists:cities,id',
            'location' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'location_radius' => [
                'required',
                'numeric',
                'min:' . $config->min_radius,
                'max:' . $config->max_radius,
            ],
            'district' => 'required',
            'store_id' => 'required',
            'bank_account_id' => 'required',
            'location_description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name_ar.regex' => 'The :attribute field must contain Arabic characters, numbers, and spaces only.',
            'name_en.regex' => 'The :attribute field must contain only English letters, numbers, and spaces.',
        ];
    }
}
