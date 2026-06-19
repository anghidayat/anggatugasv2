<?php

namespace App\Http\Controllers;

use App\Models\Vendor;

class PublicVendorController extends Controller
{
    /**
     * Show a vendor detail page publicly (accessible to all).
     */
    public function show($id)
    {
        $vendor = Vendor::approved()
            ->with(['category', 'menus', 'reviews' => function ($q) {
                $q->with('user')->latest()->take(10);
            }])
            ->withAvg('reviews', 'rating')
            ->withCount(['reviews', 'menus'])
            ->findOrFail($id);

        // Check if current user has already reviewed this vendor
        $userReview = null;
        if (auth()->check() && auth()->user()->isBuyer()) {
            $userReview = $vendor->reviews->where('user_id', auth()->id())->first();
        }

        return view('public.vendor-detail', compact('vendor', 'userReview'));
    }
}
