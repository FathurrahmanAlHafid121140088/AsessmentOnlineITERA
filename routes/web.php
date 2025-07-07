<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DataDirisController;
use App\Http\Controllers\RiwayatKeluhansController;
use App\Http\Controllers\JawabanController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Middleware\AdminAuth;
use App\Http\Controllers\HasilKuesionerCombinedController;
use App\Http\Controllers\HasilKuesionerController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatistikController;

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

});



Route::get('/mental-health/data-diri', [DataDirisController::class, 'create'])->name('mental-health.data-diri');
Route::post('/mental-health/data-diri', [DataDirisController::class, 'store'])->name('mental-health.store-data-diri');

Route::get('/mental-health/kuesioner', function () {
    return view('kuesioner', [
        'nim' => session('nim')
    ]);
})->name('mental-health.kuesioner');

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

Route::get('/karir-interpretasi', function () {
    return view('karir-interpretasi', ['title' => 'Hasil Interpretasi Anda']);
});

Route::get('/admin-karir', function () {
    return view('admin-karir', ['title' => 'Tampilan Admin RMIB']);
});