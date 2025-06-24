<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\DataDiris;
use App\Models\HasilKuesioner;
use App\Models\RiwayatKeluhans;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class SearchFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed DataDiri manual
        DataDiris::create([
            'nim' => '121140010',
            'nama' => 'Mahasiswa Search',
            'program_studi' => 'Teknik Informatika',
            'email' => 'search@example.com',
            'alamat' => 'Jalan Search',
            'jenis_kelamin' => 'L',
            'fakultas' => 'Fakultas Sains',
            'usia' => 21, // ✅ PERBAIKAN PENTING
        ]);

        // Seed RiwayatKeluhans manual
        RiwayatKeluhans::create([
            'nim' => '121140010',
            'keluhan' => 'Sering cemas',
            'lama_keluhan' => '3 bulan',
            'pernah_konsul' => 'Ya',
            'pernah_tes' => 'Tidak',
        ]);

        // Seed HasilKuesioner manual
        HasilKuesioner::create([
            'nim' => '121140010',
            'total_skor' => 150,
            'kategori' => 'Sedang (Rentan)',
        ]);
    }

    #[Test]
    public function it_can_search_by_nim(): void
    {
        $response = $this->get('/search?search=121140010');
        $response->assertOk()->assertSee('Mahasiswa Search');
    }

    #[Test]
    public function it_can_search_by_nama(): void
    {
        $response = $this->get('/search?search=Mahasiswa Search');
        $response->assertOk()->assertSee('Mahasiswa Search');
    }

    #[Test]
    public function it_can_search_by_program_studi(): void
    {
        $response = $this->get('/search?search=Teknik Informatika');
        $response->assertOk()->assertSee('Teknik Informatika');
    }

    #[Test]
    public function it_can_search_by_email(): void
    {
        $response = $this->get('/search?search=search@example.com');
        $response->assertOk()->assertSee('search@example.com');
    }

    #[Test]
    public function it_can_search_by_alamat(): void
    {
        $response = $this->get('/search?search=Jalan Search');
        $response->assertOk()->assertSee('Jalan Search');
    }

    #[Test]
    public function it_can_search_by_jenis_kelamin(): void
    {
        $response = $this->get('/search?search=L');
        $response->assertOk()->assertSee('L');
    }

    #[Test]
    public function it_can_search_by_fakultas(): void
    {
        $response = $this->get('/search?search=Fakultas Sains');
        $response->assertOk()->assertSee('Fakultas Sains');
    }

    #[Test]
    public function it_can_search_by_riwayat_keluhan_keluhan(): void
    {
        $response = $this->get('/search?search=Sering cemas');
        $response->assertOk()->assertSee('Sering cemas');
    }

    #[Test]
    public function it_can_search_by_riwayat_keluhan_lama_keluhan(): void
    {
        $response = $this->get('/search?search=3 bulan');
        $response->assertOk()->assertSee('3 bulan');
    }

    #[Test]
    public function it_can_search_by_riwayat_keluhan_pernah_konsul(): void
    {
        $response = $this->get('/search?search=Ya');
        $response->assertOk();
    }

    #[Test]
    public function it_can_search_by_riwayat_keluhan_pernah_tes(): void
    {
        $response = $this->get('/search?search=Tidak');
        $response->assertOk();
    }

    #[Test]
    public function it_can_search_by_hasil_kuesioner_kategori(): void
    {
        $response = $this->get('/search?search=Sedang (Rentan)');
        $response->assertOk()->assertSee('Sedang (Rentan)');
    }

    #[Test]
    public function it_can_search_by_hasil_kuesioner_total_skor(): void
    {
        $response = $this->get('/search?search=150');
        $response->assertOk()->assertSee('150');
    }

    #[Test]
    public function it_shows_no_results_if_not_found(): void
    {
        $response = $this->get('/search?search=TidakAdaData');
        $response->assertOk()->assertSee('Belum ada data hasil kuesioner.');
    }
}
