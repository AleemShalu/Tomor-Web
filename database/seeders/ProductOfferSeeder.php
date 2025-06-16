<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductOffer;
use Illuminate\Database\Seeder;

class ProductOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductOffer::create([
            'product_id' => Product::first()->id,
            'offer_id' => Offer::latest()->first()->id,
        ]);
    }
}
