<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_user', function (Blueprint $table) {
            $table->increments('id_user');
            $table->unsignedInteger('id_karyawan')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('peran')->nullable();
            $table->timestamps();

            $table->foreign('id_karyawan')->references('id_karyawan')->on('tb_data_karyawan')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_user');
    }
};
