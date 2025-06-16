<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxCodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define tax codes
        $taxCodes = [
            ['code' => 'VAT', 'description' => 'Value Added Tax', 'tax_rate' => 15.00],
            // Add more tax codes as needed
        ];

        foreach ($taxCodes as $taxCode) {
            // Check if the tax code already exists
            $existingTaxCode = DB::table('tax_codes')->where('code', $taxCode['code'])->first();

            if (!$existingTaxCode) {
                // Insert the tax code if it does not exist
                DB::table('tax_codes')->insert([
                    'code' => $taxCode['code'],
                    'description' => $taxCode['description'],
                    'tax_rate' => $taxCode['tax_rate'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
