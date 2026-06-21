@extends('layouts.dashboard')

@section('dashboard-content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('vendor.menus.index') }}" class="text-gray-400 hover:text-white text-sm transition">← Kembali ke Daftar Menu</a>
            <h1 class="text-2xl font-bold text-white mt-2">{{ $menu->name }}</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('vendor.menus.edit', $menu) }}"
               class="px-4 py-2 bg-yellow-600/20 text-yellow-400 hover:bg-yellow-600/30 rounded-xl text-sm font-semibold transition">
                ✏️ Edit
            </a>
            <form action="{{ route('vendor.menus.destroy', $menu) }}" method="POST"
                  onsubmit="return confirm('Hapus menu ini?')">
                @csrf @method('DELETE')
                <button class="px-4 py-2 bg-red-600/20 text-red-400 hover:bg-red-600/30 rounded-xl text-sm font-semibold transition">
                    🗑️ Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Image Comparison --}}
        <div class="bg-[#1a1a2e] rounded-2xl border border-gray-800 overflow-hidden" x-data="{ showFiltered: true }">
            <div class="flex border-b border-gray-800">
                <button @click="showFiltered = false"
                        :class="!showFiltered ? 'bg-[#f97316] text-white' : 'text-gray-400 hover:text-white'"
                        class="flex-1 py-3 text-sm font-semibold transition text-center">
                    📷 Original
                </button>
                <button @click="showFiltered = true"
                        :class="showFiltered ? 'bg-[#f97316] text-white' : 'text-gray-400 hover:text-white'"
                        class="flex-1 py-3 text-sm font-semibold transition text-center">
                    🎨 Filtered
                </button>
            </div>
            <div class="relative aspect-square">
                @if($menu->image_original)
                <img x-show="!showFiltered" src="{{ asset('storage/' . $menu->image_original) }}"
                     alt="Original" class="w-full h-full object-cover">
                @endif
                @if($menu->image_filtered && $menu->filter_type !== 'none')
                <img x-show="showFiltered" src="{{ asset('storage/' . $menu->image_filtered) }}"
                     alt="Filtered" class="w-full h-full object-cover">
                @elseif($menu->image_original)
                <img x-show="showFiltered" src="{{ asset('storage/' . $menu->image_original) }}"
                     alt="Original" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full bg-[#0f0f23] flex items-center justify-center">
                    <span class="text-6xl">🍽️</span>
                </div>
                @endif
            </div>
            @if($menu->filter_type && $menu->filter_type !== 'none')
            <div class="p-3 text-center text-sm text-gray-400 border-t border-gray-800">
                Filter diterapkan: <span class="text-purple-400 font-semibold">{{ ucfirst($menu->filter_type) }}</span>
            </div>
            @endif
        </div>

        {{-- Before/After Slider --}}
        <div class="space-y-6">
            {{-- Info Card --}}
            <div class="bg-[#1a1a2e] rounded-2xl border border-gray-800 p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white">{{ $menu->name }}</h2>
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $menu->is_available ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                        {{ $menu->is_available ? '✅ Tersedia' : '⛔ Habis' }}
                    </span>
                </div>

                <p class="text-[#f97316] font-bold text-3xl">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>

                @if($menu->description)
                <div>
                    <h4 class="text-gray-500 text-xs uppercase tracking-wider mb-1">Deskripsi</h4>
                    <p class="text-gray-300 text-sm leading-relaxed">{{ $menu->description }}</p>
                </div>
                @endif

                @if($menu->vendor)
                <div>
                    <h4 class="text-gray-500 text-xs uppercase tracking-wider mb-1">Vendor</h4>
                    <p class="text-white font-semibold">{{ $menu->vendor->name }}</p>
                </div>
                @endif

                <div>
                    <h4 class="text-gray-500 text-xs uppercase tracking-wider mb-1">Filter Gambar</h4>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-purple-500/20 text-purple-400">
                        🎨 {{ $menu->filter_type ? ucfirst($menu->filter_type) : 'None' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2 text-xs text-gray-500">
                    <div>Dibuat: {{ $menu->created_at?->format('d M Y H:i') }}</div>
                    <div>Diperbarui: {{ $menu->updated_at?->format('d M Y H:i') }}</div>
                </div>
            </div>

            {{-- Side by Side --}}
            @if($menu->image_original && $menu->image_filtered && $menu->filter_type !== 'none')
            <div class="bg-[#1a1a2e] rounded-2xl border border-gray-800 p-4">
                <h3 class="text-white font-semibold text-sm mb-3">📊 Perbandingan</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-gray-500 text-xs mb-2 text-center">Before</p>
                        <img src="{{ asset('storage/' . $menu->image_original) }}"
                             class="w-full aspect-square object-cover rounded-xl border border-gray-700">
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs mb-2 text-center">After</p>
                        <img src="{{ asset('storage/' . $menu->image_filtered) }}"
                             class="w-full aspect-square object-cover rounded-xl border border-purple-700">
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
