<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $endDate = $this->faker->dateTimeBetween($startDate, '+2 months');

        return [
            // 'code' => $this->faker->unique()->lexify('[A-Z0-9]{8}'),
            'code' => strtoupper($this->faker->unique()->word),
            // 'coupon_type_id' => $this->faker->numberBetween(1, 3),
            'coupon_type_id' => 1,
            'discount_type_id' => $this->faker->numberBetween(1, 2),
            'discount_amount' => $this->faker->randomFloat(2, 0, 100),
            'discount_percentage' => $this->faker->randomFloat(2, 0, 100),
            'coupon_currency_code' => $this->faker->randomElement(['SAR']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'enabled' => $this->faker->boolean(),
            'max_uses' => $this->faker->numberBetween(1, 100),
            'max_uses_per_user' => $this->faker->numberBetween(1, 10),
            'usage_count' => 0,
            'min_amount' => $this->faker->randomFloat(2, 0, 200),
            // 'restrictions' => $this->faker->text(),
            'restrictions' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
