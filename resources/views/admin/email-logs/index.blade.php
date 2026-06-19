@extends('layouts.dashboard')

@section('dashboard-title', 'Email Logs')

@section('dashboard-content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Email Logs</h1>
        <div class="flex items-center gap-3">
            <span class="text-sm text-green-400">✅ {{ $stats['sent'] }} terkirim</span>
            <span class="text-sm text-red-400">❌ {{ $stats['failed'] }} gagal</span>
        </div>
    </div>

    <div class="bg-[#1a1a2e] border border-gray-700/50 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700/50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase">Penerima</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase">Tipe</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase">Subjek</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-gray-400 uppercase">Status</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-gray-400 uppercase">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/30">
                    @foreach($logs as $log)
                        <tr class="hover:bg-white/[0.02]">
                            <td class="px-6 py-3">
                                <p class="text-sm text-white">{{ $log->user->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $log->recipient_email }}</p>
                            </td>
                            <td class="px-6 py-3">
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-700/30 text-gray-300">{{ $log->type }}</span>
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-300 max-w-[200px] truncate">{{ $log->subject }}</td>
                            <td class="px-6 py-3 text-center">
                                @if($log->status === 'sent')
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-green-500/10 text-green-400">✅ Terkirim</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-red-500/10 text-red-400">❌ Gagal</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-right text-xs text-gray-500">
                                {{ $log->sent_at?->format('d M Y, H:i') ?? '-' }}
                            </td>
                        </tr>
                        @if($log->error_message)
                            <tr>
                                <td colspan="5" class="px-6 py-2 bg-red-500/5">
                                    <p class="text-xs text-red-400">Error: {{ $log->error_message }}</p>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($logs->count() === 0)
            <div class="px-6 py-12 text-center text-gray-500">Belum ada email log.</div>
        @endif
    </div>

    <div>{{ $logs->links() }}</div>
</div>
@endsection
