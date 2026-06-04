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
    public function marketplace_listing_defaults_to_pending_and_can_link_to_b2c_variant(): void
    {
        $listing = MarketplaceListing::factory()->create();

        $this->assertSame(MarketplaceListingStatus::Pending, $listing->status);
        $this->assertInstanceOf(User::class, $listing->user);
        $this->assertInstanceOf(ProductVariant::class, $listing->variant);
        $this->assertInstanceOf(Product::class, $listing->variant->product);
        $this->assertSame($listing->variant->product->name, $listing->display_name);
    }

    #[Test]
    public function marketplace_service_creates_pending_freeform_listing(): void
    {
        $user = User::factory()->create();

        $listing = app(MarketplaceService::class)->createListing([
            'product_name' => 'Nike Air Max 90 Essential',
            'brand' => 'Nike',
            'size' => 'US 9',
            'color' => 'Trắng/Đen',
            'image_url' => 'https://static.nike.com/example/air-max-90.png',
            'asking_price' => 2500000,
            'condition' => MarketplaceListingCondition::Good->value,
            'seller_description' => 'Đế còn đẹp, upper sạch và có hộp.',
        ], $user->id);

        $this->assertSame($user->id, $listing->user_id);
        $this->assertNull($listing->product_variant_id);
        $this->assertSame('Nike Air Max 90 Essential', $listing->display_name);
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
        $this->get(route('marketplace.create'))->assertRedirect(route('login'));

        $this->post(route('marketplace.store'), [
            'product_name' => 'Nike Dunk Low Vintage',
            'brand' => 'Nike',
            'size' => 'US 9',
            'color' => 'Trắng/Đen',
            'asking_price' => 2500000,
            'condition' => MarketplaceListingCondition::Good->value,
            'seller_description' => 'Còn đẹp.',
        ])->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_user_can_submit_freeform_marketplace_listing_for_review(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('marketplace.store'), [
            'product_name' => 'Nike Dunk Low Vintage',
            'brand' => 'Nike',
            'size' => 'US 9',
            'color' => 'Trắng/Đen',
            'image_url' => 'https://static.nike.com/example/dunk-low.png',
            'asking_price' => 2500000,
            'condition' => MarketplaceListingCondition::LikeNew->value,
            'seller_description' => 'Mang hai lần, đế sạch và không bong keo.',
        ])->assertRedirect(route('marketplace.index'));

        $this->assertDatabaseHas('marketplace_listings', [
            'user_id' => $user->id,
            'product_variant_id' => null,
            'product_name' => 'Nike Dunk Low Vintage',
            'status' => MarketplaceListingStatus::Pending->value,
        ]);
    }

    #[Test]
    public function authenticated_user_can_still_submit_catalog_linked_listing(): void
    {
        $user = User::factory()->create();
        $variant = ProductVariant::factory()->create();

        $this->actingAs($user)->post(route('marketplace.store'), [
            'product_variant_id' => $variant->id,
            'asking_price' => 1800000,
            'condition' => MarketplaceListingCondition::Good->value,
            'seller_description' => 'Đôi này còn bám sân tốt.',
        ])->assertRedirect(route('marketplace.index'));

        $this->assertDatabaseHas('marketplace_listings', [
            'user_id' => $user->id,
            'product_variant_id' => $variant->id,
            'status' => MarketplaceListingStatus::Pending->value,
        ]);
    }

    #[Test]
    public function create_page_no_longer_locks_main_form_behind_catalog_selection(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('marketplace.create'))
            ->assertOk()
            ->assertSee('Tên giày')
            ->assertSee('Không bắt buộc')
            ->assertDontSee('opacity-20');
    }

    #[Test]
    public function marketplace_index_does_not_crash_with_active_freeform_listing(): void
    {
        $user = User::factory()->create();
        $listing = MarketplaceListing::factory()->freeform()->create([
            'status' => MarketplaceListingStatus::Active,
            'product_name' => 'Nike Air Max 90 Seller Pair',
        ]);

        $this->actingAs($user)
            ->get(route('marketplace.index'))
            ->assertOk()
            ->assertSee('Nike Air Max 90 Seller Pair')
            ->assertSee(route('marketplace.show', $listing));
    }

    #[Test]
    public function marketplace_detail_does_not_crash_with_active_freeform_listing(): void
    {
        $user = User::factory()->create();
        $listing = MarketplaceListing::factory()->freeform()->create([
            'status' => MarketplaceListingStatus::Active,
            'product_name' => 'Nike Pegasus Trail Seller Pair',
            'seller_description' => 'Giày còn sạch và có hộp.',
        ]);

        $this->actingAs($user)
            ->get(route('marketplace.show', $listing))
            ->assertOk()
            ->assertSee('Nike Pegasus Trail Seller Pair')
            ->assertSee('Giày còn sạch và có hộp.')
            ->assertSee('Tin đăng tự nhập');
    }

    #[Test]
    public function existing_catalog_linked_listing_still_displays(): void
    {
        $user = User::factory()->create();
        $listing = MarketplaceListing::factory()->create([
            'status' => MarketplaceListingStatus::Active,
            'asking_price' => 1250000,
            'seller_description' => 'Form cũ vẫn hiển thị.',
        ]);

        $this->actingAs($user)
            ->get(route('marketplace.show', $listing))
            ->assertOk()
            ->assertSee($listing->variant->product->name)
            ->assertSee('1.250.000₫')
            ->assertSee('Catalog cửa hàng')
            ->assertSee('Form cũ vẫn hiển thị.');
    }

    #[Test]
    public function admin_can_view_pending_marketplace_queue_with_freeform_listing(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $listing = MarketplaceListing::factory()->freeform()->create([
            'status' => MarketplaceListingStatus::Pending,
            'product_name' => 'Nike Cortez Seller Pair',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.marketplace.index'))
            ->assertOk()
            ->assertSee('Nike Cortez Seller Pair')
            ->assertSee($listing->display_source);
    }

    #[Test]
    public function admin_can_approve_and_reject_pending_listings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $approveListing = MarketplaceListing::factory()->freeform()->create([
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

    #[Test]
    public function authenticated_user_cannot_view_inactive_marketplace_listing_detail(): void
    {
        $user = User::factory()->create();
        $pendingListing = MarketplaceListing::factory()->freeform()->create([
            'status' => MarketplaceListingStatus::Pending,
        ]);
        $rejectedListing = MarketplaceListing::factory()->create([
            'status' => MarketplaceListingStatus::Rejected,
        ]);

        $this->actingAs($user)->get(route('marketplace.show', $pendingListing))->assertStatus(404);
        $this->actingAs($user)->get(route('marketplace.show', $rejectedListing))->assertStatus(404);
    }
}
