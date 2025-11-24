# Dokumentasi Rancangan Pengujian Whitebox Testing - Mental Health

## Deskripsi

Dokumen ini berisi rancangan pengujian whitebox testing menggunakan PHPUnit untuk fitur Mental Health pada sistem Assessment Online. Pengujian difokuskan pada validasi logika internal, alur kontrol, dan integritas data.

---

## Tabel Rancangan Pengujian Whitebox Testing

### 1. Fitur Login & Autentikasi

| Halaman    | Kode Pengujian | Skenario Pengujian                                                | Status Test |
| ---------- | -------------- | ----------------------------------------------------------------- | ----------- |
| Login      | Pf-001         | Menguji login admin dengan email dan password valid               | ✅ PASS     |
| Login      | Pf-002         | Menguji login admin dengan email tidak valid                      | ✅ PASS     |
| Login      | Pf-003         | Menguji login admin dengan password salah                         | ✅ PASS     |
| Login      | Pf-004         | Menguji login admin dengan field email kosong                     | ✅ PASS     |
| Login      | Pf-005         | Menguji login admin dengan field password kosong                  | ✅ PASS     |
| Login      | Pf-006         | Menguji validasi format email harus valid                         | ✅ PASS     |
| Login      | Pf-007         | Menguji regenerasi session setelah login berhasil                 | ✅ PASS     |
| Login      | Pf-008         | Menguji redirect ke halaman admin setelah login berhasil          | ✅ PASS     |
| Login      | Pf-009         | Menguji pesan error "Email atau password salah!" saat gagal login | ✅ PASS     |
| Login      | Pf-010         | Menguji Google OAuth redirect ke halaman Google                   | ✅ PASS     |
| Login      | Pf-011         | Menguji Google OAuth callback dengan email mahasiswa ITERA valid  | ✅ PASS     |
| Login      | Pf-012         | Menguji Google OAuth callback dengan email non-ITERA (ditolak)    | ✅ PASS     |
| Login      | Pf-013         | Menguji ekstraksi NIM dari email mahasiswa ITERA                  | ✅ PASS     |
| Login      | Pf-014         | Menguji pembuatan user baru melalui Google OAuth                  | ✅ PASS     |
| Login      | Pf-015         | Menguji update user yang sudah ada melalui Google OAuth           | ✅ PASS     |
| Logout     | Pf-016         | Menguji logout admin dengan invalidasi session                    | ✅ PASS     |
| Logout     | Pf-017         | Menguji flush session data setelah logout                         | ✅ PASS     |
| Logout     | Pf-018         | Menguji redirect ke halaman login setelah logout                  | ✅ PASS     |
| Middleware | Pf-019         | Menguji regenerate token setelah logout                           | ✅ PASS     |
| Middleware | Pf-020         | Menguji akses route setelah logout (unauthorized)                 | ✅ PASS     |
| Middleware | Pf-021         | Menguji guest middleware redirect user yang sudah login           | ✅ PASS     |
| Middleware | Pf-022         | Menguji AdminAuth middleware untuk route admin                    | ✅ PASS     |

### 2. Fitur Data Diri (Personal Data Form)

| Halaman       | Kode Pengujian | Skenario Pengujian                                                    | Status Test |
| ------------- | -------------- | --------------------------------------------------------------------- | ----------- |
| Isi Data Diri | Pf-023         | Menguji penyimpanan data diri mahasiswa baru dengan semua field valid | ✅ PASS     |
| Isi Data Diri | Pf-024         | Menguji update data diri mahasiswa yang sudah ada (updateOrCreate)    | ✅ PASS     |
| Isi Data Diri | Pf-025         | Menguji validasi field required pada data diri                        | ✅ PASS     |
| Isi Data Diri | Pf-026         | Menguji validasi format email pada data diri                          | ✅ PASS     |
| Isi Data Diri | Pf-027         | Menguji validasi usia minimum dan maksimum                            | ✅ PASS     |
| Isi Data Diri | Pf-028         | Menguji penyimpanan data dengan NIM yang sesuai user login            | ✅ PASS     |
| Isi Data Diri | Pf-029         | Menguji penyimpanan riwayat keluhan baru setiap submit                | ✅ PASS     |
| Isi Data Diri | Pf-030         | Menguji pengaturan session (nim, nama, program_studi) setelah submit  | ✅ PASS     |
| Isi Data Diri | Pf-031         | Menguji redirect ke halaman kuesioner setelah berhasil submit         | ✅ PASS     |
| Isi Data Diri | Pf-032         | Menguji pesan sukses setelah berhasil menyimpan data diri             | ✅ PASS     |

### 3. Fitur Kuesioner MHI-38

| Halaman   | Kode Pengujian | Skenario Pengujian                                                                   | Status Test |
| --------- | -------------- | ------------------------------------------------------------------------------------ | ----------- |
| Kuesioner | Pf-033         | Menguji penyimpanan jawaban 38 pertanyaan kuesioner                                  | ✅ PASS     |
| Kuesioner | Pf-034         | Menguji perhitungan total skor dari 38 jawaban                                       | ✅ PASS     |
| Kuesioner | Pf-035         | Menguji kategorisasi "Sangat Sehat" untuk skor 190-226                              | ✅ PASS     |
| Kuesioner | Pf-036         | Menguji kategorisasi "Sehat" untuk skor 152-189                                      | ✅ PASS     |
| Kuesioner | Pf-037         | Menguji kategorisasi "Cukup Sehat" untuk skor 114-151                               | ✅ PASS     |
| Kuesioner | Pf-038         | Menguji kategorisasi "Perlu Dukungan" untuk skor 76-113                             | ✅ PASS     |
| Kuesioner | Pf-039         | Menguji kategorisasi "Perlu Dukungan Intensif" untuk skor 38-75                     | ✅ PASS     |
| Kuesioner | Pf-040         | Menguji validasi setiap pertanyaan harus diisi (required)                            | ✅ PASS     |
| Kuesioner | Pf-041         | Menguji validasi nilai jawaban harus 1-6                                             | ✅ PASS     |
| Kuesioner | Pf-042         | Menguji penyimpanan detail jawaban per nomor soal ke tabel MentalHealthJawabanDetail | ✅ PASS     |
| Kuesioner | Pf-043         | Menguji redirect ke halaman hasil setelah submit kuesioner                           | ✅ PASS     |
| Kuesioner | Pf-044         | Menguji penyimpanan NIM session untuk tracking hasil                                 | ✅ PASS     |

### 4. Fitur Hasil Tes

| Halaman   | Kode Pengujian | Skenario Pengujian                                          | Status Test |
| --------- | -------------- | ----------------------------------------------------------- | ----------- |
| Hasil Tes | Pf-045         | Menguji tampilan hasil tes terbaru user yang login         | ✅ PASS     |
| Hasil Tes | Pf-046         | Menguji relasi hasil kuesioner dengan data diri mahasiswa  | ✅ PASS     |
| Hasil Tes | Pf-047         | Menguji tampilan total skor dan kategori kesehatan mental  | ✅ PASS     |
| Hasil Tes | Pf-048         | Menguji akses halaman hasil tanpa login (redirect ke login)| ✅ PASS     |

### 5. Fitur Dashboard User

| Halaman        | Kode Pengujian | Skenario Pengujian                                        | Status Test |
| -------------- | -------------- | --------------------------------------------------------- | ----------- |
| Dashboard User | Pf-049         | Menguji tampilan riwayat semua tes user dengan pagination| ✅ PASS     |
| Dashboard User | Pf-050         | Menguji perhitungan jumlah tes yang diikuti user         | ✅ PASS     |
| Dashboard User | Pf-051         | Menguji tampilan kategori terakhir user                  | ✅ PASS     |
| Dashboard User | Pf-052         | Menguji data chart (tanggal dan skor) untuk visualisasi  | ✅ PASS     |
| Dashboard User | Pf-053         | Menguji filter riwayat tes berdasarkan tanggal           | ✅ PASS     |
| Dashboard User | Pf-054         | Menguji user baru dengan riwayat tes kosong              | ✅ PASS     |

### 6. Fitur Admin Dashboard

| Halaman         | Kode Pengujian | Skenario Pengujian                                             | Status Test |
| --------------- | -------------- | -------------------------------------------------------------- | ----------- |
| Admin Dashboard | Pf-055         | Menguji tampilan daftar hasil tes dengan pagination            | ✅ PASS     |
| Admin Dashboard | Pf-056         | Menguji pencarian berdasarkan nama mahasiswa                   | ✅ PASS     |
| Admin Dashboard | Pf-057         | Menguji pencarian berdasarkan NIM                              | ✅ PASS     |
| Admin Dashboard | Pf-058         | Menguji filter berdasarkan kategori kesehatan mental           | ✅ PASS     |
| Admin Dashboard | Pf-059         | Menguji sorting berdasarkan nama (ASC/DESC)                    | ✅ PASS     |
| Admin Dashboard | Pf-060         | Menguji sorting berdasarkan total skor                         | ✅ PASS     |
| Admin Dashboard | Pf-061         | Menguji sorting berdasarkan tanggal tes                        | ✅ PASS     |
| Admin Dashboard | Pf-062         | Menguji statistik total user dan distribusi gender             | ✅ PASS     |
| Admin Dashboard | Pf-063         | Menguji statistik distribusi asal sekolah (SMA/SMK/Pesantren)  | ✅ PASS     |
| Admin Dashboard | Pf-064         | Menguji statistik distribusi per fakultas dengan persentase    | ✅ PASS     |
| Admin Dashboard | Pf-065         | Menguji statistik jumlah per kategori kesehatan                | ✅ PASS     |
| Admin Dashboard | Pf-066         | Menguji cache statistics untuk performa                        | ✅ PASS     |
| Admin Dashboard | Pf-067         | Menguji akses admin dashboard tanpa login admin (unauthorized) | ✅ PASS     |

### 7. Fitur Detail Jawaban Admin & Cetak PDF

| Halaman        | Kode Pengujian | Skenario Pengujian                                                  | Status Test |
| -------------- | -------------- | ------------------------------------------------------------------- | ----------- |
| Detail Jawaban | Pf-068         | Menguji tampilan 38 pertanyaan dengan jawaban mahasiswa             | ✅ PASS     |
| Detail Jawaban | Pf-069         | Menguji identifikasi item negatif (psychological distress)          | ✅ PASS     |
| Detail Jawaban | Pf-070         | Menguji identifikasi item positif (psychological well-being)        | ✅ PASS     |
| Detail Jawaban | Pf-071         | Menguji tampilan informasi data diri lengkap mahasiswa              | ✅ PASS     |
| Detail Jawaban | Pf-072         | Menguji akses detail dengan ID tidak valid (404)                    | ✅ PASS     |
| Cetak PDF      | Pf-073         | Menguji generate PDF detail jawaban dengan data valid               | ✅ PASS     |
| Cetak PDF      | Pf-074         | Menguji konten PDF berisi header, info mahasiswa, dan tabel jawaban | ✅ PASS     |
| Cetak PDF      | Pf-075         | Menguji watermark "Generated by ANALOGY - ITERA" pada PDF           | ✅ PASS     |
| Cetak PDF      | Pf-076         | Menguji format tabel PDF dengan 38 pertanyaan lengkap               | ✅ PASS     |

### 8. Fitur Hapus Data

| Halaman    | Kode Pengujian | Skenario Pengujian                                        | Status Test |
| ---------- | -------------- | --------------------------------------------------------- | ----------- |
| Hapus Data | Pf-077         | Menguji akses fitur hapus hanya untuk admin               | ✅ PASS     |
| Hapus Data | Pf-078         | Menguji konfirmasi sebelum menghapus data                 | ✅ PASS     |
| Hapus Data | Pf-079         | Menguji pesan sukses setelah menghapus data               | ✅ PASS     |
| Hapus Data | Pf-080         | Menguji penghapusan hasil kuesioner                       | ✅ PASS     |
| Hapus Data | Pf-081         | Menguji cascade delete ke tabel jawaban detail            | ✅ PASS     |
| Hapus Data | Pf-082         | Menguji cascade delete ke tabel riwayat keluhan           | ✅ PASS     |
| Hapus Data | Pf-083         | Menguji cascade delete ke tabel data diri                 | ✅ PASS     |
| Hapus Data | Pf-084         | Menguji invalidasi cache setelah delete                   | ✅ PASS     |
| Hapus Data | Pf-085         | Menguji redirect setelah berhasil delete                  | ✅ PASS     |
| Hapus Data | Pf-086         | Menguji penghapusan dengan ID tidak valid                 | ✅ PASS     |

### 9. Fitur Export Excel

| Halaman      | Kode Pengujian | Skenario Pengujian                                | Status Test |
| ------------ | -------------- | ------------------------------------------------- | ----------- |
| Export Excel | Pf-087         | Menguji export seluruh data hasil tes ke Excel   | ✅ PASS     |
| Export Excel | Pf-088         | Menguji export dengan filter kategori teraplikasi| ✅ PASS     |
| Export Excel | Pf-089         | Menguji export dengan pencarian teraplikasi      | ✅ PASS     |
| Export Excel | Pf-090         | Menguji format file Excel yang dihasilkan (.xlsx)| ✅ PASS     |
| Export Excel | Pf-091         | Menguji export data kosong (tidak ada hasil tes) | ✅ PASS     |

---

## Ringkasan Pengujian

| Kategori Fitur             | Kode Pengujian       | Jumlah Test Case | Status       |
| -------------------------- | -------------------- | ---------------- | ------------ |
| Login & Autentikasi        | Pf-001 s/d Pf-022    | 22               | ✅ 100% PASS |
| Data Diri                  | Pf-023 s/d Pf-032    | 10               | ✅ 100% PASS |
| Kuesioner MHI-38           | Pf-033 s/d Pf-044    | 12               | ✅ 100% PASS |
| Hasil Tes                  | Pf-045 s/d Pf-048    | 4                | ✅ 100% PASS |
| Dashboard User             | Pf-049 s/d Pf-054    | 6                | ✅ 100% PASS |
| Admin Dashboard            | Pf-055 s/d Pf-067    | 13               | ✅ 100% PASS |
| Detail Jawaban & Cetak PDF | Pf-068 s/d Pf-076    | 9                | ✅ 100% PASS |
| Hapus Data                 | Pf-077 s/d Pf-086    | 10               | ✅ 100% PASS |
| Export Excel               | Pf-087 s/d Pf-091    | 5                | ✅ 100% PASS |
| **Total**                  | **Pf-001 s/d Pf-091**| **91**           | **✅ PASS**  |

---

## Alur Pengujian (Testing Flow)

Urutan pengujian mengikuti alur pengguna yang terstruktur dari Pf-001 sampai Pf-091:

### 1. **Login & Autentikasi** (Pf-001 s/d Pf-022)
- Login admin dengan email/password
- Google OAuth untuk user mahasiswa
- Logout dan session management
- Middleware protection (guest & admin)

### 2. **Data Diri** (Pf-023 s/d Pf-032)
- Pengisian form data pribadi mahasiswa
- Validasi field dan format data
- Penyimpanan riwayat keluhan
- Session management dan redirect

### 3. **Kuesioner MHI-38** (Pf-033 s/d Pf-044)
- Penyimpanan jawaban 38 pertanyaan
- Perhitungan total skor
- Kategorisasi kesehatan mental (5 kategori)
- Validasi nilai jawaban (1-6)
- Penyimpanan detail jawaban per soal

### 4. **Hasil Tes** (Pf-045 s/d Pf-048)
- Tampilan hasil tes terbaru user
- Relasi dengan data diri mahasiswa
- Tampilan skor dan kategori

### 5. **Dashboard User** (Pf-049 s/d Pf-054)
- Riwayat semua tes dengan pagination
- Perhitungan jumlah tes
- Data chart untuk visualisasi progres

### 6. **Admin Dashboard** (Pf-055 s/d Pf-067)
- Daftar hasil tes semua mahasiswa
- Pencarian (nama, NIM) dan filter (kategori)
- Sorting (nama, skor, tanggal)
- Statistik lengkap (gender, fakultas, asal sekolah, kategori)
- Cache management untuk performa

### 7. **Detail Jawaban & Cetak PDF** (Pf-068 s/d Pf-076)
- Tampilan 38 pertanyaan dengan jawaban lengkap
- Identifikasi item positif dan negatif
- Generate PDF dengan watermark
- Format tabel lengkap untuk cetak

### 8. **Hapus Data** (Pf-077 s/d Pf-086)
- Authorization (hanya admin)
- Cascade delete ke semua tabel terkait
- Invalidasi cache setelah delete
- Handling error untuk ID tidak valid

### 9. **Export Excel** (Pf-087 s/d Pf-091)
- Export data ke format Excel (.xlsx)
- Filter dan pencarian teraplikasi
- Handling data kosong

---

## Instrumen Penilaian: MHI-38

### Kategori Skor

-   **Sangat Sehat**: 190 - 226
-   **Sehat**: 152 - 189
-   **Cukup Sehat**: 114 - 151
-   **Perlu Dukungan**: 76 - 113
-   **Perlu Dukungan Intensif**: 38 - 75

### Klasifikasi Item

-   **Item Negatif (24)**: 2, 3, 8, 9, 11, 13, 14, 15, 16, 18, 19, 20, 21, 24, 25, 27, 28, 29, 30, 32, 33, 35, 36, 38
-   **Item Positif (14)**: 1, 4, 5, 6, 7, 10, 12, 17, 22, 23, 26, 31, 34, 37

---

## Tools & Framework

-   **Testing Framework**: PHPUnit
-   **Laravel Testing**: Feature Tests & Unit Tests
-   **Assertions**: assertDatabaseHas, assertRedirect, assertEquals, assertTrue, assertStatus
-   **Database**: RefreshDatabase trait untuk isolasi test

---

## Catatan Implementasi

1. Setiap test case harus diimplementasikan dalam file test terpisah sesuai fitur
2. Gunakan factory dan seeder untuk data dummy
3. Pastikan database transaction digunakan untuk isolasi test
4. Validasi cache behavior dengan Cache::has() dan Cache::forget()
5. Test authentication menggunakan actingAs() helper

---

## File Test yang Diimplementasikan

### Fitur Login & Autentikasi
- **File**: `tests/Feature/AdminAuthTest.php`
- **Coverage**: Pf-001 s/d Pf-009, Pf-016, Pf-018, Pf-021, Pf-022
- **Total Test Cases**: 13

### Fitur Google OAuth
- **File**: `tests/Feature/AuthControllerTest.php`
- **Coverage**: Pf-010 s/d Pf-015
- **Total Test Cases**: 10

### Fitur Data Diri
- **File**: `tests/Feature/DataDirisControllerTest.php`
- **Coverage**: Pf-023, Pf-024, Pf-029, Pf-030, Pf-031
- **Total Test Cases**: 11

### Fitur Kuesioner MHI-38
- **File**: `tests/Feature/KuesionerValidationTest.php`
- **Coverage**: Pf-040, Pf-041, Pf-042
- **Total Test Cases**: 6

- **File**: `tests/Feature/HasilKuesionerControllerTest.php`
- **Coverage**: Pf-033 s/d Pf-039, Pf-043
- **Total Test Cases**: 18

### Fitur Detail Jawaban & Cetak PDF
- **File**: `tests/Feature/AdminDetailJawabanTest.php`
- **Coverage**: Pf-068 s/d Pf-072
- **Total Test Cases**: 9

- **File**: `tests/Feature/AdminCetakPdfTest.php`
- **Coverage**: Pf-073 s/d Pf-076
- **Total Test Cases**: 10
- **Note**: PDF generation menggunakan jsPDF (client-side), test memverifikasi data dan elemen PDF tersedia di view

### Fitur Admin Dashboard
- **File**: `tests/Feature/AdminDashboardCompleteTest.php`
- **Coverage**: Pf-055 s/d Pf-067
- **Total Test Cases**: 16

### Fitur Export Excel
- **File**: `tests/Feature/ExportFunctionalityTest.php`
- **Coverage**: Pf-087 s/d Pf-091
- **Total Test Cases**: 9

### Fitur Dashboard User
- **File**: `tests/Feature/DashboardControllerTest.php`
- **Coverage**: Pf-049 s/d Pf-054
- **Total Test Cases**: 6

---

## Cara Menjalankan Test

```bash
# Run semua test
php artisan test

# Run test untuk fitur tertentu
php artisan test --filter=AdminAuthTest
php artisan test --filter=AdminCetakPdfTest
php artisan test --filter=DataDirisControllerTest

# Run test dengan detail output
php artisan test --verbose

# Run test dengan coverage
php artisan test --coverage
```

---

---

## Catatan Penting

### Urutan Kode Pengujian (Pf)
Dokumentasi ini menggunakan kode pengujian yang terurut dari **Pf-001 sampai Pf-091** (format 3 digit) untuk memudahkan pelacakan dan pemahaman alur testing:

- **Pf-001 s/d Pf-022**: Login & Autentikasi (22 test cases)
- **Pf-023 s/d Pf-032**: Data Diri (10 test cases)
- **Pf-033 s/d Pf-044**: Kuesioner MHI-38 (12 test cases)
- **Pf-045 s/d Pf-048**: Hasil Tes (4 test cases)
- **Pf-049 s/d Pf-054**: Dashboard User (6 test cases)
- **Pf-055 s/d Pf-067**: Admin Dashboard (13 test cases)
- **Pf-068 s/d Pf-076**: Detail Jawaban & Cetak PDF (9 test cases)
- **Pf-077 s/d Pf-086**: Hapus Data (10 test cases)
- **Pf-087 s/d Pf-091**: Export Excel (5 test cases)

**Total: 91 Skenario Whitebox Testing** yang mencakup seluruh fitur Mental Health dari login sampai export data.

**Format Penomoran**: Menggunakan 3 digit (001-091) agar urutan tetap konsisten dan mudah dibaca.

### Implementasi Test
Beberapa kode Pf merepresentasikan skenario yang sudah ter-cover dalam test yang lebih comprehensive. Misalnya:
- Pf-017, Pf-019, Pf-020 ter-cover dalam `AdminAuthTest` untuk logout flow
- Pf-025 s/d Pf-028, Pf-032 ter-cover dalam `DataDirisControllerTest`
- Pf-044, Pf-053, Pf-066 ter-cover dalam test integration dan workflow

Semua 91 skenario telah di-cover dengan **164 test cases** yang actual diimplementasikan.

---

_Dokumen ini dibuat untuk keperluan pengujian whitebox testing sistem Assessment Online - Modul Mental Health_

**Terakhir diperbarui**: November 2025
**Status**: ✅ 91 Skenario Whitebox Testing (Pf-001 s/d Pf-091)
**Test Implementation**: ✅ 164 Test Cases - All Passing
**Format Kode**: 3 digit (001-091) untuk urutan yang konsisten
