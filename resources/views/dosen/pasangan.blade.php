<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRO-MENTOR — Pasangan Mentor</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .pm-modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.5); z-index: 1000;
            display: none; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.2s ease; padding: 20px;
        }
        .pm-modal-overlay.active { display: flex; opacity: 1; }
        .pm-modal {
            background: #fff; width: 100%; max-width: 600px;
            border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transform: translateY(20px); transition: transform 0.2s ease;
            max-height: 90vh; display: flex; flex-direction: column; overflow: hidden;
        }
        .pm-modal-overlay.active .pm-modal { transform: translateY(0); }
        .pm-modal-header {
            padding: 20px 24px; border-bottom: 1px solid #e2e8f0;
            display: flex; justify-content: space-between; align-items: center;
        }
        .pm-modal-title { font-size: 16px; font-weight: 600; color: #0f172a; display:flex; align-items:center; gap:8px; }
        .pm-modal-close {
            background: none; border: none; cursor: pointer; color: #64748b;
            display: flex; align-items: center; justify-content: center;
            padding: 4px; border-radius: 6px; transition: background 0.15s;
        }
        .pm-modal-close:hover { background: #f1f5f9; color: #0f172a; }
        .pm-modal-body { padding: 24px; overflow-y: auto; background: #f8fafc; }
        .pm-row-clickable { cursor: pointer; transition: all 0.2s; border: 1px solid transparent; }
        .pm-row-clickable:hover { background: #f8fafc; border-color: #e2e8f0; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .mentee-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; }
        .badge-status { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-aktif { background: #dcfce7; color: #166534; }
        .badge-selesai { background: #dbeafe; color: #1e40af; }
        .badge-nonaktif { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
@include('dosen.partials.nav')

<div style="padding:20px 24px;max-width:960px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div style="font-size:18px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;">
            <i data-lucide="users" style="width:20px;height:20px;color:#185FA5;"></i>
            Pengaturan Pasangan Mentor – Mentee
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

        {{-- KIRI: PASANGAN AKTIF --}}
        <div>
            <div style="font-size:13px;font-weight:600;margin-bottom:10px;color:#1a1a2e;">Pasangan Aktif</div>
            <div class="pm-card" style="padding:8px 18px;">
                @if(session('success'))
                    <div style="background:#dcfce7;color:#166534;padding:10px 14px;border-radius:8px;font-size:13px;margin:10px 0;border:1px solid #bbf7d0;display:flex;align-items:center;gap:8px;">
                        <i data-lucide="check-circle" style="width:16px;height:16px;"></i> {{ session('success') }}
                    </div>
                @endif
                
                @forelse($pasangan_grouped as $mentor_id => $group)
                    @php
                        $mentor = $group->first()->mentor;
                        $jumlah = $group->count();
                        $prodi = $group->first()->prodi;
                    @endphp
                    <div class="pm-row pm-row-clickable" onclick="openModal('modal-{{ $mentor->id }}')" style="border-radius:8px;padding:12px;margin:4px 0;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div class="pm-av pm-av-blue" style="width:38px;height:38px;font-size:14px;">{{ strtoupper(substr($mentor->name,0,2)) }}</div>
                            <div>
                                <div style="font-weight:600;font-size:14px;color:#0f172a;">{{ $mentor->name }}</div>
                                <div style="font-size:12px;color:#64748b;margin-top:2px;display:flex;align-items:center;gap:4px;">
                                    <i data-lucide="award" style="width:12px;height:12px;"></i> Mentor &bull; {{ $prodi }} &bull; Angkatan {{ date('Y', strtotime('-'. ($mentor->semester ?? 4) .' month')) }}
                                </div>
                            </div>
                        </div>
                        <div style="text-align:right;display:flex;align-items:center;gap:12px;">
                            <div>
                                <div style="font-size:13px;font-weight:600;color:#185FA5;display:flex;align-items:center;justify-content:flex-end;gap:4px;">
                                    <i data-lucide="users" style="width:14px;height:14px;"></i> {{ $jumlah }} mentee
                                </div>
                                <div style="font-size:11px;color:#94a3b8;margin-top:2px;">Klik untuk detail</div>
                            </div>
                            <i data-lucide="chevron-right" style="width:16px;height:16px;color:#cbd5e1;"></i>
                        </div>
                    </div>
                @empty
                    <div style="padding:32px 24px;text-align:center;color:#64748b;display:flex;flex-direction:column;align-items:center;gap:8px;">
                        <i data-lucide="inbox" style="width:32px;height:32px;color:#cbd5e1;"></i>
                        <span style="font-size:13px;">Belum ada pasangan aktif.</span>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- KANAN: FORM TAMBAH --}}
        <div>
            <div style="font-size:13px;font-weight:600;margin-bottom:10px;color:#1a1a2e;">Tambah Pasangan Baru</div>
            <div class="pm-card">
                <form method="POST" action="{{ route('dosen.pasangan.store') }}">
                    @csrf
                    <div style="margin-bottom:12px;">
                        <label class="pm-label">Pilih Mentor</label>
                        <select name="mentor_id" class="pm-input" required>
                            <option value="">— Pilih Mentor —</option>
                            @foreach($mentor_tersedia as $m)
                                <option value="{{ $m->id }}" {{ old('mentor_id')==$m->id?'selected':'' }}>
                                    {{ $m->name }} ({{ $m->nim }})
                                </option>
                            @endforeach
                        </select>
                        @error('mentor_id')<div style="color:#991b1b;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
                    </div>
                    <div style="margin-bottom:12px;">
                        <label class="pm-label">Pilih Mentee</label>
                        <select name="mentee_id" class="pm-input" required>
                            <option value="">— Pilih Mentee —</option>
                            @foreach($mentee_tersedia as $m)
                                <option value="{{ $m->id }}" {{ old('mentee_id')==$m->id?'selected':'' }}>
                                    {{ $m->name }} ({{ $m->nim }})
                                </option>
                            @endforeach
                        </select>
                        @error('mentee_id')<div style="color:#991b1b;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
                    </div>
                    <div style="margin-bottom:16px;">
                        <label class="pm-label">Program Studi</label>
                        <select name="prodi" class="pm-input" required>
                            <option value="">— Pilih Prodi —</option>
                            <option value="PTIK"           {{ old('prodi')==='PTIK'           ?'selected':'' }}>PTIK</option>
                            <option value="Teknik Komputer"{{ old('prodi')==='Teknik Komputer'?'selected':'' }}>Teknik Komputer</option>
                            <option value="Sistem Informasi"{{ old('prodi')==='Sistem Informasi'?'selected':'' }}>Sistem Informasi</option>
                        </select>
                        @error('prodi')<div style="color:#991b1b;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="pm-btn pm-btn-primary" style="width:100%;padding:11px;font-size:14px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:8px;">
                        <i data-lucide="plus-circle" style="width:16px;height:16px;"></i> Simpan Pasangan
                    </button>
                </form>
            </div>

            {{-- Rekap Cepat --}}
            <div class="pm-card" style="margin-top:10px;padding:14px;">
                <div style="font-size:12px;font-weight:600;color:#666;margin-bottom:8px;">Ringkasan</div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <div style="flex:1;text-align:center;background:#eff6ff;border-radius:10px;padding:12px;border:1px solid #bfdbfe;">
                        <div style="font-size:22px;font-weight:700;color:#1d4ed8;">{{ $semua_pasangan->count() }}</div>
                        <div style="font-size:11px;color:#64748b;margin-top:2px;font-weight:500;">Total Pasangan</div>
                    </div>
                    <div style="flex:1;text-align:center;background:#dcfce7;border-radius:8px;padding:10px;">
                        <div style="font-size:20px;font-weight:700;color:#166534;">{{ $mentor_tersedia->count() }}</div>
                        <div style="font-size:11px;color:#888;">Mentor</div>
                    </div>
                    <div style="flex:1;text-align:center;background:#fef3c7;border-radius:8px;padding:10px;">
                        <div style="font-size:20px;font-weight:700;color:#92400e;">{{ $mentee_tersedia->count() }}</div>
                        <div style="font-size:11px;color:#888;">Mentee Bebas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

{{-- MODALS MENTOR --}}
@foreach($pasangan_grouped as $mentor_id => $group)
    @php
        $mentor = $group->first()->mentor;
    @endphp
    <div class="pm-modal-overlay" id="modal-{{ $mentor->id }}" onclick="if(event.target===this) closeModal('modal-{{ $mentor->id }}')">
        <div class="pm-modal">
            <div class="pm-modal-header">
                <div class="pm-modal-title">
                    <div class="pm-av pm-av-blue" style="width:32px;height:32px;font-size:12px;">{{ strtoupper(substr($mentor->name,0,2)) }}</div>
                    <div>
                        <div style="font-size:15px;">{{ $mentor->name }}</div>
                        <div style="font-size:11px;font-weight:400;color:#64748b;margin-top:2px;">Mentor &bull; NIM: {{ $mentor->nim ?? '-' }}</div>
                    </div>
                </div>
                <button class="pm-modal-close" onclick="closeModal('modal-{{ $mentor->id }}')">
                    <i data-lucide="x" style="width:20px;height:20px;"></i>
                </button>
            </div>
            <div class="pm-modal-body">
                <div style="font-size:12px;font-weight:600;color:#64748b;margin-bottom:12px;text-transform:uppercase;letter-spacing:0.5px;display:flex;align-items:center;gap:6px;">
                    <i data-lucide="users" style="width:14px;height:14px;"></i> Daftar Mentee ({{ $group->count() }})
                </div>
                
                @foreach($group as $ps)
                    <div class="mentee-card">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:36px;height:36px;border-radius:50%;background:#f1f5f9;color:#475569;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;">
                                {{ strtoupper(substr($ps->mentee->name,0,2)) }}
                            </div>
                            <div>
                                <div style="font-size:14px;font-weight:600;color:#0f172a;">{{ $ps->mentee->name }}</div>
                                <div style="font-size:12px;color:#64748b;margin-top:2px;">NIM: {{ $ps->mentee->nim ?? '-' }} &bull; Sem {{ $ps->mentee->semester ?? 1 }}</div>
                            </div>
                        </div>
                        
                        <div style="display:flex;align-items:center;gap:12px;">
                            <span class="badge-status badge-{{ $ps->status }}">{{ ucfirst($ps->status) }}</span>
                            
                            <form method="POST" action="{{ route('dosen.pasangan.status', $ps->id) }}" style="display:flex;align-items:center;gap:6px;background:#f8fafc;padding:4px 8px;border-radius:6px;border:1px solid #e2e8f0;">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" style="border:none;background:transparent;font-size:12px;font-weight:500;color:#334155;outline:none;cursor:pointer;padding-right:4px;">
                                    <option value="aktif" {{ $ps->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="selesai" {{ $ps->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="nonaktif" {{ $ps->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach

<div style="text-align:center;padding:16px;font-size:11px;color:#aaa;border-top:1px solid #e8eaed;margin-top:20px;">PRO-MENTOR v1.0 · JTIK UNM · 2026</div>

<script>
    lucide.createIcons();

    function openModal(id) {
        const overlay = document.getElementById(id);
        if(overlay) {
            overlay.style.display = 'flex';
            // Trigger reflow
            overlay.offsetHeight; 
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeModal(id) {
        const overlay = document.getElementById(id);
        if(overlay) {
            overlay.classList.remove('active');
            setTimeout(() => {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }, 200);
        }
    }
</script>
</body></html>
