<style>
    .pm-nav {
        background: #185FA5;
        padding: 0 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 56px;
        box-shadow: 0 1px 3px rgba(0,0,0,.1);
    }
    .pm-nav-brand {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        letter-spacing: -.5px;
    }
    .pm-nav-brand span {
        color: #dbeafe;
    }
    .pm-nav-links {
        display: flex;
        gap: 4px;
        align-items: center;
    }
    .pm-nav-link {
        padding: 8px 16px;
        color: rgba(255,255,255,.85);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        border-radius: 6px;
        transition: all .15s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .pm-nav-link:hover {
        background: rgba(255,255,255,.15);
        color: #fff;
    }
    .pm-nav-link.active {
        background: rgba(255,255,255,.2);
        color: #fff;
    }
    .pm-nav-user {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .pm-nav-user-info {
        text-align: right;
    }
    .pm-nav-user-name {
        font-size: 12px;
        font-weight: 600;
        color: #fff;
    }
    .pm-nav-user-role {
        font-size: 10px;
        color: rgba(255,255,255,.7);
    }
    .pm-nav-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,.2);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
    }
    .pm-nav-logout {
        padding: 6px 14px;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.2);
        color: #fff;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .pm-nav-logout:hover {
        background: rgba(255,255,255,.25);
    }
</style>

<nav class="pm-nav">
    <div class="pm-nav-brand">PRO<span>-MENTOR</span></div>
    
    <div class="pm-nav-links">
        <a href="{{ route('admin.dashboard') }}" class="pm-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" style="width:14px;height:14px;"></i>
            Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="pm-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i data-lucide="users" style="width:14px;height:14px;"></i>
            Kelola User
        </a>
        <a href="{{ route('admin.pendaftar.index') }}" class="pm-nav-link {{ request()->routeIs('admin.pendaftar.*') ? 'active' : '' }}">
            <i data-lucide="clipboard-list" style="width:14px;height:14px;"></i>
            Data Pendaftar
        </a>
        <a href="{{ route('admin.dosen.index') }}" class="pm-nav-link {{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}">
            <i data-lucide="graduation-cap" style="width:14px;height:14px;"></i>
            Dosen
        </a>
        <a href="{{ route('admin.pasangan') }}" class="pm-nav-link {{ request()->routeIs('admin.pasangan*') ? 'active' : '' }}">
            <i data-lucide="handshake" style="width:14px;height:14px;"></i>
            Pasangan
        </a>
        <a href="{{ route('admin.feedback') }}" class="pm-nav-link {{ request()->routeIs('admin.feedback*') ? 'active' : '' }}">
            <i data-lucide="star" style="width:14px;height:14px;"></i>
            Feedback
        </a>
        <a href="{{ route('admin.laporan') }}" class="pm-nav-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
            <i data-lucide="file-text" style="width:14px;height:14px;"></i>
            Laporan
        </a>
    </div>

    <div class="pm-nav-user">
        <div class="pm-nav-user-info">
            <div class="pm-nav-user-name">{{ auth()->user()->name }}</div>
            <div class="pm-nav-user-role">Administrator</div>
        </div>
        <div class="pm-nav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="pm-nav-logout">
                <i data-lucide="log-out" style="width:13px;height:13px;"></i>
                Keluar
            </button>
        </form>
    </div>
</nav>
