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


class calk_lamp3Controller extends Controller
{

    public function calklamp3(Request $request)
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

        return view('akuntansi.cetakan.calk.lamp3.edit_lamp3')->with($data);
    }

    function cetak_calk21(Request $request)
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

        $query = DB::select("SELECT *,(select nm_program from ms_program b where b.kd_program=left(kd_sub_kegiatan,7)) as nm_program
                 from isi_lamp_daftar_pemeliharaan a  where $skpd_clause");

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
    
        $view =  view('akuntansi.cetakan.calk.lamp3.lamp3_index')->with($data);
        
        
        
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

    public function load_calklamp3(Request $request)
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
        
        $data = DB::select("SELECT * FROM isi_lamp_daftar_pemeliharaan WHERE kd_skpd='$kd_skpd'");
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {

            $btn = '<a href="javascript:void(0);" onclick="hapus(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->kd_sub_kegiatan . '\',\'' . $row->nm_sub_kegiatan . '\',\'' . $row->nilai . '\',\'' . $row->pelaksana . '\',\'' . $row->nilai_jaminan . '\',\'' . $row->nm_penerbit . '\',\'' . $row->id . '\',\'' . $row->masa_awal . '\',\'' . $row->masa_akhir . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function load_kd_rinci_calklamp3(Request $request)
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

    public function cari_sub_kegiatan(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $data   = DB::select("SELECT a.kd_sub_kegiatan ,b.nm_sub_kegiatan 
            FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
            where a.kd_skpd='$kd_skpd' 
            group by a.kd_sub_kegiatan,b.nm_sub_kegiatan");
        return response()->json($data);
    }

    public function hapus_calklamp3(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_sub_kegiatan             = $request->kd_sub_kegiatan;
        $pelaksana             = $request->pelaksana;
        $nilai_jaminan               = $request->nilai_jaminan;
        $nilai               = $request->nilai;
        
        
        $query = DB::delete("DELETE from $tabel where kd_skpd='$kd_skpd' and kd_sub_kegiatan = '$kd_sub_kegiatan' and pelaksana='$pelaksana' and nilai='$nilai' and nilai_jaminan='$nilai_jaminan'");
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

    public function simpan_calklamp3(Request $request){
        $tabel              = $request->tabel;
        $kd_skpd            = $request->kd_skpd;
        $nm_skpd             = $request->nm_skpd;
        $kd_sub_kegiatan             = $request->kd_sub_kegiatan;
        $nm_sub_kegiatan = nama_kegiatan($kd_sub_kegiatan);
        $nilai               = $request->nilai;
        $pelaksana               = $request->pelaksana;
        $nilai_jam               = $request->nilai_jam;
        $nm_penerbit               = $request->nm_penerbit;
        $masa_awal               = $request->masa_awal;
        $masa_akhir               = $request->masa_akhir;
        
        
        $asg = DB::insert("INSERT into $tabel (kd_skpd, nm_skpd, kd_sub_kegiatan, nm_sub_kegiatan, nilai, pelaksana, nilai_jaminan, nm_penerbit,masa_awal,masa_akhir) values ('$kd_skpd','$nm_skpd','$kd_sub_kegiatan','$nm_sub_kegiatan',$nilai,'$pelaksana',$nilai_jam,'$nm_penerbit','$masa_awal','$masa_akhir')");
        
        if ( $asg > 0 ){
           echo '1';
        } else {
           echo '0';
        }
    
    }


}
