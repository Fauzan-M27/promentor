{{-- ===== NAVBAR ===== --}}
<nav class="pm-nav">
    <div class="pm-nav-logo"><span>PRO</span>-MENTOR <span style="font-size:11px;font-weight:400;color:#888;margin-left:6px;">JTIK UNM</span></div>
    <div style="display:flex;align-items:center;gap:10px;">
        <div class="pm-av pm-av-blue" style="width:30px;height:30px;font-size:10px;">
            {{ strtoupper(substr(auth()->user()->name, 0, 3)) }}
        </div>
        <span style="font-size:13px;font-weight:600;color:#1a1a2e;">{{ auth()->user()->name }}</span>
        <span class="badge badge-review" style="font-size:11px;">Dosen</span>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="pm-btn" style="font-size:12px;padding:5px 12px;">Keluar</button>
        </form>
    </div>
</nav>

{{-- ===== TAB BAR ===== --}}
<div class="pm-tab-bar">
    <a href="{{ route('dosen.beranda') }}"   class="pm-tab {{ request()->routeIs('dosen.beranda')        ? 'active' : '' }}">Beranda</a>
    <a href="{{ route('dosen.pendaftar') }}" class="pm-tab {{ request()->routeIs(['dosen.pendaftar', 'dosen.seleksi.*']) ? 'active' : '' }}">Seleksi Mentor</a>
    <a href="{{ route('dosen.pasangan') }}"  class="pm-tab {{ request()->routeIs('dosen.pasangan*')       ? 'active' : '' }}">Pasangan Mentor</a>
    <a href="{{ route('dosen.feedback') }}"  class="pm-tab {{ request()->routeIs('dosen.feedback')        ? 'active' : '' }}">Rekap Feedback</a>
    <a href="{{ route('dosen.laporan') }}"   class="pm-tab {{ request()->routeIs('dosen.laporan*')        ? 'active' : '' }}">Laporan</a>
</div>

{{-- FLASH MESSAGES --}}
@if(session('success'))
    <div style="max-width:960px;margin:12px auto 0;padding:0 24px;">
        <div style="background:#dcfce7;border:1px solid #86efac;border-radius:8px;padding:10px 14px;font-size:13px;color:#166534;">{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div style="max-width:960px;margin:12px auto 0;padding:0 24px;">
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:10px 14px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
    </div>
@endif
@if(session('info'))
    <div style="max-width:960px;margin:12px auto 0;padding:0 24px;">
        <div style="background:#dbeafe;border:1px solid #93c5fd;border-radius:8px;padding:10px 14px;font-size:13px;color:#1e40af;">{{ session('info') }}</div>
    </div>
@endif
