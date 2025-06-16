<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StoreResource extends JsonResource
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
            'business_type_id' => $this->business_type_id,
            'commercial_name_en' => $this->commercial_name_en,
            'commercial_name_ar' => $this->commercial_name_ar,
            'short_name_en' => $this->short_name_en,
            'short_name_ar' => $this->short_name_ar,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            $this->mergeWhen((request('expanded') === true), [
                'email' => $this->email,
                'country_id' => $this->country_id,
                'dial_code' => $this->dial_code,
                'contact_no' => $this->contact_no,
                'secondary_dial_code' => $this->secondary_dial_code,
                'secondary_contact_no' => $this->secondary_contact_no,
                'tax_id_number' => $this->tax_id_number,
                'tax_id_attachment' => isset($this->tax_id_attachment)
                    ? Storage::disk(getSecondaryStorageDisk())->url($this->tax_id_attachment)
                    : null,
                'commercial_registration_no' => $this->commercial_registration_no,
                'commercial_registration_expiry' => convertGregorianToHijri($this->commercial_registration_expiry),
                'commercial_registration_attachment' => isset($this->commercial_registration_attachment)
                    ? Storage::disk(getSecondaryStorageDisk())->url($this->commercial_registration_attachment)
                    : null,
                'municipal_license_no' => $this->municipal_license_no,
                'api_url' => $this->api_url,
                'api_admin_url' => $this->api_admin_url,
                'website' => $this->website,
            ]),
            $this->mergeWhen((request('expanded') === true || request('menu_pdf')), [
                'menu_pdf' => isset($this->menu_pdf) ? Storage::disk(getSecondaryStorageDisk())->url($this->menu_pdf) : null,
            ]),
            'logo' => isset($this->logo) ? Storage::disk(getSecondaryStorageDisk())->url($this->logo) : null,
            'store_header' => isset($this->store_header) ? Storage::disk(getSecondaryStorageDisk())->url($this->store_header) : null,
            'rating_avg' => $this->getRatingAvgAttribute(),
            'rating_count' => $this->getRatingsCountAttribute(),
            $this->mergeWhen((request('expanded') === true), [
                'range_time_order' => $this->range_time_order,
                'status' => $this->status,
                'owner_id' => $this->owner_id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]),
            'business_type' => $this->business_type,
            $this->mergeWhen((request('expanded') === true), [
                'owner' => $this->owner,
                'country' => $this->country,
                'bank_accounts' => BankAccountResource::collection($this->whenLoaded('bank_accounts')),
            ]),
        ];
    }
}
