<?php

namespace App\Http\Controllers\Akuntansi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;
use PhpParser\ErrorHandler\Collecting;
use PDF;
use Knp\Snappy\Pdf as SnappyPdf;

class LaporanAkuntansiController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'bendahara' => DB::table('ms_ttd')
                ->whereIn('kode', ['1'])
                ->orderBy('nip')
                ->orderBy('nama')
                ->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran()
        ];

        return view('akuntansi.index')->with($data);
    }

    public function konsolidasi()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'ttd' => DB::table('ms_ttd')
                ->whereIn('kode', ['1'])
                ->orderBy('nip')
                ->orderBy('nama')
                ->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran()
        ];

        return view('akuntansi.konsolidasi')->with($data);
    }

    public function lapkeu()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'ttd' => DB::table('ms_ttd')
                ->whereIn('kode', ['1'])
                ->orderBy('nip')
                ->orderBy('nama')
                ->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran()
        ];

        return view('akuntansi.lapkeu')->with($data);
    }

    public function perda()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'ttd' => DB::table('ms_ttd')
                ->whereIn('kode', ['1'])
                ->orderBy('nip')
                ->orderBy('nama')
                ->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran()
        ];

        return view('akuntansi.perda')->with($data);
    }

    public function perkada()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'ttd' => DB::table('ms_ttd')
                ->whereIn('kode', ['1'])
                ->orderBy('nip')
                ->orderBy('nama')
                ->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran()
        ];

        return view('akuntansi.perkada')->with($data);
    }

    // get skpd by radio
    public function cariSkpd(Request $request)
    {
        // $type       = Auth::user()->is_admin;
        // $jenis      = $request->jenis;
        //     // echo $jenis;
        //     // return;
        //     if ($jenis == 'skpd') {
        //         $data   = DB::table('ms_organisasi')->select(DB::raw("kd_org AS kd_skpd"), DB::raw("nm_org AS nm_skpd"))->orderBy('kd_org')->get();
        //     } else {
        //         $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_skpd')->get();
        //     }

        $type       = Auth::user()->is_admin;
        // $jenis      = $request->jenis;
        $jenis_skpd = substr(Auth::user()->kd_skpd, 18, 4);
        if ($jenis_skpd == '0000') {
            $jenis  = 'skpd';
        } else {
            $jenis  = 'unit';
        }
        $kd_skpd    = Auth::user()->kd_skpd;
        $kd_org     = substr($kd_skpd, 0, 17);
        if ($type == '1') {
            if ($jenis == 'skpd') {
                $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_org')->get();
            } else {
                $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_skpd')->get();
            }
        } else {
            if ($jenis == 'skpd') {
                // select kd_org AS kd_skpd, nm_org AS nm_skpd from [ms_skpd] where LEFT(kd_org) = 5.02.0.00.0.00.01)
                $data   = DB::table('ms_skpd')->where(DB::raw("LEFT(kd_skpd,17)"), '=', $kd_org)->select(DB::raw("kd_skpd AS kd_skpd"), DB::raw("nm_skpd AS nm_skpd"))->get();
            } else {
                $data   = DB::table('ms_skpd')->where(DB::raw("kd_skpd"), '=', $kd_skpd)->select('kd_skpd', 'nm_skpd')->get();
            }
        }
        // dd($kd_skpd);
        return response()->json($data);


        return response()->json($data);
    }

    public function cariSkpd2(Request $request)
    {
        $type       = Auth::user()->is_admin;
        // $jenis      = $request->jenis;
        $jenis_skpd = substr(Auth::user()->kd_skpd, 18, 4);
        if ($jenis_skpd == '0000') {
            $jenis  = 'skpd';
        } else {
            $jenis  = 'unit';
        }
        $kd_skpd    = Auth::user()->kd_skpd;
        $kd_org     = substr($kd_skpd, 0, 17);
        if ($type == '1') {
            if ($jenis == 'skpd') {
                $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_org')->get();
            } else {
                $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_skpd')->get();
            }
        } else {
            if ($jenis == 'skpd') {
                // select kd_org AS kd_skpd, nm_org AS nm_skpd from [ms_skpd] where LEFT(kd_org) = 5.02.0.00.0.00.01)
                $data   = DB::table('ms_skpd')->where(DB::raw("LEFT(kd_skpd,17)"), '=', $kd_org)->select(DB::raw("kd_skpd AS kd_skpd"), DB::raw("nm_skpd AS nm_skpd"))->get();
            } else {
                $data   = DB::table('ms_skpd')->where(DB::raw("kd_skpd"), '=', $kd_skpd)->select('kd_skpd', 'nm_skpd')->get();
            }
        }
        // dd($kd_skpd);
        return response()->json($data);
    }

    // get bendahara pengeluaran
    function cariTtd(Request $request)
    {
        $data       = DB::table('ms_ttd')
            ->whereIn('kode', ['1'])
            ->orderBy('nip')
            ->orderBy('nama')
            ->get();
        return response()->json($data);
    }

    function cariPaKpa(Request $request)
    {
        // dd($request->kd_skpd);
        if (strlen($request->kd_skpd) == '17') {
            $kd_skpd    = $request->kd_skpd . '.0000';
        } else {
            $kd_skpd    = $request->kd_skpd;
        }
        $data       = DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->orderBy('nip')->orderBy('nama')->get();
        return response()->json($data);
    }

    function ttd_kasubbid(Request $request)
    {

        $data       =
            DB::table('ms_ttd')
            ->where(['kd_skpd' => '5.02.0.00.0.00.02.0000', 'kode' => 'AKT'])
            ->orderBy('nip')
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    function cari_ttd_bud(Request $request)
    {

        $data       = DB::table('ms_ttd')->where(['kode' => 'bud'])->orderBy('nip')->orderBy('nama')->get();
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

    public function carirek6(Request $request)
    {

        $data           = DB::table('ms_rek6')
            ->orderBy('kd_rek6')->get();
        return response()->json($data);
    }

    public function carirek1(Request $request)
    {

        $data           = DB::table('ms_rek1')
            ->orderBy('kd_rek1')->get();
        return response()->json($data);
    }

    public function cariskpdbb(Request $request)
    {

        $data           = DB::table('ms_skpd')
            ->orderBy('kd_skpd')->get();
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
        $cetak          = $request->cetak;
        $tahun_anggaran = tahun_anggaran();

        // TANDA TANGAN
        $cari_bendahara = DB::table('ms_ttd')
            ->select('nama', 'nip', 'jabatan', 'pangkat')
            ->where(['nip' => $bendahara, 'kd_skpd' => $kd_skpd])
            ->whereIn('kode', ['BK', 'BPP'])
            ->first();
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


        $view =  view('skpd.laporan_bendahara.cetak.bku')->with($data);
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('laporan BKU.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="laporan BKU - ' . $nm_skpd . '.xls"');
            return $view;
        }
    }

    // Cetak List
    public function cetakbku13(Request $request)
    {
        $tanggal_ttd    = $request->tgl_ttd;
        $pa_kpa         = $request->pa_kpa;
        $bendahara      = $request->bendahara;
        $bulan          = $request->bulan;
        $enter          = $request->spasi;
        $kd_skpd        = $request->kd_skpd;
        $tahun_anggaran = '2022';

        // TANDA TANGAN
        $cari_bendahara = DB::table('ms_ttd')
            ->select('nama', 'nip', 'jabatan', 'pangkat')
            ->where(['nip' => $bendahara, 'kd_skpd' => $kd_skpd])
            ->whereIn('kode', ['BK', 'BPP'])
            ->first();
        $cari_pakpa = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $pa_kpa, 'kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->first();

        // rekal
        DB::update("exec recall_skpd ?", array($kd_skpd));

        $saldo_awal = collect(DB::select("SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (

                SELECT distinct z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) < ? AND
           year(a.tgl_kas) = ? and kd_skpd=?)
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian,
               CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
               CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
               case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) <'$bulan' AND
               year(a.tgl_kas) = ? and b.kd_skpd=?))z


             )z WHERE
             month(z.tgl_kas) < ? and year(z.tgl_kas) = ? AND z.kd_skpd = ?", [$bulan, $tahun_anggaran, $kd_skpd, $tahun_anggaran, $kd_skpd, $bulan, $tahun_anggaran, $kd_skpd]))->first();

        $saldo_awal_pajak = collect(DB::select("SELECT isnull(sld_awal,0) AS jumlah,sld_awalpajak FROM ms_skpd where kd_skpd=?", [$kd_skpd]))->first();


        $sisa_bank = collect(DB::select("SELECT terima-keluar as sisa FROM(select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (

                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan where (tunai<>1 OR tunai is null) union
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd=? and  d.pay='BANK' union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd=?
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            union all

            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout
            a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot
            from trspmpot group by no_spm) c on b.no_spm=c.no_spm
             left join
            (
            select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
            where e.kd_skpd=? and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
                ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd
              WHERE pay='BANK' and
             (panjar not in ('1') or panjar is null)

             union
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a
             join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd=? and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
      UNION
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union
      SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union

      SELECT a.tgl_kas AS tgl,a.no_panjar AS bku,a.keterangan as ket,a.nilai-isnull(b.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM tr_panjar a
            left join
            (
                select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
                where e.kd_skpd=? and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
             ) b on a.no_panjar=b.no_kas and a.kd_skpd=b.kd_skpd
            where a.pay='BANK' and a.kd_skpd=?
            union all
            select d.tgl_bukti, d.no_bukti,d.ket [ket],sum(e.nilai) [jumlah],'1' [jns],d.kd_skpd [kode] from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
            where e.kd_skpd=? and d.no_sp2d='2704/TU/2023' and d.pay='BANK' group by d.tgl_bukti,d.no_bukti,d.ket,d.kd_skpd
            union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan
            where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd=?
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd=?
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            ) a
      where month(tgl)<=? and kode=?) a", [$kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $bulan, $kd_skpd]))->first();

        $data_tunai_lalu = DB::update("exec kas_tunai_lalu ?,?", array($kd_skpd, $bulan));

        $data_tunai = DB::update("exec kas_tunai ?,?", array($kd_skpd, $bulan));

        $saldo_pajak = collect(DB::select("SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor,
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT RTRIM(map_pot) as kd_rek6, nm_rek6 nm_rek6 FROM ms_pot WHERE kd_rek6 IN ('210106010001','210105020001 ','210105010001 ','210105030001','210109010001'))a
        LEFT JOIN
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN MONTH(tgl_bukti)<? THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)=? THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<=? THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd=?
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN MONTH(tgl_bukti)<? THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)=? THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<=? THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd=?
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b ON a.kd_rek6=b.kd_rek6", [$bulan, $bulan, $bulan, $kd_skpd, $bulan, $bulan, $bulan, $kd_skpd]))->first();

        $saldo_berharga = collect(DB::select("SELECT sum(nilai) as total from trhsp2d where month(tgl_terima)=? and kd_skpd = ? and status_terima = '1' and (month(tgl_kas) > ? or no_kas is null or no_kas='')", [$bulan, $kd_skpd, $bulan]))->first();

        $data_bku = DB::select("SELECT * FROM ( SELECT  z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) = ? AND
           year(a.tgl_kas) = ? and kd_skpd=?)
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian,
			   CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
			   CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
			   case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) =? AND
               year(a.tgl_kas) = ? and b.kd_skpd=?))z ) OKE
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening", [$bulan, $tahun_anggaran, $kd_skpd, $bulan, $tahun_anggaran, $kd_skpd]);

        // KIRIM KE VIEW
        $data = [
            'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'skpd'              => DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'bulan'             => $bulan,
            'data_bku'          => $data_bku,
            // 'data_sawal'        => $result,
            // 'data_rincian'      => $result_rincian,
            // 'data_tahun_lalu'   => $data_tahun_lalu,
            // 'tunai_lalu'        => $tunai_lalu,
            // 'tunai'             => $tunai,
            // 'terima_lalu'       => $terima_lalu,
            // 'keluar_lalu'       => $keluar_lalu,
            // 'terima'            => $terima,
            // 'keluar'            => $keluar,
            // 'saldo_bank'        => $kas_bank,
            // 'surat_berharga'    => $surat_berharga,
            // 'pajak'             => $sisa_pajak->sisa,
            // 'enter'             => $enter,
            // 'daerah'            => $daerah,
            'tanggal_ttd'       => $tanggal_ttd,
            'cari_pa_kpa'       => $cari_pakpa,
            'cari_bendahara'    => $cari_bendahara
        ];

        return view('skpd.laporan_bendahara.cetak.bku13')->with($data);
    }

    public function cetak_bb(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $dcetak    = $request->tanggal1;
        $dcetak2    = $request->tanggal2;
        $cetak          = $request->cetak;
        $skpd        = $request->kd_skpd;
        $rek6          = $request->rek6;
        // $kd_skpd        = Auth::user()->kd_skpd;


        $thn_ang = tahun_anggaran();

        if ((substr($rek6, 0, 1) == '9') or (substr($rek6, 0, 1) == '8') or (substr($rek6, 0, 1) == '4') or (substr($rek6, 0, 1) == '5') or (substr($rek6, 0, 1) == '7')) {
            $csql3 = collect(DB::select("SELECT sum(a.debet) as debet,sum(a.kredit) as kredit FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' and b.tgl_voucher < '$dcetak'   AND YEAR(b.tgl_voucher)='$thn_ang'"))->first();
        } else if ($rek6 == '310101010001') {
            $csql3 = collect(DB::select("SELECT sum(a.debet) as debet,sum(a.kredit) as kredit from (

                    select sum(debet) debet, sum(kredit) kredit
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where kd_rek6='310101010001' and reev='0' and kd_skpd='$skpd' and tgl_voucher < '$dcetak'
                    union all
                    select sum(debet) debet, sum(kredit) kredit
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where kd_rek6='310101010001' and reev not in ('0') and kd_skpd='$skpd' and tgl_voucher < '$dcetak'
                    ) a "))->first();
        } else if ($rek6 == '310102010001') {
            $csql3 = collect(DB::select("SELECT sum(a.debet) as debet,sum(a.kredit) as kredit from (
                    select sum(debet) debet, sum(kredit) kredit
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,1) in ('7','8') and kd_skpd='$skpd' and tgl_voucher < '$dcetak'

                    ) a "))->first();
        } else {
            $csql3 = collect(DB::select("SELECT sum(a.debet) as debet,sum(a.kredit) as kredit FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' and b.tgl_voucher < '$dcetak'   "))->first();
        }


        $idx = 1;
        if ($rek6 == '310101010001') {
            $query = DB::select("SELECT kd_rek6, debet, kredit, tgl_voucher, ket, no_voucher FROM (
                                           SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='310101010001' AND b.kd_skpd='$skpd' AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2'
                                           ) a
                                           ORDER BY tgl_voucher, debet-kredit");
        } else if ($rek6 == '310102010001') {
            $query = DB::select("SELECT kd_rek6, debet, kredit, tgl_voucher, ket, no_voucher FROM (

                                           SELECT '310102010001' kd_rek6, SUM(a.debet) debet, SUM(a.kredit) kredit, b.tgl_voucher, 'SURPLUS/DEFISIT LO ('+b.ket+' )' ket, 'SURPLUS/DEFISIT LO - '+b.no_voucher as no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE LEFT(a.kd_rek6,1) IN ('7','8') AND b.kd_skpd='$skpd' AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2'
                                           GROUP BY b.tgl_voucher, b.no_voucher, b.ket) a
                                           ORDER BY tgl_voucher, debet-kredit");
        } else {
            $query = DB::select("SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2'  ORDER BY b.tgl_voucher,
                                           case when left('$rek6',1) in (1,5,6,9) then kredit-debet else debet-kredit end");
            //$query = $this->db->query("SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' and b.tgl_voucher>='$dcetak' and b.tgl_voucher<='$dcetak2' and a.pos='1' ORDER by b.tgl_voucher, convert(b.no_voucher,unsigned)");
        }


        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();



        // dd($query);


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();

        $data = [
            'header'    => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'csql3'     => $csql3,
            'query'     => $query,
            'daerah'    => $sc,
            'nogub'     => $nogub,
            'dcetak'    => $dcetak,
            'dcetak2'   => $dcetak2,
            'thn_ang'   => $thn_ang,
            'skpd'      => $skpd,
            'rek6'      => $rek6
        ];
        // if($format=='sap'){
        //     $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        // }elseif($format=='djpk'){
        //     $view =  view('akuntansi.cetakan.lra_djpk')->with($data);
        // }elseif($format=='p77'){
        //     $view =  view('akuntansi.cetakan.lra_77')->with($data);
        // }elseif($format=='sng'){
        $view =  view('akuntansi.cetakan.buku_besar')->with($data);
        // }

        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('BUKU BESAR.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="BUKU BESAR.xls"');
            return $view;
        }
    }

    public function cetak_ns(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tgl1    = $request->tanggal1_ns;
        $tgl2    = $request->tanggal2_ns;
        $bulan    = $request->bulan_ns;

        $cetak          = $request->cetak;
        $skpd        = $request->kd_skpd_ns;
        $rek1          = $request->rek1;
        $thn_ang = tahun_anggaran();
        $thn_ang1 = $thn_ang - 1;
        $thn_ang2 = $thn_ang1 - 1;
        $kd_skpd        = $request->kd_skpd_ns;
        // $kd_skpd        = Auth::user()->kd_skpd;
        if ($bulan == '') {
            $periode = "(tgl_voucher between $tgl1 and $tgl2) and ";
            $periode1 = "year (tgl_voucher)='$thn_ang1' and ";
            $nm_bln = tgl_format_oyoy($tgl1);
        } else {
            $modtahun = $thn_ang % 4;

            if ($modtahun = 0) {
                $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
            } else {
                $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
            }
            $arraybulan = explode(".", $nilaibulan);
            $nm_bln = $arraybulan[$bulan];
            if (strlen($bulan) == 1) {
                $bulan = "0" . $bulan;
            } else {
                $bulan = $bulan;
            }
            $periode = "left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and year (tgl_voucher)not in('$thn_ang1','$thn_ang2') and";
            $periode1 = "year (tgl_voucher)='$thn_ang1' and ";
        }
        // dd(strlen($bulan));

        if ($kd_skpd == '') {
            $kd_skpd        = Auth::user()->kd_skpd;
            $skpd_clause = "";
        } else {
            $kd_skpd        = $request->kd_skpd_ns;
            $skpd_clause = "and left(kd_skpd,len('$kd_skpd'))='$kd_skpd'";
        }

        // dd($kd_skpd);

        $query = DB::select("SELECT kd_rek, (SELECT nm_rek6 from ms_rek6 where kd_rek6=x.kd_rek)nm_rek, SaldoAwal,debet,kredit,

                                        SUM(case when left(kd_rek,1)='1' then SaldoAwal+debet-kredit
                                                 when left(kd_rek,1) in ('2','3') then SaldoAwal+kredit-debet
                                                 when left(kd_rek,1) in ('4','7') then kredit-debet
                                                 when left(kd_rek,1) in ('5','6','8') then debet-kredit else 0 end ) as saldoakhir
                         from

                                    (select kd_rek,
                                    SUM(case when left(kd_rek,1)='1' then debetaw-kreditaw
                                             when left(kd_rek,1) in ('2','3') then kreditaw-debetaw
                                             when left(kd_rek,1) in ('4','7') then kreditaw-debetaw
                                             when left(kd_rek,1) in ('5','6','8') then debetaw-kreditaw else 0 end ) as SaldoAwal,SUM(debet) AS debet,SUM(kredit) AS kredit,(SUM(debet)-SUM(kredit)) as saldoakhir
                                                                from (

                                    Select kd_skpd,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd) nm_skpd,
                                    kd_rek6 as kd_rek,SUM(b.debet) AS debet,SUM(b.kredit) AS kredit,0 as debetaw, 0 as kreditaw from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher
                                                      and b.kd_unit=a.kd_skpd where $periode
                                                        (left(kd_rek6,1) in ('$rek1')) $skpd_clause
                                            group by            kd_skpd,kd_rek6
                                            union
                                    Select kd_skpd,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd) nm_skpd,
                                    kd_rek6 as kd_rek,0 as debet, 0 as kredit,SUM(b.debet) AS debetaw,SUM(b.kredit) AS kreditaw from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher
                                                      and b.kd_unit=a.kd_skpd where $periode1
                                                        (left(kd_rek6,1) in ('$rek1')) $skpd_clause

                                            group by            kd_skpd,kd_rek6
                                  )a
                                        group by            kd_rek
                                        )x
                                        group by kd_rek,SaldoAwal,debet, kredit
                                        order by kd_rek");


        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();



        // dd($query);


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();

        $data = [
            'header'    => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'query'     => $query,
            'daerah'    => $sc,
            'nogub'     => $nogub,
            'dcetak'    => $tgl1,
            'dcetak2'   => $tgl2,
            'thn_ang'   => $thn_ang,
            'thn_ang1'   => $thn_ang1,
            'skpd'      => $skpd,
            'rek1'      => $rek1,
            'nm_bln'      => $nm_bln,
            'bulan'      => $bulan
        ];
        // if($format=='sap'){
        //     $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        // }elseif($format=='djpk'){
        //     $view =  view('akuntansi.cetakan.lra_djpk')->with($data);
        // }elseif($format=='p77'){
        //     $view =  view('akuntansi.cetakan.lra_77')->with($data);
        // }elseif($format=='sng'){
        $view =  view('akuntansi.cetakan.neraca_saldo')->with($data);
        // }

        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('NERACA SALDO.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="NERACA SALDO.xls"');
            return $view;
        }
    }

    public function cetak_ped(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tgl1    = $request->tanggal1_ped;
        $tgl2    = $request->tanggal2_ped;
        $ttd_bud    = $request->ttd_bud;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jns_ang;

        $thn_ang = tahun_anggaran();
        $thn_ang1 = $thn_ang - 1;
        $thn_ang2 = $thn_ang1 - 1;

        // dd($nm_bln);





        $map1 = collect(DB::select("SELECT kd_skpd, kd_sub_kegiatan from map_ped_dtu_oyoy where bagian='1'"))->first();
        $map2 = collect(DB::select("SELECT kd_skpd, kd_sub_kegiatan from map_ped_dtu_oyoy where bagian='2'"))->first();

        $ttd = collect(DB::select("SELECT nama ,nip,jabatan, pangkat FROM ms_ttd where (kode='bud' OR kode='GUB') and nip like '%$ttd_bud%'"))->first();


        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();



        // dd($query);


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();

        $data = [
            'header'    => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'map1'     => $map1,
            'map2'     => $map2,
            'daerah'    => $sc,
            'nogub'     => $nogub,
            'tgl1'    => $tgl1,
            'tgl2'   => $tgl2,
            'ttd_bud'   => $ttd_bud,
            'ttd'   => $ttd,
            'jns_ang'   => $jns_ang,
            'thn_ang'   => $thn_ang,
            'thn_ang1'   => $thn_ang1
        ];
        // if($format=='sap'){
        //     $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        // }elseif($format=='djpk'){
        //     $view =  view('akuntansi.cetakan.lra_djpk')->with($data);
        // }elseif($format=='p77'){
        //     $view =  view('akuntansi.cetakan.lra_77')->with($data);
        // }elseif($format=='sng'){
        $view =  view('akuntansi.cetakan.ped')->with($data);
        // }

        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('PED.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="PED.xls"');
            return $view;
        }
    }

    public function cetak_inflasi(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tgl1    = $request->tanggal1_inflasi;
        $tgl2    = $request->tanggal2_inflasi;
        $ttd_bud    = $request->ttd_bud;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jns_ang;

        $thn_ang = tahun_anggaran();
        $thn_ang1 = $thn_ang - 1;
        $thn_ang2 = $thn_ang1 - 1;

        // dd($nm_bln);




        $from1 = collect(DB::select("SELECT kd_skpd,kd_sub_kegiatan FROM map_inflasi_dtu_oyoy where skpdnya='3.29.3.30.3.31.01.0000'"))->first();
        $map1 = DB::select(" SELECT 1 as urut, '1' no, kd_skpd, nm_skpd,'' kd_sub_kegiatan, '' uraian,''kd_rek6,''nm_rek6, 0 anggaran, 0 realisasi from ms_skpd where kd_skpd in ('3.29.3.30.3.31.01.0000')
                            union all
                            select 2 as urut , '' no, kd_skpd,''nm_skpd, kd_sub_kegiatan, (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=z.kd_sub_kegiatan)as uraian,
                            kd_rek6,(select nm_rek6 from ms_rek6 where kd_rek6=z.kd_rek6 )nm_rek6,
                            sum(nilai) as anggaran,
                            (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan
                                and z.kd_rek6=a.kd_rek6
                                and b.tgl_bukti between '$tgl1' and '$tgl2')as realisasi
                            from trdrka z
                            where kd_skpd in ($from1->kd_skpd)
                            and kd_sub_kegiatan in($from1->kd_sub_kegiatan)
                            and  z.jns_ang='$jns_ang'
                            group by kd_skpd,nm_skpd,kd_sub_kegiatan,kd_rek6
                            order by kd_skpd,kd_sub_kegiatan,kd_rek6,urut
                ");
        $from2 = collect(DB::select("SELECT kd_skpd,kd_sub_kegiatan FROM map_inflasi_dtu_oyoy where skpdnya='2.09.0.00.0.00.01.0000'"))->first();
        $map2 = DB::select(" SELECT 1 as urut , '2' no, kd_skpd, nm_skpd,'' kd_sub_kegiatan, '' uraian,''kd_rek6,''nm_rek6, 0 anggaran, 0 realisasi from ms_skpd where kd_skpd in ('2.09.0.00.0.00.01.0000')
                            union all
                            select 2 as urut , '' no, kd_skpd,''nm_skpd, kd_sub_kegiatan, (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=z.kd_sub_kegiatan)as uraian,
                            kd_rek6,(select nm_rek6 from ms_rek6 where kd_rek6=z.kd_rek6 )nm_rek6,
                            sum(nilai) as anggaran,
                            (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan
                                and z.kd_rek6=a.kd_rek6
                                and b.tgl_bukti between '$tgl1' and '$tgl2')as realisasi
                            from trdrka z
                            where kd_skpd in ($from2->kd_skpd)
                            and kd_sub_kegiatan in($from2->kd_sub_kegiatan)
                            and  z.jns_ang='$jns_ang'
                            group by kd_skpd,nm_skpd,kd_sub_kegiatan,kd_rek6
                            order by kd_skpd,kd_sub_kegiatan,kd_rek6,urut
                ");
        $from3 = collect(DB::select("SELECT kd_skpd,kd_sub_kegiatan FROM map_inflasi_dtu_oyoy where skpdnya='3.25.0.00.0.00.01.0000'"))->first();
        $map3 = DB::select(" SELECT 1 as urut , '3' no, kd_skpd, nm_skpd,'' kd_sub_kegiatan, '' uraian,''kd_rek6,''nm_rek6, 0 anggaran, 0 realisasi from ms_skpd where kd_skpd in ($from3->kd_skpd)
                            union all
                            select 2 as urut , '' no, kd_skpd,''nm_skpd, kd_sub_kegiatan, (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=z.kd_sub_kegiatan)as uraian,
                            kd_rek6,(select nm_rek6 from ms_rek6 where kd_rek6=z.kd_rek6 )nm_rek6,
                            sum(nilai) as anggaran,
                            (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan
                                and z.kd_rek6=a.kd_rek6
                                and b.tgl_bukti between '$tgl1' and '$tgl2')as realisasi
                            from trdrka z
                            where kd_skpd in ($from3->kd_skpd)
                            and kd_sub_kegiatan in($from3->kd_sub_kegiatan)
                            and  z.jns_ang='$jns_ang'
                            group by kd_skpd,nm_skpd,kd_sub_kegiatan,kd_rek6
                            order by kd_skpd,kd_sub_kegiatan,kd_rek6,urut
                ");
        $from4 = collect(DB::select("SELECT kd_skpd,kd_sub_kegiatan FROM map_inflasi_dtu_oyoy where skpdnya='3.27.0.00.0.00.01.0000'"))->first();
        $map4 = DB::select(" SELECT 1 as urut , '4' no, kd_skpd, nm_skpd,'' kd_sub_kegiatan, '' uraian,''kd_rek6,''nm_rek6, 0 anggaran, 0 realisasi from ms_skpd where kd_skpd in ($from4->kd_skpd)
                            union all
                            select 2 as urut , '' no, kd_skpd,''nm_skpd, kd_sub_kegiatan, (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=z.kd_sub_kegiatan)as uraian,
                            kd_rek6,(select nm_rek6 from ms_rek6 where kd_rek6=z.kd_rek6 )nm_rek6,
                            sum(nilai) as anggaran,
                            (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan
                                and z.kd_rek6=a.kd_rek6
                                and b.tgl_bukti between '$tgl1' and '$tgl2')as realisasi
                            from trdrka z
                            where kd_skpd in ($from4->kd_skpd)
                            and kd_sub_kegiatan in($from4->kd_sub_kegiatan)
                            and  z.jns_ang='$jns_ang'
                            group by kd_skpd,nm_skpd,kd_sub_kegiatan,kd_rek6
                            order by kd_skpd,kd_sub_kegiatan,kd_rek6,urut
                ");

        $from5 = collect(DB::select("SELECT kd_skpd,kd_sub_kegiatan FROM map_inflasi_dtu_oyoy where skpdnya='3.27.0.00.0.00.01.0002'"))->first();
        $map5 = DB::select(" SELECT 1 as urut , '5' no, kd_skpd, nm_skpd,'' kd_sub_kegiatan, '' uraian,''kd_rek6,''nm_rek6, 0 anggaran, 0 realisasi from ms_skpd where kd_skpd in ($from5->kd_skpd)
                            union all
                            select 2 as urut , '' no, kd_skpd,''nm_skpd, kd_sub_kegiatan, (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=z.kd_sub_kegiatan)as uraian,
                            kd_rek6,(select nm_rek6 from ms_rek6 where kd_rek6=z.kd_rek6 )nm_rek6,
                            sum(nilai) as anggaran,
                            (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan
                                and z.kd_rek6=a.kd_rek6
                                and b.tgl_bukti between '$tgl1' and '$tgl2')as realisasi
                            from trdrka z
                            where kd_skpd in ($from5->kd_skpd)
                            and kd_sub_kegiatan in($from5->kd_sub_kegiatan)
                            and  z.jns_ang='$jns_ang'
                            group by kd_skpd,nm_skpd,kd_sub_kegiatan,kd_rek6
                            order by kd_skpd,kd_sub_kegiatan,kd_rek6,urut
                ");

        $from6 = collect(DB::select("SELECT kd_skpd,kd_sub_kegiatan FROM map_inflasi_dtu_oyoy where skpdnya='3.27.0.00.0.00.02.0000'"))->first();
        $map6 = DB::select(" SELECT 1 as urut , '6' no, kd_skpd, nm_skpd,'' kd_sub_kegiatan, '' uraian,''kd_rek6,''nm_rek6, 0 anggaran, 0 realisasi from ms_skpd where kd_skpd in ($from6->kd_skpd)
                            union all
                            select 2 as urut , '' no, kd_skpd,''nm_skpd, kd_sub_kegiatan, (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=z.kd_sub_kegiatan)as uraian,
                            kd_rek6,(select nm_rek6 from ms_rek6 where kd_rek6=z.kd_rek6 )nm_rek6,
                            sum(nilai) as anggaran,
                            (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan
                                and z.kd_rek6=a.kd_rek6
                                and b.tgl_bukti between '$tgl1' and '$tgl2')as realisasi
                            from trdrka z
                            where kd_skpd in ($from6->kd_skpd)
                            and kd_sub_kegiatan in($from6->kd_sub_kegiatan)
                            and  z.jns_ang='$jns_ang'
                            group by kd_skpd,nm_skpd,kd_sub_kegiatan,kd_rek6
                            order by kd_skpd,kd_sub_kegiatan,kd_rek6,urut
                ");

        $from7 = collect(DB::select("SELECT kd_skpd,kd_sub_kegiatan FROM map_inflasi_dtu_oyoy where skpdnya='2.15.0.00.0.00.01.0000'"))->first();
        $map7 = DB::select(" SELECT 1 as urut , '7' no, kd_skpd, nm_skpd,'' kd_sub_kegiatan, '' uraian,''kd_rek6,''nm_rek6, 0 anggaran, 0 realisasi from ms_skpd where kd_skpd in ($from7->kd_skpd)
                            union all
                            select 2 as urut , '' no, kd_skpd,''nm_skpd, kd_sub_kegiatan, (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=z.kd_sub_kegiatan)as uraian,
                            kd_rek6,(select nm_rek6 from ms_rek6 where kd_rek6=z.kd_rek6 )nm_rek6,
                            sum(nilai) as anggaran,
                            (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan
                                and z.kd_rek6=a.kd_rek6
                                and b.tgl_bukti between '$tgl1' and '$tgl2')as realisasi
                            from trdrka z
                            where kd_skpd in ($from7->kd_skpd)
                            and kd_sub_kegiatan in($from7->kd_sub_kegiatan)
                            and  z.jns_ang='$jns_ang'
                            group by kd_skpd,nm_skpd,kd_sub_kegiatan,kd_rek6
                            order by kd_skpd,kd_sub_kegiatan,kd_rek6,urut
                ");

        $from8 = collect(DB::select("SELECT kd_skpd,kd_sub_kegiatan FROM map_inflasi_dtu_oyoy where skpdnya='1.03.0.00.0.00.01.0000'"))->first();
        $map8 = DB::select(" SELECT 1 as urut , '8' no, kd_skpd, nm_skpd,'' kd_sub_kegiatan, '' uraian,''kd_rek6,''nm_rek6, 0 anggaran, 0 realisasi from ms_skpd where kd_skpd in ($from8->kd_skpd)
                            union all
                            select 2 as urut , '' no, kd_skpd,''nm_skpd, kd_sub_kegiatan, (select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=z.kd_sub_kegiatan)as uraian,
                            kd_rek6,(select nm_rek6 from ms_rek6 where kd_rek6=z.kd_rek6 )nm_rek6,
                            sum(nilai) as anggaran,
                            (select isnull(sum(nilai),0) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where z.kd_skpd=a.kd_skpd and z.kd_sub_kegiatan=a.kd_sub_kegiatan
                                and z.kd_rek6=a.kd_rek6
                                and b.tgl_bukti between '$tgl1' and '$tgl2')as realisasi
                            from trdrka z
                            where kd_skpd in ($from8->kd_skpd)
                            and kd_sub_kegiatan in($from8->kd_sub_kegiatan)
                            and  z.jns_ang='$jns_ang'
                            group by kd_skpd,nm_skpd,kd_sub_kegiatan,kd_rek6
                            order by kd_skpd,kd_sub_kegiatan,kd_rek6,urut
                ");

        $ttd = collect(DB::select("SELECT nama ,nip,jabatan, pangkat FROM ms_ttd where (kode='bud' OR kode='GUB') and nip like '%$ttd_bud%'"))->first();


        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();



        // dd($query);


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();

        $data = [
            'header'    => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'map1'     => $map1,
            'map2'     => $map2,
            'map3'     => $map3,
            'map4'     => $map4,
            'map5'     => $map5,
            'map6'     => $map6,
            'map7'     => $map7,
            'map8'     => $map8,
            'daerah'    => $sc,
            'nogub'     => $nogub,
            'tgl1'    => $tgl1,
            'tgl2'   => $tgl2,
            'ttd_bud'   => $ttd_bud,
            'ttd'   => $ttd,
            'jns_ang'   => $jns_ang,
            'thn_ang'   => $thn_ang,
            'thn_ang1'   => $thn_ang1
        ];
        // if($format=='sap'){
        //     $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        // }elseif($format=='djpk'){
        //     $view =  view('akuntansi.cetakan.lra_djpk')->with($data);
        // }elseif($format=='p77'){
        //     $view =  view('akuntansi.cetakan.lra_77')->with($data);
        // }elseif($format=='sng'){
        $view =  view('akuntansi.cetakan.inflasi')->with($data);
        // }

        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('PED.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="PED.xls"');
            return $view;
        }
    }

    public function cetak_rekonba(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $periode1    = $request->tanggal1;
        $periode2    = $request->tanggal2;
        $ttd    = $request->ttd;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jns_ang;
        $kd_skpd        = $request->kd_skpd;
        $skpdunit        = $request->skpdunit;
        $jenis_cetakan        = $request->jenis_cetakan;

        $thn_ang = tahun_anggaran();
        $thn_ang1 = $thn_ang - 1;
        $thn_ang2 = $thn_ang1 - 1;

        $arrayperiode1 = explode("-", $periode1);
        $arrayperiode2 = explode("-", $periode2);

        if ($arrayperiode2[1] <= '3') {
            $tw = "I";
        } else if ($arrayperiode2[1] <= '6') {
            $tw = "II";
        } else if ($arrayperiode2[1] <= '9') {
            $tw = "III";
        } else {
            $tw = "IV";
        }

        $bln2 = $arrayperiode2[1];

        $tgl_periode1 = substr($periode1, 7, 2);
        $bln_periode1 = substr($periode1, 5, 1);
        $thn_periode1 = substr($periode1, 0, 4);

        if ($jenis_cetakan == 1) {

            $kon = "";
            $real_pend_sp2d = "ISNULL(SUM(d.nilai), 0) AS nilai FROM trhsp2d a
                                   INNER JOIN trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm
                                   INNER JOIN trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp
                                   INNER JOIN trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp
                                   WHERE a.kd_skpd='$kd_skpd' AND status_terima='1' AND MONTH(tgl_terima)<='$bln2'  AND (c.sp2d_batal IS NULL  OR c.sp2d_batal !=1)";

            $real_spj = "sum(gj_ll)+sum(gj_ini)+sum(brg_ll)+sum(brg_ini)+sum(up_ll)+sum(up_ini) as nilai from(
                                SELECT a.kd_skpd
                                ,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ini
                                ,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ll
                                ,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ini
                                ,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ll
                                ,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (6) THEN a.nilai ELSE 0 END) AS brg_ini
                                ,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (6) THEN a.nilai ELSE 0 END) AS brg_ll
                                from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                                WHERE a.kd_skpd='$kd_skpd' GROUP BY a.kd_skpd
                                UNION ALL
                                SELECT b.kd_skpd, 0 as up_ini
                                ,SUM(CASE WHEN MONTH(b.TGL_BUKTI)<=$bln2 and b.pengurang_belanja=1 THEN a.nilai*-1 ELSE 0 END) AS up_ll
                                , 0 as gj_ini, 0 as gj_ll, 0 as brg_ini, 0 as brg_ll
                                FROM trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                                WHERE b.kd_skpd='$kd_skpd'
                                GROUP BY b.kd_skpd
                                UNION ALL
                                SELECT a.kd_skpd, 0 up_ini, 0 up_ll
                                ,SUM(CASE WHEN MONTH(b.tgl_sts)=$bln2 and b.jns_trans=5 and b.jns_cp in (1) THEN a.rupiah*-1 ELSE 0 END) AS gj_ini
                                ,SUM(CASE WHEN MONTH(b.tgl_sts)<$bln2 and b.jns_trans=5 and b.jns_cp in (1) THEN a.rupiah*-1 ELSE 0 END) AS gj_ll
                                ,SUM(CASE WHEN MONTH(b.tgl_sts)=$bln2 and b.jns_trans=5 and b.jns_cp in ('') THEN a.rupiah*-1 ELSE 0 END) AS brg_ini
                                ,SUM(CASE WHEN MONTH(b.tgl_sts)<$bln2 and b.jns_trans=5 and b.jns_cp in ('') THEN a.rupiah*-1 ELSE 0 END) AS brg_ll
                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                WHERE b.kd_skpd='$kd_skpd' and b.pot_khusus=1
                                GROUP BY a.kd_skpd
                                UNION ALL
                                SELECT a.kd_skpd, 0 up_ini, 0 up_ll
                                ,SUM(CASE WHEN MONTH(b.tgl_sts)=$bln2 and b.jns_trans=5 and b.jns_cp in ('') THEN a.rupiah*-1 ELSE 0 END) AS gj_ini
                                ,SUM(CASE WHEN MONTH(b.tgl_sts)<$bln2 and b.jns_trans=5 and b.jns_cp in ('') THEN a.rupiah*-1 ELSE 0 END) AS gj_ll
                                ,SUM(CASE WHEN MONTH(b.tgl_sts)=$bln2 and b.jns_trans=5 and b.jns_cp in (2) THEN a.rupiah*-1 ELSE 0 END) AS brg_ini
                                ,SUM(CASE WHEN MONTH(b.tgl_sts)<$bln2 and b.jns_trans=5 and b.jns_cp in (2) THEN a.rupiah*-1 ELSE 0 END) AS brg_ll
                                from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                                WHERE b.kd_skpd='$kd_skpd' and b.pot_khusus in (0,2)
                                GROUP BY a.kd_skpd
                                UNION ALL
                                SELECT a.kd_skpd
                                ,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ini
                                ,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ll
                                ,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ini
                                ,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ll
                                ,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (6,7) THEN a.nilai ELSE 0 END) AS brg_ini
                                ,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (6,7) THEN a.nilai ELSE 0 END) AS brg_ll
                                from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                                WHERE a.kd_skpd='$kd_skpd' and (kd_satdik<>'1' OR kd_satdik is not null) and left(a.kd_rek6,1)='5'  GROUP BY a.kd_skpd
                            )b GROUP BY kd_skpd ";

            $att = DB::select(" exec spj_skpd '$kd_skpd','$bln2','$jns_ang'");
            foreach ($att as $trh1) {
                $bre                =   $trh1->kd_rek;
                $wok                =   $trh1->uraian;
                $nilai              =   $trh1->anggaran;
                $real_up_ini        =   $trh1->up_ini;
                $real_up_ll         =   $trh1->up_lalu;
                $real_gaji_ini      =   $trh1->gaji_ini;
                $real_gaji_ll       =   $trh1->gaji_lalu;
                $real_brg_js_ini    =   $trh1->brg_ini;
                $real_brg_js_ll     =   $trh1->brg_lalu;
                $total  = $real_gaji_ll + $real_gaji_ini + $real_brg_js_ll + $real_brg_js_ini + $real_up_ll + $real_up_ini;
                $sisa   = $nilai - $real_gaji_ll - $real_gaji_ini - $real_brg_js_ll - $real_brg_js_ini - $real_up_ll - $real_up_ini;
            }
            $real_keluar_spj = $total;

            $tox_awal = collect(DB::select("SELECT CASE WHEN kd_bayar<>1 THEN isnull(sld_awal,0)+isnull(sld_awalpajak,0) ELSE 0 END AS jumlah ,sld_awalpajak
                        FROM ms_skpd where kd_skpd='$kd_skpd'"))->first();
            $tox = $tox_awal->jumlah;
            $pjk = $tox_awal->sld_awalpajak;

            $sql_kastunai = collect(DB::select("SELECT isnull(nilai,0) nilai from (
                                    SELECT x.terima+y.tox-x.keluar+z.terimakeluar as nilai FROM (
                                    --saldotunai_terimakeluar
                                    SELECT '1' kd,
                                        ISNULL(SUM(case when jns=1 then jumlah else 0 end ),0) AS terima,
                                        ISNULL(SUM(case when jns=2 then jumlah else 0 end),0) AS keluar
                                        FROM (
                                        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan
                                        UNION ALL
                                        SELECT '2022-01-01' AS tgl,'0' AS bku,'Saldo Awal' AS ket,sld_awal AS jumlah,'1' as jns ,kd_skpd AS kode FROM ms_skpd
                                        UNION ALL
                                        select f.tgl_kas as tgl,f.no_kas as bku,f.keterangan as ket, f.nilai as jumlah, '1' as jns,f.kd_skpd as kode from tr_jpanjar f join tr_panjar g on f.no_panjar_lalu=g.no_panjar and f.kd_skpd=g.kd_skpd where f.jns=2 and g.pay='TUNAI'
                                        UNION ALL
                                        select tgl_bukti [tgl],no_bukti [bku],ket [ket],nilai [jumlah],'1' [jns],kd_skpd [kode] from trhtrmpot a where kd_skpd='$kd_skpd' and pay='' and jns_spp in ('1','2','3')
                                        UNION ALL
                                        select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar where pay='TUNAI'
                                        UNION ALL
                                        select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                                        where jns_trans NOT IN ('4','2') and pot_khusus =0  and bank='TNK'
                                        GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                                        UNION ALL
                                        SELECT  a.tgl_bukti AS tgl, a.no_bukti AS bku, a.ket AS ket, SUM(z.nilai) - isnull(pot, 0) AS jumlah, '2' AS jns, a.kd_skpd AS kode FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
                                        LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
                                        LEFT JOIN (SELECT no_spm, SUM (nilai) pot FROM trspmpot GROUP BY no_spm) c ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') AND MONTH(a.tgl_bukti)<'$bln2' and a.kd_skpd='$kd_skpd' AND a.no_bukti NOT IN( select no_bukti from trhtransout where no_sp2d in (SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1) AND MONTH(tgl_bukti)<'$bln2' and  no_kas not in (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' AND MONTH(tgl_bukti)<'$bln2' GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1) and jns_spp in (4,5,6) and kd_skpd='$kd_skpd')
                                        GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
                                        UNION ALL
                                        SELECT  tgl_bukti AS tgl,no_bukti AS bku, ket AS ket,  isnull(total, 0) AS jumlah, '2' AS jns, kd_skpd AS kode
                                        from trhtransout WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') and no_sp2d in (SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1) AND MONTH(tgl_bukti)<'$bln2' and  no_kas not in (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' AND MONTH(tgl_bukti)<'$bln2' GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1) and jns_spp in (4,5,6) and kd_skpd='$kd_skpd'
                                        UNION ALL
                                        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain  WHERE pay='TUNAI'
                                        UNION ALL
                                        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2'
                                        UNION ALL
                                        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI'
                                        UNION ALL
                                        select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],a.nilai [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a
                                        where a.kd_skpd='$kd_skpd' and a.pay='' and jns_spp in ('1','2','3')
                                        UNION ALL
                                        select
                                        a.tgl_bukti [tgl],a.no_bukti [bku],a.ket_tujuan [ket],a.nilai [jumlah],'2' [jns],a.kd_skpd_sumber [kode]
                                        from tr_setorpelimpahan_tunai a
                                        WHERE kd_skpd_sumber = '$kd_skpd'
                                        UNION ALL
                                        select
                                        a.tgl_bukti [tgl],a.no_bukti [bku],a.ket_tujuan [ket],a.nilai [jumlah],'1' [jns],a.kd_skpd [kode]
                                        from tr_setorpelimpahan_tunai a
                                        WHERE kd_skpd = '$kd_skpd' and status_ambil='1'
                                        ) a where month(a.tgl)<'$bln2' and kode='$kd_skpd') x
                                    LEFT JOIN (
                                    --saldotunai_tox
                                    SELECT '1' kd, CASE WHEN kd_bayar<>1 THEN isnull(isnull(sld_awal,0)+sld_awalpajak,0) ELSE 0 END AS tox FROM ms_skpd where kd_skpd='$kd_skpd') y
                                    on x.kd=y.kd
                                    LEFT JOIN (
                                    --terimakeluar_tunai
                                    SELECT '1' kd, ISNULL(SUM(masuk-keluar),0) terimakeluar FROM (
                                    SELECT tgl_kas AS tgl, kd_skpd AS kode, nilai AS masuk,0 AS keluar FROM tr_ambilsimpanan
                                    UNION ALL
                                    select f.tgl_kas as tgl, f.kd_skpd as kode, f.nilai as masuk, 0 as keluar from tr_jpanjar f join tr_panjar g on f.no_panjar_lalu=g.no_panjar and f.kd_skpd=g.kd_skpd where f.jns=2 and g.pay='TUNAI'
                                    UNION ALL
                                    select tgl_bukti [tgl], kd_skpd [kode], nilai AS masuk,0 AS keluar from trhtrmpot a where kd_skpd='$kd_skpd' and pay='' and jns_spp in('1','2','3')
                                    UNION ALL
                                    select tgl_panjar as tgl, kd_skpd as kode, 0 as masuk,nilai as keluar from tr_panjar where pay='TUNAI'
                                    UNION ALL
                                    select a.tgl_sts as tgl, a.kd_skpd as kode, 0 as masuk,SUM(b.rupiah) as keluar from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd where jns_trans NOT IN ('4','2') and pot_khusus =0  and bank='TNK' GROUP BY a.tgl_sts,a.kd_skpd
                                    UNION ALL
                                    SELECT a.tgl_bukti AS tgl, a.kd_skpd AS kode, 0 AS masuk, SUM(z.nilai)-isnull(pot,0)  AS keluar FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
                                    LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
                                    LEFT JOIN (SELECT no_spm, SUM (nilai) pot   FROM trspmpot GROUP BY no_spm) c ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') AND MONTH(a.tgl_bukti)='$bln2' and a.kd_skpd='$kd_skpd'
                                    AND a.no_bukti NOT IN(select no_bukti from trhtransout where no_sp2d in (SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1) AND MONTH(tgl_bukti)='$bln2' and  no_kas not in (SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' AND MONTH(tgl_bukti)='$bln2' GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1) and jns_spp in (4,5,6) and kd_skpd='$kd_skpd')
                                    group by a.tgl_bukti,a.kd_skpd,pot
                                    UNION ALL
                                    select tgl_bukti AS tgl, kd_skpd AS kode, 0 AS masuk, ISNULL(total,0)  AS keluar from trhtransout WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') AND no_sp2d in (SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1) AND MONTH(tgl_bukti)='$bln2' and  no_kas not in(SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' AND MONTH(tgl_bukti)='$bln2' GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1) and jns_spp in (4,5,6) and kd_skpd='$kd_skpd'
                                    UNION ALL
                                    SELECT tgl_bukti AS tgl, kd_skpd AS kode, 0 as masuk,nilai AS keluar FROM trhoutlain WHERE pay='TUNAI'
                                    UNION ALL
                                    SELECT tgl_kas AS tgl, kd_skpd AS kode, 0 as masuk,nilai AS keluar FROM tr_setorsimpanan WHERE jenis ='2'
                                    UNION  ALL
                                    SELECT tgl_bukti AS tgl, kd_skpd AS kode, nilai as masuk,0 AS keluar FROM trhINlain WHERE pay='TUNAI'
                                    UNION ALL
                                    SELECT tgl_kas AS tgl,kd_skpd_sumber AS kode,0 as masuk,nilai AS keluar FROM tr_setorpelimpahan_tunai
                                    UNION ALL
                                    select
                                    tgl_bukti AS tgl, kd_skpd AS kode, nilai masuk,0  AS keluar
                                    from tr_setorpelimpahan_tunai
                                    WHERE kd_skpd = '$kd_skpd' and status_ambil='1'
                                    union all
                                    select a.tgl_bukti [tgl], a.kd_skpd [kode], 0 as masuk,nilai AS keluar from trhstrpot a where a.kd_skpd='$kd_skpd' and a.pay='' and jns_spp in ('1','2','3'))a
                                    where month(a.tgl)='$bln2' and kode='$kd_skpd') z
                                    on x.kd=z.kd ) r"))->first();
            $kastunai  = $sql_kastunai->nilai + $pjk;

            $sal_ll = collect(DB::select("SELECT CASE WHEN kd_bayar=1 THEN isnull(sld_awal,0)+sld_awalpajak ELSE 0 END AS sal_lalu  FROM ms_skpd where kd_skpd='$kd_skpd'"))->first();
            $sal_llu = $sal_ll->sal_lalu;

            $sql_hasil_bank = collect(DB::select("SELECT terima-keluar as sisa FROM(select SUM(case when jns=1 then jumlah else 0 end) AS terima,SUM(case when jns=2 then jumlah else 0 end) AS keluar
                from (
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan where (tunai<>1 OR tunai is null)
                  union
                  SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
                        select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on
                        c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$kd_skpd' and  d.pay='BANK' union all
                         select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a
                         join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                         where a.kd_skpd='$kd_skpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
                         union all
                        select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode
                        from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                        where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd'
                        GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  union all
                   SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout
                   a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot
                   from trspmpot group by no_spm) c on b.no_spm=c.no_spm
                         left join
                   (
                    select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
                    where e.kd_skpd='$kd_skpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
                   ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd
                          WHERE pay='BANK' and
                         (panjar not in ('1') or panjar is null)

                         union
                         select d.tgl_bukti, d.no_bukti,d.ket [ket],sum(e.nilai) [jumlah],'1' [jns],d.kd_skpd [kode] from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
                        where e.kd_skpd='$kd_skpd' and d.no_sp2d in ('2704/TU/2023' ,'8379/TU/2022','5250/TU/2022','8523/TU/2022','1182/TU/2022','1888/TU/2022','1886/TU/2022','5249/TU/2022','8380/TU/2022')

                        and d.pay='BANK' group by d.tgl_bukti,d.no_bukti,d.ket,d.kd_skpd
                         union
                         select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a
                         join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                         where a.kd_skpd='$kd_skpd' and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
                  UNION
                        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union
                  SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union
                  SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union

                        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union

                  SELECT a.tgl_kas AS tgl,a.no_panjar AS bku,a.keterangan as ket,a.nilai-isnull(b.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM tr_panjar a
                        left join
                        (
                            select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
                            where e.kd_skpd='$kd_skpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
                         ) b on a.no_panjar=b.no_kas and a.kd_skpd=b.kd_skpd
                        where a.pay='BANK' and a.kd_skpd='$kd_skpd'
                        union all
                        select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode
                        from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                        where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$kd_skpd'
                        GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd union all
                        select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode
                        from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                        where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd'
                        GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                        ) a
                  where month(tgl)<='$bln2' and kode='$kd_skpd') a"))->first();
            $saldobank  = $sql_hasil_bank->sisa + $sal_llu;

            $sql_pjk = collect(DB::select("SELECT ISNULL(SUM(nilai),0) nilai FROM (
                            SELECT ISNULL(SUM(b.nilai),0) AS nilai
                            FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                            WHERE MONTH(a.tgl_bukti)<='12' AND b.kd_skpd='$kd_skpd'
                            UNION ALL
                            SELECT ISNULL(SUM(b.nilai)*-1,0) AS nilai
                            FROM trhstrpot a INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                            WHERE MONTH(a.tgl_bukti)<='12' AND b.kd_skpd='$kd_skpd') z"))->first();
            $nil_pajak  = $sql_pjk->nilai;

            if ($bln2 < 12) {
                $uyhdtini = "ISNULL(SUM(nilai),0) nilai from (
                             select ISNULL(sld_awal,0)+ISNULL(sld_awalpajak,0) nilai from ms_skpd where KD_SKPD='$kd_skpd'
                             UNION ALL
                             select ISNULL(sum(nilai)*-1,0) nilai from TRHOUTLAIN where KD_SKPD='$kd_skpd' and tgl_bukti<='$periode2' and thnlalu='1' and jns_beban not in ('4','6')) x";
            } else {
                $uyhdtini = "ISNULL(SUM(nilai),0) nilai from (
                             select ISNULL(sld_awal,0)+ISNULL(sld_awalpajak,0) nilai from ms_skpd where KD_SKPD='$kd_skpd'
                             UNION ALL
                             select ISNULL(sum(nilai)*-1,0) nilai from TRHOUTLAIN where KD_SKPD='$kd_skpd' and tgl_bukti<='$periode2' and thnlalu='1' and jns_beban not in ('4','6')
                             UNION ALL
                             select $kastunai+$saldobank-$nil_pajak as nilai) x ";
            }

            $rek_ppn = "'210106010001'";
            $sql_terima_ppn = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_ppn)"))->first();
            $terima_ppn  = $sql_terima_ppn->nilai;
            $sql_keluar_ppn = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_ppn)"))->first();
            $keluar_ppn  = $sql_keluar_ppn->nilai;

            $rek_pph21 = "'210105010001'";
            $sql_terima_pph21 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph21)"))->first();
            $terima_pph21  = $sql_terima_pph21->nilai;

            $sql_keluar_pph21 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph21)"))->first();
            $keluar_pph21  = $sql_keluar_pph21->nilai;

            $rek_pph22 = "'210105020001'";
            $sql_terima_pph22 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph22)"))->first();
            $terima_pph22  = $sql_terima_pph22->nilai;

            $sql_keluar_pph22 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph22)"))->first();
            $keluar_pph22  = $sql_keluar_pph22->nilai;

            $rek_pph23 = "'210105030001'";
            $sql_terima_pph23 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph23)"))->first();
            $terima_pph23  = $sql_terima_pph23->nilai;

            $sql_keluar_pph23 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph23)"))->first();
            $keluar_pph23  = $sql_keluar_pph23->nilai;


            $rek_iwp = "'210108010001'";
            $sql_terima_iwp = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_iwp)"))->first();
            $terima_iwp  = $sql_terima_iwp->nilai;
            $sql_keluar_iwp = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_iwp)"))->first();
            $keluar_iwp  = $sql_keluar_iwp->nilai;

            $rek_taperum = "'210107010001'";
            $sql_terima_taperum = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_taperum)"))->first();
            $terima_taperum  = $sql_terima_taperum->nilai;
            $sql_keluar_taperum = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_taperum)"))->first();
            $keluar_taperum  = $sql_keluar_taperum->nilai;

            $rek_pph4 = "'210109010001'";
            $sql_terima_pph4 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph4)"))->first();
            $terima_pph4  = $sql_terima_pph4->nilai;
            $sql_keluar_pph4 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph4)"))->first();
            $keluar_pph4  = $sql_keluar_pph4->nilai;

            $rek_ppn2 = "'2111001'";
            $sql_terima_ppn2 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_ppn2)"))->first();
            $terima_ppn2  = $sql_terima_ppn2->nilai;
            $sql_keluar_ppn2 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_ppn2)"))->first();
            $keluar_ppn2  = $sql_keluar_ppn2->nilai;

            $rek_ppn3 = "'2111101'";
            $sql_terima_ppn3 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_ppn3)"))->first();
            $terima_ppn3  = $sql_terima_ppn3->nilai;
            $sql_keluar_ppn3 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_ppn3)"))->first();
            $keluar_ppn3  = $sql_keluar_ppn3->nilai;

            $rek_jkk = "'210103010001'";
            $sql_terima_jkk = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_jkk)"))->first();
            $terima_jkk  = $sql_terima_jkk->nilai;
            $sql_keluar_jkk = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_jkk)"))->first();
            $keluar_jkk  = $sql_keluar_jkk->nilai;

            $rek_jkm = "'210104010001'";
            $sql_terima_jkm = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_jkm)"))->first();
            $terima_jkm  = $sql_terima_jkm->nilai;
            $sql_keluar_jkm = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_jkm)"))->first();
            $keluar_jkm  = $sql_keluar_jkm->nilai;

            $rek_bpjs = "'210102010001'";
            $sql_terima_bpjs = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_bpjs)"))->first();
            $terima_bpjs  = $sql_terima_bpjs->nilai;
            $sql_keluar_bpjs = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_bpjs)"))->first();
            $keluar_bpjs  = $sql_keluar_bpjs->nilai;

            //kalau mau ditambah potongan penghasilan lainya komen di buka
            $sql_keluar_pot_penghaslain = collect(DB::select("SELECT ISNULL(SUM(a.rupiah), 0) nilai FROM trdkasin_pkd a
                    INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                               WHERE  a.kd_skpd = '$kd_skpd' AND b.tgl_sts BETWEEN '$periode1' AND '$periode2' AND jns_trans='5' and pot_khusus='2'"))->first();
            $keluar_pot_penghaslain  = $sql_keluar_pot_penghaslain->nilai;

            $sql_hkpg = collect(DB::select("SELECT
                        isnull(SUM(up_hkpg_lalu),0) +
                        isnull(SUM(up_hkpg_ini),0) +
                        isnull(SUM(ls_hkpg_lalu),0) +
                        isnull(SUM(ls_hkpg_ini),0) +
                        isnull(SUM(gj_hkpg_lalu),0) +
                        isnull(SUM(gj_hkpg_ini),0)  as nilai
                         FROM(
                    SELECT
                    SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$bln2' then a.rupiah else 0 end),0)) AS up_hkpg_lalu,
                    SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$bln2' then a.rupiah else 0 end),0)) AS up_hkpg_ini,
                    SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$bln2' then a.rupiah else 0 end),0)) AS ls_hkpg_lalu,
                    SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$bln2' then a.rupiah else 0 end),0)) AS ls_hkpg_ini,
                    SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$bln2' then a.rupiah else 0 end),0)) AS gj_hkpg_lalu,
                    SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$bln2' then a.rupiah else 0 end),0)) AS gj_hkpg_ini
                    FROM trdkasin_pkd a
                    INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_skpd = '$kd_skpd' AND jns_trans='5' AND LEFT(kd_rek6,1)<>4
                    )zz"))->first();
            $keluar_hkpg  = $sql_hkpg->nilai;

            $sql_cp = collect(DB::select("SELECT isnull(SUM (rupiah),0) AS nilai FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd
                      WHERE d.kd_skpd = '$kd_skpd' AND ((jns_trans = '5' AND pot_khusus = '0') OR jns_trans = '1') AND MONTH (tgl_sts) <= '$bln2'"))->first();
            $keluar_cp  = $sql_cp->nilai;

            $rek_denda = "'410411010001'";
            $sql_terima_denda = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_denda)"))->first();
            $terima_denda  = $sql_terima_denda->nilai;
            $sql_keluar_denda = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                               WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_denda)"))->first();
            $keluar_denda  = $sql_keluar_denda->nilai;

            // $sql_terima_lain = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
            //                    WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 NOT IN ($rek_ppn,$rek_pph21,$rek_pph22,$rek_pph23,$rek_iwp,$rek_taperum,$rek_pph4,$rek_ppn2,$rek_ppn3,$rek_jkk,$rek_jkm,$rek_bpjs,$rek_denda)"))->first();


            $sql_terima_lain = collect(DB::select("SELECT ISNULL(SUM(ISNULL(pot_lain,0)),0) nilai
                                             FROM(
                                            SELECT SUM(CASE WHEN b.jns_spp IN ('1','2','3','4','5','6') AND b.tgl_bukti BETWEEN '$periode1' AND '$periode2'
                                            AND a.kd_rek6 NOT IN
                                            ('210105010001','210106010001','210105020001','210105030001','210108010001',
                                            '210107010001','210109010001','210102010001','410411010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS pot_lain
                                            FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                                            WHERE a.kd_skpd='$kd_skpd' and left(a.kd_rek6,6)<>'210601'
                                            UNION ALL
                                            SELECT
                                            SUM(CASE WHEN a.jns_beban in ('1','4','5','6') AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2'
                                            THEN  a.nilai ELSE 0 END) AS pot_lain
                                            FROM TRHINLAIN a
                                            WHERE pengurang_belanja !='1'
                                            AND a.kd_skpd='$kd_skpd'
                                            ) a"))->first();
            $terima_lain  = $sql_terima_lain->nilai;

            // $sql_keluar_lain = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
            //                    WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 NOT IN ($rek_ppn,$rek_pph21,$rek_pph22,$rek_pph23,$rek_iwp,$rek_taperum,$rek_pph4,$rek_ppn2,$rek_ppn3,$rek_jkk,$rek_jkm,$rek_bpjs,$rek_denda)"))->first();

            $sql_keluar_lain = collect(DB::select("SELECT
                                            ISNULL(SUM(ISNULL(pot_lain,0)),0) nilai
                                             FROM(
                                            SELECT
                                            SUM(CASE WHEN b.jns_spp IN ('1','2','3','4','5','6') AND b.tgl_bukti BETWEEN '$periode1' AND '$periode2'
                                            AND a.kd_rek6 NOT IN
                                            ('210105010001','210106010001','210105020001','210105030001','210108010001',
                                            '210107010001','210109010001','210102010001','410411010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS pot_lain
                                            FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                                            WHERE a.kd_skpd='$kd_skpd' and left(a.kd_rek6,6)<>'210601'
                                            UNION ALL
                                            SELECT
                                            SUM(CASE WHEN a.jns_beban in ('1','4','5','6') AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2'
                                            THEN  a.nilai ELSE 0 END) AS pot_lain
                                            FROM TRHOUTLAIN a
                                            WHERE a.kd_skpd='$kd_skpd'
                                            ) a"))->first();
            $keluar_lain  = $sql_keluar_lain->nilai;



            $sqldropin = collect(DB::select("SELECT isnull(sum(sd_bln_ini),0) as sd_bln_ini from (
                    SELECT isnull(SUM(nilai),0) as sd_bln_ini from tr_setorpelimpahan_bank
                    WHERE kd_skpd = '$kd_skpd' AND (tgl_kas BETWEEN '$periode1' and '$periode2')
                    union ALL
                    SELECT isnull(SUM(nilai),0) as sd_bln_ini from tr_setorpelimpahan_tunai
                    WHERE kd_skpd = '$kd_skpd' AND (tgl_kas BETWEEN '$periode1' and '$periode2')
                    ) x"))->first();
            $totaldropin = $sqldropin->sd_bln_ini;
            $sqldropin = collect(DB::select("SELECT SUM(z.sd_bln_ini) sd_bln_ini from(
                            select
                            isnull(SUM(nilai),0) as sd_bln_ini
                            from tr_setorpelimpahan_bank
                            WHERE kd_skpd_sumber = '$kd_skpd' AND (tgl_kas BETWEEN '$periode1' and '$periode2')
                            UNION ALL
                            select
                            isnull(SUM(nilai),0) as sd_bln_ini
                            from tr_setorpelimpahan_tunai
                            WHERE kd_skpd_sumber = '$kd_skpd' AND (tgl_kas BETWEEN '$periode1' and '$periode2')
                            )z"))->first();
            $totaldropout = $sqldropin->sd_bln_ini;


            $sqlpanjarin = collect(DB::select("SELECT SUM(x.jar_sd_bln_ini) jar_sd_bln_ini FROM(
                SELECT isnull(SUM(nilai),0) as jar_sd_bln_ini from tr_jpanjar where jns=1 and kd_skpd = '$kd_skpd' and
                (tgl_kas BETWEEN '$periode1' and '$periode2')
                )x"))->first();
            $totalpanjarin = $sqlpanjarin->jar_sd_bln_ini;
            $sqlpanjarout = collect(DB::select("SELECT isnull(SUM(nilai),0) as jarout_sd_bln_ini from tr_panjar
                            WHERE kd_skpd= '$kd_skpd' and (tgl_kas BETWEEN '$periode1' and '$periode2') and jns='1'"))->first();
            $totalpanjarout = $sqlpanjarout->jarout_sd_bln_ini;

            //bos
            $sqlbosin = collect(DB::select("SELECT  isnull(SUM(x.bos_sd_bln_ini),0) nilai_bosin FROM(
                SELECT
                SUM(CASE WHEN tgl_spb<='$periode2' THEN b.nilai ELSE 0 END) as bos_sd_bln_ini
                from trhsp2b a inner join trdsp2b b on a.kd_skpd=b.kd_skpd and a.no_sp2b=b.no_sp2b
                INNER JOIN trspb c on a.no_sp2b=c.no_sp2b and a.kd_skpd=c.kd_skpd
                where a.kd_skpd='$kd_skpd'
                UNION
                -- SPB HIBAH
                SELECT
                SUM(CASE WHEN tgl_spb_hibah<='$periode2' THEN b.nilai ELSE 0 END) as bos_sd_bln_ini
                from trhspb_hibah_skpd a inner join trdspb_hibah_skpd b on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
                where a.kd_skpd='$kd_skpd')x"))->first();
            $bosin = $sqlbosin->nilai_bosin;
            $sqlbosout = collect(DB::select("SELECT isnull(SUM(CASE WHEN tgl_bukti<='$periode2' THEN a.nilai ELSE 0 END),0) as nilai_bosout
                        from trdtransout_blud a inner join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                        WHERE a.kd_skpd='$kd_skpd' and (kd_satdik<>'1' OR kd_satdik is not null)"))->first();
            $bosout = $sqlbosout->nilai_bosout;

            //blud
            $sqlbludin = collect(DB::select("SELECT * from (
                SELECT SUM(CASE WHEN tgl_sts<='$periode2' THEN b.rupiah ELSE 0 END) as nilai_bludin
                from trhkasin_blud a inner join trdkasin_blud b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                where a.kd_skpd='$kd_skpd' )x"))->first();
            $bludin = is_null($sqlbludin->nilai_bludin) ? 'null' : $sqlbludin->nilai_bludin;
            $sqlbludout = collect(DB::select("SELECT isnull(blud_sd_bln_ini,0) as nilai_bludout from (
                        SELECT SUM(CASE WHEN tgl_bukti<='$periode2' THEN a.nilai ELSE 0 END) as blud_sd_bln_ini
                            from trdtransout_blud a inner join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                            WHERE a.kd_skpd='$kd_skpd' and right(kd_rek6,6)='999999') x"))->first();
            $bludout = $sqlbludout->nilai_bludout;

            $setor_tlalu_blud = "";

            $sql = DB::select("SELECT 1 nomor,0 jns, 'Saldo BKU' AS nama, $kastunai+$saldobank nilai
                  UNION ALL
                  --Kas tunai
                  SELECT 1 nomor,1 jns,'- Kas Tunai' AS nama, $kastunai nilai
                  UNION ALL
                  --Saldo Bank
                  SELECT 1 nomor, 1 jns, '- Saldo Bank' AS nama, $saldobank nilai
                  UNION ALL
                  --realisasi penerimaan sp2d--
                  SELECT 2 nomor, 0 jns, 'Realisasi penerimaan SP2D' nama, $real_pend_sp2d
                  UNION ALL
                  -- Realisasi SPJ
                  SELECT 3 nomor, '0' jns, 'Realisasi Pengeluaran SPJ (LS+UP/GU/TU)' uraian, $real_spj
                  UNION ALL
                  --setro cp kalau harus masuk pot. penghasilan lainya pot kusus ditambah '2'--
                  SELECT 4 nomor, '0' jns, 'Setoran Tahun Ini' uraian, SUM (nilai_cp) nilai FROM
                      (
                      SELECT SUM (rupiah) AS nilai_cp FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd WHERE d.kd_skpd = '$kd_skpd' AND jns_trans = '5' AND pot_khusus IN ('1') AND MONTH (tgl_sts) <= '$bln2'
                      UNION ALL
                      SELECT SUM (rupiah) AS nilai_cp FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd
                      WHERE d.kd_skpd = '$kd_skpd' AND ((jns_trans = '5' AND pot_khusus = '0') OR jns_trans = '1') AND MONTH (tgl_sts) <= '$bln2'
                      UNION ALL
                      SELECT ISNULL(SUM(a.rupiah), 0) nilai_cp FROM trdkasin_pkd a
                    INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                               WHERE  a.kd_skpd = '$kd_skpd' AND b.tgl_sts BETWEEN '$periode1' AND '$periode2' AND jns_trans='5' and pot_khusus='2'
                      ) a
                  UNION ALL
                  --setor uyhd tahun lalu--
                  SELECT 5 nomor, '0' jns, 'Setoran UYHD Tahun Lalu' uraian, ISNULL(sum(nilai),0) nilai
                  from TRHOUTLAIN where KD_SKPD='$kd_skpd' and tgl_bukti<='$periode2' and jns_beban not in ('4','6','7')
                  UNION ALL
                  --lebih setor
                  SELECT 6 nomor, '0' jns, 'Lebih Setor' uraian, 0 nilai
                  UNION ALL
                  SELECT 7 nomor, '0' jns, 'Penerimaan' uraian, SUM (nilai) nilai FROM (
                       SELECT $terima_ppn+$terima_pph21+$terima_pph22+$terima_pph23+$terima_iwp+$terima_taperum+$terima_pph4+$terima_bpjs+$terima_denda+$terima_lain+$totaldropin+$totalpanjarin+$bosin as nilai
                    ) a

                UNION ALL
                SELECT 7 nomor, '1' jns, '-Potongan Pajak' uraian, 0 nilai
                UNION ALL
                ---penerimaan PPN--
                SELECT 7 nomor, '2' jns, 'a. PPn' uraian, $terima_ppn nilai
                UNION ALL
                ---penerimaan PPH21--
                SELECT 7 nomor, '2' jns, 'b. PPh 21' uraian, $terima_pph21 nilai
                UNION ALL
                ---penerimaan PPH22--
                SELECT 7 nomor, '2' jns, 'c. PPh22' uraian, $terima_pph22 nilai
                UNION ALL
                ---penerimaan PPH23--
                SELECT 7 nomor, '2' jns, 'c. PPh23' uraian, $terima_pph23 nilai
                UNION ALL
                ---penerimaan IWP--
                SELECT 7 nomor, '2' jns, '- Pot. IWP' uraian, $terima_iwp nilai
                UNION ALL
                ---penerimaan Taperum--
                SELECT 7 nomor, '2' jns, '- Pot. Taperum' uraian, $terima_taperum nilai
                UNION ALL
                ---penerimaan pph4--
                SELECT 7 nomor, '2' jns, '- Pot. PPh Pasal 4' uraian, $terima_pph4 nilai
                UNION ALL
                ---penerimaan ppnpn 2%--
                --SELECT 7 nomor, '2' jns, '- Pot. Iuran Wajib PPNPN 2%' uraian, $terima_ppn2 nilai
                --UNION ALL
                ---penerimaan ppnpn 3%--
                --SELECT 7 nomor, '2' jns, '- Pot. Iuran Wajib PPNPN 3%' uraian, $terima_ppn3 nilai
                --UNION ALL
                ---Iuran JKK--
                --SELECT 7 nomor, '2' jns, '- Pot. Iuran Wajib JKK' uraian, $terima_jkk nilai
                --UNION ALL
                ---Iuran JKM--
                --SELECT 7 nomor, '2' jns, '- Pot. Iuran Wajib JKM' uraian, $terima_jkm nilai
                --UNION ALL
                ---Pot. BPJS--
                SELECT 7 nomor, '2' jns, '- Pot.  Jaminan Kesehatan' uraian, $terima_bpjs nilai
                UNION ALL
                ---Denda Keterlambatan--
                SELECT 7 nomor, '2' jns, '- Denda Keterlambatan' uraian, $terima_denda nilai
                UNION ALL
                ---penerimaan lain--
                SELECT 7 nomor, '2' jns, '- Lain-lain' uraian, $terima_lain nilai
                UNION ALL
                SELECT 7 nomor, '2' jns, '- Dropping Dana' uraian, $totaldropin nilai
                UNION ALL
                SELECT 7 nomor, '2' jns, '- Panjar Dana' uraian, $totalpanjarin nilai
                UNION ALL
                SELECT 7 nomor, '2' jns, '- BOS' uraian, $bosin nilai
                UNION ALL
                SELECT 7 nomor, '2' jns, '- BLUD' uraian, $bludin nilai
                UNION ALL

                SELECT 8 nomor, '0' jns, 'Pengeluaran' uraian, SUM (nilai) nilai FROM ( SELECT $keluar_ppn+$keluar_pph21+$keluar_pph22+$keluar_pph23+$keluar_iwp+$keluar_taperum+$keluar_pph4+$keluar_bpjs+$keluar_pot_penghaslain+$keluar_hkpg+$keluar_cp+$keluar_denda+$keluar_lain+$totaldropout+$totalpanjarout+$bosout+$bludout as nilai
                    ) a

                UNION ALL

                SELECT 8 nomor, '1' jns, '-Potongan Pajak' uraian, 0 nilai

                UNION ALL
                --pengeluaran ppn--
                SELECT 8 nomor, '2' jns, 'a. PPn' uraian, $keluar_ppn nilai
                UNION ALL
                --pengeluaran pph21--
                SELECT 8 nomor, '2' jns, 'b. PPh 21' uraian, $keluar_pph21 nilai
                UNION ALL
                --pengeluaran pph22--
                SELECT 8 nomor, '2' jns, 'c. PPh 22' uraian, $keluar_pph22 nilai
                UNION ALL
                --pengeluaran pph23--
                SELECT 8 nomor, '2' jns, 'd. PPh 23' uraian, $keluar_pph23 nilai
                UNION ALL
                --pengeluaran iwp--
                SELECT 8 nomor, '2' jns, '- Pot. IWP' uraian, $keluar_iwp nilai
                UNION ALL
                --pengeluaran taperum--
                SELECT 8 nomor, '2' jns, '- Pot. Taperum' uraian, $keluar_taperum nilai
                UNION ALL
                --pengeluaran pphpas4--
                SELECT 8 nomor, '2' jns, '- Pot. PPh Pasal 4' uraian, $keluar_pph4 nilai
                UNION ALL
                --pengeluaran ppnpn 2%--
                --SELECT 8 nomor, '2' jns, '- Pot. Iuran Wajib PPNPN 2%' uraian, $keluar_ppn2 nilai
                --UNION ALL
                --pengeluaran ppnpn 3%--
                --SELECT 8 nomor, '2' jns, '- Pot. Iuran Wajib PPNPN 3%' uraian, $keluar_ppn3 nilai
                --UNION ALL
                --pengeluaran Pot. Iuran Wajib JKK--
                --SELECT 8 nomor, '2' jns, '- Pot. Iuran Wajib JKK' uraian, $keluar_jkk nilai
                --UNION ALL
                --pengeluaran Pot. Iuran Wajib JKM--
                --SELECT 8 nomor, '2' jns, '- Pot. Iuran Wajib JKM' uraian, $keluar_jkm nilai
                --UNION ALL
                --pengeluaran Pot. BPJS--
                SELECT 8 nomor, '2' jns, '- Pot.  Jaminan Kesehatan' uraian, $keluar_bpjs nilai
                UNION ALL
                --pengeluaran Pot. Penghasilan Lainnya--
                SELECT 8 nomor, '2' jns, '- Pot. Penghasilan Lainnya' uraian, $keluar_pot_penghaslain nilai
                UNION ALL
                --hkpg
                SELECT 8 nomor, '2' jns, '- HKPG' uraian, $keluar_hkpg nilai
                UNION ALL
                --contra pos
                SELECT 8 nomor, '2' jns, '- Contra Pos  ' uraian, $keluar_cp nilai
                UNION ALL
                --pengeluaran Denda Keterlambatan--
                SELECT 8 nomor, '2' jns, '- Denda Keterlambatan' uraian, $keluar_denda nilai
                UNION ALL
                --pengeluaran lain--
                SELECT 8 nomor, '2' jns, '- Lain-lain' uraian, $keluar_lain nilai
                UNION ALL
                SELECT 8 nomor, '2' jns, '- Dropping Dana' uraian, $totaldropout nilai
                UNION ALL
                SELECT 8 nomor, '2' jns, '- Panjar Dana' uraian, $totalpanjarout nilai
                UNION ALL
                SELECT 8 nomor, '2' jns, '- BOS' uraian, $bosout nilai
                UNION ALL
                SELECT 8 nomor, '2' jns, '- BLUD' uraian, $bludout nilai




                UNION ALL

                --Setoran Utang Pajak tahun lalu--
                SELECT 9 nomor, '0' jns, 'Setoran Utang Pajak Tahun Lalu' AS uraian,
                isnull(sum(nilai),0) as nilai from
                ( SELECT isnull(sum(nilai),0) as nilai FROM TRHOUTLAIN where jns_beban = '7' AND KD_SKPD='$kd_skpd'
                  AND tgl_bukti BETWEEN '$periode1' AND '$periode2'
                  $setor_tlalu_blud ) x



                UNION ALL

                --Setoran Utang Belanja tahun lalu--
                SELECT 10 nomor, '0' jns, 'Setoran Utang Belanja Tahun Lalu' AS uraian, ISNULL(SUM(nilai),0) nilai FROM
                    (
                    SELECT ISNULL(SUM(debet - kredit), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
                    AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek6, 6) IN ('210601','210602','210606','210607','210608','210609','210610','210614') AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND b.map_real in ('15','40')
                    UNION ALL
                    SELECT ISNULL(SUM(debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
                    AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek6, 6) IN ('210601','210602','210606','210607','210608','210609','210610','210614') AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND (b.kd_unit='' OR b.kd_unit IS NULL) AND b.tabel='1' AND reev in ('','0')
                    ) a

                UNION ALL
                SELECT 10 nomor, '1' jns, '- Belanja Pegawai' AS uraian, ISNULL(SUM(nilai),0) nilai FROM
                    (
                    SELECT ISNULL(SUM(debet - kredit), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
                    AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek6, 6) = '210601' AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND b.map_real in ('15','40')
                    UNION ALL
                    SELECT ISNULL(SUM(debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
                    AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek6, 6) = '210601' AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND (b.kd_unit='' OR b.kd_unit IS NULL) AND b.tabel='1' AND reev in ('','0')
                    ) a
                UNION ALL
                SELECT 10 nomor, '1' jns, '- Belanja Barang dan Jasa' AS uraian, ISNULL(SUM(nilai),0) nilai FROM
                    (
                    SELECT ISNULL(SUM(debet - kredit), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
                    AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek6, 6) = '210602' AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND b.map_real in ('15','40')
                    UNION ALL
                    SELECT ISNULL(SUM(debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
                    AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek6, 6) = '210602' AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND (b.kd_unit='' OR b.kd_unit IS NULL) AND b.tabel='1' AND reev in ('','0')
                    ) a
                UNION ALL
                SELECT 10 nomor, '1' jns, '- Belanja Modal' AS uraian, ISNULL(SUM(nilai),0) nilai FROM
                    (
                    SELECT ISNULL(SUM(debet - kredit), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
                    AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek6, 6) IN ('210606','210607','210608','210609','210610','210614') AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND b.map_real in ('15','40')
                    UNION ALL
                    SELECT ISNULL(SUM(debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
                    AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek6, 6) IN ('210606','210607','210608','210609','210610','210614') AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND (b.kd_unit='' OR b.kd_unit IS NULL) AND b.tabel='1' AND reev in ('','0')
                    ) a

                UNION ALL

                --pajak yang belum di setor--
                SELECT 11 nomor, '0' jns, 'Pajak yang belum disetor' AS uraian, ISNULL( SUM (terima_pajak - setor_pajak), 0 ) AS nilai
                FROM ( SELECT a.kd_skpd, SUM (b.nilai) AS terima_pajak, 0 setor_pajak
                       FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.kd_skpd = b.kd_skpd AND a.no_bukti = b.no_bukti WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' GROUP BY a.kd_skpd
                       UNION ALL
                       SELECT a.kd_skpd, 0 terima_pajak, SUM (b.nilai) AS setor_pajak
                       FROM trhstrpot a INNER JOIN trdstrpot b ON a.kd_skpd = b.kd_skpd AND a.no_bukti = b.no_bukti WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' GROUP BY a.kd_skpd
                    ) a

                UNION ALL

                --belanja yang belum dibayar--
                SELECT 12 nomor, '0' jns, 'Belanja yang belum dibayar ' AS uraian, SUM (nilai) nilai
                FROM ( SELECT ISNULL(SUM(kredit - debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher AND a.kd_unit = b.kd_skpd
                       WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek6, 6) in ('210601','210602','210606','210607','210608','210609','210610','210614') AND b.tgl_voucher <='$periode2'
                    ) a

                UNION ALL

                SELECT 12 nomor, '1' jns, '- Belanja Pegawai' AS uraian, ISNULL(SUM(kredit - debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
                AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek6, 6) = '210601' AND b.tgl_voucher <='$periode2'
                UNION ALL
                SELECT 12 nomor, '1' jns, '- Belanja Barang dan Jasa' AS uraian, ISNULL(SUM(kredit - debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
                AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek6, 6) = '210602' AND b.tgl_voucher <='$periode2'
                UNION ALL
                SELECT 12 nomor, '1' jns, '- Belanja Modal' AS uraian, ISNULL(SUM(kredit - debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
                AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek6, 6) IN ('210606','210607','210608','210609','210610','210614')
                AND b.tgl_voucher <='$periode2' ");
        } else if ($jenis_cetakan == 2) {
            $rek_pkb = "'410101010001','410101010002','410101010003','410101010004','410101020001','410101020002','410101020003','410101020004','410101030001','410101030002','410101030003','410101030004','410101040001','410101040002','410101040003','410101040004','410101050001','410101050002','410101050003','410101050004','410101060001','410101060002','410101060003','410101060004','410101070001','410101070002','410101070003','410101070004','410101080001','410101080002','410101080003','410101080004','410101090001','410101090002','410101090003','410101090004','410101100001','410101100002','410101100003','410101100004','410101110001','410101110002','410101110003','410101110004','410101140001','410101140002','410101140003','410101140004'";

            $rek_tgk_pkb = "'4110114'";

            $rek_pka = "'410101120001','410101120002','410101120003','410101120004','410101130001','410101130002','410101130003','410101130004'";
            $rek_bbnkb = "'410102010001','410102020001','410102030001','410102040001','410102050001','410102060001','410102070001','410102080001','410102090001','410102100001','410102110001','410102130001','410102140001'";
            $rek_bbnka = "'410102120001'";
            $rek_pbbkb = "'410103010001','410103020001','410103030001','410103040001'";
            $rek_rokok = "'410105010001'";
            $rek_papabt = "'410104010001'";

            $rek_ret_umum = "'410201010001','410201010002','410201010003','410201010004','410201010005','410201010006','410201020001','410201030001','410201030002','410201040001','410201050001','410201050002','410201050003','410201060001','410201070001','410201070002','410201070003','410201080001','410201080002','410201080003','410201080004','410201080005','410201090001','410201100001','410201100002','410201100003','410201110001','410201110002','410201120001','410201120002','410201120003','410201130001'";

            $rek_ret_jasa = "'410202010001','410202010002','410202010003','410202010004','410202010005','410202080001','410202110001','410202110002','410202110003','410202110004','410202090001','410202040001'";
            $rek_ret_izin = "'410203030001','410203040001','410203060001'";

            $rek_denda_pkb = "'410412010001','410412010002','410412010003','410412010004','410412010005','410412010006','410412010007','410412010008','410412010009','410412010010','410412010011','410412010012','410412010013','410412010014'";
            $rek_denda_pap = "'410412040001'";
            $rek_denda_bbnkb = "'410412020001','410412020002','410412020003','410412020004','410412020005','410412020006','410412020007','410412020008','410412020009','410412020010','410412020011','410412020012','410412020013','410412020014'";
            /*$rek_pendidikan = "'410201120001','410201120002','410201120003'";*/
            $rek_pendidikan = "''";
            $rek_pihak3 = "'430105010001'";
            $rek_penjualan = "'410401010001','410401020002','410401020005','410401030001'";
            $rek_laba = "'410302010001'";
            $rek_jagir = "'410405010001','410405020001','410405040001'";
            $rek_bunga = "'410407010001'";
            $rek_denda_terlambat = "'410411010001'";
            $rek_pengembalian = "'4141003','4141007','4141009','4141011'";
            $rek_bg_hasil_pjk = "'4210101','4210103','4210104','4210105','4210106'";
            $rek_bg_hasil_bknpjk = "'4210201','4210202','4210203','4210204','4210205'";
            $rek_dau = "'4220101'";
            $rek_dak = "'4230103','4230104','4230105','4230106','4230107','4230109','4230111'";
            $rek_daknf = "'4230201','4230202','4230203','4230206','4230207','4230209','4230210'";
            $rek_hibah = "'4310101'";
            $rek_penyesuaian = "'4340104'";
            $rek_ban_keu = "'4350201'";
            $rek_blud = "'410416010001'";


            $sql_terima_pkb = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(nilai), 0) AS nilai
                        FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                        AND kd_rek6 IN ($rek_pkb)
                        union all
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_pkb) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $terima_pkb  = $sql_terima_pkb->nilai;

            $sql_terima_tgk_pkb = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(nilai), 0) AS nilai
                        FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                        AND kd_rek6 IN ($rek_tgk_pkb)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_tgk_pkb) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $terima_tgk_pkb  = $sql_terima_tgk_pkb->nilai;

            $sql_terima_pka = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(nilai), 0) AS nilai
                        FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                        AND kd_rek6 IN ($rek_pka)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_pka) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $terima_pka  = $sql_terima_pka->nilai;

            $sql_terima_bbnkb = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(nilai), 0) AS nilai
                        FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                        AND kd_rek6 IN ($rek_bbnkb)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_bbnkb) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $terima_bbnkb  = $sql_terima_bbnkb->nilai;

            $sql_terima_bbnka = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(nilai), 0) AS nilai
                        FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                        AND kd_rek6 IN ($rek_bbnka)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_bbnka) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $terima_bbnka  = $sql_terima_bbnka->nilai;


            $sql_terima_pbbkb = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(nilai), 0) AS nilai
                        FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                        AND kd_rek6 IN ($rek_pbbkb)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_pbbkb) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $terima_pbbkb  = $sql_terima_pbbkb->nilai;

            $sql_terima_rokok = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(nilai), 0) AS nilai
                        FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                        AND kd_rek6 IN ($rek_rokok)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_rokok) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $terima_rokok  = $sql_terima_rokok->nilai;


            $sql_terima_papabt = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                            SELECT ISNULL(SUM(nilai), 0) AS nilai
                            FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                            AND kd_rek6 IN ($rek_papabt)
                            UNION ALL
                            select ISNULL(sum(b.rupiah*-1),0) nilai
                            FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                            WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_papabt) AND a.tgl_sts <= '$periode2'
                            AND a.jns_trans='3') z"))->first();
            $terima_papabt  = $sql_terima_papabt->nilai;


            $sql_terima_ret_umum = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                            SELECT ISNULL(SUM(nilai), 0) AS nilai
                            FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                            AND kd_rek6 IN ($rek_ret_umum)
                            UNION ALL
                            select ISNULL(sum(b.rupiah*-1),0) nilai
                            FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                            WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_ret_umum) AND a.tgl_sts <= '$periode2'
                            AND a.jns_trans='3')z"))->first();
            $terima_ret_umum  = $sql_terima_ret_umum->nilai;


            $sql_terima_ret_jasa = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                            SELECT ISNULL(SUM(nilai), 0) AS nilai
                            FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                            AND kd_rek6 IN ($rek_ret_jasa)
                            UNION ALL
                            select ISNULL(sum(b.rupiah*-1),0) nilai
                            FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                            WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_ret_jasa) AND a.tgl_sts <= '$periode2'
                            AND a.jns_trans='3') z"))->first();
            $terima_ret_jasa  = $sql_terima_ret_jasa->nilai;


            $sql_terima_ret_izin = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                            SELECT ISNULL(SUM(nilai), 0) AS nilai
                            FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                            AND kd_rek6 IN ($rek_ret_izin)
                            UNION ALL
                            select ISNULL(sum(b.rupiah*-1),0) nilai
                            FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                            WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_ret_izin) AND a.tgl_sts <= '$periode2'
                            AND a.jns_trans='3') z"))->first();
            $terima_ret_izin  = $sql_terima_ret_izin->nilai;


            $sql_terima_denda_pkb = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                            SELECT ISNULL(SUM(nilai), 0) AS nilai
                            FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                            AND kd_rek6 IN ($rek_denda_pkb)
                            UNION ALL
                            select ISNULL(sum(b.rupiah*-1),0) nilai
                            FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                            WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_denda_pkb) AND a.tgl_sts <= '$periode2'
                            AND a.jns_trans='3') z"))->first();
            $terima_denda_pkb  = $sql_terima_denda_pkb->nilai;

            $sql_terima_denda_pap = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                            SELECT ISNULL(SUM(nilai), 0) AS nilai
                            FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                            AND kd_rek6 IN ($rek_denda_pap)
                            UNION ALL
                            select ISNULL(sum(b.rupiah*-1),0) nilai
                            FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                            WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_denda_pap) AND a.tgl_sts <= '$periode2'
                            AND a.jns_trans='3') z"))->first();
            $terima_denda_pap  = $sql_terima_denda_pap->nilai;

            $sql_terima_denda_bbnkb = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                            SELECT ISNULL(SUM(nilai), 0) AS nilai
                            FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                            AND kd_rek6 IN ($rek_denda_bbnkb)
                            UNION ALL
                            select ISNULL(sum(b.rupiah*-1),0) nilai
                            FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                            WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_denda_bbnkb) AND a.tgl_sts <= '$periode2'
                            AND a.jns_trans='3') z"))->first();
            $terima_denda_bbnkb  = $sql_terima_denda_bbnkb->nilai;

            $sql_terima_pendidikan = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                            SELECT ISNULL(SUM(nilai), 0) AS nilai
                            FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                            AND kd_rek6 IN ($rek_pendidikan)
                            UNION ALL
                            select ISNULL(sum(b.rupiah*-1),0) nilai
                            FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                            WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_pendidikan) AND a.tgl_sts <= '$periode2'
                            AND a.jns_trans='3') z"))->first();
            $terima_pendidikan  = $sql_terima_pendidikan->nilai;

            $sql_terima_pihak3 = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                            SELECT ISNULL(SUM(nilai), 0) AS nilai
                            FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                            AND kd_rek6 IN ($rek_pihak3)
                            UNION ALL
                            select ISNULL(sum(b.rupiah*-1),0) nilai
                            FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                            WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_pihak3) AND a.tgl_sts <= '$periode2'
                            AND a.jns_trans='3') z"))->first();
            $terima_pihak3  = $sql_terima_pihak3->nilai;

            $sql_terima_penjualan = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                            SELECT ISNULL(SUM(nilai), 0) AS nilai
                            FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                            AND kd_rek6 IN ($rek_penjualan)
                            UNION ALL
                            select ISNULL(sum(b.rupiah*-1),0) nilai
                            FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                            WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_penjualan) AND a.tgl_sts <= '$periode2'
                            AND a.jns_trans='3') z"))->first();
            $terima_penjualan  = $sql_terima_penjualan->nilai;

            $sql_terima_blud = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                            SELECT ISNULL(SUM(nilai), 0) AS nilai
                            FROM tr_terima_blud WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2'
                            AND kd_rek5 IN ($rek_blud)
                            UNION ALL
                            select ISNULL(sum(b.rupiah*-1),0) nilai
                            FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                            WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_blud) AND a.tgl_sts <= '$periode2'
                            AND a.jns_trans='3') z"))->first();
            $terima_blud  = $sql_terima_blud->nilai;

            $sql_setor_pkb = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_pkb)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_pkb) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_pkb  = $sql_setor_pkb->nilai;

            $sql_setor_tgk_pkb = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_tgk_pkb)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_tgk_pkb) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_tgk_pkb  = $sql_setor_tgk_pkb->nilai;

            $sql_setor_pka = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_pka)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_pka) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_pka  = $sql_setor_pka->nilai;

            $sql_setor_bbnkb = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_bbnkb)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_bbnkb) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_bbnkb  = $sql_setor_bbnkb->nilai;

            $sql_setor_bbnka = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_bbnka)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_bbnka) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_bbnka  = $sql_setor_bbnka->nilai;

            $sql_setor_pbbkb = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_pbbkb)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_pbbkb) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_pbbkb  = $sql_setor_pbbkb->nilai;

            $sql_setor_rokok = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_rokok)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_rokok) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_rokok  = $sql_setor_rokok->nilai;

            $sql_setor_papabt = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_papabt)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_papabt) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_papabt  = $sql_setor_papabt->nilai;

            $sql_setor_ret_umum = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_ret_umum)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_ret_umum) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_ret_umum  = $sql_setor_ret_umum->nilai;

            $sql_setor_ret_jasa = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_ret_jasa)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_ret_jasa) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_ret_jasa  = $sql_setor_ret_jasa->nilai;

            $sql_setor_ret_izin = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_ret_izin)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_ret_izin) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_ret_izin  = $sql_setor_ret_izin->nilai;

            $sql_setor_denda_pkb = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_denda_pkb)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_denda_pkb) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_denda_pkb  = $sql_setor_denda_pkb->nilai;

            $sql_setor_denda_pap = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_denda_pap)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_denda_pap) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_denda_pap  = $sql_setor_denda_pap->nilai;

            $sql_setor_denda_bbnkb = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_denda_bbnkb)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_denda_bbnkb) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_denda_bbnkb  = $sql_setor_denda_bbnkb->nilai;

            $sql_setor_pendidikan = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_pendidikan)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_pendidikan) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_pendidikan  = $sql_setor_pendidikan->nilai;

            $sql_setor_pihak3 = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_pihak3)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_pihak3) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_pihak3  = $sql_setor_pihak3->nilai;

            $sql_setor_penjualan = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_penjualan)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_penjualan) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_penjualan  = $sql_setor_penjualan->nilai;

            $sql_setor_laba = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_laba)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_laba) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_laba  = $sql_setor_laba->nilai;

            $sql_setor_jagir = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_jagir)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_jagir) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_jagir  = $sql_setor_jagir->nilai;

            $sql_setor_bunga = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_bunga)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_bunga) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_bunga  = $sql_setor_bunga->nilai;

            $sql_setor_denda_terlambat = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_denda_terlambat)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_denda_terlambat) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_denda_terlambat  = $sql_setor_denda_terlambat->nilai;

            $sql_setor_pengembalian = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_pengembalian)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_pengembalian) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_pengembalian  = $sql_setor_pengembalian->nilai;

            $sql_setor_bg_hasil_pjk = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_bg_hasil_pjk)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_bg_hasil_pjk) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_bg_hasil_pjk  = $sql_setor_bg_hasil_pjk->nilai;

            $sql_setor_bg_hasil_bknpjk = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_bg_hasil_bknpjk)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_bg_hasil_bknpjk) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_bg_hasil_bknpjk  = $sql_setor_bg_hasil_bknpjk->nilai;

            $sql_setor_dau = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_dau)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_dau) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_dau  = $sql_setor_dau->nilai;

            $sql_setor_dak = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_dak)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_dak) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_dak  = $sql_setor_dak->nilai;

            // $sql_setor_daknf = collect(DB::select("SELECT ISNULL(sum(a.rupiah),0) as nilai from trdkasin_pkd a inner join trhkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts where a.kd_skpd = '$kd_skpd' and b.jns_trans in ('4','2') and left(a.kd_rek6,1)='4' AND b.tgl_sts <= '$periode2' AND a.kd_rek6 IN ($rek_daknf) "))->first();

            $sql_setor_daknf = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_daknf)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_daknf) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_daknf  = $sql_setor_daknf->nilai;

            $sql_setor_hibah = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_hibah)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_hibah) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_hibah  = $sql_setor_hibah->nilai;

            $sql_setor_penyesuaian = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_penyesuaian)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_penyesuaian) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_penyesuaian  = $sql_setor_penyesuaian->nilai;

            $sql_setor_ban_keu = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_ban_keu)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_ban_keu) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_ban_keu  = $sql_setor_ban_keu->nilai;

            $sql_setor_blud = collect(DB::select("SELECT SUM(nilai) nilai FROM (
                        SELECT ISNULL(SUM(b.rupiah), 0) AS nilai
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek6,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek6 IN ($rek_blud)
                        UNION ALL
                        select ISNULL(sum(b.rupiah*-1),0) nilai
                        FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
                        WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek6 IN ($rek_blud) AND a.tgl_sts <= '$periode2'
                        AND a.jns_trans='3') z"))->first();
            $setor_blud  = $sql_setor_blud->nilai;

            $Realisasi_Penerimaan = "SELECT 2 nomor, 0 jns,0 urut,'- Realisasi Penerimaan' nama,ISNULL(SUM(nilai), 0) AS nilai
                    FROM( SELECT $setor_pkb+$setor_tgk_pkb+$setor_pka+$setor_bbnkb+$setor_bbnka+$setor_pbbkb+$setor_rokok+$setor_papabt+$setor_ret_umum+$setor_ret_jasa+$setor_ret_izin+$setor_denda_pkb+$setor_denda_pap+$setor_denda_bbnkb+$setor_pendidikan+$setor_pihak3+$setor_penjualan+$setor_laba+$setor_jagir+$setor_bunga+$setor_denda_terlambat+$setor_pengembalian+$setor_bg_hasil_pjk+$setor_bg_hasil_bknpjk+$setor_dau+$setor_dak+$setor_daknf+$setor_hibah+$setor_penyesuaian+$setor_ban_keu nilai
                        ) a

                   UNION ALL
                    SELECT 2 nomor, 1 jns,1 urut,'- Pajak Kendaraan Bermotor' nama, $terima_pkb nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 2 urut,'- Bea Balik Nama Kendaraan Bermotor' nama, $terima_bbnkb nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 3 urut,'- Pajak Bahan Bakar Kendaraan Bermotor' nama, $terima_pbbkb nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 4 urut,'- Pajak Air Permukaan' nama, $terima_papabt nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 5 urut,'- Pajak Rokok' nama, $terima_rokok nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 6 urut,'- Retribusi Jasa Umum' nama, $terima_ret_umum nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 7 urut,'- Retribusi Jasa Usaha' nama, $terima_ret_jasa nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 8 urut,'- Retribusi Perizinan Tertentu' nama, $terima_ret_izin nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 9 urut,'- Pendapatan Denda Pajak Kendaraan Bermotor' nama, $terima_denda_pkb nilai
                       UNION ALL

                       SELECT 2 nomor, 1 jns, 10 urut,'- Pendapatan Denda Pajak Bea Balik Nama Kendaraan Bermotor' nama, $terima_denda_bbnkb nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 11 urut,'- Pendapatan dari Penyelenggaraan Pendidikan dan Pelatihan' nama, $terima_pendidikan nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 12 urut,'- Partisipasi Pihak Ketiga' nama, $terima_pihak3 nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 13 urut,'- Hasil Penjualan Aset Daerah Yang Tidak Dipisahkan' nama, $terima_penjualan nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 14 urut,'- Pendapatan BLUD' nama, $terima_blud nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 18 urut,'- Bagian Laba Atas Penyertaan Modal Pada Perusahaan Milik Daerah/BUMD' nama, $setor_laba nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 19 urut,'- Penerimaan Jasa Giro' nama, $setor_jagir nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 20 urut,'- Pendapatan Bunga' nama, $setor_bunga nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 21 urut,'- Pendapatan Denda Atas Keterlambatan Pelaksanaan Pekerjaan' nama, $setor_denda_terlambat nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 22 urut,'- Pendapatan Dari Pengembalian' nama, $setor_pengembalian nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 23 urut,'- Bagi Hasil Pajak' nama, $setor_bg_hasil_pjk nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 24 urut,'- Bagi Hasil Bukan Pajak/Sumber Daya Alam' nama, $setor_bg_hasil_bknpjk nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 25 urut,'- Dana Alokasi Umum' nama, $setor_dau nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 26 urut,'- Dana Alokasi Khusus Fisik' nama, $setor_dak nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 27 urut,'- Dana Alokasi Khusus Non Fisik' nama, $setor_daknf nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 28 urut,'- Pendapatan Hibah Dari Pemerintah' nama, $setor_hibah nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 29 urut,'- Dana Penyesuaian' nama, $setor_penyesuaian nilai
                   UNION ALL
                   SELECT 2 nomor, 1 jns, 30 urut,'- Bantuan Keuangan Dari Kabupaten' nama, $setor_ban_keu nilai";

            $Saldo_Penerimaan_Yang_Belum_Di_Setor = "SELECT 4 nomor, 0 jns, 0 urut, '- Saldo Penerimaan Yang Belum Di Setor' nama,  nilai_terima-nilai_setor as nilai
                        from (
                        select isnull(sum(a.rupiah),0) nilai_setor,(select isnull(sum(a.rupiah),0) nilai_setor from trdkasin_pkd a INNER JOIN trhkasin_pkd b
                        on a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
                        where a.kd_skpd='$kd_skpd' AND b.jns_trans='4' AND LEFT(a.kd_rek6,1)='4' AND b.tgl_sts <= '$periode2'
                        ) nilai_terima
                        from trdkasin_pkd a INNER JOIN trhkasin_pkd b
                        on a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
                        where a.kd_skpd='$kd_skpd' AND b.jns_trans='4' AND LEFT(a.kd_rek6,1)='4' AND b.tgl_sts <= '$periode2' ) a";

            $akuntansi = "SELECT 2 nomor, 0 jns,0 urut,'- Realisasi Penerimaan' nama,ISNULL(SUM(nilai), 0) AS nilai
                    FROM( SELECT $setor_pkb+$setor_tgk_pkb+$setor_pka+$setor_bbnkb+$setor_bbnka+$setor_pbbkb+$setor_rokok+$setor_papabt+$setor_ret_umum+$setor_ret_jasa+$setor_ret_izin+$setor_denda_pkb+$setor_denda_pap+$setor_denda_bbnkb+$setor_pendidikan+$setor_pihak3+$setor_penjualan+$setor_laba+$setor_jagir+$setor_bunga+$setor_denda_terlambat+$setor_pengembalian+$setor_bg_hasil_pjk+$setor_bg_hasil_bknpjk+$setor_dau+$setor_dak+$setor_daknf+$setor_hibah+$setor_penyesuaian+$setor_ban_keu nilai
                        ) a

                      UNION ALL
                       SELECT 2 nomor, 1 jns,1 urut,'- Pajak Kendaraan Bermotor' nama, $terima_pkb nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 2 urut,'- Bea Balik Nama Kendaraan Bermotor' nama, $terima_bbnkb nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 3 urut,'- Pajak Bahan Bakar Kendaraan Bermotor' nama, $terima_pbbkb nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 4 urut,'- Pajak Air Permukaan' nama, $terima_papabt nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 5 urut,'- Pajak Rokok' nama, $terima_rokok nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 6 urut,'- Retribusi Jasa Umum' nama, $terima_ret_umum nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 7 urut,'- Retribusi Jasa Usaha' nama, $terima_ret_jasa nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 8 urut,'- Retribusi Perizinan Tertentu' nama, $terima_ret_izin nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 9 urut,'- Pendapatan Denda Pajak Kendaraan Bermotor' nama, $terima_denda_pkb nilai
                       UNION ALL

                       SELECT 2 nomor, 1 jns, 10 urut,'- Pendapatan Denda Pajak Bea Balik Nama Kendaraan Bermotor' nama, $terima_denda_bbnkb nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 11 urut,'- Pendapatan dari Penyelenggaraan Pendidikan dan Pelatihan' nama, $terima_pendidikan nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 12 urut,'- Partisipasi Pihak Ketiga' nama, $terima_pihak3 nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 13 urut,'- Hasil Penjualan Aset Daerah Yang Tidak Dipisahkan' nama, $terima_penjualan nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 14 urut,'- Pendapatan BLUD' nama, $terima_blud nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 18 urut,'- Bagian Laba Atas Penyertaan Modal Pada Perusahaan Milik Daerah/BUMD' nama, $setor_laba nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 19 urut,'- Penerimaan Jasa Giro' nama, $setor_jagir nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 20 urut,'- Pendapatan Bunga' nama, $setor_bunga nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 21 urut,'- Pendapatan Denda Atas Keterlambatan Pelaksanaan Pekerjaan' nama, $setor_denda_terlambat nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 22 urut,'- Pendapatan Dari Pengembalian' nama, $setor_pengembalian nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 23 urut,'- Bagi Hasil Pajak' nama, $setor_bg_hasil_pjk nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 24 urut,'- Bagi Hasil Bukan Pajak/Sumber Daya Alam' nama, $setor_bg_hasil_bknpjk nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 25 urut,'- Dana Alokasi Umum' nama, $setor_dau nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 26 urut,'- Dana Alokasi Khusus Fisik' nama, $setor_dak nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 27 urut,'- Dana Alokasi Khusus Non Fisik' nama, $setor_daknf nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 28 urut,'- Pendapatan Hibah Dari Pemerintah' nama, $setor_hibah nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 29 urut,'- Dana Penyesuaian' nama, $setor_penyesuaian nilai
                       UNION ALL
                       SELECT 2 nomor, 1 jns, 30 urut,'- Bantuan Keuangan Dari Kabupaten' nama, $setor_ban_keu nilai";

            $sql = DB::select("SELECT n.kode, n.nomor, n.jns, n.nama, n.jns, n.nilai_unit, w.nilai_ak, (nilai_ak - nilai_unit) sisa
                FROM (SELECT ROW_NUMBER () OVER (ORDER BY b.nomor) AS kode,b.nomor, b.urut, b.nama,b.jns,b.nilai_unit
                FROM (
                    SELECT 8 nomor,0 jns,0 urut,'Saldo BKU' nama,SUM (CASE WHEN jns = 1 THEN a.nilai ELSE 0 END) AS nilai_unit
                        FROM( SELECT 8 nomor, 1 jns,1 urut, '- Kas Tunai' nama, 0 nilai
                              UNION ALL
                              SELECT 8 nomor, 1 jns,2 urut, '- Saldo Bank' nama, 0 nilai
                            ) a
                              -- UNION ALL
                              -- SELECT 8 nomor, 1 jns,1 urut, '- Kas Tunai' nama, 0 nilai
                              -- UNION ALL
                              -- SELECT 8 nomor, 1 jns,2 urut, '- Saldo Bank' nama, 0 nilai
                              UNION ALL

                       -- Kas di Bendahara Penerimaan
                       select 1 nomor,0 jns,0 urut,'- Kas di Bendahara Penerimaan' nama,sum(debet-kredit)  nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(a.kd_rek6,12)='110102010001' and a.kd_unit='$kd_skpd' AND b.tgl_voucher <= '$periode2'

                       UNION ALL
                         --TOTAL PENERIMAAN--
                         $Realisasi_Penerimaan

                       UNION ALL


                       -- Lebih Setor(Pendapatan dari Pengembalian Lain-lain)
                       SELECT 3 nomor, 0 jns,0 urut, '- Lebih Setor(Pendapatan dari Pengembalian Lain-lain)' nama, 0 nilai

                       UNION ALL

                       -- Saldo Penerimaan Yang Belum Di Setor
                       $Saldo_Penerimaan_Yang_Belum_Di_Setor

                       UNION ALL

                      --saldo awal penerimaan tahun lalu yang belum di setor
                      select 5 nomor,0 jns,0 urut,'- Saldo Awal Penerimaan Tahun Lalu Yang Belum Disetor' nama , isnull(sum(a.nilai_benpen-nilai_setor_uyhd),0) as nilai
                       from
                        (   select isnull(sum(debet-kredit),0)  nilai_benpen,
                       (select  isnull(sum(a.rupiah),0) nilai_setor_uyhd
                        from trdkasin_pkd a inner join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                         WHERE b.jns_trans='2' AND a.kd_skpd='$kd_skpd' AND b.tgl_sts <= '$periode2'
                        ) as nilai_setor_uyhd
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(a.kd_rek6,12)='110102010001' and a.kd_unit='$kd_skpd' AND year(b.tgl_voucher) < '$thn_ang'
                        ) a

                      UNION ALL
                      -- Saldo Awal Penerimaan Tahun Lalu
                       select 6 nomor,0 jns,0 urut,'- Saldo Awal Penerimaan Tahun Lalu' nama , isnull(sum(a.nilai),0) as nilai
                       from
                        (   select sum(debet-kredit)  nilai
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(a.kd_rek6,12)='110102010001' and a.kd_unit='$kd_skpd' AND year(b.tgl_voucher) < '$thn_ang'
                        ) a
                      UNION ALL

                      -- Setoran UYHD Penerimaan Tahun Lalu
                      select 7 nomor, 0 jns,0 urut, '- Setoran UYHD Penerimaan Tahun Lalu' nama , isnull(sum(a.rupiah),0) nilai from trdkasin_pkd a inner join trhkasin_pkd b
                      on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd WHERE b.jns_trans='2' AND a.kd_skpd='$kd_skpd' AND b.tgl_sts <= '$periode2'
                    )b
                )n

                inner join

                (select ROW_NUMBER() OVER (ORDER BY c.nomor) AS kode, urut, nilai_ak
                  from(
                    select 8 nomor, 0 jns, 0 urut,'Saldo BKU' nama, sum(case when jns=1 then a.nilai else 0 end) as nilai_ak
                        FROM ( select 8 nomor, 1 jns, 1 urut, '- Kas Tunai' nama, 0 nilai
                               UNION ALL
                               select 8 nomor, 1 jns, 2 urut,'- Saldo Bank' nama, 0 nilai
                              ) a
                        -- UNION ALL
                        -- select 8 nomor, 1 jns, 1 urut,'- Kas Tunai' nama, 0 nilai
                        -- UNION ALL
                        -- select 8 nomor, 1 jns, 2 urut,'- Saldo Bank' nama, 0 nilai
                        UNION ALL

                        $akuntansi

                         UNION ALL

                          -- Kas di Bendahara Penerimaan
                          select 1 nomor,0 jns,0 urut,'- Kas di Bendahara Penerimaan' nama, sum(0) nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(a.kd_rek6,5)='110102010001' and a.kd_unit='$kd_skpd' AND b.tgl_voucher <= '$periode2'

                          UNION ALL

                          -- Lebih Setor(Pendapatan dari Pengembalian Lain-lain)
                          select 3 nomor,0 jns,0 urut,'- Lebih Setor(Pendapatan dari Pengembalian Lain-lain)' nama, 0 nilai

                          UNION ALL

                          -- Saldo Penerimaan Yang Belum Di Setor
                           select 4 nomor, 0 jns, 0 urut, '- Saldo Penerimaan Yang Belum Di Setor' nama,  0 as nilai

                          UNION ALL

                          --saldo awal penerimaan tahun lalu yang belum di setor
                          select 5 nomor, 0 jns,0 urut, '- Saldo Awal Penerimaan Tahun Lalu Yang Belum Disetor' nama , isnull(sum(a.rupiah),0) nilai from trdkasin_pkd a inner join trhkasin_pkd b
                          on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd WHERE b.jns_trans='2' AND a.kd_skpd='$kd_skpd' AND b.tgl_sts <= '$periode2'

                          UNION ALL
                          -- Saldo Awal Penerimaan Tahun Lalu
                           select 6 nomor,0 jns,0 urut,'- Saldo Awal Penerimaan Tahun Lalu' nama , isnull(sum(a.nilai),0) as nilai
                           from
                            (   select sum(debet-kredit)  nilai
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where LEFT(a.kd_rek6,12)='110102010001' and a.kd_unit='$kd_skpd' AND year(b.tgl_voucher) < '$thn_ang'
                            ) a
                          UNION ALL

                          -- Setoran UYHD Penerimaan Tahun Lalu
                          select 7 nomor, 0 jns,0 urut, '- Setoran UYHD Penerimaan Tahun Lalu' nama , isnull(sum(a.rupiah),0) nilai from trdkasin_pkd a inner join trhkasin_pkd b
                          on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd WHERE b.jns_trans='2' AND a.kd_skpd='$kd_skpd' AND b.tgl_sts <= '$periode2'
                    )c
                )w
                on n.kode=w.kode and n.urut=w.urut
                WHERE nama<> 'saldo bku'
                order by n.kode");
        } else if ($jenis_cetakan == 3) {
            $sql = DB::select("SELECT a.seq,a.cetak,a.bold,a.parent,a.nor,a.uraian,isnull(a.kode_1,'-') as kode_1,isnull(a.kode_2,'-') as kode_2,isnull(a.kode_3,'-') as kode_3,thn_m1 AS lalu FROM map_lra_skpd a
                          ORDER BY a.seq");
        } else if ($jenis_cetakan == 4) {
            $sql = DB::select("SELECT seq,bold, nor, uraian, isnull(kode_1ja,'-') as kode_1ja, isnull(kode,'-') as kode, isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak
                , isnull(kurangi_1,'-') kurangi_1, isnull(kurangi,'-') kurangi, isnull(c_kurangi,0) as c_kurangi
                FROM map_lo_prov_permen_77_oyoy
                GROUP BY seq,bold, nor, uraian, isnull(kode_1ja,'-'), isnull(kode,'-'), isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet') ,
                isnull(kurangi_1,'-') , isnull(kurangi,'-') , isnull(c_kurangi,0)
                ORDER BY nor");
        } else if ($jenis_cetakan == 5) {
            $sql = DB::select("SELECT kode, uraian, seq,bold, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3,
                isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7,
                    isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
                    isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15, isnull(kecuali,'xxx') as kecuali
                FROM map_neraca_permen_77_oyoy ORDER BY seq");
        } else if ($jenis_cetakan == 6) {
            $ekuitas_awal = collect(DB::select("SELECT sum(nilai) nilai,sum(nilai_lalu) nilai_lalu
                        from(
                        --1 ekuitas_awal
                        select isnull(sum(nilai),0)nilai,0 nilai_lalu from data_ekuitas_lalu_tgl_oyoy('$periode1','$$periode2',$thn_ang,$thn_ang1) where left(kd_unit,len('$kd_skpd'))='$kd_skpd'
                        union all
                        --1 ekuitas lalu
                        select 0 nilai, isnull(sum(nilai),0)nilai_lalu from data_real_ekuitas_lalu_tgl_oyoy('$periode1','$periode2',$thn_ang,$thn_ang1) where left(kd_unit,len('$kd_skpd'))='$kd_skpd'
                        )a"))->first();
            // dd($ekuitas_awal);
            $surdef = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                            from(
                            --2 surplus lo
                            select sum(nilai_pen-nilai_bel) nilai,0 nilai_lalu
                            from(
                                select sum(kredit-debet) as nilai_pen,0 nilai_bel from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                where (tgl_voucher between '$periode1' and '$periode2') and left(kd_rek6,1) in ('7') and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'
                                union all
                                select 0 nilai_pen,sum(debet-kredit) as nilai_bel from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                where (tgl_voucher between '$periode1' and '$periode2') and left(kd_rek6,1) in ('8') and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'
                                )a
                                union all
                                -- 2 surplus lo lalu
                                select 0 nilai,isnull(sum(nilai_pen-nilai_bel),0) nilai_lalu
                                from(
                                select sum(kredit-debet) as nilai_pen,0 nilai_bel
                                from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                where year(tgl_voucher)=$thn_ang1 and left(kd_rek6,1) in ('7') and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'
                                union all
                                select 0 nilai_pen,sum(debet-kredit) as nilai_bel
                                from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                where year(tgl_voucher)=$thn_ang1 and left(kd_rek6,1) in ('8') and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'
                                )a
                            )a"))->first();

            $koreksi = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                            from(
                                --5 nilai lpe 1
                                select isnull(sum(kredit-debet),0) nilai , 0 nilai_lalu
                                from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                                where  reev='2' and kd_rek6='310101010001' and (tgl_voucher between '$periode1' and '$periode2') and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'
                                union all
                                --5 nilai lpe 1 lalu
                                select 0 nilai,isnull(sum(kredit-debet),0) nilai_lalu
                                from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                                where  reev='2' and kd_rek6='310101010001' and year(b.tgl_voucher)=$thn_ang1 and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'
                            )a"))->first();

            $selisih = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                            from(
                                --6 nilai dr
                                select isnull(sum(kredit-debet),0) nilai, 0 nilai_lalu
                                from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                                where  reev='1' and kd_rek6='310101010001' and (tgl_voucher between '$periode1' and '$periode2')
                                union all
                                --6 nilai dr lalu
                                select 0 nilai, isnull(sum(kredit-debet),0) nilai_lalu
                                from trhju a inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit
                                where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang1
                            )a"))->first();

            $lain = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                            from(
                                --7 nilai lpe2
                                select isnull(sum(kredit-debet),0) nilai, 0 nilai_lalu
                                from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                                where  reev='3' and kd_rek6='310101010001' and (tgl_voucher between '$periode1' and '$periode2') and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'
                                union all
                                --7 nilai lpe2 lalu
                                select 0 nilai,isnull(sum(kredit-debet),0) nilai_lalu
                                from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                                where  reev='3' and kd_rek6='310101010001' and year(tgl_voucher)=$thn_ang1 and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'
                            )a"))->first();
        }

        $ekuitas = collect(DB::select("SELECT sum(nilai)ekuitas from data_ekuitas_tgl_oyoy('$periode1','$periode2',$thn_ang,$thn_ang1) where left(kd_unit,len('$kd_skpd'))='$kd_skpd'"))->first();
        $ekuitas_tanpa_rkppkd = collect(DB::select("SELECT sum(nilai)ekuitas_tanpa_rkppkd from data_ekuitas_tanpa_rkppkd_tgl_oyoy('$periode1','$periode2',$thn_ang,$thn_ang1) where left(kd_unit,len('$kd_skpd'))='$kd_skpd'"))->first();
        $ekuitas_lalu = collect(DB::select("SELECT sum(nilai)ekuitas_lalu from data_ekuitas_lalu_tgl_oyoy('$periode1','$periode2',$thn_ang,$thn_ang1) where left(kd_unit,len('$kd_skpd'))='$kd_skpd'"))->first();

        $sus = collect(DB::select("SELECT
                    SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
                    SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus,
                    SUM(CASE WHEN kd_rek='4' THEN (kredit_awal-debet_awal) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet_awal-kredit_awal) ELSE 0 END) as nil_surplus_awal
                    FROM
                    (SELECT LEFT(kd_rek6,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet
                        ,SUM(kredit_awal) as kredit_awal,SUM(debet_awal) as debet_awal
                         FROM data_jurnal_n_sal_awal_tgl($periode1,$periode2,'$jns_ang') WHERE LEFT(kd_rek6,1) IN ('4','5') AND left(kd_skpd,len('$kd_skpd'))='$kd_skpd'
                    GROUP BY LEFT(kd_rek6,1)) a"))->first();
        // dd($sql);

        $ttdd = collect(DB::select("SELECT nama ,nip,jabatan, pangkat FROM ms_ttd where  nip like '%$ttd%'"))->first();


        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();



        // dd($sql);


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();
        if ($jenis_cetakan == 6) {
            $data = [
                'header'            => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
                // 'ekuitas_awal'      => $ekuitas_awal,
                'ekuitas_awal'      => $ekuitas_awal->nilai,
                'ekuitas_awal_lalu'      => $ekuitas_awal->nilai_lalu,
                'surdef'            => $surdef->nilai,
                'surdef_lalu'            => $surdef->nilai_lalu,
                'koreksi'           => $koreksi->nilai,
                'koreksi_lalu'           => $koreksi->nilai_lalu,
                'selisih'           => $selisih->nilai,
                'selisih_lalu'           => $selisih->nilai_lalu,
                'lain'              => $lain->nilai,
                'lain_lalu'              => $lain->nilai_lalu,
                'nogub'    => $nogub,
                'kd_skpd'    => $kd_skpd,
                'tw'    => $tw,
                'periode1'    => $periode1,
                'periode2'   => $periode2,
                'ttd'   => $ttdd,
                'jns_ang'   => $jns_ang,
                'thn_ang'   => $thn_ang,
                'thn_ang1'   => $thn_ang1,
                'tgl_periode1'   => $tgl_periode1,
                'bln_periode1'   => $bln_periode1
            ];
        } else {

            $data = [
                'header'    => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
                'sql'     => $sql,
                'sus'    => $sus,
                'ekuitas'     => $ekuitas,
                'ekuitas_tanpa_rkppkd'    => $ekuitas_tanpa_rkppkd,
                'ekuitas_lalu'    => $ekuitas_lalu,
                'nogub'    => $nogub,
                'kd_skpd'    => $kd_skpd,
                'tw'    => $tw,
                'periode1'    => $periode1,
                'periode2'   => $periode2,
                'ttd'   => $ttdd,
                'jns_ang'   => $jns_ang,
                'thn_ang'   => $thn_ang,
                'thn_ang1'   => $thn_ang1,
                'tgl_periode1'   => $tgl_periode1,
                'bln_periode1'   => $bln_periode1
            ];
        }
        if ($jenis_cetakan == 1) {
            $view =  view('akuntansi.cetakan.rekonba.pengeluaran')->with($data);
        } elseif ($jenis_cetakan == 2) {
            $view =  view('akuntansi.cetakan.rekonba.penerimaan')->with($data);
        } elseif ($jenis_cetakan == 3) {
            $view =  view('akuntansi.cetakan.rekonba.lra')->with($data);
        } elseif ($jenis_cetakan == 4) {
            $view =  view('akuntansi.cetakan.rekonba.lo')->with($data);
        } elseif ($jenis_cetakan == 5) {
            $view =  view('akuntansi.cetakan.rekonba.neraca')->with($data);
        } elseif ($jenis_cetakan == 6) {
            $view =  view('akuntansi.cetakan.rekonba.lpe')->with($data);
        }
        // elseif($format=='sng'){
        //     $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        // }

        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('PED.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="PED.xls"');
            return $view;
        }
    }

    public function cetak_ju(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $dcetak    = $request->tanggal1;
        $dcetak2    = $request->tanggal2;
        $cetak          = $request->cetak;
        $skpd        = $request->kd_skpd;
        // $kd_skpd        = Auth::user()->kd_skpd;


        $thn_ang = tahun_anggaran();


        $trh = collect(DB::select("SELECT count(*) as tot FROM
                 trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher  AND b.kd_skpd=a.kd_unit
                 where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd'"))->first();

        $query = DB::select("SELECT b.tgl_voucher,a.no_voucher,a.kd_rek6,a.nm_rek6 AS nm_rek6,a.debet,a.kredit FROM
                  trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher AND b.kd_skpd=a.kd_unit
                  where b.tgl_voucher between '$dcetak' and  '$dcetak2' and b.kd_skpd = '$skpd'
                  ORDER BY b.tgl_voucher,a.no_voucher,a.urut,a.rk,a.kd_rek6");




        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();



        // dd($query);


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();

        $data = [
            'header'    => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'trh'     => $trh,
            'query'     => $query,
            'daerah'    => $sc,
            'nogub'     => $nogub,
            'dcetak'    => $dcetak,
            'dcetak2'   => $dcetak2,
            'thn_ang'   => $thn_ang,
            'skpd'      => $skpd
        ];
        // if($format=='sap'){
        //     $view =  view('akuntansi.cetakan.lra_semester')->with($data);
        // }elseif($format=='djpk'){
        //     $view =  view('akuntansi.cetakan.lra_djpk')->with($data);
        // }elseif($format=='p77'){
        //     $view =  view('akuntansi.cetakan.lra_77')->with($data);
        // }elseif($format=='sng'){
        $view =  view('akuntansi.cetakan.jumum')->with($data);
        // }

        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('JURNAL UMUM.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="JURNAL UMUM.xls"');
            return $view;
        }
    }

    public function cetak_rekap_sisa_kas(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $bulan    = $request->bulan;
        $anggaran    = $request->anggaran;
        $cetak          = $request->cetak;
        $jenis        = $request->jenis;
        // $kd_skpd        = Auth::user()->kd_skpd;


        $thn_ang = tahun_anggaran();

        if ($jenis == '1') {

            $query = DB::select("SELECT * FROM rekap_sisa_kas_pengeluaran_new ($bulan,'$anggaran',$thn_ang) order by kd_skpd");
            $query_jum = DB::select("SELECT
                                    sum(anggaran) total_ang,
                                    sum(sp2d) total_sp2d,
                                    sum(spj) total_spj,
                                    sum(sisa_kas) total_sisa_kas,
                                    sum(cp) total_cp,
                                    sum(kas_ben) total_kas_ben
                         FROM rekap_sisa_kas_pengeluaran_new ($bulan,'$anggaran',$thn_ang) ");
        } else if ($jenis == '2') {

            $query = DB::select("SELECT * FROM rekap_sisa_kas_pengeluaran_new_droping_oyoy ($bulan,'$anggaran',$thn_ang) order by kd_skpd");
            $query_jum = DB::select("SELECT
                                    sum(anggaran) total_ang,
                                    sum(sp2d) total_sp2d,
                                    sum(spb) total_spb,
                                    sum(dropp_dana_terima) total_dropp_dana_terima,
                                    sum(dropp_dana_keluar) total_dropp_dana_keluar,
                                    sum(spj) total_spj,
                                    sum(sisa_kas) total_sisa_kas,
                                    sum(cp) total_cp,
                                    sum(uyhd) total_uyhd,
                                    sum(kas_ben_rumus) total_kas_ben_rumus,
                                    sum(kas_ben) total_kas_ben
                         FROM rekap_sisa_kas_pengeluaran_new_droping_oyoy ($bulan,'$anggaran',$thn_ang) ");
        } else if ($jenis == '3') {

            $query = DB::select("SELECT * FROM rekap_sisa_kas_penerimaan_oyoy ($bulan,'$anggaran',$thn_ang) order by kd_skpd");
            $query_jum = DB::select("SELECT
                            sum(anggaran) total_ang,
                            sum(terima) total_terima,
                            sum(setor) total_setor,
                            sum(sisa_kas) total_sisa_kas,
                            sum(setor_lalu) total_setor_lalu,
                            sum(kas_ben) total_kas_ben,
                            sum(kas_ben_lalu) total_kas_ben_lalu

                        FROM rekap_sisa_kas_penerimaan_oyoy($bulan,'$anggaran',$thn_ang) ");
        }





        $sc = collect(DB::select("SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient"))->first();

        $nogub = collect(DB::select("SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub_akt"))->first();



        // dd($query);


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $kd_skpd)->first();

        $data = [
            'header'    => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'query'     => $query,
            'query_jum'     => $query_jum,
            'daerah'    => $sc,
            'nogub'     => $nogub,
            'bulan'    => $bulan,
            'anggaran'   => $anggaran,
            'thn_ang'   => $thn_ang,
            'jenis'      => $jenis
        ];
        if ($jenis == '1') {
            $view =  view('akuntansi.cetakan.rekap_sisa_kas.pengeluaran')->with($data);
        } elseif ($jenis == '2') {
            $view =  view('akuntansi.cetakan.rekap_sisa_kas.pengeluaran_dropping')->with($data);
        } elseif ($jenis == '3') {
            $view =  view('akuntansi.cetakan.rekap_sisa_kas.penerimaan')->with($data);
        }
        //elseif($format=='sng'){
        //     $view =  view('akuntansi.cetakan.rekap_sisa_kas.pengeluaran')->with($data);
        // // }

        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Rekap Sisa Kas.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Rekap Sisa Kas.xls"');
            return $view;
        }
    }

    public function mandatory(Request $request)
    {
        $bidang = $request->bidang;
        $format = $request->format;
        $anggaran = $request->anggaran;

        if ($format == 1) {
            if ($bidang == 1) {
                $data = [
                    'data' => DB::select("SELECT nor,uraian,bold,kode_1,kd_skpd from map_pend_mandatory_rekap_oyoy order by nor"),
                    'jns_ang' => $anggaran
                ];
                return view('akuntansi.cetakan.mandatory_pendidikan')->with($data);
            } elseif ($bidang == 2) {
                $data = [
                    'data' => DB::select("SELECT nor,uraian,bold,kode_1,kd_skpd from map_kes_mandatory_rekap_oyoy order by nor"),
                    'jns_ang' => $anggaran
                ];
                return view('akuntansi.cetakan.mandatory_kesehatan')->with($data);
            } elseif ($bidang == 3) {
                $data = [
                    'data' => DB::select("SELECT nor,uraian,bold,kode_1,kode_2,kode_3,kode_4 from map_inA_mandatory_rekap_oyoy order by nor"),
                    'jns_ang' => $anggaran
                ];
                return view('akuntansi.cetakan.mandatory_infrastruktur')->with($data);
            }
        } else {
            if ($bidang == 1) {
                $res = DB::select("SELECT z.kd_skpd,(select nm_skpd from ms_skpd where z.kd_skpd=kd_skpd)nm_skpd,z.kd_sub_kegiatan,(select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=z.kd_sub_kegiatan)nm_sub_kegiatan,kd_rek6,(select nm_rek6 from ms_rek6 where kd_rek6=z.kd_rek6)nm_rek6,
                    (select isnull(sum(nilai),0)nilai from trdrka where jns_ang=? and kd_skpd=z.kd_skpd and z.kd_sub_kegiatan=kd_sub_kegiatan and kd_rek6=z.kd_rek6)anggaran,
                    (select isnull(sum(debet-kredit),0)nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where year(tgl_voucher)='2023' and kd_skpd=z.kd_skpd and z.kd_sub_kegiatan=kd_sub_kegiatan and kd_rek6=z.kd_rek6)realisasi
                    from map_pend_mandatory_oyoy z
                    group by kd_skpd,kd_sub_kegiatan,kd_rek6
                    order by kd_skpd,kd_sub_kegiatan,kd_rek6", [$anggaran]);
            } elseif ($bidang == 2) {
                $res = DB::select("SELECT z.kd_skpd,(select nm_skpd from ms_skpd where z.kd_skpd=kd_skpd)nm_skpd,z.kd_sub_kegiatan,(select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=z.kd_sub_kegiatan)nm_sub_kegiatan,kd_rek6,(select nm_rek6 from ms_rek6 where kd_rek6=z.kd_rek6)nm_rek6,
                    (select isnull(sum(nilai),0)nilai from trdrka where jns_ang=? and kd_skpd=z.kd_skpd and z.kd_sub_kegiatan=kd_sub_kegiatan and kd_rek6=z.kd_rek6)anggaran,
                    (select isnull(sum(debet-kredit),0)nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where year(tgl_voucher)='2023' and kd_skpd=z.kd_skpd and z.kd_sub_kegiatan=kd_sub_kegiatan and kd_rek6=z.kd_rek6)realisasi
                    from map_kes_mandatory_oyoy z
                    group by kd_skpd,kd_sub_kegiatan,kd_rek6
                    order by kd_skpd,kd_sub_kegiatan,kd_rek6", [$anggaran]);
            } elseif ($bidang == 3) {
                $res = DB::select("SELECT z.kd_skpd,(select nm_skpd from ms_skpd where z.kd_skpd=kd_skpd)nm_skpd,z.kd_sub_kegiatan,(select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=z.kd_sub_kegiatan)nm_sub_kegiatan,kd_rek6,(select nm_rek6 from ms_rek6 where kd_rek6=z.kd_rek6)nm_rek6,
                    (select isnull(sum(nilai),0)nilai from trdrka where jns_ang=? and kd_skpd=z.kd_skpd and z.kd_sub_kegiatan=kd_sub_kegiatan and kd_rek6=z.kd_rek6)anggaran,
                    (select isnull(sum(debet-kredit),0)nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where year(tgl_voucher)='2023' and kd_skpd=z.kd_skpd and z.kd_sub_kegiatan=kd_sub_kegiatan and kd_rek6=z.kd_rek6)realisasi
                    from map_in_mandatory_oyoy z
                    group by kd_skpd,kd_sub_kegiatan,kd_rek6
                    order by kd_skpd,kd_sub_kegiatan,kd_rek6", [$anggaran]);
            }

            $data = [
                'jns_ang' => $anggaran,
                'bidang' => $bidang,
                'format' => $format,
                'data' => $res
            ];
            return view('akuntansi.cetakan.mandatory_rinci')->with($data);
        }
    }
}
