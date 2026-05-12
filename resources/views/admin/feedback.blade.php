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
    </style>

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
                    <div class="pm-row">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div class="pm-av pm-av-blue">{{ strtoupper(substr($m->name,0,2)) }}</div>
                            <div>
                                <div style="font-size:14px;font-weight:600;color:#0f172a;">{{ $m->name }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ $m->feedback_diterima_count }} Feedback masuk</div>
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
</x-admin-layout>
