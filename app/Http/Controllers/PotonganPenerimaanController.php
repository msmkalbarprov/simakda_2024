<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PotonganPenerimaanController extends Controller
{
    public function index()
    {
        return view('skpd.potongan_ppkd.index');
    }

    public function load()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::select("SELECT * FROM trhkasin_ppkd_pot where kd_skpd=?", [$kd_skpd]);

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("potongan_ppkd.edit", ['no_kas' => Crypt::encrypt($row->no_kas), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_sts . '\',\'' . $row->no_kas . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambah()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        if ($kd_skpd != '5.02.0.00.0.00.02.0000') {
            return back();
        }

        $data = [
            'daftar_jenis' => DB::table('trdrka as a')
                ->select('kd_rek6', 'nm_rek6')
                ->whereRaw("left(kd_rek6,1)=? and kd_skpd=?", ['4', '5.02.0.00.0.00.02.0000'])
                ->orderBy('kd_rek6')
                ->distinct()
                ->get(),
            'daftar_pengirim' => DB::table('ms_pengirim as a')
                ->where(['kd_skpd' => $kd_skpd])
                ->orderByRaw("kd_pengirim")
                ->get(),
            'daftar_rkud' => DB::table('ms_rek_kasda')
                ->get(),
        ];

        return view('skpd.potongan_ppkd.create')->with($data);
    }

    // public function noBukti(Request $request)
    // {
    //     $kd_skpd = Auth::user()->kd_skpd;

    //     $data = DB::table('trhkasin_pkd as a')
    //         ->join('trdkasin_pkd as b', function ($join) {
    //             $join->on('a.no_sts', '=', 'b.no_sts');
    //             $join->on('a.kd_skpd', '=', 'b.kd_skpd');
    //         })
    //         ->leftJoin('ms_rek6 as c', function ($join) {
    //             $join->on('b.kd_rek6', '=', 'c.kd_rek6');
    //         })
    //         ->selectRaw("a.*, b.kd_sub_kegiatan, b.kd_rek6, c.nm_rek6,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd")
    //         ->where(['a.kd_skpd' => $kd_skpd, 'a.jns_trans' => '4'])
    //         ->whereRaw("a.no_sts NOT IN (SELECT no_sts FROM trhkasin_ppkd_pot WHERE kd_skpd=?)", [$kd_skpd])
    //         ->orderByRaw("CAST(REPLACE(a.no_sts,'/BP','') as int)")
    //         ->take(10)
    //         ->get();

    //     return response()->json([
    //         'data_sts' => $data,
    //         'no_urut' => nomor_urut_ppkd()
    //     ]);
    // }

    public function noBukti(Request $request)
    {
        $kd_skpd    = Auth::user()->kd_skpd;
        $term       = $request->term;
        $no_urut    = nomor_urut_ppkd();
        if (isset($term)) {
            $data = DB::table('trhkasin_pkd as a')
            ->join('trdkasin_pkd as b', function ($join) {
                $join->on('a.no_sts', '=', 'b.no_sts');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->leftJoin('ms_rek6 as c', function ($join) {
                $join->on('b.kd_rek6', '=', 'c.kd_rek6');
            })
            ->selectRaw("$no_urut as no_urut,a.*,a.no_sts as id, CAST(a.tgl_sts as varchar)+' | '+cast(a.total as varchar) as text, b.kd_sub_kegiatan, b.kd_rek6, c.nm_rek6,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd")
            ->where(['a.kd_skpd' => $kd_skpd, 'a.jns_trans' => '4'])
            ->whereRaw("a.no_sts NOT IN (SELECT no_sts FROM trhkasin_ppkd_pot WHERE kd_skpd=?)", [$kd_skpd])
            ->orderByRaw("CAST(REPLACE(a.no_sts,'/BP','') as int)")
            ->when($term, function ($query, $term) {
                $query->where(function ($query) use ($term) {
                    $query->orWhere('a.no_sts', 'like', '%' . $term . '%')
                        ->orWhere('a.no_sts', 'like', '%' . $term . '%');
                });
            })
            ->get();
        }else {
            
            $data = DB::table('trhkasin_pkd as a')
                ->join('trdkasin_pkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->leftJoin('ms_rek6 as c', function ($join) {
                    $join->on('b.kd_rek6', '=', 'c.kd_rek6');
                })
                ->selectRaw("$no_urut as no_urut,a.*,a.no_sts as id,  CAST(a.tgl_sts as varchar)+' | '+cast(a.total as varchar) as text, b.kd_sub_kegiatan, b.kd_rek6, c.nm_rek6,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd")
                ->where(['a.kd_skpd' => $kd_skpd, 'a.jns_trans' => '4'])
                ->whereRaw("a.no_sts NOT IN (SELECT no_sts FROM trhkasin_ppkd_pot WHERE kd_skpd=?)", [$kd_skpd])
                ->orderByRaw("CAST(REPLACE(a.no_sts,'/BP','') as int)")
                ->take(10)
                ->get();
        }
            
        return response()->json([
            'results' => $data
        ]);
    }

    public function urut(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::table('trhkasin_pkd')
            ->selectRaw("count(no_sts)+1 as nomor")
            ->where(['kd_skpd' => $kd_skpd, 'jns_trans' => '4'])
            ->first();

        return response()->json($data);
    }

    public function simpan(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $cek_terima = DB::table('trhkasin_ppkd_pot')
                ->where(['no_sts' => $data['no_sts'], 'kd_skpd' => '5.02.0.00.0.00.02.0000'])
                ->count();

            if ($cek_terima > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhkasin_ppkd_pot')->insert([
                'no_kas' => $data['no_kas'],
                'tgl_kas' => $data['tgl_kas'],
                'no_sts' => $data['no_sts'],
                'tgl_sts' => $data['tgl_sts'],
                'kd_skpd' => $kd_skpd,
                'keterangan' => $data['keterangan'],
                'total' => $data['nilai'],
                'kd_bank' => '1',
                'kd_sub_kegiatan' => '5.02.00.0.00.0004',
                'jns_trans' => '4',
                'rek_bank' => $data['rkud'],
                'sumber' => $data['pengirim'],
                'kd_rek6' => $data['jenis'],
                'pot_khusus' => '0',
                'no_sp2d' => '',
                'jns_cp' => '',
            ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'nomor' => $data['no_kas']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
            ]);
        }
    }

    public function edit($no_kas, $kd_skpd)
    {
        $no_kas = Crypt::decrypt($no_kas);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        if ($kd_skpd != '5.02.0.00.0.00.02.0000') {
            return back();
        }

        $data = [
            'daftar_jenis' => DB::table('trdrka as a')
                ->select('kd_rek6', 'nm_rek6')
                ->whereRaw("left(kd_rek6,1)=? and kd_skpd=?", ['4', '5.02.0.00.0.00.02.0000'])
                ->orderBy('kd_rek6')
                ->distinct()
                ->get(),
            'daftar_pengirim' => DB::table('ms_pengirim as a')
                ->where(['kd_skpd' => $kd_skpd])
                ->orderByRaw("kd_pengirim")
                ->get(),
            'daftar_rkud' => DB::table('ms_rek_kasda')
                ->get(),
            'potongan' => DB::table('trhkasin_ppkd_pot')
                ->where(['no_kas' => $no_kas, 'kd_skpd' => $kd_skpd])
                ->first(),
            'data_sts' => DB::table('trhkasin_pkd as a')
                ->join('trdkasin_pkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->leftJoin('ms_rek6 as c', function ($join) {
                    $join->on('b.kd_rek6', '=', 'c.kd_rek6');
                })
                ->selectRaw("a.*, b.kd_sub_kegiatan, b.kd_rek6, c.nm_rek6,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd")
                ->where(['a.kd_skpd' => $kd_skpd, 'a.jns_trans' => '4'])
                ->orderByRaw("CAST(REPLACE(a.no_sts,'/BP','') as int)")
                ->get()
        ];

        return view('skpd.potongan_ppkd.edit')->with($data);
    }

    public function update(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhkasin_ppkd_pot')
                ->where([
                    'no_kas' => $data['no_kas'],
                    'no_sts' => $data['no_sts'],
                ])
                ->update([
                    'tgl_kas' => $data['tgl_kas'],
                    'total' => $data['nilai'],
                    'keterangan' => $data['keterangan']
                ]);

            DB::commit();
            return response()->json([
                'message' => '1',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
            ]);
        }
    }

    public function hapus(Request $request)
    {
        $no_sts = $request->no_sts;
        $no_kas = $request->no_kas;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhkasin_ppkd_pot')
                ->where([
                    'no_sts' => $no_sts,
                    'no_kas' => $no_kas,
                    'kd_skpd' => $kd_skpd,
                    'jns_trans' => '4',
                ])
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
}
