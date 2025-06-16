<?php

namespace App\Http\Resources\Web\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends JsonResource
{
    public function toArray($request)
    {
        return $this->map(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'order_date' => $order->order_date,
                'customer_name' => $order->customer_name,
                'customer_special_needs_qualified' => $order->customer_special_needs()->id == 1 ? 'Qualified' : 'Not qualified',
                'items_count' => $order->items_count,
                'items_quantity' => $order->items_quantity,
                'grand_total' => $order->grand_total,
                'base_grand_total' => $order->base_grand_total,
                'sub_total' => $order->sub_total,
                'base_sub_total' => $order->base_sub_total,
                'service_total' => $order->service_total,
                'base_service_total' => $order->base_service_total,
                'discount_total' => $order->discount_total,
                'base_discount_total' => $order->base_discount_total,
                'tax_total' => $order->tax_total,
                'base_tax_total' => $order->base_tax_total,
                'taxable_total' => $order->taxable_total,
                'base_taxable_total' => $order->base_taxable_total,
                'checkout_method' => $order->checkout_method,
                'coupon_code' => $order->coupon_code,
                'is_gift' => $order->is_gift,
                'is_guest' => $order->is_guest,
                'store_id' => $order->store_id,
                'store_branch_id' => $order->store_branch_id,
                'employee_id' => $order->employee_id,
                'business_type_id' => $order->store->business_type_id,
                'commercial_name_en' => $order->store->commercial_name_en,
                'commercial_name_ar' => $order->store->commercial_name_ar,
                'short_name_en' => $order->store->short_name_en,
                'short_name_ar' => $order->store->short_name_ar,
                'description' => $order->store->description,
                'email_store' => $order->store->email,
                'logo' => $order->store->logo,
            ];
        })->toArray();
    }
}
