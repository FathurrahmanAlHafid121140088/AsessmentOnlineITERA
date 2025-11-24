# 🚀 Panduan Deployment Laravel ke VPS Kampus

Panduan lengkap step-by-step untuk deploy Assessment Online ke VPS kampus dengan domain .ac.id

---

## 📋 Daftar Isi

1. [Persiapan](#1-persiapan)
2. [Setup Server (First Time)](#2-setup-server-first-time)
3. [Deploy Aplikasi](#3-deploy-aplikasi)
4. [Konfigurasi Web Server](#4-konfigurasi-web-server)
5. [DNS Configuration](#5-dns-configuration)
6. [Testing](#6-testing)
7. [Update Deployment](#7-update-deployment)
8. [Troubleshooting](#8-troubleshooting)

---

## 1. Persiapan

### 1.1 Informasi yang Dibutuhkan

Pastikan Anda sudah memiliki informasi berikut:

```
[ ] IP Address VPS: ___________________
[ ] SSH Username: ___________________
[ ] SSH Password/Key: ___________________
[ ] Domain/Subdomain: ___________________
[ ] Database Type: SQLite / MySQL
```

### 1.2 Software yang Dibutuhkan (Local)

- **SSH Client:**
  - Windows: PuTTY, Windows Terminal, atau Git Bash
  - Mac/Linux: Terminal bawaan
- **SFTP Client (Optional):** FileZilla, WinSCP
- **Git** (untuk clone project)

### 1.3 Akses VPS

Test koneksi ke VPS:

```bash
# Ganti dengan IP/domain VPS Anda
ssh username@103.xxx.xxx.xxx

# Atau jika pakai domain
ssh username@vps.kampus.ac.id
```

Jika berhasil login, Anda siap melanjutkan!

---

## 2. Setup Server (First Time)

### Opsi A: Pakai Script Otomatis (Recommended)

```bash
# 1. Download script setup
wget https://raw.githubusercontent.com/yourusername/AsessmentOnline/main/setup-server.sh

# Atau jika wget tidak ada, pakai curl
curl -O https://raw.githubusercontent.com/yourusername/AsessmentOnline/main/setup-server.sh

# 2. Berikan permission execute
chmod +x setup-server.sh

# 3. Jalankan script (butuh sudo)
sudo bash setup-server.sh

# Script akan install:
# - PHP 8.2 + extensions
# - Nginx
# - Composer
# - Node.js 20
# - MySQL (optional)

# Waktu: ~10-15 menit
```

### Opsi B: Manual Step-by-Step

<details>
<summary>Klik untuk lihat langkah manual</summary>

#### 2.1 Update System

```bash
sudo apt update && sudo apt upgrade -y
```

#### 2.2 Install PHP 8.2

```bash
# Add repository PHP
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP dan extensions
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common \
    php8.2-mysql php8.2-xml php8.2-curl php8.2-mbstring \
    php8.2-zip php8.2-gd php8.2-bcmath php8.2-sqlite3

# Verify
php -v
```

#### 2.3 Install Nginx

```bash
sudo apt install nginx -y
sudo systemctl start nginx
sudo systemctl enable nginx

# Verify
sudo systemctl status nginx
```

#### 2.4 Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Verify
composer --version
```

#### 2.5 Install Node.js & npm

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Verify
node -v
npm -v
```

#### 2.6 Install MySQL (Optional, jika pakai MySQL)

```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Buat database
sudo mysql -u root -p
```

```sql
CREATE DATABASE assessment_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'assessment_user'@'localhost' IDENTIFIED BY 'YourSecurePassword123!';
GRANT ALL PRIVILEGES ON assessment_db.* TO 'assessment_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

</details>

---

## 3. Deploy Aplikasi

### 3.1 Clone/Upload Project

#### Opsi A: Via Git (Recommended)

```bash
# 1. Install Git (jika belum ada)
sudo apt install git -y

# 2. Pindah ke directory web
cd /var/www/

# 3. Clone project
sudo git clone https://github.com/yourusername/AsessmentOnline.git

# Atau jika private repository (butuh credentials)
sudo git clone https://username:token@github.com/yourusername/AsessmentOnline.git

# 4. Set ownership
sudo chown -R $USER:$USER /var/www/AsessmentOnline
```

#### Opsi B: Upload via SFTP

```
1. Buka FileZilla/WinSCP
2. Connect ke VPS:
   - Host: 103.xxx.xxx.xxx
   - Username: your_username
   - Password: your_password
   - Port: 22

3. Upload folder project ke: /var/www/AsessmentOnline
   (exclude: node_modules, vendor, .git)

4. Di VPS, set ownership:
   sudo chown -R $USER:$USER /var/www/AsessmentOnline
```

### 3.2 Install Dependencies

```bash
cd /var/www/AsessmentOnline

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies
npm install

# Build frontend assets
npm run build
```

### 3.3 Setup Environment

```bash
# Copy .env example
cp .env.example .env

# Edit .env
nano .env
```

**Edit isi .env:**

```env
APP_NAME="Assessment Online"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://assessment.kampus.ac.id

LOG_CHANNEL=stack
LOG_LEVEL=error

# Database - Pilih salah satu:

# Option 1: SQLite (Simple, recommended untuk start)
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/AsessmentOnline/database/database.sqlite

# Option 2: MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=assessment_db
# DB_USERNAME=assessment_user
# DB_PASSWORD=YourSecurePassword123!

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database

# Google OAuth (optional, setup nanti)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

**Simpan file:** `Ctrl+O`, Enter, `Ctrl+X`

### 3.4 Setup Database & Generate Key

```bash
# Jika pakai SQLite, buat file database
touch database/database.sqlite

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# (Optional) Run seeders jika ada
php artisan db:seed --force

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3.5 Set Permissions

```bash
# Set ownership ke web server user
sudo chown -R www-data:www-data /var/www/AsessmentOnline

# Set directory permissions
sudo find /var/www/AsessmentOnline -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/AsessmentOnline -type f -exec chmod 644 {} \;

# Storage & cache harus writable
sudo chmod -R 775 /var/www/AsessmentOnline/storage
sudo chmod -R 775 /var/www/AsessmentOnline/bootstrap/cache

# Database SQLite harus writable
sudo chmod 664 /var/www/AsessmentOnline/database/database.sqlite
```

---

## 4. Konfigurasi Web Server

### 4.1 Nginx Configuration

```bash
# Create config file
sudo nano /etc/nginx/sites-available/assessment.kampus.ac.id
```

**Paste konfigurasi berikut:**

```nginx
server {
    listen 80;
    server_name assessment.kampus.ac.id www.assessment.kampus.ac.id;

    root /var/www/AsessmentOnline/public;
    index index.php index.html;

    charset utf-8;

    # Logging
    access_log /var/log/nginx/assessment-access.log;
    error_log /var/log/nginx/assessment-error.log;

    # Laravel routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;

        # Timeout untuk kuesioner panjang
        fastcgi_read_timeout 300;
    }

    # Deny access to hidden files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Static assets caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Deny access to sensitive files
    location ~ /\.(env|git) {
        deny all;
        return 404;
    }

    # Client max body size (untuk upload)
    client_max_body_size 20M;
}
```

**Simpan:** `Ctrl+O`, Enter, `Ctrl+X`

### 4.2 Enable Site & Reload Nginx

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/assessment.kampus.ac.id \
           /etc/nginx/sites-enabled/

# (Optional) Disable default site
sudo rm /etc/nginx/sites-enabled/default

# Test configuration
sudo nginx -t

# Jika OK, reload
sudo systemctl reload nginx
```

---

## 5. DNS Configuration

### 5.1 Request ke IT Kampus

Kirim email/tiket ke IT kampus dengan informasi:

```
Subject: Request DNS Record - Assessment Online

Halo Tim IT,

Saya membutuhkan DNS A Record untuk project Assessment Online.

Detail:
- Hostname: assessment.kampus.ac.id
- Type: A Record
- Value: 103.xxx.xxx.xxx (IP VPS)
- TTL: 3600

Terima kasih!
```

### 5.2 Verify DNS Propagation

Setelah IT setup DNS, cek dengan:

```bash
# Di terminal/CMD
nslookup assessment.kampus.ac.id

# Expected output:
# Name: assessment.kampus.ac.id
# Address: 103.xxx.xxx.xxx
```

**Atau cek online:**
- https://dnschecker.org
- Masukkan: `assessment.kampus.ac.id`
- Check apakah IP sudah benar

**Waktu propagasi:** 1-24 jam (biasanya < 2 jam)

---

## 6. Testing

### 6.1 Basic Connectivity

```bash
# Test dari VPS
curl -I http://localhost

# Expected: HTTP/1.1 200 OK

# Test dari browser local (ganti dengan domain Anda)
# http://assessment.kampus.ac.id
```

### 6.2 Functional Testing

Buka browser dan test:

```
[ ] Homepage loads (http://assessment.kampus.ac.id)
[ ] Register user baru
[ ] Login dengan user yang baru dibuat
[ ] Logout
[ ] Mental Health landing page
[ ] Isi Data Diri form
[ ] Kuesioner Mental Health
    [ ] Jawab semua pertanyaan
    [ ] Submit berhasil
    [ ] Redirect ke hasil
[ ] Lihat Hasil Kuesioner
[ ] User Dashboard
[ ] Karir Assessment (jika ada)
[ ] Admin Login
    [ ] Username: admin (atau sesuai seeder)
    [ ] Lihat data kuesioner
    [ ] Export Excel
[ ] Google OAuth (jika sudah setup)
```

### 6.3 Performance Check

```bash
# Check response time
curl -o /dev/null -s -w "Time: %{time_total}s\n" http://assessment.kampus.ac.id

# Expected: < 2 seconds

# Check logs
sudo tail -f /var/log/nginx/assessment-error.log
tail -f /var/www/AsessmentOnline/storage/logs/laravel.log
```

---

## 7. Update Deployment

Untuk update aplikasi setelah ada perubahan code:

### 7.1 Pakai Script Otomatis

```bash
cd /var/www/AsessmentOnline
bash deploy.sh
```

### 7.2 Manual Update

```bash
cd /var/www/AsessmentOnline

# Pull latest code
git pull origin main

# Install new dependencies (jika ada)
composer install --optimize-autoloader --no-dev
npm install

# Build assets
npm run build

# Run new migrations (jika ada)
php artisan migrate --force

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Re-cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions (jika ada file baru)
sudo chown -R www-data:www-data /var/www/AsessmentOnline
sudo chmod -R 775 storage bootstrap/cache

# Reload PHP-FPM
sudo systemctl reload php8.2-fpm
```

---

## 8. Troubleshooting

### Issue 1: 500 Internal Server Error

```bash
# Check Laravel logs
tail -50 /var/www/AsessmentOnline/storage/logs/laravel.log

# Check Nginx error log
sudo tail -50 /var/log/nginx/assessment-error.log

# Common fixes:
# 1. Permission issue
sudo chown -R www-data:www-data /var/www/AsessmentOnline
sudo chmod -R 775 storage bootstrap/cache

# 2. Config cache issue
php artisan config:clear
php artisan cache:clear

# 3. Missing .env or APP_KEY
php artisan key:generate
```

### Issue 2: 404 Not Found (Routes)

```bash
# Check Nginx config
sudo nginx -t

# Pastikan ada try_files directive:
# try_files $uri $uri/ /index.php?$query_string;

# Reload Nginx
sudo systemctl reload nginx

# Clear route cache
php artisan route:clear
php artisan route:cache
```

### Issue 3: CSS/JS Not Loading

```bash
# Rebuild assets
cd /var/www/AsessmentOnline
npm run build

# Check build directory exists
ls -la public/build/

# Check .env APP_URL
nano .env
# Pastikan: APP_URL=http://assessment.kampus.ac.id

# Clear config
php artisan config:clear
php artisan config:cache
```

### Issue 4: Database Connection Error

```bash
# For SQLite:
# Check database file exists and writable
ls -la database/database.sqlite
sudo chmod 664 database/database.sqlite
sudo chown www-data:www-data database/database.sqlite

# For MySQL:
# Test connection
mysql -u assessment_user -p assessment_db

# Check .env credentials match
nano .env
```

### Issue 5: Permission Denied

```bash
# Fix all permissions
cd /var/www/AsessmentOnline

# Set ownership
sudo chown -R www-data:www-data .

# Set directory permissions
sudo find . -type d -exec chmod 755 {} \;

# Set file permissions
sudo find . -type f -exec chmod 644 {} \;

# Storage & cache writable
sudo chmod -R 775 storage bootstrap/cache

# Database writable (if SQLite)
sudo chmod 664 database/database.sqlite
```

### Issue 6: Google OAuth Error

```bash
# 1. Check Google Cloud Console:
#    - Authorized redirect URIs harus exact match
#    - http://assessment.kampus.ac.id/auth/google/callback

# 2. Check .env
nano .env
# GOOGLE_CLIENT_ID harus ada
# GOOGLE_CLIENT_SECRET harus ada
# GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# 3. Clear config cache
php artisan config:clear
php artisan config:cache
```

---

## 9. Maintenance

### 9.1 Backup Database

#### SQLite Backup

```bash
# Manual backup
cp /var/www/AsessmentOnline/database/database.sqlite \
   /var/www/AsessmentOnline/database/backup-$(date +%Y%m%d).sqlite

# Automated daily backup (via cron)
crontab -e

# Add line:
0 2 * * * cp /var/www/AsessmentOnline/database/database.sqlite /var/www/AsessmentOnline/database/backup-$(date +\%Y\%m\%d).sqlite
```

#### MySQL Backup

```bash
# Manual backup
mysqldump -u assessment_user -p assessment_db > backup-$(date +%Y%m%d).sql

# Automated daily backup
crontab -e

# Add line:
0 2 * * * mysqldump -u assessment_user -pYourPassword assessment_db > /var/backups/assessment-$(date +\%Y\%m\%d).sql
```

### 9.2 Monitor Logs

```bash
# Check disk usage
df -h

# Check log size
du -sh /var/www/AsessmentOnline/storage/logs/
du -sh /var/log/nginx/

# Rotate logs (automatic via logrotate)
sudo nano /etc/logrotate.d/nginx
```

### 9.3 Security Updates

```bash
# Update system packages (monthly)
sudo apt update
sudo apt upgrade -y

# Update Composer packages (check for security updates)
cd /var/www/AsessmentOnline
composer update --with-all-dependencies

# Update npm packages
npm audit fix
```

---

## 10. Useful Commands

```bash
# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm

# Check service status
sudo systemctl status nginx
sudo systemctl status php8.2-fpm

# View logs real-time
tail -f /var/log/nginx/assessment-error.log
tail -f /var/www/AsessmentOnline/storage/logs/laravel.log

# Clear all Laravel caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check Laravel info
php artisan --version
php artisan about

# List all routes
php artisan route:list

# Database fresh start (WARNING: deletes all data!)
php artisan migrate:fresh --seed --force
```

---

## 📞 Support

Jika menemui masalah:

1. Check section [Troubleshooting](#8-troubleshooting)
2. Check Laravel logs: `storage/logs/laravel.log`
3. Check Nginx logs: `/var/log/nginx/assessment-error.log`
4. Google error message yang spesifik
5. Konsultasi dengan IT kampus

---

## 🎉 Selesai!

Aplikasi Assessment Online sekarang sudah live di:
**http://assessment.kampus.ac.id**

Untuk pertanyaan atau issue, silakan buat issue di repository GitHub.
