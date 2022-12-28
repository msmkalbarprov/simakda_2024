<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class Sp2dController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'ttd1' => DB::table('ms_ttd')->select('nama', 'nip', 'jabatan')->where(['kode' => 'BUD'])->groupBy('nama', 'nip', 'jabatan')->get(),
            'ttd2' => DB::table('ms_ttd')->select('nama', 'nip', 'jabatan')->where(['kd_skpd' => $kd_skpd])->groupBy('nama', 'nip', 'jabatan')->get()
        ];

        return view('penatausahaan.pengeluaran.sp2d.index')->with($data);
    }

    public function loadData()
    {
        // USER BUD JANGAN LUPA
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhsp2d as a')->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trhspd as c', 'a.no_spd', '=', 'c.no_spd')->whereIn('a.jns_spp', ['1', '2', '3', '4', '5', '6'])
            // ->where(['a.kd_skpd' => $kd_skpd])
            ->where(function ($query) use ($kd_skpd) {
                if (Auth::user()->is_admin == 2) {
                    $query->where(['a.kd_skpd' => $kd_skpd]);
                }
            })
            ->orderBy('tgl_sp2d')->orderBy(DB::raw("CAST(LEFT(no_sp2d,LEN(no_sp2d)-8)as int)"))->orderBy('kd_skpd')->select('a.*', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"))->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("sp2d.tampil", Crypt::encryptString($row->no_sp2d)) . '" class="btn btn-info btn-sm" style="margin-right:4px" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat SP2D"><i class="uil-eye"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_sp2d . '\',\'' . $row->jns_spp . '\',\'' . $row->kd_skpd . '\');" class="btn btn-success btn-sm" style="margin-right:4px" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak SP2D"><i class="uil-print"></i></a>';
            if ($row->status_bud != 1 || !$row->no_advice) {
                $btn .= '<a href="javascript:void(0);" onclick="batal_sp2d(\'' . $row->no_sp2d . '\',\'' . $row->jns_spp . '\',\'' . $row->kd_skpd . '\',\'' . $row->no_spm . '\',\'' . $row->no_spp . '\',\'' . $row->status_bud . '\');" class="btn btn-danger btn-sm" style="margin-right:4px" data-bs-toggle="tooltip" data-bs-placement="top" title="Batal SP2D"><i class="uil-ban"></i></a>';
            } else {
                $btn .= '';
            }
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function create()
    {
        return view('penatausahaan.pengeluaran.sp2d.create');
    }

    public function cariSpm(Request $request)
    {
        $beban = $request->beban;
        // pakai kd_skpd bud nanti
        $id_pengguna    = Auth::user()->id;
        $role           = Auth::user()->role;
        if($role=='1012' || $role=='1017'){
            // get_skpd_pengguna
            $skpd               = DB::table('pengguna_skpd')->select('kd_skpd')->where('id', $id_pengguna)->orderBy('kd_skpd')->get();
            $pengguna_skpd      = Array();   
            foreach ($skpd as $list) {
                $pengguna_skpd[] =  $list->kd_skpd;
            }
            // get skpd dari master skpd
            $kd_skpd = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->whereIn('kd_skpd', $pengguna_skpd)->orderBy('kd_skpd')->get();

                if (in_array($beban, ['1', '2', '3'])) {
                    $data = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.jns_spp' => $beban])->where(function ($query) {
                        $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
                    })->whereIn('a.kd_skpd', $kd_skpd)->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban')->get();
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
        }else{
                if (in_array($beban, ['1', '2', '3'])) {
                    $data = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.jns_spp' => $beban])->where(function ($query) {
                        $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
                    })->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban')->get();
                } elseif ($beban == '4') {
                    $data1 = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.jns_spp' => $beban])->whereIn('a.jenis_beban', ['1', '7', '9', '10'])->where(function ($query) {
                        $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
                    })->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban');
        
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
                    })->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban');
        
                    $data2 = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.jns_spp' => '4'])->whereIn('a.jenis_beban', ['1', '7', '9', '10'])->where(function ($query) {
                        $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
                    })->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban')->unionAll($data1);
        
                    $data3 = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.kd_skpd' => '4.02.0.00.0.00.01.0000', 'a.jns_spp' => '4'])->whereIn('a.jenis_beban', ['1', '7', '10'])->where(function ($query) {
                        $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
                    })->where(function ($query) {
                        $query->where('a.keperluan', 'not like', '%anggota dprd%')->orWhere('a.keperluan', 'not like', '%BPOP%');
                    })->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban')->unionAll($data2);
        
                    $data4 = DB::table('trhspm as a')->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trhspp as c', 'a.no_spp', '=', 'c.no_spp')->where(['a.status' => '0', 'a.kd_skpd' => '4.02.0.00.0.00.01.0000', 'a.jns_spp' => '4'])->whereIn('a.jenis_beban', ['1', '7', '10'])->where(function ($query) {
                        $query->where('c.sp2d_batal', '!=', '1')->orWhereNull('c.sp2d_batal');
                    })->where('a.no_spm', 'not like', '%BTL%')->select('a.no_spm', 'a.tgl_spm', 'a.no_spp', 'a.tgl_spp', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.keperluan', 'a.bulan', 'a.no_spd', 'a.bank', 'a.nmrekan', 'a.no_rek', 'a.npwp', DB::raw("(CASE WHEN c.jns_beban = '5' THEN 'Belanja' ELSE 'Pembiayaan' END) as jns_spd"), 'a.jenis_beban')->unionAll($data3);
        
                    $data = $data4->get();
                }
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

    public function simpanSp2d(Request $request)
    {
        $beban = $request->beban;
        $no_spp = $request->no_spp;
        $no_spm = $request->no_spm;
        $tgl_sp2d = $request->tgl_sp2d;

        DB::beginTransaction();
        try {
            // DB::raw("LOCK TABLES nomor WRITE");
            $nomor = DB::table('nomor')->select(DB::raw("(nosp2d+1) as nomor"))->first();
            if ($beban == '1') {
                $no_sp2d = $nomor->nomor . '/UP/2022';
            } elseif ($beban == '2') {
                $no_sp2d = $nomor->nomor . '/GU/2022';
            } elseif ($beban == '3') {
                $no_sp2d = $nomor->nomor . '/TU/2022';
            } elseif ($beban == '4') {
                $no_sp2d = $nomor->nomor . '/GJ/2022';
            } elseif ($beban == '5' || $beban == '6') {
                $no_sp2d = $nomor->nomor . '/LS/2022';
            }

            $data_spm = DB::table('trhspm')->where(['no_spm' => $no_spm])->select('no_spm', 'tgl_spm', 'no_spp', 'kd_skpd', 'nm_skpd', 'tgl_spp', 'bulan', 'no_spd', 'keperluan', 'username', 'last_update', 'jns_spp', 'bank', 'nmrekan', 'no_rek', 'npwp', 'nilai', 'jenis_beban', DB::raw("'$tgl_sp2d' as tgl_sp2d"), DB::raw("'$no_sp2d' as no_sp2d"), DB::raw("'0' as status"), DB::raw("'0' as status_terima"));


            DB::table('trhsp2d')->where(['no_sp2d' => $no_sp2d])->insertUsing(['no_spm', 'tgl_spm', 'no_spp', 'kd_skpd', 'nm_skpd', 'tgl_spp', 'bulan', 'no_spd', 'keperluan', 'username', 'last_update', 'jns_spp', 'bank', 'nmrekan', 'no_rek', 'npwp', 'nilai', 'jenis_beban', 'tgl_sp2d', 'no_sp2d', 'status', 'status_terima'], $data_spm);

            DB::table('nomor')->update(['nosp2d' => $nomor->nomor]);
            DB::table('trhspm')->where(['no_spm' => $no_spm])->update([
                'status' => '1'
            ]);
            DB::commit();
            // DB::raw("UNLOCK TABLES");
            return response()->json([
                'message' => '1',
                'no_sp2d' => $no_sp2d
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    // cetak sp2d
    public function cetakSp2d(Request $request)
    {
        $no_sp2d = $request->no_sp2d;
        $ttd_bud = $request->ttd_bud;
        $ttd1 = $request->ttd1;
        $ttd2 = $request->ttd2;
        $baris = $request->baris;
        $jenis = $request->jenis;
        $kd_skpd = $request->kd_skpd;
        $beban = $request->beban;
        $baris = $request->baris;

        $sp2d = DB::table('trhsp2d as a')->where(['a.no_sp2d' => $no_sp2d])->select('a.*', DB::raw("(SELECT nmrekan FROM trhspp WHERE no_spp=a.no_spp AND kd_skpd=a.kd_skpd) as nmrekan"), DB::raw("(SELECT pimpinan FROM trhspp WHERE no_spp=a.no_spp AND kd_skpd=a.kd_skpd) as pimpinan"), DB::raw("(SELECT alamat FROM trhspp WHERE no_spp=a.no_spp AND kd_skpd=a.kd_skpd) as alamat"))->first();
        $data_sp2d = cari_sp2d($sp2d, $baris);
        $data = [
            'no_sp2d' => $no_sp2d,
            'sp2d' => $sp2d,
            'nilai_sp2d' => DB::table('trdspp')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $sp2d->no_spp])->first(),
            'bank' => DB::table('trhsp2d')->select('bank', 'no_rek', 'npwp')->where(['no_sp2d' => $no_sp2d])->first(),
            'ttd1' => DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd1, 'kode' => 'BUD'])->first(),
            'ttd_skpd' => DB::table('ms_ttd')->select('nama', 'jabatan')->where(['kd_skpd' => $sp2d->kd_skpd, 'kode' => 'BK'])->first(),
            'beban' => $beban,
            'total' => DB::table('trdspp')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $sp2d->no_spp, 'kd_skpd' => $sp2d->kd_skpd])->first(),
            'data_sp2d' => $data_sp2d,
            'potongan1' => DB::table('trspmpot as a')->join('ms_pot as b', 'a.map_pot', '=', 'b.map_pot')->where(['no_spm' => $sp2d->no_spm, 'kelompok' => '1', 'kd_skpd' => $sp2d->kd_skpd])->get(),
            'total_potongan1' => DB::table('trspmpot as a')->join('ms_pot as b', 'a.map_pot', '=', 'b.map_pot')->where(['no_spm' => $sp2d->no_spm, 'kelompok' => '1', 'kd_skpd' => $sp2d->kd_skpd])->select(DB::raw("SUM(nilai) as nilai"))->first(),
            'jumlah_potongan1' => DB::table('trspmpot as a')->join('ms_pot as b', 'a.map_pot', '=', 'b.map_pot')->where(['no_spm' => $sp2d->no_spm, 'kelompok' => '1', 'kd_skpd' => $sp2d->kd_skpd])->count(),
            'potongan2' => DB::table('trspmpot as a')->join('ms_pot as b', 'a.map_pot', '=', 'b.map_pot')->where(['no_spm' => $sp2d->no_spm, 'kelompok' => '2', 'kd_skpd' => $sp2d->kd_skpd])->get(),
            'total_potongan2' => DB::table('trspmpot as a')->join('ms_pot as b', 'a.map_pot', '=', 'b.map_pot')->where(['no_spm' => $sp2d->no_spm, 'kelompok' => '2', 'kd_skpd' => $sp2d->kd_skpd])->select(DB::raw("SUM(nilai) as nilai"))->first(),
            'jumlah_potongan2' => DB::table('trspmpot as a')->join('ms_pot as b', 'a.map_pot', '=', 'b.map_pot')->where(['no_spm' => $sp2d->no_spm, 'kelompok' => '2', 'kd_skpd' => $sp2d->kd_skpd])->count()
        ];
        return view('penatausahaan.pengeluaran.sp2d.cetak.sp2d')->with($data);
    }

    // cetak lampiran
    public function cetakLampiran(Request $request)
    {
        $no_sp2d = $request->no_sp2d;
        $ttd_bud = $request->ttd_bud;
        $ttd1 = $request->ttd1;
        $ttd2 = $request->ttd2;
        $baris = $request->baris;
        $jenis = $request->jenis;
        $kd_skpd = $request->kd_skpd;
        $beban = $request->beban;
        $baris = $request->baris;

        $sp2d = DB::table('trhsp2d as a')->where(['a.no_sp2d' => $no_sp2d])->select('a.*', DB::raw("(SELECT nmrekan FROM trhspp WHERE no_spp=a.no_spp AND kd_skpd=a.kd_skpd) as nmrekan"), DB::raw("(SELECT pimpinan FROM trhspp WHERE no_spp=a.no_spp AND kd_skpd=a.kd_skpd) as pimpinan"), DB::raw("(SELECT alamat FROM trhspp WHERE no_spp=a.no_spp AND kd_skpd=a.kd_skpd) as alamat"))->first();
        $data_sp2d = cari_lampiran($sp2d, $baris);

        $data = [
            'no_sp2d' => $no_sp2d,
            'sp2d' => $sp2d,
            'ttd1' => DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd1, 'kode' => 'BUD'])->first(),
            'total' => DB::table('trdspp')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $sp2d->no_spp, 'kd_skpd' => $sp2d->kd_skpd])->first(),
            'data_sp2d' => $data_sp2d,
        ];
        return view('penatausahaan.pengeluaran.sp2d.cetak.lampiran')->with($data);
    }

    // cetak lampiran lama
    public function cetakLampiranLama(Request $request)
    {
        $no_sp2d = $request->no_sp2d;
        $ttd_bud = $request->ttd_bud;
        $ttd1 = $request->ttd1;
        $ttd2 = $request->ttd2;
        $baris = $request->baris;
        $jenis = $request->jenis;
        $kd_skpd = $request->kd_skpd;
        $beban = $request->beban;
        $baris = $request->baris;

        $sp2d = DB::table('trhsp2d as a')->where(['a.no_sp2d' => $no_sp2d])->select('a.*', DB::raw("(SELECT nmrekan FROM trhspp WHERE no_spp=a.no_spp AND kd_skpd=a.kd_skpd) as nmrekan"), DB::raw("(SELECT pimpinan FROM trhspp WHERE no_spp=a.no_spp AND kd_skpd=a.kd_skpd) as pimpinan"), DB::raw("(SELECT alamat FROM trhspp WHERE no_spp=a.no_spp AND kd_skpd=a.kd_skpd) as alamat"))->first();
        $data_sp2d = cari_lampiran_lama($sp2d);

        $data = [
            'no_sp2d' => $no_sp2d,
            'sp2d' => $sp2d,
            'ttd1' => DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd1, 'kode' => 'BUD'])->first(),
            'total' => DB::table('trdspp')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $sp2d->no_spp, 'kd_skpd' => $sp2d->kd_skpd])->first(),
            'data_sp2d' => $data_sp2d,
        ];
        return view('penatausahaan.pengeluaran.sp2d.cetak.lampiran_lama')->with($data);
    }

    // cetak kelengkapan
    public function cetakKelengkapan(Request $request)
    {
        $no_sp2d = $request->no_sp2d;
        $ttd_bud = $request->ttd_bud;
        $ttd1 = $request->ttd1;
        $ttd2 = $request->ttd2;
        $baris = $request->baris;
        $jenis = $request->jenis;
        $kd_skpd = $request->kd_skpd;
        $beban = $request->beban;
        $baris = $request->baris;

        $data = [
            'sp2d' => DB::table('trhsp2d')->where(['no_sp2d' => $no_sp2d])->first(),
            'ttd1' => DB::table('ms_ttd')->where(['nip' => $ttd1])->first(),
            'ttd2' => DB::table('ms_ttd')->where(['nip' => $ttd2])->first(),
            'beban' => $beban
        ];
        return view('penatausahaan.pengeluaran.sp2d.cetak.kelengkapan')->with($data);
    }

    // batal sp2d
    public function batalSp2d(Request $request)
    {
        $no_sp2d = $request->no_sp2d;
        $no_spm = $request->no_spm;
        $no_spp = $request->no_spp;
        $keterangan = $request->keterangan;
        $beban = $request->beban;

        DB::beginTransaction();
        try {
            DB::table('trhspp')->where(['no_spp' => $no_spp])->update([
                'sp2d_batal' => '1',
                'ket_batal' => $keterangan,
                'user_batal' => Auth::user()->nama,
                'tgl_batal' => date('Y-m-d H:i:s')
            ]);
            DB::table('trhsp2d')->where(['no_sp2d' => $no_sp2d])->update([
                'sp2d_batal' => '1'
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

    // tampil sp2d
    public function tampilSp2d($no_sp2d)
    {
        $no_sp2d = Crypt::decryptString($no_sp2d);
        $sp2d = DB::table('trhsp2d')->where(['no_sp2d' => $no_sp2d])->first();
        $data = [
            'sp2d' => $sp2d,
            'total_rincian' => DB::table('trdspp')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $sp2d->no_spp])->first(),
            'total_potongan' => DB::table('trspmpot')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spm' => $sp2d->no_spm])->first()
        ];
        return view('penatausahaan.pengeluaran.sp2d.show')->with($data);
    }
}
