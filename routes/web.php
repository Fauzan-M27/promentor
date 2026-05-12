<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Redirect root ke dashboard atau login
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin())      return redirect()->route('admin.dashboard');
        if ($user->isDosen())      return redirect()->route('dosen.beranda');
        return redirect()->route('mahasiswa.beranda');
    }
    return redirect()->route('login');
});

// ============================================================
// DOSEN ROUTES
// ============================================================
Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {

        Route::get('/beranda', [\App\Http\Controllers\Dosen\BerandaController::class, 'index'])
            ->name('beranda');

        Route::get('/pendaftar', [\App\Http\Controllers\Dosen\PendaftarController::class, 'index'])
            ->name('pendaftar');

        Route::get('/seleksi/{pendaftaran}', [\App\Http\Controllers\Dosen\SeleksiController::class, 'show'])
            ->name('seleksi.show');
        Route::post('/seleksi/{pendaftaran}', [\App\Http\Controllers\Dosen\SeleksiController::class, 'store'])
            ->name('seleksi.store');
        Route::patch('/seleksi/{pendaftaran}/status', [\App\Http\Controllers\Dosen\SeleksiController::class, 'updateStatus'])
            ->name('seleksi.status');



        Route::get('/notifikasi', [\App\Http\Controllers\Dosen\NotifikasiController::class, 'index'])
            ->name('notifikasi');
        Route::post('/notifikasi/{notifikasi}/baca', [\App\Http\Controllers\Dosen\NotifikasiController::class, 'tandaiBaca'])
            ->name('notifikasi.baca');
        Route::post('/notifikasi/baca-semua', [\App\Http\Controllers\Dosen\NotifikasiController::class, 'bacaSemua'])
            ->name('notifikasi.baca-semua');
    });

// ============================================================
// MAHASISWA ROUTES
// ============================================================
Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {

        Route::get('/beranda', [\App\Http\Controllers\Mahasiswa\BerandaController::class, 'index'])
            ->name('beranda');

        Route::get('/daftar', [\App\Http\Controllers\Mahasiswa\DaftarController::class, 'create'])
            ->name('daftar.create');
        Route::post('/daftar', [\App\Http\Controllers\Mahasiswa\DaftarController::class, 'store'])
            ->name('daftar.store');

        Route::get('/seleksi', [\App\Http\Controllers\Mahasiswa\SeleksiController::class, 'index'])
            ->name('seleksi');

        Route::get('/self-assessment', [\App\Http\Controllers\Mahasiswa\SelfAssessmentController::class, 'create'])
            ->name('self-assessment.create');
        Route::post('/self-assessment', [\App\Http\Controllers\Mahasiswa\SelfAssessmentController::class, 'store'])
            ->name('self-assessment.store');

        Route::get('/feedback', [\App\Http\Controllers\Mahasiswa\FeedbackController::class, 'create'])
            ->name('feedback.create');
        Route::post('/feedback', [\App\Http\Controllers\Mahasiswa\FeedbackController::class, 'store'])
            ->name('feedback.store');

        Route::get('/notifikasi', [\App\Http\Controllers\Mahasiswa\NotifikasiController::class, 'index'])
            ->name('notifikasi');
        Route::post('/notifikasi/{notifikasi}/baca', [\App\Http\Controllers\Mahasiswa\NotifikasiController::class, 'tandaiBaca'])
            ->name('notifikasi.baca');
        Route::post('/notifikasi/baca-semua', [\App\Http\Controllers\Mahasiswa\NotifikasiController::class, 'bacaSemua'])
            ->name('notifikasi.baca-semua');
    });

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('/users', \App\Http\Controllers\Admin\UserController::class)
            ->except(['show']);

        // Fitur yang dipindahkan dari Dosen
        Route::get('/pasangan', [\App\Http\Controllers\Admin\PasanganController::class, 'index'])
            ->name('pasangan');
        Route::post('/pasangan', [\App\Http\Controllers\Admin\PasanganController::class, 'store'])
            ->name('pasangan.store');
        Route::patch('/pasangan/{pasangan}/status', [\App\Http\Controllers\Admin\PasanganController::class, 'updateStatus'])
            ->name('pasangan.status');

        Route::get('/feedback', [\App\Http\Controllers\Admin\FeedbackController::class, 'index'])
            ->name('feedback');

        Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])
            ->name('laporan');
        Route::get('/laporan/export', [\App\Http\Controllers\Admin\LaporanController::class, 'export'])
            ->name('laporan.export');
    });

// ============================================================
// PROFILE (shared)
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Fallback logout GET (mencegah MethodNotAllowedHttpException)
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});
