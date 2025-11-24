<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/**
 * Test untuk fitur Login & Logout Admin
 * Coverage: Pf-01 s/d Pf-09, Pf-16, Pf-18, Pf-21, Pf-22
 */
class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Pf-01: Menguji login admin dengan email dan password valid
     */
    public function test_login_admin_dengan_kredensial_valid()
    {
        // Buat admin dengan password yang diketahui
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post(route('login.process'), [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ]);

        // Pastikan redirect ke admin home
        $response->assertRedirect(route('admin.home'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    /**
     * Pf-02: Menguji login admin dengan email tidak valid
     */
    public function test_login_admin_dengan_email_tidak_valid()
    {
        Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post(route('login.process'), [
            'email' => 'wrong@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertGuest('admin');
    }

    /**
     * Pf-03: Menguji login admin dengan password salah
     */
    public function test_login_admin_dengan_password_salah()
    {
        Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post(route('login.process'), [
            'email' => 'admin@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertGuest('admin');
    }

    /**
     * Pf-04: Menguji login admin dengan field email kosong
     */
    public function test_login_admin_dengan_field_email_kosong()
    {
        Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123')
        ]);

        try {
            $response = $this->withoutExceptionHandling()
                ->post(route('login.process'), [
                    'email' => '',
                    'password' => 'password123'
                ]);

            // Jika tidak ada exception, berarti validasi tidak berjalan
            $this->fail('Expected validation exception was not thrown');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validasi berjalan dengan baik
            $this->assertArrayHasKey('email', $e->errors());
            $this->assertGuest('admin');
        }
    }

    /**
     * Pf-05: Menguji login admin dengan field password kosong
     */
    public function test_login_admin_dengan_field_password_kosong()
    {
        Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123')
        ]);

        try {
            $response = $this->withoutExceptionHandling()
                ->post(route('login.process'), [
                    'email' => 'admin@example.com',
                    'password' => ''
                ]);

            $this->fail('Expected validation exception was not thrown');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('password', $e->errors());
            $this->assertGuest('admin');
        }
    }

    /**
     * Pf-06: Menguji validasi format email harus valid
     */
    public function test_validasi_format_email_harus_valid()
    {
        Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123')
        ]);

        try {
            $response = $this->withoutExceptionHandling()
                ->post(route('login.process'), [
                    'email' => 'invalid-email-format',
                    'password' => 'password123'
                ]);

            $this->fail('Expected validation exception was not thrown');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
            $this->assertGuest('admin');
        }
    }


    /**
     * Pf-07: Menguji regenerasi session setelah login berhasil
     */
    public function test_regenerasi_session_setelah_login_berhasil()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123')
        ]);

        // Simpan session ID sebelum login
        $this->startSession();
        $oldSessionId = Session::getId();

        $response = $this->post(route('login.process'), [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ]);

        // Session ID harus berubah untuk keamanan
        $newSessionId = Session::getId();
        $this->assertNotEquals($oldSessionId, $newSessionId);
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    /**
     * Pf-08: Menguji redirect ke halaman admin setelah login berhasil
     */
    public function test_redirect_ke_halaman_admin_setelah_login_berhasil()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post(route('login.process'), [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('admin.home'));
        $response->assertSessionDoesntHaveErrors();
    }

    /**
     * Pf-09: Menguji pesan error "Email atau password salah!" saat gagal login
     */
    public function test_pesan_error_saat_gagal_login()
    {
        Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post(route('login.process'), [
            'email' => 'admin@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /**
     * Pf-16: Menguji logout admin dengan invalidasi session
     */
    public function test_logout_admin_dengan_invalidasi_session()
    {
        $admin = Admin::factory()->create();

        // Login terlebih dahulu
        $this->actingAs($admin, 'admin');
        $this->assertAuthenticated('admin');

        // Logout
        $response = $this->post(route('logout'));

        // Pastikan session di-invalidate
        $this->assertGuest('admin');
    }

    /**
     * Pf-18: Menguji redirect ke halaman login setelah logout
     */
    public function test_redirect_ke_login_setelah_logout()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Pf-21: Menguji guest middleware redirect user yang sudah login
     */
    public function test_guest_middleware_redirect_user_sudah_login()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        // Coba akses halaman login saat sudah login
        $response = $this->get(route('login'));

        // Harus redirect (admin redirect ke user.mental-health berdasarkan middleware)
        $response->assertRedirect();
    }

    /**
     * Pf-22: Menguji AdminAuth middleware untuk route admin
     */
    public function test_admin_auth_middleware_untuk_route_admin()
    {
        // Akses route admin tanpa login
        $response = $this->get(route('admin.home'));

        // Harus redirect ke login
        $response->assertRedirect(route('login'));
        $this->assertGuest('admin');
    }
}
