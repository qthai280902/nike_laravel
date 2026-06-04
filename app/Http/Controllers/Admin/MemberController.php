<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Display a listing of the members.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.members.index', compact('users', 'search'));
    }

    /**
     * Display the specified member details.
     */
    public function show(User $user): View
    {
        // Eager load relations to prevent N+1 queries
        $user->load([
            'orders' => function ($query): void {
                $query->latest();
            },
            'orders.items.variant.product',
            'marketplaceListings' => function ($query): void {
                $query->latest();
            },
            'marketplaceListings.variant.product.category',
            'wishlistProducts' => function ($query): void {
                $query->latest();
            },
            'wishlistProducts.category',
        ]);

        // Calculate total spend (excluding cancelled orders)
        $totalSpent = $user->orders
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');

        return view('admin.members.show', compact('user', 'totalSpent'));
    }
}
