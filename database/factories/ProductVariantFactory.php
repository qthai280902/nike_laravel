<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sku' => strtoupper(Str::random(10)),
            'size' => fake()->randomElement(['US 7', 'US 8', 'US 9', 'US 10', 'US 11', 'US 12']),
            'color' => fake()->safeColorName(),
            'stock' => fake()->numberBetween(0, 100),
        ];
    }
}
