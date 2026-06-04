<?php

namespace Tests\Feature;

use App\Models\MarketplaceListing;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminReportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_cannot_access_admin_reports(): void
    {
        $response = $this->get(route('admin.reports.index'));
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function non_admin_user_cannot_access_admin_reports(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get(route('admin.reports.index'));
        $response->assertStatus(404);
    }

    #[Test]
    public function admin_can_view_reports_dashboard_with_statistics(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 1. Create orders with some revenue
        $buyer = User::factory()->create();

        $order1 = Order::factory()->create([
            'user_id' => $buyer->id,
            'total_price' => 1500000,
            'status' => 'delivered',
            'created_at' => now(),
        ]);

        $order2 = Order::factory()->create([
            'user_id' => $buyer->id,
            'total_price' => 2500000,
            'status' => 'paid',
            'created_at' => now(),
        ]);

        $orderCancelled = Order::factory()->create([
            'user_id' => $buyer->id,
            'total_price' => 500000,
            'status' => 'cancelled',
            'created_at' => now(),
        ]);

        // 2. Create products with low stock alert
        $product1 = Product::factory()->create(['name' => 'Nike Pegasus 39']);
        $variantLow = ProductVariant::factory()->create([
            'product_id' => $product1->id,
            'stock' => 2,
            'size' => '41',
            'color' => 'Black',
        ]);

        $product2 = Product::factory()->create(['name' => 'Nike Air Max Excee']);
        $variantNormal = ProductVariant::factory()->create([
            'product_id' => $product2->id,
            'stock' => 20,
            'size' => '42',
            'color' => 'White',
        ]);

        // 3. Create top selling item via order_items
        $orderItem = OrderItem::factory()->create([
            'order_id' => $order1->id,
            'product_variant_id' => $variantNormal->id,
            'quantity' => 3,
            'price' => 500000,
        ]);

        // 4. Create marketplace listings
        $listing1 = MarketplaceListing::factory()->create([
            'user_id' => $buyer->id,
            'product_variant_id' => $variantNormal->id,
            'status' => 'pending',
        ]);

        $listing2 = MarketplaceListing::factory()->create([
            'user_id' => $buyer->id,
            'product_variant_id' => $variantNormal->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Báo cáo Hệ thống');

        // Total revenue (excluding cancelled) = 1.500.000 + 2.500.000 = 4.000.000
        $response->assertSee('4.000.000đ');

        // General stats assertions
        $response->assertSee('Thống kê Đơn hàng (Tổng số: 3)');
        $response->assertSee('Trạng thái Chợ đồ cũ C2C');
        $response->assertSee('Cảnh báo tồn kho (Sắp hết)');

        // Low stock warning list
        $response->assertSee('Nike Pegasus 39');
        $response->assertSee('Còn 2');

        // Top Selling list
        $response->assertSee('Top 5 Sản phẩm bán chạy nhất');
        $response->assertSee('Nike Air Max Excee');
    }
}
