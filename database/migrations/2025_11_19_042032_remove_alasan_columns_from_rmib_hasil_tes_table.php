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
        Schema::table('rmib_hasil_tes', function (Blueprint $table) {
            $table->dropColumn([
                'top_1_alasan',
                'top_2_alasan',
                'top_3_alasan',
                'pekerjaan_lain_alasan'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rmib_hasil_tes', function (Blueprint $table) {
            $table->text('top_1_alasan')->nullable()->after('top_1_pekerjaan');
            $table->text('top_2_alasan')->nullable()->after('top_2_pekerjaan');
            $table->text('top_3_alasan')->nullable()->after('top_3_pekerjaan');
            $table->text('pekerjaan_lain_alasan')->nullable()->after('pekerjaan_lain');
        });
    }
};
