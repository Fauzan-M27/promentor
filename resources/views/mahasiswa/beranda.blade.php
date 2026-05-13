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
            {{-- MODE MENTEE (Default): Tampilkan Mentor Saya — REDESIGN PREMIUM --}}
            <div style="grid-column: span 3; position: relative; border-radius: 16px; overflow: hidden;
                        background: linear-gradient(135deg, #0f172a 0%, #185FA5 50%, #1e40af 100%);
                        border: none; padding: 0;">
                @if($mentor)
                    {{-- Background decorative circles --}}
                    <div style="position:absolute;top:-30px;right:-30px;width:160px;height:160px;
                                border-radius:50%;background:rgba(255,255,255,.06);pointer-events:none;"></div>
                    <div style="position:absolute;bottom:-20px;left:60px;width:100px;height:100px;
                                border-radius:50%;background:rgba(255,255,255,.04);pointer-events:none;"></div>

                    <div style="padding: 20px 24px; display: flex; align-items: center; gap: 20px; position: relative;">
                        {{-- Avatar Mentor --}}
                        <div style="width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,.15);
                                    border:2px solid rgba(255,255,255,.3);display:flex;align-items:center;
                                    justify-content:center;font-size:20px;font-weight:800;color:#fff;
                                    flex-shrink:0;">
                            {{ strtoupper(substr($mentor->name, 0, 2)) }}
                        </div>

                        {{-- Info Mentor --}}
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:10px;font-weight:700;text-transform:uppercase;
                                        letter-spacing:1.5px;color:rgba(255,255,255,.7);margin-bottom:4px;">
                                ✦ Mentor Saya
                            </div>
                            <div style="font-size:20px;font-weight:800;color:#fff;line-height:1.2;">
                                {{ $mentor->name }}
                            </div>
                            <div style="display:flex;gap:14px;flex-wrap:wrap;margin-top:6px;">
                                <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:rgba(255,255,255,.8);">
                                    <i data-lucide="hash" style="width:12px;height:12px;"></i> {{ $mentor->nim }}
                                </span>
                                <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:rgba(255,255,255,.8);">
                                    <i data-lucide="graduation-cap" style="width:12px;height:12px;"></i> Sem. {{ $mentor->semester }}
                                </span>
                                <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:rgba(255,255,255,.8);">
                                    <i data-lucide="phone" style="width:12px;height:12px;"></i> {{ $mentor->no_wa }}
                                </span>
                            </div>
                        </div>

                        {{-- Tombol WA --}}
                        @if($mentor->no_wa)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $mentor->no_wa) }}"
                               target="_blank"
                               style="flex-shrink:0;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);
                                      border-radius:10px;padding:8px 14px;color:#fff;text-decoration:none;
                                      font-size:12px;font-weight:600;display:flex;align-items:center;gap:6px;
                                      transition:background .2s;"
                               onmouseover="this.style.background='rgba(255,255,255,.25)'"
                               onmouseout="this.style.background='rgba(255,255,255,.15)'">
                                <i data-lucide="message-circle" style="width:14px;height:14px;"></i> Hubungi
                            </a>
                        @endif
                    </div>
                @else
                    <div style="padding: 24px; display:flex; align-items:center; gap:16px; position:relative;">
                        <div style="width:52px;height:52px;border-radius:50%;background:rgba(255,255,255,.1);
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i data-lucide="user-x" style="width:24px;height:24px;color:rgba(255,255,255,.5);"></i>
                        </div>
                        <div>
                            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;
                                        color:rgba(255,255,255,.6);margin-bottom:4px;">Mentor Saya</div>
                            <div style="font-size:16px;font-weight:600;color:rgba(255,255,255,.5);">
                                Anda belum dipasangkan dengan mentor
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- CARD FEEDBACK DIKIRIM — REDESIGN PREMIUM --}}
        <div style="position:relative;border-radius:14px;overflow:hidden;
                    background:linear-gradient(145deg,#fff 0%,#f0f9ff 100%);
                    border:1.5px solid #bfdbfe;padding:18px 20px;
                    display:flex;flex-direction:column;justify-content:space-between;">
            {{-- Decorative circle --}}
            <div style="position:absolute;top:-16px;right:-16px;width:80px;height:80px;
                        border-radius:50%;background:linear-gradient(135deg,#dbeafe,#93c5fd);
                        opacity:.4;pointer-events:none;"></div>

            <div style="position:relative;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
                    <div style="width:28px;height:28px;border-radius:8px;
                                background:linear-gradient(135deg,#185FA5,#1e40af);
                                display:flex;align-items:center;justify-content:center;">
                        <i data-lucide="star" style="width:14px;height:14px;color:#fff;fill:#fff;"></i>
                    </div>
                    <span style="font-size:11px;font-weight:700;color:#185FA5;text-transform:uppercase;letter-spacing:.8px;">Feedback</span>
                </div>
                <div style="font-size:36px;font-weight:800;color:#0f172a;line-height:1;">{{ $feedback_count }}</div>
                <div style="font-size:11px;color:#64748b;margin-top:4px;font-weight:500;">Ulasan terkirim</div>
            </div>

            <a href="{{ route('mahasiswa.feedback.create') }}"
               style="margin-top:14px;display:block;text-align:center;background:linear-gradient(135deg,#185FA5,#1e40af);
                      color:#fff;border-radius:8px;padding:7px;font-size:12px;font-weight:600;
                      text-decoration:none;transition:opacity .2s;"
               onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                + Beri Feedback
            </a>
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
