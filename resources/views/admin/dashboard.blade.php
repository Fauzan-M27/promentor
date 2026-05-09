<x-admin-layout>
    <x-slot:pageTitle>Dashboard Admin</x-slot:pageTitle>

    {{-- STAT GRID --}}
    <div class="adm-stat-grid">

        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#eff6ff;">
                <i data-lucide="users" style="color:#2563eb;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['total_user'] }}</div>
            <div class="adm-stat-lbl">Total User</div>
        </div>

        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#f0fdf4;">
                <i data-lucide="briefcase" style="color:#16a34a;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['total_dosen'] }}</div>
            <div class="adm-stat-lbl">Dosen</div>
        </div>

        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#fdf4ff;">
                <i data-lucide="book-open" style="color:#9333ea;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['total_mahasiswa'] }}</div>
            <div class="adm-stat-lbl">Mahasiswa</div>
        </div>

        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#fff7ed;">
                <i data-lucide="clipboard-list" style="color:#ea580c;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['total_pendaftar'] }}</div>
            <div class="adm-stat-lbl">Total Pendaftar</div>
        </div>

        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#f0fdf4;">
                <i data-lucide="handshake" style="color:#16a34a;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['total_pasangan'] }}</div>
            <div class="adm-stat-lbl">Pasangan Aktif</div>
        </div>

        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#fefce8;">
                <i data-lucide="star" style="color:#ca8a04;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['total_feedback'] }}</div>
            <div class="adm-stat-lbl">Feedback Masuk</div>
        </div>

        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#fef2f2;">
                <i data-lucide="clock" style="color:#dc2626;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['pending_seleksi'] }}</div>
            <div class="adm-stat-lbl">Menunggu Seleksi</div>
        </div>

        <div class="adm-stat-card">
            <div class="adm-stat-icon" style="background:#eff6ff;">
                <i data-lucide="shield-check" style="color:#2563eb;"></i>
            </div>
            <div class="adm-stat-val">{{ $stats['total_admin'] }}</div>
            <div class="adm-stat-lbl">Admin</div>
        </div>

    </div>

    {{-- USER TERBARU --}}
    <div class="adm-table-wrap">
        <div class="adm-table-head">
            <h3>User Terbaru</h3>
            <a href="{{ route('admin.users.index') }}" class="adm-btn adm-btn-secondary adm-btn-sm">
                Lihat Semua <i data-lucide="arrow-right" style="width:14px;height:14px;"></i>
            </a>
        </div>
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($userTerbaru as $u)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;color:#1e40af;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($u->name,0,2)) }}
                            </div>
                            <div style="font-weight:500;color:#0f172a;">{{ $u->name }}</div>
                        </div>
                    </td>
                    <td style="color:#64748b;">{{ $u->email }}</td>
                    <td>
                        <span class="adm-badge adm-badge-{{ $u->role }}">{{ ucfirst($u->role) }}</span>
                    </td>
                    <td style="color:#64748b;">{{ $u->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $u) }}" class="adm-btn adm-btn-secondary adm-btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
