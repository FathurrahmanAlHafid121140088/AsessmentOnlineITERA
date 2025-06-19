<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Admin;
use PHPUnit\Framework\Attributes\Test;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_username_is_visible_after_login()
    {
        $admin = Admin::create([
            'username' => 'superadmin',
            'email' => 'admin@email.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin');
        $response->assertSee('superadmin');
    }

    #[Test]
    public function admin_can_logout_successfully()
    {
        $admin = Admin::create([
            'username' => 'superadmin',
            'email' => 'admin@email.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($admin, 'admin');
        $response = $this->post('/logout');
        $response->assertRedirect('/login');
    }

    #[Test]
    public function guest_cannot_access_admin_dashboard()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }
}
