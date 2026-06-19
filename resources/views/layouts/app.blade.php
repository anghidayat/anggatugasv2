<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'StreetFoodies') — Street Food Discovery</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#f97316',
                        secondary: '#ef4444',
                        dark: '#1a1a2e',
                        darker: '#0f0f23',
                        'dark-surface': '#16213e',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        }
    </script>

    {{-- Leaflet.js CSS + JS (1.9.4) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    {{-- Chart.js (4.x) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Custom inline styles --}}
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', system-ui, sans-serif; }
        /* Custom scrollbar for dark theme */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f0f23; }
        ::-webkit-scrollbar-thumb { background: #1a1a2e; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #f97316; }
        /* Touch-friendly minimum tap targets */
        button, a.button, [role="button"] { min-height: 44px; min-width: 44px; }
        /* Smooth page transitions */
        main { animation: fadeIn 0.2s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
        /* Improve form elements on mobile */
        @media (max-width: 640px) {
            input, select, textarea { font-size: 16px !important; }
        }
        /* Safe area for notch phones */
        .safe-bottom { padding-bottom: env(safe-area-inset-bottom, 0); }
    </style>

    @stack('styles')
</head>
<body class="bg-darker text-gray-100 min-h-screen flex flex-col">

    {{-- Navbar --}}
    @include('layouts.navbar')

    {{-- Spacer for fixed navbar --}}
    <div class="h-16"></div>

    {{-- Flash Alerts --}}
    <div class="container mx-auto px-4 pt-4">
        @include('layouts.alert')
    </div>

    {{-- Main Content --}}
    <main class="flex-1 container mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    @stack('scripts')
</body>
</html>
