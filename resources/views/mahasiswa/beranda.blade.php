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
            <div class="pm-info-card pm-info-card-mentee pm-mentee-card-responsive">
                @if($mentees->count() > 0)
                    <div class="pm-info-card-content">
                        <span class="pm-info-card-label" style="color: var(--pm-success);">Mentee Saya</span>
                        <div class="pm-mentee-list">
                            @foreach($mentees as $pasangan)
                                <div class="pm-mentee-item" style="min-width: 120px;">
                                    <div style="font-size: 13px; font-weight: 700;">{{ $pasangan->mentee_nama }}</div>
                                    <div style="font-size: 11px; color: var(--pm-blue); font-weight: 600; margin-top: 2px;">
                                        {{ $pasangan->mentee_nim }}
                                        @if($pasangan->mentee_no_telp)
                                            <br><span style="font-size: 10px;">{{ $pasangan->mentee_no_telp }}</span>
                                        @endif
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
        @endif
    </div>

    {{-- STATUS PENDAFTARAN (FULL WIDTH) --}}
    <div style="margin-bottom:16px;">
        <div style="font-size:13px;font-weight:600;margin-bottom:10px;">Status Pendaftaran Saya</div>
        <div class="pm-card">
            @if($pendaftaran)
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
                    <div>
                        <div style="font-weight:600;font-size:15px;color:#0f172a;">{{ $user->name }}</div>
                        <div style="font-size:12px;color:#64748b;margin-top:2px;">{{ $user->nim }} • {{ $user->prodi }}</div>
                    </div>
                    @php $bc=match($pendaftaran->status){'diterima'=>'badge-success','ditolak'=>'badge-danger','review'=>'badge-review',default=>'badge-pending'}; @endphp
                    <span class="badge {{ $bc }}" style="font-size:12px;padding:6px 14px;">{{ ucfirst($pendaftaran->status) }}</span>
                </div>
                
                <div style="background:#f8fafc;border-radius:8px;padding:12px;margin-bottom:14px;border-left:3px solid #185FA5;">
                    <div style="font-size:12px;color:#475569;line-height:1.6;">
                        @if($pendaftaran->status === 'diterima')
                            <strong style="color:#166534;">🎉 Selamat!</strong> Anda telah resmi menjadi <strong>Mentor PRO-MENTOR</strong>. Silakan cek daftar mentee yang dipasangkan dengan Anda di card "Mentee Saya" di atas.
                        @elseif($pendaftaran->status === 'ditolak')
                            <strong style="color:#991b1b;">Mohon maaf,</strong> pendaftaran Anda belum dapat kami terima pada periode ini. Anda dapat mencoba mendaftar kembali pada periode berikutnya.
                        @elseif($pendaftaran->status === 'review')
                            <strong style="color:#1e40af;">Dalam Review</strong> — Berkas Anda sedang dalam tahap penilaian oleh dosen pengelola. Harap menunggu pengumuman hasil seleksi.
                        @else
                            <strong style="color:#92400e;">Menunggu Seleksi</strong> — Berkas Anda sedang dalam antrian untuk diproses oleh tim seleksi.
                        @endif
                    </div>
                </div>
                
                {{-- PROGRESS STEPS --}}
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                    @php
                        $step1 = true; // berkas lengkap selalu true jika sudah daftar
                        $step2 = in_array($pendaftaran->status, ['review','diterima','ditolak']);
                        $step3 = in_array($pendaftaran->status, ['diterima','ditolak']);
                    @endphp
                    <div style="text-align:center;padding:10px 8px;border-radius:8px;font-size:11px;font-weight:600;
                        background:{{ $step1 ? '#dcfce7' : '#f5f6fa' }};color:{{ $step1 ? '#166534' : '#94a3b8' }};
                        border:2px solid {{ $step1 ? '#86efac' : '#e2e8f0' }};position:relative;">
                        @if($step1)
                            <div style="position:absolute;top:-8px;right:-8px;width:20px;height:20px;background:#166534;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <i data-lucide="check" style="width:12px;height:12px;color:#fff;stroke-width:3;"></i>
                            </div>
                        @endif
                        <div style="margin-bottom:4px;">
                            <i data-lucide="file-check" style="width:16px;height:16px;"></i>
                        </div>
                        Berkas Lengkap
                    </div>
                    <div style="text-align:center;padding:10px 8px;border-radius:8px;font-size:11px;font-weight:600;
                        background:{{ $step2 ? '#fef3c7' : '#f5f6fa' }};color:{{ $step2 ? '#92400e' : '#94a3b8' }};
                        border:2px solid {{ $step2 ? '#fde047' : '#e2e8f0' }};position:relative;">
                        @if($step2)
                            <div style="position:absolute;top:-8px;right:-8px;width:20px;height:20px;background:#92400e;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <i data-lucide="check" style="width:12px;height:12px;color:#fff;stroke-width:3;"></i>
                            </div>
                        @endif
                        <div style="margin-bottom:4px;">
                            <i data-lucide="search-check" style="width:16px;height:16px;"></i>
                        </div>
                        Seleksi Aktif
                    </div>
                    <div style="text-align:center;padding:10px 8px;border-radius:8px;font-size:11px;font-weight:600;
                        background:{{ $step3 ? '#dcfce7' : '#f5f6fa' }};color:{{ $step3 ? '#166534' : '#94a3b8' }};
                        border:2px solid {{ $step3 ? '#86efac' : '#e2e8f0' }};position:relative;">
                        @if($step3)
                            <div style="position:absolute;top:-8px;right:-8px;width:20px;height:20px;background:#166534;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <i data-lucide="check" style="width:12px;height:12px;color:#fff;stroke-width:3;"></i>
                            </div>
                        @endif
                        <div style="margin-bottom:4px;">
                            <i data-lucide="megaphone" style="width:16px;height:16px;"></i>
                        </div>
                        Pengumuman
                    </div>
                </div>
                
                {{-- Quick Actions in Card --}}
                @if($pendaftaran->status !== 'diterima')
                    <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
                        @if(!$user->selfAssessment)
                            <a href="{{ route('mahasiswa.self-assessment.create') }}" class="pm-btn pm-btn-primary" style="flex:1;min-width:140px;text-decoration:none;text-align:center;font-size:12px;padding:10px;">
                                <i data-lucide="clipboard-check" style="width:14px;height:14px;"></i> Isi Self Assessment
                            </a>
                        @endif
                        @if($user->selfAssessment)
                            <a href="{{ route('mahasiswa.seleksi') }}" class="pm-btn" style="flex:1;min-width:140px;text-decoration:none;text-align:center;font-size:12px;padding:10px;">
                                <i data-lucide="eye" style="width:14px;height:14px;"></i> Lihat Status Seleksi
                            </a>
                        @endif
                    </div>
                @endif
            @else
                <div style="text-align:center;padding:32px 20px;display:flex;flex-direction:column;align-items:center;">
                    <div style="width:64px;height:64px;border-radius:50%;background:#f0f9ff;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                        <i data-lucide="clipboard-list" style="width:32px;height:32px;color:#185FA5;"></i>
                    </div>
                    <div style="font-size:15px;font-weight:600;color:#0f172a;margin-bottom:6px;">Belum Mendaftar</div>
                    <div style="font-size:13px;color:#64748b;margin-bottom:16px;max-width:400px;">
                        Anda belum mendaftar sebagai calon mentor. Daftarkan diri Anda sekarang untuk menjadi bagian dari PRO-MENTOR!
                    </div>
                    <a href="{{ route('mahasiswa.daftar.create') }}" class="pm-btn pm-btn-primary" style="text-decoration:none;font-size:13px;padding:10px 24px;display:inline-flex;align-items:center;gap:8px;">
                        <i data-lucide="user-plus" style="width:16px;height:16px;"></i> Daftar Jadi Mentor
                    </a>
                </div>
            @endif
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

<style>
    /* Mobile responsive styles for Mentee Card */
    @media (max-width: 768px) {
        /* Make mentee card full width on mobile */
        .pm-mentee-card-responsive {
            grid-column: 1 / -1 !important;
            padding: 16px !important;
        }
        
        /* Enable horizontal scroll for mentee list on mobile */
        .pm-mentee-list {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: rgba(0,0,0,0.2) transparent;
            padding-bottom: 12px;
        }
        
        /* Webkit scrollbar styling for mentee list */
        .pm-mentee-list::-webkit-scrollbar {
            height: 4px;
        }
        
        .pm-mentee-list::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05);
            border-radius: 2px;
        }
        
        .pm-mentee-list::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 2px;
        }
        
        /* Ensure mentee items don't shrink too much */
        .pm-mentee-item {
            min-width: 140px !important;
            padding-right: 16px;
        }
        
        /* Adjust font sizes for mobile */
        .pm-mentee-item > div:first-child {
            font-size: 12px !important;
        }
        
        .pm-mentee-item > div:last-child {
            font-size: 10px !important;
        }
        
        /* Adjust label size on mobile */
        .pm-info-card-label {
            font-size: 10px !important;
        }
        
        /* Make mentor card responsive too */
        .pm-stat-grid > div[style*="grid-column: span 3"] {
            grid-column: 1 / -1 !important;
        }
        
        /* Adjust mentor card padding and layout on mobile */
        .pm-stat-grid > div[style*="linear-gradient"] > div[style*="padding"] {
            padding: 16px !important;
            flex-direction: column;
            align-items: flex-start !important;
            gap: 12px !important;
        }
        
        /* Make mentor info wrap better on mobile */
        .pm-stat-grid > div[style*="linear-gradient"] > div > div[style*="flex:1"] {
            width: 100%;
        }
        
        /* Stack mentor meta info vertically on very small screens */
        @media (max-width: 480px) {
            .pm-stat-grid > div[style*="linear-gradient"] > div > div[style*="flex:1"] > div[style*="display:flex"] {
                flex-direction: column !important;
                gap: 6px !important;
                align-items: flex-start !important;
            }
        }
        
        /* Adjust feedback card on mobile */
        .pm-stat-grid > div[style*="position:relative"][style*="border-radius:14px"] {
            padding: 14px 16px !important;
        }
        
        .pm-stat-grid > div[style*="position:relative"][style*="border-radius:14px"] .pm-stat-value {
            font-size: 28px !important;
        }
    }
</style>

<script>
    lucide.createIcons();
</script>
</body></html>
