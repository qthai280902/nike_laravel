<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CheckoutService;
use Exception;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService
    ) {}

    /**
     * Display the checkout page.
     */
    public function index()
    {
        $cartItems = $this->cartService->items();

        if ($cartItems->isEmpty()) {
            return redirect()->route('catalog.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $totalPrice = $this->cartService->subtotal();

        return view('checkout.index', compact('cartItems', 'totalPrice'));
    }

    /**
     * Process checkout submission.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod',
        ]);

        try {
            $this->checkoutService->process($data);

            return redirect()->route('profile.index')->with('success', 'Đơn hàng của bạn đã được đặt thành công!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
