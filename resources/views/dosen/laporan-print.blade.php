<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Rekrutmen Mentor 2026 — PRO-MENTOR JTIK UNM</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #1a1a2e; background: #fff; padding: 30px 40px; }

        /* HEADER */
        .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #185FA5; padding-bottom: 16px; }
        .header h1 { font-size: 18px; font-weight: 700; color: #1a1a2e; }
        .header h1 span { color: #185FA5; }
        .header .sub { font-size: 13px; color: #555; margin-top: 4px; }
        .header .meta { font-size: 11px; color: #888; margin-top: 6px; }

        /* STAT GRID */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 20px; }
        .stat-card { border: 1px solid #e8eaed; border-radius: 8px; padding: 12px; text-align: center; }
        .stat-label { font-size: 10px; color: #888; margin-bottom: 4px; }
        .stat-value { font-size: 24px; font-weight: 700; color: #1a1a2e; }
        .stat-value.green { color: #166534; }
        .stat-value.red   { color: #991b1b; }

        /* SECTION TITLE */
        .section-title { font-size: 13px; font-weight: 600; color: #1a1a2e; margin: 18px 0 10px; border-left: 3px solid #185FA5; padding-left: 8px; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        thead tr { background: #f0f7ff; }
        th { padding: 8px 10px; text-align: left; font-weight: 600; color: #185FA5; border-bottom: 2px solid #185FA5; }
        td { padding: 7px 10px; border-bottom: 1px solid #f0f0f0; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafbfc; }

        /* BADGE */
        .badge { display: inline-block; font-size: 10px; padding: 2px 8px; border-radius: 12px; font-weight: 500; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-review   { background: #dbeafe; color: #1e40af; }
        .badge-success  { background: #dcfce7; color: #166534; }
        .badge-danger   { background: #fee2e2; color: #991b1b; }

        /* BAR CHART */
        .bar-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
        .bar-label { width: 90px; font-size: 11px; color: #555; }
        .bar-track { flex: 1; height: 14px; background: #e8eaed; border-radius: 4px; overflow: hidden; }
        .bar-fill  { height: 100%; background: #185FA5; border-radius: 4px; }
        .bar-val   { width: 40px; font-size: 11px; color: #185FA5; font-weight: 600; text-align: right; }

        /* FOOTER */
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #aaa; border-top: 1px solid #e8eaed; padding-top: 12px; }

        /* PRINT */
        @media print {
            body { padding: 15px 20px; }
            .no-print { display: none !important; }
            @page { margin: 1.5cm; size: A4; }
        }
    </style>
</head>
<body>

    {{-- TOMBOL PRINT (tidak muncul saat print) --}}
    <div class="no-print" style="text-align:right;margin-bottom:16px;display:flex;gap:8px;justify-content:flex-end;">
        <a href="{{ route('dosen.laporan') }}" style="font-size:12px;padding:7px 14px;border:1px solid #d1d5db;border-radius:6px;text-decoration:none;color:#555;">← Kembali</a>
        <button onclick="window.print()" style="font-size:12px;padding:7px 16px;background:#185FA5;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:600;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    {{-- HEADER --}}
    <div class="header">
        <h1><span>PRO</span>-MENTOR · Sistem Rekrutmen Mentor</h1>
        <div class="sub">Jurusan Teknik Informatika dan Komputer — Universitas Negeri Makassar</div>
        <div class="meta">Laporan Rekrutmen Mentor · Tahun Ajaran 2026 · Dicetak: {{ now()->format('d F Y, H:i') }} WIB</div>
    </div>

    {{-- STAT CARDS --}}
    <div class="section-title">Ringkasan Statistik</div>
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-label">Total Pendaftar</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Diterima</div>
            <div class="stat-value green">{{ $stats['diterima'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Ditolak</div>
            <div class="stat-value red">{{ $stats['ditolak'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Rata-rata Skor</div>
            <div class="stat-value">{{ $stats['rata_skor'] }}</div>
        </div>
    </div>

    {{-- DISTRIBUSI SKOR --}}
    @php
        $distribusi = [
            ['range' => '90–100',       'count' => \App\Models\Pendaftaran::whereBetween('skor_total',[90,100])->count()],
            ['range' => '80–89',        'count' => \App\Models\Pendaftaran::whereBetween('skor_total',[80,89])->count()],
            ['range' => '70–79',        'count' => \App\Models\Pendaftaran::whereBetween('skor_total',[70,79])->count()],
            ['range' => 'Di bawah 70',  'count' => \App\Models\Pendaftaran::where('skor_total','<',70)->whereNotNull('skor_total')->count()],
        ];
        $max = max(array_column($distribusi,'count')) ?: 1;
    @endphp
    <div class="section-title">Distribusi Skor</div>
    @foreach($distribusi as $d)
        <div class="bar-row">
            <div class="bar-label">{{ $d['range'] }}</div>
            <div class="bar-track">
                <div class="bar-fill" style="width:{{ $max>0 ? round($d['count']/$max*100) : 0 }}%"></div>
            </div>
            <div class="bar-val">{{ $d['count'] }}</div>
        </div>
    @endforeach

    {{-- TABEL LENGKAP --}}
    <div class="section-title">Data Seluruh Pendaftar</div>
    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Prodi</th>
                <th style="text-align:center">Semester</th>
                <th style="text-align:center">IPK</th>
                <th style="text-align:center">Skor</th>
                <th style="text-align:center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendaftar as $i => $p)
                @php $bc = match($p->status){'diterima'=>'badge-success','ditolak'=>'badge-danger','review'=>'badge-review',default=>'badge-pending'}; @endphp
                <tr>
                    <td style="color:#888;">{{ $i+1 }}</td>
                    <td style="font-weight:500;">{{ $p->mahasiswa->name }}</td>
                    <td style="color:#888;">{{ $p->mahasiswa->nim ?? '—' }}</td>
                    <td>{{ $p->mahasiswa->prodi ?? '—' }}</td>
                    <td style="text-align:center;">{{ $p->mahasiswa->semester ?? '—' }}</td>
                    <td style="text-align:center;">{{ $p->mahasiswa->ipk ? number_format($p->mahasiswa->ipk,2) : '—' }}</td>
                    <td style="text-align:center;font-weight:600;color:#185FA5;">{{ $p->skor_total ?? '—' }}</td>
                    <td style="text-align:center;"><span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        PRO-MENTOR v1.0 · Jurusan Teknik Informatika dan Komputer · Universitas Negeri Makassar · 2026
        <br>Dokumen ini digenerate otomatis oleh sistem. Sah tanpa tanda tangan.
    </div>

<script>
    // Auto-trigger print setelah halaman load (bisa dikomentari jika tidak perlu)
    // window.addEventListener('load', () => window.print());
</script>
</body>
</html>
