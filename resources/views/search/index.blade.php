@extends('layouts.app')

@section('title', 'Cari Lapak — StreetFoodies')

@section('content')
<div x-data="searchApp()" class="max-w-6xl mx-auto">
    {{-- Search Header --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Cari Lapak Kuliner</h1>
        <p class="text-gray-400">Temukan hidden gem di sekitarmu</p>
    </div>

    {{-- Main Search Bar --}}
    <div class="relative max-w-2xl mx-auto mb-8">
        <input type="text" x-model="query" @input.debounce.300="doSearch()"
               placeholder="Cari nama lapak, menu, atau alamat..."
               class="w-full pl-12 pr-4 py-4 bg-[#1a1a2e] border border-gray-700/50 rounded-2xl text-white text-base placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50">
        <svg class="absolute left-4 top-4.5 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        {{-- Loading spinner --}}
        <div x-show="loading" class="absolute right-4 top-4">
            <svg class="animate-spin w-5 h-5 text-orange-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Filters Sidebar --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-5 sticky top-20">
                <h3 class="text-sm font-semibold text-white mb-4">Filter</h3>

                {{-- Category --}}
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-400 mb-2">Kategori</label>
                    <select x-model="filters.category" @change="doSearch()"
                            class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-orange-500/50">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Min Rating --}}
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-400 mb-2">Rating Minimum</label>
                    <div class="flex items-center gap-1" x-data="{ stars: 0 }">
                        <template x-for="i in 5">
                            <button type="button"
                                    @click="filters.min_rating = (filters.min_rating === i ? 0 : i); doSearch()"
                                    :class="(filters.min_rating || 0) >= i ? 'text-yellow-400' : 'text-gray-600'"
                                    class="text-lg transition-colors">
                                ★
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Open Now --}}
                <div class="mb-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="filters.open_now" @change="doSearch()"
                               class="w-4 h-4 rounded border-gray-600 bg-[#0f0f23] text-orange-500 focus:ring-orange-500">
                        <span class="text-sm text-gray-300">Buka sekarang</span>
                    </label>
                </div>

                {{-- Price Range --}}
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-400 mb-2">Range Harga (Rp)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" x-model="filters.min_price" @input.debounce.500="doSearch()"
                               placeholder="Min" class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-3 py-1.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-orange-500/50">
                        <span class="text-gray-500">-</span>
                        <input type="number" x-model="filters.max_price" @input.debounce.500="doSearch()"
                               placeholder="Max" class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-3 py-1.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-orange-500/50">
                    </div>
                </div>

                {{-- Distance --}}
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-400 mb-2">
                        Radius (km)
                        <button @click="getLocation()" class="text-orange-400 hover:text-orange-300 ml-1 text-xs">
                            📍 Gunakan lokasi saya
                        </button>
                    </label>
                    <input type="range" x-model="filters.radius" @input.debounce.300="doSearch()"
                           min="1" max="50" step="1"
                           class="w-full accent-orange-500">
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>1 km</span>
                        <span x-text="filters.radius + ' km'"></span>
                        <span>50 km</span>
                    </div>
                </div>

                {{-- Sort --}}
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-400 mb-2">Urutkan</label>
                    <select x-model="filters.sort" @change="doSearch()"
                            class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-orange-500/50">
                        <option value="newest">Terbaru</option>
                        <option value="rating">Rating Tertinggi</option>
                        <option value="reviews">Terbanyak Diulas</option>
                    </select>
                </div>

                {{-- Reset --}}
                <button @click="resetFilters()"
                        class="w-full py-2 text-sm text-gray-400 hover:text-white border border-gray-700/50 rounded-lg hover:bg-white/5 transition-colors">
                    Reset Filter
                </button>
            </div>
        </div>

        {{-- Results --}}
        <div class="lg:col-span-3 space-y-4">
            {{-- Result count --}}
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-400">
                    <span x-text="results.length"></span> lapak ditemukan
                </p>
            </div>

            {{-- Empty state --}}
            <div x-show="results.length === 0 && !loading" class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-12 text-center">
                <span class="text-5xl block mb-4">🔍</span>
                <p class="text-gray-400 mb-1">Tidak ada lapak ditemukan</p>
                <p class="text-gray-500 text-sm">Coba kata kunci atau filter lain</p>
            </div>

            {{-- Result cards --}}
            <template x-for="vendor in results" :key="vendor.id">
                <a :href="vendor.url"
                   class="block bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-5 hover:border-orange-500/20 transition-all group">
                    <div class="flex items-start gap-4">
                        {{-- Image --}}
                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-[#0f0f23] border border-gray-700/30 flex items-center justify-center">
                            <img x-show="vendor.image" :src="vendor.image" :alt="vendor.name"
                                 class="w-full h-full object-cover" @@error="$el.style.display='none'">
                            <span x-show="!vendor.image" class="text-3xl" x-text="vendor.category_icon"></span>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-white font-semibold group-hover:text-orange-400 transition-colors truncate" x-text="vendor.name"></h3>
                                <span x-show="vendor.is_open"
                                      class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-500/20 text-green-400 flex-shrink-0">
                                    Buka
                                </span>
                                <span x-show="!vendor.is_open"
                                      class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-500/20 text-red-400 flex-shrink-0">
                                    Tutup
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mb-2" x-text="vendor.category_icon + ' ' + vendor.category"></p>

                            {{-- Rating --}}
                            <div class="flex items-center gap-3 text-xs mb-2" x-show="vendor.review_count > 0">
                                <span class="flex items-center gap-1 text-yellow-400">
                                    ⭐ <span x-text="vendor.avg_rating"></span>
                                </span>
                                <span class="text-gray-500" x-text="'(' + vendor.review_count + ' ulasan)'"></span>
                            </div>

                            {{-- Meta row --}}
                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-400">
                                <span x-show="vendor.menu_count > 0" class="flex items-center gap-1">
                                    🍽️ <span x-text="vendor.menu_count + ' menu'"></span>
                                </span>
                                <span x-show="vendor.min_price" class="flex items-center gap-1">
                                    💰 Rp<span x-text="Number(vendor.min_price).toLocaleString('id-ID')"></span>
                                    <span x-show="vendor.max_price > vendor.min_price">
                                        - <span x-text="Number(vendor.max_price).toLocaleString('id-ID')"></span>
                                    </span>
                                </span>
                                <span x-show="vendor.distance" class="flex items-center gap-1">
                                    📍 <span x-text="vendor.distance + ' km'"></span>
                                </span>
                                <span x-show="vendor.address" class="truncate max-w-[200px]" x-text="'📍 ' + vendor.address"></span>
                            </div>
                        </div>

                        {{-- Arrow --}}
                        <div class="text-gray-600 group-hover:text-orange-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function searchApp() {
    return {
        query: '',
        results: [],
        loading: false,
        userLat: null,
        userLng: null,
        filters: {
            category: '',
            min_rating: 0,
            open_now: false,
            min_price: '',
            max_price: '',
            radius: 10,
            sort: 'newest',
        },

        init() {
            // Attempt to get user location on load
            this.getLocation(true);
            // Do initial search
            this.doSearch();
        },

        async doSearch() {
            this.loading = true;
            const params = new URLSearchParams();
            if (this.query) params.set('q', this.query);
            if (this.filters.category) params.set('category', this.filters.category);
            if (this.filters.min_rating) params.set('min_rating', this.filters.min_rating);
            if (this.filters.open_now) params.set('open_now', '1');
            if (this.filters.min_price) params.set('min_price', this.filters.min_price);
            if (this.filters.max_price) params.set('max_price', this.filters.max_price);
            if (this.filters.sort) params.set('sort', this.filters.sort);
            if (this.userLat && this.userLng && this.filters.radius) {
                params.set('lat', this.userLat);
                params.set('lng', this.userLng);
                params.set('radius', this.filters.radius);
            }

            try {
                const resp = await fetch('/search/results?' + params.toString(), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                this.results = await resp.json();
            } catch (e) {
                console.error('Search error:', e);
            }
            this.loading = false;
        },

        getLocation(silent = false) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        this.userLat = pos.coords.latitude;
                        this.userLng = pos.coords.longitude;
                        this.doSearch();
                    },
                    err => {
                        if (!silent) alert('Gagal mendapatkan lokasi: ' + err.message);
                    }
                );
            } else if (!silent) {
                alert('Geolocation tidak didukung browser Anda');
            }
        },

        resetFilters() {
            this.query = '';
            this.filters = {
                category: '',
                min_rating: 0,
                open_now: false,
                min_price: '',
                max_price: '',
                radius: 10,
                sort: 'newest',
            };
            this.doSearch();
        }
    }
}
</script>
@endpush
