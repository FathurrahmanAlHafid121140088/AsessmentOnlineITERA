# JUMLAH TEST CASE YANG DIMINTA
## Kategori: Admin, User, Autentikasi, Kuesioner Mental Health, dan Jawaban Detail

**Tanggal**: November 2025
**Status**: ✅ Verified

---

## 📊 TOTAL: 133 Test Cases

---

## 📋 Breakdown Detail per Kategori

### **1. AUTENTIKASI (24 tests)** ✅

| File Test | Jumlah | Deskripsi |
|-----------|--------|-----------|
| `AdminAuthTest.php` | 13 | Login/Logout Admin dengan email-password |
| `AuthControllerTest.php` | 11 | Login Mahasiswa dengan Google OAuth |
| **SUBTOTAL** | **24** | |

**Test Cases Autentikasi:**
- Login admin dengan kredensial valid
- Login admin dengan email/password salah
- Validasi field required
- Regenerasi session setelah login
- Logout dan invalidasi session
- Middleware protection
- Auto-logout setelah 30 menit inaktif
- Google OAuth redirect
- Callback dengan email ITERA valid/invalid
- Ekstraksi NIM dari email
- Exception handling

---

### **2. USER - Dashboard User (6 tests)** ✅

| File Test | Jumlah | Deskripsi |
|-----------|--------|-----------|
| `DashboardControllerTest.php` | 6 | Dashboard mahasiswa dengan riwayat tes |
| **SUBTOTAL** | **6** | |

**Test Cases User Dashboard:**
- User dengan riwayat tes (statistik & chart)
- User tanpa riwayat tes
- Chart data terurut kronologis
- Kategori terakhir ditampilkan
- Pagination riwayat tes (10 per page)
- Cache dashboard user

---

### **3. ADMIN - Management & Dashboard (79 tests)** ✅

| File Test | Jumlah | Deskripsi |
|-----------|--------|-----------|
| `AdminDashboardCompleteTest.php` | 16 | Dashboard admin dengan filter & sort |
| `HasilKuesionerCombinedControllerTest.php` | 36 | CRUD admin, search, filter kombinasi |
| `AdminDetailJawabanTest.php` | 9 | **Detail jawaban per soal** ⭐ |
| `AdminCetakPdfTest.php` | 9 | Export PDF |
| `ExportFunctionalityTest.php` | 9 | Export Excel |
| **SUBTOTAL** | **79** | |

#### **3a. AdminDashboardCompleteTest (16 tests)**
- Tampilkan hanya tes terakhir per mahasiswa
- Pagination (10, 25, 50, 100 per page)
- Search berdasarkan nama/NIM
- Filter berdasarkan kategori
- Sorting (skor, nama, tanggal)
- Statistik (total user, gender)

#### **3b. HasilKuesionerCombinedControllerTest (36 tests)**
- Index dengan pagination
- Search multi-kolom (nama, NIM, email, fakultas, prodi)
- Filter kategori
- Sort ascending/descending
- Kombinasi search + filter + sort
- Delete hasil kuesioner
- Cascade delete
- Export dengan filter
- Large dataset handling

#### **3c. AdminDetailJawabanTest (9 tests)** ⭐
- Tampilan 38 pertanyaan dengan jawaban
- Identifikasi item negatif (24 item)
- Identifikasi item positif (14 item)
- Info mahasiswa lengkap
- Total skor dan kategori
- Tanggal tes
- Jawaban terurut nomor soal 1-38
- Invalid ID (404)
- Relasi eager loading

#### **3d. AdminCetakPdfTest (9 tests)**
- Generate PDF hasil kuesioner
- PDF dengan filter kategori
- PDF format dan struktur
- Download functionality

#### **3e. ExportFunctionalityTest (9 tests)**
- Export Excel semua data
- Format nama file (timestamp)
- Export dengan filter kategori
- Export dengan search
- Export dengan sort
- Data kosong
- Large dataset (1000+ records)
- Authentication required
- Kolom lengkap

---

### **4. KUESIONER MENTAL HEALTH (24 tests)** ✅

| File Test | Jumlah | Deskripsi |
|-----------|--------|-----------|
| `KuesionerValidationTest.php` | 6 | Validasi input MHI-38 |
| `HasilKuesionerControllerTest.php` | 18 | Scoring & kategorisasi |
| **SUBTOTAL** | **24** | |

#### **4a. KuesionerValidationTest (6 tests)**
- Validasi batas minimum (nilai 1)
- Validasi batas maksimum (nilai 6)
- Reject nilai < 1 atau > 6
- Penyimpanan 38 detail jawaban per nomor soal
- Foreign key relationships
- Multiple submit terpisah (tidak overwrite)

#### **4b. HasilKuesionerControllerTest (18 tests)**

**Kategorisasi MHI-38:**
- 190-226: Sangat Sehat
- 152-189: Sehat
- 114-151: Cukup Sehat
- 76-113: Perlu Dukungan
- 38-75: Perlu Dukungan Intensif

**Test Cases:**
- Kategori "Sangat Sehat" (skor 208)
- Kategori "Sehat" (skor 170)
- Kategori "Cukup Sehat" (skor 130)
- Kategori "Perlu Dukungan" (skor 90)
- Kategori "Perlu Dukungan Intensif" (skor 50)
- Boundary testing (batas minimal/maksimal setiap kategori)
- Total skor 38 (semua jawaban 1)
- Total skor 228 (semua jawaban 6)
- Konversi string ke integer
- Variasi jawaban dihitung akurat
- NIM user login tersimpan
- Cache invalidation setelah submit
- Redirect ke halaman hasil
- Flash message sukses
- Multiple submit tidak overwrite
- Hasil terakhir di dashboard
- FK constraint hasil-detail
- Transaction rollback on error

---

### **5. JAWABAN DETAIL MENTAL HEALTH (9 tests)** ✅

| File Test | Jumlah | Deskripsi |
|-----------|--------|-----------|
| `AdminDetailJawabanTest.php` | 9 | Detail jawaban 38 soal MHI-38 |
| **SUBTOTAL** | **9** | |

**Catatan**: Test ini sudah termasuk dalam Admin (kategori 3c di atas)

**Item Classification MHI-38:**
- **Psychological Distress (24 item - Negatif):**
  Nomor: 2, 3, 8, 9, 11, 13, 14, 15, 16, 18, 19, 20, 21, 24, 25, 27, 28, 29, 30, 32, 33, 35, 36, 38

- **Psychological Well-being (14 item - Positif):**
  Nomor: 1, 4, 5, 6, 7, 10, 12, 17, 22, 23, 26, 31, 34, 37

**Test Cases:**
- Tampilan 38 pertanyaan lengkap
- Setiap pertanyaan memiliki jawaban
- 24 item negatif teridentifikasi
- 14 item positif teridentifikasi
- Info mahasiswa (nama, NIM, email)
- Total skor dan kategori
- Tanggal tes dengan format benar
- Jawaban terurut 1-38
- Invalid ID return 404
- Eager loading (N+1 problem avoided)

---

## 🎯 RINGKASAN TOTAL

### **Perhitungan:**

**Cara 1: Tanpa Duplikasi (Detail Jawaban terpisah)**
```
Autentikasi:           24 tests
User Dashboard:         6 tests
Admin (tanpa Detail):  70 tests (79 - 9 Detail)
Kuesioner MH:          24 tests
Detail Jawaban MH:      9 tests
─────────────────────────────
TOTAL:                133 tests
```

**Cara 2: Detail Jawaban termasuk Admin**
```
Autentikasi:           24 tests
User Dashboard:         6 tests
Admin (termasuk Detail): 79 tests
Kuesioner MH:          24 tests
─────────────────────────────
TOTAL:                133 tests
```

### **JAWABAN: 133 Test Cases** ✅

---

## 📁 File Test yang Relevan

```
tests/Feature/
├── AdminAuthTest.php                        (13 tests)
├── AuthControllerTest.php                   (11 tests)
├── DashboardControllerTest.php              (6 tests)
├── AdminDashboardCompleteTest.php           (16 tests)
├── HasilKuesionerCombinedControllerTest.php (36 tests)
├── AdminDetailJawabanTest.php               (9 tests) ⭐
├── AdminCetakPdfTest.php                    (9 tests)
├── ExportFunctionalityTest.php              (9 tests)
├── KuesionerValidationTest.php              (6 tests)
└── HasilKuesionerControllerTest.php         (18 tests)
```

**Total: 133 tests**

---

## ✅ Verification

Command untuk verify:
```bash
# Run specific tests
php artisan test tests/Feature/AdminAuthTest.php
php artisan test tests/Feature/AuthControllerTest.php
php artisan test tests/Feature/DashboardControllerTest.php
php artisan test tests/Feature/AdminDashboardCompleteTest.php
php artisan test tests/Feature/HasilKuesionerCombinedControllerTest.php
php artisan test tests/Feature/AdminDetailJawabanTest.php
php artisan test tests/Feature/AdminCetakPdfTest.php
php artisan test tests/Feature/ExportFunctionalityTest.php
php artisan test tests/Feature/KuesionerValidationTest.php
php artisan test tests/Feature/HasilKuesionerControllerTest.php
```

---

## 📝 Catatan Penting

1. **Detail Jawaban** (AdminDetailJawabanTest - 9 tests) sudah termasuk dalam kategori **Admin**, jadi tidak perlu dihitung 2 kali.

2. Jika ingin memisahkan Detail Jawaban sebagai kategori terpisah dari Admin, maka:
   - Admin: 70 tests (79 - 9)
   - Detail Jawaban: 9 tests
   - **Total tetap: 133 tests**

3. **Tidak termasuk:**
   - DataDirisControllerTest (3 tests) - Data diri form
   - CachePerformanceTest (9 tests) - Performance
   - MentalHealthWorkflowIntegrationTest (7 tests) - Integration E2E
   - Unit Tests (33 tests) - Model testing
   - RmibScoringTest (4 tests) - RMIB (bukan Mental Health)

4. **Yang diminta dan sudah dihitung:**
   ✅ Admin
   ✅ User
   ✅ Autentikasi
   ✅ Kuesioner Mental Health
   ✅ Jawaban Detail Mental Health

---

**Status**: ✅ All 133 Tests PASSED
**Last Verified**: November 2025
