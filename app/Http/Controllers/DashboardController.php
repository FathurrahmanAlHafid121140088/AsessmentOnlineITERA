<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\HasilKuesioner;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Membuat query dasar yang akan digunakan kembali
        $baseQuery = HasilKuesioner::query()
            ->leftJoin('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
            ->where('hasil_kuesioners.nim', $user->nim)
            ->select(
                'data_diris.nama',
                'hasil_kuesioners.nim',
                'data_diris.program_studi',
                'hasil_kuesioners.kategori as kategori_mental_health',
                'hasil_kuesioners.total_skor',
                'hasil_kuesioners.created_at',
                DB::raw('(SELECT keluhan FROM riwayat_keluhans WHERE nim = hasil_kuesioners.nim AND created_at <= hasil_kuesioners.created_at ORDER BY created_at DESC LIMIT 1) as keluhan'),
                DB::raw('(SELECT lama_keluhan FROM riwayat_keluhans WHERE nim = hasil_kuesioners.nim AND created_at <= hasil_kuesioners.created_at ORDER BY created_at DESC LIMIT 1) as lama_keluhan')
            );

        // 1. Ambil SEMUA data untuk chart (diurutkan dari TERLAMA ke terbaru - asc)
        $semuaRiwayat = $baseQuery->clone()->orderBy('hasil_kuesioners.created_at', 'asc')->get();

        // 2. Ambil data PAGINASI untuk tabel riwayat (urutan sama, tes terlama di halaman pertama)
        $riwayatTes = $baseQuery->clone()->orderBy('hasil_kuesioners.created_at', 'asc')->paginate(10);

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

        return view('user-mental-health', [
            'title' => 'Dashboard Mental Health',
            'jumlahTesDiikuti' => $jumlahTesDiikuti,
            'jumlahTesSelesai' => $jumlahTesDiikuti,
            'kategoriTerakhir' => $kategoriTerakhir,
            'riwayatTes' => $riwayatTes, // Kirim data paginasi ke view
            'chartLabels' => $labels,
            'chartScores' => $scores,
        ]);
    }
}

