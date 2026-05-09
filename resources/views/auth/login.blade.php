<x-guest-layout>
<div class="pm-login-wrap">
    <div class="pm-login-box">

        {{-- LOGO --}}
        <div style="text-align:center; margin-bottom:24px;">
            <div style="font-size:26px; font-weight:700; letter-spacing:-0.5px; margin-bottom:6px;">
                <span style="color:#1a1a2e;">PRO</span><span style="color:#185FA5;">-MENTOR</span>
            </div>
            <div style="font-size:12px; color:#888;">Sistem Rekrutmen Mentor JTIK UNM</div>
        </div>

        {{-- HEADING --}}
        <div style="font-size:15px; font-weight:600; color:#1a1a2e; margin-bottom:3px;">Masuk ke sistem</div>
        <div style="font-size:12px; color:#888; margin-bottom:16px;">Silakan masuk menggunakan akun Anda</div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            {{-- Session errors --}}
            @if ($errors->any())
                <div style="background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; padding:10px 12px; margin-bottom:14px; font-size:12px; color:#991b1b;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif



            {{-- EMAIL / NIM --}}
            <div style="margin-bottom:12px;">
                <label class="pm-label" for="email">Email / NIM</label>
                <input
                    id="email"
                    class="pm-input"
                    type="text"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email atau NIM"
                    required
                    autofocus
                    autocomplete="username"
                />
            </div>

            {{-- PASSWORD --}}
            <div style="margin-bottom:18px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                    <label class="pm-label" for="password" style="margin-bottom:0;">Kata sandi</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size:11px; color:#185FA5; text-decoration:none;">Lupa sandi?</a>
                    @endif
                </div>
                <input
                    id="password"
                    class="pm-input"
                    type="password"
                    name="password"
                    placeholder="Masukkan kata sandi"
                    required
                    autocomplete="current-password"
                />
            </div>

            {{-- REMEMBER ME --}}
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
                <input type="checkbox" name="remember" id="remember" style="width:16px; height:16px; flex-shrink:0; accent-color:#185FA5; cursor:pointer;">
                <label for="remember" style="font-size:12px; color:#666; cursor:pointer;">Ingat saya</label>
            </div>

            {{-- SUBMIT --}}
            <button
                type="submit"
                style="width:100%; background:#185FA5; color:#fff; border:none; border-radius:8px; padding:11px; font-size:14px; font-weight:600; cursor:pointer; transition:background .2s; letter-spacing:0.3px;"
                onmouseover="this.style.background='#0c447c'"
                onmouseout="this.style.background='#185FA5'"
            >
                Masuk
            </button>

            <div style="margin-top:16px; text-align:center; font-size:12px; color:#666;">
                Belum punya akun? <a href="{{ route('register') }}" style="color:#185FA5; text-decoration:none; font-weight:500;">Daftar di sini</a>
            </div>
        </form>


        {{-- DEMO LOGIN INFO --}}
        <div style="margin-top:18px; padding:12px 14px; background:#f0f7ff; border-radius:8px; font-size:12px; color:#555; line-height:1.7;">
            <div style="font-weight:600; color:#1e40af; margin-bottom:4px;">Demo Login:</div>
            <div>Dosen &rarr; masuk sebagai <strong>Ainun Nida Rifqi</strong></div>
            <div style="font-size:11px; color:#888; margin-top:1px;">ainun@jtik.unm.ac.id / password</div>
            <div style="margin-top:6px;">Mahasiswa &rarr; masuk sebagai <strong>Ahmad Reza</strong></div>
            <div style="font-size:11px; color:#888; margin-top:1px;">ahmad@mhs.unm.ac.id / password</div>
        </div>

    </div>
</div>
</x-guest-layout>
