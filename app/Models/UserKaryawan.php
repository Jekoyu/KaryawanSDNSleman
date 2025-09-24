<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserKaryawan extends Model
{
    protected $table = 'tb_user';
    protected $primaryKey = 'id_user';
    
    protected $fillable = [
        'id_karyawan',
        'username',
        'password',
        'peran'
    ];

    protected $hidden = [
        'password'
    ];

    // Relationship to DataKaryawan
    public function karyawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'id_karyawan', 'id_karyawan');
    }
}