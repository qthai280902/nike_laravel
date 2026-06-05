<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductReviewController extends Controller
{
    /**
     * Display the review moderation queue.
     */
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', ProductReview::STATUS_PENDING);
        $search = trim((string) $request->query('q', ''));

        $reviewsQuery = ProductReview::query()
            ->with(['product.category', 'user', 'moderator'])
            ->latest();

        if (in_array($status, $this->moderationStatuses(), true)) {
            $reviewsQuery->where('status', $status);
        }

        if ($search !== '') {
            $reviewsQuery->where(function ($query) use ($search): void {
                $query->where('author_name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('comment', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search): void {
                        $userQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('product', function ($productQuery) use ($search): void {
                        $productQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%");
                    });
            });
        }

        $reviews = $reviewsQuery->paginate(15)->withQueryString();

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'status' => $status,
            'search' => $search,
            'statuses' => $this->moderationStatuses(),
        ]);
    }

    /**
     * Show a single product review for moderation.
     */
    public function show(ProductReview $review): View
    {
        $review->load(['product.category', 'user', 'moderator']);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve a review for public storefront display.
     */
    public function approve(Request $request, ProductReview $review): RedirectResponse
    {
        return $this->moderate($request, $review, ProductReview::STATUS_APPROVED);
    }

    /**
     * Hide an approved review without deleting it.
     */
    public function hide(Request $request, ProductReview $review): RedirectResponse
    {
        return $this->moderate($request, $review, ProductReview::STATUS_HIDDEN);
    }

    /**
     * Return a review to the pending queue.
     */
    public function keepPending(Request $request, ProductReview $review): RedirectResponse
    {
        return $this->moderate($request, $review, ProductReview::STATUS_PENDING);
    }

    /**
     * Reject a review with an internal/user-facing reason.
     */
    public function reject(Request $request, ProductReview $review): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:2000'],
        ]);

        $review->update([
            'status' => ProductReview::STATUS_REJECTED,
            'rejection_reason' => $validated['rejection_reason'],
            'moderated_at' => now(),
            'moderated_by_user_id' => $request->user()->id,
        ]);

        return back()->with('success', 'Đã từ chối đánh giá sản phẩm.');
    }

    /**
     * @return list<string>
     */
    private function moderationStatuses(): array
    {
        return [
            ProductReview::STATUS_PENDING,
            ProductReview::STATUS_APPROVED,
            ProductReview::STATUS_HIDDEN,
            ProductReview::STATUS_REJECTED,
        ];
    }

    private function moderate(Request $request, ProductReview $review, string $status): RedirectResponse
    {
        $review->update([
            'status' => $status,
            'rejection_reason' => null,
            'moderated_at' => $status === ProductReview::STATUS_PENDING ? null : now(),
            'moderated_by_user_id' => $status === ProductReview::STATUS_PENDING ? null : $request->user()->id,
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái đánh giá sản phẩm.');
    }
}
