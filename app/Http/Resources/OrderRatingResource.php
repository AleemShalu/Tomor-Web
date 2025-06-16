<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderRatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'order_id' => $this->order_id,
            'customer_id' => $this->customer_id,
            'order_rating_type_id' => $this->order_rating_type_id,
            'body_massage' => $this->body_massage,
            'rating' => $this->rating,
            'store' => $this->store,
            'updated_at' => convertDateToRiyadhTimezone($this->updated_at),
            'created_at' => convertDateToRiyadhTimezone($this->created_at),
        ];
    }
}
