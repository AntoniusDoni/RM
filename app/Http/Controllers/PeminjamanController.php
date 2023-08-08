<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class PeminjamanController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Peminjaman::query()->with(['pasien', 'petugasPinjam', 'petugasPinjam.ruang'])
            ->whereNull('tanggal_kembali')->orderBy('tgl_pinjam', 'DESC');
        if ($request->datestart && $request->dateend) {
            $query->whereBetween('tgl_pinjam', [$request->datestart, $request->dateend]);
        }
        if ($request->q) {
            $query->whereHas('petugasPinjam', function ($query) use ($request) {
                $query->where('nama', 'like', "%{$request->q}%")->orWhere('kode_petugas', '=', $request->q);
            });
        }

        return inertia('RM/Peminjaman', [
            'query' => $query->paginate(10),
        ]);
    }
    public function history(Request $request)
    {
        $query = Peminjaman::query()->with(['pasien', 'petugasPinjam', 'petugasPinjam.ruang', 'petugasKembali'])
            ->whereNotNull('tgl_pinjam')->whereNotNull('tanggal_kembali')->orderBy('tgl_pinjam', 'DESC');
        if ($request->q) {
            $query->whereHas('pasien', function ($query) use ($request) {
                $query->where('nama', 'like', "%$request->q%")->orWhere('no_rm', '=', $request->q);
            });
        }
        return inertia('RM/History', [
            'query' => $query->paginate(10),
        ]);
    }
    public function pengembalian(Request $request)
    {
        $query = Peminjaman::query()->with(['pasien', 'petugasPinjam', 'petugasPinjam.ruang','petugasKembali','petugasKembali.ruang']);
            if ($request->datestart && $request->dateend) {
                $query->whereBetween('tanggal_kembali', [$request->datestart, $request->dateend])->orWhereBetween('tgl_pinjam', [$request->datestart, $request->dateend]);
                if ($request->q) {
                    $query->whereHas('pasien', function ($query) use ($request) {
                        $query->where('nama', 'like', "%$request->q%")->orWhere('no_rm', '=', $request->q);
                    });
                }
                return inertia('RM/Pengembalian', [
                    'query' => $query->orderBy('tgl_pinjam', 'DESC')->paginate(10),
                ]);
            } else{
                $query->whereNotNull('tgl_pinjam')->whereNull('tanggal_kembali')->orderBy('tgl_pinjam', 'DESC');
                return inertia('RM/Pengembalian', [
                    'query' => $query->paginate(10),
                ]);
            }

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
    public function pengembalianberkas(Request $request, Peminjaman $peminjam)
    {
        $request->validate([
            'kode_ruang' => 'required|exists:ruangans,id',
            'no_rm' => 'required|exists:pasiens,id',
            'kode_peminjaman' => 'required|exists:petugas,id',
            'tgl_kembali' => 'required|date',
        ]);

        $peminjam->fill([
            'kd_petugas_kembali' => $request->kode_peminjaman,
            'tanggal_kembali' => date('Y-m-d', strtotime($request->tgl_kembali)),
        ]);
        $peminjam->save();

        return redirect()->route('pengembalian.index')
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
    public function exportpdf(Request $request)
    {
        $query = Peminjaman::query()->with(['pasien', 'petugasPinjam', 'petugasPinjam.ruang','creator'])
            ->whereNull('tanggal_kembali')->orderBy('tgl_pinjam', 'DESC');
        if ($request->datestart && $request->dateend) {
            $query->whereBetween('tgl_pinjam', [$request->datestart, $request->dateend]);
        }
        if ($request->q) {
            $query->whereHas('petugasPinjam', function ($query) use ($request) {
                $query->where('nama', 'like', "%{$request->q}%")->orWhere('kode_petugas', '=', $request->q);
            });
        }

        Carbon::setLocale('id');
        $curdate = Carbon::now()->isoFormat('D MMMM Y');
        // dd(auth()->guard()->user());
        $data = [
            'title' => 'Laporan Peminjaman Berkas Rekam Medis ',
            'date' => $curdate,
            'datas' => $query->orderBy('tgl_pinjam', 'DESC')->get(),
            'datestart'=>$request->datestart,
            'dateend'=>$request->dateend,
            'creator'=>auth()?->guard()?->user()?->name
        ];
        $pdf=PDF::loadView('PDFPeminjaman',$data);

        return $pdf->download('Laporan Peminjaman Berkas Rekam Medis per tanggal '.$curdate.'.pdf');

    }
    public function exportpdfpengembalian(Request $request)
    {
        $query = Peminjaman::query()->with(['pasien', 'petugasPinjam', 'petugasPinjam.ruang','petugasKembali','petugasKembali.ruang']);
            if ($request->datestart && $request->dateend) {
                $query->whereBetween('tanggal_kembali', [$request->datestart, $request->dateend]);
                if ($request->q) {
                    $query->whereHas('pasien', function ($query) use ($request) {
                        $query->where('nama', 'like', "%$request->q%")->orWhere('no_rm', '=', $request->q);
                    });
                }

            } else{
                $query->whereNotNull('tgl_pinjam')->whereNotNull('tanggal_kembali');

            }

         Carbon::setLocale('id');
        $curdate = Carbon::now()->isoFormat('D MMMM Y');
        // dd($request->datestart);
        $data = [
            'title' => 'Laporan Pengembalian Berkas Rekam Medis ',
            'date' => $curdate,
            'datas' => $query->orderBy('tanggal_kembali', 'DESC')->get(),
            'datestart'=>$request->datestart,
            'dateend'=>$request->dateend,
            'creator'=>auth()?->guard()?->user()?->name
        ];
        $pdf=PDF::loadView('PDFPengembalian',$data);

        return $pdf->download('Laporan Pengembalian Berkas Rekam Medis per tanggal '.$curdate.'.pdf');

    }



    public function exportpdfhistory(Request $request)
    {
        $query = Peminjaman::query()->with(['pasien', 'petugasPinjam', 'petugasPinjam.ruang','petugasKembali','petugasKembali.ruang']);
            if ($request->datestart && $request->dateend) {
                $query->whereBetween('tanggal_kembali', [$request->datestart, $request->dateend]);
                if ($request->q) {
                    $query->whereHas('pasien', function ($query) use ($request) {
                        $query->where('nama', 'like', "%$request->q%")->orWhere('no_rm', '=', $request->q);
                    });
                }

            } else{
                $query->whereNotNull('tgl_pinjam')->whereNotNull('tanggal_kembali');

            }

           Carbon::setLocale('id');
        $curdate = Carbon::now()->isoFormat('D MMMM Y');
        // dd($request->datestart);
        $data = [
            'title' => 'Laporan Riwayat Berkas Rekam Medis ',
            'date' => $curdate,
            'datas' => $query->orderBy('tgl_pinjam', 'DESC')->get(),
            'datestart'=>$request->datestart,
            'dateend'=>$request->dateend,
            'creator'=>auth()?->guard()?->user()?->name

        ];
        $pdf=PDF::loadView('PDFHistory',$data);

        return $pdf->download('Laporan Riwayat Berkas Rekam Medis per tanggal '.$curdate.'.pdf');

    }
}
