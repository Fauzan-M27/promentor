<x-admin-layout>
    <x-slot:pageTitle>Detail Pendaftar Mentor</x-slot:pageTitle>

    <style>
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            width: 200px;
            font-weight: 600;
            color: #64748b;
            font-size: 13px;
        }
        .info-value {
            flex: 1;
            color: #0f172a;
            font-size: 13px;
        }
        .file-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
            background: #fff;
        }
        .file-card:hover {
            border-color: #185FA5;
            background: #f8fafc;
        }
        .file-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: #fee2e2;
            color: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .file-icon.pdf {
            background: #fee2e2;
            color: #dc2626;
        }
        .file-name {
            font-size: 11px;
            color: #64748b;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 300px;
        }
        .section-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 16px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }
        /* Modal PDF Preview */
        .pm-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
        }
        .pm-modal-content {
            background: #fff;
            margin: 2% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 900px;
            height: 88%;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        }
        .pm-modal-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
        }
        
        /* Mobile Responsive */
        @media(max-width:768px) {
            .section-card {
                padding: 16px;
                margin-bottom: 16px;
            }
            .info-row {
                flex-direction: column;
                padding: 10px 0;
                gap: 4px;
            }
            .info-label {
                width: 100%;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .info-value {
                font-size: 14px;
            }
            .file-card {
                flex-wrap: wrap;
                padding: 12px;
                gap: 10px;
            }
            .file-icon {
                width: 40px;
                height: 40px;
            }
            .file-card > div:nth-child(2) {
                flex: 1;
                min-width: 150px;
            }
            .file-card .adm-btn {
                flex: 1;
                justify-content: center;
            }
            .pm-modal-content {
                width: 95%;
                height: 90%;
                margin: 5% auto;
            }
        }
    </style>

    {{-- BACK BUTTON & STATUS --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <a href="{{ route('admin.pendaftar.index') }}" class="adm-btn adm-btn-secondary" style="gap:6px;">
            <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Kembali
        </a>
        <span class="adm-badge adm-badge-{{ $pendaftaran->status === 'diterima' ? 'dosen' : ($pendaftaran->status === 'ditolak' ? 'admin' : 'mahasiswa') }}" style="font-size:13px;padding:6px 12px;">
            {{ ucfirst($pendaftaran->status) }}
        </span>
    </div>

    {{-- DATA MAHASISWA --}}
    <div class="section-card">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:16px;border-bottom:2px solid #f0f0f0;">
            @php
                $mhs = $pendaftaran->mahasiswa;
                $inits = strtoupper(substr($mhs->name ?? 'NA', 0, 2));
            @endphp
            <div style="width:56px;height:56px;border-radius:50%;background:#dbeafe;color:#1e40af;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;flex-shrink:0;">
                {{ $inits }}
            </div>
            <div>
                <div style="font-size:18px;font-weight:600;color:#0f172a;">{{ $mhs->name ?? '—' }}</div>
                <div style="font-size:13px;color:#64748b;">{{ $mhs->nim ?? '—' }}</div>
            </div>
        </div>

        <div class="section-title">
            <i data-lucide="user" style="width:16px;height:16px;color:#185FA5;"></i>
            Informasi Mahasiswa
        </div>
        
        <div class="info-row">
            <div class="info-label">Email</div>
            <div class="info-value">{{ $mhs->email ?? '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Program Studi</div>
            <div class="info-value">{{ $mhs->prodi ?? '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">IPK</div>
            <div class="info-value">{{ $mhs->ipk ? number_format($mhs->ipk, 2) : '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Semester</div>
            <div class="info-value">{{ $mhs->semester ?? '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tahun Ajaran</div>
            <div class="info-value">{{ $pendaftaran->tahun_ajaran }}</div>
        </div>
    </div>

    {{-- DATA PENDAFTARAN --}}
    <div class="section-card">
        <div class="section-title">
            <i data-lucide="clipboard-list" style="width:16px;height:16px;color:#185FA5;"></i>
            Informasi Pendaftaran
        </div>
        
        <div class="info-row">
            <div class="info-label">Pengalaman Organisasi</div>
            <div class="info-value">{{ $pendaftaran->pengalaman_organisasi ?? '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Ketersediaan Waktu</div>
            <div class="info-value">{{ $pendaftaran->ketersediaan_waktu ?? '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Motivation Letter</div>
            <div class="info-value" style="white-space:pre-wrap;">{{ $pendaftaran->motivation_letter ?? '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Skor Total</div>
            <div class="info-value">
                @if($pendaftaran->skor_total)
                    <span style="font-weight:600;color:#185FA5;font-size:16px;">{{ $pendaftaran->skor_total }}/100</span>
                @else
                    <span style="color:#94a3b8;">Belum dinilai</span>
                @endif
            </div>
        </div>
        @if($pendaftaran->catatan_dosen)
            <div class="info-row">
                <div class="info-label">Catatan Dosen</div>
                <div class="info-value" style="white-space:pre-wrap;">{{ $pendaftaran->catatan_dosen }}</div>
            </div>
        @endif
    </div>

    {{-- FILE UPLOAD --}}
    <div class="section-card">
        <div class="section-title">
            <i data-lucide="paperclip" style="width:16px;height:16px;color:#185FA5;"></i>
            File yang Diupload
        </div>

        <div style="display:grid;gap:12px;">
            {{-- MOTIVATION LETTER --}}
            @if($pendaftaran->motivation_letter)
                <div class="file-card">
                    <div class="file-icon pdf">
                        <i data-lucide="pen-tool" style="width:24px;height:24px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;color:#0f172a;font-size:13px;">Motivation Letter</div>
                        <div class="file-name" title="{{ basename($pendaftaran->motivation_letter) }}">{{ basename($pendaftaran->motivation_letter) }}</div>
                    </div>
                    <button type="button" onclick="openPdf('{{ route('admin.pendaftar.preview', ['pendaftaran' => $pendaftaran, 'fileType' => 'motivation_letter']) }}', 'Motivation Letter - {{ $mhs->name }}')" class="adm-btn adm-btn-primary adm-btn-sm" style="gap:5px;">
                        <i data-lucide="eye" style="width:12px;height:12px;"></i> Lihat
                    </button>
                    <a href="{{ route('admin.pendaftar.download', ['pendaftaran' => $pendaftaran, 'fileType' => 'motivation_letter']) }}" class="adm-btn adm-btn-secondary adm-btn-sm" style="gap:5px;">
                        <i data-lucide="download" style="width:12px;height:12px;"></i> Download
                    </a>
                </div>
            @else
                <div class="file-card" style="opacity:0.5;cursor:not-allowed;">
                    <div class="file-icon pdf">
                        <i data-lucide="pen-tool" style="width:24px;height:24px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;color:#0f172a;font-size:13px;">Motivation Letter</div>
                        <div style="font-size:11px;color:#dc2626;">Tidak ada file</div>
                    </div>
                </div>
            @endif

            {{-- KHS --}}
            @if($pendaftaran->khs)
                <div class="file-card">
                    <div class="file-icon pdf">
                        <i data-lucide="file-text" style="width:24px;height:24px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;color:#0f172a;font-size:13px;">Kartu Hasil Studi (KHS)</div>
                        <div class="file-name" title="{{ basename($pendaftaran->khs) }}">{{ basename($pendaftaran->khs) }}</div>
                    </div>
                    <button type="button" onclick="openPdf('{{ route('admin.pendaftar.preview', ['pendaftaran' => $pendaftaran, 'fileType' => 'khs']) }}', 'KHS Terbaru - {{ $mhs->name }}')" class="adm-btn adm-btn-primary adm-btn-sm" style="gap:5px;">
                        <i data-lucide="eye" style="width:12px;height:12px;"></i> Lihat
                    </button>
                    <a href="{{ route('admin.pendaftar.download', ['pendaftaran' => $pendaftaran, 'fileType' => 'khs']) }}" class="adm-btn adm-btn-secondary adm-btn-sm" style="gap:5px;">
                        <i data-lucide="download" style="width:12px;height:12px;"></i> Download
                    </a>
                </div>
            @else
                <div class="file-card" style="opacity:0.5;cursor:not-allowed;">
                    <div class="file-icon pdf">
                        <i data-lucide="file-text" style="width:24px;height:24px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;color:#0f172a;font-size:13px;">Kartu Hasil Studi (KHS)</div>
                        <div style="font-size:11px;color:#dc2626;">Tidak ada file</div>
                    </div>
                </div>
            @endif

            {{-- SERTIFIKAT ORGANISASI --}}
            @if($pendaftaran->sertifikat_organisasi)
                <div class="file-card">
                    <div class="file-icon pdf">
                        <i data-lucide="award" style="width:24px;height:24px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;color:#0f172a;font-size:13px;">Sertifikat Organisasi</div>
                        <div class="file-name" title="{{ basename($pendaftaran->sertifikat_organisasi) }}">{{ basename($pendaftaran->sertifikat_organisasi) }}</div>
                    </div>
                    <button type="button" onclick="openPdf('{{ route('admin.pendaftar.preview', ['pendaftaran' => $pendaftaran, 'fileType' => 'sertifikat_organisasi']) }}', 'Sertifikat Organisasi - {{ $mhs->name }}')" class="adm-btn adm-btn-primary adm-btn-sm" style="gap:5px;">
                        <i data-lucide="eye" style="width:12px;height:12px;"></i> Lihat
                    </button>
                    <a href="{{ route('admin.pendaftar.download', ['pendaftaran' => $pendaftaran, 'fileType' => 'sertifikat_organisasi']) }}" class="adm-btn adm-btn-secondary adm-btn-sm" style="gap:5px;">
                        <i data-lucide="download" style="width:12px;height:12px;"></i> Download
                    </a>
                </div>
            @else
                <div class="file-card" style="opacity:0.5;cursor:not-allowed;">
                    <div class="file-icon pdf">
                        <i data-lucide="award" style="width:24px;height:24px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;color:#0f172a;font-size:13px;">Sertifikat Organisasi</div>
                        <div style="font-size:11px;color:#dc2626;">Tidak ada file</div>
                    </div>
                </div>
            @endif

            {{-- SERTIFIKAT MENTORING --}}
            @if($pendaftaran->sertifikat_mentoring)
                <div class="file-card">
                    <div class="file-icon pdf">
                        <i data-lucide="graduation-cap" style="width:24px;height:24px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;color:#0f172a;font-size:13px;">Sertifikat Mentoring</div>
                        <div class="file-name" title="{{ basename($pendaftaran->sertifikat_mentoring) }}">{{ basename($pendaftaran->sertifikat_mentoring) }}</div>
                    </div>
                    <button type="button" onclick="openPdf('{{ route('admin.pendaftar.preview', ['pendaftaran' => $pendaftaran, 'fileType' => 'sertifikat_mentoring']) }}', 'Sertifikat Mentoring - {{ $mhs->name }}')" class="adm-btn adm-btn-primary adm-btn-sm" style="gap:5px;">
                        <i data-lucide="eye" style="width:12px;height:12px;"></i> Lihat
                    </button>
                    <a href="{{ route('admin.pendaftar.download', ['pendaftaran' => $pendaftaran, 'fileType' => 'sertifikat_mentoring']) }}" class="adm-btn adm-btn-secondary adm-btn-sm" style="gap:5px;">
                        <i data-lucide="download" style="width:12px;height:12px;"></i> Download
                    </a>
                </div>
            @else
                <div class="file-card" style="opacity:0.5;cursor:not-allowed;">
                    <div class="file-icon pdf">
                        <i data-lucide="graduation-cap" style="width:24px;height:24px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;color:#0f172a;font-size:13px;">Sertifikat Mentoring</div>
                        <div style="font-size:11px;color:#94a3b8;">Opsional - Tidak ada file</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- PENILAIAN --}}
    @if($pendaftaran->penilaian)
        <div class="section-card">
            <div class="section-title">
                <i data-lucide="clipboard-check" style="width:16px;height:16px;color:#185FA5;"></i>
                Hasil Penilaian Dosen
            </div>

            @php $penilaian = $pendaftaran->penilaian; @endphp
            
            <div class="info-row">
                <div class="info-label">IPK</div>
                <div class="info-value">{{ $penilaian->skor_ipk ?? '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Pengalaman Organisasi</div>
                <div class="info-value">{{ $penilaian->skor_pengalaman ?? '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Motivation Letter</div>
                <div class="info-value">{{ $penilaian->skor_motivasi ?? '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Ketersediaan Waktu</div>
                <div class="info-value">{{ $penilaian->skor_waktu ?? '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Sertifikat</div>
                <div class="info-value">{{ $penilaian->skor_sertifikat ?? '—' }}</div>
            </div>
        </div>
    @endif

    {{-- SELF ASSESSMENT --}}
    @if($pendaftaran->selfAssessment)
        <div class="section-card">
            <div class="section-title">
                <i data-lucide="user-check" style="width:16px;height:16px;color:#185FA5;"></i>
                Self Assessment
            </div>

            @php $sa = $pendaftaran->selfAssessment; @endphp
            
            <div class="info-row">
                <div class="info-label">Kemampuan Akademik</div>
                <div class="info-value">{{ $sa->q1_kemampuan_akademik ?? '—' }}/4</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kemampuan Menjelaskan</div>
                <div class="info-value">{{ $sa->q2_kemampuan_menjelaskan ?? '—' }}/4</div>
            </div>
            <div class="info-row">
                <div class="info-label">Komunikasi & Empati</div>
                <div class="info-value">{{ $sa->q3_komunikasi_empati ?? '—' }}/4</div>
            </div>
            <div class="info-row">
                <div class="info-label">Ketersediaan Waktu</div>
                <div class="info-value">{{ $sa->q4_ketersediaan_waktu ?? '—' }}/4</div>
            </div>
            <div class="info-row">
                <div class="info-label">Pengalaman Kepemimpinan</div>
                <div class="info-value">{{ $sa->q5_pengalaman_kepemimpinan ?? '—' }}/4</div>
            </div>
            <div class="info-row">
                <div class="info-label">Motivasi</div>
                <div class="info-value">{{ $sa->q6_motivasi ?? '—' }}/4</div>
            </div>
            
            {{-- Refleksi Diri --}}
            @if($sa->kelebihan || $sa->kelemahan || $sa->rencana_jika_kesulitan)
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid #e2e8f0;">
                    <div style="font-size:12px;font-weight:600;color:#64748b;margin-bottom:12px;text-transform:uppercase;">Refleksi Diri</div>
                    
                    @if($sa->kelebihan)
                        <div style="margin-bottom:12px;">
                            <div style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;">Kelebihan:</div>
                            <div style="font-size:12px;color:#64748b;line-height:1.6;background:#f8fafc;padding:10px;border-radius:6px;">{{ $sa->kelebihan }}</div>
                        </div>
                    @endif
                    
                    @if($sa->kelemahan)
                        <div style="margin-bottom:12px;">
                            <div style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;">Kelemahan & Perbaikan:</div>
                            <div style="font-size:12px;color:#64748b;line-height:1.6;background:#f8fafc;padding:10px;border-radius:6px;">{{ $sa->kelemahan }}</div>
                        </div>
                    @endif
                    
                    @if($sa->rencana_jika_kesulitan)
                        <div>
                            <div style="font-size:11px;font-weight:600;color:#475569;margin-bottom:4px;">Rencana Jika Kesulitan:</div>
                            <div style="font-size:12px;color:#64748b;line-height:1.6;background:#f8fafc;padding:10px;border-radius:6px;">{{ $sa->rencana_jika_kesulitan }}</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- MODAL PREVIEW PDF --}}
    <div id="pdfModal" class="pm-modal">
        <div class="pm-modal-content">
            <div class="pm-modal-header">
                <div style="font-weight:600; font-size:14px; color:#0f172a;" id="pdfTitle">Preview Berkas</div>
                <button onclick="closePdf()" style="background:none; border:none; cursor:pointer; color:#94a3b8; transition:color .2s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#94a3b8'">
                    <i data-lucide="x" style="width:20px;height:20px;"></i>
                </button>
            </div>
            <iframe id="pdfFrame" src="" style="width:100%; flex:1; border:none;"></iframe>
        </div>
    </div>

    <script>
        function openPdf(url, title) {
            document.getElementById('pdfFrame').src = url;
            document.getElementById('pdfTitle').textContent = title;
            document.getElementById('pdfModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closePdf() {
            document.getElementById('pdfModal').style.display = 'none';
            document.getElementById('pdfFrame').src = '';
            document.body.style.overflow = 'auto';
        }
        
        window.onclick = function(event) {
            if (event.target == document.getElementById('pdfModal')) {
                closePdf();
            }
        }
    </script>

</x-admin-layout>
