<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Feedback;
use App\Models\Pasangan;

class BerandaController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pendaftar'  => Pendaftaran::where('tahun_ajaran', '2026')->count(),
            'dalam_seleksi'    => Pendaftaran::whereIn('status', ['pending', 'review'])->count(),
            'diterima'         => Pendaftaran::where('status', 'diterima')->count(),
        ];

        $pendaftar_terbaru = Pendaftaran::with('mahasiswa')
            ->where('tahun_ajaran', '2026')
            ->latest()
            ->take(5)
            ->get();

        $tahapan = [
            ['nama' => 'Pendaftaran Online',    'tanggal' => '6–20 Mei 2026',  'status' => 'selesai'],
            ['nama' => 'Seleksi Administrasi',  'tanggal' => '21–23 Mei 2026', 'status' => 'aktif'],
            ['nama' => 'Penilaian Rubrik',      'tanggal' => '24–28 Mei 2026', 'status' => 'mendatang'],
            ['nama' => 'Pengumuman Hasil',      'tanggal' => '30 Mei 2026',    'status' => 'mendatang'],
            ['nama' => 'Sosialisasi Mentor',    'tanggal' => '2–6 Juni 2026',  'status' => 'mendatang'],
        ];

        $progress = $stats['total_pendaftar'] > 0
            ? round(($stats['diterima'] + $stats['dalam_seleksi']) / $stats['total_pendaftar'] * 100)
            : 0;

        return view('dosen.beranda', compact('stats', 'pendaftar_terbaru', 'tahapan', 'progress'));
    }
}
