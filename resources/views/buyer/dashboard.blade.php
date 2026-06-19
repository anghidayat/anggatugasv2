@extends('layouts.dashboard')

@section('dashboard-content')
<div class="space-y-8">
    {{-- Page Title & Greeting --}}
    <div>
        <h1 class="text-3xl font-bold text-white">Dashboard</h1>
        <p class="text-gray-400 mt-1">Selamat datang kembali, <span class="text-orange-400 font-medium">{{ Auth::user()->name ?? 'Foodie' }}</span>! 🍜</p>
    </div>

    {{-- Stat Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
        {{-- Reviews Written --}}
        <div class="group relative bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-6 hover:scale-105 transition-all duration-300 hover:border-orange-500/30 hover:shadow-lg hover:shadow-orange-500/5">
            <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-orange-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-full bg-orange-500/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-orange-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-white">{{ $myReviews ?? 0 }}</p>
                <p class="text-sm text-gray-400 mt-1">Ulasan Ditulis</p>
            </div>
        </div>

        {{-- Vendors Explored --}}
        <div class="group relative bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-6 hover:scale-105 transition-all duration-300 hover:border-green-500/30 hover:shadow-lg hover:shadow-green-500/5">
            <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-green-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-green-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-white">{{ $vendorsExplored ?? 0 }}</p>
                <p class="text-sm text-gray-400 mt-1">Pedagang Dijelajahi</p>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div>
        <h2 class="text-lg font-semibold text-white mb-4">Jelajahi StreetFoodies</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Explore Map --}}
            <a href="{{ route('map') ?? '#' }}" class="group flex items-center gap-4 bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-5 hover:border-orange-500/30 hover:shadow-lg hover:shadow-orange-500/5 transition-all duration-300 hover:scale-105">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500/20 to-red-500/20 flex items-center justify-center flex-shrink-0 group-hover:from-orange-500/30 group-hover:to-red-500/30 transition-all duration-300">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold group-hover:text-orange-400 transition-colors">Jelajahi Peta</p>
                    <p class="text-sm text-gray-500">Temukan pedagang terdekat</p>
                </div>
            </a>

            {{-- Browse Vendors --}}
            <a href="{{ route('search') }}" class="group flex items-center gap-4 bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-5 hover:border-green-500/30 hover:shadow-lg hover:shadow-green-500/5 transition-all duration-300 hover:scale-105">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center flex-shrink-0 group-hover:from-green-500/30 group-hover:to-emerald-500/30 transition-all duration-300">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold group-hover:text-green-400 transition-colors">Telusuri Pedagang</p>
                    <p class="text-sm text-gray-500">Lihat semua pedagang</p>
                </div>
            </a>

            {{-- Read Articles --}}
            <a href="{{ route('articles.index') ?? '#' }}" class="group flex items-center gap-4 bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-5 hover:border-purple-500/30 hover:shadow-lg hover:shadow-purple-500/5 transition-all duration-300 hover:scale-105">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center flex-shrink-0 group-hover:from-purple-500/30 group-hover:to-pink-500/30 transition-all duration-300">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold group-hover:text-purple-400 transition-colors">Baca Artikel</p>
                    <p class="text-sm text-gray-500">Tips & kuliner jalanan</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Your Recent Reviews --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-white">Ulasan Terakhirmu</h2>
            @if(($myReviews ?? 0) > 0)
                <a href="#" class="text-sm text-orange-400 hover:text-orange-300 transition-colors">Lihat Semua →</a>
            @endif
        </div>

        @if(isset($recentReviews) && count($recentReviews) > 0)
            <div class="space-y-3">
                @foreach($recentReviews as $review)
                    <div class="bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition-all duration-300">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-white font-semibold truncate">{{ $review->vendor->name ?? 'Vendor' }}</h3>
                                    <div class="flex items-center gap-0.5 flex-shrink-0">
                                        @for($s = 1; $s <= 5; $s++)
                                            <svg class="w-4 h-4 {{ $s <= ($review->rating ?? 0) ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-400 text-sm">{{ $review->comment ?? '' }}</p>
                                <p class="text-xs text-gray-600 mt-2">{{ $review->created_at?->diffForHumans() ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-[#1a1a2e]/80 border border-gray-800 border-dashed rounded-xl p-10 text-center">
                <div class="w-16 h-16 mx-auto rounded-full bg-orange-500/10 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-orange-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">Belum Ada Ulasan</h3>
                <p class="text-gray-400 mb-4 max-w-sm mx-auto">Kunjungi pedagang kaki lima dan bagikan pengalamanmu melalui ulasan!</p>
                <a href="{{ route('search') }}" class="text-orange-400 hover:text-orange-300 text-sm font-medium transition-colors">Jelajahi Pedagang →</a>
            </div>
        @endif
    </div>

    {{-- CTA Banner --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-orange-500/10 via-red-500/10 to-orange-500/10 border border-orange-500/20 rounded-2xl p-8 md:p-10">
        {{-- Background decoration --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-orange-500/10 to-transparent rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-red-500/10 to-transparent rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl"></div>

        <div class="relative flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left">
                <h3 class="text-2xl font-bold text-white mb-2">🍜 Temukan pedagang kaki lima favoritmu!</h3>
                <p class="text-gray-300 max-w-lg">Jelajahi ribuan pedagang kaki lima di sekitarmu. Dari bakso, sate, hingga martabak — semua ada di StreetFoodies.</p>
            </div>
            <a href="{{ route('map') ?? '#' }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold rounded-xl hover:from-orange-600 hover:to-red-600 transition-all duration-300 hover:shadow-xl hover:shadow-orange-500/25 hover:scale-105 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Jelajahi Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
