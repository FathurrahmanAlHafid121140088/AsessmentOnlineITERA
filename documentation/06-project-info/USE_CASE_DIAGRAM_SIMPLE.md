# Use Case Diagram - Assessment Online ITERA
## Versi Sederhana (Simplified)

**Platform Asesmen Kesehatan Mental Mahasiswa ITERA**
**Tanggal:** 28 November 2025
**Versi:** 3.0 - Simplified Version

---

## 📑 Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Use Case Diagram User (Mahasiswa)](#use-case-diagram-user-mahasiswa)
3. [Use Case Diagram Admin (Administrator)](#use-case-diagram-admin-administrator)
4. [Ringkasan Use Case](#ringkasan-use-case)
5. [Cara Render Diagram](#cara-render-diagram)

---

## 1. Pendahuluan

### 1.1 Tujuan Dokumen
Dokumen ini menjelaskan use case diagram untuk sistem Assessment Online ITERA secara sederhana, fokus pada fitur-fitur utama yang dapat diakses oleh pengguna tanpa detail teknis implementasi.

### 1.2 Aktor Sistem
- **User (Mahasiswa)**: Mahasiswa ITERA yang menggunakan sistem untuk melakukan asesmen kesehatan mental pribadi
- **Admin (Administrator)**: Administrator yang mengelola dan memonitor data kesehatan mental seluruh mahasiswa

### 1.3 Kategori Fitur
- **Autentikasi** - Login dan logout untuk akses sistem
- **Asesmen Mental Health** - Mengisi kuesioner dan melihat hasil (User)
- **Pengelolaan Data** - Monitoring, pencarian, dan manajemen data (Admin)

---

## 2. Use Case Diagram User (Mahasiswa)

### 2.1 Diagram User

```plantuml
@startuml
left to right direction
skinparam packageStyle rectangle

actor "User\n(Mahasiswa)" as User

rectangle "SISTEM ASSESSMENT ONLINE - USER" {

  package "AUTENTIKASI" #LightBlue {
    usecase "Login dengan\nGoogle OAuth" as UC_U_Login
    usecase "Logout" as UC_U_Logout
  }

  package "ASESMEN MENTAL HEALTH" #LightCyan {
    usecase "Melihat Dashboard\nPribadi" as UC_U_Dashboard
    usecase "Melihat Riwayat\nTes" as UC_U_History
    usecase "Melihat Grafik\nProgres" as UC_U_Chart
    usecase "Mengisi/Update\nData Diri" as UC_U_DataDiri
    usecase "Mengerjakan\nKuesioner MHI-38" as UC_U_Kuesioner
    usecase "Melihat Hasil\nTes" as UC_U_Result
  }
}

' Relasi User dengan Use Case
User --> UC_U_Login
User --> UC_U_Logout
User --> UC_U_Dashboard
User --> UC_U_History
User --> UC_U_Chart
User --> UC_U_DataDiri
User --> UC_U_Kuesioner
User --> UC_U_Result

' Dependency antar use case
UC_U_Kuesioner ..> UC_U_DataDiri : <<requires>>
UC_U_Result ..> UC_U_Kuesioner : <<requires>>

note right of UC_U_Kuesioner
  Kuesioner MHI-38
  38 pertanyaan, Skala 1-6

  Kategori:
  - Sangat Sehat (190-228)
  - Sehat (152-189)
  - Cukup Sehat (114-151)
  - Perlu Dukungan (76-113)
  - Perlu Dukungan Intensif (38-75)
end note

@enduml
```

### 2.2 Deskripsi Use Case User

| No | Use Case | Deskripsi | Prasyarat |
|----|----------|-----------|-----------|
| 1 | Login dengan Google OAuth | Mahasiswa login menggunakan akun Google ITERA | Email: `{NIM}@student.itera.ac.id` |
| 2 | Logout | Mahasiswa keluar dari sistem | Sudah login |
| 3 | Melihat Dashboard Pribadi | Melihat ringkasan statistik tes pribadi | Sudah login |
| 4 | Melihat Riwayat Tes | Melihat semua tes yang pernah dikerjakan | Sudah login |
| 5 | Melihat Grafik Progres | Melihat grafik trend skor dari waktu ke waktu | Sudah login, minimal 2 tes |
| 6 | Mengisi Data Diri | Mengisi data pribadi dan akademik | Sudah login |
| 7 | Mengerjakan Kuesioner MHI-38 | Menjawab 38 pertanyaan skala 1-6 | Data diri sudah lengkap |
| 8 | Melihat Hasil Tes | Melihat hasil tes dengan interpretasi | Sudah submit kuesioner |

### 2.3 Alur Penggunaan User

```
1. Login dengan Google OAuth
   ↓
2. Mengisi/Update Data Diri (wajib)
   ↓
3. Mengerjakan Kuesioner MHI-38
   ↓
4. Melihat Hasil Tes
   ↓
5. Melihat Dashboard Pribadi / Riwayat / Grafik Progres
```

---

## 3. Use Case Diagram Admin (Administrator)

### 3.1 Diagram Admin

```plantuml
@startuml
left to right direction
skinparam packageStyle rectangle

actor "Admin\n(Administrator)" as Admin

rectangle "SISTEM ASSESSMENT ONLINE - ADMIN" {

  package "AUTENTIKASI" #LightGreen {
    usecase "Login dengan\nEmail & Password" as UC_A_Login
    usecase "Logout" as UC_A_Logout
  }

  package "PENGELOLAAN DATA" #LightYellow {
    usecase "Melihat Dashboard\nAdmin" as UC_A_Dashboard
    usecase "Melihat Statistik\nKesehatan Mental" as UC_A_Stats
    usecase "Mencari Data\nMahasiswa" as UC_A_Search
    usecase "Filter Data\nberdasarkan Kategori" as UC_A_Filter
    usecase "Mengurutkan Data" as UC_A_Sort
    usecase "Melihat Detail\nMahasiswa" as UC_A_Detail
    usecase "Melihat Detail\nJawaban Kuesioner" as UC_A_DetailJawaban
    usecase "Export Data\nke Excel" as UC_A_Export
    usecase "Export Detail\nJawaban ke PDF" as UC_A_ExportPDF
    usecase "Menghapus Data\nMahasiswa" as UC_A_Delete
  }
}

' Relasi Admin dengan Use Case
Admin --> UC_A_Login
Admin --> UC_A_Logout
Admin --> UC_A_Dashboard
Admin --> UC_A_Stats
Admin --> UC_A_Search
Admin --> UC_A_Filter
Admin --> UC_A_Sort
Admin --> UC_A_Detail
Admin --> UC_A_DetailJawaban
Admin --> UC_A_Export
Admin --> UC_A_ExportPDF
Admin --> UC_A_Delete

' Dependency antar use case
UC_A_Export ..> UC_A_Search : <<extend>>
UC_A_Export ..> UC_A_Filter : <<extend>>
UC_A_ExportPDF ..> UC_A_DetailJawaban : <<extend>>

note right of UC_A_Search
  Pencarian 11 field:
  NIM, Nama, Gender, Provinsi,
  Alamat, Fakultas, Prodi,
  Asal Sekolah, Status Tinggal,
  Email, Kategori
end note

note bottom of UC_A_Delete
  Cascade delete:
  - Detail Jawaban
  - Hasil Kuesioner
  - Riwayat Keluhan
  - Data Diri
  - User Account
end note

@enduml
```

### 3.2 Deskripsi Use Case Admin

| No | Use Case | Deskripsi | Prasyarat |
|----|----------|-----------|-----------|
| 1 | Login dengan Email & Password | Admin login untuk akses dashboard | Kredensial valid |
| 2 | Logout | Admin keluar dari sistem | Sudah login |
| 3 | Melihat Dashboard Admin | Melihat statistik dan tabel data mahasiswa | Sudah login |
| 4 | Melihat Statistik | Melihat statistik lengkap: total, distribusi, trend | Sudah login |
| 5 | Mencari Data Mahasiswa | Mencari data mahasiswa di 11 field | Sudah login |
| 6 | Filter Data berdasarkan Kategori | Memfilter data berdasarkan 5 kategori | Sudah login |
| 7 | Mengurutkan Data | Mengurutkan data berdasarkan kolom tertentu | Sudah login |
| 8 | Melihat Detail Mahasiswa | Melihat detail lengkap 1 mahasiswa | Sudah login |
| 9 | Melihat Detail Jawaban Kuesioner | Melihat 38 pertanyaan dan jawaban mahasiswa | Sudah login |
| 10 | Export Data ke Excel | Export data mahasiswa ke file Excel | Sudah login |
| 11 | Export Detail Jawaban ke PDF | Export detail jawaban 38 item ke PDF | Sudah login |
| 12 | Menghapus Data Mahasiswa | Menghapus data mahasiswa secara permanen | Sudah login |

### 3.3 Alur Penggunaan Admin

```
1. Login dengan Email & Password
   ↓
2. Melihat Dashboard Admin / Statistik
   ↓
3. Mencari / Filter / Mengurutkan Data (opsional)
   ↓
4. Melihat Detail Mahasiswa
   ↓
5. Melihat Detail Jawaban Kuesioner (opsional)
   ↓
6. Export Data (Excel/PDF) atau Menghapus Data
```

---

## 4. Ringkasan Use Case

### 4.1 Total Use Case

| Aktor | Jumlah Use Case | Kategori |
|-------|-----------------|----------|
| **User (Mahasiswa)** | 8 | Autentikasi (2), Asesmen (6) |
| **Admin (Administrator)** | 12 | Autentikasi (2), Pengelolaan (10) |
| **TOTAL** | **20** | - |

### 4.2 Use Case User (8)

**Autentikasi:**
1. Login dengan Google OAuth
2. Logout

**Asesmen Mental Health:**
3. Melihat Dashboard Pribadi
4. Melihat Riwayat Tes
5. Melihat Grafik Progres
6. Mengisi/Update Data Diri
7. Mengerjakan Kuesioner MHI-38
8. Melihat Hasil Tes

### 4.3 Use Case Admin (12)

**Autentikasi:**
1. Login dengan Email & Password
2. Logout

**Pengelolaan Data:**
3. Melihat Dashboard Admin
4. Melihat Statistik Kesehatan Mental
5. Mencari Data Mahasiswa
6. Filter Data berdasarkan Kategori
7. Mengurutkan Data
8. Melihat Detail Mahasiswa
9. Melihat Detail Jawaban Kuesioner
10. Export Data ke Excel
11. Export Detail Jawaban ke PDF
12. Menghapus Data Mahasiswa

### 4.4 Perbedaan Fitur User vs Admin

| Fitur | User | Admin |
|-------|------|-------|
| **Metode Login** | Google OAuth | Email & Password |
| **Isi Kuesioner** | ✓ Ya | ✗ Tidak |
| **Lihat Data Pribadi** | ✓ Ya (pribadi) | ✓ Ya (semua mahasiswa) |
| **Pencarian & Filter** | ✗ Tidak | ✓ Ya |
| **Export Data** | ✗ Tidak | ✓ Ya (Excel/PDF) |
| **Hapus Data** | ✗ Tidak | ✓ Ya |
| **Statistik** | ✓ Pribadi | ✓ Global |

---

## 5. Cara Render Diagram

### 5.1 Online Tools (Recommended)

**1. PlantUML Online Editor**
- URL: http://www.plantuml.com/plantuml/uml/
- Langkah:
  1. Buka website
  2. Copy code PlantUML dari diagram User atau Admin
  3. Paste ke editor
  4. Klik "Generate" atau tekan Submit
  5. Download gambar PNG/SVG

**2. PlantText**
- URL: https://www.planttext.com/
- Langkah sama seperti di atas

### 5.2 VS Code Extension

1. Install extension **"PlantUML"** by jebbs
2. Install **Graphviz**: https://graphviz.org/download/
3. Buka file `.md` ini di VS Code
4. Tekan `Alt+D` untuk preview diagram
5. Klik kanan pada preview → Export → pilih format (PNG/SVG)

### 5.3 Command Line

```bash
# Install PlantUML (macOS)
brew install plantuml

# Install PlantUML (Ubuntu/Debian)
sudo apt install plantuml

# Generate PNG
plantuml USE_CASE_DIAGRAM_SIMPLE.md

# Generate SVG
plantuml -tsvg USE_CASE_DIAGRAM_SIMPLE.md
```

---

## 📌 Catatan Penting

### Kategori Kesehatan Mental (5 tingkat)

| Skor | Kategori | Keterangan |
|------|----------|------------|
| 190-228 | Sangat Sehat | Kesehatan mental sangat baik |
| 152-189 | Sehat | Kesehatan mental baik |
| 114-151 | Cukup Sehat | Kesehatan mental cukup |
| 76-113 | Perlu Dukungan | Perlu konseling |
| 38-75 | Perlu Dukungan Intensif | Perlu konseling segera |

### Format Email Mahasiswa
```
{NIM}@student.itera.ac.id
```
Contoh: `121450001@student.itera.ac.id`

### Instrumen Asesmen
- **Nama:** MHI-38 (Mental Health Inventory-38)
- **Jumlah Pertanyaan:** 38 item
- **Skala:** Likert 1-6
- **Skor Minimum:** 38
- **Skor Maksimum:** 228

---

## 📝 Changelog

### Version 3.0 (28 November 2025) - Simplified Version
- ✅ Menghilangkan use case sistem internal (23 use case)
- ✅ Fokus pada use case yang diakses aktor langsung (20 use case)
- ✅ Diagram lebih sederhana dan mudah dipahami
- ✅ Menghapus detail teknis implementasi
- ✅ Menambahkan alur penggunaan untuk User dan Admin

### Version 2.0 (28 November 2025)
- Menambahkan fitur detail jawaban kuesioner
- Menambahkan export PDF
- Total 44 use case (termasuk sistem internal)

### Version 1.0 (11 November 2025)
- Initial release
- Total 40 use case

---

**Dokumen Dibuat:** 11 November 2025
**Terakhir Diupdate:** 28 November 2025
**Versi:** 3.0 - Simplified Version
**Status:** Final
**Institut Teknologi Sumatera (ITERA)**

---

**END OF DOCUMENT**
