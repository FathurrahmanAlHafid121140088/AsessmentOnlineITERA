<?php

namespace Tests\Unit\Rmib;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\KarirDataDiri;
use App\Models\RmibHasilTes;
use App\Models\RmibJawabanPeserta;
use App\Models\RmibPekerjaan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KarirExport;

/**
 * UNIT TEST: Additional KarirController Methods
 *
 * Coverage untuk method-method KarirController yang belum di-test:
 * - destroy() - Hapus hasil tes
 * - exportExcel() - Export ke Excel
 * - adminProvinsi() - Statistik provinsi
 * - adminProgramStudi() - Statistik program studi
 * - form() - Deprecated method
 */
class RmibControllerAdditionalTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RmibPekerjaanSeeder::class);

        $this->admin = Admin::create([
            'username' => 'admintest',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    // ========================================
    // DESTROY METHOD
    // ========================================

    #[Test]
    public function destroy_deletes_hasil_tes_and_jawaban()
    {
        $this->actingAs($this->admin, 'admin');

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
            'top_1_pekerjaan' => 'Engineer',
            'top_2_pekerjaan' => 'Programmer',
            'top_3_pekerjaan' => 'Analyst',
        ]);

        // Create jawaban
        RmibJawabanPeserta::create([
            'hasil_id' => $hasilTes->id,
            'kelompok' => 'A',
            'pekerjaan' => 'Test Job',
            'peringkat' => 1,
        ]);

        $response = $this->delete(route('admin.karir.delete', $hasilTes->id));

        $response->assertRedirect(route('admin.karir.index'));
        $response->assertSessionHas('success');

        // Verify hasil tes deleted
        $this->assertDatabaseMissing('rmib_hasil_tes', ['id' => $hasilTes->id]);

        // Verify jawaban deleted (cascade)
        $this->assertDatabaseMissing('rmib_jawaban_peserta', ['hasil_id' => $hasilTes->id]);
    }

    #[Test]
    public function destroy_handles_non_existent_hasil_tes()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->delete(route('admin.karir.delete', 999));

        // Controller catches exception and redirects with error message
        $response->assertRedirect(route('admin.karir.index'));
        $response->assertSessionHas('error');
    }

    // ========================================
    // EXPORT EXCEL METHOD
    // ========================================

    #[Test]
    public function export_excel_downloads_file()
    {
        $this->actingAs($this->admin, 'admin');

        Excel::fake();

        // Freeze time to make filename predictable
        $frozenTime = Carbon::create(2025, 11, 29, 14, 30, 45);
        Carbon::setTestNow($frozenTime);

        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'Test User',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Engineer',
            'top_2_pekerjaan' => 'Programmer',
            'top_3_pekerjaan' => 'Analyst',
        ]);

        $response = $this->get(route('admin.karir.export.excel'));

        // Expected filename with frozen timestamp
        $expectedFilename = 'Data_Hasil_Tes_RMIB_20251129143045.xlsx';

        Excel::assertDownloaded($expectedFilename, function(KarirExport $export) {
            return true;
        });

        // Clean up
        Carbon::setTestNow();
    }

    #[Test]
    public function export_excel_with_empty_data()
    {
        $this->actingAs($this->admin, 'admin');

        Excel::fake();

        // Freeze time to make filename predictable
        $frozenTime = Carbon::create(2025, 11, 29, 15, 0, 0);
        Carbon::setTestNow($frozenTime);

        // No data created - test empty export
        $response = $this->get(route('admin.karir.export.excel'));

        // Expected filename with frozen timestamp
        $expectedFilename = 'Data_Hasil_Tes_RMIB_20251129150000.xlsx';

        Excel::assertDownloaded($expectedFilename, function(KarirExport $export) {
            return $export instanceof KarirExport;
        });

        // Clean up
        Carbon::setTestNow();
    }

    // ========================================
    // ADMIN PROVINSI METHOD
    // ========================================

    #[Test]
    public function admin_provinsi_displays_statistics()
    {
        $this->actingAs($this->admin, 'admin');

        $dataDiri1 = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'User Lampung',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        // ✅ CREATE HASIL TES
        RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri1->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Engineer',
            'top_2_pekerjaan' => 'Programmer',
            'top_3_pekerjaan' => 'Analyst',
        ]);

        $dataDiri2 = KarirDataDiri::create([
            'nim' => '987654321',
            'nama' => 'User Jakarta',
            'program_studi' => 'Teknik Sipil',
            'jenis_kelamin' => 'P',
            'provinsi' => 'DKI Jakarta',
            'alamat' => 'Test',
            'usia' => 21,
            'fakultas' => 'FTIK',
            'email' => 'test2@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Tidak',
        ]);

        // ✅ CREATE HASIL TES
        RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri2->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Architect',
            'top_2_pekerjaan' => 'Designer',
            'top_3_pekerjaan' => 'Manager',
        ]);

        $response = $this->get(route('admin.karir.provinsi'));

        $response->assertStatus(200);
        $response->assertViewIs('admin-karir-provinsi');
        $response->assertViewHas('provinsiData');
        $response->assertViewHas('totalUsers', 2);

        // Verify provinsi data structure
        $provinsiData = $response->viewData('provinsiData');
        $this->assertIsArray($provinsiData);

        // Find Lampung in the data
        $lampungData = collect($provinsiData)->firstWhere('nama', 'Lampung');
        $this->assertNotNull($lampungData);
        $this->assertEquals(1, $lampungData['jumlah']);
        $this->assertEquals(50.0, $lampungData['persentase']);
    }

    #[Test]
    public function admin_provinsi_calculates_percentage_correctly()
    {
        $this->actingAs($this->admin, 'admin');

        // Create 3 users from Lampung, 1 from Jakarta
        for ($i = 0; $i < 3; $i++) {
            $dataDiri = KarirDataDiri::create([
                'nim' => '12345678' . $i,
                'nama' => 'User Lampung ' . $i,
                'program_studi' => 'Teknik Informatika',
                'jenis_kelamin' => 'L',
                'provinsi' => 'Lampung',
                'alamat' => 'Test',
                'usia' => 20,
                'fakultas' => 'FTI',
                'email' => "test{$i}@student.itera.ac.id",
                'asal_sekolah' => 'SMA',
                'status_tinggal' => 'Kost',
                'prodi_sesuai_keinginan' => 'Ya',
            ]);

            // ✅ CREATE HASIL TES for each user
            RmibHasilTes::create([
                'karir_data_diri_id' => $dataDiri->id,
                'tanggal_pengerjaan' => Carbon::now(),
                'top_1_pekerjaan' => 'Engineer',
                'top_2_pekerjaan' => 'Programmer',
                'top_3_pekerjaan' => 'Analyst',
            ]);
        }

        $dataDiri4 = KarirDataDiri::create([
            'nim' => '987654321',
            'nama' => 'User Jakarta',
            'program_studi' => 'Teknik Sipil',
            'jenis_kelamin' => 'P',
            'provinsi' => 'DKI Jakarta',
            'alamat' => 'Test',
            'usia' => 21,
            'fakultas' => 'FTIK',
            'email' => 'jakarta@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        // ✅ CREATE HASIL TES
        RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri4->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Architect',
            'top_2_pekerjaan' => 'Designer',
            'top_3_pekerjaan' => 'Manager',
        ]);

        $response = $this->get(route('admin.karir.provinsi'));

        $provinsiData = $response->viewData('provinsiData');

        $lampungData = collect($provinsiData)->firstWhere('nama', 'Lampung');
        $this->assertEquals(3, $lampungData['jumlah']);
        $this->assertEquals(75.0, $lampungData['persentase']); // 3/4 * 100
    }

    // ========================================
    // ADMIN PROGRAM STUDI METHOD
    // ========================================

    #[Test]
    public function admin_program_studi_displays_statistics()
    {
        $this->actingAs($this->admin, 'admin');

        $dataDiri1 = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'User TI',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        // ✅ CREATE HASIL TES
        RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri1->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Engineer',
            'top_2_pekerjaan' => 'Programmer',
            'top_3_pekerjaan' => 'Analyst',
        ]);

        $dataDiri2 = KarirDataDiri::create([
            'nim' => '987654321',
            'nama' => 'User Sipil',
            'program_studi' => 'Teknik Sipil',
            'jenis_kelamin' => 'P',
            'provinsi' => 'Lampung',
            'alamat' => 'Test',
            'usia' => 21,
            'fakultas' => 'FTIK',
            'email' => 'test2@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        // ✅ CREATE HASIL TES
        RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri2->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Architect',
            'top_2_pekerjaan' => 'Designer',
            'top_3_pekerjaan' => 'Manager',
        ]);

        $response = $this->get(route('admin.karir.program-studi'));

        $response->assertStatus(200);
        $response->assertViewIs('admin-karir-program-studi');
        $response->assertViewHas('prodiData');
        $response->assertViewHas('totalUsers', 2);
        $response->assertViewHas('fakultasStats');

        // Verify fakultas stats
        $fakultasStats = $response->viewData('fakultasStats');
        $this->assertArrayHasKey('FTI', $fakultasStats);
        $this->assertArrayHasKey('FTIK', $fakultasStats);
        $this->assertEquals(1, $fakultasStats['FTI']['jumlah']);
        $this->assertEquals(1, $fakultasStats['FTIK']['jumlah']);
    }

    #[Test]
    public function admin_program_studi_groups_by_fakultas()
    {
        $this->actingAs($this->admin, 'admin');

        // Create 2 FTI students
        $dataDiri1 = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'User TI',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test1@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        // ✅ CREATE HASIL TES
        RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri1->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Engineer',
            'top_2_pekerjaan' => 'Programmer',
            'top_3_pekerjaan' => 'Analyst',
        ]);

        $dataDiri2 = KarirDataDiri::create([
            'nim' => '987654321',
            'nama' => 'User TE',
            'program_studi' => 'Teknik Elektro',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test2@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        // ✅ CREATE HASIL TES
        RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri2->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Engineer',
            'top_2_pekerjaan' => 'Developer',
            'top_3_pekerjaan' => 'Scientist',
        ]);

        $response = $this->get(route('admin.karir.program-studi'));

        $fakultasStats = $response->viewData('fakultasStats');
        $this->assertEquals(2, $fakultasStats['FTI']['jumlah']);
        $this->assertEquals(100.0, $fakultasStats['FTI']['persentase']);
    }

    // ========================================
    // LIST PEKERJAAN METHOD
    // ========================================

    #[Test]
    public function admin_list_pekerjaan_displays_jobs()
    {
        $this->actingAs($this->admin, 'admin');

        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'Test User',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test',
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
            'top_1_pekerjaan' => 'Engineer',
            'top_2_pekerjaan' => 'Programmer',
            'top_3_pekerjaan' => 'Analyst',
        ]);

        $response = $this->get(route('admin.karir.list-pekerjaan', $hasilTes->id));

        $response->assertStatus(200);
        $response->assertViewIs('karir-list-pekerjaan');
        $response->assertViewHas('hasil');
        $response->assertViewHas('gender');
        $response->assertViewHas('daftarPekerjaan');

        // Verify pekerjaan grouped by kelompok
        $daftarPekerjaan = $response->viewData('daftarPekerjaan');
        $this->assertCount(9, $daftarPekerjaan); // 9 kelompok (A-I)
    }
}
