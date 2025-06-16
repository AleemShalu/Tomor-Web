<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerVehicleResource extends JsonResource
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

        # id, customer_id, vehicle_manufacturer, vehicle_name, vehicle_model_year,
        // vehicle_color, vehicle_plate_number, vehicle_plate_letters_ar,
        // vehicle_plate_letters_en, default_vehicle, created_at, updated_at

        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'vehicle_manufacturer' => $this->vehicle_manufacturer,
            'vehicle_name' => $this->vehicle_name,
            'vehicle_model_year' => $this->vehicle_model_year,
            'vehicle_color' => $this->vehicle_color,
            'vehicle_plate_number' => $this->vehicle_plate_number,
//            'vehicle_plate_letters_ar' => $this->vehicle_plate_letters_ar,
            'vehicle_plate_letters_en' => $this->vehicle_plate_letters_en,
            'default_vehicle' => $this->default_vehicle,
            'updated_at' => convertDateToRiyadhTimezone($this->updated_at),
            'created_at' => convertDateToRiyadhTimezone($this->created_at),
            'customer' => $this->customer,
        ];
    }
}
