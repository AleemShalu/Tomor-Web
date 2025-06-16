<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankCardResource extends JsonResource
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
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'card_holder_name' => $this->card_holder_name,
            'card_number' => $this->card_number,
            'card_expiry_year' => $this->card_expiry_year,
            'card_expiry_month' => $this->card_expiry_month,
            'card_cvv' => $this->card_cvv,
            'default_card' => $this->default_card,
            'created_at' => convertDateToRiyadhTimezone($this->created_at),
            'updated_at' => convertDateToRiyadhTimezone($this->updated_at),
            'customer' => $this->customer,
        ];

    }
}
