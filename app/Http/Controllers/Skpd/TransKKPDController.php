<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransKKPDController extends Controller
{
    public function index()
    {
        return view('skpd.trans_kkpd.index');
    }

    public function loadData()
    {
        $data = DB::table('trhtransout_kkpd as a')
            ->select('a.*')
            ->where(['a.kd_skpd' => Auth::user()->kd_skpd])
            ->orderBy('a.no_voucher')
            ->orderBy('a.kd_skpd')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<a href="' . route("trans_kkpd.edit", ['no_voucher' => Crypt::encrypt($row->no_voucher), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="fa fa-edit"></i></a>';
                // if ($row->status_upload != '1') {
                //     $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_voucher . '\',\'' . $row->no_bukti . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
                // } else {
                //     $btn .= '';
                // }
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'daftar_dpt' => DB::table('trhdpt as c')
                ->select('c.*')
                ->selectRaw("(SELECT SUM(nilai) from trddpt a inner join trhdpt b on a.no_dpt=b.no_dpt and a.kd_skpd=b.kd_skpd where a.no_dpt=c.no_dpt and a.kd_skpd=c.kd_skpd) as nilai")
                ->where(['kd_skpd' => Auth::user()->kd_skpd, 'status_verifikasi' => '1', 'status' => '0'])
                ->get(),
            'sisa_kas' => sisa_bank_kkpd1()
        ];

        return view('skpd.trans_kkpd.create')->with($data);
    }

    public function loadDpt(Request $request)
    {
        $no_dpt = $request->no_dpt;
        $kd_skpd = $request->kd_skpd;
        $jenis = $request->jenis;

        $data = DB::table('trddpt as a')
            ->join('trhdpt as b', function ($join) {
                $join->on('a.no_dpt', '=', 'b.no_dpt');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->select('a.*')
            ->selectRaw("(select nm_sumber_dana1 from sumber_dana where kd_sumber_dana1=a.sumber) as nm_sumber")
            ->where(['b.no_dpt' => $no_dpt, 'b.kd_skpd' => $kd_skpd, 'b.status' => '0', 'b.status_verifikasi' => '1'])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function no_urut()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $urut1 = DB::table('trhtransout_kkpd')
            ->where(['kd_skpd' => $kd_skpd])
            ->select('no_voucher as nomor', DB::raw("'Daftar Transaksi Non Tunai KKPD' as ket"), 'kd_skpd');

        $urut2 = DB::table('trhtrmpot_kkpd')
            ->select('no_bukti as nomor', DB::raw("'Potongan Pajak Transaksi Non Tunai' as ket"), 'kd_skpd')
            ->where(['kd_skpd' => $kd_skpd])
            ->unionAll($urut1);

        $urut = DB::table(DB::raw("({$urut2->toSql()}) AS sub"))
            ->select(DB::raw("CASE WHEN MAX(nomor+1) IS NULL THEN 1 ELSE MAX(nomor+1) END AS nomor"))
            ->mergeBindings($urut2)
            ->first();

        return $urut->nomor;
    }

    public function simpan(Request $request)
    {
        $data = $request->data;

        DB::beginTransaction();
        try {
            $cek = DB::table('trhtransout_kkpd')
                ->where(['no_dpt' => $data['no_dpt'], 'kd_skpd' => $data['kd_skpd']])
                ->count();

            if ($cek > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            $data['rincian_rekening'] = json_decode($data['rincian_rekening'], true);

            DB::table('trhtransout_kkpd')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_dpt' => $data['no_dpt']])
                ->delete();

            $rincian_data = $data['rincian_rekening'];

            foreach ($rincian_data as $rincian => $value) {
                $no_urut = $this->no_urut();

                $input_trh = [
                    'no_voucher' => $no_urut,
                    'tgl_voucher' => $data['tgl_voucher'],
                    'no_bukti' => $no_urut,
                    'tgl_bukti' => $data['tgl_voucher'],
                    'ket' => $data['keterangan'],
                    'username' => Auth::user()->nama,
                    'tgl_update' => date('Y-m-d H:i:s'),
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => nama_skpd($data['kd_skpd']),
                    'total' => $rincian_data[$rincian]['nilai'],
                    'rekening_awal' => cari_rekening_awal($data['kd_skpd']),
                    'jns_spp' => '1',
                    'pay' => 'BANK',
                    'status_validasi' => '0',
                    'status_upload' => '0',
                    'status_verifikasi' => '0',
                    'no_dpt' => $data['no_dpt']
                ];
                DB::table('trhtransout_kkpd')
                    ->insert($input_trh);

                $input_trd = [
                    'no_voucher' => $no_urut,
                    'kd_sub_kegiatan' => $rincian_data[$rincian]['kd_sub_kegiatan'],
                    'nm_sub_kegiatan' => $rincian_data[$rincian]['nm_sub_kegiatan'],
                    'kd_rek6' => $rincian_data[$rincian]['kd_rek6'],
                    'nm_rek6' => $rincian_data[$rincian]['nm_rek6'],
                    'nilai' => $rincian_data[$rincian]['nilai'],
                    'kd_skpd' => $data['kd_skpd'],
                    'sumber' => $rincian_data[$rincian]['sumber'],
                ];
                DB::table('trdtransout_kkpd')
                    ->insert($input_trd);
            }

            DB::table('trhdpt')
                ->where(['no_dpt' => $data['no_dpt'], 'kd_skpd' => $data['kd_skpd']])
                ->update([
                    'status' => '1'
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

    public function edit($no_voucher, $kd_skpd)
    {
        $no_voucher = Crypt::decrypt($no_voucher);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'kkpd' => DB::table('trhtransout_kkpd')
                ->where(['no_voucher' => $no_voucher, 'kd_skpd' => $kd_skpd])
                ->first(),
            'rincian_kkpd' => DB::table('trdtransout_kkpd as a')
                ->join('trhtransout_kkpd as b', function ($join) {
                    $join->on('a.no_voucher', '=', 'b.no_voucher');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('a.*')
                ->where(['b.no_voucher' => $no_voucher, 'b.kd_skpd' => $kd_skpd])
                ->get()
        ];

        return view('skpd.trans_kkpd.edit')->with($data);
    }

    public function update(Request $request)
    {
        $data = $request->data;

        DB::beginTransaction();
        try {
            DB::table('trhtransout_kkpd')
                ->where(['no_voucher' => $data['no_voucher'], 'kd_skpd' => $data['kd_skpd'], 'no_dpt' => $data['no_dpt']])
                ->update([
                    'tgl_voucher' => $data['tgl_voucher'],
                    'tgl_bukti' => $data['tgl_voucher'],
                    'ket' => $data['keterangan'],
                    'username' => Auth::user()->nama,
                    'tgl_update' => date('Y-m-d H:i:s'),
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
        $no_dpt = $request->no_dpt;
        $no_dpr = $request->no_dpr;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trddpt')
                ->where(['no_dpt' => $no_dpt, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trhdpt')
                ->where(['no_dpt' => $no_dpt, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trhdpr')
                ->where(['no_dpr' => $no_dpr, 'kd_skpd' => $kd_skpd])
                ->update([
                    'status' => '0'
                ]);

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

    // VERIFIKASI DPT
    public function indexVerifikasi()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'sisa_kas' => sisa_bank_kkpd1()
        ];

        return view('skpd.verifikasi_dpt.index')->with($data);
    }

    public function loadVerifikasi()
    {
        $data = DB::table('trhdpt as a')
            ->select('a.*')
            ->where(['a.kd_skpd' => Auth::user()->kd_skpd])
            ->orderBy('a.no_dpt')
            ->orderBy(DB::raw("CAST(a.no_urut as int)"))
            ->orderBy('a.kd_skpd')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                // $btn = '<a href="' . route("dpt.detail_verifikasi", ['no_dpt' => Crypt::encrypt($row->no_dpt), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-primary btn-sm" style="margin-right:4px"><i class="uil-info-circle"></i></a>';
                $btn = '<a href="javascript:void(0);" style="margin-right:4px" onclick="detail(\'' . $row->no_dpt . '\', \'' . $row->no_dpr . '\', \'' . $row->kd_skpd . '\', \'' . $row->tgl_dpt . '\', \'' . $row->tgl_dpr . '\', \'' . $row->nm_skpd . '\', \'' . $row->status . '\', \'' . $row->status_verifikasi . '\', \'' . $row->tgl_verifikasi . '\');" class="btn btn-primary btn-sm"><i class="uil-info-circle"></i></a>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function detailVerifikasi(Request $request)
    {
        $no_dpt = $request->no_dpt;
        $kd_skpd = $request->kd_skpd;

        $data = DB::table('trddpt as a')
            ->join('trhdpt as b', function ($join) {
                $join->on('a.no_dpt', '=', 'b.no_dpt');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->select('a.*')
            ->selectRaw("(select nm_sumber_dana1 from sumber_dana where a.sumber=kd_sumber_dana1) as nm_sumber")
            ->where(['b.no_dpt' => $no_dpt, 'b.kd_skpd' => $kd_skpd])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function simpanVerifikasi(Request $request)
    {
        $no_dpt = $request->no_dpt;
        $kd_skpd = $request->kd_skpd;
        $tgl_verifikasi = $request->tgl_verifikasi;
        $jenis = $request->jenis;

        DB::beginTransaction();
        try {
            DB::table('trhdpt')
                ->where(['kd_skpd' => $kd_skpd, 'no_dpt' => $no_dpt])
                ->update([
                    'status_verifikasi' => $jenis == 'terima' ? '1' : '0',
                    'user_verif' => $jenis == 'terima' ? Auth::user()->nama : '',
                    'tgl_verifikasi' => $jenis == 'terima' ? $tgl_verifikasi : '',
                    'updated_at' => date('Y-m-d H:i:s')
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
