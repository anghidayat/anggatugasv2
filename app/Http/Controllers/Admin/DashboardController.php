<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Review;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats cards
        $stats = [
            'totalUsers'     => User::count(),
            'totalVendors'    => Vendor::where('status', 'approved')->count(),
            'pendingVendors'  => Vendor::where('status', 'pending')->count(),
            'totalMenus'      => Menu::count(),
            'totalReviews'    => Review::count(),
            'totalCategories' => Category::count(),
        ];

        // Vendors per category (for chart)
        $vendorsPerCategory = Category::withCount('vendors')
            ->orderByDesc('vendors_count')
            ->get()
            ->map(fn ($cat) => [
                'name'  => $cat->name,
                'icon'  => $cat->icon,
                'count' => $cat->vendors_count,
            ]);

        // Reviews per month (last 6 months, for chart)
        // Use PHP grouping — cross-database compatible (MySQL + SQLite)
        $reviewsPerMonth = Review::select('created_at')
            ->where('created_at', '>=', now()->subMonths(6))
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn ($r) => $r->created_at->format('Y-m'))
            ->map(fn ($group) => [
                'month' => $group->first()->created_at->format('M Y'),
                'count' => $group->count(),
            ])
            ->values();

        // Recent data
        $recentVendors = Vendor::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $latestReviews = Review::with(['user', 'vendor'])
            ->latest()
            ->take(5)
            ->get();

        // Email stats
        $emailStats = [
            'sent'   => \App\Models\EmailLog::where('status', 'sent')->count(),
            'failed' => \App\Models\EmailLog::where('status', 'failed')->count(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'vendorsPerCategory',
            'reviewsPerMonth',
            'recentVendors',
            'latestReviews',
            'emailStats'
        ));
    }
}
