<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\Crypt;
use stdClass;

use function PHPUnit\Framework\isNull;

class SetorSisaController extends Controller
{
    public function index()
    {
        return view('skpd.setor_sisa_kas.index');
    }

    public function loadData()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data1 = DB::table('trhkasin_pkd as a')->select('a.*', DB::raw("(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=a.kd_skpd) as nm_skpd"))->where(['a.kd_skpd' => $kd_skpd])->whereIn('a.jns_trans', ['1', '5']);
        $data2 = DB::table('trhkasin_pkd as a')->select('a.*', DB::raw("(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=a.kd_skpd) as nm_skpd"))->where(['a.kd_skpd' => $kd_skpd, 'a.jns_trans' => '4'])->whereRaw("no_sts IN (SELECT no_sts FROM trdkasin_pkd WHERE LEFT(kd_rek6,12)=?)", ['410411010001'])->unionAll($data1);
        $data = DB::table(DB::raw("({$data2->toSql()}) AS sub"))
            ->mergeBindings($data2)
            ->orderBy(DB::raw("CAST(no_sts as INT)"))
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("skpd.setor_sisa.edit", Crypt::encryptString($row->no_sts)) . '" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="fa fa-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapusSetor(' . $row->no_sts . ', \'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
        return view('skpd.setor_sisa_kas.index');
    }

    public function create()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'tahun_anggaran' => tahun_anggaran(),
            'sisa_tunai' => load_sisa_tunai()
        ];

        return view('skpd.setor_sisa_kas.create')->with($data);
    }

    public function noSp2d(Request $request)
    {
        $jenis_transaksi = $request->jenis_transaksi;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::enableQueryLog();
        if ($jenis_transaksi == '1') {
            $data = DB::table('trhsp2d as a')
                ->join('trhspp as b', function ($join) {
                    $join->on('a.no_spp', '=', 'b.no_spp');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('a.no_sp2d', 'a.jns_spp', 'b.jns_beban', 'a.nilai', DB::raw("CASE a.jns_spp WHEN '4' THEN 'LS GAJI' WHEN '6' THEN 'LS BARANG/JASA' WHEN '1' THEN 'UP' WHEN '2' THEN 'GU' ELSE 'TU' END as jns_cp"))
                ->where(['a.kd_skpd' => $kd_skpd])
                ->whereIn('a.jns_spp', ['3', '6'])
                ->whereRaw("b.no_spp NOT IN (SELECT no_spp FROM trhspp WHERE jns_spp=? and jns_beban=?)", ['6', '6'])
                ->get();
        } elseif ($jenis_transaksi == '5') {
            // $data = DB::table('trhsp2d as a')
            //     ->join('trhspp as b', function ($join) {
            //         $join->on('a.no_spp', '=', 'b.no_spp');
            //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            //     })
            //     ->select('a.no_sp2d', 'a.jns_spp', 'b.jns_beban', DB::raw("CASE a.jns_spp WHEN '4' THEN 'LS GAJI' WHEN '6' THEN 'LS BARANG/JASA' WHEN '1' THEN 'UP' WHEN '5' THEN 'LS PIHAK KETIGA LAINNYA' WHEN '2' THEN 'GU' ELSE 'TU' END as jns_cp"))
            //     ->where(['a.kd_skpd' => $kd_skpd])
            //     // ->whereIn('a.jns_spp', ['4', '5'])
            //     ->whereRaw("a.jns_spp=? and a.jns_spp=? and (a.jns_spp=? and b.jns_beban=?)", ['4', '5', '6', '6'])
            //     ->get();
            $data = DB::select("SELECT * from (
                select a.no_sp2d,a.jns_spp, a.nilai,b.jns_beban,CASE a.jns_spp WHEN '4' THEN 'LS GAJI' WHEN '6' THEN 'LS BARANG/JASA' WHEN '1' THEN 'UP' WHEN '5' THEN 'LS PIHAK KETIGA LAINNYA' WHEN '2' THEN 'GU' ELSE 'TU' END as jns_cp FROM trhsp2d as a INNER JOIN trhspp as b ON a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd where a.kd_skpd=? and a.jns_spp IN (?,?)
                UNION ALL
                select a.no_sp2d,a.jns_spp, a.nilai,b.jns_beban,CASE a.jns_spp WHEN '4' THEN 'LS GAJI' WHEN '6' THEN 'LS BARANG/JASA' WHEN '1' THEN 'UP' WHEN '5' THEN 'LS PIHAK KETIGA LAINNYA' WHEN '2' THEN 'GU' ELSE 'TU' END as jns_cp FROM trhsp2d as a INNER JOIN trhspp as b ON a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd where a.kd_skpd=? and a.jns_spp=? AND b.jns_beban=?
                )z", [$kd_skpd, '4', '5', $kd_skpd, '6', '6']);
        }

        return response()->json($data);
    }

    public function kegiatan(Request $request)
    {
        $no_sp2d = $request->no_sp2d;
        $kd_skpd = Auth::user()->kd_skpd;

        $jenis_spp = DB::table('trhsp2d')->select('jns_spp')->where(['no_sp2d' => $no_sp2d])->first();
        $jns_spp = $jenis_spp->jns_spp;

        if ($jns_spp == '1' || $jns_spp == '2') {
            $data = DB::table('trdtransout as a')->join('trhtransout as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->select('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan')->where(['a.no_sp2d' => $no_sp2d])->groupBy('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan')->get();
        } else {
            $data = DB::table('trdspp as a')->join('trhsp2d as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->select('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan')->where(['b.no_sp2d' => $no_sp2d])->groupBy('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan')->get();
        }

        $sisa_bank = collect(DB::select("SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan
            union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK'
            union all
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd=? and  d.pay='BANK'
            union all
            select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a
            join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where a.kd_skpd=? and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
            union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd=?
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot
                from trspmpot group by no_spm) c on b.no_spm=c.no_spm
                left join
                    (select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd where e.kd_skpd=? and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
                    ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd WHERE pay='BANK' and (panjar not in ('1') or panjar is null)
             union all
            select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a
            join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where a.kd_skpd=? and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
            UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan
            union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK'
            union all
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank
            union all
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1'
            union all
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
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd=?
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd=?
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            ) a
        where  kode=?", [$kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd]))->first();

        // SISA TUNAI
        $sisa_tunai1 = DB::table('tr_ambilsimpanan')->select('tgl_kas as tgl', 'no_kas as bku', 'keterangan as ket', 'nilai as jumlah', DB::raw("'1' as jns"), 'kd_skpd as kode')->where(['kd_skpd' => $kd_skpd]);

        $sisa_tunai2 = DB::table('trhkasin_pkd as a')->join('trdkasin_pkd as b', function ($join) {
            $join->on('a.no_sts', '=', 'b.no_sts');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->select('a.tgl_sts as tgl', 'a.no_sts as bku', 'a.keterangan as ket', DB::raw("sum(b.rupiah) as jumlah"), DB::raw("'2' as jns"), 'a.kd_skpd as kode')->where(['a.kd_skpd' => $kd_skpd, 'bank' => 'TN'])->whereIn('pot_khusus', ['0', '2'])->whereNotIn('jns_trans', ['2', '4', '5'])->groupBy('a.tgl_sts', 'a.no_sts', 'a.keterangan', 'a.kd_skpd')->unionAll($sisa_tunai1);

        $join1 = DB::table('trspmpot')->select('no_spm', DB::raw("SUM(nilai) as pot"))->groupBy('no_spm');

        $sisa_tunai3 = DB::table('trhtransout as a')->join('trdtransout as b', function ($join) {
            $join->on('a.no_bukti', '=', 'b.no_bukti');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->leftJoin('trhsp2d as c', function ($join) {
            $join->on('b.no_sp2d', '=', 'c.no_sp2d');
        })->leftJoinSub($join1, 'd', function ($join) {
            $join->on('c.no_spm', '=', 'd.no_spm');
        })->select('a.tgl_bukti as tgl', 'a.no_bukti as bku', 'a.ket as ket', DB::raw("sum(b.nilai - isnull(pot,0)) as jumlah"), DB::raw("'2' as jns"), 'a.kd_skpd as kode')->where(['a.kd_skpd' => $kd_skpd, 'pay' => 'TUNAI'])->where('panjar', '<>', '1')->whereRaw("a.no_bukti NOT IN (SELECT no_bukti FROM trhtransout WHERE no_sp2d IN ( SELECT no_sp2d AS no_bukti FROM trhtransout WHERE kd_skpd=? GROUP BY no_sp2d HAVING COUNT ( no_sp2d ) > 1 ) AND no_kas NOT IN (SELECT MIN( z.no_kas ) AS no_bukti FROM trhtransout z WHERE z.jns_spp IN ( 4, 5, 6 ) AND kd_skpd=? GROUP BY z.no_sp2d HAVING COUNT ( z.no_sp2d ) > 1) AND jns_spp IN ( 4, 5, 6 ) AND kd_skpd=?)", [$kd_skpd, $kd_skpd, $kd_skpd])->groupBy('a.tgl_bukti', 'a.no_bukti', 'a.ket', 'a.no_sp2d', 'b.no_sp2d', 'a.total', 'pot', 'a.kd_skpd')->unionAll($sisa_tunai2);

        $sisa_tunai4 = DB::table('trhtransout')->select('tgl_bukti as tgl', 'no_bukti as bku', 'ket as ket', DB::raw("isnull(total,0) as jumlah"), DB::raw("'2' as jns"), 'kd_skpd as kode')->where(['kd_skpd' => $kd_skpd, 'pay' => 'TUNAI'])->whereIn('jns_spp', ['4', '5', '6'])->where('panjar', '<>', '1')->whereRaw("no_sp2d IN (SELECT no_sp2d AS no_bukti FROM trhtransout WHERE kd_skpd=? GROUP BY no_sp2d HAVING COUNT ( no_sp2d ) > 1)", [$kd_skpd])->whereRaw("no_kas NOT IN(SELECT MIN( z.no_kas ) AS no_bukti FROM trhtransout z WHERE z.jns_spp IN ( 4, 5, 6 ) AND kd_skpd=? GROUP BY z.no_sp2d HAVING COUNT ( z.no_sp2d ) > 1)", [$kd_skpd])->unionAll($sisa_tunai3);

        $sisa_tunai5 = DB::table('tr_setorsimpanan')->select('tgl_kas as tgl', 'no_kas as bku', 'keterangan as ket', 'nilai as jumlah', DB::raw("'2' as jns"), 'kd_skpd as kode')->where(['kd_skpd' => $kd_skpd, 'jenis' => '2'])->unionAll($sisa_tunai4);

        $sisa_tunai6 = DB::table('tr_setorpelimpahan')->select('tgl_bukti as tgl', 'no_bukti as bku', 'keterangan as ket', 'nilai as jumlah', DB::raw("'2' as jns"), 'kd_skpd_sumber as kode')->where(['kd_skpd_sumber' => $kd_skpd])->unionAll($sisa_tunai5);

        $sisa_tunai = DB::table(DB::raw("({$sisa_tunai6->toSql()}) AS sub"))
            ->select(DB::raw("(CASE WHEN jns=1 THEN jumlah ELSE 0 END) as terima"), DB::raw("(CASE WHEN jns=2 THEN jumlah ELSE 0 END) as keluar"))
            ->mergeBindings($sisa_tunai6)
            ->first();

        $potongan_ls = collect(DB::select("SELECT SUM(a.nilai) as total  FROM trspmpot a INNER JOIN trhsp2d b on b.no_spm = a.no_spm AND b.kd_skpd=a.kd_skpd
		where ((b.jns_spp = '4' AND b.jenis_beban != '1') or (b.jns_spp = '6' AND b.jenis_beban != '3'))
		and b.no_sp2d = ? and b.kd_skpd = ?", [$no_sp2d, $kd_skpd]))->first();

        return response()->json([
            'kegiatan' => $data,
            'sisa_bank' => $sisa_bank->terima - $sisa_bank->keluar,
            'sisa_tunai' => isset($sisa_tunai) ? $sisa_tunai->terima - $sisa_tunai->keluar : 0,
            'potongan_ls' => $potongan_ls->total
        ]);
    }

    public function rekening(Request $request)
    {
        $jenis_transaksi = $request->jenis_transaksi;
        $no_sp2d = $request->no_sp2d;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_rek = $request->kd_rek6;
        $kd_skpd = Auth::user()->kd_skpd;

        if (isset($kd_rek)) {
            $kd_rek1 = [];
            foreach ($kd_rek as $rek) {
                $kd_rek1[] = $rek['kd_rek6'];
            }
            $kd_rek6 = json_decode(json_encode($kd_rek1), true);
        } else {
            $kd_rek6 = [];
        }

        if (isset($kd_sub_kegiatan)) {
            $data1 = DB::table('trdspp as a')
                ->join('trhspp as b', function ($join) {
                    $join->on('a.no_spp', '=', 'b.no_spp');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->join('trhsp2d as c', function ($join) {
                    $join->on('b.no_spp', '=', 'c.no_spp');
                    $join->on('b.kd_skpd', '=', 'c.kd_skpd');
                })
                ->selectRaw("a.kd_rek6,a.nm_rek6,SUM(a.nilai) as nilai,
					(SELECT sum(nilai) FROM trdtransout WHERE no_sp2d=c.no_sp2d and kd_sub_kegiatan=a.kd_sub_kegiatan and kd_rek6=a.kd_rek6) as transaksi,
					(select sum(f.rupiah) from trhkasin_pkd e join trdkasin_pkd f on e.no_sts=f.no_sts and e.kd_skpd=f.kd_skpd
					where f.kd_sub_kegiatan=a.kd_sub_kegiatan and e.no_sp2d=? and f.kd_rek6=a.kd_rek6) [cp]", [$no_sp2d])
                ->whereRaw("c.no_sp2d =? AND c.kd_skpd =? and a.kd_sub_kegiatan=?", [$no_sp2d, $kd_skpd, $kd_sub_kegiatan])
                ->whereNotIn('a.kd_rek6', $kd_rek6)
                ->groupBy('kd_rek6', 'nm_rek6', 'no_sp2d', 'a.kd_sub_kegiatan');

            $data = DB::table(DB::raw("({$data1->toSql()}) AS sub"))
                ->select('sub.*', DB::raw("nilai - isnull(transaksi,0) - isnull(cp,0) as sisa"))
                ->mergeBindings($data1)
                ->get();

            // $data = DB::select("SELECT z.*, nilai-isnull(transaksi,0)-isnull(cp,0) as sisa FROM (SELECT a.kd_rek6,a.nm_rek6,SUM(a.nilai) as nilai,
            // 		(SELECT sum(nilai) FROM trdtransout WHERE no_sp2d=c.no_sp2d and kd_sub_kegiatan=a.kd_sub_kegiatan and kd_rek6=a.kd_rek6) as transaksi,
            // 		(select sum(f.rupiah) from trhkasin_pkd e join trdkasin_pkd f on e.no_sts=f.no_sts and e.kd_skpd=f.kd_skpd
            // 		where f.kd_sub_kegiatan=a.kd_sub_kegiatan and e.no_sp2d=? and f.kd_rek6=a.kd_rek6) [cp]
            // 		FROM trdspp a INNER JOIN trhspp b ON a.no_spp = b.no_spp  and a.kd_skpd=b.kd_skpd
            // 		INNER JOIN trhsp2d c ON c.no_spp = b.no_spp and c.kd_skpd=b.kd_skpd where c.no_sp2d =?
            // 		AND c.kd_skpd = ? and a.kd_sub_kegiatan = ?
            // 		 and a.kd_rek6 not in ($kd_rek6) GROUP BY kd_rek6, nm_rek6,no_sp2d,a.kd_sub_kegiatan)z", [$no_sp2d, $no_sp2d, $kd_skpd, $kd_sub_kegiatan]);
        } else {
            $data = DB::table('ms_rek6')
                ->select('kd_rek6', 'nm_rek6')
                ->whereRaw("LEFT(kd_rek6,4)=?", ['1101'])
                ->whereNotIn('kd_rek6', $kd_rek6)
                ->get();
        }

        return response()->json($data);
    }

    public function simpan(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $no_urut = no_urut($kd_skpd);

            DB::table('trhkasin_pkd')->where(['kd_skpd' => $kd_skpd, 'no_sts' => $no_urut])->delete();

            DB::table('trhju_pkd')->where(['kd_skpd' => $kd_skpd, 'no_voucher' => $no_urut])->delete();

            DB::table('trhju')->where(['kd_skpd' => $kd_skpd, 'no_voucher' => $no_urut])->delete();
            // Setor Sisa Kas/CP
            if ($data['jenis_transaksi'] == '5') {
                DB::table('trhkasin_pkd')->insert([
                    'no_kas' => $no_urut,
                    'no_sts' => $no_urut,
                    'kd_skpd' => $data['kd_skpd'],
                    'tgl_sts' => $data['tgl_kas'],
                    'tgl_kas' => $data['tgl_kas'],
                    'keterangan' => $data['uraian'],
                    'total' => $data['jumlah'],
                    'kd_bank' => '',
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    'jns_trans' => $data['jenis_transaksi'],
                    'rek_bank' => '',
                    'sumber' => '0',
                    'pot_khusus' => $data['potlain'],
                    'no_sp2d' => $data['no_sp2d'],
                    'jns_cp' => $data['jns_cp'],
                    'bank' => $data['pembayaran'],
                ]);
            } else {
                DB::table('trhkasin_pkd')->insert([
                    'no_kas' => $no_urut,
                    'no_sts' => $no_urut,
                    'kd_skpd' => $data['kd_skpd'],
                    'tgl_sts' => $data['tgl_kas'],
                    'tgl_kas' => $data['tgl_kas'],
                    'keterangan' => $data['uraian'],
                    'total' => $data['jumlah'],
                    'kd_bank' => '',
                    'kd_sub_kegiatan' => isset($data['kd_sub_kegiatan']) ? $data['kd_sub_kegiatan'] : '',
                    'jns_trans' => $data['jenis_transaksi'],
                    'rek_bank' => '',
                    'sumber' => '0',
                    'pot_khusus' => '0',
                    'no_sp2d' => $data['no_sp2d'],
                    'jns_cp' => $data['jns_cp'],
                    'bank' => $data['pembayaran'],
                ]);
            }

            DB::table('trdkasin_pkd')->where(['no_sts' => $no_urut, 'kd_skpd' => $kd_skpd])->delete();

            if (isset($data['detail'])) {
                DB::table('trdkasin_pkd')->insert(array_map(function ($value) use ($no_urut, $kd_skpd, $data) {
                    return [
                        'no_sts' => $no_urut,
                        'kd_rek6' => $value['kd_rek6'],
                        'rupiah' => $value['rupiah'],
                        'kd_sub_kegiatan' => isset($data['kd_sub_kegiatan']) ? $data['kd_sub_kegiatan'] : '',
                        'kd_skpd' => $kd_skpd,
                    ];
                }, $data['detail']));
            }

            DB::commit();
            return response()->json([
                'message' => '1',
                'no_kas' => $no_urut
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function edit($no_sts)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_sts = Crypt::decryptString($no_sts);

        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'tahun_anggaran' => tahun_anggaran(),
            'sisa_tunai' => load_sisa_tunai(),
            'setor' => DB::table('trhkasin_pkd as a')->join('trdkasin_pkd as b', function ($join) {
                $join->on('a.no_sts', '=', 'b.no_sts');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->select('a.*')->where(['a.no_sts' => $no_sts, 'a.kd_skpd' => $kd_skpd])->first(),
            'data_list' => DB::table('trhkasin_pkd as a')->join('trdkasin_pkd as b', function ($join) {
                $join->on('a.no_sts', '=', 'b.no_sts');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->select('b.*')->where(['a.no_sts' => $no_sts, 'a.kd_skpd' => $kd_skpd])->get(),
        ];

        return view('skpd.setor_sisa_kas.edit')->with($data);
    }

    public function update(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {

            DB::table('trhkasin_pkd')->where(['kd_skpd' => $kd_skpd, 'no_sts' => $data['no_kas']])->delete();

            DB::table('trhju_pkd')->where(['kd_skpd' => $kd_skpd, 'no_voucher' => $data['no_kas']])->delete();

            DB::table('trhju')->where(['kd_skpd' => $kd_skpd, 'no_voucher' => $data['no_kas']])->delete();
            // Setor Sisa Kas/CP
            if ($data['jenis_transaksi'] == '5') {
                DB::table('trhkasin_pkd')->insert([
                    'no_kas' => $data['no_kas'],
                    'no_sts' => $data['no_kas'],
                    'kd_skpd' => $data['kd_skpd'],
                    'tgl_sts' => $data['tgl_kas'],
                    'tgl_kas' => $data['tgl_kas'],
                    'keterangan' => $data['uraian'],
                    'total' => $data['jumlah'],
                    'kd_bank' => '',
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    'jns_trans' => $data['jenis_transaksi'],
                    'rek_bank' => '',
                    'sumber' => '0',
                    'pot_khusus' => $data['potlain'],
                    'no_sp2d' => $data['no_sp2d'],
                    'jns_cp' => $data['jns_cp'],
                    'bank' => $data['pembayaran'],
                ]);
            } else {
                DB::table('trhkasin_pkd')->insert([
                    'no_kas' => $data['no_kas'],
                    'no_sts' => $data['no_kas'],
                    'kd_skpd' => $data['kd_skpd'],
                    'tgl_sts' => $data['tgl_kas'],
                    'tgl_kas' => $data['tgl_kas'],
                    'keterangan' => $data['uraian'],
                    'total' => $data['jumlah'],
                    'kd_bank' => '',
                    'kd_sub_kegiatan' => isset($data['kd_sub_kegiatan']) ? $data['kd_sub_kegiatan'] : '',
                    'jns_trans' => $data['jenis_transaksi'],
                    'rek_bank' => '',
                    'sumber' => '0',
                    'pot_khusus' => '0',
                    'no_sp2d' => $data['no_sp2d'],
                    'jns_cp' => $data['jns_cp'],
                    'bank' => $data['pembayaran'],
                ]);
            }

            DB::table('trdkasin_pkd')->where(['no_sts' => $data['no_kas'], 'kd_skpd' => $kd_skpd])->delete();

            if (isset($data['detail'])) {
                DB::table('trdkasin_pkd')->insert(array_map(function ($value) use ($kd_skpd, $data) {
                    return [
                        'no_sts' => $data['no_kas'],
                        'kd_rek6' => $value['kd_rek6'],
                        'rupiah' => $value['rupiah'],
                        'kd_sub_kegiatan' => isset($data['kd_sub_kegiatan']) ? $data['kd_sub_kegiatan'] : '',
                        'kd_skpd' => $kd_skpd,
                    ];
                }, $data['detail']));
            }

            DB::commit();
            return response()->json([
                'message' => '1',
                'no_kas' => $data['no_kas']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function hapus(Request $request)
    {
        $no_sts = $request->no_sts;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('tr_terima as a')->join('trdkasin_pkd as b', function ($join) {
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.no_terima', '=', 'b.no_terima');
                $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
            })->where(['a.kd_skpd' => $kd_skpd, 'b.no_sts' => $no_sts])->update([
                'a.kunci' => '0'
            ]);

            DB::table('trhkasin_pkd')->where(['no_sts' => $no_sts, 'kd_skpd' => $kd_skpd])->delete();

            DB::table('trdkasin_pkd')->where(['no_sts' => $no_sts, 'kd_skpd' => $kd_skpd])->delete();

            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }
    // Pelimpahan UP Sampai hapusUp
}
