<?php

namespace App\Models;


class Peminjaman extends Model
{
    protected $fillable = [
        'kd_petugas_pinjam',
        'pasiens_id',
        'tgl_pinjam',
        'kd_petugas_kembali',
        'tanggal_kembali'
    ];
    protected $table='peminjaman';
    public function pasien(){
        return $this->belongsTo(Pasien::class,'pasiens_id');
    }
    public function petugasPinjam(){
        return $this->belongsTo(Petugas::class,'kd_petugas_pinjam');
    }

    public function petugasKembali(){
        return $this->belongsTo(Petugas::class,'kd_petugas_kembali');
    }
}
