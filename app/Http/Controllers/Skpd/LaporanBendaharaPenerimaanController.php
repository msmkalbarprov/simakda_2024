<?php

namespace App\Http\Controllers\Skpd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;

class LaporanBendaharaPenerimaanController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'bendahara' => DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd,'kode'=>'BK'])->orderBy('nip')->orderBy('nama')->get(),
            'pa_kpa' => DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd])->whereIn('kode',['PA','KPA'])->orderBy('nip')->orderBy('nama')->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran()
        ];

        return view('skpd.laporan_bendahara_penerimaan.index')->with($data);
    }
    
// get skpd by radio    
    public function cariSkpd(Request $request)
    {   
        $type       = Auth::user()->is_admin;
        $jenis      = $request->jenis;
        $kd_skpd    = $request->kd_skpd;
        $kd_org     = substr($kd_skpd,0,17);
        if ($type=='1'){
            if($jenis=='skpd'){
                $data   = DB::table('ms_organisasi')->select(DB::raw("kd_org AS kd_skpd"), DB::raw("nm_org AS nm_skpd"))->orderBy('kd_org')->get();
            }else{
                $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_skpd')->get();
            }
        }else{
            if($jenis=='skpd'){
                $data   = DB::table('ms_organisasi')->where(DB::raw("LEFT(kd_org)"),'=',$kd_org)->select(DB::raw("kd_org AS kd_skpd"), DB::raw("nm_org AS nm_skpd"))->get();
            }else{
                $data   = DB::table('ms_skpd')->where(DB::raw("LEFT(kd_skpd)"),'=',$kd_org)->select('kd_skpd', 'nm_skpd')->get();
            }
        }

        return response()->json($data);
    }

// get bendahara pengeluaran
    function cariBendahara(Request $request)
    {
        if(strlen($request->kd_skpd)=='17'){
            $kd_skpd    = $request->kd_skpd.'.0000';
        }else{
            $kd_skpd    = $request->kd_skpd;
        }
        $data       = DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd,'kode'=>'BP'])->orderBy('nip')->orderBy('nama')->get();
        return response()->json($data);

    }

    function cariPaKpa(Request $request)
    {
        if(strlen($request->kd_skpd)=='17'){
            $kd_skpd    = $request->kd_skpd.'.0000';
        }else{
            $kd_skpd    = $request->kd_skpd;
        }
        $data       = DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd])->whereIn('kode',['PA','KPA'])->orderBy('nip')->orderBy('nama')->get();
        return response()->json($data);

    }

    function cariSubkegiatan(Request $request)
    {
        $kd_skpd        = $request->kd_skpd;
        $jns_anggaran   = $request->jns_anggaran;
        $data           = DB::table('trskpd')->where(['kd_skpd' => $kd_skpd, 'jns_ang' => $jns_anggaran])->orderBy('kd_sub_kegiatan')->get();
        return response()->json($data);

    }
    function cariAkunBelanja(Request $request)
    {
        $kd_skpd        = $request->kd_skpd;
        $jns_anggaran   = $request->jns_anggaran;
        $subkegiatan    = $request->subkegiatan;
        $data           = DB::table('trdrka')->where(['kd_skpd' => $kd_skpd, 'jns_ang' => $jns_anggaran, 'kd_sub_kegiatan' => $subkegiatan])->orderBy('kd_rek6')->get();
        return response()->json($data);

    }

    
}
