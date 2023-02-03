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

class BukuSetoranPenerimaanController extends Controller
{


    // Cetak List
    public function cetakBukuSetoran(Request $request)
    {
        $tanggal_ttd    = $request->tgl_ttd;
        $pa_kpa         = $request->pa_kpa;
        $bendahara      = $request->bendahara;
        $tanggal1       = $request->tanggal1;
        $tanggal2       = $request->tanggal2;
        $enter          = $request->spasi;
        $tipe           = $request->tipe;
        $rekening       = $request->rekening;
        if ($tipe == 1) {
            $jenis = 'PENERIMAAN';
        } else {
            $jenis = 'PENYETORAN';
        }

        $cetak          = $request->cetak;
        $jenis_cetakan  = $request->jenis_cetakan;
        $tahun_anggaran = tahun_anggaran();

        if ($jenis_cetakan == 'skpd') {
            $kd_skpd        = $request->kd_skpd;
            $kd_org         = $request->kd_skpd;
        } else {
            $kd_org        =  substr($request->kd_skpd, 0, 17);
            $kd_skpd        =  $request->kd_skpd;
        }

        // TANDA TANGAN
        $cari_bendahara = DB::table('ms_ttd')
            ->select('nama', 'nip', 'jabatan', 'pangkat')
            ->where(['nip' => $bendahara, 'kode' => 'BP', 'kd_skpd' => $kd_skpd])
            ->first();
        $cari_pakpa = DB::table('ms_ttd')
            ->select('nama', 'nip', 'jabatan', 'pangkat')
            ->where(['nip' => $pa_kpa, 'kd_skpd' => $kd_skpd])
            ->whereIn('kode', ['PA', 'KPA'])
            ->first();

        // rincian
        $rincian = DB::select("SELECT * FROM (
                    select 1 nomor, b.no_sts, a.tgl_sts, a.keterangan, '' kd_rek6,
                    '' nm_rek6, b.rupiah as rupiah, '' no_terima, '' tgl_terima, '' sumber, '' nm_sumber
                    from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd
                    and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                    LEFT JOIN tr_terima c on b.kd_skpd=c.kd_skpd and b.kd_rek6=c.kd_rek6 and  b.kd_sub_kegiatan=c.kd_sub_kegiatan and b.no_terima=c.no_terima
                    where b.kd_skpd= ? and b.kd_rek6= ? AND a.jns_trans IN ('4','2') and a.tgl_sts BETWEEN  ?  AND  ?
                    group by b.no_sts, a.tgl_sts, a.keterangan, b.rupiah
                    UNION ALL
                    select 2 nomor, b.no_sts, a.tgl_sts, '' keterangan, b.kd_rek6,
                    (select nm_rek6 from ms_rek6 where kd_rek6=b.kd_rek6) nm_rek6, b.rupiah, b.no_terima, c.tgl_terima, b.sumber, (select nm_pengirim from ms_pengirim where kd_pengirim=b.sumber) nm_sumber
                    from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd
                    and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                    LEFT JOIN tr_terima c on b.kd_skpd=c.kd_skpd and b.kd_rek6=c.kd_rek6 and  b.kd_sub_kegiatan=c.kd_sub_kegiatan and b.no_terima=c.no_terima
                    where b.kd_skpd= ? and b.kd_rek6= ?   AND a.jns_trans IN ('4','2') and a.tgl_sts BETWEEN  ?  AND  ?

                    ) x order by tgl_sts, no_sts, nomor, kd_rek6", [$kd_skpd, $rekening, $tanggal1, $tanggal2, $kd_skpd, $rekening, $tanggal1, $tanggal2]);

        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
        $nm_skpd = cari_nama($kd_skpd, 'ms_skpd', 'kd_skpd', 'nm_skpd');

        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'skpd'              => DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'tanggal1'          => $tanggal1,
            'tanggal2'          => $tanggal2,
            'rincian'           => $rincian,
            'enter'             => $enter,
            'kd_skpd'           => $kd_skpd,
            'daerah'            => $daerah,
            'tanggal_ttd'       => $tanggal_ttd,
            'cari_pa_kpa'       => $cari_pakpa,
            'cari_bendahara'    => $cari_bendahara,
            'jenis'            => $jenis
        ];

        $view = view('skpd.laporan_bendahara_penerimaan.cetak.buku_terima_setor')->with($data);

        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setOrientation('landscape')->setPaper('a4');
            return $pdf->stream('BP KAS BANK.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="BP KAS BANK - ' . $nm_skpd . '.xls"');
            return $view;
        }
    }
}
