@extends('layouts.app')

@section('title', $vendor->name . ' — StreetFoodies')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Back Button --}}
    <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('map') }}"
       class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    {{-- Vendor Header --}}
    <div class="relative rounded-2xl overflow-hidden mb-6">
        @if($vendor->image)
            <img src="{{ asset('storage/' . $vendor->image) }}" alt="{{ $vendor->name }}"
                 class="w-full h-64 md:h-80 object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f23] via-[#0f0f23]/50 to-transparent"></div>
        @else
            <div class="w-full h-64 md:h-80 bg-[#1a1a2e] flex items-center justify-center border border-gray-700/50 rounded-2xl">
                <span class="text-6xl">{{ $vendor->category->icon ?? '🍜' }}</span>
            </div>
        @endif

        {{-- Overlay Info --}}
        <div class="absolute bottom-0 left-0 right-0 p-6">
            <div class="flex flex-wrap items-center gap-3 mb-2">
                @if($vendor->category)
                    <span class="px-3 py-1 text-xs font-medium rounded-lg bg-orange-500/20 text-orange-400 border border-orange-500/30">
                        {{ $vendor->category->icon }} {{ $vendor->category->name }}
                    </span>
                @endif
                <span class="flex items-center gap-1 px-3 py-1 text-xs font-medium rounded-full {{ $vendor->is_open ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                    <span class="w-2 h-2 rounded-full {{ $vendor->is_open ? 'bg-green-400 animate-pulse' : 'bg-red-400' }}"></span>
                    {{ $vendor->is_open ? 'Buka' : 'Tutup' }}
                </span>
                @php $avgRating = round($vendor->reviews_avg_rating ?? 0, 1); @endphp
                @if($avgRating > 0)
                    <span class="flex items-center gap-1 px-3 py-1 text-xs font-medium rounded-full bg-yellow-500/20 text-yellow-400">
                        ⭐ {{ $avgRating }} ({{ $vendor->reviews_count }})
                    </span>
                @endif
            </div>
            <h1 class="text-3xl font-bold text-white">{{ $vendor->name }}</h1>
            @if($vendor->address)
                <p class="text-gray-400 mt-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $vendor->address }}
                </p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Info + Menu + Map --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Description --}}
            <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-3">Tentang Lapak</h2>
                <p class="text-gray-300 leading-relaxed">{{ $vendor->description ?: 'Belum ada deskripsi.' }}</p>

                @if($vendor->open_time && $vendor->close_time)
                    <div class="mt-4 pt-4 border-t border-gray-700/50 flex items-center gap-2 text-sm text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Jam operasional: {{ \Carbon\Carbon::parse($vendor->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($vendor->close_time)->format('H:i') }} WIB
                    </div>
                @endif
            </div>

            {{-- Menu List --}}
            @if($vendor->menus->count() > 0)
                <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-white mb-4">Menu ({{ $vendor->menus_count }})</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($vendor->menus as $menu)
                            <div class="flex items-center gap-3 bg-[#0f0f23] rounded-lg p-3 border border-gray-700/30 hover:border-orange-500/20 transition-colors">
                                @if($menu->image_filtered)
                                    <img src="{{ asset('storage/' . $menu->image_filtered) }}" alt="{{ $menu->name }}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                @elseif($menu->image_original)
                                    <img src="{{ asset('storage/' . $menu->image_original) }}" alt="{{ $menu->name }}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="w-16 h-16 rounded-lg bg-gray-700/30 flex items-center justify-center flex-shrink-0">
                                        <span class="text-2xl">🍽️</span>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white truncate">{{ $menu->name }}</p>
                                    @if($menu->description)
                                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ $menu->description }}</p>
                                    @endif
                                    <p class="text-sm text-orange-400 font-semibold mt-1">Rp {{ number_format($menu->price ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Map --}}
            @if($vendor->latitude && $vendor->longitude)
                <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-white mb-3">Lokasi</h2>
                    <div id="vendor-map" class="w-full h-[300px] rounded-lg border border-gray-700 z-0"></div>
                </div>
            @endif
        </div>

        {{-- Right: Review Section --}}
        <div class="space-y-6">
            {{-- Write Review (for logged-in buyers) --}}
            @auth
                @if(auth()->user()->isBuyer())
                    @if(!$userReview)
                        <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
                            <h2 class="text-lg font-semibold text-white mb-4">Tulis Ulasan</h2>
                            <form action="{{ route('buyer.reviews.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">

                                {{-- Star Rating --}}
                                <div class="mb-4">
                                    <label class="block text-sm text-gray-400 mb-2">Rating</label>
                                    <div class="flex items-center gap-1" x-data="{ rating: 0, hover: 0 }">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button"
                                                    @click="rating = {{ $i }}"
                                                    @mouseenter="hover = {{ $i }}"
                                                    @mouseleave="hover = 0"
                                                    class="text-2xl transition-colors"
                                                    :class="(hover || rating) >= {{ $i }} ? 'text-yellow-400' : 'text-gray-600'">
                                                ★
                                            </button>
                                        @endfor
                                        <input type="hidden" name="rating" x-model="rating" required>
                                    </div>
                                    @error('rating') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Comment --}}
                                <div class="mb-4">
                                    <textarea name="comment" rows="3" placeholder="Bagikan pengalamanmu..."
                                              class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-4 py-3 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30 resize-none"></textarea>
                                    @error('comment') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Photo Upload --}}
                                <div class="mb-4">
                                    <label class="block text-sm text-gray-400 mb-2">Foto (opsional)</label>
                                    <input type="file" name="image" accept="image/*"
                                           class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-500/10 file:text-orange-400 hover:file:bg-orange-500/20 file:transition-colors">
                                    @error('image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <button type="submit"
                                        class="w-full py-3 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl transition-colors">
                                    Kirim Ulasan ✨
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- Edit existing review --}}
                        <div class="bg-[#1a1a2e] border border-orange-500/20 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-lg font-semibold text-white">Ulasan Kamu</h2>
                                <form action="{{ route('buyer.reviews.destroy', $userReview) }}" method="POST"
                                      onsubmit="return confirm('Hapus ulasan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-300">Hapus</button>
                                </form>
                            </div>
                            <form action="{{ route('buyer.reviews.update', $userReview) }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')

                                <div class="mb-4" x-data="{ rating: {{ $userReview->rating }}, hover: 0 }">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button"
                                                    @click="rating = {{ $i }}"
                                                    @mouseenter="hover = {{ $i }}"
                                                    @mouseleave="hover = 0"
                                                    class="text-2xl transition-colors"
                                                    :class="(hover || rating) >= {{ $i }} ? 'text-yellow-400' : 'text-gray-600'">
                                                ★
                                            </button>
                                        @endfor
                                        <input type="hidden" name="rating" x-model="rating" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <textarea name="comment" rows="3" placeholder="Bagikan pengalamanmu..."
                                              class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-4 py-3 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30 resize-none">{{ $userReview->comment }}</textarea>
                                </div>

                                <button type="submit"
                                        class="w-full py-2.5 bg-orange-500/10 text-orange-400 text-sm font-semibold rounded-xl border border-orange-500/30 hover:bg-orange-500/20 transition-colors">
                                    Perbarui Ulasan
                                </button>
                            </form>
                        </div>
                    @endif
                @elseif(auth()->user()->isVendor())
                    <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6 text-center">
                        <span class="text-gray-500 text-sm">Sebagai vendor, kamu tidak bisa menulis ulasan.</span>
                    </div>
                @endif
            @else
                <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6 text-center">
                    <p class="text-gray-400 text-sm mb-3">Login untuk menulis ulasan</p>
                    <a href="{{ route('login') }}"
                       class="inline-block px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl transition-colors">
                        Login
                    </a>
                </div>
            @endauth

            {{-- Reviews List --}}
            <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">
                    Ulasan ({{ $vendor->reviews_count }})
                </h2>

                @if($vendor->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($vendor->reviews as $review)
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
                                    <p class="text-sm text-gray-300 mb-3">{{ $review->comment }}</p>
                                @endif
                                @if($review->image)
                                    <img src="{{ asset('storage/' . $review->image) }}" alt="Review photo"
                                         class="w-full h-40 object-cover rounded-lg border border-gray-700/30">
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <span class="text-4xl block mb-3">💬</span>
                        <p class="text-gray-500 text-sm">Belum ada ulasan. Jadi yang pertama!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($vendor->latitude && $vendor->longitude)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('vendor-map', {
            scrollWheelZoom: false,
            dragging: true,
            zoomControl: true
        }).setView([{{ $vendor->latitude }}, {{ $vendor->longitude }}], 16);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            subdomains: 'abcd'
        }).addTo(map);

        L.marker([{{ $vendor->latitude }}, {{ $vendor->longitude }}])
            .bindPopup('<strong>{{ addslashes($vendor->name) }}</strong>')
            .addTo(map);

        setTimeout(function() { map.invalidateSize(); }, 250);
    });
</script>
@endif
@endpush
