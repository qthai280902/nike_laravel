<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    /**
     * Get paginated products with filtering and sorting for the B2C catalog.
     */
    public function getCatalogProducts(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::with(['category', 'variants'])
            ->where('status', 'active');

        // Filter by Category Slug (including parent/child categories)
        if (! empty($filters['category'])) {
            $query->whereHas('category', function (Builder $q) use ($filters) {
                $q->where('slug', $filters['category'])
                    ->orWhereHas('parent', function (Builder $parentQuery) use ($filters) {
                        $parentQuery->where('slug', $filters['category']);
                    });
            });
        }

        // Filter by Sale (Discounted Products)
        if (! empty($filters['on_sale'])) {
            $query->whereNotNull('original_price')
                ->whereColumn('price', '<', 'original_price');
        }

        // Filter by Size
        if (! empty($filters['size'])) {
            $query->whereHas('variants', function (Builder $q) use ($filters) {
                $q->where('size', $filters['size'])->where('stock', '>', 0);
            });
        }

        // Filter by Color
        if (! empty($filters['color'])) {
            $query->whereHas('variants', function (Builder $q) use ($filters) {
                $q->where('color', $filters['color']);
            });
        }

        // Sorting
        $sort = $filters['sort'] ?? 'latest';
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->latest(),
        };

        return $query->paginate($perPage);
    }

    /**
     * Get products by category slug.
     */
    public function getProductsByCategory(string $categorySlug, int $perPage = 12): LengthAwarePaginator
    {
        return Product::whereHas('category', function ($query) use ($categorySlug) {
            $query->where('slug', $categorySlug)
                ->orWhereHas('parent', function ($parentQuery) use ($categorySlug) {
                    $parentQuery->where('slug', $categorySlug);
                });
        })
            ->where('status', 'active')
            ->with(['category', 'variants'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find a single product by slug with its variants.
     */
    public function findBySlug(string $slug): ?Product
    {
        return Product::where('slug', $slug)
            ->where('status', 'active')
            ->with([
                'category',
                'variants',
                'approvedReviews' => fn ($query) => $query->with('user')->latest()->limit(5),
            ])
            ->withAvg('approvedReviews as approved_reviews_avg_rating', 'rating')
            ->withCount('approvedReviews as approved_reviews_count')
            ->first();
    }

    /**
     * Check if a product variant has stock.
     */
    public function checkStock(string $variantId, int $quantity = 1): bool
    {
        return Product::whereHas('variants', function ($query) use ($variantId, $quantity) {
            $query->where('id', $variantId)->where('stock', '>=', $quantity);
        })->exists();
    }
}
