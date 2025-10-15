<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('karir_data_diri', function (Blueprint $table) {
            // 1. Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['user_id']);

            // 2. Hapus kolomnya
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('karir_data_diri', function (Blueprint $table) {
            // Kode ini untuk mengembalikan kolom jika Anda melakukan rollback
            $table->foreignId('user_id')->after('id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }
};