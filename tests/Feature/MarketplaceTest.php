<?php

namespace Tests\Feature;

use App\Enums\MarketplaceListingCondition;
use App\Enums\MarketplaceListingStatus;
use App\Models\MarketplaceListing;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\MarketplaceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function marketplace_listing_defaults_to_pending_and_links_to_b2c_variant(): void
    {
        $listing = MarketplaceListing::factory()->create();

        $this->assertSame(MarketplaceListingStatus::Pending, $listing->status);
        $this->assertInstanceOf(User::class, $listing->user);
        $this->assertInstanceOf(ProductVariant::class, $listing->variant);
        $this->assertInstanceOf(Product::class, $listing->variant->product);
    }

    #[Test]
    public function marketplace_service_creates_pending_listing_for_product_variant(): void
    {
        $user = User::factory()->create();
        $variant = ProductVariant::factory()->create();

        $listing = app(MarketplaceService::class)->createListing([
            'product_variant_id' => $variant->id,
            'asking_price' => 2500000,
            'condition' => MarketplaceListingCondition::Good->value,
            'seller_description' => 'Kept clean and ready to wear.',
        ], $user->id);

        $this->assertSame($user->id, $listing->user_id);
        $this->assertSame($variant->id, $listing->product_variant_id);
        $this->assertSame(MarketplaceListingStatus::Pending, $listing->status);
    }

    #[Test]
    public function product_search_endpoint_returns_product_summary_without_variants(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Nike Air Max Pulse',
            'slug' => 'nike-air-max-pulse',
            'status' => 'active',
        ]);
        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
        ]);

        $response = $this->actingAs($user)->getJson(route('marketplace.search', ['q' => 'Air Max']));

        $response->assertOk()
            ->assertJsonPath('data.0.id', $product->id)
            ->assertJsonPath('data.0.name', 'Nike Air Max Pulse')
            ->assertJsonMissingPath('data.0.variants');
    }

    #[Test]
    public function variants_endpoint_returns_sizes_for_selected_product(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'size' => 'US 10',
            'color' => 'Black/White',
            'stock' => 3,
        ]);
        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'size' => 'US 11',
            'stock' => 0,
        ]);

        $response = $this->actingAs($user)->getJson(route('marketplace.products.variants', $product));

        $response->assertOk()
            ->assertJsonPath('data.0.id', $variant->id)
            ->assertJsonPath('data.0.size', 'US 10')
            ->assertJsonCount(1, 'data');
    }

    #[Test]
    public function guest_is_redirected_from_marketplace_create_and_store(): void
    {
        $variant = ProductVariant::factory()->create();

        $this->get(route('marketplace.create'))->assertRedirect(route('login'));

        $this->post(route('marketplace.store'), [
            'product_variant_id' => $variant->id,
            'asking_price' => 2500000,
            'condition' => MarketplaceListingCondition::Good->value,
        ])->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_user_can_submit_marketplace_listing_for_review(): void
    {
        $user = User::factory()->create();
        $variant = ProductVariant::factory()->create();

        $this->actingAs($user)->post(route('marketplace.store'), [
            'product_variant_id' => $variant->id,
            'asking_price' => 2500000,
            'condition' => MarketplaceListingCondition::LikeNew->value,
            'seller_description' => 'Only worn twice.',
        ])->assertRedirect(route('marketplace.index'));

        $this->assertDatabaseHas('marketplace_listings', [
            'user_id' => $user->id,
            'product_variant_id' => $variant->id,
            'status' => MarketplaceListingStatus::Pending->value,
        ]);
    }

    #[Test]
    public function admin_can_view_pending_marketplace_queue(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $listing = MarketplaceListing::factory()->create([
            'status' => MarketplaceListingStatus::Pending,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.marketplace.index'))
            ->assertOk()
            ->assertSee($listing->variant->product->name);
    }

    #[Test]
    public function admin_can_approve_and_reject_pending_listings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $approveListing = MarketplaceListing::factory()->create([
            'status' => MarketplaceListingStatus::Pending,
        ]);
        $rejectListing = MarketplaceListing::factory()->create([
            'status' => MarketplaceListingStatus::Pending,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.marketplace.update', [$approveListing, MarketplaceListingStatus::Active->value]))
            ->assertRedirect();

        $this->actingAs($admin)
            ->patch(route('admin.marketplace.update', [$rejectListing, MarketplaceListingStatus::Rejected->value]))
            ->assertRedirect();

        $this->assertSame(MarketplaceListingStatus::Active, $approveListing->fresh()->status);
        $this->assertSame(MarketplaceListingStatus::Rejected, $rejectListing->fresh()->status);
    }

    #[Test]
    public function non_admin_cannot_access_marketplace_queue(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get(route('admin.marketplace.index'))
            ->assertNotFound();
    }
}
