<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminOrderInventoryReturnTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_cancels_pending_order_and_returns_inventory_once(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Inventory Admin']);
        $variant = ProductVariant::factory()->create(['stock' => 3]);
        $order = $this->createOrderWithItem('pending', $variant, 2);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $order), [
                'status' => 'cancelled',
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Hủy đơn thành công và đã hoàn kho.');

        $order->refresh();

        $this->assertSame('cancelled', $order->status);
        $this->assertSame(5, $variant->fresh()->stock);
        $this->assertNotNull($order->inventory_returned_at);
        $this->assertSame($admin->id, $order->inventory_returned_by_user_id);

        $this->actingAs($admin)
            ->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee('Đã hoàn kho')
            ->assertSee('Inventory Admin');
    }

    #[Test]
    public function admin_cancels_paid_order_and_returns_inventory(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $variant = ProductVariant::factory()->create(['stock' => 8]);
        $order = $this->createOrderWithItem('paid', $variant, 4);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $order), [
                'status' => 'cancelled',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $order->refresh();

        $this->assertSame('cancelled', $order->status);
        $this->assertSame(12, $variant->fresh()->stock);
        $this->assertNotNull($order->inventory_returned_at);
        $this->assertSame($admin->id, $order->inventory_returned_by_user_id);
    }

    #[Test]
    public function shipped_order_cannot_be_cancelled_and_stock_is_not_returned(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $variant = ProductVariant::factory()->create(['stock' => 6]);
        $order = $this->createOrderWithItem('shipped', $variant, 3);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $order), [
                'status' => 'cancelled',
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $order->refresh();

        $this->assertSame('shipped', $order->status);
        $this->assertSame(6, $variant->fresh()->stock);
        $this->assertNull($order->inventory_returned_at);
    }

    #[Test]
    public function cancelling_same_order_again_does_not_return_inventory_twice(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $variant = ProductVariant::factory()->create(['stock' => 1]);
        $order = $this->createOrderWithItem('pending', $variant, 2);

        $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
            'status' => 'cancelled',
        ])->assertRedirect();

        $order->refresh();
        $firstReturnedAt = $order->inventory_returned_at;

        $this->assertSame(3, $variant->fresh()->stock);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $order), [
                'status' => 'cancelled',
            ])
            ->assertRedirect()
            ->assertSessionHas('info');

        $order->refresh();

        $this->assertSame(3, $variant->fresh()->stock);
        $this->assertEquals($firstReturnedAt, $order->inventory_returned_at);
    }

    #[Test]
    public function delivered_order_cannot_be_cancelled_and_stock_is_not_returned(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $variant = ProductVariant::factory()->create(['stock' => 4]);
        $order = $this->createOrderWithItem('delivered', $variant, 2);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $order), [
                'status' => 'cancelled',
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Không thể đổi trạng thái đơn đã hoàn tất/đã hủy.');

        $order->refresh();

        $this->assertSame('delivered', $order->status);
        $this->assertSame(4, $variant->fresh()->stock);
        $this->assertNull($order->inventory_returned_at);
    }

    #[Test]
    public function cancelled_order_cannot_transition_to_other_status_and_stock_is_not_changed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $variant = ProductVariant::factory()->create(['stock' => 7]);
        $order = $this->createOrderWithItem('cancelled', $variant, 2);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $order), [
                'status' => 'paid',
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Không thể đổi trạng thái đơn đã hoàn tất/đã hủy.');

        $order->refresh();

        $this->assertSame('cancelled', $order->status);
        $this->assertSame(7, $variant->fresh()->stock);
        $this->assertNull($order->inventory_returned_at);
    }

    #[Test]
    public function cancelling_order_with_multiple_items_returns_each_variant_quantity(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $firstVariant = ProductVariant::factory()->create(['stock' => 10]);
        $secondVariant = ProductVariant::factory()->create(['stock' => 20]);
        $order = Order::factory()->create(['status' => 'pending']);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_variant_id' => $firstVariant->id,
            'quantity' => 2,
        ]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_variant_id' => $secondVariant->id,
            'quantity' => 5,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $order), [
                'status' => 'cancelled',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame(12, $firstVariant->fresh()->stock);
        $this->assertSame(25, $secondVariant->fresh()->stock);
        $this->assertNotNull($order->fresh()->inventory_returned_at);
    }

    #[Test]
    public function cancelling_order_with_deleted_variant_skips_bad_item_without_wrong_stock_return(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $validVariant = ProductVariant::factory()->create(['stock' => 9]);
        $deletedVariant = ProductVariant::factory()->create(['stock' => 30]);
        $order = Order::factory()->create(['status' => 'pending']);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_variant_id' => $validVariant->id,
            'quantity' => 3,
        ]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_variant_id' => $deletedVariant->id,
            'quantity' => 4,
        ]);

        $deletedVariant->delete();

        $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $order), [
                'status' => 'cancelled',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $order->refresh();

        $this->assertSame(12, $validVariant->fresh()->stock);
        $this->assertSame(30, ProductVariant::withTrashed()->find($deletedVariant->id)->stock);
        $this->assertNotNull($order->inventory_returned_at);
        $this->assertStringContainsString('Bỏ qua 1 item', $order->inventory_return_note);
    }

    private function createOrderWithItem(string $status, ProductVariant $variant, int $quantity): Order
    {
        $order = Order::factory()->create(['status' => $status]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'quantity' => $quantity,
        ]);

        return $order;
    }
}
