<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CustomerWithSpecialNeedsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'special_needs_type_id' => $this->special_needs_type_id,
            'special_needs_qualified' => $this->special_needs_qualified,
            'special_needs_sa_card_number' => $this->special_needs_sa_card_number,
            'special_needs_description' => $this->special_needs_description,
            'special_needs_attachment' => isset($this->special_needs_attachment)
                ? Storage::disk(getSecondaryStorageDisk())->url($this->special_needs_attachment)
                : null,
            'special_needs_status' => $this->special_needs_status,
            'updated_at' => convertDateToRiyadhTimezone($this->updated_at),
            'created_at' => convertDateToRiyadhTimezone($this->created_at),
            'special_needs_type' => $this->special_needs_type,
        ];
    }
}
