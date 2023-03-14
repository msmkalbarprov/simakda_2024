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
    
}
