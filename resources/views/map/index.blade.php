@extends('layouts.app')

@section('content')
<div class="relative" style="margin: -1.5rem -1rem; height: calc(100vh - 80px);">
    {{-- Search & Filter Bar --}}
    <div class="absolute top-4 left-4 right-4 z-[1000] flex flex-col md:flex-row gap-3">
        {{-- Search Box --}}
        <div class="flex-1 max-w-md">
            <div class="relative">
                <input type="text" id="map-search"
                       placeholder="Cari lapak atau menu..."
                       class="w-full pl-10 pr-4 py-3 bg-dark/90 backdrop-blur-xl border border-gray-700/50 rounded-xl text-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50">
                <svg class="absolute left-3 top-3.5 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        {{-- Filter Buttons --}}
        <div class="flex gap-2 overflow-x-auto pb-1">
            <button onclick="filterByCategory('all')"
                    class="filter-btn active px-4 py-2.5 bg-primary/90 backdrop-blur text-white text-xs font-semibold rounded-lg whitespace-nowrap transition-all">
                🍽️ Semua
            </button>
            @php
                $categories = \App\Models\Category::all();
            @endphp
            @foreach($categories as $cat)
                <button onclick="filterByCategory('{{ $cat->name }}')"
                        class="filter-btn px-4 py-2.5 bg-dark/90 backdrop-blur border border-gray-700/50 text-gray-300 text-xs font-medium rounded-lg whitespace-nowrap hover:bg-white/10 transition-all">
                    {{ $cat->icon }} {{ $cat->name }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Locate Me Button --}}
    <button onclick="locateMe()" title="Lokasi Saya"
            class="absolute bottom-6 right-4 z-[1000] w-12 h-12 bg-dark/90 backdrop-blur-xl border border-gray-700/50 rounded-xl flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </button>

    {{-- Vendor Count Badge --}}
    <div class="absolute bottom-6 left-4 z-[1000] px-4 py-2.5 bg-dark/90 backdrop-blur-xl border border-gray-700/50 rounded-xl">
        <span class="text-xs text-gray-400">Lapak ditemukan:</span>
        <span id="vendor-count" class="text-sm font-bold text-primary ml-1">0</span>
    </div>

    {{-- Map Container --}}
    <div id="map" class="w-full h-full"></div>
</div>
@endsection

@push('scripts')
<style>
    .leaflet-popup-content-wrapper {
        background: #1a1a2e !important;
        color: #e5e7eb !important;
        border: 1px solid rgba(107, 114, 128, 0.3) !important;
        border-radius: 12px !important;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5) !important;
    }
    .leaflet-popup-tip {
        background: #1a1a2e !important;
    }
    .leaflet-popup-close-button {
        color: #9ca3af !important;
    }
    .leaflet-popup-close-button:hover {
        color: #f97316 !important;
    }
    .leaflet-control-zoom a {
        background: #1a1a2e !important;
        color: #e5e7eb !important;
        border-color: rgba(107, 114, 128, 0.3) !important;
    }
    .leaflet-control-zoom a:hover {
        background: #16213e !important;
    }
    .filter-btn.active {
        background: rgba(249, 115, 22, 0.9) !important;
        color: white !important;
        border-color: transparent !important;
    }
    .vendor-marker {
        background: none;
        border: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Init map centered on Indonesia
    const map = L.map('map', {
        zoomControl: true,
        attributionControl: false
    }).setView([-6.2, 106.816], 12);

    // Dark tile layer
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        subdomains: 'abcd'
    }).addTo(map);

    // Attribution
    L.control.attribution({
        prefix: '🍜 StreetFoodies'
    }).addTo(map);

    let allVendors = [];
    let markers = L.layerGroup().addTo(map);
    let currentFilter = 'all';

    // Fetch vendors from API
    fetch('{{ route("api.vendors-map") }}')
        .then(r => r.json())
        .then(data => {
            allVendors = data;
            renderMarkers(data);
            document.getElementById('vendor-count').textContent = data.length;
        })
        .catch(err => console.error('Failed to load vendors:', err));

    function renderMarkers(vendors) {
        markers.clearLayers();

        vendors.forEach(v => {
            const ratingStars = '★'.repeat(Math.round(v.avg_rating)) + '☆'.repeat(5 - Math.round(v.avg_rating));
            const statusBadge = v.is_open
                ? '<span class="inline-block px-2 py-0.5 text-xs font-semibold bg-green-500/20 text-green-400 rounded-full">Buka</span>'
                : '<span class="inline-block px-2 py-0.5 text-xs font-semibold bg-red-500/20 text-red-400 rounded-full">Tutup</span>';

            const imageHtml = v.image
                ? `<img src="${v.image}" class="w-full h-32 object-cover rounded-lg mb-2" onerror="this.style.display='none'">`
                : '<div class="w-full h-32 bg-gray-800 rounded-lg mb-2 flex items-center justify-center text-3xl">' + v.category_icon + '</div>';

            const popup = `
                <div style="min-width: 220px; max-width: 280px;">
                    ${imageHtml}
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="text-sm font-bold text-white">${v.name}</h3>
                        ${statusBadge}
                    </div>
                    <p class="text-xs text-gray-400 mb-1">${v.category_icon} ${v.category}</p>
                    <p class="text-xs text-yellow-400 mb-1">${ratingStars} <span class="text-gray-500">(${v.review_count})</span></p>
                    ${v.address ? `<p class="text-xs text-gray-500 mb-2">📍 ${v.address.substring(0, 60)}${v.address.length > 60 ? '...' : ''}</p>` : ''}
                    ${v.open_time && v.close_time ? `<p class="text-xs text-gray-500 mb-2">🕐 ${v.open_time.substring(0,5)} - ${v.close_time.substring(0,5)}</p>` : ''}
                    <a href="/vendors/${v.id}" class="inline-block mt-1 px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold rounded-lg transition-colors">Lihat Detail →</a>
                </div>
            `;

            const icon = L.divIcon({
                className: 'vendor-marker',
                html: `<div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-dark border-2 ${v.is_open ? 'border-green-400' : 'border-red-400'} flex items-center justify-center text-lg shadow-lg" style="background:#1a1a2e;">
                        ${v.category_icon}
                    </div>
                    <div class="w-2 h-2 bg-primary rounded-full -mt-1"></div>
                </div>`,
                iconSize: [40, 48],
                iconAnchor: [20, 48],
                popupAnchor: [0, -48]
            });

            L.marker([v.latitude, v.longitude], { icon })
                .bindPopup(popup, { maxWidth: 300 })
                .addTo(markers);
        });

        document.getElementById('vendor-count').textContent = vendors.length;
    }

    // Search
    const searchInput = document.getElementById('map-search');
    searchInput.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        let filtered = allVendors.filter(v =>
            v.name.toLowerCase().includes(q) ||
            v.category.toLowerCase().includes(q) ||
            (v.address && v.address.toLowerCase().includes(q))
        );
        if (currentFilter !== 'all') {
            filtered = filtered.filter(v => v.category === currentFilter);
        }
        renderMarkers(filtered);
    });

    // Category filter
    window.filterByCategory = function(category) {
        currentFilter = category;
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        event.target.classList.add('active');

        const q = searchInput.value.toLowerCase();
        let filtered = allVendors;
        if (category !== 'all') {
            filtered = filtered.filter(v => v.category === category);
        }
        if (q) {
            filtered = filtered.filter(v =>
                v.name.toLowerCase().includes(q) ||
                (v.address && v.address.toLowerCase().includes(q))
            );
        }
        renderMarkers(filtered);
    };

    // Locate me
    window.locateMe = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => {
                    const { latitude, longitude } = pos.coords;
                    map.setView([latitude, longitude], 15);

                    const myIcon = L.divIcon({
                        className: 'vendor-marker',
                        html: '<div class="w-4 h-4 bg-blue-500 rounded-full border-2 border-white shadow-lg animate-pulse"></div>',
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    });
                    L.marker([latitude, longitude], { icon: myIcon })
                        .bindPopup('<p class="text-sm text-white font-medium">📍 Lokasi Anda</p>')
                        .addTo(map)
                        .openPopup();
                },
                err => alert('Gagal mendapatkan lokasi: ' + err.message)
            );
        } else {
            alert('Geolocation tidak didukung browser Anda');
        }
    };
});
</script>
@endpush
