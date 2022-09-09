<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpmController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'data_spm' => DB::table('trhspm as a')->join('trhspp as b', 'a.no_spp', '=', 'b.no_spp')->where(['a.kd_skpd' => $kd_skpd])->select('a.*', DB::raw("ISNULL(b.sp2d_batal,'') as sp2d_batal"), DB::raw("ISNULL(ket_batal,'') as ket_batal"))->orderBy('a.no_spm', 'asc')->orderBy('a.kd_skpd', 'asc')->get(),
        ];
        return view('penatausahaan.pengeluaran.spm.index')->with($data);
    }

    public function create()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $tanggal = date('d');
        $bulan = date('m');
        if ($bulan - 1 == 0) {
            $bulan2 = 1;
        } else {
            $bulan2 = $bulan - 1;
        }
        $data1 = DB::table('trhspm')->select('no_spp')->where(['kd_skpd' => $kd_skpd])->get();
        $data2 = json_decode(json_encode($data1), true);
        $skpd1 = DB::table('trhspj_ppkd')->select('kd_skpd')->where(['bulan' => $bulan2, 'cek' => '1', 'kd_skpd' => $kd_skpd])->get();
        $skpd = json_decode(json_encode($skpd1), true);
        $prov = DB::table('trhspj_ppkd')->select(DB::raw("ISNULL(cek,0) as cek"))->where(['kd_skpd' => $kd_skpd, 'bulan' => $bulan2])->first();
        $cek = $prov->cek;

        if ($cek == '0' || $cek == null || $cek == 0) {
            if ($tanggal < 13) {
                $data_spp1 = DB::table('trhspp')->select('no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'jns_beban', DB::raw("(replace( replace( npwp, '.', '' ), '-', '' )) as npwp"))->where('kd_skpd', $kd_skpd)->where('jns_spp', '!=', '3')->where(function ($query) {
                    $query->where('sp2d_batal', '!=', '1')->orWhereNull('sp2d_batal');
                })->whereNotIn('no_spp', $data2);
                $data_spp2 = DB::table('trhspp')->select('no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'jns_beban', DB::raw("(replace( replace( npwp, '.', '' ), '-', '' )) as npwp"))->where(['kd_skpd' => $kd_skpd, 'jns_spp' => '3', 'sts_setuju' => '1'])->where(function ($query) {
                    $query->where('sp2d_batal', '!=', '1')->orWhereNull('sp2d_batal');
                })->whereNotIn('no_spp', $data2)->unionAll($data_spp1);
                $data_spp = DB::table(DB::raw("({$data_spp2->toSql()}) AS sub"))
                    ->mergeBindings($data_spp2)
                    ->get();
            } else {
                $data_spp = DB::table('trhspp')->select('no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'jns_beban', DB::raw("(replace( replace( npwp, '.', '' ), '-', '' )) as npwp"))->where('kd_skpd', $kd_skpd)->whereIn('jns_spp', ['4', '5', '6'])->where(function ($query) {
                    $query->where('sp2d_batal', '!=', '1')->orWhereNull('sp2d_batal');
                })->whereNotIn('no_spp', $data2)->get();
            }
        } else {
            $data_spp1 = DB::table('trhspp')->select('no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'jns_beban', DB::raw("(replace( replace( npwp, '.', '' ), '-', '' )) as npwp"))->where('kd_skpd', $kd_skpd)->whereIn('jns_spp', ['1', '2'])->where(function ($query) {
                $query->where('sp2d_batal', '!=', '1')->orWhereNull('sp2d_batal');
            })->whereNotIn('no_spp', $data2)->whereIn('kd_skpd', $skpd);
            $data_spp2 = DB::table('trhspp')->select('no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'jns_beban', DB::raw("(replace( replace( npwp, '.', '' ), '-', '' )) as npwp"))->where(['kd_skpd' => $kd_skpd, 'sts_setuju' => '1'])->whereIn('jns_spp', ['3'])->where(function ($query) {
                $query->where('sp2d_batal', '!=', '1')->orWhereNull('sp2d_batal');
            })->whereNotIn('no_spp', $data2)->whereIn('kd_skpd', $skpd)->unionAll($data_spp1);
            $data_spp3 = DB::table('trhspp')->select('no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'jns_beban', DB::raw("(replace( replace( npwp, '.', '' ), '-', '' )) as npwp"))->where(['kd_skpd' => $kd_skpd])->whereIn('jns_spp', ['4', '5', '6'])->where(function ($query) {
                $query->where('sp2d_batal', '!=', '1')->orWhereNull('sp2d_batal');
            })->whereNotIn('no_spp', $data2)->unionAll($data_spp2);
            $data_spp = DB::table(DB::raw("({$data_spp3->toSql()}) AS sub"))
                ->mergeBindings($data_spp3)
                ->get();
        }
        $data = [
            'data_spp' => $data_spp,
        ];
        return view('penatausahaan.pengeluaran.spm.create')->with($data);
    }

    public function cariJenis(Request $request)
    {
        $beban = $request->beban;
        $jenis = $request->jenis;
        $data = DB::table('ms_jenis_beban')->select('nama', 'jenis')->where(['jns_spp' => $beban, 'jenis' => $jenis])->first();
        return response()->json($data);
    }

    public function cariBank(Request $request)
    {
        $bank = $request->bank;
        $data = DB::table('ms_bank')->select('nama')->where(['kode' => $bank])->first();
        return response()->json($data);
    }

    public function detailSpm(Request $request)
    {
        $no_spp = $request->no_spp;
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trdspp')->select('kd_sub_kegiatan', 'nm_sub_kegiatan', 'kd_rek6', 'nm_rek6', 'nilai', 'sisa', 'no_bukti')->where(['no_spp' => $no_spp, 'kd_skpd' => $kd_skpd])->orderBy('no_bukti')->orderBy('kd_sub_kegiatan')->orderBy('kd_rek6')->get();
        return response()->json($data);
    }
}
