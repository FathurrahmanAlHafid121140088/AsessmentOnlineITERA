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
        Schema::table('rmib_hasil_tes', function (Blueprint $table) {
            $table->string('pekerjaan_lain')->nullable()->after('top_3_alasan');
            $table->text('pekerjaan_lain_alasan')->nullable()->after('pekerjaan_lain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rmib_hasil_tes', function (Blueprint $table) {
            $table->dropColumn(['pekerjaan_lain', 'pekerjaan_lain_alasan']);
        });
    }
};
