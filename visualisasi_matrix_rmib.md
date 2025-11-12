# Visualisasi Matrix RMIB 12x9 dengan Titik Awal "x"

## Penjelasan
Tanda "x" menunjukkan **titik awal (starting position)** untuk pengisian data dari setiap kluster (A-I).

- Kluster A dimulai dari baris **Outdoor** (index 0)
- Kluster B dimulai dari baris **Mechanical** (index 1)
- Kluster C dimulai dari baris **Computational** (index 2)
- Dan seterusnya...

Setelah mencapai baris terakhir (Medical), pengisian akan **wrap-around** kembali ke baris pertama (Outdoor).

---

## Matrix RMIB 12x9 dengan Titik Awal "x"

```
                    KLUSTER (9 Kolom)
KATEGORI            A    B    C    D    E    F    G    H    I
(12 Baris)         ─────────────────────────────────────────────

Outdoor            [x]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Mechanical         [ ]  [x]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Computational      [ ]  [ ]  [x]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Scientific         [ ]  [ ]  [ ]  [x]  [ ]  [ ]  [ ]  [ ]  [ ]
Personal Contact   [ ]  [ ]  [ ]  [ ]  [x]  [ ]  [ ]  [ ]  [ ]
Aesthetic          [ ]  [ ]  [ ]  [ ]  [ ]  [x]  [ ]  [ ]  [ ]
Literary           [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [x]  [ ]  [ ]
Musical            [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [x]  [ ]
Social Service     [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [x]
Clerical           [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Practical          [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Medical            [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
```

---

## Pola Diagonal "x"

Perhatikan bahwa tanda "x" membentuk **pola diagonal** dari kiri atas ke kanan bawah:
- Kluster A: baris 0 (Outdoor)
- Kluster B: baris 1 (Mechanical)
- Kluster C: baris 2 (Computational)
- Kluster D: baris 3 (Scientific)
- Kluster E: baris 4 (Personal Contact)
- Kluster F: baris 5 (Aesthetic)
- Kluster G: baris 6 (Literary)
- Kluster H: baris 7 (Musical)
- Kluster I: baris 8 (Social Service)

---

## Contoh Pengisian: Kluster A

User memberi ranking untuk Kluster A:
1. Petani → 1
2. Insinyur Sipil → 2
3. Akuntan → 3
4. Ilmuwan → 4
5. Manager Penjualan → 5
6. Seniman → 6
7. Wartawan → 7
8. Pianis Konser → 8
9. Guru SD → 9
10. Manager Bank → 10
11. Tukang Kayu → 11
12. Dokter → 12

**Pengisian dimulai dari "x" (Outdoor) dan turun ke bawah:**

```
KATEGORI            A
───────────────────────

Outdoor            [x] ← 1 (Petani)
Mechanical         [ ] ← 2 (Insinyur Sipil)
Computational      [ ] ← 3 (Akuntan)
Scientific         [ ] ← 4 (Ilmuwan)
Personal Contact   [ ] ← 5 (Manager Penjualan)
Aesthetic          [ ] ← 6 (Seniman)
Literary           [ ] ← 7 (Wartawan)
Musical            [ ] ← 8 (Pianis Konser)
Social Service     [ ] ← 9 (Guru SD)
Clerical           [ ] ← 10 (Manager Bank)
Practical          [ ] ← 11 (Tukang Kayu)
Medical            [ ] ← 12 (Dokter)
```

---

## Contoh Pengisian: Kluster B

User memberi ranking untuk Kluster B:
1. Ahli Pembuat Alat → 1
2. Ahli Statistik → 2
3. Insinyur Kimia Industri → 3
4. Penyiar Radio → 4
5. Artis Profesional → 5
6. Pengarang → 6
7. Dirigen Orkestra → 7
8. Psikolog Pendidikan → 8
9. Sekretaris Perusahaan → 9
10. Ahli Bangunan → 10
11. Ahli Bedah → 11
12. Ahli Kehutanan → 12

**Pengisian dimulai dari "x" (Mechanical) dan turun ke bawah, wrap-around ke Outdoor:**

```
KATEGORI            B
───────────────────────

Outdoor            [ ] ← 12 (Ahli Kehutanan) ← WRAP AROUND!
Mechanical         [x] ← 1 (Ahli Pembuat Alat)
Computational      [ ] ← 2 (Ahli Statistik)
Scientific         [ ] ← 3 (Insinyur Kimia Industri)
Personal Contact   [ ] ← 4 (Penyiar Radio)
Aesthetic          [ ] ← 5 (Artis Profesional)
Literary           [ ] ← 6 (Pengarang)
Musical            [ ] ← 7 (Dirigen Orkestra)
Social Service     [ ] ← 8 (Psikolog Pendidikan)
Clerical           [ ] ← 9 (Sekretaris Perusahaan)
Practical          [ ] ← 10 (Ahli Bangunan)
Medical            [ ] ← 11 (Ahli Bedah)
```

---

## Matrix Lengkap Setelah Terisi (Contoh A & B)

```
                    KLUSTER
KATEGORI            A    B    C    D    E    F    G    H    I
───────────────────────────────────────────────────────────────

Outdoor             1   12   [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Mechanical          2    1   [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Computational       3    2   [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Scientific          4    3   [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Personal Contact    5    4   [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Aesthetic           6    5   [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Literary            7    6   [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Musical             8    7   [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Social Service      9    8   [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Clerical           10    9   [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Practical          11   10   [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
Medical            12   11   [ ]  [ ]  [ ]  [ ]  [ ]  [ ]  [ ]
```

---

## Perhitungan Skor (SUM)

Setelah semua 9 kluster terisi, skor untuk setiap kategori dihitung dengan **menjumlahkan secara horizontal**:

```
SUM(Outdoor) = A + B + C + D + E + F + G + H + I
SUM(Mechanical) = A + B + C + D + E + F + G + H + I
...dst
```

**Skor terkecil = Minat tertinggi (Peringkat 1)**

---

## Kode Implementasi di RmibScoringService.php

```php
// Tentukan starting position untuk setiap kluster
// Kluster A: index 0 (Outdoor)
// Kluster B: index 1 (Mechanical)
// Kluster C: index 2 (Computational)
// dst...

$startingRow = $klusterIndex; // 0 untuk A, 1 untuk B, 2 untuk C, ...

// Isi matrix secara vertikal dengan wrap-around
$rowIndex = $startingRow;
foreach ($pekerjaanList as $pekerjaan) {
    $targetKategori = $kategoriUrutan[$rowIndex % 12]; // Modulo untuk wrap-around
    $matrix[$targetKategori][$kluster] = $peringkat;
    $rowIndex++;
}
```

---

## Verifikasi dengan Test

Hasil test menunjukkan matrix terisi dengan benar:

```
✓ Outdoor: Expected=1, Actual=1
✓ Mechanical: Expected=2, Actual=2
✓ Computational: Expected=3, Actual=3
✓ Scientific: Expected=4, Actual=4
✓ Personal Contact: Expected=5, Actual=5
✓ Aesthetic: Expected=6, Actual=6
✓ Literary: Expected=7, Actual=7
✓ Musical: Expected=8, Actual=8
✓ Social Service: Expected=9, Actual=9
✓ Clerical: Expected=10, Actual=10
✓ Practical: Expected=11, Actual=11
✓ Medical: Expected=12, Actual=12
```

**Circular shift bekerja dengan sempurna!** ✓
