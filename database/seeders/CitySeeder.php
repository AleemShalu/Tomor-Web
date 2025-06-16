<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Optionally clear existing cities
        // City::truncate();

        // Retrieve the country
        $saudiArabia = Country::where('en_name', 'Saudi Arabia')->first();

        if ($saudiArabia) {
            // Define default cities for Saudi Arabia
            $defaultCities = [
                ['en_name' => 'Riyadh', 'ar_name' => 'الرياض', 'center_latitude' => '24.7136', 'center_longitude' => '46.6753', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Jeddah', 'ar_name' => 'جدة', 'center_latitude' => '21.4858', 'center_longitude' => '39.1925', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Makkah', 'ar_name' => 'مكة المكرمة', 'center_latitude' => '21.3891', 'center_longitude' => '39.8579', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Madinah', 'ar_name' => 'المدينة المنورة', 'center_latitude' => '24.5247', 'center_longitude' => '39.5692', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Dammam', 'ar_name' => 'الدمام', 'center_latitude' => '26.3927', 'center_longitude' => '49.9777', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Taif', 'ar_name' => 'الطائف', 'center_latitude' => '21.2703', 'center_longitude' => '40.4158', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Tabuk', 'ar_name' => 'تبوك', 'center_latitude' => '28.3835', 'center_longitude' => '36.5662', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Abha', 'ar_name' => 'أبها', 'center_latitude' => '18.2371', 'center_longitude' => '42.5006', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Buraydah', 'ar_name' => 'بريدة', 'center_latitude' => '26.3259', 'center_longitude' => '43.9744', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Khobar', 'ar_name' => 'الخبر', 'center_latitude' => '26.2799', 'center_longitude' => '50.2084', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Hail', 'ar_name' => 'حائل', 'center_latitude' => '27.4008', 'center_longitude' => '41.4404', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Najran', 'ar_name' => 'نجران', 'center_latitude' => '17.4924', 'center_longitude' => '44.1277', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Dhahran', 'ar_name' => 'الظهران', 'center_latitude' => '26.2958', 'center_longitude' => '50.1501', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Jizan', 'ar_name' => 'جيزان', 'center_latitude' => '16.8892', 'center_longitude' => '42.5511', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Ras Tanura', 'ar_name' => 'رأس تنورة', 'center_latitude' => '26.6372', 'center_longitude' => '50.1604', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Al Qunfudhah', 'ar_name' => 'القنفذة', 'center_latitude' => '19.1289', 'center_longitude' => '41.0789', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Umluj', 'ar_name' => 'أملج', 'center_latitude' => '25.0211', 'center_longitude' => '37.2685', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Al Lith', 'ar_name' => 'الليث', 'center_latitude' => '18.1900', 'center_longitude' => '42.5028', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Arar', 'ar_name' => 'عرعر', 'center_latitude' => '30.9908', 'center_longitude' => '41.0351', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Al Bahah', 'ar_name' => 'الباحة', 'center_latitude' => '20.0144', 'center_longitude' => '41.4676', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Sakakah', 'ar_name' => 'سكاكا', 'center_latitude' => '29.9788', 'center_longitude' => '40.2060', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Rafha', 'ar_name' => 'رفحاء', 'center_latitude' => '29.6237', 'center_longitude' => '43.4963', 'country_id' => $saudiArabia->id],
                ['en_name' => 'Turaif', 'ar_name' => 'طريف', 'center_latitude' => '31.6707', 'center_longitude' => '38.6635', 'country_id' => $saudiArabia->id],
                // Add more cities as needed
            ];

            // Insert cities and avoid duplicates
            foreach ($defaultCities as $cityData) {
                City::updateOrCreate(
                    ['en_name' => $cityData['en_name']], // Unique key for existing cities
                    $cityData
                );
            }

            // Output success message
            $this->command->info('Default cities added or updated for Saudi Arabia.');
        } else {
            // Output error message if Saudi Arabia is not found
            $this->command->error('Could not find Saudi Arabia country. Please make sure it exists in the countries table.');
        }
    }
}
