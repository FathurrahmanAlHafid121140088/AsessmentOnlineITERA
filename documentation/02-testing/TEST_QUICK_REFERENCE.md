# Test Quick Reference Card

## 🚀 Quick Commands

```bash
# Run all tests
php artisan test

# Run with parallel execution (faster)
php artisan test --parallel

# Run specific file
php artisan test tests/Feature/AdminLoginTest.php

# Run specific test method
php artisan test --filter test_login_admin_dengan_email_dan_password_valid

# Stop on first failure
php artisan test --stop-on-failure

# Show coverage
php artisan test --coverage

# Generate HTML coverage report
php artisan test --coverage-html coverage
```

## 📁 Test Files Quick Access

| Module | File | Command |
|--------|------|---------|
| Admin Login | `AdminLoginTest.php` | `php artisan test tests/Feature/AdminLoginTest.php` |
| Logout & Session | `LogoutSessionTest.php` | `php artisan test tests/Feature/LogoutSessionTest.php` |
| Form Validation | `DataDiriValidationTest.php` | `php artisan test tests/Feature/DataDiriValidationTest.php` |
| Cetak PDF | `CetakPdfTest.php` | `php artisan test tests/Feature/CetakPdfTest.php` |
| Cascade Delete | `CascadeDeleteTest.php` | `php artisan test tests/Feature/CascadeDeleteTest.php` |
| Security | `SecurityValidationTest.php` | `php artisan test tests/Feature/SecurityValidationTest.php` |

## 🎯 Test by Whitebox Code

### Login & Auth (Pf-01 to Pf-22)
```bash
php artisan test tests/Feature/AdminLoginTest.php
php artisan test tests/Feature/AuthControllerTest.php
php artisan test tests/Feature/LogoutSessionTest.php
```

### Data Diri (Pf-23 to Pf-32)
```bash
php artisan test tests/Feature/DataDirisControllerTest.php
php artisan test tests/Feature/DataDiriValidationTest.php
```

### Kuesioner (Pf-33 to Pf-44)
```bash
php artisan test tests/Feature/HasilKuesionerControllerTest.php
```

### Dashboard (Pf-45 to Pf-67)
```bash
php artisan test tests/Feature/DashboardControllerTest.php
php artisan test tests/Feature/AdminDashboardCompleteTest.php
```

### Detail & PDF (Pf-68 to Pf-79)
```bash
php artisan test tests/Feature/HasilKuesionerCombinedControllerTest.php
php artisan test tests/Feature/CetakPdfTest.php
```

### Delete & Export (Pf-80 to Pf-91)
```bash
php artisan test tests/Feature/CascadeDeleteTest.php
php artisan test tests/Feature/ExportFunctionalityTest.php
```

### Security (Pf-92 to Pf-102)
```bash
php artisan test tests/Feature/SecurityValidationTest.php
```

## 🔍 Common Test Patterns

### Testing with Auth
```php
// Admin
$admin = Admin::factory()->create();
$this->actingAs($admin, 'admin');

// User
$user = Users::factory()->create();
$this->actingAs($user);
```

### Testing Forms
```php
$response = $this->post(route('route.name'), [
    'field' => 'value'
]);

$response->assertRedirect();
$response->assertSessionHas('success');
$response->assertSessionHasNoErrors();
```

### Testing Database
```php
$this->assertDatabaseHas('table', ['field' => 'value']);
$this->assertDatabaseMissing('table', ['field' => 'value']);
```

### Testing Views
```php
$response->assertViewIs('view-name');
$response->assertViewHas('variable');
$response->assertSee('text');
```

## 🐛 Debugging

```bash
# Verbose output
php artisan test --verbose

# Single test with debug
php artisan test --filter test_name --stop-on-failure

# Enable query log in test
DB::enableQueryLog();
// ... code
dd(DB::getQueryLog());
```

## 📊 Coverage Stats

| Module | Tests | Coverage |
|--------|-------|----------|
| Total | 173+ | 100% |
| Login | 26 | ✅ |
| Validation | 20 | ✅ |
| Kuesioner | 19 | ✅ |
| Dashboard | 35 | ✅ |
| PDF | 12 | ✅ |
| Delete | 8 | ✅ |
| Export | 9 | ✅ |
| Security | 13 | ✅ |
| Models | 25+ | ✅ |

## 🆘 Quick Troubleshooting

| Problem | Solution |
|---------|----------|
| "Class not found" | `composer dump-autoload` |
| "Database doesn't exist" | `php artisan migrate --env=testing` |
| "Session not working" | Add `Session::start()` in `setUp()` |
| "CSRF mismatch" | Use `$this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)` |
| Tests too slow | Use `php artisan test --parallel` |

## 📚 Documentation

- **Full Guide**: `tests/README.md`
- **Mapping**: `documentation/TEST_SUITE_COMPLETE_MAPPING.md`
- **Summary**: `TESTING_SUMMARY.md`
- **Whitebox**: `documentation/WHITEBOX_TESTING_MENTAL_HEALTH.md`

## 🎯 New Tests Summary

✨ **6 New Test Files Added**:

1. `AdminLoginTest.php` - 9 tests (Pf-01 to Pf-09)
2. `LogoutSessionTest.php` - 7 tests (Pf-16 to Pf-22)
3. `DataDiriValidationTest.php` - 11 tests (Pf-25, 26, 28, 32)
4. `CetakPdfTest.php` - 12 tests (Pf-73 to Pf-79)
5. `CascadeDeleteTest.php` - 8 tests (Pf-81 to Pf-86)
6. `SecurityValidationTest.php` - 13 tests (Pf-91, 92, 94, 95, 98-102)

**Total New Tests**: 60 tests
**Total All Tests**: 173+ tests
**Whitebox Coverage**: 102/102 (100%)

---

**Last Updated**: 2025-11-20
**Version**: 2.0
