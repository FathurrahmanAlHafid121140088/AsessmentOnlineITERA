<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;

class HasilKuesionerController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        // Ambil semua hasil kuesioner dengan relasi
        $allHasil = HasilKuesioner::with(['dataDiri', 'riwayatKeluhans'])
            ->when($query, function ($q) use ($query) {
                $q->where('nim', 'like', "%{$query}%")
                    ->orWhereHas('dataDiri', function ($q2) use ($query) {
                        $q2->where('nama', 'like', "%{$query}%")
                            ->orWhere('program_studi', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%")
                            ->orWhere('alamat', 'like', "%{$query}%")
                            ->orWhere('jenis_kelamin', 'like', "%{$query}%")
                            ->orWhere('fakultas', 'like', "%{$query}%");
                    });
            })
            ->get();

        // Ambil hasil terbaru per NIM
        $hasilKuesioners = $allHasil
            ->groupBy('nim')
            ->map(fn($group) => $group->sortByDesc('created_at')->first())
            ->values();

        // Hitung total user unik (berdasarkan semua data, tanpa filter pencarian)
        $totalUsers = HasilKuesioner::pluck('nim')->unique()->count();

        // Hitung total tes (dengan skor yang tidak null)
        $totalTes = HasilKuesioner::whereNotNull('total_skor')->count();

        // Hitung distribusi fakultas (untuk bar/pie chart)
        $fakultasCount = DataDiris::select('fakultas', DB::raw('COUNT(*) as total'))
            ->whereNotNull('fakultas') // hanya abaikan yang kosong/null
            ->groupBy('fakultas')
            ->pluck('total', 'fakultas');

        // Hitung total seluruh fakultas terdaftar (yang termasuk 3 fakultas itu)
        $totalFakultas = $fakultasCount->sum();

        // Hitung persentase untuk setiap fakultas
        $fakultasPersen = $fakultasCount->map(function ($value) use ($totalFakultas) {
            return $totalFakultas > 0 ? round(($value / $totalFakultas) * 100, 1) : 0;
        });

        $warnaFakultas = [
            'Fakultas Sains' => '#4e79a7',
            'Fakultas Teknologi Industri' => '#f28e2c',
            'Fakultas Teknologi Infrastruktur dan Kewilayahan' => '#e15759',
        ];

        return view('admin-home', [
            'title' => 'Admin',
            'hasilKuesioners' => $hasilKuesioners,
            'totalUsers' => $totalUsers,
            'totalTes' => $totalTes,
            'fakultasCount' => $fakultasCount,
            'fakultasPersen' => $fakultasPersen,
            'warnaFakultas' => $warnaFakultas, // ⬅️ tambahkan ini
        ]);


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
