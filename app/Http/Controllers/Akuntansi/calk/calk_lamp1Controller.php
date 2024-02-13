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


class calk_lamp1Controller extends Controller
{

    public function calklamp1(Request $request)
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

        return view('akuntansi.cetakan.calk.lamp1.edit_lamp1')->with($data);
    }

    function cetak_calk19(Request $request)
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

        // vertical
            $vlra = collect(DB::select("SELECT *, (pendapatan-belanja) surdef,((pendapatan-belanja)-(pendapatan-belanja)) selisih
                FROM(
                    SELECT isnull(SUM(pendapatan),0)pendapatan,isnull(sum(belanja),0)belanja
                    from(
                        SELECT  case when left(kd_rek6,1)='4' then ISNULL(SUM(kredit-debet),0) end pendapatan ,case when left(kd_rek6,1)='5' then ISNULL(SUM(debet-kredit),0) end belanja
                        FROM $trdju a INNER JOIN $trhju b on a.kd_unit=b.kd_skpd AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND LEFT(a.kd_rek6,1) IN ('4','5') AND YEAR(b.tgl_voucher)=$thn_ang
                        group by left(kd_rek6,1)
                    )a
                )a"))->first();

            $vlra_ket = DB::select("SELECT ket,nilai from isi_analisis_calk where $skpd_clause and kd_rek='a11' order by kd_rinci");

            $vneraca = collect(DB::select("SELECT *,(aset-(kewajiban+ekuitas)) selisih FROM(
                SELECT SUM(aset) aset, SUM(kewajiban) kewajiban, SUM(ekuitas) ekuitas 
                FROM (
                    select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) aset, 0 kewajiban, 0 ekuitas
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where LEFT(kd_rek6,1)='1' and $skpd_clause
                    UNION ALL
                    select 0 aset, isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) kewajiban, 0 ekuitas
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where LEFT(kd_rek6,1)='2' and $skpd_clause
                    UNION ALL
                    select 0 aset, 0 kewajiban, ISNULL(sum(sal),0) ekuitas 
                    from (
                        select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal
                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where left(kd_rek6,1) in ('7','8') and $skpd_clause
                        union all
                        select isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal
                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where kd_rek6='310101010001' and tabel='1' and reev='0' and $skpd_clause
                        union all
                        select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal 
                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where kd_rek6='310101010001' and reev in ('1','2','3') and $skpd_clause
                        union all
                        select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal
                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where kd_rek6='310301010001' and $skpd_clause
                    ) a
                ) a
                )a"))->first();

            $vneraca_ket = DB::select("SELECT ket,nilai from isi_analisis_calk where $skpd_clause and kd_rek='a21' order by kd_rinci");

            $vkasben = collect(DB::select("SELECT x.kas_keluar, y.sisa_kas, z.utang_pfk 
                    FROM (
                        select 1 jns, isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) kas_keluar
                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(kd_rek6,12)='110103010001' and $skpd_clause 
                    ) x
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(x.nilai_sp2d-y.sd_bulan_ini-z.nilai_cp-p.nilai+t.nilai,0) sisa_kas 
                        FROM (
                            select 1 jns, ISNULL(SUM(d.nilai),0) as nilai_sp2d FROM trhsp2d a 
                            INNER JOIN trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm
                            INNER JOIN trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp
                            INNER JOIN trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp
                            WHERE $skpd_clause_a AND status_terima='1' 
                            AND MONTH(tgl_terima)<='12' AND LEFT(kd_rek6,1)  in ('5','1')
                            AND (c.sp2d_batal IS NULL  OR c.sp2d_batal !=1)
                        ) x 
                        INNER JOIN
                        (
                            SELECT 1 jns, SUM(debet-kredit) as sd_bulan_ini
                            FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                            WHERE LEFT(b.kd_rek6,1) IN ('5')  and $skpd_clause AND YEAR(a.tgl_voucher) = $thn_ang AND MONTH(a.tgl_voucher)<=12 
                        ) y ON x.jns=y.jns
                        INNER JOIN
                        (
                            SELECT 1 jns, ISNULL(SUM(nilai_cp),0) nilai_cp 
                            FROM (
                                    select SUM(rupiah) as nilai_cp from 
                                    trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd 
                                    where $skpd_clause_d AND jns_trans='5' AND pot_khusus IN ('2','1') AND MONTH(tgl_sts)<='12'
                                    UNION ALL
                                    select SUM(rupiah) as nilai_cp from 
                                    trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd 
                                    where $skpd_clause_d AND ((jns_trans='5' AND pot_khusus='0') OR jns_trans='1') AND MONTH(tgl_sts)<='12'
                                )a
                        ) z
                            ON y.jns=z.jns
                        INNER JOIN
                        (
                            select 1 as jns,sum(q.nilai) nilai 
                            from (
                                --drop dana keluar
                                SELECT  sum(x.nilai) nilai 
                                from(
                                    select kd_skpd_sumber kd_skpd,SUM(nilai) as nilai from tr_setorpelimpahan WHERE MONTH(tgl_kas)<=12 group by kd_skpd_sumber 
                                    UNION ALL
                                    select kd_skpd_sumber kd_skpd,SUM(nilai) as nilai from tr_setorpelimpahan_bank WHERE MONTH(tgl_kas)<=12 group by kd_skpd_sumber
                                    UNION ALL
                                    select kd_skpd_sumber kd_skpd,SUM(nilai) as nilai from tr_setorpelimpahan_tunai WHERE MONTH(tgl_kas)<=12 group by kd_skpd_sumber
                                )x 
                                WHERE $skpd_clause
                                group by kd_skpd
                                UNION ALL
                                --drop dana terima
                                SELECT  sum(x.nilai*-1) nilai  
                                from(
                                    select kd_skpd,SUM(nilai) as nilai from tr_setorpelimpahan WHERE MONTH(tgl_kas)<=12 group by kd_skpd 
                                    UNION ALL
                                    select kd_skpd,SUM(nilai) as nilai from tr_setorpelimpahan_bank WHERE MONTH(tgl_kas)<=12 group by kd_skpd
                                    UNION ALL
                                    SELECT kd_skpd,SUM(nilai) as nilai from tr_setorsimpanan  WHERE jenis='3' and MONTH(tgl_kas)<=12 group by kd_skpd 
                                )x 
                                WHERE $skpd_clause
                                group by kd_skpd
                            ) q
                        ) p on y.jns=p.jns 
                        INNER JOIN 
                        (
                            SELECT 1 as jns,SUM(CASE WHEN MONTH(tgl_bukti)<='12' THEN a.nilai ELSE 0 END) as nilai
                            from trdtransout_blud a inner join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                            WHERE  (kd_satdik<>'1' OR kd_satdik is not null) and $skpd_clause_a
                        ) t on p.jns=t.jns
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        select 1 jns, isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) utang_pfk
                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where left(kd_rek6,4)='2101' and $skpd_clause
                    ) z ON y.jns=z.jns"))->first();
            
            $vkasben_ket = DB::select("SELECT ket,nilai from isi_analisis_calk where $skpd_clause and kd_rek='a22' order by kd_rinci");

            $vlo = collect(DB::select("SELECT a.*,(sur_def-(pend_lo+(beban_lo*-1)+keg_non_op+pos_lb)) selisih
                    from(
                        SELECT 
                        -- x.pend_lo-y.beban_lo 
                        (
                            SELECT sum(pend_lo-beban_lo) sur_def
                            from(
                                select  ISNULL(sum(kredit-debet),0) as pend_lo ,0 beban_lo
                                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=12 and left(kd_rek6,1) in  ('7') and $skpd_clause
                                union all
                                select  0 pend_lo,ISNULL(sum(debet-kredit),0) as beban_lo
                                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=12 and left(kd_rek6,4) in  ('8101','8102','8103','8104','8105','8106','8107','8108','8206','8301','8302','8401') and $skpd_clause
                            ) a
                        )sur_def, 
                        x.pend_lo, y.beban_lo, z.pen_non_op-p.bel_non_op keg_non_op, q.pos_lb 
                        FROM(
                            select 1 jns, ISNULL(sum(kredit-debet),0) as pend_lo 
                            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                            where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=12 and 
                            (left(kd_rek6,4) in 
                                -- ('7') 
                                ('7101','7102','7103','7104','7202','7301','7302','7303') or left(kd_rek6,6) in ('720102','720103','720104','720105') or left(kd_rek6,8) in('72010101','72010102','72010103','72010104')
                            ) and $skpd_clause
                        ) x
                        INNER JOIN
                        (
                            select 1 jns, ISNULL(sum(debet-kredit),0) as beban_lo 
                            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                            where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=12 and 
                            left(kd_rek6,4) in 
                            -- ('8')
                            ('8101','8102','8103','8104','8105','8106','8107','8108','8206','8301','8302','8401') and $skpd_clause
                        ) y ON x.jns=y.jns
                        INNER JOIN
                        (
                            select 1 jns, ISNULL(sum(kredit-debet),0) as pen_non_op 
                            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                            where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=12 and left(kd_rek6,2) in ('74') and $skpd_clause
                        ) z ON y.jns=z.jns
                        INNER JOIN
                        (
                            select 1 jns, ISNULL(sum(debet-kredit),0) as bel_non_op 
                            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                            where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=12 and left(kd_rek6,2) in ('83') and $skpd_clause
                        ) p ON z.jns=p.jns
                        INNER JOIN
                        (
                            SELECT 1 jns, ISNULL(SUM(kredit-debet),0) as pos_lb 
                            FROM $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                            WHERE left(kd_rek6,4) in ('851','941') and year(tgl_voucher)=$thn_ang and $skpd_clause
                        ) q ON p.jns=q.jns
                    )a"))->first();

            $vlo_ket = DB::select("SELECT ket,nilai from isi_analisis_calk where $skpd_clause and kd_rek='a31' order by kd_rinci");

            $vlpe = collect(DB::select("SELECT a.*, eq_akhir-(eq_awal+sur_def+koreksi)selisih
                FROM (
                    SELECT sum(sur_awal+umum_awal+kor_awal) eq_awal,  sum(sur_akhir) sur_def, sum(umum_akhir+kor_akhir) koreksi, sum(sur_awal+umum_awal+kor_awal+sur_akhir+umum_akhir+kor_akhir) eq_akhir 
                    from (
                        select isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sur_awal, 0 umum_awal, 0 kor_awal, 0 sur_akhir, 0 umum_akhir, 0 kor_akhir
                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where left(kd_rek6,1) in ('7','8') and $skpd_clause
                        union all
                        select 0 sur_awal, isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) umum_awal, 0 kor_awal, 0 sur_akhir, 0 umum_akhir, 0 kor_akhir
                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where kd_rek6='310101010001' and tabel='1' and reev='0' and $skpd_clause
                        union all
                        select 0 sur_awal, 0 umum_awal, isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) kor_awal, 0 sur_akhir, 0 umum_akhir, 0 kor_akhir
                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where kd_rek6='310101010001' and reev in ('1','2','3') and $skpd_clause
                        union all
                        select 0 sur_awal, 0 umum_awal, 0 kor_awal, isnull(sum(case when YEAR(tgl_voucher)='$thn_ang' then kredit-debet else 0 end),0) sur_akhir, 0 umum_akhir, 0 kor_akhir
                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where left(kd_rek6,1) in ('7','8') and $skpd_clause
                        union all
                        select 0 sur_awal, 0 umum_awal, 0 kor_awal, 0 sur_akhir, 0 umum_awal, 0 kor_akhir
                        union all
                        select 0 sur_awal, 0 umum_awal, 0 kor_awal, 0 sur_akhir, 0 umum_akhir, isnull(sum(case when YEAR(tgl_voucher)='$thn_ang' then kredit-debet else 0 end),0) kor_akhir
                        from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where kd_rek6='310101010001' and reev in ('1','2','3') and $skpd_clause
                    ) a
                )a"))->first();

            $vlpe_ket = DB::select("SELECT ket,nilai from isi_analisis_calk where $skpd_clause and kd_rek='a41' order by kd_rinci");
        //
        // horizontal 1
            $h_1_tanah = collect(DB::select("SELECT x.real_tanah realisasi, (y.aset_tanah-z.aset_tanah_l)tamkur,y.aset_tanah aset, z.aset_tanah_l aset_lalu ,((y.aset_tanah-z.aset_tanah_l)-x.real_tanah) selisih,r.mutasi
                    FROM
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) real_tanah 
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)=$thn_ang AND LEFT(b.kd_rek6,4)='5201' AND $skpd_clause
                    ) x
                    INNER JOIN
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) aset_tanah
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)<=$thn_ang AND LEFT(b.kd_rek6,4)='1301' AND $skpd_clause
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) aset_tanah_l 
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)<=$thn_ang_1 AND LEFT(b.kd_rek6,4)='1301' AND $skpd_clause
                    ) z ON y.jns=z.jns
                    inner join
                    (
                    SELECT 1 jns ,isnull((sum(nilai))*-1,0) mutasi
                    from(
                    SELECT kd_rek, 
                    case when left(kd_rek,4)='1312' then sum(nilai*-1) else sum(nilai) end  as nilai from isi_neraca_calk where $skpd_clause and left(kd_rek,4) in ('1313','1312') 
                    group by kd_rek)z
                    )r on z.jns=r.jns"))->first();
            
            $h_1_tanah_ket = DB::select("SELECT a.kd_rek,a.nm_rek , ket,nilai
                            from ket_neraca_calk a left join isi_neraca_calk b on a.kd_rek=b.kd_rek
                            where left(a.kd_rek,3)='131' and $skpd_clause and (ket is not null or ket !='') and nilai<>0
                            order by a.kd_rek,nm_rek ");
            
            $h_1_pmesin = collect(DB::select("SELECT x.real realisasi, (y.aset-z.aset_l)tamkur,y.aset aset, z.aset_l aset_lalu ,((y.aset-z.aset_l)-x.real) selisih,r.mutasi
                    FROM
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) real 
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)=$thn_ang AND LEFT(b.kd_rek6,4)='5202' AND $skpd_clause
                    ) x
                    INNER JOIN
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) aset
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)<=$thn_ang AND LEFT(b.kd_rek6,4)='1302' AND $skpd_clause
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) aset_l 
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)<=$thn_ang_1 AND LEFT(b.kd_rek6,4)='1302' AND $skpd_clause
                    ) z ON y.jns=z.jns
                    inner join
                    (
                    SELECT 1 jns ,isnull((sum(nilai))*-1,0) mutasi
                    from(
                    SELECT kd_rek, 
                    case when left(kd_rek,4)='1322' then sum(nilai*-1) else sum(nilai) end  as nilai from isi_neraca_calk where $skpd_clause and left(kd_rek,4) in ('1323','1322') 
                    group by kd_rek)z
                    )r on z.jns=r.jns"))->first();
            
            $h_1_pmesin_ket = DB::select("SELECT a.kd_rek,a.nm_rek , ket,nilai
                            from ket_neraca_calk a left join isi_neraca_calk b on a.kd_rek=b.kd_rek
                            where left(a.kd_rek,3)='132' and $skpd_clause and (ket is not null or ket !='') and nilai<>0
                            order by a.kd_rek,nm_rek ");
            
            $h_1_gbangunan = collect(DB::select("SELECT x.real realisasi, (y.aset-z.aset_l)tamkur,y.aset aset, z.aset_l aset_lalu ,((y.aset-z.aset_l)-x.real) selisih,r.mutasi
                    FROM
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) real 
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)=$thn_ang AND LEFT(b.kd_rek6,4)='5203' AND $skpd_clause
                    ) x
                    INNER JOIN
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) aset
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)<=$thn_ang AND LEFT(b.kd_rek6,4)='1303' AND $skpd_clause
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) aset_l 
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)<=$thn_ang_1 AND LEFT(b.kd_rek6,4)='1303' AND $skpd_clause
                    ) z ON y.jns=z.jns
                    inner join
                    (
                    SELECT 1 jns ,isnull((sum(nilai))*-1,0) mutasi
                    from(
                    SELECT kd_rek, 
                    case when left(kd_rek,4)='1332' then sum(nilai*-1) else sum(nilai) end  as nilai from isi_neraca_calk where $skpd_clause and left(kd_rek,4) in ('1333','1332') 
                    group by kd_rek)z
                    )r on z.jns=r.jns"))->first();
            
            $h_1_gbangunan_ket = DB::select("SELECT a.kd_rek,a.nm_rek , ket,nilai
                            from ket_neraca_calk a left join isi_neraca_calk b on a.kd_rek=b.kd_rek
                            where left(a.kd_rek,3)='133' and $skpd_clause and (ket is not null or ket !='') and nilai<>0
                            order by a.kd_rek,nm_rek ");

            $h_1_jij = collect(DB::select("SELECT x.real realisasi, (y.aset-z.aset_l)tamkur,y.aset aset, z.aset_l aset_lalu ,((y.aset-z.aset_l)-x.real) selisih,r.mutasi
                    FROM
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) real 
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)=$thn_ang AND LEFT(b.kd_rek6,4)='5204' AND $skpd_clause
                    ) x
                    INNER JOIN
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) aset
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)<=$thn_ang AND LEFT(b.kd_rek6,4)='1304' AND $skpd_clause
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) aset_l 
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)<=$thn_ang_1 AND LEFT(b.kd_rek6,4)='1304' AND $skpd_clause
                    ) z ON y.jns=z.jns
                    inner join
                    (
                    SELECT 1 jns ,isnull((sum(nilai))*-1,0) mutasi
                    from(
                    SELECT kd_rek, 
                    case when left(kd_rek,4)='1342' then sum(nilai*-1) else sum(nilai) end  as nilai from isi_neraca_calk where $skpd_clause and left(kd_rek,4) in ('1343','1342') 
                    group by kd_rek)z
                    )r on z.jns=r.jns"))->first();
            
            $h_1_jij_ket = DB::select("SELECT a.kd_rek,a.nm_rek , ket,nilai
                            from ket_neraca_calk a left join isi_neraca_calk b on a.kd_rek=b.kd_rek
                            where left(a.kd_rek,3)='134' and $skpd_clause and (ket is not null or ket !='') and nilai<>0
                            order by a.kd_rek,nm_rek ");

            $h_1_asettl = collect(DB::select("SELECT x.real realisasi, (y.aset-z.aset_l)tamkur,y.aset aset, z.aset_l aset_lalu ,((y.aset-z.aset_l)-x.real) selisih,r.mutasi
                    FROM
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) real 
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)=$thn_ang AND LEFT(b.kd_rek6,4)='5205' AND $skpd_clause
                    ) x
                    INNER JOIN
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) aset
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)<=$thn_ang AND LEFT(b.kd_rek6,4)='1305' AND $skpd_clause
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) aset_l 
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)<=$thn_ang_1 AND LEFT(b.kd_rek6,4)='1305' AND $skpd_clause
                    ) z ON y.jns=z.jns
                    inner join
                    (
                    SELECT 1 jns ,isnull((sum(nilai))*-1,0) mutasi
                    from(
                    SELECT kd_rek, 
                    case when left(kd_rek,4)='1352' then sum(nilai*-1) else sum(nilai) end  as nilai from isi_neraca_calk where $skpd_clause and left(kd_rek,4) in ('1353','1352') 
                    group by kd_rek)z
                    )r on z.jns=r.jns"))->first();
            
            $h_1_asettl_ket = DB::select("SELECT a.kd_rek,a.nm_rek , ket,nilai
                            from ket_neraca_calk a left join isi_neraca_calk b on a.kd_rek=b.kd_rek
                            where left(a.kd_rek,3)='135' and $skpd_clause and (ket is not null or ket !='') and nilai<>0
                            order by a.kd_rek,nm_rek ");

            $h_1_kontruksi = collect(DB::select("SELECT x.real realisasi, (y.aset-z.aset_l)tamkur,y.aset aset, z.aset_l aset_lalu ,((y.aset-z.aset_l)-x.real) selisih,r.mutasi
                    FROM
                    (
                        select jns, sum(real)real
                        from(
                            select 1 jns, 0 real
                            union all
                            select 1 jns, 0 real 
                            from $trhju a inner join $trdju b 
                            on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                            where YEAR(a.tgl_voucher)=$thn_ang AND LEFT(b.kd_rek6,4)='5206' AND $skpd_clause
                        )a group by jns
                    ) x
                    INNER JOIN
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) aset
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)<=$thn_ang AND LEFT(b.kd_rek6,4)='1306' AND $skpd_clause
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        select 1 jns, ISNULL(SUM(debet-kredit),0) aset_l 
                        from $trhju a inner join $trdju b 
                        on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                        where YEAR(a.tgl_voucher)<=$thn_ang_1 AND LEFT(b.kd_rek6,4)='1306' AND $skpd_clause
                    ) z ON y.jns=z.jns
                    inner join
                    (
                    SELECT 1 jns ,isnull((sum(nilai))*-1,0) mutasi
                    from(
                    SELECT kd_rek, 
                    case when left(kd_rek,4)='1362' then sum(nilai*-1) else sum(nilai) end  as nilai from isi_neraca_calk where $skpd_clause and left(kd_rek,4) in ('1363','1362') 
                    group by kd_rek)z
                    )r on z.jns=r.jns"))->first();
            
            $h_1_kontruksi_ket = DB::select("SELECT a.kd_rek,a.nm_rek , ket,nilai
                        from ket_neraca_calk a left join isi_neraca_calk b on a.kd_rek=b.kd_rek
                        where left(a.kd_rek,3)='136' and $skpd_clause and (ket is not null or ket !='') and nilai<>0
                        order by a.kd_rek,nm_rek ");
        //

        // horizontal 2

            $h_2_eku_awal = collect(DB::select("SELECT sum(sal) ek_aw, sum(sal) ek_sbl 
                from (
                    select isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,1) in ('7','8') and $skpd_clause
                    union all
                    select isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where kd_rek6='310101010001' and tabel='1' and reev='0' and $skpd_clause
                    union all
                    select isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where kd_rek6='310101010001' and reev in ('1','2','3') and $skpd_clause
                ) a"))->first();

            $h_2_eku_awal_ket = DB::select("SELECT ket,nilai from isi_analisis_calk where $skpd_clause and kd_rek='b21' order by kd_rinci");

            $h_2_surdef_lolpe = collect(DB::select("SELECT x.pen_lo-y.bel_lo sur_def_lo 
                FROM 
                (
                    select 1 jns, ISNULL(SUM(kredit-debet),0) pen_lo 
                    from $trhju a inner join $trdju b 
                    on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                    where YEAR(a.tgl_voucher)=$thn_ang AND LEFT(b.kd_rek6,1)='7' AND $skpd_clause
                ) x
                    INNER JOIN
                (
                    select 1 jns, ISNULL(SUM(debet-kredit),0) bel_lo 
                    from $trhju a inner join $trdju b 
                    on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                    where YEAR(a.tgl_voucher)=$thn_ang AND LEFT(b.kd_rek6,1)='8' AND $skpd_clause
                ) y ON x.jns=y.jns"))->first();

            $h_2_surdef_lolpe_ket = DB::select("SELECT ket,nilai from isi_analisis_calk where $skpd_clause and kd_rek='b22' order by kd_rinci");

            $h_2_eku_akhir = collect(DB::select("SELECT sum(sal) ekuitas
                from 
                (
                    select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                    isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,1) in ('7','8') and $skpd_clause
                    union all
                    select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                    isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where kd_rek6='310101010001' and tabel='1' and reev='0' and $skpd_clause
                    union all
                    select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                    isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where kd_rek6='310101010001' and reev in ('1','2','3') and $skpd_clause
                ) x"))->first();

            $h_2_eku_akhir_ket = DB::select("SELECT ket,nilai from isi_analisis_calk where $skpd_clause and kd_rek='b23' order by kd_rinci");
        //

        // horizontal 3
            $h_3_pen_pajak = collect(DB::select("SELECT w.pend_pajak_lo, x.pend_pajak_lra, y.piutang_pajak_akhir, z.piutang_pajak_awal*-1 piutang_pajak_awal 
                FROM 
                (
                    SELECT 1 jns, ISNULL(SUM(kredit-debet),0) pend_pajak_lo 
                    FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                    WHERE LEFT(kd_rek6,4)='7101' AND YEAR(a.tgl_voucher)='$thn_ang' AND $skpd_clause
                ) w
                INNER JOIN
                (
                    SELECT 1 jns, ISNULL(SUM(kredit-debet),0) pend_pajak_lra 
                    FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                    WHERE LEFT(kd_rek6,4)='4101' AND YEAR(a.tgl_voucher)='$thn_ang' AND $skpd_clause
                ) x ON w.jns=x.jns
                INNER JOIN
                (
                    SELECT 1 jns, ISNULL(SUM(debet-kredit),0) piutang_pajak_akhir 
                    FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                    WHERE LEFT(kd_rek6,4)='1103' AND YEAR(a.tgl_voucher)<='$thn_ang' AND $skpd_clause
                ) y ON x.jns=y.jns
                INNER JOIN
                (
                    SELECT 1 jns, ISNULL(SUM(debet-kredit),0) piutang_pajak_awal 
                    FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                    WHERE LEFT(kd_rek6,4)='1103' AND YEAR(a.tgl_voucher)<='$thn_ang_1' AND $skpd_clause
                ) z ON y.jns=z.jns"))->first();

            $h_3_pen_pajak_ket = DB::select("SELECT ket,nilai from isi_analisis_calk where $skpd_clause and kd_rek='b31' order by kd_rinci");

            $h_3_pen_retribusi = collect(DB::select("SELECT pend_retri_lo, pend_retri_lra, piutang_retri_akhir, piutang_retri_awal,(pend_retri_lo-(pend_retri_lra+piutang_retri_awal+piutang_retri_akhir)) selisihpr
                from
                (
                    SELECT w.pend_retri_lo, x.pend_retri_lra, y.piutang_retri_akhir, z.piutang_retri_awal*-1 piutang_retri_awal 
                    FROM 
                    (
                        SELECT 1 jns, ISNULL(SUM(kredit-debet),0) pend_retri_lo 
                        FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                        WHERE LEFT(kd_rek6,4)='7102' AND YEAR(a.tgl_voucher)='$thn_ang' AND $skpd_clause
                    ) w
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(kredit-debet),0) pend_retri_lra 
                        FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                        WHERE LEFT(kd_rek6,4)='4102' AND YEAR(a.tgl_voucher)='$thn_ang' AND $skpd_clause
                    ) x ON w.jns=x.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) piutang_retri_akhir 
                        FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                        WHERE LEFT(kd_rek6,4)='1104' AND YEAR(a.tgl_voucher)<='$thn_ang' AND $skpd_clause
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (SELECT 1 jns, ISNULL(SUM(debet-kredit),0) piutang_retri_awal 
                        FROM $trhju a INNER JOIN $trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                        WHERE LEFT(kd_rek6,4)='1104' AND YEAR(a.tgl_voucher)<='$thn_ang_1' AND $skpd_clause
                    ) z ON y.jns=z.jns
                )a"))->first();

            $h_3_pen_retribusi_ket = DB::select("SELECT ket,nilai from isi_analisis_calk where $skpd_clause and kd_rek='b32' order by kd_rinci");
            // persediaan
                $sql21 = collect(DB::select("SELECT kd_rek FROM map_beban2_2021 WHERE jenis=1 AND kode=2"))->first();
                $kd_rek21 = $sql21->kd_rek;
                if ($kd_skpd=='1.02.0.00.0.00.02.0000' || $kd_skpd=='1.02.0.00.0.00.03.0000') {
                    $persediaanakhir_barang="SELECT 1 jns,ISNULL(b.nilai,0)*-1 persediaan_akhir from ket_beban_calk a  
                            LEFT JOIN (select kd_rek, nm_rek, nilai from nilai_beban_calk where kd_skpd='$kd_skpd')b
                            on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('81031')";
                    $persediaanawal_barang="SELECT 1 jns, ISNULL(b.nilai,0) persediaan_awal from ket_beban_calk a LEFT JOIN (
                                         select kd_rek, nm_rek, nilai 
                                         from nilai_beban_calk 
                                         where kd_skpd='$kd_skpd') b on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,5) in ('81032') ";
                }else{
                    $persediaanakhir_barang="SELECT 1 jns, ISNULL(sum(b.debet-b.kredit),0) persediaan_akhir 
                            from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                            where LEFT(b.kd_rek6,4) IN ('1112') 
                            AND b.kd_unit='$kd_skpd' AND YEAR(tgl_voucher)<='$thn_ang'";
                    $persediaanawal_barang="SELECT 1 jns, ISNULL(sum(b.debet-b.kredit),0) persediaan_awal 
                            from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                            where LEFT(b.kd_rek6,4) IN ('1112') 
                            AND b.kd_unit='$kd_skpd' AND YEAR(tgl_voucher)<='$thn_ang_1'";
                }

                $h_3_persediaan = collect(DB::select("SELECT beban_persediaan, belanja_persediaan, persediaan_awal, persediaan_akhir , persediaan_lain_awal,  persediaan_lain_akhir,(beban_persediaan-(belanja_persediaan+persediaan_awal+persediaan_akhir)) selisih
                    from
                    (
                        SELECT sum(x.beban_persediaan) beban_persediaan, sum(t.belanja_persediaan) belanja_persediaan, sum(y.persediaan_awal)persediaan_awal, sum(z.persediaan_akhir)*-1 persediaan_akhir , sum(r.persediaan_lain_awal)persediaan_lain_awal, sum(s.persediaan_lain_akhir)*-1 persediaan_lain_akhir
                        FROM 
                        (
                            select 1 jns, ISNULL(sum(b.debet-b.kredit),0) beban_persediaan 
                            from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                            where left(b.kd_rek6,6) IN ('810201') AND b.kd_unit='$kd_skpd' AND YEAR(tgl_voucher)='$thn_ang'
                        ) x
                        INNER JOIN
                        (
                            select 1 jns, ISNULL(sum(b.debet-b.kredit),0) belanja_persediaan 
                            from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
                            where left(b.kd_rek6,6) IN ('510201') AND b.kd_unit='$kd_skpd' AND YEAR(tgl_voucher)='$thn_ang'
                        ) t ON x.jns=t.jns
                        INNER JOIN
                        ($persediaanawal_barang) y ON x.jns=y.jns
                        INNER JOIN
                        ($persediaanakhir_barang) z ON y.jns=z.jns
                        INNER JOIN
                        (
                            SELECT 1 jns, ISNULL(SUM(sal_awal),0) persediaan_lain_awal 
                            FROM lamp_aset 
                            WHERE kd_skpd='$kd_skpd' AND left(kd_rek6,8)='11120105'
                        ) r ON z.jns=r.jns
                        INNER JOIN
                        (
                            SELECT 1 jns, ISNULL(SUM(sal_awal+tambah-kurang+tahun_n+(kormin-korplus)),0) persediaan_lain_akhir 
                            FROM lamp_aset 
                            WHERE kd_skpd='$kd_skpd' AND left(kd_rek6,8)='11120105'
                        ) s ON r.jns=s.jns
                    )a"))->first();

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
            

                if ($kd_skpd=='1.04.2.10.0.00.01.0000'||$kd_skpd=='1.02.0.00.0.00.02.0000'||$kd_skpd=='1.02.0.00.0.00.03.0000'||$kd_skpd=='1.02.0.00.0.00.01.0000'||$kd_skpd=='3.29.3.30.3.31.01.0000'||$kd_skpd=='2.09.0.00.0.00.01.0000'||$kd_skpd=='2.09.3.27.0.00.01.0000'||$kd_skpd=='3.27.0.00.0.00.03.0003'||$kd_skpd=='2.09.3.27.0.00.01.0002'||$kd_skpd=='1.03.1.04.0.00.01.0000'||$kd_skpd=='3.27.0.00.0.00.04.0000'||$kd_skpd=='3.27.0.00.0.00.03.0003' || $kd_skpd=='3.27.0.00.0.00.01.0004') {
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
                    on a.kd_rek=b.kd_rek where LEFT(a.kd_rek,6) in ('81031')";
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
                    $sql_810317 = "SELECT '810317' kd_rek, ISNULL(SUM(debet),0) nilai 
                    FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
                    WHERE b.kd_unit='$kd_skpd' AND YEAR(a.tgl_voucher)='$thn_ang' AND tgl_real='20'";
                }

                $h_3_persediaan_ket = DB::select("SELECT a.kd_rek, a.nm_rek, ISNULL(b.nilai,0) nilai from ket_beban_calk a 
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
                            where LEFT(a.kd_rek,4)='8103' and nilai!=0 and (a.kd_rek!='81031' and  a.kd_rek!='81032')
                            order by cast(a.kd_rek as int)");
            //

            // Akumulasi Penyusutan Peralatan dan Mesin
                $h_3_akum_ppm = collect(DB::select("SELECT beban, sal_awal, sal_akhir,(sal_akhir-sal_awal) selisih, sal_ket,(beban*-1)penyusutan
                    FROM 
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) beban
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)='$thn_ang' AND LEFT(b.kd_rek6,6)='810801'
                    ) x
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) sal_awal
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,6)='130701'
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) sal_akhir
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,6)='130701'
                    ) z ON y.jns=z.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, isnull(sum(a.nil_m-a.nil_p),0) sal_ket
                        from(
                            select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                            from isi_neraca_calk_baru 
                            where $skpd_clause and kd_rek='11' 
                            group by kd_skpd
                            union all
                            select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                            from isi_neraca_calk_baru 
                            where $skpd_clause and kd_rek='12' 
                            group by kd_skpd
                        )a
                    ) r ON r.jns=z.jns"))->first();

                $h_3_akum_ppm_ket = DB::select("SELECT kd_rek2 kd_rek,nm_rek2 nm_rek , ket,nilai
                    from  isi_neraca_calk_baru
                    where left(kd_rek,1)='1' and $skpd_clause and (ket is not null or ket !='') and nilai<>0
                    order by kd_rek2,nm_rek2");
            //

            // AKUMULASI PENYUSUTAN GEDUNG DAN BANGUNAN
                $h_3_akum_pgb = collect(DB::select("SELECT beban, sal_awal, sal_akhir,(sal_akhir-sal_awal) selisih, sal_ket,(beban*-1)penyusutan
                    FROM 
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) beban
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)='$thn_ang' AND LEFT(b.kd_rek6,6)='810802'
                    ) x
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) sal_awal
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,6)='130702'
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) sal_akhir
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,6)='130702'
                    ) z ON y.jns=z.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, isnull(sum(a.nil_m-a.nil_p),0) sal_ket
                        from(
                            select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                            from isi_neraca_calk_baru 
                            where $skpd_clause and kd_rek='21' 
                            group by kd_skpd
                            union all
                            select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                            from isi_neraca_calk_baru 
                            where $skpd_clause and kd_rek='22' 
                            group by kd_skpd
                        )a
                    ) r ON r.jns=z.jns"))->first();

                $h_3_akum_pgb_ket = DB::select("SELECT kd_rek2 kd_rek,nm_rek2 nm_rek , ket,nilai
                    from  isi_neraca_calk_baru
                    where left(kd_rek,1)='2' and $skpd_clause and (ket is not null or ket !='') and nilai<>0
                    order by kd_rek2,nm_rek2");
            //

            // Akumulasi Penyusutan Jalan, Jaringan, danIrigasi
                $h_3_akum_pjji = collect(DB::select("SELECT beban, sal_awal, sal_akhir,(sal_akhir-sal_awal) selisih, sal_ket,(beban*-1)penyusutan
                    FROM 
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) beban
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)='$thn_ang' AND LEFT(b.kd_rek6,6)='810803'
                    ) x
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) sal_awal
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,6)='130703'
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) sal_akhir
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,6)='130703'
                    ) z ON y.jns=z.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, isnull(sum(a.nil_m-a.nil_p),0) sal_ket
                        from(
                            select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                            from isi_neraca_calk_baru 
                            where $skpd_clause and kd_rek='31' 
                            group by kd_skpd
                            union all
                            select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                            from isi_neraca_calk_baru 
                            where $skpd_clause and kd_rek='32' 
                            group by kd_skpd
                        )a
                    ) r ON r.jns=z.jns"))->first();

                $h_3_akum_pjji_ket = DB::select("SELECT kd_rek2 kd_rek,nm_rek2 nm_rek , ket,nilai
                    from  isi_neraca_calk_baru
                    where left(kd_rek,1)='3' and $skpd_clause and (ket is not null or ket !='') and nilai<>0
                    order by kd_rek2,nm_rek2");
            //

            // Akumulasi Penyusutan Aset Tetap Lainnya
                $h_3_akum_patl = collect(DB::select("SELECT beban, sal_awal, sal_akhir,(sal_akhir-sal_awal) selisih, sal_ket,(beban*-1)penyusutan
                    FROM 
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) beban
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)='$thn_ang' AND LEFT(b.kd_rek6,6)='810804'
                    ) x
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) sal_awal
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,6)='130704'
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) sal_akhir
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,6)='130704'
                    ) z ON y.jns=z.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, isnull(sum(a.nil_m-a.nil_p),0) sal_ket
                        from(
                            select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                            from isi_neraca_calk_baru 
                            where $skpd_clause and kd_rek='41' 
                            group by kd_skpd
                            union all
                            select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                            from isi_neraca_calk_baru 
                            where $skpd_clause and kd_rek='42' 
                            group by kd_skpd
                        )a
                    ) r ON r.jns=z.jns"))->first();

                $h_3_akum_patl_ket = DB::select("SELECT kd_rek2 kd_rek,nm_rek2 nm_rek , ket,nilai
                    from  isi_neraca_calk_baru
                    where left(kd_rek,1)='4' and $skpd_clause and (ket is not null or ket !='') and nilai<>0
                    order by kd_rek2,nm_rek2");
            //

            // Akumulasi Amortisasi Aset Tidak Berwujud
                $h_3_akum_astb = collect(DB::select("SELECT beban, sal_awal, sal_akhir,(sal_akhir-sal_awal) selisih, sal_ket,(beban*-1)penyusutan
                    FROM 
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) beban
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)='$thn_ang' AND LEFT(b.kd_rek6,6)='810806'
                    ) x
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) sal_awal
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,4)='1505'
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) sal_akhir
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,4)='1505'
                    ) z ON y.jns=z.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, isnull(sum(a.nil_m-a.nil_p),0) sal_ket
                        from(
                            select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                            from isi_neraca_calk_baru 
                            where $skpd_clause and kd_rek='51' 
                            group by kd_skpd
                            union all
                            select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                            from isi_neraca_calk_baru 
                            where $skpd_clause and kd_rek='52' 
                            group by kd_skpd
                        )a
                    ) r ON r.jns=z.jns"))->first();

                $h_3_akum_astb_ket = DB::select("SELECT kd_rek2 kd_rek,nm_rek2 nm_rek , ket,nilai
                    from  isi_neraca_calk_baru
                    where left(kd_rek,1)='5' and $skpd_clause and (ket is not null or ket !='') and nilai<>0
                    order by kd_rek2,nm_rek2");
            //

            // Akumulasi Penyusutan Aset Lainnya
                $h_3_akum_pal = collect(DB::select("SELECT beban, sal_awal, sal_akhir,(sal_akhir-sal_awal) selisih, sal_ket,(beban*-1)penyusutan
                    FROM 
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) beban
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)='$thn_ang' AND LEFT(b.kd_rek6,6)='810807'
                    ) x
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) sal_awal
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)<='$thn_ang_1' AND LEFT(b.kd_rek6,4)='1506'
                    ) y ON x.jns=y.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, ISNULL(SUM(debet-kredit),0) sal_akhir
                        FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
                        WHERE $skpd_clause AND YEAR(a.tgl_voucher)<='$thn_ang' AND LEFT(b.kd_rek6,4)='1506'
                    ) z ON y.jns=z.jns
                    INNER JOIN
                    (
                        SELECT 1 jns, isnull(sum(a.nil_m-a.nil_p),0) sal_ket
                        from(
                            select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                            from isi_neraca_calk_baru 
                            where $skpd_clause and kd_rek='61' 
                            group by kd_skpd
                            union all
                            select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                            from isi_neraca_calk_baru 
                            where $skpd_clause and kd_rek='62' 
                            group by kd_skpd
                        )a
                    ) r ON r.jns=z.jns"))->first();

                $h_3_akum_pal_ket = DB::select("SELECT kd_rek2 kd_rek,nm_rek2 nm_rek , ket,nilai
                    from  isi_neraca_calk_baru
                    where left(kd_rek,1)='6' and $skpd_clause and (ket is not null or ket !='') and nilai<>0
                    order by kd_rek2,nm_rek2");
            //
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
        'vlra'          => $vlra,
        'vlra_ket'      => $vlra_ket,
        'vneraca'          => $vneraca,
        'vneraca_ket'      => $vneraca_ket,
        'vkasben'          => $vkasben,
        'vkasben_ket'      => $vkasben_ket,
        'vlo'          => $vlo,
        'vlo_ket'      => $vlo_ket,
        'vlpe'          => $vlpe,
        'vlpe_ket'      => $vlpe_ket,
        'h_1_tanah'   => $h_1_tanah,
        'h_1_tanah_ket'   => $h_1_tanah_ket,
        'h_1_pmesin'   => $h_1_pmesin,
        'h_1_pmesin_ket'   => $h_1_pmesin_ket,
        'h_1_gbangunan'   => $h_1_gbangunan,
        'h_1_gbangunan_ket'   => $h_1_gbangunan_ket,
        'h_1_jij'   => $h_1_jij,
        'h_1_jij_ket'   => $h_1_jij_ket,
        'h_1_asettl'   => $h_1_asettl,
        'h_1_asettl_ket'   => $h_1_asettl_ket,
        'h_1_kontruksi'   => $h_1_kontruksi,
        'h_1_kontruksi_ket'   => $h_1_kontruksi_ket,
        'h_2_eku_awal'   => $h_2_eku_awal,
        'h_2_eku_awal_ket'   => $h_2_eku_awal_ket,
        'h_2_surdef_lolpe'   => $h_2_surdef_lolpe,
        'h_2_surdef_lolpe_ket'   => $h_2_surdef_lolpe_ket,
        'h_2_eku_akhir'   => $h_2_eku_akhir,
        'h_2_eku_akhir_ket'   => $h_2_eku_akhir_ket,
        'h_3_pen_pajak'   => $h_3_pen_pajak,
        'h_3_pen_pajak_ket'   => $h_3_pen_pajak_ket,
        'h_3_pen_retribusi'   => $h_3_pen_retribusi,
        'h_3_pen_retribusi_ket'   => $h_3_pen_retribusi_ket,
        'h_3_persediaan'   => $h_3_persediaan,
        'h_3_persediaan_ket'   => $h_3_persediaan_ket,
        'h_3_akum_ppm'   => $h_3_akum_ppm,
        'h_3_akum_ppm_ket'   => $h_3_akum_ppm_ket,
        'h_3_akum_pgb'   => $h_3_akum_pgb,
        'h_3_akum_pgb_ket'   => $h_3_akum_pgb_ket,
        'h_3_akum_pjji'   => $h_3_akum_pjji,
        'h_3_akum_pjji_ket'   => $h_3_akum_pjji_ket,
        'h_3_akum_patl'   => $h_3_akum_patl,
        'h_3_akum_patl_ket'   => $h_3_akum_patl_ket,
        'h_3_akum_astb'   => $h_3_akum_astb,
        'h_3_akum_astb_ket'   => $h_3_akum_astb_ket,
        'h_3_akum_pal'   => $h_3_akum_pal,
        'h_3_akum_pal_ket'   => $h_3_akum_pal_ket,
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
    
        $view =  view('akuntansi.cetakan.calk.lamp1.lamp1_index')->with($data);
        
        
        
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

    public function load_calklamp1(Request $request)
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
        
        $data = DB::select("SELECT *,(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd)nm_skpd FROM isi_analisis_calk a WHERE $skpd_clause AND kd_rek='$kd_rek'");
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->kd_rek . '\',\'' . $row->kd_rinci . '\',\'' . $row->ket . '\',\'' . $row->nilai . '\');" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->kd_rek . '\',\'' . $row->kd_rinci . '\',\'' . $row->ket . '\',\'' . $row->nilai . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function load_kd_rinci_calklamp1(Request $request)
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
    public function hapus_calklamp1(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $kd_rinci             = $request->kd_rinci;
        $ket               = $request->ket;
        $nilai               = $request->nilai;
        
        
        $query = DB::delete("DELETE from $tabel where kd_skpd='$kd_skpd' and kd_rinci = '$kd_rinci' and kd_rek='$kd_rek'");
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

    public function simpan_calklamp1(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $kd_rinci             = $request->kd_rinci;
        $ket               = $request->ket;
        $nilai               = $request->nilai;
        
        $hasil=collect(DB::select("SELECT  count(*) as jumlah FROM $tabel where kd_skpd='$kd_skpd' and kd_rek='$kd_rek' and kd_rinci='$kd_rinci'"))->first();

        $jumlah=$hasil->jumlah; 
        
        if($jumlah>0){
            $asg     = DB::update("UPDATE $tabel SET ket='$ket', nilai=$nilai where kd_rek='$kd_rek' and kd_rinci='$kd_rinci' and kd_skpd='$kd_skpd'");
        } else{
            $asg = DB::insert("INSERT into $tabel (kd_skpd,kd_rek,ket,nilai,kd_rinci) values ('$kd_skpd','$kd_rek','$ket',$nilai,'$kd_rinci')");
        }
        
        if ( $asg > 0 ){
           echo '1';
        } else {
           echo '0';
        }
    
    }


}
