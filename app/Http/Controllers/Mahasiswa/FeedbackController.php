<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Pasangan;
use App\Models\Notifikasi;
use App\Models\User;
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

        // Kirim notifikasi ke Mentor
        $mentor = User::find($pasangan->mentor_id);
        Notifikasi::send(
            $mentor->id,
            'Feedback Baru Diterima',
            "Anda baru saja menerima feedback baru dari mentee {$user->name}. Cek detailnya di halaman feedback.",
            'info'
        );

        // Kirim notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notifikasi::send(
                $admin->id,
                'Feedback Mentor Terkirim',
                "Mentee {$user->name} baru saja memberi feedback ke mentor {$mentor->name}.",
                'info'
            );
        }

        return back()->with('success', 'Feedback berhasil dikirim! Terima kasih atas penilaian Anda.');
    }
}
