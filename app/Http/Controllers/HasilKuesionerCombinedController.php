<?php

namespace App\Http\Controllers;

use App\Exports\HasilKuesionerExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

// MODELS
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use App\Models\Users;
use App\Models\RiwayatKeluhans;

class HasilKuesionerCombinedController extends Controller
{
    /**
     * DASHBOARD UTAMA ADMIN
     */
    public function index(Request $request)
    {
        try {
            // 1. Ambil parameter filter
            $limit = $request->input('limit', 10);
            $search = $request->input('search');
            $sort = $request->input('sort', 'created_at');
            $order = $request->input('order', 'desc');
            $kategori = $request->input('kategori');

            // 2. Subquery ID terakhir (HANYA YANG SELESAI)
            $latestIds = DB::table('hasil_kuesioners')
                ->select(DB::raw('MAX(id) as id'))
                ->where('status', 'selesai')
                ->groupBy('nim');

            // 3. Query Builder Utama
            $query = HasilKuesioner::query()
                ->joinSub($latestIds, 'latest', 'hasil_kuesioners.id', '=', 'latest.id')
                ->join('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
                ->where('hasil_kuesioners.status', 'selesai')
                ->select(
                    'hasil_kuesioners.*',
                    'data_diris.nama as nama_mahasiswa',
                    'data_diris.fakultas',
                    'data_diris.program_studi'
                )
                ->addSelect([
                    'jumlah_tes' => DB::table('hasil_kuesioners as hk_sub')
                        ->selectRaw('count(*)')
                        ->whereColumn('hk_sub.nim', 'hasil_kuesioners.nim')
                        ->where('hk_sub.status', 'selesai')
                ]);

            // 4. Filter Kategori
            $query->when($kategori, function ($q) use ($kategori) {
                $q->where('hasil_kuesioners.kategori', $kategori);
            });

            // 5. Fitur Pencarian
            $query->when($search, function ($q) use ($search) {
                $terms = array_filter(preg_split('/\s+/', trim($search)));
                $likeColumns = [
                    'hasil_kuesioners.nim',
                    'data_diris.nama',
                    'data_diris.email',
                    'data_diris.alamat',
                    'data_diris.asal_sekolah',
                    'data_diris.status_tinggal',
                ];

                $q->where(function (Builder $query) use ($terms, $likeColumns) {
                    foreach ($terms as $term) {
                        $query->where(function (Builder $subQuery) use ($term, $likeColumns) {
                            foreach ($likeColumns as $column) {
                                $subQuery->orWhere($column, 'like', "%$term%");
                            }
                            $subQuery->orWhere('data_diris.fakultas', 'like', "%$term%");
                            $subQuery->orWhere('data_diris.program_studi', 'like', "%$term%");
                        });
                    }
                });
            });

            // 6. Sorting
            $sortColumn = match ($sort) {
                'nama' => 'data_diris.nama',
                default => 'hasil_kuesioners.' . $sort,
            };
            $query->orderBy($sortColumn, $order);

            // 7. Eksekusi Pagination
            $hasilKuesioners = $query->paginate($limit)->withQueryString();

            $searchMessage = null;
            if ($search && !$request->has('page')) {
                $searchMessage = $hasilKuesioners->total() > 0 ? 'Data berhasil ditemukan!' : 'Data tidak ditemukan!';
            }

            // 8. Statistik Global (Cache 1 Menit)
            $userStats = Cache::remember('mh.admin.user_stats', 60, function () {
                $res = DB::table('data_diris')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('hasil_kuesioners')
                            ->whereColumn('hasil_kuesioners.nim', 'data_diris.nim')
                            ->where('hasil_kuesioners.status', 'selesai');
                    })
                    ->selectRaw("
                        COUNT(DISTINCT data_diris.nim) as total_users,
                        COUNT(DISTINCT CASE WHEN jenis_kelamin = 'L' THEN data_diris.nim END) as total_laki,
                        COUNT(DISTINCT CASE WHEN jenis_kelamin = 'P' THEN data_diris.nim END) as total_perempuan,
                        COUNT(DISTINCT CASE WHEN asal_sekolah = 'SMA' THEN data_diris.nim END) as total_sma,
                        COUNT(DISTINCT CASE WHEN asal_sekolah = 'SMK' THEN data_diris.nim END) as total_smk,
                        COUNT(DISTINCT CASE WHEN asal_sekolah = 'Boarding School' THEN data_diris.nim END) as total_boarding
                    ")
                    ->first();

                return $res ?: (object) [
                    'total_users' => 0,
                    'total_laki' => 0,
                    'total_perempuan' => 0,
                    'total_sma' => 0,
                    'total_smk' => 0,
                    'total_boarding' => 0
                ];
            });

            $totalUsers = $userStats->total_users ?? 0;
            $totalTes = Cache::remember('mh.admin.total_tes', 60, fn() => HasilKuesioner::where('status', 'selesai')->count());
            $totalLaki = $userStats->total_laki ?? 0;
            $totalPerempuan = $userStats->total_perempuan ?? 0;

            // Kategori Counts
            $kategoriCounts = Cache::remember('mh.admin.kategori_counts', 60, function () use ($latestIds) {
                return HasilKuesioner::whereIn('id', $latestIds)
                    ->selectRaw('kategori, COUNT(*) as jumlah')
                    ->groupBy('kategori')
                    ->pluck('jumlah', 'kategori')
                    ->all();
            });

            // ---------------------------------------------------------
            // [BARU] PIE CHART ANGKATAN (Menggantikan Status Tinggal)
            // ---------------------------------------------------------
            $angkatanStats = Cache::remember('mh.admin.angkatan_stats', 60, function () {
                // Logic: Ambil digit ke-2 dan 3 dari NIM (1[21]...) lalu concat dengan '20'
                // Filter: Hanya mahasiswa yang SUDAH SELESAI tes
                return DB::table('hasil_kuesioners')
                    ->where('status', 'selesai')
                    ->selectRaw("CONCAT('20', SUBSTRING(nim, 2, 2)) as tahun")
                    ->selectRaw("COUNT(DISTINCT nim) as total")
                    ->groupBy('tahun')
                    ->orderBy('tahun', 'asc')
                    ->get();
            });

            // Hitung Kalkulasi Lingkaran (Donut/Pie)
            $totalAngkatan = $angkatanStats->sum('total');
            $r = 60; // Radius SVG
            $circ = 2 * M_PI * $r;
            $angkatanSegments = [];
            $offset = 0;

            // Palet Warna Dinamis (Akan diloop jika angkatan bertambah)
            $palette = [
                '#4e79a7',
                '#f28e2c',
                '#e15759',
                '#76b7b2',
                '#59a14f',
                '#edc948',
                '#b07aa1',
                '#ff9da7',
                '#9c755f',
                '#bab0ac'
            ];

            foreach ($angkatanStats as $index => $row) {
                $val = $row->total;
                // Hitung Persentase
                $pct = $totalAngkatan > 0 ? round(($val / $totalAngkatan) * 100, 1) : 0;
                // Hitung panjang dash array untuk SVG
                $dash = $circ * ($totalAngkatan > 0 ? $val / $totalAngkatan : 0);

                // Ambil warna secara urut, balik ke awal jika habis
                $color = $palette[$index % count($palette)];

                $angkatanSegments[] = [
                    'label' => 'Angkatan ' . $row->tahun,
                    'value' => $val,
                    'percent' => $pct,
                    'dash' => $dash,
                    'offset' => $offset,
                    'color' => $color
                ];

                // Geser offset untuk segmen berikutnya
                $offset += $dash; // Ingat: SVG stroke-dashoffset berjalan counter-clockwise
            }
            // ---------------------------------------------------------

            // Data Grafik Donut (Asal Sekolah) - Tetap Ada
            $asalCounts = [
                'SMA' => $userStats->total_sma ?? 0,
                'SMK' => $userStats->total_smk ?? 0,
                'Boarding School' => $userStats->total_boarding ?? 0,
            ];
            $totalAsal = array_sum($asalCounts);
            $segments = [];
            $offsetAsal = 0;
            $pctAsal = fn($n) => $totalAsal > 0 ? round(($n / $totalAsal) * 100, 1) : 0;

            foreach ($asalCounts as $label => $val) {
                $p = $totalAsal > 0 ? $val / $totalAsal : 0;
                $dash = $circ * $p;
                $segments[] = ['label' => $label, 'value' => $val, 'percent' => $pctAsal($val), 'dash' => $dash, 'offset' => $offsetAsal];
                $offsetAsal += $dash;
            }

            // 10. Ambil Statistik Fakultas
            $fakultasData = $this->getStatistikFakultas();

            return view('admin-home', [
                'title' => 'Dashboard Mental Health',
                'hasilKuesioners' => $hasilKuesioners,
                'limit' => $limit,
                'kategoriCounts' => $kategoriCounts,
                'totalUsers' => $totalUsers,
                'totalTes' => $totalTes,
                'totalLaki' => $totalLaki,
                'totalPerempuan' => $totalPerempuan,

                // Data Chart Asal Sekolah
                'asalCounts' => $asalCounts,
                'totalAsal' => $totalAsal,
                'segments' => $segments,

                // [BARU] Data Chart Angkatan
                'angkatanSegments' => $angkatanSegments,
                'totalAngkatan' => $totalAngkatan,

                'radius' => $r,
                'circumference' => $circ,
                'searchMessage' => $searchMessage,
            ] + $fakultasData);

        } catch (\Exception $e) {
            dd("ERROR CONTROLLER DASHBOARD: " . $e->getMessage());
        }
    }

    /**
     * Helper: Statistik Fakultas
     */
    private function getStatistikFakultas()
    {
        $fakultasCount = DataDiris::select('data_diris.fakultas', DB::raw('COUNT(DISTINCT data_diris.nim) as total'))
            ->join('hasil_kuesioners', 'data_diris.nim', '=', 'hasil_kuesioners.nim')
            ->where('hasil_kuesioners.status', 'selesai')
            ->whereNotNull('data_diris.fakultas')
            ->groupBy('data_diris.fakultas')
            ->pluck('total', 'data_diris.fakultas');

        $totalFakultas = $fakultasCount->sum();
        $fakultasPersen = $fakultasCount->map(fn($count) => $totalFakultas > 0 ? round(($count / $totalFakultas) * 100, 1) : 0);

        return [
            'fakultasCount' => $fakultasCount->all(),
            'fakultasPersen' => $fakultasPersen->all(),
            'warnaFakultas' => ['FS' => '#4e79a7', 'FTI' => '#f28e2c', 'FTIK' => '#e15759'],
        ];
    }

    // ... method destroy, exportExcel, showDetail tetap sama seperti sebelumnya ...
    // (Hanya perlu menyalin ulang jika Anda belum menyimpannya)

    public function destroy($id)
    {
        $hasil = HasilKuesioner::find($id);
        if (!$hasil)
            return redirect()->route('admin.home')->with('error', 'Data tidak ditemukan.');

        $nim = $hasil->nim;
        DB::beginTransaction();
        try {
            $dataDiri = DataDiris::where('nim', $nim)->first();
            $user = Users::where('nim', $nim)->first();
            HasilKuesioner::where('nim', $nim)->delete();
            RiwayatKeluhans::where('nim', $nim)->delete();
            if ($dataDiri)
                $dataDiri->delete();
            if ($user)
                $user->delete();
            DB::commit();

            Cache::forget('mh.admin.user_stats');
            Cache::forget('mh.admin.kategori_counts');
            Cache::forget('mh.admin.total_tes');
            Cache::forget('mh.admin.fakultas_stats');
            Cache::forget('mh.admin.angkatan_stats'); // Clear cache angkatan
            Cache::forget("mh.user.{$nim}.test_history");

            return redirect()->route('admin.home')->with('success', 'Data mahasiswa NIM ' . $nim . ' berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.home')->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        $fileName = 'hasil-kuesioner-' . now()->setTimezone('Asia/Jakarta')->format('Y-m-d_H-i') . '.xlsx';
        return Excel::download(new HasilKuesionerExport($search, $kategori, $sort, $order), $fileName);
    }

    public function showDetail($id)
    {
        $hasil = HasilKuesioner::with([
            'dataDiri',
            'jawabanDetails' => function ($query) {
                $query->orderBy('nomor_soal');
            },
            'riwayatKeluhans' => function ($query) {
                $query->latest()->limit(1);
            }
        ])->where('status', 'selesai')->findOrFail($id);

        $questions = [
            1 => 'Seberapa bahagia, puas, atau senangkah Anda dengan kehidupan pribadi Anda selama sebulan terakhir?',
            2 => 'Seberapa sering Anda merasa kesepian selama sebulan terakhir?',
            3 => 'Seberapa sering Anda merasa gugup atau gelisah ketika dihadapkan pada situasi yang menyenangkan atau tak terduga selama sebulan terakhir?',
            4 => 'Selama sebulan terakhir, seberapa sering Anda merasa bahwa masa depan terlihat penuh harapan dan menjanjikan?',
            5 => 'Berapa banyak waktu, selama sebulan terakhir, kehidupan sehari-hari Anda penuh dengan hal-hal yang menarik bagi Anda?',
            6 => 'Seberapa sering, selama sebulan terakhir, Anda merasa rileks dan bebas dari ketegangan?',
            7 => 'Selama sebulan terakhir, berapa banyak waktu yang Anda habiskan untuk menikmati hal-hal yang Anda lakukan?',
            8 => 'Selama sebulan terakhir, pernahkah Anda merasa kehilangan akal sehat, atau kehilangan kendali atas cara Anda bertindak, berbicara, berpikir, merasakan, atau ingatan Anda?',
            9 => 'Apakah Anda merasa tertekan selama sebulan terakhir?',
            10 => 'Selama sebulan terakhir, berapa banyak waktu yang Anda gunakan untuk merasa dicintai dan diinginkan?',
            11 => 'Seberapa sering, selama sebulan terakhir, Anda menjadi orang yang sangat gugup?',
            12 => 'Ketika Anda bangun di pagi hari, dalam sebulan terakhir ini, kira-kira seberapa sering Anda berharap untuk mendapatkan hari yang menarik?',
            13 => 'Seberapa sering Anda merasa tegang atau sangat gelisah?',
            14 => 'Selama sebulan terakhir, apakah Anda memegang kendali penuh atas perilaku, pikiran, emosi, atau perasaan Anda?',
            15 => 'Selama sebulan terakhir, seberapa sering tangan Anda bergetar ketika mencoba melakukan sesuatu?',
            16 => 'Selama sebulan terakhir, seberapa sering Anda merasa tidak memiliki sesuatu yang dinantikan?',
            17 => 'Seberapa sering, selama sebulan terakhir, Anda merasa tenang dan damai?',
            18 => 'Seberapa sering, selama sebulan terakhir, Anda merasa stabil secara emosional?',
            19 => 'Seberapa sering, selama sebulan terakhir, Anda merasa murung?',
            20 => 'Seberapa sering Anda merasa ingin menangis, selama sebulan terakhir?',
            21 => 'Selama sebulan terakhir, seberapa sering Anda merasa bahwa orang lain akan lebih baik jika Anda mati?',
            22 => 'Berapa banyak waktu, selama sebulan terakhir, Anda dapat bersantai tanpa kesulitan?',
            23 => 'Seberapa sering, selama sebulan terakhir, Anda merasa bahwa hubungan cinta Anda, mencintai dan dicintai, terasa utuh dan lengkap?',
            24 => 'Seberapa sering, selama sebulan terakhir, Anda merasa bahwa tidak ada yang berjalan sesuai dengan yang Anda inginkan?',
            25 => 'Seberapa sering Anda merasa terganggu oleh rasa gugup, atau "kegelisahan" Anda, selama sebulan terakhir?',
            26 => 'Selama sebulan terakhir, berapa banyak waktu yang Anda gunakan untuk menjalani petualangan yang luar biasa bagi Anda?',
            27 => 'Seberapa sering, selama sebulan terakhir, Anda merasa sangat terpuruk sehingga tidak ada yang dapat menghibur Anda?',
            28 => 'Selama sebulan terakhir, apakah Anda berpikir untuk bunuh diri?',
            29 => 'Selama sebulan terakhir, berapa kali Anda merasa gelisah, resah, atau tidak sabar?',
            30 => 'Selama sebulan terakhir, berapa banyak waktu yang Anda habiskan untuk murung atau merenung tentang berbagai hal?',
            31 => 'Seberapa sering, selama sebulan terakhir, Anda merasa ceria dan gembira?',
            32 => 'Selama sebulan terakhir, seberapa sering Anda merasa gelisah, kesal, atau bingung?',
            33 => 'Selama sebulan terakhir, apakah Anda pernah merasa cemas atau khawatir?',
            34 => 'Selama sebulan terakhir, berapa banyak waktu yang Anda habiskan untuk menjadi orang yang bahagia?',
            35 => 'Seberapa sering selama sebulan terakhir Anda merasa perlu menenangkan diri?',
            36 => 'Selama sebulan terakhir, seberapa sering Anda merasa sedih atau sangat terpuruk?',
            37 => 'Seberapa sering, selama sebulan terakhir, Anda bangun tidur dengan perasaan segar dan beristirahat?',
            38 => 'Selama sebulan terakhir, apakah Anda pernah mengalami atau merasa berada di bawah tekanan, stres, atau tekanan?'
        ];
        $negativeQuestions = [2, 3, 8, 9, 11, 13, 14, 15, 16, 18, 19, 20, 21, 24, 25, 27, 28, 29, 30, 32, 33, 35, 36, 38];

        return view('admin-mental-health-detail', [
            'title' => 'Detail Jawaban - ' . ($hasil->dataDiri->nama ?? $hasil->nim),
            'hasil' => $hasil,
            'jawabanDetails' => $hasil->jawabanDetails,
            'questions' => $questions,
            'negativeQuestions' => $negativeQuestions
        ]);
    }
}