# Pattern Replacement untuk Perbaikan Konsistensi Bahasa
# Dokumentasi: bab3-use-case-diagram-explanation.md & bab3-activity-diagram-explanation.md

**Tanggal:** 21 November 2025
**Tujuan:** Mengubah istilah umum ke Bahasa Indonesia, mempertahankan istilah teknis dalam Bahasa Inggris

---

## INSTRUKSI PENGGUNAAN

1. Buka file di VS Code atau editor favorit
2. Gunakan Find & Replace (Ctrl+H)
3. Aktifkan opsi **Match Case** (case sensitive)
4. Terapkan replacement satu per satu dari atas ke bawah
5. Review hasil replacement sebelum save

**PENTING:**
- Jangan replace secara global/bulk tanpa review
- Beberapa kata mungkin kontekstual, perlu dicek manual
- Istilah teknis (OAuth, token, session, cache, dll) TIDAK diganti

---

## KATEGORI 1: ISTILAH UMUM (Ganti ke Bahasa Indonesia)

### Pengguna & Interaksi
```
Find: user experience
Replace: pengalaman pengguna

Find: users
Replace: pengguna

Find: user
Replace: pengguna

Find: administrator
Replace: administrator
(CATATAN: sudah Bahasa Indonesia, tidak perlu diganti)

Find: entry point
Replace: titik masuk

Find: gateway
Replace: gerbang

Find: access
Replace: akses
(CATATAN: sudah Bahasa Indonesia, tidak perlu diganti)
```

### Proses & Alur
```
Find: flow
Replace: alur

Find: workflow
Replace: alur kerja

Find: process
Replace: proses

Find: step
Replace: langkah

Find: stage
Replace: tahap

Find: phase
Replace: fase
```

### Implementasi
```
Find: implement
Replace: menerapkan

Find: implementation
Replace: penerapan

Find: feature
Replace: fitur

Find: functionality
Replace: fungsionalitas

Find: mechanism
Replace: mekanisme
```

### Umpan Balik & Komunikasi
```
Find: feedback
Replace: umpan balik

Find: message
Replace: pesan

Find: notification
Replace: notifikasi

Find: alert
Replace: peringatan

Find: confirmation
Replace: konfirmasi

Find: prompt
Replace: permintaan
```

### Loading & Performance
```
Find: loading
Replace: pemuatan

Find: load
Replace: memuat

Find: performance
Replace: kinerja

Find: optimization
Replace: optimisasi

Find: efficiency
Replace: efisiensi
```

### Data & Informasi
```
Find: data
Replace: data
(CATATAN: sudah umum dipakai dalam Bahasa Indonesia)

Find: record
Replace: catatan

Find: entry
Replace: entri

Find: field
Replace: bidang atau kolom

Find: column
Replace: kolom

Find: row
Replace: baris

Find: table
Replace: tabel
```

### Tampilan & Interface
```
Find: interface
Replace: antarmuka

Find: display
Replace: tampilan

Find: view
Replace: tampilan atau halaman

Find: page
Replace: halaman

Find: screen
Replace: layar

Find: form
Replace: formulir

Find: button
Replace: tombol

Find: dropdown
Replace: dropdown
(CATATAN: istilah teknis UI, bisa tetap atau "menu turun")
```

### Keamanan
```
Find: secure
Replace: aman

Find: security
Replace: keamanan

Find: safe
Replace: aman

Find: protection
Replace: perlindungan

Find: prevent
Replace: mencegah

Find: validation
Replace: validasi
(CATATAN: istilah teknis, tetap Bahasa Inggris)
```

### Operasi
```
Find: operation
Replace: operasi

Find: action
Replace: aksi atau tindakan

Find: task
Replace: tugas

Find: job
Replace: pekerjaan

Find: activity
Replace: aktivitas
```

---

## KATEGORI 2: FRASA & KALIMAT UMUM

### Frasa Teknis yang Sering Muncul
```
Find: use case
Replace: use case
(CATATAN: istilah teknis diagram, tetap Bahasa Inggris)

Find: activity diagram
Replace: activity diagram
(CATATAN: istilah teknis diagram, tetap Bahasa Inggris)

Find: best practice
Replace: praktik terbaik

Find: design pattern
Replace: pola desain

Find: single sign-on
Replace: Single Sign-On
(CATATAN: istilah teknis, tetap atau SSO)
```

### Kalimat & Konteks
```
Find: make
Replace: membuat

Find: create
Replace: membuat

Find: generate
Replace: menghasilkan

Find: produce
Replace: menghasilkan

Find: provide
Replace: menyediakan

Find: allow
Replace: memungkinkan

Find: enable
Replace: mengaktifkan

Find: ensure
Replace: memastikan

Find: verify
Replace: memverifikasi

Find: check
Replace: memeriksa

Find: validate
Replace: memvalidasi
```

---

## KATEGORI 3: ISTILAH TEKNIS (TETAP Bahasa Inggris)

**JANGAN GANTI istilah-istilah berikut:**

### Framework & Technology
- Laravel
- OAuth (Google OAuth)
- PHP
- JavaScript
- SQL
- HTML
- CSS
- API
- REST/RESTful
- JSON
- XML

### Database & Storage
- database
- query
- table (dalam konteks database)
- column (dalam konteks database)
- index
- foreign key
- primary key
- schema
- migration

### Authentication & Security
- authentication
- authorization
- session
- token (session token, CSRF token, access token)
- cookie
- hash/hashing
- bcrypt
- CSRF (Cross-Site Request Forgery)
- OAuth 2.0

### Web & HTTP
- request (HTTP request)
- response (HTTP response)
- GET/POST/PUT/DELETE (HTTP methods)
- endpoint
- URL
- redirect
- callback
- middleware
- controller
- route

### Caching & Performance
- cache/caching
- TTL (Time To Live)
- cache hit/miss
- invalidation

### Development
- code
- method
- function
- class
- variable
- array
- object
- string
- boolean
- integer

### UI/UX Components
- modal
- popup
- tooltip
- badge
- card
- navbar
- sidebar
- header
- footer
- layout

### Testing & Debug
- testing
- debug
- log/logging
- error
- exception
- assertion

---

## KATEGORI 4: KASUS KHUSUS & KONTEKSTUAL

### Perlu Review Manual
Beberapa kata tergantung konteks, perlu dicek:

```
record → "catatan" atau "rekaman" atau tetap "record"
field → "bidang" atau "kolom" (tergantung konteks form/database)
entry → "entri" atau "masukan"
view → "tampilan" atau "melihat" (tergantung konteks)
```

### Singular vs Plural
```
user → pengguna
users → para pengguna ATAU pengguna (kontekstual)

feature → fitur
features → fitur-fitur

process → proses
processes → proses-proses
```

---

## KATEGORI 5: FRASA CAMPURAN YANG PERLU DIPERBAIKI

### Contoh Frasa yang Perlu Diubah

**SEBELUM:**
```
"User dapat access dashboard untuk view data"
"System melakukan validate terhadap input user"
"Feature ini provide functionality untuk export data"
"Administrator dapat perform delete operation"
```

**SESUDAH:**
```
"Pengguna dapat mengakses dashboard untuk melihat data"
"Sistem melakukan validasi terhadap masukan pengguna"
"Fitur ini menyediakan fungsionalitas untuk mengekspor data"
"Administrator dapat melakukan operasi penghapusan"
```

---

## PANDUAN REPLACEMENT

### Urutan Replacement yang Direkomendasikan:

1. **Replacement Spesifik Dulu** (frasa panjang)
   - "user experience" → "pengalaman pengguna"
   - "entry point" → "titik masuk"
   - "best practice" → "praktik terbaik"

2. **Replacement Umum** (kata tunggal)
   - "users" → "pengguna"
   - "user" → "pengguna"
   - "feature" → "fitur"

3. **Verb/Action Words**
   - "implement" → "menerapkan"
   - "provide" → "menyediakan"
   - "ensure" → "memastikan"

4. **Review Manual**
   - Cek konteks setiap replacement
   - Pastikan kalimat tetap natural
   - Perbaiki grammar jika perlu

---

## CHECKLIST AFTER REPLACEMENT

- [ ] Semua "user" umum sudah jadi "pengguna"
- [ ] Semua "flow" sudah jadi "alur"
- [ ] Semua "feature" sudah jadi "fitur"
- [ ] Semua "implement" sudah jadi "menerapkan"
- [ ] Istilah teknis (OAuth, token, session, cache) tetap Bahasa Inggris
- [ ] Baca ulang beberapa paragraf, pastikan natural
- [ ] Grammar check (subjek-predikat-objek)
- [ ] Konsistensi: "data diri" bukan "data pribadi"
- [ ] Konsistensi: "dashboard" bukan "dasbor"

---

## CONTOH HASIL PERBAIKAN

### BEFORE (Campur):
```
Sistem Assessment Online ITERA mengimplementasikan dua mekanisme autentikasi
yang terpisah untuk menjamin keamanan dan fleksibilitas akses. User berinteraksi
dengan sistem melalui proses login yang terintegrasi dengan akun Google ITERA mereka.
Entry point utama bagi mahasiswa untuk mengakses sistem adalah melalui Google OAuth
flow yang sudah terintegrasi.
```

### AFTER (Konsisten):
```
Sistem Assessment Online ITERA menerapkan dua mekanisme autentikasi yang terpisah
untuk menjamin keamanan dan fleksibilitas akses. Pengguna berinteraksi dengan sistem
melalui proses login yang terintegrasi dengan akun Google ITERA mereka. Titik masuk
utama bagi mahasiswa untuk mengakses sistem adalah melalui alur Google OAuth yang
sudah terintegrasi.
```

---

## CATATAN PENTING

1. **Jangan Global Replace Semua Sekaligus**
   - Replace per kategori
   - Review hasil setiap kategori
   - Beberapa kata bisa punya konteks berbeda

2. **Istilah yang Tetap Inggris**
   - Nama teknologi (Laravel, OAuth, PHP)
   - Istilah teknis programming (token, session, cache, query)
   - Istilah yang sudah umum (database, email, login)

3. **Konsistensi Internal**
   - Jika "dashboard" dipakai di awal, gunakan terus
   - Jangan ganti-ganti "pengguna" dan "user"
   - Pilih satu: "halaman" atau "page" (disarankan: halaman)

4. **Grammar Indonesia**
   - Kata kerja: meng-, me-, mem- (mengakses, melihat, memuat)
   - Kata benda: -an (tampilan, pemuatan, penerapan)
   - Imbuhan: ke-an (keamanan, kemudahan)

---

## FILE TARGET

Terapkan pattern ini pada file:
1. `bab3-use-case-diagram-explanation.md` (290 baris)
2. `bab3-activity-diagram-explanation.md` (1129 baris)

**Backup sudah dibuat:**
- `bab3-use-case-diagram-explanation.md.backup`
- `bab3-activity-diagram-explanation.md.backup`

---

**Good luck!** 🚀

Jika ada pertanyaan atau ada pattern yang missed, silakan tambahkan ke dokumen ini.
