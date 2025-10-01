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
            // 1. Ubah nama kolom 'username' menjadi 'name'
            $table->renameColumn('username', 'name');

            // 2. Tambahkan kolom untuk Google ID
            $table->string('google_id')->nullable()->unique()->after('email');

            // 3. Buat kolom password agar bisa kosong (nullable)
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'username');
            $table->dropColumn('google_id');
            $table->string('password')->nullable(false)->change();
        });
    }
};