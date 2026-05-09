<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('mahasiswa.notifikasi', compact('notifikasi'));
    }

    public function tandaiBaca(Notifikasi $notifikasi)
    {
        abort_if($notifikasi->user_id !== auth()->id(), 403);
        $notifikasi->tandaiDibaca();
        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    public function bacaSemua()
    {
        Notifikasi::where('user_id', auth()->id())
            ->where('dibaca', false)
            ->update(['dibaca' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
