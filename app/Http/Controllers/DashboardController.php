<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\HasilKuesioner;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ⚡ CACHING: Cache data statistik & chart selama 5 menit
        $cacheKey = "mh.user.{$user->nim}.test_history";

        $testData = Cache::remember($cacheKey, 300, function () use ($user) {

            // 1. QUERY UNTUK CHART (Tetap ASC agar grafik dari kiri ke kanan urut waktu)
            $baseQuery = HasilKuesioner::query()
                ->leftJoin('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
                ->leftJoin('riwayat_keluhans', function ($join) {
                    $join->on('hasil_kuesioners.nim', '=', 'riwayat_keluhans.nim')
                        ->whereColumn('riwayat_keluhans.created_at', '<=', 'hasil_kuesioners.created_at')
                        ->whereRaw('riwayat_keluhans.created_at = (
                             SELECT MAX(rk.created_at)
                             FROM riwayat_keluhans rk
                             WHERE rk.nim = hasil_kuesioners.nim
                             AND rk.created_at <= hasil_kuesioners.created_at
                          )');
                })
                ->where('hasil_kuesioners.nim', $user->nim)
                ->where('hasil_kuesioners.status', 'selesai') // Filter Status Selesai
                ->select(
                    'hasil_kuesioners.kategori as kategori_mental_health',
                    'hasil_kuesioners.total_skor',
                    'hasil_kuesioners.created_at'
                );

            // Urutkan ASC (Terlama -> Terbaru) untuk keperluan Grafik
            $semuaRiwayat = $baseQuery->orderBy('hasil_kuesioners.created_at', 'asc')->get();

            // --- Proses Data Chart ---
            $labels = $semuaRiwayat->map(function ($item, $index) {
                return 'Tes ' . ($index + 1);
            });

            $scores = $semuaRiwayat->pluck('total_skor')->map(fn($v) => (int) $v);

            $jumlahTesDiikuti = $semuaRiwayat->count();
            $kategoriTerakhir = $semuaRiwayat->isNotEmpty() ? $semuaRiwayat->last()->kategori_mental_health : 'Belum ada tes';

            return [
                'jumlahTesDiikuti' => $jumlahTesDiikuti,
                'kategoriTerakhir' => $kategoriTerakhir,
                'chartLabels' => $labels,
                'chartScores' => $scores,
            ];
        });

        // 2. QUERY PAGINASI TABEL RIWAYAT (DESC: Terbaru di Atas)
        $riwayatTes = HasilKuesioner::query()
            ->leftJoin('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
            ->leftJoin('riwayat_keluhans', function ($join) {
                $join->on('hasil_kuesioners.nim', '=', 'riwayat_keluhans.nim')
                    ->whereColumn('riwayat_keluhans.created_at', '<=', 'hasil_kuesioners.created_at')
                    ->whereRaw('riwayat_keluhans.created_at = (
                          SELECT MAX(rk.created_at)
                          FROM riwayat_keluhans rk
                          WHERE rk.nim = hasil_kuesioners.nim
                          AND rk.created_at <= hasil_kuesioners.created_at
                      )');
            })
            ->where('hasil_kuesioners.nim', $user->nim)
            ->where('hasil_kuesioners.status', 'selesai') // Filter Status Selesai
            ->select(
                'data_diris.nama',
                'hasil_kuesioners.nim',
                'data_diris.program_studi',
                'hasil_kuesioners.kategori as kategori_mental_health',
                'hasil_kuesioners.total_skor',
                'hasil_kuesioners.created_at', // Pastikan kolom ini ada untuk sorting
                'hasil_kuesioners.updated_at', // Tambahkan updated_at jika perlu untuk tampilan "Berlaku hingga"
                'riwayat_keluhans.keluhan',
                'riwayat_keluhans.lama_keluhan'
            )
            ->orderBy('hasil_kuesioners.created_at', 'desc') // ✅ UBAH KE DESC (Terbaru Paling Atas)
            ->paginate(10);

        return view('user-mental-health', [
            'title' => 'Dashboard Mental Health',
            'jumlahTesDiikuti' => $testData['jumlahTesDiikuti'],
            'jumlahTesSelesai' => $testData['jumlahTesDiikuti'],
            'kategoriTerakhir' => $testData['kategoriTerakhir'],
            'riwayatTes' => $riwayatTes,
            'chartLabels' => $testData['chartLabels'],
            'chartScores' => $testData['chartScores'],
        ]);
    }
}