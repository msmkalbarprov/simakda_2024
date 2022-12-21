<?php

namespace App\Http\Controllers\Skpd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;
use PDF;
use Knp\Snappy\Pdf as SnappyPdf;

class RealisasiFisikController extends Controller
{
    

    // Cetak Buku Panjar 
    public function cetakRealisasiFisik(Request $request)
    {
            $tanggal_ttd    = $request->tgl_ttd ;
            $pa_kpa         = $request->pa_kpa ;
            $bendahara      = $request->bendahara ;
            $bulan          = $request->bulan;
            $enter          = $request->spasi;
            $cetak          = $request->cetak;
            if(strlen($request->kd_skpd) == 17){
                $kd_skpd        = $request->kd_skpd.'.0000';
                $kd_org         = $request->kd_skpd;
            }else{
                $kd_skpd        = $request->kd_skpd;
                $kd_org         = "";
            }
            $jns_anggaran   = $request->jns_anggaran ;
            $tahun_anggaran = tahun_anggaran();
            
            // TANDA TANGAN
            $cari_bendahara = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $bendahara, 'kode' => 'BK', 'kd_skpd' => $kd_skpd])->first();
            $cari_pakpa = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $pa_kpa, 'kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->first();
            
            
            if(strlen($request->kd_skpd)== '17'){
                $rincian      = DB::select("exec realisasi_fisik_org ?,?,?", array($kd_org,$bulan,$jns_anggaran));
            }else{
                $rincian      = DB::select("exec realisasi_fisik ?,?,?", array($kd_skpd,$bulan,$jns_anggaran));
            }
            
            
            
            $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            $nm_skpd = cari_nama($kd_skpd,'ms_skpd','kd_skpd','nm_skpd');
        // KIRIM KE VIEW
            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan','logo_pemda_hp')->first(),
                'skpd'              => DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
                'bulan'             => $bulan,
                'rincian'           => $rincian,
                'enter'             => $enter,
                'daerah'            => $daerah,
                'tanggal_ttd'       => $tanggal_ttd,
                'cari_pa_kpa'       => $cari_pakpa,
                'cari_bendahara'    => $cari_bendahara
            ];

        $view =  view('skpd.laporan_bendahara.cetak.realisasi_fisik')->with($data);
        if($cetak=='1'){
            return $view;
        }else if($cetak=='2'){
            $pdf = PDF::loadHtml($view)->setOrientation('landscape')->setPaper('a4');
            return $pdf->stream('REALISASI FISIK.pdf');
        }else{
            
            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="REALISASI FISIK - ' . $nm_skpd . '.xls"');
            return $view;

        }
    }

    
}
