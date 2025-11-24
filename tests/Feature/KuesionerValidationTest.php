<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Users;
use App\Models\DataDiris;
use App\Models\MentalHealthJawabanDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test untuk validasi kuesioner dan penyimpanan detail jawaban
 * Coverage: Pf-40, Pf-41, Pf-42
 */
class KuesionerValidationTest extends TestCase
{
    use RefreshDatabase;

    protected Users $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user dan data diri
        $this->user = Users::factory()->create(['nim' => '123456789']);
        DataDiris::factory()->create(['nim' => '123456789']);

        $this->actingAs($this->user);
    }


    /**
     * Pf-41: Menguji validasi batas minimum (nilai 1)
     */
    public function test_validasi_batas_minimum_nilai_1()
    {
        $data = ['nim' => '123456789'];

        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = 1; // Nilai minimum valid
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        // Tidak boleh ada error
        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect(route('mental-health.hasil'));
    }

    /**
     * Pf-41: Menguji validasi batas maksimum (nilai 6)
     */
    public function test_validasi_batas_maksimum_nilai_6()
    {
        $data = ['nim' => '123456789'];

        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = 6; // Nilai maksimum valid
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        // Tidak boleh ada error
        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect(route('mental-health.hasil'));
    }

    /**
     * Pf-42: Menguji penyimpanan detail jawaban per nomor soal
     */
    public function test_penyimpanan_detail_jawaban_per_nomor_soal()
    {
        $data = ['nim' => '123456789'];

        // Isi semua pertanyaan dengan nilai berbeda
        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = ($i % 6) + 1; // Nilai 1-6 berulang
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        $response->assertRedirect(route('mental-health.hasil'));

        // Ambil hasil kuesioner yang baru dibuat
        $hasilKuesioner = \App\Models\HasilKuesioner::where('nim', '123456789')->latest()->first();

        // Cek apakah 38 detail jawaban tersimpan
        $this->assertEquals(38, MentalHealthJawabanDetail::where('hasil_kuesioner_id', $hasilKuesioner->id)->count());

        // Cek detail beberapa jawaban (menggunakan kolom 'skor' bukan 'jawaban')
        $this->assertDatabaseHas('mental_health_jawaban_details', [
            'hasil_kuesioner_id' => $hasilKuesioner->id,
            'nomor_soal' => 1,
            'skor' => 2 // (1 % 6) + 1 = 2
        ]);

        $this->assertDatabaseHas('mental_health_jawaban_details', [
            'hasil_kuesioner_id' => $hasilKuesioner->id,
            'nomor_soal' => 38,
            'skor' => 3 // (38 % 6) + 1 = 3
        ]);
    }

    /**
     * Pf-42: Menguji detail jawaban tersimpan dengan hasil_kuesioner_id yang benar
     */
    public function test_detail_jawaban_tersimpan_dengan_hasil_kuesioner_id_benar()
    {
        $data = ['nim' => '123456789'];

        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = 5;
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        // Ambil hasil kuesioner yang baru dibuat
        $hasilKuesioner = \App\Models\HasilKuesioner::where('nim', '123456789')->latest()->first();

        // Pastikan semua detail jawaban memiliki hasil_kuesioner_id yang sama
        $allDetails = MentalHealthJawabanDetail::where('hasil_kuesioner_id', $hasilKuesioner->id)->get();

        $this->assertEquals(38, $allDetails->count());

        foreach ($allDetails as $detail) {
            $this->assertEquals($hasilKuesioner->id, $detail->hasil_kuesioner_id);
        }
    }

    /**
     * Pf-42: Menguji detail jawaban tersimpan dengan nomor soal berurutan
     */
    public function test_detail_jawaban_tersimpan_dengan_nomor_soal_berurutan()
    {
        $data = ['nim' => '123456789'];

        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = 4;
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        // Ambil hasil kuesioner yang baru dibuat
        $hasilKuesioner = \App\Models\HasilKuesioner::where('nim', '123456789')->latest()->first();

        // Cek nomor soal 1-38 semua ada
        for ($i = 1; $i <= 38; $i++) {
            $this->assertDatabaseHas('mental_health_jawaban_details', [
                'hasil_kuesioner_id' => $hasilKuesioner->id,
                'nomor_soal' => $i
            ]);
        }
    }

    /**
     * Pf-42: Menguji multiple submit menyimpan detail jawaban terpisah
     */
    public function test_multiple_submit_menyimpan_detail_jawaban_terpisah()
    {
        // Submit pertama
        $data1 = ['nim' => '123456789'];
        for ($i = 1; $i <= 38; $i++) {
            $data1["question{$i}"] = 5;
        }
        $this->post(route('mental-health.kuesioner.submit'), $data1);

        $hasil1 = \App\Models\HasilKuesioner::where('nim', '123456789')->first();

        // Submit kedua
        $data2 = ['nim' => '123456789'];
        for ($i = 1; $i <= 38; $i++) {
            $data2["question{$i}"] = 3;
        }
        $this->post(route('mental-health.kuesioner.submit'), $data2);

        $hasil2 = \App\Models\HasilKuesioner::where('nim', '123456789')->latest()->first();

        // Harus ada 38 detail untuk hasil pertama
        $this->assertEquals(38, MentalHealthJawabanDetail::where('hasil_kuesioner_id', $hasil1->id)->count());

        // Harus ada 38 detail untuk hasil kedua
        $this->assertEquals(38, MentalHealthJawabanDetail::where('hasil_kuesioner_id', $hasil2->id)->count());

        // Harus ada 2 hasil kuesioner
        $this->assertEquals(2, \App\Models\HasilKuesioner::where('nim', '123456789')->count());
    }
}
