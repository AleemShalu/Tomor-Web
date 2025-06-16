<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProfileRequest extends FormRequest
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
    public function rules($id): array
    {
        return [
            'name' => 'required|string|min:1|max:100',
            'username' => 'nullable|string|max:20',
            'email' => 'required|string|email:rfc,dns|max:50|unique:App\Models\User,email,' . $id,
            // 'password' => 'required|string|min:8|max:50|confirmed',
            'dial_code' => 'nullable|max:5|regex:/^([0-9\-\+\(\)]*)$/',
            'contact_no' => [
                'nullable',
                'min:9',
                'max:15',
                'regex:/^[0-9]+$/',
                Rule::unique('users')->where(fn($query) => $query->where('dial_code', request('dial_code', null)))->ignore($id),
            ],
            'profile_photo_path' => 'nullable|file|image|mimes:jpg,jpeg,png,heic,raw,webp,bmp,gif|max:520', // up to 5 MB
        ];
    }
}
