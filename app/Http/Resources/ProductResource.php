<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductResource extends JsonResource
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

        // get the latest active offer based on date range and status.
        $last_active_offer = $this->product_offer->filter(function ($productOffer) {
            $offer = $productOffer->offer;
            return $offer->start_date <= now() && $offer->end_date >= now() && $offer->status;
        })->last()?->offer;
        $discounted_price = $this->unit_price;
        if ($last_active_offer) {
            $discounted_price = $this->unit_price * (1 - $last_active_offer->discount_percentage / 100);
        }
        $has_active_offer = !!$last_active_offer; // convert to boolean

        // $locale = request('locale', config('app.locale'));
        // $currency_code = request('currency_code', config('app.currency'));
        $currency_code = request('currency_code', 'SAR');

        $images = array();
        if ($this->images) {
            foreach ($this->images as $image) {
                $images[] = [
                    'id' => $image['id'],
                    'label' => Str::ucfirst($image['label']),
                    'url' => isset($image['url']) ? Storage::disk(getSecondaryStorageDisk())->url($image['url']) : null,
                ];
            }
        }

        return [
            'id' => $this->id,
            'product_brand_id' => $this->product_brand_id,
            'product_code' => $this->product_code,
            'model_number' => $this->model_number,
            'barcode' => $this->barcode,
            'quantity' => $this->quantity,
            'availability' => $this->availability,
            'has_active_offer' => $has_active_offer,
            'unit_price' => $request->user() && $request->user()->hasRole('owner')
                ? number_format($this->unit_price, 2, '.', '')
                : number_format($discounted_price, 2, '.', ''),
            'unit_price_previous' => number_format($this->unit_price, 2, '.', ''),
            'offer_details' => $last_active_offer ? [
                'offer_name' => $last_active_offer->offer_name,
                'offer_description' => $last_active_offer->offer_description,
                'discount_percentage' => $last_active_offer->discount_percentage,
                'start_date' => $last_active_offer->start_date->format('Y-m-d'),
                'end_date' => $last_active_offer->end_date->format('Y-m-d'),
            ] : [
                'offer_name' => null,
                'offer_description' => null,
                'discount_percentage' => null,
                'start_date' => null,
                'end_date' => null,
            ],
            'currency_code' => $currency_code,
            'calories' => $this->calories,
            'status' => $this->status,
            'product_category_id' => $this->product_category_id,
            'store_id' => $this->store_id,
            'product_brand' => $this->product_brand,
            'product_category' => $this->product_category,
            'translations' => $this->translations,
            'images' => $images,
            'store' => $this->store,
            // 'product_offer' => $this->when($this->has_active_offer, $this->product_offer),
            'updated_at' => convertDateToRiyadhTimezone($this->updated_at),
            'created_at' => convertDateToRiyadhTimezone($this->created_at),
        ];
    }
}
