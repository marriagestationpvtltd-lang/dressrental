<?php

namespace Database\Factories;

use App\Models\Dress;
use App\Models\DressCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dress>
 */
class DressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id'    => DressCategory::factory(),
            'name'           => fake()->words(3, true),
            'description'    => fake()->paragraph(),
            'size'           => fake()->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size']),
            'price_per_day'  => fake()->randomFloat(2, 100, 5000),
            'deposit_amount' => fake()->randomFloat(2, 500, 10000),
            'status'         => 'available',
            'is_featured'    => false,
            'color'          => fake()->colorName(),
            'brand'          => fake()->company(),
            'views'          => 0,
        ];
    }

    /**
     * Indicate the dress is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate the dress is unavailable.
     */
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'unavailable',
        ]);
    }
}
