<?php

namespace Database\Seeders;

use App\Models\PlatformRating;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlatformRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Assuming you have some users in the 'users' table already
        User::all()->each(function ($user) {
            PlatformRating::factory()
                ->count(rand(1, 5))  // Seed between 1 and 5 ratings for each user
                ->for($user)
                ->create();
        });
    }
}
