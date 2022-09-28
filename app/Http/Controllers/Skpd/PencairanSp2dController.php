<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PencairanSp2dController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'cair_sp2d' => DB::table('trhsp2d')->where(['status_terima' => '1', 'kd_skpd' => $kd_skpd])->orderBy('no_sp2d')->orderBy('kd_skpd')->get()
        ];
        return view('skpd.pencairan_sp2d.index')->with($data);
    }

    public function tampilSp2d($no_sp2d)
    {
        $sp2d = DB::table('trhsp2d')->where(['status_terima' => '1', 'no_sp2d' => $no_sp2d])->first();
        $data = [
            'sp2d' => $sp2d,
            'total_spm' => DB::table('trdspp')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $sp2d->no_spp])->first(),
            'total_potongan' => DB::table('trspmpot')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spm' => $sp2d->no_spm])->first(),
        ];
        return view('skpd.pencairan_sp2d.show')->with($data);
    }

    public function batalCair(Request $request)
    {
        $no_kas = $request->no_kas;
        $no_sp2d = $request->no_sp2d;
        $beban = $request->beban;
        $jenis = $request->jenis;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $no_spp = DB::table('trhsp2d')->select('no_spp')->where(['no_sp2d' => $no_sp2d])->first();
            $kontrak = DB::table('trhspp')->select('kontrak')->where(['no_spp' => $no_spp->no_spp])->first();
            $total_data = DB::table('trspmpot as a')->join('trhsp2d as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->where(['b.no_sp2d' => $no_sp2d, 'b.kd_skpd' => $kd_skpd])->whereNotIn('a.kd_rek6', ['210601010007', '4140612'])->count();
            if ($total_data > 0) {
                $no_bukti = $no_kas + 1;
            }
            $no_sts = $no_kas + 1;
            $no_sts =  "$no_sts";
            $total_data1 = DB::table('trspmpot as a')->join('trhsp2d as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->where(['b.no_sp2d' => $no_sp2d, 'b.kd_skpd' => $kd_skpd])->whereIn('a.kd_rek6', ['2110801', '4140612'])->count();
            if ($total_data1 > 0) {
                $no_sts = $no_kas + 1;
                $no_sts = "$no_sts";
            }

            if (($beban < 5) || ($beban == 6 && $kontrak->kontrak == '')) {
                $total_data2 = DB::table('tr_setorsimpanan')->where(['kd_skpd' => $kd_skpd, 'no_sp2d' => $no_sp2d])->count();
                if ($total_data2 > 0) {
                    DB::table('tr_setorsimpanan')->where(['no_sp2d' => $no_sp2d, 'kd_skpd' => $kd_skpd])->delete();
                } else {
                    DB::table('tr_setorsimpanan')->where(['no_kas' => $no_setor, 'kd_skpd' => $kd_skpd])->delete();
                }
            }
            $tgl_terima = DB::table('trhsp2d')->select('tgl_terima')->where(['no_sp2d' => $no_sp2d, 'kd_skpd' => $kd_skpd])->first();
            DB::table('trhsp2d')->where(['no_sp2d' => $no_sp2d, 'kd_skpd' => $kd_skpd])->update([
                'status' => '0',
                'no_kas' => '',
                'tgl_kas' => $tgl_terima->tgl_terima,
            ]);
            DB::table('trdkasout_pkd')->where(['no_kas' => $no_kas, 'kd_skpd' => $kd_skpd])->delete();
            DB::table('trhkasout_pkd')->where(['no_kas' => $no_kas, 'kd_skpd' => $kd_skpd])->delete();

            if ($beban == '4' && ($jenis == '1' || $jenis == '10')) {
                DB::table('trdtransout')->where(['kd_skpd' => $kd_skpd, 'no_sp2d' => $no_sp2d])->delete();
                DB::table('trhtransout')->where(['no_sp2d' => $no_sp2d, 'kd_skpd' => $kd_skpd])->delete();
            }

            if (($beban == '6' && $kontrak->kontrak <> '') || $beban == '5') {
                DB::table('trdtransout')->where(['kd_skpd' => $kd_skpd, 'no_sp2d' => $no_sp2d])->delete();
                DB::table('trhtransout')->where(['no_sp2d' => $no_sp2d, 'kd_skpd' => $kd_skpd])->delete();
            }

            if ($beban == '6' || $beban == '5' || $beban == '4') {
                DB::table('trdstrpot')->where(['kd_skpd' => $kd_skpd, 'no_sp2d' => $no_sp2d])->delete();
                DB::table('trhstrpot')->where(['no_sp2d' => $no_sp2d, 'kd_skpd' => $kd_skpd])->delete();
                DB::table('trdkasin_pkd')->where(['no_sts' => $no_sts, 'kd_skpd' => $kd_skpd])->delete();
                DB::table('trhkasin_pkd')->where(['no_sts' => $no_sts, 'kd_skpd' => $kd_skpd])->delete();
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
