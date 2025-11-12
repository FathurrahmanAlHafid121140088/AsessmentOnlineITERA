<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('karir_data_diri', function (Blueprint $table) {
            $table->enum('prodi_sesuai_keinginan', ['Ya', 'Tidak'])->nullable()->after('program_studi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karir_data_diri', function (Blueprint $table) {
            $table->dropColumn('prodi_sesuai_keinginan');
        });
    }
};
