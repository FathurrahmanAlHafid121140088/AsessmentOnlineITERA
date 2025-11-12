<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/..._create_rmib_pekerjaan_table.php
public function up(): void
{
    Schema::create('rmib_pekerjaan', function (Blueprint $table) {
        $table->id();
        $table->string('kelompok'); // Kluster (A, B, C, ...)
        $table->enum('gender', ['L', 'P']);
        
        // TAMBAHKAN KOLOM INI
        $table->string('kategori'); // Misal: 'Outdoor', 'Mechanical', 'Computational'

        $table->string('nama_pekerjaan');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rmib_pekerjaans');
    }
};
