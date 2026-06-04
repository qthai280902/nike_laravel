<?php

namespace App\Http\Controllers;

use App\Models\LandingArticle;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the dynamic dynamic storefront.
     */
    public function index(): View
    {
        $heroProduct = $this->homepageProductQuery()
            ->where('featured_position', 'hero')
            ->first();

        $secondaryProducts = $this->homepageProductQuery()
            ->where('featured_position', 'secondary')
            ->when($heroProduct, function (Builder $query) use ($heroProduct): void {
                $query->where('id', '!=', $heroProduct->id);
            })
            ->latest()
            ->limit(3)
            ->get();

        $landingArticles = LandingArticle::where('is_published', true)
            ->where(function ($query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderBy('position', 'asc')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('welcome', compact('heroProduct', 'secondaryProducts', 'landingArticles'));
    }

    /**
     * Display a published landing article.
     */
    public function showArticle(string $slug): View
    {
        $article = LandingArticle::where('slug', $slug)
            ->where('is_published', true)
            ->where(function ($query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->firstOrFail();

        return view('articles.show', compact('article'));
    }

    /**
     * Build the product query used for homepage merchandising.
     */
    private function homepageProductQuery(): Builder
    {
        return Product::query()
            ->with('category')
            ->where('status', 'active')
            ->whereNotNull('image_url')
            ->where('image_url', '!=', '')
            ->where('image_url', 'not like', 'https://images.unsplash.com/%')
            ->where('image_url', 'not like', '%placeholder%')
            ->where(function (Builder $query): void {
                $query->where('image_url', 'like', 'https://static.nike.com/%')
                    ->orWhere('image_url', 'like', '/images/%')
                    ->orWhere('image_url', 'like', 'images/%');
            });
    }
}
