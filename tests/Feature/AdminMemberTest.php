<?php

namespace Tests\Feature;

use App\Enums\MarketplaceListingCondition;
use App\Models\MarketplaceListing;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminMemberTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_cannot_access_admin_members(): void
    {
        $response = $this->get(route('admin.members.index'));
        $response->assertRedirect(route('login'));

        $user = User::factory()->create();
        $response = $this->get(route('admin.members.show', $user));
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function non_admin_user_cannot_access_admin_members(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $otherUser = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.members.index'));
        $response->assertStatus(404);

        $response = $this->actingAs($user)->get(route('admin.members.show', $otherUser));
        $response->assertStatus(404);
    }

    #[Test]
    public function admin_can_view_members_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user1 = User::factory()->create(['name' => 'Nguyen Van A', 'email' => 'a@gmail.com', 'role' => 'customer']);
        $user2 = User::factory()->create(['name' => 'Tran Thi B', 'email' => 'b@gmail.com', 'role' => 'seller']);

        $response = $this->actingAs($admin)->get(route('admin.members.index'));

        $response->assertStatus(200);
        $response->assertSee('Quản lý Thành viên');
        $response->assertSee('Nguyen Van A');
        $response->assertSee('a@gmail.com');
        $response->assertSee('Khách hàng');
        $response->assertSee('Tran Thi B');
        $response->assertSee('b@gmail.com');
        $response->assertSee('Người bán');
    }

    #[Test]
    public function admin_can_search_members(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user1 = User::factory()->create(['name' => 'Nguyen Van An', 'email' => 'an@gmail.com']);
        $user2 = User::factory()->create(['name' => 'Pham Quoc Bao', 'email' => 'bao@gmail.com']);

        // Search by name
        $response = $this->actingAs($admin)->get(route('admin.members.index', ['search' => 'Nguyen']));
        $response->assertStatus(200);
        $response->assertSee('Nguyen Van An');
        $response->assertDontSee('Pham Quoc Bao');

        // Search by email
        $response = $this->actingAs($admin)->get(route('admin.members.index', ['search' => 'bao@gmail.com']));
        $response->assertStatus(200);
        $response->assertSee('Pham Quoc Bao');
        $response->assertDontSee('Nguyen Van An');
    }

    #[Test]
    public function admin_can_view_member_detail_with_orders_listings_and_wishlist(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['name' => 'Khach Hang C', 'email' => 'c@gmail.com']);

        // 1. Create Orders
        $order1 = Order::factory()->create([
            'user_id' => $member->id,
            'total_price' => 1200000,
            'status' => 'delivered',
            'payment_method' => 'cod',
        ]);
        $order2 = Order::factory()->create([
            'user_id' => $member->id,
            'total_price' => 800000,
            'status' => 'cancelled',
            'payment_method' => 'card',
        ]);

        // 2. Create Marketplace Listings
        $product = Product::factory()->create(['name' => 'Nike Pegasus Old']);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'size' => '40', 'color' => 'Blue']);
        $listing = MarketplaceListing::factory()->create([
            'user_id' => $member->id,
            'product_variant_id' => $variant->id,
            'asking_price' => 900000,
            'status' => 'active',
            'condition' => MarketplaceListingCondition::Good,
        ]);

        // 3. Create Wishlist
        $wishProduct = Product::factory()->create(['name' => 'Nike Pegasus New', 'price' => 3000000]);
        $member->wishlistProducts()->attach($wishProduct->id);

        $response = $this->actingAs($admin)->get(route('admin.members.show', $member));

        $response->assertStatus(200);
        $response->assertSee('Chi tiết Thành viên');
        $response->assertSee('Khach Hang C');
        $response->assertSee('c@gmail.com');

        // Total spent should only sum non-cancelled orders
        $response->assertSee('1.200.000đ');

        // Orders assertions
        $response->assertSee('Đơn hàng đã mua (2)');
        $response->assertSee('1.200.000đ');
        $response->assertSee('800.000đ');
        $response->assertSee('Đã giao');
        $response->assertSee('Đã hủy');

        // Marketplace assertions
        $response->assertSee('Tin đăng bán chợ đồ cũ C2C (1)');
        $response->assertSee('Nike Pegasus Old');
        $response->assertSee('900.000đ');
        $response->assertSee('Đang hiển thị');

        // Wishlist assertions
        $response->assertSee('Danh sách yêu thích (1)');
        $response->assertSee('Nike Pegasus New');
        $response->assertSee('3.000.000đ');
    }
}
