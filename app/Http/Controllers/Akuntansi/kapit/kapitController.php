<?php

namespace App\Http\Controllers\Akuntansi\kapit;

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


class kapitController extends Controller
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

        return view('akuntansi.kapit.index')->with($data);
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
        //jaga jaga jika ini yang di pakai
        // $data           = DB::select("SELECT kd_rek3,nm_rek3 FROM ms_rek3 WHERE (left(kd_rek3,2) in ('11','12') OR left(kd_rek3,1)='2' OR kd_rek3='313') 
        //     UNION 
        //     select '15' as kd_rek3, 'Aset Lainnya' as nm_rek3 union SELECT kd_rek3,nm_rek3 FROM ms_rek3 WHERE left(kd_rek3,2) in ('15')
        //     union 
        //     select '1103-1109' as kd_rek3, 'Keseluruhan Piutang' as nm_rek3
        //     ORDER BY kd_rek3");
        $data = DB::select("SELECT kd_rek3,nm_rek3 FROM ms_rek3 WHERE (left(kd_rek3,2) in ('11','12') OR left(kd_rek3,1)='2' OR kd_rek3='313')
        ORDER BY kd_rek3");
        return response()->json($data);

    }

    //inputan
    public function input_kapitalisasi()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $jns_ang = collect(DB::select("SELECT TOP 1 jns_ang from trhrka where kd_skpd=? order by tgl_dpa DESC",[$kd_skpd]))->first();
        $sub_kegiatan = DB::select("SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan,a.jns_kegiatan 
                        FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                        where a.kd_skpd=? and a.jns_ang=?",[$kd_skpd,$jns_ang->jns_ang]);
        $data = [
            'bendahara' => DB::table('ms_ttd')
                ->whereIn('kode', ['1'])
                ->orderBy('nip')
                ->orderBy('nama')
                ->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran(),
            'kd_skpd' => $kd_skpd,
            'sub_kegiatan' => $sub_kegiatan
        ];

        return view('akuntansi.kapit.inputan.input')->with($data);
    }
    public function load_input_kapitalisasi(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;  
        $bulan   = $request->bulan;
        // dd($id);
        $data = DB::select("SELECT a.*, case when b.kd_rek5 is null then 0 else 1 end jenis from lamp_aset a left join (select distinct kd_rek5 from ms_rek6) b on a.kd_rek5=b.kd_rek5 where kd_skpd='$kd_skpd'");
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="edit(\'' . $row->no_lamp . '\',\'' . $row->kd_rek3 . '\',\'' . $row->nm_rek3 . '\',\'' . $row->kd_rek5 . '\',\'' . $row->nm_rek5 . '\',\'' . $row->kd_rek6 . '\',\'' . $row->nm_rek6 . '\',\'' . $row->tahun . '\',\'' . $row->merk . '\',\'' . $row->no_polisi . '\',\'' . $row->fungsi . '\',\'' . $row->hukum . '\',\'' . $row->lokasi . '\',\'' . $row->alamat . '\',\'' . $row->sert . '\',\'' . $row->luas . '\',\'' . $row->satuan . '\',\'' . $row->harga_satuan . '\',\'' . $row->piutang_awal . '\',\'' . $row->piutang_koreksi . '\',\'' . $row->piutang_sudah . '\',\'' . $row->investasi_awal . '\',\'' . $row->sal_awal . '\',\'' . $row->kurang . '\',\'' . $row->tambah . '\',\'' . $row->tahun_n . '\',\'' . $row->akhir . '\',\'' . $row->kondisi_b . '\',\'' . $row->kondisi_rr . '\',\'' . $row->kondisi_rb . '\',\'' . $row->keterangan . '\',\'' . $row->kd_skpd . '\',\'' . $row->jumlah . '\',\'' . $row->kepemilikan . '\',\'' . $row->rincian_beban. '\',\'' . $row->jenis_aset . '\',\'' . $row->realisasi_janji . '\',\'' . $row->nama_perusahaan . '\',\'' . $row->no_polis . '\',\'' . $row->tgl_awal . '\',\'' . $row->tgl_akhir . '\',\'' . $row->jam . '\',\'' . $row->bulan . '\',\'' . $row->masa . '\',\'' . $row->tmasa . '\',\'' . $row->korplus . '\',\'' . $row->kormin . '\',\'' . $row->akum_penyu . '\',\'' . $row->sisa_umur . '\',\'' . $row->status . '\',\'' . $row->akum_penyub . '\',\'' . $row->kondisi_x . '\',\'' . $row->nil_kurang_excomp . '\',\'' . $row->status_extracomp . '\',\'' . $row->sekolah . '\',\'' . $row->kd_rek7 . '\',\'' . $row->nm_rek7 . '\',\'' . $row->jenis . '\');" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_lamp . '\',\'' . $row->kd_rek3 . '\',\'' . $row->nm_rek3 . '\',\'' . $row->kd_rek5 . '\',\'' . $row->nm_rek5 . '\',\'' . $row->kd_rek6 . '\',\'' . $row->nm_rek6 . '\',\'' . $row->tahun . '\',\'' . $row->merk . '\',\'' . $row->no_polis . '\',\'' . $row->fungsi . '\',\'' . $row->hukum . '\',\'' . $row->lokasi . '\',\'' . $row->alamat . '\',\'' . $row->sert . '\',\'' . $row->luas . '\',\'' . $row->satuan . '\',\'' . $row->harga_satuan . '\',\'' . $row->piutang_awal . '\',\'' . $row->piutang_koreksi . '\',\'' . $row->piutang_sudah . '\',\'' . $row->investasi_awal . '\',\'' . $row->sal_awal . '\',\'' . $row->kurang . '\',\'' . $row->tambah . '\',\'' . $row->tahun_n . '\',\'' . $row->akhir . '\',\'' . $row->kondisi_b . '\',\'' . $row->kondisi_rr . '\',\'' . $row->kondisi_rb . '\',\'' . $row->keterangan . '\',\'' . $row->kd_skpd . '\',\'' . $row->jumlah . '\',\'' . $row->kepemilikan . '\',\'' . $row->rincian_beban. '\',\'' . $row->jenis_aset . '\',\'' . $row->realisasi_janji . '\',\'' . $row->nama_perusahaan . '\',\'' . $row->no_polis . '\',\'' . $row->tgl_awal . '\',\'' . $row->tgl_akhir . '\',\'' . $row->jam . '\',\'' . $row->bulan . '\',\'' . $row->masa . '\',\'' . $row->tmasa . '\',\'' . $row->korplus . '\',\'' . $row->kormin . '\',\'' . $row->akum_penyu . '\',\'' . $row->sisa_umur . '\',\'' . $row->status . '\',\'' . $row->akum_penyub . '\',\'' . $row->kondisi_x . '\',\'' . $row->nil_kurang_excomp . '\',\'' . $row->status_extracomp . '\',\'' . $row->sekolah . '\',\'' . $row->kd_rek7 . '\',\'' . $row->nm_rek7 . '\',\'' . $row->jenis . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    


}
