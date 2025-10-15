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
            $table->bigInteger('nim');
            $table->string('kelompok'); // Nama kategori pekerjaan
            $table->string('pekerjaan'); // Nama pekerjaan
            $table->integer('peringkat'); // Peringkat 1-12
            $table->timestamps();

            $table->foreignId('karir_data_diri_id')->constrained('karir_data_diri')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rmib_jawaban_peserta');
    }
};
