@extends('layouts.dashboard')

@section('dashboard-title', 'Kelola Users')

@section('dashboard-content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Kelola Users</h1>
    </div>

    {{-- Filter Pills --}}
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.users.index') }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ !request('role') ? 'bg-orange-500/20 text-orange-400 border border-orange-500/30' : 'text-gray-400 hover:text-white border border-gray-700/50 hover:bg-white/5' }}">
            Semua ({{ $counts['total'] }})
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ request('role') === 'admin' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'text-gray-400 hover:text-white border border-gray-700/50 hover:bg-white/5' }}">
            Admin ({{ $counts['admin'] }})
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'vendor']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ request('role') === 'vendor' ? 'bg-orange-500/20 text-orange-400 border border-orange-500/30' : 'text-gray-400 hover:text-white border border-gray-700/50 hover:bg-white/5' }}">
            Vendor ({{ $counts['vendor'] }})
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'buyer']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                  {{ request('role') === 'buyer' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 'text-gray-400 hover:text-white border border-gray-700/50 hover:bg-white/5' }}">
            Buyer ({{ $counts['buyer'] }})
        </a>
    </div>

    {{-- Search --}}
    <form class="flex items-center gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..."
               class="flex-1 max-w-sm bg-[#1a1a2e] border border-gray-700/50 rounded-lg px-4 py-2 text-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500/50">
        <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg transition-colors">
            Cari
        </button>
    </form>

    {{-- Users Table --}}
    <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700/50">
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">User</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Role</th>
                        <th class="text-center px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-center px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Lapak</th>
                        <th class="text-center px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Ulasan</th>
                        <th class="text-right px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/30">
                    @foreach($users as $user)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                                    {{ $user->role === 'admin' ? 'bg-red-500/10 text-red-400' : '' }}
                                    {{ $user->role === 'vendor' ? 'bg-orange-500/10 text-orange-400' : '' }}
                                    {{ $user->role === 'buyer' ? 'bg-blue-500/10 text-blue-400' : '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                                    {{ $user->is_active ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Banned' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-400">{{ $user->vendors_count }}</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-400">{{ $user->reviews_count }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs px-3 py-1.5 rounded-lg transition-colors
                                                {{ $user->is_active ? 'text-red-400 hover:text-red-300 hover:bg-red-500/10' : 'text-green-400 hover:text-green-300 hover:bg-green-500/10' }}">
                                                {{ $user->is_active ? 'Banned' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        @if(!$user->isAdmin())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                  onsubmit="return confirm('Hapus user {{ addslashes($user->name) }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs px-3 py-1.5 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-600">(Kamu)</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->count() === 0)
            <div class="px-6 py-12 text-center text-gray-500">
                Tidak ada user ditemukan.
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
