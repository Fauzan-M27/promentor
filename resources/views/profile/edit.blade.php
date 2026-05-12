@php
    $role = auth()->user()->role;
    $layout = 'app';
    if ($role === 'admin') $layout = 'admin';
    else if ($role === 'dosen') $layout = 'dosen';
    else if ($role === 'mahasiswa') $layout = 'mahasiswa';
@endphp

@if($layout === 'admin')
    <x-admin-layout>
        <x-slot:pageTitle>Profil Saya</x-slot:pageTitle>
        @include('profile.partials.custom-profile-content')
    </x-admin-layout>
@else
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PRO-MENTOR — Profil Saya</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body style="background:#f1f5f9;">
        @if($layout === 'dosen')
            @include('dosen.partials.nav')
        @elseif($layout === 'mahasiswa')
            @include('mahasiswa.partials.nav')
        @endif

        <div class="pm-container" style="max-width: 800px; margin: 40px auto; padding: 0 20px;">
            @include('profile.partials.custom-profile-content')
        </div>

        <div style="text-align:center;padding:16px;font-size:11px;color:#aaa;border-top:1px solid #e8eaed;margin-top:20px;">
            PRO-MENTOR v1.0 · JTIK UNM · 2026
        </div>
        <script>
            lucide.createIcons();
        </script>
    </body>
    </html>
@endif
