# MHI-38 Item Classification

## Overview
Mental Health Inventory (MHI-38) adalah instrumen penilaian kesehatan mental yang terdiri dari 38 pertanyaan. Item-item ini dibagi menjadi dua kategori utama berdasarkan aspek psikologis yang diukur.

## Klasifikasi Item

### 1. Psychological Distress (Tekanan Psikologis) - 24 Items

**Item Negatif** - Mengukur aspek-aspek negatif dari kesehatan mental:

| No. Soal | Aspek yang Diukur |
|----------|-------------------|
| 2, 3     | Kesepian dan Kecemasan |
| 8, 9     | Ketegangan dan Kegelisahan |
| 11, 13   | Energi dan Kontrol Emosi |
| 14, 15   | Depresi dan Kehilangan Harapan |
| 16, 18   | Kelelahan Mental |
| 19, 20, 21 | Ketegangan dan Stres |
| 24       | Kontrol Hidup |
| 25, 27   | Kegugupan dan Depresi |
| 28, 29, 30 | Pemikiran Bunuh Diri dan Kegelisahan |
| 32, 33   | Kebingungan dan Kecemasan |
| 35, 36   | Kebutuhan Menenangkan Diri dan Kesedihan |
| 38       | Tekanan/Stres |

**Total: 24 pertanyaan negatif**

**Array untuk Kode:**
```php
$negativeQuestions = [2, 3, 8, 9, 11, 13, 14, 15, 16, 18, 19, 20, 21, 24, 25, 27, 28, 29, 30, 32, 33, 35, 36, 38];
```

---

### 2. Psychological Well-being (Kesejahteraan Psikologis) - 14 Items

**Item Positif** - Mengukur aspek-aspek positif dari kesehatan mental:

| No. Soal | Aspek yang Diukur |
|----------|-------------------|
| 1        | Kebahagiaan dan Kepuasan Hidup |
| 4        | Harapan terhadap Masa Depan |
| 5        | Minat terhadap Kehidupan |
| 6        | Relaksasi dan Kebebasan dari Ketegangan |
| 7        | Energi dan Semangat |
| 10       | Ketenangan |
| 12       | Kehidupan yang Menarik |
| 17       | Stabilitas Emosi |
| 22       | Kemampuan Relaksasi |
| 23       | Hubungan Cinta yang Utuh |
| 26       | Petualangan Hidup |
| 31       | Keceriaan dan Kegembiraan |
| 34       | Kebahagiaan |
| 37       | Tidur Berkualitas |

**Total: 14 pertanyaan positif**

**Array untuk Kode:**
```php
$positiveQuestions = [1, 4, 5, 6, 7, 10, 12, 17, 22, 23, 26, 31, 34, 37];
```

---

## Scoring Guidelines

### Skala Likert
Setiap pertanyaan menggunakan skala 1-6:
- 1 = Tidak Pernah
- 2 = Sangat Jarang
- 3 = Kadang-kadang
- 4 = Agak Sering
- 5 = Sering
- 6 = Selalu

### Interpretasi Skor Total (Range: 38-228)

| Skor Total | Kategori | Interpretasi |
|-----------|----------|--------------|
| 190-228 | Sangat Sehat | Kesehatan mental sangat baik |
| 152-189 | Sehat | Kesehatan mental baik |
| 114-151 | Cukup Sehat | Kesehatan mental cukup, perlu perhatian |
| 76-113 | Perlu Dukungan | Disarankan konsultasi dengan konselor |
| 38-75 | Perlu Dukungan Intensif | Sangat disarankan segera konsultasi profesional |

---

## Referensi

Klasifikasi ini berdasarkan pada:
1. **RAND Mental Health Inventory (MHI-38)** - Struktur asli instrumen
2. **Adaptation of the Mental Health Inventory (MHI-38) for Adolescents - Indonesian Version** (PMC10351590)
3. **RAND Corporation** - Original MHI-38 documentation

### Catatan Penting:
- **Total item**: 38 pertanyaan
- **Item negatif (Distress)**: 24 pertanyaan
- **Item positif (Well-being)**: 14 pertanyaan
- **Total**: 24 + 14 = 38 ✓

---

## Implementasi dalam Sistem

File yang menggunakan klasifikasi ini:
- `app/Http/Controllers/HasilKuesionerCombinedController.php` (line 391-396)
- `resources/views/admin-mental-health-detail.blade.php`
- `tests/Feature/HasilKuesionerCombinedControllerTest.php` (line 760-762)

---

**Terakhir Diperbarui:** 13 November 2025
**Versi:** 1.0
**Status:** ✅ Validated & Tested
