<?php

namespace App\Http\Resources;

use App\Models\ServiceDefinition;
use App\Models\TaxCode;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceDefinitionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $tax = TaxCode::find(1);
        $taxRate = $tax->tax_rate / 100;
        $priceAfterDiscount = $this->price * (1 - ($this->discount_percentage / 100));
        $taxAmount = $priceAfterDiscount * $taxRate;
        $priceAfterDiscountAndTax = $priceAfterDiscount + $taxAmount;

        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'code' => $this->code,
            // 'description' => $this->description,
            // 'service_currency_code' => $this->service_currency_code,
            'price' => number_format($this->price, 2, '.', ''),
            'discount_percentage' => number_format((float) $this->discount_percentage, 2, '.', ''),
            'discount_amount' => number_format(($this->discount_percentage / 100) * $this->price, 2, '.', ''),
            'tax_amount' => number_format($taxAmount, 2, '.', ''),
            'final_price' => number_format($priceAfterDiscountAndTax, 2, '.', ''),
            // 'rate' => number_format((float)$service_definition->rate, 2, '.', ''),
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
