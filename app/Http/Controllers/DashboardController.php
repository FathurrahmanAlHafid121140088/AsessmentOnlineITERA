<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // ⚡ CACHING: Import Cache facade
use App\Models\HasilKuesioner;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ⚡ CACHING: Cache user test history for 5 minutes (per user)
        // Cache key includes user NIM for per-user caching
        $cacheKey = "mh.user.{$user->nim}.test_history";

        $testData = Cache::remember($cacheKey, 300, function () use ($user) {
            // Membuat query dasar yang akan digunakan kembali
            // ⚡ OPTIMASI: Query untuk ambil data tes dengan keluhan terbaru sebelum setiap tes
            $baseQuery = HasilKuesioner::query()
                ->leftJoin('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
                ->leftJoin('riwayat_keluhans', function($join) {
                    $join->on('hasil_kuesioners.nim', '=', 'riwayat_keluhans.nim')
                         ->whereColumn('riwayat_keluhans.created_at', '<=', 'hasil_kuesioners.created_at')
                         // Ambil keluhan terbaru sebelum test dengan subquery
                         ->whereRaw('riwayat_keluhans.created_at = (
                             SELECT MAX(rk.created_at)
                             FROM riwayat_keluhans rk
                             WHERE rk.nim = hasil_kuesioners.nim
                             AND rk.created_at <= hasil_kuesioners.created_at
                         )');
                })
                ->where('hasil_kuesioners.nim', $user->nim)
                ->select(
                    'data_diris.nama',
                    'hasil_kuesioners.nim',
                    'data_diris.program_studi',
                    'hasil_kuesioners.kategori as kategori_mental_health',
                    'hasil_kuesioners.total_skor',
                    'hasil_kuesioners.created_at',
                    'riwayat_keluhans.keluhan',
                    'riwayat_keluhans.lama_keluhan'
                );

            // 1. Ambil SEMUA data untuk chart (diurutkan dari TERLAMA ke terbaru - asc)
            $semuaRiwayat = $baseQuery->orderBy('hasil_kuesioners.created_at', 'asc')->get();

            // --- Proses data untuk chart dan statistik dari $semuaRiwayat ---

            // Label: Tes ke-1, Tes ke-2, dst. ("Tes 1" adalah tes terlama)
            $labels = $semuaRiwayat->map(function ($item, $index) {
                return 'Tes ' . ($index + 1);
            });

            // Data skor (diurutkan dari terlama ke terbaru)
            $scores = $semuaRiwayat->pluck('total_skor')->map(fn($v) => (int) $v);

            $jumlahTesDiikuti = $semuaRiwayat->count();
            // Karena urutan asc, data terakhir adalah item TERAKHIR
            $kategoriTerakhir = $semuaRiwayat->isNotEmpty() ? $semuaRiwayat->last()->kategori_mental_health : 'Belum ada tes';

            return [
                'jumlahTesDiikuti' => $jumlahTesDiikuti,
                'kategoriTerakhir' => $kategoriTerakhir,
                'chartLabels' => $labels,
                'chartScores' => $scores,
            ];
        });

        // 2. Ambil data PAGINASI untuk tabel riwayat (NOT cached, changes per page)
        // Subquery untuk mendapatkan keluhan terbaru SEBELUM setiap tes
        $riwayatTes = HasilKuesioner::query()
            ->leftJoin('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
            ->leftJoin('riwayat_keluhans', function($join) {
                $join->on('hasil_kuesioners.nim', '=', 'riwayat_keluhans.nim')
                     ->whereColumn('riwayat_keluhans.created_at', '<=', 'hasil_kuesioners.created_at')
                     // Ambil keluhan terbaru sebelum test dengan subquery
                     ->whereRaw('riwayat_keluhans.created_at = (
                         SELECT MAX(rk.created_at)
                         FROM riwayat_keluhans rk
                         WHERE rk.nim = hasil_kuesioners.nim
                         AND rk.created_at <= hasil_kuesioners.created_at
                     )');
            })
            ->where('hasil_kuesioners.nim', $user->nim)
            ->select(
                'data_diris.nama',
                'hasil_kuesioners.nim',
                'data_diris.program_studi',
                'hasil_kuesioners.kategori as kategori_mental_health',
                'hasil_kuesioners.total_skor',
                'hasil_kuesioners.created_at',
                'riwayat_keluhans.keluhan',
                'riwayat_keluhans.lama_keluhan'
            )
            ->orderBy('hasil_kuesioners.created_at', 'asc')
            ->paginate(10);

        return view('user-mental-health', [
            'title' => 'Dashboard Mental Health',
            'jumlahTesDiikuti' => $testData['jumlahTesDiikuti'],
            'jumlahTesSelesai' => $testData['jumlahTesDiikuti'],
            'kategoriTerakhir' => $testData['kategoriTerakhir'],
            'riwayatTes' => $riwayatTes, // Kirim data paginasi ke view
            'chartLabels' => $testData['chartLabels'],
            'chartScores' => $testData['chartScores'],
        ]);
    }
}

