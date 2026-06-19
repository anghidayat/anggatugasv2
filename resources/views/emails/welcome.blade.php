@extends('emails.layout')

@section('title', 'Selamat Datang di StreetFoodies!')

@section('content')
<div class="card">
    <div class="header" style="text-align: center;">
        <div style="font-size: 48px; margin-bottom: 8px;">🍜</div>
        <h1>Selamat Datang di StreetFoodies!</h1>
    </div>

    <div class="body">
        <p>Halo <strong>{{ $user->name }}</strong>,</p>

        <p>Terima kasih telah bergabung dengan <strong>StreetFoodies</strong> — platform penemuan kuliner kaki lima terbaik di Indonesia! 🎉</p>

        <p>
            @if($user->isVendor())
                Sebagai <strong>Vendor</strong>, kamu bisa:
                <br>✅ Membuat dan mengelola lapak kuliner
                <br>✅ Menambahkan menu dengan foto & filter
                <br>✅ Menandai lokasi lapak di peta interaktif
                <br>✅ Menerima ulasan dari pembeli
            @else
                Sebagai <strong>Pembeli</strong>, kamu bisa:
                <br>🔍 Mencari dan menjelajahi lapak kuliner
                <br>🗺️ Melihat peta interaktif street food
                <br>⭐ Memberikan ulasan dan rating
                <br>📰 Membaca artikel kuliner terbaru
            @endif
        </p>

        <div style="text-align: center; margin: 24px 0;">
            <a href="{{ url('/') }}" class="button" style="font-size: 16px; padding: 14px 40px;">
                Mulai Jelajahi 🚀
            </a>
        </div>

        <p style="font-size: 13px; color: #6b7280;">
            Punya pertanyaan? Balas email ini atau hubungi kami kapan saja.
        </p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} <a href="{{ url('/') }}">StreetFoodies</a> — Platform penemuan kuliner kaki lima.</p>
        <p>Jakarta, Indonesia</p>
    </div>
</div>
@endsection
