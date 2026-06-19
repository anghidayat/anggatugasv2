@extends('layouts.dashboard')

@section('dashboard-content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.vendors.index') }}" class="text-gray-400 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-white">Detail Lapak</h1>
        </div>
        <div>
            @if($vendor->status === 'pending')
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-yellow-500/20 text-yellow-400">⏳ Pending</span>
            @elseif($vendor->status === 'approved')
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-500/20 text-green-400">✅ Disetujui</span>
            @else
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-red-500/20 text-red-400">❌ Ditolak</span>
            @endif
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Vendor Card --}}
            <div class="bg-[#1a1a2e] rounded-xl border border-gray-700/50 overflow-hidden">
                @if($vendor->image)
                    <img src="{{ asset('storage/' . $vendor->image) }}" alt="{{ $vendor->name }}"
                         class="w-full h-64 object-cover">
                @endif
                <div class="p-6 space-y-4">
                    <h2 class="text-xl font-bold text-white">{{ $vendor->name }}</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-400">Pemilik</p>
                            <p class="text-white">{{ $vendor->user->name ?? '-' }}</p>
                            <p class="text-gray-400 text-sm">{{ $vendor->user->email ?? '' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Kategori</p>
                            <p class="text-white">{{ $vendor->category->icon ?? '' }} {{ $vendor->category->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Jam Operasional</p>
                            <p class="text-white">{{ $vendor->operating_hours ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Terdaftar</p>
                            <p class="text-white">{{ $vendor->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if($vendor->description)
                    <div>
                        <p class="text-sm text-gray-400 mb-1">Deskripsi</p>
                        <p class="text-gray-300">{{ $vendor->description }}</p>
                    </div>
                    @endif

                    @if($vendor->address)
                    <div>
                        <p class="text-sm text-gray-400 mb-1">Alamat</p>
                        <p class="text-gray-300">{{ $vendor->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Map --}}
            @if($vendor->latitude && $vendor->longitude)
            <div class="bg-[#1a1a2e] rounded-xl border border-gray-700/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700/50">
                    <h3 class="text-lg font-semibold text-white">📍 Lokasi</h3>
                </div>
                <div id="map" class="h-64 w-full"></div>
            </div>
            @endif

            {{-- Menus --}}
            <div class="bg-[#1a1a2e] rounded-xl border border-gray-700/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700/50">
                    <h3 class="text-lg font-semibold text-white">🍽️ Menu ({{ $vendor->menus->count() }})</h3>
                </div>
                @if($vendor->menus->count() > 0)
                <div class="divide-y divide-gray-700/50">
                    @foreach($vendor->menus as $menu)
                    <div class="px-6 py-3 flex items-center justify-between hover:bg-white/5 transition">
                        <span class="text-white">{{ $menu->name }}</span>
                        <span class="text-orange-400 font-semibold">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-8 text-center text-gray-400">Belum ada menu.</div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Actions --}}
            @if($vendor->status === 'pending')
            <div class="bg-[#1a1a2e] rounded-xl border border-gray-700/50 p-6 space-y-3">
                <h3 class="text-lg font-semibold text-white mb-2">Tindakan</h3>
                <form action="{{ route('admin.vendors.approve', $vendor->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2.5 rounded-lg font-semibold transition">
                        ✅ Setujui Lapak
                    </button>
                </form>
                <form action="{{ route('admin.vendors.reject', $vendor->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm text-gray-400 mb-1">Alasan penolakan (opsional)</label>
                        <textarea name="reason" rows="2" placeholder="Berikan alasan agar vendor bisa memperbaiki..."
                                  class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-3 py-2 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-red-500/50 resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-lg font-semibold transition">
                        ❌ Tolak Lapak
                    </button>
                </form>
            </div>
            @endif

            {{-- Reviews --}}
            <div class="bg-[#1a1a2e] rounded-xl border border-gray-700/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700/50">
                    <h3 class="text-lg font-semibold text-white">⭐ Ulasan Terbaru</h3>
                </div>
                @if($vendor->reviews->count() > 0)
                <div class="divide-y divide-gray-700/50">
                    @foreach($vendor->reviews->take(10) as $review)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-white font-medium text-sm">{{ $review->user->name ?? 'Anonim' }}</span>
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }} text-xs">★</span>
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="text-gray-400 text-sm">{{ $review->comment }}</p>
                        @endif
                        <p class="text-gray-600 text-xs mt-1">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-8 text-center text-gray-400">Belum ada ulasan.</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Leaflet Map --}}
@if($vendor->latitude && $vendor->longitude)
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([{{ $vendor->latitude }}, {{ $vendor->longitude }}], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
        L.marker([{{ $vendor->latitude }}, {{ $vendor->longitude }}])
            .addTo(map)
            .bindPopup('<b>{{ $vendor->name }}</b><br>{{ $vendor->address }}')
            .openPopup();
    });
</script>
@endpush
@endif
@endsection
