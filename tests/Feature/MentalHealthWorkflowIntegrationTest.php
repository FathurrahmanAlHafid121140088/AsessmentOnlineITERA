<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;
use App\Models\Users;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentalHealthWorkflowIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Complete user workflow from registration to viewing results
     */
    public function test_complete_user_workflow()
    {
        // 1. User logs in (via Google OAuth - simulated)
        $user = Users::factory()->create([
            'nim' => '123456789',
            'email' => 'student@student.itera.ac.id'
        ]);

        $this->actingAs($user);

        // 2. User fills data diri form
        $dataDiriData = [
            'nama' => 'John Doe',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'Jl. Test No. 123',
            'usia' => 20,
            'fakultas' => 'FTIK',
            'program_studi' => 'Teknik Informatika',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'email' => 'john@example.com',
            'keluhan' => 'Stress karena tugas kuliah',
            'lama_keluhan' => '1-3 bulan',
            'pernah_konsul' => 'Tidak',
            'pernah_tes' => 'Tidak'
        ];

        $response = $this->post(route('mental-health.store-data-diri'), $dataDiriData);

        // Should redirect to kuesioner
        $response->assertRedirect(route('mental-health.kuesioner'));

        // Verify data diri saved
        $this->assertDatabaseHas('data_diris', [
            'nim' => '123456789',
            'nama' => 'John Doe'
        ]);

        // Verify riwayat keluhan saved
        $this->assertDatabaseHas('riwayat_keluhans', [
            'nim' => '123456789',
            'keluhan' => 'Stress karena tugas kuliah'
        ]);

        // 3. User fills mental health kuesioner
        $kuesionerData = ['nim' => '123456789'];
        for ($i = 1; $i <= 38; $i++) {
            $kuesionerData["question{$i}"] = 5; // Total: 190 (Sangat Sehat)
        }

        $response = $this->post(route('mental-health.kuesioner.submit'), $kuesionerData);

        // Should redirect to hasil
        $response->assertRedirect(route('mental-health.hasil'));

        // Verify hasil saved
        $this->assertDatabaseHas('hasil_kuesioners', [
            'nim' => '123456789',
            'total_skor' => 190,
            'kategori' => 'Sangat Sehat'
        ]);

        // 4. User views hasil page
        $response = $this->get(route('mental-health.hasil'));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Sangat Sehat');
        $response->assertSee('190');

        // 5. User views dashboard
        $response = $this->get(route('user.mental-health'));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Teknik Informatika');

        // Verify workflow completed successfully
        $this->assertTrue(DataDiris::where('nim', '123456789')->exists());
        $this->assertTrue(RiwayatKeluhans::where('nim', '123456789')->exists());
        $this->assertTrue(HasilKuesioner::where('nim', '123456789')->exists());
    }

    /**
     * Test: User takes multiple tests over time
     */
    public function test_user_takes_multiple_tests_over_time()
    {
        $user = Users::factory()->create(['nim' => '123456789']);
        DataDiris::factory()->create(['nim' => '123456789', 'nama' => 'John Doe']);

        $this->actingAs($user);

        // First test - Sangat Sehat
        $data1 = ['nim' => '123456789'];
        for ($i = 1; $i <= 38; $i++) {
            $data1["question{$i}"] = 5;
        }

        $this->post(route('mental-health.kuesioner.submit'), $data1);

        $this->assertDatabaseHas('hasil_kuesioners', [
            'nim' => '123456789',
            'total_skor' => 190,
            'kategori' => 'Sangat Sehat'
        ]);

        // Second test - Sehat (some time later)
        $data2 = ['nim' => '123456789'];
        for ($i = 1; $i <= 34; $i++) {
            $data2["question{$i}"] = 5;
        }
        for ($i = 35; $i <= 38; $i++) {
            $data2["question{$i}"] = 0;
        }

        $this->post(route('mental-health.kuesioner.submit'), $data2);

        // Should have 2 test results
        $this->assertEquals(2, HasilKuesioner::where('nim', '123456789')->count());

        // Dashboard should show both tests
        $response = $this->get(route('user.mental-health'));

        $response->assertStatus(200);
        $testData = $response->viewData('jumlahTesDiikuti');
        $this->assertEquals(2, $testData);
    }

    /**
     * Test: Admin workflow - view, search, filter, export, delete
     */
    public function test_admin_complete_workflow()
    {
        $admin = Admin::factory()->create();

        // Create test data
        $dataDiri1 = DataDiris::factory()->create(['nim' => '111111111', 'nama' => 'Alice']);
        $dataDiri2 = DataDiris::factory()->create(['nim' => '222222222', 'nama' => 'Bob']);

        $hasil1 = HasilKuesioner::factory()->create([
            'nim' => '111111111',
            'kategori' => 'Sangat Sehat'
        ]);

        $hasil2 = HasilKuesioner::factory()->create([
            'nim' => '222222222',
            'kategori' => 'Sehat'
        ]);

        $this->actingAs($admin, 'admin');

        // 1. View dashboard
        $response = $this->get(route('admin.home'));
        $response->assertStatus(200);
        $response->assertSee('Alice');
        $response->assertSee('Bob');

        // 2. Search for specific student
        $response = $this->get(route('admin.home', ['search' => 'Alice']));
        $response->assertStatus(200);
        $response->assertSee('Alice');
        $response->assertDontSee('Bob');

        // 3. Filter by kategori
        $response = $this->get(route('admin.home', ['kategori' => 'Sangat Sehat']));
        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('hasilKuesioners'));

        // 4. Export data
        $response = $this->get(route('admin.export.excel'));
        $response->assertStatus(200);

        // 5. Delete a result
        $response = $this->delete(route('admin.delete', $hasil1->id));
        $response->assertRedirect(route('admin.home'));
        $this->assertDatabaseMissing('hasil_kuesioners', ['id' => $hasil1->id]);
    }

    /**
     * Test: Update data diri workflow
     */
    public function test_update_data_diri_workflow()
    {
        $user = Users::factory()->create(['nim' => '123456789']);

        // Initial data diri
        DataDiris::factory()->create([
            'nim' => '123456789',
            'nama' => 'Old Name',
            'fakultas' => 'FTIK'
        ]);

        $this->actingAs($user);

        // Update data diri
        $updateData = [
            'nama' => 'New Name',
            'jenis_kelamin' => 'L',
            'provinsi' => 'Lampung',
            'alamat' => 'New Address',
            'usia' => 21,
            'fakultas' => 'FTI', // Changed
            'program_studi' => 'Teknik Elektro',
            'asal_sekolah' => 'SMA',
            'status_tinggal' => 'Kost',
            'email' => 'new@example.com',
            'keluhan' => 'Anxiety',
            'lama_keluhan' => '3-6 bulan',
            'pernah_konsul' => 'Ya',
            'pernah_tes' => 'Ya'
        ];

        $response = $this->post(route('mental-health.store-data-diri'), $updateData);

        // Verify update
        $this->assertDatabaseHas('data_diris', [
            'nim' => '123456789',
            'nama' => 'New Name',
            'fakultas' => 'FTI'
        ]);

        // Old data should be replaced
        $this->assertDatabaseMissing('data_diris', [
            'nim' => '123456789',
            'nama' => 'Old Name'
        ]);
    }

    /**
     * Test: Full workflow with cache invalidation
     */
    public function test_full_workflow_with_cache_invalidation()
    {
        $admin = Admin::factory()->create();
        $user = Users::factory()->create(['nim' => '123456789']);

        // Admin views empty dashboard - creates cache
        $this->actingAs($admin, 'admin')
            ->get(route('admin.home'));

        $this->assertTrue(\Cache::has('mh.admin.user_stats'));

        // User fills data and submits kuesioner
        DataDiris::factory()->create(['nim' => '123456789']);

        $data = ['nim' => '123456789'];
        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = 5;
        }

        $this->actingAs($user)
            ->post(route('mental-health.kuesioner.submit'), $data);

        // Cache should be invalidated
        $this->assertFalse(\Cache::has('mh.admin.user_stats'));

        // Admin views dashboard again - sees new data
        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.home'));

        $response->assertStatus(200);
        $this->assertEquals(1, $response->viewData('totalUsers'));
        $this->assertEquals(1, $response->viewData('totalTes'));
    }

    /**
     * Test: Multiple students same workflow
     */
    public function test_multiple_students_same_workflow()
    {
        $admin = Admin::factory()->create();

        // Create 5 students
        for ($i = 1; $i <= 5; $i++) {
            $nim = str_pad($i, 9, '0', STR_PAD_LEFT);
            $user = Users::factory()->create(['nim' => $nim]);

            // Each student fills data diri
            DataDiris::factory()->create([
                'nim' => $nim,
                'nama' => "Student {$i}"
            ]);

            // Each student takes test
            $data = ['nim' => $nim];
            for ($j = 1; $j <= 38; $j++) {
                $data["question{$j}"] = rand(1, 6);
            }

            $this->actingAs($user)
                ->post(route('mental-health.kuesioner.submit'), $data);
        }

        // Admin should see all 5 students
        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.home'));

        $this->assertEquals(5, $response->viewData('totalUsers'));
        $this->assertEquals(5, $response->viewData('totalTes'));
        $this->assertCount(5, $response->viewData('hasilKuesioners'));
    }

    /**
     * Test: Error handling in workflow
     */
    public function test_error_handling_in_workflow()
    {
        $user = Users::factory()->create(['nim' => '123456789']);
        $this->actingAs($user);

        // Try to submit kuesioner without data diri (should fail validation)
        $data = ['nim' => '123456789'];
        for ($i = 1; $i <= 38; $i++) {
            $data["question{$i}"] = 5;
        }

        // This might fail due to foreign key constraint, but should be handled gracefully
        $response = $this->post(route('mental-health.kuesioner.submit'), $data);

        // Should either succeed or show error, but not crash
        $this->assertTrue(
            $response->isRedirection() || $response->status() === 200
        );
    }
}
