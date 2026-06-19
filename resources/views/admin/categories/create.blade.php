@extends('layouts.dashboard')

@section('dashboard-content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-white">Tambah Kategori</h1>
    </div>

    {{-- Form --}}
    <div class="bg-[#1a1a2e] rounded-xl border border-gray-700/50 p-6 max-w-lg">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Kategori <span class="text-red-400">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full bg-[#0f0f23] border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                       placeholder="Contoh: Makanan Berat" required maxlength="100">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="icon" class="block text-sm font-medium text-gray-300 mb-2">Icon (Emoji)</label>
                <input type="text" name="icon" id="icon" value="{{ old('icon') }}"
                       class="w-full bg-[#0f0f23] border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                       placeholder="Contoh: 🍔 atau 🥤" maxlength="50">
                <p class="text-gray-500 text-sm mt-1">Masukkan emoji untuk ikon kategori. Tekan Win+. untuk membuka emoji picker.</p>
                @error('icon')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-lg font-semibold transition">
                    Simpan Kategori
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="text-gray-400 hover:text-white px-4 py-2.5 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
