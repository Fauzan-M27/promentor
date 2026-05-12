<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;

class FeedbackController extends Controller
{
    public function index()
    {
        $stats = [
            'total_feedback' => Feedback::where('tahun_ajaran', '2026')->count(),
            'rata_bintang'   => round(Feedback::where('tahun_ajaran', '2026')->avg('rata_rata') ?? 0, 1),
        ];

        // Performa per mentor
        $mentor_list = User::where('role', 'mahasiswa')
            ->whereHas('pendaftaran', fn($q) => $q->where('status', 'diterima'))
            ->withCount('feedbackDiterima')
            ->with('feedbackDiterima')
            ->get()
            ->map(function ($mentor) {
                $mentor->rata_feedback = round($mentor->feedbackDiterima->avg('rata_rata') ?? 0, 1);
                return $mentor;
            })
            ->sortByDesc('rata_feedback');

        $mentor_terbaik = $mentor_list->first();

        // Komentar terbaru
        $komentar_terbaru = Feedback::with(['mentee', 'mentor'])
            ->where('tahun_ajaran', '2026')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.feedback', compact('stats', 'mentor_list', 'mentor_terbaik', 'komentar_terbaru'));
    }

    public function show(User $mentor)
    {
        $feedbacks = Feedback::with('mentee')
            ->where('mentor_id', $mentor->id)
            ->latest()
            ->get()
            ->map(function ($fb) {
                return [
                    'mentee_name'          => $fb->mentee->name ?? '—',
                    'mentee_nim'           => $fb->mentee->nim  ?? '—',
                    'rata_rata'            => round($fb->rata_rata, 2),
                    'bintang_ketersediaan' => $fb->bintang_ketersediaan,
                    'bintang_penjelasan'   => $fb->bintang_penjelasan,
                    'bintang_empati'       => $fb->bintang_empati,
                    'bintang_komitmen'     => $fb->bintang_komitmen,
                    'hal_positif'          => $fb->hal_positif,
                    'saran'                => $fb->saran,
                    'rekomendasi'          => $fb->rekomendasi,
                    'tanggal'              => $fb->created_at->format('d M Y'),
                ];
            });

        return response()->json([
            'mentor_name'    => $mentor->name,
            'mentor_nim'     => $mentor->nim,
            'rata_feedback'  => round($feedbacks->avg('rata_rata'), 2),
            'total_feedback' => $feedbacks->count(),
            'feedbacks'      => $feedbacks->values(),
        ]);
    }
}
