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

class LraperkadaController extends Controller
{

    public function cetak_lamp1(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $kd_skpd        = $request->kd_skpd;
        $bulan          = $request->bulan;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jenis_anggaran;
        $jenis          = $request->jenis;
        // $kd_skpd        = Auth::user()->kd_skpd;

        
        $tahun_anggaran = tahun_anggaran();
        $modtahun= $tahun_anggaran%4;
        
            if ($modtahun = 0){
                $nilaibulan=".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
            }
                    else {
                $nilaibulan=".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
            }
            $arraybulan=explode(".",$nilaibulan);
            $nm_bln = $arraybulan[$bulan];

        if($request->kd_skpd==''){
            $kd_skpd        = Auth::user()->kd_skpd;
            $skpd_clause="";
            $skpd_clauses= "";
            $skpd_clause_prog= "";
        }else{
            $kd_skpd        = $request->kd_skpd;
            $skpd_clause = "AND left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clauses= "WHERE left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clause_prog= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' and ";
        }


            // rincian
        if ($jenis=="1") {
            
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
                                                FROM trhju_pkd a
                                                JOIN trdju_pkd b ON a.no_voucher = b.no_voucher 
                                                            AND a.kd_skpd = b.kd_unit
                                                WHERE b.kd_rek1_cmp IN ('4', '5', '6') 
                                                $skpd_clause  AND MONTH(tgl_voucher) <= ? and  LEFT(b.kd_rek6, LEN(map_lra_2023.kd_rek)) = map_lra_2023.kd_rek
                                                GROUP BY a.tgl_voucher,b.no_voucher,b.kd_unit,b.kd_sub_kegiatan,b.kd_rek6)a
                                            ) ,0)realisasi 

                                            FROM map_lra_2023
                                            
                                            where group_id <='8'
                                            GROUP BY map_lra_2023.id,group_id, kd_rek, nama, padding, is_bold, is_show_kd_rek, is_right_align                                       
                                            ORDER BY map_lra_2023.id,group_id, nama", [$jns_ang,$bulan]);
            $sus=collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet(?,?,?) $skpd_clauses",[$bulan,$jns_ang,$tahun_anggaran]))->first();
        }else if($jenis=="2"){
        
        }else if($jenis=="3"){
            

        }else if($jenis=="4"){
            
        }else if($jenis=="5"){
            
        }
        
        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_pergub, ket_pergub_no, ket_pergub_tentang FROM config_nogub_akt"))->first();


 
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'rincian'           => $rincian,
            'sus'               => $sus,
            'daerah'            => $sc,
            'nogub'             => $nogub,
            'kd_skpd'           => $kd_skpd,
            'nm_bln'            => $nm_bln,
            'tahun_anggaran'    => $tahun_anggaran
        ];
        // if($format=='sap'){
        //     $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        // }elseif($format=='djpk'){
        //     $view =  view('akuntansi.cetakan.lra_djpk')->with($data);
        // }elseif($format=='p77'){
        //     $view =  view('akuntansi.cetakan.lra_77')->with($data);
        // }elseif($format=='sng'){
            $view =  view('akuntansi.cetakan.perkada.lamp1')->with($data);
        // }
        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('PERKADA LAMP I.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="PERKADA LAMP I.xls"');
            return $view;
        }
    }

    public function cetak_lamp2(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $kd_skpd        = $request->kd_skpd;
        $bulan          = $request->bulan;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jenis_anggaran;
        $jenis          = $request->jenis;
        $tgl_ttd          = $request->tgl_ttd;
        $skpdunit          = $request->skpdunit;
        // $kd_skpd        = Auth::user()->kd_skpd;
        // dd($skpdunit);
        
        $tahun_anggaran = tahun_anggaran();
        $modtahun= $tahun_anggaran%4;
        
            if ($modtahun = 0){
                $nilaibulan=".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
            }
                    else {
                $nilaibulan=".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
            }
            $arraybulan=explode(".",$nilaibulan);
            $nm_bln = $arraybulan[$bulan];

        if($request->kd_skpd==''){
            $kd_skpd        = Auth::user()->kd_skpd;
            $skpd_clause_a="";
            $skpd_clause_d="";
            $skpd_clauses= "where";
            $skpd_clause_prog= "";
        }else{
            if ($skpdunit=="unit") {
                $kd_skpd=$kd_skpd;
            }else if ($skpdunit=="skpd") {
                $kd_skpd=substr($kd_skpd,0,17);
            }
            $skpd_clause_a = "left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd' and ";
            $skpd_clause_d = "left(d.kd_skpd,len('$kd_skpd'))='$kd_skpd' and ";
            $skpd_clauses= "WHERE left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
            $skpd_clause_prog= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' and ";
        }
        // dd($kd_skpd);


            
            $rincian_pend = DB::select("SELECT kd_sub_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini,(sd_bulan_ini-anggaran)sisa FROM realisasi_jurnal_pend_n($bulan,'$jns_ang',$tahun_anggaran) where $skpd_clause_prog LEN(kd_rek)<='$jenis' AND kd_sub_kegiatan<>'' ORDER BY kd_sub_kegiatan,kd_rek");
            $tot_pend=collect(DB::select("SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini,sum(sd_bulan_ini-anggaran)sisa FROM realisasi_jurnal_pend_n($bulan,'$jns_ang',$tahun_anggaran) where $skpd_clause_prog LEN(kd_rek)='$jenis'"))->first();
            $belda = collect(DB::select("SELECT SUM(nilai_sp2d) as nilai_sp2d,SUM(nilai_cp) nilai_cp,SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini,sum(sd_bulan_ini-anggaran) sisa,sum(nilai_sp2d-sd_bulan_ini-nilai_cp)sisa_kas
                from
                (SELECT SUM(d.nilai) as nilai_sp2d,0 nilai_cp,0 anggaran ,0 sd_bulan_ini 
                    FROM trhsp2d a INNER JOIN trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm
                        INNER JOIN trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp
                        INNER JOIN trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp
                    WHERE $skpd_clause_a status_terima='1' AND MONTH(tgl_terima)<='$bulan' AND LEFT(kd_rek6,1)  in ('5','1') AND (c.sp2d_batal IS NULL  OR c.sp2d_batal !=1) 
                union all   
                SELECT 0 nilai_sp2d,SUM(nilai_cp) nilai_cp,0 anggaran ,0 sd_bulan_ini 
                    FROM (select sum(rupiah) as nilai_cp 
                        from trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd 
                        where $skpd_clause_d  MONTH(tgl_sts)<='$bulan' AND jns_cp in('1','2','3')  AND pot_khusus!='3' AND LEFT(kd_rek6,1)<>4
                        ) a

                union all
                SELECT 0 nilai_sp2d,0 nilai_cp,SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_objek_n($bulan,'$jns_ang',$tahun_anggaran) 
                where $skpd_clause_prog  LEN(kd_rek)='4'
                )a
                "))->first();
            $rincian_bel = DB::select("SELECT urut,kd_sub_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini ,(sd_bulan_ini-anggaran)sisa,
                    isnull((select nm_sumberdana from [sumber_dana_all-XXX] a where a.kd_sumberdana=sumber),'')as sumber,lokasi
                            FROM realisasi_jurnal_rinci_n($bulan,'$jns_ang',$tahun_anggaran) where $skpd_clause_prog LEN(kd_rek)<='$jenis' 
                            AND SUBSTRING(kd_sub_kegiatan,6,2)!='00' 
                            ORDER BY kd_sub_kegiatan,kd_rek");
            $rincian_pem = DB::select("SELECT urut,kd_sub_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini,(sd_bulan_ini-anggaran)sisa ,isnull((select nm_sumberdana from [sumber_dana_all-XXX] a where a.kd_sumberdana=sumber),'')as sumber,lokasi
                            FROM realisasi_jurnal_rinci_n($bulan,'$jns_ang',$tahun_anggaran) where $skpd_clause_prog LEN(kd_rek)<='$jenis' 
                            AND SUBSTRING(kd_sub_kegiatan,6,2)='00' 
                            ORDER BY kd_sub_kegiatan,kd_rek");
            $tot_pem = collect(DB::select("SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini,sum(sd_bulan_ini-anggaran)sisa FROM realisasi_jurnal_objek_biaya_n($bulan,'$jns_ang',$tahun_anggaran) where $skpd_clause_prog LEN(kd_rek)='4'"))->first();

        $hukum_4102="Peraturan Daerah Provinsi Kalimantan Barat Nomor 6 Tahun 2021 Tentang Retribusi Daerah";
        $hukum_4101="Peraturan Menteri Keuangan Nomor 102/PMK.07/2015 Tentang Perubahan Atas Peraturan Menteri Keuangan Nomor 115/PMK.07/2013 tentang Tata Cara Pemungutan Penyetoran Pajak Rokok
                    <br>
                    <br>
                    Peraturan Daerah Nomor 8 Tahun 2010 Tentang Pajak Daerah sebagaimana telah diubah dengan Peraturan Daerah Nomor 2 Tahun 2012 tentang Perubahan Atas Peraturan Daerah Nomor 8 Tahun 2010 tentang Pajak Daerah
                    <br>
                    <br>
                    Peraturan Daerah Nomor 22 Tahun 2013 Tentang Petunjuk Pelaksanaan Pemungutan Pajak Kendaraan Bermotor dan Bea Balik Nama Kendaraan Bermotor sebagaimana telah diubah dengan Peraturan Gubernur Nomor 12 Tahun 2019 tentang Petunjuk Pelaksanaan Pemungutan Pajak Kendaraan Bermotor dan Bea Balik Nama Kendaraan Bermotor
                    <br>
                    <br>
                    Peraturan Gubernur Nomor 55 Tahun 2012 Tentang Penghitungan Dasar Pengenaan Pajak, Pengambilan dan Pemanfaatan Air Permukaan di Provinsi Kalimantan Barat
                    <br>
                    <br>
                    Peraturan Gubernur Nomor 72 Tahun 2020 Tentang Perhitungan Dasar Pengenaan Pajak Kendaraan Bermotor dan Bea Balik Nama Kendaraan Bermotor Pembuatan Sebelum Tahun 2020";
        $hukum_kos="";
        $hukum_1="Peraturan Daerah Nomor 11 Tahun 2021 Tentang Anggaran Pendapatan dan Belanja Daerah Tahun Anggaran 2022 
                <br><br>
                 Peraturan Daerah Nomor 4 Tahun 2022 Tentang Perubahan Anggaran Pendapatan dan Belanja Daerah Tahun Anggaran 2022";


        
        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_pergub, ket_pergub_no, ket_pergub_tentang FROM config_nogub_akt"))->first();

        // dd($rincian_pend);
 
        


        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'rincian_pend'      => $rincian_pend,
            'rincian_bel'       => $rincian_bel,
            'rincian_pem'       => $rincian_pem,
            'hukum_4101'        => $hukum_4101,
            'hukum_4102'        => $hukum_4102,
            'hukum_kos'         => $hukum_kos,
            'hukum_1'           => $hukum_1,
            'tot_pend'          => $tot_pend,
            'tot_pem'           => $tot_pem,
            'belda'             => $belda,
            'daerah'            => $sc,
            'nogub'             => $nogub,
            'kd_skpd'           => $kd_skpd,
            'nm_bln'            => $nm_bln,
            'tgl_ttd'            => $tgl_ttd,
            'tahun_anggaran'    => $tahun_anggaran
        ];
        // if($format=='sap'){
        //     $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        // }elseif($format=='djpk'){
        //     $view =  view('akuntansi.cetakan.lra_djpk')->with($data);
        // }elseif($format=='p77'){
        //     $view =  view('akuntansi.cetakan.lra_77')->with($data);
        // }elseif($format=='sng'){
            $view =  view('akuntansi.cetakan.perkada.lamp2')->with($data);
        // }
        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('PERKADA LAMP I.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="PERKADA LAMP I.xls"');
            return $view;
        }
    }
    
}
