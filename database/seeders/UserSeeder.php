<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define users to insert
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@tomor.com',
                'password' => Hash::make(12345678),
                'dial_code' => null,
                'contact_no' => null,
                'status' => 1,
                'role' => 'admin',
            ],
//            [
//                'name' => 'dev.moyad',
//                'email' => 'owner@tomor.com',
//                'password' => Hash::make(12345678),
//                'dial_code' => 966,
//                'contact_no' => 553434031,
//                'status' => 1,
//                'role' => 'owner',
//            ],
//            [
//                'name' => 'dev.hamed',
//                'email' => 'worker@tomor.com',
//                'password' => Hash::make(12345678),
//                'dial_code' => 966,
//                'contact_no' => 532078751,
//                'status' => 1,
//                'role' => 'worker',
//            ],
//            [
//                'name' => 'dev.abdulmajeed',
//                'email' => 'worker_supervisor@tomor.com',
//                'password' => Hash::make(12345678),
//                'dial_code' => 966,
//                'contact_no' => 555555555,
//                'status' => 1,
//                'role' => 'worker_supervisor',
//            ],
//            [
//                'name' => 'dev.aamirm',
//                'email' => 'customer@tomor.com',
//                'password' => Hash::make(12345678),
//                'dial_code' => 966,
//                'contact_no' => 578973809,
//                'status' => 1,
//                'role' => 'customer',
//            ],
        ];

        foreach ($users as $userData) {
            // Check if user already exists
            $user = User::where('email', $userData['email'])->first();

            if (!$user) {
                // Create user if not exists
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'email_verified_at' => Carbon::now(),
                    'password' => $userData['password'],
                    'dial_code' => $userData['dial_code'],
                    'contact_no' => $userData['contact_no'],
                    'status' => $userData['status'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            // Assign role if it exists
            $role = Role::findByName($userData['role'], 'web');
            if ($role) {
                $user->assignRole($role);
            }
        }

        // Output success message
        $this->command->info('Users and roles seeded.');
    }
}
