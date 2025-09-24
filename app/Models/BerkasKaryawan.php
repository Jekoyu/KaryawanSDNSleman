<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasKaryawan extends Model
{
    use HasFactory;

    protected $table = 'tb_berkas_karyawan';
    protected $primaryKey = 'id_berkas';
    
    protected $fillable = [
        'id_karyawan',
        'nama_berkas', 
        'files'
    ];

    // Relationship dengan DataKaryawan
    public function karyawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'id_karyawan', 'id_karyawan');
    }

    // Accessor untuk mendapatkan URL file
    public function getFileUrlAttribute()
    {
        if ($this->files) {
            return asset('storage/' . $this->files);
        }
        return null;
    }

    // Accessor untuk mendapatkan ukuran file dalam format yang mudah dibaca
    public function getFileSizeAttribute()
    {
        if ($this->files) {
            $filePath = storage_path('app/public/' . $this->files);
            if (file_exists($filePath)) {
                $bytes = filesize($filePath);
                $units = ['B', 'KB', 'MB', 'GB'];
                $factor = floor((strlen((string)$bytes) - 1) / 3);
                return sprintf("%.1f %s", $bytes / pow(1024, $factor), $units[$factor]);
            }
        }
        return '0 B';
    }

    // Accessor untuk mendapatkan jenis file berdasarkan nama berkas
    public function getJenisFileAttribute()
    {
        $nama = strtolower($this->nama_berkas ?? '');
        
        if (strpos($nama, 'cv') !== false) return 'CV';
        if (strpos($nama, 'sertifikat') !== false) return 'Sertifikat';
        if (strpos($nama, 'ijazah') !== false) return 'Ijazah';
        if (strpos($nama, 'ktp') !== false) return 'KTP';
        if (strpos($nama, 'sim') !== false) return 'SIM';
        if (strpos($nama, 'npwp') !== false) return 'NPWP';
        
        return 'Lainnya';
    }
}
