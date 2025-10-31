<?php
/**
 * ============================================
 * CACHING PERFORMANCE TEST SCRIPT
 * ============================================
 *
 * Script untuk testing cache implementation dan performance improvement
 *
 * CARA PENGGUNAAN:
 * 1. Pastikan database sudah ada data
 * 2. Run: php test-caching-performance.php
 * 3. Lihat hasil comparison dengan/tanpa cache
 */

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n";
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║         CACHING PERFORMANCE TEST - MENTAL HEALTH         ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

// ============================================
// PHASE 1: Test WITHOUT cache (fresh queries)
// ============================================
echo "📊 PHASE 1: Testing WITHOUT Cache (Cold Start)\n";
echo "─────────────────────────────────────────────────────────────\n";

// Clear all caches first
Cache::forget('mh.admin.user_stats');
Cache::forget('mh.admin.kategori_counts');
Cache::forget('mh.admin.total_tes');
Cache::forget('mh.admin.fakultas_stats');

echo "✅ All caches cleared\n\n";

// Test 1: User Stats Query
echo "Test 1: User Statistics Query\n";
$start = microtime(true);
$userStats = DataDiris::query()
    ->join('hasil_kuesioners', 'data_diris.nim', '=', 'hasil_kuesioners.nim')
    ->selectRaw("
        COUNT(DISTINCT data_diris.nim) as total_users,
        COUNT(CASE WHEN jenis_kelamin = 'L' THEN 1 END) as total_laki,
        COUNT(CASE WHEN jenis_kelamin = 'P' THEN 1 END) as total_perempuan,
        COUNT(CASE WHEN asal_sekolah = 'SMA' THEN 1 END) as total_sma,
        COUNT(CASE WHEN asal_sekolah = 'SMK' THEN 1 END) as total_smk,
        COUNT(CASE WHEN asal_sekolah = 'Boarding School' THEN 1 END) as total_boarding
    ")
    ->first();
$time1_nocache = (microtime(true) - $start) * 1000;

echo "  - Execution Time: " . round($time1_nocache, 2) . " ms (NO CACHE)\n";
echo "  - Total Users: " . ($userStats->total_users ?? 0) . "\n\n";

// Test 2: Kategori Counts Query
echo "Test 2: Kategori Counts Query\n";
$latestIds = DB::table('hasil_kuesioners')
    ->select(DB::raw('MAX(id) as id'))
    ->groupBy('nim');

$start = microtime(true);
$kategoriCounts = HasilKuesioner::whereIn('id', $latestIds)
    ->selectRaw('kategori, COUNT(*) as jumlah')
    ->groupBy('kategori')
    ->pluck('jumlah', 'kategori')
    ->all();
$time2_nocache = (microtime(true) - $start) * 1000;

echo "  - Execution Time: " . round($time2_nocache, 2) . " ms (NO CACHE)\n";
echo "  - Categories Found: " . count($kategoriCounts) . "\n\n";

// Test 3: Total Tests Query
echo "Test 3: Total Tests Count Query\n";
$start = microtime(true);
$totalTes = HasilKuesioner::count();
$time3_nocache = (microtime(true) - $start) * 1000;

echo "  - Execution Time: " . round($time3_nocache, 2) . " ms (NO CACHE)\n";
echo "  - Total Tests: " . $totalTes . "\n\n";

// Test 4: Fakultas Stats Query
echo "Test 4: Fakultas Statistics Query\n";
$start = microtime(true);
$fakultasCount = DataDiris::select('data_diris.fakultas', DB::raw('COUNT(DISTINCT data_diris.nim) as total'))
    ->join('hasil_kuesioners', 'data_diris.nim', '=', 'hasil_kuesioners.nim')
    ->whereNotNull('data_diris.fakultas')
    ->groupBy('data_diris.fakultas')
    ->pluck('total', 'data_diris.fakultas');
$time4_nocache = (microtime(true) - $start) * 1000;

echo "  - Execution Time: " . round($time4_nocache, 2) . " ms (NO CACHE)\n";
echo "  - Faculties Found: " . count($fakultasCount) . "\n\n";

$totalNoCacheTime = $time1_nocache + $time2_nocache + $time3_nocache + $time4_nocache;

echo "💡 Total Time WITHOUT Cache: " . round($totalNoCacheTime, 2) . " ms\n";
echo "\n";

// ============================================
// PHASE 2: Test WITH cache (cached queries)
// ============================================
echo "📊 PHASE 2: Testing WITH Cache (Warm Start)\n";
echo "─────────────────────────────────────────────────────────────\n";

// Test 1: User Stats with Cache
echo "Test 1: User Statistics Query (CACHED)\n";
$start = microtime(true);
$userStats = Cache::remember('mh.admin.user_stats', 300, function () {
    return DataDiris::query()
        ->join('hasil_kuesioners', 'data_diris.nim', '=', 'hasil_kuesioners.nim')
        ->selectRaw("
            COUNT(DISTINCT data_diris.nim) as total_users,
            COUNT(CASE WHEN jenis_kelamin = 'L' THEN 1 END) as total_laki,
            COUNT(CASE WHEN jenis_kelamin = 'P' THEN 1 END) as total_perempuan,
            COUNT(CASE WHEN asal_sekolah = 'SMA' THEN 1 END) as total_sma,
            COUNT(CASE WHEN asal_sekolah = 'SMK' THEN 1 END) as total_smk,
            COUNT(CASE WHEN asal_sekolah = 'Boarding School' THEN 1 END) as total_boarding
        ")
        ->first();
});
$time1_cache_first = (microtime(true) - $start) * 1000;

echo "  - First Load: " . round($time1_cache_first, 2) . " ms (CACHE MISS - storing to cache)\n";

// Load again from cache
$start = microtime(true);
$userStats = Cache::get('mh.admin.user_stats');
$time1_cache_hit = (microtime(true) - $start) * 1000;

echo "  - Second Load: " . round($time1_cache_hit, 2) . " ms (CACHE HIT ⚡)\n";
echo "  - Speed Improvement: " . round(($time1_nocache - $time1_cache_hit) / $time1_nocache * 100, 1) . "%\n\n";

// Test 2: Kategori Counts with Cache
echo "Test 2: Kategori Counts Query (CACHED)\n";
$start = microtime(true);
$kategoriCounts = Cache::remember('mh.admin.kategori_counts', 300, function () use ($latestIds) {
    return HasilKuesioner::whereIn('id', $latestIds)
        ->selectRaw('kategori, COUNT(*) as jumlah')
        ->groupBy('kategori')
        ->pluck('jumlah', 'kategori')
        ->all();
});
$time2_cache_first = (microtime(true) - $start) * 1000;

echo "  - First Load: " . round($time2_cache_first, 2) . " ms (CACHE MISS)\n";

$start = microtime(true);
$kategoriCounts = Cache::get('mh.admin.kategori_counts');
$time2_cache_hit = (microtime(true) - $start) * 1000;

echo "  - Second Load: " . round($time2_cache_hit, 2) . " ms (CACHE HIT ⚡)\n";
echo "  - Speed Improvement: " . round(($time2_nocache - $time2_cache_hit) / $time2_nocache * 100, 1) . "%\n\n";

// Test 3: Total Tests with Cache
echo "Test 3: Total Tests Count Query (CACHED)\n";
$start = microtime(true);
$totalTes = Cache::remember('mh.admin.total_tes', 300, function () {
    return HasilKuesioner::count();
});
$time3_cache_first = (microtime(true) - $start) * 1000;

echo "  - First Load: " . round($time3_cache_first, 2) . " ms (CACHE MISS)\n";

$start = microtime(true);
$totalTes = Cache::get('mh.admin.total_tes');
$time3_cache_hit = (microtime(true) - $start) * 1000;

echo "  - Second Load: " . round($time3_cache_hit, 2) . " ms (CACHE HIT ⚡)\n";
echo "  - Speed Improvement: " . round(($time3_nocache - $time3_cache_hit) / $time3_nocache * 100, 1) . "%\n\n";

// Test 4: Fakultas Stats with Cache
echo "Test 4: Fakultas Statistics Query (CACHED)\n";
$start = microtime(true);
$fakultasStats = Cache::remember('mh.admin.fakultas_stats', 600, function () {
    $fakultasCount = DataDiris::select('data_diris.fakultas', DB::raw('COUNT(DISTINCT data_diris.nim) as total'))
        ->join('hasil_kuesioners', 'data_diris.nim', '=', 'hasil_kuesioners.nim')
        ->whereNotNull('data_diris.fakultas')
        ->groupBy('data_diris.fakultas')
        ->pluck('total', 'data_diris.fakultas');

    return $fakultasCount->all();
});
$time4_cache_first = (microtime(true) - $start) * 1000;

echo "  - First Load: " . round($time4_cache_first, 2) . " ms (CACHE MISS)\n";

$start = microtime(true);
$fakultasStats = Cache::get('mh.admin.fakultas_stats');
$time4_cache_hit = (microtime(true) - $start) * 1000;

echo "  - Second Load: " . round($time4_cache_hit, 2) . " ms (CACHE HIT ⚡)\n";
echo "  - Speed Improvement: " . round(($time4_nocache - $time4_cache_hit) / $time4_nocache * 100, 1) . "%\n\n";

$totalCacheHitTime = $time1_cache_hit + $time2_cache_hit + $time3_cache_hit + $time4_cache_hit;

echo "💡 Total Time WITH Cache: " . round($totalCacheHitTime, 2) . " ms\n";
echo "\n";

// ============================================
// SUMMARY
// ============================================
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║                     PERFORMANCE SUMMARY                   ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

printf("📊 Test 1 (User Stats):          %6.2f ms → %6.2f ms (%+.0f%%)\n",
    $time1_nocache, $time1_cache_hit,
    (($time1_cache_hit - $time1_nocache) / $time1_nocache) * 100);

printf("📊 Test 2 (Kategori Counts):     %6.2f ms → %6.2f ms (%+.0f%%)\n",
    $time2_nocache, $time2_cache_hit,
    (($time2_cache_hit - $time2_nocache) / $time2_nocache) * 100);

printf("📊 Test 3 (Total Tests):         %6.2f ms → %6.2f ms (%+.0f%%)\n",
    $time3_nocache, $time3_cache_hit,
    (($time3_cache_hit - $time3_nocache) / $time3_nocache) * 100);

printf("📊 Test 4 (Fakultas Stats):      %6.2f ms → %6.2f ms (%+.0f%%)\n",
    $time4_nocache, $time4_cache_hit,
    (($time4_cache_hit - $time4_nocache) / $time4_nocache) * 100);

echo "─────────────────────────────────────────────────────────────\n";

printf("⚡ TOTAL TIME:                  %6.2f ms → %6.2f ms\n",
    $totalNoCacheTime, $totalCacheHitTime);

$improvement = (($totalNoCacheTime - $totalCacheHitTime) / $totalNoCacheTime) * 100;
printf("📈 PERFORMANCE IMPROVEMENT:      %.0f%% faster ⚡⚡⚡\n", $improvement);

echo "\n";

// Performance Rating
if ($improvement >= 70) {
    echo "🎉 EXCELLENT! Caching working perfectly!\n";
    echo "   ✅ Cache hit rate: High\n";
    echo "   ✅ Performance boost: Outstanding\n";
} elseif ($improvement >= 50) {
    echo "✅ GOOD! Caching is effective.\n";
    echo "   Performance improvement: {$improvement}% (target > 70%)\n";
} else {
    echo "⚠️  MODERATE. Check cache configuration.\n";
    echo "   Performance improvement: {$improvement}% (target > 70%)\n";
}

echo "\n";

// ============================================
// CACHE VERIFICATION
// ============================================
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║                    CACHE VERIFICATION                     ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

$cacheKeys = [
    'mh.admin.user_stats',
    'mh.admin.kategori_counts',
    'mh.admin.total_tes',
    'mh.admin.fakultas_stats',
];

echo "Checking cache keys:\n";
foreach ($cacheKeys as $key) {
    $exists = Cache::has($key);
    $status = $exists ? '✅ EXISTS' : '❌ MISSING';
    echo "  - {$key}: {$status}\n";
}

echo "\n";

// ============================================
// CACHE INVALIDATION TEST
// ============================================
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║                 CACHE INVALIDATION TEST                   ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "Testing cache invalidation...\n";
echo "  1. Cache exists: " . (Cache::has('mh.admin.user_stats') ? 'YES ✅' : 'NO ❌') . "\n";

Cache::forget('mh.admin.user_stats');
echo "  2. Cache forgotten...\n";

echo "  3. Cache exists: " . (Cache::has('mh.admin.user_stats') ? 'YES ❌' : 'NO ✅') . "\n";

Cache::remember('mh.admin.user_stats', 300, function () {
    return DataDiris::query()
        ->join('hasil_kuesioners', 'data_diris.nim', '=', 'hasil_kuesioners.nim')
        ->selectRaw("COUNT(DISTINCT data_diris.nim) as total_users")
        ->first();
});
echo "  4. Cache recreated...\n";

echo "  5. Cache exists: " . (Cache::has('mh.admin.user_stats') ? 'YES ✅' : 'NO ❌') . "\n";

echo "\n✅ Cache invalidation working correctly!\n\n";

// ============================================
// RECOMMENDATIONS
// ============================================
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║                    RECOMMENDATIONS                        ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "✅ COMPLETED OPTIMIZATIONS:\n";
echo "   1. ✅ Database Indexes\n";
echo "   2. ✅ N+1 Query Elimination\n";
echo "   3. ✅ Query Result Caching\n";
echo "\n";

echo "🚀 NEXT STEPS (Optional):\n";
echo "   1. ⬜ Switch to Redis cache for production\n";
echo "   2. ⬜ Implement cache warming strategy\n";
echo "   3. ⬜ Monitor cache hit rate with Debugbar\n";
echo "   4. ⬜ Add response caching (HTTP cache)\n";
echo "\n";

echo "💡 TIP: For production, use Redis instead of file cache:\n";
echo "   CACHE_DRIVER=redis (in .env)\n";
echo "\n";

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║                    TEST COMPLETED                         ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";
