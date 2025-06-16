<?php

namespace Database\Seeders;

use App\Models\BranchLocation;
use App\Models\BranchWorkStatus;
use App\Models\City;
use App\Models\StoreBranch;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreBranchCustomSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Define branch attributes
        $longitude = 39.144143;
        $latitude = 21.559263;
        $radius = 500000;
        $cityId = 2;

        // Find city
        $city = City::find($cityId);
        if (!$city) {
            $this->command->error("City with ID {$cityId} not found.");
            return;
        }

        // Create branch if not exists
        $branch = StoreBranch::firstOrCreate([
            'store_id' => 1,
            'name_ar' => "مكسرات علي بابا - $city->ar_name",
            'name_en' => "Ali Baba nuts - $city->en_name"
        ], [
            'commercial_registration_no' => $faker->numerify('##########'),
            'commercial_registration_expiry' => $faker->dateTimeBetween('2024-01-01', '2030-12-31')->format('Y-m-d'),
            'email' => "alibaba.nuts@example.com",
            'dial_code' => "966",
            'contact_no' => "551212121",
            'city_id' => $city->id,
            'bank_account_id' => 1 // Change this if needed
        ]);

        // Create location if not exists
        BranchLocation::firstOrCreate([
            'store_branch_id' => $branch->id
        ], [
            'location_description' => $faker->address,
            'google_maps_url' => 'https://maps.google.com/?q=' . $latitude . ',' . $longitude,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'district' => "5, 14th Street",
            'location_radius' => $radius
        ]);

        // Create or update work status
        BranchWorkStatus::updateOrCreate(
            ['store_branch_id' => $branch->id],
            [
                'store_id' => $branch->store_id,
                'status' => "Active",
                'start_time' => '00:00:01',
                'end_time' => '23:00:01'
            ]
        );

        $this->command->info('Successfully created or updated the branch and related records.');

        // Update the 'store_id' for users if they exist
        $userIds = [3, 4];
        foreach ($userIds as $userId) {
            User::where('id', $userId)->update(['store_id' => 1]);
        }

        // Insert records into the branch_employees table if not exists
        $employees = [
            ['employee_id' => 3, 'store_branch_id' => $branch->id, 'role_id' => 3],
            ['employee_id' => 5, 'store_branch_id' => $branch->id, 'role_id' => 4],
        ];

        foreach ($employees as $employee) {
            DB::table('branch_employees')->updateOrInsert(
                ['employee_id' => $employee['employee_id'], 'store_branch_id' => $employee['store_branch_id']],
                array_merge($employee, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
