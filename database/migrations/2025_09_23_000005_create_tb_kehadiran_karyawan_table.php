<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_kehadiran_karyawan', function (Blueprint $table) {
            $table->increments('id_kehadiran');
            $table->unsignedInteger('id_karyawan');
            $table->date('tanggal');
            $table->string('status')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_karyawan')->references('id_karyawan')->on('tb_data_karyawan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_kehadiran_karyawan');
    }
};
