@extends('emails.layout')

@section('title', $newStatus === 'approved' ? 'Lapak Disetujui!' : 'Status Lapak')

@section('content')
<div class="card">
    <div class="header" style="text-align: center;">
        <div style="font-size: 48px; margin-bottom: 8px;">{{ $newStatus === 'approved' ? '🎉' : '📋' }}</div>
        <h1>
            @if($newStatus === 'approved')
                Lapak Disetujui!
            @else
                Status Lapak Diperbarui
            @endif
        </h1>
    </div>

    <div class="body">
        <p>Halo <strong>{{ $vendorName }}</strong>,</p>

        <p>
            Status lapak <strong>"{{ $vendor->name }}"</strong> telah diperbarui:
        </p>

        <div style="text-align: center; margin: 20px 0;">
            <span class="badge {{ $newStatus === 'approved' ? 'badge-approved' : 'badge-rejected' }}" style="font-size: 15px; padding: 8px 20px;">
                {{ $newStatus === 'approved' ? '✅ DISETUJUI' : '❌ DITOLAK' }}
            </span>
        </div>

        @if($newStatus === 'approved')
            <div class="highlight-box">
                <p style="margin: 0; font-size: 14px;">
                    🎊 Lapak kamu sekarang sudah muncul di peta dan bisa ditemukan oleh pembeli. 
                    Jangan lupa untuk menambahkan menu dan foto agar makin menarik!
                </p>
            </div>

            <div style="text-align: center; margin: 24px 0;">
                <a href="{{ route('vendor.vendors.show', $vendor) }}" class="button">
                    Lihat Lapak →
                </a>
            </div>
        @else
            @if($reason)
                <div class="highlight-box" style="border-left-color: #ef4444; background: rgba(239, 68, 68, 0.08);">
                    <p style="margin: 0; font-size: 14px;">
                        <strong>Alasan penolakan:</strong><br>
                        {{ $reason }}
                    </p>
                </div>
            @endif

            <p>
                Jangan khawatir! Kamu bisa memperbaiki data lapak dan mengajukan ulang. 
                Pastikan informasi lapak sudah lengkap dan jelas.
            </p>

            <div style="text-align: center; margin: 24px 0;">
                <a href="{{ route('vendor.vendors.edit', $vendor) }}" class="button" style="background: #374151;">
                    Perbaiki Lapak →
                </a>
            </div>
        @endif

        <p style="font-size: 13px; color: #6b7280;">
            Email ini dikirim otomatis oleh sistem StreetFoodies.
        </p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} <a href="{{ url('/') }}">StreetFoodies</a> — Platform penemuan kuliner kaki lima.</p>
    </div>
</div>
@endsection
