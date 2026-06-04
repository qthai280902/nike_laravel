<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Process the checkout with inventory locking.
     */
    public function process(array $shippingData): Order
    {
        $cartItems = $this->cartService->getItems();

        if ($cartItems->isEmpty()) {
            throw new Exception('Cart is empty');
        }

        return DB::transaction(function () use ($cartItems, $shippingData) {
            // 1. Create the Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $this->cartService->getTotal(),
                'status' => 'pending',
                'shipping_name' => $shippingData['name'],
                'shipping_email' => $shippingData['email'],
                'shipping_phone' => $shippingData['phone'],
                'shipping_address' => $shippingData['address'],
                'payment_method' => $shippingData['payment_method'] ?? 'cod',
            ]);

            // 2. Process Items and Lock Stock
            foreach ($cartItems as $item) {
                // Pessimistic Locking: Lock the row until transaction finishes
                $variant = ProductVariant::where('id', $item['id'])
                    ->lockForUpdate()
                    ->first();

                if (! $variant || $variant->stock < $item['qty']) {
                    throw new Exception("Stock mismatch for product: {$item['product_name']}");
                }

                // Deduct Stock
                $variant->decrement('stock', $item['qty']);

                // Create Order Item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }

            // 3. Clear Cart
            $this->cartService->clear();

            return $order;
        });
    }
}
