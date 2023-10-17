<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DaftarPembayaranTagihanGabunganController extends Controller
{
    public function index()
    {
        return view('skpd.dpt_gabungan.index');
    }

    public function loadData()
    {
        $data = DB::table('trhdpt_gabungan as a')
            ->select('a.*')
            ->where(['a.kd_skpd' => Auth::user()->kd_skpd])
            ->orderBy('a.tgl_dpt')
            ->orderBy('a.no_dpt')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<a href="' . route("dpt_gabungan.edit", ['no_dpt' => Crypt::encrypt($row->no_dpt), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="fa fa-edit"></i></a>';
                if ($row->status == '1' || $row->status == '2') {
                    $btn .= "";
                } else {
                    $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_dpt . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
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
            'kd_skpd' => $kd_skpd,
            'sisa_kas' => sisa_bank_kkpd1()
        ];

        return view('skpd.dpt_gabungan.create')->with($data);
    }

    public function loadDpt(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $no_dpt_unit = $request->no_dpt_unit;

        $no_dpt = array();
        if (!empty($no_dpt_unit)) {
            foreach ($no_dpt_unit as $lpj) {
                $no_dpt[] = $lpj['no_dpt_unit'];
            }
        } else {
            $no_dpt[] = '';
        }

        $data = DB::table('trhdpt as a')
            ->selectRaw("a.*,(SELECT SUM(nilai) FROM trddpt WHERE no_dpt=a.no_dpt) AS nilai,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=a.kd_skpd) AS nm_skpd")
            ->where(['a.status' => '0', 'a.status_verifikasi' => '1'])
            ->whereRaw("LEFT(a.kd_skpd,17)=LEFT(?,17)", [$kd_skpd])
            ->whereRaw("a.no_dpt NOT IN (SELECT no_dpt_unit FROM trddpt_gabungan WHERE a.no_dpt=no_dpt_unit AND a.kd_skpd=kd_skpd AND (no_dpt <> '' OR kd_bp_skpd <> ''))")
            ->whereNotIn('no_dpt', $no_dpt)
            ->get();

        return response()->json($data);
    }

    public function simpan(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $list_dpt = array();
            if (!empty($data['detail_dpt'])) {
                foreach ($data['detail_dpt'] as $lpj) {
                    $list_dpt[] = $lpj['no_dpt_unit'];
                }
            } else {
                $list_dpt[] = '';
            }

            $no_dpt = $data['no_dpt'] . "/DPT/GLOBAL/" . $data['kd_skpd'] . "/" . tahun_anggaran();

            $cek_dpt = DB::table('trhdpt_gabungan')
                ->where(['no_dpt' => $no_dpt, 'kd_skpd' => $kd_skpd])
                ->count();

            if ($cek_dpt > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhdpt_gabungan')
                ->insert([
                    'no_dpt' => $no_dpt,
                    'tgl_dpt' => $data['tgl_dpt'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keterangan' => $data['keterangan'],
                    'status' => '0',
                    'username' => Auth::user()->nama,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);


            $data['rincian_input'] = json_decode($data['rincian_input'], true);

            if (isset($data['rincian_input'])) {
                DB::table('trddpt_gabungan')
                    ->insert(array_map(function ($value) use ($no_dpt, $kd_skpd) {
                        return [
                            'no_dpt' => $no_dpt,
                            'no_dpt_unit' => $value['no_dpt_unit'],
                            'kd_skpd' => $value['kd_skpd'],
                            'kd_bp_skpd' => $kd_skpd,
                            'nilai' => $value['nilai'],
                        ];
                    }, $data['rincian_input']));
            }


            DB::table('trhdpt')
                ->whereIn('no_dpt', $list_dpt)
                ->update([
                    'status' => '1',
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

    public function edit($no_dpt, $kd_skpd)
    {
        $no_dpt = Crypt::decrypt($no_dpt);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'kd_skpd' => $kd_skpd,
            'sisa_kas' => sisa_bank_kkpd1(),
            'dpt' => DB::table('trhdpt_gabungan')
                ->where(['no_dpt' => $no_dpt, 'kd_skpd' => $kd_skpd])
                ->first(),
            'rincian_dpt' => DB::table('trddpt_gabungan as a')
                ->join('trhdpt_gabungan as b', function ($join) {
                    $join->on('a.no_dpt', '=', 'b.no_dpt');
                    $join->on('a.kd_bp_skpd', '=', 'b.kd_skpd');
                })
                ->select('a.*')
                ->where(['b.no_dpt' => $no_dpt, 'b.kd_skpd' => $kd_skpd])
                ->get()
        ];

        return view('skpd.dpt_gabungan.edit')->with($data);
    }

    public function update(Request $request)
    {
        $data = $request->data;

        DB::beginTransaction();
        try {
            DB::table('trhdpt_gabungan')
                ->where(['no_dpt' => $data['no_dpt'], 'kd_skpd' => $data['kd_skpd']])
                ->update([
                    'tgl_dpt' => $data['tgl_dpt'],
                    'username' => Auth::user()->nama,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'keterangan' => $data['keterangan']
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
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {

            DB::update("UPDATE trhdpt set status=0 where no_dpt IN (select no_dpt_unit from trddpt_gabungan where kd_bp_skpd=? and no_dpt=?)", [$kd_skpd, $no_dpt]);

            DB::table('trhdpt_gabungan')
                ->where(['no_dpt' => $no_dpt, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trddpt_gabungan')
                ->where(['no_dpt' => $no_dpt, 'kd_bp_skpd' => $kd_skpd])
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
