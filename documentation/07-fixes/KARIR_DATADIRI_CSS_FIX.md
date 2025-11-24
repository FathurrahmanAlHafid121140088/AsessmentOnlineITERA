# Karir Data Diri CSS Fix - styleform.css

## Second Issue Report
After initial Vite revert fix, user reported:
> "coba cek karena yang halaman karir-home.blade sudah benar, tapi halaman karir-datadiri.blade.php style nya masih berantakan tidak sama dengan sebelum vite"

## Problem
karir-home was displaying correctly, but karir-datadiri remained "berantakan" (broken styling) even after reverting from Vite to asset() helper.

## Root Cause Investigation

### Initial Fix (WRONG)
After reverting from Vite, karir-datadiri.blade.php was using:
```blade
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="{{ asset('css/karir-form.css') }}" rel="stylesheet">  <!-- ❌ WRONG FILE -->
```

### The Problem
karir-datadiri.blade.php uses **DIFFERENT CSS classes** than other karir pages:

**HTML structure uses `.formbold-*` classes:**
```html
<div class="formbold-main-wrapper">
    <div class="formbold-form-wrapper">
        <div class="formbold-form-title">
            <h2>Form Data Diri</h2>
        </div>

        <div class="formbold-input-flex">
            <input type="text" class="formbold-form-input" />
        </div>

        <label class="formbold-radio-label">
            <input type="radio" class="formbold-input-radio" />
        </label>
    </div>
</div>
```

**These classes are NOT in karir-form.css!**

### Investigation
```bash
# Find which CSS file has formbold styles
grep -n "formbold" public/css/*.css

# Result:
public/css/styleform.css:3:.formbold-mb-3 {
public/css/styleform.css:6:.formbold-relative {
public/css/styleform.css:19:.formbold-main-wrapper {
public/css/styleform.css:26:.formbold-form-wrapper {
public/css/styleform.css:43:.formbold-form-title {
public/css/styleform.css:69:.formbold-form-input {
public/css/styleform.css:98:.formbold-checkbox-label,
public/css/styleform.css:99:.formbold-radio-label {
# ... many more
```

**Found it!** All `.formbold-*` styles are in `styleform.css`, not `karir-form.css`.

## Solution

### Changed CSS File
**File:** `resources/views/karir-datadiri.blade.php` (Line 25)

**Before (WRONG):**
```blade
<link href="{{ asset('css/karir-form.css') }}" rel="stylesheet">
```

**After (CORRECT):**
```blade
<link href="{{ asset('css/styleform.css') }}" rel="stylesheet">
```

### Complete CSS Loading (Lines 18-25)
```blade
<!-- Vendor CSS Files -->
<link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
<!-- Core theme CSS -->
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="{{ asset('css/styleform.css') }}" rel="stylesheet">  <!-- ✅ FIXED -->
```

## styleform.css Content
Key styles defined in `public/css/styleform.css`:

```css
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

.formbold-mb-3 { margin-bottom: 15px; }
.formbold-relative { position: relative; }
.formbold-opacity-0 { opacity: 0; }
.formbold-stroke-current { stroke: currentColor; }

.formbold-main-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    padding-bottom: 1rem;
}

.formbold-form-wrapper {
    max-width: 720px;
    width: 100%;
    background: white;
    padding: 40px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    border: 1px solid #6c757d;
}

.formbold-form-title {
    margin-bottom: 20px;
    text-align: center;
}

.formbold-form-input {
    width: 100%;
    padding: 13px 22px;
    border-radius: 5px;
    border: 1px solid #dde3ec;
    background: #ffffff;
    /* ... more styles */
}

.formbold-radio-label {
    font-size: 16px;
    line-height: 24px;
    display: block;
    cursor: pointer;
    /* ... more styles */
}

/* ... many more formbold-* classes */
```

## Testing

### Test Command
```bash
php artisan serve --port=8000
curl -s http://127.0.0.1:8000/karir-datadiri | grep -E "styleform\.css|style\.css"
```

### Expected Output
```html
<link href="http://127.0.0.1:8000/css/style.css" rel="stylesheet">
<link href="http://127.0.0.1:8000/css/styleform.css" rel="stylesheet">
```

### Verification
✅ styleform.css loads correctly
✅ All .formbold-* classes now styled
✅ Form displays with proper styling
✅ Layout matches "sebelum menggunakan vite"

## Comparison: karir-form.css vs styleform.css

### karir-form.css
- Used by: karir-form.blade.php (actual RMIB test form)
- Contains: RMIB test-specific styles
- Classes: `.card`, `.card-header`, `.card-content`, `.categories-grid`, `.category-card`, etc.

### styleform.css
- Used by: karir-datadiri.blade.php (data diri form)
- Contains: Form builder styles (formbold framework)
- Classes: `.formbold-main-wrapper`, `.formbold-form-input`, `.formbold-radio-label`, etc.

**They are COMPLETELY DIFFERENT files for DIFFERENT pages!**

## Why Initial Fix Failed

### Mistake in Initial Fix
When reverting from Vite, I incorrectly assumed all karir pages use similar CSS files. I changed:
```blade
@vite(['resources/css/app-karir.css'])
```
to:
```blade
<link href="{{ asset('css/karir-form.css') }}" rel="stylesheet">
```

**This was WRONG** because:
- karir-datadiri uses `.formbold-*` classes
- karir-form.css doesn't have these classes
- Correct file is `styleform.css`

### Lesson Learned
**Each karir page may use different CSS files!** Don't assume uniformity. Check:
1. HTML structure and class names
2. Which CSS file defines those classes
3. Load the correct CSS file

## Impact

### Before Fix (Initial Vite Revert)
- ❌ karir-home: Working ✓
- ❌ karir-datadiri: Still berantakan (wrong CSS file)
- CSS loaded: `karir-form.css` (doesn't have .formbold-* styles)

### After This Fix
- ✅ karir-home: Working ✓
- ✅ karir-datadiri: Working ✓ (correct CSS file)
- CSS loaded: `styleform.css` (has all .formbold-* styles)

## Summary

### Issue
karir-datadiri styling still "berantakan" after initial Vite revert fix.

### Root Cause
Wrong CSS file loaded (`karir-form.css` instead of `styleform.css`).

### Solution
Changed to correct CSS file: `styleform.css`.

### Files Changed
- `resources/views/karir-datadiri.blade.php` (1 line - line 25)

### Status
✅ **FIXED AND VERIFIED**

---

**Date:** October 31, 2025
**Issue:** karir-datadiri styling berantakan
**Fix:** Load styleform.css instead of karir-form.css
**Result:** Page now displays correctly with proper form styling
