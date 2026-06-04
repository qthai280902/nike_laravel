<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceListing;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index(): View
    {
        $now = Carbon::now();

        // 1. Revenue stats (excluding cancelled orders)
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_price');

        $revenueToday = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_price');

        $revenueThisMonth = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_price');

        // 2. Orders stats
        $totalOrders = Order::count();
        $orderStatsRaw = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $orderStats = [
            'pending' => $orderStatsRaw['pending'] ?? 0,
            'paid' => $orderStatsRaw['paid'] ?? 0,
            'shipped' => $orderStatsRaw['shipped'] ?? 0,
            'delivered' => $orderStatsRaw['delivered'] ?? 0,
            'cancelled' => $orderStatsRaw['cancelled'] ?? 0,
        ];

        // 3. Members stats
        $totalMembers = User::count();
        $newMembersThisMonth = User::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        // 4. Products & Stock stats
        $totalProducts = Product::count();
        $lowStockProducts = ProductVariant::with('product')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        // 5. C2C Marketplace stats
        $marketplaceStatsRaw = MarketplaceListing::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $marketplaceStats = [
            'pending' => $marketplaceStatsRaw['pending'] ?? 0,
            'active' => $marketplaceStatsRaw['active'] ?? 0,
            'rejected' => $marketplaceStatsRaw['rejected'] ?? 0,
            'sold' => $marketplaceStatsRaw['sold'] ?? 0,
        ];

        // 6. Top 5 Selling Products
        $topSelling = OrderItem::selectRaw('product_variant_id, sum(quantity) as total_qty, sum(price * quantity) as total_revenue')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('product_variant_id')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->with(['variant.product'])
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue',
            'revenueToday',
            'revenueThisMonth',
            'totalOrders',
            'orderStats',
            'totalMembers',
            'newMembersThisMonth',
            'totalProducts',
            'lowStockProducts',
            'marketplaceStats',
            'topSelling'
        ));
    }
}
