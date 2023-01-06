<?php

namespace App\Http\Controllers\Skpd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;

class LaporanBendaharaController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'bendahara' => DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd, 'kode' => 'BK'])->orderBy('nip')->orderBy('nama')->get(),
            'pa_kpa' => DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->orderBy('nip')->orderBy('nama')->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran()
        ];

        return view('skpd.laporan_bendahara.index')->with($data);
    }

    // get skpd by radio
    public function cariSkpd(Request $request)
    {
        $type       = Auth::user()->is_admin;
        $jenis      = $request->jenis;
        $kd_skpd    = $request->kd_skpd;
        $kd_org     = substr($kd_skpd, 0, 17);
        if ($type == '1') {
            if ($jenis == 'skpd') {
                $data   = DB::table('ms_organisasi')->select(DB::raw("kd_org AS kd_skpd"), DB::raw("nm_org AS nm_skpd"))->orderBy('kd_org')->get();
            } else {
                $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_skpd')->get();
            }
        } else {
            if ($jenis == 'skpd') {
                $data   = DB::table('ms_organisasi')->where(DB::raw("LEFT(kd_org)"), '=', $kd_org)->select(DB::raw("kd_org AS kd_skpd"), DB::raw("nm_org AS nm_skpd"))->get();
            } else {
                $data   = DB::table('ms_skpd')->where(DB::raw("LEFT(kd_skpd,17)"), '=', $kd_org)->select('kd_skpd', 'nm_skpd')->get();
            }
        }

        return response()->json($data);
    }

    // get bendahara pengeluaran
    function cariBendahara(Request $request)
    {
        if (strlen($request->kd_skpd) == '17') {
            $kd_skpd    = $request->kd_skpd . '.0000';
        } else {
            $kd_skpd    = $request->kd_skpd;
        }
        $data       = DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd, 'kode' => 'BK'])->orderBy('nip')->orderBy('nama')->get();
        return response()->json($data);
    }

    function cariPaKpa(Request $request)
    {
        if (strlen($request->kd_skpd) == '17') {
            $kd_skpd    = $request->kd_skpd . '.0000';
        } else {
            $kd_skpd    = $request->kd_skpd;
        }
        $data       = DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->orderBy('nip')->orderBy('nama')->get();
        return response()->json($data);
    }

    function cariSubkegiatan(Request $request)
    {
        $kd_skpd        = $request->kd_skpd;
        $jns_anggaran   = $request->jns_anggaran;
        $data           = DB::table('trskpd')->where(['kd_skpd' => $kd_skpd, 'jns_ang' => $jns_anggaran])->orderBy('kd_sub_kegiatan')->get();
        return response()->json($data);
    }
    function cariAkunBelanja(Request $request)
    {
        $kd_skpd        = $request->kd_skpd;
        $jns_anggaran   = $request->jns_anggaran;
        $subkegiatan    = $request->subkegiatan;
        $data           = DB::table('trdrka')->where(['kd_skpd' => $kd_skpd, 'jns_ang' => $jns_anggaran, 'kd_sub_kegiatan' => $subkegiatan])->orderBy('kd_rek6')->get();
        return response()->json($data);
    }


    // Cetak List
    public function cetakbku(Request $request)
    {
        $tanggal_ttd    = $request->tgl_ttd;
        $pa_kpa         = $request->pa_kpa;
        $bendahara      = $request->bendahara;
        $bulan          = $request->bulan;
        $enter          = $request->spasi;
        $kd_skpd        = $request->kd_skpd;
        $tahun_anggaran = tahun_anggaran();

        // TANDA TANGAN
        $cari_bendahara = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $bendahara, 'kode' => 'BK', 'kd_skpd' => $kd_skpd])->first();
        $cari_pakpa = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $pa_kpa, 'kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->first();

        // rekal
        $stmt      = DB::update("exec recall_skpd ?", array($kd_skpd));

        $data_tahun_lalu = DB::table('ms_skpd')
            ->select(DB::raw('isnull(sld_awal,0) AS nilai'), 'sld_awalpajak')
            ->where('kd_skpd', $kd_skpd)
            ->first();
        $data_sawal1 = DB::table('trhrekal as a')
            ->select(
                'kd_skpd',
                'tgl_kas',
                'tgl_kas AS tanggal',
                'no_kas',
                DB::raw("'' AS kegiatan"),
                DB::raw("'' AS rekening"),
                'uraian',
                DB::raw("'0' AS terima"),
                DB::raw("'0' AS keluar"),
                DB::raw("'' AS st"),
                'jns_trans'
            )
            ->where(DB::raw("month(tgl_kas)"), '<', $bulan)
            ->where(DB::raw("YEAR(tgl_kas)"), $tahun_anggaran)
            ->where('kd_skpd', $kd_skpd);

        $data_sawal2 = DB::table('trdrekal as a')
            ->leftjoin('trhrekal as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->where(DB::raw("month(b.tgl_kas)"), '<', $bulan)->where(DB::raw("YEAR(b.tgl_kas)"), $tahun_anggaran)
            ->where('b.kd_skpd', $kd_skpd)->select(
                'b.kd_skpd',
                'b.tgl_kas',
                DB::raw(" '' AS tanggal"),
                'a.no_kas',
                'a.kd_sub_kegiatan as kegiatan',
                'a.kd_rek6 AS rekening',
                'a.nm_rek6 AS uraian',
                DB::raw("CASE WHEN a.keluar + a.terima <0 THEN (a.keluar*-1) ELSE a.terima END as terima"),
                DB::raw("CASE WHEN a.keluar+a.terima<0 THEN (a.terima*-1) ELSE a.keluar END as keluar"),
                DB::raw("case when a.terima<>0 then '1' else '2' end AS st"),
                'b.jns_trans'
            )
            ->unionAll($data_sawal1)
            ->distinct();

        $result = DB::table(DB::raw("({$data_sawal2->toSql()}) AS sub"))
            ->select(DB::raw('SUM(terima) AS terima'), DB::raw('SUM(keluar) AS keluar'), DB::raw('SUM(terima) - SUM(keluar) AS sel'))
            ->mergeBindings($data_sawal2)
            ->first();


        // RINCIAN
        $rincian1 = DB::table('trhrekal as a')
            ->select(
                'kd_skpd',
                'tgl_kas',
                'tgl_kas AS tanggal',
                'no_kas',
                DB::raw("'' AS kegiatan"),
                DB::raw("'' AS rekening"),
                'uraian',
                DB::raw("'0' AS terima"),
                DB::raw("'0' AS keluar"),
                DB::raw("'' AS st"),
                'jns_trans'
            )
            ->where(DB::raw("month(tgl_kas)"), '=', $bulan)
            ->where(DB::raw("YEAR(tgl_kas)"), $tahun_anggaran)
            ->where('kd_skpd', $kd_skpd);

        $rincian2 = DB::table('trdrekal as a')
            ->leftjoin('trhrekal as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->where(DB::raw("month(b.tgl_kas)"), '=', $bulan)->where(DB::raw("YEAR(b.tgl_kas)"), $tahun_anggaran)
            ->where('b.kd_skpd', $kd_skpd)->select(
                'b.kd_skpd',
                'b.tgl_kas',
                DB::raw(" '' AS tanggal"),
                'a.no_kas',
                'a.kd_sub_kegiatan as kegiatan',
                'a.kd_rek6 AS rekening',
                'a.nm_rek6 AS uraian',
                DB::raw("CASE WHEN a.keluar + a.terima <0 THEN (a.keluar*-1) ELSE a.terima END as terima"),
                DB::raw("CASE WHEN a.keluar+a.terima<0 THEN (a.terima*-1) ELSE a.keluar END as keluar"),
                DB::raw("case when a.terima<>0 then '1' else '2' end AS st"),
                'b.jns_trans'
            )
            ->unionAll($rincian1)
            ->distinct();

        $result_rincian = DB::table(DB::raw("({$rincian2->toSql()}) AS sub"))
            ->orderBy('tgl_kas')
            ->orderBy(DB::raw("CAST(no_kas AS INT)"))
            ->orderBy('jns_trans')
            ->orderBy('st')
            ->orderBy('rekening')
            ->mergeBindings($rincian2)
            ->get();

        // SALDO TUNAI
        // DB::select('exec my_stored_procedure(?,?,..)',array($Param1,$param2));
        $tunai_lalu = DB::select("exec kas_tunai_lalu ?,?", array($kd_skpd, $bulan));
        $tunai      = DB::select("exec kas_tunai ?,?", array($kd_skpd, $bulan));


        $terima_lalu = 0;
        $keluar_lalu = 0;
        foreach ($tunai_lalu as $lalu) {
            $terima_lalu += $lalu->terima;
            $keluar_lalu += $lalu->keluar;
        }

        $terima = 0;
        $keluar = 0;
        foreach ($tunai as $sekarang) {
            $terima += $sekarang->terima;
            $keluar += $sekarang->keluar;
        }
        // KAS BANK
        $kas_bank = sisa_bank_by_bulan($bulan);

        // KAS SALDO BERHARGA

        $surat_berharga = DB::table('trhsp2d')
            ->select(DB::raw('isnull(sum(nilai),0) AS nilai'))
            ->where(DB::raw("month(tgl_terima)"), '=', $bulan)
            ->where(['kd_skpd' => $kd_skpd, 'status_terima' => '1'])
            ->where(function ($query) use ($bulan) {
                $query->where(DB::raw('month(tgl_kas)'), '>', $bulan)->orWhereNull('no_kas')->orWhere('no_kas', '');
            })
            ->first();

        // SALDO PAJAK
        // $saldo_pajak_1 = DB::table('trhtrmpot as a')
        //     ->select('b.kd_rek6', 'b.nm_rek6', 'a.kd_skpd', DB::raw("SUM(CASE WHEN MONTH(tgl_bukti)< $bulan THEN b.nilai ELSE 0 END) AS terima_lalu"), DB::raw("SUM(CASE WHEN MONTH(tgl_bukti)= $bulan THEN b.nilai ELSE 0 END) AS terima_ini"), DB::raw("SUM(CASE WHEN MONTH(tgl_bukti)<= $bulan THEN b.nilai ELSE 0 END) AS terima"), DB::raw("0 AS setor_lalu"), DB::raw("0 AS setor_ini"), DB::raw("0 AS setor"))
        //     ->join('trdtrmpot as b', function ($join) {
        //         $join->on('a.no_bukti', '=', 'b.no_bukti');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->where('a.kd_skpd', $kd_skpd)
        //     ->groupBy('b.kd_rek6', 'b.nm_rek6', 'a.kd_skpd');

        // $saldo_pajak_2 = DB::table('trhstrpot as a')
        //     ->select('b.kd_rek6', 'b.nm_rek6', 'a.kd_skpd', DB::raw("SUM(CASE WHEN MONTH(tgl_bukti)< $bulan THEN b.nilai ELSE 0 END) AS terima_lalu"), DB::raw("SUM(CASE WHEN MONTH(tgl_bukti)= $bulan THEN b.nilai ELSE 0 END) AS terima_ini"), DB::raw("SUM(CASE WHEN MONTH(tgl_bukti)<= $bulan THEN b.nilai ELSE 0 END) AS terima"), DB::raw("0 AS setor_lalu"), DB::raw("0 AS setor_ini"), DB::raw("0 AS setor"))
        //     ->join('trdstrpot as b', function ($join) {
        //         $join->on('a.no_bukti', '=', 'b.no_bukti');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->leftJoin('trhsp2d as c', function ($join) {
        //         $join->on('a.no_bukti', '=', 'c.no_sp2d');
        //         $join->on('a.kd_skpd', '=', 'c.kd_skpd');
        //     })
        //     ->where('a.kd_skpd', $kd_skpd)
        //     ->groupBy('b.kd_rek6', 'b.nm_rek6', 'a.kd_skpd')
        //     ->unionAll($saldo_pajak_1);

        // $saldo_pajak_3 = DB::table('ms_pot as a')
        //     ->select(DB::raw('RTRIM(a.map_pot) as kd_rek6'), 'a.nm_rek6')
        //     ->whereIn('a.kd_rek6', ['210106010001', '210105020001', '210105010001', '210105030001', '210109010001']);

        // $saldo_pajak1 = DB::table($saldo_pajak_3, 'a')
        //     ->leftJoinSub($saldo_pajak_2, 'b', function ($join) {
        //         $join->on('a.kd_rek6', '=', 'b.kd_rek6');
        //     })->distinct()->get();

        // $sisa_pajak             = 0;
        // foreach ($saldo_pajak1 as $pajak1) {
        //     $sisa_pajak             += $pajak1->terima_ini + $pajak1->terima_lalu - $pajak1->setor_lalu - $pajak1->setor_ini;
        // }

        $sisa_pajak = collect(DB::select("SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
            ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor,
            ISNULL(SUM(terima)-SUM(setor),0) as sisa
            FROM
            (SELECT RTRIM(map_pot) as kd_rek6, nm_rek6 nm_rek6 FROM ms_pot WHERE kd_rek6 IN ('210106010001','210105020001 ','210105010001 ','210105030001','210109010001'))a
            LEFT JOIN
            (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
            SUM(CASE WHEN MONTH(tgl_bukti)< ? THEN b.nilai ELSE 0 END) AS terima_lalu,
            SUM(CASE WHEN MONTH(tgl_bukti)= ? THEN b.nilai ELSE 0 END) AS terima_ini,
            SUM(CASE WHEN MONTH(tgl_bukti)<= ? THEN b.nilai ELSE 0 END) AS terima,
            0 as setor_lalu,
            0 as setor_ini,
            0 as setor
            FROM trhtrmpot a
            INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
            LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
            WHERE a.kd_skpd= ?
            GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd

            UNION ALL

            SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
            0 as terima_lalu,
            0 as terima_ini,
            0 as terima,
            SUM(CASE WHEN MONTH(tgl_bukti)< ? THEN b.nilai ELSE 0 END) AS setor_lalu,
            SUM(CASE WHEN MONTH(tgl_bukti)= ? THEN b.nilai ELSE 0 END) AS setor_ini,
            SUM(CASE WHEN MONTH(tgl_bukti)<= ? THEN b.nilai ELSE 0 END) AS setor
            FROM trhstrpot a
            INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
            LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
            WHERE a.kd_skpd= ?
            GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b ON a.kd_rek6=b.kd_rek6", [$bulan, $bulan, $bulan, $kd_skpd, $bulan, $bulan, $bulan, $kd_skpd]))->first();


        $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();


        // KIRIM KE VIEW
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'skpd'              => DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'bulan'             => $bulan,
            'data_sawal'        => $result,
            'data_rincian'      => $result_rincian,
            'data_tahun_lalu'   => $data_tahun_lalu,
            'tunai_lalu'        => $tunai_lalu,
            'tunai'             => $tunai,
            'terima_lalu'       => $terima_lalu,
            'keluar_lalu'       => $keluar_lalu,
            'terima'            => $terima,
            'keluar'            => $keluar,
            'saldo_bank'        => $kas_bank,
            'surat_berharga'    => $surat_berharga,
            'pajak'             => $sisa_pajak->sisa,
            'enter'             => $enter,
            'daerah'            => $daerah,
            'tanggal_ttd'       => $tanggal_ttd,
            'cari_pa_kpa'       => $cari_pakpa,
            'cari_bendahara'    => $cari_bendahara
        ];

        return view('skpd.laporan_bendahara.cetak.bku')->with($data);
    }
}
