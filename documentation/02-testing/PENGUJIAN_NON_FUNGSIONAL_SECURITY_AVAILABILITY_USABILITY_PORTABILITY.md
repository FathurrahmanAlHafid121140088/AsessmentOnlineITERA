# Panduan Pengujian Kebutuhan Non-Fungsional
## Assessment Online ITERA - Mental Health System

**Tanggal Dokumen:** 26 November 2025
**Status:** Draft Panduan Pengujian Lengkap
**Fokus Aspek:** Security, Availability, Usability, Portability

---

## Daftar Isi

1. [Ringkasan Aspek](#ringkasan-aspek)
2. [5.2 Security (Keamanan Sistem)](#52-security-keamanan-sistem)
3. [5.5 Reliability/Availability (Keandalan & Ketersediaan)](#55-reliabilityavailability-keandalan--ketersediaan)
4. [5.4 Usability (Kemudahan Penggunaan)](#54-usability-kemudahan-penggunaan)
5. [5.7 Compatibility/Portability (Kompatibilitas)](#57-compatibilityportability-kompatibilitas)
6. [Metrik Pengujian & Acceptance Criteria](#metrik-pengujian--acceptance-criteria)

---

## Ringkasan Aspek

| Aspek | Fokus Utama | Target | Prioritas |
|-------|-----------|--------|----------|
| **Security** | Proteksi data, autentikasi, keamanan session | Keamanan data mahasiswa | CRITICAL |
| **Availability** | Uptime, reliability, disaster recovery | 99% uptime, data integrity | CRITICAL |
| **Usability** | User experience, interface, navigasi | User-friendly, mudah digunakan | HIGH |
| **Portability** | Browser compatibility, responsiveness | Multi-device, cross-platform | HIGH |

---

## 5.2 Security (Keamanan Sistem)

### 5.2.1 Deskripsi Kebutuhan

Sistem harus aman dari berbagai ancaman keamanan web untuk melindungi data mahasiswa dan integritas sistem. Implementasi keamanan mencakup:

- **Autentikasi & Password:**
  - Google OAuth 2.0 untuk mahasiswa dengan state token (prevent CSRF di OAuth flow)
  - Email/password untuk admin dengan password hashing bcrypt cost factor 10

- **Session & Authorization:**
  - Session-based authentication, disimpan di database (bukan cookie/file)
  - CSRF protection di semua form POST/DELETE dengan token `@csrf` dan middleware `VerifyCsrfToken`
  - Session fixation prevention dengan regenerate session ID saat login
  - Session timeout khusus admin (auto-logout 30 menit idle)
  - Middleware `auth` untuk user routes dan `AdminAuth` untuk admin routes

- **Input & Output Security:**
  - Input validation dan sanitization menggunakan Laravel validation rules (prevent SQL injection)
  - XSS prevention dengan Blade automatic escaping (semua `{{ }}` di-escape)
  - Gunakan `{!! !!}` hanya untuk trusted content

- **Credential Protection:**
  - Environment variables untuk sensitive credentials (DB password, Google OAuth secret, APP_KEY)
  - Tidak boleh ada password plain text, semua harus bcrypt hash
  - Security headers (X-Frame-Options, X-Content-Type-Options, X-XSS-Protection)
  - HTTPS mandatory untuk production

---

### 5.2.2 Panduan Pengujian Security

#### **Test Case 1: Autentikasi Google OAuth Validation**

**Objective:** Verifikasi sistem menolak email non-ITERA dan hanya menerima format `{NIM}@student.itera.ac.id`

**Test Steps:**
1. Buka halaman login `/`
2. Klik tombol "Login dengan Google"
3. Redirect ke Google OAuth, login dengan akun non-ITERA (contoh: gmail.com)
4. Verifikasi sistem menampilkan error: "Login gagal. Pastikan Anda menggunakan email mahasiswa ITERA yang valid."
5. Logout dan ulangi dengan email ITERA valid (contoh: `121450123@student.itera.ac.id`)
6. Verifikasi login berhasil dan redirect ke `/user/mental-health`

**Expected Results:**
- ✅ Email non-ITERA ditolak dengan pesan error yang jelas
- ✅ Email ITERA valid diterima dan ekstrak NIM (9 digit pertama)
- ✅ State token di OAuth flow tervalidasi (prevent CSRF)
- ✅ Session diproduksi dan disimpan di database tabel `sessions`

**Tools:** Browser manual testing, OAuth debugger, Burp Suite (untuk inspect token)

**Pass Criteria:** Email validation berfungsi 100% sesuai regex pattern

---

#### **Test Case 2: Password Hashing Verification**

**Objective:** Verifikasi semua password di-hash dengan bcrypt, tidak ada plain text di database

**Test Steps:**
1. Buka database (SQLite/MySQL)
2. Query tabel `users` dan `admins`, lihat kolom `password`
3. Inspect 5 sample records, verifikasi format bcrypt (prefix `$2y$10$`)
4. Coba hash password "test123" dengan `bcrypt("test123")` di PHP
5. Bandingkan dengan hash di database
6. Coba query string literal password: `SELECT * FROM users WHERE password = 'test123'`
7. Verifikasi query return 0 rows (tidak ada match)

**Expected Results:**
- ✅ Semua password format bcrypt dengan cost factor 10: `$2y$10$...`
- ✅ Hash bersifat unique (random salt), password sama punya hash berbeda
- ✅ Plain text password tidak ditemukan di database
- ✅ Query dengan plain text password tidak menghasilkan match

**Tools:** Database client (DBeaver, MySQL Workbench), PHP CLI

**Pass Criteria:** 100% password ter-hash dengan bcrypt, 0% plain text found

---

#### **Test Case 3: CSRF Token Protection**

**Objective:** Verifikasi form submission dan POST requests dilindungi CSRF token

**Test Steps:**
1. Buka halaman form (contoh: `/mental-health/isi-data-diri`)
2. Inspect HTML form, verifikasi ada CSRF token via `@csrf` directive
3. Extract token value dari hidden input `<input type="hidden" name="_token" value="...">`
4. Coba submit form tanpa token menggunakan curl atau Postman:
   ```bash
   curl -X POST http://localhost:8000/submit-form \
     -d "nama=John&usia=20"
   ```
5. Verifikasi response HTTP 419 "Page Expired"
6. Coba submit dengan token yang salah
7. Verifikasi response 419 juga
8. Coba submit dengan token yang benar
9. Verifikasi submit berhasil

**Expected Results:**
- ✅ Form HTML berisi CSRF token
- ✅ Request POST tanpa token → HTTP 419
- ✅ Request POST dengan token salah → HTTP 419
- ✅ Request POST dengan token benar → HTTP 200/302 (success redirect)
- ✅ Token di-regenerate setiap kali login/logout

**Tools:** Postman, curl, Burp Suite, Browser DevTools

**Pass Criteria:** 100% POST/DELETE requests require valid CSRF token

---

#### **Test Case 4: XSS Prevention (Output Escaping)**

**Objective:** Verifikasi sistem escape semua output user untuk prevent XSS attacks

**Test Steps:**
1. Login sebagai mahasiswa
2. Isi form data diri dengan payload XSS:
   ```
   Nama: <script>alert('XSS')</script>
   Alamat: <img src=x onerror="alert('XSS')">
   ```
3. Submit form dan verifikasi data tersimpan
4. Lihat data di dashboard atau admin panel
5. Inspect HTML source code (View Page Source)
6. Verifikasi script dan tag HTML di-escape:
   ```html
   <!-- Expected output -->
   &lt;script&gt;alert('XSS')&lt;/script&gt;
   <!-- atau -->
   &lt;img src=x onerror="alert('XSS')"&gt;
   ```
7. Verifikasi tidak ada alert popup muncul di browser

**Expected Results:**
- ✅ HTML tags di-escape menjadi entity (< menjadi &lt;, > menjadi &gt;, " menjadi &quot;)
- ✅ Tidak ada JavaScript execution di browser
- ✅ Data ditampilkan sebagai plain text, bukan HTML

**Tools:** Browser DevTools, OWASP ZAP, Burp Suite

**Pass Criteria:** 0 XSS vulnerabilities found, semua output escaped correctly

---

#### **Test Case 5: SQL Injection Prevention**

**Objective:** Verifikasi form input di-sanitasi dan prevent SQL injection

**Test Steps:**
1. Login sebagai admin
2. Akses halaman search data mahasiswa: `/admin/mental-health`
3. Di search box, masukkan payload SQL injection:
   ```
   ' OR '1'='1
   121450123'; DROP TABLE data_diris; --
   admin@example.com' UNION SELECT * FROM admins --
   ```
4. Submit search
5. Inspect database query di Laravel Debugbar atau log
6. Verifikasi payload dianggap sebagai literal string (bukan SQL command)
7. Verifikasi tabel tidak terhapus, query hasil normal

**Expected Results:**
- ✅ SQL injection payload tidak mengubah behavior query
- ✅ Database tetap intact, tidak ada tabel terhapus
- ✅ Query di-execute dengan binding parameter (prepared statement), bukan string concatenation
- ✅ Laravel validation rules melakukan sanitasi

**Tools:** Laravel Debugbar, Browser DevTools, Database client

**Pass Criteria:** 0 SQL injection vulnerabilities found

---

#### **Test Case 6: Session Fixation Prevention**

**Objective:** Verifikasi session ID di-regenerate saat login untuk prevent session fixation

**Test Steps:**
1. Buka browser, akses `/` (homepage)
2. Inspect cookie `PHPSESSID` atau `laravel_session`, catat session ID lama
3. Login dengan akun mahasiswa
4. Inspect cookie session ID lagi, catat session ID baru
5. Verifikasi session ID berubah (berbeda dengan session ID sebelum login)
6. Verifikasi session lama tidak valid: buka halaman protected dengan session ID lama
7. Verifikasi access denied atau redirect ke login

**Expected Results:**
- ✅ Session ID berubah setelah login berhasil
- ✅ Session ID lama (pre-login) tidak valid untuk access protected resources
- ✅ Fungsi `$request->session()->regenerate()` di-call di AuthController
- ✅ Prevent session fixation attack

**Tools:** Browser DevTools (Storage/Cookies), curl dengan cookie jar

**Pass Criteria:** Session ID regenerated on every login, old session invalidated

---

#### **Test Case 7: Admin Session Timeout (30 Menit Idle)**

**Objective:** Verifikasi admin auto-logout setelah 30 menit tanpa aktivitas

**Test Steps:**
1. Login sebagai admin
2. Catat waktu login: `HH:MM:SS`
3. Biarkan admin idle (tidak ada request) selama 31 menit
4. Coba akses halaman admin (contoh: `/admin/mental-health`)
5. Verifikasi auto-logout terjadi, redirect ke `/login`
6. Verifikasi flash message: "Sesi Anda telah habis karena tidak ada aktivitas selama 30 menit"
7. Ulangi: login, buat request pada menit ke-25 (sebelum timeout)
8. Verifikasi session tetap aktif, timeout timer di-reset

**Expected Results:**
- ✅ Admin auto-logout setelah 30 menit idle
- ✅ Flash message ditampilkan
- ✅ Session key `last_activity_admin` di-update pada setiap request
- ✅ Aktivitas (klik, request) mereset 30 menit countdown
- ✅ Middleware `AdminAuth` melakukan pengecekan timeout

**Tools:** Browser, Laravel Debugbar, System clock

**Pass Criteria:** Admin session timeout berfungsi tepat pada 30 menit idle

---

#### **Test Case 8: Environment Variables Protection**

**Objective:** Verifikasi sensitive credentials di `.env`, tidak hard-coded di source code

**Test Steps:**
1. Inspect source code file `.env.example`
2. Verifikasi ada template untuk sensitive config:
   - `DB_PASSWORD`
   - `GOOGLE_CLIENT_SECRET`
   - `APP_KEY`
3. Check `.gitignore`, verifikasi `.env` di-ignore (tidak di-commit)
4. Search code base untuk hardcoded credentials:
   ```bash
   grep -r "password:" app/ resources/
   grep -r "client_secret=" app/
   grep -r "'DB_PASSWORD' => " app/
   ```
5. Verifikasi 0 hardcoded credentials ditemukan
6. Verifikasi `.env` tidak ada di repository (git log, git show)
7. Verifikasi config di-load via `env()` helper: `env('DB_PASSWORD')`

**Expected Results:**
- ✅ Sensitive data di `.env`, bukan di source code
- ✅ `.env` tidak di-commit ke git (protected by .gitignore)
- ✅ `.env.example` ada sebagai template
- ✅ Code menggunakan `env()` untuk access config
- ✅ 0 hardcoded secrets ditemukan di source code

**Tools:** Text editor, Git, grep

**Pass Criteria:** 100% sensitive credentials in .env, 0% hardcoded in code

---

#### **Test Case 9: Security Headers Verification**

**Objective:** Verifikasi HTTP security headers di-set di response header

**Test Steps:**
1. Akses halaman aplikasi (contoh: homepage)
2. Inspect HTTP response headers menggunakan curl atau Postman:
   ```bash
   curl -I http://localhost:8000/
   ```
3. Verifikasi presence dari security headers:
   - `X-Frame-Options: DENY` atau `SAMEORIGIN`
   - `X-Content-Type-Options: nosniff`
   - `X-XSS-Protection: 1; mode=block`
   - `Content-Security-Policy: ...` (if implemented)
   - `Strict-Transport-Security: max-age=...` (for HTTPS)
4. Jika header tidak ada, check middleware config atau server config (nginx/Apache)

**Expected Results:**
- ✅ Security headers di-set di response
- ✅ X-Frame-Options prevent clickjacking
- ✅ X-Content-Type-Options prevent MIME type sniffing
- ✅ X-XSS-Protection provide XSS protection (browser-level)

**Tools:** curl, Postman, Browser DevTools (Network tab)

**Pass Criteria:** Minimal 3 security headers present, properly configured

---

#### **Test Case 10: HTTPS Enforcement (Production)**

**Objective:** Verifikasi aplikasi enforce HTTPS untuk production environment

**Test Steps:**
1. Untuk production server, test HTTPS connection:
   ```bash
   curl https://assessment.itera.ac.id/
   ```
2. Verify SSL/TLS certificate valid dan tidak expired
3. Test HTTP connection (non-HTTPS):
   ```bash
   curl http://assessment.itera.ac.id/
   ```
4. Verifikasi HTTP request di-redirect ke HTTPS (HTTP 301/302)
5. Check `.htaccess` atau nginx config untuk rewrite rule:
   ```apache
   # Apache .htaccess
   RewriteEngine On
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

**Expected Results:**
- ✅ HTTPS request berhasil dengan valid certificate
- ✅ HTTP request di-redirect ke HTTPS
- ✅ HSTS header present: `Strict-Transport-Security: max-age=31536000`
- ✅ Certificate chain valid dan trusted

**Tools:** curl, OpenSSL, SSL Labs (https://www.ssllabs.com/), Browser

**Pass Criteria:** HTTPS enforced, valid certificate, HTTP redirected to HTTPS

---

### 5.2.3 Security Test Summary

| Test Case | Requirement | Method | Status |
|-----------|------------|--------|--------|
| 1. OAuth Email Validation | Accept ITERA email only | Manual + Regex test | To-Do |
| 2. Password Hashing | Bcrypt hash all passwords | Database inspection | To-Do |
| 3. CSRF Token | POST/DELETE require token | Form submission test | To-Do |
| 4. XSS Prevention | Escape all output | Payload injection test | To-Do |
| 5. SQL Injection | Sanitize input | SQL payload test | To-Do |
| 6. Session Fixation | Regenerate session ID | Cookie inspection | To-Do |
| 7. Admin Timeout | 30 min auto-logout | Idle test | To-Do |
| 8. Environment Vars | Sensitive data in .env | Code inspection | To-Do |
| 9. Security Headers | HTTP headers set | Header inspection | To-Do |
| 10. HTTPS Enforcement | Force HTTPS production | Connection test | To-Do |

---

## 5.5 Reliability/Availability (Keandalan & Ketersediaan Sistem)

### 5.5.1 Deskripsi Kebutuhan

Sistem harus dapat diandalkan dengan uptime tinggi dan data integrity terjaga. Target reliability:

- **Uptime:** Minimal 99% dalam konteks akademik (maksimal downtime 7 jam/bulan atau ~14 menit/hari)
- **Maintenance:** Dijadwalkan di waktu low traffic, notifikasi 24 jam sebelumnya
- **Data Integrity:**
  - Foreign key constraints dengan ON DELETE CASCADE
  - Database transactions untuk operasi multi-table
  - Cascade delete berfungsi saat hapus mahasiswa
  - Data validation di database level (NOT NULL, UNIQUE, CHECK)

- **Backup & Recovery:**
  - Full backup daily @ 02:00 AM
  - Incremental backup setiap 6 jam
  - Backup disimpan minimal 30 hari
  - Automated backup verification

- **Error Handling:**
  - Try-catch di code prone to error (file upload, external API, complex calc)
  - Graceful degradation jika service tidak available
  - User-friendly error pages (404, 500, 503)
  - Log semua error ke `storage/logs/laravel.log`

- **Monitoring:**
  - Health check endpoint `/health` return server, DB, cache status
  - Uptime monitoring (Uptime Robot, Nagios)
  - Disaster recovery plan documented

---

### 5.5.2 Panduan Pengujian Availability/Reliability

#### **Test Case 1: Uptime Monitoring Setup**

**Objective:** Setup automated uptime monitoring untuk track 99% target

**Test Steps:**
1. Setup Uptime Robot (uptime.com) atau alternative monitoring service
2. Configure endpoint monitoring:
   - Homepage: `https://assessment.itera.ac.id/`
   - Health check: `https://assessment.itera.ac.id/health`
   - Admin dashboard: `https://assessment.itera.ac.id/admin/mental-health`
3. Set monitoring interval: 5 menit (check setiap 5 menit)
4. Set alert threshold: down untuk 2+ menit
5. Setup notification: email alert saat down
6. Monitor selama 30 hari, catat downtime
7. Hitung uptime percentage: (Total Minutes - Downtime Minutes) / Total Minutes * 100%
8. Verifikasi uptime >= 99%

**Expected Results:**
- ✅ Monitoring tools setup dan running
- ✅ Email alerts diterima saat down
- ✅ Dashboard monitoring show uptime % >= 99%
- ✅ Total downtime <= 7 jam per bulan
- ✅ Response time konsisten < 1 detik

**Tools:** Uptime Robot, Nagios, Zabbix, New Relic

**Pass Criteria:** 30-day uptime >= 99%, downtime <= 7 hours/month

---

#### **Test Case 2: Health Check Endpoint**

**Objective:** Verifikasi endpoint `/health` return status server, DB, cache

**Test Steps:**
1. Akses endpoint `/health`:
   ```bash
   curl https://assessment.itera.ac.id/health
   ```
2. Verifikasi response JSON format:
   ```json
   {
     "status": "healthy",
     "server": "online",
     "database": "connected",
     "cache": "connected",
     "timestamp": "2025-11-26T10:30:00Z"
   }
   ```
3. Test database down scenario: stop database service
4. Akses `/health` lagi, verifikasi response:
   ```json
   {
     "status": "unhealthy",
     "database": "disconnected"
   }
   ```
5. Restart database, verifikasi response kembali healthy
6. Test cache down scenario, verifikasi response
7. Verify endpoint return HTTP 200 (healthy) atau 503 (unhealthy)

**Expected Results:**
- ✅ Endpoint `/health` accessible
- ✅ Response format JSON valid
- ✅ Status correct reflect actual system state
- ✅ HTTP status code appropriate (200 healthy, 503 unhealthy)
- ✅ Monitoring tools dapat parse response dan alert

**Tools:** curl, Postman, Monitoring tools (Uptime Robot, Nagios)

**Pass Criteria:** Health check endpoint working correctly, status accurate

---

#### **Test Case 3: Database Backup Verification**

**Objective:** Verifikasi automated backup running dan file backup valid

**Test Steps:**
1. Check backup schedule di server cron jobs:
   ```bash
   crontab -l | grep backup
   ```
2. Verifikasi cron job untuk:
   - Full backup setiap hari @ 02:00 AM
   - Incremental backup setiap 6 jam
3. Check backup storage location (contoh: `/backups/`)
4. Verifikasi backup files ada dan size reasonable:
   ```bash
   ls -lh /backups/
   ```
5. Check backup file modification time, verifikasi file berubah sesuai schedule
6. Test restore dari backup:
   - Create test database baru: `mysql -u root -p < /backups/latest_backup.sql`
   - Verifikasi schema dan data ter-restore dengan baik
   - Query count: `SELECT COUNT(*) FROM users, data_diris, hasil_kuesioners`
7. Verify backup kept minimal 30 days, old files auto-deleted after 30 days

**Expected Results:**
- ✅ Backup cron job running sesuai schedule
- ✅ Backup files valid dan dapat di-restore
- ✅ Restored data matches dengan production
- ✅ Backup retention policy 30 hari berfungsi
- ✅ Backup verification automated (checksum, integrity check)

**Tools:** cron, MySQL backup tools, shell script

**Pass Criteria:** Backup running on schedule, restorable, 30-day retention

---

#### **Test Case 4: Cascade Delete & Data Integrity**

**Objective:** Verifikasi cascade delete berfungsi benar saat hapus mahasiswa

**Test Steps:**
1. Login sebagai admin
2. Lihat dashboard, pilih mahasiswa untuk di-delete (contoh: NIM `121450001`)
3. Catat jumlah related records:
   - `data_diris` count: 1 record
   - `riwayat_keluhans` count untuk NIM ini: X records
   - `hasil_kuesioners` count untuk NIM ini: Y records
   - `users` count: 1 record
4. Klik tombol "Hapus"
5. Confirm delete di modal
6. Verifikasi success message ditampilkan
7. Query database untuk verifikasi cascade delete:
   ```sql
   SELECT COUNT(*) FROM users WHERE nim = '121450001';                -- expect 0
   SELECT COUNT(*) FROM data_diris WHERE nim = '121450001';           -- expect 0
   SELECT COUNT(*) FROM riwayat_keluhans WHERE nim = '121450001';     -- expect 0
   SELECT COUNT(*) FROM hasil_kuesioners WHERE nim = '121450001';     -- expect 0
   ```
8. Verifikasi semua records ter-delete (count = 0)
9. Verifikasi tidak ada orphan records di related tables

**Expected Results:**
- ✅ Delete berhasil
- ✅ All related records di cascade delete otomatis
- ✅ No orphan records left
- ✅ Database transaction berhasil
- ✅ Foreign key constraint berfungsi

**Tools:** Database client, Admin panel

**Pass Criteria:** Cascade delete working correctly, 0 orphan records

---

#### **Test Case 5: Transaction Rollback on Error**

**Objective:** Verifikasi database transaction rollback saat error

**Test Steps:**
1. Create test scenario: submit kuesioner dengan intentional error di middleware
2. Setup test code:
   ```php
   DB::beginTransaction();
   // Insert hasil_kuesioner
   $hasil = HasilKuesioner::create([...]);
   // Insert riwayat_keluhan
   $riwayat = RiwayatKeluhan::create([...]);
   // Simulate error/exception
   throw new Exception("Simulated error");
   DB::commit(); // ini tidak di-execute
   ```
3. Verifikasi exception di-catch dengan try-catch
4. Query database: verifikasi tidak ada data inserted (transaction rollback)
5. Verifikasi error logged di `storage/logs/laravel.log`

**Expected Results:**
- ✅ Exception di-handle dengan graceful
- ✅ Partial data tidak inserted (transaction rollback)
- ✅ No inconsistent state
- ✅ User melihat error message friendly
- ✅ Error logged untuk debugging

**Tools:** Database client, Log viewer, Test code

**Pass Criteria:** Transaction rollback on error, no partial data

---

#### **Test Case 6: Error Handling & User-Friendly Error Pages**

**Objective:** Verifikasi error pages (404, 500, 503) user-friendly

**Test Steps:**
1. Test 404 error:
   - Akses URL tidak ada: `/nonexistent-page`
   - Verifikasi HTTP 404 response
   - Verifikasi page ditampilkan dengan:
     - Friendly message: "Halaman tidak ditemukan"
     - Link kembali ke home
     - Logo/branding aplikasi
2. Test 500 error:
   - Trigger exception di code (contoh: divide by zero)
   - Verifikasi HTTP 500 response
   - Verifikasi error message di log, bukan di browser (hide details dari user)
   - Production mode: show generic "Terjadi kesalahan, hubungi administrator"
3. Test 503 error (service unavailable):
   - Stop database service
   - Akses aplikasi
   - Verifikasi 503 response dengan message "Database tidak tersedia"
4. Restart database, verifikasi aplikasi recover otomatis

**Expected Results:**
- ✅ 404 page friendly dengan link navigasi
- ✅ 500 page hide technical details, show generic message
- ✅ 503 page inform user tentang service status
- ✅ Error details logged untuk debugging (developer only)
- ✅ User dapat navigasi kembali ke aplikasi

**Tools:** Browser, Logging tools

**Pass Criteria:** Error pages user-friendly, technical details hidden

---

#### **Test Case 7: Error Logging & Monitoring**

**Objective:** Verifikasi semua error logged dengan sufficient context

**Test Steps:**
1. Trigger various errors (validation error, database error, permission error)
2. Check log file: `storage/logs/laravel.log`
3. Verifikasi setiap error entry berisi:
   - Timestamp: `[2025-11-26 10:30:45]`
   - Log level: ERROR, WARNING, INFO
   - Message: deskripsi error
   - Context: user_id, URL, request method, input data (without sensitive)
   - Stack trace (untuk debugging)
4. Verify log rotation: old logs archived atau deleted sesuai policy
5. Setup log monitoring (contoh: use external service untuk aggregasi logs)

**Expected Results:**
- ✅ All errors logged dengan context sufficient
- ✅ Stack trace di-include untuk debugging
- ✅ Sensitive data (passwords) not logged
- ✅ Log rotation working
- ✅ Log accessible untuk administrator

**Tools:** Log viewer, Text editor, Log aggregation service

**Pass Criteria:** Comprehensive logging with context, no sensitive data

---

### 5.5.3 Availability Test Summary

| Test Case | Requirement | Method | Status |
|-----------|------------|--------|--------|
| 1. Uptime Monitoring | 99% uptime | External monitoring tool | To-Do |
| 2. Health Check | Endpoint status | API test | To-Do |
| 3. Database Backup | Daily backup, 30-day retention | Cron job verification | To-Do |
| 4. Cascade Delete | Delete with integrity | Manual + SQL query | To-Do |
| 5. Transaction Rollback | Rollback on error | Test scenario | To-Do |
| 6. Error Handling | User-friendly pages | Manual navigation | To-Do |
| 7. Error Logging | Comprehensive logging | Log file inspection | To-Do |

---

## 5.4 Usability (Kemudahan Penggunaan)

### 5.4.1 Deskripsi Kebutuhan

Sistem harus mudah digunakan tanpa pelatihan khusus. Prinsip usability:

- **Interface & Navigation:**
  - Interface intuitif, navigasi jelas & konsisten
  - Navbar tetap di atas di semua halaman
  - Menu di tempat sama di semua halaman

- **Responsiveness:**
  - Mobile 320px-480px, tablet 768px, desktop 1920px+
  - Tailwind CSS responsive utilities
  - Accessible di berbagai ukuran layar

- **Error Messages & Feedback:**
  - Pesan error jelas, spesifik, helpful (contoh: "Alamat wajib diisi" bukan "Error")
  - Loading indicator untuk proses > 500ms
  - Inline validation real-time

- **Accessibility:**
  - Contrast ratio WCAG 2.0 AA (4.5:1 normal text, 3:1 large text)
  - Font minimal 14px body, 16px mobile
  - Button/link 44x44px minimum (touch target guideline)
  - Label jelas untuk setiap form field

- **User Guidance:**
  - Placeholder helpful (contoh: "Contoh: 121450123")
  - Breadcrumb/indicator multi-step form
  - Confirmation dialog untuk destructive action

- **Validation:**
  - User testing dengan 5-10 users before launch
  - Collect feedback on usability

---

### 5.4.2 Panduan Pengujian Usability

#### **Test Case 1: Navigation Clarity & Consistency**

**Objective:** Verifikasi navigasi intuitif, consistent, dan jelas

**Test Steps:**
1. Akses aplikasi dari homepage
2. Inspect navbar position: tetap di atas saat scroll? ✓ Sticky navbar
3. Check navbar items di semua halaman: sama? ✓ Consistent
4. Test breadcrumb di halaman nested:
   - Homepage → Login → Google OAuth → Dashboard → Kuesioner → Hasil
   - Verifikasi breadcrumb akurat: "Home > Mental Health > Kuesioner > Hasil"
5. Test back button: navigasi kembali ke halaman sebelumnya
6. Ulangi di beberapa halaman, verifikasi konsistensi
7. Test menu di mobile (hamburger menu): accessible? Responsive?
8. Test footer: link ke halaman penting (Home, About, Contact, Privacy Policy)

**Expected Results:**
- ✅ Navbar sticky di top, accessible dari semua halaman
- ✅ Menu items konsisten
- ✅ Breadcrumb akurat & membantu user orientasi
- ✅ Back button berfungsi correct
- ✅ Menu responsive di mobile
- ✅ Footer links working

**Tools:** Browser, Mobile device/emulator

**Pass Criteria:** Navigasi consistent, intuitif, accessible di semua halaman

---

#### **Test Case 2: Responsive Design (Mobile, Tablet, Desktop)**

**Objective:** Verifikasi layout responsive di berbagai ukuran layar

**Test Steps:**
1. Test di mobile portrait 320px (iPhone SE):
   - Homepage: layout vertikal, readable?
   - Form: input fields full width, accessible?
   - Tabel: scrollable horizontal? Data not cut off?
   - Chart: scaled down, still readable?
2. Test di mobile landscape 480px:
   - Content visible without scroll?
   - Layout adjust untuk landscape?
3. Test di tablet portrait 768px (iPad):
   - 2-column layout jika applicable?
   - Font size readable?
4. Test di desktop 1280px:
   - Multi-column layout?
   - Optimal spacing?
5. Test di large desktop 1920px (4K):
   - Content tidak spread too wide?
   - Max-width constraint applied?
6. Test browser zoom 100%-200%:
   - Layout tidak broken?
   - Text readable?

**Expected Results:**
- ✅ Content accessible di semua breakpoints
- ✅ Layout adapt ke screen size
- ✅ No horizontal scrollbar di mobile
- ✅ Touch targets >= 44x44px di mobile
- ✅ Zoom 100%-200% supported tanpa broken layout
- ✅ Using Tailwind breakpoints: sm:640px, md:768px, lg:1024px, xl:1280px, 2xl:1536px

**Tools:** Browser DevTools (Device toolbar), BrowserStack, Real devices

**Pass Criteria:** Fully responsive di semua breakpoints (mobile-desktop)

---

#### **Test Case 3: Error Messages - Clear & Helpful**

**Objective:** Verifikasi pesan error jelas, spesifik, actionable

**Test Steps:**
1. Test form validation error:
   - Submit empty form
   - Verifikasi error message untuk setiap field:
     - ❌ "Error" (generic, not helpful)
     - ✅ "Nama wajib diisi" (specific, clear, actionable)
2. Test email validation error:
   - Input email non-ITERA
   - Verifikasi message: "Email harus menggunakan domain @student.itera.ac.id"
3. Test kuesioner incomplete error:
   - Submit kuesioner kurang dari 38 jawaban
   - Verifikasi message: "Harap jawab semua 38 pertanyaan"
4. Test permission error:
   - Mahasiswa coba akses admin page
   - Verifikasi message: "Anda tidak punya akses ke halaman ini"
5. Verify error message color: red/warning color
6. Verify error message position: near invalid field (inline error)
7. Verify old input preserved: user tidak perlu isi ulang dari awal

**Expected Results:**
- ✅ Error messages specific & actionable
- ✅ Color coding untuk error (red)
- ✅ Positioned near error field
- ✅ Old input preserved (using `old()` helper)
- ✅ Error messages helpful untuk fix issue
- ✅ Zero generic "Error" messages

**Tools:** Browser, Manual form testing

**Pass Criteria:** Error messages specific, clear, actionable, helpful

---

#### **Test Case 4: Loading Indicators & Feedback**

**Objective:** Verifikasi loading indicator ditampilkan untuk proses > 500ms

**Test Steps:**
1. Test export Excel (sering slow):
   - Klik "Export to Excel" button
   - Verifikasi spinner atau progress bar ditampilkan
   - Button disabled/grayed out (prevent double click)
   - Verifikasi message: "Exporting data... please wait"
   - After complete, verifikasi file downloaded
2. Test search (moderate speed):
   - Search dengan keyword
   - Verifikasi spinner ditampilkan jika delay
3. Test kuesioner submit:
   - Submit kuesioner
   - Verifikasi loading spinner ditampilkan
   - Button disabled (prevent double submit)
   - Verifikasi success message atau redirect
4. Test chart rendering (dengan large dataset):
   - Dashboard admin dengan 10,000+ records
   - Verify loading skeleton atau spinner for chart
   - Chart render setelah data loaded

**Expected Results:**
- ✅ Spinner/progress bar ditampilkan untuk proses > 500ms
- ✅ Button disabled during loading (prevent double submit)
- ✅ User informed system working
- ✅ After complete, loading indicator disappear
- ✅ Success/error message displayed
- ✅ Better UX, user tidak think app frozen

**Tools:** Browser, Developer tools (Throttling untuk simulate slow network)

**Pass Criteria:** Loading indicators present for processes > 500ms

---

#### **Test Case 5: WCAG 2.0 Accessibility - Contrast Ratio**

**Objective:** Verifikasi contrast ratio memenuhi WCAG 2.0 AA standard

**Test Steps:**
1. Inspect font color vs background color di critical areas:
   - Body text: black (#000000) on white (#ffffff) → 21:1 (excellent)
   - Error message: red (#dc2626) on white (#ffffff) → ~5.8:1 (AA compliant)
   - Success message: green on white
   - Button text vs button background
2. Use online contrast checker (WebAIM Contrast Checker):
   - Input foreground color (text)
   - Input background color
   - Check ratio >= 4.5:1 for normal text (AA compliant)
   - Check ratio >= 3:1 for large text (AA compliant)
3. Use automated tools (axe DevTools, WAVE, Lighthouse):
   - Install browser extension
   - Audit pages untuk contrast violations
   - Verifikasi 0 failures
4. Test color blind: use color blind simulator (simulate red-green blindness)
   - Verify information still conveyed bukan hanya via color

**Expected Results:**
- ✅ Contrast ratio >= 4.5:1 untuk normal text (WCAG AA)
- ✅ Contrast ratio >= 3:1 untuk large text (WCAG AA)
- ✅ 0 contrast ratio violations detected
- ✅ Color blind friendly (information not only via color)
- ✅ Text color distinct dari background

**Tools:** WebAIM Contrast Checker, axe DevTools, Lighthouse, Color blind simulator

**Pass Criteria:** All text meets WCAG AA contrast requirements (4.5:1)

---

#### **Test Case 6: Font Size & Readability**

**Objective:** Verifikasi font size sufficient untuk readability

**Test Steps:**
1. Measure font sizes di key areas:
   - Body text: >= 14px desktop, >= 16px mobile
   - Heading H1: typically 28-32px desktop
   - Heading H2: typically 22-26px
   - Label: >= 14px
   - Small text (helper): >= 12px (jika ada)
2. Test readability saat zoom 100%-150%:
   - Text tetap readable
   - No text cutoff
3. Inspect CSS font-size di DevTools:
   - `p { font-size: 14px; }` ✓
   - `small { font-size: 12px; }` ✓ (still readable)
   - `.text-sm { font-size: 0.875rem; }` di Tailwind ✓
4. Test font family: use sans-serif (readable di screen)
   - Good: Arial, Helvetica, Segoe UI, Roboto (system fonts)
   - Check mobile di-render dengan system font (avoid web font loading issues)

**Expected Results:**
- ✅ Body text >= 14px desktop
- ✅ Body text >= 16px mobile
- ✅ Headings appropriately sized
- ✅ Text readable at 100%-150% zoom
- ✅ Sans-serif font family (readable)
- ✅ No text cutoff

**Tools:** Browser DevTools, Ruler browser extension

**Pass Criteria:** Font sizes meet accessibility standards

---

#### **Test Case 7: Button & Link Size (Touch Targets)**

**Objective:** Verifikasi button dan link ukuran >= 44x44px (touch-friendly)

**Test Steps:**
1. Measure button size di DevTools (Inspect Element):
   - Check CSS width & height
   - Click tombol menggunakan developer tools: measure bounding box
   - Verifikasi >= 44x44px (iOS/Android guideline)
2. Test primary buttons (Login, Submit, Save):
   - Size: typically 40-48px height, full width di mobile
   - Padding: 12px vertical, 16px horizontal minimum
3. Test secondary buttons (Cancel, Back):
   - Size: consistent dengan primary
4. Test link underlines:
   - Links underlined atau colored differently dari regular text
   - Hover state clear (color change, underline appears)
5. Test spacing between clickable elements:
   - Verifikasi >= 8px spacing (avoid accidental misclick)
6. Test di mobile device: can tap comfortably?

**Expected Results:**
- ✅ Buttons >= 44x44px (touch-friendly)
- ✅ Adequate spacing between buttons
- ✅ Link identifiable (underline, color, hover state)
- ✅ Touch targets easily tappable di mobile
- ✅ No frustration dari mis-click

**Tools:** Browser DevTools, Mobile device/emulator

**Pass Criteria:** All clickable elements >= 44x44px, well-spaced

---

#### **Test Case 8: Form Labels & Placeholders**

**Objective:** Verifikasi form fields punya label jelas dan helpful placeholders

**Test Steps:**
1. Inspect form di data diri page (`/mental-health/isi-data-diri`):
   - Setiap input field punya `<label>` tag?
   - Label text jelas: "Nama", "NIM", "Usia", bukan "Field 1", "Field 2"
2. Check label association:
   ```html
   <label for="nama">Nama Lengkap:</label>
   <input id="nama" name="nama" type="text">
   <!-- Good: label connected to input -->
   ```
3. Test placeholder text:
   - Example text membantu user: "Contoh: 121450123" untuk NIM
   - Not replace label (label tetap visible)
   - Placeholder disappear saat user type
4. Verify required field indicator:
   - Asterisk (*) di required fields: "Nama *"
   - Clear indication yang field required
5. Test form in mobile: labels readable? Not cut off?

**Expected Results:**
- ✅ Setiap field punya label
- ✅ Label text clear & descriptive
- ✅ Label associated ke input (for/id match)
- ✅ Helpful placeholder text provided
- ✅ Required field indicator (asterisk or text)
- ✅ Form accessible via keyboard (tab order)

**Tools:** Browser DevTools, Accessibility inspector, Screen reader (for testing)

**Pass Criteria:** All form fields have clear labels, helpful placeholders

---

#### **Test Case 9: Breadcrumb & Form Progress Indicator**

**Objective:** Verifikasi user tahu posisi mereka di multi-step form

**Test Steps:**
1. Kuesioner workflow: Homepage → Data Diri → Kuesioner → Hasil
2. Check breadcrumb di setiap step:
   - Step 1 (Data Diri): "Home > Mental Health > Data Diri" ✓
   - Step 2 (Kuesioner): "Home > Mental Health > Kuesioner" (atau progress indicator)
   - Step 3 (Hasil): "Home > Mental Health > Hasil Tes"
3. Verify progress indicator:
   - Visual progress bar: "Step 2 of 3"
   - Percentage: "66% Complete"
   - Next/Previous buttons jelas
4. Test navigation:
   - Can go back to previous step?
   - Can go forward only if current step complete?
5. Test completion:
   - Show success message di step 3: "Selamat! Tes Anda selesai"

**Expected Results:**
- ✅ Breadcrumb accurate di setiap step
- ✅ Progress indicator visible
- ✅ User tahu posisi mereka (step 1 of 3)
- ✅ Next/Previous navigation clear
- ✅ Success message di akhir
- ✅ No confusion tentang workflow

**Tools:** Browser

**Pass Criteria:** Clear indication of form progress, user always knows position

---

#### **Test Case 10: Confirmation Dialog untuk Destructive Action**

**Objective:** Verifikasi confirm dialog untuk hapus data, logout, dsb

**Test Steps:**
1. Test logout:
   - Klik menu profil → Logout
   - Verifikasi confirm dialog: "Apakah Anda yakin ingin logout?"
   - Two buttons: "Batal", "Ya, Logout"
   - Cancel jangan logout
   - Confirm execute logout
2. Test delete data (admin):
   - Dashboard admin, klik delete button
   - Verifikasi confirm dialog: "Apakah Anda yakin ingin menghapus data ini?"
   - Warning message: "Tindakan ini tidak dapat dibatalkan (permanen)"
   - Two buttons: "Batal" (abu), "Ya, Hapus" (merah)
   - Cancel jangan delete
   - Confirm execute delete
3. Verify dialog accessibility:
   - Can dismiss dengan ESC key
   - Can dismiss dengan click outside
   - Focus management (focus trap di dialog, return to trigger button saat dismiss)

**Expected Results:**
- ✅ Confirm dialog untuk destructive action
- ✅ Clear warning message
- ✅ Prevent accidental deletion
- ✅ Dialog modal (block background)
- ✅ ESC key dismiss support
- ✅ Button colors indicate action (red for danger)

**Tools:** Browser

**Pass Criteria:** Confirm dialog prevents accidental destructive action

---

#### **Test Case 11: User Testing (5-10 Real Users)**

**Objective:** Collect usability feedback dari real users (mahasiswa & admin)

**Test Steps:**
1. Recruit 5-10 users:
   - 5 mahasiswa (for user interface)
   - 2-3 admin (for admin interface)
2. Setup test session (30-45 minutes):
   - Give tasks to complete (contoh: "Silakan isi kuesioner dan lihat hasil")
   - Observe tanpa membimbing (let user figure out)
   - Record feedback, comments, pain points
3. After task, conduct interview:
   - "Was interface intuitive?"
   - "Did you understand each step?"
   - "What was confusing?"
   - "What would improve?"
4. Document findings:
   - Create summary report
   - List top 3-5 pain points
   - Prioritize improvements
5. Implement improvements based on feedback
6. Re-test dengan sample users

**Expected Results:**
- ✅ Usability testing conducted dengan real users
- ✅ Feedback documented
- ✅ Pain points identified
- ✅ Improvements implemented
- ✅ Re-tested for confirmation
- ✅ Report shows: "System is easy to use" (feedback positive)

**Tools:** Zoom (for recording), Survey forms (Google Forms), Notes

**Pass Criteria:** Usability testing completed, feedback positive (>=4/5 rating)

---

### 5.4.3 Usability Test Summary

| Test Case | Requirement | Method | Status |
|-----------|------------|--------|--------|
| 1. Navigation | Consistent, clear | Manual inspection | To-Do |
| 2. Responsiveness | Mobile-desktop | Browser DevTools | To-Do |
| 3. Error Messages | Clear, specific | Form validation test | To-Do |
| 4. Loading Indicators | Show for > 500ms | Manual test | To-Do |
| 5. Contrast Ratio | WCAG AA (4.5:1) | Contrast checker | To-Do |
| 6. Font Size | >= 14px body | DevTools inspection | To-Do |
| 7. Touch Targets | >= 44x44px | Measurement | To-Do |
| 8. Form Labels | Clear labels | Form inspection | To-Do |
| 9. Progress Indicator | Multi-step form | Workflow test | To-Do |
| 10. Confirmation Dialog | Destructive action | Manual test | To-Do |
| 11. User Testing | 5-10 real users | Interview & observation | To-Do |

---

## 5.7 Compatibility/Portability (Kompatibilitas)

### 5.7.1 Deskripsi Kebutuhan

Sistem harus kompatibel dengan berbagai browser, device, dan platform. Target:

- **Browser Compatibility:**
  - Chrome versi 2 terbaru (120, 121)
  - Firefox versi 2 terbaru
  - Safari versi 2 terbaru (macOS & iOS)
  - Edge versi 2 terbaru (Chromium-based)
  - Tidak support IE11
  - Test dengan BrowserStack atau real devices

- **Server Compatibility:**
  - Linux Ubuntu 20.04 LTS / 22.04 LTS (production recommended)
  - macOS (development)
  - Windows + XAMPP/Laragon (development)

- **Database:**
  - SQLite 3.x (development)
  - MySQL 8.0 / MariaDB 10.x (production)

- **PHP Version:**
  - Minimal PHP 8.2 (Laravel 11 requirement)
  - Recommended PHP 8.3

- **Responsive Design:**
  - Mobile portrait: 320px-480px
  - Mobile landscape: 481px-767px
  - Tablet portrait: 768px-1024px
  - Desktop: 1025px-1920px
  - Large desktop: > 1920px (4K)
  - Breakpoints: sm:640px, md:768px, lg:1024px, xl:1280px, 2xl:1536px

- **Progressive Enhancement:**
  - Core functionality tanpa JavaScript
  - Enhanced UX dengan JavaScript enabled

- **Accessibility:**
  - Keyboard navigation support
  - Screen reader compatibility
  - Color blind friendly
  - Browser zoom 100%-200%

---

### 5.7.2 Panduan Pengujian Compatibility/Portability

#### **Test Case 1: Browser Compatibility Testing**

**Objective:** Verifikasi aplikasi berfungsi di semua major browsers

**Test Steps:**
1. **Chrome** (latest 2 versions, saat ini v120, v121):
   - Download Chrome installer
   - Install dan buka aplikasi
   - Test workflow lengkap: Login → Dashboard → Kuesioner → Result
   - Test forms: input, validation, submit
   - Test export Excel
   - Verify responsiveness at different viewport sizes
   - Check console untuk errors: F12 → Console tab
   - Expected: 0 error messages

2. **Firefox** (latest 2 versions):
   - Same testing flow seperti Chrome
   - Khusus test browser-specific features (history, caching)
   - Check DevTools console

3. **Safari** (macOS & iOS):
   - Open Safari di macOS
   - Test aplikasi
   - For iOS: use real iPhone atau iOS simulator
   - Test touch interactions
   - Verify responsive layout di berbagai iPhone models (SE, 12, 14 Pro Max)

4. **Edge** (Chromium-based):
   - Open Edge
   - Same testing flow

5. **NOT Support IE11**:
   - If legacy user tries IE11, show warning message
   - Graceful degradation: core functionality works, JS features disabled

**Expected Results:**
- ✅ Aplikasi works di Chrome v120, v121
- ✅ Aplikasi works di Firefox latest 2 versions
- ✅ Aplikasi works di Safari (macOS & iOS)
- ✅ Aplikasi works di Edge latest 2 versions
- ✅ No JavaScript errors di console
- ✅ Forms submit correctly di semua browsers
- ✅ Layout responsive di semua browsers
- ✅ IE11 not supported (graceful message jika diakses)

**Tools:** BrowserStack (cloud-based cross-browser testing), Or real devices

**Pass Criteria:** Works in Chrome, Firefox, Safari, Edge. Zero errors.

---

#### **Test Case 2: Mobile Device Compatibility**

**Objective:** Verifikasi aplikasi works correctly di berbagai mobile devices

**Test Steps:**
1. **Test iPhone models:**
   - iPhone SE (2020): 375px width
   - iPhone 12: 390px width
   - iPhone 14 Pro Max: 430px width
   - Test portrait & landscape orientation
   - Test touch interactions (tap, double-tap, long-press)
   - Test keyboard behavior (soft keyboard doesn't break layout)

2. **Test Android devices:**
   - Google Pixel 6: 412px width
   - Samsung Galaxy S21: 360px width
   - Test similar workflows

3. **Test tablet:**
   - iPad Pro 12.9": 1024px width
   - Test 2-column layout (if applicable)

4. **Test functionalities di mobile:**
   - Touch targets large enough (>= 44x44px)
   - Form fields accessible
   - Kuesioner readable & answerable
   - Export Excel works
   - Charts readable di mobile (zoomed/scrollable)

**Expected Results:**
- ✅ App works di iPhone, Android, iPad
- ✅ Layout responsive di semua devices
- ✅ No horizontal scrollbar
- ✅ Touch interactions work
- ✅ Keyboard doesn't break layout
- ✅ All features accessible di mobile

**Tools:** Real devices, iOS Simulator (Xcode), Android Emulator

**Pass Criteria:** Full functionality on mobile (iPhone, Android, Tablet)

---

#### **Test Case 3: Server OS Compatibility**

**Objective:** Verifikasi aplikasi berjalan di berbagai server OS

**Test Steps:**
1. **Linux Ubuntu 20.04 LTS:**
   - Setup fresh Ubuntu 20.04 server
   - Install PHP 8.2, MySQL 8.0, Nginx
   - Clone repository
   - Run migrations & seed
   - Verify aplikasi accessible via http://server-ip/
   - Run tests: `php artisan test`
   - All tests pass?

2. **Linux Ubuntu 22.04 LTS:**
   - Same setup, same verification

3. **macOS (development):**
   - Setup dengan Homebrew atau Docker
   - Install PHP 8.2, MySQL, Nginx
   - Verify aplikasi runs locally
   - `php artisan serve` works?

4. **Windows + Laragon (development):**
   - Setup Laragon atau XAMPP
   - Verify aplikasi accessible
   - `php artisan serve` works?

**Expected Results:**
- ✅ Aplikasi runs di Ubuntu 20.04 LTS
- ✅ Aplikasi runs di Ubuntu 22.04 LTS
- ✅ Aplikasi runs di macOS
- ✅ Aplikasi runs di Windows (Laragon/XAMPP)
- ✅ Database migrations work di semua OS
- ✅ All tests pass di semua OS
- ✅ No OS-specific issues

**Tools:** Virtual machines (VirtualBox, VMware), Docker, Laragon, macOS

**Pass Criteria:** App runs on Ubuntu 20.04, Ubuntu 22.04, macOS, Windows

---

#### **Test Case 4: Database Compatibility**

**Objective:** Verifikasi aplikasi compatible dengan SQLite dan MySQL

**Test Steps:**
1. **SQLite 3.x (development):**
   - Default database di development
   - Migrations run successfully?
   - CRUD operations work?
   - Query performance acceptable?
   - Backup & restore works?

2. **MySQL 8.0 (production):**
   - Create fresh MySQL database
   - Run migrations: `php artisan migrate`
   - Seed test data: `php artisan db:seed`
   - CRUD operations work?
   - Complex queries (JOIN, subquery, aggregation) work?
   - Indexes created successfully?
   - Cascade delete works?

3. **MariaDB 10.x (alternative production):**
   - Setup MariaDB
   - Same testing flow
   - Verify compatibility dengan Laravel queries

4. **Connection pooling:**
   - MySQL max_connections set appropriately
   - Connection pooling configured (if needed)

**Expected Results:**
- ✅ SQLite works di development
- ✅ MySQL 8.0 works di production
- ✅ MariaDB 10.x compatible
- ✅ Migrations, seeding, queries work di semua database
- ✅ Data integrity maintained
- ✅ Cascade delete works correctly
- ✅ Performance acceptable

**Tools:** SQLite browser, MySQL client, MariaDB client

**Pass Criteria:** Compatible with SQLite, MySQL 8.0, MariaDB 10.x

---

#### **Test Case 5: PHP Version Compatibility**

**Objective:** Verifikasi aplikasi berjalan di PHP 8.2 & 8.3

**Test Steps:**
1. **PHP 8.2 (minimum):**
   - Check PHP version: `php -v`
   - Verify >= 8.2.x
   - Run: `php artisan serve`
   - Test main workflows
   - Run tests: `php artisan test`
   - All pass?

2. **PHP 8.3 (recommended):**
   - Upgrade PHP to 8.3.x
   - Repeat testing
   - Verify no deprecation warnings
   - Performance improvement checked?

3. **Check compatibility issues:**
   - Any deprecated functions used?
   - Run static analysis: `./vendor/bin/phpstan analyse app/`
   - Check for PHP 8 breaking changes

**Expected Results:**
- ✅ App runs di PHP 8.2
- ✅ App runs di PHP 8.3
- ✅ No deprecation warnings
- ✅ All tests pass
- ✅ No breaking changes encountered

**Tools:** PHP CLI, PHPStan, Laravel Pint

**Pass Criteria:** Compatible with PHP 8.2 (minimum), PHP 8.3 (recommended)

---

#### **Test Case 6: Responsive Design - All Breakpoints**

**Objective:** Verifikasi responsive design works di semua breakpoints

**Test Steps:**
1. **Mobile Portrait (320px - 480px):**
   - Use Chrome DevTools: set viewport to 375px
   - Test layout: single column, vertical stack
   - Navigation: hamburger menu active
   - Form fields: full width
   - Table: horizontally scrollable
   - Verify: no cut-off, no horizontal scroll (except table)

2. **Mobile Landscape (481px - 767px):**
   - Set viewport to 640px
   - Layout adapts for wider screen?
   - Navigation: hamburger or horizontal?

3. **Tablet (768px - 1024px):**
   - Set viewport to 800px
   - 2-column layout active?
   - Sidebar visible or hamburger?
   - Content readable?

4. **Desktop (1025px - 1920px):**
   - Set viewport to 1200px
   - Multi-column layout optimal?
   - Spacing & alignment good?

5. **Large Desktop (> 1920px):**
   - Set viewport to 2560px (4K)
   - Content doesn't spread too wide?
   - Max-width constraint applied?

6. **Verify Tailwind breakpoints:**
   - sm:640px, md:768px, lg:1024px, xl:1280px, 2xl:1536px used correctly?

**Expected Results:**
- ✅ Layout responsive at 320px - 1920px+
- ✅ No horizontal scrollbar (except intentional)
- ✅ Readable at all breakpoints
- ✅ Touch targets >= 44x44px di mobile
- ✅ Hamburger menu appears at appropriate breakpoint
- ✅ 2-column/multi-column layout at tablet/desktop

**Tools:** Chrome DevTools (Device toolbar), Browser zoom

**Pass Criteria:** Perfect responsive design at all breakpoints

---

#### **Test Case 7: Progressive Enhancement (No JavaScript)**

**Objective:** Verifikasi core functionality works tanpa JavaScript

**Test Steps:**
1. Disable JavaScript di browser:
   - Chrome DevTools → Settings (F1) → Disable JavaScript
   - Or use browser extension (ScriptSafe, uMatrix)
2. Test core workflows:
   - **Login (Google OAuth):** JavaScript required untuk redirect
     - Graceful message: "JavaScript diperlukan untuk login"
   - **Form submission:** Form can submit via traditional POST? ✓
   - **Navigation:** Links work tanpa JavaScript? ✓
   - **Data display:** Static content visible? ✓
3. Re-enable JavaScript:
   - AJAX forms work? ✓
   - Real-time validation works? ✓
   - Charts render? ✓
   - Smooth transitions? ✓

**Expected Results:**
- ✅ Core functionality works tanpa JavaScript (forms, navigation)
- ✅ OAuth likely needs JS (acceptable)
- ✅ Enhanced UX dengan JavaScript enabled
- ✅ Graceful degradation implemented
- ✅ No hard-to-understand errors

**Tools:** Browser DevTools, JavaScript disable extensions

**Pass Criteria:** Core features work without JavaScript, enhanced with JS

---

#### **Test Case 8: Keyboard Navigation (Accessibility)**

**Objective:** Verifikasi aplikasi fully navigable via keyboard

**Test Steps:**
1. **Tab navigation:**
   - Start di homepage
   - Press Tab repeatedly
   - Verify focus indicator visible (outline/highlight)
   - Focus order logical: top-to-bottom, left-to-right
   - All interactive elements (button, link, input) reachable via Tab

2. **Enter/Space activation:**
   - Tab ke button
   - Press Enter atau Space
   - Button activated (click triggered)
   - Form submitted

3. **ESC key:**
   - Open modal
   - Press ESC
   - Modal closes
   - Focus returns to trigger element

4. **Form navigation:**
   - Tab through form fields
   - Can type di input fields
   - Can select dari dropdown
   - Can check checkbox/radio with Space
   - Can submit with Enter

5. **Skip links:**
   - Check untuk "Skip to main content" link
   - Help keyboard user jump over repetitive navigation

**Expected Results:**
- ✅ Tab focuses all interactive elements
- ✅ Focus indicator visible
- ✅ Focus order logical
- ✅ Enter/Space activates buttons
- ✅ ESC closes modal
- ✅ Form fully accessible via keyboard
- ✅ Skip links present (optional but recommended)

**Tools:** Browser (no extensions needed), Keyboard only

**Pass Criteria:** Full keyboard navigation support, logical focus order

---

#### **Test Case 9: Screen Reader Compatibility**

**Objective:** Verifikasi aplikasi works dengan screen reader (NVDA, JAWS, VoiceOver)

**Test Steps:**
1. **Download & install NVDA (free):**
   - https://www.nvaccess.org/download/
   - Or use JAWS di Windows (paid)
   - Or use VoiceOver di macOS (built-in)

2. **Enable screen reader & test:**
   - Start NVDA: Ctrl+Alt+N
   - Navigate homepage
   - Verify screen reader announces:
     - Page title
     - Headings (h1, h2, h3)
     - Links (announce as "link", read text)
     - Buttons (announce as "button", read text)
     - Form labels (announce label with input)
     - Form errors (announce error message)

3. **Test semantic HTML:**
   - Page structure: nav, main, aside, footer properly used?
   - Headings hierarchy: h1 → h2 → h3 (no skipping levels)?
   - Images have alt text?
   - Form labels associated dengan input (for/id match)?
   - ARIA labels used untuk icons (icon-only buttons)?

4. **Test dynamic content:**
   - When chart renders, screen reader notified?
   - When form errors appear, screen reader announces?
   - When modal opens, focus managed correctly?

**Expected Results:**
- ✅ Screen reader can navigate aplikasi
- ✅ All content announced correctly
- ✅ Semantic HTML properly used
- ✅ Form labels associated
- ✅ ARIA labels present
- ✅ No incomprehensible elements
- ✅ User dengan screen reader dapat use aplikasi

**Tools:** NVDA (free), JAWS (paid), VoiceOver (macOS/iOS)

**Pass Criteria:** Screen reader compatible, proper semantic HTML

---

#### **Test Case 10: Color Blind Friendly**

**Objective:** Verifikasi informasi tidak relay hanya pada color

**Test Steps:**
1. **Test color contrast:**
   - Verified di Test Case 5.4.5 (WCAG AA 4.5:1)
   - Repeat: use WebAIM Contrast Checker

2. **Use color blind simulator:**
   - https://www.color-blindness.com/coblis-color-blindness-simulator/
   - Upload screenshot dari aplikasi
   - Simulate different color blindness:
     - Protanopia (red-blind)
     - Deuteranopia (green-blind)
     - Tritanopia (blue-blind)
   - Verify: information still conveyed bukan hanya via color

3. **Example - Status badge:**
   - Sangat Sehat (Green badge)
   - Should also have text label: "Sangat Sehat" (not just green color)
   - Color blind person dapat read text, bukan depend pada color

4. **Example - Charts:**
   - Bar chart dengan warna berbeda per kategori
   - Tambahkan label text atau legend
   - Include pattern/texture (tidak hanya color)

**Expected Results:**
- ✅ Color tidak sole indicator of information
- ✅ Text labels provided untuk color-coded elements
- ✅ High contrast maintained
- ✅ Color blind user dapat understand aplikasi
- ✅ Patterns/textures used alongside colors (charts)

**Tools:** Color blind simulator, WebAIM Contrast Checker

**Pass Criteria:** Information conveyed without relying solely on color

---

#### **Test Case 11: Browser Zoom Support (100%-200%)**

**Objective:** Verifikasi layout tidak broken saat browser zoom

**Test Steps:**
1. **Test zoom in:**
   - Ctrl++ (Chrome, Firefox, Edge)
   - Cmd++ (Safari macOS)
   - Zoom to 150%
   - Verify: layout tidak broken, text readable, no overlapping
   - Zoom to 200%
   - Repeat verification

2. **Test zoom out:**
   - Ctrl+- (Chrome, Firefox, Edge)
   - Zoom to 50%
   - Verify: layout still good, not too compressed

3. **Test content:**
   - Forms still accessible?
   - Table still scrollable (not too wide)?
   - Charts readable?
   - Buttons clickable?

4. **Test CSS:**
   - Check untuk fixed widths: `width: 500px`
   - Should use flexible widths: `width: 100%`, `max-width: 500px`
   - Check untuk hardcoded breakpoints: avoid

**Expected Results:**
- ✅ Zoom 100%-200% supported
- ✅ Layout tidak broken
- ✅ Text readable
- ✅ No overlapping elements
- ✅ Forms/buttons functional
- ✅ Flexible layout (not hardcoded widths)

**Tools:** Browser zoom, CSS inspection

**Pass Criteria:** Supports browser zoom 100%-200% without layout breaks

---

### 5.7.3 Compatibility Test Summary

| Test Case | Requirement | Method | Status |
|-----------|------------|--------|--------|
| 1. Browser Compatibility | Chrome, Firefox, Safari, Edge | BrowserStack | To-Do |
| 2. Mobile Device | iPhone, Android, Tablet | Real devices | To-Do |
| 3. Server OS | Ubuntu 20.04/22.04, macOS, Windows | VM/Local setup | To-Do |
| 4. Database | SQLite, MySQL 8.0, MariaDB 10.x | Database testing | To-Do |
| 5. PHP Version | PHP 8.2, 8.3 | PHP CLI test | To-Do |
| 6. Responsive Design | 320px-1920px+ | DevTools | To-Do |
| 7. Progressive Enhancement | Works without JS | JS disable test | To-Do |
| 8. Keyboard Navigation | Tab, Enter, ESC | Manual keyboard test | To-Do |
| 9. Screen Reader | NVDA, JAWS, VoiceOver | Screen reader test | To-Do |
| 10. Color Blind Friendly | No color-only info | Simulator + review | To-Do |
| 11. Browser Zoom | 100%-200% support | Zoom test | To-Do |

---

## Metrik Pengujian & Acceptance Criteria

### Overall Test Execution Summary

| Aspect | Total Test Cases | Pass Target | Status | Notes |
|--------|-----------------|------------|--------|-------|
| **Security** | 10 | 100% pass | To-Do | CRITICAL |
| **Availability** | 7 | 100% pass | To-Do | CRITICAL |
| **Usability** | 11 | >= 90% pass | To-Do | HIGH |
| **Compatibility** | 11 | 100% pass | To-Do | HIGH |
| **TOTAL** | **39 Test Cases** | **>= 95% pass** | **To-Do** | **Target: All critical aspects 100%** |

### Acceptance Criteria

**Security (CRITICAL):**
- ✅ 10/10 test cases PASS
- ✅ 0 SQL injection vulnerabilities
- ✅ 0 XSS vulnerabilities
- ✅ 0 CSRF vulnerabilities
- ✅ All passwords bcrypt hashed
- ✅ HTTPS enforced production

**Availability (CRITICAL):**
- ✅ 7/7 test cases PASS
- ✅ 99% uptime over 30 days
- ✅ < 7 hours downtime per month
- ✅ Health check endpoint working
- ✅ Database backup verified
- ✅ Data integrity maintained

**Usability (HIGH):**
- ✅ >= 10/11 test cases PASS
- ✅ Error messages clear & helpful
- ✅ Loading indicators present
- ✅ WCAG AA accessibility compliant
- ✅ User testing feedback positive (>= 4/5 rating)
- ✅ Form navigation intuitive

**Compatibility (HIGH):**
- ✅ 11/11 test cases PASS
- ✅ Works Chrome, Firefox, Safari, Edge
- ✅ Mobile responsive (320px-1920px+)
- ✅ Keyboard navigable
- ✅ Screen reader compatible
- ✅ Zoom 100%-200% supported

### Launch Readiness Checklist

```
SECURITY:
[ ] OAuth email validation implemented & tested
[ ] Password hashing bcrypt implemented
[ ] CSRF tokens all forms
[ ] XSS prevention escaping
[ ] SQL injection prevention
[ ] Session fixation prevention
[ ] Admin timeout 30 minutes
[ ] Environment variables configured
[ ] Security headers set
[ ] HTTPS certificate valid

AVAILABILITY:
[ ] Uptime monitoring setup
[ ] Health check endpoint working
[ ] Database backup schedule configured
[ ] Cascade delete tested
[ ] Transaction rollback tested
[ ] Error handling implemented
[ ] Error logging configured
[ ] Disaster recovery plan documented

USABILITY:
[ ] Navigation consistent
[ ] Responsive design verified
[ ] Error messages clear
[ ] Loading indicators present
[ ] Contrast ratio WCAG AA
[ ] Font sizes appropriate
[ ] Button sizes 44x44px
[ ] Form labels clear
[ ] Breadcrumb/progress indicator
[ ] Confirmation dialogs implemented
[ ] User testing completed (5-10 users)

COMPATIBILITY:
[ ] Browser testing completed (Chrome, Firefox, Safari, Edge)
[ ] Mobile device testing (iPhone, Android, iPad)
[ ] Server OS compatibility (Ubuntu, macOS, Windows)
[ ] Database compatibility (SQLite, MySQL, MariaDB)
[ ] PHP version compatibility (8.2, 8.3)
[ ] Responsive design all breakpoints
[ ] Progressive enhancement working
[ ] Keyboard navigation complete
[ ] Screen reader compatible
[ ] Color blind friendly
[ ] Browser zoom supported

DOCUMENTATION:
[ ] README.md complete
[ ] API documentation
[ ] Deployment guide
[ ] Kebutuhan fungsional dokumen (ini)
[ ] Testing guide (ini)
[ ] Release notes

FINAL:
[ ] All critical tests PASS
[ ] >= 95% non-critical tests PASS
[ ] Code review approved
[ ] Security audit passed
[ ] Performance acceptable
[ ] Ready for production launch
```

---

## Kesimpulan

Dokumen panduan pengujian ini mencakup **39 comprehensive test cases** untuk mengvalidasi **4 aspek kebutuhan non-fungsional utama**: Security, Availability, Usability, dan Compatibility.

### Target Achievement:
- **Security & Availability:** 100% pass (CRITICAL)
- **Usability & Compatibility:** >= 95% pass (HIGH)
- **Overall:** >= 95% test case pass rate sebelum production launch

### Next Steps:
1. Execute semua test cases secara sistematis
2. Document hasil testing (pass/fail, evidence)
3. Fix issues yang ditemukan
4. Re-test untuk verify fixes
5. Approval dari stakeholder (product owner, security team)
6. Production deployment dengan confidence

---

**Dokumen Dibuat:** 26 November 2025
**Status:** Testing Guide Complete
**Version:** 1.0
**Author:** Assessment Online ITERA Team

---

**END OF DOCUMENT**
