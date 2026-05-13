<x-admin-layout>
    <x-slot:pageTitle>Data Pendaftar Mentor</x-slot:pageTitle>

    {{-- STATS GRID --}}
    <div class="adm-stat-grid" style="grid-template-columns:repeat(5,1fr);margin-bottom:24px;">
        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#eff6ff;">
                <i data-lucide="users" style="color:#2563eb;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['total'] }}</div>
            <div class="adm-stat-lbl">Total</div>
        </div>
        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#fefce8;">
                <i data-lucide="clock" style="color:#ca8a04;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['pending'] }}</div>
            <div class="adm-stat-lbl">Pending</div>
        </div>
        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#dbeafe;">
                <i data-lucide="file-search" style="color:#1d4ed8;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['review'] }}</div>
            <div class="adm-stat-lbl">Review</div>
        </div>
        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#dcfce7;">
                <i data-lucide="check-circle" style="color:#16a34a;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['diterima'] }}</div>
            <div class="adm-stat-lbl">Diterima</div>
        </div>
        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#fee2e2;">
                <i data-lucide="x-circle" style="color:#dc2626;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['ditolak'] }}</div>
            <div class="adm-stat-lbl">Ditolak</div>
        </div>
    </div>

    {{-- FILTER --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;gap:12px;flex-wrap:wrap;">
        <form method="GET" action="{{ route('admin.pendaftar.index') }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
            <select name="tahun_ajaran" class="adm-input adm-select" style="width:auto;min-width:150px;" onchange="this.form.submit()">
                <option value="">Pilih Tahun Ajaran</option>
                @foreach($tahunAjaranList as $ta)
                    <option value="{{ $ta }}" {{ request('tahun_ajaran', '2026') == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                @endforeach
            </select>
            <select name="status" class="adm-input adm-select" style="width:auto;min-width:150px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                <option value="review"   {{ request('status') === 'review'   ? 'selected' : '' }}>Review</option>
                <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="ditolak"  {{ request('status') === 'ditolak'  ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="prodi" class="adm-input adm-select" style="width:auto;min-width:170px;" onchange="this.form.submit()">
                <option value="">Semua Prodi</option>
                <option value="PTIK"            {{ request('prodi') === 'PTIK'            ? 'selected' : '' }}>PTIK</option>
                <option value="Teknik Komputer" {{ request('prodi') === 'Teknik Komputer' ? 'selected' : '' }}>Teknik Komputer</option>
            </select>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama / NIM..." class="adm-input" style="width:200px;">
            <button type="submit" class="adm-btn adm-btn-primary" style="gap:6px;">
                <i data-lucide="filter" style="width:14px;height:14px;"></i> Filter
            </button>
            @if(request()->hasAny(['status','prodi','cari','tahun_ajaran']))
                <a href="{{ route('admin.pendaftar.index') }}" class="adm-btn adm-btn-secondary" style="gap:6px;">
                    <i data-lucide="x" style="width:14px;height:14px;"></i> Reset
                </a>
            @endif
        </form>
    </div>

    {{-- TABEL --}}
    <div class="adm-table-wrap">
        <div class="adm-table-head">
            <h3>Daftar Pendaftar <span style="font-weight:400;color:#64748b;">({{ $pendaftar->total() }} total)</span></h3>
        </div>
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Pendaftar</th>
                    <th>Prodi</th>
                    <th style="text-align:center;">IPK</th>
                    <th style="text-align:center;">Semester</th>
                    <th style="text-align:center;">Tahun Ajaran</th>
                    <th style="text-align:center;">Skor</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">File</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftar as $p)
                    @php
                        $mhs = $p->mahasiswa;
                        $inits = strtoupper(substr($mhs->name ?? 'NA', 0, 2));
                        $fileCount = 0;
                        if($p->khs) $fileCount++;
                        if($p->sertifikat_organisasi) $fileCount++;
                        if($p->sertifikat_mentoring) $fileCount++;
                    @endphp
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;color:#1e40af;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">
                                    {{ $inits }}
                                </div>
                                <div>
                                    <div style="font-weight:500;color:#0f172a;">{{ $mhs->name ?? '—' }}</div>
                                    <div style="font-size:11px;color:#94a3b8;">{{ $mhs->nim ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="color:#64748b;">{{ $mhs->prodi ?? '—' }}</td>
                        <td style="text-align:center;font-weight:500;">{{ $mhs->ipk ? number_format($mhs->ipk, 2) : '—' }}</td>
                        <td style="text-align:center;color:#64748b;">{{ $mhs->semester ?? '—' }}</td>
                        <td style="text-align:center;color:#64748b;">{{ $p->tahun_ajaran }}</td>
                        <td style="text-align:center;font-weight:600;color:#185FA5;">{{ $p->skor_total ? $p->skor_total.'/100' : '—' }}</td>
                        <td style="text-align:center;">
                            <span class="adm-badge adm-badge-{{ $p->status === 'diterima' ? 'dosen' : ($p->status === 'ditolak' ? 'admin' : 'mahasiswa') }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;color:#64748b;">
                                <i data-lucide="paperclip" style="width:12px;height:12px;"></i>
                                {{ $fileCount }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <a href="{{ route('admin.pendaftar.show', $p) }}" class="adm-btn adm-btn-secondary adm-btn-sm" style="gap:5px;">
                                <i data-lucide="eye" style="width:12px;height:12px;"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;color:#94a3b8;padding:32px;">
                            Tidak ada data pendaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        @if($pendaftar->hasPages())
        <div class="adm-pagination">
            {{ $pendaftar->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
