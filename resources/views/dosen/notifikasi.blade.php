<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRO-MENTOR — Notifikasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('dosen.partials.nav')
<div style="padding:20px 24px;max-width:760px;margin:0 auto;">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <div style="font-size:15px;font-weight:600;">Notifikasi</div>
        @if($notifikasi->where('dibaca',false)->count() > 0)
            <form method="POST" action="{{ route('dosen.notifikasi.baca-semua') }}">
                @csrf
                <button type="submit" class="pm-btn" style="font-size:12px;padding:6px 14px;">Tandai Semua Dibaca</button>
            </form>
        @endif
    </div>

    @if($notifikasi->isEmpty())
        <div class="pm-card" style="text-align:center;padding:40px;color:#888;">
            <div style="font-size:13px;">Belum ada notifikasi untuk Anda.</div>
        </div>
    @else
        <div class="pm-card" style="padding:0 18px;">
            @foreach($notifikasi as $n)
                <div class="pm-row" style="padding:14px 0;align-items:flex-start;gap:14px;">
                    {{-- INDIKATOR --}}
                    <div style="margin-top:4px;flex-shrink:0;">
                        <div style="width:9px;height:9px;border-radius:50%;background:{{ $n->dibaca ? '#d1d5db' : '#185FA5' }};"></div>
                    </div>
                    {{-- KONTEN --}}
                    <div style="flex:1;">
                        <div style="font-size:13px;font-weight:{{ $n->dibaca ? '400' : '600' }};color:{{ $n->dibaca ? '#666' : '#1a1a2e' }};margin-bottom:3px;">
                            {{ $n->judul }}
                        </div>
                        <div style="font-size:12px;color:#888;line-height:1.6;">{{ $n->pesan }}</div>
                    </div>
                    {{-- WAKTU + AKSI --}}
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-size:11px;color:#aaa;">{{ $n->created_at->diffForHumans() }}</div>
                        @if(!$n->dibaca)
                            <form method="POST" action="{{ route('dosen.notifikasi.baca', $n) }}" style="margin-top:4px;">
                                @csrf
                                <button type="submit" style="font-size:11px;color:#185FA5;background:none;border:none;cursor:pointer;padding:0;">Tandai dibaca</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        @if($notifikasi->hasPages())
            <div style="margin-top:10px;font-size:12px;color:#888;">{{ $notifikasi->links() }}</div>
        @endif
    @endif
</div>

<div style="text-align:center;padding:16px;font-size:11px;color:#aaa;border-top:1px solid #e8eaed;margin-top:20px;">PRO-MENTOR v1.0 · JTIK UNM · 2026</div>
</body></html>
