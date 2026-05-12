<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\Notifikasi;
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
            'prodi'                 => ['required', 'in:PTIK,Teknik Komputer'],
            'semester'              => ['required', 'integer', 'min:1', 'max:8'],
            'ipk'                   => ['required', 'numeric', 'min:0', 'max:4'],
            'no_wa'                 => ['required', 'string', 'max:20'],
            'email'                 => ['required', 'email'],
            'pengalaman_organisasi' => ['nullable', 'string', 'max:1000'],
            'ketersediaan_waktu'    => ['required', 'in:2,4,6,8,10'],
            'motivation_letter'     => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'khs'                   => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'sertifikat_organisasi' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
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

        // Simpan file ke storage
        $motivationPath = $request->file('motivation_letter')->store('pendaftaran/motivation_letters', 'public');
        $khsPath        = $request->file('khs')->store('pendaftaran/khs', 'public');
        $sertifikatPath = null;

        if ($request->hasFile('sertifikat_organisasi')) {
            $sertifikatPath = $request->file('sertifikat_organisasi')->store('pendaftaran/sertifikat', 'public');
        }

        // Buat record pendaftaran
        Pendaftaran::create([
            'user_id'               => $user->id,
            'pengalaman_organisasi' => $validated['pengalaman_organisasi'] ?? null,
            'motivation_letter'     => $motivationPath,
            'khs'                   => $khsPath,
            'sertifikat_organisasi' => $sertifikatPath,
            'ketersediaan_waktu'    => $validated['ketersediaan_waktu'],
            'status'                => 'pending',
            'tahun_ajaran'          => '2026',
        ]);

        // Kirim notifikasi ke semua dosen
        $dosens = User::where('role', 'dosen')->get();
        foreach ($dosens as $dosen) {
            Notifikasi::send(
                $dosen->id,
                'Pendaftaran Mentor Baru',
                "Mahasiswa {$user->name} ({$user->nim}) baru saja mendaftar sebagai calon mentor.",
                'info'
            );
        }

        return redirect()->route('mahasiswa.self-assessment.create')
            ->with('success', 'Pendaftaran berhasil dikirim! Silakan lanjutkan dengan mengisi Self Assessment.');
    }
}
