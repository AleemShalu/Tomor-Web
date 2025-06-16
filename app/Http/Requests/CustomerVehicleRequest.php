<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerVehicleRequest extends FormRequest
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
            'customer_id' => 'required|numeric|exists:App\Models\User,id',
            'vehicle_manufacturer' => 'required|string|min:1|max:50',
            'vehicle_name' => 'required|string|min:1|max:50',
            'vehicle_model_year' => 'nullable|string|max:4',
            'vehicle_color' => 'required|string||min:1|max:10',
            'vehicle_plate_number' => 'nullable|string|min:1|max:9999',
            //'vehicle_plate_letters_ar' => 'nullable|string|min:1|max:4',
            'vehicle_plate_letters_en' => 'nullable|string|min:1|max:3',
            'default_vehicle' => [
                'nullable',
                Rule::in([0, 1]),
            ],
        ];
    }
}
