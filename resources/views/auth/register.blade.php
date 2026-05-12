<x-guest-layout>
<div class="pm-login-wrap" style="padding-top:40px; padding-bottom:40px;">
    <div class="pm-login-box" style="max-width:480px;">

        {{-- LOGO --}}
        <div style="text-align:center; margin-bottom:24px;">
            <div style="font-size:26px; font-weight:700; letter-spacing:-0.5px; margin-bottom:6px;">
                <span style="color:#1a1a2e;">PRO</span><span style="color:#185FA5;">-MENTOR</span>
            </div>
            <div style="font-size:12px; color:#888;">Buat Akun Mahasiswa</div>
        </div>

        {{-- INFO BADGE --}}
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:10px 12px;
                    margin-bottom:18px;font-size:12px;color:#1e40af;display:flex;gap:8px;align-items:flex-start;">
            <i data-lucide="info" style="width:14px;height:14px;flex-shrink:0;margin-top:1px;"></i>
            <span>Halaman pendaftaran ini khusus untuk <strong>mahasiswa</strong>. Akun dosen hanya dapat dibuat oleh Admin.</span>
        </div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            {{-- Session errors --}}
            @if ($errors->any())
                <div style="background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; padding:10px 12px; margin-bottom:14px; font-size:12px; color:#991b1b;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            {{-- ─── DATA AKUN ─── --}}
            <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;
                        letter-spacing:.8px;margin-bottom:12px;padding-bottom:6px;border-bottom:1px solid #e2e8f0;">
                Data Akun
            </div>

            <div style="margin-bottom:12px;">
                <label class="pm-label" for="name">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                <input id="name" class="pm-input" type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required autofocus>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label class="pm-label" for="email">Email <span style="color:#ef4444;">*</span></label>
                    <input id="email" class="pm-input" type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required>
                </div>
                <div>
                    <label class="pm-label" for="no_wa">No. WhatsApp</label>
                    <input id="no_wa" class="pm-input" type="text" name="no_wa" value="{{ old('no_wa') }}" placeholder="081234567890">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px;">
                <div>
                    <label class="pm-label" for="password">Kata Sandi <span style="color:#ef4444;">*</span></label>
                    <input id="password" class="pm-input" type="password" name="password" placeholder="Min. 8 karakter" required>
                </div>
                <div>
                    <label class="pm-label" for="password_confirmation">Konfirmasi Sandi <span style="color:#ef4444;">*</span></label>
                    <input id="password_confirmation" class="pm-input" type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required>
                </div>
            </div>

            {{-- ─── DATA IDENTITAS MAHASISWA ─── --}}
            <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;
                        letter-spacing:.8px;margin-bottom:12px;padding-bottom:6px;border-bottom:1px solid #e2e8f0;">
                Identitas Mahasiswa
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label class="pm-label" for="nim">NIM <span style="color:#ef4444;">*</span></label>
                    <input id="nim" class="pm-input" type="text" name="nim" value="{{ old('nim') }}" placeholder="220209001" required>
                </div>
                <div>
                    <label class="pm-label" for="prodi">Program Studi <span style="color:#ef4444;">*</span></label>
                    <select id="prodi" name="prodi" class="pm-input" required>
                        <option value="">Pilih Prodi</option>
                        <option value="PTIK"            {{ old('prodi') === 'PTIK'            ? 'selected' : '' }}>PTIK</option>
                        <option value="Teknik Komputer" {{ old('prodi') === 'Teknik Komputer' ? 'selected' : '' }}>Teknik Komputer</option>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
                <div>
                    <label class="pm-label" for="semester">Semester Aktif <span style="color:#ef4444;">*</span></label>
                    <select id="semester" name="semester" class="pm-input" required>
                        <option value="">Pilih Semester</option>
                        @for($s = 1; $s <= 8; $s++)
                            <option value="{{ $s }}" {{ old('semester') == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="pm-label" for="ipk">IPK Terakhir</label>
                    <input id="ipk" class="pm-input" type="number" step="0.01" min="0" max="4"
                           name="ipk" value="{{ old('ipk') }}" placeholder="3.75">
                </div>
            </div>

            {{-- SUBMIT --}}
            <button
                type="submit"
                style="width:100%; background:#185FA5; color:#fff; border:none; border-radius:8px; padding:11px; font-size:14px; font-weight:600; cursor:pointer; transition:background .2s; letter-spacing:0.3px;"
                onmouseover="this.style.background='#0c447c'"
                onmouseout="this.style.background='#185FA5'"
            >
                Daftar Sebagai Mahasiswa
            </button>

            <div style="margin-top:16px; text-align:center; font-size:12px; color:#666;">
                Sudah punya akun? <a href="{{ route('login') }}" style="color:#185FA5; text-decoration:none; font-weight:500;">Masuk di sini</a>
            </div>
        </form>
    </div>
</div>
</x-guest-layout>
