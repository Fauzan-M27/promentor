<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class SeleksiController extends Controller
{
    public function show(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load(['mahasiswa', 'penilaian']);
        return view('dosen.seleksi', compact('pendaftaran'));
    }

    public function store(Request $request, Pendaftaran $pendaftaran)
    {
        $validated = $request->validate([
            'kompetensi_akademik'  => ['required', 'integer', 'min:0', 'max:25'],
            'kemampuan_komunikasi' => ['required', 'integer', 'min:0', 'max:25'],
            'kepemimpinan'         => ['required', 'integer', 'min:0', 'max:25'],
            'integritas_komitmen'  => ['required', 'integer', 'min:0', 'max:25'],
            'catatan'              => ['nullable', 'string'],
        ]);

        $skor = $validated['kompetensi_akademik']
              + $validated['kemampuan_komunikasi']
              + $validated['kepemimpinan']
              + $validated['integritas_komitmen'];

        Penilaian::updateOrCreate(
            ['pendaftaran_id' => $pendaftaran->id],
            array_merge($validated, [
                'dosen_id' => auth()->id(),
            ])
        );

        $pendaftaran->update([
            'skor_total' => $skor,
            'status'     => 'review',
        ]);

        return back()->with('success', 'Penilaian rubrik berhasil disimpan.');
    }

    public function updateStatus(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate([
            'status'         => ['required', 'in:diterima,ditolak'],
            'catatan_dosen'  => ['nullable', 'string'],
        ]);

        $pendaftaran->update([
            'status'        => $request->status,
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        $label = $request->status === 'diterima' ? 'diterima' : 'ditolak';
        return back()->with('success', "Kandidat berhasil {$label}.");
    }
}
