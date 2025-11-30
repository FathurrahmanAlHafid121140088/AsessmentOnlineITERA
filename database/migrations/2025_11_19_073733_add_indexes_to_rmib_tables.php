<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Performance Improvement: Menambahkan indexes untuk query optimization
     * - Mempercepat JOIN operations
     * - Mempercepat WHERE clauses
     * - Mempercepat ORDER BY operations
     * - Mempercepat GROUP BY aggregations
     *
     * Expected improvement: 10-100x faster queries
     */
    public function up(): void
    {
        // ========================================
        // TABLE: rmib_hasil_tes
        // ========================================
        Schema::table('rmib_hasil_tes', function (Blueprint $table) {
            // Index untuk foreign key (frequently joined)
            if (!$this->indexExists('rmib_hasil_tes', 'idx_hasil_tes_data_diri_id')) {
                $table->index('karir_data_diri_id', 'idx_hasil_tes_data_diri_id');
            }

            // Index untuk tanggal (frequently sorted and filtered)
            if (!$this->indexExists('rmib_hasil_tes', 'idx_hasil_tes_tanggal')) {
                $table->index('tanggal_pengerjaan', 'idx_hasil_tes_tanggal');
            }

            // Composite index untuk query kombinasi
            if (!$this->indexExists('rmib_hasil_tes', 'idx_hasil_tes_combo')) {
                $table->index(['karir_data_diri_id', 'tanggal_pengerjaan'], 'idx_hasil_tes_combo');
            }
        });

        // ========================================
        // TABLE: rmib_jawaban_peserta
        // ========================================
        Schema::table('rmib_jawaban_peserta', function (Blueprint $table) {
            // Index untuk foreign key
            if (!$this->indexExists('rmib_jawaban_peserta', 'idx_jawaban_hasil_id')) {
                $table->index('hasil_id', 'idx_jawaban_hasil_id');
            }

            // Index untuk kelompok
            if (!$this->indexExists('rmib_jawaban_peserta', 'idx_jawaban_kelompok')) {
                $table->index('kelompok', 'idx_jawaban_kelompok');
            }

            // Index untuk pekerjaan
            if (!$this->indexExists('rmib_jawaban_peserta', 'idx_jawaban_pekerjaan')) {
                $table->index('pekerjaan', 'idx_jawaban_pekerjaan');
            }

            // Composite index
            if (!$this->indexExists('rmib_jawaban_peserta', 'idx_jawaban_combo')) {
                $table->index(['hasil_id', 'kelompok'], 'idx_jawaban_combo');
            }
        });

        // ========================================
        // TABLE: karir_data_diri
        // ========================================
        Schema::table('karir_data_diri', function (Blueprint $table) {
            if (!$this->indexExists('karir_data_diri', 'idx_data_diri_nim')) {
                $table->index('nim', 'idx_data_diri_nim');
            }

            if (!$this->indexExists('karir_data_diri', 'idx_data_diri_fakultas')) {
                $table->index('fakultas', 'idx_data_diri_fakultas');
            }

            if (!$this->indexExists('karir_data_diri', 'idx_data_diri_prodi')) {
                $table->index('program_studi', 'idx_data_diri_prodi');
            }

            if (!$this->indexExists('karir_data_diri', 'idx_data_diri_gender')) {
                $table->index('jenis_kelamin', 'idx_data_diri_gender');
            }

            if (!$this->indexExists('karir_data_diri', 'idx_data_diri_provinsi')) {
                $table->index('provinsi', 'idx_data_diri_provinsi');
            }

            if (!$this->indexExists('karir_data_diri', 'idx_data_diri_asal_sekolah')) {
                $table->index('asal_sekolah', 'idx_data_diri_asal_sekolah');
            }

            if (!$this->indexExists('karir_data_diri', 'idx_data_diri_status_tinggal')) {
                $table->index('status_tinggal', 'idx_data_diri_status_tinggal');
            }

            if (!$this->indexExists('karir_data_diri', 'idx_data_diri_prodi_sesuai')) {
                $table->index('prodi_sesuai_keinginan', 'idx_data_diri_prodi_sesuai');
            }
        });

        // ========================================
        // TABLE: rmib_pekerjaan
        // ========================================
        Schema::table('rmib_pekerjaan', function (Blueprint $table) {
            if (!$this->indexExists('rmib_pekerjaan', 'idx_pekerjaan_gender')) {
                $table->index('gender', 'idx_pekerjaan_gender');
            }

            if (!$this->indexExists('rmib_pekerjaan', 'idx_pekerjaan_kelompok')) {
                $table->index('kelompok', 'idx_pekerjaan_kelompok');
            }

            if (!$this->indexExists('rmib_pekerjaan', 'idx_pekerjaan_kategori')) {
                $table->index('kategori', 'idx_pekerjaan_kategori');
            }

            if (!$this->indexExists('rmib_pekerjaan', 'idx_pekerjaan_gender_kelompok')) {
                $table->index(['gender', 'kelompok'], 'idx_pekerjaan_gender_kelompok');
            }

            if (!$this->indexExists('rmib_pekerjaan', 'idx_pekerjaan_gender_kategori')) {
                $table->index(['gender', 'kategori'], 'idx_pekerjaan_gender_kategori');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rmib_hasil_tes', function (Blueprint $table) {
            $table->dropIndex('idx_hasil_tes_data_diri_id');
            $table->dropIndex('idx_hasil_tes_tanggal');
            $table->dropIndex('idx_hasil_tes_combo');
        });

        Schema::table('rmib_jawaban_peserta', function (Blueprint $table) {
            $table->dropIndex('idx_jawaban_hasil_id');
            $table->dropIndex('idx_jawaban_kelompok');
            $table->dropIndex('idx_jawaban_pekerjaan');
            $table->dropIndex('idx_jawaban_combo');
        });

        Schema::table('karir_data_diri', function (Blueprint $table) {
            $table->dropIndex('idx_data_diri_nim');
            $table->dropIndex('idx_data_diri_fakultas');
            $table->dropIndex('idx_data_diri_prodi');
            $table->dropIndex('idx_data_diri_gender');
            $table->dropIndex('idx_data_diri_provinsi');
            $table->dropIndex('idx_data_diri_asal_sekolah');
            $table->dropIndex('idx_data_diri_status_tinggal');
            $table->dropIndex('idx_data_diri_prodi_sesuai');
        });

        Schema::table('rmib_pekerjaan', function (Blueprint $table) {
            $table->dropIndex('idx_pekerjaan_gender');
            $table->dropIndex('idx_pekerjaan_kelompok');
            $table->dropIndex('idx_pekerjaan_kategori');
            $table->dropIndex('idx_pekerjaan_gender_kelompok');
            $table->dropIndex('idx_pekerjaan_gender_kategori');
        });
    }

    /**
     * Check if index exists on a table
     * Support both MySQL and SQLite
     */
    private function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            $result = $connection->select(
                "SELECT COUNT(*) as count
                 FROM sqlite_master
                 WHERE type = 'index'
                 AND name = ?",
                [$index]
            );

            return $result[0]->count > 0;
        }

        // MySQL
        $database = $connection->getDatabaseName();
        $result = $connection->select(
            "SELECT COUNT(*) as count
             FROM information_schema.statistics
             WHERE table_schema = ?
             AND table_name = ?
             AND index_name = ?",
            [$database, $table, $index]
        );

        return $result[0]->count > 0;
    }
};
