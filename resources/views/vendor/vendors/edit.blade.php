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
        <div>
            <h1 class="text-2xl font-bold text-white">Edit Lapak</h1>
            <p class="text-gray-400 text-sm mt-1">Perbarui data lapak <span class="text-orange-400">{{ $vendor->name }}</span></p>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
            <div class="flex items-center gap-2 text-red-400 font-semibold mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Terdapat kesalahan:
            </div>
            <ul class="list-disc list-inside text-sm text-red-300 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('vendor.vendors.update', $vendor->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Left Column: Basic Info --}}
            <div class="space-y-6">
                {{-- Informasi Dasar --}}
                <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6 space-y-5">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Informasi Dasar
                    </h2>

                    {{-- Nama Lapak --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">Nama Lapak <span class="text-red-400">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $vendor->name) }}" required maxlength="150"
                               class="w-full bg-[#0f0f23] border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors"
                               placeholder="Contoh: Bakso Pak Joko">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-1.5">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full bg-[#0f0f23] border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors resize-none"
                                  placeholder="Ceritakan tentang lapak Anda...">{{ old('description', $vendor->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-300 mb-1.5">Kategori <span class="text-red-400">*</span></label>
                        <select name="category_id" id="category_id" required
                                class="w-full bg-[#0f0f23] border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                            <option value="" class="text-gray-500">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $vendor->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Foto Lapak --}}
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-300 mb-1.5">Foto Lapak</label>
                        <div class="relative">
                            {{-- Current Image --}}
                            <div id="image-preview-container" class="{{ $vendor->image ? '' : 'hidden' }} mb-3 relative">
                                <img id="image-preview"
                                     src="{{ $vendor->image ? asset('storage/' . $vendor->image) : '' }}"
                                     alt="Preview"
                                     class="w-full h-48 object-cover rounded-lg border border-gray-700">
                                <button type="button" onclick="clearImage()"
                                        class="absolute top-2 right-2 p-1 rounded-full bg-red-500/80 text-white hover:bg-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <label for="image" class="{{ $vendor->image ? 'hidden' : '' }} flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-700 rounded-lg cursor-pointer hover:border-orange-500/50 transition-colors" id="image-drop-zone">
                                <svg class="w-8 h-8 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm text-gray-500">Klik untuk ganti foto</span>
                                <span class="text-xs text-gray-600 mt-1">Maks. 2MB (JPG, PNG)</span>
                            </label>
                            <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Jam Operasional --}}
                <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6 space-y-5">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Jam Operasional
                    </h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="open_time" class="block text-sm font-medium text-gray-300 mb-1.5">Jam Buka</label>
                            <input type="time" name="open_time" id="open_time" value="{{ old('open_time', $vendor->open_time ? \Carbon\Carbon::parse($vendor->open_time)->format('H:i') : '') }}"
                                   class="w-full bg-[#0f0f23] border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                            @error('open_time')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="close_time" class="block text-sm font-medium text-gray-300 mb-1.5">Jam Tutup</label>
                            <input type="time" name="close_time" id="close_time" value="{{ old('close_time', $vendor->close_time ? \Carbon\Carbon::parse($vendor->close_time)->format('H:i') : '') }}"
                                   class="w-full bg-[#0f0f23] border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                            @error('close_time')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Location --}}
            <div class="space-y-6">
                <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6 space-y-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Lokasi
                        </h2>
                        <button type="button" onclick="detectLocation()"
                                class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/30 hover:bg-blue-500/20 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3A8.994 8.994 0 0013 3.06V1h-2v2.06A8.994 8.994 0 003.06 11H1v2h2.06A8.994 8.994 0 0011 20.94V23h2v-2.06A8.994 8.994 0 0020.94 13H23v-2h-2.06z"/>
                            </svg>
                            Deteksi GPS
                        </button>
                    </div>

                    {{-- Map --}}
                    <div>
                        <p class="text-sm text-gray-400 mb-2">Klik pada peta untuk mengubah lokasi lapak</p>
                        <div id="map" class="w-full h-[400px] rounded-lg border border-gray-700 z-0"></div>
                    </div>

                    {{-- Hidden Lat/Lng --}}
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $vendor->latitude) }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $vendor->longitude) }}">

                    {{-- Coordinate Display --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-[#0f0f23] rounded-lg px-3 py-2 border border-gray-700/50">
                            <span class="text-xs text-gray-500 block">Latitude</span>
                            <span id="lat-display" class="text-sm text-gray-300 font-mono">{{ old('latitude', $vendor->latitude) }}</span>
                        </div>
                        <div class="bg-[#0f0f23] rounded-lg px-3 py-2 border border-gray-700/50">
                            <span class="text-xs text-gray-500 block">Longitude</span>
                            <span id="lng-display" class="text-sm text-gray-300 font-mono">{{ old('longitude', $vendor->longitude) }}</span>
                        </div>
                    </div>

                    @error('latitude')
                        <p class="text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    @error('longitude')
                        <p class="text-sm text-red-400">{{ $message }}</p>
                    @enderror

                    {{-- Alamat --}}
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-300 mb-1.5">Alamat Lengkap</label>
                        <textarea name="address" id="address" rows="3"
                                  class="w-full bg-[#0f0f23] border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors resize-none"
                                  placeholder="Jl. Contoh No. 123, Kecamatan, Kota">{{ old('address', $vendor->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('vendor.vendors.index') }}"
               class="px-6 py-2.5 text-sm font-medium rounded-lg border border-gray-700 text-gray-300 hover:text-white hover:border-gray-600 transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="px-8 py-2.5 text-sm font-semibold rounded-lg bg-orange-500 hover:bg-orange-600 text-white transition-colors shadow-lg shadow-orange-500/25">
                Perbarui Lapak
            </button>
        </div>
    </form>
</div>

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

@push('scripts')
{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Image preview
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview-container').classList.remove('hidden');
                document.getElementById('image-drop-zone').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearImage() {
        document.getElementById('image').value = '';
        document.getElementById('image-preview-container').classList.add('hidden');
        document.getElementById('image-drop-zone').classList.remove('hidden');
    }

    // Leaflet Map
    const vendorLat = parseFloat(document.getElementById('latitude').value) || -6.2088;
    const vendorLng = parseFloat(document.getElementById('longitude').value) || 106.8456;

    const map = L.map('map').setView([vendorLat, vendorLng], 16);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);

    let marker = L.marker([vendorLat, vendorLng], { draggable: true }).addTo(map);

    function updateCoordinates(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
        document.getElementById('lat-display').textContent = lat.toFixed(6);
        document.getElementById('lng-display').textContent = lng.toFixed(6);
    }

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoordinates(e.latlng.lat, e.latlng.lng);
    });

    marker.on('dragend', function(e) {
        const pos = marker.getLatLng();
        updateCoordinates(pos.lat, pos.lng);
    });

    function detectLocation() {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung geolokasi');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 17);
                marker.setLatLng([lat, lng]);
                updateCoordinates(lat, lng);
            },
            function(error) {
                alert('Gagal mendeteksi lokasi: ' + error.message);
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    // Fix map rendering in tabs/hidden containers
    setTimeout(function() { map.invalidateSize(); }, 250);
</script>
@endpush
@endsection
