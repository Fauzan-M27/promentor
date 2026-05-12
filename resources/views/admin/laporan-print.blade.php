<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Rekrutmen Mentor 2026</title>
    <style>
        body { font-family: 'Inter', sans-serif; font-size: 12px; color: #333; margin: 0; padding: 40px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 11px; color: #666; }
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 30px; }
        .stat-box { border: 1px solid #ddd; padding: 15px; text-align: center; }
        .stat-val { font-size: 20px; font-weight: bold; margin-bottom: 5px; }
        .stat-lbl { font-size: 10px; color: #666; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f5f5f5; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .badge-diterima { background: #dcfce7; color: #166534; }
        .badge-ditolak { background: #fee2e2; color: #991b1b; }
        .badge-review { background: #dbeafe; color: #1e40af; }
        .footer { margin-top: 40px; font-size: 10px; color: #999; text-align: center; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>Laporan Hasil Rekrutmen Mentor</h1>
        <p>Program Pro-Mentor JTIK UNM · Tahun Ajaran 2026</p>
        <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <div class="stat-grid">
        <div class="stat-box">
            <div class="stat-val">{{ $stats['total'] }}</div>
            <div class="stat-lbl">Pendaftar</div>
        </div>
        <div class="stat-box">
            <div class="stat-val">{{ $stats['diterima'] }}</div>
            <div class="stat-lbl">Diterima</div>
        </div>
        <div class="stat-box">
            <div class="stat-val">{{ $stats['ditolak'] }}</div>
            <div class="stat-lbl">Ditolak</div>
        </div>
        <div class="stat-box">
            <div class="stat-val">{{ $stats['rata_skor'] }}</div>
            <div class="stat-lbl">Rata Skor</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
                <th>Prodi</th>
                <th>Skor</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendaftar as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $p->mahasiswa->name }}</strong></td>
                    <td>{{ $p->mahasiswa->nim }}</td>
                    <td>{{ $p->mahasiswa->prodi }}</td>
                    <td>{{ $p->skor_total ?? '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $p->status }}">{{ strtoupper($p->status) }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini diterbitkan secara otomatis oleh Sistem PRO-MENTOR JTIK UNM.
    </div>

    <button onclick="window.print()" class="no-print" style="position:fixed;bottom:20px;right:20px;padding:10px 20px;background:#185FA5;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:bold;box-shadow:0 4px 12px rgba(0,0,0,0.2);">
        Cetak Laporan
    </button>
</body>
</html>
