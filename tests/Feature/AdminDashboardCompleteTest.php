<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use App\Models\Users;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class AdminDashboardCompleteTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = Admin::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123')
        ]);
    }

    /**
     * Test: Admin can access dashboard
     */
    public function test_admin_can_access_dashboard()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        $response->assertStatus(200);
        $response->assertViewIs('admin-home');
    }

    /**
     * Test: Guest cannot access admin dashboard
     */
    public function test_guest_cannot_access_admin_dashboard()
    {
        $response = $this->get(route('admin.home'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Dashboard shows correct statistics
     */
    public function test_dashboard_shows_correct_statistics()
    {
        // Create test data
        $dataDiri1 = DataDiris::factory()->create(['nim' => '111111111', 'jenis_kelamin' => 'L', 'asal_sekolah' => 'SMA']);
        $dataDiri2 = DataDiris::factory()->create(['nim' => '222222222', 'jenis_kelamin' => 'P', 'asal_sekolah' => 'SMK']);

        HasilKuesioner::factory()->create(['nim' => '111111111', 'kategori' => 'Sangat Sehat']);
        HasilKuesioner::factory()->create(['nim' => '222222222', 'kategori' => 'Sehat']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        $response->assertStatus(200);
        $response->assertViewHas('totalUsers', 2);
        $response->assertViewHas('totalTes', 2);
        $response->assertViewHas('totalLaki', 1);
        $response->assertViewHas('totalPerempuan', 1);
    }

    /**
     * Test: Pagination works correctly
     */
    public function test_pagination_works_correctly()
    {
        // Create 25 test results
        for ($i = 1; $i <= 25; $i++) {
            $nim = str_pad($i, 9, '0', STR_PAD_LEFT);
            DataDiris::factory()->create(['nim' => $nim]);
            HasilKuesioner::factory()->create(['nim' => $nim]);
        }

        // Test page 1 with 10 per page
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home', ['limit' => 10]));

        $response->assertStatus(200);
        $this->assertCount(10, $response->viewData('hasilKuesioners'));

        // Test page 2
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home', ['limit' => 10, 'page' => 2]));

        $response->assertStatus(200);
        $this->assertCount(10, $response->viewData('hasilKuesioners'));
    }

    /**
     * Test: Search functionality
     */
    public function test_search_functionality()
    {
        $dataDiri = DataDiris::factory()->create([
            'nim' => '123456789',
            'nama' => 'John Doe'
        ]);

        HasilKuesioner::factory()->create(['nim' => '123456789']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home', ['search' => 'John']));

        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('hasilKuesioners'));
    }

    /**
     * Test: Filter by kategori
     */
    public function test_filter_by_kategori()
    {
        $dd1 = DataDiris::factory()->create(['nim' => '111111111']);
        $dd2 = DataDiris::factory()->create(['nim' => '222222222']);

        HasilKuesioner::factory()->create(['nim' => '111111111', 'kategori' => 'Sangat Sehat']);
        HasilKuesioner::factory()->create(['nim' => '222222222', 'kategori' => 'Sehat']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home', ['kategori' => 'Sangat Sehat']));

        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('hasilKuesioners'));
    }

    /**
     * Test: Sort functionality
     */
    public function test_sort_functionality()
    {
        $dd1 = DataDiris::factory()->create(['nim' => '111111111', 'nama' => 'Alice']);
        $dd2 = DataDiris::factory()->create(['nim' => '222222222', 'nama' => 'Bob']);

        HasilKuesioner::factory()->create(['nim' => '111111111']);
        HasilKuesioner::factory()->create(['nim' => '222222222']);

        // Sort by nama ascending
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home', ['sort' => 'nama', 'order' => 'asc']));

        $response->assertStatus(200);
        $results = $response->viewData('hasilKuesioners');
        $this->assertEquals('Alice', $results->first()->nama_mahasiswa);
    }

    /**
     * Test: Delete functionality
     */
    public function test_delete_functionality()
    {
        $dataDiri = DataDiris::factory()->create(['nim' => '123456789']);
        $user = Users::factory()->create(['nim' => '123456789']);
        $hasil = HasilKuesioner::factory()->create(['nim' => '123456789']);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.delete', $hasil->id));

        $response->assertRedirect(route('admin.home'));
        $response->assertSessionHas('success');

        // Verify all related data is deleted
        $this->assertDatabaseMissing('hasil_kuesioners', ['nim' => '123456789']);
        $this->assertDatabaseMissing('data_diris', ['nim' => '123456789']);
        $this->assertDatabaseMissing('users', ['nim' => '123456789']);
    }

    /**
     * Test: Delete invalidates cache
     */
    public function test_delete_invalidates_cache()
    {
        // Set up cache
        Cache::put('mh.admin.user_stats', ['test' => 'data'], 60);
        Cache::put('mh.admin.kategori_counts', ['test' => 'data'], 60);

        $this->assertTrue(Cache::has('mh.admin.user_stats'));
        $this->assertTrue(Cache::has('mh.admin.kategori_counts'));

        // Create and delete
        $dataDiri = DataDiris::factory()->create(['nim' => '123456789']);
        $hasil = HasilKuesioner::factory()->create(['nim' => '123456789']);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.delete', $hasil->id));

        // Verify cache is cleared
        $this->assertFalse(Cache::has('mh.admin.user_stats'));
        $this->assertFalse(Cache::has('mh.admin.kategori_counts'));
    }

    /**
     * Test: Export to Excel
     */
    public function test_export_to_excel()
    {
        DataDiris::factory()->create(['nim' => '123456789']);
        HasilKuesioner::factory()->create(['nim' => '123456789']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.export.excel'));

        $response->assertStatus(200);
        $response->assertDownload();
        $this->assertTrue(
            str_contains($response->headers->get('content-type'), 'spreadsheet') ||
            str_contains($response->headers->get('content-type'), 'excel')
        );
    }

    /**
     * Test: Kategori counts displayed correctly
     */
    public function test_kategori_counts_displayed_correctly()
    {
        $dd1 = DataDiris::factory()->create(['nim' => '111111111']);
        $dd2 = DataDiris::factory()->create(['nim' => '222222222']);
        $dd3 = DataDiris::factory()->create(['nim' => '333333333']);

        HasilKuesioner::factory()->create(['nim' => '111111111', 'kategori' => 'Sangat Sehat']);
        HasilKuesioner::factory()->create(['nim' => '222222222', 'kategori' => 'Sangat Sehat']);
        HasilKuesioner::factory()->create(['nim' => '333333333', 'kategori' => 'Sehat']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        $kategoriCounts = $response->viewData('kategoriCounts');

        $this->assertEquals(2, $kategoriCounts['Sangat Sehat'] ?? 0);
        $this->assertEquals(1, $kategoriCounts['Sehat'] ?? 0);
    }

    /**
     * Test: Fakultas statistics displayed correctly
     */
    public function test_fakultas_statistics_displayed_correctly()
    {
        $dd1 = DataDiris::factory()->create(['nim' => '111111111', 'fakultas' => 'FTIK']);
        $dd2 = DataDiris::factory()->create(['nim' => '222222222', 'fakultas' => 'FTIK']);
        $dd3 = DataDiris::factory()->create(['nim' => '333333333', 'fakultas' => 'FTI']);

        HasilKuesioner::factory()->create(['nim' => '111111111']);
        HasilKuesioner::factory()->create(['nim' => '222222222']);
        HasilKuesioner::factory()->create(['nim' => '333333333']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        $fakultasCount = $response->viewData('fakultasCount');

        $this->assertEquals(2, $fakultasCount['FTIK'] ?? 0);
        $this->assertEquals(1, $fakultasCount['FTI'] ?? 0);
    }

    /**
     * Test: Jumlah tes per mahasiswa calculated correctly
     */
    public function test_jumlah_tes_per_mahasiswa_calculated_correctly()
    {
        $dataDiri = DataDiris::factory()->create(['nim' => '123456789']);

        // Create 3 tests for same student
        HasilKuesioner::factory()->count(3)->create(['nim' => '123456789']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        $results = $response->viewData('hasilKuesioners');
        $this->assertEquals(3, $results->first()->jumlah_tes);
    }

    /**
     * Test: Only latest test per student shown by default
     */
    public function test_only_latest_test_per_student_shown()
    {
        $dd1 = DataDiris::factory()->create(['nim' => '111111111']);
        $dd2 = DataDiris::factory()->create(['nim' => '222222222']);

        // Create multiple tests for first student
        HasilKuesioner::factory()->create(['nim' => '111111111', 'created_at' => now()->subDays(2)]);
        HasilKuesioner::factory()->create(['nim' => '111111111', 'created_at' => now()]);

        // One test for second student
        HasilKuesioner::factory()->create(['nim' => '222222222']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        $results = $response->viewData('hasilKuesioners');

        // Should show 2 results (latest for each student)
        $this->assertCount(2, $results);
    }

    /**
     * Test: Asal sekolah statistics calculated correctly
     */
    public function test_asal_sekolah_statistics_calculated_correctly()
    {
        $dd1 = DataDiris::factory()->create(['nim' => '111111111', 'asal_sekolah' => 'SMA']);
        $dd2 = DataDiris::factory()->create(['nim' => '222222222', 'asal_sekolah' => 'SMK']);
        $dd3 = DataDiris::factory()->create(['nim' => '333333333', 'asal_sekolah' => 'SMA']);

        HasilKuesioner::factory()->create(['nim' => '111111111']);
        HasilKuesioner::factory()->create(['nim' => '222222222']);
        HasilKuesioner::factory()->create(['nim' => '333333333']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        $asalCounts = $response->viewData('asalCounts');

        $this->assertEquals(2, $asalCounts['SMA']);
        $this->assertEquals(1, $asalCounts['SMK']);
    }

    /**
     * Test: Cache is used for statistics
     */
    public function test_cache_is_used_for_statistics()
    {
        $dd = DataDiris::factory()->create(['nim' => '123456789']);
        HasilKuesioner::factory()->create(['nim' => '123456789']);

        // First request - should create cache
        $response1 = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        $this->assertTrue(Cache::has('mh.admin.user_stats'));
        $this->assertTrue(Cache::has('mh.admin.kategori_counts'));
        $this->assertTrue(Cache::has('mh.admin.total_tes'));

        // Second request - should use cache
        $response2 = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        $response2->assertStatus(200);
    }
}
