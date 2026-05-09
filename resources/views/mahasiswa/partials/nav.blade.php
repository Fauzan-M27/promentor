{{-- ===== NAVBAR ===== --}}
<nav class="pm-nav">
    <div class="pm-nav-logo"><span>PRO</span>-MENTOR <span style="font-size:11px;font-weight:400;color:#888;margin-left:6px;">JTIK UNM</span></div>
    <div style="display:flex;align-items:center;gap:10px;">
        <div class="pm-av pm-av-green" style="width:30px;height:30px;font-size:10px;">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
        <span style="font-size:13px;font-weight:600;color:#1a1a2e;">{{ auth()->user()->name }}</span>
        <span class="badge badge-success" style="font-size:11px;">Mahasiswa</span>
        @if(auth()->user()->pendaftaran && auth()->user()->pendaftaran->status === 'diterima')
            <span class="badge badge-info" style="font-size:11px;background:#e0f2fe;color:#0369a1;border-color:#bae6fd;">Mentor</span>
        @endif
        @php $notifBaru = auth()->user()->notifikasi()->where('dibaca',false)->count(); @endphp
        @if($notifBaru > 0)
            <a href="{{ route('mahasiswa.notifikasi') }}" style="text-decoration:none;">
                <span style="background:#185FA5;color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:10px;">{{ $notifBaru }}</span>
            </a>
        @endif
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="pm-btn" style="font-size:12px;padding:5px 12px;">Keluar</button>
        </form>
    </div>
</nav>

{{-- ===== TAB BAR ===== --}}
<div class="pm-tab-bar">
    <a href="{{ route('mahasiswa.beranda') }}"               class="pm-tab {{ request()->routeIs('mahasiswa.beranda')               ? 'active' : '' }}">Beranda</a>
    <a href="{{ route('mahasiswa.daftar.create') }}"         class="pm-tab {{ request()->routeIs('mahasiswa.daftar.*')               ? 'active' : '' }}">Daftar Mentor</a>
    <a href="{{ route('mahasiswa.seleksi') }}"               class="pm-tab {{ request()->routeIs('mahasiswa.seleksi')               ? 'active' : '' }}">Status Seleksi</a>
    <a href="{{ route('mahasiswa.self-assessment.create') }}" class="pm-tab {{ request()->routeIs('mahasiswa.self-assessment.*')     ? 'active' : '' }}">Self Assessment</a>
    <a href="{{ route('mahasiswa.feedback.create') }}"       class="pm-tab {{ request()->routeIs('mahasiswa.feedback.*')            ? 'active' : '' }}">Feedback</a>
    <a href="{{ route('mahasiswa.notifikasi') }}" class="pm-tab {{ request()->routeIs('mahasiswa.notifikasi') ? 'active' : '' }}">
        Notifikasi
        @if(isset($notifBaru) && $notifBaru > 0)
            <span style="background:#185FA5;color:#fff;font-size:10px;font-weight:700;padding:1px 5px;border-radius:10px;margin-left:3px;">{{ $notifBaru }}</span>
        @endif
    </a>
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
