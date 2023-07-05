<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class PeminjamanController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Peminjaman::query()->with(['pasien', 'petugasPinjam', 'petugasPinjam.ruang'])
            ->whereNull('tanggal_kembali')->orderBy('tgl_pinjam', 'DESC');
        if ($request->q) {
            // $query->where('nama', 'like', "%{$request->q}%")->orWhere('kode_petugas','=',$request->q);
        }
        return inertia('RM/Peminjaman', [
            'query' => $query->paginate(10),
        ]);
    }
    public function pengembalian()
    {
        $query = Peminjaman::query()->with(['pasien', 'petugasPinjam', 'petugasPinjam.ruang'])
            ->whereNotNull('tgl_pinjam')->whereNull('tanggal_kembali')->orderBy('tgl_pinjam', 'DESC');
        return inertia('RM/Pengembalian', [
            'query' => $query->paginate(10),
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'kode_ruang' => 'required|exists:ruangans,id',
            'no_rm' => 'required|exists:pasiens,id',
            'kode_peminjaman' => 'required|exists:petugas,id',
            'tgl_pinjam' => 'required|date',
        ]);
        $checkRm = Peminjaman::where('pasiens_id', '=', $request->no_rm)->whereNull('tanggal_kembali')->orderBy('tgl_pinjam', 'DESC')->first();

        if ($checkRm == null) {
            Peminjaman::create([
                'kd_petugas_pinjam' => $request->kode_peminjaman,
                'pasiens_id' => $request->no_rm,
                'tgl_pinjam' => date('Y-m-d', strtotime($request->tgl_pinjam)),
            ]);
            return redirect()->route('peminjaman.index')
                ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
        } else {
            $load = $checkRm->load('petugasPinjam', 'petugasPinjam.ruang');
            session()->flash('message', ['type' => 'Failed', 'message' => 'Berkas Masih Dalam Proses Peminjaman Oleh Ruangan ' . $load?->petugasPinjam->ruang?->nama . " Petugas Peminjam " . $load->petugasPinjam->nama]);
        }
    }
    public function pengembalianberkas(Request $request, Peminjaman $peminjam) {
        $request->validate([
            'kode_ruang' => 'required|exists:ruangans,id',
            'no_rm' => 'required|exists:pasiens,id',
            'kode_peminjaman' => 'required|exists:petugas,id',
            'tgl_kembali' => 'required|date',
        ]);
        $peminjam->fill([
            'kd_petugas_kembali' => $request->kode_peminjaman,
            'pasiens_id' => $request->no_rm,
            'tanggal_kembali' => date('Y-m-d', strtotime($request->tgl_pinjam)),
        ]);
        $peminjam->save();

        return redirect()->route('peminjaman.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed updated']);
    }
    public function update(Request $request, Peminjaman $peminjam)
    {
        $request->validate([
            'kode_ruang' => 'required|exists:ruangans,id',
            'no_rm' => 'required|exists:pasiens,id',
            'kode_peminjaman' => 'required|exists:petugas,id',
            'tgl_pinjam' => 'required|date',
        ]);
        $peminjam->fill([
            'kd_petugas_pinjam' => $request->kode_peminjaman,
            'pasiens_id' => $request->no_rm,
            'tgl_pinjam' => date('Y-m-d', strtotime($request->tgl_pinjam)),
        ]);
        $peminjam->save();

        return redirect()->route('peminjaman.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed updated']);
    }
    public function destroy(Peminjaman $peminjam)
    {

        $peminjam->delete();
        return redirect()->route('peminjaman.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }
}
