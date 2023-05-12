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
        if ($jenis_skpd=='0000') {
            $jenis  = 'skpd';
        }else{
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
        if ($jenis_skpd=='0000') {
            $jenis  = 'skpd';
        }else{
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
                    ->where(['kd_skpd' => '5.02.0.00.0.00.02.0000'])
                    ->where(['kode'=> 'AKT'])
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
            where e.kd_skpd=? and d.no_sp2d='2977/TU/2022' and d.pay='BANK' group by d.tgl_bukti,d.no_bukti,d.ket,d.kd_skpd
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

    public function cetak_bb(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $dcetak    = $request->tanggal1;
        $dcetak2    = $request->tanggal2;
        $cetak          = $request->cetak;
        $skpd        = $request->kd_skpd;
        $rek6          = $request->rek6;
        // $kd_skpd        = Auth::user()->kd_skpd;

        
        $thn_ang = tahun_anggaran();

        if ((substr($rek6,0,1)=='9') or (substr($rek6,0,1)=='8') or (substr($rek6,0,1)=='4') or (substr($rek6,0,1)=='5') or (substr($rek6,0,1)=='7')){
        $csql3 = collect(DB::select("SELECT sum(a.debet) as debet,sum(a.kredit) as kredit FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' and b.tgl_voucher < '$dcetak'   AND YEAR(b.tgl_voucher)='$thn_ang'"))->first();
        } else if ($rek6=='310101010001'){
        $csql3 = collect(DB::select("SELECT sum(a.debet) as debet,sum(a.kredit) as kredit from (
                    
                    select sum(debet) debet, sum(kredit) kredit 
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where kd_rek6='310101010001' and reev='0' and kd_skpd='$skpd' and tgl_voucher < '$dcetak'
                    union all
                    select sum(debet) debet, sum(kredit) kredit 
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where kd_rek6='310101010001' and reev not in ('0') and kd_skpd='$skpd' and tgl_voucher < '$dcetak'
                    ) a "))->first();

        }else if ($rek6=='310102010001') {
            $csql3 = collect(DB::select("SELECT sum(a.debet) as debet,sum(a.kredit) as kredit from (
                    select sum(debet) debet, sum(kredit) kredit 
                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,1) in ('7','8') and kd_skpd='$skpd' and tgl_voucher < '$dcetak'
                    
                    ) a "))->first();
        }
        else {
         $csql3 = collect(DB::select("SELECT sum(a.debet) as debet,sum(a.kredit) as kredit FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' and b.tgl_voucher < '$dcetak'   "))->first();
        }

        
        $idx=1;
        if($rek6=='310101010001'){
                $query = DB::select("SELECT kd_rek6, debet, kredit, tgl_voucher, ket, no_voucher FROM (
                                           SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='310101010001' AND b.kd_skpd='$skpd' AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2'  
                                           ) a
                                           ORDER BY tgl_voucher, debet-kredit");  

        }else if ($rek6=='310102010001') {
                    $query = DB::select("SELECT kd_rek6, debet, kredit, tgl_voucher, ket, no_voucher FROM (
                                           
                                           SELECT '310102010001' kd_rek6, SUM(a.debet) debet, SUM(a.kredit) kredit, b.tgl_voucher, 'SURPLUS/DEFISIT LO ('+b.ket+' )' ket, 'SURPLUS/DEFISIT LO - '+b.no_voucher as no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE LEFT(a.kd_rek6,1) IN ('7','8') AND b.kd_skpd='$skpd' AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2'
                                           GROUP BY b.tgl_voucher, b.no_voucher, b.ket) a
                                           ORDER BY tgl_voucher, debet-kredit");

        }else{
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

    public function cetak_ns(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tgl1    = $request->tanggal1_ns;
        $tgl2    = $request->tanggal2_ns;
        $bulan    = $request->bulan_ns;
        
        $cetak          = $request->cetak;
        $skpd        = $request->kd_skpd_ns;
        $rek1          = $request->rek1;
        $thn_ang = tahun_anggaran();
        $thn_ang1 = $thn_ang-1;
        $thn_ang2 = $thn_ang1-1;
        $kd_skpd        = $request->kd_skpd_ns;
        // $kd_skpd        = Auth::user()->kd_skpd;
        if ($bulan=='') {
            $periode = "(tgl_voucher between $tgl1 and $tgl2) and ";
            $periode1= "year (tgl_voucher)='$thn_ang1' and ";
            $nm_bln = tgl_format_oyoy($tgl1);
        }else{
            $modtahun= $thn_ang%4;
        
            if ($modtahun = 0){
                $nilaibulan=".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
            }
                    else {
                $nilaibulan=".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
            }
         $arraybulan=explode(".",$nilaibulan);
         $nm_bln = $arraybulan[$bulan];
            if (strlen($bulan)==1) {
                $bulan="0".$bulan;
            }else{
                $bulan=$bulan;
            }
            $periode = "left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and year (tgl_voucher)not in('$thn_ang1','$thn_ang2') and";
            $periode1= "year (tgl_voucher)='$thn_ang1' and ";
         
        }
        // dd(strlen($bulan));

        if($kd_skpd==''){
            $kd_skpd        = Auth::user()->kd_skpd;
            $skpd_clause="";
        }else{
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

    public function cetak_ped(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tgl1    = $request->tanggal1_ped;
        $tgl2    = $request->tanggal2_ped;
        $ttd_bud    = $request->ttd_bud;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jns_ang;

        $thn_ang = tahun_anggaran();
        $thn_ang1 = $thn_ang-1;
        $thn_ang2 = $thn_ang1-1;
        
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

    public function cetak_inflasi(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tgl1    = $request->tanggal1_inflasi;
        $tgl2    = $request->tanggal2_inflasi;
        $ttd_bud    = $request->ttd_bud;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jns_ang;

        $thn_ang = tahun_anggaran();
        $thn_ang1 = $thn_ang-1;
        $thn_ang2 = $thn_ang1-1;
        
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

    public function cetak_rekonba(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $tgl1    = $request->tanggal1;
        $tgl2    = $request->tanggal2;
        $ttd    = $request->ttd;
        $cetak          = $request->cetak;
        $jns_ang        = $request->jns_ang;
        $kd_skpd        = $request->kd_skpd;
        $skpdunit        = $request->skpdunit;
        $jenis_cetakan        = $request->jenis_cetakan;

        $thn_ang = tahun_anggaran();
        $thn_ang1 = $thn_ang-1;
        $thn_ang2 = $thn_ang1-1;
        
        $arraytgl1=explode("-",$tgl1);
        $arraytgl2=explode("-",$tgl2);      
        
        if($arraytgl2[1]<='3'){
            $tw = "I";
        }else if($arraytgl2[1]<='6'){
            $tw = "II";
        }else if($arraytgl2[1]<='9'){
            $tw = "III";
        }else{
            $tw = "IV";
        }

        $bln2 = $arraytgl2[1];

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
        foreach ($hasil->result() as $trh1){
            $bre                =   $trh1->kd_rek;
            $wok                =   $trh1->uraian;
            $nilai              =   $trh1->anggaran;
            $real_up_ini        =   $trh1->up_ini;
            $real_up_ll         =   $trh1->up_lalu;
            $real_gaji_ini      =   $trh1->gaji_ini;
            $real_gaji_ll       =   $trh1->gaji_lalu;
            $real_brg_js_ini    =   $trh1->brg_ini;
            $real_brg_js_ll     =   $trh1->brg_lalu;
            $total  = $real_gaji_ll+$real_gaji_ini+$real_brg_js_ll+$real_brg_js_ini+$real_up_ll+$real_up_ini;
            $sisa   = $nilai-$real_gaji_ll-$real_gaji_ini-$real_brg_js_ll-$real_brg_js_ini-$real_up_ll-$real_up_ini;
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
            $kastunai  = $sql_kastunai->nilai+$pjk;
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
                    where e.kd_skpd='$kd_skpd' and d.no_sp2d in ('2977/TU/2022' ,'8379/TU/2022','5250/TU/2022','8523/TU/2022','1182/TU/2022','1888/TU/2022','1886/TU/2022','5249/TU/2022','8380/TU/2022')
                    
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

        $sql_pjk = collect(DB::select("SELECT ISNULL(SUM(nilai),0) nilai FROM (
                        SELECT ISNULL(SUM(b.nilai),0) AS nilai
                        FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                        WHERE MONTH(a.tgl_bukti)<='12' AND b.kd_skpd='$kd_skpd'
                        UNION ALL
                        SELECT ISNULL(SUM(b.nilai)*-1,0) AS nilai
                        FROM trhstrpot a INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                        WHERE MONTH(a.tgl_bukti)<='12' AND b.kd_skpd='$kd_skpd') z"))->first();

        if($bln2<12){
            $uyhdtini = "ISNULL(SUM(nilai),0) nilai from (
                         select ISNULL(sld_awal,0)+ISNULL(sld_awalpajak,0) nilai from ms_skpd where KD_SKPD='$kd_skpd'
                         UNION ALL 
                         select ISNULL(sum(nilai)*-1,0) nilai from TRHOUTLAIN where KD_SKPD='$kd_skpd' and tgl_bukti<='$periode2' and thnlalu='1' and jns_beban not in ('4','6')) x";
        }else{
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
        $sql_keluar_ppn = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_ppn)"))->first();

        $rek_pph21 = "'210105010001'";

        $sql_terima_pph21 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph21)"))->first();

        $sql_keluar_pph21 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph21)"))->first();

        $rek_pph22 = "'210105020001'";

        $sql_terima_pph22 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph22)"))->first();

        $sql_keluar_pph22 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph22)"))->first();

        $rek_pph23 = "'210105030001'";

        $sql_terima_pph23 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph23)"))->first();

        $sql_keluar_pph23 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph23)"))->first();


        $rek_iwp = "'210108010001'";
        $sql_terima_iwp = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_iwp)"))->first();
        $sql_keluar_iwp = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_iwp)"))->first();

        $rek_taperum = "'210107010001'";
        $sql_terima_taperum = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_taperum)"))->first();
        $sql_keluar_taperum = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_taperum)"))->first();

        $rek_pph4 = "'210109010001'";
        $sql_terima_pph4 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph4)"))->first();
        $sql_keluar_pph4 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_pph4)"))->first();

        $rek_ppn2 = "'2111001'";
        $sql_terima_ppn2 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_ppn2)"))->first();
        $sql_keluar_ppn2 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_ppn2)"))->first();

        $rek_ppn3 = "'2111101'";
        $sql_terima_ppn3 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_ppn3)"))->first();
        $sql_keluar_ppn3 = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_ppn3)"))->first();

        $rek_jkk = "'210103010001'";
        $sql_terima_jkk = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_jkk)"))->first();
        $sql_keluar_jkk = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_jkk)"))->first();

        $rek_jkm = "'210104010001'";
        $sql_terima_jkm = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_jkm)"))->first();
        $sql_keluar_jkm = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_jkm)"))->first();

        $rek_bpjs = "'210102010001'";
        $sql_terima_bpjs = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_bpjs)"))->first();
        $sql_keluar_bpjs = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_bpjs)"))->first();

        //kalau mau ditambah potongan penghasilan lainya komen di buka
        $sql_keluar_pot_penghaslain = collect(DB::select("SELECT ISNULL(SUM(a.rupiah), 0) nilai FROM trdkasin_pkd a 
                INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                           WHERE  a.kd_skpd = '$kd_skpd' AND b.tgl_sts BETWEEN '$periode1' AND '$periode2' AND jns_trans='5' and pot_khusus='2'"))->first();

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

        $sql_cp = collect(DB::select("SELECT isnull(SUM (rupiah),0) AS nilai FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd 
                  WHERE d.kd_skpd = '$kd_skpd' AND ((jns_trans = '5' AND pot_khusus = '0') OR jns_trans = '1') AND MONTH (tgl_sts) <= '$bln2'"))->first();

        $rek_denda = "'410411010001'";
        $sql_terima_denda = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_denda)"))->first();
        $sql_keluar_denda = collect(DB::select("SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
                           WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek6 IN ($rek_denda)"))->first();

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


        $sqldropin = collect(DB::select("SELECT isnull(sum(sd_bln_ini),0) as sd_bln_ini from (
                SELECT isnull(SUM(nilai),0) as sd_bln_ini from tr_setorpelimpahan_bank 
                WHERE kd_skpd = '$kd_skpd' AND (tgl_kas BETWEEN '$periode1' and '$periode2')
                union ALL
                SELECT isnull(SUM(nilai),0) as sd_bln_ini from tr_setorpelimpahan_tunai 
                WHERE kd_skpd = '$kd_skpd' AND (tgl_kas BETWEEN '$periode1' and '$periode2')
                ) x"))->first();
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


        $sqlpanjarin = collect(DB::select("SELECT SUM(x.jar_sd_bln_ini) jar_sd_bln_ini FROM(
            SELECT isnull(SUM(nilai),0) as jar_sd_bln_ini from tr_jpanjar where jns=1 and kd_skpd = '$kd_skpd' and 
            (tgl_kas BETWEEN '$periode1' and '$periode2')
            )x"))->first();
        $sqlpanjarout = collect(DB::select("SELECT isnull(SUM(nilai),0) as jarout_sd_bln_ini from tr_panjar 
                        WHERE kd_skpd= '$kd_skpd' and (tgl_kas BETWEEN '$periode1' and '$periode2') and jns='1'"))->first();

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
            where a.kd_skpd='$kd_skpd'

            )x"))->first();
        $sqlbosout = collect(DB::select("SELECT isnull(SUM(CASE WHEN tgl_bukti<='$periode2' THEN a.nilai ELSE 0 END),0) as nilai_bosout
                    from trdtransout_blud a inner join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                    WHERE a.kd_skpd='$kd_skpd' and (kd_satdik<>'1' OR kd_satdik is not null)"))->first();

        //blud
        $sqlbludin = collect(DB::select("SELECT * from (
            SELECT SUM(CASE WHEN tgl_sts<='$periode2' THEN b.rupiah ELSE 0 END) as nilai_bludin 
            from trhkasin_blud a inner join trdkasin_blud b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
            where a.kd_skpd='$kd_skpd' )x"))->first();
        $sqlbludout = collect(DB::select("SELECT isnull(blud_sd_bln_ini,0) as nilai_bludout from (
                    SELECT SUM(CASE WHEN tgl_bukti<='$periode2' THEN a.nilai ELSE 0 END) as blud_sd_bln_ini
                        from trdtransout_blud a inner join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                        WHERE a.kd_skpd='$kd_skpd' and right(kd_rek6,6)='999999'
                                                ) x"))->first();

        $setor_tlalu_blud = "";

        $sql = DB::select(" ");
        

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

}
