<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KarirDataDiri;
use App\Models\RmibHasilTes;
use App\Models\RmibJawabanPeserta;
use App\Services\RmibScoringService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\RmibPekerjaan;
use Carbon\Carbon;
use App\Http\Requests\StoreRmibJawabanRequest;


class KarirController extends Controller
{
    // ========================
    // USER SIDE
    // ========================

    // 0. Dashboard User Peminatan Karir
    public function userDashboard()
    {
        $user = Auth::user();

        // Ambil NIM dari email
        preg_match('/\d{9}/', $user->email, $matches);
        $nim = $matches[0] ?? null;

        // Cari data diri berdasarkan NIM
        $dataDiri = KarirDataDiri::where('nim', $nim)->first();

        if (!$dataDiri) {
            // Jika belum ada data diri, set semua ke 0
            return view('user-peminatan-karir', [
                'title' => 'Dashboard Peminatan Karir',
                'jumlahTesDiikuti' => 0,
                'jumlahTesSelesai' => 0,
                'kategoriTerakhir' => 'Belum Ada Data',
                'riwayatTes' => collect(),
                'radarData' => [],
                'radarLabels' => []
            ]);
        }

        // Ambil semua hasil tes user
        $hasilTesList = RmibHasilTes::where('karir_data_diri_id', $dataDiri->id)
            ->orderBy('tanggal_pengerjaan', 'desc')
            ->get();

        // Hitung statistik
        $jumlahTesDiikuti = $hasilTesList->count();
        $jumlahTesSelesai = $hasilTesList->where('top_1_pekerjaan', '!=', null)->count();

        // Ambil hasil tes terakhir untuk kategori dan radar chart
        $hasilTerakhir = $hasilTesList->first();
        $kategoriTerakhir = 'Belum Ada Data';
        $radarData = [];
        $radarLabels = [];

        if ($hasilTerakhir) {
            $kategoriTerakhir = $hasilTerakhir->top_1_pekerjaan ?? 'Belum Ada Data';

            // Hitung skor untuk radar chart
            $scoringService = new RmibScoringService();
            $hasilPerhitungan = $scoringService->hitungSemuaSkor($hasilTerakhir->id, $dataDiri->jenis_kelamin);

            // Prepare data untuk radar chart (12 kategori)
            $deskripsiKategori = $scoringService->getDeskripsiKategori();
            foreach ($hasilPerhitungan['skor_kategori'] as $kategori => $skor) {
                $radarLabels[] = $deskripsiKategori[$kategori]['singkatan'];
                $radarData[] = $skor;
            }
        }

        // Format riwayat tes untuk tabel
        $riwayatTes = $hasilTesList->map(function ($hasil) use ($dataDiri) {
            return [
                'id' => $hasil->id,
                'created_at' => $hasil->tanggal_pengerjaan,
                'nim' => $dataDiri->nim,
                'nama' => $dataDiri->nama,
                'program_studi' => $dataDiri->program_studi,
                'top_1' => $hasil->top_1_pekerjaan,
                'top_2' => $hasil->top_2_pekerjaan,
                'top_3' => $hasil->top_3_pekerjaan,
            ];
        });

        return view('user-peminatan-karir', [
            'title' => 'Dashboard Peminatan Karir',
            'jumlahTesDiikuti' => $jumlahTesDiikuti,
            'jumlahTesSelesai' => $jumlahTesSelesai,
            'kategoriTerakhir' => $kategoriTerakhir,
            'riwayatTes' => $riwayatTes,
            'radarData' => $radarData,
            'radarLabels' => $radarLabels
        ]);
    }

    // 1. Form Data Diri
// app/Http/Controllers/KarirController.php

public function showDataDiri()
{
    // 1. Ambil email user yang login
    $email = Auth::user()->email;
    
    // 2. Gunakan regex untuk mencari 9 digit NIM
    preg_match('/\d{9}/', $email, $matches);
    
    // 3. Ambil hasil pencarian, jika tidak ada, beri nilai kosong
    $nim = $matches[0] ?? '';

    // 4. Kirim HANYA variabel $nim ke view
    return view('karir-datadiri', ['nim' => $nim]);
}

    // 2. Simpan Data Diri
public function storeDataDiri(Request $request)
    {
        // 1. Validasi semua input form
        $validatedData = $request->validate([
            'nim'                    => 'nullable|string|size:9', // Validasi NIM jika dikirim
            'nama'                   => 'required|string|max:255',
            'program_studi'          => 'required|string|max:255',
            'jenis_kelamin'          => 'required|in:L,P', // Harus L atau P
            'provinsi'               => 'required|string|max:255',
            'alamat'                 => 'required|string',
            'usia'                   => 'required|integer|min:1|regex:/^[0-9]+$/', // Hanya angka, tidak boleh e, koma, dll
            'fakultas'               => 'required|string|max:255',
            'email'                  => 'required|email|max:255',
            'asal_sekolah'           => 'required|string|max:255',
            'status_tinggal'         => 'required|string|max:255',
            'prodi_sesuai_keinginan' => 'required|in:Ya,Tidak',
        ]);

        // 2. Ambil email dari pengguna dan ekstrak 9 digit NIM
        $email = Auth::user()->email;
        preg_match('/\d{9}/', $email, $matches);
        $nim = $matches[0] ?? null;

        if (!$nim) {
            return back()->withErrors(['nim' => 'NIM tidak dapat ditemukan dari email Anda.']);
        }

        // 3. Gunakan updateOrCreate (sesuai patokan DataDirisController)
        // Mencari berdasarkan NIM, lalu update atau buat baru.
        $dataDiri = KarirDataDiri::updateOrCreate(
            ['nim' => $nim],
            $validatedData
        );

        // 4. Arahkan ke halaman tes
        return redirect()->route('karir.tes.form', $dataDiri->id);
    }
    // app/Http/Controllers/KarirController.php

/**
 * Menampilkan halaman form tes RMIB.
 * Menerima data diri peserta melalui Route Model Binding.
 */
public function showTesForm(KarirDataDiri $data_diri)
{
    // dd($data_diri); // Pastikan tidak ada dd() di sini lagi
    $gender = $data_diri->jenis_kelamin;

    $pekerjaanDb = RmibPekerjaan::where('gender', $gender)
                                ->orderBy('kelompok')
                                ->orderBy('id') // PENTING! Urutan sesuai circular shift di seeder
                                ->get();

    $pekerjaanData = $pekerjaanDb->groupBy('kelompok')
                                ->map(function ($kelompok) {
                                    return $kelompok->pluck('nama_pekerjaan');
                                })
                                ->values()
                                ->toArray();

    return view('karir-form', [
        'dataDiri'             => $data_diri,
        'gender'               => $gender,
        'pekerjaanPerKelompok' => $pekerjaanData, // Kirim data yang sudah dikelompokkan
        'clusters'             => $pekerjaanData, // Alias untuk testing
        'semuaPekerjaan'       => $pekerjaanDb->pluck('nama_pekerjaan') // Kirim daftar flat
    ]);
}

    public function form($id)
    {
        $dataDiri = KarirDataDiri::findOrFail($id);

        // Tentukan gender & file JSON
        $gender = strtolower($dataDiri->jenis_kelamin) === 'laki-laki' ? 'pria' : 'wanita';
        $jsonPath = public_path("data/pekerjaan_{$gender}.json");

        if (!file_exists($jsonPath)) {
            abort(404, "File pekerjaan untuk gender {$gender} tidak ditemukan.");
        }

        $pekerjaan = json_decode(file_get_contents($jsonPath), true);

        return view('karir-form', [
            'dataDiri'  => $dataDiri,
            'gender'    => $gender,
            'pekerjaan' => $pekerjaan
        ]);
    }

    // 4. Simpan Jawaban RMIB
public function storeJawaban(StoreRmibJawabanRequest $request, KarirDataDiri $data_diri) {
    // Validasi sudah dilakukan di StoreRmibJawabanRequest
    // Data sudah di-sanitize di prepareForValidation()

    // Log successful validation
    Log::info('RMIB submission validated successfully', [
        'user_id' => auth()->id(),
        'user_email' => auth()->user()->email,
        'data_diri_id' => $data_diri->id,
        'ip_address' => $request->ip(),
        'timestamp' => now()->toDateTimeString(),
    ]);

    DB::beginTransaction();
    try {
        // 1. Buat record Hasil Tes awal (untuk mendapatkan ID)
        // Data sudah ter-sanitize dari prepareForValidation()
        $hasilTes = RmibHasilTes::create([
            'karir_data_diri_id' => $data_diri->id, // Foreign key yang benar
            'tanggal_pengerjaan' => Carbon::now(),
            'top_1_pekerjaan'    => $request->validated()['top1'],
            'top_2_pekerjaan'    => $request->validated()['top2'],
            'top_3_pekerjaan'    => $request->validated()['top3'],
            'pekerjaan_lain'     => $request->validated()['pekerjaan_lain'] ?? null,
            // Skor & interpretasi akan di-update nanti
        ]);

        // 2. Siapkan array kosong untuk menampung semua jawaban
        $jawabanToInsert = [];
        $now = Carbon::now(); // Waktu saat ini untuk timestamp

        // 3. Loop data dari request untuk membangun array
        // Data sudah divalidasi, jadi tidak perlu validasi ulang
        $validatedData = $request->validated();

        foreach ($validatedData['jawaban'] as $kelompok => $pekerjaanList) {
            foreach ($pekerjaanList as $pekerjaan => $peringkat) {
                // Data sudah pasti valid karena sudah melalui Form Request validation
                $jawabanToInsert[] = [
                    'hasil_id'  => $hasilTes->id,
                    'kelompok'  => $kelompok,
                    'pekerjaan' => $pekerjaan,
                    'peringkat' => $peringkat,
                    'created_at'=> $now,
                    'updated_at'=> $now,
                ];
            }
        }

        // 4. Lakukan SATU kali kueri INSERT untuk semua jawaban
        if (!empty($jawabanToInsert)) {
            RmibJawabanPeserta::insert($jawabanToInsert); // Ini adalah 'bulk insert'
        } else {
             // Jika tidak ada jawaban valid sama sekali, batalkan
             throw new \InvalidArgumentException("Tidak ada data jawaban valid yang diterima.");
        }

        // 5. Panggil service untuk hitung skor RMIB
        $scoringService = new \App\Services\RmibScoringService();
        $hasilPerhitungan = $scoringService->hitungSemuaSkor($hasilTes->id, $data_diri->jenis_kelamin);

        // Ambil top 3 kategori berdasarkan peringkat
        $skorKategori = $hasilPerhitungan['skor_kategori'];
        $peringkat = $hasilPerhitungan['peringkat'];

        // Urutkan kategori berdasarkan peringkat (ascending)
        asort($peringkat);
        $top3Kategori = array_slice(array_keys($peringkat), 0, 3, true);

        // Generate interpretasi
        $top3Data = [];
        foreach ($top3Kategori as $kategori) {
            $top3Data[$kategori] = $skorKategori[$kategori];
        }
        $interpretasi = $scoringService->generateInterpretasi($top3Data);

        // 6. Update hasil tes dengan interpretasi
        // CATATAN: top_1/2/3_pekerjaan TIDAK di-update karena sudah berisi nama pekerjaan pilihan user
        $hasilTes->update([
            'interpretasi' => $interpretasi,
        ]);

        DB::commit(); // Simpan semua perubahan ke database

        // Clear cache karena data baru sudah disimpan
        $this->clearKarirStatsCache();

        // Log successful submission
        Log::info('RMIB submission saved successfully', [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'hasil_tes_id' => $hasilTes->id,
            'data_diri_id' => $data_diri->id,
            'ip_address' => $request->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        // 6. Redirect ke halaman hasil
        return redirect()->route('karir.hasil', $hasilTes->id)
            ->with('success', 'Jawaban Anda berhasil disimpan!');

    } catch (\Exception $e) {
        DB::rollBack(); // Batalkan semua jika ada error

        // Log error dengan detail lengkap untuk debugging
        Log::error('Error saat menyimpan jawaban RMIB', [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'data_diri_id' => $data_diri->id,
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
            'ip_address' => $request->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        return back()
            ->withErrors(['msg' => 'Terjadi kesalahan sistem saat menyimpan jawaban. Silakan coba lagi.'])
            ->withInput();
    }
}

    // 5. Halaman Interpretasi User
    public function showInterpretasi($hasil_tes)
    {
        $hasil = RmibHasilTes::with('dataDiri')->findOrFail($hasil_tes);

        // Hitung ulang skor untuk mendapatkan peringkat lengkap
        $scoringService = new \App\Services\RmibScoringService();
        $hasilPerhitungan = $scoringService->hitungSemuaSkor($hasil->id, $hasil->dataDiri->jenis_kelamin);

        // Get deskripsi kategori
        $deskripsiKategori = $scoringService->getDeskripsiKategori();

        // Gabungkan skor dengan peringkat
        $hasilLengkap = [];
        foreach ($hasilPerhitungan['skor_kategori'] as $kategori => $skor) {
            $hasilLengkap[] = [
                'kategori' => $kategori,
                'singkatan' => $deskripsiKategori[$kategori]['singkatan'],
                'nama' => $deskripsiKategori[$kategori]['nama'],
                'deskripsi' => $deskripsiKategori[$kategori]['deskripsi'],
                'skor' => $skor,
                'peringkat' => $hasilPerhitungan['peringkat'][$kategori],
            ];
        }

        // Urutkan berdasarkan peringkat dengan tie-breaking
        usort($hasilLengkap, function($a, $b) {
            // 1. Prioritas utama: peringkat (ascending)
            $rankComparison = $a['peringkat'] <=> $b['peringkat'];
            if ($rankComparison !== 0) {
                return $rankComparison;
            }

            // 2. Tie-breaker pertama: skor (ascending - skor lebih kecil = lebih baik)
            $skorComparison = $a['skor'] <=> $b['skor'];
            if ($skorComparison !== 0) {
                return $skorComparison;
            }

            // 3. Tie-breaker kedua: alfabetis (deterministik)
            return strcmp($a['kategori'], $b['kategori']);
        });

        // Ambil top 3
        $top3 = array_slice($hasilLengkap, 0, 3);

        // Ambil pekerjaan yang sesuai dengan top 3 kategori
        $pekerjaanTop3 = [];
        foreach ($top3 as $kategoriData) {
            $pekerjaan = RmibPekerjaan::where('kategori', $kategoriData['kategori'])
                ->where('gender', $hasil->dataDiri->jenis_kelamin)
                ->pluck('nama_pekerjaan')
                ->toArray();

            $pekerjaanTop3[] = [
                'kategori' => $kategoriData['kategori'],
                'singkatan' => $kategoriData['singkatan'],
                'nama' => $kategoriData['nama'],
                'pekerjaan' => $pekerjaan
            ];
        }

        return view('karir-interpretasi', compact('hasil', 'hasilLengkap', 'top3', 'pekerjaanTop3'))
            ->with('hasilLengkap', array_merge(['interpretasi' => $hasil->interpretasi], $hasilLengkap));
    }

    // ========================
    // ADMIN SIDE
    // ========================

    // 1. Daftar semua peserta
    public function adminIndex()
    {
        // Ambil hasil tes terbaru per NIM menggunakan Window Function (Optimized)
        // Window function lebih efisien daripada GROUP BY + MAX untuk "get latest per group"
        $latestIds = DB::table(DB::raw('(
            SELECT id,
                   ROW_NUMBER() OVER (PARTITION BY karir_data_diri_id ORDER BY id DESC) as rn
            FROM rmib_hasil_tes
        ) as ranked'))
            ->where('rn', 1)
            ->pluck('id');

        // Query hasil tes dengan Eager Loading (Optimized - Fix N+1 Problem)
        $query = RmibHasilTes::with([
            'karirDataDiri:id,nim,nama,program_studi,fakultas,jenis_kelamin,email,usia,provinsi,alamat,asal_sekolah,status_tinggal,prodi_sesuai_keinginan',
            'karirDataDiri.hasilTes'
        ])
        ->whereIn('id', $latestIds);

        // Search functionality dengan sanitasi
        if (request('search')) {
            $search = request('search');

            // Sanitize search input
            $search = trim($search);
            $search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');
            $search = substr($search, 0, 100); // Limit length

            // Log search activity
            Log::info('Admin search in karir index', [
                'admin_id' => auth()->id(),
                'search_query' => $search,
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            $query->whereHas('karirDataDiri', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('program_studi', 'like', "%{$search}%")
                  ->orWhere('fakultas', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by program studi
        if (request('prodi')) {
            $prodi = request('prodi');
            $query->whereHas('karirDataDiri', function($q) use ($prodi) {
                $q->where('program_studi', $prodi);
            });
        }

        // Filter by provinsi
        if (request('provinsi')) {
            $provinsi = request('provinsi');
            $query->whereHas('karirDataDiri', function($q) use ($provinsi) {
                $q->where('provinsi', $provinsi);
            });
        }

        $hasilTes = $query->orderBy('tanggal_pengerjaan', 'desc')
            ->paginate(request('limit', 10))
            ->appends(request()->query());

        // Statistik dengan Caching (1 hour cache)
        $totalUsers = Cache::remember('karir_stats_total_users', 3600, function () {
            return KarirDataDiri::count();
        });

        $totalTes = Cache::remember('karir_stats_total_tes', 3600, function () {
            return RmibHasilTes::count();
        });

        $totalLaki = Cache::remember('karir_stats_total_laki', 3600, function () {
            return KarirDataDiri::where('jenis_kelamin', 'L')->count();
        });

        $totalPerempuan = Cache::remember('karir_stats_total_perempuan', 3600, function () {
            return KarirDataDiri::where('jenis_kelamin', 'P')->count();
        });

        // Additional statistics for tests
        $totalPeserta = $totalUsers; // Alias for totalUsers
        $totalProdi = Cache::remember('karir_stats_total_prodi', 3600, function () {
            return KarirDataDiri::distinct('program_studi')->count('program_studi');
        });
        $totalProvinsi = Cache::remember('karir_stats_total_provinsi', 3600, function () {
            return KarirDataDiri::distinct('provinsi')->count('provinsi');
        });

        // Hitung jumlah per kategori RMIB (top 1)
        $kategoriRMIB = [
            'Outdoor' => 'Outdoor (O)',
            'Mechanical' => 'Mechanical (M)',
            'Computational' => 'Computational (C)',
            'Scientific' => 'Scientific (S)',
            'Personal Contact' => 'Personal Contact (P)',
            'Aesthetic' => 'Aesthetic (A)',
            'Literary' => 'Literary (L)',
            'Musical' => 'Musical (Mu)',
            'Social Service' => 'Social Service (SS)',
            'Clerical' => 'Clerical (Cl)',
            'Practical' => 'Practical (Pr)',
            'Medical' => 'Medical (Me)',
        ];

        $kategoriCounts = Cache::remember('karir_stats_kategori_counts', 3600, function () use ($kategoriRMIB) {
            $counts = [];
            foreach (array_keys($kategoriRMIB) as $kategori) {
                $counts[$kategori] = RmibHasilTes::where('top_1_pekerjaan', $kategori)->count();
            }
            return $counts;
        });

        // Distribusi Fakultas dengan Caching
        $fakultasCount = Cache::remember('karir_stats_fakultas_count', 3600, function () {
            return KarirDataDiri::select('fakultas', DB::raw('count(*) as total'))
                ->groupBy('fakultas')
                ->pluck('total', 'fakultas')
                ->toArray();
        });

        $totalFakultas = array_sum($fakultasCount);
        $fakultasPersen = [];
        foreach ($fakultasCount as $fak => $count) {
            $fakultasPersen[$fak] = $totalFakultas > 0 ? round(($count / $totalFakultas) * 100, 1) : 0;
        }

        $warnaFakultas = [
            'FS' => '#4361ee',
            'FTIK' => '#f72585',
            'FTI' => '#4cc9f0',
        ];

        // Data untuk chart Status Tinggal dengan Caching
        $statusTinggalCounts = Cache::remember('karir_stats_status_tinggal', 3600, function () {
            return [
                'Kost' => KarirDataDiri::where('status_tinggal', 'Kost')->count(),
                'Bersama Orang Tua' => KarirDataDiri::where('status_tinggal', 'Bersama Orang Tua')->count(),
            ];
        });

        // Data untuk chart Prodi Sesuai Keinginan dengan Caching
        $prodiSesuaiCounts = Cache::remember('karir_stats_prodi_sesuai', 3600, function () {
            return [
                'Ya' => KarirDataDiri::where('prodi_sesuai_keinginan', 'Ya')->count(),
                'Tidak' => KarirDataDiri::where('prodi_sesuai_keinginan', 'Tidak')->count(),
            ];
        });

        // Data untuk chart Asal Sekolah dengan Caching
        $asalSekolahCounts = Cache::remember('karir_stats_asal_sekolah', 3600, function () {
            return [
                'SMA' => KarirDataDiri::where('asal_sekolah', 'SMA')->count(),
                'SMK' => KarirDataDiri::where('asal_sekolah', 'SMK')->count(),
                'Boarding School' => KarirDataDiri::where('asal_sekolah', 'Boarding School')->count(),
            ];
        });

        // Kirim ke view admin-karir.blade.php
        return view('admin-karir', compact(
            'hasilTes',
            'totalUsers',
            'totalTes',
            'totalLaki',
            'totalPerempuan',
            'totalPeserta',
            'totalProdi',
            'totalProvinsi',
            'kategoriCounts',
            'kategoriRMIB',
            'fakultasCount',
            'fakultasPersen',
            'warnaFakultas',
            'statusTinggalCounts',
            'prodiSesuaiCounts',
            'asalSekolahCounts'
        ));
    }

    // 2. Detail hasil peserta + perhitungan RMIB
    public function adminDetail($hasil_tes)
    {
        // Ambil hasil tes berdasarkan id_hasil dengan relasi ke data diri
        $hasil = RmibHasilTes::with('karirDataDiri')->findOrFail($hasil_tes);

        // Ambil gender peserta
        $gender = $hasil->karirDataDiri->jenis_kelamin;

        // Hitung skor lengkap menggunakan service RMIB
        $scoringService = new \App\Services\RmibScoringService();
        $hasilPerhitungan = $scoringService->hitungSemuaSkor($hasil->id, $gender);

        // Generate matrix 12x9
        $matrixData = $scoringService->generateMatrix($hasil->id, $gender);

        // Get deskripsi kategori untuk tampilan
        $deskripsiKategori = $scoringService->getDeskripsiKategori();

        // Gabungkan data untuk ditampilkan di view (urutan FIXED sesuai standar RMIB)
        $hasilLengkap = [];
        foreach ($matrixData['kategori_urutan'] as $kategori) {
            $hasilLengkap[] = [
                'kategori' => $kategori,
                'singkatan' => $deskripsiKategori[$kategori]['singkatan'],
                'matrix_row' => $matrixData['matrix'][$kategori], // Data kolom A-I
                'sum' => $matrixData['sum'][$kategori],
                'rank' => $matrixData['rank'][$kategori],
                'percentage' => $matrixData['percentage'][$kategori],
            ];
        }

        // Buat salinan untuk diurutkan (untuk mendapatkan top 3)
        $hasilSorted = $hasilLengkap;
        usort($hasilSorted, function ($a, $b) {
            return $a['rank'] <=> $b['rank'];
        });

        // Ambil top 3 dari hasil yang sudah diurutkan
        $top3 = array_slice($hasilSorted, 0, 3);

        // Ambil kategori untuk top 1, 2, 3 pekerjaan yang dipilih user
        // Handle kedua kasus: data baru (nama pekerjaan) dan data lama (kategori saja)
        $top1Data = $this->parseTopPekerjaan($hasil->top_1_pekerjaan, $gender);
        $top2Data = $this->parseTopPekerjaan($hasil->top_2_pekerjaan, $gender);
        $top3Data = $this->parseTopPekerjaan($hasil->top_3_pekerjaan, $gender);

        $top1Pekerjaan = $top1Data['pekerjaan'];
        $top1Kategori = $top1Data['kategori'];

        $top2Pekerjaan = $top2Data['pekerjaan'];
        $top2Kategori = $top2Data['kategori'];

        $top3Pekerjaan = $top3Data['pekerjaan'];
        $top3Kategori = $top3Data['kategori'];

        // Kirim ke view karir-detail-hasil.blade.php
        return view('karir-detail-hasil', compact(
            'hasil',
            'hasilLengkap',
            'top3',
            'matrixData',
            'top1Pekerjaan',
            'top1Kategori',
            'top2Pekerjaan',
            'top2Kategori',
            'top3Pekerjaan',
            'top3Kategori'
        ))->with([
            'matrix' => $matrixData['matrix'],
            'sum' => $matrixData['sum'],
            'rank' => $matrixData['rank']
        ]);
    }

    /**
     * Helper function untuk parse top pekerjaan
     * Handle kedua kasus: nama pekerjaan atau kategori
     *
     * @param string|null $value
     * @param string $gender
     * @return array ['pekerjaan' => string|null, 'kategori' => string|null]
     */
    private function parseTopPekerjaan($value, $gender)
    {
        if (!$value) {
            return ['pekerjaan' => null, 'kategori' => null];
        }

        // Cek apakah value adalah nama pekerjaan (cari di database)
        $pekerjaan = RmibPekerjaan::where('gender', $gender)
            ->where('nama_pekerjaan', $value)
            ->first();

        if ($pekerjaan) {
            // Value adalah nama pekerjaan (data benar)
            return [
                'pekerjaan' => $pekerjaan->nama_pekerjaan,
                'kategori' => $pekerjaan->kategori
            ];
        }

        // Value bukan nama pekerjaan, kemungkinan adalah kategori (data lama/salah)
        // Cari satu contoh pekerjaan dari kategori tersebut
        $pekerjaanDariKategori = RmibPekerjaan::where('gender', $gender)
            ->where('kategori', $value)
            ->first();

        if ($pekerjaanDariKategori) {
            // Value adalah kategori, ambil contoh pekerjaan
            return [
                'pekerjaan' => $pekerjaanDariKategori->nama_pekerjaan,
                'kategori' => $value
            ];
        }

        // Tidak ditemukan sama sekali, return null
        return ['pekerjaan' => null, 'kategori' => null];
    }

    /**
     * Tampilkan halaman list pekerjaan RMIB
     *
     * @param int $hasil_tes ID hasil tes
     * @return \Illuminate\View\View
     */
    public function adminListPekerjaan($hasil_tes)
    {
        // Ambil hasil tes berdasarkan id_hasil dengan relasi ke data diri
        $hasil = RmibHasilTes::with('karirDataDiri')->findOrFail($hasil_tes);

        // Ambil gender peserta
        $gender = $hasil->karirDataDiri->jenis_kelamin;

        // Ambil daftar semua pekerjaan
        $daftarPekerjaan = RmibPekerjaan::where('gender', $gender)
            ->orderBy('kelompok')
            ->orderBy('id')
            ->get()
            ->groupBy('kelompok');

        // Kirim ke view karir-list-pekerjaan.blade.php
        return view('karir-list-pekerjaan', compact(
            'hasil',
            'gender',
            'daftarPekerjaan'
        ));
    }

    // 3. Hapus hasil tes
    public function destroy($hasil_tes)
    {
        try {
            $hasil = RmibHasilTes::findOrFail($hasil_tes);

            // Hapus jawaban terkait
            RmibJawabanPeserta::where('hasil_id', $hasil_tes)->delete();

            // Hapus hasil tes
            $hasil->delete();

            return redirect()->route('admin.karir.index')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.karir.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // 4. Export ke Excel
    public function exportExcel()
    {
        // Mengambil hanya 1 data terbaru per NIM (berdasarkan ID terbesar per NIM)
        $hasilTes = RmibHasilTes::with('karirDataDiri')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(rht.id)')
                    ->from('rmib_hasil_tes as rht')
                    ->join('karir_data_diri as kdd', 'rht.karir_data_diri_id', '=', 'kdd.id')
                    ->groupBy('kdd.nim');
            })
            ->orderBy('tanggal_pengerjaan', 'desc')
            ->get();

        $filename = 'Data_Hasil_Tes_RMIB_' . \Carbon\Carbon::now()->format('YmdHis') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\KarirExport($hasilTes),
            $filename
        );
    }

    // 5. Data Provinsi - Halaman khusus untuk melihat data provinsi
    public function adminProvinsi()
    {
        // Daftar provinsi di Indonesia
        $daftarProvinsi = [
            'Aceh',
            'Sumatera Utara',
            'Sumatera Barat',
            'Riau',
            'Kepulauan Riau',
            'Jambi',
            'Bengkulu',
            'Kepulauan Bangka Belitung',
            'Sumatera Selatan',
            'Lampung',
            'Banten',
            'DKI Jakarta',
            'Jawa Barat',
            'Jawa Tengah',
            'DI Yogyakarta',
            'Jawa Timur',
            'Bali',
            'Nusa Tenggara Barat',
            'Nusa Tenggara Timur',
            'Kalimantan Barat',
            'Kalimantan Tengah',
            'Kalimantan Selatan',
            'Kalimantan Timur',
            'Kalimantan Utara',
            'Sulawesi Utara',
            'Sulawesi Tengah',
            'Sulawesi Selatan',
            'Sulawesi Tenggara',
            'Gorontalo',
            'Sulawesi Barat',
            'Maluku',
            'Maluku Utara',
            'Papua',
            'Papua Barat',
            'Papua Selatan',
            'Papua Tengah',
            'Papua Pegunungan',
            'Papua Barat Daya',
        ];

        // Hitung jumlah peserta per provinsi dengan single query (Optimized)
        // Menggunakan GROUP BY lebih efisien daripada loop + count()
        $totalUsers = KarirDataDiri::count();

        $provinsiCounts = KarirDataDiri::select('provinsi', DB::raw('count(*) as jumlah'))
            ->groupBy('provinsi')
            ->pluck('jumlah', 'provinsi')
            ->toArray();

        $provinsiData = [];
        foreach ($daftarProvinsi as $provinsi) {
            $jumlah = $provinsiCounts[$provinsi] ?? 0;
            $persentase = $totalUsers > 0 ? round(($jumlah / $totalUsers) * 100, 1) : 0;

            $provinsiData[] = [
                'nama' => $provinsi,
                'jumlah' => $jumlah,
                'persentase' => $persentase,
            ];
        }

        // Urutkan berdasarkan jumlah (descending)
        usort($provinsiData, function ($a, $b) {
            return $b['jumlah'] <=> $a['jumlah'];
        });

        return view('admin-karir-provinsi', compact('provinsiData', 'totalUsers'));
    }

    // 6. Data Program Studi - Halaman khusus untuk melihat data program studi
    public function adminProgramStudi()
    {
        // Daftar program studi per fakultas
        $prodiPerFakultas = [
            'FS' => [
                'Fisika',
                'Matematika',
                'Biologi',
                'Kimia',
                'Farmasi',
                'Sains Data',
                'Sains Aktuaria',
                'Sains Lingkungan Kelautan',
                'Sains Atmosfer dan Keplanetan',
                'Magister Fisika',
            ],
            'FTIK' => [
                'Perencanaan Wilayah dan Kota',
                'Teknik Geomatika',
                'Teknik Sipil',
                'Arsitektur',
                'Teknik Lingkungan',
                'Teknik Kelautan',
                'Desain Komunikasi Visual',
                'Arsitektur Lanskap',
                'Teknik Perkeretaapian',
                'Rekayasa Tata Kelola Air Terpadu',
                'Pariwisata',
            ],
            'FTI' => [
                'Teknik Elektro',
                'Teknik Fisika',
                'Teknik Informatika',
                'Teknik Geologi',
                'Teknik Geofisika',
                'Teknik Mesin',
                'Teknik Kimia',
                'Teknik Material',
                'Teknik Sistem Energi',
                'Teknik Industri',
                'Teknik Telekomunikasi',
                'Teknik Biomedis',
                'Teknik Biosistem',
                'Teknik Pertambangan',
                'Teknologi Industri Pertanian',
                'Teknologi Pangan',
                'Rekayasa Kehutanan',
                'Rekayasa Kosmetik',
                'Rekayasa Minyak dan Gas',
                'Rekayasa Instrumentasi dan Automasi',
                'Rekayasa Keolahragaan',
            ],
        ];

        // Hitung jumlah peserta per program studi dengan single query (Optimized)
        // Menggunakan GROUP BY lebih efisien daripada nested loop + count()
        $totalUsers = KarirDataDiri::count();

        $prodiCounts = KarirDataDiri::select('program_studi', DB::raw('count(*) as jumlah'))
            ->groupBy('program_studi')
            ->pluck('jumlah', 'program_studi')
            ->toArray();

        $prodiData = [];
        foreach ($prodiPerFakultas as $fakultas => $prodiList) {
            foreach ($prodiList as $prodi) {
                $jumlah = $prodiCounts[$prodi] ?? 0;
                $persentase = $totalUsers > 0 ? round(($jumlah / $totalUsers) * 100, 1) : 0;

                $prodiData[] = [
                    'fakultas' => $fakultas,
                    'nama' => $prodi,
                    'jumlah' => $jumlah,
                    'persentase' => $persentase,
                ];
            }
        }

        // Urutkan berdasarkan jumlah (descending)
        usort($prodiData, function ($a, $b) {
            return $b['jumlah'] <=> $a['jumlah'];
        });

        // Hitung statistik per fakultas dengan single query (Optimized)
        $fakultasCounts = KarirDataDiri::select('fakultas', DB::raw('count(*) as jumlah'))
            ->groupBy('fakultas')
            ->pluck('jumlah', 'fakultas')
            ->toArray();

        $fakultasStats = [];
        foreach (['FS', 'FTIK', 'FTI'] as $fak) {
            $jumlah = $fakultasCounts[$fak] ?? 0;
            $fakultasStats[$fak] = [
                'jumlah' => $jumlah,
                'persentase' => $totalUsers > 0 ? round(($jumlah / $totalUsers) * 100, 1) : 0,
            ];
        }

        return view('admin-karir-program-studi', compact('prodiData', 'totalUsers', 'fakultasStats', 'prodiPerFakultas'));
    }

    // ========================
    // HELPER METHODS
    // ========================

    /**
     * Clear all RMIB statistics cache
     * Call this method when new data is inserted or updated
     */
    private function clearKarirStatsCache(): void
    {
        Cache::forget('karir_stats_total_users');
        Cache::forget('karir_stats_total_tes');
        Cache::forget('karir_stats_total_laki');
        Cache::forget('karir_stats_total_perempuan');
        Cache::forget('karir_stats_kategori_counts');
        Cache::forget('karir_stats_fakultas_count');
        Cache::forget('karir_stats_status_tinggal');
        Cache::forget('karir_stats_prodi_sesuai');
        Cache::forget('karir_stats_asal_sekolah');

        Log::info('RMIB statistics cache cleared', [
            'cleared_at' => now()->toDateTimeString(),
        ]);
    }
}
