<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_data_karyawan', function (Blueprint $table) {
            $table->increments('id_karyawan');
            $table->string('nip')->nullable()->unique();
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('status_karyawan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_data_karyawan');
    }
};
