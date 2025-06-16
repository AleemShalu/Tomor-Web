<?php

namespace App\Http\Resources\Web;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SpecialNeedsCollection extends JsonResource
{
    public function toArray($request)
    {
        return $this->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'dial_code' => $user->dial_code,
                'contact_no' => $user->contact_no,
                'user_type' => $user->user_type,
                'profile_photo_path' => $user->profile_photo_path,
                'created_at' => Carbon::parse($user->created_at)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($user->updated_at)->format('Y-m-d H:i:s'),
                'last_seen' => Carbon::parse($user->last_seen)->format('Y-m-d H:i:s'),
                // 'profile_photo_url' => $user->profile_photo_url,
                'customer_id' => $user->customer->id,
                'special_needs_qualified' => $user->customer->special_needs_qualified == 1 ? 'Qualified' : 'Not qualified',
                'special_needs_description' => $user->customer->special_needs_description,
                'special_needs_attachment' => $user->customer->special_needs_attachment,
                'special_needs_status' => $user->customer->special_needs_status,
                'created_at_customer' => Carbon::parse($user->customer->created_at)->format('Y-m-d H:i:s'),
                'updated_at_customer' => Carbon::parse($user->customer->updated_at)->format('Y-m-d H:i:s'),
            ];
        })->toArray();
    }
}
