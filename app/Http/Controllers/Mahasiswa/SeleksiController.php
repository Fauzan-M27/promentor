<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

class SeleksiController extends Controller
{
    public function index()
    {
        $user        = auth()->user();
        $pendaftaran = $user->pendaftaran()->with('penilaian')->first();
        $penilaian   = $pendaftaran?->penilaian;

        return view('mahasiswa.seleksi', compact('user', 'pendaftaran', 'penilaian'));
    }
}
