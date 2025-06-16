<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TermsAndPrivacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Data for privacy policies
        $privacyPolicyData = [
            'body_ar' => 'سياسة الخصوصية والأمان غير متاحة حاليًا.',
            'body_en' => 'Privacy Policy is not available at the moment.',
            'issued_at' => now(),
            'expired_at' => now()->addYears(1),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Check if privacy policy already exists
        $existingPrivacyPolicy = DB::table('privacy_policies')->first();

        if (!$existingPrivacyPolicy) {
            // Insert privacy policy if it doesn't exist
            DB::table('privacy_policies')->insert($privacyPolicyData);
        }

        // Data for terms and conditions
        $termsConditionsData = [
            'body_ar' => 'الشروط والأحكام غير متاحة حاليًا.',
            'body_en' => 'Terms and Conditions are not available at the moment.',
            'issued_at' => now(),
            'expired_at' => now()->addYears(1),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Check if terms and conditions already exists
        $existingTermsConditions = DB::table('terms_conditions')->first();

        if (!$existingTermsConditions) {
            // Insert terms and conditions if it doesn't exist
            DB::table('terms_conditions')->insert($termsConditionsData);
        }
    }
}
