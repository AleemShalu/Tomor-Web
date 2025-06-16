<?php

namespace Database\Seeders;

use App\Models\LocationConfig;
use Illuminate\Database\Seeder;

class LocationConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        LocationConfig::firstOrCreate(
            ['code' => 'BR'],
            [
                'name' => 'branch_radius',
                'unit' => 'm',
                'max_radius' => 50,
                'min_radius' => 5,
            ]
        );

        LocationConfig::firstOrCreate(
            ['code' => 'CR'],
            [
                'name' => 'customer_radius',
                'unit' => 'm',
                'max_radius' => 50,
                'min_radius' => 0,
            ]
        );
    }
}
