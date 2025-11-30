<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\DataDirisController;
use App\Http\Middleware\CheckNimSession;
use App\Http\Controllers\RiwayatKeluhansController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Middleware\AdminAuth;
use App\Http\Controllers\HasilKuesionerCombinedController;
use App\Http\Controllers\HasilKuesionerController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\KarirController;
use App\Http\Controllers\DashboardController; // <-- Jangan lupa tambahkan ini di atas
use App\Http\Controllers\AuthController;



// Beranda/Home
Route::get('/', function () {
    return view('home', ['title' => 'Home']);
})->name('home');

// Resource routes


// =====================
// AUTH ADMIN ROUTE
// =====================

Route::middleware('guest')->group(function () {
    // Form Login Admin
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');

    // Proses Login Admin
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('login.process');
});

// Logout Admin
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

// Route khusus admin yang hanya bisa diakses setelah login sebagai admin:
Route::middleware([AdminAuth::class])->group(function () {
    // Dashboard Admin Utama - Mental Health Admin (daftar hasil kuesioner + search + pagination)
    Route::get('/admin', [HasilKuesionerCombinedController::class, 'index'])
        ->name('admin.home');

    // Halaman Mental Health Admin (alias untuk admin.home untuk backward compatibility)
    Route::get('/admin/mental-health', [HasilKuesionerCombinedController::class, 'index'])
        ->name('admin.mental-health');

    // Statistik total users
    Route::get('/statistik/total-users', [StatistikController::class, 'totalUsers'])
        ->name('statistik.total-users');

    // Detail jawaban kuesioner
    Route::get('/admin/mental-health/{id}/detail', [HasilKuesionerCombinedController::class, 'showDetail'])
        ->name('admin.mental-health.detail');

    // Hapus hasil
    Route::delete('/admin/mental-health/{id}', [HasilKuesionerCombinedController::class, 'destroy'])
        ->name('admin.delete');

    // Export Excel
    Route::get('/admin/mental-health/export', [App\Http\Controllers\HasilKuesionerCombinedController::class, 'exportExcel'])
        ->name('admin.export.excel');
});
Route::middleware('auth')->group(function () {

    // Halaman dashboard utama
    Route::get('/user/mental-health', [DashboardController::class, 'index'])->name('user.mental-health');

    // --- RUTE-RUTE APLIKASI MENTAL HEALTH ---

    // Rute untuk alur pengisian data diri (dikelompokkan)
    Route::prefix('mental-health')->name('mental-health.')->group(function () {
        Route::get('/isi-data-diri', [DataDirisController::class, 'create'])->name('isi-data-diri');

        // Submit Data Diri
        Route::post('/isi-data-diri', [DataDirisController::class, 'store'])
            ->name('store-data-diri');
    });

    // Rute untuk menampilkan dan mengirim kuesioner
    Route::get('/mental-health/kuesioner', function () {       
        // Data 'nim' tidak perlu lagi dikirim dari sini, karena bisa diakses
        // langsung di view atau controller menggunakan Auth::user()->nim
        return view('kuesioner', [
            'title' => 'Kuesioner Mental Health'
        ]);
    })->name('mental-health.kuesioner');

    // Submit Kuesioner
    Route::post('/mental-health/kuesioner', [HasilKuesionerController::class, 'store'])
        ->name('mental-health.kuesioner.submit');

    // Rute untuk menampilkan hasil kuesioner terakhir
    Route::get('/mental-health/hasil', [HasilKuesionerController::class, 'showLatest'])->name('mental-health.hasil');

});

Route::get('/search', [SearchController::class, 'search'])->name('search');


// =====================
// ROUTES USER LAIN (Bebas diakses)
// =====================


// Rute untuk mengarahkan pengguna ke halaman login Google
Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');

// Google Callback
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])
    ->name('google.callback');

Route::get('/mental-health', function () {
    return view('mental-health', ['title' => 'Mental Health']);
})->name('mental-health.index');

// =========================================================================
// KARIR ROUTES (TES PEMINATAN KARIR RMIB)
// =========================================================================

// Halaman home karir (info page - TIDAK PERLU LOGIN) -> GET /karir
// User bisa melihat informasi tentang tes RMIB sebelum login
Route::get('/karir', function () {
    // Set session flag bahwa user sudah mengunjungi karir-home
    session()->put('visited_karir_home', true);
    return view('karir-home');
})->name('karir.home');

// KARIR ROUTES YANG MEMERLUKAN LOGIN
Route::prefix('karir')->name('karir.')->middleware(['auth'])->group(function () {

    // Dashboard user peminatan karir -> GET /karir/dashboard
    Route::get('/dashboard', [KarirController::class, 'userDashboard'])->name('dashboard');

    // Menampilkan form data diri -> GET /karir/data-diri
    // Middleware 'check.karir.home' memastikan user sudah mengunjungi karir.home terlebih dahulu
    Route::get('/data-diri', [KarirController::class, 'showDataDiri'])
        ->middleware('check.karir.home')
        ->name('datadiri.form');

    // Menyimpan data diri dari form -> POST /karir/data-diri
    Route::post('/data-diri', [KarirController::class, 'storeDataDiri'])->name('datadiri.store');

    // Menampilkan halaman tes RMIB -> GET /karir/tes/{data_diri}
    Route::get('/tes/{data_diri}', [KarirController::class, 'showTesForm'])->name('tes.form');

    // Menyimpan jawaban tes -> POST /karir/tes/{data_diri}
    Route::post('/tes/{data_diri}', [KarirController::class, 'storeJawaban'])->name('tes.store');

    // Menampilkan halaman hasil/interpretasi -> GET /karir/hasil/{hasil_tes}
    Route::get('/hasil/{hasil_tes}', [KarirController::class, 'showInterpretasi'])->name('hasil');
});


// =========================================================================
// ADMIN ROUTES (MANAJEMEN TES KARIR) - WAJIB LOGIN ADMIN
// =========================================================================

Route::middleware([AdminAuth::class])->group(function () {

    // Halaman utama admin karir (daftar peserta) -> GET /admin/admin-karir
    Route::get('/admin/admin-karir', [KarirController::class, 'adminIndex'])->name('admin.karir.index');

    // Halaman detail hasil peserta -> GET /admin/karir/detail/{hasil_tes}
    Route::get('/admin/karir/detail/{hasil_tes}', [KarirController::class, 'adminDetail'])->name('admin.karir.detail');

    // Halaman list pekerjaan RMIB -> GET /admin/karir/list-pekerjaan/{hasil_tes}
    Route::get('/admin/karir/list-pekerjaan/{hasil_tes}', [KarirController::class, 'adminListPekerjaan'])->name('admin.karir.list-pekerjaan');

    // Halaman Data Provinsi -> GET /admin/peminatan-karir/provinsi
    Route::get('/admin/peminatan-karir/provinsi', [KarirController::class, 'adminProvinsi'])->name('admin.karir.provinsi');

    // Halaman Data Program Studi -> GET /admin/peminatan-karir/program-studi
    Route::get('/admin/peminatan-karir/program-studi', [KarirController::class, 'adminProgramStudi'])->name('admin.karir.program-studi');

    // Hapus hasil tes -> DELETE /admin/karir/{hasil_tes}
    Route::delete('/admin/karir/{hasil_tes}', [KarirController::class, 'destroy'])->name('admin.karir.delete');

    // Export Excel -> GET /admin/karir/export
    Route::get('/admin/karir/export', [KarirController::class, 'exportExcel'])->name('admin.karir.export.excel');
});