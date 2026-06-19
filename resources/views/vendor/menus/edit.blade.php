@extends('layouts.dashboard')

@section('dashboard-content')
<div class="space-y-6" x-data="editMenuForm()">
    {{-- Header --}}
    <div>
        <a href="{{ route('vendor.menus.index') }}" class="text-gray-400 hover:text-white text-sm transition">← Kembali</a>
        <h1 class="text-2xl font-bold text-white mt-2">✏️ Edit Menu: {{ $menu->name }}</h1>
    </div>

    @if($errors->any())
    <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-xl text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('vendor.menus.update', $menu) }}" method="POST" enctype="multipart/form-data" id="menuForm"
          class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @csrf @method('PUT')

        {{-- Left: Basic Info --}}
        <div class="space-y-5">
            <div class="bg-[#1a1a2e] rounded-2xl border border-gray-800 p-6 space-y-5">
                <h2 class="text-white font-bold text-lg">📋 Informasi Menu</h2>

                {{-- Vendor --}}
                <div>
                    <label class="block text-gray-400 text-sm font-semibold mb-2">Vendor <span class="text-red-400">*</span></label>
                    <select name="vendor_id" required
                            class="w-full bg-[#0f0f23] border border-gray-700 text-white rounded-xl px-4 py-3 focus:ring-[#f97316] focus:border-[#f97316] @error('vendor_id') border-red-500 @enderror">
                        <option value="">Pilih Vendor</option>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ old('vendor_id', $menu->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                    @error('vendor_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Name --}}
                <div>
                    <label class="block text-gray-400 text-sm font-semibold mb-2">Nama Menu <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $menu->name) }}" required
                           class="w-full bg-[#0f0f23] border border-gray-700 text-white rounded-xl px-4 py-3 focus:ring-[#f97316] focus:border-[#f97316] @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-gray-400 text-sm font-semibold mb-2">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full bg-[#0f0f23] border border-gray-700 text-white rounded-xl px-4 py-3 focus:ring-[#f97316] focus:border-[#f97316] @error('description') border-red-500 @enderror">{{ old('description', $menu->description) }}</textarea>
                    @error('description')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Price --}}
                <div>
                    <label class="block text-gray-400 text-sm font-semibold mb-2">Harga <span class="text-red-400">*</span></label>
                    <div class="flex">
                        <span class="inline-flex items-center px-4 bg-[#f97316]/20 text-[#f97316] font-bold border border-r-0 border-gray-700 rounded-l-xl text-sm">Rp</span>
                        <input type="number" name="price" value="{{ old('price', $menu->price) }}" required min="0" step="500"
                               class="flex-1 bg-[#0f0f23] border border-gray-700 text-white rounded-r-xl px-4 py-3 focus:ring-[#f97316] focus:border-[#f97316] @error('price') border-red-500 @enderror">
                    </div>
                    @error('price')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Availability --}}
                <div class="flex items-center justify-between">
                    <label class="text-gray-400 text-sm font-semibold">Tersedia?</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_available" value="0">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $menu->is_available) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-700 peer-focus:ring-2 peer-focus:ring-[#f97316] rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#f97316]"></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Right: Image & Filter --}}
        <div class="space-y-5">
            {{-- Current Image --}}
            <div class="bg-[#1a1a2e] rounded-2xl border border-gray-800 p-6 space-y-4">
                <h2 class="text-white font-bold text-lg">🖼️ Gambar Saat Ini</h2>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-gray-500 text-xs mb-2 text-center">Original</p>
                        @if($menu->image_original)
                        <img src="{{ asset('storage/menus/' . $menu->image_original) }}" class="w-full aspect-square object-cover rounded-xl border border-gray-700">
                        @else
                        <div class="w-full aspect-square bg-[#0f0f23] rounded-xl flex items-center justify-center border border-gray-700"><span class="text-3xl">🍽️</span></div>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs mb-2 text-center">Filtered ({{ ucfirst($menu->filter_type ?? 'none') }})</p>
                        @if($menu->image_filtered && $menu->filter_type !== 'none')
                        <img src="{{ asset('storage/menus/filtered/' . $menu->image_filtered) }}" class="w-full aspect-square object-cover rounded-xl border border-purple-700">
                        @elseif($menu->image_original)
                        <img src="{{ asset('storage/menus/' . $menu->image_original) }}" class="w-full aspect-square object-cover rounded-xl border border-gray-700 opacity-60">
                        @else
                        <div class="w-full aspect-square bg-[#0f0f23] rounded-xl flex items-center justify-center border border-gray-700"><span class="text-3xl">🎨</span></div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Change Image --}}
            <div class="bg-[#1a1a2e] rounded-2xl border border-gray-800 p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-white font-bold text-lg">📸 Ganti Foto</h2>
                    <span class="text-gray-500 text-xs">(Opsional)</span>
                </div>

                <div class="flex border-b border-gray-700">
                    <button type="button" @click="imageTab = 'camera'"
                            :class="imageTab === 'camera' ? 'border-[#f97316] text-[#f97316]' : 'border-transparent text-gray-400 hover:text-white'"
                            class="flex-1 py-3 text-sm font-semibold border-b-2 transition text-center">
                        📸 Ambil Foto
                    </button>
                    <button type="button" @click="imageTab = 'upload'"
                            :class="imageTab === 'upload' ? 'border-[#f97316] text-[#f97316]' : 'border-transparent text-gray-400 hover:text-white'"
                            class="flex-1 py-3 text-sm font-semibold border-b-2 transition text-center">
                        📁 Upload File
                    </button>
                </div>

                {{-- Camera Tab --}}
                <div x-show="imageTab === 'camera'" x-cloak class="space-y-3">
                    <template x-if="!cameraActive && !capturedImage">
                        <button type="button" @click="startCamera()"
                                class="w-full py-8 border-2 border-dashed border-gray-600 rounded-xl text-gray-400 hover:border-[#f97316] hover:text-[#f97316] transition">
                            <div class="text-3xl mb-2">📷</div>
                            <div class="font-semibold text-sm">Buka Kamera</div>
                        </button>
                    </template>
                    <div x-show="cameraActive" x-cloak class="space-y-3">
                        <video x-ref="video" autoplay playsinline class="w-full rounded-xl bg-black aspect-video"></video>
                        <div class="flex gap-2">
                            <button type="button" @click="capturePhoto()" class="flex-1 py-3 bg-[#f97316] hover:bg-orange-600 text-white font-bold rounded-xl transition">📸 Capture</button>
                            <button type="button" @click="stopCamera()" class="px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition">✖</button>
                        </div>
                    </div>
                    <div x-show="capturedImage && !cameraActive" x-cloak class="space-y-3">
                        <img :src="capturedImage" class="w-full rounded-xl aspect-video object-cover">
                        <button type="button" @click="retakePhoto()" class="w-full py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl text-sm transition">🔄 Ulangi Foto</button>
                    </div>
                </div>

                {{-- Upload Tab --}}
                <div x-show="imageTab === 'upload'" x-cloak class="space-y-3">
                    <label class="block w-full py-8 border-2 border-dashed border-gray-600 rounded-xl text-gray-400 hover:border-[#f97316] hover:text-[#f97316] transition cursor-pointer text-center">
                        <div class="text-3xl mb-2">📁</div>
                        <div class="font-semibold text-sm" x-text="uploadedFileName || 'Pilih Gambar Baru'"></div>
                        <input type="file" accept="image/*" class="hidden" @change="handleFileUpload($event)">
                    </label>
                    <div x-show="uploadedPreview" x-cloak>
                        <img :src="uploadedPreview" class="w-full rounded-xl aspect-video object-cover">
                    </div>
                </div>

                <input type="file" name="image" x-ref="fileInput" class="hidden" accept="image/*">
                <canvas x-ref="canvas" class="hidden"></canvas>
                @error('image')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Filter Section --}}
            <div class="bg-[#1a1a2e] rounded-2xl border border-gray-800 p-6 space-y-4">
                <h2 class="text-white font-bold text-lg">🎨 Filter Gambar</h2>
                <p class="text-gray-500 text-xs">Ganti filter tanpa perlu upload ulang gambar</p>

                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-thin">
                    <template x-for="f in filters" :key="f.name">
                        <button type="button" @click="selectFilter(f.name)"
                                :class="selectedFilter === f.name ? 'ring-2 ring-[#f97316] border-[#f97316]' : 'border-gray-700'"
                                class="flex-shrink-0 w-20 border rounded-xl overflow-hidden transition hover:border-[#f97316]/50">
                            <div class="w-20 h-20 overflow-hidden">
                                <img :src="filterPreviewSrc" :style="f.css" class="w-full h-full object-cover">
                            </div>
                            <div class="py-1.5 text-center">
                                <span class="text-xs font-semibold" :class="selectedFilter === f.name ? 'text-[#f97316]' : 'text-gray-400'" x-text="f.label"></span>
                            </div>
                        </button>
                    </template>
                </div>

                <input type="hidden" name="filter_type" :value="selectedFilter">
                @error('filter_type')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror

                <div x-show="filterPreviewSrc" x-cloak>
                    <p class="text-gray-500 text-xs mb-2">Preview dengan filter:</p>
                    <img :src="filterPreviewSrc" :style="currentFilterCSS" class="w-full rounded-xl aspect-video object-cover">
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="lg:col-span-2 flex gap-3">
            <button type="submit"
                    class="flex-1 py-4 bg-[#f97316] hover:bg-orange-600 text-white font-bold text-lg rounded-2xl transition shadow-lg shadow-orange-500/20">
                💾 Perbarui Menu
            </button>
            <a href="{{ route('vendor.menus.show', $menu) }}"
               class="px-8 py-4 bg-gray-700 hover:bg-gray-600 text-white font-bold text-lg rounded-2xl transition text-center">
                Batal
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function editMenuForm() {
    const originalImg = @json($menu->image_original ? asset('storage/menus/' . $menu->image_original) : null);
    return {
        imageTab: 'upload',
        cameraActive: false,
        capturedImage: null,
        uploadedPreview: null,
        uploadedFileName: null,
        hasNewImage: false,
        previewImage: null,
        selectedFilter: @json(old('filter_type', $menu->filter_type ?? 'none')),
        stream: null,
        filters: [
            { name: 'none', label: 'Normal', css: '' },
            { name: 'grayscale', label: 'Grayscale', css: 'filter: grayscale(100%)' },
            { name: 'sepia', label: 'Sepia', css: 'filter: sepia(100%)' },
            { name: 'brightness', label: 'Bright', css: 'filter: brightness(130%)' },
            { name: 'contrast', label: 'Contrast', css: 'filter: contrast(130%)' },
            { name: 'sharpen', label: 'Sharpen', css: 'filter: contrast(120%) brightness(110%)' },
            { name: 'blur', label: 'Blur', css: 'filter: blur(2px)' },
            { name: 'vintage', label: 'Vintage', css: 'filter: sepia(40%) contrast(90%) brightness(110%)' },
        ],
        get filterPreviewSrc() {
            return this.previewImage || originalImg;
        },
        get currentFilterCSS() {
            let f = this.filters.find(x => x.name === this.selectedFilter);
            return f ? f.css : '';
        },
        async startCamera() {
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } } });
                this.$refs.video.srcObject = this.stream;
                this.cameraActive = true;
            } catch (e) {
                alert('Tidak dapat mengakses kamera: ' + e.message);
            }
        },
        stopCamera() {
            if (this.stream) { this.stream.getTracks().forEach(t => t.stop()); }
            this.cameraActive = false;
            this.stream = null;
        },
        capturePhoto() {
            const video = this.$refs.video;
            const canvas = this.$refs.canvas;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            this.capturedImage = canvas.toDataURL('image/jpeg', 0.9);
            this.previewImage = this.capturedImage;
            this.hasNewImage = true;
            this.stopCamera();
            canvas.toBlob((blob) => {
                const file = new File([blob], 'camera-capture.jpg', { type: 'image/jpeg' });
                const dt = new DataTransfer();
                dt.items.add(file);
                this.$refs.fileInput.files = dt.files;
            }, 'image/jpeg', 0.9);
        },
        retakePhoto() {
            this.capturedImage = null;
            this.previewImage = null;
            this.hasNewImage = false;
            this.startCamera();
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.uploadedFileName = file.name;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.uploadedPreview = e.target.result;
                this.previewImage = e.target.result;
                this.hasNewImage = true;
            };
            reader.readAsDataURL(file);
            const dt = new DataTransfer();
            dt.items.add(file);
            this.$refs.fileInput.files = dt.files;
        },
        selectFilter(name) {
            this.selectedFilter = name;
        }
    }
}
</script>
@endpush
@endsection
