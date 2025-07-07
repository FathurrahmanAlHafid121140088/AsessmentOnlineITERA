<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;

class HasilKuesionerCombinedController extends Controller
{
    /**
     * Dashboard + pagination + search
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $search = $request->input('search');

        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');

        // ambil id terakhir per NIM
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
            ->orderBy($sort == 'nama' ? 'created_at' : $sort, $order)
            ->paginate($limit)
            ->withQueryString();

        // sorting manual kalau berdasarkan nama (relasi)
        if ($sort == 'nama') {
            $hasilKuesioners = $hasilKuesioners->getCollection()
                ->sortBy(function ($item) {
                    return $item->dataDiri->nama ?? '';
                }, SORT_REGULAR, $order === 'desc')
                ->values();

            $hasilKuesioners = new \Illuminate\Pagination\LengthAwarePaginator(
                $hasilKuesioners,
                $hasilKuesioners->count(),
                $limit,
                $request->input('page', 1),
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        // data chart
        $kategoriCounts = HasilKuesioner::selectRaw('kategori, COUNT(*) as jumlah')
            ->groupBy('kategori')
            ->pluck('jumlah', 'kategori')
            ->toArray();

        $totalUsers = HasilKuesioner::distinct('nim')->count('nim');
        $totalTes = HasilKuesioner::count();

        // ambil NIM yang sudah pernah tes
        $nimDenganHasil = \App\Models\HasilKuesioner::distinct('nim')->pluck('nim');

        // hitung total laki-laki yang sudah pernah tes
        $totalLaki = \App\Models\DataDiris::whereIn('nim', $nimDenganHasil)
            ->where('jenis_kelamin', 'L')
            ->count();

        // hitung total perempuan yang sudah pernah tes
        $totalPerempuan = \App\Models\DataDiris::whereIn('nim', $nimDenganHasil)
            ->where('jenis_kelamin', 'P')
            ->count();

        return view('admin-home', [
            'title' => 'Dashboard Mental Health',
            'hasilKuesioners' => $hasilKuesioners,
            'kategoriCounts' => $kategoriCounts,
            'limit' => $limit,
            'totalUsers' => $totalUsers,
            'totalTes' => $totalTes,
            'totalLaki' => $totalLaki,
            'totalPerempuan' => $totalPerempuan,
        ] + $this->getStatistikFakultas());
    }
    /**
     * Statistik fakultas
     */
    private function getStatistikFakultas()
    {
        // ambil hanya mahasiswa yang punya hasil kuesioner
        $fakultasCount = DataDiris::select('data_diris.fakultas', DB::raw('COUNT(DISTINCT data_diris.nim) as total'))
            ->join('hasil_kuesioners', 'data_diris.nim', '=', 'hasil_kuesioners.nim')
            ->whereNotNull('data_diris.fakultas')
            ->groupBy('data_diris.fakultas')
            ->pluck('total', 'data_diris.fakultas');

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
