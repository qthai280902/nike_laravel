<?php

namespace Tests\Feature;

use App\Enums\MarketplaceListingStatus;
use App\Models\MarketplaceListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminMarketplaceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_queue_shows_preview_eye_link_for_pending_listing(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $listing = MarketplaceListing::factory()->freeform()->create([
            'status' => MarketplaceListingStatus::Pending,
            'product_name' => 'Nike Preview Queue Pair',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.marketplace.index'))
            ->assertOk()
            ->assertSee('Nike Preview Queue Pair')
            ->assertSee(route('admin.marketplace.show', $listing), false);
    }

    #[Test]
    public function admin_can_preview_pending_listing_before_moderation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create([
            'name' => 'Seller Preview',
            'email' => 'seller-preview@example.test',
        ]);
        $listing = MarketplaceListing::factory()->freeform()->create([
            'user_id' => $seller->id,
            'status' => MarketplaceListingStatus::Pending,
            'product_name' => 'Nike Preview Detail Pair',
            'seller_description' => 'Ảnh thật, đế sạch và còn hộp.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.marketplace.show', $listing))
            ->assertOk()
            ->assertSee('Nike Preview Detail Pair')
            ->assertSee('Seller Preview')
            ->assertSee('seller-preview@example.test')
            ->assertSee('Ảnh thật, đế sạch và còn hộp.')
            ->assertSee(route('admin.marketplace.update', [$listing->id, 'active']), false)
            ->assertSee(route('admin.marketplace.update', [$listing->id, 'rejected']), false);
    }

    #[Test]
    public function admin_can_approve_and_reject_from_preview_context(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $approveListing = MarketplaceListing::factory()->freeform()->create([
            'status' => MarketplaceListingStatus::Pending,
        ]);
        $rejectListing = MarketplaceListing::factory()->freeform()->create([
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
    public function non_admin_cannot_preview_admin_marketplace_listing(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $listing = MarketplaceListing::factory()->freeform()->create([
            'status' => MarketplaceListingStatus::Pending,
        ]);

        $this->actingAs($user)
            ->get(route('admin.marketplace.show', $listing))
            ->assertNotFound();
    }
}
