<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StorefrontController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [AboutController::class, 'index'])->name('about');
Route::view('/story', 'story')->name('story');
Route::view('/stores', 'stores')->name('stores');
Route::get('/discount-sale', [ProductController::class, 'sale'])->name('catalog.sale');

// B2C Catalog Routes
Route::prefix('catalog')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/products', [ProductController::class, 'index'])->name('catalog.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('catalog.show');
    Route::get('/search/suggestions', [ProductController::class, 'searchSuggestions'])->name('search.suggestions');
});

Route::get('/checkout', [ProductController::class, 'index'])->name('checkout.index');
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

    // Wishlist
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Marketplace (C2C)
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/create', [MarketplaceController::class, 'create'])->name('marketplace.create')->middleware('auth');
    Route::post('/marketplace', [MarketplaceController::class, 'store'])->name('marketplace.store')->middleware('auth');
    Route::get('/marketplace/search-products', [MarketplaceController::class, 'search'])->name('marketplace.search');
    Route::get('/marketplace/products/{product}/variants', [MarketplaceController::class, 'variants'])->name('marketplace.products.variants');

    // Admin Dashboard & Management
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/marketplace', [AdminController::class, 'marketplaceIndex'])->name('marketplace.index');
        Route::patch('/marketplace/{listing}/{status}', [AdminController::class, 'updateListingStatus'])->name('marketplace.update');
        Route::get('/storefront', [StorefrontController::class, 'index'])->name('storefront.index');
        Route::patch('/storefront/{product}', [StorefrontController::class, 'update'])->name('storefront.update');
    });
});
