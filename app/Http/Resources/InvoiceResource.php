<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class InvoiceResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,
            'status' => $this->status,
            'order_id' => $this->order_id,
            'store_invoice_number' => $this->store_invoice_number,
            'path' => isset($this->path) ? Storage::disk(getSecondaryStorageDisk())->url($this->path) : null,
            'invoice_date' => $this->invoice_date,
            'store_name_ar' => $this->store_name_ar,
            'store_name_en' => $this->store_name_en,
            'gross_total_including_vat' => $this->gross_total_including_vat,
            'updated_at' => convertDateToRiyadhTimezone($this->updated_at),
            'created_at' => convertDateToRiyadhTimezone($this->created_at),
        ];
    }
}
