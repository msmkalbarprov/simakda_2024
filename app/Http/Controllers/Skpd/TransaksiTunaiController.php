<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransaksiTunaiController extends Controller
{
    public function index()
    {
        $cek1 = selisih_angkas();

        $status_ang = $cek1['status_ang'];
        $status_angkas = $cek1['status_angkas'];

        $cek = DB::table('tb_status_angkas')
            ->whereRaw("left(jns_angkas,2)=? and status_kunci=? and status=?", [$status_ang, $status_angkas, '1'])
            ->count();

        $data = [
            'cek' => selisih_angkas(),
            'cek1' => $cek
        ];

        if ($cek  == 0) {
            return view('skpd.transaksi_tunai.index')
                ->with($data)
                ->with('message', 'Jenis Anggaran tidak sama dengan Jenis Anggaran Kas!');
        }
        return view('skpd.transaksi_tunai.index')->with($data);
    }

    public function loadData()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::table('trhtransout as a')->select('a.*', DB::raw("'' as nokas_pot"), DB::raw("'' as tgl_pot"), DB::raw("'' as kete"), DB::raw("(SELECT COUNT(*) FROM trlpj z JOIN trhlpj v ON v.no_lpj=z.no_lpj WHERE v.jenis=a.jns_spp AND z.no_bukti=a.no_bukti AND z.kd_bp_skpd=a.kd_skpd) as ketlpj"), DB::raw("CASE WHEN a.tgl_bukti<'2018-01-01' THEN 1 ELSE 0 END as ketspj"))->where(['a.panjar' => '0', 'a.kd_skpd' => $kd_skpd, 'a.pay' => 'TUNAI'])->orderBy(DB::raw("CAST(a.no_bukti as numeric)"))->orderBy('a.kd_skpd')->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("skpd.transaksi_tunai.edit", Crypt::encryptString($row->no_bukti)) . '" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            if ($row->ketlpj != 1) {
                $btn .= '<a href="javascript:void(0);" onclick="hapusTransaksi(' . $row->no_bukti . ');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
            }
            return $btn;
        })->rawColumns(['aksi'])->make(true);
        return view('skpd.transaksi_tunai.index');
    }

    public function create()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_kegiatan' => DB::table('trdrka as a')->select('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan', DB::raw("SUM(a.nilai) as total"))->where(['a.kd_skpd' => $kd_skpd])->whereRaw("left(a.kd_rek6,1)=?", ['5'])->groupBy('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan')->orderBy('a.kd_sub_kegiatan')->orderBy('a.nm_sub_kegiatan')->get(),
            'persen' => DB::table('config_app')->select('persen_kkpd', 'persen_tunai')->first(),
        ];

        DB::table('tb_transaksi')
            ->where(['kd_skpd' => $kd_skpd, 'username' => Auth::user()->nama])
            ->delete();

        return view('skpd.transaksi_tunai.create')->with($data);
    }

    public function nomorSp2d(Request $request)
    {
        $beban = $request->beban;
        $kd_skpd = $request->kd_skpd;
        $kode = substr($kd_skpd, 0, 17);
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;

        if ((isset($beban) && empty($kd_sub_kegiatan)) || ($beban == '1')) {
            $where = "a.jns_spp IN ('1','2')";
        }
        if (isset($kd_sub_kegiatan) && $beban != '1') {
            $where = "a.jns_spp=? AND b.kd_sub_kegiatan =?";
        }

        $data = DB::table('trhsp2d as a')
            ->join('trdspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })
            ->select('a.no_sp2d', 'a.tgl_sp2d', DB::raw("SUM(a.nilai) as nilai"))
            ->whereRaw("LEFT(a.kd_skpd,17)=LEFT(?,17)", [$kd_skpd])
            ->where(['a.status' => '1'])
            ->whereRaw($where, [$beban, $kd_sub_kegiatan])
            ->where(function ($query) {
                $query->where('c.kkpd', '!=', '1')->orWhereNull('c.kkpd');
            })
            ->groupBy('a.no_sp2d', 'a.tgl_sp2d')
            ->orderByDesc('a.tgl_sp2d')
            ->orderBy('a.no_sp2d')
            ->distinct()
            ->get();

        return response()->json($data);
    }

    public function cariRekening(Request $request)
    {
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $no_bukti = $request->no_bukti;
        $beban = $request->beban;
        $no_sp2d = $request->no_sp2d;
        $kd_skpd = $request->kd_skpd;
        $jenis_ang = status_anggaran();

        // REKENING
        if ($beban == '1') {
            if ($kd_sub_kegiatan == '1.01.1.01.01.00.22.002') {
                $data = DB::table('trdrka as a')->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.kd_skpd' => $kd_skpd, 'a.status_aktif' => '1', 'a.kd_rek6' => '5221104'])->select('a.kd_rek6', 'a.nm_rek6', DB::raw("'0' as sp2d"), 'nilai as anggaran')->selectRaw("(SELECT SUM( nilai ) FROM(SELECT SUM( c.nilai ) AS nilai FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti  AND c.kd_skpd = d.kd_skpd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan  AND d.kd_skpd = a.kd_skpd  AND c.kd_rek6 = a.kd_rek6  AND d.jns_spp= ? AND c.no_voucher <> ? AND d.status_validasi = '0' UNION ALL SELECT SUM(c.nilai) as nilai FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti AND c.kd_skpd = d.kd_skpd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan AND d.kd_skpd=a.kd_skpd AND c.kd_rek6 = a.kd_rek6 AND d.jns_spp=? UNION ALL SELECT SUM(x.nilai) as nilai FROM trdspp x INNER JOIN trhspp y ON x.no_spp= y.no_spp AND x.kd_skpd= y.kd_skpd WHERE x.kd_sub_kegiatan = a.kd_sub_kegiatan AND x.kd_skpd=a.kd_skpd AND x.kd_rek6 = a.kd_rek6 AND y.jns_spp IN ( '3', '4', '5', '6' ) AND ( sp2d_batal IS NULL OR sp2d_batal = '' OR sp2d_batal = '0') UNION ALL SELECT SUM( nilai ) AS nilai FROM trdtagih t INNER JOIN trhtagih u ON t.no_bukti= u.no_bukti AND t.kd_skpd= u.kd_skpd WHERE t.kd_sub_kegiatan = a.kd_sub_kegiatan AND u.kd_skpd = a.kd_skpd AND t.kd_rek = a.kd_rek6 AND u.no_bukti NOT IN ( SELECT no_tagih FROM trhspp WHERE kd_skpd=? )r) AS lalu", [$beban, $no_bukti, $no_bukti, $beban, $kd_skpd])->selectRaw("(SELECT SUM ( nilai ) FROM trdrka WHERE no_trdrka = a.no_trdrka AND jns_ang =?) as nilai_ubah", [$jenis_ang])->distinct()->get();
            } elseif ($kd_sub_kegiatan == '4.08.4.08.01.00.01.351') {
                $data = DB::table('trdrka as a')->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.kd_skpd' => $kd_skpd, 'a.status_aktif' => '1'])->select('a.kd_rek6', 'a.nm_rek6', DB::raw("'0' as sp2d"), 'nilai as anggaran')->selectRaw("(SELECT SUM( nilai ) FROM(SELECT SUM(c.nilai) AS nilai FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher AND c.kd_skpd = d.kd_skpd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan AND d.kd_skpd=a.kd_skpd AND c.kd_rek6 = a.kd_rek6 AND c.no_voucher <> ? AND d.jns_spp=? AND d.status_validasi= '0' UNION ALL SELECT SUM(c.nilai) AS nilai FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti AND c.kd_skpd = d.kd_skpd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan AND d.kd_skpd=a.kd_skpd AND c.kd_rek6 = a.kd_rek6 AND d.jns_spp=? UNION ALL SELECT SUM(x.nilai) AS nilai FROM trdspp x INNER JOIN trhspp y ON x.no_spp= y.no_spp AND x.kd_skpd= y.kd_skpd WHERE x.kd_sub_kegiatan = a.kd_sub_kegiatan AND x.kd_skpd=a.kd_skpd AND x.kd_rek6 = a.kd_rek6 AND y.jns_spp IN ( '3', '4', '5', '6' ) AND ( sp2d_batal IS NULL OR sp2d_batal = '' OR sp2d_batal = '0') UNION ALL SELECT SUM(nilai) AS nilai FROM trdtagih t INNER JOIN trhtagih u ON t.no_bukti= u.no_bukti AND t.kd_skpd= u.kd_skpd WHERE t.kd_sub_kegiatan = a.kd_sub_kegiatan AND u.kd_skpd = a.kd_skpd AND t.kd_rek = a.kd_rek6 AND u.no_bukti NOT IN ( SELECT no_tagih FROM trhspp WHERE kd_skpd =? ))r) AS lalu", [$no_bukti, $beban, $beban, $kd_skpd])->selectRaw("(SELECT SUM ( nilai ) FROM trdrka WHERE no_trdrka = a.no_trdrka AND jns_ang =?) as nilai_ubah", [$jenis_ang])->distinct()->get();
            } else {
                $data = DB::table('trdrka as a')->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.kd_skpd' => $kd_skpd, 'a.status_aktif' => '1'])->select('a.kd_rek6', 'a.nm_rek6', DB::raw("'0' as sp2d"), 'nilai as anggaran')->selectRaw("(SELECT SUM( nilai ) FROM(SELECT SUM(c.nilai) AS nilai FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher AND c.kd_skpd = d.kd_skpd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan AND d.kd_skpd=a.kd_skpd AND c.kd_rek6 = a.kd_rek6 AND c.no_voucher <> ? AND d.jns_spp=? AND d.status_validasi= '0' UNION ALL SELECT SUM(c.nilai) AS nilai FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti AND c.kd_skpd = d.kd_skpd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan AND d.kd_skpd= a.kd_skpd AND c.kd_rek6 = a.kd_rek6 AND d.jns_spp=? UNION ALL SELECT SUM(x.nilai) AS nilai FROM trdspp x INNER JOIN trhspp y ON x.no_spp= y.no_spp AND x.kd_skpd= y.kd_skpd WHERE x.kd_sub_kegiatan = a.kd_sub_kegiatan AND x.kd_skpd = a.kd_skpd AND x.kd_rek6 = a.kd_rek6 AND y.jns_spp IN ( '3', '4', '5', '6' ) AND ( sp2d_batal IS NULL OR sp2d_batal = '' OR sp2d_batal = '0' ) UNION ALL SELECT SUM(nilai) AS nilai FROM trdtagih t INNER JOIN trhtagih u ON t.no_bukti= u.no_bukti AND t.kd_skpd= u.kd_skpd WHERE t.kd_sub_kegiatan = a.kd_sub_kegiatan AND u.kd_skpd = a.kd_skpd AND t.kd_rek = a.kd_rek6 AND u.no_bukti NOT IN ( SELECT no_tagih FROM trhspp WHERE kd_skpd =? ))r) AS lalu", [$no_bukti, $beban, $beban, $kd_skpd])->selectRaw("(SELECT SUM ( nilai ) FROM trdrka WHERE no_trdrka = a.no_trdrka AND jns_ang =?) as nilai_ubah", [$jenis_ang])->distinct()->get();
            }
        } else {
            $data = DB::table('trhspp as a')->join('trdspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhspm as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })->join('trhsp2d as d', function ($join) {
                $join->on('c.no_spm', '=', 'd.no_spm');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })->join('trdrka as f', function ($join) {
                $join->on('b.kd_bidang', '=', 'f.kd_skpd');
                $join->on('b.kd_sub_kegiatan', '=', 'f.kd_sub_kegiatan');
                $join->on('b.kd_rek6', '=', 'f.kd_rek6');
            })->where(['d.no_sp2d' => $no_sp2d, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'f.status_aktif' => '1'])->select('b.kd_rek6', 'b.nm_rek6', DB::raw("'0' as anggaran"), DB::raw("'0' as nilai_ubah"), 'b.nilai as sp2d')->selectRaw("(SELECT SUM(c.nilai) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher= d.no_voucher AND c.kd_skpd= d.kd_skpd WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND d.kd_skpd= a.kd_skpd AND c.kd_rek6= b.kd_rek6 AND c.no_voucher <> ? AND d.jns_spp = ? AND c.no_sp2d = ?) AS lalu", [$no_bukti, $beban, $no_sp2d])->distinct()->get();
        }

        // SISA BANK
        if ($beban == '1') {
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
            where e.kd_skpd=? and d.no_sp2d='2704/TU/2023' and d.pay='BANK' group by d.tgl_bukti,d.no_bukti,d.ket,d.kd_skpd
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
        } else {
            $sisa_bank = collect(DB::select("SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT SUM(a.nilai) AS jumlah,'1' AS jns FROM trdspp a INNER JOIN trhspp b ON a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd INNER JOIN trhspm c ON b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd INNER JOIN trhsp2d d ON c.no_spm=d.no_spm and c.kd_skpd=d.kd_skpd WHERE d.kd_skpd=? and d.no_sp2d=?
            UNION ALL
            select SUM(b.nilai) AS jumlah,'2' AS jns
            from trhtransout a join trdtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where b.kd_skpd=? and a.no_sp2d=?
            UNION ALL
            select isnull(sum(isnull(b.nilai,0)),0) AS jumlah, '2' as jns
            from trhtransout_cmsbank a join trdtransout_cmsbank b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_skpd
            where b.kd_skpd=? and a.no_sp2d=? and (status_validasi='0' OR status_validasi is null)
            UNION ALL
            SELECT isnull(sum(isnull(nilai,0)),0) AS jumlah, '2' as jns
            from tb_transaksi WHERE kd_skpd=? and no_sp2d=?
            ) aa", [$kd_skpd, $no_sp2d, $kd_skpd, $no_sp2d, $kd_skpd, $no_sp2d, $kd_skpd, $no_sp2d]))
                ->first();
        }

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
            'rekening' => $data,
            'sisa_bank' => $sisa_bank->terima - $sisa_bank->keluar,
            'sisa_tunai' => isset($sisa_tunai) ? $sisa_tunai->terima - $sisa_tunai->keluar : 0,
            'potongan_ls' => $potongan_ls->total
        ]);
    }

    public function cariSumber(Request $request)
    {
        $kd_rek6 = $request->kd_rek6;
        $kd_skpd = $request->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $no_sp2d = $request->no_sp2d;
        $beban = $request->beban;

        $jenis_ang = status_anggaran();

        $data1 = DB::table('trdrka as a')->select('sumber1 as sumber_dana', DB::raw("ISNULL(nsumber1,0) as nilai"))->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.kd_rek6' => $kd_rek6, 'a.kd_skpd' => $kd_skpd, 'a.jns_ang' => $jenis_ang]);

        $data2 = DB::table('trdrka as a')->select('sumber2 as sumber_dana', DB::raw("ISNULL(nsumber2,0) as nilai"))->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.kd_rek6' => $kd_rek6, 'a.kd_skpd' => $kd_skpd, 'a.jns_ang' => $jenis_ang])->where('a.nsumber2', '<>', '0')->unionAll($data1);

        $data3 = DB::table('trdrka as a')->select('sumber3 as sumber_dana', DB::raw("ISNULL(nsumber3,0) as nilai"))->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.kd_rek6' => $kd_rek6, 'a.kd_skpd' => $kd_skpd, 'a.jns_ang' => $jenis_ang])->where('a.nsumber3', '<>', '0')->unionAll($data2);

        $data4 = DB::table('trdrka as a')->select('sumber4 as sumber_dana', DB::raw("ISNULL(nsumber4,0) as nilai"))->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.kd_rek6' => $kd_rek6, 'a.kd_skpd' => $kd_skpd, 'a.jns_ang' => $jenis_ang])->where('a.nsumber4', '<>', '0')->unionAll($data3);

        $data = DB::table(DB::raw("({$data4->toSql()}) AS sub"))
            ->mergeBindings($data4)
            ->get();

        return response()->json($data);
    }

    public function sisaTunai(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data1 = DB::table('tr_ambilsimpanan')->select('tgl_kas as tgl', 'no_kas as bku', 'keterangan as ket', 'nilai as jumlah', DB::raw("'1' as jns"), 'kd_skpd as kode')->where(['kd_skpd' => $kd_skpd]);

        $data2 = DB::table('trhkasin_pkd as a')->join('trdkasin_pkd as b', function ($join) {
            $join->on('a.no_sts', '=', 'b.no_sts');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->select('a.tgl_sts as tgl', 'a.no_sts as bku', 'a.keterangan as ket', DB::raw("sum(b.rupiah) as jumlah"), DB::raw("'2' as jns"), 'a.kd_skpd as kode')->where(['a.kd_skpd' => $kd_skpd, 'bank' => 'TN'])->whereIn('pot_khusus', ['0', '2'])->whereNotIn('jns_trans', ['2', '4', '5'])->groupBy('a.tgl_sts', 'a.no_sts', 'a.keterangan', 'a.kd_skpd')->unionAll($data1);

        $join1 = DB::table('trspmpot')->select('no_spm', DB::raw("SUM(nilai) as pot"))->groupBy('no_spm');

        $data3 = DB::table('trhtransout as a')->join('trdtransout as b', function ($join) {
            $join->on('a.no_bukti', '=', 'b.no_bukti');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->leftJoin('trhsp2d as c', function ($join) {
            $join->on('b.no_sp2d', '=', 'c.no_sp2d');
        })->leftJoinSub($join1, 'd', function ($join) {
            $join->on('c.no_spm', '=', 'd.no_spm');
        })->select('a.tgl_bukti as tgl', 'a.no_bukti as bku', 'a.ket as ket', DB::raw("sum(b.nilai - isnull(pot,0)) as jumlah"), DB::raw("'2' as jns"), 'a.kd_skpd as kode')->where(['a.kd_skpd' => $kd_skpd, 'pay' => 'TUNAI'])->where('panjar', '<>', '1')->whereRaw("a.no_bukti NOT IN (SELECT no_bukti FROM trhtransout WHERE no_sp2d IN ( SELECT no_sp2d AS no_bukti FROM trhtransout WHERE kd_skpd=? GROUP BY no_sp2d HAVING COUNT ( no_sp2d ) > 1 ) AND no_kas NOT IN (SELECT MIN( z.no_kas ) AS no_bukti FROM trhtransout z WHERE z.jns_spp IN ( 4, 5, 6 ) AND kd_skpd=? GROUP BY z.no_sp2d HAVING COUNT ( z.no_sp2d ) > 1) AND jns_spp IN ( 4, 5, 6 ) AND kd_skpd=?)", [$kd_skpd, $kd_skpd, $kd_skpd])->groupBy('a.tgl_bukti', 'a.no_bukti', 'a.ket', 'a.no_sp2d', 'b.no_sp2d', 'a.total', 'pot', 'a.kd_skpd')->unionAll($data2);

        $data4 = DB::table('trhtransout')->select('tgl_bukti as tgl', 'no_bukti as bku', 'ket as ket', DB::raw("isnull(total,0) as jumlah"), DB::raw("'2' as jns"), 'kd_skpd as kode')->where(['kd_skpd' => $kd_skpd, 'pay' => 'TUNAI'])->whereIn('jns_spp', ['4', '5', '6'])->where('panjar', '<>', '1')->whereRaw("no_sp2d IN (SELECT no_sp2d AS no_bukti FROM trhtransout WHERE kd_skpd=? GROUP BY no_sp2d HAVING COUNT ( no_sp2d ) > 1)", [$kd_skpd])->whereRaw("no_kas NOT IN(SELECT MIN( z.no_kas ) AS no_bukti FROM trhtransout z WHERE z.jns_spp IN ( 4, 5, 6 ) AND kd_skpd=? GROUP BY z.no_sp2d HAVING COUNT ( z.no_sp2d ) > 1)", [$kd_skpd])->unionAll($data3);

        $data5 = DB::table('tr_setorsimpanan')->select('tgl_kas as tgl', 'no_kas as bku', 'keterangan as ket', 'nilai as jumlah', DB::raw("'2' as jns"), 'kd_skpd as kode')->where(['kd_skpd' => $kd_skpd, 'jenis' => '2'])->unionAll($data4);

        $data6 = DB::table('tr_setorpelimpahan')->select('tgl_bukti as tgl', 'no_bukti as bku', 'keterangan as ket', 'nilai as jumlah', DB::raw("'2' as jns"), 'kd_skpd_sumber as kode')->where(['kd_skpd_sumber' => $kd_skpd])->unionAll($data5);

        $data = DB::table(DB::raw("({$data6->toSql()}) AS sub"))
            ->select(DB::raw("(CASE WHEN jns=1 THEN jumlah ELSE 0 END) as terima"), DB::raw("(CASE WHEN jns=2 THEN jumlah ELSE 0 END) as keluar"))
            ->mergeBindings($data6)
            ->first();

        return response()->json($data);
    }

    public function simpanTransaksi(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $no_urut = no_urut($kd_skpd);

            // TRHTRANSOUT
            DB::table('trhtransout')->where(['no_bukti' => $no_urut, 'kd_skpd' => $kd_skpd, 'pay' => 'TUNAI'])->delete();

            DB::table('trhtransout')->insert([
                'no_kas' => $no_urut,
                'tgl_kas' => $data['tgl_bukti'],
                'no_bukti' => $no_urut,
                'tgl_bukti' => $data['tgl_bukti'],
                'ket' => $data['keterangan'],
                'username' => Auth::user()->nama,
                'tgl_update' => date('Y-m-d H:i:s'),
                'kd_skpd' => $kd_skpd,
                'nm_skpd' => $data['nm_skpd'],
                'total' => $data['total'],
                'no_tagih' => '',
                'sts_tagih' => '0',
                'tgl_tagih' => '',
                'jns_spp' => $data['beban'],
                'pay' => $data['pembayaran'],
                'no_kas_pot' => $no_urut,
                'panjar' => '0',
                'no_sp2d' => $data['sp2d'],
            ]);

            // TRDTRANSOUT
            DB::table('trdtransout')->where(['no_bukti' => $no_urut, 'kd_skpd' => $kd_skpd])->delete();

            if (isset($data['tabel_rincian'])) {
                DB::table('trdtransout')->insert(array_map(function ($value) use ($no_urut, $kd_skpd) {
                    return [
                        'no_bukti' => $no_urut,
                        'no_sp2d' => $value['no_sp2d'],
                        'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                        'nm_sub_kegiatan' => $value['nm_sub_kegiatan'],
                        'kd_rek6' => $value['kd_rek6'],
                        'nm_rek6' => $value['nm_rek6'],
                        'nilai' => $value['nilai'],
                        'kd_skpd' => $kd_skpd,
                        'sumber' => $value['sumber'],
                    ];
                }, $data['tabel_rincian']));
            }

            DB::table('tb_transaksi')
                ->where(['kd_skpd' => $kd_skpd, 'username' => Auth::user()->nama])
                ->delete();

            DB::commit();
            return response()->json([
                'message' => '1',
                'no_bukti' => $no_urut
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function hapusTransaksi(Request $request)
    {
        $no_bukti = $request->no_bukti;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trdtransout')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->delete();

            DB::table('trhtransout')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd, 'pay' => 'TUNAI'])->delete();

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

    public function editTransaksi(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            // TRHTRANSOUT
            DB::table('trhtransout')->where(['no_bukti' => $data['no_bukti'], 'kd_skpd' => $kd_skpd, 'pay' => 'TUNAI'])->delete();

            DB::table('trhtransout')->insert([
                'no_kas' => $data['no_bukti'],
                'tgl_kas' => $data['tgl_bukti'],
                'no_bukti' => $data['no_bukti'],
                'tgl_bukti' => $data['tgl_bukti'],
                'ket' => $data['keterangan'],
                'username' => Auth::user()->nama,
                'tgl_update' => date('Y-m-d H:i:s'),
                'kd_skpd' => $kd_skpd,
                'nm_skpd' => $data['nm_skpd'],
                'total' => $data['total'],
                'no_tagih' => '',
                'sts_tagih' => '0',
                'tgl_tagih' => '',
                'jns_spp' => $data['beban'],
                'pay' => $data['pembayaran'],
                'no_kas_pot' => $data['no_bukti'],
                'panjar' => '0',
                'no_sp2d' => $data['sp2d'],
            ]);

            // TRDTRANSOUT
            DB::table('trdtransout')->where(['no_bukti' => $data['no_bukti'], 'kd_skpd' => $kd_skpd])->delete();

            if (isset($data['tabel_rincian'])) {
                DB::table('trdtransout')->insert(array_map(function ($value) use ($data, $kd_skpd) {
                    return [
                        'no_bukti' => $data['no_bukti'],
                        'no_sp2d' => $value['no_sp2d'],
                        'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                        'nm_sub_kegiatan' => $value['nm_sub_kegiatan'],
                        'kd_rek6' => $value['kd_rek6'],
                        'nm_rek6' => $value['nm_rek6'],
                        'nilai' => $value['nilai'],
                        'kd_skpd' => $kd_skpd,
                        'sumber' => $value['sumber'],
                    ];
                }, $data['tabel_rincian']));
            }

            DB::commit();
            return response()->json([
                'message' => '1',
                'no_bukti' => $data['no_bukti']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function edit($no_bukti)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_bukti = Crypt::decryptString($no_bukti);

        $data = [
            'transaksi' => DB::table('trhtransout as a')->join('trdtransout as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->select('a.*')->where(['a.kd_skpd' => $kd_skpd, 'a.no_bukti' => $no_bukti, 'a.pay' => 'TUNAI'])->first(),
            'daftar_transaksi' => DB::table('trdtransout as a')->join('trhtransout as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->select('a.*')->where(['a.kd_skpd' => $kd_skpd, 'a.no_bukti' => $no_bukti, 'b.pay' => 'TUNAI'])->get(),
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_kegiatan' => DB::table('trdrka as a')->select('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan', DB::raw("SUM(a.nilai) as total"))->where(['a.kd_skpd' => $kd_skpd])->whereRaw("left(a.kd_rek6,1)=?", ['5'])->groupBy('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan')->orderBy('a.kd_sub_kegiatan')->orderBy('a.nm_sub_kegiatan')->get(),
            'persen' => DB::table('config_app')->select('persen_kkpd', 'persen_tunai')->first(),
        ];
        return view('skpd.transaksi_tunai.edit')->with($data);
    }
}
