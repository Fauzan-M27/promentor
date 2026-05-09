<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pendaftaran;
use App\Models\Pasangan;
use App\Models\Feedback;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_user'      => User::count(),
            'total_admin'     => User::where('role', 'admin')->count(),
            'total_dosen'     => User::where('role', 'dosen')->count(),
            'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'total_pendaftar' => Pendaftaran::count(),
            'total_pasangan'  => Pasangan::count(),
            'total_feedback'  => Feedback::count(),
            'pending_seleksi' => Pendaftaran::where('status', 'pending')->count(),
        ];

        $userTerbaru = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'userTerbaru'));
    }
}
