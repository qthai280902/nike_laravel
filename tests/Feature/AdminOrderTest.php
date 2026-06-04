<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_is_redirected_to_login_when_accessing_orders(): void
    {
        $response = $this->get(route('admin.orders.index'));
        $response->assertRedirect(route('login'));

        $order = Order::factory()->create();
        $response = $this->get(route('admin.orders.show', $order));
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function customer_cannot_access_orders(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = Order::factory()->create();

        $response = $this->actingAs($customer)->get(route('admin.orders.index'));
        $response->assertStatus(404);

        $response = $this->actingAs($customer)->get(route('admin.orders.show', $order));
        $response->assertStatus(404);
    }

    #[Test]
    public function admin_can_view_orders_index_and_search_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order1 = Order::factory()->create([
            'id' => '11111111-2222-3333-4444-555555555555',
            'shipping_name' => 'Nguyen Van An',
            'status' => 'pending',
            'total_price' => 1500000,
        ]);
        $order2 = Order::factory()->create([
            'id' => '99999999-2222-3333-4444-555555555555',
            'shipping_name' => 'Tran Thi Binh',
            'status' => 'paid',
            'total_price' => 2500000,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));
        $response->assertStatus(200);
        $response->assertSee('Nguyen Van An');
        $response->assertSee('Tran Thi Binh');
        $response->assertSee('1.500.000');
        $response->assertSee('2.500.000');

        // Search name
        $response = $this->actingAs($admin)->get(route('admin.orders.index', ['search' => 'Nguyen']));
        $response->assertSee('Nguyen Van An');
        $response->assertDontSee('Tran Thi Binh');

        // Filter status
        $response = $this->actingAs($admin)->get(route('admin.orders.index', ['status' => 'paid']));
        $response->assertSee('Tran Thi Binh');
        $response->assertDontSee('Nguyen Van An');
    }

    #[Test]
    public function admin_can_view_order_show(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['name' => 'Nike Pegasus Trail']);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'size' => '42',
            'color' => 'Black',
            'sku' => 'NIKE-PEG-42',
        ]);
        $order = Order::factory()->create([
            'shipping_name' => 'Nguyen Van An',
            'shipping_email' => 'an@gmail.com',
            'status' => 'pending',
        ]);
        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
            'price' => 1200000,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.show', $order));
        $response->assertStatus(200);
        $response->assertSee('Nguyen Van An');
        $response->assertSee('an@gmail.com');
        $response->assertSee('Nike Pegasus Trail');
        $response->assertSee('Size: 42');
        $response->assertSee('Màu: Black');
        $response->assertSee('NIKE-PEG-42');
        $response->assertSee('1.200.000');
    }

    #[Test]
    public function admin_can_update_valid_order_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->create(['status' => 'pending']);

        // pending -> paid
        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
            'status' => 'paid',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('paid', $order->fresh()->status);

        // paid -> shipped
        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
            'status' => 'shipped',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('shipped', $order->fresh()->status);

        // shipped -> delivered
        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
            'status' => 'delivered',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('delivered', $order->fresh()->status);
    }

    #[Test]
    public function admin_cannot_update_invalid_status_value(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
            'status' => 'invalid-status',
        ]);
        $response->assertSessionHasErrors('status');
        $this->assertEquals('pending', $order->fresh()->status);
    }

    #[Test]
    public function admin_cannot_perform_invalid_status_transitions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 1. pending cannot go directly to delivered
        $order1 = Order::factory()->create(['status' => 'pending']);
        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order1), [
            'status' => 'delivered',
        ]);
        $response->assertSessionHas('error');
        $this->assertEquals('pending', $order1->fresh()->status);

        // 2. paid cannot go directly to delivered
        $order2 = Order::factory()->create(['status' => 'paid']);
        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order2), [
            'status' => 'delivered',
        ]);
        $response->assertSessionHas('error');
        $this->assertEquals('paid', $order2->fresh()->status);

        // 3. shipped cannot go to paid or cancelled or pending
        $order3 = Order::factory()->create(['status' => 'shipped']);
        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order3), [
            'status' => 'cancelled',
        ]);
        $response->assertSessionHas('error');
        $this->assertEquals('shipped', $order3->fresh()->status);
    }

    #[Test]
    public function admin_cannot_transition_from_terminal_states(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 1. delivered is terminal
        $orderDelivered = Order::factory()->create(['status' => 'delivered']);
        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $orderDelivered), [
            'status' => 'pending',
        ]);
        $response->assertSessionHas('error');
        $this->assertEquals('delivered', $orderDelivered->fresh()->status);

        // 2. cancelled is terminal
        $orderCancelled = Order::factory()->create(['status' => 'cancelled']);
        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $orderCancelled), [
            'status' => 'pending',
        ]);
        $response->assertSessionHas('error');
        $this->assertEquals('cancelled', $orderCancelled->fresh()->status);
    }
}
