<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create a superadmin user in tb_user and a corresponding tb_data_karyawan record
        $idKaryawan = DB::table('tb_data_karyawan')->insertGetId([
            'nip' => '000000',
            'nama' => 'Super Admin',
            'jabatan' => 'Administrator',
            'alamat' => 'Headquarters',
            'status_karyawan' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tb_user')->insert([
            'id_karyawan' => $idKaryawan,
            'username' => 'superadmin',
            'password' => Hash::make('superadmin'),
            'peran' => 'superadmin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
