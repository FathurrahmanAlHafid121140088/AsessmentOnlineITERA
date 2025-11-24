# ⚡ Quick Start - Deploy ke VPS Kampus

Panduan singkat untuk deploy Assessment Online ke VPS kampus dalam **1-2 jam**.

---

## 🎯 Prerequisites

```
✅ VPS dengan Ubuntu 20.04/22.04
✅ SSH access (username + password/key)
✅ Domain/subdomain sudah siap (contoh: assessment.kampus.ac.id)
✅ Sudo/root access
```

---

## 🚀 Deployment dalam 5 Langkah

### **Step 1: Setup Server** (10-15 menit)

```bash
# Login ke VPS
ssh username@your-vps-ip

# Download & jalankan setup script
curl -O https://raw.githubusercontent.com/yourusername/AsessmentOnline/main/setup-server.sh
sudo bash setup-server.sh

# Tunggu hingga selesai (~10 menit)
# Script akan install: PHP 8.2, Nginx, Composer, Node.js
```

**✅ Cek instalasi berhasil:**
```bash
php -v          # Expected: PHP 8.2.x
nginx -v        # Expected: nginx/1.x
composer -V     # Expected: Composer version 2.x
node -v         # Expected: v20.x
```

---

### **Step 2: Clone Project** (5 menit)

```bash
# Pindah ke web directory
cd /var/www/

# Clone project (ganti URL dengan repo Anda)
sudo git clone https://github.com/yourusername/AsessmentOnline.git

# Set ownership
sudo chown -R $USER:$USER AsessmentOnline
cd AsessmentOnline

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

---

### **Step 3: Configure Environment** (10 menit)

```bash
# Copy .env template
cp .env.production .env

# Edit .env (ganti domain & settings)
nano .env
```

**Edit minimal ini:**
```env
APP_URL=http://assessment.kampus.ac.id    # Ganti dengan domain Anda
DB_CONNECTION=sqlite                       # Pakai SQLite untuk simple
DB_DATABASE=/var/www/AsessmentOnline/database/database.sqlite
```

**Save:** `Ctrl+O`, Enter, `Ctrl+X`

```bash
# Generate key & setup database
php artisan key:generate
touch database/database.sqlite
php artisan migrate --force

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
sudo chown -R www-data:www-data .
sudo chmod -R 775 storage bootstrap/cache
sudo chmod 664 database/database.sqlite
```

---

### **Step 4: Configure Nginx** (10 menit)

```bash
# Create Nginx config
sudo nano /etc/nginx/sites-available/assessment.kampus.ac.id
```

**Paste config minimal ini:**

```nginx
server {
    listen 80;
    server_name assessment.kampus.ac.id;    # Ganti dengan domain Anda
    root /var/www/AsessmentOnline/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(env|git) {
        deny all;
        return 404;
    }
}
```

**Save:** `Ctrl+O`, Enter, `Ctrl+X`

```bash
# Enable site & reload
sudo ln -s /etc/nginx/sites-available/assessment.kampus.ac.id \
           /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

### **Step 5: Setup DNS** (Koordinasi dengan IT)

**Kirim email ke IT kampus:**

```
Subject: Request DNS A Record

Halo,

Mohon bantuan setup DNS untuk:
- Hostname: assessment.kampus.ac.id
- Type: A Record
- Value: <IP-VPS-ANDA>
- TTL: 3600

Terima kasih!
```

**Cek DNS sudah aktif:**
```bash
nslookup assessment.kampus.ac.id
# Harus return IP VPS Anda
```

**Waiting time:** 1-24 jam

---

## ✅ Testing

**1. Test dari VPS:**
```bash
curl -I http://localhost
# Expected: HTTP/1.1 200 OK
```

**2. Test dari browser:**
```
http://assessment.kampus.ac.id
```

**Expected:** Homepage muncul ✅

**3. Test fitur:**
- [ ] Register user
- [ ] Login
- [ ] Isi kuesioner
- [ ] Lihat hasil
- [ ] Admin panel

---

## 🔄 Update Aplikasi (Next Time)

```bash
cd /var/www/AsessmentOnline
bash deploy.sh

# Script otomatis akan:
# - Git pull latest code
# - Install dependencies
# - Build assets
# - Run migrations
# - Clear & optimize cache
# - Fix permissions
# - Reload services
```

**Waktu:** 2-3 menit

---

## 🆘 Troubleshooting

### Issue: 500 Error

```bash
# Check logs
tail -50 storage/logs/laravel.log

# Fix common issues
sudo chmod -R 775 storage bootstrap/cache
php artisan config:clear
php artisan cache:clear
```

### Issue: CSS/JS Not Loading

```bash
npm run build
php artisan config:clear
```

### Issue: Routes Not Working (404)

```bash
# Check Nginx config
sudo nginx -t
# Pastikan ada: try_files $uri $uri/ /index.php?$query_string;

sudo systemctl reload nginx
```

### Issue: Database Connection Error

```bash
# If SQLite:
chmod 664 database/database.sqlite
sudo chown www-data:www-data database/database.sqlite
```

---

## 📚 Documentation

Untuk panduan lengkap, lihat:
- **DEPLOYMENT_GUIDE.md** - Step-by-step detail
- **DEPLOYMENT_CHECKLIST.md** - Checklist lengkap
- **nginx-config.template** - Nginx config lengkap dengan komentar

---

## 🎉 Done!

Aplikasi Assessment Online sekarang live di:
**http://assessment.kampus.ac.id**

**Default Admin (jika ada seeder):**
- Username: `admin@kampus.ac.id`
- Password: `password` (ganti segera!)

---

## 📞 Need Help?

1. Check [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) untuk detail
2. Check [Troubleshooting section](#troubleshooting)
3. Check Laravel logs: `storage/logs/laravel.log`
4. Check Nginx logs: `/var/log/nginx/assessment-error.log`

**Happy Deploying! 🚀**
