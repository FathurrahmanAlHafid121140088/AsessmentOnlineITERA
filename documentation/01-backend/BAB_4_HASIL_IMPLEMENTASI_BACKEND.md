# BAB IV
# PENGUJIAN DAN IMPLEMENTASI SISTEM

## 4.5 Hasil Implementasi Backend Sistem Mental Health Assessment

### 4.5.1 Gambaran Umum Implementasi

Implementasi backend sistem Mental Health Assessment dikembangkan menggunakan framework Laravel versi 11.x dengan bahasa pemrograman PHP 8.1. Pemilihan teknologi ini didasari oleh pertimbangan ekosistem Laravel yang matang, dokumentasi lengkap, serta dukungan komunitas yang aktif. Sistem ini dibangun dengan arsitektur Model-View-Controller (MVC) yang memisahkan logika bisnis, representasi data, dan antarmuka pengguna secara tegas.

Backend sistem mengelola delapan fitur utama yang saling terintegrasi. Pertama adalah autentikasi pengguna yang membedakan antara mahasiswa dan administrator. Kedua adalah pengelolaan data diri mahasiswa beserta riwayat keluhan kesehatan mental. Ketiga adalah implementasi kuesioner MHI-38 dengan validasi ketat untuk setiap jawaban. Keempat adalah kalkulasi skor otomatis dan kategorisasi tingkat kesehatan mental. Kelima adalah dashboard mahasiswa dengan visualisasi perkembangan hasil tes. Keenam adalah dashboard administrator dengan fitur analisis data komprehensif. Ketujuh adalah tampilan detail jawaban per soal untuk evaluasi mendalam. Terakhir adalah ekspor data ke format Excel untuk keperluan pelaporan.

Struktur basis data dirancang dengan prinsip normalisasi untuk menghindari redundansi sekaligus menjaga integritas referensial. Terdapat delapan tabel utama yang saling berelasi: users untuk autentikasi mahasiswa, admins untuk autentikasi administrator, data_diris untuk informasi demografis, hasil_kuesioners untuk menyimpan ringkasan hasil tes, mental_health_jawaban_details untuk menyimpan jawaban per soal, riwayat_keluhans untuk melacak keluhan dari waktu ke waktu, serta tabel migrations dan personal_access_tokens untuk keperluan framework.

### 4.5.2 Implementasi Sistem Routing

Sistem routing dalam aplikasi ini dibagi menjadi tiga kategori besar sesuai dengan tingkat akses dan jenis pengguna. Kategori pertama adalah route publik yang dapat diakses tanpa autentikasi, mencakup halaman beranda dan informasi umum tentang layanan mental health. Kategori kedua adalah route mahasiswa yang dilindungi middleware 'auth', memastikan hanya mahasiswa yang sudah login dapat mengakses fitur seperti dashboard, pengisian kuesioner, dan melihat hasil tes. Kategori ketiga adalah route administrator yang dilindungi middleware AdminAuth khusus, memberikan akses eksklusif ke fitur manajemen data.

Route mahasiswa diorganisir dengan pendekatan grup untuk mempermudah manajemen dan konsistensi. Semua route terkait fitur mental health menggunakan prefix '/mental-health' dan name prefix 'mental-health.', sehingga URL menjadi semantik dan mudah dipahami. Contohnya route untuk mengisi data diri berada di '/mental-health/isi-data-diri' dengan nama 'mental-health.isi-data-diri'. Pengelompokan ini juga memudahkan penerapan middleware secara kolektif tanpa perlu mendefinisikan pada setiap route individual.

Route administrator menggunakan prefix '/admin/mental-health' untuk membedakan dengan jelas antara area mahasiswa dan administrator. Selain middleware autentikasi, route administrator juga dilengkapi dengan mekanisme auto-logout setelah 30 menit tidak ada aktivitas. Implementasi ini penting dari sisi keamanan karena administrator mengelola data sensitif mahasiswa yang harus dilindungi dari akses tidak sah.

Untuk autentikasi, sistem mengimplementasikan dua jalur berbeda. Mahasiswa menggunakan OAuth 2.0 melalui Google dengan route '/auth/google/redirect' untuk inisiasi dan '/auth/google/callback' untuk penanganan respon. Sementara administrator menggunakan autentikasi tradisional email-password dengan route '/login' yang dilindungi middleware 'guest' untuk mencegah pengguna yang sudah login mengakses halaman login ulang.

### 4.5.3 Implementasi Autentikasi Mahasiswa

Autentikasi mahasiswa diimplementasikan menggunakan protokol OAuth 2.0 dengan Google sebagai identity provider. Pilihan ini dibuat karena seluruh mahasiswa ITERA memiliki akun Google institutional dengan format email standar NIM@student.itera.ac.id. Integrasi OAuth dilakukan melalui package Laravel Socialite yang menyediakan abstraksi tingkat tinggi untuk berbagai penyedia OAuth.

Proses autentikasi dimulai ketika mahasiswa mengklik tombol "Login dengan Google" yang memicu method redirectToGoogle() pada AuthController. Method ini menggunakan Socialite facade untuk mengarahkan pengguna ke halaman consent Google. Setelah mahasiswa memberikan izin, Google akan mengarahkan kembali ke callback URL aplikasi dengan authorization code.

Method handleGoogleCallback() menerima code tersebut dan menukarnya dengan access token melalui komunikasi server-to-server dengan Google. Token ini kemudian digunakan untuk mengambil informasi profil mahasiswa seperti nama, email, dan Google ID. Tahap validasi kritis dilakukan pada titik ini dengan memeriksa format email menggunakan regular expression '/(\d{9})@student\.itera\.ac\.id$/'. Validasi ini memastikan hanya email mahasiswa ITERA yang valid, serta mengekstrak NIM dari alamat email.

Jika validasi berhasil, sistem melakukan operasi upsert pada tabel users menggunakan method updateOrCreate(). Method ini mencari record berdasarkan NIM, jika ditemukan akan diperbarui dengan data terbaru dari Google, jika tidak akan dibuat record baru. Pendekatan ini mengatasi skenario mahasiswa yang login pertama kali maupun mahasiswa yang sudah pernah login sebelumnya dalam satu operasi atomik.

Paralel dengan tabel users, sistem juga melakukan operasi pada tabel data_diris menggunakan method firstOrCreate(). Berbeda dengan updateOrCreate(), method ini hanya membuat record baru jika belum ada dan tidak mengubah record yang sudah ada. Strategi ini penting karena data_diris akan dilengkapi oleh mahasiswa melalui form terpisah, sehingga kita tidak ingin menimpa data yang sudah diisi dengan data minimal dari Google.

Setelah semua operasi basis data selesai, mahasiswa di-login menggunakan Auth::login($user) dan session autentikasi dibuat. Pengguna kemudian diarahkan ke halaman yang dituju sebelumnya (menggunakan intended()) atau ke dashboard mental health sebagai fallback. Seluruh proses ini dibungkus dalam try-catch block untuk menangani kemungkinan kegagalan komunikasi dengan Google atau masalah basis data.

### 4.5.4 Implementasi Autentikasi Administrator

Administrator menggunakan mekanisme autentikasi berbeda yang lebih tradisional namun tetap aman. Sistem login administrator dibangun di atas fitur multi-authentication guards Laravel, di mana guard 'admin' dikonfigurasi untuk menggunakan tabel admins dengan driver eloquent. Pemisahan guard ini memastikan session administrator terpisah sepenuhnya dari session mahasiswa.

Halaman login administrator menampilkan form sederhana dengan dua field: email dan password. Ketika form disubmit, method login() pada AdminAuthController melakukan validasi input menggunakan Laravel's validation system. Rule yang diterapkan adalah 'required|email' untuk email dan 'required|string' untuk password. Validasi ini bersifat sanitasi awal sebelum kredensial diverifikasi ke basis data.

Proses verifikasi dilakukan dengan method Auth::guard('admin')->attempt($credentials). Method attempt() secara otomatis melakukan hashing password input dengan bcrypt dan membandingkannya dengan hash yang tersimpan di basis data. Bcrypt dipilih karena termasuk algoritma slow hash function yang resisten terhadap brute force attack, dengan cost factor yang dapat disesuaikan seiring peningkatan kemampuan hardware.

Jika kredensial cocok, Laravel melakukan beberapa operasi keamanan secara otomatis. Pertama adalah regenerasi session ID untuk mencegah session fixation attack. Kedua adalah penyimpanan user identifier ke session dengan enkripsi. Administrator kemudian diarahkan ke dashboard dengan URL '/admin/mental-health' menggunakan method intended() yang menghormati intended URL sebelum login jika ada.

Kasus kegagalan login ditangani dengan mengembalikan administrator ke halaman login disertai pesan error yang samar "Email atau password salah!". Pesan yang samar ini penting dari perspektif keamanan untuk mencegah user enumeration attack, di mana penyerang mencoba menebak email valid dengan mengamati perbedaan pesan error.

Middleware AdminAuth berperan krusial dalam menjaga keamanan area administrator. Selain memeriksa status autentikasi, middleware ini mengimplementasikan mekanisme auto-logout berbasis inaktivitas. Timestamp aktivitas terakhir disimpan di session dengan key 'last_activity_admin' dan diperbarui setiap kali administrator melakukan request. Jika selisih waktu antara request saat ini dengan aktivitas terakhir melebihi 30 menit, session akan dihancurkan dan administrator dipaksa login ulang. Implementasi ini menggunakan Carbon untuk manipulasi waktu dengan method diffInMinutes() yang akurat dan timezone-aware.

### 4.5.5 Implementasi Dashboard Mahasiswa

Dashboard mahasiswa merupakan halaman pertama yang dilihat setelah login, menampilkan ringkasan komprehensif tentang riwayat tes mental health yang pernah diikuti. Implementasi dashboard ini menangani beberapa tantangan teknis seperti query kompleks dengan multiple joins, visualisasi data dalam chart line, dan optimisasi performa untuk mahasiswa yang memiliki banyak riwayat tes.

Method index() pada DashboardController menjadi entry point untuk halaman ini. Method ini mengambil instance user yang sedang login melalui Auth::user(), kemudian menggunakan NIM dari user tersebut untuk query data spesifik mahasiswa. Salah satu keputusan desain penting adalah pemisahan antara data yang di-cache dan data yang selalu fresh. Data statistik dan riwayat lengkap untuk chart di-cache selama 5 menit, sementara data untuk tabel dengan pagination selalu di-query untuk menghormati navigasi halaman pengguna.

Query untuk riwayat tes menggunakan teknik LEFT JOIN yang cukup rumit untuk menggabungkan tiga tabel: hasil_kuesioners, data_diris, dan riwayat_keluhans. Kompleksitas muncul dari kebutuhan untuk menampilkan keluhan yang relevan dengan setiap tes, bukan sembarang keluhan. Sistem harus menemukan keluhan terakhir yang dibuat sebelum tes dilakukan, bukan keluhan terakhir secara absolut.

Implementasi ini menggunakan subquery dalam closure LEFT JOIN yang menggunakan whereColumn() untuk membandingkan timestamp. Kondisi pertama memastikan NIM sama antara hasil tes dan keluhan. Kondisi kedua memastikan keluhan dibuat sebelum atau bersamaan dengan tes. Kondisi ketiga menggunakan whereRaw() dengan subquery SQL untuk menemukan keluhan dengan timestamp terbesar yang memenuhi kedua kondisi sebelumnya. Pendekatan ini elegan namun perlu index yang tepat di basis data agar performa tetap optimal.

Data untuk chart line diproses dengan cara menarik semua riwayat tes yang diurutkan ascending berdasarkan created_at. Urutan ascending penting karena chart akan menampilkan perkembangan dari tes pertama ke tes terakhir secara kronologis. Collection Laravel digunakan untuk transform data: method map() digunakan untuk membuat label "Tes 1", "Tes 2", dst, sementara pluck('total_skor') mengekstrak hanya nilai skor untuk sumbu Y chart.

Penghitungan statistik dilakukan di tingkat collection daripada database karena data sudah ditarik untuk chart. Method count() memberikan jumlah tes yang pernah diikuti, method last() memberikan record tes terakhir untuk ekstraksi kategori terkini. Pendekatan ini lebih efisien daripada query terpisah karena memanfaatkan data yang sudah ada di memori.

Implementasi caching menggunakan Cache::remember() dengan key yang unik per mahasiswa "mh.user.{$user->nim}.test_history". Strategi per-user caching ini penting untuk aplikasi multi-tenant seperti ini. Setiap mahasiswa memiliki cache terpisah yang di-invalidate hanya ketika mahasiswa tersebut melakukan tes baru, tidak terpengaruh oleh aktivitas mahasiswa lain. TTL 5 menit dipilih sebagai keseimbangan antara freshness dan performance.

Data pagination untuk tabel riwayat menggunakan query yang identik dengan query untuk cache, namun tanpa wrapping Cache::remember(). Hal ini dilakukan karena pagination bersifat stateful - parameter page dalam URL menentukan record mana yang ditampilkan. Jika di-cache, navigasi halaman tidak akan berfungsi dengan benar. Laravel's built-in pagination menghandle kompleksitas seperti link halaman, total record, dan record per halaman secara otomatis dengan method paginate(10).

### 4.5.6 Implementasi Form Data Diri dan Riwayat Keluhan

Sebelum mahasiswa dapat mengisi kuesioner mental health, sistem mewajibkan pengisian data diri lengkap beserta riwayat keluhan saat ini. Persyaratan ini penting untuk keperluan analisis demografis dan konteks evaluasi hasil tes. Implementasi form ini menangani dua skenario: mahasiswa baru yang belum pernah mengisi data, dan mahasiswa lama yang sudah pernah mengisi namun ingin memperbarui.

Method create() pada DataDirisController bertanggung jawab untuk menampilkan form. Method ini melakukan query sederhana ke tabel data_diris berdasarkan NIM mahasiswa yang login. Jika ditemukan, object data_diris akan dikirim ke view untuk pre-populate form. Jika tidak ditemukan (mahasiswa baru), view akan menampilkan form kosong. Teknik ini memberikan user experience yang baik karena mahasiswa tidak perlu mengetik ulang data yang sudah pernah diisi.

Submission form ditangani oleh method store() yang menggunakan StoreDataDiriRequest sebagai parameter. Laravel akan secara otomatis melakukan validasi sebelum method store() dipanggil, mengikuti prinsip separation of concerns. Jika validasi gagal, pengguna dikembalikan ke form dengan pesan error yang spesifik tanpa method store() pernah dieksekusi.

StoreDataDiriRequest mendefinisikan 14 field yang harus divalidasi dengan berbagai rule. Field seperti nama, provinsi, alamat, fakultas, program_studi, asal_sekolah, status_tinggal, keluhan, dan lama_keluhan menggunakan rule 'required|string' dengan batas maksimal karakter. Field jenis_kelamin dibatasi hanya menerima nilai 'L' atau 'P' dengan rule 'in:L,P'. Field pernah_konsul dan pernah_tes juga dibatasi dengan rule 'in:Ya,Tidak'. Field usia menggunakan rule 'required|integer|min:1' untuk memastikan angka positif. Field email menggunakan rule 'required|email|max:255' untuk validasi format.

Setelah validasi berhasil, proses penyimpanan dibungkus dalam database transaction untuk menjaga atomicity. Transaction dimulai dengan DB::beginTransaction(). Operasi pertama adalah upsert data diri menggunakan DataDiris::updateOrCreate(). Parameter pertama method ini adalah kondisi pencarian (NIM), parameter kedua adalah data yang akan disimpan atau diperbarui. Jika record dengan NIM tersebut sudah ada, semua field akan diperbarui dengan data baru. Jika belum ada, record baru akan dibuat.

Operasi kedua adalah menyimpan riwayat keluhan menggunakan RiwayatKeluhans::create(). Berbeda dengan data diri yang di-upsert, keluhan selalu dibuat sebagai record baru. Strategi ini disengaja karena sistem dirancang untuk melacak perubahan keluhan dari waktu ke waktu. Setiap kali mahasiswa mengisi form sebelum tes baru, snapshot keluhan saat itu akan tersimpan, memungkinkan analisis tren keluhan di masa depan.

Jika kedua operasi berhasil, transaction di-commit dengan DB::commit(). Sistem kemudian melakukan cache invalidation untuk dua cache yang relevan: 'mh.admin.user_stats' dan 'mh.admin.fakultas_stats'. Invalidation ini penting karena data diri mahasiswa mempengaruhi statistik demografis di dashboard administrator. Data penting seperti NIM, nama, dan program_studi disimpan ke session untuk digunakan di halaman berikutnya, kemudian mahasiswa diarahkan ke halaman kuesioner.

Jika terjadi exception di tengah proses, catch block akan menangkap error, melakukan DB::rollBack() untuk membatalkan semua perubahan, dan mengembalikan mahasiswa ke form dengan pesan error yang mencakup detail exception. Method withInput() mempertahankan input mahasiswa sehingga mereka tidak perlu mengisi ulang seluruh form, hanya memperbaiki bagian yang bermasalah.

### 4.5.7 Implementasi Kuesioner MHI-38

Kuesioner Mental Health Inventory-38 merupakan jantung dari sistem assessment ini. Implementasi kuesioner melibatkan validasi ketat untuk 38 pertanyaan, kalkulasi skor dengan mempertimbangkan reverse scoring untuk pertanyaan negatif, kategorisasi berdasarkan rentang skor, dan penyimpanan hasil beserta detail jawaban per soal.

Halaman kuesioner ditampilkan melalui route closure sederhana yang langsung merender view tanpa logika bisnis. Semua 38 pertanyaan di-hardcode di view karena pertanyaan MHI-38 bersifat standardized dan tidak berubah. Setiap pertanyaan menggunakan skala Likert 1-6 yang direpresentasikan dengan radio button untuk memaksa mahasiswa memilih satu jawaban untuk setiap pertanyaan.

Submission kuesioner ditangani oleh method store() pada HasilKuesionerController dengan StoreHasilKuesionerRequest sebagai validasi layer. Form request ini menggunakan pendekatan programatik untuk membuat rule validasi. Alih-alih mendefinisikan 38 rule secara manual, loop for digunakan untuk generate rule "question{$i}" dengan constraint 'required|integer|min:0|max:6' untuk setiap nomor soal.

Nilai minimum 0 dan maksimum 6 sengaja dibuat lebih lebar dari skala Likert normal (1-6) untuk mengakomodasi edge case dalam pengujian. Dalam produksi aktual, range bisa dipersempit. Custom error message juga di-generate dalam loop untuk memberikan feedback spesifik seperti "Pertanyaan nomor 5 wajib dijawab" jika mahasiswa melewatkan pertanyaan tertentu.

Setelah validasi lolos, method store() mengumpulkan semua jawaban dalam array dan menghitung total skor dengan loop sederhana. Total skor ini kemudian digunakan untuk kategorisasi menggunakan match expression PHP 8. Match expression lebih ketat dibanding switch-case karena menggunakan strict comparison dan harus exhaustive dengan default case. Kategorisasi mengikuti standar MHI-38 dengan lima kategori: Sangat Sehat (190-226), Sehat (152-189), Cukup Sehat (114-151), Perlu Dukungan (76-113), dan Perlu Dukungan Intensif (38-75).

Penyimpanan hasil dilakukan dalam database transaction dengan dua operasi utama. Pertama adalah penyimpanan header hasil ke tabel hasil_kuesioners dengan field nim, total_skor, dan kategori. Method create() mengembalikan instance model yang baru disimpan, termasuk ID auto-increment yang sangat penting untuk operasi kedua.

Operasi kedua adalah penyimpanan 38 record detail jawaban ke tabel mental_health_jawaban_details. Implementasi menggunakan teknik bulk insert untuk performa optimal. Loop for mempersiapkan array berisi 38 element, di mana setiap element adalah associative array dengan key hasil_kuesioner_id, nomor_soal, skor, created_at, dan updated_at. Setelah array lengkap, method insert() dipanggil sekali untuk menyimpan semua record dalam satu query. Bulk insert bisa puluhan kali lebih cepat dibanding 38 kali pemanggilan create() individual.

Setelah penyimpanan berhasil dan transaction di-commit, sistem melakukan cache invalidation agresif untuk lima cache berbeda: statistik user admin, hitungan kategori, total tes, statistik fakultas, dan riwayat tes mahasiswa yang bersangkutan. Invalidation menyeluruh ini memastikan semua dashboard menampilkan data terkini setelah tes baru disubmit.

### 4.5.8 Implementasi Dashboard Administrator

Dashboard administrator merupakan modul paling kompleks dalam sistem dengan fitur multi-dimensi: pagination untuk menangani ribuan record, search multi-kolom dengan intelligently matching, filter berdasarkan kategori kesehatan mental, sorting dinamis untuk berbagai kolom, statistik global agregat, dan visualisasi chart. Semua fitur ini harus bekerja secara harmonis sambil mempertahankan performa yang responsif.

Method index() pada HasilKuesionerCombinedController menjadi orchestrator untuk semua fitur ini. Method menerima Request object yang berisi parameter dari URL query string seperti limit, search, sort, order, kategori. Nilai default disediakan untuk setiap parameter menggunakan method input() dengan parameter kedua sebagai fallback.

Query utama dibangun dengan teknik bertingkat untuk optimisasi maksimal. Tingkat pertama adalah subquery yang menghitung ID maksimal (terbaru) untuk setiap NIM dengan groupBy. Subquery ini penting karena seorang mahasiswa bisa mengikuti tes berkali-kali, namun dashboard hanya perlu menampilkan hasil terbaru. Penggunaan MAX(id) dengan asumsi ID yang lebih besar adalah tes yang lebih baru.

Tingkat kedua adalah join subquery tersebut dengan tabel hasil_kuesioners menggunakan method joinSub(). Laravel mengeksekusi subquery terlebih dahulu dan menggunakan hasilnya sebagai tabel sementara untuk join. Pendekatan ini lebih efisien dibanding correlated subquery yang dieksekusi untuk setiap row.

Tingkat ketiga adalah join dengan tabel data_diris untuk mendapatkan informasi demografis mahasiswa. Join ini menggunakan kolom nim yang harus memiliki index untuk performa optimal. Tanpa index, join akan melakukan full table scan yang sangat lambat untuk tabel besar.

Tingkat keempat adalah LEFT JOIN dengan alias untuk menghitung jumlah tes per mahasiswa. Teknik ini menggunakan self-join dengan tabel hasil_kuesioners dengan alias hk_count, kemudian COUNT dengan GROUP BY untuk aggregate. Implementasi ini menghindari N+1 problem yang akan terjadi jika menggunakan accessor atau query terpisah di loop.

Fitur search mengimplementasikan logika yang cukup sophisticated. Input search dipecah menjadi terms menggunakan preg_split() dengan regex '\s+' untuk memisahkan berdasarkan whitespace. Array filter digunakan untuk menghapus string kosong. Setiap term kemudian di-search di multiple kolom dengan logik AND - semua term harus cocok untuk record dianggap match.

Implementasi search membedakan antara kolom regular dan kolom special. Kolom regular seperti nim, nama, email, alamat menggunakan LIKE search yang case-insensitive. Kolom special seperti fakultas, provinsi, program_studi, jenis_kelamin memiliki aturan khusus. Untuk kolom ini, sistem pertama mengecek apakah term masuk daftar nilai valid (misalnya 'fti' untuk fakultas). Jika ya, term ditransformasi sesuai aturan (uppercase untuk fakultas menjadi 'FTI') dan exact match dilakukan. Jika tidak, fallback ke LIKE search. Logika ini membuat search intuitif - admin bisa search "fti" dan sistem cukup pintar untuk match dengan "FTI" di database.

Fitur filter kategori lebih straightforward menggunakan method when() yang kondisional menambahkan WHERE clause hanya jika parameter kategori ada di request. Filter ini di-apply di kolom kategori tabel hasil_kuesioners.

Fitur sorting menggunakan match expression untuk mapping parameter sort dari request ke nama kolom lengkap termasuk nama tabel. Mapping ini penting untuk menghindari ambiguitas karena query melibatkan multiple tabel. Order direction (asc/desc) divalidasi untuk mencegah SQL injection, meskipun query builder Laravel sudah menghandle ini.

Pagination menggunakan method paginate() dengan parameter limit yang didapat dari request. Method withQueryString() mempertahankan semua parameter URL saat navigasi halaman, sehingga filter dan search tidak hilang ketika pindah halaman.

Statistik global menggunakan strategi caching agresif dengan TTL 1 menit. Rentang waktu pendek ini dipilih karena data bisa sering berubah (mahasiswa baru submit tes), namun cukup untuk mengurangi load database secara signifikan jika banyak admin mengakses dashboard bersamaan. Query statistik menggunakan teknik aggregation di database dengan COUNT DISTINCT dan CASE WHEN untuk menghitung berbagai metrik dalam satu query pass, jauh lebih efisien dibanding multiple query terpisah.

### 4.5.9 Implementasi Detail Jawaban Per Soal

Halaman detail jawaban memberikan visibility penuh tentang bagaimana seorang mahasiswa menjawab setiap pertanyaan dalam kuesioner. Fitur ini penting untuk administrator yang ingin melakukan evaluasi mendalam atau konseling berbasis hasil tes.

Method showDetail() pada HasilKuesionerCombinedController menerima parameter ID hasil kuesioner dari route. Method ini menggunakan eager loading untuk menghindari N+1 problem dengan query terpisah untuk setiap relasi. Method with() menerima array relasi yang akan di-load: 'dataDiri' untuk informasi mahasiswa, 'jawabanDetails' untuk 38 jawaban dengan ordering berdasarkan nomor_soal, dan 'riwayatKeluhans' dengan constraint latest() dan limit(1) untuk mengambil hanya keluhan terbaru.

Eager loading dengan constraint seperti ini sangat powerful. Tanpa eager loading, mengakses $hasil->jawabanDetails di view akan trigger query terpisah. Dengan 100 hasil kuesioner di tabel, view akan eksekusi 100 query tambahan. Eager loading mengkonsolidasi ini menjadi 1-2 query saja menggunakan WHERE IN clause.

Array questions berisi mapping dari nomor soal ke teks pertanyaan lengkap. Array ini di-hardcode karena pertanyaan MHI-38 adalah konstan. Array negativeQuestions berisi nomor soal yang termasuk kategori Psychological Distress, yang perlu reverse scoring dalam perhitungan subscale (meskipun tidak digunakan dalam implementasi current yang hanya menghitung total score).

View menerima semua data ini dan merender tabel dengan kolom nomor, pertanyaan, jawaban, dan indikator positif/negatif. Administrator bisa melihat pola jawaban dan mengidentifikasi area concern secara visual.

### 4.5.10 Implementasi Penghapusan Data

Fitur penghapusan data mahasiswa merupakan operasi destruktif yang memerlukan kehati-hatian extra. Method destroy() mengimplementasikan cascading delete yang menghapus tidak hanya satu hasil tes, tetapi seluruh jejak mahasiswa dari sistem.

Method menerima parameter ID dari route, namun scope penghapusan lebih luas dari satu record. Pertama, hasil kuesioner dengan ID tersebut dicari untuk mendapatkan NIM mahasiswa. Jika tidak ditemukan, request dibatalkan dengan pesan error. Jika ditemukan, NIM digunakan sebagai key untuk mencari semua data terkait mahasiswa tersebut.

Proses penghapusan dibungkus dalam transaction untuk atomicity. Urutan penghapusan mengikuti dependency chain dari child ke parent untuk menghindari foreign key constraint violation. Pertama, semua hasil kuesioner dengan NIM tersebut dihapus. Detail jawaban akan terhapus otomatis jika foreign key dikonfigurasi dengan ON DELETE CASCADE, atau bisa dihapus eksplisit jika tidak.

Kedua, semua riwayat keluhan dengan NIM tersebut dihapus. Ketiga, record di tabel data_diris dihapus jika ada. Keempat, record di tabel users dihapus jika ada. Pengecekan keberadaan record sebelum delete penting karena dalam skenario tertentu, admin mungkin menghapus data parsial sebelumnya.

Jika semua operasi berhasil, transaction di-commit dan cache invalidation dilakukan untuk semua cache yang mungkin mencakup data mahasiswa tersebut. Jika terjadi exception, catch block melakukan rollback sehingga data tidak terhapus sebagian. Admin mendapat feedback jelas apakah operasi berhasil atau gagal beserta detail error jika ada.

### 4.5.11 Implementasi Export Excel

Fitur export memungkinkan administrator mengunduh data hasil kuesioner dalam format Excel untuk analisis offline atau pelaporan. Implementasi menggunakan package Maatwebsite Excel yang merupakan wrapper Laravel-friendly untuk library PhpSpreadsheet.

Method exportExcel() pada controller menerima parameter filter dari request yang sama dengan dashboard (search, kategori, sort, order). Parameter ini diteruskan ke class HasilKuesionerExport sebagai constructor parameter. Pendekatan ini memastikan data yang di-export identik dengan data yang ditampilkan di dashboard, memberikan consistency pengalaman pengguna.

Nama file Excel di-generate secara dinamis dengan timestamp menggunakan Carbon helper now() dengan timezone Asia/Jakarta. Format nama file adalah 'hasil-kuesioner-YYYY-MM-DD_HH-mm.xlsx'. Timestamp dalam nama file memudahkan administrator membedakan export dari waktu berbeda.

Class HasilKuesionerExport mengimplementasikan interface FromQuery untuk efisiensi memori. Interface ini memungkinkan Maatwebsite Excel melakukan streaming export daripada load semua data ke memori sekaligus. Untuk dataset besar dengan ribuan record, streaming export bisa menghemat ratusan MB memori.

Method query() dalam export class membangun query yang identik dengan query dashboard, menerapkan filter dan sort yang sama. Maatwebsite Excel kemudian iterate hasil query dan menulis ke file Excel secara incremental. Method headings() mendefinisikan header kolom yang akan muncul di baris pertama Excel.

Method download() dari Excel facade mengembalikan BinaryFileResponse yang trigger download di browser. File Excel langsung di-stream ke browser tanpa disimpan di server, menghemat disk space dan meningkatkan privacy dengan tidak meninggalkan file sensitif di server.

### 4.5.12 Implementasi Relasi Database dengan Eloquent ORM

Model Eloquent menjadi jembatan antara tabel database dan kode PHP dengan menyediakan API berorientasi objek untuk query dan manipulasi data. Setiap tabel utama memiliki model yang bersesuaian dengan konvensi penamaan singular CamelCase untuk model dan plural snake_case untuk tabel.

Model HasilKuesioner mendefinisikan tiga relasi penting. Relasi belongsTo dengan DataDiris menggunakan nim sebagai foreign key di kedua tabel. Relasi ini memungkinkan akses ke data mahasiswa dengan $hasil->dataDiri. Relasi hasMany dengan RiwayatKeluhans juga menggunakan nim sebagai foreign key, memungkinkan akses semua keluhan mahasiswa tersebut. Relasi hasMany dengan MentalHealthJawabanDetail menggunakan hasil_kuesioner_id sebagai foreign key, memungkinkan akses 38 jawaban detail untuk hasil tersebut.

Model MentalHealthJawabanDetail mendefinisikan relasi inverse belongsTo dengan HasilKuesioner. Relasi inverse ini penting untuk navigasi bidirectional. Dari jawaban detail, kita bisa mengakses hasil kuesioner parent dengan $jawaban->hasilKuesioner.

Property fillable di setiap model mendefinisikan kolom yang boleh di-mass assign. Ini adalah security feature untuk mencegah mass assignment vulnerability di mana attacker memasukkan field tidak terduga dalam request. Hanya field yang eksplisit didefinisikan dalam fillable array yang bisa di-assign melalui create() atau update().

Penamaan method relasi mengikuti konvensi Laravel: singular untuk belongsTo (dataDiri, hasilKuesioner) dan plural untuk hasMany (riwayatKeluhans, jawabanDetails). Konvensi ini membuat kode lebih readable dan intuitif.

### 4.5.13 Optimisasi Performa Backend

Performa aplikasi web sangat bergantung pada efisiensi query database dan strategi caching yang tepat. Beberapa teknik optimisasi telah diimplementasikan secara konsisten di seluruh sistem.

Eager loading digunakan secara ekstensif untuk menghindari N+1 query problem. Setiap kali relasi perlu diakses, relasi tersebut di-load di awal menggunakan with(). Misalnya di dashboard admin, loading 100 hasil kuesioner dengan nama mahasiswa tanpa eager loading akan mengeksekusi 101 query (1 untuk hasil, 100 untuk nama). Dengan eager loading, hanya 2 query diperlukan.

Selective column loading menggunakan select() untuk mengurangi data transfer dari database. Hanya kolom yang benar-benar digunakan di view yang di-fetch. Untuk tabel dengan puluhan kolom atau kolom tipe TEXT yang besar, selective loading bisa mengurangi data transfer secara signifikan.

Bulk operations digunakan untuk insert multiple record. Method insert() dengan array of arrays bisa puluhan kali lebih cepat dibanding loop dengan create() karena mengurangi round-trip ke database dari N kali menjadi 1 kali.

Database indexing untuk foreign key column seperti nim dan hasil_kuesioner_id sangat kritikal. Index membuat lookup dalam join operation dari O(n) menjadi O(log n). Tanpa index, join pada tabel dengan jutaan record bisa timeout.

Caching strategy mengikuti prinsip cache data yang expensive untuk compute namun tidak sering berubah. Statistik global di dashboard admin di-cache 1 menit karena query-nya kompleks namun data bisa berubah setiap saat. Riwayat tes mahasiswa di-cache 5 menit karena hanya berubah ketika mahasiswa tersebut submit tes baru.

Cache invalidation dilakukan secara proaktif setiap kali data berubah. Method Cache::forget() dipanggil setelah operasi create, update, atau delete untuk memastikan cache tidak stale. Strategi invalidation ini lebih reliable dibanding cache expiration saja.

Per-user caching untuk data user-specific menggunakan key yang include user identifier. Cache key seperti "mh.user.{$nim}.test_history" memastikan setiap user memiliki cache terpisah. Ini penting untuk aplikasi multi-tenant untuk mencegah kebocoran data antar user.

### 4.5.14 Implementasi Keamanan Backend

Keamanan aplikasi dibangun berlapis dengan multiple defense mechanism untuk melindungi dari berbagai jenis serangan.

Authentication menggunakan dua mekanisme berbeda untuk user dan admin. OAuth 2.0 untuk mahasiswa mendelegasikan autentikasi ke Google yang memiliki infrastruktur keamanan enterprise-grade. Password tidak pernah disimpan di sistem kita, mengurangi risk jika database compromise. Untuk admin, password di-hash dengan bcrypt yang merupakan adaptive hash function. Cost factor secara otomatis disesuaikan untuk menjaga hashing tetap slow meskipun hardware semakin cepat.

Session management mengikuti best practice dengan regenerasi session ID setelah login untuk mencegah session fixation. Session invalidation total saat logout memastikan session tidak bisa digunakan kembali. Auto-logout untuk admin setelah inaktivitas mencegah unauthorized access jika admin lupa logout di komputer public.

Input validation menggunakan Form Request yang memisahkan concern validasi dari controller logic. Semua input di-validate dengan rule yang strict, reject input yang tidak sesuai spesifikasi. Rule seperti 'in:L,P' untuk jenis kelamin mencegah injection nilai yang tidak terduga. Rule 'integer|min|max' untuk jawaban kuesioner memastikan hanya angka dalam range yang valid.

SQL injection prevention otomatis ditangani oleh Query Builder dan Eloquent yang selalu menggunakan prepared statement dengan parameter binding. Developer tidak pernah concatenate user input langsung ke SQL string, mengeliminasi SQL injection risk.

XSS prevention dilakukan oleh Blade templating engine yang secara default escape semua output. Expression {{ $variable }} otomatis di-escape, sementara {!! $variable !!} untuk raw output hanya digunakan untuk konten yang sudah di-sanitize.

CSRF protection otomatis diterapkan untuk semua non-GET request. Setiap form harus include @csrf directive yang generate hidden token. Laravel memverifikasi token di setiap POST/PUT/DELETE request, reject request dengan token missing atau invalid.

Mass assignment protection menggunakan fillable whitelist di model. Hanya kolom yang eksplisit didefinisikan dalam fillable array yang bisa di-mass assign. Ini mencegah attacker inject field tidak terduga dalam request untuk modify kolom sensitif.

### 4.5.15 Kesimpulan Implementasi Backend

Implementasi backend sistem Mental Health Assessment telah berhasil merealisasikan semua requirement fungsional dengan arsitektur yang maintainable dan performant. Pemilihan Laravel sebagai framework terbukti tepat dengan ekosistem yang kaya, abstraksi yang tepat guna, dan convention yang konsisten.

Delapan modul utama yang diimplementasikan - autentikasi, data diri, kuesioner, scoring, dashboard user, dashboard admin, detail jawaban, dan export - bekerja secara kohesif dengan relasi yang jelas antar komponen. Setiap modul mengikuti prinsip separation of concerns dengan controller yang slim, model yang fokus pada data dan relasi, serta form request yang handle validasi.

Optimisasi performa telah dipertimbangkan sejak awal dengan teknik seperti eager loading, selective column, bulk operations, indexing, dan caching. Strategi caching dengan invalidation proaktif memastikan balance antara performance dan data freshness.

Keamanan diimplementasikan berlapis dengan autentikasi yang robust, session management yang aman, input validation yang ketat, dan proteksi otomatis dari framework terhadap SQL injection, XSS, dan CSRF. Audit trail untuk sensitive operations bisa ditambahkan untuk compliance dengan regulasi privasi data.

Kualitas kode dijaga dengan adherence terhadap Laravel best practices, penggunaan type hinting, dokumentasi inline yang memadai, dan struktur yang konsisten. Codebase siap untuk scaling horizontal dengan load balancer dan shared cache seperti Redis.

Testing coverage yang comprehensive dengan 140 test case memberikan confidence bahwa sistem bekerja sesuai spesifikasi. Continuous Integration bisa di-setup untuk menjalankan test suite otomatis setiap kali ada code change.

Sistem telah siap untuk deployment production dengan beberapa adjustment seperti konfigurasi cache driver ke Redis, optimization MySQL query cache, setup queue worker untuk background job, dan monitoring dengan tools seperti Laravel Telescope atau New Relic.
