<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    /**
     * Store an authenticated product review for moderation.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:120'],
            'comment' => ['required', 'string', 'max:1500'],
        ]);

        $user = $request->user();

        if ($user->productReviews()->where('product_id', $product->id)->exists()) {
            return back()
                ->withErrors(['review' => 'Bạn đã gửi đánh giá cho sản phẩm này.'])
                ->withInput();
        }

        ProductReview::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi và đang chờ duyệt.');
    }
}
