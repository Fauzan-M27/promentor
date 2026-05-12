<x-admin-layout>
    <x-slot:pageTitle>Tambah Akun Dosen</x-slot:pageTitle>

    <div style="margin-bottom:16px;">
        <a href="{{ route('admin.dosen.index') }}" class="adm-btn adm-btn-secondary adm-btn-sm" style="gap:5px;">
            <i data-lucide="arrow-left" style="width:13px;height:13px;"></i> Kembali ke Manajemen Dosen
        </a>
    </div>

    {{-- INFO BADGE --}}
    <div style="background:#faf5ff;border:1px solid #e9d5ff;border-radius:10px;padding:12px 16px;
                margin-bottom:20px;font-size:12px;color:#6b21a8;display:flex;gap:10px;align-items:flex-start;">
        <i data-lucide="shield-check" style="width:16px;height:16px;flex-shrink:0;margin-top:1px;"></i>
        <span>
            <strong>Catatan:</strong> Akun dosen hanya dapat dibuat oleh Admin. 
            Isi formulir di bawah ini dengan lengkap.
        </span>
    </div>

    @if($errors->any())
        <div class="adm-alert adm-alert-danger">
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
    @endif

    <div class="adm-form-card" style="max-width:640px;">
        <h3 style="font-size:15px;font-weight:600;color:#0f172a;margin:0 0 20px;">Form Tambah Dosen</h3>

        <form method="POST" action="{{ route('admin.dosen.store') }}" id="dosenForm">
            @csrf

            <div class="adm-form-group">
                <label class="adm-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="adm-input"
                       placeholder="Dr. Nama Lengkap, M.Pd." required>
            </div>

            <div class="adm-form-group">
                <label class="adm-label">Email <span style="color:#ef4444;">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" class="adm-input"
                       placeholder="dosen@unm.ac.id" required>
            </div>

            {{-- NIDN + Prodi --}}
            <div class="adm-form-grid-2">
                <div class="adm-form-group">
                    <label class="adm-label">NIDN</label>
                    <input type="text" name="nidn" value="{{ old('nidn') }}" class="adm-input"
                           placeholder="0012345678">
                </div>
                <div class="adm-form-group">
                    <label class="adm-label">Prodi yang Diampu</label>
                    <select name="prodi" class="adm-input">
                        <option value="">Pilih Prodi</option>
                        <option value="PTIK"            {{ old('prodi') === 'PTIK'            ? 'selected' : '' }}>PTIK</option>
                        <option value="Teknik Komputer" {{ old('prodi') === 'Teknik Komputer' ? 'selected' : '' }}>Teknik Komputer</option>
                    </select>
                </div>
            </div>

            <div class="adm-form-group">
                <label class="adm-label">No. WhatsApp</label>
                <input type="text" name="no_wa" value="{{ old('no_wa') }}" class="adm-input"
                       placeholder="081234567890">
            </div>

            <div style="border-top:1px solid #e2e8f0;margin:16px 0 14px;"></div>

            <div class="adm-form-group">
                <label class="adm-label">
                    Kata Sandi <span style="color:#ef4444;">*</span>
                    <span style="font-weight:400;color:#94a3b8;">(min. 8 karakter)</span>
                </label>
                <div style="position:relative;">
                    <input type="password" name="password" id="pwdInput" class="adm-input"
                           placeholder="Buat kata sandi" required style="padding-right:40px;">
                    <button type="button" onclick="togglePwd()"
                            style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
                                   background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;">
                        <i data-lucide="eye" id="pwdIcon" style="width:16px;height:16px;"></i>
                    </button>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <a href="{{ route('admin.dosen.index') }}" class="adm-btn adm-btn-secondary">Batal</a>
                <button type="submit" class="adm-btn adm-btn-primary" style="gap:6px;">
                    <i data-lucide="user-plus" style="width:15px;height:15px;"></i> Tambah Dosen
                </button>
            </div>
        </form>
    </div>

    <script>
        function togglePwd() {
            var input = document.getElementById('pwdInput');
            var icon  = document.getElementById('pwdIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</x-admin-layout>
