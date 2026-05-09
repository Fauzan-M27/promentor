<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;

class DaftarController extends Controller
{
    public function create()
    {
        $user        = auth()->user();
        $pendaftaran = $user->pendaftaran;
        $sudahDaftar = (bool) $pendaftaran;

        return view('mahasiswa.daftar', compact('user', 'pendaftaran', 'sudahDaftar'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->pendaftaran) {
            return redirect()->route('mahasiswa.seleksi')
                ->with('info', 'Anda sudah mendaftarkan diri.');
        }

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'nim'                   => ['required', 'string', 'max:20'],
            'prodi'                 => ['required', 'in:PTIK,Teknik Komputer,Sistem Informasi'],
            'semester'              => ['required', 'integer', 'min:1', 'max:8'],
            'ipk'                   => ['required', 'numeric', 'min:0', 'max:4'],
            'no_wa'                 => ['required', 'string', 'max:20'],
            'email'                 => ['required', 'email'],
            'pengalaman_organisasi' => ['nullable', 'string', 'max:1000'],
            'motivasi'              => ['required', 'string', 'min:30', 'max:2000'],
            'ketersediaan_waktu'    => ['required', 'in:2,4,6,8,10'],
        ]);

        // Update profil user dengan data terbaru
        $user->update([
            'name'     => $validated['name'],
            'nim'      => $validated['nim'],
            'prodi'    => $validated['prodi'],
            'semester' => $validated['semester'],
            'ipk'      => $validated['ipk'],
            'no_wa'    => $validated['no_wa'],
            'email'    => $validated['email'],
        ]);

        // Buat record pendaftaran
        Pendaftaran::create([
            'user_id'               => $user->id,
            'pengalaman_organisasi' => $validated['pengalaman_organisasi'] ?? null,
            'motivasi'              => $validated['motivasi'],
            'ketersediaan_waktu'    => $validated['ketersediaan_waktu'],
            'status'                => 'pending',
            'tahun_ajaran'          => '2026',
        ]);

        return redirect()->route('mahasiswa.beranda')
            ->with('success', 'Pendaftaran berhasil dikirim! Pantau status di halaman Beranda.');
    }
}
