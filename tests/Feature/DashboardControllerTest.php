<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Users;
use App\Models\HasilKuesioner;
use App\Models\RiwayatKeluhans;
use App\Models\DataDiris;
use Carbon\Carbon; // <-- Pastikan Carbon di-import
use Illuminate\Support\Facades\Auth; // <-- Tambahkan import Auth Facade

/**
 * @mixin \Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication
 */
class DashboardControllerTest extends TestCase
{
    use RefreshDatabase; // <-- Wajib ada untuk tes yang mengubah database

    /**
     * Skenario 1: Pengguna mengakses dashboard tanpa login.
     * Harusnya dialihkan ke halaman login.
     */
    public function test_pengguna_tidak_login()
    {
        // Akses dashboard tanpa login
        $response = $this->get('/user/mental-health');

        // Pastikan dialihkan (status 302) ke halaman login
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Skenario 2: Pengguna login tapi belum punya riwayat tes.
     * Halaman harus tampil, tapi dengan data kosong.
     */
    public function test_pengguna_login_tanpa_riwayat_tes()
    {
        // Buat user baru
        $user = Users::factory()->create([
            'nim' => '123456789'
        ]);

        // Login sebagai user tersebut menggunakan Auth Facade
        Auth::login($user); // <-- PERUBAHAN DI SINI

        // Akses dashboard
        $response = $this->get('/user/mental-health');

        // Pastikan halaman berhasil dimuat
        $response->assertStatus(200);
        $response->assertViewIs('user-mental-health'); // Pastikan view yang benar dimuat

        // Pastikan data yang dikirim ke view berada dalam kondisi "kosong"
        $response->assertViewHas('jumlahTesDiikuti', 0);
        $response->assertViewHas('kategoriTerakhir', 'Belum ada tes');
        $response->assertViewHas('riwayatTes', fn($data) => $data->count() == 0);
        $response->assertViewHas('chartLabels', fn($data) => $data->isEmpty());
        $response->assertViewHas('chartScores', fn($data) => $data->isEmpty());
    }

    /**
     * Skenario 3: Pengguna login dan sudah punya riwayat tes.
     * Halaman harus tampil dengan data yang benar (statistik, chart, tabel paginasi).
     */
    public function test_pengguna_login_dengan_riwayat_tes()
    {
        // --- 1. Persiapan Data ---
        $user = Users::factory()->create([
            'nim' => '123456789'
        ]);

        DataDiris::factory()->create([
            'nim' => $user->nim,
            'nama' => 'Nama Tes Pengguna',
            'program_studi' => 'Teknik Informatika'
        ]);

        // Buat 2 data keluhan pada waktu yang berbeda
        RiwayatKeluhans::factory()->create([
            'nim' => $user->nim,
            'keluhan' => 'Keluhan pertama',
            'lama_keluhan' => '1 Bulan',
            'created_at' => Carbon::now()->subDays(10) // 10 hari lalu
        ]);
        RiwayatKeluhans::factory()->create([
            'nim' => $user->nim,
            'keluhan' => 'Keluhan kedua',
            'lama_keluhan' => '2 Bulan',
            'created_at' => Carbon::now()->subDays(2) // 2 hari lalu
        ]);

        // Buat 2 hasil tes pada waktu yang berbeda
        // Tes Pertama (Terlama)
        $tesPertama = HasilKuesioner::factory()->create([
            'nim' => $user->nim,
            'kategori' => 'Cukup Sehat',
            'total_skor' => 50,
            'created_at' => Carbon::now()->subDays(9) // 9 hari lalu
        ]);
        // Tes Kedua (Terbaru)
        $tesKedua = HasilKuesioner::factory()->create([
            'nim' => $user->nim,
            'kategori' => 'Sehat',
            'total_skor' => 70,
            'created_at' => Carbon::now()->subDays(1) // 1 hari lalu
        ]);

        // --- 2. Aksi ---
        // Login sebagai user menggunakan Auth Facade
        Auth::login($user); // <-- PERUBAHAN DI SINI

        // Akses dashboard
        $response = $this->get('/user/mental-health');

        // --- 3. Pengecekan (Asserts) ---
        $response->assertStatus(200);
        $response->assertViewIs('user-mental-health');

        // Cek statistik
        $response->assertViewHas('jumlahTesDiikuti', 2);
        // Kategori terakhir diambil dari $semuaRiwayat->last() (karena urutan ASC)
        $response->assertViewHas('kategoriTerakhir', 'Sehat');

        // Cek data chart (harus urut ASC, dari 50 ke 70)
        $response->assertViewHas('chartLabels', collect(['Tes 1', 'Tes 2']));
        $response->assertViewHas('chartScores', collect([50, 70]));

        // Cek data tabel (paginasi)
        $response->assertViewHas('riwayatTes', function ($riwayat) use ($tesPertama, $tesKedua) {
            // Pastikan ini adalah objek paginasi
            $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $riwayat);
            // Pastikan total datanya benar
            $this->assertEquals(2, $riwayat->total());
            // Pastikan urutannya benar (ASC)
            $this->assertEquals($tesPertama->total_skor, $riwayat[0]->total_skor);
            $this->assertEquals($tesKedua->total_skor, $riwayat[1]->total_skor);

            // Cek hasil subquery keluhan
            // Tes pertama (9 hari lalu) harus mengambil keluhan pertama (10 hari lalu)
            $this->assertEquals('Keluhan pertama', $riwayat[0]->keluhan);
            // Tes kedua (1 hari lalu) harus mengambil keluhan kedua (2 hari lalu)
            $this->assertEquals('Keluhan kedua', $riwayat[1]->keluhan);

            return true;
        });
    }

    /**
     * Skenario 4: Pengguna dengan banyak riwayat tes (lebih dari 10).
     * Memastikan paginasi berfungsi dengan benar
     */
    public function test_pengguna_dengan_banyak_riwayat_tes()
    {
        $user = Users::factory()->create(['nim' => '987654321']);
        DataDiris::factory()->create(['nim' => $user->nim]);

        // Buat 15 hasil tes
        for ($i = 1; $i <= 15; $i++) {
            HasilKuesioner::factory()->create([
                'nim' => $user->nim,
                'total_skor' => 50 + $i,
                'kategori' => 'Sehat',
                'created_at' => Carbon::now()->subDays(15 - $i)
            ]);
        }

        Auth::login($user);
        $response = $this->get('/user/mental-health');

        $response->assertStatus(200);
        $response->assertViewHas('jumlahTesDiikuti', 15);

        // Cek chart labels (semua 15 tes ditampilkan di chart)
        $response->assertViewHas('chartLabels', function ($labels) {
            return $labels->count() === 15;
        });

        // Cek chart scores
        $response->assertViewHas('chartScores', function ($scores) {
            return $scores->count() === 15;
        });

        // Cek paginasi (default 10 per halaman)
        $response->assertViewHas('riwayatTes', function ($riwayat) {
            $this->assertEquals(15, $riwayat->total());
            $this->assertCount(10, $riwayat->items()); // Halaman pertama 10 item
            return true;
        });
    }

    /**
     * Skenario 5: Chart data dengan progres menurun.
     * Memastikan chart menampilkan tren penurunan skor
     */
    public function test_chart_dengan_progres_menurun()
    {
        $user = Users::factory()->create(['nim' => '555666777']);
        DataDiris::factory()->create(['nim' => $user->nim]);

        // Buat 3 tes dengan skor menurun
        HasilKuesioner::factory()->create([
            'nim' => $user->nim,
            'total_skor' => 150,
            'kategori' => 'Sehat',
            'created_at' => Carbon::now()->subDays(6)
        ]);
        HasilKuesioner::factory()->create([
            'nim' => $user->nim,
            'total_skor' => 120,
            'kategori' => 'Cukup Sehat',
            'created_at' => Carbon::now()->subDays(3)
        ]);
        HasilKuesioner::factory()->create([
            'nim' => $user->nim,
            'total_skor' => 90,
            'kategori' => 'Perlu Dukungan',
            'created_at' => Carbon::now()->subDay()
        ]);

        Auth::login($user);
        $response = $this->get('/user/mental-health');

        $response->assertStatus(200);
        $response->assertViewHas('chartScores', collect([150, 120, 90]));
        $response->assertViewHas('kategoriTerakhir', 'Perlu Dukungan');
    }

    /**
     * Skenario 6: Pengguna tanpa keluhan namun ada hasil tes.
     * Memastikan sistem handle kasus tanpa keluhan
     */
    public function test_pengguna_dengan_tes_tanpa_keluhan()
    {
        $user = Users::factory()->create(['nim' => '111222333']);
        DataDiris::factory()->create(['nim' => $user->nim]);

        // Buat hasil tes tanpa keluhan
        HasilKuesioner::factory()->create([
            'nim' => $user->nim,
            'total_skor' => 160,
            'kategori' => 'Sehat',
        ]);

        Auth::login($user);
        $response = $this->get('/user/mental-health');

        $response->assertStatus(200);
        $response->assertViewHas('jumlahTesDiikuti', 1);
        $response->assertViewHas('riwayatTes', function ($riwayat) {
            // Keluhan bisa null atau kosong
            $this->assertTrue(
                is_null($riwayat[0]->keluhan) || empty($riwayat[0]->keluhan)
            );
            return true;
        });
    }
}

