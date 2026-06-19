<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * List all users.
     */
    public function index(Request $request)
    {
        $query = User::withCount(['vendors', 'reviews']);

        // Filter by role
        if ($request->has('role') && in_array($request->role, ['admin', 'vendor', 'buyer'])) {
            $query->where('role', $request->role);
        }

        // Search
        if ($q = $request->input('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->latest()->paginate(15)->appends($request->query());

        $counts = [
            'total'   => User::count(),
            'admin'   => User::where('role', 'admin')->count(),
            'vendor'  => User::where('role', 'vendor')->count(),
            'buyer'   => User::where('role', 'buyer')->count(),
            'active'  => User::where('is_active', true)->count(),
            'banned'  => User::where('is_active', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'counts'));
    }

    /**
     * Toggle user active/banned status.
     */
    public function toggleActive(User $user)
    {
        // Prevent self-ban
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "User {$user->name} berhasil {$status}.");
    }

    /**
     * Delete a user (soft approach — only if no vendors/reviews).
     */
    public function destroy(User $user)
    {
        // Prevent self-delete
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akun sendiri.');
        }

        // Prevent deleting admin
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak bisa menghapus akun admin.');
        }

        // Check if user has vendors or reviews
        if ($user->vendors()->count() > 0 || $user->reviews()->count() > 0) {
            return back()->with('error', "User {$user->name} masih memiliki data (lapak/ulasan). Nonaktifkan saja.");
        }

        $user->delete();

        return back()->with('success', "User {$user->name} berhasil dihapus.");
    }
}
