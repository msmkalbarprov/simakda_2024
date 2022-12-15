<?php

namespace App\Http\Controllers\Skpd\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PenerimaanController extends Controller
{
    // Penerimaan Tahun Lalu
    public function indexPenerimaanLalu()
    {
        return view('skpd.penerimaan_tahun_lalu.index');
    }

    public function loadDataPenerimaanLalu()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('tr_terima as a')
            ->selectRaw("a.*")
            ->where(['a.kd_skpd' => $kd_skpd, 'a.jenis' => '2'])
            ->orderBy('tgl_terima')
            ->orderBy('no_terima')
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            if ($row->kunci != '1') {
                $btn = '<a href="' . route("penerimaan_lalu.edit", Crypt::encrypt($row->no_terima)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
                $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_terima . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            } else {
                $btn = '';
            }
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahPenerimaanLalu()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $status_ang_pend = DB::table('trhrka_pend')
            ->select('jns_ang')
            ->where(['kd_skpd' => $kd_skpd, 'status' => '1'])
            ->orderByDesc('tgl_dpa')
            ->first();
        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_akun' => DB::table('trdrka_pend as a')
                ->leftJoin('ms_rek6 as b', 'a.kd_rek6', '=', 'b.kd_rek6')
                ->leftJoin('ms_rek5 as c', DB::raw("left(a.kd_rek6,8)"), '=', 'c.kd_rek5')
                ->selectRaw("a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek5, a.kd_sub_kegiatan")
                ->where(['a.kd_skpd' => $kd_skpd])
                ->whereRaw("left(a.kd_rek6,1)=? and a.jns_ang=?", ['4', $status_ang_pend->jns_ang])
                ->orderBy('kd_rek6')
                ->distinct()
                ->get()
        ];

        return view('skpd.penerimaan_tahun_lalu.create')->with($data);
    }

    public function simpanPenerimaanLalu(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $cek1 = DB::table('tr_kunci')
                ->selectRaw("max(tgl_kunci) as tgl_kasda,''tgl_spj,? as tgl2", [$data['tgl_terima']])
                ->where(['kd_skpd' => $kd_skpd]);

            $cek2 = DB::table($cek1, 'a')
                ->selectRaw("CASE WHEN tgl2<=tgl_kasda THEN '1' ELSE '0' END as status_kasda,0 status_spj,*");

            $cek3 = DB::table('trhspj_terima_ppkd')
                ->selectRaw("''tgl_kasda,max(tgl_terima) as tgl_spj,? as tgl2", [$data['tgl_terima']])
                ->where(['kd_skpd' => $kd_skpd]);

            $cek4 = DB::table($cek3, 'a')
                ->selectRaw("0 status_kasda,CASE WHEN tgl2<=tgl_spj THEN '1' ELSE '0' END as status_spj,*")->unionAll($cek2);

            $cek = DB::table(DB::raw("({$cek4->toSql()}) AS sub"))
                ->selectRaw("sum(status_kasda) status_kasda, sum(status_spj) status_spj,max(tgl_kasda) tgl_kasda,max(tgl_spj) tgl_spj,max(tgl2) tgl2")
                ->mergeBindings($cek4)
                ->first();

            if ($cek->status_kasda == '1') {
                return response()->json([
                    'message' => '3'
                ]);
            } elseif ($cek->status_spj == '1') {
                return response()->json([
                    'message' => '3'
                ]);
            } else {
                $cek_terima = DB::table('tr_terima')->where(['no_terima' => $data['no_terima'], 'kd_skpd' => $kd_skpd])->count();
                if ($cek_terima > 0) {
                    return response()->json([
                        'message' => '2'
                    ]);
                }
            }

            DB::table('tr_terima')->insert([
                'no_terima' => $data['no_terima'],
                'tgl_terima' => $data['tgl_terima'],
                'no_tetap' => '',
                'tgl_tetap' => '',
                'sts_tetap' => '',
                'kd_skpd' => $data['kd_skpd'],
                'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                'kd_rek6' => $data['rekening'],
                'kd_rek_lo' => $data['kode_rek'],
                'nilai' => $data['nilai'],
                'keterangan' => $data['keterangan'],
                'jenis' => '2',
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

    public function editPenerimaanLalu($no_terima)
    {
        $no_terima = Crypt::decrypt($no_terima);
        $kd_skpd = Auth::user()->kd_skpd;
        $status_ang_pend = DB::table('trhrka_pend')
            ->select('jns_ang')
            ->where(['kd_skpd' => $kd_skpd, 'status' => '1'])
            ->orderByDesc('tgl_dpa')
            ->first();
        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_akun' => DB::table('trdrka_pend as a')
                ->leftJoin('ms_rek6 as b', 'a.kd_rek6', '=', 'b.kd_rek6')
                ->leftJoin('ms_rek5 as c', DB::raw("left(a.kd_rek6,8)"), '=', 'c.kd_rek5')
                ->selectRaw("a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek5, a.kd_sub_kegiatan")
                ->where(['a.kd_skpd' => $kd_skpd])
                ->whereRaw("left(a.kd_rek6,1)=? and a.jns_ang=?", ['4', $status_ang_pend->jns_ang])
                ->orderBy('kd_rek6')
                ->distinct()
                ->get(),
            'terima' => DB::table('tr_terima as a')
                ->selectRaw("a.*")
                ->where(['a.kd_skpd' => $kd_skpd, 'a.jenis' => '2', 'no_terima' => $no_terima])
                ->orderBy('tgl_terima')
                ->orderBy('no_terima')
                ->first()
        ];

        return view('skpd.penerimaan_tahun_lalu.edit')->with($data);
    }

    public function simpanEditPenerimaanLalu(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $cek1 = DB::table('tr_kunci')
                ->selectRaw("max(tgl_kunci) as tgl_kasda,''tgl_spj,? as tgl2", [$data['tgl_terima']])
                ->where(['kd_skpd' => $kd_skpd]);

            $cek2 = DB::table($cek1, 'a')
                ->selectRaw("CASE WHEN tgl2<=tgl_kasda THEN '1' ELSE '0' END as status_kasda,0 status_spj,*");

            $cek3 = DB::table('trhspj_terima_ppkd')
                ->selectRaw("''tgl_kasda,max(tgl_terima) as tgl_spj,? as tgl2", [$data['tgl_terima']])
                ->where(['kd_skpd' => $kd_skpd]);

            $cek4 = DB::table($cek3, 'a')
                ->selectRaw("0 status_kasda,CASE WHEN tgl2<=tgl_spj THEN '1' ELSE '0' END as status_spj,*")->unionAll($cek2);

            $cek = DB::table(DB::raw("({$cek4->toSql()}) AS sub"))
                ->selectRaw("sum(status_kasda) status_kasda, sum(status_spj) status_spj,max(tgl_kasda) tgl_kasda,max(tgl_spj) tgl_spj,max(tgl2) tgl2")
                ->mergeBindings($cek4)
                ->first();

            if ($cek->status_kasda == '1') {
                return response()->json([
                    'message' => '3'
                ]);
            } elseif ($cek->status_spj == '1') {
                return response()->json([
                    'message' => '3'
                ]);
            } else {
                $cek_terima = DB::table('tr_terima')->where(['no_terima' => $data['no_terima'], 'kd_skpd' => $kd_skpd])->count();
                if ($cek_terima > 0 && $data['no_terima'] != $data['no_simpan']) {
                    return response()->json([
                        'message' => '2'
                    ]);
                }
            }


            DB::table('tr_terima')->where(['kd_skpd' => $kd_skpd, 'no_terima' => $data['no_simpan']])->delete();

            DB::table('tr_terima')->insert([
                'no_terima' => $data['no_terima'],
                'tgl_terima' => $data['tgl_terima'],
                'no_tetap' => '',
                'tgl_tetap' => '',
                'sts_tetap' => '',
                'kd_skpd' => $data['kd_skpd'],
                'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                'kd_rek6' => $data['rekening'],
                'kd_rek_lo' => $data['kode_rek'],
                'nilai' => $data['nilai'],
                'keterangan' => $data['keterangan'],
                'jenis' => '2',
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

    public function hapusPenerimaanLalu(Request $request)
    {
        $no_terima = $request->no_terima;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('tr_terima')->where(['no_terima' => $no_terima, 'kd_skpd' => $kd_skpd, 'jenis' => '2'])->delete();

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

    // Penerimaan Tahun Ini
    public function indexPenerimaanIni()
    {
        return view('skpd.penerimaan_tahun_ini.index');
    }

    public function loadDataPenerimaanIni()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $spjbulan = cek_status_spj_pend($kd_skpd);
        $data = DB::table('tr_terima as a')
            ->selectRaw("no_terima,no_tetap,tgl_terima,tgl_tetap,kd_skpd,keterangan as ket, sumber,
        nilai, kd_rek6,kd_rek_lo,kd_sub_kegiatan,sts_tetap,(CASE WHEN month(tgl_terima)<=? THEN 1 ELSE 0 END) ketspj,user_name,kunci", [$spjbulan])
            ->where(['a.kd_skpd' => $kd_skpd])
            ->where(function ($query) {
                $query->where('jenis', '<>', '2')->orWhereNull('jenis');
            })
            ->orderBy('tgl_terima')
            ->orderBy('no_terima')
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            if ($row->kunci != '1') {
                $btn = '<a href="' . route("penerimaan_ini.edit", Crypt::encrypt($row->no_terima)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
                $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_terima . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            } else {
                $btn = '';
            }
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahPenerimaanIni()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $status_ang_pend = DB::table('trhrka_pend')
            ->select('jns_ang')
            ->where(['kd_skpd' => $kd_skpd, 'status' => '1'])
            ->orderByDesc('tgl_dpa')
            ->first();

        $tetap1 = DB::table('tr_tetap')
            ->selectRaw("no_tetap, tgl_tetap, kd_skpd, keterangan, nilai, kd_rek6, kd_rek_lo,
                (SELECT a.nm_rek6 FROM ms_rek6 a WHERE a.kd_rek6=tr_tetap.kd_rek6) as nm_rek")
            ->whereRaw("no_tetap not in(select no_tetap from tr_terima)")
            ->where(['kd_skpd' => $kd_skpd]);

        $from = DB::table('tr_tetap')
            ->selectRaw("*,(SELECT a.nm_rek6 FROM ms_rek6 a WHERE a.kd_rek6=tr_tetap.kd_rek6) as nm_rek")
            ->where(['kd_skpd' => $kd_skpd]);

        $join1 = DB::table('tr_terima')
            ->selectRaw("no_tetap as tetap,ISNULL(SUM(nilai),0) as nilai_terima")
            ->where(['kd_skpd' => $kd_skpd])
            ->groupBy('no_tetap');

        $tetap2 = DB::table($from, 'a')->leftJoinSub($join1, 'b', function ($join) {
            $join->on('a.no_tetap', '=', 'b.tetap');
        })
            ->selectRaw("no_tetap,tgl_tetap,kd_skpd,keterangan,ISNULL(nilai,0)-ISNULL(nilai_terima,0) as nilai,kd_rek6,kd_rek_lo,a.nm_rek ")
            ->whereRaw("nilai != nilai_terima")
            ->unionAll($tetap1);

        $tetap = DB::table(DB::raw("({$tetap2->toSql()}) AS sub"))
            ->mergeBindings($tetap2)
            ->orderBy('no_tetap')
            ->get();

        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_akun' => DB::table('trdrka_pend as a')
                ->leftJoin('ms_rek6 as b', 'a.kd_rek6', '=', 'b.kd_rek6')
                ->leftJoin('ms_rek5 as c', DB::raw("left(a.kd_rek6,8)"), '=', 'c.kd_rek5')
                ->selectRaw("a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek5, a.kd_sub_kegiatan")
                ->where(['a.kd_skpd' => $kd_skpd])
                ->whereRaw("left(a.kd_rek6,1)=? and a.jns_ang=?", ['4', $status_ang_pend->jns_ang])
                ->orderBy('kd_rek6')
                ->distinct()
                ->get(),
            'daftar_pengirim' => DB::table('ms_pengirim')
                ->select('kd_pengirim', 'nm_pengirim', 'kd_skpd')
                ->where(function ($query) use ($kd_skpd) {
                    if (substr($kd_skpd, 0, 17) == '5-02.0-00.0-00.02') {
                        $query->where('kd_skpd', $kd_skpd);
                    } else {
                        $query->whereRaw("left(kd_skpd,15)=left(?,15)", [$kd_skpd]);
                    }
                })
                ->orderByRaw("cast(kd_pengirim as int)")
                ->get(),
            'daftar_penetapan' => $tetap
        ];

        return view('skpd.penerimaan_tahun_ini.create')->with($data);
    }

    public function simpanPenerimaanIni(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;
        DB::beginTransaction();
        try {
            $cek1 = DB::table('tr_kunci')
                ->selectRaw("max(tgl_kunci) as tgl_kasda,''tgl_spj,? as tgl2", [$data['tgl_terima']])
                ->where(['kd_skpd' => $kd_skpd]);

            $cek2 = DB::table($cek1, 'a')
                ->selectRaw("CASE WHEN tgl2<=tgl_kasda THEN '1' ELSE '0' END as status_kasda,0 status_spj,*");

            $cek3 = DB::table('trhspj_terima_ppkd')
                ->selectRaw("''tgl_kasda,max(tgl_terima) as tgl_spj,? as tgl2", [$data['tgl_terima']])
                ->where(['kd_skpd' => $kd_skpd]);

            $cek4 = DB::table($cek3, 'a')
                ->selectRaw("0 status_kasda,CASE WHEN tgl2<=tgl_spj THEN '1' ELSE '0' END as status_spj,*")->unionAll($cek2);

            $cek = DB::table(DB::raw("({$cek4->toSql()}) AS sub"))
                ->selectRaw("sum(status_kasda) status_kasda, sum(status_spj) status_spj,max(tgl_kasda) tgl_kasda,max(tgl_spj) tgl_spj,max(tgl2) tgl2")
                ->mergeBindings($cek4)
                ->first();

            if ($cek->status_kasda == '1') {
                return response()->json([
                    'message' => '2'
                ]);
            } elseif ($cek->status_spj == '1') {
                return response()->json([
                    'message' => '3'
                ]);
            } else {
                $cek_terima = DB::table('tr_terima')->where(['no_terima' => $data['no_terima'], 'kd_skpd' => $kd_skpd])->count();
                if ($cek_terima > 0) {
                    return response()->json([
                        'message' => '4'
                    ]);
                }
            }

            DB::table('tr_terima')->insert([
                'no_terima' => $data['no_terima'],
                'tgl_terima' => $data['tgl_terima'],
                'no_tetap' => $data['no_tetap'],
                'tgl_tetap' => $data['tgl_tetap'],
                'sts_tetap' => $data['dengan_penetapan'],
                'kd_skpd' => $data['kd_skpd'],
                'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                'kd_rek6' => $data['kode_akun'],
                'kd_rek_lo' => $data['kode_rek'],
                'nilai' => $data['nilai'],
                'keterangan' => $data['keterangan'],
                'jenis' => '1',
                'sumber' => $data['kode_pengirim'],
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

    public function editPenerimaanIni($no_terima)
    {
        $no_terima = Crypt::decrypt($no_terima);
        $kd_skpd = Auth::user()->kd_skpd;
        $status_ang_pend = DB::table('trhrka_pend')
            ->select('jns_ang')
            ->where(['kd_skpd' => $kd_skpd, 'status' => '1'])
            ->orderByDesc('tgl_dpa')
            ->first();

        $tetap1 = DB::table('tr_tetap')
            ->selectRaw("no_tetap, tgl_tetap, kd_skpd, keterangan, nilai, kd_rek6, kd_rek_lo,
                (SELECT a.nm_rek6 FROM ms_rek6 a WHERE a.kd_rek6=tr_tetap.kd_rek6) as nm_rek")
            ->whereRaw("no_tetap not in(select no_tetap from tr_terima)")
            ->where(['kd_skpd' => $kd_skpd]);

        $from = DB::table('tr_tetap')
            ->selectRaw("*,(SELECT a.nm_rek6 FROM ms_rek6 a WHERE a.kd_rek6=tr_tetap.kd_rek6) as nm_rek")
            ->where(['kd_skpd' => $kd_skpd]);

        $join1 = DB::table('tr_terima')
            ->selectRaw("no_tetap as tetap,ISNULL(SUM(nilai),0) as nilai_terima")
            ->where(['kd_skpd' => $kd_skpd])
            ->groupBy('no_tetap');

        $tetap2 = DB::table($from, 'a')->leftJoinSub($join1, 'b', function ($join) {
            $join->on('a.no_tetap', '=', 'b.tetap');
        })
            ->selectRaw("no_tetap,tgl_tetap,kd_skpd,keterangan,ISNULL(nilai,0)-ISNULL(nilai_terima,0) as nilai,kd_rek6,kd_rek_lo,a.nm_rek ")
            ->whereRaw("nilai != nilai_terima")
            ->unionAll($tetap1);

        $tetap = DB::table(DB::raw("({$tetap2->toSql()}) AS sub"))
            ->mergeBindings($tetap2)
            ->orderBy('no_tetap')
            ->get();

        $data = [
            'terima' => DB::table('tr_terima')
                ->where(['no_terima' => $no_terima, 'kd_skpd' => $kd_skpd])
                ->first(),
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_akun' => DB::table('trdrka_pend as a')
                ->leftJoin('ms_rek6 as b', 'a.kd_rek6', '=', 'b.kd_rek6')
                ->leftJoin('ms_rek5 as c', DB::raw("left(a.kd_rek6,8)"), '=', 'c.kd_rek5')
                ->selectRaw("a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek5, a.kd_sub_kegiatan")
                ->where(['a.kd_skpd' => $kd_skpd])
                ->whereRaw("left(a.kd_rek6,1)=? and a.jns_ang=?", ['4', $status_ang_pend->jns_ang])
                ->orderBy('kd_rek6')
                ->distinct()
                ->get(),
            'daftar_pengirim' => DB::table('ms_pengirim')
                ->select('kd_pengirim', 'nm_pengirim', 'kd_skpd')
                ->where(function ($query) use ($kd_skpd) {
                    if (substr($kd_skpd, 0, 17) == '5-02.0-00.0-00.02') {
                        $query->where('kd_skpd', $kd_skpd);
                    } else {
                        $query->whereRaw("left(kd_skpd,15)=left(?,15)", [$kd_skpd]);
                    }
                })
                ->orderByRaw("cast(kd_pengirim as int)")
                ->get(),
            'daftar_penetapan' => $tetap
        ];

        return view('skpd.penerimaan_tahun_ini.edit')->with($data);
    }

    public function simpanEditPenerimaanIni(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;
        DB::beginTransaction();
        try {
            $cek1 = DB::table('tr_kunci')
                ->selectRaw("max(tgl_kunci) as tgl_kasda,''tgl_spj,? as tgl2", [$data['tgl_terima']])
                ->where(['kd_skpd' => $kd_skpd]);

            $cek2 = DB::table($cek1, 'a')
                ->selectRaw("CASE WHEN tgl2<=tgl_kasda THEN '1' ELSE '0' END as status_kasda,0 status_spj,*");

            $cek3 = DB::table('trhspj_terima_ppkd')
                ->selectRaw("''tgl_kasda,max(tgl_terima) as tgl_spj,? as tgl2", [$data['tgl_terima']])
                ->where(['kd_skpd' => $kd_skpd]);

            $cek4 = DB::table($cek3, 'a')
                ->selectRaw("0 status_kasda,CASE WHEN tgl2<=tgl_spj THEN '1' ELSE '0' END as status_spj,*")->unionAll($cek2);

            $cek = DB::table(DB::raw("({$cek4->toSql()}) AS sub"))
                ->selectRaw("sum(status_kasda) status_kasda, sum(status_spj) status_spj,max(tgl_kasda) tgl_kasda,max(tgl_spj) tgl_spj,max(tgl2) tgl2")
                ->mergeBindings($cek4)
                ->first();

            if ($cek->status_kasda == '1') {
                return response()->json([
                    'message' => '2'
                ]);
            } elseif ($cek->status_spj == '1') {
                return response()->json([
                    'message' => '3'
                ]);
            } else {
                $cek_terima = DB::table('tr_terima')->where(['no_terima' => $data['no_terima'], 'kd_skpd' => $kd_skpd])->count();
                if ($cek_terima > 0 && $data['no_terima'] != $data['no_simpan']) {
                    return response()->json([
                        'message' => '4'
                    ]);
                }
            }

            DB::table('tr_terima')->where(['no_terima' => $data['no_simpan'], 'kd_skpd' => $data['kd_skpd']])->delete();

            DB::table('tr_terima')->insert([
                'no_terima' => $data['no_terima'],
                'tgl_terima' => $data['tgl_terima'],
                'no_tetap' => $data['no_tetap'],
                'tgl_tetap' => $data['tgl_tetap'],
                'sts_tetap' => $data['dengan_penetapan'],
                'kd_skpd' => $data['kd_skpd'],
                'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                'kd_rek6' => $data['kode_akun'],
                'kd_rek_lo' => $data['kode_rek'],
                'nilai' => $data['nilai'],
                'keterangan' => $data['keterangan'],
                'jenis' => '1',
                'sumber' => $data['kode_pengirim'],
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

    public function hapusPenerimaanIni(Request $request)
    {
        $no_terima = $request->no_terima;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('tr_terima')->where(['no_terima' => $no_terima, 'kd_skpd' => $kd_skpd])->delete();

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

    // Penerimaan Lain PPKD
    public function indexPenerimaanPpkd()
    {
        return view('skpd.penerimaan_lain_ppkd.index');
    }

    public function loadDataPenerimaanPpkd()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhkasin_pkd as a')
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
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("penerimaan_ppkd.edit", Crypt::encrypt($row->no_sts)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_sts . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahPenerimaanPpkd()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'daftar_jenis' => DB::table('trdrka_pend as a')
                ->select('kd_rek6', 'nm_rek6')
                ->whereRaw("left(kd_rek6,1)=? and kd_skpd=?", ['4', '5.02.0.00.0.00.02.0000'])
                ->orderBy('kd_rek6')
                ->distinct()
                ->get(),
            'daftar_pengirim' => DB::table('ms_pengirim as a')
                ->where(['kd_skpd' => $kd_skpd])
                ->orderByRaw("cast(kd_pengirim as int)")
                ->get()
        ];

        return view('skpd.penerimaan_lain_ppkd.create')->with($data);
    }

    public function simpanPenerimaanPpkd(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $no_urut = DB::table('trhkasin_pkd')
                ->selectRaw("count(no_sts)+1 as nomor")
                ->where(['kd_skpd' => $kd_skpd, 'jns_trans' => '4'])
                ->first();
            $nomor = $no_urut->nomor;
            $cek_terima = DB::table('trhkasin_pkd')->where(['no_sts' => $nomor . '/BP', 'kd_skpd' => '5.02.0.00.0.00.02.0000'])->count();
            if ($cek_terima > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            $no_kas = nomor_tukd();

            DB::table('trhkasin_pkd')->insert([
                'no_sts' => $nomor . '/BP',
                'tgl_sts' => $data['tgl_kas'],
                'kd_skpd' => $kd_skpd,
                'keterangan' => $data['keterangan'],
                'total' => $data['nilai'],
                'kd_sub_kegiatan' => '5.02.00.0.00.04',
                'jns_trans' => '4',
                'no_kas' => $no_kas,
                'tgl_kas' => $data['tgl_kas'],
                'sumber' => $data['pengirim'],
                'user_name' => Auth::user()->nama,
                'no_cek' => '1',
                'status' => '1',
            ]);

            DB::table('trdkasin_pkd')->insert([
                'no_sts' => $nomor . '/BP',
                'kd_skpd' => $kd_skpd,
                'kd_rek6' => $data['jenis'],
                'rupiah' => $data['nilai'],
                'kd_sub_kegiatan' => '5.02.00.0.00.04',
                'sumber' => $data['pengirim'],
            ]);

            DB::table('trhkasin_ppkd')->insert([
                'no_kas' => $no_kas,
                'no_sts' => $nomor . '/BP',
                'kd_skpd' => $kd_skpd,
                'tgl_sts' => $data['tgl_kas'],
                'tgl_kas' => $data['tgl_kas'],
                'keterangan' => $data['keterangan'],
                'total' => $data['nilai'],
                'kd_bank' => '1',
                'kd_sub_kegiatan' => '5.02.00.0.00.04',
                'jns_trans' => '4',
                'rek_bank' => '',
                'sumber' => $data['pengirim'],
                'pot_khusus' => '0',
                'no_sp2d' => '',
                'jns_cp' => '',
            ]);

            DB::table('trdkasin_ppkd')->insert([
                'no_kas' => $no_kas,
                'kd_skpd' => $kd_skpd,
                'no_sts' => $nomor . '/BP',
                'kd_rek6' => $data['jenis'],
                'rupiah' => $data['nilai'],
                'kd_sub_kegiatan' => '5.02.00.0.00.04',
                'sumber' => $data['pengirim'],
            ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'nomor' => $nomor . '/BP'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
            ]);
        }
    }

    public function editPenerimaanPpkd($no_sts)
    {
        $no_sts = Crypt::decrypt($no_sts);
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'terima' => $data = DB::table('trhkasin_pkd as a')
                ->join('trdkasin_pkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("a.*,b.kd_rek6")
                ->where(['a.kd_skpd' => $kd_skpd, 'a.jns_trans' => '4', 'a.no_sts' => $no_sts])
                ->first(),
            'daftar_jenis' => DB::table('trdrka_pend as a')
                ->select('kd_rek6', 'nm_rek6')
                ->whereRaw("left(kd_rek6,1)=? and kd_skpd=?", ['4', '5.02.0.00.0.00.02.0000'])
                ->orderBy('kd_rek6')
                ->distinct()
                ->get(),
            'daftar_pengirim' => DB::table('ms_pengirim as a')
                ->where(['kd_skpd' => $kd_skpd])
                ->orderByRaw("cast(kd_pengirim as int)")
                ->get()
        ];
        // dd($data['terima']);
        return view('skpd.penerimaan_lain_ppkd.edit')->with($data);
    }

    public function simpanEditPenerimaanPpkd(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhkasin_pkd')
                ->where(['no_sts' => $data['no_kas'], 'kd_skpd' => $kd_skpd, 'jns_trans' => '4'])
                ->update([
                    'tgl_sts' => $data['tgl_kas'],
                    'keterangan' => $data['keterangan'],
                    'total' => $data['nilai'],
                    'tgl_kas' => $data['tgl_kas'],
                    'sumber' => $data['pengirim'],
                ]);

            DB::table('trdkasin_pkd')
                ->where(['no_sts' => $data['no_kas'], 'kd_skpd' => $kd_skpd])
                ->whereRaw("LEFT(kd_rek6,1)=?", ['4'])
                ->update([
                    'kd_rek6' => $data['jenis'],
                    'rupiah' => $data['nilai'],
                    'sumber' => $data['pengirim'],
                ]);

            DB::table('trhkasin_ppkd')
                ->where(['no_sts' => $data['no_kas'], 'kd_skpd' => $kd_skpd, 'jns_trans' => '4'])
                ->update([
                    'tgl_sts' => $data['tgl_kas'],
                    'tgl_kas' => $data['tgl_kas'],
                    'keterangan' => $data['keterangan'],
                    'total' => $data['nilai'],
                    'sumber' => $data['pengirim'],
                ]);

            DB::table('trdkasin_ppkd')
                ->where(['no_sts' => $data['no_kas'], 'kd_skpd' => $kd_skpd])
                ->whereRaw("LEFT(kd_rek6,1)=?", ['4'])
                ->update([
                    'kd_rek6' => $data['jenis'],
                    'rupiah' => $data['nilai'],
                    'sumber' => $data['pengirim'],
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

    public function hapusPenerimaanPpkd(Request $request)
    {
        $no_kas = $request->no_kas;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhkasin_pkd')
                ->where(['no_sts' => $no_kas, 'kd_skpd' => $kd_skpd, 'jns_trans' => '4'])
                ->delete();

            DB::table('trdkasin_pkd')
                ->where(['no_sts' => $no_kas, 'kd_skpd' => $kd_skpd])
                ->whereRaw("LEFT(kd_rek6,1)=?", ['4'])
                ->delete();

            DB::table('trhkasin_ppkd')
                ->where(['no_sts' => $no_kas, 'kd_skpd' => $kd_skpd, 'jns_trans' => '4'])
                ->delete();

            DB::table('trdkasin_ppkd')
                ->where(['no_sts' => $no_kas, 'kd_skpd' => $kd_skpd])
                ->whereRaw("LEFT(kd_rek6,1)=?", ['4'])
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

    // Penerimaan Lain PPKD
    public function indexPenerimaanKas()
    {
        return view('skpd.penerimaan_kas.index');
    }

    public function loadDataPenerimaanKas()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhkasin_ppkd as a')
            ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd")
            ->orderBy('a.tgl_kas')
            ->orderBy('a.no_kas')
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("penerimaan_kas.edit", Crypt::encrypt($row->no_kas)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_kas . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahPenerimaanKas()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'daftar_skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd', 'jns')
                ->orderBy('kd_skpd')
                ->get(),
            'daftar_jenis' => DB::table('trdrka_pend as a')
                ->select('kd_rek6', 'nm_rek6')
                ->whereRaw("left(kd_rek6,1)=? and kd_skpd=?", ['4', '5.02.0.00.0.00.02.0000'])
                ->orderBy('kd_rek6')
                ->distinct()
                ->get(),
            'daftar_pengirim' => DB::table('ms_pengirim as a')
                ->where(['kd_skpd' => $kd_skpd])
                ->orderByRaw("cast(kd_pengirim as int)")
                ->get()
        ];

        return view('skpd.penerimaan_kas.create')->with($data);
    }

    public function noBuktiPenerimaanKas(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $tgl_kas = $request->tgl_kas;

        if ($kd_skpd == '1.02.0.00.0.00.01.0000') {
            $data1 = DB::table('trhkasin_pkd')
                ->selectRaw("no_sts, tgl_sts,kd_skpd, keterangan,sumber,kd_sub_kegiatan,jns_trans,jns_cp,total")
                ->whereRaw("no_sts+jns_trans NOT IN(SELECT a.no_sts+jns_trans FROM trhkasin_ppkd a where kd_skpd=? and a.jns_trans=4 )
                and kd_skpd=?
				and tgl_sts=?
				and jns_trans=4", [$kd_skpd, $kd_skpd, $tgl_kas]);

            $data2 = DB::table('trhkasin_pkd')
                ->selectRaw("no_sts, tgl_sts,kd_skpd, keterangan,sumber,kd_sub_kegiatan,jns_trans,jns_cp,total")
                ->whereRaw("no_sts+jns_trans NOT IN(SELECT a.no_sts+jns_trans FROM trhkasin_ppkd a where kd_skpd=? and a.jns_trans NOT IN (4,3))
                and kd_skpd=?
				and tgl_sts=?
				and jns_trans NOT IN (4,3)", [$kd_skpd, $kd_skpd, $tgl_kas])
                ->unionAll($data1);

            $data3 = DB::table('TRHOUTLAIN')
                ->selectRaw("NO_BUKTI no_sts, TGL_BUKTI tgl_sts, KD_SKPD, KET keterangan, (CASE WHEN thnlalu='1' THEN 'y' ELSE 'n' END) sumber,
				'' kd_sub_kegiatan, '' jns_trans,'' jns_cp ,nilai as total")
                ->whereRaw("KD_SKPD=? AND TGL_BUKTI=? AND jns_beban<>7 AND NO_BUKTI NOT IN (select no_sts from trhkasin_ppkd where  sumber='y')", [$kd_skpd, $tgl_kas])
                ->unionAll($data2);
        } else {
            $data1 = DB::table('trhkasin_pkd')
                ->selectRaw("no_sts, tgl_sts,kd_skpd, keterangan,sumber,kd_sub_kegiatan,jns_trans,jns_cp,total")
                ->whereRaw("no_sts+kd_skpd+jns_trans NOT IN(SELECT a.no_sts+kd_skpd+jns_trans FROM trhkasin_ppkd a where kd_skpd=? and a.jns_trans=4 ) and kd_skpd=? and tgl_sts=? and jns_trans=4", [$kd_skpd, $kd_skpd, $tgl_kas]);

            $data2 = DB::table('trhkasin_pkd')
                ->selectRaw("no_sts, tgl_sts,kd_skpd, keterangan,sumber,kd_sub_kegiatan,jns_trans,jns_cp,total")
                ->whereRaw("no_sts+kd_skpd+jns_trans NOT IN(SELECT a.no_sts+kd_skpd+jns_trans FROM trhkasin_ppkd a where kd_skpd=? and a.jns_trans NOT IN (4,3)) and kd_skpd=? and tgl_sts=? and jns_trans NOT IN (4,3)", [$kd_skpd, $kd_skpd, $tgl_kas])
                ->unionAll($data1);

            $data3 = DB::table('TRHOUTLAIN')
                ->selectRaw("NO_BUKTI no_sts, TGL_BUKTI tgl_sts, KD_SKPD, KET keterangan, (CASE WHEN thnlalu='1' THEN 'y' ELSE 'n' END) sumber,
				'' kd_sub_kegiatan, '' jns_trans,'' jns_cp ,nilai as total")
                ->whereRaw("KD_SKPD=? AND TGL_BUKTI=? AND jns_beban<>7 AND NO_BUKTI NOT IN (select no_sts from trhkasin_ppkd where  sumber='y')", [$kd_skpd, $tgl_kas])
                ->unionAll($data2);
        }
        $data = DB::table(DB::raw("({$data3->toSql()}) AS sub"))
            ->mergeBindings($data3)
            ->get();

        return response()->json($data);
    }

    public function detailPenerimaanKas(Request $request)
    {
        $no_bukti = $request->no_bukti;
        $kd_skpd = $request->kd_skpd;
        $jenis = $request->jenis;

        $data1 = DB::table('trdkasin_pkd as a')
            ->join('trhkasin_pkd as b', function ($join) {
                $join->on('a.no_sts', '=', 'b.no_sts');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
            })
            ->leftJoin('ms_pengirim as c', function ($join) {
                $join->on('a.sumber', '=', 'c.sumber');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->leftJoin('ms_rek5 as d', function ($join) {
                $join->on(DB::raw("LEFT(a.kd_rek6,8)"), '=', 'd.kd_rek5');
            })
            ->selectRaw("a.*, (select nm_rek6 from ms_rek6 where kd_rek6 = a.kd_rek6) as nm_rek, c.nm_pengirim, d.nm_rek5")
            ->where(['a.no_sts' => $no_bukti, 'a.kd_skpd' => $kd_skpd, 'b.jns_trans' => $jenis]);

        $data2 = DB::table('TRHOUTLAIN')
            ->selectRaw("KD_SKPD, NO_BUKTI no_sts, '' kd_rek6, nilai as rupiah, '' kd_sub_kegiatan, '' no_terima,  (CASE WHEN thnlalu='1' THEN 'y' ELSE 'n' END) sumber,''kanal,
		'' nm_rek, '' nm_pengirim, '' nm_rek5");

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function simpanPenerimaanKas(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $no_urut = DB::table('trhkasin_pkd')
                ->selectRaw("count(no_sts)+1 as nomor")
                ->where(['kd_skpd' => $kd_skpd, 'jns_trans' => '4'])
                ->first();
            $nomor = $no_urut->nomor;
            $cek_terima = DB::table('trhkasin_pkd')->where(['no_sts' => $nomor . '/BP', 'kd_skpd' => '5.02.0.00.0.00.02.0000'])->count();
            if ($cek_terima > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            $no_kas = nomor_tukd();

            DB::table('trhkasin_pkd')->insert([
                'no_sts' => $nomor . '/BP',
                'tgl_sts' => $data['tgl_kas'],
                'kd_skpd' => $kd_skpd,
                'keterangan' => $data['keterangan'],
                'total' => $data['nilai'],
                'kd_sub_kegiatan' => '5.02.00.0.00.04',
                'jns_trans' => '4',
                'no_kas' => $no_kas,
                'tgl_kas' => $data['tgl_kas'],
                'sumber' => $data['pengirim'],
                'user_name' => Auth::user()->nama,
                'no_cek' => '1',
                'status' => '1',
            ]);

            DB::table('trdkasin_pkd')->insert([
                'no_sts' => $nomor . '/BP',
                'kd_skpd' => $kd_skpd,
                'kd_rek6' => $data['jenis'],
                'rupiah' => $data['nilai'],
                'kd_sub_kegiatan' => '5.02.00.0.00.04',
                'sumber' => $data['pengirim'],
            ]);

            DB::table('trhkasin_ppkd')->insert([
                'no_kas' => $no_kas,
                'no_sts' => $nomor . '/BP',
                'kd_skpd' => $kd_skpd,
                'tgl_sts' => $data['tgl_kas'],
                'tgl_kas' => $data['tgl_kas'],
                'keterangan' => $data['keterangan'],
                'total' => $data['nilai'],
                'kd_bank' => '1',
                'kd_sub_kegiatan' => '5.02.00.0.00.04',
                'jns_trans' => '4',
                'rek_bank' => '',
                'sumber' => $data['pengirim'],
                'pot_khusus' => '0',
                'no_sp2d' => '',
                'jns_cp' => '',
            ]);

            DB::table('trdkasin_ppkd')->insert([
                'no_kas' => $no_kas,
                'kd_skpd' => $kd_skpd,
                'no_sts' => $nomor . '/BP',
                'kd_rek6' => $data['jenis'],
                'rupiah' => $data['nilai'],
                'kd_sub_kegiatan' => '5.02.00.0.00.04',
                'sumber' => $data['pengirim'],
            ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'nomor' => $nomor . '/BP'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
            ]);
        }
    }

    public function editPenerimaanKas($no_sts)
    {
        $no_sts = Crypt::decrypt($no_sts);
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'terima' => $data = DB::table('trhkasin_pkd as a')
                ->join('trdkasin_pkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("a.*,b.kd_rek6")
                ->where(['a.kd_skpd' => $kd_skpd, 'a.jns_trans' => '4', 'a.no_sts' => $no_sts])
                ->first(),
            'daftar_jenis' => DB::table('trdrka_pend as a')
                ->select('kd_rek6', 'nm_rek6')
                ->whereRaw("left(kd_rek6,1)=? and kd_skpd=?", ['4', '5.02.0.00.0.00.02.0000'])
                ->orderBy('kd_rek6')
                ->distinct()
                ->get(),
            'daftar_pengirim' => DB::table('ms_pengirim as a')
                ->where(['kd_skpd' => $kd_skpd])
                ->orderByRaw("cast(kd_pengirim as int)")
                ->get()
        ];
        // dd($data['terima']);
        return view('skpd.penerimaan_lain_ppkd.edit')->with($data);
    }

    public function simpanEditPenerimaanKas(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhkasin_pkd')
                ->where(['no_sts' => $data['no_kas'], 'kd_skpd' => $kd_skpd, 'jns_trans' => '4'])
                ->update([
                    'tgl_sts' => $data['tgl_kas'],
                    'keterangan' => $data['keterangan'],
                    'total' => $data['nilai'],
                    'tgl_kas' => $data['tgl_kas'],
                    'sumber' => $data['pengirim'],
                ]);

            DB::table('trdkasin_pkd')
                ->where(['no_sts' => $data['no_kas'], 'kd_skpd' => $kd_skpd])
                ->whereRaw("LEFT(kd_rek6,1)=?", ['4'])
                ->update([
                    'kd_rek6' => $data['jenis'],
                    'rupiah' => $data['nilai'],
                    'sumber' => $data['pengirim'],
                ]);

            DB::table('trhkasin_ppkd')
                ->where(['no_sts' => $data['no_kas'], 'kd_skpd' => $kd_skpd, 'jns_trans' => '4'])
                ->update([
                    'tgl_sts' => $data['tgl_kas'],
                    'tgl_kas' => $data['tgl_kas'],
                    'keterangan' => $data['keterangan'],
                    'total' => $data['nilai'],
                    'sumber' => $data['pengirim'],
                ]);

            DB::table('trdkasin_ppkd')
                ->where(['no_sts' => $data['no_kas'], 'kd_skpd' => $kd_skpd])
                ->whereRaw("LEFT(kd_rek6,1)=?", ['4'])
                ->update([
                    'kd_rek6' => $data['jenis'],
                    'rupiah' => $data['nilai'],
                    'sumber' => $data['pengirim'],
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

    public function hapusPenerimaanKas(Request $request)
    {
        $no_kas = $request->no_kas;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhkasin_pkd')
                ->where(['no_sts' => $no_kas, 'kd_skpd' => $kd_skpd, 'jns_trans' => '4'])
                ->delete();

            DB::table('trdkasin_pkd')
                ->where(['no_sts' => $no_kas, 'kd_skpd' => $kd_skpd])
                ->whereRaw("LEFT(kd_rek6,1)=?", ['4'])
                ->delete();

            DB::table('trhkasin_ppkd')
                ->where(['no_sts' => $no_kas, 'kd_skpd' => $kd_skpd, 'jns_trans' => '4'])
                ->delete();

            DB::table('trdkasin_ppkd')
                ->where(['no_sts' => $no_kas, 'kd_skpd' => $kd_skpd])
                ->whereRaw("LEFT(kd_rek6,1)=?", ['4'])
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
