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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizProgressController; // <--- CONTROLLER BARU DITAMBAHKAN

use Illuminate\Support\Facades\Artisan;

// GANTI ROUTE JALUR RAHASIA DENGAN INI
Route::get('/jalur-rahasia-db', function () {
    try {
        // PERINTAH INI AKAN MENGHAPUS SEMUA TABEL DAN MEMBUAT ULANG DARI NOL
        // Ini satu-satunya cara agar kolom 'name' benar-benar terbuat
        Artisan::call('migrate:fresh', [
            '--seed' => true, // Otomatis jalankan semua seeder
            '--force' => true
        ]);

        return "<h1>SUKSES! DATABASE SUDAH DI-RESET TOTAL.</h1>" .
            "<p>Tabel lama dihapus, tabel baru dibuat (ada kolom name), dan data admin diisi ulang.</p>" .
            "<p>Silakan <a href='/login'>LOGIN DISINI</a> dengan: <strong>admin@email.com</strong> / <strong>password123</strong></p>";

    } catch (\Exception $e) {
        return "<h1>GAGAL: " . $e->getMessage() . "</h1>";
    }
});

// Beranda/Home
Route::get('/', function () {
    return view('home', ['title' => 'Home']);
})->name('home');


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


// =====================
// AUTH USER ROUTE (Mental Health & Dashboard)
// =====================

Route::middleware('auth')->group(function () {

    // Halaman dashboard utama
    Route::get('/user/mental-health', [DashboardController::class, 'index'])->name('user.mental-health');

    // --- RUTE-RUTE APLIKASI MENTAL HEALTH ---

    // 1. PENGISIAN DATA DIRI
    Route::prefix('mental-health')->name('mental-health.')->group(function () {
        Route::get('/isi-data-diri', [DataDirisController::class, 'create'])->name('isi-data-diri');
        Route::post('/isi-data-diri', [DataDirisController::class, 'store'])->name('store-data-diri');
    });

    // 2. KUESIONER (LOGIKA BARU: STEP-BY-STEP)

    // Halaman Instruksi (Landing Page Kuesioner)
    Route::get('/mental-health/kuesioner', function () {
        return view('kuesioner', [
            'title' => 'Kuesioner Mental Health'
        ]);
    })->name('mental-health.kuesioner');

    // --- MULAI LOGIKA BARU DI SINI ---

    // Memulai sesi kuis (Cek sesi / buat baru)
    Route::get('/mental-health/kuesioner/start', [QuizProgressController::class, 'start'])
        ->name('quiz.start');

    // Menampilkan soal berdasarkan nomor urut (Step)
    Route::get('/mental-health/kuesioner/step/{step}', [QuizProgressController::class, 'show'])
        ->name('quiz.show');

    // Menyimpan jawaban per soal dan lanjut ke soal berikutnya
    Route::post('/mental-health/kuesioner/step/{step}', [QuizProgressController::class, 'storeAnswer'])
        ->name('quiz.store');

    // --- AKHIR LOGIKA BARU ---

    // 3. HASIL (Menggunakan Controller Lama untuk menampilkan hasil akhir)
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
    Route::get('/admin/karir/hasil/{hasil_tes}/list-pekerjaan-kategori', [KarirController::class, 'adminListPekerjaanKategori'])->name('admin.karir.list-pekerjaan-kategori');
});

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

Route::get('/cek-db', function () {
    echo "<h3>1. Cek Struktur Tabel 'admins'</h3>";
    try {
        // Lihat semua kolom yang ada
        $columns = Schema::getColumnListing('admins');
        echo "Kolom yang ditemukan: <b>" . implode(', ', $columns) . "</b><br>";

        if (!in_array('email', $columns)) {
            echo "<span style='color:red'>BAHAYA: Kolom 'email' TIDAK ADA! Login akan gagal.</span><br>";
        }

    } catch (\Exception $e) {
        die("Gagal akses tabel: " . $e->getMessage());
    }

    echo "<hr><h3>2. Cek Data Admin</h3>";
    $admin = Admin::first();

    if (!$admin) {
        echo "Tabel kosong. Belum ada data admin.";
    } else {
        // Tampilkan data apa adanya (tanpa memanggil 'nama' biar gak error)
        echo "Data ditemukan (ID: $admin->id)<br>";
        echo "Email: " . ($admin->email ?? 'KOSONG') . "<br>";
        echo "Username: " . ($admin->username ?? 'KOSONG') . "<br>";
        echo "Password Hash: " . substr($admin->password, 0, 15) . "... (Terisi)<br>";

        echo "<hr><h3>3. Simulasi Login</h3>";
        // Cek apakah password 'password123' cocok dengan hash di DB
        $passwordCheck = Hash::check('password123', $admin->password);

        if ($passwordCheck) {
            echo "<b style='color:green'>HASIL: Password COCOK!</b><br>";
            echo "Seharusnya Anda BISA login dengan:<br>";
            echo "Email: <b>" . $admin->email . "</b><br>";
            echo "Password: <b>password123</b>";
        } else {
            echo "<b style='color:red'>HASIL: Password TIDAK COCOK!</b><br>";
            echo "Seeder mungkin error saat hashing. Solusi: Jalankan 'php artisan migrate:fresh --seed' lagi.";
        }
    }
});


Route::get('/cek-password', function () {
    $admin = Admin::where('email', 'admin@email.com')->first();

    if (!$admin) {
        return "Admin tidak ditemukan di database.";
    }

    echo "Hash di Database: " . $admin->password . "<br>";

    // Cek manual
    if (Hash::check('password123', $admin->password)) {
        return "<h1 style='color:green'>PASSWORD COCOK! Masalah ada di config/session atau browser.</h1>";
    } else {
        return "<h1 style='color:red'>PASSWORD TIDAK COCOK! Seeder Anda mungkin salah.</h1>";
    }
});