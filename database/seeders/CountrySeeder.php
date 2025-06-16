<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Optionally clear existing countries
        // Country::truncate();

        // Define default countries
        $countries = [
            ['en_name' => 'Saudi Arabia', 'ar_name' => 'المملكة العربية السعودية'],
            // Add more countries as needed
        ];

        foreach ($countries as $countryData) {
            // Check if country already exists
            $country = Country::firstOrCreate([
                'en_name' => $countryData['en_name'],
                'ar_name' => $countryData['ar_name'],
            ]);

            // Optional: Update the country if needed
            // $country->update($countryData);
        }

        // Output success message
        $this->command->info('Default countries inserted.');
    }
}
