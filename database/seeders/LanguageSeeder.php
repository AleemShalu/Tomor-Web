<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'code' => 'ar',
                'name' => 'Arabic',
                'direction' => 'RTL',
                'default' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'direction' => 'LTR',
                'default' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert languages if they don't already exist
        foreach ($languages as $language) {
            DB::table('languages')->updateOrInsert(
                ['code' => $language['code']], // Unique key
                $language
            );
        }

        // Output success message
        $this->command->info('Languages inserted or updated.');
    }
}
