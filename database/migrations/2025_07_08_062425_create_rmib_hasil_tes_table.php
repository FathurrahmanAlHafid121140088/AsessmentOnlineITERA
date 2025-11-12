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
    Schema::create('rmib_hasil_tes', function (Blueprint $table) {
        $table->id();

        // INI BAGIAN KUNCINYA:
        // Pastikan Anda menggunakan foreignId untuk merujuk ke 'id' di tabel karir_data_diri
        $table->foreignId('karir_data_diri_id')->constrained('karir_data_diri')->onDelete('cascade');

        // Kolom-kolom hasil tes lainnya
        $table->datetime('tanggal_pengerjaan');
        $table->string('top_1_pekerjaan');
        $table->string('top_2_pekerjaan');
        $table->string('top_3_pekerjaan');
        $table->integer('skor_konsistensi');
        $table->text('interpretasi')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rmib_hasil_tes');
    }
};