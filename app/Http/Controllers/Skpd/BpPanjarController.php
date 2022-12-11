<?php

namespace App\Http\Controllers\Skpd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;

class BpPanjarController extends Controller
{
    

    // Cetak Buku Panjar 
    public function cetakBpPanjar(Request $request)
    {
            $tanggal_ttd    = $request->tgl_ttd ;
            $pa_kpa         = $request->pa_kpa ;
            $bendahara      = $request->bendahara ;
            $bulan          = $request->bulan;
            $enter          = $request->spasi;
            $kd_skpd        = get_userskpd($request->kd_skpd);
            $tahun_anggaran = tahun_anggaran();

            // TANDA TANGAN
            $cari_bendahara = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $bendahara, 'kode' => 'BK', 'kd_skpd' => $kd_skpd])->first();
            $cari_pakpa = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $pa_kpa, 'kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->first();

            $sawal = DB::select("SELECT 
            sum(case when jns=1 then terima else 0 end)-
            sum(case when jns=2 then keluar else 0 end) as sawal
            FROM(
            SELECT no_kas AS bku,tgl_kas AS tgl, keterangan,nilai AS terima,'0' AS keluar,'1' as jns,kd_skpd FROM tr_jpanjar where jns=1 UNION
            SELECT no_panjar AS bku,tgl_panjar AS tgl, keterangan,'0' AS terima,nilai AS keluar,'2' as jns,kd_skpd FROM tr_panjar where jns=1) a WHERE MONTH(tgl)< ?  AND kd_skpd= ? ", [$bulan,$kd_skpd]);

            foreach ($sawal as $sawal)
            {
                $saldo_awal  =$sawal->sawal;
            }
            
            $rincian = DB::select("SELECT * FROM(
                SELECT no_kas AS bku,tgl_kas AS tgl,('Pertanggungjawaban '+keterangan + '  (No Panjar:' +no_panjar+')') AS ket,nilai AS terima,'0' AS keluar,'1' as jns,kd_skpd FROM tr_jpanjar where jns=1 UNION
                SELECT no_panjar AS bku,tgl_panjar AS tgl,(keterangan) AS ket,'0' AS terima,nilai AS keluar,'2' as jns,kd_skpd FROM tr_panjar where jns=1) a WHERE MONTH(tgl)= ? AND kd_skpd= ? ORDER BY tgl, Cast(bku as decimal) ", [$bulan,$kd_skpd]);
            
            
            $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();

        // KIRIM KE VIEW
            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan','logo_pemda_hp')->first(),
                'skpd'              => DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
                'bulan'             => $bulan,
                'saldoawal'         => $saldo_awal,
                'rincian'           => $rincian,
                'enter'             => $enter,
                'daerah'            => $daerah,
                'tanggal_ttd'       => $tanggal_ttd,
                'cari_pa_kpa'       => $cari_pakpa,
                'cari_bendahara'    => $cari_bendahara
            ];

        return view('skpd.laporan_bendahara.cetak.bp_panjar')->with($data);
    }

    
}
