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
        // Ensure our application seed order: data karyawan -> users
        $this->call(AdminUserSeeder::class);
        $this->call(SampleDataSeeder::class);
    }
}
