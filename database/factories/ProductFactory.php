<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'image' => fake()->imageUrl(),
            'price' => fake()->numberBetween(0, 100),
            'quantity' => fake()->numberBetween(0, 100),
            'description' => fake()->text(),
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
