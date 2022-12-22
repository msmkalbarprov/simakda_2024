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
            ->where(['a.jns_trans' => '2'])
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
                ->selectRaw("max(tgl_kunci) as tgl_kasda,''tgl_spj,? as tgl2", [$data['tgl_sts']])
                ->where(['kd_skpd' => $kd_skpd]);

            $cek2 = DB::table($cek1, 'a')
                ->selectRaw("CASE WHEN tgl2<=tgl_kasda THEN '1' ELSE '0' END as status_kasda,0 status_spj,*");

            $cek3 = DB::table('trhspj_terima_ppkd')
                ->selectRaw("''tgl_kasda,max(tgl_terima) as tgl_spj,? as tgl2", [$data['tgl_sts']])
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
                DB::table('trhkasin_ppkd')
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

                DB::table('trhkasin_pkd')
                    ->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_sts']])
                    ->update([
                        'no_cek' => '1',
                        'status' => '1'
                    ]);
            }

            DB::table('trdkasin_pkd')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_sts']])
                ->delete();

            if (isset($data['detail_sts'])) {
                DB::table('trdkasin_pkd')
                    ->insert(array_map(function ($value) use ($data) {
                        return [
                            'no_sts' => $data['no_sts'],
                            'kd_skpd' => $data['kd_skpd'],
                            'kd_rek6' => $value['kd_rek6'],
                            'rupiah' => $value['nilai'],
                            'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                            'no_terima' => '',
                            'sumber' => $data['pengirim'],
                        ];
                    }, $data['detail_sts']));
            }

            DB::table('tr_terima as a')
                ->join('trdkasin_pkd as b', function ($join) {
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                    $join->on('a.no_terima', '=', 'b.no_terima');
                    $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
                })
                ->where(['a.kd_skpd' => $data['kd_skpd'], 'b.no_sts' => $data['no_sts']])
                ->update([
                    'a.kunci' => '1'
                ]);

            if ($jumlah == 0 && $data['kd_skpd'] <> '1.02.0.00.0.00.02.0000') {
                if (isset($data['detail_sts'])) {
                    DB::table('trdkasin_ppkd')
                        ->insert(array_map(function ($value) use ($data) {
                            return [
                                'no_sts' => $data['no_sts'],
                                'kd_skpd' => $data['kd_skpd'],
                                'kd_rek6' => $value['kd_rek6'],
                                'rupiah' => $value['nilai'],
                                'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                                'no_kas' => '',
                                'sumber' => $data['pengirim'],
                            ];
                        }, $data['detail_sts']));
                }

                DB::table('trdkasin_ppkd as a')
                    ->join('trhkasin_ppkd as b', function ($join) {
                        $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                        $join->on('a.no_sts', '=', 'b.no_sts');
                        $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
                    })
                    ->where(['a.kd_skpd' => $data['kd_skpd'], 'b.no_sts' => $data['no_sts']])
                    ->update([
                        'a.no_kas' => $nomor
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

    public function editPenyetoranLalu($no_sts)
    {
        $no_sts = Crypt::decrypt($no_sts);
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
                ->get(),
            'setor' => DB::table('trhkasin_pkd as a')
                ->join('trdkasin_pkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                    $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
                })
                ->select('a.*')
                ->where(['a.kd_skpd' => $kd_skpd, 'b.no_sts' => $no_sts])
                ->first(),
            'detail_setor' => DB::table('trhkasin_pkd as a')
                ->join('trdkasin_pkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                    $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
                })
                ->select('b.*')
                ->where(['a.kd_skpd' => $kd_skpd, 'b.no_sts' => $no_sts])
                ->get()
        ];
        // dd($data['setor']);
        return view('penatausahaan.penyetoran_tahun_lalu.edit')->with($data);
    }

    public function simpanEditPenyetoranLalu(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $cek1 = DB::table('tr_kunci')
                ->selectRaw("max(tgl_kunci) as tgl_kasda,''tgl_spj,? as tgl2", [$data['tgl_sts']])
                ->where(['kd_skpd' => $kd_skpd]);

            $cek2 = DB::table($cek1, 'a')
                ->selectRaw("CASE WHEN tgl2<=tgl_kasda THEN '1' ELSE '0' END as status_kasda,0 status_spj,*");

            $cek3 = DB::table('trhspj_terima_ppkd')
                ->selectRaw("''tgl_kasda,max(tgl_terima) as tgl_spj,? as tgl2", [$data['tgl_sts']])
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
                if ($cek_terima > 0 && $data['no_sts'] != $data['no_simpan']) {
                    return response()->json([
                        'message' => '4'
                    ]);
                }
            }


            DB::table('trhkasin_pkd')->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_simpan']])->delete();

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

            DB::table('trdkasin_pkd')->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_simpan']])->delete();

            if (isset($data['detail_sts'])) {
                DB::table('trdkasin_pkd')
                    ->insert(array_map(function ($value) use ($data) {
                        return [
                            'no_sts' => $data['no_sts'],
                            'kd_skpd' => $data['kd_skpd'],
                            'kd_rek6' => $value['kd_rek6'],
                            'rupiah' => $value['nilai'],
                            'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                            'sumber' => $data['pengirim'],
                        ];
                    }, $data['detail_sts']));
            }

            $jumlah = DB::table('ms_skpd')->where(['jns' => '2', 'kd_skpd' => $data['kd_skpd']])->count();


            DB::table('trhkasin_ppkd')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_simpan']])->delete();

            DB::table('trdkasin_ppkd')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_simpan']])->delete();

            $nomor = nomor_tukd();

            if ($jumlah == 0 && $data['kd_skpd'] <> '1.02.0.00.0.00.02.0000') {
                DB::table('trhkasin_ppkd')
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

                DB::table('trhkasin_pkd')
                    ->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_sts']])
                    ->update([
                        'no_cek' => '1',
                        'status' => '1'
                    ]);

                if (isset($data['detail_sts'])) {
                    DB::table('trdkasin_ppkd')
                        ->insert(array_map(function ($value) use ($data) {
                            return [
                                'no_sts' => $data['no_sts'],
                                'kd_skpd' => $data['kd_skpd'],
                                'kd_rek6' => $value['kd_rek6'],
                                'rupiah' => $value['nilai'],
                                'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                                'no_kas' => '',
                                'sumber' => $data['pengirim'],
                            ];
                        }, $data['detail_sts']));
                }

                DB::table('trdkasin_ppkd as a')
                    ->join('trhkasin_ppkd as b', function ($join) {
                        $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                        $join->on('a.no_sts', '=', 'b.no_sts');
                        $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
                    })
                    ->where(['a.kd_skpd' => $data['kd_skpd'], 'b.no_sts' => $data['no_sts']])
                    ->update([
                        'a.no_kas' => $nomor
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

    public function hapusPenyetoranLalu(Request $request)
    {
        $no_sts = $request->no_sts;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('tr_terima as a')
                ->join('trdkasin_pkd as b', function ($join) {
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                    $join->on('a.no_terima', '=', 'b.no_terima');
                    $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
                })
                ->where(['a.kd_skpd' => $kd_skpd, 'b.no_sts' => $no_sts])
                ->update([
                    'a.kunci' => '0'
                ]);

            DB::table('trhkasin_pkd')
                ->where(['no_sts' => $no_sts, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trdkasin_pkd')
                ->where(['no_sts' => $no_sts, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trhkasin_ppkd')
                ->where(['no_sts' => $no_sts, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trdkasin_ppkd')
                ->where(['no_sts' => $no_sts, 'kd_skpd' => $kd_skpd])
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

    public function cekPenyetoranLalu(Request $request)
    {
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [];
        return view('penatausahaan.pengeluaran.spm.cetak.ringkasan_gu')->with($data);
    }

    public function validasiPenyetoranLalu(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;

        DB::beginTransaction();
        try {
            $cek = DB::table('ms_skpd')
                ->where(['jns' => '2', 'kd_skpd' => $kd_skpd])
                ->count();

            if ($cek > 0) {
                $nomor = nomor_tukd();

                DB::update("UPDATE trhkasin_pkd set status='1' where kd_skpd=? and (tgl_sts BETWEEN ? and ?)", [$kd_skpd, $tgl_awal, $tgl_akhir]);

                DB::delete("DELETE b from trhkasin_ppkd a inner join trdkasin_ppkd b on a.kd_skpd=b.kd_skpd and a.no_kas=b.no_kas
					 where a.jns_trans IN ('4','2') and a.kd_bank<>'1' and b.kd_skpd=? and (a.tgl_sts BETWEEN ? and ?)
					 AND b.no_sts+b.kd_skpd NOT IN (select b.no_sts+b.kd_skpd from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
					 where a.jns_trans IN ('4','2') and a.status='1' and b.kd_skpd=? and (a.tgl_sts BETWEEN ? and ?))", [$kd_skpd, $tgl_awal, $tgl_akhir, $kd_skpd, $tgl_awal, $tgl_akhir]);

                DB::delete("DELETE from trhkasin_ppkd
					 where jns_trans IN ('4','2') and kd_bank<>'1' and kd_skpd=? and (tgl_sts BETWEEN ? and ?)
					 AND no_sts+kd_skpd NOT IN (select no_sts+kd_skpd from trhkasin_pkd
					 where jns_trans IN ('4','2') and status='1' and kd_skpd=? and (tgl_sts BETWEEN ? and ?))", [$kd_skpd, $tgl_awal, $tgl_akhir, $kd_skpd, $tgl_awal, $tgl_akhir]);

                DB::insert("INSERT into trdkasin_ppkd
						SELECT kd_skpd, no_sts, kd_rek6, rupiah, kd_sub_kegiatan, no_kas, sumber,kanal FROM (
						select b.kd_skpd, b.no_sts, b.kd_rek6, b.rupiah, b.kd_sub_kegiatan,'' no_kas, b.sumber,kanal
						from trdkasin_pkd b inner join trhkasin_pkd a on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						where a.jns_trans IN ('4','2') AND (a.tgl_sts BETWEEN ? and ?)
						AND LEFT(b.kd_skpd,17) IN ('5.02.0.00.0.00.01')
						AND b.kd_skpd NOT IN ('5.02.0.00.0.00.01.0000','5.02.0.00.0.00.02.0000')
						AND a.status=1) x
						WHERE kd_skpd = ? AND no_sts+kd_skpd not in (select b.no_sts+b.kd_skpd from trhkasin_ppkd b where (tgl_sts BETWEEN ? and ?) AND b.kd_skpd=? AND jns_trans IN ('4','2'))", [$tgl_awal, $tgl_akhir, $kd_skpd, $tgl_awal, $tgl_akhir, $kd_skpd]);

                DB::insert("INSERT into trhkasin_ppkd(no_kas,tgl_kas,no_sts,kd_skpd,tgl_sts,keterangan,total,kd_sub_kegiatan,jns_trans,sumber,kd_skpd_sumber)
						select $nomor+ROW_NUMBER() OVER (ORDER BY tgl_kas) AS no_kas, tgl_sts, no_sts, kd_skpd, tgl_sts, keterangan, total, kd_sub_kegiatan, jns_trans, sumber,kd_skpd
						FROM (
						SELECT a.*,(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) nm_skpd
										FROM trhkasin_pkd a
										WHERE jns_trans IN ('4','2') AND (a.tgl_sts BETWEEN ? and ?)
											  AND LEFT(kd_skpd,17) IN ('5.02.0.00.0.00.01')
											  AND kd_skpd NOT IN ('5.02.0.00.0.00.01.0000','5.02.0.00.0.00.02.0000')
											  AND status=1
											  AND no_sts+kd_skpd not in (select no_sts+kd_skpd from trhkasin_ppkd WHERE jns_trans IN ('4','2') AND (a.tgl_sts BETWEEN ? and ?) AND LEFT(kd_skpd,17) IN ('5.02.0.00.0.00.01')
											  AND kd_skpd NOT IN ('5.02.0.00.0.00.01.0000','5.02.0.00.0.00.02.0000')))x WHERE kd_skpd = ?", [$tgl_awal, $tgl_akhir, $tgl_awal, $tgl_akhir, $kd_skpd]);

                DB::update("UPDATE b set b.no_kas=a.no_kas
								from trdkasin_ppkd b inner join trhkasin_ppkd a
								on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
								where LEFT(b.kd_rek6,1)=4 AND b.kd_skpd = ? AND (a.tgl_sts BETWEEN ? and ?) AND a.jns_trans in ('4','2')", [$kd_skpd, $tgl_awal, $tgl_akhir]);

                DB::update("UPDATE a set a.no_cek=1
								from trhkasin_ppkd b inner join trhkasin_pkd a
								on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
								where a.jns_trans in ('4','2') AND a.kd_skpd = ? AND (a.tgl_sts BETWEEN ? and ?)", [$kd_skpd, $tgl_awal, $tgl_akhir]);

                DB::commit();
                return response()->json([
                    'message' => '1'
                ]);
            } else {
                DB::rollBack();
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
}
