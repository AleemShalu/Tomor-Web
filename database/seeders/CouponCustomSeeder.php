<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CouponCustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Ensure foreign key checks are disabled to avoid issues with non-nullable fields
        Schema::disableForeignKeyConstraints();

        $this->seedCoupons();

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    private function seedCoupons()
    {
        // Define the coupon code
        $couponCode = 'CODE10';

        // Check if the coupon already exists
        $existingCoupon = DB::table('coupons')->where('code', $couponCode)->first();

        if (!$existingCoupon) {
            // Insert the coupon if it does not already exist
            DB::table('coupons')->insert([
                'code' => $couponCode,
                'coupon_type_id' => 2,
                'discount_type_id' => 1,
                'discount_percentage' => 10,
                'discount_amount' => null,
                'coupon_currency_code' => 'SAR',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(30),
                'enabled' => true,
                'max_uses_per_user' => 1,
                'usage_count' => 0,
                'restrictions' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $this->command->info('Custom coupon inserted.');
        } else {
            $this->command->info('Coupon already exists.');
        }
    }

    private function getValidCouponTypeId()
    {
        // Replace with logic to fetch or create a valid coupon type ID
        return DB::table('coupon_types')->first()->id ?? 1;
    }

    private function getValidDiscountTypeId()
    {
        // Replace with logic to fetch or create a valid discount type ID
        return DB::table('discount_types')->first()->id ?? 1;
    }
}
