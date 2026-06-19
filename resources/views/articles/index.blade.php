@extends('layouts.app')

@section('title', 'Artikel Kuliner — StreetFoodies')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Artikel Kuliner</h1>
        <p class="text-gray-400">Cerita, tips, dan berita seputar dunia street food Indonesia</p>
    </div>

    {{-- Articles Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($articles as $article)
            <a href="{{ $article['url'] !== '#' ? $article['url'] : 'javascript:void(0)' }}"
               @if($article['url'] === '#') onclick="return false" @endif
               target="{{ $article['url'] !== '#' ? '_blank' : '_self' }}"
               rel="{{ $article['url'] !== '#' ? 'noopener' : '' }}"
               class="group bg-[#1a1a2e] border border-gray-700/50 rounded-xl overflow-hidden hover:border-orange-500/20 transition-all hover:-translate-y-1">
                
                {{-- Image --}}
                <div class="h-48 bg-[#0f0f23] flex items-center justify-center border-b border-gray-700/30 relative overflow-hidden">
                    @if($article['image'])
                        <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <span class="hidden absolute inset-0 text-5xl flex items-center justify-center">🍜</span>
                    @else
                        <span class="text-5xl">{{ ['🍜', '🍢', '🥘', '🍲', '🍛', '🥟', '🍱', '🧆'][array_rand(['🍜', '🍢', '🥘', '🍲', '🍛', '🥟', '🍱', '🧆'])] }}</span>
                    @endif
                </div>

                {{-- Content --}}
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-md bg-orange-500/10 text-orange-400 border border-orange-500/20">
                            {{ $article['source'] }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($article['published'])->diffForHumans() }}
                        </span>
                    </div>

                    <h3 class="text-white font-semibold mb-2 group-hover:text-orange-400 transition-colors line-clamp-2">
                        {{ $article['title'] }}
                    </h3>
                    <p class="text-sm text-gray-400 line-clamp-2">
                        {{ $article['description'] }}
                    </p>

                    <div class="mt-4 flex items-center text-sm text-orange-400 font-medium">
                        Baca selengkapnya
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Demo mode notice --}}
    @if(!env('GNEWS_API_KEY') && !env('NEWSAPI_KEY'))
        <div class="mt-10 bg-[#1a1a2e] border border-yellow-500/20 rounded-xl p-5 text-center">
            <span class="text-2xl block mb-2">📰</span>
            <p class="text-gray-400 text-sm mb-2">Menampilkan artikel demo</p>
            <p class="text-gray-500 text-xs">
                Untuk artikel live dari GNews, daftar di <a href="https://gnews.io" class="text-orange-400 hover:underline" target="_blank">gnews.io</a> dan tambahkan <code class="text-orange-400 bg-[#0f0f23] px-2 py-0.5 rounded">GNEWS_API_KEY=***</code> ke file <code class="text-orange-400 bg-[#0f0f23] px-2 py-0.5 rounded">.env</code>
            </p>
        </div>
    @endif
</div>
@endsection
