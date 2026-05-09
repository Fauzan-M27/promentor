<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRO-MENTOR — Feedback Mentor</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('mahasiswa.partials.nav')
<div style="padding:20px 24px;max-width:760px;margin:0 auto;">
    <div style="font-size:15px;font-weight:600;margin-bottom:4px;">Feedback untuk Mentor Saya</div>
    <div style="font-size:12px;color:#185FA5;margin-bottom:16px;">Berikan penilaian yang jujur dan konstruktif. Feedback Anda sangat membantu peningkatan kualitas program PRO-MENTOR.</div>

    @if(!$mentor)
        <div class="pm-card" style="text-align:center;padding:40px;">
            <div style="font-size:13px;color:#888;">Anda belum memiliki mentor yang dipasangkan. Tunggu pengumuman hasil seleksi.</div>
        </div>
    @else
        {{-- FORM FEEDBACK --}}
        <div class="pm-card" style="margin-bottom:14px;">
            {{-- INFO MENTOR --}}
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;">
                <div class="pm-av pm-av-blue" style="width:48px;height:48px;font-size:16px;flex-shrink:0;">
                    {{ strtoupper(substr($mentor->name,0,2)) }}
                </div>
                <div>
                    <div style="font-size:14px;font-weight:600;">{{ $mentor->name }}</div>
                    <div style="font-size:12px;color:#888;">Mentor Anda · {{ $mentor->prodi }} · Angkatan {{ $mentor->semester ? date('Y') - intval($mentor->semester/2) : '2022' }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('mahasiswa.feedback.store') }}">
                @csrf

                {{-- PENILAIAN BINTANG --}}
                <div style="font-size:13px;font-weight:600;margin-bottom:12px;">Penilaian Bintang per Aspek</div>
                @php
                    $aspects = [
                        ['name'=>'bintang_ketersediaan','label'=>'Ketersediaan & Responsivitas'],
                        ['name'=>'bintang_penjelasan',  'label'=>'Kemampuan Menjelaskan Materi'],
                        ['name'=>'bintang_empati',      'label'=>'Empati & Dukungan Emosional'],
                        ['name'=>'bintang_komitmen',    'label'=>'Komitmen & Kedisiplinan'],
                    ];
                @endphp

                @foreach($aspects as $asp)
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                        <span style="font-size:13px;color:#555;">{{ $asp['label'] }}</span>
                        <div class="star-rating" id="sr-{{ $asp['name'] }}">
                            @for($i=1;$i<=5;$i++)
                                <span class="star-btn" data-name="{{ $asp['name'] }}" data-val="{{ $i }}"
                                    onclick="setStar('{{ $asp['name'] }}', {{ $i }})"
                                    style="font-size:24px;cursor:pointer;color:#d1d5db;transition:color .1s;line-height:1;">★</span>
                            @endfor
                            <input type="hidden" name="{{ $asp['name'] }}" id="inp-{{ $asp['name'] }}" value="{{ old($asp['name'],0) }}">
                        </div>
                    </div>
                    @error($asp['name'])<div style="color:#991b1b;font-size:11px;margin-bottom:6px;">{{ $message }}</div>@enderror
                @endforeach

                {{-- TEKS --}}
                <div style="margin-top:14px;margin-bottom:12px;">
                    <label class="pm-label">Apa yang paling membantu dari sesi mentoring bersama mentor Anda?</label>
                    <textarea name="hal_positif" class="pm-input" rows="3"
                        placeholder="Ceritakan hal positif yang Anda rasakan selama sesi mentoring...">{{ old('hal_positif') }}</textarea>
                    @error('hal_positif')<div style="color:#991b1b;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
                </div>
                <div style="margin-bottom:14px;">
                    <label class="pm-label">Apa yang perlu ditingkatkan oleh mentor Anda?</label>
                    <textarea name="saran" class="pm-input" rows="3"
                        placeholder="Berikan saran yang konstruktif...">{{ old('saran') }}</textarea>
                    @error('saran')<div style="color:#991b1b;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
                </div>

                {{-- REKOMENDASI --}}
                <div style="margin-bottom:18px;">
                    <label class="pm-label">Apakah Anda merekomendasikan mentor ini kepada teman lain?</label>
                    <div style="display:flex;gap:20px;margin-top:6px;">
                        @foreach(['ya'=>'Ya, sangat merekomendasikan','mungkin'=>'Mungkin','tidak'=>'Tidak'] as $val => $lbl)
                            <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="rekomendasi" value="{{ $val }}" {{ old('rekomendasi')===$val?'checked':'' }} style="width:16px;height:16px;flex-shrink:0;accent-color:#185FA5;cursor:pointer;">
                                {{ $lbl }}
                            </label>
                        @endforeach
                    </div>
                    @error('rekomendasi')<div style="color:#991b1b;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
                </div>

                <div style="text-align:right;">
                    <button type="submit" class="pm-btn pm-btn-primary" style="padding:10px 24px;font-size:14px;font-weight:600;">Kirim Feedback</button>
                </div>
            </form>
        </div>
    @endif

    {{-- RIWAYAT FEEDBACK --}}
    @if($riwayat->isNotEmpty())
        <div class="pm-card">
            <div style="font-size:13px;font-weight:600;margin-bottom:12px;">Riwayat Feedback Saya</div>
            @foreach($riwayat as $fb)
                <div style="padding:12px 0;border-bottom:1px solid #f0f0f0;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                        <div style="font-weight:500;font-size:13px;">{{ $fb->mentor->name }}</div>
                        <div style="font-size:11px;color:#aaa;">{{ $fb->created_at->format('j M Y') }}</div>
                    </div>
                    <div style="font-size:15px;margin-bottom:4px;">
                        @for($i=1;$i<=5;$i++)
                            <span style="color:{{ $i<=round($fb->rata_rata)?'#f59e0b':'#d1d5db' }};">★</span>
                        @endfor
                    </div>
                    @if($fb->hal_positif)
                        <div style="font-size:12px;color:#555;line-height:1.6;">{{ $fb->hal_positif }}</div>
                    @endif
                </div>
            @endforeach
    @endif

    {{-- FEEDBACK YANG DITERIMA (KHUSUS MENTOR) --}}
    @if(isset($feedbackSebagaiMentor) && $feedbackSebagaiMentor->isNotEmpty())
        <div class="pm-card" style="margin-top:20px;border-top:4px solid #185FA5;">
            <div style="font-size:14px;font-weight:600;margin-bottom:12px;color:#1a1a2e;display:flex;align-items:center;gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#185FA5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg> 
                Feedback dari Mentee Anda
            </div>
            @foreach($feedbackSebagaiMentor as $fb)
                <div style="padding:14px 0;border-bottom:1px solid #f0f0f0;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                        <div style="font-weight:600;font-size:13px;color:#0f172a;">{{ $fb->mentee->name }}</div>
                        <div style="font-size:11px;color:#64748b;font-weight:500;">{{ $fb->created_at->format('j M Y') }}</div>
                    </div>
                    <div style="font-size:14px;margin-bottom:10px;display:flex;align-items:center;gap:4px;">
                        @for($i=1;$i<=5;$i++)
                            <span style="color:{{ $i<=round($fb->rata_rata)?'#f59e0b':'#d1d5db' }};font-size:16px;line-height:1;">★</span>
                        @endfor
                        <span style="font-size:12px;color:#475569;font-weight:600;margin-left:4px;">{{ number_format($fb->rata_rata,1) }} / 5.0</span>
                    </div>
                    @if($fb->hal_positif)
                        <div style="margin-bottom:8px;background:#f8fafc;padding:10px;border-radius:6px;">
                            <div style="font-size:11px;font-weight:700;color:#16a34a;text-transform:uppercase;margin-bottom:4px;letter-spacing:0.5px;">Hal Positif</div>
                            <div style="font-size:13px;color:#334155;line-height:1.5;">{{ $fb->hal_positif }}</div>
                        </div>
                    @endif
                    @if($fb->saran)
                        <div style="background:#fcf8f3;padding:10px;border-radius:6px;">
                            <div style="font-size:11px;font-weight:700;color:#ca8a04;text-transform:uppercase;margin-bottom:4px;letter-spacing:0.5px;">Saran Peningkatan</div>
                            <div style="font-size:13px;color:#334155;line-height:1.5;">{{ $fb->saran }}</div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

<div style="text-align:center;padding:16px;font-size:11px;color:#aaa;border-top:1px solid #e8eaed;margin-top:20px;">PRO-MENTOR v1.0 · JTIK UNM · 2026</div>

<script>
function setStar(name, val) {
    document.getElementById('inp-' + name).value = val;
    document.querySelectorAll('.star-btn[data-name="' + name + '"]').forEach(function(s) {
        s.style.color = parseInt(s.dataset.val) <= val ? '#f59e0b' : '#d1d5db';
    });
}
// Re-init dari old() value
window.addEventListener('load', function() {
    @foreach($aspects as $asp)
        var v{{ $loop->index }} = parseInt(document.getElementById('inp-{{ $asp['name'] }}').value) || 0;
        if (v{{ $loop->index }} > 0) setStar('{{ $asp['name'] }}', v{{ $loop->index }});
    @endforeach
});
</script>
</body></html>
