<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;

class HasilKuesionerController extends Controller
{
    private function getStatistikFakultas()
    {
        $fakultasCount = DataDiris::select('fakultas', DB::raw('COUNT(*) as total'))
            ->whereNotNull('fakultas')
            ->groupBy('fakultas')
            ->pluck('total', 'fakultas');

        $totalFakultas = $fakultasCount->sum();

        $fakultasPersen = $fakultasCount->map(function ($count) use ($totalFakultas) {
            return $totalFakultas > 0 ? round(($count / $totalFakultas) * 100, 1) : 0;
        });

        $warnaFakultas = [
            'Fakultas Sains' => '#4e79a7',
            'Fakultas Teknologi Industri' => '#f28e2c',
            'Fakultas Teknologi Infrastruktur dan Kewilayahan' => '#e15759',
        ];

        return [
            'fakultasCount' => $fakultasCount,
            'fakultasPersen' => $fakultasPersen,
            'warnaFakultas' => $warnaFakultas,
        ];
    }

    public function dashboard()
    {
        $kategoriCounts = HasilKuesioner::selectRaw('kategori, COUNT(*) as jumlah')
            ->groupBy('kategori')
            ->pluck('jumlah', 'kategori')
            ->toArray();

        $title = "Dashboard Mental Health";

        // tambahkan hasilKuesioners kosong
        $hasilKuesioners = collect();  // supaya tidak undefined

        return view('admin-home', [
            'title' => $title,
            'kategoriCounts' => $kategoriCounts,
            'hasilKuesioners' => $hasilKuesioners,
        ] + $this->getStatistikFakultas());
    }

    public function storeKuesioner(Request $request)
    {
        $validated = $request->validate([
            'total_skor' => 'required|integer|min:0',
            'kategori' => 'required|string|max:255',
        ]);

        $nim = session('nim');

        if (!$nim) {
            return redirect()->route('mental-health.data-diri')->withErrors([
                'error' => 'NIM tidak ditemukan. Silakan isi data diri terlebih dahulu.'
            ]);
        }

        $existing = HasilKuesioner::where('nim', $nim)->first();

        if (!$existing) {
            HasilKuesioner::create([
                'nim' => $nim,
                'total_skor' => $validated['total_skor'],
                'kategori' => $validated['kategori'],
            ]);
        }

        return redirect()->route('admin.home')->with('success', 'Hasil kuesioner berhasil disimpan.');
    }

    public function destroy($id)
    {
        $hasil = HasilKuesioner::find($id);

        if (!$hasil) {
            return redirect()->route('admin.home')->with('error', 'Data tidak ditemukan.');
        }

        $hasil->delete();

        return redirect()->route('admin.home')->with('success', 'Data berhasil dihapus.');
    }
}
