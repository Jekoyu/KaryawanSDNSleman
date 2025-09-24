<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (! session()->has('user_id')) {
            return redirect('/login');
        }

        // Get statistics from database
        $stats = [
            'total_karyawan' => DB::table('tb_data_karyawan')->count(),
            'karyawan_aktif' => DB::table('tb_data_karyawan')->where('status_karyawan', 'aktif')->count(),
            'total_dokumen' => DB::table('tb_berkas_karyawan')->count(),
            'total_kehadiran' => DB::table('tb_kehadiran_karyawan')->count(),
        ];

        return view('dashboard', compact('stats'));
    }
}