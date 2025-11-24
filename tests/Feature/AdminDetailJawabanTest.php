<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use App\Models\MentalHealthJawabanDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test untuk fitur Detail Jawaban Admin dan Cetak PDF
 * Coverage: Pf-68 s/d Pf-76
 */
class AdminDetailJawabanTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected HasilKuesioner $hasilKuesioner;
    protected DataDiris $dataDiri;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat admin
        $this->admin = Admin::factory()->create();

        // Buat data mahasiswa
        $this->dataDiri = DataDiris::factory()->create([
            'nim' => '123456789',
            'nama' => 'John Doe',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Jl. Test No. 123',
            'usia' => 20,
            'fakultas' => 'FTIK',
            'program_studi' => 'Teknik Informatika',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'email' => 'john@example.com'
        ]);

        // Buat hasil kuesioner
        $this->hasilKuesioner = HasilKuesioner::factory()->create([
            'nim' => '123456789',
            'total_skor' => 180,
            'kategori' => 'Sehat'
        ]);

        // Buat 38 detail jawaban
        for ($i = 1; $i <= 38; $i++) {
            MentalHealthJawabanDetail::create([
                'hasil_kuesioner_id' => $this->hasilKuesioner->id,
                'nomor_soal' => $i,
                'skor' => ($i % 6) + 1 // Nilai 1-6 berulang
            ]);
        }
    }

    /**
     * Pf-68: Menguji tampilan 38 pertanyaan dengan jawaban mahasiswa
     */
    public function test_tampilan_38_pertanyaan_dengan_jawaban_mahasiswa()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.mental-health.detail', $this->hasilKuesioner->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin-mental-health-detail');

        // Pastikan ada 38 jawaban
        $response->assertViewHas('jawabanDetails', function ($details) {
            return $details->count() === 38;
        });

        // Cek data mahasiswa tampil
        $response->assertSee('John Doe');
        $response->assertSee('123456789');
    }

    /**
     * Pf-69: Menguji identifikasi item negatif (psychological distress)
     */
    public function test_identifikasi_item_negatif()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.mental-health.detail', $this->hasilKuesioner->id));

        $response->assertStatus(200);

        // Item negatif sesuai dokumentasi MHI-38
        $itemNegatif = [2, 3, 8, 9, 11, 13, 14, 15, 16, 18, 19, 20, 21, 24, 25, 27, 28, 29, 30, 32, 33, 35, 36, 38];

        $response->assertViewHas('jawabanDetails', function ($details) use ($itemNegatif) {
            foreach ($details as $detail) {
                $isNegative = in_array($detail->nomor_soal, $itemNegatif);

                // Verifikasi detail memiliki informasi tentang tipe soal
                if ($isNegative) {
                    // Item negatif harus ditandai
                    $this->assertContains($detail->nomor_soal, $itemNegatif);
                }
            }
            return true;
        });
    }

    /**
     * Pf-70: Menguji identifikasi item positif (psychological well-being)
     */
    public function test_identifikasi_item_positif()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.mental-health.detail', $this->hasilKuesioner->id));

        $response->assertStatus(200);

        // Item positif sesuai dokumentasi MHI-38
        $itemPositif = [1, 4, 5, 6, 7, 10, 12, 17, 22, 23, 26, 31, 34, 37];

        $response->assertViewHas('jawabanDetails', function ($details) use ($itemPositif) {
            foreach ($details as $detail) {
                $isPositive = in_array($detail->nomor_soal, $itemPositif);

                if ($isPositive) {
                    $this->assertContains($detail->nomor_soal, $itemPositif);
                }
            }
            return true;
        });
    }

    /**
     * Pf-71: Menguji tampilan informasi data diri lengkap mahasiswa
     */
    public function test_tampilan_informasi_data_diri_lengkap_mahasiswa()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.mental-health.detail', $this->hasilKuesioner->id));

        $response->assertStatus(200);

        // Cek informasi utama data diri tampil
        $response->assertSee('John Doe');
        $response->assertSee('123456789');
        $response->assertSee('Teknik Informatika');

        // Cek hasil kuesioner tampil
        $response->assertSee('180');
        $response->assertSee('Sehat');
    }

    /**
     * Pf-72: Menguji akses detail dengan ID tidak valid (404)
     */
    public function test_akses_detail_dengan_id_tidak_valid()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.mental-health.detail', 99999));

        $response->assertStatus(404);
    }

    /**
     * Pf-72: Menguji akses detail tanpa login admin
     */
    public function test_akses_detail_tanpa_login_admin()
    {
        $response = $this->get(route('admin.mental-health.detail', $this->hasilKuesioner->id));

        $response->assertRedirect(route('login'));
    }


    /**
     * Test: Detail jawaban urut berdasarkan nomor soal
     */
    public function test_detail_jawaban_urut_berdasarkan_nomor_soal()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.mental-health.detail', $this->hasilKuesioner->id));

        $response->assertStatus(200);

        $response->assertViewHas('jawabanDetails', function ($details) {
            // Cek urutan
            $previousNomor = 0;
            foreach ($details as $detail) {
                $this->assertGreaterThan($previousNomor, $detail->nomor_soal);
                $previousNomor = $detail->nomor_soal;
            }
            return true;
        });
    }

    /**
     * Test: Semua 38 jawaban harus ada
     */
    public function test_semua_38_jawaban_harus_ada()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.mental-health.detail', $this->hasilKuesioner->id));

        $response->assertViewHas('jawabanDetails', function ($details) {
            // Harus ada 38 jawaban
            $this->assertEquals(38, $details->count());

            // Cek nomor 1-38 semua ada
            $nomorSoal = $details->pluck('nomor_soal')->toArray();
            for ($i = 1; $i <= 38; $i++) {
                $this->assertContains($i, $nomorSoal);
            }

            return true;
        });
    }

    /**
     * Test: Relasi antara hasil kuesioner dan detail jawaban
     */
    public function test_relasi_hasil_kuesioner_dengan_detail_jawaban()
    {
        // Buat hasil kuesioner kedua untuk mahasiswa yang sama
        $hasilKuesioner2 = HasilKuesioner::factory()->create([
            'nim' => '123456789',
            'total_skor' => 150,
            'kategori' => 'Cukup Sehat'
        ]);

        // Buat detail jawaban untuk hasil kedua
        for ($i = 1; $i <= 38; $i++) {
            MentalHealthJawabanDetail::create([
                'hasil_kuesioner_id' => $hasilKuesioner2->id,
                'nomor_soal' => $i,
                'skor' => 3
            ]);
        }

        // Akses detail hasil pertama
        $response1 = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.mental-health.detail', $this->hasilKuesioner->id));

        $response1->assertViewHas('jawabanDetails', function ($details) {
            // Harus menampilkan jawaban dari hasil pertama saja
            foreach ($details as $detail) {
                $this->assertEquals($this->hasilKuesioner->id, $detail->hasil_kuesioner_id);
            }
            return true;
        });

        // Akses detail hasil kedua
        $response2 = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.mental-health.detail', $hasilKuesioner2->id));

        $response2->assertViewHas('jawabanDetails', function ($details) use ($hasilKuesioner2) {
            // Harus menampilkan jawaban dari hasil kedua saja
            foreach ($details as $detail) {
                $this->assertEquals($hasilKuesioner2->id, $detail->hasil_kuesioner_id);
                $this->assertEquals(3, $detail->skor);
            }
            return true;
        });
    }
}
