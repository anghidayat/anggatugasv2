<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    /**
     * Display a listing of the vendor's own vendors (lapak).
     */
    public function index()
    {
        $vendors = auth()->user()->vendors()
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->get();

        return view('vendor.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('vendor.vendors.create', compact('categories'));
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'address'     => 'nullable|string',
            'open_time'   => 'nullable',
            'close_time'  => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('vendors', 'public');
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';
        $validated['is_open'] = false;

        auth()->user()->vendors()->create($validated);

        return redirect()->route('vendor.vendors.index')
            ->with('success', 'Lapak berhasil ditambahkan! Menunggu persetujuan admin.');
    }

    /**
     * Display the specified vendor.
     */
    public function show($id)
    {
        $vendor = auth()->user()->vendors()
            ->with(['category', 'menus', 'reviews.user'])
            ->withAvg('reviews', 'rating')
            ->withCount(['reviews', 'menus'])
            ->findOrFail($id);

        return view('vendor.vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit($id)
    {
        $vendor = auth()->user()->vendors()->findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('vendor.vendors.edit', compact('vendor', 'categories'));
    }

    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, $id)
    {
        $vendor = auth()->user()->vendors()->findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'address'     => 'nullable|string',
            'open_time'   => 'nullable',
            'close_time'  => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($vendor->image) {
                Storage::disk('public')->delete($vendor->image);
            }
            $validated['image'] = $request->file('image')->store('vendors', 'public');
        }

        $vendor->update($validated);

        return redirect()->route('vendor.vendors.index')
            ->with('success', 'Lapak berhasil diperbarui!');
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy($id)
    {
        $vendor = auth()->user()->vendors()->findOrFail($id);

        // Delete image if exists
        if ($vendor->image) {
            Storage::disk('public')->delete($vendor->image);
        }

        $vendor->delete();

        return redirect()->route('vendor.vendors.index')
            ->with('success', 'Lapak berhasil dihapus!');
    }

    /**
     * Toggle the open/closed status of a vendor.
     */
    public function toggleOpen($id)
    {
        $vendor = auth()->user()->vendors()->findOrFail($id);
        $vendor->update(['is_open' => !$vendor->is_open]);

        $status = $vendor->is_open ? 'dibuka' : 'ditutup';

        return back()->with('success', "Lapak berhasil {$status}!");
    }
}
