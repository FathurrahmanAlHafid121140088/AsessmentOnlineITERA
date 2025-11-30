<?php

namespace Tests\Unit\Rmib;

use Tests\TestCase;
use App\Models\KarirDataDiri;
use App\Models\RmibHasilTes;
use App\Models\RmibJawabanPeserta;
use App\Models\RmibPekerjaan;
use App\Services\RmibScoringService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

/**
 * UNIT TEST: RMIB Scoring Service
 *
 * Test HANYA fitur yang DIGUNAKAN di production
 * Total: 12 test cases
 *
 * ✅ TESTED (Actual Usage):
 * - hitungSemuaSkor() → return skor_kategori & peringkat
 * - generateMatrix() → return matrix, sum, rank
 * - generateInterpretasi()
 * - getDeskripsiKategori()
 *
 * ❌ NOT TESTED (Deprecated):
 * - skor_konsistensi (tidak ditampilkan di view)
 * - percentage (tidak ditampilkan di view)
 * - peringkat_per_kategori (internal only)
 */
class RmibScoringServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RmibScoringService $scoringService;
    protected KarirDataDiri $dataDiri;
    protected RmibHasilTes $hasilTes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->scoringService = new RmibScoringService();
        $this->seed(\Database\Seeders\RmibPekerjaanSeeder::class);

        // Create test data
        $this->dataDiri = KarirDataDiri::create([
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

        $this->hasilTes = RmibHasilTes::create([
            'karir_data_diri_id' => $this->dataDiri->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);
    }

    // ========================================
    // SKOR KATEGORI TESTS (Yang Dipakai)
    // ========================================

    #[Test]
    public function hitung_semua_skor_returns_skor_kategori_dan_peringkat()
    {
        $this->createFullJawaban($this->hasilTes->id, 'L');

        $result = $this->scoringService->hitungSemuaSkor($this->hasilTes->id, 'L');

        // Test ONLY fields that are used in views
        $this->assertArrayHasKey('skor_kategori', $result);
        $this->assertArrayHasKey('peringkat', $result);

        // Should have 12 categories
        $this->assertCount(12, $result['skor_kategori']);
        $this->assertCount(12, $result['peringkat']);
    }

    #[Test]
    public function all_categories_present_in_skor_kategori()
    {
        $this->createFullJawaban($this->hasilTes->id, 'L');

        $result = $this->scoringService->hitungSemuaSkor($this->hasilTes->id, 'L');

        $expectedCategories = [
            'Outdoor', 'Mechanical', 'Computational', 'Scientific',
            'Personal Contact', 'Aesthetic', 'Literary', 'Musical',
            'Social Service', 'Clerical', 'Practical', 'Medical'
        ];

        foreach ($expectedCategories as $category) {
            $this->assertArrayHasKey($category, $result['skor_kategori']);
            $this->assertArrayHasKey($category, $result['peringkat']);
        }
    }

    #[Test]
    public function skor_is_sum_of_rankings()
    {
        // Give rank 1 to all jobs (sum should be 1 * 9 = 9 for each category)
        $clusters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        foreach ($clusters as $cluster) {
            $pekerjaanList = RmibPekerjaan::where('gender', 'L')
                ->where('kelompok', $cluster)
                ->orderBy('id')
                ->get();

            foreach ($pekerjaanList as $pekerjaan) {
                RmibJawabanPeserta::create([
                    'hasil_id' => $this->hasilTes->id,
                    'kelompok' => $cluster,
                    'pekerjaan' => $pekerjaan->nama_pekerjaan,
                    'peringkat' => 1,
                ]);
            }
        }

        $result = $this->scoringService->hitungSemuaSkor($this->hasilTes->id, 'L');

        // All categories should have sum = 9
        foreach ($result['skor_kategori'] as $skor) {
            $this->assertEquals(9, $skor);
        }
    }

    // ========================================
    // PERINGKAT TESTS
    // ========================================

    #[Test]
    public function peringkat_is_sequential_1_to_12()
    {
        $this->createFullJawaban($this->hasilTes->id, 'L');

        $result = $this->scoringService->hitungSemuaSkor($this->hasilTes->id, 'L');

        $ranks = array_values($result['peringkat']);
        sort($ranks);

        $this->assertEquals(range(1, 12), $ranks);
    }

    #[Test]
    public function lower_score_gets_better_rank()
    {
        $this->createFullJawaban($this->hasilTes->id, 'L');

        $result = $this->scoringService->hitungSemuaSkor($this->hasilTes->id, 'L');

        // Find category with lowest score
        $lowestScore = min($result['skor_kategori']);
        $lowestScoreCategories = array_keys($result['skor_kategori'], $lowestScore);

        // At least one should have rank 1
        $hasRank1 = false;
        foreach ($lowestScoreCategories as $category) {
            if ($result['peringkat'][$category] === 1) {
                $hasRank1 = true;
                break;
            }
        }

        $this->assertTrue($hasRank1);
    }

    #[Test]
    public function tie_breaker_uses_alphabetical_order()
    {
        // All categories get same score
        $clusters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        foreach ($clusters as $cluster) {
            $pekerjaanList = RmibPekerjaan::where('gender', 'L')
                ->where('kelompok', $cluster)
                ->orderBy('id')
                ->get();

            foreach ($pekerjaanList as $pekerjaan) {
                RmibJawabanPeserta::create([
                    'hasil_id' => $this->hasilTes->id,
                    'kelompok' => $cluster,
                    'pekerjaan' => $pekerjaan->nama_pekerjaan,
                    'peringkat' => 6, // Same for all
                ]);
            }
        }

        $result = $this->scoringService->hitungSemuaSkor($this->hasilTes->id, 'L');

        // Alphabetical: Aesthetic < Clerical < Computational
        $this->assertEquals(1, $result['peringkat']['Aesthetic']);
        $this->assertEquals(2, $result['peringkat']['Clerical']);
    }

    // ========================================
    // MATRIX GENERATION TESTS (Yang Dipakai)
    // ========================================

    #[Test]
    public function generate_matrix_returns_correct_structure()
    {
        $this->createFullJawaban($this->hasilTes->id, 'L');

        $result = $this->scoringService->generateMatrix($this->hasilTes->id, 'L');

        // Test ONLY fields used in admin view
        $this->assertArrayHasKey('matrix', $result);
        $this->assertArrayHasKey('sum', $result);
        $this->assertArrayHasKey('rank', $result);
        $this->assertArrayHasKey('kategori_urutan', $result);
        $this->assertArrayHasKey('kluster_urutan', $result);

        // NOTE: 'percentage' exists but NOT tested because NOT displayed in view
    }

    #[Test]
    public function matrix_has_twelve_rows_and_nine_columns()
    {
        $this->createFullJawaban($this->hasilTes->id, 'L');

        $result = $this->scoringService->generateMatrix($this->hasilTes->id, 'L');

        // 12 rows (categories)
        $this->assertCount(12, $result['matrix']);

        // 9 columns (clusters A-I)
        foreach ($result['matrix'] as $categoryData) {
            $this->assertCount(9, $categoryData);
        }
    }

    #[Test]
    public function matrix_sum_calculation_is_correct()
    {
        $this->createFullJawaban($this->hasilTes->id, 'L');

        $result = $this->scoringService->generateMatrix($this->hasilTes->id, 'L');

        // Verify sum = array_sum of matrix row
        foreach ($result['matrix'] as $category => $clusterData) {
            $calculatedSum = array_sum($clusterData);
            $this->assertEquals($calculatedSum, $result['sum'][$category]);
        }
    }

    // ========================================
    // INTERPRETASI TESTS
    // ========================================

    #[Test]
    public function generate_interpretasi_returns_string()
    {
        $top3 = [
            'Scientific' => 15,
            'Computational' => 18,
            'Medical' => 20,
        ];

        $interpretasi = $this->scoringService->generateInterpretasi($top3);

        $this->assertIsString($interpretasi);
        $this->assertStringContainsString('Scientific', $interpretasi);
        $this->assertStringContainsString('Computational', $interpretasi);
        $this->assertStringContainsString('Medical', $interpretasi);
    }

    // ========================================
    // DESKRIPSI KATEGORI TESTS
    // ========================================

    #[Test]
    public function get_deskripsi_kategori_returns_all_12_categories()
    {
        $deskripsi = $this->scoringService->getDeskripsiKategori();

        $this->assertCount(12, $deskripsi);

        $expectedCategories = [
            'Outdoor', 'Mechanical', 'Computational', 'Scientific',
            'Personal Contact', 'Aesthetic', 'Literary', 'Musical',
            'Social Service', 'Clerical', 'Practical', 'Medical'
        ];

        foreach ($expectedCategories as $category) {
            $this->assertArrayHasKey($category, $deskripsi);
            $this->assertArrayHasKey('singkatan', $deskripsi[$category]);
            $this->assertArrayHasKey('nama', $deskripsi[$category]);
            $this->assertArrayHasKey('deskripsi', $deskripsi[$category]);
        }
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
