<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Check if the settings already exist before creating
        Setting::firstOrCreate(
            ['id' => 1], // Assuming the id of the settings record is 1
            [
                'web_status' => true,
                'app_status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Output success message
        $this->command->info('Settings inserted or already exist.');
    }
}
