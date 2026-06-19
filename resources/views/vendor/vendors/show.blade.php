@extends('layouts.dashboard')

@section('dashboard-content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('vendor.vendors.index') }}" class="p-2 rounded-lg bg-[#1a1a2e] border border-gray-700/50 text-gray-400 hover:text-white hover:border-orange-500/30 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-white">{{ $vendor->name }}</h1>
            <div class="flex items-center gap-3 mt-1">
                @if($vendor->category)
                    <span class="px-2 py-0.5 text-xs font-medium rounded-md bg-orange-500/10 text-orange-400 border border-orange-500/20">
                        {{ $vendor->category->name }}
                    </span>
                @endif
                @if($vendor->status === 'approved')
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-500/20 text-green-400 border border-green-500/30">Disetujui</span>
                @elseif($vendor->status === 'rejected')
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-500/20 text-red-400 border border-red-500/30">Ditolak</span>
                @else
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">Menunggu</span>
                @endif
                <span class="flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-full {{ $vendor->is_open ? 'bg-green-500/10 text-green-400' : 'bg-gray-500/10 text-gray-400' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $vendor->is_open ? 'bg-green-400' : 'bg-gray-500' }}"></span>
                    {{ $vendor->is_open ? 'Buka' : 'Tutup' }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('vendor.vendors.edit', $vendor->id) }}"
               class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-orange-500/10 text-orange-400 border border-orange-500/30 hover:bg-orange-500/20 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Lapak
            </a>
            @if(Route::has('vendor.menus.index'))
            <a href="{{ route('vendor.menus.index', ['vendor' => $vendor->id]) }}"
               class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/30 hover:bg-blue-500/20 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Kelola Menu
            </a>
            @endif
        </div>
    </div>

    {{-- Image Banner --}}
    <div class="relative rounded-xl overflow-hidden">
        @if($vendor->image)
            <img src="{{ asset('storage/' . $vendor->image) }}" alt="{{ $vendor->name }}"
                 class="w-full h-64 md:h-80 object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f23] via-transparent to-transparent"></div>
        @else
            <div class="w-full h-64 md:h-80 bg-[#1a1a2e] flex items-center justify-center border border-gray-700/50 rounded-xl">
                <svg class="w-20 h-20 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"/>
                </svg>
            </div>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Total Menu --}}
        <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-5 flex items-center gap-4">
            <div class="p-3 rounded-lg bg-blue-500/10 border border-blue-500/20">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">{{ $vendor->menus_count }}</p>
                <p class="text-sm text-gray-400">Total Menu</p>
            </div>
        </div>

        {{-- Rating --}}
        <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-5 flex items-center gap-4">
            <div class="p-3 rounded-lg bg-yellow-500/10 border border-yellow-500/20">
                <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
            <div>
                @php $avgRating = round($vendor->reviews_avg_rating ?? 0, 1); @endphp
                <p class="text-2xl font-bold text-white">{{ $avgRating > 0 ? $avgRating : '-' }}</p>
                <p class="text-sm text-gray-400">Rating Rata-rata</p>
            </div>
        </div>

        {{-- Total Reviews --}}
        <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-5 flex items-center gap-4">
            <div class="p-3 rounded-lg bg-green-500/10 border border-green-500/20">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">{{ $vendor->reviews_count }}</p>
                <p class="text-sm text-gray-400">Total Ulasan</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left: Info --}}
        <div class="space-y-6">
            {{-- Description --}}
            <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tentang Lapak
                </h2>
                <p class="text-gray-300 leading-relaxed">{{ $vendor->description ?: 'Belum ada deskripsi.' }}</p>

                <div class="mt-5 space-y-3 border-t border-gray-700/50 pt-5">
                    {{-- Address --}}
                    @if($vendor->address)
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-400">Alamat</p>
                                <p class="text-gray-300">{{ $vendor->address }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Operating Hours --}}
                    @if($vendor->open_time && $vendor->close_time)
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-400">Jam Operasional</p>
                                <p class="text-gray-300">{{ \Carbon\Carbon::parse($vendor->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($vendor->close_time)->format('H:i') }} WIB</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Reviews --}}
            <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Ulasan Terbaru
                </h2>

                @if($vendor->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($vendor->reviews->take(5) as $review)
                            <div class="bg-[#0f0f23] rounded-lg p-4 border border-gray-700/30">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-orange-500/20 flex items-center justify-center">
                                            <span class="text-sm font-semibold text-orange-400">{{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-white">{{ $review->user->name ?? 'Anonim' }}</p>
                                            <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-sm text-gray-300 mt-2">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-gray-500 text-sm">Belum ada ulasan untuk lapak ini.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Map --}}
        <div class="space-y-6">
            <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    Lokasi di Peta
                </h2>
                <div id="map" class="w-full h-[300px] rounded-lg border border-gray-700 z-0"></div>
                @if($vendor->latitude && $vendor->longitude)
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <div class="bg-[#0f0f23] rounded-lg px-3 py-2 border border-gray-700/50">
                            <span class="text-xs text-gray-500 block">Latitude</span>
                            <span class="text-sm text-gray-300 font-mono">{{ $vendor->latitude }}</span>
                        </div>
                        <div class="bg-[#0f0f23] rounded-lg px-3 py-2 border border-gray-700/50">
                            <span class="text-xs text-gray-500 block">Longitude</span>
                            <span class="text-sm text-gray-300 font-mono">{{ $vendor->longitude }}</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Menu List Preview --}}
            <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Menu ({{ $vendor->menus_count }})
                    </h2>
                </div>

                @if($vendor->menus->count() > 0)
                    <div class="space-y-3">
                        @foreach($vendor->menus->take(5) as $menu)
                            <div class="flex items-center gap-3 bg-[#0f0f23] rounded-lg p-3 border border-gray-700/30">
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-700/30 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white truncate">{{ $menu->name }}</p>
                                    <p class="text-sm text-orange-400 font-semibold">Rp {{ number_format($menu->price ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                        @if($vendor->menus->count() > 5)
                            <p class="text-center text-sm text-gray-500">+ {{ $vendor->menus->count() - 5 }} menu lainnya</p>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-gray-500 text-sm">Belum ada menu.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

@push('scripts')
{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const vendorLat = {{ $vendor->latitude ?? -6.2088 }};
    const vendorLng = {{ $vendor->longitude ?? 106.8456 }};

    const map = L.map('map', {
        scrollWheelZoom: false,
        dragging: true,
        zoomControl: true
    }).setView([vendorLat, vendorLng], 16);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);

    L.marker([vendorLat, vendorLng]).addTo(map)
        .bindPopup('<strong>{{ addslashes($vendor->name) }}</strong>');

    setTimeout(function() { map.invalidateSize(); }, 250);
</script>
@endpush
@endsection
