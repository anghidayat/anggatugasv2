@extends('layouts.dashboard')

@section('dashboard-content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Kelola Lapak Vendor</h1>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter Tabs --}}
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('admin.vendors.index') }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('status') ? 'bg-orange-500 text-white' : 'bg-[#1a1a2e] text-gray-400 hover:text-white border border-gray-700/50' }}">
            Semua
        </a>
        <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-[#1a1a2e] text-gray-400 hover:text-white border border-gray-700/50' }}">
            Pending
            @if($pendingCount > 0)
                <span class="bg-yellow-500/30 text-yellow-300 px-2 py-0.5 rounded-full text-xs font-bold">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.vendors.index', ['status' => 'approved']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'approved' ? 'bg-green-500 text-white' : 'bg-[#1a1a2e] text-gray-400 hover:text-white border border-gray-700/50' }}">
            Disetujui
        </a>
        <a href="{{ route('admin.vendors.index', ['status' => 'rejected']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'rejected' ? 'bg-red-500 text-white' : 'bg-[#1a1a2e] text-gray-400 hover:text-white border border-gray-700/50' }}">
            Ditolak
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-[#1a1a2e] rounded-xl border border-gray-700/50 overflow-hidden">
        @if($vendors->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700/50 text-gray-400 text-sm">
                        <th class="text-left px-6 py-4">Foto</th>
                        <th class="text-left px-6 py-4">Nama Lapak</th>
                        <th class="text-left px-6 py-4">Pemilik</th>
                        <th class="text-left px-6 py-4">Kategori</th>
                        <th class="text-left px-6 py-4">Status</th>
                        <th class="text-left px-6 py-4">Dibuat</th>
                        <th class="text-right px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @foreach($vendors as $vendor)
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4">
                            @if($vendor->image)
                                <img src="{{ asset('storage/' . $vendor->image) }}" alt="{{ $vendor->name }}"
                                     class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-700 flex items-center justify-center text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-white font-medium">{{ $vendor->name }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $vendor->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="text-gray-300">{{ $vendor->category->icon ?? '' }} {{ $vendor->category->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($vendor->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-400">Pending</span>
                            @elseif($vendor->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400">Disetujui</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-400">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $vendor->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.vendors.show', $vendor->id) }}"
                                   class="bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 px-3 py-1.5 rounded-lg text-sm transition">
                                    Lihat
                                </a>
                                @if($vendor->status !== 'approved')
                                <form action="{{ route('admin.vendors.approve', $vendor->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500/20 hover:bg-green-500/30 text-green-400 px-3 py-1.5 rounded-lg text-sm transition">
                                        Setujui
                                    </button>
                                </form>
                                @endif
                                @if($vendor->status !== 'rejected')
                                <form action="{{ route('admin.vendors.reject', $vendor->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-500/20 hover:bg-red-500/30 text-red-400 px-3 py-1.5 rounded-lg text-sm transition">
                                        Tolak
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-700/50">
            {{ $vendors->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <div class="text-5xl mb-4">🏪</div>
            <h3 class="text-lg font-semibold text-white mb-2">Tidak ada lapak ditemukan</h3>
            <p class="text-gray-400">Belum ada lapak vendor yang terdaftar{{ request('status') ? ' dengan status ini' : '' }}.</p>
        </div>
        @endif
    </div>
</div>
@endsection
