<?php

namespace App\Http\Controllers;

use App\Models\HasilKuesioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataDiris;

class HasilKuesionerPaginationController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $search = $request->input('search');

        $latestPerNim = HasilKuesioner::select(DB::raw('MAX(id) as id'))->groupBy('nim');

        $hasilKuesioners = HasilKuesioner::with([
            'dataDiri',
            'riwayatKeluhans' => fn($q) => $q->latest()
        ])
            ->whereIn('id', $latestPerNim)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('nim', 'like', "%$search%")
                        ->orWhereHas('dataDiri', function ($q3) use ($search) {
                            $q3->where('nama', 'like', "%$search%")
                                ->orWhere('program_studi', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('alamat', 'like', "%$search%")
                                ->orWhere('jenis_kelamin', 'like', "%$search%")
                                ->orWhere('fakultas', 'like', "%$search%");
                        });
                });
            })
            ->latest()
            ->paginate($limit)
            ->withQueryString();

        // tambahkan kategoriCounts agar chart tetap muncul
        $kategoriCounts = HasilKuesioner::selectRaw('kategori, COUNT(*) as jumlah')
            ->groupBy('kategori')
            ->pluck('jumlah', 'kategori')
            ->toArray();

        return view('admin-home', [
            'title' => 'Admin - Data Kuesioner',
            'hasilKuesioners' => $hasilKuesioners,
            'kategoriCounts' => $kategoriCounts,
            'limit' => $limit,
        ] + $this->getStatistikFakultas());
    }
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

}
