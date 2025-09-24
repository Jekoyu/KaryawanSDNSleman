<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataKaryawan;
use App\Models\UserKaryawan;
use Illuminate\Support\Facades\Hash;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $karyawan = [
            [
                'nama' => 'Siti Nurhaliza',
                'nip' => '196801011990032001',
                'email' => 'siti.nurhaliza@example.com',
                'nomor_telepon' => '081234567890',
                'alamat' => 'Jl. Pendidikan No. 1, Sleman, Yogyakarta',
                'tanggal_lahir' => '1968-01-01',
                'jenis_kelamin' => 'P',
                'jabatan' => 'Kepala Sekolah',
                'tanggal_masuk' => '1990-03-01',
                'status_karyawan' => 'aktif',
                'username' => 'siti.nurhaliza',
                'peran' => 'admin'
            ],
            [
                'nama' => 'Ahmad Wijaya',
                'nip' => '197505152000031002',
                'email' => 'ahmad.wijaya@example.com',
                'nomor_telepon' => '081234567891',
                'alamat' => 'Jl. Mawar No. 15, Sleman, Yogyakarta',
                'tanggal_lahir' => '1975-05-15',
                'jenis_kelamin' => 'L',
                'jabatan' => 'Guru Kelas 1A',
                'tanggal_masuk' => '2000-03-15',
                'status_karyawan' => 'aktif',
                'username' => 'ahmad.wijaya',
                'peran' => 'karyawan'
            ],
            [
                'nama' => 'Rina Sari',
                'nip' => '198003102005012003',
                'email' => 'rina.sari@example.com',
                'nomor_telepon' => '081234567892',
                'alamat' => 'Jl. Melati No. 8, Sleman, Yogyakarta',
                'tanggal_lahir' => '1980-03-10',
                'jenis_kelamin' => 'P',
                'jabatan' => 'Guru Kelas 2B',
                'tanggal_masuk' => '2005-01-10',
                'status_karyawan' => 'aktif',
                'username' => 'rina.sari',
                'peran' => 'karyawan'
            ],
            [
                'nama' => 'Budi Santoso',
                'nip' => '197712252002121001',
                'email' => 'budi.santoso@example.com',
                'nomor_telepon' => '081234567893',
                'alamat' => 'Jl. Anggrek No. 22, Sleman, Yogyakarta',
                'tanggal_lahir' => '1977-12-25',
                'jenis_kelamin' => 'L',
                'jabatan' => 'Guru Kelas 3A',
                'tanggal_masuk' => '2002-12-01',
                'status_karyawan' => 'aktif',
                'username' => 'budi.santoso',
                'peran' => 'karyawan'
            ],
            [
                'nama' => 'Dewi Lestari',
                'nip' => '198209182007012004',
                'email' => 'dewi.lestari@example.com',
                'nomor_telepon' => '081234567894',
                'alamat' => 'Jl. Kenanga No. 5, Sleman, Yogyakarta',
                'tanggal_lahir' => '1982-09-18',
                'jenis_kelamin' => 'P',
                'jabatan' => 'Guru Kelas 4B',
                'tanggal_masuk' => '2007-01-15',
                'status_karyawan' => 'aktif',
                'username' => 'dewi.lestari',
                'peran' => 'karyawan'
            ],
            [
                'nama' => 'Agus Prasetyo',
                'nip' => '197904051999031001',
                'email' => 'agus.prasetyo@example.com',
                'nomor_telepon' => '081234567895',
                'alamat' => 'Jl. Cempaka No. 12, Sleman, Yogyakarta',
                'tanggal_lahir' => '1979-04-05',
                'jenis_kelamin' => 'L',
                'jabatan' => 'Guru Kelas 5A',
                'tanggal_masuk' => '1999-03-01',
                'status_karyawan' => 'aktif',
                'username' => 'agus.prasetyo',
                'peran' => 'karyawan'
            ],
            [
                'nama' => 'Maya Sari',
                'nip' => '198506302010012005',
                'email' => 'maya.sari@example.com',
                'nomor_telepon' => '081234567896',
                'alamat' => 'Jl. Flamboyan No. 7, Sleman, Yogyakarta',
                'tanggal_lahir' => '1985-06-30',
                'jenis_kelamin' => 'P',
                'jabatan' => 'Guru Kelas 6B',
                'tanggal_masuk' => '2010-01-11',
                'status_karyawan' => 'aktif',
                'username' => 'maya.sari',
                'peran' => 'karyawan'
            ],
            [
                'nama' => 'Hendra Kurniawan',
                'nip' => '198011202003121002',
                'email' => 'hendra.kurniawan@example.com',
                'nomor_telepon' => '081234567897',
                'alamat' => 'Jl. Dahlia No. 18, Sleman, Yogyakarta',
                'tanggal_lahir' => '1980-11-20',
                'jenis_kelamin' => 'L',
                'jabatan' => 'Guru Penjas',
                'tanggal_masuk' => '2003-12-15',
                'status_karyawan' => 'aktif',
                'username' => 'hendra.kurniawan',
                'peran' => 'karyawan'
            ],
            [
                'nama' => 'Indira Putri',
                'nip' => '198712142012012006',
                'email' => 'indira.putri@example.com',
                'nomor_telepon' => '081234567898',
                'alamat' => 'Jl. Bougenville No. 3, Sleman, Yogyakarta',
                'tanggal_lahir' => '1987-12-14',
                'jenis_kelamin' => 'P',
                'jabatan' => 'Guru Seni Budaya',
                'tanggal_masuk' => '2012-01-16',
                'status_karyawan' => 'aktif',
                'username' => 'indira.putri',
                'peran' => 'karyawan'
            ],
            [
                'nama' => 'Joko Susilo',
                'nip' => '197608082001031003',
                'email' => 'joko.susilo@example.com',
                'nomor_telepon' => '081234567899',
                'alamat' => 'Jl. Teratai No. 25, Sleman, Yogyakarta',
                'tanggal_lahir' => '1976-08-08',
                'jenis_kelamin' => 'L',
                'jabatan' => 'Staff Administrasi',
                'tanggal_masuk' => '2001-03-20',
                'status_karyawan' => 'aktif',
                'username' => 'joko.susilo',
                'peran' => 'karyawan'
            ],
            [
                'nama' => 'Kartika Sari',
                'nip' => '198901052014012007',
                'email' => 'kartika.sari@example.com',
                'nomor_telepon' => '081234567800',
                'alamat' => 'Jl. Kamboja No. 10, Sleman, Yogyakarta',
                'tanggal_lahir' => '1989-01-05',
                'jenis_kelamin' => 'P',
                'jabatan' => 'Staff Perpustakaan',
                'tanggal_masuk' => '2014-01-02',
                'status_karyawan' => 'cuti',
                'username' => 'kartika.sari',
                'peran' => 'karyawan'
            ],
            [
                'nama' => 'Lukman Hakim',
                'nip' => '198304172008031001',
                'email' => 'lukman.hakim@example.com',
                'nomor_telepon' => '081234567801',
                'alamat' => 'Jl. Lily No. 14, Sleman, Yogyakarta',
                'tanggal_lahir' => '1983-04-17',
                'jenis_kelamin' => 'L',
                'jabatan' => 'Penjaga Sekolah',
                'tanggal_masuk' => '2008-03-10',
                'status_karyawan' => 'aktif',
                'username' => 'lukman.hakim',
                'peran' => 'karyawan'
            ]
        ];

        foreach ($karyawan as $data) {
            // Create karyawan record (hanya gunakan kolom yang ada di tabel)
            $kry = DataKaryawan::create([
                'nama' => $data['nama'],
                'nip' => $data['nip'],
                'jabatan' => $data['jabatan'],
                'alamat' => $data['alamat'],
                'status_karyawan' => $data['status_karyawan'],
            ]);

            // Create user account
            UserKaryawan::create([
                'id_karyawan' => $kry->id_karyawan,
                'username' => $data['username'],
                'password' => Hash::make('password123'),
                'peran' => $data['peran'],
            ]);
        }
    }
}
