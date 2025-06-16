<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class SpecialNeedsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array
     */
    public function toArray($request): array
    {
        if ($this->resource == null) {
            return [];
        }

        return [
            'id' => $this->id,
//            'name' => $this->name,
//            'username' => $this->username,
//            'email' => $this->email,
//            'email_verified_at' => $this->email_verified_at,
//            'dial_code' => $this->dial_code,
//            'contact_no' => $this->contact_no,
//            'phone_verified_at' => $this->phone_verified_at,
//            'two_factor_confirmed_at' => $this->two_factor_confirmed_at,
//            'status' => $this->status,
//            'user_type' => $this->user_type,
//            'auth_id' => $this->auth_id,
//            'auth_type' => $this->auth_type,
//            'robot_auth' => $this->robot_auth,
//            'profile_photo_path' => $this->profile_photo_path,
//            'store_id' => $this->store_id,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//            'last_seen' => $this->last_seen,
//            'profile_photo_url' => $this->profile_photo_url,
//            'customer' => [
//                'id' => $this->customer->id,
//                'user_id' => $this->customer->user_id,
//                'special_needs_qualified' => $this->customer->special_needs_qualified ? 'True' : 'False',
//                'special_needs_description' => $this->customer->special_needs_description,
//                'special_needs_attachment' => $this->customer->special_needs_attachment,
//                'special_needs_status' => $this->customer->special_needs_status,
//                'created_at' => $this->customer->created_at,
//                'updated_at' => $this->customer->updated_at,
//            ],
        ];
    }
}
