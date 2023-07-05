<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Pasien::query();

        if ($request->q) {
            $query->where('nama', 'like', "%{$request->q}%")->orWhere('no_rm', 'like', "%{$request->q}%");
        }

        return $query->get();
    }
}
