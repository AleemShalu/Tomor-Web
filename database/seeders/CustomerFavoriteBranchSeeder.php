<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerFavoriteBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('customer_favorite_branches')->insert([
                ['customer_id' => 4, 'store_branch_id' => $i, 'created_at' => now(), 'updated_at' =>  now(),],
            ]);
        }
    }
}
