<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan daftar notifikasi untuk dosen.
     */
    public function index()
    {
        $user = auth()->user();

        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('dosen.notifikasi', compact('notifikasi'));
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     */
    public function tandaiBaca(Notifikasi $notifikasi)
    {
        abort_if($notifikasi->user_id !== auth()->id(), 403);
        
        $notifikasi->tandaiDibaca();

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Tandai semua notifikasi milik dosen sebagai dibaca.
     */
    public function bacaSemua()
    {
        Notifikasi::where('user_id', auth()->id())
            ->where('dibaca', false)
            ->update(['dibaca' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
