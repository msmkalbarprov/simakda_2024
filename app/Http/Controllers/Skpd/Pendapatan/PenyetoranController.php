<?php

namespace App\Http\Controllers\Skpd\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PenyetoranController extends Controller
{
    // Penyetoran Atas Penerimaan Tahun Lalu
    public function indexPenyetoranLalu()
    {
        return view('penatausahaan.penyetoran_tahun_lalu.index');
    }

    public function loadDataPenyetoranLalu()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhkasin_pkd as a')
            ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd")
            ->where(['a.kd_skpd' => $kd_skpd, 'a.jns_trans' => '2'])
            ->orderBy('a.tgl_sts')
            ->orderBy('a.no_sts')
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("penyetoran_lalu.edit", Crypt::encrypt($row->no_sts)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_sts . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahPenyetoranLalu()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_pengirim' => DB::table('ms_pengirim as a')
                ->whereRaw("LEFT(kd_skpd,5)=LEFT(?,5)", [$kd_skpd])
                ->orderByRaw("cast(kd_pengirim as int)")
                ->get(),
            'daftar_kegiatan' => DB::table('trskpd_pend as a')
                ->selectRaw("a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program,a.nm_program,a.total")
                ->where(['kd_skpd' => $kd_skpd, 'a.jns_sub_kegiatan' => '4'])
                ->get()
        ];

        return view('penatausahaan.penyetoran_tahun_lalu.create')->with($data);
    }

    public function rekeningPenyetoranLalu(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;

        $data = DB::table('trdrka as a')
            ->selectRaw("a.kd_rek6,(SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6=a.kd_rek6) AS nm_rek6")
            ->where(['a.kd_skpd' => $kd_skpd, 'a.kd_sub_kegiatan' => $kd_sub_kegiatan])
            ->distinct()
            ->get();

        return response()->json($data);
    }

    public function simpanPenyetoranLalu(Request $request)
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
                $cek_terima = DB::table('trhkasin_pkd')->where(['no_sts' => $data['no_sts'], 'kd_skpd' => $kd_skpd])->count();
                if ($cek_terima > 0) {
                    return response()->json([
                        'message' => '4'
                    ]);
                }
            }
            $nomor = nomor_tukd();

            DB::table('trhkasin_pkd')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_sts']])
                ->delete();

            DB::table('trhkasin_pkd')
                ->insert([
                    'no_sts' => $data['no_sts'],
                    'tgl_sts' => $data['tgl_sts'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keterangan' => $data['keterangan'],
                    'total' => $data['total'],
                    'kd_bank' => '',
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    'jns_trans' => '2',
                    'rek_bank' => '',
                    'sumber' => $data['pengirim'],
                    'pot_khusus' => '0',
                    'no_sp2d' => '',
                    'jns_cp' => '',
                ]);

            $jumlah = DB::table('ms_skpd')->where(['jns' => '2', 'kd_skpd' => $data['kd_skpd']])->count();
            if ($jumlah == 0 && $data['kd_skpd'] <> '1.02.0.00.0.00.02.0000') {
                DB::table('trhkasin_pkd')
                    ->insert([
                        'no_kas' => $nomor,
                        'tgl_kas' => $data['tgl_sts'],
                        'no_sts' => $data['no_sts'],
                        'tgl_sts' => $data['tgl_sts'],
                        'kd_skpd' => $data['kd_skpd'],
                        'keterangan' => $data['keterangan'],
                        'total' => $data['total'],
                        'kd_bank' => '',
                        'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                        'jns_trans' => '2',
                        'rek_bank' => '',
                        'sumber' => $data['pengirim'],
                        'pot_khusus' => '0',
                        'no_sp2d' => '',
                        'jns_cp' => '',
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

    public function editPenyetoranLalu($no_terima)
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

    public function simpanEditPenyetoranLalu(Request $request)
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

    public function hapusPenyetoranLalu(Request $request)
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
}
