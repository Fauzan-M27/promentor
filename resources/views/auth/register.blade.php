<x-guest-layout>
<div class="pm-login-wrap" style="padding-top:40px; padding-bottom:40px;">
    <div class="pm-login-box" style="max-width:450px;">

        {{-- LOGO --}}
        <div style="text-align:center; margin-bottom:24px;">
            <div style="font-size:26px; font-weight:700; letter-spacing:-0.5px; margin-bottom:6px;">
                <span style="color:#1a1a2e;">PRO</span><span style="color:#185FA5;">-MENTOR</span>
            </div>
            <div style="font-size:12px; color:#888;">Buat Akun Baru</div>
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

            {{-- Common Fields --}}
            <div style="margin-bottom:12px;">
                <label class="pm-label" for="name">Nama Lengkap</label>
                <input id="name" class="pm-input" type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required autofocus>
            </div>

            <div style="margin-bottom:12px;">
                <label class="pm-label" for="email">Email</label>
                <input id="email" class="pm-input" type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
            </div>

            <div style="margin-bottom:12px;">
                <label class="pm-label" for="no_wa">No. WhatsApp</label>
                <input id="no_wa" class="pm-input" type="text" name="no_wa" value="{{ old('no_wa') }}" placeholder="Contoh: 081234567890">
            </div>

            {{-- Passwords --}}
            <div style="margin-bottom:12px;">
                <label class="pm-label" for="password">Kata Sandi</label>
                <input id="password" class="pm-input" type="password" name="password" placeholder="Buat kata sandi" required>
            </div>

            <div style="margin-bottom:18px;">
                <label class="pm-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" class="pm-input" type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required>
            </div>

            {{-- SUBMIT --}}
            <button
                type="submit"
                style="width:100%; background:#185FA5; color:#fff; border:none; border-radius:8px; padding:11px; font-size:14px; font-weight:600; cursor:pointer; transition:background .2s; letter-spacing:0.3px;"
                onmouseover="this.style.background='#0c447c'"
                onmouseout="this.style.background='#185FA5'"
            >
                Daftar
            </button>

            <div style="margin-top:16px; text-align:center; font-size:12px; color:#666;">
                Sudah punya akun? <a href="{{ route('login') }}" style="color:#185FA5; text-decoration:none; font-weight:500;">Masuk di sini</a>
            </div>
        </form>
    </div>
</div>
</x-guest-layout>
