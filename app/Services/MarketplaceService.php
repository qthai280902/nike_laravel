<?php

namespace App\Services;

use App\Enums\MarketplaceListingStatus;
use App\Models\MarketplaceListing;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class MarketplaceService
{
    /**
     * Get active listings with their variants and products.
     */
    public function getActiveListings(): LengthAwarePaginator
    {
        return MarketplaceListing::active()
            ->with(['user', 'variant.product.category'])
            ->latest()
            ->paginate(12);
    }

    /**
     * Create a new marketplace listing.
     *
     * @param  array{product_variant_id: string, asking_price: numeric, condition: string, seller_description?: ?string}  $data
     */
    public function createListing(array $data, int $userId): MarketplaceListing
    {
        return MarketplaceListing::create([
            'user_id' => $userId,
            'product_variant_id' => $data['product_variant_id'],
            'asking_price' => $data['asking_price'],
            'condition' => $data['condition'],
            'seller_description' => $data['seller_description'] ?? null,
            'status' => MarketplaceListingStatus::Pending,
        ]);
    }

    /**
     * Search B2C products for the "Sell Item" flow.
     *
     * @return EloquentCollection<int, Product>
     */
    public function searchProducts(string $query): EloquentCollection
    {
        return Product::where('name', 'like', "%{$query}%")
            ->where('status', 'active')
            ->whereHas('variants', fn ($query) => $query->where('stock', '>', 0))
            ->with('category')
            ->limit(5)
            ->get();
    }

    /**
     * Get variants for a selected B2C product.
     */
    public function getProductVariants(Product $product): Collection
    {
        return $product->variants()
            ->where('stock', '>', 0)
            ->orderBy('size')
            ->get(['id', 'size', 'color', 'stock']);
    }
}
