{{-- ===== NAVBAR ===== --}}
<style>
    .pm-mobile-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 998;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .pm-mobile-overlay.active {
        display: block;
        opacity: 1;
    }
    .pm-mobile-menu {
        display: none;
        position: fixed;
        top: 0;
        left: -100% !important;
        right: auto !important;
        width: 280px;
        height: 100%;
        background: #fff;
        z-index: 999;
        transition: left 0.3s ease;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        overflow-y: auto;
    }
    .pm-mobile-menu.active {
        left: 0 !important;
    }
    .pm-hamburger {
        display: none;
        flex-direction: column;
        gap: 4px;
        cursor: pointer;
        padding: 8px;
    }
    .pm-hamburger span {
        width: 24px;
        height: 3px;
        background: #185FA5;
        border-radius: 2px;
        transition: all 0.3s;
    }
    @media(max-width:768px) {
        .pm-nav-actions {
            display: none !important;
        }
        .pm-hamburger {
            display: flex !important;
        }
        .pm-mobile-menu {
            display: block;
        }
        .pm-tab-bar {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .pm-tab-bar::-webkit-scrollbar {
            display: none;
        }
    }
</style>

<nav class="pm-nav">
    <div class="pm-container" style="display:flex;align-items:center;justify-content:space-between;width:100%;padding-top:0;padding-bottom:0;">
        {{-- Mobile Hamburger (Kiri) --}}
        <div class="pm-hamburger" onclick="toggleMobileMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="pm-nav-logo"><span>PRO-</span><br>MENTOR</div>
        
        {{-- Desktop Menu --}}
        <div class="pm-nav-actions">
            <div class="pm-nav-info" style="text-align:right;">
                <span class="pm-nav-name">{{ auth()->user()->name }}</span>
                <div class="pm-nav-badges">
                    <span class="badge badge-success">Mahasiswa</span>
                    @if(auth()->user()->pendaftaran && auth()->user()->pendaftaran->status === 'diterima')
                        <span class="badge badge-info" style="background:#e0f2fe;color:#0369a1;">Mentor</span>
                    @endif
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

{{-- Mobile Menu --}}
<div class="pm-mobile-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>
<div class="pm-mobile-menu" id="mobileMenu">
    <div style="padding:20px;border-bottom:1px solid #e2e8f0;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <div style="font-size:18px;font-weight:700;color:#185FA5;">PRO-MENTOR</div>
            <button onclick="toggleMobileMenu()" style="background:none;border:none;cursor:pointer;padding:8px;">
                <i data-lucide="x" style="width:24px;height:24px;color:#64748b;"></i>
            </button>
        </div>
        <div style="font-weight:600;color:#0f172a;margin-bottom:4px;">{{ auth()->user()->name }}</div>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
            <span class="badge badge-success">Mahasiswa</span>
            @if(auth()->user()->pendaftaran && auth()->user()->pendaftaran->status === 'diterima')
                <span class="badge badge-info" style="background:#e0f2fe;color:#0369a1;">Mentor</span>
            @endif
        </div>
    </div>
    <div style="padding:12px;">
        <a href="{{ route('profile.edit') }}" style="display:flex;align-items:center;gap:12px;padding:12px;text-decoration:none;color:#0f172a;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
            <i data-lucide="user" style="width:20px;height:20px;color:#185FA5;"></i>
            <span style="font-weight:500;">Profil Saya</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" style="width:100%;display:flex;align-items:center;gap:12px;padding:12px;text-decoration:none;color:#991b1b;border-radius:8px;transition:background 0.2s;background:none;border:none;cursor:pointer;text-align:left;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background=''">
                <i data-lucide="log-out" style="width:20px;height:20px;"></i>
                <span style="font-weight:500;">Keluar</span>
            </button>
        </form>
    </div>
</div>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const overlay = document.getElementById('mobileOverlay');
    menu.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
}
</script>

{{-- ===== TAB BAR ===== --}}
@php
    $user = auth()->user();
    $hasPendaftaran = $user->pendaftaran()->exists();
    $hasSelfAssessment = $user->selfAssessment()->exists();
@endphp
<div class="pm-tab-bar">
    <div class="pm-container" style="display:flex;gap:2px;padding-top:0;padding-bottom:0;width:100%;">
        <a href="{{ route('mahasiswa.beranda') }}" class="pm-tab {{ request()->routeIs('mahasiswa.beranda') ? 'active' : '' }}">Beranda</a>
        
        @if(request()->routeIs('mahasiswa.daftar.*') || $hasPendaftaran)
            <a href="{{ route('mahasiswa.daftar.create') }}" class="pm-tab {{ request()->routeIs('mahasiswa.daftar.*') ? 'active' : '' }}">Daftar</a>
        @endif

        @if($hasPendaftaran)
            <a href="{{ route('mahasiswa.self-assessment.create') }}" class="pm-tab {{ request()->routeIs('mahasiswa.self-assessment.*') ? 'active' : '' }}">Self-Assessment</a>
        @endif

        @if($hasSelfAssessment)
            <a href="{{ route('mahasiswa.seleksi') }}" class="pm-tab {{ request()->routeIs('mahasiswa.seleksi') ? 'active' : '' }}">Seleksi</a>
        @endif

        <a href="{{ route('mahasiswa.notifikasi') }}" class="pm-tab {{ request()->routeIs('mahasiswa.notifikasi') ? 'active' : '' }}">
            <span>Notifikasi</span>
            @php $notifBaru = auth()->user()->notifikasi()->where('dibaca',false)->count(); @endphp
            @if($notifBaru > 0)
                <span style="background:#185FA5;color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:10px;margin-left:5px;">{{ $notifBaru }}</span>
            @endif
        </a>
    </div>
</div>

{{-- FLASH --}}
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
