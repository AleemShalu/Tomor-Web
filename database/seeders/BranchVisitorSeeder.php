<?php

namespace Database\Seeders;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchVisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create(); // Initialize the faker

        for ($i = 1; $i <= 49; $i++) {
            DB::table('branch_visitors')->insert([
                'store_id' => 1,
                'store_branch_id' => 1, // Increment the order_id value in each iteration
                'user_id' => rand(3, 40),
                'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),
                'updated_at' => now(),
            ]);
        }
    }
}
