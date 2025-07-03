<?php

namespace Tests\Feature;

use App\Models\DataDiris;
use App\Models\HasilKuesioner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Middleware\VerifyCsrfToken;

class HasilKuesionerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Menonaktifkan CSRF supaya 419 tidak muncul saat testing.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Laravel 11: pastikan App\Http\Middleware\VerifyCsrfToken sudah ada
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_user_can_submit_kuesioner_and_redirect_to_hasil(): void
    {
        $dataDiri = DataDiris::create([
            'nim' => 123456,
            'nama' => 'Fathurrahman Al Hafid',
            'jenis_kelamin' => 'L',
            'alamat' => 'Bandar Lampung',
            'usia' => 21,
            'fakultas' => 'Teknik',
            'program_studi' => 'Teknik Informatika',
            'email' => 'test@example.com',
        ]);

        $jawaban = collect(range(1, 38))->mapWithKeys(fn($i) => ["question{$i}" => 5])->toArray();
        $jawaban['nim'] = $dataDiri->nim;

        $response = $this->withSession([
            'nim' => $dataDiri->nim,
            'nama' => $dataDiri->nama,
            'program_studi' => $dataDiri->program_studi,
        ])->post(route('mental-health.kuesioner.submit'), $jawaban);

        $response->assertRedirect(route('mental-health.hasil'));

        $this->assertDatabaseHas('hasil_kuesioners', [
            'nim' => $dataDiri->nim,
        ]);
    }

    public function test_user_cannot_submit_kuesioner_if_missing_nim(): void
    {
        $jawaban = collect(range(1, 38))->mapWithKeys(fn($i) => ["question{$i}" => 3])->toArray();

        $response = $this->post(route('mental-health.kuesioner.submit'), $jawaban);

        $response->assertSessionHasErrors(['nim']);
    }

    public function test_hasil_showLatest_returns_view_with_data(): void
    {
        $dataDiri = DataDiris::create([
            'nim' => 654321,
            'nama' => 'Rudi Hartono',
            'jenis_kelamin' => 'L',
            'alamat' => 'Metro',
            'usia' => 23,
            'fakultas' => 'Kedokteran',
            'program_studi' => 'Kedokteran Umum',
            'email' => 'rudi@example.com',
        ]);

        $hasil = HasilKuesioner::create([
            'nim' => $dataDiri->nim,
            'total_skor' => 200,
            'kategori' => 'Sangat Baik (Sejahtera Secara Mental)',
        ]);

        $response = $this->withSession([
            'nim' => $dataDiri->nim,
            'nama' => $dataDiri->nama,
            'program_studi' => $dataDiri->program_studi,
        ])->get(route('mental-health.hasil'));

        $response->assertOk()
            ->assertViewIs('hasil')
            ->assertViewHas('hasil', fn($viewHasil) => $viewHasil->id === $hasil->id);
    }

    public function test_user_gets_sangat_baik_if_all_answers_highest(): void
    {
        $dataDiri = DataDiris::create([
            'nim' => 987654,
            'nama' => 'Tes Skor Tinggi',
            'jenis_kelamin' => 'L',
            'alamat' => 'Lampung',
            'usia' => 22,
            'fakultas' => 'Teknik',
            'program_studi' => 'Teknik Informatika',
            'email' => 'tinggi@example.com',
        ]);

        $jawaban = collect(range(1, 38))->mapWithKeys(fn($i) => ["question{$i}" => 6])->toArray();
        $jawaban['nim'] = $dataDiri->nim;

        $response = $this->withSession([
            'nim' => $dataDiri->nim,
            'nama' => $dataDiri->nama,
            'program_studi' => $dataDiri->program_studi,
        ])->post(route('mental-health.kuesioner.submit'), $jawaban);

        $response->assertRedirect(route('mental-health.hasil'));

        $this->assertDatabaseHas('hasil_kuesioners', [
            'nim' => $dataDiri->nim,
            'kategori' => 'Sangat Baik (Sejahtera Secara Mental)',
        ]);
    }

    public function test_user_gets_sangat_buruk_if_all_answers_lowest(): void
    {
        $dataDiri = DataDiris::create([
            'nim' => 112233,
            'nama' => 'Tes Skor Rendah',
            'jenis_kelamin' => 'P',
            'alamat' => 'Metro',
            'usia' => 20,
            'fakultas' => 'Hukum',
            'program_studi' => 'Ilmu Hukum',
            'email' => 'rendah@example.com',
        ]);

        $jawaban = collect(range(1, 38))->mapWithKeys(fn($i) => ["question{$i}" => 1])->toArray();
        $jawaban['nim'] = $dataDiri->nim;

        $response = $this->withSession([
            'nim' => $dataDiri->nim,
            'nama' => $dataDiri->nama,
            'program_studi' => $dataDiri->program_studi,
        ])->post(route('mental-health.kuesioner.submit'), $jawaban);

        $response->assertRedirect(route('mental-health.hasil'));

        $this->assertDatabaseHas('hasil_kuesioners', [
            'nim' => $dataDiri->nim,
            'kategori' => 'Sangat Buruk (Distres Berat)',
        ]);
    }
}
