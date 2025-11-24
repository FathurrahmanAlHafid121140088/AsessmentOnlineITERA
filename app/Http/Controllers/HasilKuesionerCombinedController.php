<?php

namespace App\Http\Controllers;

use App\Exports\HasilKuesionerExport; // 1. Tambahkan use statement untuk class Export
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // ⚡ CACHING: Import Cache facade
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use App\Models\Users; // <-- Pastikan ini ada
use App\Models\MentalHealthJawabanDetail;
use Illuminate\Database\Eloquent\Builder;

class HasilKuesionerCombinedController extends Controller
{
    /**
     * Fungsi utama untuk menampilkan dashboard admin (VERSI OPTIMAL)
     * - Semua logika dikembalikan ke dalam satu fungsi index tanpa mengubah nama fungsi.
     * - Tetap mempertahankan performa tinggi dengan jumlah query minimal.
     */
    public function index(Request $request)
    {
        // 1. Ambil parameter dari request
        $limit = $request->input('limit', 10);
        $search = $request->input('search');
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        $kategori = $request->input('kategori');

        // 2. Subquery untuk mendapatkan ID hasil kuesioner terakhir per mahasiswa (Dibuat sekali, dipakai ulang)
        $latestIds = DB::table('hasil_kuesioners')
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('nim');

        // 3. Query Utama (Digabung dengan data_diris untuk sorting & search)
        // ⚡ OPTIMASI: Ganti correlated subquery dengan LEFT JOIN + COUNT untuk avoid N+1
        $query = HasilKuesioner::query()
            ->joinSub($latestIds, 'latest', 'hasil_kuesioners.id', '=', 'latest.id')
            ->join('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
            // ⚡ LEFT JOIN untuk count jumlah tes per mahasiswa (menggantikan subquery)
            ->leftJoin('hasil_kuesioners as hk_count', 'data_diris.nim', '=', 'hk_count.nim')
            ->select('hasil_kuesioners.*', 'data_diris.nama as nama_mahasiswa')
            // ⚡ COUNT dengan GROUP BY (1 query, bukan N queries!)
            ->selectRaw('COUNT(hk_count.id) as jumlah_tes')
            ->groupBy(
                'hasil_kuesioners.id',
                'hasil_kuesioners.nim',
                'hasil_kuesioners.total_skor',
                'hasil_kuesioners.kategori',
                'hasil_kuesioners.tanggal_pengerjaan',
                'hasil_kuesioners.created_at',
                'hasil_kuesioners.updated_at',
                'data_diris.nama'
            );

        // 4. Terapkan Filter & Pencarian secara langsung
        $query
            ->when($kategori, function ($q) use ($kategori) {
                $q->where('hasil_kuesioners.kategori', $kategori);
            })
            // ✅ BLOK PENCARIAN YANG DIOPTIMALKAN
            ->when($search, function ($q) use ($search) {
                $terms = array_filter(preg_split('/\s+/', trim($search)));

                // Definisikan kolom-kolom untuk pencarian umum menggunakan 'like'
                $likeColumns = [
                    'hasil_kuesioners.nim',
                    'data_diris.nama',
                    'data_diris.email',
                    'data_diris.alamat',
                    'data_diris.asal_sekolah',
                    'data_diris.status_tinggal',
                ];

                // Definisikan aturan pencarian khusus untuk pencocokan eksak
                $specialRules = [
                    'fakultas' => [
                        'values' => ['fs', 'fti', 'ftik'],
                        'transform' => 'strtoupper', // contoh: fti -> FTI
                    ],
                    'provinsi' => [
                        'values' => ['papua', 'riau'],
                        'transform' => 'ucfirst', // contoh: papua -> Papua
                    ],
                    'program_studi' => [
                        'values' => ['fisika', 'arsitektur', 'kimia'],
                        'transform' => null, // tidak ada transformasi
                    ],
                    'jenis_kelamin' => [
                        'values' => ['l', 'p'],
                        'transform' => 'strtoupper', // contoh: l -> L
                    ],
                ];

                $q->where(function (Builder $query) use ($terms, $likeColumns, $specialRules) {
                    foreach ($terms as $term) {
                        $query->where(function (Builder $subQuery) use ($term, $likeColumns, $specialRules) {
                            // 1. Terapkan pencarian 'like' pada kolom-kolom umum
                            foreach ($likeColumns as $column) {
                                $subQuery->orWhere($column, 'like', "%$term%");
                            }

                            // 2. Terapkan pencarian berdasarkan aturan khusus
                            foreach ($specialRules as $columnName => $rule) {
                                $dbColumn = 'data_diris.' . $columnName;
                                $lowerTerm = strtolower($term);

                                if (in_array($lowerTerm, $rule['values'])) {
                                    // Jika cocok aturan, gunakan pencocokan eksak dengan transformasi
                                    $transformedTerm = $rule['transform'] ? $rule['transform']($term) : $term;
                                    $subQuery->orWhere($dbColumn, $transformedTerm);
                                } else {
                                    // Jika tidak, tetap cari dengan 'like' di kolom ini
                                    $subQuery->orWhere($dbColumn, 'like', "%$term%");
                                }
                            }
                        });
                    }
                });
            });


        // 5. Terapkan Sorting (semua di level DB)
        $sortColumn = match ($sort) {
            'nama' => 'data_diris.nama',
            default => 'hasil_kuesioners.' . $sort,
        };
        $query->orderBy($sortColumn, $order);

        // 6. Ambil data dengan Pagination (Hanya 1 query utama untuk data tabel)
        $hasilKuesioners = $query->paginate($limit)->withQueryString();

        // ✅ PERUBAHAN: Tambahkan status pesan pencarian (hanya untuk request awal, bukan paginasi)
        $searchMessage = null;
        // Pesan hanya akan muncul jika ada parameter 'search' DAN BUKAN dari klik paginasi (selain halaman 1)
        if ($search && !$request->has('page')) {
            if ($hasilKuesioners->total() > 0) {
                $searchMessage = 'Data berhasil ditemukan!';
            } else {
                $searchMessage = 'Data tidak ditemukan!';
            }
        }
        // 7. Ambil semua statistik global dengan query seminimal mungkin
        // ⚡ CACHING: Cache global statistics for 1 minute (frequent updates)
        $userStats = Cache::remember('mh.admin.user_stats', 60, function () {
            return DB::table('data_diris')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('hasil_kuesioners')
                        ->whereColumn('hasil_kuesioners.nim', 'data_diris.nim');
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
        });

        // Use the total_users from query instead of separate count
        $totalUsers = $userStats->total_users ?? 0;

        // ⚡ CACHING: Cache kategori counts for 1 minute
        $kategoriCounts = Cache::remember('mh.admin.kategori_counts', 60, function () use ($latestIds) {
            return HasilKuesioner::whereIn('id', $latestIds)
                ->selectRaw('kategori, COUNT(*) as jumlah')
                ->groupBy('kategori')
                ->pluck('jumlah', 'kategori')
                ->all();
        });

        // ⚡ CACHING: Cache total tests count for 1 minute
        $totalTes = Cache::remember('mh.admin.total_tes', 60, function () {
            return HasilKuesioner::count();
        });
        $totalLaki = $userStats->total_laki ?? 0;
        $totalPerempuan = $userStats->total_perempuan ?? 0;
        $asalCounts = [
            'SMA' => $userStats->total_sma ?? 0,
            'SMK' => $userStats->total_smk ?? 0,
            'Boarding School' => $userStats->total_boarding ?? 0,
        ];

        // 8. Siapkan data untuk Donut Chart
        $totalAsal = array_sum($asalCounts);
        $r = 60;
        $circ = 2 * M_PI * $r;
        $segments = [];
        $offset = 0;
        $pct = fn($n) => $totalAsal > 0 ? round(($n / $totalAsal) * 100, 1) : 0;

        foreach ($asalCounts as $label => $val) {
            $p = $totalAsal > 0 ? $val / $totalAsal : 0;
            $dash = $circ * $p;
            $segments[] = ['label' => $label, 'value' => $val, 'percent' => $pct($val), 'dash' => $dash, 'offset' => $offset];
            $offset += $dash;
        }

        // 9. Kirim data ke view
        return view('admin-home', [
            'title' => 'Dashboard Mental Health',
            'hasilKuesioners' => $hasilKuesioners,
            'limit' => $limit,
            'kategoriCounts' => $kategoriCounts,
            'totalUsers' => $totalUsers,
            'totalTes' => $totalTes,
            'totalLaki' => $totalLaki,
            'totalPerempuan' => $totalPerempuan,
            'asalCounts' => $asalCounts,
            'totalAsal' => $totalAsal,
            'segments' => $segments,
            'radius' => $r,
            'circumference' => $circ,
            'searchMessage' => $searchMessage, // ✅ kirim ke blade
        ] + $this->getStatistikFakultas());
    }

    /**
     * Ambil statistik mahasiswa per fakultas
     * ⚡ CACHING: Cached for 1 minute (frequent updates)
     */
    private function getStatistikFakultas()
    {
        return Cache::remember('mh.admin.fakultas_stats', 60, function () {
            $fakultasCount = DataDiris::select('data_diris.fakultas', DB::raw('COUNT(DISTINCT data_diris.nim) as total'))
                ->join('hasil_kuesioners', 'data_diris.nim', '=', 'hasil_kuesioners.nim')
                ->whereNotNull('data_diris.fakultas')
                ->groupBy('data_diris.fakultas')
                ->pluck('total', 'data_diris.fakultas');

            $totalFakultas = $fakultasCount->sum();
            $fakultasPersen = $fakultasCount->map(fn($count) => $totalFakultas > 0 ? round(($count / $totalFakultas) * 100, 1) : 0);

            return [
                'fakultasCount' => $fakultasCount->all(), // FIX: Convert Collection to Array
                'fakultasPersen' => $fakultasPersen->all(), // FIX: Convert Collection to Array
                'warnaFakultas' => ['FS' => '#4e79a7', 'FTI' => '#f28e2c', 'FTIK' => '#e15759'],
            ];
        });
    }

    /**
     * Hapus data hasil kuesioner berdasarkan ID (Sudah efisien)
     */
    public function destroy($id)
    {
        // 1. Temukan hasil kuesioner berdasarkan ID yang dikirim dari tombol hapus.
        $hasil = HasilKuesioner::find($id);

        if (!$hasil) {
            return redirect()->route('admin.home')->with('error', 'Data tidak ditemukan.');
        }

        // 2. Dapatkan NIM dari hasil kuesioner tersebut.
        $nim = $hasil->nim;

        // --- PERBAIKAN DI SINI ---
        DB::beginTransaction(); // Gunakan transaksi untuk keamanan
        try {
            // 3. Temukan data diri mahasiswa berdasarkan NIM (gunakan where).
            $dataDiri = DataDiris::where('nim', $nim)->first();

            // 4. Temukan data user mahasiswa berdasarkan NIM (gunakan where).
            $user = Users::where('nim', $nim)->first();

            // 5. Hapus semua data terkait (Hasil, Riwayat otomatis jika ada cascade, DataDiri, User)
            // Hapus Hasil Kuesioner (jika tidak ada cascade)
            HasilKuesioner::where('nim', $nim)->delete();
            // Hapus Riwayat Keluhan (jika tidak ada cascade)
            \App\Models\RiwayatKeluhans::where('nim', $nim)->delete(); // Pastikan namespace benar

            // Hapus Data Diri jika ada
            if ($dataDiri) {
                $dataDiri->delete();
            }

            // Hapus User jika ada
            if ($user) {
                $user->delete();
            }

            DB::commit(); // Konfirmasi semua penghapusan

            // ⚡ CACHING: Invalidate all cached statistics after deletion
            Cache::forget('mh.admin.user_stats');
            Cache::forget('mh.admin.kategori_counts');
            Cache::forget('mh.admin.total_tes');
            Cache::forget('mh.admin.fakultas_stats');
            // ⚡ CACHING: Invalidate user dashboard cache
            Cache::forget("mh.user.{$nim}.test_history");

            return redirect()->route('admin.home')->with('success', 'Seluruh data mahasiswa dengan NIM ' . $nim . ' berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika ada error
            return redirect()->route('admin.home')->with('error', 'Gagal menghapus data mahasiswa: ' . $e->getMessage());
        }
    } // <-- Kurung kurawal penutup untuk destroy()

    /**
     * OPTIMASI: Tampilkan persentase mahasiswa yang tinggal di kost (2 query menjadi 1)
     */
    public function showGauge()
    {
        $stats = DataDiris::selectRaw("
            COUNT(*) as total,
            COUNT(CASE WHEN status_tinggal = 'Kost' THEN 1 END) as kost_count
        ")->first();

        $kostPercent = $stats->total ? round(($stats->kost_count / $stats->total) * 100, 2) : 0;
        return view('admin-home', compact('kostPercent'));
    }

    /**
     * Ekspor data ke file Excel.
     */
    public function exportExcel(Request $request)
    {
        // Ambil semua parameter filter dan sort dari request saat ini
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');

        // --- PERBAIKAN: Gunakan setTimezone('Asia/Jakarta') ---
        $fileName = 'hasil-kuesioner-' . now()->setTimezone('Asia/Jakarta')->format('Y-m-d_H-i') . '.xlsx';
        // --- AKHIR PERBAIKAN ---

        // Panggil class export dengan parameter yang relevan dan trigger unduhan
        return Excel::download(new HasilKuesionerExport($search, $kategori, $sort, $order), $fileName);
    }

    /**
     * Tampilkan detail jawaban per pertanyaan untuk satu hasil kuesioner
     */
    public function showDetail($id)
    {
        $hasil = HasilKuesioner::with(['dataDiri', 'jawabanDetails' => function($query) {
            $query->orderBy('nomor_soal');
        }, 'riwayatKeluhans' => function($query) {
            $query->latest()->limit(1);
        }])->findOrFail($id);

        // Daftar pertanyaan lengkap (38 pertanyaan) - Sama persis dengan kuesioner
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

        // Pertanyaan berdasarkan MHI-38 asli
        // Psychological Distress (24 items) - Negatif
        $negativeQuestions = [2, 3, 8, 9, 11, 13, 14, 15, 16, 18, 19, 20, 21, 24, 25, 27, 28, 29, 30, 32, 33, 35, 36, 38];

        // Psychological Well-being (14 items) - Positif
        // Items: 1, 4, 5, 6, 7, 10, 12, 17, 22, 23, 26, 31, 34, 37

        return view('admin-mental-health-detail', [
            'title' => 'Detail Jawaban Kuesioner - ' . $hasil->dataDiri->nama,
            'hasil' => $hasil,
            'jawabanDetails' => $hasil->jawabanDetails, // Tambahkan ini
            'questions' => $questions,
            'negativeQuestions' => $negativeQuestions
        ]);
    }
}

