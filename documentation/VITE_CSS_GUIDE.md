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

## 🔍 TROUBLESHOOTING ADVANCED

### Case Study: app-auth.css Styling Issues (Nov 2025)

**Problem:**
Login page styling tidak konsisten setelah migrasi ke Vite. Background image tidak muncul dan beberapa style tidak ter-apply.

**Root Cause:**
1. `@import` path dengan relative URL tidak ter-resolve oleh Vite
2. Background image path `url("../assets/Sprinkle.svg")` gagal di-bundle
3. Beberapa CSS tidak ter-load karena import gagal

**Solution:**

#### 1. **Update app-auth.css dengan Complete CSS**

**Before (`resources/css/app-auth.css`):**
```css
/* Only imports - NOT WORKING with Vite */
@import url('../../public/css/style-login.css');
@import url('../../public/css/style-register.css');
@import url('../../public/css/style-lupa-password.css');
```

**After (`resources/css/app-auth.css`):**
```css
/* Complete CSS copy dengan path fix */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

body.login-page {
    background-color: #ffffff;
    /* ✅ FIXED: Absolute path instead of relative */
    background-image: url("/assets/Sprinkle.svg");
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* ... rest of 341 lines CSS ... */
```

#### 2. **Asset Path Rules dengan Vite**

| Path Type | Example | Vite Behavior | Recommended |
|-----------|---------|---------------|-------------|
| Relative | `url("../assets/file.svg")` | ❌ May fail | Avoid |
| Absolute | `url("/assets/file.svg")` | ✅ Works | Use this |
| External | `url("https://...")` | ✅ Works | OK for fonts |
| Asset in public | `/assets/file.svg` | ✅ Served as-is | Best |

#### 3. **Best Practices untuk Auth Pages**

**DO:**
```css
/* ✅ Use absolute paths for public assets */
background-image: url("/assets/Sprinkle.svg");

/* ✅ Import external fonts directly */
@import url("https://fonts.googleapis.com/css2?family=Poppins...");

/* ✅ Copy complete CSS instead of @import dari public */
body.login-page { ... }
```

**DON'T:**
```css
/* ❌ Avoid relative paths in Vite bundles */
background-image: url("../assets/Sprinkle.svg");

/* ❌ Avoid @import dari public folder */
@import url('../../public/css/style-login.css');

/* ❌ Don't use asset() helper in CSS */
background-image: asset('assets/Sprinkle.svg'); /* This is Blade syntax */
```

#### 4. **Rebuild dan Verify**

```bash
# Rebuild Vite assets
npm run build

# Output should show:
# ✓ built in 2.20s
# app-auth-CceyxNNV.css   4.02 kB │ gzip: 1.30 kB

# Verify file exists
ls -lh public/build/assets/app-auth-*.css

# Clear Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

#### 5. **Verify in Browser**

1. Open login page (Ctrl + Shift + R untuk hard refresh)
2. Check DevTools → Network tab
3. Verify `app-auth-*.css` loaded (status 200)
4. Check Elements tab → Computed styles
5. Verify background-image applied

**Expected in DevTools:**
```
Network Tab:
GET /build/assets/app-auth-CceyxNNV.css    200  4.0 kB

Computed Styles:
background-image: url("/assets/Sprinkle.svg")
```

---

## 📝 BEST PRACTICES UPDATE

### For Authentication Pages

1. **Complete CSS Copy**
   - Copy seluruh CSS ke bundle file (jangan gunakan `@import` dari public)
   - Benefit: Vite bisa optimize dan minify dengan benar

2. **Absolute Asset Paths**
   - Gunakan `/assets/file.svg` bukan `../assets/file.svg`
   - Benefit: Konsisten di development dan production

3. **External Resources**
   - Font dari CDN: OK menggunakan `@import url("https://...")`
   - Icon libraries: OK menggunakan CDN `<script>` di Blade

4. **Testing Checklist**
   - [ ] Hard refresh browser (Ctrl + Shift + R)
   - [ ] Check Network tab untuk CSS file
   - [ ] Verify background images loaded
   - [ ] Test di multiple browsers
   - [ ] Verify mobile responsive

### For All Vite Bundles

1. **File Organization**
   ```
   resources/css/
   ├── app-auth.css       ✅ Complete CSS
   ├── app-public.css     ✅ Only @import dari public
   ├── app-mh-home.css    ✅ Only @import dari public
   └── ...
   ```

2. **When to Use Complete CSS vs @import**

   **Use Complete CSS when:**
   - Asset paths dalam CSS (background-image, fonts, etc.)
   - CSS file kecil (< 10 KB)
   - Styling critical untuk page

   **Use @import when:**
   - CSS file besar (Bootstrap, vendors)
   - CSS stable dan tidak perlu diubah
   - Shared CSS across multiple pages

3. **Build Verification**
   ```bash
   # Always check build output
   npm run build

   # Look for warnings
   # Example warning:
   # /assets/Sprinkle.svg referenced in /assets/Sprinkle.svg
   # didn't resolve at build time, it will remain unchanged to be
   # resolved at runtime

   # This is OK for public assets!
   ```

---

## 🎉 Selesai!

Setelah implementasi, semua page akan load CSS yang sesuai tanpa konflik.

**Updated:** 1 November 2025
**Version:** 1.1.0
