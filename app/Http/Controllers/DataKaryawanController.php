<?php

namespace App\Http\Controllers;

use App\Models\DataKaryawan;
use App\Models\UserKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DataKaryawanController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is logged in
        if (! session()->has('user_id')) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu');
        }

        // Get all data for statistics (not paginated)
        $allKaryawan = DataKaryawan::all();
        
        // Calculate statistics
        $stats = [
            'total' => $allKaryawan->count(),
            'guru' => $allKaryawan->filter(function($karyawan) {
                return stripos($karyawan->jabatan, 'guru') !== false;
            })->count(),
            'staff' => $allKaryawan->filter(function($karyawan) {
                return stripos($karyawan->jabatan, 'staff') !== false;
            })->count(),
            'aktif' => $allKaryawan->where('status_karyawan', 'aktif')->count()
        ];

        // Build query for paginated data with filters
        $query = DataKaryawan::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%")
                  ->orWhere('alamat', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        if ($request->filled('status')) {
            $query->where('status_karyawan', $request->status);
        }

        // Get paginated data for display
        $karyawan = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();

        return view('pages.data-karyawan', compact('karyawan', 'stats', 'allKaryawan'));
    }
    
    public function create()
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            return redirect('/dashboard')->with('error', 'Akses ditolak');
        }
        
        return view('pages.create-karyawan');
    }
    
    public function store(Request $request)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            return redirect('/dashboard')->with('error', 'Akses ditolak');
        }
        
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'nip' => 'nullable|string|max:50|unique:tb_data_karyawan,nip',
                'jabatan' => 'nullable|string|max:100',
                'alamat' => 'nullable|string|max:500',
                'status_karyawan' => 'nullable|in:aktif,cuti,non-aktif'
            ]);

            $karyawan = DataKaryawan::create($request->only([
                'nama', 'nip', 'jabatan', 'alamat', 'status_karyawan'
            ]));

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data karyawan berhasil ditambahkan!',
                    'data' => $karyawan
                ]);
            }

            return redirect()->route('data-karyawan.index')
                            ->with('success', 'Data karyawan berhasil ditambahkan!');
                            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
                ], 422);
            }
            throw $e;
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
    
    public function show($id)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            return redirect('/dashboard')->with('error', 'Akses ditolak');
        }
        
        $karyawan = DataKaryawan::with(['user', 'riwayatPekerjaan', 'berkasKaryawan', 'kehadiranKaryawan'])
                                ->findOrFail($id);
        
        return view('pages.detail-karyawan', compact('karyawan'));
    }
    
    public function edit($id)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            return redirect('/dashboard')->with('error', 'Akses ditolak');
        }
        
        $karyawan = DataKaryawan::with('user')->findOrFail($id);
        return view('pages.edit-karyawan', compact('karyawan'));
    }
    
    public function update(Request $request, $id)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            return redirect('/dashboard')->with('error', 'Akses ditolak');
        }
        
        try {
            $karyawan = DataKaryawan::findOrFail($id);
            
            $request->validate([
                'nama' => 'required|string|max:255',
                'nip' => 'nullable|string|max:50|unique:tb_data_karyawan,nip,' . $id . ',id_karyawan',
                'jabatan' => 'nullable|string|max:100',
                'alamat' => 'nullable|string|max:500',
                'status_karyawan' => 'nullable|in:aktif,cuti,non-aktif'
            ]);

            $karyawan->update($request->only([
                'nama', 'nip', 'jabatan', 'alamat', 'status_karyawan'
            ]));

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data karyawan berhasil diperbarui!',
                    'data' => $karyawan->fresh()
                ]);
            }

            return redirect()->route('data-karyawan.index')
                            ->with('success', 'Data karyawan berhasil diperbarui!');
                            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
                ], 422);
            }
            throw $e;
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
    
    public function destroy(Request $request, $id)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            return redirect('/dashboard')->with('error', 'Akses ditolak');
        }
        
        try {
            $karyawan = DataKaryawan::findOrFail($id);
            $nama = $karyawan->nama;
            
            $karyawan->delete();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data karyawan {$nama} berhasil dihapus!"
                ]);
            }
            
            return redirect()->route('data-karyawan.index')
                            ->with('success', "Data karyawan {$nama} berhasil dihapus!");
                            
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