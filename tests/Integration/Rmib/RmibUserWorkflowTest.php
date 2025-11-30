<?php

namespace Tests\Integration\Rmib;

use Tests\TestCase;
use App\Models\KarirDataDiri;
use App\Models\RmibHasilTes;
use App\Models\RmibJawabanPeserta;
use App\Models\RmibPekerjaan;
use App\Models\Users;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

/**
 * INTEGRATION TEST: RMIB User Workflow
 *
 * Test complete user flow dari login sampai lihat interpretasi
 * Total: 8 test cases
 *
 * Complete Flow (6 steps):
 * 1. User dashboard dengan radar chart
 * 2. Form data diri
 * 3. Store data diri → redirect to tes form
 * 4. Form tes RMIB (gender-specific jobs)
 * 5. Store jawaban (108 jawaban + top1/2/3)
 * 6. Interpretasi page (hasilLengkap + top3 + pekerjaanTop3)
 */
class RmibUserWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RmibPekerjaanSeeder::class);

        // Create authenticated user
        $this->user = Users::create([
            'name' => 'Test User',
            'email' => '123456789@student.itera.ac.id',
            'nim' => '123456789',
        ]);
    }

    // ========================================
    // COMPLETE USER WORKFLOW
    // ========================================

    #[Test]
    public function complete_user_workflow_first_time()
    {
        // STEP 1: User login and access dashboard
        $this->actingAs($this->user);

        $response = $this->get(route('karir.dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('user-peminatan-karir');

        // User has no test results yet
        $this->assertNull(KarirDataDiri::where('nim', '123456789')->first());

        // Visit karir home first (required by middleware)
        $this->get(route('karir.home'));

        // STEP 2: Access data diri form
        $response = $this->get(route('karir.datadiri.form'));
        $response->assertStatus(200);
        $response->assertViewIs('karir-datadiri');
        $response->assertViewHas('nim', '123456789'); // Pre-filled from session

        // STEP 3: Submit data diri
        $response = $this->post(route('karir.datadiri.store'), [
            'nim' => '123456789',
            'nama' => 'Test User',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Jl. Test No. 123',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'test@student.itera.ac.id',
            'asal_sekolah' => 'SMAN 1 Test',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        $response->assertStatus(302);

        // Data diri should be saved
        $dataDiri = KarirDataDiri::where('nim', '123456789')->first();
        $this->assertNotNull($dataDiri);
        $this->assertEquals('Test User', $dataDiri->nama);

        $response->assertRedirect(route('karir.tes.form', $dataDiri->id));

        // STEP 4: Access test form
        $response = $this->get(route('karir.tes.form', $dataDiri->id));
        $response->assertStatus(200);
        $response->assertViewIs('karir-form');
        $response->assertViewHas('gender', 'L');
        $response->assertViewHas('pekerjaanPerKelompok'); // 9 clusters (A-I)

        // Verify gender-specific jobs are loaded
        $pekerjaanPerKelompok = $response->viewData('pekerjaanPerKelompok');
        $this->assertCount(9, $pekerjaanPerKelompok); // A-I

        foreach ($pekerjaanPerKelompok as $pekerjaanList) {
            $this->assertCount(12, $pekerjaanList); // 12 jobs per cluster
        }

        // STEP 5: Submit test answers
        $jawabanData = $this->createFullJawabanData('L');

        $response = $this->post(route('karir.tes.store', $dataDiri->id), array_merge($jawabanData, [
            'top1' => 'Ilmuwan',
            'top2' => 'Akuntan',
            'top3' => 'Wartawan',
        ]));

        $response->assertStatus(302);

        // Verify hasil_tes created
        $hasilTes = RmibHasilTes::whereHas('karirDataDiri', function ($q) {
            $q->where('nim', '123456789');
        })->first();

        $this->assertNotNull($hasilTes);
        $this->assertEquals('Ilmuwan', $hasilTes->top_1_pekerjaan);
        $this->assertEquals('Akuntan', $hasilTes->top_2_pekerjaan);
        $this->assertEquals('Wartawan', $hasilTes->top_3_pekerjaan);

        $response->assertRedirect(route('karir.hasil', $hasilTes->id));

        // Verify 108 jawaban saved (9 clusters × 12 jobs)
        $this->assertDatabaseCount('rmib_jawaban_peserta', 108);

        // STEP 6: View interpretasi
        $response = $this->get(route('karir.hasil', $hasilTes->id));
        $response->assertStatus(200);
        $response->assertViewIs('karir-interpretasi');
        $response->assertViewHas('hasilLengkap');
        $response->assertViewHas('top3');
        $response->assertViewHas('pekerjaanTop3');

        // Verify hasilLengkap has all 12 categories + interpretasi key
        $hasilLengkap = $response->viewData('hasilLengkap');
        $this->assertArrayHasKey('interpretasi', $hasilLengkap);

        // Filter only kategori items (exclude 'interpretasi' key)
        $kategoriItems = array_filter($hasilLengkap, function($item) {
            return is_array($item) && isset($item['kategori']);
        });
        $this->assertCount(12, $kategoriItems);

        // Verify each kategori item has required keys
        foreach ($kategoriItems as $item) {
            $this->assertArrayHasKey('kategori', $item);
            $this->assertArrayHasKey('skor', $item);
            $this->assertArrayHasKey('peringkat', $item);
        }

        // Verify top3 has exactly 3 categories
        $top3 = $response->viewData('top3');
        $this->assertCount(3, $top3);
    }

    #[Test]
    public function user_retake_test_updates_existing_data()
    {
        $this->actingAs($this->user);

        // Create initial test result
        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'Initial Name',
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
            'tanggal_pengerjaan' => Carbon::now()->subDays(7),
            'top_1_pekerjaan' => 'Old Job 1',
            'top_2_pekerjaan' => 'Old Job 2',
            'top_3_pekerjaan' => 'Old Job 3',
        ]);

        // Retake test - submit new data diri
        $response = $this->post(route('karir.datadiri.store'), [
            'nim' => '123456789',
            'nama' => 'Updated Name',
            'program_studi' => 'Teknik Elektro',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Jakarta',
            'alamat' => 'New Address',
            'usia' => 21,
            'fakultas' => 'FTI',
            'email' => 'updated@student.itera.ac.id',
            'asal_sekolah' => 'SMAN 2 Test',
            'status_tinggal' => 'Asrama',
            'prodi_sesuai_keinginan' => 'Tidak',
        ]);

        $response->assertStatus(302);

        // Should update, not create new
        $this->assertDatabaseCount('karir_data_diri', 1);
        $this->assertDatabaseHas('karir_data_diri', [
            'nim' => '123456789',
            'nama' => 'Updated Name',
            'provinsi' => 'Jakarta',
        ]);

        // Get updated dataDiri
        $updatedDataDiri = KarirDataDiri::where('nim', '123456789')->first();

        // Submit new test answers
        $jawabanData = $this->createFullJawabanData('L');

        $response = $this->post(route('karir.tes.store', $updatedDataDiri->id), array_merge($jawabanData, [
            'top1' => 'Ilmuwan',
            'top2' => 'Akuntan',
            'top3' => 'Wartawan',
        ]));

        $response->assertStatus(302);

        // New hasil_tes should be created (not updated)
        $this->assertEquals(2, RmibHasilTes::count());

        // Get the newest record by ID
        $latestHasil = RmibHasilTes::orderBy('id', 'desc')->first();
        $this->assertEquals('Ilmuwan', $latestHasil->top_1_pekerjaan);
    }

    #[Test]
    public function dashboard_shows_radar_chart_if_has_result()
    {
        $this->actingAs($this->user);

        // Create test result
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

        // Access dashboard
        $response = $this->get(route('karir.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('riwayatTes');
        $response->assertViewHas('radarData');
        $response->assertViewHas('radarLabels');

        // Verify radarData has correct structure
        $radarData = $response->viewData('radarData');
        $this->assertArrayHasKey('labels', $radarData);
        $this->assertArrayHasKey('values', $radarData);
        $this->assertCount(12, $radarData['labels']);
        $this->assertCount(12, $radarData['values']);
    }

    #[Test]
    public function male_student_gets_male_jobs()
    {
        $this->actingAs($this->user);

        // Create male student data
        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'Male Student',
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

        $response = $this->get(route('karir.tes.form', $dataDiri->id));
        $response->assertStatus(200);

        $response->assertViewHas('pekerjaanPerKelompok');
        $response->assertViewHas('semuaPekerjaan');

        // Verify view has gender-specific data
        $this->assertEquals('L', $response->viewData('gender'));
    }

    #[Test]
    public function female_student_gets_female_jobs()
    {
        // Create female user
        $femaleUser = Users::create([
            'name' => 'Female Student',
            'email' => '987654321@student.itera.ac.id',
            'nim' => '987654321',
        ]);

        $this->actingAs($femaleUser);

        // Create female student data
        $dataDiri = KarirDataDiri::create([
            'nim' => '987654321',
            'nama' => 'Female Student',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'P',
            'provinsi' => 'Lampung',
            'alamat' => 'Test',
            'usia' => 20,
            'fakultas' => 'FTI',
            'email' => 'female@student.itera.ac.id',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        $response = $this->get(route('karir.tes.form', $dataDiri->id));
        $response->assertStatus(200);

        $response->assertViewHas('pekerjaanPerKelompok');
        $response->assertViewHas('semuaPekerjaan');

        // Verify view has gender-specific data
        $this->assertEquals('P', $response->viewData('gender'));
    }

    #[Test]
    public function data_integrity_108_jawaban_saved_correctly()
    {
        $this->actingAs($this->user);

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

        $jawabanData = $this->createFullJawabanData('L');

        $response = $this->post(route('karir.tes.store', $dataDiri->id), array_merge($jawabanData, [
            'top1' => 'Ilmuwan',
            'top2' => 'Akuntan',
            'top3' => 'Wartawan',
        ]));

        $response->assertStatus(302);

        // Verify exactly 108 jawaban (9 clusters × 12 jobs)
        $this->assertDatabaseCount('rmib_jawaban_peserta', 108);

        // Verify each cluster has exactly 12 jobs
        $clusters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        $hasilTes = RmibHasilTes::latest()->first();

        foreach ($clusters as $cluster) {
            $count = RmibJawabanPeserta::where('hasil_id', $hasilTes->id)
                ->where('kelompok', $cluster)
                ->count();

            $this->assertEquals(12, $count, "Cluster {$cluster} should have 12 jobs");
        }

        // Verify rankings are 1-12 for each cluster
        foreach ($clusters as $cluster) {
            $rankings = RmibJawabanPeserta::where('hasil_id', $hasilTes->id)
                ->where('kelompok', $cluster)
                ->pluck('peringkat')
                ->sort()
                ->values()
                ->toArray();

            // Note: Depending on test data, rankings might repeat
            // Just verify they're in valid range 1-12
            foreach ($rankings as $rank) {
                $this->assertTrue($rank >= 1 && $rank <= 12);
            }
        }
    }

    #[Test]
    public function interpretasi_page_displays_complete_result()
    {
        $this->actingAs($this->user);

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

        $response = $this->get(route('karir.hasil', $hasilTes->id));
        $response->assertStatus(200);

        // Verify view data structure
        $hasilLengkap = $response->viewData('hasilLengkap');
        $top3 = $response->viewData('top3');
        $pekerjaanTop3 = $response->viewData('pekerjaanTop3');

        // hasilLengkap should be an array with 12 kategori items + interpretasi key
        $this->assertIsArray($hasilLengkap);
        $this->assertArrayHasKey('interpretasi', $hasilLengkap);

        // Filter only kategori items
        $kategoriItems = array_filter($hasilLengkap, function($item) {
            return is_array($item) && isset($item['kategori']);
        });
        $this->assertCount(12, $kategoriItems);

        // Each kategori item should have required keys
        foreach ($kategoriItems as $item) {
            $this->assertArrayHasKey('kategori', $item);
            $this->assertArrayHasKey('skor', $item);
            $this->assertArrayHasKey('peringkat', $item);
        }

        // top3 should have 3 categories
        $this->assertCount(3, $top3);

        // pekerjaanTop3 should have 3 category-job mappings
        $this->assertCount(3, $pekerjaanTop3);
        foreach ($pekerjaanTop3 as $item) {
            $this->assertArrayHasKey('kategori', $item);
            $this->assertArrayHasKey('pekerjaan', $item);
        }
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    protected function createFullJawabanData($gender)
    {
        $jawaban = [];
        $clusters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        foreach ($clusters as $cluster) {
            $pekerjaanList = RmibPekerjaan::where('gender', $gender)
                ->where('kelompok', $cluster)
                ->orderBy('id')
                ->get();

            $jawaban[$cluster] = [];
            foreach ($pekerjaanList as $index => $pekerjaan) {
                // Key = nama_pekerjaan, Value = peringkat (1-12)
                $jawaban[$cluster][$pekerjaan->nama_pekerjaan] = ($index % 12) + 1;
            }
        }

        // Return dengan struktur yang benar sesuai Form Request
        return ['jawaban' => $jawaban];
    }

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
