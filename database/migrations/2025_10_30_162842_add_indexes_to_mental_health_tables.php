<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan indexes ke tabel Mental Health untuk optimasi query performance:
     * - hasil_kuesioners: Speed up JOINs dan filtering
     * - data_diris: Speed up search dan filtering
     * - riwayat_keluhans: Speed up queries
     */
    public function up(): void
    {
        // =========================================
        // 1. HASIL KUESIONERS TABLE
        // =========================================
        Schema::table('hasil_kuesioners', function (Blueprint $table) {
            // Index untuk JOIN dengan data_diris (CRITICAL - paling penting!)
            // Query: JOIN data_diris ON hasil_kuesioners.nim = data_diris.nim
            $table->index('nim', 'idx_hasil_kuesioners_nim');

            // Index untuk filtering by kategori
            // Query: WHERE kategori = 'Sangat Baik'
            $table->index('kategori', 'idx_hasil_kuesioners_kategori');

            // Index untuk sorting by tanggal
            // Query: ORDER BY tanggal_pengerjaan DESC
            $table->index('tanggal_pengerjaan', 'idx_hasil_kuesioners_tanggal');

            // Index untuk sorting by created_at (sering dipakai di admin dashboard)
            // Query: ORDER BY created_at DESC
            $table->index('created_at', 'idx_hasil_kuesioners_created_at');

            // Composite index untuk kombinasi filter + sort (advanced optimization)
            // Query: WHERE kategori = 'X' ORDER BY created_at DESC
            $table->index(['kategori', 'created_at'], 'idx_hasil_kuesioners_kategori_created');

            // Composite index untuk NIM + created_at (untuk ambil hasil terakhir per mahasiswa)
            // Query: WHERE nim = X ORDER BY created_at DESC LIMIT 1
            $table->index(['nim', 'created_at'], 'idx_hasil_kuesioners_nim_created');
        });

        // =========================================
        // 2. DATA DIRIS TABLE
        // =========================================
        Schema::table('data_diris', function (Blueprint $table) {
            // Index untuk search by nama (LIKE query)
            // Query: WHERE nama LIKE '%keyword%'
            $table->index('nama', 'idx_data_diris_nama');

            // Index untuk filter by fakultas
            // Query: WHERE fakultas = 'Fakultas Sains'
            $table->index('fakultas', 'idx_data_diris_fakultas');

            // Index untuk filter by program_studi
            // Query: WHERE program_studi = 'Teknik Informatika'
            $table->index('program_studi', 'idx_data_diris_prodi');

            // Index untuk filter by jenis_kelamin
            // Query: WHERE jenis_kelamin = 'L'
            $table->index('jenis_kelamin', 'idx_data_diris_jk');

            // Index untuk search by email (jika ada)
            // Query: WHERE email = 'user@example.com'
            if (Schema::hasColumn('data_diris', 'email')) {
                $table->index('email', 'idx_data_diris_email');
            }

            // Composite index untuk filter fakultas + prodi (sering dipakai bersamaan)
            // Query: WHERE fakultas = 'X' AND program_studi = 'Y'
            $table->index(['fakultas', 'program_studi'], 'idx_data_diris_fakultas_prodi');
        });

        // =========================================
        // 3. RIWAYAT KELUHANS TABLE
        // =========================================
        Schema::table('riwayat_keluhans', function (Blueprint $table) {
            // Index untuk nim (jika belum ada dari foreign key)
            // Query: WHERE nim = X
            if (!$this->indexExists('riwayat_keluhans', 'riwayat_keluhans_nim_foreign')) {
                $table->index('nim', 'idx_riwayat_keluhans_nim');
            }

            // Index untuk filter by pernah_konsul
            // Query: WHERE pernah_konsul = 'Ya'
            $table->index('pernah_konsul', 'idx_riwayat_keluhans_konsul');

            // Index untuk filter by pernah_tes
            // Query: WHERE pernah_tes = 'Ya'
            $table->index('pernah_tes', 'idx_riwayat_keluhans_tes');

            // Index untuk sorting by created_at
            // Query: ORDER BY created_at DESC
            $table->index('created_at', 'idx_riwayat_keluhans_created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Menghapus semua indexes yang ditambahkan
     */
    public function down(): void
    {
        // Drop indexes dari hasil_kuesioners
        Schema::table('hasil_kuesioners', function (Blueprint $table) {
            $table->dropIndex('idx_hasil_kuesioners_nim');
            $table->dropIndex('idx_hasil_kuesioners_kategori');
            $table->dropIndex('idx_hasil_kuesioners_tanggal');
            $table->dropIndex('idx_hasil_kuesioners_created_at');
            $table->dropIndex('idx_hasil_kuesioners_kategori_created');
            $table->dropIndex('idx_hasil_kuesioners_nim_created');
        });

        // Drop indexes dari data_diris
        Schema::table('data_diris', function (Blueprint $table) {
            $table->dropIndex('idx_data_diris_nama');
            $table->dropIndex('idx_data_diris_fakultas');
            $table->dropIndex('idx_data_diris_prodi');
            $table->dropIndex('idx_data_diris_jk');

            if (Schema::hasColumn('data_diris', 'email')) {
                $table->dropIndex('idx_data_diris_email');
            }

            $table->dropIndex('idx_data_diris_fakultas_prodi');
        });

        // Drop indexes dari riwayat_keluhans
        Schema::table('riwayat_keluhans', function (Blueprint $table) {
            if ($this->indexExists('riwayat_keluhans', 'idx_riwayat_keluhans_nim')) {
                $table->dropIndex('idx_riwayat_keluhans_nim');
            }
            $table->dropIndex('idx_riwayat_keluhans_konsul');
            $table->dropIndex('idx_riwayat_keluhans_tes');
            $table->dropIndex('idx_riwayat_keluhans_created_at');
        });
    }

    /**
     * Check if index exists on a table
     */
    private function indexExists(string $table, string $index): bool
    {
        try {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes($table);
            return array_key_exists($index, $indexes);
        } catch (\Exception $e) {
            // Fallback: Assume index doesn't exist
            return false;
        }
    }
};
