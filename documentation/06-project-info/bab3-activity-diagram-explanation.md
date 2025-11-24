# BAB 3 - PENJELASAN ACTIVITY DIAGRAM
# Platform Assessment Online ITERA

**Tanggal:** 21 November 2025
**Versi:** 1.0
**Sistem:** Mental Health Assessment - Institut Teknologi Sumatera

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Penjelasan Activity Diagram Autentikasi](#2-penjelasan-activity-diagram-autentikasi)
3. [Penjelasan Activity Diagram Fitur Pengguna](#3-penjelasan-activity-diagram-fitur-pengguna)
4. [Penjelasan Activity Diagram Fitur Administrator](#4-penjelasan-activity-diagram-fitur-administrator)
5. [Analisis Pola dan Karakteristik Activity Diagram](#5-analisis-pola-dan-karakteristik-activity-diagram)

---

## 1. Pendahuluan

### 1.1 Tujuan Dokumen

Dokumen ini menyediakan penjelasan naratif dan deskriptif mengenai activity diagram yang ada dalam sistem Assessment Online ITERA. Activity diagram merupakan representasi visual yang menggambarkan alur kerja (workflow) dari berbagai proses bisnis dan teknis dalam sistem. Berbeda dengan use case diagram yang fokus pada apa yang dapat dilakukan oleh aktor, activity diagram menjelaskan bagaimana proses-proses tersebut berjalan langkah demi langkah, termasuk decision point, parallel process, dan interaksi antar komponen sistem.

### 1.2 Struktur Activity Diagram

Activity diagram dalam sistem Assessment Online diorganisir berdasarkan domain fungsional sistem dan dikelompokkan menjadi tiga bagian utama:

1. **Activity Diagram Autentikasi** - Menjelaskan alur proses login dan logout untuk pengguna mahasiswa dan administrator, termasuk validasi session timeout
2. **Activity Diagram Fitur Pengguna** - Menjelaskan alur proses asesmen kesehatan mental dari perspektif mahasiswa, mulai dari mengisi data diri, mengerjakan kuesioner, hingga melihat hasil
3. **Activity Diagram Fitur Administrator** - Menjelaskan alur proses administratif seperti monitoring data, pencarian, penyaringan, ekspor data, dan pengelolaan data mahasiswa

Total terdapat 15 activity diagram yang masing-masing merepresentasikan satu proses spesifik dan fokus, memudahkan pemahaman dan penerapan.

### 1.3 Konvensi Swimlane

Activity diagram dalam dokumen ini menggunakan swimlane untuk menunjukkan aktor atau komponen sistem yang bertanggung jawab atas setiap aktivitas. Swimlane utama yang digunakan adalah:

- **Pengguna** atau **Mahasiswa** - Aksi yang dilakukan oleh mahasiswa
- **Administrator** - Aksi yang dilakukan oleh administrator sistem
- **Sistem** - Proses yang dilakukan oleh aplikasi backend
- **Browser** - Proses yang dilakukan di sisi client (frontend)
- **JavaScript** - Proses scripting di sisi client
- **Google** - Proses yang dilakukan oleh Google OAuth Server

Pemisahan swimlane ini memudahkan untuk memahami responsibility setiap komponen dan alur data antar komponen.

---

## 2. Penjelasan Activity Diagram Autentikasi

### 2.1 Activity Diagram: Login Pengguna dengan Google OAuth

Proses login mahasiswa menggunakan Google OAuth merupakan titik masuk utama bagi mahasiswa untuk mengakses sistem Assessment Online. Alur dimulai ketika mahasiswa membuka aplikasi Mental Health di peramban web mereka. Pada tahap awal, sistem akan memeriksa apakah mahasiswa sudah pernah login sebelumnya dengan mengecek keberadaan session token yang valid di browser.

Jika sistem menemukan bahwa mahasiswa masih memiliki session aktif yang valid, maka tidak perlu melakukan proses login ulang. Sistem akan langsung menampilkan halaman yang dituju, yang biasanya adalah dashboard mahasiswa. Mekanisme ini menerapkan konsep "Remember Me" atau persistent session yang memberikan pengguna experience yang seamless - mahasiswa tidak perlu login berulang kali setiap membuka aplikasi selama session mereka masih aktif.

Namun jika tidak ditemukan session aktif, mahasiswa akan melihat halaman utama dengan tombol "Masuk dengan Google". Ketika mahasiswa mengklik tombol ini, sistem backend akan membuat authorization request dan mengarahkan browser mahasiswa ke halaman login Google. Redirect ini membawa beberapa parameter penting seperti client_id yang mengidentifikasi aplikasi Assessment Online, redirect_uri yang menunjukkan kemana Google harus mengirim callback, scope yang menentukan data apa yang diminta (email dan profile), dan state parameter untuk keamanan CSRF.

Pada halaman login Google, mahasiswa akan melihat daftar akun Google yang tersimpan di browser mereka. Mahasiswa harus memilih akun Google ITERA mereka yang memiliki format email NIM@student.itera.ac.id. Setelah memilih akun, Google akan menampilkan consent screen yang menjelaskan data apa yang akan diakses oleh aplikasi Assessment Online (nama dan email). Mahasiswa harus mengklik "Izinkan Akses" untuk memberikan permission.

Setelah mahasiswa memberikan izin, Google OAuth Server akan mengirim callback ke sistem Assessment Online dengan membawa authorization code. Sistem kemudian menukar authorization code ini dengan access token melalui token exchange request yang dilakukan di backend. Access token inilah yang digunakan untuk mengambil informasi profil mahasiswa dari Google API.

Data yang diterima dari Google mencakup nama lengkap, email, dan Google ID. Sistem kemudian melakukan validasi kritis terhadap format email untuk memastikan bahwa email tersebut adalah email mahasiswa ITERA yang valid. Validasi ini menggunakan regular expression pattern yang memeriksa bahwa email memiliki format tepat 9 digit angka diikuti @student.itera.ac.id. Jika email tidak sesuai format, misalnya email personal seperti @gmail.com atau email dosen @itera.ac.id, sistem akan menolak login dan menampilkan pesan error "Gunakan email ITERA".

Ketika validasi email berhasil, sistem akan mengekstrak 9 digit pertama dari email sebagai NIM mahasiswa. NIM ini kemudian digunakan sebagai primary key untuk mengidentifikasi mahasiswa dalam database. Sistem akan mengecek apakah mahasiswa dengan NIM tersebut sudah pernah terdaftar sebelumnya di tabel users. Jika sudah terdaftar, sistem akan memperbarui informasi nama dan email dengan data terbaru dari Google untuk menjaga sinkronisasi. Jika belum terdaftar, sistem akan membuat record baru di tabel para pengguna dengan NIM sebagai primary key, nama dari Google, email, dan Google ID.

Bersamaan dengan pengelolaan pengguna account, sistem juga mengelola record di tabel data_diris. Tabel ini menyimpan data demografis mahasiswa yang lebih lengkap. Pada saat login pertama kali, sistem akan membuat record data_diris dengan informasi minimal (NIM, nama, email), dan mahasiswa akan diminta untuk melengkapi data ini di langkah berikutnya sebelum dapat mengerjakan kuesioner. Jika record data_diris sudah ada, sistem akan memperbarui nama dan email saja, tidak menghapus data demografis lain yang sudah pernah diisi mahasiswa.

Setelah pengguna account dan data_diris di-handle, sistem akan memasukkan mahasiswa ke dalam authenticated session menggunakan Laravel's Auth::login() method. Method ini akan menyimpan pengguna ID (dalam hal ini NIM) ke dalam session storage. Sistem juga men-set session lifetime menjadi 120 menit, yang berarti mahasiswa akan tetap login selama 120 menit sejak aktivitas terakhir mereka.

Sistem kemudian menampilkan flash message "Login berhasil!" menggunakan session flash yang akan tampil sekali di halaman berikutnya. Browser mahasiswa akan di-redirect ke dashboard mahasiswa (/user/mental-health) dimana mereka dapat melihat statistik tes mereka, mulai tes baru, atau melihat riwayat tes sebelumnya.

Seluruh proses login ini, dari klik tombol login hingga melihat dashboard, biasanya hanya memakan waktu beberapa detik jika mahasiswa sudah login ke Google. Jika mahasiswa belum login ke Google, mereka perlu memasukkan password Google mereka terlebih dahulu, yang menambah satu step tambahan di sisi Google.

### 2.2 Activity Diagram: Login Admin

Proses login administrator memiliki alur yang berbeda dan lebih tradisional dibandingkan login mahasiswa. Administrator tidak menggunakan OAuth, melainkan kombinasi email dan password yang di-manage langsung oleh sistem Assessment Online. Alur dimulai ketika administrator membuka halaman login yang biasanya diakses melalui URL /login.

Sistem pertama-tama akan memeriksa apakah administrator sudah memiliki session login yang aktif. Pengecekan ini dilakukan dengan mengecek guard 'admin' di Laravel authentication system. Jika administrator sudah login dan session masih valid, tidak perlu login ulang - sistem langsung redirect ke dashboard admin (/admin). Ini mencegah administrator yang sudah login harus melalui halaman login lagi secara tidak perlu.

Jika administrator belum login, sistem akan menampilkan formulir login yang sederhana dan straightforward. Formulir ini berisi dua field input: Email dan Kata Sandi, plus tombol "Masuk". Tidak ada opsi "Lupa Password" atau "Register" karena account administrator dibuat melalui seeder atau command artisan, bukan melalui self-registration.

Administrator mengisi alamat email mereka di field email. Email ini tidak harus mengikuti format tertentu, bisa email institusi atau email personal, tergantung apa yang di-set saat account dibuat. Kemudian administrator mengisi password mereka di field kata sandi. Field ini menggunakan input type="password" sehingga karakter yang diketik akan di-mask dengan bullet points untuk security.

Setelah mengisi kedua field, administrator mengklik tombol "Masuk". Browser akan mengirim POST request ke endpoint /login dengan data email dan password. Request ini juga membawa CSRF token untuk mencegah CSRF attack.

Di sisi server, sistem menerima data login dan melakukan validasi format terlebih dahulu. Sistem mengecek bahwa email field tidak kosong dan memiliki format email yang valid (mengandung @ dan domain). Sistem juga mengecek bahwa password field tidak kosong dan memiliki minimal character tertentu. Jika validasi format gagal, sistem akan menampilkan pesan error validasi yang spesifik menjelaskan field mana yang bermasalah.

Jika validasi format berhasil, sistem melanjutkan ke validasi kredensial. Sistem akan query database tabel admins untuk mencari record dengan email yang match. Jika ditemukan, sistem akan mem-verify password yang disubmit dengan password hash yang tersimpan di database menggunakan bcrypt verification. Laravel's Hash::check() method akan membandingkan plain password dengan hashed password secara secure.

Jika password tidak cocok atau email tidak ditemukan, sistem akan menampilkan generic error message "Email atau kata sandi salah". Generic message ini adalah security praktik terbaik untuk mencegah attacker mengetahui apakah email tertentu terdaftar di sistem atau tidak. Pesan error yang terlalu spesifik seperti "Email tidak ditemukan" atau "Password salah" dapat diexploit untuk enumerate valid usernames.

Ketika validasi kredensial berhasil, sistem akan memasukkan administrator ke dalam authenticated session menggunakan Auth::guard('admin')->login(). Penggunaan guard 'admin' memastikan bahwa session administrator terpisah dari session mahasiswa - administrator dan mahasiswa dapat login secara bersamaan di browser yang sama tanpa conflict.

Setelah login, sistem melakukan regenerasi session ID untuk mencegah session fixation attack. Session fixation adalah attack dimana attacker menanam session ID di victim's browser sebelum victim login, kemudian menggunakan session ID yang sama setelah victim login untuk hijack session mereka. Regenerasi session ID membuat attack ini tidak efektif.

Sistem juga menyimpan timestamp waktu login terakhir ke dalam session storage. Timestamp ini akan digunakan oleh middleware AdminAuth untuk tracking session timeout. Setiap kali administrator melakukan request, middleware akan membandingkan current time dengan last activity timestamp. Jika selisihnya lebih dari 30 menit, administrator akan di-logout otomatis.

Status login disimpan dengan session lifetime 120 menit, sama seperti mahasiswa. Namun berbeda dengan mahasiswa yang tidak memiliki timeout otomatis, administrator akan dikenakan auto-logout jika idle selama 30 menit. Ini adalah additional security layer untuk mencegah unauthorized access jika administrator meninggalkan komputer tanpa logout.

Sistem menampilkan flash message "Login berhasil!" dan redirect browser administrator ke dashboard admin (/admin). Di dashboard, administrator akan melihat statistik global, tabel data mahasiswa, dan berbagai tools untuk analisis dan management data.

### 2.3 Activity Diagram: Logout

Proses logout adalah proses yang universal untuk baik mahasiswa maupun administrator, sehingga activity diagram ini menggunakan aktor gabungan "Pengguna/Admin". Alur logout dimulai ketika pengguna yang sudah login ingin mengakhiri session mereka dan keluar dari aplikasi.

Ketika pengguna sedang berada di dalam aplikasi, mereka akan melihat menu profil atau account di pojok kanan atas halaman. Menu ini biasanya menampilkan nama pengguna dan foto profil (jika ada). Ketika pengguna mengklik menu profil ini, akan muncul dropdown menu yang berisi beberapa opsi, salah satunya adalah tombol "Keluar" atau "Logout".

Ketika pengguna mengklik tombol "Keluar", browser akan mengirimkan POST request ke endpoint /logout. Request ini harus menggunakan POST method (bukan GET) sebagai security praktik terbaik - logout operation yang mengubah state tidak boleh dilakukan dengan GET request karena dapat di-trigger oleh simple image tag atau link prefetch. Request POST logout juga membawa CSRF token untuk memverifikasi bahwa request berasal dari aplikasi yang legitimate, bukan dari malicious third-party site.

Di sisi server, sistem menerima permintaan logout dan melakukan validasi keamanan terlebih dahulu. Validasi ini mencakup pengecekan CSRF token - sistem akan membandingkan token yang dikirim dengan token yang tersimpan di session. Jika token tidak match, ini indikasi bahwa request mungkin berasal dari CSRF attack, dan sistem akan menolak request dengan error 419 "CSRF token mismatch".

Jika validasi CSRF berhasil, sistem melanjutkan proses logout dengan memeriksa tipe pengguna yang sedang logout. Sistem akan mengecek apakah pengguna yang logout adalah admin (guard 'admin') atau mahasiswa (guard 'web'). Pengecekan ini penting karena kedua tipe pengguna menggunakan guard yang berbeda dan perlu di-logout dari guard yang sesuai.

Jika pengguna adalah administrator, sistem akan call Auth::guard('admin')->logout() untuk mengeluarkan administrator dari admin guard. Jika pengguna adalah mahasiswa, sistem akan call Auth::logout() yang by default menggunakan web guard. Method logout ini akan menghapus authentication identifier dari session, secara efektif "melupakan" pengguna yang sedang login.

Setelah pengguna di-logout dari guard, sistem melakukan cleanup session storage. Pertama, sistem menghapus semua data user-specific dari session storage, termasuk cart, preferences, atau cached data apapun yang terkait dengan user. Kedua, sistem menghapus session record dari database tabel sessions. Laravel menyimpan session data di database (karena kita config SESSION_DRIVER=database), dan record ini perlu dihapus untuk free up storage dan untuk security.

Sistem kemudian melakukan regenerasi token CSRF dengan men-generate token baru dan mengganti token lama di session. Regenerasi token ini penting untuk mencegah session replay attack - jika attacker sempat mencuri CSRF token sebelum logout, token tersebut menjadi invalid setelah regenerasi dan tidak bisa digunakan untuk membuat request atas nama user.

Setelah semua cleanup selesai, sistem menampilkan flash message "Berhasil keluar" untuk memberikan umpan balik kepada pengguna bahwa logout berhasil. Browser kemudian di-redirect ke halaman login (/login). Redirect ini juga menghapus history stack, sehingga jika pengguna menekan tombol back di browser, mereka tidak akan kembali ke halaman authenticated melainkan akan di-redirect ulang ke login.

Dari perspektif user, mereka akan melihat halaman login dengan pesan berhasil keluar di bagian atas halaman. Jika mereka mencoba mengakses URL halaman authenticated seperti /user/mental-health atau /admin tanpa login, middleware authentication akan menangkap request dan redirect mereka kembali ke /login dengan pesan error "Unauthenticated". Ini membuktikan bahwa logout berhasil dan pengguna tidak lagi memiliki akses ke protected resources.

### 2.4 Activity Diagram: Validasi Session Timeout Admin

Mekanisme validasi session timeout adalah fitur keamanan khusus yang hanya berlaku untuk administrator. Mahasiswa tidak dikenakan timeout otomatis karena use case mereka adalah mengerjakan kuesioner yang mungkin memakan waktu lama dan akan sangat mengganggu jika tiba-tiba di-logout di tengah mengerjakan.

Proses monitoring timeout dimulai sejak administrator berhasil login. Pada saat login, sistem menyimpan current timestamp ke dalam session storage dengan key 'last_activity'. Timestamp ini merepresentasikan waktu aktivitas terakhir administrator. Setiap kali administrator melakukan aktivitas seperti membuka halaman, mengklik tombol, atau melakukan search, timestamp ini akan di-update ke current time.

Monitoring session timeout dipenerapankan di middleware AdminAuth yang dijalankan pada setiap HTTP request ke route yang ter-protect admin. Ketika administrator melakukan request apapun, middleware ini akan dieksekusi sebagai pre-request logic sebelum controller dijalankan.

Middleware pertama-tama mengecek status login administrator dengan memanggil Auth::guard('admin')->check(). Jika check ini return false, berarti administrator tidak login atau session sudah invalid. Dalam kasus ini, middleware akan langsung redirect administrator ke halaman login dengan pesan "Silakan login terlebih dahulu". Pengecekan ini mencegah timeout calculation dilakukan terhadap pengguna yang memang tidak login.

Jika administrator masih login, middleware mengambil timestamp last_activity dari session storage. Middleware kemudian menghitung selisih waktu antara current timestamp dengan last_activity timestamp. Selisih ini dikonversi ke dalam satuan menit untuk memudahkan comparison.

Middleware membandingkan selisih waktu dengan batas timeout yang di-set yaitu 30 menit. Jika selisih waktu lebih besar dari 30 menit, ini berarti administrator sudah tidak melakukan aktivitas apapun selama lebih dari 30 menit dan session dianggap idle. Dalam kondisi ini, middleware akan trigger proses auto-logout.

Proses auto-logout dilakukan dengan memanggil Auth::guard('admin')->logout() untuk mengeluarkan administrator dari system. Middleware juga menghapus session record dari database dan me-regenerate CSRF token, sama seperti proses logout normal. Perbedaannya adalah auto-logout di-trigger oleh sistem, bukan oleh pengguna action.

Setelah auto-logout, middleware menampilkan flash message "Waktu login habis. Silakan masuk kembali" untuk menginformasikan administrator mengapa mereka di-logout. Message ini penting untuk pengguna experience - administrator perlu tahu bahwa logout bukan karena error atau bug, melainkan karena timeout policy yang memang di-design untuk security. Browser administrator kemudian di-redirect ke halaman login dimana mereka dapat login ulang jika ingin melanjutkan aktivitas.

Jika selisih waktu masih di bawah atau sama dengan 30 menit, berarti administrator masih aktif dan tidak perlu di-logout. Dalam kasus ini, middleware akan memperbarui timestamp last_activity dengan current timestamp. Update ini penting untuk mereset penghitung timeout - setiap aktivitas administrator mereset timer kembali ke 0. Jadi selama administrator terus berinteraksi dengan sistem dengan interval kurang dari 30 menit, mereka tidak akan pernah kena timeout.

Setelah update last_activity, middleware mengizinkan request untuk melanjutkan ke controller dengan memanggil $next($request). Controller kemudian akan memproses request normal dan mengirimkan response. Administrator akan melihat halaman yang mereka minta tanpa menyadari bahwa timeout checking telah dilakukan di background.

Mekanisme timeout ini transparent dari perspektif administrator yang aktif - mereka tidak melihat delay atau disruption apapun. Hanya administrator yang idle lebih dari 30 menit yang akan merasakan impact berupa auto-logout. Implementasi ini adalah balance yang baik antara security dan kegunaan.

---

## 3. Penjelasan Activity Diagram Fitur Pengguna

### 3.1 Activity Diagram: Melihat Dashboard Pengguna

Dashboard pengguna adalah halaman central dimana mahasiswa mendapatkan overview komprehensif tentang status asesmen kesehatan mental mereka. Proses dimulai ketika mahasiswa mengklik menu "Dashboard" di navigation bar atau ketika mereka baru saja login dan di-redirect otomatis ke dashboard.

Sebelum menampilkan dashboard, sistem melakukan validasi autentikasi dengan mengecek apakah mahasiswa sudah login. Pengecekan ini dilakukan oleh middleware auth yang diattach ke route dashboard. Middleware mengecek keberadaan session dengan memanggil Auth::check(). Jika return false, berarti mahasiswa belum login, dan middleware akan redirect mereka ke halaman login dengan parameter intended URL yang menunjuk ke dashboard. Setelah login berhasil, sistem akan redirect mahasiswa kembali ke intended URL yaitu dashboard.

Jika mahasiswa sudah login, sistem melanjutkan ke proses pemuatan data dashboard. Untuk mengoptimalkan performance, sistem menerapkan caching mechanism dengan TTL 5 menit. Sistem pertama mengecek apakah data dashboard untuk mahasiswa tersebut ada di cache storage dengan cache key format "user_dashboard_{nim}".

Jika cache hit (data ditemukan di cache dan belum expired), sistem akan mengambil data langsung dari cache tanpa perlu query database. Ini significantly meningkatkan response time karena cache lookup jauh lebih cepat daripada database query, terutama query yang complex dengan multiple joins dan aggregations. Cache hit rate yang tinggi (ideally 70-90%) menunjukkan bahwa caching strategy efektif.

Jika cache miss (data tidak ditemukan atau sudah expired), sistem perlu mengambil data dari database. Query database mencakup beberapa data penting: total tes yang pernah diambil mahasiswa (count dari tabel hasil_kuesioners dengan filter nim = mahasiswa yang login), kategori kesehatan mental terbaru (ambil kategori dari record hasil_kuesioner terbaru berdasarkan created_at DESC), riwayat skor (ambil total_skor dan created_at dari semua hasil_kuesioner mahasiswa diurutkan chronologically), dan riwayat tes lengkap (ambil semua fields dari hasil_kuesioner untuk ditampilkan di tabel riwayat).

Setelah data diperoleh dari database, sistem menyimpan data tersebut ke cache storage dengan TTL 5 menit. Dengan begitu, request berikutnya dalam 5 menit ke depan dapat mengambil data dari cache. Setelah 5 menit, cache akan expired dan data perlu di-fetch ulang dari database untuk memastikan data tetap fresh.

Dengan data yang sudah diperoleh (dari cache atau database), sistem melakukan kalkulasi statistik untuk ditampilkan di kartu-kartu statistik. Kalkulasi mencakup: total tes diambil (simple count), tes selesai (count tes dengan status completed, meskipun dalam penerapan current semua tes yang tersimpan adalah completed), dan kategori terakhir (kategori dari tes most recent).

Sistem juga membuat data untuk grafik progres skor. Data grafik berupa array koordinat (x: tanggal, y: skor) yang akan di-render oleh charting library di frontend. Grafik ini menunjukkan trend skor kesehatan mental mahasiswa dari waktu ke waktu, memudahkan untuk melihat apakah ada peningkatan, penurunan, atau stability.

Setelah semua data siap, sistem me-render view dashboard dengan passing data statistik, data grafik, dan data tabel riwayat. View akan menampilkan layout dashboard yang terdiri dari: section header dengan greeting dan nama mahasiswa, row kartu statistik yang menampilkan total tes dan kategori terakhir dengan color-coded badge, section grafik progres skor dengan line chart interaktif, section tabel riwayat tes yang menampilkan tanggal, skor, dan kategori untuk setiap tes, dan prominent button "Mulai Tes Baru" yang meng-direct mahasiswa ke alur pengisian data diri dan kuesioner.

Dari dashboard, mahasiswa memiliki beberapa opsi interaksi. Jika mahasiswa ingin melihat detail hasil dari tes tertentu, mereka dapat mengklik tombol "Lihat Detail" pada row riwayat tes tersebut. Sistem akan menampilkan modal popup yang berisi penjelasan lengkap hasil tes tersebut, termasuk interpretasi kategori dan rekomendasi. Modal ini menggunakan AJAX untuk fetch detail data tanpa perlu reload halaman.

Jika mahasiswa ingin memulai tes baru, mereka mengklik tombol "Mulai Tes Baru". Sistem akan redirect mereka ke halaman pengisian data diri (/mental-health/isi-data-diri). Jika data diri mahasiswa sudah lengkap dari tes sebelumnya, form akan ter-fill otomatis dengan data existing dan mahasiswa hanya perlu verify atau update jika ada perubahan.

### 3.2 Activity Diagram: Mengisi Data Diri

Pengisian data diri adalah prerequisite sebelum mahasiswa dapat mengerjakan kuesioner MHI-38. Proses dimulai ketika mahasiswa membuka halaman isi data diri, baik dengan mengklik "Mulai Tes Baru" dari dashboard atau dengan direct access ke URL /mental-health/isi-data-diri.

Seperti pada dashboard, sistem melakukan authentication check terlebih dahulu. Middleware auth memverifikasi bahwa mahasiswa sudah login. Jika belum login, redirect ke halaman login dengan intended URL. Ini memastikan bahwa hanya mahasiswa ter-otentikasi yang dapat mengisi data diri.

Setelah verification berhasil, sistem mengambil data mahasiswa dari database dengan query ke tabel data_diris menggunakan NIM mahasiswa sebagai key. Sistem mengecek apakah data diri untuk mahasiswa tersebut sudah ada di database. Pengecekan ini dilakukan dengan query "SELECT * FROM data_diris WHERE nim = ?".

Jika data diri sudah ada (mahasiswa pernah mengisi data diri di tes sebelumnya), sistem akan menggunakan data tersebut untuk pre-fill form. Semua field input di form akan memiliki value attribute yang diisi dengan data existing. Ini significantly improve pengguna experience - mahasiswa tidak perlu mengisi ulang semua field dari awal setiap kali ingin tes. Mereka hanya perlu review data yang sudah ada dan update field yang mungkin berubah (misalnya alamat atau nomor HP).

Jika data diri belum ada (mahasiswa baru pertama kali tes), sistem menampilkan form kosong. Beberapa field seperti NIM dan Email akan tetap ter-fill otomatis karena data ini available dari Google OAuth login. Field NIM juga di-set readonly agar mahasiswa tidak bisa mengubahnya, karena NIM adalah primary key yang critical.

Form data diri terdiri dari dua section: Data Diri dan Riwayat Keluhan. Section Data Diri mencakup field-field: NIM (readonly, auto-filled dari login), Nama (auto-filled dari Google profile, editable), Jenis Kelamin (radio button: Laki-laki/Perempuan), Provinsi (dropdown atau text input tergantung penerapan), Alamat (textarea untuk alamat lengkap), Usia (number input), Fakultas (dropdown dengan list fakultas ITERA), Program Studi (dropdown yang dependent ke Fakultas yang dipilih), Asal Sekolah (text input), Status Tinggal (dropdown: Kos/Orang Tua/Asrama/Lainnya), dan Email (auto-filled dari login, readonly).

Section Riwayat Keluhan mencakup field-field yang bersifat opsional tetapi recommended: Keluhan yang dialami (textarea describing mental health concerns jika ada), Sudah berapa lama (text input describing duration), Pernah konsultasi? (yes/no radio button), dan Pernah tes sebelumnya? (yes/no radio button).

Mahasiswa mengisi atau memperbarui data di form tersebut. Required fields ditandai dengan asterisk merah. Mahasiswa harus mengisi semua required fields sebelum dapat submit. Optional fields seperti riwayat keluhan dapat dikosongkan jika mahasiswa tidak memiliki keluhan atau tidak ingin sharing.

Setelah selesai mengisi form, mahasiswa mengklik tombol "Lanjutkan" di bagian bawah form. Browser akan mengirimkan POST request ke endpoint /mental-health/isi-data-diri dengan semua form data dalam request body. Request juga membawa CSRF token untuk security.

Di sisi server, sistem menerima data dari formulir dan melakukan validasi menggunakan Form Request class StoreDataDiriRequest. Validasi mencakup pengecekan: required fields tidak boleh kosong, email harus valid email format, usia harus numeric dan dalam range reasonable (misalnya 16-50), NIM harus sesuai format NIM ITERA (9 digits), dan fakultas & program studi harus valid value dari enum list.

Jika validasi gagal, sistem akan return back ke form dengan error messages. Error messages bersifat field-specific sehingga mahasiswa tahu exactly field mana yang bermasalah. Old input values juga di-retain sehingga mahasiswa tidak perlu mengisi ulang seluruh form - hanya field yang error yang perlu diperbaiki. Sistem juga menampilkan highlight merah pada field yang error untuk visual indicator.

Jika validasi berhasil, sistem melanjutkan ke proses penyimpanan data. Sistem menggunakan Eloquent updateOrCreate method yang akan melakukan insert jika record belum ada, atau update jika record sudah ada. Query yang di-generate: "INSERT INTO data_diris (nim, nama, ...) VALUES (?, ?, ...) ON DUPLICATE KEY UPDATE nama = ?, ...".

Bersamaan dengan menyimpan data diri, sistem juga menyimpan riwayat keluhan jika diisi. Riwayat keluhan disimpan ke tabel terpisah riwayat_keluhans dengan foreign key nim yang reference ke data_diris. Pemisahan ini dilakukan karena keluhan bersifat historical - setiap kali mahasiswa tes, mereka bisa submit keluhan baru yang berbeda dengan tes sebelumnya.

Setelah penyimpanan berhasil, sistem menampilkan flash message "Data berhasil disimpan" menggunakan session flash. Flash message ini berwarna hijau (success) dan akan tampil di bagian atas halaman berikutnya. Sistem kemudian redirect mahasiswa ke halaman kuesioner MHI-38 (/mental-health/kuesioner).

Redirect ini adalah automatic alur yang guide mahasiswa melalui tahapan tes. Mahasiswa tidak perlu bingung "setelah isi data diri, saya harus ngapain?" - sistem otomatis membawa mereka ke step berikutnya yaitu mengerjakan kuesioner. Smooth alur seperti ini penting untuk conversion rate dan completion rate tes.

### 3.3 Activity Diagram: Mengisi Kuesioner MHI-38

Kuesioner MHI-38 adalah core assessment instrument dalam sistem. Proses dimulai ketika mahasiswa membuka halaman kuesioner, biasanya setelah di-redirect dari halaman data diri atau dengan mengklik link tes di dashboard.

Sistem melakukan authentication check seperti biasa dengan middleware auth. Yang unique pada halaman kuesioner adalah ada additional check untuk memastikan data diri mahasiswa sudah lengkap. Check ini dilakukan dengan query data_diris untuk melihat apakah semua required fields sudah terisi.

Jika data diri belum lengkap, sistem akan redirect mahasiswa kembali ke halaman isi data diri dengan flash message "Silakan lengkapi data diri terlebih dahulu". Redirect ini enforce alur yang proper - mahasiswa harus melengkapi data diri sebelum bisa mengerjakan kuesioner. Enforcement ini penting untuk data integrity dan untuk memastikan setiap hasil kuesioner terasosiasi dengan informasi demografis yang lengkap.

Jika data diri sudah lengkap, sistem proceed dengan menampilkan halaman kuesioner. Sistem me-load 38 pertanyaan dari array atau database. Pertanyaan-pertanyaan ini adalah standard MHI-38 questions yang sudah ter-validasi secara psikometrik. Urutan pertanyaan fixed dan sama untuk semua mahasiswa untuk menjaga consistency.

Halaman kuesioner memiliki layout yang ramah pengguna. Di bagian kiri adalah sidebar fixed yang menampilkan progress indicator berupa progress bar dan counter "X/38 pertanyaan terjawab". Indicator ini update secara real-time menggunakan JavaScript setiap kali mahasiswa menjawab pertanyaan. Visual progress umpan balik ini penting untuk motivasi - mahasiswa dapat melihat berapa banyak yang sudah dikerjakan dan berapa yang masih tersisa.

Di bagian tengah adalah main content area yang menampilkan pertanyaan-pertanyaan. Setiap pertanyaan memiliki format: nomor pertanyaan (1-38), text pertanyaan lengkap, dan 6 opsi jawaban dalam bentuk radio buttons atau scale slider labeled 1 (Tidak Pernah) sampai 6 (Selalu). Beberapa penerapan menggunakan horizontal scale yang lebih visual dibanding simple radio buttons.

Mahasiswa membaca setiap pertanyaan dengan seksama dan memilih jawaban yang paling sesuai dengan kondisi mereka. Tidak ada time limit untuk mengerjakan kuesioner - mahasiswa dapat take their time untuk reflect dan menjawab dengan jujur. Jawaban tidak dapat di-skip - mahasiswa harus menjawab satu pertanyaan sebelum bisa lanjut ke pertanyaan berikutnya (atau sistem allow scrolling bebas tapi menampilkan error jika ada pertanyaan yang belum dijawab saat submit).

Setiap kali mahasiswa memilih jawaban untuk satu pertanyaan, JavaScript handler akan fire event yang update progress indicator di sidebar. Count pertanyaan terjawab akan increment dan progress bar akan fill sesuai persentase (misalnya 10/38 = 26% progress). Update real-time ini give immediate umpan balik dan sense of accomplishment.

Proses menjawab berlanjut dalam loop: mahasiswa menjawab pertanyaan, progress indicator update, mahasiswa scroll ke pertanyaan berikutnya. Loop ini berulang sampai semua 38 pertanyaan terjawab. Saat pertanyaan ke-38 terjawab, progress indicator menunjukkan 100% dan tombol "Kirim" di bagian bawah form menjadi prominent (mungkin berubah warna atau size).

Ketika mahasiswa merasa sudah menjawab semua pertanyaan, mereka mengklik tombol "Kirim" di bagian bawah halaman. Sebelum submit, JavaScript dapat melakukan client-side validation untuk mengecek bahwa semua 38 pertanyaan benar-benar sudah terjawab. Jika ada yang terlewat, tampilkan alert "Masih ada X pertanyaan yang belum dijawab" dan scroll otomatis ke pertanyaan pertama yang terlewat.

Jika client-side validation pass, browser mengirim POST request ke endpoint /mental-health/kuesioner dengan payload berupa object atau array yang berisi 38 pasang key-value (question_id: answer_value). Request juga membawa CSRF token dan NIM mahasiswa (dari session atau hidden input).

Di sisi server, sistem menerima semua jawaban dan melakukan server-side validation. Server-side validation penting sebagai redundant check karena client-side validation dapat di-bypass. Sistem mengecek bahwa: exactly 38 jawaban diterima (not more, not less), setiap jawaban adalah integer antara 1-6 (valid range), dan tidak ada jawaban null atau empty.

Jika validasi gagal, sistem return error dengan status 422 Unprocessable Entity dan error messages explaining what went wrong. Browser akan menampilkan error messages di bagian atas form dan mahasiswa perlu melengkapi jawaban yang terlewat.

Jika validasi berhasil, sistem melanjutkan ke proses scoring. Sistem menjumlahkan semua 38 jawaban untuk mendapatkan total skor. Formula sederhana: total_skor = jawaban_1 + jawaban_2 + ... + jawaban_38. Dengan range jawaban 1-6 dan 38 pertanyaan, possible range total skor adalah 38 (minimum - semua dijawab 1) sampai 228 (maksimum - semua dijawab 6).

Namun perlu dicatat bahwa dalam MHI-38, ada pertanyaan yang bersifat reverse-scored. Pertanyaan positif (semakin tinggi jawaban, semakin baik kesehatan mental) di-score normal. Pertanyaan negatif (semakin tinggi jawaban, semakin buruk kesehatan mental) perlu di-reverse sebelum dijumlahkan: reversed_score = 7 - original_score. Implementasi reverse scoring ini penting untuk akurasi assessment.

Setelah mendapatkan total skor, sistem menentukan kategori kesehatan mental berdasarkan mapping predefined: skor 190-226 → "Sangat Sehat", skor 152-189 → "Sehat", skor 114-151 → "Cukup Sehat", skor 76-113 → "Perlu Dukungan", dan skor 38-75 → "Perlu Dukungan Intensif". Sistem menggunakan kondisional logic atau lookup table untuk mapping ini.

Setelah scoring dan kategorisasi selesai, sistem menyimpan hasil ke database. Record baru di-insert ke tabel hasil_kuesioners dengan fields: nim (foreign key ke data_diris), total_skor (hasil kalkulasi), kategori (hasil kategorisasi), jawaban_json (JSON encode dari array 38 jawaban untuk future reference), dan timestamp (created_at auto-populated).

Penyimpanan hasil juga trigger cache invalidation. Sistem clear cache untuk pengguna dashboard mahasiswa tersebut (cache key "user_dashboard_{nim}") dan cache statistik admin (cache key "admin_mental_health_stats"). Cache clearing ini penting untuk memastikan bahwa hasil tes baru segera reflected di dashboard tanpa perlu wait sampai cache expired.

Sistem juga may trigger notification atau event. Misalnya jika mahasiswa mendapat kategori "Perlu Dukungan Intensif", sistem dapat trigger email notification ke counseling center atau event yang di-listen oleh notification service. Ini memungkinkan early intervention untuk mahasiswa yang berisiko.

Setelah semua proses selesai, sistem menampilkan flash message "Kuesioner berhasil disimpan" dan redirect mahasiswa ke halaman hasil (/mental-health/hasil). Redirect ke halaman hasil memberikan immediate gratification - mahasiswa langsung dapat melihat hasil tes mereka setelah submit.

### 3.4 Activity Diagram: Melihat Hasil Tes

Halaman hasil tes adalah culmination dari proses assessment dimana mahasiswa finally get to see hasil dan interpretasi dari kuesioner yang mereka kerjakan. Proses dimulai ketika mahasiswa membuka halaman hasil, biasanya right after submit kuesioner atau dengan mengklik row hasil di dashboard.

Sistem melakukan authentication check standard. Setelah verified authenticated, sistem mencari hasil tes terbaru mahasiswa di database dengan query "SELECT * FROM hasil_kuesioners WHERE nim = ? ORDER BY created_at DESC LIMIT 1". Query ini mengambil record paling recent based on timestamp.

Sistem mengecek apakah query return result. Jika tidak ada hasil tes (misalnya mahasiswa somehow mengakses halaman hasil sebelum pernah mengerjakan tes), sistem menampilkan empty state. Empty state berupa: card atau section dengan icon atau illustration, text message "Belum ada hasil tes" atau "Anda belum pernah mengerjakan tes kesehatan mental", dan prominent button "Mulai Tes" yang link ke halaman isi data diri.

Empty state yang well-designed memberikan clear call-to-action dan guide mahasiswa untuk complete the expected flow. Mahasiswa tidak left confused tentang apa yang harus dilakukan next.

Jika ada hasil tes, sistem proceed dengan mengambil data hasil. Data yang diambil include: total skor (integer 38-228), kategori (string enum), dan tanggal pengerjaan (timestamp yang akan di-format readable). Sistem juga may load historical data untuk comparison atau trend analysis.

Berdasarkan kategori yang diperoleh, sistem menentukan penjelasan interpretasi yang akan ditampilkan. Setiap kategori memiliki interpretasi text yang explain apa arti kategori tersebut. Misalnya: "Sangat Sehat" → "Kondisi kesehatan mental Anda sangat baik. Anda memiliki kesejahteraan psikologis yang tinggi dan mampu mengelola stress dengan efektif. Pertahankan gaya hidup sehat dan kebiasaan positif Anda."

Sistem juga menentukan rekomendasi berdasarkan kategori. Rekomendasi bersifat actionable advice yang tailored ke level kesehatan mental mahasiswa. Contoh: "Sehat" → "Jaga kesehatan mental Anda dengan olahraga rutin, tidur cukup, dan maintain social connections. Consider mindfulness atau meditation practice untuk prevent future issues.", atau "Perlu Dukungan Intensif" → "Kami strongly recommend Anda untuk immediately consult dengan professional counselor. Kondisi Anda memerlukan professional assessment dan potentially therapy atau intervention."

Setelah data dan content siap, sistem me-render halaman hasil. Layout halaman hasil typically include: Header section dengan congratulatory message atau acknowledgement, Large card atau hero section yang display total skor dengan large typography untuk emphasis, Badge atau label yang display kategori dengan color coding (hijau untuk Sangat Sehat/Sehat, kuning untuk Cukup Sehat, orange untuk Perlu Dukungan, merah untuk Perlu Dukungan Intensif), Visual representation seperti gauge chart atau progress bar yang show dimana skor mahasiswa berada dalam spectrum kategori, Section penjelasan dengan heading "Interpretasi Hasil" dan paragraph text yang explain kategori, Section rekomendasi dengan heading "Rekomendasi" dan bullet points atau paragraph dengan actionable advice, dan Action buttons di bagian bawah: "Tes Lagi" dan "Kembali ke Dashboard".

Dari halaman hasil, terdapat kondisional content berdasarkan kategori. Jika kategori adalah "Perlu Dukungan" atau "Perlu Dukungan Intensif", sistem menampilkan additional section dengan informasi kontak layanan konseling. Section ini include: heading urgent-sounding seperti "Butuh Bantuan?" atau "Layanan Konseling", contact information untuk counseling center (phone, email, walk-in hours, location), link atau button untuk schedule appointment online jika available, dan reassuring message yang de-stigmatize seeking help.

Jika kategori adalah "Sangat Sehat", "Sehat", atau "Cukup Sehat", sistem menampilkan section motivasi dan tips. Section ini include: positive reinforcement message, tips untuk menjaga kesehatan mental (list of practical tips), resources untuk learning more about mental wellness (links to articles, videos, etc.), dan encouragement untuk continue monitoring mental health dengan tes berkala.

Conditional content ini make halaman hasil lebih relevant dan useful. Mahasiswa dengan kategori different needs different information dan support.

Dari halaman hasil, mahasiswa memiliki opsi interaksi. Jika mahasiswa ingin mengerjakan tes lagi (untuk re-assessment atau monitoring progress), mereka click tombol "Tes Lagi". Sistem redirect ke halaman isi data diri dimana form akan pre-filled dengan data existing. Mahasiswa verify/update data, lalu proceed ke kuesioner untuk tes baru.

Jika mahasiswa ingin kembali ke dashboard (untuk see overall progress atau access other fiturs), mereka click tombol "Kembali ke Dashboard". Sistem redirect ke dashboard dimana mereka akan see updated statistics reflecting tes yang baru selesai, termasuk total tes count yang increment dan kategori terakhir yang updated.

---

## 4. Penjelasan Activity Diagram Fitur Administrator

### 4.1 Activity Diagram: Melihat Dashboard Admin

Dashboard admin adalah command center dimana administrator mendapat bird's eye view dari semua data asesmen kesehatan mental di institusi. Proses dimulai ketika administrator membuka dashboard admin, typically di URL /admin atau /admin/mental-health.

Sistem pertama melakukan authentication check untuk memastikan pengguna yang mengakses adalah admin yang sudah login. Check ini dilakukan oleh middleware AdminAuth yang attached ke route admin. Middleware mengecek Auth::guard('admin')->check().

Jika not authenticated, redirect ke /login dengan intended URL. Jika authenticated, middleware proceed dengan session timeout validation. Middleware mengambil last_activity timestamp dari session dan menghitung time elapsed since last activity. Jika elapsed time > 30 minutes, trigger auto-logout process: Auth::guard('admin')->logout(), session regeneration, cache clearing, flash message "Session timeout. Silakan login kembali", dan redirect ke /login.

Auto-logout mechanism ini critical security fitur untuk admin dashboard. Admin memiliki access ke sensitive data semua mahasiswa, jadi idle session perlu di-terminate automatically untuk prevent unauthorized access jika admin lupa logout.

Jika session masih valid (elapsed time ≤ 30 menit), middleware update last_activity timestamp ke current time untuk reset timeout counter. Request then proceed ke controller dengan Admin pengguna authenticated dan session verified active.

Di controller, sistem check cache untuk admin dashboard statistics dengan cache key "admin_mental_health_stats". Cache TTL untuk admin stats adalah 1 minute, shorter than pengguna dashboard (5 minutes). Shorter TTL karena admin stats bersifat global aggregation yang need to be relatively fresh untuk accurate institutional monitoring.

Jika cache hit, ambil stats dari cache dan skip database queries. Jika cache miss, execute series of database queries untuk gather comprehensive statistics:

Query 1 - Total mahasiswa: SELECT COUNT(DISTINCT nim) FROM data_diris. Query ini count unique mahasiswa yang pernah mengisi data diri.

Query 2 - Total tes: SELECT COUNT(*) FROM hasil_kuesioners. Query ini count total tes yang pernah diselesaikan across all mahasiswa.

Query 3 - Gender distribution: SELECT jenis_kelamin, COUNT(*) as count FROM data_diris GROUP BY jenis_kelamin. Query ini breakdown mahasiswa by gender.

Query 4 - Faculty distribution: SELECT fakultas, COUNT(*) as count FROM data_diris GROUP BY fakultas ORDER BY count DESC. Query ini breakdown mahasiswa by fakultas, sorted by count descending untuk identify fakultas dengan partisipasi highest.

Query 5 - Top schools: SELECT asal_sekolah, COUNT(*) as count FROM data_diris GROUP BY asal_sekolah ORDER BY count DESC LIMIT 5. Query ini identify top 5 asal sekolah with most students tested.

Query 6 - Category distribution: SELECT kategori, COUNT(*) as count FROM (SELECT nim, kategori FROM hasil_kuesioners WHERE (nim, created_at) IN (SELECT nim, MAX(created_at) FROM hasil_kuesioners GROUP BY nim)) as latest GROUP BY kategori. Query complex ini get latest category untuk each mahasiswa, then count distribution across categories. Complexity necessary untuk avoid counting duplicate mahasiswa yang test multiple times.

Semua query results di-aggregate into statistics object, then cached dengan cache key dan TTL 1 minute. Caching ini significantly reduce database load, especially untuk dashboard yang frequently accessed.

Setelah statistics ready, sistem query data mahasiswa untuk table display. Query ini more complex karena need to aggregate data dari multiple tables:

```sql
SELECT
    dd.nim,
    dd.nama,
    dd.fakultas,
    dd.program_studi,
    dd.jenis_kelamin,
    dd.email,
    COUNT(hk.id) as total_tes,
    latest.kategori as kategori_terakhir,
    latest.total_skor as skor_terakhir,
    latest.created_at as tanggal_terakhir
FROM data_diris dd
LEFT JOIN hasil_kuesioners hk ON dd.nim = hk.nim
LEFT JOIN (
    SELECT nim, kategori, total_skor, created_at
    FROM hasil_kuesioners hk1
    WHERE created_at = (SELECT MAX(created_at) FROM hasil_kuesioners hk2 WHERE hk2.nim = hk1.nim)
) as latest ON dd.nim = latest.nim
GROUP BY dd.nim
ORDER BY latest.created_at DESC
```

Query ini menggunakan LEFT JOIN untuk include mahasiswa yang sudah isi data diri but belum tes, subquery untuk get latest test result per mahasiswa, GROUP BY untuk aggregate test count per mahasiswa, dan ORDER BY latest test date untuk show most recent tests first.

Query optimization techniques applied: indexes on nim columns untuk fast joins, indexes on created_at untuk fast MAX() lookup, dan limit query results untuk pagination (typically 10 or 25 records per page).

Query results are paginated menggunakan Laravel's pagination (typically 10 entries per page dengan parameter ?page= untuk navigation). Pagination essential untuk performance dengan large datasets - pemuatan all records at once would be slow dan memory-intensive.

Dengan statistics dan table data ready, sistem render view dashboard admin. View layout typically include:

Header section dengan greeting "Selamat datang, Admin" dan current date/time.

Statistics cards row dengan card-card displaying: Total Mahasiswa (large number dengan icon), Total Tes Diselesaikan (large number dengan icon), dan Gender Distribution (breakdown laki-laki vs perempuan dengan percentage).

Faculty distribution chart (bar chart atau pie chart) visualizing test participation per faculty.

Search dan filter controls: Search box untuk keyword search across 11 fields, dropdown filter kategori untuk filter by mental health category, dropdown sort untuk customize sorting column dan order, dan tombol "Export Excel" prominent di corner untuk quick export.

Data table dengan columns: No (row number), Tanggal Tes (formatted date), NIM, Nama, Fakultas, Prodi, Kategori (dengan color-coded badge), Skor, dan Actions (buttons untuk Detail, Delete).

Pagination controls di bottom: Previous/Next buttons, page numbers, dan dropdown untuk items per page (10/25/50/100).

Dashboard ini adalah information-dense antarmuka but organized logically dengan clear visual hierarchy. Stats cards give quick overview at-a-glance, charts provide visual insights, dan table provide detailed data dengan interactivity.

Administrator can interact dengan dashboard in several ways: click Detail button untuk see comprehensive details of specific student (trigger modal popup), use search box untuk find students by various criteria, use filter dropdown untuk focus on specific mental health category, use sort dropdown untuk reorder table by different columns, click Export Excel untuk download data sesuai current filters, dan use pagination controls untuk navigate through pages of data.

### 4.2 Activity Diagram: Mencari dan Menyaring Data

Fitur search dan penyaringan adalah powerful tools yang allow administrator untuk quickly find relevant data dalam large dataset. Activity diagram ini cover three related operations: searching by keyword, penyaringan by category, dan sorting by column.

Process starts dengan administrator already di dashboard admin, looking at table of student data. Administrator decide mereka want to narrow down atau reorder data based on certain criteria.

Administrator first choose jenis operasi yang ingin dilakukan. Three main options: Search by keyword, Filter by category, atau Sort by column. Diagram menggunakan kondisional branching (if/elseif/else) untuk handle three different flows.

**Flow 1: Search by Keyword**

Jika administrator choose to search, mereka type keyword(s) ke search box di top of table. Search box is typically text input dengan placeholder text "Cari mahasiswa..." dan search icon di inside box.

Keyword bisa single word (e.g., "Teknik") atau multiple words (e.g., "Teknik Informatika"). System support multi-term search dimana multiple keywords bisa separated by spaces.

Administrator kemudian click tombol "Cari" button atau simply press Enter key di search box. Browser mengirim GET request ke endpoint /admin/mental-health dengan query parameter ?search={keyword}.

Di server side, system receive search keyword dan execute search query against database. Search is performed across 11 fields using OR logic:

```sql
SELECT ... FROM data_diris dd
LEFT JOIN hasil_kuesioners hk ON dd.nim = hk.nim
WHERE
    dd.nim LIKE '%keyword%' OR
    dd.nama LIKE '%keyword%' OR
    dd.fakultas LIKE '%keyword%' OR
    dd.program_studi LIKE '%keyword%' OR
    dd.email LIKE '%keyword%' OR
    dd.jenis_kelamin LIKE '%keyword%' OR
    dd.provinsi LIKE '%keyword%' OR
    dd.alamat LIKE '%keyword%' OR
    dd.asal_sekolah LIKE '%keyword%' OR
    dd.status_tinggal LIKE '%keyword%' OR
    latest.kategori LIKE '%keyword%'
```

LIKE operator dengan wildcard %keyword% allows partial matching. E.g., search "Tek" will match "Teknik", "Teknologi", "Arsitektur" (contains "tek").

Untuk multi-term search, system can split keyword by spaces dan create nested OR conditions untuk each term. This allows flexible searching where results match any of the terms.

System may also implement "smart matching" dimana certain keywords are recognized as shortcuts. E.g., "FTI" maps to "Fakultas Teknologi Industri", "L"/"P" maps to "Laki-laki"/"Perempuan".

Search query is executed dan results (yang match keyword) are returned. System then render dashboard view dengan table showing only matched records. Jika no matches found, empty state displayed dengan message "Tidak ada data yang sesuai dengan pencarian '{keyword}'" dan suggestion untuk try different keyword atau reset filter.

Search keyword is retained di search box so administrator can see what they searched for dan can easily modify search term. Pagination is reset to page 1 since search results are new dataset.

**Flow 2: Filter by Category**

Jika administrator choose to filter, mereka select kategori dari dropdown filter. Dropdown typically di top-right corner of table dengan label "Filter Kategori:".

Dropdown options include: "Semua" (default, show all students), "Sangat Sehat", "Sehat", "Cukup Sehat", "Perlu Dukungan", dan "Perlu Dukungan Intensif".

Ketika administrator select option dari dropdown, browser immediately send GET request (bisa auto-submit on change atau require click "Apply" button) ke /admin/mental-health?kategori={selected_category}.

Di server side, system receive kategori parameter dan apply filter to query:

```sql
SELECT ... FROM data_diris dd
LEFT JOIN hasil_kuesioners hk ON dd.nim = hk.nim
WHERE latest.kategori = 'selected_category'
```

Jika kategori is "Semua", WHERE clause is omitted untuk show all records.

Filter is particularly useful untuk identify students needing intervention. E.g., administrator can filter "Perlu Dukungan Intensif" untuk get list of students yang require immediate attention dan follow-up.

Query executed, filtered results returned, dan view rendered showing only students dengan selected category. Category badge di table rows akan all be same color since all match filter criteria.

Selected category is retained di dropdown so administrator can see active filter. Clear atau Reset button may be provided untuk quickly remove filter dan return to showing all data.

**Flow 3: Sort by Column**

Jika administrator choose to sort, mereka select column to sort by dari dropdown atau click column header di table.

Common sortable columns include: Tanggal Tes (untuk see newest atau oldest tests), Nama (alphabetical), NIM (numerical), Fakultas (alphabetical), Kategori (by severity level), dan Skor (numerical, highest to lowest atau vice versa).

Sorting typically toggle between ascending dan descending order. First click on column header sort ascending, second click sort descending, third click remove sort dan return to default order.

Browser send GET request dengan sort parameters: /admin/mental-health?sort_by={column}&sort_order={asc|desc}.

Di server side, system receive sort parameters dan modify ORDER BY clause of query:

```sql
SELECT ... FROM data_diris dd
LEFT JOIN hasil_kuesioners hk ON dd.nim = hk.nim
ORDER BY {sort_column} {sort_order}
```

Column name is validated against whitelist untuk prevent SQL injection. Only allowed column names can be used in ORDER BY.

Sorted results returned dan rendered. Table rows reorder according to selected sort criteria. Visual indicator (arrow icon) shown on sorted column header indicating current sort direction (up arrow for ascending, down arrow for descending).

Sort parameters retained in URL so current sort state is bookmarkable dan shareable. Administrator can send dashboard URL to colleague dan colleague will see same sorted view.

**After any Operation**

After any of three operations (search, filter, sort), administrator see table dengan data yang sudah di-adjust according to criteria. System menampilkan result count seperti "Menampilkan 1-10 dari 45 hasil" untuk give umpan balik about result set size.

Administrator dapat combine multiple operations. E.g., search "Teknik", then filter "Perlu Dukungan", then sort by "Skor" descending. Combined operations allow very precise data slicing.

Jika administrator ingin reset all filters dan kembali ke view all data, mereka dapat click tombol "Reset" atau reload dashboard page. Reset button clear all query parameters dan reload default view.

From filtered/sorted view, administrator masih dapat perform other actions like melihat details atau exporting data. Export operation respects active filters - jika administrator export after penyaringan "Perlu Dukungan Intensif", Excel file will only contain those students, not all students.

### 4.3 Activity Diagram: Melihat Detail Mahasiswa

View detail mahasiswa fitur allows administrator untuk see comprehensive information about specific student, including personal data, complaint history, all test results, dan progress chart. Process starts ketika administrator, while looking at table di dashboard, decide mereka want deep dive into specific student's data.

Administrator identify student of interest in table (maybe karena kategori concerning, atau untuk follow-up purpose) dan click tombol "Lihat" atau "Detail" pada row mahasiswa tersebut. Button typically small icon button (eye icon) in Actions column of table.

Click event trigger JavaScript yang open modal dialog atau navigate to detail page. Implementation bisa either: AJAX-based modal yang load data via API call dan display in overlay popup, atau full page navigation ke /admin/mental-health/student/{nim} yang render dedicated detail page.

Modal-based approach adalah more common karena allow quick melihat without leaving dashboard context. Administrator can close modal dan still be at same position in table.

Di sisi system, request received dengan NIM mahasiswa yang di-click. System execute query untuk fetch comprehensive data:

Query 1 - Personal data dari data_diris table:
```sql
SELECT * FROM data_diris WHERE nim = '{clicked_nim}'
```

Query 2 - Complaint history dari riwayat_keluhans table:
```sql
SELECT keluhan, created_at FROM riwayat_keluhans WHERE nim = '{clicked_nim}' ORDER BY created_at DESC
```

Query 3 - All test results dari hasil_kuesioners table:
```sql
SELECT total_skor, kategori, created_at FROM hasil_kuesioners WHERE nim = '{clicked_nim}' ORDER BY created_at ASC
```

Query 3 ordered by created_at ASC (oldest first) karena data akan digunakan untuk chart yang show progression over time.

System aggregate query results dan calculate statistics for this student specifically: total tes diambil (count of test results), rata-rata skor (average of total_skor across all tests), dan trend kategori (analyze if category improving, worsening, atau stable over time).

System generate data for progress chart. Chart data adalah array of coordinates: [{date: 'YYYY-MM-DD', score: 123}, ...]. Chart will visualize student's score progression over time as line chart, making trend easily visible.

All data compiled into comprehensive student profile object dan rendered in modal atau detail page. View layout typically organized dengan tab structure atau accordion sections untuk organize information:

**Section 1: Informasi Pribadi**
Display personal data fields in labeled key-value format: NIM, Nama Lengkap, Jenis Kelamin, Usia, Email, Fakultas, Program Studi, Provinsi Asal, Alamat Lengkap, Asal Sekolah, dan Status Tempat Tinggal. Data displayed read-only since admin should not edit student data through dashboard.

**Section 2: Riwayat Keluhan**
Display timeline atau list of complaints submitted by student across different tests. Each complaint entry show: tanggal keluhan submitted, text keluhan lengkap, durasi keluhan, apakah student pernah konsultasi, dan apakah pernah tes sebelumnya. Jika no complaints ever submitted, show empty state "Tidak ada riwayat keluhan".

**Section 3: Hasil Tes**
Display table of all test results dengan columns: No (counter), Tanggal Pengerjaan (formatted date/time), Total Skor (numeric), Kategori (color-coded badge), dan Aksi (button untuk view detail jawaban). Table sorted chronologically dari newest ke oldest atau sebaliknya dengan option to toggle. Prominent display of most recent test result at top atau highlighted.

**Section 4: Grafik Progres**
Display interactive line chart showing score progression over time. X-axis adalah dates of tests, Y-axis adalah total score (range 38-228). Chart includes: grid lines untuk readability, colored zones untuk category ranges (red zone 38-75, orange zone 76-113, yellow zone 114-151, light green 152-189, dark green 190-226), data points untuk each test dengan tooltip showing exact score dan date, dan trend line atau moving average untuk show overall trajectory.

Chart visualization make trend immediately apparent. Administrator can quickly see if student improving (upward trend), worsening (downward trend), atau stable. Sharp drops in score can indicate crisis periods needing intervention.

From detail view, administrator can analyze data dan make informed decisions. E.g., if student started at "Sehat" but recent tests show decline to "Perlu Dukungan", this is red flag for follow-up. Conversely, if student started at "Perlu Dukungan" but show consistent improvement to "Sehat", this is positive indicator that interventions working.

Administrator dapat click button "Lihat Detail Jawaban" on specific test result untuk drill down further ke detailed answers for that specific test. This navigate to separate detail jawaban page (covered in next diagram).

Ketika administrator done remelihat detail, mereka click tombol "Tutup" untuk close modal atau browser back button untuk return to dashboard. System close modal overlay dan return to dashboard table dengan same position dan filter state as before, providing seamless navigation experience.

### 4.4 Activity Diagram: Melihat Detail Jawaban Kuesioner

Fitur detail jawaban adalah new fitur (Update November 13, 2025) yang allow administrator untuk see not just total score dan category, but actual answers untuk each of 38 questions. This granular view is useful untuk counselors yang need to understand specific areas of concern untuk student.

Process starts dari dashboard admin dimana administrator see table of students dengan test results. Administrator decide mereka want to see detailed answers for specific student's specific test. They click tombol "Detail" pada baris mahasiswa yang ingin dilihat.

Browser navigate to detail jawaban page at URL /admin/detail-jawaban/{id_hasil} dimana {id_hasil} adalah primary key of hasil_kuesioners record. Page load dengan full page refresh (bukan modal) karena detail jawaban adalah information-dense page yang needs full screen space.

Di sisi system, controller method receive hasil_kuesioner ID dan validate that ID exists. System execute query dengan eager pemuatan untuk optimize performance:

```sql
SELECT hk.*, dd.nim, dd.nama, dd.program_studi, dd.jenis_kelamin, ...
FROM hasil_kuesioners hk
INNER JOIN data_diris dd ON hk.nim = dd.nim
WHERE hk.id = {id_hasil}
```

Eager pemuatan prevent N+1 query problem. Instead of separate query untuk get data_diris after getting hasil_kuesioner, single join query get both.

System check if query return result. Jika hasil kuesioner dengan ID tersebut tidak ditemukan (maybe invalid ID atau record deleted), system show 404 error page dengan message "Data tidak ditemukan" dan button "Kembali ke Dashboard".

Jika hasil kuesioner found, system retrieve 38 questions dari controller atau config. Questions stored as array di controller atau in config file, not in database, karena questions are static dan same for all students.

```php
protected $pertanyaanMHI38 = [
    1 => "Seberapa sering Anda merasa gugup atau tegang?",
    2 => "Seberapa sering Anda merasa sangat sedih?",
    // ... 38 questions total
];
```

System determine tipe pertanyaan (positif atau negatif) untuk each question number. Tipe determined by checking if question number in array of negative questions:

```php
protected $pertanyaanNegatif = [2, 3, 8, 9, 10, 12, 14, 15, 17, 19, 20, 21, 22, 24, 26, 28, 30, 32, 35, 37];
```

20 questions are negative-type (higher score indicates worse mental health), dan 18 questions are positive-type (higher score indicates better mental health). Tipe designation important untuk interpretation.

System retrieve jawaban_details records untuk hasil kuesioner tersebut dari mental_health_jawaban_details table:

```sql
SELECT nomor_soal, skor_jawaban FROM mental_health_jawaban_details WHERE id_hasil = {id_hasil} ORDER BY nomor_soal ASC
```

Ordering by nomor_soal ensure answers displayed in correct sequence 1-38.

System combine questions array, tipe designation, dan answers data into unified data structure untuk rendering:

```php
$detailJawaban = [];
for ($i = 1; $i <= 38; $i++) {
    $detailJawaban[] = [
        'nomor' => $i,
        'pertanyaan' => $this->pertanyaanMHI38[$i],
        'tipe' => in_array($i, $this->pertanyaanNegatif) ? 'Negatif' : 'Positif',
        'skor' => $jawabanDetails->where('nomor_soal', $i)->first()->skor_jawaban ?? '-'
    ];
}
```

Loop ensures all 38 questions represented even if some answers missing (though validation should prevent missing answers).

View then rendered with comprehensive layout. Layout organized in clear sections:

**Header Section**
Page title "Detail Jawaban Kuesioner MHI-38" dan breadcrumb navigation showing: Dashboard Admin > Detail Jawaban.

**Information Cards Grid**
Four cards di top of page displaying key info dengan explicit ordering:
- Card 1: NIM (e.g., "123456789")
- Card 2: Nama (e.g., "John Doe")
- Card 3: Program Studi (e.g., "Teknik Informatika")
- Card 4: Tanggal Tes (e.g., "13 November 2025 - 14:30 WIB")

Cards have consistent styling dengan icon untuk each field dan formatted layout.

**Summary Section**
Card atau panel showing test summary: Total Skor displayed prominent dengan large font (e.g., "156"), Kategori displayed as color-coded badge matching color scheme used throughout app (e.g., green badge "Sehat").

**Detail Jawaban Table**
Comprehensive table showing all 38 questions and answers dengan columns:
- No: Question number 1-38
- Tipe: Badge showing "Positif" (green) atau "Negatif" (red)
- Pertanyaan: Full question text
- Skor: Answer value 1-6 displayed bold untuk emphasis

Table has alternating row colors untuk readability dan fixed header that stays visible on scroll untuk large tables. Full question text displayed (no truncation) since understanding full question important untuk interpretation.

Tipe badges use distinct colors: green for Positif questions, red for Negatif questions. Color coding help counselor quickly identify question type without reading, speeding up analysis workflow.

**Action Buttons**
Two buttons at bottom of page:
- "Cetak PDF" button (primary button, prominent) untuk export detail to PDF
- "Kembali" button (secondary button) untuk navigate back to dashboard

From detail jawaban view, administrator dapat analyze answers untuk identify specific areas of concern. E.g., if student score high on multiple Negatif questions related to anxiety (questions 2, 3, 8, 9, 10), counselor can focus counseling sessions on anxiety management strategies.

Jika administrator ingin export detail untuk documentation atau untuk share dengan counselor, they click tombol "Cetak PDF". This trigger PDF generation alur (covered in next diagram).

Jika administrator done remelihat dan want return to dashboard, they click tombol "Kembali". Browser navigate back to /admin dashboard dimana administrator return to table view dengan same filters and pagination state as before navigating away (state maintained via URL query parameters).

### 4.5 Activity Diagram: Mengekspor Detail Jawaban ke PDF

PDF export fitur allows administrator untuk create printable document of detail jawaban yang can be saved for records, shared with counselors, atau included in student consultation files. Process starts ketika administrator already melihat detail jawaban page dan decide they want PDF copy.

Administrator click tombol "Cetak PDF" at bottom of detail jawaban page. Button click trigger JavaScript function yang handle PDF generation client-side menggunakan jsPDF library.

JavaScript first show pemuatan indicator dengan SweetAlert pop-up displaying message "Mencetak PDF..." dengan spinning loader icon. Loading indicator important karena PDF generation for 38-question table can take few seconds, dan without indicator pengguna might think button click did nothing.

JavaScript initialize jsPDF instance dengan configuration:

```javascript
const pdf = new jsPDF({
    orientation: 'portrait',
    unit: 'mm',
    format: 'a4'
});
```

Portrait orientation chosen untuk better accommodate long question text. A4 format is standard paper size. Unit mm (millimeters) used untuk precise positioning.

JavaScript validate that jsPDF library loaded correctly. If library not available (CDN failed atau ad blocker blocked), show error alert "Library jsPDF tidak tersedia. Silakan refresh halaman." dan abort PDF generation.

If library available, JavaScript begin building PDF content. Content added in sections:

**Section 1: Header**
Generate header text "Detail Jawaban Kuesioner Mental Health" centered at top of page dengan large font size (16-18pt). Add subtitle with test date formatted as "DD F Y - H:i WIB" (e.g., "13 November 2025 - 14:30 WIB"). Draw horizontal line below header untuk visual separation (line from 20mm to 190mm width, y position ~25mm).

**Section 2: Student Information**
Add section heading "Informasi Mahasiswa" dengan font size 12pt bold. Add student info as bulleted list:
• NIM: {nim_value}
• Nama: {nama_value}
• Program Studi: {prodi_value}
• Tanggal Tes: {date_value}

Bullet character is actual bullet point (•), not asterisk atau dash, untuk professional appearance. Each info line has consistent indentation dan spacing.

**Section 3: Test Summary**
Add section heading "Ringkasan Hasil". Display total score dan category:
• Total Skor: {skor_value}
• Kategori: {kategori_value}

**Section 4: Questions Table**
Generate table of 38 questions and answers menggunakan jsPDF autoTable plugin. Table configuration:

```javascript
pdf.autoTable({
    head: [['No', 'Tipe', 'Pertanyaan', 'Skor']],
    body: tableData, // Array of 38 rows
    startY: 95, // Start position after header sections
    margin: { left: 22.5, right: 22.5 }, // Centered margins
    headStyles: {
        fillColor: [66, 139, 202], // Blue header
        halign: 'center',
        fontStyle: 'bold'
    },
    columnStyles: {
        0: { halign: 'center', cellWidth: 10 }, // No column: narrow, centered
        1: { halign: 'center', cellWidth: 20 }, // Tipe column: medium, centered
        2: { halign: 'left', cellWidth: 110 }, // Pertanyaan column: wide, left-aligned
        3: { halign: 'center', cellWidth: 15, fontStyle: 'bold' } // Skor column: narrow, centered, bold
    },
    bodyStyles: {
        fontSize: 9
    }
});
```

Table data prepared by looping through 38 questions dan creating array entries:

```javascript
const tableData = [];
for (let i = 1; i <= 38; i++) {
    const tipe = negative_questions.includes(i) ? 'Negatif' : 'Positif';
    const pertanyaan = questions[i]; // Escaped for JSON
    const skor = answers[i] || '-';
    tableData.push([i, tipe, pertanyaan, skor]);
}
```

Question text escaped properly untuk handle special characters like quotes atau line breaks yang might break PDF rendering. Escape done with JSON.stringify() atau replace() untuk certain problematic characters.

Table styling includes: Tipe Positif rows dengan green text color (RGB: 40, 167, 69), Tipe Negatif rows dengan red text color (RGB: 220, 53, 69), Skor values bold dengan font size 9pt, dan Alternating row backgrounds untuk readability.

Table automatically handles pagination - if table extends beyond one page, autoTable automatically creates new pages dan repeats header on each page.

**Section 5: Watermark**
After all content added, JavaScript loop through all pages in PDF dan add watermark to each page:

```javascript
const pageCount = pdf.internal.getNumberOfPages();
for (let i = 1; i <= pageCount; i++) {
    pdf.setPage(i);
    pdf.setFontSize(8);
    pdf.setTextColor(150, 150, 150); // Gray color
    pdf.setFont('helvetica', 'italic');
    pdf.text('Generated by ANALOGY - ITERA',
             190, // x position: right edge
             285, // y position: bottom edge
             { align: 'right' });
}
```

Watermark positioned at bottom-right corner of every page. Text "Generated by ANALOGY - ITERA" dalam italic font dengan gray color untuk subtle appearance yang doesn't obstruct main content. Watermark serves as document authenticity indicator dan branding.

After PDF fully constructed, JavaScript generate filename dengan format:

```javascript
const filename = `Detail-Jawaban-${nim}-${timestamp}.pdf`;
// Example: Detail-Jawaban-123456789-20251113143045.pdf
```

Filename includes NIM untuk easy identification dan timestamp untuk uniqueness dan version tracking.

JavaScript close SweetAlert pemuatan indicator dan show success message "PDF berhasil dicetak!" dengan green checkmark icon. Success message give pengguna confirmation that operation completed.

Browser automatically trigger download of generated PDF file dengan specified filename. Download appears in browser's download bar atau folder dengan file ready to open.

Entire PDF generation process handled client-side, no server request needed. Client-side generation advantages: reduces server load, works offline, faster generation (no network bolak-balik), dan no temporary files to manage on server.

Jika any error occur during PDF generation, JavaScript catch error in try-catch block:

```javascript
try {
    // PDF generation code
} catch (error) {
    console.error('PDF Generation Error:', error);
    Swal.close(); // Close pemuatan indicator
    Swal.fire({
        icon: 'error',
        title: 'Gagal Mencetak PDF',
        text: error.message || 'Terjadi kesalahan saat membuat PDF'
    });
}
```

Error handling ensures pengguna gets umpan balik if something goes wrong (e.g., out of memory, browser compatibility issue) rather than page just freezing atau failing silently.

After PDF downloaded, administrator can open file dengan PDF reader untuk verify content, save file to appropriate folder (e.g., student records folder), attach file to email if sharing dengan counselor, atau print file to physical paper if hard copy needed.

### 4.6 Activity Diagram: Mengekspor Data ke Excel

Excel export fitur allows administrator untuk extract student data in spreadsheet format untuk advanced analysis, reporting, atau archival. Unlike PDF export which is for individual student detail, Excel export is for bulk data export potentially covering many students.

Process starts dengan administrator at dashboard admin, possibly after applying search filters atau category filters untuk narrow down data. Administrator decide they want export current data view to Excel untuk further analysis.

Administrator may atau may not have applied filters. Export respects current filter state - jika administrator filtered for "Fakultas Teknik" students dengan "Perlu Dukungan" category, exported Excel will contain only those students, not all students in database.

Administrator click tombol "Ekspor ke Excel" which is prominently placed near top of dashboard, typically near search box. Button labeled clearly dengan Excel icon (green spreadsheet icon) untuk instant recognition.

Button click send GET request to server endpoint /admin/mental-health/export. Request carries same query parameters as current dashboard view (search, kategori, sort_by, sort_order), ensuring export matches what administrator seeing on screen.

Di sisi server, system receive export request dan first validate authentication. Validation check that request coming from authenticated admin, not unauthorized pengguna trying direct URL access. If not authenticated, return 403 Forbidden response.

If authenticated, system retrieve query parameters from request (search keyword, category filter, sort column/order). System then construct database query that matches dashboard query but WITHOUT pagination limit. Dashboard query limited to 10 atau 25 records per page for display, but export query gets ALL matching records.

Query similar to dashboard query but selecting full data fields needed for export:

```sql
SELECT
    dd.nim,
    dd.nama,
    dd.jenis_kelamin,
    dd.usia,
    dd.provinsi,
    dd.alamat,
    dd.email,
    dd.fakultas,
    dd.program_studi,
    dd.asal_sekolah,
    dd.status_tinggal,
    COUNT(hk.id) as jumlah_tes,
    latest.kategori as kategori_terakhir,
    latest.total_skor as skor_terakhir,
    latest.created_at as tanggal_terakhir
FROM data_diris dd
LEFT JOIN hasil_kuesioners hk ON dd.nim = hk.nim
LEFT JOIN (/* subquery untuk latest test */) as latest ON dd.nim = latest.nim
WHERE {filter conditions from query parameters}
GROUP BY dd.nim
ORDER BY {sort from query parameters}
```

Query executed dan returns collection of all matching students. Collection could be anywhere from few records to thousands depending on filter criteria.

System then instantiate Excel export class (HasilKuesionerExport) yang implements Maatwebsite\Excel\Concerns\FromCollection dan other export antarmukas:

```php
$export = new HasilKuesionerExport($collection);
return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX);
```

HasilKuesionerExport class define structure of Excel file. Methods include:

**collection()**: Returns data collection to be exported.

**headings()**: Returns array of column headers:
```php
return ['No', 'Tanggal Pengerjaan', 'NIM', 'Nama', 'Fakultas', 'Program Studi',
        'Jenis Kelamin', 'Usia', 'Provinsi', 'Alamat', 'Email', 'Asal Sekolah',
        'Status Tinggal', 'Jumlah Tes', 'Kategori Terakhir', 'Skor Terakhir'];
```
Total 16 columns providing comprehensive demographic dan test data.

**map($row)**: Transform each data row for export:
```php
public function map($row): array {
    return [
        ++$this->rowNumber, // Auto-increment row number
        Carbon::parse($row->tanggal_terakhir)->format('d/m/Y H:i'), // Formatted date
        $row->nim,
        $row->nama,
        $row->fakultas,
        $row->program_studi,
        $row->jenis_kelamin,
        $row->usia,
        $row->provinsi,
        $row->alamat,
        $row->email,
        $row->asal_sekolah,
        $row->status_tinggal,
        $row->jumlah_tes,
        $row->kategori_terakhir,
        $row->skor_terakhir
    ];
}
```

**styles()**: Apply styling to Excel sheet untuk professional appearance:
```php
public function styles(Worksheet $sheet) {
    return [
        1 => ['font' => ['bold' => true]], // Bold header row
        'A1:P1' => ['fill' => ['fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '4472C4']]], // Blue header background
    ];
}
```

**columnFormats()**: Define format for specific columns:
```php
public function columnFormats(): array {
    return [
        'C' => NumberFormat::FORMAT_TEXT, // NIM as text to prevent scientific notation
    ];
}
```

Library handles Excel file generation including: Writing data to XLSX file format (modern Excel format dengan compression), Applying cell formatting dan column widths, Creating proper Excel structure dengan headers, Handling special characters dan encoding correctly, dan Generating temporary file yang will be streamed to client.

Filename generated dengan timestamp untuk uniqueness:

```php
$filename = 'mental_health_data_' . now()->format('YmdHis') . '.xlsx';
// Example: mental_health_data_20251113143045.xlsx
```

System prepare HTTP response dengan headers that trigger browser download:

```php
return response()->download($tempFile, $filename, [
    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'Content-Disposition' => 'attachment; filename="' . $filename . '"'
]);
```

Response sent to browser. Browser receive response dan automatically start download. File appears in browser download bar with filename visible.

From browser perspective, administrator see: Loading indicator or progress bar during export generation (for large exports), Download notification when file ready, dan File appearing in browser downloads folder.

Administrator then dapat: Open file dengan Microsoft Excel, Google Sheets, atau LibreOffice Calc, Review data in spreadsheet format dengan sort/filter capabilities, Perform advanced analysis using Excel formulas atau pivot tables, Create charts/graphs untuk data visualization, Share file dengan colleagues atau management, dan Archive file untuk record-keeping.

Excel format advantages over PDF: Data editable dan manipulable, Rows/columns can be sorted and filtered, Formulas can be added untuk calculations, Large datasets handled more efficiently, dan Can be imported into other analysis tools.

Jika error occur during export (e.g., database query failure, file write permission error, memory exhausted untuk very large export), system catch error dan return error response dengan message explaining issue. Error message displayed to administrator via flash message atau error page.

For very large exports (thousands of records), system may implement queueing where export job queued dan processed in background, dengan notification sent to administrator when file ready untuk download. This prevent timeout issues dan improve pengguna experience for large data exports.

### 4.7 Activity Diagram: Menghapus Data Mahasiswa

Delete operation is destructive action yang permanently remove student's data from system. Karena irreversibility, process include multiple confirmation steps dan safeguards untuk prevent accidental penghapusan.

Process starts dengan administrator at dashboard, looking at table of students. Administrator identify student whose data need to be deleted. Deletion might be necessary karena: student request untuk data removal (GDPR right to be forgotten), duplicate entry need to be cleaned up, test data or demo data need to be removed, atau data entered incorrectly dan need to be re-entered.

Administrator locate student in table dan click tombol "Hapus" atau "Delete" in Actions column. Button typically red-colored atau with trash icon untuk indicate destructive action.

Button click trigger JavaScript yang show confirmation popup. Instead of immediate penghapusan, system show double-confirmation dialog for safety:

```javascript
Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Menghapus data mahasiswa akan menghapus semua data terkait termasuk riwayat tes, keluhan, dan data diri. Tindakan ini tidak dapat dibatalkan!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
});
```

Confirmation popup emphasize severity dengan: warning icon (yellow exclamation triangle), prominent text explaining data will be permanently deleted, mention that action is irreversible, Red confirm button dengan explicit text "Ya, Hapus!", dan Gray cancel button yang equally prominent giving pengguna easy out.

Dari popup, administrator must make choice. Jika administrator have second thoughts atau clicked delete button by mistake, they can click "Batal". Popup close dan no action taken. Table remains unchanged dan student data safe.

Jika administrator confirm dengan clicking "Ya, Hapus!", browser send DELETE request to server endpoint /admin/mental-health/{nim} dengan mahasiswa's NIM in URL. Request use DELETE HTTP method following RESTful conventions. Request include CSRF token untuk security.

Di sisi server, controller method receive delete request dan first perform authentication/authorization check. System verify that: Request coming from authenticated admin session (via AdminAuth middleware), CSRF token valid (automatic Laravel CSRF protection), dan Admin has permission untuk delete data (future enhancement could add role-based permissions).

If authorization check fail, return 403 Forbidden response dengan error message "Akses ditolak" atau redirect to login if session expired.

If authorized, system proceed dengan penghapusan process. First, system query database untuk verify student dengan NIM tersebut exist:

```sql
SELECT nim FROM data_diris WHERE nim = '{nim_to_delete}'
```

If query return no result, student already deleted atau NIM invalid. System abort penghapusan, return error response atau redirect dengan flash message "Data tidak ditemukan", dan administrator see error message on dashboard.

If student exist, system begin cascade penghapusan process. Cascade penghapusan必须 delete records from multiple tables in correct order untuk respect foreign key constraints:

**Step 1**: Delete riwayat keluhan records
```sql
DELETE FROM riwayat_keluhans WHERE nim = '{nim_to_delete}'
```

This remove all complaint history records associated dengan student.

**Step 2**: Delete jawaban details (if table exists)
```sql
DELETE FROM mental_health_jawaban_details WHERE id_hasil IN (
    SELECT id FROM hasil_kuesioners WHERE nim = '{nim_to_delete}'
)
```

This remove detailed answer records for all tests taken by student.

**Step 3**: Delete hasil kuesioner records
```sql
DELETE FROM hasil_kuesioners WHERE nim = '{nim_to_delete}'
```

This remove all test results for student.

**Step 4**: Delete data diri record
```sql
DELETE FROM data_diris WHERE nim = '{nim_to_delete}'
```

This remove personal demographic data.

**Step 5**: Delete pengguna account (if exists)
```sql
DELETE FROM para pengguna WHERE nim = '{nim_to_delete}'
```

This remove authentication account, preventing student from logging in.

Deletion order important karena foreign key constraints. Child records (yang reference parent) must be deleted before parent record. E.g., hasil_kuesioners references data_diris via nim foreign key, so hasil_kuesioners must be deleted before data_diris.

Alternatively, if database foreign keys defined dengan ON DELETE CASCADE, steps 1-4 may happen automatically when step 4 executed. But explicit penghapusan in application code provide better control dan logging.

All penghapusan queries wrapped in database transaction untuk ensure atomicity:

```php
DB::beginTransaction();
try {
    // Execute all delete queries
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    // Handle error
}
```

Transaction ensure that either ALL penghapusans succeed atau NONE succeed. Partial penghapusan (e.g., results deleted but data_diris remain) would leave database in inconsistent state. Transaction prevent that.

After successful penghapusan, system perform cleanup operations:

**Cache Invalidation**: Clear related caches
```php
Cache::forget('admin_mental_health_stats'); // Clear admin stats cache
Cache::forget('user_dashboard_' . $nim); // Clear pengguna dashboard cache if exists
```

Cache clearing necessary to ensure dashboard statistics don't show stale data that include deleted student.

**Logging**: Log penghapusan action for audit trail
```php
Log::info('Admin deleted student data', [
    'admin_id' => Auth::guard('admin')->id(),
    'student_nim' => $nim,
    'timestamp' => now()
]);
```

Audit logging important untuk accountability. If question arise later about why data deleted, log provide record of who deleted it and when.

After all cleanup done, system return success response. For AJAX request, return JSON response:

```json
{
    "success": true,
    "message": "Data berhasil dihapus"
}
```

For standard request, redirect back to dashboard dengan flash message "Data berhasil dihapus".

JavaScript handle success response dengan: Close confirmation dialog, Show success toast atau notification "Data berhasil dihapus", Remove deleted student's row from table dengan smooth fade-out animation, Update record count indicator if shown, dan Optionally refresh page untuk reflect updated statistics.

Administrator see updated dashboard dengan student no longer in table. Statistics cards update to reflect new counts. If deleted student was only one in certain category, that category count decrease by one.

From pengguna experience perspective, entire delete alur from button click to confirmation to actual penghapusan to UI update happen smoothly within seconds. Administrator get clear umpan balik at each step: confirmation before action, pemuatan indicator during penghapusan, success message after completion, dan immediate visual update of table.

Important considerations for penghapusan fitur: No undo functionality exists - penghapusan is permanent and irreversible, Deleted data cannot be recovered without database backup restore, Consideration for GDPR compliance regarding data retention policies, Potential enhancement: soft delete instead of hard delete, keeping records but marking as deleted, dan Potential enhancement: export student data before penghapusan untuk archival.

---

## 5. Analisis Pola dan Karakteristik Activity Diagram

### 5.1 Pola Umum: Authentication dan Authorization Check

Hampir semua activity diagram dalam sistem Assessment Online dimulai dengan pattern yang sama: authentication dan authorization check. Pattern ini adalah critical security measure yang memastikan bahwa hanya pengguna yang ter-authenticated dan ter-authorized yang dapat mengakses protected resources dan perform sensitive operations.

Standard alur adalah: pengguna trigger action (e.g., click link, submit form), system check authentication status via middleware, jika not authenticated redirect ke login page dengan intended URL, jika authenticated proceed dengan authorization check untuk ensure pengguna has permission untuk accessed resource atau action, dan jika authorized proceed dengan main process, jika not authorized return error atau redirect.

Pattern ini implemented consistently across all protected routes via Laravel middleware: auth middleware untuk pengguna routes, AdminAuth middleware untuk admin routes yang also include session timeout check, dan guest middleware untuk public routes yang should not be accessible when authenticated.

Consistency in authentication/authorization checking ensure security posture of application. No protected route accidentally left open without authentication check. Central middleware implementation make security enforcement andal dan maintainable.

### 5.2 Pola Caching untuk Performance Optimization

Multiple activity diagrams showcase caching pattern yang significantly improve system performance. Pattern adalah: pada first request, check if data available in cache dengan appropriate cache key, jika cache hit (data found dan not expired), retrieve data from cache dan skip expensive database queries, jika cache miss (data not found atau expired), execute database queries to fetch data, store fetched data in cache dengan TTL (Time To Live), dan return data untuk rendering.

Caching implemented dengan different TTL values based on data characteristics: pengguna dashboard cache: 5 minutes TTL (data is personal dan needs reasonable freshness), admin statistics cache: 1 minute TTL (data is global aggregation yang should be relatively current), dan search results may not be cached due to high variability of search queries.

Cache invalidation adalah equally important part of caching strategy. Activity diagrams show that cache cleared when relevant data changes: submit new test result → clear pengguna dashboard cache dan admin stats cache, delete student data → clear related caches, dan update data diri → clear pengguna dashboard cache.

Caching pattern demonstrate balance between performance dan data freshness. Without caching, every dashboard access would trigger multiple expensive database queries involving joins dan aggregations, potentially causing slow response times especially under high concurrent load. With caching, majority of requests (cache hit rate ideally 70-90%) served quickly from cache with sub-second response times.

### 5.3 Pola Validasi Berlapis

Activity diagrams consistently show multi-layer validation approach yang provide robust data integrity dan security. Validation layers are:

**Client-side validation**: JavaScript validation in browser before form submission, providing immediate umpan balik to pengguna without server bolak-balik, checking required fields filled, format constraints met (e.g., email format, numeric ranges), dan length limits respected.

**Server-side validation**: Redundant validation on server using Laravel Form Request classes, ensuring data integrity even if client-side validation bypassed (malicious pengguna or script), using same validation rules as client-side untuk consistency, dan returning detailed error messages untuk each field that fail validation.

**Business logic validation**: Additional validation at business logic layer, checking constraints that require database lookup (e.g., uniqueness, existence of related records), enforcing business rules (e.g., data diri must be complete before kuesioner), dan maintaining referential integrity.

**Database constraints**: Final validation layer at database level, using foreign key constraints to prevent orphaned records, NOT NULL constraints pada required fields, CHECK constraints untuk value ranges, dan UNIQUE constraints untuk fields that must be unique.

Multi-layer approach demonstrate defense-in-depth security principle. Even if one layer fail atau compromised, other layers provide backup protection. E.g., if malicious pengguna bypass client-side validation using browser dev tools, server-side validation catch invalid data before it reach database.

### 5.4 Pola Feedback dan User Communication

Activity diagrams consistently show emphasis on pengguna umpan balik at every step of interaction. Feedback patterns include:

**Loading indicators**: During processing that takes time (e.g., OAuth redirect, PDF generation, data export), system show pemuatan indicator atau progress bar so pengguna know something happening dan don't think page frozen.

**Success messages**: After successful operation completion (e.g., data saved, tes submitted, logout successful), system show success message (typically green) confirming action completed as expected.

**Error messages**: When operation fail (e.g., validation error, authentication failure, server error), system show clear error message explaining what went wrong dan how to fix it.

**Confirmation dialogs**: Before destructive operations (e.g., delete data), system show confirmation dialog asking pengguna to confirm intent, preventing accidental actions.

Feedback timeliness also important: immediate umpan balik for client-side actions (e.g., progress indicator update when answering question), near-immediate umpan balik for fast server actions (e.g., form submission response within seconds), dan asynchronous umpan balik for long-running operations (e.g., export job queued, will notify when complete).

Effective umpan balik contribute significantly to pengguna experience. Users feel confident that system working correctly, know what to do when error occurs, dan have opportunity to prevent mistakes before they happen.

### 5.5 Pola Cascade Operations

Beberapa activity diagram demonstrate cascade operations pattern dimana single high-level action trigger multiple related actions automatically. Primary example adalah delete operation yang cascade across multiple tables: delete student → delete complaints, delete test results, delete personal data, delete pengguna account, dan invalidate related caches.

Cascade pattern important untuk: maintaining data consistency - no orphaned records left behind, simplifying pengguna interaction - pengguna tidak need to manually perform each related penghapusan, enforcing business rules - related data yang no longer valid automatically removed, dan ensuring integrity - foreign key relationships properly maintained.

Implementation of cascade can be at application level (explicit penghapusan in sequence) atau database level (ON DELETE CASCADE constraints). Activity diagrams show both approaches used complementarily untuk redundancy dan reliability.

### 5.6 Pola Transaction dan Atomicity

Diagrams involving complex multi-step operations (especially delete dan complex updates) show transaction pattern ensuring atomicity. Pattern adalah: begin database transaction before starting multi-step operation, execute all steps within transaction, jika all steps succeed, commit transaction to persist changes, jika any step fail, rollback transaction to undo all changes, dan return appropriate success/error response to user.

Transaction pattern critical untuk maintaining database consistency. Without transactions, partial completion (some steps succeed, some fail) could leave database in inconsistent state. E.g., if delete operation deleted hasil_kuesioners but fail to delete data_diris, student would have personal data but no test results, violating expected data relationships.

Laravel's DB::transaction() method make transaction implementation straightforward, dan activity diagrams show this pattern applied consistently untuk critical operations.

---

## Kesimpulan

Activity diagram dalam sistem Assessment Online ITERA memberikan pandangan detail tentang alur kerja sistem dari berbagai proses bisnis dan teknis. Setiap diagram menunjukkan sequence of actions yang logis, decision points yang jelas, error handling yang comprehensive, dan interaction between different system components (user, browser, server, database, external services).

Diagram-diagram ini mengikuti praktik terbaiks dalam software design seperti: separation of concerns (authentication, business logic, data access separated), defense in depth (multiple validation layers, authentication + authorization), fail-safe design (validation, confirmation dialogs, transaction rollback), dan user-centric design (clear umpan balik, intuitive flow, error prevention).

Implementasi yang faithful terhadap activity diagram ini ensure bahwa sistem robust, secure, performant, dan ramah pengguna. Diagram serve not only as documentation but as blueprint untuk development, testing, dan maintenance aktivitas, memastikan konsistensi implementation dengan design yang telah direncanakan.

---

**Tanggal Pembuatan:** 21 November 2025
**Versi Dokumen:** 1.0
**Status:** Final
**Institut Teknologi Sumatera (ITERA)**

---

**END OF DOCUMENT**
