<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranKaryawan extends Model
{
    use HasFactory;

    protected $table = 'tb_kehadiran_karyawan';
    protected $primaryKey = 'id_kehadiran';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_karyawan',
        'tanggal',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationship dengan DataKaryawan
    public function karyawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'id_karyawan', 'id_karyawan');
    }

    // Alias untuk relationship
    public function dataKaryawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'id_karyawan', 'id_karyawan');
    }

    // Status options
    public static function getStatusOptions()
    {
        return [
            'hadir' => 'Hadir',
            'terlambat' => 'Terlambat',
            'sakit' => 'Sakit',
            'izin' => 'Izin',
            'alfa' => 'Alfa',
            'cuti' => 'Cuti'
        ];
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'hadir' => 'bg-green-100 text-green-800',
            'terlambat' => 'bg-yellow-100 text-yellow-800',
            'sakit' => 'bg-blue-100 text-blue-800',
            'izin' => 'bg-purple-100 text-purple-800',
            'alfa' => 'bg-red-100 text-red-800',
            'cuti' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Scope untuk filter tanggal
    public function scopeByDate($query, $date)
    {
        return $query->where('tanggal', $date);
    }

    // Scope untuk filter bulan
    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month);
    }
}