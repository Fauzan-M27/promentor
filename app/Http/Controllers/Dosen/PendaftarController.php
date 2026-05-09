<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class PendaftarController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with('mahasiswa')->where('tahun_ajaran', '2026');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prodi')) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('prodi', $request->prodi));
        }

        if ($request->filled('cari')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->cari.'%')
                  ->orWhere('nim', 'like', '%'.$request->cari.'%');
            });
        }

        $pendaftar = $query->latest()->paginate(10);

        $stats = [
            'total' => Pendaftaran::where('tahun_ajaran', '2026')->count(),
            'pending' => Pendaftaran::where('tahun_ajaran', '2026')->where('status', 'pending')->count(),
            'diterima' => Pendaftaran::where('tahun_ajaran', '2026')->where('status', 'diterima')->count(),
        ];

        return view('dosen.pendaftar', compact('pendaftar', 'stats'));
    }
}
