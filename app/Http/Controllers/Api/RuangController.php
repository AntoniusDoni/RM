<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Ruangan::query();
        if ($request->q) {
            $query->where('nama', 'like', "%{$request->q}%");
        }

        return $query->get();
    }
}
