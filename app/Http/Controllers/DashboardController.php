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

        // Ambil data tes + total_skor
        $riwayatData = HasilKuesioner::query()
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
            )
            ->orderBy('hasil_kuesioners.created_at', 'asc')
            ->get();

        // Label: Tes ke-1, Tes ke-2, dst
        $labels = $riwayatData->map(function ($item, $index) {
            return 'Tes ' . ($index + 1);
        });

        // Data skor (pastikan cast ke integer)
        $scores = $riwayatData->pluck('total_skor')->map(fn($v) => (int) $v);

        $jumlahTesDiikuti = $riwayatData->count();
        $kategoriTerakhir = $riwayatData->isNotEmpty() ? $riwayatData->last()->kategori_mental_health : 'Belum ada tes';

        return view('user-mental-health', [
            'title' => 'Dashboard Mental Health',
            'jumlahTesDiikuti' => $jumlahTesDiikuti,
            'jumlahTesSelesai' => $jumlahTesDiikuti,
            'kategoriTerakhir' => $kategoriTerakhir,
            'riwayatTes' => $riwayatData,
            'chartLabels' => $labels,
            'chartScores' => $scores,
        ]);
    }


}