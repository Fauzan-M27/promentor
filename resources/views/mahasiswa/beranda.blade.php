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

<div class="pm-container">

    {{-- STAT CARDS --}}
    <div class="pm-stat-grid" style="margin-bottom:16px;">
        @if($pendaftaran && $pendaftaran->status === 'diterima')
            {{-- MENTEE CARD (Untuk user yang sudah LOLOS jadi mentor) --}}
            <div class="pm-info-card pm-info-card-mentee">
                @if($mentees->count() > 0)
                    <div class="pm-info-card-content">
                        <span class="pm-info-card-label" style="color: var(--pm-success);">Mentee Saya</span>
                        <div class="pm-mentee-list">
                            @foreach($mentees as $m)
                                <div class="pm-mentee-item" style="min-width: 120px;">
                                    <div style="font-size: 13px; font-weight: 700;">{{ $m->name }}</div>
                                    <div style="font-size: 11px; color: var(--pm-blue); font-weight: 600; margin-top: 2px;"> {{ $m->no_wa }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="pm-info-card-content">
                        <span class="pm-info-card-label">Mentee Saya</span>
                        <div class="pm-info-card-title" style="color: #94a3b8; font-size: 16px;">Belum ada mentee yang dipasangkan</div>
                    </div>
                @endif
            </div>
        @elseif($is_registrasi_selesai)
            {{-- MODE PELAMAR: Tampilkan 3 statistik --}}
            <div class="pm-stat-card">
                <div class="pm-stat-label">Status Saya</div>
                @php $warna=match($pendaftaran->status){'diterima'=>'#166534','ditolak'=>'#991b1b','review'=>'#1e40af',default=>'#92400e'}; @endphp
                <div class="pm-stat-value" style="font-size:16px;color:{{ $warna }}">{{ ucfirst($pendaftaran->status) }}</div>
                <div class="pm-stat-sub">Menunggu review</div>
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
        @else
            {{-- MODE MENTEE (Default): Tampilkan Mentor Saya --}}
            <div class="pm-info-card pm-info-card-mentor">
                @if($mentor)
                    <div class="pm-info-card-content">
                        <span class="pm-info-card-label" style="color: var(--pm-blue);">Mentor Saya</span>
                        <div class="pm-info-card-title">{{ $mentor->name }}</div>
                        <div class="pm-info-card-meta">
                            <span><i data-lucide="hash" style="width: 14px; height: 14px;"></i> {{ $mentor->nim }}</span>
                            <span><i data-lucide="graduation-cap" style="width: 14px; height: 14px;"></i> Sem. {{ $mentor->semester }}</span>
                            <span><i data-lucide="phone" style="width: 14px; height: 14px;"></i> {{ $mentor->no_wa }}</span>
                        </div>
                    </div>
                @else
                    <div class="pm-info-card-content">
                        <span class="pm-info-card-label">Mentor Saya</span>
                        <div class="pm-info-card-title" style="color: #94a3b8; font-size: 16px;">Anda belum dipasangkan dengan mentor</div>
                    </div>
                @endif
            </div>
        @endif

        <div class="pm-stat-card">
            <div class="pm-stat-label">Feedback Dikirim</div>
            <div class="pm-stat-value">{{ $feedback_count }}</div>
            <div class="pm-stat-sub">Ulasan aktif</div>
        </div>
    </div>

    {{-- DUA KOLOM --}}
    <div class="pm-grid-2" style="margin-bottom:16px;">

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
                        {{ $pendaftaran->status === 'diterima' ? 'Selamat! Anda telah resmi menjadi mentor PRO-MENTOR.' : 'Berkas sedang dalam tahap seleksi administrasi oleh dosen pengelola.' }}
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
                        <a href="{{ route('mahasiswa.daftar.create') }}" class="pm-btn pm-btn-primary" style="text-decoration:none;font-size:13px;">Daftar Jadi Mentor</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- KANAN: AKSI CEPAT --}}
        <div>
            <div style="font-size:13px;font-weight:600;margin-bottom:10px;">Aksi Cepat</div>
            <div class="pm-grid-2" style="gap:8px;">
                @if($pendaftaran)
                    <a href="{{ route('mahasiswa.self-assessment.create') }}" class="pm-btn" style="padding:14px;font-size:12px;text-align:center;text-decoration:none;display:block;">Self Assessment</a>
                @endif
                <a href="{{ route('mahasiswa.feedback.create') }}"        class="pm-btn" style="padding:14px;font-size:12px;text-align:center;text-decoration:none;display:block;">Beri Feedback</a>
                @if($user->selfAssessment)
                    <a href="{{ route('mahasiswa.seleksi') }}"                class="pm-btn" style="padding:14px;font-size:12px;text-align:center;text-decoration:none;display:block;">Status Seleksi</a>
                @endif
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
