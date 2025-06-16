<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class FilamentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $role = Role::firstOrCreate(
            ['name' => 'super-admin'],
            [
                'name_en' => 'Super Administrator',
                'name_ar' => 'مشرف عام',
                'guard_name' => 'web'
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'superadmin@tomor.com'],
            [
                'name' => 'superadmin',
                'password' => Hash::make('admin@2023'),
            ]
        );

        if (!$user->hasRole('superadmin')) {
            $user->assignRole($role);
        }
    }
}
