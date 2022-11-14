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

        if ($jenis_transaksi == '1') {
            $data = DB::table('trhsp2d')->select('no_sp2d', 'jns_spp', DB::raw("CASE jns_spp WHEN '4' THEN 'LS GAJI' WHEN '6' THEN 'LS BARANG/JASA' WHEN '1' THEN 'UP' WHEN '2' THEN 'GU' ELSE 'TU' END as jns_cp"))->where(['kd_skpd' => $kd_skpd])->get();
        } elseif ($jenis_transaksi == '5') {
            $data = DB::table('trhsp2d')->select('no_sp2d', 'jns_spp', DB::raw("CASE jns_spp WHEN '4' THEN 'LS GAJI' WHEN '6' THEN 'LS BARANG/JASA' WHEN '1' THEN 'UP' WHEN '5' THEN 'LS PIHAK KETIGA LAINNYA' WHEN '2' THEN 'GU' ELSE 'TU' END as jns_cp"))->where(['kd_skpd' => $kd_skpd])->whereIn('jns_spp', ['4', '5', '6'])->get();
        }

        return response()->json($data);
    }

    public function kegiatan(Request $request)
    {
        $no_sp2d = $request->no_sp2d;

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

        return response()->json($data);
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

        if ($jenis_transaksi == '5') {
            $data1 = DB::table('trdspp as a')->join('trhspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('trhsp2d as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })->select('a.kd_rek6', 'a.nm_rek6', DB::raw("SUM(a.nilai) as nilai"), DB::raw("(SELECT SUM(nilai) FROM trdtransout WHERE no_sp2d=c.no_sp2d AND kd_sub_kegiatan=a.kd_sub_kegiatan AND kd_rek6=a.kd_rek6) as transaksi"))->selectRaw("(SELECT f.rupiah FROM trhkasin_pkd e JOIN trdkasin_pkd f ON e.no_sts=f.no_sts AND e.kd_skpd=f.kd_skpd WHERE f.kd_sub_kegiatan=a.kd_sub_kegiatan AND e.no_sp2d=? AND f.kd_rek6=a.kd_rek6) as cp", [$no_sp2d])->where(['c.no_sp2d' => $no_sp2d, 'c.kd_skpd' => $kd_skpd, 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])->whereNotIn('a.kd_rek6', $kd_rek6)->groupBy('kd_rek6', 'nm_rek6', 'no_sp2d', 'a.kd_sub_kegiatan');

            $data = DB::table(DB::raw("({$data1->toSql()}) AS sub"))
                ->select('sub.*', DB::raw("nilai - isnull(transaksi,0) - isnull(cp,0) as sisa"))
                ->mergeBindings($data1)
                ->get();
        } else if ($jenis_transaksi == '1') {
            $data = DB::table('ms_rek6')->select('kd_rek6', 'nm_rek6')->whereRaw("LEFT(kd_rek6,4)=?", ['1101'])->whereNotIn('kd_rek6', $kd_rek6)->get();
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
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
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
                        'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
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
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
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
                        'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
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
