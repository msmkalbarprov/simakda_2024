<?php

namespace App\Http\Controllers\Akuntansi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;
use PDF;
use Knp\Snappy\Pdf as SnappyPdf;

class LapkeuController extends Controller
{

    public function cetak_lra(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tanggal_ttd    = $request->tgl_ttd;
        $ttd            = $request->ttd;
        $bulan          = $request->bulan;
        $format          = $request->format;
        $enter          = $request->spasi;
        $cetak          = $request->cetak;
        $tanggal1       = $request->tanggal1;
        $tanggal2       = $request->tanggal2;
        $jns_ang        = $request->jenis_anggaran;
        $periodebulan   = $request->periodebulan;
        $skpdunit    = $request->skpdunit;
        $kd_skpd        = $request->kd_skpd;

            if ($skpdunit=="unit") {
                $kd_skpd=$kd_skpd;
            }else if ($skpdunit=="skpd") {
                $kd_skpd=substr($kd_skpd,0,17);
            }
            $skpd_clause = "AND left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clause_ang = "AND left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clauses= "WHERE left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clause_prog= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' and ";

        // dd(substr($tanggal2,5,2));
        // dd($kd_skpd);
        if ($periodebulan=='periode') {
            $bulan=substr($tanggal2,6,1);
        }else{
            $bulan=$bulan;
        }
        $tahun_anggaran = tahun_anggaran();
        $thn_ang1   = $tahun_anggaran-1;

        $modtahun= $tahun_anggaran%4;
        
            if ($modtahun = 0){
                $nilaibulan=".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
            }else {
                $nilaibulan=".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
            }
         
         $arraybulan=explode(".",$nilaibulan);
         $nm_bln = $arraybulan[$bulan];

        // TANDA TANGAN
        if($ttd == '0'){
            $tandatangan="";
        }else{
            $tandatangan = DB::table('ms_ttd')
                            ->select('nama', 'nip', 'jabatan', 'pangkat')
                            ->where('nip', $ttd)
                            ->first();
        }
        

        $map_lra = DB::select("SELECT a.seq,a.cetak,a.bold,a.parent,a.nor,a.uraian,isnull(a.kode_1,'-') as kode_1,isnull(a.kode_2,'-') as kode_2,isnull(a.kode_3,'-') as kode_3,thn_m1 AS lalu FROM map_lra_skpd a 
                          ORDER BY a.seq");
        if ($periodebulan=="periode") {
            $sus=collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet_tgl(?,?,?) $skpd_clauses",[$tanggal1,$tanggal2,$jns_ang]))->first();
        }else if($periodebulan=="bulan"){
            $sus=collect(DB::select("SELECT 
                    SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
                    SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus,
                    SUM(CASE WHEN kd_rek='4' THEN (kredit_awal-debet_awal) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet_awal-kredit_awal) ELSE 0 END) as nil_surplus_awal
                    FROM
                    (SELECT LEFT(kd_rek6,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet
                        ,SUM(kredit_awal) as kredit_awal,SUM(debet_awal) as debet_awal 
                         FROM data_jurnal_n_sal_awal($bulan,'$jns_ang',$tahun_anggaran) WHERE LEFT(kd_rek6,1) IN ('4','5') $skpd_clause_ang
                    GROUP BY LEFT(kd_rek6,1)) a"))->first();
        }
        // dd($periodebulan);
        

        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            // dd($sus);

            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
                'map_lra'           => $map_lra,
                'enter'             => $enter,
                'skpd_clauses'      => $skpd_clauses,
                'kd_skpd'           => $kd_skpd,
                'daerah'            => $daerah,
                'tanggal_ttd'       => $tanggal_ttd,
                'tandatangan'       => $tandatangan,
                'bulan'             => $bulan,
                'nm_bln'            => $nm_bln,
                'jenis_ttd'         => $ttd,
                'sus'               => $sus,
                'thn_ang'           => $tahun_anggaran,
                'periodebulan'      => $periodebulan,
                'tanggal1'          => $tanggal1,
                'tanggal2'          => $tanggal2,
                'jns_ang'           => $jns_ang,
                'thn_ang_1'         => $thn_ang1,
                'skpdunit'          =>$skpdunit   
            ];


        $view =  view('akuntansi.cetakan.lapkeu.lra')->with($data);

        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LRA SKPD.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LRA SKPD.xls"');
            return $view;
        }
    }

    public function cetak_semester(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tanggal_ttd    = $request->tgl_ttd;
        $ttd            = $request->ttd;
        $bulan          = $request->bulan;
        $enter          = $request->spasi;
        $cetak          = $request->cetak;
        $tanggal1       = $request->tanggal1;
        $tanggal2       = $request->tanggal2;
        $jns_ang        = $request->jenis_anggaran;
        $periodebulan   = $request->periodebulan;
        $jenis_data     = $request->jenis_data;
        $skpdunit    = $request->skpdunit;
        $kd_skpd        = $request->kd_skpd;
        $jns_rincian = $request->panjang_data;;


        // dd($jns_rincian);
        if ($skpdunit == "keseluruhan") {
            $kd_skpd        = "";
            $skpd_clause = "";
            $skpd_clauses = "";
            $skpd_clause_prog = "";
            $skpd_clause_ang = "";
            $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', '5.02.0.00.0.00.02.0000')->first();
            $ttd            = "-";
        } else {
            if ($skpdunit == "unit") {
                $kd_skpd = $kd_skpd;
            } else if ($skpdunit == "skpd") {
                $kd_skpd = substr($kd_skpd, 0, 17);
            }
            $skpd_clause = "AND left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clause_ang = "AND left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clauses = "WHERE left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clause_prog = "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' and ";
            $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            $ttd            = $request->ttd;
        }
        $tahun_anggaran = tahun_anggaran();

        // TANDA TANGAN
        if ($ttd == '0') {
            $tandatangan = "";
        } else {
            // $tandatangan = DB::table('ms_ttd')
            //     ->select('nama', 'nip', 'jabatan', 'pangkat')
            //     ->where('nip', $ttd)
            //     ->whereIn('kode', ['1'])
            //     ->first();
            $tandatangan = collect(DB::select("SELECT top 1 nama,nip,jabatan,pangkat from ms_ttd where nip='$ttd'"))->first();
        }
        // dd($tandatangan);
        $isi    = "sd_bulan_ini";
        $pilih  = "S/D";
        $operator = "<=";
        if ($bulan=="1"||$bulan=="2"||$bulan=="4"||$bulan=="5"||$bulan=="7"||$bulan=="8"||$bulan=="10"||$bulan=="11") {
            $judul  = BULAN($bulan);
        }else if ($bulan=="3"){
            $judul = "TRIWULAN I";
        }else if ($bulan=="6"){
            $judul = "SEMESTER PERTAMA";
        }else if ($bulan=="9"){
            $judul = "TRIWULAN III";
        }else if ($bulan=="2"){
            $judul = "SEMESTER KEDUA";
        }else{
            $judul = "bulan tidak diketahui";
        }
        $bulan2 = 12 - $bulan;
        // dd(left($kd_skpd,3));

        // rincian
            if ($jenis_data == 5) { //Jurnal
                if ($periodebulan == 'periode') {
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                            -- anggaran
                                            isnull((SELECT sum(nilai) FROM trdrka
                                                    where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0
                                                    ) AS anggaran,
                                            --realisasi
                                            isnull((
                                                SELECT sum(realisasi) realisasi FROM(
                                                SELECT
                                                b.kd_unit,
                                                b.kd_sub_kegiatan,
                                                b.kd_rek6,
                                                CASE
                                                    WHEN LEFT(b.kd_rek6, 1) = '4' THEN SUM(kredit) - SUM(debet)
                                                    WHEN LEFT(b.kd_rek6, 1) = '5' THEN SUM(debet) - SUM(kredit)
                                                    WHEN LEFT(b.kd_rek6, 2) = '61' THEN SUM(kredit) - SUM(debet)
                                                    WHEN LEFT(b.kd_rek6, 2) = '62' THEN SUM(debet) - SUM(kredit)
                                                    ELSE 0
                                                END AS realisasi
                                                FROM trhju_pkd a
                                                JOIN trdju_pkd b ON a.no_voucher = b.no_voucher
                                                            AND a.kd_skpd = b.kd_unit
                                                WHERE  left(b.kd_rek6,1) IN ('4', '5', '6')
                                                $skpd_clause AND (tgl_voucher between ? and ? ) and  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                GROUP BY a.tgl_voucher,b.no_voucher,b.kd_unit,b.kd_sub_kegiatan,b.kd_rek6)a
                                            ) ,0)realisasi
                                            FROM map_lra_2023

                                            where group_id <= ?
                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                            ORDER BY  map_lra_2023.id,group_id, nama", [$jns_ang, $tanggal1, $tanggal2, $jns_rincian]);
                    $sus = collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet_tgl(?,?,?) $skpd_clauses", [$tanggal1, $tanggal2, $jns_ang]))->first();
                } else {
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,  -- anggaran
                                            isnull((SELECT sum(nilai) FROM trdrka
                                                    where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0
                                                    ) AS anggaran,
                                        --realisasi
                                            isnull((
                                                SELECT sum(realisasi) realisasi FROM(
                                                SELECT
                                                b.kd_unit,
                                                b.kd_sub_kegiatan,
                                                b.kd_rek6,
                                                CASE
                                                    WHEN LEFT(b.kd_rek6, 1) = '4' THEN SUM(kredit-debet)
                                                    WHEN LEFT(b.kd_rek6, 1) = '5' THEN SUM(debet-kredit)
                                                    WHEN LEFT(b.kd_rek6, 2) = '61' THEN SUM(kredit-debet)
                                                    WHEN LEFT(b.kd_rek6, 2) = '62' THEN SUM(debet-kredit)
                                                    ELSE 0
                                                END AS realisasi
                                                FROM trhju_pkd a
                                                JOIN trdju_pkd b ON a.no_voucher = b.no_voucher
                                                            AND a.kd_skpd = b.kd_unit
                                                WHERE  left(b.kd_rek6,1) IN ('4', '5', '6')
                                                $skpd_clause  AND MONTH(tgl_voucher) $operator ? and  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                GROUP BY a.tgl_voucher,b.no_voucher,b.kd_unit,b.kd_sub_kegiatan,b.kd_rek6)a
                                            ) ,0)realisasi

                                            FROM map_lra_2023

                                            where group_id <= ?
                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                            ORDER BY map_lra_2023.id,group_id, nama", [$jns_ang, $bulan, $jns_rincian]);
                    $sus = collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet(?,?,?) $skpd_clauses", [$bulan, $jns_ang, $tahun_anggaran]))->first();
                }
            } else if ($jenis_data == 4) { // SPJ
                if ($periodebulan == 'periode') {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdtransout a
                                                JOIN trhtransout b ON a.no_bukti = b.no_bukti
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                AND (b.tgl_kas between ? and ? ) and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                union all

                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdtransout_blud a
                                                JOIN trhtransout_blud b ON a.no_bukti = b.no_bukti
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                AND (b.tgl_kas between ? and ? ) and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE (b.tgl_sts between ? and ? ) and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                --hkpg
                                                SELECT   sum(a.rupiah*-1) as realisasi
                                                from trdkasin_pkd a 
                                                INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                                                INNER JOIN trhsp2d e ON b.no_sp2d=e.no_sp2d 
                                                INNER JOIN trspmpot f ON e.no_spm=f.no_spm and f.kd_rek6=a.kd_rek6
                                                LEFT JOIN ms_rek6 c on f.kd_trans=c.kd_rek6 
                                                where (b.tgl_sts between ? and ? ) and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(c.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek and left(a.kd_rek6,1)='2'
                                                group by a.kd_skpd, c.kd_rek6 
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? ) AND  LEFT(b.kd_rek6, 1) = '4' $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $tanggal1, $tanggal2, $tanggal1, $tanggal2,  $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj_tgl(?,?,?)", [$tanggal1, $tanggal2, $jns_ang]))->first();
                } else {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdtransout a
                                                JOIN trhtransout b ON a.no_bukti = b.no_bukti
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                AND MONTH(b.tgl_bukti) $operator ? and year(b.tgl_bukti)=?  and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                union all

                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdtransout_blud a
                                                JOIN trhtransout_blud b ON a.no_bukti = b.no_bukti
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                AND MONTH(b.tgl_bukti) $operator ? and year(b.tgl_bukti)=?  and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE MONTH(b.tgl_sts) $operator ? and year(b.tgl_sts)=?    $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                --hkpg
                                                SELECT   sum(a.rupiah*-1) as realisasi
                                                from trdkasin_pkd a 
                                                INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                                                INNER JOIN trhsp2d e ON b.no_sp2d=e.no_sp2d 
                                                INNER JOIN trspmpot f ON e.no_spm=f.no_spm and f.kd_rek6=a.kd_rek6
                                                LEFT JOIN ms_rek6 c on f.kd_trans=c.kd_rek6 
                                                where MONTH(b.tgl_sts) $operator ? and year(b.tgl_sts)=?    $skpd_clause
                                                AND  LEFT(c.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek and left(a.kd_rek6,1)='2'
                                                group by a.kd_skpd, c.kd_rek6 
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0) realisasi
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? and year(a.tgl_sts)=? AND  LEFT(b.kd_rek6, 1) = '4' $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)realisasi
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? and year(a.tgl_sts)=?  and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah else b.rupiah end),0)realisasi FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? and year(a.tgl_sts)=?  $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj(?,?,?)", [$bulan, $jns_ang, $tahun_anggaran]))->first();
                }
            } else if ($jenis_data == 3) { // SP2D LUNAS
                if ($periodebulan == 'periode') {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdspp a
                                                INNER JOIN trhsp2d b ON a.no_spp = b.no_spp
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                and status_bud = 1
                                                and (sp2d_batal is null OR sp2d_batal <> 1)
                                                AND (b.tgl_sp2d between ? and ? ) and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE (b.tgl_sts between ? and ? ) and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj_tgl(?,?,?)", [$tanggal1, $tanggal2, $jns_ang]))->first();
                } else {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdspp a
                                                INNER JOIN trhsp2d b ON a.no_spp = b.no_spp
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                and status_bud=1
                                                and (sp2d_batal is null OR sp2d_batal <> 1)
                                                AND MONTH(b.tgl_sp2d) $operator ? and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE MONTH(b.tgl_sts) $operator ? and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $bulan, $bulan, $bulan, $bulan, $bulan, $jns_rincian]);

                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj(?,?,?)", [$bulan, $jns_ang, $tahun_anggaran]))->first();
                }
            } else if ($jenis_data == 2) { // SP2D Advis
                if ($periodebulan == 'periode') {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdspp a
                                                INNER JOIN trhsp2d b ON a.no_spp = b.no_spp
                                                AND b.kd_skpd = a.kd_skpd
                                                INNER JOIN trduji c on b.no_sp2d=c.no_sp2d
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                and (sp2d_batal is null OR sp2d_batal <> 1)
                                                AND (b.tgl_sp2d between ? and ? ) and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE (b.tgl_sts between ? and ? ) and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj_tgl(?,?,?)", [$tanggal1, $tanggal2, $jns_ang]))->first();
                } else {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdspp a
                                                INNER JOIN trhsp2d b ON a.no_spp = b.no_spp
                                                AND b.kd_skpd = a.kd_skpd
                                                INNER JOIN trduji c on b.no_sp2d=c.no_sp2d
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                and (sp2d_batal is null OR sp2d_batal <> 1)
                                                AND MONTH(b.tgl_sp2d) $operator ? and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE MONTH(b.tgl_sts) $operator ? and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $bulan, $bulan, $bulan, $bulan, $bulan, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj(?,?,?)", [$bulan, $jns_ang, $tahun_anggaran]))->first();
                }
            } else if ($jenis_data == 1) { // SP2D terbit
                if ($periodebulan == 'periode') {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdspp a
                                                INNER JOIN trhsp2d b ON a.no_spp = b.no_spp
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                and (sp2d_batal is null OR sp2d_batal <> 1)
                                                AND (b.tgl_sp2d between ? and ? ) and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE (b.tgl_sts between ? and ? ) and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                --hkpg
                                                SELECT   sum(a.rupiah*-1) as realisasi
                                                from trdkasin_pkd a 
                                                INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                                                INNER JOIN trhsp2d e ON b.no_sp2d=e.no_sp2d 
                                                INNER JOIN trspmpot f ON e.no_spm=f.no_spm and f.kd_rek6=a.kd_rek6
                                                LEFT JOIN ms_rek6 c on f.kd_trans=c.kd_rek6 
                                                where (b.tgl_sts between ? and ? ) and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(c.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek and left(a.kd_rek6,1)='2'
                                                group by a.kd_skpd, c.kd_rek6 
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause and left(kd_rek6,1)='4'
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj_tgl(?,?,?)", [$tanggal1, $tanggal2, $jns_ang]))->first();
                } else {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdspp a
                                                INNER JOIN trhsp2d b ON a.no_spp = b.no_spp
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                and (sp2d_batal is null OR sp2d_batal <> 1)
                                                AND MONTH(b.tgl_sp2d) $operator ? and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE MONTH(b.tgl_sts) $operator ? and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $bulan, $bulan, $bulan, $bulan, $bulan, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj(?,?,?)", [$bulan, $jns_ang, $tahun_anggaran]))->first();
                }
            }




        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
        // dd($sus);
            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
                'kd_skpd'           => $kd_skpd,
                'skpdunit'          => $skpdunit,
                'tanggal1'          => $tanggal1,
                'tanggal2'          => $tanggal2,
                'periodebulan'      => $periodebulan,
                'rincian'           => $rincian,
                'enter'             => $enter,
                'daerah'            => $daerah,
                'tanggal_ttd'       => $tanggal_ttd,
                'tandatangan'       => $tandatangan,
                'tahun_anggaran'    => $tahun_anggaran,
                'bulan'             => $bulan,
                'bulan2'            => $bulan2,
                'judul'             => $judul,
                'pilih'             => $pilih,
                'jenis_ttd'         => $ttd,
                'jenis'             => $jns_rincian,
                'sus'               => $sus
            ];
        
        $view =  view('akuntansi.cetakan.lapkeu.semester')->with($data);
        

        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LRA Semester.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LRA Semester.xls"');
            return $view;
        }
    }

    public function cetak_semester_jurnal(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tanggal_ttd    = $request->tgl_ttd;
        $bulan          = $request->bulan;
        $enter          = $request->spasi;
        $cetak          = $request->cetak;
        $tanggal1       = $request->tanggal1;
        $tanggal2       = $request->tanggal2;
        $jns_ang        = $request->jenis_anggaran;
        $periodebulan   = $request->periodebulan;
        $jenis_data     = $request->jenis_data;
        $skpdunit    = $request->skpdunit;
        $kd_skpd        = $request->kd_skpd;
        $jns_rincian = $request->panjang_data;;

        // dd($jns_rincian);
        if ($skpdunit == "keseluruhan") {
            $kd_skpd        = "";
            $skpd_clause = "";
            $skpd_clauses = "";
            $skpd_clause_prog = "";
            $skpd_clause_ang = "";
            $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', '5.02.0.00.0.00.02.0000')->first();
            $ttd            = "-";
        } else {
            if ($skpdunit == "unit") {
                $kd_skpd = $kd_skpd;
            } else if ($skpdunit == "skpd") {
                $kd_skpd = substr($kd_skpd, 0, 17);
            }
            $skpd_clause = "AND left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clause_ang = "AND left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clauses = "WHERE left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clause_prog = "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' and ";
            $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            $ttd            = $request->ttd;
        }

        $tahun_anggaran = tahun_anggaran();
        $tahun_anggaran1=$tahun_anggaran-1;
        if ($periodebulan=="bulan") {
            $modtahun= $tahun_anggaran%4;
            
                if ($modtahun = 0){
                    $nilaibulan=".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
                }else {
                    $nilaibulan=".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
                }
             
             $arraybulan=explode(".",$nilaibulan);
             $nm_bln = $arraybulan[$bulan];
        }else{
            $nm_bln="";
        }

        // TANDA TANGAN
        if ($ttd == "0") {
            $tandatangan = "";
        } else {
            // $tandatangan = DB::table('ms_ttd')
            //     ->select('nama', 'nip', 'jabatan', 'pangkat')
            //     ->where('nip', $ttd)
            //     ->whereIn('kode', ['1'])
            //     ->first();
            $tandatangan = collect(DB::select("SELECT top 1 nama,nip,jabatan,pangkat from ms_ttd where nip='$ttd'"))->first();
        }
        // dd($tandatangan);
        $isi    = "sd_bulan_ini";
        $pilih  = "S/D";
        $operator = "<=";
        if ($bulan=="1"||$bulan=="2"||$bulan=="4"||$bulan=="5"||$bulan=="7"||$bulan=="8"||$bulan=="10"||$bulan=="11") {
            $judul  = BULAN($bulan);
        }else if ($bulan=="3"){
            $judul = "TRIWULAN I";
        }else if ($bulan=="6"){
            $judul = "SEMESTER PERTAMA";
        }else if ($bulan=="9"){
            $judul = "TRIWULAN III";
        }else if ($bulan=="2"){
            $judul = "SEMESTER KEDUA";
        }else{
            $judul = "bulan tidak diketahui";
        }
        $bulan2 = 12 - $bulan;
        // dd(left($kd_skpd,3));

        // rincian

        if ($periodebulan == 'periode') {
            if ($jenis_data==4) {
                $sus = collect(DB::select("
                    SELECT SUM(CASE WHEN left(kd_rek6,1)='4' THEN (anggaran) ELSE 0 END) - SUM(CASE WHEN left(kd_rek6,1)='5' THEN (anggaran) ELSE 0 END) as ang_surplus,
                    SUM(CASE WHEN left(kd_rek6,1)='4' THEN (realisasi) ELSE 0 END) - SUM(CASE WHEN left(kd_rek6,1)='5' THEN (realisasi) ELSE 0 END) as nil_surplus,
                    SUM(CASE WHEN left(kd_rek6,2)='61' THEN (anggaran) ELSE 0 END) - SUM(CASE WHEN left(kd_rek6,2)='62' THEN (anggaran) ELSE 0 END) as ang_neto,
                    SUM(CASE WHEN left(kd_rek6,2)='61' THEN (realisasi) ELSE 0 END) - SUM(CASE WHEN left(kd_rek6,2)='62' THEN (realisasi) ELSE 0 END) as nil_neto 
                from data_lra_spj_tgl('$tanggal1','$jns_ang','$tanggal2')  $skpd_clauses"))->first();
            }else{
                $sus = collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet_tgl(?,?,?) $skpd_clauses", [$tanggal1, $tanggal2, $jns_ang]))->first();
            }
            
        }else{
            if ($jenis_data==4) {
                $sus = collect(DB::select("
                    SELECT SUM(CASE WHEN left(kd_rek6,1)='4' THEN (anggaran) ELSE 0 END) - SUM(CASE WHEN left(kd_rek6,1)='5' THEN (anggaran) ELSE 0 END) as ang_surplus,
                    SUM(CASE WHEN left(kd_rek6,1)='4' THEN (realisasi) ELSE 0 END) - SUM(CASE WHEN left(kd_rek6,1)='5' THEN (realisasi) ELSE 0 END) as nil_surplus,
                    SUM(CASE WHEN left(kd_rek6,2)='61' THEN (anggaran) ELSE 0 END) - SUM(CASE WHEN left(kd_rek6,2)='62' THEN (anggaran) ELSE 0 END) as ang_neto,
                    SUM(CASE WHEN left(kd_rek6,2)='61' THEN (realisasi) ELSE 0 END) - SUM(CASE WHEN left(kd_rek6,2)='62' THEN (realisasi) ELSE 0 END) as nil_neto 
                from data_lra_spj_bulan($bulan,'$jns_ang',$tahun_anggaran)  $skpd_clauses"))->first();
            }else{
                $sus = collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet(?,?,?) $skpd_clauses", [$bulan, $jns_ang, $tahun_anggaran]))->first();
            }
        }
        $rincian = DB::select("SELECT * from map_lra_semester");
            




        // dd($sus);
            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
                'kd_skpd'           => $kd_skpd,
                'skpdunit'          => $skpdunit,
                'tanggal1'          => $tanggal1,
                'tanggal2'          => $tanggal2,
                'periodebulan'      => $periodebulan,
                'rincian'           => $rincian,
                'enter'             => $enter,
                'daerah'            => $daerah,
                'tanggal_ttd'       => $tanggal_ttd,
                'tandatangan'       => $tandatangan,
                'tahun_anggaran'    => $tahun_anggaran,
                'tahun_anggaran1'    => $tahun_anggaran1,
                'jns_ang'           => $jns_ang,
                'skpd_clause_ang'   => $skpd_clause_ang,
                'skpd_clause'       => $skpd_clause, 
                'skpd_clauses'       => $skpd_clauses,     
                'bulan'             => $bulan,
                'bulan2'            => $bulan2,
                'judul'             => $judul,
                'pilih'             => $pilih,
                'nm_bln'             => $nm_bln,
                'jenis_ttd'         => $ttd,
                'jenis'             => $jns_rincian,
                'sus'               => $sus
            ];
        if ($jenis_data==4) {
            $view =  view('akuntansi.cetakan.lapkeu.semester_spj')->with($data);
        }else{
            $view =  view('akuntansi.cetakan.lapkeu.semester_jurnal')->with($data);
        }
        

        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LRA Semester.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LRA Semester.xls"');
            return $view;
        }
    }

    public function cetak_semester_rinci(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tanggal_ttd    = $request->tgl_ttd;
        $ttd            = $request->ttd;
        $bulan          = $request->bulan;
        $enter          = $request->spasi;
        $cetak          = $request->cetak;
        $tanggal1       = $request->tanggal1;
        $tanggal2       = $request->tanggal2;
        $jns_ang        = $request->jenis_anggaran;
        $periodebulan   = $request->periodebulan;
        $jenis_data     = $request->jenis_data;
        $jns_rincian     = $request->panjang_data;
        $skpdunit    = $request->skpdunit;
        $kd_skpd        = $request->kd_skpd;
        // $jns_rincian = 4;
        // dd($skpdunit);
        if ($kd_skpd == '') {
            $kd_skpd        = "";
            $skpd_clause = "";
            $skpd_clauses = "";
            $skpd_clause_prog = "";
            $skpd_clause_ang = "";
            $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', '5.02.0.00.0.00.02.0000')->first();
        } else {
            $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            if ($skpdunit == "unit") {
                $kd_skpd = $kd_skpd;
            } else if ($skpdunit == "skpd") {
                $kd_skpd = substr($kd_skpd, 0, 17);
            }
            $skpd_clause = "AND left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clause_ang = "AND left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clauses = "WHERE left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clause_prog = "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' and ";
        }
        $where1 = "LEN(kd_rek)<='$jns_rincian'";
        // dd($kd_skpd);

        $tahun_anggaran = tahun_anggaran();

        // TANDA TANGAN
        if ($ttd == "0") {
            $tandatangan = "";
        } else {
            // $tandatangan = DB::table('ms_ttd')
            //     ->select('nama', 'nip', 'jabatan', 'pangkat')
            //     ->where('nip', $ttd)
            //     ->whereIn('kode', ['1'])
            //     ->first();
            $tandatangan = collect(DB::select("SELECT top 1 nama,nip,jabatan,pangkat from ms_ttd where nip='$ttd'"))->first();
        }
        $isi    = "sd_bulan_ini";
        $pilih  = "S/D";
        $operator = "<=";
        if ($bulan=="1"||$bulan=="2"||$bulan=="4"||$bulan=="5"||$bulan=="7"||$bulan=="8"||$bulan=="10"||$bulan=="11") {
            $judul  = BULAN($bulan);
            $bulan2 = 12 - $bulan;
        }else if ($bulan=="3"){
            $judul = "TRIWULAN I";
            $bulan2 = 12 - $bulan;
        }else if ($bulan=="6"){
            $judul = "SEMESTER PERTAMA";
            $bulan2 = 12 - $bulan;
        }else if ($bulan=="9"){
            $judul = "TRIWULAN III";
            $bulan2 = 12 - $bulan;
        }else if ($bulan=="2"){
            $judul = "SEMESTER KEDUA";
            $bulan2 = 12 - $bulan;
        }else{
            $judul = "$tanggal1 S/D $tanggal2";
            $bulan2 = "";
        }
        // dd(left($kd_skpd,3));

        // rincian
            if ($jenis_data == 5) { //Jurnal
                if ($periodebulan == 'periode') {
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                            -- anggaran
                                            isnull((SELECT sum(nilai) FROM trdrka
                                                    where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0
                                                    ) AS anggaran,
                                            --realisasi
                                            isnull((
                                                SELECT sum(realisasi) realisasi FROM(
                                                SELECT
                                                b.kd_unit,
                                                b.kd_sub_kegiatan,
                                                b.kd_rek6,
                                                CASE
                                                    WHEN LEFT(b.kd_rek6, 1) = '4' THEN SUM(kredit) - SUM(debet)
                                                    WHEN LEFT(b.kd_rek6, 1) = '5' THEN SUM(debet) - SUM(kredit)
                                                    WHEN LEFT(b.kd_rek6, 2) = '61' THEN SUM(kredit) - SUM(debet)
                                                    WHEN LEFT(b.kd_rek6, 2) = '62' THEN SUM(debet) - SUM(kredit)
                                                    ELSE 0
                                                END AS realisasi
                                                FROM trhju_pkd a
                                                JOIN trdju_pkd b ON a.no_voucher = b.no_voucher
                                                            AND a.kd_skpd = b.kd_unit
                                                WHERE b.kd_rek1_cmp IN ('4', '5', '6')
                                                $skpd_clause AND (tgl_voucher between ? and ? ) and  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                GROUP BY a.tgl_voucher,b.no_voucher,b.kd_unit,b.kd_sub_kegiatan,b.kd_rek6)a
                                            ) ,0)realisasi
                                            FROM map_lra_2023

                                            where group_id <= ?
                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                            ORDER BY  map_lra_2023.id,group_id, nama", [$jns_ang, $tanggal1, $tanggal2, $jns_rincian]);

                    $rincian_pend=DB::select("SELECT kd_sub_kegiatan,kd_rek,nm_rek,SUM(anggaran)anggaran,SUM(sd_bulan_ini)sd_bulan_ini FROM realisasi_jurnal_pend_n_tgl('$tanggal1','$tanggal2','$jns_ang') WHERE $skpd_clause_prog $where1 AND kd_sub_kegiatan<>'' 
                        group by kd_sub_kegiatan,kd_rek,nm_rek
                        ORDER BY kd_sub_kegiatan,kd_rek");
                    $jum_pend=collect(DB::select("SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n_tgl('$tanggal1','$tanggal2','$jns_ang') WHERE $skpd_clause_prog LEN(kd_rek)='$jns_rincian' "))->first();
                    $jum_bel=collect(DB::select("SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini 
                        FROM realisasi_jurnal_rinci_n_tgl('$tanggal1','$tanggal2','$jns_ang') WHERE $skpd_clause_prog LEN(kd_rek)='4' and urut='4' and left(kd_rek,1)in('5')"))->first();
                    $rincian_bel=DB::select("SELECT urut,kd_sub_kegiatan,kd_rek,nm_rek,sum(anggaran)anggaran,sum(sd_bulan_ini)sd_bulan_ini FROM realisasi_jurnal_rinci_n_tgl('$tanggal1','$tanggal2','$jns_ang') WHERE $skpd_clause_prog $where1 AND SUBSTRING(kd_sub_kegiatan,17,2)!='00' 
                        group by urut,kd_sub_kegiatan,kd_rek,nm_rek
                        ORDER BY kd_sub_kegiatan,kd_rek");
                    
                    $sus = collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet_tgl(?,?,?) $skpd_clauses", [$tanggal1, $tanggal2, $jns_ang]))->first();
                } else {
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,  -- anggaran
                                            isnull((SELECT sum(nilai) FROM trdrka
                                                    where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0
                                                    ) AS anggaran,
                                        --realisasi
                                            isnull((
                                                SELECT sum(realisasi) realisasi FROM(
                                                SELECT
                                                b.kd_unit,
                                                b.kd_sub_kegiatan,
                                                b.kd_rek6,
                                                CASE
                                                    WHEN LEFT(b.kd_rek6, 1) = '4' THEN SUM(kredit-debet)
                                                    WHEN LEFT(b.kd_rek6, 1) = '5' THEN SUM(debet-kredit)
                                                    WHEN LEFT(b.kd_rek6, 2) = '61' THEN SUM(kredit-debet)
                                                    WHEN LEFT(b.kd_rek6, 2) = '62' THEN SUM(debet-kredit)
                                                    ELSE 0
                                                END AS realisasi
                                                FROM trhju_pkd a
                                                JOIN trdju_pkd b ON a.no_voucher = b.no_voucher
                                                            AND a.kd_skpd = b.kd_unit
                                                WHERE b.kd_rek1_cmp IN ('4', '5', '6')
                                                $skpd_clause  AND MONTH(tgl_voucher) $operator ? and  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                GROUP BY a.tgl_voucher,b.no_voucher,b.kd_unit,b.kd_sub_kegiatan,b.kd_rek6)a
                                            ) ,0)realisasi

                                            FROM map_lra_2023

                                            where group_id <= ?
                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                            ORDER BY map_lra_2023.id,group_id, nama", [$jns_ang, $bulan, $jns_rincian]);

                    $rincian_pend=DB::select("SELECT kd_sub_kegiatan,kd_rek,nm_rek,SUM(anggaran)anggaran,SUM(sd_bulan_ini)sd_bulan_ini FROM realisasi_jurnal_pend_n($bulan,'$jns_ang',$tahun_anggaran) WHERE $skpd_clause_prog $where1 AND kd_sub_kegiatan<>'' 
                        group by kd_sub_kegiatan,kd_rek,nm_rek
                        ORDER BY kd_sub_kegiatan,kd_rek");
                    $jum_pend=collect(DB::select("SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n($bulan,'$jns_ang',$tahun_anggaran) WHERE $skpd_clause_prog LEN(kd_rek)='$jns_rincian' "))->first();
                    $jum_bel=collect(DB::select("SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini 
                        FROM realisasi_jurnal_rinci_n($bulan,'$jns_ang',$tahun_anggaran) WHERE $skpd_clause_prog LEN(kd_rek)='4' and urut='4' and left(kd_rek,1)in('5')"))->first();
                    $rincian_bel=DB::select("SELECT urut,kd_sub_kegiatan,kd_rek,nm_rek,sum(anggaran)anggaran,sum(sd_bulan_ini)sd_bulan_ini FROM realisasi_jurnal_rinci_n($bulan,'$jns_ang',$tahun_anggaran) WHERE $skpd_clause_prog $where1 AND SUBSTRING(kd_sub_kegiatan,17,2)!='00' 
                        group by urut,kd_sub_kegiatan,kd_rek,nm_rek
                        ORDER BY kd_sub_kegiatan,kd_rek");

                    $sus = collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet(?,?,?) $skpd_clauses", [$bulan, $jns_ang, $tahun_anggaran]))->first();
                }
            } else if ($jenis_data == 4) { // SPJ
                if ($periodebulan == 'periode') {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdtransout a
                                                JOIN trhtransout b ON a.no_bukti = b.no_bukti
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                AND (b.tgl_kas between ? and ? ) and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                union all

                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdtransout_blud a
                                                JOIN trhtransout_blud b ON a.no_bukti = b.no_bukti
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                AND (b.tgl_kas between ? and ? ) and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE (b.tgl_sts between ? and ? ) and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                --hkpg
                                                SELECT   sum(a.rupiah*-1) as realisasi
                                                from trdkasin_pkd a 
                                                INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                                                INNER JOIN trhsp2d e ON b.no_sp2d=e.no_sp2d 
                                                INNER JOIN trspmpot f ON e.no_spm=f.no_spm and f.kd_rek6=a.kd_rek6
                                                LEFT JOIN ms_rek6 c on f.kd_trans=c.kd_rek6 
                                                where (b.tgl_sts between ? and ? ) and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(c.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek and left(a.kd_rek6,1)='2'
                                                group by a.kd_skpd, c.kd_rek6 
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? ) AND  LEFT(b.kd_rek6, 1) = '4' $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $tanggal1, $tanggal2, $tanggal1, $tanggal2,  $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj_tgl(?,?,?)", [$tanggal1, $tanggal2, $jns_ang]))->first();
                } else {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdtransout a
                                                JOIN trhtransout b ON a.no_bukti = b.no_bukti
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                AND MONTH(b.tgl_bukti) $operator ? and year(b.tgl_bukti)=?  and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                union all

                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdtransout_blud a
                                                JOIN trhtransout_blud b ON a.no_bukti = b.no_bukti
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                AND MONTH(b.tgl_bukti) $operator ? and year(b.tgl_bukti)=?  and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE MONTH(b.tgl_sts) $operator ? and year(b.tgl_sts)=?    $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                --hkpg
                                                SELECT   sum(a.rupiah*-1) as realisasi
                                                from trdkasin_pkd a 
                                                INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                                                INNER JOIN trhsp2d e ON b.no_sp2d=e.no_sp2d 
                                                INNER JOIN trspmpot f ON e.no_spm=f.no_spm and f.kd_rek6=a.kd_rek6
                                                LEFT JOIN ms_rek6 c on f.kd_trans=c.kd_rek6 
                                                where MONTH(b.tgl_sts) $operator ? and year(b.tgl_sts)=?    $skpd_clause
                                                AND  LEFT(c.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek and left(a.kd_rek6,1)='2'
                                                group by a.kd_skpd, c.kd_rek6 
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0) realisasi
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? and year(a.tgl_sts)=? AND  LEFT(b.kd_rek6, 1) = '4' $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)realisasi
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? and year(a.tgl_sts)=?  and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah else b.rupiah end),0)realisasi FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? and year(a.tgl_sts)=?  $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj(?,?,?)", [$bulan, $jns_ang, $tahun_anggaran]))->first();
                }
            } else if ($jenis_data == 3) { // SP2D LUNAS
                if ($periodebulan == 'periode') {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdspp a
                                                INNER JOIN trhsp2d b ON a.no_spp = b.no_spp
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                and status_bud = 1
                                                and (sp2d_batal is null OR sp2d_batal <> 1)
                                                AND (b.tgl_sp2d between ? and ? ) and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE (b.tgl_sts between ? and ? ) and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj_tgl(?,?,?)", [$tanggal1, $tanggal2, $jns_ang]))->first();
                } else {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdspp a
                                                INNER JOIN trhsp2d b ON a.no_spp = b.no_spp
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                and status_bud=1
                                                and (sp2d_batal is null OR sp2d_batal <> 1)
                                                AND MONTH(b.tgl_sp2d) $operator ? and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE MONTH(b.tgl_sts) $operator ? and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $bulan, $bulan, $bulan, $bulan, $bulan, $jns_rincian]);

                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj(?,?,?)", [$bulan, $jns_ang, $tahun_anggaran]))->first();
                }
            } else if ($jenis_data == 2) { // SP2D Advis
                if ($periodebulan == 'periode') {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdspp a
                                                INNER JOIN trhsp2d b ON a.no_spp = b.no_spp
                                                AND b.kd_skpd = a.kd_skpd
                                                INNER JOIN trduji c on b.no_sp2d=c.no_sp2d
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                and (sp2d_batal is null OR sp2d_batal <> 1)
                                                AND (b.tgl_sp2d between ? and ? ) and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE (b.tgl_sts between ? and ? ) and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj_tgl(?,?,?)", [$tanggal1, $tanggal2, $jns_ang]))->first();
                } else {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdspp a
                                                INNER JOIN trhsp2d b ON a.no_spp = b.no_spp
                                                AND b.kd_skpd = a.kd_skpd
                                                INNER JOIN trduji c on b.no_sp2d=c.no_sp2d
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                and (sp2d_batal is null OR sp2d_batal <> 1)
                                                AND MONTH(b.tgl_sp2d) $operator ? and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE MONTH(b.tgl_sts) $operator ? and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $bulan, $bulan, $bulan, $bulan, $bulan, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj(?,?,?)", [$bulan, $jns_ang, $tahun_anggaran]))->first();
                }
            } else if ($jenis_data == 1) { // SP2D terbit
                if ($periodebulan == 'periode') {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdspp a
                                                INNER JOIN trhsp2d b ON a.no_spp = b.no_spp
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                and (sp2d_batal is null OR sp2d_batal <> 1)
                                                AND (b.tgl_sp2d between ? and ? ) and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE (b.tgl_sts between ? and ? ) and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                --hkpg
                                                SELECT   sum(a.rupiah*-1) as realisasi
                                                from trdkasin_pkd a 
                                                INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                                                INNER JOIN trhsp2d e ON b.no_sp2d=e.no_sp2d 
                                                INNER JOIN trspmpot f ON e.no_spm=f.no_spm and f.kd_rek6=a.kd_rek6
                                                LEFT JOIN ms_rek6 c on f.kd_trans=c.kd_rek6 
                                                where (b.tgl_sts between ? and ? ) and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(c.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek and left(a.kd_rek6,1)='2'
                                                group by a.kd_skpd, c.kd_rek6 
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause and left(kd_rek6,1)='4'
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE (a.tgl_sts between ? and ? )  $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $tanggal1, $tanggal2, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj_tgl(?,?,?)", [$tanggal1, $tanggal2, $jns_ang]))->first();
                } else {
                    # code...
                    $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                        -- anggaran
                                        isnull((SELECT sum(nilai) FROM trdrka where jns_ang= ? $skpd_clause_ang and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0) AS anggaran,
                                        -- realisasi SPJ
                                        (
                                            SELECT sum(realisasi) from (
                                                SELECT
                                                SUM(a.nilai) realisasi
                                                FROM trdspp a
                                                INNER JOIN trhsp2d b ON a.no_spp = b.no_spp
                                                AND b.kd_skpd = a.kd_skpd
                                                WHERE left(a.kd_rek6,1) IN ('5', '6')  $skpd_clause
                                                and (sp2d_batal is null OR sp2d_batal <> 1)
                                                AND MONTH(b.tgl_sp2d) $operator ? and  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek

                                                UNION ALL
                                                -- CP
                                                SELECT
                                                CASE
                                                    WHEN b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus<>0 THEN sum(a.rupiah)*-1
                                                    WHEN b.jns_trans=5 and b.jns_cp in (2)THEN sum(a.rupiah)*-1
                                                ELSE 0
                                                END as realisasi
                                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                                WHERE MONTH(b.tgl_sts) $operator ? and left(a.kd_rek6,1)='5'  $skpd_clause
                                                AND  LEFT(a.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by b.jns_trans,b.jns_cp,b.pot_khusus
                                                UNION ALL
                                                -- PENDAPATAN
                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_pkd a INNER JOIN trdkasin_pkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('3') then b.rupiah*-1 else b.rupiah end),0)
                                                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? and b.kd_rek6='410411010001' and b.kd_skpd='5.02.0.00.0.00.02.0000'
                                                $skpd_clause
                                                AND  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                                UNION ALL

                                                SELECT isnull(SUM(case when jns_trans in ('4') then b.rupiah*-1 else b.rupiah end),0) FROM trhkasin_blud a INNER JOIN trdkasin_blud b
                                                ON RTRIM(a.no_sts)=RTRIM(b.no_sts) and a.kd_skpd=b.kd_skpd
                                                WHERE month(a.tgl_sts) $operator ? $skpd_clause
                                                AND  LEFT(b.kd_rek5, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                group by a.jns_trans
                                            )zzzz
                                                ) AS realisasi
                                                            FROM map_lra_2023
                                                            where group_id <= ?
                                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                                            ORDER BY id,group_id, nama", [$jns_ang, $bulan, $bulan, $bulan, $bulan, $bulan, $jns_rincian]);
                    $sus = collect(DB::select("SELECT * FROM data_jurnal_n_sal_awal_spj(?,?,?)", [$bulan, $jns_ang, $tahun_anggaran]))->first();
                }
            }




        // dd($sus);
        if ($jenis_data == 5 && $kd_skpd!= "") {
            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
                'kd_skpd'           => $kd_skpd,
                'skpdunit'          => $skpdunit,
                'tanggal1'          => $tanggal1,
                'tanggal2'          => $tanggal2,
                'periodebulan'      => $periodebulan,
                'rincian_pend'      => $rincian_pend,
                'rincian_bel'       => $rincian_bel,
                'jum_pend'          => $jum_pend,
                'jum_bel'           => $jum_bel,
                'enter'             => $enter,
                'daerah'            => $daerah,
                'tanggal_ttd'       => $tanggal_ttd,
                'tandatangan'       => $tandatangan,
                'tahun_anggaran'    => $tahun_anggaran,
                'bulan'             => $bulan,
                'bulan2'            => $bulan2,
                'judul'             => $judul,
                'pilih'             => $pilih,
                'jenis_ttd'         => $ttd,
                'jenis'             => $jns_rincian,
                'sus'               => $sus
            ];
        
        $view =  view('akuntansi.cetakan.lapkeu.semester_rinci_jurnal')->with($data);
        }else if ($jenis_data == 5 && $kd_skpd== "") {
            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
                'kd_skpd'           => $kd_skpd,
                'skpdunit'          => $skpdunit,
                'tanggal1'          => $tanggal1,
                'tanggal2'          => $tanggal2,
                'periodebulan'      => $periodebulan,
                'rincian'      => $rincian,
                'rincian_bel'       => $rincian_bel,
                'jum_pend'          => $jum_pend,
                'jum_bel'           => $jum_bel,
                'enter'             => $enter,
                'daerah'            => $daerah,
                'tanggal_ttd'       => $tanggal_ttd,
                'tandatangan'       => $tandatangan,
                'tahun_anggaran'    => $tahun_anggaran,
                'bulan'             => $bulan,
                'bulan2'            => $bulan2,
                'judul'             => $judul,
                'pilih'             => $pilih,
                'jenis_ttd'         => $ttd,
                'jenis'             => $jns_rincian,
                'sus'               => $sus
            ];
        
        $view =  view('akuntansi.cetakan.lapkeu.semester')->with($data);
        }else{

            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
                'kd_skpd'           => $kd_skpd,
                'skpdunit'          => $skpdunit,
                'tanggal1'          => $tanggal1,
                'tanggal2'          => $tanggal2,
                'periodebulan'      => $periodebulan,
                'rincian'           => $rincian,
                'enter'             => $enter,
                'daerah'            => $daerah,
                'tanggal_ttd'       => $tanggal_ttd,
                'tandatangan'       => $tandatangan,
                'tahun_anggaran'    => $tahun_anggaran,
                'bulan'             => $bulan,
                'bulan2'            => $bulan2,
                'judul'             => $judul,
                'pilih'             => $pilih,
                'jenis_ttd'         => $ttd,
                'jenis'             => $jns_rincian,
                'sus'               => $sus
            ];
        
        $view =  view('akuntansi.cetakan.lapkeu.semester')->with($data);
        }
        

        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LRA Semester.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LRA Semester.xls"');
            return $view;
        }
    }
}
