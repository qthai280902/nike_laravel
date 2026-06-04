<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index(): View
    {
        $user = Auth::user();

        $orders = $user->orders()
            ->with(['items.variant.product'])
            ->latest()
            ->get();

        $wishlistProducts = $user->wishlistProducts()->latest()->get();

        return view('profile.index', compact('user', 'orders', 'wishlistProducts'));
    }
}
