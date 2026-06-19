<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Services\EmailService;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::with(['user', 'category']);

        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $vendors = $query->latest()->paginate(10)->appends($request->query());

        $pendingCount = Vendor::where('status', 'pending')->count();

        return view('admin.vendors.index', compact('vendors', 'pendingCount'));
    }

    public function show($id)
    {
        $vendor = Vendor::with(['user', 'category', 'menus', 'reviews.user'])->findOrFail($id);
        return view('admin.vendors.show', compact('vendor'));
    }

    public function approve($id)
    {
        $vendor = Vendor::with('user')->findOrFail($id);
        $vendor->update(['status' => 'approved']);

        // Send approval email
        try {
            app(EmailService::class)->sendVendorStatusUpdate($vendor, 'approved');
        } catch (\Exception $e) {
            // Silent fail
        }

        return redirect()->back()->with('success', 'Lapak disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $vendor = Vendor::with('user')->findOrFail($id);
        $vendor->update(['status' => 'rejected']);

        $reason = $request->input('reason');

        // Send rejection email
        try {
            app(EmailService::class)->sendVendorStatusUpdate($vendor, 'rejected', $reason);
        } catch (\Exception $e) {
            // Silent fail
        }

        return redirect()->back()->with('success', 'Lapak ditolak.');
    }
}
