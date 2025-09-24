<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data first
        DB::table('tb_user')->where('id_user', '>', 1)->delete(); // Keep admin user
        DB::table('tb_data_karyawan')->delete();

        // Add sample karyawan data (adjust according to actual table structure)
        $karyawanData = [
            [
                'nip' => '196801151992031005',
                'nama' => 'Budi Santoso',
                'alamat' => 'Jl. Malioboro No. 123, Yogyakarta',
                'jabatan' => 'Guru Kelas IV',
                'status_karyawan' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '197203101995121003',
                'nama' => 'Ahmad Wijaya',
                'alamat' => 'Jl. Kaliurang No. 789, Sleman',
                'jabatan' => 'Staff Administrasi',
                'status_karyawan' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '198505122009022004',
                'nama' => 'Siti Purwanti',
                'alamat' => 'Jl. Solo No. 456, Bantul',
                'jabatan' => 'Guru Olahraga',
                'status_karyawan' => 'cuti',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '197512081998032002',
                'nama' => 'Dewi Sartika',
                'alamat' => 'Jl. Imogiri No. 321, Yogyakarta',
                'jabatan' => 'Kepala Sekolah',
                'status_karyawan' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '198803152012012008',
                'nama' => 'Rini Pratiwi',
                'alamat' => 'Jl. Magelang No. 567, Klaten',
                'jabatan' => 'Guru Kelas II',
                'status_karyawan' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '198010202005011004',
                'nama' => 'Agus Setiawan',
                'alamat' => 'Jl. Wonosari No. 234, Gunungkidul',
                'jabatan' => 'Guru Matematika',
                'status_karyawan' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '199204182016022003',
                'nama' => 'Maya Sari',
                'alamat' => 'Jl. Wates No. 890, Kulon Progo',
                'jabatan' => 'Guru Bahasa Indonesia',
                'status_karyawan' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '197707252000121001',
                'nama' => 'Bambang Sutrisno',
                'alamat' => 'Jl. Borobudur No. 345, Magelang',
                'jabatan' => 'Guru IPA',
                'status_karyawan' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '198906102014032005',
                'nama' => 'Lestari Wulandari',
                'alamat' => 'Jl. Pakem No. 678, Sleman',
                'jabatan' => 'Guru Seni Budaya',
                'status_karyawan' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '198112152007011002',
                'nama' => 'Eko Prasetyo',
                'alamat' => 'Jl. Purworejo No. 123, Purworejo',
                'jabatan' => 'Guru Agama',
                'status_karyawan' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '199011082018022001',
                'nama' => 'Fitri Handayani',
                'alamat' => 'Jl. Temanggung No. 456, Temanggung',
                'jabatan' => 'Pustakawan',
                'status_karyawan' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '197808202002121003',
                'nama' => 'Joko Widodo',
                'alamat' => 'Jl. Kebumen No. 789, Kebumen',
                'jabatan' => 'Security',
                'status_karyawan' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert karyawan data and get the inserted IDs
        $insertedKaryawan = [];
        foreach ($karyawanData as $karyawan) {
            $idKaryawan = DB::table('tb_data_karyawan')->insertGetId($karyawan);
            $insertedKaryawan[] = ['id' => $idKaryawan, 'nama' => $karyawan['nama']];
        }

        // Add users for some karyawan (for login purposes)
        $userData = [
            [
                'id_karyawan' => $insertedKaryawan[0]['id'], // Budi Santoso
                'username' => 'budi.santoso',
                'password' => bcrypt('password123'),
                'peran' => 'karyawan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_karyawan' => $insertedKaryawan[1]['id'], // Ahmad Wijaya  
                'username' => 'ahmad.wijaya',
                'password' => bcrypt('password123'),
                'peran' => 'karyawan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_karyawan' => $insertedKaryawan[3]['id'], // Dewi Sartika (Kepala Sekolah)
                'username' => 'dewi.sartika',
                'password' => bcrypt('password123'),
                'peran' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tb_user')->insert($userData);

        // Add sample berkas for some karyawan
        $berkasData = [];
        foreach ($insertedKaryawan as $karyawan) {
            $berkasData[] = [
                'id_karyawan' => $karyawan['id'],
                'nama_berkas' => 'CV',
                'files' => 'cv_' . strtolower(str_replace(' ', '_', $karyawan['nama'])) . '.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $berkasData[] = [
                'id_karyawan' => $karyawan['id'],
                'nama_berkas' => 'Ijazah',
                'files' => 'ijazah_' . strtolower(str_replace(' ', '_', $karyawan['nama'])) . '.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('tb_berkas_karyawan')->insert($berkasData);

        // Add sample kehadiran for each karyawan (last 7 days)
        $kehadiranData = [];
        foreach ($insertedKaryawan as $karyawan) {
            for ($i = 6; $i >= 0; $i--) {
                $kehadiranData[] = [
                    'id_karyawan' => $karyawan['id'],
                    'tanggal' => now()->subDays($i)->format('Y-m-d'),
                    'status' => rand(0, 10) > 1 ? 'hadir' : 'tidak hadir', // 90% hadir
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('tb_kehadiran_karyawan')->insert($kehadiranData);
    }
}