<?php

namespace App\Http\Controllers\Skpd\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LPJController extends Controller
{
    // INPUT LPJ UP/GU SKPD TANPA UNIT
    public function indexSkpdTanpaUnit()
    {
        return view('skpd.lpj.skpd_tanpa_unit.index');
    }

    public function loadSkpdTanpaUnit()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhlpj as a')
            ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd")
            ->where(['a.kd_skpd' => $kd_skpd, 'a.jenis' => '1'])
            ->orderBy('a.tgl_lpj')
            ->orderBy('a.no_lpj')
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("lpj.skpd_tanpa_unit.edit", Crypt::encrypt($row->no_lpj)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_lpj . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahSkpdTanpaUnit()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'nilai_up' => DB::table('ms_up')
                ->selectRaw("SUM(nilai_up) as nilai")
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'spd_global' => collect(DB::select("SELECT ISNULL(nilai_spd,0) spd, ISNULL(transaksi,0) transaksi, isnull(nilai_spd,0)-isnull(transaksi,0) sisa_spd FROM(
                select 1 as nomor, SUM(nilai) as nilai_spd from trhspd a INNER JOIN trdspd b ON a.no_spd=b.no_spd WHERE kd_skpd = ? AND (RIGHT(kd_sub_kegiatan,10) !='01.1.02.01' OR kd_sub_kegiatan !='4.01.01.1.11.01') AND status='1') a LEFT JOIN (SELECT 1 as nomor, SUM(b.nilai) as transaksi FROM trhspp a INNER JOIN trdspp b ON a.kd_skpd=b.kd_skpd AND a.no_spp=b.no_spp WHERE a.kd_skpd = ? AND (RIGHT(b.kd_sub_kegiatan,10) !='01.1.02.01' OR b.kd_sub_kegiatan !='4.01.01.1.11.01') and (sp2d_batal is null or sp2d_batal<>'1')) b ON a.nomor=b.nomor", [$kd_skpd, $kd_skpd]))->first(),
        ];

        return view('skpd.lpj.skpd_tanpa_unit.create')->with($data);
    }

    public function detailSkpdTanpaUnit(Request $request)
    {
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $kd_skpd = $request->kd_skpd;

        $data1 = DB::table('trdtransout as a')
            ->join('trhtransout as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("b.kd_skpd,b.tgl_bukti,a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.no_bukti,a.nilai,a.kd_skpd as kd_skpd1")
            ->whereRaw("(a.no_bukti+a.kd_sub_kegiatan+a.kd_rek6+a.kd_skpd) NOT IN(SELECT (no_bukti+kd_sub_kegiatan+kd_rek6+kd_skpd) FROM trlpj) AND b.panjar not in ('3','5') AND b.tgl_bukti >= ? and b.tgl_bukti <= ? and b.jns_spp='1' and b.kd_skpd=?", [$tgl_awal, $tgl_akhir, $kd_skpd]);

        $data2 = DB::table('trdtransout as a')
            ->join('trhtransout as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("b.kd_skpd,b.tgl_bukti,a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.no_bukti,a.nilai,a.kd_skpd as kd_skpd1")
            ->whereRaw("(a.no_bukti+a.kd_sub_kegiatan+a.kd_rek6+a.kd_skpd) NOT IN(SELECT (no_bukti+kd_sub_kegiatan+kd_rek6+kd_skpd) FROM trlpj) AND b.panjar in ('3','5') AND b.tgl_bukti >= ? and b.tgl_bukti <= ? and b.jns_spp='1' and b.kd_skpd=?", [$tgl_awal, $tgl_akhir, $kd_skpd])
            ->unionAll($data1);

        $data = DB::table(DB::raw("({$data2->toSql()}) AS sub"))
            ->mergeBindings($data2)
            ->orderByRaw("kd_skpd,tgl_bukti,kd_sub_kegiatan, kd_rek6, cast(no_bukti as int)")
            ->get();

        return response()->json($data);
    }

    public function totalspdSkpdTanpaUnit(Request $request)
    {
        $jns = '5';
        $kd_skpd = Auth::user()->kd_skpd;
        $nospp = '';
        $no_bukti = '';

        $data = collect(DB::select("SELECT spd,keluar1 = keluar-terima,keluarspp  from(
                        select sum(spd) as spd,sum(terima) as terima,sum(keluar) as keluar,sum(keluarspp) as keluarspp from(SELECT 'SPD' as ket,isnull(sum(nilai),0) as spd,0 as terima,0 as keluar,0 as keluarspp from trhspd d join trdspd e on d.no_spd=e.no_spd
                            where d.kd_skpd=? and d.status='1' and d.jns_beban=? UNION ALL
                            SELECT 'SPP' as ket,0 as spd,0 as terima,isnull(sum(a.nilai),0) [keluar],0 as keluarspp from trdspp a join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
                            where LEFT(kd_rek6,1)=? and b.jns_spp in ('3','4','5','6') and left(a.kd_skpd,17)=left(?,17) and b.no_spp<>? and (sp2d_batal is null or sp2d_batal <>'1')
                            union all
                            select 'Trans UP/GU' as ket,0 as spd,0 as terima,isnull(sum(a.nilai),0) [keluar],0 as keluarspp from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                            where LEFT(kd_rek6,1)=? and b.jns_spp in ('1','2') and left(a.kd_skpd,17)=left(?,17) and a.no_bukti<>?
                            union all
                            select 'Trans UP/GU CMS' as ket,0 as spd,0 as terima,isnull(sum(a.nilai),0) [keluar],0 as keluarspp from trdtransout_cmsbank a join trhtransout_cmsbank b on a.no_voucher=b.no_voucher
                            and a.kd_skpd=b.kd_skpd where LEFT(kd_rek6,1)=? and b.jns_spp in ('1','2') and left(a.kd_skpd,17)=left(?,17) and status_validasi<>'1' union all
                            select 'Panjar' as ket,0 as spd,0 as terima,ISNULL(sum(nilai),0) as keluar,0 as keluarspp from tr_panjar where jns='1' and left(kd_skpd,17)=left(?,17) and no_kas<>?
                            union all
                            select 'T/P Panjar' as ket,0 as spd,ISNULL(sum(nilai),0) as terima,0 as keluar,0 as keluarspp from tr_jpanjar where left(kd_skpd,17)=left(?,17) and no_kas<>? union all
                            select 'SPP' as ket,0 as spd,0 as terima,0 as keluar,isnull(sum(a.nilai),0) [keluarspp] from trdspp a join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
                            where b.jns_spp in ('1','2','3','6') and left(a.kd_skpd,17)=left(?,17) and b.no_spp<>? and (sp2d_batal is null or sp2d_batal <>'1') )as f
                    )as g", [$kd_skpd, $jns, $jns, $kd_skpd, $nospp, $jns, $kd_skpd, $no_bukti, $jns, $kd_skpd, $kd_skpd, $no_bukti, $kd_skpd, $no_bukti, $kd_skpd, $nospp]))->first();

        return response()->json($data);
    }

    public function simpanSkpdTanpaUnit(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $cek_lpj = DB::table('trhlpj')->where(['no_lpj' => $data['no_lpj'], 'kd_skpd' => $kd_skpd])->count();
            if ($cek_lpj > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhlpj')
                ->insert([
                    'no_lpj' => $data['no_lpj'],
                    'tgl_lpj' => $data['tgl_lpj'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keterangan' => $data['keterangan'],
                    'tgl_awal' => $data['tgl_awal'],
                    'tgl_akhir' => $data['tgl_akhir'],
                    'status' => '0',
                    'jenis' => '1',
                ]);

            DB::table('trhlpj_unit')
                ->insert([
                    'no_lpj' => $data['no_lpj'],
                    'tgl_lpj' => $data['tgl_lpj'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keterangan' => $data['keterangan'],
                    'tgl_awal' => $data['tgl_awal'],
                    'tgl_akhir' => $data['tgl_akhir'],
                    'status' => '0',
                    'jenis' => '1',
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

    public function editSkpdTanpaUnit($no_sts)
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

    public function updateSkpdTanpaUnit(Request $request)
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

    public function hapusSkpdTanpaUnit(Request $request)
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
}
