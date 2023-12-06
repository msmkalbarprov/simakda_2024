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


class calk_bab3lra_belController extends Controller
{

    public function calkbab3_lra_bel(Request $request)
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

        return view('akuntansi.cetakan.calk.bab3.lra_bel.edit_bab3_lra_bel')->with($data);
    }

    function cetak_calk12(Request $request)
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
        $tanggal = "29 Desember 2023";
        $tempat_tanggal = "Pontianak, 29 Desember 2023";
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
        

        $prv = collect(DB::select("SELECT ISNULL(sum(a.nilai),0) as anggaran
                    from (select kd_skpd, kd_rek6, kd_sub_kegiatan, nilai 
                          from trdrka 
                          where left(kd_rek6,1)=4 and jns_ang='$jns_ang' and left(kd_rek6,1)='5'
                         ) a 
                    where $skpd_clause"))->first();
        $cek = $prv->anggaran;
        
        $sql = DB::select("SELECT *, case when anggaran<>0 then realisasi/anggaran else 0 end persen  , realisasi-anggaran selisih, realisasi-realisasi_lalu selisih_tahun
                from(select a.kd_skpd,a.kd_rek,
                    case when len(kd_rek)=1 then (select nm_rek1 from ms_rek1 where kd_rek=kd_rek1)
                         when len(kd_rek)=2 then (select nm_rek2 from ms_rek2 where kd_rek=kd_rek2)
                         when len(kd_rek)=4 then (select nm_rek3 from ms_rek3 where kd_rek=kd_rek3)
                         when len(kd_rek)=6 then (select nm_rek4 from ms_rek4 where kd_rek=kd_rek4) end nm_rek,
                    isnull(sum(anggaran),0) anggaran, isnull(sum(realisasi),0)realisasi,isnull(sum(realisasi_lalu),0)realisasi_lalu
                    from(select a.kd_skpd,a.kd_rek, isnull(sum(anggaran),0) anggaran, isnull(sum(realisasi),0)realisasi,isnull(sum(realisasi_lalu),0)realisasi_lalu
                        from(select kd_skpd,left(kd_rek6,1)kd_rek, sum(nilai) anggaran, 0 realisasi 
                             from trdrka 
                             where jns_ang='$jns_ang' and left(kd_rek6,1)='5'
                             group by kd_skpd,left(kd_rek6,1)
                             union all
                             select b.kd_skpd, left(kd_rek6,1)kd_rek,0 anggaran, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi 
                             from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                             where left(a.kd_rek6,1)='5' and YEAR(b.tgl_voucher)='$thn_ang' 
                             group by kd_skpd,left(kd_rek6,1)
                            )a
                            LEFT JOIN
                            (select b.kd_skpd, LEFT(a.kd_rek6,1) kd_rek, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi_lalu 
                             from simakda_2022.dbo.$trdju a inner join simakda_2022.dbo.$trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                             where left(a.kd_rek6,1)='5'  and YEAR(b.tgl_voucher)='$thn_ang_1'
                             group by b.kd_skpd, LEFT(a.kd_rek6,1)
                            ) b on a.kd_skpd=b.kd_skpd and a.kd_rek=b.kd_rek
                        group by a.kd_skpd,a.kd_rek
                        union all
                        select a.kd_skpd,a.kd_rek, isnull(sum(anggaran),0) anggaran, isnull(sum(realisasi),0)realisasi,isnull(sum(realisasi_lalu),0)realisasi_lalu
                        from(select kd_skpd,left(kd_rek6,2)kd_rek, sum(nilai) anggaran, 0 realisasi 
                             from trdrka 
                             where jns_ang='$jns_ang' and left(kd_rek6,1)='5'
                             group by kd_skpd,left(kd_rek6,2)
                             union all
                             select b.kd_skpd, left(kd_rek6,2)kd_rek,0 anggaran, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi 
                             from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                             where left(a.kd_rek6,1)='5' and YEAR(b.tgl_voucher)='$thn_ang' 
                             group by kd_skpd,left(kd_rek6,2)
                            )a
                            LEFT JOIN
                            (select b.kd_skpd, LEFT(a.kd_rek6,2) kd_rek, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi_lalu 
                             from simakda_2022.dbo.$trdju a inner join simakda_2022.dbo.$trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                             where left(a.kd_rek6,1)='5'  and YEAR(b.tgl_voucher)='$thn_ang_1'
                             group by b.kd_skpd, LEFT(a.kd_rek6,2)
                            ) b on a.kd_skpd=b.kd_skpd and a.kd_rek=b.kd_rek
                        group by a.kd_skpd,a.kd_rek
                        union all
                        select a.kd_skpd,a.kd_rek, isnull(sum(anggaran),0) anggaran, isnull(sum(realisasi),0)realisasi,isnull(sum(realisasi_lalu),0)realisasi_lalu
                        from(select kd_skpd,left(kd_rek6,4)kd_rek, sum(nilai) anggaran, 0 realisasi 
                             from trdrka 
                             where jns_ang='$jns_ang' and left(kd_rek6,1)='5'
                             group by kd_skpd,left(kd_rek6,4)
                             union all
                             select b.kd_skpd, left(kd_rek6,4)kd_rek,0 anggaran, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi 
                             from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                             where left(a.kd_rek6,1)='5' and YEAR(b.tgl_voucher)='$thn_ang' 
                             group by kd_skpd,left(kd_rek6,4)
                            )a
                            LEFT JOIN
                            (select b.kd_skpd, LEFT(a.kd_rek6,4) kd_rek, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi_lalu 
                             from simakda_2022.dbo.$trdju a inner join simakda_2022.dbo.$trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                             where left(a.kd_rek6,1)='5'  and YEAR(b.tgl_voucher)='$thn_ang_1'
                             group by b.kd_skpd, LEFT(a.kd_rek6,4)
                            ) b on a.kd_skpd=b.kd_skpd and a.kd_rek=b.kd_rek
                        group by a.kd_skpd,a.kd_rek
                        union all
                        select a.kd_skpd,a.kd_rek, isnull(sum(anggaran),0) anggaran, isnull(sum(realisasi),0)realisasi,isnull(sum(realisasi_lalu),0)realisasi_lalu
                        from(select kd_skpd,left(kd_rek6,6)kd_rek, sum(nilai) anggaran, 0 realisasi 
                             from trdrka 
                             where jns_ang='$jns_ang' and left(kd_rek6,1)='5'
                             group by kd_skpd,left(kd_rek6,6)
                             union all
                             select b.kd_skpd, left(kd_rek6,6)kd_rek,0 anggaran, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi 
                             from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                             where left(a.kd_rek6,1)='5' and YEAR(b.tgl_voucher)='$thn_ang' 
                             group by kd_skpd,left(kd_rek6,6)
                            )a
                            LEFT JOIN
                            (select b.kd_skpd, LEFT(a.kd_rek6,6) kd_rek, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as realisasi_lalu 
                             from simakda_2022.dbo.$trdju a inner join simakda_2022.dbo.$trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                             where left(a.kd_rek6,1)='5'  and YEAR(b.tgl_voucher)='$thn_ang_1'
                             group by b.kd_skpd, LEFT(a.kd_rek6,6)
                            ) b on a.kd_skpd=b.kd_skpd and a.kd_rek=b.kd_rek
                        group by a.kd_skpd,a.kd_rek
                    )a
                    where $skpd_clause
                group by a.kd_skpd,a.kd_rek
                )a 
                order by a.kd_skpd,a.kd_rek");
            


        
        
        $data = [
        'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'ttd_nih'       => $ttd_nih,
        'kd_skpd'       => $kd_skpd,
        'kd_skpd_edit'       => $kd_skpd,
        'nm_skpd'       => $nm_skpd,
        'lampiran'      => $lampiran,
        'judul'         => $judul,
        'bulan'         => $bulan,
        'jenis'         => $jenis,
        'skpdunit'      => $skpdunit,
        'tempat_tanggal'=> $tempat_tanggal,
        'tanggal'       => $tanggal,
        'peraturan'     => $peraturan,
        'permendagri'   => $permendagri,
        'sql'        => $sql,
        'cetak'         => $cetak,
        'spasi'         => $spasi,
        'jns_ang'         => $jns_ang,
        'nm_jns_ang'         => $nm_jns_ang,
        'thn_ang'       => $thn_ang ,
        'thn_ang_1'       => $thn_ang_1  
        ];
    
        $view =  view('akuntansi.cetakan.calk.bab3.lra_bel.bab3_lra_index')->with($data);
        
        
        
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

    public function load_calkbab3_lra_bel(Request $request)
    {
        $kd_skpd = $request->kd_skpd;  
        $bulan   = $request->bulan;  
        $kd_rek   = $request->kd_rek;
        $thn_ang = tahun_anggaran();
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran,(select nama from tb_status_anggaran where a.jns_ang=kode)nama from trhrka a where $skpd_clause and status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
        $data = DB::select("SELECT a.kd_skpd,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd,a.kd_rek, a.nm_rek, b.ket1, b.ket2 
                FROM (Select kd_skpd, left(kd_ang,6) kd_ang, left(kd_rek6,6) kd_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(kd_ang,6)) nm_rek
                      FROM (select a.kd_skpd, a.kd_rek6 kd_ang, a.kd_rek6 from
                            (select kd_skpd, kd_rek6 from trdrka where left(kd_rek6,1)=4 and jns_ang='$jns_ang'
                             group by kd_skpd, kd_rek6
                             ) a
                             LEFT JOIN
                             (select b.kd_skpd, a.kd_rek6
                              from trdju_calk a inner join trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                              where left(a.kd_rek6,1)=4 and YEAR(b.tgl_voucher)='2023' 
                              group by kd_skpd, a.kd_rek6
                             )b on a.kd_skpd=b.kd_skpd and a.kd_rek6=b.kd_rek6 
                            ) z
                      WHERE kd_skpd='$kd_skpd' and LEFT(kd_rek6,4)='$kd_rek'
                      GROUP BY kd_skpd, left(kd_ang,6), left(kd_rek6,6) ) a
                     LEFT JOIN 
                     (select * from lamp_calk_bab3_lra_pend where kd_skpd='$kd_skpd'
                     ) b on a.kd_skpd=b.kd_skpd and a.kd_rek=b.kd_rek4 and a.kd_ang=b.kd_ang");
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->kd_rek . '\',\'' . $row->nm_rek . '\',\'' . $row->ket1 . '\',\'' . $row->ket2 . '\');" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpan_calkbab3_lra_bel(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $nm_rek            = $request->nm_rek;
        $ket1               = $request->ket1;
        $ket2               = $request->ket2;
        
        $hasil=collect(DB::select("SELECT  count(*) as jumlah FROM $tabel where kd_skpd='$kd_skpd' and kd_rek4='$kd_rek'"))->first();

        $jumlah=$hasil->jumlah; 
        
        if($jumlah>0){
            $asg     = DB::update("UPDATE $tabel SET ket1='$ket1', ket2='$ket2' where kd_rek4='$kd_rek'");
        } else{
            $asg = DB::insert("INSERT into $tabel (kd_skpd,kd_rek4,nm_rek4,ket1,kd_ang,ket2,jenis) values ('$kd_skpd','$kd_rek', '$nm_rek','$ket1', '$kd_rek','$ket2', '')");
        }
        
        if ( $asg > 0 ){
           echo '1';
        } else {
           echo '0';
        }
    
    }


}
