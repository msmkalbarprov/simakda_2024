<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class ProteksiSppController extends Controller
{
    public function index()
    {
        $username = Auth::user()->username;

        if ($username == 'AKT01' || $username == 'AKT02' || $username == 'AKT03') {
            return view('penatausahaan.pengeluaran.proteksi_spp.index');
        } else {
            return redirect()->back();
        }
    }

    public function loadData()
    {
        $data = DB::table('trhspp')
            ->whereRaw("(sp2d_batal!='1' or sp2d_batal is null)")
            ->orderByRaw("tgl_spp ASC, no_spp ASC,CAST(urut AS INT) ASC")
            ->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("proteksi_spp.show", ['no_spp' => Crypt::encrypt($row->no_spp), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-info btn-sm" style="margin-right:4px"><i class="uil-eye"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tampilSppLs($no_spp, $kd_skpd)
    {
        $no_spp = Crypt::decrypt($no_spp);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data_sppls = DB::table('trhspp as a')->join('trdspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where('a.no_spp', $no_spp)->select('a.*')->first();

        $data = [
            'sppls' => $data_sppls,
            'tgl_spd' => DB::table('trhspd')->select('tgl_spd')->where('no_spd', $data_sppls->no_spd)->first(),
            'bank' => DB::table('ms_bank')->select('nama')->where('kode', $data_sppls->bank)->first(),
            'detail_spp' => DB::table('trdspp as a')->select('a.*', 'c.nm_sumber_dana1')->join('trhspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('sumber_dana as c', 'a.sumber', '=', 'c.kd_sumber_dana1')->where('a.no_spp', $no_spp)->get(),
        ];
        return view('penatausahaan.pengeluaran.proteksi_spp.show')->with($data);
    }

    public function setuju(Request $request)
    {
        $no_spp = $request->no_spp;
        $kd_skpd = $request->kd_skpd;
        $setuju = $request->setuju;

        try {
            DB::beginTransaction();

            if ($setuju == 1) {
                DB::table('trhspp')
                    ->where(['no_spp' => $no_spp, 'kd_skpd' => $kd_skpd])
                    ->update([
                        'setujui' => 0
                    ]);
            } else {
                DB::table('trhspp')
                    ->where(['no_spp' => $no_spp, 'kd_skpd' => $kd_skpd])
                    ->update([
                        'setujui' => 1
                    ]);
            }
            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }
}
