@extends('layouts.dashboard')

@section('dashboard-title', 'Moderasi Ulasan')

@section('dashboard-content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Moderasi Ulasan</h1>
        <span class="text-sm text-gray-400">{{ $reviews->total() }} ulasan</span>
    </div>

    @if($reviews->count() > 0)
        <div class="space-y-4">
            @foreach($reviews as $review)
                <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-orange-500/20 flex items-center justify-center">
                                <span class="text-sm font-semibold text-orange-400">{{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-white">{{ $review->user->name ?? 'Anonim' }}</span>
                                    <span class="text-xs text-gray-500">→</span>
                                    <a href="{{ route('vendors.show', $review->vendor) }}" class="text-sm font-medium text-orange-400 hover:text-orange-300">
                                        {{ $review->vendor->name }}
                                    </a>
                                    @if($review->vendor->category)
                                        <span class="text-xs text-gray-500">({{ $review->vendor->category->name }})</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $review->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                              onsubmit="return confirm('Hapus ulasan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    @if($review->comment)
                        <div class="mt-3 bg-[#0f0f23] rounded-lg p-4 border border-gray-700/30">
                            <p class="text-sm text-gray-300">{{ $review->comment }}</p>
                        </div>
                    @endif

                    @if($review->image)
                        <img src="{{ asset('storage/' . $review->image) }}" alt="Review photo"
                             class="mt-3 w-32 h-32 object-cover rounded-lg border border-gray-700/30">
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @else
        <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-12 text-center">
            <span class="text-5xl block mb-4">📝</span>
            <p class="text-gray-400">Belum ada ulasan.</p>
        </div>
    @endif
</div>
@endsection
