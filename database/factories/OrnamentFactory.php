<?php

namespace Database\Factories;

use App\Models\Ornament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ornament>
 */
class OrnamentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'           => fake()->unique()->words(2, true),
            'description'    => fake()->sentence(),
            'price_per_day'  => fake()->randomFloat(2, 50, 1000),
            'deposit_amount' => fake()->randomFloat(2, 100, 3000),
            'category'       => fake()->randomElement(['jewelry', 'hair_accessories', 'footwear', 'handbag', 'other']),
            'image_path'     => null,
            'status'         => 'available',
        ];
    }

    /**
     * Indicate the ornament is unavailable.
     */
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'unavailable',
        ]);
    }
}
