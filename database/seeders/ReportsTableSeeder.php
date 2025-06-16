<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 100; $i++) {
            DB::table('reports')->insert([
                'uuid' => Str::uuid(),
                'ticket_id' => 'TICKET' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'report_subtype_id' => rand(1, 2),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'status' => $faker->randomElement(['Pending Review', 'In Progress', 'Resolved']),
                'report_title' => $faker->sentence,
                'body_message' => $faker->paragraph,
                'submission_time' => $faker->dateTimeThisMonth,
                'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),
                'updated_at' => now(),
            ]);
        }
    }
}
