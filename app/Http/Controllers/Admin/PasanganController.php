<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasangan;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class PasanganController extends Controller
{
    public function index()
    {
        // Ambil semua pasangan aktif, lalu group berdasarkan mentor
        $semua_pasangan = Pasangan::with(['mentor', 'mentee'])
            ->where('tahun_ajaran', '2026')
            ->get();
            
        $pasangan_grouped = $semua_pasangan->groupBy('mentor_id');

        // Mentor: mahasiswa yang pendaftarannya diterima
        $mentor_ids = User::where('role', 'mahasiswa')
            ->whereHas('pendaftaran', fn($q) => $q->where('status', 'diterima'))
            ->pluck('id');

        $mentor_tersedia = User::whereIn('id', $mentor_ids)->get();

        // Mentee: mahasiswa yang:
        //  1. Bukan mentor (tidak ada di mentor_ids)
        //  2. Belum punya mentor yang ditetapkan (tidak ada di tabel pasangan sebagai mentee)
        $sudah_jadi_mentee = Pasangan::where('tahun_ajaran', '2026')
            ->pluck('mentee_id');

        $mentee_tersedia = User::where('role', 'mahasiswa')
            ->whereNotIn('id', $mentor_ids)       // bukan mentor
            ->whereNotIn('id', $sudah_jadi_mentee) // belum dipasangkan
            ->get();

        // Rekap mentee per mentor untuk tampilan daftar
        $mentee_per_mentor = Pasangan::where('tahun_ajaran', '2026')
            ->selectRaw('mentor_id, COUNT(*) as jumlah')
            ->groupBy('mentor_id')
            ->pluck('jumlah', 'mentor_id');

        return view('admin.pasangan', compact(
            'semua_pasangan', 'pasangan_grouped', 'mentor_tersedia', 'mentee_tersedia', 'mentee_per_mentor'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mentor_id' => ['required', 'exists:users,id'],
            'mentee_id' => ['required', 'exists:users,id', 'different:mentor_id'],
            'prodi'     => ['required', 'string'],
        ]);

        // Cegah duplikasi pasangan yang sama
        $sudahAda = Pasangan::where('mentor_id', $request->mentor_id)
            ->where('mentee_id', $request->mentee_id)
            ->exists();

        if ($sudahAda) {
            return back()->withErrors(['mentee_id' => 'Pasangan ini sudah ada.']);
        }

        $pasangan = Pasangan::create([
            'mentor_id'    => $request->mentor_id,
            'mentee_id'    => $request->mentee_id,
            'prodi'        => $request->prodi,
            'tahun_ajaran' => '2026',
            'status'       => 'aktif',
        ]);

        // Kirim notifikasi ke Mentor
        $mentor = User::find($request->mentor_id);
        $mentee = User::find($request->mentee_id);

        Notifikasi::send(
            $mentor->id,
            'Mentee Baru Ditugaskan',
            "Anda telah dipasangkan dengan mentee baru: {$mentee->name} ({$mentee->nim}).",
            'sukses'
        );

        // Kirim notifikasi ke Mentee
        Notifikasi::send(
            $mentee->id,
            'Mentor Telah Ditentukan',
            "Anda telah dipasangkan dengan mentor: {$mentor->name} ({$mentor->prodi}).",
            'sukses'
        );

        return back()->with('success', 'Pasangan mentor-mentee berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, Pasangan $pasangan)
    {
        $request->validate([
            'status' => ['required', 'in:aktif,selesai,nonaktif'],
        ]);

        $pasangan->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status pasangan berhasil diperbarui.');
    }
}
