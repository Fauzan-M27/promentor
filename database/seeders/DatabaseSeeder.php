<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pendaftaran;
use App\Models\Penilaian;
use App\Models\Pasangan;
use App\Models\Feedback;
use App\Models\Notifikasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== ADMIN =====
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@jtik.unm.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // ===== DOSEN =====
        $dosen = User::create([
            'name'     => 'Ainun Nida Rifqi, S.Psi., M.Psi.T',
            'email'    => 'ainun@jtik.unm.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'dosen',
        ]);

        // ===== MAHASISWA =====
        $ahmad = User::create([
            'name'     => 'Ahmad Reza',
            'email'    => 'ahmad@mhs.unm.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'mahasiswa',
            'nim'      => '220209001',
            'prodi'    => 'PTIK',
            'semester' => 5,
            'ipk'      => 3.72,
            'no_wa'    => '081234567890',
        ]);

        $siti = User::create([
            'name'     => 'Siti Fatimah',
            'email'    => 'siti@mhs.unm.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'mahasiswa',
            'nim'      => '220209008',
            'prodi'    => 'PTIK',
            'semester' => 5,
            'ipk'      => 3.91,
            'no_wa'    => '081234567891',
        ]);

        $budi = User::create([
            'name'     => 'Budi Nugroho',
            'email'    => 'budi@mhs.unm.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'mahasiswa',
            'nim'      => '220209015',
            'prodi'    => 'Teknik Komputer',
            'semester' => 4,
            'ipk'      => 3.45,
            'no_wa'    => '081234567892',
        ]);

        $dewi = User::create([
            'name'     => 'Dewi Kartika',
            'email'    => 'dewi@mhs.unm.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'mahasiswa',
            'nim'      => '220209022',
            'prodi'    => 'Teknik Komputer',
            'semester' => 4,
            'ipk'      => 3.21,
            'no_wa'    => '081234567893',
        ]);

        $rizky = User::create([
            'name'     => 'Rizky Maulana',
            'email'    => 'rizky@mhs.unm.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'mahasiswa',
            'nim'      => '220209031',
            'prodi'    => 'PTIK',
            'semester' => 5,
            'ipk'      => 3.68,
            'no_wa'    => '081234567894',
        ]);

        // Mentee (angkatan 2026)
        $fajar = User::create([
            'name'     => 'Muh. Fajar',
            'email'    => 'fajar@mhs.unm.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'mahasiswa',
            'nim'      => '230209010',
            'prodi'    => 'PTIK',
            'semester' => 1,
            'ipk'      => null,
        ]);

        $nurAini = User::create([
            'name'     => 'Nur Aini',
            'email'    => 'nuraini@mhs.unm.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'mahasiswa',
            'nim'      => '230209011',
            'prodi'    => 'PTIK',
            'semester' => 1,
            'ipk'      => null,
        ]);

        // ===== PENDAFTARAN =====
        $daftarAhmad = Pendaftaran::create([
            'user_id'              => $ahmad->id,
            'pengalaman_organisasi'=> 'Anggota HIMAPTIK UNM 2024-2025',
            'motivasi'             => 'Ingin berbagi ilmu dan membantu mahasiswa baru beradaptasi di lingkungan kampus.',
            'ketersediaan_waktu'   => '2-3',
            'status'               => 'review',
            'tahun_ajaran'         => '2026',
        ]);

        $daftarSiti = Pendaftaran::create([
            'user_id'              => $siti->id,
            'pengalaman_organisasi'=> 'Ketua Divisi Akademik HIMAPTIK 2024, Panitia PKKMB 2024',
            'motivasi'             => 'Membantu adik tingkat agar lebih mudah beradaptasi dan berprestasi.',
            'ketersediaan_waktu'   => '3-4',
            'status'               => 'diterima',
            'skor_total'           => 91,
            'tahun_ajaran'         => '2026',
        ]);

        $daftarBudi = Pendaftaran::create([
            'user_id'              => $budi->id,
            'pengalaman_organisasi'=> 'Anggota UKM Robotik',
            'motivasi'             => 'Mengembangkan kemampuan komunikasi dan kepemimpinan.',
            'ketersediaan_waktu'   => '1-2',
            'status'               => 'pending',
            'tahun_ajaran'         => '2026',
        ]);

        $daftarDewi = Pendaftaran::create([
            'user_id'              => $dewi->id,
            'pengalaman_organisasi'=> '-',
            'motivasi'             => 'Ingin membantu teman-teman yang kesulitan.',
            'ketersediaan_waktu'   => '1-2',
            'status'               => 'ditolak',
            'skor_total'           => 58,
            'tahun_ajaran'         => '2026',
        ]);

        $daftarRizky = Pendaftaran::create([
            'user_id'              => $rizky->id,
            'pengalaman_organisasi'=> 'Bendahara HIMAPTIK 2024',
            'motivasi'             => 'Termotivasi untuk membimbing dan menginspirasi mahasiswa baru.',
            'ketersediaan_waktu'   => '2-3',
            'status'               => 'review',
            'tahun_ajaran'         => '2026',
        ]);

        // ===== PENILAIAN =====
        Penilaian::create([
            'pendaftaran_id'       => $daftarAhmad->id,
            'dosen_id'             => $dosen->id,
            'kompetensi_akademik'  => 22,
            'kemampuan_komunikasi' => 18,
            'kepemimpinan'         => 20,
            'integritas_komitmen'  => 23,
            'catatan'              => 'Kandidat potensial, perlu pengembangan kemampuan komunikasi.',
        ]);

        Penilaian::create([
            'pendaftaran_id'       => $daftarSiti->id,
            'dosen_id'             => $dosen->id,
            'kompetensi_akademik'  => 25,
            'kemampuan_komunikasi' => 23,
            'kepemimpinan'         => 22,
            'integritas_komitmen'  => 21,
            'catatan'              => 'Sangat layak diterima. IPK tinggi dan berpengalaman.',
        ]);

        Penilaian::create([
            'pendaftaran_id'       => $daftarDewi->id,
            'dosen_id'             => $dosen->id,
            'kompetensi_akademik'  => 14,
            'kemampuan_komunikasi' => 12,
            'kepemimpinan'         => 17,
            'integritas_komitmen'  => 15,
            'catatan'              => 'Belum memenuhi syarat minimum.',
        ]);

        // ===== PASANGAN =====
        $pasanganSitiFajar = Pasangan::create([
            'mentor_id'    => $siti->id,
            'mentee_id'    => $fajar->id,
            'prodi'        => 'PTIK',
            'tahun_ajaran' => '2026',
            'status'       => 'aktif',
        ]);

        $pasanganSitiAini = Pasangan::create([
            'mentor_id'    => $siti->id,
            'mentee_id'    => $nurAini->id,
            'prodi'        => 'PTIK',
            'tahun_ajaran' => '2026',
            'status'       => 'aktif',
        ]);

        // ===== FEEDBACK =====
        Feedback::create([
            'mentee_id'            => $fajar->id,
            'mentor_id'            => $siti->id,
            'pasangan_id'          => $pasanganSitiFajar->id,
            'bintang_ketersediaan' => 5,
            'bintang_penjelasan'   => 5,
            'bintang_empati'       => 5,
            'bintang_komitmen'     => 5,
            'hal_positif'          => 'Mentor sangat sabar dan mudah dihubungi. Selalu membantu saat saya kesulitan memahami materi pemrograman dasar.',
            'saran'                => 'Tidak ada saran, sudah sangat baik.',
            'rekomendasi'          => 'ya',
            'tahun_ajaran'         => '2026',
        ]);

        Feedback::create([
            'mentee_id'            => $nurAini->id,
            'mentor_id'            => $siti->id,
            'pasangan_id'          => $pasanganSitiAini->id,
            'bintang_ketersediaan' => 4,
            'bintang_penjelasan'   => 4,
            'bintang_empati'       => 5,
            'bintang_komitmen'     => 4,
            'hal_positif'          => 'Penjelasan cukup jelas namun kadang sulit dihubungi di luar jam pertemuan.',
            'saran'                => 'Lebih responsif di luar jam pertemuan.',
            'rekomendasi'          => 'ya',
            'tahun_ajaran'         => '2026',
        ]);

        // ===== NOTIFIKASI =====
        Notifikasi::create([
            'user_id' => $ahmad->id,
            'judul'   => 'Self Assessment Dikirim',
            'pesan'   => 'Self Assessment Anda telah berhasil dikirim dan sedang ditinjau oleh dosen pengelola.',
            'tipe'    => 'sukses',
            'dibaca'  => false,
        ]);

        Notifikasi::create([
            'user_id' => $ahmad->id,
            'judul'   => 'Status Pendaftaran',
            'pesan'   => 'Pendaftaran Anda sedang dalam tahap seleksi administrasi. Pantau terus statusnya.',
            'tipe'    => 'info',
            'dibaca'  => false,
        ]);

        Notifikasi::create([
            'user_id' => $ahmad->id,
            'judul'   => 'Pengingat Feedback',
            'pesan'   => 'Jangan lupa mengisi Feedback untuk mentor Anda sebelum 10 Juni 2026.',
            'tipe'    => 'peringatan',
            'dibaca'  => true,
        ]);

        Notifikasi::create([
            'user_id' => $ahmad->id,
            'judul'   => 'Rekrutmen Dibuka',
            'pesan'   => 'Rekrutmen Mentor PRO-MENTOR 2026 resmi dibuka. Daftarkan diri sebelum 20 Mei 2026.',
            'tipe'    => 'info',
            'dibaca'  => true,
        ]);
    }
}
