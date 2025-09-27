<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatPekerjaan;
use App\Models\DataKaryawan;

class RiwayatPekerjaanSeeder extends Seeder
{
    public function run(): void
    {
        // Get some karyawan data
        $karyawan = DataKaryawan::where('status_karyawan', 'aktif')->take(5)->get();

        if ($karyawan->count() > 0) {
            $riwayatData = [
                [
                    'id_karyawan' => $karyawan[0]->id_karyawan,
                    'nama_perusahaan' => 'SD Negeri 2 Yogyakarta',
                    'jabatan_lama' => 'Guru Kelas',
                    'tahun_kerja' => '2015-2020'
                ],
                [
                    'id_karyawan' => $karyawan[1]->id_karyawan ?? $karyawan[0]->id_karyawan,
                    'nama_perusahaan' => 'Dinas Pendidikan Sleman',
                    'jabatan_lama' => 'Staff Administrasi',
                    'tahun_kerja' => '2018-2022'
                ],
                [
                    'id_karyawan' => $karyawan[2]->id_karyawan ?? $karyawan[0]->id_karyawan,
                    'nama_perusahaan' => 'SMP Negeri 1 Sleman',
                    'jabatan_lama' => 'Guru Matematika',
                    'tahun_kerja' => '2010-2018'
                ],
                [
                    'id_karyawan' => $karyawan[3]->id_karyawan ?? $karyawan[0]->id_karyawan,
                    'nama_perusahaan' => 'Kursus Bimbel Cemerlang',
                    'jabatan_lama' => 'Tutor',
                    'tahun_kerja' => '2016-2019'
                ],
                [
                    'id_karyawan' => $karyawan[4]->id_karyawan ?? $karyawan[0]->id_karyawan,
                    'nama_perusahaan' => 'Toko Buku Pendidikan',
                    'jabatan_lama' => 'Sales',
                    'tahun_kerja' => '2012-2015'
                ]
            ];

            foreach ($riwayatData as $data) {
                RiwayatPekerjaan::create($data);
            }
        }
    }
}