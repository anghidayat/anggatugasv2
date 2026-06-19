{{-- Flash Message Alerts --}}
@foreach(['success' => 'green', 'error' => 'red', 'warning' => 'yellow', 'info' => 'blue'] as $type => $color)
    @if(session($type))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="relative mb-4 px-4 py-3 rounded-xl border
                    @if($color === 'green') bg-green-500/10 border-green-500/30 text-green-400
                    @elseif($color === 'red') bg-red-500/10 border-red-500/30 text-red-400
                    @elseif($color === 'yellow') bg-yellow-500/10 border-yellow-500/30 text-yellow-400
                    @elseif($color === 'blue') bg-blue-500/10 border-blue-500/30 text-blue-400
                    @endif"
             role="alert">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    {{-- Icon --}}
                    @if($color === 'green')
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @elseif($color === 'red')
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @elseif($color === 'yellow')
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    @elseif($color === 'blue')
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif

                    <p class="text-sm font-medium">{{ session($type) }}</p>
                </div>

                {{-- Close Button --}}
                <button @click="show = false"
                        class="p-1 rounded-lg hover:bg-white/10 transition-colors"
                        aria-label="Dismiss">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Progress bar for auto-dismiss --}}
            <div class="absolute bottom-0 left-0 h-0.5 rounded-b-xl
                        @if($color === 'green') bg-green-500
                        @elseif($color === 'red') bg-red-500
                        @elseif($color === 'yellow') bg-yellow-500
                        @elseif($color === 'blue') bg-blue-500
                        @endif"
                 style="animation: shrinkWidth 5s linear forwards;">
            </div>
        </div>
    @endif
@endforeach

<style>
    @keyframes shrinkWidth {
        from { width: 100%; }
        to { width: 0%; }
    }
</style>
