<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'item_code' => $this->item_code,
            'item_name' => $this->item_name,
            'item_description' => $this->item_description,
            'item_unit_price' => number_format($this->item_unit_price, 4),
            'item_quantity' => number_format($this->item_quantity, 4),
            'item_status' => $this->item_status,
            'item_total' => number_format($this->item_total, 4),
            'note' => $this->note,
            'voice_url' => $this->voice_url,
            'voice_path' => $this->voice_path,
            'updated_at' => convertDateToRiyadhTimezone($this->updated_at),
            'created_at' => convertDateToRiyadhTimezone($this->created_at),
        ];
    }
}
