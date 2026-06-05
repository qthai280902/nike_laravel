<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        $supportTickets = $user->supportTickets()
            ->with('resolver')
            ->latest()
            ->get();

        $marketplaceListings = $user->marketplaceListings()
            ->withTrashed()
            ->with(['variant.product.category'])
            ->latest()
            ->get();

        $productReviews = $user->productReviews()
            ->with(['product.category', 'moderator'])
            ->latest()
            ->get();

        return view('profile.index', compact('user', 'orders', 'wishlistProducts', 'supportTickets', 'marketplaceListings', 'productReviews'));
    }

    /**
     * Show the profile edit form.
     */
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the authenticated user's editable profile fields.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $updates = [
            'name' => $validated['name'],
        ];

        if ($request->hasFile('avatar_file')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $updates['avatar_path'] = $request->file('avatar_file')->store('avatars', 'public');
        }

        $user->update($updates);

        return redirect()->route('profile.index')->with('success', 'Hồ sơ của bạn đã được cập nhật.');
    }
}
