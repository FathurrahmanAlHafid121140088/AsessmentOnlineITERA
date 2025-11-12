<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\KarirDataDiri;
use App\Models\RmibHasilTes;
use App\Models\RmibJawabanPeserta;
use App\Models\RmibPekerjaan;
use App\Services\RmibScoringService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;

class RmibScoringTest extends TestCase
{
    use RefreshDatabase;

    protected $scoringService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scoringService = new RmibScoringService();

        // Seed data pekerjaan
        $this->seed(\Database\Seeders\RmibPekerjaanSeeder::class);
    }

    /**
     * Test verifikasi urutan pekerjaan dari database sesuai circular shift
     */
    public function test_pekerjaan_ordering_maintains_circular_shift()
    {
        // Test untuk pria - cluster A harus dimulai dari Outdoor
        $pekerjaanClusterA = RmibPekerjaan::where('gender', 'L')
            ->where('kelompok', 'A')
            ->orderBy('id')
            ->get();

        $this->assertEquals(12, $pekerjaanClusterA->count());

        // Cluster A pria: harus dimulai dari Outdoor (Petani)
        $this->assertEquals('Petani', $pekerjaanClusterA->first()->nama_pekerjaan);
        $this->assertEquals('Outdoor', $pekerjaanClusterA->first()->kategori);

        // Test untuk pria - cluster B harus dimulai dari Mechanical
        $pekerjaanClusterB = RmibPekerjaan::where('gender', 'L')
            ->where('kelompok', 'B')
            ->orderBy('id')
            ->get();

        // Cluster B pria: harus dimulai dari Mechanical (Ahli Pembuat Alat)
        $this->assertEquals('Ahli Pembuat Alat', $pekerjaanClusterB->first()->nama_pekerjaan);
        $this->assertEquals('Mechanical', $pekerjaanClusterB->first()->kategori);

        // Cluster B pria: harus diakhiri dengan Outdoor (Ahli Kehutanan) - wrap around
        $this->assertEquals('Ahli Kehutanan', $pekerjaanClusterB->last()->nama_pekerjaan);
        $this->assertEquals('Outdoor', $pekerjaanClusterB->last()->kategori);
    }

    /**
     * Test matrix generation dengan sample data
     */
    public function test_matrix_generation_with_sample_data()
    {
        // Buat data diri peserta
        $dataDiri = KarirDataDiri::create([
            'nim' => '123456789',
            'nama' => 'Test User',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test Address',
            'usia' => 20,
            'fakultas' => 'Teknik',
            'email' => 'test@student.itera.ac.id',
            'asal_sekolah' => 'SMA Test',
            'status_tinggal' => 'Kos',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        // Buat hasil tes
        $hasilTes = RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Akuntan',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        // Simulasi jawaban user untuk cluster A (12 pekerjaan)
        // User memberi ranking 1-12 sesuai urutan yang ditampilkan di form
        $clusterAPekerjaan = RmibPekerjaan::where('gender', 'L')
            ->where('kelompok', 'A')
            ->orderBy('id')
            ->get();

        foreach ($clusterAPekerjaan as $index => $pekerjaan) {
            RmibJawabanPeserta::create([
                'hasil_id' => $hasilTes->id,
                'kelompok' => 'A',
                'pekerjaan' => $pekerjaan->nama_pekerjaan,
                'peringkat' => $index + 1, // Ranking 1-12 sesuai urutan
            ]);
        }

        // Simulasi jawaban user untuk cluster B
        $clusterBPekerjaan = RmibPekerjaan::where('gender', 'L')
            ->where('kelompok', 'B')
            ->orderBy('id')
            ->get();

        foreach ($clusterBPekerjaan as $index => $pekerjaan) {
            RmibJawabanPeserta::create([
                'hasil_id' => $hasilTes->id,
                'kelompok' => 'B',
                'pekerjaan' => $pekerjaan->nama_pekerjaan,
                'peringkat' => 12 - $index, // Ranking terbalik untuk variasi
            ]);
        }

        // Generate matrix
        $matrixData = $this->scoringService->generateMatrix($hasilTes->id, 'L');

        // Verifikasi struktur matrix
        $this->assertArrayHasKey('matrix', $matrixData);
        $this->assertArrayHasKey('sum', $matrixData);
        $this->assertArrayHasKey('rank', $matrixData);
        $this->assertArrayHasKey('percentage', $matrixData);

        // Verifikasi matrix 12x9
        $this->assertCount(12, $matrixData['matrix']); // 12 kategori

        foreach ($matrixData['matrix'] as $kategori => $klusterData) {
            // Setiap kategori harus punya data untuk 9 kluster (tapi kita hanya isi 2)
            $this->assertIsArray($klusterData);
        }

        // Verifikasi sum untuk kategori
        $this->assertArrayHasKey('Outdoor', $matrixData['sum']);
        $this->assertArrayHasKey('Mechanical', $matrixData['sum']);
        $this->assertArrayHasKey('Scientific', $matrixData['sum']);

        // Verifikasi rank
        $this->assertCount(12, $matrixData['rank']);

        // Output untuk debugging
        echo "\n=== MATRIX TEST RESULTS ===\n";
        echo "Matrix Structure:\n";
        foreach ($matrixData['matrix'] as $kategori => $data) {
            echo "  $kategori: A={$data['A']}, B={$data['B']}\n";
        }
        echo "\nSUM Scores:\n";
        foreach ($matrixData['sum'] as $kategori => $sum) {
            echo "  $kategori: $sum\n";
        }
        echo "\nRANK:\n";
        foreach ($matrixData['rank'] as $kategori => $rank) {
            echo "  $kategori: $rank\n";
        }
    }

    /**
     * Test scoring service dengan full 9 cluster data
     */
    public function test_full_scoring_with_all_clusters()
    {
        // Buat data diri peserta
        $dataDiri = KarirDataDiri::create([
            'nim' => '987654321',
            'nama' => 'Full Test User',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test Address',
            'usia' => 21,
            'fakultas' => 'Teknik',
            'email' => 'fulltest@student.itera.ac.id',
            'asal_sekolah' => 'SMA Test',
            'status_tinggal' => 'Kos',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        // Buat hasil tes
        $hasilTes = RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri->id,
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan' => 'Ilmuwan',
            'top_2_pekerjaan' => 'Ahli Statistik',
            'top_3_pekerjaan' => 'Wartawan',
        ]);

        // Isi jawaban untuk semua 9 cluster
        $clusters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        foreach ($clusters as $cluster) {
            $pekerjaanList = RmibPekerjaan::where('gender', 'L')
                ->where('kelompok', $cluster)
                ->orderBy('id')
                ->get();

            foreach ($pekerjaanList as $index => $pekerjaan) {
                RmibJawabanPeserta::create([
                    'hasil_id' => $hasilTes->id,
                    'kelompok' => $cluster,
                    'pekerjaan' => $pekerjaan->nama_pekerjaan,
                    'peringkat' => ($index % 12) + 1, // Variasi ranking
                ]);
            }
        }

        // Test hitungSemuaSkor
        $skorData = $this->scoringService->hitungSemuaSkor($hasilTes->id, 'L');

        // Verifikasi struktur hasil
        $this->assertArrayHasKey('skor_kategori', $skorData);
        $this->assertArrayHasKey('peringkat', $skorData);
        $this->assertArrayHasKey('skor_konsistensi', $skorData);

        // Verifikasi semua kategori ada
        $this->assertCount(12, $skorData['skor_kategori']);
        $this->assertCount(12, $skorData['peringkat']);

        // Verifikasi skor konsistensi dalam range 0-10
        $this->assertGreaterThanOrEqual(0, $skorData['skor_konsistensi']);
        $this->assertLessThanOrEqual(10, $skorData['skor_konsistensi']);

        echo "\n=== FULL SCORING TEST RESULTS ===\n";
        echo "Consistency Score: {$skorData['skor_konsistensi']}/10\n";
        echo "\nCategory Scores:\n";
        foreach ($skorData['skor_kategori'] as $kategori => $skor) {
            $rank = $skorData['peringkat'][$kategori];
            echo "  $kategori: Score=$skor, Rank=$rank\n";
        }
    }

    /**
     * Test verifikasi circular shift pattern di matrix
     */
    public function test_circular_shift_pattern_in_matrix()
    {
        // Buat test data
        $dataDiri = KarirDataDiri::create([
            'nim' => '111222333',
            'nama' => 'Circular Test',
            'program_studi' => 'Teknik Informatika',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Test Address',
            'usia' => 20,
            'fakultas' => 'Teknik',
            'email' => 'circular@student.itera.ac.id',
            'asal_sekolah' => 'SMA Test',
            'status_tinggal' => 'Kos',
            'prodi_sesuai_keinginan' => 'Ya',
        ]);

        $hasilTes = RmibHasilTes::create([
            'karir_data_diri_id' => $dataDiri->id,
            'tanggal_pengerjaan' => Carbon::now(),
        ]);

        // Isi cluster A dengan ranking linear 1-12
        $clusterAPekerjaan = RmibPekerjaan::where('gender', 'L')
            ->where('kelompok', 'A')
            ->orderBy('id')
            ->get();

        foreach ($clusterAPekerjaan as $index => $pekerjaan) {
            RmibJawabanPeserta::create([
                'hasil_id' => $hasilTes->id,
                'kelompok' => 'A',
                'pekerjaan' => $pekerjaan->nama_pekerjaan,
                'peringkat' => $index + 1,
            ]);
        }

        // Generate matrix
        $matrixData = $this->scoringService->generateMatrix($hasilTes->id, 'L');

        // Verifikasi cluster A dengan circular shift dimulai dari index 0 (Outdoor)
        // Petani (Outdoor) dapat rank 1 -> masuk ke matrix[Outdoor][A] = 1
        // Insinyur Sipil (Mechanical) dapat rank 2 -> masuk ke matrix[Mechanical][A] = 2
        // dst...

        $expectedMapping = [
            'Outdoor' => 1,      // Petani
            'Mechanical' => 2,   // Insinyur Sipil
            'Computational' => 3, // Akuntan
            'Scientific' => 4,   // Ilmuwan
            'Personal Contact' => 5, // Manager Penjualan
            'Aesthetic' => 6,    // Seniman
            'Literary' => 7,     // Wartawan
            'Musical' => 8,      // Pianis Konser
            'Social Service' => 9, // Guru SD
            'Clerical' => 10,    // Manager Bank
            'Practical' => 11,   // Tukang Kayu
            'Medical' => 12,     // Dokter
        ];

        foreach ($expectedMapping as $kategori => $expectedRank) {
            $this->assertEquals(
                $expectedRank,
                $matrixData['matrix'][$kategori]['A'],
                "Cluster A: Kategori $kategori should have rank $expectedRank in matrix"
            );
        }

        echo "\n=== CIRCULAR SHIFT VERIFICATION ===\n";
        echo "Cluster A Matrix Values (should match expected ranking 1-12):\n";
        foreach ($expectedMapping as $kategori => $expectedRank) {
            $actualRank = $matrixData['matrix'][$kategori]['A'];
            $status = $actualRank === $expectedRank ? '✓' : '✗';
            echo "  $status $kategori: Expected=$expectedRank, Actual=$actualRank\n";
        }
    }
}
