@extends('layouts.dashboard')

@section('dashboard-title', 'Edit Profil')

@section('dashboard-content')
<div class="max-w-lg mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('profile.show') }}" class="p-2 rounded-lg bg-[#1a1a2e] border border-gray-700/50 text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-white">Edit Profil</h1>
    </div>

    <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Avatar --}}
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white text-2xl font-bold overflow-hidden flex-shrink-0">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover" id="avatar-preview">
                    @else
                        <span id="avatar-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Foto Profil</label>
                    <input type="file" name="avatar" accept="image/*"
                           class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-500/10 file:text-orange-400 hover:file:bg-orange-500/20 transition-colors">
                    @error('avatar') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Name --}}
            <div>
                <label class="block text-sm text-gray-400 mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50">
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email (readonly) --}}
            <div>
                <label class="block text-sm text-gray-400 mb-1">Email</label>
                <input type="email" value="{{ $user->email }}" disabled
                       class="w-full bg-[#0f0f23]/50 border border-gray-700/30 rounded-lg px-4 py-2.5 text-gray-500 text-sm cursor-not-allowed">
                <p class="text-xs text-gray-600 mt-1">Email tidak dapat diubah.</p>
            </div>

            {{-- Phone --}}
            <div>
                <label class="block text-sm text-gray-400 mb-1">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xx xxxx xxxx"
                       class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50">
                @error('phone') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="w-full py-3 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
