<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DaftarPengeluaranRillController extends Controller
{
    public function index()
    {
        return view('skpd.dpr.index');
    }

    public function loadData()
    {
        $data = DB::table('trhdpr as a')
            ->select('a.*')
            ->where(['a.kd_skpd' => Auth::user()->kd_skpd])
            ->orderBy('a.no_dpr')
            ->orderBy(DB::raw("CAST(a.no_urut as int)"))
            ->orderBy('a.kd_skpd')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<a href="' . route("dpr.edit", ['no_dpr' => Crypt::encrypt($row->no_dpr), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="fa fa-edit"></i></a>';
                if ($row->status_verifikasi != '1') {
                    $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_dpr . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
                } else {
                    $btn .= '';
                }
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $data = [
            'rek_kkpd' => DB::table('ms_kkpd')
                ->where(['kd_skpd' => Auth::user()->kd_skpd])
                ->get()
        ];

        return view('skpd.dpr.create')->with($data);
    }

    public function no_urut()
    {
        $urut1 = DB::table('trhdpr')
            ->where(['kd_skpd' => Auth::user()->kd_skpd])
            ->select('no_urut as nomor', DB::raw("'Daftar Pengeluaran RILL' as ket"), 'kd_skpd');

        $urut = DB::table(DB::raw("({$urut1->toSql()}) AS sub"))
            ->select(DB::raw("CASE WHEN MAX(nomor+1) IS NULL THEN 1 ELSE MAX(nomor+1) END AS nomor"))
            ->mergeBindings($urut1)
            ->first();

        return response()->json($urut->nomor);
    }

    public function cekModal(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_rekening = $request->kd_rekening;
        $sumber = $request->sumber;

        $cek_perjalanan_dinas = collect(DB::select("SELECT MAX(total) as total from (SELECT count(*) as total from trhdpr a INNER JOIN trddpr b on a.no_dpr=b.no_dpr and a.kd_skpd=b.kd_skpd where b.kd_skpd=? and left(b.kd_rek6,6)='510204' and (a.status_verifikasi<>'1' or a.status_verifikasi is null)
        UNION ALL
        SELECT count(*) as total from trhdpr a INNER JOIN trddpr b on a.no_dpr=b.no_dpr and a.kd_skpd=b.kd_skpd where b.kd_skpd=? and left(b.kd_rek6,6)='510204' and a.status_verifikasi='1')z", [$kd_skpd, $kd_skpd]))
            ->first();

        $cek_belanja_modal = collect(DB::select("SELECT MAX(total) as total from (SELECT count(*) as total from trhdpr a INNER JOIN trddpr b on a.no_dpr=b.no_dpr and a.kd_skpd=b.kd_skpd where b.kd_skpd=? and left(b.kd_rek6,2)='52' and (a.status_verifikasi<>'1' or a.status_verifikasi is null)
        UNION ALL
        SELECT count(*) as total from trhdpr a INNER JOIN trddpr b on a.no_dpr=b.no_dpr and a.kd_skpd=b.kd_skpd where b.kd_skpd=? and left(b.kd_rek6,2)='52' and a.status_verifikasi='1')z", [$kd_skpd, $kd_skpd]))
            ->first();

        $cek_belanja_barang = collect(DB::select("SELECT MAX(total) as total from (SELECT count(*) as total from trhdpr a INNER JOIN trddpr b on a.no_dpr=b.no_dpr and a.kd_skpd=b.kd_skpd where b.kd_skpd=? and left(b.kd_rek6,4)='5102' and (a.status_verifikasi<>'1' or a.status_verifikasi is null)
        UNION ALL
        SELECT count(*) as total from trhdpr a INNER JOIN trddpr b on a.no_dpr=b.no_dpr and a.kd_skpd=b.kd_skpd where b.kd_skpd=? and left(b.kd_rek6,4)='5102' and a.status_verifikasi='1')z", [$kd_skpd, $kd_skpd]))
            ->first();

        return response()->json([
            'cek_perjalanan_dinas' => $cek_perjalanan_dinas->total,
            'cek_belanja_modal' => $cek_belanja_modal->total,
            'cek_belanja_barang' => $cek_belanja_barang->total,
        ]);
    }

    public function cariKegiatan(Request $request)
    {
        $jenis_belanja = $request->jenis_belanja;
        $kd_skpd = $request->kd_skpd;
        $anggaran = status_anggaran();

        $data = DB::table('trdrka as a')
            ->join('trskpd as b', function ($join) {
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
                $join->on('a.jns_ang', '=', 'b.jns_ang');
            })
            ->join('ms_sub_kegiatan as c', 'b.kd_sub_kegiatan', '=', 'c.kd_sub_kegiatan')
            ->select('b.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'b.kd_program', DB::raw("(SELECT nm_program FROM ms_program WHERE kd_program=b.kd_program) as nm_program"), 'b.total')
            ->where(['b.kd_skpd' => $kd_skpd, 'b.status_sub_kegiatan' => '1', 'b.jns_ang' => $anggaran, 'c.jns_sub_kegiatan' => '5'])
            ->where(function ($query) use ($jenis_belanja) {
                if ($jenis_belanja == '1') $query->whereRaw("left(a.kd_rek6,6)=?", ['510204']);
                if ($jenis_belanja == '2') $query->whereRaw("left(a.kd_rek6,2)=?", ['52']);
                if ($jenis_belanja == '3') $query->whereRaw("left(a.kd_rek6,4)=?", ['5102']);
            })
            ->groupBy('b.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'b.kd_program', 'b.total')
            ->get();

        return response()->json($data);
    }

    public function cariRekening(Request $request)
    {
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $jenis_belanja = $request->jenis_belanja;
        $kd_skpd = $request->kd_skpd;
        $jenis_ang = status_anggaran();

        if ($jenis_belanja == '1') $filter = "and left(a.kd_rek6,6)='510204'";
        if ($jenis_belanja == '2') $filter = "and left(a.kd_rek6,2)='52'";
        if ($jenis_belanja == '3') $filter = "and left(a.kd_rek6,4)='5102'";

        $rekening = DB::select("SELECT a.kd_rek6,a.nm_rek6,e.map_lo,
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

                        UNION ALL
                        SELECT SUM(x.nilai) as nilai FROM trhdpr z
                        INNER JOIN trddpr x ON z.no_dpr=x.no_dpr and z.kd_skpd=x.kd_skpd
                        WHERE
                        x.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND x.kd_skpd = a.kd_skpd
                        AND x.kd_rek6 = a.kd_rek6
                        -- tambahan tampungan
                        )r) AS lalu,
                        0 AS sp2d,a.nilai AS anggaran
                      FROM trdrka a LEFT JOIN ms_rek6 e ON a.kd_rek6=e.kd_rek6
                      WHERE a.kd_sub_kegiatan= ? AND jns_ang=? AND a.kd_skpd = ? and a.status_aktif='1' $filter", [$kd_skpd, $kd_sub_kegiatan, $jenis_ang, $kd_skpd]);

        $sisa_kkpd = sisa_bank_kkpd1();


        return response()->json([
            'rekening' => $rekening,
            'sisa_kkpd' => $sisa_kkpd->terima - $sisa_kkpd->keluar,
        ]);
    }

    public function cariSumber(Request $request)
    {
        $kd_rek6 = $request->kd_rek6;
        $kd_skpd = $request->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $tgl_dpr = $request->tgl_dpr;
        $status_angkas = $request->status_angkas;
        $jenis_belanja = $request->jenis_belanja;
        $jenis_ang = status_anggaran();

        $no_trdrka = $kd_skpd . '.' . $kd_sub_kegiatan . '.' . $kd_rek6;

        $sumber = sumber_dana($no_trdrka, $jenis_ang);

        // LOAD ANGKAS
        $bulan = date('m', strtotime($tgl_dpr));

        $angkas = angkas1($status_angkas, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $bulan, $jenis_ang);

        // ANGKAS LALU
        $angkas_lalu = angkas_lalu_penagihan($kd_skpd, $kd_sub_kegiatan, $kd_rek6);

        $spd = load_spd($kd_sub_kegiatan, $kd_skpd, $kd_rek6);

        return response()->json([
            'sumber' => $sumber,
            'angkas' => $angkas->nilai,
            'angkas_lalu' => $angkas_lalu->total,
            'spd' => $spd->total,
        ]);
    }

    public function loadDana(Request $request)
    {
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_skpd = $request->kd_skpd;
        $sumber = $request->sumber;
        $kd_rekening = $request->kd_rekening;
        $no_sp2d = $request->no_sp2d;
        $beban = $request->beban;
        $spp = DB::table('trhsp2d')->select('no_spp')->where(['no_sp2d' => $no_sp2d])->first();
        $no_spp = $spp->no_spp;
        $data = cari_dana($sumber, $kd_sub_kegiatan, $kd_rekening, $kd_skpd, $no_sp2d, $no_spp, $beban);

        return response()->json($data);
    }

    public function statusAng(Request $request)
    {
        return response()->json(status_anggaran_new());
    }

    public function loadSpd(Request $request)
    {
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_skpd = $request->kd_skpd;
        $kd_rekening = $request->kd_rekening;

        $data = load_spd($kd_sub_kegiatan, $kd_skpd, $kd_rekening);

        return response()->json($data);
    }

    public function no_urut_dpr()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $urut1 = DB::table('trddpr')
            ->where(['kd_skpd' => $kd_skpd])
            ->select('urut as nomor', DB::raw("'Detail DPR KKPD' as ket"), 'kd_skpd');

        $urut = DB::table(DB::raw("({$urut1->toSql()}) AS sub"))
            ->select(DB::raw("CASE WHEN MAX(nomor+1) IS NULL THEN 1 ELSE MAX(nomor+1) END AS nomor"))
            ->mergeBindings($urut1)
            ->first();

        return $urut->nomor;
    }

    public function simpan(Request $request)
    {
        $data = $request->data;

        DB::beginTransaction();
        try {
            $cek = DB::table('trhdpr')
                ->where(['no_dpr' => $data['no_dpr'], 'kd_skpd' => $data['kd_skpd']])
                ->count();

            if ($cek > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhdpr')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_dpr' => $data['no_dpr']])
                ->delete();

            DB::table('trhdpr')
                ->insert([
                    'no_dpr' => $data['no_dpr'],
                    'tgl_dpr' => $data['tgl_dpr'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'no_kkpd' => $data['no_kkpd'],
                    'nm_kkpd' => $data['nm_kkpd'],
                    'jenis_belanja' => $data['jenis_belanja'],
                    'total' => $data['total_belanja'],
                    'username' => Auth::user()->nama,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'status_verifikasi' => '0',
                    'no_urut' => $data['no_urut'],
                    'keterangan_tolak' => '',
                    'user_verif' => '',
                    'tgl_verif' => '',
                    'status' => 0,
                ]);

            DB::table('trddpr')
                ->where(['no_dpr' => $data['no_dpr'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            $rincian_data = json_decode($data['rincian_rekening'], true);

            // if (isset($data['rincian_rekening'])) {
            //     DB::table('trddpr')
            //         ->insert(array_map(function ($value) use ($data) {
            //             return [
            //                 'no_dpr' => $data['no_dpr'],
            //                 'kd_skpd' => $data['kd_skpd'],
            //                 'nm_skpd' => nama_skpd($data['kd_skpd']),
            //                 'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
            //                 'nm_sub_kegiatan' => $value['nm_sub_kegiatan'],
            //                 'kd_rek6' => $value['kd_rek6'],
            //                 'nm_rek6' => $value['nm_rek6'],
            //                 'nilai' => $value['nilai'],
            //                 'uraian' => $value['uraian'],
            //                 'bukti' => $value['bukti'],
            //                 'sumber' => $value['sumber'],
            //                 'pembayaran' => $value['pembayaran'],
            //                 'status' => '0',
            //                 'kode' => $value['kd_sub_kegiatan'] . '.' . $value['kd_rek6'] . '.' . $value['sumber']
            //             ];
            //         }, $data['rincian_rekening']));
            // }

            foreach ($rincian_data as $rincian => $value) {
                $no_urut = $this->no_urut_dpr();

                $input_trh = [
                    'no_dpr' => $data['no_dpr'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => nama_skpd($data['kd_skpd']),
                    'kd_sub_kegiatan' => $rincian_data[$rincian]['kd_sub_kegiatan'],
                    'nm_sub_kegiatan' => $rincian_data[$rincian]['nm_sub_kegiatan'],
                    'kd_rek6' => $rincian_data[$rincian]['kd_rek6'],
                    'nm_rek6' => $rincian_data[$rincian]['nm_rek6'],
                    'nilai' => $rincian_data[$rincian]['nilai'],
                    'uraian' => $rincian_data[$rincian]['uraian'],
                    'bukti' => $rincian_data[$rincian]['bukti'],
                    'sumber' => $rincian_data[$rincian]['sumber'],
                    'pembayaran' => $rincian_data[$rincian]['pembayaran'],
                    'status' => '0',
                    'kode' => $rincian_data[$rincian]['kd_sub_kegiatan'] . '.' . $rincian_data[$rincian]['kd_rek6'] . '.' . $rincian_data[$rincian]['sumber'] . '.' . $no_urut,
                    'urut' => $no_urut
                ];
                DB::table('trddpr')
                    ->insert($input_trh);
            }

            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function edit($no_dpr, $kd_skpd)
    {
        $no_dpr = Crypt::decrypt($no_dpr);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'rek_kkpd' => DB::table('ms_kkpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->get(),
            'dpr' => DB::table('trhdpr')
                ->where(['no_dpr' => $no_dpr, 'kd_skpd' => $kd_skpd])
                ->first(),
            'rincian_dpr' => DB::table('trddpr as a')
                ->join('trhdpr as b', function ($join) {
                    $join->on('a.no_dpr', '=', 'b.no_dpr');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('a.*')
                ->where(['b.no_dpr' => $no_dpr, 'b.kd_skpd' => $kd_skpd])
                ->get()
        ];

        return view('skpd.dpr.edit')->with($data);
    }

    public function update(Request $request)
    {
        $data = $request->data;

        DB::beginTransaction();
        try {
            DB::table('trhdpr')
                ->where(['no_dpr' => $data['no_dpr'], 'kd_skpd' => $data['kd_skpd']])
                ->update([
                    'tgl_dpr' => $data['tgl_dpr'],
                    'no_kkpd' => $data['no_kkpd'],
                    'nm_kkpd' => $data['nm_kkpd'],
                    'username' => Auth::user()->nama,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function hapus(Request $request)
    {
        $no_dpr = $request->no_dpr;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trddpr')
                ->where(['no_dpr' => $no_dpr, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trhdpr')
                ->where(['no_dpr' => $no_dpr, 'kd_skpd' => $kd_skpd])
                ->delete();

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

    public function cetakList(Request $request)
    {
        $tgl_voucher = $request->tgl_voucher;
        $kd_skpd = Auth::user()->kd_skpd;
        $tahun_anggaran = tahun_anggaran();

        $data1 = DB::table('trhtransout_cmsbank as a')->where(DB::raw("YEAR(a.tgl_voucher)"), $tahun_anggaran)->where(['a.tgl_voucher' => $tgl_voucher, 'a.kd_skpd' => $kd_skpd])->select(DB::raw("'1' as urut"), 'a.kd_skpd', 'a.tgl_voucher', 'a.no_voucher', 'a.no_sp2d as kegiatan', DB::raw("'' as rekening"), 'a.ket', DB::raw("'0' as terima"), DB::raw("'0' as keluar"), 'a.jns_spp', 'a.status_upload');

        $data2 = DB::table('trhtransout_cmsbank as a')->leftJoin('trdtransout_cmsbank as b', function ($join) {
            $join->on('a.no_voucher', '=', 'b.no_voucher');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(DB::raw("YEAR(a.tgl_voucher)"), $tahun_anggaran)->where(['a.tgl_voucher' => $tgl_voucher, 'a.kd_skpd' => $kd_skpd])->select(DB::raw("'2' as urut"), 'a.kd_skpd', 'a.tgl_voucher', 'a.no_voucher', 'b.kd_sub_kegiatan as kegiatan', 'b.kd_rek6 as rekening', DB::raw("b.nm_sub_kegiatan + ', ' + b.nm_rek6 as ket"), DB::raw("'0' as terima"), 'b.nilai as keluar', 'a.jns_spp', DB::raw("'' as status_upload"))->union($data1);

        $data3 = DB::table('trdtransout_transfercms as a')->where(DB::raw("YEAR(a.tgl_voucher)"), $tahun_anggaran)->where(['a.tgl_voucher' => $tgl_voucher, 'a.kd_skpd' => $kd_skpd])->select(DB::raw("'3' as urut"), 'a.kd_skpd', 'a.tgl_voucher', 'a.no_voucher', DB::raw("'Rek. Tujuan :' as kegiatan"), DB::raw("'' as rekening"), DB::raw("RTRIM(a.rekening_tujuan) + ' , AN : ' + RTRIM(a.nm_rekening_tujuan) as ket"), DB::raw("'0' as terima"), 'a.nilai as keluar', DB::raw("'' as jns_spp"), DB::raw("'' as status_upload"))->union($data2);

        $data4 = DB::table('trhtransout_cmsbank as a')->join('trhtrmpot_cmsbank as b', function ($join) {
            $join->on('a.no_voucher', '=', 'b.no_voucher');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdtrmpot_cmsbank as c', function ($join) {
            $join->on('b.no_bukti', '=', 'c.no_bukti');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        })->where(DB::raw("YEAR(a.tgl_voucher)"), $tahun_anggaran)->where(['a.tgl_voucher' => $tgl_voucher, 'a.kd_skpd' => $kd_skpd])->select(DB::raw("'4' as urut"), 'a.kd_skpd', 'a.tgl_voucher', 'a.no_voucher', 'b.kd_sub_kegiatan as kegiatan', 'c.kd_rek6 as rekening', DB::raw("'Terima ' + c.nm_rek6 as ket"), 'c.nilai as terima', DB::raw("'0' as keluar"), DB::raw("'' as jns_spp"), DB::raw("'' as status_upload"))->union($data3);

        $bank1 = DB::table('tr_setorsimpanan')->select('tgl_kas as tgl', 'no_kas as bku', 'keterangan as ket', 'nilai as jumlah', DB::raw("'1' as jns"), 'kd_skpd as kode');

        $bank2 = DB::table('TRHINLAIN')->select('tgl_bukti as tgl', 'no_bukti as bku', 'ket as ket', 'nilai as jumlah', DB::raw("'1' as jns"), 'kd_skpd as kode')->where('pay', 'BANK')->unionAll($bank1);

        $bank3 = DB::table('tr_jpanjar as c')->join('tr_panjar as d', function ($join) {
            $join->on('c.no_panjar_lalu', '=', 'd.no_panjar');
            $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        })->select('c.tgl_kas as tgl', 'c.no_kas as bku', 'c.keterangan as ket', 'c.nilai as jumlah', DB::raw("'1' as jns"), 'c.kd_skpd as kode')->where(['c.jns' => '1', 'c.kd_skpd' => $kd_skpd, 'd.pay' => 'BANK'])->unionAll($bank2);

        $bank4 = DB::table('trhtrmpot')->select('tgl_bukti as tgl', 'no_bukti as bku', 'ket as ket', 'nilai as jumlah', DB::raw("'1' as jns"), 'kd_skpd as kode')->where(['pay' => 'BANK', 'kd_skpd' => $kd_skpd])->unionAll($bank3);

        $bank5 = DB::table('tr_ambilsimpanan')->select('tgl_kas as tgl', 'no_kas as bku', 'keterangan as ket', 'nilai as jumlah', DB::raw("'2' as jns"), 'kd_skpd as kode')->unionAll($bank4);

        $bank6 = DB::table('trhoutlain')->select('tgl_bukti as tgl', 'no_bukti as bku', 'ket as ket', 'nilai as jumlah', DB::raw("'2' as jns"), 'kd_skpd as kode')->where('pay', 'BANK')->unionAll($bank5);

        $bank7 = DB::table('tr_panjar')->select('tgl_panjar as tgl', 'no_panjar as bku', 'keterangan as ket', 'nilai as jumlah', DB::raw("'2' as jns"), 'kd_skpd as kode')->where(['jns' => '1', 'kd_skpd' => $kd_skpd, 'pay' => 'BANK'])->unionAll($bank6);

        $leftjoin1 = DB::table('trspmpot')->select('no_spm', DB::raw("SUM(nilai) as pot"))->groupBy('no_spm');

        $bank8 = DB::table('trhtransout as a')->join('trhsp2d as b', 'a.no_sp2d', '=', 'b.no_sp2d')->leftJoinSub($leftjoin1, 'c', function ($join) {
            $join->on('b.no_spm', '=', 'c.no_spm');
        })->select('tgl_bukti as tgl', 'no_bukti as bku', 'ket as ket', DB::raw("total - ISNULL(pot,0) as jumlah"), DB::raw("'2' as jns"), 'a.kd_skpd as kode')->where(['pay' => 'BANK'])->unionAll($bank7);

        $bank9 = DB::table('trhkasin_pkd as a')->join('trdkasin_pkd as b', function ($join) {
            $join->on('a.no_sts', '=', 'b.no_sts');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['pot_khusus' => '0', 'bank' => 'BANK', 'a.kd_skpd' => $kd_skpd])->whereNotIn('jns_trans', ['4', '2'])->groupBy('a.tgl_sts', 'a.no_sts', 'a.keterangan', 'a.kd_skpd')->select('a.tgl_sts as tgl', 'a.no_sts as bku', 'a.keterangan as ket', DB::raw("SUM(b.rupiah) as jumlah"), DB::raw("'2' as jns"), 'a.kd_skpd as kode')->unionAll($bank8);

        $bank10 = DB::table('trhstrpot')->where(['kd_skpd' => $kd_skpd, 'pay' => 'BANK'])->select('tgl_bukti as tgl', 'no_bukti as bku', 'ket as ket', 'nilai as jumlah', DB::raw("'2' as jns"), 'kd_skpd as kode')->unionAll($bank9);

        $data = [
            'daerah' => DB::table('sclient')->select('daerah', 'kab_kota')->where(['kd_skpd' => $kd_skpd])->first(),
            'skpd' => DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'tgl_voucher' => $tgl_voucher,
            'data_cms' => DB::table(DB::raw("({$data4->toSql()}) AS sub"))
                ->mergeBindings($data4)
                ->orderBy('kd_skpd')
                ->orderBy('tgl_voucher')
                ->orderBy(DB::raw("CAST(no_voucher as INT)"))
                ->orderBy('urut')
                ->get(),
            'bank' => DB::table(DB::raw("({$bank10->toSql()}) AS sub"))
                ->select(DB::raw("SUM(CASE WHEN jns=1 THEN jumlah ELSE 0 END) as terima"), DB::raw("SUM(CASE WHEN jns=2 THEN jumlah ELSE 0 END) as keluar"))
                ->mergeBindings($bank10)
                ->whereRaw("tgl<='$tgl_voucher' AND kode='$kd_skpd'")
                ->first()
        ];

        return view('skpd.transaksi_cms.cetak')->with($data);
    }

    // VERIFIKASI DPR
    public function indexVerifikasi()
    {
        return view('skpd.verifikasi_dpr.index');
    }

    public function loadVerifikasi()
    {
        $data = DB::table('trhdpr as a')
            ->select('a.*')
            ->where(['a.kd_skpd' => Auth::user()->kd_skpd])
            ->orderBy('a.no_dpr')
            ->orderBy(DB::raw("CAST(a.no_urut as int)"))
            ->orderBy('a.kd_skpd')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<a href="' . route("dpr.detail_verifikasi", ['no_dpr' => Crypt::encrypt($row->no_dpr), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-primary btn-sm" style="margin-right:4px"><i class="uil-info-circle"></i></a>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function detailVerifikasi($no_dpr, $kd_skpd)
    {
        $no_dpr = Crypt::decrypt($no_dpr);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'dpr' => DB::table('trhdpr')
                ->where(['no_dpr' => $no_dpr, 'kd_skpd' => $kd_skpd])
                ->first(),
            'rincian_dpr' => DB::table('trddpr as a')
                ->join('trhdpr as b', function ($join) {
                    $join->on('a.no_dpr', '=', 'b.no_dpr');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('a.*')
                ->where(['b.no_dpr' => $no_dpr, 'b.kd_skpd' => $kd_skpd])
                ->get()
        ];

        return view('skpd.verifikasi_dpr.show')->with($data);
    }

    public function simpanVerifikasi(Request $request)
    {
        $data = $request->data;

        $data['rincian_rekening'] = json_decode($data['rincian_rekening'], true);

        $kode = array();
        if (!empty($data['rincian_rekening'])) {
            foreach ($data['rincian_rekening'] as $rincian_rekening) {
                $kode[] = $rincian_rekening['kode'];
            }
        } else {
            $kode[] = '';
        }

        DB::beginTransaction();
        try {
            DB::table('trhdpr')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_dpr' => $data['no_dpr']])
                ->update([
                    'status_verifikasi' => '1',
                    'keterangan_tolak' => $data['keterangan'],
                    'user_verif' => Auth::user()->nama,
                    'tgl_verif' => $data['tgl_verifikasi'],
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            DB::table('trddpr')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_dpr' => $data['no_dpr']])
                ->whereIn('kode', $kode)
                ->update([
                    'status' => '1'
                ]);

            DB::table('trddpr')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_dpr' => $data['no_dpr']])
                ->whereNotIn('kode', $kode)
                ->update([
                    'status' => '2'
                ]);

            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
                'error' => $e->getMessage()
            ]);
        }
    }
}
