{{-- Responsive Navbar with Alpine.js --}}
<nav x-data="{ mobileOpen: false, profileOpen: false }"
     class="fixed top-0 left-0 right-0 z-50 bg-dark/80 backdrop-blur-xl border-b border-gray-800/50">
    <div class="container mx-auto px-4 py-3">
        <div class="flex items-center justify-between">

            {{-- Left: Logo --}}
            <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                <span class="text-2xl">🍜</span>
                <span class="text-xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent
                             group-hover:from-secondary group-hover:to-primary transition-all duration-300">
                    StreetFoodies
                </span>
            </a>

            {{-- Center: Desktop Nav Links --}}
            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ url('/') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                          {{ request()->is('/') ? 'bg-primary/20 text-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Home
                </a>
                <a href="{{ route('map') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                          {{ request()->routeIs('map') ? 'bg-primary/20 text-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    Explore Map
                </a>
                <a href="{{ route('search') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                          {{ request()->routeIs('search*') ? 'bg-primary/20 text-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Search
                </a>
                <a href="{{ route('articles.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                          {{ request()->routeIs('articles.*') ? 'bg-primary/20 text-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    Articles
                </a>
            </div>

            {{-- Right: Auth Buttons --}}
            <div class="hidden md:flex items-center space-x-3">
                @guest
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white transition-colors duration-200">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-5 py-2 text-sm font-semibold rounded-lg bg-gradient-to-r from-primary to-secondary
                              text-white hover:shadow-lg hover:shadow-primary/25 transition-all duration-300 hover:-translate-y-0.5">
                        Register
                    </a>
                @endguest

                @auth
                    <div class="relative" x-data="{ profileOpen: false }">
                        <button @click="profileOpen = !profileOpen"
                                @click.outside="profileOpen = false"
                                class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-white/5 transition-colors duration-200">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-300">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                 :class="{ 'rotate-180': profileOpen }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="profileOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                             x-cloak
                             class="absolute right-0 mt-2 w-48 bg-dark-surface rounded-xl border border-gray-700/50 shadow-2xl shadow-black/50 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-700/50">
                                <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ auth()->check() ? route(auth()->user()->role . '.dashboard') : '#' }}"
                                   class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-primary/10 hover:text-primary transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('profile.show') }}"
                                   class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-primary/10 hover:text-primary transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profile
                                </a>
                            </div>
                            <div class="border-t border-gray-700/50 py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center w-full px-4 py-2 text-sm text-secondary hover:bg-secondary/10 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>

            {{-- Mobile: Hamburger Button --}}
            <button @click="mobileOpen = !mobileOpen"
                    class="md:hidden p-2 rounded-lg hover:bg-white/5 transition-colors duration-200">
                <svg x-show="!mobileOpen" class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileOpen" x-cloak class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             x-cloak
             class="md:hidden mt-3 pt-3 border-t border-gray-700/50 space-y-1">

            <a href="{{ url('/') }}"
               class="block px-4 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->is('/') ? 'bg-primary/20 text-primary' : 'text-gray-300 hover:bg-white/5' }}">
                🏠 Home
            </a>
            <a href="{{ route('map') }}"
               class="block px-4 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->routeIs('map') ? 'bg-primary/20 text-primary' : 'text-gray-300 hover:bg-white/5' }}">
                🗺️ Explore Map
            </a>
            <a href="{{ route('search') }}"
               class="block px-4 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->routeIs('search*') ? 'bg-primary/20 text-primary' : 'text-gray-300 hover:bg-white/5' }}">
                🔍 Search
            </a>
            <a href="{{ route('articles.index') }}"
               class="block px-4 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->routeIs('articles.*') ? 'bg-primary/20 text-primary' : 'text-gray-300 hover:bg-white/5' }}">
                📰 Articles
            </a>

            <div class="border-t border-gray-700/50 pt-3 mt-3">
                @guest
                    <a href="{{ route('login') }}"
                       class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-center bg-gradient-to-r from-primary to-secondary text-white mt-2">
                        Register
                    </a>
                @endguest

                @auth
                    <div class="px-4 py-2 flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ Auth::user()->role }}</p>
                        </div>
                    </div>
                    <a href="{{ auth()->check() ? route(auth()->user()->role . '.dashboard') : '#' }}"
                       class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('profile.show') }}"
                       class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5">
                        👤 Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="block w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium text-secondary hover:bg-secondary/10">
                            🚪 Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>
