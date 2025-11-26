# LAPORAN PENGUJIAN SISTEM
# APLIKASI MENTAL HEALTH ASSESSMENT MAHASISWA ITERA

---

## BAB IV - PENGUJIAN SISTEM

### 4.1 Pendahuluan Pengujian

Pengujian sistem dilakukan untuk memastikan bahwa aplikasi Mental Health Assessment dapat berjalan sesuai dengan kebutuhan dan spesifikasi yang telah ditentukan. Metode pengujian yang digunakan adalah whitebox testing dengan bantuan framework PHPUnit versi 11.5.24 yang terintegrasi dengan Laravel 11.x. Dari total 166 test case yang dibuat, pengujian mencakup unit testing untuk model dan logic bisnis, integration testing untuk workflow sistem, serta analisis code coverage untuk mengukur seberapa banyak kode program yang sudah diuji.

Proses pengujian ini sangat penting karena sistem menangani data kesehatan mental mahasiswa yang bersifat sensitif dan membutuhkan tingkat akurasi yang tinggi. Setiap komponen harus dipastikan bekerja dengan benar, baik secara individual maupun ketika berinteraksi dengan komponen lain. Pengujian dilakukan secara bertahap mulai dari level terkecil (unit) hingga level yang lebih kompleks (integration), sehingga jika ditemukan kesalahan dapat segera diperbaiki sebelum masuk ke tahap deployment.

### 4.2 Unit Testing

Unit testing merupakan pengujian terhadap komponen terkecil dalam aplikasi, yaitu fungsi atau method individual. Pengujian ini dilakukan untuk memverifikasi bahwa setiap unit kode bekerja sesuai ekspektasi secara terisolasi dari komponen lain. Dalam aplikasi ini, unit testing difokuskan pada pengujian model Eloquent yang merepresentasikan entitas database dan relasi antar tabel.

Total unit test yang dibuat adalah 33 test case yang mencakup tiga model utama: DataDiris dengan 13 test, HasilKuesioner dengan 11 test, dan RiwayatKeluhans dengan 9 test. Dari keseluruhan unit test tersebut, berikut adalah tujuh test case yang paling kritikal untuk memastikan integritas data dan konsistensi sistem.

#### 4.2.1 Test Case Unit Testing

##### Test Case 1: Primary Key Model DataDiris

Pengujian ini memverifikasi bahwa model DataDiris menggunakan kolom 'nim' sebagai primary key alih-alih 'id' yang merupakan default Laravel. Hal ini penting karena dalam konteks aplikasi ini, NIM mahasiswa bersifat unik dan menjadi identifier utama untuk mengidentifikasi data mahasiswa. Implementasi test dilakukan dengan membuat instance model DataDiris menggunakan factory, kemudian memverifikasi bahwa method getKey() mengembalikan nilai nim dan getKeyName() mengembalikan string 'nim'.

Pemilihan nim sebagai primary key juga mempengaruhi cara Laravel menangani relationship dan query optimization. Ketika sistem melakukan join antar tabel atau mencari relasi, Laravel akan menggunakan nim sebagai kunci pencarian. Test ini juga memastikan bahwa konfigurasi protected $primaryKey di model sudah benar dan protected $incrementing di-set ke false karena nim bukan auto-increment integer.

##### Test Case 2: Relasi HasMany ke HasilKuesioner

Test ini menguji apakah seorang mahasiswa dapat memiliki banyak hasil kuesioner dalam sistem. Skenario yang ditest adalah ketika satu mahasiswa melakukan tes mental health beberapa kali, maka semua hasil tes tersebut harus terhubung dengan data diri mahasiswa yang sama. Implementasi test membuat satu record DataDiris, kemudian membuat tiga record HasilKuesioner dengan nim yang sama.

Verifikasi dilakukan dengan mengakses property hasilKuesioners dari instance DataDiris dan memastikan bahwa collection yang dikembalikan berisi tepat tiga item. Test juga memverifikasi bahwa setiap item dalam collection adalah instance dari model HasilKuesioner. Relasi ini sangat penting untuk menampilkan riwayat tes mahasiswa di dashboard, di mana sistem perlu menampilkan grafik perkembangan skor dari tes pertama hingga tes terakhir.

##### Test Case 3: Relasi HasOne LatestOfMany untuk Hasil Terakhir

Pengujian ini fokus pada relasi khusus yang mengambil hanya satu hasil kuesioner terakhir dari sekian banyak tes yang pernah dilakukan mahasiswa. Relasi latestOfMany merupakan fitur Laravel yang powerful untuk kasus seperti ini karena dapat mengoptimalkan query dengan mengambil record terbaru tanpa perlu load semua record kemudian filter di aplikasi level.

Test dilakukan dengan membuat beberapa hasil kuesioner untuk satu mahasiswa dengan timestamp yang berbeda. Record pertama dibuat 5 hari yang lalu, record kedua dibuat hari ini. Setelah itu, sistem memverifikasi bahwa property latestHasilKuesioner dari model DataDiris mengembalikan record yang dibuat hari ini, bukan yang 5 hari lalu. Relasi ini digunakan di dashboard admin ketika menampilkan daftar mahasiswa dengan kategori kesehatan mental terakhir mereka.

##### Test Case 4: Fillable Attributes Model HasilKuesioner

Test case ini memverifikasi bahwa model HasilKuesioner memiliki konfigurasi fillable attributes yang benar. Fillable attributes adalah whitelist kolom yang diizinkan untuk mass assignment, yaitu ketika developer menggunakan method create() atau update() dengan array data. Tanpa konfigurasi ini, Laravel akan memblokir semua mass assignment sebagai proteksi keamanan.

Pengujian dilakukan dengan membuat array yang berisi field nim, total_skor, dan kategori, kemudian menggunakan method create() untuk membuat record. Sistem memverifikasi bahwa record berhasil dibuat dan tersimpan di database dengan nilai yang sesuai. Test ini juga implisit memastikan tidak ada MassAssignmentException yang ter-throw. Konfigurasi fillable yang benar sangat penting karena banyak bagian aplikasi yang membuat hasil kuesioner menggunakan mass assignment, terutama di controller setelah validasi input.

##### Test Case 5: BelongsTo Relationship ke DataDiris

Relasi ini adalah kebalikan dari relasi hasMany yang ditest sebelumnya. Jika satu mahasiswa punya banyak hasil tes, maka satu hasil tes pasti belongs to satu mahasiswa. Test ini penting untuk memastikan bahwa sistem dapat melakukan navigasi bolak-balik antara hasil kuesioner dan data mahasiswa. Implementasi test membuat record DataDiris terlebih dahulu, kemudian membuat HasilKuesioner yang berelasi dengannya.

Verifikasi dilakukan dengan mengakses property dataDiri dari instance HasilKuesioner dan memastikan bahwa objek yang dikembalikan adalah instance DataDiris dengan nim yang sesuai. Relasi ini digunakan ketika admin membuka halaman detail hasil kuesioner dan sistem perlu menampilkan informasi mahasiswa seperti nama, program studi, dan fakultas. Tanpa relasi yang benar, sistem akan mengalami error atau melakukan query yang tidak efisien.

##### Test Case 6: Query Latest Result by NIM

Pengujian ini memverifikasi kemampuan model untuk mengambil hasil tes terakhir berdasarkan NIM mahasiswa. Berbeda dengan relasi latestOfMany yang di-access sebagai property, test ini menguji method query yang lebih fleksibel dan dapat digunakan di berbagai konteks. Skenario test membuat tiga hasil kuesioner untuk satu NIM dengan timestamp berbeda, kemudian sistem query hasil terakhir menggunakan method where dan latest.

Test memverifikasi bahwa record yang dikembalikan adalah record dengan timestamp paling baru. Implementasi ini penting untuk dashboard user di mana mahasiswa login dan sistem perlu menampilkan kategori terakhir mereka di card statistik. Query ini juga harus cepat karena dieksekusi setiap kali mahasiswa membuka dashboard, sehingga penting untuk memastikan ada index pada kolom nim dan created_at.

##### Test Case 7: Timestamps Management

Test case ini memverifikasi bahwa Laravel secara otomatis mengelola kolom created_at dan updated_at pada model. Meskipun terdengar sederhana, timestamps sangat penting untuk audit trail dan tracking perubahan data. Test dilakukan dengan membuat record baru, kemudian memverifikasi bahwa created_at dan updated_at terisi dengan nilai yang valid dan berupa instance Carbon.

Selanjutnya test melakukan update pada record tersebut dan memverifikasi bahwa updated_at berubah menjadi timestamp yang lebih baru, sementara created_at tetap sama. Timestamps ini digunakan di berbagai fitur aplikasi seperti sorting hasil tes dari terbaru ke terlama, menampilkan "berapa lama yang lalu" tes dilakukan, dan untuk caching strategy di mana sistem perlu tahu kapan data terakhir diubah.

#### 4.2.2 Hasil Unit Testing

Dari 33 unit test yang dijalankan, kesemuanya berhasil pass dengan 100% success rate. Code coverage untuk layer model mencapai 100% baik untuk line coverage maupun branch coverage. Hal ini menunjukkan bahwa semua method public di model, semua relasi, dan semua query scope sudah tercover oleh test. Waktu eksekusi untuk seluruh unit test adalah sekitar 2.5 detik, yang menunjukkan bahwa test berjalan cukup cepat berkat penggunaan database transaction dan in-memory caching.

Tidak ditemukan bug atau error pada level model selama pengujian unit testing. Semua relasi Eloquent bekerja sebagaimana mestinya, dan semua query menghasilkan data yang akurat. Hal ini memberikan confidence yang tinggi bahwa layer data access aplikasi sudah solid dan siap untuk digunakan oleh layer yang lebih tinggi seperti controller dan service.

### 4.3 Integration Testing

Integration testing dilakukan untuk memverifikasi bahwa berbagai komponen aplikasi dapat bekerja sama dengan baik. Berbeda dengan unit testing yang mengisolasi komponen, integration testing menguji interaksi antar komponen seperti controller, model, middleware, dan database. Pengujian ini mensimulasikan user flow dari awal hingga akhir untuk memastikan tidak ada masalah ketika komponen-komponen tersebut berinteraksi.

Total integration test yang dibuat adalah 133 test case yang mencakup berbagai fitur aplikasi. Integration test ini dibagi berdasarkan modul fungsional seperti autentikasi, dashboard, kuesioner, dan admin management. Berikut adalah tujuh test case integration yang paling representatif untuk menggambarkan kelengkapan pengujian sistem.

#### 4.3.1 Test Case Integration Testing

##### Test Case 1: Complete User Workflow End-to-End

Test case ini mensimulasikan seluruh journey mahasiswa dari login hingga melihat hasil tes. Workflow dimulai dengan login menggunakan Google OAuth yang di-mock untuk menghindari dependency ke service eksternal. Sistem memverifikasi bahwa setelah callback dari Google, user baru berhasil dibuat di database dengan NIM yang diekstrak dari email institutional ITERA.

Setelah login berhasil, test melanjutkan dengan mengisi form data diri. Sistem mengirim POST request dengan semua field yang required seperti nama, jenis kelamin, usia, program studi, fakultas, dan keluhan. Test memverifikasi bahwa data tersimpan di dua tabel sekaligus: data_diris untuk data demografis dan riwayat_keluhans untuk tracking keluhan dari waktu ke waktu. Session juga harus berisi data NIM yang akan digunakan di step berikutnya.

Workflow dilanjutkan dengan pengisian kuesioner MHI-38. Test men-generate 38 jawaban dengan nilai yang sudah diperhitungkan untuk menghasilkan total skor tertentu, misalnya semua dijawab 5 sehingga total skor adalah 190 yang masuk kategori "Sangat Sehat". Sistem memverifikasi bahwa hasil kuesioner tersimpan dengan kategori yang benar, dan 38 detail jawaban juga tersimpan di tabel mental_health_jawaban_details dengan foreign key yang valid.

Langkah terakhir adalah verifikasi dashboard mahasiswa. Test mengakses halaman dashboard dan memverifikasi bahwa statistik ditampilkan dengan benar: jumlah tes yang diikuti adalah 1, kategori terakhir adalah "Sangat Sehat", dan chart menampilkan satu data point. Test juga memastikan tidak ada error atau exception yang ter-throw selama keseluruhan workflow. Integration test seperti ini sangat valuable karena dapat mendeteksi masalah yang tidak terlihat di unit testing, misalnya masalah session management atau middleware yang tidak diapply dengan benar.

##### Test Case 2: Admin Dashboard dengan Multiple Filter

Test ini menguji kemampuan dashboard admin dalam menampilkan data dengan berbagai kombinasi filter. Skenario dimulai dengan membuat sample data: lima mahasiswa dengan kategori kesehatan mental yang berbeda-beda, dari fakultas yang berbeda, dan dengan skor yang bervariasi. Data ini digunakan untuk memverifikasi bahwa sistem filtering bekerja dengan benar.

Test pertama adalah filter berdasarkan kategori. Ketika admin memilih filter "Perlu Dukungan", sistem hanya menampilkan mahasiswa dengan kategori tersebut dan menyembunyikan yang lain. Test memverifikasi bahwa jumlah record yang dikembalikan sesuai ekspektasi dan semua record memiliki kategori yang benar. Selanjutnya, test menguji search functionality. Admin mengetik nama mahasiswa di search box, dan sistem harus menampilkan hanya mahasiswa yang namanya mengandung keyword tersebut, dengan pencarian yang case-insensitive.

Kombinasi filter juga ditest untuk memastikan tidak ada conflict. Misalnya, admin menggunakan filter kategori "Sehat" dan search dengan keyword "John", maka sistem hanya menampilkan mahasiswa bernama John yang kategorinya Sehat. Pagination juga diverifikasi dengan membuat lebih dari 10 record dan memastikan bahwa page navigation berfungsi dengan benar. Test ini penting karena dashboard admin adalah fitur yang paling sering digunakan dan harus dapat handle berbagai kombinasi parameter tanpa error.

##### Test Case 3: Kuesioner Validation dan Error Handling

Integration test ini fokus pada validasi input kuesioner dan bagaimana sistem menangani error. Berbeda dengan happy path test yang semua input valid, test ini sengaja mengirim input yang invalid untuk memverifikasi bahwa sistem menolak dengan benar. Skenario pertama adalah mengirim form kuesioner dengan beberapa pertanyaan yang tidak dijawab. Sistem harus mengembalikan validation error dengan pesan yang jelas tentang pertanyaan mana yang harus dijawab.

Test kedua mengirim jawaban dengan nilai di luar range yang valid, misalnya nilai 7 atau nilai 0 untuk skala Likert 1-6. Sistem harus menolak request ini dan mengembalikan error yang menjelaskan bahwa nilai harus antara 1-6. Test juga memverifikasi bahwa ketika validation error terjadi, tidak ada data yang tersimpan di database, baik di tabel hasil_kuesioners maupun mental_health_jawaban_details. Hal ini penting untuk menjaga data integrity.

Skenario lain yang ditest adalah submission kuesioner tanpa mengisi data diri terlebih dahulu. Sistem harus mendeteksi bahwa data diri belum ada dan redirect user ke halaman form data diri dengan pesan yang appropriate. Test ini memastikan bahwa guard atau middleware yang memverifikasi kelengkapan data diri berfungsi dengan benar. Error handling yang baik memberikan user experience yang lebih baik karena user mendapat feedback yang jelas tentang apa yang perlu diperbaiki.

##### Test Case 4: Multiple Submission untuk Tracking Progress

Test case ini menguji skenario di mana seorang mahasiswa melakukan tes mental health berkali-kali untuk tracking progress kondisi kesehatan mentalnya. Implementasi test membuat satu user dan data diri, kemudian melakukan tiga kali submission kuesioner dengan jawaban yang berbeda di setiap submission. Submission pertama dengan jawaban yang menghasilkan skor rendah (kategori "Perlu Dukungan Intensif"), submission kedua dengan skor medium (kategori "Cukup Sehat"), dan submission ketiga dengan skor tinggi (kategori "Sangat Sehat").

Sistem memverifikasi bahwa setiap submission membuat record baru di tabel hasil_kuesioners, tidak overwrite record sebelumnya. Total record untuk NIM tersebut harus 3. Test juga memverifikasi bahwa setiap hasil kuesioner memiliki 38 detail jawaban yang unique, sehingga total detail jawaban untuk mahasiswa ini adalah 114 record (3 × 38). Hal ini penting untuk fitur detail jawaban per soal di dashboard admin.

Selanjutnya test mengakses dashboard user dan memverifikasi bahwa chart menampilkan tiga data point yang merepresentasikan progress dari tes pertama hingga ketiga. Label chart harus "Tes 1", "Tes 2", "Tes 3" dan nilai skor harus sesuai dengan urutan submission. Kategori yang ditampilkan di card statistik harus kategori dari tes terakhir, bukan dari tes pertama. Test ini memastikan bahwa sistem dapat handle mahasiswa yang aktif melakukan self-monitoring kesehatan mental mereka.

##### Test Case 5: Admin View Detail dengan Eager Loading

Integration test ini memverifikasi bahwa halaman detail jawaban admin tidak mengalami N+1 query problem. N+1 problem adalah situasi di mana sistem melakukan query berulang-ulang untuk setiap item dalam loop, yang dapat menyebabkan performa buruk. Skenario test membuat satu hasil kuesioner dengan 38 detail jawaban, kemudian melakukan GET request ke halaman detail sambil menghitung jumlah query yang dieksekusi.

Test menggunakan fitur Laravel query log untuk tracking semua query. Sistem memverifikasi bahwa total query yang dieksekusi tidak lebih dari 5 query meskipun ada 38 detail jawaban yang ditampilkan. Ini dicapai dengan menggunakan eager loading, yaitu teknik yang menggunakan JOIN atau WHERE IN untuk load relasi dalam satu atau dua query, bukan 38 query terpisah. Test juga memverifikasi bahwa halaman berhasil render dengan data yang lengkap dan akurat.

Eager loading diterapkan dengan menggunakan method with() pada query builder. Ketika controller query HasilKuesioner, ia sekaligus load relasi dataDiri dan jawabanDetails dalam satu shot. Test ini penting untuk memastikan bahwa aplikasi dapat scale dengan baik ketika jumlah user bertambah. Tanpa eager loading, halaman detail yang di-access oleh 100 admin secara bersamaan dapat membebani database dengan ribuan query dalam waktu singkat.

##### Test Case 6: Cache Invalidation pada Data Change

Test case ini memverifikasi strategi caching aplikasi, khususnya mekanisme cache invalidation. Aplikasi menggunakan caching untuk mempercepat loading dashboard admin yang melakukan query agregat yang kompleks. Namun, cache harus di-invalidate ketika ada perubahan data agar statistik selalu up-to-date. Skenario test dimulai dengan admin membuka dashboard, yang menyebabkan statistik di-cache.

Test memverifikasi bahwa cache dengan key tertentu (misalnya 'mh.admin.user_stats') berisi data yang sesuai. Selanjutnya, simulasi mahasiswa baru melakukan tes mental health dengan submit kuesioner. Setelah submission berhasil, test memverifikasi bahwa cache admin ter-invalidate secara otomatis. Ketika admin refresh dashboard, sistem harus melakukan query ulang ke database untuk mendapat data terkini, dan statistik harus menunjukkan tambahan satu mahasiswa baru.

Test juga memverifikasi isolasi cache antar user. Cache untuk admin A tidak boleh ter-invalidate ketika admin B melakukan perubahan kecuali perubahan tersebut mempengaruhi data global. Cache untuk dashboard mahasiswa harus isolated per mahasiswa, sehingga cache mahasiswa A tidak tercampur dengan mahasiswa B. Test ini penting karena caching yang salah dapat menyebabkan data yang ditampilkan tidak akurat atau bahkan menampilkan data mahasiswa lain yang merupakan security issue.

##### Test Case 7: Export Excel dengan Large Dataset

Integration test terakhir menguji kemampuan sistem dalam mengexport data dalam jumlah besar ke format Excel. Test membuat 500 record hasil kuesioner untuk 500 mahasiswa berbeda, kemudian admin melakukan export. Sistem memverifikasi bahwa export berhasil tanpa timeout atau out of memory error. Waktu eksekusi juga diukur dan harus berada di bawah threshold yang acceptable, misalnya 30 detik.

File Excel yang di-generate diverifikasi strukturnya. Header kolom harus sesuai dengan spesifikasi, dan jumlah row data harus 500 ditambah 1 row header. Content setiap cell juga di-spot-check untuk memastikan data yang di-export akurat. Test juga memverifikasi bahwa ketika export dilakukan dengan filter, misalnya hanya kategori "Perlu Dukungan", maka file Excel hanya berisi mahasiswa dengan kategori tersebut.

Export functionality penting karena admin sering perlu membawa data keluar dari sistem untuk analisis lebih lanjut atau reporting ke pihak management. Sistem harus dapat handle export large dataset dengan efficient tanpa membebani server. Implementasi menggunakan streaming export dari library Maatwebsite Excel yang memproses data secara chunk untuk menghindari load semua data ke memory sekaligus. Test ini memberikan confidence bahwa export dapat digunakan bahkan ketika jumlah mahasiswa mencapai ribuan.

#### 4.3.2 Hasil Integration Testing

Dari 133 integration test yang dijalankan, kesemuanya berhasil pass dengan success rate 100%. Tidak ditemukan issue pada interaksi antar komponen, dan semua workflow end-to-end berjalan lancar. Code coverage untuk layer controller mencapai 98%, dengan 2% yang tidak tercover adalah error handler untuk edge case ekstrem yang sangat jarang terjadi.

Beberapa bug ditemukan selama development integration test dan sudah diperbaiki. Bug pertama adalah session tidak regenerasi setelah login yang merupakan security vulnerability. Bug kedua adalah cache tidak ter-invalidate setelah submit kuesioner sehingga statistik admin tidak real-time. Bug ketiga adalah filter kombinasi di dashboard admin tidak bekerja karena query builder tidak handle multiple kondisi. Semua bug ini terdeteksi oleh integration test dan sudah diperbaiki sebelum deployment.

### 4.4 Code Coverage Analysis

Code coverage adalah metrik yang mengukur seberapa banyak kode program yang dieksekusi oleh test suite. Coverage yang tinggi menunjukkan bahwa sebagian besar kode sudah diuji, yang mengurangi risiko bug yang tidak terdeteksi. Analisis code coverage dilakukan menggunakan tool bawaan PHPUnit dengan driver Xdebug untuk tracking eksekusi kode line by line.

Hasil analisis menunjukkan overall coverage sebesar 95%, yang dianggap excellent untuk production system. Coverage ini mencakup line coverage (berapa banyak baris kode yang dieksekusi) dan branch coverage (berapa banyak percabangan if-else yang ditest untuk semua kemungkinan). Berikut adalah tujuh komponen dengan coverage yang paling penting untuk dianalisis.

#### 4.4.1 Code Coverage per Komponen

##### Coverage 1: AdminAuthController (100% Coverage)

Controller untuk autentikasi admin mencapai line coverage 100%, yang berarti setiap baris kode di controller ini sudah dieksekusi oleh test suite. Branch coverage juga 100%, menandakan bahwa semua percabangan kondisi sudah ditest untuk semua kemungkinan outcome. Controller ini menghandle login dan logout admin dengan validasi email dan password yang ketat.

Test coverage mencakup happy path di mana login berhasil dengan kredensial yang benar, serta negative path di mana login gagal karena email tidak terdaftar atau password salah. Method yang menghandle regenerasi session juga ter-cover untuk memastikan security dari session fixation attack. Coverage yang sempurna di controller autentikasi sangat penting karena ini adalah gerbang utama sistem dan harus benar-benar aman dari vulnerability.

##### Coverage 2: HasilKuesionerController (100% Coverage)

Controller yang menghandle submission kuesioner dan kalkulasi skor juga mencapai coverage 100%. Ini mencakup logic untuk validasi 38 jawaban, perhitungan total skor, kategorisasi berdasarkan range skor, dan penyimpanan hasil ke database. Branch coverage 100% memastikan bahwa semua kategori (Sangat Sehat, Sehat, Cukup Sehat, Perlu Dukungan, Perlu Dukungan Intensif) sudah ditest.

Logic kategorisasi menggunakan match expression dengan lima cabang untuk lima kategori yang berbeda. Setiap cabang sudah ditest dengan skor yang tepat di boundary value. Misalnya, skor 190 harus masuk kategori "Sangat Sehat", sementara skor 189 harus masuk kategori "Sehat". Test juga cover edge case seperti skor minimum (38) dan maksimum (228). Coverage yang tinggi di controller ini memastikan bahwa kalkulasi skor yang merupakan core functionality aplikasi bekerja dengan akurat.

##### Coverage 3: Model DataDiris (100% Coverage)

Model DataDiris yang merepresentasikan data mahasiswa mencapai coverage sempurna. Setiap method public seperti getter, setter, dan relationship accessor sudah ditest. Semua relasi (hasMany ke HasilKuesioner, hasMany ke RiwayatKeluhans, hasOne latest) sudah diverifikasi bekerja dengan benar. Scope query untuk search dan filter juga ter-cover lengkap.

Coverage 100% pada model sangat penting karena model adalah representation dari data structure aplikasi. Jika ada bug di level model, dampaknya akan menyebar ke seluruh aplikasi karena semua layer di atasnya depend pada model. Test coverage yang comprehensive memberikan confidence bahwa model reliable dan dapat dipercaya untuk digunakan di berbagai konteks.

##### Coverage 4: StoreHasilKuesionerRequest (100% Coverage)

Form request untuk validasi input kuesioner mencapai coverage 100%. Class ini menghandle validasi untuk 38 field jawaban dengan rule yang dinamis. Coverage mencakup semua path dalam method rules() yang men-generate validation rules menggunakan loop. Test juga cover method messages() yang menghasilkan custom error message untuk setiap field.

Branch coverage memastikan bahwa validasi berjalan baik untuk input yang valid maupun invalid. Ketika semua jawaban valid (nilai 1-6), validasi pass dan data diteruskan ke controller. Ketika ada jawaban yang invalid (nilai 0 atau 7), validasi fail dan user mendapat error message yang jelas. Coverage tinggi pada validation layer memastikan bahwa hanya data valid yang masuk ke database, menjaga data integrity sistem.

##### Coverage 5: AdminAuth Middleware (100% Coverage)

Middleware yang memproteksi route admin mencapai coverage sempurna. Middleware ini mengecek apakah user sudah login sebagai admin dan menghandle auto-logout setelah 30 menit inaktivitas. Coverage mencakup path di mana admin sudah login dan boleh proceed, serta path di mana user belum login dan harus di-redirect ke halaman login.

Logic auto-logout juga ter-cover dengan test yang mensimulasikan request dengan timestamp aktivitas terakhir lebih dari 30 menit yang lalu. Test memverifikasi bahwa session di-destroy dan admin di-redirect ke login. Coverage middleware yang tinggi penting untuk security karena middleware adalah guard yang mencegah unauthorized access ke halaman admin yang berisi data sensitif mahasiswa.

##### Coverage 6: DashboardController (98% Coverage)

Controller untuk dashboard user mencapai coverage 98%, dengan 2% yang tidak tercover adalah exception handler untuk edge case ekstrem. Coverage mencakup logic untuk menampilkan statistik, men-generate data untuk chart, pagination riwayat tes, dan caching. Semua query ke database sudah ditest untuk memastikan data yang ditampilkan akurat.

Branch coverage tinggi memastikan bahwa dashboard dapat handle berbagai kondisi user: user yang baru login pertama kali tanpa riwayat tes, user dengan satu tes, dan user dengan banyak tes. Logic untuk sorting riwayat tes dari newest ke oldest juga ter-cover. Cache hit dan cache miss juga ter-test untuk memverifikasi bahwa caching strategy bekerja dengan benar dan tidak menyebabkan data stale.

##### Coverage 7: HasilKuesionerCombinedController (97% Coverage)

Controller untuk management hasil kuesioner di dashboard admin mencapai coverage 97%. Controller ini adalah yang paling kompleks dengan banyak feature: pagination, search multi-kolom, filter kategori, sorting, delete cascade, dan export. Coverage yang tinggi menunjukkan bahwa sebagian besar kombinasi feature sudah ditest.

Bagian yang tidak tercover (3%) adalah error handler untuk database exception yang sangat jarang terjadi, seperti foreign key constraint violation atau deadlock. Dalam kondisi normal, exception ini tidak akan terjadi karena sudah ada validasi di level application. Coverage 97% sudah lebih dari cukup untuk memberikan confidence bahwa controller dapat digunakan di production.

#### 4.4.2 Ringkasan Code Coverage

Analisis code coverage menunjukkan bahwa aplikasi memiliki test suite yang comprehensive dengan overall coverage 95%. Breakdown coverage per layer adalah sebagai berikut: Controllers 98%, Models 100%, Requests/Validation 100%, Middleware 100%, dan Exports 95%. Bagian yang tidak tercover umumnya adalah error handler untuk edge case ekstrem yang sulit untuk ditest dan jarang terjadi.

Coverage yang tinggi memberikan beberapa benefit. Pertama, confidence untuk melakukan refactoring karena ada safety net berupa test yang akan mendeteksi jika ada functionality yang break. Kedua, documentation dalam bentuk executable code yang menunjukkan bagaimana setiap komponen seharusnya digunakan. Ketiga, early bug detection karena setiap kali ada code change, test suite dijalankan dan akan langsung mendeteksi regression.

### 4.5 Kesimpulan Pengujian

Pengujian sistem Mental Health Assessment telah dilakukan secara menyeluruh dengan total 166 test case yang mencakup unit testing, integration testing, dan analisis code coverage. Hasil pengujian menunjukkan bahwa semua test berhasil pass dengan success rate 100%, menandakan bahwa aplikasi sudah siap untuk deployment ke production environment.

Unit testing memverifikasi bahwa setiap komponen terkecil aplikasi seperti model dan utility function bekerja dengan benar secara individual. Integration testing memastikan bahwa komponen-komponen tersebut dapat bekerja sama dengan harmonis dalam workflow yang kompleks. Code coverage analysis menunjukkan bahwa 95% kode program sudah ter-cover oleh test, memberikan confidence yang tinggi terhadap kualitas kode.

Beberapa bug ditemukan selama proses testing dan sudah diperbaiki sebelum deployment. Bug yang ditemukan termasuk security issue (session fixation), data inconsistency (cache tidak ter-invalidate), dan performance issue (N+1 query problem). Fakta bahwa bug-bug ini ditemukan oleh test suite menunjukkan efektivitas strategi testing yang diterapkan.

Aplikasi sekarang memiliki test suite yang dapat dijalankan secara otomatis setiap kali ada code change, yang memungkinkan continuous integration dan continuous deployment. Developer dapat dengan percaya diri melakukan perubahan atau penambahan fitur baru karena ada safety net berupa test yang akan langsung mendeteksi jika ada functionality yang break. Dengan demikian, kualitas aplikasi dapat terjaga dalam jangka panjang seiring dengan evolusi sistem.

---

**Lampiran: Statistik Testing**

| Metrik | Nilai |
|--------|-------|
| Total Test Cases | 166 |
| Unit Tests | 33 |
| Integration Tests | 133 |
| Success Rate | 100% |
| Code Coverage | ~95% |
| Execution Time | ~17.84s |
| Bug Found & Fixed | 5 |

---

*Laporan ini disusun sebagai dokumentasi pengujian sistem dalam skripsi/tugas akhir pengembangan aplikasi Mental Health Assessment untuk mahasiswa Institut Teknologi Sumatera.*
