<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DataDiriSeeder;
use Database\Seeders\HasilKuesionerSeeder;
use PHPUnit\Framework\Attributes\Test;

class AdminHomeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_view_admin_page_with_seeded_data(): void
    {
        // Jalankan hanya seeder utama
        $this->seed(HasilKuesionerSeeder::class);

        $admin = Admin::create([
            'username' => 'adminuser',
            'email' => 'admin@email.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get('/admin');
        $response->assertStatus(200);

        // Cek jumlah minimal
        $this->assertGreaterThanOrEqual(10, DataDiris::count());
        $this->assertGreaterThanOrEqual(10, HasilKuesioner::count());

        $hasil = HasilKuesioner::first();
        $dataDiri = DataDiris::where('nim', $hasil->nim)->first();

        // Pastikan data muncul di halaman
        $response->assertSee($dataDiri->nama);
        $response->assertSee($dataDiri->program_studi);
        $response->assertSee($hasil->kategori);
    }

    #[Test]
    public function admin_page_shows_empty_message_when_no_data(): void
    {
        // Buat admin dummy
        $admin = Admin::create([
            'username' => 'adminuser',
            'email' => 'admin@email.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Belum ada data hasil kuesioner.');
    }
}
