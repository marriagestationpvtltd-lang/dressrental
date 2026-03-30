<?php

namespace Database\Factories;

use App\Models\DressCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DressCategory>
 */
class DressCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name'        => $name,
            'slug'        => Str::slug($name) . '-' . Str::random(4),
            'description' => fake()->sentence(),
            'icon'        => '👗',
            'is_active'   => true,
            'sort_order'  => fake()->numberBetween(1, 10),
            'parent_id'   => null,
        ];
    }

    /**
     * Indicate this category is a sub-category of the given parent.
     */
    public function childOf(DressCategory $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * Indicate the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
