<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rmib_jawaban_peserta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hasil_id');
            $table->string('kelompok'); // Nama kategori pekerjaan
            $table->string('pekerjaan'); // Nama pekerjaan
            $table->integer('peringkat'); // Peringkat 1-12
            $table->timestamps();

            $table->foreign('hasil_id')->references('id_hasil')->on('rmib_hasil_tes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rmib_jawaban_peserta');
    }
};
