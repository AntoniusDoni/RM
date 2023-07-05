<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Ruangan::query();

        if ($request->q) {
            $query->where('nama', 'like', "%{$request->q}%")->orWhere('kode_ruang','=',$request->q);
        }

        $query->orderBy('created_at', 'desc');

        return inertia('Ruangan/Index', [
            'query' => $query->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_ruang' => 'required|string|unique:ruangans,kode_ruang',
            'nama' => 'required|string|max:255',
        ]);

        Ruangan::create([
            'kode_ruang' => $request->kode_ruang,
            'nama' => $request->nama,
        ]);

        return redirect()->route('ruang.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }

    public function update(Request $request, Ruangan $ruang): RedirectResponse
    {
        $request->validate([
            'kode_ruang' => 'required|string|unique:ruangans,kode_ruang,'.$ruang->id,
            'nama' => 'required|string|max:255',
        ]);

        $ruang->fill([
            'kode_ruang' => $request->kode_ruang,
            'nama' => $request->nama,
        ]);
        $ruang->save();

        return redirect()->route('ruang.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed updated']);
    }

    public function destroy(Ruangan $ruang)
    {

        $ruang->delete();
        return redirect()->route('ruang.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
