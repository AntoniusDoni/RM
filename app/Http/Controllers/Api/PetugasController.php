<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Petugas::query()->where('kd_ruang','=',$request->kode_ruang);

        if ($request->q) {
            $query->where('nama', 'like', "%{$request->q}%")->orWhere('kode_petugas','like', "%{$request->q}%");
        }

        return $query->get();
    }
}
