<?php

namespace App\Http\Controllers;

use App\Enums\MarketplaceListingCondition;
use App\Models\Product;
use App\Services\MarketplaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function __construct(
        protected MarketplaceService $marketplaceService
    ) {}

    /**
     * Display the Marketplace feed.
     */
    public function index(): View
    {
        $listings = $this->marketplaceService->getActiveListings();

        return view('marketplace.index', compact('listings'));
    }

    /**
     * Show the form to create a new listing.
     */
    public function create(): View
    {
        return view('marketplace.create');
    }

    /**
     * Store a new listing.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_variant_id' => ['required', 'exists:product_variants,id'],
            'asking_price' => ['required', 'numeric', 'min:0'],
            'condition' => ['required', Rule::enum(MarketplaceListingCondition::class)],
            'seller_description' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->marketplaceService->createListing($validated, auth()->id());

        return redirect()->route('marketplace.index')
            ->with('success', 'Tin đăng của bạn đã được gửi và đang chờ kiểm duyệt.');
    }

    /**
     * AJAX search for products.
     */
    public function search(Request $request): JsonResponse
    {
        $query = (string) $request->get('q', '');

        if (strlen(trim($query)) < 2) {
            return response()->json(['data' => []]);
        }

        $products = $this->marketplaceService->searchProducts($query);

        return response()->json([
            'data' => $products->map(fn (Product $product): array => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image_url' => $product->image_url,
                'category' => $product->category?->name,
            ]),
        ]);
    }

    /**
     * AJAX variants lookup for the selected B2C product.
     */
    public function variants(Product $product): JsonResponse
    {
        return response()->json([
            'data' => $this->marketplaceService->getProductVariants($product)->map(fn ($variant): array => [
                'id' => $variant->id,
                'size' => $variant->size,
                'color' => $variant->color,
                'stock' => $variant->stock,
            ]),
        ]);
    }
}
