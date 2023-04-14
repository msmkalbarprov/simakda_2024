<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PenagihanController extends Controller
{
    public function index()
    {
        $data = [
            'cek' => selisih_angkas()
        ];

        return view('penatausahaan.pengeluaran.penagihan.index')->with($data);
    }

    public function loadData()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhtagih as a')
            ->select('a.*', DB::raw("(SELECT COUNT(*) FROM trhspp WHERE no_tagih=a.no_bukti) as jumlah_spp"))
            ->where(['kd_skpd' => $kd_skpd])
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("penagihan.show", Crypt::encryptString($row->no_bukti)) . '" class="btn btn-info btn-sm" style="margin-right:4px"><i class="fas fa-info-circle"></i></a>';
            $btn .= '<a href="' . route("penagihan.edit", Crypt::encryptString($row->no_bukti)) . '" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="fa fa-edit"></i></a>';
            if ($row->jumlah_spp > 0) {
                $btn .= '';
            } else {
                $btn .= '<a href="javascript:void(0);" onclick="deleteData(\'' . $row->no_bukti . '\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
            }
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function create()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $status_anggaran = DB::table('trhrka')->select('jns_ang')->where(['kd_skpd' => $kd_skpd, 'status' => 1])->orderByDesc('tgl_dpa')->first();
        $data = [
            'data_penagihan' => DB::table('trhtagih')->get(),
            'kd_skpd' => $kd_skpd,
            'skpd' => DB::table('ms_skpd')->select('nm_skpd')->where('kd_skpd', $kd_skpd)->first(),
            'daftar_kontrak' => DB::table('ms_kontrak as z')->where('z.kd_skpd', $kd_skpd)
                ->select('z.no_kontrak', 'z.nilai', DB::raw("(SELECT SUM(nilai) FROM trhtagih a INNER JOIN trdtagih b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE kontrak=z.no_kontrak) as lalu"))->orderBy('z.no_kontrak', 'ASC')->get(),
            'daftar_rekanan' => DB::table('ms_rekening_bank_online')->where('kd_skpd', $kd_skpd)->orderBy('rekening', 'ASC')->get(),
            'daftar_sub_kegiatan' => DB::table('trskpd as a')
                ->select('a.total', 'a.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'a.kd_program', DB::raw("(SELECT nm_program FROM ms_program WHERE kd_program=a.kd_program) as nm_program"))
                ->join('ms_sub_kegiatan AS b', 'a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan')
                ->where(['a.kd_skpd' => $kd_skpd, 'a.status_sub_kegiatan' => '1', 'a.jns_ang' => $status_anggaran->jns_ang, 'b.jns_sub_kegiatan' => '5'])->get()
        ];


        return view('penatausahaan.pengeluaran.penagihan.create')->with($data);
    }

    public function show($no_bukti)
    {
        $no_bukti = Crypt::decryptString($no_bukti);
        $data_tagih = DB::table('trhtagih')->where('no_bukti', $no_bukti)->first();
        $data = [
            'data_tagih' => DB::table('trhtagih as a')->join('trdtagih as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->where('a.no_bukti', $no_bukti)->first(),
            'detail_tagih' => DB::table('trdtagih as a')->select('a.*')->join('trhtagih as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->where('a.no_bukti', $no_bukti)->get(),
            'kontrak' => DB::table('ms_kontrak')->where('no_kontrak', $data_tagih->kontrak)->first(),
        ];

        return view('penatausahaan.pengeluaran.penagihan.show')->with($data);
    }

    public function cariRekening(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $status_anggaran = DB::table('trhrka')
            ->select('jns_ang')
            ->where(['kd_skpd' => $kd_skpd, 'status' => 1])
            ->orderBy('tgl_dpa', 'DESC')
            ->first();

        // $daftar_rekening = DB::table('trdrka as a')
        //     ->leftJoin('ms_rek6 as e', 'a.kd_rek6', '=', 'e.kd_rek6')
        //     ->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.jns_ang' => $status_anggaran->jns_ang, 'a.kd_skpd' => $kd_skpd, 'a.status_aktif' => '1'])
        //     ->selectRaw("a.kd_rek6,a.nm_rek6,e.map_lo,(SELECT SUM(nilai) FROM
        //                 (SELECT
        //                     SUM (c.nilai) as nilai
        //                 FROM
        //                     trdtransout c
        //                 LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
        //                 AND c.kd_skpd = d.kd_skpd
        //                 WHERE
        //                     c.kd_sub_kegiatan = a.kd_sub_kegiatan
        //                 AND d.kd_skpd = a.kd_skpd
        //                 AND c.kd_rek6 = a.kd_rek6
        //                 AND d.jns_spp='1'
        //                 UNION ALL
        //                 SELECT SUM(x.nilai) as nilai FROM trdspp x
        //                 INNER JOIN trhspp y
        //                 ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
        //                 WHERE
        //                     x.kd_sub_kegiatan = a.kd_sub_kegiatan
        //                 AND x.kd_skpd = a.kd_skpd
        //                 AND x.kd_rek6 = a.kd_rek6
        //                 AND y.jns_spp IN ('3','4','5','6')
        //                 AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')

        //                 UNION ALL
        //                 SELECT SUM(nilai) as nilai FROM trdtagih t
        //                 INNER JOIN trhtagih u
        //                 ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
        //                 WHERE
        //                 t.kd_sub_kegiatan = a.kd_sub_kegiatan
        //                 AND u.kd_skpd = a.kd_skpd
        //                 AND t.kd_rek = a.kd_rek6
        //                 AND u.no_bukti
        //                 NOT IN (select no_tagih FROM trhspp WHERE kd_skpd=? )

        //                 -- tambahan tampungan
        //                 UNION ALL
        //                 SELECT SUM(nilai) as nilai FROM tb_transaksi
        //                 WHERE
        //                 kd_sub_kegiatan = a.kd_sub_kegiatan
        //                 AND kd_skpd = a.kd_skpd
        //                 AND kd_rek6 = a.kd_rek6
        //                 -- tambahan tampungan
        //                 )r) AS lalu,
        //             0 AS sp2d,a.nilai AS anggaran", [$kd_skpd])
        //     ->get();

        $daftar_rekening = DB::select("SELECT a.kd_rek6,a.nm_rek6,e.map_lo,
                      (SELECT SUM(nilai) FROM
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                            c.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND d.kd_skpd = a.kd_skpd
                        AND c.kd_rek6 = a.kd_rek6
                        AND d.jns_spp='1'
                        UNION ALL
                        SELECT SUM(x.nilai) as nilai FROM trdspp x
                        INNER JOIN trhspp y
                        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                        WHERE
                            x.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND x.kd_skpd = a.kd_skpd
                        AND x.kd_rek6 = a.kd_rek6
                        AND y.jns_spp IN ('3','4','5','6')
                        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')

                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t
                        INNER JOIN trhtagih u
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE
                        t.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek6
                        AND u.no_bukti
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd=? )

                        -- tambahan tampungan
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM tb_transaksi
                        WHERE
                        kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND kd_skpd = a.kd_skpd
                        AND kd_rek6 = a.kd_rek6
                        -- tambahan tampungan
                        )r) AS lalu,
                    0 AS sp2d,a.nilai AS anggaran
                      FROM trdrka a LEFT JOIN ms_rek6 e ON a.kd_rek6=e.kd_rek6
                      WHERE a.kd_sub_kegiatan= ? AND jns_ang=? AND a.kd_skpd = ? and a.status_aktif='1'", [$kd_skpd, $kd_sub_kegiatan, $status_anggaran->jns_ang, $kd_skpd]);

        return response()->json($daftar_rekening);
    }

    public function cekStatusAngNew(Request $request)
    {
        if ($request->ajax()) {
            $skpd       = Auth::user()->kd_skpd;
            $tgl_bukti = $request->tgl_bukti;
            $data = DB::table('trhrka as a')->join('tb_status_anggaran as b', 'a.jns_ang', '=', 'b.kode')->select('nama', 'jns_ang')->where(['a.kd_skpd' => $skpd, 'status' => '1'])->where('tgl_dpa', '<', $tgl_bukti)->orderBy('tgl_dpa', 'DESC')->first();
            return response()->json($data);
        }
    }

    public function cekStatusAng(Request $request)
    {
        if ($request->ajax()) {
            $skpd       = Auth::user()->kd_skpd;
            $tgl_bukti = $request->tgl_bukti;
            $urut1 = DB::table('status_angkas')->select(DB::raw("'1' AS urut"), DB::raw("'murni' AS status"), 'murni as nilai')->where(['kd_skpd' => $skpd, 'murni' => '1']);
            $urut2 = DB::table('status_angkas')->select(DB::raw("'2' AS urut"), DB::raw("'murni_geser1' AS status"), 'murni_geser1 as nilai')->where(['kd_skpd' => $skpd, 'murni_geser1' => '1'])->unionAll($urut1);
            $urut3 = DB::table('status_angkas')->select(DB::raw("'3' AS urut"), DB::raw("'murni_geser2' AS status"), 'murni_geser2 as nilai')->where(['kd_skpd' => $skpd, 'murni_geser2' => '1'])->unionAll($urut2);
            $urut4 = DB::table('status_angkas')->select(DB::raw("'4' AS urut"), DB::raw("'murni_geser3' AS status"), 'murni_geser3 as nilai')->where(['kd_skpd' => $skpd, 'murni_geser3' => '1'])->unionAll($urut3);
            $urut5 = DB::table('status_angkas')->select(DB::raw("'5' AS urut"), DB::raw("'murni_geser4' AS status"), 'murni_geser4 as nilai')->where(['kd_skpd' => $skpd, 'murni_geser4' => '1'])->unionAll($urut4);
            $urut6 = DB::table('status_angkas')->select(DB::raw("'6' AS urut"), DB::raw("'murni_geser5' AS status"), 'murni_geser5 as nilai')->where(['kd_skpd' => $skpd, 'murni_geser5' => '1'])->unionAll($urut5);
            $urut7 = DB::table('status_angkas')->select(DB::raw("'7' AS urut"), DB::raw("'sempurna1' AS status"), 'sempurna1 as nilai')->where(['kd_skpd' => $skpd, 'sempurna1' => '1'])->unionAll($urut6);
            $urut8 = DB::table('status_angkas')->select(DB::raw("'8' AS urut"), DB::raw("'sempurna1_geser1' AS status"), 'sempurna1_geser1 as nilai')->where(['kd_skpd' => $skpd, 'sempurna1_geser1' => '1'])->unionAll($urut7);
            $urut9 = DB::table('status_angkas')->select(DB::raw("'9' AS urut"), DB::raw("'sempurna1_geser2' AS status"), 'sempurna1_geser2 as nilai')->where(['kd_skpd' => $skpd, 'sempurna1_geser2' => '1'])->unionAll($urut8);
            $urut10 = DB::table('status_angkas')->select(DB::raw("'10' AS urut"), DB::raw("'sempurna1_geser3' AS status"), 'sempurna1_geser3 as nilai')->where(['kd_skpd' => $skpd, 'sempurna1_geser3' => '1'])->unionAll($urut9);
            $urut11 = DB::table('status_angkas')->select(DB::raw("'11' AS urut"), DB::raw("'sempurna1_geser4' AS status"), 'sempurna1_geser4 as nilai')->where(['kd_skpd' => $skpd, 'sempurna1_geser4' => '1'])->unionAll($urut10);
            $urut12 = DB::table('status_angkas')->select(DB::raw("'12' AS urut"), DB::raw("'sempurna1_geser5' AS status"), 'sempurna1_geser5 as nilai')->where(['kd_skpd' => $skpd, 'sempurna1_geser5' => '1'])->unionAll($urut11);
            $urut13 = DB::table('status_angkas')->select(DB::raw("'13' AS urut"), DB::raw("'sempurna2' AS status"), 'sempurna2 as nilai')->where(['kd_skpd' => $skpd, 'sempurna2' => '1'])->unionAll($urut12);
            $urut14 = DB::table('status_angkas')->select(DB::raw("'14' AS urut"), DB::raw("'sempurna2_geser1' AS status"), 'sempurna2_geser1 as nilai')->where(['kd_skpd' => $skpd, 'sempurna2_geser1' => '1'])->unionAll($urut13);
            $urut15 = DB::table('status_angkas')->select(DB::raw("'15' AS urut"), DB::raw("'sempurna2_geser2' AS status"), 'sempurna2_geser2 as nilai')->where(['kd_skpd' => $skpd, 'sempurna2_geser2' => '1'])->unionAll($urut14);
            $urut16 = DB::table('status_angkas')->select(DB::raw("'16' AS urut"), DB::raw("'sempurna2_geser3' AS status"), 'sempurna2_geser3 as nilai')->where(['kd_skpd' => $skpd, 'sempurna2_geser3' => '1'])->unionAll($urut15);
            $urut17 = DB::table('status_angkas')->select(DB::raw("'17' AS urut"), DB::raw("'sempurna2_geser4' AS status"), 'sempurna2_geser4 as nilai')->where(['kd_skpd' => $skpd, 'sempurna2_geser4' => '1'])->unionAll($urut16);
            $urut18 = DB::table('status_angkas')->select(DB::raw("'18' AS urut"), DB::raw("'sempurna2_geser5' AS status"), 'sempurna2_geser5 as nilai')->where(['kd_skpd' => $skpd, 'sempurna2_geser5' => '1'])->unionAll($urut17);
            $urut19 = DB::table('status_angkas')->select(DB::raw("'19' AS urut"), DB::raw("'sempurna3' AS status"), 'sempurna3 as nilai')->where(['kd_skpd' => $skpd, 'sempurna3' => '1'])->unionAll($urut18);
            $urut20 = DB::table('status_angkas')->select(DB::raw("'20' AS urut"), DB::raw("'sempurna3_geser1' AS status"), 'sempurna3_geser1 as nilai')->where(['kd_skpd' => $skpd, 'sempurna3_geser1' => '1'])->unionAll($urut19);
            $urut21 = DB::table('status_angkas')->select(DB::raw("'21' AS urut"), DB::raw("'sempurna3_geser2' AS status"), 'sempurna3_geser2 as nilai')->where(['kd_skpd' => $skpd, 'sempurna3_geser2' => '1'])->unionAll($urut20);
            $urut22 = DB::table('status_angkas')->select(DB::raw("'22' AS urut"), DB::raw("'sempurna3_geser3' AS status"), 'sempurna3_geser3 as nilai')->where(['kd_skpd' => $skpd, 'sempurna3_geser3' => '1'])->unionAll($urut21);
            $urut23 = DB::table('status_angkas')->select(DB::raw("'23' AS urut"), DB::raw("'sempurna3_geser4' AS status"), 'sempurna3_geser4 as nilai')->where(['kd_skpd' => $skpd, 'sempurna3_geser4' => '1'])->unionAll($urut22);
            $urut24 = DB::table('status_angkas')->select(DB::raw("'24' AS urut"), DB::raw("'sempurna3_geser5' AS status"), 'sempurna3_geser5 as nilai')->where(['kd_skpd' => $skpd, 'sempurna3_geser5' => '1'])->unionAll($urut23);
            $urut25 = DB::table('status_angkas')->select(DB::raw("'25' AS urut"), DB::raw("'sempurna4' AS status"), 'sempurna4 as nilai')->where(['kd_skpd' => $skpd, 'sempurna4' => '1'])->unionAll($urut24);
            $urut26 = DB::table('status_angkas')->select(DB::raw("'26' AS urut"), DB::raw("'sempurna4_geser1' AS status"), 'sempurna4_geser1 as nilai')->where(['kd_skpd' => $skpd, 'sempurna4_geser1' => '1'])->unionAll($urut25);
            $urut27 = DB::table('status_angkas')->select(DB::raw("'27' AS urut"), DB::raw("'sempurna4_geser2' AS status"), 'sempurna4_geser2 as nilai')->where(['kd_skpd' => $skpd, 'sempurna4_geser2' => '1'])->unionAll($urut26);
            $urut28 = DB::table('status_angkas')->select(DB::raw("'28' AS urut"), DB::raw("'sempurna4_geser3' AS status"), 'sempurna4_geser3 as nilai')->where(['kd_skpd' => $skpd, 'sempurna4_geser3' => '1'])->unionAll($urut27);
            $urut29 = DB::table('status_angkas')->select(DB::raw("'29' AS urut"), DB::raw("'sempurna4_geser4' AS status"), 'sempurna4_geser4 as nilai')->where(['kd_skpd' => $skpd, 'sempurna4_geser4' => '1'])->unionAll($urut28);
            $urut30 = DB::table('status_angkas')->select(DB::raw("'30' AS urut"), DB::raw("'sempurna4_geser5' AS status"), 'sempurna4_geser5 as nilai')->where(['kd_skpd' => $skpd, 'sempurna4_geser5' => '1'])->unionAll($urut29);
            $urut31 = DB::table('status_angkas')->select(DB::raw("'31' AS urut"), DB::raw("'sempurna5' AS status"), 'sempurna5 as nilai')->where(['kd_skpd' => $skpd, 'sempurna5' => '1'])->unionAll($urut30);
            $urut32 = DB::table('status_angkas')->select(DB::raw("'32' AS urut"), DB::raw("'sempurna5_geser1' AS status"), 'sempurna5_geser1 as nilai')->where(['kd_skpd' => $skpd, 'sempurna5_geser1' => '1'])->unionAll($urut31);
            $urut33 = DB::table('status_angkas')->select(DB::raw("'33' AS urut"), DB::raw("'sempurna5_geser2' AS status"), 'sempurna5_geser2 as nilai')->where(['kd_skpd' => $skpd, 'sempurna5_geser2' => '1'])->unionAll($urut32);
            $urut34 = DB::table('status_angkas')->select(DB::raw("'34' AS urut"), DB::raw("'sempurna5_geser3' AS status"), 'sempurna5_geser3 as nilai')->where(['kd_skpd' => $skpd, 'sempurna5_geser3' => '1'])->unionAll($urut33);
            $urut35 = DB::table('status_angkas')->select(DB::raw("'35' AS urut"), DB::raw("'sempurna5_geser4' AS status"), 'sempurna5_geser4 as nilai')->where(['kd_skpd' => $skpd, 'sempurna5_geser4' => '1'])->unionAll($urut34);
            $urut36 = DB::table('status_angkas')->select(DB::raw("'36' AS urut"), DB::raw("'sempurna5_geser5' AS status"), 'sempurna5_geser5 as nilai')->where(['kd_skpd' => $skpd, 'sempurna5_geser5' => '1'])->unionAll($urut35);
            $urut37 = DB::table('status_angkas')->select(DB::raw("'37' AS urut"), DB::raw("'ubah' AS status"), 'ubah as nilai')->where(['kd_skpd' => $skpd, 'ubah' => '1'])->unionAll($urut36);
            $urut38 = DB::table('status_angkas')->select(DB::raw("'38' AS urut"), DB::raw("'ubah11' AS status"), 'ubah11 as nilai')->where(['kd_skpd' => $skpd, 'ubah11' => '1'])->unionAll($urut37);
            $urut39 = DB::table('status_angkas')->select(DB::raw("'39' AS urut"), DB::raw("'ubah12' AS status"), 'ubah12 as nilai')->where(['kd_skpd' => $skpd, 'ubah12' => '1'])->unionAll($urut38);
            $urut40 = DB::table('status_angkas')->select(DB::raw("'40' AS urut"), DB::raw("'ubah13' AS status"), 'ubah13 as nilai')->where(['kd_skpd' => $skpd, 'ubah13' => '1'])->unionAll($urut39);
            $urut41 = DB::table('status_angkas')->select(DB::raw("'41' AS urut"), DB::raw("'ubah14' AS status"), 'ubah14 as nilai')->where(['kd_skpd' => $skpd, 'ubah14' => '1'])->unionAll($urut40);
            $urut42 = DB::table('status_angkas')->select(DB::raw("'42' AS urut"), DB::raw("'ubah15' AS status"), 'ubah15 as nilai')->where(['kd_skpd' => $skpd, 'ubah15' => '1'])->unionAll($urut41);
            $urut43 = DB::table('status_angkas')->select(DB::raw("'43' AS urut"), DB::raw("'ubah2' AS status"), 'ubah2 as nilai')->where(['kd_skpd' => $skpd, 'ubah2' => '1'])->unionAll($urut42);
            $urut44 = DB::table('status_angkas')->select(DB::raw("'44' AS urut"), DB::raw("'ubah21' AS status"), 'ubah21 as nilai')->where(['kd_skpd' => $skpd, 'ubah21' => '1'])->unionAll($urut43);
            $urut45 = DB::table('status_angkas')->select(DB::raw("'45' AS urut"), DB::raw("'ubah22' AS status"), 'ubah22 as nilai')->where(['kd_skpd' => $skpd, 'ubah22' => '1'])->unionAll($urut44);
            $urut46 = DB::table('status_angkas')->select(DB::raw("'46' AS urut"), DB::raw("'ubah23' AS status"), 'ubah23 as nilai')->where(['kd_skpd' => $skpd, 'ubah23' => '1'])->unionAll($urut45);
            $urut47 = DB::table('status_angkas')->select(DB::raw("'47' AS urut"), DB::raw("'ubah24' AS status"), 'ubah24 as nilai')->where(['kd_skpd' => $skpd, 'ubah24' => '1'])->unionAll($urut46);
            $urut48 = DB::table('status_angkas')->select(DB::raw("'48' AS urut"), DB::raw("'ubah25' AS status"), 'ubah25 as nilai')->where(['kd_skpd' => $skpd, 'ubah25' => '1'])->unionAll($urut47);
            $urut49 = DB::table('status_angkas')->select(DB::raw("'49' AS urut"), DB::raw("'ubah3' AS status"), 'ubah3 as nilai')->where(['kd_skpd' => $skpd, 'ubah3' => '1'])->unionAll($urut48);
            $urut50 = DB::table('status_angkas')->select(DB::raw("'50' AS urut"), DB::raw("'ubah31' AS status"), 'ubah31 as nilai')->where(['kd_skpd' => $skpd, 'ubah31' => '1'])->unionAll($urut49);
            $urut51 = DB::table('status_angkas')->select(DB::raw("'51' AS urut"), DB::raw("'ubah32' AS status"), 'ubah32 as nilai')->where(['kd_skpd' => $skpd, 'ubah32' => '1'])->unionAll($urut50);
            $urut52 = DB::table('status_angkas')->select(DB::raw("'52' AS urut"), DB::raw("'ubah33' AS status"), 'ubah33 as nilai')->where(['kd_skpd' => $skpd, 'ubah33' => '1'])->unionAll($urut51);
            $urut53 = DB::table('status_angkas')->select(DB::raw("'53' AS urut"), DB::raw("'ubah34' AS status"), 'ubah34 as nilai')->where(['kd_skpd' => $skpd, 'ubah34' => '1'])->unionAll($urut52);
            $urut54 = DB::table('status_angkas')->select(DB::raw("'54' AS urut"), DB::raw("'ubah35' AS status"), 'ubah35 as nilai')->where(['kd_skpd' => $skpd, 'ubah35' => '1'])->unionAll($urut53);
            $result = DB::table(DB::raw("({$urut54->toSql()}) AS sub"))
                ->select("urut", "status", "nilai")
                ->mergeBindings($urut54)
                ->orderByRaw("CAST(urut AS INT) DESC")
                ->first();
            return response()->json($result);
        }
    }

    public function cariSumberDana(Request $request)
    {
        $kode               = $request->skpd;
        $giat               = $request->kdgiat;
        $rek                = $request->kdrek;
        $tgl_bukti                = $request->tgl_bukti;
        $sts_angkas                = $request->status_angkas;
        $status             = DB::table('trhrka')->where(['kd_skpd' => $kode, 'status' => '1'])->orderByDesc('tgl_dpa')->first();
        $status_anggaran    = $status->jns_ang;

        $bulan = date('m', strtotime($tgl_bukti));
        // SUMBER DANA
        $no_trdrka = $kode . '.' . $giat . '.' . $rek;

        $data1 = DB::table('trdpo')
            ->select('sumber', 'nm_sumber', DB::raw("SUM(total) as nilai"))
            ->where(['no_trdrka' => $no_trdrka, 'jns_ang' => $status_anggaran])
            ->whereNotNull('sumber')
            ->groupBy('sumber', 'nm_sumber');

        $data2 = DB::table('trdpo')
            ->select('sumber', DB::raw("'Silahkan isi sumber di anggaran' as nm_sumber"), DB::raw("SUM(total) as nilai"))
            ->where(['no_trdrka' => $no_trdrka, 'jns_ang' => $status_anggaran])
            ->where(function ($query) {
                $query->where('sumber', '')->orWhereNull('sumber');
            })
            ->groupBy('sumber', 'nm_sumber')
            ->union($data1);

        $data = DB::table(DB::raw("({$data2->toSql()}) AS sub"))
            ->mergeBindings($data2)
            ->get();

        // ANGKAS
        if ($sts_angkas == 'murni') {
            $field_angkas = 'nilai_susun';
        } else if ($sts_angkas == 'murni_geser1') {
            $field_angkas = 'nilai_susun1';
        } else if ($sts_angkas == 'murni_geser2') {
            $field_angkas = 'nilai_susun2';
        } else if ($sts_angkas == 'murni_geser3') {
            $field_angkas = 'nilai_susun3';
        } else if ($sts_angkas == 'murni_geser4') {
            $field_angkas = 'nilai_susun4';
        } else if ($sts_angkas == 'murni_geser5') {
            $field_angkas = 'nilai_susun5';
        } else if ($sts_angkas == 'sempurna1') {
            $field_angkas = 'nilai_sempurna';
        } else if ($sts_angkas == 'sempurna1_geser1') {
            $field_angkas = 'nilai_sempurna11';
        } else if ($sts_angkas == 'sempurna1_geser2') {
            $field_angkas = 'nilai_sempurna12';
        } else if ($sts_angkas == 'sempurna1_geser3') {
            $field_angkas = 'nilai_sempurna13';
        } else if ($sts_angkas == 'sempurna1_geser4') {
            $field_angkas = 'nilai_sempurna14';
        } else if ($sts_angkas == 'sempurna1_geser5') {
            $field_angkas = 'nilai_sempurna15';
        } else if ($sts_angkas == 'sempurna2') {
            $field_angkas = 'nilai_sempurna2';
        } else if ($sts_angkas == 'sempurna2_geser1') {
            $field_angkas = 'nilai_sempurna21';
        } else if ($sts_angkas == 'sempurna2_geser2') {
            $field_angkas = 'nilai_sempurna22';
        } else if ($sts_angkas == 'sempurna2_geser3') {
            $field_angkas = 'nilai_sempurna23';
        } else if ($sts_angkas == 'sempurna2_geser4') {
            $field_angkas = 'nilai_sempurna24';
        } else if ($sts_angkas == 'sempurna2_geser5') {
            $field_angkas = 'nilai_sempurna25';
        } else if ($sts_angkas == 'sempurna3') {
            $field_angkas = 'nilai_sempurna3';
        } else if ($sts_angkas == 'sempurna3_geser1') {
            $field_angkas = 'nilai_sempurna31';
        } else if ($sts_angkas == 'sempurna3_geser2') {
            $field_angkas = 'nilai_sempurna32';
        } else if ($sts_angkas == 'sempurna3_geser3') {
            $field_angkas = 'nilai_sempurna33';
        } else if ($sts_angkas == 'sempurna3_geser4') {
            $field_angkas = 'nilai_sempurna34';
        } else if ($sts_angkas == 'sempurna3_geser5') {
            $field_angkas = 'nilai_sempurna35';
        } else if ($sts_angkas == 'sempurna4') {
            $field_angkas = 'nilai_sempurna4';
        } else if ($sts_angkas == 'sempurna4_geser1') {
            $field_angkas = 'nilai_sempurna41';
        } else if ($sts_angkas == 'sempurna4_geser2') {
            $field_angkas = 'nilai_sempurna42';
        } else if ($sts_angkas == 'sempurna4_geser3') {
            $field_angkas = 'nilai_sempurna43';
        } else if ($sts_angkas == 'sempurna4_geser4') {
            $field_angkas = 'nilai_sempurna44';
        } else if ($sts_angkas == 'sempurna4_geser5') {
            $field_angkas = 'nilai_sempurna45';
        } else if ($sts_angkas == 'sempurna5') {
            $field_angkas = 'nilai_sempurna5';
        } else if ($sts_angkas == 'sempurna5_geser1') {
            $field_angkas = 'nilai_sempurna51';
        } else if ($sts_angkas == 'sempurna5_geser2') {
            $field_angkas = 'nilai_sempurna52';
        } else if ($sts_angkas == 'sempurna5_geser3') {
            $field_angkas = 'nilai_sempurna53';
        } else if ($sts_angkas == 'sempurna5_geser4') {
            $field_angkas = 'nilai_sempurna1';
        } else if ($sts_angkas == 'sempurna5_geser5') {
            $field_angkas = 'nilai_sempurna55';
        } else if ($sts_angkas == 'ubah') {
            $field_angkas = 'nilai_ubah';
        } else if ($sts_angkas == 'ubah1') {
            $field_angkas = 'nilai_ubah1';
        } else if ($sts_angkas == 'ubah2') {
            $field_angkas = 'nilai_ubah2';
        } else if ($sts_angkas == 'ubah3') {
            $field_angkas = 'nilai_ubah3';
        } else if ($sts_angkas == 'ubah4') {
            $field_angkas = 'nilai_ubah4';
        } else {
            $field_angkas = 'nilai_ubah5';
        }

        $angkas = collect(DB::select("SELECT  a.kd_sub_kegiatan, SUM(a.$field_angkas) as nilai FROM trdskpd_ro a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan
            where a.kd_skpd = ? and  a.kd_sub_kegiatan = ? and a.kd_rek6=? and bulan <= ? and jns_ang=? GROUP BY a.kd_sub_kegiatan,a.kd_rek6", [$kode, $giat, $rek, $bulan, $status_anggaran]))->first();


        // ANGKAS LALU
        $angkas_lalu = collect(DB::select("SELECT SUM(nilai) total FROM
                                    (
                                         --Table tampungan

                                    SELECT SUM (isnull(nilai,0)) as nilai
                                    FROM tb_transaksi
                                    WHERE kd_sub_kegiatan = ?
                                    AND kd_skpd = ?
                                    AND kd_rek6 = ?
                                    UNION ALL
                                    -- transaksi UP/GU
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout c
                                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan = ?
                                    AND d.kd_skpd = ?
                                    AND c.kd_rek6 = ?
                                    and d.tgl_bukti<=?
                                    AND d.jns_spp in ('1')

                                    UNION ALL
                                    -- transaksi UP/GU CMS BANK Belum Validasi
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout_cmsbank c
                                    LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan =?
                                    AND d.kd_skpd = ?
                                    AND c.kd_rek6=?
                                    and d.tgl_voucher<=?
                                    AND d.jns_spp in ('1')
                                    AND (d.status_validasi='0' OR d.status_validasi is null)

                                    UNION ALL
                                    -- transaksi SPP SELAIN UP/GU
                                    SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
                                    INNER JOIN trhspp y
                                    ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                                    WHERE x.kd_sub_kegiatan = ?
                                    AND x.kd_skpd = ?
                                    AND x.kd_rek6 = ?
                                    and y.tgl_spp <=?
                                    AND y.jns_spp IN ('3','4','5','6')
                                    AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')

                                    UNION ALL
                                    -- Penagihan yang belum jadi SPP
                                    SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t
                                    INNER JOIN trhtagih u
                                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                    WHERE t.kd_sub_kegiatan =?
                                    AND t.kd_rek =?
                                    AND u.kd_skpd = ?
                                    and u.tgl_bukti<=?
                                    AND u.no_bukti
                                    NOT IN (select no_tagih FROM trhspp WHERE kd_skpd=?)
                                    )r", [$giat, $kode, $rek, $giat, $kode, $rek, $tgl_bukti, $giat, $kode, $rek, $tgl_bukti, $giat, $kode, $rek, $tgl_bukti, $giat, $rek, $kode, $tgl_bukti, $kode]))->first();


        // SPD
        $revisi1 = collect(DB::select("SELECT max(revisi_ke) as revisi from trhspd where
                                left(kd_skpd,17)=left(?,17)
                                and bulan_akhir='3'", [$kode]))->first();

        $revisi2 = collect(DB::select("SELECT isnull(max(revisi_ke),0) as revisi from trhspd where
                                left(kd_skpd,17)=left(?,17)
                                and bulan_akhir='6'", [$kode]))->first();

        $revisi3 = collect(DB::select("SELECT isnull(max(revisi_ke),0) as revisi from trhspd where
                                left(kd_skpd,17)=left(?,17)
                                and bulan_akhir='9'", [$kode]))->first();

        $revisi4 = collect(DB::select("SELECT isnull(max(revisi_ke),0) as revisi from trhspd where
                                left(kd_skpd,17)=left(?,17)
                                and bulan_akhir='12'", [$kode]))->first();

        $spd = collect(DB::select("SELECT sum(nilai)as total_spd from (
                    SELECT
                    'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = ?
                    AND a.kd_sub_kegiatan = ?
                    AND a.kd_rek6 = ?
                    AND b.status = '1'
                    and bulan_akhir='3'
                    and revisi_ke=?
                    UNION ALL
                    SELECT
                    'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = ?
                    AND a.kd_sub_kegiatan = ?
                    AND a.kd_rek6 = ?
                    AND b.status = '1'
                    and bulan_akhir='6'
                    and revisi_ke=?
                    UNION ALL
                    SELECT
                    'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = ?
                    AND a.kd_sub_kegiatan = ?
                    AND a.kd_rek6 = ?
                    AND b.status = '1'
                    and bulan_akhir='9'
                    and revisi_ke=?
                    UNION ALL
                    SELECT
                    'TW4' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = ?
                    AND a.kd_sub_kegiatan = ?
                    AND a.kd_rek6 = ?
                    AND b.status = '1'
                    and bulan_akhir='12'
                    and revisi_ke=?)spd", [$kode, $giat, $rek, $revisi1->revisi, $kode, $giat, $rek, $revisi2->revisi, $kode, $giat, $rek, $revisi3->revisi, $kode, $giat, $rek, $revisi4->revisi,]))->first();

        return response()->json([
            'sumber' => $data,
            'angkas' => $angkas->nilai,
            'angkas_lalu' => $angkas_lalu->total,
            'spd' => $spd->total_spd
        ]);
    }

    public function cariSumberDanaTunai(Request $request)
    {
        $kode               = $request->skpd;
        $giat               = $request->kdgiat;
        $rek                = $request->kdrek;
        $tgl_voucher                = $request->tgl_voucher;
        $beban                = $request->beban;
        $status_angkas                = $request->status_angkas;
        $status             = DB::table('trhrka')->where(['kd_skpd' => $kode, 'status' => '1'])->orderByDesc('tgl_dpa')->first();
        $status_anggaran    = $status->jns_ang;

        // SUMBER DANA
        $no_trdrka = $kode . '.' . $giat . '.' . $rek;

        $data1 = DB::table('trdpo')
            ->select('sumber', 'nm_sumber', DB::raw("SUM(total) as nilai"))
            ->where(['no_trdrka' => $no_trdrka, 'jns_ang' => $status_anggaran])
            ->whereNotNull('sumber')
            ->groupBy('sumber', 'nm_sumber');

        $data2 = DB::table('trdpo')
            ->select('sumber', DB::raw("'Silahkan isi sumber di anggaran' as nm_sumber"), DB::raw("SUM(total) as nilai"))
            ->where(['no_trdrka' => $no_trdrka, 'jns_ang' => $status_anggaran])
            ->where(function ($query) {
                $query->where('sumber', '')->orWhereNull('sumber');
            })
            ->groupBy('sumber', 'nm_sumber')
            ->union($data1);

        $data = DB::table(DB::raw("({$data2->toSql()}) AS sub"))
            ->mergeBindings($data2)
            ->get();

        // ANGKAS
        $bulan = date('m', strtotime($tgl_voucher));
        $bulan1 = 0;
        $angkas = field_angkas($status_angkas);
        $jenis_ang = status_anggaran();

        if ($beban == '4' || substr($giat, 5, 10) == '01.1.02.01') {
            $bulan1 = $bulan + 1;
            $angkas = DB::table('trdskpd_ro as a')->join('trskpd as b', function ($join) {
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
            })->where(['a.kd_skpd' => $kode, 'a.kd_sub_kegiatan' => $giat, 'kd_rek6' => $rek, 'jns_ang' => $jenis_ang])->where('bulan', '<=', $bulan1)->groupBy('a.kd_sub_kegiatan', 'a.kd_rek6')->select('a.kd_sub_kegiatan', DB::raw("SUM(a.$angkas) as nilai"))->first();
        } else {
            $angkas = DB::table('trdskpd_ro as a')->join('trskpd as b', function ($join) {
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
            })->where(['a.kd_skpd' => $kode, 'a.kd_sub_kegiatan' => $giat, 'kd_rek6' => $rek, 'jns_ang' => $jenis_ang])->where('bulan', '<=', $bulan)->groupBy('a.kd_sub_kegiatan', 'a.kd_rek6')->select('a.kd_sub_kegiatan', DB::raw("SUM(a.$angkas) as nilai"))->first();
        }

        // ANGKAS LALU
        if ($beban == '1') {
            $data1 = DB::table('trdtransout as c')->join('trhtransout as d', function ($join) {
                $join->on('c.no_bukti', '=', 'd.no_bukti');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })->where(['c.kd_sub_kegiatan' => $giat, 'd.kd_skpd' => $kode, 'c.kd_rek6' => $rek, 'd.jns_spp' => '1'])->select(DB::raw("SUM(ISNULL(c.nilai,0)) as nilai"));

            $data2 = DB::table('trdtransout_cmsbank as c')->join('trhtransout_cmsbank as d', function ($join) {
                $join->on('c.no_voucher', '=', 'd.no_voucher');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })->where(['c.kd_sub_kegiatan' => $giat, 'd.kd_skpd' => $kode, 'c.kd_rek6' => $rek, 'd.jns_spp' => '1'])->where(function ($query) {
                $query->where('d.status_validasi', '0')->orWhereNull('d.status_validasi');
            })->select(DB::raw("SUM(ISNULL(c.nilai,0)) as nilai"))->unionAll($data1);

            $data3 = DB::table('trdspp as c')->join('trhspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })->where(['c.kd_sub_kegiatan' => $giat, 'c.kd_skpd' => $kode, 'c.kd_rek6' => $rek])->whereIn('d.jns_spp', ['3', '4', '5', '6'])->where(function ($query) {
                $query->where('sp2d_batal', '')->orWhereNull('sp2d_batal');
            })->select(DB::raw("SUM(ISNULL(c.nilai,0)) as nilai"))->unionAll($data2);

            $data4 = DB::table('trdtagih as c')->join('trhtagih as d', function ($join) {
                $join->on('c.no_bukti', '=', 'd.no_bukti');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })->where(['c.kd_sub_kegiatan' => $giat, 'd.kd_skpd' => $kode, 'c.kd_rek6' => $rek])->whereRaw('d.no_bukti NOT IN (SELECT no_tagih FROM trhspp WHERE kd_skpd=?)', [$kode])->select(DB::raw("SUM(ISNULL(nilai,0)) as nilai"))->unionAll($data3);

            $angkas_lalu = DB::table(DB::raw("({$data4->toSql()}) AS sub"))
                ->select(DB::raw("SUM(nilai) as total"))
                ->mergeBindings($data4)
                ->first();
        } else {
            $spp = DB::table('trhsp2d')->select('no_spp')->where(['no_sp2d' => $no_sp2d])->first();
            $no_spp = $spp->no_spp;

            $data1 = DB::table('trdtransout as c')->join('trhtransout as d', function ($join) {
                $join->on('c.no_bukti', '=', 'd.no_bukti');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })->where(['c.kd_sub_kegiatan' => $giat, 'd.kd_skpd' => $kode, 'c.kd_rek6' => $rek, 'd.jns_spp' => '1'])->select(DB::raw("SUM(ISNULL(c.nilai,0)) as nilai"));

            $data2 = DB::table('trdtransout_cmsbank as c')->join('trhtransout_cmsbank as d', function ($join) {
                $join->on('c.no_voucher', '=', 'd.no_voucher');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })->where(['c.kd_sub_kegiatan' => $giat, 'd.kd_skpd' => $kode, 'c.kd_rek6' => $rek, 'd.jns_spp' => '1'])->where(function ($query) {
                $query->where('d.status_validasi', '0')->orWhereNull('d.status_validasi');
            })->select(DB::raw("SUM(ISNULL(c.nilai,0)) as nilai"))->unionAll($data1);

            $data3 = DB::table('trdspp as c')->join('trhspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })->where(['c.kd_sub_kegiatan' => $giat, 'c.kd_skpd' => $kode, 'c.kd_rek6' => $rek])->whereIn('d.jns_spp', ['3', '4', '5', '6'])->where('d.no_spp', '<>', $no_spp)->where(function ($query) {
                $query->where('sp2d_batal', '')->orWhereNull('sp2d_batal');
            })->select(DB::raw("SUM(ISNULL(c.nilai,0)) as nilai"))->unionAll($data2);

            $data4 = DB::table('trdtagih as c')->join('trhtagih as d', function ($join) {
                $join->on('c.no_bukti', '=', 'd.no_bukti');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })->where(['c.kd_sub_kegiatan' => $giat, 'd.kd_skpd' => $kode, 'c.kd_rek6' => $rek])->whereRaw('d.no_bukti NOT IN (SELECT no_tagih FROM trhspp WHERE kd_skpd=?)', [$kode])->select(DB::raw("SUM(ISNULL(nilai,0)) as nilai"))->unionAll($data3);

            $angkas_lalu = DB::table(DB::raw("({$data4->toSql()}) AS sub"))
                ->select(DB::raw("SUM(nilai) as total"))
                ->mergeBindings($data4)
                ->first();
        }

        // SPD
        $spd = load_spd($giat, $kode, $rek);

        return response()->json([
            'sumber' => $data,
            'angkas' => $angkas->nilai,
            'angkas_lalu' => $angkas_lalu->total,
            'spd' => $spd->total
        ]);
    }

    public function cariSumberDanaSppLs(Request $request)
    {
        $kode               = $request->skpd;
        $giat               = $request->kdgiat;
        $rek                = $request->kdrek;
        $no_spp                = $request->no_spp;
        $tgl_spp                = $request->tgl_spp;
        $status_angkas    = $request->status_angkas;
        $beban    = $request->beban;
        $bulan = date('m', strtotime($tgl_spp));

        $status             = DB::table('trhrka')->where(['kd_skpd' => $kode, 'status' => '1'])->orderByDesc('tgl_dpa')->first();
        $status_anggaran    = $status->jns_ang;


        $no_trdrka = $kode . '.' . $giat . '.' . $rek;

        // SUMBER DANA
        $data1 = DB::table('trdpo')
            ->select('sumber', 'nm_sumber', DB::raw("SUM(total) as nilai"))
            ->where(['no_trdrka' => $no_trdrka, 'jns_ang' => $status_anggaran])
            ->whereNotNull('sumber')
            ->groupBy('sumber', 'nm_sumber');

        $data2 = DB::table('trdpo')
            ->select('sumber', DB::raw("'Silahkan isi sumber di anggaran' as nm_sumber"), DB::raw("SUM(total) as nilai"))
            ->where(['no_trdrka' => $no_trdrka, 'jns_ang' => $status_anggaran])
            ->where(function ($query) {
                $query->where('sumber', '')->orWhereNull('sumber');
            })
            ->groupBy('sumber', 'nm_sumber')
            ->union($data1);

        $data = DB::table(DB::raw("({$data2->toSql()}) AS sub"))
            ->mergeBindings($data2)
            ->get();


        // ANGGARAN PENYUSUNAN
        $status_anggaran = DB::table('trhrka')->select('jns_ang')->where(['kd_skpd' => $kode, 'status' => 1])->orderBy('tgl_dpa', 'DESC')->first();
        $no_bukti1 = DB::select(DB::raw("SELECT no_tagih from trhspp where kd_skpd='$kode'"));
        $no_bukti = json_decode(json_encode($no_bukti1), true);

        $query1 = DB::table('trdspp as a')->select(DB::raw("SUM(a.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.kd_sub_kegiatan' => $giat, 'a.kd_rek6' => $rek, 'a.kd_skpd' => $kode])->where('a.no_spp', '<>', $no_spp)->whereIn('b.jns_spp', ['3', '4', '5', '6'])->where(function ($query) {
            $query->where('b.sp2d_batal', '<>', '1')
                ->orWhereNull('b.sp2d_batal');
        });

        $query2 = DB::table('trdtagih as t')->select(DB::raw("SUM(nilai) as nilai"))->join('trhtagih as u', function ($join) {
            $join->on('t.no_bukti', '=', 'u.no_bukti');
            $join->on('t.kd_skpd', '=', 'u.kd_skpd');
        })->where(['t.kd_sub_kegiatan' => $giat, 't.kd_rek' => $rek, 'u.kd_skpd' => $kode])->whereNotIn('u.no_bukti', $no_bukti)->unionAll($query1);

        $query3 = DB::table('trdtransout as a')->select(DB::raw("SUM(a.nilai) as nilai"))->join('trhtransout as b', function ($join) {
            $join->on('a.no_bukti', '=', 'b.no_bukti');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.kd_sub_kegiatan' => $giat, 'a.kd_rek6' => $rek, 'a.kd_skpd' => $kode])->whereIn('b.jns_spp', ['1', '2'])->unionAll($query2);

        $query4 = DB::table('trdtransout as a')->select(DB::raw("SUM(a.nilai) as nilai"))->join('trhtransout as b', function ($join) {
            $join->on('a.no_bukti', '=', 'b.no_bukti');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.kd_sub_kegiatan' => $giat, 'a.kd_rek6' => $rek, 'a.kd_skpd' => $kode])->whereIn('b.jns_spp', ['4', '6'])->whereIn('panjar', ['3'])->unionAll($query3);

        $query5 = DB::table('trdtransout_cmsbank as a')->select(DB::raw("SUM(a.nilai) as nilai"))->join('trhtransout_cmsbank as b', function ($join) {
            $join->on('a.no_voucher', '=', 'b.no_voucher');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.kd_sub_kegiatan' => $giat, 'a.kd_rek6' => $rek, 'a.kd_skpd' => $kode, 'b.status_validasi' => '0'])->unionAll($query4);

        $result = DB::table(DB::raw("({$query5->toSql()}) AS sub"))
            ->select(DB::raw("SUM(nilai) as rektotal_spp_lalu"))
            ->mergeBindings($query5)
            ->first();

        $rektotal = DB::table('trdrka')->select(DB::raw("SUM(nilai) as rektotal"))->where(['kd_rek6' => $rek, 'kd_sub_kegiatan' => $giat, 'jns_ang' => $status_anggaran->jns_ang, 'kd_skpd' => $kode])->first();

        // TOTAL SPD
        $revisi1 = collect(DB::select("SELECT max(revisi_ke) as revisi from trhspd where left(kd_skpd,17)=left(?,17) and bulan_akhir='3' and tgl_spd<=?", [$kode, $tgl_spp]))->first()->revisi;

        $revisi2 = collect(DB::select("SELECT isnull(max(revisi_ke),0) as revisi from trhspd where left(kd_skpd,17)=left(?,17) and bulan_akhir='6' and tgl_spd<=?", [$kode, $tgl_spp]))->first()->revisi;

        $revisi3 = collect(DB::select("SELECT isnull(max(revisi_ke),0) as revisi from trhspd where left(kd_skpd,17)=left(?,17) and bulan_akhir='6' and tgl_spd<=?", [$kode, $tgl_spp]))->first()->revisi;

        $revisi4 = collect(DB::select("SELECT isnull(max(revisi_ke),0) as revisi from trhspd where left(kd_skpd,17)=left(?,17) and bulan_akhir='6' and tgl_spd<=?", [$kode, $tgl_spp]))->first()->revisi;

        $total_spd =
            collect(DB::select("SELECT sum(nilai)as total_spd from (
                    SELECT
                    'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = ?
                    AND a.kd_sub_kegiatan = ?
                    AND a.kd_rek6 = ?
                    AND b.status = '1'
                    and bulan_akhir='3'
                    and revisi_ke=?
                    and tgl_spd<=?
                    and bulan_awal <= month(?)
                    UNION ALL
                    SELECT
                    'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = ?
                    AND a.kd_sub_kegiatan = ?
                    AND a.kd_rek6 = ?
                    AND b.status = '1'
                    and bulan_akhir='6'
                    and revisi_ke=?
                    and tgl_spd<=?
                    and bulan_awal <= month(?)
                    UNION ALL
                    SELECT
                    'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = ?
                    AND a.kd_sub_kegiatan = ?
                    AND a.kd_rek6 = ?
                    AND b.status = '1'
                    and bulan_akhir='9'
                    and revisi_ke=?
                    and tgl_spd<=?
                    and bulan_awal <= month(?)
                    UNION ALL
                    SELECT
                    'TW4' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = ?
                    AND a.kd_sub_kegiatan = ?
                    AND a.kd_rek6 = ?
                    AND b.status = '1'
                    and bulan_akhir='12'
                    and revisi_ke=?
                    and tgl_spd<=?
                    and bulan_awal <= month(?)
                    )spd", [$kode, $giat, $rek, $revisi1, $tgl_spp, $tgl_spp, $kode, $giat, $rek, $revisi2, $tgl_spp, $tgl_spp, $kode, $giat, $rek, $revisi3, $tgl_spp, $tgl_spp, $kode, $giat, $rek, $revisi4, $tgl_spp, $tgl_spp]))->first();

        // ANGKAS
        if ($status_angkas == 'murni') {
            $field_angkas = 'nilai_susun';
        } else if ($status_angkas == 'murni_geser1') {
            $field_angkas = 'nilai_susun1';
        } else if ($status_angkas == 'murni_geser2') {
            $field_angkas = 'nilai_susun2';
        } else if ($status_angkas == 'murni_geser3') {
            $field_angkas = 'nilai_susun3';
        } else if ($status_angkas == 'murni_geser4') {
            $field_angkas = 'nilai_susun4';
        } else if ($status_angkas == 'murni_geser5') {
            $field_angkas = 'nilai_susun5';
        } else if ($status_angkas == 'sempurna1') {
            $field_angkas = 'nilai_sempurna';
        } else if ($status_angkas == 'sempurna1_geser1') {
            $field_angkas = 'nilai_sempurna11';
        } else if ($status_angkas == 'sempurna1_geser2') {
            $field_angkas = 'nilai_sempurna12';
        } else if ($status_angkas == 'sempurna1_geser3') {
            $field_angkas = 'nilai_sempurna13';
        } else if ($status_angkas == 'sempurna1_geser4') {
            $field_angkas = 'nilai_sempurna14';
        } else if ($status_angkas == 'sempurna1_geser5') {
            $field_angkas = 'nilai_sempurna15';
        } else if ($status_angkas == 'sempurna2') {
            $field_angkas = 'nilai_sempurna2';
        } else if ($status_angkas == 'sempurna2_geser1') {
            $field_angkas = 'nilai_sempurna21';
        } else if ($status_angkas == 'sempurna2_geser2') {
            $field_angkas = 'nilai_sempurna22';
        } else if ($status_angkas == 'sempurna2_geser3') {
            $field_angkas = 'nilai_sempurna23';
        } else if ($status_angkas == 'sempurna2_geser4') {
            $field_angkas = 'nilai_sempurna24';
        } else if ($status_angkas == 'sempurna2_geser5') {
            $field_angkas = 'nilai_sempurna25';
        } else if ($status_angkas == 'sempurna3') {
            $field_angkas = 'nilai_sempurna3';
        } else if ($status_angkas == 'sempurna3_geser1') {
            $field_angkas = 'nilai_sempurna31';
        } else if ($status_angkas == 'sempurna3_geser2') {
            $field_angkas = 'nilai_sempurna32';
        } else if ($status_angkas == 'sempurna3_geser3') {
            $field_angkas = 'nilai_sempurna33';
        } else if ($status_angkas == 'sempurna3_geser4') {
            $field_angkas = 'nilai_sempurna34';
        } else if ($status_angkas == 'sempurna3_geser5') {
            $field_angkas = 'nilai_sempurna35';
        } else if ($status_angkas == 'sempurna4') {
            $field_angkas = 'nilai_sempurna4';
        } else if ($status_angkas == 'sempurna4_geser1') {
            $field_angkas = 'nilai_sempurna41';
        } else if ($status_angkas == 'sempurna4_geser2') {
            $field_angkas = 'nilai_sempurna42';
        } else if ($status_angkas == 'sempurna4_geser3') {
            $field_angkas = 'nilai_sempurna43';
        } else if ($status_angkas == 'sempurna4_geser4') {
            $field_angkas = 'nilai_sempurna44';
        } else if ($status_angkas == 'sempurna4_geser5') {
            $field_angkas = 'nilai_sempurna45';
        } else if ($status_angkas == 'sempurna5') {
            $field_angkas = 'nilai_sempurna5';
        } else if ($status_angkas == 'sempurna5_geser1') {
            $field_angkas = 'nilai_sempurna51';
        } else if ($status_angkas == 'sempurna5_geser2') {
            $field_angkas = 'nilai_sempurna52';
        } else if ($status_angkas == 'sempurna5_geser3') {
            $field_angkas = 'nilai_sempurna53';
        } else if ($status_angkas == 'sempurna5_geser4') {
            $field_angkas = 'nilai_sempurna1';
        } else if ($status_angkas == 'sempurna5_geser5') {
            $field_angkas = 'nilai_sempurna55';
        } else if ($status_angkas == 'ubah') {
            $field_angkas = 'nilai_ubah';
        } else if ($status_angkas == 'ubah1') {
            $field_angkas = 'nilai_ubah1';
        } else if ($status_angkas == 'ubah2') {
            $field_angkas = 'nilai_ubah2';
        } else if ($status_angkas == 'ubah3') {
            $field_angkas = 'nilai_ubah3';
        } else if ($status_angkas == 'ubah4') {
            $field_angkas = 'nilai_ubah4';
        } else {
            $field_angkas = 'nilai_ubah5';
        }

        $status_anggaran = DB::table('trhrka')->select('jns_ang')->where(['kd_skpd' => $kode, 'status' => 1])->orderBy('tgl_dpa', 'DESC')->first();

        $hasil = DB::table('trhspd')->select(DB::raw("COUNT(*) as spd"))->whereRaw("LEFT(kd_skpd,17) = LEFT('$kode',17)")->groupBy('bulan_awal', 'bulan_akhir')->first();

        if ($beban == '4' || substr($giat, 5, 10) == '01.1.02.01') {
            $bulan1 = $bulan  + 1;
        } else {
            $bulan1 = $bulan;
        }

        $total_angkas = DB::table('trdskpd_ro as a')->select('a.kd_sub_kegiatan', 'kd_rek6', DB::raw("SUM(a.$field_angkas) as nilai"))->join('trskpd as b', function ($join) {
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
        })->where(['a.kd_skpd' => $kode, 'a.kd_sub_kegiatan' => $giat, 'a.kd_rek6' => $rek, 'jns_ang' => $status_anggaran->jns_ang])->where('bulan', '<=', $bulan1)->groupBy('a.kd_sub_kegiatan', 'a.kd_rek6')->first();


        // REALISASI
        $sql = DB::table('trhspp')->select('no_tagih')->where('kd_skpd', $kode)->get();
        $no_bukti = json_decode(json_encode($sql), true);
        $realisasi1 = DB::table('tb_transaksi')->selectRaw("SUM(ISNULL(nilai,0)) as nilai")->where(['kd_sub_kegiatan' => $giat, 'kd_skpd' => $kode, 'kd_rek6' => $rek]);

        $realisasi2 = DB::table('trdtransout as c')->leftJoin('trhtransout as d', function ($join) {
            $join->on('c.no_bukti', '=', 'd.no_bukti');
            $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        })->selectRaw("SUM(ISNULL(c.nilai,0)) as nilai")->where(['c.kd_sub_kegiatan' => $giat, 'd.kd_skpd' => $kode, 'c.kd_rek6' => $rek])->whereIn('d.jns_spp', ['1'])->unionAll($realisasi1);

        $realisasi3 = DB::table('trdtransout_cmsbank as c')->leftJoin('trhtransout_cmsbank as d', function ($join) {
            $join->on('c.no_voucher', '=', 'd.no_voucher');
            $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        })->selectRaw("SUM(ISNULL(c.nilai,0)) as nilai")->where(['c.kd_sub_kegiatan' => $giat, 'd.kd_skpd' => $kode, 'c.kd_rek6' => $rek])->whereIn('d.jns_spp', ['1'])->where(function ($query) {
            $query->where('d.status_validasi', '=', '0')
                ->orWhereNull('d.status_validasi');
        })->unionAll($realisasi2);

        $realisasi4 = DB::table('trdspp as x')->join('trhspp as y', function ($join) {
            $join->on('x.no_spp', '=', 'y.no_spp');
            $join->on('x.kd_skpd', '=', 'y.kd_skpd');
        })->selectRaw("SUM(ISNULL(x.nilai,0)) as nilai")->where(['x.kd_sub_kegiatan' => $giat, 'x.kd_skpd' => $kode, 'x.kd_rek6' => $rek])->whereIn('y.jns_spp', ['3', '4', '5', '6'])->where(function ($query) {
            $query->where('sp2d_batal', '=', '0')
                ->orWhereNull('sp2d_batal');
        })->unionAll($realisasi3);

        $realisasi5 = DB::table('trdtagih as t')->selectRaw("SUM(ISNULL(t.nilai,0)) as nilai")->join('trhtagih as u', function ($join) {
            $join->on('t.no_bukti', '=', 'u.no_bukti');
            $join->on('t.kd_skpd', '=', 'u.kd_skpd');
        })->where(['t.kd_sub_kegiatan' => $giat, 't.kd_rek' => $rek, 'u.kd_skpd' => $kode])->whereNotIn('u.no_bukti', $no_bukti)->unionAll($realisasi4);

        $realisasi = DB::table(DB::raw("({$realisasi5->toSql()}) AS sub"))
            ->select(DB::raw("SUM(nilai) as total"))
            ->mergeBindings($realisasi5)
            ->first();

        return response()->json([
            'sumber' => $data,
            'rektotal' => $rektotal->rektotal,
            'rektotal_lalu' => $result->rektotal_spp_lalu,
            'total_spd' => $total_spd->total_spd,
            'angkas' => $total_angkas->nilai,
            'realisasi' => $realisasi->total
        ]);
    }

    public function realisasiSumber(Request $request)
    {
        $sumber = $request->sumber;
        $kd_skpd = $request->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_skpd = $request->kd_skpd;
        $kd_rek6 = $request->kd_rek6;

        $tagih_lalu = DB::table('trdtagih as a')
            ->join('trhtagih as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("SUM( nilai ) AS nilai")
            ->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'b.kd_skpd' => $kd_skpd, 'a.kd_rek6' => $kd_rek6, 'sumber' => $sumber])
            ->whereRaw("b.no_bukti NOT IN ( SELECT no_tagih FROM trhspp WHERE kd_skpd =? )", [$kd_skpd])
            ->first();

        $tampungan = DB::table('tb_transaksi as a')
            ->selectRaw("SUM( nilai ) AS nilai")
            ->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.kd_skpd' => $kd_skpd, 'a.kd_rek6' => $kd_rek6, 'a.sumber' => $sumber])
            ->first();

        $spplalu = DB::table('trhspp as a')
            ->join('trdspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("SUM( b.nilai ) AS nilai")
            ->where(['b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'b.kd_skpd' => $kd_skpd, 'b.kd_rek6' => $kd_rek6, 'sumber' => $sumber])
            ->where(function ($query) {
                $query->where('sp2d_batal', '<>', '1')->orWhereNull('sp2d_batal');
            })
            ->whereNotIn('jns_spp', ['1', '2'])
            ->first();

        $upgulalucms = DB::table('trhtransout_cmsbank as a')
            ->join('trdtransout_cmsbank as b', function ($join) {
                $join->on('a.no_voucher', '=', 'b.no_voucher');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("SUM( b.nilai ) AS nilai")
            ->where(['b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'b.kd_skpd' => $kd_skpd, 'b.kd_rek6' => $kd_rek6, 'sumber' => $sumber])
            ->where(function ($query) {
                $query->where('a.status_validasi', '<>', '1')->orWhereNull('a.status_validasi');
            })
            ->whereIn('a.jns_spp', ['1'])
            ->first();

        $upgulalu = DB::table('trhtransout as a')
            ->join('trdtransout as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("SUM( b.nilai ) AS nilai")
            ->where(['b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'b.kd_skpd' => $kd_skpd, 'b.kd_rek6' => $kd_rek6, 'sumber' => $sumber])
            ->whereIn('a.jns_spp', ['1'])
            ->first();

        $realisasi = $tagih_lalu->nilai + $tampungan->nilai + $spplalu->nilai + $upgulalucms->nilai + $upgulalu->nilai;
        return response()->json($realisasi);
    }

    public function cariNamaSumber(Request $request)
    {
        $sumber_dana = $request->sumber_dana;
        $data = DB::table('sumber_dana')->select('nm_sumber_dana1')->where('kd_sumber_dana1', $sumber_dana)->first();
        return response()->json($data);
    }

    public function cariTotalKontrak(Request $request)
    {
        $no_kontrak = $request->no_kontrak;
        $skpd = $request->skpd;
        $data = DB::table('ms_kontrak')->select(DB::raw('SUM(nilai) as total_kontrak'))->where(['kd_skpd' => $skpd, 'no_kontrak' => $no_kontrak])->first();
        return response()->json($data);
    }

    public function simpanTampungan(Request $request)
    {
        $nomor = $request->nomor;
        $kdgiat = $request->kdgiat;
        $kdrek = $request->kdrek;
        $nilai_tagih = $request->nilai_tagih;
        $sumber = $request->sumber;
        $skpd = Auth::user()->kd_skpd;
        $nama = Auth::user()->nama;
        $tanggal_ubah = date('Y-m-d H:i:s');
        DB::beginTransaction();
        try {
            DB::table('tb_transaksi')->insert(
                [
                    'kd_skpd' => $skpd,
                    'no_transaksi' => $nomor,
                    'kd_sub_kegiatan' => $kdgiat,
                    'kd_rek6' => $kdrek,
                    'sumber' => $sumber,
                    'nilai' => $nilai_tagih,
                    'username' => $nama,
                    'last_update' => $tanggal_ubah,
                ]
            );
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

    public function cekNilaiKontrak(Request $request)
    {
        $no_kontrak = $request->no_kontrak;
        $tgl_bukti = $request->tgl_bukti;
        $data = DB::table('trhtagih')->select(DB::raw('SUM(total) as total'))->where('kontrak', $no_kontrak)->first();
        return response()->json($data);
    }

    public function cekNilaiKontrak2(Request $request)
    {
        $no_kontrak = $request->no_kontrak;
        $tgl_bukti = $request->tgl_bukti;
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('ms_kontrak')->select(DB::raw('SUM(nilai) as nilai'))->where(['no_kontrak' => $no_kontrak, 'kd_skpd' => $kd_skpd])->first();
        return response()->json($data);
    }

    // Cek simpan Input
    public function cekSimpanPenagihan(Request $request)
    {
        $no_bukti = $request->no_bukti;
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhtagih')->select(DB::raw('COUNT(*) as jumlah'))->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->first();
        return response()->json($data);
    }

    public function simpanPenagihan(Request $request)
    {
        $no_bukti = $request->no_bukti;
        $kd_skpd = Auth::user()->kd_skpd;
        DB::beginTransaction();
        try {
            $cek_simpan = DB::table('trhtagih')->select('no_bukti')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->count();
            if ($cek_simpan > 0) {
                return response()->json([
                    'message' => '1'
                ]);
            }
            DB::table('trhtagih')->insert([
                'no_bukti' => $request->no_bukti,
                'tgl_bukti' => $request->tgl_bukti,
                'ket' => $request->ket,
                'username' => '',
                'tgl_update' => '',
                'kd_skpd' => $request->kd_skpd,
                'nm_skpd' => $request->nm_skpd,
                'total' => $request->total_nilai,
                'no_tagih' => $request->ctagih,
                'sts_tagih' => $request->cstatus,
                'status' => $request->status_bayar,
                'tgl_tagih' => $request->ctgltagih,
                'jns_spp' => $request->cjenis,
                'jenis' => $request->jenis,
                'kontrak' => $request->no_kontrak,
                'jns_trs' => $request->jns_trs,
                'ket_bast' => $request->ket_bast,
                'nm_rekanan' => $request->rekanan,
            ]);
            DB::commit();
            return response()->json([
                'message' => '2'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function simpanDetailPenagihan(Request $request)
    {
        $no_bukti = $request->no_bukti;
        $status_bayar = $request->status_bayar;
        $rincian_penagihan = $request->rincian_penagihan;
        $kd_skpd = Auth::user()->kd_skpd;
        $nama = Auth::user()->nama;
        DB::beginTransaction();
        try {
            if (isset($rincian_penagihan)) {
                DB::table('trdtagih')->insert(array_map(function ($value) {
                    return [
                        'no_bukti' => $value['no_bukti'],
                        'no_sp2d' => $value['no_sp2d'],
                        'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                        'nm_sub_kegiatan' => $value['nm_sub_kegiatan'],
                        'kd_rek6' => $value['kd_rek6'],
                        'kd_rek' => $value['kd_rek'],
                        'nm_rek6' => $value['nm_rek6'],
                        'nilai' => $value['nilai'],
                        'kd_skpd' => $value['kd_skpd'],
                        'sumber' => $value['sumber'],
                    ];
                }, $rincian_penagihan));
                DB::table('tb_transaksi')->where(['kd_skpd' => $kd_skpd, 'no_transaksi' => $no_bukti, 'username' => $nama])->delete();
            }
            DB::commit();
            return response()->json([
                'message' => '4'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '5'
            ]);
        }
    }

    public function edit($no_bukti)
    {
        $no_bukti = Crypt::decryptString($no_bukti);
        $data_tagih = DB::table('trhtagih')->where('no_bukti', $no_bukti)->first();
        // dd($data_tagih);
        $status_anggaran = DB::table('trhrka')->select('jns_ang')->where(['kd_skpd' => $data_tagih->kd_skpd, 'status' => 1])->orderBy('tgl_dpa', 'DESC')->first();
        $data = [
            'data_tagih' => DB::table('trhtagih as a')->join('trdtagih as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->where('a.no_bukti', $no_bukti)->first(),
            'detail_tagih' => DB::table('trdtagih as a')->select('a.*')->join('trhtagih as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->where('a.no_bukti', $no_bukti)->get(),
            'daftar_kontrak' => DB::table('ms_kontrak as z')->where('z.kd_skpd', $data_tagih->kd_skpd)
                ->select('z.no_kontrak', 'z.nilai', DB::raw("(SELECT SUM(nilai) FROM trhtagih a INNER JOIN trdtagih b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE kontrak=z.no_kontrak) as lalu"))->orderBy('z.no_kontrak', 'ASC')->get(),
            'kontrak' => DB::table('ms_kontrak')->where('no_kontrak', $data_tagih->kontrak)->first(),
            'daftar_rekanan' => DB::table('ms_rekening_bank_online')->where('kd_skpd', $data_tagih->kd_skpd)->orderBy('rekening', 'ASC')->get(),
            'daftar_sub_kegiatan' => DB::table('trskpd as a')
                ->select('a.total', 'a.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'a.kd_program', DB::raw("(SELECT nm_program FROM ms_program WHERE kd_program=a.kd_program) as nm_program"))
                ->join('ms_sub_kegiatan AS b', 'a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan')
                ->where(['a.kd_skpd' => $data_tagih->kd_skpd, 'a.status_sub_kegiatan' => '1', 'a.jns_ang' => $status_anggaran->jns_ang, 'b.jns_sub_kegiatan' => '5'])->get(),
            'kontrak' => DB::table('ms_kontrak')->where('no_kontrak', $data_tagih->kontrak)->first(),
        ];

        return view('penatausahaan.pengeluaran.penagihan.edit')->with($data);
    }

    public function hapusPenagihan(Request $request)
    {
        $no_bukti = $request->no_bukti;
        $kd_skpd = Auth::user()->kd_skpd;
        DB::beginTransaction();
        try {
            DB::table('trdtagih')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->delete();
            DB::table('trhtagih')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->delete();
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

    public function hapusTampunganPenagihan(Request $request)
    {
        $no_bukti = $request->no_bukti;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_rek = $request->kd_rek;
        $sumber = $request->sumber;
        $nama = Auth::user()->nama;
        $kd_skpd = Auth::user()->kd_skpd;
        $nilai = $request->nilai;
        DB::beginTransaction();
        try {
            DB::table('tb_transaksi')->where(['no_transaksi' => $no_bukti, 'username' => $nama, 'kd_skpd' => $kd_skpd, 'kd_sub_kegiatan' => $kd_sub_kegiatan, 'kd_rek6' => $kd_rek, 'sumber' => $sumber])->delete();
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

    public function hapusSemuaTampungan()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $nama = Auth::user()->nama;
        DB::beginTransaction();
        try {
            DB::table('tb_transaksi')->where(['kd_skpd' => $kd_skpd, 'username' => $nama])->delete();
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

    public function hapusDetailEditPenagihan(Request $request)
    {
        DB::beginTransaction();
        try {
            $no_bukti = $request->no_bukti;
            $kd_sub_kegiatan = $request->kd_sub_kegiatan;
            $kd_rek = $request->kd_rek;
            $sumber = $request->sumber;
            $nilai = $request->nilai;
            $kd_skpd = Auth::user()->kd_skpd;

            DB::table('trdtagih')->where(['no_bukti' => $no_bukti, 'kd_sub_kegiatan' => $kd_sub_kegiatan, 'kd_rek' => $kd_rek, 'sumber' => $sumber])->delete();
            $cari_total = DB::table('trhtagih')->select('total')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->first();
            if ($cari_total) {
                $total = $cari_total->total;
                DB::table('trhtagih')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->update([
                    'total' => $total - $nilai,
                ]);
            }
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

    public function updatePenagihan(Request $request)
    {
        $no_bukti = $request->no_bukti;
        $kd_skpd = Auth::user()->kd_skpd;
        $kd_skpd1 = $request->kd_skpd;
        $no_tersimpan = $request->no_tersimpan;
        DB::beginTransaction();
        try {
            if ($no_bukti != $no_tersimpan) {
                $cek_simpan = DB::table('trhtagih')->select('no_bukti')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->count();
                if ($cek_simpan > 0) {
                    return response()->json([
                        'message' => '1'
                    ]);
                }
            }
            $cek_spp = DB::table('trhspp')->select('no_tagih')->where('no_tagih', $no_bukti)->where('sp2d_batal', '0')->where('sp2d_batal', null)->count();
            if ($cek_spp == '0') {
                DB::table('trhtagih')->where(['no_bukti' => $no_tersimpan, 'kd_skpd' => $kd_skpd1])->update([
                    'no_bukti' => $request->no_bukti,
                    'tgl_bukti' => $request->tgl_bukti,
                    'ket' => $request->ket,
                    'username' => '',
                    'tgl_update' => '',
                    'nm_skpd' => $request->nm_skpd,
                    'total' => $request->total_nilai,
                    'no_tagih' => $request->ctagih,
                    'sts_tagih' => $request->cstatus,
                    'status' => $request->status_bayar,
                    'tgl_tagih' => $request->ctgltagih,
                    'jns_spp' => $request->cjenis,
                    'jenis' => $request->jenis,
                    'kontrak' => $request->no_kontrak,
                    'ket_bast' => $request->ket_bast,
                    'nm_rekanan' => $request->rekanan,
                ]);
                DB::commit();
                return response()->json([
                    'message' => '2'
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function updateDetailPenagihan(Request $request)
    {
        $no_bukti = $request->no_bukti;
        $no_tersimpan = $request->no_tersimpan;
        $status_bayar = $request->status_bayar;
        $rincian_penagihan = $request->rincian_penagihan;
        $kd_skpd = Auth::user()->kd_skpd;
        $nama = Auth::user()->nama;
        DB::beginTransaction();
        try {
            $cek_spp = DB::table('trhspp')->select('no_tagih')->where('no_tagih', $no_bukti)->where('sp2d_batal', '0')->where('sp2d_batal', null)->count();
            if ($cek_spp == '0') {
                DB::table('trdtagih')->where(['no_bukti' => $no_tersimpan, 'kd_skpd' => $kd_skpd])->delete();
                if (isset($rincian_penagihan)) {
                    DB::table('trdtagih')->insert(array_map(function ($value) use ($no_bukti) {
                        return [
                            'no_bukti' => $no_bukti,
                            'no_sp2d' => $value['no_sp2d'],
                            'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                            'nm_sub_kegiatan' => $value['nm_sub_kegiatan'],
                            'kd_rek6' => $value['kd_rek6'],
                            'kd_rek' => $value['kd_rek'],
                            'nm_rek6' => $value['nm_rek6'],
                            'nilai' => $value['nilai'],
                            'kd_skpd' => $value['kd_skpd'],
                            'sumber' => $value['sumber'],
                        ];
                    }, $rincian_penagihan));
                    DB::commit();
                    return response()->json([
                        'message' => '1'
                    ]);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function simpanEditTampungan(Request $request)
    {
        $nomor = $request->nomor;
        $no_simpan = $request->no_simpan;
        $kdgiat = $request->kdgiat;
        $nmgiat = $request->nmgiat;
        $kdrek6 = $request->kdrek6;
        $kdrek = $request->kdrek;
        $nmrek = $request->nmrek;
        $nilai_tagih = $request->nilai_tagih;
        $sumber = $request->sumber;
        $kd_skpd = Auth::user()->kd_skpd;
        DB::beginTransaction();
        try {
            DB::table('trdtagih')->insert([
                'no_bukti' => $no_simpan,
                'kd_sub_kegiatan' => $kdgiat,
                'nm_sub_kegiatan' => $nmgiat,
                'kd_rek6' => $kdrek6,
                'kd_rek' => $kdrek,
                'nm_rek6' => $nmrek,
                'nilai' => $nilai_tagih,
                'kd_skpd' => $kd_skpd,
                'sumber' => $sumber
            ]);
            $cari_total = DB::table('trhtagih')->select('total')->where(['no_bukti' => $nomor, 'kd_skpd' => $kd_skpd])->first();
            if ($cari_total) {
                $total = $cari_total->total;
                DB::table('trhtagih')->where(['kd_skpd' => $kd_skpd, 'no_bukti' => $no_simpan])->update([
                    'total' => $total + $nilai_tagih,
                ]);
            }
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
}
