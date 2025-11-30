<?php

namespace Tests\Integration\Rmib;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\KarirDataDiri;
use App\Models\RmibHasilTes;
use App\Models\RmibJawabanPeserta;
use App\Models\RmibPekerjaan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

/**
 * INTEGRATION TEST: RMIB Admin Workflow
 *
 * Test complete admin flow dari dashboard sampai detail hasil
 * Total: 10 test cases
 *
 * Complete Flow:
 * 1. Admin dashboard (statistics, pagination, search)
 * 2. Admin detail (matrix 12×9, sum, rank)
 * 3. Delete hasil tes (cascade delete jawaban)
 * 4. Export Excel
 * 5. Provinsi statistics
 * 6. Prodi statistics
 */
class RmibAdminWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RmibPekerjaanSeeder::class);

        // Create admin user
        $this->admin = Admin::create([
            'username' => 'testadmin',
            'email' => 'admin@itera.ac.id',
            'password' => Hash::make('admin123'),
        ]);
    }

    // ========================================
    // ADMIN DASHBOARD TESTS
    // ========================================

    #[Test]
    public function admin_dashboard_menampilkan_semua_hasil_tes()
    {
        $this->actingAs($this->admin, 'admin');

        // Create 3 test results
        for ($i = 1; $i <= 3; $i++) {
            $dataDiri = KarirDataDiri::create([
                'nim' => "12345678{$i}",
                'nama' => "Test User {$i}",
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

            RmibHasilTes::create([
                'karir_data_diri_id' => $dataDiri->id,
                'tanggal_pengerjaan' => Carbon::now(),
                'top_1_pekerjaan' => 'Ilmuwan',
                'top_2_pekerjaan' => 'Akuntan',
                'top_3_pekerjaan' => 'Wartawan',
            ]);
        }

        $response = $this->get(route('admin.karir.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin-karir');
        $response->assertViewHas('hasilTes');

        // Verify pagination
        $hasilTes = $response->viewData('hasilTes');
        $this->assertGreaterThanOrEqual(3, $hasilTes->total());
    }

    #[Test]
    public function admin_dashboard_statistics_cards()
    {
        $this->actingAs($this->admin, 'admin');

        // Create test data with different prodi
        $dataDiri1 = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'User Informatika',
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

        $dataDiri2 = KarirDataDiri::create([
            'nim' => '987654321',
            'nama' => 'User Elektro',
            'program_studi' => 'Teknik Elektro',
            'jenis_kelamin' => 'P',
            'provinsi' => 'Jakarta',
            'alamat' => 'Test',
            'usia' => 21,
            'fakultas' => 'FTI',
            'email' => 'test2@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Asrama',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri1->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri2->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Engineer',
            'top_2_pekerjaan' => 'Designer',
            'top_3_pekerjaan' => 'Manager',
        ]);

        $response = $this->get(route('admin.karir.index'));
        $response->assertStatus(200);

        // Verify statistics are passed to view
        $response->assertViewHas('totalPeserta');
        $response->assertViewHas('totalProdi');
        $response->assertViewHas('totalProvinsi');

        $totalPeserta = $response->viewData('totalPeserta');
        $this->assertEquals(2, $totalPeserta);
    }

    #[Test]
    public function admin_search_by_nama()
    {
        $this->actingAs($this->admin, 'admin');

        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'John Doe Unique',
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
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        $response = $this->get(route('admin.karir.index', ['search' => 'John Doe']));
        $response->assertStatus(200);

        $hasilTes = $response->viewData('hasilTes');
        $this->assertGreaterThan(0, $hasilTes->total());
    }

    #[Test]
    public function admin_filter_by_prodi()
    {
        $this->actingAs($this->admin, 'admin');

        // Create data with specific prodi
        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'User Informatika',
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
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        $response = $this->get(route('admin.karir.index', ['prodi' => 'Teknik Informatika']));
        $response->assertStatus(200);

        $hasilTes = $response->viewData('hasilTes');

        // All results should be from Teknik Informatika
        foreach ($hasilTes as $hasil) {
            $this->assertEquals('Teknik Informatika', $hasil->dataDiri->program_studi);
        }
    }

    #[Test]
    public function admin_pagination_works()
    {
        $this->actingAs($this->admin, 'admin');

        // Create 25 test results (more than 1 page if paginated by 20)
        for ($i = 1; $i <= 25; $i++) {
            $dataDiri = KarirDataDiri::create([
                'nim' => sprintf('12345%04d', $i),
                'nama' => "Test User {$i}",
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

            RmibHasilTes::create([
                'karir_data_diri_id' => $dataDiri->id,
                'tanggal_pengerjaan' => Carbon::now(),
                'top_1_pekerjaan' => 'Ilmuwan',
                'top_2_pekerjaan' => 'Akuntan',
                'top_3_pekerjaan' => 'Wartawan',
            ]);
        }

        $response = $this->get(route('admin.karir.index'));
        $response->assertStatus(200);

        $hasilTes = $response->viewData('hasilTes');
        $this->assertEquals(25, $hasilTes->total());
        $this->assertGreaterThan(1, $hasilTes->lastPage());
    }

    // ========================================
    // ADMIN DETAIL TESTS
    // ========================================

    #[Test]
    public function admin_detail_menampilkan_matrix_12x9()
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
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        $this->createFullJawaban($hasilTes->id, 'L');

        $response = $this->get(route('admin.karir.detail', $hasilTes->id));
        $response->assertStatus(200);
        $response->assertViewIs('karir-detail-hasil');
        $response->assertViewHas('hasil');
        $response->assertViewHas('hasilLengkap');
        $response->assertViewHas('matrixData');

        // Verify matrixData has the required keys
        $matrixData = $response->viewData('matrixData');
        $this->assertArrayHasKey('matrix', $matrixData);
        $this->assertArrayHasKey('sum', $matrixData);
        $this->assertArrayHasKey('rank', $matrixData);
        $this->assertArrayHasKey('kategori_urutan', $matrixData);

        // Verify matrix structure from matrixData
        $matrix = $matrixData['matrix'];
        $sum = $matrixData['sum'];
        $rank = $matrixData['rank'];

        // 12 rows (categories)
        $this->assertCount(12, $matrix);

        // Each row has 9 columns (clusters A-I)
        foreach ($matrix as $categoryData) {
            $this->assertCount(9, $categoryData);
        }

        // Sum and rank also have 12 entries
        $this->assertCount(12, $sum);
        $this->assertCount(12, $rank);

        // NOTE: percentage NOT tested (deprecated, not displayed in view)
    }

    #[Test]
    public function admin_can_delete_hasil_tes()
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
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        $this->createFullJawaban($hasilTes->id, 'L');

        // Verify jawaban exists
        $this->assertDatabaseCount('rmib_jawaban_peserta', 108);

        // Delete
        $response = $this->delete(route('admin.karir.delete', $hasilTes->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.karir.index'));

        // Verify deleted
        $this->assertDatabaseMissing('rmib_hasil_tes', ['id' => $hasilTes->id]);

        // Verify cascade delete (jawaban also deleted)
        $this->assertDatabaseCount('rmib_jawaban_peserta', 0);
    }

    #[Test]
    public function admin_export_excel()
    {
        $this->actingAs($this->admin, 'admin');

        // Create test data
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
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        $response = $this->get(route('admin.karir.export.excel'));

        // Should download Excel file
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    #[Test]
    public function admin_provinsi_statistics()
    {
        $this->actingAs($this->admin, 'admin');

        // Create data from different provinces
        $provinsiData = [
            ['provinsi' => 'Lampung', 'count' => 3],
            ['provinsi' => 'Jakarta', 'count' => 2],
            ['provinsi' => 'Banten', 'count' => 1],
        ];

        foreach ($provinsiData as $data) {
            for ($i = 0; $i < $data['count']; $i++) {
                $dataDiri = KarirDataDiri::create([
                    'nim' => uniqid(),
                    'nama' => "User {$data['provinsi']} {$i}",
                    'program_studi' => 'Teknik Informatika',
                    'jenis_kelamin' => 'L',
                    'provinsi' => $data['provinsi'],
                    'alamat' => 'Test',
                    'usia' => 20,
                    'fakultas' => 'FTI',
                    'email' => uniqid() . '@student.itera.ac.id',
                    'asal_sekolah' => 'SMA',
                    'status_tinggal' => 'Kost',
                    'prodi_sesuai_keinginan' => 'Ya',
                ]);

                RmibHasilTes::create([
                    'karir_data_diri_id' => $dataDiri->id,
                    'tanggal_pengerjaan' => Carbon::now(),
                    'top_1_pekerjaan' => 'Ilmuwan',
                    'top_2_pekerjaan' => 'Akuntan',
                    'top_3_pekerjaan' => 'Wartawan',
                ]);
            }
        }

        $response = $this->get(route('admin.karir.index'));
        $response->assertStatus(200);

        // Verify province statistics
        $totalProvinsi = $response->viewData('totalProvinsi');
        $this->assertEquals(3, $totalProvinsi);
    }

    #[Test]
    public function admin_prodi_statistics()
    {
        $this->actingAs($this->admin, 'admin');

        // Create data from different prodi
        $prodiData = [
            'Teknik Informatika' => 4,
            'Teknik Elektro' => 2,
            'Teknik Sipil' => 1,
        ];

        foreach ($prodiData as $prodi => $count) {
            for ($i = 0; $i < $count; $i++) {
                $dataDiri = KarirDataDiri::create([
                    'nim' => uniqid(),
                    'nama' => "User {$prodi} {$i}",
                    'program_studi' => $prodi,
                    'jenis_kelamin' => 'L',
                    'provinsi' => 'Lampung',
                    'alamat' => 'Test',
                    'usia' => 20,
                    'fakultas' => 'FTI',
                    'email' => uniqid() . '@student.itera.ac.id',
                    'asal_sekolah' => 'SMA',
                    'status_tinggal' => 'Kost',
                    'prodi_sesuai_keinginan' => 'Ya',
                ]);

                RmibHasilTes::create([
                    'karir_data_diri_id' => $dataDiri->id,
                    'tanggal_pengerjaan' => Carbon::now(),
                    'top_1_pekerjaan' => 'Ilmuwan',
                    'top_2_pekerjaan' => 'Akuntan',
                    'top_3_pekerjaan' => 'Wartawan',
                ]);
            }
        }

        $response = $this->get(route('admin.karir.index'));
        $response->assertStatus(200);

        // Verify prodi statistics
        $totalProdi = $response->viewData('totalProdi');
        $this->assertEquals(3, $totalProdi);
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    protected function createFullJawaban($hasilId, $gender)
    {
        $clusters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        foreach ($clusters as $cluster) {
            $pekerjaanList = RmibPekerjaan::where('gender', $gender)
                ->where('kelompok', $cluster)
                ->orderBy('id')
                ->get();

            foreach ($pekerjaanList as $index => $pekerjaan) {
                RmibJawabanPeserta::create([
                    'hasil_id' => $hasilId,
                    'kelompok' => $cluster,
                    'pekerjaan' => $pekerjaan->nama_pekerjaan,
                    'peringkat' => ($index % 12) + 1,
                ]);
            }
        }
    }
}
