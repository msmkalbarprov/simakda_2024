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
        }else if($jenis=="6"){
            $rincian = DB::select("SELECT kd_skpd kode,nm_skpd nm_rek,ang_peg,ang_brng,ang_modal ang_mod,ang_hibah,ang_bansos,ang_bghasil,
            ang_bankeu,ang_btt,
          real_peg,real_brng,real_modal real_mod,real_hibah,real_bansos,real_bghasil,real_bankeu,real_btt 
          FROM [perda_lampi4_spj_rinci]($bulan,'$jns_ang',$tahun_anggaran)
          where len(kd_skpd)<='22'
          ORDER BY kd_skpd"
                );
            $tot=collect(DB::select("SELECT sum(ang_peg)ang_peg,sum(ang_brng)ang_brng,sum(ang_modal) ang_mod,sum(ang_hibah)ang_hibah,sum(ang_bansos)ang_bansos,sum(ang_bghasil)ang_bghasil,sum(ang_bankeu)ang_bankeu,sum(ang_btt)ang_btt, sum(ang_peg+ang_brng+ang_modal+ang_hibah+ang_bansos+ang_bghasil+ang_bankeu+ang_btt)jum_ang,
                sum(real_peg)real_peg,sum(real_brng)real_brng,sum(real_modal) real_mod,sum(real_hibah)real_hibah,sum(real_bansos)real_bansos,sum(real_bghasil)real_bghasil,sum(real_bankeu)real_bankeu,sum(real_btt)real_btt ,sum(real_peg+real_brng+real_modal+real_hibah+real_bansos+real_bghasil+real_bankeu+real_btt)real_jum
                FROM [perda_lampi4_spj_rinci]($bulan,'$jns_ang',$tahun_anggaran)
                where len(kd_skpd)='22'
                "))->first();
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
            }else if($jenis=="6"){
                $view =  view('akuntansi.cetakan.perda.i_4_urusan.perda_i4_urusan_spj_rinci')->with($data);
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

    public function cetak_d3(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        
        $tanggal_ttd    = $request->tgl_ttd;
        $cetak          = $request->cetak;
        $anggaran        = $request->jenis_anggaran;
        
        $thn_ang = tahun_anggaran();
        $thn_ang_1 = $thn_ang-1;
        //pendidikan
            $a_pendidikan = DB::select("SELECT a.kd_kegiatan,a.nm_kegiatan,nilai,realisasi from(
                    SELECT kd_kegiatan,b.nm_kegiatan,sum(a.nilai) nilai from trdrka a
                    inner join ms_kegiatan b on left(a.kd_sub_kegiatan,12)=b.kd_kegiatan 
                    where left(a.kd_sub_kegiatan,9)='1.01.02.1' and left(a.kd_rek6,1)='5' and a.jns_ang='$anggaran'
                    group by kd_kegiatan,b.nm_kegiatan ) a 
                    left join 
                    (Select left(kd_sub_kegiatan,12) as kd_kegiatan,SUM(b.debet-b.kredit) AS realisasi from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                                      and b.kd_unit=a.kd_skpd where year(tgl_voucher)='$thn_ang' and
                                        left(kd_rek6,1) in ('5')
                        group by left(kd_sub_kegiatan,12)
                        
                    )b on a.kd_kegiatan=b.kd_kegiatan");
            $ang_sma=0;
            $real_sma=0;
            $ang_smk=0;
            $real_smk=0;
            $ang_khusus=0;
            $real_khusus=0;
            foreach($a_pendidikan as $row){
                $kd_kegiatan = $row->kd_kegiatan;
                $nm_kegiatan = $row->nm_kegiatan;
                $nilai = $row->nilai;
                $realisasi = $row->realisasi;

                if ($kd_kegiatan=="1.01.02.1.01") {
                    $kd_kegiatan_sma = $kd_kegiatan;
                    $ang_sma = $ang_sma+$nilai;
                    $real_sma = $real_sma+$realisasi;
                }
                // dd($ang_sma);
                if ($kd_kegiatan=="1.01.02.1.02") {
                    $kd_kegiatan_smk = $kd_kegiatan;
                    $ang_smk = $ang_smk+$nilai;
                    $real_smk = $real_smk+$realisasi;
                }
                if ($kd_kegiatan=="1.01.02.1.03") {
                    $kd_kegiatan_khusus = $kd_kegiatan;
                    $ang_khusus = $ang_khusus+$nilai;
                    $real_khusus = $real_khusus+$realisasi;
                }
            }
        //

        //kesehatan
            $b_kesehatan = DB::select("SELECT a.kd_kegiatan,a.nm_kegiatan,a.kd_sub_kegiatan,a.nm_sub_kegiatan,nilai,realisasi from(
                SELECT kd_kegiatan,b.nm_kegiatan,kd_sub_kegiatan,nm_sub_kegiatan,sum(a.nilai) nilai from trdrka a
                inner join ms_kegiatan b on left(a.kd_sub_kegiatan,12)=b.kd_kegiatan 
                where left(a.kd_sub_kegiatan,12)='1.02.02.1.02' and left(a.kd_rek6,1)='5'  and a.jns_ang='$anggaran'and kd_sub_kegiatan in('1.02.02.1.02.01','1.02.02.1.02.02')
                group by kd_kegiatan,b.nm_kegiatan,kd_sub_kegiatan,nm_sub_kegiatan ) a left join 
                (
                Select left(kd_sub_kegiatan,12) as kd_kegiatan,kd_sub_kegiatan,SUM(b.debet-b.kredit) AS realisasi from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                                  and b.kd_unit=a.kd_skpd where year(tgl_voucher)='$thn_ang' and
                                    left(kd_rek6,1) in ('5') and  left(kd_sub_kegiatan,12)='1.02.02.1.02'
                    group by left(kd_sub_kegiatan,12),kd_sub_kegiatan
                    
                )b on a.kd_kegiatan=b.kd_kegiatan and a.kd_sub_kegiatan=b.kd_sub_kegiatan");
            $ang_bencana=0;
            $real_bencana=0;
            $ang_lb=0;
            $real_lb=0;
            foreach($b_kesehatan as $row){
                $kd_kegiatan = $row->kd_kegiatan;
                $nm_kegiatan = $row->nm_kegiatan;
                $kd_sub_kegiatan = $row->kd_sub_kegiatan;
                $nm_sub_kegiatan = $row->nm_sub_kegiatan;
                $nilai = $row->nilai;
                $realisasi = $row->realisasi;

                if ($kd_sub_kegiatan=="1.02.02.1.02.01") {
                    $kd_sub_kegiatan_bencana = $kd_sub_kegiatan;
                    $ang_bencana = $ang_bencana+$nilai;
                    $real_bencana = $real_bencana+$realisasi;
                }
                if ($kd_sub_kegiatan=="1.02.02.1.02.01") {
                    $kd_sub_kegiatan_lb = $kd_sub_kegiatan;
                    $ang_lb = $ang_lb+$nilai;
                    $real_lb = $real_lb+$realisasi;
                }
            }
        //

        //C bidang pu dan pr
            $c_pupr = DB::select("SELECT a.kd_kegiatan,a.nm_kegiatan,nilai,realisasi from(
                SELECT kd_kegiatan,b.nm_kegiatan,sum(a.nilai) nilai from trdrka a
                inner join ms_kegiatan b on left(a.kd_sub_kegiatan,12)=b.kd_kegiatan 
                where left(a.kd_rek6,1)='5' and a.kd_sub_kegiatan in ('1.03.03.1.01.01','1.03.05.1.01.01') and a.jns_ang='$anggaran'
                group by kd_kegiatan,b.nm_kegiatan ) a left join 
                (
                Select left(kd_sub_kegiatan,12) as kd_kegiatan,SUM(b.debet-b.kredit) AS realisasi from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                                  and b.kd_unit=a.kd_skpd where year(tgl_voucher)='$thn_ang' and
                                    left(kd_rek6,1) in ('5') and kd_sub_kegiatan in ('1.03.03.1.01.01','1.03.05.1.01.01')
                    group by left(kd_sub_kegiatan,12)
                    
                )b on a.kd_kegiatan=b.kd_kegiatan");
            $ang_spam=0;
            $real_spam=0;
            $ang_saldr=0;
            $real_saldr=0;
            foreach($c_pupr as $row){
                $kd_kegiatan = $row->kd_kegiatan;
                $nm_kegiatan = $row->nm_kegiatan;
                $nilai = $row->nilai;
                $realisasi = $row->realisasi;

                if ($kd_kegiatan=="1.03.03.1.01") {
                    $kd_kegiatan_spam = $kd_kegiatan;
                    $ang_spam = $ang_spam+$nilai;
                    $real_spam = $real_spam+$realisasi;
                }
                if ($kd_kegiatan=="1.03.05.1.01") {
                    $kd_kegiatan_saldr = $kd_kegiatan;
                    $ang_saldr = $ang_saldr+$nilai;
                    $real_saldr = $real_saldr+$realisasi;
                }
            }
        //

        //D bidang pr dan kp
            $d_prkp = DB::select("SELECT a.kd_kegiatan,a.nm_kegiatan,nilai,realisasi from(
                SELECT kd_kegiatan,b.nm_kegiatan,sum(a.nilai) nilai from trdrka a
                inner join ms_kegiatan b on left(a.kd_sub_kegiatan,12)=b.kd_kegiatan 
                where left(a.kd_rek6,1)='5' and 
                a.kd_sub_kegiatan in ('1.04.02.1.01.01','1.04.02.1.03.01','1.04.02.1.03.02','1.04.02.1.03.03','1.04.02.1.03.04','1.04.02.1.03.05','1.04.02.1.03.06') and a.jns_ang='$anggaran'
                group by kd_kegiatan,b.nm_kegiatan ) a left join 
                (
                Select left(kd_sub_kegiatan,12) as kd_kegiatan,SUM(b.debet-b.kredit) AS realisasi from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher  and b.kd_unit=a.kd_skpd 
                where year(tgl_voucher)='$thn_ang' and
                left(kd_rek6,1) in ('5') and kd_sub_kegiatan in ('1.04.02.1.01.01','1.04.02.1.03.01','1.04.02.1.03.02','1.04.02.1.03.03','1.04.02.1.03.04','1.04.02.1.03.05','1.04.02.1.03.06')
                group by left(kd_sub_kegiatan,12)
                    
                )b on a.kd_kegiatan=b.kd_kegiatan");
            $ang_penyediaan=0;
            $real_penyediaan=0;
            $ang_pembangunan=0;
            $real_pembangunan=0;
            foreach($d_prkp as $row){
                $kd_kegiatan = $row->kd_kegiatan;
                $nm_kegiatan = $row->nm_kegiatan;
                $nilai = $row->nilai;
                $realisasi = $row->realisasi;

                if ($kd_kegiatan=="1.04.02.1.01") {
                    $kd_kegiatan_penyediaan = $kd_kegiatan;
                    $ang_penyediaan = $ang_penyediaan+$nilai;
                    $real_penyediaan = $real_penyediaan+$realisasi;
                }
                if ($kd_kegiatan=="1.04.02.1.03") {
                    $kd_kegiatan_pembangunan = $kd_kegiatan;
                    $ang_pembangunan = $ang_pembangunan+$nilai;
                    $real_pembangunan = $real_pembangunan+$realisasi;
                }
            }
        //
          

        //E bidang Ketentraman dan ketertiban umum
            $e_kku = DB::select("SELECT a.kd_kegiatan,a.nm_kegiatan,nilai,realisasi from(
                SELECT kd_kegiatan,b.nm_kegiatan,sum(a.nilai) nilai from trdrka a
                inner join ms_kegiatan b on left(a.kd_sub_kegiatan,12)=b.kd_kegiatan 
                where left(a.kd_rek6,1)='5' 
                and a.kd_sub_kegiatan in('1.05.02.1.01.01','1.05.02.1.01.02','1.05.02.1.01.03','1.05.02.1.01.04','1.05.02.1.01.05','1.05.02.1.01.06','1.05.02.1.01.07','1.05.02.1.01.08','1.05.02.1.01.09',
                '1.05.02.1.03.01','1.05.02.1.03.02') 
                and a.jns_ang='$anggaran'
                group by kd_kegiatan,b.nm_kegiatan ) a left join 
                (
                Select left(kd_sub_kegiatan,12) as kd_kegiatan,SUM(b.debet-b.kredit) AS realisasi from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
                where year(tgl_voucher)='$thn_ang' and left(kd_rek6,1) in ('5')and 
                kd_sub_kegiatan in('1.05.02.1.01.01','1.05.02.1.01.02','1.05.02.1.01.03','1.05.02.1.01.04','1.05.02.1.01.05','1.05.02.1.01.06','1.05.02.1.01.07','1.05.02.1.01.08','1.05.02.1.01.09',
                '1.05.02.1.03.01','1.05.02.1.03.02')
                    group by left(kd_sub_kegiatan,12)
                    
                )b on a.kd_kegiatan=b.kd_kegiatan");
            $ang_gangguan=0;
            $real_gangguan=0;
            $ang_ppns=0;
            $real_ppns=0;
            foreach($e_kku as $row){
                $kd_kegiatan = $row->kd_kegiatan;
                $nm_kegiatan = $row->nm_kegiatan;
                $nilai = $row->nilai;
                $realisasi = $row->realisasi;

                if ($kd_kegiatan=="1.05.02.1.01") {
                    $kd_kegiatan_gangguan = $kd_kegiatan;
                    $ang_gangguan = $ang_gangguan+$nilai;
                    $real_gangguan = $real_gangguan+$realisasi;
                }
                if ($kd_kegiatan=="1.05.02.1.03") {
                    $kd_kegiatan_ppns = $kd_kegiatan;
                    $ang_ppns = $ang_ppns+$nilai;
                    $real_ppns = $real_ppns+$realisasi;
                }
            }
        //  

        //F bidang Sosial
            $f_bs = DB::select("SELECT a.kd_kegiatan,a.nm_kegiatan,nilai,realisasi from(
            SELECT kd_kegiatan,b.nm_kegiatan,sum(a.nilai) nilai from trdrka a
            inner join ms_kegiatan b on left(a.kd_sub_kegiatan,12)=b.kd_kegiatan 
            where left(a.kd_sub_kegiatan,12)in('1.06.04.1.01','1.06.04.1.02','1.06.04.1.03','1.06.04.1.04','1.06.06.1.01') and left(a.kd_rek6,1)='5' 
            and a.kd_sub_kegiatan in('1.06.04.1.01.06',
            '1.06.04.1.02.02','1.06.04.1.02.03','1.06.04.1.02.06','1.06.04.1.02.07','1.06.04.1.02.09','1.06.04.1.02.10',
            '1.06.04.1.03.01','1.06.04.1.03.02','1.06.04.1.03.05','1.06.04.1.03.06','1.06.04.1.03.12',
            '1.06.04.1.04.01','1.06.04.1.04.02','1.06.04.1.04.04','1.06.04.1.04.05','1.06.04.1.04.07','1.06.04.1.04.08',
            '1.06.06.1.01.01','1.06.06.1.01.02','1.06.06.1.01.04','1.06.06.1.01.05') and a.jns_ang='$anggaran'
            group by kd_kegiatan,b.nm_kegiatan ) a left join 
            (
            Select left(kd_sub_kegiatan,12) as kd_kegiatan,SUM(b.debet-b.kredit) AS realisasi from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
            where year(tgl_voucher)='$thn_ang' and left(kd_rek6,1) in ('5') 
            and left(kd_sub_kegiatan,12)in('1.06.04.1.01','1.06.04.1.02','1.06.04.1.03','1.06.04.1.04','1.06.06.1.01')
            and kd_sub_kegiatan in ('1.06.04.1.01.06',
                                '1.06.04.1.02.02','1.06.04.1.02.03','1.06.04.1.02.06','1.06.04.1.02.07','1.06.04.1.02.09','1.06.04.1.02.10',
                                '1.06.04.1.03.01','1.06.04.1.03.02','1.06.04.1.03.05','1.06.04.1.03.06','1.06.04.1.03.12',
                                '1.06.04.1.04.01','1.06.04.1.04.02','1.06.04.1.04.04','1.06.04.1.04.05','1.06.04.1.04.07','1.06.04.1.04.08',
                                '1.06.06.1.01.01','1.06.06.1.01.02','1.06.06.1.01.04','1.06.06.1.01.05')
                group by left(kd_sub_kegiatan,12)
                
            )b on a.kd_kegiatan=b.kd_kegiatan
            order by a.kd_kegiatan");
            $ang_10604101=0;
            $real_10604101=0;
            $ang_10604102=0;
            $real_10604102=0;
            $ang_10604103=0;
            $real_10604103=0;
            $ang_10604104=0;
            $real_10604104=0;
            $ang_10606101=0;
            $real_10606101=0;
            foreach($f_bs as $row){
                $kd_kegiatan = $row->kd_kegiatan;
                $nm_kegiatan = $row->nm_kegiatan;
                $nilai = $row->nilai;
                $realisasi = $row->realisasi;

                if ($kd_kegiatan=="1.06.04.1.01") {
                    $kd_kegiatan_10604101 = $kd_kegiatan;
                    $ang_10604101 = $ang_10604101+$nilai;
                    $real_10604101 = $real_10604101+$realisasi;
                }
                if ($kd_kegiatan=="1.06.04.1.02") {
                    $kd_kegiatan_10604102 = $kd_kegiatan;
                    $ang_10604102 = $ang_10604102+$nilai;
                    $real_10604102 = $real_10604102+$realisasi;
                }
                if ($kd_kegiatan=="1.06.04.1.03") {
                    $kd_kegiatan_10604103 = $kd_kegiatan;
                    $ang_10604103 = $ang_10604103+$nilai;
                    $real_10604103 = $real_10604103+$realisasi;
                }
                if ($kd_kegiatan=="1.06.04.1.04") {
                    $kd_kegiatan_10604104 = $kd_kegiatan;
                    $ang_10604104 = $ang_10604104+$nilai;
                    $real_10604104 = $real_10604104+$realisasi;
                }
                if ($kd_kegiatan=="1.06.06.1.01") {
                    $kd_kegiatan_10606101 = $kd_kegiatan;
                    $ang_10606101 = $ang_10606101+$nilai;
                    $real_10606101 = $real_10606101+$realisasi;
                }
            }
        //  
        
        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();


        // dd($kd_skpd);
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'kd_kegiatan_sma'           => $kd_kegiatan_sma,
            'ang_sma'                   => $ang_sma,
            'real_sma'                  => $real_sma,
            'kd_kegiatan_smk'           => $kd_kegiatan_smk,
            'ang_smk'                   => $ang_smk,
            'real_smk'                  => $real_smk,
            'kd_kegiatan_khusus'           => $kd_kegiatan_khusus,
            'ang_khusus'                   => $ang_khusus,
            'real_khusus'                  => $real_khusus,
            'kd_kegiatan_bencana'       => substr($kd_sub_kegiatan_bencana,0,12),
            'kd_sub_kegiatan_bencana'   => $kd_sub_kegiatan_bencana,
            'ang_bencana'               => $ang_bencana,
            'real_bencana'              => $real_bencana,
            'kd_kegiatan_lb'            => substr($kd_sub_kegiatan_lb,0,12),
            'kd_sub_kegiatan_lb'        => $kd_sub_kegiatan_lb,
            'ang_lb'                    => $ang_lb,
            'real_lb'                   => $real_lb,
            'kd_kegiatan_spam'          => $kd_kegiatan_spam,
            'ang_spam'                  => $ang_spam,
            'real_spam'                 => $real_spam,
            'kd_kegiatan_saldr'         => $kd_kegiatan_saldr,
            'ang_saldr'                 => $ang_saldr,
            'real_saldr'                => $real_saldr,
            'kd_kegiatan_penyediaan'    => $kd_kegiatan_penyediaan,
            'ang_penyediaan'            => $ang_penyediaan,
            'real_penyediaan'           => $real_penyediaan,
            'kd_kegiatan_pembangunan'   => $kd_kegiatan_pembangunan,
            'ang_pembangunan'           => $ang_pembangunan,
            'real_pembangunan'          => $real_pembangunan,
            'kd_kegiatan_gangguan'      => $kd_kegiatan_gangguan,
            'ang_gangguan'              => $ang_gangguan,
            'real_gangguan'             => $real_gangguan,
            'kd_kegiatan_ppns'          => $kd_kegiatan_ppns,
            'ang_ppns'                  => $ang_ppns,
            'real_ppns'                 => $real_ppns,
            'kd_kegiatan_10604101'      => $kd_kegiatan_10604101,
            'ang_10604101'              => $ang_10604101,
            'real_10604101'             => $real_10604101,
            'kd_kegiatan_10604102'      => $kd_kegiatan_10604102,
            'ang_10604102'              => $ang_10604102,
            'real_10604102'             => $real_10604102,
            'kd_kegiatan_10604103'      => $kd_kegiatan_10604103,
            'ang_10604103'              => $ang_10604103,
            'real_10604103'             => $real_10604103,
            'kd_kegiatan_10604104'      => $kd_kegiatan_10604104,
            'ang_10604104'              => $ang_10604104,
            'real_10604104'             => $real_10604104,
            'kd_kegiatan_10606101'      => $kd_kegiatan_10606101,
            'ang_10606101'              => $ang_10606101,
            'real_10606101'             => $real_10606101,
            'daerah'            => $sc,
            'nogub'             => $nogub,
            'thn_ang'           => $thn_ang,
            'tanggal_ttd'       => $tanggal_ttd,
            'thn_ang_1'         => $thn_ang_1
        ];
        $view =  view('akuntansi.cetakan.perda.perda_d3')->with($data);
        
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

    public function cetak_d4(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        
        $tanggal_ttd    = $request->tgl_ttd;
        $cetak          = $request->cetak;
        $anggaran        = $request->jenis_anggaran;
        
        $thn_ang = tahun_anggaran();
        $thn_ang_1 = $thn_ang-1;
        $rincian = DB::select("SELECT kd_rek,
              case when len(kd_rek)=1 then (select kelompok from ms_rek1 where kd_rek=kd_rek1)
              when len(kd_rek)=2 then (select kelompok from ms_rek2 where kd_rek=kd_rek2)
              when len(kd_rek)=4 then (select kelompok from ms_rek3 where kd_rek=kd_rek3)
              when len(kd_rek)=6 then concat(SUBSTRING(kd_rek, 1, 1),'.',SUBSTRING(kd_rek, 2, 1),'.',SUBSTRING(kd_rek, 3, 2),'.',SUBSTRING(kd_rek, 5, 2))
              when len(kd_rek)=8 then concat(SUBSTRING(kd_rek, 1, 1),'.',SUBSTRING(kd_rek, 2, 1),'.',SUBSTRING(kd_rek, 3, 2),'.',SUBSTRING(kd_rek, 5, 2),'.',SUBSTRING(kd_rek, 7, 2))
              when len(kd_rek)=12 then (select kelompok from ms_rek6 where kd_rek=kd_rek6) else '' end kelompok,
              case when len(kd_rek)=1 then (select nm_rek1 from ms_rek1 where kd_rek=kd_rek1)
              when len(kd_rek)=2 then (select nm_rek2 from ms_rek2 where kd_rek=kd_rek2)
              when len(kd_rek)=4 then (select nm_rek3 from ms_rek3 where kd_rek=kd_rek3)
              when len(kd_rek)=6 then (select nm_rek4 from ms_rek4 where kd_rek=kd_rek4)
              when len(kd_rek)=8 then (select nm_rek5 from ms_rek5 where kd_rek=kd_rek5)
              when len(kd_rek)=12 then (select nm_rek6 from ms_rek6 where kd_rek=kd_rek6) else '' end nm_rek,
              sum(anggaran)anggaran,sum(realisasi)realisasi
              from(select left(kd_rek6,1)kd_rek ,sum(anggaran)anggaran,sum(realisasi)realisasi 
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,1)
              union all
              select left(kd_rek6,2)kd_rek ,sum(anggaran)anggaran,sum(realisasi)realisasi 
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,2)
              union all
              select left(kd_rek6,4)kd_rek ,sum(anggaran)anggaran,sum(realisasi)realisasi 
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,4)
              union all
              select left(kd_rek6,6)kd_rek ,sum(anggaran)anggaran,sum(realisasi)realisasi 
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,6)
              union all
              select left(kd_rek6,8)kd_rek ,sum(anggaran)anggaran,sum(realisasi)realisasi 
              from perda_d4(12,'$anggaran',$thn_ang)
              group by  left(kd_rek6,8)
              union all
              select left(kd_rek6,12)kd_rek ,sum(anggaran)anggaran,sum(realisasi)realisasi 
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,12)) a 
              group by kd_rek
              union all
              select '552' kd_rek,'' kelompok,'Surplus / (Defisit)'nm_rek, sum(ang_pend-ang_bel)anggaran,sum(real_pend-real_bel)realisasi
              from(
              select 
              case when left(kd_rek6,1)='4' then sum(anggaran) else 0 end ang_pend,
              case when left(kd_rek6,1)='5' then sum(anggaran) else 0 end ang_bel,
              case when left(kd_rek6,1)='4' then sum(realisasi) else 0 end real_pend,
              case when left(kd_rek6,1)='5' then sum(realisasi) else 0 end real_bel
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,1))a
              union all
              select '632' kd_rek,'' kelompok,'Pembiayaan Neto'nm_rek, sum(ang_pend-ang_bel)anggaran,sum(real_pend-real_bel)realisasi
              from(
              select 
              case when left(kd_rek6,2)='61' then sum(anggaran) else 0 end ang_pend,
              case when left(kd_rek6,2)='62' then sum(anggaran) else 0 end ang_bel,
              case when left(kd_rek6,2)='61' then sum(realisasi) else 0 end real_pend,
              case when left(kd_rek6,2)='62' then sum(realisasi) else 0 end real_bel
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,2))a
              union all
              select '441' kd_rek,'' kelompok,'Jumlah Pendapatan'nm_rek, sum(ang_pend)anggaran,sum(real_pend)realisasi
              from(
              select 
              case when left(kd_rek6,1)='4' then sum(anggaran) else 0 end ang_pend,
              case when left(kd_rek6,1)='4' then sum(realisasi) else 0 end real_pend
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,1))a
              union all
              select '551' kd_rek,'' kelompok,'Jumlah Belanja'nm_rek, sum(ang_pend)anggaran,sum(real_pend)realisasi
              from(
              select 
              case when left(kd_rek6,1)='5' then sum(anggaran) else 0 end ang_pend,
              case when left(kd_rek6,1)='5' then sum(realisasi) else 0 end real_pend
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,1))a
              union all
              select '611' kd_rek,'' kelompok,'Jumlah Penerimaan Pembiayaan'nm_rek, sum(ang_pend)anggaran,sum(real_pend)realisasi
              from(
              select 
              case when left(kd_rek6,2)='61' then sum(anggaran) else 0 end ang_pend,
              case when left(kd_rek6,2)='61' then sum(realisasi) else 0 end real_pend
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,2))a
              union all
              select '621' kd_rek,'' kelompok,'Jumlah Penerimaan Pembiayaan'nm_rek, sum(ang_pend)anggaran,sum(real_pend)realisasi
              from(
              select 
              case when left(kd_rek6,2)='62' then sum(anggaran) else 0 end ang_pend,
              case when left(kd_rek6,2)='62' then sum(realisasi) else 0 end real_pend
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,2))a
              union all
              select '633' kd_rek,'' kelompok,'Sisa lebih pembiayaan anggaran tahun berkenaan (SILPA)'nm_rek, sum((ang_pend-ang_bel)+(ang_pene-ang_peng))anggaran,sum((real_pend-real_bel)+(real_pene-real_peng))realisasi
              from(
              select 
              case when left(kd_rek6,1)='4' then sum(anggaran) else 0 end ang_pend,
              case when left(kd_rek6,1)='5' then sum(anggaran) else 0 end ang_bel,
              case when left(kd_rek6,1)='4' then sum(realisasi) else 0 end real_pend,
              case when left(kd_rek6,1)='5' then sum(realisasi) else 0 end real_bel,
              0 ang_pene,0 ang_peng,0 real_pene,0 real_peng
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,1)
              union all
              select 
              0 ang_pend,0 ang_bel,0 real_pend,0 real_bel,
              case when left(kd_rek6,2)='61' then sum(anggaran) else 0 end ang_pene,
              case when left(kd_rek6,2)='62' then sum(anggaran) else 0 end ang_peng,
              case when left(kd_rek6,2)='61' then sum(realisasi) else 0 end real_pene,
              case when left(kd_rek6,2)='62' then sum(realisasi) else 0 end real_peng
              from perda_d4(12,'$anggaran',$thn_ang)
              group by left(kd_rek6,2))a
              order by kd_rek");

            
        
        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();


        // dd($kd_skpd);
        


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'rincian'           => $rincian,
            'daerah'            => $sc,
            'nogub'             => $nogub,
            'thn_ang'           => $thn_ang,
            'tanggal_ttd'       => $tanggal_ttd,
            'thn_ang_1'         => $thn_ang_1
        ];
        $view =  view('akuntansi.cetakan.perda.perda_d4')->with($data);
        
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
