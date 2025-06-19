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
        // Seed data_diri dan hasil_kuesioner
        $this->seed(DataDiriSeeder::class);
        $this->seed(HasilKuesionerSeeder::class);

        // Buat admin dummy
        $admin = Admin::create([
            'username' => 'adminuser',
            'email' => 'admin@email.com',
            'password' => bcrypt('password'),
        ]);

        // Login sebagai admin dengan guard 'admin'
        $this->actingAs($admin, 'admin');

        // Hit ke halaman admin (/admin)
        $response = $this->get('/admin');

        $response->assertStatus(200);

        // Cek minimal 10 data hasil kuesioner
        $this->assertDatabaseCount('data_diris', 10);
        $this->assertDatabaseCount('hasil_kuesioners', 10);

        // Ambil 1 data hasil kuesioner
        $hasil = HasilKuesioner::first();
        $dataDiri = DataDiris::where('nim', $hasil->nim)->first();

        // Cek apakah halaman menampilkan data dari hasil seeder
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
