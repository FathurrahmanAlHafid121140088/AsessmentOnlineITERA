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

        // Subquery: ambil ID hasil terakhir tiap NIM
        $latestIds = DB::table('hasil_kuesioners')
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('nim');

        $hasilKuesioners = HasilKuesioner::select('hasil_kuesioners.*')
            ->joinSub($latestIds, 'latest', function ($join) {
                $join->on('hasil_kuesioners.id', '=', 'latest.id');
            })
            ->with(['dataDiri'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('nim', 'like', "%$search%")
                        ->orWhereHas('dataDiri', function ($q3) use ($search) {
                            $q3->where('nama', 'like', "%$search%")
                                ->orWhere('program_studi', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('alamat', 'like', "%$search%")
                                ->orWhere('jenis_kelamin', 'like', "%$search%")
                                ->orWhere('fakultas', 'like', "%$search%")
                                ->orWhere('provinsi', 'like', "%$search%")
                                ->orWhere('asal_sekolah', 'like', "%$search%")
                                ->orWhere('status_tinggal', 'like', "%$search%");
                        });
                });
            })
            ->orderBy($sort == 'nama' ? 'created_at' : $sort, $order)
            ->paginate($limit)
            ->withQueryString();

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

        // Data chart dan statistik
        $kategoriCounts = HasilKuesioner::selectRaw('kategori, COUNT(*) as jumlah')
            ->groupBy('kategori')
            ->pluck('jumlah', 'kategori')
            ->toArray();

        $totalUsers = HasilKuesioner::distinct('nim')->count('nim');
        $totalTes = HasilKuesioner::count();

        $nimDenganHasil = HasilKuesioner::distinct('nim')->pluck('nim');

        $totalLaki = DataDiris::whereIn('nim', $nimDenganHasil)
            ->where('jenis_kelamin', 'L')
            ->count();

        $totalPerempuan = DataDiris::whereIn('nim', $nimDenganHasil)
            ->where('jenis_kelamin', 'P')
            ->count();

        // ====== Donut: Asal Sekolah ======
        $asalCounts = [
            'SMA' => DataDiris::where('asal_sekolah', 'SMA')->count(),
            'SMK' => DataDiris::where('asal_sekolah', 'SMK')->count(),
            'Boarding School' => DataDiris::where('asal_sekolah', 'Boarding School')->count(),
        ];
        $totalAsal = array_sum($asalCounts);
        $pct = function ($n) use ($totalAsal) {
            return $totalAsal > 0 ? round(($n / $totalAsal) * 100, 1) : 0;
        };

        $r = 60;
        $circ = 2 * M_PI * $r;
        $segments = [];
        $offset = 0;
        foreach ($asalCounts as $label => $val) {
            $p = $totalAsal > 0 ? $val / $totalAsal : 0;
            $dash = $circ * $p;
            $segments[] = [
                'label' => $label,
                'value' => $val,
                'percent' => $pct($val),
                'dash' => $dash,
                'offset' => $offset,
            ];
            $offset += $dash;
        }

        return view('admin-home', [
            'title' => 'Dashboard Mental Health',
            'hasilKuesioners' => $hasilKuesioners,
            'kategoriCounts' => $kategoriCounts,
            'limit' => $limit,
            'totalUsers' => $totalUsers,
            'totalTes' => $totalTes,
            'totalLaki' => $totalLaki,
            'totalPerempuan' => $totalPerempuan,
            'asalCounts' => $asalCounts,
            'totalAsal' => $totalAsal,
            'segments' => $segments,
            'radius' => $r,
            'circumference' => $circ
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
    public function showGauge()
    {
        $totalTinggal = DataDiris::count();
        $kostCount = DataDiris::where('status_tinggal', 'Kost')->count();
        $kostPercent = $totalTinggal ? round(($kostCount / $totalTinggal) * 100, 2) : 0;

        return view('admin-home', compact('kostPercent'));
    }
}
