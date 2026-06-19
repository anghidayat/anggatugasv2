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
        $reviewsPerMonth = Review::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn ($r) => [
                'month' => date('M Y', strtotime($r->month . '-01')),
                'count' => $r->count,
            ]);

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
