<?php

namespace Database\Seeders;

use App\Models\BranchLocation;
use App\Models\BranchWorkStatus;
use App\Models\StoreBranch;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class StoreBranchSeeder extends Seeder
{
    public function run()
    {
        $faker_en = Faker::create();
        $faker_ar = Faker::create('ar_SA');

        // change the loop count to the number of branches you want to create
        for ($i = 0; $i < 50; $i++) {

            $branch = new StoreBranch();

            // set branch fields
            $branch->store_id = $faker_en->numberBetween(1, 10); // assuming there are 10 stores in database
            $branch->name_ar = $faker_ar->company;
            $branch->name_en = $faker_en->company;
            $branch->commercial_registration_no = $faker_en->numerify('##########'); // 10-digit saudi commercial registration number
            $branch->commercial_registration_expiry = $faker_en->dateTimeBetween('2023-01-01', '2030-12-31')->format('Y-m-d');
            $branch->email = $faker_en->email;
            $branch->dial_code = 996; // saudi arabia dial code
            $branch->contact_no = $faker_en->numerify('5########'); // 9-digit saudi phone number without the leading zero
            $branch->city_id = $faker_en->numberBetween(1, 10); // assuming there are 10 cities
            $branch->bank_account_id = $faker_en->numberBetween(1, 1); // assuming there are 100 bank accounts
            $branch->created_at = $faker_en->dateTimeThisYear->format('Y-m-d H:i:s');


            $branch->save();

            $latitudeJeddah = 21.559327746112142;
            $longitudeJeddah = 39.14379918457559;

            $location = new BranchLocation();
            $location->store_branch_id = $branch->id;
            $location->location_description = $faker_en->address;

            // use the selected city to set the location attributes
            $location->google_maps_url = 'https://maps.google.com/?q=' . $latitudeJeddah . ',' . $longitudeJeddah;
            $location->longitude = $faker_en->randomFloat(6, $longitudeJeddah - 0.05, $longitudeJeddah + 0.05);
            $location->latitude = $faker_en->randomFloat(6, $latitudeJeddah - 0.05, $latitudeJeddah + 0.05);
            $location->district = "jeddah 23 st a4"; // you can choose between en_name and ar_name based on your needs
            $location->location_radius = $faker_en->randomFloat(2, 1, 10); // random radius between 1 and 10 km
            $location->save();

            $work_status = new BranchWorkStatus();
            $work_status->store_id = $branch->store_id;
            $work_status->store_branch_id = $branch->id;
            $work_status->status = $faker_en->randomElement(['active', 'busy', 'closed', 'inactive']);
            $work_status->start_time = '00:00:01';
            $work_status->end_time = '23:00:01';
            $work_status->save();
        }
    }

}
