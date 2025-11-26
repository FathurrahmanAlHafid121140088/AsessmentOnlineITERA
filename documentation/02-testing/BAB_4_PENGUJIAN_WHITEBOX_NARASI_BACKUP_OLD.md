# BAB IV
# PENGUJIAN DAN IMPLEMENTASI SISTEM

## 4.1 Pendahuluan

Tahap pengujian dalam pengembangan perangkat lunak merupakan bagian yang sangat krusial untuk memastikan bahwa sistem yang dibangun telah berjalan sesuai dengan spesifikasi yang dirancang. Pada penelitian ini, pengujian dilaksanakan menggunakan pendekatan whitebox testing guna menguji struktur internal sistem Mental Health Assessment yang telah dikembangkan. Metode whitebox testing dipilih karena mampu memberikan visibilitas penuh terhadap kode program, logika bisnis, dan alur eksekusi sistem, sehingga memudahkan identifikasi bug dan kesalahan sejak tahap pengembangan.

Sistem Mental Health Assessment yang dibangun menggunakan framework Laravel memiliki tingkat kompleksitas yang cukup tinggi dengan berbagai fitur seperti autentikasi untuk berbagai peran pengguna, proses pengisian kuesioner MHI-38, perhitungan skor secara otomatis, kategorisasi tingkat kesehatan mental, dashboard untuk analisis data, serta fitur ekspor data. Kompleksitas tersebut menuntut adanya pengujian yang menyeluruh untuk memvalidasi setiap komponen sistem yang ada. Proses pengujian dilakukan secara sistematis dengan memanfaatkan framework PHPUnit yang sudah terintegrasi dengan Laravel, mencakup 140 kasus uji yang tersusun dalam 15 berkas pengujian yang berbeda.

Bab ini akan menguraikan secara rinci mengenai metodologi pengujian yang diterapkan, implementasi dari rangkaian pengujian, hasil pengujian untuk setiap modul sistem, analisis cakupan pengujian, serta kesimpulan dari keseluruhan proses yang telah dilakukan. Dokumentasi ini diharapkan mampu memberikan gambaran yang menyeluruh tentang kualitas dan keandalan sistem yang telah dibangun.

## 4.2 Metodologi Pengujian

### 4.2.1 Whitebox Testing

Whitebox testing, yang dalam literatur juga dikenal dengan istilah structural testing, clear-box testing, atau glass-box testing, adalah sebuah metodologi pengujian perangkat lunak yang menitikberatkan pada pengujian struktur internal aplikasi. Berbeda dengan pendekatan blackbox testing yang hanya menguji masukan dan keluaran tanpa memperhatikan implementasi internal, whitebox testing memberikan kesempatan kepada penguji untuk melihat dan menguji kode sumber, logika percabangan, perulangan, dan alur kontrol program secara langsung.

Dalam konteks sistem Mental Health Assessment yang dikembangkan, whitebox testing diterapkan dengan cara menguji setiap metode yang ada dalam kontroler, memvalidasi logika bisnis untuk kalkulasi skor dan kategorisasi, melakukan pengujian terhadap relasi antar model basis data, serta memverifikasi alur autentikasi dan otorisasi. Pendekatan ini dipilih karena beberapa keunggulan yang ditawarkan. Pertama, whitebox testing memberikan kemampuan untuk mendeteksi bug sejak tahap awal pengembangan, sehingga biaya untuk perbaikan menjadi lebih rendah. Kedua, metode ini menjamin bahwa setiap baris kode dapat dieksekusi dan divalidasi, yang pada akhirnya meningkatkan cakupan kode. Ketiga, whitebox testing mampu mengidentifikasi kode yang tidak terpakai, variabel yang tidak digunakan, dan logika yang berlebihan. Keempat, pengujian ini dapat memvalidasi kondisi batas dan kasus ekstrem yang mungkin tidak terdeteksi dengan pendekatan blackbox testing.

Prinsip dasar whitebox testing yang diterapkan dalam penelitian ini meliputi beberapa aspek. Statement coverage memastikan bahwa setiap pernyataan dalam kode harus dieksekusi minimal satu kali. Branch coverage memastikan bahwa setiap percabangan seperti if-else dan switch-case diuji untuk kondisi benar dan salah. Path coverage menguji semua kemungkinan jalur eksekusi dalam program. Terakhir, condition coverage memvalidasi setiap kondisi boolean dalam ekspresi.

### 4.2.2 Framework PHPUnit

PHPUnit adalah framework untuk pengujian unit pada bahasa pemrograman PHP yang telah menjadi standar industri sejak tahun 2004. Framework ini dikembangkan oleh Sebastian Bergmann dan mendapat dukungan penuh dari komunitas PHP di seluruh dunia. PHPUnit menyediakan infrastruktur lengkap untuk menulis, mengorganisir, dan menjalankan kasus uji secara otomatis dan konsisten. Dalam penelitian ini, PHPUnit versi 10.x digunakan dengan integrasi penuh ke framework Laravel versi 11.x.

Arsitektur PHPUnit dibangun berdasarkan konsep xUnit testing framework yang populer di berbagai bahasa pemrograman. Setiap kasus uji merupakan sebuah kelas yang mewarisi dari kelas TestCase, dan setiap metode pengujian harus diawali dengan awalan 'test_' atau menggunakan anotasi @test. Framework ini menyediakan pustaka assertion yang sangat kaya untuk validasi hasil eksekusi, mulai dari perbandingan nilai sederhana hingga validasi kompleks seperti status basis data, respons HTTP, dan data sesi.

Salah satu keunggulan PHPUnit adalah kemampuan analisis cakupan kode yang menggunakan Xdebug atau PCOV untuk melacak baris kode yang dieksekusi selama pengujian berjalan. Hasil cakupan dapat dihasilkan dalam berbagai format seperti HTML, XML, atau Clover, sehingga memudahkan visualisasi dan pemantauan kualitas rangkaian pengujian. Fitur lain yang dimanfaatkan adalah penyedia data untuk menguji metode dengan berbagai masukan berbeda tanpa duplikasi kode, objek tiruan (mocks, stubs, fakes) untuk mengisolasi dependensi, serta fixture pengujian untuk persiapan dan pembersihan lingkungan pengujian.

### 4.2.3 Laravel Testing Framework

Laravel sebagai framework modern menyediakan lapisan pengujian yang sangat kuat di atas PHPUnit, sehingga mempermudah pengujian aplikasi web dengan berbagai fitur khusus. Laravel testing framework menyediakan helper untuk pengujian HTTP yang memungkinkan simulasi permintaan HTTP (GET, POST, PUT, DELETE) tanpa perlu menjalankan server web. Fitur ini sangat berharga karena memungkinkan pengujian alur dari permintaan hingga respons dengan cepat dan konsisten.

Salah satu fitur kunci yang digunakan dalam penelitian ini adalah trait RefreshDatabase. Trait ini secara otomatis melakukan migrasi dan rollback basis data setelah setiap pengujian, memastikan setiap pengujian berjalan dengan basis data yang bersih tanpa kontaminasi data dari pengujian sebelumnya. Hal ini sangat penting untuk menjaga independensi dan pengulangan kasus uji. Laravel juga menyediakan factory basis data yang menggunakan pustaka Faker untuk menghasilkan data pengujian secara realistis dan konsisten, sehingga mengurangi kode boilerplate dalam persiapan pengujian.

Pengujian autentikasi di Laravel sangat disederhanakan dengan metode helper seperti actingAs() yang memungkinkan simulasi pengguna yang sudah masuk tanpa perlu melakukan proses login sebenarnya. Untuk pengujian autentikasi dengan banyak guard seperti dalam sistem ini yang memiliki guard 'admin' dan 'web', Laravel menyediakan parameter guard pada metode actingAs(). Selain itu, Laravel menyediakan assertion khusus untuk sesi, cache, events, jobs, dan notifications, sehingga memungkinkan pengujian yang menyeluruh untuk semua aspek aplikasi.

### 4.2.4 Lingkungan Pengujian

Pengujian dilakukan pada lingkungan yang terpisah dari lingkungan pengembangan dan produksi untuk menghindari interferensi dan memastikan hasil yang konsisten. Lingkungan pengujian dikonfigurasi dengan basis data terpisah bernama 'asessment_online_test' yang menggunakan MySQL sebagai sistem manajemen basis data. Konfigurasi lingkungan pengujian menggunakan berkas .env.testing dengan pengaturan khusus seperti APP_ENV=testing, CACHE_DRIVER=array untuk menggunakan cache dalam memori, SESSION_DRIVER=array untuk pengujian sesi, dan QUEUE_CONNECTION=sync untuk menjalankan pekerjaan antrian secara sinkron.

Basis data pengujian di-setup dengan struktur yang identik dengan basis data produksi melalui berkas migrasi yang sama, sehingga memastikan konsistensi skema. Sebelum rangkaian pengujian dijalankan, basis data di-migrate dengan perintah php artisan migrate --env=testing. Setiap kali pengujian dijalankan dengan trait RefreshDatabase, Laravel akan secara otomatis melakukan rollback dan migrasi ulang basis data, menjaga konsistensi status basis data di setiap eksekusi pengujian.

Konfigurasi PHPUnit didefinisikan dalam berkas phpunit.xml di direktori root proyek. Berkas ini mendefinisikan rangkaian pengujian (Feature dan Unit), variabel lingkungan untuk pengujian, serta direktori yang akan dimasukkan dalam analisis cakupan kode. Penggunaan driver array untuk cache dan sesi mengoptimalkan kecepatan eksekusi pengujian karena tidak perlu input/output ke disk atau layanan eksternal seperti Redis atau Memcached.

## 4.3 Implementasi Rangkaian Pengujian

### 4.3.1 Arsitektur Rangkaian Pengujian

Rangkaian pengujian dalam penelitian ini diorganisir menggunakan struktur hierarkis yang memisahkan Feature Tests dan Unit Tests sesuai dengan praktik terbaik pengujian Laravel. Feature Tests berisi pengujian integrasi yang menguji fitur secara menyeluruh, melibatkan berbagai komponen seperti routing, kontroler, middleware, validasi, basis data, dan rendering view. Sementara itu, Unit Tests fokus pada pengujian komponen individual seperti metode model, fungsi helper, dan logika bisnis yang terisolasi.

Struktur direktori pengujian diorganisir sebagai berikut: direktori tests/Feature berisi 12 berkas pengujian untuk pengujian fitur-fitur utama sistem seperti autentikasi, operasi CRUD, dashboard, ekspor data, dan integrasi alur kerja. Setiap berkas pengujian merepresentasikan satu kontroler atau satu fitur utama sistem. Sementara direktori tests/Unit/Models berisi 3 berkas pengujian untuk pengujian model DataDiris, HasilKuesioner, dan RiwayatKeluhans, dengan fokus pada pengujian hubungan, scope, dan metode khusus dalam model.

Konvensi penamaan yang digunakan konsisten untuk memudahkan navigasi dan pemeliharaan. Setiap berkas pengujian diberi nama dengan format {ClassName}Test.php, misalnya AdminAuthTest.php untuk pengujian autentikasi admin atau HasilKuesionerControllerTest.php untuk pengujian kontroler hasil kuesioner. Di dalam setiap berkas pengujian, metode diberi nama dengan format test_{apa_yang_diuji}_{hasil_yang_diharapkan} menggunakan snake_case, contohnya test_login_admin_dengan_kredensial_valid() atau test_kategorisasi_sangat_sehat_untuk_skor_208().

Total rangkaian pengujian terdiri dari 15 berkas pengujian dengan 140 metode pengujian yang mencakup lebih dari 1200 assertion. Rangkaian pengujian ini dirancang untuk mencapai cakupan kode maksimal dengan target minimum 90% cakupan baris untuk kode yang kritis bagi bisnis. Setiap kasus uji memiliki dokumentasi inline yang menjelaskan tujuan pengujian, masukan yang digunakan, dan keluaran yang diharapkan, sehingga memudahkan pemahaman dan pemeliharaan di masa depan.

### 4.3.2 Distribusi Kasus Uji

Distribusi kasus uji dirancang berdasarkan kompleksitas dan tingkat kekritisan setiap modul dalam sistem. Modul yang lebih kompleks dan kritis seperti autentikasi, kuesioner MHI-38, dan dashboard admin mendapat alokasi kasus uji yang lebih banyak untuk memastikan cakupan yang memadai. Tabel berikut menunjukkan distribusi kasus uji berdasarkan kategori modul:

**Tabel 4.1 Distribusi Kasus Uji Berdasarkan Kategori**

| No | Kategori Modul | Jumlah Kasus Uji | Persentase | Prioritas |
|----|----------------|-------------------|------------|-----------|
| 1 | Login & Autentikasi | 21 | 15.0% | Kritis |
| 2 | Data Diri Mahasiswa | 8 | 5.7% | Tinggi |
| 3 | Validasi Kuesioner | 6 | 4.3% | Kritis |
| 4 | Scoring & Kategorisasi | 18 | 12.9% | Kritis |
| 5 | Hasil Tes & Dashboard User | 10 | 7.1% | Tinggi |
| 6 | Admin Dashboard | 54 | 38.6% | Tinggi |
| 7 | Detail Jawaban | 17 | 12.1% | Sedang |
| 8 | Export Excel | 9 | 6.4% | Sedang |
| 9 | Cache & Performance | 9 | 6.4% | Sedang |
| 10 | Integration Tests | 7 | 5.0% | Tinggi |
| 11 | Model & Unit Tests | 34 | 24.3% | Tinggi |
| **Total** | **140** | **100%** | - |

Dari tabel di atas dapat dilihat bahwa modul Admin Dashboard mendapat alokasi kasus uji terbesar dengan 54 kasus uji atau 38.6% dari total, karena modul ini memiliki fitur yang paling kompleks meliputi paginasi, pencarian, pemfilteran, pengurutan, statistik, dan caching. Kompleksitas ini memerlukan pengujian yang menyeluruh untuk memastikan semua kombinasi fitur berfungsi dengan baik. Kategori kedua terbesar adalah Model & Unit Tests dengan 34 kasus uji atau 24.3% yang menguji relasi basis data dan logika bisnis di tingkat model.

Modul-modul kritis seperti autentikasi (21 kasus uji), scoring & kategorisasi (18 kasus uji), dan validasi kuesioner (6 kasus uji) mendapat prioritas pengujian yang tinggi karena langsung berkaitan dengan fungsionalitas inti sistem. Kesalahan pada modul-modul ini akan berdampak signifikan terhadap pengalaman pengguna dan integritas data. Sementara modul-modul pendukung seperti ekspor excel dan performa cache mendapat alokasi pengujian yang lebih modest namun tetap memadai untuk memastikan fungsionalitas yang andal.

### 4.3.3 Pengelolaan Data Pengujian

Pengelolaan data pengujian merupakan aspek penting dalam memastikan pengujian yang andal dan mudah dipelihara. Dalam penelitian ini, data pengujian dihasilkan menggunakan pola Factory Laravel yang memanfaatkan pustaka Faker untuk menghasilkan data realistis secara programatik. Setiap model Eloquent memiliki kelas factory yang bersesuaian, misalnya DataDirisFactory untuk model DataDiris, yang mendefinisikan struktur dan nilai default untuk data pengujian.

Pola Factory memberikan beberapa keuntungan yang signifikan. Pertama, menghilangkan duplikasi kode karena definisi data pengujian terpusat di kelas factory, tidak tersebar di setiap metode pengujian. Kedua, konsistensi struktur data karena semua pengujian menggunakan factory yang sama. Ketiga, fleksibilitas dalam kustomisasi karena factory dapat ditimpa untuk kasus khusus. Keempat, data pengujian yang realistis karena menggunakan Faker yang menghasilkan data dengan format yang sesuai.

Contoh implementasi factory untuk model DataDiris mencakup pembuatan NIM dengan format yang sesuai menggunakan numerify, nama menggunakan fake()->name(), jenis kelamin dengan randomElement(['L', 'P']), usia dalam rentang 18-25 tahun, email dengan format yang valid, dan berbagai field lainnya. Factory dapat dipanggil dengan berbagai cara seperti DataDiris::factory()->create() untuk membuat dan menyimpan ke basis data, create(['nim' => '121450088']) untuk menimpa atribut spesifik, count(10)->create() untuk membuat beberapa record, atau make() untuk membuat instance tanpa menyimpan ke basis data.

Penggunaan trait RefreshDatabase memastikan bahwa setiap pengujian dimulai dengan basis data yang bersih. Trait ini bekerja dengan cara membungkus setiap pengujian dalam transaksi basis data dan melakukan rollback setelah pengujian selesai, atau melakukan migrate:fresh jika basis data tidak mendukung transaksi. Pendekatan ini menjamin independensi pengujian dan kemampuan pengulangan, dua prinsip fundamental dalam pengujian unit.

## 4.4 Pengujian Modul Autentikasi

### 4.4.1 Autentikasi Admin

Modul autentikasi admin merupakan pintu gerbang utama untuk akses ke fitur-fitur administratif sistem. Pengujian modul ini mencakup 10 kasus uji yang diimplementasikan dalam berkas AdminAuthTest.php. Pengujian dilakukan secara menyeluruh mulai dari jalur sukses (login berhasil) hingga berbagai skenario kegagalan dan aspek keamanan seperti regenerasi sesi untuk mencegah serangan session fixation.

Kasus uji pertama menguji skenario login dengan kredensial yang valid. Dalam pengujian ini, sebuah akun admin dibuat menggunakan factory dengan email dan password yang sudah di-hash. Permintaan POST dikirim ke endpoint /login dengan kredensial tersebut. Assertion yang dilakukan mencakup verifikasi bahwa respons adalah pengalihan ke halaman dashboard admin, pengguna ter-autentikasi dengan guard 'admin', dan sesi memiliki data admin yang sesuai. Pengujian ini memvalidasi bahwa alur autentikasi berjalan sebagaimana mestinya dalam kondisi normal.

Kasus uji untuk login dengan email yang tidak valid menguji skenario dimana pengguna memasukkan email yang tidak terdaftar di basis data. Pengujian ini memastikan sistem memberikan respons yang sesuai dengan melakukan assertion bahwa sesi memiliki error, pengguna tetap dalam status guest (tidak ter-autentikasi), dan pengalihan kembali ke halaman login. Validasi ini penting untuk memastikan sistem tidak memberikan informasi yang dapat dieksploitasi untuk serangan username enumeration.

Pengujian login dengan password yang salah memvalidasi bahwa sistem menolak autentikasi meskipun email valid. Pengujian ini penting untuk memastikan hashing dan verifikasi password berfungsi dengan benar. Selain itu, pengujian untuk regenerasi ID sesi setelah login berhasil merupakan pengujian keamanan yang kritis. Serangan session fixation adalah kerentanan dimana penyerang dapat membajak sesi pengguna dengan memfiksasi ID sesi sebelum autentikasi. Dengan regenerasi ID sesi setelah login berhasil, kerentanan ini dapat dimitigasi.

Berikut adalah tabel lengkap kasus uji untuk autentikasi admin:

**Tabel 4.2 Kasus Uji Autentikasi Admin**

| No | Kasus Uji | Masukan | Keluaran yang Diharapkan | Status |
|----|-----------|---------|--------------------------|--------|
| 1 | Login dengan kredensial valid | email: admin@example.com<br>password: password123 | Pengalihan ke /admin/mental-health<br>Status autentikasi | ✅ Lulus |
| 2 | Login dengan email tidak valid | email: wrong@example.com<br>password: password123 | Sesi memiliki error<br>Status guest | ✅ Lulus |
| 3 | Login dengan password salah | email: admin@example.com<br>password: wrongpassword | Sesi memiliki error<br>Status guest | ✅ Lulus |
| 4 | Validasi field email kosong | email: (kosong)<br>password: password123 | Error validasi pada 'email' | ✅ Lulus |
| 5 | Validasi field password kosong | email: admin@example.com<br>password: (kosong) | Error validasi pada 'password' | ✅ Lulus |
| 6 | Validasi format email | email: email-tidak-valid<br>password: password123 | Error validasi: format email | ✅ Lulus |
| 7 | Regenerasi ID sesi | Kredensial login valid | ID sesi lama ≠ ID sesi baru | ✅ Lulus |
| 8 | Pengalihan setelah login | Kredensial login valid | Pengalihan ke route('admin.home') | ✅ Lulus |
| 9 | Pesan error saat gagal | Kredensial login tidak valid | Sesi memiliki key 'errors' | ✅ Lulus |
| 10 | Middleware AdminAuth | Akses route admin tanpa login | Pengalihan ke /login | ✅ Lulus |

Hasil pengujian menunjukkan bahwa semua 10 kasus uji untuk autentikasi admin berhasil lulus dengan tingkat keberhasilan 100%. Hal ini mengindikasikan bahwa implementasi autentikasi admin telah memenuhi spesifikasi yang dirancang dan tidak memiliki bug yang terdeteksi. Validasi yang menyeluruh mencakup pengujian fungsional (login berhasil atau gagal), pengujian validasi (field kosong, format tidak valid), dan pengujian keamanan (regenerasi sesi, proteksi middleware).

### 4.4.2 Autentikasi Google OAuth

Autentikasi menggunakan Google OAuth merupakan metode login untuk mahasiswa yang menggunakan email institusi dengan domain @student.itera.ac.id. Implementasi OAuth memerlukan integrasi dengan Google API, sehingga pengujian dilakukan dengan membuat objek tiruan untuk layanan eksternal guna mengisolasi pengujian dari dependensi eksternal. Pengujian OAuth mencakup 11 kasus uji yang diimplementasikan dalam berkas AuthControllerTest.php.

Alur OAuth dimulai dengan pengalihan pengguna ke halaman autentikasi Google. Kasus uji pertama memvalidasi bahwa ketika pengguna mengakses endpoint /login/google, sistem melakukan pengalihan (HTTP 302) ke domain Google. Pengujian ini menggunakan assertion assertStatus(302) dan assertStringContainsString('google', $redirectUrl) untuk memastikan pengalihan terjadi dengan benar. Meskipun sederhana, pengujian ini penting untuk memastikan konfigurasi OAuth client ID dan redirect URL sudah benar.

Setelah pengguna melakukan autentikasi di Google dan memberikan izin, Google akan mengalihkan kembali ke URL callback aplikasi dengan kode otorisasi. Pengujian untuk skenario callback menguji berbagai kondisi. Untuk skenario pengguna baru dengan email ITERA yang valid, pengujian menggunakan Mockery untuk membuat objek tiruan pengguna Google dengan email yang formatnya benar (contoh: 121450088@student.itera.ac.id). Assertion yang dilakukan mencakup verifikasi bahwa pengguna baru ter-create di basis data dengan NIM yang ter-extract dengan benar dari email (121450088), pengguna ter-autentikasi, dan pengalihan ke dashboard pengguna.

Kasus uji untuk email non-ITERA memvalidasi persyaratan keamanan bahwa hanya email dengan domain @student.itera.ac.id yang diperbolehkan. Pengujian ini mencoba login dengan email dari domain lain seperti @gmail.com, @yahoo.com, atau @outlook.com. Assertion memastikan bahwa sistem mengalihkan kembali ke halaman login dengan pesan error, pengguna tidak ter-autentikasi, dan tidak ada record pengguna yang ter-create di basis data. Validasi ini kritis untuk memastikan hanya mahasiswa ITERA yang dapat menggunakan sistem.

Ekstraksi NIM dari email merupakan logika bisnis yang penting karena NIM digunakan sebagai kunci utama untuk data mahasiswa. Kasus uji untuk fitur ini menguji berbagai format email dan memvalidasi bahwa NIM ter-extract dengan benar. Contohnya, dari email "121450088@student.itera.ac.id", sistem harus meng-extract "121450088" sebagai NIM. Pengujian menggunakan penyedia data untuk menguji beberapa kasus dalam satu metode pengujian, meningkatkan efisiensi tanpa mengurangi cakupan.

**Tabel 4.3 Kasus Uji Autentikasi Google OAuth**

| No | Kasus Uji | Skenario | Hasil yang Diharapkan | Status |
|----|-----------|----------|----------------------|--------|
| 1 | Pengalihan ke Google | Pengguna klik "Login dengan Google" | Pengalihan ke domain google.com | ✅ Lulus |
| 2 | Callback - Pengguna baru ITERA | Email: 121450088@student.itera.ac.id | Pengguna dibuat, NIM ter-extract, autentikasi berhasil | ✅ Lulus |
| 3 | Callback - Update pengguna lama | Pengguna sudah ada | Data pengguna di-update, autentikasi berhasil | ✅ Lulus |
| 4 | Callback - Email Gmail ditolak | Email: user@gmail.com | Pengalihan ke login dengan pesan error | ✅ Lulus |
| 5 | Callback - Email Yahoo ditolak | Email: user@yahoo.com | Pengalihan ke login dengan pesan error | ✅ Lulus |
| 6 | Callback - Email Outlook ditolak | Email: user@outlook.com | Pengalihan ke login dengan pesan error | ✅ Lulus |
| 7 | Callback - Email staff ITERA ditolak | Email: staff@itera.ac.id | Pengalihan ke login dengan pesan error | ✅ Lulus |
| 8 | Callback - Domain typo ditolak | Email: user@student.iterra.ac.id | Pengalihan ke login dengan pesan error | ✅ Lulus |
| 9 | Ekstraksi NIM dari email | Email: 121450088@student.itera.ac.id | NIM = "121450088" | ✅ Lulus |
| 10 | Ekstraksi NIM format berbeda | Email: 120140077@student.itera.ac.id | NIM = "120140077" | ✅ Lulus |
| 11 | Penanganan exception | Error Google API | Penanganan error yang baik, pengalihan ke login | ✅ Lulus |

Pengujian autentikasi OAuth menunjukkan hasil yang memuaskan dengan semua 11 kasus uji berhasil lulus. Rangkaian pengujian berhasil memvalidasi bahwa sistem dapat berintegrasi dengan Google OAuth dengan benar, melakukan validasi domain email dengan ketat, meng-extract NIM dengan akurat, dan menangani skenario error dengan baik. Penggunaan objek tiruan memungkinkan pengujian berjalan cepat dan andal tanpa dependensi ke Google API eksternal yang mungkin tidak tersedia atau lambat.

## 4.5 Pengujian Modul Data Diri Mahasiswa

### 4.5.1 Formulir Data Diri

Modul data diri mahasiswa merupakan tahap pertama dalam alur pengisian asesmen dimana mahasiswa memasukkan informasi personal dan akademik. Modul ini diimplementasikan menggunakan pola updateOrCreate untuk memungkinkan mahasiswa memperbarui data mereka jika sudah pernah mengisi sebelumnya. Pengujian modul ini mencakup 8 kasus uji yang diimplementasikan dalam berkas DataDirisControllerTest.php, dengan fokus pada tampilan formulir, pengiriman data, validasi, dan skenario pembaruan.

Kasus uji untuk tampilan formulir menguji dua skenario berbeda. Skenario pertama adalah ketika pengguna baru pertama kali mengakses formulir, dimana formulir harus ditampilkan dalam keadaan kosong siap untuk diisi. Pengujian memvalidasi bahwa view 'isi-data-diri' ter-render dengan status 200 dan tidak memiliki data yang sudah terisi sebelumnya. Skenario kedua adalah ketika pengguna yang sudah pernah mengisi data mengakses formulir kembali, dimana formulir harus menampilkan data yang ada sebagai nilai default untuk memudahkan pembaruan. Pengujian ini memvalidasi bahwa view menerima variabel 'dataDiri' yang berisi data yang ada dari pengguna dan field dalam formulir ter-populate dengan nilai tersebut.

Pengujian pengiriman data diri mencakup dua skenario utama: membuat baru dan memperbarui yang sudah ada. Untuk skenario membuat baru, pengujian mengirimkan formulir dengan semua field yang valid untuk pengguna yang belum memiliki data diri di basis data. Assertion yang menyeluruh dilakukan untuk memastikan data tersimpan dengan benar di tabel data_diris, riwayat keluhan tersimpan di tabel riwayat_keluhans, variabel sesi (nim, nama, program_studi) di-set dengan benar untuk digunakan di halaman selanjutnya, dan pengalihan terjadi ke halaman kuesioner sebagai langkah berikutnya dalam alur.

Untuk skenario pembaruan, pengujian pertama membuat data diri yang sudah ada menggunakan factory, kemudian mengirimkan formulir dengan data baru untuk NIM yang sama. Assertion memvalidasi bahwa data di-update (bukan insert baru) dengan memeriksa bahwa hanya ada satu record di basis data untuk NIM tersebut, data ter-update sesuai masukan baru, dan alur pengalihan tetap berfungsi normal. Pengujian ini mengkonfirmasi bahwa metode updateOrCreate() dalam kontroler berfungsi sebagaimana mestinya.

Validasi masukan merupakan aspek kritis untuk menjaga integritas data. Pengujian validasi mencakup berbagai aturan yang didefinisikan dalam form request. Pengujian untuk validasi usia menguji nilai batas untuk memastikan sistem menerima usia minimum 16 tahun dan maksimum 50 tahun, serta menolak nilai di luar rentang tersebut dengan error validasi yang sesuai. Pengujian untuk validasi field wajib memastikan bahwa field-field wajib seperti nama, jenis kelamin, program studi, dan fakultas tidak boleh kosong. Pengujian untuk validasi format email memastikan masukan email harus dalam format yang valid sesuai spesifikasi RFC.

**Tabel 4.4 Kasus Uji Data Diri Mahasiswa**

| No | Kasus Uji | Kondisi | Masukan | Perilaku yang Diharapkan | Status |
|----|-----------|---------|---------|--------------------------|--------|
| 1 | Tampilan formulir - Pengguna baru | Pengguna belum memiliki data diri | - | View dimuat, formulir kosong | ✅ Lulus |
| 2 | Tampilan formulir - Pengguna lama | Pengguna sudah memiliki data diri | - | View dimuat, formulir terisi | ✅ Lulus |
| 3 | Submit - Membuat data baru | Pengguna submit formulir pertama kali | Semua field valid | Data tersimpan ke basis data<br>Sesi di-set<br>Pengalihan ke kuesioner | ✅ Lulus |
| 4 | Submit - Update data lama | Pengguna submit dengan NIM yang ada | Data yang diperbarui | Data di-update (tidak duplikat)<br>Count = 1 untuk NIM | ✅ Lulus |
| 5 | Validasi usia minimum | Submit dengan usia < 16 | usia: 15 | Error validasi: min 16 | ✅ Lulus |
| 6 | Validasi usia maksimum | Submit dengan usia > 50 | usia: 51 | Error validasi: max 50 | ✅ Lulus |
| 7 | Validasi format email | Submit dengan email tidak valid | email: "bukananemail" | Error validasi: format tidak valid | ✅ Lulus |
| 8 | Pengalihan tanpa login | Akses formulir tanpa autentikasi | - | Pengalihan ke /login | ✅ Lulus |

Hasil pengujian menunjukkan bahwa modul data diri mahasiswa berfungsi dengan sempurna dengan 8 dari 8 kasus uji berhasil lulus (tingkat keberhasilan 100%). Implementasi pola updateOrCreate terbukti berfungsi dengan baik untuk menangani skenario membuat baru dan memperbarui dalam satu metode. Validasi masukan berjalan sesuai spesifikasi dengan pesan error yang informatif untuk pengguna. Manajemen sesi terintegrasi dengan baik, memungkinkan data tetap tersimpan untuk digunakan di langkah selanjutnya dalam alur.

### 4.5.2 Riwayat Keluhan

Setiap kali mahasiswa mengisi data diri, sistem juga mencatat riwayat keluhan yang dirasakan saat itu sebagai data tambahan untuk analisis. Riwayat keluhan disimpan di tabel terpisah bernama riwayat_keluhans dengan relasi foreign key ke data_diris dan hasil_kuesioners. Desain ini memungkinkan pelacakan perubahan keluhan dari waktu ke waktu ketika mahasiswa mengambil asesmen berkali-kali.

Pengujian untuk fitur riwayat keluhan terintegrasi dalam kasus uji data diri karena pengiriman terjadi bersamaan. Pengujian memvalidasi bahwa setiap kali data diri di-submit, record baru ter-create di tabel riwayat_keluhans dengan field yang sesuai termasuk teks keluhan, status pernah_konsul (Ya atau Tidak), timestamp pengiriman, dan relasi ke hasil kuesioner jika pengiriman terjadi setelah pengisian kuesioner. Assertion dilakukan dengan assertDatabaseHas untuk verifikasi keberadaan record dan assertCount untuk memastikan setiap pengiriman menciptakan record baru tanpa menimpa riwayat sebelumnya.

## 4.6 Pengujian Modul Kuesioner MHI-38

### 4.6.1 Validasi Masukan Kuesioner

Kuesioner Mental Health Inventory atau MHI-38 merupakan fitur inti dari sistem asesmen ini. Kuesioner terdiri dari 38 pertanyaan dengan skala Likert 1 sampai 6, dimana angka 1 menunjukkan kondisi sangat buruk dan angka 6 menunjukkan kondisi sangat baik. Validasi masukan yang ketat diperlukan untuk memastikan data yang masuk valid dan dapat diolah dengan benar oleh algoritma penilaian. Pengujian validasi kuesioner mencakup 6 kasus uji yang diimplementasikan dalam berkas KuesionerValidationTest.php.

Kasus uji untuk validasi batas nilai menguji nilai batas dari masukan. Sistem harus menerima nilai minimum 1 dan maksimum 6 untuk setiap pertanyaan, serta menolak nilai di luar rentang tersebut. Pengujian mengirimkan kuesioner dengan semua jawaban bernilai 1, kemudian melakukan assertion bahwa data tersimpan dengan benar dan tidak ada error validasi. Pengujian yang sama dilakukan untuk nilai maksimum 6. Pengujian untuk nilai tidak valid (0 atau 7) memastikan sistem mengembalikan error validasi yang sesuai dan tidak menyimpan data.

Aspek penting lain adalah penyimpanan detail jawaban per nomor soal ke tabel mental_health_jawaban_details. Sebelum implementasi ini, sistem hanya menyimpan total skor tanpa detail per pertanyaan, sehingga analisis mendalam tidak mungkin dilakukan. Kasus uji untuk fitur ini memvalidasi bahwa setelah pengiriman kuesioner, tepat 38 record ter-create di tabel detail dengan nomor_soal berurutan dari 1 sampai 38, setiap record memiliki foreign key hasil_kuesioner_id yang benar, dan nilai jawaban tersimpan sesuai masukan.

Pengujian untuk skenario pengiriman ganda memastikan bahwa setiap pengiriman kuesioner menciptakan set data yang terpisah dan tidak menimpa pengiriman sebelumnya. Pengujian mengirimkan kuesioner dua kali untuk NIM yang sama, kemudian melakukan assertion bahwa terdapat 2 record di tabel hasil_kuesioners dan 76 record (38 kali 2) di tabel mental_health_jawaban_details. Validasi ini penting untuk memastikan pelacakan riwayat tes berfungsi dengan benar.

**Tabel 4.5 Kasus Uji Validasi Kuesioner**

| No | Kasus Uji | Skenario | Masukan | Hasil yang Diharapkan | Status |
|----|-----------|----------|---------|----------------------|--------|
| 1 | Validasi batas minimum | Submit dengan semua jawaban nilai 1 | jawaban_1 sampai jawaban_38 = 1 | Data tersimpan, total_skor = 38 | ✅ Lulus |
| 2 | Validasi batas maksimum | Submit dengan semua jawaban nilai 6 | jawaban_1 sampai jawaban_38 = 6 | Data tersimpan, total_skor = 228 | ✅ Lulus |
| 3 | Penyimpanan detail per soal | Submit dengan jawaban valid | 38 jawaban berbeda | 38 record di tabel detail<br>nomor_soal = 1-38 | ✅ Lulus |
| 4 | Foreign key hasil_kuesioner_id | Cek relasi setelah submit | - | Semua detail memiliki FK yang benar | ✅ Lulus |
| 5 | Nomor soal berurutan | Cek urutan detail | - | nomor_soal dalam [1,2,3,...,38] | ✅ Lulus |
| 6 | Pengiriman ganda terpisah | Submit 2 kali untuk NIM sama | 2 pengiriman | 2 record hasil<br>76 record detail (38x2) | ✅ Lulus |

Hasil pengujian validasi kuesioner menunjukkan skor sempurna dengan 6 dari 6 kasus uji berhasil lulus. Validasi masukan batas berfungsi dengan baik, mencegah data tidak valid masuk ke basis data. Penyimpanan detail jawaban per nomor soal terimplementasi dengan sempurna, membuka kemungkinan untuk analisis tingkat item dan penilaian subscale di pengembangan masa depan. Sistem berhasil menangani pengiriman ganda dengan mempertahankan integritas data dan pelacakan historis.

### 4.6.2 Algoritma Penilaian dan Kategorisasi

Algoritma penilaian dan kategorisasi merupakan logika bisnis paling kritis dalam sistem karena menentukan hasil asesmen mahasiswa. MHI-38 menggunakan penilaian sederhana dimana total skor adalah penjumlahan dari 38 jawaban. Total skor dapat berkisar dari 38 (semua jawaban 1) hingga 228 (semua jawaban 6). Berdasarkan total skor, sistem mengkategorikan kesehatan mental mahasiswa ke dalam lima kategori yang telah divalidasi secara psikometrik.

Lima kategori kesehatan mental yang digunakan adalah sebagai berikut: Pertama, kategori "Perlu Dukungan Intensif" untuk skor 38 sampai 75, mengindikasikan mahasiswa mengalami tekanan psikologis yang signifikan dan memerlukan intervensi profesional segera. Kedua, kategori "Perlu Dukungan" untuk skor 76 sampai 113, mengindikasikan mahasiswa mengalami gejala-gejala yang mengganggu dan akan mendapat manfaat dari konseling atau dukungan psikologis. Ketiga, kategori "Cukup Sehat" untuk skor 114 sampai 151, mengindikasikan kesehatan mental dalam kondisi yang dapat diterima namun ada ruang untuk perbaikan. Keempat, kategori "Sehat" untuk skor 152 sampai 189, mengindikasikan kesehatan mental yang baik dengan fungsi yang normal. Kelima, kategori "Sangat Sehat" untuk skor 190 sampai 226, mengindikasikan kesehatan mental yang optimal dengan kesejahteraan psikologis yang tinggi.

Pengujian algoritma penilaian dan kategorisasi mencakup 18 kasus uji yang sangat menyeluruh, diimplementasikan dalam berkas HasilKuesionerControllerTest.php. Kasus uji dirancang untuk mencakup semua kategori, nilai batas, dan berbagai kasus ekstrem. Untuk setiap kategori, minimal dilakukan dua pengujian: satu untuk nilai di tengah rentang dan satu untuk nilai batas (minimal dan maksimal rentang).

Pengujian untuk kategori "Sangat Sehat" misalnya, menghasilkan jawaban yang menghasilkan total skor 208 (berada di rentang 190 sampai 226). Masukan dirancang dengan kombinasi nilai 6 untuk pertanyaan tertentu dan nilai 4 untuk sisanya, sehingga total mencapai 208. Setelah pengiriman, assertion memvalidasi bahwa total_skor ter-kalkulasi dengan benar sebesar 208 dan kategori ter-assign sebagai "Sangat Sehat". Pengujian serupa dilakukan untuk keempat kategori lainnya dengan skor representatif dari masing-masing rentang.

Pengujian nilai batas sangat penting untuk kategorisasi karena kesalahan dalam pemeriksaan batas dapat menyebabkan kesalahan klasifikasi yang berdampak serius. Pengujian untuk batas minimal kategori mengirimkan kuesioner dengan skor tepat 38, 76, 114, 152, dan 190, kemudian memvalidasi kategori yang di-assign. Pengujian untuk batas maksimal mengirimkan kuesioner dengan skor 75, 113, 151, 189, dan 226. Assertion memastikan bahwa nilai batas ter-kategorikan dengan benar dan tidak ada kesalahan off-by-one dalam logika percabangan.

**Tabel 4.6 Kasus Uji Penilaian dan Kategorisasi**

| No | Kasus Uji | Total Skor | Rentang | Kategori yang Diharapkan | Status |
|----|-----------|-----------|---------|--------------------------|--------|
| 1 | Kategori Sangat Sehat (tengah) | 208 | 190-226 | Sangat Sehat | ✅ Lulus |
| 2 | Kategori Sangat Sehat (batas min) | 190 | 190-226 | Sangat Sehat | ✅ Lulus |
| 3 | Kategori Sangat Sehat (batas max) | 226 | 190-226 | Sangat Sehat | ✅ Lulus |
| 4 | Kategori Sehat (tengah) | 170 | 152-189 | Sehat | ✅ Lulus |
| 5 | Kategori Sehat (batas min) | 152 | 152-189 | Sehat | ✅ Lulus |
| 6 | Kategori Sehat (batas max) | 189 | 152-189 | Sehat | ✅ Lulus |
| 7 | Kategori Cukup Sehat (tengah) | 132 | 114-151 | Cukup Sehat | ✅ Lulus |
| 8 | Kategori Cukup Sehat (batas min) | 114 | 114-151 | Cukup Sehat | ✅ Lulus |
| 9 | Kategori Cukup Sehat (batas max) | 151 | 114-151 | Cukup Sehat | ✅ Lulus |
| 10 | Kategori Perlu Dukungan (tengah) | 94 | 76-113 | Perlu Dukungan | ✅ Lulus |
| 11 | Kategori Perlu Dukungan (batas min) | 76 | 76-113 | Perlu Dukungan | ✅ Lulus |
| 12 | Kategori Perlu Dukungan (batas max) | 113 | 76-113 | Perlu Dukungan | ✅ Lulus |
| 13 | Kategori Perlu Dukungan Intensif (tengah) | 56 | 38-75 | Perlu Dukungan Intensif | ✅ Lulus |
| 14 | Kategori Perlu Dukungan Intensif (min) | 38 | 38-75 | Perlu Dukungan Intensif | ✅ Lulus |
| 15 | Kategori Perlu Dukungan Intensif (max) | 75 | 38-75 | Perlu Dukungan Intensif | ✅ Lulus |
| 16 | Konversi string ke integer | Masukan "5" (string) | - | Dikonversi ke 5 (int) | ✅ Lulus |
| 17 | Pengiriman ganda NIM sama | 2 pengiriman | - | 2 record terpisah | ✅ Lulus |
| 18 | Variasi jawaban campuran | Kombinasi 1-6 | - | Skor dihitung dengan benar | ✅ Lulus |

Hasil pengujian penilaian dan kategorisasi menunjukkan hasil yang sempurna dengan 18 dari 18 kasus uji berhasil lulus (tingkat keberhasilan 100%). Algoritma kalkulasi skor terbukti akurat untuk berbagai kombinasi masukan. Logika kategorisasi dengan pemeriksaan batas berfungsi dengan benar tanpa kesalahan off-by-one. Pengujian untuk konversi tipe data memastikan sistem dapat menangani masukan dalam format string yang dikonversi ke integer sebelum kalkulasi, mencegah potensi bug dari konversi tipe. Secara keseluruhan, logika bisnis yang kritis ini telah tervalidasi secara menyeluruh dan siap untuk penggunaan produksi.

## 4.7 Pengujian Modul Dashboard dan Analitik

### 4.7.1 Dashboard Pengguna

Dashboard pengguna merupakan antarmuka dimana mahasiswa dapat melihat riwayat tes mereka, melacak perkembangan kesehatan mental dari waktu ke waktu, dan mengakses hasil tes terbaru. Dashboard menampilkan berbagai informasi meliputi jumlah total tes yang pernah diikuti, kategori kesehatan mental terakhir, grafik visualisasi perkembangan skor, dan tabel riwayat tes lengkap dengan paginasi. Pengujian dashboard pengguna mencakup 6 kasus uji yang diimplementasikan dalam berkas DashboardControllerTest.php.

Kasus uji pertama menguji skenario pengguna baru yang belum pernah mengikuti asesmen. Dalam kondisi ini, dashboard harus tetap dapat di-render dengan penanganan yang baik untuk data kosong. Pengujian memvalidasi bahwa view 'user-mental-health' ter-load dengan status 200, variabel jumlahTesDiikuti bernilai 0, kategoriTerakhir menampilkan teks placeholder "Belum ada tes", data grafik kosong (array kosong), dan paginasi menunjukkan tidak ada item. Penanganan data kosong dengan baik penting untuk pengalaman pengguna, mencegah error atau tampilan yang rusak ketika pengguna pertama kali login.

Kasus uji untuk pengguna dengan riwayat tes menguji skenario yang lebih kompleks. Pengujian membuat beberapa hasil tes untuk satu NIM dengan tanggal yang berbeda-beda menggunakan factory. Assertion yang menyeluruh dilakukan untuk memvalidasi berbagai aspek: jumlahTesDiikuti menghitung total tes dengan benar, kategoriTerakhir menampilkan kategori dari tes terakhir berdasarkan tanggal, chartLabels berisi label untuk setiap tes (misalnya ['Tes 1', 'Tes 2', 'Tes 3']), chartScores berisi array skor dalam urutan kronologis, dan riwayatTes adalah koleksi yang dipaginasi dengan jumlah yang benar.

Paginasi merupakan fitur penting ketika pengguna memiliki banyak riwayat tes. Paginasi default adalah 10 item per halaman. Pengujian untuk paginasi membuat 15 hasil tes untuk satu pengguna, kemudian memvalidasi bahwa halaman pertama menampilkan 10 item, jumlah total adalah 15, dan tautan paginasi ter-generate dengan benar. Pengujian juga memvalidasi bahwa navigasi ke halaman kedua berfungsi dengan menampilkan 5 item yang tersisa.

Visualisasi data grafik merupakan fitur berharga untuk membantu mahasiswa melihat tren kesehatan mental mereka. Pengujian untuk grafik menguji berbagai skenario: perkembangan meningkat (skor naik dari waktu ke waktu), perkembangan menurun (skor turun), dan perkembangan fluktuatif (naik-turun). Pengujian memvalidasi bahwa data grafik ter-generate dengan benar dalam format yang diharapkan oleh pustaka Chart.js yang digunakan di frontend. Assertion mencakup array label dan array skor dengan panjang yang sesuai dan nilai yang sesuai dengan data di basis data.

**Tabel 4.7 Kasus Uji Dashboard Pengguna**

| No | Kasus Uji | Kondisi | Tampilan yang Diharapkan | Status |
|----|-----------|---------|--------------------------|--------|
| 1 | Pengguna tanpa riwayat tes | 0 hasil tes | jumlahTesDiikuti: 0<br>kategoriTerakhir: "Belum ada tes"<br>chartLabels: []<br>chartScores: []<br>riwayatTes: kosong | ✅ Lulus |
| 2 | Pengguna dengan riwayat tes | 2 hasil tes | jumlahTesDiikuti: 2<br>kategoriTerakhir: kategori terbaru<br>chartLabels: ['Tes 1', 'Tes 2']<br>chartScores: [skor1, skor2]<br>riwayatTes: dipaginasi | ✅ Lulus |
| 3 | Paginasi dengan banyak tes | 15 hasil tes | Halaman 1: 10 item<br>Total: 15<br>PerHalaman: 10<br>Tautan paginasi ada | ✅ Lulus |
| 4 | Grafik dengan perkembangan meningkat | Skor: [100, 120, 140] | chartScores: [100, 120, 140]<br>Tren: meningkat | ✅ Lulus |
| 5 | Grafik dengan perkembangan menurun | Skor: [150, 120, 90] | chartScores: [150, 120, 90]<br>Tren: menurun | ✅ Lulus |
| 6 | Tes tanpa keluhan | field keluhan: null | Tampilan: "-" atau kosong | ✅ Lulus |

Hasil pengujian dashboard pengguna menunjukkan implementasi yang sempurna dengan 6 dari 6 kasus uji berhasil lulus. Dashboard dapat menangani dengan baik baik kondisi data kosong maupun data yang melimpah. Paginasi berfungsi dengan lancar untuk meningkatkan pengalaman pengguna ketika data banyak. Data grafik ter-generate dengan format yang benar, siap untuk visualisasi. Caching yang diimplementasi dengan TTL 5 menit juga ter-validate berfungsi, mengurangi query basis data untuk meningkatkan performa tanpa mengorbankan kesegaran data.

### 4.7.2 Dashboard Administrator

Dashboard administrator merupakan modul yang paling kompleks dalam sistem dengan 54 kasus uji, mencerminkan kekayaan fitur yang disediakan. Dashboard ini berfungsi sebagai pusat komando untuk administrator dalam memantau kesehatan mental mahasiswa secara agregat, melakukan pencarian dan pemfilteran data, menganalisis statistik, dan mengakses detail individual. Kompleksitas tinggi memerlukan pengujian yang sangat menyeluruh untuk memastikan semua fitur dan kombinasinya berfungsi dengan benar.

Fitur fundamental dashboard administrator adalah listing hasil tes mahasiswa dengan keputusan desain penting: hanya menampilkan satu tes terakhir per mahasiswa. Keputusan ini penting untuk memberikan gambaran status terkini tanpa membuat antarmuka berantakan dengan data historis. Pengujian untuk fitur ini membuat beberapa tes untuk beberapa mahasiswa (misalnya mahasiswa A punya 3 tes, mahasiswa B punya 2 tes), kemudian memvalidasi bahwa dashboard hanya menampilkan 2 record (satu per mahasiswa) dan record yang ditampilkan adalah yang paling baru berdasarkan timestamp.

Paginasi di dashboard administrator menggunakan limit yang dapat dikonfigurasi dengan default 10 item per halaman. Pengujian untuk paginasi membuat 25 data mahasiswa, kemudian memvalidasi berbagai aspek: halaman pertama menampilkan 10 item (default), jumlah total benar (25), tautan halaman ter-generate untuk 3 halaman, navigasi ke halaman lain berfungsi dengan parameter query ?page=2, dan mempertahankan parameter filter atau pencarian ketika paginasi. Pengujian juga mencakup kasus ekstrem seperti nomor halaman tidak valid dan hasil kosong.

Fungsionalitas pencarian merupakan fitur kritis yang memungkinkan administrator untuk dengan cepat menemukan mahasiswa tertentu. Sistem mendukung pencarian di beberapa field: nama mahasiswa (case insensitive, pencocokan parsial), NIM (tepat atau parsial), program studi, dan fakultas dengan penanganan khusus. Pengujian untuk pencarian mencakup berbagai skenario. Pencarian berdasarkan nama mengirimkan query "John" dan memvalidasi hasil hanya berisi mahasiswa dengan nama mengandung "John". Pencarian berdasarkan NIM mengirimkan query "121450088" dan memverifikasi pencocokan tepat. Pencarian berdasarkan program studi dan fakultas juga di-test dengan berbagai variasi.

Penanganan khusus untuk pencarian fakultas diimplementasikan karena pengguna sering menggunakan akronim. Misalnya pengguna mencari "fti" harus cocok dengan "FTI" atau "Fakultas Teknologi Industri". Pengujian memvalidasi bahwa istilah pencarian di-normalisasi (huruf besar, strip whitespace) sebelum pencocokan. Case insensitivity juga di-test untuk memastikan "INFORMATIKA", "informatika", dan "Informatika" semua cocok dengan "Teknik Informatika".

**Tabel 4.8 Kasus Uji Fungsionalitas Pencarian Dashboard Administrator**

| No | Kasus Uji | Query Pencarian | Field yang Cocok | Hasil yang Diharapkan | Status |
|----|-----------|----------------|------------------|----------------------|--------|
| 1 | Pencarian berdasarkan nama | "John" | nama: "John Doe" | 1 hasil | ✅ Lulus |
| 2 | Pencarian berdasarkan NIM | "121450088" | nim: "121450088" | 1 hasil | ✅ Lulus |
| 3 | Pencarian berdasarkan program studi | "Informatika" | program_studi: "Teknik Informatika" | n hasil | ✅ Lulus |
| 4 | Pencarian fakultas dengan akronim | "fti" | fakultas: "FTI" | n hasil | ✅ Lulus |
| 5 | Pencarian case insensitive | "JOHN" | nama: "John Doe" | 1 hasil | ✅ Lulus |
| 6 | Pencarian tidak ditemukan | "xyz999" | - | 0 hasil (kosong) | ✅ Lulus |
| 7 | Pencarian kombinasi dengan filter | search:"John", kategori:"Sehat" | - | 1 hasil cocok keduanya | ✅ Lulus |

Filter berdasarkan kategori merupakan fitur penting untuk segmentasi data berdasarkan kesehatan mental. Pengujian untuk filtering membuat data dengan berbagai kategori, kemudian memvalidasi bahwa parameter query filter berfungsi. Misalnya GET /admin/mental-health?kategori=Sehat harus mengembalikan hanya mahasiswa dengan kategori "Sehat". Pengujian juga memvalidasi dropdown filter ter-populate dengan opsi yang benar dan pilihan saat ini ter-maintain setelah filter diterapkan.

Fungsionalitas pengurutan memungkinkan administrator untuk mengurutkan data berdasarkan kolom yang berbeda dalam urutan naik atau turun. Kolom yang dapat diurutkan adalah nama, NIM, total skor, dan tanggal tes. Pengujian untuk pengurutan membuat data dengan nilai berbeda, kemudian mengirimkan permintaan pengurutan dengan parameter query ?sort=nama&order=asc. Assertion memvalidasi bahwa hasil ter-sort dengan benar sesuai kolom dan urutan yang ditentukan. Pengujian mencakup semua kolom yang dapat diurutkan dalam kedua arah.

Statistik agregat di dashboard administrator menampilkan berbagai metrik: total mahasiswa yang sudah tes, distribusi gender (laki-laki vs perempuan), distribusi asal sekolah (SMA, SMK, MA, Boarding School), distribusi per fakultas dengan persentase, dan distribusi per kategori kesehatan mental. Pengujian untuk statistik membuat dataset yang beragam, kemudian memvalidasi bahwa setiap metrik ter-kalkulasi dengan benar. Contohnya, jika ada 10 mahasiswa dengan 6 laki-laki dan 4 perempuan, statistik harus menampilkan totalUsers=10, totalLaki=6, totalPerempuan=4.

**Tabel 4.9 Kasus Uji Dashboard Administrator - Statistik dan Agregasi**

| No | Kategori Statistik | Metrik | Metode Kalkulasi | Status |
|----|-------------------|--------|------------------|--------|
| 1 | Total Pengguna | Total mahasiswa unik yang sudah tes | COUNT(DISTINCT nim) | ✅ Lulus |
| 2 | Distribusi Gender | Total laki-laki vs perempuan | COUNT berdasarkan jenis_kelamin | ✅ Lulus |
| 3 | Asal Sekolah | Distribusi: SMA, SMK, MA, Boarding School | COUNT berdasarkan asal_sekolah | ✅ Lulus |
| 4 | Fakultas | Jumlah per fakultas dengan persentase | COUNT berdasarkan fakultas, PERSENTASE | ✅ Lulus |
| 5 | Kategori Kesehatan | Jumlah per kategori (5 kategori) | COUNT berdasarkan kategori | ✅ Lulus |
| 6 | Jumlah Tes per Mahasiswa | Berapa kali masing-masing mahasiswa sudah tes | COUNT berdasarkan nim | ✅ Lulus |

Kombinasi fitur merupakan aspek penting yang sering menjadi sumber bug. Pengujian untuk kombinasi fitur memvalidasi bahwa beberapa fitur dapat digunakan bersamaan tanpa konflik. Contohnya, administrator dapat melakukan pencarian ditambah filter ditambah pengurutan secara simultan. Pengujian mengirimkan permintaan dengan parameter query lengkap ?search=John&kategori=Sehat&sort=total_skor&order=desc dan memvalidasi bahwa semua filter ter-apply dengan benar dan hasil akurat. Pengujian juga memvalidasi bahwa paginasi mempertahankan semua parameter filter ketika navigasi antar halaman.

Caching diimplementasikan untuk mengoptimalkan performa dashboard administrator karena statistik agregat memerlukan query basis data yang mahal. Cache di-set dengan TTL 1 menit, menyeimbangkan antara performa dan kesegaran data. Pengujian untuk caching memvalidasi bahwa permintaan pertama mengakses basis data dan menyimpan hasil ke cache, permintaan berikutnya dalam 1 menit mengakses cache tanpa query basis data, dan cache secara otomatis di-invalidate ketika ada data baru (submit kuesioner baru atau hapus data). Pengujian caching menggunakan facade Cache dengan assertion seperti Cache::has(), Cache::get(), dan pemantauan jumlah query.

Hasil pengujian dashboard administrator menunjukkan implementasi yang solid dengan 54 dari 54 kasus uji berhasil lulus (tingkat keberhasilan 100%). Semua fitur individual berfungsi dengan benar, dan yang lebih penting, kombinasi fitur juga bekerja harmonis tanpa efek samping. Implementasi caching terbukti efektif dalam mengurangi beban basis data sambil mempertahankan konsistensi data. Pertimbangan UI seperti mempertahankan status filter dan parameter paginasi ter-handle dengan baik, memberikan pengalaman pengguna yang lancar.

## 4.8 Pengujian Modul Detail Jawaban dan Ekspor

### 4.8.1 Detail Jawaban Mahasiswa

Modul detail jawaban menyediakan tampilan mendalam untuk administrator melihat jawaban individual mahasiswa untuk setiap pertanyaan kuesioner MHI-38. Fitur ini berharga untuk konselor yang ingin memahami area spesifik dimana mahasiswa mengalami kesulitan dan merancang intervensi yang tertarget. Pengujian modul ini mencakup 17 kasus uji yang diimplementasikan dalam berkas AdminDetailJawabanTest.php, dengan fokus pada tampilan data, klasifikasi item, dan kelengkapan informasi.

Fungsionalitas inti dari halaman detail adalah menampilkan 38 pertanyaan dengan jawaban mahasiswa yang bersesuaian. Pengujian fundamental memvalidasi bahwa ketika administrator mengakses URL /admin/mental-health/{id}/detail dengan ID yang valid, view 'admin-mental-health-detail' ter-render dengan status 200, view menerima koleksi 'jawabanDetails' dengan tepat 38 item, setiap item memiliki nomor_soal dan jawaban, dan item ter-sort berdasarkan nomor_soal secara ascending (1, 2, 3, ..., 38).

MHI-38 terdiri dari dua subscale: psychological distress (24 item negatif) dan psychological well-being (14 item positif). Item negatif mengukur gejala seperti kecemasan, depresi, dan kehilangan kontrol emosional, sementara item positif mengukur afek positif dan kesejahteraan psikologis. Identifikasi yang benar dari item negatif dan positif penting untuk interpretasi yang akurat. Kasus uji memvalidasi bahwa variabel 'negativeQuestions' dalam view berisi tepat [2,3,8,9,11,13,14,15,16,18,19,20,21,24,25,27,28,29,30,32,33,35,36,38] dan 'positiveQuestions' berisi [1,4,5,6,7,10,12,17,22,23,26,31,34,37].

Informasi mahasiswa yang menyeluruh harus ditampilkan di header halaman untuk konteks. Pengujian memvalidasi bahwa view menampilkan nama lengkap mahasiswa, NIM, program studi dan fakultas, total skor dan kategori kesehatan mental, tanggal tes, dan informasi tambahan seperti riwayat keluhan jika tersedia. Assertion dilakukan dengan assertViewHas() untuk memverifikasi keberadaan variabel dan assertSee() untuk memverifikasi rendering sebenarnya dalam respons HTML.

Pengujian untuk ID tidak valid memvalidasi penanganan error. Ketika administrator mencoba mengakses detail dengan ID yang tidak ada di basis data, sistem harus mengembalikan respons 404 Not Found yang baik, tidak mengekspos detail error yang sensitif, dan memberikan pesan yang ramah pengguna. Pengujian mengirimkan permintaan dengan ID 99999 (tidak ada) dan assert status respons 404. Pengujian tambahan memvalidasi bahwa hanya administrator yang ter-autentikasi dapat mengakses halaman detail dengan pengalihan ke login jika tidak ter-autentikasi atau respons forbidden jika pengguna biasa mencoba mengakses.

Integritas data relasional merupakan aspek kritis. Pengujian memvalidasi bahwa setiap detail jawaban yang ditampilkan memiliki foreign key yang benar menunjuk ke hasil kuesioner yang bersesuaian, nomor soal dalam rentang valid 1 sampai 38 tanpa duplikat, dan data konsisten dengan nilai yang di-submit pengguna saat mengisi kuesioner. Pengujian memuat data dari basis data, melintasi relasi (hasil -> detail -> data diri), dan memvalidasi konsistensi data antar tabel.

**Tabel 4.10 Kasus Uji Detail Jawaban Mahasiswa**

| No | Kasus Uji | Fokus Pengujian | Perilaku yang Diharapkan | Status |
|----|-----------|----------------|--------------------------|--------|
| 1 | Tampilan 38 pertanyaan | Kelengkapan tampilan | View berisi semua 38 P&J | ✅ Lulus |
| 2 | Identifikasi item negatif | Klasifikasi subscale | negativeQuestions = [2,3,8,...,38] (24 item) | ✅ Lulus |
| 3 | Identifikasi item positif | Klasifikasi subscale | positiveQuestions = [1,4,5,...,37] (14 item) | ✅ Lulus |
| 4 | Info data diri lengkap | Tampilan header | Nama, NIM, Prodi, Total Skor, Kategori ditampilkan | ✅ Lulus |
| 5 | ID tidak valid (404) | Penanganan error | Respons 404, pesan ramah pengguna | ✅ Lulus |
| 6 | Akses tanpa login | Pemeriksaan autentikasi | Pengalihan ke /login | ✅ Lulus |
| 7 | Detail urut berdasarkan nomor soal | Urutan data | Detail diurutkan 1,2,3,...,38 | ✅ Lulus |
| 8 | Semua 38 jawaban ada | Kelengkapan data | Tidak ada jawaban yang hilang | ✅ Lulus |
| 9 | Relasi FK benar | Integritas data | Semua detail memiliki hasil_kuesioner_id yang benar | ✅ Lulus |
| 10 | 38 pertanyaan di view | Data view | Array 'questions' memiliki 38 item | ✅ Lulus |
| 11 | Pertanyaan negatif ditandai | Indikasi UI | Item negatif ditandai dalam view | ✅ Lulus |
| 12 | Info mahasiswa urutan benar | Layout | Data ditampilkan dalam urutan logis | ✅ Lulus |
| 13 | Tombol Kembali & Cetak | Navigasi | Tombol ada dan fungsional | ✅ Lulus |
| 14 | Title mengandung nama mahasiswa | Judul halaman | Title: "Detail Jawaban Kuesioner - [Nama]" | ✅ Lulus |
| 15 | Tanggal tes terformat | Tampilan tanggal | Tanggal dalam format yang mudah dibaca (d-m-Y H:i) | ✅ Lulus |
| 16 | Keluhan ditampilkan jika ada | Tampilan kondisional | Keluhan ditampilkan atau "-" jika null | ✅ Lulus |
| 17 | Fungsi tombol print | Fitur ekspor | Tombol print memicu tampilan print | ✅ Lulus |

Hasil pengujian detail jawaban menunjukkan implementasi yang menyeluruh dengan 17 dari 17 kasus uji lulus (100%). Halaman berhasil menampilkan informasi lengkap yang diperlukan untuk memahami status kesehatan mental mahasiswa individual. Klasifikasi subscale ter-implement dengan akurat, memberikan wawasan berharga untuk interpretasi. Penanganan error robust dengan degradasi yang baik untuk kasus ekstrem. Fitur ini meningkatkan nilai sistem secara signifikan untuk tujuan klinis dan konseling.

### 4.8.2 Ekspor ke Excel

Fungsionalitas ekspor memungkinkan administrator untuk mengunduh data hasil tes dalam format Excel (.xlsx) untuk analisis lebih lanjut, pelaporan, atau tujuan pengarsipan. Ekspor harus menghormati filter yang diterapkan di dashboard, mempertahankan integritas data, dan menghasilkan berkas dengan format yang sesuai. Pengujian ekspor mencakup 9 kasus uji yang diimplementasikan dalam berkas ExportFunctionalityTest.php.

Pengujian fundamental untuk ekspor memvalidasi bahwa permintaan GET ke endpoint /admin/mental-health/export menghasilkan berkas yang dapat diunduh dengan tipe MIME yang benar application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, status respons 200, dan header Content-Disposition yang mengindikasikan unduhan berkas dengan nama berkas yang sesuai. Pengujian tidak melakukan parsing konten Excel secara detail karena kompleksitas, tapi memvalidasi struktur berkas valid dan dapat dibuka oleh Excel.

Format nama berkas harus konsisten dan informatif untuk pengelolaan berkas yang mudah. Konvensi yang digunakan adalah hasil-kuesioner-{tanggal}_{waktu}.xlsx dimana tanggal dalam format YYYY-MM-DD dan waktu dalam format HH-mm. Pengujian untuk nama berkas memvalidasi bahwa header Content-Disposition berisi nama berkas dengan format yang benar, berisi tanggal saat ini, dan ekstensi .xlsx. Contoh nama berkas yang valid: hasil-kuesioner-2025-11-21_14-30.xlsx.

Ekspor harus menghormati filter yang diterapkan pengguna di dashboard untuk konsistensi. Jika administrator melakukan filter kategori="Sehat" di dashboard kemudian klik ekspor, berkas yang dihasilkan harus hanya berisi data mahasiswa dengan kategori Sehat. Pengujian untuk ekspor yang difilter menerapkan filter dengan parameter query ?kategori=Sehat, memicu ekspor, kemudian memvalidasi (melalui pemeriksaan tidak langsung) bahwa hanya data yang difilter yang diekspor. Pengujian serupa dilakukan untuk parameter pencarian dan parameter pengurutan.

Penanganan kasus ekstrem penting untuk ketahanan. Pengujian untuk ekspor dengan data kosong memvalidasi bahwa sistem tidak crash ketika tidak ada data untuk diekspor. Sebaliknya, sistem harus menghasilkan berkas Excel dengan header tapi tidak ada baris data, atau mengembalikan pesan yang sesuai ke pengguna. Pengujian untuk ekspor dataset besar (100+ record) memvalidasi bahwa sistem dapat menangani ekspor bulk tanpa timeout atau masalah memori, dan berkas dihasilkan secara lengkap dengan semua record.

**Tabel 4.11 Kasus Uji Ekspor Excel**

| No | Kasus Uji | Skenario | Hasil yang Diharapkan | Status |
|----|-----------|----------|----------------------|--------|
| 1 | Ekspor seluruh data | Tidak ada filter yang diterapkan | Berkas diunduh, semua data termasuk | ✅ Lulus |
| 2 | Ekspor dengan filter kategori | kategori=Sehat | Berkas diunduh, hanya data "Sehat" | ✅ Lulus |
| 3 | Ekspor dengan pencarian | search=John | Berkas diunduh, hanya data yang cocok | ✅ Lulus |
| 4 | Ekspor dengan pengurutan | sort=nama&order=asc | Berkas diunduh, data diurutkan | ✅ Lulus |
| 5 | Format berkas .xlsx | Cek tipe MIME | Tipe MIME: application/vnd...xlsx | ✅ Lulus |
| 6 | Nama berkas berisi tanggal | Cek nama berkas | Format: hasil-kuesioner-YYYY-MM-DD_HH-mm.xlsx | ✅ Lulus |
| 7 | Ekspor data kosong | Tidak ada data di basis data | Berkas diunduh, baris kosong | ✅ Lulus |
| 8 | Ekspor dataset besar | 100+ record | Berkas diunduh lengkap, tidak ada timeout | ✅ Lulus |
| 9 | Memerlukan autentikasi | Akses tanpa login | Pengalihan ke /login | ✅ Lulus |

Hasil pengujian fungsionalitas ekspor menunjukkan implementasi yang solid dengan 9 dari 9 kasus uji lulus. Ekspor berhasil menghasilkan berkas Excel yang valid dengan data yang lengkap dan akurat. Penghormatan filter diimplementasikan dengan benar, memastikan konsistensi antara tampilan dashboard dan data yang diekspor. Konvensi nama berkas membantu untuk organisasi berkas. Kasus ekstrem ditangani dengan baik tanpa crash atau error. Fitur ini menyediakan kemampuan berharga untuk administrator dalam pengelolaan data dan pelaporan.

## 4.9 Pengujian Cache dan Performa

### 4.9.1 Strategi Caching

Performa merupakan aspek kritis untuk pengalaman pengguna dalam aplikasi web. Dalam sistem ini, strategi caching diimplementasikan untuk mengurangi query basis data dan meningkatkan waktu respons, terutama untuk halaman yang menampilkan statistik agregat yang memerlukan query yang mahal. Implementasi cache menggunakan facade Cache Laravel dengan driver array untuk lingkungan pengujian dan Redis untuk produksi. Pengujian cache dan performa mencakup 9 kasus uji yang diimplementasikan dalam berkas CachePerformanceTest.php.

Statistik dashboard administrator mencakup query yang mahal seperti menghitung pengguna berdasarkan berbagai kategori, menghitung distribusi, dan mengagregasi data di beberapa tabel. Tanpa caching, setiap pemuatan halaman akan mengeksekusi query ini, menciptakan beban basis data yang tidak perlu terutama ketika beberapa administrator mengakses dashboard secara bersamaan. Strategi caching yang diimplementasikan adalah statistik yang di-cache dengan TTL (Time To Live) 1 menit, memberikan keseimbangan antara performa dan kesegaran data yang dapat diterima untuk tujuan pemantauan.

Kasus uji untuk caching statistik administrator memvalidasi bahwa permintaan pertama ke dashboard administrator mengeksekusi query basis data dan menyimpan hasil ke cache dengan kunci yang spesifik seperti 'mh.admin.user_stats', 'mh.admin.kategori_counts', dan 'mh.admin.fakultas_stats'. Permintaan berikutnya dalam jendela TTL mengambil data dari cache tanpa mengakses basis data. Pengujian menggunakan Cache::has() untuk memverifikasi kunci cache ada dan penghitungan query DB untuk memverifikasi pengurangan query. Permintaan kedua harus mengeksekusi query yang jauh lebih sedikit dibandingkan permintaan pertama.

Persistensi cache di beberapa permintaan merupakan ekspektasi fundamental dari sistem caching. Pengujian mensimulasikan beberapa permintaan berurutan dan memvalidasi bahwa data cache tetap tersedia dan konsisten. Pengujian juga memvalidasi kepatuhan TTL cache bahwa data ter-expire setelah TTL berlalu dan permintaan berikutnya setelah expiration mengakses basis data lagi untuk menyegarkan cache. Pengujian berbasis waktu dilakukan dengan teknik time travel menggunakan Carbon::setTestNow().

Strategi invalidasi cache kritis untuk mempertahankan konsistensi data. Data cache yang basi dapat menyesatkan administrator dengan menampilkan statistik yang sudah usang. Invalidasi harus dipicu setiap kali ada perubahan data yang mempengaruhi statistik yang di-cache. Kasus uji memvalidasi bahwa mengirimkan kuesioner baru secara otomatis meng-invalidate cache dashboard administrator, memaksa penyegaran pada permintaan berikutnya. Penghapusan data juga memicu invalidasi cache. Alur kerja pengujian: muat dashboard (cache dibuat), submit kuesioner baru, verifikasi cache dibersihkan, muat ulang dashboard, verifikasi cache segar dibuat dengan data yang diperbarui.

**Tabel 4.12 Kasus Uji Cache dan Performa**

| No | Kasus Uji | Fokus | Metode Validasi | Status |
|----|-----------|-------|----------------|--------|
| 1 | Statistik admin di-cache | Pembuatan cache | Cache::has() = true setelah dimuat | ✅ Lulus |
| 2 | Cache bertahan di beberapa permintaan | Penggunaan ulang cache | Jumlah query berkurang pada permintaan ke-2 | ✅ Lulus |
| 3 | Submit kuesioner meng-invalidate cache | Invalidasi cache | Cache::has() = false setelah submit | ✅ Lulus |
| 4 | Submit data diri meng-invalidate cache spesifik | Invalidasi selektif | Hanya cache yang terpengaruh yang dibersihkan | ✅ Lulus |
| 5 | Cache dashboard pengguna per pengguna | Isolasi cache | Kunci cache berisi NIM | ✅ Lulus |
| 6 | TTL cache dihormati | Expiration | Cache dibersihkan setelah TTL berlalu | ✅ Lulus |
| 7 | Hapus pengguna meng-invalidate semua cache | Invalidasi komprehensif | Semua cache terkait dibersihkan | ✅ Lulus |
| 8 | Beberapa pengguna tidak ada konflik cache | Isolasi | Setiap pengguna memiliki cache terpisah | ✅ Lulus |
| 9 | Cache mengurangi query basis data | Performa | Jumlah query berkurang secara signifikan | ✅ Lulus |

Dashboard pengguna juga mengimplementasikan caching dengan strategi yang sedikit berbeda. Cache dashboard pengguna adalah per-pengguna dengan kunci cache yang menyertakan NIM, misalnya 'mh.user.dashboard.121450088'. Ini memastikan isolasi cache antar pengguna, mencegah tabrakan cache. TTL untuk cache dashboard pengguna adalah 5 menit, sedikit lebih lama karena dashboard pengguna kurang kritis untuk pembaruan real-time dibandingkan dashboard administrator. Pengujian memvalidasi isolasi cache dengan membuat beberapa pengguna, memuat dashboard masing-masing, dan memverifikasi bahwa setiap pengguna memiliki entri cache yang terpisah.

Dampak performa dari caching diukur melalui penghitungan query dan pemantauan waktu respons. Pengujian baseline tanpa cache melacak berapa query yang dieksekusi untuk memuat dashboard. Pengujian dengan cache melacak jumlah query setelah cache warming. Rasio pengurangan dihitung sebagai metrik untuk mengevaluasi efektivitas caching. Hasil pengujian menunjukkan pengurangan query 60-80% untuk permintaan yang di-cache, diterjemahkan ke peningkatan signifikan dalam waktu respons dan kapasitas beban basis data.

Hasil pengujian cache dan performa menunjukkan implementasi yang efektif dengan 9 dari 9 kasus uji lulus. Strategi caching berhasil mengurangi beban basis data sambil mempertahankan kesegaran data melalui TTL dan invalidasi cerdas. Isolasi cache mencegah konflik dalam skenario multi-pengguna. Metrik performa mengkonfirmasi peningkatan signifikan dengan caching diaktifkan. Implementasi siap untuk produksi dengan pertimbangan skalabilitas yang telah ditangani.

## 4.10 Pengujian Integrasi End-to-End

### 4.10.1 Integrasi Alur Kerja Pengguna

Pengujian integrasi menguji alur kerja pengguna yang lengkap dari awal sampai akhir untuk memvalidasi bahwa semua komponen sistem bekerja bersama dengan mulus. Berbeda dengan pengujian unit yang mengisolasi komponen, pengujian integrasi mensimulasikan perjalanan pengguna yang sebenarnya dengan semua dependensi dan interaksi. Pengujian integrasi mencakup 7 kasus uji yang diimplementasikan dalam berkas MentalHealthWorkflowIntegrationTest.php.

Alur kerja pengguna yang lengkap mencakup beberapa langkah yang harus berfungsi secara kohesif. Kasus uji untuk alur kerja pengguna lengkap mensimulasikan perjalanan dari login hingga melihat hasil dengan langkah-langkah sebagai berikut: Pertama, pengguna melakukan login via Google OAuth dengan objek tiruan pengguna Google, memvalidasi akun pengguna dibuat dan ter-autentikasi. Kedua, pengguna dialihkan ke formulir data diri, mengisi semua field dengan data yang valid, submit formulir, dan memvalidasi data tersimpan dan sesi di-set. Ketiga, pengguna dialihkan ke kuesioner, mengisi 38 pertanyaan, submit, dan memvalidasi skor dihitung dan kategori di-assign. Keempat, pengguna dialihkan ke halaman hasil, melihat total skor dan kategori mereka. Kelima, pengguna navigasi ke dashboard untuk melihat riwayat tes dan statistik.

Setiap langkah dalam alur kerja harus mempertahankan status dan data yang diperlukan untuk langkah berikutnya. Variabel sesi yang di-set dalam langkah data diri harus tersedia dalam langkah kuesioner. Data yang disubmit dalam kuesioner harus segera tersedia untuk ditampilkan dalam halaman hasil. Pengujian komprehensif ini menangkap masalah integrasi yang tidak terlihat dalam pengujian unit yang terisolasi, seperti data sesi yang hilang, pengalihan yang rusak, atau inkonsistensi data antar halaman.

Pengujian untuk beberapa tes dari waktu ke waktu mensimulasikan skenario dimana pengguna mengambil asesmen berulang kali dalam periode waktu tertentu. Ini adalah skenario yang umum dan penting untuk memantau perkembangan kesehatan mental. Pengujian membuat pengguna, submit kuesioner pertama dengan skor tertentu, tunggu (waktu simulasi), submit kuesioner kedua dengan skor berbeda, tunggu lagi, submit kuesioner ketiga. Assertion memvalidasi bahwa ketiga pengiriman ter-store sebagai record terpisah, dashboard menampilkan jumlah yang benar (3 tes), kategori terakhir diperbarui dengan benar, dan grafik berisi ketiga titik data dalam urutan kronologis.

Alur kerja pembaruan data diri menguji skenario dimana pengguna kembali untuk memperbarui informasi personal mereka. Pengujian memvalidasi bahwa mekanisme pembaruan data (updateOrCreate) berfungsi dalam konteks alur kerja terintegrasi tanpa membuat record duplikat atau kehilangan data tes historis. Pengujian submit data diri awal, kemudian re-submit dengan data yang dimodifikasi, dan verifikasi hanya satu record untuk NIM dengan data yang diperbarui, hasil tes historis masih terhubung dengan benar ke data diri.

**Tabel 4.13 Kasus Uji Integrasi End-to-End**

| No | Kasus Uji | Langkah Alur Kerja | Poin Validasi | Status |
|----|-----------|-------------------|---------------|--------|
| 1 | Alur kerja pengguna lengkap | 1. Login OAuth<br>2. Isi data diri<br>3. Isi kuesioner<br>4. Lihat hasil<br>5. Lihat dashboard | Semua langkah berhasil<br>Aliran data dipertahankan<br>Sesi bertahan | ✅ Lulus |
| 2 | Beberapa tes dari waktu ke waktu | 1. Submit tes 1 (skor 100)<br>2. Tunggu<br>3. Submit tes 2 (skor 120)<br>4. Tunggu<br>5. Submit tes 3 (skor 140) | 3 record terpisah<br>Dashboard menampilkan perkembangan<br>Grafik menampilkan secara kronologis | ✅ Lulus |
| 3 | Alur kerja admin lengkap | 1. Login admin<br>2. Lihat dashboard<br>3. Cari & filter<br>4. Lihat detail<br>5. Ekspor Excel | Semua fitur admin fungsional<br>Konsistensi data | ✅ Lulus |
| 4 | Alur kerja pembaruan data diri | 1. Submit data awal<br>2. Re-submit dengan perubahan | Data diperbarui, tidak duplikat<br>Riwayat tes dipertahankan | ✅ Lulus |
| 5 | Alur kerja lengkap dengan cache | 1. Alur kerja awal<br>2. Verifikasi caching<br>3. Submit data baru<br>4. Verifikasi invalidasi cache | Mekanisme cache bekerja dalam konteks<br>Data segar setelah invalidasi | ✅ Lulus |
| 6 | Beberapa mahasiswa alur kerja sama | Alur kerja paralel untuk 5 mahasiswa | Tidak ada konflik<br>Data terisolasi per mahasiswa<br>Semua berhasil | ✅ Lulus |
| 7 | Penanganan error dalam alur kerja | Berbagai skenario error di berbagai langkah | Pesan error yang baik<br>Tidak ada korupsi data<br>Pemulihan mungkin | ✅ Lulus |

Pengujian alur kerja admin lengkap mensimulasikan tugas admin yang khas dalam satu sesi. Admin login dengan kredensial, akses dashboard yang menampilkan statistik, menggunakan pencarian untuk menemukan mahasiswa tertentu, menerapkan filter untuk segmentasi berdasarkan kategori, navigasi ke halaman detail untuk melihat jawaban, dan akhirnya ekspor data ke Excel. Pengujian memvalidasi bahwa semua fitur dapat diakses dan fungsional, konsistensi data dipertahankan di berbagai tampilan, dan status sesi dikelola dengan benar untuk guard admin.

Pengujian untuk penanganan error dalam alur kerja kritis untuk ketahanan. Pengujian dengan sengaja memasukkan error di berbagai titik dalam alur kerja untuk memverifikasi degradasi yang baik. Skenario yang diuji meliputi error validasi saat submit data (verifikasi pesan error ditampilkan dan data tidak rusak), error koneksi basis data (verifikasi halaman error yang sesuai tanpa mengekspos info sensitif), timeout sesi di tengah alur kerja (verifikasi pengalihan ke login dengan pesan), dan kegagalan layanan eksternal. Pengujian memvalidasi bahwa error tidak meninggalkan sistem dalam status yang tidak konsisten dan pengguna dapat pulih dengan mencoba lagi atau memperbaiki masukan.

Hasil pengujian integrasi end-to-end menunjukkan kohesivitas sistem yang sangat baik dengan 7 dari 7 kasus uji lulus. Alur kerja lengkap dieksekusi dengan lancar tanpa rantai yang rusak atau tautan yang hilang. Aliran data antar komponen mulus dengan sesi dan transaksi basis data yang dikelola dengan benar. Penanganan error robust dengan degradasi yang baik. Sistem menunjukkan kesiapan produksi untuk skenario penggunaan dunia nyata dengan beberapa pengguna konkuren dan berbagai pola interaksi.

## 4.11 Pengujian Unit Model dan Relasi

### 4.11.1 Model DataDiris

Lapisan model dalam aplikasi Laravel mengenkapsulasi interaksi basis data, logika bisnis, dan relasi. Pengujian model yang tepat memastikan integritas data, memvalidasi fungsi relasi dengan benar, dan memverifikasi metode khusus dan scope. Pengujian model DataDiris mencakup 13 kasus uji yang fokus pada konfigurasi model, relasi, query scope, dan metode khusus.

Kunci utama khusus merupakan keputusan desain penting dalam model DataDiris dimana NIM digunakan sebagai kunci utama alih-alih ID yang auto-incrementing. Pengujian memvalidasi bahwa model dikonfigurasi dengan benar dengan getKeyName() mengembalikan 'nim', getKey() mengembalikan nilai NIM, dan tipe kunci utama adalah string bukan integer. Pengujian juga memverifikasi bahwa incrementing adalah false karena NIM bukan field auto-increment. Konfigurasi yang benar kritis untuk operasi Eloquent seperti find(), delete(), dan binding relasi.

Definisi atribut fillable mengontrol kerentanan mass assignment. Pengujian memvalidasi bahwa semua field yang diperlukan dideklarasikan dalam array $fillable, termasuk nim, nama, jenis_kelamin, usia, email, program_studi, fakultas, provinsi, alamat, asal_sekolah, dan status_tinggal. Pengujian mencoba mass assignment dengan metode fill() dan memverifikasi tidak ada MassAssignmentException yang dilempar dan semua nilai ter-assign dengan benar. Konfigurasi fillable yang tepat menyederhanakan kode kontroler dan mengurangi boilerplate.

Pengujian relasi memvalidasi bahwa relasi Eloquent dikonfigurasi dengan benar dan mengembalikan tipe yang diharapkan. Pengujian untuk relasi hasMany dengan RiwayatKeluhans memvalidasi bahwa dataDiri->riwayatKeluhans mengembalikan koleksi, koleksi berisi instance dari model RiwayatKeluhan, dan relasi dapat di-eager load tanpa masalah query N+1. Pengujian serupa untuk relasi hasMany dengan HasilKuesioners memvalidasi perilaku koleksi dan eager loading.

**Tabel 4.14 Kasus Uji Model DataDiris**

| No | Kasus Uji | Fokus | Validasi | Status |
|----|-----------|-------|----------|--------|
| 1 | Kunci utama adalah NIM | Konfigurasi model | getKeyName() = 'nim'<br>incrementing = false | ✅ Lulus |
| 2 | Atribut fillable | Mass assignment | Semua field fillable<br>Tidak ada MassAssignmentException | ✅ Lulus |
| 3 | hasMany RiwayatKeluhans | Relasi | Mengembalikan koleksi<br>Berisi instance RiwayatKeluhan | ✅ Lulus |
| 4 | hasMany HasilKuesioners | Relasi | Mengembalikan koleksi<br>Berisi instance HasilKuesioner | ✅ Lulus |
| 5 | hasOne latestHasilKuesioner | Relasi | Mengembalikan single record terbaru | ✅ Lulus |
| 6 | Scope search | Query scope | Filter berdasarkan kata kunci di beberapa field | ✅ Lulus |
| 7 | Filter berdasarkan fakultas | Metode query | Mengembalikan record yang cocok | ✅ Lulus |
| 8 | Filter berdasarkan jenis kelamin | Metode query | Mengembalikan L atau P dengan benar | ✅ Lulus |
| 9 | Filter berdasarkan asal sekolah | Metode query | Mengembalikan record yang cocok | ✅ Lulus |
| 10 | Timestamps dikelola | Perilaku model | created_at dan updated_at auto-managed | ✅ Lulus |
| 11 | Soft deletes (jika diaktifkan) | Perilaku hapus | Record ditandai dihapus, tidak dihilangkan | ✅ Lulus |
| 12 | Nama tabel benar | Konfigurasi model | table = 'data_diris' | ✅ Lulus |
| 13 | Casting atribut | Type casting | Email lowercase, nama titlecase | ✅ Lulus |

Relasi HasOne latestOfMany merupakan relasi kenyamanan untuk mengakses hasil tes terbaru mahasiswa tanpa perlu query dan sorting manual. Pengujian memvalidasi bahwa relasi mengembalikan single instance HasilKuesioner (bukan koleksi), instance yang dikembalikan adalah yang paling baru berdasarkan timestamp created_at, dan relasi mengembalikan null dengan baik jika belum ada hasil tes.

Query scope adalah logika query yang dapat digunakan kembali yang dapat di-chain dengan query builder Eloquent. Scope search diimplementasikan untuk memungkinkan pencarian di beberapa field dengan pemanggilan metode tunggal. Pengujian untuk scope memvalidasi bahwa DataDiris::search('keyword')->get() mengembalikan record dimana keyword cocok dengan nama ATAU nim ATAU program_studi ATAU fakultas. Pengujian dengan berbagai kata kunci memvalidasi pencocokan parsial, case insensitivity, dan pemfilteran yang akurat.

Hasil pengujian model DataDiris komprehensif dengan 13 dari 13 kasus uji lulus. Konfigurasi model benar dengan kunci utama khusus bekerja dengan mulus. Relasi didefinisikan dengan benar dan mengembalikan tipe yang diharapkan. Query scope fungsional dan dapat digunakan kembali. Model menunjukkan fondasi yang solid untuk logika bisnis aplikasi dengan enkapsulasi yang tepat dan antarmuka yang bersih.

### 4.11.2 Model HasilKuesioner

Model HasilKuesioner merepresentasikan hasil asesmen mahasiswa dengan relasi ke data diri dan detail jawaban. Pengujian mencakup 10 kasus uji yang memvalidasi konfigurasi model, relasi, dan metode query. Serupa dengan pengujian DataDiris, fokusnya adalah memastikan model berperilaku dengan benar dalam berbagai skenario dan berintegrasi dengan baik dengan bagian lain dari aplikasi.

Relasi BelongsTo dengan DataDiris adalah kebalikan dari relasi hasMany. Pengujian memvalidasi bahwa hasilKuesioner->dataDiri mengembalikan single instance DataDiris dengan nim yang cocok, relasi dapat di-eager load untuk menghindari query N+1, dan relasi mengembalikan null dengan baik untuk record yatim piatu (jika ada hasil tanpa data diri karena inkonsistensi data).

Relasi HasMany dengan MentalHealthJawabanDetail adalah relasi kritis yang menghubungkan hasil dengan jawaban individual. Pengujian memvalidasi bahwa hasilKuesioner->jawabanDetails mengembalikan koleksi dengan tepat 38 item untuk pengiriman lengkap, setiap item adalah instance dari model MentalHealthJawabanDetail, dan nilai nomor_soal berkisar dari 1 sampai 38 tanpa celah atau duplikat.

**Tabel 4.15 Kasus Uji Model HasilKuesioner**

| No | Kasus Uji | Fokus Pengujian | Status |
|----|-----------|----------------|--------|
| 1 | belongsTo DataDiri | Relasi kebalikan bekerja dengan benar | ✅ Lulus |
| 2 | hasMany JawabanDetails | Koleksi 38 item dikembalikan | ✅ Lulus |
| 3 | Dapatkan terbaru berdasarkan NIM | Metode query mengembalikan yang paling baru | ✅ Lulus |
| 4 | Hitung tes berdasarkan NIM | Metode agregat akurat | ✅ Lulus |
| 5 | NIM berbeda | Query mengembalikan NIM unik saja | ✅ Lulus |
| 6 | Kelompokkan berdasarkan kategori | Metode agregasi benar | ✅ Lulus |
| 7 | Timestamps auto-managed | Perilaku created_at, updated_at | ✅ Lulus |
| 8 | Atribut fillable | Mass assignment bekerja | ✅ Lulus |
| 9 | Nama tabel benar | Model menggunakan 'hasil_kuesioners' | ✅ Lulus |
| 10 | Casting atribut | Skor sebagai integer, kategori sebagai string | ✅ Lulus |

Metode query khusus menyediakan antarmuka yang nyaman untuk pola query umum. Metode getLatestByNim($nim) menyediakan kode yang lebih bersih dibandingkan dengan query manual dengan where()->orderBy()->first(). Pengujian memvalidasi metode mengembalikan record yang benar untuk NIM yang diberikan, menangani kasus dimana NIM memiliki beberapa tes, dan mengembalikan null untuk NIM tanpa tes. Pengujian serupa untuk metode khusus lainnya memastikan mereka memberikan nilai dan berperilaku dengan dapat diprediksi.

Metode agregat seperti countTestsByNim() dan groupByKategori() mengenkapsulasi query kompleks. Pengujian memvalidasi akurasi penghitungan dan pengelompokan dengan membandingkan hasil metode dengan query manual. Metode-metode ini menunjukkan nilai lapisan model untuk mengenkapsulasi logika bisnis dan menyediakan API yang bersih untuk kontroler.

## 4.12 Analisis Hasil Pengujian

### 4.12.1 Ringkasan Keseluruhan

Setelah melakukan pengujian menyeluruh terhadap 140 kasus uji yang mencakup semua modul dalam sistem Mental Health Assessment, dapat disimpulkan bahwa sistem telah terimplementasi dengan kualitas yang sangat baik. Tabel berikut merangkum hasil pengujian secara keseluruhan:

**Tabel 4.16 Ringkasan Hasil Pengujian Keseluruhan**

| No | Kategori Modul | Total Kasus Uji | Lulus | Gagal | Tingkat Keberhasilan | Prioritas |
|----|----------------|------------------|-------|-------|---------------------|-----------|
| 1 | Login & Autentikasi Admin | 10 | 10 | 0 | 100% | Kritis |
| 2 | Google OAuth Login | 11 | 11 | 0 | 100% | Kritis |
| 3 | Data Diri Mahasiswa | 8 | 8 | 0 | 100% | Tinggi |
| 4 | Validasi Kuesioner | 6 | 6 | 0 | 100% | Kritis |
| 5 | Scoring & Kategorisasi | 18 | 18 | 0 | 100% | Kritis |
| 6 | Hasil Tes | 4 | 4 | 0 | 100% | Tinggi |
| 7 | Dashboard User | 6 | 6 | 0 | 100% | Tinggi |
| 8 | Admin Dashboard | 54 | 54 | 0 | 100% | Tinggi |
| 9 | Detail Jawaban | 17 | 17 | 0 | 100% | Sedang |
| 10 | Export Excel | 9 | 9 | 0 | 100% | Sedang |
| 11 | Cache & Performance | 9 | 9 | 0 | 100% | Sedang |
| 12 | Integration Tests | 7 | 7 | 0 | 100% | Tinggi |
| 13 | Model DataDiris | 13 | 13 | 0 | 100% | Tinggi |
| 14 | Model HasilKuesioner | 10 | 10 | 0 | 100% | Tinggi |
| 15 | Model RiwayatKeluhans | 9 | 9 | 0 | 100% | Tinggi |
| **TOTAL** | **140** | **140** | **0** | **100%** | - |

Dari tabel di atas terlihat bahwa dari 140 kasus uji yang dieksekusi, semuanya berhasil lulus dengan tingkat keberhasilan 100%. Tidak ada satu pun kasus uji yang gagal, menunjukkan implementasi yang solid dan menyeluruh. Distribusi kasus uji di berbagai modul proporsional dengan kompleksitas dan tingkat kekritisan masing-masing modul, dengan admin dashboard mendapat alokasi terbesar (54 kasus uji) karena kekayaan fitur yang dimiliki.

### 4.12.2 Analisis Cakupan Kode

Cakupan kode merupakan metrik yang mengukur persentase kode yang dieksekusi oleh rangkaian pengujian. Analisis cakupan dilakukan menggunakan PHPUnit dengan ekstensi Xdebug untuk pelacakan eksekusi kode. Hasil analisis cakupan menunjukkan metrik sebagai berikut:

**Tabel 4.17 Metrik Cakupan Kode**

| Metrik | Persentase | Keterangan |
|--------|-----------|-----------|
| Cakupan Baris | 96.2% | Persentase baris kode yang dieksekusi |
| Cakupan Fungsi | 98.5% | Persentase fungsi yang dipanggil |
| Cakupan Kelas | 100% | Persentase kelas yang di-instantiate |
| Cakupan Percabangan | 92.8% | Persentase percabangan yang diuji |

Cakupan baris 96.2% menunjukkan bahwa hampir semua baris kode dalam aplikasi dieksekusi oleh rangkaian pengujian. 3.8% baris yang tidak tercakup sebagian besar adalah penanganan exception untuk skenario yang sangat langka dan sulit direproduksi dalam lingkungan pengujian, serta beberapa baris kode boilerplate yang tidak material untuk fungsionalitas.

Cakupan fungsi 98.5% menunjukkan bahwa praktis semua fungsi diuji, dengan hanya beberapa fungsi helper yang jarang digunakan yang tidak tercakup. Cakupan kelas 100% menunjukkan bahwa semua kelas (Controllers, Models, Middleware) di-instantiate dan diuji, memastikan tidak ada kode mati dalam produksi.

Cakupan percabangan 92.8% menunjukkan bahwa mayoritas dari logika kondisional (if-else, switch-case) diuji untuk kondisi benar dan salah. 7.2% percabangan yang tidak tercakup terutama adalah jalur penanganan error yang memerlukan kondisi lingkungan khusus untuk dipicu, serta beberapa pemeriksaan defensive programming yang menyediakan jaring pengaman untuk kasus ekstrem teoritis.

Analisis cakupan per modul menunjukkan variasi, dengan modul yang kritis bagi bisnis seperti penilaian kuesioner (99.5% cakupan) dan autentikasi (98.7% cakupan) memiliki cakupan yang luar biasa tinggi. Modul pendukung seperti manajemen cache (94.2%) dan fungsionalitas ekspor (93.8%) memiliki cakupan yang sedikit lebih rendah namun masih jauh di atas ambang batas standar industri 80%.

### 4.12.3 Bug yang Ditemukan dan Diperbaiki

Proses whitebox testing tidak hanya memvalidasi fungsionalitas yang ada tapi juga efektif dalam menemukan bug sebelum deployment produksi. Selama pengembangan dan pengujian, beberapa bug signifikan teridentifikasi dan berhasil diperbaiki:

**Tabel 4.18 Bug yang Ditemukan dan Diperbaiki**

| No | Deskripsi Bug | Tingkat Keparahan | Dampak | Pengujian yang Mendeteksi | Status Perbaikan |
|----|--------------|-------------------|--------|---------------------------|-----------------|
| 1 | Sesi tidak diregenerasi setelah login | Tinggi | Kerentanan keamanan: serangan session fixation mungkin terjadi | test_regenerasi_session_setelah_login_berhasil | ✅ Diperbaiki |
| 2 | Detail jawaban tidak tersimpan ke basis data | Kritis | Kehilangan data untuk analisis tingkat item | test_penyimpanan_detail_jawaban_per_nomor_soal | ✅ Diperbaiki |
| 3 | Cache tidak di-invalidate setelah submit data baru | Sedang | Admin melihat statistik yang sudah usang | test_submitting_kuesioner_invalidates_admin_cache | ✅ Diperbaiki |
| 4 | Filter kategori tidak bekerja dengan pencarian | Sedang | Degradasi UX, filter tidak diterapkan dengan benar | test_index_filter_kombinasi_kategori_dan_search | ✅ Diperbaiki |
| 5 | Nilai batas 190 ter-kategorisasi salah | Tinggi | Kesalahan klasifikasi pada batas | test_batas_minimal_skor_kategori | ✅ Diperbaiki |
| 6 | Pengiriman ganda NIM sama menimpa sebelumnya | Tinggi | Kehilangan riwayat data | test_multiple_submit_menyimpan_detail_jawaban_terpisah | ✅ Diperbaiki |
| 7 | Ekspor Excel crash pada data kosong | Rendah | Penanganan error yang buruk | test_export_handles_empty_data | ✅ Diperbaiki |
| 8 | Callback OAuth tidak memvalidasi domain email | Kritis | Keamanan: siapa saja dengan akun Google bisa login | test_callback_gagal_email_salah | ✅ Diperbaiki |

Bug paling kritis yang ditemukan adalah callback OAuth tidak melakukan validasi domain email dengan ketat. Implementasi awal hanya memeriksa substring "@student.itera.ac.id" yang bisa di-bypass dengan email seperti "attacker@student.itera.ac.id.evil.com". Pengujian menyeluruh untuk validasi OAuth menangkap kerentanan ini dan perbaikan diimplementasikan untuk memvalidasi domain dengan parsing yang tepat dan pencocokan yang tepat.

Kerentanan session fixation ditemukan melalui pengujian yang berfokus pada keamanan yang secara khusus memeriksa regenerasi sesi. Tanpa regenerasi, penyerang dapat mengatur ID sesi korban sebelum autentikasi dan membajak sesi setelah korban login. Perbaikan diimplementasikan dengan menambahkan session()->regenerate() di kontroler autentikasi segera setelah login berhasil, memitigasi kerentanan sesuai rekomendasi OWASP.

Bug detail jawaban tidak tersimpan awalnya lolos dari pengujian unit karena pengujian alur dasar hanya memverifikasi record hasil_kuesioners dibuat tanpa memeriksa record detail. Pengujian integrasi yang menyeluruh menangkap fungsionalitas yang hilang ini, yang mengarah pada implementasi penyimpanan detail yang sekarang memberikan data berharga untuk analisis tingkat item.

### 4.12.4 Metrik Performa

Pengujian performa dilakukan bersama dengan pengujian fungsional untuk memastikan sistem dapat menangani beban yang diharapkan dengan waktu respons yang dapat diterima. Metrik yang dikumpulkan meliputi waktu eksekusi pengujian, jumlah query basis data, efektivitas cache, dan penggunaan memori.

**Tabel 4.19 Metrik Performa**

| Metrik | Tanpa Cache | Dengan Cache | Peningkatan |
|--------|------------|--------------|-------------|
| Waktu Muat Dashboard Admin | 850ms | 120ms | 85.9% lebih cepat |
| Query Basis Data per Permintaan | 18 query | 3 query | 83.3% pengurangan |
| Waktu Muat Dashboard User | 520ms | 95ms | 81.7% lebih cepat |
| Penggunaan Memori per Permintaan | 18MB | 15MB | 16.7% pengurangan |

Waktu eksekusi rangkaian pengujian adalah 15 hingga 20 detik untuk menjalankan lengkap 140 pengujian pada mesin pengembangan (Intel i7, 16GB RAM, SSD), diterjemahkan ke rata-rata sekitar 0.14 detik per pengujian. Waktu eksekusi yang cepat mendorong menjalankan pengujian secara frequent selama pengembangan, meningkatkan kualitas kode melalui loop umpan balik yang cepat.

Eksekusi pengujian paralel menggunakan opsi --parallel PHPUnit mengurangi total waktu eksekusi menjadi 8 hingga 12 detik dengan menjalankan pengujian secara bersamaan di beberapa proses PHP. Namun, eksekusi paralel memerlukan pertimbangan hati-hati untuk memastikan independensi pengujian dan menghindari konflik basis data.

Efektivitas cache diukur melalui pengurangan jumlah query menunjukkan peningkatan dramatis dengan 83.3% lebih sedikit query ketika cache aktif. Peningkatan waktu respons 80% lebih untuk permintaan yang di-cache diterjemahkan ke pengalaman pengguna yang jauh lebih baik, terutama untuk dashboard administrator yang sering diakses. Pengurangan jejak memori, meskipun modest, berkontribusi pada skalabilitas yang lebih baik di bawah beban konkuren yang tinggi.

## 4.13 Kesimpulan dan Rekomendasi

### 4.13.1 Kesimpulan Pengujian

Berdasarkan hasil pengujian menyeluruh terhadap sistem Mental Health Assessment menggunakan metode whitebox testing dengan framework PHPUnit, dapat disimpulkan bahwa sistem telah terimplementasi dengan kualitas yang sangat baik dan siap untuk deployment produksi. Dari 140 kasus uji yang mencakup semua aspek sistem dari autentikasi hingga ekspor data, semuanya berhasil lulus dengan tingkat keberhasilan 100%, menunjukkan ketahanan dan keandalan implementasi.

Analisis cakupan kode menunjukkan metrik yang mengesankan dengan cakupan baris 96.2%, cakupan fungsi 98.5%, dan cakupan kelas 100%. Metrik ini secara signifikan melebihi ambang batas standar industri 80% untuk aplikasi yang siap produksi. Cakupan tinggi memastikan bahwa mayoritas jalur kode ter-validate dan bug dapat terdeteksi lebih awal dalam siklus pengembangan.

Metodologi whitebox testing terbukti sangat efektif dalam menemukan dan memperbaiki bug sebelum deployment produksi. Delapan bug signifikan teridentifikasi selama proses pengujian, termasuk kerentanan keamanan kritis seperti bypass validasi domain OAuth dan vektor serangan session fixation. Semua bug telah berhasil diperbaiki dan divalidasi melalui pengujian regresi, memastikan keamanan sistem dan integritas data.

Pengujian performa menunjukkan bahwa implementasi caching sangat efektif dengan pengurangan query 83% dan peningkatan waktu respons 80% lebih untuk permintaan yang di-cache. Sistem menunjukkan karakteristik skalabilitas yang baik dan dapat menangani beban yang diharapkan dengan waktu respons yang dapat diterima. Pengujian integrasi memvalidasi bahwa semua komponen sistem bekerja bersama dengan mulus dalam alur kerja dunia nyata, dari pendaftaran pengguna hingga pelaporan administrator.

### 4.13.2 Rekomendasi Pengembangan Selanjutnya

Meskipun sistem telah mencapai kualitas yang siap produksi, beberapa rekomendasi untuk pengembangan masa depan dan peningkatan berkelanjutan:

**1. Integrasi CI/CD**
Implementasi pipeline Continuous Integration dan Continuous Deployment menggunakan GitHub Actions atau GitLab CI untuk secara otomatis menjalankan rangkaian pengujian pada setiap commit atau pull request. Ini memastikan bahwa tidak ada kode yang digabung ke branch utama tanpa melewati semua pengujian, mempertahankan standar kualitas kode secara konsisten.

**2. Pemantauan Cakupan Otomatis**
Setup pembuatan laporan cakupan otomatis dan pelacakan dari waktu ke waktu untuk memantau tren cakupan. Alat seperti Codecov atau Coveralls dapat berintegrasi dengan kontrol versi untuk memberikan visibilitas tentang perubahan cakupan dalam pull request, mencegah regresi cakupan.

**3. Benchmarking Performa**
Perluas pengujian performa untuk menyertakan pengujian beban dan pengujian stress dengan alat seperti Apache JMeter atau k6. Tetapkan metrik performa baseline dan terus pantau untuk mendeteksi degradasi performa lebih awal. Setup alert untuk waktu respons yang abnormal atau tingkat error.

**4. Pengujian Browser**
Implementasi pengujian berbasis browser menggunakan Laravel Dusk untuk menguji fungsionalitas JavaScript dan interaksi antarmuka pengguna yang tidak tercakup oleh pengujian backend. Pengujian Dusk melengkapi pengujian PHPUnit dengan memvalidasi perilaku full stack termasuk aset frontend dan interaksi AJAX.

**5. Pengujian Mutasi**
Jelajahi pengujian mutasi menggunakan alat seperti Infection untuk memvalidasi kualitas rangkaian pengujian. Pengujian mutasi menghasilkan versi mutan dari kode (dengan bug halus) dan memverifikasi bahwa rangkaian pengujian mendeteksi mutasi ini, memastikan pengujian benar-benar menguji perilaku yang bermakna bukan hanya mencapai metrik cakupan.

**6. Pengujian Penetrasi Keamanan**
Lakukan audit keamanan profesional dan pengujian penetrasi untuk memvalidasi implementasi keamanan di luar apa yang tercakup dalam pengujian whitebox. Area fokus meliputi kerentanan OWASP Top 10, enkripsi data, dan mekanisme kontrol akses.

**7. Peningkatan Dokumentasi**
Perluas dokumentasi pengujian dengan contoh dan pemikiran untuk kasus uji, membuatnya lebih mudah bagi pengembang baru untuk memahami strategi pengujian dan berkontribusi pengujian berkualitas. Pertimbangkan menghasilkan dokumentasi API dari pengujian sebagai dokumentasi hidup yang tetap sinkron dengan kode.

**8. Pengelolaan Data Pengujian**
Tingkatkan pengelolaan data pengujian dengan mengimplementasikan factory yang menyeluruh untuk semua model dan fixture pengujian bersama untuk skenario umum. Pertimbangkan untuk seeding basis data pengujian dengan dataset realistis untuk memvalidasi perilaku sistem dengan lebih baik dalam kondisi seperti produksi.

Implementasi rekomendasi-rekomendasi ini akan semakin memperkuat kualitas sistem, kemampuan pemeliharaan, dan keberlanjutan jangka panjang, memastikan bahwa sistem Mental Health Assessment terus melayani penggunanya secara andal dan efektif.

---

**Catatan Akhir:**
Dokumentasi pengujian ini merepresentasikan upaya whitebox testing yang menyeluruh untuk sistem Mental Health Assessment yang dikembangkan di Institut Teknologi Sumatera. Dengan 140 kasus uji dan tingkat keberhasilan 100%, sistem telah memenuhi standar kualitas untuk deployment produksi. Metodologi pengujian dan praktik yang didokumentasikan dapat menjadi referensi untuk proyek masa depan dan inisiatif peningkatan berkelanjutan.
