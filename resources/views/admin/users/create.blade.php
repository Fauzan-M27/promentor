<x-admin-layout>
    <x-slot:pageTitle>Tambah User Baru</x-slot:pageTitle>

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
        <h3 style="font-size:15px;font-weight:600;color:#0f172a;margin:0 0 20px;">Tambah User Baru</h3>

        <form method="POST" action="{{ route('admin.users.store') }}" id="userForm">
            @csrf

            {{-- ROLE --}}
            <div class="adm-form-group">
                <label class="adm-label">Role <span style="color:#ef4444;">*</span></label>
                <select name="role" id="roleSelect" class="adm-input adm-select" onchange="toggleMahasiswaFields()" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin"     {{ old('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
                    <option value="dosen"     {{ old('role') === 'dosen'     ? 'selected' : '' }}>Dosen</option>
                    <option value="mahasiswa" {{ old('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
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

            {{-- MAHASISWA FIELDS --}}
            <div id="mahasiswaFields" style="display:none; border-top:1px solid #e2e8f0; padding-top:16px; margin-top:4px;">
                <div style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:14px;">Data Mahasiswa</div>
                <div class="adm-form-grid-2">
                    <div class="adm-form-group">
                        <label class="adm-label">NIM</label>
                        <input type="text" name="nim" id="nim" value="{{ old('nim') }}" class="adm-input" placeholder="220209001">
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label">Program Studi</label>
                        <input type="text" name="prodi" value="{{ old('prodi') }}" class="adm-input" placeholder="PTIK">
                    </div>
                </div>
                <div class="adm-form-grid-2">
                    <div class="adm-form-group">
                        <label class="adm-label">Semester</label>
                        <input type="number" name="semester" id="semester" value="{{ old('semester') }}" class="adm-input" placeholder="5" min="1" max="14">
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label">IPK</label>
                        <input type="number" step="0.01" name="ipk" id="ipk" value="{{ old('ipk') }}" class="adm-input" placeholder="3.85" min="0" max="4">
                    </div>
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
        function toggleMahasiswaFields() {
            var role = document.getElementById('roleSelect').value;
            var mhs  = document.getElementById('mahasiswaFields');
            mhs.style.display = role === 'mahasiswa' ? 'block' : 'none';
        }
        document.addEventListener('DOMContentLoaded', toggleMahasiswaFields);
    </script>
</x-admin-layout>
