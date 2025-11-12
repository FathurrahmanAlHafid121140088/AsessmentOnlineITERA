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
            $table->integer('skor_konsistensi')->nullable()->change();
            $table->string('top_1_pekerjaan')->nullable()->change();
            $table->string('top_2_pekerjaan')->nullable()->change();
            $table->string('top_3_pekerjaan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rmib_hasil_tes', function (Blueprint $table) {
            $table->integer('skor_konsistensi')->nullable(false)->change();
            $table->string('top_1_pekerjaan')->nullable(false)->change();
            $table->string('top_2_pekerjaan')->nullable(false)->change();
            $table->string('top_3_pekerjaan')->nullable(false)->change();
        });
    }
};
