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

        return view('akuntansi.pengesahan_spj.index')->with($data);
    }

    public function load_penerimaan(Request $request)
    {
        $id = Auth::user()->id;
        $bulan   = $request->bulan;
        // dd($id);
        $data = DB::table('trhspj_terima_ppkd as a')
            ->selectRaw("a.*,(SELECT nm_skpd from ms_skpd where kd_skpd=a.kd_skpd)nm_skpd")
            ->where(['bulan' => $bulan])
            ->whereRaw("kd_skpd IN (SELECT kd_skpd FROM user_akt WHERE user_id='$id')")
            ->orderBy('kd_skpd')
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->tgl_terima . '\',\'' . $row->real_terima . '\',\'' . $row->real_setor . '\',\'' . $row->sisa . '\',\'' . $row->spj . '\',\'' . $row->bku . '\',\'' . $row->koran . '\',\'' . $row->sts . '\',\'' . $row->ket . '\',\'' . $row->cek . '\');" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }


}
