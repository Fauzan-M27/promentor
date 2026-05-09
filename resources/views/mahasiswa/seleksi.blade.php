<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRO-MENTOR — Status Seleksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('mahasiswa.partials.nav')
<div style="padding:20px 24px;max-width:760px;margin:0 auto;">
    <div style="font-size:15px;font-weight:600;margin-bottom:16px;">Status Seleksi Saya</div>

    @if(!$pendaftaran)
        <div class="pm-card" style="text-align:center;padding:40px;display:flex;flex-direction:column;align-items:center;">
            <i data-lucide="clipboard-list" style="width:36px;height:36px;color:#cbd5e1;margin-bottom:12px;"></i>
            <div style="font-size:14px;color:#888;margin-bottom:14px;">Anda belum mendaftarkan diri sebagai calon mentor.</div>
            <a href="{{ route('mahasiswa.daftar.create') }}" class="pm-btn pm-btn-primary" style="text-decoration:none;">Daftar Sekarang</a>
        </div>
    @else
        <div class="pm-card">
            {{-- HEADER --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <div style="font-weight:600;font-size:14px;">{{ $user->name }} — {{ $user->nim }}</div>
                @php
                    $label = match($pendaftaran->status) {
                        'pending'  => 'Menunggu Review',
                        'review'   => 'Sedang Diproses',
                        'diterima' => 'Diterima',
                        'ditolak'  => 'Tidak Diterima',
                        default    => ucfirst($pendaftaran->status)
                    };
                    $bc = match($pendaftaran->status) {
                        'diterima'=>'badge-success','ditolak'=>'badge-danger',
                        'review'=>'badge-review',default=>'badge-pending'
                    };
                @endphp
                <span class="badge {{ $bc }}" style="font-size:12px;padding:4px 12px;">{{ $label }}</span>
            </div>

            @if($penilaian)
                {{-- 4 KRITERIA PROGRESS BAR (2x2 grid) --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                    @foreach([
                        ['key'=>'kompetensi_akademik', 'label'=>'Kompetensi Akademik'],
                        ['key'=>'kemampuan_komunikasi','label'=>'Kemampuan Komunikasi'],
                        ['key'=>'kepemimpinan',        'label'=>'Kepemimpinan'],
                        ['key'=>'integritas_komitmen', 'label'=>'Integritas & Komitmen'],
                    ] as $k)
                        @php $val = $penilaian->{$k['key']}; @endphp
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:5px;">
                                <span style="color:#555;font-weight:500;">{{ $k['label'] }}</span>
                                <span style="color:#888;">{{ $val }}/25</span>
                            </div>
                            <div style="height:8px;background:#e8eaed;border-radius:4px;overflow:hidden;">
                                <div style="height:100%;width:{{ ($val/25)*100 }}%;background:#185FA5;border-radius:4px;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- TOTAL SKOR --}}
                <div style="background:#f0f7ff;border-radius:10px;padding:14px 18px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <div style="font-weight:600;font-size:13px;">Total Skor</div>
                            <div style="font-size:12px;color:#888;margin-top:2px;">
                                Nilai minimum: 70 poin
                            </div>
                        </div>
                        <div style="font-size:36px;font-weight:700;color:#185FA5;">{{ $penilaian->skor_total }}</div>
                    </div>
                    @if($penilaian->skor_total >= 70)
                        <div style="font-size:12px;color:#166534;margin-top:6px;display:flex;align-items:center;gap:4px;">
                            <i data-lucide="check-circle-2" style="width:14px;height:14px;"></i> Memenuhi syarat minimum kelulusan
                        </div>
                    @else
                        <div style="font-size:12px;color:#991b1b;margin-top:6px;display:flex;align-items:center;gap:4px;">
                            <i data-lucide="x-circle" style="width:14px;height:14px;"></i> Belum memenuhi syarat minimum (70)
                        </div>
                    @endif
                </div>

                @if($penilaian->catatan)
                    <div style="margin-top:14px;background:#fefce8;border:1px solid #fde68a;border-radius:8px;padding:12px;">
                        <div style="font-size:12px;font-weight:600;color:#92400e;margin-bottom:6px;display:flex;align-items:center;gap:6px;">
                            <i data-lucide="file-edit" style="width:14px;height:14px;"></i> Catatan Dosen:
                        </div>
                        <div style="font-size:13px;color:#555;">{{ $penilaian->catatan }}</div>
                    </div>
                @endif
            @else
                <div style="text-align:center;padding:24px 0;color:#888;font-size:13px;display:flex;flex-direction:column;align-items:center;">
                    <i data-lucide="hourglass" style="width:32px;height:32px;color:#cbd5e1;margin-bottom:12px;"></i>
                    <div>Pendaftaran Anda sedang dalam antrian penilaian.<br>Silakan tunggu proses seleksi dari dosen pengelola.</div>
                </div>
            @endif
        </div>

        {{-- SELF ASSESSMENT STATUS --}}
        <div class="pm-card" style="margin-top:12px;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:13px;font-weight:600;">Self Assessment</div>
                    <div style="font-size:12px;color:#888;margin-top:2px;">
                        {{ $user->selfAssessment ? 'Sudah diisi pada '.$user->selfAssessment->created_at->format('j M Y') : 'Belum diisi — wajib dilengkapi sebelum pengumuman' }}
                    </div>
                </div>
                @if(!$user->selfAssessment)
                    <a href="{{ route('mahasiswa.self-assessment.create') }}" class="pm-btn pm-btn-primary" style="text-decoration:none;font-size:12px;">Isi Sekarang</a>
                @else
                    <span class="badge badge-success" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="check-circle-2" style="width:12px;height:12px;"></i> Selesai</span>
                @endif
            </div>
        </div>
    @endif
</div>
<div style="text-align:center;padding:16px;font-size:11px;color:#aaa;border-top:1px solid #e8eaed;margin-top:20px;">PRO-MENTOR v1.0 · JTIK UNM · 2026</div>

<script>
    lucide.createIcons();
</script>
</body></html>
