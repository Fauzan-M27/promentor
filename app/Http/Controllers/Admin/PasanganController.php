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
        $semua_pasangan = Pasangan::with(['mentor'])
            ->where('tahun_ajaran', '2026')
            ->get();
            
        $pasangan_grouped = $semua_pasangan->groupBy('mentor_id');

        // Mentor: mahasiswa yang pendaftarannya diterima
        $mentor_ids = User::where('role', 'mahasiswa')
            ->whereHas('pendaftaran', fn($q) => $q->where('status', 'diterima'))
            ->pluck('id');

        $mentor_tersedia = User::whereIn('id', $mentor_ids)->get();

        // Rekap mentee per mentor untuk tampilan daftar
        $mentee_per_mentor = Pasangan::where('tahun_ajaran', '2026')
            ->selectRaw('mentor_id, COUNT(*) as jumlah')
            ->groupBy('mentor_id')
            ->pluck('jumlah', 'mentor_id');

        return view('admin.pasangan', compact(
            'semua_pasangan', 'pasangan_grouped', 'mentor_tersedia', 'mentee_per_mentor'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mentor_id'      => ['required', 'exists:users,id'],
            'mentee_nama'    => ['required', 'string', 'max:255'],
            'mentee_nim'     => ['required', 'string', 'max:50'],
            'mentee_no_telp' => ['nullable', 'string', 'max:20'],
            'prodi'          => ['required', 'string'],
        ]);

        // Cegah duplikasi NIM yang sama untuk mentor yang sama
        $sudahAda = Pasangan::where('mentor_id', $request->mentor_id)
            ->where('mentee_nim', $request->mentee_nim)
            ->exists();

        if ($sudahAda) {
            return back()->withErrors(['mentee_nim' => 'Mentee dengan NIM ini sudah dipasangkan dengan mentor ini.']);
        }

        $pasangan = Pasangan::create([
            'mentor_id'      => $request->mentor_id,
            'mentee_nama'    => $request->mentee_nama,
            'mentee_nim'     => $request->mentee_nim,
            'mentee_no_telp' => $request->mentee_no_telp,
            'prodi'          => $request->prodi,
            'tahun_ajaran'   => '2026',
            'status'         => 'aktif',
        ]);

        // Kirim notifikasi ke Mentor saja (mentee tidak punya akun)
        $mentor = User::find($request->mentor_id);

        Notifikasi::send(
            $mentor->id,
            'Mentee Baru Ditugaskan',
            "Anda telah dipasangkan dengan mentee baru: {$request->mentee_nama} ({$request->mentee_nim}).",
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
