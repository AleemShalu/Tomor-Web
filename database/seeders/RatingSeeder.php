<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 1; $i <= 50; $i++) {
            DB::table('order_ratings')->insert([
                'store_id' => 1,
                'order_id' => $i, // Increment the order_id value in each iteration
                'customer_id' => 4,
                'order_rating_type_id' => rand(1, 4),
                'body_massage' => 'test',
                'rating' => $faker->randomElement([1, 2, 3, 4, 5]), // Use randomElement to select from the given values
                'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),
                'updated_at' => now(),
            ]);
        }
    }
}
