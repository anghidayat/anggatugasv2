@extends('layouts.dashboard')

@section('dashboard-content')
<div class="space-y-8">
    {{-- Page Title --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
            <p class="text-gray-400 mt-1">Kelola semua data StreetFoodies dari sini.</p>
        </div>
    </div>

    {{-- Stat Cards Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        {{-- Total Users --}}
        <div class="group relative bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-4 hover:scale-105 transition-all duration-300 hover:border-orange-500/30">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg bg-orange-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['totalUsers'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Users</p>
        </div>

        {{-- Vendors --}}
        <div class="group relative bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-4 hover:scale-105 transition-all duration-300 hover:border-green-500/30">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['totalVendors'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Lapak</p>
        </div>

        {{-- Pending --}}
        <div class="group relative bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-4 hover:scale-105 transition-all duration-300 hover:border-yellow-500/30">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($stats['pendingVendors'] > 0)
                    <span class="px-1.5 py-0.5 text-xs font-medium rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 animate-pulse">{{ $stats['pendingVendors'] }}</span>
                @endif
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['pendingVendors'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Pending</p>
        </div>

        {{-- Menus --}}
        <div class="group relative bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-4 hover:scale-105 transition-all duration-300 hover:border-blue-500/30">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['totalMenus'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Menu</p>
        </div>

        {{-- Reviews --}}
        <div class="group relative bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-4 hover:scale-105 transition-all duration-300 hover:border-purple-500/30">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['totalReviews'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Ulasan</p>
        </div>

        {{-- Email --}}
        <div class="group relative bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-4 hover:scale-105 transition-all duration-300 hover:border-cyan-500/30">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg bg-cyan-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $emailStats['sent'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Email Terkirim</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Vendors per Category Chart --}}
        <div class="bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Lapak per Kategori</h2>
            <canvas id="vendorsPerCategoryChart" class="w-full h-64"></canvas>
        </div>

        {{-- Reviews per Month Chart --}}
        <div class="bg-[#1a1a2e]/80 border border-gray-800 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Ulasan per Bulan</h2>
            <canvas id="reviewsPerMonthChart" class="w-full h-64"></canvas>
        </div>
    </div>

    {{-- Two Column Section: Recent Vendors & Latest Reviews --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Vendors Table --}}
        <div class="bg-[#1a1a2e]/80 border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Vendor Terbaru</h2>
                <a href="{{ route('admin.vendors.index') }}" class="text-sm text-orange-400 hover:text-orange-300 transition-colors">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 uppercase tracking-wider border-b border-gray-800/50">
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Pemilik</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @forelse($recentVendors as $vendor)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-3">
                                    <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-sm text-white hover:text-orange-400 transition-colors">{{ $vendor->name }}</a>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="text-sm text-gray-400">{{ $vendor->user->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-3">
                                    @if($vendor->status === 'approved')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400">Disetujui</span>
                                    @elseif($vendor->status === 'pending')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-500/10 text-orange-400">Menunggu</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada vendor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Latest Reviews --}}
        <div class="bg-[#1a1a2e]/80 border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Ulasan Terbaru</h2>
                <a href="{{ route('admin.reviews.index') }}" class="text-sm text-orange-400 hover:text-orange-300 transition-colors">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-gray-800/50">
                @forelse($latestReviews as $review)
                    <div class="px-6 py-4 hover:bg-white/5 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-white">{{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-white">{{ $review->user->name ?? 'User' }}</span>
                                    <div class="flex items-center gap-0.5">
                                        @for($s = 1; $s <= 5; $s++)
                                            <svg class="w-3 h-3 {{ $s <= ($review->rating ?? 0) ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-sm text-gray-400 mt-1 truncate">{{ $review->comment ?? '' }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $review->vendor->name ?? 'Vendor' }} • {{ $review->created_at?->diffForHumans() ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-500">Belum ada ulasan.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Vendors per Category Chart ──
    const categoryData = @json($vendorsPerCategory);
    if (categoryData.length > 0) {
        const ctx1 = document.getElementById('vendorsPerCategoryChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: categoryData.map(c => c.icon + ' ' + c.name),
                datasets: [{
                    label: 'Jumlah Lapak',
                    data: categoryData.map(c => c.count),
                    backgroundColor: [
                        'rgba(249, 115, 22, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(168, 85, 247, 0.7)',
                        'rgba(236, 72, 153, 0.7)',
                        'rgba(20, 184, 166, 0.7)',
                        'rgba(250, 204, 21, 0.7)',
                    ],
                    borderColor: [
                        'rgba(249, 115, 22, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(168, 85, 247, 1)',
                        'rgba(236, 72, 153, 1)',
                        'rgba(20, 184, 166, 1)',
                        'rgba(250, 204, 21, 1)',
                    ],
                    borderWidth: 1,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#6b7280',
                            stepSize: 1,
                        },
                        grid: { color: 'rgba(107, 114, 128, 0.1)' },
                    },
                    x: {
                        ticks: { color: '#6b7280', font: { size: 11 } },
                        grid: { display: false },
                    },
                },
            },
        });
    }

    // ── Reviews per Month Chart ──
    const reviewData = @json($reviewsPerMonth);
    if (reviewData.length > 0) {
        const ctx2 = document.getElementById('reviewsPerMonthChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: reviewData.map(r => r.month),
                datasets: [{
                    label: 'Ulasan',
                    data: reviewData.map(r => r.count),
                    borderColor: 'rgba(249, 115, 22, 1)',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(249, 115, 22, 1)',
                    pointBorderColor: '#1a1a2e',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#6b7280',
                            stepSize: 1,
                        },
                        grid: { color: 'rgba(107, 114, 128, 0.1)' },
                    },
                    x: {
                        ticks: { color: '#6b7280' },
                        grid: { color: 'rgba(107, 114, 128, 0.1)' },
                    },
                },
            },
        });
    }
});
</script>
@endpush
