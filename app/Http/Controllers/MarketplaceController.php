<?php

namespace App\Http\Controllers;

use App\Enums\MarketplaceListingCondition;
use App\Enums\MarketplaceListingStatus;
use App\Models\MarketplaceListing;
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
    public function index(Request $request): View
    {
        $listings = $this->marketplaceService->getActiveListings();
        $myListings = $request->user()
            ? $request->user()->marketplaceListings()
                ->withTrashed()
                ->with(['variant.product.category'])
                ->latest()
                ->limit(5)
                ->get()
            : collect();

        return view('marketplace.index', compact('listings', 'myListings'));
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
            'product_variant_id' => ['nullable', 'exists:product_variants,id'],
            'product_name' => ['required_without:product_variant_id', 'nullable', 'string', 'max:160'],
            'brand' => ['nullable', 'string', 'max:80'],
            'size' => ['required_without:product_variant_id', 'nullable', 'string', 'max:40'],
            'color' => ['required_without:product_variant_id', 'nullable', 'string', 'max:80'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'asking_price' => ['required', 'numeric', 'min:0'],
            'condition' => ['required', Rule::enum(MarketplaceListingCondition::class)],
            'seller_description' => ['required', 'string', 'max:1500'],
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image_path'] = $request->file('image_file')->store('marketplace-listings', 'public');
        }

        unset($validated['image_file']);

        $this->marketplaceService->createListing($validated, $request->user()->id);

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

    /**
     * Display the specified marketplace listing detail.
     */
    public function show(Request $request, MarketplaceListing $listing): View
    {
        $isOwner = $request->user()?->id === $listing->user_id;

        if ($listing->status !== MarketplaceListingStatus::Active && ! $isOwner) {
            abort(404);
        }

        $listing->load(['user', 'variant.product.category']);

        return view('marketplace.show', compact('listing'));
    }
}
