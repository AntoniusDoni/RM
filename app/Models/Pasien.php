<?php

namespace App\Models;


class Pasien extends Model
{

    protected $fillable = [
        'no_rm',
        'nama',
        'alamat',
        'phone',
        'jk',
        'tgl_lahir',
    ];
}
