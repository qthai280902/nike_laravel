<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    /**
     * Display the paginated product management surface.
     */
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'variants' => fn ($query) => $query->orderBy('size')->orderBy('color')])
            ->withCount('variants')
            ->withSum('variants as total_stock', 'stock')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = (string) $request->string('search');

                $query->where(function ($productQuery) use ($search): void {
                    $productQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhereHas('variants', function ($variantQuery) use ($search): void {
                            $variantQuery->where('sku', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('category_id'), function ($query) use ($request): void {
                $query->where('category_id', $request->string('category_id'));
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $query->where('status', $request->string('status'));
            })
            ->when($request->filled('stock'), function ($query) use ($request): void {
                match ((string) $request->string('stock')) {
                    'out' => $query->whereDoesntHave('variants', fn ($variantQuery) => $variantQuery->where('stock', '>', 0)),
                    'low' => $query->whereHas('variants', fn ($variantQuery) => $variantQuery->whereBetween('stock', [1, 5])),
                    'available' => $query->whereHas('variants', fn ($variantQuery) => $variantQuery->where('stock', '>', 5)),
                    default => null,
                };
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $statusLabels = $this->statusLabels();

        return view('admin.storefront.index', compact('products', 'categories', 'statusLabels'));
    }

    /**
     * Show the product creation form.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $statusLabels = $this->statusLabels();

        return view('admin.storefront.create', compact('categories', 'statusLabels'));
    }

    /**
     * Store a new product with one initial variant.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->productRules([
            'variant_sku' => ['required', 'string', 'max:80', 'unique:product_variants,sku'],
            'variant_size' => ['nullable', 'string', 'max:40'],
            'variant_color' => ['nullable', 'string', 'max:80'],
            'variant_stock' => ['required', 'integer', 'min:0'],
            'variant_price_override' => ['nullable', 'numeric', 'min:0'],
        ]));

        $product = DB::transaction(function () use ($validated): Product {
            $this->clearExistingHero($validated['featured_position'] ?? null);

            $product = Product::create($this->productPayload($validated));
            $product->variants()->create([
                'sku' => $validated['variant_sku'],
                'size' => $validated['variant_size'] ?? null,
                'color' => $validated['variant_color'] ?? null,
                'stock' => $validated['variant_stock'],
                'price_override' => $validated['variant_price_override'] ?? null,
            ]);

            return $product;
        });

        return redirect()
            ->route('admin.products.show', $product)
            ->with('success', 'Đã tạo sản phẩm mới.');
    }

    /**
     * Show one product with variants and review summary.
     */
    public function show(Product $product): View
    {
        $product->load([
            'category',
            'variants' => fn ($query) => $query->orderBy('size')->orderBy('color'),
            'approvedReviews' => fn ($query) => $query->latest()->limit(5),
        ]);
        $product->loadCount('approvedReviews');
        $product->loadAvg('approvedReviews as approved_reviews_avg_rating', 'rating');

        return view('admin.storefront.show', compact('product'));
    }

    /**
     * Show the product edit form.
     */
    public function edit(Product $product): View
    {
        $product->load(['category', 'variants' => fn ($query) => $query->orderBy('size')->orderBy('color')]);

        $categories = Category::orderBy('name')->get();
        $statusLabels = $this->statusLabels();

        return view('admin.storefront.edit', compact('product', 'categories', 'statusLabels'));
    }

    /**
     * Update product details and variant inventory.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate($this->productRules([
            'variant_stock' => ['nullable', 'array'],
            'variant_stock.*' => ['integer', 'min:0'],
            'variant_price_override' => ['nullable', 'array'],
            'variant_price_override.*' => ['nullable', 'numeric', 'min:0'],
        ], $product));

        DB::transaction(function () use ($product, $validated): void {
            $this->clearExistingHero($validated['featured_position'] ?? null, $product);
            $product->update($this->productPayload($validated, $product));

            foreach (($validated['variant_stock'] ?? []) as $variantId => $stock) {
                $variant = $product->variants()->whereKey($variantId)->first();

                if (! $variant) {
                    continue;
                }

                $variant->update([
                    'stock' => $stock,
                    'price_override' => $validated['variant_price_override'][$variantId] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.products.show', $product)
            ->with('success', 'Đã cập nhật sản phẩm.');
    }

    /**
     * Update the featured position of a product from the storefront list.
     */
    public function updateFeaturedPosition(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'featured_position' => 'nullable|in:hero,secondary',
        ]);

        $this->clearExistingHero($validated['featured_position'] ?? null, $product);

        $product->update([
            'featured_position' => $validated['featured_position'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật trưng bày cho '.$product->name);
    }

    /**
     * @param  array<string, mixed>  $extraRules
     * @return array<string, mixed>
     */
    private function productRules(array $extraRules = [], ?Product $product = null): array
    {
        return array_merge([
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($product?->id),
            ],
            'description' => ['nullable', 'string'],
            'product_story' => ['nullable', 'string'],
            'highlights_text' => ['nullable', 'string'],
            'care_instructions' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'featured_position' => ['nullable', 'in:hero,secondary'],
            'status' => ['required', 'in:active,inactive,archived'],
        ], $extraRules);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function productPayload(array $validated, ?Product $product = null): array
    {
        $name = (string) $validated['name'];
        $slug = filled($validated['slug'] ?? null)
            ? Str::slug((string) $validated['slug'])
            : $this->uniqueSlug(Str::slug($name), $product);

        return [
            'category_id' => $validated['category_id'],
            'name' => $name,
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'product_story' => $validated['product_story'] ?? null,
            'highlights' => $this->highlightLines((string) ($validated['highlights_text'] ?? '')),
            'care_instructions' => $validated['care_instructions'] ?? null,
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? null,
            'image_url' => $validated['image_url'] ?: '/'.Product::FALLBACK_IMAGE_PATH,
            'featured_position' => $validated['featured_position'] ?? null,
            'status' => $validated['status'],
        ];
    }

    /**
     * @return array<int, string>
     */
    private function highlightLines(string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $text) ?: [])
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function uniqueSlug(string $baseSlug, ?Product $product = null): string
    {
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'san-pham';
        $slug = $baseSlug;
        $suffix = 2;

        while (Product::where('slug', $slug)
            ->when($product, fn ($query) => $query->whereKeyNot($product->id))
            ->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function clearExistingHero(?string $featuredPosition, ?Product $except = null): void
    {
        if ($featuredPosition !== 'hero') {
            return;
        }

        Product::where('featured_position', 'hero')
            ->when($except, fn ($query) => $query->whereKeyNot($except->id))
            ->update(['featured_position' => null]);
    }

    /**
     * @return array<string, string>
     */
    private function statusLabels(): array
    {
        return [
            'active' => 'Đang bán',
            'inactive' => 'Tạm ẩn',
            'archived' => 'Lưu trữ',
        ];
    }
}
