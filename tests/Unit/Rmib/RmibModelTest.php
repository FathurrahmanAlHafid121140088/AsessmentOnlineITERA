<?php

namespace Tests\Unit\Rmib;

use Tests\TestCase;
use App\Models\KarirDataDiri;
use App\Models\RmibHasilTes;
use App\Models\RmibJawabanPeserta;
use App\Models\RmibPekerjaan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

/**
 * UNIT TEST: RMIB Models
 *
 * Menguji model-model RMIB secara terisolasi
 * Total: 10 test cases
 *
 * Coverage:
 * - KarirDataDiri Model (3 tests)
 * - RmibHasilTes Model (3 tests)
 * - RmibJawabanPeserta Model (2 tests)
 * - RmibPekerjaan Model (2 tests)
 */
class RmibModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RmibPekerjaanSeeder::class);
    }

    // ========================================
    // KARIR DATA DIRI MODEL TESTS
    // ========================================

    #[Test]
    public function karir_data_diri_dapat_dibuat()
    {
        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'Test User',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Jl. Test No. 123',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        $this->assertDatabaseHas('karir_data_diri', [
            'nim' => '123456789',
            'nama' => 'Test User',
        ]);

        $this->assertInstanceOf(KarirDataDiri::class, $dataDiri);
    }

    #[Test]
    public function karir_data_diri_memiliki_relasi_ke_hasil_tes()
    {
        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'Test User',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test Address',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        // Create hasil tes
        RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        // Test hasMany relation
        $this->assertCount(1, $dataDiri->hasilTes);
        $this->assertInstanceOf(RmibHasilTes::class, $dataDiri->hasilTes->first());
    }

    #[Test]
    public function update_or_create_tidak_membuat_duplikat()
    {
        // Create initial
        KarirDataDiri::updateOrCreate(
            ['nim' => '123456789'],
            [
                'nama' => 'Initial Name',
                'program_studi' => 'Teknik Informatika',
                'jenis_kelamin' => 'L',
                'provinsi' => 'Lampung',
                'alamat' => 'Test Address',
                'usia' => 20,
                'fakultas' => 'FTI',
                'email' => 'test@student.itera.ac.id',
                'asal_sekolah' => 'SMA',
                'status_tinggal' => 'Kost',
                'prodi_sesuai_keinginan' => 'Ya',
            ]
        );

        // Update with same NIM
        KarirDataDiri::updateOrCreate(
            ['nim' => '123456789'],
            [
                'nama' => 'Updated Name',
                'program_studi' => 'Teknik Informatika',
                'jenis_kelamin' => 'L',
                'provinsi' => 'Lampung',
                'alamat' => 'Test Address',
                'usia' => 20,
                'fakultas' => 'FTI',
                'email' => 'test@student.itera.ac.id',
                'asal_sekolah' => 'SMA',
                'status_tinggal' => 'Kost',
                'prodi_sesuai_keinginan' => 'Ya',
            ]
        );

        // Should have only 1 record
        $this->assertDatabaseCount('karir_data_diri', 1);

        // Name should be updated
        $this->assertDatabaseHas('karir_data_diri', [
            'nim' => '123456789',
            'nama' => 'Updated Name',
        ]);
    }

    // ========================================
    // RMIB HASIL TES MODEL TESTS
    // ========================================

    #[Test]
    public function rmib_hasil_tes_dapat_dibuat()
    {
        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'Test User',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test Address',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        $hasilTes = RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
            'pekerjaan_lain' => 'Data Scientist',
            'interpretasi' => 'Test interpretasi',
        ]);

        $this->assertDatabaseHas('rmib_hasil_tes', [
            'karir_data_diri_id' => $dataDiri->id,
            'top_1_pekerjaan' => 'Ilmuwan',
        ]);
    }

    #[Test]
    public function rmib_hasil_tes_belongs_to_karir_data_diri()
    {
        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'Test User',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test Address',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        $hasilTes = RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        // Test belongsTo relation
        $this->assertInstanceOf(KarirDataDiri::class, $hasilTes->dataDiri);
        $this->assertEquals($dataDiri->id, $hasilTes->dataDiri->id);
    }

    #[Test]
    public function rmib_hasil_tes_memiliki_jawaban_peserta()
    {
        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'Test User',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test Address',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        $hasilTes = RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        RmibJawabanPeserta::create([
            'hasil_id' => $hasilTes->id,
            'kelompok' => 'A',
            'pekerjaan' => 'Petani',
            'peringkat' => 1,
        ]);

        // Test hasMany relation
        $this->assertCount(1, $hasilTes->jawaban);
    }

    // ========================================
    // RMIB JAWABAN PESERTA MODEL TESTS
    // ========================================

    #[Test]
    public function rmib_jawaban_peserta_dapat_dibuat()
    {
        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'Test User',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test Address',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        $hasilTes = RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        $jawaban = RmibJawabanPeserta::create([
            'hasil_id' => $hasilTes->id,
            'kelompok' => 'A',
            'pekerjaan' => 'Petani',
            'peringkat' => 1,
        ]);

        $this->assertDatabaseHas('rmib_jawaban_peserta', [
            'hasil_id' => $hasilTes->id,
            'kelompok' => 'A',
            'pekerjaan' => 'Petani',
            'peringkat' => 1,
        ]);
    }

    #[Test]
    public function bulk_insert_jawaban_peserta_bekerja()
    {
        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'Test User',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test Address',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        $hasilTes = RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        $jawabanToInsert = [];
        $now = Carbon::now();

        // Get pekerjaan from cluster A (12 jobs)
        $pekerjaanList = RmibPekerjaan::where('gender', 'L')
            ->where('kelompok', 'A')
            ->orderBy('id')
            ->get();

        foreach ($pekerjaanList as $index => $pekerjaan) {
            $jawabanToInsert[] = [
                'hasil_id' => $hasilTes->id,
                'kelompok' => 'A',
                'pekerjaan' => $pekerjaan->nama_pekerjaan,
                'peringkat' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        RmibJawabanPeserta::insert($jawabanToInsert);

        // Should have 12 jawaban for cluster A
        $this->assertDatabaseCount('rmib_jawaban_peserta', 12);
    }

    // ========================================
    // RMIB PEKERJAAN MODEL TESTS
    // ========================================

    #[Test]
    public function rmib_pekerjaan_seeder_menghasilkan_216_records()
    {
        // 12 kategori × 9 kelompok × 2 gender = 216 total
        $totalPekerjaan = RmibPekerjaan::count();
        $this->assertEquals(216, $totalPekerjaan);

        // 108 per gender
        $this->assertEquals(108, RmibPekerjaan::where('gender', 'L')->count());
        $this->assertEquals(108, RmibPekerjaan::where('gender', 'P')->count());
    }

    #[Test]
    public function circular_shift_pattern_exists()
    {
        // Cluster A starts at Outdoor
        $clusterA = RmibPekerjaan::where('gender', 'L')
            ->where('kelompok', 'A')
            ->orderBy('id')
            ->first();

        $this->assertEquals('Outdoor', $clusterA->kategori);

        // Cluster B starts at Mechanical
        $clusterB = RmibPekerjaan::where('gender', 'L')
            ->where('kelompok', 'B')
            ->orderBy('id')
            ->first();

        $this->assertEquals('Mechanical', $clusterB->kategori);
    }
}
