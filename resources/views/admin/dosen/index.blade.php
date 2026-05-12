<x-admin-layout>
    <x-slot:pageTitle>Manajemen Dosen</x-slot:pageTitle>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="adm-alert adm-alert-success" style="display:flex;align-items:center;gap:8px;">
            <i data-lucide="circle-check" style="width:15px;height:15px;flex-shrink:0;"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="adm-alert adm-alert-danger" style="display:flex;align-items:center;gap:8px;">
            <i data-lucide="circle-x" style="width:15px;height:15px;flex-shrink:0;"></i> {{ session('error') }}
        </div>
    @endif

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;gap:12px;flex-wrap:wrap;">
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama, email, NIDN..."
                class="adm-input"
                style="width:240px;"
            >
            <button type="submit" class="adm-btn adm-btn-primary" style="gap:6px;">
                <i data-lucide="search" style="width:14px;height:14px;"></i> Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.dosen.index') }}" class="adm-btn adm-btn-secondary" style="gap:6px;">
                    <i data-lucide="x" style="width:14px;height:14px;"></i> Reset
                </a>
            @endif
        </form>
        <a href="{{ route('admin.dosen.create') }}" class="adm-btn adm-btn-primary" style="gap:6px;">
            <i data-lucide="user-plus" style="width:15px;height:15px;"></i> Tambah Dosen
        </a>
    </div>

    {{-- Table --}}
    <div class="adm-table-wrap">
        <div class="adm-table-head">
            <h3>
                Daftar Dosen
                <span style="font-weight:400;color:#64748b;">({{ $dosens->total() }} total)</span>
            </h3>
        </div>
        <table class="adm-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Dosen</th>
                    <th>NIDN</th>
                    <th>Email</th>
                    <th>Prodi</th>
                    <th>No. WA</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dosens as $d)
                <tr>
                    <td style="color:#94a3b8;">{{ $dosens->firstItem() + $loop->index }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;
                                        background:linear-gradient(135deg,#7c3aed22,#a855f722);
                                        color:#7c3aed;display:flex;align-items:center;
                                        justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($d->name,0,2)) }}
                            </div>
                            <div style="font-weight:500;color:#0f172a;">{{ $d->name }}</div>
                        </div>
                    </td>
                    <td>
                        @if($d->nidn)
                            <span style="font-family:monospace;font-size:12px;background:#f1f5f9;
                                         padding:2px 8px;border-radius:4px;color:#475569;">
                                {{ $d->nidn }}
                            </span>
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </td>
                    <td style="color:#64748b;">{{ $d->email }}</td>
                    <td style="color:#64748b;">{{ $d->prodi ?? '—' }}</td>
                    <td style="color:#64748b;">{{ $d->no_wa ?? '—' }}</td>
                    <td style="color:#94a3b8;font-size:12px;">{{ $d->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.dosen.edit', $d) }}"
                               class="adm-btn adm-btn-secondary adm-btn-sm" style="gap:5px;">
                                <i data-lucide="pencil" style="width:12px;height:12px;"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.dosen.destroy', $d) }}"
                                  onsubmit="return confirm('Hapus akun dosen {{ addslashes($d->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="adm-btn adm-btn-danger adm-btn-sm">
                                    <i data-lucide="trash-2" style="width:12px;height:12px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:#94a3b8;padding:40px;">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                            <i data-lucide="users" style="width:32px;height:32px;color:#cbd5e1;"></i>
                            <div>Belum ada akun dosen yang terdaftar.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($dosens->hasPages())
        <div class="adm-pagination">
            {{ $dosens->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
