<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRO-MENTOR — Self Assessment</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('mahasiswa.partials.nav')
<div style="padding:20px 24px;max-width:760px;margin:0 auto;">
    <div style="font-size:15px;font-weight:600;margin-bottom:4px;">Self Assessment Calon Mentor PRO-MENTOR</div>
    <div style="font-size:12px;color:#185FA5;margin-bottom:16px;">Isilah penilaian diri secara jujur dan objektif. Hasil self assessment ini akan menjadi bahan refleksi dan pertimbangan tambahan dalam proses seleksi mentor.</div>

    @if($sudahIsi)
        <div style="background:#dcfce7;border:1px solid #86efac;border-radius:8px;padding:14px 16px;margin-bottom:16px;font-size:13px;color:#166534;">
            ✓ Anda sudah mengisi Self Assessment pada {{ auth()->user()->selfAssessment->created_at->format('j F Y') }}. Terima kasih!
        </div>
    @endif

    <form method="POST" action="{{ route('mahasiswa.self-assessment.store') }}">
        @csrf

        @php
        $sections = [
            'A. Kompetensi Akademik' => [
                [
                    'name' => 'q1',
                    'question' => '1. Bagaimana Anda menilai kemampuan akademik Anda secara keseluruhan?',
                    'options' => [
                        '4' => 'Sangat baik — IPK di atas 3.75 dan aktif dalam kegiatan akademik',
                        '3'        => 'Baik — IPK di atas 3.50 dan cukup aktif',
                        '2'       => 'Cukup — IPK di atas 3.00',
                        '1'       => 'Perlu ditingkatkan — IPK di bawah 3.00',
                    ]
                ],
                [
                    'name' => 'q2',
                    'question' => '2. Seberapa mampu Anda menjelaskan materi perkuliahan kepada orang lain?',
                    'options' => [
                        '4' => 'Sangat mampu — selalu berhasil membuat orang lain paham',
                        '3'        => 'Mampu — cukup sering berhasil',
                        '2'       => 'Kadang-kadang mampu',
                        '1'        => 'Masih perlu latihan',
                    ]
                ],
            ],
            'B. Kemampuan Interpersonal' => [
                [
                    'name' => 'q3',
                    'question' => '3. Bagaimana kemampuan Anda dalam berkomunikasi dan berempati dengan orang lain?',
                    'options' => [
                        '4' => 'Sangat baik — mudah membangun hubungan dan memahami perasaan orang lain',
                        '3'        => 'Baik — umumnya mampu berkomunikasi dengan baik',
                        '2'       => 'Cukup — dalam situasi tertentu saja',
                        '1'       => 'Masih perlu pengembangan',
                    ]
                ],
                [
                    'name' => 'q4',
                    'question' => '4. Seberapa siap Anda meluangkan waktu di luar jadwal kuliah untuk mendampingi mentee?',
                    'options' => [
                        '4' => 'Sangat siap — minimal 4 jam per minggu',
                        '3'        => 'Siap — sekitar 2–3 jam per minggu',
                        '2'  => 'Cukup siap — 1–2 jam per minggu',
                        '1' => 'Belum pasti jadwalnya',
                    ]
                ],
            ],
            'C. Kepemimpinan & Motivasi' => [
                [
                    'name' => 'q5',
                    'question' => '5. Apakah Anda pernah memegang peran kepemimpinan dalam organisasi atau kepanitiaan?',
                    'options' => [
                        '4'   => 'Ya, sering — lebih dari 2 kali sebagai ketua/koordinator',
                        '3'   => 'Ya, pernah — 1–2 kali',
                        '2'     => 'Pernah sebagai anggota/panitia',
                        '1'       => 'Belum pernah',
                    ]
                ],
                [
                    'name' => 'q6',
                    'question' => '6. Apa motivasi utama Anda mendaftar sebagai Mentor PRO-MENTOR?',
                    'options' => [
                        '4'        => 'Ingin berbagi ilmu dan membantu mahasiswa baru beradaptasi',
                        '3'     => 'Ingin mengembangkan kemampuan kepemimpinan saya',
                        '2'   => 'Ingin menambah pengalaman organisasi',
                        '1'            => 'Dorongan dari teman atau dosen',
                    ]
                ],
            ],
        ];
        @endphp

        @foreach($sections as $sectionTitle => $questions)
            <div class="pm-card" style="margin-bottom:12px;">
                <div style="font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:14px;">{{ $sectionTitle }}</div>
                @foreach($questions as $q)
                    <div style="background:#f9fafb;border-radius:8px;padding:12px;margin-bottom:10px;">
                        <div style="font-size:13px;font-weight:500;color:#1a1a2e;margin-bottom:10px;">{{ $q['question'] }}</div>
                        @foreach($q['options'] as $val => $label)
                            <label style="display:flex;align-items:flex-start;gap:8px;font-size:12px;color:#555;cursor:pointer;padding:3px 0;line-height:1.5;">
                                <input type="radio" name="{{ $q['name'] }}" value="{{ $val }}"
                                    {{ old($q['name']) == $val ? 'checked' : '' }}
                                    {{ $sudahIsi ? 'disabled' : '' }}
                                    style="width:16px;height:16px;flex-shrink:0;margin-top:1px;accent-color:#185FA5;cursor:pointer;">
                                {{ $label }}
                            </label>
                        @endforeach
                        @error($q['name'])<div style="color:#991b1b;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
        @endforeach

        {{-- SECTION D: REFLEKSI --}}
        <div class="pm-card" style="margin-bottom:16px;">
            <div style="font-size:13px;font-weight:600;margin-bottom:14px;">D. Refleksi Diri</div>
            <div style="margin-bottom:12px;">
                <label class="pm-label">Apa kelebihan terbesar Anda yang mendukung peran sebagai mentor?</label>
                <textarea name="kelebihan" class="pm-input" rows="3"
                    placeholder="Tuliskan kelebihan Anda secara spesifik..."
                    {{ $sudahIsi ? 'disabled' : '' }}>{{ old('kelebihan') }}</textarea>
                @error('kelebihan')<div style="color:#991b1b;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>
            <div style="margin-bottom:12px;">
                <label class="pm-label">Apa kelemahan Anda yang perlu diperbaiki untuk menjadi mentor yang baik?</label>
                <textarea name="kelemahan" class="pm-input" rows="3"
                    placeholder="Tuliskan kelemahan dan rencana perbaikan Anda..."
                    {{ $sudahIsi ? 'disabled' : '' }}>{{ old('kelemahan') }}</textarea>
                @error('kelemahan')<div style="color:#991b1b;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="pm-label">Apa yang akan Anda lakukan jika mentee mengalami kesulitan akademik yang tidak bisa Anda bantu?</label>
                <textarea name="rencana_aksi" class="pm-input" rows="3"
                    placeholder="Tuliskan langkah konkret yang akan Anda ambil..."
                    {{ $sudahIsi ? 'disabled' : '' }}>{{ old('rencana_aksi') }}</textarea>
                @error('rencana_aksi')<div style="color:#991b1b;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div style="font-size:12px;color:#888;">Hasil akan dikirim otomatis ke dosen pengelola sebagai bahan pertimbangan seleksi.</div>
            @if(!$sudahIsi)
                <button type="submit" class="pm-btn pm-btn-primary" style="padding:10px 24px;font-size:14px;font-weight:600;">Kirim Self Assessment</button>
            @else
                <div style="font-size:13px;color:#166534;font-weight:500;">✓ Sudah terkirim</div>
            @endif
        </div>
    </form>
</div>
<div style="text-align:center;padding:16px;font-size:11px;color:#aaa;border-top:1px solid #e8eaed;margin-top:20px;">PRO-MENTOR v1.0 · JTIK UNM · 2026</div>
</body></html>
