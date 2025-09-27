<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPekerjaan;
use App\Models\DataKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RiwayatPekerjaanController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            return redirect('/dashboard')->with('error', 'Akses ditolak');
        }

        // Get all karyawan with their riwayat pekerjaan count
        $karyawan = DataKaryawan::where('status_karyawan', 'aktif')
            ->withCount('riwayatPekerjaan')
            ->orderBy('nama')
            ->paginate(12);

        // Get all karyawan for dropdown (for add modal)
        $allKaryawan = DataKaryawan::where('status_karyawan', 'aktif')
            ->orderBy('nama')
            ->get();

        return view('pages.riwayat-pekerjaan', compact('karyawan', 'allKaryawan'));
    }

    public function store(Request $request)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            return redirect('/riwayat-pekerjaan')->with('error', 'Akses ditolak');
        }

        try {
            $validator = Validator::make($request->all(), [
                'id_karyawan' => 'required|exists:tb_data_karyawan,id_karyawan',
                'nama_perusahaan' => 'required|string|max:255',
                'jabatan_lama' => 'required|string|max:255',
                'tahun_kerja' => 'required|string|max:50'
            ], [
                'id_karyawan.required' => 'Karyawan harus dipilih',
                'id_karyawan.exists' => 'Karyawan tidak valid',
                'nama_perusahaan.required' => 'Nama perusahaan harus diisi',
                'jabatan_lama.required' => 'Jabatan harus diisi',
                'tahun_kerja.required' => 'Tahun kerja harus diisi'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $riwayatPekerjaan = RiwayatPekerjaan::create([
                'id_karyawan' => $request->id_karyawan,
                'nama_perusahaan' => $request->nama_perusahaan,
                'jabatan_lama' => $request->jabatan_lama,
                'tahun_kerja' => $request->tahun_kerja
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Riwayat pekerjaan berhasil ditambahkan!',
                    'data' => $riwayatPekerjaan->load('karyawan')
                ]);
            }

            return redirect()->route('riwayat-pekerjaan.index')
                ->with('success', 'Riwayat pekerjaan berhasil ditambahkan!');

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

    public function edit($id)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        try {
            $riwayatPekerjaan = RiwayatPekerjaan::with('karyawan')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $riwayatPekerjaan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pekerjaan tidak ditemukan'
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
            return redirect('/riwayat-pekerjaan')->with('error', 'Akses ditolak');
        }

        try {
            $riwayatPekerjaan = RiwayatPekerjaan::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'id_karyawan' => 'required|exists:tb_data_karyawan,id_karyawan',
                'nama_perusahaan' => 'required|string|max:255',
                'jabatan_lama' => 'required|string|max:255',
                'tahun_kerja' => 'required|string|max:50'
            ], [
                'id_karyawan.required' => 'Karyawan harus dipilih',
                'id_karyawan.exists' => 'Karyawan tidak valid',
                'nama_perusahaan.required' => 'Nama perusahaan harus diisi',
                'jabatan_lama.required' => 'Jabatan harus diisi',
                'tahun_kerja.required' => 'Tahun kerja harus diisi'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $riwayatPekerjaan->update([
                'id_karyawan' => $request->id_karyawan,
                'nama_perusahaan' => $request->nama_perusahaan,
                'jabatan_lama' => $request->jabatan_lama,
                'tahun_kerja' => $request->tahun_kerja
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Riwayat pekerjaan berhasil diperbarui!',
                    'data' => $riwayatPekerjaan->load('karyawan')
                ]);
            }

            return redirect()->route('riwayat-pekerjaan.index')
                ->with('success', 'Riwayat pekerjaan berhasil diperbarui!');

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

    public function getByKaryawan($idKaryawan)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        try {
            $karyawan = DataKaryawan::findOrFail($idKaryawan);
            $riwayatPekerjaan = RiwayatPekerjaan::where('id_karyawan', $idKaryawan)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'karyawan' => $karyawan,
                    'riwayat_pekerjaan' => $riwayatPekerjaan
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function destroy($id)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        try {
            $riwayatPekerjaan = RiwayatPekerjaan::findOrFail($id);
            $karyawanNama = $riwayatPekerjaan->karyawan->nama ?? 'Unknown';
            
            $riwayatPekerjaan->delete();

            return response()->json([
                'success' => true,
                'message' => "Riwayat pekerjaan {$karyawanNama} berhasil dihapus!"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}