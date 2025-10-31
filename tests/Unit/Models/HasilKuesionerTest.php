<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasilKuesionerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Model memiliki fillable attributes yang benar
     */
    public function test_model_has_correct_fillable_attributes()
    {
        $hasil = new HasilKuesioner();
        $fillable = $hasil->getFillable();

        $expectedFillable = [
            'nim',
            'total_skor',
            'kategori',
            'created_at',
            'updated_at'
        ];

        $this->assertEquals($expectedFillable, $fillable);
    }

    /**
     * Test: Model menggunakan tabel yang benar
     */
    public function test_model_uses_correct_table()
    {
        $hasil = new HasilKuesioner();
        $this->assertEquals('hasil_kuesioners', $hasil->getTable());
    }

    /**
     * Test: Relasi belongsTo ke DataDiris
     */
    public function test_belongs_to_data_diri()
    {
        // Create DataDiri
        $dataDiri = DataDiris::factory()->create([
            'nim' => '123456789'
        ]);

        // Create HasilKuesioner
        $hasil = HasilKuesioner::factory()->create([
            'nim' => '123456789'
        ]);

        // Test relation
        $this->assertInstanceOf(DataDiris::class, $hasil->dataDiri);
        $this->assertEquals('123456789', $hasil->dataDiri->nim);
        $this->assertEquals($dataDiri->nim, $hasil->dataDiri->nim);
    }

    /**
     * Test: Relasi hasMany ke RiwayatKeluhans
     */
    public function test_has_many_riwayat_keluhans()
    {
        // Create DataDiri first
        DataDiris::factory()->create(['nim' => '123456789']);

        // Create HasilKuesioner
        $hasil = HasilKuesioner::factory()->create([
            'nim' => '123456789'
        ]);

        // Create RiwayatKeluhans
        RiwayatKeluhans::factory()->count(3)->create([
            'nim' => '123456789'
        ]);

        // Test relation
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $hasil->riwayatKeluhans);
        $this->assertCount(3, $hasil->riwayatKeluhans);
        $this->assertInstanceOf(RiwayatKeluhans::class, $hasil->riwayatKeluhans->first());
    }

    /**
     * Test: Model dapat dibuat dengan data valid
     */
    public function test_model_can_be_created_with_valid_data()
    {
        // Create DataDiri first for foreign key
        DataDiris::factory()->create(['nim' => '123456789']);

        $data = [
            'nim' => '123456789',
            'total_skor' => 180,
            'kategori' => 'Sehat',
        ];

        $hasil = HasilKuesioner::create($data);

        $this->assertDatabaseHas('hasil_kuesioners', [
            'nim' => '123456789',
            'total_skor' => 180,
            'kategori' => 'Sehat',
        ]);

        $this->assertInstanceOf(HasilKuesioner::class, $hasil);
        $this->assertEquals('123456789', $hasil->nim);
        $this->assertEquals(180, $hasil->total_skor);
        $this->assertEquals('Sehat', $hasil->kategori);
    }

    /**
     * Test: Query latest results by NIM
     */
    public function test_can_get_latest_result_by_nim()
    {
        DataDiris::factory()->create(['nim' => '123456789']);

        // Create multiple results
        HasilKuesioner::factory()->create([
            'nim' => '123456789',
            'total_skor' => 100,
            'created_at' => now()->subDays(2)
        ]);

        $latest = HasilKuesioner::factory()->create([
            'nim' => '123456789',
            'total_skor' => 150,
            'created_at' => now()
        ]);

        // Get latest
        $result = HasilKuesioner::where('nim', '123456789')
            ->latest()
            ->first();

        $this->assertEquals($latest->id, $result->id);
        $this->assertEquals(150, $result->total_skor);
    }

    /**
     * Test: Count total tests by NIM
     */
    public function test_can_count_total_tests_by_nim()
    {
        DataDiris::factory()->create(['nim' => '123456789']);

        // Create 5 tests
        HasilKuesioner::factory()->count(5)->create([
            'nim' => '123456789'
        ]);

        $count = HasilKuesioner::where('nim', '123456789')->count();

        $this->assertEquals(5, $count);
    }

    /**
     * Test: Get distinct NIMs
     */
    public function test_can_get_distinct_nims()
    {
        DataDiris::factory()->create(['nim' => '111111111']);
        DataDiris::factory()->create(['nim' => '222222222']);
        DataDiris::factory()->create(['nim' => '333333333']);

        HasilKuesioner::factory()->count(3)->create(['nim' => '111111111']);
        HasilKuesioner::factory()->count(2)->create(['nim' => '222222222']);
        HasilKuesioner::factory()->count(1)->create(['nim' => '333333333']);

        $distinctNims = HasilKuesioner::distinct()->pluck('nim');

        $this->assertCount(3, $distinctNims);
        $this->assertTrue($distinctNims->contains('111111111'));
        $this->assertTrue($distinctNims->contains('222222222'));
        $this->assertTrue($distinctNims->contains('333333333'));
    }

    /**
     * Test: Group by kategori
     */
    public function test_can_group_by_kategori()
    {
        DataDiris::factory()->create(['nim' => '111111111']);
        DataDiris::factory()->create(['nim' => '222222222']);
        DataDiris::factory()->create(['nim' => '333333333']);

        HasilKuesioner::factory()->create(['nim' => '111111111', 'kategori' => 'Sangat Sehat']);
        HasilKuesioner::factory()->create(['nim' => '222222222', 'kategori' => 'Sehat']);
        HasilKuesioner::factory()->create(['nim' => '333333333', 'kategori' => 'Sehat']);

        $grouped = HasilKuesioner::selectRaw('kategori, COUNT(*) as count')
            ->groupBy('kategori')
            ->pluck('count', 'kategori');

        $this->assertEquals(1, $grouped['Sangat Sehat']);
        $this->assertEquals(2, $grouped['Sehat']);
    }

    /**
     * Test: Timestamps are automatically managed
     */
    public function test_timestamps_are_automatically_managed()
    {
        DataDiris::factory()->create(['nim' => '123456789']);

        $hasil = HasilKuesioner::create([
            'nim' => '123456789',
            'total_skor' => 150,
            'kategori' => 'Sehat'
        ]);

        $this->assertNotNull($hasil->created_at);
        $this->assertNotNull($hasil->updated_at);
        $this->assertEquals($hasil->created_at, $hasil->updated_at);

        // Update
        sleep(1);
        $hasil->update(['total_skor' => 160]);
        $hasil->refresh();

        $this->assertNotEquals($hasil->created_at, $hasil->updated_at);
        $this->assertTrue($hasil->updated_at->greaterThan($hasil->created_at));
    }

    /**
     * Test: Can delete hasil kuesioner
     */
    public function test_can_delete_hasil_kuesioner()
    {
        DataDiris::factory()->create(['nim' => '123456789']);

        $hasil = HasilKuesioner::factory()->create([
            'nim' => '123456789'
        ]);

        $id = $hasil->id;

        $hasil->delete();

        $this->assertDatabaseMissing('hasil_kuesioners', [
            'id' => $id
        ]);
    }
}
