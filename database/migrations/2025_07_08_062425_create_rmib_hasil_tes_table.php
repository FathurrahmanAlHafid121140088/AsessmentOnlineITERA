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
            $table->id(); // Primary key untuk tabel ini

            // Foreign key yang terhubung ke tabel data diri
            $table->string('nim', 20);
            $table->foreignId('karir_data_diri_id')->constrained('karir_data_diri')->onDelete('cascade');

            $table->datetime('tanggal_pengerjaan');
            $table->string('top_1_pekerjaan');
            $table->string('top_2_pekerjaan');
            $table->string('top_3_pekerjaan');
            $table->integer('skor_konsistensi');
            $table->text('interpretasi')->nullable(); // Boleh null jika interpretasi tidak selalu ada

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