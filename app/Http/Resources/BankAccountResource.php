<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BankAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        # id, store_id, account_holder_name, iban_number, iban_attachment, bank_name, swift_code, created_at, updated_at
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'account_holder_name' => $this->account_holder_name,
            'iban_number' => $this->iban_number,
            'iban_attachment' => isset($this->iban_attachment)
                ? Storage::disk(getSecondaryStorageDisk())->url($this->iban_attachment)
                : null,
            'bank_name' => $this->bank_name,
            'swift_code' => $this->swift_code,
            'updated_at' => convertDateToRiyadhTimezone($this->updated_at),
            'created_at' => convertDateToRiyadhTimezone($this->created_at),
            // 'store' => $this->store,
        ];
    }
}
