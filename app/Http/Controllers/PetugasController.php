<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Petugas::query()->with(['ruang']);

        if ($request->q) {
            $query->where('nama', 'like', "%{$request->q}%")->orWhere('kode_petugas','=',$request->q);
        }

        $query->orderBy('created_at', 'desc');

        return inertia('Petugas/Index', [
            'query' => $query->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_ruang' => 'required|exists:ruangans,id',
            'nama' => 'required|string|max:255',
            'kode_petugas'=> 'required|string|unique:petugas,kode_petugas',
        ]);

        Petugas::create([
            'kd_ruang' => $request->kode_ruang,
            'nama' => $request->nama,
            'kode_petugas'=>$request->kode_petugas,
        ]);

        return redirect()->route('petugas.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }

    public function update(Request $request, Petugas $petugas): RedirectResponse
    {
        $request->validate([
            'kode_ruang' => 'required|exists:ruangans,id',
            'nama' => 'required|string|max:255',
            'kode_petugas'=> 'required|string|unique:petugas,kode_petugas,'.$petugas->id,
        ]);

        $petugas->fill([
            'kd_ruang' => $request->kode_ruang,
            'nama' => $request->nama,
            'kode_petugas'=>$request->kode_petugas,
        ]);
        $petugas->save();

        return redirect()->route('petugas.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed updated']);
    }

    public function destroy(Petugas $petugas)
    {

        $petugas->delete();
        return redirect()->route('petugas.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
