<?php

namespace App\Providers;

use App\Services\AdminNotificationService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            if (auth()->check() && auth()->user()->role === 'admin') {
                $view->with('adminNotifications', app(AdminNotificationService::class)->summary());
            } else {
                $view->with('adminNotifications', [
                    'pending_orders_count' => 0,
                    'open_support_tickets_count' => 0,
                    'pending_product_reviews_count' => 0,
                    'pending_listings_count' => 0,
                    'low_stock_count' => 0,
                    'total_count' => 0,
                    'links' => [],
                ]);
            }
        });
    }
}
