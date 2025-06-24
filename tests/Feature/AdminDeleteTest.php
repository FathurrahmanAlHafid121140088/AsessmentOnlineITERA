<?php

namespace Tests\Unit;

use App\Models\DataDiris;
use App\Models\HasilKuesioner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminDeleteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_delete_a_hasil_kuesioner_record()
    {
        // Buat data data_diris agar FK valid
        DataDiris::create([
            'nim' => '121140011',
            'nama' => 'Test Mahasiswa',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Testing',
            'usia' => 20,
            'fakultas' => 'Fakultas Teknik',
            'program_studi' => 'Teknik Informatika',
            'email' => 'test@example.com',
        ]);

        // Buat data hasil_kuesioner
        $kuesioner = HasilKuesioner::create([
            'nim' => '121140011',
            'total_skor' => 150,
            'kategori' => 'Sedang (Rentan)',
        ]);

        // Cek data ada
        $this->assertDatabaseHas('hasil_kuesioners', [
            'id' => $kuesioner->id,
        ]);

        // Hapus data
        $kuesioner->delete();

        // Pastikan data terhapus
        $this->assertDatabaseMissing('hasil_kuesioners', [
            'id' => $kuesioner->id,
        ]);
    }
}
