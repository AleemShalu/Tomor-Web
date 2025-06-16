<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>|null
     */
    public function toArray(Request $request): ?array
    {
        if ($this->resource == null) {
            return null;
        }

        return [
            # id, name, username, email, email_verified_at, dial_code, contact_no, phone_verified_at, password,
            // two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, status, user_type,
            // auth_id, auth_type, robot_auth, remember_token, profile_photo_path, store_id, last_seen, created_at, updated_at
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'is_quick_login' => $this->is_quick_login,
            'dial_code' => $this->dial_code,
            'contact_no' => $this->contact_no,
            'phone_verified_at' => $this->phone_verified_at,
            'status' => $this->status,
            'user_type' => $this->user_type,
            // 'auth_id' =>  $this->auth_id,
            // 'auth_type' =>  $this->auth_type,
            // 'robot_auth' =>  $this->robot_auth,
            'profile_photo_path' => isset($this->profile_photo_path)
                ? Storage::disk(getSecondaryStorageDisk())->url($this->profile_photo_path)
                : null,
            'store_id' => $this->store_id,
            'last_seen' => $this->last_seen,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'customer_with_special_needs' => isset($this->customer_with_special_needs)
                ? CustomerWithSpecialNeedsResource::make($this->customer_with_special_needs)
                : null,
            'roles' => $this->roles,
        ];
    }
}
