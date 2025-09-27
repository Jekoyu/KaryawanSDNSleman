<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'tb_riwayat_pekerjaan';
    protected $primaryKey = 'id_riwayat_pekerjaan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_karyawan',
        'nama_perusahaan',
        'jabatan_lama',
        'tahun_kerja'
    ];

    // Relationship dengan DataKaryawan
    public function karyawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'id_karyawan', 'id_karyawan');
    }

    // Format tahun kerja untuk tampilan
    public function getFormattedTahunKerjaAttribute()
    {
        if (!$this->tahun_kerja) {
            return '-';
        }
        
        // Jika format "2015-2020"
        if (strpos($this->tahun_kerja, '-') !== false) {
            list($start, $end) = explode('-', $this->tahun_kerja);
            $years = (int)$end - (int)$start;
            return $this->tahun_kerja . " ({$years} tahun)";
        }
        
        return $this->tahun_kerja;
    }

    // Get durasi kerja dalam tahun
    public function getDurasiKerjaAttribute()
    {
        if (!$this->tahun_kerja || strpos($this->tahun_kerja, '-') === false) {
            return 0;
        }
        
        list($start, $end) = explode('-', $this->tahun_kerja);
        return (int)$end - (int)$start;
    }
}