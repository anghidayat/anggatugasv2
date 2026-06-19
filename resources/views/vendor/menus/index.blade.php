@extends('layouts.dashboard')

@section('dashboard-content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">🍔 Menu Saya</h1>
            <p class="text-gray-400 text-sm mt-1">Total: {{ $menus->count() }} menu</p>
        </div>
        <a href="{{ route('vendor.menus.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#f97316] hover:bg-orange-600 text-white font-semibold rounded-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Menu
        </a>
    </div>

    {{-- Filter by Vendor --}}
    @if(isset($vendors) && $vendors->count() > 1)
    <div class="flex items-center gap-3">
        <label class="text-gray-400 text-sm">Filter Vendor:</label>
        <form method="GET" action="{{ route('vendor.menus.index') }}">
            <select name="vendor_id" onchange="this.form.submit()"
                    class="bg-[#1a1a2e] border border-gray-700 text-white rounded-lg px-3 py-2 text-sm focus:ring-[#f97316] focus:border-[#f97316]">
                <option value="">Semua Vendor</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                        {{ $vendor->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
    @endif

    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-green-900/50 border border-green-700 text-green-300 px-4 py-3 rounded-xl text-sm">
        ✅ {{ session('success') }}
    </div>
    @endif

    {{-- Menu Grid --}}
    @if($menus->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($menus as $menu)
        <div class="bg-[#1a1a2e] rounded-2xl overflow-hidden border border-gray-800 hover:border-[#f97316]/50 transition group">
            {{-- Image --}}
            <div class="relative h-48 overflow-hidden">
                @if($menu->image_filtered && $menu->filter_type !== 'none')
                    <img src="{{ asset('storage/menus/filtered/' . $menu->image_filtered) }}"
                         alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @elseif($menu->image_original)
                    <img src="{{ asset('storage/menus/' . $menu->image_original) }}"
                         alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="w-full h-full bg-[#0f0f23] flex items-center justify-center">
                        <span class="text-4xl">🍽️</span>
                    </div>
                @endif

                {{-- Availability Badge --}}
                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-bold
                    {{ $menu->is_available ? 'bg-green-500/90 text-white' : 'bg-red-500/90 text-white' }}">
                    {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                </span>

                {{-- Filter Badge --}}
                @if($menu->filter_type && $menu->filter_type !== 'none')
                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-500/90 text-white">
                    🎨 {{ ucfirst($menu->filter_type) }}
                </span>
                @endif
            </div>

            {{-- Info --}}
            <div class="p-4 space-y-3">
                <div>
                    <h3 class="text-white font-bold text-lg truncate">{{ $menu->name }}</h3>
                    @if($menu->vendor)
                    <p class="text-gray-500 text-xs mt-0.5">{{ $menu->vendor->name }}</p>
                    @endif
                </div>
                <p class="text-[#f97316] font-bold text-xl">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>

                {{-- Actions --}}
                <div class="flex items-center gap-2 pt-2 border-t border-gray-800">
                    <a href="{{ route('vendor.menus.show', $menu) }}"
                       class="flex-1 text-center py-2 text-xs font-semibold rounded-lg bg-blue-600/20 text-blue-400 hover:bg-blue-600/30 transition">
                        👁️ Lihat
                    </a>
                    <a href="{{ route('vendor.menus.edit', $menu) }}"
                       class="flex-1 text-center py-2 text-xs font-semibold rounded-lg bg-yellow-600/20 text-yellow-400 hover:bg-yellow-600/30 transition">
                        ✏️ Edit
                    </a>

                    {{-- Toggle Available --}}
                    <form action="{{ route('vendor.menus.update', $menu) }}" method="POST" class="flex-1">
                        @csrf @method('PUT')
                        <input type="hidden" name="is_available" value="{{ $menu->is_available ? '0' : '1' }}">
                        <input type="hidden" name="name" value="{{ $menu->name }}">
                        <input type="hidden" name="price" value="{{ $menu->price }}">
                        <input type="hidden" name="vendor_id" value="{{ $menu->vendor_id }}">
                        <input type="hidden" name="_toggle" value="1">
                        <button type="submit"
                                class="w-full py-2 text-xs font-semibold rounded-lg transition
                                {{ $menu->is_available ? 'bg-red-600/20 text-red-400 hover:bg-red-600/30' : 'bg-green-600/20 text-green-400 hover:bg-green-600/30' }}">
                            {{ $menu->is_available ? '⛔ Habiskan' : '✅ Aktifkan' }}
                        </button>
                    </form>

                    {{-- Delete --}}
                    <form action="{{ route('vendor.menus.destroy', $menu) }}" method="POST"
                          onsubmit="return confirm('Hapus menu {{ addslashes($menu->name) }}?')" class="flex-shrink-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-xs rounded-lg bg-red-600/20 text-red-400 hover:bg-red-600/30 transition">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    {{-- Empty State --}}
    <div class="text-center py-20 bg-[#1a1a2e] rounded-2xl border border-gray-800">
        <div class="text-6xl mb-4">🍽️</div>
        <h3 class="text-xl font-bold text-white mb-2">Belum Ada Menu</h3>
        <p class="text-gray-400 mb-6">Mulai tambahkan menu pertama Anda!</p>
        <a href="{{ route('vendor.menus.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-[#f97316] hover:bg-orange-600 text-white font-semibold rounded-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Menu Sekarang
        </a>
    </div>
    @endif
</div>
@endsection
