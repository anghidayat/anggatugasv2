@extends('layouts.app')

@section('content')
<div x-data="{ sidebarOpen: false }" class="flex -mx-4 -my-6 min-h-[calc(100vh-10rem)]">

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         x-cloak
         class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden">
    </div>

    {{-- Mobile Sidebar Panel --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         x-cloak
         class="fixed inset-y-0 left-0 z-50 lg:hidden">
        <div class="relative">
            {{-- Close button --}}
            <button @click="sidebarOpen = false"
                    class="absolute top-4 right-4 z-10 p-1 rounded-lg text-gray-400 hover:text-white hover:bg-white/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            @include('layouts.sidebar')
        </div>
    </div>

    {{-- Desktop Sidebar --}}
    @include('layouts.sidebar')

    {{-- Main Dashboard Content --}}
    <div class="flex-1 min-w-0">
        {{-- Mobile Top Bar --}}
        <div class="lg:hidden flex items-center justify-between p-4 bg-dark border-b border-gray-800">
            <button @click="sidebarOpen = true"
                    class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h2 class="text-sm font-semibold text-gray-300">@yield('dashboard-title', 'Dashboard')</h2>
            <div class="w-10"></div> {{-- Spacer for centering --}}
        </div>

        {{-- Dashboard Content Area --}}
        <div class="p-4 lg:p-6">
            {{-- Breadcrumb / Page Header --}}
            @hasSection('dashboard-header')
                <div class="mb-6">
                    @yield('dashboard-header')
                </div>
            @endif

            @yield('dashboard-content')
        </div>
    </div>
</div>
@endsection
