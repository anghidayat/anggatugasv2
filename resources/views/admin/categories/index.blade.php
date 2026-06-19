@extends('layouts.dashboard')

@section('dashboard-content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Kelola Kategori</h1>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-[#1a1a2e] rounded-xl border border-gray-700/50 overflow-hidden">
        @if($categories->count() > 0)
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-700/50 text-gray-400 text-sm">
                    <th class="text-left px-6 py-4">Icon</th>
                    <th class="text-left px-6 py-4">Nama</th>
                    <th class="text-left px-6 py-4">Jumlah Vendor</th>
                    <th class="text-right px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700/50">
                @foreach($categories as $category)
                <tr class="hover:bg-white/5 transition">
                    <td class="px-6 py-4 text-2xl">{{ $category->icon ?? '📁' }}</td>
                    <td class="px-6 py-4 text-white font-medium">{{ $category->name }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 bg-orange-500/20 text-orange-400 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $category->vendors_count }} vendor
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                               class="inline-flex items-center gap-1 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 px-3 py-1.5 rounded-lg text-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 bg-red-500/20 hover:bg-red-500/30 text-red-400 px-3 py-1.5 rounded-lg text-sm transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center py-16">
            <div class="text-5xl mb-4">📂</div>
            <h3 class="text-lg font-semibold text-white mb-2">Belum ada kategori</h3>
            <p class="text-gray-400 mb-6">Mulai dengan menambahkan kategori pertama.</p>
            <a href="{{ route('admin.categories.create') }}"
               class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Kategori
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
