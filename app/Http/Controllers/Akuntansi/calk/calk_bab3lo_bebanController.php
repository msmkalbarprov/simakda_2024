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


class calk_bab3lo_bebanController extends Controller
{

    public function calkbab3_lo_beban(Request $request)
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

        return view('akuntansi.cetakan.calk.bab3.lo_beban.edit_bab3_lo_beban')->with($data);
    }

    function cetak_calk14(Request $request)
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
        // map kode
            $sql51 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=1 AND kode=5"))->first();
            $kd_rek51 = $sql51->kd_rek;

            $sql81 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=1 AND kode=8"))->first();
            $kd_rek81 = $sql81->kd_rek;

            $sql21 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=1 AND kode=2"))->first();
            $kd_rek21 = $sql21->kd_rek;

            $sql52 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=2 AND kode=5"))->first();
            $kd_rek52 = $sql52->kd_rek;

            $sql82 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=2 AND kode=8"))->first();
            $kd_rek82 = $sql82->kd_rek;

            $sql22 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=2 AND kode=2"))->first();
            $kd_rek22 = $sql22->kd_rek;

            $sql53 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=3 AND kode=5"))->first();
            $kd_rek53 = $sql53->kd_rek;

            $sql83 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=3 AND kode=8"))->first();
            $kd_rek83 = $sql83->kd_rek;

            $sql23 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=3 AND kode=2"))->first();
            $kd_rek23 = $sql23->kd_rek;

            $sql54 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=4 AND kode=5"))->first();
            $kd_rek54 = $sql54->kd_rek;

            $sql84 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=4 AND kode=8"))->first();
            $kd_rek84 = $sql84->kd_rek;

            $sql24 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=4 AND kode=2"))->first();
            $kd_rek24 = $sql24->kd_rek;
        //


        $kode_8 = DB::select("SELECT kd_rek,nm_rek,realisasi,real_tlalu,(real_tlalu-realisasi)kenaikan,
            case when realisasi<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
            from(
                SELECT kd_rek,nm_rek, sum(realisasi)realisasi, sum(real_tlalu) real_tlalu,sum(real_lra)real_lra
            from(SELECT kd_skpd,kd_rek,nm_rek, sum(realisasi)realisasi, sum(real_tlalu) real_tlalu,sum(real_lra)real_lra
            from(SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,1) as kd_rek,(select nm_rek1 from ms_rek1 where kd_rek1=LEFT(kd_rek6,1)) as nm_rek,  sum(debet-kredit) realisasi, 0 as real_tlalu , 0 real_lra
            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
            where LEFT(kd_rek6,1) IN ('8')  AND YEAR(tgl_voucher)='$thn_ang'
            group by kd_skpd, LEFT(kd_rek6,1)
            union all
            SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,1) as kd_rek,(select nm_rek1 from ms_rek1 where kd_rek1=LEFT(kd_rek6,1)) as nm_rek,  0 realisasi, sum(debet-kredit) as real_tlalu ,0 real_lra
            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
            where LEFT(kd_rek6,1) IN ('8')  AND YEAR(tgl_voucher)='$thn_ang_1'
            group by kd_skpd, LEFT(kd_rek6,1)
            union all
            SELECT kd_skpd as kd_skpd, LEFT(map_lo,1) as kd_rek,(select nm_rek1 from ms_rek1 where kd_rek1=LEFT(map_lo,1)) as nm_rek,  0 realisasi, 0 as real_tlalu ,  sum(debet-kredit) real_lra
            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
            where LEFT(map_lo,1) IN ('8')  AND YEAR(tgl_voucher)='$thn_ang'
            group by kd_skpd, LEFT(map_lo,1)
            union all
            SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek6,2)) as nm_rek,  sum(debet-kredit) realisasi, 0 as real_tlalu , 0 real_lra
            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
            where LEFT(kd_rek6,1) IN ('8')  AND YEAR(tgl_voucher)='$thn_ang'
            group by kd_skpd, LEFT(kd_rek6,2)
            union all
            SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek6,2)) as nm_rek,   0 realisasi, sum(debet-kredit) as real_tlalu , 0 real_lra
            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
            where LEFT(kd_rek6,1) IN ('8')  AND YEAR(tgl_voucher)='$thn_ang_1'
            group by kd_skpd, LEFT(kd_rek6,2)
            union all
            SELECT kd_skpd as kd_skpd, LEFT(map_lo,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(map_lo,2)) as nm_rek,  0 realisasi, 0 as real_tlalu ,  sum(debet-kredit) real_lra
            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
            where LEFT(map_lo,1) IN ('8') AND YEAR(tgl_voucher)='$thn_ang'
            group by kd_skpd, LEFT(map_lo,2)

            )a
            where $skpd_clause
            group by kd_skpd,kd_rek,nm_rek)a
            group by kd_rek,nm_rek
            )a 
            order by kd_rek,nm_rek");
        $kode_81 = DB::select("SELECT kd_rek,nm_rek,realisasi,real_tlalu,(real_tlalu-realisasi)kenaikan,
            case when realisasi<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
            from(
                SELECT kd_rek,nm_rek, sum(realisasi)realisasi, sum(real_tlalu) real_tlalu,sum(real_lra)real_lra
            from(SELECT kd_skpd,kd_rek,nm_rek, sum(realisasi)realisasi, sum(real_tlalu) real_tlalu,sum(real_lra)real_lra
            from(SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek6,2)) as nm_rek,  sum(debet-kredit) realisasi, 0 as real_tlalu , 0 real_lra
            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
            where LEFT(kd_rek6,2) IN ('81')  AND YEAR(tgl_voucher)='$thn_ang'
            group by kd_skpd, LEFT(kd_rek6,2)
            union all
            SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek6,2)) as nm_rek,  0 realisasi, sum(debet-kredit) as real_tlalu ,0 real_lra
            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
            where LEFT(kd_rek6,2) IN ('81')  AND YEAR(tgl_voucher)='$thn_ang_1'
            group by kd_skpd, LEFT(kd_rek6,2)
            union all
            SELECT kd_skpd as kd_skpd, LEFT(map_lo,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(map_lo,2)) as nm_rek,  0 realisasi, 0 as real_tlalu ,  sum(debet-kredit) real_lra
            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
            where LEFT(map_lo,2) IN ('81')  AND YEAR(tgl_voucher)='$thn_ang'
            group by kd_skpd, LEFT(map_lo,2)
            union all
            SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(kd_rek6,4)) as nm_rek,  sum(debet-kredit) realisasi, 0 as real_tlalu , 0 real_lra
            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
            where LEFT(kd_rek6,2) IN ('81')  AND YEAR(tgl_voucher)='$thn_ang'
            group by kd_skpd, LEFT(kd_rek6,4)
            union all
            SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(kd_rek6,4)) as nm_rek,   0 realisasi, sum(debet-kredit) as real_tlalu , 0 real_lra
            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
            where LEFT(kd_rek6,2) IN ('81')  AND YEAR(tgl_voucher)='$thn_ang_1'
            group by kd_skpd, LEFT(kd_rek6,4)
            union all
            SELECT kd_skpd as kd_skpd, LEFT(map_lo,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(map_lo,4)) as nm_rek,  0 realisasi, 0 as real_tlalu ,  sum(debet-kredit) real_lra
            from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
            where LEFT(map_lo,2) IN ('81') AND YEAR(tgl_voucher)='$thn_ang'
            group by kd_skpd, LEFT(map_lo,4)

            )a
            where $skpd_clause
            group by kd_skpd,kd_rek,nm_rek)a
            group by kd_rek,nm_rek
            )a
            order by kd_rek,nm_rek");

        $kode_8102 = DB::select("SELECT kd_rek,nm_rek,nm_rek_bel,realisasi,real_tlalu,(real_tlalu-realisasi)kenaikan,
                   case when realisasi<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
            from(
                Select kd_rek,nm_rek,nm_rek_bel,sum(realisasi)realisasi,sum(real_lalu)real_tlalu,sum(real_lra)real_lra
                from(
                    select kd_skpd,left(a.kd_rek6,6)kd_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(a.kd_rek6,6))nm_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(c.kd_rek6,6))nm_rek_bel, sum(isnull(debet,0)-isnull(kredit,0)) realisasi,0 real_lalu,0 real_lra
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd inner join ms_rek6 c on a.kd_rek6=c.map_lo
                    where year(tgl_voucher)=$thn_ang and left(a.kd_rek6,4)='8102'
                    group by kd_skpd,left(a.kd_rek6,6),left(c.kd_rek6,6)
                    union all
                    select kd_skpd,left(a.kd_rek6,6)kd_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(a.kd_rek6,6))nm_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(c.kd_rek6,6))nm_rek_bel,0 realisasi ,sum(isnull(debet,0)-isnull(kredit,0)) real_lalu,0 real_lra
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd inner join ms_rek6 c on a.kd_rek6=c.map_lo
                    where year(tgl_voucher)=$thn_ang_1 and left(a.kd_rek6,4)='8102'
                    group by kd_skpd,left(a.kd_rek6,6),left(c.kd_rek6,6)
                    union all
                    select kd_skpd,left(c.map_lo,6)kd_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(c.map_lo,6))nm_rek,(select nm_rek4 from ms_rek4 where kd_rek4=left(c.kd_rek6,6))nm_rek_bel,0 realisasi ,0 real_lalu,sum(isnull(debet,0)-isnull(kredit,0)) real_lra
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                    where year(tgl_voucher)=$thn_ang and left(c.map_lo,4)='8102' 
                    group by kd_skpd,left(c.map_lo,6),left(c.kd_rek6,6)
                )a
                where $skpd_clause
                group by kd_rek,nm_rek,nm_rek_bel
            )a
            order by kd_rek");

        //810201
            if($kd_skpd=='1.03.01.01'){
                $nilai = "0";
            }else{
                $nilai = "debet-kredit";
            }
            
            if($kd_skpd=='1.02.02.01'){
                $blud_81037 = "SELECT '81037' kd_rek, ISNULL(SUM($nilai),0)*-1 nilai 
                FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                WHERE b.kd_unit='$kd_skpd' AND YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,12) IN ($kd_rek22,'2150210')";
            }else if($kd_skpd=='1.03.01.01'){
                $blud_81037 = "SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81037') ";
            }else{
                $blud_81037 = "SELECT '81037' kd_rek, ISNULL(SUM($nilai),0)*-1 nilai 
                FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                WHERE b.kd_unit='$kd_skpd' AND YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,12) IN ($kd_rek21,'2150210')";
            }

            if($kd_skpd=='1.04.2.10.0.00.01.0000' || $kd_skpd=='1.02.0.00.0.00.02.0000' || $kd_skpd=='1.03.1.04.0.00.01.0000'){
                $sql_81038 = "SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81038') ";
            }else{
                $sql_81038 = "SELECT '81038' kd_rek, ISNULL(SUM(debet-kredit),0) nilai 
                FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                WHERE b.kd_unit='$kd_skpd' AND YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,12) IN ($kd_rek21) ";
            }
        

            if ($kd_skpd=='1.04.2.10.0.00.01.0000'
                // ||$kd_skpd=='1.03.0.00.0.00.01.0000'
                ||$kd_skpd=='1.02.0.00.0.00.02.0000'
                ||$kd_skpd=='1.02.0.00.0.00.03.0000'
                ||$kd_skpd=='1.02.0.00.0.00.01.0000'
                ||$kd_skpd=='3.29.3.30.3.31.01.0000'
                ||$kd_skpd=='2.09.0.00.0.00.01.0000'
                ||$kd_skpd=='2.09.3.27.0.00.01.0000'
                ||$kd_skpd=='3.27.0.00.0.00.03.0003'
                ||$kd_skpd=='2.09.3.27.0.00.01.0002'
                ||$kd_skpd=='1.03.1.04.0.00.01.0000'
                ||$kd_skpd=='3.27.0.00.0.00.04.0000'
                ||$kd_skpd=='3.27.0.00.0.00.03.0003' 
                ||$kd_skpd=='3.27.0.00.0.00.01.0004' 
                ||$kd_skpd=='1.02.0.00.0.00.01.0006') {
                $sql_81032="
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81032') ";
            }else{
                $sql_81032="SELECT '81032' kd_rek, ISNULL(SUM(debet-kredit),0) nilai 
                FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                WHERE b.kd_unit='$kd_skpd' AND YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,6) IN ('111201','111202','111203') ";
            }


            if ($kd_skpd=='1.04.02.01') {
                $sql_81033="
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81033') ";
            }else{
                $sql_81033=" SELECT '81033' kd_rek, ISNULL(sal_awal-kurang+tambah+tahun_n+koreksi,0) nilai
                FROM (
                SELECT SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, 
                SUM(tahun_n) tahun_n, 
                sum(isnull(korplus,0)-isnull(kormin,0)) koreksi
                FROM lamp_aset 
                where left(kd_rek6,6)='111203' and kd_skpd='$kd_skpd' and tahun<='$thn_ang' 
                group by left(kd_rek6,6)) x";
            }


            /*if($kd_skpd=='1.02.01.01'){
                $sql_810316 = "";
            }else{
                $sql_810316 = " ";
            }*/


            if($kd_skpd=='3.29.0.00.0.00.01.0000' 
                || $kd_skpd=='1.03.2.10.0.00.01.0000' 
                || $kd_skpd=='2.07.3.32.0.00.01.0004'
                || $kd_skpd=='2.07.3.32.0.00.01.0000'
                || $kd_skpd=='1.02.0.00.0.00.01.0000'
                || $kd_skpd=='1.02.0.00.0.00.02.0000'
                || $kd_skpd=='1.02.0.00.0.00.03.0000'
                || $kd_skpd=='3.27.0.00.0.00.03.0003'
                || $kd_skpd=='2.09.3.27.0.00.01.0000'
                || $kd_skpd=='3.27.0.00.0.00.01.0004'
                || $kd_skpd=='2.09.3.27.0.00.01.0002'
                || $kd_skpd=='1.06.0.00.0.00.01.0001'
                || $kd_skpd=='1.06.0.00.0.00.01.0002'
                || $kd_skpd=='3.31.3.30.0.00.01.0001'
                || $kd_skpd=='3.27.0.00.0.00.03.0001'){
                $sql_81031 = "
                SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                on a.kd_rek=b.kd_rek where a.kd_rek = ('81031')";
            }else{
                $sql_81031 = "SELECT '81031' kd_rek, ISNULL(SUM(debet-kredit),0)*-1 nilai 
                FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                WHERE b.kd_unit='$kd_skpd' AND YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,6) IN ('111201','111202','111203') ";
            }


            if($kd_skpd=='1.04.2.10.0.00.01.0000' || $kd_skpd=='3.29.3.30.3.31.01.0000' || $kd_skpd=='3.27.0.00.0.00.03.0001' ){
                $sql_810317 = "
                SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('810317')";
            }else{
                // $sql_810317 = "SELECT '810317' kd_rek, ISNULL(SUM(debet-kredit),0) nilai 
                // FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                // WHERE b.kd_unit='$kd_skpd' AND YEAR(a.tgl_voucher)='$thn_ang' AND LEFT(b.kd_rek6,12) IN ('810201010042')";
                $sql_810317 = "SELECT '810317' kd_rek, ISNULL(SUM(debet),0) nilai 
                FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                WHERE b.kd_unit='$kd_skpd' AND YEAR(a.tgl_voucher)='$thn_ang' AND tgl_real='20'";
            }

            $det_810201 = DB::select("SELECT a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a 
                            LEFT JOIN
                            ($sql_81031
                            UNION ALL
                            $sql_81032
                            UNION ALL
                            $sql_81033
                            UNION ALL
                            SELECT '81034' kd_rek, ISNULL(sal_awal,0)*-1 nilai
                            FROM (
                            SELECT SUM(sal_awal) sal_awal, SUM(kurang) kurang, SUM(tambah) tambah, 
                            SUM(tahun_n) tahun_n, 
                            sum(isnull(korplus,0)-isnull(kormin,0)) koreksi
                            FROM lamp_aset 
                            where kd_rek6='1170302' and kd_skpd='$kd_skpd' and tahun<='$thn_ang_1' 
                            group by left(kd_rek6,5)) x
                            UNION ALL
                            select a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81035')
                            UNION ALL
                            select a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81036')
                            union all
                            $blud_81037
                            UNION ALL
                            $sql_81038
                            union all
                            SELECT '81039' kd_rek, ISNULL(SUM(0),0) nilai 
                            FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                            WHERE (b.kd_unit='$kd_skpd' and b.kd_unit='$kd_skpd') AND YEAR(a.tgl_voucher)='$thn_ang' AND LEFT(b.kd_rek6,3) IN ('117')
                            UNION ALL
                            SELECT '810310' kd_rek, ISNULL(SUM(0),0) nilai 
                            FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                            WHERE (b.kd_unit='$kd_skpd' and b.kd_unit='$kd_skpd') AND YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,3) IN ('117')
                            UNION ALL
                            select a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('810311')
                            UNION ALL
                            select a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('810312')
                            UNION ALL
                            select a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('810315')
                            UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('810316')
                            union all
                            $sql_810317
                            UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('810318')
                            UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('810319')
                            UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,7) in ('8103020')
                            UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,7) in ('8103030')
                            UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,7) in ('8103040')
                            UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,7) in ('8103050')
                            UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,7) in ('8103060')
                            ) b
                            on a.kd_rek=b.kd_rek
                            where LEFT(a.kd_rek,4)='8103'
                            order by cast(a.kd_rek as int)");
        //

        // 810202
            if ($kd_skpd=='1.04.2.10.0.00.01.0000'||$kd_skpd=='3.27.0.00.0.00.03.0000'||$kd_skpd=='1.02.0.00.0.00.02.0000'||$kd_skpd=='3.29.3.30.3.31.01.0000'||$kd_skpd=='1.03.1.04.0.00.01.0000'||$kd_skpd=='4.02.03.06'||$kd_skpd=='4.02.03.07'||$kd_skpd=='4.02.03.08' || $kd_skpd=='4.02.02.01' || $kd_skpd=='2.05.01.01' ||$kd_skpd=='1.04.02.01' ) {
                $sql_81025="
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81025') ";
            }else{
                $sql_81025="SELECT '81025' kd_rek, ISNULL(SUM(kredit-debet),0)*-1 nilai 
                            from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                            WHERE YEAR(a.tgl_voucher)<='$thn_ang_1' AND b.kd_rek6 IN ($kd_rek22) AND b.kd_unit='$kd_skpd' ";
            }
            if ($kd_skpd=='3.29.3.30.3.31.01.0001'||$kd_skpd=='3.31.3.30.0.00.01.0002'||$kd_skpd=='3.27.0.00.0.00.04.0002'||$kd_skpd=='2.09.3.27.0.00.01.0001'||$kd_skpd=='3.27.0.00.0.00.02.0001'||$kd_skpd=='3.27.0.00.0.00.03.0002'||$kd_skpd=='3.29.3.30.3.31.01.0000'||$kd_skpd=='3.27.0.00.0.00.04.0001' ||$kd_skpd=='3.27.0.00.0.00.03.0003'||$kd_skpd=='3.27.0.00.0.00.03.0001' ||$kd_skpd=='3.27.0.00.0.00.01.0000' ||$kd_skpd=='3.27.0.00.0.00.01.0002' ) {
                
                $sql_81023="
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81023') ";                         
            }else{
                $sql_81023="SELECT '81023' kd_rek, ISNULL(SUM(debet-kredit),0) nilai 
                            from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                            WHERE YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,4)='1111' AND b.kd_unit='$kd_skpd'";
            }


            if ($kd_skpd=='3.31.3.30.0.00.01.0001' || $kd_skpd=='3.27.0.00.0.00.04.0001' || $kd_skpd=='3.31.3.30.0.00.01.0000'|| $kd_skpd=='3.27.0.00.0.00.03.0001' ) {
                $sql_81024="
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81024') ";
            }else{
                $sql_81024="SELECT '81024' kd_rek, ISNULL(SUM(debet-kredit),0)*-1 nilai 
                            from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                            WHERE YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,4)='1111' AND b.kd_unit='$kd_skpd'";
            }

            if($kd_skpd=='1.02.0.00.0.00.02.0000'||$kd_skpd=='1.02.0.00.0.00.03.0000'){
                $sql_810291="   UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('810291') ";

            }else{
                $sql_810291="   ";
            }

            if ($kd_skpd=='1.02.0.00.0.00.02.0000'||$kd_skpd=='3.31.3.30.0.00.01.0001'  || $kd_skpd=='3.31.3.30.0.00.01.0000' ) {
                $sql_81026="SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81026')";
            }else{
                $sql_81026="SELECT '81026' kd_rek, ISNULL(SUM(kredit-debet),0) nilai 
                            from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                            WHERE YEAR(a.tgl_voucher)<='$thn_ang' AND b.kd_rek6 IN ($kd_rek22) AND b.kd_unit='$kd_skpd'";
            }

            if ($kd_skpd=='1.01.2.22.0.00.01.0000') {
                $sql_810293=" UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where a.kd_rek = '810293'";
                $sql_810294="UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where a.kd_rek = '810294'";
                
            }else{
                $sql_810293=" UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where a.kd_rek = '810293'";
                $sql_810294="UNION ALL
                            SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                             select kd_rek, nm_rek, nilai 
                             from nilai_beban_calk 
                             where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where a.kd_rek = '810294'";
            }

            $det_810202 = DB::select("SELECT a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a 
                                        LEFT JOIN
                                        (
                                        $sql_81023
                                        UNION ALL
                                        $sql_81024
                                        UNION ALL
                                        $sql_81025
                                        UNION ALL
                                        $sql_81026
                                        UNION ALL
                                        select a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                                        LEFT JOIN
                                        (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                                        on a.kd_rek=b.kd_rek
                                        where LEFT(a.kd_rek,5) in ('81027')
                                        UNION ALL
                                        select a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                                        LEFT JOIN
                                        (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                                        on a.kd_rek=b.kd_rek
                                        where LEFT(a.kd_rek,5) in ('81028')
                                        UNION ALL
                                        SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                                         select kd_rek, nm_rek, nilai 
                                         from nilai_beban_calk 
                                         where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('81029')
                                        $sql_810291
                                        union ALL
                                        SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                                         select kd_rek, nm_rek, nilai 
                                         from nilai_beban_calk 
                                         where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('810292')
                                        UNION ALL
                                        SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                                        LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                                        on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81022')
                                        $sql_810293
                                        $sql_810294
                                        ) b
                                        on a.kd_rek=b.kd_rek
                                        where LEFT(a.kd_rek,4)='8102'
                                        order by kd_rek");
        //

        //810203
            if ($kd_skpd=='1.02.0.00.0.00.02.0000' || $kd_skpd=='2.15.0.00.0.00.01.0000') {
                                    
                $sql_81041="SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81041')";


                $sql_81042="SELECT a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81042')";

            }else{
                        

                $sql_81041="SELECT '81041' kd_rek, ISNULL(SUM(debet-kredit),0)*-1 nilai 
                FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                WHERE b.kd_unit='$kd_skpd' AND YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,8) IN ('21060203')";


                $sql_81042="SELECT '81042' kd_rek, ISNULL(SUM(debet-kredit),0)*-1 nilai 
                FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                WHERE b.kd_unit='$kd_skpd' AND YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,8) IN ('21060203')";
            }

            $det_810203 = DB::select("SELECT a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a 
                            LEFT JOIN
                            (
                            $sql_81041
                            UNION ALL
                            $sql_81042
                            union all
                            select a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81043')
                            UNION ALL
                            select a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81044')
                            UNION ALL
                            select a.kd_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81045')
                            ) b
                            on a.kd_rek=b.kd_rek
                            where LEFT(a.kd_rek,4)='8104'
                            order by a.kd_rek");
        //
        // 810204
            $det_810204 = DB::select("SELECT a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a 
                            LEFT JOIN
                            (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek
                            where LEFT(a.kd_rek,4)='8105'
                            order by kd_rek");

        //
        // 810299
            $det_810299 = DB::select("SELECT a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a 
                            LEFT JOIN
                            (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                            on a.kd_rek=b.kd_rek
                            where LEFT(a.kd_rek,4)='8109'
                            order by kd_rek");

        //
        
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
        'peraturan'     => $peraturan,
        'permendagri'   => $permendagri,
        'kode_8'        => $kode_8,
        'kode_81'       => $kode_81,
        'kode_8102'     => $kode_8102,
        'det_810201'     => $det_810201,
        'det_810202'     => $det_810202,
        'det_810203'     => $det_810203,
        'det_810204'     => $det_810204,
        'det_810299'     => $det_810299,
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
    
        $view =  view('akuntansi.cetakan.calk.bab3.lo_beban.bab3_lo_index')->with($data);
        
        
        
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

    public function load_calkbab3_lo_beban(Request $request)
    {
        $kd_skpd = $request->kd_skpd;  
        $bulan   = $request->bulan;  
        $kd_rek   = $request->kd_rek;
        $thn_ang = tahun_anggaran();
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran,(select nama from tb_status_anggaran where a.jns_ang=kode)nama from trhrka a where $skpd_clause and status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
        if ($kd_rek=="8101") {
            
            $data = DB::select("SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek,uraian nm_rek,sum(nilai)nilai
                        from(
                            select kd_rek1 kd_rek ,0 nilai
                            from calk_map_bab3_lo_beban a 
                            where edit='1' and left(kd_rek,4)='8101' and kd_rek1!='81012'
                            group by kd_rek1,uraian 
                            union all
                            select kd_rek, nilai
                            from nilai_beban_calk 
                            where left(kd_rek,4)='8101' and kd_skpd='$kd_skpd' 
                            union all
                            select kd_rek1 kd_rek ,0 nilai
                            from calk_map_bab3_lo_beban a 
                            where edit='1' and  kd_rek1='81012'and '3.27.0.00.0.00.03.0003'='$kd_skpd'    
                            group by kd_rek1,uraian
                        )a inner join calk_map_bab3_lo_beban b on a.kd_rek=b.kd_rek1
                        group by a.kd_rek,uraian");
        }elseif($kd_rek=="810201"){
            if ($kd_skpd=='1.04.2.10.0.00.01.0000'||$kd_skpd=='4.02.03.02'||$kd_skpd=='4.02.03.03'||$kd_skpd=='4.02.03.04'||$kd_skpd=='4.02.03.05'||$kd_skpd=='4.02.03.06'||$kd_skpd=='4.02.03.07'||$kd_skpd=='4.02.03.08'||$kd_skpd=='4.02.02.01'||$kd_skpd=='2.05.01.01'  ) {
                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where (LEFT(a.kd_rek,5) in ('81032','81035','81036','81038') or LEFT(a.kd_rek,6) in ('91311','91312','810315','810316','810317','810318','810319','810302','810303','810304','810305','810306'))
                    order by kd_rek";
            }else if($kd_skpd=='1.03.0.00.0.00.01.0000'){
                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where (LEFT(a.kd_rek,5) in ('81032','81035','81036','81038') or LEFT(a.kd_rek,6) in ('810316','810315','91315','810318','810319','810302','810303','810304','810305','810306'))";
            }else if($kd_skpd=='1.04.02.01'){
                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where (LEFT(a.kd_rek,4) in ('9132','9133','9135','9136') or LEFT(a.kd_rek,5) in ('91311','91312','91315'))";
            }else if($kd_skpd=='1.02.01.01'){
                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where (LEFT(a.kd_rek,4) in ('9135','9136') or LEFT(a.kd_rek,5) in ('91311','91312','91315','91316'))";
            }else if($kd_skpd=='3.29.0.00.0.00.01.0000' || $kd_skpd=='1.03.2.10.0.00.01.0000'
                    || $kd_skpd=='2.07.3.32.0.00.01.0004' || $kd_skpd=='2.07.3.32.0.00.01.0000'|| $kd_skpd=='1.02.0.00.0.00.01.0000'|| $kd_skpd=='3.27.0.00.0.00.03.0001' ){ 
                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where (LEFT(a.kd_rek,5) in ('81032','81031','81035','81036') or LEFT(a.kd_rek,6) in ('91311','91312','810315','810316','810317','810318','810319','810302','810303','810304','810305','810306')) ";
            }elseif ($kd_skpd=='3.29.3.30.3.31.01.0000'||$kd_skpd=='2.09.0.00.0.00.01.0000'||$kd_skpd=='3.27.0.00.0.00.03.0003' ||$kd_skpd=='3.27.0.00.0.00.03.0001') {


                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where (LEFT(a.kd_rek,5) in ('81032','81035','81036') or LEFT(a.kd_rek,6) in ('91311','91312','810315','810316','810317','810318','810319','810302','810303','810304','810305','810306'))";   


            }elseif ($kd_skpd=='1.02.0.00.0.00.02.0000'||$kd_skpd=='1.02.0.00.0.00.03.0000'||$kd_skpd=='2.09.3.27.0.00.01.0000' || $kd_skpd=='1.03.1.04.0.00.01.0000' ) {

                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where (LEFT(a.kd_rek,5) in ('81031','81032','81035','81036','81038') or LEFT(a.kd_rek,6) in ('91311','91312','810315','810316','810318','810319','810302','810303','810304','810305','810306'))";

            }elseif ($kd_skpd=='3.27.0.00.0.00.03.0003' || $kd_skpd=='3.27.0.00.0.00.01.0004' || $kd_skpd=='2.09.3.27.0.00.01.0002'
                     || $kd_skpd=='1.06.0.00.0.00.01.0001' || $kd_skpd=='1.06.0.00.0.00.01.0002' || $kd_skpd=='3.31.3.30.0.00.01.0001' 
                    || $kd_skpd=='3.27.0.00.0.00.04.0000' ) {

                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where (LEFT(a.kd_rek,5) in ('81031','81032','81035','81036') or LEFT(a.kd_rek,6) in ('91311','91312','810315','810316','810318','810319','810302','810303','810304','810305','810306'))";
            }else if($kd_skpd=='1.02.0.00.0.00.01.0006'){
                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where (LEFT(a.kd_rek,5) in ('81032','81035','81036') or LEFT(a.kd_rek,6) in ('91311','91312','810315','810316','810318','810319','810302','810303','810304','810305','810306'))";
            }else{
                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where (LEFT(a.kd_rek,5) in ('81035','81036') or LEFT(a.kd_rek,6) in ('91311','91312','810315','810316','810318','810319','810302','810303','810304','810305','810306'))";    
            }

            $data = DB::select("$sql");
        }elseif($kd_rek=="810202"){
            if ($kd_skpd=='3.29.3.30.3.31.01.0001'||$kd_skpd=='3.31.3.30.0.00.01.0002'||$kd_skpd=='3.27.0.00.0.00.04.0002'||$kd_skpd=='2.09.3.27.0.00.01.0001'||$kd_skpd=='3.27.0.00.0.00.02.0001'||$kd_skpd=='4.02.03.06'||$kd_skpd=='4.02.03.07'||$kd_skpd=='4.02.03.08' || $kd_skpd=='1.04.02.01' ) {

                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where LEFT(a.kd_rek,5) in ('81022','81023','81027','81028','9125','9127','9128')
                    or left(a.kd_rek,6)in('810292','810293','810294')";

            }else if($kd_skpd=='3.31.3.30.0.00.01.0001' || $kd_skpd=='3.27.0.00.0.00.04.0001' ){

                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where LEFT(a.kd_rek,5) in ('81022','81023','81024','81027','81026','81028','9125','9127','9128')
                    or left(a.kd_rek,6) in ('810292','810293','810294')";

            }else if($kd_skpd=='1.04.2.10.0.00.01.0000'||$kd_skpd=='3.27.0.00.0.00.03.0000'||$kd_skpd=='3.29.3.30.3.31.01.0000'||$kd_skpd=='1.03.1.04.0.00.01.0000' ||$kd_skpd=='3.27.0.00.0.00.03.0003' ||$kd_skpd=='3.27.0.00.0.00.01.0000'){

                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where LEFT(a.kd_rek,5) in ('81022','81023','81025','81027','81028') or 
                    LEFT(a.kd_rek,6) in ('810292','810293','810294')";

            }else if($kd_skpd=='1.02.0.00.0.00.02.0000'||$kd_skpd=='1.02.0.00.0.00.03.0000'){

                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where LEFT(a.kd_rek,5) in ('81022','81025','81026','81027','81028','81029') or 
                    LEFT(a.kd_rek,6) in ('810292','810291','810293','810294')";

            }elseif ($kd_skpd=='3.27.0.00.0.00.03.0002'||$kd_skpd=='3.31.3.30.0.00.01.0000'||$kd_skpd=='3.27.0.00.0.00.03.0001'||$kd_skpd=='3.27.0.00.0.00.01.0002') {
                    $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where LEFT(a.kd_rek,5) in ('81022','81023','81024','81026','81027','81028') or 
                    LEFT(a.kd_rek,6) in ('810292','810293','810294')";

            }elseif ($kd_skpd=='1.01.2.22.0.00.01.0000') {
                    $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where a.kd_rek in ('81022','81027','81028','810292','810293','810294')";

            }else{
                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where LEFT(a.kd_rek,5) in ('81022','81027','81028') or 
                    LEFT(a.kd_rek,6) in ('810292','810293','810294')";    
            }
            $data = DB::select("$sql");
        }elseif($kd_rek=="810203"){
            if ($kd_skpd=='1.02.0.00.0.00.02.0000' || $kd_skpd=='2.15.0.00.0.00.01.0000') {
                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where LEFT(a.kd_rek,5) in ('81041','81042','81043','81044','81045')";
            }else{
                $sql = "SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                    LEFT JOIN
                    (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                    on a.kd_rek=b.kd_rek
                    where LEFT(a.kd_rek,5) in ('81043','81044','81045')";
            }
            $data = DB::select("$sql");
        }elseif($kd_rek=="810204"){
            $data = DB::select("SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                LEFT JOIN
                (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                on a.kd_rek=b.kd_rek
                where LEFT(a.kd_rek,4) in ('8105')
                order by kd_rek");
        }elseif($kd_rek=="810299"){
            $data = DB::select("SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd,a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a  
                LEFT JOIN
                (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd') b
                on a.kd_rek=b.kd_rek
                where LEFT(a.kd_rek,4) in ('8109')
                order by kd_rek");
        }else{

        }
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->kd_rek . '\',\'' . $row->nm_rek . '\',\'' . $row->nilai . '\');" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpan_calkbab3_lo_beban(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $nm_rek             = $request->nm_rek;
        $nilai               = $request->nilai;
        
        $hasil=collect(DB::select("SELECT  count(*) as jumlah FROM $tabel where kd_skpd='$kd_skpd' and kd_rek='$kd_rek'"))->first();

        $jumlah=$hasil->jumlah; 
        
        if($jumlah>0){
            $asg     = DB::update("UPDATE $tabel SET nilai='$nilai', nm_rek='$nm_rek' where kd_rek='$kd_rek' and kd_skpd='$kd_skpd'");
        } else{
            $asg = DB::insert("INSERT into $tabel (kd_skpd,kd_rek,nm_rek,nilai,nilai2) values ('$kd_skpd','$kd_rek', '$nm_rek',$nilai, 0)");
        }
        
        if ( $asg > 0 ){
           echo '1';
        } else {
           echo '0';
        }
    
    }


}
