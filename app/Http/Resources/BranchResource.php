<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>|null
     */
    public function toArray(Request $request): ?array
    {
        // return parent::toArray($request);

        if ($this->resource == null) {
            return null;
        }

        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'branch_serial_number' => $this->branch_serial_number,
            $this->mergeWhen((request('expanded') === true), [
                'qr_code' => isset($this->qr_code) ? Storage::disk(getSecondaryStorageDisk())->url($this->qr_code) : null,
                'commercial_registration_no' => $this->commercial_registration_no,
                'commercial_registration_expiry' => convertGregorianToHijri($this->commercial_registration_expiry),
                'commercial_registration_attachment' => isset($this->commercial_registration_attachment)
                    ? Storage::disk(getSecondaryStorageDisk())->url($this->commercial_registration_attachment)
                    : null,
                'bank_account_id' => $this->bank_account_id,
                'email' => $this->email,
                'dial_code' => $this->dial_code,
                'contact_no' => $this->contact_no,
                'city_id' => $this->city_id,
                'default_branch' => $this->default_branch,
            ]),
            'store_id' =>  $this->store_id,
            $this->mergeWhen(
                Auth::guard('sanctum')->check() && Auth::guard('sanctum')->user()->hasRole('customer'),
                [
                    'favoured_by_auth_customer' => $this->getFavouredByCustomersAttribute(Auth::guard('sanctum')->user()->id ?? null),
                ]
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            $this->mergeWhen((request('expanded') === true || (request('latitude') && request('longitude'))), [
                'location' => $this->whenLoaded('location'),
            ]),
//            $this->mergeWhen((request('expanded') === true), [
                'city' => $this->whenLoaded('city'),
                'work_statuses' => $this->whenLoaded('work_statuses'),
                'favoured_by_customers' => $this->whenLoaded('favoured_by_customers'),
                'bank_account' => $this->whenLoaded('bank_account'),
//            ]),
            'is_working_now' => $this->when($this->work_statuses->count(),
                $this->branchIsWorkingNow(request('timezone', config('app.timezone'))),
            ),
            'store' => StoreResource::make($this->whenLoaded('store')),
        ];
    }
}
