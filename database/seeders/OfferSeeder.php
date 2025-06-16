<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Offer::create([
            'offer_name' => 'Special Discount',
            'offer_description' => 'A special discount for special products!',
            'store_id' => 1,
            'discount_percentage' => 20, // 20% discount
            'status' => 1,
            'start_date' => now(),
            'end_date' => now()->addDays(10),
        ]);
    }
}
