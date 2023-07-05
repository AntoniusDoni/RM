<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    //
    public function index(Request $request){

        $query=Peminjaman::with(['pasien','petugasPinjam']);
        return inertia('RM/Peminjaman', [
            'query' =>$query->paginate(10),
        ]);
    }
    public function store(Request $request){
        $request->validate([
            'kode_ruang' => 'required|exists:ruangans,id',
            'no_rm' => 'required|exists:pasiens,id',
            'kode_petugas'=> 'required|exists:petugas,id',
            'tgl_pinjam'=>'required|date',
        ]);
    }
}
