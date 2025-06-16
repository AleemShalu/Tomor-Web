<?php

namespace Database\Seeders;

use App\Models\PaymentConfiguration;
use Illuminate\Database\Seeder;

class PaymentConfigurationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prefix options that should start the identity number
        $prefixOptions = ['1', '2', '7'];

        // Update or insert prefix options
        PaymentConfiguration::updateOrInsert(
            ['key' => 'identity_number_prefix_options'],
            ['value' => implode(',', $prefixOptions)]
        );

        // Update or insert payment check time limit
        PaymentConfiguration::updateOrInsert(
            ['key' => 'payment_check_time_limit'],
            ['value' => '5']
        );
    }
}
