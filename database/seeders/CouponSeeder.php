<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coupon::factory()->count(50)->create();

        for ($i = 1; $i <= 4; $i++) {
            DB::table('coupons_for_stores')->insert([
                ['coupon_id' => $i, 'store_id' => $i, 'created_at' => now(), 'updated_at' =>  now(),],
            ]);
        }
    }
}
