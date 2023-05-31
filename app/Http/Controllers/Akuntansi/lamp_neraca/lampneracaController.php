<?php

namespace App\Http\Controllers\Akuntansi\lamp_neraca;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;
use PhpParser\ErrorHandler\Collecting;
use PDF;
use Knp\Snappy\Pdf as SnappyPdf;
use Yajra\DataTables\Facades\DataTables;


class lampneracaController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'bendahara' => DB::table('ms_ttd')
                ->whereIn('kode', ['1'])
                ->orderBy('nip')
                ->orderBy('nama')
                ->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran()
        ];

        return view('akuntansi.lamp_neraca.index')->with($data);
    }

    public function cariSkpd(Request $request)
    {
        $type       = Auth::user()->is_admin;
        // $jenis      = $request->jenis;
        $jenis_skpd = substr(Auth::user()->kd_skpd, 18, 4);
        if ($jenis_skpd=='0000') {
            $jenis  = 'skpd';
        }else{
            $jenis  = 'unit';
        }
        $kd_skpd    = Auth::user()->kd_skpd;
        $kd_org     = substr($kd_skpd, 0, 17);
        if ($type == '1') {
            if ($jenis == 'skpd') {
                $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_org')->get();
            } else {
                $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_skpd')->get();
            }
        } else {
            if ($jenis == 'skpd') {
                // select kd_org AS kd_skpd, nm_org AS nm_skpd from [ms_skpd] where LEFT(kd_org) = 5.02.0.00.0.00.01)
                $data   = DB::table('ms_skpd')->where(DB::raw("LEFT(kd_skpd,17)"), '=', $kd_org)->select(DB::raw("kd_skpd AS kd_skpd"), DB::raw("nm_skpd AS nm_skpd"))->get();
            } else {
                $data   = DB::table('ms_skpd')->where(DB::raw("kd_skpd"), '=', $kd_skpd)->select('kd_skpd', 'nm_skpd')->get();
            }
        }
        // dd($kd_skpd);
        return response()->json($data);
    }

    public function cari_rek_objek(Request $request)
    {
        
        $data           = DB::table('ms_rek6')
                        ->orderBy('kd_rek6')->get();
        return response()->json($data);

    }

    


}
