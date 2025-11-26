# Penjelasan Entity Relationship Diagram (ERD) - Sistem Asesmen Kesehatan Mental

## Gambaran Umum

ERD ini menggambarkan struktur database untuk sistem asesmen kesehatan mental mahasiswa. Database dirancang untuk menyimpan data mulai dari login pengguna, profil mahasiswa, riwayat keluhan, hingga hasil kuesioner MHI-38 beserta detail jawabannya.

---

## 1. Tabel `admins`

### Fungsi
Tabel ini menyimpan data administrator yang bertanggung jawab mengelola sistem.

### Atribut
- **id**: Nomor identitas unik untuk setiap admin (primary key, auto increment)
- **username**: Nama pengguna yang digunakan admin untuk login (harus unik, tidak boleh sama)
- **email**: Alamat email admin (harus unik)
- **password**: Kata sandi yang sudah dienkripsi menggunakan bcrypt
- **remember_token**: Token untuk fitur "ingat saya" saat login (opsional)
- **created_at**: Waktu kapan data admin dibuat
- **updated_at**: Waktu terakhir data admin diperbarui

### Karakteristik
- Berdiri sendiri, tidak punya relasi ke tabel lain
- Digunakan untuk autentikasi admin yang mengelola sistem
- Password disimpan dalam bentuk hash untuk keamanan

### Contoh Data
```
id: 1
username: admin_budi
email: admin@university.ac.id
password: $2y$10$92IXUNpk... (hash bcrypt)
```

---

## 2. Tabel `users`

### Fungsi
Menyimpan data pengguna sistem, yaitu mahasiswa yang akan mengikuti asesmen kesehatan mental.

### Atribut
- **id**: Nomor identitas unik pengguna (primary key)
- **name**: Nama lengkap pengguna
- **email**: Email pengguna (harus unik)
- **nim**: Nomor Induk Mahasiswa (boleh kosong karena bisa login via Google dulu)
- **google_id**: ID unik dari Google untuk fitur login dengan akun Google (Google SSO)
- **password**: Password yang sudah dienkripsi
- **created_at**: Tanggal pendaftaran
- **updated_at**: Tanggal terakhir update data

### Karakteristik
- Tabel ini terpisah dari `data_diris` untuk fleksibilitas
- Mendukung 2 cara login: username/password biasa atau via Google SSO
- NIM boleh kosong karena mahasiswa bisa login dulu, baru lengkapi data

### Contoh Data
```
id: 1
name: Andi Wijaya
email: andi.wijaya@student.ac.id
nim: 2021001
google_id: 108234567890123456789
```

---

## 3. Tabel `data_diris`

### Fungsi
Menyimpan data diri lengkap mahasiswa yang diperlukan untuk profil dan analisis hasil asesmen.

### Atribut
- **id**: Identitas unik (primary key)
- **nim**: Nomor Induk Mahasiswa (harus unik, tidak boleh sama)
- **nama**: Nama lengkap mahasiswa
- **jenis_kelamin**: Jenis kelamin (L untuk Laki-laki, P untuk Perempuan)
- **provinsi**: Provinsi asal mahasiswa
- **alamat**: Alamat lengkap tempat tinggal
- **usia**: Umur mahasiswa
- **fakultas**: Fakultas tempat kuliah (misal: Fakultas Psikologi)
- **program_studi**: Program studi/jurusan (misal: Psikologi)
- **asal_sekolah**: Nama sekolah SMA/SMK asal
- **status_tinggal**: Status tempat tinggal (misal: Kost, Rumah Sendiri, Asrama)
- **email**: Email mahasiswa
- **created_at**: Waktu data dibuat
- **updated_at**: Waktu terakhir data diubah

### Relasi
- **Ke `hasil_kuesioners`**: Satu mahasiswa bisa punya banyak hasil kuesioner (relasi one-to-many)
  - Kenapa? Karena mahasiswa bisa ngerjain tes berkali-kali untuk tracking perkembangan

- **Ke `riwayat_keluhans`**: Satu mahasiswa bisa punya banyak riwayat keluhan (relasi one-to-many)
  - Kenapa? Mahasiswa bisa input keluhan beberapa kali sesuai kondisi

### Karakteristik
- Tabel ini jadi pusat informasi mahasiswa
- NIM dijadikan penghubung utama (foreign key) ke tabel lain
- Data ini penting untuk analisis statistik (misal: fakultas mana yang paling banyak masalah mental health)

### Contoh Data
```
id: 1
nim: 2021001
nama: Siti Nurhaliza
jenis_kelamin: P
provinsi: Jawa Barat
fakultas: Fakultas Psikologi
program_studi: Psikologi
status_tinggal: Kost
```

---

## 4. Tabel `riwayat_keluhans`

### Fungsi
Mencatat riwayat keluhan kesehatan mental yang dialami mahasiswa sebelum mengikuti tes.

### Atribut
- **id**: Identitas unik keluhan (primary key)
- **nim**: NIM mahasiswa (foreign key ke `data_diris.nim`)
- **keluhan**: Deskripsi lengkap keluhan yang dialami (format teks panjang)
- **lama_keluhan**: Sudah berapa lama keluhan dirasakan (misal: "2 bulan", "1 tahun")
- **pernah_konsul**: Apakah pernah konsultasi ke psikolog? (Ya/Tidak)
- **pernah_tes**: Apakah pernah tes psikologi sebelumnya? (Ya/Tidak)
- **created_at**: Kapan keluhan diinput
- **updated_at**: Kapan terakhir diupdate

### Relasi
- **Dari `data_diris`**: Many-to-one (banyak keluhan bisa dari satu mahasiswa)
  - Relasi melalui kolom `nim`
  - Jika data mahasiswa dihapus, semua riwayat keluhannya juga ikut terhapus (cascade delete)

### Karakteristik
- Data ini membantu psikolog/konselor memahami latar belakang mahasiswa
- Penting untuk menentukan apakah mahasiswa butuh follow-up konseling
- Jenis kelamin disimpan sebagai enum (pilihan terbatas) untuk konsistensi data

### Contoh Data
```
id: 1
nim: 2021001
keluhan: "Sering merasa cemas saat menjelang ujian, susah tidur, hilang nafsu makan"
lama_keluhan: "3 bulan"
pernah_konsul: Tidak
pernah_tes: Tidak
```

---

## 5. Tabel `hasil_kuesioners`

### Fungsi
Menyimpan hasil akhir dari kuesioner MHI-38 yang dikerjakan mahasiswa.

### Atribut
- **id**: Identitas unik hasil tes (primary key)
- **nim**: NIM mahasiswa yang mengerjakan (foreign key ke `data_diris.nim`)
- **total_skor**: Total skor dari 38 soal (range: 38-228)
  - Minimum 38 (jika semua soal dijawab 1)
  - Maximum 228 (jika semua soal dijawab 6)
- **kategori**: Klasifikasi kesehatan mental berdasarkan skor
  - "Sangat Buruk" (38-76): Butuh perhatian segera
  - "Buruk" (77-114): Perlu konseling
  - "Cukup" (115-152): Perlu monitoring
  - "Baik" (153-190): Kondisi sehat
  - "Sangat Baik" (191-228): Kondisi sangat sehat
- **tanggal_pengerjaan**: Kapan mahasiswa mengerjakan tes
- **created_at**: Waktu data disimpan ke database
- **updated_at**: Waktu terakhir update

### Relasi
- **Dari `data_diris`**: Many-to-one (satu mahasiswa bisa punya banyak hasil tes)
  - Berguna untuk tracking progress mahasiswa dari waktu ke waktu

- **Ke `mental_health_jawaban_details`**: One-to-many (satu hasil punya 38 detail jawaban)
  - Setiap hasil tes punya 38 baris detail (1 baris per soal)

### Karakteristik
- Tabel ini menyimpan "ringkasan" hasil tes
- Skor total dihitung dari menjumlahkan semua jawaban di tabel detail
- Kategori ditentukan otomatis berdasarkan total skor
- Bisa digunakan untuk membuat grafik perkembangan mahasiswa

### Contoh Data
```
id: 1
nim: 2021001
total_skor: 152
kategori: "Cukup"
tanggal_pengerjaan: 2025-01-15 10:30:00
```

**Interpretasi**: Mahasiswa 2021001 dapat skor 152, masuk kategori "Cukup", artinya butuh monitoring tapi belum urgent.

---

## 6. Tabel `mental_health_jawaban_details`

### Fungsi
Menyimpan detail jawaban untuk setiap soal dalam kuesioner MHI-38.

### Atribut
- **id**: Identitas unik detail jawaban (primary key)
- **hasil_kuesioner_id**: ID hasil kuesioner (foreign key ke `hasil_kuesioners.id`)
- **nomor_soal**: Nomor soal yang dijawab (1 sampai 38)
- **skor**: Skor jawaban untuk soal tersebut (1 sampai 6)
  - 1: Sangat tidak setuju / Tidak pernah
  - 2: Tidak setuju / Jarang
  - 3: Agak tidak setuju / Kadang-kadang
  - 4: Agak setuju / Sering
  - 5: Setuju / Hampir selalu
  - 6: Sangat setuju / Selalu
- **created_at**: Waktu jawaban disimpan
- **updated_at**: Waktu terakhir diupdate

### Relasi
- **Dari `hasil_kuesioners`**: Many-to-one (38 detail dari 1 hasil)
  - Jika hasil kuesioner dihapus, semua detailnya juga dihapus (cascade delete)
  - Constraint: Kombinasi hasil_kuesioner_id + nomor_soal harus unik (tidak boleh jawab soal yang sama 2x)

### Karakteristik
- **Setiap kali mahasiswa ngerjain 1 tes**, akan tersimpan:
  - 1 baris di `hasil_kuesioners`
  - 38 baris di `mental_health_jawaban_details`
- Data ini berguna untuk analisis per dimensi (kecemasan, depresi, dll)
- Bisa digunakan untuk melihat soal mana yang sering dijawab rendah

### Contoh Data
```
Hasil kuesioner ID 1:
- Soal 1: skor 4
- Soal 2: skor 5
- Soal 3: skor 3
- ... (total 38 baris)
- Soal 38: skor 4
```

---

## Relasi Antar Tabel (Penjelasan Detail)

### 1. Relasi `data_diris` → `hasil_kuesioners`

**Jenis**: One-to-Many (1:N)

**Penjelasan**:
- Satu mahasiswa (di tabel `data_diris`) bisa punya banyak hasil kuesioner
- Kenapa? Karena mahasiswa bisa ngerjain tes berkali-kali untuk monitoring perkembangan

**Cara Kerja**:
- Kolom `nim` di tabel `hasil_kuesioners` merujuk ke kolom `nim` di tabel `data_diris`
- Jika mahasiswa dengan nim 2021001 dihapus, semua hasil tesnya juga ikut terhapus

**Contoh Kasus**:
```
Mahasiswa Andi (NIM: 2021001) mengerjakan tes 3 kali:
- 10 Jan 2025: skor 120 (Cukup)
- 15 Feb 2025: skor 145 (Cukup) → ada peningkatan
- 20 Mar 2025: skor 165 (Baik) → membaik!

Semua 3 hasil ini disimpan dan bisa dilacak progressnya.
```

### 2. Relasi `data_diris` → `riwayat_keluhans`

**Jenis**: One-to-Many (1:N)

**Penjelasan**:
- Satu mahasiswa bisa punya banyak riwayat keluhan
- Mahasiswa bisa input keluhan di waktu berbeda sesuai kondisi

**Cara Kerja**:
- Kolom `nim` di `riwayat_keluhans` adalah foreign key ke `data_diris.nim`
- Cascade delete: hapus mahasiswa = hapus semua riwayat keluhannya

**Contoh Kasus**:
```
Mahasiswa Siti (NIM: 2021002):
- Keluhan 1 (Jan): "Sering cemas sebelum ujian"
- Keluhan 2 (Feb): "Susah tidur, sering terbangun malam"
- Keluhan 3 (Mar): "Kehilangan motivasi belajar"

Semua keluhan ini terekam untuk evaluasi psikolog.
```

### 3. Relasi `hasil_kuesioners` → `mental_health_jawaban_details`

**Jenis**: One-to-Many (1:N)

**Penjelasan**:
- Satu hasil kuesioner pasti punya 38 detail jawaban (karena ada 38 soal MHI-38)
- Tidak mungkin lebih, tidak mungkin kurang (kecuali tes tidak selesai)

**Cara Kerja**:
- Kolom `hasil_kuesioner_id` di detail merujuk ke `id` di hasil_kuesioners
- Constraint UNIQUE pada kombinasi (hasil_kuesioner_id, nomor_soal) → tidak bisa jawab soal yang sama 2x

**Contoh Kasus**:
```
Hasil ID 5 (Mahasiswa mengerjakan tes):
┌────────────────────────────┐
│ hasil_kuesioners           │
│ id: 5                      │
│ nim: 2021003               │
│ total_skor: 152            │
│ kategori: Cukup            │
└────────────────────────────┘
         │
         │ Punya 38 detail:
         ├─→ [Soal 1: skor 4]
         ├─→ [Soal 2: skor 5]
         ├─→ [Soal 3: skor 3]
         ├─→ ...
         └─→ [Soal 38: skor 4]

Total skor = 4+5+3+...+4 = 152
```

---

## Alur Data Lengkap (Step by Step)

### Scenario: Mahasiswa Baru Mengikuti Asesmen

**Step 1: Registrasi**
```
Mahasiswa daftar → data masuk ke tabel `users`
{
  name: "Budi Santoso"
  email: "budi@student.ac.id"
  password: (encrypted)
}
```

**Step 2: Lengkapi Profil**
```
Mahasiswa isi data lengkap → masuk ke tabel `data_diris`
{
  nim: 2021005
  nama: "Budi Santoso"
  fakultas: "Fakultas Teknik"
  program_studi: "Teknik Informatika"
  jenis_kelamin: "L"
  ...
}
```

**Step 3: Input Keluhan (Opsional)**
```
Mahasiswa cerita keluhannya → masuk ke `riwayat_keluhans`
{
  nim: 2021005
  keluhan: "Sering merasa tertekan dengan tugas kuliah yang banyak"
  lama_keluhan: "2 bulan"
  pernah_konsul: "Tidak"
}
```

**Step 4: Mengerjakan Kuesioner MHI-38**
```
Mahasiswa jawab 38 soal:
1. Soal 1: pilih skor 4
2. Soal 2: pilih skor 5
3. Soal 3: pilih skor 3
...
38. Soal 38: pilih skor 4

Total skor = 152 → Kategori "Cukup"
```

**Step 5: Simpan Hasil**
```
Data masuk ke 2 tabel:

A. hasil_kuesioners (ringkasan):
{
  id: 10
  nim: 2021005
  total_skor: 152
  kategori: "Cukup"
  tanggal_pengerjaan: 2025-01-15
}

B. mental_health_jawaban_details (detail 38 soal):
{hasil_kuesioner_id: 10, nomor_soal: 1, skor: 4}
{hasil_kuesioner_id: 10, nomor_soal: 2, skor: 5}
{hasil_kuesioner_id: 10, nomor_soal: 3, skor: 3}
...
{hasil_kuesioner_id: 10, nomor_soal: 38, skor: 4}
```

**Step 6: Analisis & Dashboard**
```
Admin/Psikolog bisa lihat:
- Data mahasiswa lengkap (dari data_diris)
- Keluhan yang pernah disampaikan (dari riwayat_keluhans)
- Hasil tes dengan kategori (dari hasil_kuesioners)
- Detail jawaban per soal (dari mental_health_jawaban_details)
- Statistik fakultas, trend bulanan, dll.
```

---

## Kegunaan Relasi dalam Praktik

### 1. Query: "Tampilkan semua mahasiswa fakultas Psikologi yang hasil tesnya kategori Buruk atau Sangat Buruk"

```sql
SELECT
    dd.nim,
    dd.nama,
    dd.fakultas,
    hk.total_skor,
    hk.kategori,
    rk.keluhan
FROM data_diris dd
INNER JOIN hasil_kuesioners hk ON dd.nim = hk.nim
LEFT JOIN riwayat_keluhans rk ON dd.nim = rk.nim
WHERE dd.fakultas = 'Fakultas Psikologi'
  AND hk.kategori IN ('Buruk', 'Sangat Buruk')
```

**Relasi yang dipakai**:
- `data_diris` JOIN `hasil_kuesioners` (via nim)
- `data_diris` LEFT JOIN `riwayat_keluhans` (via nim)

### 2. Query: "Lihat detail jawaban mahasiswa untuk soal tentang kecemasan (soal 2, 5, 9)"

```sql
SELECT
    dd.nama,
    jd.nomor_soal,
    jd.skor,
    hk.kategori
FROM mental_health_jawaban_details jd
INNER JOIN hasil_kuesioners hk ON jd.hasil_kuesioner_id = hk.id
INNER JOIN data_diris dd ON hk.nim = dd.nim
WHERE hk.nim = 2021001
  AND jd.nomor_soal IN (2, 5, 9)
```

**Relasi yang dipakai**:
- `mental_health_jawaban_details` JOIN `hasil_kuesioners` (via hasil_kuesioner_id)
- `hasil_kuesioners` JOIN `data_diris` (via nim)

---

## Kesimpulan Struktur Database

### Tabel Independen (Tidak Bergantung ke Tabel Lain):
- `admins`: Data admin berdiri sendiri
- `users`: Data user berdiri sendiri

### Tabel Pusat (Jadi Rujukan Banyak Tabel):
- `data_diris`: Dirujuk oleh `hasil_kuesioners` dan `riwayat_keluhans`

### Tabel Dependent (Bergantung ke Tabel Lain):
- `riwayat_keluhans`: Bergantung ke `data_diris`
- `hasil_kuesioners`: Bergantung ke `data_diris`
- `mental_health_jawaban_details`: Bergantung ke `hasil_kuesioners`

### Hirarki Dependensi:
```
data_diris (ROOT)
    ├── riwayat_keluhans
    └── hasil_kuesioners
            └── mental_health_jawaban_details
```

**Artinya**: Kalau hapus mahasiswa, semua data terkait (keluhan, hasil tes, detail jawaban) ikut terhapus otomatis (cascade delete).

---

## Catatan Penting

1. **NIM sebagai Penghubung Utama**: Hampir semua relasi pakai NIM sebagai foreign key karena NIM adalah identitas unik mahasiswa.

2. **Cascade Delete**: Semua foreign key pakai cascade delete, jadi data tetap konsisten. Hapus mahasiswa = hapus semua jejaknya.

3. **One-to-Many Dominan**: Kebanyakan relasi adalah 1:N karena satu mahasiswa bisa punya banyak hasil/keluhan/jawaban.

4. **Normalisasi Bagus**: Data tidak duplikat, setiap tabel punya fokus jelas, mudah maintain.

5. **Flexible untuk Analytics**: Struktur ini memudahkan buat query statistik, trend analysis, dan dashboard.
