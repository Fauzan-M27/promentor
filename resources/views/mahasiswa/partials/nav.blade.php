{{-- ===== NAVBAR ===== --}}
<nav class="pm-nav">
    <div class="pm-container" style="display:flex;align-items:center;justify-content:space-between;width:100%;padding-top:0;padding-bottom:0;">
        <div class="pm-nav-logo"><span>PRO-</span><br>MENTOR</div>
        
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

            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="pm-btn">Keluar</button>
            </form>
        </div>
    </div>
</nav>

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

        <a href="{{ route('mahasiswa.feedback.create') }}"       class="pm-tab {{ request()->routeIs('mahasiswa.feedback.*')            ? 'active' : '' }}">Feedback</a>
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
