<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
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
        $query = HasilKuesioner::query()
            ->joinSub($latestIds, 'latest', 'hasil_kuesioners.id', '=', 'latest.id')
            ->join('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
            ->select('hasil_kuesioners.*', 'data_diris.nama as nama_mahasiswa');

        // 4. Terapkan Filter & Pencarian secara langsung
        $query
            ->when($kategori, function ($q) use ($kategori) {
                $q->where('hasil_kuesioners.kategori', $kategori);
            })
            ->when($search, function ($q) use ($search) {
                $terms = array_filter(preg_split('/\s+/', trim($search)));
                $q->where(function (Builder $query) use ($terms) {
                    foreach ($terms as $term) {
                        // Setiap term harus ditemukan, jadi kita gunakan `where` untuk membuat klausa AND
                        $query->where(function (Builder $subQuery) use ($term) {
                            // Sebuah term bisa cocok di salah satu kolom ini, jadi kita gunakan `orWhere`
                            $subQuery->orWhere('hasil_kuesioners.nim', 'like', "%$term%")
                                ->orWhere('data_diris.nama', 'like', "%$term%")
                                ->orWhere('data_diris.email', 'like', "%$term%")
                                ->orWhere('data_diris.alamat', 'like', "%$term%")
                                ->orWhere('data_diris.asal_sekolah', 'like', "%$term%")
                                ->orWhere('data_diris.status_tinggal', 'like', "%$term%");

                            // Logika pencarian khusus untuk fakultas (exact match)
                            if (in_array(strtolower($term), ['fs', 'fti', 'ftik'])) {
                                $subQuery->orWhere('data_diris.fakultas', strtoupper($term));
                            } else {
                                $subQuery->orWhere('data_diris.fakultas', 'like', "%$term%");
                            }

                            // Logika pencarian khusus untuk provinsi (exact match)
                            if (in_array(strtolower($term), ['papua'])) {
                                $subQuery->orWhere('data_diris.provinsi', strtoupper($term));
                            } else {
                                $subQuery->orWhere('data_diris.provinsi', 'like', "%$term%");
                            }

                            // Logika pencarian khusus untuk program studi (exact match)
                            if (in_array(strtolower($term), ['fisika', 'arsitektur', 'kimia'])) {
                                $subQuery->orWhere('data_diris.program_studi', $term);
                            } else {
                                $subQuery->orWhere('data_diris.program_studi', 'like', "%$term%");
                            }

                            // Logika pencarian khusus untuk jenis kelamin (exact match)
                            if (in_array(strtolower($term), ['l', 'p'])) {
                                $subQuery->orWhere('data_diris.jenis_kelamin', strtoupper($term));
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

        // ✅ Tambahkan status pesan pencarian
        $searchMessage = null;
        if ($search) {
            if ($hasilKuesioners->total() > 0) {
                $searchMessage = 'Data berhasil ditemukan!';
            } else {
                $searchMessage = 'Data tidak ditemukan!';
            }
        }
        // 7. Ambil semua statistik global dengan query seminimal mungkin
        // Ambil NIM unik dari mahasiswa yang pernah mengisi (1 query)
        $nimDenganHasil = HasilKuesioner::distinct()->pluck('nim');

        // Ambil statistik gender dan asal sekolah dalam SATU query
        $userStats = DataDiris::whereIn('nim', $nimDenganHasil)
            ->selectRaw("
                COUNT(CASE WHEN jenis_kelamin = 'L' THEN 1 END) as total_laki,
                COUNT(CASE WHEN jenis_kelamin = 'P' THEN 1 END) as total_perempuan,
                COUNT(CASE WHEN asal_sekolah = 'SMA' THEN 1 END) as total_sma,
                COUNT(CASE WHEN asal_sekolah = 'SMK' THEN 1 END) as total_smk,
                COUNT(CASE WHEN asal_sekolah = 'Boarding School' THEN 1 END) as total_boarding
            ")->first();

        // OPTIMASI: Gunakan ulang query builder $latestIds, tidak perlu query baru
        $kategoriCounts = HasilKuesioner::whereIn('id', $latestIds)
            ->selectRaw('kategori, COUNT(*) as jumlah')
            ->groupBy('kategori')
            ->pluck('jumlah', 'kategori')
            ->all(); // FIX: Convert Collection to Array

        $totalUsers = $nimDenganHasil->count();
        $totalTes = HasilKuesioner::count();
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
     * Ambil statistik mahasiswa per fakultas (Sudah cukup efisien)
     */
    private function getStatistikFakultas()
    {
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

        // 3. Temukan data diri mahasiswa berdasarkan NIM.
        $mahasiswa = DataDiris::find($nim);

        // 4. Jika data mahasiswa ditemukan, hapus data tersebut.
        // Ini akan otomatis menghapus semua riwayat dan hasil kuesioner
        // jika 'cascade on delete' sudah diatur di migration.
        if ($mahasiswa) {
            $mahasiswa->delete();
            return redirect()->route('admin.home')->with('success', 'Seluruh data mahasiswa dengan NIM ' . $nim . ' berhasil dihapus.');
        }

        // Fallback: Jika karena suatu alasan data diri tidak ditemukan tapi hasil kuesioner ada,
        // hapus saja hasil kuesioner tunggal ini untuk membersihkan data 'yatim'.
        $hasil->delete();
        return redirect()->route('admin.dashboard') // atau ke route yang sesuai
            ->with('success', 'Data berhasil dihapus!');
    }

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
}

