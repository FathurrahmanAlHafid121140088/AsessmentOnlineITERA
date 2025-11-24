# 📊 Dokumentasi Radar Chart RMIB (Reverse/Inverted)

**Sistem Visualisasi Profil Minat Peminatan Karir**

---

## 📖 Daftar Isi

1. [Apa itu Radar Chart?](#1-apa-itu-radar-chart)
2. [Mengapa Menggunakan Reverse/Inverted Chart?](#2-mengapa-menggunakan-reverseinverted-chart)
3. [Cara Kerja Reverse Chart](#3-cara-kerja-reverse-chart)
4. [Cara Membaca Chart](#4-cara-membaca-chart)
5. [Contoh Interpretasi](#5-contoh-interpretasi)
6. [Implementasi Teknis](#6-implementasi-teknis)
7. [FAQ](#7-faq)

---

## 1. Apa itu Radar Chart?

**Radar Chart** (atau Spider Chart/Web Chart) adalah diagram yang menampilkan data multivariat dalam bentuk polygon 2D. Sangat cocok untuk menampilkan profil perbandingan antar kategori.

### Keunggulan Radar Chart untuk RMIB

| Keunggulan | Penjelasan |
|------------|------------|
| **Visual Intuitif** | Bentuk polygon menunjukkan pola minat dengan jelas |
| **Perbandingan Mudah** | Semua 12 kategori bisa dibandingkan sekaligus |
| **Identifikasi Cepat** | Area besar = Minat tinggi (setelah di-reverse) |
| **Profil Holistik** | Melihat kekuatan dan kelemahan minat secara keseluruhan |

---

## 2. Mengapa Menggunakan Reverse/Inverted Chart?

### Problem Awal

Dalam sistem RMIB:
- **Skor kecil** = Minat **TINGGI** (peringkat 1 = paling diminati)
- **Skor besar** = Minat **RENDAH** (peringkat 12 = tidak diminati)

Jika ditampilkan langsung di radar chart:
- Area **kecil** akan menunjukkan minat tinggi ❌ **Membingungkan!**
- Area **besar** akan menunjukkan minat rendah ❌ **Counter-intuitive!**

### Solusi: Reverse/Invert Data

Dengan **membalik/inverse** data:
- Area **BESAR** = Minat **TINGGI** ✅ **Mudah dipahami!**
- Area **KECIL** = Minat **RENDAH** ✅ **Intuitif!**

---

## 3. Cara Kerja Reverse Chart

### Formula Inverse

```javascript
// Data asli (skor RMIB)
const originalData = [25, 50, 30, 80, 45, 55, 60, 70, 40, 65, 75, 35];

// Cari nilai max dan min
const maxScore = Math.max(...originalData); // 80
const minScore = Math.min(...originalData); // 25
const sumRange = maxScore + minScore;        // 105

// Inverse setiap nilai
const invertedData = originalData.map(score => sumRange - score);

// Hasil inverted:
// [80, 55, 75, 25, 60, 50, 45, 35, 65, 40, 30, 70]
```

### Diagram Konsep

```
SEBELUM REVERSE (Membingungkan):
┌─────────────────────────────────┐
│ Scientific: Skor 25 (rendah)    │
│ → Area KECIL di chart           │
│ → Padahal minat TINGGI! ❌      │
└─────────────────────────────────┘

SETELAH REVERSE (Jelas):
┌─────────────────────────────────┐
│ Scientific: Skor 25 (rendah)    │
│ → Di-inverse jadi 80 (tinggi)   │
│ → Area BESAR di chart           │
│ → Sesuai dengan minat TINGGI! ✅│
└─────────────────────────────────┘
```

---

## 4. Cara Membaca Chart

### Visual Guide

```
         Scientific (Minat Tinggi)
                  🔺
                 /  \
                /    \
         AREA  /      \  AREA
        BESAR /        \ KECIL
             /          \
            /            \
    Musical ------------------- Outdoor
           \              /
            \            /
             \          /
              \        /
               \      /
                \    /
                 \  /
                  🔻
            Medical (Minat Rendah)
```

### Keterangan:
- **Area Menonjol/Besar** = Kategori minat **TINGGI** ✅
- **Area Kecil/Rendah** = Kategori minat **RENDAH** ❌
- **Bentuk Tidak Simetris** = Normal, karena setiap orang punya minat berbeda

---

## 5. Contoh Interpretasi

### Contoh 1: Profil Sains & Teknologi

**Nama:** Budi
**Skor Asli (RMIB):**

| Kategori | Skor Asli | Inverted | Interpretasi |
|----------|-----------|----------|--------------|
| Scientific | 20 | 88 | ⬆️ **Minat Sangat Tinggi** |
| Computational | 25 | 83 | ⬆️ **Minat Sangat Tinggi** |
| Mechanical | 35 | 73 | ⬆️ Minat Tinggi |
| Literary | 50 | 58 | ➡️ Minat Sedang |
| Musical | 75 | 33 | ⬇️ Minat Rendah |
| Outdoor | 80 | 28 | ⬇️ **Minat Sangat Rendah** |

**Visualisasi Radar Chart:**
```
         Scientific ⭐
              🔺
             /  \
            /    \
           /      \
    Comp. 🔺      \
         /         \
        /           \
       /             \
      /               \ Outdoor
     /                 🔻
```

**Interpretasi:**
- Polygon **menonjol** di area Scientific, Computational, Mechanical
- Polygon **mengecil** di area Outdoor, Musical
- **Rekomendasi:** Karir di bidang sains, teknik, atau teknologi

---

### Contoh 2: Profil Seni & Kreativitas

**Nama:** Ani
**Skor Asli (RMIB):**

| Kategori | Skor Asli | Inverted | Interpretasi |
|----------|-----------|----------|--------------|
| Aesthetic | 18 | 90 | ⬆️ **Minat Sangat Tinggi** |
| Musical | 22 | 86 | ⬆️ **Minat Sangat Tinggi** |
| Literary | 30 | 78 | ⬆️ Minat Tinggi |
| Computational | 70 | 38 | ⬇️ Minat Rendah |
| Scientific | 75 | 33 | ⬇️ **Minat Sangat Rendah** |

**Visualisasi Radar Chart:**
```
         Aesthetic ⭐
              🔺
             /  \
            /    \
     Musical 🔺  \
            \     \
             \     \
              \     \
               \     \ Scientific
                \     🔻
                 \   /
                  \ /
                Literary
                   🔺
```

**Interpretasi:**
- Polygon **menonjol** di area Aesthetic, Musical, Literary
- Polygon **mengecil** di area Scientific, Computational
- **Rekomendasi:** Karir di bidang seni, musik, desain, atau menulis

---

## 6. Implementasi Teknis

### File Terkait

```
resources/views/user-peminatan-karir.blade.php
├── Line 205-228: HTML struktur chart
└── Line 303-412: JavaScript untuk render chart
```

### Kode JavaScript (Simplified)

```javascript
// 1. Ambil data asli dari backend
const originalData = @json($radarData);

// 2. Hitung inverse
const maxScore = Math.max(...originalData);
const minScore = Math.min(...originalData);
const sumRange = maxScore + minScore;

const invertedData = originalData.map(score => sumRange - score);

// 3. Render chart dengan data inverted
new Chart(canvas, {
    type: 'radar',
    data: {
        labels: @json($radarLabels),
        datasets: [{
            label: 'Level Minat',
            data: invertedData // ← Data yang sudah di-inverse
        }]
    },
    options: {
        scales: {
            r: {
                ticks: {
                    display: false // Sembunyikan angka karena sudah di-inverse
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    // Tampilkan skor asli di tooltip
                    label: function(context) {
                        const index = context.dataIndex;
                        const originalScore = originalData[index];
                        return 'Skor RMIB: ' + originalScore +
                               ' (Semakin kecil = Semakin diminati)';
                    }
                }
            }
        }
    }
});
```

### Fitur Penting

| Fitur | Penjelasan |
|-------|------------|
| **Data Inverted** | Data di-inverse untuk visualisasi yang intuitif |
| **Tooltip Custom** | Menampilkan skor asli (bukan inverted) saat hover |
| **Ticks Hidden** | Angka axis disembunyikan karena sudah tidak relevan |
| **Legend Updated** | Label menjadi "Level Minat" (bukan "Skor Minat") |

---

## 7. FAQ

### Q1: Mengapa tidak menggunakan skor asli saja di chart?

**Jawaban:**
Karena di sistem RMIB:
- Skor kecil = Minat TINGGI (counter-intuitive)
- Jika ditampilkan langsung, area kecil akan menunjukkan minat tinggi
- Ini membingungkan user yang terbiasa membaca chart standar

Dengan inverse:
- Area besar = Minat tinggi (sesuai ekspektasi umum) ✅
- Lebih mudah dipahami oleh user awam

---

### Q2: Apakah inverse mengubah hasil tes?

**Jawaban:**
**TIDAK!** Inverse hanya untuk **visualisasi** saja. Data asli tetap tersimpan di database dan tidak berubah.

Yang berubah hanya tampilan di chart:
- Database: Tetap simpan skor asli (20, 50, 80, dll)
- Chart: Tampilkan skor inverted untuk visualisasi
- Tooltip: Tetap tampilkan skor asli

---

### Q3: Bagaimana cara memverifikasi chart sudah benar?

**Jawaban:**
Lakukan test berikut:

1. **Hover pada kategori dengan skor kecil** (minat tinggi)
   - Tooltip harus menampilkan skor kecil
   - Area di chart harus besar/menonjol

2. **Hover pada kategori dengan skor besar** (minat rendah)
   - Tooltip harus menampilkan skor besar
   - Area di chart harus kecil/mengecil

Jika kedua kondisi terpenuhi, chart sudah benar! ✅

---

### Q4: Apakah bisa menggunakan chart lain selain radar?

**Jawaban:**
Ya, alternatif lain:

| Chart Type | Kelebihan | Kekurangan |
|-----------|-----------|------------|
| **Bar Chart** | Mudah dibaca, tidak perlu inverse | Sulit membandingkan 12 kategori sekaligus |
| **Line Chart** | Baik untuk trend | Tidak cocok untuk perbandingan kategori |
| **Radar Chart** | Profil visual jelas, perbandingan mudah | Perlu penjelasan cara membaca |

Radar chart tetap **paling cocok** untuk menampilkan profil minat RMIB.

---

### Q5: Mengapa angka di axis disembunyikan?

**Jawaban:**
Karena angka di axis adalah hasil inverse yang tidak memiliki makna langsung:

- Angka 88 di chart = Skor asli 20 (hasil inverse)
- Angka 33 di chart = Skor asli 75 (hasil inverse)

Menampilkan angka inverse akan **membingungkan** user. Lebih baik:
- Sembunyikan angka axis
- Fokus pada **bentuk polygon** (besar/kecil)
- Tampilkan skor asli di **tooltip** saat hover

---

### Q6: Bagaimana jika semua skor sama?

**Jawaban:**
Jika semua kategori mendapat skor yang sama (contoh: semua 50):

```
Skor asli: [50, 50, 50, 50, 50, ...]
Inverted:  [50, 50, 50, 50, 50, ...]
```

Chart akan berbentuk **lingkaran sempurna** (semua area sama).

Ini menunjukkan peserta **tidak memiliki preferensi khusus** atau:
- Menjawab semua pertanyaan dengan sama
- Tidak konsisten (cek skor konsistensi!)

---

### Q7: Apakah perlu menjelaskan inverse ke user?

**Jawaban:**
**TIDAK perlu** menjelaskan detail teknis inverse ke user awam. Cukup berikan keterangan sederhana:

✅ **Yang Perlu Dijelaskan:**
> "Area yang lebih besar menunjukkan minat yang lebih tinggi."

❌ **Tidak Perlu Dijelaskan:**
> "Data telah di-inverse menggunakan formula (max + min) - original untuk membalik skala..."

User tidak perlu tahu teknis di balik layar. Mereka hanya perlu:
1. Lihat chart
2. Identifikasi area besar (minat tinggi)
3. Pahami profil minat mereka

---

## 📊 Ringkasan

| Aspek | Detail |
|-------|--------|
| **Metode** | Inverse/Reverse data visualization |
| **Formula** | `inverted = (max + min) - original` |
| **Tujuan** | Membuat chart lebih intuitif (area besar = minat tinggi) |
| **Data Asli** | Tetap tersimpan di database, tidak berubah |
| **Tooltip** | Menampilkan skor asli (bukan inverted) |
| **Interpretasi** | Area menonjol = Minat tinggi, Area kecil = Minat rendah |

---

## 🎯 Checklist Implementasi

- [x] Data di-inverse sebelum ditampilkan di chart
- [x] Tooltip menampilkan skor asli + penjelasan
- [x] Ticks axis disembunyikan
- [x] Label chart diubah menjadi "Level Minat"
- [x] Keterangan cara membaca ditambahkan di UI
- [x] Testing dengan data riil

---

## 📚 Referensi

1. **Chart.js Radar Chart Documentation**
   - https://www.chartjs.org/docs/latest/charts/radar.html

2. **Data Visualization Best Practices**
   - Tufte, E. R. (2001). *The Visual Display of Quantitative Information*

3. **Psychometric Visualization**
   - Cleveland, W. S. (1993). *Visualizing Data*

---

**Dokumentasi dibuat pada: 12 Januari 2025**

**Terakhir diperbarui: 12 Januari 2025**

---

**© 2025 PPSDM ITERA - Sistem Peminatan Karir RMIB**
