<?php

namespace Database\Factories;

use App\Models\Dress;
use App\Models\DressImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DressImage>
 */
class DressImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dress_id'   => Dress::factory(),
            'image_path' => 'dresses/' . Str::uuid() . '.jpg',
            'is_primary' => false,
            'sort_order' => fake()->numberBetween(2, 10),
        ];
    }

    /**
     * Indicate this is the primary image.
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
            'sort_order' => 1,
        ]);
    }
}
