<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class BerandaController extends Controller
{
    public function index()
    {
        $user        = auth()->user();
        $pendaftaran = $user->pendaftaran()->with('penilaian')->first();
        $feedback_count = Feedback::where('mentee_id', $user->id)->count();

        $tahapan = [
            ['nama' => 'Pendaftaran Online',   'tanggal' => '6–20 Mei 2026',  'status' => 'selesai'],
            ['nama' => 'Seleksi Administrasi', 'tanggal' => '21–23 Mei 2026', 'status' => 'aktif'],
            ['nama' => 'Penilaian Rubrik',     'tanggal' => '24–28 Mei 2026', 'status' => 'mendatang'],
            ['nama' => 'Pengumuman Hasil',     'tanggal' => '30 Mei 2026',    'status' => 'mendatang'],
            ['nama' => 'Sosialisasi Mentor',   'tanggal' => '2–6 Juni 2026',  'status' => 'mendatang'],
        ];

        return view('mahasiswa.beranda', compact('user', 'pendaftaran', 'feedback_count', 'tahapan'));
    }
}
