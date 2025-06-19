<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hasil_kuesioners', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nim'); // disesuaikan jadi angka juga
            $table->integer('total_skor');
            $table->string('kategori');
            $table->timestamp('tanggal_pengerjaan')->useCurrent();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('nim')->references('nim')->on('data_diris')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hasil_kuesioners');
    }
};
