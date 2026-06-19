<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * List current buyer's own reviews.
     */
    public function index()
    {
        $reviews = auth()->user()->reviews()
            ->with('vendor.category')
            ->latest()
            ->get();

        return view('buyer.reviews.index', compact('reviews'));
    }

    /**
     * Store a new review.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'rating'    => 'required|integer|min:1|max:5',
            'comment'   => 'nullable|string|max:1000',
            'image'     => 'nullable|image|max:2048',
        ]);

        // Check if already reviewed
        $existing = Review::where('vendor_id', $validated['vendor_id'])
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk lapak ini.');
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('reviews', 'public');
        }

        $validated['user_id'] = auth()->id();

        Review::create($validated);

        return back()->with('success', 'Ulasan berhasil ditambahkan! ✨');
    }

    /**
     * Update own review.
     */
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($review->image) {
                Storage::disk('public')->delete($review->image);
            }
            $validated['image'] = $request->file('image')->store('reviews', 'public');
        }

        $review->update($validated);

        return back()->with('success', 'Ulasan berhasil diperbarui!');
    }

    /**
     * Delete own review.
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        if ($review->image) {
            Storage::disk('public')->delete($review->image);
        }

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
