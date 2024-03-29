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

class LraController extends Controller
{

    public function cetakLra(Request $request)
    {
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
        $akumulasi      = $request->pilihakumulsai;
        $jns_ang        = $request->jenis_anggaran;
        $periodebulan   = $request->periodebulan;
        $jenis_data     = $request->jenis_data;
        $jns_rincian    = $request->jns_rincian;
        $skpdunit    = $request->skpdunit;
        $kd_skpd        = $request->kd_skpd;
        // dd($skpdunit);
        if ($kd_skpd == '') {
            $kd_skpd        = "";
            $skpd_clause = "";
            $skpd_clauses = "";
            $skpd_clause_prog = "";
            $skpd_clause_ang = "";
        } else {
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
        // dd($kd_skpd);

        $tahun_anggaran = tahun_anggaran();

        // TANDA TANGAN
        if ($ttd == '0') {
            $tandatangan = "";
        } else {
            $tandatangan = DB::table('ms_ttd')
                ->select('nama', 'nip', 'jabatan', 'pangkat')
                ->where('nip', $ttd)
                ->whereIn('kode', ['1'])
                ->first();
        }
        if ($akumulasi == 'akum') {
            $isi    = "sd_bulan_ini";
            $pilih  = "S/D";
            $judul  = BULAN($bulan);
            $operator = "<=";
        } else {
            $isi = "bulan_ini";
            $pilih = "BULAN";
            $judul  = BULAN($bulan);
            $operator = "=";
        }

        if ($format == 'sng') {
            if ($periodebulan == 'periode') {
                $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,
                                            -- anggaran
                                            isnull((SELECT sum(nilai) FROM trdrka
                                                    where jns_ang= ? and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0
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
                                                FROM trhju_sinergi a
                                                JOIN trdju_sinergi b ON a.no_voucher = b.no_voucher
                                                            AND a.kd_skpd = b.kd_unit
                                                WHERE  left(b.kd_rek6,1) IN ('4', '5', '6')
                                                $skpd_clause AND (tgl_voucher between ? and ? ) and  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                GROUP BY a.tgl_voucher,b.no_voucher,b.kd_unit,b.kd_sub_kegiatan,b.kd_rek6)a
                                            ) ,0)realisasi
                                            FROM map_lra_2023

                                            where group_id <= ?
                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                            ORDER BY map_lra_2023.id,group_id, nama", [$jns_ang, $tanggal1, $tanggal2, $jns_rincian]);
                $sus = collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet_tgl_sinergi(?,?,?) $skpd_clauses", [$tanggal1, $tanggal2, $jns_ang]))->first();
            } else {
                $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align,  -- anggaran
                                            isnull((SELECT sum(nilai) FROM trdrka
                                                    where jns_ang= ? and LEFT(trdrka.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek),0
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
                                                FROM trhju_sinergi a
                                                JOIN trdju_sinergi b ON a.no_voucher = b.no_voucher
                                                            AND a.kd_skpd = b.kd_unit
                                                WHERE b.kd_rek1_cmp IN ('4', '5', '6')
                                                $skpd_clause  AND MONTH(tgl_voucher) $operator ? and  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                GROUP BY a.tgl_voucher,b.no_voucher,b.kd_unit,b.kd_sub_kegiatan,b.kd_rek6)a
                                            ) ,0)realisasi

                                            FROM map_lra_2023

                                            where group_id <= ?
                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                            ORDER BY map_lra_2023.id,group_id, nama", [$jns_ang, $bulan, $jns_rincian]);
                $sus = collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet_sinergi(?,?,?) $skpd_clauses", [$bulan, $jns_ang, $tahun_anggaran]))->first();
            }
        } else if ($format == 'prog') {
            if ($periodebulan == 'periode') {
                $rincian = DB::select(
                    "SELECT *,(anggaran-sd_bulan_ini) sisa
                                        from
                                        (SELECT '1'urut,kd_sub_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_pend_n_tgl($tanggal1,$tanggal2,?) WHERE $skpd_clause_prog LEN(kd_rek)<=$jns_rincian AND
                                                            kd_sub_kegiatan<>''
                                        union all
                                        SELECT '2' urut,''kd_sub_kegiatan,''kd_rek,''nm_rek,0 anggaran ,0 sd_bulan_ini

                                        union all
                                        SELECT '3' urut,''kd_sub_kegiatan,''kd_rek,'Jumlah Pendapatan'nm_rek,SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n_tgl($tanggal1,$tanggal2,?)
                                        WHERE $skpd_clause_prog LEN(kd_rek)=$jns_rincian

                                        union all
                                        SELECT '4' urut,''kd_sub_kegiatan,''kd_rek,''nm_rek,0 anggaran ,0 sd_bulan_ini

                                        union all
                                        SELECT '5' urut,''kd_sub_kegiatan,''kd_rek,'Belanja Daerah'nm_rek,SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini
                                        FROM realisasi_jurnal_rinci_n_tgl($tanggal1,$tanggal2,?) WHERE $skpd_clause_prog LEN(kd_rek)='4' and urut='4' and left(kd_rek,1)in('5')

                                        union all
                                        SELECT '6' urut,''kd_sub_kegiatan,''kd_rek,''nm_rek,0 anggaran ,0 sd_bulan_ini

                                        union all
                                        SELECT '7'urut,kd_sub_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci_n_tgl($tanggal1,$tanggal2,?) WHERE $skpd_clause_prog LEN(kd_rek)<=$jns_rincian AND SUBSTRING(kd_sub_kegiatan,17,2)!='00'

                                        union all
                                        SELECT '8' urut,''kd_sub_kegiatan,''kd_rek,''nm_rek,0 anggaran ,0 sd_bulan_ini

                                        union all
                                        SELECT '9' urut,''kd_sub_kegiatan,''kd_rek,'Jumlah Belanja'nm_rek,SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini
                                        FROM realisasi_jurnal_rinci_n_tgl($tanggal1,$tanggal2,?) WHERE $skpd_clause_prog LEN(kd_rek)='4' and urut='4' and left(kd_rek,1)in('5')
                                        ) a

                                        ORDER BY urut,kd_sub_kegiatan,kd_rek",
                    [$jns_ang, $jns_ang, $jns_ang, $jns_ang, $jns_ang]
                );
            } else {
                $rincian = DB::select(
                    "SELECT *,(anggaran-sd_bulan_ini) sisa
                                        from
                                        (SELECT '1'urut,kd_sub_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_pend_n($bulan,?,$tahun_anggaran) WHERE $skpd_clause_prog LEN(kd_rek)<=$jns_rincian AND
                                                            kd_sub_kegiatan<>''
                                        union all
                                        SELECT '2' urut,''kd_sub_kegiatan,''kd_rek,''nm_rek,0 anggaran ,0 sd_bulan_ini

                                        union all
                                        SELECT '3' urut,''kd_sub_kegiatan,''kd_rek,'Jumlah Pendapatan'nm_rek,SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n($bulan,?,$tahun_anggaran)
                                        WHERE $skpd_clause_prog LEN(kd_rek)=$jns_rincian

                                        union all
                                        SELECT '4' urut,''kd_sub_kegiatan,''kd_rek,''nm_rek,0 anggaran ,0 sd_bulan_ini

                                        union all
                                        SELECT '5' urut,''kd_sub_kegiatan,''kd_rek,'Belanja Daerah'nm_rek,SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini
                                        FROM realisasi_jurnal_rinci_n($bulan,?,$tahun_anggaran) WHERE $skpd_clause_prog LEN(kd_rek)='4' and urut='4' and left(kd_rek,1)in('5')

                                        union all
                                        SELECT '6' urut,''kd_sub_kegiatan,''kd_rek,''nm_rek,0 anggaran ,0 sd_bulan_ini

                                        union all
                                        SELECT '7'urut,kd_sub_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci_n($bulan,?,$tahun_anggaran) WHERE $skpd_clause_prog LEN(kd_rek)<=$jns_rincian AND SUBSTRING(kd_sub_kegiatan,17,2)!='00'

                                        union all
                                        SELECT '8' urut,''kd_sub_kegiatan,''kd_rek,''nm_rek,0 anggaran ,0 sd_bulan_ini

                                        union all
                                        SELECT '9' urut,''kd_sub_kegiatan,''kd_rek,'Jumlah Belanja'nm_rek,SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini
                                        FROM realisasi_jurnal_rinci_n($bulan,?,$tahun_anggaran) WHERE $skpd_clause_prog LEN(kd_rek)='4' and urut='4' and left(kd_rek,1)in('5')
                                        ) a

                                        ORDER BY urut,kd_sub_kegiatan,kd_rek",
                    [$jns_ang, $jns_ang, $jns_ang, $jns_ang, $jns_ang]
                );
            }
        } else {

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
                                                FROM trhju a
                                                JOIN trdju b ON a.no_voucher = b.no_voucher
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
                                                FROM trhju a
                                                JOIN trdju b ON a.no_voucher = b.no_voucher
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
            } else if ($jenis_data == 4) {
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
                                                union all

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
                                                            ORDER BY id,group_id, nama", [$jns_ang, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran, $bulan, $tahun_anggaran,$bulan, $tahun_anggaran,$bulan, $tahun_anggaran,  $jns_rincian]);
                    $sus = collect(DB::select("SELECT sum(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_sal_awal_spj(?,?,?) $skpd_clauses", [$bulan, $jns_ang, $tahun_anggaran]))->first();
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
                } else if ($periodebulan == 'bulan'){
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

                    $sus = collect(DB::select("SELECT sum(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_sal_awal_spj(?,?,?) $skpd_clauses", [$bulan, $jns_ang, $tahun_anggaran]))->first();
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
                                                            ORDER BY id,group_id, nama", [$jns_ang, 12, 12, 12, 12, 12, $jns_rincian]);

                    $sus = collect(DB::select("SELECT sum(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_sal_awal_spj(?,?,?) $skpd_clauses", [12, $jns_ang, $tahun_anggaran]))->first();
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
                } else if ($periodebulan == 'bulan') {
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
                    $sus = collect(DB::select("SELECT sum(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_sal_awal_spj(?,?,?) $skpd_clauses", [$bulan, $jns_ang, $tahun_anggaran]))->first();
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
                                                            ORDER BY id,group_id, nama", [$jns_ang, 12, 12, 12, 12, 12, $jns_rincian]);
                    $sus = collect(DB::select("SELECT sum(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_sal_awal_spj(?,?,?) $skpd_clauses", [12, $jns_ang, $tahun_anggaran]))->first();
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
                } else if ($periodebulan == 'bulan'){
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
                    $sus = collect(DB::select("SELECT sum(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_sal_awal_spj(?,?,?) $skpd_clauses", [$bulan, $jns_ang, $tahun_anggaran]))->first();
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
                                                            ORDER BY id,group_id, nama", [$jns_ang, 12, 12, 12, 12, 12, $jns_rincian]);
                    $sus = collect(DB::select("SELECT sum(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_sal_awal_spj(?,?,?) $skpd_clauses", [12, $jns_ang, $tahun_anggaran]))->first();
                }
            }
        }




        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
        // dd($sus);
        if ($format == 'prog') {
            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
                'rincian'           => $rincian,
                'enter'             => $enter,
                'daerah'            => $daerah,
                'tanggal_ttd'       => $tanggal_ttd,
                'tandatangan'       => $tandatangan,
                'judul'             => $bulan,
                'pilih'             => $pilih,
                'jenis_ttd'         => $ttd,
                'jenis'             => $jns_rincian
            ];
        } else {

            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
                'rincian'           => $rincian,
                'enter'             => $enter,
                'daerah'            => $daerah,
                'tanggal_ttd'       => $tanggal_ttd,
                'tandatangan'       => $tandatangan,
                'judul'             => $bulan,
                'pilih'             => $pilih,
                'jenis_ttd'         => $ttd,
                'jenis'             => $jns_rincian,
                'sus'               => $sus
            ];
        }
        if ($format == 'sap') {
            $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        } elseif ($format == 'djpk') {
            $view =  view('akuntansi.cetakan.lra_djpk')->with($data);
        } elseif ($format == 'p77') {
            $view =  view('akuntansi.cetakan.lra_77')->with($data);
        } elseif ($format == 'sng') {
            $view =  view('akuntansi.cetakan.lra_sinergi')->with($data);
        } elseif ($format == 'prog') {
            $view =  view('akuntansi.cetakan.lra_program')->with($data);
        }

        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LRA 77.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LRA 77.xls"');
            return $view;
        }
    }

    public function cetakneraca(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $bulan          = $request->bulan;
        $format          = $request->format;
        $enter          = $request->spasi;
        $cetak          = $request->cetak;
        $skpdunit    = $request->skpdunit;
        $kd_skpd        = $request->kd_skpd;
        if ($skpdunit == "keseluruhan") {
            $kd_skpd        = "";
            $skpd_clause    = "";
            $skpd_clauses    = "";
            $skpd_clausesun    = "";
        } else {
            if ($skpdunit == "unit") {
                $kd_skpd = $kd_skpd;
            } else if ($skpdunit == "skpd") {
                $kd_skpd = substr($kd_skpd, 0, 17);
            }
            $skpd_clause    = "where left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clauses    = "and left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clausesun    = "where left(kd_unit,len('$kd_skpd'))='$kd_skpd'";
        }

        $thn_ang    = tahun_anggaran();
        $thn_ang1   = $thn_ang - 1;

        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);
        $nm_bln = $arraybulan[$bulan];
        if (strlen($bulan) == 1) {
            $bulan = "0$bulan";
        } else {
            $bulan = $bulan;
        }

        // dd($bulan);

        if ($format == '1') {

            $ekuitas = collect(DB::select("SELECT sum(nilai)ekuitas from data_ekuitas($bulan,$thn_ang,$thn_ang1,'$thn_ang$bulan') $skpd_clausesun"))->first();
            $ekuitas_tanpa_rkppkd = collect(DB::select("SELECT sum(nilai)ekuitas_tanpa_rkppkd from data_ekuitas_tanpa_rkppkd($bulan,$thn_ang,$thn_ang1,'$thn_ang$bulan') $skpd_clausesun"))->first();
            $ekuitas_lalu = collect(DB::select("SELECT sum(nilai)ekuitas_lalu from data_ekuitas_lalu($bulan,$thn_ang,$thn_ang1) $skpd_clausesun"))->first();
            $map_neraca = DB::select("SELECT kode, uraian, seq,bold, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3,
                isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7,
                    isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
                    isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15, isnull(kecuali,'xxx') as kecuali, isnull(c_kurangi,'xxx') as c_kurangi, isnull(kurangi_1,'xxx') as kurangi_1, isnull(c_tambah,'xxx') as c_tambah, isnull(tambah_1,'xxx') as tambah_1
                FROM map_neraca_permen_77 ORDER BY seq");
        } else if ($format == '2') {
            $ekuitas = collect(DB::select("SELECT sum(nilai)ekuitas from data_ekuitas($bulan,$thn_ang,$thn_ang1,'$thn_ang$bulan') $skpd_clausesun"))->first();
            $ekuitas_tanpa_rkppkd = collect(DB::select("SELECT sum(nilai)ekuitas_tanpa_rkppkd from data_ekuitas_tanpa_rkppkd($bulan,$thn_ang,$thn_ang1,'$thn_ang$bulan') $skpd_clausesun"))->first();
            $ekuitas_lalu = collect(DB::select("SELECT sum(nilai)ekuitas_lalu from data_ekuitas_lalu($bulan,$thn_ang,$thn_ang1) $skpd_clausesun"))->first();
            $map_neraca = DB::select("SELECT kode, uraian, seq,bold, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3,
                isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7,
                    isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
                    isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15, isnull(kecuali,'xxx') as kecuali, isnull(c_kurangi,'xxx') as c_kurangi, isnull(kurangi_1,'xxx') as kurangi_1, isnull(c_tambah,'xxx') as c_tambah, isnull(tambah_1,'xxx') as tambah_1
                FROM map_neraca_permen_77_obyek ORDER BY seq");
        } else if ($format == '3') {
        }
            // $test = "SELECT sum(nilai)ekuitas from data_ekuitas($bulan,$thn_ang,$thn_ang1,'$thn_ang$bulan') $skpd_clausesun";
            // dd($test);




        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
        // dd($sus);
        if ($format == '1') {
            $data = [
                'header'                => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
                'ekuitas'               => $ekuitas,
                'ekuitas_tanpa_rkppkd'  => $ekuitas_tanpa_rkppkd->ekuitas_tanpa_rkppkd,
                'ekuitas_lalu'          => $ekuitas_lalu,
                'map_neraca'            => $map_neraca,
                'enter'                 => $enter,
                'daerah'                => $daerah,
                'bulan'                 => $bulan,
                'skpd_clauses'          => $skpd_clauses,
                'kd_skpd'               => $kd_skpd,
                'nm_bln'                => $nm_bln,
                'thn_ang'               => $thn_ang,
                'thn_ang1'              => $thn_ang1,
                'skpdunit'              => $skpdunit
            ];

            $view =  view('akuntansi.cetakan.neraca')->with($data);
        } else if ($format == '2') {
            $data = [
                'header'                => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
                'ekuitas'               => $ekuitas,
                'ekuitas_tanpa_rkppkd'  => $ekuitas_tanpa_rkppkd,
                'ekuitas_lalu'          => $ekuitas_lalu,
                'map_neraca'            => $map_neraca,
                'enter'                 => $enter,
                'daerah'                => $daerah,
                'bulan'                 => $bulan,
                'skpd_clauses'          => $skpd_clauses,
                'kd_skpd'               => $kd_skpd,
                'nm_bln'                => $nm_bln,
                'thn_ang'               => $thn_ang,
                'thn_ang1'              => $thn_ang1,
                'skpdunit'              => $skpdunit
            ];

            $view =  view('akuntansi.cetakan.neraca_obyek')->with($data);
        } else if ($format == '3') {
        }



        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Neraca.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Neraca.xls"');
            return $view;
        }
    }

    public function cetaklo(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $bulan          = $request->bulan;
        $format          = $request->format;
        $enter          = $request->spasi;
        $cetak          = $request->cetak;
        $kd_skpd        = $request->kd_skpd;
        $skpdunit    = $request->skpdunit;
        if ($skpdunit == "keseluruhan") {
            $kd_skpd        = "";
            $skpd_clause    = "";
            $skpd_clauses    = "";
        } else {
            if ($skpdunit == "unit") {
                $kd_skpd = $kd_skpd;
            } else if ($skpdunit == "skpd") {
                $kd_skpd = substr($kd_skpd, 0, 17);
            }
            $skpd_clause    = "where left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clauses    = "and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'";
        }

        $thn_ang    = tahun_anggaran();
        $thn_ang1   = $thn_ang - 1;

        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);
        $nm_bln = $arraybulan[$bulan];



        if ($format == "1") {
            $map_lo = DB::select("SELECT seq,bold, nor, uraian, isnull(kode_1ja,'-') as kode_1ja, isnull(kode,'-') as kode, isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak
                , isnull(kurangi_1,'-') kurangi_1, isnull(kurangi,'-') kurangi, isnull(c_kurangi,0) as c_kurangi
                FROM map_lo_prov_permen_77
                GROUP BY seq,bold, nor, uraian, isnull(kode_1ja,'-'), isnull(kode,'-'), isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet') ,
                isnull(kurangi_1,'-') , isnull(kurangi,'-') , isnull(c_kurangi,0)
                ORDER BY nor");
        } else if ($format == "2") {

            $map_lo = DB::select("SELECT seq,bold, nor, uraian, 
                isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(kode_4,'-') as kode_4, isnull(kode_5,'-') as kode_5, isnull(kode_6,'-') as kode_6, 
                isnull(cetak,'debet-debet') as cetak,
                isnull(kurangi_1,'-') as kurangi_1, isnull(kurangi_2,'-') as kurangi_2, isnull(kurangi_3,'-') as kurangi_3, isnull(kurangi_4,'-') as kurangi_4, isnull(kurangi_5,'-') as kurangi_5, isnull(kurangi_6,'-') as kurangi_6, 
                isnull(c_kurangi,'debet-debet') as c_kurangi,kelompok
                FROM map_lo_prov_rinci
                ORDER BY nor");
        }





        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
        // dd($map_lo);

        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'map_lo'            => $map_lo,
            'enter'             => $enter,
            'daerah'            => $daerah,
            'bulan'             => $bulan,
            'skpd_clauses'      => $skpd_clauses,
            'skpdunit'      => $skpdunit,
            'kd_skpd'           => $kd_skpd,
            'nm_bln'            => $nm_bln,
            'thn_ang'           => $thn_ang,
            'thn_ang_1'         => $thn_ang1
        ];

        if ($format=="1") {
            $view =  view('akuntansi.cetakan.lo')->with($data);
        }else{
            $view =  view('akuntansi.cetakan.lo_rinci')->with($data);
        }


        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LO.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LO.xls"');
            return $view;
        }
    }

    public function cetaklpe(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $bulan          = $request->bulan;
        $enter          = $request->spasi;
        $cetak          = $request->cetak;
        $kd_skpd        = $request->kd_skpd;
        $skpdunit    = $request->skpdunit;
        if ($request->kd_skpd == '') {
            $kd_skpd        = "";
            $skpd_clause    = "";
            $skpd_clauses    = "";
        } else {
            if ($skpdunit == "unit") {
                $kd_skpd = $kd_skpd;
            } else if ($skpdunit == "skpd") {
                $kd_skpd = substr($kd_skpd, 0, 17);
            }
            $skpd_clause    = "where left(kd_unit,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clauses    = "and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'";
        }

        $thn_ang    = tahun_anggaran();
        $thn_ang1   = $thn_ang - 1;

        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);
        $nm_bln = $arraybulan[$bulan];




        $ekuitas_awal = collect(DB::select("SELECT sum(nilai) nilai,sum(nilai_lalu) nilai_lalu
                        from(
                        --1 ekuitas_awal
                        select isnull(sum(nilai),0)nilai,0 nilai_lalu from data_ekuitas_lalu($bulan,$thn_ang,$thn_ang1) $skpd_clause
                        union all
                        --1 ekuitas lalu
                        select 0 nilai, isnull(sum(nilai),0)nilai_lalu from data_real_ekuitas_lalu($bulan,$thn_ang,$thn_ang1) $skpd_clause
                        )a"))->first();
        // dd($ekuitas_awal);
        $surdef = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                        from(
                        --2 surplus lo
                        select sum(nilai_pen-nilai_bel) nilai,0 nilai_lalu
                        from(
                            select sum(kredit-debet) as nilai_pen,0 nilai_bel from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') $skpd_clauses
                            union all
                            select 0 nilai_pen,sum(debet-kredit) as nilai_bel from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') $skpd_clauses
                            )a
                            union all
                            -- 2 surplus lo lalu
                            select 0 nilai,isnull(sum(nilai_pen-nilai_bel),0) nilai_lalu
                            from(
                            select sum(kredit-debet) as nilai_pen,0 nilai_bel
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where year(tgl_voucher)=$thn_ang1 and left(kd_rek6,1) in ('7') $skpd_clauses
                            union all
                            select 0 nilai_pen,sum(debet-kredit) as nilai_bel
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where year(tgl_voucher)=$thn_ang1 and left(kd_rek6,1) in ('8') $skpd_clauses
                            )a
                        )a"))->first();

        $koreksi = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                        from(
                            --5 nilai lpe 1
                            select isnull(sum(kredit-debet),0) nilai , 0 nilai_lalu
                            from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where  reev='2' and kd_rek6='310101010001' and year(b.tgl_voucher)=$thn_ang and month(b.tgl_voucher)<=$bulan $skpd_clauses
                            union all
                            --5 nilai lpe 1 lalu
                            select 0 nilai,isnull(sum(kredit-debet),0) nilai_lalu
                            from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where  reev='2' and kd_rek6='310101010001' and year(b.tgl_voucher)=$thn_ang1 $skpd_clauses
                        )a"))->first();

        $selisih = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                        from(
                            --6 nilai dr
                            select isnull(sum(kredit-debet),0) nilai, 0 nilai_lalu
                            from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where  reev='1' and kd_rek6='310101010001' and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan $skpd_clauses
                            union all
                            --6 nilai dr lalu
                            select 0 nilai, isnull(sum(kredit-debet),0) nilai_lalu
                            from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where  reev='1' and kd_rek6='310101010001' and year(tgl_voucher)=$thn_ang1 $skpd_clauses
                        )a"))->first();

        $lain = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                        from(
                            --7 nilai lpe2
                            select isnull(sum(kredit-debet),0) nilai, 0 nilai_lalu
                            from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where  reev='3' and kd_rek6='310101010001' and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan $skpd_clauses
                            union all
                            --7 nilai lpe2 lalu
                            select 0 nilai,isnull(sum(kredit-debet),0) nilai_lalu
                            from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where  reev='3' and kd_rek6='310101010001' and year(tgl_voucher)=$thn_ang1 $skpd_clauses
                        )a"))->first();






        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
        // dd($sus);

        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            // 'ekuitas_awal'      => $ekuitas_awal,
            'ekuitas_awal'      => $ekuitas_awal->nilai,
            'ekuitas_awal_lalu'      => $ekuitas_awal->nilai_lalu,
            'surdef'            => $surdef->nilai,
            'surdef_lalu'            => $surdef->nilai_lalu,
            'koreksi'           => $koreksi->nilai,
            'koreksi_lalu'           => $koreksi->nilai_lalu,
            'selisih'           => $selisih->nilai,
            'selisih_lalu'           => $selisih->nilai_lalu,
            'lain'              => $lain->nilai,
            'lain_lalu'              => $lain->nilai_lalu,
            'enter'             => $enter,
            'daerah'            => $daerah,
            'bulan'             => $bulan,
            'kd_skpd'           => $kd_skpd,
            'nm_bln'            => $nm_bln,
            'thn_ang'           => $thn_ang,
            'thn_ang1'         => $thn_ang1
        ];
        // dd($data['ekuitas_awal']->nilai);
        $view =  view('akuntansi.cetakan.lpe')->with($data);


        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LPE.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LPE.xls"');
            return $view;
        }
    }

    public function cetaklak(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $bulan          = $request->bulan;
        $enter          = $request->spasi;
        $cetak          = $request->cetak;
        $kd_skpd        = Auth::user()->kd_skpd;

        // dd($kd_skpd);

        $thn_ang    = tahun_anggaran();
        $thn_ang1   = $thn_ang - 1;

        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);
        $nm_bln = $arraybulan[$bulan];

        $map = DB::select("SELECT * FROM map_lak_prov_permen77 ORDER BY seq");

        $sql_pfk_masuk = collect(DB::select("SELECT sum(nilai) nilai 
                from (
                select sum(a.nilai) nilai 
                from trdtrmpot a inner join trhtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 
                where a.kd_skpd<>'' and b.jns_spp in ('1','2','3','4','5','6') and 
                left(a.kd_rek6,12) in ('210106010001','210105010001','210105020001','210105030001','210109010001','210108010001','210107010001 ','210103010001','210104010001','210102010001') 
                and year(b.tgl_bukti)=$thn_ang and month(b.tgl_bukti)<=$bulan)a"))->first();
        $pfk_masuk = $sql_pfk_masuk->nilai;

        $sql_pfk_keluar = collect(DB::select("SELECT sum(nilai) nilai from (
                    select sum(a.nilai) nilai from trdstrpot a inner join trhstrpot b on a.no_bukti=b.no_bukti and 
                    a.kd_skpd=b.kd_skpd where a.kd_skpd<>'' and b.jns_spp in ('1','2','3','4','5','6') and left(a.kd_rek6,12) in ('210106010001','210105010001','210105020001','210105030001','210109010001','2111001','2110901','2111101','210108010001','210107010001 ','210103010001','210104010001','210102010001') and year(b.tgl_bukti)=$thn_ang and month(b.tgl_bukti)<=$bulan)a"))->first();
        $pfk_keluar = $sql_pfk_keluar->nilai;
        $pfk_bersih = $pfk_masuk-$pfk_keluar;
        //operasi bersih seq 170
        $sqlseq170 = collect(DB::select("SELECT isnull(kode_1,'999') kode_1, isnull(kode_2,'999') kode_2 , isnull(kode_3,'999') kode_3, isnull(kecuali_1,'999') kecuali_1, isnull(kecuali_2,'999') kecuali_2, isnull(kecuali_3,'999') kecuali_3,tambah from map_lak_prov_permen77 where seq='170'"))->first();
        $kode_1_seq170 = $sqlseq170->kode_1;
        $kode_2_seq170 = $sqlseq170->kode_2;
        $kode_3_seq170 = $sqlseq170->kode_3;
        $kecuali_1_seq170 = $sqlseq170->kecuali_1;
        $kecuali_2_seq170 = $sqlseq170->kecuali_2;
        $kecuali_3_seq170 = $sqlseq170->kecuali_3;
        $tambah_seq170 = $sqlseq170->tambah;
        $ob170 = collect(DB::select("SELECT SUM(nilai) nilai
                    from(
                    SELECT SUM(realisasi) as nilai FROM Data_realisasi_tanpa_anggaran($bulan,$thn_ang)
                    WHERE (left(kd_rek6,4) in ($kode_1_seq170) 
                    or left(kd_rek6,6) in ($kode_2_seq170) 
                    or left(kd_rek6,8) in ($kode_3_seq170))
                    union all
                    SELECT SUM(realisasi*-1) as nilai FROM Data_realisasi_tanpa_anggaran($bulan,$thn_ang)
                    WHERE (left(kd_rek6,4) in ($kecuali_1_seq170) 
                    or left(kd_rek6,6) in ($kecuali_2_seq170) 
                    or left(kd_rek6,8) in ($kecuali_3_seq170)))a"))->first();
        $operasi_bersih = $ob170->nilai+$tambah_seq170;
        //
        //investasi bersih seq 275
        $sqlseq275 = collect(DB::select("SELECT isnull(kode_1,'999') kode_1, isnull(kode_2,'999') kode_2 , isnull(kode_3,'999') kode_3, isnull(kecuali_1,'999') kecuali_1, isnull(kecuali_2,'999') kecuali_2, isnull(kecuali_3,'999') kecuali_3,tambah from map_lak_prov_permen77 where seq='275'"))->first();
        $kode_1_seq275 = $sqlseq275->kode_1;
        $kode_2_seq275 = $sqlseq275->kode_2;
        $kode_3_seq275 = $sqlseq275->kode_3;
        $kecuali_1_seq275 = $sqlseq275->kecuali_1;
        $kecuali_2_seq275 = $sqlseq275->kecuali_2;
        $kecuali_3_seq275 = $sqlseq275->kecuali_3;
        $tambah_seq275 = $sqlseq275->tambah;
        $ob275 = collect(DB::select("SELECT SUM(nilai) nilai
                    from(
                    SELECT SUM(realisasi) as nilai FROM Data_realisasi_tanpa_anggaran($bulan,$thn_ang)
                    WHERE (left(kd_rek6,4) in ($kode_1_seq275) 
                    or left(kd_rek6,6) in ($kode_2_seq275) 
                    or left(kd_rek6,8) in ($kode_3_seq275))
                    union all
                    SELECT SUM(realisasi*-1) as nilai FROM Data_realisasi_tanpa_anggaran($bulan,$thn_ang)
                    WHERE (left(kd_rek6,4) in ($kecuali_1_seq275) 
                    or left(kd_rek6,6) in ($kecuali_2_seq275) 
                    or left(kd_rek6,8) in ($kecuali_3_seq275)))a"))->first();
        $investasi_bersih = $ob275->nilai+$tambah_seq275;
        //
        //pendanaan bersih seq 360
        $sqlseq360 = collect(DB::select("SELECT isnull(kode_1,'999') kode_1, isnull(kode_2,'999') kode_2 , isnull(kode_3,'999') kode_3, isnull(kecuali_1,'999') kecuali_1, isnull(kecuali_2,'999') kecuali_2, isnull(kecuali_3,'999') kecuali_3,tambah from map_lak_prov_permen77 where seq='360'"))->first();
        $kode_1_seq360 = $sqlseq360->kode_1;
        $kode_2_seq360 = $sqlseq360->kode_2;
        $kode_3_seq360 = $sqlseq360->kode_3;
        $kecuali_1_seq360 = $sqlseq360->kecuali_1;
        $kecuali_2_seq360 = $sqlseq360->kecuali_2;
        $kecuali_3_seq360 = $sqlseq360->kecuali_3;
        $tambah_seq360 = $sqlseq360->tambah;
        $ob360 = collect(DB::select("SELECT SUM(nilai) nilai
                    from(
                    SELECT SUM(realisasi) as nilai FROM Data_realisasi_tanpa_anggaran($bulan,$thn_ang)
                    WHERE (left(kd_rek6,4) in ($kode_1_seq360) 
                    or left(kd_rek6,6) in ($kode_2_seq360) 
                    or left(kd_rek6,8) in ($kode_3_seq360))
                    union all
                    SELECT SUM(realisasi*-1) as nilai FROM Data_realisasi_tanpa_anggaran($bulan,$thn_ang)
                    WHERE (left(kd_rek6,4) in ($kecuali_1_seq360) 
                    or left(kd_rek6,6) in ($kecuali_2_seq360) 
                    or left(kd_rek6,8) in ($kecuali_3_seq360)))a"))->first();
        $pendanaan_bersih = $ob360->nilai+$tambah_seq360;
        //
        //naik turun kas seq 405
        $naik_turun_kas = $operasi_bersih+$investasi_bersih+$pendanaan_bersih+$pfk_bersih;
        //
        //saldo awal kas seq 410
        $sqlseq410 = collect(DB::select("SELECT isnull(kode_1,'999') kode_1, isnull(kode_2,'999') kode_2 , isnull(kode_3,'999') kode_3, isnull(kecuali_1,'999') kecuali_1, isnull(kecuali_2,'999') kecuali_2, isnull(kecuali_3,'999') kecuali_3,tambah from map_lak_prov_permen77 where seq='410'"))->first();
        $kode_1_seq410 = $sqlseq410->kode_1;
        $kode_2_seq410 = $sqlseq410->kode_2;
        $kode_3_seq410 = $sqlseq410->kode_3;
        $kecuali_1_seq410 = $sqlseq410->kecuali_1;
        $kecuali_2_seq410 = $sqlseq410->kecuali_2;
        $kecuali_3_seq410 = $sqlseq410->kecuali_3;
        $tambah_seq410 = $sqlseq410->tambah;
        $ob410 = collect(DB::select("SELECT SUM(nilai) nilai
                    from(
                    SELECT SUM(realisasi) as nilai FROM Data_realisasi_tanpa_anggaran($bulan,$thn_ang)
                    WHERE (left(kd_rek6,4) in ($kode_1_seq410) 
                    or left(kd_rek6,6) in ($kode_2_seq410) 
                    or left(kd_rek6,8) in ($kode_3_seq410))
                    union all
                    SELECT SUM(realisasi*-1) as nilai FROM Data_realisasi_tanpa_anggaran($bulan,$thn_ang)
                    WHERE (left(kd_rek6,4) in ($kecuali_1_seq410) 
                    or left(kd_rek6,6) in ($kecuali_2_seq410) 
                    or left(kd_rek6,8) in ($kecuali_3_seq410)))a"))->first();
        $saldo_akhir_kas = $ob410->nilai+$tambah_seq410+$naik_turun_kas;
        //








        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
        // dd($sus);

        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'map'               => $map,
            'pfk_masuk'         => $pfk_masuk,
            'pfk_keluar'        => $pfk_keluar,
            'pfk_bersih'        => $pfk_bersih,
            'operasi_bersih'    => $operasi_bersih,
            'investasi_bersih'  => $investasi_bersih,
            'pendanaan_bersih'  => $pendanaan_bersih,
            'naik_turun_kas'    => $naik_turun_kas,
            'saldo_akhir_kas'   => $saldo_akhir_kas,
            'enter'             => $enter,
            'daerah'            => $daerah,
            'bulan'             => $bulan,
            'kd_skpd'           => $kd_skpd,
            'nm_bln'            => $nm_bln,
            'thn_ang'           => $thn_ang,
            'thn_ang1'          => $thn_ang1
        ];
        // dd($data['ekuitas_awal']->nilai);
        $view =  view('akuntansi.cetakan.lak')->with($data);


        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LPE.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LPE.xls"');
            return $view;
        }
    }

    public function cetaklpsal(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $cbulan          = $request->bulan;
        $enter          = $request->spasi;
        $cetak          = $request->cetak;
        // $kd_skpd        = Auth::user()->kd_skpd;

        // dd($kd_skpd);

        $thn_ang    = tahun_anggaran();
        $thn_ang1   = $thn_ang - 1;

        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);
        $nm_bln = $arraybulan[$cbulan];




        // $anggaran = collect(DB::select("SELECT TOP 1 jns_ang from trhrka where kd_skpd='$kd_skpd' and status=1 order by tgl_dpa DESC"))->first();


        $kas_lalu = collect(DB::select("SELECT isnull(thn_m1,0) as nilai FROM map_lpsal_permen_77 where seq='40'"))->first();
        $kas_lalu=$kas_lalu->nilai;

        $kas = collect(DB::select("SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a
                    inner join trdju_pkd b on a.no_voucher=b.no_voucher AND b.kd_unit=a.kd_skpd where kd_rek6 in ('110101010001') and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$cbulan'"))->first();
        $kas=$kas->thn_m1;

        $bulan  = 12;       
        
        $silpa=collect(DB::select(" SELECT ((pend+pemb)-(belanja+trans)) as silpa FROM
                    (SELECT 
                     SUM(CASE WHEN LEFT(a.kd_rek6,1)='4' THEN a.kredit-a.debet ELSE 0 END) as pend
                    ,SUM(CASE WHEN LEFT(a.kd_rek6,4)='6101' THEN a.kredit-a.debet ELSE 0 END) as pemb
                    ,SUM(CASE WHEN LEFT(a.kd_rek6,1)IN ('5'
                        -- ,'6'
                        ) THEN a.debet-a.kredit ELSE 0 END) as belanja
                    ,SUM(CASE WHEN LEFT(a.kd_rek6,4)='6202' THEN a.debet-a.kredit ELSE 0 END) as trans
                    FROM trdju a INNER JOIN trhju b ON a.kd_unit=b.kd_skpd AND a.no_voucher=b.no_voucher 
                    WHERE YEAR(b.tgl_voucher)=$thn_ang and month(b.tgl_voucher)<=$cbulan
                    ) a"))->first();
        $silpa=$silpa->silpa;
                 
        $salnil=collect(DB::select("SELECT sum(kredit-debet)*-1 nilai FROM trdju a INNER JOIN trhju b ON a.kd_unit=b.kd_skpd AND a.no_voucher=b.no_voucher 
                    WHERE left(a.kd_rek6,2)='61' and YEAR(b.tgl_voucher)=$thn_ang and month(b.tgl_voucher)<=$cbulan"))->first();//rama_oyoy
        $salnil=$salnil->nilai;
        
        $lain=collect(DB::select("SELECT sum(kredit-debet) nilai FROM trdju a INNER JOIN trhju b ON a.kd_unit=b.kd_skpd AND a.no_voucher=b.no_voucher WHERE left(a.kd_rek6,4)='6101' and YEAR(b.tgl_voucher)=$thn_ang and month(b.tgl_voucher)<=$cbulan"))->first();
        $lain=$lain->nilai;
        
        $lain   = $lain-$kas_lalu;
        // $silpa   = $silpa-$lain;

        $silpa  = $silpa;
        $sub01  = $kas_lalu+$salnil;
        $sub02  = $sub01+$silpa;
        
        $koreksi = 0;
        
        //$lain = 0;

        $sub03  = $sub02+$koreksi+$lain;

        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
        // dd($sus);

        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            // 'ekuitas_awal'      => $ekuitas_awal,
            'kas_lalu'      => $kas_lalu,
            'kas'      => $kas,
            'silpa'            => $silpa,
            'salnil'            => $salnil,
            'lain'           => $lain,
            'sub01'           => $sub01,
            'sub02'           => $sub02,
            'sub03'           => $sub03,
            'enter'             => $enter,
            'koreksi'            => $koreksi,
            'bulan'             => $cbulan,
            'nm_bln'            => $nm_bln,
            'thn_ang'           => $thn_ang,
            'thn_ang1'         => $thn_ang1
        ];
        // dd($data['ekuitas_awal']->nilai);
        $view =  view('akuntansi.cetakan.lpsal')->with($data);


        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LPSAL.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LPSAL.xls"');
            return $view;
        }
    }
}
