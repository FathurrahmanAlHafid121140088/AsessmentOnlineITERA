Deskripsi Logika Perhitungan Matrix RMIB (Metode 'x')
Metode ini mengubah 9 set peringkat (Kluster A-I) yang diisi pengguna menjadi 12 skor Kategori (Outdoor, Mechanical, dst.) menggunakan sebuah matrix perhitungan 12x9 (12 baris Kategori x 9 kolom Kluster).

1. Komponen Utama
   Matrix Skor (12x9):

Baris (12): Mewakili 12 Kategori Minat (Outdoor, Mechanical, Computational, Scientific, Personal Contact, Aesthetic, Literary, Musical, Social Service, Clerical, Practical, Medical).

Kolom (9): Mewakili 9 Kluster Pekerjaan (A, B, C, D, E, F, G, H, I).

Titik Awal "x":

Ini adalah "kunci jawaban" atau offset untuk pengisian.

Setiap kolom Kluster (A-I) memiliki satu baris "x" yang menandai titik awal untuk memasukkan data peringkat dari kluster tersebut.

Input Pengguna:

9 set peringkat. Setiap set (Kluster A, Kluster B, dst.) berisi 12 angka peringkat (1 s/d 12) yang diisi oleh pengguna.

2. Proses Pengisian Matrix 📝
   Proses pengisian data dari input pengguna ke dalam matrix dilakukan secara VERTIKAL (kolom per kolom).

Langkah A: Pengisian Kolom Kluster A
Ambil 12 angka peringkat yang diisi pengguna untuk Kluster A.

Lihat "kunci jawaban" untuk menemukan di baris mana letak "x" pada Kolom A. (Misalnya, "x" ada di baris Computational).

Mulai dari sel "x" tersebut (di kolom A), masukkan 12 peringkat dari Kluster A secara vertikal ke bawah.

Aturan "Wrap-Around": Jika pengisian sudah mencapai baris terakhir (baris 12, Medical), peringkat berikutnya akan "melompat" kembali ke baris pertama (baris 1, Outdoor) dan melanjutkan pengisian ke bawah di kolom yang sama (Kolom A) sampai semua 12 peringkat terisi.

Langkah B: Pengisian Kolom Kluster B
Ambil 12 angka peringkat yang diisi pengguna untuk Kluster B.

Lihat "kunci jawaban" untuk menemukan di baris mana letak "x" pada Kolom B. (Misalnya, "x" ada di baris Outdoor).

Mulai dari sel "x" tersebut (di kolom B), masukkan 12 peringkat dari Kluster B secara vertikal ke bawah, menggunakan aturan "wrap-around" yang sama jika sudah mencapai baris terakhir.

Langkah C s/d I: Pengisian Kolom Berikutnya
Proses yang sama diulangi untuk semua kluster (C, D, E, F, G, H, I) sampai seluruh 108 sel di dalam matrix 12x9 terisi penuh.

3. Proses Kalkulasi Skor Akhir 🔢
   Setelah seluruh matrix terisi, proses kalkulasi skor dilakukan.

Langkah 1: Penjumlahan Skor (SUM) ➡️
Skor dijumlahkan secara HORIZONTAL (dari kiri ke kanan) untuk setiap baris Kategori.

Total skor Kategori Outdoor = (Nilai di A, Outdoor) + (Nilai di B, Outdoor) + ... + (Nilai di I, Outdoor).

Total skor Kategori Mechanical = (Nilai di A, Mechanical) + (Nilai di B, Mechanical) + ... + (Nilai di I, Mechanical).

...dan seterusnya untuk 12 kategori.

Hasilnya adalah 12 Skor Total (SUM) untuk 12 Kategori Minat.

Langkah 2: Penentuan Peringkat
Peringkat ditentukan berdasarkan skor SUM terkecil.

Kategori yang memiliki Total Skor (SUM) terkecil adalah minat yang paling dominan dan mendapatkan Peringkat 1.

Kategori dengan skor SUM terbesar mendapat Peringkat 12.

Langkah 3: Aturan Peringkat Sama (Ties)
Jika terdapat dua atau lebih Kategori dengan skor SUM yang identik, peringkat mereka akan dihitung menggunakan aturan unik +0.5.

Contoh: Jika Kategori A dan B sama-sama berada di Peringkat 2 (karena Kategori C di Peringkat 1), maka peringkat Kategori A dan B akan menjadi 2.5. Peringkat berikutnya akan dimulai dari 4.
