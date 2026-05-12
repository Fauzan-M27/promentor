<x-admin-layout>
    <x-slot:pageTitle>Edit Dosen — {{ $dosen->name }}</x-slot:pageTitle>

    <div style="margin-bottom:16px;">
        <a href="{{ route('admin.dosen.index') }}" class="adm-btn adm-btn-secondary adm-btn-sm" style="gap:5px;">
            <i data-lucide="arrow-left" style="width:13px;height:13px;"></i> Kembali ke Manajemen Dosen
        </a>
    </div>

    @if($errors->any())
        <div class="adm-alert adm-alert-danger">
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
    @endif

    <div class="adm-form-card" style="max-width:640px;">

        {{-- Header card --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
            <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin:0;">Edit Data Dosen</h3>
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:38px;height:38px;border-radius:50%;
                            background:linear-gradient(135deg,#7c3aed22,#a855f722);
                            color:#7c3aed;display:flex;align-items:center;
                            justify-content:center;font-size:14px;font-weight:700;">
                    {{ strtoupper(substr($dosen->name,0,2)) }}
                </div>
                <div>
                    <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $dosen->name }}</div>
                    <span class="adm-badge adm-badge-dosen">Dosen</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.dosen.update', $dosen) }}" id="editDosenForm">
            @csrf @method('PUT')

            <div class="adm-form-group">
                <label class="adm-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" value="{{ old('name', $dosen->name) }}"
                       class="adm-input" required>
            </div>

            <div class="adm-form-group">
                <label class="adm-label">Email <span style="color:#ef4444;">*</span></label>
                <input type="email" name="email" value="{{ old('email', $dosen->email) }}"
                       class="adm-input" required>
            </div>

            {{-- NIDN + Prodi --}}
            <div class="adm-form-grid-2">
                <div class="adm-form-group">
                    <label class="adm-label">NIDN</label>
                    <input type="text" name="nidn" value="{{ old('nidn', $dosen->nidn) }}"
                           class="adm-input" placeholder="0012345678">
                </div>
                <div class="adm-form-group">
                    <label class="adm-label">Prodi yang Diampu</label>
                    <select name="prodi" class="adm-input">
                        <option value="">Pilih Prodi</option>
                        <option value="PTIK"            {{ old('prodi', $dosen->prodi) === 'PTIK'            ? 'selected' : '' }}>PTIK</option>
                        <option value="Teknik Komputer" {{ old('prodi', $dosen->prodi) === 'Teknik Komputer' ? 'selected' : '' }}>Teknik Komputer</option>
                    </select>
                </div>
            </div>

            <div class="adm-form-group">
                <label class="adm-label">No. WhatsApp</label>
                <input type="text" name="no_wa" value="{{ old('no_wa', $dosen->no_wa) }}"
                       class="adm-input" placeholder="081234567890">
            </div>

            <div style="border-top:1px solid #e2e8f0;margin:16px 0 14px;"></div>

            <div class="adm-form-group">
                <label class="adm-label">
                    Kata Sandi Baru
                    <span style="font-weight:400;color:#94a3b8;">(kosongkan jika tidak diubah)</span>
                </label>
                <div style="position:relative;">
                    <input type="password" name="password" id="pwdInput" class="adm-input"
                           placeholder="Min. 8 karakter" style="padding-right:40px;">
                    <button type="button" onclick="togglePwd()"
                            style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
                                   background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;">
                        <i data-lucide="eye" id="pwdIcon" style="width:16px;height:16px;"></i>
                    </button>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="{{ route('admin.dosen.index') }}" class="adm-btn adm-btn-secondary">Batal</a>
                <button type="submit" class="adm-btn adm-btn-primary" style="gap:6px;">
                    <i data-lucide="save" style="width:15px;height:15px;"></i> Simpan Perubahan
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
