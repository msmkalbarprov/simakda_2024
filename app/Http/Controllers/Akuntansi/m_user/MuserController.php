<?php

namespace App\Http\Controllers\Akuntansi\m_user;

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


class MuserController extends Controller
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

        return view('akuntansi.m_user.index')->with($data);
    }

    public function j_koreksi()
    {
        $username = Auth::user()->username;
        $role = Auth::user()->role;
        if ($role=='1022') {
            if ($username=='AKT01' || $username=='AKT02' || $username=='AKT03') {
                return view('muser.j_koreksi');
                
            }else{
                return view('akses_koreksi');
            }
        
        }else{
            return view('akuntansi.m_user.j_koreksi');
        }
    }

    public function load_jkoreksi(Request $request)
    {
        $id = Auth::user()->id;
        $bulan   = $request->bulan;
        // dd($id);
        $data = DB::table('pengguna as a')
            ->selectRaw("*")
            ->where(['role' => '1007'])
            ->orderBy('kd_skpd')
            ->get();
        // dd($data);
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_skpd . '\',\'' . $row->username . '\',\'' . $row->nama . '\',\'' . $row->koreksi . '\');" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }
    public function simpan_j_koreksi (Request $request){
        $kdskpd         = $request->kdskpd;
        $username       = $request->username;
        $nama           = $request->nama;
        $koreksi        = $request->koreksi;
        $update         = date('Y-m-d');
        // $username       = Auth::user()->nama;
        $asg = DB::update("UPDATE pengguna SET koreksi='$koreksi' WHERE kd_skpd='$kdskpd' AND username='$username' AND nama='$nama'");
        
    }


}
