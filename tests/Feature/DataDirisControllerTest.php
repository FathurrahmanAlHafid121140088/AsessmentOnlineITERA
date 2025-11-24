<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Users;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test; // <-- Tambahkan ini

/**
 * @mixin \Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication
 */
class DataDirisControllerTest extends TestCase
{
    use RefreshDatabase; // Gunakan RefreshDatabase untuk membersihkan DB setiap tes

    // ================================================
    // TES UNTUK METHOD create() (Menampilkan Form)
    // ================================================

    /**
     * Skenario 1.1: Pengguna Belum Login saat akses form.
     * Menguji apakah pengguna yang belum terautentikasi dialihkan
     * ke halaman login ketika mencoba mengakses form data diri.
     * Ini adalah pengujian keamanan dasar (authorization).
     */
    #[Test] // <-- Ganti @test dengan ini
    public function form_create_pengguna_belum_login(): void // <-- Tambahkan :void
    {
        // 1. Aksi: Kirim request GET ke route 'isi-data-diri'
        $response = $this->get(route('mental-health.isi-data-diri'));

        // 2. Pengecekan:
        //    - Harusnya dialihkan (status 302).
        //    - Tujuan redirect harus ke '/login'.
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Skenario 1.2: Pengguna Login, Belum Punya Data Diri.
     * Menguji apakah pengguna yang sudah login tetapi belum pernah
     * mengisi data diri dapat melihat halaman form dengan benar,
     * dan memastikan data `dataDiri` yang dikirim ke view adalah null.
     */
    #[Test] // <-- Ganti @test dengan ini
    public function form_create_pengguna_login_tanpa_data_diri(): void // <-- Tambahkan :void
    {
        // 1. Persiapan: Buat user dummy dan login
        $user = Users::factory()->create();
        Auth::login($user);

        // 2. Aksi: Kirim request GET ke route 'isi-data-diri'
        $response = $this->get(route('mental-health.isi-data-diri'));

        // 3. Pengecekan:
        //    - Halaman berhasil dimuat (status 200).
        //    - View yang dimuat adalah 'isi-data-diri'.
        //    - Variabel 'dataDiri' di dalam view bernilai null.
        $response->assertStatus(200);
        $response->assertViewIs('isi-data-diri');
        $response->assertViewHas('dataDiri', null);
    }

    /**
     * Skenario 1.3: Pengguna Login, Sudah Punya Data Diri.
     * Menguji apakah pengguna yang sudah login dan sudah pernah
     * mengisi data diri dapat melihat halaman form, dan memastikan
     * data `dataDiri` yang dikirim ke view berisi data yang sudah ada
     * di database (misalnya, untuk pre-fill form).
     */
    #[Test] // <-- Ganti @test dengan ini
    public function form_create_pengguna_login_dengan_data_diri(): void // <-- Tambahkan :void
    {
        // 1. Persiapan: Buat user dummy, buat data diri dummy yang terkait, lalu login
        $user = Users::factory()->create();
        $dataDiri = DataDiris::factory()->create(['nim' => $user->nim]);
        Auth::login($user);

        // 2. Aksi: Kirim request GET ke route 'isi-data-diri'
        $response = $this->get(route('mental-health.isi-data-diri'));

        // 3. Pengecekan:
        //    - Halaman berhasil dimuat (status 200).
        //    - View yang dimuat adalah 'isi-data-diri'.
        //    - Variabel 'dataDiri' di dalam view berisi objek DataDiris yang sama
        //      dengan yang kita buat sebelumnya (dicek berdasarkan NIM).
        $response->assertStatus(200);
        $response->assertViewIs('isi-data-diri');
        $response->assertViewHas('dataDiri', function ($viewDataDiri) use ($dataDiri) {
            return $viewDataDiri instanceof DataDiris && $viewDataDiri->nim == $dataDiri->nim;
        });
    }

    // ================================================
    // TES UNTUK METHOD store() (Menyimpan Data)
    // ================================================

    /**
     * Helper function untuk menyediakan data valid minimal
     * yang dibutuhkan oleh form store.
     */
    private function dataValid(): array
    {
        return [
            'nama' => 'Nama Pengguna Tes Valid',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Alamat Lengkap Tes',
            'usia' => 20,
            'fakultas' => 'Fakultas Teknik',
            'program_studi' => 'Teknik Informatika',
            'asal_sekolah' => 'SMA Tes',
            'status_tinggal' => 'Bersama Orang Tua',
            'email' => 'tes.valid@example.com', // Email ini hanya untuk form, tidak harus sama dengan email user
            'keluhan' => 'Keluhan tes valid',
            'lama_keluhan' => '3 Bulan',
            'pernah_konsul' => 'Tidak',
            'pernah_tes' => 'Ya',
        ];
    }

    /**
     * Skenario 2.1: Pengguna Belum Login saat simpan data.
     * Menguji apakah pengguna yang belum terautentikasi dialihkan
     * ke halaman login ketika mencoba mengirim data ke endpoint store.
     */
    #[Test] // <-- Ganti @test dengan ini
    public function form_store_pengguna_belum_login(): void // <-- Tambahkan :void
    {
        // 1. Aksi: Kirim request POST ke route 'store-data-diri'
        $response = $this->post(route('mental-health.store-data-diri'), $this->dataValid());

        // 2. Pengecekan:
        //    - Harusnya dialihkan (status 302).
        //    - Tujuan redirect harus ke '/login'.
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Skenario 2.3: Pengguna Login, Data Valid, Data Diri Baru.
     * Menguji kasus di mana pengguna baru pertama kali mengisi data diri.
     * Memastikan data diri dan riwayat keluhan berhasil disimpan ke database,
     * pengguna dialihkan ke halaman kuesioner dengan pesan sukses, dan
     * data yang relevan disimpan di session.
     */
    #[Test] // <-- Ganti @test dengan ini
    public function form_store_data_valid_data_diri_baru(): void // <-- Tambahkan :void
    {
        // 1. Persiapan: Buat user dummy dan login
        $user = Users::factory()->create();
        Auth::login($user);

        // 2. Siapkan data valid
        $data = $this->dataValid();

        // 3. Aksi: Kirim request POST ke 'store-data-diri'
        $response = $this->post(route('mental-health.store-data-diri'), $data);

        // 4. Pengecekan Database:
        //    - Pastikan data baru tersimpan di tabel 'data_diris' dengan benar.
        $this->assertDatabaseHas('data_diris', [
            'nim' => $user->nim,
            'nama' => $data['nama'],
            'program_studi' => $data['program_studi'],
            'email' => $data['email'], // Cek beberapa field penting
        ]);
        //    - Pastikan data baru tersimpan di tabel 'riwayat_keluhans'.
        $this->assertDatabaseHas('riwayat_keluhans', [
            'nim' => $user->nim,
            'keluhan' => $data['keluhan'],
            'lama_keluhan' => $data['lama_keluhan'],
        ]);

        // 5. Pengecekan Redirect dan Session:
        //    - Harusnya dialihkan (status 302) ke halaman kuesioner.
        //    - Session harus memiliki pesan sukses ('success').
        //    - Session harus menyimpan nim, nama, dan program_studi.
        $response->assertStatus(302);
        $response->assertRedirect(route('mental-health.kuesioner'));
        $response->assertSessionHas('success', 'Data berhasil disimpan.');
        $response->assertSessionHas('nim', $user->nim);
        $response->assertSessionHas('nama', $data['nama']);
        $response->assertSessionHas('program_studi', $data['program_studi']);
    }

    /**
     * Skenario 2.4: Pengguna Login, Data Valid, Update Data Diri.
     * Menguji kasus di mana pengguna mengisi form data diri lagi (mungkin
     * untuk memperbarui data). Memastikan data di tabel 'data_diris' ter-update
     * (bukan membuat duplikat), entri baru tetap dibuat di 'riwayat_keluhans',
     * pengguna dialihkan ke halaman kuesioner dengan pesan sukses, dan
     * session berisi data yang sudah ter-update.
     */
    #[Test] // <-- Ganti @test dengan ini
    public function form_store_data_valid_update_data_diri(): void // <-- Tambahkan :void
    {
        // 1. Persiapan: Buat user dummy, buat data diri awal, lalu login
        $user = Users::factory()->create();
        DataDiris::factory()->create([
            'nim' => $user->nim,
            'nama' => 'Nama Awal',
            'alamat' => 'Alamat Lama',
            'program_studi' => 'Prodi Lama'
        ]);
        Auth::login($user);

        // 2. Siapkan data valid untuk update (ubah beberapa field)
        $dataUpdate = $this->dataValid();
        $dataUpdate['nama'] = 'Nama Baru Setelah Update';
        $dataUpdate['alamat'] = 'Alamat Baru Setelah Update';
        $dataUpdate['program_studi'] = 'Prodi Baru';

        // 3. Aksi: Kirim request POST ke 'store-data-diri'
        $response = $this->post(route('mental-health.store-data-diri'), $dataUpdate);

        // 4. Pengecekan Database:
        //    - Pastikan data di 'data_diris' telah ter-update.
        $this->assertDatabaseHas('data_diris', [
            'nim' => $user->nim,
            'nama' => $dataUpdate['nama'],
            'alamat' => $dataUpdate['alamat'],
            'program_studi' => $dataUpdate['program_studi'],
        ]);
        //    - Pastikan hanya ada satu data diri untuk NIM ini (tidak duplikat).
        $this->assertEquals(1, DataDiris::where('nim', $user->nim)->count());
        //    - Pastikan entri BARU tersimpan di 'riwayat_keluhans'.
        $this->assertDatabaseHas('riwayat_keluhans', [
            'nim' => $user->nim,
            'keluhan' => $dataUpdate['keluhan'],
            'lama_keluhan' => $dataUpdate['lama_keluhan'],
        ]);

        // 5. Pengecekan Redirect dan Session:
        //    - Harusnya dialihkan (status 302) ke halaman kuesioner.
        //    - Session harus memiliki pesan sukses ('success').
        //    - Session harus menyimpan nim, nama baru, dan program_studi baru.
        $response->assertStatus(302);
        $response->assertRedirect(route('mental-health.kuesioner'));
        $response->assertSessionHas('success', 'Data berhasil disimpan.');
        $response->assertSessionHas('nim', $user->nim);
        $response->assertSessionHas('nama', $dataUpdate['nama']); // Cek nama baru
        $response->assertSessionHas('program_studi', $dataUpdate['program_studi']); // Cek prodi baru
    }

    /**
     * Skenario 2.6: Validasi Usia - Boundary Testing Minimum
     * Memastikan usia minimum dapat diterima (misalnya 15 tahun)
     */
    #[Test]
    public function form_store_validasi_usia_minimum(): void
    {
        $user = Users::factory()->create();
        Auth::login($user);

        $data = $this->dataValid();
        $data['usia'] = 15; // Usia minimum

        $response = $this->post(route('mental-health.store-data-diri'), $data);

        $this->assertDatabaseHas('data_diris', [
            'nim' => $user->nim,
            'usia' => 15
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('mental-health.kuesioner'));
    }

    /**
     * Skenario 2.7: Validasi Usia - Boundary Testing Maximum
     * Memastikan usia maksimum dapat diterima (misalnya 100 tahun)
     */
    #[Test]
    public function form_store_validasi_usia_maksimum(): void
    {
        $user = Users::factory()->create();
        Auth::login($user);

        $data = $this->dataValid();
        $data['usia'] = 100; // Usia maksimum

        $response = $this->post(route('mental-health.store-data-diri'), $data);

        $this->assertDatabaseHas('data_diris', [
            'nim' => $user->nim,
            'usia' => 100
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('mental-health.kuesioner'));
    }

    /**
     * Pf-30: Menguji pengaturan session (nim, nama, program_studi) setelah submit
     */
    #[Test]
    public function test_pengaturan_session_setelah_submit_data_diri()
    {
        $user = Users::factory()->create(['nim' => '987654321']);
        Auth::login($user);

        $data = $this->dataValid();
        $data['nama'] = 'Test Session User';
        $data['program_studi'] = 'Sistem Informasi';

        $response = $this->post(route('mental-health.store-data-diri'), $data);

        // Verifikasi session berisi nim, nama, dan program_studi
        $response->assertSessionHas('nim', '987654321');
        $response->assertSessionHas('nama', 'Test Session User');
        $response->assertSessionHas('program_studi', 'Sistem Informasi');

        // Verifikasi session menggunakan session helper
        $this->assertEquals('987654321', session('nim'));
        $this->assertEquals('Test Session User', session('nama'));
        $this->assertEquals('Sistem Informasi', session('program_studi'));
    }

    /**
     * Pf-31: Menguji redirect ke halaman kuesioner setelah berhasil submit
     */
    #[Test]
    public function test_redirect_ke_halaman_kuesioner_setelah_berhasil_submit()
    {
        $user = Users::factory()->create();
        Auth::login($user);

        $data = $this->dataValid();

        $response = $this->post(route('mental-health.store-data-diri'), $data);

        // Verifikasi redirect ke halaman kuesioner
        $response->assertRedirect(route('mental-health.kuesioner'));
        $response->assertStatus(302);

        // Verifikasi pesan sukses
        $response->assertSessionHas('success', 'Data berhasil disimpan.');
    }

    /**
     * Pf-29: Menguji penyimpanan riwayat keluhan baru setiap submit
     */
    #[Test]
    public function test_penyimpanan_riwayat_keluhan_baru_setiap_submit()
    {
        $user = Users::factory()->create();
        Auth::login($user);

        // Submit pertama
        $data1 = $this->dataValid();
        $data1['keluhan'] = 'Keluhan pertama';
        $data1['lama_keluhan'] = '1 Bulan';

        $this->post(route('mental-health.store-data-diri'), $data1);

        // Verifikasi keluhan pertama tersimpan
        $this->assertDatabaseHas('riwayat_keluhans', [
            'nim' => $user->nim,
            'keluhan' => 'Keluhan pertama',
            'lama_keluhan' => '1 Bulan'
        ]);

        // Submit kedua dengan keluhan berbeda
        $data2 = $this->dataValid();
        $data2['keluhan'] = 'Keluhan kedua';
        $data2['lama_keluhan'] = '2 Bulan';

        $this->post(route('mental-health.store-data-diri'), $data2);

        // Verifikasi keluhan kedua juga tersimpan (tidak overwrite)
        $this->assertDatabaseHas('riwayat_keluhans', [
            'nim' => $user->nim,
            'keluhan' => 'Keluhan kedua',
            'lama_keluhan' => '2 Bulan'
        ]);

        // Verifikasi ada 2 riwayat keluhan untuk user ini
        $this->assertEquals(2, RiwayatKeluhans::where('nim', $user->nim)->count());
    }

}

