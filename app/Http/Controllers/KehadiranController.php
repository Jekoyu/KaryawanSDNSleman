<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KehadiranKaryawan;
use App\Models\DataKaryawan;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            return redirect('/dashboard')->with('error', 'Akses ditolak');
        }

        // Get filter date (default: today)
        $filterDate = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $filterMonth = $request->get('bulan', Carbon::today()->format('Y-m'));

        // Get kehadiran for today with pagination
        $kehadiran = KehadiranKaryawan::with('karyawan')
            ->byDate($filterDate)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get all karyawan for batch input
        $allKaryawan = DataKaryawan::where('status_karyawan', 'aktif')
            ->orderBy('nama')
            ->get();

        // Get statistics for the selected date
        $stats = $this->getDailyStats($filterDate);

        // Get status options
        $statusOptions = KehadiranKaryawan::getStatusOptions();

        return view('pages.kehadiran', compact(
            'kehadiran',
            'allKaryawan', 
            'filterDate',
            'filterMonth',
            'stats',
            'statusOptions'
        ));
    }

    public function store(Request $request)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            return redirect('/kehadiran')->with('error', 'Akses ditolak');
        }

        try {
            $request->validate([
                'tanggal' => 'required|date',
                'kehadiran' => 'required|array',
                'kehadiran.*.id_karyawan' => 'required|exists:tb_data_karyawan,id_karyawan',
                'kehadiran.*.status' => 'required|in:hadir,terlambat,sakit,izin,alfa,cuti',
            ]);

            $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
            $successCount = 0;
            $updateCount = 0;

            foreach ($request->kehadiran as $item) {
                // Check if record already exists
                $existing = KehadiranKaryawan::where('id_karyawan', $item['id_karyawan'])
                    ->where('tanggal', $tanggal)
                    ->first();

                if ($existing) {
                    // Update existing record
                    $existing->update(['status' => $item['status']]);
                    $updateCount++;
                } else {
                    // Create new record
                    KehadiranKaryawan::create([
                        'id_karyawan' => $item['id_karyawan'],
                        'tanggal' => $tanggal,
                        'status' => $item['status']
                    ]);
                    $successCount++;
                }
            }

            $message = "Berhasil menyimpan {$successCount} data kehadiran baru";
            if ($updateCount > 0) {
                $message .= " dan memperbarui {$updateCount} data yang sudah ada";
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'created' => $successCount,
                        'updated' => $updateCount
                    ]
                ]);
            }

            return redirect()->route('kehadiran.index')
                ->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getKehadiranByDate(Request $request)
    {
        try {
            $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
            
            // Get existing attendance for the date
            $kehadiran = KehadiranKaryawan::with('karyawan')
                ->byDate($tanggal)
                ->get()
                ->keyBy('id_karyawan');

            // Get all active employees
            $karyawan = DataKaryawan::where('status_karyawan', 'aktif')
                ->orderBy('nama')
                ->get();

            $result = $karyawan->map(function($k) use ($kehadiran) {
                $attendance = $kehadiran->get($k->id_karyawan);
                return [
                    'id_karyawan' => $k->id_karyawan,
                    'nama' => $k->nama,
                    'jabatan' => $k->jabatan,
                    'status' => $attendance ? $attendance->status : null
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getDailyStats($date)
    {
        $stats = KehadiranKaryawan::byDate($date)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'hadir' => $stats['hadir'] ?? 0,
            'terlambat' => $stats['terlambat'] ?? 0,
            'sakit' => $stats['sakit'] ?? 0,
            'izin' => $stats['izin'] ?? 0,
            'alfa' => $stats['alfa'] ?? 0,
            'cuti' => $stats['cuti'] ?? 0,
        ];
    }

    private function getMonthlyStats($year, $month)
    {
        $stats = KehadiranKaryawan::byMonth($year, $month)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'hadir' => $stats['hadir'] ?? 0,
            'terlambat' => $stats['terlambat'] ?? 0,
            'sakit' => $stats['sakit'] ?? 0,
            'izin' => $stats['izin'] ?? 0,
            'alfa' => $stats['alfa'] ?? 0,
            'cuti' => $stats['cuti'] ?? 0,
        ];
    }

    public function update(Request $request, $id)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            return redirect('/kehadiran')->with('error', 'Akses ditolak');
        }

        try {
            $kehadiran = KehadiranKaryawan::findOrFail($id);

            $request->validate([
                'status' => 'required|in:hadir,terlambat,sakit,izin,alfa,cuti',
                'keterangan' => 'nullable|string|max:255'
            ]);

            $kehadiran->update([
                'status' => $request->status,
                'keterangan' => $request->keterangan
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status kehadiran berhasil diperbarui!',
                    'data' => $kehadiran->load('karyawan')
                ]);
            }

            return redirect()->route('kehadiran.index')
                ->with('success', 'Status kehadiran berhasil diperbarui!');

        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}