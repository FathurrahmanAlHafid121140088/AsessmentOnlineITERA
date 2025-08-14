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
            // Provinsi setelah jenis_kelamin
            $table->string('provinsi')->after('jenis_kelamin');

            // Asal sekolah setelah program_studi
            $table->string('asal_sekolah')->after('program_studi');

            // Status tinggal setelah asal_sekolah
            $table->string('status_tinggal')->after('asal_sekolah');
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
