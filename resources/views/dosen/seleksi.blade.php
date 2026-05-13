<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRO-MENTOR — Seleksi Kandidat</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .pm-modal {
            display: none; position: fixed; z-index: 1000; left: 0; top: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
        }
        .pm-modal-content {
            background: #fff; margin: 2% auto; padding: 0; border-radius: 12px;
            width: 90%; max-width: 900px; height: 88%; position: relative;
            overflow: hidden; display: flex; flex-direction: column;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        }
        .pm-modal-header {
            padding: 14px 20px; border-bottom: 1px solid #e2e8f0; display: flex;
            justify-content: space-between; align-items: center; background: #f8fafc;
        }
    </style>
</head>
<body>
@include('dosen.partials.nav')

<div style="padding:20px 24px;max-width:720px;margin:0 auto;">

    <div style="margin-bottom:16px;">
        <a href="{{ route('dosen.pendaftar') }}" style="font-size:12px;color:#185FA5;text-decoration:none;display:flex;align-items:center;gap:4px;">
            <i data-lucide="chevron-left" style="width:14px;height:14px;"></i> Kembali ke Daftar Pendaftar
        </a>
    </div>

    <div style="font-size:15px;font-weight:600;margin-bottom:16px;">
        Rubrik Penilaian — {{ $pendaftaran->mahasiswa->name }} ({{ $pendaftaran->mahasiswa->nim }})
    </div>

    {{-- INFO KANDIDAT --}}
    <div class="pm-card" style="display:flex;gap:20px;align-items:center;margin-bottom:14px;">
        <div class="pm-av pm-av-blue" style="width:48px;height:48px;font-size:15px;flex-shrink:0;">
            {{ strtoupper(substr($pendaftaran->mahasiswa->name,0,2)) }}
        </div>
        <div style="flex:1;">
            <div style="font-weight:600;font-size:14px;">{{ $pendaftaran->mahasiswa->name }}</div>
            <div style="font-size:12px;color:#888;margin-top:2px;">
                {{ $pendaftaran->mahasiswa->nim }} · {{ $pendaftaran->mahasiswa->prodi }} · Semester {{ $pendaftaran->mahasiswa->semester }} · IPK {{ number_format($pendaftaran->mahasiswa->ipk,2) }}
            </div>
        </div>
        @php $bc = match($pendaftaran->status){'diterima'=>'badge-success','ditolak'=>'badge-danger','review'=>'badge-review',default=>'badge-pending'}; @endphp
        <span class="badge {{ $bc }}" style="font-size:12px;">{{ ucfirst($pendaftaran->status) }}</span>
    </div>

    {{-- FORM PENILAIAN --}}
    <form method="POST" action="{{ route('dosen.seleksi.store', $pendaftaran) }}" id="rubrikForm">
        @csrf
        <div class="pm-card" style="margin-bottom:12px;">
            {{-- SLIDER GRID --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                @php
                    $criteria = [
                        ['key'=>'kompetensi_akademik',  'label'=>'Kompetensi Akademik',  'desc'=>'Nilai akademik dan penguasaan materi'],
                        ['key'=>'kemampuan_komunikasi', 'label'=>'Kemampuan Komunikasi', 'desc'=>'Kemampuan menyampaikan informasi'],
                        ['key'=>'kepemimpinan',         'label'=>'Kepemimpinan',         'desc'=>'Potensi memimpin dan menginspirasi'],
                        ['key'=>'integritas_komitmen',  'label'=>'Integritas & Komitmen','desc'=>'Dedikasi dan tanggung jawab'],
                    ];
                    $existing = $pendaftaran->penilaian;
                @endphp

                @foreach($criteria as $c)
                    @php $val = $existing ? $existing->{$c['key']} : old($c['key'], 0); @endphp
                    <div>
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                            <div>
                                <div style="font-size:13px;font-weight:500;">{{ $c['label'] }}</div>
                                <div style="font-size:11px;color:#aaa;">{{ $c['desc'] }}</div>
                            </div>
                            <div id="val-{{ $c['key'] }}" style="font-size:20px;font-weight:700;color:#185FA5;min-width:30px;text-align:right;">{{ $val }}</div>
                        </div>
                        <input type="range" name="{{ $c['key'] }}" id="slider-{{ $c['key'] }}"
                            min="0" max="25" value="{{ $val }}" step="1"
                            style="width:100%;accent-color:#185FA5;"
                            oninput="updateSlider('{{ $c['key'] }}', this.value)">
                        <div style="display:flex;justify-content:space-between;font-size:10px;color:#aaa;margin-top:2px;">
                            <span>0</span><span>25</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- TOTAL SKOR --}}
            <div style="background:#f0f7ff;border-radius:10px;padding:14px 16px;margin-top:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <div style="font-size:13px;font-weight:600;">Total Skor</div>
                        <div id="skorKet" style="font-size:12px;color:#166534;margin-top:3px;">
                            @if($existing && $existing->skor_total >= 70)
                                ✓ Memenuhi syarat minimum kelulusan (70)
                            @elseif($existing)
                                ✗ Belum memenuhi syarat minimum (70)
                            @endif
                        </div>
                    </div>
                    <div id="totalSkor" style="font-size:32px;font-weight:700;color:#185FA5;">
                        {{ $existing ? $existing->skor_total : 0 }}
                    </div>
                </div>
                <div class="pm-progress" style="margin-top:10px;">
                    <div id="skorBar" class="pm-progress-fill" style="width:{{ $existing ? $existing->skor_total : 0 }}%;transition:width .3s;"></div>
                </div>
            </div>
        </div>

        {{-- CATATAN --}}
        <div class="pm-card" style="margin-bottom:12px;">
            <div class="pm-label" style="margin-bottom:8px;">Catatan dan Rekomendasi Dosen</div>
            <textarea name="catatan" class="pm-input" rows="4"
                placeholder="Tuliskan catatan atau rekomendasi untuk kandidat ini...">{{ $existing?->catatan }}</textarea>
        </div>

        {{-- TOMBOL SIMPAN PENILAIAN --}}
        <div style="text-align:right;margin-bottom:14px;">
            <button type="submit" class="pm-btn pm-btn-primary">Simpan Penilaian</button>
        </div>
    </form>

    {{-- TOMBOL KEPUTUSAN --}}
    @if($pendaftaran->penilaian || $pendaftaran->skor_total)
        <div style="display:flex;justify-content:flex-end;gap:10px;margin-bottom:30px;">
            <form method="POST" action="{{ route('dosen.seleksi.status', $pendaftaran) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="ditolak">
                <button type="submit" class="pm-btn pm-btn-danger"
                    onclick="return confirm('Yakin menolak kandidat ini?')"
                    style="background:#fee2e2;color:#991b1b;border-color:#fca5a5;padding:9px 20px;font-size:13px;font-weight:600;">
                    Tolak Kandidat
                </button>
            </form>
            <form method="POST" action="{{ route('dosen.seleksi.status', $pendaftaran) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="diterima">
                <button type="submit" class="pm-btn"
                    onclick="return confirm('Terima kandidat ini sebagai mentor?')"
                    style="background:#dcfce7;color:#166534;border-color:#86efac;padding:9px 20px;font-size:13px;font-weight:600;">
                    Terima Kandidat
                </button>
            </form>
        </div>
    @endif

    <div style="border-top:2px dashed #e2e8f0; margin:30px 0;"></div>

    {{-- 1. FORM PENDAFTARAN --}}
    <div class="pm-card" style="margin-top:20px;">
        <div style="font-size:14px;font-weight:700;margin-bottom:12px;color:#0f172a;display:flex;align-items:center;gap:8px;">
            <i data-lucide="file-text" style="width:18px;height:18px;color:#185FA5;"></i> 1. Formulir Pendaftaran
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:13px;">
            <div>
                <div style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;">Nama Lengkap</div>
                <div style="font-weight:500;margin-top:2px;">{{ $pendaftaran->mahasiswa->name }}</div>
            </div>
            <div>
                <div style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;">NIM</div>
                <div style="font-weight:500;margin-top:2px;">{{ $pendaftaran->mahasiswa->nim }}</div>
            </div>
            <div>
                <div style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;">Program Studi</div>
                <div style="font-weight:500;margin-top:2px;">{{ $pendaftaran->mahasiswa->prodi }}</div>
            </div>
            <div>
                <div style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;">Semester</div>
                <div style="font-weight:500;margin-top:2px;">Semester {{ $pendaftaran->mahasiswa->semester }}</div>
            </div>
            <div>
                <div style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;">IPK Terakhir</div>
                <div style="font-weight:500;margin-top:2px;">{{ number_format($pendaftaran->mahasiswa->ipk, 2) }}</div>
            </div>
            <div>
                <div style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;">No. WhatsApp</div>
                <div style="font-weight:500;margin-top:2px;">{{ $pendaftaran->mahasiswa->no_wa ?? '—' }}</div>
            </div>
            <div style="grid-column:span 2;">
                <div style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;">Email</div>
                <div style="font-weight:500;margin-top:2px;">{{ $pendaftaran->mahasiswa->email }}</div>
            </div>
            <div style="grid-column:span 2;">
                <div style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;">Pengalaman Organisasi</div>
                <div style="margin-top:4px;padding:10px;background:#f8fafc;border-radius:6px;border:1px solid #e2e8f0;line-height:1.5;">
                    {{ $pendaftaran->pengalaman_organisasi ?: 'Tidak ada data.' }}
                </div>
            </div>
            <div>
                <div style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;">Ketersediaan Waktu</div>
                <div style="font-weight:500;margin-top:2px;">{{ $pendaftaran->ketersediaan_waktu }} jam per minggu</div>
            </div>
        </div>
    </div>

    {{-- 2. KHS TERBARU --}}
    <div class="pm-card" style="margin-top:14px;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div style="font-size:14px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px;">
                <i data-lucide="file-badge" style="width:18px;height:18px;color:#185FA5;"></i> 2. KHS Terbaru
            </div>
            <div style="display:flex;gap:8px;">
                <button type="button" onclick="openPdf('{{ asset('storage/'.$pendaftaran->khs) }}', 'KHS Terbaru - {{ $pendaftaran->mahasiswa->name }}')" class="pm-btn" style="padding:6px 12px;font-size:11px;background:#eff6ff;color:#185FA5;border-color:#bfdbfe;">
                    <i data-lucide="eye" style="width:13px;height:13px;margin-right:4px;"></i> Preview
                </button>
                <a href="{{ asset('storage/'.$pendaftaran->khs) }}" download class="pm-btn" style="padding:6px 12px;font-size:11px;">
                    <i data-lucide="download" style="width:13px;height:13px;margin-right:4px;"></i> Download
                </a>
            </div>
        </div>
    </div>

    {{-- 3. ESSAY MOTIVASI --}}
    <div class="pm-card" style="margin-top:14px;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div style="font-size:14px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px;">
                <i data-lucide="pen-tool" style="width:18px;height:18px;color:#185FA5;"></i> 3. Essay Motivasi (Motivation Letter)
            </div>
            <div style="display:flex;gap:8px;">
                <button type="button" onclick="openPdf('{{ asset('storage/'.$pendaftaran->motivation_letter) }}', 'Motivation Letter - {{ $pendaftaran->mahasiswa->name }}')" class="pm-btn" style="padding:6px 12px;font-size:11px;background:#eff6ff;color:#185FA5;border-color:#bfdbfe;">
                    <i data-lucide="eye" style="width:13px;height:13px;margin-right:4px;"></i> Preview
                </button>
                <a href="{{ asset('storage/'.$pendaftaran->motivation_letter) }}" download class="pm-btn" style="padding:6px 12px;font-size:11px;">
                    <i data-lucide="download" style="width:13px;height:13px;margin-right:4px;"></i> Download
                </a>
            </div>
        </div>
    </div>

    {{-- 4. SERTIFIKAT ORGANISASI --}}
    <div class="pm-card" style="margin-top:14px;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div style="font-size:14px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px;">
                <i data-lucide="award" style="width:18px;height:18px;color:#185FA5;"></i> 4. Sertifikat Organisasi
            </div>
            @if($pendaftaran->sertifikat_organisasi)
            <div style="display:flex;gap:8px;">
                <button type="button" onclick="openPdf('{{ asset('storage/'.$pendaftaran->sertifikat_organisasi) }}', 'Sertifikat - {{ $pendaftaran->mahasiswa->name }}')" class="pm-btn" style="padding:6px 12px;font-size:11px;background:#eff6ff;color:#185FA5;border-color:#bfdbfe;">
                    <i data-lucide="eye" style="width:13px;height:13px;margin-right:4px;"></i> Preview
                </button>
                <a href="{{ asset('storage/'.$pendaftaran->sertifikat_organisasi) }}" download class="pm-btn" style="padding:6px 12px;font-size:11px;">
                    <i data-lucide="download" style="width:13px;height:13px;margin-right:4px;"></i> Download
                </a>
            </div>
            @else
            <div style="font-size:12px;color:#888;">Tidak melampirkan sertifikat.</div>
            @endif
        </div>
    </div>

    {{-- 5. SERTIFIKAT MENTORING --}}
    <div class="pm-card" style="margin-top:14px;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div style="font-size:14px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px;">
                <i data-lucide="graduation-cap" style="width:18px;height:18px;color:#185FA5;"></i> 5. Sertifikat Mentoring / Pelatihan
            </div>
            @if($pendaftaran->sertifikat_mentoring)
            <div style="display:flex;gap:8px;">
                <button type="button" onclick="openPdf('{{ asset('storage/'.$pendaftaran->sertifikat_mentoring) }}', 'Sertifikat Mentoring - {{ $pendaftaran->mahasiswa->name }}')" class="pm-btn" style="padding:6px 12px;font-size:11px;background:#eff6ff;color:#185FA5;border-color:#bfdbfe;">
                    <i data-lucide="eye" style="width:13px;height:13px;margin-right:4px;"></i> Preview
                </button>
                <a href="{{ asset('storage/'.$pendaftaran->sertifikat_mentoring) }}" download class="pm-btn" style="padding:6px 12px;font-size:11px;">
                    <i data-lucide="download" style="width:13px;height:13px;margin-right:4px;"></i> Download
                </a>
            </div>
            @else
            <div style="font-size:12px;color:#888;">Tidak melampirkan sertifikat mentoring.</div>
            @endif
        </div>
    </div>

    {{-- 6. SELF ASSESSMENT KANDIDAT --}}
    @php $sa = $pendaftaran->selfAssessment; @endphp
    @if($sa)
    <div class="pm-card" style="margin-top:14px;">
        <div style="font-size:14px;font-weight:700;margin-bottom:14px;color:#0f172a;display:flex;align-items:center;gap:8px;">
            <i data-lucide="user-check" style="width:18px;height:18px;color:#185FA5;"></i> 6. Hasil Self Assessment Kandidat
        </div>
        
        @php
        $questions = [
            'q1_kemampuan_akademik' => [
                'q' => 'Bagaimana Anda menilai kemampuan akademik Anda secara keseluruhan?',
                'options' => [
                    4 => 'Sangat baik — IPK di atas 3.75 and aktif dalam kegiatan akademik',
                    3 => 'Baik — IPK di atas 3.50 dan cukup aktif',
                    2 => 'Cukup — IPK di atas 3.00',
                    1 => 'Perlu ditingkatkan — IPK di bawah 3.00',
                ]
            ],
            'q2_kemampuan_menjelaskan' => [
                'q' => 'Seberapa mampu Anda menjelaskan materi perkuliahan kepada orang lain?',
                'options' => [
                    4 => 'Sangat mampu — selalu berhasil membuat orang lain paham',
                    3 => 'Mampu — cukup sering berhasil',
                    2 => 'Kadang-kadang mampu',
                    1 => 'Masih perlu latihan',
                ]
            ],
            'q3_komunikasi_empati' => [
                'q' => 'Bagaimana kemampuan Anda dalam berkomunikasi dan berempati dengan orang lain?',
                'options' => [
                    4 => 'Sangat baik — mudah membangun hubungan dan memahami perasaan orang lain',
                    3 => 'Baik — umumnya mampu berkomunikasi dengan baik',
                    2 => 'Cukup — dalam situasi tertentu saja',
                    1 => 'Masih perlu pengembangan',
                ]
            ],
            'q4_ketersediaan_waktu' => [
                'q' => 'Seberapa siap Anda meluangkan waktu di luar jadwal kuliah untuk mendampingi mentee?',
                'options' => [
                    4 => 'Sangat siap — minimal 4 jam per minggu',
                    3 => 'Siap — sekitar 2–3 jam per minggu',
                    2 => 'Cukup siap — 1–2 jam per minggu',
                    1 => 'Belum pasti jadwalnya',
                ]
            ],
            'q5_pengalaman_kepemimpinan' => [
                'q' => 'Apakah Anda pernah memegang peran kepemimpinan dalam organisasi atau kepanitiaan?',
                'options' => [
                    4 => 'Ya, sering — lebih dari 2 kali sebagai ketua/koordinator',
                    3 => 'Ya, pernah — 1–2 kali',
                    2 => 'Pernah sebagai anggota/panitia',
                    1 => 'Belum pernah',
                ]
            ],
            'q6_motivasi' => [
                'q' => 'Apa motivasi utama Anda mendaftar sebagai Mentor PRO-MENTOR?',
                'options' => [
                    4 => 'Ingin berbagi ilmu dan membantu mahasiswa baru beradaptasi',
                    3 => 'Ingin mengembangkan kemampuan kepemimpinan saya',
                    2 => 'Ingin menambah pengalaman organisasi',
                    1 => 'Dorongan dari teman atau dosen',
                ]
            ]
        ];
        @endphp

        <div style="font-size:11px;color:#64748b;font-weight:700;margin-bottom:10px;letter-spacing:0.5px;">PILIHAN JAWABAN (SKOR 1-4)</div>
        <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:20px;">
            @foreach($questions as $key => $data)
                @php 
                    $score = $sa->$key; 
                    $answer = $data['options'][$score] ?? 'Tidak menjawab';
                @endphp
                <div style="background:#f8fafc;padding:14px;border-radius:8px;border:1px solid #e2e8f0;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
                        <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $data['q'] }}</div>
                        <div style="font-size:11px;font-weight:700;color:#185FA5;background:#eff6ff;padding:4px 8px;border-radius:12px;white-space:nowrap;margin-left:12px;border:1px solid #bfdbfe;">Skor: {{ $score }}/4</div>
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:8px;">
                        <div style="color:#16a34a;margin-top:2px;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div style="font-size:13px;color:#475569;line-height:1.5;">{{ $answer }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="font-size:11px;color:#64748b;font-weight:700;margin-bottom:10px;letter-spacing:0.5px;">REFLEKSI DIRI KANDIDAT</div>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <div>
                <div style="font-size:12px;font-weight:600;color:#334155;">Kelebihan</div>
                <div style="font-size:13px;color:#475569;background:#f1f5f9;padding:12px;border-radius:8px;margin-top:4px;line-height:1.5;">{{ $sa->kelebihan ?: '—' }}</div>
            </div>
            <div>
                <div style="font-size:12px;font-weight:600;color:#334155;">Kelemahan & Perbaikan</div>
                <div style="font-size:13px;color:#475569;background:#f1f5f9;padding:12px;border-radius:8px;margin-top:4px;line-height:1.5;">{{ $sa->kelemahan ?: '—' }}</div>
            </div>
            <div>
                <div style="font-size:12px;font-weight:600;color:#334155;">Rencana Jika Kesulitan</div>
                <div style="font-size:13px;color:#475569;background:#f1f5f9;padding:12px;border-radius:8px;margin-top:4px;line-height:1.5;">{{ $sa->rencana_jika_kesulitan ?: '—' }}</div>
            </div>
        </div>
    </div>
    @else
    <div class="pm-card" style="margin-top:14px;text-align:center;padding:32px 24px;color:#64748b;font-size:13px;display:flex;flex-direction:column;align-items:center;">
        <i data-lucide="file-warning" style="width:36px;height:36px;margin-bottom:12px;opacity:0.4;"></i>
        <div>Kandidat ini belum mengisi Self Assessment.</div>
    </div>
    @endif
</div>

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

<div style="text-align:center;padding:16px;font-size:11px;color:#aaa;border-top:1px solid #e8eaed;margin-top:20px;">PRO-MENTOR v1.0 · JTIK UNM · 2026</div>

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

function updateSlider(key, val) {
    document.getElementById('val-' + key).textContent = val;
    var total = 0;
    ['kompetensi_akademik','kemampuan_komunikasi','kepemimpinan','integritas_komitmen'].forEach(function(k){
        total += parseInt(document.getElementById('slider-' + k).value) || 0;
    });
    document.getElementById('totalSkor').textContent = total;
    document.getElementById('skorBar').style.width = total + '%';
    var ket = document.getElementById('skorKet');
    if (total >= 70) {
        ket.style.color = '#166534';
        ket.textContent = '✓ Memenuhi syarat minimum kelulusan (70)';
    } else {
        ket.style.color = '#991b1b';
        ket.textContent = '✗ Belum memenuhi syarat minimum (70)';
    }
}
window.addEventListener('load', function(){
    lucide.createIcons();
    var total = 0;
    ['kompetensi_akademik','kemampuan_komunikasi','kepemimpinan','integritas_komitmen'].forEach(function(k){
        total += parseInt(document.getElementById('slider-' + k).value) || 0;
    });
    if (total > 0) {
        document.getElementById('totalSkor').textContent = total;
        document.getElementById('skorBar').style.width = total + '%';
        var ket = document.getElementById('skorKet');
        if (total >= 70) { ket.style.color='#166534'; ket.textContent='✓ Memenuhi syarat minimum kelulusan (70)'; }
        else { ket.style.color='#991b1b'; ket.textContent='✗ Belum memenuhi syarat minimum (70)'; }
    }
});
</script>
</body></html>
