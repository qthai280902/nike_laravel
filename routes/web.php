<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LandingArticleController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StorefrontController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [AboutController::class, 'index'])->name('about');
Route::view('/story', 'story')->name('story');
Route::view('/stores', 'stores')->name('stores');
Route::get('/discount-sale', [ProductController::class, 'sale'])->name('catalog.sale');
Route::get('/articles/{slug}', [HomeController::class, 'showArticle'])->name('articles.show');

// B2C Catalog Routes
Route::prefix('catalog')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/products', [ProductController::class, 'index'])->name('catalog.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('catalog.show');
    Route::get('/search/suggestions', [ProductController::class, 'searchSuggestions'])->name('search.suggestions');
});

// Storefront Support Routes
Route::get('/support', [SupportController::class, 'create'])->name('support.create');
Route::post('/support', [SupportController::class, 'store'])->name('support.store');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/fragment', [CartController::class, 'fragment'])->name('cart.fragment');

// Auth Group
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
});

// Profile & Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Wishlist
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Marketplace (C2C)
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/create', [MarketplaceController::class, 'create'])->name('marketplace.create')->middleware('auth');
    Route::post('/marketplace', [MarketplaceController::class, 'store'])->name('marketplace.store')->middleware('auth');
    Route::get('/marketplace/search-products', [MarketplaceController::class, 'search'])->name('marketplace.search');
    Route::get('/marketplace/products/{product}/variants', [MarketplaceController::class, 'variants'])->name('marketplace.products.variants');
    Route::get('/marketplace/{listing}', [MarketplaceController::class, 'show'])->name('marketplace.show');

    // Admin Dashboard & Management
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/marketplace', [AdminController::class, 'marketplaceIndex'])->name('marketplace.index');
        Route::patch('/marketplace/{listing}/{status}', [AdminController::class, 'updateListingStatus'])->name('marketplace.update');
        Route::get('/storefront', [StorefrontController::class, 'index'])->name('storefront.index');
        Route::patch('/storefront/{product}', [StorefrontController::class, 'updateFeaturedPosition'])->name('storefront.update');
        Route::get('/products', [StorefrontController::class, 'index'])->name('products.index');
        Route::get('/products/create', [StorefrontController::class, 'create'])->name('products.create');
        Route::post('/products', [StorefrontController::class, 'store'])->name('products.store');
        Route::get('/products/{product}', [StorefrontController::class, 'show'])->name('products.show');
        Route::get('/products/{product}/edit', [StorefrontController::class, 'edit'])->name('products.edit');
        Route::match(['put', 'patch'], '/products/{product}', [StorefrontController::class, 'update'])->name('products.update');

        // Admin Members
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/{user}', [MemberController::class, 'show'])->name('members.show');

        // Admin Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        // Admin Support Tickets
        Route::get('/support', [SupportTicketController::class, 'index'])->name('support.index');
        Route::get('/support/{ticket}', [SupportTicketController::class, 'show'])->name('support.show');
        Route::patch('/support/{ticket}', [SupportTicketController::class, 'update'])->name('support.update');

        // Admin Landing Articles
        Route::get('/landing-articles', [LandingArticleController::class, 'index'])->name('landing-articles.index');
        Route::get('/landing-articles/create', [LandingArticleController::class, 'create'])->name('landing-articles.create');
        Route::post('/landing-articles', [LandingArticleController::class, 'store'])->name('landing-articles.store');
        Route::get('/landing-articles/{landingArticle}/edit', [LandingArticleController::class, 'edit'])->name('landing-articles.edit');
        Route::match(['put', 'patch'], '/landing-articles/{landingArticle}', [LandingArticleController::class, 'update'])->name('landing-articles.update');
        Route::delete('/landing-articles/{landingArticle}', [LandingArticleController::class, 'destroy'])->name('landing-articles.destroy');

        // Admin Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    });
});
