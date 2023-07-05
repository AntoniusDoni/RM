<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class PasienController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Pasien::query();

        if ($request->q) {
            $query->where('nama', 'like', "%{$request->q}%");
        }

        $query->orderBy('created_at', 'desc');

        return inertia('Pasien/Index', [
            'query' => $query->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'norm' => 'required|string|unique:pasiens,no_rm',
            'nama' => 'required|string|max:255',
            'alamat'=> 'required|string|max:255',
            'phone'=> 'required|string|max:12',
            'jk'=> 'required|string|max:255',
            'tgl_lahir'=> 'required|date',
        ]);

        Pasien::create([
            'no_rm' => $request->norm,
            'nama' => $request->nama,
            'alamat'=>$request->alamat,
            'phone'=> $request->phone,
            'jk'=>$request->jk,
            'tgl_lahir'=> date('Y-m-d', strtotime($request->tgl_lahir)),
        ]);

        return redirect()->route('pasien.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }

    public function update(Request $request, Pasien $pasien): RedirectResponse
    {
        $request->validate([
            'norm' => 'required|string|unique:pasiens,no_rm,'.$pasien->id,
            'nama' => 'required|string|max:255',
            'alamat'=> 'required|string|max:255',
            'phone'=> 'required|string|max:12',
            'jk'=> 'required|string|max:255',
            'tgl_lahir'=> 'required|date',
        ]);

        $pasien->fill([
            'no_rm' => $request->norm,
            'nama' => $request->nama,
            'alamat'=>$request->alamat,
            'phone'=> $request->phone,
            'jk'=>$request->jk,
            'tgl_lahir'=> date('Y-m-d', strtotime($request->tgl_lahir)),
        ]);



        $pasien->save();

        return redirect()->route('pasien.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed updated']);
    }

    public function destroy(Pasien $pasien): RedirectResponse
    {
        $pasien->delete();
        return redirect()->route('pasien.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
