<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\RiwayatKeluhans;
use App\Models\DataDiris;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiwayatKeluhansTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Model menggunakan tabel yang benar
     */
    public function test_model_uses_correct_table()
    {
        $riwayat = new RiwayatKeluhans();
        $this->assertEquals('riwayat_keluhans', $riwayat->getTable());
    }

    /**
     * Test: Model memiliki fillable attributes yang benar
     */
    public function test_model_has_correct_fillable_attributes()
    {
        $riwayat = new RiwayatKeluhans();
        $fillable = $riwayat->getFillable();

        $expected = [
            'nim',
            'keluhan',
            'lama_keluhan',
            'pernah_konsul',
            'pernah_tes',
        ];

        $this->assertEquals($expected, $fillable);
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
            'keluhan' => 'Stress',
            'lama_keluhan' => '1-3 bulan',
            'pernah_konsul' => 'Ya',
            'pernah_tes' => 'Tidak',
        ];

        $riwayat = RiwayatKeluhans::create($data);

        $this->assertDatabaseHas('riwayat_keluhans', $data);
        $this->assertInstanceOf(RiwayatKeluhans::class, $riwayat);
    }

    /**
     * Test: Can get latest keluhan by NIM
     */
    public function test_can_get_latest_keluhan_by_nim()
    {
        DataDiris::factory()->create(['nim' => '123456789']);

        RiwayatKeluhans::factory()->create([
            'nim' => '123456789',
            'keluhan' => 'Old Keluhan',
            'created_at' => now()->subDays(2)
        ]);

        $latest = RiwayatKeluhans::factory()->create([
            'nim' => '123456789',
            'keluhan' => 'Latest Keluhan',
            'created_at' => now()
        ]);

        $result = RiwayatKeluhans::where('nim', '123456789')
            ->latest()
            ->first();

        $this->assertEquals($latest->id, $result->id);
        $this->assertEquals('Latest Keluhan', $result->keluhan);
    }

    /**
     * Test: Can count riwayat by NIM
     */
    public function test_can_count_riwayat_by_nim()
    {
        DataDiris::factory()->create(['nim' => '123456789']);

        RiwayatKeluhans::factory()->count(3)->create([
            'nim' => '123456789'
        ]);

        $count = RiwayatKeluhans::where('nim', '123456789')->count();

        $this->assertEquals(3, $count);
    }

    /**
     * Test: Filter by pernah_konsul
     */
    public function test_can_filter_by_pernah_konsul()
    {
        DataDiris::factory()->create(['nim' => '111111111']);
        DataDiris::factory()->create(['nim' => '222222222']);

        RiwayatKeluhans::factory()->create([
            'nim' => '111111111',
            'pernah_konsul' => 'Ya'
        ]);

        RiwayatKeluhans::factory()->create([
            'nim' => '222222222',
            'pernah_konsul' => 'Tidak'
        ]);

        $ya = RiwayatKeluhans::where('pernah_konsul', 'Ya')->count();
        $tidak = RiwayatKeluhans::where('pernah_konsul', 'Tidak')->count();

        $this->assertEquals(1, $ya);
        $this->assertEquals(1, $tidak);
    }

    /**
     * Test: Can update riwayat
     */
    public function test_can_update_riwayat()
    {
        DataDiris::factory()->create(['nim' => '123456789']);

        $riwayat = RiwayatKeluhans::factory()->create([
            'nim' => '123456789',
            'keluhan' => 'Old Keluhan'
        ]);

        $riwayat->update(['keluhan' => 'Updated Keluhan']);

        $this->assertDatabaseHas('riwayat_keluhans', [
            'id' => $riwayat->id,
            'keluhan' => 'Updated Keluhan'
        ]);
    }

    /**
     * Test: Can delete riwayat
     */
    public function test_can_delete_riwayat()
    {
        DataDiris::factory()->create(['nim' => '123456789']);

        $riwayat = RiwayatKeluhans::factory()->create([
            'nim' => '123456789'
        ]);

        $id = $riwayat->id;
        $riwayat->delete();

        $this->assertDatabaseMissing('riwayat_keluhans', [
            'id' => $id
        ]);
    }

    /**
     * Test: Timestamps are automatically managed
     */
    public function test_timestamps_are_automatically_managed()
    {
        DataDiris::factory()->create(['nim' => '123456789']);

        $riwayat = RiwayatKeluhans::create([
            'nim' => '123456789',
            'keluhan' => 'Test',
            'lama_keluhan' => '1-3 bulan',
            'pernah_konsul' => 'Ya',
            'pernah_tes' => 'Tidak'
        ]);

        $this->assertNotNull($riwayat->created_at);
        $this->assertNotNull($riwayat->updated_at);
    }
}
