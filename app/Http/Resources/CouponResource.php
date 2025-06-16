<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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

        # id, code, coupon_type_id, discount_type_id, discount_amount, coupon_currency_code,
        # start_date, end_date, enabled, max_uses, max_uses_per_user,
        # usage_count, min_amount, restrictions, created_at, updated_at
        return [
            'id' => $this->id,
            'code' => $this->code,
            'coupon_type_id' => $this->coupon_type_id,
            'discount_type_id' => $this->discount_type_id,
            'discount_amount' => $this->discount_amount,
            'discount_percentage' => $this->discount_percentage,
            'coupon_currency_code' => $this->coupon_currency_code,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'enabled' => $this->enabled,
            'max_uses' => $this->max_uses,
            'max_uses_per_user' => $this->max_uses_per_user,
            'usage_count' => $this->usage_count,
            'min_amount' => $this->min_amount,
            'restrictions' => $this->restrictions,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'coupon_type' => $this->coupon_type,
            'discount_type' => $this->discount_type,
            'stores' => StoreResource::collection($this->whenLoaded('stores')),
        ];
    }
}
