<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'      => fake()->unique()->sentence(3),
            'content'    => '<p>' . implode('</p><p>', fake()->paragraphs(3)) . '</p>',
            'status'     => 'active',
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate the page is inactive (draft).
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
