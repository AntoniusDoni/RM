<?php

namespace App\Models;


class Pasien extends Model
{

    protected $fillable = [
        'no_rm',
        'nama',
        'tempat_lahir',
        'tgl_lahir',
        'jk',
        'alamat',
        'phone',
    ];
}
