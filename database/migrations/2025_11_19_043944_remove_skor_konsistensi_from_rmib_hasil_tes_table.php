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
            $table->dropColumn('skor_konsistensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rmib_hasil_tes', function (Blueprint $table) {
            $table->integer('skor_konsistensi')->nullable()->after('top_3_pekerjaan');
        });
    }
};
