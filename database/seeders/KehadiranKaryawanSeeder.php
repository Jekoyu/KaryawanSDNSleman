<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KehadiranKaryawan;
use App\Models\DataKaryawan;
use Carbon\Carbon;

class KehadiranKaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active employees
        $karyawan = DataKaryawan::where('status_karyawan', 'aktif')->get();
        
        $statusOptions = ['hadir', 'terlambat', 'sakit', 'izin', 'alfa'];
        
        // Generate attendance data for the last 30 days
        for ($i = 0; $i < 30; $i++) {
            $tanggal = Carbon::today()->subDays($i);
            
            // Skip weekends (Saturday = 6, Sunday = 0)
            if ($tanggal->dayOfWeek == 0 || $tanggal->dayOfWeek == 6) {
                continue;
            }
            
            foreach ($karyawan as $k) {
                // 85% chance of attendance (hadir/terlambat)
                $rand = rand(1, 100);
                
                if ($rand <= 70) {
                    $status = 'hadir';
                } elseif ($rand <= 80) {
                    $status = 'terlambat';
                } elseif ($rand <= 90) {
                    $status = 'sakit';
                } elseif ($rand <= 95) {
                    $status = 'izin';
                } else {
                    $status = 'alfa';
                }
                
                $keterangan = null;
                if ($status == 'sakit') {
                    $keterangan = 'Sakit demam';
                } elseif ($status == 'izin') {
                    $keterangan = 'Keperluan keluarga';
                } elseif ($status == 'terlambat') {
                    $keterangan = 'Macet lalu lintas';
                }
                
                KehadiranKaryawan::create([
                    'id_karyawan' => $k->id_karyawan,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);
            }
        }
    }
}