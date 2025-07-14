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
    Schema::create('rmib_hasil_tes', function (Blueprint $table) {
        $table->id('id_hasil');
        $table->unsignedBigInteger('user_id'); // Foreign key ke users
        $table->date('tanggal_pengerjaan')->nullable();

        $table->string('top_1_pekerjaan');
        $table->string('top_2_pekerjaan');
        $table->string('top_3_pekerjaan');

        $table->text('interpretasi')->nullable(); // bisa null dulu

        // Optional: snapshot user info
        $table->string('nama')->nullable();
        $table->string('nim')->nullable();
        $table->string('program_studi')->nullable();

        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

};
