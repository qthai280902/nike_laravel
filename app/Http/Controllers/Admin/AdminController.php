<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MarketplaceListingStatus;
use App\Http\Controllers\Controller;
use App\Models\LandingArticle;
use App\Models\MarketplaceListing;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index(): View
    {
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_price');
        $newOrdersCount = Order::where('status', 'pending')->count();
        $productsCount = Product::count();
        $newMembersCount = User::count();

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // 1. Thống kê trạng thái đơn hàng
        $orderStats = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        $orderStatuses = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];
        foreach ($orderStatuses as $status) {
            if (! isset($orderStats[$status])) {
                $orderStats[$status] = 0;
            }
        }

        // 2. Thống kê marketplace
        $marketplaceStats = MarketplaceListing::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        $marketplaceStatuses = ['pending', 'active', 'rejected', 'sold', 'hidden', 'deleted'];
        foreach ($marketplaceStatuses as $status) {
            if (! isset($marketplaceStats[$status])) {
                $marketplaceStats[$status] = 0;
            }
        }

        // 3. Tin C2C chờ duyệt
        $pendingListingsCount = MarketplaceListing::pending()->count();
        $pendingListings = MarketplaceListing::pending()
            ->with(['user', 'variant.product.category'])
            ->latest()
            ->limit(5)
            ->get();

        // 4. Sản phẩm/biến thể sắp hết hàng
        $lowStockVariants = ProductVariant::with('product')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        $openTicketsCount = SupportTicket::whereIn('status', ['open', 'in_progress'])->count();
        $publishedArticlesCount = LandingArticle::where('is_published', true)->count();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'newOrdersCount',
            'productsCount',
            'newMembersCount',
            'recentOrders',
            'orderStats',
            'marketplaceStats',
            'pendingListingsCount',
            'pendingListings',
            'lowStockVariants',
            'openTicketsCount',
            'publishedArticlesCount'
        ));
    }

    /**
     * List pending marketplace listings.
     */
    public function marketplaceIndex(): View
    {
        $listings = MarketplaceListing::pending()
            ->with(['user', 'variant.product.category'])
            ->latest()
            ->paginate(15);

        return view('admin.marketplace.index', compact('listings'));
    }

    /**
     * Preview a marketplace listing before moderation.
     */
    public function marketplaceShow(MarketplaceListing $listing): View
    {
        $listing->load(['user', 'variant.product.category']);

        return view('admin.marketplace.show', compact('listing'));
    }

    /**
     * Approve or reject a listing.
     */
    public function updateListingStatus(MarketplaceListing $listing, string $status): RedirectResponse
    {
        $nextStatus = MarketplaceListingStatus::tryFrom($status);

        if (! in_array($nextStatus, [MarketplaceListingStatus::Active, MarketplaceListingStatus::Rejected], true)) {
            return back()->with('error', 'Trạng thái không hợp lệ.');
        }

        if ($listing->status !== MarketplaceListingStatus::Pending) {
            return back()->with('error', 'Tin đăng này không còn ở hàng chờ duyệt.');
        }

        $listing->update(['status' => $nextStatus]);

        return back()->with('success', 'Đã cập nhật trạng thái tin đăng.');
    }
}
