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
            // $tot = collect(DB::select("SELECT sum(ang_peg)ang_peg,sum(ang_brng)ang_brng,sum(ang_modal)ang_mod,sum(ang_hibah)ang_hibah,sum(ang_bansos)ang_bansos,sum(ang_bghasil)ang_bghasil,sum(ang_bankeu)ang_bankeu,sum(ang_btt)ang_btt,sum(ang_peg+ang_brng+ang_modal+ang_hibah+ang_bansos+ang_bghasil+ang_bankeu+ang_btt)jum_ang,sum(real_peg)real_peg,sum(real_brng)real_brng,sum(real_modal)real_mod,sum(real_hibah)real_hibah,sum(real_bansos)real_bansos,sum(real_bghasil)real_bghasil,sum(real_bankeu)real_bankeu,sum(real_btt)real_btt,sum(real_peg+real_brng+real_modal+real_hibah+real_bansos+real_bghasil+real_bankeu+real_btt)real_jum
            //       FROM [perda_lampI.3_skpd_spj]($bulan,'$jns_ang',$tahun_anggaran)
            //       where len(kd_skpd)='22'"))->first();
            $tot='';
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
            if($jenis=="2"){
                $view =  view('akuntansi.cetakan.perda.i_4_urusan.perda_i4_urusan_spj')->with($data);
            }else{
                $view =  view('akuntansi.cetakan.perda.i_4_urusan.perda_i4_urusan')->with($data);
            }
            
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
       
            $pendapatan = collect(DB::select("SELECT SUM(nilai) AS nilai_ag, SUM(kredit - debet) AS nilai_real 
                    FROM (
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai)nilai,0 debet,0 kredit
                    from trdrka 
                    where jns_ang = '$jns_ang' 
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    union all
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,0 nilai,sum(debet) debet,sum(kredit) kredit
                    from jurnal_rekap
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    )a
                    where  LEFT(kd_rek6,1)='4'"
                ))->first();
            $belanja = collect(DB::select("SELECT SUM(nilai) AS nilai_ag, SUM(debet - kredit) AS nilai_real 
                    FROM (
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai)nilai,0 debet,0 kredit
                    from trdrka 
                    where jns_ang = '$jns_ang' 
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    union all
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,0 nilai,sum(debet) debet,sum(kredit) kredit
                    from jurnal_rekap
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    )a
                    where  LEFT(kd_rek6,1)='5'"
                ))->first();
            $daftar_lra = DB::select("SELECT SUM(a.nilai) AS nilai_ag, SUM(debet - kredit) AS nilai_real
                    FROM (
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai)nilai,0 debet,0 kredit
                    from trdrka 
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    union all
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,0 nilai,sum(debet) debet,sum(kredit) kredit
                    from jurnal_rekap
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    )a
                    where  LEFT(kd_rek6,1)='5'");
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
                      SUM(a.nilai), SUM(CASE LEFT(a.kd_rek6, 1) WHEN '4' THEN a.kredit - a.debet WHEN '5' THEN a.debet - a.kredit END),
                      1 is_bold, 'bidang_urusan' AS jenis
                    FROM ms_urusan mu
                    JOIN ms_bidang_urusan mbu ON mu.kd_urusan = mbu.kd_urusan
                    JOIN (select kd_skpd,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai)nilai,sum(debet) debet,sum(kredit)kredit
                    from(
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai)nilai,0 debet,0 kredit
                    from trdrka 
                    where jns_ang = '$jns_ang' 
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    union all
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,0 nilai,sum(debet) debet,sum(kredit) kredit
                    from jurnal_rekap
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    )a
                    where  LEFT(kd_rek6,1) in ('4','5')
                    group by kd_skpd,kd_sub_kegiatan,kd_rek6)a ON mbu.kd_bidang_urusan = LEFT(a.kd_sub_kegiatan, 4)
                    GROUP BY kd_bidang_urusan, nm_bidang_urusan, LEFT(a.kd_rek6, 1)

                    UNION ALL

                    SELECT
                      CONCAT(LEFT(a.kd_rek6, 1), kd_bidang_urusan, a.kd_skpd) AS ikey, a.kd_skpd AS kode, a.nm_skpd AS nama,
                      SUM(a.nilai), SUM(CASE LEFT(a.kd_rek6, 1) WHEN '4' THEN a.kredit - a.debet WHEN '5' THEN a.debet - a.kredit END),
                      0 is_bold, 'skpd' AS jenis
                    FROM ms_urusan mu
                    JOIN ms_bidang_urusan mbu ON mu.kd_urusan = mbu.kd_urusan
                    JOIN (select kd_skpd,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai)nilai,sum(debet) debet,sum(kredit)kredit
                    from(
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai)nilai,0 debet,0 kredit
                    from trdrka 
                    where jns_ang = '$jns_ang' 
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    union all
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,0 nilai,sum(debet) debet,sum(kredit) kredit
                    from jurnal_rekap
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    )a
                    where  LEFT(kd_rek6,1) in ('4','5')
                    group by kd_skpd,kd_sub_kegiatan,kd_rek6)a ON mbu.kd_bidang_urusan = LEFT(a.kd_sub_kegiatan, 4)
                    GROUP BY kd_bidang_urusan, nm_bidang_urusan, LEFT(a.kd_rek6, 1), a.kd_skpd, a.nm_skpd

                    UNION ALL

                    SELECT
                      CONCAT(LEFT(a.kd_rek6, 1), kd_bidang_urusan, a.kd_skpd, mrek2.kd_rek2) AS ikey,
                      mrek2.kd_rek2 AS kode, mrek2.nm_rek2 AS nama,
                      SUM(a.nilai), SUM(CASE LEFT(a.kd_rek6, 1) WHEN '4' THEN a.kredit - a.debet WHEN '5' THEN a.debet - a.kredit END),
                      1 is_bold, 'rek2' AS jenis
                    FROM ms_urusan mu
                    JOIN ms_bidang_urusan mbu ON mu.kd_urusan = mbu.kd_urusan
                    JOIN (select kd_skpd,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai)nilai,sum(debet) debet,sum(kredit)kredit
                    from(
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai)nilai,0 debet,0 kredit
                    from trdrka 
                    where jns_ang = '$jns_ang' 
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    union all
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,0 nilai,sum(debet) debet,sum(kredit) kredit
                    from jurnal_rekap
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    )a
                    where  LEFT(kd_rek6,1) in ('4','5')
                    group by kd_skpd,kd_sub_kegiatan,kd_rek6)a ON mbu.kd_bidang_urusan = LEFT(a.kd_sub_kegiatan, 4)
                    JOIN ms_rek2 mrek2 ON LEFT(a.kd_rek6, 2) = mrek2.kd_rek2
                    WHERE LEFT(a.kd_rek6, 1) = '5'
                    GROUP BY kd_bidang_urusan, nm_bidang_urusan, LEFT(a.kd_rek6, 1), a.kd_skpd, a.nm_skpd, mrek2.kd_rek2, mrek2.nm_rek2

                    UNION ALL

                    SELECT
                      CONCAT(LEFT(a.kd_rek6, 1), kd_bidang_urusan, a.kd_skpd, mrek3.kd_rek3) AS ikey,
                      mrek3.kd_rek3 AS kode, mrek3.nm_rek3 AS nama,
                      SUM(a.nilai), SUM(CASE LEFT(a.kd_rek6, 1) WHEN '4' THEN a.kredit - a.debet WHEN '5' THEN a.debet - a.kredit END),
                      0 is_bold, 'rek3' AS jenis
                    FROM ms_urusan mu
                    JOIN ms_bidang_urusan mbu ON mu.kd_urusan = mbu.kd_urusan
                    JOIN (select kd_skpd,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai)nilai,sum(debet) debet,sum(kredit)kredit
                    from(
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai)nilai,0 debet,0 kredit
                    from trdrka 
                    where jns_ang = '$jns_ang' 
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    union all
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,0 nilai,sum(debet) debet,sum(kredit) kredit
                    from jurnal_rekap
                    group by  kd_skpd,kd_sub_kegiatan,kd_rek6
                    )a
                    where  LEFT(kd_rek6,1) in ('4','5')
                    group by kd_skpd,kd_sub_kegiatan,kd_rek6)a ON mbu.kd_bidang_urusan = LEFT(a.kd_sub_kegiatan, 4)
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
       
            $tot = collect(DB::select("SELECT * from data_tot_perda_i1_ringkasan(3,'P1',2023)"))->first();

            $rincian = DB::select("SELECT * from data_perda_i1_ringkasan($bulan,'$jns_ang',$tahun_anggaran) order by kode");

        
        
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

    public function cetak_i2(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tanggal_ttd    = $request->tgl_ttd;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jenis_anggaran;
        $bulan          = $request->bulan;
        // $kd_skpd        = Auth::user()->kd_skpd;
        $skpdunit    = $request->skpdunit;
        $kd_skpd        = $request->kd_skpd;
        $tahun_anggaran = tahun_anggaran();

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



            // rincian
       
            $tot = collect(DB::select("SELECT * from data_tot_perda_i1_ringkasan(3,'P1',2023)"))->first();

            $rincian = DB::select("SELECT * from data_perda_i1_ringkasan($bulan,'$jns_ang',$tahun_anggaran) order by kode");

            $sus = collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet(?,?,?) $skpd_clauses", [$bulan, $jns_ang, $tahun_anggaran]))->first();

        
        
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

    public function cetak_i3_rincian(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tanggal_ttd    = $request->tgl_ttd;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jenis_anggaran;
        // $kd_skpd        = Auth::user()->kd_skpd;
        $skpdunit    = $request->skpdunit;
        $kd_skpd        = $request->kd_skpd;
        $panjang_data        = $request->panjang_data;
        $tahun_anggaran = tahun_anggaran();
        $bulan          = $request->bulan;
        switch  ($bulan){
            case  1:
            $judul="JANUARI";
            break;
            case  2:
            $judul="FEBRUARI";
            break;
            case  3:
            $judul= "MARET";
            break;
            case  4:
            $judul="APRIL";
            break;
            case  5:
            $judul= "MEI";
            break;
            case  6:
            $judul= "JUNI";
            break;
            case  7:
            $judul= "JULI";
            break;
            case  8:
            $judul= "AGUSTUS";
            break;
            case  9:
            $judul= "SEPTEMBER";
            break;
            case  10:
            $judul= "OKTOBER";
            break;
            case  11:
            $judul= "NOVEMBER";
            break;
            case  12:
            $judul= "DESEMBER";
            break;
        }

        
        if ($skpdunit == "unit") {
            $kd_skpd = $kd_skpd;
        } else if ($skpdunit == "skpd") {
            $kd_skpd = substr($kd_skpd, 0, 17);
        }
        $skpd_clause = "AND left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd'";
        $skpd_clause_ang = "AND left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
        $skpd_clauses = "WHERE left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
        $skpd_clause_prog = "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' and ";

            // rincian
       
            $pend = DB::select("SELECT  kd_sub_kegiatan,a.kd_rek,a.nm_rek,sum(a.anggaran)anggaran,sum(a.sd_bulan_ini) sd_bulan_ini ,
                cast (b.nm_hukum as varchar(8000)) as nm_hukum
                FROM realisasi_jurnal_pend_n($bulan,'$jns_ang',$tahun_anggaran) a left join m_hukum1 b on a.kd_rek=b.kd_rek6
                $skpd_clauses and right(kd_sub_kegiatan,5)<>'' AND LEN(kd_rek)<='$panjang_data'
                group by kd_sub_kegiatan ,kd_rek,nm_rek , cast (b.nm_hukum as varchar(8000))
                ORDER BY kd_sub_kegiatan,kd_rek");

            $jum_pend = collect(DB::select("SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n($bulan,'$jns_ang',$tahun_anggaran) $skpd_clauses AND LEN(kd_rek)='4'"))->first();
            $ang_jpend=$jum_pend->anggaran;
            $real_jpend=$jum_pend->sd_bulan_ini;

            $jum_bel = collect(DB::select("SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini 
            FROM realisasi_jurnal_rinci_n($bulan,'$jns_ang',$tahun_anggaran) $skpd_clauses  AND LEN(kd_rek)='4' and urut='4' and left(kd_rek,1)in('5')"))->first();
            $ang_jbel=$jum_bel->anggaran;
            $real_jbel=$jum_bel->sd_bulan_ini;

            $belanja = DB::select("SELECT urut,kd_sub_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini 
                FROM realisasi_jurnal_rinci_n($bulan,'$jns_ang',$tahun_anggaran) $skpd_clauses
              AND SUBSTRING(kd_sub_kegiatan,17,2)!='00' and urut in ('1','2','3','4') and (kd_rek!='0' and kd_rek!='06' and left(kd_rek,1)!='6') and nm_rek!='PENDAPATAN'
              ORDER BY kd_sub_kegiatan,kd_rek");

            $ang_surplus = $ang_jpend-$ang_jbel;
            $real_surplus = $real_jpend-$real_jbel;



        
        
        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();


        // dd($kd_skpd);
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pend'               => $pend,
            'jum_pend'           => $jum_pend,
            'belanja'               => $belanja,
            'jum_bel'           => $jum_bel,
            'ang_surplus'               => $ang_surplus,
            'real_surplus'           => $real_surplus,
            'skpdunit'           => $skpdunit,
            'kd_skpd'           => $kd_skpd,
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
            $view =  view('akuntansi.cetakan.perda.perda_i3_rincian')->with($data);
        // }
        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('PERDA LAMP I3 Rincian.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="PERDA I3 Rincian.xls"');
            return $view;
        }
    }

    public function cetak_i6_piutang(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        
        $cetak          = $request->cetak;
        
        $tahun_anggaran = tahun_anggaran();
        $rincian = DB::select("SELECT b.nm_rek4 as nama, tahun, ISNULL(saldo_awal,0) saldo_awal, ISNULL(penambahan,0) penambahan, ISNULL(pengurangan,0) pengurangan
                    FROM (
                    select LEFT(kd_rek6,6) as kode, tahun, SUM(sal_awal+tahun_n) as saldo_awal, SUM(tambah) as penambahan, 
                    SUM(kurang) as pengurangan FROM lamp_aset WHERE kd_rek3 IN ('1103','1104','1105','1106','1107','1108','1109')
                    GROUP BY LEFT(kd_rek6,6), tahun
                    ) a
                    LEFT JOIN 
                    ms_rek4 b ON  a.kode=b.kd_rek4
                    ORDER BY tahun");

            
        
        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();


        // dd($kd_skpd);
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'rincian'               => $rincian,
            'daerah'            => $sc,
            'nogub'             => $nogub,
            'tahun_anggaran'    => $tahun_anggaran
        ];
        $view =  view('akuntansi.cetakan.perda.perda_i6_piutang')->with($data);
        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('PERDA LAMP I6 PIUTANG.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="PERDA I6 PIUTANG.xls"');
            return $view;
        }
    }

    public function cetak_i8_aset_tetap(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        
        $cetak          = $request->cetak;
        
        $thn_ang = tahun_anggaran();
        $thn_ang_1 = $thn_ang-1;
        $map = DB::select("SELECT uraian, seq, bold,normal, ISNULL(kode_1,'XXX') as rek FROM map_perda_aset_tetap 
                    where bold<=4
                    ORDER BY seq");

            
        
        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();


        // dd($kd_skpd);
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'map'               => $map,
            'daerah'            => $sc,
            'nogub'             => $nogub,
            'thn_ang'           => $thn_ang,
            'thn_ang_1'         => $thn_ang_1
        ];
        $view =  view('akuntansi.cetakan.perda.perda_i8_aset_tetap')->with($data);
        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('PERDA LAMP I8 ASET TETAP.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="PERDA I8 ASET TETAP.xls"');
            return $view;
        }
    }

    public function cetak_d1_keselarasan(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        
        $tanggal_ttd    = $request->tgl_ttd;
        $cetak          = $request->cetak;
        $anggaran        = $request->jenis_anggaran;
        $bulan          = $request->bulan;
        
        $lntahunang = tahun_anggaran();
        $thn_ang_1 = $lntahunang-1;
        $rincian = DB::select("SELECT a.kode, a.nama,SUM(ISNULL(a.operasi,0)) as a_operasi,SUM(ISNULL(a.modal,0)) as a_modal,SUM(ISNULL(a.btt,0)) as a_btt,SUM(ISNULL(a.bt,0)) as a_bt,SUM(ISNULL(b.operasi,0)) as r_operasi,SUM(ISNULL(b.modal,0)) as r_modal,SUM(ISNULL(b.btt,0)) as r_btt,SUM(ISNULL(b.bt,0)) as r_bt
        FROM (
            SELECT RTRIM(a.kd_fungsi)+'.'+a.kd_urusan  as kode, a.nm_fungsi as nama,SUM(ISNULL(operasi,0)) as operasi,SUM(ISNULL(modal,0)) as modal,SUM(ISNULL(btt,0)) as btt,SUM(ISNULL(bt,0)) as bt
              FROM ms_sub_fungsi a 
              LEFT JOIN
              (SELECT LEFT(kd_sub_kegiatan,4) kd_urusan ,
                case when LEFT(a.kd_rek6,2) IN ('51') then SUM(nilai) else 0 end AS operasi,
                case when LEFT(a.kd_rek6,2) IN ('52') then SUM(nilai) else 0 end AS modal,
                case when LEFT(a.kd_rek6,2) IN ('53') then SUM(nilai) else 0 end AS btt,
                case when LEFT(a.kd_rek6,2) IN ('54') then SUM(nilai) else 0 end AS bt
                FROM trdrka a 
                WHERE a.jns_ang='$anggaran' and  LEFT(a.kd_rek6,1) IN ('5')  GROUP BY LEFT(kd_sub_kegiatan,4) , LEFT(a.kd_rek6,2)
              )b ON a.kd_urusan=b.kd_urusan
              GROUP BY a.kd_fungsi,a.kd_urusan,a.nm_fungsi
            )a
        LEFT JOIN 
          (
            SELECT RTRIM(a.kd_fungsi)+'.'+a.kd_urusan as kode, a.nm_fungsi as nama,SUM(ISNULL(operasi,0)) as operasi,SUM(ISNULL(modal,0)) as modal,SUM(ISNULL(btt,0)) as btt,SUM(ISNULL(bt,0)) as bt
              FROM ms_sub_fungsi a 
              LEFT JOIN
              (SELECT LEFT(kd_sub_kegiatan,4) kd_urusan ,
                case when LEFT(a.map_real,2) IN ('51') then SUM(debet-kredit) else 0 end AS operasi,
                case when LEFT(a.map_real,2) IN ('52') then SUM(debet-kredit) else 0 end AS modal,
                case when LEFT(a.map_real,2) IN ('53') then SUM(debet-kredit) else 0 end AS btt,
                case when LEFT(a.map_real,2) IN ('54') then SUM(debet-kredit) else 0 end AS bt
                FROM trdju a INNER JOIN trhju b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
                WHERE LEFT(a.map_real,1) IN ('5')  AND MONTH(tgl_voucher)<='$bulan' AND YEAR(tgl_voucher)='$lntahunang'  GROUP BY LEFT(kd_sub_kegiatan,4) , LEFT(a.map_real,2)
              )b ON a.kd_urusan=b.kd_urusan
              GROUP BY a.kd_fungsi,a.kd_urusan,a.nm_fungsi
          )b ON a.kode=b.kode  
        group by a.kode,a.nama

        union all

        SELECT a.kode as kode, a.nama, SUM(ISNULL(a.operasi,0)) as a_operasi,SUM(ISNULL(a.modal,0)) as a_modal,SUM(ISNULL(a.btt,0)) as a_btt,SUM(ISNULL(a.bt,0)) as a_bt,
        SUM(ISNULL(b.operasi,0)) as r_operasi,SUM(ISNULL(b.modal,0)) as r_modal,SUM(ISNULL(b.btt,0)) as r_btt,SUM(ISNULL(b.bt,0)) as r_bt
        FROM (
            SELECT a.kd_fungsi as kode, a.nm_fungsi as nama ,SUM(ISNULL(operasi,0)) as operasi,SUM(ISNULL(modal,0)) as modal,SUM(ISNULL(btt,0)) as btt,SUM(ISNULL(bt,0)) as bt
              FROM ms_fungsi a 
              LEFT JOIN 
              (SELECT RTRIM(a.kd_fungsi) as kode,SUM(ISNULL(operasi,0)) as operasi,SUM(ISNULL(modal,0)) as modal,SUM(ISNULL(btt,0)) as btt,SUM(ISNULL(bt,0)) as bt
                FROM ms_sub_fungsi a 
                LEFT JOIN
                (SELECT LEFT(kd_sub_kegiatan,4) kd_urusan ,
                case when LEFT(a.kd_rek6,2) IN ('51') then SUM(nilai) else 0 end AS operasi,
                case when LEFT(a.kd_rek6,2) IN ('52') then SUM(nilai) else 0 end AS modal,
                case when LEFT(a.kd_rek6,2) IN ('53') then SUM(nilai) else 0 end AS btt,
                case when LEFT(a.kd_rek6,2) IN ('54') then SUM(nilai) else 0 end AS bt
                  FROM trdrka a 
                  WHERE a.jns_ang='$anggaran' and  LEFT(a.kd_rek6,1) IN ('5')  GROUP BY LEFT(kd_sub_kegiatan,4) , LEFT(a.kd_rek6,2)
                )b ON a.kd_urusan=b.kd_urusan
                GROUP BY a.kd_fungsi
              ) b on a.kd_fungsi=left(b.kode,1)
              GROUP BY a.kd_fungsi,nm_fungsi
          ) a
          LEFT JOIN  
          (
            SELECT a.kd_fungsi as kode, a.nm_fungsi as nama,SUM(ISNULL(operasi,0)) as operasi,SUM(ISNULL(modal,0)) as modal,SUM(ISNULL(btt,0)) as btt,SUM(ISNULL(bt,0)) as bt
              FROM ms_fungsi a 
              LEFT JOIN 
              (SELECT RTRIM(a.kd_fungsi) as kode,SUM(ISNULL(operasi,0)) as operasi,SUM(ISNULL(modal,0)) as modal,SUM(ISNULL(btt,0)) as btt,SUM(ISNULL(bt,0)) as bt
                FROM ms_sub_fungsi a 
                LEFT JOIN
                (SELECT LEFT(kd_sub_kegiatan,4) kd_urusan ,
                case when LEFT(a.map_real,2) IN ('51') then SUM(debet-kredit) else 0 end AS operasi,
                case when LEFT(a.map_real,2) IN ('52') then SUM(debet-kredit) else 0 end AS modal,
                case when LEFT(a.map_real,2) IN ('53') then SUM(debet-kredit) else 0 end AS btt,
                case when LEFT(a.map_real,2) IN ('54') then SUM(debet-kredit) else 0 end AS bt
                  FROM trdju a INNER JOIN trhju b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
                  WHERE LEFT(a.map_real,1) IN ('5')  AND MONTH(tgl_voucher)<='$bulan' AND YEAR(tgl_voucher)='$lntahunang' GROUP BY LEFT(kd_sub_kegiatan,4) , LEFT(a.map_real,2)
                    
                )b ON a.kd_urusan=b.kd_urusan
                GROUP BY a.kd_fungsi
              ) b on a.kd_fungsi=left(b.kode,1)
              GROUP BY a.kd_fungsi,nm_fungsi
          )b ON a.kode=b.kode
        group by a.kode,a.nama
        ORDER BY kode");

            
        
        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();


        // dd($kd_skpd);
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'rincian'               => $rincian,
            'daerah'            => $sc,
            'nogub'             => $nogub,
            'lntahunang'           => $lntahunang,
            'tanggal_ttd'           => $tanggal_ttd,
            'bulan'           => $bulan,
            'thn_ang_1'         => $thn_ang_1
        ];
        $view =  view('akuntansi.cetakan.perda.perda_d1_keselarasan')->with($data);
        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('PERDA LAMP I8 ASET TETAP.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="PERDA I8 ASET TETAP.xls"');
            return $view;
        }
    }
    
}
