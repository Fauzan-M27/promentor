{{-- ===== NAVBAR ===== --}}
<nav class="pm-nav">
    <div class="pm-container" style="display:flex;align-items:center;justify-content:space-between;width:100%;padding-top:0;padding-bottom:0;">
        <div class="pm-nav-logo"><span>PRO-</span><br>MENTOR</div>
        
        <div class="pm-nav-actions">
            <div class="pm-nav-info" style="text-align:right;">
                <span class="pm-nav-name">{{ auth()->user()->name }}</span>
                <div class="pm-nav-badges">
                    <span class="badge badge-review">Dosen</span>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" style="font-size:12px;font-weight:600;color:#185FA5;text-decoration:none;margin-right:12px;">Profil Saya</a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="pm-btn">Keluar</button>
            </form>
        </div>
    </div>
</nav>

{{-- ===== TAB BAR ===== --}}
<div class="pm-tab-bar">
    <div class="pm-container" style="display:flex;gap:2px;padding-top:0;padding-bottom:0;width:100%;">
        <a href="{{ route('dosen.beranda') }}"   class="pm-tab {{ request()->routeIs('dosen.beranda')        ? 'active' : '' }}">Beranda</a>
        <a href="{{ route('dosen.pendaftar') }}" class="pm-tab {{ request()->routeIs(['dosen.pendaftar', 'dosen.seleksi.*']) ? 'active' : '' }}">Seleksi</a>
        <a href="{{ route('dosen.notifikasi') }}" class="pm-tab {{ request()->routeIs('dosen.notifikasi') ? 'active' : '' }}">
            <span>Notifikasi</span>
            @php $jumlahNotifikasiUnread = auth()->user()->notifikasi()->where('dibaca', false)->count(); @endphp
            @if($jumlahNotifikasiUnread > 0)
                <span style="background:#185FA5;color:white;font-size:10px;padding:2px 8px;border-radius:12px;margin-left:6px;font-weight:700;">
                    {{ $jumlahNotifikasiUnread }}
                </span>
            @endif
        </a>

    </div>
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
