<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Vendor;
use App\Services\ImageFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    protected ImageFilterService $imageFilterService;

    public function __construct(ImageFilterService $imageFilterService)
    {
        $this->imageFilterService = $imageFilterService;
    }

    /**
     * List all menus across all of the auth user's vendors.
     */
    public function index()
    {
        $vendors = Vendor::where('user_id', Auth::id())->get();
        $vendorIds = $vendors->pluck('id');

        $menus = Menu::whereIn('vendor_id', $vendorIds)
            ->with('vendor')
            ->latest()
            ->paginate(15);

        return view('vendor.menus.index', compact('menus', 'vendors'));
    }

    /**
     * Show the form for creating a new menu item.
     */
    public function create()
    {
        $vendors = Vendor::where('user_id', Auth::id())->approved()->get();

        if ($vendors->isEmpty()) {
            return redirect()
                ->route('vendor.menus.index')
                ->with('error', 'Anda harus memiliki minimal satu vendor yang sudah disetujui untuk menambah menu.');
        }

        $filters = ImageFilterService::getAvailableFilters();

        return view('vendor.menus.create', compact('vendors', 'filters'));
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:2048',
            'filter_type' => 'nullable|string|in:none,grayscale,sepia,brightness,contrast,sharpen,blur,vintage',
        ]);

        // Verify vendor belongs to auth user
        $vendor = Vendor::where('id', $validated['vendor_id'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$vendor) {
            abort(403, 'Vendor ini bukan milik Anda.');
        }

        // Upload original image
        $imagePath = $request->file('image')->store('menus', 'public');

        $filterType = $validated['filter_type'] ?? 'none';
        $filteredPath = null;

        // Apply filter if needed
        if ($filterType !== 'none') {
            $absoluteSource = storage_path('app/public/' . $imagePath);
            $filteredPath = $this->imageFilterService->apply($absoluteSource, $filterType);
        }

        Menu::create([
            'vendor_id' => $vendor->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image_original' => $imagePath,
            'image_filtered' => $filteredPath,
            'filter_type' => $filterType,
            'is_available' => true,
        ]);

        return redirect()
            ->route('vendor.menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    /**
     * Display the specified menu item.
     */
    public function show($id)
    {
        $menu = $this->authorizeMenu($id);
        $menu->load('vendor');

        return view('vendor.menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified menu item.
     */
    public function edit($id)
    {
        $menu = $this->authorizeMenu($id);
        $vendors = Vendor::where('user_id', Auth::id())->approved()->get();
        $filters = ImageFilterService::getAvailableFilters();

        return view('vendor.menus.edit', compact('menu', 'vendors', 'filters'));
    }

    /**
     * Update the specified menu item.
     */
    public function update(Request $request, $id)
    {
        $menu = $this->authorizeMenu($id);

        $rules = [
            'vendor_id' => 'required|exists:vendors,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'filter_type' => 'nullable|string|in:none,grayscale,sepia,brightness,contrast,sharpen,blur,vintage',
        ];

        $validated = $request->validate($rules);

        // Verify vendor belongs to auth user
        $vendor = Vendor::where('id', $validated['vendor_id'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$vendor) {
            abort(403, 'Vendor ini bukan milik Anda.');
        }

        $filterType = $validated['filter_type'] ?? 'none';
        $imagePath = $menu->image_original;
        $filteredPath = $menu->image_filtered;

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old files
            $this->deleteMenuImages($menu);

            // Upload new original image
            $imagePath = $request->file('image')->store('menus', 'public');
            $filteredPath = null;

            // Apply filter to new image
            if ($filterType !== 'none') {
                $absoluteSource = storage_path('app/public/' . $imagePath);
                $filteredPath = $this->imageFilterService->apply($absoluteSource, $filterType);
            }
        } elseif ($filterType !== $menu->filter_type) {
            // Filter changed but no new image — re-apply filter to existing original
            // Delete old filtered image only
            if ($menu->image_filtered) {
                Storage::disk('public')->delete($menu->image_filtered);
            }

            $filteredPath = null;

            if ($filterType !== 'none') {
                $absoluteSource = storage_path('app/public/' . $imagePath);
                $filteredPath = $this->imageFilterService->apply($absoluteSource, $filterType);
            }
        }

        $menu->update([
            'vendor_id' => $vendor->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image_original' => $imagePath,
            'image_filtered' => $filteredPath,
            'filter_type' => $filterType,
        ]);

        return redirect()
            ->route('vendor.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * Remove the specified menu item.
     */
    public function destroy($id)
    {
        $menu = $this->authorizeMenu($id);

        // Delete image files
        $this->deleteMenuImages($menu);

        $menu->delete();

        return redirect()
            ->route('vendor.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * Toggle the availability of a menu item.
     */
    public function toggleAvailable($id)
    {
        $menu = $this->authorizeMenu($id);

        $menu->update([
            'is_available' => !$menu->is_available,
        ]);

        $status = $menu->is_available ? 'tersedia' : 'tidak tersedia';

        return back()->with('success', "Menu berhasil diubah menjadi {$status}.");
    }

    /**
     * AJAX endpoint to apply a filter to an existing menu image.
     */
    public function applyFilter(Request $request, $id)
    {
        $menu = $this->authorizeMenu($id);

        $validated = $request->validate([
            'filter_type' => 'required|string|in:none,grayscale,sepia,brightness,contrast,sharpen,blur,vintage',
        ]);

        $filterType = $validated['filter_type'];

        // Delete old filtered image
        if ($menu->image_filtered) {
            Storage::disk('public')->delete($menu->image_filtered);
        }

        $filteredPath = null;
        $filteredUrl = null;

        if ($filterType !== 'none') {
            $absoluteSource = storage_path('app/public/' . $menu->image_original);

            if (!file_exists($absoluteSource)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gambar asli tidak ditemukan.',
                ], 404);
            }

            $filteredPath = $this->imageFilterService->apply($absoluteSource, $filterType);
            $filteredUrl = Storage::disk('public')->url($filteredPath);
        }

        $menu->update([
            'image_filtered' => $filteredPath,
            'filter_type' => $filterType,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Filter berhasil diterapkan.',
            'data' => [
                'filter_type' => $filterType,
                'image_filtered' => $filteredPath,
                'image_filtered_url' => $filteredUrl,
                'image_original_url' => Storage::disk('public')->url($menu->image_original),
            ],
        ]);
    }

    /**
     * Find a menu item and verify it belongs to the authenticated user's vendor.
     *
     * @param int $id
     * @return Menu
     */
    private function authorizeMenu($id): Menu
    {
        $menu = Menu::findOrFail($id);

        $vendorIds = Vendor::where('user_id', Auth::id())->pluck('id');

        if (!$vendorIds->contains($menu->vendor_id)) {
            abort(403, 'Anda tidak memiliki akses ke menu ini.');
        }

        return $menu;
    }

    /**
     * Delete original and filtered image files for a menu.
     *
     * @param Menu $menu
     * @return void
     */
    private function deleteMenuImages(Menu $menu): void
    {
        if ($menu->image_original) {
            Storage::disk('public')->delete($menu->image_original);
        }

        if ($menu->image_filtered) {
            Storage::disk('public')->delete($menu->image_filtered);
        }
    }
}
