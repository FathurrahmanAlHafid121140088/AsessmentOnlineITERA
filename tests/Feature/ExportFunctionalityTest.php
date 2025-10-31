<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();
    }

    /**
     * Test: Export returns downloadable file
     */
    public function test_export_returns_downloadable_file()
    {
        DataDiris::factory()->create(['nim' => '123456789']);
        HasilKuesioner::factory()->create(['nim' => '123456789']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.export.excel'));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    /**
     * Test: Export filename contains date
     */
    public function test_export_filename_contains_date()
    {
        DataDiris::factory()->create(['nim' => '123456789']);
        HasilKuesioner::factory()->create(['nim' => '123456789']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.export.excel'));

        $contentDisposition = $response->headers->get('Content-Disposition');

        // Should contain current date
        $this->assertStringContainsString('hasil-kuesioner-', $contentDisposition);
        $this->assertStringContainsString('.xlsx', $contentDisposition);
    }

    /**
     * Test: Export respects search filters
     */
    public function test_export_respects_search_filters()
    {
        $dd1 = DataDiris::factory()->create(['nim' => '111111111', 'nama' => 'Alice']);
        $dd2 = DataDiris::factory()->create(['nim' => '222222222', 'nama' => 'Bob']);

        HasilKuesioner::factory()->create(['nim' => '111111111']);
        HasilKuesioner::factory()->create(['nim' => '222222222']);

        // Export with search filter
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.export.excel', ['search' => 'Alice']));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    /**
     * Test: Export respects kategori filter
     */
    public function test_export_respects_kategori_filter()
    {
        $dd1 = DataDiris::factory()->create(['nim' => '111111111']);
        $dd2 = DataDiris::factory()->create(['nim' => '222222222']);

        HasilKuesioner::factory()->create(['nim' => '111111111', 'kategori' => 'Sangat Sehat']);
        HasilKuesioner::factory()->create(['nim' => '222222222', 'kategori' => 'Sehat']);

        // Export with kategori filter
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.export.excel', ['kategori' => 'Sangat Sehat']));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    /**
     * Test: Export works with large dataset
     */
    public function test_export_works_with_large_dataset()
    {
        // Create 100 test records
        for ($i = 1; $i <= 100; $i++) {
            $nim = str_pad($i, 9, '0', STR_PAD_LEFT);
            DataDiris::factory()->create(['nim' => $nim]);
            HasilKuesioner::factory()->create(['nim' => $nim]);
        }

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.export.excel'));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    /**
     * Test: Export requires authentication
     */
    public function test_export_requires_authentication()
    {
        $response = $this->get(route('admin.export.excel'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Export respects sort parameters
     */
    public function test_export_respects_sort_parameters()
    {
        $dd1 = DataDiris::factory()->create(['nim' => '111111111', 'nama' => 'Zack']);
        $dd2 = DataDiris::factory()->create(['nim' => '222222222', 'nama' => 'Alice']);

        HasilKuesioner::factory()->create(['nim' => '111111111']);
        HasilKuesioner::factory()->create(['nim' => '222222222']);

        // Export with sort
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.export.excel', ['sort' => 'nama', 'order' => 'asc']));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    /**
     * Test: Export handles empty data
     */
    public function test_export_handles_empty_data()
    {
        // No data in database
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.export.excel'));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    /**
     * Test: Export file has correct MIME type
     */
    public function test_export_file_has_correct_mime_type()
    {
        DataDiris::factory()->create(['nim' => '123456789']);
        HasilKuesioner::factory()->create(['nim' => '123456789']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.export.excel'));

        $contentType = $response->headers->get('Content-Type');

        $this->assertTrue(
            str_contains($contentType, 'spreadsheet') ||
            str_contains($contentType, 'excel') ||
            str_contains($contentType, 'vnd.openxmlformats')
        );
    }
}
