# Dokumentasi Code Backend - Fitur Mental Health

## Daftar Isi
1. [Struktur Routing](#1-struktur-routing)
2. [Autentikasi User (Google OAuth)](#2-autentikasi-user-google-oauth)
3. [Autentikasi Admin](#3-autentikasi-admin)
4. [Dashboard User](#4-dashboard-user)
5. [Form Data Diri & Riwayat Keluhan](#5-form-data-diri--riwayat-keluhan)
6. [Kuesioner Mental Health Test](#6-kuesioner-mental-health-test)
7. [Dashboard Admin](#7-dashboard-admin)
8. [Detail Jawaban Per Soal](#8-detail-jawaban-per-soal)
9. [Models & Database Relations](#9-models--database-relations)

---

## 1. Struktur Routing

### Routes User (Mental Health Test)
**File:** `routes/web.php` (Baris 76-108)

```php
Route::middleware('auth')->group(function () {
    // Dashboard utama user mental health
    Route::get('/user/mental-health', [DashboardController::class, 'index'])
        ->name('user.mental-health');

    // Group routes untuk mental health
    Route::prefix('mental-health')->name('mental-health.')->group(function () {
        // Form isi data diri
        Route::get('/isi-data-diri', [DataDirisController::class, 'create'])
            ->name('isi-data-diri');

        // Submit data diri
        Route::post('/isi-data-diri', [DataDirisController::class, 'store'])
            ->name('store-data-diri');
    });

    // Halaman kuesioner
    Route::get('/mental-health/kuesioner', function () {
        return view('kuesioner', ['title' => 'Kuesioner Mental Health']);
    })->name('mental-health.kuesioner');

    // Submit kuesioner
    Route::post('/mental-health/kuesioner', [HasilKuesionerController::class, 'store'])
        ->name('mental-health.kuesioner.submit');

    // Halaman hasil kuesioner terakhir
    Route::get('/mental-health/hasil', [HasilKuesionerController::class, 'showLatest'])
        ->name('mental-health.hasil');
});
```

### Routes Admin (Mental Health Management)
**File:** `routes/web.php` (Baris 32-75)

```php
// Login Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('login.process');
});

// Logout Route
Route::post('/logout', [AdminAuthController::class, 'logout'])
    ->name('logout');

// Admin Protected Routes
Route::middleware([AdminAuth::class])->group(function () {
    // Dashboard admin dengan search, filter, pagination
    Route::get('/admin/mental-health', [HasilKuesionerCombinedController::class, 'index'])
        ->name('admin.home');

    // Detail jawaban kuesioner per soal
    Route::get('/admin/mental-health/{id}/detail', [HasilKuesionerCombinedController::class, 'showDetail'])
        ->name('admin.mental-health.detail');

    // Hapus hasil kuesioner
    Route::delete('/admin/mental-health/{id}', [HasilKuesionerCombinedController::class, 'destroy'])
        ->name('admin.delete');

    // Export Excel
    Route::get('/admin/mental-health/export', [HasilKuesionerCombinedController::class, 'exportExcel'])
        ->name('admin.export.excel');
});
```

### Route OAuth Google
**File:** `routes/web.php` (Baris 119-123)

```php
// Redirect ke halaman Google OAuth
Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])
    ->name('google.redirect');

// Callback dari Google setelah login
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])
    ->name('google.callback');
```

---

## 2. Autentikasi User (Google OAuth)

### Controller: AuthController
**File:** `app/Http/Controllers/AuthController.php`

#### Method: redirectToGoogle()
```php
public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}
```
**Fungsi:** Mengarahkan user ke halaman login Google OAuth.

#### Method: handleGoogleCallback()
```php
public function handleGoogleCallback()
{
    try {
        // Ambil data user dari Google
        $googleUser = Socialite::driver('google')->user();
        $email = $googleUser->getEmail();
        $nim = null;

        // Ekstrak NIM dari email mahasiswa ITERA (format: 121140088@student.itera.ac.id)
        if (preg_match('/(\d{9})@student\.itera\.ac\.id$/', $email, $matches)) {
            $nim = $matches[1];
        }

        // Validasi: hanya email mahasiswa ITERA yang valid
        if (!$nim) {
            return redirect('/login')->with('error',
                'Login gagal. Pastikan Anda menggunakan email mahasiswa ITERA yang valid.');
        }

        // Buat/update user di tabel 'users'
        $user = Users::updateOrCreate(
            ['nim' => $nim],
            [
                'name' => $googleUser->getName(),
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(Str::random(16))
            ]
        );

        // Buat data diri default (hanya jika belum ada)
        DataDiris::firstOrCreate(
            ['nim' => $nim],
            [
                'nama' => $googleUser->getName(),
                'email' => $email,
            ]
        );

        // Login user
        Auth::login($user);

        // Redirect ke dashboard user
        return redirect()->intended('/user/mental-health');

    } catch (Exception $e) {
        if (config('app.debug')) {
            throw $e;
        }
        return redirect('/login')->with('error',
            'Terjadi kesalahan saat login via Google. Silakan coba lagi.');
    }
}
```

**Fungsi:**
- Menerima callback dari Google setelah user login
- Validasi email mahasiswa ITERA (harus format `NIM@student.itera.ac.id`)
- Ekstrak NIM dari email
- Buat/update data user
- Login user dan redirect ke dashboard

---

## 3. Autentikasi Admin

### Controller: AdminAuthController
**File:** `app/Http/Controllers/Auth/AdminAuthController.php`

#### Method: showLoginForm()
```php
public function showLoginForm()
{
    session(['title' => 'Login']);
    return view('login');
}
```

#### Method: login()
```php
public function login(Request $request)
{
    // Validasi input
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Login menggunakan guard 'admin'
    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/admin/mental-health');
    }

    // Login gagal
    return redirect()->back()
        ->withInput($request->only('email'))
        ->withErrors(['email' => 'Email atau password salah!']);
}
```

#### Method: logout()
```php
public function logout(Request $request)
{
    Auth::guard('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login')->with('success', 'Anda berhasil logout.');
}
```

### Middleware: AdminAuth
**File:** `app/Http/Middleware/AdminAuth.php`

```php
public function handle($request, Closure $next)
{
    // Cek apakah admin sudah login
    if (!Auth::guard('admin')->check()) {
        return redirect('/login')->with('error', 'Silakan login sebagai admin.');
    }

    // Ambil timestamp terakhir aktivitas admin
    $lastActivity = Session::get('last_activity_admin');
    $now = Carbon::now();

    // Auto logout jika tidak ada aktivitas selama 30 menit
    if ($lastActivity && $now->diffInMinutes(Carbon::parse($lastActivity)) > 30) {
        Auth::guard('admin')->logout();
        Session::forget('last_activity_admin');

        return redirect('/login')
            ->with('expired', true)
            ->with('error', 'Sesi Anda telah habis karena tidak ada aktivitas selama 1 jam.');
    }

    // Update waktu aktivitas terakhir
    Session::put('last_activity_admin', $now);

    return $next($request);
}
```

**Fungsi:** Middleware yang memproteksi routes admin dengan:
- Validasi login admin
- Auto logout setelah 30 menit tidak ada aktivitas
- Update timestamp aktivitas terakhir

---

## 4. Dashboard User

### Controller: DashboardController
**File:** `app/Http/Controllers/DashboardController.php`

#### Method: index()
```php
public function index()
{
    $user = Auth::user();

    // Cache key per user
    $cacheKey = "mh.user.{$user->nim}.test_history";

    // Cache data test history selama 5 menit
    $testData = Cache::remember($cacheKey, 300, function () use ($user) {
        // Query untuk ambil riwayat tes dengan keluhan terbaru sebelum setiap tes
        $baseQuery = HasilKuesioner::query()
            ->leftJoin('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
            ->leftJoin('riwayat_keluhans', function($join) {
                $join->on('hasil_kuesioners.nim', '=', 'riwayat_keluhans.nim')
                     ->whereColumn('riwayat_keluhans.created_at', '<=', 'hasil_kuesioners.created_at')
                     // Ambil keluhan terbaru sebelum test dengan subquery
                     ->whereRaw('riwayat_keluhans.created_at = (
                         SELECT MAX(rk.created_at)
                         FROM riwayat_keluhans rk
                         WHERE rk.nim = hasil_kuesioners.nim
                         AND rk.created_at <= hasil_kuesioners.created_at
                     )');
            })
            ->where('hasil_kuesioners.nim', $user->nim)
            ->select(
                'data_diris.nama',
                'hasil_kuesioners.nim',
                'data_diris.program_studi',
                'hasil_kuesioners.kategori as kategori_mental_health',
                'hasil_kuesioners.total_skor',
                'hasil_kuesioners.created_at',
                'riwayat_keluhans.keluhan',
                'riwayat_keluhans.lama_keluhan'
            );

        // Ambil SEMUA data untuk chart (diurutkan dari terlama ke terbaru)
        $semuaRiwayat = $baseQuery->orderBy('hasil_kuesioners.created_at', 'asc')->get();

        // Label chart: Tes 1, Tes 2, dst.
        $labels = $semuaRiwayat->map(function ($item, $index) {
            return 'Tes ' . ($index + 1);
        });

        // Data skor untuk chart
        $scores = $semuaRiwayat->pluck('total_skor')->map(fn($v) => (int) $v);

        $jumlahTesDiikuti = $semuaRiwayat->count();
        $kategoriTerakhir = $semuaRiwayat->isNotEmpty()
            ? $semuaRiwayat->last()->kategori_mental_health
            : 'Belum ada tes';

        return [
            'jumlahTesDiikuti' => $jumlahTesDiikuti,
            'kategoriTerakhir' => $kategoriTerakhir,
            'chartLabels' => $labels,
            'chartScores' => $scores,
        ];
    });

    // Query terpisah untuk data paginasi tabel riwayat (tidak di-cache)
    $riwayatTes = HasilKuesioner::query()
        ->leftJoin('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
        ->leftJoin('riwayat_keluhans', function($join) {
            $join->on('hasil_kuesioners.nim', '=', 'riwayat_keluhans.nim')
                 ->whereColumn('riwayat_keluhans.created_at', '<=', 'hasil_kuesioners.created_at')
                 ->whereRaw('riwayat_keluhans.created_at = (
                     SELECT MAX(rk.created_at)
                     FROM riwayat_keluhans rk
                     WHERE rk.nim = hasil_kuesioners.nim
                     AND rk.created_at <= hasil_kuesioners.created_at
                 )');
        })
        ->where('hasil_kuesioners.nim', $user->nim)
        ->select(
            'data_diris.nama',
            'hasil_kuesioners.nim',
            'data_diris.program_studi',
            'hasil_kuesioners.kategori as kategori_mental_health',
            'hasil_kuesioners.total_skor',
            'hasil_kuesioners.created_at',
            'riwayat_keluhans.keluhan',
            'riwayat_keluhans.lama_keluhan'
        )
        ->orderBy('hasil_kuesioners.created_at', 'asc')
        ->paginate(10);

    return view('user-mental-health', [
        'title' => 'Dashboard Mental Health',
        'jumlahTesDiikuti' => $testData['jumlahTesDiikuti'],
        'jumlahTesSelesai' => $testData['jumlahTesDiikuti'],
        'kategoriTerakhir' => $testData['kategoriTerakhir'],
        'riwayatTes' => $riwayatTes,
        'chartLabels' => $testData['chartLabels'],
        'chartScores' => $testData['chartScores'],
    ]);
}
```

**Fitur:**
- Menampilkan statistik user: jumlah tes, kategori terakhir
- Chart line perkembangan skor mental health
- Tabel riwayat tes dengan pagination
- Join dengan riwayat keluhan terbaru sebelum setiap tes
- Implementasi caching untuk performa optimal (5 menit)

---

## 5. Form Data Diri & Riwayat Keluhan

### Controller: DataDirisController
**File:** `app/Http/Controllers/DataDirisController.php`

#### Method: create()
```php
public function create()
{
    // Cari data diri berdasarkan NIM user yang login
    $dataDiri = DataDiris::where('nim', Auth::user()->nim)->first();

    return view('isi-data-diri', [
        'title' => 'Form Data Diri',
        'dataDiri' => $dataDiri
    ]);
}
```

#### Method: store()
```php
public function store(StoreDataDiriRequest $request)
{
    $user = Auth::user();

    // Data sudah tervalidasi otomatis oleh FormRequest
    $validated = $request->validated();

    DB::beginTransaction();

    try {
        // Update atau create data diri
        $dataDiri = DataDiris::updateOrCreate(
            ['nim' => $user->nim],
            [
                'nama' => $validated['nama'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'provinsi' => $validated['provinsi'],
                'alamat' => $validated['alamat'],
                'usia' => $validated['usia'],
                'fakultas' => $validated['fakultas'],
                'program_studi' => $validated['program_studi'],
                'asal_sekolah' => $validated['asal_sekolah'],
                'status_tinggal' => $validated['status_tinggal'],
                'email' => $validated['email'],
            ]
        );

        // Selalu buat entri riwayat keluhan baru setiap submit
        RiwayatKeluhans::create([
            'nim' => $user->nim,
            'keluhan' => $validated['keluhan'],
            'lama_keluhan' => $validated['lama_keluhan'],
            'pernah_konsul' => $validated['pernah_konsul'],
            'pernah_tes' => $validated['pernah_tes'],
        ]);

        DB::commit();

        // Invalidate cache admin (user stats, fakultas stats)
        Cache::forget('mh.admin.user_stats');
        Cache::forget('mh.admin.fakultas_stats');

        // Simpan ke session untuk digunakan di halaman berikutnya
        session([
            'nim' => $user->nim,
            'nama' => $dataDiri->nama,
            'program_studi' => $dataDiri->program_studi
        ]);

        return redirect()
            ->route('mental-health.kuesioner')
            ->with('success', 'Data berhasil disimpan.');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()
            ->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])
            ->withInput();
    }
}
```

### Form Request: StoreDataDiriRequest
**File:** `app/Http/Requests/StoreDataDiriRequest.php`

```php
public function rules(): array
{
    return [
        'nama' => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:L,P',
        'provinsi' => 'required|string|max:255',
        'alamat' => 'required|string',
        'usia' => 'required|integer|min:1',
        'fakultas' => 'required|string|max:255',
        'program_studi' => 'required|string|max:255',
        'asal_sekolah' => 'required|string|max:255',
        'status_tinggal' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'keluhan' => 'required|string',
        'lama_keluhan' => 'required|string|max:255',
        'pernah_konsul' => 'required|in:Ya,Tidak',
        'pernah_tes' => 'required|in:Ya,Tidak',
    ];
}
```

**Fungsi:**
- Validasi otomatis menggunakan Form Request
- Update data diri mahasiswa (gunakan `updateOrCreate`)
- Selalu membuat riwayat keluhan baru setiap submit
- Implementasi database transaction untuk data integrity
- Cache invalidation untuk admin statistics

---

## 6. Kuesioner Mental Health Test

### Controller: HasilKuesionerController
**File:** `app/Http/Controllers/HasilKuesionerController.php`

#### Method: store()
```php
public function store(StoreHasilKuesionerRequest $request)
{
    // Data sudah tervalidasi otomatis (nim + 38 questions)
    $validated = $request->validated();

    // Collect answers
    $jawaban = [];
    $totalSkor = 0;

    for ($i = 1; $i <= 38; $i++) {
        $answer = (int) $request->input("question{$i}");
        $jawaban[] = $answer;
        $totalSkor += $answer;
    }

    // Kategori berdasarkan total skor MHI-38
    $kategori = match (true) {
        $totalSkor >= 190 && $totalSkor <= 226 => 'Sangat Sehat',
        $totalSkor >= 152 && $totalSkor <= 189 => 'Sehat',
        $totalSkor >= 114 && $totalSkor <= 151 => 'Cukup Sehat',
        $totalSkor >= 76 && $totalSkor <= 113 => 'Perlu Dukungan',
        $totalSkor >= 38 && $totalSkor <= 75 => 'Perlu Dukungan Intensif',
        default => 'Tidak Terdefinisi',
    };

    try {
        DB::beginTransaction();

        // Simpan hasil kuesioner
        $hasil = HasilKuesioner::create([
            'nim' => $validated['nim'],
            'total_skor' => $totalSkor,
            'kategori' => $kategori,
        ]);

        // Simpan detail jawaban per soal
        $jawabanDetails = [];
        for ($i = 1; $i <= 38; $i++) {
            $jawabanDetails[] = [
                'hasil_kuesioner_id' => $hasil->id,
                'nomor_soal' => $i,
                'skor' => $jawaban[$i - 1],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        MentalHealthJawabanDetail::insert($jawabanDetails);

        DB::commit();

        // Invalidate all related caches
        Cache::forget('mh.admin.user_stats');
        Cache::forget('mh.admin.kategori_counts');
        Cache::forget('mh.admin.total_tes');
        Cache::forget('mh.admin.fakultas_stats');
        Cache::forget("mh.user.{$validated['nim']}.test_history");

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors([
            'error' => 'Gagal menyimpan hasil kuesioner: ' . $e->getMessage()
        ]);
    }

    // Simpan nim ke session
    session(['nim' => $validated['nim']]);

    return redirect()
        ->route('mental-health.hasil')
        ->with('success', 'Hasil kuesioner berhasil disimpan.');
}
```

#### Method: showLatest()
```php
public function showLatest()
{
    $nim = session('nim');
    $nama = session('nama');
    $programStudi = session('program_studi');

    if (!$nim) {
        return redirect()->route('mental-health.kuesioner')
            ->with('error', 'NIM tidak ditemukan di sesi.');
    }

    // Ambil hasil kuesioner terbaru
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
```

### Form Request: StoreHasilKuesionerRequest
**File:** `app/Http/Requests/StoreHasilKuesionerRequest.php`

```php
public function rules(): array
{
    $rules = [
        'nim' => 'required|string|max:20',
    ];

    // Validasi untuk 38 pertanyaan kuesioner
    // Setiap pertanyaan harus diisi dan nilainya antara 0-6
    for ($i = 1; $i <= 38; $i++) {
        $rules["question{$i}"] = 'required|integer|min:0|max:6';
    }

    return $rules;
}
```

**Fungsi:**
- Validasi 38 pertanyaan MHI-38 (Mental Health Inventory)
- Hitung total skor (range: 38-226)
- Kategorisasi hasil berdasarkan skor
- Simpan hasil kuesioner dan detail jawaban per soal
- Bulk insert untuk performa optimal
- Cache invalidation untuk semua statistik terkait

---

## 7. Dashboard Admin

### Controller: HasilKuesionerCombinedController
**File:** `app/Http/Controllers/HasilKuesionerCombinedController.php`

#### Method: index() - Dashboard Admin dengan Filter & Search
```php
public function index(Request $request)
{
    // 1. Ambil parameter dari request
    $limit = $request->input('limit', 10);
    $search = $request->input('search');
    $sort = $request->input('sort', 'created_at');
    $order = $request->input('order', 'desc');
    $kategori = $request->input('kategori');

    // 2. Subquery untuk mendapatkan ID hasil kuesioner terakhir per mahasiswa
    $latestIds = DB::table('hasil_kuesioners')
        ->select(DB::raw('MAX(id) as id'))
        ->groupBy('nim');

    // 3. Query Utama dengan optimasi (menghindari N+1 problem)
    $query = HasilKuesioner::query()
        ->joinSub($latestIds, 'latest', 'hasil_kuesioners.id', '=', 'latest.id')
        ->join('data_diris', 'hasil_kuesioners.nim', '=', 'data_diris.nim')
        // LEFT JOIN untuk count jumlah tes per mahasiswa
        ->leftJoin('hasil_kuesioners as hk_count', 'data_diris.nim', '=', 'hk_count.nim')
        ->select('hasil_kuesioners.*', 'data_diris.nama as nama_mahasiswa')
        // COUNT dengan GROUP BY (1 query, bukan N queries!)
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

    // 4. Terapkan Filter Kategori
    $query->when($kategori, function ($q) use ($kategori) {
        $q->where('hasil_kuesioners.kategori', $kategori);
    });

    // 5. Terapkan Pencarian Multi-Kolom
    $query->when($search, function ($q) use ($search) {
        $terms = array_filter(preg_split('/\s+/', trim($search)));

        // Kolom untuk pencarian umum (LIKE)
        $likeColumns = [
            'hasil_kuesioners.nim',
            'data_diris.nama',
            'data_diris.email',
            'data_diris.alamat',
            'data_diris.asal_sekolah',
            'data_diris.status_tinggal',
        ];

        // Aturan pencarian khusus untuk pencocokan eksak
        $specialRules = [
            'fakultas' => [
                'values' => ['fs', 'fti', 'ftik'],
                'transform' => 'strtoupper',
            ],
            'provinsi' => [
                'values' => ['papua', 'riau'],
                'transform' => 'ucfirst',
            ],
            'program_studi' => [
                'values' => ['fisika', 'arsitektur', 'kimia'],
                'transform' => null,
            ],
            'jenis_kelamin' => [
                'values' => ['l', 'p'],
                'transform' => 'strtoupper',
            ],
        ];

        $q->where(function (Builder $query) use ($terms, $likeColumns, $specialRules) {
            foreach ($terms as $term) {
                $query->where(function (Builder $subQuery) use ($term, $likeColumns, $specialRules) {
                    // Pencarian LIKE pada kolom umum
                    foreach ($likeColumns as $column) {
                        $subQuery->orWhere($column, 'like', "%$term%");
                    }

                    // Pencarian dengan aturan khusus
                    foreach ($specialRules as $columnName => $rule) {
                        $dbColumn = 'data_diris.' . $columnName;
                        $lowerTerm = strtolower($term);

                        if (in_array($lowerTerm, $rule['values'])) {
                            // Pencocokan eksak dengan transformasi
                            $transformedTerm = $rule['transform'] ? $rule['transform']($term) : $term;
                            $subQuery->orWhere($dbColumn, $transformedTerm);
                        } else {
                            // Fallback ke LIKE
                            $subQuery->orWhere($dbColumn, 'like', "%$term%");
                        }
                    }
                });
            }
        });
    });

    // 6. Terapkan Sorting
    $sortColumn = match ($sort) {
        'nama' => 'data_diris.nama',
        default => 'hasil_kuesioners.' . $sort,
    };
    $query->orderBy($sortColumn, $order);

    // 7. Pagination
    $hasilKuesioners = $query->paginate($limit)->withQueryString();

    // 8. Status Pesan Pencarian
    $searchMessage = null;
    if ($search && !$request->has('page')) {
        if ($hasilKuesioners->total() > 0) {
            $searchMessage = 'Data berhasil ditemukan!';
        } else {
            $searchMessage = 'Data tidak ditemukan!';
        }
    }

    // 9. Statistik Global (dengan caching 1 menit)
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

    $totalUsers = $userStats->total_users ?? 0;

    // Kategori Counts (cached)
    $kategoriCounts = Cache::remember('mh.admin.kategori_counts', 60, function () use ($latestIds) {
        return HasilKuesioner::whereIn('id', $latestIds)
            ->selectRaw('kategori, COUNT(*) as jumlah')
            ->groupBy('kategori')
            ->pluck('jumlah', 'kategori')
            ->all();
    });

    // Total Tes (cached)
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

    // 10. Siapkan data untuk Donut Chart
    $totalAsal = array_sum($asalCounts);
    $r = 60;
    $circ = 2 * M_PI * $r;
    $segments = [];
    $offset = 0;
    $pct = fn($n) => $totalAsal > 0 ? round(($n / $totalAsal) * 100, 1) : 0;

    foreach ($asalCounts as $label => $val) {
        $p = $totalAsal > 0 ? $val / $totalAsal : 0;
        $dash = $circ * $p;
        $segments[] = [
            'label' => $label,
            'value' => $val,
            'percent' => $pct($val),
            'dash' => $dash,
            'offset' => $offset
        ];
        $offset += $dash;
    }

    // 11. Kirim data ke view
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
        'searchMessage' => $searchMessage,
    ] + $this->getStatistikFakultas());
}
```

#### Method: getStatistikFakultas() - Statistik per Fakultas
```php
private function getStatistikFakultas()
{
    return Cache::remember('mh.admin.fakultas_stats', 60, function () {
        $fakultasCount = DataDiris::select('data_diris.fakultas', DB::raw('COUNT(DISTINCT data_diris.nim) as total'))
            ->join('hasil_kuesioners', 'data_diris.nim', '=', 'hasil_kuesioners.nim')
            ->whereNotNull('data_diris.fakultas')
            ->groupBy('data_diris.fakultas')
            ->pluck('total', 'data_diris.fakultas');

        $totalFakultas = $fakultasCount->sum();
        $fakultasPersen = $fakultasCount->map(fn($count) =>
            $totalFakultas > 0 ? round(($count / $totalFakultas) * 100, 1) : 0
        );

        return [
            'fakultasCount' => $fakultasCount->all(),
            'fakultasPersen' => $fakultasPersen->all(),
            'warnaFakultas' => ['FS' => '#4e79a7', 'FTI' => '#f28e2c', 'FTIK' => '#e15759'],
        ];
    });
}
```

#### Method: destroy() - Hapus Data Mahasiswa
```php
public function destroy($id)
{
    // Temukan hasil kuesioner berdasarkan ID
    $hasil = HasilKuesioner::find($id);

    if (!$hasil) {
        return redirect()->route('admin.home')->with('error', 'Data tidak ditemukan.');
    }

    $nim = $hasil->nim;

    DB::beginTransaction();
    try {
        // Temukan data terkait
        $dataDiri = DataDiris::where('nim', $nim)->first();
        $user = Users::where('nim', $nim)->first();

        // Hapus semua data terkait
        HasilKuesioner::where('nim', $nim)->delete();
        \App\Models\RiwayatKeluhans::where('nim', $nim)->delete();

        if ($dataDiri) {
            $dataDiri->delete();
        }

        if ($user) {
            $user->delete();
        }

        DB::commit();

        // Invalidate all cached statistics
        Cache::forget('mh.admin.user_stats');
        Cache::forget('mh.admin.kategori_counts');
        Cache::forget('mh.admin.total_tes');
        Cache::forget('mh.admin.fakultas_stats');
        Cache::forget("mh.user.{$nim}.test_history");

        return redirect()->route('admin.home')
            ->with('success', 'Seluruh data mahasiswa dengan NIM ' . $nim . ' berhasil dihapus.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('admin.home')
            ->with('error', 'Gagal menghapus data mahasiswa: ' . $e->getMessage());
    }
}
```

#### Method: exportExcel() - Export Data ke Excel
```php
public function exportExcel(Request $request)
{
    // Ambil parameter filter dan sort
    $search = $request->input('search');
    $kategori = $request->input('kategori');
    $sort = $request->input('sort', 'created_at');
    $order = $request->input('order', 'desc');

    // Generate nama file dengan timestamp
    $fileName = 'hasil-kuesioner-' . now()->setTimezone('Asia/Jakarta')->format('Y-m-d_H-i') . '.xlsx';

    // Export dengan filter yang sama dengan view
    return Excel::download(
        new HasilKuesionerExport($search, $kategori, $sort, $order),
        $fileName
    );
}
```

**Fitur Dashboard Admin:**
- Tabel hasil kuesioner dengan pagination
- Search multi-kolom (NIM, nama, email, fakultas, dll)
- Filter berdasarkan kategori mental health
- Sorting dinamis (nama, skor, tanggal, dll)
- Statistik global (total user, total tes, gender, asal sekolah, fakultas)
- Chart donut asal sekolah
- Chart bar fakultas
- Export ke Excel dengan filter
- Implementasi caching untuk performa optimal (1 menit)
- Optimasi query untuk menghindari N+1 problem

---

## 8. Detail Jawaban Per Soal

### Controller: HasilKuesionerCombinedController
**File:** `app/Http/Controllers/HasilKuesionerCombinedController.php`

#### Method: showDetail()
```php
public function showDetail($id)
{
    // Eager loading relasi untuk menghindari N+1 problem
    $hasil = HasilKuesioner::with([
        'dataDiri',
        'jawabanDetails' => function($query) {
            $query->orderBy('nomor_soal');
        },
        'riwayatKeluhans' => function($query) {
            $query->latest()->limit(1);
        }
    ])->findOrFail($id);

    // Daftar pertanyaan lengkap (38 pertanyaan MHI-38)
    $questions = [
        1 => 'Seberapa bahagia, puas, atau senangkah Anda dengan kehidupan pribadi Anda selama sebulan terakhir?',
        2 => 'Seberapa sering Anda merasa kesepian selama sebulan terakhir?',
        3 => 'Seberapa sering Anda merasa gugup atau gelisah ketika dihadapkan pada situasi yang menyenangkan atau tak terduga selama sebulan terakhir?',
        // ... (sampai pertanyaan 38)
        38 => 'Selama sebulan terakhir, apakah Anda pernah mengalami atau merasa berada di bawah tekanan, stres, atau tekanan?'
    ];

    // Pertanyaan negatif (Psychological Distress) - 24 items
    $negativeQuestions = [2, 3, 8, 9, 11, 13, 14, 15, 16, 18, 19, 20, 21, 24, 25, 27, 28, 29, 30, 32, 33, 35, 36, 38];

    // Pertanyaan positif (Psychological Well-being) - 14 items
    // Items: 1, 4, 5, 6, 7, 10, 12, 17, 22, 23, 26, 31, 34, 37

    return view('admin-mental-health-detail', [
        'title' => 'Detail Jawaban Kuesioner - ' . $hasil->dataDiri->nama,
        'hasil' => $hasil,
        'jawabanDetails' => $hasil->jawabanDetails,
        'questions' => $questions,
        'negativeQuestions' => $negativeQuestions
    ]);
}
```

**Fungsi:**
- Menampilkan detail jawaban per soal dari kuesioner
- Menampilkan data diri mahasiswa
- Menampilkan riwayat keluhan terakhir
- Menandai pertanyaan negatif (distress) dan positif (well-being)
- Eager loading untuk menghindari N+1 problem

---

## 9. Models & Database Relations

### Model: HasilKuesioner
**File:** `app/Models/HasilKuesioner.php`

```php
class HasilKuesioner extends Model
{
    use HasFactory;

    protected $table = 'hasil_kuesioners';

    protected $fillable = [
        'nim',
        'total_skor',
        'kategori',
        'created_at',
        'updated_at'
    ];

    // Relasi ke DataDiris (belongsTo)
    public function dataDiri()
    {
        return $this->belongsTo(DataDiris::class, 'nim', 'nim');
    }

    // Relasi ke RiwayatKeluhans (hasMany)
    public function riwayatKeluhans()
    {
        return $this->hasMany(RiwayatKeluhans::class, 'nim', 'nim');
    }

    // Relasi ke MentalHealthJawabanDetail (hasMany)
    public function jawabanDetails()
    {
        return $this->hasMany(MentalHealthJawabanDetail::class, 'hasil_kuesioner_id');
    }
}
```

### Model: MentalHealthJawabanDetail
**File:** `app/Models/MentalHealthJawabanDetail.php`

```php
class MentalHealthJawabanDetail extends Model
{
    protected $table = 'mental_health_jawaban_details';

    protected $fillable = [
        'hasil_kuesioner_id',
        'nomor_soal',
        'skor'
    ];

    // Relasi ke HasilKuesioner (belongsTo)
    public function hasilKuesioner()
    {
        return $this->belongsTo(HasilKuesioner::class, 'hasil_kuesioner_id');
    }
}
```

### Relasi Database

```
users (table)
├─ nim (PK)
└─ relasi: hasOne DataDiris

data_diris (table)
├─ nim (PK)
└─ relasi:
   ├─ belongsTo Users
   ├─ hasMany HasilKuesioner
   └─ hasMany RiwayatKeluhans

hasil_kuesioners (table)
├─ id (PK)
├─ nim (FK)
└─ relasi:
   ├─ belongsTo DataDiris
   └─ hasMany MentalHealthJawabanDetail

mental_health_jawaban_details (table)
├─ id (PK)
├─ hasil_kuesioner_id (FK)
├─ nomor_soal
├─ skor
└─ relasi: belongsTo HasilKuesioner

riwayat_keluhans (table)
├─ id (PK)
├─ nim (FK)
└─ relasi: belongsTo DataDiris
```

---

## Optimasi Performa

### 1. Database Query Optimization
- **Eager Loading:** Gunakan `with()` untuk menghindari N+1 problem
- **Subquery:** Gunakan `joinSub()` untuk query kompleks
- **Bulk Insert:** Gunakan `insert()` untuk menyimpan banyak data sekaligus
- **Selective Column:** Hanya ambil kolom yang dibutuhkan dengan `select()`
- **Index:** Pastikan kolom `nim` di-index untuk performa JOIN

### 2. Caching Strategy
- **Admin Statistics:** Cache 1 menit (frequent updates)
- **User Test History:** Cache 5 menit (per user)
- **Cache Invalidation:** Hapus cache saat data berubah (create, update, delete)

### 3. Database Transaction
- Gunakan `DB::beginTransaction()` dan `DB::commit()` untuk data integrity
- Gunakan `DB::rollBack()` saat terjadi error

---

## Security Features

### 1. Authentication & Authorization
- **User:** Google OAuth (email mahasiswa ITERA)
- **Admin:** Email/Password dengan guard 'admin'
- **Middleware:** Proteksi routes dengan `auth` dan `AdminAuth`

### 2. Session Management
- Auto logout admin setelah 30 menit tidak ada aktivitas
- Session regeneration setelah login
- Session invalidation setelah logout

### 3. Input Validation
- Form Request untuk validasi otomatis
- CSRF Protection (Laravel default)
- XSS Protection dengan Blade templating

### 4. SQL Injection Prevention
- Query Builder & Eloquent ORM
- Parameter Binding otomatis
- Prepared Statements

---

## Kategori Mental Health (MHI-38)

| Total Skor | Kategori |
|-----------|----------|
| 190 - 226 | Sangat Sehat |
| 152 - 189 | Sehat |
| 114 - 151 | Cukup Sehat |
| 76 - 113 | Perlu Dukungan |
| 38 - 75 | Perlu Dukungan Intensif |

**Catatan:**
- MHI-38 menggunakan skala Likert 1-6
- Total skor minimum: 38 (38 soal × 1)
- Total skor maksimum: 226 (38 soal × 6, dengan adjustment untuk pertanyaan negatif)
- Pertanyaan negatif (24 items): skor di-reverse (7 - skor asli)
- Pertanyaan positif (14 items): skor langsung

---

**Dokumentasi dibuat pada:** 21 November 2025
**Versi:** 1.0
