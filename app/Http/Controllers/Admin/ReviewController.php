<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * List all reviews for moderation.
     */
    public function index()
    {
        $reviews = Review::with(['user', 'vendor.category'])
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Delete any review (admin moderation).
     */
    public function destroy(Review $review)
    {
        if ($review->image) {
            Storage::disk('public')->delete($review->image);
        }

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus oleh admin.');
    }
}
