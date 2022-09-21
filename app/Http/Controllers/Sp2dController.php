<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class Sp2dController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'data_sp2d' => DB::table('trhsp2d as a')->join('trhspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhspd as c', 'a.no_spd', '=', 'c.no_spd')->whereIn('a.jns_spp', ['1', '2', '3', '4', '5', '6'])->where(['a.kd_skpd' => $kd_skpd])->orderBy('tgl_sp2d')->orderBy('no_sp2d')->orderBy('kd_skpd')->select('a.*', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"))->get(),
        ];

        return view('penatausahaan.pengeluaran.sp2d.index')->with($data);
    }

    public function create()
    {
        return view('penatausahaan.pengeluaran.sp2d.create');
    }

    public function cariSpm(Request $request)
    {
        $beban = $request->beban;
        // pakai kd_skpd bud nanti
        $kd_skpd = Auth::user()->kd_skpd;
        if (in_array($beban, ['1', '2', '3'])) {
            $data = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.jns_spp' => $beban])->where(function ($query) {
                $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
            })->where('a.kd_skpd', $kd_skpd)->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban')->get();
        } elseif ($beban == '4') {
            $data1 = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.jns_spp' => $beban])->whereIn('a.jenis_beban', ['1', '7', '9', '10'])->where(function ($query) {
                $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
            })->where('a.kd_skpd', $kd_skpd)->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban');

            $data2 = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.jns_spp' => $beban, 'a.kd_skpd' => '3.10.01.01'])->whereIn('a.jenis_beban', ['1', '7', '10'])->where(function ($query) {
                $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
            })->where('a.keperluan', 'not like', '%anggota dprd%')->where('a.keperluan', 'not like', '%BPOP%')->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban')->unionAll($data1);

            $data3 = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.jns_spp' => $beban, 'a.kd_skpd' => '1.20.02.01'])->whereIn('a.jenis_beban', ['1', '7', '10'])->where(function ($query) {
                $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
            })->where('a.no_spm', 'not like', '%BTL%')->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban')->unionAll($data2);

            $data = $data3->get();
        } elseif (in_array($beban, ['5', '6'])) {
            $data1 = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0'])->whereIn('a.jns_spp', ['5', '6'])->where(function ($query) {
                $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
            })->where('a.kd_skpd', $kd_skpd)->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban');

            $data2 = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.jns_spp' => '4'])->whereIn('a.jenis_beban', ['1', '7', '9', '10'])->where(function ($query) {
                $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
            })->where('a.kd_skpd', $kd_skpd)->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban')->unionAll($data1);

            $data3 = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.kd_skpd' => '4.02.0.00.0.00.01.0000', 'a.jns_spp' => '4'])->whereIn('a.jenis_beban', ['1', '7', '10'])->where(function ($query) {
                $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
            })->where('a.kd_skpd', $kd_skpd)->where(function ($query) {
                $query->where('a.keperluan', 'not like', '%anggota dprd%')->orWhere('a.keperluan', 'not like', '%BPOP%');
            })->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban')->unionAll($data2);

            $data4 = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.kd_skpd' => '4.02.0.00.0.00.01.0000', 'a.jns_spp' => '4'])->whereIn('a.jenis_beban', ['1', '7', '10'])->where(function ($query) {
                $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
            })->where('a.kd_skpd', $kd_skpd)->where('a.no_spm', 'not like', '%BTL%')->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban')->unionAll($data3);

            $data = $data4->get();
        }


        return response()->json($data);
    }

    public function cariJenis(Request $request)
    {
        $beban = $request->beban;
        $jenis = $request->jenis;

        $nama = jenis($beban, $jenis);
        return response()->json($nama);
    }

    public function cariBulan(Request $request)
    {
        $bulan = $request->bulan;

        $nama = bulan($bulan);
        return response()->json($nama);
    }

    public function loadRincianSpm(Request $request)
    {
        $no_spp = $request->no_spp;

        $data = DB::table('trdspp')->select('kd_sub_kegiatan', 'nm_sub_kegiatan', 'kd_rek6', 'nm_rek6', 'nilai', 'sisa')->where(['no_spp' => $no_spp])->orderBy('kd_sub_kegiatan')->orderBy('kd_rek6')->get();

        return DataTables::of($data)->make(true);;
        return view('penatausahaan.pengeluaran.sp2d.create');
    }

    public function loadRincianPotongan(Request $request)
    {
        $no_spm = $request->no_spm;

        $data = DB::table('trspmpot')->select('kd_rek6', 'nm_rek6', 'nilai', 'pot')->where(['no_spm' => $no_spm])->orderBy('kd_rek6')->get();

        return DataTables::of($data)->make(true);;
        return view('penatausahaan.pengeluaran.sp2d.create');
    }

    public function cariTotal(Request $request)
    {
        $no_spp = $request->no_spp;
        $no_spm = $request->no_spm;

        $total_spm = DB::table('trdspp')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $no_spp])->first();

        $total_potongan = DB::table('trspmpot')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spm' => $no_spm])->first();

        return response()->json([
            'total_spm' => $total_spm->nilai,
            'total_potongan' => $total_potongan->nilai,
        ]);
    }

    public function cariNomor()
    {
        $data = DB::table('nomor')->select(DB::raw("(nosp2d)+1 as nomor"))->first();
        return response()->json($data);
    }
}
