<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRO-MENTOR — Beranda Dosen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('dosen.partials.nav')

<div style="padding:20px 24px;max-width:960px;margin:0 auto;">

    {{-- STAT CARDS --}}
    <div class="pm-stat-grid">
        <div class="pm-stat-card">
            <div class="pm-stat-label">Total Pendaftar</div>
            <div class="pm-stat-value">{{ $stats['total_pendaftar'] }}</div>
            <div class="pm-stat-sub">TA 2026</div>
        </div>
        <div class="pm-stat-card">
            <div class="pm-stat-label">Dalam Seleksi</div>
            <div class="pm-stat-value">{{ $stats['dalam_seleksi'] }}</div>
            <div class="pm-stat-sub">Diproses</div>
        </div>
        <div class="pm-stat-card">
            <div class="pm-stat-label">Mentor Diterima</div>
            <div class="pm-stat-value" style="color:#166534;">{{ $stats['diterima'] }}</div>
            <div class="pm-stat-sub">Aktif</div>
        </div>
        <div class="pm-stat-card">
            <div class="pm-stat-label">Rata-rata Feedback</div>
            <div class="pm-stat-value" style="color:#f59e0b;">{{ $stats['rata_feedback'] }}</div>
            <div class="pm-stat-sub">dari 5 bintang</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

        {{-- KIRI --}}
        <div>
            <div class="pm-stat-label" style="font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:10px;">Progress Rekrutmen</div>
            <div class="pm-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                    <span style="font-weight:600;font-size:13px;">Rekrutmen Mentor 2026</span>
                    <span class="badge badge-review">Aktif</span>
                </div>
                <div style="font-size:12px;color:#888;margin-bottom:8px;">Pendaftaran ditutup: 20 Mei 2026</div>
                <div class="pm-progress">
                    <div class="pm-progress-fill" style="width:{{ $progress }}%"></div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:4px;">
                    <span style="font-size:11px;color:#888;">{{ $stats['dalam_seleksi'] + $stats['diterima'] }} dari {{ $stats['total_pendaftar'] }} diproses</span>
                    <span style="font-size:11px;color:#185FA5;font-weight:600;">{{ $progress }}%</span>
                </div>
            </div>

            <div class="pm-stat-label" style="font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:10px;margin-top:4px;">Tahapan Rekrutmen</div>
            <div class="pm-card">
                @foreach($tahapan as $t)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f5f6fa;font-size:12px;">
                        <span style="font-weight:500;color:{{ $t['status']==='selesai'?'#166534':($t['status']==='aktif'?'#92400e':'#aaa') }};display:flex;align-items:center;gap:6px;">
                            @if($t['status']==='selesai') 
                                <i data-lucide="check-circle-2" style="width:14px;height:14px;"></i> 
                            @elseif($t['status']==='aktif') 
                                <i data-lucide="arrow-right-circle" style="width:14px;height:14px;"></i> 
                            @else 
                                <i data-lucide="circle" style="width:14px;height:14px;"></i> 
                            @endif
                            {{ $t['nama'] }}
                        </span>
                        <span style="color:#888;">{{ $t['tanggal'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- KANAN --}}
        <div>
            <div class="pm-stat-label" style="font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:10px;">Pendaftar Terbaru</div>
            <div class="pm-card" style="padding:0 18px;margin-bottom:10px;">
                @forelse($pendaftar_terbaru as $p)
                    <div class="pm-row">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="pm-av pm-av-blue">{{ strtoupper(substr($p->mahasiswa->name,0,2)) }}</div>
                            <div>
                                <div style="font-size:13px;font-weight:500;">{{ $p->mahasiswa->name }}</div>
                                <div style="font-size:11px;color:#888;">Skor: {{ $p->skor_total ?? '—' }} / 100</div>
                            </div>
                        </div>
                        <div style="display:flex;gap:6px;align-items:center;">
                            @php
                                $bc = match($p->status) {
                                    'diterima'=>'badge-success','ditolak'=>'badge-danger',
                                    'review'=>'badge-review','pending'=>'badge-pending', default=>'badge-pending'
                                };
                            @endphp
                            <span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span>
                        </div>
                    </div>
                @empty
                    <div style="padding:20px;text-align:center;font-size:13px;color:#888;">Belum ada pendaftar.</div>
                @endforelse
            </div>

            {{-- QUICK ACTIONS --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <a href="{{ route('dosen.pendaftar') }}" class="pm-btn" style="font-size:12px;text-align:center;text-decoration:none;padding:10px;">Lihat Semua Pendaftar</a>
                <a href="{{ route('dosen.pendaftar') }}?status=pending" class="pm-btn pm-btn-primary" style="font-size:12px;text-align:center;text-decoration:none;padding:10px;">Mulai Seleksi</a>
                <a href="{{ route('dosen.feedback') }}"  class="pm-btn" style="font-size:12px;text-align:center;text-decoration:none;padding:10px;">Rekap Feedback</a>
                <a href="{{ route('dosen.laporan') }}"   class="pm-btn" style="font-size:12px;text-align:center;text-decoration:none;padding:10px;">Unduh Laporan</a>
            </div>
        </div>
    </div>
</div>
<div style="text-align:center;padding:16px;font-size:11px;color:#aaa;border-top:1px solid #e8eaed;margin-top:20px;">
    PRO-MENTOR v1.0 · Jurusan Teknik Informatika dan Komputer · Universitas Negeri Makassar · 2026
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>
