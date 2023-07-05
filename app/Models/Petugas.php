<?php

namespace App\Models;


class Petugas extends Model
{
    protected $fillable = [
        'kode_petugas',
        'nama',
        'kd_ruang',
    ];

    public function ruang(){
        return $this->belongsTo(Ruangan::class,'kd_ruang');
    }
}
