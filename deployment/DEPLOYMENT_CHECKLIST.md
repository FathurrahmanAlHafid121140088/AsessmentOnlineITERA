# ✅ Deployment Checklist - Assessment Online

Checklist lengkap untuk memastikan deployment berjalan lancar.

---

## 📋 Pre-Deployment Checklist

### Informasi VPS

```
[ ] IP Address VPS: ___________________________
[ ] SSH Username: ___________________________
[ ] SSH Password/SSH Key: ___________________________
[ ] Root/Sudo Access: Ya / Tidak
[ ] OS Version: Ubuntu ___________
```

### Domain & DNS

```
[ ] Domain sudah didaftarkan: ___________________________
[ ] Akses ke DNS Management: Ya / Tidak
[ ] Kontak IT untuk DNS: ___________________________
```

### Database

```
Database yang akan digunakan:
[ ] SQLite (Recommended untuk start)
[ ] MySQL/MariaDB
[ ] PostgreSQL

Jika MySQL:
[ ] Database Name: ___________________________
[ ] Database User: ___________________________
[ ] Database Password: ___________________________
```

### Code Repository

```
[ ] Code sudah di Git repository
[ ] Repository URL: ___________________________
[ ] Branch untuk production: main / master / production
[ ] Deploy key/access token (jika private): ___________________________
```

---

## 🚀 Server Setup Checklist

### Step 1: Akses VPS

```
[ ] Berhasil login via SSH
    Command: ssh username@ip-address

[ ] Test sudo access
    Command: sudo whoami
    Expected: root

[ ] Check OS version
    Command: lsb_release -a
    Expected: Ubuntu 20.04 atau 22.04
```

### Step 2: Install Server Stack

#### Opsi A: Otomatis (Recommended)

```
[ ] Download setup-server.sh
[ ] Run setup script
    Command: sudo bash setup-server.sh
[ ] Verify PHP installed
    Command: php -v
    Expected: PHP 8.2.x
[ ] Verify Nginx installed
    Command: nginx -v
    Expected: nginx/1.x
[ ] Verify Composer installed
    Command: composer --version
    Expected: Composer version 2.x
[ ] Verify Node.js installed
    Command: node -v
    Expected: v20.x
```

#### Opsi B: Manual

```
[ ] Update system
    sudo apt update && sudo apt upgrade -y

[ ] Install PHP 8.2
    sudo add-apt-repository ppa:ondrej/php -y
    sudo apt install php8.2 php8.2-fpm php8.2-cli ... (see guide)

[ ] Install Nginx
    sudo apt install nginx -y

[ ] Install Composer
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer

[ ] Install Node.js 20
    curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
    sudo apt install -y nodejs

[ ] (Optional) Install MySQL
    sudo apt install mysql-server -y
    sudo mysql_secure_installation
```

### Step 3: Verify Services Running

```
[ ] Nginx running
    Command: sudo systemctl status nginx
    Expected: Active: active (running)

[ ] PHP-FPM running
    Command: sudo systemctl status php8.2-fpm
    Expected: Active: active (running)

[ ] MySQL running (if installed)
    Command: sudo systemctl status mysql
    Expected: Active: active (running)
```

---

## 📦 Application Deployment Checklist

### Step 1: Clone/Upload Project

```
[ ] Create web directory
    Command: sudo mkdir -p /var/www

[ ] Clone project
    Command: cd /var/www && sudo git clone <repo-url> AsessmentOnline

[ ] Set ownership
    Command: sudo chown -R $USER:$USER /var/www/AsessmentOnline

[ ] Check project structure
    Command: ls -la /var/www/AsessmentOnline
    Expected: app, config, database, public, resources, routes, ...
```

### Step 2: Install Dependencies

```
[ ] Install Composer dependencies
    cd /var/www/AsessmentOnline
    composer install --optimize-autoloader --no-dev

[ ] Install NPM dependencies
    npm install

[ ] Build frontend assets
    npm run build

[ ] Check build directory exists
    ls -la public/build/
    Expected: assets/, manifest.json
```

### Step 3: Environment Configuration

```
[ ] Copy .env file
    cp .env.example .env

[ ] Edit .env dengan informasi yang benar:
    [ ] APP_NAME = "Assessment Online"
    [ ] APP_ENV = production
    [ ] APP_DEBUG = false
    [ ] APP_URL = http://your-domain.ac.id
    [ ] DB_CONNECTION = sqlite atau mysql
    [ ] DB_DATABASE = (path atau nama database)
    [ ] SESSION_DRIVER = database
    [ ] CACHE_DRIVER = database

[ ] Generate application key
    php artisan key:generate

[ ] Verify .env tidak kosong
    cat .env | grep APP_KEY
    Expected: APP_KEY=base64:...
```

### Step 4: Database Setup

#### Jika SQLite:

```
[ ] Create database file
    touch database/database.sqlite

[ ] Set permissions
    chmod 664 database/database.sqlite
```

#### Jika MySQL:

```
[ ] Login MySQL
    sudo mysql -u root -p

[ ] Create database & user
    CREATE DATABASE assessment_db;
    CREATE USER 'assessment_user'@'localhost' IDENTIFIED BY 'password';
    GRANT ALL PRIVILEGES ON assessment_db.* TO 'assessment_user'@'localhost';
    FLUSH PRIVILEGES;
    EXIT;

[ ] Update .env dengan credentials
```

#### Migration:

```
[ ] Run migrations
    php artisan migrate --force

[ ] (Optional) Run seeders
    php artisan db:seed --force

[ ] Verify tables created
    php artisan db:show
```

### Step 5: Optimize Laravel

```
[ ] Cache configuration
    php artisan config:cache

[ ] Cache routes
    php artisan route:cache

[ ] Cache views
    php artisan view:cache

[ ] Verify cache files
    ls -la bootstrap/cache/
```

### Step 6: Set Permissions

```
[ ] Set ownership to www-data
    sudo chown -R www-data:www-data /var/www/AsessmentOnline

[ ] Set directory permissions (755)
    sudo find /var/www/AsessmentOnline -type d -exec chmod 755 {} \;

[ ] Set file permissions (644)
    sudo find /var/www/AsessmentOnline -type f -exec chmod 644 {} \;

[ ] Storage writable (775)
    sudo chmod -R 775 /var/www/AsessmentOnline/storage

[ ] Bootstrap/cache writable (775)
    sudo chmod -R 775 /var/www/AsessmentOnline/bootstrap/cache

[ ] Database writable (if SQLite)
    sudo chmod 664 /var/www/AsessmentOnline/database/database.sqlite
    sudo chown www-data:www-data database/database.sqlite
```

---

## 🌐 Web Server Configuration Checklist

### Step 1: Nginx Configuration

```
[ ] Create Nginx config file
    sudo nano /etc/nginx/sites-available/assessment.kampus.ac.id

[ ] Paste configuration (from nginx-config.template)

[ ] Edit konfigurasi:
    [ ] server_name = domain Anda
    [ ] root = /var/www/AsessmentOnline/public
    [ ] fastcgi_pass = unix:/var/run/php/php8.2-fpm.sock

[ ] Save file (Ctrl+O, Enter, Ctrl+X)
```

### Step 2: Enable Site

```
[ ] Create symbolic link
    sudo ln -s /etc/nginx/sites-available/assessment.kampus.ac.id \
               /etc/nginx/sites-enabled/

[ ] (Optional) Disable default site
    sudo rm /etc/nginx/sites-enabled/default

[ ] Test Nginx configuration
    sudo nginx -t
    Expected: syntax is ok, test is successful

[ ] Reload Nginx
    sudo systemctl reload nginx
```

---

## 🌍 DNS Configuration Checklist

### Step 1: Request DNS Record

```
[ ] Siapkan informasi:
    - Hostname: assessment.kampus.ac.id
    - Type: A Record
    - Value: <IP VPS>
    - TTL: 3600

[ ] Kirim request ke IT kampus

[ ] Catat tanggal request: _______________

[ ] Follow up jika > 3 hari belum diproses
```

### Step 2: Verify DNS

```
[ ] Check DNS propagation
    Command: nslookup assessment.kampus.ac.id
    Expected: Address = <IP VPS Anda>

[ ] Check dari online tool
    https://dnschecker.org
    Domain: assessment.kampus.ac.id

[ ] Verify dari berbeda network/ISP
```

---

## 🧪 Testing Checklist

### Basic Tests

```
[ ] Test dari VPS sendiri
    curl -I http://localhost
    Expected: HTTP/1.1 200 OK

[ ] Test dari browser
    http://assessment.kampus.ac.id
    Expected: Homepage muncul

[ ] Check no Nginx errors
    sudo tail -20 /var/log/nginx/assessment-error.log

[ ] Check no Laravel errors
    tail -20 /var/www/AsessmentOnline/storage/logs/laravel.log
```

### Functional Tests

```
Authentication:
[ ] Register user baru berhasil
[ ] Login dengan user tadi berhasil
[ ] Logout berhasil
[ ] (Optional) Google OAuth berhasil

Mental Health Assessment:
[ ] Halaman landing /mental-health muncul
[ ] Form data diri bisa diisi & submit
[ ] Kuesioner bisa diakses
[ ] Semua pertanyaan bisa dijawab
[ ] Submit jawaban berhasil
[ ] Redirect ke halaman hasil
[ ] Hasil ditampilkan dengan benar

User Dashboard:
[ ] Bisa akses dashboard
[ ] History kuesioner muncul
[ ] Bisa lihat detail hasil sebelumnya

Admin Panel:
[ ] Login admin berhasil
[ ] Dashboard admin muncul
[ ] Bisa lihat semua data kuesioner
[ ] Filter/search berfungsi
[ ] Export Excel berhasil download
[ ] File Excel bisa dibuka

Career Assessment (jika ada):
[ ] Form karir bisa diisi
[ ] Hasil karir muncul
[ ] Admin bisa lihat data karir
```

### Performance Tests

```
[ ] Homepage load < 3 detik
[ ] Kuesioner submit < 2 detik
[ ] Excel export < 5 detik
[ ] No memory errors di logs
```

### Mobile Tests

```
[ ] Buka dari mobile browser
[ ] Responsive layout works
[ ] Form bisa diisi di mobile
[ ] Submit works di mobile
```

---

## 🔒 Security Checklist

### File Permissions

```
[ ] .env tidak bisa diakses dari browser
    Test: http://domain.ac.id/.env
    Expected: 404 Not Found

[ ] storage/ tidak bisa diakses
    Test: http://domain.ac.id/storage/
    Expected: 404 Not Found

[ ] vendor/ tidak bisa diakses
    Test: http://domain.ac.id/vendor/
    Expected: 404 Not Found
```

### Configuration

```
[ ] APP_DEBUG = false di production
[ ] APP_ENV = production
[ ] Database credentials aman (strong password)
[ ] Google OAuth secrets tidak di-commit ke Git
```

### Server Security

```
[ ] Firewall enabled (UFW)
    sudo ufw status
    Expected: Status: active

[ ] Only necessary ports open
    Expected: 22 (SSH), 80 (HTTP), 443 (HTTPS jika ada)

[ ] SSH password authentication disabled (recommended)
[ ] Fail2ban installed (optional tapi recommended)
```

---

## 📊 Post-Deployment Checklist

### Documentation

```
[ ] Dokumentasi credentials disimpan aman:
    - SSH credentials
    - Database credentials
    - Admin login credentials
    - Google OAuth credentials

[ ] Dokumentasi deployment disimpan
[ ] Contact person IT kampus dicatat
```

### Monitoring Setup

```
[ ] Setup log rotation (auto via logrotate)
    Check: /etc/logrotate.d/nginx

[ ] Setup database backup
    Manual: cp database/database.sqlite database/backup.sqlite
    Cron: 0 2 * * * cp /var/www/.../database.sqlite ...

[ ] Setup uptime monitoring (optional)
    Tools: UptimeRobot, Pingdom, StatusCake (free tier)
```

### Handover

```
[ ] Demo aplikasi ke stakeholder
[ ] Training admin panel (jika perlu)
[ ] Serahkan credentials & dokumentasi
[ ] Setup support channel (email/WhatsApp group)
```

---

## 🔄 Update Deployment Checklist

Untuk update aplikasi setelah ada perubahan:

```
[ ] Backup database dulu!
    cp database/database.sqlite database/backup-$(date +%Y%m%d).sqlite

[ ] Pull latest code
    cd /var/www/AsessmentOnline
    git pull origin main

[ ] Run update script
    bash deploy.sh

    Atau manual:
    [ ] composer install --no-dev
    [ ] npm install && npm run build
    [ ] php artisan migrate --force
    [ ] php artisan config:cache
    [ ] php artisan route:cache
    [ ] php artisan view:cache
    [ ] sudo systemctl reload nginx php8.2-fpm

[ ] Test aplikasi masih jalan
[ ] Check logs tidak ada error
```

---

## 📞 Support Contacts

```
IT Kampus:
- Nama: _______________________
- Email: _______________________
- Phone: _______________________

Developer:
- Nama: _______________________
- Email: _______________________
- Phone: _______________________

Backup Contact:
- Nama: _______________________
- Email: _______________________
```

---

## ✅ Final Sign-off

```
Deployment completed by: _______________________
Date: _______________________
Time: _______________________

Verified by: _______________________
Date: _______________________

Notes:
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
```

---

**Selamat! Aplikasi sudah deployed! 🎉**
