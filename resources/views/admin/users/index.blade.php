<x-admin-layout>
    <x-slot:pageTitle>Kelola User</x-slot:pageTitle>

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

    {{-- FILTER & SEARCH --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;gap:12px;flex-wrap:wrap;">
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama, email, NIM..."
                class="adm-input"
                style="width:240px;"
            >
            <select name="role" class="adm-input adm-select" style="width:140px;">
                <option value="">Semua Role</option>
                <option value="admin"     {{ request('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
                <option value="dosen"     {{ request('role') === 'dosen'     ? 'selected' : '' }}>Dosen</option>
                <option value="mahasiswa" {{ request('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            </select>
            <button type="submit" class="adm-btn adm-btn-primary" style="gap:6px;">
                <i data-lucide="search" style="width:14px;height:14px;"></i> Filter
            </button>
            @if(request()->hasAny(['search','role']))
                <a href="{{ route('admin.users.index') }}" class="adm-btn adm-btn-secondary" style="gap:6px;">
                    <i data-lucide="x" style="width:14px;height:14px;"></i> Reset
                </a>
            @endif
        </form>
        <a href="{{ route('admin.users.create') }}" class="adm-btn adm-btn-primary" style="gap:6px;">
            <i data-lucide="user-plus" style="width:15px;height:15px;"></i> Tambah User
        </a>
    </div>

    {{-- TABLE --}}
    <div class="adm-table-wrap">
        <div class="adm-table-head">
            <h3>Daftar User <span style="font-weight:400;color:#64748b;">({{ $users->total() }} total)</span></h3>
        </div>
        <table class="adm-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>NIM / Prodi</th>
                    <th>No. WA</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td style="color:#94a3b8;">{{ $users->firstItem() + $loop->index }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;color:#1e40af;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($u->name,0,2)) }}
                            </div>
                            <div>
                                <div style="font-weight:500;color:#0f172a;">{{ $u->name }}</div>
                                @if($u->semester) <div style="font-size:11px;color:#94a3b8;">Sem. {{ $u->semester }} | IPK {{ $u->ipk ?? '-' }}</div> @endif
                            </div>
                        </div>
                    </td>
                    <td style="color:#64748b;">{{ $u->email }}</td>
                    <td>
                        <span class="adm-badge adm-badge-{{ $u->role }}">{{ ucfirst($u->role) }}</span>
                    </td>
                    <td style="color:#64748b;">
                        @if($u->nim)
                            <div>{{ $u->nim }}</div>
                            <div style="font-size:11px;color:#94a3b8;">{{ $u->prodi }}</div>
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </td>
                    <td style="color:#64748b;">{{ $u->no_wa ?? '—' }}</td>
                    <td style="color:#94a3b8;font-size:12px;">{{ $u->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.users.edit', $u) }}" class="adm-btn adm-btn-secondary adm-btn-sm" style="gap:5px;">
                                <i data-lucide="pencil" style="width:12px;height:12px;"></i> Edit
                            </a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                                  onsubmit="return confirm('Hapus user {{ addslashes($u->name) }}? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="adm-btn adm-btn-danger adm-btn-sm">
                                    <i data-lucide="trash-2" style="width:12px;height:12px;"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:#94a3b8;padding:32px;">
                        Tidak ada user yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        @if($users->hasPages())
        <div class="adm-pagination">
            {{$users->links()}}
        </div>
        @endif
    </div>
</x-admin-layout>
