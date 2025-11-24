# Panduan Implementasi PHPUnit Testing
# Mental Health Assessment System

## Daftar Isi

1. [Pengenalan PHPUnit](#1-pengenalan-phpunit)
2. [Setup Environment](#2-setup-environment)
3. [Struktur Test File](#3-struktur-test-file)
4. [Menulis Test Case](#4-menulis-test-case)
5. [Testing Patterns](#5-testing-patterns)
6. [Laravel Testing Features](#6-laravel-testing-features)
7. [Best Practices](#7-best-practices)
8. [Troubleshooting](#8-troubleshooting)

---

## 1. Pengenalan PHPUnit

### 1.1 Apa itu PHPUnit?

PHPUnit adalah framework testing unit untuk PHP yang menjadi standar industri. Framework ini memungkinkan developer untuk:
- Menulis automated tests
- Menjalankan tests secara konsisten
- Menghasilkan coverage reports
- Mengintegrasikan dengan CI/CD pipeline

### 1.2 Mengapa PHPUnit?

**Keuntungan:**
- ✅ Standar industri untuk PHP testing
- ✅ Integrasi sempurna dengan Laravel
- ✅ Rich assertion library
- ✅ Support untuk mocking dan stubbing
- ✅ Parallel test execution
- ✅ Code coverage reports

### 1.3 Tipe Testing dengan PHPUnit

```
┌─────────────────────────────────────────┐
│         Testing Pyramid                 │
├─────────────────────────────────────────┤
│              E2E Tests                  │  ← Integration Tests
│           ┌─────────────┐               │
│           │ Integration │               │  ← Feature Tests
│       ┌───┴─────────────┴───┐           │
│       │    Unit Tests        │          │  ← Unit Tests
│   ┌───┴──────────────────────┴───┐     │
│   └──────────────────────────────┘     │
└─────────────────────────────────────────┘
```

**Unit Tests**: Test individual methods/functions
**Feature Tests**: Test complete features dengan HTTP requests
**Integration Tests**: Test multiple components working together

---

## 2. Setup Environment

### 2.1 Requirement

```json
{
    "require-dev": {
        "phpunit/phpunit": "^10.5",
        "mockery/mockery": "^1.6",
        "laravel/framework": "^11.0"
    }
}
```

### 2.2 Install Dependencies

```bash
# Install PHPUnit dan dependencies
composer install

# Verify installation
vendor/bin/phpunit --version
```

### 2.3 Konfigurasi phpunit.xml

File `phpunit.xml` di root project:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
>
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>

    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_CONNECTION" value="mysql"/>
        <env name="DB_DATABASE" value="testing_database"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>

    <source>
        <include>
            <directory suffix=".php">./app</directory>
        </include>
    </source>
</phpunit>
```

### 2.4 Setup Database Testing

**Create Testing Database:**
```sql
CREATE DATABASE asessment_online_test;
```

**Configure .env.testing:**
```env
APP_ENV=testing
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=asessment_online_test
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
```

### 2.5 Run Migration untuk Testing

```bash
# Run migrations
php artisan migrate --env=testing

# Run seeders (optional)
php artisan db:seed --env=testing
```

---

## 3. Struktur Test File

### 3.1 Naming Convention

**File Name:**
- Format: `{ClassName}Test.php`
- Location Feature: `tests/Feature/{ClassName}Test.php`
- Location Unit: `tests/Unit/{ClassName}Test.php`

**Examples:**
```
tests/
├── Feature/
│   ├── AdminAuthTest.php
│   ├── DataDirisControllerTest.php
│   └── HasilKuesionerControllerTest.php
└── Unit/
    ├── DataDirisTest.php
    └── HasilKuesionerTest.php
```

### 3.2 Basic Test File Structure

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup yang dijalankan sebelum setiap test
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Setup code here
    }

    /**
     * Cleanup yang dijalankan setelah setiap test
     */
    protected function tearDown(): void
    {
        // Cleanup code here
        parent::tearDown();
    }

    /**
     * Test case example
     *
     * @test
     */
    public function test_example_functionality()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->get('/dashboard');

        // Assert
        $response->assertStatus(200);
    }
}
```

### 3.3 Test Method Naming

**Convention:**
- Prefix: `test_`
- Format: `test_{what_you_test}_{expected_result}`
- Snake_case lowercase

**Good Examples:**
```php
test_login_admin_dengan_kredensial_valid()
test_kategori_sangat_sehat_untuk_skor_208()
test_validasi_email_harus_format_valid()
```

**Bad Examples:**
```php
test1()                      // ❌ Tidak deskriptif
testLogin()                  // ❌ Tidak jelas skenario
testKategorisasi()          // ❌ Terlalu general
```

---

## 4. Menulis Test Case

### 4.1 Arrange-Act-Assert Pattern

**Template:**
```php
public function test_example()
{
    // ARRANGE: Setup preconditions dan input
    $user = User::factory()->create(['email' => 'test@example.com']);
    $expectedResult = 'success';

    // ACT: Execute the function being tested
    $result = $this->actingAs($user)->post('/action', ['data' => 'value']);

    // ASSERT: Verify the output
    $this->assertEquals($expectedResult, $result->status());
}
```

### 4.2 Common Assertions

**HTTP Assertions:**
```php
// Status codes
$response->assertStatus(200);
$response->assertOk();              // 200
$response->assertCreated();         // 201
$response->assertNoContent();       // 204
$response->assertNotFound();        // 404
$response->assertForbidden();       // 403
$response->assertUnauthorized();    // 401

// Redirects
$response->assertRedirect('/dashboard');
$response->assertRedirectToRoute('home');

// Views
$response->assertViewIs('admin.dashboard');
$response->assertViewHas('users');
$response->assertViewHas('count', 10);

// Session
$response->assertSessionHas('success');
$response->assertSessionHasErrors(['email']);
```

**Database Assertions:**
```php
// Check record exists
$this->assertDatabaseHas('users', [
    'email' => 'test@example.com'
]);

// Check record doesn't exist
$this->assertDatabaseMissing('users', [
    'email' => 'deleted@example.com'
]);

// Check count
$this->assertDatabaseCount('users', 5);
```

**Authentication Assertions:**
```php
// Check authenticated
$this->assertAuthenticated();
$this->assertAuthenticatedAs($user);

// Check guest (not authenticated)
$this->assertGuest();

// Check guard
$this->assertAuthenticatedAs($admin, 'admin');
```

**General Assertions:**
```php
// Equality
$this->assertEquals($expected, $actual);
$this->assertNotEquals($expected, $actual);

// Identity (same object)
$this->assertSame($expected, $actual);

// Type
$this->assertIsInt($value);
$this->assertIsString($value);
$this->assertIsArray($value);

// Boolean
$this->assertTrue($condition);
$this->assertFalse($condition);

// Null
$this->assertNull($value);
$this->assertNotNull($value);

// Empty
$this->assertEmpty($array);
$this->assertNotEmpty($array);

// Contains
$this->assertStringContainsString('needle', $haystack);
$this->assertContains('value', $array);

// Count
$this->assertCount(5, $array);
```

### 4.3 Testing Controller Method

**Example: Testing Login**

```php
public function test_login_dengan_kredensial_valid()
{
    // Arrange
    $admin = Admin::factory()->create([
        'email' => 'admin@test.com',
        'password' => Hash::make('password123')
    ]);

    // Act
    $response = $this->post('/login', [
        'email' => 'admin@test.com',
        'password' => 'password123'
    ]);

    // Assert
    $response->assertRedirect('/admin/mental-health');
    $this->assertAuthenticatedAs($admin, 'admin');
}
```

**Example: Testing Validation**

```php
public function test_validasi_email_wajib_diisi()
{
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = $this->actingAs($user)->post('/data-diri/store', [
        'nama' => 'Test User',
        // email kosong
        'usia' => 20,
    ]);

    // Assert
    $response->assertSessionHasErrors('email');
    $this->assertDatabaseMissing('data_diris', [
        'nama' => 'Test User'
    ]);
}
```

### 4.4 Testing Database Operations

**Example: Testing Insert**

```php
public function test_penyimpanan_data_diri_baru()
{
    // Arrange
    $user = User::factory()->create(['nim' => '121450088']);

    // Act
    $this->actingAs($user)->post('/data-diri/store', [
        'nim' => '121450088',
        'nama' => 'John Doe',
        'email' => 'john@student.itera.ac.id',
        // ... fields lainnya
    ]);

    // Assert
    $this->assertDatabaseHas('data_diris', [
        'nim' => '121450088',
        'nama' => 'John Doe'
    ]);
}
```

**Example: Testing Update**

```php
public function test_update_data_diri_existing()
{
    // Arrange
    $user = User::factory()->create(['nim' => '121450088']);
    DataDiris::factory()->create([
        'nim' => '121450088',
        'nama' => 'Old Name'
    ]);

    // Act
    $this->actingAs($user)->post('/data-diri/store', [
        'nim' => '121450088',
        'nama' => 'New Name',
        // ... fields lainnya
    ]);

    // Assert
    $this->assertDatabaseHas('data_diris', [
        'nim' => '121450088',
        'nama' => 'New Name'
    ]);

    // Pastikan tidak ada duplikasi
    $this->assertDatabaseCount('data_diris', 1);
}
```

**Example: Testing Delete**

```php
public function test_hapus_hasil_kuesioner()
{
    // Arrange
    $admin = Admin::factory()->create();
    $hasil = HasilKuesioner::factory()->create(['nim' => '121450088']);

    // Act
    $this->actingAs($admin, 'admin')
         ->delete("/admin/mental-health/{$hasil->id}");

    // Assert
    $this->assertDatabaseMissing('hasil_kuesioners', [
        'id' => $hasil->id
    ]);
}
```

---

## 5. Testing Patterns

### 5.1 Factory Pattern

**Definisi Factory:**

File: `database/factories/DataDirisFactory.php`
```php
<?php

namespace Database\Factories;

use App\Models\DataDiris;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataDirisFactory extends Factory
{
    protected $model = DataDiris::class;

    public function definition(): array
    {
        return [
            'nim' => fake()->unique()->numerify('12145####'),
            'nama' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'usia' => fake()->numberBetween(18, 25),
            'email' => fake()->unique()->safeEmail(),
            'program_studi' => fake()->randomElement([
                'Teknik Informatika',
                'Teknik Elektro',
                'Teknik Sipil'
            ]),
            'fakultas' => 'FTII',
            'provinsi' => 'Lampung',
            'alamat' => fake()->address(),
            'asal_sekolah' => fake()->randomElement(['SMA', 'SMK', 'MA']),
            'status_tinggal' => fake()->randomElement(['Kos', 'Rumah Sendiri']),
        ];
    }
}
```

**Menggunakan Factory dalam Test:**

```php
// Create single record
$dataDiri = DataDiris::factory()->create();

// Create dengan custom attributes
$dataDiri = DataDiris::factory()->create([
    'nim' => '121450088',
    'nama' => 'Specific Name'
]);

// Create multiple records
$dataDiris = DataDiris::factory()->count(10)->create();

// Make (tidak save ke database)
$dataDiri = DataDiris::factory()->make();
```

### 5.2 Mocking External Services

**Example: Mock Google OAuth**

```php
use Laravel\Socialite\Facades\Socialite;
use Mockery;

public function test_google_oauth_callback()
{
    // Arrange - Mock Google User
    $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
    $abstractUser->shouldReceive('getId')
                 ->andReturn('123456789');
    $abstractUser->shouldReceive('getEmail')
                 ->andReturn('test@student.itera.ac.id');
    $abstractUser->shouldReceive('getName')
                 ->andReturn('Test User');

    // Mock Socialite
    Socialite::shouldReceive('driver->user')
             ->andReturn($abstractUser);

    // Act
    $response = $this->get('/login/google/callback');

    // Assert
    $response->assertRedirect('/user/mental-health');
    $this->assertAuthenticated();
}
```

### 5.3 Testing dengan Data Provider

**Untuk menguji multiple scenarios dengan data berbeda:**

```php
/**
 * @dataProvider kategorisasiDataProvider
 */
public function test_kategorisasi_berdasarkan_skor($skor, $expectedKategori)
{
    // Arrange
    $user = User::factory()->create(['nim' => '121450088']);
    DataDiris::factory()->create(['nim' => '121450088']);

    $jawaban = $this->generateJawabanForSkor($skor);

    // Act
    $this->actingAs($user)->post('/kuesioner/store', $jawaban);

    // Assert
    $this->assertDatabaseHas('hasil_kuesioners', [
        'nim' => '121450088',
        'total_skor' => $skor,
        'kategori' => $expectedKategori
    ]);
}

/**
 * Data provider untuk test kategorisasi
 */
public function kategorisasiDataProvider()
{
    return [
        'Perlu Dukungan Intensif - Min' => [38, 'Perlu Dukungan Intensif'],
        'Perlu Dukungan Intensif - Max' => [75, 'Perlu Dukungan Intensif'],
        'Perlu Dukungan - Min' => [76, 'Perlu Dukungan'],
        'Perlu Dukungan - Max' => [113, 'Perlu Dukungan'],
        'Cukup Sehat - Min' => [114, 'Cukup Sehat'],
        'Cukup Sehat - Max' => [151, 'Cukup Sehat'],
        'Sehat - Min' => [152, 'Sehat'],
        'Sehat - Max' => [189, 'Sehat'],
        'Sangat Sehat - Min' => [190, 'Sangat Sehat'],
        'Sangat Sehat - Max' => [226, 'Sangat Sehat'],
    ];
}
```

### 5.4 Testing Middleware

**Example: Test AdminAuth Middleware**

```php
public function test_admin_route_requires_admin_authentication()
{
    // Act - Access tanpa login
    $response = $this->get('/admin/mental-health');

    // Assert - Redirect ke login
    $response->assertRedirect('/login');
}

public function test_regular_user_cannot_access_admin_route()
{
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = $this->actingAs($user)->get('/admin/mental-health');

    // Assert
    $response->assertForbidden(); // atau assertRedirect
}

public function test_admin_can_access_admin_route()
{
    // Arrange
    $admin = Admin::factory()->create();

    // Act
    $response = $this->actingAs($admin, 'admin')
                     ->get('/admin/mental-health');

    // Assert
    $response->assertOk();
}
```

---

## 6. Laravel Testing Features

### 6.1 RefreshDatabase Trait

**Automatically reset database after each test:**

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_example()
    {
        // Database akan di-reset setelah test ini
    }
}
```

### 6.2 HTTP Testing

**Making HTTP Requests:**

```php
// GET request
$response = $this->get('/dashboard');

// POST request
$response = $this->post('/data-diri/store', [
    'nama' => 'Test',
    'email' => 'test@example.com'
]);

// PUT request
$response = $this->put('/user/1', ['name' => 'Updated']);

// DELETE request
$response = $this->delete('/user/1');

// Authenticated request
$response = $this->actingAs($user)->get('/dashboard');

// With headers
$response = $this->withHeaders([
    'X-Header' => 'Value',
])->get('/api/user');

// Follow redirects
$response = $this->followingRedirects()
                 ->post('/login', $credentials);
```

### 6.3 Session Testing

```php
// Set session data
$this->session(['key' => 'value']);

// Assert session has
$response->assertSessionHas('success');
$response->assertSessionHas('user.name', 'John');

// Assert session has errors
$response->assertSessionHasErrors(['email', 'password']);
$response->assertSessionHasErrorsIn('registration', ['email']);

// Assert session doesn't have
$response->assertSessionMissing('error');
```

### 6.4 Cache Testing

```php
use Illuminate\Support\Facades\Cache;

public function test_data_is_cached()
{
    // Arrange
    $admin = Admin::factory()->create();

    // Act - First request (data di-cache)
    $this->actingAs($admin, 'admin')->get('/admin/mental-health');

    // Assert
    $this->assertTrue(Cache::has('mh.admin.user_stats'));
}

public function test_cache_is_invalidated_after_new_data()
{
    // Arrange
    $admin = Admin::factory()->create();
    $user = User::factory()->create();

    // Load data ke cache
    $this->actingAs($admin, 'admin')->get('/admin/mental-health');
    $this->assertTrue(Cache::has('mh.admin.user_stats'));

    // Act - Submit data baru
    $this->actingAs($user)->post('/kuesioner/store', [/* data */]);

    // Assert - Cache cleared
    $this->assertFalse(Cache::has('mh.admin.user_stats'));
}
```

### 6.5 Testing Email

```php
use Illuminate\Support\Facades\Mail;

public function test_email_is_sent()
{
    Mail::fake();

    // Act - Trigger email sending
    $this->post('/contact', ['message' => 'Hello']);

    // Assert
    Mail::assertSent(ContactMail::class, function ($mail) {
        return $mail->hasTo('admin@example.com');
    });
}
```

---

## 7. Best Practices

### 7.1 Test Independence

**❌ Bad - Tests depend on each other:**
```php
class BadTest extends TestCase
{
    private $userId;

    public function test_create_user()
    {
        $user = User::create([...]);
        $this->userId = $user->id; // ❌ Sharing state
    }

    public function test_update_user()
    {
        // ❌ Depends on test_create_user
        User::find($this->userId)->update([...]);
    }
}
```

**✅ Good - Each test is independent:**
```php
class GoodTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user()
    {
        $user = User::create([...]);
        $this->assertDatabaseHas('users', [...]);
    }

    public function test_update_user()
    {
        // ✅ Create own data
        $user = User::factory()->create();
        $user->update([...]);
        $this->assertDatabaseHas('users', [...]);
    }
}
```

### 7.2 Test One Thing

**❌ Bad - Testing multiple things:**
```php
public function test_user_workflow()
{
    // ❌ Too many assertions in one test
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertOk();

    $response = $this->actingAs($user)->post('/data-diri', [...]);
    $response->assertRedirect();

    $response = $this->actingAs($user)->post('/kuesioner', [...]);
    $response->assertRedirect();
}
```

**✅ Good - Separate tests:**
```php
public function test_user_can_view_dashboard()
{
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertOk();
}

public function test_user_can_submit_data_diri()
{
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post('/data-diri', [...]);
    $response->assertRedirect('/kuesioner');
}

public function test_user_can_submit_kuesioner()
{
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post('/kuesioner', [...]);
    $response->assertRedirect('/hasil');
}
```

### 7.3 Use Descriptive Names

**❌ Bad:**
```php
public function test1() { }
public function testLogin() { }
public function testKuesioner() { }
```

**✅ Good:**
```php
public function test_login_dengan_email_valid_berhasil() { }
public function test_login_dengan_password_salah_gagal() { }
public function test_kuesioner_skor_208_kategorisasi_sangat_sehat() { }
```

### 7.4 Arrange-Act-Assert

**Always follow AAA pattern:**
```php
public function test_example()
{
    // ARRANGE: Setup
    $user = User::factory()->create();
    $expectedStatus = 200;

    // ACT: Execute
    $response = $this->actingAs($user)->get('/dashboard');

    // ASSERT: Verify
    $response->assertStatus($expectedStatus);
}
```

### 7.5 Use Factories, Not Raw Data

**❌ Bad:**
```php
$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => Hash::make('password'),
    // ... 20 more fields
]);
```

**✅ Good:**
```php
$user = User::factory()->create([
    'email' => 'test@example.com' // Override only what matters
]);
```

### 7.6 Test Edge Cases

**Don't just test happy path:**
```php
// Happy path
public function test_valid_input_accepted() { }

// Edge cases
public function test_minimum_value_accepted() { }
public function test_maximum_value_accepted() { }
public function test_empty_input_rejected() { }
public function test_invalid_format_rejected() { }
public function test_duplicate_data_handled() { }
public function test_null_value_handled() { }
```

---

## 8. Troubleshooting

### 8.1 Common Issues

**Issue: "Class 'Tests\TestCase' not found"**
```bash
# Solution: Run composer autoload
composer dump-autoload
```

**Issue: "Database file does not exist"**
```bash
# Solution: Run migrations
php artisan migrate --env=testing
```

**Issue: "Tests are slow"**
```bash
# Solution: Use parallel execution
php artisan test --parallel

# Or use in-memory SQLite
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

**Issue: "CSRF token mismatch"**
```php
// Solution: Use withoutMiddleware or withoutExceptionHandling
$this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
```

### 8.2 Debugging Tests

**Output debug information:**
```php
public function test_example()
{
    $response = $this->get('/dashboard');

    // Dump response content
    dump($response->getContent());

    // Dump session
    dump(session()->all());

    // Dump database
    dump(User::all()->toArray());

    $response->assertOk();
}
```

**Use dd() to stop execution:**
```php
public function test_example()
{
    $user = User::factory()->create();

    dd($user->toArray()); // Dump and die

    $response = $this->actingAs($user)->get('/dashboard');
}
```

### 8.3 Running Specific Tests

```bash
# Run specific file
php artisan test tests/Feature/AdminAuthTest.php

# Run specific method
php artisan test --filter test_login_admin_dengan_kredensial_valid

# Run with specific group
php artisan test --group authentication

# Stop on first failure
php artisan test --stop-on-failure
```

---

## Kesimpulan

Panduan ini mencakup:

1. ✅ Setup PHPUnit di Laravel
2. ✅ Struktur test files
3. ✅ Menulis test cases
4. ✅ Testing patterns (Factory, Mocking, Data Provider)
5. ✅ Laravel testing features
6. ✅ Best practices
7. ✅ Troubleshooting

**Next Steps:**
- Praktikkan dengan membuat test untuk fitur baru
- Jalankan `php artisan test --coverage` untuk cek coverage
- Integrasikan dengan CI/CD pipeline
- Maintain test suite seiring development

**Resources:**
- Laravel Testing Docs: https://laravel.com/docs/11.x/testing
- PHPUnit Docs: https://phpunit.de/documentation.html
- Test Examples: Lihat `tests/Feature/` dan `tests/Unit/` di project ini

---

**Prepared by**: Development Team
**Date**: November 2025
**Version**: 1.0
