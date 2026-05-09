<?php

namespace App\Http\Controllers\Dosen;

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

        return view('dosen.feedback', compact('stats', 'mentor_list', 'mentor_terbaik', 'komentar_terbaru'));
    }
}
