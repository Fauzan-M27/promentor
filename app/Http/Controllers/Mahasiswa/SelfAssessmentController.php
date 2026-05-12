<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\SelfAssessment;
use Illuminate\Http\Request;

class SelfAssessmentController extends Controller
{
    public function create()
    {
        $user     = auth()->user();
        $sudahIsi = (bool) $user->selfAssessment;

        return view('mahasiswa.self-assessment', compact('user', 'sudahIsi'));
    }

    public function store(Request $request)
    {
        $user        = auth()->user();
        $pendaftaran = $user->pendaftaran;

        if (!$pendaftaran) {
            return redirect()->route('mahasiswa.daftar.create')
                ->with('info', 'Lengkapi pendaftaran terlebih dahulu.');
        }

        $validated = $request->validate([
            'q1' => ['required', 'integer'],
            'q2' => ['required', 'integer'],
            'q3' => ['required', 'integer'],
            'q4' => ['required', 'integer'],
            'q5' => ['required', 'integer'],
            'q6' => ['required', 'integer'],
            'kelebihan'           => ['nullable', 'string', 'max:1000'],
            'kelemahan'           => ['nullable', 'string', 'max:1000'],
            'rencana_aksi'        => ['nullable', 'string', 'max:1000'],
        ]);

        SelfAssessment::updateOrCreate(
            ['user_id' => $user->id, 'pendaftaran_id' => $pendaftaran->id],
            [
                'q1_kemampuan_akademik'      => $validated['q1'],
                'q2_kemampuan_menjelaskan'   => $validated['q2'],
                'q3_komunikasi_empati'       => $validated['q3'],
                'q4_ketersediaan_waktu'      => $validated['q4'],
                'q5_pengalaman_kepemimpinan' => $validated['q5'],
                'q6_motivasi'                => $validated['q6'],
                'kelebihan'                  => $validated['kelebihan'] ?? null,
                'kelemahan'                  => $validated['kelemahan'] ?? null,
                'rencana_jika_kesulitan'     => $validated['rencana_aksi'] ?? null,
            ]
        );

        return redirect()->route('mahasiswa.seleksi')
            ->with('success', 'Self Assessment berhasil dikirim! Silakan pantau status seleksi Anda di sini.');
    }
}
