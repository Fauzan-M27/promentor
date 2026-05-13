<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRO-MENTOR — Seleksi Calon Mentor</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Responsive styles for mobile */
        @media (max-width: 768px) {
            /* Stats grid: 3 columns → 1 column on mobile */
            .stats-grid-responsive {
                grid-template-columns: 1fr !important;
            }
            
            /* Filter form: stack vertically on mobile */
            .filter-form-responsive {
                flex-direction: column !important;
            }
            
            .filter-form-responsive select,
            .filter-form-responsive input,
            .filter-form-responsive button,
            .filter-form-responsive a {
                width: 100% !important;
                min-width: 100% !important;
            }
            
            /* Table wrapper: enable horizontal scroll */
            .table-wrapper-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table-wrapper-responsive table {
                min-width: 800px; /* Ensure table doesn't shrink too much */
            }
            
            /* Adjust padding for mobile */
            .mobile-padding {
                padding: 16px !important;
            }
            
            /* Stats card: adjust layout for mobile */
            .stat-card-mobile {
                padding: 12px !important;
            }
            
            .stat-card-mobile > div:first-child {
                width: 40px !important;
                height: 40px !important;
            }
            
            .stat-card-mobile > div:first-child i {
                width: 20px !important;
                height: 20px !important;
            }
        }
    </style>
</head>
<body>
@include('dosen.partials.nav')
<div class="mobile-padding" style="padding:20px 24px;max-width:960px;margin:0 auto;">

    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
        <i data-lucide="clipboard-check" style="width:24px;height:24px;color:#185FA5;"></i>
        <div style="font-size:18px;font-weight:600;color:#0f172a;">Seleksi Calon Mentor</div>
    </div>

    {{-- STATS GRID --}}
    <div class="stats-grid-responsive" style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
        <div class="pm-card stat-card-mobile" style="padding:16px;display:flex;align-items:center;gap:16px;">
            <div style="width:48px;height:48px;border-radius:12px;background:#fefce8;color:#ca8a04;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="clock" style="width:24px;height:24px;"></i>
            </div>
            <div>
                <div style="font-size:24px;font-weight:700;color:#0f172a;">{{ $stats['pending'] }}</div>
                <div style="font-size:12px;color:#64748b;font-weight:500;">Perlu Dinilai</div>
            </div>
        </div>
        <div class="pm-card stat-card-mobile" style="padding:16px;display:flex;align-items:center;gap:16px;">
            <div style="width:48px;height:48px;border-radius:12px;background:#dcfce7;color:#16a34a;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="check-circle" style="width:24px;height:24px;"></i>
            </div>
            <div>
                <div style="font-size:24px;font-weight:700;color:#0f172a;">{{ $stats['diterima'] }}</div>
                <div style="font-size:12px;color:#64748b;font-weight:500;">Diterima</div>
            </div>
        </div>
        <div class="pm-card stat-card-mobile" style="padding:16px;display:flex;align-items:center;gap:16px;">
            <div style="width:48px;height:48px;border-radius:12px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="users" style="width:24px;height:24px;"></i>
            </div>
            <div>
                <div style="font-size:24px;font-weight:700;color:#0f172a;">{{ $stats['total'] }}</div>
                <div style="font-size:12px;color:#64748b;font-weight:500;">Total Pendaftar</div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('dosen.pendaftar') }}" class="filter-form-responsive" style="display:flex;gap:10px;margin-bottom:16px;">
        <select name="status" class="pm-input" style="width:auto;min-width:150px;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status')==='pending'  ?'selected':'' }}>Pending</option>
            <option value="review"   {{ request('status')==='review'   ?'selected':'' }}>Review</option>
            <option value="diterima" {{ request('status')==='diterima' ?'selected':'' }}>Diterima</option>
            <option value="ditolak"  {{ request('status')==='ditolak'  ?'selected':'' }}>Ditolak</option>
        </select>
        <select name="prodi" class="pm-input" style="width:auto;min-width:170px;" onchange="this.form.submit()">
            <option value="">Semua Prodi</option>
            <option value="PTIK"            {{ request('prodi')==='PTIK'            ?'selected':'' }}>PTIK</option>
            <option value="Teknik Komputer" {{ request('prodi')==='Teknik Komputer' ?'selected':'' }}>Teknik Komputer</option>
        </select>
        <div style="flex:1;position:relative;">
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama / NIM..." class="pm-input" style="padding-left:34px;">
            <i data-lucide="search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8;width:14px;height:14px;"></i>
        </div>
        <button type="submit" class="pm-btn pm-btn-primary" style="display:flex;align-items:center;gap:6px;">
            Filter
        </button>
        @if(request()->hasAny(['status','prodi','cari']))
            <a href="{{ route('dosen.pendaftar') }}" class="pm-btn" style="display:flex;align-items:center;gap:6px;">
                <i data-lucide="x" style="width:14px;height:14px;"></i> Reset
            </a>
        @endif
    </form>

    {{-- TABEL --}}
    <div class="pm-card" style="padding:0;overflow:hidden;">
        <div class="table-wrapper-responsive">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e8eaed;">
                    <th style="padding:11px 16px;text-align:left;font-size:12px;color:#555;font-weight:600;">Pendaftar</th>
                    <th style="padding:11px 12px;text-align:left;font-size:12px;color:#555;font-weight:600;">Prodi</th>
                    <th style="padding:11px 12px;text-align:center;font-size:12px;color:#555;font-weight:600;">IPK</th>
                    <th style="padding:11px 12px;text-align:center;font-size:12px;color:#555;font-weight:600;">Semester</th>
                    <th style="padding:11px 12px;text-align:center;font-size:12px;color:#555;font-weight:600;">Skor</th>
                    <th style="padding:11px 12px;text-align:center;font-size:12px;color:#555;font-weight:600;">Status</th>
                    <th style="padding:11px 16px;text-align:center;font-size:12px;color:#555;font-weight:600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftar as $p)
                    @php
                        $mhs = $p->mahasiswa;
                        $inits = strtoupper(substr($mhs->name,0,2));
                        $bc = match($p->status){'diterima'=>'badge-success','ditolak'=>'badge-danger','review'=>'badge-review',default=>'badge-pending'};
                        $av = match($p->status){'diterima'=>'pm-av-green','ditolak'=>'pm-av-red','review'=>'pm-av-blue',default=>'pm-av-yellow'};
                    @endphp
                    <tr style="border-bottom:1px solid #f0f0f0;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                        <td style="padding:12px 16px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="pm-av {{ $av }}">{{ $inits }}</div>
                                <div>
                                    <div style="font-weight:500;">{{ $mhs->name }}</div>
                                    <div style="font-size:11px;color:#888;">{{ $mhs->nim ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:12px;color:#555;">{{ $mhs->prodi ?? '—' }}</td>
                        <td style="padding:12px;text-align:center;font-weight:500;">{{ $mhs->ipk ? number_format($mhs->ipk,2) : '—' }}</td>
                        <td style="padding:12px;text-align:center;color:#555;">{{ $mhs->semester ?? '—' }}</td>
                        <td style="padding:12px;text-align:center;font-weight:600;color:#185FA5;">{{ $p->skor_total ? $p->skor_total.'/100' : '—' }}</td>
                        <td style="padding:12px;text-align:center;"><span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span></td>
                        <td style="padding:12px 16px;text-align:center;">
                            @if(in_array($p->status, ['pending', 'review']))
                                <a href="{{ route('dosen.seleksi.show', $p) }}" class="pm-btn pm-btn-primary" style="font-size:11px;padding:6px 14px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;font-weight:600;">
                                    <i data-lucide="clipboard-pen" style="width:13px;height:13px;"></i> Beri Nilai
                                </a>
                            @else
                                <a href="{{ route('dosen.seleksi.show', $p) }}" class="pm-btn" style="font-size:11px;padding:6px 14px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;background:#f8fafc;border:1px solid #e2e8f0;color:#64748b;">
                                    <i data-lucide="eye" style="width:13px;height:13px;"></i> Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;padding:30px;color:#888;">Tidak ada pendaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        @if($pendaftar->hasPages())
            <div style="padding:12px 16px;border-top:1px solid #e8eaed;font-size:12px;color:#888;">
                {{ $pendaftar->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
<div style="text-align:center;padding:16px;font-size:11px;color:#aaa;border-top:1px solid #e8eaed;margin-top:20px;">PRO-MENTOR v1.0 · JTIK UNM · 2026</div>

<script>
    lucide.createIcons();
</script>
</body></html>
