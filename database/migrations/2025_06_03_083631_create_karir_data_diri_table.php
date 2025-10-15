<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('karir_data_diri', function (Blueprint $table) {
        // KEMBALIKAN: Jadikan 'id' sebagai primary key standar Laravel.
        // Ini sudah auto-increment dan primary key.
        $table->id();

        // UBAH: Jadikan 'nim' sebagai kolom unik, bukan primary key.
        // Panjang 20 dan constraint 'unique' memastikan tidak ada NIM yang sama.
        $table->string('nim', 20)->unique();
        
        // Kolom-kolom lainnya
        $table->string('nama');
        $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
        $table->text('alamat')->nullable();
        $table->integer('usia')->nullable();
        $table->string('fakultas')->nullable();
        $table->string('program_studi')->nullable();
        $table->string('email')->nullable();
        $table->string('asal_sekolah')->nullable();
        $table->string('provinsi')->nullable();
        $table->string('status_tinggal')->nullable();

        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('karir_data_diri');
    }
};