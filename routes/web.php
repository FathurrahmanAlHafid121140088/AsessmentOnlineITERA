<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DataDirisController;
use App\Http\Controllers\RiwayatKeluhansController;
use App\Http\Controllers\JawabanController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Middleware\AdminAuth;
use App\Http\Controllers\HasilKuesionerController;



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
Route::resources([
    'admins' => AdminsController::class,
    'users' => UsersController::class,
    'data-diris' => DataDirisController::class,
    'riwayat-keluhans' => RiwayatKeluhansController::class,
    'jawabans' => JawabanController::class,
]);

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

// Route khusus admin yang hanya bisa diakses setelah login sebagai admin:
Route::middleware([AdminAuth::class])->group(function () {
    Route::get('/admin', [HasilKuesionerController::class, 'index'])->name('admin.home');
});
;

// =====================
// ROUTES USER LAIN (Bebas diakses)
// =====================

Route::get('/register', function () {
    return view('register', ['title' => 'Register']);
});

Route::get('/lupa-password', function () {
    return view('lupa-password', ['title' => 'Lupa Password']);
});

Route::get('/mental-health', function () {
    return view('mental-health', ['title' => 'Mental Health']);
});

Route::get('/mental-health/kuesioner', function () {
    return view('kuesioner', ['title' => 'Kuesioner MHI-38']);
})->name('mental-health.kuesioner');

Route::get('/mental-health/hasil', function () {
    return view('hasil', ['title' => 'Hasil MHI-38']);
});

// routes untuk Karir
Route::get('/karir-home', function () {
    return view('karir-home', ['title' => 'Peminatan-Karir']);
});

Route::get('/karir-datadiri', function () {
    return view('karir-datadiri', ['title' => 'Isi Identitas']);
});

Route::get('/karir-form', function () {
    return view('karir-form', ['title' => 'Form Peminatan-Karir']);
});

Route::get('/karir-hitung', function () {
    return view('karir-hitung', ['title' => 'Hasil Hitung RMIB']);
});
