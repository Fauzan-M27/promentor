<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Pasangan;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create()
    {
        $user     = auth()->user();
        $pasangan = Pasangan::where('mentee_id', $user->id)->with('mentor')->first();
        $mentor   = $pasangan?->mentor;

        $riwayat = Feedback::where('mentee_id', $user->id)
            ->with('mentor')
            ->latest()
            ->get();

        $feedbackSebagaiMentor = Feedback::where('mentor_id', $user->id)
            ->with('mentee')
            ->latest()
            ->get();

        // Variabel untuk view JS (aspects list)
        $aspects = [
            ['name' => 'bintang_ketersediaan', 'label' => 'Ketersediaan & Responsivitas'],
            ['name' => 'bintang_penjelasan',   'label' => 'Kemampuan Menjelaskan Materi'],
            ['name' => 'bintang_empati',       'label' => 'Empati & Dukungan Emosional'],
            ['name' => 'bintang_komitmen',     'label' => 'Komitmen & Kedisiplinan'],
        ];

        return view('mahasiswa.feedback', compact('user', 'pasangan', 'mentor', 'riwayat', 'feedbackSebagaiMentor', 'aspects'));
    }

    public function store(Request $request)
    {
        $user     = auth()->user();
        $pasangan = Pasangan::where('mentee_id', $user->id)->first();

        if (!$pasangan) {
            return back()->with('error', 'Anda belum memiliki mentor yang ditugaskan.');
        }

        $validated = $request->validate([
            'bintang_ketersediaan' => ['required', 'integer', 'min:1', 'max:5'],
            'bintang_penjelasan'   => ['required', 'integer', 'min:1', 'max:5'],
            'bintang_empati'       => ['required', 'integer', 'min:1', 'max:5'],
            'bintang_komitmen'     => ['required', 'integer', 'min:1', 'max:5'],
            'hal_positif'          => ['nullable', 'string', 'max:1000'],
            'saran'                => ['nullable', 'string', 'max:1000'],
            'rekomendasi'          => ['required', 'in:ya,mungkin,tidak'],
        ]);

        Feedback::create(array_merge($validated, [
            'mentee_id'    => $user->id,
            'mentor_id'    => $pasangan->mentor_id,
            'pasangan_id'  => $pasangan->id,
            'tahun_ajaran' => '2026',
        ]));

        return back()->with('success', 'Feedback berhasil dikirim! Terima kasih atas penilaian Anda.');
    }
}
