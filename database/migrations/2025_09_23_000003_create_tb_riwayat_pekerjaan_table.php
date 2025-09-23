<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_riwayat_pekerjaan', function (Blueprint $table) {
            $table->increments('id_riwayat_pekerjaan');
            $table->unsignedInteger('id_karyawan');
            $table->string('nama_perusahaan')->nullable();
            $table->string('jabatan_lama')->nullable();
            $table->string('tahun_kerja')->nullable();
            $table->timestamps();

            $table->foreign('id_karyawan')->references('id_karyawan')->on('tb_data_karyawan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_riwayat_pekerjaan');
    }
};
