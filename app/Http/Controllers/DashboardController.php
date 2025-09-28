<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataKaryawan;
use App\Models\BerkasKaryawan;
use App\Models\KehadiranKaryawan;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (! session()->has('user_id')) {
            return redirect('/login');
        }

        // Get basic statistics using models
        $total_karyawan = DataKaryawan::count();
        $karyawan_aktif = DataKaryawan::where('status_karyawan', 'aktif')->count();
        $total_dokumen = BerkasKaryawan::count();

        // Get today's attendance statistics
        $today = date('Y-m-d');
        $kehadiran_hari_ini = KehadiranKaryawan::where('tanggal', $today)->count();
        $hadir_hari_ini = KehadiranKaryawan::where('tanggal', $today)
            ->where('status', 'hadir')
            ->count();

        // Get recent activities (last 7 days attendance)
        $recent_attendance = KehadiranKaryawan::with('dataKaryawan')
            ->where('tanggal', '>=', date('Y-m-d', strtotime('-7 days')))
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->nama = $item->dataKaryawan->nama ?? 'Unknown';
                return $item;
            });

        // Get attendance statistics by status
        $attendance_stats = KehadiranKaryawan::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $stats = [
            'total_karyawan' => $total_karyawan,
            'karyawan_aktif' => $karyawan_aktif,
            'total_dokumen' => $total_dokumen,
            'kehadiran_hari_ini' => $kehadiran_hari_ini,
            'hadir_hari_ini' => $hadir_hari_ini,
            'recent_attendance' => $recent_attendance,
            'attendance_stats' => $attendance_stats,
        ];

        return view('dashboard', compact('stats'));
    }
}