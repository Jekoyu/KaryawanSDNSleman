<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure our application seed order: data karyawan -> users -> attendance
        $this->call(AdminUserSeeder::class);
        // $this->call(SampleDataSeeder::class);
        // $this->call(BerkasKaryawanSeeder::class);
        $this->call(KaryawanSeeder::class);
        $this->call(KehadiranKaryawanSeeder::class);
    }
}
