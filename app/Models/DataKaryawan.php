<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataKaryawan extends Model
{
    protected $table = 'tb_data_karyawan';
    protected $primaryKey = 'id_karyawan';
    
    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'alamat',
        'status_karyawan'
    ];

    // Relationship to User
    public function user()
    {
        return $this->hasOne(UserKaryawan::class, 'id_karyawan', 'id_karyawan');
    }

    // Scope untuk karyawan aktif
    public function scopeAktif($query)
    {
        return $query->where('status_karyawan', 'aktif');
    }

    // Scope untuk guru
    public function scopeGuru($query)
    {
        return $query->where('jabatan', 'like', '%guru%');
    }

    // Scope untuk staff
    public function scopeStaff($query)
    {
        return $query->where('jabatan', 'not like', '%guru%');
    }

    // Accessor untuk status badge class
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status_karyawan) {
            'aktif' => 'bg-green-100 text-green-800',
            'cuti' => 'bg-yellow-100 text-yellow-800',
            'nonaktif' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Accessor untuk initials
    public function getInitialsAttribute()
    {
        return collect(explode(' ', $this->nama))
            ->map(fn($name) => strtoupper(substr($name, 0, 1)))
            ->implode('');
    }
}