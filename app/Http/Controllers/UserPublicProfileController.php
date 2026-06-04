<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserPublicProfileController extends Controller
{
    /**
     * Display safe public profile information for a reviewer or C2C seller.
     */
    public function show(User $user): View
    {
        $user->loadCount([
            'productReviews as approved_reviews_count' => fn ($query) => $query->approved(),
            'marketplaceListings as active_marketplace_listings_count' => fn ($query) => $query->active(),
        ]);

        $recentReviews = $user->productReviews()
            ->approved()
            ->with('product.category')
            ->latest()
            ->limit(8)
            ->get();

        return view('users.show', compact('user', 'recentReviews'));
    }
}
