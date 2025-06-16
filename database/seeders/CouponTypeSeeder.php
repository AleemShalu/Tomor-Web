<?php

namespace Database\Seeders;

use App\Models\CouponType;
use App\Models\DiscountType;
use Illuminate\Database\Seeder;

class CouponTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedCouponTypes();
        $this->seedDiscountTypes();
    }

    private function seedCouponTypes(): void
    {
        $defaultCouponTypes = [
            [
                'code' => 'STOR_COUP',
                'en_name' => 'store discount coupon',
                'ar_name' => 'قسيمة خصم للمتجر',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'USER_COUP',
                'en_name' => 'user discount coupon',
                'ar_name' => 'قسيمة خصم للمستخدم',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PROD_COUP',
                'en_name' => 'product discount coupon',
                'ar_name' => 'قسيمة خصم للمنتج',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($defaultCouponTypes as $couponType) {
            CouponType::updateOrInsert(
                ['code' => $couponType['code']],
                $couponType
            );
        }

        $this->command->info('Default coupon types inserted or updated.');
    }

    private function seedDiscountTypes(): void
    {
        $defaultDiscountTypes = [
            [
                'code' => 'PERCENT',
                'en_name' => 'percentage',
                'ar_name' => 'نسبة مئوية',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FIXED',
                'en_name' => 'fixed amount',
                'ar_name' => 'قيمة محددة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FREE_SERVICE',
                'en_name' => 'free service',
                'ar_name' => 'خدمة مجانية',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($defaultDiscountTypes as $discountType) {
            DiscountType::updateOrInsert(
                ['code' => $discountType['code']],
                $discountType
            );
        }

        $this->command->info('Default discount types inserted or updated.');
    }
}
