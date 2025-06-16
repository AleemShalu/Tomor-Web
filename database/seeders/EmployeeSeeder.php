<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = FakerFactory::create(); // Initialize the faker
        $workerRole = Role::findByName('worker'); // Assuming the role name is 'worker'
        $workerSupervisorRole = Role::findByName('worker_supervisor'); // Assuming the role name is 'worker'
        $supervisorRole = Role::findByName('supervisor'); // Assuming the role name is 'supervisor'

        // Create supervisors
        for ($i = 0; $i < 10; $i++) {
            $uniquePhoneNumber = $this->generateUniquePhoneNumber();
            $email = strtolower(str_replace(' ', '.', $faker->unique()->name)) . $faker->unique()->randomNumber(2) . '@example.com';

            $user = User::create([
                'name' => $faker->name,
                'email' => $email,
                'status' => 1,
                'contact_no' => $uniquePhoneNumber,
                'password' => Hash::make('password123'),
                'store_id' => 1,
                'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),

            ]);
            $user->assignRole($supervisorRole);
            // Attach the supervisor position to the user
            $user->employee_roles()->attach(5, ['store_branch_id' => 1]);
        }

        // Create workers
        for ($i = 0; $i < 40; $i++) {
            $uniquePhoneNumber = $this->generateUniquePhoneNumber();
            $email = strtolower(str_replace(' ', '.', $faker->unique()->name)) . $faker->unique()->randomNumber(2) . '@example.com';

            $user = User::create([
                'name' => $faker->name,
                'email' => $email,
                'status' => 1,
                'contact_no' => $uniquePhoneNumber,
                'password' => Hash::make('password123'),
                'store_id' => 1,
                'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),

            ]);
            $user->assignRole($workerRole);

            // Attach the worker position to the user
            $user->employee_roles()->attach(3, ['store_branch_id' => 1]);
        }

        // Create workers supervisor
        for ($i = 0; $i < 40; $i++) {
            $uniquePhoneNumber = $this->generateUniquePhoneNumber();
            $email = strtolower(str_replace(' ', '.', $faker->unique()->name)) . $faker->unique()->randomNumber(2) . '@example.com';

            $user = User::create([
                'name' => $faker->name,
                'email' => $email,
                'status' => 1,
                'contact_no' => $uniquePhoneNumber,
                'password' => Hash::make('password123'),
                'store_id' => 1,
                'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),
            ]);
            $user->assignRole($workerSupervisorRole);

            // Attach the worker position to the user
            $user->employee_roles()->attach(4, ['store_branch_id' => 1]);
        }
    }

    private function generateUniquePhoneNumber()
    {
        $faker = Faker::create();

        // Generate a random 8-digit number (excluding the "05" prefix)
        $randomNumber = $faker->randomNumber(8);
        $phoneNumber = '05' . $randomNumber;

        // Check if the phone number already exists in the database
        $exists = User::where('contact_no', $phoneNumber)->exists();

        // If the phone number exists, regenerate until we find a unique one
        while ($exists) {
            $randomNumber = $faker->randomNumber(8);
            $phoneNumber = '05' . $randomNumber;
            $exists = User::where('contact_no', $phoneNumber)->exists();
        }

        return $phoneNumber;
    }
}
