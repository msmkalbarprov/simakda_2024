<?php

namespace App\Http\Controllers\Akuntansi\calk_aset;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;
use PDF;
use Knp\Snappy\Pdf as SnappyPdf;

class cetakcalkasetController extends Controller
{

    public function cetak_lap_aset(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $jenis = $request->jenis;
        $rek3    = $request->rek3;
        $format = $request->format;
        $cetak   = $request->cetak;
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        // dd($kd_skpd);

        if($rek3==153){
            $notin='and left(b.kd_rek5,5)<>15306';
            $where=' where b.kd_rek4_64<>15306';
        }else{
            $notin='';
            $where='';
        }
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        if($format=="1"){
            if($jenis=="1"){
                
                if ($rek3==1503) {
                    $query = DB::select("SELECT kd_skpd, nm_skpd,
                    SUM(sal_awal) sal_awal ,SUM(tambah) tambah,
                    SUM(kurang) kurang,SUM(tahun_n) tahun_n,
                    SUM(thn_berjalan) thn_berjalan from  (
                    
                    SELECT kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, 
                     sum(debet-kredit) sal_awal , 0 tambah , 0 kurang, 0 tahun_n, 0 thn_berjalan
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,8)='15030101' and year(tgl_voucher)<=$thn_ang_1 
                    group by kd_skpd,nm_skpd
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, 
                     0 sal_awal , sum(nilai) tambah, 0 kurang, 0 tahun_n, 0 thn_berjalan
                    from isi_neraca_calk b where  left(kd_rek,4)='1532'
                    group by kd_skpd
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, 
                     0 sal_awal , 0 tambah, sum(nilai) kurang, 0 tahun_n, 0 thn_berjalan
                    from isi_neraca_calk b where left(kd_rek,4)='1533'
                    group by kd_skpd
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd,
                    0 sal_awal, 0 tambah, 0 kurang,
                    isnull(sum(debet-kredit),0) as tahun_n , 0 thn_berjalan
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5206' and year(tgl_voucher)=$thn_ang
                    group by kd_skpd
                    UNION ALL
                    SELECT z.kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=z.kd_skpd ) nm_skpd, 
                     0 sal_awal , 0 tambah , 0 kurang, 0 tahun_n, SUM(z.thn_berjalan) thn_berjalan
                    from (
                            select kd_skpd, sum(debet-kredit) thn_berjalan
                            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where left(kd_rek6,8)='15030101' and year(tgl_voucher)<=$thn_ang 
                            group by kd_skpd,nm_skpd
                        )z
                    group by kd_skpd
                    
                    ) a where (sal_awal<>0 OR tambah<>0 OR kurang<>0 OR tahun_n<>0 OR thn_berjalan<>0 )
                    and kd_skpd<>'' and nm_skpd<>''
                    group by kd_skpd ,nm_skpd
                    order by kd_skpd");

                }elseif($rek3==1504){
                    $query = DB::select("SELECT kd_skpd, nm_skpd,
                    SUM(sal_awal) sal_awal ,SUM(tambah) tambah,
                    SUM(kurang) kurang,SUM(tahun_n) tahun_n,
                    SUM(thn_berjalan) thn_berjalan from  (
                    
                    SELECT kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, 
                     sum(debet-kredit) sal_awal , 0 tambah , 0 kurang, 0 tahun_n, 0 thn_berjalan
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,4)='$rek3' and year(tgl_voucher)<=$thn_ang_1 
                    group by kd_skpd,nm_skpd
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, 
                     0 sal_awal , sum(nilai) tambah, 0 kurang, 0 tahun_n, 0 thn_berjalan
                    from isi_neraca_calk b where  left(kd_rek,4)='1542'
                    group by kd_skpd
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, 
                     0 sal_awal , 0 tambah, sum(nilai) kurang, 0 tahun_n, 0 thn_berjalan
                    from isi_neraca_calk b where left(kd_rek,4)='1543'
                    group by kd_skpd
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd,
                    0 sal_awal, 0 tambah, 0 kurang,
                    isnull(sum(0),0) as tahun_n , 0 thn_berjalan
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,5)='15421' and year(tgl_voucher)=$thn_ang
                    group by kd_skpd
                    UNION ALL
                    SELECT z.kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=z.kd_skpd ) nm_skpd, 
                     0 sal_awal , 0 tambah , 0 kurang, 0 tahun_n, SUM(z.thn_berjalan) thn_berjalan
                    from (
                            select kd_skpd, sum(debet-kredit) thn_berjalan
                            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where left(kd_rek6,4)='$rek3' and year(tgl_voucher)<=$thn_ang 
                            group by kd_skpd,nm_skpd
                        )z
                    group by kd_skpd
                    
                    ) a where (sal_awal<>0 OR tambah<>0 OR kurang<>0 OR tahun_n<>0 OR thn_berjalan<>0 )
                    and kd_skpd<>'' and nm_skpd<>''
                    group by kd_skpd ,nm_skpd
                    order by kd_skpd");
                
                }elseif($rek3==1112){
                    $query = DB::select("SELECT a.kd_skpd, a.nm_skpd, ISNULL(sal_awal,0) sal_awal, ISNULL(tambah,0) tambah, ISNULL(kurang,0) kurang,
                    ISNULL(tahun_n,0) tahun_n,ISNULL(koreksi,0) koreksi FROM ms_skpd a
                    LEFT JOIN (
                    SELECT a.kd_skpd, SUM(a.sal_awal) sal_awal, SUM(a.tambah) as tambah, SUM(a.kurang) as kurang, SUM(a.tahun_n) as tahun_n,sum(isnull(korplus,0)-isnull(kormin,0)) koreksi FROM lamp_aset a 
                    WHERE kd_rek3='$rek3'
                    GROUP BY a.kd_skpd) b ON a.kd_skpd=b.kd_skpd
                    ORDER BY a.kd_skpd");
                
                }else{
                    $query = DB::select("SELECT a.kd_skpd, a.nm_skpd, ISNULL(sal_awal,0) sal_awal, ISNULL(tambah,0) tambah, ISNULL(kurang,0) kurang,
                    ISNULL(tahun_n,0) tahun_n FROM ms_skpd a
                    LEFT JOIN (
                    SELECT a.kd_skpd, SUM(a.sal_awal+a.korplus-a.kormin) sal_awal, SUM(a.tambah) as tambah, SUM(a.kurang) as kurang, SUM(a.tahun_n) as tahun_n FROM lamp_aset a 
                    WHERE kd_rek3='$rek3'
                    GROUP BY a.kd_skpd) b ON a.kd_skpd=b.kd_skpd
                    ORDER BY a.kd_skpd");
                }
                $query_tot ="";
            }else{
                if ($rek3==1503) {
                    $query = DB::select("SELECT kd_skpd, nm_skpd, kd_rek3, nm_rek3, isnull(sal_awal,0) sal_awal, isnull(tambah,0) tambah, isnull(kurang,0) kurang, 
                        isnull(tahun_n,0) tahun_n 
                    FROM (
                    --rek3
                    select a.kd_skpd, a.nm_skpd, a.kd_rek3, a.nm_rek3, 
                    SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n 
                    from (
                        select kd_skpd, nm_skpd, a.kd_rek3, b.nm_rek3 
                        from (select kd_skpd, nm_skpd, '1503' kd_rek3 from ms_skpd) a left join ms_rek3 b on a.kd_rek3=b.kd_rek3 )
                     a 
                    left join 
                    (
                        SELECT kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, left(a.kd_rek6,4) kd_rek3,
                     sum(debet-kredit) sal_awal , 0 tambah , 0 kurang, 0 tahun_n, 0 thn_berjalan
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,6)='150301' and year(tgl_voucher)<=$thn_ang_1 
                    group by kd_skpd,nm_skpd,left(a.kd_rek6,4)
                
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, '1503' kd_rek3,
                     0 sal_awal , sum(nilai) tambah, 0 kurang, 0 tahun_n, 0 thn_berjalan
                    from isi_neraca_calk b where  left(kd_rek,4)='1532'
                    group by kd_skpd 
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, '1503' kd_rek3,
                     0 sal_awal , 0 tambah, sum(nilai) kurang, 0 tahun_n, 0 thn_berjalan
                    from isi_neraca_calk b where left(kd_rek,4)='1533'
                    group by kd_skpd
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, left(a.kd_rek6,4) kd_rek3,
                    0 sal_awal, 0 tambah, 0 kurang,
                    isnull(sum(0),0) as tahun_n , 0 thn_berjalan
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5206' and year(tgl_voucher)=$thn_ang
                    group by kd_skpd,left(a.kd_rek6,4)
                    
                    ) b on a.kd_skpd=b.kd_skpd
                    group by a.kd_skpd, a.nm_skpd, a.kd_rek3, a.nm_rek3
                    UNION ALL 
                    --rek5  
                    
                    select b.kd_skpd, b.nm_skpd, b.kd_rek6 kd_rek3, (select nm_rek3 from ms_rek3 where kd_rek3='1503' ) nm_rek3, 
                    SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n 
                    from 
                    (
                        SELECT kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, left(a.kd_rek6,6) kd_rek6,
                     sum(debet-kredit) sal_awal , 0 tambah , 0 kurang, 0 tahun_n, 0 thn_berjalan
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,6)='150301' and year(tgl_voucher)<=$thn_ang_1 
                    group by kd_skpd,nm_skpd,left(a.kd_rek6,6)
                
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, '150301' kd_rek6,
                     0 sal_awal , sum(nilai) tambah, 0 kurang, 0 tahun_n, 0 thn_berjalan
                    from isi_neraca_calk b where  left(kd_rek,4)='1532'
                    group by kd_skpd 
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, '150301' kd_rek6,
                     0 sal_awal , 0 tambah, sum(nilai) kurang, 0 tahun_n, 0 thn_berjalan
                    from isi_neraca_calk b where left(kd_rek,4)='1533'
                    group by kd_skpd
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, left(a.kd_rek6,6) kd_rek6,
                    0 sal_awal, 0 tambah, 0 kurang,
                    isnull(sum(0),0) as tahun_n , 0 thn_berjalan
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5206' and year(tgl_voucher)=$thn_ang
                    group by kd_skpd,left(a.kd_rek6,6)
                    
                    ) b 
                
                    where left(b.kd_rek6,4)='1503' 
                    group by b.kd_skpd, b.nm_skpd, b.kd_rek6
                    ) x
                            where (sal_awal<>0 OR tambah<>0 OR kurang<>0 OR tahun_n<>0 )
                                        and kd_skpd<>'' and nm_skpd<>''
                    order by kd_skpd, kd_rek3");

                }elseif($rek3==1504){
                    $query = DB::select("SELECT kd_skpd, nm_skpd, kd_rek3, nm_rek3, isnull(sal_awal,0) sal_awal, isnull(tambah,0) tambah, isnull(kurang,0) kurang, 
                    isnull(tahun_n,0) tahun_n 
                    FROM (
                        --rek3
                    select a.kd_skpd, a.nm_skpd, a.kd_rek3, a.nm_rek3, 
                    SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n 
                    from (
                        select kd_skpd, nm_skpd, a.kd_rek3, b.nm_rek3 
                        from (select kd_skpd, nm_skpd, '1504' kd_rek3 from ms_skpd) a left join ms_rek3 b on a.kd_rek3=b.kd_rek3 )
                     a 
                    left join 
                    (
                        SELECT kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, left(a.kd_rek6,4) kd_rek3,
                     sum(debet-kredit) sal_awal , 0 tambah , 0 kurang, 0 tahun_n, 0 thn_berjalan
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,4)='1504' and year(tgl_voucher)<=$thn_ang_1 
                    group by kd_skpd,nm_skpd,left(a.kd_rek6,4)
                
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, '1504' kd_rek3,
                     0 sal_awal , sum(nilai) tambah, 0 kurang, 0 tahun_n, 0 thn_berjalan
                    from isi_neraca_calk b where  left(kd_rek,4)='1542'
                    group by kd_skpd 
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, '1504' kd_rek3,
                     0 sal_awal , 0 tambah, sum(nilai) kurang, 0 tahun_n, 0 thn_berjalan
                    from isi_neraca_calk b where left(kd_rek,4)='1543'
                    group by kd_skpd
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, left(a.kd_rek6,4) kd_rek3,
                    0 sal_awal, 0 tambah, 0 kurang,
                    isnull(sum(0),0) as tahun_n , 0 thn_berjalan
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,5)='15421' and year(tgl_voucher)=$thn_ang
                    group by kd_skpd,left(a.kd_rek6,4)
                    UNION ALL
                    SELECT z.kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=z.kd_skpd ) nm_skpd, kd_rek3,
                     0 sal_awal , 0 tambah , 0 kurang, 0 tahun_n, SUM(z.thn_berjalan) thn_berjalan
                    from (
                            select kd_skpd, sum(debet-kredit) thn_berjalan, left(a.kd_rek6,4) kd_rek3
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where left(kd_rek6,4)='1504' and year(tgl_voucher)<=$thn_ang 
                            group by kd_skpd,nm_skpd, left(a.kd_rek6,4) 
                        )z
                    group by kd_skpd,kd_rek3
                    ) b on a.kd_skpd=b.kd_skpd
                    group by a.kd_skpd, a.nm_skpd, a.kd_rek3, a.nm_rek3
                    UNION ALL 
                    --rek5  
                    
                    select b.kd_skpd, b.nm_skpd, b.kd_rek6 kd_rek3, (select nm_rek3 from ms_rek3 where kd_rek3='1504' ) nm_rek3, 
                    SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n 
                    from 
                    (
                    SELECT kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, left(a.kd_rek6,6) kd_rek6,
                     sum(debet-kredit) sal_awal , 0 tambah , 0 kurang, 0 tahun_n, 0 thn_berjalan
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,4)='1504' and year(tgl_voucher)<=$thn_ang_1 
                    group by kd_skpd,nm_skpd,left(a.kd_rek6,6)
                
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, '150401' kd_rek6,
                     0 sal_awal , sum(nilai) tambah, 0 kurang, 0 tahun_n, 0 thn_berjalan
                    from isi_neraca_calk b where  left(kd_rek,4)='1542'
                    group by kd_skpd 
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, '150401' kd_rek6,
                     0 sal_awal , 0 tambah, sum(nilai) kurang, 0 tahun_n, 0 thn_berjalan
                    from isi_neraca_calk b where left(kd_rek,4)='1543'
                    group by kd_skpd
                    UNION ALL
                    select kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=b.kd_skpd ) nm_skpd, left(a.kd_rek6,6) kd_rek6,
                    0 sal_awal, 0 tambah, 0 kurang,
                    isnull(sum(0),0) as tahun_n , 0 thn_berjalan
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,6)='15421' and year(tgl_voucher)=$thn_ang
                    group by kd_skpd,left(a.kd_rek6,6)
                    
                    ) b 
                
                    where left(b.kd_rek6,4)='1504' 
                    group by b.kd_skpd, b.nm_skpd, b.kd_rek6
                    ) x
                    where (sal_awal<>0 OR tambah<>0 OR kurang<>0 OR tahun_n<>0 )
                                and kd_skpd<>'' and nm_skpd<>''
                                order by kd_skpd, kd_rek3");
                
                }elseif($rek3==1111){
                    $query = DB::select("SELECT kd_skpd, nm_skpd, kd_rek3, nm_rek3,
                    isnull(sal_awal,0) sal_awal,
                    isnull(tambah,0) tambah, 
                    isnull(kurang,0) kurang, 
                    isnull(tahun_n,0) tahun_n,
                    isnull(koreksi,0) koreksi FROM 
                    (
                    select a.kd_skpd, a.nm_skpd, a.kd_rek3, a.nm_rek3, 
                    SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n ,sum(koreksi)koreksi
                    from (
                    select kd_skpd, nm_skpd, a.kd_rek3, b.nm_rek3 
                    from (select kd_skpd, nm_skpd, '1111' kd_rek3 from ms_skpd) a left join ms_rek3 b on a.kd_rek3=b.kd_rek3
                    ) a 
                    left join 
                     (
                    select b.kd_skpd, b.kd_rek3,  
                    SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n,sum(isnull(korplus,0)-isnull(kormin,0)) koreksi
                    from lamp_aset b where b.kd_rek3='1111'  group by b.kd_skpd, b.kd_rek3
                    ) b on a.kd_skpd=b.kd_skpd
                    group by a.kd_skpd, a.nm_skpd, a.kd_rek3, a.nm_rek3
                    
                    UNION ALL 

                    select a.kd_skpd, a.nm_skpd, a.kd_rek4 kd_rek3, a.nm_rek4 nm_rek3, 
                    SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n ,sum(koreksi)koreksi
                    from 
                    (
                    select kd_skpd, nm_skpd, kd_rek4 kd_rek4, nm_rek4 nm_rek4 
                    from (select kd_skpd, nm_skpd, '1111' kd_rek3 from ms_skpd) a left join ms_rek4 b on a.kd_rek3=b.kd_rek3
                    ) a
                    left join 
                    (
                    select b.kd_skpd, b.kd_rek6,  
                    SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n,sum(isnull(korplus,0)-isnull(kormin,0)) koreksi
                    from lamp_aset b where b.kd_rek3='1111'  group by b.kd_skpd, b.kd_rek6
                    ) b on a.kd_skpd=b.kd_skpd 
                    and a.kd_rek4=left(b.kd_rek6,6) 
                    where left(a.kd_rek4,4)='1111' 
                    group by a.kd_skpd, a.nm_skpd, a.kd_rek4, a.nm_rek4


                    UNION ALL 

                    select a.kd_skpd, a.nm_skpd, a.kd_rek6 kd_rek3, a.nm_rek6 nm_rek3 ,
                    SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n 
                    from 
                    (
                    select kd_skpd, nm_skpd, kd_rek6 , nm_rek6  
                    from (select kd_skpd, nm_skpd, '1111' kd_rek4 from ms_skpd) a left join ms_rek6 b on a.kd_rek4=left(b.kd_rek6,4)
                    ) a
                    left join 
                    (
                    select b.kd_skpd, b.kd_rek6,  
                    SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n
                    from lamp_aset b where LEFT(b.kd_rek6,4)='1111'  group by b.kd_skpd, b.kd_rek6
                    ) b on a.kd_skpd=b.kd_skpd 
                    and a.kd_rek6=b.kd_rek6 
                    where left(a.kd_rek6,4)='1111' 
                    group by a.kd_skpd, a.nm_skpd, a.kd_rek6, a.nm_rek6
                    
                    ) x
                    where sal_awal<>0 or tambah<>0 or kurang<>0 or tahun_n<>0
                    order by kd_skpd, kd_rek3");
                
                }elseif($rek3=="1103-1109"){
                    $rek_="'1103','1104','1105','1106','1107','1108','1109'";
                    $query = DB::select("SELECT kd_skpd, nm_skpd, kd_rek3, nm_rek3, isnull(sal_awal,0) sal_awal, isnull(tambah,0) tambah, isnull(kurang,0) kurang, isnull(tahun_n,0) tahun_n ,isnull(koreksi,0)koreksi
                        FROM (
                            select b.kd_skpd,(select nm_skpd from ms_skpd where b.kd_skpd=kd_skpd)nm_skpd, b.kd_rek3,(select nm_rek3 from ms_rek3 where b.kd_rek3=kd_rek3)nm_rek3,  
                            SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n,sum(isnull(korplus,0)-isnull(kormin,0)) koreksi
                            from lamp_aset b where b.kd_rek3 in ('1103','1104','1105','1106','1107','1108','1109')  group by b.kd_skpd, b.kd_rek3
                            
                            UNION ALL 


                            select b.kd_skpd,(select nm_skpd from ms_skpd where b.kd_skpd=kd_skpd)nm_skpd, b.kd_rek6 kd_rek3,(select nm_rek6 from ms_rek6 where b.kd_rek6=kd_rek6)nm_rek3,    
                            SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n,sum(isnull(korplus,0)-isnull(kormin,0)) koreksi
                            from lamp_aset b where b.kd_rek3 in('1103','1104','1105','1106','1107','1108','1109')  
                            group by b.kd_skpd, b.kd_rek6

                        ) x where sal_awal<>0 or tambah<>0 or kurang<>0 or tahun_n<>0
                
                            order by kd_skpd, kd_rek3");
                }else{
                    $query = DB::select("SELECT kd_skpd, nm_skpd, kd_rek3, nm_rek3, isnull(sal_awal,0) sal_awal, isnull(tambah,0) tambah, isnull(kurang,0) kurang, isnull(tahun_n,0) tahun_n , isnull(koreksi,0) koreksi 
                        FROM (
                            select a.kd_skpd, a.nm_skpd, a.kd_rek3, a.nm_rek3, 
                            SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n,sum(koreksi)koreksi 
                            from (select kd_skpd, nm_skpd, a.kd_rek3, b.nm_rek3 
                            from (select kd_skpd, nm_skpd, '$rek3' kd_rek3 from ms_skpd) a left join ms_rek3 b on a.kd_rek3=b.kd_rek3) a 
                            left join 
                            (select b.kd_skpd, b.kd_rek3,  
                            SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n,sum(isnull(korplus,0)-isnull(kormin,0)) koreksi
                            from lamp_aset b where b.kd_rek3='$rek3'  group by b.kd_skpd, b.kd_rek3) b on a.kd_skpd=b.kd_skpd
                            group by a.kd_skpd, a.nm_skpd, a.kd_rek3, a.nm_rek3
                            
                            UNION ALL 

                            select a.kd_skpd, a.nm_skpd, a.kd_rek6 kd_rek3, a.nm_rek6 nm_rek3, 
                            SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n ,sum(koreksi)koreksi
                            from (
                            select kd_skpd, nm_skpd, kd_rek6 kd_rek6, nm_rek6 nm_rek6 
                            from (select kd_skpd, nm_skpd, '$rek3' kd_rek3 from ms_skpd) a left join ms_rek6 b on a.kd_rek3=left(b.kd_rek6,4) $where
                            ) a
                            left join 
                            (
                            select b.kd_skpd, b.kd_rek6,  
                            SUM(b.sal_awal) sal_awal, SUM(b.tambah) as tambah, SUM(b.kurang) as kurang, SUM(b.tahun_n) as tahun_n,sum(isnull(korplus,0)-isnull(kormin,0)) koreksi
                            from lamp_aset b where b.kd_rek3='$rek3'  group by b.kd_skpd, b.kd_rek6
                            ) b on a.kd_skpd=b.kd_skpd and a.kd_rek6=left(b.kd_rek6,12) 
                            where left(a.kd_rek6,4)='$rek3' 
                            group by a.kd_skpd, a.nm_skpd, a.kd_rek6, a.nm_rek6 

                        ) x where sal_awal<>0 or tambah<>0 or kurang<>0 or tahun_n<>0
                
                            order by kd_skpd, kd_rek3");
                }
                $query_tot ="";
            }
        }else{
            if ($jenis=="1") {
                if($rek3==15){
                    $nama_rek='Aset Lainnya';
                    $where="LEFT(b.kd_rek6,2)";
                    $rek3_ ="'$rek3'";
                }else if($rek3==1505){
                    $nama_rek='Amortisasi';
                    $where="LEFT(b.kd_rek6,7)";
                    $rek3_ ="'$rek3'";
                }else if($rek3==1503){
                    $nama_rek=nama_rek3($rek3);
                    $where="LEFT(b.kd_rek6,6)";
                    $rek3_ ="'150301'"; 
                }else if($rek3==15306){
                    $nama_rek=nama_rek3($rek3);
                    $where="LEFT(b.kd_rek6,5)";
                    $rek3_ ="'15306'"; 
                }else if($rek3=="1103-1109"){
                    $nama_rek="Keseluruhan Piutang";
                    $where="LEFT(b.kd_rek6,5)";
                    $rek3_ ="'1103','1104','1105','1106','1107','1108','1109'"; 
                }else{
                    $nama_rek=nama_rek3($rek3);
                    $where="LEFT(b.kd_rek6,4)";
                    $rek3_ ="'$rek3'";
                }

                $query = DB::select("SELECT x.kd_skpd kd_unit, x.nm_skpd, ISNULL(nilai,0) nilai, ISNULL(nilai_lalu,0) nilai_lalu 
                        FROM (
                        SELECT kd_skpd, nm_skpd FROM ms_skpd
                        ) x
                        LEFT JOIN
                        (
                        SELECT * FROM (SELECT b.kd_unit, (SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=b.kd_unit) nm_skpd,
                                                   SUM(CASE WHEN YEAR(a.tgl_voucher)<=$thn_ang THEN debet-kredit ELSE 0 END) nilai,
                                                   SUM(CASE WHEN YEAR(a.tgl_voucher)<=$thn_ang_1 THEN debet-kredit ELSE 0 END) nilai_lalu 
                                            FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                                            WHERE $where IN ($rek3_) GROUP BY b.kd_unit) z WHERE nilai<>0 OR nilai_lalu<>0 
                        ) y ON x.kd_skpd=y.kd_unit                  
                        ORDER BY kd_unit");
                
                $query_tot ="";
            }else{
                if($rek3==15){
                    $nama_rek='Aset Lainnya';
                    $where="LEFT(b.kd_rek6,2)";
                    $where2="LEFT(b.kd_rek6,2)";
                    $rek3_ =$rek3;
                }else if($rek3==1503){
                    $nama_rek=nama_rek3($rek3);
                    $where="LEFT(b.kd_rek6,5)";
                    $where2="LEFT(b.kd_rek6,4)";
                    $rek3_ =150301; 
                }else if($rek3==15306){
                    $nama_rek=nama_rek3(153);
                    $where="LEFT(b.kd_rek6,5)";
                    $where2="LEFT(b.kd_rek6,4)";
                    $rek3_ =15306; 
                }else if($rek3=="1103-1109"){
                    $nama_rek="Keseluruhan Piutang";
                    $where="LEFT(b.kd_rek6,4)";
                    $where2="LEFT(b.kd_rek6,4)";
                    $rek3_ ="1103','1104','1105','1106','1107','1108','1109"; 
                }else{
                    $nama_rek=nama_rek3($rek3);
                    $where="LEFT(b.kd_rek6,4)";
                    $where2="LEFT(b.kd_rek6,4)";
                    $rek3_ =$rek3;
                }
                $query=DB::select("SELECT * FROM (SELECT 1 jns, b.kd_unit, (SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=b.kd_unit) nm_unit, $where2 kd_akun,
                           (SELECT nm_rek3 FROM ms_rek3 WHERE kd_rek3=$where2) nm_akun,
                           SUM(CASE WHEN YEAR(a.tgl_voucher)<=$thn_ang THEN debet-kredit ELSE 0 END) nilai,
                           SUM(CASE WHEN YEAR(a.tgl_voucher)<=$thn_ang_1 THEN debet-kredit ELSE 0 END) nilai_lalu 
                    FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                    WHERE $where IN ('$rek3_') GROUP BY b.kd_unit, $where2 
                    UNION ALL
                    SELECT 2 jns, b.kd_unit, (SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=b.kd_unit) nm_unit, LEFT(b.kd_rek6,12) kd_akun,
                           (SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6=LEFT(b.kd_rek6,12)) nm_akun,
                           SUM(CASE WHEN YEAR(a.tgl_voucher)<=$thn_ang THEN debet-kredit ELSE 0 END) nilai,
                           SUM(CASE WHEN YEAR(a.tgl_voucher)<=$thn_ang_1 THEN debet-kredit ELSE 0 END) nilai_lalu 
                    FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                    WHERE $where IN ('$rek3_') GROUP BY b.kd_unit, LEFT(b.kd_rek6,12)) z
                    WHERE nilai<>0 OR nilai_lalu<>0
                    ORDER BY kd_unit, kd_akun");

                $query_tot =collect(DB::select("SELECT 
                           SUM(CASE WHEN YEAR(a.tgl_voucher)<=$thn_ang THEN debet-kredit ELSE 0 END) tot,
                           SUM(CASE WHEN YEAR(a.tgl_voucher)<=$thn_ang_1 THEN debet-kredit ELSE 0 END) tot_lalu 
                    FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                    WHERE $where IN ('$rek3_')"))->first();
            }

        }

        if ($rek3=="1103-1109") {
            $nama_rek="Keseluruhan Piutang";
        }else{

            $namanya = collect(DB::select("SELECT nm_rek3 from ms_rek3 where kd_rek3='$rek3'"))->first();
            $nama_rek=$namanya->nm_rek3;
        }


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
            $data = [
            'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'cetak'        => $cetak,
            'query'          => $query,
            'query_tot'          => $query_tot,
            'namanya'        => $nama_rek,
            'format'         => $format,
            'jenis'          => $jenis,
            'rek3'           => $rek3,
            'thn_ang'        => $thn_ang,
            'thn_ang_1'      => $thn_ang_1     
            ];

        if($format=="1"){
            if($jenis=="1"){
                $view =  view('akuntansi.calk_aset.cetakan.lap_aset.global_lampiran')->with($data);
            }else{
                $view =view('akuntansi.calk_aset.cetakan.lap_aset.rinci_lampiran')->with($data);
            }
        }else{
            if ($jenis=="1") {
                $view =view('akuntansi.calk_aset.cetakan.lap_aset.global_neraca')->with($data);
            }else{
                $view =view('akuntansi.calk_aset.cetakan.lap_aset.rinci_neraca')->with($data);
            }

        }

        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Lap_aset.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Lap_aset.xls"');
            return $view;
        }
    }

    public function cetak_lap_penyu_aset(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $rek3    = $request->rek3;
        $cetak   = $request->cetak;
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        if($rek3=="1302"){
            $kd_aset = "130701";
            $kd_beban = "810801";
        }elseif($rek3=="1303"){
            $kd_aset = "130702";
            $kd_beban = "810802";
        }elseif($rek3=="1304"){
            $kd_aset = "130703";
            $kd_beban = "810803";
        }elseif($rek3=="1305"){
            $kd_aset = "130704";
            $kd_beban = "810804";
        }
        

        $query = DB::select("SELECT kd_skpd,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd) nm_skpd,SUM(sal_lalu) sal_lalu ,SUM(sal-penyusutan-sal_lalu) koreksi,SUM(penyusutan)penyusutan,sum(sal)sal
            from  
            (
                SELECT kd_skpd, sum(debet-kredit) sal_lalu , 0 penyusutan, 0 sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,6)='$kd_aset' and year(tgl_voucher)<=$thn_ang_1 
                group by kd_skpd,nm_skpd
                union all
                select kd_skpd, 0 sal_lalu , sum(kredit-debet) penyusutan, 0 sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,6)='$kd_beban' and year(tgl_voucher)=$thn_ang and tgl_real in ('',0)
                group by kd_skpd,nm_skpd
                union all
                select kd_skpd, 0 sal_lalu , 0 penyusutan, sum(debet-kredit) Sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,6)='$kd_aset' and year(tgl_voucher)<=$thn_ang 
                group by kd_skpd,nm_skpd

            ) a 
            group by kd_skpd 
            order by kd_skpd");
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
            $data = [
            'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'cetak'          => $cetak,
            'query'          => $query,
            'rek3'           => $rek3,
            'thn_ang'        => $thn_ang,
            'thn_ang_1'      => $thn_ang_1     
            ];

            $view =  view('akuntansi.calk_aset.cetakan.lap_penyu_aset.lap_penyu_aset')->with($data);

        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Lap_Penyusutan_aset.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Lap_Penyusutan_aset.xls"');
            return $view;
        }
    }

    public function cetak_lap_amortisasi(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $rek3    = $request->rek3;
        $cetak   = $request->cetak;
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;
        

        $query = DB::select("SELECT kd_skpd,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd,SUM(sal_lalu) sal_lalu ,SUM(koreksi) koreksi,SUM(amortisasi)amortisasi,SUM(sal) sal 
            from  
            (
                SELECT kd_skpd,sum(debet-kredit) sal_lalu , 0 koreksi, 0 amortisasi, 0 sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,6)='150501' and year(tgl_voucher)<=$thn_ang_1 
                group by kd_skpd,nm_skpd
                union all
                select kd_skpd,0 sal_lalu , sum(debet-kredit) koreksi, 0 amortisasi, 0 sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,6)='150501' and year(tgl_voucher)=$thn_ang and tgl_real  in ('',0) 
                group by kd_skpd,nm_skpd
                union all
                select kd_skpd,0 sal_lalu , 0 koreksi, sum(kredit-debet) amortisasi, 0 sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,6)=('810806') and year(tgl_voucher)=$thn_ang and tgl_real in ('',0) 
                group by kd_skpd,nm_skpd
                union all
                select kd_skpd,0 sal_lalu , 0 koreksi, 0 amortisasi, sum(debet-kredit) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,6)='150501' and year(tgl_voucher)<=$thn_ang 
                group by kd_skpd,nm_skpd
            ) a 
            group by kd_skpd 
            order by kd_skpd");
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
            $data = [
            'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'cetak'          => $cetak,
            'query'          => $query,
            'thn_ang'        => $thn_ang,
            'thn_ang_1'      => $thn_ang_1     
            ];

            $view =  view('akuntansi.calk_aset.cetakan.lap_amortisasi.lap_amortisasi')->with($data);

        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LAP_Amortisasi.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LAP_Amortisasi.xls"');
            return $view;
        }
    }

    public function cetak_lap_peng_aset(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $rek3    = $request->rek3;
        $cetak   = $request->cetak;
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;
        

        $query = DB::select("SELECT a.kd_skpd, a.nm_skpd
            ,ISNULL(mod_tanah,0) as mod_tanah 
            ,ISNULL(mod_mesin,0) as mod_mesin 
            ,ISNULL(mod_gedung,0) as mod_gedung 
            ,ISNULL(mod_jalan,0) as mod_jalan
            ,ISNULL(mod_tetap,0) as mod_tetap
            ,ISNULL(mod_lainnya,0) as mod_lainnya
            ,ISNULL(aset_persediaan,0) as aset_persediaan
            ,ISNULL(aset_tanah,0) as aset_tanah
            ,ISNULL(aset_mesin,0) as aset_mesin
            ,ISNULL(aset_gedung,0) as aset_gedung
            ,ISNULL(aset_jalan,0) as aset_jalan
            ,ISNULL(aset_tetap,0) as aset_tetap
            ,ISNULL(aset_kontruksi,0) as aset_kontruksi
            ,ISNULL(aset_takwujud,0) as aset_takwujud
            ,ISNULL(aset_lainnya,0) as aset_lainnya
            FROM ms_skpd a 
            LEFT JOIN 
            (
                SELECT kd_skpd
                ,SUM(CASE WHEN kd_rek ='5201' THEN real_spj ELSE 0 END) AS mod_tanah
                ,SUM(CASE WHEN kd_rek ='5202' THEN real_spj ELSE 0 END) AS mod_mesin
                ,SUM(CASE WHEN kd_rek ='5203' THEN real_spj ELSE 0 END) AS mod_gedung
                ,SUM(CASE WHEN kd_rek ='5204' THEN real_spj ELSE 0 END) AS mod_jalan
                ,SUM(CASE WHEN kd_rek ='5205' THEN real_spj ELSE 0 END) AS mod_tetap
                ,SUM(CASE WHEN kd_rek ='5206' THEN real_spj ELSE 0 END) AS mod_lainnya
                ,SUM(CASE WHEN kd_rek ='1112' THEN real_spj ELSE 0 END) AS aset_persediaan
                ,SUM(CASE WHEN kd_rek ='1301' THEN real_spj ELSE 0 END) AS aset_tanah
                ,SUM(CASE WHEN kd_rek ='1302' THEN real_spj ELSE 0 END) AS aset_mesin
                ,SUM(CASE WHEN kd_rek ='1303' THEN real_spj ELSE 0 END) AS aset_gedung
                ,SUM(CASE WHEN kd_rek ='1304' THEN real_spj ELSE 0 END) AS aset_jalan
                ,SUM(CASE WHEN kd_rek ='1305' THEN real_spj ELSE 0 END) AS aset_tetap
                ,SUM(CASE WHEN kd_rek ='1306' THEN real_spj ELSE 0 END) AS aset_kontruksi
                ,SUM(CASE WHEN kd_rek ='1503' THEN real_spj ELSE 0 END) AS aset_takwujud
                ,SUM(CASE WHEN kd_rek ='1504' THEN real_spj ELSE 0 END) AS aset_lainnya
                FROM 
                (
                    select b.kd_skpd, left(a.kd_rek6,4) kd_rek, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as real_spj 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where YEAR(b.tgl_voucher)='$thn_ang' AND left(a.kd_rek6,4) in ('5201','5202','5203','5204','5205','5206')
                    group by kd_skpd, left(a.kd_rek6,4)
                                
                    UNION ALL
                    select kd_skpd, kd_rek3 as kd_rek, ISNULL(SUM(tahun_n),0)+ISNULL(SUM(nilai),0) as real_spj 
                    from trdkapitalisasi
                    where kd_rek3 IN('1301','1302','1303','1304','1305','1503','1504')
                    group by kd_skpd, kd_rek3           
                )a
                GROUP BY kd_skpd
            ) b
            ON a.kd_skpd=b.kd_skpd
            order by a.kd_skpd");
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
            $data = [
            'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'cetak'          => $cetak,
            'query'          => $query,
            'thn_ang'        => $thn_ang,
            'thn_ang_1'      => $thn_ang_1     
            ];

            $view =  view('akuntansi.calk_aset.cetakan.lap_peng_aset.lap_peng_aset')->with($data);

        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LAP_Pengadaan_Aset.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LAP_Pengadaan_Aset.xls"');
            return $view;
        }
    }

    public function cetak_lap_pen_lralo(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $format    = $request->format;
        $cetak   = $request->cetak;
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;
        
        if ($format=="1") {
            $query = DB::select("SELECT a.kd_skpd, a.nm_skpd
                ,ISNULL(pend,0) as pend 
                ,ISNULL(bel_peg,0) as bel_peg 
                ,ISNULL(bel_br,0) as bel_br 
                ,ISNULL(pend_lo,0) as pend_lo
                ,ISNULL(bbn_peg,0) as bbn_peg
                ,ISNULL(bbn_br,0) as bbn_br
                FROM ms_skpd a 
                LEFT JOIN 
                (
                    SELECT kd_skpd
                    ,SUM(CASE WHEN kd_rek ='4' THEN real_spj ELSE 0 END) AS pend
                    ,SUM(CASE WHEN kd_rek ='5101' THEN real_spj ELSE 0 END) AS bel_peg
                    ,SUM(CASE WHEN kd_rek ='5102' THEN real_spj ELSE 0 END) AS bel_br
                    ,SUM(CASE WHEN kd_rek ='7' THEN real_spj ELSE 0 END) AS pend_lo
                    ,SUM(CASE WHEN kd_rek ='8101' THEN real_spj ELSE 0 END) AS bbn_peg
                    ,SUM(CASE WHEN kd_rek ='8102' THEN real_spj ELSE 0 END) AS bbn_br
                    FROM
                    (
                        select b.kd_skpd, left(a.kd_rek6,4) kd_rek, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as real_spj 
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                        where left(a.kd_rek6,4) in ('5101','5102','8101','8102') AND YEAR(b.tgl_voucher)=$thn_ang
                        group by kd_skpd, left(a.kd_rek6,4)
                        UNION ALL
                        select b.kd_skpd, left(a.kd_rek6,1) kd_rek, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_spj 
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                        where left(a.kd_rek6,1) in ('4','7') AND YEAR(b.tgl_voucher)=$thn_ang
                        GROUP BY kd_skpd, left(a.kd_rek6,1)
                    )a
                    GROUP BY kd_skpd
                ) b ON 
                a.kd_skpd=b.kd_skpd
                order by a.kd_skpd");
            
        }elseif ($format=="2") {
            $query = DB::select("SELECT o.kd_skpd,(select nm_skpd from trdrka where kd_skpd=o.kd_skpd group by nm_skpd) nm_skpd, 
                ISNULL(k.lra_pjk,0) lra_pjk, 
                ISNULL(p.lo_pjk,0) lo_pjk, 
                ISNULL(l.lra_retri,0) lra_retri, 
                ISNULL(q.lo_retri,0) lo_retri, 
                ISNULL(m.lra_hasil,0) lra_hasil, 
                ISNULL(r.lo_hasil,0) lo_hasil, 
                ISNULL(n.lra_lain,0) lra_lain, 
                ISNULL(s.lo_lain,0) lo_lain,
                ISNULL(ee.lra_dbh,0) lra_dbh,
                ISNULL(tt.lo_dbh,0) lo_dbh, 
                ISNULL(x.lra_dau,0) lra_dau,
                ISNULL(y.lo_dau,0) lo_dau,
                ISNULL(z.lra_dak,0) lra_dak, 
                ISNULL(za.lo_dak,0) lo_dak,
                ISNULL(t.lra_dak_nf,0) lra_dak_nf,
                ISNULL(u.lo_dak_nf,0) lo_dak_nf, 
                ISNULL(qq.lra_did,0) lra_did, 
                ISNULL(pp.lo_did,0) lo_did,
                ISNULL(zb.lra_dok,0) lra_dok, 
                ISNULL(zc.lo_dok,0) lo_dok,
                ISNULL(zf.lra_hibah,0) lra_hibah,
                ISNULL(zg.lo_hibah,0) lo_hibah,
                ISNULL(zj.lra_lainnya,0) lra_lainnya,
                ISNULL(zk.lo_lainnya,0) lo_lainnya
                FROM 
                (
                    select kd_skpd from trdrka group by kd_skpd
                ) o
                LEFT JOIN 
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lra_pjk 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,4) IN ('4101') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) k on o.kd_skpd=k.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lo_pjk from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher where LEFT(b.kd_rek6,4) IN ('7101') AND YEAR(tgl_voucher)=$thn_ang group by b.kd_unit) p on o.kd_skpd=p.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lra_retri 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,4) IN ('4102') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) l on o.kd_skpd=l.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lo_retri 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,4) IN ('7102') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) q on o.kd_skpd=q.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lra_hasil 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,4) IN ('4103') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) m on o.kd_skpd=m.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lo_hasil 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,4) IN ('7103') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) r on o.kd_skpd=r.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lra_lain 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,4) IN ('4104') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) n on o.kd_skpd=n.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lo_lain 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,4) IN ('7104') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) s on o.kd_skpd=s.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lra_dbh 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,8) IN ('42010101') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) ee on o.kd_skpd=ee.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lo_dbh 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,8) IN ('72010101') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) tt on o.kd_skpd=tt.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lra_dau 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,8) IN ('42010102') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) x on o.kd_skpd=x.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lo_dau 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,8) IN ('72010102') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) y on o.kd_skpd=y.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lra_dak 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,8) IN ('42010103') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) z on o.kd_skpd=z.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lo_dak 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,8) IN ('72010103') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) za on o.kd_skpd=za.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lra_dak_nf 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,8) IN ('42010104') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) t on o.kd_skpd=t.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lo_dak_nf 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,8) IN ('72010104') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) u on o.kd_skpd=u.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lra_did 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,6) IN ('720102') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) qq on o.kd_skpd=t.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lo_did 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,6) IN ('420102') AND YEAR(tgl_voucher)=$thn_ang group by b.kd_unit
                ) pp on o.kd_skpd=u.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lra_dok 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,6) IN ('420103') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) zb on o.kd_skpd=zb.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lo_dok 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,6) IN ('720103') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) zc on o.kd_skpd=zc.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lra_hibah 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,4) IN ('4301') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) zf on o.kd_skpd=zf.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lo_hibah 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,4) IN ('7301') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) zg on o.kd_skpd=zg.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lra_lainnya 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher
                    where LEFT(b.kd_rek6,4) IN ('4303') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                    ) zj on o.kd_skpd=zj.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.kredit-b.debet) lo_lainnya 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and  a.no_voucher=b.no_voucher 
                    where LEFT(b.kd_rek6,4) IN ('7303') AND YEAR(tgl_voucher)=$thn_ang 
                    group by b.kd_unit
                ) zk on o.kd_skpd=zk.kd_skpd
                order by kd_skpd");
        }elseif($format=="3"){
            $query = DB::select("SELECT o.kd_skpd,(select nm_skpd from trdrka where kd_skpd=o.kd_skpd group by nm_skpd) nm_skpd, 
                ISNULL(k.lra_brg,0) lra_brg, 
                ISNULL(p.lo_brg,0) lo_brg, 
                ISNULL(l.jasa,0) jasa, 
                ISNULL(q.lo_jasa,0) lo_jasa, 
                ISNULL(m.lra_pemeliharaan,0) lra_pemeliharaan, 
                ISNULL(r.lo_pemeliharaan,0) lo_pemeliharaan, 
                ISNULL(n.lra_prj_dinas,0) lra_prj_dinas, 
                ISNULL(s.lo_prj_dinas,0) lo_prj_dinas, 
                ISNULL(ra.lra_serah,0) lra_serah, 
                ISNULL(ma.lo_serah,0) lo_serah 
                FROM 
                (
                    select kd_skpd 
                    from trdrka 
                    group by kd_skpd 
                ) o
                LEFT JOIN 
                (
                    select b.kd_unit as kd_skpd, sum(b.debet-b.kredit) lra_brg 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                    where left(b.kd_rek6,6) IN ('510201') AND YEAR(tgl_voucher)=$thn_ang
                    group by b.kd_unit
                ) k on o.kd_skpd=k.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.debet-b.kredit) lo_brg 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                    where  left(b.kd_rek6,6) IN ('810201') AND YEAR(tgl_voucher)=$thn_ang
                    group by b.kd_unit
                ) p on o.kd_skpd=p.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.debet-b.kredit) jasa 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                    where left(b.kd_rek6,6) IN ('510202') AND YEAR(tgl_voucher)=$thn_ang
                    group by b.kd_unit
                )l on o.kd_skpd=l.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.debet-b.kredit) lo_jasa 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                    where left(b.kd_rek6,6) IN ('810202') AND YEAR(tgl_voucher)=$thn_ang
                    group by b.kd_unit
                ) q on o.kd_skpd=q.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.debet-b.kredit) lra_pemeliharaan 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                    where left(b.kd_rek6,6) IN ('510203') AND YEAR(tgl_voucher)=$thn_ang
                    group by b.kd_unit
                )m on o.kd_skpd=m.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.debet-b.kredit) lo_pemeliharaan 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                    where left(b.kd_rek6,6) IN ('810203') AND YEAR(tgl_voucher)=$thn_ang
                    group by b.kd_unit
                ) r on o.kd_skpd=r.kd_skpd
                LEFT JOIN
                (
                select b.kd_unit as kd_skpd, sum(b.debet-b.kredit) lra_prj_dinas 
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where  left(b.kd_rek6,6) IN ('510204') AND YEAR(tgl_voucher)=$thn_ang
                group by b.kd_unit
                )n on o.kd_skpd=n.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.debet-b.kredit) lo_prj_dinas 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                    where left(b.kd_rek6,6) IN ('810204') AND YEAR(tgl_voucher)=$thn_ang
                    group by b.kd_unit
                ) s on o.kd_skpd=s.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.debet-b.kredit) lra_serah 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                    where  left(b.kd_rek6,6) IN ('510205') AND YEAR(tgl_voucher)=$thn_ang
                    group by b.kd_unit
                )ra on o.kd_skpd=ra.kd_skpd
                LEFT JOIN
                (
                    select b.kd_unit as kd_skpd, sum(b.debet-b.kredit) lo_serah 
                    from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                    where left(b.kd_rek6,6) IN ('810205') AND YEAR(tgl_voucher)=$thn_ang
                    group by b.kd_unit
                ) ma on o.kd_skpd=ma.kd_skpd
                order by kd_skpd");
        }


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

        if($format=="1"){
            $view =  view('akuntansi.calk_aset.cetakan.lap_pen_lralo.biasa')->with($data);
        }elseif($format=="2"){
            $view =  view('akuntansi.calk_aset.cetakan.lap_pen_lralo.rinci_pendapatan')->with($data);
        }elseif($format=="3"){
            $view =  view('akuntansi.calk_aset.cetakan.lap_pen_lralo.rinci_beban')->with($data);
        }


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LAP_Penjelasan_LRA_LO.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LAP_Penjelasan_LRA_LO.xls"');
            return $view;
        }
    }

    public function cetak_lap_pen_komulatif(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $cetak   = $request->cetak;
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        $query = DB::select("SELECT a.kd_skpd, a.nm_skpd,ISNULL(real_sedia,0) as real_sedia ,ISNULL(real_tetap,0) as real_tetap ,ISNULL(real_lain,0) as real_lain 
            FROM ms_skpd a 
            LEFT JOIN 
            (
                SELECT kd_skpd
                ,SUM(CASE WHEN reev ='2' THEN real_spj ELSE 0 END) AS real_sedia
                ,SUM(CASE WHEN reev ='1' THEN real_spj ELSE 0 END) AS real_tetap
                ,SUM(CASE WHEN reev ='3' THEN real_spj ELSE 0 END) AS real_lain
                FROM 
                (
                    select b.kd_skpd, kd_rek6,b.reev ,sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_spj 
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where b.reev = '2' AND kd_rek6 = '310101010001' and year(b.tgl_voucher)=$$thn_ang and month(b.tgl_voucher)<=12
                    GROUP BY b.kd_skpd,kd_rek6,b.reev
                    UNION ALL
                    select b.kd_skpd, kd_rek6,b.reev, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_spj 
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where b.reev = '1' AND kd_rek6 = '310101010001' and year(b.tgl_voucher)=$$thn_ang and month(b.tgl_voucher)<=12
                    GROUP BY b.kd_skpd,kd_rek6,b.reev
                    UNION ALL
                    select b.kd_skpd, kd_rek6,b.reev, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_spj 
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where b.reev = '3' AND kd_rek6 = '310101010001' and year(b.tgl_voucher)=$$thn_ang and month(b.tgl_voucher)<=12
                    GROUP BY b.kd_skpd,kd_rek6,b.reev
                ) a
                GROUP BY kd_skpd
            )b
            ON a.kd_skpd=b.kd_skpd
            order by a.kd_skpd");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.lap_pen_komulatif.lap_pen_komulatif')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LAP_Penjelasan_Komulatif.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LAP_Penjelasan_Komulatif.xls"');
            return $view;
        }
    }

    public function cetak_lap_pen_lo(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $cetak   = $request->cetak;
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        $query = DB::select("SELECT a.kd_skpd, a.nm_skpd,ISNULL(bbn_susut,0) as bbn_susut ,ISNULL(bbn_lain,0) as bbn_lain 
            FROM ms_skpd a 
            LEFT JOIN 
            (
                SELECT kd_skpd,
                SUM(CASE WHEN kd_rek in('8201','8202','8203','8204','8205') THEN real_spj ELSE 0 END) AS bbn_susut,
                SUM(CASE WHEN kd_rek IN ('8107','919') THEN real_spj ELSE 0 END) AS bbn_lain
                FROM 
                (
                    select b.kd_skpd, left(a.kd_rek6,4) kd_rek, sum(isnull(a.debet,0)-isnull(a.kredit,0)) as real_spj 
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4) in ('8201','8202','8203','8204','8205','8107','919')
                    group by kd_skpd, left(a.kd_rek6,4)
                )a
                GROUP BY kd_skpd
            ) b ON a.kd_skpd=b.kd_skpd
            order by a.kd_skpd");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.lap_pen_lo.lap_pen_lo')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LAP_Penjelasan_lo.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LAP_Penjelasan_lo.xls"');
            return $view;
        }
    }

    public function cetak_hambatan_calk(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $cetak   = $request->cetak;
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        $query = DB::select("SELECT * FROM 
            (
                select '1' nomor,gabung.kd_skpd skpd, '' kode, (select nm_skpd from trdrka where kd_skpd=gabung.kd_skpd group by nm_skpd) nm_skpd, '' bidang, 0 angg_ubah, 0 realisasi, 0 persen, '' hambatan 
                from
                (
                    select nomor,keg.kd_skpd,kode,bidang,angg_ubah,real,selisih=[real]-angg_ubah,case when angg_ubah=0  then 0 else ([real]/angg_ubah)*100 end [persen],isnull(e.hambatan,'') [hambatan] 
                    from
                    (
                        select 8 [nomor],a.kd_skpd, b.kd_sub_kegiatan [kode],b.nm_sub_kegiatan [bidang],sum(a.nilai) [angg_ubah],
                            (select ISNULL(sum(debet-kredit),0) from trdju_pkd d inner join trhju_pkd c on d.kd_unit=c.kd_skpd and c.no_voucher=d.no_voucher where YEAR(c.tgl_voucher)=$thn_ang and d.kd_sub_kegiatan=b.kd_sub_kegiatan) [real]
                        from trdrka a join trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd 
                        where right(b.kd_program,2) not in ('00') and a.jns_ang='U2'
                        group by b.kd_sub_kegiatan,b.nm_sub_kegiatan,a.kd_skpd
                    ) as keg 
                    left join 
                    calk_babII e on keg.kode=e.kd_program 
                    where angg_ubah>0 
                )as gabung 
                where persen<75
                group by gabung.kd_skpd
                UNION ALL
                --ubah nomor 1 juga jika ada yang tidak muncul
                select '2' nomor,gabung.kd_skpd skpd, kode, (select nm_skpd from trdrka where kd_skpd=gabung.kd_skpd group by nm_skpd) nm_skpd, bidang, angg_ubah, real as realisasi, persen, hambatan 
                from
                (
                    select nomor,keg.kd_skpd,kode,bidang,angg_ubah,real,selisih=[real]-angg_ubah,case when angg_ubah=0  then 0 else ([real]/angg_ubah)*100 end [persen],isnull(e.hambatan,'') [hambatan] 
                    from
                    (
                        SELECT 10 [nomor],a.kd_skpd,a.kd_sub_kegiatan as kode,
                            (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=a.kd_sub_kegiatan) bidang, isnull (sum(nilai),0) angg_ubah,isnull (sum(real_bel),0) [real]
                        from
                        (
                            SELECT a.kd_skpd,kd_sub_kegiatan,a.kd_rek6,sum(a.nilai) as nilai, 0 as real_bel
                            FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 
                            where LEFT(a.kd_rek6,1) ='5'and jns_ang='U2'
                            GROUP BY a.kd_skpd,kd_sub_kegiatan,a.kd_rek6
                            union all
                            select b.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6, 0 as belanja ,sum(a.debet-a.kredit) as real_bel
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                            where MONTH(tgl_voucher)<=12 and year(b.tgl_voucher)='$thn_ang' and LEFT(kd_rek6,1)='5'
                            group by b.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6
                        )a
                        group by a.kd_skpd,a.kd_sub_kegiatan
                    ) as keg left join calk_babII e on keg.kode=e.kd_program where angg_ubah>0             
                )as gabung 
                where persen<75 
            ) x
            order by skpd,kode");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.hambatan_calk.hambatan_calk')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Hambatan_CALK.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Hambatan_CALK.xls"');
            return $view;
        }
    }

    public function cetak_rekap_bel_peg_brg(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $cetak   = $request->cetak;
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        $query = DB::select("SELECT a.kd_skpd, (SELECT nm_skpd from trdrka where kd_skpd=a.kd_skpd group by nm_skpd) nm_skpd,
            SUM(CASE WHEN LEFT(kd_rek6,4) in ('5101') THEN (debet-kredit) ELSE 0 END) as belanja_pegawai,
            SUM(CASE WHEN LEFT(kd_rek6,4) in ('5102') THEN (debet-kredit) ELSE 0 END) as belanja_brg
            FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
            WHERE LEFT(kd_rek6,4) IN ('5101','5102') AND YEAR(a.tgl_voucher)=$thn_ang
            GROUP BY kd_skpd
            order by kd_skpd");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.rekap_bel_peg_brg.rekap_bel_peg_brg')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Rekap_Belanja_Pegawai_Barang.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Rekap_Belanja_Pegawai_Barang.xls"');
            return $view;
        }
    }

    public function cetak_rekap_pendapatan(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $cetak   = $request->cetak;
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        $query = DB::select("SELECT a.kd_skpd, (SELECT nm_skpd from trdrka where kd_skpd=a.kd_skpd group by nm_skpd) nm_skpd,
                        SUM(CASE WHEN LEFT(b.map_real,1) in ('4') THEN (kredit-debet) ELSE 0 END) as pendapatan
                        FROM $trhju a 
                        INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                        WHERE LEFT(b.map_real,1) IN ('4') AND YEAR(a.tgl_voucher)=$thn_ang
                        GROUP BY kd_skpd
                        ORDER BY kd_skpd");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.rekap_pendapatan.rekap_pendapatan')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Rekap_Pendapatan.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Rekap_Pendapatan.xls"');
            return $view;
        }
    }

    public function cetak_rekap_beban(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $cetak   = $request->cetak;
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        $query = DB::select("SELECT kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) nm_skpd, sum(pegawai)pegawai, sum(barang)barang, sum(jasa)jasa, sum(pemeliharaan)pemeliharaan, sum(perjalanan_dinas)perjalanan_dinas
            from
            (
                select kd_skpd,0 pegawai, 0 barang, 0 jasa, 0 pemeliharaan, 0 perjalanan_dinas from ms_skpd
                union all
                select kd_skpd, sum(b.debet-b.kredit) pegawai, 0 barang, 0 jasa, 0 pemeliharaan, 0 perjalanan_dinas
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,4) IN ('8101') AND YEAR(tgl_voucher)=$thn_ang
                group by kd_skpd
                union all
                select kd_skpd, 0 pegawai, sum(b.debet-b.kredit) barang, 0 jasa, 0 pemeliharaan, 0 perjalanan_dinas
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,6) IN ('810201') AND YEAR(tgl_voucher)=$thn_ang
                group by kd_skpd
                union all
                select kd_skpd, 0 pegawai, 0 barang, sum(b.debet-b.kredit) jasa, 0 pemeliharaan, 0 perjalanan_dinas
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,6) IN ('810202') AND YEAR(tgl_voucher)=$thn_ang
                group by kd_skpd
                union all
                select kd_skpd, 0 pegawai, 0 barang, 0 jasa, sum(b.debet-b.kredit) pemeliharaan, 0 perjalanan_dinas
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,6) IN ('810203') AND YEAR(tgl_voucher)=$thn_ang
                group by kd_skpd
                union all
                select kd_skpd, 0 pegawai, 0 barang, 0 jasa, 0 pemeliharaan, sum(b.debet-b.kredit) perjalanan_dinas
                from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                where LEFT(kd_rek6,6) IN ('810204') AND YEAR(tgl_voucher)=$thn_ang
                group by kd_skpd
            )a
            group by kd_skpd
            order by kd_skpd");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.rekap_beban.rekap_beban')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Rekap_Beban.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Rekap_Beban.xls"');
            return $view;
        }
    }

    public function cetak_penjelasan_pendapatan(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $cetak   = $request->cetak;
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        $query = DB::select("SELECT kd_skpd, (select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) nm_skpd, kd_ang, (SELECT nm_rek4 FROM ms_rek4 WHERE kd_rek4=kd_ang) nm_rek, ket1 
            from lamp_calk_bab3_lra_pend a 
            ORDER BY kd_skpd, kd_ang");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.penjelasan_pendapatan.penjelasan_pendapatan')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Penjelasan_Pendapatan.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Penjelasan_Pendapatan.xls"');
            return $view;
        }
    }

    public function cetak_lap_calk_lo_beban(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $jenis   = $request->jenis;
        $cetak   = $request->cetak;
        $spasi   = 'line-height:1.5em;';
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;
        $isi="";
        $isi_tot="";
        //map_beban2_2021
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


        if ($jenis=="1") {
            $judul = "PAJAK LO";
            $head = "
                    <tr>
                        <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\">KODE SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"35%\" align=\"center\">NAMA SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Pajak Daerah - LRA $thn_ang</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Piutang Pajak $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Piutang Pajak $thn_ang_1</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Pajak Daerah - LO $thn_ang</td>                        
                    </tr>";
            $query = DB::select("SELECT x.kd_skpd, x.nm_skpd, ISNULL(y.lra_pjk,0) lra_pjk, ISNULL(y.piutang_pjk,0) piutang_pjk, ISNULL(y.piutang_pjk_lalu,0) piutang_pjk_lalu 
                FROM 
                (
                    SELECT kd_skpd, nm_skpd FROM ms_skpd
                ) x
                LEFT JOIN
                (
                    SELECT kd_unit,
                            SUM(CASE WHEN jns=411 AND thn=$thn_ang THEN nilai ELSE 0 END) AS lra_pjk, 
                            SUM(CASE WHEN jns=811 AND thn=$thn_ang THEN nilai ELSE 0 END) AS piutang_pjk,
                            SUM(CASE WHEN jns=811 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS piutang_pjk_lalu 
                    FROM 
                    (
                        SELECT 811 jns, d.kd_unit, $thn_ang_1 thn, ISNULL(SUM(d.debet-d.kredit),0)*-1 nilai 
                        FROM $trhju c INNER JOIN $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,4)='1103' AND YEAR(c.tgl_voucher)<='$thn_ang_1'
                        GROUP BY d.kd_unit
                        UNION ALL
                        SELECT 811 jns, d.kd_unit, $thn_ang thn, ISNULL(SUM(d.debet-d.kredit),0) nilai 
                        FROM $trhju c inner join $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,4)='1103' AND YEAR(c.tgl_voucher)<='$thn_ang'
                        GROUP BY d.kd_unit
                        UNION ALL
                        SELECT 411 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE LEFT(b.kd_rek6,4)='4101' AND YEAR(a.tgl_voucher)=$thn_ang
                        GROUP BY b.kd_unit 
                    ) p 
                    GROUP BY kd_unit 
                ) y ON x.kd_skpd=y.kd_unit 
                WHERE lra_pjk<>0 OR piutang_pjk<>0 OR piutang_pjk_lalu<>0 
                ORDER BY kd_skpd");
            
        }elseif ($jenis=="2") {
            $judul = "RETRIBUSI LO";
            $head = "
                    <tr>
                        <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\">KODE SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"30%\" align=\"center\">NAMA SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Retribusi Daerah - LRA $thn_ang</td>
                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Piutang Retribusi $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Piutang Retribusi $thn_ang_1</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Diterima dimuka $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Diterima dimuka $thn_ang_1</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Retribusi Daerah - LO $thn_ang</td>                        
                    </tr>";
            $query = DB::select("SELECT x.kd_skpd, x.nm_skpd, ISNULL(y.lra_pjk,0) lra_pjk, ISNULL(y.piutang_pjk,0) piutang_pjk, ISNULL(y.piutang_pjk_lalu,0) piutang_pjk_lalu, ISNULL(y.dimuka,0) dimuka, ISNULL(y.dimuka_lalu,0) dimuka_lalu 
                FROM (SELECT kd_skpd, nm_skpd FROM ms_skpd) x
                LEFT JOIN
                (
                    SELECT kd_unit,
                                SUM(CASE WHEN jns=412 AND thn=$thn_ang THEN nilai ELSE 0 END) AS lra_pjk, 
                                SUM(CASE WHEN jns=812 AND thn=$thn_ang THEN nilai ELSE 0 END) AS piutang_pjk,
                                SUM(CASE WHEN jns=812 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS piutang_pjk_lalu, 
                                SUM(CASE WHEN jns=8121 AND kd_unit not in('1.02.0.00.0.00.02.0000') AND thn=$thn_ang THEN nilai ELSE 0 END) AS dimuka,
                                SUM(CASE WHEN jns=8121 AND kd_unit not in('1.02.0.00.0.00.02.0000') AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS dimuka_lalu 
                    FROM 
                    (
                        SELECT 812 jns, d.kd_unit, $thn_ang_1 thn, ISNULL(SUM(d.debet-d.kredit),0)*-1 nilai 
                        FROM $trhju c INNER JOIN $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,4)='1104' AND YEAR(c.tgl_voucher)<='$thn_ang_1'
                        GROUP BY d.kd_unit
                        UNION ALL
                        SELECT 812 jns, d.kd_unit, $thn_ang thn, ISNULL(SUM(d.debet-d.kredit),0) nilai 
                        FROM $trhju c inner join $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,4)='1104' AND YEAR(c.tgl_voucher)<='$thn_ang'
                        GROUP BY d.kd_unit
                        UNION ALL
                        SELECT 412 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE LEFT(b.kd_rek6,4)='4102' AND YEAR(a.tgl_voucher)=$thn_ang
                        GROUP BY b.kd_unit 
                        UNION ALL
                        SELECT 8121 jns, d.kd_unit, $thn_ang_1 thn, ISNULL(SUM(d.kredit-d.debet),0) nilai 
                        FROM $trhju c INNER JOIN $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,4)='2105' AND YEAR(c.tgl_voucher)<='$thn_ang_1'
                        GROUP BY d.kd_unit
                        UNION ALL
                        SELECT 8121 jns, d.kd_unit, $thn_ang thn, ISNULL(SUM(d.kredit-d.debet),0)*-1 nilai 
                        FROM $trhju c inner join $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,4)='2105' AND YEAR(c.tgl_voucher)<='$thn_ang'
                        GROUP BY d.kd_unit) p GROUP BY kd_unit 
                    ) y ON x.kd_skpd=y.kd_unit 
                WHERE lra_pjk<>0 OR piutang_pjk<>0 OR piutang_pjk_lalu<>0 OR dimuka<>0 OR dimuka_lalu<>0 
                ORDER BY kd_skpd");
        }elseif ($jenis=="3") {
            $judul = "LAIN LAIN PAD LO";
            $head = "
                    <tr>
                        <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\">KODE SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"30%\" align=\"center\">NAMA SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Lain-lain PAD Yang Sah - LRA $thn_ang</td>
                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Piutang Lain-lain PAD yang Sah $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Piutang Lain-lain PAD yang Sah $thn_ang_1</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Piutang Bagi Hasil Pajak $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Piutang Bagi Hasil Pajak $thn_ang_1</td> 
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Piutang BLUD $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Piutang BLUD $thn_ang_1</td>

                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Pendapatan Diterima Dimuka $thn_ang</td> 
                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Pendapatan Diterima Dimuka $thn_ang_1</td> 

                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Piutang Angsuran Tahun Anggaran $thn_ang</td> 
                        <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">Piutang Angsuran Tahun Anggaran $thn_ang_1</td> 

                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Lain-lain PAD Yang Sah - LO $thn_ang</td>                        
                    </tr>";
            $query = DB::select("SELECT x.kd_skpd, x.nm_skpd, ISNULL(y.lra_lain,0) lra_lain, ISNULL(y.piutang_lain,0) piutang_lain, ISNULL(y.piutang_lain_lalu,0) piutang_lain_lalu,ISNULL(y.piutang_bhp,0) piutang_bhp, ISNULL(y.piutang_bhp_lalu,0) piutang_bhp_lalu,ISNULL(y.piutang_blud,0) piutang_blud, ISNULL(y.piutang_blud_lalu,0) piutang_blud_lalu,ISNULL(y.pdpt_dimuka_lalu,0) pdpt_dimuka_lalu, ISNULL(y.pdpt_dimuka,0) pdpt_dimuka,ISNULL(y.piutang_angsuran_lalu,0) piutang_angsuran_lalu, ISNULL(y.piutang_angsuran,0) piutang_angsuran
                FROM 
                (
                    SELECT kd_skpd, nm_skpd FROM ms_skpd
                    where kd_skpd not in ('1.01.01.04','3.01.01.05')
                ) x
                LEFT JOIN
                (
                    SELECT kd_unit,
                                SUM(CASE WHEN jns=414 AND thn=$thn_ang THEN nilai ELSE 0 END) AS lra_lain, 
                                SUM(CASE WHEN jns=814 AND thn=$thn_ang THEN nilai ELSE 0 END) AS piutang_lain,
                                SUM(CASE WHEN jns=814 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS piutang_lain_lalu,
                                SUM(CASE WHEN jns=8141 AND thn=$thn_ang THEN nilai ELSE 0 END) AS piutang_bhp,
                                SUM(CASE WHEN jns=8141 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS piutang_bhp_lalu,
                                SUM(CASE WHEN jns=8142 AND thn=$thn_ang THEN nilai ELSE 0 END) AS piutang_blud,
                                SUM(CASE WHEN jns=8142 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS piutang_blud_lalu,
                                SUM(CASE WHEN jns=81404 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS pdpt_dimuka_lalu,
                                SUM(CASE WHEN jns=81404 AND thn=$thn_ang THEN nilai ELSE 0 END) AS pdpt_dimuka,
                                SUM(CASE WHEN jns=81405 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS piutang_angsuran_lalu,
                                SUM(CASE WHEN jns=81405 AND thn=$thn_ang THEN nilai ELSE 0 END) AS piutang_angsuran
                    FROM 
                    (
                        SELECT 814 jns, d.kd_unit, $thn_ang_1 thn, ISNULL(SUM(d.debet-d.kredit),0)*-1 nilai 
                        FROM $trhju c INNER JOIN $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE (LEFT(d.kd_rek6,4)='1106' AND LEFT(d.kd_rek6,6)<>'110616') AND YEAR(c.tgl_voucher)<='$thn_ang_1'
                        GROUP BY d.kd_unit
                        UNION ALL
                        --kondisi di calk
                        SELECT 814 jns,b.kd_skpd, '$thn_ang_1' thn , ISNULL(b.nilai,0) nilai_x from ket_lo_calk a LEFT JOIN (
                                            select kd_rek, kd_skpd, nilai 
                                            from nilai_lo_calk 
                                            ) b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('71042')    
                        --------------

                        UNION ALL
                        SELECT 814 jns, d.kd_unit, $thn_ang thn, ISNULL(SUM(d.debet-d.kredit),0) nilai 
                        FROM $trhju c inner join $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE (LEFT(d.kd_rek6,4)='1106' AND LEFT(d.kd_rek6,6)<>'110616') AND YEAR(c.tgl_voucher)<='$thn_ang'
                        GROUP BY d.kd_unit
                        UNION ALL
                        SELECT 8141 jns, d.kd_unit, $thn_ang_1 thn, ISNULL(SUM(d.debet-d.kredit),0)*-1 nilai 
                        FROM $trhju c INNER JOIN $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,6)='110801' AND YEAR(c.tgl_voucher)<='$thn_ang_1'
                        GROUP BY d.kd_unit
                        UNION ALL
                        SELECT 8141 jns, d.kd_unit, $thn_ang thn, isnull(sum(d.kredit-d.debet),0)*-1 nilai 
                        FROM $trhju c inner join $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,6)='110801' AND YEAR(c.tgl_voucher)<='$thn_ang'
                        GROUP BY d.kd_unit
                        UNION ALL
                        SELECT 8142 jns, d.kd_unit, $thn_ang_1 thn, ISNULL(SUM(d.debet-d.kredit),0)*-1 nilai 
                        FROM $trhju c INNER JOIN $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,6)='110616' AND YEAR(c.tgl_voucher)<='$thn_ang_1'
                        GROUP BY d.kd_unit
                        UNION ALL
                        SELECT 8142 jns, d.kd_unit, $thn_ang thn, ISNULL(SUM(d.debet-d.kredit),0) nilai 
                        FROM $trhju c inner join $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,6)='110616' AND YEAR(c.tgl_voucher)<='$thn_ang'
                        GROUP BY d.kd_unit

                        UNION ALL
                        SELECT 81404 jns, d.kd_unit, $thn_ang_1 thn, isnull(sum(d.debet-d.kredit),0)*-1 nilai 
                        FROM $trhju c INNER JOIN $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,4)='2105' AND YEAR(c.tgl_voucher)<='$thn_ang_1'
                        GROUP BY d.kd_unit
                        UNION ALL
                        SELECT 81404 jns, d.kd_unit, $thn_ang thn, isnull(sum(d.kredit-d.debet),0)*-1 nilai 
                        FROM $trhju c inner join $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,4)='2105' AND YEAR(c.tgl_voucher)<='$thn_ang'
                        GROUP BY d.kd_unit
                        ---------------piutang angsuran
                        UNION ALL
                        SELECT 81405 jns, d.kd_unit, $thn_ang_1 thn, isnull(sum(d.debet-d.kredit),0)*-1 nilai 
                        FROM $trhju c INNER JOIN $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,5)='11403' AND YEAR(c.tgl_voucher)<='$thn_ang_1'
                        GROUP BY d.kd_unit
                        UNION ALL
                        SELECT 81405 jns, d.kd_unit, $thn_ang thn, isnull(sum(d.debet-d.kredit),0) nilai 
                        FROM $trhju c inner join $trdju d 
                        ON c.kd_skpd=d.kd_unit and c.no_voucher=d.no_voucher 
                        WHERE LEFT(d.kd_rek6,5)='11403' AND YEAR(c.tgl_voucher)<='$thn_ang'
                        GROUP BY d.kd_unit
                        ---------------piutang angsuran
                        UNION ALL
                        SELECT 414 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE LEFT(b.kd_rek6,4)='4104' AND YEAR(a.tgl_voucher)=$thn_ang
                        GROUP BY b.kd_unit 
                    ) p GROUP BY kd_unit 
                ) y ON x.kd_skpd=y.kd_unit 
                WHERE (kd_skpd!='1.01.2.22.0.00.01.0003' or kd_skpd='3.25.0.00.0.00.01.0003') and( lra_lain<>0 OR piutang_lain<>0 OR piutang_lain_lalu<>0 OR piutang_blud<>0 OR piutang_blud_lalu<>0 
                OR pdpt_dimuka<>0 OR pdpt_dimuka_lalu<>0  
                OR piutang_angsuran<>0 OR piutang_angsuran_lalu<>0 )
                ORDER BY kd_skpd");
        }elseif ($jenis=="4") {
            $judul = "BEBAN PEGAWAI";
            $head = "
                    <tr>
                        <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\">KODE SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"35%\" align=\"center\">NAMA SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Beban Pegawai - LRA $thn_ang</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Pegawai Penambah Kapitalisasi Aset $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Utang Pegawai $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Utang Pegawai $thn_ang_1</td> 
                        <td bgcolor=\"#CCCCCC\" width=\"35%\" align=\"center\">Belanja Modal yang Tidak Diakui sebagai Aset Tetap 2019</td>                       
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Beban Pegawai - LO $thn_ang</td>  
                                              
                    </tr>";
            $query = DB::select("SELECT x.kd_skpd, x.nm_skpd, ISNULL(y.lra_peg,0) lra_peg,
                             ISNULL(y.peg_kapit,0) peg_kapit, 
                             ISNULL(y.utang_peg,0) utang_peg, 
                             ISNULL(y.utang_peg_lalu,0) utang_peg_lalu,
                             ISNULL(y.blj_mdl_x,0) blj_mdl_x 
                FROM (SELECT kd_skpd, nm_skpd FROM ms_skpd) x
                LEFT JOIN
                (
                    SELECT kd_unit,
                                SUM(CASE WHEN jns=511 AND thn=$thn_ang THEN nilai ELSE 0 END) AS lra_peg, 
                                SUM(CASE WHEN jns=9111 AND thn=$thn_ang THEN nilai ELSE 0 END) AS peg_kapit, 
                                SUM(CASE WHEN jns=911 AND thn=$thn_ang THEN nilai ELSE 0 END) AS utang_peg,
                                SUM(CASE WHEN jns=911 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS utang_peg_lalu,
                                SUM(CASE WHEN jns=9112 AND thn=$thn_ang  THEN nilai ELSE 0 END) AS blj_mdl_x
                    FROM 
                    (
                        SELECT 9111 jns, a.kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai_trans),0)*-1 nilai
                        FROM trkapitalisasi a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 
                        WHERE jenis<>'X' AND nilai_trans<>0 AND LEFT(b.map_lo,4)='8101'
                        and LEFT(a.kd_rek6,4)='5101'
                        GROUP BY a.kd_skpd
                        UNION ALL
                        SELECT 911 jns, b.kd_unit, $thn_ang_1 thn, ISNULL(SUM(kredit-debet),0)*-1 nilai
                        FROM $trhju a INNER JOIN $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE LEFT(b.kd_rek6,6) IN ('210601') AND YEAR(tgl_voucher)<='$thn_ang_1'
                        GROUP BY b.kd_unit
                        UNION ALL
                        SELECT 911 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai 
                        FROM $trhju a INNER JOIN $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE LEFT(b.kd_rek6,6) IN ('210601') AND YEAR(tgl_voucher)<='$thn_ang'
                        GROUP BY b.kd_unit
                        UNION ALL
                        SELECT 511 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(b.debet-b.kredit),0) nilai 
                        FROM $trhju a INNER JOIN $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE LEFT(b.kd_rek6,4) IN ('5101') AND YEAR(tgl_voucher)='$thn_ang' 
                        GROUP BY b.kd_unit
                        UNION ALL
                        select 9112 jns, b.kd_skpd as kd_unit  ,$thn_ang thn, ISNULL(SUM(b.nilai),0) nilai from ket_beban_calk a  
                        LEFT JOIN
                        (select kd_skpd,kd_rek, nm_rek, nilai from nilai_beban_calk ) b
                        on a.kd_rek=b.kd_rek
                        where LEFT(a.kd_rek,5) in ('81014')GROUP BY kd_skpd 
                    ) p GROUP BY kd_unit 
                ) y ON x.kd_skpd=y.kd_unit 
                WHERE lra_peg<>0 OR peg_kapit<>0 OR utang_peg<>0 OR utang_peg_lalu<>0 
                ORDER BY kd_skpd");
        }elseif ($jenis=="5") {
            $judul = "BEBAN BARANG JASA";
            $head = "
                    <tr>
                        <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\">KODE SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"35%\" align=\"center\">NAMA SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Beban Jasa - LRA $thn_ang</td>                  
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Beban Jasa dibayar dimuka $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Beban Jasa dibayar dimuka $thn_ang_1</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Utang Barang dan Jasa $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Utang Barang dan Jasa $thn_ang_1</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Belanja Modal yang tidak diakui sebagai aset tetap</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Belanja Barang dan Jasa sebagai penambah kapitalisasi aset</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Persediaan Blud Tahun $thn_ang_1</td> 
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Persediaan Blud Tahun $thn_ang</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Beban Jasa BTT</td> 
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Belanja Barang Jadi Jasa</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Koreksi Utang $thn_ang_1</td>                           
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Koreksi Utang $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Beban Barang dan Jasa - LO</td>                        
                    </tr>";
            $query = DB::select("SELECT x.kd_skpd, x.nm_skpd, ISNULL(y.brgjsa_lra,0) brgjsa_lra,
                                             ISNULL(y.beban_dimuka,0) beban_dimuka,
                                             ISNULL(y.beban_dimuka_lalu,0) beban_dimuka_lalu, 
                                             ISNULL(y.utang_brg_jasa,0) utang_brg_jasa, 
                                             ISNULL(y.utang_brg_jasa_lalu,0) utang_brg_jasa_lalu,
                                             ISNULL(y.bm_tdk_aset_ttp,0) bm_tdk_aset_ttp,
                                             ISNULL(y.brg_jsa_tmbh_kapit,0) brg_jsa_tmbh_kapit,
                                             ISNULL(y.pers_blud_l,0) pers_blud_l,
                                              ISNULL(y.pers_blud_n,0) pers_blud_n,
                                             ISNULL(y.beban_jasa_btt,0) beban_jasa_btt,
                                             ISNULL(y.beljas_per,0) beljas_per,
                                             ISNULL(y.kor_21,0) kor_21,
                                             ISNULL(y.kor_22,0) kor_22
                FROM (SELECT kd_skpd, nm_skpd FROM ms_skpd) x
                LEFT JOIN
                (
                    SELECT kd_unit, 
                            SUM(CASE WHEN jns=512 AND thn=$thn_ang THEN nilai ELSE 0 END) AS brgjsa_lra,
                            SUM(CASE WHEN jns=912 AND thn=$thn_ang THEN nilai ELSE 0 END) AS beban_dimuka,
                            SUM(CASE WHEN jns=912 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS beban_dimuka_lalu,
                            SUM(CASE WHEN jns=9122 AND thn=$thn_ang THEN nilai ELSE 0 END) AS utang_brg_jasa,
                            SUM(CASE WHEN jns=9122 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS utang_brg_jasa_lalu,
                            SUM(CASE WHEN jns=9123 AND thn=$thn_ang THEN nilai ELSE 0 END) AS bm_tdk_aset_ttp,
                            SUM(CASE WHEN jns=9124 AND thn=$thn_ang THEN nilai ELSE 0 END) AS brg_jsa_tmbh_kapit,
                            SUM(CASE WHEN jns=9125 AND thn=$thn_ang THEN nilai ELSE 0 END) AS pers_blud_l ,
                            SUM(CASE WHEN jns=9126 AND thn=$thn_ang THEN nilai ELSE 0 END) AS pers_blud_n ,  
                            SUM(CASE WHEN jns=9127 AND thn=$thn_ang THEN nilai ELSE 0 END) AS beban_jasa_btt,  
                            SUM(CASE WHEN jns=9128 AND thn=$thn_ang THEN nilai ELSE 0 END) AS beljas_per ,  
                            SUM(CASE WHEN jns=9129 AND thn=$thn_ang THEN nilai ELSE 0 END) AS kor_21 ,  
                            SUM(CASE WHEN jns=91210 AND thn=$thn_ang THEN nilai ELSE 0 END) AS kor_22    
                    FROM 
                    (
                        SELECT 512 jns, b.kd_unit, $thn_ang thn, SUM(b.debet-b.kredit) nilai
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE left(b.kd_rek6,6) IN 
                                ('510202','510288','510299')
                        AND YEAR(tgl_voucher)='$thn_ang'
                        GROUP BY b.kd_unit
                        UNION ALL
                        SELECT 912 jns, b.kd_unit, $thn_ang_1 thn, ISNULL(SUM(debet-kredit),0) nilai
                        FROM $trhju a INNER JOIN $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE LEFT(b.kd_rek6,4) IN ('1111') AND YEAR(tgl_voucher)<='$thn_ang_1'
                        GROUP BY b.kd_unit
                        UNION ALL
                        SELECT 912 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(debet-kredit),0)*-1 nilai 
                        FROM $trhju a INNER JOIN $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE LEFT(b.kd_rek6,4) IN ('1111') AND YEAR(tgl_voucher)<='$thn_ang'
                        GROUP BY b.kd_unit
                        UNION ALL

                        ----------kondisi-----
                        SELECT 9122 jns, b.kd_unit, $thn_ang_1 thn, ISNULL(SUM(kredit-debet),0)*-1 nilai
                        FROM $trhju a INNER JOIN $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE left(b.kd_rek6,8) IN ('21060201') AND YEAR(tgl_voucher)<='$thn_ang_1' and b.kd_unit not in ('1.04.2.10.0.00.01.0000','3.27.0.00.0.00.03.0000','1.02.0.00.0.00.02.0000','3.29.3.30.3.31.01.0000','1.03.1.04.0.00.01.0000','4.02.03.06','4.02.03.07','4.02.03.08','4.02.02.01','2.05.01.01','1.04.02.01')
                        --and b.kd_unit not in ('1.04.01.01','4.02.01.01','4.02.01.09')
                        GROUP BY b.kd_unit
                        UNION ALL                       
                        SELECT 9122 jns,b.kd_skpd kd_unit, $thn_ang_1 thn, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                                            select kd_rek, kd_skpd , nilai 
                                            from nilai_beban_calk 
                                            ) b on a.kd_rek=b.kd_rek where a.kd_rek in ('81025') and b.kd_skpd in ('1.04.2.10.0.00.01.0000','3.27.0.00.0.00.03.0000','1.02.0.00.0.00.02.0000','3.29.3.30.3.31.01.0000','1.03.1.04.0.00.01.0000','4.02.03.06','4.02.03.07','4.02.03.08','4.02.02.01','2.05.01.01','1.04.02.01')
                        -----------------
                        UNION ALL
                        SELECT 9122 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai 
                        FROM $trhju a INNER JOIN $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE left(b.kd_rek6,8) IN ('21060201') AND YEAR(tgl_voucher)<='$thn_ang' and b.kd_unit not in ('1.02.0.00.0.00.02.0000','3.31.3.30.0.00.01.0001','3.31.3.30.0.00.01.0000')
                        GROUP BY b.kd_unit
                        UNION ALL
                        SELECT 9122 jns,b.kd_skpd kd_unit, $thn_ang thn, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                                            select kd_rek, kd_skpd , nilai 
                                            from nilai_beban_calk 
                                            ) b on a.kd_rek=b.kd_rek where a.kd_rek in ('81026') and b.kd_skpd in ('1.02.0.00.0.00.02.0000','3.31.3.30.0.00.01.0001','3.31.3.30.0.00.01.0000')
                        -----------------
                        UNION ALL

                        SELECT 9123 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai
                        FROM nilai_beban_calk WHERE kd_rek IN ('81027')
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 9124 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai
                        FROM nilai_beban_calk WHERE kd_rek IN ('81028')
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 9125 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai
                        FROM nilai_beban_calk WHERE kd_rek IN ('81029')
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 9126 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai
                        FROM nilai_beban_calk WHERE kd_rek IN ('810291')
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 9127 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai
                        FROM nilai_beban_calk WHERE kd_rek IN ('810292') 
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 9128 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai
                        FROM nilai_beban_calk WHERE kd_rek IN ('81022') 
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 9129 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai
                        FROM nilai_beban_calk WHERE kd_rek IN ('810293') 
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 91210 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai
                        FROM nilai_beban_calk WHERE kd_rek IN ('810294') 
                        GROUP BY kd_skpd




                        ) p GROUP BY kd_unit 
                        ) y ON x.kd_skpd=y.kd_unit 
                WHERE brgjsa_lra<>0 OR beban_dimuka<>0 OR beban_dimuka_lalu<>0 OR utang_brg_jasa<>0 OR utang_brg_jasa_lalu<>0 OR bm_tdk_aset_ttp<>0 OR brg_jsa_tmbh_kapit<>0 OR pers_blud_l<>0  OR pers_blud_n<>0 OR beban_jasa_btt<>0 
                ORDER BY kd_skpd");
        }elseif ($jenis=="6") {
            
            $judul = "BEBAN PERSEDIAAN";
            $head = "
                    <tr>
                        <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\">KODE SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"35%\" align=\"center\">NAMA SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Beban Persediaan - LRA $thn_ang</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Persediaan $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Persediaan $thn_ang_1</td>                                              
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Belanja Modal yang tidak diakui sebagai aset tetap</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Belanja Persediaan yag menjadi Aset</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Utang Persediaan (belanja yang diserahkan) $thn_ang</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Utang Persediaan (belanja yang diserahkan) $thn_ang_1</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Hibah dari Pihak Ketiga/Lainya</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Persediaan dari BTT</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Extracomptable</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Belanja Persediaan Menjadi Extracomptable</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Beban Barang BTT</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Koreksi Persediaan</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Belanja Modal Yang Jadi Persediaan</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Belanja Hibah Persediaan</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Belanja Jasa Jadi Persediaan </td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Beban Persediaan - LO $thn_ang</td>
                    </tr>";
            $query = DB::select("SELECT x.kd_skpd, x.nm_skpd, ISNULL(y.persediaan_lra,0) persediaan_lra,
                                             ISNULL(y.persediaan,0) persediaan,
                                             ISNULL(y.persediaan_lalu,0) persediaan_lalu,
                                             ISNULL(y.bm_tdk_aset_ttp,0) bm_tdk_aset_ttp,
                                             ISNULL(y.persediaan_aset,0) persediaan_aset,
                                             ISNULL(y.utang_persediaan,0) utang_persediaan,
                                             ISNULL(y.utang_persediaan_lalu,0) utang_persediaan_lalu,
                                             ISNULL(y.hibah_pihak3,0) hibah_pihak3,
                                             ISNULL(y.persediaan_btt,0) persediaan_btt,
                                             ISNULL(y.excomp,0) excomp,
                                             ISNULL(y.bel_per_eks,0) bel_per_eks,
                                             ISNULL(y.bb_btt,0) bb_btt,
                                             ISNULL(y.kor_per,0) kor_per,
                                             ISNULL(y.belmod_per,0) belmod_per,
                                             ISNULL(y.belhiper,0) belhiper,
                                             ISNULL(y.beljas_per,0) beljas_per
                FROM (SELECT kd_skpd, nm_skpd FROM ms_skpd) x
                LEFT JOIN
                (
                    SELECT kd_unit, 
                                SUM(CASE WHEN jns=512 AND thn=$thn_ang THEN nilai ELSE 0 END) AS persediaan_lra,
                                SUM(CASE WHEN jns=912 AND thn=$thn_ang THEN nilai ELSE 0 END) AS persediaan,
                                SUM(CASE WHEN jns=912 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS persediaan_lalu,
                                SUM(CASE WHEN jns=9122 AND thn=$thn_ang THEN nilai ELSE 0 END) AS bm_tdk_aset_ttp,
                                SUM(CASE WHEN jns=9123 AND thn=$thn_ang THEN nilai ELSE 0 END) AS persediaan_aset,
                                SUM(CASE WHEN jns=9124 AND thn=$thn_ang THEN nilai ELSE 0 END) AS utang_persediaan,
                                SUM(CASE WHEN jns=9124 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS utang_persediaan_lalu,
                                SUM(CASE WHEN jns=9129 AND thn=$thn_ang THEN nilai ELSE 0 END) AS hibah_pihak3,
                                SUM(CASE WHEN jns=91291 AND thn=$thn_ang THEN nilai ELSE 0 END) AS persediaan_btt,
                                SUM(CASE WHEN jns=91292 AND thn=$thn_ang THEN nilai ELSE 0 END) AS excomp,
                                SUM(CASE WHEN jns=91293 AND thn=$thn_ang THEN nilai ELSE 0 END) AS bel_per_eks,
                                SUM(CASE WHEN jns=91294 AND thn=$thn_ang THEN nilai ELSE 0 END) AS bb_btt,
                                SUM(CASE WHEN jns=91295 AND thn=$thn_ang THEN nilai ELSE 0 END) AS kor_per,
                                SUM(CASE WHEN jns=91296 AND thn=$thn_ang THEN nilai ELSE 0 END) AS belmod_per,
                                SUM(CASE WHEN jns=91297 AND thn=$thn_ang THEN nilai ELSE 0 END) AS belhiper,
                                SUM(CASE WHEN jns=91298 AND thn=$thn_ang THEN nilai ELSE 0 END) AS beljas_per
                    FROM 
                    (
                        -------LRA
                        SELECT 512 jns, b.kd_unit, $thn_ang thn, SUM(b.debet-b.kredit) nilai
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE  left(b.kd_rek6,6) IN 
                                -- ($kd_rek51)
                                ('510201')  
                        AND YEAR(tgl_voucher)='$thn_ang'
                        GROUP BY b.kd_unit
                        UNION ALL
                        ------------------


                        -------kondisi di calk
                        SELECT 912 jns, b.kd_unit, $thn_ang_1 thn, ISNULL(SUM(debet-kredit),0) nilai
                        FROM $trhju a INNER JOIN $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE LEFT(b.kd_rek6,6) IN ('111201','111202','111203') AND YEAR(tgl_voucher)<='$thn_ang_1' and b.kd_unit not in ('1.04.2.10.0.00.01.0000','1.02.0.00.0.00.02.0000','1.02.0.00.0.00.03.0000','1.02.0.00.0.00.01.0000','3.29.3.30.3.31.01.0000','2.09.0.00.0.00.01.0000','2.09.3.27.0.00.01.0000','3.27.0.00.0.00.03.0003','2.09.3.27.0.00.01.0002','1.03.1.04.0.00.01.0000','3.27.0.00.0.00.04.0000','3.27.0.00.0.00.03.0003','3.27.0.00.0.00.01.0004')
                        GROUP BY b.kd_unit

                        UNION ALL
                        SELECT 912 jns,  b.kd_skpd kd_unit, $thn_ang_1 thn, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                                            select kd_rek, kd_skpd, nilai 
                                            from nilai_beban_calk 
                                            ) b on a.kd_rek=b.kd_rek where a.kd_rek in ('81032') and b.kd_skpd in ('1.04.2.10.0.00.01.0000','1.02.0.00.0.00.02.0000','1.02.0.00.0.00.03.0000','1.02.0.00.0.00.01.0000','3.29.3.30.3.31.01.0000','2.09.0.00.0.00.01.0000','2.09.3.27.0.00.01.0000','3.27.0.00.0.00.03.0003','2.09.3.27.0.00.01.0002','1.03.1.04.0.00.01.0000','3.27.0.00.0.00.04.0000','3.27.0.00.0.00.03.0003','3.27.0.00.0.00.01.0004')
                        ---------------------
                        UNION ALL
                        SELECT 912 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(debet-kredit),0)*-1 nilai 
                        FROM $trhju a INNER JOIN $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE LEFT(b.kd_rek6,6) IN ('111201','111202','111203')  AND YEAR(tgl_voucher)<='$thn_ang' and b.kd_unit not in ('3.29.0.00.0.00.01.0000','1.03.2.10.0.00.01.0000','2.07.3.32.0.00.01.0004','2.07.3.32.0.00.01.0000','1.02.0.00.0.00.01.0000','1.02.0.00.0.00.02.0000','1.02.0.00.0.00.03.0000','3.27.0.00.0.00.03.0003','2.09.3.27.0.00.01.0000','3.27.0.00.0.00.01.0004','2.09.3.27.0.00.01.0002','1.06.0.00.0.00.01.0001','1.06.0.00.0.00.01.0002','3.31.3.30.0.00.01.0001','3.27.0.00.0.00.03.0001')
                                        
                        GROUP BY b.kd_unit
                        UNION ALL
                        SELECT 912 jns,  b.kd_skpd kd_unit, $thn_ang thn, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                                            select kd_rek, kd_skpd, nilai 
                                            from nilai_beban_calk 
                                            ) b on a.kd_rek=b.kd_rek where a.kd_rek in ('81031') and b.kd_skpd in ('3.29.0.00.0.00.01.0000','1.03.2.10.0.00.01.0000','2.07.3.32.0.00.01.0004','2.07.3.32.0.00.01.0000','1.02.0.00.0.00.01.0000','1.02.0.00.0.00.02.0000','1.02.0.00.0.00.03.0000','3.27.0.00.0.00.03.0003','2.09.3.27.0.00.01.0000','3.27.0.00.0.00.01.0004','2.09.3.27.0.00.01.0002','1.06.0.00.0.00.01.0001','1.06.0.00.0.00.01.0002','3.31.3.30.0.00.01.0001','3.27.0.00.0.00.03.0001')
                                        

                        UNION ALL
                        SELECT 9122 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai
                        FROM nilai_beban_calk WHERE kd_rek='81035'
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 9123 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai
                        FROM nilai_beban_calk WHERE kd_rek='81036'
                        GROUP BY kd_skpd


                        UNION ALL
                        SELECT 9124 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(debet-kredit),0)*-1 nilai 
                        FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                        WHERE YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,12) IN ($kd_rek21,'2150210')
                        GROUP BY b.kd_unit
                        UNION ALL
                        SELECT 9124 jns, kd_unit, $thn_ang_1 thn, ISNULL(SUM(nilai),0) nilai FROM (
                        SELECT b.kd_unit, ISNULL(SUM(debet-kredit),0) nilai 
                        FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                        WHERE YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,12) IN ($kd_rek21,'2150210') and b.kd_unit not in ('1.04.2.10.0.00.01.0000','1.02.0.00.0.00.02.0000','1.03.1.04.0.00.01.0000')
                        GROUP BY b.kd_unit
                        union all 
                        SELECT kd_skpd kd_unit, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                                            select kd_rek, kd_skpd, nilai 
                                            from nilai_beban_calk  
                                            ) b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81038')  and b.kd_skpd in ('1.04.2.10.0.00.01.0000','1.02.0.00.0.00.02.0000','1.03.1.04.0.00.01.0000')
                        ) z
                        GROUP BY kd_unit
                        UNION ALL
                        SELECT 9129 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai 
                        FROM nilai_beban_calk WHERE kd_rek IN ('810315')    
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 91291 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai 
                        FROM nilai_beban_calk WHERE kd_rek IN ('810316')    
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 91292 jns,  b.kd_skpd kd_unit, $thn_ang thn, ISNULL(b.nilai,0) nilai from ket_beban_calk a LEFT JOIN (
                                            select kd_rek, kd_skpd, nilai 
                                            from nilai_beban_calk 
                                            ) b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('810317')
                        ---------------------
                        UNION ALL
                        SELECT 91292 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(debet),0) nilai 
                        FROM trhju_pkd a INNER JOIN trdju_pkd b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE  YEAR(a.tgl_voucher)='$thn_ang' AND tgl_real='20'
                        -- LEFT(b.kd_rek6,12) IN ('810201010042')  AND YEAR(tgl_voucher)='$thn_ang'
                        GROUP BY b.kd_unit
                        UNION ALL
                        SELECT 91293 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai 
                        FROM nilai_beban_calk WHERE kd_rek IN ('810318')    
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 91294 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai 
                        FROM nilai_beban_calk WHERE kd_rek IN ('810319')    
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 91295 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai 
                        FROM nilai_beban_calk WHERE kd_rek IN ('8103020')   
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 91296 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai 
                        FROM nilai_beban_calk WHERE kd_rek IN ('8103030')   
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 91297 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai 
                        FROM nilai_beban_calk WHERE kd_rek IN ('8103040')   
                        GROUP BY kd_skpd

                        UNION ALL
                        SELECT 91298 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai 
                        FROM nilai_beban_calk WHERE kd_rek IN ('8103050')   
                        GROUP BY kd_skpd


                    ) p GROUP BY kd_unit 
                ) y ON x.kd_skpd=y.kd_unit 
                WHERE persediaan_lra<>0 OR persediaan<>0 OR persediaan_lalu<>0 OR bm_tdk_aset_ttp<>0 OR persediaan_aset<>0 OR utang_persediaan<>0 OR utang_persediaan_lalu<>0 OR hibah_pihak3<>0 OR persediaan_btt<>0 OR excomp<>0
                ORDER BY kd_skpd"); 
        }elseif ($jenis=="7") {
            $judul = "BEBAN PEMELIHARAAN";
            $head = "
                    <tr>
                        <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\">KODE SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"35%\" align=\"center\">NAMA SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Beban Pemeliharaan - LRA $thn_ang</td>
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Utang Pemeliharaan $thn_ang</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Utang Pemeliharaan $thn_ang_1</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Belanja Modal yang tidak masuk sebagai kapitalisasi aset</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Belanja Pemeliharaan yang menjadi aset</td> 
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Pemeliharaan BTT </td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Beban Pemeliharaan - LO $thn_ang</td>                        
                    </tr>";
            $query = DB::select("SELECT x.kd_skpd, x.nm_skpd, ISNULL(y.lra_pem,0) lra_pem,
                             ISNULL(y.utang_pem,0) utang_pem,
                             ISNULL(y.utang_pem_lalu,0) utang_pem_lalu,
                             ISNULL(y.bm_tdk_kapit,0) bm_tdk_kapit,
                             ISNULL(y.pem_aset,0) pem_aset,
                             ISNULL(y.pem_btt,0) pem_btt
                FROM (SELECT kd_skpd, nm_skpd FROM ms_skpd) x
                LEFT JOIN
                (
                    SELECT kd_unit,
                                SUM(CASE WHEN jns=512 AND thn=$thn_ang THEN nilai ELSE 0 END) AS lra_pem,
                                SUM(CASE WHEN jns=912 AND thn=$thn_ang THEN nilai ELSE 0 END) AS utang_pem,
                                SUM(CASE WHEN jns=912 AND thn=$thn_ang_1 THEN nilai ELSE 0 END) AS utang_pem_lalu,
                                SUM(CASE WHEN jns=9121 AND thn=$thn_ang THEN nilai ELSE 0 END) AS bm_tdk_kapit,
                                SUM(CASE WHEN jns=9122 AND thn=$thn_ang THEN nilai ELSE 0 END) AS pem_aset,
                                SUM(CASE WHEN jns=9123 AND thn=$thn_ang THEN nilai ELSE 0 END) AS pem_btt
                    FROM (
                        SELECT 512 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(b.debet-b.kredit),0) nilai 
                        FROM $trhju a INNER JOIN $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE left(b.kd_rek6,6) IN 
                        -- ($kd_rek53)
                        ('510203') 
                        AND YEAR(tgl_voucher)='$thn_ang'
                        GROUP BY b.kd_unit
                        UNION ALL
                        SELECT 912 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai 
                        FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                        WHERE YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,8) IN ('21060203')
                        GROUP BY b.kd_unit
                        UNION ALL
                        SELECT 912 jns, b.kd_unit, $thn_ang_1 thn, ISNULL(SUM(kredit-debet),0)*-1 nilai 
                        FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                        WHERE YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,8) IN ('21060203')
                        GROUP BY b.kd_unit
                        UNION ALL
                        SELECT 9121 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai 
                        FROM nilai_beban_calk WHERE kd_rek='81043'
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 9122 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai 
                        FROM nilai_beban_calk WHERE kd_rek='81044'
                        GROUP BY kd_skpd
                        UNION ALL
                        SELECT 9122 jns, kd_skpd kd_unit, $thn_ang thn, ISNULL(SUM(nilai),0) nilai 
                        FROM nilai_beban_calk WHERE kd_rek='81045'
                        GROUP BY kd_skpd
                    ) p GROUP BY kd_unit 
                ) y ON x.kd_skpd=y.kd_unit 
                WHERE lra_pem<>0 OR utang_pem<>0 OR utang_pem_lalu<>0 OR bm_tdk_kapit<>0 OR pem_aset<>0 OR pem_btt<>0 
                ORDER BY kd_skpd");
        }elseif ($jenis=="8") {
            $judul = "BEBAN PERJALANAN DINAS";
            $head = "
                    <tr>
                        <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\">KODE SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"35%\" align=\"center\">NAMA SKPD</td>
                        <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\">Beban Perjalanan Dinas - LRA $thn_ang</td>
                        <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\">Belanja Perjalanan dinas sebagai penambah kapitalisasi aset</td>
                        <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\">Belanja Modal yang tidak diakui sebagai Aset Tetap</td> 
                        <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\">Perjalanan Dinas BTT</td>                        
                        <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\">Beban Perjalanan Dinas - LO $thn_ang</td>
                    </tr>";
            $query = DB::select("SELECT x.kd_skpd, x.nm_skpd, ISNULL(y.lra_prj,0) lra_prj,
                             ISNULL(y.bel_tmb_kapit,0) bel_tmb_kapit,
                             ISNULL(y.bel_mdl_tdk_aset,0) bel_mdl_tdk_aset,
                             ISNULL(y.perjadin_btt,0) perjadin_btt
                FROM (SELECT kd_skpd, nm_skpd FROM ms_skpd) x
                LEFT JOIN
                (
                    SELECT kd_unit,
                                SUM(CASE WHEN jns=512 AND thn=$thn_ang THEN nilai ELSE 0 END) AS lra_prj,
                                SUM(CASE WHEN jns=9121 AND thn=$thn_ang THEN nilai ELSE 0 END) AS bel_tmb_kapit,
                                SUM(CASE WHEN jns=9122 AND thn=$thn_ang THEN nilai ELSE 0 END) AS bel_mdl_tdk_aset,
                                SUM(CASE WHEN jns=9123 AND thn=$thn_ang THEN nilai ELSE 0 END) AS perjadin_btt
                    FROM 
                    (
                        SELECT 512 jns, b.kd_unit, $thn_ang thn, ISNULL(SUM(b.debet-b.kredit),0) nilai 
                        FROM $trhju a INNER JOIN $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        WHERE left(b.kd_rek6,6) IN ('510204') 
                        AND YEAR(tgl_voucher)='$thn_ang'
                        GROUP BY b.kd_unit
                        UNION ALL
                        select 9121 jns, b.kd_skpd as kd_unit  ,$thn_ang thn, ISNULL(SUM(b.nilai),0) nilai from ket_beban_calk a  
                        LEFT JOIN
                        (select kd_skpd,kd_rek, nm_rek, nilai from nilai_beban_calk ) b
                        on a.kd_rek=b.kd_rek
                        where LEFT(a.kd_rek,5) in ('81051')GROUP BY kd_skpd 
                        UNION ALL
                        select 9122 jns, b.kd_skpd as kd_unit  ,$thn_ang thn, ISNULL(SUM(b.nilai),0) nilai from ket_beban_calk a  
                        LEFT JOIN
                        (select kd_skpd,kd_rek, nm_rek, nilai from nilai_beban_calk ) b
                        on a.kd_rek=b.kd_rek
                        where LEFT(a.kd_rek,5) in ('81052')GROUP BY kd_skpd 
                        UNION ALL
                        select 9123 jns, b.kd_skpd as kd_unit  ,$thn_ang thn, ISNULL(SUM(b.nilai),0) nilai from ket_beban_calk a  
                        LEFT JOIN
                        (select kd_skpd,kd_rek, nm_rek, nilai from nilai_beban_calk ) b
                        on a.kd_rek=b.kd_rek
                        where LEFT(a.kd_rek,5) in ('81053')GROUP BY kd_skpd 
                        ) p GROUP BY kd_unit 
                        ) y ON x.kd_skpd=y.kd_unit 
                WHERE lra_prj<>0 OR bel_tmb_kapit<>0 
                ORDER BY kd_skpd");
        }




        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'judul'          => $judul,
        'head'          => $head,
        'jenis'          => $jenis,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.lap_calk_lo_beban.lap_calk_lo_beban')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Penjelasan_Pendapatan.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Penjelasan_Pendapatan.xls"');
            return $view;
        }
    }

    public function cetak_lap_calk_aset(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $jenis   = $request->jenis;
        $rek   = $request->rek;
        $cetak   = $request->cetak;
        $spasi   = 'line-height:1.5em;';
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        if ($jenis=="2") {
            $nm_jenis = "MUTASI BERTAMBAH";
        }else{
            $nm_jenis = "MUTASI BERKURANG";
        }

        if ($rek=="1301") {
            $rekmut = "131".$jenis;
        }elseif($rek == "1302"){
            $rekmut = "132".$jenis;
        }elseif($rek == "1303"){
            $rekmut = "133".$jenis;
        }elseif($rek == "1304"){
            $rekmut = "134".$jenis;
        }elseif($rek == "1305"){
            $rekmut = "135".$jenis;
        }elseif($rek == "1306"){
            $rekmut = "136".$jenis;
        }
        
        $query = DB::select("SELECT kd_skpd, (select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd, kd_rek,(select nm_rek from ket_neraca_calk where a.kd_rek=kd_rek) nm_rek, ket penjelasan, nilai
            FROM isi_neraca_calk a
            WHERE left(kd_rek,4)='$rekmut' and (ket is not null and nilai is not null)
            order by kd_skpd,kd_rek");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,        
        'rek'          => $rek,
        'jenis'          => $jenis,
        'nm_jenis'          => $nm_jenis,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.lap_calk_aset.lap_calk_aset')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LAP_CALK_ASET.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LAP_CALK_ASET.xls"');
            return $view;
        }
    }

    public function cetak_lap_calk_penyajian_data(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $jenis   = $request->jenis;
        $rek3   = $request->rek;
        $rek    = str_replace("0","",$rek3);
        $cetak   = $request->cetak;
        $spasi   = 'line-height:1.5em;';
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        // dd($rek);

        IF($rek=='131'){ 
            $judul="TANAH";    
            $rek_aset="1301";            
            $rek_bel="LEFT(b.kd_rek6,4)in('5201')"; 
            $rek_lamp="kd_rek3='$rek_aset'";
        }ELSE IF($rek=='132'){ 
            $judul="PERALATAN DAN MESIN"; 
            $rek_aset="1302"; 
            $rek_bel="LEFT(b.kd_rek6,4)in('5202')"; 
            $rek_lamp="kd_rek3='$rek_aset'";
        }ELSE IF($rek=='133'){ 
            $judul="GEDUNG DAN BANGUNAN"; 
            $rek_aset="1303"; 
            $rek_bel="LEFT(b.kd_rek6,4)in('5203')"; 
            $rek_lamp="kd_rek3='$rek_aset'";
        }ELSE IF($rek=='134'){ 
            $judul="JALAN, IRIGASI DAN JARINGAN"; 
            $rek_aset="1304"; 
            $rek_bel="LEFT(b.kd_rek6,4)in('5204')"; 
            $rek_lamp="kd_rek3='$rek_aset'";
        }ELSE IF($rek=='135'){ 
            $judul="ASET TETAP LAINNYA";  
            $rek_aset="1305";   
            $rek_bel="LEFT(b.kd_rek6,6)in('520501','520502','520503','520504','520505','520506','520507','520588','520599')"; 
            $rek_lamp="kd_rek3='$rek_aset'";
        }ELSE{ 
            $judul="ASET LAINNYA";   
            $rek_aset="1503";      
            $rek_bel="(LEFT(b.kd_rek6,4)in('5206') or LEFT(b.kd_rek6,6)in('520508'))"; 
            $rek_lamp="LEFT(kd_rek6,2)='15'";
        }
        
        $query = DB::select("SELECT *, tot_belanja_modal-tot_neraca selisih, 0 nilai, '' ket, '' kd_rinci, 1 jns FROM (
                SELECT x.kd_skpd, x.nm_skpd, ISNULL(y.tot_belanja_modal,0) tot_belanja_modal, ISNULL(z.tot_neraca,0) tot_neraca FROM ms_skpd x
                LEFT JOIN
                (
                SELECT b.kd_unit, ISNULL(SUM(debet-kredit),0) tot_belanja_modal 
                FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher 
                WHERE YEAR(a.tgl_voucher)=$thn_ang AND $rek_bel
                GROUP BY b.kd_unit) y ON x.kd_skpd=y.kd_unit
                LEFT JOIN
                (SELECT kd_skpd, ISNULL(SUM(tahun_n),0)+ISNULL(SUM(nilai),0) as tot_neraca from trdkapitalisasi 
                WHERE $rek_lamp GROUP BY kd_skpd) z
                ON x.kd_skpd=z.kd_skpd) t
                WHERE tot_belanja_modal<>0 OR tot_neraca<>0
                UNION ALL
                SELECT kd_skpd, '' nm_skpd, 0 tot_belanja_modal, 0 tot_neraca, 0 selisih, nilai, ket, kd_rinci, 2 jns FROM isi_analisis_calk WHERE kd_rek='$rek'
                ORDER BY kd_skpd, kd_rinci");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,        
        'rek'          => $rek,
        'judul'          => $judul,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.lap_calk_penyajian_data.lap_calk_penyajian_data')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LAP_CALK_PENYAJIAN_DATA.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LAP_CALK_PENYAJIAN_DATA.xls"');
            return $view;
        }
    }

    public function cetak_lap_calk_kewajiban(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $jenis   = $request->jenis;
        $rek   = $request->rek;
        $cetak   = $request->cetak;
        $spasi   = 'line-height:1.5em;';
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        // dd($rek);

        IF($rek=='211'){ 
            $judul="Utang PPh Pusat"; 
            $rek2="LEFT(kd_rek6,6)='210105'";
        }ELSE IF($rek=='212'){ 
            $judul="Utang PPN Pusat"; 
            $rek2="LEFT(kd_rek6,6)='210106'";
        }ELSE IF($rek=='213'){ 
            $judul="Utang Perhitungan Pihak Ketiga Lainnya"; 
            $rek2="LEFT(kd_rek6,4)='21107'";
        }ELSE IF($rek=='221'){ 
            $judul="Pendapatan Diterima Dimuka lainnya"; 
            $rek2="LEFT(kd_rek6,4)='2105'";
        }ELSE IF($rek=='231'){ 
            $judul="Utang Belanja Pegawai"; 
            $rek2="LEFT(kd_rek6,6)='210601'";
        }ELSE IF($rek=='232'){ 
            $judul="Utang Belanja Barang dan Jasa"; 
            $rek2="LEFT(kd_rek6,6)='210602'";
        }ELSE { 
            $judul="Utang Belanja Modal"; 
            $rek2="LEFT(kd_rek6,6)='210614'";
        }
        
        $query = DB::select("SELECT *, (SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=x.kd_skpd) nm_skpd, $rek kd_rek3 FROM (
                    SELECT 1 jns, a.kd_unit kd_skpd, ISNULL(SUM(CASE WHEN YEAR(tgl_voucher)<=$thn_ang THEN kredit-debet ELSE 0 END),0) nilai, '' penjelasan, '' kd_rinci
                    FROM $trdju a INNER JOIN $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    WHERE $rek2
                    GROUP BY a.kd_unit
                    UNION ALL
                    SELECT 2 jns, kd_skpd, 0 nilai, ket penjelasan, kd_rinci FROM isi_neraca_calk WHERE kd_rek=$rek) x
                    WHERE nilai<>0 OR penjelasan NOT LIKE ''
                    ORDER BY kd_skpd, jns");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,        
        'rek'          => $rek,
        'judul'          => $judul,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.lap_calk_kewajiban.lap_calk_kewajiban')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LAP_CALK_kewajiban.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LAP_CALK_kewajiban.xls"');
            return $view;
        }
    }

    public function cetak_lap_calk_lpe_lain_lain(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $cetak   = $request->cetak;
        $spasi   = 'line-height:1.5em;';
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        
        $query = DB::select("SELECT x.kd_skpd, x.nm_skpd, ISNULL(y.peny_piutang,0) peny_piutang,
                             ISNULL(y.koreksi_peny,0) koreksi_peny, 
                             ISNULL(y.hibah_kel,0) hibah_kel, 
                             ISNULL(y.mutasi_masuk,0) mutasi_masuk,
                             ISNULL(y.mutasi_kel,0) mutasi_kel,
                             ISNULL(y.hapus_pers,0) hapus_pers,
                             ISNULL(y.ubah_kode,0) ubah_kode,
                             ISNULL(y.koreksi_tanah,0) koreksi_tanah,
                             ISNULL(y.koreksi_utang,0) koreksi_utang, 
                             ISNULL(y.reklas_akun,0) reklas_akun,
                             ISNULL(y.tagihan,0) tagihan,
                             ISNULL(y.peny_modal,0) peny_modal,
                             ISNULL(y.persediaan_apbn,0) persediaan_apbn,
                             ISNULL(y.aset_peralatan,0) aset_peralatan,
                             ISNULL(y.koreksi_dana,0) koreksi_dana,
                             ISNULL(y.koreksi_gedung,0) koreksi_gedung,
                             ISNULL(y.koreksi_persediaan,0) koreksi_persediaan,
                             ISNULL(y.koreksi_kas,0) koreksi_kas,
                             ISNULL(y.extracompatable,0) extracompatable,
                             ISNULL(y.koreksi_peralatan_mesin,0) koreksi_peralatan_mesin,
                             ISNULL(y.koreksi_jij,0) koreksi_jij,
                             ISNULL(y.koreksi_atl,0) koreksi_atl,
                             ISNULL(y.koreksi_piutang,0) koreksi_piutang,
                             ISNULL(y.koreksi_all,0) koreksi_all,
                             ISNULL(y.pelimpahan_masuk,0) pelimpahan_masuk, 
                             ISNULL(y.pelimpahan_keluar,0) pelimpahan_keluar  
                FROM (SELECT kd_skpd, nm_skpd FROM ms_skpd 
                        where kd_skpd<>'4.02.02.02') x
                LEFT JOIN
                (select * from (
                SELECT kd_unit,
                     SUM(CASE WHEN jns=331 AND thn=$thn_ang THEN nilai ELSE 0 END) AS peny_piutang, 
                     SUM(CASE WHEN jns=332 AND thn=$thn_ang THEN nilai ELSE 0 END) AS koreksi_peny, 
                     SUM(CASE WHEN jns=333 AND thn=$thn_ang THEN nilai ELSE 0 END) AS hibah_kel,
                     SUM(CASE WHEN jns=334 AND thn=$thn_ang THEN nilai ELSE 0 END) AS mutasi_masuk,
                     SUM(CASE WHEN jns=335 AND thn=$thn_ang THEN nilai ELSE 0 END) AS mutasi_kel,
                     SUM(CASE WHEN jns=336 AND thn=$thn_ang THEN nilai ELSE 0 END) AS hapus_pers,
                     SUM(CASE WHEN jns=337 AND thn=$thn_ang THEN nilai ELSE 0 END) AS ubah_kode,
                     SUM(CASE WHEN jns=338 AND thn=$thn_ang THEN nilai ELSE 0 END) AS koreksi_tanah,
                     SUM(CASE WHEN jns=339 AND thn=$thn_ang THEN nilai ELSE 0 END) AS koreksi_utang,
                     SUM(CASE WHEN jns=3310 AND thn=$thn_ang THEN nilai ELSE 0 END) AS reklas_akun,
                     SUM(CASE WHEN jns=3311 AND thn=$thn_ang THEN nilai ELSE 0 END) AS tagihan,
                     SUM(CASE WHEN jns=3312 AND thn=$thn_ang THEN nilai ELSE 0 END) AS peny_modal,
                     SUM(CASE WHEN jns=3313 AND thn=$thn_ang THEN nilai ELSE 0 END) AS persediaan_apbn,
                     SUM(CASE WHEN jns=3314 AND thn=$thn_ang THEN nilai ELSE 0 END) AS aset_peralatan,
                     SUM(CASE WHEN jns=3315 AND thn=$thn_ang THEN nilai ELSE 0 END) AS koreksi_dana,
                     SUM(CASE WHEN jns=3316 AND thn=$thn_ang THEN nilai ELSE 0 END) AS koreksi_gedung,
                     SUM(CASE WHEN jns=3317 AND thn=$thn_ang THEN nilai ELSE 0 END) AS koreksi_persediaan, 
                     SUM(CASE WHEN jns=3318 AND thn=$thn_ang THEN nilai ELSE 0 END) AS koreksi_kas,
                     SUM(CASE WHEN jns=3319 AND thn=$thn_ang THEN nilai ELSE 0 END) AS extracompatable, 
                     SUM(CASE WHEN jns=3320 AND thn=$thn_ang THEN nilai ELSE 0 END) AS koreksi_peralatan_mesin, 
                     SUM(CASE WHEN jns=3321 AND thn=$thn_ang THEN nilai ELSE 0 END) AS koreksi_jij,
                     SUM(CASE WHEN jns=3322 AND thn=$thn_ang THEN nilai ELSE 0 END) AS koreksi_atl,
                     SUM(CASE WHEN jns=3323 AND thn=$thn_ang THEN nilai ELSE 0 END) AS koreksi_piutang,
                     SUM(CASE WHEN jns=3324 AND thn=$thn_ang THEN nilai ELSE 0 END) AS koreksi_all,
                     SUM(CASE WHEN jns=3325 AND thn=$thn_ang THEN nilai ELSE 0 END) AS pelimpahan_masuk, 
                     SUM(CASE WHEN jns=3326 AND thn=$thn_ang THEN nilai ELSE 0 END) AS pelimpahan_keluar 
                FROM (
                SELECT 331 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='1' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 332 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='2' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 333 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='3' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 334 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='4' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 335 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='5' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 336 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='6' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 337 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='7' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 338 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='8' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 339 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='9' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3310 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='10' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3311 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='11' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3312 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='12' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3313 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='13' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3314 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='14' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3315 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='15' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3316 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='16' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3317 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='17' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3318 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='18' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3319 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real in ('19','20') AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3320 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='23' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3321 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='24' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3322 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='26' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3323 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='27' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3324 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='28' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3325 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='30' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                UNION ALL
                SELECT 3326 jns, a.kd_unit, $thn_ang thn, ISNULL(SUM(kredit-debet),0) nilai
                FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                WHERE kd_rek6='310101010001' AND reev='3' AND tgl_real='31' AND YEAR(tgl_voucher)='$thn_ang'
                GROUP BY a.kd_unit
                
                 ) p GROUP BY kd_unit ) a 
                 WHERE peny_piutang<>0 OR koreksi_peny<>0 OR hibah_kel<>0 OR mutasi_masuk<>0 OR mutasi_kel<>0 OR hapus_pers<>0 OR ubah_kode<>0 OR koreksi_tanah<>0 OR koreksi_utang<>0 OR reklas_akun<>0 OR tagihan<>0 OR peny_modal<>0 OR persediaan_apbn<>0 OR aset_peralatan<>0 OR koreksi_dana<>0 OR koreksi_gedung<>0 OR koreksi_persediaan<>0 OR koreksi_kas<>0 OR extracompatable<>0 OR koreksi_peralatan_mesin<>0 OR Koreksi_jij<>0 OR koreksi_atl<>0 OR koreksi_piutang<>0 OR koreksi_all<>0 OR pelimpahan_masuk<>0 OR pelimpahan_keluar<>0
                 ) y ON x.kd_skpd=y.kd_unit 
                ORDER BY kd_skpd");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.lap_calk_lpe_lain_lain.lap_calk_lpe_lain_lain')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LAP_CALK_lpe_lain_lain.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LAP_CALK_lpe_lain_lain.xls"');
            return $view;
        }
    }

    public function cetak_penjelasan_calk(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $format   = $request->format;
        $rek   = $request->rek;
        $cetak   = $request->cetak;
        $spasi   = 'line-height:1.5em;';
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";

        if ($rek=="1301") {
            $rekbel = "left(a.kd_rek6,4)='5201'";
        }elseif ($rek=="1302") {
            $rekbel = "left(a.kd_rek6,4)='5202'";
        }elseif ($rek=="1303") {
            $rekbel = "left(a.kd_rek6,4)='5203'";
        }elseif ($rek=="1304") {
            $rekbel = "left(a.kd_rek6,4)='5204'";
        }elseif ($rek=="1305") {
            $rekbel = "left(a.kd_rek6,6)in('520501','520502','520503','520504','520505','520506','520507','520588','520599','520508') ";
        }elseif ($rek=="1306") {
            $rekbel = "left(a.kd_rek6,6)in('') ";
        }

        $rekmut = str_replace("0", "", $rek);

        $rekmut22 = $rekmut."22";
        $rekmut23 = $rekmut."23";
        $rekmut24 = $rekmut."24";
        $rekmut25 = $rekmut."25";
        $rekmut26 = $rekmut."26";
        $rekmut27 = $rekmut."27";
        $rekmut28 = $rekmut."28";
        $rekmut31 = $rekmut."31";
        $rekmut32 = $rekmut."32";
        $rekmut33 = $rekmut."33";
        $rekmut34 = $rekmut."34";
        $rekmut35 = $rekmut."35";
        $rekmut36 = $rekmut."36";
        $rekmut37 = $rekmut."37";
        $rekmut38 = $rekmut."38";
        $rekmut39 = $rekmut."39";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;

        if ($format=="1") {
            if ($rek=="1301" || $rek=="1304" || $rek=="1305" || $rek=="1306") {
                $head = "
                        <tr>
                            <td rowspan=\"2\"align=\"center\">KODE</td>
                            <td rowspan=\"2\"align=\"center\">OPD</td>
                            <td rowspan=\"2\"align=\"center\">$thn_ang_1</td>
                            <td colspan=\"9\"align=\"center\">Mutasi Tambah</td>
                            <td colspan=\"10\"align=\"center\">Mutasi Kurang</td>
                            <td rowspan=\"2\"align=\"center\">$thn_ang</td>
                        </tr>
                        <tr>
                            <td width=\"3%\" >Realisasi Belanja Modal</td>
                            <td width=\"3%\">Hibah</td>
                            <td width=\"3%\">Beban</td>
                            <td width=\"3%\">Mutasi Antar SKPD</td>
                            <td width=\"3%\">Reklas</td>
                            <td width=\"3%\">Revaluasi</td>
                            <td width=\"3%\">Koreksi</td>
                            <td width=\"3%\">Mutasi Nomenklatur</td>
                            <td width=\"3%\">Jumlah</td>

                            <td width=\"3%\">Hibah</td>
                            <td width=\"3%\">Penghapusan</td>
                            <td width=\"3%\">Mutasi Antar SKPD</td>
                            <td width=\"3%\">Reklas</td>
                            <td width=\"3%\">Revaluasi</td>
                            <td width=\"3%\">Koreksi</td>
                            <td width=\"3%\">Rusak Berat</td>
                            <td width=\"3%\">Beban</td>
                            <td width=\"3%\">Mutasi Nomenklatur</td>
                            <td width=\"3%\">Jumlah</td>
                        </tr>
                        <tr>
                            <td align=\"center\">1</td>
                            <td align=\"center\">2</td>
                            <td align=\"center\">3</td>
                            <td align=\"center\">4</td>
                            <td align=\"center\">5</td>
                            <td align=\"center\">6</td>
                            <td align=\"center\">7</td>
                            <td align=\"center\">8</td>
                            <td align=\"center\">9</td>
                            <td align=\"center\">10</td>
                            <td align=\"center\">11</td>
                            <td align=\"center\">12</td>
                            <td align=\"center\">13</td>
                            <td align=\"center\">14</td>
                            <td align=\"center\">15</td>
                            <td align=\"center\">16</td>
                            <td align=\"center\">17</td>
                            <td align=\"center\">18</td>
                            <td align=\"center\">19</td>
                            <td align=\"center\">20</td>
                            <td align=\"center\">21</td>
                            <td align=\"center\">22</td>
                            <td align=\"center\">23</td>
                        </tr>";
                $query = DB::select("SELECT
                        a.kd_skpd,
                        a.nm_skpd ,
                        isnull( b.sal_lalu, 0 ) sal_lalu,
                        isnull( b.realisasibelanjamodal, 0 ) realisasibelanjamodal,
                        isnull( b.hibah, 0 ) hibah,
                        isnull( b.beban, 0 ) beban,
                        isnull( b.mutasiantaropd, 0 ) mutasiantaropd,
                        isnull( b.reklas, 0 ) reklas,
                        isnull( b.revaluasi, 0 ) revaluasi,
                        isnull( b.koreksi, 0 ) koreksi,
                        isnull( b.mutasi_nomenklatur, 0 ) mutasi_nomenklatur,
                        isnull( b.hibah1, 0 ) hibah1,
                        isnull( b.penghapusan1, 0 ) penghapusan1,
                        isnull( b.mutasiantaropd1, 0 ) mutasiantaropd1,
                        isnull( b.reklas1, 0 ) reklas1,
                        isnull( b.revaluasi1, 0 ) revaluasi1,
                        isnull( b.koreksi1, 0 ) koreksi1,
                        isnull( b.rusakberat, 0 ) rusakberat,
                        isnull( b.beban1, 0 ) beban1,
                        isnull( b.mutasi_nomenklatur1, 0 ) mutasi_nomenklatur1 
                    FROM
                        ms_skpd a
                        LEFT JOIN (
                        SELECT
                            b.* 
                        FROM
                            (
                            SELECT
                                a.kd_unit,
                                b.nm_skpd,
                                ISNULL( SUM ( a.sal_lalu ), 0 ) AS sal_lalu,
                                ISNULL( SUM ( a.RealisasiBelanjaModal ), 0 ) AS realisasibelanjamodal,
                                isnull( SUM ( a.hibah ), 0 ) AS hibah,
                                isnull( SUM ( a.beban ), 0 ) AS beban,
                                isnull( SUM ( a.mutasiantaropd ), 0 ) AS mutasiantaropd,
                                isnull( SUM ( a.reklas ), 0 ) AS reklas,
                                isnull( SUM ( a.revaluasi ), 0 ) AS revaluasi,
                                isnull( SUM ( a.koreksi ), 0 ) AS koreksi,
                                isnull( SUM ( a.mutasi_nomenklatur ), 0 ) AS mutasi_nomenklatur,
                                isnull( SUM ( a.hibah1 ), 0 ) AS hibah1,
                                isnull( SUM ( a.Penghapusan1 ), 0 ) AS penghapusan1,
                                isnull( SUM ( a.mutasiantaropd1 ), 0 ) AS mutasiantaropd1,
                                isnull( SUM ( a.reklas1 ), 0 ) AS reklas1,
                                isnull( SUM ( a.revaluasi1 ), 0 ) AS revaluasi1,
                                isnull( SUM ( a.koreksi1 ), 0 ) AS koreksi1,
                                isnull( SUM ( a.rusakberat ), 0 ) AS rusakberat,
                                isnull( SUM ( a.beban1 ), 0 ) AS beban1,
                                isnull( SUM ( a.mutasi_nomenklatur1 ), 0 ) AS mutasi_nomenklatur1,
                                ISNULL( SUM ( a.sal ), 0 ) AS sal 
                            FROM
                                (
                                SELECT
                                    a.kd_unit,
                                    isnull( SUM ( debet - kredit ), 0 ) sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    trdju_calk a
                                    INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                    AND a.kd_unit= b.kd_skpd 
                                WHERE
                                    LEFT ( a.kd_rek6, 4 ) = '$rek' 
                                    AND YEAR ( tgl_voucher ) <= '$thn_ang_1' 
                                GROUP BY
                                    a.kd_unit UNION ALL
                                SELECT
                                    a.kd_unit,
                                    0 sal_lalu,
                                    isnull( SUM ( debet - kredit ), 0 ) RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    trdju_calk a
                                    INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                    AND a.kd_unit= b.kd_skpd 
                                WHERE
                                    $rekbel 
                                    AND YEAR ( tgl_voucher ) = '$thn_ang' 
                                GROUP BY
                                    a.kd_unit UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    isnull( SUM ( nilai ), 0 ) hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut22' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    isnull( SUM ( nilai ), 0 ) beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut23' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    isnull( SUM ( nilai ), 0 ) mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut24' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    isnull( SUM ( nilai ), 0 ) reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut25' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    isnull( SUM ( nilai ), 0 ) revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut26' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    isnull( SUM ( nilai ), 0 ) koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut27' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut28' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    isnull( SUM ( nilai ), 0 ) hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut31' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    isnull( SUM ( nilai ), 0 ) Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut32' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    isnull( SUM ( nilai ), 0 ) mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut33' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    isnull( SUM ( nilai ), 0 ) reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut34' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    isnull( SUM ( nilai ), 0 ) revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut35' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    isnull( SUM ( nilai ), 0 ) koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut36' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    isnull( SUM ( nilai ), 0 ) rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut37' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    isnull( SUM ( nilai ), 0 ) beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut38' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut39' 
                                GROUP BY
                                    kd_skpd 
                                ) a
                                INNER JOIN ms_skpd b ON a.kd_unit= b.kd_skpd 
                            GROUP BY
                                a.kd_unit,
                                b.nm_skpd 
                            ) b 
                        ) b ON a.kd_skpd= b.kd_unit 
                    ORDER BY
                        a.kd_skpd");
            }elseif($rek=="1302"){
                $head = "
                    <tr>
                        <td rowspan=\"2\"align=\"center\">KODE</td>
                        <td rowspan=\"2\"align=\"center\">OPD</td>
                        <td rowspan=\"2\"align=\"center\">$thn_ang_1</td>
                        <td colspan=\"10\"align=\"center\">Mutasi Tambah</td>
                        <td colspan=\"11\"align=\"center\">Mutasi Kurang</td>
                        <td rowspan=\"2\"align=\"center\">$thn_ang</td>
                    </tr>
                    <tr>
                        <td width=\"3%\" >Realisasi Belanja Modal</td>
                        <td width=\"3%\">Hibah</td>
                        <td width=\"3%\">Beban</td>
                        <td width=\"3%\">Mutasi Antar SKPD</td>
                        <td width=\"3%\">Reklas</td>
                        <td width=\"3%\">Revaluasi</td>
                        <td width=\"3%\">Koreksi</td>
                        <td width=\"3%\">Pengadaan dari Belanja Tidak Terduga</td>
                        <td width=\"3%\">Mutasi Nomenklatur</td>
                        <td width=\"3%\">Jumlah</td>

                        <td width=\"3%\">Hibah</td>
                        <td width=\"3%\">Penghapusan</td>
                        <td width=\"3%\">Mutasi Antar SKPD</td>
                        <td width=\"3%\">Reklas</td>
                        <td width=\"3%\">Revaluasi</td>
                        <td width=\"3%\">Koreksi</td>
                        <td width=\"3%\">Rusak Berat</td>
                        <td width=\"3%\">Beban</td>
                        <td width=\"3%\">Ekstracomptable</td>
                        <td width=\"3%\">Mutasi Nomenklatur</td>
                        <td width=\"3%\">Jumlah</td>
                    </tr>
                    <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                        <td align=\"center\">5</td>
                        <td align=\"center\">6</td>
                        <td align=\"center\">7</td>
                        <td align=\"center\">8</td>
                        <td align=\"center\">9</td>
                        <td align=\"center\">10</td>
                        <td align=\"center\">11</td>
                        <td align=\"center\">12</td>
                        <td align=\"center\">13</td>
                        <td align=\"center\">14</td>
                        <td align=\"center\">15</td>
                        <td align=\"center\">16</td>
                        <td align=\"center\">17</td>
                        <td align=\"center\">18</td>
                        <td align=\"center\">19</td>
                        <td align=\"center\">20</td>
                        <td align=\"center\">21</td>
                        <td align=\"center\">22</td>
                        <td align=\"center\">23</td>
                        <td align=\"center\">24</td>
                        <td align=\"center\">25</td>
                    </tr>";
                $query = DB::select("SELECT
                        a.kd_skpd,
                        a.nm_skpd ,
                        isnull( b.sal_lalu, 0 ) sal_lalu,
                        isnull( b.realisasibelanjamodal, 0 ) realisasibelanjamodal,
                        isnull( b.hibah, 0 ) hibah,
                        isnull( b.beban, 0 ) beban,
                        isnull( b.mutasiantaropd, 0 ) mutasiantaropd,
                        isnull( b.reklas, 0 ) reklas,
                        isnull( b.revaluasi, 0 ) revaluasi,
                        isnull( b.koreksi, 0 ) koreksi,
                        isnull( b.pengadaan_btt, 0 ) pengadaan_btt,
                        isnull( b.mutasi_nomenklatur, 0 ) mutasi_nomenklatur,
                        isnull( b.hibah1, 0 ) hibah1,
                        isnull( b.penghapusan1, 0 ) penghapusan1,
                        isnull( b.mutasiantaropd1, 0 ) mutasiantaropd1,
                        isnull( b.reklas1, 0 ) reklas1,
                        isnull( b.revaluasi1, 0 ) revaluasi1,
                        isnull( b.koreksi1, 0 ) koreksi1,
                        isnull( b.rusakberat, 0 ) rusakberat,
                        isnull( b.beban1, 0 ) beban1,
                        isnull( b.Ekstracomptable, 0 ) Ekstracomptable,
                        isnull( b.mutasi_nomenklatur1, 0 ) mutasi_nomenklatur1 
                    FROM
                        ms_skpd a
                        LEFT JOIN (
                        SELECT
                            b.* 
                        FROM
                            (
                            SELECT
                                a.kd_unit,
                                b.nm_skpd,
                                ISNULL( SUM ( a.sal_lalu ), 0 ) AS sal_lalu,
                                ISNULL( SUM ( a.RealisasiBelanjaModal ), 0 ) AS realisasibelanjamodal,
                                isnull( SUM ( a.hibah ), 0 ) AS hibah,
                                isnull( SUM ( a.beban ), 0 ) AS beban,
                                isnull( SUM ( a.mutasiantaropd ), 0 ) AS mutasiantaropd,
                                isnull( SUM ( a.reklas ), 0 ) AS reklas,
                                isnull( SUM ( a.revaluasi ), 0 ) AS revaluasi,
                                isnull( SUM ( a.koreksi ), 0 ) AS koreksi,
                                isnull( SUM ( a.pengadaan_btt ), 0 ) AS pengadaan_btt,
                                isnull( SUM ( a.mutasi_nomenklatur ), 0 ) AS mutasi_nomenklatur,
                                isnull( SUM ( a.hibah1 ), 0 ) AS hibah1,
                                isnull( SUM ( a.Penghapusan1 ), 0 ) AS penghapusan1,
                                isnull( SUM ( a.mutasiantaropd1 ), 0 ) AS mutasiantaropd1,
                                isnull( SUM ( a.reklas1 ), 0 ) AS reklas1,
                                isnull( SUM ( a.revaluasi1 ), 0 ) AS revaluasi1,
                                isnull( SUM ( a.koreksi1 ), 0 ) AS koreksi1,
                                isnull( SUM ( a.rusakberat ), 0 ) AS rusakberat,
                                isnull( SUM ( a.beban1 ), 0 ) AS beban1,
                                isnull( SUM ( a.Ekstracomptable ), 0 ) AS Ekstracomptable,
                                isnull( SUM ( a.mutasi_nomenklatur1 ), 0 ) AS mutasi_nomenklatur1,
                                ISNULL( SUM ( a.sal ), 0 ) AS sal 
                            FROM
                                (
                                SELECT
                                    a.kd_unit,
                                    isnull( SUM ( debet - kredit ), 0 ) sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    trdju_calk a
                                    INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                    AND a.kd_unit= b.kd_skpd 
                                WHERE
                                    LEFT ( a.kd_rek6, 4 ) = '1302' 
                                    AND YEAR ( tgl_voucher ) <= '$thn_ang_1' 
                                GROUP BY
                                    a.kd_unit UNION ALL
                                SELECT
                                    a.kd_unit,
                                    0 sal_lalu,
                                    isnull( SUM ( debet - kredit ), 0 ) RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    trdju_calk a
                                    INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                    AND a.kd_unit= b.kd_skpd 
                                WHERE
                                    LEFT ( a.kd_rek6, 4 ) = '5202' 
                                    AND YEAR ( tgl_voucher ) = '$thn_ang' 
                                GROUP BY
                                    a.kd_unit UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    isnull( SUM ( nilai ), 0 ) hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13222' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    isnull( SUM ( nilai ), 0 ) beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13223' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    isnull( SUM ( nilai ), 0 ) mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13224' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    isnull( SUM ( nilai ), 0 ) reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13225' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    isnull( SUM ( nilai ), 0 ) revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13226' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    isnull( SUM ( nilai ), 0 ) koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13227' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    isnull( SUM ( nilai ), 0 ) pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13228' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13229' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    isnull( SUM ( nilai ), 0 ) hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13231' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    isnull( SUM ( nilai ), 0 ) Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13232' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    isnull( SUM ( nilai ), 0 ) mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13233' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    isnull( SUM ( nilai ), 0 ) reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13234' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    isnull( SUM ( nilai ), 0 ) revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13235' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    isnull( SUM ( nilai ), 0 ) koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13236' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    isnull( SUM ( nilai ), 0 ) rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13237' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    isnull( SUM ( nilai ), 0 ) beban1,
                                    0 Ekstracomptable,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13238' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    isnull( SUM ( nilai ), 0 ) Ekstracomptable,
                                    0 mutasi_nomenklatur,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13239' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 Ekstracomptable,
                                    isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '132310' 
                                GROUP BY
                                    kd_skpd 
                                ) a
                                INNER JOIN ms_skpd b ON a.kd_unit= b.kd_skpd 
                            GROUP BY
                                a.kd_unit,
                                b.nm_skpd 
                            ) b 
                        ) b ON a.kd_skpd= b.kd_unit 
                    ORDER BY
                        a.kd_skpd");
            }elseif($rek=="1303"){
                $head = "
                    <tr>
                        <td rowspan=\"2\"align=\"center\">KODE</td>
                        <td rowspan=\"2\"align=\"center\">OPD</td>
                        <td rowspan=\"2\"align=\"center\">$thn_ang_1</td>
                        <td colspan=\"10\"align=\"center\">Mutasi Tambah</td>
                        <td colspan=\"10\"align=\"center\">Mutasi Kurang</td>
                        <td rowspan=\"2\"align=\"center\">$thn_ang</td>
                        </tr>
                        <tr>
                        <td width=\"3%\" >Realisasi Belanja Modal</td>
                        <td width=\"3%\">Hibah</td>
                        <td width=\"3%\">Beban</td>
                        <td width=\"3%\">Mutasi Antar SKPD</td>
                        <td width=\"3%\">Reklas</td>
                        <td width=\"3%\">Revaluasi</td>
                        <td width=\"3%\">Koreksi</td>
                        <td width=\"3%\">Pengadaan dari Belanja Tidak Terduga</td>
                        <td width=\"3%\">Mutasi Nomenklatur</td>
                        <td width=\"3%\">Jumlah</td>

                        <td width=\"3%\">Hibah</td>
                        <td width=\"3%\">Penghapusan</td>
                        <td width=\"3%\">Mutasi Antar SKPD</td>
                        <td width=\"3%\">Reklas</td>
                        <td width=\"3%\">Revaluasi</td>
                        <td width=\"3%\">Koreksi</td>
                        <td width=\"3%\">Rusak Berat</td>
                        <td width=\"3%\">Beban</td>
                        <td width=\"3%\">Mutasi Nomenklatur</td>
                        <td width=\"3%\">Jumlah</td>
                        </tr>
                        <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                        <td align=\"center\">5</td>
                        <td align=\"center\">6</td>
                        <td align=\"center\">7</td>
                        <td align=\"center\">8</td>
                        <td align=\"center\">9</td>
                        <td align=\"center\">10</td>
                        <td align=\"center\">11</td>
                        <td align=\"center\">12</td>
                        <td align=\"center\">13</td>
                        <td align=\"center\">14</td>
                        <td align=\"center\">15</td>
                        <td align=\"center\">16</td>
                        <td align=\"center\">17</td>
                        <td align=\"center\">18</td>
                        <td align=\"center\">19</td>
                        <td align=\"center\">20</td>
                        <td align=\"center\">21</td>
                        <td align=\"center\">22</td>
                        <td align=\"center\">23</td>
                        <td align=\"center\">24</td>
                        
                    </tr>";
                $query = DB::select("SELECT
                        a.kd_skpd,
                        a.nm_skpd ,
                        isnull( b.sal_lalu, 0 ) sal_lalu,
                        isnull( b.realisasibelanjamodal, 0 ) realisasibelanjamodal,
                        isnull( b.hibah, 0 ) hibah,
                        isnull( b.beban, 0 ) beban,
                        isnull( b.mutasiantaropd, 0 ) mutasiantaropd,
                        isnull( b.reklas, 0 ) reklas,
                        isnull( b.revaluasi, 0 ) revaluasi,
                        isnull( b.koreksi, 0 ) koreksi,
                        isnull( b.pengadaan_btt, 0 ) pengadaan_btt,
                        isnull( b.mutasi_nomenklatur, 0 ) mutasi_nomenklatur,
                        isnull( b.hibah1, 0 ) hibah1,
                        isnull( b.penghapusan1, 0 ) penghapusan1,
                        isnull( b.mutasiantaropd1, 0 ) mutasiantaropd1,
                        isnull( b.reklas1, 0 ) reklas1,
                        isnull( b.revaluasi1, 0 ) revaluasi1,
                        isnull( b.koreksi1, 0 ) koreksi1,
                        isnull( b.rusakberat, 0 ) rusakberat,
                        isnull( b.beban1, 0 ) beban1,
                        isnull( b.mutasi_nomenklatur1, 0 ) mutasi_nomenklatur1 
                    FROM
                        ms_skpd a
                        LEFT JOIN (
                        SELECT
                            b.* 
                        FROM
                            (
                            SELECT
                                a.kd_unit,
                                b.nm_skpd,
                                ISNULL( SUM ( a.sal_lalu ), 0 ) AS sal_lalu,
                                ISNULL( SUM ( a.RealisasiBelanjaModal ), 0 ) AS realisasibelanjamodal,
                                isnull( SUM ( a.hibah ), 0 ) AS hibah,
                                isnull( SUM ( a.beban ), 0 ) AS beban,
                                isnull( SUM ( a.mutasiantaropd ), 0 ) AS mutasiantaropd,
                                isnull( SUM ( a.reklas ), 0 ) AS reklas,
                                isnull( SUM ( a.revaluasi ), 0 ) AS revaluasi,
                                isnull( SUM ( a.koreksi ), 0 ) AS koreksi,
                                isnull( SUM ( a.pengadaan_btt ), 0 ) AS pengadaan_btt,
                                isnull( SUM ( a.mutasi_nomenklatur ), 0 ) AS mutasi_nomenklatur,
                                isnull( SUM ( a.hibah1 ), 0 ) AS hibah1,
                                isnull( SUM ( a.Penghapusan1 ), 0 ) AS penghapusan1,
                                isnull( SUM ( a.mutasiantaropd1 ), 0 ) AS mutasiantaropd1,
                                isnull( SUM ( a.reklas1 ), 0 ) AS reklas1,
                                isnull( SUM ( a.revaluasi1 ), 0 ) AS revaluasi1,
                                isnull( SUM ( a.koreksi1 ), 0 ) AS koreksi1,
                                isnull( SUM ( a.rusakberat ), 0 ) AS rusakberat,
                                isnull( SUM ( a.beban1 ), 0 ) AS beban1,
                                isnull( SUM ( a.mutasi_nomenklatur1 ), 0 ) AS mutasi_nomenklatur1,
                                ISNULL( SUM ( a.sal ), 0 ) AS sal 
                            FROM
                                (
                                SELECT
                                    a.kd_unit,
                                    isnull( SUM ( debet - kredit ), 0 ) sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    trdju_calk a
                                    INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                    AND a.kd_unit= b.kd_skpd 
                                WHERE
                                    LEFT ( a.kd_rek6, 4 ) = '1303' 
                                    AND YEAR ( tgl_voucher ) <= '$thn_ang_1' 
                                GROUP BY
                                    a.kd_unit UNION ALL
                                SELECT
                                    a.kd_unit,
                                    0 sal_lalu,
                                    isnull( SUM ( debet - kredit ), 0 ) RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    trdju_calk a
                                    INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                    AND a.kd_unit= b.kd_skpd 
                                WHERE
                                    LEFT ( a.kd_rek6, 4 ) = '5203' 
                                    AND YEAR ( tgl_voucher ) = '$thn_ang' 
                                GROUP BY
                                    a.kd_unit UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    isnull( SUM ( nilai ), 0 ) hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13322' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    isnull( SUM ( nilai ), 0 ) beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13323' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    isnull( SUM ( nilai ), 0 ) mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13324' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    isnull( SUM ( nilai ), 0 ) reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13325' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    isnull( SUM ( nilai ), 0 ) revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13326' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    isnull( SUM ( nilai ), 0 ) koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13327' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    isnull( SUM ( nilai ), 0 ) pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13328' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13329' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    isnull( SUM ( nilai ), 0 ) hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13331' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    isnull( SUM ( nilai ), 0 ) Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13332' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    isnull( SUM ( nilai ), 0 ) mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13333' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    isnull( SUM ( nilai ), 0 ) reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13334' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    isnull( SUM ( nilai ), 0 ) revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13335' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    isnull( SUM ( nilai ), 0 ) koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13336' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    isnull( SUM ( nilai ), 0 ) rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13337' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    isnull( SUM ( nilai ), 0 ) beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13338' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 pengadaan_btt,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '13339' 
                                GROUP BY
                                    kd_skpd 
                                ) a
                                INNER JOIN ms_skpd b ON a.kd_unit= b.kd_skpd 
                            GROUP BY
                                a.kd_unit,
                                b.nm_skpd 
                            ) b 
                        ) b ON a.kd_skpd= b.kd_unit 
                    ORDER BY
                        a.kd_skpd");
            }elseif($rek=="1503"){
                $head = "
                    <tr>
                        <td rowspan=\"2\"align=\"center\">KODE</td>
                        <td rowspan=\"2\"align=\"center\">OPD</td>
                        <td rowspan=\"2\"align=\"center\">$thn_ang_1</td>
                        <td colspan=\"9\"align=\"center\">Mutasi Tambah</td>
                        <td colspan=\"10\"align=\"center\">Mutasi Kurang</td>
                        <td rowspan=\"2\"align=\"center\">$thn_ang</td>
                        </tr>
                        <tr>
                        <td width=\"3%\" >Realisasi Belanja Modal</td>
                        <td width=\"3%\">Hibah</td>
                        <td width=\"3%\">Beban</td>
                        <td width=\"3%\">Mutasi Antar SKPD</td>
                        <td width=\"3%\">Reklas</td>
                        <td width=\"3%\">Revaluasi</td>
                        <td width=\"3%\">Koreksi</td>
                        <td width=\"3%\">Mutasi Nomenklatur</td>
                        <td width=\"3%\">Jumlah</td>

                        <td width=\"3%\">Hibah</td>
                        <td width=\"3%\">Penghapusan</td>
                        <td width=\"3%\">Mutasi Antar SKPD</td>
                        <td width=\"3%\">Reklas</td>
                        <td width=\"3%\">Revaluasi</td>
                        <td width=\"3%\">Koreksi</td>
                        <td width=\"3%\">Rusak Berat</td>
                        <td width=\"3%\">Beban</td>
                        <td width=\"3%\">Mutasi Nomenklatur</td>
                        <td width=\"3%\">Jumlah</td>
                        </tr>
                        <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                        <td align=\"center\">5</td>
                        <td align=\"center\">6</td>
                        <td align=\"center\">7</td>
                        <td align=\"center\">8</td>
                        <td align=\"center\">9</td>
                        <td align=\"center\">10</td>
                        <td align=\"center\">11</td>
                        <td align=\"center\">12</td>
                        <td align=\"center\">13</td>
                        <td align=\"center\">14</td>
                        <td align=\"center\">15</td>
                        <td align=\"center\">16</td>
                        <td align=\"center\">17</td>
                        <td align=\"center\">18</td>
                        <td align=\"center\">19</td>
                        <td align=\"center\">20</td>
                        <td align=\"center\">21</td>
                        <td align=\"center\">22</td>
                        <td align=\"center\">23</td>
                        
                    </tr>";
                $query = $query = DB::select("SELECT
                        a.kd_skpd,
                        a.nm_skpd ,
                        isnull( b.sal_lalu, 0 ) sal_lalu,
                        isnull( b.realisasibelanjamodal, 0 ) realisasibelanjamodal,
                        isnull( b.hibah, 0 ) hibah,
                        isnull( b.beban, 0 ) beban,
                        isnull( b.mutasiantaropd, 0 ) mutasiantaropd,
                        isnull( b.reklas, 0 ) reklas,
                        isnull( b.revaluasi, 0 ) revaluasi,
                        isnull( b.koreksi, 0 ) koreksi,
                        isnull( b.mutasi_nomenklatur, 0 ) mutasi_nomenklatur,
                        isnull( b.hibah1, 0 ) hibah1,
                        isnull( b.penghapusan1, 0 ) penghapusan1,
                        isnull( b.mutasiantaropd1, 0 ) mutasiantaropd1,
                        isnull( b.reklas1, 0 ) reklas1,
                        isnull( b.revaluasi1, 0 ) revaluasi1,
                        isnull( b.koreksi1, 0 ) koreksi1,
                        isnull( b.rusakberat, 0 ) rusakberat,
                        isnull( b.beban1, 0 ) beban1,
                        isnull( b.mutasi_nomenklatur1, 0 ) mutasi_nomenklatur1 
                    FROM
                        ms_skpd a
                        LEFT JOIN (
                        SELECT
                            b.* 
                        FROM
                            (
                            SELECT
                                a.kd_unit,
                                b.nm_skpd,
                                ISNULL( SUM ( a.sal_lalu ), 0 ) AS sal_lalu,
                                ISNULL( SUM ( a.RealisasiBelanjaModal ), 0 ) AS realisasibelanjamodal,
                                isnull( SUM ( a.hibah ), 0 ) AS hibah,
                                isnull( SUM ( a.beban ), 0 ) AS beban,
                                isnull( SUM ( a.mutasiantaropd ), 0 ) AS mutasiantaropd,
                                isnull( SUM ( a.reklas ), 0 ) AS reklas,
                                isnull( SUM ( a.revaluasi ), 0 ) AS revaluasi,
                                isnull( SUM ( a.koreksi ), 0 ) AS koreksi,
                                isnull( SUM ( a.mutasi_nomenklatur ), 0 ) AS mutasi_nomenklatur,
                                isnull( SUM ( a.hibah1 ), 0 ) AS hibah1,
                                isnull( SUM ( a.Penghapusan1 ), 0 ) AS penghapusan1,
                                isnull( SUM ( a.mutasiantaropd1 ), 0 ) AS mutasiantaropd1,
                                isnull( SUM ( a.reklas1 ), 0 ) AS reklas1,
                                isnull( SUM ( a.revaluasi1 ), 0 ) AS revaluasi1,
                                isnull( SUM ( a.koreksi1 ), 0 ) AS koreksi1,
                                isnull( SUM ( a.rusakberat ), 0 ) AS rusakberat,
                                isnull( SUM ( a.beban1 ), 0 ) AS beban1,
                                isnull( SUM ( a.mutasi_nomenklatur1 ), 0 ) AS mutasi_nomenklatur1,
                                ISNULL( SUM ( a.sal ), 0 ) AS sal 
                            FROM
                                (
                                SELECT
                                    a.kd_unit,
                                    isnull( SUM ( debet - kredit ), 0 ) sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    trdju_calk a
                                    INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                    AND a.kd_unit= b.kd_skpd 
                                WHERE
                                    LEFT ( kd_rek6, 4 ) IN ( '1503' ) 
                                    AND YEAR ( tgl_voucher ) <= '$thn_ang_1' 
                                GROUP BY
                                    a.kd_unit UNION ALL
                                SELECT
                                    a.kd_unit,
                                    0 sal_lalu,
                                    isnull( SUM ( debet - kredit ), 0 ) RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    trdju_calk a
                                    INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                    AND a.kd_unit= b.kd_skpd 
                                    WHERE-- (
                                    LEFT ( a.kd_rek6, 4 ) IN ( '5206' ) -- or left(a.kd_rek6,6)in('520508'))
                                    
                                    AND YEAR ( tgl_voucher ) = '$thn_ang' 
                                GROUP BY
                                    a.kd_unit UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    isnull( SUM ( nilai ), 0 ) hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15322' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    isnull( SUM ( nilai ), 0 ) beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15323' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    isnull( SUM ( nilai ), 0 ) mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15324' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    isnull( SUM ( nilai ), 0 ) reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15325' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    isnull( SUM ( nilai ), 0 ) revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15326' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    isnull( SUM ( nilai ), 0 ) koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15327' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15328' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    isnull( SUM ( nilai ), 0 ) hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15331' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    isnull( SUM ( nilai ), 0 ) Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15332' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    isnull( SUM ( nilai ), 0 ) mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15333' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    isnull( SUM ( nilai ), 0 ) reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15334' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    isnull( SUM ( nilai ), 0 ) revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15335' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    isnull( SUM ( nilai ), 0 ) koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15336' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    isnull( SUM ( nilai ), 0 ) rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15337' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    isnull( SUM ( nilai ), 0 ) beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15338' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15339' 
                                GROUP BY
                                    kd_skpd 
                                ) a
                                INNER JOIN ms_skpd b ON a.kd_unit= b.kd_skpd 
                            GROUP BY
                                a.kd_unit,
                                b.nm_skpd 
                            ) b 
                        ) b ON a.kd_skpd= b.kd_unit 
                    ORDER BY
                        a.kd_skpd");
            }elseif($rek=="1504"){
                $head ="
                    <tr>
                        <td rowspan=\"2\"align=\"center\">KODE</td>
                        <td rowspan=\"2\"align=\"center\">OPD</td>
                        <td rowspan=\"2\"align=\"center\">$thn_ang_1</td>
                        <td colspan=\"9\"align=\"center\">Mutasi Tambah</td>
                        <td colspan=\"10\"align=\"center\">Mutasi Kurang</td>
                        <td rowspan=\"2\"align=\"center\">$thn_ang</td>
                        </tr>
                        <tr>
                        <td width=\"3%\" >Realisasi Belanja Modal</td>
                        <td width=\"3%\">Hibah</td>
                        <td width=\"3%\">Beban</td>
                        <td width=\"3%\">Mutasi Antar SKPD</td>
                        <td width=\"3%\">Reklas</td>
                        <td width=\"3%\">Revaluasi</td>
                        <td width=\"3%\">Koreksi</td>
                        <td width=\"3%\">Mutasi Nomenklatur</td>
                        <td width=\"3%\">Jumlah</td>

                        <td width=\"3%\">Hibah</td>
                        <td width=\"3%\">Penghapusan</td>
                        <td width=\"3%\">Mutasi Antar SKPD</td>
                        <td width=\"3%\">Reklas</td>
                        <td width=\"3%\">Revaluasi</td>
                        <td width=\"3%\">Koreksi</td>
                        <td width=\"3%\">Rusak Berat</td>
                        <td width=\"3%\">Beban</td>
                        <td width=\"3%\">Mutasi Nomenklatur</td>
                        <td width=\"3%\">Jumlah</td>
                        </tr>
                        <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                        <td align=\"center\">5</td>
                        <td align=\"center\">6</td>
                        <td align=\"center\">7</td>
                        <td align=\"center\">8</td>
                        <td align=\"center\">9</td>
                        <td align=\"center\">10</td>
                        <td align=\"center\">11</td>
                        <td align=\"center\">12</td>
                        <td align=\"center\">13</td>
                        <td align=\"center\">14</td>
                        <td align=\"center\">15</td>
                        <td align=\"center\">16</td>
                        <td align=\"center\">17</td>
                        <td align=\"center\">18</td>
                        <td align=\"center\">19</td>
                        <td align=\"center\">20</td>
                        <td align=\"center\">21</td>
                        <td align=\"center\">22</td>
                        <td align=\"center\">23</td>
                        
                    </tr>";
                $query = DB::select("SELECT
                        a.kd_skpd,
                        a.nm_skpd ,
                        isnull( b.sal_lalu, 0 ) sal_lalu,
                        isnull( b.realisasibelanjamodal, 0 ) realisasibelanjamodal,
                        isnull( b.hibah, 0 ) hibah,
                        isnull( b.beban, 0 ) beban,
                        isnull( b.mutasiantaropd, 0 ) mutasiantaropd,
                        isnull( b.reklas, 0 ) reklas,
                        isnull( b.revaluasi, 0 ) revaluasi,
                        isnull( b.koreksi, 0 ) koreksi,
                        isnull( b.mutasi_nomenklatur, 0 ) mutasi_nomenklatur,
                        isnull( b.hibah1, 0 ) hibah1,
                        isnull( b.penghapusan1, 0 ) penghapusan1,
                        isnull( b.mutasiantaropd1, 0 ) mutasiantaropd1,
                        isnull( b.reklas1, 0 ) reklas1,
                        isnull( b.revaluasi1, 0 ) revaluasi1,
                        isnull( b.koreksi1, 0 ) koreksi1,
                        isnull( b.rusakberat, 0 ) rusakberat,
                        isnull( b.beban1, 0 ) beban1,
                        isnull( b.mutasi_nomenklatur1, 0 ) mutasi_nomenklatur1 
                    FROM
                        ms_skpd a
                        LEFT JOIN (
                        SELECT
                            b.* 
                        FROM
                            (
                            SELECT
                                a.kd_unit,
                                b.nm_skpd,
                                ISNULL( SUM ( a.sal_lalu ), 0 ) AS sal_lalu,
                                ISNULL( SUM ( a.RealisasiBelanjaModal ), 0 ) AS realisasibelanjamodal,
                                isnull( SUM ( a.hibah ), 0 ) AS hibah,
                                isnull( SUM ( a.beban ), 0 ) AS beban,
                                isnull( SUM ( a.mutasiantaropd ), 0 ) AS mutasiantaropd,
                                isnull( SUM ( a.reklas ), 0 ) AS reklas,
                                isnull( SUM ( a.revaluasi ), 0 ) AS revaluasi,
                                isnull( SUM ( a.koreksi ), 0 ) AS koreksi,
                                isnull( SUM ( a.mutasi_nomenklatur ), 0 ) AS mutasi_nomenklatur,
                                isnull( SUM ( a.hibah1 ), 0 ) AS hibah1,
                                isnull( SUM ( a.Penghapusan1 ), 0 ) AS penghapusan1,
                                isnull( SUM ( a.mutasiantaropd1 ), 0 ) AS mutasiantaropd1,
                                isnull( SUM ( a.reklas1 ), 0 ) AS reklas1,
                                isnull( SUM ( a.revaluasi1 ), 0 ) AS revaluasi1,
                                isnull( SUM ( a.koreksi1 ), 0 ) AS koreksi1,
                                isnull( SUM ( a.rusakberat ), 0 ) AS rusakberat,
                                isnull( SUM ( a.beban1 ), 0 ) AS beban1,
                                isnull( SUM ( a.mutasi_nomenklatur1 ), 0 ) AS mutasi_nomenklatur1,
                                ISNULL( SUM ( a.sal ), 0 ) AS sal 
                            FROM
                                (
                                SELECT
                                    a.kd_unit,
                                    isnull( SUM ( debet - kredit ), 0 ) sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    trdju_calk a
                                    INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                    AND a.kd_unit= b.kd_skpd 
                                WHERE
                                    LEFT ( a.kd_rek6, 4 ) = '1504' 
                                    AND YEAR ( tgl_voucher ) <= '$thn_ang_1' 
                                GROUP BY
                                    a.kd_unit UNION ALL
                                SELECT
                                    a.kd_skpd,
                                    0 sal_lalu,
                                    isnull( SUM ( 0 ), 0 ) RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk a 
                                WHERE
                                    LEFT ( kd_rek, 5 ) = '15421' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    isnull( SUM ( nilai ), 0 ) hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15422' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    isnull( SUM ( nilai ), 0 ) beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15423' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    isnull( SUM ( nilai ), 0 ) mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15424' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    isnull( SUM ( nilai ), 0 ) reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15425' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    isnull( SUM ( nilai ), 0 ) revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15426' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    isnull( SUM ( nilai ), 0 ) koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15427' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15428' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    isnull( SUM ( nilai ), 0 ) hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15431' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    isnull( SUM ( nilai ), 0 ) Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15432' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    isnull( SUM ( nilai ), 0 ) mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15433' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    isnull( SUM ( nilai ), 0 ) reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15434' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    isnull( SUM ( nilai ), 0 ) revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15435' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    isnull( SUM ( nilai ), 0 ) koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15436' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    isnull( SUM ( nilai ), 0 ) rusakberat,
                                    0 beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15437' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    isnull( SUM ( nilai ), 0 ) beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15438' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 beban1,
                                    isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '15439' 
                                GROUP BY
                                    kd_skpd 
                                ) a
                                INNER JOIN ms_skpd b ON a.kd_unit= b.kd_skpd 
                            GROUP BY
                                a.kd_unit,
                                b.nm_skpd 
                            ) b 
                        ) b ON a.kd_skpd= b.kd_unit 
                    ORDER BY
                        a.kd_skpd");
            }

        }elseif($format=="2"){
            if ($rek=="1301" || $rek=="1304" || $rek=="1305" || $rek=="1306") {
                $head = "
                    <tr>
                        <td rowspan=\"3\"align=\"center\" bgcolor=\"#CCCCCC\">KODE</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">OPD</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">$thn_ang_1</td>
                        <td colspan=\"16\"align=\"center\"bgcolor=\"#CCCCCC\">Mutasi Tambah</td>
                        <td colspan=\"19\"align=\"center\"bgcolor=\"#CCCCCC\">Mutasi Kurang</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">$thn_ang</td>
                        </tr>

                        <tr>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Realisasi Belanja Modal</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Hibah</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Beban</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Antar SKPD</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Reklas</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Revaluasi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Koreksi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutas Nomenklatur</td>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Jumlah</td>

                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Hibah</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Penghapusan</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Antar SKPD</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Reklas</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Revaluasi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Koreksi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Rusak Berat</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Beban</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Nomenklatur</td>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Jumlah</td>
                        </tr>

                        <tr>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                                    

                        </tr>
                        <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                        <td align=\"center\">5</td>
                        <td align=\"center\">6</td>
                        <td align=\"center\">7</td>
                        <td align=\"center\">8</td>
                        <td align=\"center\">9</td>
                        <td align=\"center\">10</td>
                        <td align=\"center\">11</td>
                        <td align=\"center\">12</td>
                        <td align=\"center\">13</td>
                        <td align=\"center\">14</td>
                        <td align=\"center\">15</td>
                        <td align=\"center\">16</td>
                        <td align=\"center\">17</td>
                        <td align=\"center\">18</td>
                        <td align=\"center\">19</td>
                        <td align=\"center\">20</td>
                        <td align=\"center\">21</td>
                        <td align=\"center\">22</td>
                        <td align=\"center\">23</td>
                        <td align=\"center\">24</td>
                        <td align=\"center\">25</td>
                        <td align=\"center\">26</td>
                        <td align=\"center\">27</td>
                        <td align=\"center\">28</td>
                        <td align=\"center\">29</td>
                        <td align=\"center\">30</td>
                        <td align=\"center\">31</td>
                        <td align=\"center\">32</td>
                        <td align=\"center\">33</td>
                        <td align=\"center\">34</td>
                        <td align=\"center\">35</td>
                        <td align=\"center\">36</td>
                        <td align=\"center\">37</td>
                        <td align=\"center\">38</td>
                        <td align=\"center\">39</td>
                        
                    </tr>";
                $query = DB::select("SELECT
                    a.kd_skpd,
                    a.nm_skpd,
                    isnull( b.sal_lalu, 0 ) sal_lalu,
                    isnull( b.realisasibelanjamodal, 0 ) realisasibelanjamodal,
                    isnull( b.ket_hibah, '' ) ket_hibah,
                    isnull( b.hibah, 0 ) hibah,
                    isnull( b.ket_beban, '' ) ket_beban,
                    isnull( b.beban, 0 ) beban,
                    isnull( b.ket_mutasiantaropd, '' ) ket_mutasiantaropd,
                    isnull( b.mutasiantaropd, 0 ) mutasiantaropd,
                    isnull( b.ket_reklas, '' ) ket_reklas,
                    isnull( b.reklas, 0 ) reklas,
                    isnull( b.ket_revaluasi, '' ) ket_revaluasi,
                    isnull( b.revaluasi, 0 ) revaluasi,
                    isnull( b.ket_koreksi, '' ) ket_koreksi,
                    isnull( b.koreksi, 0 ) koreksi,
                    isnull( b.ket_mutasi_nomenklatur, '' ) ket_mutasi_nomenklatur,
                    isnull( b.mutasi_nomenklatur, 0 ) mutasi_nomenklatur,
                    isnull( b.ket_hibah1, '' ) ket_hibah1,
                    isnull( b.hibah1, 0 ) hibah1,
                    isnull( b.ket_penghapusan, '' ) ket_penghapusan,
                    isnull( b.penghapusan1, 0 ) penghapusan1,
                    isnull( b.ket_mutasiantaropd1, '' ) ket_mutasiantaropd1,
                    isnull( b.mutasiantaropd1, 0 ) mutasiantaropd1,
                    isnull( b.ket_reklas1, '' ) ket_reklas1,
                    isnull( b.reklas1, 0 ) reklas1,
                    isnull( b.ket_revaluasi1, '' ) ket_revaluasi1,
                    isnull( b.revaluasi1, 0 ) revaluasi1,
                    isnull( b.ket_koreksi1, '' ) ket_koreksi1,
                    isnull( b.koreksi1, 0 ) koreksi1,
                    isnull( b.ket_rusakberat, '' ) ket_rusakberat,
                    isnull( b.rusakberat, 0 ) rusakberat,
                    isnull( b.ket_beban1, '' ) ket_beban1,
                    isnull( b.beban1, 0 ) beban1,
                    isnull( b.ket_mutasi_nomenklatur1, '' ) ket_mutasi_nomenklatur1,
                    isnull( b.mutasi_nomenklatur1, 0 ) mutasi_nomenklatur1 
                FROM
                    ms_skpd a
                    LEFT JOIN (
                    SELECT
                        a.kd_unit,
                        a.nm_skpd,
                        a.sal_lalu,
                        a.realisasibelanjamodal,
                        b.ket_hibah,
                        a.hibah,
                        b.ket_beban,
                        a.beban,
                        b.ket_mutasiantaropd,
                        a.mutasiantaropd,
                        b.ket_reklas,
                        a.reklas,
                        b.ket_revaluasi,
                        a.revaluasi,
                        b.ket_koreksi,
                        a.koreksi,
                        b.ket_mutasi_nomenklatur,
                        a.mutasi_nomenklatur,
                        b.ket_hibah1,
                        a.hibah1,
                        b.ket_penghapusan,
                        a.penghapusan1,
                        b.ket_mutasiantaropd1,
                        a.mutasiantaropd1,
                        b.ket_reklas1,
                        a.reklas1
                        ,
                        b.ket_revaluasi1,
                        a.revaluasi1,
                        b.ket_koreksi1,
                        a.koreksi1,
                        b.ket_rusakberat,
                        a.rusakberat,
                        b.ket_beban1,
                        a.beban1,
                        b.ket_mutasi_nomenklatur1,
                        a.mutasi_nomenklatur1 
                    FROM
                        (
                        SELECT
                            b.* 
                        FROM
                            (
                            SELECT
                                a.kd_unit,
                                b.nm_skpd,
                                ISNULL( SUM ( a.sal_lalu ), 0 ) AS sal_lalu,
                                ISNULL( SUM ( a.RealisasiBelanjaModal ), 0 ) AS realisasibelanjamodal,
                                isnull( SUM ( a.hibah ), 0 ) AS hibah,
                                isnull( SUM ( a.Beban ), 0 ) AS beban,
                                isnull( SUM ( a.mutasiantaropd ), 0 ) AS mutasiantaropd,
                                isnull( SUM ( a.reklas ), 0 ) AS reklas,
                                isnull( SUM ( a.revaluasi ), 0 ) AS revaluasi,
                                isnull( SUM ( a.koreksi ), 0 ) AS koreksi,
                                isnull( SUM ( a.mutasi_nomenklatur ), 0 ) AS mutasi_nomenklatur,
                                isnull( SUM ( a.hibah1 ), 0 ) AS hibah1,
                                isnull( SUM ( a.Penghapusan1 ), 0 ) AS penghapusan1,
                                isnull( SUM ( a.mutasiantaropd1 ), 0 ) AS mutasiantaropd1,
                                isnull( SUM ( a.Reklas1 ), 0 ) AS reklas1,
                                isnull( SUM ( a.revaluasi1 ), 0 ) AS revaluasi1,
                                isnull( SUM ( a.koreksi1 ), 0 ) AS koreksi1,
                                isnull( SUM ( a.rusakberat ), 0 ) AS rusakberat,
                                isnull( SUM ( a.Beban1 ), 0 ) AS beban1,
                                isnull( SUM ( a.mutasi_nomenklatur1 ), 0 ) AS mutasi_nomenklatur1 
                            FROM
                                (
                                SELECT
                                    a.kd_unit,
                                    isnull( SUM ( debet - kredit ), 0 ) sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    trdju_calk a
                                    INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                    AND a.kd_unit= b.kd_skpd 
                                WHERE
                                    LEFT ( a.kd_rek6, 4 ) = '$rek' 
                                    AND YEAR ( tgl_voucher ) <= '$thn_ang_1' 
                                GROUP BY
                                    a.kd_unit UNION ALL
                                SELECT
                                    a.kd_unit,
                                    0 sal_lalu,
                                    isnull( SUM ( debet - kredit ), 0 ) RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur,
                                    0 sal 
                                FROM
                                    trdju_calk a
                                    INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                    AND a.kd_unit= b.kd_skpd 
                                WHERE
                                    $rekbel
                                    AND YEAR ( tgl_voucher ) = '$thn_ang' 
                                GROUP BY
                                    a.kd_unit UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    isnull( SUM ( nilai ), 0 ) hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut22' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    isnull( SUM ( nilai ), 0 ) Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut23' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    isnull( SUM ( nilai ), 0 ) mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut24' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    isnull( SUM ( nilai ), 0 ) reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut25' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    isnull( SUM ( nilai ), 0 ) revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut26' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    isnull( SUM ( nilai ), 0 ) koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut27' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut28' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    isnull( SUM ( nilai ), 0 ) hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut31' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    isnull( SUM ( nilai ), 0 ) Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut32' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    isnull( SUM ( nilai ), 0 ) mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut33' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    isnull( SUM ( nilai ), 0 ) Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut34' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    isnull( SUM ( nilai ), 0 ) revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut35' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    isnull( SUM ( nilai ), 0 ) koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut36' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    isnull( SUM ( nilai ), 0 ) rusakberat,
                                    0 Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut37' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    isnull( SUM ( nilai ), 0 ) Beban1,
                                    0 mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut38' 
                                GROUP BY
                                    kd_skpd UNION ALL
                                SELECT
                                    kd_skpd,
                                    0 sal_lalu,
                                    0 RealisasiBelanjaModal,
                                    0 hibah,
                                    0 Beban,
                                    0 mutasiantaropd,
                                    0 reklas,
                                    0 revaluasi,
                                    0 koreksi,
                                    0 mutasi_nomenklatur,
                                    0 hibah1,
                                    0 Penghapusan1,
                                    0 mutasiantaropd1,
                                    0 Reklas1,
                                    0 revaluasi1,
                                    0 koreksi1,
                                    0 rusakberat,
                                    0 Beban1,
                                    isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur1,
                                    0 sal 
                                FROM
                                    isi_neraca_calk 
                                WHERE
                                    kd_rek = '$rekmut39' 
                                GROUP BY
                                    kd_skpd 
                                ) a
                                INNER JOIN ms_skpd b ON a.kd_unit= b.kd_skpd 
                            GROUP BY
                                a.kd_unit,
                                b.nm_skpd 
                            ) b 
                        ) a
                        LEFT JOIN (
                        SELECT
                            kd_skpd,
                            nm_skpd,
                            (
                            SELECT
                                ket_hibah 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_hibah 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut22' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_hibah,
                            (
                            SELECT
                                ket_beban 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_beban 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek= '$rekmut23' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_beban,
                            (
                            SELECT
                                ket_mutasiantaropd 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_mutasiantaropd 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut24' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_mutasiantaropd,
                            (
                            SELECT
                                ket_reklas 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_reklas 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut25' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_reklas,
                            (
                            SELECT
                                ket_revaluasi 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_revaluasi 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut26' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_revaluasi,
                            (
                            SELECT
                                ket_koreksi 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_koreksi 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut27' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_koreksi,
                            (
                            SELECT
                                ket_mutasi_nomenklatur 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_mutasi_nomenklatur 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut28' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_mutasi_nomenklatur,
                            (
                            SELECT
                                ket_hibah1 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_hibah1 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut31' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_hibah1,
                            (
                            SELECT
                                ket_penghapusan 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_penghapusan 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut32' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_penghapusan,
                            (
                            SELECT
                                ket_mutasiantaropd1 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_mutasiantaropd1 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut33' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_mutasiantaropd1,
                            (
                            SELECT
                                ket_reklas1 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_reklas1 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut34' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_reklas1,
                            (
                            SELECT
                                ket_revaluasi1 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_revaluasi1 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut35' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_revaluasi1,
                            (
                            SELECT
                                ket_koreksi1 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_koreksi1 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut36' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_koreksi1,
                            (
                            SELECT
                                ket_rusakberat 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_rusakberat 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut37' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_rusakberat,
                            (
                            SELECT
                                ket_beban1 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_beban1 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut38' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_beban1,
                            (
                            SELECT
                                ket_mutasi_nomenklatur1 
                            FROM
                                (
                                SELECT
                                    kd_skpd,
                                    isnull(
                                        replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                    '' 
                                ) ket_mutasi_nomenklatur1 
                                FROM
                                    (
                                    SELECT
                                        a.kd_skpd,
                                        STUFF(
                                            (
                                            SELECT
                                                '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                            FROM
                                                isi_neraca_calk b 
                                            WHERE
                                                b.kd_rek = '$rekmut39' 
                                                AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                            ),
                                            1,
                                            1,
                                            '' 
                                        ) ket 
                                    FROM
                                        isi_neraca_calk a 
                                    GROUP BY
                                        a.kd_skpd 
                                    ) a 
                                ) a 
                            WHERE
                                a.kd_skpd= x.kd_skpd 
                            ) ket_mutasi_nomenklatur1 
                        FROM
                            ms_skpd x 
                        ) b ON a.kd_unit= b.kd_skpd 
                    ) b ON a.kd_skpd= b.kd_unit 
                ORDER BY
                    a.kd_skpd");
            }elseif($rek=="1302"){
                $head = "
                    <tr>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">KODE</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">OPD</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">$thn_ang_1</td>
                        <td colspan=\"18\"align=\"center\"bgcolor=\"#CCCCCC\">Mutasi Tambah</td>
                        <td colspan=\"21\"align=\"center\"bgcolor=\"#CCCCCC\">Mutasi Kurang</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">$thn_ang</td>
                        </tr>
                        <tr>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Realisasi Belanja Modal</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Hibah</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Beban</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Antar SKPD</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Reklas</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Revaluasi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Koreksi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Pengadaan dari Belanja Tidak Terduga</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Nomenklatur</td>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Jumlah</td>

                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Hibah</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Penghapusan</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Antar SKPD</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Reklas</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Revaluasi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Koreksi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Rusak Berat</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Beban</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Ekstracomptable</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Nomenklatur</td>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Jumlah</td>
                        </tr>
                        <tr>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>

                        </tr>

                        <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                        <td align=\"center\">5</td>
                        <td align=\"center\">6</td>
                        <td align=\"center\">7</td>
                        <td align=\"center\">8</td>
                        <td align=\"center\">9</td>
                        <td align=\"center\">10</td>
                        <td align=\"center\">11</td>
                        <td align=\"center\">12</td>
                        <td align=\"center\">13</td>
                        <td align=\"center\">14</td>
                        <td align=\"center\">15</td>
                        <td align=\"center\">16</td>
                        <td align=\"center\">17</td>
                        <td align=\"center\">18</td>
                        <td align=\"center\">19</td>
                        <td align=\"center\">20</td>
                        <td align=\"center\">21</td>
                        <td align=\"center\">22</td>
                        <td align=\"center\">23</td>
                        <td align=\"center\">24</td>
                        <td align=\"center\">25</td>
                        <td align=\"center\">26</td>
                        <td align=\"center\">27</td>
                        <td align=\"center\">28</td>
                        <td align=\"center\">29</td>
                        <td align=\"center\">30</td>
                        <td align=\"center\">31</td>
                        <td align=\"center\">32</td>
                        <td align=\"center\">33</td>
                        <td align=\"center\">34</td>
                        <td align=\"center\">35</td>
                        <td align=\"center\">36</td>
                        <td align=\"center\">37</td>
                        <td align=\"center\">38</td>
                        <td align=\"center\">39</td>
                        <td align=\"center\">40</td>
                        <td align=\"center\">41</td>
                        <td align=\"center\">42</td>
                        <td align=\"center\">43</td>
                    </tr>";
                $query = DB::select("SELECT
                        a.kd_skpd,
                        a.nm_skpd,
                        isnull( b.sal_lalu, 0 ) sal_lalu,
                        isnull( b.realisasibelanjamodal, 0 ) realisasibelanjamodal,
                        isnull( b.ket_hibah, '' ) ket_hibah,
                        isnull( b.hibah, 0 ) hibah,
                        isnull( b.ket_beban, '' ) ket_beban,
                        isnull( b.beban, 0 ) beban,
                        isnull( b.ket_mutasiantaropd, '' ) ket_mutasiantaropd,
                        isnull( b.mutasiantaropd, 0 ) mutasiantaropd,
                        isnull( b.ket_reklas, '' ) ket_reklas,
                        isnull( b.reklas, 0 ) reklas,
                        isnull( b.ket_revaluasi, '' ) ket_revaluasi,
                        isnull( b.revaluasi, 0 ) revaluasi,
                        isnull( b.ket_koreksi, '' ) ket_koreksi,
                        isnull( b.koreksi, 0 ) koreksi,
                        isnull( b.ket_pengadaan_btt, '' ) ket_pengadaan_btt,
                        isnull( b.pengadaan_btt, 0 ) pengadaan_btt,
                        isnull( b.ket_mutasi_nomenklatur, '' ) ket_mutasi_nomenklatur,
                        isnull( b.mutasi_nomenklatur, 0 ) mutasi_nomenklatur,
                        isnull( b.ket_hibah1, '' ) ket_hibah1,
                        isnull( b.hibah1, 0 ) hibah1,
                        isnull( b.ket_penghapusan, '' ) ket_penghapusan,
                        isnull( b.penghapusan1, 0 ) penghapusan1,
                        isnull( b.ket_mutasiantaropd1, '' ) ket_mutasiantaropd1,
                        isnull( b.mutasiantaropd1, 0 ) mutasiantaropd1,
                        isnull( b.ket_reklas1, '' ) ket_reklas1,
                        isnull( b.reklas1, 0 ) reklas1,
                        isnull( b.ket_revaluasi1, '' ) ket_revaluasi1,
                        isnull( b.revaluasi1, 0 ) revaluasi1,
                        isnull( b.ket_koreksi1, '' ) ket_koreksi1,
                        isnull( b.koreksi1, 0 ) koreksi1,
                        isnull( b.ket_rusakberat, '' ) ket_rusakberat,
                        isnull( b.rusakberat, 0 ) rusakberat,
                        isnull( b.ket_beban1, '' ) ket_beban1,
                        isnull( b.beban1, 0 ) beban1,
                        isnull( b.ket_Ekstracomptable, '' ) ket_Ekstracomptable,
                        isnull( b.Ekstracomptable, 0 ) Ekstracomptable,
                        isnull( b.ket_mutasi_nomenklatur1, '' ) ket_mutasi_nomenklatur1,
                        isnull( b.mutasi_nomenklatur1, 0 ) mutasi_nomenklatur1 
                    FROM
                        ms_skpd a
                        LEFT JOIN (
                        SELECT
                            a.kd_unit,
                            a.nm_skpd,
                            a.sal_lalu,
                            a.realisasibelanjamodal,
                            b.ket_hibah,
                            a.hibah,
                            b.ket_beban,
                            a.beban,
                            b.ket_mutasiantaropd,
                            a.mutasiantaropd,
                            b.ket_reklas,
                            a.reklas,
                            b.ket_revaluasi,
                            a.revaluasi,
                            b.ket_koreksi,
                            a.koreksi,
                            b.ket_pengadaan_btt,
                            a.pengadaan_btt,
                            b.ket_mutasi_nomenklatur,
                            a.mutasi_nomenklatur,
                            b.ket_hibah1,
                            a.hibah1,
                            b.ket_penghapusan,
                            a.penghapusan1,
                            b.ket_mutasiantaropd1,
                            a.mutasiantaropd1,
                            b.ket_reklas1,
                            a.reklas1
                            ,
                            b.ket_revaluasi1,
                            a.revaluasi1,
                            b.ket_koreksi1,
                            a.koreksi1,
                            b.ket_rusakberat,
                            a.rusakberat,
                            b.ket_beban1,
                            a.beban1,
                            b.ket_Ekstracomptable,
                            a.Ekstracomptable,
                            b.ket_mutasi_nomenklatur1,
                            a.mutasi_nomenklatur1 
                        FROM
                            (
                            SELECT
                                b.* 
                            FROM
                                (
                                SELECT
                                    a.kd_unit,
                                    b.nm_skpd,
                                    ISNULL( SUM ( a.sal_lalu ), 0 ) AS sal_lalu,
                                    ISNULL( SUM ( a.RealisasiBelanjaModal ), 0 ) AS realisasibelanjamodal,
                                    isnull( SUM ( a.hibah ), 0 ) AS hibah,
                                    isnull( SUM ( a.Beban ), 0 ) AS beban,
                                    isnull( SUM ( a.mutasiantaropd ), 0 ) AS mutasiantaropd,
                                    isnull( SUM ( a.reklas ), 0 ) AS reklas,
                                    isnull( SUM ( a.revaluasi ), 0 ) AS revaluasi,
                                    isnull( SUM ( a.koreksi ), 0 ) AS koreksi,
                                    isnull( SUM ( a.pengadaan_btt ), 0 ) AS pengadaan_btt,
                                    isnull( SUM ( a.mutasi_nomenklatur ), 0 ) AS mutasi_nomenklatur,
                                    isnull( SUM ( a.hibah1 ), 0 ) AS hibah1,
                                    isnull( SUM ( a.Penghapusan1 ), 0 ) AS penghapusan1,
                                    isnull( SUM ( a.mutasiantaropd1 ), 0 ) AS mutasiantaropd1,
                                    isnull( SUM ( a.Reklas1 ), 0 ) AS reklas1,
                                    isnull( SUM ( a.revaluasi1 ), 0 ) AS revaluasi1,
                                    isnull( SUM ( a.koreksi1 ), 0 ) AS koreksi1,
                                    isnull( SUM ( a.rusakberat ), 0 ) AS rusakberat,
                                    isnull( SUM ( a.Beban1 ), 0 ) AS beban1,
                                    isnull( SUM ( a.Ekstracomptable ), 0 ) AS Ekstracomptable,
                                    isnull( SUM ( a.mutasi_nomenklatur1 ), 0 ) AS mutasi_nomenklatur1 
                                FROM
                                    (
                                    SELECT
                                        a.kd_unit,
                                        isnull( SUM ( debet - kredit ), 0 ) sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        trdju_calk a
                                        INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                        AND a.kd_unit= b.kd_skpd 
                                    WHERE
                                        LEFT ( a.kd_rek6, 4 ) = '1302' 
                                        AND YEAR ( tgl_voucher ) <= '$thn_ang_1' 
                                    GROUP BY
                                        a.kd_unit UNION ALL
                                    SELECT
                                        a.kd_unit,
                                        0 sal_lalu,
                                        isnull( SUM ( debet - kredit ), 0 ) RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        trdju_calk a
                                        INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                        AND a.kd_unit= b.kd_skpd 
                                    WHERE
                                        LEFT ( a.kd_rek6, 4 ) = '5202' 
                                        AND YEAR ( tgl_voucher ) = '$thn_ang' 
                                    GROUP BY
                                        a.kd_unit UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        isnull( SUM ( nilai ), 0 ) hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13222' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        isnull( SUM ( nilai ), 0 ) Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13223' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        isnull( SUM ( nilai ), 0 ) mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13224' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        isnull( SUM ( nilai ), 0 ) reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13225' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        isnull( SUM ( nilai ), 0 ) revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13226' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        isnull( SUM ( nilai ), 0 ) koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13227' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        isnull( SUM ( nilai ), 0 ) pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13228' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13229' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        isnull( SUM ( nilai ), 0 ) hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13231' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        isnull( SUM ( nilai ), 0 ) Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13232' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        isnull( SUM ( nilai ), 0 ) mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13233' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        isnull( SUM ( nilai ), 0 ) Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13234' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        isnull( SUM ( nilai ), 0 ) revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13235' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        isnull( SUM ( nilai ), 0 ) koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13236' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        isnull( SUM ( nilai ), 0 ) rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13237' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        isnull( SUM ( nilai ), 0 ) Beban1,
                                        0 Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13238' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        isnull( SUM ( nilai ), 0 ) Ekstracomptable,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13239' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 Ekstracomptable,
                                        isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '132310' 
                                    GROUP BY
                                        kd_skpd 
                                    ) a
                                    INNER JOIN ms_skpd b ON a.kd_unit= b.kd_skpd 
                                GROUP BY
                                    a.kd_unit,
                                    b.nm_skpd 
                                ) b 
                            ) a
                            LEFT JOIN (
                            SELECT
                                kd_skpd,
                                nm_skpd,
                                (
                                SELECT
                                    ket_hibah 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_hibah 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13222' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_hibah,
                                (
                                SELECT
                                    ket_beban 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_beban 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek= '13223' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_beban,
                                (
                                SELECT
                                    ket_mutasiantaropd 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasiantaropd 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13224' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasiantaropd,
                                (
                                SELECT
                                    ket_reklas 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_reklas 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13225' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_reklas,
                                (
                                SELECT
                                    ket_revaluasi 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_revaluasi 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13226' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_revaluasi,
                                (
                                SELECT
                                    ket_koreksi 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_koreksi 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13227' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_koreksi,
                                (
                                SELECT
                                    ket_pengadaan_btt 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_pengadaan_btt 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13228' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_pengadaan_btt,
                                (
                                SELECT
                                    ket_mutasi_nomenklatur 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasi_nomenklatur 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13228' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasi_nomenklatur,
                                (
                                SELECT
                                    ket_hibah1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_hibah1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13231' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_hibah1,
                                (
                                SELECT
                                    ket_penghapusan 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_penghapusan 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13232' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_penghapusan,
                                (
                                SELECT
                                    ket_mutasiantaropd1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasiantaropd1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13233' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasiantaropd1,
                                (
                                SELECT
                                    ket_reklas1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_reklas1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13234' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_reklas1,
                                (
                                SELECT
                                    ket_revaluasi1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_revaluasi1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13235' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_revaluasi1,
                                (
                                SELECT
                                    ket_koreksi1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_koreksi1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13236' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_koreksi1,
                                (
                                SELECT
                                    ket_rusakberat 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_rusakberat 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13237' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_rusakberat,
                                (
                                SELECT
                                    ket_beban1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_beban1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13238' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_beban1,
                                (
                                SELECT
                                    ket_Ekstracomptable 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_Ekstracomptable 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13239' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_Ekstracomptable,
                                (
                                SELECT
                                    ket_mutasi_nomenklatur1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasi_nomenklatur1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '132310' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasi_nomenklatur1 
                            FROM
                                ms_skpd x 
                            ) b ON a.kd_unit= b.kd_skpd 
                        ) b ON a.kd_skpd= b.kd_unit 
                    ORDER BY
                        a.kd_skpd");
            }elseif($rek=="1303"){
                $head = "
                    <tr>
                        <td rowspan=\"3\"align=\"center\" bgcolor=\"#CCCCCC\">KODE</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">OPD</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">$thn_ang_1</td>
                        <td colspan=\"18\"align=\"center\"bgcolor=\"#CCCCCC\">Mutasi Tambah</td>
                        <td colspan=\"19\"align=\"center\"bgcolor=\"#CCCCCC\">Mutasi Kurang</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">$thn_ang</td>
                        </tr>

                        <tr>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Realisasi Belanja Modal</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Hibah</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Beban</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Antar SKPD</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Reklas</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Revaluasi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Koreksi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Pengadaan dari Belanja Tidak Terduga</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Nomenklatur</td>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Jumlah</td>

                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Hibah</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Penghapusan</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Antar SKPD</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Reklas</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Revaluasi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Koreksi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Rusak Berat</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Beban</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Nomenklatur</td>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Jumlah</td>
                        </tr>

                        <tr>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                                    

                        </tr>
                        <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                        <td align=\"center\">5</td>
                        <td align=\"center\">6</td>
                        <td align=\"center\">7</td>
                        <td align=\"center\">8</td>
                        <td align=\"center\">9</td>
                        <td align=\"center\">10</td>
                        <td align=\"center\">11</td>
                        <td align=\"center\">12</td>
                        <td align=\"center\">13</td>
                        <td align=\"center\">14</td>
                        <td align=\"center\">15</td>
                        <td align=\"center\">16</td>
                        <td align=\"center\">17</td>
                        <td align=\"center\">18</td>
                        <td align=\"center\">19</td>
                        <td align=\"center\">20</td>
                        <td align=\"center\">21</td>
                        <td align=\"center\">22</td>
                        <td align=\"center\">23</td>
                        <td align=\"center\">24</td>
                        <td align=\"center\">25</td>
                        <td align=\"center\">26</td>
                        <td align=\"center\">27</td>
                        <td align=\"center\">28</td>
                        <td align=\"center\">29</td>
                        <td align=\"center\">30</td>
                        <td align=\"center\">31</td>
                        <td align=\"center\">32</td>
                        <td align=\"center\">33</td>
                        <td align=\"center\">34</td>
                        <td align=\"center\">35</td>
                        <td align=\"center\">36</td>
                        <td align=\"center\">37</td>
                        <td align=\"center\">38</td>
                        <td align=\"center\">39</td>
                        <td align=\"center\">40</td>
                        <td align=\"center\">41</td>
                        
                    </tr>";
                $query = DB::select("SELECT
                        a.kd_skpd,
                        a.nm_skpd,
                        isnull( b.sal_lalu, 0 ) sal_lalu,
                        isnull( b.realisasibelanjamodal, 0 ) realisasibelanjamodal,
                        isnull( b.ket_hibah, '' ) ket_hibah,
                        isnull( b.hibah, 0 ) hibah,
                        isnull( b.ket_beban, '' ) ket_beban,
                        isnull( b.beban, 0 ) beban,
                        isnull( b.ket_mutasiantaropd, '' ) ket_mutasiantaropd,
                        isnull( b.mutasiantaropd, 0 ) mutasiantaropd,
                        isnull( b.ket_reklas, '' ) ket_reklas,
                        isnull( b.reklas, 0 ) reklas,
                        isnull( b.ket_revaluasi, '' ) ket_revaluasi,
                        isnull( b.revaluasi, 0 ) revaluasi,
                        isnull( b.ket_koreksi, '' ) ket_koreksi,
                        isnull( b.koreksi, 0 ) koreksi,
                        isnull( b.ket_pengadaan_btt, '' ) ket_pengadaan_btt,
                        isnull( b.pengadaan_btt, 0 ) pengadaan_btt,
                        isnull( b.ket_mutasi_nomenklatur, '' ) ket_mutasi_nomenklatur,
                        isnull( b.mutasi_nomenklatur, 0 ) mutasi_nomenklatur,
                        isnull( b.ket_hibah1, '' ) ket_hibah1,
                        isnull( b.hibah1, 0 ) hibah1,
                        isnull( b.ket_penghapusan, '' ) ket_penghapusan,
                        isnull( b.penghapusan1, 0 ) penghapusan1,
                        isnull( b.ket_mutasiantaropd1, '' ) ket_mutasiantaropd1,
                        isnull( b.mutasiantaropd1, 0 ) mutasiantaropd1,
                        isnull( b.ket_reklas1, '' ) ket_reklas1,
                        isnull( b.reklas1, 0 ) reklas1,
                        isnull( b.ket_revaluasi1, '' ) ket_revaluasi1,
                        isnull( b.revaluasi1, 0 ) revaluasi1,
                        isnull( b.ket_koreksi1, '' ) ket_koreksi1,
                        isnull( b.koreksi1, 0 ) koreksi1,
                        isnull( b.ket_rusakberat, '' ) ket_rusakberat,
                        isnull( b.rusakberat, 0 ) rusakberat,
                        isnull( b.ket_beban1, '' ) ket_beban1,
                        isnull( b.beban1, 0 ) beban1,
                        isnull( b.ket_mutasi_nomenklatur1, '' ) ket_mutasi_nomenklatur1,
                        isnull( b.mutasi_nomenklatur1, 0 ) mutasi_nomenklatur1 
                    FROM
                        ms_skpd a
                        LEFT JOIN (
                        SELECT
                            a.kd_unit,
                            a.nm_skpd,
                            a.sal_lalu,
                            a.realisasibelanjamodal,
                            b.ket_hibah,
                            a.hibah,
                            b.ket_beban,
                            a.beban,
                            b.ket_mutasiantaropd,
                            a.mutasiantaropd,
                            b.ket_reklas,
                            a.reklas,
                            b.ket_revaluasi,
                            a.revaluasi,
                            b.ket_koreksi,
                            a.koreksi,
                            b.ket_pengadaan_btt,
                            a.pengadaan_btt,
                            b.ket_mutasi_nomenklatur,
                            a.mutasi_nomenklatur,
                            b.ket_hibah1,
                            a.hibah1,
                            b.ket_penghapusan,
                            a.penghapusan1,
                            b.ket_mutasiantaropd1,
                            a.mutasiantaropd1,
                            b.ket_reklas1,
                            a.reklas1
                            ,
                            b.ket_revaluasi1,
                            a.revaluasi1,
                            b.ket_koreksi1,
                            a.koreksi1,
                            b.ket_rusakberat,
                            a.rusakberat,
                            b.ket_beban1,
                            a.beban1
                            ,
                            b.ket_mutasi_nomenklatur1,
                            a.mutasi_nomenklatur1 
                        FROM
                            (
                            SELECT
                                b.* 
                            FROM
                                (
                                SELECT
                                    a.kd_unit,
                                    b.nm_skpd,
                                    ISNULL( SUM ( a.sal_lalu ), 0 ) AS sal_lalu,
                                    ISNULL( SUM ( a.RealisasiBelanjaModal ), 0 ) AS realisasibelanjamodal,
                                    isnull( SUM ( a.hibah ), 0 ) AS hibah,
                                    isnull( SUM ( a.Beban ), 0 ) AS beban,
                                    isnull( SUM ( a.mutasiantaropd ), 0 ) AS mutasiantaropd,
                                    isnull( SUM ( a.reklas ), 0 ) AS reklas,
                                    isnull( SUM ( a.revaluasi ), 0 ) AS revaluasi,
                                    isnull( SUM ( a.koreksi ), 0 ) AS koreksi,
                                    isnull( SUM ( a.pengadaan_btt ), 0 ) AS pengadaan_btt,
                                    isnull( SUM ( a.mutasi_nomenklatur ), 0 ) AS mutasi_nomenklatur,
                                    isnull( SUM ( a.hibah1 ), 0 ) AS hibah1,
                                    isnull( SUM ( a.Penghapusan1 ), 0 ) AS penghapusan1,
                                    isnull( SUM ( a.mutasiantaropd1 ), 0 ) AS mutasiantaropd1,
                                    isnull( SUM ( a.Reklas1 ), 0 ) AS reklas1,
                                    isnull( SUM ( a.revaluasi1 ), 0 ) AS revaluasi1,
                                    isnull( SUM ( a.koreksi1 ), 0 ) AS koreksi1,
                                    isnull( SUM ( a.rusakberat ), 0 ) AS rusakberat,
                                    isnull( SUM ( a.Beban1 ), 0 ) AS beban1,
                                    isnull( SUM ( a.mutasi_nomenklatur1 ), 0 ) AS mutasi_nomenklatur1 
                                FROM
                                    (
                                    SELECT
                                        a.kd_unit,
                                        isnull( SUM ( debet - kredit ), 0 ) sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        trdju_calk a
                                        INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                        AND a.kd_unit= b.kd_skpd 
                                    WHERE
                                        LEFT ( a.kd_rek6, 4 ) = '1303' 
                                        AND YEAR ( tgl_voucher ) <= '$thn_ang_1' 
                                    GROUP BY
                                        a.kd_unit UNION ALL
                                    SELECT
                                        a.kd_unit,
                                        0 sal_lalu,
                                        isnull( SUM ( debet - kredit ), 0 ) RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        trdju_calk a
                                        INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                        AND a.kd_unit= b.kd_skpd 
                                    WHERE
                                        LEFT ( a.kd_rek6, 4 ) = '5203' 
                                        AND YEAR ( tgl_voucher ) = '$thn_ang' 
                                    GROUP BY
                                        a.kd_unit UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        isnull( SUM ( nilai ), 0 ) hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13322' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        isnull( SUM ( nilai ), 0 ) Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13323' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        isnull( SUM ( nilai ), 0 ) mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13324' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        isnull( SUM ( nilai ), 0 ) reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13325' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        isnull( SUM ( nilai ), 0 ) revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13326' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        isnull( SUM ( nilai ), 0 ) koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13327' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        isnull( SUM ( nilai ), 0 ) pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13328' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13329' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        isnull( SUM ( nilai ), 0 ) hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13331' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        isnull( SUM ( nilai ), 0 ) Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13332' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        isnull( SUM ( nilai ), 0 ) mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13333' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        isnull( SUM ( nilai ), 0 ) Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13334' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        isnull( SUM ( nilai ), 0 ) revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13335' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        isnull( SUM ( nilai ), 0 ) koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13336' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        isnull( SUM ( nilai ), 0 ) rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13337' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        isnull( SUM ( nilai ), 0 ) Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13338' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 pengadaan_btt,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '13339' 
                                    GROUP BY
                                        kd_skpd 
                                    ) a
                                    INNER JOIN ms_skpd b ON a.kd_unit= b.kd_skpd 
                                GROUP BY
                                    a.kd_unit,
                                    b.nm_skpd 
                                ) b 
                            ) a
                            LEFT JOIN (
                            SELECT
                                kd_skpd,
                                nm_skpd,
                                (
                                SELECT
                                    ket_hibah 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_hibah 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13322' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_hibah,
                                (
                                SELECT
                                    ket_beban 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_beban 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek= '13323' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_beban,
                                (
                                SELECT
                                    ket_mutasiantaropd 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasiantaropd 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13324' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasiantaropd,
                                (
                                SELECT
                                    ket_reklas 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_reklas 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13325' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_reklas,
                                (
                                SELECT
                                    ket_revaluasi 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_revaluasi 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13326' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_revaluasi,
                                (
                                SELECT
                                    ket_koreksi 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_koreksi 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13327' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_koreksi,
                                (
                                SELECT
                                    ket_pengadaan_btt 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_pengadaan_btt 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13328' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_pengadaan_btt,
                                (
                                SELECT
                                    ket_mutasi_nomenklatur 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasi_nomenklatur 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13329' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasi_nomenklatur,
                                (
                                SELECT
                                    ket_hibah1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_hibah1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13331' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_hibah1,
                                (
                                SELECT
                                    ket_penghapusan 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_penghapusan 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13332' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_penghapusan,
                                (
                                SELECT
                                    ket_mutasiantaropd1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasiantaropd1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13333' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasiantaropd1,
                                (
                                SELECT
                                    ket_reklas1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_reklas1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13334' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_reklas1,
                                (
                                SELECT
                                    ket_revaluasi1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_revaluasi1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13335' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_revaluasi1,
                                (
                                SELECT
                                    ket_koreksi1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_koreksi1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13336' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_koreksi1,
                                (
                                SELECT
                                    ket_rusakberat 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_rusakberat 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13337' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_rusakberat,
                                (
                                SELECT
                                    ket_beban1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_beban1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13338' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_beban1,
                                (
                                SELECT
                                    ket_mutasi_nomenklatur1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasi_nomenklatur1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '13339' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasi_nomenklatur1 
                            FROM
                                ms_skpd x 
                            ) b ON a.kd_unit= b.kd_skpd 
                        ) b ON a.kd_skpd= b.kd_unit 
                    ORDER BY
                        a.kd_skpd");
            }elseif($rek=="1503"){
                $head = "
                    <tr>
                        <td rowspan=\"3\"align=\"center\" bgcolor=\"#CCCCCC\">KODE</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">OPD</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">$thn_ang_1</td>
                        <td colspan=\"16\"align=\"center\"bgcolor=\"#CCCCCC\">Mutasi Tambah</td>
                        <td colspan=\"19\"align=\"center\"bgcolor=\"#CCCCCC\">Mutasi Kurang</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">$thn_ang</td>
                        </tr>

                        <tr>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Realisasi Belanja Modal</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Hibah</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Beban</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Antar SKPD</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Reklas</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Revaluasi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Koreksi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Nomenklatur</td>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Jumlah</td>

                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Hibah</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Penghapusan</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Antar SKPD</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Reklas</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Revaluasi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Koreksi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Rusak Berat</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Beban</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Nomenklatur</td>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Jumlah</td>
                        </tr>

                        <tr>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                                    

                        </tr>
                        <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                        <td align=\"center\">5</td>
                        <td align=\"center\">6</td>
                        <td align=\"center\">7</td>
                        <td align=\"center\">8</td>
                        <td align=\"center\">9</td>
                        <td align=\"center\">10</td>
                        <td align=\"center\">11</td>
                        <td align=\"center\">12</td>
                        <td align=\"center\">13</td>
                        <td align=\"center\">14</td>
                        <td align=\"center\">15</td>
                        <td align=\"center\">16</td>
                        <td align=\"center\">17</td>
                        <td align=\"center\">18</td>
                        <td align=\"center\">19</td>
                        <td align=\"center\">20</td>
                        <td align=\"center\">21</td>
                        <td align=\"center\">22</td>
                        <td align=\"center\">23</td>
                        <td align=\"center\">24</td>
                        <td align=\"center\">25</td>
                        <td align=\"center\">26</td>
                        <td align=\"center\">27</td>
                        <td align=\"center\">28</td>
                        <td align=\"center\">29</td>
                        <td align=\"center\">30</td>
                        <td align=\"center\">31</td>
                        <td align=\"center\">32</td>
                        <td align=\"center\">33</td>
                        <td align=\"center\">34</td>
                        <td align=\"center\">35</td>
                        <td align=\"center\">36</td>
                        <td align=\"center\">37</td>
                        <td align=\"center\">38</td>
                        <td align=\"center\">39</td>
                    </tr>";
                $query = DB::select("SELECT
                        a.kd_skpd,
                        a.nm_skpd,
                        isnull( b.sal_lalu, 0 ) sal_lalu,
                        isnull( b.realisasibelanjamodal, 0 ) realisasibelanjamodal,
                        isnull( b.ket_hibah, '' ) ket_hibah,
                        isnull( b.hibah, 0 ) hibah,
                        isnull( b.ket_beban, '' ) ket_beban,
                        isnull( b.beban, 0 ) beban,
                        isnull( b.ket_mutasiantaropd, '' ) ket_mutasiantaropd,
                        isnull( b.mutasiantaropd, 0 ) mutasiantaropd,
                        isnull( b.ket_reklas, '' ) ket_reklas,
                        isnull( b.reklas, 0 ) reklas,
                        isnull( b.ket_revaluasi, '' ) ket_revaluasi,
                        isnull( b.revaluasi, 0 ) revaluasi,
                        isnull( b.ket_koreksi, '' ) ket_koreksi,
                        isnull( b.koreksi, 0 ) koreksi,
                        isnull( b.ket_mutasi_nomenklatur, '' ) ket_mutasi_nomenklatur,
                        isnull( b.mutasi_nomenklatur, 0 ) mutasi_nomenklatur,
                        isnull( b.ket_hibah1, '' ) ket_hibah1,
                        isnull( b.hibah1, 0 ) hibah1,
                        isnull( b.ket_penghapusan, '' ) ket_penghapusan,
                        isnull( b.penghapusan1, 0 ) penghapusan1,
                        isnull( b.ket_mutasiantaropd1, '' ) ket_mutasiantaropd1,
                        isnull( b.mutasiantaropd1, 0 ) mutasiantaropd1,
                        isnull( b.ket_reklas1, '' ) ket_reklas1,
                        isnull( b.reklas1, 0 ) reklas1,
                        isnull( b.ket_revaluasi1, '' ) ket_revaluasi1,
                        isnull( b.revaluasi1, 0 ) revaluasi1,
                        isnull( b.ket_koreksi1, '' ) ket_koreksi1,
                        isnull( b.koreksi1, 0 ) koreksi1,
                        isnull( b.ket_rusakberat, '' ) ket_rusakberat,
                        isnull( b.rusakberat, 0 ) rusakberat,
                        isnull( b.ket_beban1, '' ) ket_beban1,
                        isnull( b.beban1, 0 ) beban1,
                        isnull( b.ket_mutasi_nomenklatur1, '' ) ket_mutasi_nomenklatur1,
                        isnull( b.mutasi_nomenklatur1, 0 ) mutasi_nomenklatur1 
                    FROM
                        ms_skpd a
                        LEFT JOIN (
                        SELECT
                            a.kd_unit,
                            a.nm_skpd,
                            a.sal_lalu,
                            a.realisasibelanjamodal,
                            b.ket_hibah,
                            a.hibah,
                            b.ket_beban,
                            a.beban,
                            b.ket_mutasiantaropd,
                            a.mutasiantaropd,
                            b.ket_reklas,
                            a.reklas,
                            b.ket_revaluasi,
                            a.revaluasi,
                            b.ket_koreksi,
                            a.koreksi,
                            b.ket_mutasi_nomenklatur,
                            a.mutasi_nomenklatur,
                            b.ket_hibah1,
                            a.hibah1,
                            b.ket_penghapusan,
                            a.penghapusan1,
                            b.ket_mutasiantaropd1,
                            a.mutasiantaropd1,
                            b.ket_reklas1,
                            a.reklas1
                            ,
                            b.ket_revaluasi1,
                            a.revaluasi1,
                            b.ket_koreksi1,
                            a.koreksi1,
                            b.ket_rusakberat,
                            a.rusakberat,
                            b.ket_beban1,
                            a.beban1 
                            ,
                            b.ket_mutasi_nomenklatur1,
                            a.mutasi_nomenklatur1 
                        FROM
                            (
                            SELECT
                                b.* 
                            FROM
                                (
                                SELECT
                                    a.kd_unit,
                                    b.nm_skpd,
                                    ISNULL( SUM ( a.sal_lalu ), 0 ) AS sal_lalu,
                                    ISNULL( SUM ( a.RealisasiBelanjaModal ), 0 ) AS realisasibelanjamodal,
                                    isnull( SUM ( a.hibah ), 0 ) AS hibah,
                                    isnull( SUM ( a.Beban ), 0 ) AS beban,
                                    isnull( SUM ( a.mutasiantaropd ), 0 ) AS mutasiantaropd,
                                    isnull( SUM ( a.reklas ), 0 ) AS reklas,
                                    isnull( SUM ( a.revaluasi ), 0 ) AS revaluasi,
                                    isnull( SUM ( a.koreksi ), 0 ) AS koreksi,
                                    isnull( SUM ( a.mutasi_nomenklatur ), 0 ) AS mutasi_nomenklatur,
                                    isnull( SUM ( a.hibah1 ), 0 ) AS hibah1,
                                    isnull( SUM ( a.Penghapusan1 ), 0 ) AS penghapusan1,
                                    isnull( SUM ( a.mutasiantaropd1 ), 0 ) AS mutasiantaropd1,
                                    isnull( SUM ( a.Reklas1 ), 0 ) AS reklas1,
                                    isnull( SUM ( a.revaluasi1 ), 0 ) AS revaluasi1,
                                    isnull( SUM ( a.koreksi1 ), 0 ) AS koreksi1,
                                    isnull( SUM ( a.rusakberat ), 0 ) AS rusakberat,
                                    isnull( SUM ( a.Beban1 ), 0 ) AS beban1,
                                    isnull( SUM ( a.mutasi_nomenklatur1 ), 0 ) AS mutasi_nomenklatur1 
                                FROM
                                    (
                                    SELECT
                                        a.kd_unit,
                                        isnull( SUM ( debet - kredit ), 0 ) sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        trdju_calk a
                                        INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                        AND a.kd_unit= b.kd_skpd 
                                    WHERE
                                        LEFT ( kd_rek6, 4 ) IN ( '1503' ) 
                                        AND YEAR ( tgl_voucher ) <= '$thn_ang_1' 
                                    GROUP BY
                                        a.kd_unit UNION ALL
                                    SELECT
                                        a.kd_unit,
                                        0 sal_lalu,
                                        isnull( SUM ( debet - kredit ), 0 ) RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        trdju_calk a
                                        INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                        AND a.kd_unit= b.kd_skpd 
                                        WHERE-- (
                                        LEFT ( a.kd_rek6, 4 ) IN ( '5206' ) -- or left(a.kd_rek6,6)in('520508'))
                                        
                                        AND YEAR ( tgl_voucher ) = '$thn_ang' 
                                    GROUP BY
                                        a.kd_unit UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        isnull( SUM ( nilai ), 0 ) hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15322' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        isnull( SUM ( nilai ), 0 ) Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15323' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        isnull( SUM ( nilai ), 0 ) mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15324' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        isnull( SUM ( nilai ), 0 ) reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15325' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        isnull( SUM ( nilai ), 0 ) revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15326' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        isnull( SUM ( nilai ), 0 ) koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15327' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15328' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        isnull( SUM ( nilai ), 0 ) hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15331' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        isnull( SUM ( nilai ), 0 ) Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15332' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        isnull( SUM ( nilai ), 0 ) mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15333' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        isnull( SUM ( nilai ), 0 ) Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15334' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        isnull( SUM ( nilai ), 0 ) revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15335' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        isnull( SUM ( nilai ), 0 ) koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15336' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        isnull( SUM ( nilai ), 0 ) rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15337' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        isnull( SUM ( nilai ), 0 ) Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15338' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15339' 
                                    GROUP BY
                                        kd_skpd 
                                    ) a
                                    INNER JOIN ms_skpd b ON a.kd_unit= b.kd_skpd 
                                GROUP BY
                                    a.kd_unit,
                                    b.nm_skpd 
                                ) b 
                            ) a
                            LEFT JOIN (
                            SELECT
                                kd_skpd,
                                nm_skpd,
                                (
                                SELECT
                                    ket_hibah 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_hibah 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15322' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_hibah,
                                (
                                SELECT
                                    ket_beban 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_beban 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek= '15323' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_beban,
                                (
                                SELECT
                                    ket_mutasiantaropd 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasiantaropd 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15324' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasiantaropd,
                                (
                                SELECT
                                    ket_reklas 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_reklas 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15325' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_reklas,
                                (
                                SELECT
                                    ket_revaluasi 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_revaluasi 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15326' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_revaluasi,
                                (
                                SELECT
                                    ket_koreksi 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_koreksi 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15327' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_koreksi,
                                (
                                SELECT
                                    ket_mutasi_nomenklatur 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasi_nomenklatur 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15328' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasi_nomenklatur,
                                (
                                SELECT
                                    ket_hibah1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_hibah1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15331' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_hibah1,
                                (
                                SELECT
                                    ket_penghapusan 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_penghapusan 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15332' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_penghapusan,
                                (
                                SELECT
                                    ket_mutasiantaropd1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasiantaropd1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15333' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasiantaropd1,
                                (
                                SELECT
                                    ket_reklas1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_reklas1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15334' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_reklas1,
                                (
                                SELECT
                                    ket_revaluasi1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_revaluasi1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15335' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_revaluasi1,
                                (
                                SELECT
                                    ket_koreksi1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_koreksi1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15336' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_koreksi1,
                                (
                                SELECT
                                    ket_rusakberat 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_rusakberat 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15337' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_rusakberat,
                                (
                                SELECT
                                    ket_beban1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_beban1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15338' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_beban1,
                                (
                                SELECT
                                    ket_mutasi_nomenklatur1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasi_nomenklatur1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15339' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasi_nomenklatur1 
                            FROM
                                ms_skpd x 
                            ) b ON a.kd_unit= b.kd_skpd 
                        ) b ON a.kd_skpd= b.kd_unit 
                    ORDER BY
                        a.kd_skpd");
            }elseif($rek=="1504"){
                $head = "
                    <tr>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">KODE</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">OPD</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">$thn_ang_1</td>
                        <td colspan=\"16\"align=\"center\"bgcolor=\"#CCCCCC\">Mutasi Tambah</td>
                        <td colspan=\"19\"align=\"center\"bgcolor=\"#CCCCCC\">Mutasi Kurang</td>
                        <td rowspan=\"3\"align=\"center\"bgcolor=\"#CCCCCC\">$thn_ang</td>
                        </tr>
                        <tr>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Realisasi Belanja Modal</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Hibah</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Beban</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Antar SKPD</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Reklas</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Revaluasi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Koreksi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Nomenklatur</td>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Jumlah</td>

                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Hibah</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Penghapusan</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Antar SKPD</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Reklas</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Revaluasi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Koreksi</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Rusak Berat</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Beban</td>
                        <td width=\"3%\" colspan=\"2\"bgcolor=\"#CCCCCC\">Mutasi Nomenklatur</td>
                        <td width=\"3%\" rowspan=\"2\"bgcolor=\"#CCCCCC\">Jumlah</td>
                        </tr>
                        <tr>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">penjelasan</td>
                        <td align=\"center\"bgcolor=\"#CCCCCC\">nilai</td>

                                    

                        </tr>
                        <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                        <td align=\"center\">5</td>
                        <td align=\"center\">6</td>
                        <td align=\"center\">7</td>
                        <td align=\"center\">8</td>
                        <td align=\"center\">9</td>
                        <td align=\"center\">10</td>
                        <td align=\"center\">11</td>
                        <td align=\"center\">12</td>
                        <td align=\"center\">13</td>
                        <td align=\"center\">14</td>
                        <td align=\"center\">15</td>
                        <td align=\"center\">16</td>
                        <td align=\"center\">17</td>
                        <td align=\"center\">18</td>
                        <td align=\"center\">19</td>
                        <td align=\"center\">20</td>
                        <td align=\"center\">21</td>
                        <td align=\"center\">22</td>
                        <td align=\"center\">23</td>
                        <td align=\"center\">24</td>
                        <td align=\"center\">25</td>
                        <td align=\"center\">26</td>
                        <td align=\"center\">27</td>
                        <td align=\"center\">28</td>
                        <td align=\"center\">29</td>
                        <td align=\"center\">30</td>
                        <td align=\"center\">31</td>
                        <td align=\"center\">32</td>
                        <td align=\"center\">33</td>
                        <td align=\"center\">34</td>
                        <td align=\"center\">35</td>
                        <td align=\"center\">36</td>
                        <td align=\"center\">37</td>
                        <td align=\"center\">38</td>
                        <td align=\"center\">39</td>
                    </tr>";
                $query = DB::select("SELECT
                        a.kd_skpd,
                        a.nm_skpd,
                        isnull( b.sal_lalu, 0 ) sal_lalu,
                        isnull( b.realisasibelanjamodal, 0 ) realisasibelanjamodal,
                        isnull( b.ket_hibah, '' ) ket_hibah,
                        isnull( b.hibah, 0 ) hibah,
                        isnull( b.ket_beban, '' ) ket_beban,
                        isnull( b.beban, 0 ) beban,
                        isnull( b.ket_mutasiantaropd, '' ) ket_mutasiantaropd,
                        isnull( b.mutasiantaropd, 0 ) mutasiantaropd,
                        isnull( b.ket_reklas, '' ) ket_reklas,
                        isnull( b.reklas, 0 ) reklas,
                        isnull( b.ket_revaluasi, '' ) ket_revaluasi,
                        isnull( b.revaluasi, 0 ) revaluasi,
                        isnull( b.ket_koreksi, '' ) ket_koreksi,
                        isnull( b.koreksi, 0 ) koreksi,
                        isnull( b.ket_mutasi_nomenklatur, '' ) ket_mutasi_nomenklatur,
                        isnull( b.mutasi_nomenklatur, 0 ) mutasi_nomenklatur,
                        isnull( b.ket_hibah1, '' ) ket_hibah1,
                        isnull( b.hibah1, 0 ) hibah1,
                        isnull( b.ket_penghapusan, '' ) ket_penghapusan,
                        isnull( b.penghapusan1, 0 ) penghapusan1,
                        isnull( b.ket_mutasiantaropd1, '' ) ket_mutasiantaropd1,
                        isnull( b.mutasiantaropd1, 0 ) mutasiantaropd1,
                        isnull( b.ket_reklas1, '' ) ket_reklas1,
                        isnull( b.reklas1, 0 ) reklas1,
                        isnull( b.ket_revaluasi1, '' ) ket_revaluasi1,
                        isnull( b.revaluasi1, 0 ) revaluasi1,
                        isnull( b.ket_koreksi1, '' ) ket_koreksi1,
                        isnull( b.koreksi1, 0 ) koreksi1,
                        isnull( b.ket_rusakberat, '' ) ket_rusakberat,
                        isnull( b.rusakberat, 0 ) rusakberat,
                        isnull( b.ket_beban1, '' ) ket_beban1,
                        isnull( b.beban1, 0 ) beban1,
                        isnull( b.ket_mutasi_nomenklatur1, '' ) ket_mutasi_nomenklatur1,
                        isnull( b.mutasi_nomenklatur1, 0 ) mutasi_nomenklatur1 
                    FROM
                        ms_skpd a
                        LEFT JOIN (
                        SELECT
                            a.kd_unit,
                            a.nm_skpd,
                            a.sal_lalu,
                            a.realisasibelanjamodal,
                            b.ket_hibah,
                            a.hibah,
                            b.ket_beban,
                            a.beban,
                            b.ket_mutasiantaropd,
                            a.mutasiantaropd,
                            b.ket_reklas,
                            a.reklas,
                            b.ket_revaluasi,
                            a.revaluasi,
                            b.ket_koreksi,
                            a.koreksi,
                            b.ket_mutasi_nomenklatur,
                            a.mutasi_nomenklatur,
                            b.ket_hibah1,
                            a.hibah1,
                            b.ket_penghapusan,
                            a.penghapusan1,
                            b.ket_mutasiantaropd1,
                            a.mutasiantaropd1,
                            b.ket_reklas1,
                            a.reklas1
                            ,
                            b.ket_revaluasi1,
                            a.revaluasi1,
                            b.ket_koreksi1,
                            a.koreksi1,
                            b.ket_rusakberat,
                            a.rusakberat,
                            b.ket_beban1,
                            a.beban1 
                            ,
                            b.ket_mutasi_nomenklatur1,
                            a.mutasi_nomenklatur1 
                        FROM
                            (
                            SELECT
                                b.* 
                            FROM
                                (
                                SELECT
                                    a.kd_unit,
                                    b.nm_skpd,
                                    ISNULL( SUM ( a.sal_lalu ), 0 ) AS sal_lalu,
                                    ISNULL( SUM ( a.RealisasiBelanjaModal ), 0 ) AS realisasibelanjamodal,
                                    isnull( SUM ( a.hibah ), 0 ) AS hibah,
                                    isnull( SUM ( a.Beban ), 0 ) AS beban,
                                    isnull( SUM ( a.mutasiantaropd ), 0 ) AS mutasiantaropd,
                                    isnull( SUM ( a.reklas ), 0 ) AS reklas,
                                    isnull( SUM ( a.revaluasi ), 0 ) AS revaluasi,
                                    isnull( SUM ( a.koreksi ), 0 ) AS koreksi,
                                    isnull( SUM ( a.mutasi_nomenklatur ), 0 ) AS mutasi_nomenklatur,
                                    isnull( SUM ( a.hibah1 ), 0 ) AS hibah1,
                                    isnull( SUM ( a.Penghapusan1 ), 0 ) AS penghapusan1,
                                    isnull( SUM ( a.mutasiantaropd1 ), 0 ) AS mutasiantaropd1,
                                    isnull( SUM ( a.Reklas1 ), 0 ) AS reklas1,
                                    isnull( SUM ( a.revaluasi1 ), 0 ) AS revaluasi1,
                                    isnull( SUM ( a.koreksi1 ), 0 ) AS koreksi1,
                                    isnull( SUM ( a.rusakberat ), 0 ) AS rusakberat,
                                    isnull( SUM ( a.Beban1 ), 0 ) AS beban1,
                                    isnull( SUM ( a.mutasi_nomenklatur1 ), 0 ) AS mutasi_nomenklatur1 
                                FROM
                                    (
                                    SELECT
                                        a.kd_unit,
                                        isnull( SUM ( debet - kredit ), 0 ) sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        trdju_calk a
                                        INNER JOIN trhju_calk b ON a.no_voucher= b.no_voucher 
                                        AND a.kd_unit= b.kd_skpd 
                                    WHERE
                                        LEFT ( a.kd_rek6, 4 ) = '1504' 
                                        AND YEAR ( tgl_voucher ) <= '$thn_ang_1' 
                                    GROUP BY
                                        a.kd_unit UNION ALL
                                    SELECT
                                        a.kd_skpd,
                                        0 sal_lalu,
                                        isnull( SUM ( 0 ), 0 ) RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk a 
                                    WHERE
                                        LEFT ( kd_rek, 5 ) = '15421' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        isnull( SUM ( nilai ), 0 ) hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15422' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        isnull( SUM ( nilai ), 0 ) Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15423' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        isnull( SUM ( nilai ), 0 ) mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15424' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        isnull( SUM ( nilai ), 0 ) reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15425' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        isnull( SUM ( nilai ), 0 ) revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15426' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        isnull( SUM ( nilai ), 0 ) koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15427' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15428' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        isnull( SUM ( nilai ), 0 ) hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15431' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        isnull( SUM ( nilai ), 0 ) Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15432' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        isnull( SUM ( nilai ), 0 ) mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15433' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        isnull( SUM ( nilai ), 0 ) Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15434' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        isnull( SUM ( nilai ), 0 ) revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15435' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        isnull( SUM ( nilai ), 0 ) koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15436' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        isnull( SUM ( nilai ), 0 ) rusakberat,
                                        0 Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15437' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        isnull( SUM ( nilai ), 0 ) Beban1,
                                        0 mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15438' 
                                    GROUP BY
                                        kd_skpd UNION ALL
                                    SELECT
                                        kd_skpd,
                                        0 sal_lalu,
                                        0 RealisasiBelanjaModal,
                                        0 hibah,
                                        0 Beban,
                                        0 mutasiantaropd,
                                        0 reklas,
                                        0 revaluasi,
                                        0 koreksi,
                                        0 mutasi_nomenklatur,
                                        0 hibah1,
                                        0 Penghapusan1,
                                        0 mutasiantaropd1,
                                        0 Reklas1,
                                        0 revaluasi1,
                                        0 koreksi1,
                                        0 rusakberat,
                                        0 Beban1,
                                        isnull( SUM ( nilai ), 0 ) mutasi_nomenklatur1,
                                        0 sal 
                                    FROM
                                        isi_neraca_calk 
                                    WHERE
                                        kd_rek = '15439' 
                                    GROUP BY
                                        kd_skpd 
                                    ) a
                                    INNER JOIN ms_skpd b ON a.kd_unit= b.kd_skpd 
                                GROUP BY
                                    a.kd_unit,
                                    b.nm_skpd 
                                ) b 
                            ) a
                            LEFT JOIN (
                            SELECT
                                kd_skpd,
                                nm_skpd,
                                (
                                SELECT
                                    ket_hibah 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_hibah 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15422' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_hibah,
                                (
                                SELECT
                                    ket_beban 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_beban 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek= '15423' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_beban,
                                (
                                SELECT
                                    ket_mutasiantaropd 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasiantaropd 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15424' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasiantaropd,
                                (
                                SELECT
                                    ket_reklas 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_reklas 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15425' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_reklas,
                                (
                                SELECT
                                    ket_revaluasi 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_revaluasi 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15426' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_revaluasi,
                                (
                                SELECT
                                    ket_koreksi 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_koreksi 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15427' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_koreksi,
                                (
                                SELECT
                                    ket_mutasi_nomenklatur 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasi_nomenklatur 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15428' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasi_nomenklatur,
                                (
                                SELECT
                                    ket_hibah1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_hibah1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15431' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_hibah1,
                                (
                                SELECT
                                    ket_penghapusan 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_penghapusan 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15432' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_penghapusan,
                                (
                                SELECT
                                    ket_mutasiantaropd1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasiantaropd1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15433' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasiantaropd1,
                                (
                                SELECT
                                    ket_reklas1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_reklas1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15434' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_reklas1,
                                (
                                SELECT
                                    ket_revaluasi1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_revaluasi1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15435' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_revaluasi1,
                                (
                                SELECT
                                    ket_koreksi1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_koreksi1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15436' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_koreksi1,
                                (
                                SELECT
                                    ket_rusakberat 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_rusakberat 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15437' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_rusakberat,
                                (
                                SELECT
                                    ket_beban1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_beban1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15438' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_beban1,
                                (
                                SELECT
                                    ket_mutasi_nomenklatur1 
                                FROM
                                    (
                                    SELECT
                                        kd_skpd,
                                        isnull(
                                            replace( replace( replace( replace( ket, '/p&gt;', '</p>' ), 'p&gt;', '</p>' ), '&lt;', '<p>' ), ';', '' ),
                                        '' 
                                    ) ket_mutasi_nomenklatur1 
                                    FROM
                                        (
                                        SELECT
                                            a.kd_skpd,
                                            STUFF(
                                                (
                                                SELECT
                                                    '; ' + CAST ( b.ket AS VARCHAR ( 8000 ) ) 
                                                FROM
                                                    isi_neraca_calk b 
                                                WHERE
                                                    b.kd_rek = '15439' 
                                                    AND a.kd_skpd= b.kd_skpd FOR XML PATH ( '' ) 
                                                ),
                                                1,
                                                1,
                                                '' 
                                            ) ket 
                                        FROM
                                            isi_neraca_calk a 
                                        GROUP BY
                                            a.kd_skpd 
                                        ) a 
                                    ) a 
                                WHERE
                                    a.kd_skpd= x.kd_skpd 
                                ) ket_mutasi_nomenklatur1 
                            FROM
                                ms_skpd x 
                            ) b ON a.kd_unit= b.kd_skpd 
                        ) b ON a.kd_skpd= b.kd_unit 
                    ORDER BY
                        a.kd_skpd");
            }
        }

        
        

        // $query = DB::select("SELECT *, (SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=x.kd_skpd) nm_skpd, $rek kd_rek3 FROM (
        //             SELECT 1 jns, a.kd_unit kd_skpd, ISNULL(SUM(CASE WHEN YEAR(tgl_voucher)<=$thn_ang THEN kredit-debet ELSE 0 END),0) nilai, '' penjelasan, '' kd_rinci
        //             FROM $trdju a INNER JOIN $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
        //             WHERE $rek2
        //             GROUP BY a.kd_unit
        //             UNION ALL
        //             SELECT 2 jns, kd_skpd, 0 nilai, ket penjelasan, kd_rinci FROM isi_neraca_calk WHERE kd_rek=$rek) x
        //             WHERE nilai<>0 OR penjelasan NOT LIKE ''
        //             ORDER BY kd_skpd, jns");

        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'format'          => $format,
        'head'          => $head,
        'query'          => $query,
        'rek'          => $rek,
        'format'          => $format,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];


        if ($format=="1") {
            $view =  view('akuntansi.calk_aset.cetakan.penjelasan_calk.penjelasan_calk_tanpa_penjelasan')->with($data);
        }else{
            $view =  view('akuntansi.calk_aset.cetakan.penjelasan_calk.penjelasan_calk_dengan_penjelasan')->with($data);
        }
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('penjelasan_calk.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="penjelasan_calk.xls"');
            return $view;
        }
    }

    public function cetak_lap_beban_penyusutan(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $cetak   = $request->cetak;
        $spasi   = 'line-height:1.5em;';
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;
        
        $query = DB::select("SELECT
                v.kd_skpd,
                ( SELECT nm_skpd FROM trdrka WHERE kd_skpd = v.kd_skpd GROUP BY nm_skpd ) nm_skpd,
                ISNULL( peralatan_mesin, 0 ) AS peralatan_mesin,
                ISNULL( gdg_bangunan, 0 ) AS gdg_bangunan,
                ISNULL( jln_irigas, 0 ) AS jln_irigas,
                ISNULL( atl, 0 ) AS atl,
                ISNULL( aset_lainya, 0 ) AS aset_lainya,
                ISNULL( amortisasi, 0 ) AS amortisasi 
            FROM
                ( SELECT kd_skpd FROM trdrka GROUP BY kd_skpd ) v
                LEFT JOIN (
                SELECT
                    b.kd_unit AS kd_skpd,
                    SUM ( b.debet- b.kredit ) peralatan_mesin 
                FROM
                    trhju_calk a
                    INNER JOIN trdju_calk b ON a.kd_skpd= b.kd_unit 
                    AND a.no_voucher= b.no_voucher 
                WHERE
                    LEFT ( b.kd_rek6, 6 ) IN ( '810801' ) 
                    AND YEAR ( tgl_voucher ) = $thn_ang 
                GROUP BY
                    b.kd_unit 
                ) w ON v.kd_skpd= w.kd_skpd
                LEFT JOIN (
                SELECT
                    b.kd_unit AS kd_skpd,
                    SUM ( b.debet- b.kredit ) gdg_bangunan 
                FROM
                    trhju_calk a
                    INNER JOIN trdju_calk b ON a.kd_skpd= b.kd_unit 
                    AND a.no_voucher= b.no_voucher 
                WHERE
                    LEFT ( b.kd_rek6, 6 ) IN ( '810802' ) 
                    AND YEAR ( tgl_voucher ) = $thn_ang 
                GROUP BY
                    b.kd_unit 
                ) x ON v.kd_skpd= x.kd_skpd
                LEFT JOIN (
                SELECT
                    b.kd_unit AS kd_skpd,
                    SUM ( b.debet- b.kredit ) jln_irigas 
                FROM
                    trhju_calk a
                    INNER JOIN trdju_calk b ON a.kd_skpd= b.kd_unit 
                    AND a.no_voucher= b.no_voucher 
                WHERE
                    LEFT ( b.kd_rek6, 6 ) IN ( '810803' ) 
                    AND YEAR ( tgl_voucher ) = $thn_ang 
                GROUP BY
                    b.kd_unit 
                ) y ON v.kd_skpd= y.kd_skpd
                LEFT JOIN (
                SELECT
                    b.kd_unit AS kd_skpd,
                    SUM ( b.debet- b.kredit ) atl 
                FROM
                    trhju_calk a
                    INNER JOIN trdju_calk b ON a.kd_skpd= b.kd_unit 
                    AND a.no_voucher= b.no_voucher 
                WHERE
                    LEFT ( b.kd_rek6, 6 ) IN ( '810804' ) 
                    AND YEAR ( tgl_voucher ) = $thn_ang 
                GROUP BY
                    b.kd_unit 
                ) qq ON v.kd_skpd= qq.kd_skpd
                LEFT JOIN (
                SELECT
                    b.kd_unit AS kd_skpd,
                    SUM ( b.debet- b.kredit ) aset_lainya 
                FROM
                    trhju_calk a
                    INNER JOIN trdju_calk b ON a.kd_skpd= b.kd_unit 
                    AND a.no_voucher= b.no_voucher 
                WHERE
                    LEFT ( b.kd_rek6, 6 ) IN ( '810805' ) 
                    AND YEAR ( tgl_voucher ) = $thn_ang 
                GROUP BY
                    b.kd_unit 
                ) pp ON v.kd_skpd= pp.kd_skpd
                LEFT JOIN (
                SELECT
                    b.kd_unit AS kd_skpd,
                    SUM ( b.debet- b.kredit ) amortisasi 
                FROM
                    trhju_calk a
                    INNER JOIN trdju_calk b ON a.kd_skpd= b.kd_unit 
                    AND a.no_voucher= b.no_voucher 
                WHERE
                    LEFT ( b.kd_rek6, 4 ) IN ( '810806' ) 
                    AND YEAR ( tgl_voucher ) = $thn_ang 
                GROUP BY
                    b.kd_unit 
                ) z ON v.kd_skpd= z.kd_skpd 
            ORDER BY
                kd_skpd");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

            $view =  view('akuntansi.calk_aset.cetakan.lap_beban_penyusutan.lap_beban_penyusutan')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LAP_beban_penyusutan.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LAP_beban_penyusutan.xls"');
            return $view;
        }
    }

    public function cetak_lap_jaminan_pemeliharaan(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $kd_skpd   = $request->kd_skpd;
        $skpdunit   = $request->skpdunit;
        $cetak   = $request->cetak;
        $spasi   = 'line-height:1.5em;';
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;
        // dd($skpdunit);
        if ($skpdunit=="keseluruhan") {
            $where="";
            $nm_skpd="";
        }else{
            $where=" where a.kd_skpd='$kd_skpd'";
            $nm_skpd=nama_skpd($kd_skpd);
        }
        

        $query = DB::select("SELECT *,(select nm_program from ms_program b where b.kd_program=left(kd_sub_kegiatan,7)) as nm_program
            from isi_lamp_daftar_pemeliharaan a   $where order by a.kd_skpd");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'skpdunit'          => $skpdunit,
        'kd_skpd'          => $kd_skpd,
        'nm_skpd'          => $nm_skpd,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

        $view =  view('akuntansi.calk_aset.cetakan.lap_jaminan_pemeliharaan.lap_jaminan_pemeliharaan')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LAP_jaminan_pemeliharaan.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LAP_jaminan_pemeliharaan.xls"');
            return $view;
        }
    }

    public function cetak_lap_akumulasi_penyusutan(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $rek   = $request->rek;
        $cetak   = $request->cetak;
        $spasi   = 'line-height:1.5em;';
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";
        
        $thn_ang    = tahun_anggaran();
        $thn_ang_1   = $thn_ang-1;
        
        // dd($ket_1307);

        if ($rek=="150501") {
            $kd_penyu= "810806";
            $ket_1307 = "5";
        }elseif($rek=="150601"){
            $kd_penyu= "810807";
            $ket_1307 = "7";
        }else{
            $ket_1307 = substr($rek , 5,1);
            $kd_penyu= "81080".$ket_1307;
        }
        $kd2_1= $ket_1307."1";
        $kd4_111= $ket_1307."111";
        $kd4_112= $ket_1307."112";
        $kd4_113= $ket_1307."113";
        $kd4_114= $ket_1307."114";
        $kd2_2= $ket_1307."2";
        $kd4_211= $ket_1307."211";
        $kd4_212= $ket_1307."212";
        $kd4_213= $ket_1307."213";
        $kd4_214= $ket_1307."214";
        $kd4_215= $ket_1307."215";
        $kd4_216= $ket_1307."216";
        

        $query = DB::select("SELECT a.kd_skpd,(SELECT nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd,
            sal,
            isnull(hibah_tambah,0)hibah_tambah,isnull(mutasi_tambah,0)mutasi_tambah,isnull(reklas_tambah,0)reklas_tambah,isnull(koreksi_tambah,0)koreksi_tambah,
            isnull(jumlah_tambah,0)jumlah_tambah,
            isnull(hibah_kurang,0)hibah_kurang,isnull(mutasi_kurang,0)mutasi_kurang,isnull(reklas_kurang,0)reklas_kurang,isnull(koreksi_kurang,0)koreksi_kurang,isnull(penghapusan_kurang,0)penghapusan_kurang,isnull(rusak_kurang,0)rusak_kurang,
            isnull(jumlah_kurang,0)jumlah_kurang,
            isnull(penyusutan,0)penyusutan,
            (sal+isnull(jumlah_kurang,0)-isnull(jumlah_tambah,0)+isnull(penyusutan,0))total
            from
            (
                select kd_skpd,sum(sal)sal
                from
                (
                    select kd_skpd,0 sal from ms_skpd
                    union all
                    SELECT kd_skpd, sum(debet-kredit) sal
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,6)='$rek' and year(tgl_voucher)<=$thn_ang_1 
                    group by kd_skpd
                )a
                group by kd_skpd
            )a
            left join
            (
                select kd_skpd, isnull(sum(nilai),0) hibah_tambah
                from isi_neraca_calk_baru 
                where  kd_rek2='$kd4_111' 
                group by kd_skpd
            )a1 on a.kd_skpd=a1.kd_skpd
            left join
            (
                select kd_skpd, isnull(sum(nilai),0)mutasi_tambah
                from isi_neraca_calk_baru 
                where  kd_rek2='$kd4_112' 
                group by kd_skpd
            )a2 on a.kd_skpd=a2.kd_skpd
            left join
            (
                select kd_skpd, isnull(sum(nilai),0)reklas_tambah
                from isi_neraca_calk_baru 
                where  kd_rek2='$kd4_113' 
                group by kd_skpd
            )a3 on a.kd_skpd=a3.kd_skpd
            left join
            (
                select kd_skpd, isnull(sum(nilai),0)koreksi_tambah
                from isi_neraca_calk_baru 
                where  kd_rek2='$kd4_114' 
                group by kd_skpd
            )a4 on a.kd_skpd=a4.kd_skpd
            left join
            (
                select kd_skpd, isnull(sum(nilai),0)jumlah_tambah
                from isi_neraca_calk_baru 
                where  kd_rek='$kd2_1' 
                group by kd_skpd
            )a_jum on a.kd_skpd=a_jum.kd_skpd
            left join
            (
                select kd_skpd, isnull(sum(nilai),0) hibah_kurang
                from isi_neraca_calk_baru 
                where  kd_rek2='$kd4_211' 
                group by kd_skpd
            )b1 on a.kd_skpd=b1.kd_skpd
            left join
            (
                select kd_skpd, isnull(sum(nilai),0)mutasi_kurang
                from isi_neraca_calk_baru 
                where  kd_rek2='$kd4_212' 
                group by kd_skpd
            )b2 on a.kd_skpd=b2.kd_skpd
            left join
            (
                select kd_skpd, isnull(sum(nilai),0)reklas_kurang
                from isi_neraca_calk_baru 
                where  kd_rek2='$kd4_213' 
                group by kd_skpd
            )b3 on a.kd_skpd=b3.kd_skpd
            left join
            (
                select kd_skpd, isnull(sum(nilai),0)koreksi_kurang
                from isi_neraca_calk_baru 
                where  kd_rek2='$kd4_214' 
                group by kd_skpd
            )b4 on a.kd_skpd=b4.kd_skpd
            left join
            (
                select kd_skpd, isnull(sum(nilai),0)penghapusan_kurang
                from isi_neraca_calk_baru 
                where  kd_rek2='$kd4_215' 
                group by kd_skpd
            )b5 on a.kd_skpd=b5.kd_skpd
            left join
            (
                select kd_skpd, isnull(sum(nilai),0)rusak_kurang
                from isi_neraca_calk_baru 
                where  kd_rek2='$kd4_216' 
                group by kd_skpd
            )b6 on a.kd_skpd=b6.kd_skpd
            left join
            (
                select kd_skpd, isnull(sum(nilai),0)jumlah_kurang
                from isi_neraca_calk_baru 
                where  kd_rek='$kd2_2' 
                group by kd_skpd
            )b_jum on a.kd_skpd=b_jum.kd_skpd
            left join
            (
                select kd_skpd,  isnull(sum(kredit-debet),0) penyusutan
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,6)='$kd_penyu' and year(tgl_voucher)=$thn_ang and tgl_real in ('',0) 
                group by kd_skpd
            )p on a.kd_skpd=p.kd_skpd
            order by a.kd_skpd");



        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($head);
        
        $data = [
        'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'cetak'          => $cetak,
        'query'          => $query,
        'rek'          => $rek,
        'thn_ang'        => $thn_ang,
        'thn_ang_1'      => $thn_ang_1     
        ];

        $view =  view('akuntansi.calk_aset.cetakan.lap_akumulasi_penyusutan.lap_akumulasi_penyusutan')->with($data);
        


        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LAP_akumulasi_penyusutan.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LAP_akumulasi_penyusutan.xls"');
            return $view;
        }
    }
}
