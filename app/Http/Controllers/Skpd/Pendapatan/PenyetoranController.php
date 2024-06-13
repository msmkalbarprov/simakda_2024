<?php

namespace App\Http\Controllers\Skpd\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\isNull;

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
            ->where(['a.jns_trans' => '2', 'a.kd_skpd' => $kd_skpd])
            ->orderBy('a.tgl_sts')
            ->orderBy('a.no_sts')
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("penyetoran_lalu.edit", Crypt::encrypt($row->no_sts)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            if ($row->no_cek != 1 || $row->no_cek == null || $row->no_cek == '' || $row->no_cek == 0) {
                $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_sts . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            }

            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahPenyetoranLalu()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $status_anggaran = status_anggaran();

        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_pengirim' => DB::table('ms_pengirim as a')
                // ->whereRaw("LEFT(kd_skpd,5)=LEFT(?,5)", [$kd_skpd])
                ->where(['kd_skpd' => $kd_skpd])
                // ->orderByRaw("cast(kd_pengirim as int)")
                ->orderByRaw("kd_pengirim")
                ->get(),
            'daftar_kegiatan' => DB::table('trskpd as a')
                ->join('ms_sub_kegiatan as b', 'a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan')
                ->selectRaw("a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program,a.nm_program,a.total")
                ->where(['kd_skpd' => $kd_skpd, 'jns_ang' => $status_anggaran, 'jns_sub_kegiatan' => '4'])
                ->whereRaw("right(a.kd_sub_kegiatan,7)=?", ['00.0004'])
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

            $tanggal = $data['tgl_sts'];

            $cek = collect(DB::select("SELECT sum(status_kasda) status_kasda, sum(status_spj) status_spj from (
            SELECT CASE
                        WHEN tgl2<=tgl_kasda THEN '1'
                            ELSE '0'
                        END as status_kasda,0 status_spj,*
                        from (

                            SELECT max(tgl_kunci) as tgl_kasda,''tgl_spj,'$tanggal'as tgl2 FROM [dbo].[tr_kunci] where kd_skpd=?
                        )zz

            UNION ALL
            SELECT 0 status_kasda,CASE
                        WHEN tgl2<=tgl_spj THEN '1'
                            ELSE '0'
                        END as status_spj,*
                        from (

                            SELECT ''tgl_kasda,max(bulan) as tgl_spj,MONTH('$tanggal')as tgl2 FROM [dbo].[trhspj_terima_ppkd]
                                    where kd_skpd=? and cek = '1'
                        )zz
            )xx", [$kd_skpd, $kd_skpd]))->first();

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
                    'jns_pembayaran' => $data['jenis_pembayaran'],
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
        $status_anggaran = status_anggaran();

        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_pengirim' => DB::table('ms_pengirim as a')
                // ->whereRaw("LEFT(kd_skpd,5)=LEFT(?,5)", [$kd_skpd])
                ->where(['kd_skpd' => $kd_skpd])
                // ->orderByRaw("cast(kd_pengirim as int)")
                ->orderByRaw("kd_pengirim")
                ->get(),
            'daftar_kegiatan' => DB::table('trskpd as a')
                ->join('ms_sub_kegiatan as b', 'a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan')
                ->selectRaw("a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program,a.nm_program,a.total")
                ->where(['kd_skpd' => $kd_skpd, 'jns_ang' => $status_anggaran, 'jns_sub_kegiatan' => '4'])
                ->whereRaw("right(a.kd_sub_kegiatan,7)=?", ['00.0004'])
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

        return view('penatausahaan.penyetoran_tahun_lalu.edit')->with($data);
    }

    public function simpanEditPenyetoranLalu(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $tanggal = $data['tgl_sts'];

            $cek = collect(DB::select("SELECT sum(status_kasda) status_kasda, sum(status_spj) status_spj from (
            SELECT CASE
                        WHEN tgl2<=tgl_kasda THEN '1'
                            ELSE '0'
                        END as status_kasda,0 status_spj,*
                        from (

                            SELECT max(tgl_kunci) as tgl_kasda,''tgl_spj,'$tanggal'as tgl2 FROM [dbo].[tr_kunci] where kd_skpd=?
                        )zz

            UNION ALL
            SELECT 0 status_kasda,CASE
                        WHEN tgl2<=tgl_spj THEN '1'
                            ELSE '0'
                        END as status_spj,*
                        from (

                            SELECT ''tgl_kasda,max(bulan) as tgl_spj,MONTH('$tanggal')as tgl2 FROM [dbo].[trhspj_terima_ppkd]
                                    where kd_skpd=? and cek = '1'
                        )zz
            )xx", [$kd_skpd, $kd_skpd]))->first();

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
                    'jns_pembayaran' => $data['jenis_pembayaran'],
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

                $datarkud = DB::table('ms_rek_kasda')
                    ->first();

                $rkud = $datarkud->rek_bank;

                DB::insert("INSERT into trhkasin_ppkd(no_kas,tgl_kas,no_sts,kd_skpd,tgl_sts,keterangan,total,kd_sub_kegiatan,jns_trans,sumber,kd_skpd_sumber,rek_bank)
						select $nomor+ROW_NUMBER() OVER (ORDER BY tgl_kas) AS no_kas, tgl_sts, no_sts, kd_skpd, tgl_sts, keterangan, total, kd_sub_kegiatan, jns_trans, sumber,kd_skpd, '$rkud' as rek_bank
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

    // Penyetoran Atas Penerimaan Tahun Ini
    public function indexPenyetoranIni()
    {
        return view('penatausahaan.penyetoran_tahun_ini.index');
    }

    public function loadDataPenyetoranIniLama()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $spjbulan = cek_status_spj_pend($kd_skpd);

        // $data = DB::table('trhkasin_pkd as a')
        //     ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd,(CASE WHEN month(a.tgl_sts)<=? THEN 1 ELSE 0 END) ketspj,a.user_name", [$spjbulan])
        //     ->where(['a.kd_skpd' => $kd_skpd, 'a.jns_trans' => '4'])
        //     ->whereRaw("not exists (select * from trdkasin_pkd b where left(kd_rek6,12) =? and a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts) AND keterangan not like '%keterlambatan%'", ['410411010001'])
        //     ->orderBy('a.tgl_sts')
        //     ->orderBy('a.no_sts')
        //     ->get();

        $data = DB::select("SELECT
                a.*,
                ( SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd ) AS nm_skpd,
                ( CASE WHEN MONTH ( a.tgl_sts ) <= ? THEN 1 ELSE 0 END ) ketspj,
                a.user_name
                FROM trhkasin_pkd a
            WHERE
                a.kd_skpd= ?
                AND a.jns_trans= '4'
                AND a.keterangan NOT LIKE '%keterlambatan%'
                AND NOT EXISTS (
                SELECT
                    *
                FROM
                    trdkasin_pkd b
                WHERE
                    LEFT ( kd_rek6, 12 ) = '410411010001'
                    AND a.kd_skpd= b.kd_skpd
                    AND a.no_sts= b.no_sts
                )

            ORDER BY
                a.tgl_sts,
                a.no_sts", [$spjbulan, $kd_skpd]);

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {

            if ($row->no_cek != 1 || $row->no_cek == null || $row->no_cek == '' || $row->no_cek == 0) {
                $btn = '<a href="' . route("penyetoran_ini.edit", ['no_sts' => Crypt::encrypt($row->no_sts), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
                $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_sts . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            } else {
                $btn = '<a href="' . route("penyetoran_ini.edit", ['no_sts' => Crypt::encrypt($row->no_sts), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-primary btn-sm"  style="margin-right:4px"><i class="uil-eye"></i></a>';
            }

            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function loadDataPenyetoranIni(Request $request)
    {   
        $kd_skpd = Auth::user()->kd_skpd;
        $spjbulan = cek_status_spj_pend($kd_skpd);
        $query = DB::table('trhkasin_pkd as a')
            ->selectRaw('a.*,
                ( SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd ) AS nm_skpd,
                ( CASE WHEN MONTH ( a.tgl_sts ) <= '.$spjbulan.' THEN 1 ELSE 0 END ) ketspj,
                a.user_name')
            ->where(['a.kd_skpd' => $kd_skpd, 'a.jns_trans' => 4])
            ->whereRaw("a.keterangan NOT LIKE '%keterlambatan%'")
            ->whereNotExists(function($query) {
                $query->from('trdkasin_pkd AS b')
                    ->whereRaw("LEFT ( kd_rek6, 12 ) = '410411010001'")
                      ->whereColumn('b.kd_skpd', 'a.kd_skpd')
                      ->whereColumn('b.no_sts', 'a.no_sts');
               });
        // $query = DB::select("SELECT
        //         a.*,
        //         ( SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd ) AS nm_skpd,
        //         ( CASE WHEN MONTH ( a.tgl_sts ) <= ? THEN 1 ELSE 0 END ) ketspj,
        //         a.user_name
        //         FROM trhkasin_pkd a
        //     WHERE
        //         a.kd_skpd= ?
        //         AND a.jns_trans= '4'
        //         AND a.keterangan NOT LIKE '%keterlambatan%'
        //         AND NOT EXISTS (
        //         SELECT
        //             *
        //         FROM
        //             trdkasin_pkd b
        //         WHERE
        //             LEFT ( kd_rek6, 12 ) = '410411010001'
        //             AND a.kd_skpd= b.kd_skpd
        //             AND a.no_sts= b.no_sts
        //         )

        //     ORDER BY
        //         a.tgl_sts,
        //         a.no_sts", [$spjbulan, $kd_skpd]);

        $column_seacrh = ['a.no_sts', 'a.keterangan', 'a.kd_skpd'];
        $filtered           =   $query->where(function ($query) use ($column_seacrh, $request) {
            foreach ($column_seacrh as $eachElement) {
                $query->orWhere($eachElement, 'LIKE', '%' . $request->search['value'] . '%');
            }
        });

        if (!$request->search['value']) {
            $record_total   = $query->get()->count();
            $data = $query->skip($request->start)
                ->take($request->length)
                ->get();
        } else {
            $record_total   = $filtered->get()->count();
            $data           = $filtered->get();
        }
        
        return Datatables::of($data)
            ->with([
                "recordsTotal" => $record_total,
                "recordsFiltered" => $record_total,
            ])
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->setTransformer(function ($item) {
                if ($item->no_cek != 1 || $item->no_cek == null || $item->no_cek == '' || $item->no_cek == 0) {
                    $btn = '<a href="' . route("penyetoran_ini.edit", ['no_sts' => Crypt::encrypt($item->no_sts), 'kd_skpd' => Crypt::encrypt($item->kd_skpd)]) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $item->no_sts . '\',\'' . $item->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
                } else {
                    $btn = '<a href="' . route("penyetoran_ini.edit", ['no_sts' => Crypt::encrypt($item->no_sts), 'kd_skpd' => Crypt::encrypt($item->kd_skpd)]) . '" class="btn btn-primary btn-sm"  style="margin-right:4px"><i class="uil-eye"></i></a>';
                }
                return [
                    'no_sts'        => $item->no_sts,
                    'tgl_sts'       => $item->tgl_sts,
                    'kd_skpd'       => $item->kd_skpd,
                    'keterangan'    => $item->keterangan,
                    'ketspj'        => $item->ketspj,
                    'aksi'          => $btn,
                ];
            })

            ->skipPaging()
            ->make(true);
    }

    public function tambahPenyetoranIni()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $status_anggaran = status_anggaran();

        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_kegiatan' => DB::table('trskpd as a')
                ->join('ms_sub_kegiatan as b', 'a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan')
                ->selectRaw("a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program,a.nm_program,a.total")
                ->where(['kd_skpd' => $kd_skpd, 'jns_ang' => $status_anggaran, 'jns_sub_kegiatan' => '4'])
                ->whereRaw("right(a.kd_sub_kegiatan,7)=?", ['00.0004'])
                ->get(),
            'daftar_pengirim' => DB::table('ms_pengirim as a')
                // ->whereRaw("LEFT(kd_skpd,5)=LEFT(?,5)", [$kd_skpd])
                ->where('kd_skpd', $kd_skpd)
                ->orderByRaw("kd_pengirim")
                ->get(),
        ];

        return view('penatausahaan.penyetoran_tahun_ini.create')->with($data);
    }

    public function nomorPenyetoranIni(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $tgl_terima = $request->tgl_terima;
        $gerai = $request->gerai;
        $jenis_pembayaran = $request->jenis_pembayaran;

        $no_sts1 = $request->no_sts;

        $no_sts = array();
        if (!empty($no_sts1)) {
            foreach ($no_sts1 as $sts) {
                $no_sts[] = $sts['no_sts'];
            }
        } else {
            $no_sts[] = '';
        }

        // $data = DB::table('tr_terima as a')
        //     ->leftJoin('ms_pengirim as b', function ($join) {
        //         $join->on('a.sumber', '=', 'b.kd_pengirim');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->selectRaw("a.*,(SELECT nama from ms_kanal where kode=a.kanal) as nama,b.nm_pengirim,(SELECT nm_rek6 from ms_rek6 where kd_rek6=a.kd_rek6) as nm_rek6")
        //     ->whereRaw("a.kd_skpd=? AND a.no_terima + '.' + kanal NOT IN(select ISNULL(no_terima,'') + '.' + ISNULL(kanal,'') no_terima from trdkasin_pkd where kd_skpd=?) AND  a.tgl_terima=? and a.jns_pembayaran=?", [$kd_skpd, $kd_skpd, $tgl_terima, $jenis_pembayaran])
        //     ->where(function ($query) use ($gerai) {
        //         if ($gerai != 'all') {
        //             $query->where('a.sumber', $gerai);
        //         }
        //     })
        //     ->whereNotIn('a.no_terima', $no_sts)
        //     ->orderBy('b.nm_pengirim')
        //     ->orderBy('a.tgl_terima')
        //     ->orderBy('a.kd_rek6')
        //     ->get();
        // DB::enableQueryLog();
        $data = DB::table('tr_terima as a')
            ->selectRaw("a.no_terima,a.tgl_terima,a.kd_rek6,a.nilai,a.kanal,a.sumber,(SELECT nama from ms_kanal where kode=a.kanal) as nama,(select nm_pengirim from ms_pengirim where a.sumber=kd_pengirim and a.kd_skpd=kd_skpd) as nm_pengirim,(SELECT nm_rek6 from ms_rek6 where kd_rek6=a.kd_rek6) as nm_rek6")
            ->whereRaw("a.kd_skpd=? AND a.no_terima + '.' + kanal NOT IN(select ISNULL(no_terima,'') + '.' + ISNULL(kanal,'') no_terima from trdkasin_pkd where kd_skpd=?) AND  a.tgl_terima=? and a.jns_pembayaran=?", [$kd_skpd, $kd_skpd, $tgl_terima, $jenis_pembayaran])
            ->where(function ($query) use ($gerai) {
                if ($gerai != 'all') {
                    $query->where('a.sumber', $gerai);
                }
            })
            ->whereNotIn('a.no_terima', $no_sts)
            ->where(function ($query) {
                $query->where('a.kunci', '!=', '1')->orWhereNull('a.kunci');
            })
            // ->orderBy('b.nm_pengirim')
            ->orderBy('a.tgl_terima')
            ->orderBy('a.kd_rek6')
            ->get();
        // dd(DB::getQueryLog());

        return response()->json($data);
    }

    public function simpanPenyetoranIni(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;
        $detail_sts = json_decode($data['detail_sts'], true);

        DB::beginTransaction();
        try {
            $tanggal = $data['tgl_sts'];

            $cek = collect(DB::select("SELECT sum(status_kasda) status_kasda, sum(status_spj) status_spj from (
            SELECT CASE
                        WHEN tgl2<=tgl_kasda THEN '1'
                            ELSE '0'
                        END as status_kasda,0 status_spj,*
                        from (

                            SELECT max(tgl_kunci) as tgl_kasda,''tgl_spj,'$tanggal'as tgl2 FROM [dbo].[tr_kunci] where kd_skpd=?
                        )zz

            UNION ALL
            SELECT 0 status_kasda,CASE
                        WHEN tgl2<=tgl_spj THEN '1'
                            ELSE '0'
                        END as status_spj,*
                        from (

                            SELECT ''tgl_kasda,max(bulan) as tgl_spj,MONTH('$tanggal')as tgl2 FROM [dbo].[trhspj_terima_ppkd]
                                    where kd_skpd=? and cek = '1'
                        )zz
            )xx", [$kd_skpd, $kd_skpd]))->first();

            if ($cek->status_kasda == '1') {
                return response()->json([
                    'message' => '2'
                ]);
            }
            if ($cek->status_spj == '1') {
                return response()->json([
                    'message' => '3'
                ]);
            }

            $cek_terima = DB::table('trhkasin_pkd')->where(['no_sts' => $data['no_sts'], 'kd_skpd' => $kd_skpd])->count();
            if ($cek_terima > 0) {
                return response()->json([
                    'message' => '4'
                ]);
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
                    'keterangan' => isset($data['keterangan']) ? $data['keterangan'] : '',
                    'total' => $data['total'],
                    'kd_bank' => '',
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    'jns_trans' => '4',
                    'rek_bank' => '',
                    'sumber' => '',
                    'pot_khusus' => '0',
                    'no_sp2d' => '',
                    'jns_cp' => '',
                    'no_terima' => '',
                    'jns_pembayaran' => $data['jenis_pembayaran']
                ]);

            DB::table('trdkasin_pkd')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_sts']])
                ->delete();

            if (isset($detail_sts)) {
                foreach ($detail_sts as $detail1) {
                    $data_input = [
                        'no_sts' => $data['no_sts'],
                        'kd_skpd' => $data['kd_skpd'],
                        'kd_rek6' => $detail1['kd_rek6'],
                        'rupiah' => $detail1['nilai'],
                        'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                        'no_terima' => $detail1['no_sts'],
                        'sumber' => $detail1['sumber'],
                        'kanal' => $detail1['kanal'],
                    ];
                    DB::table('trdkasin_pkd')->insert($data_input);
                };
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

            $jumlah = DB::table('ms_skpd')->where(['jns' => '2', 'kd_skpd' => $data['kd_skpd']])->count();

            if ($jumlah == 0 && $data['kd_skpd'] <> '1.02.0.00.0.00.02.0000') {
                DB::table('trhkasin_ppkd')
                    ->insert([
                        'no_kas' => $nomor,
                        'tgl_kas' => $data['tgl_sts'],
                        'no_sts' => $data['no_sts'],
                        'tgl_sts' => $data['tgl_sts'],
                        'kd_skpd' => $data['kd_skpd'],
                        'keterangan' => isset($data['keterangan']) ? $data['keterangan'] : '',
                        'total' => $data['total'],
                        'kd_bank' => '',
                        'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                        'jns_trans' => '4',
                        'rek_bank' => '',
                        'sumber' => '',
                        'pot_khusus' => '0',
                        'no_sp2d' => '',
                        'jns_cp' => '',
                        'no_terima' => '',
                    ]);

                DB::table('trhkasin_pkd')
                    ->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_sts']])
                    ->update([
                        'no_cek' => '1',
                        'status' => '1'
                    ]);

                if (isset($detail_sts)) {
                    foreach ($detail_sts as $detail2) {
                        $data_input1 = [
                            'no_sts' => $data['no_sts'],
                            'kd_skpd' => $data['kd_skpd'],
                            'kd_rek6' => $detail2['kd_rek6'],
                            'rupiah' => $detail2['nilai'],
                            'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                            'no_kas' => $detail2['no_sts'],
                            'sumber' => $detail2['sumber'],
                            'kanal' => $detail2['kanal'] == "" ? '' : $detail2['kanal'],
                        ];
                        DB::table('trdkasin_ppkd')->insert($data_input1);
                    }
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



            // if ($jumlah == 0 && $data['kd_skpd'] <> '1.02.0.00.0.00.02.0000') {

            // }

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

    public function editPenyetoranIni($no_sts, $kd_skpd)
    {
        $no_sts = Crypt::decrypt($no_sts);
        $kd_skpd = Crypt::decrypt($kd_skpd);
        $spjbulan = cek_status_spj_pend($kd_skpd);
        $status_anggaran = status_anggaran();

        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_kegiatan' => DB::table('trskpd as a')
                ->join('ms_sub_kegiatan as b', 'a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan')
                ->selectRaw("a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program,a.nm_program,a.total")
                ->where(['kd_skpd' => $kd_skpd, 'jns_ang' => $status_anggaran, 'jns_sub_kegiatan' => '4'])
                ->whereRaw("right(a.kd_sub_kegiatan,7)=?", ['00.0004'])
                ->get(),
            'sts' => DB::table('trhkasin_pkd as a')
                ->leftJoin('trhkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("a.*,b.no_kas,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd,(CASE WHEN month(a.tgl_sts)<=? THEN 1 ELSE 0 END) ketspj,a.user_name", [$spjbulan])
                ->where(['a.kd_skpd' => $kd_skpd, 'a.jns_trans' => '4', 'a.no_sts' => $no_sts])
                ->whereRaw("not exists (select * from trdkasin_pkd b where left(kd_rek6,12) =? and a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts) AND a.keterangan not like '%keterlambatan%'", ['410411010001'])
                ->first(),
            'detail_sts' => DB::select("SELECT a.*,(SELECT nama from ms_kanal where kode=a.kanal) as nama, (select nm_rek6 from ms_rek6 where kd_rek6 = a.kd_rek6) as nm_rek,b.nm_pengirim
        from trdkasin_pkd a left join ms_pengirim b on a.sumber=b.kd_pengirim and a.kd_skpd=b.kd_skpd where a.no_sts = ?  AND a.kd_skpd = ? and left(a.kd_rek6,1)='4' order by a.no_sts", [$no_sts, $kd_skpd])
        ];

        return view('penatausahaan.penyetoran_tahun_ini.edit')->with($data);
    }

    public function updatePenyetoranIni(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $tanggal = $data['tgl_sts'];

            $cek = collect(DB::select("SELECT sum(status_kasda) status_kasda, sum(status_spj) status_spj from (
            SELECT CASE
                        WHEN tgl2<=tgl_kasda THEN '1'
                            ELSE '0'
                        END as status_kasda,0 status_spj,*
                        from (

                            SELECT max(tgl_kunci) as tgl_kasda,''tgl_spj,'$tanggal'as tgl2 FROM [dbo].[tr_kunci] where kd_skpd=?
                        )zz

            UNION ALL
            SELECT 0 status_kasda,CASE
                        WHEN tgl2<=tgl_spj THEN '1'
                            ELSE '0'
                        END as status_spj,*
                        from (

                            SELECT ''tgl_kasda,max(bulan) as tgl_spj,MONTH('$tanggal')as tgl2 FROM [dbo].[trhspj_terima_ppkd]
                                    where kd_skpd=? and cek = '1'
                        )zz
            )xx", [$kd_skpd, $kd_skpd]))->first();

            if ($cek->status_kasda == '1') {
                return response()->json([
                    'message' => '2'
                ]);
            }
            if ($cek->status_spj == '1') {
                return response()->json([
                    'message' => '3'
                ]);
            }

            $cek_terima = DB::table('trhkasin_pkd')
                ->where(['no_sts' => $data['no_sts'], 'kd_skpd' => $kd_skpd])
                ->count();

            if ($cek_terima > 0 && $data['no_sts'] != $data['no_simpan']) {
                return response()->json([
                    'message' => '4'
                ]);
            }

            $nomor = $data['no_kas'];

            DB::table('trhkasin_pkd')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_simpan']])
                ->delete();

            DB::table('trhkasin_pkd')
                ->insert([
                    'no_sts' => $data['no_sts'],
                    'tgl_sts' => $data['tgl_sts'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keterangan' => isset($data['keterangan']) ? $data['keterangan'] : '',
                    'total' => $data['total'],
                    'kd_bank' => '',
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    'jns_trans' => '4',
                    'rek_bank' => '',
                    'sumber' => '',
                    'pot_khusus' => '0',
                    'no_sp2d' => '',
                    'jns_cp' => '',
                    'no_terima' => '',
                    'jns_pembayaran' => $data['jenis_pembayaran']
                ]);

            $jumlah = DB::table('ms_skpd')
                ->where(['jns' => '2', 'kd_skpd' => $data['kd_skpd']])
                ->count();

            if ($jumlah == 0 && $data['kd_skpd'] <> '1.02.0.00.0.00.02.0000') {
                DB::table('trhkasin_ppkd')
                    ->where(['no_sts' => $data['no_simpan'], 'kd_skpd' => $data['kd_skpd']])
                    ->delete();

                DB::table('trhkasin_ppkd')
                    ->insert([
                        'no_kas' => $nomor,
                        'tgl_kas' => $data['tgl_sts'],
                        'no_sts' => $data['no_sts'],
                        'tgl_sts' => $data['tgl_sts'],
                        'kd_skpd' => $data['kd_skpd'],
                        'keterangan' => isset($data['keterangan']) ? $data['keterangan'] : '',
                        'total' => $data['total'],
                        'kd_bank' => '',
                        'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                        'jns_trans' => '4',
                        'rek_bank' => '',
                        'sumber' => '',
                        'pot_khusus' => '0',
                        'no_sp2d' => '',
                        'jns_cp' => '',
                        'no_terima' => '',
                    ]);

                DB::table('trhkasin_pkd')
                    ->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_sts']])
                    ->update([
                        'no_cek' => '1',
                        'status' => '1'
                    ]);
            }

            DB::table('trdkasin_pkd')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_sts' => $data['no_simpan']])
                ->delete();

            if (isset($data['detail_sts'])) {
                // DB::table('trdkasin_pkd')
                //     ->insert(array_map(
                //         function ($value) use ($data) {
                //             return [
                //                 'no_sts' => $data['no_sts'],
                //                 'kd_skpd' => $data['kd_skpd'],
                //                 'kd_rek6' => $value['kd_rek6'],
                //                 'rupiah' => $value['nilai'],
                //                 'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                //                 'no_terima' => $value['no_sts'],
                //                 'sumber' => $value['sumber'],
                //                 'kanal' => !isset($value['kanal']) ? '' : $value['kanal'],
                //             ];
                //         },
                //         $data['detail_sts']
                //     ));
                foreach ($data['detail_sts'] as $detail) {
                    $data_input = [
                        'no_sts' => $data['no_sts'],
                        'kd_skpd' => $data['kd_skpd'],
                        'kd_rek6' => $detail['kd_rek6'],
                        'rupiah' => $detail['nilai'],
                        'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                        'no_terima' => $detail['no_sts'],
                        'sumber' => $detail['sumber'],
                        'kanal' => $detail['kanal'],
                    ];
                    DB::table('trdkasin_pkd')->insert($data_input);
                }
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
                DB::table('trdkasin_ppkd')
                    ->where(['no_sts' => $data['no_simpan'], 'kd_skpd' => $data['kd_skpd']])
                    ->delete();

                if (isset($data['detail_sts'])) {
                    // DB::table('trdkasin_ppkd')
                    //     ->insert(array_map(function ($value) use ($data) {
                    //         return [
                    //             'no_sts' => $data['no_sts'],
                    //             'kd_skpd' => $data['kd_skpd'],
                    //             'kd_rek6' => $value['kd_rek6'],
                    //             'rupiah' => $value['nilai'],
                    //             'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    //             'no_kas' => $value['no_sts'],
                    //             'sumber' => $value['sumber'],
                    //             'kanal' => $value['kanal'],
                    //         ];
                    //     }, $data['detail_sts']));
                    foreach ($data['detail_sts'] as $detail) {
                        $data_input2 = [
                            'no_sts' => $data['no_sts'],
                            'kd_skpd' => $data['kd_skpd'],
                            'kd_rek6' => $detail['kd_rek6'],
                            'rupiah' => $detail['nilai'],
                            'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                            'no_kas' => $detail['no_sts'],
                            'sumber' => $detail['sumber'],
                            'kanal' => $detail['kanal'],
                        ];
                        DB::table('trdkasin_ppkd')->insert($data_input2);
                    }
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

    public function hapusPenyetoranIni(Request $request)
    {
        $no_sts = $request->no_sts;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('tr_terima as a')
                ->join('trdkasin_pkd as b', function ($join) {
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                    $join->on(
                        'a.no_terima',
                        '=',
                        'b.no_terima'
                    );
                    $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
                })
                ->where([
                    'a.kd_skpd' => $kd_skpd, 'b.no_sts' => $no_sts
                ])
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

    public function cekPenyetoranIni(Request $request)
    {
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'header' => DB::table('config_app')
                ->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')
                ->first(),
            'skpd' => $kd_skpd,
            'tanggal_awal' => $tgl_awal,
            'tanggal_akhir' => $tgl_akhir,
            'data_setor' => DB::select("SELECT * FROM (
                        select 1 nomor, b.no_sts, a.tgl_sts, a.keterangan, '' kd_rek6,
                        '' nm_rek6, a.total as rupiah, '' no_terima, '' tgl_terima, '' sumber, '' nm_sumber,'' as pembayaran
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd
                                                                and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                        LEFT JOIN tr_terima c on b.kd_skpd=c.kd_skpd and b.kd_rek6=c.kd_rek6
                                                                and  b.kd_sub_kegiatan=c.kd_sub_kegiatan and b.no_terima=c.no_terima AND b.kanal=c.kanal
                        where b.kd_skpd=? AND a.jns_trans IN ('4','2')
                        and a.tgl_sts BETWEEN ? AND ?
                        group by b.no_sts, a.tgl_sts, a.keterangan, a.total

                        UNION ALL

                        select 2 nomor, b.no_sts, a.tgl_sts, '' keterangan, b.kd_rek6,
                        (select nm_rek6 from ms_rek6 where kd_rek6=b.kd_rek6) nm_rek6, b.rupiah, b.no_terima, c.tgl_terima, b.sumber,
                        (select nm_pengirim from ms_pengirim where kd_pengirim=b.sumber) nm_sumber,b.kanal
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd
                                                                and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                        LEFT JOIN tr_terima c on b.kd_skpd=c.kd_skpd and b.kd_rek6=c.kd_rek6 and  b.kd_sub_kegiatan=c.kd_sub_kegiatan
                                                                    and b.no_terima=c.no_terima AND b.kanal=c.kanal
                        where b.kd_skpd=? AND a.jns_trans IN ('4','2')
                        and a.tgl_sts BETWEEN ? AND ?
                ) x
                order by tgl_sts, no_sts, nomor, kd_rek6", [$kd_skpd, $tgl_awal, $tgl_akhir, $kd_skpd, $tgl_awal, $tgl_akhir]),
            'total_setor' => collect(DB::select("SELECT SUM(rupiah) total FROM (
                        select 1 nomor, b.no_sts, a.tgl_sts, a.keterangan, '' kd_rek6,
                        '' nm_rek6, a.total as rupiah, '' no_terima, '' tgl_terima, '' sumber, '' nm_sumber
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd
                        and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                        LEFT JOIN tr_terima c on b.kd_skpd=c.kd_skpd and b.kd_rek6=c.kd_rek6 and  b.kd_sub_kegiatan=c.kd_sub_kegiatan and b.no_terima=c.no_terima AND b.kanal=c.kanal
                        where b.kd_skpd=? AND a.jns_trans IN ('4','2') and a.tgl_sts BETWEEN ? AND ?
                        group by b.no_sts, a.tgl_sts, a.keterangan, a.total) x", [$kd_skpd, $tgl_awal, $tgl_akhir]))->first()
        ];

        return view('penatausahaan.penyetoran_tahun_ini.cetak')->with($data);
    }

    public function validasiPenyetoranIni(Request $request)
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

                $datarkud = DB::table('ms_rek_kasda')
                    ->first();

                $rkud = $datarkud->rek_bank;

                DB::insert("INSERT into trhkasin_ppkd(no_kas,tgl_kas,no_sts,kd_skpd,tgl_sts,keterangan,total,kd_sub_kegiatan,jns_trans,sumber,kd_skpd_sumber,rek_bank)
						select $nomor+ROW_NUMBER() OVER (ORDER BY tgl_kas) AS no_kas, tgl_sts, no_sts, kd_skpd, tgl_sts, keterangan, total, kd_sub_kegiatan, jns_trans, sumber,kd_skpd, '$rkud' as rek_bank
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
