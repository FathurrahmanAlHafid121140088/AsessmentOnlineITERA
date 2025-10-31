<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Users; // Pastikan ini sesuai dengan model User Anda
use App\Models\DataDiris;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Exception;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase; // Otomatis reset database setelah setiap tes

    /**
     * Selalu tutup Mockery setelah setiap tes untuk mencegah memory leak.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    /**
     * Skenario 1: Tes pengalihan ke Google berhasil.
     */
    public function test_redirect_ke_google()
    {
        // Kita "berpura-pura" memanggil Socialite
        $mockRedirect = Mockery::mock('Laravel\Socialite\Two\Redirector');
        $mockRedirect->shouldReceive('redirect')
            ->andReturn(redirect('https://accounts.google.com/test-url')); // URL pura-pura

        Socialite::shouldReceive('driver')->with('google')->andReturn($mockRedirect);

        // Panggil route redirect
        $response = $this->get(route('google.redirect'));

        // Pastikan respons adalah redirect (302) ke URL Google
        $response->assertStatus(302);
        $response->assertRedirectContains('accounts.google.com');
    }

    /**
     * Helper: Membuat mock pengguna Google Socialite.
     */
    private function buat_mock_pengguna_socialite($email, $name = 'Nama Tes', $id = '12345')
    {
        $mockUser = Mockery::mock('Laravel\Socialite\Contracts\User');
        $mockUser->shouldReceive('getEmail')->andReturn($email);
        $mockUser->shouldReceive('getName')->andReturn($name);
        $mockUser->shouldReceive('getId')->andReturn($id);

        Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
        Socialite::shouldReceive('user')->andReturn($mockUser);
    }

    /**
     * Skenario 2: Tes login berhasil untuk pengguna baru.
     */
    public function test_callback_buat_user_baru()
    {
        // Siapkan mock user dengan email ITERA yang valid
        $this->buat_mock_pengguna_socialite('121140088@student.itera.ac.id', 'Mahasiswa Baru');

        // Panggil route callback
        $response = $this->get(route('google.callback'));

        // Periksa database
        $this->assertDatabaseHas('users', [
            'nim' => '121140088',
            'name' => 'Mahasiswa Baru',
            'email' => '121140088@student.itera.ac.id'
        ]);
        $this->assertDatabaseHas('data_diris', [
            'nim' => '121140088',
            'nama' => 'Mahasiswa Baru'
        ]);

        // Periksa status login dan redirect
        $this->assertAuthenticated();
        $response->assertRedirect('/user/mental-health');
    }

    /**
     * Skenario 3: Tes login berhasil untuk pengguna lama (update data).
     */
    public function test_callback_update_user_lama()
    {
        // Buat pengguna lama di database
        Users::create([
            'nim' => '121140088',
            'name' => 'Nama Lama',
            'email' => '121140088@student.itera.ac.id',
            'password' => bcrypt('passwordlama')
        ]);
        // Buat data diri lama
        DataDiris::create([
            'nim' => '121140088',
            'nama' => 'Nama Lama',
            'email' => '121140088@student.itera.ac.id'
        ]);

        // Siapkan mock user dengan data baru
        $this->buat_mock_pengguna_socialite('121140088@student.itera.ac.id', 'Nama Baru Update');

        // Panggil route callback
        $response = $this->get(route('google.callback'));

        // Periksa database
        $this->assertDatabaseHas('users', [
            'nim' => '121140088',
            'name' => 'Nama Baru Update' // Nama harus ter-update
        ]);
        $this->assertDatabaseCount('users', 1); // Pastikan tidak ada user baru
        $this->assertDatabaseCount('data_diris', 1); // Pastikan tidak ada data diri baru

        // Periksa status login dan redirect
        $this->assertAuthenticated();
        $response->assertRedirect('/user/mental-health');
    }

    /**
     * Skenario 4: Tes login gagal karena email bukan ITERA.
     */
    public function test_callback_gagal_email_salah()
    {
        // Siapkan mock user dengan email gmail biasa
        $this->buat_mock_pengguna_socialite('bukan.itera@gmail.com');

        // Panggil route callback
        $response = $this->get(route('google.callback'));

        // Periksa database (harus kosong)
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('data_diris', 0);

        // Periksa status login dan redirect
        $this->assertGuest(); // Memastikan pengguna TIDAK login
        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Login gagal. Pastikan Anda menggunakan email mahasiswa ITERA yang valid.');
    }

    /**
     * Skenario 5: Tes login gagal karena exception dari Socialite.
     */
    public function test_callback_gagal_exception()
    {
        // === PERBAIKAN: Tambahkan baris ini ===
        // Atur app.debug ke false untuk tes ini.
        // Ini untuk memastikan controller Anda mengembalikan redirect,
        // bukan melempar ulang exception (throw $e) seperti saat mode debug.
        config(['app.debug' => false]);
        // === BATAS PERBAIKAN ===

        // "Berpura-pura" Socialite melempar error
        Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
        Socialite::shouldReceive('user')->andThrow(new Exception('Kesalahan server Google.'));

        // Panggil route callback
        $response = $this->get(route('google.callback'));

        // Periksa database (harus kosong)
        $this->assertDatabaseCount('users', 0);

        // Periksa status login dan redirect
        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Terjadi kesalahan saat login via Google. Silakan coba lagi.');
    }

    /**
     * Skenario 6: Login gagal dengan email domain @itera.ac.id (Staff/Dosen).
     * Memastikan sistem HANYA menerima email mahasiswa (@student.itera.ac.id)
     * Email staff/dosen ditolak karena sistem khusus untuk mahasiswa
     */
    public function test_callback_gagal_dengan_email_staff_itera()
    {
        // Siapkan mock user dengan email @itera.ac.id
        $this->buat_mock_pengguna_socialite('john.doe@itera.ac.id', 'John Doe Staff');

        // Panggil route callback
        $response = $this->get(route('google.callback'));

        // Periksa database (harus kosong karena bukan email mahasiswa)
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('data_diris', 0);

        // Periksa status login dan redirect
        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Login gagal. Pastikan Anda menggunakan email mahasiswa ITERA yang valid.');
    }

    /**
     * Skenario 7: Login berhasil dengan email format NIM lengkap @student.itera.ac.id.
     * Memastikan berbagai format NIM mahasiswa diterima
     */
    public function test_callback_berhasil_dengan_berbagai_format_nim()
    {
        // Test dengan NIM format 9 digit
        $this->buat_mock_pengguna_socialite('123450001@student.itera.ac.id', 'Mahasiswa Format Lama');

        $response = $this->get(route('google.callback'));

        $this->assertDatabaseHas('users', [
            'nim' => '123450001',
            'email' => '123450001@student.itera.ac.id'
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect('/user/mental-health');
    }

    /**
     * Skenario 8: Login gagal dengan email Yahoo.
     * Memastikan email non-ITERA ditolak
     */
    public function test_callback_gagal_dengan_email_yahoo()
    {
        // Siapkan mock user dengan email Yahoo
        $this->buat_mock_pengguna_socialite('mahasiswa@yahoo.com');

        // Panggil route callback
        $response = $this->get(route('google.callback'));

        // Periksa database (harus kosong)
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('data_diris', 0);

        // Periksa status login dan redirect
        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Login gagal. Pastikan Anda menggunakan email mahasiswa ITERA yang valid.');
    }

    /**
     * Skenario 9: Login gagal dengan email Outlook/Hotmail.
     * Memastikan berbagai email non-ITERA ditolak
     */
    public function test_callback_gagal_dengan_email_outlook()
    {
        // Siapkan mock user dengan email Outlook
        $this->buat_mock_pengguna_socialite('student@outlook.com');

        // Panggil route callback
        $response = $this->get(route('google.callback'));

        // Periksa database (harus kosong)
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('data_diris', 0);

        // Periksa status login dan redirect
        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Login gagal. Pastikan Anda menggunakan email mahasiswa ITERA yang valid.');
    }

    /**
     * Skenario 10: Login gagal dengan email domain salah (typo ITERA).
     * Memastikan typo domain ITERA ditolak
     */
    public function test_callback_gagal_dengan_domain_typo()
    {
        // Siapkan mock user dengan domain typo (itera.ac.com, bukan itera.ac.id)
        $this->buat_mock_pengguna_socialite('121140088@student.itera.ac.com');

        // Panggil route callback
        $response = $this->get(route('google.callback'));

        // Periksa database (harus kosong)
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('data_diris', 0);

        // Periksa status login dan redirect
        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Login gagal. Pastikan Anda menggunakan email mahasiswa ITERA yang valid.');
    }

    /**
     * Skenario 11: Login gagal dengan email tanpa domain.
     * Memastikan format email yang tidak valid ditolak
     */
    public function test_callback_gagal_dengan_email_tanpa_domain()
    {
        // Siapkan mock user dengan email tanpa domain yang benar
        $this->buat_mock_pengguna_socialite('mahasiswa');

        // Panggil route callback
        $response = $this->get(route('google.callback'));

        // Periksa database (harus kosong)
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('data_diris', 0);

        // Periksa status login dan redirect
        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Login gagal. Pastikan Anda menggunakan email mahasiswa ITERA yang valid.');
    }
}

