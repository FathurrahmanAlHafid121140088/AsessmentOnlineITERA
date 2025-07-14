<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('rmib_jawaban_peserta', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('hasil_id'); // FK ke rmib_hasil_tes
        $table->string('kelompok');             // Kelompok A–I
        $table->string('pekerjaan');            // Nama pekerjaan
        $table->integer('peringkat')->nullable();
        $table->timestamps();

        $table->foreign('hasil_id')->references('id_hasil')->on('rmib_hasil_tes')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rmib_jawaban_peserta');
    }
};
