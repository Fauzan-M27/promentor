@if (session('status') === 'profile-updated')
    <div class="pm-alert pm-alert-success" style="margin-bottom:20px;padding:12px;background:#dcfce7;color:#166534;border-radius:8px;border:1px solid #bbf7d0;">
        Berhasil memperbarui informasi profil.
    </div>
@endif

@if (session('status') === 'password-updated')
    <div class="pm-alert pm-alert-success" style="margin-bottom:20px;padding:12px;background:#dcfce7;color:#166534;border-radius:8px;border:1px solid #bbf7d0;">
        Berhasil memperbarui kata sandi.
    </div>
@endif

<div style="display:flex;flex-direction:column;gap:24px;">

    {{-- KARTU INFORMASI PROFIL --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
            <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#185FA5,#1e40af);
                        color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <h3 style="margin:0;font-size:16px;font-weight:600;color:#0f172a;">Informasi Profil</h3>
                <div style="font-size:12px;color:#64748b;">Perbarui profil dan alamat email Anda.</div>
            </div>
        </div>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px;">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box;" required>
                @error('name')<div style="font-size:12px;color:#ef4444;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px;">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box;" required>
                @error('email')<div style="font-size:12px;color:#ef4444;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px;">Nomor WhatsApp</label>
                <input type="text" name="no_wa" value="{{ old('no_wa', $user->no_wa) }}"
                       style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box;">
                @error('no_wa')<div style="font-size:12px;color:#ef4444;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="display:flex;justify-content:flex-end;">
                <button type="submit" style="background:#185FA5;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- KARTU UBAH PASSWORD --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;">
        <div style="margin-bottom:20px;">
            <h3 style="margin:0;font-size:16px;font-weight:600;color:#0f172a;">Perbarui Kata Sandi</h3>
            <div style="font-size:12px;color:#64748b;">Pastikan akun Anda menggunakan kata sandi yang panjang dan aman.</div>
        </div>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px;">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password"
                       style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box;" required>
                @error('current_password', 'updatePassword')<div style="font-size:12px;color:#ef4444;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px;">Kata Sandi Baru</label>
                <input type="password" name="password"
                       style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box;" required>
                @error('password', 'updatePassword')<div style="font-size:12px;color:#ef4444;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px;">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation"
                       style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box;" required>
                @error('password_confirmation', 'updatePassword')<div style="font-size:12px;color:#ef4444;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="display:flex;justify-content:flex-end;">
                <button type="submit" style="background:#0f172a;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;">
                    Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>

</div>
