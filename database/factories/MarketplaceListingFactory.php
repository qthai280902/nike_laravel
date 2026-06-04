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
            'product_name' => null,
            'brand' => null,
            'size' => null,
            'color' => null,
            'image_url' => null,
            'asking_price' => fake()->numberBetween(500000, 5000000),
            'condition' => fake()->randomElement(MarketplaceListingCondition::cases()),
            'seller_description' => fake()->sentence(),
            'status' => MarketplaceListingStatus::Pending,
        ];
    }

    /**
     * Create a seller-entered listing that is not linked to the B2C catalog.
     */
    public function freeform(): static
    {
        return $this->state(fn (): array => [
            'product_variant_id' => null,
            'product_name' => fake()->randomElement([
                'Nike Air Max 90 Essential',
                'Nike Dunk Low Vintage',
                'Nike Pegasus Trail',
            ]),
            'brand' => 'Nike',
            'size' => fake()->randomElement(['US 7', 'US 8', 'US 9', 'US 10']),
            'color' => fake()->randomElement(['Trắng/Đen', 'Đen/Xám', 'Kem/Nâu']),
            'image_url' => 'https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/8e97f699-245c-4433-875f-3ee0a1f49615/NIKE+DUNK+LOW+RETRO.png',
        ]);
    }
}
