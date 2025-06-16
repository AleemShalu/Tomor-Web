<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'code' => 'SAR',
                'symbol' => 'ر.س.‏',
                'name' => 'Saudi Riyal',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'USD',
                'symbol' => '$',
                'name' => 'US Dollar',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'EUR',
                'symbol' => '€',
                'name' => 'Euro',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert or update currencies
        foreach ($currencies as $currency) {
            DB::table('currencies')->updateOrInsert(
                ['code' => $currency['code']], // Unique key
                $currency
            );
        }

        // Output success message
        $this->command->info('Currencies inserted or updated.');
    }
}
