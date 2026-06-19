@extends('layouts.dashboard')

@section('dashboard-title', 'Ulasan Saya')

@section('dashboard-content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-white">Ulasan Saya</h1>

    @if($reviews->count() > 0)
        <div class="space-y-4">
            @foreach($reviews as $review)
                <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center text-2xl border border-orange-500/20">
                                {{ $review->vendor->category->icon ?? '🍜' }}
                            </div>
                            <div>
                                <a href="{{ route('vendors.show', $review->vendor) }}"
                                   class="text-white font-semibold hover:text-orange-400 transition-colors">
                                    {{ $review->vendor->name }}
                                </a>
                                @if($review->vendor->category)
                                    <p class="text-xs text-gray-500">{{ $review->vendor->category->name }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>

                    @if($review->comment)
                        <p class="text-sm text-gray-300 mt-3">{{ $review->comment }}</p>
                    @endif

                    @if($review->image)
                        <img src="{{ asset('storage/' . $review->image) }}" alt="Review photo"
                             class="mt-3 w-32 h-32 object-cover rounded-lg border border-gray-700/30">
                    @endif

                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-700/30">
                        <span class="text-xs text-gray-500">{{ $review->created_at->format('d M Y, H:i') }}</span>
                        <div class="flex items-center gap-3">
                            <button onclick="document.getElementById('edit-review-{{ $review->id }}').classList.toggle('hidden')"
                                    class="text-xs text-blue-400 hover:text-blue-300">Edit</button>
                            <form action="{{ route('buyer.reviews.destroy', $review) }}" method="POST"
                                  onsubmit="return confirm('Hapus ulasan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-300">Hapus</button>
                            </form>
                        </div>
                    </div>

                    {{-- Edit Form (hidden by default) --}}
                    <div id="edit-review-{{ $review->id }}" class="hidden mt-4 pt-4 border-t border-gray-700/30">
                        <form action="{{ route('buyer.reviews.update', $review) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')

                            <div class="mb-3" x-data="{ rating: {{ $review->rating }}, hover: 0 }">
                                <label class="block text-sm text-gray-400 mb-2">Rating</label>
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button"
                                                @click="rating = {{ $i }}"
                                                @mouseenter="hover = {{ $i }}"
                                                @mouseleave="hover = 0"
                                                class="text-xl transition-colors"
                                                :class="(hover || rating) >= {{ $i }} ? 'text-yellow-400' : 'text-gray-600'">
                                            ★
                                        </button>
                                    @endfor
                                    <input type="hidden" name="rating" x-model="rating" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <textarea name="comment" rows="3" placeholder="Update komentar..."
                                          class="w-full bg-[#0f0f23] border border-gray-700/50 rounded-lg px-4 py-3 text-white text-sm placeholder-gray-500 focus:outline-none focus:border-orange-500/50 resize-none">{{ $review->comment }}</textarea>
                            </div>

                            <button type="submit"
                                    class="px-4 py-2 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 transition-colors">
                                Simpan
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl p-12 text-center">
            <span class="text-5xl block mb-4">⭐</span>
            <p class="text-gray-400 mb-2">Belum ada ulasan.</p>
            <p class="text-gray-500 text-sm mb-4">Jelajahi peta dan beri ulasan untuk lapak favoritmu!</p>
            <a href="{{ route('map') }}"
               class="inline-block px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Jelajahi Peta
            </a>
        </div>
    @endif
</div>
@endsection
