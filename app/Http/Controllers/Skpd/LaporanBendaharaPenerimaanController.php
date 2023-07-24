<?php

namespace App\Http\Controllers\Skpd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class LaporanBendaharaPenerimaanController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'bendahara' => DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd, 'kode' => 'BP'])->orderBy('nip')->orderBy('nama')->get(),
            'pa_kpa' => DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->orderBy('nip')->orderBy('nama')->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'daftar_rekening' => DB::select("SELECT * FROM (SELECT '' kd_rek6, '' nm_rek6 UNION ALL SELECT a.kd_rek6, (select nm_rek6 from ms_rek6 where kd_rek6=a.kd_rek6) nm_rek6 from tr_terima a group by a.kd_rek6) x order by kd_rek6"),
            'daftar_skpd' => DB::table('ms_skpd')
                ->where('kd_skpd', $kd_skpd)
                ->orderBy('kd_skpd')
                ->get()
        ];

        return view('skpd.laporan_bendahara_penerimaan.index')->with($data);
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
        $data       = DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd, 'kode' => 'BP'])->orderBy('nip')->orderBy('nama')->get();
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

    public function cariRekening(Request $request)
    {
        $kd_skpd = $request->kd_skpd;

        $data = DB::table('trdrka')
            ->select('kd_rek6', 'nm_rek6')
            ->where(['kd_skpd' => $kd_skpd])
            ->groupBy('kd_skpd', 'kd_rek6', 'nm_rek6')
            ->distinct()
            ->get();

        return response()->json($data);
    }

    // BERDASARKAN SKPD REGISTER KASDA
    public function berdasarkanSkpd(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $kd_skpd = $request->kd_skpd;
        $jenis_print = $request->jenis_print;

        if ($pilihan == '2') {
            $rincian = DB::select("SELECT a.kd_rek6 kode,a.tgl_sts as tgl, b.nm_rek6 uraian, ISNULL(pend,0) pend, ISNULL(cp,0) cp FROM
				(
				select a.kd_skpd, a.kd_rek6, b.tgl_sts,
				ISNULL(SUM(CASE WHEN LEFT(a.kd_rek6,1)='4' AND b.jns_trans<>3 THEN rupiah
				WHEN LEFT(a.kd_rek6,1)='4' AND b.jns_trans=3 THEN rupiah*-1 END ),0) AS pend,
				ISNULL(SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','2','1') THEN rupiah END ),0) AS cp FROM trdkasin_pkd a
				INNER JOIN trhkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
				WHERE a.kd_skpd =?
				AND b.tgl_sts BETWEEN ? AND ?
				GROUP BY a.kd_skpd,a.kd_rek6,tgl_sts
				UNION ALL
				select a.kd_skpd, a.kd_rek6, b.tgl_sts,
				ISNULL(SUM(CASE WHEN LEFT(a.kd_rek6,1)='4' AND b.jns_trans<>3 THEN rupiah
				WHEN LEFT(a.kd_rek6,1)='4' AND b.jns_trans=3 THEN rupiah*-1 END ),0) AS pend,
				0 AS cp FROM trdkasin_ppkd a
				INNER JOIN trhkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
				WHERE a.kd_skpd =? and a.sumber='y'
				 AND b.tgl_sts BETWEEN ? AND ?
				GROUP BY a.kd_skpd,a.kd_rek6,tgl_sts ) a
				LEFT JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6
				order by a.tgl_sts, a.kd_rek6", [$kd_skpd, $periode1, $periode2, $kd_skpd, $periode1, $periode2]);
        } else {
            $rincian = DB::select("SELECT a.kd_skpd as kode,'' AS tgl,a.nm_skpd as uraian, ISNULL(pend,0) pend, ISNULL(cp,0) cp
					FROM ms_skpd a
					LEFT JOIN (select a.kd_skpd,
					ISNULL(SUM(CASE WHEN LEFT(a.kd_rek6,1)='4' AND b.jns_trans<>3 THEN rupiah
					                WHEN LEFT(a.kd_rek6,1)='4' AND b.jns_trans=3 THEN rupiah*-1 END ),0) AS  pend,
					ISNULL(SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','2','1') THEN rupiah END ),0) AS  cp
					FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
					WHERE a.kd_skpd !=''
					AND b.tgl_sts BETWEEN ? AND ?
					GROUP BY a.kd_skpd) b
					ON a.kd_skpd=b.kd_skpd
					ORDER BY a.kd_skpd", [$periode1, $periode2]);
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'skpd' => $kd_skpd,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'rincian' => $rincian
        ];

        $judul = 'REGISTER_KASDA';

        $view = view('bud.register_kasda.berdasarkan_skpd')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }

    // BERDASARKAN KASDA REGISTER KASDA
    public function berdasarkanKasda(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $kd_skpd = $request->kd_skpd;
        $jenis_print = $request->jenis_print;

        if ($pilihan == '2') {
            $rincian = DB::select("SELECT a.kd_rek6 kode, a.tgl_kas as tgl, b.nm_rek6 uraian, ISNULL(pend,0) pend, ISNULL(cp,0) cp  FROM
                        (
                        select a.kd_skpd, b.tgl_kas,a.kd_rek6,
                        ISNULL(SUM(CASE WHEN LEFT(a.kd_rek6,1)='4' THEN rupiah END ),0) AS  pend,
                        ISNULL(SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','2','1') THEN rupiah END ),0) AS  cp
                        FROM trdkasin_ppkd a INNER JOIN trhkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas and a.no_sts=b.no_sts
                        WHERE a.kd_skpd =? and a.kd_rek6<>'410416010001'
                        AND b.tgl_kas BETWEEN ? AND ?
                        GROUP BY a.kd_skpd,a.kd_rek6,b.tgl_kas
                        ) a
                        LEFT JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6
                        ORDER BY a.tgl_kas,a.kd_rek6", [$kd_skpd, $periode1, $periode2]);
        } else {
            $rincian = DB::select("SELECT a.kd_skpd as kode,'' as tgl,a.nm_skpd as uraian, ISNULL(pend,0) pend, ISNULL(cp,0) cp
					FROM ms_skpd a
					LEFT JOIN (select a.kd_skpd,
					ISNULL(SUM(CASE WHEN LEFT(a.kd_rek6,1)='4' THEN rupiah END ),0) AS  pend,
					ISNULL(SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','2','1') THEN rupiah END ),0) AS  cp
					FROM trdkasin_ppkd a INNER JOIN trhkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas and a.no_sts=b.no_sts
					WHERE a.kd_skpd !='' and a.kd_rek6<>'410416010001'
					AND b.tgl_kas BETWEEN ? AND ?
					GROUP BY a.kd_skpd) b
					ON a.kd_skpd=b.kd_skpd
					ORDER BY a.kd_skpd", [$periode1, $periode2]);
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'skpd' => $kd_skpd,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'rincian' => $rincian
        ];

        $judul = 'REGISTER_KASDA';

        $view = view('bud.register_kasda.berdasarkan_kasda')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }

    // DETAIL PENERIMAAN REGISTER KASDA
    public function detailPenerimaan(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $kd_skpd = $request->kd_skpd;
        $jenis_print = $request->jenis_print;

        if ($pilihan == '2') {
            $rincian = DB::select("SELECT no_terima, a.kd_rek6, nilai, LEFT(a.kd_rek6,8) as kd_rek5,
						(SELECT nm_rek5 FROM ms_rek5 d WHERE LEFT(a.kd_rek6,8)=d.kd_rek5) as nm_rek5,b.nm_rek6
						from tr_terima a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6
						WHERE a.kd_skpd =? AND a.tgl_terima BETWEEN ? AND ?
						ORDER BY no_terima", [$kd_skpd, $periode1, $periode2]);
        } else {
            $rincian = DB::select("SELECT no_terima, a.kd_rek6, nilai, LEFT(a.kd_rek6,8) as kd_rek5,
						(SELECT nm_rek5 FROM ms_rek5 d WHERE LEFT(a.kd_rek6,8)=d.kd_rek5) as nm_rek5,b.nm_rek6
						from tr_terima a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6
						WHERE  a.tgl_terima BETWEEN ? AND ?
						ORDER BY no_terima", [$periode1, $periode2]);
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'skpd' => $kd_skpd,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'rincian' => $rincian
        ];

        $judul = 'REGISTER_KASDA';

        $view = view('bud.register_kasda.detail_penerimaan')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }
}
