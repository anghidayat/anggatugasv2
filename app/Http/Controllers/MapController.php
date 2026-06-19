<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\JsonResponse;

class MapController extends Controller
{
    public function index()
    {
        return view('map.index');
    }

    /**
     * Return approved vendors as JSON for Leaflet markers
     */
    public function vendorsJson(): JsonResponse
    {
        $vendors = Vendor::with(['category', 'user'])
            ->where('status', 'approved')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->get()
            ->map(function ($vendor) {
                return [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'description' => mb_substr($vendor->description ?? '', 0, 100),
                    'category' => $vendor->category->name ?? '-',
                    'category_icon' => $vendor->category->icon ?? '🍽️',
                    'image' => $vendor->image ? asset('storage/' . $vendor->image) : null,
                    'latitude' => (float) $vendor->latitude,
                    'longitude' => (float) $vendor->longitude,
                    'address' => $vendor->address,
                    'open_time' => $vendor->open_time,
                    'close_time' => $vendor->close_time,
                    'is_open' => $vendor->is_open,
                    'owner' => $vendor->user->name ?? '-',
                    'avg_rating' => round($vendor->reviews_avg_rating ?? 0, 1),
                    'review_count' => $vendor->reviews_count,
                ];
            });

        return response()->json($vendors);
    }
}
