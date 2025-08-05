<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rmib_hasil_tes', function (Blueprint $table) {
            $table->id('id_hasil');
            $table->unsignedBigInteger('user_id');
            $table->datetime('tanggal_pengerjaan');
            $table->string('top_1_pekerjaan');
            $table->string('top_2_pekerjaan');
            $table->string('top_3_pekerjaan');
            $table->text('interpretasi')->nullable();
            $table->string('nama');
            $table->string('nim');
            $table->string('program_studi');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('karir_data_diri')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rmib_hasil_tes');
    }
};
