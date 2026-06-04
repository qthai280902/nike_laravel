<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
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
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'product_story' => fake()->paragraphs(2, true),
            'highlights' => [
                fake()->sentence(6),
                fake()->sentence(6),
                fake()->sentence(6),
            ],
            'care_instructions' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 50, 300),
            'image_url' => '/images/hero.png',
            'status' => 'active',
        ];
    }
}
