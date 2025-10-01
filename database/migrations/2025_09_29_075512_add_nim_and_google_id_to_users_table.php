<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom 'nim' setelah kolom 'email'.
            // unique() memastikan tidak ada NIM yang duplikat.
            $table->string('nim')->unique()->after('email');

            // Tambahkan kolom 'google_id' setelah kolom 'nim'.
            // nullable() berarti kolom ini boleh kosong.
            //$table->string('google_id')->nullable()->after('nim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn(['nim', 'google_id']);
        });
    }
};