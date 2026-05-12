<x-admin-layout>
    <x-slot:pageTitle>Tambah User Baru</x-slot:pageTitle>

    <div style="margin-bottom:16px;">
        <a href="{{ route('admin.users.index') }}" class="adm-btn adm-btn-secondary adm-btn-sm" style="gap:5px;">
            <i data-lucide="arrow-left" style="width:13px;height:13px;"></i> Kembali
        </a>
    </div>

    {{-- INFO: Cara buat akun dosen --}}
    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:12px 16px;margin-bottom:20px;
                display:flex;gap:10px;align-items:flex-start;font-size:12px;color:#1e40af;">
        <i data-lucide="info" style="width:16px;height:16px;flex-shrink:0;margin-top:1px;"></i>
        <div>
            <strong>Catatan:</strong> Akun <strong>Dosen</strong> hanya dapat dibuat dari halaman ini.
            Mahasiswa mendaftar sendiri melalui halaman registrasi publik.
        </div>
    </div>

    @if($errors->any())
        <div class="adm-alert adm-alert-danger">
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
    @endif

    <div class="adm-form-card">
        <h3 style="font-size:15px;font-weight:600;color:#0f172a;margin:0 0 20px;">Tambah User Baru</h3>

        <form method="POST" action="{{ route('admin.users.store') }}" id="userForm">
            @csrf

            <div class="adm-form-group">
                <label class="adm-label">Role <span style="color:#ef4444;">*</span></label>
                <select name="role" id="roleSelect" class="adm-input adm-select" onchange="toggleRoleFields()" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin"     {{ old('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
                    <option value="mahasiswa" {{ old('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
                <div style="font-size:11px;color:#94a3b8;margin-top:4px;">
                    Untuk menambah akun Dosen, gunakan halaman
                    <a href="{{ route('admin.dosen.index') }}" style="color:#7c3aed;font-weight:600;">Manajemen Dosen</a>.
                </div>
            </div>

            <div class="adm-form-grid-2">
                <div class="adm-form-group">
                    <label class="adm-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="adm-input" placeholder="Nama lengkap" required>
                </div>
                <div class="adm-form-group">
                    <label class="adm-label">Email <span style="color:#ef4444;">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="adm-input" placeholder="email@example.com" required>
                </div>
            </div>

            <div class="adm-form-grid-2">
                <div class="adm-form-group">
                    <label class="adm-label">Kata Sandi <span style="color:#ef4444;">*</span></label>
                    <input type="password" name="password" class="adm-input" placeholder="Minimal 8 karakter" required>
                </div>
                <div class="adm-form-group">
                    <label class="adm-label">No. WhatsApp</label>
                    <input type="text" name="no_wa" value="{{ old('no_wa') }}" class="adm-input" placeholder="081234567890">
                </div>
            </div>

            {{-- ═══ MAHASISWA FIELDS ═══ --}}
            <div id="mahasiswaFields" style="display:none; border-top:1px solid #e2e8f0; padding-top:16px; margin-top:4px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                    <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#185FA5,#1e40af);
                                display:flex;align-items:center;justify-content:center;">
                        <i data-lucide="graduation-cap" style="width:14px;height:14px;color:#fff;"></i>
                    </div>
                    <span style="font-size:12px;font-weight:700;color:#185FA5;text-transform:uppercase;letter-spacing:.5px;">
                        Data Mahasiswa
                    </span>
                </div>
                <div class="adm-form-grid-2">
                    <div class="adm-form-group">
                        <label class="adm-label">NIM <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="nim" value="{{ old('nim') }}" class="adm-input" placeholder="220209001">
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label">Program Studi <span style="color:#ef4444;">*</span></label>
                        <select name="prodi" id="prodiMahasiswa" class="adm-input">
                            <option value="">Pilih Prodi</option>
                            <option value="PTIK"            {{ old('prodi') === 'PTIK'            ? 'selected' : '' }}>PTIK</option>
                            <option value="Teknik Komputer" {{ old('prodi') === 'Teknik Komputer' ? 'selected' : '' }}>Teknik Komputer</option>
                        </select>
                    </div>
                </div>
                <div class="adm-form-grid-2">
                    <div class="adm-form-group">
                        <label class="adm-label">Semester Aktif</label>
                        <select name="semester" class="adm-input">
                            <option value="">Pilih Semester</option>
                            @for($s = 1; $s <= 8; $s++)
                                <option value="{{ $s }}" {{ old('semester') == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label">IPK</label>
                        <input type="number" step="0.01" name="ipk" value="{{ old('ipk') }}" class="adm-input" placeholder="3.85" min="0" max="4">
                    </div>
                </div>
                <div class="adm-form-group">
                    <label class="adm-label">No. WhatsApp</label>
                    <input type="text" name="no_wa_mhs" value="{{ old('no_wa_mhs', old('no_wa')) }}" class="adm-input" placeholder="081234567890">
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="{{ route('admin.users.index') }}" class="adm-btn adm-btn-secondary">Batal</a>
                <button type="submit" class="adm-btn adm-btn-primary" style="gap:6px;">
                    <i data-lucide="save" style="width:15px;height:15px;"></i> Simpan User
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleRoleFields() {
            var role = document.getElementById('roleSelect').value;
            var mhsFields = document.getElementById('mahasiswaFields');

            mhsFields.style.display = role === 'mahasiswa' ? 'block' : 'none';

            // Disable inputs di section yang tersembunyi agar tidak terkirim
            mhsFields.querySelectorAll('input, select').forEach(el => el.disabled = role !== 'mahasiswa');
        }
        document.addEventListener('DOMContentLoaded', toggleRoleFields);
    </script>
</x-admin-layout>
