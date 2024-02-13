<?php

namespace App\Http\Controllers\Akuntansi\calk;

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


class calk_lamp4Controller extends Controller
{

    public function calklamp4(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $jns_ang = $request->jns_ang;
        $bulan = $request->bulan;
        $kd_rek = $request->kd_rek;
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
            'jns_ang' => $jns_ang,
            'bulan' => $bulan,
            'kd_rek' => $kd_rek
        ];

        return view('akuntansi.cetakan.calk.lamp4.edit_lamp4')->with($data);
    }

    function cetak_calk22(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $kd_skpd        = $request->kd_skpd;  
        $lampiran       = $request->lampiran;
        $judul          = $request->judul;
        $ttd            = $request->ttd;
        $jenis          = $request->jenis;
        $skpdunit       = $request->skpdunit;
        $cetak          = $request->cetak;
        $tanggal = "31 Desember 2023";
        $tempat_tanggal = "Pontianak, 31 Desember 2023";
        $bulan          = 12;
        $thn_ang        = tahun_anggaran();
        $thn_ang_1        = $thn_ang-1;
        $thn_bln        = "$thn_ang$bulan";
        $angg="nilai";
        
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";

        $spasi = "line-height: 1.5em;";
        $peraturan   = "Peraturan Pemerintah Nomor 71 Tahun 2010";
        $permendagri = "Permendagri Nomor 64 Tahun 2013";
        if ($skpdunit=="skpd") {
            $nm_skpd = nama_org($kd_skpd);
        }else{
            $nm_skpd = nama_skpd($kd_skpd);
        }
        $kd_org = substr($kd_skpd, 0, 17);
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $skpd_clause_a= "left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $skpd_clause_d= "left(d.kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $skpd_clause_a= "left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $unit_clause= "left(kd_unit,len('$kd_skpd'))='$kd_skpd' ";
        $unit_clause_a= "left(a.kd_unit,len('$kd_skpd'))='$kd_skpd' ";

        $ttd_nih = collect(DB::select("SELECT nama,nip,jabatan,pangkat FROM ms_ttd where $skpd_clause and kode IN ('PA','KPA') and nip='$ttd'"))->first();

        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran,(select nama from tb_status_anggaran where a.jns_ang=kode)nama from trhrka a where $skpd_clause and status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;

        if($jns_ang=="U1"){
            $nm_jns_ang = 'PERUBAHAN' ;  
            $v = '1';
        }else if($jns_ang=='U2'){
               $nm_jns_ang = 'PERGESERAH PERUBAHAN I' ;
               $v = '2';
        }else{
               $nm_jns_ang = 'PERGESERAN' ;
               $v = '3';
        }

        $query = DB::select("SELECT *, (saldo_awal-berkurang+bertambah+tahun_n) as saldo_akhir from isi_lamp_perencanaan_teknis where $skpd_clause");

        $data = [
        'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'ttd_nih'       => $ttd_nih,
        'kd_skpd'       => $kd_skpd,
        'kd_skpd_edit'  => $kd_skpd,
        'nm_skpd'       => $nm_skpd,
        'lampiran'      => $lampiran,
        'judul'         => $judul,
        'bulan'         => $bulan,
        'jenis'         => $jenis,
        'skpdunit'      => $skpdunit,
        'tempat_tanggal'=> $tempat_tanggal,
        'tanggal'       => $tanggal,
        'query'       => $query,
        'peraturan'     => $peraturan,
        'peraturan'     => $peraturan,
        'permendagri'   => $permendagri,
        'cetak'         => $cetak,
        'spasi'         => $spasi,
        'jns_ang'       => $jns_ang,
        'nm_jns_ang'    => $nm_jns_ang,
        'trdju'         => $trdju,
        'trhju'         => $trhju,
        'thn_ang'       => $thn_ang ,
        'thn_ang_1'     => $thn_ang_1,
        'skpd_clause'   => $skpd_clause  
        ];
    
        $view =  view('akuntansi.cetakan.calk.lamp4.lamp4_index')->with($data);
        
        
        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('calk.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="calk.xls"');
            return $view;
        }
    }

    public function load_calklamp4(Request $request)
    {
        $kd_skpd = $request->kd_skpd;  
        $bulan   = $request->bulan;  
        $kd_rek   = $request->kd_rek;
        $thn_ang = tahun_anggaran();
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $leng_skpd = strlen($kd_skpd);
        if ($leng_skpd=="17") {
            $nm_skpd = nama_org($kd_skpd);
        }else{
            $nm_skpd = nama_skpd($kd_skpd);
        }
        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran,(select nama from tb_status_anggaran where a.jns_ang=kode)nama from trhrka a where $skpd_clause and status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
        
        $data = DB::select("SELECT * FROM isi_lamp_perencanaan_teknis WHERE kd_skpd='$kd_skpd'");
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {

            $btn = '<a href="javascript:void(0);" onclick="hapus(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->uraian . '\',\'' . $row->lokasi . '\',\'' . $row->alamat . '\',\'' . $row->tahun . '\',\'' . $row->saldo_awal . '\',\'' . $row->berkurang . '\',\'' . $row->bertambah . '\',\'' . $row->tahun_n . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function load_kd_rinci_calklamp4(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $kd_rek   = $request->kd_rek;
        
        $data = DB::select("SELECT case when kd_rinci = (select kd_rinci FROM isi_analisis_calk WHERE kd_skpd='$kd_skpd' and kd_rek='$kd_rek' and kd_rinci=a.kd_rinci)
                        then concat('9',kd_rinci) else kd_rinci end kd_rinci
            from(
                SELECT COUNT(kd_skpd)+1 kd_rinci FROM isi_analisis_calk WHERE kd_skpd='$kd_skpd' and kd_rek='$kd_rek'
            )a");
        return response()->json($data);
    }

    public function cari_lokasi(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $data   = DB::select("SELECT DISTINCT lokasi from ms_sekolah");
        return response()->json($data);
    }

    public function hapus_calklamp4(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $uraian             = $request->uraian;
        $lokasi             = $request->lokasi;
        $tahun             = $request->tahun;
        $saldo_awal               = $request->saldo_awal;
        $berkurang               = $request->berkurang;
        $bertambah               = $request->bertambah;
        $tahun_n               = $request->tahun_n;
        
        
        $query = DB::delete("DELETE from $tabel where kd_skpd='$kd_skpd' and uraian = '$uraian' and lokasi='$lokasi' and tahun='$tahun' and saldo_awal='$saldo_awal' and berkurang='$berkurang' and bertambah='$bertambah' and tahun_n='$tahun_n'");
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

    public function simpan_calklamp4(Request $request){
        $tabel              = $request->tabel;
        $kd_skpd            = $request->kd_skpd;
        $nm_skpd             = $request->nm_skpd;
        $lokasi             = $request->lokasi;
        $uraian               = $request->uraian;
        $alamat               = $request->alamat;
        $tahun               = $request->tahun;
        $saldo_awal               = $request->saldo_awal;
        $berkurang               = $request->berkurang;
        $bertambah               = $request->bertambah;
        $tahun_n               = $request->tahun_n;
        
        
        $asg = DB::insert("INSERT into $tabel (kd_skpd, nm_skpd, uraian, lokasi, alamat, tahun, saldo_awal, berkurang, bertambah,tahun_n) values ('$kd_skpd','$nm_skpd','$uraian','$lokasi','$alamat','$tahun',$saldo_awal,$berkurang,$bertambah,$tahun_n)");
        
        if ( $asg > 0 ){
           echo '1';
        } else {
           echo '0';
        }
    
    }


}
