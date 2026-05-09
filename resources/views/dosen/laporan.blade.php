<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRO-MENTOR — Laporan Rekrutmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('dosen.partials.nav')

<div style="padding:20px 24px;max-width:960px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <div style="font-size:15px;font-weight:600;">Laporan Rekrutmen Mentor 2026</div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('dosen.laporan.export') }}?type=excel" class="pm-btn" style="font-size:12px;text-decoration:none;display:flex;align-items:center;gap:6px;">
                <i data-lucide="download" style="width:14px;height:14px;"></i> Unduh Excel
            </a>
            <a href="{{ route('dosen.laporan.export') }}?type=pdf" target="_blank" class="pm-btn pm-btn-primary" style="font-size:12px;text-decoration:none;display:flex;align-items:center;gap:6px;">
                <i data-lucide="printer" style="width:14px;height:14px;"></i> Unduh Laporan PDF
            </a>
        </div>
    </div>

    {{-- 4 STAT CARDS --}}
    <div class="pm-stat-grid" style="margin-bottom:16px;">
        <div class="pm-stat-card">
            <div class="pm-stat-label">Total Pendaftar</div>
            <div class="pm-stat-value">{{ $stats['total'] }}</div>
            <div class="pm-stat-sub">TA 2026</div>
        </div>
        <div class="pm-stat-card">
            <div class="pm-stat-label">Diterima</div>
            <div class="pm-stat-value" style="color:#166534;">{{ $stats['diterima'] }}</div>
            <div class="pm-stat-sub">{{ $stats['total'] > 0 ? round($stats['diterima']/$stats['total']*100) : 0 }}% dari total</div>
        </div>
        <div class="pm-stat-card">
            <div class="pm-stat-label">Ditolak</div>
            <div class="pm-stat-value" style="color:#991b1b;">{{ $stats['ditolak'] }}</div>
            <div class="pm-stat-sub">{{ $stats['total'] > 0 ? round($stats['ditolak']/$stats['total']*100) : 0 }}% dari total</div>
        </div>
        <div class="pm-stat-card">
            <div class="pm-stat-label">Rata-rata Skor</div>
            <div class="pm-stat-value">{{ $stats['rata_skor'] }}</div>
            <div class="pm-stat-sub">dari 100 poin</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

        {{-- DISTRIBUSI SKOR --}}
        <div class="pm-card">
            <div style="font-size:13px;font-weight:600;margin-bottom:14px;">Distribusi Skor Pendaftar</div>
            @foreach($distribusi as $d)
                @php $pct = $max_dist > 0 ? round($d['count']/$max_dist*100) : 0; @endphp
                <div style="margin-bottom:12px;">
                    <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                        <span style="color:#555;font-weight:500;">{{ $d['range'] }}</span>
                        <span style="font-weight:600;color:#185FA5;">{{ $d['count'] }} orang</span>
                    </div>
                    <div style="height:8px;background:#e8eaed;border-radius:4px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:#185FA5;border-radius:4px;transition:width .4s;"></div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- REKAP PRODI --}}
        <div class="pm-card">
            <div style="font-size:13px;font-weight:600;margin-bottom:14px;">Rekap per Program Studi</div>
            @forelse($prodi_rekap as $pr)
                @php $pct = $total_prodi > 0 ? round($pr->total/$total_prodi*100) : 0; @endphp
                <div style="margin-bottom:12px;">
                    <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                        <span style="color:#555;font-weight:500;">{{ $pr->prodi ?: 'Tidak Diketahui' }}</span>
                        <span style="font-weight:600;color:#185FA5;">{{ $pr->total }} ({{ $pct }}%)</span>
                    </div>
                    <div style="height:8px;background:#e8eaed;border-radius:4px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:#185FA5;border-radius:4px;transition:width .4s;"></div>
                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:20px;color:#888;font-size:13px;">Belum ada data.</div>
            @endforelse
        </div>
    </div>

    {{-- TABEL REKAP LENGKAP --}}
    <div class="pm-card" style="margin-top:14px;padding:0;overflow:hidden;">
        <div style="padding:14px 16px;border-bottom:1px solid #e8eaed;font-size:13px;font-weight:600;">Rekap Seluruh Pendaftar</div>
        <table style="width:100%;border-collapse:collapse;font-size:12px;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e8eaed;">
                    <th style="padding:10px 16px;text-align:left;color:#555;font-weight:600;">Nama</th>
                    <th style="padding:10px 12px;text-align:left;color:#555;font-weight:600;">NIM</th>
                    <th style="padding:10px 12px;text-align:left;color:#555;font-weight:600;">Prodi</th>
                    <th style="padding:10px 12px;text-align:center;color:#555;font-weight:600;">IPK</th>
                    <th style="padding:10px 12px;text-align:center;color:#555;font-weight:600;">Skor</th>
                    <th style="padding:10px 12px;text-align:center;color:#555;font-weight:600;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Pendaftaran::with('mahasiswa')->where('tahun_ajaran','2026')->get() as $p)
                    @php $bc=match($p->status){'diterima'=>'badge-success','ditolak'=>'badge-danger','review'=>'badge-review',default=>'badge-pending'}; @endphp
                    <tr style="border-bottom:1px solid #f5f6fa;">
                        <td style="padding:9px 16px;font-weight:500;">{{ $p->mahasiswa->name }}</td>
                        <td style="padding:9px 12px;color:#888;">{{ $p->mahasiswa->nim }}</td>
                        <td style="padding:9px 12px;color:#555;">{{ $p->mahasiswa->prodi }}</td>
                        <td style="padding:9px 12px;text-align:center;">{{ $p->mahasiswa->ipk ? number_format($p->mahasiswa->ipk,2) : '—' }}</td>
                        <td style="padding:9px 12px;text-align:center;font-weight:600;color:#185FA5;">{{ $p->skor_total ?? '—' }}</td>
                        <td style="padding:9px 12px;text-align:center;"><span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div style="text-align:center;padding:16px;font-size:11px;color:#aaa;border-top:1px solid #e8eaed;margin-top:20px;">PRO-MENTOR v1.0 · JTIK UNM · 2026</div>

<script>
    lucide.createIcons();
</script>
</body></html>
