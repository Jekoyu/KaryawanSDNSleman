<?php

namespace App\Http\Controllers;

use App\Models\BerkasKaryawan;
use App\Models\DataKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is logged in
        if (! session()->has('user_id')) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu');
        }

        // Build query for documents with filters
        $query = BerkasKaryawan::with('karyawan');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_berkas', 'LIKE', "%{$search}%")
                  ->orWhereHas('karyawan', function($q2) use ($search) {
                      $q2->where('nama', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('jenis')) {
            $jenis = $request->jenis;
            $query->where('nama_berkas', 'LIKE', "%{$jenis}%");
        }

        if ($request->filled('karyawan')) {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->where('nama', 'LIKE', "%{$request->karyawan}%");
            });
        }

        // Get paginated data for display
        $dokumen = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Get all documents for JavaScript (for modals)
        $allDokumen = BerkasKaryawan::with('karyawan')->get();

        // Get all karyawan for select options
        $allKaryawan = DataKaryawan::orderBy('nama', 'asc')->get();

        return view('pages.dokumen', compact('dokumen', 'allDokumen', 'allKaryawan'));
    }

    public function store(Request $request)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            return redirect('/dokumen')->with('error', 'Akses ditolak');
        }

        try {
            $request->validate([
                'nama_berkas' => 'required|string|max:255',
                'id_karyawan' => 'required|exists:tb_data_karyawan,id_karyawan',
                'file_dokumen' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
            ]);

            // Handle file upload
            $fileName = null;
            if ($request->hasFile('file_dokumen')) {
                $file = $request->file('file_dokumen');
                
                // Get karyawan name for better file organization
                $karyawan = \App\Models\DataKaryawan::find($request->id_karyawan);
                $karyawanName = $karyawan ? Str::slug($karyawan->nama) : 'unknown';
                
                // Create unique filename with multiple layers of uniqueness:
                // 1. Karyawan name prefix
                // 2. Document type/name
                // 3. Timestamp
                // 4. Random hash (first 8 characters of md5)
                // 5. Original extension
                $timestamp = now()->format('YmdHis');
                $randomHash = substr(md5(uniqid(rand(), true)), 0, 8);
                $documentSlug = Str::slug($request->nama_berkas);
                $extension = $file->getClientOriginalExtension();
                
                $fileName = "{$karyawanName}_{$documentSlug}_{$timestamp}_{$randomHash}.{$extension}";
                
                // Create directory structure: berkas/karyawan_name/year/
                $directory = "berkas/{$karyawanName}/" . now()->format('Y');
                
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }
                
                // Store file in organized directory
                $file->storeAs($directory, $fileName, 'public');
                
                // Store the full path in database for easy retrieval
                $fileName = "{$directory}/{$fileName}";
            }

            $berkas = BerkasKaryawan::create([
                'id_karyawan' => $request->id_karyawan,
                'nama_berkas' => $request->nama_berkas,
                'files' => $fileName
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen berhasil ditambahkan!',
                    'data' => $berkas->load('karyawan')
                ]);
            }

            return redirect()->route('dokumen.index')
                            ->with('success', 'Dokumen berhasil ditambahkan!');

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

    public function show($id)
    {
        try {
            $berkas = BerkasKaryawan::with('karyawan')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $berkas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak ditemukan'
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
            return redirect('/dokumen')->with('error', 'Akses ditolak');
        }

        try {
            $berkas = BerkasKaryawan::findOrFail($id);

            $request->validate([
                'nama_berkas' => 'required|string|max:255',
                'id_karyawan' => 'required|exists:tb_data_karyawan,id_karyawan',
                'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
            ]);

            // Handle file upload if new file is provided
            if ($request->hasFile('file_dokumen')) {
                // Delete old file
                if ($berkas->files && Storage::disk('public')->exists($berkas->files)) {
                    Storage::disk('public')->delete($berkas->files);
                }

                // Upload new file with same naming convention
                $file = $request->file('file_dokumen');
                
                // Get karyawan name for better file organization
                $karyawan = \App\Models\DataKaryawan::find($request->id_karyawan);
                $karyawanName = $karyawan ? Str::slug($karyawan->nama) : 'unknown';
                
                // Create unique filename
                $timestamp = now()->format('YmdHis');
                $randomHash = substr(md5(uniqid(rand(), true)), 0, 8);
                $documentSlug = Str::slug($request->nama_berkas);
                $extension = $file->getClientOriginalExtension();
                
                $fileName = "{$karyawanName}_{$documentSlug}_{$timestamp}_{$randomHash}.{$extension}";
                
                // Create directory structure
                $directory = "berkas/{$karyawanName}/" . now()->format('Y');
                
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }
                
                // Store file in organized directory
                $file->storeAs($directory, $fileName, 'public');
                
                // Store the full path in database
                $berkas->files = "{$directory}/{$fileName}";
            }

            $berkas->update([
                'id_karyawan' => $request->id_karyawan,
                'nama_berkas' => $request->nama_berkas,
                'files' => $berkas->files
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen berhasil diperbarui!',
                    'data' => $berkas->load('karyawan')
                ]);
            }

            return redirect()->route('dokumen.index')
                            ->with('success', 'Dokumen berhasil diperbarui!');

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

    public function destroy($id)
    {
        // Check if user is logged in and has proper role
        if (! session()->has('user_id') || !in_array(session('peran'), ['superadmin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        try {
            $berkas = BerkasKaryawan::findOrFail($id);

            // Delete file from storage
            if ($berkas->files && Storage::disk('public')->exists($berkas->files)) {
                Storage::disk('public')->delete($berkas->files);
            }

            $berkas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($id)
    {
        try {
            $berkas = BerkasKaryawan::findOrFail($id);
            
            if (!$berkas->files || !Storage::disk('public')->exists($berkas->files)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan'
                ], 404);
            }

            $filePath = storage_path('app/public/' . $berkas->files);
            $fileName = $berkas->nama_berkas . '.' . pathinfo($berkas->files, PATHINFO_EXTENSION);

            return response()->download($filePath, $fileName);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
