<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\Auth\AuthController; // Tambahkan ini

Route::get('/home', function () {
    return view('home', ['title' => 'Home']);
});

// Route untuk logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/login', function () {
    return view('login', ['title' => 'Login']);
});

Route::get('/register', function () {
    return view('register', ['title' => 'Register']);
});

Route::get('/lupa-password', function () {
    return view('lupa-password', ['title' => 'Lupa Password']);
});
Route::get('/admin', function () {
    return view('admin-home', ['title' => 'Admin']);
});

Route::get('/mental-health', function () {
    return view('mental-health', ['title' => 'Mental Health']);
});

Route::get('/mental-health/isi-data-diri', function () {
    return view('isi-data-diri', ['title' => 'Isi Data Diri']);
});

Route::get('/mental-health/kuesioner', function () {
    return view('kuesioner', ['title' => 'Kuesioner MHI-38']);
});

Route::get('/mental-health/hasil', function () {
    return view('hasil', ['title' => 'Hasil MHI-38']);
});

Route::get('/karir-kuesioner', function () {
    return view('karir-kuesioner', ['title' => 'Kuesioner RMIB']);
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