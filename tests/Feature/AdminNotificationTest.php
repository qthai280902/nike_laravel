<?php

namespace Tests\Feature;

use App\Enums\MarketplaceListingStatus;
use App\Models\MarketplaceListing;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_dashboard_displays_badge_when_notifications_exist(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 1. Create a pending order
        Order::factory()->create(['status' => 'pending']);

        // 2. Create one open and one in_progress support ticket
        SupportTicket::create([
            'name' => 'User A',
            'email' => 'a@example.com',
            'subject' => 'Ticket 1',
            'message' => 'Message 1',
            'status' => 'open',
        ]);
        SupportTicket::create([
            'name' => 'User B',
            'email' => 'b@example.com',
            'subject' => 'Ticket 2',
            'message' => 'Message 2',
            'status' => 'in_progress',
        ]);

        // 3. Create a pending marketplace listing with a high stock variant (so it doesn't trigger low stock warning)
        $product = Product::factory()->create();
        $variant1 = ProductVariant::factory()->create(['product_id' => $product->id, 'stock' => 20]);
        MarketplaceListing::factory()->create([
            'product_variant_id' => $variant1->id,
            'status' => MarketplaceListingStatus::Pending,
        ]);

        // 4. Create a pending product review
        ProductReview::factory()->pending()->create([
            'product_id' => $product->id,
            'title' => 'Review waiting for admin',
        ]);

        // 5. Create a low stock variant (stock = 3 <= 5)
        ProductVariant::factory()->create(['product_id' => $product->id, 'stock' => 3]);

        // Total count = 1 (pending order) + 2 (open/in_progress tickets) + 1 (pending review) + 1 (pending listing) + 1 (low stock variant) = 6
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Check if badge is displayed with count 6
        $response->assertSee('absolute top-0 right-0 block h-4 w-4 rounded-full bg-red-500');
        $response->assertSee('6');

        // Check Vietnamese strings and links inside dropdown
        $response->assertSee('Thông báo quản trị');
        $response->assertSee('Đơn hàng chờ xử lý');
        $response->assertSee('Yêu cầu hỗ trợ đang mở');
        $response->assertSee('Đánh giá sản phẩm chờ duyệt');
        $response->assertSee('Tin C2C chờ duyệt');
        $response->assertSee('Sản phẩm sắp hết hàng');

        // Verify correct route links
        $response->assertSee(route('admin.orders.index', ['status' => 'pending']));
        $response->assertSee(route('admin.support.index'));
        $response->assertSee(route('admin.reviews.index', ['status' => ProductReview::STATUS_PENDING]));
        $response->assertSee(route('admin.marketplace.index'));
        $response->assertSee(route('admin.dashboard').'#low-stock-section');
    }

    #[Test]
    public function admin_dashboard_does_not_display_badge_when_no_notifications(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // No pending order (only paid/delivered/cancelled)
        Order::factory()->create(['status' => 'paid']);
        Order::factory()->create(['status' => 'delivered']);
        Order::factory()->create(['status' => 'cancelled']);

        // No open support ticket (only resolved/closed)
        SupportTicket::create([
            'name' => 'User C',
            'email' => 'c@example.com',
            'subject' => 'Ticket 3',
            'message' => 'Message 3',
            'status' => 'resolved',
        ]);
        SupportTicket::create([
            'name' => 'User D',
            'email' => 'd@example.com',
            'subject' => 'Ticket 4',
            'message' => 'Message 4',
            'status' => 'closed',
        ]);

        // No pending marketplace listing (only active/rejected/sold)
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'stock' => 15]);
        MarketplaceListing::factory()->create([
            'product_variant_id' => $variant->id,
            'status' => MarketplaceListingStatus::Active,
        ]);

        // No low stock variant (all variants have stock > 5)
        ProductVariant::factory()->create(['product_id' => $product->id, 'stock' => 10]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Check that badge (bg-red-500) is NOT visible
        $response->assertDontSee('absolute top-0 right-0 block h-4 w-4 rounded-full bg-red-500');
        $response->assertSee('Không có thông báo mới');
    }
}
