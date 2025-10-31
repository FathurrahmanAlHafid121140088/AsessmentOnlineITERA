<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\DataDiris;
use App\Models\HasilKuesioner;
use App\Models\RiwayatKeluhans;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataDirisTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Model menggunakan primary key 'nim'
     */
    public function test_model_uses_nim_as_primary_key()
    {
        $dataDiri = new DataDiris();

        $this->assertEquals('nim', $dataDiri->getKeyName());
        $this->assertFalse($dataDiri->getIncrementing());
        $this->assertEquals('string', $dataDiri->getKeyType());
    }

    /**
     * Test: Model memiliki fillable attributes yang benar
     */
    public function test_model_has_correct_fillable_attributes()
    {
        $dataDiri = new DataDiris();
        $fillable = $dataDiri->getFillable();

        $expected = [
            'nim',
            'nama',
            'jenis_kelamin',
            'provinsi',
            'alamat',
            'usia',
            'fakultas',
            'program_studi',
            'asal_sekolah',
            'status_tinggal',
            'email'
        ];

        $this->assertEquals($expected, $fillable);
    }

    /**
     * Test: Relasi hasMany ke RiwayatKeluhans
     */
    public function test_has_many_riwayat_keluhans()
    {
        $dataDiri = DataDiris::factory()->create(['nim' => '123456789']);

        RiwayatKeluhans::factory()->count(3)->create([
            'nim' => '123456789'
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $dataDiri->riwayatKeluhans);
        $this->assertCount(3, $dataDiri->riwayatKeluhans);
    }

    /**
     * Test: Relasi hasMany ke HasilKuesioners
     */
    public function test_has_many_hasil_kuesioners()
    {
        $dataDiri = DataDiris::factory()->create(['nim' => '123456789']);

        HasilKuesioner::factory()->count(5)->create([
            'nim' => '123456789'
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $dataDiri->hasilKuesioners);
        $this->assertCount(5, $dataDiri->hasilKuesioners);
    }

    /**
     * Test: Relasi latestHasilKuesioner
     */
    public function test_has_one_latest_hasil_kuesioner()
    {
        $dataDiri = DataDiris::factory()->create(['nim' => '123456789']);

        HasilKuesioner::factory()->create([
            'nim' => '123456789',
            'total_skor' => 100,
            'created_at' => now()->subDays(2)
        ]);

        $latest = HasilKuesioner::factory()->create([
            'nim' => '123456789',
            'total_skor' => 180,
            'created_at' => now()
        ]);

        $result = $dataDiri->latestHasilKuesioner;

        $this->assertInstanceOf(HasilKuesioner::class, $result);
        $this->assertEquals($latest->id, $result->id);
        $this->assertEquals(180, $result->total_skor);
    }

    /**
     * Test: Model dapat dibuat dengan data valid
     */
    public function test_model_can_be_created_with_valid_data()
    {
        $data = [
            'nim' => '123456789',
            'nama' => 'John Doe',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Jl. Test No. 123',
            'usia' => 20,
            'fakultas' => 'FTIK',
            'program_studi' => 'Teknik Informatika',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'email' => 'john@example.com'
        ];

        $dataDiri = DataDiris::create($data);

        $this->assertDatabaseHas('data_diris', $data);
        $this->assertInstanceOf(DataDiris::class, $dataDiri);
        $this->assertEquals('123456789', $dataDiri->nim);
        $this->assertEquals('John Doe', $dataDiri->nama);
    }

    /**
     * Test: Scope search dengan keyword
     */
    public function test_scope_search_filters_by_keyword()
    {
        DataDiris::factory()->create([
            'nim' => '111111111',
            'nama' => 'John Doe'
        ]);

        DataDiris::factory()->create([
            'nim' => '222222222',
            'nama' => 'Jane Smith'
        ]);

        DataDiris::factory()->create([
            'nim' => '333333333',
            'nama' => 'Bob Johnson'
        ]);

        $results = DataDiris::search('John')->get();

        $this->assertCount(2, $results); // John Doe and Bob Johnson
        $this->assertTrue($results->contains('nama', 'John Doe'));
        $this->assertTrue($results->contains('nama', 'Bob Johnson'));
    }

    /**
     * Test: Scope search returns all when no keyword
     */
    public function test_scope_search_returns_all_when_no_keyword()
    {
        DataDiris::factory()->count(5)->create();

        $results = DataDiris::search(null)->get();

        $this->assertCount(5, $results);
    }

    /**
     * Test: Can update data diri
     */
    public function test_can_update_data_diri()
    {
        $dataDiri = DataDiris::factory()->create([
            'nim' => '123456789',
            'nama' => 'Old Name'
        ]);

        $dataDiri->update(['nama' => 'New Name']);

        $this->assertDatabaseHas('data_diris', [
            'nim' => '123456789',
            'nama' => 'New Name'
        ]);
    }

    /**
     * Test: Can delete data diri
     */
    public function test_can_delete_data_diri()
    {
        $dataDiri = DataDiris::factory()->create([
            'nim' => '123456789'
        ]);

        $dataDiri->delete();

        $this->assertDatabaseMissing('data_diris', [
            'nim' => '123456789'
        ]);
    }

    /**
     * Test: Filter by fakultas
     */
    public function test_can_filter_by_fakultas()
    {
        DataDiris::factory()->create(['fakultas' => 'FTIK']);
        DataDiris::factory()->create(['fakultas' => 'FTIK']);
        DataDiris::factory()->create(['fakultas' => 'FTI']);

        $results = DataDiris::where('fakultas', 'FTIK')->get();

        $this->assertCount(2, $results);
    }

    /**
     * Test: Filter by jenis_kelamin
     */
    public function test_can_filter_by_jenis_kelamin()
    {
        DataDiris::factory()->create(['jenis_kelamin' => 'L']);
        DataDiris::factory()->create(['jenis_kelamin' => 'L']);
        DataDiris::factory()->create(['jenis_kelamin' => 'P']);

        $laki = DataDiris::where('jenis_kelamin', 'L')->count();
        $perempuan = DataDiris::where('jenis_kelamin', 'P')->count();

        $this->assertEquals(2, $laki);
        $this->assertEquals(1, $perempuan);
    }

    /**
     * Test: Filter by asal_sekolah
     */
    public function test_can_filter_by_asal_sekolah()
    {
        DataDiris::factory()->create(['asal_sekolah' => 'SMA']);
        DataDiris::factory()->create(['asal_sekolah' => 'SMK']);
        DataDiris::factory()->create(['asal_sekolah' => 'SMA']);

        $sma = DataDiris::where('asal_sekolah', 'SMA')->count();
        $smk = DataDiris::where('asal_sekolah', 'SMK')->count();

        $this->assertEquals(2, $sma);
        $this->assertEquals(1, $smk);
    }
}
