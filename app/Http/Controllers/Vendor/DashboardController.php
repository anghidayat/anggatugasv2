<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $vendors = $user->vendors()->with('category')->latest()->get();

        $vendorIds = $vendors->pluck('id');

        return view('vendor.dashboard', [
            'myVendors' => $vendors->count(),
            'totalMenus' => \App\Models\Menu::whereIn('vendor_id', $vendorIds)->count(),
            'avgRating' => \App\Models\Review::whereIn('vendor_id', $vendorIds)->avg('rating'),
            'vendors' => $vendors,
        ]);
    }
}
