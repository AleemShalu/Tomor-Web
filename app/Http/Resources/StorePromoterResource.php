<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StorePromoterResource extends JsonResource
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
            //'code' => $this->code,
            'store_data' => [
                'id' => $this->store->id,
                //'commercial_name_en' => $this->store->commercial_name_en,
                //'commercial_name_ar' => $this->store->commercial_name_ar,
                'short_name_en' => $this->store->short_name_en,
                'short_name_ar' => $this->store->short_name_ar,
                'logo' => isset($this->store->logo) ? Storage::disk(getSecondaryStorageDisk())->url($this->store->logo) : null,
                //'store_header' => isset($this->store->store_header) ? Storage::disk(getSecondaryStorageDisk())->url($this->store->store_header) : null,
            ],
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'promoter_header_path' => isset($this->promoter_header_path) ? Storage::disk(getSecondaryStorageDisk())->url($this->promoter_header_path) : null,
            //'status' => (bool)$this->status, // Ensure 'status' is a boolean
            //'start_date' => $this->start_date->toIso8601String(),
            //'end_date' => $this->end_date->toIso8601String(),
            'updated_at' => convertDateToRiyadhTimezone($this->updated_at),
            'created_at' => convertDateToRiyadhTimezone($this->created_at),
        ];
    }
}
