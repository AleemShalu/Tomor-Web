<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Set to true to allow authorization by default
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules($id = null): array
    {
        return [
            'store_id' => 'nullable|exists:stores,id', // Check if the store_id exists in the 'stores' table
            'order_id' => 'nullable|exists:orders,id', // Check if the order_id exists in the 'orders' table
            'customer_id' => 'nullable|exists:users,id', // Check if the customer_id exists in the 'users' table
            'order_rating_type_code' => 'nullable|exists:order_rating_types,code', // Check if the order_rating_type_id exists in the 'order_rating_types' table
            'body_massage' => 'nullable|string', // Ensure body_massage is a string
            'rating' => 'nullable|integer|between:1,5', // Rating should be an integer between 1 and 5
        ];
    }
}
