<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    /**
     * Display a listing of B2C products.
     */
    public function index(Request $request): JsonResponse|View
    {
        $products = $this->productService->getCatalogProducts($request->all(), $request->get('limit', 12));

        if ($request->expectsJson()) {
            return response()->json(ProductResource::collection($products)->response()->getData(true));
        }

        $categories = Category::all();

        return view('catalog.index', compact('products', 'categories'));
    }

    /**
     * Display only discounted products.
     */
    public function sale(Request $request): View
    {
        $filters = $request->all();
        $filters['on_sale'] = true;

        $products = $this->productService->getCatalogProducts($filters, $request->get('limit', 12));
        $categories = Category::all();

        return view('catalog.index', compact('products', 'categories'));
    }

    /**
     * Display the specified product.
     */
    public function show(Request $request, string $slug): JsonResponse|View
    {
        $product = $this->productService->findBySlug($slug);

        if (! $product) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
            }
            abort(404);
        }

        if ($request->expectsJson()) {
            return (new ProductResource($product))->response();
        }

        $viewerReview = $request->user()
            ? $request->user()->productReviews()->where('product_id', $product->id)->first()
            : null;

        return view('catalog.show', compact('product', 'viewerReview'));
    }

    /**
     * Get search suggestions as JSON.
     */
    public function searchSuggestions(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->where('status', 'active')
            ->with('category')
            ->limit(5)
            ->get();

        return response()->json($products->map(function ($product) {
            return [
                'name' => $product->name,
                'slug' => $product->slug,
                'category' => $product->category->name,
                'image' => $product->image_url,
            ];
        }));
    }
}
