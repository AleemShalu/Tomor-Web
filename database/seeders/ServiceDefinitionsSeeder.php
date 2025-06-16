<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceDefinitionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define service definitions
        $services = [
            [
                'name' => 'Delivery Cost',
                'code' => 1001,
                'description' => 'Cost associated with the delivery of goods',
                'service_currency_code' => 'SAR',  // Saudi Riyal
                'price' => 5.0000,  // Adjusted for hypothetical Saudi pricing
                'rate' => 0.0000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more services here as needed
        ];

        foreach ($services as $service) {
            // Check if the service with the same 'code' already exists
            $exists = DB::table('service_definitions')->where('code', $service['code'])->exists();

            if (!$exists) {
                // Insert the record if it does not exist
                DB::table('service_definitions')->insert($service);
            }
        }

        // Output success message
        $this->command->info('Service definitions inserted.');
    }
}
