<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Users;
// use App\Models\Admin; // Hapus atau sesuaikan jika Anda menggunakan model Admin
use App\Models\DataDiris;
use App\Models\HasilKuesioner;
use App\Models\RiwayatKeluhans;
use App\Exports\HasilKuesionerExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

/**
 * @mixin \Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication
 */
class HasilKuesionerCombinedControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin; // Ubah nama variabel jika lebih sesuai

    /**
     * Setup environment untuk setiap tes.
     * Membuat pengguna (admin) dan login.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Buat pengguna biasa saja, karena kolom 'is_admin' tidak ada.
        $this->admin = Users::factory()->create();
    }

    // ================================================
    // TES UNTUK METHOD index() (Dashboard Admin)
    // ================================================

    #[Test]
    public function index_pengguna_belum_login_dialihkan_ke_login(): void
    {
        $response = $this->get(route('admin.home'));
        $response->assertStatus(302);
        $response->assertRedirect('/login'); // Pastikan ini route login admin yang benar
    }

    #[Test]
    public function index_admin_login_data_kosong_berhasil_dimuat(): void
    {
        // === LOGIN SEBAGAI ADMIN MENGGUNAKAN GUARD 'admin' ===
        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('admin.home'));

        $response->assertStatus(200);
        $response->assertViewIs('admin-home');
        $response->assertViewHas('hasilKuesioners', fn($paginator) => $paginator->total() === 0);
        $response->assertViewHas('totalUsers', 0);
        $response->assertViewHas('totalTes', 0);
    }

    #[Test]
    public function index_hanya_menampilkan_hasil_tes_terakhir_per_mahasiswa(): void
    {
        // Data Mahasiswa 1
        $mhs1 = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111']);
        HasilKuesioner::factory()->create(['nim' => '111', 'created_at' => now()->subDays(2)]); // Tes lama
        $tesTerakhirMhs1 = HasilKuesioner::factory()->create(['nim' => '111', 'created_at' => now()->subDay()]); // Tes terakhir

        // Data Mahasiswa 2
        $mhs2 = Users::factory()->create(['nim' => '222']);
        DataDiris::factory()->create(['nim' => '222']);
        $tesTerakhirMhs2 = HasilKuesioner::factory()->create(['nim' => '222', 'created_at' => now()]); // Tes terakhir

        // === LOGIN SEBAGAI ADMIN MENGGUNAKAN GUARD 'admin' ===
        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('admin.home'));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) use ($tesTerakhirMhs1, $tesTerakhirMhs2) {
            $this->assertEquals(2, $paginator->total(), "Harusnya hanya ada 2 hasil tes terakhir.");
            $idsTampil = $paginator->pluck('id')->all();
            $this->assertContains($tesTerakhirMhs1->id, $idsTampil, "Tes terakhir mhs1 tidak ditemukan.");
            $this->assertContains($tesTerakhirMhs2->id, $idsTampil, "Tes terakhir mhs2 tidak ditemukan.");
            return true;
        });
    }

    #[Test]
    public function index_paginasi_berfungsi_sesuai_limit(): void
    {
        // Buat 12 user dengan data diri dan hasil kuesioner
        Users::factory(12)->create()->each(function ($user) {
            DataDiris::factory()->create(['nim' => $user->nim]);
            HasilKuesioner::factory()->create(['nim' => $user->nim]);
        });

        // === LOGIN SEBAGAI ADMIN MENGGUNAKAN GUARD 'admin' ===
        $this->actingAs($this->admin, 'admin');
        // Minta halaman dengan limit 5 item
        $response = $this->get(route('admin.home', ['limit' => 5]));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertCount(5, $paginator->items(), "Harusnya ada 5 item per halaman.");
            $this->assertEquals(12, $paginator->total(), "Total item harusnya 12.");
            return true;
        });
    }

    #[Test]
    public function index_filter_kategori_berfungsi(): void
    {
        // Data Mahasiswa 1 - Kategori Sehat
        $mhs1 = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111']);
        HasilKuesioner::factory()->create(['nim' => '111', 'kategori' => 'Sehat']);

        // Data Mahasiswa 2 - Kategori Cukup Sehat
        $mhs2 = Users::factory()->create(['nim' => '222']);
        DataDiris::factory()->create(['nim' => '222']);
        HasilKuesioner::factory()->create(['nim' => '222', 'kategori' => 'Cukup Sehat']);

        // === LOGIN SEBAGAI ADMIN MENGGUNAKAN GUARD 'admin' ===
        $this->actingAs($this->admin, 'admin');
        // Filter berdasarkan kategori 'Sehat'
        $response = $this->get(route('admin.home', ['kategori' => 'Sehat']));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertEquals(1, $paginator->total(), "Harusnya hanya 1 hasil dengan kategori Sehat.");
            $this->assertEquals('Sehat', $paginator->items()[0]->kategori, "Kategori yang ditampilkan harusnya Sehat.");
            return true;
        });
    }

    #[Test]
    public function index_sort_berdasarkan_nama_asc_berfungsi(): void
    {
        // Data Mahasiswa Budi
        $mhsB = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111', 'nama' => 'Budi']);
        HasilKuesioner::factory()->create(['nim' => '111']);

        // Data Mahasiswa Andi
        $mhsA = Users::factory()->create(['nim' => '222']);
        DataDiris::factory()->create(['nim' => '222', 'nama' => 'Andi']);
        HasilKuesioner::factory()->create(['nim' => '222']);

        // === LOGIN SEBAGAI ADMIN MENGGUNAKAN GUARD 'admin' ===
        $this->actingAs($this->admin, 'admin');
        // Sort berdasarkan nama ascending
        $response = $this->get(route('admin.home', ['sort' => 'nama', 'order' => 'asc']));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertEquals('Andi', $paginator->items()[0]->nama_mahasiswa, "Urutan pertama harusnya Andi.");
            $this->assertEquals('Budi', $paginator->items()[1]->nama_mahasiswa, "Urutan kedua harusnya Budi.");
            return true;
        });
    }

    #[Test]
    public function index_pencarian_berdasarkan_nama_berfungsi(): void
    {
        // Data Mahasiswa 1
        $mhs1 = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111', 'nama' => 'Cari Nama Ini']);
        HasilKuesioner::factory()->create(['nim' => '111']);

        // Data Mahasiswa 2
        $mhs2 = Users::factory()->create(['nim' => '222']);
        DataDiris::factory()->create(['nim' => '222', 'nama' => 'Nama Lain']);
        HasilKuesioner::factory()->create(['nim' => '222']);

        // === LOGIN SEBAGAI ADMIN MENGGUNAKAN GUARD 'admin' ===
        $this->actingAs($this->admin, 'admin');
        // Cari berdasarkan sebagian nama
        $response = $this->get(route('admin.home', ['search' => 'Cari Nama']));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertEquals(1, $paginator->total(), "Harusnya hanya 1 hasil ditemukan.");
            $this->assertEquals('Cari Nama Ini', $paginator->items()[0]->nama_mahasiswa, "Nama yang ditemukan harusnya 'Cari Nama Ini'.");
            return true;
        });
        // $response->assertSee('Data berhasil ditemukan!'); // Uncomment jika ingin cek pesan
    }

    #[Test]
    public function index_pencarian_berdasarkan_aturan_khusus_fakultas_berfungsi(): void
    {
        // Data Mahasiswa FTI
        $mhs1 = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111', 'nama' => 'Mahasiswa FTI', 'fakultas' => 'FTI']);
        HasilKuesioner::factory()->create(['nim' => '111']);

        // Data Mahasiswa FS
        $mhs2 = Users::factory()->create(['nim' => '222']);
        DataDiris::factory()->create(['nim' => '222', 'nama' => 'Mahasiswa FS', 'fakultas' => 'FS']);
        HasilKuesioner::factory()->create(['nim' => '222']);

        // === LOGIN SEBAGAI ADMIN MENGGUNAKAN GUARD 'admin' ===
        $this->actingAs($this->admin, 'admin');
        // Cari 'fti' (lowercase)
        $response = $this->get(route('admin.home', ['search' => 'fti']));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertEquals(1, $paginator->total(), "Harusnya hanya 1 hasil FTI ditemukan.");
            $this->assertEquals('Mahasiswa FTI', $paginator->items()[0]->nama_mahasiswa, "Nama yang ditemukan harusnya Mahasiswa FTI.");
            return true;
        });
    }

    #[Test]
    public function index_pencarian_tidak_ditemukan_menampilkan_hasil_kosong(): void
    {
        // === LOGIN SEBAGAI ADMIN MENGGUNAKAN GUARD 'admin' ===
        $this->actingAs($this->admin, 'admin');
        // Cari kata kunci yang pasti tidak ada
        $response = $this->get(route('admin.home', ['search' => 'TidakAdaDataIniPasti']));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', fn($paginator) => $paginator->total() === 0);
        // $response->assertSee('Data tidak ditemukan!'); // Uncomment jika ingin cek pesan
    }

    #[Test]
    public function index_statistik_dihitung_dengan_benar(): void
    {
        // Data User Laki-laki, SMA, FTI, 2 Tes (Sehat, Sangat Sehat)
        $userL = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111', 'jenis_kelamin' => 'L', 'asal_sekolah' => 'SMA', 'fakultas' => 'FTI']);
        HasilKuesioner::factory()->create(['nim' => '111', 'kategori' => 'Sehat', 'created_at' => now()->subDay()]);
        HasilKuesioner::factory()->create(['nim' => '111', 'kategori' => 'Sangat Sehat', 'created_at' => now()]); // Tes terakhir

        // Data User Perempuan, SMK, FS, 1 Tes (Cukup Sehat)
        $userP = Users::factory()->create(['nim' => '222']);
        DataDiris::factory()->create(['nim' => '222', 'jenis_kelamin' => 'P', 'asal_sekolah' => 'SMK', 'fakultas' => 'FS']);
        HasilKuesioner::factory()->create(['nim' => '222', 'kategori' => 'Cukup Sehat', 'created_at' => now()]); // Tes terakhir

        // === LOGIN SEBAGAI ADMIN MENGGUNAKAN GUARD 'admin' ===
        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('admin.home'));

        $response->assertStatus(200);
        // Cek statistik dasar
        $response->assertViewHas('totalUsers', 2);
        $response->assertViewHas('totalTes', 3); // Total semua tes
        $response->assertViewHas('totalLaki', 1);
        $response->assertViewHas('totalPerempuan', 1);
        // Cek statistik asal sekolah
        $response->assertViewHas('asalCounts', ['SMA' => 1, 'SMK' => 1, 'Boarding School' => 0]);
        // Cek statistik kategori (berdasarkan tes TERAKHIR)
        $response->assertViewHas('kategoriCounts', ['Sangat Sehat' => 1, 'Cukup Sehat' => 1]);
        // Cek statistik fakultas
        $response->assertViewHas('fakultasCount', ['FTI' => 1, 'FS' => 1]);
    }

    // ================================================
    // TES UNTUK METHOD destroy() (Hapus Data)
    // ================================================

    #[Test]
    public function destroy_pengguna_belum_login_dialihkan_ke_login(): void
    {
        // Akses route hapus tanpa login
        $response = $this->delete(route('admin.delete', ['id' => 1]));
        // Harusnya dialihkan ke login
        $response->assertStatus(302);
        $response->assertRedirect('/login'); // Pastikan ini route login admin yang benar
    }

    #[Test]
    public function destroy_data_tidak_ditemukan_redirect_dengan_error(): void
    {
        // === LOGIN SEBAGAI ADMIN MENGGUNAKAN GUARD 'admin' ===
        $this->actingAs($this->admin, 'admin');
        // Coba hapus ID yang tidak ada
        $response = $this->delete(route('admin.delete', ['id' => 999]));

        // Harusnya redirect kembali ke admin home dengan pesan error
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.home'));
        $response->assertSessionHas('error', 'Data tidak ditemukan.');
    }

    #[Test]
    public function destroy_data_berhasil_dihapus(): void
    {
        // Buat data lengkap untuk satu user
        $user = Users::factory()->create(['nim' => '111']);
        $dataDiri = DataDiris::factory()->create(['nim' => '111']);
        $hasil = HasilKuesioner::factory()->create(['nim' => '111']);
        $riwayat = RiwayatKeluhans::factory()->create(['nim' => '111']);

        // === LOGIN SEBAGAI ADMIN MENGGUNAKAN GUARD 'admin' ===
        $this->actingAs($this->admin, 'admin');
        // Hapus berdasarkan ID hasil kuesioner
        $response = $this->delete(route('admin.delete', ['id' => $hasil->id]));

        // Harusnya redirect ke admin home dengan pesan sukses
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.home'));
        $response->assertSessionHas('success', 'Seluruh data mahasiswa dengan NIM 111 berhasil dihapus.');

        // Pastikan SEMUA data terkait user tersebut hilang dari database
        $this->assertDatabaseMissing('users', ['nim' => '111']);
        $this->assertDatabaseMissing('data_diris', ['nim' => '111']);
        $this->assertDatabaseMissing('hasil_kuesioners', ['nim' => '111']);
        $this->assertDatabaseMissing('riwayat_keluhans', ['nim' => '111']);
    }

    // ================================================
    // TES UNTUK METHOD exportExcel() (Ekspor Data)
    // ================================================

    #[Test]
    public function export_excel_pengguna_belum_login_dialihkan_ke_login(): void
    {
        // Akses route ekspor tanpa login
        $response = $this->get(route('admin.export.excel'));
        // Harusnya dialihkan ke login
        $response->assertStatus(302);
        $response->assertRedirect('/login'); // Pastikan ini route login admin yang benar
    }

    #[Test]
    public function export_excel_dipicu_dengan_benar(): void
    {
        // Kita tidak perlu Excel::fake() jika hanya cek header
        // Excel::fake();

        // === LOGIN SEBAGAI ADMIN MENGGUNAKAN GUARD 'admin' ===
        $this->actingAs($this->admin, 'admin');

        // Akses route ekspor dengan beberapa parameter
        $response = $this->get(route('admin.export.excel', [
            'search' => 'test',
            'kategori' => 'Sehat',
            'sort' => 'nama',
            'order' => 'asc'
        ]));

        $response->assertStatus(200); // Harusnya berhasil (status 200)

        // --- SOLUSI FINAL (SEHARUSNYA): CEK HEADER ---
        // 1. Cek tipe konten
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // 2. Ambil nilai header Content-Disposition
        $contentDispositionHeader = $response->headers->get('Content-Disposition');
        $this->assertNotNull($contentDispositionHeader, "Header Content-Disposition tidak ditemukan.");

        // --- PERBAIKAN DI SINI ---
        // 3. Cek apakah header tersebut mengandung bagian nama file TANPA tanda kutip
        $expectedFileNameStart = 'hasil-kuesioner-' . now()->setTimezone('Asia/Jakarta')->format('Y-m-d_H-i'); // Format tanpa detik, GMT+7
        $this->assertStringContainsString('attachment; filename=' . $expectedFileNameStart, $contentDispositionHeader, "Nama file di header tidak sesuai format (awal)."); // Hapus tanda kutip
        $this->assertStringEndsWith('.xlsx', $contentDispositionHeader, "Nama file di header tidak diakhiri dengan .xlsx."); // Hapus tanda kutip
        // --- AKHIR PERBAIKAN ---

    }

    // ================================================
    // TES TAMBAHAN - KOMBINASI FILTER DAN EDGE CASES
    // ================================================

    #[Test]
    public function index_filter_kombinasi_kategori_dan_search_berfungsi(): void
    {
        // Data Mahasiswa 1 - Sehat, nama mengandung "Budi"
        $mhs1 = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111', 'nama' => 'Budi Santoso']);
        HasilKuesioner::factory()->create(['nim' => '111', 'kategori' => 'Sehat']);

        // Data Mahasiswa 2 - Sehat, nama mengandung "Ahmad"
        $mhs2 = Users::factory()->create(['nim' => '222']);
        DataDiris::factory()->create(['nim' => '222', 'nama' => 'Ahmad']);
        HasilKuesioner::factory()->create(['nim' => '222', 'kategori' => 'Sehat']);

        // Data Mahasiswa 3 - Cukup Sehat, nama mengandung "Budi"
        $mhs3 = Users::factory()->create(['nim' => '333']);
        DataDiris::factory()->create(['nim' => '333', 'nama' => 'Budi Wijaya']);
        HasilKuesioner::factory()->create(['nim' => '333', 'kategori' => 'Cukup Sehat']);

        $this->actingAs($this->admin, 'admin');
        // Filter: Kategori "Sehat" DAN nama mengandung "Budi"
        $response = $this->get(route('admin.home', ['kategori' => 'Sehat', 'search' => 'Budi']));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertEquals(1, $paginator->total(), "Harusnya hanya 1 hasil (Budi Santoso - Sehat).");
            $this->assertEquals('Budi Santoso', $paginator->items()[0]->nama_mahasiswa);
            return true;
        });
    }

    #[Test]
    public function index_sort_berdasarkan_nim_desc_berfungsi(): void
    {
        // Data dengan NIM berbeda
        $mhs1 = Users::factory()->create(['nim' => '123']);
        DataDiris::factory()->create(['nim' => '123', 'nama' => 'User 1']);
        HasilKuesioner::factory()->create(['nim' => '123']);

        $mhs2 = Users::factory()->create(['nim' => '456']);
        DataDiris::factory()->create(['nim' => '456', 'nama' => 'User 2']);
        HasilKuesioner::factory()->create(['nim' => '456']);

        $this->actingAs($this->admin, 'admin');
        // Sort berdasarkan NIM descending
        $response = $this->get(route('admin.home', ['sort' => 'nim', 'order' => 'desc']));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertEquals('456', $paginator->items()[0]->nim, "Urutan pertama harusnya NIM 456.");
            $this->assertEquals('123', $paginator->items()[1]->nim, "Urutan kedua harusnya NIM 123.");
            return true;
        });
    }

    #[Test]
    public function index_sort_berdasarkan_tanggal_desc_berfungsi(): void
    {
        // Data dengan tanggal berbeda
        $mhs1 = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111', 'nama' => 'User Lama']);
        HasilKuesioner::factory()->create(['nim' => '111', 'created_at' => now()->subDays(5)]);

        $mhs2 = Users::factory()->create(['nim' => '222']);
        DataDiris::factory()->create(['nim' => '222', 'nama' => 'User Baru']);
        HasilKuesioner::factory()->create(['nim' => '222', 'created_at' => now()]);

        $this->actingAs($this->admin, 'admin');
        // Sort berdasarkan tanggal descending (terbaru dulu)
        $response = $this->get(route('admin.home', ['sort' => 'created_at', 'order' => 'desc']));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertEquals('User Baru', $paginator->items()[0]->nama_mahasiswa, "Urutan pertama harusnya data terbaru.");
            $this->assertEquals('User Lama', $paginator->items()[1]->nama_mahasiswa, "Urutan kedua harusnya data lama.");
            return true;
        });
    }

    #[Test]
    public function index_paginasi_halaman_kedua_berfungsi(): void
    {
        // Buat 15 user
        Users::factory(15)->create()->each(function ($user) {
            DataDiris::factory()->create(['nim' => $user->nim]);
            HasilKuesioner::factory()->create(['nim' => $user->nim]);
        });

        $this->actingAs($this->admin, 'admin');
        // Minta halaman kedua dengan limit 10
        $response = $this->get(route('admin.home', ['limit' => 10, 'page' => 2]));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertCount(5, $paginator->items(), "Halaman 2 harusnya ada 5 item sisanya.");
            $this->assertEquals(15, $paginator->total(), "Total item harusnya 15.");
            $this->assertEquals(2, $paginator->currentPage(), "Harusnya di halaman 2.");
            return true;
        });
    }

    #[Test]
    public function index_statistik_dengan_semua_kategori_sama(): void
    {
        // Buat 3 user dengan kategori "Sehat" semua
        for ($i = 1; $i <= 3; $i++) {
            $user = Users::factory()->create(['nim' => "11{$i}"]);
            DataDiris::factory()->create(['nim' => "11{$i}"]);
            HasilKuesioner::factory()->create(['nim' => "11{$i}", 'kategori' => 'Sehat']);
        }

        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('admin.home'));

        $response->assertStatus(200);
        $response->assertViewHas('totalUsers', 3);
        $response->assertViewHas('kategoriCounts', function ($counts) {
            return $counts['Sehat'] === 3 &&
                   array_sum(array_diff_key($counts, ['Sehat' => 0])) === 0;
        });
    }

    #[Test]
    public function index_pencarian_case_insensitive_berfungsi(): void
    {
        $mhs1 = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111', 'nama' => 'NAMA UPPERCASE']);
        HasilKuesioner::factory()->create(['nim' => '111']);

        $this->actingAs($this->admin, 'admin');
        // Cari dengan lowercase
        $response = $this->get(route('admin.home', ['search' => 'uppercase']));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertEquals(1, $paginator->total(), "Pencarian case-insensitive harus menemukan 1 hasil.");
            return true;
        });
    }

    #[Test]
    public function index_filter_kategori_tidak_ada_hasil_kosong(): void
    {
        // Buat data dengan kategori "Sehat"
        $mhs1 = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111']);
        HasilKuesioner::factory()->create(['nim' => '111', 'kategori' => 'Sehat']);

        $this->actingAs($this->admin, 'admin');
        // Filter kategori yang tidak ada di database
        $response = $this->get(route('admin.home', ['kategori' => 'Perlu Dukungan Intensif']));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', fn($paginator) => $paginator->total() === 0);
    }

    #[Test]
    public function index_kombinasi_filter_sort_search_sekaligus(): void
    {
        // Data kompleks untuk tes kombinasi
        $mhs1 = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111', 'nama' => 'Andi Sehat']);
        HasilKuesioner::factory()->create(['nim' => '111', 'kategori' => 'Sehat', 'created_at' => now()->subDay()]);

        $mhs2 = Users::factory()->create(['nim' => '222']);
        DataDiris::factory()->create(['nim' => '222', 'nama' => 'Budi Sehat']);
        HasilKuesioner::factory()->create(['nim' => '222', 'kategori' => 'Sehat', 'created_at' => now()]);

        $mhs3 = Users::factory()->create(['nim' => '333']);
        DataDiris::factory()->create(['nim' => '333', 'nama' => 'Citra Cukup']);
        HasilKuesioner::factory()->create(['nim' => '333', 'kategori' => 'Cukup Sehat']);

        $this->actingAs($this->admin, 'admin');
        // Filter kategori "Sehat", search "Sehat", sort by nama asc
        $response = $this->get(route('admin.home', [
            'kategori' => 'Sehat',
            'search' => 'Sehat',
            'sort' => 'nama',
            'order' => 'asc'
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertEquals(2, $paginator->total(), "Harusnya ada 2 hasil (Andi dan Budi).");
            $this->assertEquals('Andi Sehat', $paginator->items()[0]->nama_mahasiswa, "Andi harusnya pertama (A < B).");
            $this->assertEquals('Budi Sehat', $paginator->items()[1]->nama_mahasiswa, "Budi harusnya kedua.");
            return true;
        });
    }

    #[Test]
    public function destroy_hapus_mahasiswa_dengan_multiple_hasil_tes(): void
    {
        // Buat user dengan 3 hasil tes
        $user = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111']);
        $hasil1 = HasilKuesioner::factory()->create(['nim' => '111', 'created_at' => now()->subDays(2)]);
        $hasil2 = HasilKuesioner::factory()->create(['nim' => '111', 'created_at' => now()->subDay()]);
        $hasil3 = HasilKuesioner::factory()->create(['nim' => '111', 'created_at' => now()]);

        $this->actingAs($this->admin, 'admin');
        // Hapus berdasarkan salah satu hasil (yang terakhir)
        $response = $this->delete(route('admin.delete', ['id' => $hasil3->id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.home'));
        $response->assertSessionHas('success');

        // Pastikan SEMUA data mahasiswa tersebut terhapus (termasuk 3 tes)
        $this->assertDatabaseMissing('users', ['nim' => '111']);
        $this->assertDatabaseMissing('hasil_kuesioners', ['nim' => '111']);
        $this->assertEquals(0, HasilKuesioner::where('nim', '111')->count());
    }

    #[Test]
    public function export_excel_dengan_data_kosong(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Ekspor ketika tidak ada data
        $response = $this->get(route('admin.export.excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // File tetap tergenerate meskipun kosong
        $contentDispositionHeader = $response->headers->get('Content-Disposition');
        $this->assertNotNull($contentDispositionHeader);
        $this->assertStringContainsString('hasil-kuesioner-', $contentDispositionHeader);
    }

    #[Test]
    public function export_excel_dengan_filter_kategori(): void
    {
        // Buat data dengan kategori berbeda
        $mhs1 = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111', 'nama' => 'User Sehat']);
        HasilKuesioner::factory()->create(['nim' => '111', 'kategori' => 'Sehat']);

        $mhs2 = Users::factory()->create(['nim' => '222']);
        DataDiris::factory()->create(['nim' => '222', 'nama' => 'User Cukup']);
        HasilKuesioner::factory()->create(['nim' => '222', 'kategori' => 'Cukup Sehat']);

        $this->actingAs($this->admin, 'admin');

        // Ekspor dengan filter kategori "Sehat"
        $response = $this->get(route('admin.export.excel', ['kategori' => 'Sehat']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    #[Test]
    public function index_pencarian_berdasarkan_nim_berfungsi(): void
    {
        $mhs1 = Users::factory()->create(['nim' => '121140088']);
        DataDiris::factory()->create(['nim' => '121140088', 'nama' => 'Mahasiswa 1']);
        HasilKuesioner::factory()->create(['nim' => '121140088']);

        $mhs2 = Users::factory()->create(['nim' => '999999999']);
        DataDiris::factory()->create(['nim' => '999999999', 'nama' => 'Mahasiswa 2']);
        HasilKuesioner::factory()->create(['nim' => '999999999']);

        $this->actingAs($this->admin, 'admin');
        // Cari berdasarkan sebagian NIM
        $response = $this->get(route('admin.home', ['search' => '121140']));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertEquals(1, $paginator->total(), "Harusnya menemukan 1 hasil dengan NIM 121140088.");
            $this->assertEquals('121140088', $paginator->items()[0]->nim);
            return true;
        });
    }

    #[Test]
    public function index_pencarian_berdasarkan_program_studi_berfungsi(): void
    {
        $mhs1 = Users::factory()->create(['nim' => '111']);
        DataDiris::factory()->create(['nim' => '111', 'nama' => 'User TI', 'program_studi' => 'Teknik Informatika']);
        HasilKuesioner::factory()->create(['nim' => '111']);

        $mhs2 = Users::factory()->create(['nim' => '222']);
        DataDiris::factory()->create(['nim' => '222', 'nama' => 'User TE', 'program_studi' => 'Teknik Elektro']);
        HasilKuesioner::factory()->create(['nim' => '222']);

        $this->actingAs($this->admin, 'admin');
        // Cari berdasarkan program studi
        $response = $this->get(route('admin.home', ['search' => 'Informatika']));

        $response->assertStatus(200);
        $response->assertViewHas('hasilKuesioners', function ($paginator) {
            $this->assertEquals(1, $paginator->total(), "Harusnya menemukan 1 hasil dari Teknik Informatika.");
            return true;
        });
    }

    // ================================================
    // TES UNTUK METHOD showDetail() (Detail Jawaban)
    // ================================================

    #[Test]
    public function show_detail_pengguna_belum_login_dialihkan_ke_login(): void
    {
        // Akses route detail tanpa login
        $response = $this->get(route('admin.mental-health.detail', ['id' => 1]));
        // Harusnya dialihkan ke login
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    #[Test]
    public function show_detail_data_tidak_ditemukan_error_404(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Coba akses ID yang tidak ada
        $response = $this->get(route('admin.mental-health.detail', ['id' => 999]));

        $response->assertStatus(404);
    }

    #[Test]
    public function show_detail_berhasil_menampilkan_data_lengkap(): void
    {
        // Buat data lengkap
        $user = Users::factory()->create(['nim' => '121140088']);
        $dataDiri = DataDiris::factory()->create([
            'nim' => '121140088',
            'nama' => 'John Doe',
            'program_studi' => 'Teknik Informatika'
        ]);
        $hasil = HasilKuesioner::factory()->create([
            'nim' => '121140088',
            'total_skor' => 150,
            'kategori' => 'Sehat'
        ]);

        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('admin.mental-health.detail', ['id' => $hasil->id]));

        $response->assertStatus(200);
        $response->assertViewIs('admin-mental-health-detail');

        // Cek data yang dikirim ke view
        $response->assertViewHas('hasil', function ($viewHasil) use ($hasil) {
            return $viewHasil->id === $hasil->id;
        });

        $response->assertViewHas('questions'); // Daftar pertanyaan ada
        $response->assertViewHas('negativeQuestions'); // Daftar pertanyaan negatif ada

        // Cek konten halaman
        $response->assertSee('John Doe');
        $response->assertSee('121140088');
        $response->assertSee('Teknik Informatika');
        $response->assertSee('150'); // Total skor
        $response->assertSee('Sehat'); // Kategori
    }

    #[Test]
    public function show_detail_menampilkan_38_pertanyaan(): void
    {
        $user = Users::factory()->create(['nim' => '111']);
        $dataDiri = DataDiris::factory()->create(['nim' => '111', 'nama' => 'Test User']);
        $hasil = HasilKuesioner::factory()->create(['nim' => '111']);

        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('admin.mental-health.detail', ['id' => $hasil->id]));

        $response->assertStatus(200);
        $response->assertViewHas('questions', function ($questions) {
            $this->assertCount(38, $questions, "Harusnya ada 38 pertanyaan.");
            return true;
        });
    }

    #[Test]
    public function show_detail_pertanyaan_negatif_ditandai_dengan_benar(): void
    {
        $user = Users::factory()->create(['nim' => '111']);
        $dataDiri = DataDiris::factory()->create(['nim' => '111', 'nama' => 'Test User']);
        $hasil = HasilKuesioner::factory()->create(['nim' => '111']);

        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('admin.mental-health.detail', ['id' => $hasil->id]));

        $response->assertStatus(200);
        $response->assertViewHas('negativeQuestions', function ($negatives) {
            // Daftar pertanyaan negatif sesuai MHI-38 asli (Psychological Distress - 24 items)
            $expectedNegatives = [2, 3, 8, 9, 11, 13, 14, 15, 16, 18, 19, 20, 21, 24, 25, 27, 28, 29, 30, 32, 33, 35, 36, 38];
            $this->assertCount(24, $negatives, "Harusnya ada 24 pertanyaan negatif sesuai MHI-38.");
            $this->assertEquals($expectedNegatives, $negatives, "Daftar pertanyaan negatif harus sesuai.");
            return true;
        });
    }

    #[Test]
    public function show_detail_info_mahasiswa_urutan_benar(): void
    {
        // Test urutan: NIM, Nama, Program Studi, Tanggal Tes
        $user = Users::factory()->create(['nim' => '121140088']);
        $dataDiri = DataDiris::factory()->create([
            'nim' => '121140088',
            'nama' => 'Budi Santoso',
            'program_studi' => 'Teknik Elektro'
        ]);
        $hasil = HasilKuesioner::factory()->create([
            'nim' => '121140088',
            'created_at' => now()
        ]);

        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('admin.mental-health.detail', ['id' => $hasil->id]));

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            'NIM',
            '121140088',
            'Nama Mahasiswa',
            'Budi Santoso',
            'Program Studi',
            'Teknik Elektro',
            'Tanggal Tes'
        ]);
    }

    #[Test]
    public function show_detail_tombol_kembali_dan_cetak_ada(): void
    {
        $user = Users::factory()->create(['nim' => '111']);
        $dataDiri = DataDiris::factory()->create(['nim' => '111']);
        $hasil = HasilKuesioner::factory()->create(['nim' => '111']);

        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('admin.mental-health.detail', ['id' => $hasil->id]));

        $response->assertStatus(200);
        $response->assertSee('Kembali');
        $response->assertSee('Cetak PDF');
        $response->assertSee('printDetail()'); // JavaScript function ada
    }

    #[Test]
    public function show_detail_title_mengandung_nama_mahasiswa(): void
    {
        $user = Users::factory()->create(['nim' => '111']);
        $dataDiri = DataDiris::factory()->create(['nim' => '111', 'nama' => 'Ahmad Zaki']);
        $hasil = HasilKuesioner::factory()->create(['nim' => '111']);

        $this->actingAs($this->admin, 'admin');
        $response = $this->get(route('admin.mental-health.detail', ['id' => $hasil->id]));

        $response->assertStatus(200);
        $response->assertViewHas('title', 'Detail Jawaban Kuesioner - Ahmad Zaki');
    }
}

