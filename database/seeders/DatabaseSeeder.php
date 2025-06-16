<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            RolesAndPermissionsSeeder::class,
            BusinessTableSeeder::class,
            TermsAndPrivacySeeder::class,
            TaxCodeTableSeeder::class,
            SpecialNeedsTypesSeeder::class,
            ServiceDefinitionsSeeder::class,
            UserSeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            LanguageSeeder::class,
            CurrencySeeder::class,
            BusinessTypeSeeder::class,
            CouponTypeSeeder::class,
            CouponCustomSeeder::class,
            OrderRatingTypesSeeder::class,
            SettingsTableSeeder::class,
            NotificationTypeSeeder::class,
//            StoreCustomSeeder::class,
            // StoreSeeder::class,
            // ProductSeeder::class,
            // OfferSeeder::class,
            // ProductOfferSeeder::class,
//            StoreBranchCustomSeeder::class,
            // StoreBranchSeeder::class,
            // EmployeeSeeder::class,
            // CustomerSeeder::class,
            // OrderSeeder::class,
            FeedbackSeeder::class,
            // ReportsTableSeeder::class,
            // CouponSeeder::class,
            // CustomerFavoriteBranchSeeder::class,
            // WorkDaysTableSeeder::class,
            // BreakTimesTableSeeder::class,
            // RatingSeeder::class,
            // BranchVisitorSeeder::class
            FilamentUserSeeder::class,
            LocationConfigSeeder::class,
            PaymentConfigurationsSeeder::class,
        ]);
    }
}
