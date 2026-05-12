<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\User;

class LaporanController extends Controller
{
    public function index()
    {
        $stats = [
            'total'     => Pendaftaran::where('tahun_ajaran', '2026')->count(),
            'diterima'  => Pendaftaran::where('status', 'diterima')->count(),
            'ditolak'   => Pendaftaran::where('status', 'ditolak')->count(),
            'rata_skor' => round(Pendaftaran::whereNotNull('skor_total')->avg('skor_total') ?? 0),
        ];

        $distribusi = [
            ['range' => '90–100', 'count' => Pendaftaran::whereBetween('skor_total', [90, 100])->count()],
            ['range' => '80–89',  'count' => Pendaftaran::whereBetween('skor_total', [80, 89])->count()],
            ['range' => '70–79',  'count' => Pendaftaran::whereBetween('skor_total', [70, 79])->count()],
            ['range' => 'Di bawah 70', 'count' => Pendaftaran::where('skor_total', '<', 70)->whereNotNull('skor_total')->count()],
        ];
        $max_dist = max(array_column($distribusi, 'count')) ?: 1;

        $prodi_rekap = User::where('role', 'mahasiswa')
            ->whereHas('pendaftaran')
            ->selectRaw('prodi, count(*) as total')
            ->groupBy('prodi')
            ->get();

        $total_prodi = $prodi_rekap->sum('total') ?: 1;

        return view('admin.laporan', compact('stats', 'distribusi', 'max_dist', 'prodi_rekap', 'total_prodi'));
    }

    public function export()
    {
        $type = request('type', 'pdf');

        if ($type === 'excel') {
            return $this->exportExcel();
        }

        return $this->exportPdf();
    }

    private function exportExcel()
    {
        $pendaftar = Pendaftaran::with('mahasiswa')->where('tahun_ajaran', '2026')->get();

        $filename = 'laporan-rekrutmen-mentor-2026-' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($pendaftar) {
            $handle = fopen('php://output', 'w');

            // BOM untuk Excel agar karakter khusus terbaca
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom
            fputcsv($handle, ['No', 'Nama', 'NIM', 'Program Studi', 'Semester', 'IPK',
                              'Pengalaman Organisasi', 'Ketersediaan Waktu', 'Skor Total', 'Status']);

            foreach ($pendaftar as $i => $p) {
                fputcsv($handle, [
                    $i + 1,
                    $p->mahasiswa->name,
                    $p->mahasiswa->nim ?? '',
                    $p->mahasiswa->prodi ?? '',
                    $p->mahasiswa->semester ?? '',
                    $p->mahasiswa->ipk ? number_format($p->mahasiswa->ipk, 2) : '',
                    $p->pengalaman_organisasi ?? '',
                    $p->ketersediaan_waktu ? $p->ketersediaan_waktu . ' jam/minggu' : '',
                    $p->skor_total ?? '',
                    ucfirst($p->status),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPdf()
    {
        $stats = [
            'total'     => Pendaftaran::where('tahun_ajaran', '2026')->count(),
            'diterima'  => Pendaftaran::where('status', 'diterima')->count(),
            'ditolak'   => Pendaftaran::where('status', 'ditolak')->count(),
            'rata_skor' => round(Pendaftaran::whereNotNull('skor_total')->avg('skor_total') ?? 0),
        ];

        $pendaftar = Pendaftaran::with('mahasiswa')->where('tahun_ajaran', '2026')->get();

        return view('admin.laporan-print', compact('stats', 'pendaftar'));
    }
}
