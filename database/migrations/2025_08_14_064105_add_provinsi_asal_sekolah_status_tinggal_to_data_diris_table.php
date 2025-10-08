<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('data_diris', function (Blueprint $table) {
            // Menambahkan ->nullable() agar kolom ini diizinkan kosong
            $table->string('provinsi')->after('jenis_kelamin')->nullable();
            $table->string('asal_sekolah')->after('program_studi')->nullable();
            $table->string('status_tinggal')->after('asal_sekolah')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_diris', function (Blueprint $table) {
            $table->dropColumn(['provinsi', 'asal_sekolah', 'status_tinggal']);
        });
    }
};
