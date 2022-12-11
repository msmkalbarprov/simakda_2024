<?php

namespace App\Http\Controllers\Skpd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;

class RegisterSppSpmSp2dController extends Controller
{
    

    // Cetak Register SPP/SPM/SP2D
    public function cetakRegisterSppSpmSp2d (Request $request)
    {
            $tanggal_ttd    = $request->tgl_ttd ;
            $pa_kpa         = $request->pa_kpa ;
            $bendahara      = $request->bendahara ;
            $bulan          = $request->bulan;
            $enter          = $request->spasi;
            $kd_skpd        = $request->kd_skpd;
            $tahun_anggaran = tahun_anggaran();
            
            // TANDA TANGAN
            $cari_bendahara = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $bendahara, 'kode' => 'BK', 'kd_skpd' => $kd_skpd])->first();
            $cari_pakpa = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $pa_kpa, 'kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->first();
            $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
            $nm_skpd = cari_nama($kd_skpd,'ms_skpd','kd_skpd','nm_skpd');

            $rincian = DB::select("SELECT 
                        a.tgl_spp,a.no_spp,a.keperluan,a.jns_spp,SUM(b.nilai) nilai 
                        FROM trhspp a 
                        INNER JOIN trdspp b ON a.kd_skpd=b.kd_skpd AND a.no_spp=b.no_spp
                        WHERE (a.sp2d_batal=0 OR a.sp2d_batal is NULL) and  month(a.tgl_spp)<= ?
                        and a.kd_skpd= ?
                        GROUP BY a.tgl_spp,a.no_spp,a.keperluan,a.jns_spp
                        ORDER BY a.tgl_spp,a.no_spp",[$bulan,$kd_skpd]);
        

        // KIRIM KE VIEW
            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan','logo_pemda_hp')->first(),
                'skpd'              => DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
                'bulan'             => $bulan,
                'nm_skpd'           => $nm_skpd,
                'rincian'           => $rincian,
                'enter'             => $enter,
                'daerah'            => $daerah,
                'tanggal_ttd'       => $tanggal_ttd,
                'cari_pa_kpa'       => $cari_pakpa,
                'cari_bendahara'    => $cari_bendahara
            ];

        return view('skpd.laporan_bendahara.cetak.registersppspmsp2d')->with($data);
    }
}