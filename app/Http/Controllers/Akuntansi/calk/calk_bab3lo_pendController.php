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


class calk_bab3lo_pendController extends Controller
{

    public function calkbab3_lo_pend(Request $request)
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

        return view('akuntansi.cetakan.calk.bab3.lra_pend.edit_bab3_lra_pend')->with($data);
    }

    function cetak_calk13(Request $request)
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
        

        $prv = collect(DB::select("SELECT ISNULL(sum(b.kredit-b.debet),0) realisasi from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
            where LEFT(b.kd_rek6,1) IN ('7') AND $skpd_clause AND YEAR(tgl_voucher)='$thn_ang'"))->first();
        $cek = $prv->realisasi;
        if($cek==0){
            $kode_7 = DB::select("SELECT kd_skpd,kd_rek,nm_rek,realisasi,real_tlalu,(real_tlalu-realisasi)kenaikan,
                case when real_tlalu<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra 
                from(SELECT b.kd_unit as kd_skpd, LEFT(b.kd_rek6,1) as kd_rek,(select nm_rek1 from ms_rek1 where kd_rek1=LEFT(b.kd_rek6,1)) as nm_rek, 0 as realisasi,  sum(b.kredit-b.debet) real_tlalu, 0 as real_lra 
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(b.kd_rek6,1) IN ('7') AND $skpd_clause AND YEAR(tgl_voucher)='$thn_ang_1'
                group by b.kd_unit, LEFT(b.kd_rek6,1)
                UNION ALL
                select b.kd_unit as kd_skpd, LEFT(b.kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(b.kd_rek6,2)) as nm_rek, 0 as realisasi,  sum(b.kredit-b.debet) real_tlalu, 0 as real_lra 
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(b.kd_rek6,1) IN ('7') AND $skpd_clause AND YEAR(tgl_voucher)='$thn_ang_1'
                group by b.kd_unit, LEFT(b.kd_rek6,2))a
                order by kd_rek6");
            $kode_71 = DB::select("SELECT kd_skpd,kd_rek,nm_rek,realisasi,real_tlalu,(real_tlalu-realisasi)kenaikan,
                case when real_tlalu<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
                from(SELECT b.kd_unit as kd_skpd, LEFT(b.kd_rek6,2) as kd_rek,(select nm_rek1 from ms_rek1 where kd_rek1=LEFT(b.kd_rek6,1)) as nm_rek, 0 as realisasi,  sum(b.kredit-b.debet) real_tlalu, 0 as real_lra 
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(b.kd_rek6,2) IN ('71') AND $skpd_clause AND YEAR(tgl_voucher)='$thn_ang_1'
                group by b.kd_unit, LEFT(b.kd_rek6,1)
                UNION ALL
                select b.kd_unit as kd_skpd, LEFT(b.kd_rek6,4) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(b.kd_rek6,2)) as nm_rek, 0 as realisasi,  sum(b.kredit-b.debet) real_tlalu, 0 as real_lra 
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(b.kd_rek6,2) IN ('71') AND $skpd_clause AND YEAR(tgl_voucher)='$thn_ang_1'
                group by b.kd_unit, LEFT(b.kd_rek6,2))a
                order by kd_rek");
            $kode_72 = DB::select("SELECT kd_skpd,kd_rek,nm_rek,realisasi,real_tlalu,(real_tlalu-realisasi)kenaikan,
                case when real_tlalu<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
                from(SELECT b.kd_unit as kd_skpd, LEFT(b.kd_rek6,2) as kd_rek,(select nm_rek1 from ms_rek1 where kd_rek1=LEFT(b.kd_rek6,1)) as nm_rek, 0 as realisasi,  sum(b.kredit-b.debet) real_tlalu, 0 as real_lra 
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(b.kd_rek6,2) IN ('72') AND $skpd_clause AND YEAR(tgl_voucher)='$thn_ang_1'
                group by b.kd_unit, LEFT(b.kd_rek6,1)
                UNION ALL
                select b.kd_unit as kd_skpd, LEFT(b.kd_rek6,4) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(b.kd_rek6,2)) as nm_rek, 0 as realisasi,  sum(b.kredit-b.debet) real_tlalu, 0 as real_lra 
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(b.kd_rek6,2) IN ('72') AND $skpd_clause AND YEAR(tgl_voucher)='$thn_ang_1'
                group by b.kd_unit, LEFT(b.kd_rek6,2))a
                order by kd_rek");
            $kode_73 = DB::select("SELECT kd_skpd,kd_rek,nm_rek,realisasi,real_tlalu,(real_tlalu-realisasi)kenaikan,
                case when real_tlalu<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
                from(SELECT b.kd_unit as kd_skpd, LEFT(b.kd_rek6,2) as kd_rek,(select nm_rek1 from ms_rek1 where kd_rek1=LEFT(b.kd_rek6,1)) as nm_rek, 0 as realisasi,  sum(b.kredit-b.debet) real_tlalu, 0 as real_lra 
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(b.kd_rek6,2) IN ('73') AND $skpd_clause AND YEAR(tgl_voucher)='$thn_ang_1'
                group by b.kd_unit, LEFT(b.kd_rek6,1)
                UNION ALL
                select b.kd_unit as kd_skpd, LEFT(b.kd_rek6,4) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(b.kd_rek6,2)) as nm_rek, 0 as realisasi,  sum(b.kredit-b.debet) real_tlalu, 0 as real_lra 
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(b.kd_rek6,2) IN ('73') AND $skpd_clause AND YEAR(tgl_voucher)='$thn_ang_1'
                group by b.kd_unit, LEFT(b.kd_rek6,2))a
                order by kd_rek");


        }else{
            $kode_7 = DB::select("SELECT kd_skpd,kd_rek,nm_rek,realisasi,real_tlalu,(real_tlalu-realisasi)kenaikan,
                case when real_tlalu<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra 
                from(
                                select x.kd_skpd,'7'  kd_rek,'PENDAPATAN - LO' nm_rek,sum(x.real_ini) realisasi,sum(x.real_lalu) real_tlalu,sum(x.real_lra) real_lra  
                                from(select a.kd_skpd,
                                     case when left(b.kd_rek6,1)='7' then left(b.kd_rek6,2) end kod,
                                     case when left(b.kd_rek6,1)='7' then replace(left(b.kd_rek6,2),'7','4') end kd_lra,
                                     sum(case when left(b.kd_rek6,1)='7' and year(a.tgl_voucher)='$thn_ang' then b.kredit-b.debet else 0 end) real_ini,
                                     sum(case when left(b.kd_rek6,1)='7' and year(a.tgl_voucher)='$thn_ang_1' then b.kredit-b.debet else 0 end) real_lalu,
                                     0 real_lra
                                     from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                                     where left(b.kd_rek6,1) in ('7') 
                                     group by a.kd_skpd,left(b.kd_rek6,2),left(b.kd_rek6,1)
                                     union all
                                     select a.kd_skpd,case when left(b.kd_rek6,1)='4' then replace(left(b.kd_rek6,2),'4','7') end kod,left(b.kd_rek6,2) kd_lra,0 real_ini,0 real_lalu,
                                     sum(case when left(b.kd_rek6,1)='4' and year(a.tgl_voucher)='$thn_ang' then b.kredit-b.debet else 0 end) real_lra
                                     from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                                     where left(b.kd_rek6,1) in ('4')
                                     group by a.kd_skpd,left(b.kd_rek6,2),left(b.kd_rek6,1)
                                    ) x
                                group by x.kd_skpd
                                union all
                                select x.kd_skpd,x.kod kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=x.kod group by nm_rek2) nm_rek,sum(x.real_ini) realisasi,sum(x.real_lalu) real_tlalu,sum(x.real_lra) real_lra  
                                from(select a.kd_skpd,
                                     case when left(b.kd_rek6,1)='7' then left(b.kd_rek6,2) end kod,
                                     case when left(b.kd_rek6,1)='7' then replace(left(b.kd_rek6,2),'7','4') end kd_lra,
                                     sum(case when left(b.kd_rek6,1)='7' and year(a.tgl_voucher)='$thn_ang' then b.kredit-b.debet else 0 end) real_ini,
                                     sum(case when left(b.kd_rek6,1)='7' and year(a.tgl_voucher)='$thn_ang_1' then b.kredit-b.debet else 0 end) real_lalu,0 real_lra
                                     from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                                     where left(b.kd_rek6,1) in ('7')
                                     group by a.kd_skpd,left(b.kd_rek6,2),left(b.kd_rek6,1)
                                     union all
                                     select a.kd_skpd,case when left(b.kd_rek6,1)='4' then replace(left(b.kd_rek6,2),'4','7') end kod,left(b.kd_rek6,2) kd_lra,0 real_ini,0 real_lalu,
                                     sum(case when left(b.kd_rek6,1)='4' and year(a.tgl_voucher)='$thn_ang' then b.kredit-b.debet else 0 end) real_lra
                                     from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                                     where left(b.kd_rek6,1) in ('4') 
                                     group by a.kd_skpd,left(b.kd_rek6,2),left(b.kd_rek6,1)
                                    ) x
                                group by x.kd_skpd,x.kod
                                ) q
                            where q.realisasi<>'0' and q.real_tlalu<>'0' and q.real_lra<>'0' and $skpd_clause
                            order by q.kd_rek");
            $kode_71 = DB::select("SELECT kd_skpd,kd_rek,nm_rek,realisasi,real_tlalu,(real_tlalu-realisasi)kenaikan,
                case when real_tlalu<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
                from(SELECT kd_skpd,kd_rek,nm_rek, sum(realisasi)realisasi, sum(real_tlalu) real_tlalu,sum(real_lra)real_lra
                from(SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek6,2)) as nm_rek,  sum(kredit-debet) realisasi, 0 as real_tlalu , 0 real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,2) IN ('71')  AND YEAR(tgl_voucher)='$thn_ang'
                group by kd_skpd, LEFT(kd_rek6,2)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek6,2)) as nm_rek,  0 realisasi, sum(kredit-debet) as real_tlalu ,0 real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,2) IN ('71')  AND YEAR(tgl_voucher)='$thn_ang_1'
                group by kd_skpd, LEFT(kd_rek6,2)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(map_lo,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(map_lo,2)) as nm_rek,  0 realisasi, 0 as real_tlalu ,  sum(kredit-debet) real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                where LEFT(map_lo,2) IN ('71')  AND YEAR(tgl_voucher)='$thn_ang'
                group by kd_skpd, LEFT(map_lo,2)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(kd_rek6,4)) as nm_rek,  sum(kredit-debet) realisasi, 0 as real_tlalu , 0 real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,2) IN ('71')  AND YEAR(tgl_voucher)='$thn_ang'
                group by kd_skpd, LEFT(kd_rek6,4)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(kd_rek6,4)) as nm_rek,   0 realisasi, sum(kredit-debet) as real_tlalu , 0 real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,2) IN ('71')  AND YEAR(tgl_voucher)='$thn_ang_1'
                group by kd_skpd, LEFT(kd_rek6,4)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(map_lo,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(map_lo,4)) as nm_rek,  0 realisasi, 0 as real_tlalu ,  sum(kredit-debet) real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                where LEFT(map_lo,2) IN ('71') AND YEAR(tgl_voucher)='$thn_ang'
                group by kd_skpd, LEFT(map_lo,4)

                )a
                where $skpd_clause
                group by kd_skpd,kd_rek,nm_rek)a
                order by kd_skpd,kd_rek,nm_rek");

            $kode_72 = DB::select("SELECT kd_skpd,kd_rek,nm_rek,realisasi,real_tlalu,(real_tlalu-realisasi)kenaikan,
                case when real_tlalu<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
                from(SELECT kd_skpd,kd_rek,nm_rek, sum(realisasi)realisasi, sum(real_tlalu) real_tlalu,sum(real_lra)real_lra
                from(SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek6,2)) as nm_rek,  sum(kredit-debet) realisasi, 0 as real_tlalu , 0 real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,2) IN ('72')  AND YEAR(tgl_voucher)='$thn_ang'
                group by kd_skpd, LEFT(kd_rek6,2)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek6,2)) as nm_rek,  0 realisasi, sum(kredit-debet) as real_tlalu ,0 real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,2) IN ('72')  AND YEAR(tgl_voucher)='$thn_ang_1'
                group by kd_skpd, LEFT(kd_rek6,2)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(map_lo,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(map_lo,2)) as nm_rek,  0 realisasi, 0 as real_tlalu ,  sum(kredit-debet) real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                where LEFT(map_lo,2) IN ('72')  AND YEAR(tgl_voucher)='$thn_ang'
                group by kd_skpd, LEFT(map_lo,2)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(kd_rek6,4)) as nm_rek,  sum(kredit-debet) realisasi, 0 as real_tlalu , 0 real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,2) IN ('72')  AND YEAR(tgl_voucher)='$thn_ang'
                group by kd_skpd, LEFT(kd_rek6,4)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(kd_rek6,4)) as nm_rek,   0 realisasi, sum(kredit-debet) as real_tlalu , 0 real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,2) IN ('72')  AND YEAR(tgl_voucher)='$thn_ang_1'
                group by kd_skpd, LEFT(kd_rek6,4)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(map_lo,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(map_lo,4)) as nm_rek,  0 realisasi, 0 as real_tlalu ,  sum(kredit-debet) real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                where LEFT(map_lo,2) IN ('72') AND YEAR(tgl_voucher)='$thn_ang'
                group by kd_skpd, LEFT(map_lo,4)

                )a
                where $skpd_clause
                group by kd_skpd,kd_rek,nm_rek)a
                order by kd_skpd,kd_rek,nm_rek");

            $kode_73 = DB::select("SELECT kd_skpd,kd_rek,nm_rek,realisasi,real_tlalu,(real_tlalu-realisasi)kenaikan,
                case when real_tlalu<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,(realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo
                from(SELECT kd_skpd,kd_rek,nm_rek, sum(realisasi)realisasi, sum(real_tlalu) real_tlalu,sum(real_lra)real_lra
                from(SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek6,2)) as nm_rek,  sum(kredit-debet) realisasi, 0 as real_tlalu , 0 real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,2) IN ('73')  AND YEAR(tgl_voucher)='$thn_ang'
                group by kd_skpd, LEFT(kd_rek6,2)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek6,2)) as nm_rek,  0 realisasi, sum(kredit-debet) as real_tlalu ,0 real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,2) IN ('73')  AND YEAR(tgl_voucher)='$thn_ang_1'
                group by kd_skpd, LEFT(kd_rek6,2)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(map_lo,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(map_lo,2)) as nm_rek,  0 realisasi, 0 as real_tlalu ,  sum(kredit-debet) real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                where LEFT(map_lo,2) IN ('73')  AND YEAR(tgl_voucher)='$thn_ang'
                group by kd_skpd, LEFT(map_lo,2)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(kd_rek6,4)) as nm_rek,  sum(kredit-debet) realisasi, 0 as real_tlalu , 0 real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,2) IN ('73')  AND YEAR(tgl_voucher)='$thn_ang'
                group by kd_skpd, LEFT(kd_rek6,4)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(kd_rek6,4)) as nm_rek,   0 realisasi, sum(kredit-debet) as real_tlalu , 0 real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,2) IN ('73')  AND YEAR(tgl_voucher)='$thn_ang_1'
                group by kd_skpd, LEFT(kd_rek6,4)
                union all
                SELECT kd_skpd as kd_skpd, LEFT(map_lo,4) as kd_rek,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(map_lo,4)) as nm_rek,  0 realisasi, 0 as real_tlalu ,  sum(kredit-debet) real_lra
                from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                where LEFT(map_lo,2) IN ('73') AND YEAR(tgl_voucher)='$thn_ang'
                group by kd_skpd, LEFT(map_lo,4)

                )a
                where $skpd_clause
                group by kd_skpd,kd_rek,nm_rek)a
                order by kd_skpd,kd_rek,nm_rek");



        }


        
        
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
        'kode_7'        => $kode_7,
        'kode_71'        => $kode_71,
        'kode_72'        => $kode_72,
        'kode_73'        => $kode_73,
        'cetak'         => $cetak,
        'spasi'         => $spasi,
        'jns_ang'         => $jns_ang,
        'nm_jns_ang'         => $nm_jns_ang,
        'trdju'         => $trdju,
        'trhju'         => $trhju,
        'thn_ang'       => $thn_ang ,
        'thn_ang_1'       => $thn_ang_1,
        'skpd_clause'       => $skpd_clause  
        ];
    
        $view =  view('akuntansi.cetakan.calk.bab3.lo_pend.bab3_lo_index')->with($data);
        
        
        
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

    public function load_calkbab3_lo_pend(Request $request)
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
                              where left(a.kd_rek6,1)=4 and YEAR(b.tgl_voucher)='$thn_ang' 
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

    public function simpan_calkbab3_lo_pend(Request $request){
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
