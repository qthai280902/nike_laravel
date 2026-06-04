<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_b2c_checkout_flow_works_correctly(): void
    {
        // 1. Setup a product variant with stock
        $variant = ProductVariant::factory()->create([
            'stock' => 5,
            'price_override' => 1500000,
        ]);

        // 2. Add variant to cart via AJAX
        $response = $this->postJson(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'cart_count' => 1,
        ]);

        // Verify cart is in session under 'nike_cart'
        $cartService = app(CartService::class);
        $this->assertCount(1, $cartService->items());
        $this->assertEquals(2, $cartService->items()->get($variant->id)['qty']);

        // 3. Trying to visit checkout as guest redirects to login
        $checkoutResponse = $this->get(route('checkout.index'));
        $checkoutResponse->assertRedirect(route('login'));

        // 4. Authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // 5. Visit checkout page
        $checkoutViewResponse = $this->get(route('checkout.index'));
        $checkoutViewResponse->assertStatus(200);
        $checkoutViewResponse->assertSee($variant->product->name);
        $checkoutViewResponse->assertSee('3.000.000'); // total price formatted in summary

        // 6. Submit checkout form
        $shippingData = [
            'name' => 'Nguyen Van A',
            'email' => 'a@example.com',
            'phone' => '0987654321',
            'address' => '123 Le Loi, District 1, HCMC',
            'payment_method' => 'cod',
        ];

        $submitResponse = $this->post(route('checkout.store'), $shippingData);

        // Redirects to profile on success
        $submitResponse->assertRedirect(route('profile.index'));

        // Verify database states
        $this->assertEquals(3, $variant->fresh()->stock); // Stock decremented by 2 (5 - 2 = 3)
        $this->assertEquals(1, Order::count());
        $this->assertEquals(1, OrderItem::count());

        $order = Order::first();
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals(3000000, $order->total_price); // 1.5M * 2 = 3M
        $this->assertEquals('pending', $order->status);
        $this->assertEquals('Nguyen Van A', $order->shipping_name);

        $orderItem = OrderItem::first();
        $this->assertEquals($order->id, $orderItem->order_id);
        $this->assertEquals($variant->id, $orderItem->product_variant_id);
        $this->assertEquals(2, $orderItem->quantity);
        $this->assertEquals(1500000, $orderItem->price);

        // Verify cart is cleared
        $this->assertCount(0, $cartService->items());

        // 7. Visit profile page and ensure it doesn't crash
        $profileResponse = $this->get(route('profile.index'));
        $profileResponse->assertStatus(200);
        $profileResponse->assertSee(number_format($order->total_price, 0, ',', '.'));
    }

    public function test_checkout_fails_if_cart_is_empty(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Accessing checkout index redirects
        $response = $this->get(route('checkout.index'));
        $response->assertRedirect(route('catalog.index'));

        // Post submit fails validation or business logic
        $submitResponse = $this->post(route('checkout.store'), [
            'name' => 'Nguyen Van A',
            'email' => 'a@example.com',
            'phone' => '0987654321',
            'address' => '123 Le Loi',
            'payment_method' => 'cod',
        ]);
        $submitResponse->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function test_checkout_fails_if_stock_is_insufficient(): void
    {
        $variant = ProductVariant::factory()->create([
            'stock' => 1,
            'price_override' => 1000000,
        ]);

        // Add 2 to cart
        $this->postJson(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $shippingData = [
            'name' => 'Nguyen Van A',
            'email' => 'a@example.com',
            'phone' => '0987654321',
            'address' => '123 Le Loi',
            'payment_method' => 'cod',
        ];

        // Submit checkout should redirect back with error
        $response = $this->post(route('checkout.store'), $shippingData);
        $response->assertRedirect();
        $this->assertTrue(session()->has('error'));
        $this->assertStringContainsString('Stock mismatch', session('error'));
    }
}
