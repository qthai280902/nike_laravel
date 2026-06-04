<?php

namespace App\Services;

use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'nike_cart';

    /**
     * Get all items in the cart.
     */
    public function getItems(): Collection
    {
        return collect(Session::get($this->sessionKey, []));
    }

    /**
     * Add an item to the cart.
     */
    public function add(string $variantId, int $qty = 1): void
    {
        $cart = $this->getItems();

        $variant = ProductVariant::with('product')->findOrFail($variantId);

        if ($cart->has($variantId)) {
            $item = $cart->get($variantId);
            $item['qty'] += $qty;
            $cart->put($variantId, $item);
        } else {
            $cart->put($variantId, [
                'id' => $variantId,
                'product_name' => $variant->product->name,
                'variant_name' => "{$variant->color} - {$variant->size}",
                'price' => $variant->price_override ?? $variant->product->price,
                'qty' => $qty,
                'image' => $variant->product->image_url,
                'size' => $variant->size,
                'color' => $variant->color,
                'slug' => $variant->product->slug,
            ]);
        }

        Session::put($this->sessionKey, $cart->toArray());
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(string $variantId): void
    {
        $cart = $this->getItems();
        $cart->forget($variantId);
        Session::put($this->sessionKey, $cart->toArray());
    }

    /**
     * Clear the cart.
     */
    public function clear(): void
    {
        Session::forget($this->sessionKey);
    }

    /**
     * Get total price.
     */
    public function getTotal(): float
    {
        return $this->getItems()->sum(fn ($item) => $item['price'] * $item['qty']);
    }

    /**
     * Get all items.
     */
    public function items(): Collection
    {
        return $this->getItems();
    }

    /**
     * Get count of unique items in cart.
     */
    public function count(): int
    {
        return $this->getItems()->count();
    }

    /**
     * Get subtotal (alias to getTotal).
     */
    public function subtotal(): float
    {
        return $this->getTotal();
    }
}
