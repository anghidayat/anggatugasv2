@extends('layouts.dashboard')

@section('dashboard-content')
<div class="space-y-8">
    {{-- Page Title & Greeting --}}
    <div>
        <h1 class="text-3xl font-bold text-white">Dashboard Pedagang</h1>
        <p class="text-gray-400 mt-1">Halo, <span class="text-orange-400 font-medium">{{ Auth::user()->name ?? 'Pedagang' }}</span>! Kelola lapak dan menu kamu di sini.</p>
    </div>

    {{-- Stat Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
        {{-- My Vendors / Lapak --}}
        <div class="group relative bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-6 hover:scale-105 transition-all duration-300 hover:border-orange-500/30 hover:shadow-lg hover:shadow-orange-500/5">
            <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-orange-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-full bg-orange-500/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-orange-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-white">{{ $myVendors ?? 0 }}</p>
                <p class="text-sm text-gray-400 mt-1">Lapak Saya</p>
            </div>
        </div>

        {{-- Total Menu Items --}}
        <div class="group relative bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-6 hover:scale-105 transition-all duration-300 hover:border-green-500/30 hover:shadow-lg hover:shadow-green-500/5">
            <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-green-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-green-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-white">{{ $totalMenus ?? 0 }}</p>
                <p class="text-sm text-gray-400 mt-1">Total Menu</p>
            </div>
        </div>

        {{-- Average Rating --}}
        <div class="group relative bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-6 hover:scale-105 transition-all duration-300 hover:border-yellow-500/30 hover:shadow-lg hover:shadow-yellow-500/5">
            <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-yellow-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-full bg-yellow-500/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <p class="text-3xl font-bold text-white">{{ $avgRating ?? '0.0' }}</p>
                    <div class="flex items-center gap-0.5 mb-1">
                        @for($s = 1; $s <= 5; $s++)
                            <svg class="w-4 h-4 {{ $s <= round($avgRating ?? 0) ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
                <p class="text-sm text-gray-400 mt-1">Rating Rata-rata</p>
            </div>
        </div>
    </div>

    {{-- Quick Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('vendor.vendors.create') ?? '#' }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300 hover:shadow-lg hover:shadow-orange-500/25 hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Lapak Baru
        </a>
        <a href="{{ route('vendor.menus.create') ?? '#' }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#1a1a2e] border border-orange-500/30 text-orange-400 font-semibold rounded-xl hover:bg-orange-500/10 transition-all duration-300 hover:shadow-lg hover:shadow-orange-500/10 hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Menu
        </a>
    </div>

    {{-- Lapak Saya Section --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-white">Lapak Saya</h2>
            @if(($myVendors ?? 0) > 0)
                <a href="#" class="text-sm text-orange-400 hover:text-orange-300 transition-colors">Lihat Semua →</a>
            @endif
        </div>

        @if(isset($vendors) && count($vendors) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($vendors as $vendor)
                    <div class="group bg-[#1a1a2e]/80 border border-gray-800 rounded-xl overflow-hidden hover:border-orange-500/30 hover:shadow-lg hover:shadow-orange-500/5 transition-all duration-300 hover:scale-[1.02]">
                        {{-- Vendor Image --}}
                        <div class="h-40 bg-gradient-to-br from-[#0f0f23] to-[#1a1a2e] relative overflow-hidden">
                            @if($vendor->image)
                                <img src="{{ asset('storage/' . $vendor->image) }}" alt="{{ $vendor->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <svg class="w-12 h-12 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            @endif
                            {{-- Status Badge --}}
                            <div class="absolute top-3 right-3">
                                @if(($vendor->status ?? '') === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-400 backdrop-blur-sm border border-green-500/20">Aktif</span>
                                @elseif(($vendor->status ?? '') === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-500/20 text-orange-400 backdrop-blur-sm border border-orange-500/20">Menunggu</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-400 backdrop-blur-sm border border-red-500/20">Ditolak</span>
                                @endif
                            </div>
                        </div>
                        {{-- Vendor Info --}}
                        <div class="p-4">
                            <h3 class="text-white font-semibold truncate">{{ $vendor->name }}</h3>
                            <p class="text-gray-400 text-sm mt-1 truncate">{{ $vendor->address ?? 'Alamat belum diatur' }}</p>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-800/50">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm text-gray-300">{{ number_format($vendor->avg_rating ?? 0, 1) }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $vendor->menus_count ?? 0 }} menu</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-[#1a1a2e]/80 border border-gray-800 border-dashed rounded-xl p-12 text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-orange-500/10 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-orange-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">Belum Ada Lapak</h3>
                <p class="text-gray-400 mb-6 max-w-md mx-auto">Mulai berjualan dengan mendaftarkan lapak kaki lima pertamamu di StreetFoodies!</p>
                <a href="{{ route('vendor.vendors.create') ?? '#' }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300 hover:shadow-lg hover:shadow-orange-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Lapak Pertamamu
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
