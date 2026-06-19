<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('buyer.dashboard', [
            'myReviews'       => $user->reviews()->count(),
            'vendorsExplored' => $user->reviews()->distinct('vendor_id')->count('vendor_id'),
            'recentReviews'   => $user->reviews()
                ->with('vendor')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
