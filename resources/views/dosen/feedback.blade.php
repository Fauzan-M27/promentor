<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRO-MENTOR — Rekap Feedback</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('dosen.partials.nav')

<div style="padding:20px 24px;max-width:960px;margin:0 auto;">
    <div style="font-size:15px;font-weight:600;margin-bottom:16px;">Rekap Feedback Mentor dari Mentee</div>

    {{-- 3 STAT CARDS --}}
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:16px;">
        <div class="pm-card">
            <div class="pm-stat-label">Total Feedback</div>
            <div class="pm-stat-value">{{ $stats['total_feedback'] }}</div>
            <div class="pm-stat-sub">Dari semua mentee</div>
        </div>
        <div class="pm-card">
            <div class="pm-stat-label">Rata-rata Bintang</div>
            <div class="pm-stat-value" style="color:#f59e0b;">{{ $stats['rata_bintang'] }}</div>
            <div class="pm-stat-sub">Dari 5.0</div>
        </div>
        <div class="pm-card">
            <div class="pm-stat-label">Mentor Terbaik</div>
            @if($mentor_terbaik)
                <div class="pm-stat-value" style="font-size:15px;margin-top:2px;">{{ $mentor_terbaik->name }}</div>
                <div class="pm-stat-sub">Bintang {{ $mentor_terbaik->rata_feedback }}</div>
            @else
                <div class="pm-stat-value" style="font-size:14px;color:#aaa;">—</div>
            @endif
        </div>
    </div>

    {{-- PERFORMA PER MENTOR --}}
    <div class="pm-card" style="margin-bottom:14px;">
        <div style="font-size:13px;font-weight:600;margin-bottom:12px;">Performa Per Mentor</div>
        @forelse($mentor_list as $mentor)
            @php $bintang = $mentor->rata_feedback; @endphp
            <div class="pm-row" style="padding:10px 0;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="pm-av pm-av-blue">{{ strtoupper(substr($mentor->name,0,2)) }}</div>
                    <div>
                        <div style="font-weight:500;font-size:13px;">{{ $mentor->name }}</div>
                        <div style="font-size:11px;color:#888;">{{ $mentor->feedbackDiterima_count }} feedback diterima</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <div style="font-size:15px;line-height:1;display:flex;gap:2px;">
                        @for($i=1;$i<=5;$i++)
                            <i data-lucide="star" style="width:14px;height:14px;color:{{ $i <= round($bintang) ? '#f59e0b' : '#d1d5db' }};fill:{{ $i <= round($bintang) ? '#f59e0b' : 'none' }};"></i>
                        @endfor
                    </div>
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">{{ number_format($bintang,1) }} / 5.0</span>
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:20px;color:#888;font-size:13px;">Belum ada data feedback.</div>
        @endforelse
    </div>

    {{-- KOMENTAR TERBARU --}}
    <div class="pm-card">
        <div style="font-size:13px;font-weight:600;margin-bottom:12px;">Komentar Terbaru Mentee</div>
        @forelse($komentar_terbaru as $fb)
            <div style="padding:12px 0;border-bottom:1px solid #f0f0f0;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                    <div style="font-size:13px;font-weight:500;display:flex;align-items:center;gap:6px;">
                        {{ $fb->mentee->name }} <i data-lucide="arrow-right" style="width:12px;height:12px;color:#888;"></i> {{ $fb->mentor->name }}
                    </div>
                    <div style="font-size:11px;color:#aaa;">{{ $fb->created_at->format('j M Y') }}</div>
                </div>
                <div style="font-size:14px;margin-bottom:4px;display:flex;gap:2px;">
                    @for($i=1;$i<=5;$i++)
                        <i data-lucide="star" style="width:12px;height:12px;color:{{ $i <= round($fb->rata_rata) ? '#f59e0b' : '#d1d5db' }};fill:{{ $i <= round($fb->rata_rata) ? '#f59e0b' : 'none' }};"></i>
                    @endfor
                </div>
                @if($fb->hal_positif)
                    <div style="font-size:12px;color:#555;line-height:1.6;">{{ $fb->hal_positif }}</div>
                @endif
                @if($fb->saran)
                    <div style="font-size:12px;color:#888;margin-top:3px;font-style:italic;">Saran: {{ $fb->saran }}</div>
                @endif
            </div>
        @empty
            <div style="text-align:center;padding:20px;color:#888;font-size:13px;">Belum ada komentar.</div>
        @endforelse
    </div>
</div>
<div style="text-align:center;padding:16px;font-size:11px;color:#aaa;border-top:1px solid #e8eaed;margin-top:20px;">PRO-MENTOR v1.0 · JTIK UNM · 2026</div>

<script>
    lucide.createIcons();
</script>
</body></html>
