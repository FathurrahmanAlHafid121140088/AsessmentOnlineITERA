<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update "Tukang Kayu" menjadi "Carpenter/ Pengrajin Kayu"
        DB::table('rmib_pekerjaan')
            ->where('nama_pekerjaan', 'Tukang Kayu')
            ->update(['nama_pekerjaan' => 'Carpenter/ Pengrajin Kayu']);

        // Update "Ahli Gosok Lensa/ Lens Grinder" menjadi "Ahli Optik"
        DB::table('rmib_pekerjaan')
            ->where('nama_pekerjaan', 'Ahli Gosok Lensa/ Lens Grinder')
            ->update(['nama_pekerjaan' => 'Ahli Optik']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert "Carpenter/ Pengrajin Kayu" back to "Tukang Kayu"
        DB::table('rmib_pekerjaan')
            ->where('nama_pekerjaan', 'Carpenter/ Pengrajin Kayu')
            ->update(['nama_pekerjaan' => 'Tukang Kayu']);

        // Revert "Ahli Optik" back to "Ahli Gosok Lensa/ Lens Grinder"
        DB::table('rmib_pekerjaan')
            ->where('nama_pekerjaan', 'Ahli Optik')
            ->update(['nama_pekerjaan' => 'Ahli Gosok Lensa/ Lens Grinder']);
    }
};
