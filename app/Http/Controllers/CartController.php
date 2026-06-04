<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Add a product variant to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cartService->add($request->variant_id, $request->quantity);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ!',
                'cart_count' => $this->cartService->count(),
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ!');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'variant_id' => 'required',
        ]);

        $this->cartService->remove($request->variant_id);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => $this->cartService->count(),
            ]);
        }

        return redirect()->back();
    }

    /**
     * Return the cart items HTML fragment for dynamic update.
     */
    public function fragment()
    {
        return view('components.cart-items-fragment');
    }
}
