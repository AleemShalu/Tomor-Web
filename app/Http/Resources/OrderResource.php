<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'branch_order_number' => $this->branch_order_number,
            'store_order_number' => $this->store_order_number,
            'branch_queue_number' => (string)$this->branch_queue_number,
            'status' => $this->status,
            'is_paid' => (bool)$this->is_paid,
            'refund_request' => (bool)$this->refund_request,
            'refund_status' => $this->refund_status,
            'refund_reason' => $this->refund_reason,
            'order_date' => convertDateToTimezone($this->order_date, null, request('timezone')),
            'order_color' => $this->order_color,
            'customer_name' => $this->customer_name,
            'customer_dial_code' => $this->customer_dial_code,
            'customer_contact_no' => $this->customer_contact_no,
            'customer_email' => $this->customer_email,
            'customer_vehicle_id' => $this->customer_vehicle_id,
            'customer_vehicle_description' => $this->customer_vehicle_description,
            'customer_vehicle_manufacturer' => $this->customer_vehicle_manufacturer,
            'customer_vehicle_name' => $this->customer_vehicle_name, //
            'customer_vehicle_model_year' => $this->customer_vehicle_model_year, //
            'customer_vehicle_color' => $this->customer_vehicle_color,
            'customer_vehicle_plate_letters' => $this->customer_vehicle_plate_letters, //
            'customer_vehicle_plate_number' => $this->customer_vehicle_plate_number, //
            'customer_special_needs_qualified' => $this->customer_special_needs_qualified,
            'items_count' => $this->items_count,
            'items_quantity' => $this->items_quantity,
            'exchange_rate' => $this->exchange_rate,
            'conversion_time' => $this->conversion_time,
            'order_currency_code' => $this->order_currency_code,
//            'base_currency_code' => $this->base_currency_code,
            'grand_total' => convertToTwoDecimalPlaces($this->grand_total),
//            'base_grand_total' => $this->base_grand_total,
            'sub_total' => convertToTwoDecimalPlaces($this->sub_total),
            'sub_total_including_tax' => convertToTwoDecimalPlaces(getAmountIncludingVate($this->sub_total)),
//            'base_sub_total' => $this->base_sub_total,
            'service_total' => convertToTwoDecimalPlaces($this->service_total),
//            'base_service_total' => $this->base_service_total,
            'service_total_tax_amount' => convertToTwoDecimalPlaces($this->service_total_tax_amount),
            'service_total_including_tax' => convertToTwoDecimalPlaces($this->service_total_including_tax),

            'discount_total' => convertToTwoDecimalPlaces($this->discount_total),
//            'base_discount_total' => $this->base_discount_total,
            'tax_total' => convertToTwoDecimalPlaces($this->tax_total),
//            'base_tax_total' => $this->base_tax_total,
            'taxable_total' => convertToTwoDecimalPlaces($this->taxable_total),
//            'base_taxable_total' => $this->base_taxable_total,
            'bank_card_id' => $this->bank_card_id,
            'checkout_method' => $this->checkout_method,
            'coupon_code' => $this->coupon_code,
//            'is_gift' => $this->is_gift,
//            'is_guest' => $this->is_guest,
            'store_id' => $this->store_id,
            'store_branch_id' => $this->store_branch_id,
            'employee_id' => $this->employee_id,
            'created_at' => convertDateToRiyadhTimezone($this->created_at),
            'updated_at' => convertDateToRiyadhTimezone($this->updated_at),
            'order_items' => $this->order_items,
            'invoice_url' => isset($this->invoice->path) ? Storage::disk(getSecondaryStorageDisk())->url($this->invoice->path) : null,
            'rating' => $this->order_ratings->isNotEmpty() ? $this->order_ratings->first()->rating : null,
            'customer' => $this->customer,
            'customer_vehicle' => $this->customer_vehicle,
//            'bank_card' => new BankCardResource($this->bank_card),
            'employee' => $this->employee,
            'store_branch' => $this->store_branch,
            'store' => StoreResource::make($this->whenLoaded('store')),
        ];
    }
}
