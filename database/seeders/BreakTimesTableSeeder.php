<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BreakTimesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            // Generate a work day with a start time between 8:00 AM and 9:00 AM
            // and an end time between 4:00 PM and 5:00 PM
            $workDayStartTime = $faker->time('H:i', '08:00');
            $workDayEndTime = $faker->time('H:i', '16:00');

            // Calculate a random break start time within the work day
            $breakStart = $faker->time('H:i', $workDayStartTime, $workDayEndTime);

            // Calculate a random break end time within the work day
            $breakEnd = $faker->time('H:i', $breakStart, $workDayEndTime);

            DB::table('break_times')->insert([
                'work_day_id' => $index, // Assuming 10 work days
                'break_start_time' => $breakStart,
                'break_end_time' => $breakEnd,
                'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),
                'updated_at' => now(),
            ]);
        }
    }
}
