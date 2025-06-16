<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
            // 'item_type_id' => 'required|numeric|exists:App\Models\ItemType,id',
            'product_brand_id' => 'nullable|numeric|exists:App\Models\ProductBrand,id',
            'product_code' => [
                'required',
                'alpha_dash',
                'max:50',
                Rule::unique('products')->where(fn($query) => $query->where('store_id', request('store_id', null)))->ignore($id),
            ],
            'model_number' => 'nullable|alpha_dash|max:50',
            'barcode' => 'nullable|alpha_dash|max:50',
            'quantity' => 'nullable|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'calories' => 'nullable|string|max:255',
            'status' => [
                'nullable',
                Rule::in([0, 1])
            ],
            // 'vat_code_id' => 'required|numeric|exists:App\Models\VatCode,id',
            'product_category_id' => 'required|numeric|exists:App\Models\ProductCategory,id',
            'store_id' => 'required|numeric|exists:App\Models\Store,id',

            'translations' => 'required|array|min:0',
            'translations.*.locale' => 'required|distinct|string|max:5',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.excerpt' => 'nullable|string|max:255',
            'translations.*.description' => 'required|string|max:5000',

            // existing images
            'images' => 'nullable|array|max:5',
            'images.*.id' => 'required|numeric|exists:App\Models\ProductImage,id',
            'images.*.label' => 'nullable|string|max:255',
            'images.*.url' => 'required|string|max:255',

            // uploaded images
            'product_images.*' => 'present|file|image|mimes:jpg,jpeg,png,heic,raw,webp,bmp,gif|max:520', // up to 5 MB
            'product_image_labels' => 'nullable|array|max:5',
            'product_image_labels.*' => 'present|string|min:1|max:50',
        ];
    }
}
