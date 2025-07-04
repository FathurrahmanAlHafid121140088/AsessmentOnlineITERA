<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilKuesioner;

class HasilKuesionerController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required',
        ]);

        $totalSkor = 0;

        for ($i = 1; $i <= 38; $i++) {
            $totalSkor += (int) $request->input("question{$i}");
        }

        // kategori berdasarkan total skor
        $kategori = match (true) {
            $totalSkor >= 191 && $totalSkor <= 226 => 'Sangat Baik (Sejahtera Secara Mental)',
            $totalSkor >= 161 && $totalSkor <= 190 => 'Baik (Sehat Secara Mental)',
            $totalSkor >= 131 && $totalSkor <= 160 => 'Sedang (Rentan)',
            $totalSkor >= 91 && $totalSkor <= 130 => 'Buruk (Distres Sedang)',
            $totalSkor >= 38 && $totalSkor <= 90 => 'Sangat Buruk (Distres Berat)',
            default => 'Tidak Terdefinisi',
        };
        try {
            HasilKuesioner::create([
                'nim' => $validated['nim'],
                'total_skor' => $totalSkor,
                'kategori' => $kategori,
            ]);
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
