<x-admin-layout>
    <x-slot:pageTitle>Edit User — {{ $user->name }}</x-slot:pageTitle>

    <div style="margin-bottom:16px;">
        <a href="{{ route('admin.users.index') }}" class="adm-btn adm-btn-secondary adm-btn-sm" style="gap:5px;">
            <i data-lucide="arrow-left" style="width:13px;height:13px;"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="adm-alert adm-alert-danger">
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
    @endif

    <div class="adm-form-card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:15px;font-weight:600;color:#0f172a;margin:0;">Edit Data User</h3>
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:38px;height:38px;border-radius:50%;background:#dbeafe;color:#1e40af;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;">
                    {{ strtoupper(substr($user->name,0,2)) }}
                </div>
                <div>
                    <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $user->name }}</div>
                    <span class="adm-badge adm-badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" id="userForm">
            @csrf @method('PUT')

            {{-- ROLE --}}
            <div class="adm-form-group">
                <label class="adm-label">Role <span style="color:#ef4444;">*</span></label>
                <select name="role" id="roleSelect" class="adm-input adm-select" onchange="toggleMahasiswaFields()" required>
                    <option value="admin"     {{ old('role', $user->role) === 'admin'     ? 'selected' : '' }}>Admin</option>
                    <option value="dosen"     {{ old('role', $user->role) === 'dosen'     ? 'selected' : '' }}>Dosen</option>
                    <option value="mahasiswa" {{ old('role', $user->role) === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
                @if($user->id === auth()->id())
                    <div style="font-size:11px;color:#f59e0b;margin-top:4px;">⚠️ Anda sedang mengedit akun sendiri. Jangan ubah role sembarangan.</div>
                @endif
            </div>

            <div class="adm-form-grid-2">
                <div class="adm-form-group">
                    <label class="adm-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="adm-input" placeholder="Nama lengkap" required>
                </div>
                <div class="adm-form-group">
                    <label class="adm-label">Email <span style="color:#ef4444;">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="adm-input" required>
                </div>
            </div>

            <div class="adm-form-grid-2">
                <div class="adm-form-group">
                    <label class="adm-label">Kata Sandi Baru <span style="color:#94a3b8;">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="adm-input" placeholder="Minimal 8 karakter">
                </div>
                <div class="adm-form-group">
                    <label class="adm-label">No. WhatsApp</label>
                    <input type="text" name="no_wa" value="{{ old('no_wa', $user->no_wa) }}" class="adm-input" placeholder="081234567890">
                </div>
            </div>

            {{-- MAHASISWA FIELDS --}}
            <div id="mahasiswaFields" style="display:none; border-top:1px solid #e2e8f0; padding-top:16px; margin-top:4px;">
                <div style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:14px;">Data Mahasiswa</div>
                <div class="adm-form-grid-2">
                    <div class="adm-form-group">
                        <label class="adm-label">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim', $user->nim) }}" class="adm-input" placeholder="220209001">
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label">Program Studi</label>
                        <input type="text" name="prodi" value="{{ old('prodi', $user->prodi) }}" class="adm-input" placeholder="PTIK">
                    </div>
                </div>
                <div class="adm-form-grid-2">
                    <div class="adm-form-group">
                        <label class="adm-label">Semester</label>
                        <input type="number" name="semester" value="{{ old('semester', $user->semester) }}" class="adm-input" placeholder="5" min="1" max="14">
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label">IPK</label>
                        <input type="number" step="0.01" name="ipk" value="{{ old('ipk', $user->ipk) }}" class="adm-input" placeholder="3.85" min="0" max="4">
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="{{ route('admin.users.index') }}" class="adm-btn adm-btn-secondary">Batal</a>
                <button type="submit" class="adm-btn adm-btn-primary" style="gap:6px;">
                    <i data-lucide="save" style="width:15px;height:15px;"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleMahasiswaFields() {
            var role = document.getElementById('roleSelect').value;
            var mhs  = document.getElementById('mahasiswaFields');
            mhs.style.display = role === 'mahasiswa' ? 'block' : 'none';
        }
        document.addEventListener('DOMContentLoaded', toggleMahasiswaFields);
    </script>
</x-admin-layout>
