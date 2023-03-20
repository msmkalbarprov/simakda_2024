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

class LraperdaController extends Controller
{

    public function cetak_i4_urusan(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tanggal_ttd    = $request->tgl_ttd;
        $bulan          = $request->bulan;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jenis_anggaran;
        $jenis          = $request->jenis;
        // $kd_skpd        = Auth::user()->kd_skpd;

        
        $tahun_anggaran = tahun_anggaran();



            // rincian
        if ($jenis=="1") {
            
            $rincian = DB::select("SELECT kd_skpd,kd_sub_kegiatan kode ,nm_rek,ang_peg,ang_brng,ang_mod,ang_hibah,ang_bansos,ang_bghasil,ang_bankeu,ang_btt,real_peg,real_brng,real_mod,real_hibah,real_bansos,real_bghasil,real_bankeu,real_btt 
            FROM [perda_lampI.3_rinci2]($bulan,'$jns_ang',$tahun_anggaran)
                    where len(kd_skpd)='22'
                    ORDER BY kd_skpd,kd_sub_kegiatan"
                );
            $tot = collect(DB::select("SELECT sum(ang_peg)ang_peg,sum(ang_brng)ang_brng,sum(ang_mod)ang_mod,sum(ang_hibah)ang_hibah,sum(ang_bansos)ang_bansos,sum(ang_bghasil)ang_bghasil,sum(ang_bankeu)ang_bankeu,sum(ang_btt)ang_btt,sum(ang_peg+ang_brng+ang_mod+ang_hibah+ang_bansos+ang_bghasil+ang_bankeu+ang_btt)jum_ang,sum(real_peg)real_peg,sum(real_brng)real_brng,sum(real_mod)real_mod,sum(real_hibah)real_hibah,sum(real_bansos)real_bansos,sum(real_bghasil)real_bghasil,sum(real_bankeu)real_bankeu,sum(real_btt)real_btt,sum(real_peg+real_brng+real_mod+real_hibah+real_bansos+real_bghasil+real_bankeu+real_btt)real_jum
                    FROM [perda_lampI.3_rinci2]($bulan,'$jns_ang',$tahun_anggaran)
                    where len(kd_skpd)='1'"))->first();
        }else if($jenis=="2"){
            $rincian = DB::select("SELECT kd_skpd kode,nm_skpd nm_rek,ang_peg,ang_brng,ang_modal ang_mod,ang_hibah,ang_bansos,ang_bghasil,
            ang_bankeu,ang_btt,
          real_peg,real_brng,real_modal real_mod,real_hibah,real_bansos,real_bghasil,real_bankeu,real_btt 
          FROM [perda_lampI.3_skpd_spj]($bulan,'$jns_ang',$tahun_anggaran)
          where len(kd_skpd)='22'
          ORDER BY kd_skpd"
                );
            $tot = collect(DB::select("SELECT sum(ang_peg)ang_peg,sum(ang_brng)ang_brng,sum(ang_modal)ang_mod,sum(ang_hibah)ang_hibah,sum(ang_bansos)ang_bansos,sum(ang_bghasil)ang_bghasil,sum(ang_bankeu)ang_bankeu,sum(ang_btt)ang_btt,sum(ang_peg+ang_brng+ang_modal+ang_hibah+ang_bansos+ang_bghasil+ang_bankeu+ang_btt)jum_ang,sum(real_peg)real_peg,sum(real_brng)real_brng,sum(real_modal)real_mod,sum(real_hibah)real_hibah,sum(real_bansos)real_bansos,sum(real_bghasil)real_bghasil,sum(real_bankeu)real_bankeu,sum(real_btt)real_btt,sum(real_peg+real_brng+real_modal+real_hibah+real_bansos+real_bghasil+real_bankeu+real_btt)real_jum
                  FROM [perda_lampI.3_skpd_spj]($bulan,'$jns_ang',$tahun_anggaran)
                  where len(kd_skpd)='22'"))->first();
        }else if($jenis=="3"){
            $rincian = DB::select("SELECT kd_skpd,kd_sub_kegiatan kode ,nm_rek,ang_peg,ang_brng,ang_mod,ang_hibah,ang_bansos,ang_bghasil,
                        ang_bankeu,ang_btt,
                    real_peg,real_brng,real_mod,real_hibah,real_bansos,real_bghasil,real_bankeu,real_btt 
                    FROM [perda_lampI.3_rinci2]($bulan,'$jns_ang',$tahun_anggaran)
                    where len(kd_skpd)<='22'
                    ORDER BY kd_skpd,kd_sub_kegiatan"
                );
           $tot = collect(DB::select("SELECT sum(ang_peg)ang_peg,sum(ang_brng)ang_brng,sum(ang_mod)ang_mod,sum(ang_hibah)ang_hibah,sum(ang_bansos)ang_bansos,sum(ang_bghasil)ang_bghasil,sum(ang_bankeu)ang_bankeu,sum(ang_btt)ang_btt,sum(ang_peg+ang_brng+ang_mod+ang_hibah+ang_bansos+ang_bghasil+ang_bankeu+ang_btt)jum_ang,sum(real_peg)real_peg,sum(real_brng)real_brng,sum(real_mod)real_mod,sum(real_hibah)real_hibah,sum(real_bansos)real_bansos,sum(real_bghasil)real_bghasil,sum(real_bankeu)real_bankeu,sum(real_btt)real_btt,sum(real_peg+real_brng+real_mod+real_hibah+real_bansos+real_bghasil+real_bankeu+real_btt)real_jum
                    FROM [perda_lampI.3_rinci2]($bulan,'$jns_ang',$tahun_anggaran)
                    where len(kd_skpd)='1'"))->first();

        }else if($jenis=="4"){
            $rincian = DB::select("SELECT kd_skpd,kd_sub_kegiatan kode ,nm_rek,ang_peg,ang_brng,ang_mod,ang_hibah,ang_bansos,ang_bghasil,
                        ang_bankeu,ang_btt,
                    real_peg,real_brng,real_mod,real_hibah,real_bansos,real_bghasil,real_bankeu,real_btt 
                    FROM [perda_lampI.3_rinci2]($bulan,'$jns_ang',$tahun_anggaran) ORDER BY kd_skpd,kd_sub_kegiatan"
                );
            $tot = collect(DB::select("SELECT sum(ang_peg)ang_peg,sum(ang_brng)ang_brng,sum(ang_mod)ang_mod,sum(ang_hibah)ang_hibah,sum(ang_bansos)ang_bansos,sum(ang_bghasil)ang_bghasil,sum(ang_bankeu)ang_bankeu,sum(ang_btt)ang_btt,sum(ang_peg+ang_brng+ang_mod+ang_hibah+ang_bansos+ang_bghasil+ang_bankeu+ang_btt)jum_ang,sum(real_peg)real_peg,sum(real_brng)real_brng,sum(real_mod)real_mod,sum(real_hibah)real_hibah,sum(real_bansos)real_bansos,sum(real_bghasil)real_bghasil,sum(real_bankeu)real_bankeu,sum(real_btt)real_btt,sum(real_peg+real_brng+real_mod+real_hibah+real_bansos+real_bghasil+real_bankeu+real_btt)real_jum
                    FROM [perda_lampI.3_rinci2]($bulan,'$jns_ang',$tahun_anggaran)
                    where len(kd_skpd)='1'"))->first();
        }else if($jenis=="5"){
            $rincian = DB::select("SELECT kd_skpd,kd_sub_kegiatan kode ,nm_rek,ang_peg,ang_brng,ang_mod,ang_hibah,ang_bansos,ang_bghasil,
                        ang_bankeu,ang_btt,
                    real_peg,real_brng,real_mod,real_hibah,real_bansos,real_bghasil,real_bankeu,real_btt 
                    FROM [perda_lampI.3_rinci2]($bulan,'$jns_ang',$tahun_anggaran)
                    where len(kd_skpd)='17'
                    ORDER BY kd_skpd,kd_sub_kegiatan"
                );
            $tot = collect(DB::select("SELECT sum(ang_peg)ang_peg,sum(ang_brng)ang_brng,sum(ang_mod)ang_mod,sum(ang_hibah)ang_hibah,sum(ang_bansos)ang_bansos,sum(ang_bghasil)ang_bghasil,sum(ang_bankeu)ang_bankeu,sum(ang_btt)ang_btt,sum(ang_peg+ang_brng+ang_mod+ang_hibah+ang_bansos+ang_bghasil+ang_bankeu+ang_btt)jum_ang,sum(real_peg)real_peg,sum(real_brng)real_brng,sum(real_mod)real_mod,sum(real_hibah)real_hibah,sum(real_bansos)real_bansos,sum(real_bghasil)real_bghasil,sum(real_bankeu)real_bankeu,sum(real_btt)real_btt,sum(real_peg+real_brng+real_mod+real_hibah+real_bansos+real_bghasil+real_bankeu+real_btt)real_jum
                    FROM [perda_lampI.3_rinci2]($bulan,'$jns_ang',$tahun_anggaran)
                    where len(kd_skpd)='1'"))->first();
        }
        
        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();


 
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'rincian'           => $rincian,
            'tot'               => $tot,
            'daerah'            => $sc,
            'nogub'             => $nogub,
            'tanggal_ttd'       => $tanggal_ttd,
            'judul'             => $bulan,
            'tahun_anggaran'    => $tahun_anggaran
        ];
        // if($format=='sap'){
        //     $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        // }elseif($format=='djpk'){
        //     $view =  view('akuntansi.cetakan.lra_djpk')->with($data);
        // }elseif($format=='p77'){
        //     $view =  view('akuntansi.cetakan.lra_77')->with($data);
        // }elseif($format=='sng'){
            $view =  view('akuntansi.cetakan.perda.i_4_urusan.perda_i4_urusan')->with($data);
        // }
        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('PERDA LAMP I4 URUSAN.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="PERDA LAMP I4 URUSAN.xls"');
            return $view;
        }
    }

    public function cetak_i1(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tanggal_ttd    = $request->tgl_ttd;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jenis_anggaran;
        // $kd_skpd        = Auth::user()->kd_skpd;

        
        $tahun_anggaran = tahun_anggaran();



            // rincian
       
            $pendapatan = collect(DB::select("SELECT SUM(a.nilai) AS nilai_ag, SUM(r.kredit - r.debet) AS nilai_real 
                FROM trdrka a LEFT JOIN jurnal_rekap r ON a.kd_skpd = r.kd_skpd AND a.kd_sub_kegiatan = r.kd_sub_kegiatan AND a.kd_rek6 = r.kd_rek6 
                WHERE LEFT(a.kd_rek6, 1) = '4' and jns_ang='$jns_ang'"
                ))->first();
            $belanja = collect(DB::select("SELECT SUM(a.nilai) AS nilai_ag, SUM(r.kredit - r.debet) AS nilai_real 
                FROM trdrka a LEFT JOIN jurnal_rekap r ON a.kd_skpd = r.kd_skpd AND a.kd_sub_kegiatan = r.kd_sub_kegiatan AND a.kd_rek6 = r.kd_rek6 
                WHERE LEFT(a.kd_rek6, 1) = '5' and jns_ang='$jns_ang'"
                ))->first();
            $daftar_lra = DB::select("SELECT SUM(a.nilai) AS nilai_ag, SUM(r.debet - r.kredit) AS nilai_real
                  FROM trdrka a
                  LEFT JOIN jurnal_rekap r ON a.kd_skpd = r.kd_skpd AND a.kd_sub_kegiatan = r.kd_sub_kegiatan AND a.kd_rek6 = r.kd_rek6
                  WHERE LEFT(a.kd_rek6, 1) = '5'");
                $daftar_lra = DB::select(
                  "SELECT * FROM (
                    SELECT
                      CONCAT(kd_rek1, kd_urusan) AS ikey, kd_urusan AS kode, nm_urusan AS nama,
                      0 AS nilai_ag, 0 AS nilai_real, 0 is_bold, 'urusan' AS jenis
                    FROM ms_urusan mu
                    JOIN trdrka a ON mu.kd_urusan = LEFT(a.kd_sub_kegiatan, 1)
                    JOIN ms_rek1 mrek1 ON mrek1.kd_rek1 = LEFT(a.kd_rek6, 1)
                    WHERE LEFT(a.kd_rek6, 1) IN ('4', '5')
                    GROUP BY mrek1.kd_rek1, mu.kd_urusan, mu.nm_urusan

                    UNION ALL

                    SELECT
                      CONCAT(LEFT(a.kd_rek6, 1), kd_bidang_urusan) AS ikey, kd_bidang_urusan AS kode, nm_bidang_urusan AS nama,
                      SUM(a.nilai), SUM(CASE LEFT(r.kd_rek6, 1) WHEN '4' THEN r.kredit - r.debet WHEN '5' THEN r.debet - r.kredit END),
                      1 is_bold, 'bidang_urusan' AS jenis
                    FROM ms_urusan mu
                    JOIN ms_bidang_urusan mbu ON mu.kd_urusan = mbu.kd_urusan
                    JOIN trdrka a ON mbu.kd_bidang_urusan = LEFT(a.kd_sub_kegiatan, 4)
                    LEFT JOIN jurnal_rekap r ON a.kd_skpd = r.kd_skpd AND a.kd_sub_kegiatan = r.kd_sub_kegiatan AND a.kd_rek6 = r.kd_rek6
                    WHERE LEFT(a.kd_rek6, 1) IN ('4', '5')
                    GROUP BY kd_bidang_urusan, nm_bidang_urusan, LEFT(a.kd_rek6, 1)

                    UNION ALL

                    SELECT
                      CONCAT(LEFT(a.kd_rek6, 1), kd_bidang_urusan, a.kd_skpd) AS ikey, a.kd_skpd AS kode, a.nm_skpd AS nama,
                      SUM(a.nilai), SUM(CASE LEFT(r.kd_rek6, 1) WHEN '4' THEN r.kredit - r.debet WHEN '5' THEN r.debet - r.kredit END),
                      0 is_bold, 'skpd' AS jenis
                    FROM ms_urusan mu
                    JOIN ms_bidang_urusan mbu ON mu.kd_urusan = mbu.kd_urusan
                    JOIN trdrka a ON mbu.kd_bidang_urusan = LEFT(a.kd_sub_kegiatan, 4)
                    LEFT JOIN jurnal_rekap r ON a.kd_skpd = r.kd_skpd AND a.kd_sub_kegiatan = r.kd_sub_kegiatan AND a.kd_rek6 = r.kd_rek6
                    WHERE LEFT(a.kd_rek6, 1) IN ('4', '5')
                    GROUP BY kd_bidang_urusan, nm_bidang_urusan, LEFT(a.kd_rek6, 1), a.kd_skpd, a.nm_skpd

                    UNION ALL

                    SELECT
                      CONCAT(LEFT(a.kd_rek6, 1), kd_bidang_urusan, a.kd_skpd, mrek2.kd_rek2) AS ikey,
                      mrek2.kd_rek2 AS kode, mrek2.nm_rek2 AS nama,
                      SUM(a.nilai), SUM(CASE LEFT(r.kd_rek6, 1) WHEN '4' THEN r.kredit - r.debet WHEN '5' THEN r.debet - r.kredit END),
                      1 is_bold, 'rek2' AS jenis
                    FROM ms_urusan mu
                    JOIN ms_bidang_urusan mbu ON mu.kd_urusan = mbu.kd_urusan
                    JOIN trdrka a ON mbu.kd_bidang_urusan = LEFT(a.kd_sub_kegiatan, 4)
                    LEFT JOIN jurnal_rekap r ON a.kd_skpd = r.kd_skpd AND a.kd_sub_kegiatan = r.kd_sub_kegiatan AND a.kd_rek6 = r.kd_rek6
                    JOIN ms_rek2 mrek2 ON LEFT(a.kd_rek6, 2) = mrek2.kd_rek2
                    WHERE LEFT(a.kd_rek6, 1) = '5'
                    GROUP BY kd_bidang_urusan, nm_bidang_urusan, LEFT(a.kd_rek6, 1), a.kd_skpd, a.nm_skpd, mrek2.kd_rek2, mrek2.nm_rek2

                    UNION ALL

                    SELECT
                      CONCAT(LEFT(a.kd_rek6, 1), kd_bidang_urusan, a.kd_skpd, mrek3.kd_rek3) AS ikey,
                      mrek3.kd_rek3 AS kode, mrek3.nm_rek3 AS nama,
                      SUM(a.nilai), SUM(CASE LEFT(r.kd_rek6, 1) WHEN '4' THEN r.kredit - r.debet WHEN '5' THEN r.debet - r.kredit END),
                      0 is_bold, 'rek3' AS jenis
                    FROM ms_urusan mu
                    JOIN ms_bidang_urusan mbu ON mu.kd_urusan = mbu.kd_urusan
                    JOIN trdrka a ON mbu.kd_bidang_urusan = LEFT(a.kd_sub_kegiatan, 4)
                    LEFT JOIN jurnal_rekap r ON a.kd_skpd = r.kd_skpd AND a.kd_sub_kegiatan = r.kd_sub_kegiatan AND a.kd_rek6 = r.kd_rek6
                    JOIN ms_rek3 mrek3 ON LEFT(a.kd_rek6, 4) = mrek3.kd_rek3
                    WHERE LEFT(a.kd_rek6, 2) = '51'
                    GROUP BY kd_bidang_urusan, nm_bidang_urusan, LEFT(a.kd_rek6, 1), a.kd_skpd, a.nm_skpd, mrek3.kd_rek3, mrek3.nm_rek3
                  ) lra
                  ORDER BY ikey"
                );

        
        
        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();


 
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pendapatan'        => $pendapatan,
            'belanja'           => $belanja,
            'daftar_lra'        => $daftar_lra,
            'daerah'            => $sc,
            'nogub'             => $nogub,
            'tanggal_ttd'       => $tanggal_ttd,
            'tahun_anggaran'    => $tahun_anggaran
        ];
        // if($format=='sap'){
        //     $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        // }elseif($format=='djpk'){
        //     $view =  view('akuntansi.cetakan.lra_djpk')->with($data);
        // }elseif($format=='p77'){
        //     $view =  view('akuntansi.cetakan.lra_77')->with($data);
        // }elseif($format=='sng'){
            $view =  view('akuntansi.cetakan.perda.perda_i1')->with($data);
        // }
        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('PERDA LAMP I1 URUSAN.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="PERDA LAMP I1.xls"');
            return $view;
        }
    }

    public function cetak_i1_ringkasan(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tanggal_ttd    = $request->tgl_ttd;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jenis_anggaran;
        $bulan          = $request->bulan;
        // $kd_skpd        = Auth::user()->kd_skpd;

        
        $tahun_anggaran = tahun_anggaran();



            // rincian
       
            $tot = collect(DB::select("SELECT * from data_tot_perda_i1_ringkasan_oyoy(3,'P1',2023)"))->first();

            $rincian = DB::select("SELECT * from data_perda_i1_ringkasan_oyoy($bulan,'$jns_ang',$tahun_anggaran) order by kode");

        
        
        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();


 
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'tot'               => $tot,
            'rincian'           => $rincian,
            'daerah'            => $sc,
            'nogub'             => $nogub,
            'tanggal_ttd'       => $tanggal_ttd,
            'tahun_anggaran'    => $tahun_anggaran
        ];
        // if($format=='sap'){
        //     $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        // }elseif($format=='djpk'){
        //     $view =  view('akuntansi.cetakan.lra_djpk')->with($data);
        // }elseif($format=='p77'){
        //     $view =  view('akuntansi.cetakan.lra_77')->with($data);
        // }elseif($format=='sng'){
            $view =  view('akuntansi.cetakan.perda.perda_i1_ringkasan')->with($data);
        // }
        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('PERDA LAMP I1 RINGKASAN.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="PERDA I1 RINGKASAN.xls"');
            return $view;
        }
    }
    
}
