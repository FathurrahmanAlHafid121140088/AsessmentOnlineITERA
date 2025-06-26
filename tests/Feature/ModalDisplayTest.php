<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\DataDiris;
use App\Models\HasilKuesioner;
use App\Models\RiwayatKeluhans;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Admin;

class ModalDisplayTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_only_one_row_per_nim(): void
    {
        $this->prepareData();

        $response = $this->get('/admin');

        $response->assertOk();
        $response->assertSee('Mahasiswa Email');

        // Pastikan hanya satu baris yang muncul untuk NIM 11223344
        $this->assertEquals(
            1,
            substr_count($response->getContent(), '<td>11223344</td>')
        );
    }

    #[Test]
    public function it_displays_all_modal_data_for_same_nim(): void
    {
        $this->prepareData();

        $response = $this->get('/admin');
        $response->assertOk();

        // Periksa semua data kuesioner dan keluhan tampil di modal
        $response->assertSee('130');
        $response->assertSee('170');
        $response->assertSee('Sedang');
        $response->assertSee('Baik');
        $response->assertSee('Stres ringan');
        $response->assertSee('Overthinking');
    }

    protected function prepareData(): void
    {
        // ✅ Buat admin dummy & login
        $admin = Admin::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // atau hash('sha256', 'password')
        ]);
        $this->actingAs($admin, 'admin'); // <== tambahkan guard 'admin' di sini

        // Buat data_diris
        DataDiris::create([
            'nim' => '11223344',
            'nama' => 'Mahasiswa Email',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Email',
            'usia' => 22,
            'fakultas' => 'FT',
            'program_studi' => 'Teknik Sipil',
            'email' => 'email@example.com',
        ]);

        // Tambah hasil kuesioner
        HasilKuesioner::create([
            'nim' => '11223344',
            'total_skor' => 130,
            'kategori' => 'Sedang',
        ]);
        HasilKuesioner::create([
            'nim' => '11223344',
            'total_skor' => 170,
            'kategori' => 'Baik',
        ]);

        // Tambah riwayat keluhan
        RiwayatKeluhans::create([
            'nim' => '11223344',
            'keluhan' => 'Stres ringan',
            'lama_keluhan' => '4 bulan',
            'pernah_konsul' => 'Ya',
            'pernah_tes' => 'Tidak',
        ]);
        RiwayatKeluhans::create([
            'nim' => '11223344',
            'keluhan' => 'Overthinking',
            'lama_keluhan' => '2 minggu',
            'pernah_konsul' => 'Tidak',
            'pernah_tes' => 'Ya',
        ]);
    }
}
