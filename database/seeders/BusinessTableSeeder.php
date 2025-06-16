<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $businessData = [
            'name_en' => 'Hader Digital Company',
            'name_ar' => 'شركة حاضر الرقمية',
            'vat_number' => '311635883500003',
            'group_vat_number' => '',
            'cr_number' => '4030508736',
            'email' => 'info@tomor-sa.com',
            'country_code' => '966',
            'phone' => '555555555',
            'country' => 'Saudi Arabia',
            'state' => 'Jeddah',
            'city' => 'Jeddah',
            'district' => 'Al-Rawdah',
            'street' => 'Abed bin Hussein',
            'building_no' => '23431',
            'zipcode' => '00000',
            'logo' => 'https://tomor-sa.com/images/tomor-logo-04.png',
            'website' => 'https://tomor-sa.com/',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Check if a record with the same email or VAT number already exists
        $existingBusiness = DB::table('businesses')
            ->where('email', $businessData['email'])
            ->orWhere('vat_number', $businessData['vat_number'])
            ->first();

        if (!$existingBusiness) {
            // Insert the record if it doesn't exist
            DB::table('businesses')->insert($businessData);
        }
    }
}
