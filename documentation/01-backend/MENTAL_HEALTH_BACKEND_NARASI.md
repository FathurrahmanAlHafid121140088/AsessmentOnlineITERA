# Penjelasan Narasi - Backend Fitur Mental Health

## Pendahuluan

Sistem Mental Health Assessment merupakan aplikasi web berbasis Laravel yang dirancang untuk membantu mahasiswa Institut Teknologi Sumatera (ITERA) dalam melakukan evaluasi kesehatan mental mereka secara mandiri. Sistem ini mengimplementasikan instrumen psikologi terstandar MHI-38 (Mental Health Inventory-38) yang telah terbukti valid dan reliabel untuk mengukur kondisi kesehatan mental seseorang.

Dokumentasi ini akan menjelaskan secara mendalam bagaimana backend sistem bekerja, mulai dari proses autentikasi, pengisian kuesioner, hingga pengelolaan data oleh administrator. Penjelasan disusun secara naratif untuk memudahkan pemahaman alur kerja sistem secara menyeluruh.

---

## Arsitektur Sistem

Sistem Mental Health Assessment dibangun menggunakan arsitektur MVC (Model-View-Controller) yang merupakan pola desain standar dalam Laravel. Backend sistem terdiri dari beberapa komponen utama:

### Komponen Backend

**Controllers** bertindak sebagai pengatur logika bisnis aplikasi. Setiap fitur utama memiliki controller tersendiri yang menangani request dari user dan mengembalikan response yang sesuai. Controllers berkomunikasi dengan Models untuk mengakses dan memanipulasi data, kemudian mengirimkan data tersebut ke Views untuk ditampilkan kepada pengguna.

**Models** merepresentasikan struktur data dan relasi antar tabel dalam database. Models menggunakan Eloquent ORM yang memungkinkan developer untuk berinteraksi dengan database menggunakan syntax PHP yang intuitif tanpa perlu menulis raw SQL query. Setiap model memiliki relasi yang jelas dengan model lain, mencerminkan relasi antar tabel di database.

**Form Requests** berfungsi sebagai lapisan validasi yang memisahkan logika validasi dari controller. Hal ini membuat kode lebih bersih, mudah dipelihara, dan dapat digunakan kembali. Setiap form memiliki Form Request class tersendiri yang mendefinisikan aturan validasi dan pesan error kustom.

**Middleware** bertindak sebagai filter yang memeriksa setiap HTTP request sebelum mencapai controller. Dalam sistem ini, middleware digunakan untuk autentikasi user dan admin, serta untuk mengimplementasikan auto logout saat tidak ada aktivitas dalam waktu tertentu.

**Routes** mendefinisikan URL endpoint dan menghubungkannya dengan controller method yang sesuai. Routes juga menerapkan middleware untuk proteksi akses, memastikan hanya user yang terautentikasi yang dapat mengakses fitur-fitur tertentu.

---

## Alur Kerja Sistem untuk Mahasiswa

### 1. Proses Login dengan Google OAuth

Mahasiswa yang ingin menggunakan sistem harus login terlebih dahulu menggunakan akun Google mereka. Sistem ini mengimplementasikan OAuth 2.0 melalui Laravel Socialite, yang merupakan cara aman dan standar industri untuk autentikasi pihak ketiga.

Ketika mahasiswa mengklik tombol "Login dengan Google", browser akan mengarahkan mereka ke halaman login Google melalui method `redirectToGoogle()` di `AuthController`. Laravel Socialite akan menangani seluruh proses OAuth, termasuk pertukaran token dan verifikasi identitas dengan server Google.

Setelah mahasiswa berhasil login di Google, mereka akan diarahkan kembali ke aplikasi melalui callback URL. Di sinilah proses verifikasi dan registrasi otomatis terjadi. Method `handleGoogleCallback()` akan menerima data user dari Google, yang mencakup nama, email, dan Google ID.

Sistem kemudian melakukan validasi penting: memastikan bahwa email yang digunakan adalah email mahasiswa ITERA dengan format `NIM@student.itera.ac.id`. Validasi ini dilakukan menggunakan regular expression yang mengekstrak 9 digit NIM dari email. Jika format email tidak sesuai, login akan ditolak dengan pesan error yang informatif.

Setelah validasi berhasil, sistem akan mencari atau membuat record user di database menggunakan method `updateOrCreate()`. Method ini sangat efisien karena akan membuat user baru jika belum ada, atau mengupdate data jika user sudah pernah login sebelumnya. Data yang disimpan meliputi NIM, nama, email, Google ID, dan password random yang di-hash.

Sistem juga membuat record awal di tabel `data_diris` menggunakan method `firstOrCreate()`. Berbeda dengan `updateOrCreate()`, method ini hanya akan membuat record jika belum ada, dan tidak akan mengupdate data yang sudah ada. Ini penting untuk menjaga data diri mahasiswa yang mungkin sudah dilengkapi sebelumnya.

Setelah semua proses selesai, mahasiswa akan di-login secara otomatis menggunakan `Auth::login($user)` dan diarahkan ke dashboard mereka. Penggunaan `intended()` memastikan bahwa jika mahasiswa mencoba mengakses halaman tertentu sebelum login, mereka akan diarahkan kembali ke halaman tersebut setelah login berhasil.

### 2. Dashboard Mahasiswa - Melihat Riwayat dan Statistik

Setelah login, mahasiswa akan diarahkan ke dashboard mental health mereka yang ditangani oleh `DashboardController`. Dashboard ini menampilkan informasi komprehensif tentang riwayat tes mental health yang pernah diikuti.

Method `index()` di `DashboardController` melakukan query database yang cukup kompleks untuk mengambil semua data yang diperlukan. Query ini menggunakan teknik optimasi yang canggih untuk memastikan performa tetap cepat meskipun data bertambah banyak.

Salah satu fitur menarik di dashboard adalah implementasi caching. Data statistik dan riwayat tes mahasiswa di-cache selama 5 menit menggunakan Laravel Cache. Ini berarti jika mahasiswa refresh halaman berkali-kali dalam 5 menit, sistem tidak perlu query database lagi, melainkan langsung mengambil data dari cache. Hal ini sangat meningkatkan performa dan mengurangi beban database.

Query utama menggunakan teknik LEFT JOIN dengan tabel `riwayat_keluhans` untuk menampilkan keluhan yang dilaporkan mahasiswa sebelum setiap tes. Yang menarik adalah implementasi subquery untuk mendapatkan keluhan terbaru sebelum setiap tes dilakukan. Ini memastikan bahwa keluhan yang ditampilkan adalah keluhan yang benar-benar relevan dengan waktu tes tersebut.

Dashboard menampilkan beberapa statistik penting: jumlah total tes yang pernah diikuti, kategori hasil tes terakhir, dan chart line yang menunjukkan perkembangan skor mental health dari waktu ke waktu. Chart ini sangat berguna untuk melihat apakah kondisi mental health mahasiswa membaik atau memburuk.

Data untuk chart disusun dengan urutan ascending (dari tes terlama ke terbaru), dengan label "Tes 1", "Tes 2", dan seterusnya. Skor dari setiap tes diambil dan dikonversi menjadi integer untuk memastikan chart dapat merender dengan benar.

Selain data yang di-cache, dashboard juga menampilkan tabel riwayat tes dengan pagination. Data paginasi ini tidak di-cache karena bersifat dinamis - mahasiswa bisa berpindah halaman, dan setiap halaman harus menampilkan data yang tepat. Pagination menggunakan Laravel's built-in pagination yang sangat efisien dan mudah digunakan.

### 3. Mengisi Data Diri dan Riwayat Keluhan

Sebelum mahasiswa dapat mengisi kuesioner mental health, mereka harus melengkapi data diri dan riwayat keluhan terlebih dahulu. Proses ini ditangani oleh `DataDirisController`.

Method `create()` akan menampilkan form data diri. Jika mahasiswa sudah pernah mengisi data sebelumnya, form akan menampilkan data yang sudah ada (pre-filled) untuk memudahkan proses update. Ini dilakukan dengan melakukan query ke tabel `data_diris` berdasarkan NIM mahasiswa yang sedang login.

Ketika mahasiswa submit form, request akan ditangani oleh method `store()` yang menggunakan `StoreDataDiriRequest` untuk validasi otomatis. Form Request ini mendefinisikan 14 field yang wajib diisi, mulai dari data demografi (nama, jenis kelamin, usia, provinsi, alamat) hingga data akademik (fakultas, program studi, asal sekolah, status tinggal).

Yang menarik dari validasi adalah penggunaan rule `in` untuk field tertentu. Misalnya, jenis kelamin hanya boleh 'L' atau 'P', status konsultasi dan tes sebelumnya hanya boleh 'Ya' atau 'Tidak'. Hal ini memastikan data yang masuk ke database selalu konsisten dan valid.

Setelah validasi berhasil, sistem menggunakan database transaction untuk memastikan data integrity. Transaction dimulai dengan `DB::beginTransaction()`, yang berarti semua operasi database setelahnya bisa di-rollback jika terjadi error.

Sistem menggunakan method `updateOrCreate()` untuk data diri, yang berarti jika mahasiswa sudah pernah mengisi data sebelumnya, data akan di-update. Namun, untuk riwayat keluhan, sistem selalu membuat entri baru menggunakan `create()`. Ini penting karena keluhan bisa berubah dari waktu ke waktu, dan kita ingin melacak perubahan tersebut.

Setelah semua operasi database berhasil, transaction di-commit dengan `DB::commit()`. Sistem juga melakukan cache invalidation untuk statistik admin yang mungkin terpengaruh oleh data baru ini, khususnya `mh.admin.user_stats` dan `mh.admin.fakultas_stats`.

Data penting seperti NIM, nama, dan program studi disimpan ke session untuk digunakan di halaman berikutnya. Mahasiswa kemudian diarahkan ke halaman kuesioner untuk melanjutkan proses tes.

Jika terjadi error pada proses ini, transaction akan di-rollback dengan `DB::rollBack()`, memastikan tidak ada data yang tersimpan sebagian. Mahasiswa akan dikembalikan ke form dengan pesan error yang jelas dan input mereka tetap tersimpan (menggunakan `withInput()`), sehingga mereka tidak perlu mengisi ulang semua field.

### 4. Mengerjakan Kuesioner Mental Health (MHI-38)

Kuesioner mental health menggunakan instrumen MHI-38 (Mental Health Inventory-38), yang merupakan instrumen psikologi terstandar untuk mengukur kesehatan mental. Kuesioner ini terdiri dari 38 pertanyaan dengan skala Likert 1-6, di mana setiap pertanyaan mengukur aspek berbeda dari kesehatan mental.

Halaman kuesioner ditampilkan melalui route sederhana yang langsung me-render view, tanpa melalui controller khusus. Ini karena tidak ada logika bisnis yang diperlukan untuk menampilkan form - semua pertanyaan sudah hard-coded di view.

Yang menarik dari MHI-38 adalah pembagian pertanyaan menjadi dua kategori: Psychological Well-being (14 pertanyaan positif) dan Psychological Distress (24 pertanyaan negatif). Pertanyaan negatif perlu di-reverse skor-nya saat perhitungan, tapi di backend ini dilakukan secara implisit melalui kategorisasi hasil akhir.

Ketika mahasiswa submit kuesioner, request ditangani oleh `HasilKuesionerController::store()`. Method ini menggunakan `StoreHasilKuesionerRequest` yang memvalidasi bahwa:
- NIM harus ada dan valid
- Semua 38 pertanyaan harus dijawab
- Setiap jawaban harus berupa integer antara 0-6

Validasi ini sangat penting untuk memastikan tidak ada data yang missing atau invalid. Jika satu pertanyaan pun tidak dijawab, seluruh submission akan ditolak dengan pesan error yang spesifik menunjukkan pertanyaan mana yang belum dijawab.

Setelah validasi berhasil, sistem menghitung total skor dengan menjumlahkan semua jawaban. Total skor ini kemudian digunakan untuk menentukan kategori kesehatan mental berdasarkan tabel kategorisasi MHI-38.

Sistem menggunakan match expression (fitur PHP 8) untuk kategorisasi yang sangat clean dan readable. Rentang skor dibagi menjadi 5 kategori:
- 190-226: Sangat Sehat (kondisi mental sangat baik)
- 152-189: Sehat (kondisi mental baik)
- 114-151: Cukup Sehat (kondisi mental cukup baik)
- 76-113: Perlu Dukungan (perlu mendapat perhatian)
- 38-75: Perlu Dukungan Intensif (perlu bantuan profesional segera)

Proses penyimpanan menggunakan database transaction untuk memastikan konsistensi data. Pertama, hasil kuesioner disimpan ke tabel `hasil_kuesioners` dengan data NIM, total skor, dan kategori. Sistem mendapatkan ID dari hasil yang baru disimpan, yang akan digunakan sebagai foreign key untuk detail jawaban.

Kemudian, detail jawaban per soal disimpan ke tabel `mental_health_jawaban_details`. Daripada melakukan 38 kali insert (yang sangat lambat), sistem menggunakan bulk insert dengan method `insert()`. Semua 38 record disiapkan dalam array, kemudian di-insert sekaligus dalam satu query. Ini jauh lebih efisien dan cepat.

Setelah semua data tersimpan, sistem melakukan cache invalidation untuk semua cache yang terkait:
- Cache statistik admin (user stats, kategori counts, total tes, fakultas stats)
- Cache riwayat tes mahasiswa yang bersangkutan

Ini memastikan bahwa statistik yang ditampilkan di dashboard admin dan dashboard user selalu up-to-date.

Mahasiswa kemudian diarahkan ke halaman hasil yang menampilkan skor total, kategori, dan interpretasi dari hasil tes mereka. Halaman ini mengambil data hasil kuesioner terbaru berdasarkan NIM dari session.

### 5. Melihat Hasil Kuesioner

Method `showLatest()` di `HasilKuesionerController` menangani penampilan hasil kuesioner. Method ini mengambil NIM, nama, dan program studi dari session yang sudah disimpan saat mahasiswa mengisi data diri.

Jika session tidak ditemukan (misalnya karena session expired atau mahasiswa membuka halaman hasil langsung tanpa melalui alur normal), mahasiswa akan diarahkan kembali ke halaman kuesioner dengan pesan error.

Sistem kemudian melakukan query untuk mengambil hasil kuesioner terbaru berdasarkan NIM menggunakan method `latest()`. Method ini otomatis mengurutkan berdasarkan kolom `created_at` descending dan mengambil record pertama.

Jika tidak ada hasil ditemukan (mahasiswa belum pernah mengisi kuesioner), mereka akan diarahkan kembali ke halaman kuesioner dengan pesan error yang sesuai.

Halaman hasil menampilkan informasi lengkap: nama mahasiswa, program studi, total skor, kategori kesehatan mental, dan interpretasi hasil. Interpretasi biasanya mencakup penjelasan tentang arti kategori tersebut dan saran tindak lanjut jika diperlukan.

---

## Alur Kerja Sistem untuk Administrator

### 1. Login Admin

Administrator memiliki alur login yang berbeda dari mahasiswa. Jika mahasiswa menggunakan Google OAuth, admin menggunakan sistem login tradisional dengan email dan password. Ini ditangani oleh `AdminAuthController`.

Method `showLoginForm()` menampilkan halaman login sederhana dengan dua field: email dan password. Session title di-set untuk keperluan tampilan di view.

Ketika admin submit form login, method `login()` akan memvalidasi input dengan aturan:
- Email harus ada dan formatnya valid
- Password harus ada dan berupa string

Perbedaan krusial antara login admin dan user terletak pada penggunaan guard. Laravel memiliki konsep guard yang memungkinkan multiple authentication system dalam satu aplikasi. Admin menggunakan guard 'admin' yang dikonfigurasi untuk mengakses tabel `admins` di database, sementara mahasiswa menggunakan guard default yang mengakses tabel `users`.

Method `attempt()` pada guard admin akan mengecek kredensial di database tabel admins. Jika email dan password cocok, session akan di-regenerate untuk keamanan (mencegah session fixation attack) dan admin diarahkan ke dashboard admin.

Jika login gagal, admin dikembalikan ke halaman login dengan pesan error "Email atau password salah!". Email yang diinput tetap di-maintain menggunakan `withInput()` sehingga admin tidak perlu mengetik ulang.

Method `logout()` menangani proses logout dengan memanggil `logout()` pada guard admin, menginvalidate session, dan me-regenerate CSRF token untuk keamanan. Admin kemudian diarahkan kembali ke halaman login dengan pesan sukses.

Yang menarik adalah implementasi middleware `AdminAuth` yang tidak hanya mengecek apakah admin sudah login, tapi juga mengimplementasikan auto logout setelah 30 menit tidak ada aktivitas.

Middleware ini menyimpan timestamp aktivitas terakhir admin di session dengan key `last_activity_admin`. Setiap kali admin melakukan request (membuka halaman, klik tombol, dll), middleware akan:
1. Mengecek apakah admin sudah login
2. Mengambil timestamp aktivitas terakhir dari session
3. Menghitung selisih waktu antara sekarang dengan aktivitas terakhir
4. Jika selisih lebih dari 30 menit, logout admin otomatis
5. Jika masih dalam 30 menit, update timestamp aktivitas terakhir

Implementasi auto logout ini sangat penting untuk keamanan, terutama jika admin mengakses sistem dari komputer publik atau lupa logout. Setelah 30 menit tidak ada aktivitas, session otomatis berakhir dan admin harus login ulang.

### 2. Dashboard Admin - Manajemen Data Mental Health

Dashboard admin adalah bagian paling kompleks dari sistem, ditangani oleh `HasilKuesionerCombinedController::index()`. Method ini menggabungkan beberapa fitur penting: pagination, search, filter, sorting, dan statistik global.

**Query Utama dan Optimasi**

Query utama menggunakan teknik yang sangat dioptimasi untuk performa. Pertama, sistem membuat subquery untuk mendapatkan ID hasil kuesioner terakhir dari setiap mahasiswa. Ini penting karena dashboard admin hanya menampilkan hasil tes terbaru, bukan semua tes yang pernah dilakukan.

Subquery menggunakan `MAX(id) GROUP BY nim`, yang akan menghasilkan ID terbesar (terbaru) untuk setiap NIM. Subquery ini kemudian di-join dengan tabel utama menggunakan `joinSub()`, teknik yang sangat efisien di Laravel untuk join dengan subquery.

Query kemudian di-join dengan tabel `data_diris` untuk mendapatkan informasi mahasiswa seperti nama, fakultas, program studi, dll. Join ini sangat penting karena banyak fitur search dan filter yang bergantung pada data di tabel ini.

Yang menarik adalah implementasi COUNT untuk menghitung jumlah tes per mahasiswa tanpa menyebabkan N+1 problem. Daripada melakukan query terpisah untuk setiap mahasiswa (yang akan sangat lambat), sistem menggunakan LEFT JOIN dengan alias `hk_count` dan `COUNT() GROUP BY`. Ini menghasilkan hitungan dalam satu query yang sama.

**Fitur Search Multi-Kolom**

Fitur search sangat powerful dan fleksibel. Admin bisa search berdasarkan berbagai kolom sekaligus, dan sistem akan mencari di semua kolom yang relevan.

Sistem memecah input search menjadi beberapa term menggunakan `preg_split()`, sehingga jika admin search "John FTI", sistem akan mencari "John" DAN "FTI" (bukan "John FTI" sebagai satu string).

Search dibagi menjadi dua kategori:
1. **LIKE search** untuk kolom umum seperti NIM, nama, email, alamat, asal sekolah, status tinggal
2. **Exact match dengan transformasi** untuk kolom khusus seperti fakultas, provinsi, program studi, jenis kelamin

Untuk kolom khusus, sistem mengecek apakah search term ada dalam daftar nilai yang valid. Jika ya, sistem melakukan transformasi (uppercase untuk fakultas, ucfirst untuk provinsi) dan melakukan exact match. Jika tidak, fallback ke LIKE search.

Misalnya, jika admin search "fti", sistem akan mentransformasi menjadi "FTI" dan melakukan exact match di kolom fakultas. Tapi jika admin search "teknik", sistem akan melakukan LIKE search karena "teknik" bukan nilai valid untuk fakultas.

Implementasi ini membuat search sangat intuitif - admin tidak perlu tahu case sensitivity atau exact value, sistem akan menangani dengan cerdas.

**Fitur Filter dan Sorting**

Filter kategori sangat straightforward - admin bisa memilih satu kategori kesehatan mental dan sistem akan menampilkan hanya mahasiswa dengan kategori tersebut. Implementasi menggunakan `when()` yang hanya menambahkan WHERE clause jika parameter kategori ada.

Sorting lebih kompleks karena kolom yang di-sort bisa berasal dari tabel berbeda. Misalnya, kolom "nama" ada di tabel `data_diris`, sementara kolom lain seperti "total_skor" dan "created_at" ada di tabel `hasil_kuesioners`.

Sistem menggunakan match expression untuk mapping nama kolom sort ke nama kolom database yang lengkap (termasuk nama tabel). Ini memastikan tidak ada ambiguitas saat query dieksekusi.

Order bisa ascending atau descending, dikontrol oleh parameter `order` dari request. Default-nya adalah descending untuk `created_at`, sehingga hasil tes terbaru muncul di atas.

**Pagination**

Pagination menggunakan Laravel's built-in pagination yang sangat efisien. Parameter `limit` mengontrol berapa banyak record per halaman (default 10).

Method `withQueryString()` sangat penting untuk mempertahankan parameter search, filter, dan sort saat berpindah halaman. Tanpa ini, ketika admin pindah ke halaman 2, semua filter akan hilang.

**Statistik Global dengan Caching**

Dashboard menampilkan berbagai statistik global yang dihitung dari seluruh data:
- Total users (mahasiswa yang pernah tes)
- Total tes (semua tes yang pernah dilakukan)
- Breakdown by gender (laki-laki, perempuan)
- Breakdown by asal sekolah (SMA, SMK, Boarding School)
- Breakdown by kategori mental health
- Breakdown by fakultas

Semua statistik ini menggunakan caching dengan durasi 1 menit. Kenapa 1 menit? Karena data bisa sering berubah (mahasiswa baru mengisi tes), tapi query statistik cukup berat jika harus dijalankan setiap kali admin refresh halaman.

Dengan cache 1 menit, jika admin refresh halaman berkali-kali dalam 1 menit, statistik diambil dari cache tanpa perlu query database. Setelah 1 menit, cache expired dan statistik akan di-refresh dari database.

Query statistik sangat dioptimasi menggunakan teknik aggregation di level database. Daripada mengambil semua data dan menghitung di PHP, sistem menggunakan `COUNT DISTINCT` dan `CASE WHEN` di SQL untuk menghitung langsung di database. Ini jauh lebih cepat.

Misalnya, untuk menghitung total laki-laki dan perempuan dalam satu query:
```sql
COUNT(DISTINCT CASE WHEN jenis_kelamin = 'L' THEN data_diris.nim END) as total_laki,
COUNT(DISTINCT CASE WHEN jenis_kelamin = 'P' THEN data_diris.nim END) as total_perempuan
```

Query ini menghitung total mahasiswa laki-laki dan perempuan dalam satu pass, tanpa perlu query terpisah untuk masing-masing.

**Chart Donut untuk Asal Sekolah**

Dashboard menampilkan chart donut SVG yang menunjukkan proporsi mahasiswa berdasarkan asal sekolah. Data untuk chart ini disiapkan di backend dengan menghitung stroke-dasharray dan stroke-dashoffset untuk setiap segment.

Perhitungan menggunakan rumus lingkaran (circumference = 2πr) dan proporsi untuk menentukan panjang arc setiap segment. Offset diakumulasi untuk memastikan segment-segment tidak overlap.

Semua perhitungan ini dilakukan di backend karena lebih akurat dan mudah di-maintain dibanding menggunakan JavaScript di frontend.

**Search Message**

Sistem menampilkan pesan "Data berhasil ditemukan!" atau "Data tidak ditemukan!" setelah search, tapi hanya pada request awal, bukan saat paginasi. Ini diimplementasikan dengan mengecek apakah parameter `page` ada di request.

Jika ada parameter search tapi tidak ada parameter page (atau page = 1), berarti ini request search baru, dan pesan akan ditampilkan. Jika user kemudian klik halaman 2, 3, dst, pesan tidak akan muncul lagi untuk menghindari kebingungan.

### 3. Melihat Detail Jawaban Per Soal

Method `showDetail()` menampilkan detail jawaban kuesioner per soal dari satu mahasiswa tertentu. Halaman ini sangat berguna untuk admin yang ingin melihat pola jawaban mahasiswa secara detail.

Method ini menggunakan eager loading untuk menghindari N+1 problem. Relasi yang di-load adalah:
- `dataDiri`: informasi mahasiswa
- `jawabanDetails`: detail jawaban per soal (38 record), diurutkan berdasarkan nomor soal
- `riwayatKeluhans`: riwayat keluhan terbaru (limit 1)

Eager loading ini sangat penting untuk performa. Tanpa eager loading, jika view mengakses relasi, Laravel akan melakukan query terpisah untuk setiap relasi (N+1 problem). Dengan eager loading, semua data diambil dalam jumlah query yang minimal.

Array `$questions` berisi semua 38 pertanyaan MHI-38 lengkap. Array ini hard-coded karena pertanyaan MHI-38 adalah standar dan tidak akan berubah.

Array `$negativeQuestions` berisi nomor-nomor pertanyaan yang termasuk Psychological Distress (pertanyaan negatif). Ini digunakan di view untuk memberikan marking visual pada pertanyaan-pertanyaan tersebut, memudahkan admin memahami struktur kuesioner.

View akan menampilkan tabel dengan kolom: nomor soal, pertanyaan, jawaban mahasiswa, dan marking apakah pertanyaan tersebut positif atau negatif. Admin bisa melihat pola jawaban dan mengidentifikasi area yang mungkin perlu perhatian khusus.

### 4. Menghapus Data Mahasiswa

Method `destroy()` menangani penghapusan data mahasiswa secara menyeluruh. Berbeda dengan soft delete yang hanya menandai data sebagai deleted, method ini melakukan hard delete yang permanen.

Yang menarik adalah scope penghapusan - bukan hanya satu hasil kuesioner yang dihapus, tapi SEMUA data terkait mahasiswa tersebut:
- Semua hasil kuesioner (mungkin lebih dari satu jika mahasiswa pernah tes berkali-kali)
- Semua detail jawaban kuesioner
- Semua riwayat keluhan
- Data diri mahasiswa
- Record user di tabel users

Implementasi menggunakan database transaction untuk memastikan atomicity - semua data terhapus atau tidak sama sekali. Jika terjadi error di tengah proses (misalnya koneksi database terputus), semua penghapusan akan di-rollback.

Urutan penghapusan penting untuk menghindari foreign key constraint error:
1. Hapus hasil kuesioner (dan detail jawaban akan terhapus otomatis jika ada cascade)
2. Hapus riwayat keluhan
3. Hapus data diri
4. Hapus user

Setelah penghapusan berhasil, sistem melakukan cache invalidation untuk SEMUA cache yang mungkin terpengaruh:
- Statistik admin (user stats, kategori counts, total tes, fakultas stats)
- Riwayat tes mahasiswa yang bersangkutan

Admin kemudian mendapat pesan sukses yang menunjukkan NIM mahasiswa yang berhasil dihapus.

Jika terjadi error, transaction di-rollback dan admin mendapat pesan error yang mencakup detail exception. Ini membantu debugging jika terjadi masalah.

### 5. Export Data ke Excel

Method `exportExcel()` memungkinkan admin untuk export semua data hasil kuesioner ke file Excel. Yang menarik adalah export ini mempertahankan semua filter dan sorting yang sedang aktif di dashboard.

Jika admin sedang filter kategori "Perlu Dukungan" dan sort by nama ascending, file Excel yang di-export akan berisi data yang sama persis dengan yang ditampilkan di dashboard (tapi tanpa pagination - semua data akan di-export).

Implementasi menggunakan Laravel Excel (Maatwebsite Excel) yang merupakan wrapper powerful untuk PhpSpreadsheet. Parameter filter dan sort dikirim ke class `HasilKuesionerExport` yang akan melakukan query dan formatting.

Nama file Excel otomatis include timestamp dengan timezone Jakarta, sehingga admin bisa membedakan export dari waktu berbeda. Format nama file: `hasil-kuesioner-2025-11-21_14-30.xlsx`.

Method ini langsung trigger download file, sehingga admin tidak perlu navigasi ke halaman lain. File akan langsung terdownload ke browser dengan nama yang sudah digenerate.

---

## Database Models dan Relasi

### Model HasilKuesioner

Model `HasilKuesioner` merepresentasikan tabel `hasil_kuesioners` yang menyimpan hasil tes mental health. Field yang fillable adalah `nim`, `total_skor`, `kategori`, `created_at`, dan `updated_at`.

Model ini memiliki tiga relasi penting:

**Relasi belongsTo dengan DataDiris**: Setiap hasil kuesioner dimiliki oleh satu mahasiswa. Relasi ini menggunakan kolom `nim` sebagai foreign key di kedua tabel. Dengan relasi ini, kita bisa mengakses data diri mahasiswa dari hasil kuesioner dengan `$hasil->dataDiri`.

**Relasi hasMany dengan RiwayatKeluhans**: Setiap hasil kuesioner bisa terkait dengan banyak riwayat keluhan. Relasi ini juga menggunakan `nim` sebagai foreign key. Dalam prakteknya, kita biasanya hanya mengambil keluhan terbaru yang waktu pembuatannya sebelum tes dilakukan.

**Relasi hasMany dengan MentalHealthJawabanDetail**: Setiap hasil kuesioner memiliki 38 detail jawaban (satu untuk setiap pertanyaan). Relasi ini menggunakan `hasil_kuesioner_id` sebagai foreign key. Relasi ini sangat penting untuk menampilkan detail jawaban per soal.

### Model MentalHealthJawabanDetail

Model `MentalHealthJawabanDetail` merepresentasikan tabel `mental_health_jawaban_details` yang menyimpan detail jawaban per soal. Field yang fillable adalah `hasil_kuesioner_id`, `nomor_soal`, dan `skor`.

Model ini memiliki satu relasi belongsTo dengan `HasilKuesioner`. Dengan relasi ini, kita bisa mengakses hasil kuesioner dari detail jawaban dengan `$jawaban->hasilKuesioner`.

### Struktur Database

Database dirancang dengan normalisasi yang baik untuk menghindari redundansi dan menjaga konsistensi data.

**Tabel users** menyimpan informasi login user (mahasiswa). Tabel ini berisi NIM sebagai primary key, nama, email, Google ID, dan password. Tabel ini terpisah dari data diri karena concern-nya berbeda - users untuk autentikasi, data_diris untuk informasi demografis.

**Tabel admins** menyimpan informasi login admin, terpisah dari users. Struktur mirip tapi tidak memerlukan Google ID karena admin login dengan email/password biasa.

**Tabel data_diris** menyimpan informasi demografis dan akademik mahasiswa. Tabel ini sangat penting karena banyak field yang digunakan untuk statistik dan filtering di dashboard admin.

**Tabel hasil_kuesioners** menyimpan header informasi hasil tes: siapa yang tes (NIM), total skor, kategori, dan kapan tes dilakukan. Tabel ini relatif kecil karena hanya menyimpan informasi summary.

**Tabel mental_health_jawaban_details** menyimpan detail jawaban per soal. Tabel ini bisa cukup besar karena setiap tes menghasilkan 38 record. Desain ini dipilih untuk fleksibilitas - kita bisa dengan mudah query jawaban per soal, hitung statistik per pertanyaan, dll.

**Tabel riwayat_keluhans** menyimpan riwayat keluhan mahasiswa. Tabel ini didesain untuk menyimpan riwayat, bukan hanya data terbaru. Setiap kali mahasiswa mengisi form data diri sebelum tes, entri baru dibuat. Ini memungkinkan kita melacak perubahan keluhan dari waktu ke waktu.

---

## Optimasi Performa

### Query Optimization

Sistem mengimplementasikan berbagai teknik optimasi query untuk memastikan performa tetap cepat meskipun data bertambah banyak.

**Eager Loading** digunakan secara konsisten di seluruh aplikasi untuk menghindari N+1 problem. Setiap kali kita perlu mengakses relasi, relasi tersebut di-load di awal menggunakan method `with()`.

Misalnya, di dashboard admin, kita perlu menampilkan nama mahasiswa untuk setiap hasil kuesioner. Tanpa eager loading, Laravel akan melakukan query terpisah untuk setiap hasil kuesioner untuk mendapatkan nama mahasiswa. Jika ada 100 hasil, akan ada 101 query (1 untuk hasil kuesioner, 100 untuk nama mahasiswa).

Dengan eager loading `with('dataDiri')`, Laravel melakukan JOIN atau subquery yang mengambil semua data sekaligus dalam 2-3 query saja. Perbedaan performa sangat signifikan.

**Selective Column Selection** digunakan untuk mengurangi data yang ditransfer dari database. Daripada menggunakan `SELECT *` yang mengambil semua kolom, kita hanya mengambil kolom yang benar-benar digunakan dengan method `select()`.

Ini sangat penting terutama untuk tabel yang memiliki banyak kolom atau kolom dengan data besar (text, blob). Mengurangi data yang ditransfer bisa meningkatkan performa secara signifikan, terutama jika koneksi database lambat.

**Bulk Insert** digunakan saat menyimpan detail jawaban kuesioner. Daripada melakukan 38 kali insert individual (yang memerlukan 38 round-trip ke database), semua data disiapkan dalam array dan di-insert sekaligus dengan satu query.

Bulk insert bisa 10-100x lebih cepat dibanding individual insert, terutama jika database berada di server terpisah dengan latency tinggi.

**Subquery Optimization** digunakan untuk query kompleks seperti mendapatkan hasil kuesioner terbaru per mahasiswa. Daripada melakukan query terpisah atau loop di PHP, semua logika dilakukan di level database menggunakan subquery.

Database engine biasanya jauh lebih efisien dalam melakukan agregasi dan filtering dibanding PHP, terutama untuk dataset besar.

**Index pada Foreign Keys** sangat penting untuk performa JOIN. Semua kolom yang digunakan untuk JOIN (seperti `nim`, `hasil_kuesioner_id`) harus di-index untuk performa optimal.

Tanpa index, database harus melakukan full table scan untuk setiap JOIN, yang sangat lambat untuk tabel besar. Dengan index, lookup bisa dilakukan dalam waktu logaritmik.

### Caching Strategy

Sistem mengimplementasikan caching untuk data yang sering diakses tapi jarang berubah.

**Cache Duration** dipilih berdasarkan frekuensi update data:
- Statistik admin: 1 menit (data bisa sering berubah karena mahasiswa mengisi tes)
- Riwayat tes user: 5 menit (lebih jarang berubah, hanya saat user itu sendiri mengisi tes baru)

Duration ini adalah trade-off antara performa dan freshness. Terlalu lama, data bisa stale. Terlalu pendek, benefit caching minimal.

**Cache Invalidation** dilakukan secara proaktif saat data berubah. Setiap kali ada operasi yang mengubah data (create, update, delete), cache yang relevan di-invalidate.

Misalnya, saat mahasiswa mengisi tes baru:
- Cache statistik admin di-invalidate (karena total tes, kategori counts, dll berubah)
- Cache riwayat tes mahasiswa tersebut di-invalidate

Ini memastikan data yang ditampilkan selalu konsisten dengan database, meskipun menggunakan cache.

**Per-User Caching** diimplementasikan untuk data yang spesifik per user. Cache key include user ID/NIM, sehingga setiap user memiliki cache terpisah.

Ini sangat penting untuk aplikasi multi-user. Tanpa per-user caching, semua user akan share cache yang sama, yang bisa menyebabkan kebocoran data atau data yang salah.

**Cache Driver** yang digunakan bisa dikonfigurasi di `.env`. Untuk development, biasanya menggunakan file driver yang simple. Untuk production, disarankan menggunakan Redis atau Memcached untuk performa optimal dan shared cache antar server (jika ada load balancer).

### Database Transaction

Database transaction digunakan secara konsisten untuk operasi yang melibatkan multiple tables atau multiple queries.

**Atomicity** dijamin dengan transaction - semua operasi berhasil atau semua gagal. Tidak ada kemungkinan data tersimpan sebagian.

Misalnya, saat menyimpan hasil kuesioner, kita menyimpan ke dua tabel: `hasil_kuesioners` dan `mental_health_jawaban_details`. Jika penyimpanan ke tabel pertama berhasil tapi ke tabel kedua gagal (misalnya karena disk penuh), kita tidak ingin data di tabel pertama tersimpan. Transaction memastikan kedua penyimpanan berhasil atau tidak sama sekali.

**Error Handling** di dalam transaction block menggunakan try-catch. Jika terjadi exception, transaction di-rollback dan error di-handle dengan baik (redirect dengan pesan error).

**Isolation Level** menggunakan default Laravel (biasanya READ COMMITTED), yang cukup untuk sebagian besar use case. Untuk requirement yang lebih strict, bisa ditingkatkan ke REPEATABLE READ atau SERIALIZABLE.

---

## Security Considerations

### Authentication & Authorization

Sistem mengimplementasikan authentication berlapis untuk user dan admin.

**Google OAuth untuk User** memberikan keamanan yang baik karena:
- Password tidak disimpan di aplikasi kita
- Google menangani all security measures (2FA, breach detection, dll)
- Token OAuth bisa di-revoke kapan saja
- Validasi email mahasiswa ITERA memastikan hanya mahasiswa yang bisa akses

**Email/Password untuk Admin** menggunakan bcrypt hashing yang sangat aman:
- Password tidak pernah disimpan dalam plain text
- Bcrypt secara otomatis menggunakan salt yang unik untuk setiap password
- Bcrypt adalah slow hash function yang resistant terhadap brute force attack
- Laravel secara otomatis menggunakan cost factor yang tepat

**Guard Separation** memastikan admin dan user session terpisah:
- Admin login tidak bisa digunakan untuk akses fitur user, dan sebaliknya
- Credentials di-check dari tabel yang berbeda
- Session cookie bisa dikonfigurasi berbeda (misalnya admin cookie lebih secure)

**Middleware Protection** memastikan hanya user yang terautentikasi yang bisa akses fitur tertentu:
- Routes public tidak memerlukan middleware
- Routes user protected dengan middleware `auth`
- Routes admin protected dengan middleware `AdminAuth`
- Middleware juga bisa di-chain untuk proteksi berlapis

### Session Management

Sistem mengimplementasikan session security best practices.

**Session Regeneration** dilakukan setelah login untuk mencegah session fixation attack. Attacker tidak bisa menyiapkan session ID dan memaksa user untuk login dengan session ID tersebut.

**Session Invalidation** dilakukan saat logout, memastikan session benar-benar berakhir dan tidak bisa digunakan lagi.

**CSRF Token Regeneration** dilakukan saat logout untuk mencegah CSRF attack yang menggunakan token lama.

**Auto Logout** diimplementasikan untuk admin setelah 30 menit tidak ada aktivitas. Ini sangat penting untuk mencegah unauthorized access jika admin lupa logout dari komputer publik.

**Session Timeout** bisa dikonfigurasi di Laravel config. Default biasanya 120 menit (2 jam), tapi bisa disesuaikan dengan kebutuhan.

### Input Validation

Sistem menggunakan Form Request untuk validasi input secara konsisten dan aman.

**Whitelist Validation** digunakan - kita explicitly mendefinisikan field mana yang valid dan aturannya. Field yang tidak didefinisikan akan diabaikan, mencegah mass assignment vulnerability.

**Type Validation** memastikan data memiliki type yang benar (string, integer, email, dll). Ini mencegah type juggling attack dan memastikan data consistency.

**Range Validation** memastikan nilai dalam range yang valid (misalnya jawaban kuesioner antara 0-6). Ini mencegah data yang tidak masuk akal tersimpan di database.

**XSS Prevention** dilakukan otomatis oleh Blade templating engine. Semua output di-escape secara default, mencegah injection of malicious script.

**SQL Injection Prevention** dilakukan otomatis oleh Query Builder dan Eloquent. Semua parameter di-bind dengan prepared statement, bukan di-concatenate ke query string.

**CSRF Protection** diimplementasikan otomatis oleh Laravel untuk semua POST/PUT/DELETE request. Setiap form harus include `@csrf` token, dan request tanpa token yang valid akan ditolak.

### Data Privacy

Sistem memperhatikan privacy data mahasiswa yang sensitif.

**Password Hashing** menggunakan bcrypt yang irreversible. Password tidak bisa di-decrypt, hanya bisa di-verify.

**Soft Delete vs Hard Delete** - sistem menggunakan hard delete untuk flexibility, tapi dalam production mungkin lebih baik menggunakan soft delete untuk audit trail.

**Access Control** memastikan mahasiswa hanya bisa melihat data mereka sendiri, tidak bisa akses data mahasiswa lain. Admin bisa akses semua data tapi dengan autentikasi yang ketat.

**Audit Trail** bisa diimplementasikan untuk melacak siapa mengakses/mengubah data kapan. Ini penting untuk compliance dengan regulasi privacy data.

---

## Kesimpulan

Sistem Mental Health Assessment ini dibangun dengan memperhatikan berbagai aspek penting: fungsionalitas yang lengkap, performa yang optimal, keamanan yang ketat, dan maintainability yang baik.

Arsitektur backend menggunakan best practices Laravel, dengan separation of concerns yang jelas antara routing, controller, model, dan validation. Setiap komponen memiliki tanggung jawab yang spesifik dan tidak overlap.

Optimasi performa dilakukan di berbagai layer: database query optimization, eager loading, caching, dan bulk operations. Ini memastikan sistem tetap responsive meskipun data bertambah banyak.

Keamanan diimplementasikan dengan serius, dari autentikasi berlapis, session management yang aman, input validation yang ketat, hingga protection terhadap common vulnerabilities seperti XSS, SQL injection, dan CSRF.

Code quality dijaga dengan menggunakan Form Request untuk validation, database transaction untuk data integrity, dan error handling yang comprehensive. Dokumentasi dan comment yang baik memudahkan maintenance dan development selanjutnya.

Sistem ini siap untuk digunakan dalam environment production dengan beberapa penyesuaian konfigurasi seperti cache driver, session storage, dan database optimization. Monitoring dan logging juga perlu ditambahkan untuk production readiness.

---

**Dokumentasi dibuat pada:** 21 November 2025
**Versi:** 1.0
**Platform:** Laravel 10.x
**Database:** MySQL 8.x
**PHP Version:** 8.1+
