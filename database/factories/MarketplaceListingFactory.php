<?php

namespace Database\Factories;

use App\Enums\MarketplaceListingCondition;
use App\Enums\MarketplaceListingStatus;
use App\Models\MarketplaceListing;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MarketplaceListing>
 */
class MarketplaceListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_variant_id' => ProductVariant::factory(),
            'asking_price' => fake()->numberBetween(500000, 5000000),
            'condition' => fake()->randomElement(MarketplaceListingCondition::cases()),
            'seller_description' => fake()->sentence(),
            'status' => MarketplaceListingStatus::Pending,
        ];
    }
}
