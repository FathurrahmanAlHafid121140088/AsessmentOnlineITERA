<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\DataDiris;
use PHPUnit\Framework\Attributes\Test;

class DataDiriTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_submit_data_diri_form_successfully()
    {
        $this->assertDatabaseCount('data_diris', 0); // Awal: kosong

        $response = $this->post(route('mental-health.store-data-diri'), [
            'nim' => '12345678',
            'nama' => 'Contoh Mahasiswa',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Contoh No. 1',
            'usia' => 20,
            'fakultas' => 'FS',
            'program_studi' => 'Teknik Informatika',
            'email' => 'contoh@email.com',
            'keluhan' => 'Sering merasa cemas',
            'lama_keluhan' => '1',
            'pernah_konsul' => 'Tidak',
            'pernah_tes' => 'Tidak',
        ]);

        $response->assertRedirect(route('mental-health.kuesioner'));

        $this->assertDatabaseHas('data_diris', [
            'nim' => '12345678',
            'nama' => 'Contoh Mahasiswa',
            'email' => 'contoh@email.com',
        ]);

        $this->assertDatabaseHas('riwayat_keluhans', [
            'nim' => '12345678',
            'keluhan' => 'Sering merasa cemas',
        ]);

        $this->assertDatabaseCount('data_diris', 1); // Setelah submit: harus ada 1
    }

    #[Test]
    public function data_diri_submission_fails_if_nim_is_missing()
    {
        $this->assertDatabaseCount('data_diris', 0);

        $response = $this->post(route('mental-health.store-data-diri'), [
            'nim' => '', // NIM kosong
            'nama' => 'Mahasiswa Tes',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Tes',
            'usia' => 21,
            'fakultas' => 'FT',
            'program_studi' => 'Teknik Mesin',
            'email' => 'tes@email.com',
            'keluhan' => 'Cemas',
            'lama_keluhan' => '2',
            'pernah_konsul' => 'Tidak',
            'pernah_tes' => 'Tidak',
        ]);

        $response->assertSessionHasErrors('nim');

        $this->assertDatabaseCount('data_diris', 0); // Tidak ada data baru
    }

    #[Test]
    public function data_diri_submission_fails_if_usia_is_not_numeric()
    {
        $this->assertDatabaseCount('data_diris', 0);

        $response = $this->post(route('mental-health.store-data-diri'), [
            'nim' => '87654321',
            'nama' => 'Mahasiswa Usia',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Usia',
            'usia' => 'dua puluh', // Invalid usia
            'fakultas' => 'FS',
            'program_studi' => 'Biologi',
            'email' => 'usia@email.com',
            'keluhan' => 'Lelah',
            'lama_keluhan' => '3',
            'pernah_konsul' => 'Ya',
            'pernah_tes' => 'Tidak',
        ]);

        $response->assertSessionHasErrors('usia');
        $this->assertDatabaseCount('data_diris', 0);
    }

    #[Test]
    public function data_diri_submission_fails_if_email_is_invalid()
    {
        $this->assertDatabaseCount('data_diris', 0);

        $response = $this->post(route('mental-health.store-data-diri'), [
            'nim' => '11223344',
            'nama' => 'Mahasiswa Email',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Email',
            'usia' => 22,
            'fakultas' => 'FT',
            'program_studi' => 'Teknik Sipil',
            'email' => 'bukan_email_valid', // Invalid email
            'keluhan' => 'Stres',
            'lama_keluhan' => '4',
            'pernah_konsul' => 'Ya',
            'pernah_tes' => 'Tidak',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('data_diris', 0);
    }

    #[Test]
    public function data_diri_submission_fails_if_required_fields_are_missing()
    {
        $this->assertDatabaseCount('data_diris', 0);

        $response = $this->post(route('mental-health.store-data-diri'), []);

        $response->assertSessionHasErrors([
            'nim',
            'nama',
            'jenis_kelamin',
            'alamat',
            'usia',
            'fakultas',
            'program_studi',
            'email',
            'keluhan',
            'lama_keluhan',
            'pernah_konsul',
            'pernah_tes'
        ]);

        $this->assertDatabaseCount('data_diris', 0);
    }
}
