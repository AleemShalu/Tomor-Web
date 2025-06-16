<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkDaysTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('work_days')->insert([
                'employee_id' => 3, // Assuming 10 employees
//                'employee_id' => $faker->numberBetween(1, 10), // Assuming 10 employees
                'work_date' => $faker->dateTimeBetween('2023-09-10', '2023-09-30')->format('Y-m-d'),
                'start_time' => $faker->time('H:i', '08:00'), // Assuming start time is between 8:00 AM and 9:00 AM
                'end_time' => $faker->time('H:i', '16:00'),   // Assuming end time is between 4:00 PM and 5:00 PM
                'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),
                'updated_at' => now(),
            ]);
        }
    }
}
