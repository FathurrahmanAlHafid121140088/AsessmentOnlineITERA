<?php
/**
 * ============================================
 * DATABASE INDEXES PERFORMANCE TEST SCRIPT
 * ============================================
 *
 * Script untuk testing performance improvement setelah menambahkan indexes
 *
 * CARA PENGGUNAAN:
 * 1. Pastikan database sudah ada data (minimal 100 rows di hasil_kuesioners)
 * 2. Run: php test-indexes-performance.php
 * 3. Lihat hasil comparison BEFORE vs AFTER indexes
 */

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n";
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║   DATABASE INDEXES PERFORMANCE TEST - MENTAL HEALTH      ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

// ============================================
// TEST 1: JOIN Query Performance
// ============================================
echo "📊 TEST 1: JOIN Query (Dashboard Admin)\n";
echo "─────────────────────────────────────────────────────────────\n";

$start = microtime(true);
$results = DB::table('hasil_kuesioners')
    ->join('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
    ->select('hasil_kuesioners.*', 'data_diris.nama')
    ->orderBy('hasil_kuesioners.created_at', 'desc')
    ->limit(50)
    ->get();
$time1 = (microtime(true) - $start) * 1000;

echo "✅ Query executed in: " . round($time1, 2) . " ms\n";
echo "📈 Rows returned: " . count($results) . "\n";
echo "💡 Expected: < 100ms (with indexes)\n";
echo "\n";

// ============================================
// TEST 2: Filter by Kategori
// ============================================
echo "📊 TEST 2: Filter by Kategori\n";
echo "─────────────────────────────────────────────────────────────\n";

$start = microtime(true);
$results = DB::table('hasil_kuesioners')
    ->where('kategori', 'Baik')
    ->orderBy('created_at', 'desc')
    ->get();
$time2 = (microtime(true) - $start) * 1000;

echo "✅ Query executed in: " . round($time2, 2) . " ms\n";
echo "📈 Rows returned: " . count($results) . "\n";
echo "💡 Expected: < 50ms (with indexes)\n";
echo "\n";

// ============================================
// TEST 3: Search by NIM
// ============================================
echo "📊 TEST 3: Search by NIM\n";
echo "─────────────────────────────────────────────────────────────\n";

// Get first NIM from data_diris
$firstNim = DB::table('data_diris')->first()->nim ?? null;

if ($firstNim) {
    $start = microtime(true);
    $results = DB::table('hasil_kuesioners')
        ->where('nim', $firstNim)
        ->orderBy('created_at', 'desc')
        ->get();
    $time3 = (microtime(true) - $start) * 1000;

    echo "✅ Query executed in: " . round($time3, 2) . " ms\n";
    echo "📈 Rows returned: " . count($results) . "\n";
    echo "💡 Expected: < 10ms (with indexes)\n";
} else {
    echo "⚠️  No data found in data_diris table\n";
    $time3 = 0;
}
echo "\n";

// ============================================
// TEST 4: Complex Search (nama LIKE)
// ============================================
echo "📊 TEST 4: Search Mahasiswa by Nama\n";
echo "─────────────────────────────────────────────────────────────\n";

$start = microtime(true);
$results = DB::table('data_diris')
    ->where('nama', 'LIKE', '%a%')
    ->limit(20)
    ->get();
$time4 = (microtime(true) - $start) * 1000;

echo "✅ Query executed in: " . round($time4, 2) . " ms\n";
echo "📈 Rows returned: " . count($results) . "\n";
echo "💡 Expected: < 80ms (with indexes)\n";
echo "\n";

// ============================================
// TEST 5: Composite Index Test (kategori + created_at)
// ============================================
echo "📊 TEST 5: Filter Kategori + Sort by Date\n";
echo "─────────────────────────────────────────────────────────────\n";

$start = microtime(true);
$results = DB::table('hasil_kuesioners')
    ->where('kategori', 'Baik')
    ->orderBy('created_at', 'desc')
    ->limit(20)
    ->get();
$time5 = (microtime(true) - $start) * 1000;

echo "✅ Query executed in: " . round($time5, 2) . " ms\n";
echo "📈 Rows returned: " . count($results) . "\n";
echo "💡 Expected: < 30ms (with composite index)\n";
echo "\n";

// ============================================
// TEST 6: EXPLAIN Query Analysis
// ============================================
echo "📊 TEST 6: EXPLAIN Analysis (Check if indexes are used)\n";
echo "─────────────────────────────────────────────────────────────\n";

$explain = DB::select("
    EXPLAIN SELECT hk.*, dd.nama
    FROM hasil_kuesioners hk
    JOIN data_diris dd ON hk.nim = dd.nim
    WHERE hk.kategori = 'Baik'
    ORDER BY hk.created_at DESC
    LIMIT 10
");

echo "Table: hasil_kuesioners\n";
foreach ($explain as $row) {
    if ($row->table === 'hk') {
        echo "  - Type: " . $row->type . "\n";
        echo "  - Key Used: " . ($row->key ?? 'NONE') . "\n";
        echo "  - Rows Examined: " . ($row->rows ?? 'N/A') . "\n";

        if ($row->key && str_contains($row->key, 'idx_')) {
            echo "  ✅ INDEX BEING USED!\n";
        } else {
            echo "  ⚠️  NO INDEX USED (Full table scan)\n";
        }
    }
}
echo "\n";

// ============================================
// SUMMARY
// ============================================
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║                    PERFORMANCE SUMMARY                    ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

$totalTime = $time1 + $time2 + $time3 + $time4 + $time5;
$averageTime = $totalTime / 5;

printf("📊 Test 1 (JOIN Query):           %6.2f ms\n", $time1);
printf("📊 Test 2 (Filter Kategori):      %6.2f ms\n", $time2);
printf("📊 Test 3 (Search by NIM):        %6.2f ms\n", $time3);
printf("📊 Test 4 (Search by Nama):       %6.2f ms\n", $time4);
printf("📊 Test 5 (Composite Index):      %6.2f ms\n", $time5);
echo "─────────────────────────────────────────────────────────────\n";
printf("⚡ Total Time:                   %6.2f ms\n", $totalTime);
printf("📈 Average Time:                 %6.2f ms\n", $averageTime);
echo "\n";

// Performance Rating
if ($averageTime < 50) {
    echo "🎉 EXCELLENT! Indexes working perfectly!\n";
} elseif ($averageTime < 100) {
    echo "✅ GOOD! Performance is acceptable.\n";
} elseif ($averageTime < 200) {
    echo "⚠️  MODERATE. Consider optimizing queries.\n";
} else {
    echo "❌ SLOW! Check if indexes are properly created.\n";
}

echo "\n";
echo "💡 TIP: Run this script multiple times for consistent results\n";
echo "💡 TIP: Check 'EXPLAIN' output to verify indexes are being used\n";
echo "\n";

// ============================================
// VERIFICATION: List All Indexes
// ============================================
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║                   INSTALLED INDEXES                       ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

$tables = ['hasil_kuesioners', 'data_diris', 'riwayat_keluhans'];

foreach ($tables as $table) {
    echo "📋 Table: $table\n";
    echo "─────────────────────────────────────────────────────────────\n";

    try {
        $indexes = DB::select("SHOW INDEX FROM $table WHERE Key_name LIKE 'idx_%'");

        if (empty($indexes)) {
            echo "  ⚠️  No custom indexes found\n";
        } else {
            $indexGroups = [];
            foreach ($indexes as $index) {
                $indexGroups[$index->Key_name][] = $index->Column_name;
            }

            foreach ($indexGroups as $indexName => $columns) {
                echo "  ✅ " . $indexName . " (" . implode(', ', $columns) . ")\n";
            }
        }
    } catch (\Exception $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
    }

    echo "\n";
}

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║                    TEST COMPLETED                         ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";
