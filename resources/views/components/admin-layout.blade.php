@props(['pageTitle' => 'Admin Panel'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} — PRO-MENTOR Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --adm-sidebar-w: 240px;
            --adm-navy: #0f172a;
            --adm-navy-2: #1e293b;
            --adm-accent: #185FA5;
            --adm-accent-light: #dbeafe;
            --adm-text-muted: #94a3b8;
        }
        body { margin: 0; display: flex; min-height: 100vh; background: #f1f5f9; overflow-x: hidden; width: 100%; position: relative; }
        html { overflow-x: hidden; width: 100%; }

        /* SIDEBAR */
        .adm-sidebar {
            width: var(--adm-sidebar-w);
            background: var(--adm-navy);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow-y: auto;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .adm-sidebar-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 90;
            display: none;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .adm-sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
        .adm-sidebar-logo {
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .adm-sidebar-logo .brand { font-size: 18px; font-weight: 700; color: #fff; letter-spacing: -.5px; }
        .adm-sidebar-logo .brand span { color: #60a5fa; }
        .adm-sidebar-logo .sub { font-size: 10px; color: var(--adm-text-muted); margin-top: 2px; text-transform: uppercase; letter-spacing: .5px; }
        .adm-nav-section { padding: 16px 12px 6px; font-size: 10px; font-weight: 600; color: var(--adm-text-muted); text-transform: uppercase; letter-spacing: .8px; }
        .adm-nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 16px; margin: 1px 8px;
            border-radius: 8px; font-size: 13px; font-weight: 500;
            color: #cbd5e1; text-decoration: none;
            transition: all .15s;
        }
        .adm-nav-item:hover { background: rgba(255,255,255,.07); color: #fff; }
        .adm-nav-item.active { background: var(--adm-accent); color: #fff; }
        .adm-nav-item .icon { width: 20px; height: 20px; flex-shrink: 0; display:flex; align-items:center; justify-content:center; }
        .adm-nav-item .icon svg { width: 16px; height: 16px; stroke: currentColor; stroke-width: 2; fill: none; }

        .adm-stat-icon { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-bottom:12px; }
        .adm-stat-icon svg { width:18px; height:18px; stroke-width:2; fill:none; }
        .adm-sidebar-footer {
            margin-top: auto;
            padding: 14px 12px;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .adm-user-chip {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 8px;
            background: rgba(255,255,255,.06);
        }
        .adm-user-av {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--adm-accent); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; flex-shrink: 0;
        }
        .adm-user-name { font-size: 12px; font-weight: 600; color: #f1f5f9; }
        .adm-user-role { font-size: 10px; color: var(--adm-text-muted); }

        /* MAIN */
        .adm-main {
            margin-left: var(--adm-sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
            max-width: 100vw;
            box-sizing: border-box;
        }
        .adm-topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 28px;
            height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 40;
        }
        .adm-topbar-title { font-size: 15px; font-weight: 600; color: #0f172a; }
        .adm-topbar-right { display: flex; align-items: center; gap: 12px; }
        .adm-logout-btn {
            font-size: 12px; padding: 6px 14px;
            border: 1px solid #e2e8f0; border-radius: 6px;
            background: #fff; color: #64748b; cursor: pointer;
            text-decoration: none; transition: all .15s;
        }
        .adm-logout-btn:hover { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }

        .adm-content { padding: 28px; flex: 1; }

        /* STAT CARDS */
        .adm-stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 24px; }
        .adm-stat-card {
            background: #fff; border: 1px solid #e2e8f0;
            border-radius: 12px; padding: 18px 20px;
        }

        .adm-stat-val { font-size: 26px; font-weight: 700; color: #0f172a; }
        .adm-stat-lbl { font-size: 12px; color: #64748b; margin-top: 2px; }

        /* TABLE */
        .adm-table-wrap { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
        .adm-table-head { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; }
        .adm-table-head h3 { font-size: 14px; font-weight: 600; color: #0f172a; margin: 0; }
        table.adm-table { width: 100%; border-collapse: collapse; }
        table.adm-table th {
            background: #f8fafc; font-size: 11px; font-weight: 600;
            color: #64748b; text-transform: uppercase; letter-spacing: .5px;
            padding: 10px 18px; text-align: left; border-bottom: 1px solid #e2e8f0;
        }
        table.adm-table td {
            padding: 12px 18px; font-size: 13px; color: #374151;
            border-bottom: 1px solid #f1f5f9;
        }
        table.adm-table tr:last-child td { border-bottom: none; }
        table.adm-table tr:hover td { background: #f8fafc; }

        /* BADGES */
        .adm-badge { display:inline-block; font-size:11px; padding:2px 10px; border-radius:20px; font-weight:500; }
        .adm-badge-admin    { background:#fef3c7; color:#92400e; }
        .adm-badge-dosen    { background:#dbeafe; color:#1e40af; }
        .adm-badge-mahasiswa{ background:#dcfce7; color:#166534; }

        /* FORM CARD */
        .adm-form-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:24px; }
        .adm-form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .adm-form-group { margin-bottom:16px; }
        .adm-label { display:block; font-size:12px; font-weight:500; color:#374151; margin-bottom:5px; }
        .adm-input {
            width:100%; font-size:13px; padding:9px 12px;
            border:1px solid #d1d5db; border-radius:8px;
            background:#fff; color:#0f172a; outline:none;
            transition:border .15s; box-sizing:border-box;
        }
        .adm-input:focus { border-color: var(--adm-accent); box-shadow:0 0 0 3px rgba(24,95,165,.1); }
        .adm-select { appearance:none; background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-repeat:no-repeat; background-position:right 10px center; background-size:18px; padding-right:34px; }

        /* BUTTONS */
        .adm-btn { display:inline-flex; align-items:center; gap:6px; font-size:13px; padding:8px 16px; border-radius:8px; border:1px solid transparent; cursor:pointer; font-weight:500; text-decoration:none; transition:all .15s; }
        .adm-btn-primary { background:var(--adm-accent); color:#fff; border-color:var(--adm-accent); }
        .adm-btn-primary:hover { background:#0c447c; }
        .adm-btn-secondary { background:#fff; color:#374151; border-color:#d1d5db; }
        .adm-btn-secondary:hover { background:#f8fafc; }
        .adm-btn-danger { background:#fee2e2; color:#991b1b; border-color:#fca5a5; }
        .adm-btn-danger:hover { background:#fecaca; }
        .adm-btn-sm { padding:5px 12px; font-size:12px; }

        /* ALERTS */
        .adm-alert { padding:10px 14px; border-radius:8px; font-size:13px; margin-bottom:16px; }
        .adm-alert-success { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
        .adm-alert-danger   { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }

        @media(max-width:768px){
            :root { --adm-sidebar-w: 0px; }
            .adm-sidebar {
                transform: translateX(-100%);
                width: 280px;
                box-shadow: 20px 0 25px -5px rgba(0,0,0,0.1), 10px 0 10px -5px rgba(0,0,0,0.04);
            }
            .adm-sidebar.open { transform: translateX(0); }
            .adm-main { margin-left: 0; }
            .adm-stat-grid { grid-template-columns:1fr 1fr; }
            .adm-form-grid-2 { grid-template-columns:1fr; }
            .adm-content { padding: 80px 16px 16px; width: 100%; box-sizing: border-box; }
            .mobile-only { display: flex !important; }
            .adm-table-wrap { max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
            table.adm-table { min-width: 700px; }
            .adm-topbar { position: fixed; top: 0; left: 0; right: 0; z-index: 50; height: 64px; }
            .adm-stat-grid { grid-template-columns: repeat(4, 1fr) !important; gap: 8px !important; }
            .adm-stat-card { padding: 10px 4px !important; text-align: center; }
            .adm-stat-icon { width: 28px !important; height: 28px !important; margin: 0 auto 6px !important; }
            .adm-stat-icon svg { width: 14px !important; height: 14px !important; }
            .adm-stat-val { font-size: 15px !important; letter-spacing: -0.5px; }
            .adm-stat-lbl { font-size: 8px !important; text-transform: uppercase; font-weight: 600; margin-top: 2px; }
            
            /* Responsive Utility Classes */
            .adm-grid-resp { grid-template-columns: 1fr !important; gap: 16px !important; }
            .adm-flex-resp { flex-direction: column !important; align-items: flex-start !important; gap: 8px !important; }
            .adm-hide-mobile { display: none !important; }
            .adm-stack-mobile { flex-direction: column !important; align-items: stretch !important; gap: 10px !important; }
        }
        
        .adm-grid-resp { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .adm-stat-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        @media(max-width:1024px){
            .adm-stat-grid-4 { grid-template-columns: 1fr 1fr; }
        }
        @media(max-width:600px){
            .adm-stat-grid-4 { grid-template-columns: 1fr 1fr; }
        }
        
        .mobile-only { display: none; }
    </style>
</head>
{{-- OVERLAY --}}
<div class="adm-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

{{-- SIDEBAR --}}
<aside class="adm-sidebar" id="sidebar">
    <div class="adm-sidebar-logo" style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <div class="brand">PRO<span>-MENTOR</span></div>
            <div class="sub">Admin Panel</div>
        </div>
        <button onclick="toggleSidebar()" style="background:rgba(255,255,255,0.1); border:none; color:#fff; width:32px; height:32px; border-radius:50%; align-items:center; justify-content:center; cursor:pointer;" class="mobile-only">
            <i data-lucide="x" style="width:18px; height:18px;"></i>
        </button>
    </div>

    <div class="adm-nav-section">Menu</div>
    <a href="{{ route('admin.dashboard') }}"
       class="adm-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span class="icon"><i data-lucide="layout-dashboard"></i></span> Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}"
       class="adm-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <span class="icon"><i data-lucide="users"></i></span> Kelola User
    </a>
    <a href="{{ route('admin.pendaftar.index') }}"
       class="adm-nav-item {{ request()->routeIs('admin.pendaftar.*') ? 'active' : '' }}">
        <span class="icon"><i data-lucide="clipboard-list"></i></span> Data Pendaftar
    </a>
    <a href="{{ route('admin.dosen.index') }}"
       class="adm-nav-item {{ request()->routeIs('admin.dosen*') ? 'active' : '' }}">
        <span class="icon"><i data-lucide="graduation-cap"></i></span> Manajemen Dosen
    </a>
    <a href="{{ route('admin.pasangan') }}"
       class="adm-nav-item {{ request()->routeIs('admin.pasangan*') ? 'active' : '' }}">
        <span class="icon"><i data-lucide="users-round"></i></span> Pasangan Mentor
    </a>
    <a href="{{ route('admin.laporan') }}"
       class="adm-nav-item {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
        <span class="icon"><i data-lucide="bar-chart-3"></i></span> Laporan & Analitik
    </a>

    <div class="adm-sidebar-footer">
        <div class="adm-user-chip">
            <div class="adm-user-av">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div>
                <div class="adm-user-name">{{ auth()->user()->name }}</div>
                <div class="adm-user-role">Administrator</div>
            </div>
        </div>
        <div style="display:flex;gap:6px;margin-top:8px;">
            <a href="{{ route('profile.edit') }}" class="adm-logout-btn" style="flex:1;text-align:center;display:flex;align-items:center;justify-content:center;gap:6px;">
                <i data-lucide="user-cog" style="width:13px;height:13px;"></i> Profil
            </a>
            <form method="POST" action="{{ route('logout') }}" style="flex:1;margin:0;">
                @csrf
                <button type="submit" class="adm-logout-btn" style="width:100%; text-align:center; display:flex; align-items:center; justify-content:center; gap:6px; color:#991b1b; background:#fee2e2; border-color:#fca5a5;">
                    <i data-lucide="log-out" style="width:13px;height:13px;"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- MAIN --}}
<div class="adm-main">
    <div class="adm-topbar">
        <div style="display:flex; align-items:center; gap:12px;">
            <button onclick="toggleSidebar()" style="background:var(--adm-accent-light); border:none; color:var(--adm-accent); width:40px; height:40px; border-radius:10px; align-items:center; justify-content:center; cursor:pointer; padding:0; transition: all 0.2s;" class="mobile-only" id="hamburger">
                <i data-lucide="menu" style="width:24px; height:24px;"></i>
            </button>
            <div class="adm-topbar-title">{{ $pageTitle }}</div>
        </div>
        <div class="adm-topbar-right">
            <span style="font-size:12px; color:#64748b; @media(max-width:480px){display:none;}">{{ now()->isoFormat('D MMMM YYYY') }}</span>
        </div>
    </div>
    <div class="adm-content">
        {{ $slot }}
    </div>
</div>

<script>
    lucide.createIcons();

    function toggleSidebar() {
        if (window.innerWidth > 768) return;
        
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const isOpen = sidebar.classList.toggle('open');
        
        if (isOpen) {
            overlay.style.display = 'block';
            setTimeout(() => overlay.classList.add('active'), 10);
            document.body.style.overflow = 'hidden';
        } else {
            overlay.classList.remove('active');
            setTimeout(() => {
                if (!sidebar.classList.contains('open')) {
                    overlay.style.display = 'none';
                }
            }, 400);
            document.body.style.overflow = '';
        }
    }

    // Close sidebar on click outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (window.innerWidth <= 768 && 
            sidebar.classList.contains('open') && 
            !sidebar.contains(event.target) && 
            !hamburger.contains(event.target)) {
            toggleSidebar();
        }
    });
</script>
</body>
</html>
