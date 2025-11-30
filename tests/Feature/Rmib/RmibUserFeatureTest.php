<?php

namespace Tests\Feature\Rmib;

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
 * FEATURE TEST: RMIB User Features
 *
 * Test user-facing UI/UX features
 * Total: 5 test cases
 *
 * Coverage:
 * - Dashboard radar chart data
 * - Data diri form with pre-filled NIM
 * - Test form with 9 clusters
 * - Interpretasi page displays correctly
 */
class RmibUserFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RmibPekerjaanSeeder::class);

        $this->user = Users::create([
            'name' => 'Test User',
            'email' => '123456789@student.itera.ac.id',
            'nim' => '123456789',
        ]);
    }

    // ========================================
    // USER DASHBOARD TESTS
    // ========================================

    #[Test]
    public function dashboard_shows_radar_chart_data()
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

        $response = $this->get(route('karir.dashboard'));
        $response->assertStatus(200);

        // Verify radar chart data structure
        $response->assertViewHas('radarData');
        $radarData = $response->viewData('radarData');

        $this->assertArrayHasKey('labels', $radarData);
        $this->assertArrayHasKey('values', $radarData);

        // 12 categories for labels
        $this->assertCount(12, $radarData['labels']);

        // 12 scores for values
        $this->assertCount(12, $radarData['values']);

        // Values should be numeric
        foreach ($radarData['values'] as $value) {
            $this->assertIsNumeric($value);
        }
    }

    #[Test]
    public function dashboard_shows_mulai_tes_button_when_no_result()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('karir.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Mulai Tes');
    }

    // ========================================
    // DATA DIRI FORM TESTS
    // ========================================

    #[Test]
    public function data_diri_form_prefills_nim_from_session()
    {
        $this->actingAs($this->user);
        session(['visited_karir_home' => true]);

        $response = $this->get(route('karir.datadiri.form'));
        $response->assertStatus(200);
        $response->assertViewHas('nim', '123456789');

        // Verify NIM is in the view
        $response->assertSee('123456789');
    }

    // ========================================
    // TEST FORM TESTS
    // ========================================

    #[Test]
    public function tes_form_displays_9_clusters()
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

        $response = $this->get(route('karir.tes.form', $dataDiri->id));
        $response->assertStatus(200);
        $response->assertViewHas('clusters');

        $clusters = $response->viewData('clusters');
        $this->assertCount(9, $clusters);

        // Verify each cluster has 12 jobs
        foreach ($clusters as $cluster => $pekerjaanList) {
            $this->assertCount(12, $pekerjaanList);
        }
    }

    // ========================================
    // INTERPRETASI PAGE TESTS
    // ========================================

    #[Test]
    public function interpretasi_page_displays_all_required_data()
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

        // Verify required view data
        $response->assertViewHas('hasilLengkap');
        $response->assertViewHas('top3');
        $response->assertViewHas('pekerjaanTop3');

        // Verify top 3 job names are displayed
        $response->assertSee('Ilmuwan');
        $response->assertSee('Akuntan');
        $response->assertSee('Wartawan');

        // Verify interpretation text exists
        $hasilLengkap = $response->viewData('hasilLengkap');
        $this->assertArrayHasKey('interpretasi', $hasilLengkap);
        $this->assertIsString($hasilLengkap['interpretasi']);
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

        // Generate interpretasi
        $scoringService = new \App\Services\RmibScoringService();
        $hasilPerhitungan = $scoringService->hitungSemuaSkor($hasilId, $gender);

        $skorKategori = $hasilPerhitungan['skor_kategori'];
        $peringkat = $hasilPerhitungan['peringkat'];

        // Urutkan kategori berdasarkan peringkat (ascending)
        asort($peringkat);
        $top3Kategori = array_slice(array_keys($peringkat), 0, 3, true);

        // Generate interpretasi
        $top3Data = [];
        foreach ($top3Kategori as $kategori) {
            $top3Data[$kategori] = $skorKategori[$kategori];
        }
        $interpretasi = $scoringService->generateInterpretasi($top3Data);

        // Update hasil tes dengan interpretasi
        RmibHasilTes::where('id', $hasilId)->update([
            'interpretasi' => $interpretasi,
        ]);
    }
}
