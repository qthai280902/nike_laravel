<?php

namespace App\Services;

use App\Models\MarketplaceListing;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\SupportTicket;

class AdminNotificationService
{
    /**
     * Get a summary of administrative actions required.
     *
     * @return array{
     *     pending_orders_count: int,
     *     open_support_tickets_count: int,
     *     pending_listings_count: int,
     *     low_stock_count: int,
     *     total_count: int,
     *     links: array<string, string>
     * }
     */
    public function summary(): array
    {
        $pendingOrders = Order::where('status', 'pending')->count();
        $openSupport = SupportTicket::whereIn('status', ['open', 'in_progress'])->count();
        $pendingListings = MarketplaceListing::pending()->count();
        $lowStock = ProductVariant::where('stock', '<=', 5)->count();

        $totalCount = $pendingOrders + $openSupport + $pendingListings + $lowStock;

        return [
            'pending_orders_count' => $pendingOrders,
            'open_support_tickets_count' => $openSupport,
            'pending_listings_count' => $pendingListings,
            'low_stock_count' => $lowStock,
            'total_count' => $totalCount,
            'links' => [
                'pending_orders' => route('admin.orders.index', ['status' => 'pending']),
                'open_support' => route('admin.support.index'),
                'pending_listings' => route('admin.marketplace.index'),
                'low_stock' => route('admin.dashboard').'#low-stock-section',
            ],
        ];
    }
}
