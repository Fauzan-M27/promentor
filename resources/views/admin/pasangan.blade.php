<x-admin-layout>
    <x-slot:pageTitle>Pengaturan Pasangan<br>Mentor – Mentee</x-slot:pageTitle>

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
        
        .pm-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 16px; }
        .pm-av { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; }
        .pm-av-blue { background: #dbeafe; color: #1e40af; }
        .pm-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; }
        .pm-label { display: block; font-size: 12px; font-weight: 500; color: #374151; margin-bottom: 5px; }
        .pm-input { width: 100%; font-size: 13px; padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 8px; background: #fff; color: #0f172a; outline: none; transition: border .15s; box-sizing: border-box; }
        .pm-input:focus { border-color: #185FA5; box-shadow: 0 0 0 3px rgba(24,95,165,.1); }
        .pm-btn-primary { background: #185FA5; color: #fff; border: none; border-radius: 8px; padding: 10px 16px; cursor: pointer; transition: background 0.15s; }
        .pm-btn-primary:hover { background: #0c447c; }

        /* REFINED CARD UI */
        .pm-active-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .pm-active-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background: #fcfdfe;
        }
        .pm-info-group {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
            min-width: 0;
        }
        .pm-name-block {
            min-width: 0;
        }
        .pm-mentor-name {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .pm-mentor-sub {
            font-size: 12px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .pm-stats-block {
            width: 140px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border-left: 1px solid #f1f5f9;
            border-right: 1px solid #f1f5f9;
            padding: 0 16px;
            flex-shrink: 0;
        }
        .pm-stats-val {
            font-size: 13px;
            font-weight: 600;
            color: #185FA5;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .pm-stats-lbl {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .pm-action-block {
            width: 40px;
            display: flex;
            justify-content: flex-end;
            flex-shrink: 0;
            color: #cbd5e1;
            transition: color 0.2s;
        }
        .pm-active-card:hover .pm-action-block {
            color: #185FA5;
        }
    </style>

    {{-- 1. NOTIFIKASI BERHASIL --}}
    @if(session('success'))
        <div style="background:#dcfce7;color:#166534;padding:12px 16px;border-radius:10px;font-size:14px;margin-bottom:20px;border:1px solid #bbf7d0;display:flex;align-items:center;gap:10px;box-shadow:0 2px 4px rgba(22,101,52,0.05);">
            <i data-lucide="check-circle" style="width:18px;height:18px;"></i> <strong>Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    {{-- 2. TAMBAH PASANGAN BARU (FORM) --}}
    <div style="margin-bottom:32px;">
        <div style="font-size:13px;font-weight:600;margin-bottom:12px;color:#475569;text-transform:uppercase;letter-spacing:0.5px;">Tambah Pasangan Baru</div>
        <div class="pm-card" style="max-width:800px;">
            <form method="POST" action="{{ route('admin.pasangan.store') }}" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:20px;align-items:end;">
                @csrf
                <div>
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
                <div>
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
                <div>
                    <label class="pm-label">Program Studi</label>
                    <select name="prodi" class="pm-input" required>
                        <option value="">— Pilih Prodi —</option>
                        <option value="PTIK"           {{ old('prodi')==='PTIK'           ?'selected':'' }}>PTIK</option>
                        <option value="Teknik Komputer"{{ old('prodi')==='Teknik Komputer'?'selected':'' }}>Teknik Komputer</option>
                    </select>
                    @error('prodi')<div style="color:#991b1b;font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
                </div>
                <div style="grid-column: span auto;">
                    <button type="submit" class="pm-btn-primary" style="width:100%;padding:10px;font-size:14px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:8px;">
                        <i data-lucide="plus-circle" style="width:18px;height:18px;"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. STATISTIK RINGKASAN --}}
    <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:16px;margin-bottom:32px;" class="adm-stat-grid-4">
        <div class="pm-card" style="padding:16px;text-align:center;margin-bottom:0;">
            <div style="font-size:24px;font-weight:700;color:#1d4ed8;">{{ $semua_pasangan->count() }}</div>
            <div style="font-size:12px;color:#64748b;margin-top:4px;font-weight:500;">Total Pasangan</div>
        </div>
        <div class="pm-card" style="padding:16px;text-align:center;margin-bottom:0;">
            <div style="font-size:24px;font-weight:700;color:#166534;">{{ $mentor_tersedia->count() }}</div>
            <div style="font-size:12px;color:#64748b;margin-top:4px;font-weight:500;">Mentor Aktif</div>
        </div>
        <div class="pm-card" style="padding:16px;text-align:center;margin-bottom:0;">
            <div style="font-size:24px;font-weight:700;color:#185FA5;">{{ $mentee_tersedia->count() }}</div>
            <div style="font-size:12px;color:#64748b;margin-top:4px;font-weight:500;">Mentee Tersedia</div>
        </div>
        <div class="pm-card" style="padding:16px;text-align:center;margin-bottom:0;">
            <div style="font-size:24px;font-weight:700;color:#6366f1;">{{ count($pasangan_grouped) }}</div>
            <div style="font-size:12px;color:#64748b;margin-top:4px;font-weight:500;">Grup Mentor</div>
        </div>
    </div>

    {{-- 4. DAFTAR PASANGAN AKTIF --}}
    <div style="margin-bottom:32px;">
        <div style="font-size:13px;font-weight:600;margin-bottom:12px;color:#475569;text-transform:uppercase;letter-spacing:0.5px;">Pasangan Aktif</div>
        <div>
            @forelse($pasangan_grouped as $mentor_id => $group)
                @php
                    $mentor = $group->first()->mentor;
                    $jumlah = $group->count();
                    $prodi = $group->first()->prodi;
                @endphp
                <div class="pm-active-card" onclick="openModal('modal-{{ $mentor->id }}')">
                    <div class="pm-info-group">
                        <div class="pm-av pm-av-blue" style="width:42px;height:42px;font-size:15px;box-shadow: 0 2px 4px rgba(30,64,175,0.1);">
                            {{ strtoupper(substr($mentor->name,0,2)) }}
                        </div>
                        <div class="pm-name-block">
                            <div class="pm-mentor-name">{{ $mentor->name }}</div>
                            <div class="pm-mentor-sub">
                                <i data-lucide="award" style="width:13px;height:13px;color:#185FA5;"></i>
                                <span>Mentor &bull; {{ $prodi }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="pm-stats-block">
                        <div class="pm-stats-val">
                            <i data-lucide="users" style="width:14px;height:14px;"></i> {{ $jumlah }} mentee
                        </div>
                        <div class="pm-stats-lbl">Klik untuk detail</div>
                    </div>
                    <div class="pm-action-block">
                        <i data-lucide="chevron-right" style="width:20px;height:20px;"></i>
                    </div>
                </div>
            @empty
                <div class="pm-card" style="padding:48px 24px;text-align:center;color:#64748b;display:flex;flex-direction:column;align-items:center;gap:12px;">
                    <i data-lucide="inbox" style="width:40px;height:40px;color:#cbd5e1;"></i>
                    <span style="font-size:14px;">Belum ada pasangan aktif.</span>
                </div>
            @endforelse
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
                            <div style="font-size:11px;font-weight:400;color:#64748b;margin-top:2px;">NIM: {{ $mentor->nim ?? '-' }}</div>
                        </div>
                    </div>
                    <button class="pm-modal-close" onclick="closeModal('modal-{{ $mentor->id }}')">
                        <i data-lucide="x" style="width:20px;height:20px;"></i>
                    </button>
                </div>
                <div class="pm-modal-body">
                    <div style="font-size:12px;font-weight:600;color:#64748b;margin-bottom:16px;text-transform:uppercase;letter-spacing:0.5px;display:flex;align-items:center;gap:6px;">
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
                                    <div style="font-size:12px;color:#64748b;margin-top:2px;">NIM: {{ $ps->mentee->nim ?? '-' }}</div>
                                </div>
                            </div>
                            
                            <div style="display:flex;align-items:center;gap:12px;">
                                <span class="badge-status badge-{{ $ps->status }}">{{ ucfirst($ps->status) }}</span>
                                
                                <form method="POST" action="{{ route('admin.pasangan.status', $ps->id) }}" style="display:flex;align-items:center;gap:6px;background:#f8fafc;padding:4px 8px;border-radius:6px;border:1px solid #e2e8f0;">
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

    <script>
        function openModal(id) {
            const overlay = document.getElementById(id);
            if(overlay) {
                overlay.style.display = 'flex';
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
</x-admin-layout>
