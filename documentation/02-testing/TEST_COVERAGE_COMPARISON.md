# Perbandingan Skenario Whitebox Testing vs PHPUnit Test Implementation

## Status Coverage: **SANGAT KURANG** ⚠️

### Summary
| Total Skenario | Test Implemented | Coverage | Status |
|----------------|------------------|----------|---------|
| 102 | 35 | 34.3% | ⚠️ INCOMPLETE |

---

## 1. Login & Autentikasi (22 skenario)

| Kode | Skenario | Status Test | File Test | Catatan |
|------|----------|-------------|-----------|---------|
| Pf-01 | Login admin dengan email dan password valid | ✅ DONE | AdminLoginTest.php:17 | |
| Pf-02 | Login admin dengan email tidak valid | ✅ DONE | AdminLoginTest.php:36 | |
| Pf-03 | Login admin dengan password salah | ✅ DONE | AdminLoginTest.php:50 | |
| Pf-04 | Login admin dengan field email kosong | ❌ MISSING | - | Perlu dibuat |
| Pf-05 | Login admin dengan field password kosong | ❌ MISSING | - | Perlu dibuat |
| Pf-06 | Validasi format email harus valid | ❌ MISSING | - | Perlu dibuat |
| Pf-07 | Regenerasi session setelah login berhasil | ✅ DONE | AdminLoginTest.php:70 | |
| Pf-08 | Redirect ke halaman admin setelah login berhasil | ✅ DONE | AdminLoginTest.php:95 | |
| Pf-09 | Pesan error "Email atau password salah!" saat gagal login | ✅ DONE | AdminLoginTest.php:113 | |
| Pf-10 | Google OAuth redirect ke halaman Google | ❌ MISSING | - | Perlu dibuat |
| Pf-11 | Google OAuth callback dengan email mahasiswa ITERA valid | ❌ MISSING | - | Perlu dibuat |
| Pf-12 | Google OAuth callback dengan email non-ITERA (ditolak) | ❌ MISSING | - | Perlu dibuat |
| Pf-13 | Ekstraksi NIM dari email mahasiswa ITERA | ❌ MISSING | - | Perlu dibuat |
| Pf-14 | Pembuatan user baru melalui Google OAuth | ❌ MISSING | - | Perlu dibuat |
| Pf-15 | Update user yang sudah ada melalui Google OAuth | ❌ MISSING | - | Perlu dibuat |
| Pf-16 | Logout admin dengan invalidasi session | ✅ DONE | LogoutSessionTest.php:18 | |
| Pf-17 | Regenerasi CSRF token setelah logout | ✅ DONE | LogoutSessionTest.php:34 | |
| Pf-18 | Redirect ke halaman login setelah logout | ✅ DONE | LogoutSessionTest.php:55 | |
| Pf-19 | Session timeout setelah 30 menit tidak aktif | ❌ MISSING | - | Perlu dibuat |
| Pf-20 | Update last_activity_admin setiap request | ❌ MISSING | - | Perlu dibuat |
| Pf-21 | Guest middleware redirect user yang sudah login | ❌ MISSING | - | Perlu dibuat |
| Pf-22 | AdminAuth middleware untuk route admin | ✅ DONE | LogoutSessionTest.php:71 | |

**Coverage: 9/22 (40.9%)** ⚠️

---

## 2. Fitur Data Diri (10 skenario)

| Kode | Skenario | Status Test | File Test | Catatan |
|------|----------|-------------|-----------|---------|
| Pf-23 | Penyimpanan data diri mahasiswa baru dengan semua field valid | ❌ MISSING | - | File kosong |
| Pf-24 | Update data diri mahasiswa yang sudah ada (updateOrCreate) | ❌ MISSING | - | File kosong |
| Pf-25 | Validasi field nama wajib diisi | ❌ MISSING | - | File kosong |
| Pf-26 | Validasi field jenis kelamin harus L atau P | ❌ MISSING | - | File kosong |
| Pf-27 | Validasi field usia harus integer minimal 1 | ❌ MISSING | - | File kosong |
| Pf-28 | Validasi field email harus format email valid | ❌ MISSING | - | File kosong |
| Pf-29 | Penyimpanan riwayat keluhan baru setiap submit | ❌ MISSING | - | File kosong |
| Pf-30 | Pengaturan session (nim, nama, program_studi) setelah submit | ❌ MISSING | - | File kosong |
| Pf-31 | Redirect ke halaman kuesioner setelah berhasil submit | ❌ MISSING | - | File kosong |
| Pf-32 | Validasi field pernah_konsul harus Ya atau Tidak | ❌ MISSING | - | File kosong |

**Coverage: 0/10 (0%)** ⚠️ **CRITICAL - File ada tapi kosong!**

---

## 3. Fitur Kuesioner MHI-38 (12 skenario)

| Kode | Skenario | Status Test | File Test | Catatan |
|------|----------|-------------|-----------|---------|
| Pf-33 | Penyimpanan jawaban 38 pertanyaan kuesioner | ❌ MISSING | - | Perlu dibuat |
| Pf-34 | Perhitungan total skor dari 38 jawaban | ❌ MISSING | - | Perlu dibuat |
| Pf-35 | Kategorisasi "Sangat Sehat" untuk skor 190-226 | ❌ MISSING | - | Perlu dibuat |
| Pf-36 | Kategorisasi "Sehat" untuk skor 152-189 | ❌ MISSING | - | Perlu dibuat |
| Pf-37 | Kategorisasi "Cukup Sehat" untuk skor 114-151 | ❌ MISSING | - | Perlu dibuat |
| Pf-38 | Kategorisasi "Perlu Dukungan" untuk skor 76-113 | ❌ MISSING | - | Perlu dibuat |
| Pf-39 | Kategorisasi "Perlu Dukungan Intensif" untuk skor 38-75 | ❌ MISSING | - | Perlu dibuat |
| Pf-40 | Validasi setiap pertanyaan harus diisi (required) | ❌ MISSING | - | Perlu dibuat |
| Pf-41 | Validasi nilai jawaban harus 1-6 | ❌ MISSING | - | Perlu dibuat |
| Pf-42 | Penyimpanan detail jawaban per nomor soal ke tabel MentalHealthJawabanDetail | ❌ MISSING | - | Perlu dibuat |
| Pf-43 | Redirect ke halaman hasil setelah submit kuesioner | ❌ MISSING | - | Perlu dibuat |
| Pf-44 | Invalidasi cache setelah submit kuesioner baru | ❌ MISSING | - | Perlu dibuat |

**Coverage: 0/12 (0%)** ⚠️ **CRITICAL**

---

## 4. Fitur Hasil Tes (4 skenario)

| Kode | Skenario | Status Test | File Test | Catatan |
|------|----------|-------------|-----------|---------|
| Pf-45 | Tampilan hasil tes terbaru user yang login | ❌ MISSING | - | Perlu dibuat |
| Pf-46 | Relasi hasil kuesioner dengan data diri mahasiswa | ❌ MISSING | - | Perlu dibuat |
| Pf-47 | Tampilan total skor dan kategori kesehatan mental | ❌ MISSING | - | Perlu dibuat |
| Pf-48 | Akses halaman hasil tanpa login (redirect ke login) | ❌ MISSING | - | Perlu dibuat |

**Coverage: 0/4 (0%)** ⚠️ **CRITICAL**

---

## 5. Fitur Dashboard User (6 skenario)

| Kode | Skenario | Status Test | File Test | Catatan |
|------|----------|-------------|-----------|---------|
| Pf-49 | Tampilan riwayat semua tes user dengan pagination | ❌ MISSING | - | Perlu dibuat |
| Pf-50 | Perhitungan jumlah tes yang diikuti user | ❌ MISSING | - | Perlu dibuat |
| Pf-51 | Tampilan kategori terakhir user | ❌ MISSING | - | Perlu dibuat |
| Pf-52 | Data chart (tanggal dan skor) untuk visualisasi | ❌ MISSING | - | Perlu dibuat |
| Pf-53 | Caching riwayat tes user (5 menit) | ❌ MISSING | - | Perlu dibuat |
| Pf-54 | User baru dengan riwayat tes kosong | ❌ MISSING | - | Perlu dibuat |

**Coverage: 0/6 (0%)** ⚠️ **CRITICAL**

---

## 6. Fitur Admin Dashboard (13 skenario)

| Kode | Skenario | Status Test | File Test | Catatan |
|------|----------|-------------|-----------|---------|
| Pf-55 | Tampilan daftar hasil tes dengan pagination | ❌ MISSING | - | Perlu dibuat |
| Pf-56 | Pencarian berdasarkan nama mahasiswa | ❌ MISSING | - | Perlu dibuat |
| Pf-57 | Pencarian berdasarkan NIM | ❌ MISSING | - | Perlu dibuat |
| Pf-58 | Filter berdasarkan kategori kesehatan mental | ❌ MISSING | - | Perlu dibuat |
| Pf-59 | Sorting berdasarkan nama (ASC/DESC) | ❌ MISSING | - | Perlu dibuat |
| Pf-60 | Sorting berdasarkan total skor | ❌ MISSING | - | Perlu dibuat |
| Pf-61 | Sorting berdasarkan tanggal tes | ❌ MISSING | - | Perlu dibuat |
| Pf-62 | Statistik total user dan distribusi gender | ❌ MISSING | - | Perlu dibuat |
| Pf-63 | Statistik distribusi asal sekolah (SMA/SMK/Pesantren) | ❌ MISSING | - | Perlu dibuat |
| Pf-64 | Statistik distribusi per fakultas dengan persentase | ❌ MISSING | - | Perlu dibuat |
| Pf-65 | Statistik jumlah per kategori kesehatan | ❌ MISSING | - | Perlu dibuat |
| Pf-66 | Caching statistik admin (1 menit) | ❌ MISSING | - | Perlu dibuat |
| Pf-67 | Akses admin dashboard tanpa login admin (unauthorized) | ❌ MISSING | - | Perlu dibuat |

**Coverage: 0/13 (0%)** ⚠️ **CRITICAL**

---

## 7. Fitur Detail Jawaban & Cetak PDF (12 skenario)

| Kode | Skenario | Status Test | File Test | Catatan |
|------|----------|-------------|-----------|---------|
| Pf-68 | Tampilan 38 pertanyaan dengan jawaban mahasiswa | ❌ MISSING | - | Perlu dibuat |
| Pf-69 | Identifikasi item negatif (psychological distress) | ❌ MISSING | - | Perlu dibuat |
| Pf-70 | Identifikasi item positif (psychological well-being) | ❌ MISSING | - | Perlu dibuat |
| Pf-71 | Tampilan informasi data diri lengkap mahasiswa | ❌ MISSING | - | Perlu dibuat |
| Pf-72 | Akses detail dengan ID tidak valid (404) | ❌ MISSING | - | Perlu dibuat |
| Pf-73 | Generate PDF detail jawaban dengan data valid | ✅ DONE | CetakPdfTest.php:44 | |
| Pf-74 | Konten PDF berisi header, info mahasiswa, dan tabel jawaban | ✅ DONE | CetakPdfTest.php:63 | |
| Pf-75 | Watermark "Generated by ANALOGY - ITERA" pada PDF | ✅ DONE | CetakPdfTest.php:88 | |
| Pf-76 | Format tabel PDF dengan 38 pertanyaan lengkap | ❌ MISSING | - | Perlu dibuat |
| Pf-77 | Escape karakter khusus dalam pertanyaan untuk PDF | ✅ DONE | CetakPdfTest.php:103 | |
| Pf-78 | Error handling saat generate PDF gagal | ✅ DONE | CetakPdfTest.php:120 | |
| Pf-79 | Nama file PDF sesuai format (detail_jawaban_{nim}_{tanggal}.pdf) | ❌ MISSING | - | Perlu dibuat |

**Coverage: 5/12 (41.7%)** ⚠️

---

## 8. Fitur Hapus Data (7 skenario)

| Kode | Skenario | Status Test | File Test | Catatan |
|------|----------|-------------|-----------|---------|
| Pf-80 | Penghapusan hasil kuesioner | ✅ DONE | CascadeDeleteTest.php:* | Implicit dalam cascade test |
| Pf-81 | Cascade delete ke tabel jawaban detail | ❌ MISSING | - | Perlu dibuat |
| Pf-82 | Cascade delete ke tabel riwayat keluhan | ✅ DONE | CascadeDeleteTest.php:32 | |
| Pf-83 | Cascade delete ke tabel data diri | ✅ DONE | CascadeDeleteTest.php:60 | |
| Pf-84 | Invalidasi cache setelah penghapusan | ❌ MISSING | - | Perlu dibuat |
| Pf-85 | Database transaction rollback jika gagal | ✅ DONE | CascadeDeleteTest.php:91 | |
| Pf-86 | Penghapusan dengan ID tidak valid | ❌ MISSING | - | Perlu dibuat |

**Coverage: 4/7 (57.1%)** ⚠️

---

## 9. Fitur Export Excel (5 skenario)

| Kode | Skenario | Status Test | File Test | Catatan |
|------|----------|-------------|-----------|---------|
| Pf-87 | Export seluruh data hasil tes ke Excel | ❌ MISSING | - | Perlu dibuat |
| Pf-88 | Export dengan filter kategori teraplikasi | ❌ MISSING | - | Perlu dibuat |
| Pf-89 | Export dengan pencarian teraplikasi | ❌ MISSING | - | Perlu dibuat |
| Pf-90 | Format file Excel yang dihasilkan (.xlsx) | ❌ MISSING | - | Perlu dibuat |
| Pf-91 | Export data kosong (tidak ada hasil tes) | ❌ MISSING | - | Perlu dibuat |

**Coverage: 0/5 (0%)** ⚠️ **CRITICAL**

---

## 10. Fitur Model & Relasi (6 skenario)

| Kode | Skenario | Status Test | File Test | Catatan |
|------|----------|-------------|-----------|---------|
| Pf-92 | Relasi HasilKuesioner belongsTo DataDiri | ❌ MISSING | - | Perlu dibuat |
| Pf-93 | Relasi HasilKuesioner hasMany JawabanDetail | ❌ MISSING | - | Perlu dibuat |
| Pf-94 | Relasi DataDiri hasMany HasilKuesioner | ❌ MISSING | - | Perlu dibuat |
| Pf-95 | Relasi DataDiri latestOfMany HasilKuesioner | ❌ MISSING | - | Perlu dibuat |
| Pf-96 | Scope search pada model DataDiri | ❌ MISSING | - | Perlu dibuat |
| Pf-97 | Primary key custom (nim) pada DataDiri | ❌ MISSING | - | Perlu dibuat |

**Coverage: 0/6 (0%)** ⚠️ **CRITICAL**

---

## 11. Fitur Validasi & Keamanan (5 skenario)

| Kode | Skenario | Status Test | File Test | Catatan |
|------|----------|-------------|-----------|---------|
| Pf-98 | Middleware auth untuk route user | ✅ DONE | SecurityValidationTest.php:19 | |
| Pf-99 | Middleware AdminAuth untuk route admin | ✅ DONE | SecurityValidationTest.php:53 | |
| Pf-100 | Custom error messages pada form request | ❌ MISSING | - | Perlu dibuat |
| Pf-101 | Proteksi CSRF pada form submission | ✅ DONE | SecurityValidationTest.php:87 | |
| Pf-102 | Sanitasi input untuk mencegah XSS | ✅ DONE | SecurityValidationTest.php:120 | |

**Coverage: 4/5 (80%)** ✅

---

## Ringkasan Per Kategori

| Kategori | Total | Implemented | Missing | Coverage % | Status |
|----------|-------|-------------|---------|------------|--------|
| 1. Login & Autentikasi | 22 | 9 | 13 | 40.9% | ⚠️ |
| 2. Data Diri | 10 | 0 | 10 | 0% | 🔴 CRITICAL |
| 3. Kuesioner MHI-38 | 12 | 0 | 12 | 0% | 🔴 CRITICAL |
| 4. Hasil Tes | 4 | 0 | 4 | 0% | 🔴 CRITICAL |
| 5. Dashboard User | 6 | 0 | 6 | 0% | 🔴 CRITICAL |
| 6. Admin Dashboard | 13 | 0 | 13 | 0% | 🔴 CRITICAL |
| 7. Detail Jawaban & PDF | 12 | 5 | 7 | 41.7% | ⚠️ |
| 8. Hapus Data | 7 | 4 | 3 | 57.1% | ⚠️ |
| 9. Export Excel | 5 | 0 | 5 | 0% | 🔴 CRITICAL |
| 10. Model & Relasi | 6 | 0 | 6 | 0% | 🔴 CRITICAL |
| 11. Validasi & Keamanan | 5 | 4 | 1 | 80% | ✅ |
| **TOTAL** | **102** | **22** | **80** | **21.6%** | **🔴 CRITICAL** |

---

## Critical Findings

### ⚠️ MASALAH KRITIS

1. **DataDiriValidationTest.php kosong!**
   - File ada tapi hanya berisi helper function
   - 10 skenario (Pf-23 s/d Pf-32) tidak diimplementasikan

2. **Fitur Core Tidak Tertest:**
   - Kuesioner MHI-38 (0% coverage) - INI FITUR UTAMA!
   - Dashboard User (0% coverage)
   - Admin Dashboard (0% coverage)
   - Model & Relasi (0% coverage)

3. **Google OAuth Tidak Tertest:**
   - 6 skenario OAuth (Pf-10 s/d Pf-15) belum dibuat

4. **Export Excel Tidak Tertest:**
   - 5 skenario export (Pf-87 s/d Pf-91) belum dibuat

---

## Rekomendasi Prioritas

### 🔴 URGENT (Harus dibuat segera)

1. **Kuesioner MHI-38 Tests** (Pf-33 s/d Pf-44)
   - Ini fitur inti aplikasi!
   - Buat file: `tests/Feature/KuesionerMentalHealthTest.php`

2. **Data Diri Tests** (Pf-23 s/d Pf-32)
   - Lengkapi file `DataDiriValidationTest.php` yang masih kosong

3. **Admin Dashboard Tests** (Pf-55 s/d Pf-67)
   - Buat file: `tests/Feature/AdminDashboardTest.php`

### ⚠️ HIGH PRIORITY

4. **Dashboard User Tests** (Pf-49 s/d Pf-54)
   - Buat file: `tests/Feature/UserDashboardTest.php`

5. **Hasil Tes Tests** (Pf-45 s/d Pf-48)
   - Buat file: `tests/Feature/HasilTesTest.php`

6. **Model & Relasi Tests** (Pf-92 s/d Pf-97)
   - Buat file: `tests/Unit/ModelRelationTest.php`

### 📋 MEDIUM PRIORITY

7. **Google OAuth Tests** (Pf-10 s/d Pf-15)
   - Tambahkan ke `AdminLoginTest.php`

8. **Export Excel Tests** (Pf-87 s/d Pf-91)
   - Buat file: `tests/Feature/ExportExcelTest.php`

9. **Complete Login Tests** (Pf-04, Pf-05, Pf-06, Pf-19, Pf-20, Pf-21)
   - Tambahkan ke `AdminLoginTest.php`

---

## Files Yang Perlu Dibuat

```
tests/Feature/
├── KuesionerMentalHealthTest.php       (NEW - URGENT)
├── AdminDashboardTest.php              (NEW - URGENT)
├── UserDashboardTest.php               (NEW - HIGH)
├── HasilTesTest.php                    (NEW - HIGH)
├── ExportExcelTest.php                 (NEW - MEDIUM)
└── DataDiriValidationTest.php          (COMPLETE - URGENT)

tests/Unit/
└── ModelRelationTest.php               (NEW - HIGH)
```

---

## Target Coverage

**Saat ini: 21.6% (22/102)**

**Target Minimum: 80% (82/102)**

**Target Ideal: 100% (102/102)**

---

*Generated: 2025-11-20*
*Status: INCOMPLETE - Butuh 80 test case lagi untuk memenuhi dokumentasi whitebox testing*
