<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DaftarPembayaranTagihanController extends Controller
{
    public function index()
    {
        return view('skpd.dpt.index');
    }

    public function loadData()
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
                $btn = '<a href="' . route("dpt.edit", ['no_dpt' => Crypt::encrypt($row->no_dpt), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="fa fa-edit"></i></a>';
                if ($row->status_verifikasi != '1') {
                    $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_dpt . '\',\'' . $row->no_dpr . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
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
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'rek_kkpd' => DB::table('ms_kkpd')
                ->where(['kd_skpd' => Auth::user()->kd_skpd])
                ->get(),
            'daftar_dpr' => DB::table('trhdpr as c')
                ->select('c.*')
                ->selectRaw("(SELECT SUM(nilai) from trddpr a inner join trhdpr b on a.no_dpr=b.no_dpr and a.kd_skpd=b.kd_skpd where a.no_dpr=c.no_dpr and a.kd_skpd=c.kd_skpd and a.status='1') as nilai")
                ->where(['kd_skpd' => Auth::user()->kd_skpd, 'status_verifikasi' => '1', 'status' => '0'])
                ->get(),
            'sisa_kas' => sisa_bank_kkpd1()
        ];

        return view('skpd.dpt.create')->with($data);
    }

    public function no_urut()
    {
        $urut1 = DB::table('trhdpt')
            ->where(['kd_skpd' => Auth::user()->kd_skpd])
            ->select('no_urut as nomor', DB::raw("'Daftar Pembayaran Tagihan' as ket"), 'kd_skpd');

        $urut = DB::table(DB::raw("({$urut1->toSql()}) AS sub"))
            ->select(DB::raw("CASE WHEN MAX(nomor+1) IS NULL THEN 1 ELSE MAX(nomor+1) END AS nomor"))
            ->mergeBindings($urut1)
            ->first();

        return response()->json($urut->nomor);
    }

    public function detailDpr(Request $request)
    {
        $no_dpr = $request->no_dpr;
        $kd_skpd = $request->kd_skpd;
        $jenis = $request->jenis;

        if ($jenis == 'create') {
            $data = DB::table('trddpr as a')
                ->join('trhdpr as b', function ($join) {
                    $join->on('a.no_dpr', '=', 'b.no_dpr');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('a.*')
                ->selectRaw("(select nm_sumber_dana1 from sumber_dana where a.sumber=kd_sumber_dana1) as nm_sumber")
                ->where(['b.no_dpr' => $no_dpr, 'b.kd_skpd' => $kd_skpd, 'a.status' => '1'])
                ->get();
        } else if ($jenis == 'edit') {
            $data = DB::table('trddpt as a')
                ->join('trhdpt as b', function ($join) {
                    $join->on('a.no_dpt', '=', 'b.no_dpt');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('a.*')
                ->selectRaw("(select nm_sumber_dana1 from sumber_dana where a.sumber=kd_sumber_dana1) as nm_sumber")
                ->where(['b.no_dpt' => $no_dpr, 'b.kd_skpd' => $kd_skpd])
                ->get();
        }

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function simpan(Request $request)
    {
        $data = $request->data;

        DB::beginTransaction();
        try {
            $cek = DB::table('trhdpt')
                ->where(['no_dpt' => $data['no_dpt'], 'kd_skpd' => $data['kd_skpd']])
                ->count();

            if ($cek > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhdpt')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_dpt' => $data['no_dpt']])
                ->delete();

            DB::table('trhdpt')
                ->insert([
                    'no_dpt' => $data['no_dpt'],
                    'tgl_dpt' => $data['tgl_dpt'],
                    'no_dpr' => $data['no_dpr'],
                    'tgl_dpr' => $data['tgl_dpr'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'total' => $data['total_belanja'],
                    'username' => Auth::user()->nama,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'status' => 0,
                    'status_verifikasi' => '0',
                    'no_urut' => $data['no_urut'],
                    'tgl_verifikasi' => '',
                    'user_verif' => '',
                ]);

            DB::table('trddpt')
                ->where(['no_dpt' => $data['no_dpt'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            $data['rincian_rekening'] = json_decode($data['rincian_rekening'], true);

            if (isset($data['rincian_rekening'])) {
                DB::table('trddpt')
                    ->insert(array_map(function ($value) use ($data) {
                        return [
                            'no_dpt' => $data['no_dpt'],
                            'kd_skpd' => $data['kd_skpd'],
                            'nm_skpd' => nama_skpd($data['kd_skpd']),
                            'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                            'nm_sub_kegiatan' => $value['nm_sub_kegiatan'],
                            'kd_rek6' => $value['kd_rek6'],
                            'nm_rek6' => $value['nm_rek6'],
                            'nilai' => $value['nilai'],
                            'uraian' => $value['uraian'],
                            'bukti' => $value['bukti'],
                            'sumber' => $value['sumber'],
                            'pembayaran' => $value['pembayaran'],
                            'status' => '0'
                        ];
                    }, $data['rincian_rekening']));
            }

            DB::table('trhdpr')
                ->where(['no_dpr' => $data['no_dpr'], 'kd_skpd' => $data['kd_skpd']])
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

    public function edit($no_dpt, $kd_skpd)
    {
        $no_dpt = Crypt::decrypt($no_dpt);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'rek_kkpd' => DB::table('ms_kkpd')
                ->where(['kd_skpd' => Auth::user()->kd_skpd])
                ->get(),
            'daftar_dpr' => DB::table('trhdpr as c')
                ->select('c.*')
                ->selectRaw("(SELECT SUM(nilai) from trddpr a inner join trhdpr b on a.no_dpr=b.no_dpr and a.kd_skpd=b.kd_skpd where a.no_dpr=c.no_dpr and a.kd_skpd=c.kd_skpd and a.status='1') as nilai")
                ->where(['kd_skpd' => Auth::user()->kd_skpd, 'status_verifikasi' => '1', 'status' => '0'])
                ->get(),
            'sisa_kas' => sisa_bank_kkpd1(),
            'dpt' => DB::table('trhdpt')
                ->where(['no_dpt' => $no_dpt, 'kd_skpd' => $kd_skpd])
                ->first(),
            'rincian_dpt' => DB::table('trddpt as a')
                ->join('trhdpt as b', function ($join) {
                    $join->on('a.no_dpt', '=', 'b.no_dpt');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('a.*')
                ->where(['b.no_dpt' => $no_dpt, 'b.kd_skpd' => $kd_skpd])
                ->get()
        ];

        return view('skpd.dpt.edit')->with($data);
    }

    public function update(Request $request)
    {
        $data = $request->data;

        DB::beginTransaction();
        try {
            DB::table('trhdpt')
                ->where(['no_dpt' => $data['no_dpt'], 'kd_skpd' => $data['kd_skpd']])
                ->update([
                    'tgl_dpt' => $data['tgl_dpt'],
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
