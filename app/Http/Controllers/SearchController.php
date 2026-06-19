<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Show the search page.
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get();

        return view('search.index', compact('categories'));
    }

    /**
     * AJAX search endpoint — returns filtered vendors as JSON.
     */
    public function search(Request $request): JsonResponse
    {
        $query = Vendor::approved()
            ->with(['category', 'menus'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // ── Search query ──────────────────────────────────
        if ($q = $request->input('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhereHas('menus', function ($mq) use ($q) {
                        $mq->where('name', 'like', "%{$q}%");
                    });
            });
        }

        // ── Category filter ───────────────────────────────
        if ($category = $request->input('category')) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', $category);
            });
        }

        // ── Min rating filter ─────────────────────────────
        if ($minRating = $request->input('min_rating')) {
            $query->having('reviews_avg_rating', '>=', (float) $minRating);
        }

        // ── Open now ──────────────────────────────────────
        if ($request->boolean('open_now')) {
            $query->where('is_open', true);
        }

        // ── Distance filter ───────────────────────────────
        if ($lat = $request->input('lat')) {
            $lng = $request->input('lng');
            $radius = (float) ($request->input('radius', 10));

            // Haversine formula for rough distance filtering
            $query->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->whereRaw(
                    "(6371 * acos(cos(radians(?)) * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?)) * sin(radians(latitude)))) <= ?",
                    [$lat, $lng, $lat, $radius]
                );
        }

        // ── Price range filter ────────────────────────────
        if ($minPrice = $request->input('min_price')) {
            $query->whereHas('menus', function ($q) use ($minPrice) {
                $q->where('price', '>=', (float) $minPrice);
            });
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->whereHas('menus', function ($q) use ($maxPrice) {
                $q->where('price', '<=', (float) $maxPrice);
            });
        }

        // ── Sorting ───────────────────────────────────────
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'rating':
                $query->orderByDesc('reviews_avg_rating');
                break;
            case 'reviews':
                $query->orderByDesc('reviews_count');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        // ── Also add distance calculation if lat/lng provided for display ──
        $userLat = $request->input('lat');
        $userLng = $request->input('lng');

        $vendors = $query->get()->map(function ($vendor) use ($userLat, $userLng) {
            $data = [
                'id'            => $vendor->id,
                'name'          => $vendor->name,
                'description'   => mb_substr($vendor->description ?? '', 0, 100),
                'category'      => $vendor->category->name ?? '-',
                'category_icon' => $vendor->category->icon ?? '🍽️',
                'image'         => $vendor->image ? asset('storage/' . $vendor->image) : null,
                'latitude'      => (float) $vendor->latitude,
                'longitude'     => (float) $vendor->longitude,
                'address'       => $vendor->address,
                'open_time'     => $vendor->open_time,
                'close_time'    => $vendor->close_time,
                'is_open'       => $vendor->is_open,
                'avg_rating'    => round($vendor->reviews_avg_rating ?? 0, 1),
                'review_count'  => $vendor->reviews_count,
                'menu_count'    => $vendor->menus->count(),
                'min_price'     => $vendor->menus->min('price') ?? 0,
                'max_price'     => $vendor->menus->max('price') ?? 0,
                'url'           => route('vendors.show', $vendor),
            ];

            // Calculate distance if user location provided
            if ($userLat && $userLng && $vendor->latitude && $vendor->longitude) {
                $data['distance'] = round($this->haversineDistance(
                    (float) $userLat, (float) $userLng,
                    (float) $vendor->latitude, (float) $vendor->longitude
                ), 1);
            }

            return $data;
        });

        return response()->json($vendors);
    }

    /**
     * Calculate haversine distance in km.
     */
    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
