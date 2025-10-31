<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use App\Models\Users;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class CachePerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected Users $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();
        $this->user = Users::factory()->create(['nim' => '123456789']);
    }

    /**
     * Test: Admin dashboard statistics are cached
     */
    public function test_admin_dashboard_statistics_are_cached()
    {
        DataDiris::factory()->create(['nim' => '123456789']);
        HasilKuesioner::factory()->create(['nim' => '123456789']);

        // Clear cache first
        Cache::flush();

        // First request - should create cache
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        // Verify caches exist
        $this->assertTrue(Cache::has('mh.admin.user_stats'));
        $this->assertTrue(Cache::has('mh.admin.kategori_counts'));
        $this->assertTrue(Cache::has('mh.admin.total_tes'));
        $this->assertTrue(Cache::has('mh.admin.fakultas_stats'));
    }

    /**
     * Test: Cache persists across multiple requests
     */
    public function test_cache_persists_across_multiple_requests()
    {
        DataDiris::factory()->create(['nim' => '123456789']);
        HasilKuesioner::factory()->create(['nim' => '123456789']);

        Cache::flush();

        // First request
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        $cachedData = Cache::get('mh.admin.user_stats');

        // Second request
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        // Cache should be same
        $this->assertEquals($cachedData, Cache::get('mh.admin.user_stats'));
    }

    /**
     * Test: Submitting kuesioner invalidates admin cache
     */
    public function test_submitting_kuesioner_invalidates_admin_cache()
    {
        DataDiris::factory()->create(['nim' => '123456789']);

        // Create cache
        Cache::put('mh.admin.user_stats', ['test' => 'data'], 60);
        Cache::put('mh.admin.kategori_counts', ['test' => 'data'], 60);
        Cache::put('mh.admin.total_tes', 100, 60);

        $this->assertTrue(Cache::has('mh.admin.user_stats'));

        // Submit kuesioner
        $data = ['nim' => '123456789'];
        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = 5;
        }

        $this->actingAs($this->user)
            ->post(route('mental-health.kuesioner.submit'), $data);

        // Cache should be cleared
        $this->assertFalse(Cache::has('mh.admin.user_stats'));
        $this->assertFalse(Cache::has('mh.admin.kategori_counts'));
        $this->assertFalse(Cache::has('mh.admin.total_tes'));
    }

    /**
     * Test: Submitting data diri invalidates specific caches
     */
    public function test_submitting_data_diri_invalidates_specific_caches()
    {
        Cache::put('mh.admin.user_stats', ['test' => 'data'], 60);
        Cache::put('mh.admin.fakultas_stats', ['test' => 'data'], 60);
        Cache::put('mh.admin.kategori_counts', ['test' => 'data'], 60); // Should NOT be cleared

        $this->actingAs($this->user)
            ->post(route('mental-health.store-data-diri'), [
                'nama' => 'Test User',
                'jenis_kelamin' => 'L',
                'provinsi' => 'Lampung',
                'alamat' => 'Test Address',
                'usia' => 20,
                'fakultas' => 'FTIK',
                'program_studi' => 'Teknik Informatika',
                'asal_sekolah' => 'SMA',
                'status_tinggal' => 'Kost',
                'email' => 'test@example.com',
                'keluhan' => 'Stress',
                'lama_keluhan' => '1-3 bulan',
                'pernah_konsul' => 'Ya',
                'pernah_tes' => 'Tidak'
            ]);

        // These should be cleared
        $this->assertFalse(Cache::has('mh.admin.user_stats'));
        $this->assertFalse(Cache::has('mh.admin.fakultas_stats'));

        // This should still exist (not affected by data diri)
        $this->assertTrue(Cache::has('mh.admin.kategori_counts'));
    }

    /**
     * Test: User dashboard cache is per-user
     */
    public function test_user_dashboard_cache_is_per_user()
    {
        $user1 = Users::factory()->create(['nim' => '111111111']);
        $user2 = Users::factory()->create(['nim' => '222222222']);

        DataDiris::factory()->create(['nim' => '111111111']);
        DataDiris::factory()->create(['nim' => '222222222']);

        HasilKuesioner::factory()->create(['nim' => '111111111', 'total_skor' => 100]);
        HasilKuesioner::factory()->create(['nim' => '222222222', 'total_skor' => 200]);

        Cache::flush();

        // User 1 visits dashboard
        $this->actingAs($user1)
            ->get(route('user.mental-health'));

        $cache1 = Cache::get("mh.user.111111111.test_history");

        // User 2 visits dashboard
        $this->actingAs($user2)
            ->get(route('user.mental-health'));

        $cache2 = Cache::get("mh.user.222222222.test_history");

        // Both should have their own cache
        $this->assertTrue(Cache::has("mh.user.111111111.test_history"));
        $this->assertTrue(Cache::has("mh.user.222222222.test_history"));
    }

    /**
     * Test: Cache TTL is respected
     */
    public function test_cache_ttl_is_respected()
    {
        // Put cache with 1 second TTL
        Cache::put('test.cache', 'data', 1);

        $this->assertTrue(Cache::has('test.cache'));

        // Wait 2 seconds
        sleep(2);

        // Cache should be expired
        $this->assertFalse(Cache::has('test.cache'));
    }

    /**
     * Test: Deleting user invalidates all caches
     */
    public function test_deleting_user_invalidates_all_caches()
    {
        $dataDiri = DataDiris::factory()->create(['nim' => '123456789']);
        $hasil = HasilKuesioner::factory()->create(['nim' => '123456789']);

        // Create all caches
        Cache::put('mh.admin.user_stats', ['test' => 'data'], 60);
        Cache::put('mh.admin.kategori_counts', ['test' => 'data'], 60);
        Cache::put('mh.admin.total_tes', 100, 60);
        Cache::put('mh.admin.fakultas_stats', ['test' => 'data'], 60);

        // Delete user
        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.delete', $hasil->id));

        // All caches should be cleared
        $this->assertFalse(Cache::has('mh.admin.user_stats'));
        $this->assertFalse(Cache::has('mh.admin.kategori_counts'));
        $this->assertFalse(Cache::has('mh.admin.total_tes'));
        $this->assertFalse(Cache::has('mh.admin.fakultas_stats'));
    }

    /**
     * Test: Multiple users submitting doesn't conflict caches
     */
    public function test_multiple_users_submitting_doesnt_conflict_caches()
    {
        $user1 = Users::factory()->create(['nim' => '111111111']);
        $user2 = Users::factory()->create(['nim' => '222222222']);

        DataDiris::factory()->create(['nim' => '111111111']);
        DataDiris::factory()->create(['nim' => '222222222']);

        // User 1 submits
        $data1 = ['nim' => '111111111'];
        for ($i = 1; $i <= 38; $i++) {
            $data1["question{$i}"] = 5;
        }

        $this->actingAs($user1)
            ->post(route('mental-health.kuesioner.submit'), $data1);

        // User 2 submits
        $data2 = ['nim' => '222222222'];
        for ($i = 1; $i <= 38; $i++) {
            $data2["question{$i}"] = 4;
        }

        $this->actingAs($user2)
            ->post(route('mental-health.kuesioner.submit'), $data2);

        // Both should have cleared their own user cache
        $this->assertFalse(Cache::has("mh.user.111111111.test_history"));
        $this->assertFalse(Cache::has("mh.user.222222222.test_history"));

        // But admin cache should also be cleared (once)
        $this->assertFalse(Cache::has('mh.admin.user_stats'));
    }

    /**
     * Test: Cache helps reduce database queries
     */
    public function test_cache_helps_reduce_database_queries()
    {
        DataDiris::factory()->create(['nim' => '123456789']);
        HasilKuesioner::factory()->create(['nim' => '123456789']);

        Cache::flush();

        // Enable query log
        \DB::enableQueryLog();

        // First request - no cache
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        $queriesWithoutCache = count(\DB::getQueryLog());

        // Clear query log
        \DB::flushQueryLog();

        // Second request - with cache
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.home'));

        $queriesWithCache = count(\DB::getQueryLog());

        // With cache should have fewer queries
        $this->assertLessThan($queriesWithoutCache, $queriesWithCache);
    }
}
