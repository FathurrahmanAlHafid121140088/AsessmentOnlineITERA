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

// Beranda/Home
Route::get('/home', function () {
    return view('home', ['title' => 'Home']);
})->name('home');

// Mental Health: Form Data Diri
Route::prefix('mental-health')->name('mental-health.')->group(function () {
    Route::get('/isi-data-diri', [DataDirisController::class, 'create'])->name('isi-data-diri');
    Route::post('/isi-data-diri', [DataDirisController::class, 'store'])->name('store-data-diri');
});

// Resource routes


// =====================
// AUTH ADMIN ROUTE
// =====================

// Form Login Admin
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');

// Proses Login Admin
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.process');

// Logout Admin
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

// Route khusus admin yang hanya bisa diakses setelah login sebagai admin:
Route::middleware([AdminAuth::class])->group(function () {
    // Dashboard + hasil kuesioner + search + pagination
    Route::get('/admin/mental-health', [HasilKuesionerCombinedController::class, 'index'])
        ->name('admin.home');

    // Statistik total users
    Route::get('/statistik/total-users', [StatistikController::class, 'totalUsers'])
        ->name('statistik.total-users');

    // Hapus hasil
    Route::delete('/admin/mental-health/{id}', [HasilKuesionerCombinedController::class, 'destroy'])
        ->name('admin.delete');
    Route::get('/admin', function () {
        $totalUsers = \App\Models\HasilKuesioner::distinct('nim')->count('nim');
        $totalTes = \App\Models\HasilKuesioner::count();
        return view('Admin', [
            'title' => 'Admin',
            'totalUsers' => $totalUsers,
            'totalTes' => $totalTes,
        ]);
    });
    Route::get('/admin/mental-health/export', [App\Http\Controllers\HasilKuesionerCombinedController::class, 'exportExcel'])->name('admin.export.excel');
});
Route::get('/mental-health/kuesioner', function () {
    return view('kuesioner', [
        'title' => 'Kuesioner Mental Health',
        'nim' => session('nim')
    ]);
})->middleware(CheckNimSession::class)->name('mental-health.kuesioner');

Route::get('/mental-health/data-diri', [DataDirisController::class, 'create'])->name('mental-health.data-diri');
Route::post('/mental-health/data-diri', [DataDirisController::class, 'store'])->name('mental-health.store-data-diri');

// submit
Route::post('/mental-health/kuesioner', [HasilKuesionerController::class, 'store'])
    ->name('mental-health.kuesioner.submit');

// hasil
Route::get('/mental-health/hasil', [HasilKuesionerController::class, 'showLatest'])
    ->name('mental-health.hasil');


Route::get('/search', [SearchController::class, 'search'])->name('search');


// =====================
// ROUTES USER LAIN (Bebas diakses)
// =====================

Route::get('/user/mental-health', function () {
    return view('user-mental-health', ['title' => 'ANLOGY | Institut Teknologi Sumatera']);
});
Route::get('/register', function () {
    return view('register', ['title' => 'Register']);
});

Route::get('/lupa-password', function () {
    return view('lupa-password', ['title' => 'Lupa Password']);
});

Route::get('/mental-health', function () {
    return view('mental-health', ['title' => 'Mental Health']);
});

// ---------------------- KARIR ROUTES ----------------------

// Halaman home karir
Route::get('/karir-home', function () {
    return view('karir-home');
})->name('karir.home');

// Form input data diri
Route::get('/karir-datadiri', [KarirController::class, 'showDataDiri'])->name('karir.datadiri');
Route::post('/karir-datadiri', [KarirController::class, 'storeDataDiri'])->name('karir.datadiri.store');

// Form RMIB berdasarkan ID data diri
Route::get('/karir-form/{id}', [KarirController::class, 'showForm'])->name('karir.form');
Route::post('/karir-form/{id}', [KarirController::class, 'storeForm'])->name('karir.form.store');

// Interpretasi hasil tes untuk user
Route::get('/karir-interpretasi/{id_hasil}', [KarirController::class, 'interpretasi'])->name('karir.interpretasi');

// ==============================
// ADMIN
// ==============================

// Admin - daftar semua peserta
Route::get('/admin/karir', [KarirController::class, 'adminIndex'])->name('admin.karir.index');

// Admin - detail hasil peserta + analisis circular shift
Route::get('/admin/karir/{id_hasil}', [KarirController::class, 'adminDetail'])->name('admin.karir.detail');
