<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('riwayat_keluhans', function (Blueprint $table) {
            // 1. Hapus foreign key yang lama untuk menghindari konflik.
            $table->dropForeign(['nim']);

            // 2. Tambahkan kembali foreign key dengan aturan onDelete('cascade').
            $table->foreign('nim')
                ->references('nim')->on('data_diris')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('riwayat_keluhans', function (Blueprint $table) {
            // Batalkan perubahan dengan menghapus constraint yang baru...
            $table->dropForeign(['nim']);

            // ...dan menambahkan kembali yang lama.
            $table->foreign('nim')
                ->references('nim')->on('data_diris');
        });
    }
};
