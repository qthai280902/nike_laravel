<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MarketplaceListingStatus;
use App\Http\Controllers\Controller;
use App\Models\MarketplaceListing;
use App\Models\Order;
use App\Models\Product;
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
        $totalRevenue = Order::sum('total_price');
        $newOrdersCount = Order::where('status', 'pending')->count();
        $productsCount = Product::count();
        $newMembersCount = User::count();

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'newOrdersCount',
            'productsCount',
            'newMembersCount',
            'recentOrders'
        ));
    }

    /**
     * List pending marketplace listings.
     */
    public function marketplaceIndex(): View
    {
        $listings = MarketplaceListing::pending()
            ->with(['user', 'variant.product'])
            ->latest()
            ->paginate(15);

        return view('admin.marketplace.index', compact('listings'));
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
