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


    // Cetak List
    public function cetakLraSemester(Request $request)
    {
        
        $tanggal_ttd    = $request->tgl_ttd;
        $ttd            = $request->ttd;
        $bulan          = $request->bulan;
        $enter          = $request->spasi;
        $cetak          = $request->cetak;
        $tanggal1       = $request->tanggal1;
        $tanggal2       = $request->tanggal2;
        $akumulasi      = $request->pilihakumulsai;
        $jns_ang        = $request->jenis_anggaran;
        $periodebulan   = $request->periodebulan;
        $jenis_data     = $request->jenis_data;
        if($request->kd_skpd==''){
            $kd_skpd        = Auth::user()->kd_skpd;
            $skpd_clause="";
        }else{
            $kd_skpd        = $request->kd_skpd;
            $skpd_clause = "AND kd_skpd='$kd_skpd'";
        }
        
        $tahun_anggaran = tahun_anggaran();

        // TANDA TANGAN
        $tandatangan = DB::table('ms_ttd')
            ->select('nama', 'nip', 'jabatan', 'pangkat')
            ->where('nip', $ttd)
            ->whereIn('kode', ['1'])
            ->first();
        
            if($akumulasi=='akum'){
                    $isi    = "sd_bulan_ini";
                    $pilih  = "S/D";
                    $judul  = BULAN($bulan);
                }else{
                    $isi = "bulan_ini";
                    $pilih = "BULAN";
                    $judul  = BULAN($bulan);
                }

        
                
        // rincian
        if($periodebulan=='periode'){
            $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align, 
        isnull(SUM(trdrka.nilai),0) AS anggaran, isnull(SUM(jurnal.realisasi),0) AS realisasi
                                    FROM map_lra_2023
                                    LEFT JOIN (
                                        SELECT * FROM trdrka where jns_ang= ?
                                    ) trdrka ON LEFT(trdrka.kd_rek6, LEN(kd_rek)) = map_lra_2023.kd_rek
                                    LEFT JOIN
                                    (
                                        SELECT
                                        trdju_pkd.kd_unit,
                                        trdju_pkd.kd_sub_kegiatan,
                                        trdju_pkd.map_real,
                                        CASE
                                            WHEN LEFT(trdju_pkd.map_real, 1) = '4' THEN SUM(kredit) - SUM(debet)
                                            WHEN LEFT(trdju_pkd.map_real, 1) = '5' THEN SUM(debet) - SUM(kredit)
                                            WHEN LEFT(trdju_pkd.map_real, 2) = '61' THEN SUM(kredit) - SUM(debet)
                                            WHEN LEFT(trdju_pkd.map_real, 2) = '62' THEN SUM(debet) - SUM(kredit)
                                            ELSE 0
                                        END AS realisasi
                                        FROM trhju_pkd
                                        JOIN trdju_pkd ON trhju_pkd.no_voucher = trdju_pkd.no_voucher 
                                                    AND trhju_pkd.kd_skpd = trdju_pkd.kd_unit
                                        WHERE trdju_pkd.kd_rek1_cmp IN ('4', '5', '6') 
        								$skpd_clause AND (tgl_voucher between ? and ? )
                                        GROUP BY trdju_pkd.kd_unit, trdju_pkd.kd_sub_kegiatan, trdju_pkd.map_real
                                    ) jurnal
                                    ON trdrka.kd_skpd = jurnal.kd_unit AND trdrka.kd_sub_kegiatan = jurnal.kd_sub_kegiatan 
                                    AND trdrka.kd_rek6 = jurnal.map_real
                                    where group_id <= ?
                                    GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                    ORDER BY id,group_id, nama", [$jns_ang,$tanggal1,$tanggal2,$jenis_data]);
        }else{
            $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align, 
        isnull(SUM(trdrka.nilai),0) AS anggaran, isnull(SUM(jurnal.realisasi),0) AS realisasi
                                    FROM map_lra_2023
                                    LEFT JOIN (
                                        SELECT * FROM trdrka where jns_ang=?
                                    ) trdrka ON LEFT(trdrka.kd_rek6, LEN(kd_rek)) = map_lra_2023.kd_rek
                                    LEFT JOIN
                                    (
                                        SELECT
                                        trdju_pkd.kd_unit,
                                        trdju_pkd.kd_sub_kegiatan,
                                        trdju_pkd.map_real,
                                        CASE
                                            WHEN LEFT(trdju_pkd.map_real, 1) = '4' THEN SUM(kredit) - SUM(debet)
                                            WHEN LEFT(trdju_pkd.map_real, 1) = '5' THEN SUM(debet) - SUM(kredit)
                                            WHEN LEFT(trdju_pkd.map_real, 2) = '61' THEN SUM(kredit) - SUM(debet)
                                            WHEN LEFT(trdju_pkd.map_real, 2) = '62' THEN SUM(debet) - SUM(kredit)
                                            ELSE 0
                                        END AS realisasi
                                        FROM trhju_pkd
                                        JOIN trdju_pkd ON trhju_pkd.no_voucher = trdju_pkd.no_voucher 
                                                    AND trhju_pkd.kd_skpd = trdju_pkd.kd_unit
                                        WHERE trdju_pkd.kd_rek1_cmp IN ('4', '5', '6') 
        								$skpd_clause AND MONTH(tgl_voucher) = ?
                                        GROUP BY trdju_pkd.kd_unit, trdju_pkd.kd_sub_kegiatan, trdju_pkd.map_real
                                    ) jurnal
                                    ON trdrka.kd_skpd = jurnal.kd_unit AND trdrka.kd_sub_kegiatan = jurnal.kd_sub_kegiatan 
                                    AND trdrka.kd_rek6 = jurnal.map_real
                                    where group_id <= ?
                                    GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                    ORDER BY id,group_id, nama", [$jns_ang,$bulan,$jenis_data]);
        }
        


        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'rincian'           => $rincian,
            'enter'             => $enter,
            'daerah'            => $daerah,
            'tanggal_ttd'       => $tanggal_ttd,
            'tandatangan'       => $tandatangan,
            'judul'             => $bulan,
            'pilih'             => $pilih,
            'jenis'             => $jenis_data
        ];

        $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('LRA SEMESTER.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="LRA SEMESTER.xls"');
            return $view;
        }
    }


    // P77
    public function cetakLra77(Request $request)
    {
        
        $tanggal_ttd    = $request->tgl_ttd;
        $ttd            = $request->ttd;
        $bulan          = $request->bulan;
        $enter          = $request->spasi;
        $cetak          = $request->cetak;
        $tanggal1       = $request->tanggal1;
        $tanggal2       = $request->tanggal2;
        $akumulasi      = $request->pilihakumulsai;
        $jns_ang        = $request->jenis_anggaran;
        $periodebulan   = $request->periodebulan;
        $jenis_data     = $request->jenis_data;
        $jns_rincian    = $request->jns_rincian;
        if($request->kd_skpd==''){
            $kd_skpd        = Auth::user()->kd_skpd;
            $skpd_clause="";
        }else{
            $kd_skpd        = $request->kd_skpd;
            $skpd_clause = "AND kd_skpd='$kd_skpd'";
        }
        
        $tahun_anggaran = tahun_anggaran();

        // TANDA TANGAN
        $tandatangan = DB::table('ms_ttd')
            ->select('nama', 'nip', 'jabatan', 'pangkat')
            ->where('nip', $ttd)
            ->whereIn('kode', ['1'])
            ->first();
        
            if($akumulasi=='akum'){
                    $isi    = "sd_bulan_ini";
                    $pilih  = "S/D";
                    $judul  = BULAN($bulan);
                    $operator="<=";
                }else{
                    $isi = "bulan_ini";
                    $pilih = "BULAN";
                    $judul  = BULAN($bulan);
                    $operator="=";
                }

        
                
        // rincian
        if($jenis_data==4){ //SPJ
            if($periodebulan=='periode'){
                $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align, 
            isnull(SUM(trdrka.nilai),0) AS anggaran, isnull(SUM(jurnal.realisasi),0) AS realisasi
                                        FROM map_lra_2023
                                        LEFT JOIN (
                                            SELECT * FROM trdrka where jns_ang= ?
                                        ) trdrka ON LEFT(trdrka.kd_rek6, LEN(kd_rek)) = map_lra_2023.kd_rek
                                        LEFT JOIN
                                        (
                                            SELECT
                                            trdju_pkd.kd_unit,
                                            trdju_pkd.kd_sub_kegiatan,
                                            trdju_pkd.map_real,
                                            CASE
                                                WHEN LEFT(trdju_pkd.map_real, 1) = '4' THEN SUM(kredit) - SUM(debet)
                                                WHEN LEFT(trdju_pkd.map_real, 1) = '5' THEN SUM(debet) - SUM(kredit)
                                                WHEN LEFT(trdju_pkd.map_real, 2) = '61' THEN SUM(kredit) - SUM(debet)
                                                WHEN LEFT(trdju_pkd.map_real, 2) = '62' THEN SUM(debet) - SUM(kredit)
                                                ELSE 0
                                            END AS realisasi
                                            FROM trhju_pkd
                                            JOIN trdju_pkd ON trhju_pkd.no_voucher = trdju_pkd.no_voucher 
                                                        AND trhju_pkd.kd_skpd = trdju_pkd.kd_unit
                                            WHERE trdju_pkd.kd_rek1_cmp IN ('4', '5', '6') 
                                            $skpd_clause AND (tgl_voucher between ? and ? )
                                            GROUP BY trdju_pkd.kd_unit, trdju_pkd.kd_sub_kegiatan, trdju_pkd.map_real
                                        ) jurnal
                                        ON trdrka.kd_skpd = jurnal.kd_unit AND trdrka.kd_sub_kegiatan = jurnal.kd_sub_kegiatan 
                                        AND trdrka.kd_rek6 = jurnal.map_real
                                        where group_id <= ?
                                        GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                        ORDER BY id,group_id, nama", [$jns_ang,$tanggal1,$tanggal2,$jns_rincian]);
    
                
            }else{
                $rincian = DB::select("SELECT map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align, 
            isnull(SUM(trdrka.nilai),0) AS anggaran, isnull(SUM(jurnal.realisasi),0) AS realisasi
                                        FROM map_lra_2023
                                        LEFT JOIN (
                                            SELECT * FROM trdrka where jns_ang=?
                                        ) trdrka ON LEFT(trdrka.kd_rek6, LEN(kd_rek)) = map_lra_2023.kd_rek
                                        LEFT JOIN
                                        (
                                            SELECT
                                            trdju_pkd.kd_unit,
                                            trdju_pkd.kd_sub_kegiatan,
                                            trdju_pkd.map_real,
                                            CASE
                                                WHEN LEFT(trdju_pkd.map_real, 1) = '4' THEN SUM(kredit) - SUM(debet)
                                                WHEN LEFT(trdju_pkd.map_real, 1) = '5' THEN SUM(debet) - SUM(kredit)
                                                WHEN LEFT(trdju_pkd.map_real, 2) = '61' THEN SUM(kredit) - SUM(debet)
                                                WHEN LEFT(trdju_pkd.map_real, 2) = '62' THEN SUM(debet) - SUM(kredit)
                                                ELSE 0
                                            END AS realisasi
                                            FROM trhju_pkd
                                            JOIN trdju_pkd ON trhju_pkd.no_voucher = trdju_pkd.no_voucher 
                                                        AND trhju_pkd.kd_skpd = trdju_pkd.kd_unit
                                            WHERE trdju_pkd.kd_rek1_cmp IN ('4', '5', '6') 
                                            $skpd_clause AND MONTH(tgl_voucher) $operator ?
                                            GROUP BY trdju_pkd.kd_unit, trdju_pkd.kd_sub_kegiatan, trdju_pkd.map_real
                                        ) jurnal
                                        ON trdrka.kd_skpd = jurnal.kd_unit AND trdrka.kd_sub_kegiatan = jurnal.kd_sub_kegiatan 
                                        AND trdrka.kd_rek6 = jurnal.map_real
                                        where group_id <= ?
                                        GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align
                                        ORDER BY id,group_id, nama", [$jns_ang,$bulan,$jns_rincian]);
            }
        }
        
        


        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'rincian'           => $rincian,
            'enter'             => $enter,
            'daerah'            => $daerah,
            'tanggal_ttd'       => $tanggal_ttd,
            'tandatangan'       => $tandatangan,
            'judul'             => $bulan,
            'pilih'             => $pilih,
            'jenis'             => $jns_rincian
        ];

        $view =  view('akuntansi.cetakan.lra_77')->with($data);
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
}
