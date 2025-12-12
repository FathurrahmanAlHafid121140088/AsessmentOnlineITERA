<?php

namespace Tests\Unit\Rmib;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Services\RmibScoringService;

/**
 * Unit Test untuk RmibScoringService
 * 
 * Test MURNI - hanya test method yang tidak memerlukan database
 * Method hitungSemuaSkor dan generateMatrix memerlukan database,
 * jadi akan di-test di Integration Test
 */
class RmibScoringServiceTest extends TestCase
{
    protected ?RmibScoringService $service = null;

    protected function setUp(): void
    {
        parent::setUp();
        
        if (!class_exists(\App\Services\RmibScoringService::class)) {
            $this->markTestSkipped('RmibScoringService class does not exist');
        }
        
        $this->service = new RmibScoringService();
    }

    // =====================================================
    // TEST: getDeskripsiKategori() - Ini bisa di-test tanpa DB
    // =====================================================

    #[Test]
    public function get_deskripsi_kategori_returns_array(): void
    {
        $result = $this->service->getDeskripsiKategori();
        
        $this->assertIsArray($result);
    }

    #[Test]
    public function get_deskripsi_kategori_returns_12_categories(): void
    {
        $result = $this->service->getDeskripsiKategori();
        
        $this->assertCount(12, $result);
    }

    #[Test]
    public function get_deskripsi_kategori_contains_expected_keys(): void
    {
        $result = $this->service->getDeskripsiKategori();
        
        // Sesuai dengan implementasi sebenarnya - menggunakan nama lengkap
        $expectedKeys = [
            'Outdoor',
            'Mechanical', 
            'Computational',
            'Scientific',
            'Personal Contact',
            'Aesthetic',
            'Literary',
            'Musical',
            'Social Service',
            'Clerical',
            'Practical',
            'Medical',
        ];
        
        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $result, "Key '{$key}' should exist in getDeskripsiKategori");
        }
    }

    #[Test]
    public function get_deskripsi_kategori_each_category_has_required_fields(): void
    {
        $result = $this->service->getDeskripsiKategori();
        
        foreach ($result as $kategori => $data) {
            $this->assertIsArray($data, "Data for '{$kategori}' should be array");
            $this->assertArrayHasKey('singkatan', $data, "'{$kategori}' should have 'singkatan' key");
            $this->assertArrayHasKey('nama', $data, "'{$kategori}' should have 'nama' key");
            $this->assertArrayHasKey('deskripsi', $data, "'{$kategori}' should have 'deskripsi' key");
        }
    }

    #[Test]
    public function get_deskripsi_kategori_singkatan_values_are_non_empty(): void
    {
        $result = $this->service->getDeskripsiKategori();
        
        foreach ($result as $kategori => $data) {
            $this->assertNotEmpty($data['singkatan'], "Singkatan for '{$kategori}' should not be empty");
            $this->assertIsString($data['singkatan'], "Singkatan for '{$kategori}' should be string");
        }
    }

    #[Test]
    public function get_deskripsi_kategori_nama_values_are_non_empty(): void
    {
        $result = $this->service->getDeskripsiKategori();
        
        foreach ($result as $kategori => $data) {
            $this->assertNotEmpty($data['nama'], "Nama for '{$kategori}' should not be empty");
            $this->assertIsString($data['nama'], "Nama for '{$kategori}' should be string");
        }
    }

    #[Test]
    public function get_deskripsi_kategori_deskripsi_values_are_non_empty(): void
    {
        $result = $this->service->getDeskripsiKategori();
        
        foreach ($result as $kategori => $data) {
            $this->assertNotEmpty($data['deskripsi'], "Deskripsi for '{$kategori}' should not be empty");
            $this->assertIsString($data['deskripsi'], "Deskripsi for '{$kategori}' should be string");
        }
    }

    // =====================================================
    // TEST: generateInterpretasi() - Ini bisa di-test tanpa DB
    // =====================================================

    #[Test]
    public function generate_interpretasi_returns_string(): void
    {
        $top3 = [
            'Outdoor' => 15,
            'Scientific' => 20,
            'Medical' => 25,
        ];
        
        $result = $this->service->generateInterpretasi($top3);
        
        $this->assertIsString($result);
    }

    #[Test]
    public function generate_interpretasi_contains_category_names(): void
    {
        $top3 = [
            'Outdoor' => 15,
            'Scientific' => 20,
            'Medical' => 25,
        ];
        
        $result = $this->service->generateInterpretasi($top3);
        
        $this->assertStringContainsString('Outdoor', $result);
        $this->assertStringContainsString('Scientific', $result);
        $this->assertStringContainsString('Medical', $result);
    }

    #[Test]
    public function generate_interpretasi_contains_scores(): void
    {
        $top3 = [
            'Outdoor' => 15,
            'Scientific' => 20,
            'Medical' => 25,
        ];
        
        $result = $this->service->generateInterpretasi($top3);
        
        $this->assertStringContainsString('15', $result);
        $this->assertStringContainsString('20', $result);
        $this->assertStringContainsString('25', $result);
    }

    #[Test]
    public function generate_interpretasi_contains_ranking_numbers(): void
    {
        $top3 = [
            'Outdoor' => 15,
            'Scientific' => 20,
            'Medical' => 25,
        ];
        
        $result = $this->service->generateInterpretasi($top3);
        
        $this->assertStringContainsString('1.', $result);
        $this->assertStringContainsString('2.', $result);
        $this->assertStringContainsString('3.', $result);
    }

    // =====================================================
    // TEST: Service class structure
    // =====================================================

    #[Test]
    public function service_has_hitung_semua_skor_method(): void
    {
        $this->assertTrue(
            method_exists($this->service, 'hitungSemuaSkor'),
            'RmibScoringService should have hitungSemuaSkor method'
        );
    }

    #[Test]
    public function service_has_generate_matrix_method(): void
    {
        $this->assertTrue(
            method_exists($this->service, 'generateMatrix'),
            'RmibScoringService should have generateMatrix method'
        );
    }

    #[Test]
    public function service_has_generate_interpretasi_method(): void
    {
        $this->assertTrue(
            method_exists($this->service, 'generateInterpretasi'),
            'RmibScoringService should have generateInterpretasi method'
        );
    }

    #[Test]
    public function service_has_get_deskripsi_kategori_method(): void
    {
        $this->assertTrue(
            method_exists($this->service, 'getDeskripsiKategori'),
            'RmibScoringService should have getDeskripsiKategori method'
        );
    }
}
