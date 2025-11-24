Logika Perhitungan dan Penilaian Tes RMIB

1. Pendahuluan
   Dokumen ini merinci logika perhitungan untuk aplikasi digital Tes Minat Rothwell-Miller (RMIB). Tujuan dari perhitungan ini adalah untuk mengubah 108 peringkat pekerjaan yang diisi oleh pengguna menjadi skor total dan peringkat akhir untuk 12 kategori minat.

2. Struktur Data Utama
   Perhitungan ini bergantung pada tiga tabel utama di database:

karir_data_diri: Menyimpan data demografis peserta, terutama id, nim, dan jenis_kelamin (L/P).

rmib_pekerjaan (Kunci Jawaban): Tabel master yang memetakan setiap nama pekerjaan ke kategori RMIB yang benar, berdasarkan kelompok (A-I) dan gender (L/P). Kolom penting: kelompok, gender, nama_pekerjaan, kategori.

rmib_jawaban_peserta (Input Pengguna): Menyimpan 108 baris jawaban mentah dari pengguna untuk setiap sesi tes. Kolom penting: hasil_id, kelompok, pekerjaan, peringkat (nilai 1-12).

rmib_hasil_tes (Output): Menyimpan ringkasan hasil akhir tes. Kolom penting: id, karir_data_diri_id, top_1_pekerjaan, top_2_pekerjaan, top_3_pekerjaan, skor_konsistensi, interpretasi.

3. Alur Perhitungan Skor Kategori (Inti)
   Logika ini dijalankan di dalam controller (misalnya, KarirController) setelah pengguna mengirimkan form tes (storeJawaban).

Langkah 1: Inisialisasi
Ambil hasil_tes_id (dari record RmibHasilTes yang baru dibuat) dan gender (dari KarirDataDiri).

Buat Peta Kunci Jawaban: Ambil semua pekerjaan dari tabel rmib_pekerjaan yang sesuai dengan gender pengguna. Ubah ini menjadi array asosiatif (peta) agar mudah dicari.

PHP

// $masterPekerjaan akan terlihat seperti:
// ['Petani' => 'Outdoor', 'Akuntan' => 'Computational', 'Ilmuwan' => 'Scientific', ...]
$masterPekerjaan = RmibPekerjaan::where('gender', $gender)
->pluck('kategori', 'nama_pekerjaan');
Buat Peta Skor Kategori: Inisialisasi sebuah array untuk menampung total skor 12 kategori, semuanya dimulai dari 0.

PHP

$skorKategori = [
'Outdoor' => 0, 'Mechanical' => 0, 'Computational' => 0, 'Scientific' => 0,
'Personal Contact' => 0, 'Aesthetic' => 0, 'Literary' => 0, 'Musical' => 0,
'Social Service' => 0, 'Clerical' => 0, 'Practical' => 0, 'Medical' => 0,
];
Langkah 2: Akumulasi Skor
Ambil semua (108) jawaban mentah pengguna dari tabel rmib_jawaban_peserta berdasarkan hasil_tes_id.

Lakukan loop pada setiap jawaban:

PHP

foreach ($jawabanPeserta as $jawaban) {
$namaPekerjaan = $jawaban->pekerjaan;
$peringkat = $jawaban->peringkat; // Nilai 1-12

    // Cari kategori untuk pekerjaan ini menggunakan Peta Kunci Jawaban
    if (isset($masterPekerjaan[$namaPekerjaan])) {
        $kategori = $masterPekerjaan[$namaPekerjaan];

        // Tambahkan nilai peringkat ke total skor kategori yang sesuai
        $skorKategori[$kategori] += $peringkat;
    }

}
Setelah loop selesai, $skorKategori akan berisi Total Skor SUM untuk masing-masing dari 12 kategori.

Langkah 3: Perhitungan Peringkat (Aturan Unik "+0.5")
Urutkan Skor: Urutkan array $skorKategori berdasarkan nilainya (skor SUM) dari yang terkecil ke terbesar. Kategori dengan skor terkecil adalah yang paling diminati.

PHP

asort($skorKategori); // Mengurutkan array, mempertahankan key (nama kategori)
Terapkan Aturan Peringkat: Lakukan loop pada array yang sudah terurut untuk memberikan peringkat, sambil menangani nilai yang sama (ties) dengan aturan khusus "+0.5".

Logika Aturan "+0.5":

Peringkat dimulai dari 1.

Jika beberapa kategori memiliki nilai SUM yang sama, peringkat dasar mereka ditentukan.

Semua kategori yang sama tersebut akan mendapatkan peringkat dasar + 0.5.

Peringkat berikutnya akan melompati jumlah kategori yang sama tersebut.

Contoh Implementasi Aturan:

Skor: {'A': 10, 'B': 12, 'C': 12, 'D': 12, 'E': 15, 'F': 16}

Hasil Peringkat:

A: Peringkat 1

B: Peringkat 2.5 (Dasarnya 2, ditambah 0.5)

C: Peringkat 2.5 (Dasarnya 2, ditambah 0.5)

D: Peringkat 2.5 (Dasarnya 2, ditambah 0.5)

E: Peringkat 5 (Melompati 3 kategori sebelumnya: 2, 3, 4 -> mulai dari 5)

F: Peringkat 6

4. Perhitungan Skor Konsistensi
   Skor konsistensi dihitung secara terpisah untuk mengukur seberapa konsisten pengguna dalam menilai pekerjaan-pekerjaan yang termasuk dalam kategori yang sama.

Kumpulkan Data: Selama Langkah 2 (Akumulasi Skor), kumpulkan juga semua peringkat mentah (1-12) untuk setiap kategori ke dalam array terpisah.

PHP

// $peringkatPerKategori akan terlihat seperti:
// [
// 'Outdoor' => [5, 2, 8, ... (9 angka)],
// 'Mechanical' => [1, 7, 3, ... (9 angka)],
// ...
// ]
Hitung Varians: Untuk setiap kategori, hitung nilai varians (atau simpangan baku) dari 9 peringkat yang didapatkannya. Varians yang tinggi menunjukkan pengguna tidak konsisten (misal: memberi peringkat 1 dan 12 untuk dua pekerjaan di kategori yang sama).

Normalisasi: Jumlahkan total varians dari ke-12 kategori. Konversikan total varians ini menjadi skor yang mudah dibaca (misalnya, skala 0-10), di mana varians rendah (konsisten) menghasilkan skor tinggi (misal: 10/10).

5. Penyimpanan Hasil Akhir
   Setelah $skorPeringkat dan $skorKonsistensi dihitung, ambil 3 kategori dengan peringkat teratas.

Buat teks $interpretasi sederhana berdasarkan 3 kategori teratas tersebut.

Lakukan update() pada record RmibHasilTes yang dibuat di awal untuk mengisi kolom skor_konsistensi dan interpretasi dengan hasil perhitungan yang baru saja didapat.

Arahkan pengguna ke halaman hasil (karir.hasil), yang akan menampilkan data dari RmibHasilTes ini.
