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
    Schema::create('jawabans_details', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('nim');
        $table->foreignId('jawaban_id')->constrained('jawaban')->onDelete('cascade');
        $table->integer('nomor_soal');
        $table->integer('skor');
        $table->timestamps();

        $table->foreignId('karir_data_diri_id')->constrained('karir_data_diri')->onDelete('cascade');

    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawabans_details');
    }
};
