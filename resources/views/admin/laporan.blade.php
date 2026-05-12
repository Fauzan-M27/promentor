<x-admin-layout>
    <x-slot:pageTitle>Laporan & Analitik Rekrutmen</x-slot:pageTitle>

    <style>
        .pm-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 16px; }
        .stat-box { text-align: center; padding: 16px; border-radius: 10px; border: 1px solid transparent; }
        .stat-box-blue { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; }
        .stat-box-green { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
        .stat-box-red { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .stat-box-orange { background: #fff7ed; border-color: #fed7aa; color: #9a3412; }
        .dist-bar-bg { background: #f1f5f9; height: 12px; border-radius: 6px; overflow: hidden; margin-top: 4px; }
        .dist-bar-fill { background: #185FA5; height: 100%; border-radius: 6px; }
    </style>

    {{-- HEADER ACTIONS --}}
    <div style="display:flex;justify-content:flex-end;gap:10px;margin-bottom:20px;" class="adm-stack-mobile">
        <a href="{{ route('admin.laporan.export') }}?type=excel" class="adm-btn adm-btn-secondary">
            <i data-lucide="file-spreadsheet" style="width:16px;height:16px;"></i> Export Excel (.csv)
        </a>
        <a href="{{ route('admin.laporan.export') }}?type=pdf" target="_blank" class="adm-btn adm-btn-primary">
            <i data-lucide="printer" style="width:16px;height:16px;"></i> Cetak Laporan (PDF)
        </a>
    </div>

    <div class="adm-stat-grid-4" style="margin-bottom:24px;">
        <div class="stat-box stat-box-blue">
            <div style="font-size:12px;font-weight:600;text-transform:uppercase;opacity:0.8;">Total Pendaftar</div>
            <div style="font-size:28px;font-weight:800;margin-top:4px;">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-box stat-box-green">
            <div style="font-size:12px;font-weight:600;text-transform:uppercase;opacity:0.8;">Lulus Seleksi</div>
            <div style="font-size:28px;font-weight:800;margin-top:4px;">{{ $stats['diterima'] }}</div>
        </div>
        <div class="stat-box stat-box-red">
            <div style="font-size:12px;font-weight:600;text-transform:uppercase;opacity:0.8;">Tidak Lulus</div>
            <div style="font-size:28px;font-weight:800;margin-top:4px;">{{ $stats['ditolak'] }}</div>
        </div>
        <div class="stat-box stat-box-orange">
            <div style="font-size:12px;font-weight:600;text-transform:uppercase;opacity:0.8;">Rata-rata Skor</div>
            <div style="font-size:28px;font-weight:800;margin-top:4px;">{{ $stats['rata_skor'] }}</div>
        </div>
    </div>

    <div class="adm-grid-resp">
        
        {{-- DISTRIBUSI SKOR --}}
        <div>
            <div style="font-size:13px;font-weight:600;margin-bottom:12px;color:#475569;text-transform:uppercase;letter-spacing:0.5px;">Distribusi Skor Seleksi</div>
            <div class="pm-card">
                @foreach($distribusi as $d)
                    @php $pct = ($d['count'] / $max_dist) * 100; @endphp
                    <div style="margin-bottom:16px;">
                        <div style="display:flex;justify-content:space-between;font-size:13px;font-weight:500;color:#334155;">
                            <span>Skor {{ $d['range'] }}</span>
                            <span>{{ $d['count'] }} Mahasiswa</span>
                        </div>
                        <div class="dist-bar-bg">
                            <div class="dist-bar-fill" style="width:{{ $pct }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- DISTRIBUSI PRODI --}}
        <div>
            <div style="font-size:13px;font-weight:600;margin-bottom:12px;color:#475569;text-transform:uppercase;letter-spacing:0.5px;">Pendaftar per Program Studi</div>
            <div class="pm-card">
                @foreach($prodi_rekap as $p)
                    @php $pct = ($p->total / $total_prodi) * 100; @endphp
                    <div style="margin-bottom:16px;">
                        <div style="display:flex;justify-content:space-between;font-size:13px;font-weight:500;color:#334155;">
                            <span>{{ $p->prodi }}</span>
                            <span>{{ round($pct) }}% ({{ $p->total }})</span>
                        </div>
                        <div class="dist-bar-bg">
                            <div class="dist-bar-fill" style="width:{{ $pct }}%;background:#9333ea;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-admin-layout>
