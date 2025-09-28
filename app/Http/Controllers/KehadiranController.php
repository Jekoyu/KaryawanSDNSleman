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

    public function create()
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            return redirect('/dashboard')->with('error', 'Akses ditolak');
        }

        // For now, redirect to index since we use modal for creating
        return redirect()->route('kehadiran.index');
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

    public function show($id)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak'
                ], 403);
            }
            return redirect('/dashboard')->with('error', 'Akses ditolak');
        }

        try {
            $kehadiran = KehadiranKaryawan::with('karyawan')->findOrFail($id);
            
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $kehadiran
                ]);
            }

            // For now, redirect to index since we don't have a show view
            return redirect()->route('kehadiran.index');

        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kehadiran tidak ditemukan'
                ], 404);
            }
            return redirect()->route('kehadiran.index')->with('error', 'Data kehadiran tidak ditemukan');
        }
    }

    public function edit($id)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        try {
            $kehadiran = KehadiranKaryawan::with('karyawan')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $kehadiran
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data kehadiran tidak ditemukan'
            ], 404);
        }
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

    public function destroy($id)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            return redirect('/kehadiran')->with('error', 'Akses ditolak');
        }

        try {
            $kehadiran = KehadiranKaryawan::findOrFail($id);
            $kehadiran->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data kehadiran berhasil dihapus!'
                ]);
            }

            return redirect()->route('kehadiran.index')
                ->with('success', 'Data kehadiran berhasil dihapus!');

        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getMonthlyReport(Request $request)
    {
        try {
            // Check if user is logged in and has proper role
            if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak'
                ], 403);
            }

            $month = $request->get('month');
            if (!$month) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter bulan diperlukan'
                ], 400);
            }

            // Parse month (format: YYYY-MM)
            $date = Carbon::createFromFormat('Y-m', $month);
            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();

            // Get all active employees
            $employees = DataKaryawan::where('status_karyawan', 'aktif')
                ->orderBy('nama')
                ->get();

            // Get attendance data for the month
            $attendanceData = KehadiranKaryawan::whereBetween('tanggal', [$startDate, $endDate])
                ->get()
                ->groupBy('id_karyawan');

            // Prepare report data
            $reportData = [];
            foreach ($employees as $employee) {
                $employeeAttendance = [];
                $employeeData = $attendanceData->get($employee->id_karyawan, collect());
                
                // Group attendance by date
                foreach ($employeeData as $attendance) {
                    $employeeAttendance[$attendance->tanggal->format('Y-m-d')] = $attendance;
                }

                $reportData[] = [
                    'id_karyawan' => $employee->id_karyawan,
                    'nama' => $employee->nama,
                    'jabatan' => $employee->jabatan,
                    'kehadiran' => $employeeAttendance
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $reportData,
                'month_info' => [
                    'month' => $month,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'days_in_month' => $endDate->day
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function setHoliday(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'keterangan' => 'nullable|string|max:255',
                'apply_to_all' => 'boolean'
            ]);

            $tanggal = $request->input('tanggal');
            $keterangan = $request->input('keterangan', 'Hari Libur');
            $applyToAll = $request->boolean('apply_to_all', false);

            if ($applyToAll) {
                // Set holiday for all employees
                $employees = DataKaryawan::all();
                $createdCount = 0;
                $updatedCount = 0;

                foreach ($employees as $employee) {
                    $attendance = KehadiranKaryawan::updateOrCreate(
                        [
                            'id_karyawan' => $employee->id_karyawan,
                            'tanggal' => $tanggal
                        ],
                        [
                            'status' => 'libur',
                            'keterangan' => $keterangan,
                            'jam_masuk' => null,
                            'jam_keluar' => null
                        ]
                    );

                    if ($attendance->wasRecentlyCreated) {
                        $createdCount++;
                    } else {
                        $updatedCount++;
                    }
                }

                $message = "Berhasil mengatur libur untuk {$createdCount} karyawan baru";
                if ($updatedCount > 0) {
                    $message .= " dan memperbarui {$updatedCount} data yang sudah ada";
                }
                $message .= " pada tanggal " . Carbon::parse($tanggal)->format('d M Y');

            } else {
                // Set holiday for selected employees (this would require additional UI implementation)
                // For now, we'll just return an error message
                return response()->json([
                    'success' => false,
                    'message' => 'Fitur pengaturan libur untuk karyawan tertentu belum tersedia. Silakan gunakan opsi "Terapkan untuk semua karyawan".'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}