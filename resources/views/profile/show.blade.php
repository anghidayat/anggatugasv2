@extends('layouts.dashboard')

@section('dashboard-title', 'Profil Saya')

@section('dashboard-content')
<div class="max-w-3xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-white">Profil Saya</h1>

    {{-- Profile Card --}}
    <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
        <div class="flex flex-col sm:flex-row items-center gap-6">
            {{-- Avatar --}}
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white text-3xl font-bold flex-shrink-0 overflow-hidden">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>

            <div class="text-center sm:text-left flex-1">
                <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                <p class="text-gray-400">{{ $user->email }}</p>
                <div class="flex items-center gap-3 mt-2 justify-center sm:justify-start">
                    <span class="inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full
                        {{ $user->role === 'admin' ? 'bg-red-500/10 text-red-400' : '' }}
                        {{ $user->role === 'vendor' ? 'bg-orange-500/10 text-orange-400' : '' }}
                        {{ $user->role === 'buyer' ? 'bg-blue-500/10 text-blue-400' : '' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    @if($user->phone)
                        <span class="text-sm text-gray-500">{{ $user->phone }}</span>
                    @endif
                </div>
            </div>

            <a href="{{ route('profile.edit') }}"
               class="px-5 py-2.5 bg-orange-500/10 text-orange-400 text-sm font-semibold rounded-xl border border-orange-500/30 hover:bg-orange-500/20 transition-colors">
                Edit Profil
            </a>
        </div>
    </div>

    {{-- Stats (vendor only) --}}
    @if($user->isVendor())
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-5">
                <p class="text-2xl font-bold text-white">{{ $user->vendors_count }}</p>
                <p class="text-sm text-gray-400">Total Lapak</p>
            </div>
            <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-5">
                <p class="text-2xl font-bold text-white">{{ \App\Models\Menu::whereHas('vendor', fn($q) => $q->where('user_id', $user->id))->count() }}</p>
                <p class="text-sm text-gray-400">Total Menu</p>
            </div>
        </div>
    @endif

    {{-- Reviews (buyer only) --}}
    @if($user->isBuyer())
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-5">
                <p class="text-2xl font-bold text-white">{{ $user->reviews_count }}</p>
                <p class="text-sm text-gray-400">Total Ulasan</p>
            </div>
            <a href="{{ route('buyer.reviews.index') }}"
               class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-5 flex items-center justify-center text-sm text-orange-400 hover:border-orange-500/30 transition-colors">
                Lihat Ulasan Saya →
            </a>
        </div>
    @endif

    {{-- Account Info --}}
    <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Informasi Akun</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Email</p>
                <p class="text-sm text-white">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Terdaftar</p>
                <p class="text-sm text-white">{{ $user->created_at->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Status</p>
                <p class="text-sm {{ $user->is_active ? 'text-green-400' : 'text-red-400' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</p>
            </div>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Ganti Password</h3>
        <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4 max-w-md">
            @csrf

            <div>
                <label class="block text-sm text-gray-400 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" required
                       class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50">
                @error('current_password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Password Baru</label>
                <input type="password" name="password" required
                       class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50">
                @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required
                       class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50">
            </div>

            <button type="submit"
                    class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Ubah Password
            </button>
        </form>
    </div>
</div>
@endsection
