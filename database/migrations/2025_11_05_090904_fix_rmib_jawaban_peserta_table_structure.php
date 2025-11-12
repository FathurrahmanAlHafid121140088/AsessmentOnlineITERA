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
        Schema::table('rmib_jawaban_peserta', function (Blueprint $table) {
            // Hapus kolom yang tidak diperlukan
            $table->dropForeign(['karir_data_diri_id']);
            $table->dropColumn(['nim', 'karir_data_diri_id']);

            // Tambah kolom hasil_id yang merujuk ke rmib_hasil_tes
            $table->foreignId('hasil_id')->after('id')->constrained('rmib_hasil_tes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rmib_jawaban_peserta', function (Blueprint $table) {
            // Kembalikan struktur semula
            $table->dropForeign(['hasil_id']);
            $table->dropColumn('hasil_id');

            $table->bigInteger('nim')->after('id');
            $table->foreignId('karir_data_diri_id')->after('peringkat')->constrained('karir_data_diri')->onDelete('cascade');
        });
    }
};
