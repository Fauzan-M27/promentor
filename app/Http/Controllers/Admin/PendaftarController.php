<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendaftarController extends Controller
{
    /**
     * Menampilkan daftar semua pendaftar mentor
     */
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['mahasiswa', 'penilaian', 'selfAssessment']);

        // Filter berdasarkan tahun ajaran
        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        } else {
            // Default tahun ajaran saat ini
            $query->where('tahun_ajaran', '2026');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan prodi
        if ($request->filled('prodi')) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('prodi', $request->prodi));
        }

        // Pencarian nama atau NIM
        if ($request->filled('cari')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->cari.'%')
                  ->orWhere('nim', 'like', '%'.$request->cari.'%');
            });
        }

        $pendaftar = $query->latest()->paginate(15);

        // Statistik
        $stats = [
            'total' => Pendaftaran::where('tahun_ajaran', $request->get('tahun_ajaran', '2026'))->count(),
            'pending' => Pendaftaran::where('tahun_ajaran', $request->get('tahun_ajaran', '2026'))
                ->where('status', 'pending')->count(),
            'review' => Pendaftaran::where('tahun_ajaran', $request->get('tahun_ajaran', '2026'))
                ->where('status', 'review')->count(),
            'diterima' => Pendaftaran::where('tahun_ajaran', $request->get('tahun_ajaran', '2026'))
                ->where('status', 'diterima')->count(),
            'ditolak' => Pendaftaran::where('tahun_ajaran', $request->get('tahun_ajaran', '2026'))
                ->where('status', 'ditolak')->count(),
        ];

        // Daftar tahun ajaran yang tersedia
        $tahunAjaranList = Pendaftaran::select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        return view('admin.pendaftar.index', compact('pendaftar', 'stats', 'tahunAjaranList'));
    }

    /**
     * Menampilkan detail pendaftar beserta file-file yang diupload
     */
    public function show(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load(['mahasiswa', 'penilaian', 'selfAssessment']);

        return view('admin.pendaftar.show', compact('pendaftaran'));
    }

    /**
     * Download file yang diupload pendaftar
     */
    public function downloadFile(Pendaftaran $pendaftaran, $fileType)
    {
        $allowedTypes = ['khs', 'sertifikat_organisasi', 'sertifikat_mentoring', 'motivation_letter'];
        
        if (!in_array($fileType, $allowedTypes)) {
            abort(404, 'Tipe file tidak valid');
        }

        $filePath = $pendaftaran->$fileType;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($filePath);
    }

    /**
     * Preview file PDF yang diupload pendaftar
     */
    public function previewFile(Pendaftaran $pendaftaran, $fileType)
    {
        $allowedTypes = ['khs', 'sertifikat_organisasi', 'sertifikat_mentoring', 'motivation_letter'];
        
        if (!in_array($fileType, $allowedTypes)) {
            abort(404, 'Tipe file tidak valid');
        }

        $filePath = $pendaftaran->$fileType;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $file = Storage::disk('public')->get($filePath);
        $type = Storage::disk('public')->mimeType($filePath);

        return response($file, 200)->header('Content-Type', $type);
    }
}
