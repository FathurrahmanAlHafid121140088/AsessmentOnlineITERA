<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use App\Models\Users;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;

class HasilKuesionerControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * User instance untuk testing
     *
     * @var Users
     */
    protected Users $user;

    /**
     * Setup method yang dijalankan sebelum setiap test
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Setup session jika diperlukan
        Session::start();

        // Buat user dummy untuk autentikasi
        $this->user = Users::factory()->create([
            'email' => 'test@example.com',
            'nim' => '123456789'
        ]);

        // Login sebagai user
        $this->actingAs($this->user);
    }

    /**
     * Helper method untuk membuat DataDiris
     */
    protected function createDataDiri($nim, $overrides = [])
    {
        return DataDiris::factory()->create(array_merge([
            'nim' => $nim,
            'nama' => 'Test User',
            'email' => "test{$nim}@example.com"
        ], $overrides));
    }

    /**
     * SKENARIO 1: Validasi NIM Wajib Diisi
     * Memastikan form tidak bisa disubmit tanpa NIM
     */
    public function test_validasi_nim_wajib_diisi()
    {
        $response = $this->post(route('mental-health.kuesioner.submit'), [
            'question1' => 5,
            'question2' => 4,
            // nim tidak diisi
        ]);

        $response->assertSessionHasErrors(['nim']);
    }

    /**
     * SKENARIO 2: Simpan Kuesioner dengan Kategori "Sangat Sehat"
     * Total skor: 190-226
     */
    public function test_simpan_kuesioner_kategori_sangat_sehat()
    {
        // Buat data diri terlebih dahulu untuk foreign key
        DataDiris::factory()->create([
            'nim' => '123456789',
            'nama' => 'Test User',
            'email' => 'test@example.com'
        ]);

        $data = [
            'nim' => '123456789',
        ];

        // Simulasi 38 pertanyaan dengan nilai maksimal (6)
        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = 6;
        }
        // Total: 38 * 6 = 228, tapi kita buat 190 untuk test minimum
        for ($i = 1; $i <= 32; $i++) {
            $data["question{$i}"] = 6;
        }
        for ($i = 33; $i <= 38; $i++) {
            $data["question{$i}"] = 5; // Adjust agar total = 192 + 30 = 222
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        $this->assertDatabaseHas('hasil_kuesioners', [
            'nim' => '123456789',
            'kategori' => 'Sangat Sehat',
        ]);

        $response->assertRedirect(route('mental-health.hasil'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('nim', '123456789');
    }

    /**
     * SKENARIO 3: Simpan Kuesioner dengan Kategori "Sehat"
     * Total skor: 152-189
     */
    public function test_simpan_kuesioner_kategori_sehat()
    {
        $this->createDataDiri('987654321');

        $data = ['nim' => '987654321'];

        // Buat total skor 170 (dalam range 152-189)
        for ($i = 1; $i <= 34; $i++) {
            $data["question{$i}"] = 5; // 34 * 5 = 170
        }
        for ($i = 35; $i <= 38; $i++) {
            $data["question{$i}"] = 0;
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        $hasil = HasilKuesioner::where('nim', '987654321')->first();

        $this->assertEquals(170, $hasil->total_skor);
        $this->assertEquals('Sehat', $hasil->kategori);
        $response->assertRedirect(route('mental-health.hasil'));
    }

    /**
     * SKENARIO 4: Simpan Kuesioner dengan Kategori "Cukup Sehat"
     * Total skor: 114-151
     */
    public function test_simpan_kuesioner_kategori_cukup_sehat()
    {
        $this->createDataDiri('111222333');

        $data = ['nim' => '111222333'];

        // Buat total skor 130 (dalam range 114-151)
        for ($i = 1; $i <= 26; $i++) {
            $data["question{$i}"] = 5; // 26 * 5 = 130
        }
        for ($i = 27; $i <= 38; $i++) {
            $data["question{$i}"] = 0;
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        $this->assertDatabaseHas('hasil_kuesioners', [
            'nim' => '111222333',
            'total_skor' => 130,
            'kategori' => 'Cukup Sehat',
        ]);
    }

    /**
     * SKENARIO 5: Simpan Kuesioner dengan Kategori "Perlu Dukungan"
     * Total skor: 76-113
     */
    public function test_simpan_kuesioner_kategori_perlu_dukungan()
    {
        $this->createDataDiri('444555666');

        $data = ['nim' => '444555666'];

        // Buat total skor 95 (dalam range 76-113)
        for ($i = 1; $i <= 19; $i++) {
            $data["question{$i}"] = 5; // 19 * 5 = 95
        }
        for ($i = 20; $i <= 38; $i++) {
            $data["question{$i}"] = 0;
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        $this->assertDatabaseHas('hasil_kuesioners', [
            'nim' => '444555666',
            'total_skor' => 95,
            'kategori' => 'Perlu Dukungan',
        ]);
    }

    /**
     * SKENARIO 6: Simpan Kuesioner dengan Kategori "Perlu Dukungan Intensif"
     * Total skor: 38-75
     */
    public function test_simpan_kuesioner_kategori_perlu_dukungan_intensif()
    {
        $this->createDataDiri('777888999');

        $data = ['nim' => '777888999'];

        // Buat total skor 57 (dalam range 38-75)
        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = ($i <= 19) ? 3 : 0; // 19 * 3 = 57
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        $this->assertDatabaseHas('hasil_kuesioners', [
            'nim' => '777888999',
            'total_skor' => 57,
            'kategori' => 'Perlu Dukungan Intensif',
        ]);
    }

    /**
     * SKENARIO 7: Simpan Kuesioner dengan Kategori "Tidak Terdefinisi"
     * Total skor di luar range yang ditentukan (skor = 0)
     */
    public function test_simpan_kuesioner_kategori_tidak_terdefinisi()
    {
        $this->createDataDiri('000111222');

        $data = ['nim' => '000111222'];

        // Buat total skor 0 (di luar range)
        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = 0;
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        $this->assertDatabaseHas('hasil_kuesioners', [
            'nim' => '000111222',
            'total_skor' => 0,
            'kategori' => 'Tidak Terdefinisi',
        ]);
    }

    /**
     * SKENARIO 8: NIM Tersimpan di Session Setelah Submit
     * Memastikan NIM tersimpan di session untuk digunakan pada halaman hasil
     */
    public function test_nim_tersimpan_di_session()
    {
        $this->createDataDiri('555666777');

        $data = ['nim' => '555666777'];
        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = 5;
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        $this->assertEquals('555666777', session('nim'));
    }

    /**
     * SKENARIO 9: Tampilkan Hasil Kuesioner dengan NIM di Session
     * Menampilkan hasil terbaru ketika NIM tersedia di session
     */
    public function test_tampilkan_hasil_dengan_nim_di_session()
    {
        // Buat data diri terlebih dahulu
        $this->createDataDiri('123456789');

        // Buat data hasil kuesioner
        HasilKuesioner::create([
            'nim' => '123456789',
            'total_skor' => 180,
            'kategori' => 'Sehat',
        ]);

        // Set session
        session([
            'nim' => '123456789',
            'nama' => 'John Doe',
            'program_studi' => 'Teknik Informatika'
        ]);

        $response = $this->get(route('mental-health.hasil'));

        $response->assertStatus(200);
        $response->assertViewIs('hasil');
        $response->assertViewHas('hasil');
        $response->assertViewHas('nama', 'John Doe');
        $response->assertViewHas('program_studi', 'Teknik Informatika');
    }

    /**
     * SKENARIO 10: Redirect Jika NIM Tidak Ada di Session
     * User diarahkan kembali ke halaman kuesioner jika belum mengisi
     */
    public function test_redirect_jika_nim_tidak_ada_di_session()
    {
        $response = $this->get(route('mental-health.hasil'));

        $response->assertRedirect(route('mental-health.kuesioner'));
        $response->assertSessionHas('error', 'NIM tidak ditemukan di sesi.');
    }

    /**
     * SKENARIO 11: Redirect Jika Data Hasil Tidak Ditemukan
     * User diarahkan kembali jika belum ada data hasil kuesioner
     */
    public function test_redirect_jika_data_hasil_tidak_ditemukan()
    {
        session(['nim' => '999999999']);

        $response = $this->get(route('mental-health.hasil'));

        $response->assertRedirect(route('mental-health.kuesioner'));
        $response->assertSessionHas('error', 'Data hasil kuesioner tidak ditemukan.');
    }

    /**
     * SKENARIO 12: Menampilkan Data Hasil Terbaru
     * Memastikan sistem menampilkan hasil kuesioner paling baru jika ada beberapa data
     */
    public function test_menampilkan_data_hasil_terbaru()
    {
        // Buat data diri terlebih dahulu
        $this->createDataDiri('123456789');

        // Buat beberapa hasil kuesioner untuk NIM yang sama
        HasilKuesioner::create([
            'nim' => '123456789',
            'total_skor' => 150,
            'kategori' => 'Sehat',
            'created_at' => now()->subDays(2),
        ]);

        $latestHasil = HasilKuesioner::create([
            'nim' => '123456789',
            'total_skor' => 180,
            'kategori' => 'Sangat Sehat',
            'created_at' => now(),
        ]);

        session(['nim' => '123456789']);

        $response = $this->get(route('mental-health.hasil'));

        $response->assertStatus(200);
        $response->assertViewHas('hasil', function ($hasil) use ($latestHasil) {
            return $hasil->id === $latestHasil->id
                && $hasil->total_skor === 180;
        });
    }

    /**
     * SKENARIO 13: Uji Batas Minimal Skor Setiap Kategori
     * Memastikan sistem mengenali batas minimum skor untuk setiap kategori
     */
    public function test_batas_minimal_skor_kategori()
    {
        $boundaries = [
            ['skor' => 190, 'kategori' => 'Sangat Sehat', 'nim' => '1001001001'],
            ['skor' => 152, 'kategori' => 'Sehat', 'nim' => '1001001002'],
            ['skor' => 114, 'kategori' => 'Cukup Sehat', 'nim' => '1001001003'],
            ['skor' => 76, 'kategori' => 'Perlu Dukungan', 'nim' => '1001001004'],
            ['skor' => 38, 'kategori' => 'Perlu Dukungan Intensif', 'nim' => '1001001005'],
        ];

        foreach ($boundaries as $boundary) {
            $nim = $boundary['nim'];
            $this->createDataDiri($nim);

            $data = ['nim' => $nim];

            $remaining = $boundary['skor'];
            for ($i = 1; $i <= 38; $i++) {
                if ($remaining >= 6) {
                    $data["question{$i}"] = 6;
                    $remaining -= 6;
                } else {
                    $data["question{$i}"] = $remaining;
                    $remaining = 0;
                }
            }

            $this->post(route('mental-health.kuesioner.submit'), $data);

            $this->assertDatabaseHas('hasil_kuesioners', [
                'nim' => $nim,
                'kategori' => $boundary['kategori'],
            ]);
        }
    }

    /**
     * SKENARIO 14: Uji Batas Maksimal Skor Setiap Kategori
     * Memastikan sistem mengenali batas maksimum skor untuk setiap kategori
     */
    public function test_batas_maksimal_skor_kategori()
    {
        $boundaries = [
            ['skor' => 226, 'kategori' => 'Sangat Sehat', 'nim' => '2001001001'],
            ['skor' => 189, 'kategori' => 'Sehat', 'nim' => '2001001002'],
            ['skor' => 151, 'kategori' => 'Cukup Sehat', 'nim' => '2001001003'],
            ['skor' => 113, 'kategori' => 'Perlu Dukungan', 'nim' => '2001001004'],
            ['skor' => 75, 'kategori' => 'Perlu Dukungan Intensif', 'nim' => '2001001005'],
        ];

        foreach ($boundaries as $boundary) {
            $nim = $boundary['nim'];
            $this->createDataDiri($nim);

            $data = ['nim' => $nim];

            $remaining = $boundary['skor'];
            for ($i = 1; $i <= 38; $i++) {
                if ($remaining >= 6) {
                    $data["question{$i}"] = 6;
                    $remaining -= 6;
                } else {
                    $data["question{$i}"] = $remaining;
                    $remaining = 0;
                }
            }

            $this->post(route('mental-health.kuesioner.submit'), $data);

            $this->assertDatabaseHas('hasil_kuesioners', [
                'nim' => $nim,
                'kategori' => $boundary['kategori'],
            ]);
        }
    }

    /**
     * SKENARIO 15: Konversi Input String ke Integer
     * Memastikan input jawaban (string) dikonversi ke integer dengan benar
     */
    public function test_konversi_input_string_ke_integer()
    {
        $this->createDataDiri('123456789');

        $data = ['nim' => '123456789'];

        // Input sebagai string
        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = "5"; // String, bukan integer
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        $hasil = HasilKuesioner::where('nim', '123456789')->first();

        $this->assertEquals(190, $hasil->total_skor);
        $this->assertIsInt($hasil->total_skor);
    }

    /**
     * SKENARIO 16: Submit Multiple Kuesioner untuk NIM yang Sama
     * Memastikan user bisa mengisi kuesioner berulang kali dan data tersimpan
     */
    public function test_submit_multiple_kuesioner_nim_sama()
    {
        $this->createDataDiri('123456789');

        // Submit pertama
        $data1 = ['nim' => '123456789'];
        for ($i = 1; $i <= 38; $i++) {
            $data1["question{$i}"] = 5;
        }
        $this->post(route('mental-health.kuesioner.submit'), $data1);

        // Submit kedua (beberapa waktu kemudian)
        $data2 = ['nim' => '123456789'];
        for ($i = 1; $i <= 38; $i++) {
            $data2["question{$i}"] = 4;
        }
        $this->post(route('mental-health.kuesioner.submit'), $data2);

        // Pastikan ada 2 record di database
        $jumlah = HasilKuesioner::where('nim', '123456789')->count();
        $this->assertEquals(2, $jumlah);
    }

    /**
     * SKENARIO 17: Test Skor Dengan Variasi Jawaban
     * Memastikan perhitungan skor dengan berbagai nilai jawaban
     */
    public function test_skor_dengan_variasi_jawaban()
    {
        $this->createDataDiri('123456789');

        $data = ['nim' => '123456789'];

        // 10 pertanyaan nilai 6, 10 pertanyaan nilai 3, sisanya nilai 0
        for ($i = 1; $i <= 10; $i++) {
            $data["question{$i}"] = 6;
        }
        for ($i = 11; $i <= 20; $i++) {
            $data["question{$i}"] = 3;
        }
        for ($i = 21; $i <= 38; $i++) {
            $data["question{$i}"] = 0;
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        $hasil = HasilKuesioner::where('nim', '123456789')->first();

        // Total: 10*6 + 10*3 = 60 + 30 = 90
        $this->assertEquals(90, $hasil->total_skor);
        $this->assertNotEquals('Tidak Terdefinisi', $hasil->kategori);
    }

    /**
     * SKENARIO 18: Test NIM Session Tersimpan Setelah Submit
     * Memastikan NIM tersimpan di session untuk redirect ke halaman hasil
     */
    public function test_nim_session_tersimpan_setelah_submit()
    {
        $this->createDataDiri('123456789');

        $data = ['nim' => '123456789'];
        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = 5;
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        // Cek session NIM tersimpan
        $this->assertEquals('123456789', session('nim'));
    }

    /**
     * SKENARIO 19: Test Redirect Setelah Submit Berhasil
     * Memastikan user dialihkan ke halaman hasil setelah submit
     */
    public function test_redirect_setelah_submit_berhasil()
    {
        $this->createDataDiri('123456789');

        $data = ['nim' => '123456789'];
        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = 5;
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        $response->assertRedirect(route('mental-health.hasil'));
        $response->assertSessionHas('success');
    }
}