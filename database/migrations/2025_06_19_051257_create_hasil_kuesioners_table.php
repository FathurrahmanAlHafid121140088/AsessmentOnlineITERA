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
        Schema::create('hasil_kuesioners', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nim');
            $table->integer('total_skor')->nullable();
            $table->string('kategori')->nullable();

            // Resume Features
            $table->integer('posisi_soal_terakhir')->default(0);
            $table->string('status')->default('on_progress'); // on_progress / selesai
            $table->json('draft_jawaban')->nullable(); // Stores [1=>4, 2=>5, ...]

            $table->timestamp('tanggal_pengerjaan')->useCurrent();
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('data_diris')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_kuesioners');
    }
};