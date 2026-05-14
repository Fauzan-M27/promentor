<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

class BerandaController extends Controller
{
    public function index()
    {
        $user        = auth()->user();
        $pendaftaran = $user->pendaftaran()->with('penilaian')->first();

        // Cek apakah registrasi (Pendaftaran + Self Assessment) sudah selesai
        $is_registrasi_selesai = $pendaftaran && $user->selfAssessment()->exists();

        // Ambil data mentees jika user sudah lolos seleksi (untuk mode Mentor)
        $mentees = [];
        if ($pendaftaran && $pendaftaran->status === 'diterima') {
            // Mentee sekarang adalah data manual (bukan relasi ke User)
            $mentees = $user->mentees()->get(); // Langsung ambil dari tabel pasangan
        }

        $tahapan = [
            ['nama' => 'Pendaftaran Online',   'tanggal' => '6–20 Mei 2026',  'status' => 'selesai'],
            ['nama' => 'Seleksi Administrasi', 'tanggal' => '21–23 Mei 2026', 'status' => 'aktif'],
            ['nama' => 'Penilaian Rubrik',     'tanggal' => '24–28 Mei 2026', 'status' => 'mendatang'],
            ['nama' => 'Pengumuman Hasil',     'tanggal' => '30 Mei 2026',    'status' => 'mendatang'],
            ['nama' => 'Sosialisasi Mentor',   'tanggal' => '2–6 Juni 2026',  'status' => 'mendatang'],
        ];

        return view('mahasiswa.beranda', compact(
            'user', 'pendaftaran', 'tahapan', 
            'is_registrasi_selesai', 'mentees'
        ));
    }
}
