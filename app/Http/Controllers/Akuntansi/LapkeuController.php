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

        // dd($kd_skpd);
        
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
        if ($periodebulan="periode") {
            $sus=collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet_tgl_oyoy(?,?,?) $skpd_clauses",[$tanggal1,$tanggal2,$jns_ang]))->first();
        }else if($periodebulan="bulan"){
            $sus=collect(DB::select("SELECT SUM(ang_surplus)ang_surplus,sum(nil_surplus)nil_surplus,sum(ang_neto)ang_neto,sum(nil_neto)nil_neto FROM data_jurnal_n_surnet_oyoy(?,?,?) $skpd_clauses",[$bulan,$jns_ang,$tahun_anggaran]))->first();
        }
        // dd($map_lra);
        

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
                'anggaran'          => $jns_ang,
                'thn_ang_1'         => $thn_ang1             
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
}
