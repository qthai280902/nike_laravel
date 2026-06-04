<?php

namespace Tests\Feature;

use App\Models\MarketplaceListing;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function non_admin_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(404);
    }

    #[Test]
    public function admin_can_access_admin_dashboard_with_all_widgets(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create some sample data for widgets
        $product = Product::factory()->create(['name' => 'Nike Air Zoom Test']);
        $variant1 = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'size' => '42',
            'color' => 'Black',
            'stock' => 3, // low stock
        ]);
        $variant2 = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'size' => '43',
            'color' => 'White',
            'stock' => 10,
        ]);

        $seller = User::factory()->create(['role' => 'seller']);
        $listing = MarketplaceListing::factory()->create([
            'user_id' => $seller->id,
            'product_variant_id' => $variant1->id,
            'status' => 'pending',
            'asking_price' => 1500000,
        ]);

        $order = Order::factory()->create([
            'user_id' => $seller->id,
            'total_price' => 2000000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Tổng Doanh Thu');
        $response->assertSee('Đơn Hàng Mới');
        $response->assertSee('Thống kê Hoạt động');
        $response->assertSee('Trạng thái Đơn hàng');
        $response->assertSee('Chợ đồ cũ C2C');
        $response->assertSee('Tin C2C chờ duyệt');
        $response->assertSee('Lối tắt quản trị nhanh');
        $response->assertSee('Sản phẩm sắp hết hàng');

        // Check dynamic data
        $response->assertSee('Nike Air Zoom Test');
        $response->assertSee('Size: 42');
        $response->assertSee($listing->user->name);
        $response->assertSee('1.500.000₫');
    }
}
