<?php

namespace Database\Factories;

use App\Models\PlatformRating;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlatformRating>
 */
class PlatformRatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PlatformRating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = FakerFactory::create(); // Initialize the faker

        return [
            'platform' => $this->faker->randomElement(['web', 'app']),
            'body_massage' => $this->faker->optional()->text(100),
            'rating' => $this->faker->numberBetween(1, 5),
            'created_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),
        ];
    }
}
