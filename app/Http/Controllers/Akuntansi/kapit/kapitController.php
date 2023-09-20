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
        $no_lamp= collect(DB::select("SELECT CONVERT(varchar(10),jumlah)+'-'+REPLACE(kd_skpd,'.','') as nomor FROM
            (SELECT COUNT(*)+1 as jumlah, kd_skpd FROM(
            SELECT no_lamp,kd_skpd FROM lamp_aset UNION ALL
            SELECT no_lamp,kd_skpd FROM trdkapitalisasi) z
            WHERE kd_skpd=? GROUP BY kd_skpd)y",[$kd_skpd]))->first();
        // dd($no_lamp);
        $data = [
            'bendahara' => DB::table('ms_ttd')
                ->whereIn('kode', ['1'])
                ->orderBy('nip')
                ->orderBy('nama')
                ->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran(),
            'no_lamp' => $no_lamp
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

    public function cari_rek3(Request $request)
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

    public function cari_rek5(Request $request)
    {
        $rek3   = $request->rek3;
        if($rek3=='8'){
            $data = DB::select("SELECT a.kd_rek5,a.nm_rek5, case when b.kd_rek5 is null then 0 else 1 end jenis 
            FROM ms_rek5 a left join (select distinct kd_rek5 from ms_rek6) b on a.kd_rek5=b.kd_rek5 
            WHERE a.kd_rek5 in('81010307','81020101','81020201',
                               '81020208','81020303','81020304',
                               '81020305','81020401','81020402') 
            ORDER BY a.kd_rek5");
        }else{

            $data = DB::select("SELECT a.kd_rek5,a.nm_rek5, case when b.kd_rek5 is null then 0 else 1 end jenis 
            FROM ms_rek5 a left join (select distinct kd_rek5 from ms_rek6) b on a.kd_rek5=b.kd_rek5 
            WHERE left(a.kd_rek5,4)='$rek3'
            ORDER BY a.kd_rek5");
        }
        // dd($rek3);
        return response()->json($data);

    }

    public function cari_rek6(Request $request)
    {
        $rek5   = $request->rek5;
        $data = DB::select("SELECT b.kd_rek5,b.nm_rek5,kd_rek6,nm_rek6 FROM ms_rek6 a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
            WHERE left(kd_rek6,8)='$rek5'
            ORDER BY kd_rek6");
        //jika dipakai $data = DB::select("SELECT b.kd_rek5,b.nm_rek5,kd_rek6,nm_rek6 FROM ms_rek6 a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
            // WHERE left(a.kd_rek5,4)='$rek3' 
            // union all
            // select * from 
            // (select b.kd_rek5,b.nm_rek5,kd_rek6,nm_rek6 FROM ms_rek6 a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
            //         WHERE left(a.kd_rek5,1)='$kdrek3' ) z where kd_rek6 between '810201010001' and '810201010051' or left(kd_rek6,8) between '81020208' and '81020209' or left(kd_rek6,6)='810203' or kd_rek6 in('810103070002','810202010062') 
            // ORDER BY kd_rek6");
        return response()->json($data);

    }

    public function cari_rek7(Request $request)
    {
        $rek6   = $request->rek6;
        $data = DB::select("SELECT kd_rek7,nm_rek7 FROM ms_rek7_ WHERE kd_rek6='$rek6'");
        return response()->json($data);

    }

    public function cari_lokasi(Request $request)
    {
        $data = DB::select("SELECT DISTINCT lokasi from ms_sekolah");
        return response()->json($data);

    }

    //inputan
    public function input_lamp_neraca()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_lamp= collect(DB::select("SELECT CONVERT(varchar(10),jumlah)+'-'+REPLACE(kd_skpd,'.','') as nomor FROM
            (SELECT COUNT(*)+1 as jumlah, kd_skpd FROM(
            SELECT no_lamp,kd_skpd FROM lamp_aset UNION ALL
            SELECT no_lamp,kd_skpd FROM trdkapitalisasi) z
            WHERE kd_skpd=? GROUP BY kd_skpd)y",[$kd_skpd]))->first();
        $data = [
            'bendahara' => DB::table('ms_ttd')
                ->whereIn('kode', ['1'])
                ->orderBy('nip')
                ->orderBy('nama')
                ->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran(),
            'no_lamp' => $no_lamp
        ];

        return view('akuntansi.lamp_neraca.inputan.input')->with($data);
    }
    public function load_input_lamp_neraca(Request $request)
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

    public function cek_simpan(Request $request){
        $nomor    = $request->no;
        $tabel   = $request->tabel;
        $field    = $request->field;
        $field2    = $request->field2;
        $tabel2   = $request->tabel2;
        $kd_skpd  = Auth::user()->kd_skpd;
        if ($field2==''){
        $hasil=collect(DB::select("SELECT  count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd'"))->first();
        } else{
        $hasil=collect(DB::select("SELECT count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL SELECT $field2 as nomor FROM $tabel2 WHERE kd_skpd = '$kd_skpd')a WHERE a.nomor = '$nomor'"))->first();
        }
        $jumlah=$hasil->jumlah; 
        
        if($jumlah>0){
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
        } else{
        $msg = array('pesan'=>'0');
        echo json_encode($msg);
        }
    }

    public function simpan_lamp_aset(Request $request){
        
        $tabel   = $request->tabel;
        $lckolom = $request->kolom;
        $lcnilai = $request->nilai;
        $cid     = $request->cid;
        $lcid    = $request->lcid;
        
        $asg = DB::insert("insert into $tabel $lckolom values $lcnilai");
        if($asg){
            echo '2';
        }else{
            echo '0';
        }
    }

    public function update_lamp_aset(Request $request){
        $skpd   = Auth::user()->kd_skpd;
        $tabel  = $request->tabel;
        $cid    = $request->cid;
        $lcid   = $request->lcid;
        $lcid_h = $request->lcid_h;
        
        if (  $lcid <> $lcid_h ) {
            
           $res     = DB::select("select $cid from $tabel where $cid='$lcid' AND kd_skpd='$skpd'");
           if ( count($res)>0 ) {
                echo '1';
                exit();
           } 
        }
        
        $query   = $request->st_query;
        $asg     = DB::update("$query");
        if ( $asg > 0 ){
           echo '2';
        } else {
           echo '0';
        }
    
    }

    public function hapus_lamp_aset(Request $request){
        $kd_skpd   = Auth::user()->kd_skpd;
        $nomor  = $request->no;

        $query = DB::delete("delete from lamp_aset where no_lamp='$nomor' and kd_skpd = '$kd_skpd'");
        if ($query) {
            return response()->json([
                'pesan' => '1'
            ]);
        } else {
            return response()->json([
                'pesan' => '0'
            ]);
        }
    
    }

    


}
