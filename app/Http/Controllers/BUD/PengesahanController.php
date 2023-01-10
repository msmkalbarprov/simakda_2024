<?php

namespace App\Http\Controllers\BUD;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PengesahanController extends Controller
{
    public function indexPengesahanLpjUp()
    {
        return view('bud.pengesahan_lpj_up.index');
    }

    public function loadPengesahanLpjUp()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $role           = Auth::user()->role;
        $id_pengguna    = Auth::user()->id;

        $data = DB::table('trhlpj as a')
            ->selectRaw("a.*, (SELECT b.nm_skpd FROM ms_skpd b WHERE a.kd_skpd=b.kd_skpd) as nm_skpd")
            ->where(function ($query) use ($role, $id_pengguna) {
                if ($role == '1012' || $role == '1017') {
                    $query->whereRaw("a.kd_skpd IN (SELECT kd_skpd FROM pengguna_skpd where id=?)", [$id_pengguna]);
                }
            })
            ->where('jenis', '1')
            ->orderBy('a.tgl_lpj')
            ->orderBy('a.no_lpj')
            ->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("pengesahan_lpj_upgu.edit", ['no_lpj' => Crypt::encrypt($row->no_lpj), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-primary btn-sm"  style="margin-right:4px"><i class="uil-eye"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_lpj . '\',\'' . $row->kd_skpd . '\');" class="btn btn-dark btn-sm" style="margin-right:4px"><i class="uil-print"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function editPengesahanLpjUp($no_lpj, $kd_skpd)
    {
        $no_lpj = Crypt::decrypt($no_lpj);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'lpj' => DB::table('trhlpj as a')
                ->selectRaw("a.*, (SELECT b.nm_skpd FROM ms_skpd b WHERE a.kd_skpd=b.kd_skpd) as nm_skpd")
                ->where(['jenis' => '1', 'no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
                ->first()
        ];

        return view('bud.pengesahan_lpj_up.edit')->with($data);
    }

    public function detailPengesahanLpjUp(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;

        $data = DB::table('trlpj as a')
            ->join('trhlpj as b', function ($join) {
                $join->on('a.no_lpj', '=', 'b.no_lpj');
                $join->on('a.kd_bp_skpd', '=', 'b.kd_skpd');
            })
            ->select('a.*')
            ->where(['b.no_lpj' => $no_lpj, 'b.kd_skpd' => $kd_skpd, 'b.jenis' => '1'])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function setujuPengesahanLpjUp(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhlpj')
                ->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
                ->update([
                    'status' => '1'
                ]);

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

    public function batalSetujuPengesahanLpjUp(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhlpj')
                ->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
                ->update([
                    'status' => '0'
                ]);

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

    // PENGESAHAN LPJ TU
    public function indexPengesahanLpjTu()
    {
        return view('bud.pengesahan_lpj_tu.index');
    }

    public function loadPengesahanLpjTu()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $role           = Auth::user()->role;
        $id_pengguna    = Auth::user()->id;

        $data = DB::table('trhlpj_tu as a')
            ->selectRaw("a.*, (SELECT b.nm_skpd FROM ms_skpd b WHERE a.kd_skpd=b.kd_skpd) as nm_skpd")
            ->where(function ($query) use ($role, $id_pengguna) {
                if ($role == '1012' || $role == '1017') {
                    $query->whereRaw("a.kd_skpd IN (SELECT kd_skpd FROM pengguna_skpd where id=?)", [$id_pengguna]);
                }
            })
            ->where('jenis', '3')
            ->orderBy('a.tgl_lpj')
            ->orderBy('a.no_lpj')
            ->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("pengesahan_lpj_tu.edit", ['no_lpj' => Crypt::encrypt($row->no_lpj), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-primary btn-sm"  style="margin-right:4px"><i class="uil-eye"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_lpj . '\',\'' . $row->kd_skpd . '\');" class="btn btn-dark btn-sm" style="margin-right:4px"><i class="uil-print"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function editPengesahanLpjTu($no_lpj, $kd_skpd)
    {
        $no_lpj = Crypt::decrypt($no_lpj);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'lpj' => DB::table('trhlpj_tu as a')
                ->selectRaw("a.*, (SELECT b.nm_skpd FROM ms_skpd b WHERE a.kd_skpd=b.kd_skpd) as nm_skpd")
                ->where(['jenis' => '3', 'no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
                ->first()
        ];

        return view('bud.pengesahan_lpj_tu.edit')->with($data);
    }

    public function detailPengesahanLpjTu(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;

        $data = DB::table('trlpj_tu as a')
            ->join('trhlpj_tu as b', function ($join) {
                $join->on('a.no_lpj', '=', 'b.no_lpj');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->select('a.*')
            ->where(['b.no_lpj' => $no_lpj, 'b.kd_skpd' => $kd_skpd, 'b.jenis' => '3'])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function setujuPengesahanLpjTu(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhlpj_tu')
                ->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
                ->update([
                    'status' => '1'
                ]);

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

    public function batalSetujuPengesahanLpjTu(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhlpj_tu')
                ->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
                ->update([
                    'status' => '0'
                ]);

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

    // PENGESAHAN LPJ TU
    public function indexPengesahanSpmTu()
    {
        return view('bud.pengesahan_spm_tu.index');
    }

    public function loadPengesahanSpmTu()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $role           = Auth::user()->role;
        $id_pengguna    = Auth::user()->id;

        $data = DB::table('trhspp as a')
            ->selectRaw("a.*")
            ->where(function ($query) use ($role, $id_pengguna) {
                if ($role == '1012' || $role == '1017') {
                    $query->whereRaw("a.kd_skpd IN (SELECT kd_skpd FROM pengguna_skpd where id=?)", [$id_pengguna]);
                }
            })
            ->whereRaw("jns_spp=? AND (sp2d_batal!=? or sp2d_batal is null)", ['3', '1'])
            ->orderBy('a.no_spp')
            ->orderBy('a.kd_skpd')
            ->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("pengesahan_spm_tu.edit", ['no_spp' => Crypt::encrypt($row->no_spp), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-primary btn-sm"  style="margin-right:4px"><i class="uil-eye"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_spp . '\',\'' . $row->kd_skpd . '\');" class="btn btn-dark btn-sm" style="margin-right:4px"><i class="uil-print"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function editPengesahanSpmTu($no_spp, $kd_skpd)
    {
        $no_spp = Crypt::decrypt($no_spp);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'spp' => DB::table('trhspp as a')
                ->selectRaw("a.*")
                ->whereRaw("jns_spp=? AND (sp2d_batal!=? or sp2d_batal is null)", ['3', '1'])
                ->where(['no_spp' => $no_spp, 'kd_skpd' => $kd_skpd])
                ->first()
        ];

        return view('bud.pengesahan_spm_tu.edit')->with($data);
    }

    public function detailPengesahanSpmTu(Request $request)
    {
        $no_spp = $request->no_spp;
        $kd_skpd = $request->kd_skpd;

        $data = DB::table('trdspp as a')
            ->selectRaw("kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,sumber,(select DISTINCT nm_sumberdana from hsumber_dana where kd_sumberdana=sumber)as nmsumber,nilai,no_bukti")
            ->where(['a.no_spp' => $no_spp])
            ->orderBy('no_bukti')
            ->orderBy('kd_sub_kegiatan')
            ->orderBy('kd_rek6')
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function setujuPengesahanSpmTu(Request $request)
    {
        $no_spp = $request->no_spp;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhspp')
                ->where(['no_spp' => $no_spp, 'kd_skpd' => $kd_skpd])
                ->update([
                    'sts_setuju' => '1'
                ]);

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

    public function batalSetujuPengesahanSpmTu(Request $request)
    {
        $no_spp = $request->no_spp;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhspp')
                ->where(['no_spp' => $no_spp, 'kd_skpd' => $kd_skpd])
                ->update([
                    'sp2d_batal' => '1',
                ]);

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
