# Kebutuhan Fungsional - Assessment Online ITERA

**Platform Asesmen Kesehatan Mental Mahasiswa ITERA**
**Tanggal Dibuat:** 11 November 2025
**Update Terakhir:** 13 November 2025
**Versi:** 1.1

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Kebutuhan Fungsional Sistem](#2-kebutuhan-fungsional-sistem)
3. [Kebutuhan Fungsional User (Mahasiswa)](#3-kebutuhan-fungsional-user-mahasiswa)
4. [Kebutuhan Fungsional Admin (Administrator)](#4-kebutuhan-fungsional-admin-administrator)
5. [Kebutuhan Non-Fungsional](#5-kebutuhan-non-fungsional)
6. [Batasan dan Asumsi](#6-batasan-dan-asumsi)

---

## 1. Pendahuluan

### 1.1 Tujuan Sistem
Sistem Assessment Online ITERA menyediakan platform untuk evaluasi kesehatan mental mahasiswa menggunakan instrumen Mental Health Inventory (MHI-38) dengan 38 pertanyaan tervalidasi.

### 1.2 Ruang Lingkup
Dokumen ini menjelaskan kebutuhan fungsional untuk:
- **Sistem** (fungsi otomatis yang dijalankan sistem)
- **User/Mahasiswa** (fitur yang dapat digunakan mahasiswa)
- **Admin/Administrator** (fitur yang dapat digunakan administrator)

### 1.3 Pengguna Sistem
- **Mahasiswa ITERA**: Mengisi kuesioner kesehatan mental dan melihat hasil pribadi
- **Administrator**: Mengelola data mahasiswa dan melihat statistik keseluruhan

### 1.4 Tentang MHI-38

**Instrumen Penilaian:**
- Total pertanyaan: **38 pertanyaan**
- Skala jawaban: **1-6** (Tidak pernah → Selalu)
- Range skor: **38 - 228**

**Kategori Hasil:**

| Skor | Kategori | Interpretasi |
|------|----------|--------------|
| 190-228 | Sangat Sehat | Kondisi mental sangat baik |
| 152-189 | Sehat | Kondisi mental baik |
| 114-151 | Cukup Sehat | Perlu perhatian lebih |
| 76-113 | Perlu Dukungan | Disarankan konsultasi konselor |
| 38-75 | Perlu Dukungan Intensif | Sangat disarankan konsultasi segera |

---

## 2. Kebutuhan Fungsional Sistem

Kebutuhan fungsional sistem adalah fungsi-fungsi yang dijalankan secara otomatis oleh sistem tanpa intervensi langsung dari user atau admin.

---

### 2.1 Autentikasi Google OAuth

**Deskripsi:**
Sistem harus dapat melakukan autentikasi mahasiswa melalui Google OAuth 2.0. Sistem akan memvalidasi bahwa email yang digunakan adalah email mahasiswa ITERA dengan format `{NIM}@student.itera.ac.id` menggunakan regular expression `/(\d{9})@student\.itera\.ac\.id$/`. Sistem akan mengekstrak NIM dari 9 digit pertama email. Proses autentikasi melibatkan redirect ke Google OAuth, menerima callback dari Google dengan user data, memvalidasi state token untuk keamanan, dan membuat session untuk mahasiswa yang berhasil login.

---

### 2.2 Pembuatan Akun Mahasiswa Otomatis

**Deskripsi:**
Sistem harus otomatis membuat atau memperbarui akun mahasiswa saat pertama kali login melalui Google OAuth menggunakan metode `updateOrCreate`. Data yang disimpan meliputi NIM (sebagai primary key), nama lengkap dari Google, email, Google ID untuk tracking, dan password ter-hash random sebagai placeholder. Jika mahasiswa dengan NIM yang sama sudah ada di database, sistem akan memperbarui data (nama, email, google_id) tanpa membuat duplikat record. Semua operasi dilakukan dalam satu transaksi database.

---

### 2.3 Pembuatan Data Diri Awal Mahasiswa

**Deskripsi:**
Sistem harus otomatis membuat data diri awal mahasiswa saat pertama kali login menggunakan metode `firstOrCreate`. Data awal hanya berisi NIM, nama dari Google, dan email dari Google. Data diri ini berfungsi sebagai template yang nantinya dapat dilengkapi oleh mahasiswa. Jika data diri untuk NIM tersebut sudah ada, sistem tidak akan menimpa atau mengubah data yang sudah diisi mahasiswa. Sistem hanya membuat data baru jika belum ada record dengan NIM tersebut.

---

### 2.4 Manajemen Session

**Deskripsi:**
Sistem harus mengelola session pengguna dengan menyimpannya di database menggunakan session driver database Laravel. Session mahasiswa berlaku selama 120 menit tanpa mekanisme auto-logout, artinya session akan expired secara otomatis setelah 120 menit sejak login atau aktivitas terakhir. Session administrator juga berlaku 120 menit, tetapi dengan tambahan mekanisme auto-logout melalui middleware jika tidak ada aktivitas selama 30 menit berturut-turut. Sistem harus meregenerasi session ID setiap kali login berhasil menggunakan `$request->session()->regenerate()` untuk mencegah session fixation attack. Data session disimpan di tabel `sessions` dengan kolom: id, user_id, payload, last_activity, ip_address, dan user_agent.

---

### 2.5 Validasi Email Mahasiswa ITERA

**Deskripsi:**
Sistem harus memvalidasi format email mahasiswa menggunakan regular expression untuk memastikan email sesuai dengan format ITERA. Pattern yang digunakan: `/(\d{9})@student\.itera\.ac\.id$/` yang akan mencocokkan 9 digit angka di awal email diikuti dengan domain @student.itera.ac.id. Sistem akan mengekstrak NIM dari matched group pertama dalam regex. Email yang tidak sesuai format akan ditolak dengan pesan error "Login gagal. Pastikan Anda menggunakan email mahasiswa ITERA yang valid." dan user akan di-redirect kembali ke halaman login. Contoh email valid: `121450123@student.itera.ac.id` (NIM: 121450123). Contoh email invalid: `john@gmail.com`, `admin@itera.ac.id`, `12345@student.itera.ac.id` (kurang dari 9 digit).

---

### 2.6 Password Hashing

**Deskripsi:**
Sistem harus menyimpan semua password dalam bentuk hash menggunakan algoritma bcrypt dengan cost factor 10 (default Laravel). Untuk password administrator, hash dibuat saat registrasi admin atau update password menggunakan fungsi `bcrypt()` atau `Hash::make()`. Untuk password mahasiswa yang login via OAuth, sistem akan membuat random password menggunakan `Str::random(16)` kemudian di-hash dengan bcrypt sebagai placeholder (password ini tidak digunakan karena mahasiswa login via OAuth). Verifikasi password dilakukan menggunakan `Hash::check()` atau `Auth::attempt()`. Sistem tidak boleh pernah menyimpan password dalam bentuk plain text di database atau log.

---

### 2.7 Proteksi CSRF (Cross-Site Request Forgery)

**Deskripsi:**
Sistem harus melindungi semua form dan request POST/DELETE dengan token CSRF menggunakan middleware `VerifyCsrfToken` bawaan Laravel. Token CSRF akan di-embed di setiap form menggunakan directive Blade `@csrf` dan divalidasi secara otomatis di sisi server untuk setiap request. Token CSRF akan di-regenerate setiap kali terjadi login atau logout menggunakan `$request->session()->regenerateToken()` untuk meningkatkan keamanan. Request yang tidak memiliki token CSRF yang valid atau token yang sudah expired akan ditolak dengan HTTP error 419 "Page Expired". User akan diminta untuk refresh halaman dan submit ulang.

---

### 2.8 Session Timeout untuk Admin

**Deskripsi:**
Sistem harus mengimplementasikan session timeout khusus untuk administrator melalui middleware `AdminAuth`. Setiap HTTP request admin, middleware akan mengecek timestamp aktivitas terakhir yang disimpan di session dengan key `last_activity_admin`. Sistem akan menghitung selisih waktu antara waktu sekarang dengan `last_activity_admin` menggunakan `Carbon::diffInMinutes()`. Jika selisih waktu lebih dari 30 menit, sistem akan otomatis logout admin menggunakan `Auth::guard('admin')->logout()`, menghapus `last_activity_admin` dari session, dan redirect ke halaman login dengan pesan "Sesi Anda telah habis karena tidak ada aktivitas selama 30 menit". Jika selisih waktu 30 menit atau kurang, sistem akan memperbarui `last_activity_admin` dengan waktu sekarang menggunakan `Carbon::now()` dan melanjutkan request. Setiap aktivitas admin (klik, scroll, request) akan mereset timer 30 menit.

---

### 2.9 Perhitungan Skor Kuesioner

**Deskripsi:**
Sistem harus menghitung total skor kuesioner dengan menjumlahkan semua jawaban dari 38 pertanyaan. Perhitungan dilakukan dengan iterasi loop untuk menjumlahkan nilai dari `q1` hingga `q38` yang diterima dari form submission. Formula: `Total Skor = q1 + q2 + q3 + ... + q38`. Range skor yang valid adalah 38 (semua jawaban 1) hingga 228 (semua jawaban 6). Setelah total skor dihitung, hasil disimpan ke variabel `$totalSkor`. Perhitungan dilakukan di server side di controller `HasilKuesionerController` method `store` untuk keamanan dan akurasi.

---

### 2.10 Penentuan Kategori Kesehatan Mental

**Deskripsi:**
Sistem harus menentukan kategori kesehatan mental mahasiswa berdasarkan total skor yang telah dihitung. Sistem menggunakan conditional logic (if-else) untuk mencocokkan total skor dengan range kategori. Logika: jika total skor >= 190 maka kategori "Sangat Sehat", jika >= 152 maka "Sehat", jika >= 114 maka "Cukup Sehat", jika >= 76 maka "Perlu Dukungan", selain itu (38-75) "Perlu Dukungan Intensif". Kategori yang ditentukan disimpan bersama total skor ke tabel `hasil_kuesioners` dengan timestamp `created_at` untuk tracking kapan tes dikerjakan. Kategori ini akan digunakan untuk filter, statistik, dan rekomendasi.

---

### 2.11 Caching Data

**Deskripsi:**
Sistem harus mengimplementasikan caching untuk meningkatkan performa dan mengurangi beban query database. User dashboard di-cache dengan key `user_dashboard_{nim}` dan TTL (Time To Live) 5 menit (300 detik). Admin statistik di-cache dengan key `admin_mental_health_stats` dan TTL 1 menit (60 detik). Sistem menggunakan `Cache::remember()` untuk otomatis menyimpan hasil query jika cache miss atau mengambil dari cache jika cache hit. Cache akan otomatis di-invalidate (dihapus) menggunakan `Cache::forget()` ketika ada perubahan data yang mempengaruhi hasil cache, yaitu: saat mahasiswa submit kuesioner baru atau saat administrator hapus data mahasiswa. Cache driver yang digunakan adalah database sesuai konfigurasi di `config/cache.php`.

---

### 2.12 Database Indexing

**Deskripsi:**
Sistem harus memiliki index database pada kolom-kolom yang sering digunakan dalam query WHERE, JOIN, dan ORDER BY untuk meningkatkan kecepatan query. Index pada tabel `hasil_kuesioners`: single index untuk `nim`, `kategori`, `created_at`, dan composite index untuk `(kategori, created_at)` serta `(nim, created_at)`. Index pada tabel `data_diris`: single index untuk `nama`, `fakultas`, `program_studi`, `jenis_kelamin`, `email`, dan composite index untuk `(fakultas, program_studi)`. Index pada tabel `riwayat_keluhans`: single index untuk `nim`, `pernah_konsul`, `pernah_tes`, `created_at`. Implementasi indexing menggunakan migration Laravel dengan method `$table->index()`. Index akan dibuat saat migration dijalankan dengan `php artisan migrate`. Indexing terbukti meningkatkan performa dari 150+ queries menjadi 3 queries dan load time dari 3.5 detik menjadi 0.18 detik (improvement 94%).

---

### 2.13 Validasi Input Form

**Deskripsi:**
Sistem harus memvalidasi semua input dari user di sisi server menggunakan Laravel Form Request Validation atau `$request->validate()` method. Validasi untuk form data diri meliputi: NIM (required, string, max:20), nama (required, string, max:255), jenis_kelamin (required, in:L,P), provinsi (required, string, max:100), alamat (required, string), usia (required, numeric, min:1, max:100), fakultas (required, string, max:100), program_studi (required, string, max:100), asal_sekolah (required, string, max:255), status_tinggal (required, string, max:50), email (required, email). Validasi untuk kuesioner memastikan 38 field (q1 hingga q38) sudah terisi semua dengan aturan `required|integer|min:1|max:6`. Jika validasi gagal, sistem akan otomatis redirect kembali ke form dengan pesan error yang jelas dan spesifik untuk setiap field menggunakan `$errors` variable di Blade. Old input akan di-preserve menggunakan `old()` helper agar user tidak perlu mengisi ulang dari awal.

---

### 2.14 Relasi Database dan Data Integrity

**Deskripsi:**
Sistem harus memiliki relasi database yang jelas dan konsisten untuk menjaga integritas data menggunakan foreign key constraints. Relasi yang ada: tabel `users` berelasi one-to-one dengan `data_diris` melalui kolom `nim`, tabel `data_diris` berelasi one-to-many dengan `riwayat_keluhans` melalui `nim`, tabel `data_diris` berelasi one-to-many dengan `hasil_kuesioners` melalui `nim`. Foreign key menggunakan constraint `ON DELETE CASCADE` sehingga saat data diri mahasiswa dihapus, semua riwayat keluhan dan hasil kuesioner terkait akan otomatis terhapus (cascade delete). Model Eloquent mendefinisikan relasi menggunakan method `hasMany()`, `belongsTo()`, dan `hasOne()` untuk memudahkan query dengan eager loading menggunakan `with()`. Sistem tidak mengizinkan orphan records (data anak tanpa parent).

---

### 2.15 Cascade Delete

**Deskripsi:**
Sistem harus melakukan cascade delete saat administrator menghapus data mahasiswa untuk menjaga konsistensi database. Proses cascade delete berjalan otomatis karena foreign key constraint `ON DELETE CASCADE` di level database. Urutan penghapusan (otomatis oleh database): pertama hapus semua record di `riwayat_keluhans` dengan nim yang sama, kedua hapus semua record di `hasil_kuesioners` dengan nim yang sama, ketiga hapus record di `data_diris` dengan nim tersebut, terakhir hapus record di `users` dengan nim tersebut. Semua penghapusan dilakukan dalam satu transaksi database sehingga jika ada error di tengah proses, semua perubahan akan di-rollback. Sistem juga akan meng-invalidate cache terkait setelah cascade delete berhasil.

---

## 3. Kebutuhan Fungsional User (Mahasiswa)

Kebutuhan fungsional user adalah fitur-fitur yang dapat diakses dan digunakan oleh mahasiswa setelah login.

---

### 3.1 Login dengan Google OAuth

**Deskripsi:**
Mahasiswa harus dapat login ke sistem menggunakan akun Google ITERA mereka. Mahasiswa mengklik tombol "Login dengan Google" di halaman utama, kemudian sistem akan redirect ke halaman autentikasi Google OAuth. Mahasiswa memilih akun Google ITERA mereka dan memberikan izin akses (name, email, profile). Google akan mengirim callback ke sistem dengan user data dan authorization code. Sistem akan memvalidasi email, ekstrak NIM, buat/update akun mahasiswa, buat/update data diri, login mahasiswa menggunakan `Auth::login($user)`, dan redirect ke dashboard `/user/mental-health`. Session mahasiswa akan berlaku selama 120 menit. Jika email tidak valid, mahasiswa akan melihat pesan error dan diminta login ulang dengan email ITERA yang benar.

---

### 3.2 Logout

**Deskripsi:**
Mahasiswa harus dapat logout dari sistem kapan saja. Mahasiswa mengklik menu profil di pojok kanan atas, kemudian klik tombol "Logout". Sistem akan memproses POST request ke `/logout`, memvalidasi CSRF token, logout mahasiswa menggunakan `Auth::logout()`, menghapus session dari database menggunakan `$request->session()->invalidate()`, regenerate CSRF token menggunakan `$request->session()->regenerateToken()`, dan redirect ke halaman login dengan flash message "Logout berhasil". Setelah logout, mahasiswa tidak dapat mengakses halaman yang memerlukan autentikasi dan harus login ulang jika ingin mengakses sistem.

---

### 3.3 Melihat Dashboard Pribadi

**Deskripsi:**
Mahasiswa harus dapat melihat dashboard pribadi yang menampilkan statistik dan riwayat tes mereka. Dashboard menampilkan: kartu statistik berisi total tes yang sudah dikerjakan dan kategori hasil terakhir dengan warna badge, grafik progres skor berbentuk line chart yang menunjukkan trend skor dari waktu ke waktu (sumbu X: tanggal tes, sumbu Y: skor 38-228), tabel riwayat semua tes yang pernah dikerjakan dengan kolom tanggal, skor, kategori (diurutkan dari terbaru), dan tombol "Mulai Tes Baru" untuk mengerjakan kuesioner baru. Data dashboard di-cache 5 menit sehingga load time sangat cepat (< 500ms). Grafik menggunakan Chart.js untuk visualisasi interaktif dengan tooltip yang menampilkan detail saat cursor hover. Mahasiswa dapat mengklik detail pada riwayat tes untuk melihat penjelasan lengkap hasil dalam modal popup.

---

### 3.4 Mengisi Data Diri

**Deskripsi:**
Mahasiswa harus dapat mengisi atau memperbarui data diri mereka sebelum mengerjakan kuesioner. Mahasiswa mengakses halaman `/mental-health/isi-data-diri` atau mengklik tombol "Mulai Tes Baru". Sistem menampilkan formulir dengan field: NIM (readonly, auto-filled), nama (required), jenis kelamin (required, pilihan L/P), provinsi (required), alamat (required, textarea), usia (required, numeric), email (readonly, auto-filled dari Google), fakultas (required), program studi (required), asal sekolah (required), status tinggal (required, pilihan: Kos/Asrama/Rumah Sendiri/Lainnya), keluhan yang dialami (optional, textarea), lama keluhan (optional), pernah konsultasi (optional, pilihan Ya/Tidak), pernah tes (optional, pilihan Ya/Tidak). Jika mahasiswa sudah pernah mengisi, form akan auto-fill dengan data sebelumnya menggunakan `old()` dan value dari database. Mahasiswa mengisi atau memperbarui data, klik "Lanjutkan", sistem validasi semua required field, simpan data diri ke `data_diris`, simpan riwayat keluhan ke `riwayat_keluhans`, dan redirect ke halaman kuesioner `/mental-health/kuesioner`.

---

### 3.5 Mengerjakan Kuesioner MHI-38

**Deskripsi:**
Mahasiswa harus dapat mengerjakan kuesioner Mental Health Inventory yang terdiri dari 38 pertanyaan. Setelah data diri lengkap, mahasiswa diarahkan ke halaman kuesioner yang menampilkan 38 pertanyaan dengan pilihan jawaban berupa radio button (1=Tidak pernah, 2=Jarang, 3=Kadang-kadang, 4=Sering, 5=Sangat sering, 6=Selalu). Sidebar menampilkan progress bar yang update real-time setiap pertanyaan dijawab menggunakan JavaScript (contoh: "15/38 pertanyaan terjawab - 39%"). Mahasiswa menjawab semua pertanyaan satu per satu, setelah semua selesai mahasiswa klik tombol "Submit". Sistem validasi di JavaScript untuk cek apakah 38 pertanyaan sudah terjawab semua sebelum submit. Jika ada yang kosong, tampilkan alert "Harap jawab semua pertanyaan". Jika semua sudah terjawab, form di-submit ke server. Server validasi ulang, hitung total skor, tentukan kategori, simpan ke `hasil_kuesioners`, invalidate cache, dan redirect ke halaman hasil.

---

### 3.6 Melihat Hasil Tes

**Deskripsi:**
Mahasiswa harus dapat melihat hasil tes terbaru mereka dengan visualisasi yang informatif dan rekomendasi yang sesuai. Setelah submit kuesioner, mahasiswa otomatis diarahkan ke halaman hasil yang menampilkan: total skor dalam format angka besar dengan desain menarik, kategori dengan badge berwarna (hijau untuk Sangat Sehat/Sehat, kuning untuk Cukup Sehat, merah untuk Perlu Dukungan/Intensif), penjelasan interpretasi hasil sesuai kategori (misalnya "Kondisi mental Anda sangat baik, terus pertahankan pola hidup sehat"), rekomendasi tindak lanjut yang spesifik per kategori, grafik visualisasi skor dalam range 38-228 menggunakan progress bar atau gauge chart. Untuk mahasiswa dengan kategori "Perlu Dukungan" atau "Perlu Dukungan Intensif", sistem menampilkan alert box dengan warna menonjol berisi informasi kontak layanan konseling ITERA dan himbauan untuk segera berkonsultasi. Tombol aksi: "Tes Lagi" (redirect ke form data diri untuk tes ulang) dan "Kembali ke Dashboard" (redirect ke `/user/mental-health`).

---

### 3.7 Melihat Riwayat Tes

**Deskripsi:**
Mahasiswa harus dapat melihat riwayat semua tes yang pernah mereka kerjakan untuk tracking progress kesehatan mental. Di dashboard, terdapat section "Riwayat Tes" yang menampilkan tabel dengan kolom: nomor urut, tanggal pengerjaan (format: DD MMM YYYY HH:mm), total skor, kategori (dengan badge berwarna), dan aksi berupa tombol "Lihat Detail". Tabel diurutkan dari tes terbaru ke terlama berdasarkan `created_at DESC`. Mahasiswa dapat mengklik "Lihat Detail" pada salah satu tes, maka akan muncul modal popup yang menampilkan detail lengkap tes tersebut: tanggal lengkap, skor, kategori, penjelasan interpretasi, dan rekomendasi yang sesuai. Modal dapat ditutup dengan klik tombol X atau klik di luar modal. Tidak ada batasan jumlah tes yang dapat dilihat, semua history tersimpan permanen di database.

---

### 3.8 Melihat Grafik Progres Skor

**Deskripsi:**
Mahasiswa harus dapat melihat grafik progres skor mereka dari waktu ke waktu untuk memahami trend kesehatan mental mereka. Di dashboard, terdapat section "Grafik Progres" yang menampilkan line chart interaktif menggunakan Chart.js. Sumbu X (horizontal) menunjukkan tanggal pengerjaan tes dalam format DD/MM/YYYY, sumbu Y (vertikal) menunjukkan total skor dalam range 38-228. Setiap titik di grafik merepresentasikan satu tes dengan warna sesuai kategori (hijau untuk Sangat Sehat/Sehat, kuning untuk Cukup Sehat, merah untuk Perlu Dukungan). Garis menghubungkan titik-titik untuk menunjukkan trend naik/turun. Saat cursor hover di atas titik, tooltip menampilkan informasi lengkap: tanggal, skor, kategori. Grafik responsif dan akan menyesuaikan ukuran layar. Jika belum ada tes, grafik menampilkan pesan "Belum ada data untuk ditampilkan".

---

## 4. Kebutuhan Fungsional Admin (Administrator)

Kebutuhan fungsional admin adalah fitur-fitur yang dapat diakses dan digunakan oleh administrator untuk mengelola data dan monitoring sistem.

---

### 4.1 Login dengan Email dan Password

**Deskripsi:**
Administrator harus dapat login ke sistem menggunakan email dan password yang terdaftar. Administrator mengakses halaman `/login`, sistem menampilkan form login dengan field email (required, email format) dan password (required, min 6 characters). Administrator mengisi email dan password, klik tombol "Login". Sistem memvalidasi format input, kemudian mengecek kredensial menggunakan `Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])` yang otomatis melakukan hash comparison. Jika kredensial tidak cocok, tampilkan error "Email atau password salah" dan kembali ke form login. Jika kredensial cocok, sistem login admin, regenerate session ID untuk keamanan, set timestamp `last_activity_admin` untuk session timeout, dan redirect ke dashboard admin `/admin/mental-health`. Session admin berlaku 120 menit dengan auto-logout jika idle 30 menit. Administrator yang sudah login tidak dapat akses halaman login karena middleware `guest` akan redirect ke home.

---

### 4.2 Logout

**Deskripsi:**
Administrator harus dapat logout dari sistem dengan aman. Administrator mengklik menu profil, kemudian klik tombol "Logout". Sistem memproses POST request ke `/logout`, validasi CSRF token, deteksi guard yang aktif (admin atau web), logout dari guard `admin` menggunakan `Auth::guard('admin')->logout()`, hapus session dari database, hapus `last_activity_admin` dari session, regenerate CSRF token, dan redirect ke `/login` dengan flash message "Logout berhasil". Setelah logout, administrator tidak dapat akses halaman admin dan harus login ulang. Session yang sudah di-logout tidak dapat digunakan lagi meskipun session ID diketahui (regenerate mencegah session hijacking).

---

### 4.3 Melihat Dashboard Admin

**Deskripsi:**
Administrator harus dapat melihat dashboard yang menampilkan statistik lengkap dan data mahasiswa secara komprehensif. Dashboard menampilkan: kartu ringkasan di bagian atas berisi total mahasiswa yang sudah tes, total tes dikerjakan, distribusi kategori (5 kategori dengan jumlah dan persentase), distribusi gender (L/P dengan persentase), grafik bar chart distribusi per fakultas, grafik line chart trend tes per bulan (6 bulan terakhir), dan tabel data mahasiswa dengan kolom: no urut, NIM, nama, fakultas, program studi, kategori terakhir (badge berwarna), skor terakhir, tanggal tes terakhir (format: DD/MM/YYYY), jumlah tes, dan aksi (tombol "Lihat" dan "Hapus"). Di atas tabel terdapat filter dan search: search box untuk mencari di 11 field, dropdown filter kategori (Semua/Sangat Sehat/Sehat/Cukup Sehat/Perlu Dukungan/Perlu Dukungan Intensif), dropdown sorting (NIM/Nama/Tanggal/Skor/Kategori, ASC/DESC), dropdown items per page (10/25/50/100), dan tombol "Export to Excel". Pagination di bawah tabel dengan Previous/Next dan page numbers. Data statistik di-cache 1 menit untuk performa. Dashboard load dalam < 500ms dengan cache.

---

### 4.4 Mencari Data Mahasiswa

**Deskripsi:**
Administrator harus dapat mencari data mahasiswa dengan kata kunci di multiple fields secara bersamaan untuk menemukan data dengan cepat. Administrator mengetik kata kunci di search box (misalnya "Informatika" atau "Perlu Dukungan" atau nama mahasiswa), kemudian klik tombol "Cari" atau tekan Enter. Sistem akan mencari kata kunci tersebut di 11 field menggunakan LIKE query dengan partial matching: NIM, nama, jenis kelamin, provinsi, alamat, fakultas, program studi, asal sekolah, status tinggal, email, dan kategori. Pencarian case-insensitive (tidak peduli huruf besar/kecil). Sistem menggunakan optimasi query dengan LEFT JOIN ke tabel `riwayat_keluhans` dan `hasil_kuesioners`, kemudian WHERE clause dengan multiple OR conditions, dan DISTINCT untuk hindari duplikasi. Hasil pencarian ditampilkan di tabel yang sama dengan informasi "Ditemukan X data untuk pencarian 'keyword'". Pencarian dapat dikombinasikan dengan filter kategori dan sorting. Performa pencarian < 200ms untuk 10,000 records berkat indexing.

---

### 4.5 Memfilter Data berdasarkan Kategori

**Deskripsi:**
Administrator harus dapat memfilter data mahasiswa berdasarkan kategori hasil tes terakhir untuk fokus pada kelompok mahasiswa tertentu. Administrator memilih kategori dari dropdown filter yang berisi pilihan: "Semua" (default, tampilkan semua mahasiswa), "Sangat Sehat", "Sehat", "Cukup Sehat", "Perlu Dukungan", "Perlu Dukungan Intensif". Setelah memilih, sistem otomatis reload tabel dengan data yang ter-filter. Sistem menggunakan `whereHas('latestHasilKuesioner', function($q) use ($kategori) { $q->where('kategori', $kategori); })` untuk filter berdasarkan kategori hasil tes terakhir mahasiswa. Filter dapat dikombinasikan dengan search (misalnya: cari "Fakultas Sains" + filter "Perlu Dukungan" = mahasiswa Fakultas Sains yang perlu dukungan). UI menampilkan badge atau highlight untuk menunjukkan filter aktif dengan teks "Filtered by: {kategori}". Tombol "Clear Filter" untuk reset ke "Semua".

---

### 4.6 Mengurutkan Data Mahasiswa

**Deskripsi:**
Administrator harus dapat mengurutkan data mahasiswa berdasarkan berbagai kolom untuk analisis yang lebih mudah. Administrator mengklik header kolom tabel (misalnya "Nama" atau "Skor") atau memilih dari dropdown sorting. Kolom yang dapat diurutkan: NIM, nama, fakultas, program studi, kategori, skor, tanggal tes, jumlah tes. Setiap kolom dapat ascending (A-Z, kecil-besar, lama-baru, naik) atau descending (Z-A, besar-kecil, baru-lama, turun). Klik pertama = ascending, klik kedua = descending, klik ketiga = reset ke default. Default sorting: `created_at DESC` (terbaru dulu). Sistem menggunakan `orderBy($column, $direction)` di Eloquent query. UI menampilkan arrow icon (↑ untuk ASC, ↓ untuk DESC) di header kolom yang aktif. Sorting dapat dikombinasikan dengan search dan filter. URL parameter tersimpan sehingga sorting persist saat refresh atau pagination.

---

### 4.7 Pagination Data Mahasiswa

**Deskripsi:**
Administrator harus dapat membagi data mahasiswa menjadi beberapa halaman untuk menghindari loading semua data sekaligus. Administrator memilih jumlah data per halaman dari dropdown (10, 25, 50, atau 100 entries). Sistem menggunakan `paginate($perPage)` dari Laravel Eloquent yang otomatis membuat query dengan LIMIT dan OFFSET. Pagination menampilkan: tombol "Previous" (disabled jika di halaman pertama), nomor halaman (contoh: 1, 2, 3, ..., 10), tombol "Next" (disabled jika di halaman terakhir), dan informasi "Showing 1 to 25 of 250 entries". Klik nomor halaman akan load halaman tersebut dengan AJAX atau full page reload. Parameter pagination disimpan di URL query string (contoh: `?page=2&per_page=25`) sehingga bisa di-bookmark, di-share, atau back/forward browser tetap maintain state. Perubahan per_page tidak reset ke halaman 1, tetapi adjust halaman saat ini agar data yang sama tetap terlihat. Pagination berfungsi dengan search, filter, dan sorting aktif.

---

### 4.8 Melihat Detail Mahasiswa

**Deskripsi:**
Administrator harus dapat melihat informasi lengkap satu mahasiswa dalam satu tempat untuk analisis mendalam. Administrator mengklik tombol "Lihat" (icon mata) pada baris data mahasiswa di tabel. Sistem akan fetch data lengkap mahasiswa dari database dengan eager loading relasi (data diri, riwayat keluhan, semua hasil tes) menggunakan `with(['riwayatKeluhans', 'hasilKuesioners'])`. Sistem menampilkan modal popup besar (responsive) dengan 4 tab: Tab 1 "Data Diri" menampilkan semua informasi pribadi (NIM, nama, jenis kelamin, provinsi, alamat, usia, email) dan akademik (fakultas, program studi, asal sekolah, status tinggal) dalam layout 2 kolom, Tab 2 "Riwayat Keluhan" menampilkan keluhan yang dialami, lama keluhan, status konsultasi, status tes sebelumnya dalam card atau list, Tab 3 "Hasil Tes" menampilkan tabel semua tes yang pernah dikerjakan dengan kolom tanggal, skor, kategori (diurutkan terbaru dulu), Tab 4 "Grafik" menampilkan line chart progres skor mahasiswa dari waktu ke waktu sama seperti grafik di user dashboard. Modal dapat ditutup dengan klik tombol X, klik di luar modal, atau tekan ESC. Transition smooth dengan fade in/out animation.

---

### 4.8.1 Melihat Detail Jawaban Kuesioner (NEW - 13 November 2025)

**Deskripsi:**
Administrator harus dapat melihat detail jawaban kuesioner mahasiswa per pertanyaan untuk analisis mendalam dan verifikasi hasil. Administrator mengklik tombol "Detail" atau "Lihat Jawaban" pada salah satu hasil tes mahasiswa di tabel. Sistem akan redirect ke halaman detail `/admin/mental-health/detail/{id}` yang menampilkan informasi lengkap: **Info Cards** berisi (urutan: NIM → Nama → Program Studi → Tanggal Tes) dengan icon dan layout responsive, **Ringkasan Hasil** menampilkan total skor dengan visual badge dan kategori dengan warna sesuai tingkat kesehatan, **Tabel Detail Jawaban** menampilkan 38 pertanyaan lengkap dengan kolom: No (1-38), Tipe (badge "Positif" warna hijau atau "Negatif" warna merah berdasarkan 20 pertanyaan negatif: 2,3,8,9,10,12,14,15,17,19,20,21,22,24,26,28,30,32,35,37), Pertanyaan (text lengkap sama persis dengan kuesioner asli), dan Skor (jawaban mahasiswa 1-6 dengan bold styling). **Fitur Export PDF** dengan tombol "Cetak PDF" yang menggenerate file PDF berisi semua informasi: header dengan judul "Detail Jawaban Kuesioner Mental Health" dan tanggal tes, info mahasiswa dengan bullet point (• NIM, • Nama, • Program Studi, • Tanggal Tes), ringkasan hasil dengan bullet point (• Total Skor, • Kategori), tabel 38 pertanyaan dengan header rata tengah (No., Tipe, Pertanyaan, Skor), tabel berada tepat di tengah halaman dengan margin kiri-kanan otomatis (22.5mm), watermark "Generated by ANALOGY - ITERA" di pojok kanan bawah setiap halaman dengan font kecil italic. **Error Handling** dengan try-catch block untuk menangkap error saat generate PDF dan menampilkan SweetAlert dengan pesan error yang jelas. **JavaScript Validation** untuk escape karakter khusus menggunakan `json_encode()` mencegah syntax error di PDF generation. **Tombol Navigasi** berupa "Kembali" untuk return ke dashboard admin dan "Cetak PDF" untuk download file. Halaman menggunakan layout responsive, sidebar info cards 2 kolom di desktop dan 1 kolom di mobile, tabel scrollable horizontal di mobile untuk maintain readability.

---

### 4.9 Export Data ke Excel

**Deskripsi:**
Administrator harus dapat mengexport data mahasiswa ke file Excel untuk reporting, backup, atau analisis eksternal. Administrator mengklik tombol "Export to Excel" yang terletak di atas tabel. Sistem akan mengambil semua data yang sesuai dengan filter dan search yang sedang aktif, tetapi tanpa batasan pagination (ambil semua data, bukan hanya halaman saat ini). Sistem menggunakan package Maatwebsite/Excel dengan export class `HasilKuesionerExport`. File Excel berisi 16 kolom: No (urut 1,2,3,...), Tanggal Pengerjaan (format: DD/MM/YYYY HH:mm), NIM, Nama, Fakultas, Program Studi, Jenis Kelamin, Usia, Provinsi, Alamat, Email, Asal Sekolah, Status Tinggal, Jumlah Tes, Kategori Terakhir, Skor Terakhir. Formatting Excel: baris pertama (header) bold dengan background color abu-abu, lebar kolom auto-size agar text tidak terpotong, border di semua cell untuk kejelasan, freeze baris pertama agar header tetap terlihat saat scroll. Nama file menggunakan format `mental_health_data_{timestamp}.xlsx` dimana timestamp adalah tanggal dan waktu export (contoh: `mental_health_data_2025-11-11_14-30-45.xlsx`). File otomatis ter-download di browser tanpa redirect halaman. Export 1000 records < 5 detik, max 10,000 records untuk hindari timeout.

---

### 4.10 Menghapus Data Mahasiswa

**Deskripsi:**
Administrator harus dapat menghapus semua data mahasiswa secara permanen dari sistem untuk keperluan data cleanup atau request penghapusan data. Administrator mengklik tombol "Hapus" (icon trash) berwarna merah pada baris data mahasiswa. Sistem menampilkan modal konfirmasi dengan pesan peringatan "Apakah Anda yakin ingin menghapus semua data mahasiswa ini?" dan penjelasan "Data yang akan dihapus: Data Diri, Riwayat Keluhan, Semua Hasil Kuesioner, User Account. Tindakan ini tidak dapat dibatalkan (permanen)." dengan dua tombol: "Batal" (warna abu, tutup modal tanpa hapus) dan "Ya, Hapus" (warna merah, lanjutkan penghapusan). Jika klik "Ya, Hapus", sistem cari data mahasiswa berdasarkan NIM, cek apakah exists, jika tidak exists tampilkan error "Data tidak ditemukan", jika exists lakukan cascade delete: hapus semua `riwayat_keluhans` WHERE nim, hapus semua `hasil_kuesioners` WHERE nim, hapus `data_diris` WHERE nim, hapus `users` WHERE nim. Cascade delete otomatis karena foreign key `ON DELETE CASCADE`. Setelah delete berhasil, sistem invalidate cache (`Cache::forget('admin_mental_health_stats')` dan `Cache::forget("user_dashboard_{$nim}")`), tampilkan flash message "Data berhasil dihapus", dan reload dashboard dengan data terbaru. Penghapusan bersifat hard delete (permanent), bukan soft delete. Aksi dilindungi CSRF token dan AdminAuth middleware.

---

### 4.11 Melihat Statistik Kesehatan Mental

**Deskripsi:**
Administrator harus dapat melihat statistik kesehatan mental secara keseluruhan untuk monitoring dan pengambilan keputusan. Statistik ditampilkan di dashboard admin dalam berbagai bentuk: Kartu ringkasan dengan icon menampilkan angka besar untuk total mahasiswa (distinct NIM dari `hasil_kuesioners`), total tes dikerjakan (count all `hasil_kuesioners`), rata-rata skor (average dari semua skor, rounded 2 decimal), dan tes hari ini (count dengan `whereDate('created_at', today())`). Kartu distribusi kategori menampilkan breakdown 5 kategori dengan jumlah mahasiswa dan persentase dari total (contoh: "Sangat Sehat: 45 mahasiswa (30%)"). Kartu distribusi gender menampilkan jumlah dan persentase laki-laki vs perempuan dari `data_diris`. Section "Top 5 Asal Sekolah" menampilkan list 5 sekolah dengan mahasiswa terbanyak hasil groupBy dan orderBy count. Grafik bar chart distribusi fakultas (sumbu X: nama fakultas, sumbu Y: jumlah mahasiswa) menggunakan Chart.js dengan warna berbeda per bar. Grafik line chart trend tes per bulan menampilkan jumlah tes dikerjakan per bulan dalam 6 bulan terakhir (sumbu X: bulan, sumbu Y: jumlah tes). Semua statistik di-cache 1 menit untuk performa, query menggunakan aggregation (count, avg, groupBy) dengan optimasi subquery.

---

## 5. Kebutuhan Non-Fungsional

Kebutuhan non-fungsional adalah karakteristik sistem yang tidak terkait langsung dengan fungsi spesifik tetapi penting untuk kualitas sistem.

---

### 5.1 Performance (Kecepatan Sistem)

**Deskripsi:**
Sistem harus memiliki performa yang cepat dan responsif untuk memberikan user experience yang baik. Target response time yang harus dicapai: homepage < 1 detik, dashboard user dengan cache < 500ms, dashboard admin dengan cache < 500ms, pencarian mahasiswa < 200ms untuk 10,000 records, export Excel untuk 1,000 records < 5 detik. Untuk mencapai target ini, sistem menggunakan berbagai teknik optimasi: caching data yang sering diakses (user dashboard 5 menit, admin stats 1 menit) menggunakan cache driver database, database indexing di kolom yang sering di-query (nim, kategori, created_at, dll) untuk speed up WHERE dan JOIN, query optimization dengan eager loading untuk hindari N+1 query problem, menggunakan subquery dan LEFT JOIN untuk aggregation, dan pagination untuk limit data yang di-load. Hasil optimasi yang sudah dicapai: query count berkurang dari 150+ menjadi 3 queries, load time dashboard admin berkurang dari 3.5 detik menjadi 0.18 detik (improvement 94% atau 19x lebih cepat), search time untuk 10,000 records < 50ms. Monitoring performa menggunakan Laravel Debugbar saat development dan log query time > 100ms untuk identifikasi bottleneck.

---

### 5.2 Security (Keamanan Sistem)

**Deskripsi:**
Sistem harus aman dari berbagai ancaman keamanan web untuk melindungi data mahasiswa dan integritas sistem. Implementasi keamanan mencakup: autentikasi menggunakan Google OAuth 2.0 untuk mahasiswa (state token untuk prevent CSRF di OAuth flow) dan email/password untuk admin dengan password hashing bcrypt cost factor 10, session-based authentication dengan session disimpan di database (bukan cookie/file) untuk security dan scalability, CSRF protection di semua form POST/DELETE menggunakan token yang di-embed dengan `@csrf` dan validasi otomatis di middleware `VerifyCsrfToken`, input validation dan sanitization di semua form menggunakan Laravel validation rules untuk prevent SQL injection, XSS prevention dengan Blade automatic escaping (semua `{{ }}` di-escape, gunakan `{!! !!}` hanya untuk trusted content), session fixation prevention dengan regenerate session ID saat login menggunakan `$request->session()->regenerate()`, session timeout khusus admin (auto-logout setelah 30 menit idle) untuk prevent unauthorized access jika admin lupa logout, authorization menggunakan middleware `auth` untuk user routes dan `AdminAuth` untuk admin routes, environment variables untuk credential sensitif (database password, Google OAuth client secret, APP_KEY) disimpan di `.env` dan tidak di-commit ke repository, dan HTTPS mandatory untuk production environment untuk encrypt data in transit. Tidak boleh ada password disimpan plain text, semua harus bcrypt hash. Security headers (X-Frame-Options, X-Content-Type-Options, X-XSS-Protection) di-set di middleware atau web server config.

---

### 5.3 Scalability (Skalabilitas Sistem)

**Deskripsi:**
Sistem harus dapat menangani pertumbuhan jumlah pengguna dan data tanpa penurunan performa yang signifikan untuk sustainability jangka panjang. Target kapasitas yang harus didukung: 10,000+ mahasiswa terdaftar (10,000 records di tabel `users` dan `data_diris`), 100+ pengguna aktif bersamaan (concurrent users) dengan load balanced jika perlu, 1,000+ tes dikerjakan per hari (1,000 insert ke `hasil_kuesioners` per hari), dan 100,000+ total hasil tes tersimpan (accumulated data over years). Untuk mencapai skalabilitas, sistem menggunakan: database indexing yang tepat di kolom yang sering di-query sehingga query tetap cepat O(log n) meski data banyak, caching strategy untuk mengurangi beban database pada load tinggi (cache TTL 1-5 menit tergantung data volatility), efficient query design dengan avoid SELECT *, gunakan specific columns yang dibutuhkan, pagination untuk limit memory usage (tidak load semua data sekaligus), foreign key dengan proper indexing untuk speed up JOIN operations, dan database connection pooling untuk reuse connections. Arsitektur sistem dirancang stateless (session di database, bukan memory) sehingga memungkinkan horizontal scaling dengan multiple app servers di belakang load balancer jika traffic meningkat. Database dapat di-scale vertical (upgrade server spec) atau horizontal (replication, sharding) sesuai kebutuhan. File storage untuk future features (upload foto profile, dokumen) harus menggunakan cloud storage (S3, GCS) bukan local disk untuk scalability.

---

### 5.4 Usability (Kemudahan Penggunaan)

**Deskripsi:**
Sistem harus mudah digunakan oleh semua pengguna tanpa memerlukan pelatihan khusus atau manual yang panjang. Prinsip usability yang diterapkan: interface harus intuitif dengan navigasi yang jelas dan konsisten (navbar tetap di atas, menu di tempat yang sama di semua halaman), design harus responsive dan dapat diakses dengan baik di berbagai ukuran layar mulai dari mobile 320px, tablet 768px, hingga desktop 1920px+ menggunakan Tailwind CSS responsive utilities dan Bootstrap grid, pesan error harus jelas, spesifik, dan memberikan petunjuk cara memperbaiki (contoh: "Alamat wajib diisi" bukan hanya "Error"), loading indicator (spinner, progress bar, skeleton screen) harus ditampilkan saat proses yang membutuhkan waktu > 500ms agar user tahu sistem sedang processing, warna dan kontras harus baik untuk keterbacaan sesuai WCAG 2.0 level AA (contrast ratio minimal 4.5:1 untuk normal text, 3:1 untuk large text), font harus mudah dibaca dengan ukuran minimal 14px untuk body text dan 16px untuk mobile, button dan link harus mudah diklik dengan ukuran minimal 44x44px sesuai touch target guideline iOS/Android, form harus memiliki label yang jelas untuk setiap field, placeholder yang membantu (contoh: "Contoh: 121450123"), dan inline validation yang memberi feedback real-time, breadcrumb atau indicator untuk multi-step form (data diri → kuesioner → hasil) agar user tahu posisi mereka, dan confirmation dialog untuk destructive action (hapus data, logout) untuk prevent accidental action. User testing dengan 5-10 mahasiswa dan admin untuk validate usability sebelum launch.

---

### 5.5 Reliability (Keandalan Sistem)

**Deskripsi:**
Sistem harus dapat diandalkan dengan uptime tinggi dan data integrity yang terjaga untuk memastikan sistem dapat digunakan kapan saja dibutuhkan. Target reliability: uptime minimal 99% dalam konteks akademik (maksimal downtime 7 jam per bulan atau ~14 menit per hari), planned downtime untuk maintenance harus dijadwalkan di waktu low traffic (tengah malam, weekend) dan diinformasikan ke user minimal 24 jam sebelumnya. Data integrity harus terjaga dengan: tidak ada orphan records karena foreign key constraint dengan ON DELETE CASCADE, tidak ada data corruption dengan menggunakan database transaction untuk operasi multi-table, cascade delete harus berfungsi dengan benar saat hapus mahasiswa, dan data validation di level database (NOT NULL, UNIQUE, CHECK constraint) selain validasi di aplikasi. Database harus di-backup secara reguler dengan schedule: full backup daily pada jam 02:00 AM, incremental backup setiap 6 jam, backup file disimpan minimal 30 hari untuk recovery, automated backup verification untuk ensure backup file tidak corrupt. Error handling harus proper untuk menangani exceptional cases tanpa crash aplikasi: try-catch di code yang prone to error (file upload, external API call, complex calculation), graceful degradation jika service tidak available (contoh: jika cache server down, langsung query database), user-friendly error page (404, 500, 503) dengan pesan yang helpful dan link kembali ke home, dan log semua error ke `storage/logs/laravel.log` dengan context (user id, URL, input data, stack trace) untuk debugging. Health check endpoint `/health` yang return status server, database connection, cache connection untuk monitoring oleh uptime robot atau nagios. Disaster recovery plan documented untuk handle database corruption, server failure, atau data loss.

---

### 5.6 Maintainability (Kemudahan Pemeliharaan)

**Deskripsi:**
Sistem harus mudah dipelihara dan dikembangkan di masa depan oleh developer lain atau tim yang berbeda untuk sustainability project. Code quality standards yang harus diikuti: follow Laravel best practices dan convention (naming, folder structure, routing pattern), follow PSR-12 coding standard untuk PHP dengan indentation 4 spaces, opening brace di baris baru untuk class/function, naming convention konsisten dan meaningful (camelCase untuk variable/method, PascalCase untuk class, snake_case untuk database column, kebab-case untuk route), dan code harus modular dengan separation of concerns menggunakan MVC pattern (Controller untuk handle request/response, Model untuk business logic dan database interaction, View untuk presentation logic). Single Responsibility Principle: satu class/method hanya satu tanggung jawab, fat model skinny controller pattern, DRY (Don't Repeat Yourself) dengan extract reusable code ke helper/service/trait. Comment harus ada di logic yang kompleks atau tidak obvious, PHPDoc block untuk method dengan parameter dan return type description, inline comment untuk explain "why" bukan "what". Testing harus comprehensive dengan minimal 12 tests total (3 unit tests untuk model validation/relationship, 9 feature tests untuk end-to-end workflow), target 100% passing rate di CI/CD pipeline, coverage minimal 70% untuk critical path (auth, kuesioner submit, scoring, export). Documentation harus lengkap dan up-to-date meliputi: README.md untuk overview dan quick start, PROJECT_DOCUMENTATION.md untuk detailed documentation, DEPLOYMENT_GUIDE.md untuk step-by-step deployment, KEBUTUHAN_FUNGSIONAL.md (dokumen ini) untuk functional requirements, dan inline code comment untuk complex logic. Version control dengan Git best practices: meaningful commit message, feature branch workflow, pull request review sebelum merge ke main, semantic versioning untuk release tag. Dependency management dengan Composer (backend) dan NPM (frontend), specify exact version atau caret range untuk stability. Logging yang adequate untuk troubleshooting tanpa sensitive data exposure.

---

### 5.7 Compatibility (Kompatibilitas)

**Deskripsi:**
Sistem harus kompatibel dengan berbagai browser, device, dan platform untuk memastikan aksesibilitas maksimal. Browser compatibility target: Google Chrome versi 2 terbaru (saat ini 120, 121), Mozilla Firefox versi 2 terbaru, Safari versi 2 terbaru (untuk macOS dan iOS), Microsoft Edge versi 2 terbaru (Chromium-based). Tidak support IE11 atau browser legacy. Testing menggunakan BrowserStack atau manual testing di real devices. Progressive enhancement approach: core functionality harus bekerja tanpa JavaScript (form submission, navigation), enhanced experience dengan JavaScript enabled (AJAX, real-time validation, interactive chart). Cross-platform server compatibility: sistem harus dapat berjalan di Linux Ubuntu 20.04 LTS atau 22.04 LTS (recommended untuk production), macOS untuk local development, Windows dengan XAMPP/Laragon untuk local development. Database compatibility: SQLite 3.x untuk development environment (built-in, zero config, file-based), MySQL 8.0 atau MariaDB 10.x untuk production environment (better performance, support large data, replication). PHP version: minimal PHP 8.2 sesuai requirement Laravel 11, recommended PHP 8.3 untuk latest features dan performance. Responsive design compatibility: mobile portrait 320px-480px (iPhone SE, small Android), mobile landscape 481px-767px, tablet portrait 768px-1024px (iPad), desktop 1025px-1920px, large desktop > 1920px (4K monitor). Breakpoint menggunakan Tailwind default: sm:640px, md:768px, lg:1024px, xl:1280px, 2xl:1536px. Accessibility compatibility: keyboard navigation support, screen reader compatibility dengan semantic HTML dan ARIA labels, color blind friendly dengan tidak rely solely on color untuk information, dan support browser zoom 100%-200% tanpa broken layout.

---

## 6. Batasan dan Asumsi

### 6.1 Batasan Sistem

**Batasan Teknis:**
- Platform web-based only, belum ada aplikasi mobile native (iOS/Android), akses mobile via mobile browser
- Hanya support browser modern yang listed di compatibility requirement, tidak support IE11 atau browser lawas
- Email mahasiswa harus menggunakan domain `@student.itera.ac.id`, email domain lain ditolak sistem
- Sistem memerlukan internet connection yang stabil, tidak ada offline mode

**Batasan Fungsional:**
- Mahasiswa hanya dapat melihat dan mengelola data tes mereka sendiri, tidak dapat melihat data mahasiswa lain (privacy)
- Administrator dapat melihat semua data dan menghapus data, tetapi tidak dapat mengedit hasil tes yang sudah tersimpan (untuk prevent manipulation)
- Kuesioner harus dikerjakan lengkap dalam satu sesi tanpa close browser, tidak ada fitur save progress untuk melanjutkan nanti (limitation untuk maintain data integrity dan prevent gaming system)
- Pertanyaan MHI-38 bersifat fixed dan tidak dapat dikustomisasi jumlah atau konten pertanyaan (karena instrumen tervalidasi)
- Export Excel dibatasi maksimal 10,000 records per request untuk menghindari timeout dan memory limit
- Tidak ada fitur import data bulk, semua data entry manual via form atau OAuth

**Batasan Data:**
- NIM mahasiswa harus exactly 9 digit angka sesuai format NIM ITERA, tidak boleh lebih atau kurang
- Total skor kuesioner dalam range 38-228 (minimal semua jawab 1, maksimal semua jawab 6)
- Kategori hasil fixed 5 tingkatan sesuai MHI-38 standard, tidak dapat ditambah kategori custom
- File upload (jika ada future feature) dibatasi max 2MB per file, allowed extensions: jpg, jpeg, png, pdf

---

### 6.2 Asumsi

**Asumsi Pengguna:**
- Setiap mahasiswa ITERA memiliki email aktif dengan format `{NIM}@student.itera.ac.id` yang dapat digunakan untuk login
- Mahasiswa memiliki akses internet yang stabil minimal 1 Mbps untuk mengakses sistem dengan baik
- Mahasiswa akan menjawab kuesioner dengan jujur sesuai kondisi mental mereka yang sebenarnya, bukan asal jawab
- Mahasiswa memahami bahasa Indonesia dengan baik untuk memahami pertanyaan kuesioner dan interface
- Mahasiswa memiliki device (laptop, tablet, smartphone) dengan browser modern untuk akses sistem
- Administrator memiliki pengetahuan dasar penggunaan web application dan Excel untuk export data

**Asumsi Sistem:**
- Google OAuth API akan selalu tersedia dan accessible 99.9% uptime sesuai SLA Google
- Database server memiliki resource yang cukup untuk menangani load (minimal 2GB RAM, 2 CPU cores untuk 1000 concurrent users)
- Internet connection di server stabil dengan minimal 10 Mbps upload/download
- SSL/TLS certificate tersedia dan valid untuk production domain untuk enable HTTPS
- Server memiliki cron job atau task scheduler untuk automated backup dan cache cleanup
- PHP memory_limit minimal 256MB untuk handle export Excel dan complex query

**Asumsi Organisasi:**
- Institut Teknologi Sumatera mendukung dan mengendorse penggunaan sistem ini secara resmi
- Layanan konseling ITERA tersedia dan siap menerima rujukan mahasiswa yang membutuhkan berdasarkan hasil tes
- Data mahasiswa akan dijaga kerahasiaannya sesuai regulasi privasi dan UU Perlindungan Data Pribadi
- Sistem digunakan semata-mata untuk kepentingan akademik dan kesejahteraan mahasiswa, bukan untuk evaluasi performa atau hukuman
- Ada tim IT atau developer yang maintain sistem dan handle bug report, feature request, server maintenance
- Budget tersedia untuk hosting, domain, SSL certificate, dan operational cost

---

## 7. Lampiran

### 7.1 Teknologi yang Digunakan

**Backend Framework:**
- Laravel 11.31 (PHP Framework)
- PHP 8.2 (Programming Language)

**Database:**
- SQLite 3.x (Development Environment)
- MySQL 8.0 / MariaDB 10.x (Production Environment)

**Frontend:**
- Tailwind CSS 4.0 (Utility-first CSS Framework)
- Bootstrap 5 (Component Library)
- Vanilla JavaScript (Interactivity)
- Vite 6.1 (Build Tool & Dev Server)

**Libraries & Packages:**
- Laravel Socialite 5.x (Google OAuth Integration)
- Maatwebsite/Excel 3.x (Export Excel Functionality)
- Chart.js 4.x (Grafik dan Visualisasi Data)

**Testing:**
- PHPUnit 11.0.1 (Testing Framework)
- Laravel Testing Framework (Feature & Unit Tests)

**Development Tools:**
- Composer 2.x (PHP Dependency Manager)
- NPM / Yarn (JavaScript Package Manager)
- Git (Version Control)
- Laravel Pint (Code Formatter)

---

### 7.2 Struktur Database

**Tabel: users**
- nim VARCHAR(20) PRIMARY KEY
- name VARCHAR(255) UNIQUE
- email VARCHAR(255) UNIQUE
- google_id VARCHAR(255) UNIQUE
- password VARCHAR(255) NULLABLE
- created_at TIMESTAMP
- updated_at TIMESTAMP

**Tabel: admins**
- id BIGINT AUTO_INCREMENT PRIMARY KEY
- username VARCHAR(255) UNIQUE
- email VARCHAR(255) UNIQUE
- password VARCHAR(255)
- created_at TIMESTAMP
- updated_at TIMESTAMP

**Tabel: data_diris**
- nim VARCHAR(20) PRIMARY KEY
- nama VARCHAR(255)
- jenis_kelamin VARCHAR(10)
- provinsi VARCHAR(100)
- alamat TEXT
- usia INT
- fakultas VARCHAR(100)
- program_studi VARCHAR(100)
- asal_sekolah VARCHAR(255)
- status_tinggal VARCHAR(50)
- email VARCHAR(255)
- created_at TIMESTAMP
- updated_at TIMESTAMP
- FOREIGN KEY (nim) REFERENCES users(nim) ON DELETE CASCADE

**Tabel: riwayat_keluhans**
- id BIGINT AUTO_INCREMENT PRIMARY KEY
- nim VARCHAR(20)
- keluhan TEXT
- lama_keluhan VARCHAR(255)
- pernah_konsul VARCHAR(10)
- pernah_tes VARCHAR(10)
- created_at TIMESTAMP
- updated_at TIMESTAMP
- FOREIGN KEY (nim) REFERENCES data_diris(nim) ON DELETE CASCADE

**Tabel: hasil_kuesioners**
- id BIGINT AUTO_INCREMENT PRIMARY KEY
- nim VARCHAR(20)
- total_skor INT
- kategori VARCHAR(50)
- created_at TIMESTAMP
- updated_at TIMESTAMP
- FOREIGN KEY (nim) REFERENCES data_diris(nim) ON DELETE CASCADE
- INDEX idx_nim (nim)
- INDEX idx_kategori (kategori)
- INDEX idx_created_at (created_at)

**Tabel: sessions**
- id VARCHAR(255) PRIMARY KEY
- user_id BIGINT NULLABLE
- payload TEXT
- last_activity INT
- ip_address VARCHAR(45)
- user_agent TEXT

---

### 7.3 File Utama Sistem

**Controllers:**
- `app/Http/Controllers/AuthController.php` - Autentikasi Google OAuth mahasiswa
- `app/Http/Controllers/Auth/AdminAuthController.php` - Autentikasi administrator
- `app/Http/Controllers/DashboardController.php` - Dashboard mahasiswa
- `app/Http/Controllers/DataDirisController.php` - Form data diri mahasiswa
- `app/Http/Controllers/RiwayatKeluhansController.php` - Riwayat keluhan
- `app/Http/Controllers/HasilKuesionerController.php` - Submit kuesioner dan perhitungan skor
- `app/Http/Controllers/HasilKuesionerCombinedController.php` - Dashboard admin, search, export, delete
- `app/Http/Controllers/StatistikController.php` - Statistik kesehatan mental

**Models:**
- `app/Models/Users.php` - Model mahasiswa
- `app/Models/Admin.php` - Model administrator
- `app/Models/DataDiris.php` - Model data diri (dengan scope search)
- `app/Models/RiwayatKeluhans.php` - Model riwayat keluhan
- `app/Models/HasilKuesioner.php` - Model hasil kuesioner

**Middleware:**
- `app/Http/Middleware/AdminAuth.php` - Middleware autentikasi admin dengan session timeout
- `app/Http/Middleware/VerifyCsrfToken.php` - CSRF protection

**Routes:**
- `routes/web.php` - Definisi semua routing aplikasi

**Migrations:**
- `database/migrations/*_create_users_table.php`
- `database/migrations/*_create_admins_table.php`
- `database/migrations/*_create_data_diris_table.php`
- `database/migrations/*_create_riwayat_keluhans_table.php`
- `database/migrations/*_create_hasil_kuesioners_table.php`
- `database/migrations/2025_10_30_162842_add_indexes_to_mental_health_tables.php`

**Exports:**
- `app/Exports/HasilKuesionerExport.php` - Export data mahasiswa ke Excel

**Tests:**
- `tests/Feature/MentalHealthWorkflowIntegrationTest.php` - Integration test workflow lengkap
- `tests/Unit/Models/DataDirisTest.php` - Unit test model DataDiris
- `tests/Unit/Models/HasilKuesionerTest.php` - Unit test model HasilKuesioner
- `tests/Unit/Models/RiwayatKeluhansTest.php` - Unit test model RiwayatKeluhans

**Views:**
- `resources/views/home.blade.php` - Homepage
- `resources/views/login.blade.php` - Halaman login admin
- `resources/views/user-mental-health.blade.php` - Dashboard mahasiswa
- `resources/views/kuesioner.blade.php` - Form kuesioner MHI-38
- `resources/views/admin/mental-health.blade.php` - Dashboard administrator
- `resources/views/admin-mental-health-detail.blade.php` - Detail jawaban kuesioner (NEW)

---

### 7.4 Update Log

**Versi 1.1 - 13 November 2025**
- ✅ Menambahkan fitur 4.8.1: Melihat Detail Jawaban Kuesioner
- ✅ Fitur export PDF dengan watermark ANALOGY - ITERA
- ✅ Update view: admin-mental-health-detail.blade.php
- ✅ 8 test cases baru untuk fitur detail jawaban
- ✅ Dokumentasi lengkap di TEST_DETAIL_JAWABAN_DOCUMENTATION.md
- ✅ Integrasi Vite untuk bundling assets

**Versi 1.0 - 11 November 2025**
- ✅ Dokumentasi awal kebutuhan fungsional lengkap
- ✅ 15 kebutuhan fungsional sistem
- ✅ 8 kebutuhan fungsional user/mahasiswa
- ✅ 11 kebutuhan fungsional admin
- ✅ 7 kebutuhan non-fungsional
- ✅ Batasan dan asumsi sistem

---

**Dokumen Dibuat:** 11 November 2025
**Update Terakhir:** 13 November 2025
**Status:** Updated (Versi 1.1)
**Institut Teknologi Sumatera (ITERA)**

---

**END OF DOCUMENT**
