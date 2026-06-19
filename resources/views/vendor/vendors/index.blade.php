@extends('layouts.dashboard')

@section('dashboard-content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Lapak Saya</h1>
            <p class="text-gray-400 text-sm mt-1">Kelola semua lapak jualan Anda</p>
        </div>
        <a href="{{ route('vendor.vendors.create') }}"
           class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg font-semibold transition-colors shadow-lg shadow-orange-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Lapak
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4 text-green-400 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Vendor Cards --}}
    @if($vendors->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($vendors as $vendor)
                <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl overflow-hidden hover:border-orange-500/30 transition-all duration-300 group">
                    {{-- Image --}}
                    <div class="relative h-48 overflow-hidden">
                        @if($vendor->image)
                            <img src="{{ asset('storage/' . $vendor->image) }}" alt="{{ $vendor->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-[#0f0f23] flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Status Badge --}}
                        <div class="absolute top-3 left-3">
                            @if($vendor->status === 'approved')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-500/20 text-green-400 border border-green-500/30">
                                    Disetujui
                                </span>
                            @elseif($vendor->status === 'rejected')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-500/20 text-red-400 border border-red-500/30">
                                    Ditolak
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                                    Menunggu
                                </span>
                            @endif
                        </div>

                        {{-- Toggle Open Button --}}
                        <div class="absolute top-3 right-3">
                            <form action="{{ route('vendor.vendors.toggleOpen', $vendor->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $vendor->is_open ? 'bg-green-500/20 text-green-400 border border-green-500/30 hover:bg-green-500/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30 hover:bg-gray-500/30' }}">
                                    <span class="w-2 h-2 rounded-full {{ $vendor->is_open ? 'bg-green-400' : 'bg-gray-500' }}"></span>
                                    {{ $vendor->is_open ? 'Buka' : 'Tutup' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-5 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-bold text-white group-hover:text-orange-400 transition-colors">
                                    {{ $vendor->name }}
                                </h3>
                                @if($vendor->category)
                                    <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-md bg-orange-500/10 text-orange-400 border border-orange-500/20">
                                        {{ $vendor->category->name }}
                                    </span>
                                @endif
                            </div>
                            {{-- Rating --}}
                            <div class="flex items-center gap-1 flex-shrink-0">
                                @php $avgRating = round($vendor->reviews_avg_rating ?? 0, 1); @endphp
                                <svg class="w-4 h-4 {{ $avgRating > 0 ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm font-medium {{ $avgRating > 0 ? 'text-white' : 'text-gray-500' }}">
                                    {{ $avgRating > 0 ? $avgRating : '-' }}
                                </span>
                                <span class="text-xs text-gray-500">({{ $vendor->reviews_count }})</span>
                            </div>
                        </div>

                        {{-- Address --}}
                        @if($vendor->address)
                            <p class="text-sm text-gray-400 flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="line-clamp-1">{{ $vendor->address }}</span>
                            </p>
                        @endif

                        {{-- Operating Hours --}}
                        @if($vendor->open_time && $vendor->close_time)
                            <p class="text-sm text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($vendor->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($vendor->close_time)->format('H:i') }}
                            </p>
                        @endif

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 pt-2 border-t border-gray-700/50">
                            <a href="{{ route('vendor.vendors.show', $vendor->id) }}"
                               class="flex-1 text-center px-3 py-2 text-sm font-medium rounded-lg bg-[#0f0f23] text-gray-300 hover:text-white hover:bg-blue-500/20 hover:border-blue-500/30 border border-gray-700/50 transition-colors">
                                Lihat
                            </a>
                            <a href="{{ route('vendor.vendors.edit', $vendor->id) }}"
                               class="flex-1 text-center px-3 py-2 text-sm font-medium rounded-lg bg-[#0f0f23] text-gray-300 hover:text-orange-400 hover:bg-orange-500/10 hover:border-orange-500/30 border border-gray-700/50 transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('vendor.vendors.destroy', $vendor->id) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('Yakin ingin menghapus lapak ini? Semua data termasuk menu dan ulasan akan ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full px-3 py-2 text-sm font-medium rounded-lg bg-[#0f0f23] text-gray-300 hover:text-red-400 hover:bg-red-500/10 hover:border-red-500/30 border border-gray-700/50 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-12 text-center">
            <div class="max-w-sm mx-auto">
                <svg class="w-20 h-20 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"/>
                </svg>
                <h3 class="text-lg font-semibold text-white mb-2">Belum Ada Lapak</h3>
                <p class="text-gray-400 text-sm mb-6">Anda belum memiliki lapak. Buat lapak pertama Anda untuk mulai berjualan!</p>
                <a href="{{ route('vendor.vendors.create') }}"
                   class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg shadow-orange-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Lapak Pertama
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
