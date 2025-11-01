<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // ⚡ CACHING: Import Cache facade
use App\Models\HasilKuesioner;
use App\Http\Requests\StoreHasilKuesionerRequest;

class HasilKuesionerController extends Controller
{
    public function store(StoreHasilKuesionerRequest $request)
    {
        // Data sudah tervalidasi otomatis oleh FormRequest (nim + 38 questions)
        $validated = $request->validated();

        $totalSkor = 0;

        for ($i = 1; $i <= 38; $i++) {
            $totalSkor += (int) $request->input("question{$i}");
        }

        // kategori berdasarkan total skor
        $kategori = match (true) {
            $totalSkor >= 190 && $totalSkor <= 226 => 'Sangat Sehat',
            $totalSkor >= 152 && $totalSkor <= 189 => 'Sehat',
            $totalSkor >= 114 && $totalSkor <= 151 => 'Cukup Sehat',
            $totalSkor >= 76 && $totalSkor <= 113 => 'Perlu Dukungan',
            $totalSkor >= 38 && $totalSkor <= 75 => 'Perlu Dukungan Intensif',
            default => 'Tidak Terdefinisi',
        };
        try {
            HasilKuesioner::create([
                'nim' => $validated['nim'],
                'total_skor' => $totalSkor,
                'kategori' => $kategori,
            ]);

            // ⚡ CACHING: Invalidate all related caches after creating new test
            // 1. Invalidate admin dashboard caches
            Cache::forget('mh.admin.user_stats');
            Cache::forget('mh.admin.kategori_counts');
            Cache::forget('mh.admin.total_tes');
            Cache::forget('mh.admin.fakultas_stats');

            // 2. Invalidate user-specific cache
            Cache::forget("mh.user.{$validated['nim']}.test_history");

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal menyimpan hasil kuesioner: ' . $e->getMessage()
            ]);
        }

        // pastikan nim tetap di session
        session(['nim' => $validated['nim']]);
        return redirect()
            ->route('mental-health.hasil')
            ->with('success', 'Hasil kuesioner berhasil disimpan.');
    }

    public function showLatest()
    {
        $nim = session('nim');
        $nama = session('nama');
        $programStudi = session('program_studi');

        if (!$nim) {
            return redirect()->route('mental-health.kuesioner')
                ->with('error', 'NIM tidak ditemukan di sesi.');
        }

        $hasil = HasilKuesioner::where('nim', $nim)->latest()->first();

        if (!$hasil) {
            return redirect()->route('mental-health.kuesioner')
                ->with('error', 'Data hasil kuesioner tidak ditemukan.');
        }

        return view('hasil', [
            'title' => 'Hasil Kuesioner Mental Health',
            'hasil' => $hasil,
            'nama' => $nama,
            'program_studi' => $programStudi
        ]);
    }
}
