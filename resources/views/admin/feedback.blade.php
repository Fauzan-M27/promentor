<x-admin-layout>
    <x-slot:pageTitle>Rekap Feedback Mentor</x-slot:pageTitle>

    <style>
        .pm-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 16px; }
        .pm-av { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; }
        .pm-av-blue { background: #dbeafe; color: #1e40af; }
        .pm-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .pm-row:last-child { border-bottom: none; }
        .star-filled { color: #f59e0b; fill: #f59e0b; }
        .star-empty { color: #cbd5e1; fill: #cbd5e1; }

        /* Mentor row hover */
        .mentor-row-clickable {
            cursor: pointer;
            transition: background .15s;
            border-radius: 8px;
            padding: 12px 10px;
            margin: 0 -10px;
        }
        .mentor-row-clickable:hover { background: #f0f7ff; }
        .mentor-row-clickable .click-hint {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        /* MODAL */
        .fb-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .fb-modal-overlay.open { display: flex; }
        .fb-modal {
            background: #fff;
            border-radius: 16px;
            width: 100%;
            max-width: 680px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
            animation: slideUp .2s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }
        .fb-modal-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .fb-modal-body {
            overflow-y: auto;
            padding: 20px 24px;
            flex: 1;
        }
        .fb-modal-close {
            background: #f1f5f9;
            border: none;
            border-radius: 8px;
            padding: 6px 10px;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            color: #64748b;
            flex-shrink: 0;
        }
        .fb-modal-close:hover { background: #e2e8f0; }

        /* Summary strip */
        .fb-summary-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .fb-summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
            text-align: center;
        }
        .fb-summary-box .val { font-size: 22px; font-weight: 700; color: #0f172a; }
        .fb-summary-box .lbl { font-size: 11px; color: #64748b; margin-top: 2px; }

        /* Individual feedback card */
        .fb-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 12px;
        }
        .fb-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .fb-stars { display: flex; gap: 2px; }
        .fb-criteria-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            margin-bottom: 10px;
        }
        .fb-criteria-item {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
        }
        .fb-criteria-item .label { color: #64748b; }
        .fb-criteria-item .val {
            font-weight: 700;
            color: #f59e0b;
        }
        .fb-quote {
            font-size: 13px;
            color: #334155;
            font-style: italic;
            line-height: 1.5;
            padding: 8px 12px;
            background: #fff;
            border-left: 3px solid #185FA5;
            border-radius: 0 6px 6px 0;
            margin-bottom: 8px;
        }
        .fb-saran {
            font-size: 12px;
            color: #64748b;
            background: #fefce8;
            border: 1px solid #fde68a;
            border-radius: 6px;
            padding: 6px 10px;
            margin-bottom: 8px;
        }
        .fb-rekomendasi {
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
        }
        .fb-rek-ya  { background: #dcfce7; color: #166534; }
        .fb-rek-tdk { background: #fee2e2; color: #991b1b; }
        .fb-date { font-size: 10px; color: #94a3b8; text-align: right; margin-top: 8px; }

        .fb-loading { text-align:center; padding: 40px 0; color: #64748b; }
        .fb-empty   { text-align:center; padding: 40px 0; color: #94a3b8; font-size:13px; }
    </style>

    {{-- STAT CARDS --}}
    <div class="adm-stat-grid-4" style="margin-bottom:24px;">
        <div class="pm-card" style="padding:18px;">
            <div style="font-size:12px;color:#64748b;font-weight:500;">Total Feedback</div>
            <div style="font-size:24px;font-weight:700;color:#0f172a;margin-top:4px;">{{ $stats['total_feedback'] }}</div>
        </div>
        <div class="pm-card" style="padding:18px;">
            <div style="font-size:12px;color:#64748b;font-weight:500;">Rata-rata Rating</div>
            <div style="font-size:24px;font-weight:700;color:#f59e0b;margin-top:4px;display:flex;align-items:center;gap:6px;">
                {{ $stats['rata_bintang'] }} <i data-lucide="star" class="star-filled" style="width:20px;height:20px;"></i>
            </div>
        </div>
        <div class="pm-card" style="padding:18px;grid-column: span 2;">
            <div style="font-size:12px;color:#64748b;font-weight:500;">Mentor Terbaik</div>
            @if($mentor_terbaik)
                <div style="display:flex;align-items:center;gap:12px;margin-top:4px;">
                    <div class="pm-av pm-av-blue" style="width:36px;height:36px;">{{ strtoupper(substr($mentor_terbaik->name,0,2)) }}</div>
                    <div>
                        <div style="font-size:14px;font-weight:600;color:#0f172a;">{{ $mentor_terbaik->name }}</div>
                        <div style="font-size:12px;color:#166534;font-weight:500;">Skor: {{ $mentor_terbaik->rata_feedback }} / 5.0</div>
                    </div>
                </div>
            @else
                <div style="font-size:14px;color:#64748b;margin-top:4px;">Belum ada data.</div>
            @endif
        </div>
    </div>

    <div class="adm-grid-resp">

        {{-- KIRI: PERFORMA MENTOR --}}
        <div>
            <div style="font-size:13px;font-weight:600;margin-bottom:12px;color:#475569;text-transform:uppercase;letter-spacing:0.5px;">Peringkat Performa Mentor</div>
            <div class="pm-card" style="padding:0 20px;">
                @forelse($mentor_list as $m)
                    <div class="pm-row mentor-row-clickable" onclick="bukaDetailMentor({{ $m->id }}, '{{ addslashes($m->name) }}')" style="border-bottom: 1px solid #f1f5f9;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div class="pm-av pm-av-blue">{{ strtoupper(substr($m->name,0,2)) }}</div>
                            <div>
                                <div style="font-size:14px;font-weight:600;color:#0f172a;">{{ $m->name }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ $m->feedback_diterima_count }} Feedback masuk</div>
                                <div class="click-hint">
                                    <i data-lucide="mouse-pointer-click" style="width:10px;height:10px;"></i> Klik untuk lihat detail
                                </div>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:14px;font-weight:700;color:#0f172a;display:flex;align-items:center;justify-content:flex-end;gap:4px;">
                                {{ $m->rata_feedback }} <i data-lucide="star" class="star-filled" style="width:14px;height:14px;"></i>
                            </div>
                            @php $pct = ($m->rata_feedback / 5) * 100; @endphp
                            <div style="width:80px;height:4px;background:#e2e8f0;border-radius:2px;margin-top:4px;overflow:hidden;">
                                <div style="width:{{ $pct }}%;height:100%;background:#f59e0b;"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding:40px;text-align:center;color:#64748b;">Belum ada data feedback.</div>
                @endforelse
            </div>
        </div>

        {{-- KANAN: KOMENTAR TERBARU --}}
        <div>
            <div style="font-size:13px;font-weight:600;margin-bottom:12px;color:#475569;text-transform:uppercase;letter-spacing:0.5px;">Komentar & Saran Terbaru</div>
            @forelse($komentar_terbaru as $k)
                <div class="pm-card" style="padding:16px;margin-bottom:12px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                        <div style="font-size:11px;font-weight:600;color:#185FA5;">{{ $k->mentee->name }} → {{ $k->mentor->name }}</div>
                        <div style="display:flex;gap:1px;">
                            @for($i=1;$i<=5;$i++)
                                <i data-lucide="star" class="{{ $i <= round($k->rata_rata) ? 'star-filled' : 'star-empty' }}" style="width:10px;height:10px;"></i>
                            @endfor
                        </div>
                    </div>
                    <div style="font-size:13px;color:#334155;font-style:italic;line-height:1.4;">"{{ $k->hal_positif }}"</div>
                    @if($k->saran)
                        <div style="margin-top:8px;padding-top:8px;border-top:1px dashed #e2e8f0;font-size:12px;color:#64748b;">
                            <strong>Saran:</strong> {{ $k->saran }}
                        </div>
                    @endif
                    <div style="margin-top:8px;font-size:10px;color:#94a3b8;text-align:right;">{{ $k->created_at->diffForHumans() }}</div>
                </div>
            @empty
                <div class="pm-card" style="text-align:center;color:#64748b;padding:30px;">Belum ada komentar.</div>
            @endforelse
        </div>

    </div>

    {{-- MODAL DETAIL FEEDBACK MENTOR --}}
    <div class="fb-modal-overlay" id="fbModalOverlay" onclick="tutupModal(event)">
        <div class="fb-modal" id="fbModal">
            <div class="fb-modal-header">
                <div>
                    <div style="font-size:16px;font-weight:700;color:#0f172a;" id="fbModalName">—</div>
                    <div style="font-size:12px;color:#64748b;margin-top:2px;" id="fbModalNim"></div>
                </div>
                <button class="fb-modal-close" onclick="document.getElementById('fbModalOverlay').classList.remove('open')">✕</button>
            </div>
            <div class="fb-modal-body" id="fbModalBody">
                <div class="fb-loading">
                    <i data-lucide="loader-circle" style="width:32px;height:32px;color:#94a3b8;animation:spin 1s linear infinite;"></i>
                    <div style="margin-top:8px;font-size:13px;">Memuat data...</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>

    <script>
        const feedbackDetailUrl = "{{ rtrim(route('admin.feedback', []), '/') }}";

        function bukaDetailMentor(mentorId, mentorName) {
            const overlay = document.getElementById('fbModalOverlay');
            const bodyEl  = document.getElementById('fbModalBody');
            const nameEl  = document.getElementById('fbModalName');
            const nimEl   = document.getElementById('fbModalNim');

            nameEl.textContent = mentorName;
            nimEl.textContent  = '';
            bodyEl.innerHTML   = `<div class="fb-loading"><div style="font-size:13px;color:#64748b;">Memuat data feedback...</div></div>`;
            overlay.classList.add('open');

            fetch(`${feedbackDetailUrl}/${mentorId}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                nimEl.textContent = data.mentor_nim ? `NIM: ${data.mentor_nim}` : '';
                renderModal(data, bodyEl);
                lucide.createIcons();
            })
            .catch(() => {
                bodyEl.innerHTML = `<div class="fb-empty">Gagal memuat data. Coba lagi.</div>`;
            });
        }

        function starHtml(val, max = 5) {
            let s = '';
            for (let i = 1; i <= max; i++) {
                s += `<i data-lucide="star" style="width:12px;height:12px;color:${i <= Math.round(val) ? '#f59e0b' : '#cbd5e1'};fill:${i <= Math.round(val) ? '#f59e0b' : '#cbd5e1'};"></i>`;
            }
            return s;
        }

        function renderModal(data, el) {
            if (data.feedbacks.length === 0) {
                el.innerHTML = `<div class="fb-empty">Belum ada feedback untuk mentor ini.</div>`;
                return;
            }

            let html = `
                <div class="fb-summary-strip">
                    <div class="fb-summary-box">
                        <div class="val">${data.total_feedback}</div>
                        <div class="lbl">Total Feedback</div>
                    </div>
                    <div class="fb-summary-box">
                        <div class="val" style="color:#f59e0b;">${data.rata_feedback} ★</div>
                        <div class="lbl">Rata-rata Rating</div>
                    </div>
                    <div class="fb-summary-box">
                        <div class="val" style="color:#185FA5;">${data.mentor_nim || '—'}</div>
                        <div class="lbl">NIM Mentor</div>
                    </div>
                </div>
                <div style="font-size:12px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.5px;margin-bottom:12px;">
                    Detail Setiap Feedback
                </div>`;

            data.feedbacks.forEach((fb, idx) => {
                const rekClass = fb.rekomendasi === 'ya' ? 'fb-rek-ya' : 'fb-rek-tdk';
                const rekText  = fb.rekomendasi === 'ya' ? '✓ Direkomendasikan' : '✕ Tidak Direkomendasikan';

                html += `
                <div class="fb-item">
                    <div class="fb-item-header">
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#0f172a;">${fb.mentee_name}</div>
                            <div style="font-size:11px;color:#64748b;">NIM: ${fb.mentee_nim}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:16px;font-weight:700;color:#f59e0b;">${fb.rata_rata} ★</div>
                            <div class="fb-stars">${starHtml(fb.rata_rata)}</div>
                        </div>
                    </div>

                    <div class="fb-criteria-grid">
                        <div class="fb-criteria-item">
                            <span class="label">Ketersediaan</span>
                            <span class="val">${fb.bintang_ketersediaan}/5</span>
                        </div>
                        <div class="fb-criteria-item">
                            <span class="label">Penjelasan</span>
                            <span class="val">${fb.bintang_penjelasan}/5</span>
                        </div>
                        <div class="fb-criteria-item">
                            <span class="label">Empati</span>
                            <span class="val">${fb.bintang_empati}/5</span>
                        </div>
                        <div class="fb-criteria-item">
                            <span class="label">Komitmen</span>
                            <span class="val">${fb.bintang_komitmen}/5</span>
                        </div>
                    </div>

                    ${fb.hal_positif ? `<div class="fb-quote">"${fb.hal_positif}"</div>` : ''}
                    ${fb.saran ? `<div class="fb-saran"><strong>Saran:</strong> ${fb.saran}</div>` : ''}

                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;">
                        ${fb.rekomendasi ? `<span class="fb-rekomendasi ${rekClass}">${rekText}</span>` : '<span></span>'}
                        <div class="fb-date">${fb.tanggal}</div>
                    </div>
                </div>`;
            });

            el.innerHTML = html;
        }

        function tutupModal(e) {
            if (e.target === document.getElementById('fbModalOverlay')) {
                document.getElementById('fbModalOverlay').classList.remove('open');
            }
        }

        // Tutup dengan Escape
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') document.getElementById('fbModalOverlay').classList.remove('open');
        });
    </script>
</x-admin-layout>
