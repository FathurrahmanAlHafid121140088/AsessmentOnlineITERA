<?php

namespace Tests\Feature\Rmib;

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
 * FEATURE TEST: RMIB Admin Features
 *
 * Test admin-facing UI/UX features
 * Total: 8 test cases
 *
 * Coverage:
 * - Dashboard statistics cards
 * - Search functionality
 * - Filter by prodi/provinsi
 * - Pagination
 * - Detail page shows matrix correctly
 */
class RmibAdminFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RmibPekerjaanSeeder::class);

        $this->admin = Admin::create([
            'username' => 'testadmin',
            'email' => 'admin@itera.ac.id',
            'password' => Hash::make('admin123'),
        ]);
    }

    // ========================================
    // DASHBOARD FEATURES
    // ========================================

    #[Test]
    public function dashboard_displays_statistics_cards()
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

        $response = $this->get(route('admin.karir.index'));
        $response->assertStatus(200);

        // Verify statistics data is passed
        $response->assertViewHas('totalPeserta');
        $response->assertViewHas('totalProdi');
        $response->assertViewHas('totalProvinsi');

        // Verify values are numeric
        $totalPeserta = $response->viewData('totalPeserta');
        $this->assertIsNumeric($totalPeserta);
        $this->assertGreaterThanOrEqual(1, $totalPeserta);
    }

    #[Test]
    public function dashboard_displays_list_of_hasil_tes()
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
        $response->assertViewHas('hasilTes');

        // Verify list contains data
        $hasilTes = $response->viewData('hasilTes');
        $this->assertGreaterThanOrEqual(3, $hasilTes->total());

        // Verify user names are displayed
        $response->assertSee('Test User 1');
        $response->assertSee('Test User 2');
        $response->assertSee('Test User 3');
    }

    // ========================================
    // SEARCH FUNCTIONALITY
    // ========================================

    #[Test]
    public function search_by_nama_works()
    {
        $this->actingAs($this->admin, 'admin');

        // Create specific user
        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'John Doe Searchable',
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
        $response->assertSee('John Doe Searchable');
    }

    #[Test]
    public function search_by_nim_works()
    {
        $this->actingAs($this->admin, 'admin');

        $dataDiri = KarirDataDiri::create([
            'nim' => '999888777',
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

        $response = $this->get(route('admin.karir.index', ['search' => '999888777']));
        $response->assertStatus(200);
        $response->assertSee('999888777');
    }

    // ========================================
    // FILTER FUNCTIONALITY
    // ========================================

    #[Test]
    public function filter_by_prodi_works()
    {
        $this->actingAs($this->admin, 'admin');

        // Create users with different prodi
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

        $response = $this->get(route('admin.karir.index', ['prodi' => 'Teknik Informatika']));
        $response->assertStatus(200);
        $response->assertSee('User Informatika');
        $response->assertDontSee('User Elektro');
    }

    #[Test]
    public function filter_by_provinsi_works()
    {
        $this->actingAs($this->admin, 'admin');

        // Create users from different provinces
        $dataDiri1 = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'User Lampung',
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
            'nama' => 'User Jakarta',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Jakarta',
            'alamat' => 'Test',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test2@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
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

        $response = $this->get(route('admin.karir.index', ['provinsi' => 'Lampung']));
        $response->assertStatus(200);
        $response->assertSee('User Lampung');
        $response->assertDontSee('User Jakarta');
    }

    // ========================================
    // DETAIL PAGE FEATURES
    // ========================================

    #[Test]
    public function detail_page_displays_matrix()
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

        // Verify matrix is passed to view
        $response->assertViewHas('matrix');
        $response->assertViewHas('sum');
        $response->assertViewHas('rank');

        // Verify cluster headers (A-I)
        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'] as $cluster) {
            $response->assertSee($cluster);
        }

        // Verify category labels are displayed
        $categories = [
            'Outdoor', 'Mechanical', 'Computational', 'Scientific',
            'Personal Contact', 'Aesthetic', 'Literary', 'Musical',
            'Social Service', 'Clerical', 'Practical', 'Medical'
        ];

        foreach ($categories as $category) {
            $response->assertSee($category);
        }
    }

    #[Test]
    public function pagination_displays_correctly()
    {
        $this->actingAs($this->admin, 'admin');

        // Create many results to trigger pagination
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

        // Verify pagination
        $this->assertEquals(25, $hasilTes->total());
        $this->assertGreaterThan(1, $hasilTes->lastPage());

        // Verify page 2 is accessible
        $response2 = $this->get(route('admin.karir.index', ['page' => 2]));
        $response2->assertStatus(200);
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
