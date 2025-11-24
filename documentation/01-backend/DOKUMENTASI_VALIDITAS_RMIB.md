# 📊 Dokumentasi Validitas Tes RMIB

**Rothwell-Miller Interest Blank (RMIB) - Sistem Validitas & Skor Konsistensi**

---

## 📖 Daftar Isi

1. [Apa itu Validitas?](#1-apa-itu-validitas)
2. [Mengapa Validitas Penting?](#2-mengapa-validitas-penting)
3. [Cara Kerja Sistem Validitas](#3-cara-kerja-sistem-validitas)
4. [Perhitungan Skor Konsistensi](#4-perhitungan-skor-konsistensi)
5. [Contoh Perhitungan](#5-contoh-perhitungan)
6. [Interpretasi Hasil](#6-interpretasi-hasil)
7. [Threshold Validitas](#7-threshold-validitas)
8. [FAQ](#8-faq)

---

## 1. Apa itu Validitas?

**Validitas** adalah indikator untuk menentukan apakah hasil tes RMIB seseorang dapat **dipercaya** atau tidak. Validitas mengukur seberapa **konsisten** peserta dalam menjawab pertanyaan tes.

### Status Validitas

| Status | Deskripsi |
|--------|-----------|
| ✅ **Valid** | Hasil tes dapat dipercaya dan digunakan untuk rekomendasi karir |
| ❌ **Tidak Valid** | Hasil tes tidak dapat dipercaya (kemungkinan jawaban asal-asalan) |

---

## 2. Mengapa Validitas Penting?

Validitas sangat penting karena:

### ✅ Manfaat Validitas

1. **Memastikan Kejujuran**
   - Peserta menjawab dengan serius dan jujur
   - Bukan asal pilih atau random

2. **Mendeteksi Pola Jawaban Tidak Konsisten**
   - Contoh ketidakkonsistenan:
     - Ilmuwan (Scientific) diberi peringkat 1 (sangat diminati)
     - Ahli Kimia (Scientific) diberi peringkat 12 (tidak diminati)
     - Padahal keduanya kategori yang sama!

3. **Memastikan Akurasi Interpretasi**
   - Rekomendasi karir hanya valid jika jawaban konsisten
   - Konseling karir berbasis data yang akurat

4. **Standar Psikometri**
   - Memenuhi standar tes psikologi yang valid
   - Hasil dapat dipertanggungjawabkan secara ilmiah

---

## 3. Cara Kerja Sistem Validitas

### Diagram Alur

```
┌─────────────────────────────────┐
│ Peserta Mengerjakan Tes RMIB   │
│ (Memberikan peringkat 1-12)     │
└──────────┬──────────────────────┘
           │
           ▼
┌─────────────────────────────────┐
│ Sistem Mengelompokkan Jawaban   │
│ Berdasarkan 12 Kategori RMIB    │
└──────────┬──────────────────────┘
           │
           ▼
┌─────────────────────────────────┐
│ Hitung Varians Per Kategori     │
│ (Ukuran penyebaran data)         │
└──────────┬──────────────────────┘
           │
           ▼
┌─────────────────────────────────┐
│ Hitung Rata-rata Varians         │
│ Dari 12 Kategori                 │
└──────────┬──────────────────────┘
           │
           ▼
┌─────────────────────────────────┐
│ Normalisasi ke Skala 0-10       │
│ (Inversi: Varians rendah = Skor │
│  konsistensi tinggi)             │
└──────────┬──────────────────────┘
           │
           ▼
┌─────────────────────────────────┐
│ Tentukan Validitas              │
│ Skor >= 7 → Valid               │
│ Skor < 7 → Tidak Valid          │
└─────────────────────────────────┘
```

---

## 4. Perhitungan Skor Konsistensi

### Langkah-langkah Perhitungan

#### **Step 1: Kelompokkan Peringkat per Kategori**

Setiap pekerjaan dalam tes RMIB termasuk dalam salah satu dari 12 kategori:

| No | Kategori | Singkatan |
|----|----------|-----------|
| 1 | Outdoor | OUT |
| 2 | Mechanical | MECH |
| 3 | Computational | COMP |
| 4 | Scientific | SCI |
| 5 | Personal Contact | PERS |
| 6 | Aesthetic | AETH |
| 7 | Literary | LIT |
| 8 | Musical | MUS |
| 9 | Social Service | S.S |
| 10 | Clerical | CLER |
| 11 | Practical | PRAC |
| 12 | Medical | MED |

Sistem akan mengelompokkan semua peringkat yang diberikan peserta berdasarkan kategori pekerjaan.

**Contoh:**
```
Scientific (SCI):
- Ilmuwan: Peringkat 1
- Insinyur Kimia Industri: Peringkat 2
- Ahli Biologi: Peringkat 1
→ Array peringkat: [1, 2, 1]

Outdoor (OUT):
- Petani: Peringkat 12
- Peternak Sapi: Peringkat 11
- Nelayan: Peringkat 10
→ Array peringkat: [12, 11, 10]
```

---

#### **Step 2: Hitung Varians untuk Setiap Kategori**

**Varians** adalah ukuran statistik yang menunjukkan seberapa **tersebar** data dari nilai rata-ratanya.

##### Formula Varians:

```
1. Hitung Mean (Rata-rata):
   Mean = (Σ Peringkat) / Jumlah Data

2. Hitung Deviasi (Selisih dari Mean):
   Deviasi = Peringkat - Mean

3. Kuadratkan Deviasi:
   Deviasi² = (Peringkat - Mean)²

4. Hitung Varians:
   Varians = (Σ Deviasi²) / Jumlah Data
```

##### Contoh Perhitungan:

**Kategori: Scientific**
```
Data: [1, 2, 1]

Step 1: Hitung Mean
Mean = (1 + 2 + 1) / 3 = 4 / 3 = 1.33

Step 2: Hitung Deviasi
Deviasi₁ = 1 - 1.33 = -0.33
Deviasi₂ = 2 - 1.33 = 0.67
Deviasi₃ = 1 - 1.33 = -0.33

Step 3: Kuadratkan Deviasi
Deviasi₁² = (-0.33)² = 0.11
Deviasi₂² = (0.67)² = 0.45
Deviasi₃² = (-0.33)² = 0.11

Step 4: Hitung Varians
Varians = (0.11 + 0.45 + 0.11) / 3
        = 0.67 / 3
        = 0.22
```

**Interpretasi:**
- Varians = **0.22** (rendah) → Peserta **konsisten** dalam menilai kategori Scientific
- Semua pekerjaan Scientific diberi peringkat rendah (1-2), artinya sangat diminati

---

**Kategori: Outdoor**
```
Data: [12, 11, 10]

Step 1: Hitung Mean
Mean = (12 + 11 + 10) / 3 = 33 / 3 = 11

Step 2: Hitung Deviasi
Deviasi₁ = 12 - 11 = 1
Deviasi₂ = 11 - 11 = 0
Deviasi₃ = 10 - 11 = -1

Step 3: Kuadratkan Deviasi
Deviasi₁² = (1)² = 1
Deviasi₂² = (0)² = 0
Deviasi₃² = (-1)² = 1

Step 4: Hitung Varians
Varians = (1 + 0 + 1) / 3
        = 2 / 3
        = 0.67
```

**Interpretasi:**
- Varians = **0.67** (rendah) → Peserta **konsisten** dalam menilai kategori Outdoor
- Semua pekerjaan Outdoor diberi peringkat tinggi (10-12), artinya tidak diminati

---

#### **Step 3: Hitung Rata-rata Varians dari 12 Kategori**

```
Rata-rata Varians = (Varians₁ + Varians₂ + ... + Varians₁₂) / 12

Contoh:
Varians Scientific = 0.22
Varians Outdoor = 0.67
Varians Mechanical = 1.50
...
Varians Medical = 0.80

Rata-rata Varians = (0.22 + 0.67 + 1.50 + ... + 0.80) / 12
                  = 15.6 / 12
                  = 1.30
```

---

#### **Step 4: Normalisasi ke Skala 0-10**

Sistem menggunakan **inversi** karena:
- **Varians rendah** = Konsisten = **Skor tinggi** ✅
- **Varians tinggi** = Tidak konsisten = **Skor rendah** ❌

##### Formula Normalisasi:

```php
// Varians maksimal teoritis (dari distribusi [1-12])
$maxVarians = 11.92

// Normalisasi ke skala 0-1
$normalizedVarians = min($avgVarians / $maxVarians, 1)

// Inversi dan skala ke 0-10
$skorKonsistensi = round((1 - $normalizedVarians) * 10)

// Pastikan dalam range 0-10
$skorKonsistensi = max(0, min(10, $skorKonsistensi))
```

##### Contoh:

```
Rata-rata Varians = 1.30

Normalized = 1.30 / 11.92 = 0.109

Skor Konsistensi = (1 - 0.109) × 10
                 = 0.891 × 10
                 = 8.91
                 ≈ 9 (dibulatkan)
```

**Hasil:** Skor Konsistensi = **9** (Sangat konsisten!)

---

## 5. Contoh Perhitungan

### Contoh 1: Peserta Konsisten (Valid)

**Nama:** Budi
**Jawaban:**

| Kategori | Pekerjaan | Peringkat | Array |
|----------|-----------|-----------|-------|
| Scientific | Ilmuwan | 1 | [1, 2, 1] |
| Scientific | Insinyur Kimia | 2 | |
| Scientific | Ahli Biologi | 1 | |
| Outdoor | Petani | 12 | [12, 11, 10] |
| Outdoor | Peternak | 11 | |
| Outdoor | Nelayan | 10 | |
| ... | ... | ... | ... |

**Perhitungan:**

1. **Varians Scientific** = 0.22 (rendah)
2. **Varians Outdoor** = 0.67 (rendah)
3. **Rata-rata Varians** = 1.30
4. **Skor Konsistensi** = 9
5. **Status:** ✅ **Valid** (9 >= 7)

**Interpretasi:**
Budi sangat konsisten dalam menjawab. Ia memberikan peringkat rendah (1-2) untuk semua pekerjaan Scientific dan peringkat tinggi (10-12) untuk semua pekerjaan Outdoor. Ini menunjukkan ia benar-benar lebih tertarik pada bidang Scientific daripada Outdoor.

---

### Contoh 2: Peserta Tidak Konsisten (Tidak Valid)

**Nama:** Ani
**Jawaban:**

| Kategori | Pekerjaan | Peringkat | Array |
|----------|-----------|-----------|-------|
| Scientific | Ilmuwan | 1 | [1, 12, 6] |
| Scientific | Insinyur Kimia | 12 | |
| Scientific | Ahli Biologi | 6 | |
| Outdoor | Petani | 2 | [2, 11, 5] |
| Outdoor | Peternak | 11 | |
| Outdoor | Nelayan | 5 | |
| ... | ... | ... | ... |

**Perhitungan:**

1. **Varians Scientific:**
   ```
   Mean = (1 + 12 + 6) / 3 = 6.33
   Varians = [(1-6.33)² + (12-6.33)² + (6-6.33)²] / 3
          = [28.41 + 32.13 + 0.11] / 3
          = 20.22
   ```

2. **Varians Outdoor:**
   ```
   Mean = (2 + 11 + 5) / 3 = 6
   Varians = [(2-6)² + (11-6)² + (5-6)²] / 3
          = [16 + 25 + 1] / 3
          = 14.0
   ```

3. **Rata-rata Varians** = 8.50 (tinggi!)
4. **Skor Konsistensi:**
   ```
   Normalized = 8.50 / 11.92 = 0.713
   Skor = (1 - 0.713) × 10 = 2.87 ≈ 3
   ```

5. **Status:** ❌ **Tidak Valid** (3 < 7)

**Interpretasi:**
Ani tidak konsisten dalam menjawab. Untuk kategori Scientific, ia memberikan:
- Ilmuwan: Peringkat 1 (sangat diminati)
- Insinyur Kimia: Peringkat 12 (tidak diminati)
- Ahli Biologi: Peringkat 6 (netral)

Padahal ketiga pekerjaan tersebut masuk kategori yang sama! Ini menunjukkan Ani kemungkinan:
- Menjawab secara asal-asalan
- Tidak memahami instruksi tes
- Tidak fokus saat mengerjakan

**Hasil tes Ani tidak dapat dipercaya untuk rekomendasi karir.**

---

## 6. Interpretasi Hasil

### Tabel Interpretasi Skor Konsistensi

| Skor | Kategori | Interpretasi | Status Validitas |
|------|----------|--------------|------------------|
| 9-10 | Sangat Konsisten | Peserta sangat jujur dan fokus, hasil sangat dapat dipercaya | ✅ Valid |
| 7-8 | Konsisten | Peserta cukup konsisten, hasil dapat dipercaya | ✅ Valid |
| 5-6 | Cukup Konsisten | Peserta kurang fokus, hasil perlu diperiksa lebih lanjut | ❌ Tidak Valid |
| 3-4 | Kurang Konsisten | Peserta menjawab tidak konsisten, hasil tidak dapat dipercaya | ❌ Tidak Valid |
| 0-2 | Tidak Konsisten | Peserta menjawab secara acak, hasil tidak valid sama sekali | ❌ Tidak Valid |

---

### Penyebab Ketidakkonsistenan

1. **Menjawab Asal-asalan**
   - Tidak membaca instruksi dengan benar
   - Terburu-buru menyelesaikan tes

2. **Tidak Memahami Instruksi**
   - Bingung cara memberikan peringkat
   - Salah interpretasi kategori pekerjaan

3. **Kelelahan/Tidak Fokus**
   - Mengerjakan terlalu lama
   - Kehilangan konsentrasi di tengah tes

4. **Mencoba Memanipulasi Hasil**
   - Berusaha mengarahkan hasil ke kategori tertentu
   - Menjawab tidak jujur

---

## 7. Threshold Validitas

### Pengaturan Saat Ini

```php
// File: app/Http/Controllers/KarirController.php
// Line: 93

'validitas' => $hasil->skor_konsistensi >= 7 ? 'Valid' : 'Tidak Valid'
```

### Penjelasan Threshold

| Threshold | Keterangan |
|-----------|------------|
| **>= 7** | **Rekomendasi** - Keseimbangan antara ketat dan longgar |
| >= 8 | Lebih ketat - Hanya hasil sangat konsisten yang valid |
| >= 6 | Lebih longgar - Menerima hasil dengan konsistensi sedang |

### Mengapa Memilih Threshold >= 7?

1. **Standar Psikometri**
   - Skor 7/10 = 70% konsistensi
   - Sesuai dengan standar tes psikologi

2. **Keseimbangan**
   - Tidak terlalu ketat (menghasilkan terlalu banyak "Tidak Valid")
   - Tidak terlalu longgar (menerima hasil yang tidak konsisten)

3. **Praktis**
   - Memudahkan interpretasi
   - Sesuai dengan kebutuhan konseling karir

---

### Riwayat Perubahan Threshold

| Tanggal | Threshold Lama | Threshold Baru | Alasan |
|---------|----------------|----------------|---------|
| 2025-01-12 | >= 11 | >= 7 | Threshold 11 tidak pernah tercapai (max skor = 10) |

---

## 8. FAQ

### Q1: Apa yang harus dilakukan jika hasil tes "Tidak Valid"?

**Jawaban:**
1. Informasikan kepada peserta bahwa hasil tidak dapat dipercaya
2. Sarankan untuk mengulang tes dengan lebih fokus
3. Berikan penjelasan yang jelas tentang cara mengerjakan tes
4. Pastikan peserta memahami instruksi sebelum memulai

---

### Q2: Apakah skor konsistensi mempengaruhi hasil minat karir?

**Jawaban:**
Tidak. Skor konsistensi **TIDAK** mempengaruhi perhitungan kategori minat (Scientific, Outdoor, dll). Skor konsistensi hanya menentukan apakah hasil tersebut **dapat dipercaya** atau tidak.

Contoh:
- Peserta A: Scientific = Peringkat 1, Skor Konsistensi = 3 (Tidak Valid)
- Peserta B: Scientific = Peringkat 1, Skor Konsistensi = 9 (Valid)

Keduanya sama-sama mendapat Scientific sebagai minat tertinggi, tapi hasil Peserta B lebih dapat dipercaya.

---

### Q3: Bisakah skor konsistensi dijadikan sebagai indikator kepribadian?

**Jawaban:**
Tidak disarankan. Skor konsistensi hanya mengukur **konsistensi jawaban**, bukan trait kepribadian. Skor rendah bisa disebabkan oleh:
- Kelelahan
- Kurang fokus
- Tidak memahami instruksi
- Bukan indikator kepribadian yang buruk

---

### Q4: Mengapa ada peserta dengan skor konsistensi 0?

**Jawaban:**
Skor 0 berarti peserta memberikan peringkat yang sangat tidak konsisten, hampir random. Kemungkinan:
- Menjawab tanpa membaca pertanyaan
- Memberikan peringkat secara acak
- Tidak serius dalam mengerjakan tes

Dalam kasus ini, hasil tes **tidak dapat digunakan** sama sekali.

---

### Q5: Apakah threshold validitas bisa diubah?

**Jawaban:**
Ya. Threshold dapat disesuaikan tergantung kebutuhan:

**Untuk Mengubah:**
```php
// File: app/Http/Controllers/KarirController.php
// Line: 93

// Threshold saat ini: >= 7
'validitas' => $hasil->skor_konsistensi >= 7 ? 'Valid' : 'Tidak Valid'

// Ubah angka 7 sesuai kebutuhan:
// >= 8 untuk lebih ketat
// >= 6 untuk lebih longgar
```

**Pertimbangan:**
- Threshold terlalu tinggi → Banyak hasil valid yang ditolak
- Threshold terlalu rendah → Hasil tidak konsisten ikut diterima

---

### Q6: Bagaimana cara mencegah hasil "Tidak Valid"?

**Jawaban:**

**Sebelum Tes:**
1. Jelaskan instruksi dengan jelas
2. Berikan contoh cara menjawab
3. Pastikan peserta dalam kondisi fokus
4. Sediakan waktu yang cukup

**Selama Tes:**
1. Monitor peserta secara berkala
2. Ingatkan untuk fokus dan konsisten
3. Berikan jeda istirahat jika diperlukan

**Setelah Tes:**
1. Review hasil bersama peserta
2. Jika tidak valid, diskusikan penyebabnya
3. Tawarkan untuk mengulang tes

---

### Q7: Apakah sistem deteksi validitas sudah teruji secara psikometri?

**Jawaban:**
Metode yang digunakan adalah **Analisis Varians**, yang merupakan metode statistik standar dalam psikometri. Namun, untuk penggunaan klinis/profesional, sebaiknya:

1. Lakukan validasi empiris dengan sampel yang besar
2. Bandingkan dengan metode validitas lain
3. Konsultasi dengan ahli psikometri
4. Dokumentasikan hasil validasi

Sistem saat ini cocok untuk:
- Screening awal
- Penggunaan pendidikan
- Konseling karir non-klinis

---

## 📚 Referensi

1. **Rothwell-Miller Interest Blank Manual** (Original RMIB Documentation)
2. **Anastasi, A., & Urbina, S.** (1997). *Psychological Testing*. Prentice Hall.
3. **Cronbach, L. J.** (1951). Coefficient alpha and the internal structure of tests. *Psychometrika*, 16(3), 297-334.
4. **DeVellis, R. F.** (2016). *Scale Development: Theory and Applications*. SAGE Publications.

---

## 📝 Catatan Pengembangan

### Lokasi File Terkait

```
app/
├── Services/
│   └── RmibScoringService.php (Line 141-173: Perhitungan Skor Konsistensi)
├── Http/
│   └── Controllers/
│       └── KarirController.php (Line 93: Threshold Validitas)
└── Models/
    └── RmibHasilTes.php (Field: skor_konsistensi)
```

### Changelog

| Tanggal | Versi | Perubahan |
|---------|-------|-----------|
| 2025-01-12 | 1.1 | Perbaikan threshold dari >= 11 menjadi >= 7 |
| 2025-01-12 | 1.0 | Dokumentasi awal dibuat |

---

**Dokumentasi ini dibuat pada: 12 Januari 2025**

**Terakhir diperbarui: 12 Januari 2025**

---

## 💡 Tips Penggunaan

1. **Untuk Admin/Konselor:**
   - Selalu cek skor konsistensi sebelum memberikan interpretasi
   - Jangan berikan rekomendasi karir dari hasil "Tidak Valid"
   - Diskusikan hasil dengan peserta, terutama jika skor rendah

2. **Untuk Developer:**
   - Jangan ubah threshold tanpa konsultasi dengan ahli psikologi
   - Dokumentasikan setiap perubahan pada sistem validitas
   - Lakukan testing dengan data riil sebelum deployment

3. **Untuk Peserta:**
   - Baca instruksi dengan cermat
   - Jawab dengan jujur dan konsisten
   - Jangan terburu-buru
   - Fokus pada preferensi pribadi, bukan ekspektasi orang lain

---

**© 2025 PPSDM ITERA - Sistem Peminatan Karir RMIB**
