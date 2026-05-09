<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRO-MENTOR — Beranda Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('mahasiswa.partials.nav')

<div style="padding:20px 24px;max-width:960px;margin:0 auto;">

    {{-- STAT CARDS --}}
    <div class="pm-stat-grid" style="margin-bottom:16px;">
        <div class="pm-stat-card">
            <div class="pm-stat-label">Status Saya</div>
            @if($pendaftaran)
                @php $warna=match($pendaftaran->status){'diterima'=>'#166534','ditolak'=>'#991b1b','review'=>'#1e40af',default=>'#92400e'}; @endphp
                <div class="pm-stat-value" style="font-size:16px;color:{{ $warna }}">{{ ucfirst($pendaftaran->status) }}</div>
                <div class="pm-stat-sub">Menunggu review</div>
            @else
                <div class="pm-stat-value" style="font-size:14px;color:#aaa">Belum Daftar</div>
                <div class="pm-stat-sub">Segera daftar</div>
            @endif
        </div>
        <div class="pm-stat-card">
            <div class="pm-stat-label">Skor Saya</div>
            <div class="pm-stat-value">{{ $pendaftaran?->skor_total ?? '—' }}</div>
            <div class="pm-stat-sub">Dari 100 poin</div>
        </div>
        <div class="pm-stat-card">
            <div class="pm-stat-label">Self Assessment</div>
            <div class="pm-stat-value" style="font-size:14px;color:{{ $user->selfAssessment ? '#166534' : '#92400e' }}">
                {{ $user->selfAssessment ? 'Selesai' : 'Belum' }}
            </div>
            <div class="pm-stat-sub">{{ $user->selfAssessment ? $user->selfAssessment->created_at->format('j M Y') : 'Perlu diisi' }}</div>
        </div>
        <div class="pm-stat-card">
            <div class="pm-stat-label">Feedback Dikirim</div>
            <div class="pm-stat-value">{{ $feedback_count }}</div>
            <div class="pm-stat-sub">Ulasan aktif</div>
        </div>
    </div>

    {{-- DUA KOLOM --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">

        {{-- KIRI: STATUS PENDAFTARAN --}}
        <div>
            <div style="font-size:13px;font-weight:600;margin-bottom:10px;">Status Pendaftaran Saya</div>
            <div class="pm-card">
                @if($pendaftaran)
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <span style="font-weight:600;font-size:13px;">{{ $user->name }} · {{ $user->nim }}</span>
                        @php $bc=match($pendaftaran->status){'diterima'=>'badge-success','ditolak'=>'badge-danger','review'=>'badge-review',default=>'badge-pending'}; @endphp
                        <span class="badge {{ $bc }}">{{ ucfirst($pendaftaran->status) }}</span>
                    </div>
                    <div style="font-size:12px;color:#888;margin-bottom:12px;">
                        Berkas sedang dalam tahap seleksi administrasi oleh dosen pengelola.
                    </div>
                    {{-- PROGRESS STEPS --}}
                    <div style="display:flex;gap:6px;">
                        @php
                            $step1 = true; // berkas lengkap selalu true jika sudah daftar
                            $step2 = in_array($pendaftaran->status, ['review','diterima','ditolak']);
                            $step3 = in_array($pendaftaran->status, ['diterima','ditolak']);
                        @endphp
                        <div style="flex:1;text-align:center;padding:6px;border-radius:8px;font-size:11px;font-weight:500;
                            background:{{ $step1 ? '#dcfce7' : '#f5f6fa' }};color:{{ $step1 ? '#166534' : '#aaa' }};">
                            Berkas Lengkap
                        </div>
                        <div style="flex:1;text-align:center;padding:6px;border-radius:8px;font-size:11px;font-weight:500;
                            background:{{ $step2 ? '#fef3c7' : '#f5f6fa' }};color:{{ $step2 ? '#92400e' : '#aaa' }};">
                            Seleksi Aktif
                        </div>
                        <div style="flex:1;text-align:center;padding:6px;border-radius:8px;font-size:11px;font-weight:500;
                            background:{{ $step3 ? '#dcfce7' : '#f5f6fa' }};color:{{ $step3 ? '#166534' : '#aaa' }};">
                            Pengumuman
                        </div>
                    </div>
                @else
                    <div style="text-align:center;padding:20px 0;display:flex;flex-direction:column;align-items:center;">
                        <i data-lucide="clipboard-list" style="width:36px;height:36px;color:#cbd5e1;margin-bottom:12px;"></i>
                        <div style="font-size:13px;color:#888;margin-bottom:12px;">Anda belum mendaftar sebagai calon mentor.</div>
                        <a href="{{ route('mahasiswa.daftar.create') }}" class="pm-btn pm-btn-primary" style="text-decoration:none;font-size:13px;">Daftar Sekarang</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- KANAN: AKSI CEPAT --}}
        <div>
            <div style="font-size:13px;font-weight:600;margin-bottom:10px;">Aksi Cepat</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <a href="{{ route('mahasiswa.self-assessment.create') }}" class="pm-btn" style="padding:14px;font-size:12px;text-align:center;text-decoration:none;display:block;">Self Assessment</a>
                <a href="{{ route('mahasiswa.feedback.create') }}"        class="pm-btn" style="padding:14px;font-size:12px;text-align:center;text-decoration:none;display:block;">Beri Feedback</a>
                <a href="{{ route('mahasiswa.seleksi') }}"                class="pm-btn" style="padding:14px;font-size:12px;text-align:center;text-decoration:none;display:block;">Status Seleksi</a>
                <a href="{{ route('mahasiswa.notifikasi') }}"             class="pm-btn pm-btn-primary" style="padding:14px;font-size:12px;text-align:center;text-decoration:none;display:block;">Notifikasi</a>
            </div>
        </div>
    </div>

    {{-- JADWAL TAHAPAN --}}
    <div>
        <div style="font-size:13px;font-weight:600;margin-bottom:10px;">Jadwal Tahapan</div>
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
</div>

<div style="text-align:center;padding:16px;font-size:11px;color:#aaa;border-top:1px solid #e8eaed;margin-top:20px;">PRO-MENTOR v1.0 · JTIK UNM · 2026</div>

<script>
    lucide.createIcons();
</script>
</body></html>
