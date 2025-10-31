# 📘 Panduan Menggunakan Vite CSS Bundles

## 🎯 Tujuan
Mengganti semua `<link>` CSS dengan `@vite()` directive untuk menghindari konflik class names dan mengoptimalkan performa.

---

## 🗂️ CSS Bundle Mapping

Setiap page type memiliki CSS bundle sendiri untuk mencegah tumpang tindih style:

| Bundle File | Digunakan untuk Blade Files | CSS yang Di-import |
|------------|------------------------------|-------------------|
| `app-public.css` | `home.blade.php` | style.css, style-footer.css |
| `app-mental-health.css` | `mental-health.blade.php`<br>`isi-data-diri.blade.php`<br>`kuesioner.blade.php`<br>`hasil.blade.php` | style.css, main.css, style-home-mh.css,<br>main-mh.css, styleform.css,<br>stylekuesioner.css, style-hasil-mh.css,<br>style-footer.css |
| `app-admin.css` | `admin.blade.php`<br>`admin-home.blade.php` | style-admin.css, style-admin-home.css,<br>style-footer.css |
| `app-user-dashboard.css` | `user-mental-health.blade.php` | style-user-mh.css, style-footer.css |
| `app-auth.css` | `login.blade.php`<br>`register.blade.php`<br>`lupa-password.blade.php` | style-login.css, style-register.css,<br>style-lupa-password.css |
| `app-karir.css` | `karir-home.blade.php`<br>`karir-form.blade.php`<br>`karir-interpretasi.blade.php`<br>`admin-karir.blade.php` | karir-home.css, karir-form.css,<br>karir-admin.css, karir-interpretasi.css,<br>karir-hitung.css, style.css |

---

## 📝 Cara Update Blade Files

### ❌ BEFORE (Old Way - Multiple <link> tags):

```blade
<!DOCTYPE html>
<html>
<head>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">
</head>
<body>
    ...
</body>
</html>
```

### ✅ AFTER (New Way - Single @vite directive):

```blade
<!DOCTYPE html>
<html>
<head>
    {{-- Ganti semua <link> dengan 1 line @vite --}}
    @vite(['resources/css/app-public.css'])
</head>
<body>
    ...
</body>
</html>
```

---

## 🔧 Implementasi Per File

### 1. **home.blade.php**
```blade
{{-- OLD: --}}
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">

{{-- NEW: --}}
@vite(['resources/css/app-public.css'])
```

### 2. **mental-health.blade.php**
```blade
{{-- OLD: --}}
<link href="{{ asset('css/style-home-mh.css') }}" rel="stylesheet">
<link href="{{ asset('css/main-mh.css') }}" rel="stylesheet">
<link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">

{{-- NEW: --}}
@vite(['resources/css/app-mental-health.css'])
```

### 3. **isi-data-diri.blade.php**
```blade
{{-- OLD: --}}
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="{{ asset('css/main.css') }}" rel="stylesheet">
<link href="{{ asset('css/styleform.css') }}" rel="stylesheet">
<link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">

{{-- NEW: --}}
@vite(['resources/css/app-mental-health.css'])
```

### 4. **kuesioner.blade.php**
```blade
{{-- OLD: --}}
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="{{ asset('css/main.css') }}" rel="stylesheet">
<link href="{{ asset('css/stylekuesioner.css') }}" rel="stylesheet">
<link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">

{{-- NEW: --}}
@vite(['resources/css/app-mental-health.css'])
```

### 5. **hasil.blade.php**
```blade
{{-- OLD: --}}
<link href="{{ asset('css/style-hasil-mh.css') }}" rel="stylesheet">
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="{{ asset('css/styleform.css') }}" rel="stylesheet">
<link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">

{{-- NEW: --}}
@vite(['resources/css/app-mental-health.css'])
```

### 6. **admin.blade.php**
```blade
{{-- OLD: --}}
<link href="{{ asset('css/style-admin.css') }}" rel="stylesheet">
<link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">

{{-- NEW: --}}
@vite(['resources/css/app-admin.css'])
```

### 7. **admin-home.blade.php**
```blade
{{-- OLD: --}}
<link href="{{ asset('css/style-admin-home.css') }}" rel="stylesheet">
<link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">

{{-- NEW: --}}
@vite(['resources/css/app-admin.css'])
```

### 8. **user-mental-health.blade.php**
```blade
{{-- OLD: --}}
<link href="{{ asset('css/style-user-mh.css') }}" rel="stylesheet">
<link href="{{ asset('css/style-footer.css') }}" rel="stylesheet">

{{-- NEW: --}}
@vite(['resources/css/app-user-dashboard.css'])
```

### 9. **login.blade.php**
```blade
{{-- OLD: --}}
<link href="{{ asset('css/style-login.css') }}" rel="stylesheet">

{{-- NEW: --}}
@vite(['resources/css/app-auth.css'])
```

### 10. **register.blade.php**
```blade
{{-- OLD: --}}
<link href="{{ asset('css/style-register.css') }}" rel="stylesheet">

{{-- NEW: --}}
@vite(['resources/css/app-auth.css'])
```

### 11. **karir-home.blade.php**
```blade
{{-- OLD: --}}
<link href="{{ asset('css/karir-home.css') }}" rel="stylesheet">
<link href="{{ asset('css/style.css') }}" rel="stylesheet">

{{-- NEW: --}}
@vite(['resources/css/app-karir.css'])
```

---

## ⚙️ Langkah Setup

### 1. Build CSS dengan Vite
```bash
npm install
npm run build
```

### 2. Untuk Development
```bash
npm run dev
```

### 3. Untuk Production
```bash
npm run build
```

---

## ✅ Keuntungan Pendekatan Ini

1. **✅ Tidak Ada Konflik Class Names**
   - Setiap page type punya bundle sendiri
   - Class `.container` di admin tidak bentrok dengan `.container` di public

2. **✅ Optimal Loading**
   - Hanya load CSS yang dibutuhkan per page
   - Tidak load semua CSS di semua page

3. **✅ Minification Otomatis**
   - Vite otomatis minify CSS di production
   - File size berkurang ~30-40%

4. **✅ Cache Busting**
   - Vite generate hash di filename (e.g., app-public.a1b2c3.css)
   - Browser otomatis reload jika CSS berubah

5. **✅ Maintainability**
   - Clear separation per section
   - Easy to debug per page type

---

## 🚨 PENTING!

**Jangan hapus file CSS di `public/css/`!**

File-file di `resources/css/app-*.css` hanya **mengimport** dari `public/css/`, bukan menggantikan.

**Struktur:**
```
public/css/
  ├── style.css              ← File asli (jangan hapus!)
  ├── style-admin.css        ← File asli (jangan hapus!)
  └── ...

resources/css/
  ├── app-public.css         ← Import dari public/css/
  ├── app-admin.css          ← Import dari public/css/
  └── ...
```

---

## 🐛 Troubleshooting

### Error: "Cannot find module"
```bash
# Re-install dependencies
npm install
```

### CSS tidak muncul di browser
```bash
# Clear cache dan rebuild
npm run build
php artisan config:clear
php artisan cache:clear
```

### Vite tidak running
```bash
# Check if node_modules exists
ls node_modules

# If not, install
npm install

# Then run
npm run dev
```

---

## 📊 Hasil Expected

**Before:**
- 20 file CSS, total ~344 KB
- Banyak request HTTP
- Class conflicts

**After:**
- 6 CSS bundles
- Minified production build
- No class conflicts
- Faster loading

---

## 🎉 Selesai!

Setelah implementasi, semua page akan load CSS yang sesuai tanpa konflik.
