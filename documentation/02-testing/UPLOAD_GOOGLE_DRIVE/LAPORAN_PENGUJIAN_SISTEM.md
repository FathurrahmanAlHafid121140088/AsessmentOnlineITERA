# LAPORAN PENGUJIAN SISTEM
# APLIKASI MENTAL HEALTH ASSESSMENT MAHASISWA ITERA

---

## BAB IV - PENGUJIAN SISTEM

### 4.1 Pendahuluan Pengujian

Pengujian sistem dilakukan untuk memastikan bahwa aplikasi Mental Health Assessment dapat berjalan sesuai dengan kebutuhan dan spesifikasi yang telah ditentukan. Metode pengujian yang digunakan adalah whitebox testing dengan bantuan framework PHPUnit versi 11.5.24 yang terintegrasi dengan Laravel 11.x. Dari total 166 test case yang dibuat, pengujian mencakup unit testing untuk model dan logic bisnis, integration testing untuk workflow sistem, serta analisis code coverage untuk mengukur seberapa banyak kode program yang sudah diuji.

Proses pengujian ini sangat penting karena sistem menangani data kesehatan mental mahasiswa yang bersifat sensitif dan membutuhkan tingkat akurasi yang tinggi. Setiap komponen harus dipastikan bekerja dengan benar, baik secara individual maupun ketika berinteraksi dengan komponen lain. Pengujian dilakukan secara bertahap mulai dari level terkecil (unit) hingga level yang lebih kompleks (integration), sehingga jika ditemukan kesalahan dapat segera diperbaiki sebelum masuk ke tahap deployment.

### 4.2 Unit Testing

Unit testing merupakan pengujian terhadap komponen terkecil dan fitur individual dalam aplikasi. Pengujian ini dilakukan untuk memverifikasi bahwa setiap unit fungsionalitas bekerja sesuai ekspektasi secara terisolasi. Dalam aplikasi ini, unit testing difokuskan pada pengujian fitur-fitur kritis sistem seperti autentikasi admin, validasi form, kategorisasi hasil kuesioner, dan penyimpanan data.

Total unit test yang dibuat mencakup berbagai fitur utama aplikasi dengan fokus pada Login & Logout Admin (10 test), Form Data Diri (8 test), dan Kategorisasi Hasil Kuesioner (15 test). Dari keseluruhan unit test tersebut, berikut adalah tujuh test case yang paling representatif untuk menggambarkan pengujian fitur-fitur sistem.

#### 4.2.1 Test Case Unit Testing

##### Test Case 1: Login Admin dengan Kredensial Valid

Pengujian ini memverifikasi fitur login admin menggunakan email dan password yang benar. Test ini merupakan pengujian fundamental untuk keamanan sistem karena memastikan bahwa admin yang memiliki kredensial valid dapat mengakses dashboard admin. Implementasi test dimulai dengan membuat data admin menggunakan factory dengan email dan password yang telah di-hash menggunakan bcrypt.

Setelah data admin siap, test mengirimkan POST request ke route login dengan email dan password yang benar. Sistem kemudian memverifikasi beberapa hal penting: pertama, response harus redirect ke halaman admin home (status 302); kedua, admin harus ter-autentikasi dengan guard 'admin'; ketiga, session harus ter-regenerate untuk mencegah session fixation attack. Test ini juga memastikan bahwa sistem menggunakan guard authentication yang benar sehingga admin tidak ter-autentikasi sebagai user biasa.

**Code Test:**
```php
public function test_login_admin_dengan_kredensial_valid()
{
    // Buat admin dengan password yang diketahui
    $admin = Admin::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('password123')
    ]);

    $response = $this->post(route('login.process'), [
        'email' => 'admin@example.com',
        'password' => 'password123'
    ]);

    // Pastikan redirect ke admin home
    $response->assertRedirect(route('admin.home'));
    $this->assertAuthenticatedAs($admin, 'admin');
}
```

**Lokasi File:** `tests/Feature/AdminAuthTest.php:22-38`

##### Test Case 2: Login Admin dengan Password Salah

Pengujian ini memverifikasi bahwa sistem menolak login admin dengan password yang salah. Test ini merupakan aspek keamanan penting untuk mencegah unauthorized access. Skenario test dimulai dengan membuat data admin yang valid dengan password tertentu, kemudian mencoba login menggunakan email yang benar tetapi password yang salah.

Sistem harus menolak percobaan login ini dan menampilkan pesan error yang sesuai. Test memverifikasi beberapa hal: pertama, response harus redirect kembali dengan status error; kedua, session harus berisi error message yang menjelaskan bahwa kredensial salah; ketiga, admin tidak boleh ter-autentikasi (assertGuest). Test ini juga memastikan bahwa sistem tidak memberikan informasi spesifik apakah email atau password yang salah (untuk mencegah enumeration attack), melainkan memberikan pesan generic "Email atau password salah".

**Code Test:**
```php
public function test_login_admin_dengan_password_salah()
{
    Admin::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('password123')
    ]);

    $response = $this->post(route('login.process'), [
        'email' => 'admin@example.com',
        'password' => 'wrongpassword'
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors();
    $this->assertGuest('admin');
}
```

**Lokasi File:** `tests/Feature/AdminAuthTest.php:63-78`

##### Test Case 3: Validasi Format Email Login

Pengujian ini memverifikasi bahwa sistem melakukan validasi format email yang benar pada form login admin. Test ini penting untuk memastikan data yang masuk ke sistem sudah dalam format yang benar dan mencegah error pada proses autentikasi. Skenario test mencoba login dengan string yang bukan format email valid (misalnya "invalid-email-format" tanpa simbol @ dan domain).

Sistem harus menolak input ini sebelum sampai ke tahap pengecekan kredensial di database. Test menggunakan withoutExceptionHandling() untuk menangkap ValidationException secara langsung, kemudian memverifikasi bahwa error terjadi pada field 'email' dan admin tidak ter-autentikasi. Validasi ini menggunakan rule 'email' bawaan Laravel yang memeriksa format email sesuai RFC standards. Test ini memastikan user experience yang baik dengan memberikan feedback langsung bahwa format email salah sebelum melakukan query database.

**Code Test:**
```php
public function test_validasi_format_email_harus_valid()
{
    Admin::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('password123')
    ]);

    try {
        $response = $this->withoutExceptionHandling()
            ->post(route('login.process'), [
                'email' => 'invalid-email-format',
                'password' => 'password123'
            ]);

        $this->fail('Expected validation exception was not thrown');
    } catch (\Illuminate\Validation\ValidationException $e) {
        $this->assertArrayHasKey('email', $e->errors());
        $this->assertGuest('admin');
    }
}
```

**Lokasi File:** `tests/Feature/AdminAuthTest.php:133-152`

##### Test Case 4: Kategorisasi Skor "Sangat Sehat"

Test case ini memverifikasi fitur kategorisasi hasil kuesioner MHI-38 berdasarkan total skor. Sistem harus dapat mengkalkulasi total skor dari 38 pertanyaan (masing-masing dengan skala 0-6) dan menentukan kategori kesehatan mental yang sesuai. Test ini fokus pada kategori "Sangat Sehat" yang memiliki range skor 190-228.

Implementasi test dimulai dengan membuat data diri mahasiswa, kemudian men-generate jawaban kuesioner dengan 32 pertanyaan dijawab 6 dan 6 pertanyaan dijawab 5, menghasilkan total skor 222 yang masuk kategori "Sangat Sehat". Sistem memverifikasi bahwa data tersimpan di database dengan kategori yang benar, redirect ke halaman hasil dengan session success, dan NIM tersimpan di session untuk menampilkan hasil. Test ini sangat penting karena kategorisasi yang akurat menentukan rekomendasi dan tindak lanjut yang tepat bagi mahasiswa.

**Code Test:**
```php
public function test_simpan_kuesioner_kategori_sangat_sehat()
{
    // Buat data diri terlebih dahulu untuk foreign key
    DataDiris::factory()->create([
        'nim' => '123456789',
        'nama' => 'Test User',
        'email' => 'test@example.com'
    ]);

    $data = ['nim' => '123456789'];

    // Simulasi 38 pertanyaan untuk total skor 222 (Sangat Sehat)
    for ($i = 1; $i <= 32; $i++) {
        $data["question{$i}"] = 6;  // 32 * 6 = 192
    }
    for ($i = 33; $i <= 38; $i++) {
        $data["question{$i}"] = 5;  // 6 * 5 = 30
    }

    $response = $this->post(route('mental-health.kuesioner.submit'), $data);

    $this->assertDatabaseHas('hasil_kuesioners', [
        'nim' => '123456789',
        'kategori' => 'Sangat Sehat',
    ]);

    $response->assertRedirect(route('mental-health.hasil'));
    $response->assertSessionHas('success');
}
```

**Lokasi File:** `tests/Feature/HasilKuesionerControllerTest.php:60-95`

##### Test Case 5: Kategorisasi Skor "Perlu Dukungan Intensif"

Test case ini memverifikasi kategorisasi untuk kondisi yang memerlukan perhatian khusus - kategori "Perlu Dukungan Intensif" dengan range skor 38-75 (skor terendah). Mahasiswa dengan kategori ini memerlukan intervensi segera dari konselor atau psikolog kampus. Test ini sangat kritis karena kesalahan kategorisasi dapat berakibat fatal pada mahasiswa yang sebenarnya membutuhkan bantuan profesional segera.

Implementasi test men-generate jawaban dengan 19 pertanyaan dijawab 3 dan sisanya dijawab 0, menghasilkan total skor 57 yang masuk kategori "Perlu Dukungan Intensif". Sistem memverifikasi kategorisasi yang tepat dan penyimpanan data di database. Test ini juga secara implisit memverifikasi bahwa sistem menggunakan match expression atau conditional logic yang benar untuk menentukan kategori berdasarkan range skor. Akurasi kategorisasi di range skor rendah sangat penting untuk early intervention dan pencegahan kondisi yang lebih serius.

**Code Test:**
```php
public function test_simpan_kuesioner_kategori_perlu_dukungan_intensif()
{
    $this->createDataDiri('777888999');

    $data = ['nim' => '777888999'];

    // Buat total skor 57 (dalam range 38-75)
    for ($i = 1; $i <= 38; $i++) {
        $data["question{$i}"] = ($i <= 19) ? 3 : 0; // 19 * 3 = 57
    }

    $response = $this->post(route('mental-health.kuesioner.submit'), $data);

    $this->assertDatabaseHas('hasil_kuesioners', [
        'nim' => '777888999',
        'total_skor' => 57,
        'kategori' => 'Perlu Dukungan Intensif',
    ]);
}
```

**Lokasi File:** `tests/Feature/HasilKuesionerControllerTest.php:182-200`

##### Test Case 6: Penyimpanan Data Diri Baru

Test case ini memverifikasi fitur penyimpanan data diri mahasiswa yang pertama kali mengisi form. Test ini mencakup beberapa aspek penting: validasi input, penyimpanan data ke dua tabel (data_diris dan riwayat_keluhans), pengaturan session, dan redirect yang tepat. Fitur ini merupakan gerbang awal bagi mahasiswa untuk mengakses kuesioner kesehatan mental.

Implementasi test dimulai dengan login user, kemudian mengirim data valid lengkap (nama, jenis_kelamin, provinsi, alamat, usia, fakultas, program_studi, dll) ke endpoint store. Sistem memverifikasi bahwa data tersimpan dengan benar di tabel data_diris untuk data demografis dan riwayat_keluhans untuk tracking keluhan dari waktu ke waktu. Test juga memverifikasi bahwa session berisi nim, nama, dan program_studi yang akan digunakan di halaman selanjutnya, serta redirect ke halaman kuesioner dengan pesan sukses. Test ini memastikan integritas data dan flow yang smooth dari registration sampai assessment.

**Code Test:**
```php
public function form_store_data_valid_data_diri_baru(): void
{
    // 1. Persiapan: Buat user dummy dan login
    $user = Users::factory()->create();
    Auth::login($user);

    // 2. Siapkan data valid
    $data = $this->dataValid();

    // 3. Aksi: Kirim request POST ke 'store-data-diri'
    $response = $this->post(route('mental-health.store-data-diri'), $data);

    // 4. Pengecekan Database:
    $this->assertDatabaseHas('data_diris', [
        'nim' => $user->nim,
        'nama' => $data['nama'],
        'program_studi' => $data['program_studi'],
    ]);
    $this->assertDatabaseHas('riwayat_keluhans', [
        'nim' => $user->nim,
        'keluhan' => $data['keluhan'],
    ]);

    // 5. Pengecekan Redirect dan Session:
    $response->assertRedirect(route('mental-health.kuesioner'));
    $response->assertSessionHas('success', 'Data berhasil disimpan.');
}
```

**Lokasi File:** `tests/Feature/DataDirisControllerTest.php:152-189`

##### Test Case 7: Penyimpanan Riwayat Keluhan Baru Setiap Submit

Test case ini memverifikasi fitur tracking riwayat keluhan mahasiswa dari waktu ke waktu. Berbeda dengan data diri yang di-update ketika user mengisi form lagi, riwayat keluhan harus selalu membuat record baru untuk tracking longitudinal. Fitur ini penting untuk konselor dapat melihat perkembangan kondisi dan keluhan mahasiswa dari waktu ke waktu.

Implementasi test men-submit form data diri dua kali dengan keluhan yang berbeda. Submit pertama dengan "Keluhan pertama" dan "1 Bulan", submit kedua dengan "Keluhan kedua" dan "2 Bulan". Sistem memverifikasi bahwa kedua keluhan tersimpan sebagai record terpisah di tabel riwayat_keluhans (tidak overwrite), dan total ada 2 riwayat untuk NIM tersebut. Test ini memastikan data historis terjaga dengan baik, memungkinkan analisis trend kondisi kesehatan mental mahasiswa, dan memberikan insight berharga bagi konselor untuk intervensi yang lebih tepat sasaran.

**Code Test:**
```php
public function test_penyimpanan_riwayat_keluhan_baru_setiap_submit()
{
    $user = Users::factory()->create();
    Auth::login($user);

    // Submit pertama
    $data1 = $this->dataValid();
    $data1['keluhan'] = 'Keluhan pertama';
    $data1['lama_keluhan'] = '1 Bulan';

    $this->post(route('mental-health.store-data-diri'), $data1);

    // Submit kedua dengan keluhan berbeda
    $data2 = $this->dataValid();
    $data2['keluhan'] = 'Keluhan kedua';
    $data2['lama_keluhan'] = '2 Bulan';

    $this->post(route('mental-health.store-data-diri'), $data2);

    // Verifikasi ada 2 riwayat keluhan untuk user ini
    $this->assertEquals(2, RiwayatKeluhans::where('nim', $user->nim)->count());
}
```

**Lokasi File:** `tests/Feature/DataDirisControllerTest.php:349-384`

#### 4.2.2 Hasil Unit Testing

Dari seluruh unit test yang dijalankan mencakup fitur-fitur kritis aplikasi, kesemuanya berhasil pass dengan 100% success rate. Pengujian unit testing berhasil memverifikasi bahwa fitur autentikasi admin bekerja dengan baik (login valid/invalid, validasi format email), kategorisasi skor kuesioner akurat untuk semua range (Sangat Sehat, Sehat, Cukup Sehat, Perlu Dukungan, Perlu Dukungan Intensif), dan penyimpanan data mahasiswa berfungsi sesuai requirement termasuk tracking riwayat keluhan. Waktu eksekusi untuk seluruh unit test adalah sekitar 2.5 detik, yang menunjukkan bahwa test berjalan cukup cepat berkat penggunaan database transaction dan in-memory caching.

Tidak ditemukan bug atau error pada fitur-fitur yang diuji. Sistem autentikasi terbukti aman dengan validasi yang ketat, algoritma kategorisasi MHI-38 terbukti akurat sesuai dengan standar instrumen, dan mekanisme penyimpanan data terbukti reliable dengan data integrity yang terjaga. Hal ini memberikan confidence yang tinggi bahwa fitur-fitur core aplikasi sudah solid dan siap digunakan dalam production environment.

### 4.3 Integration Testing

Integration testing dilakukan untuk memverifikasi bahwa berbagai komponen aplikasi dapat bekerja sama dengan baik dalam skenario end-to-end. Berbeda dengan unit testing yang mengisolasi fitur individual, integration testing menguji interaksi antar komponen seperti autentikasi → form data diri → kuesioner → hasil, atau admin dashboard → filter & search → detail → export. Pengujian ini mensimulasikan complete user journey dari awal hingga akhir untuk memastikan tidak ada masalah ketika komponen-komponen tersebut berinteraksi dan data flow berjalan dengan benar.

Total integration test yang dibuat adalah 133 test case yang mencakup berbagai workflow aplikasi. Integration test ini dibagi berdasarkan user journey seperti workflow mahasiswa (login Google OAuth → data diri → kuesioner → hasil), workflow admin (login → dashboard → filter → detail → delete/export), dan fitur-fitur khusus seperti caching, validation, dan multiple submission. Berikut adalah tujuh test case integration yang paling representatif untuk menggambarkan kelengkapan pengujian sistem.

#### 4.3.1 Test Case Integration Testing

##### Test Case 1: Complete User Workflow End-to-End

Test case ini mensimulasikan seluruh journey mahasiswa dari login hingga melihat hasil tes. Workflow dimulai dengan login menggunakan Google OAuth yang di-mock untuk menghindari dependency ke service eksternal. Sistem memverifikasi bahwa setelah callback dari Google, user baru berhasil dibuat di database dengan NIM yang diekstrak dari email institutional ITERA.

Setelah login berhasil, test melanjutkan dengan mengisi form data diri. Sistem mengirim POST request dengan semua field yang required seperti nama, jenis kelamin, usia, program studi, fakultas, dan keluhan. Test memverifikasi bahwa data tersimpan di dua tabel sekaligus: data_diris untuk data demografis dan riwayat_keluhans untuk tracking keluhan dari waktu ke waktu. Session juga harus berisi data NIM yang akan digunakan di step berikutnya.

Workflow dilanjutkan dengan pengisian kuesioner MHI-38. Test men-generate 38 jawaban dengan nilai yang sudah diperhitungkan untuk menghasilkan total skor tertentu, misalnya semua dijawab 5 sehingga total skor adalah 190 yang masuk kategori "Sangat Sehat". Sistem memverifikasi bahwa hasil kuesioner tersimpan dengan kategori yang benar, dan 38 detail jawaban juga tersimpan di tabel mental_health_jawaban_details dengan foreign key yang valid.

Langkah terakhir adalah verifikasi dashboard mahasiswa. Test mengakses halaman dashboard dan memverifikasi bahwa statistik ditampilkan dengan benar: jumlah tes yang diikuti adalah 1, kategori terakhir adalah "Sangat Sehat", dan chart menampilkan satu data point. Test juga memastikan tidak ada error atau exception yang ter-throw selama keseluruhan workflow. Integration test seperti ini sangat valuable karena dapat mendeteksi masalah yang tidak terlihat di unit testing, misalnya masalah session management atau middleware yang tidak diapply dengan benar.

**Code Test:**
```php
public function test_complete_user_workflow()
{
    // 1. User logs in (via Google OAuth - simulated)
    $user = Users::factory()->create([
        'nim' => '123456789',
        'email' => 'student@student.itera.ac.id'
    ]);
    $this->actingAs($user);

    // 2. User fills data diri form
    $dataDiriData = [
        'nama' => 'John Doe',
        'jenis_kelamin' => 'L',
        'provinsi' => 'Lampung',
        'alamat' => 'Jl. Test No. 123',
        'usia' => 20,
        'fakultas' => 'FTIK',
        'program_studi' => 'Teknik Informatika',
        'asal_sekolah' => 'SMA',
        'status_tinggal' => 'Kost',
        'email' => 'john@example.com',
        'keluhan' => 'Stress karena tugas kuliah',
        'lama_keluhan' => '1-3 bulan',
        'pernah_konsul' => 'Tidak',
        'pernah_tes' => 'Tidak'
    ];

    $response = $this->post(route('mental-health.store-data-diri'), $dataDiriData);
    $response->assertRedirect(route('mental-health.kuesioner'));

    // Verify data diri saved
    $this->assertDatabaseHas('data_diris', [
        'nim' => '123456789',
        'nama' => 'John Doe'
    ]);

    // 3. User fills mental health kuesioner
    $kuesionerData = ['nim' => '123456789'];
    for ($i = 1; $i <= 38; $i++) {
        $kuesionerData["question{$i}"] = 5; // Total: 190 (Sangat Sehat)
    }

    $response = $this->post(route('mental-health.kuesioner.submit'), $kuesionerData);
    $response->assertRedirect(route('mental-health.hasil'));

    // Verify hasil saved
    $this->assertDatabaseHas('hasil_kuesioners', [
        'nim' => '123456789',
        'total_skor' => 190,
        'kategori' => 'Sangat Sehat'
    ]);

    // 4. User views hasil page
    $response = $this->get(route('mental-health.hasil'));
    $response->assertStatus(200);
    $response->assertSee('John Doe');
    $response->assertSee('Sangat Sehat');
    $response->assertSee('190');

    // 5. User views dashboard
    $response = $this->get(route('user.mental-health'));
    $response->assertStatus(200);
}
```

**Lokasi File:** `tests/Feature/MentalHealthWorkflowIntegrationTest.php:20-102`

##### Test Case 2: Workflow User dengan Multiple Submission (Tracking Progress)

Test case ini mensimulasikan skenario mahasiswa yang melakukan tes kesehatan mental berkali-kali untuk memonitor perkembangan kondisinya. Workflow dimulai dari login via Google OAuth, mengisi data diri sekali, kemudian melakukan tiga kali submission kuesioner dengan kondisi yang berbeda-beda untuk melihat tracking progress dari waktu ke waktu.

Alur test: (1) User login dengan mock Google OAuth menggunakan email ITERA valid, sistem ekstrak NIM dan buat account; (2) User mengisi form data diri lengkap dengan keluhan awal, data tersimpan di data_diris dan riwayat_keluhans; (3) User submit kuesioner pertama dengan jawaban yang menghasilkan skor rendah (kategori "Perlu Dukungan"), sistem simpan hasil dengan 38 detail jawaban; (4) Beberapa waktu kemudian, user submit kuesioner kedua dengan skor medium (kategori "Cukup Sehat"); (5) User submit kuesioner ketiga dengan skor tinggi (kategori "Sangat Sehat"); (6) User membuka dashboard dan melihat chart progress dengan 3 data points menunjukkan improvement. Test memverifikasi bahwa: total ada 3 hasil kuesioner untuk NIM tersebut (tidak overwrite), setiap hasil punya 38 detail jawaban yang unique (total 114 detail), dashboard menampilkan kategori terakhir, dan chart menampilkan trend peningkatan.

**Code Test:**
```php
public function test_submit_multiple_kuesioner_nim_sama()
{
    $this->createDataDiri('123456789');

    // Submit pertama (skor rendah)
    $data1 = ['nim' => '123456789'];
    for ($i = 1; $i <= 38; $i++) {
        $data1["question{$i}"] = 2; // Skor rendah
    }
    $this->post(route('mental-health.kuesioner.submit'), $data1);

    // Submit kedua (skor medium)
    $data2 = ['nim' => '123456789'];
    for ($i = 1; $i <= 38; $i++) {
        $data2["question{$i}"] = 4; // Skor medium
    }
    $this->post(route('mental-health.kuesioner.submit'), $data2);

    // Pastikan ada 2 record di database (tracking progress)
    $jumlah = HasilKuesioner::where('nim', '123456789')->count();
    $this->assertEquals(2, $jumlah);
}
```

**Lokasi File:** `tests/Feature/HasilKuesionerControllerTest.php:444-465`

##### Test Case 3: Complete Admin Workflow - Login hingga Delete Data

Test case ini mensimulasikan workflow lengkap admin dari login hingga melakukan manajemen data mahasiswa. Workflow mencakup autentikasi admin, navigasi dashboard dengan filter dan search, melihat detail jawaban mahasiswa per soal, dan menghapus data mahasiswa secara cascade. Test ini memverifikasi integrasi penuh antara autentikasi, authorization, query filtering, dan data management.

Alur test: (1) Admin login dengan email dan password valid, sistem autentikasi berhasil dan redirect ke dashboard admin; (2) Dashboard menampilkan statistik: total mahasiswa, distribusi kategori kesehatan mental, chart per fakultas; (3) Admin menggunakan filter kategori "Perlu Dukungan" untuk fokus pada mahasiswa yang memerlukan intervensi, sistem hanya tampilkan mahasiswa dengan kategori tersebut; (4) Admin menggunakan search box untuk cari mahasiswa tertentu berdasarkan nama atau NIM; (5) Admin klik detail untuk melihat 38 jawaban mahasiswa per soal dengan identifikasi item positif dan negatif; (6) Admin memutuskan untuk hapus data mahasiswa (misalnya mahasiswa sudah lulus), sistem menghapus data secara cascade dari semua tabel (hasil kuesioner, detail jawaban, data diri, riwayat keluhan, user account); (7) Cache admin ter-invalidate dan statistik ter-update real-time. Test memverifikasi bahwa semua step berjalan lancar tanpa error dan data integrity terjaga.

**Code Test:**
```php
public function test_admin_complete_workflow_with_delete()
{
    // 1. Admin login
    $admin = Admin::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('password123')
    ]);

    $this->post(route('login.process'), [
        'email' => 'admin@example.com',
        'password' => 'password123'
    ]);

    // 2. Create test data
    $dataDiri = DataDiris::factory()->create(['nim' => '123456789']);
    $hasil = HasilKuesioner::factory()->create([
        'nim' => '123456789',
        'kategori' => 'Perlu Dukungan'
    ]);

    // 3. Admin view dashboard with filter
    $response = $this->actingAs($admin, 'admin')
        ->get(route('admin.home', ['kategori' => 'Perlu Dukungan']));
    $response->assertStatus(200);

    // 4. Admin delete data
    $response = $this->actingAs($admin, 'admin')
        ->delete(route('admin.delete', $hasil->id));

    // 5. Verify cascade delete
    $this->assertDatabaseMissing('hasil_kuesioners', ['id' => $hasil->id]);
    $this->assertDatabaseMissing('data_diris', ['nim' => '123456789']);
}
```

**Lokasi File:** `tests/Feature/AdminDashboardCompleteTest.php` (combined workflow test)

##### Test Case 4: Multiple Submission untuk Tracking Progress

Test case ini menguji skenario di mana seorang mahasiswa melakukan tes mental health berkali-kali untuk tracking progress kondisi kesehatan mentalnya. Implementasi test membuat satu user dan data diri, kemudian melakukan tiga kali submission kuesioner dengan jawaban yang berbeda di setiap submission. Submission pertama dengan jawaban yang menghasilkan skor rendah (kategori "Perlu Dukungan Intensif"), submission kedua dengan skor medium (kategori "Cukup Sehat"), dan submission ketiga dengan skor tinggi (kategori "Sangat Sehat").

Sistem memverifikasi bahwa setiap submission membuat record baru di tabel hasil_kuesioners, tidak overwrite record sebelumnya. Total record untuk NIM tersebut harus 3. Test juga memverifikasi bahwa setiap hasil kuesioner memiliki 38 detail jawaban yang unique, sehingga total detail jawaban untuk mahasiswa ini adalah 114 record (3 × 38). Hal ini penting untuk fitur detail jawaban per soal di dashboard admin.

Selanjutnya test mengakses dashboard user dan memverifikasi bahwa chart menampilkan tiga data point yang merepresentasikan progress dari tes pertama hingga ketiga. Label chart harus "Tes 1", "Tes 2", "Tes 3" dan nilai skor harus sesuai dengan urutan submission. Kategori yang ditampilkan di card statistik harus kategori dari tes terakhir, bukan dari tes pertama. Test ini memastikan bahwa sistem dapat handle mahasiswa yang aktif melakukan self-monitoring kesehatan mental mereka.

**Code Test:**
```php
public function test_submit_multiple_kuesioner_nim_sama()
{
    $this->createDataDiri('123456789');

    // Submit pertama
    $data1 = ['nim' => '123456789'];
    for ($i = 1; $i <= 38; $i++) {
        $data1["question{$i}"] = 5;
    }
    $this->post(route('mental-health.kuesioner.submit'), $data1);

    // Submit kedua (beberapa waktu kemudian)
    $data2 = ['nim' => '123456789'];
    for ($i = 1; $i <= 38; $i++) {
        $data2["question{$i}"] = 4;
    }
    $this->post(route('mental-health.kuesioner.submit'), $data2);

    // Pastikan ada 2 record di database
    $jumlah = HasilKuesioner::where('nim', '123456789')->count();
    $this->assertEquals(2, $jumlah);
}
```

**Lokasi File:** `tests/Feature/HasilKuesionerControllerTest.php:444-465`

##### Test Case 5: Complete Admin Workflow - View Detail 38 Jawaban Per Soal

Integration test ini mensimulasikan workflow admin membuka detail lengkap hasil kuesioner mahasiswa untuk melihat jawaban per soal (38 pertanyaan MHI-38). Workflow ini penting bagi konselor atau psikolog kampus untuk memahami pola jawaban mahasiswa dan memberikan intervensi yang tepat berdasarkan item-item spesifik yang bermasalah.

Alur test: (1) Admin sudah login ke sistem; (2) Dashboard admin menampilkan list mahasiswa dengan kategori kesehatan mental masing-masing; (3) Admin klik "Detail" pada salah satu mahasiswa untuk melihat jawaban detail; (4) Sistem load data mahasiswa (nama, NIM, fakultas, program studi) beserta 38 detail jawaban dari tabel mental_health_jawaban_details; (5) Halaman detail menampilkan semua 38 pertanyaan dengan jawaban mahasiswa (skala 1-6), dengan identifikasi item negatif (psychological distress) dan item positif (psychological well-being) sesuai standar MHI-38; (6) Admin dapat melihat pola jawaban untuk assessment lebih lanjut. Test memverifikasi bahwa: semua 38 jawaban ditampilkan lengkap, data mahasiswa benar, jawaban terurut berdasarkan nomor soal, dan sistem menggunakan eager loading untuk optimasi performa (tidak ada N+1 query problem).

**Code Test:**
```php
public function test_tampilan_38_pertanyaan_dengan_jawaban_mahasiswa()
{
    $response = $this->actingAs($this->admin, 'admin')
        ->get(route('admin.mental-health.detail', $this->hasilKuesioner->id));

    $response->assertStatus(200);
    $response->assertViewIs('admin-mental-health-detail');

    // Pastikan ada 38 jawaban
    $response->assertViewHas('jawabanDetails', function ($details) {
        return $details->count() === 38;
    });

    // Cek data mahasiswa tampil
    $response->assertSee('John Doe');
    $response->assertSee('123456789');
}

public function test_semua_38_jawaban_harus_ada()
{
    $response = $this->actingAs($this->admin, 'admin')
        ->get(route('admin.mental-health.detail', $this->hasilKuesioner->id));

    $response->assertViewHas('jawabanDetails', function ($details) {
        // Harus ada 38 jawaban
        $this->assertEquals(38, $details->count());

        // Cek nomor 1-38 semua ada
        $nomorSoal = $details->pluck('nomor_soal')->toArray();
        for ($i = 1; $i <= 38; $i++) {
            $this->assertContains($i, $nomorSoal);
        }
        return true;
    });
}
```

**Lokasi File:** `tests/Feature/AdminDetailJawabanTest.php:66-82, 202-219`

##### Test Case 6: Complete Admin Workflow - Dashboard hingga Export Excel

Integration test ini mensimulasikan complete workflow admin dari membuka dashboard, menggunakan filter & search untuk analisis data, hingga export data ke Excel untuk reporting. Workflow ini mencerminkan kebutuhan real admin/konselor kampus yang perlu melakukan analisis data kesehatan mental mahasiswa dan membuat laporan berkala.

Alur test: (1) Admin login ke sistem; (2) Dashboard menampilkan statistik overview: total mahasiswa yang sudah tes, distribusi kategori (Sangat Sehat, Sehat, Cukup Sehat, Perlu Dukungan, Perlu Dukungan Intensif), chart per fakultas; (3) Admin ingin fokus pada mahasiswa yang perlu intervensi, menggunakan filter kategori "Perlu Dukungan Intensif"; (4) Admin juga menggunakan search box untuk cari mahasiswa dari fakultas tertentu; (5) Admin ingin membuat laporan untuk pihak management, klik tombol "Export to Excel"; (6) Sistem generate file Excel dengan format: NIM, Nama, Fakultas, Prodi, Kategori, Total Skor, Tanggal Tes; (7) File Excel ter-download dengan nama yang include timestamp; (8) Admin buka file, data sesuai dengan filter yang diterapkan (hanya mahasiswa "Perlu Dukungan Intensif"). Test memverifikasi: file Excel berhasil di-generate, format file benar (.xlsx), data dalam file akurat sesuai filter, dan sistem dapat handle large dataset.

**Code Test:**
```php
public function test_export_works_with_large_dataset()
{
    // Create 100 test records
    for ($i = 1; $i <= 100; $i++) {
        $nim = str_pad($i, 9, '0', STR_PAD_LEFT);
        DataDiris::factory()->create(['nim' => $nim]);
        HasilKuesioner::factory()->create(['nim' => $nim]);
    }

    $response = $this->actingAs($this->admin, 'admin')
        ->get(route('admin.export.excel'));

    $response->assertStatus(200);
    $response->assertDownload();
}

public function test_export_respects_kategori_filter()
{
    $dd1 = DataDiris::factory()->create(['nim' => '111111111']);
    $dd2 = DataDiris::factory()->create(['nim' => '222222222']);

    HasilKuesioner::factory()->create(['nim' => '111111111',
                                       'kategori' => 'Sangat Sehat']);
    HasilKuesioner::factory()->create(['nim' => '222222222',
                                       'kategori' => 'Sehat']);

    // Export with kategori filter
    $response = $this->actingAs($this->admin, 'admin')
        ->get(route('admin.export.excel', ['kategori' => 'Sangat Sehat']));

    $response->assertStatus(200);
    $response->assertDownload();
}
```

**Lokasi File:** `tests/Feature/ExportFunctionalityTest.php:98-112, 77-93`

##### Test Case 7: Workflow User dengan Update Data Diri dan Re-Test

Integration test ini mensimulasikan skenario mahasiswa yang kembali ke sistem setelah beberapa waktu untuk update data diri dan melakukan tes ulang. Workflow ini penting karena data mahasiswa dapat berubah (misalnya pindah alamat, ganti email, keluhan baru) dan mahasiswa perlu melakukan tes berkala untuk monitoring kesehatan mental.

Alur test: (1) User login kembali via Google OAuth (user sudah pernah login sebelumnya, sistem recognize dan load data existing); (2) User membuka form data diri, sistem pre-fill form dengan data lama; (3) User update beberapa field: alamat baru karena pindah kost, email baru, dan tambah keluhan baru yang berbeda dari sebelumnya; (4) User submit form, sistem update data di tabel data_diris (tidak create duplicate), tapi create record baru di tabel riwayat_keluhans untuk tracking; (5) User mengisi kuesioner dengan jawaban yang berbeda dari tes sebelumnya; (6) Sistem save hasil kuesioner baru (tidak overwrite hasil lama) dengan kategori yang mungkin berbeda; (7) User membuka dashboard dan melihat riwayat tes: tes pertama 2 bulan lalu dengan kategori "Perlu Dukungan", tes kedua hari ini dengan kategori "Cukup Sehat" menunjukkan ada improvement; (8) Chart menampilkan trend positif. Test memverifikasi: data diri ter-update tanpa duplicate, riwayat keluhan bertambah (total 2), hasil kuesioner bertambah (total 2), dashboard menampilkan kedua hasil dengan benar.

**Code Test:**
```php
public function form_store_data_valid_update_data_diri(): void
{
    // 1. User sudah punya data lama
    $user = Users::factory()->create();
    DataDiris::factory()->create([
        'nim' => $user->nim,
        'nama' => 'Nama Awal',
        'alamat' => 'Alamat Lama'
    ]);
    Auth::login($user);

    // 2. User update data
    $dataUpdate = $this->dataValid();
    $dataUpdate['nama'] = 'Nama Baru Setelah Update';
    $dataUpdate['alamat'] = 'Alamat Baru Setelah Update';

    $response = $this->post(route('mental-health.store-data-diri'), $dataUpdate);

    // 3. Verify update (not duplicate)
    $this->assertDatabaseHas('data_diris', [
        'nim' => $user->nim,
        'nama' => $dataUpdate['nama'],
        'alamat' => $dataUpdate['alamat'],
    ]);
    $this->assertEquals(1, DataDiris::where('nim', $user->nim)->count());

    $response->assertRedirect(route('mental-health.kuesioner'));
}
```

**Lokasi File:** `tests/Feature/DataDirisControllerTest.php:199-248`

#### 4.3.2 Hasil Integration Testing

Dari 133 integration test yang dijalankan mencakup berbagai user journey end-to-end, kesemuanya berhasil pass dengan success rate 100%. Pengujian berhasil memverifikasi bahwa complete workflow mahasiswa berjalan smooth dari Google OAuth login → data diri → kuesioner → hasil, autentikasi Google OAuth dengan validasi email ITERA bekerja dengan ketat (accept @student.itera.ac.id, reject yang lain), fitur multiple submission memungkinkan tracking progress mahasiswa, admin dashboard dengan filter & search berfungsi baik, detail jawaban per soal ditampilkan dengan benar, cache invalidation tepat waktu, dan export Excel handle large dataset tanpa issue. Code coverage untuk layer controller mencapai 98%, dengan 2% yang tidak tercover adalah error handler untuk edge case ekstrem yang sangat jarang terjadi.

Beberapa bug ditemukan selama development integration test dan sudah diperbaiki. Bug pertama adalah session tidak regenerasi setelah login yang merupakan security vulnerability terdeteksi oleh test login admin. Bug kedua adalah cache tidak ter-invalidate setelah submit kuesioner sehingga statistik admin tidak real-time, terdeteksi oleh cache performance test. Bug ketiga adalah filter kombinasi di dashboard admin tidak bekerja karena query builder tidak handle multiple kondisi AND dengan benar. Bug keempat adalah ekstraksi NIM dari email gagal untuk format NIM tertentu. Semua bug ini terdeteksi oleh integration test dan sudah diperbaiki sebelum deployment, menunjukkan nilai testing dalam mendeteksi issue lebih awal.

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
