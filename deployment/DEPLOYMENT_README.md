# 📦 Deployment Package - Assessment Online

Dokumentasi lengkap untuk deployment aplikasi Assessment Online ke VPS kampus.

---

## 📁 File-file Deployment

Repository ini sudah dilengkapi dengan file-file untuk mempermudah deployment:

### 📄 Dokumentasi

| File | Deskripsi | Kapan Digunakan |
|------|-----------|-----------------|
| **DEPLOYMENT_GUIDE.md** | Panduan lengkap step-by-step deployment | First-time deployment, referensi detail |
| **QUICK_START.md** | Panduan singkat (1-2 jam deploy) | Quick deployment, sudah familiar dengan Laravel |
| **DEPLOYMENT_CHECKLIST.md** | Checklist untuk ensure nothing missed | Sebelum & sesudah deployment |
| **DEPLOYMENT_README.md** | File ini - overview deployment package | Mulai dari sini! |

### 🔧 Scripts

| File | Deskripsi | Usage |
|------|-----------|-------|
| **setup-server.sh** | Install PHP, Nginx, Composer, Node.js otomatis | `sudo bash setup-server.sh` |
| **deploy.sh** | Deploy/update aplikasi otomatis | `bash deploy.sh` |

### ⚙️ Configuration Templates

| File | Deskripsi | Usage |
|------|-----------|-------|
| **nginx-config.template** | Template konfigurasi Nginx lengkap | Copy ke `/etc/nginx/sites-available/` |
| **.env.production** | Template environment production | Copy ke `.env` lalu edit |

---

## 🚀 Cara Mulai

### Opsi 1: Quick Start (Experienced Users)

Jika sudah familiar dengan Laravel deployment:

```bash
# 1. Baca quick start guide
cat QUICK_START.md

# 2. Ikuti 5 langkah di quick start
# Total waktu: 1-2 jam
```

### Opsi 2: Step-by-Step Guide (Recommended)

Jika pertama kali deploy Laravel atau mau detail:

```bash
# 1. Baca deployment guide lengkap
cat DEPLOYMENT_GUIDE.md

# 2. Print checklist
cat DEPLOYMENT_CHECKLIST.md

# 3. Ikuti step-by-step sambil centang checklist
# Total waktu: 2-3 jam (first time)
```

---

## 📋 Deployment Workflow

### First-Time Deployment

```
┌─────────────────────────────────────────────┐
│ 1. Persiapan                                │
│    - Informasi VPS (IP, user, password)     │
│    - Domain yang akan digunakan             │
│    - Akses sudo                             │
└─────────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────────┐
│ 2. Setup Server (setup-server.sh)           │
│    - Install PHP 8.2                        │
│    - Install Nginx                          │
│    - Install Composer, Node.js              │
│    ⏱️ Waktu: 10-15 menit                    │
└─────────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────────┐
│ 3. Clone & Install Dependencies             │
│    - git clone project                      │
│    - composer install                       │
│    - npm install && build                   │
│    ⏱️ Waktu: 10-15 menit                    │
└─────────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────────┐
│ 4. Configure Application                    │
│    - Copy & edit .env                       │
│    - Generate APP_KEY                       │
│    - Setup database                         │
│    - Run migrations                         │
│    ⏱️ Waktu: 10-15 menit                    │
└─────────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────────┐
│ 5. Configure Web Server                     │
│    - Setup Nginx config                     │
│    - Enable site                            │
│    - Reload Nginx                           │
│    ⏱️ Waktu: 10 menit                       │
└─────────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────────┐
│ 6. DNS Configuration                        │
│    - Request A record ke IT                 │
│    - Wait propagation                       │
│    ⏱️ Waktu: 1-24 jam (waiting)             │
└─────────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────────┐
│ 7. Testing                                  │
│    - Test homepage                          │
│    - Test fitur-fitur                       │
│    - Check logs                             │
│    ⏱️ Waktu: 20-30 menit                    │
└─────────────────────────────────────────────┘
              ↓
          ✅ DONE!
```

### Update Deployment (Subsequent)

```
┌─────────────────────────────────────────────┐
│ 1. Backup Database (Important!)             │
│    cp database/database.sqlite backup.sqlite│
└─────────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────────┐
│ 2. Run Deploy Script                        │
│    cd /var/www/AsessmentOnline              │
│    bash deploy.sh                           │
│    ⏱️ Waktu: 2-3 menit                      │
└─────────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────────┐
│ 3. Test                                     │
│    - Check aplikasi masih jalan             │
│    - Check logs tidak ada error             │
│    ⏱️ Waktu: 5 menit                        │
└─────────────────────────────────────────────┘
              ↓
          ✅ UPDATED!
```

---

## 🎯 Quick Reference

### Commands Cheat Sheet

```bash
# Server Setup (First Time Only)
sudo bash setup-server.sh

# Deploy/Update Application
bash deploy.sh

# Check Services
sudo systemctl status nginx
sudo systemctl status php8.2-fpm

# Restart Services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm

# View Logs
tail -f storage/logs/laravel.log                    # Laravel logs
sudo tail -f /var/log/nginx/assessment-error.log    # Nginx logs

# Laravel Artisan Commands
php artisan migrate --force          # Run migrations
php artisan config:cache             # Cache config
php artisan route:cache              # Cache routes
php artisan view:cache               # Cache views
php artisan config:clear             # Clear config cache
php artisan cache:clear              # Clear app cache

# Permission Fix
sudo chown -R www-data:www-data /var/www/AsessmentOnline
sudo chmod -R 775 storage bootstrap/cache
```

### File Locations

```
Application:
/var/www/AsessmentOnline/                    # Project root

Configuration:
/var/www/AsessmentOnline/.env                # Environment config
/etc/nginx/sites-available/assessment.*      # Nginx config
/etc/php/8.2/fpm/php.ini                     # PHP config

Logs:
/var/www/AsessmentOnline/storage/logs/       # Laravel logs
/var/log/nginx/assessment-*.log              # Nginx logs
/var/log/php8.2-fpm.log                      # PHP-FPM logs

Database:
/var/www/AsessmentOnline/database/database.sqlite  # SQLite DB (if used)
```

---

## 🔧 Customization

### Mengubah Domain

Jika domain berubah:

```bash
# 1. Edit .env
nano .env
# Ubah: APP_URL=http://new-domain.ac.id

# 2. Edit Nginx config
sudo nano /etc/nginx/sites-available/assessment.kampus.ac.id
# Ubah: server_name new-domain.ac.id;

# 3. Clear cache & reload
php artisan config:clear
php artisan config:cache
sudo systemctl reload nginx

# 4. Request DNS update ke IT kampus
```

### Pindah dari SQLite ke MySQL

```bash
# 1. Install MySQL
sudo apt install mysql-server -y
sudo mysql_secure_installation

# 2. Buat database
sudo mysql -u root -p
CREATE DATABASE assessment_db;
CREATE USER 'assessment_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON assessment_db.* TO 'assessment_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# 3. Edit .env
nano .env
# Ubah dari sqlite ke mysql:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=assessment_db
# DB_USERNAME=assessment_user
# DB_PASSWORD=your_password

# 4. Migrate data (atau fresh migrate)
php artisan config:clear
php artisan migrate --force  # atau migrate:fresh jika mau reset

# 5. Clear cache
php artisan config:cache
```

### Enable HTTPS/SSL

Setelah install SSL certificate (Let's Encrypt):

```bash
# 1. Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# 2. Generate SSL
sudo certbot --nginx -d assessment.kampus.ac.id

# 3. Auto-renewal
sudo certbot renew --dry-run

# 4. Edit .env
nano .env
# Ubah: APP_URL=https://assessment.kampus.ac.id

# 5. Clear cache
php artisan config:clear
php artisan config:cache
```

---

## 🆘 Troubleshooting

### Quick Diagnostic

```bash
# Run diagnostic script
cd /var/www/AsessmentOnline

# Check all services
echo "=== Nginx ===" && sudo systemctl status nginx --no-pager
echo "=== PHP-FPM ===" && sudo systemctl status php8.2-fpm --no-pager
echo "=== Disk Space ===" && df -h /
echo "=== Last 10 Laravel Errors ===" && tail -10 storage/logs/laravel.log | grep ERROR
```

### Common Issues & Solutions

| Problem | Solution |
|---------|----------|
| 500 Error | Check `storage/logs/laravel.log`, fix permissions |
| 404 on routes | Check Nginx config has `try_files`, reload nginx |
| CSS/JS not loading | Run `npm run build`, clear cache |
| Database error | Check `.env` DB settings, permissions |
| Slow performance | Check logs, optimize cache, check disk space |

Lihat [DEPLOYMENT_GUIDE.md - Section 8](DEPLOYMENT_GUIDE.md#8-troubleshooting) untuk detail troubleshooting.

---

## 📊 Monitoring & Maintenance

### Daily

```bash
# Check disk space
df -h

# Check for errors in logs (optional)
tail -50 storage/logs/laravel.log | grep ERROR
```

### Weekly

```bash
# Backup database
cp database/database.sqlite database/backup-$(date +%Y%m%d).sqlite
# Atau untuk MySQL:
mysqldump -u assessment_user -p assessment_db > backup-$(date +%Y%m%d).sql

# Check log size
du -sh storage/logs/
```

### Monthly

```bash
# Update system packages
sudo apt update
sudo apt upgrade -y

# Check for Laravel/Composer updates
composer outdated

# Restart services (maintenance window)
sudo systemctl restart nginx php8.2-fpm
```

---

## 📚 Additional Resources

### Documentation

- **Laravel 11 Docs:** https://laravel.com/docs/11.x
- **Nginx Docs:** https://nginx.org/en/docs/
- **PHP Docs:** https://www.php.net/docs.php

### Tools

- **DNS Checker:** https://dnschecker.org
- **SSL Test:** https://www.ssllabs.com/ssltest/
- **PageSpeed:** https://pagespeed.web.dev/

### Support

- **Laravel Community:** https://laravel.io/
- **Stack Overflow:** https://stackoverflow.com/questions/tagged/laravel
- **GitHub Issues:** Create issue di repository ini

---

## ✅ Success Criteria

Deployment dianggap berhasil jika:

- [x] Website accessible via domain (http://assessment.kampus.ac.id)
- [x] Homepage loads tanpa error
- [x] User bisa register & login
- [x] Kuesioner bisa diisi & submit
- [x] Hasil kuesioner muncul
- [x] Admin panel accessible
- [x] Export Excel works
- [x] No errors di logs
- [x] Response time < 3 detik
- [x] Mobile responsive

---

## 🎉 Selamat!

Jika Anda sampai di sini dan semua berjalan lancar, aplikasi Assessment Online sudah berhasil di-deploy!

**Next Steps:**
1. ✅ Share URL ke user/stakeholder
2. ✅ Setup monitoring/uptime checker
3. ✅ Setup regular backup
4. ✅ Dokumentasikan credentials dengan aman
5. ✅ Training admin panel (jika perlu)

**Good luck! 🚀**

---

**Last Updated:** 2025-11-03
**Version:** 1.0
**Maintainer:** [Your Name/Team]
