<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
        {
            Schema::table('data_diris', function (Blueprint $table) {
                $table->string('email')->after('program_studi');
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_diris', function (Blueprint $table) {
            //
        });
    }
};
