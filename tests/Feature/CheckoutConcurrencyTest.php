<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Services\CartService;
use App\Services\CheckoutService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CheckoutConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    protected CheckoutService $checkoutService;

    protected CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->checkoutService = app(CheckoutService::class);
        $this->cartService = app(CartService::class);
    }

    /** @test */
    public function lock_for_update_prevents_negative_stock_simulation()
    {
        // 1. Setup variant with only 1 item
        $variant = ProductVariant::factory()->create(['stock' => 1]);
        $this->cartService->add($variant->id, 1);

        $shippingData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123456789',
            'address' => '123 Nike St',
            'payment_method' => 'cod',
        ];

        // 2. Simulate User A starting checkout and holding the lock
        DB::beginTransaction();

        // This simulates the locking part of CheckoutService::process
        $lockedVariant = ProductVariant::where('id', $variant->id)->lockForUpdate()->first();
        $this->assertEquals(1, $lockedVariant->stock);

        // 3. Simulate User B trying to checkout concurrently
        // In a real multi-threaded env, this would wait.
        // Here, we verify that if we try to deduct 1 again while User A hasn't committed,
        // the logic (if run in another process) would fail.

        // To prove the lock is active, we can't easily do it in one thread,
        // but we can verify the service logic handles stock depletion correctly.

        $this->checkoutService->process($shippingData);

        DB::commit();

        $this->assertEquals(0, $variant->fresh()->stock);
        $this->assertEquals(1, Order::count());

        // 4. Try checking out again with same item (now out of stock)
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Stock mismatch');

        $this->cartService->add($variant->id, 1);
        $this->checkoutService->process($shippingData);
    }

    /** @test */
    public function checkout_snapshots_exact_price_into_order_items()
    {
        $variant = ProductVariant::factory()->create([
            'stock' => 10,
        ]);

        // Mocking a price on the product
        $variant->product->update(['price' => 199.99]);

        $this->cartService->add($variant->id, 1);

        $shippingData = [
            'name' => 'Price Tester',
            'email' => 'price@example.com',
            'phone' => '123456789',
            'address' => 'Price Check Ave',
            'payment_method' => 'cod',
        ];

        // Process order
        $order = $this->checkoutService->process($shippingData);

        // Verify price snapshot
        $orderItem = $order->items->first();
        $this->assertEquals(199.99, $orderItem->price);

        // Change product price after order
        $variant->product->update(['price' => 299.99]);

        // Verify order item price remains unchanged (snapshot preserved)
        $this->assertEquals(199.99, $orderItem->fresh()->price);
    }
}
