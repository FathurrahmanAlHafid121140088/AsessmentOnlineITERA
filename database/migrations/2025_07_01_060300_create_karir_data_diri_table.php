<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('karir_data_diri', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nim', 20);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->integer('usia');
            $table->string('fakultas');
            $table->string('program_studi');
            $table->string('email');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('karir_data_diri');
    }
};
