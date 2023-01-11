<?php

namespace App\Http\Controllers\Skpd\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\isEmpty;

class LPJController extends Controller
{
    // INPUT LPJ UP/GU SKPD TANPA UNIT
    public function indexSkpdTanpaUnit()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'ttd1' => DB::table('ms_ttd')
                ->select('nip', 'nama')
                ->where(['kd_skpd' => $kd_skpd])
                ->whereIn('kode', ['BK'])
                ->get(),
            'ttd2' => DB::table('ms_ttd')
                ->select('nip', 'nama')
                ->where(['kd_skpd' => $kd_skpd])
                ->whereIn('kode', ['PA', 'KPA'])
                ->get(),
        ];
        return view('skpd.lpj.skpd_tanpa_unit.index')->with($data);
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
            if ($row->status == '1' || $row->status == '2') {
                $btn = "";
            } else {
                $btn = '<a href="' . route("lpj.skpd_tanpa_unit.edit", Crypt::encrypt($row->no_lpj)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
                $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_lpj . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            }
            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_lpj . '\',\'' . $row->jenis . '\',\'' . $row->kd_skpd . '\');" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak LPJ" style="margin-right:4px"><i class="uil-print"></i></a>';
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
            $no_lpj = $data['no_lpj'] . "/LPJ/UPGU/" . $data['kd_skpd'] . "/" . tahun_anggaran();

            $cek_lpj = DB::table('trhlpj')->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])->count();
            if ($cek_lpj > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhlpj')
                ->insert([
                    'no_lpj' => $no_lpj,
                    'tgl_lpj' => $data['tgl_lpj'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keterangan' => $data['keterangan'],
                    'tgl_awal' => $data['tgl_awal'],
                    'tgl_akhir' => $data['tgl_akhir'],
                    'status' => '0',
                    'jenis' => '1',
                ]);

            if (isset($data['detail_lpj'])) {
                DB::table('trlpj')
                    ->insert(array_map(function ($value) use ($no_lpj, $data) {
                        return [
                            'no_lpj' => $no_lpj,
                            'kd_skpd' => $value['kd_skpd'],
                            'no_bukti' => $value['no_bukti'],
                            'tgl_lpj' => $data['tgl_lpj'],
                            'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                            'kd_rek6' => $value['kdrek6'],
                            'nm_rek6' => $value['nmrek6'],
                            'nilai' => $value['nilai'],
                            'kd_bp_skpd' => $data['kd_skpd'],
                            'no_lpj_unit' => $no_lpj,
                        ];
                    }, $data['detail_lpj']));
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

    public function editSkpdTanpaUnit($no_lpj)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_lpj = Crypt::decrypt($no_lpj);
        $arr = explode("/", $no_lpj);

        $data = [
            'nomor' => $arr[0],
            'lpj' => DB::table('trhlpj as a')
                ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd")
                ->where(['a.kd_skpd' => $kd_skpd, 'a.jenis' => '1', 'a.no_lpj' => $no_lpj])
                ->orderBy('a.tgl_lpj')
                ->orderBy('a.no_lpj')
                ->first(),
            'detail_lpj' => DB::table('trhlpj as a')
                ->join('trlpj as b', function ($join) {
                    $join->on('a.no_lpj', '=', 'b.no_lpj');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('b.*')
                ->where(['a.no_lpj' => $no_lpj, 'a.kd_skpd' => $kd_skpd, 'a.jenis' => '1'])
                ->get(),
            'total_detail' => DB::table('trhlpj as a')
                ->join('trlpj as b', function ($join) {
                    $join->on('a.no_lpj', '=', 'b.no_lpj');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("SUM(b.nilai) as nilai")
                ->where(['a.no_lpj' => $no_lpj, 'a.kd_skpd' => $kd_skpd, 'a.jenis' => '1'])
                ->first(),
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

        return view('skpd.lpj.skpd_tanpa_unit.edit')->with($data);
    }

    public function updateSkpdTanpaUnit(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $no_lpj = $data['no_lpj'] . "/LPJ/UPGU/" . $data['kd_skpd'] . "/" . tahun_anggaran();

            $cek_terima = DB::table('trhlpj')->where(['no_lpj' => $no_lpj, 'kd_skpd' => $data['kd_skpd']])->count();
            if ($cek_terima > 0 && $no_lpj != $data['no_lpj_simpan']) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhlpj')->where(['no_lpj' => $data['no_lpj_simpan'], 'kd_skpd' => $data['kd_skpd']])->delete();

            DB::table('trhlpj')
                ->insert([
                    'no_lpj' => $no_lpj,
                    'tgl_lpj' => $data['tgl_lpj'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keterangan' => $data['keterangan'],
                    'tgl_awal' => $data['tgl_awal'],
                    'tgl_akhir' => $data['tgl_akhir'],
                    'status' => '0',
                    'jenis' => '1',
                ]);

            DB::table('trlpj')->where(['no_lpj' => $data['no_lpj_simpan'], 'kd_skpd' => $data['kd_skpd']])->delete();

            if (isset($data['detail_lpj'])) {
                DB::table('trlpj')
                    ->insert(array_map(function ($value) use ($no_lpj, $data) {
                        return [
                            'no_lpj' => $no_lpj,
                            'kd_skpd' => $value['kd_skpd'],
                            'no_bukti' => $value['no_bukti'],
                            'tgl_lpj' => $data['tgl_lpj'],
                            'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                            'kd_rek6' => $value['kdrek6'],
                            'nm_rek6' => $value['nmrek6'],
                            'nilai' => $value['nilai'],
                            'kd_bp_skpd' => $data['kd_skpd'],
                            'no_lpj_unit' => $no_lpj,
                        ];
                    }, $data['detail_lpj']));
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
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trlpj')
                ->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trhlpj')
                ->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
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

    public function subKegiatanSkpdTanpaUnit(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::table('trlpj as a')
            ->join('trhlpj as b', function ($join) {
                $join->on('a.no_lpj', '=', 'b.no_lpj');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->leftJoin('trskpd as c', function ($join) {
                $join->on('a.kd_sub_kegiatan', '=', 'c.kd_sub_kegiatan');
            })
            ->select('a.kd_sub_kegiatan', 'c.nm_sub_kegiatan')
            ->whereRaw("a.no_lpj = ? AND left(a.kd_skpd,17)=left(?,17)", [$no_lpj, $kd_skpd])
            ->groupBy('a.kd_sub_kegiatan', 'c.nm_sub_kegiatan')
            ->orderBy('a.kd_sub_kegiatan')
            ->get();

        return response()->json($data);
    }

    public function sptbSkpdTanpaUnit(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;
        $pa_kpa = $request->pa_kpa;
        $jenis_print = $request->jenis_print;

        $data = [
            'header' => DB::table('config_app')
                ->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')
                ->first(),
            'kd_skpd' => $kd_skpd,
            'dpa' => DB::table('trhrka')
                ->select('no_dpa', 'tgl_dpa')
                ->where(['kd_skpd' => $kd_skpd, 'jns_ang' => status_anggaran()])
                ->first(),
            'jumlah_belanja' => DB::table('trlpj as a')
                ->join('trhlpj as b', function ($join) {
                    $join->on('a.no_lpj', '=', 'b.no_lpj');
                    $join->on('a.kd_bp_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("sum(nilai) as nilai,b.tgl_lpj")
                ->whereRaw("a.no_lpj=? and b.jenis=? and left(a.kd_bp_skpd,17)=left(?,17)", [$no_lpj, '1', $kd_skpd])
                ->groupBy('b.tgl_lpj')
                ->first(),
        ];

        return view('skpd.lpj.skpd_tanpa_unit.cetak.sptb')->with($data);
    }


    // INPUT LPJ UP/GU SKPD + UNIT
    public function indexSkpdDanUnit()
    {
        return view('skpd.lpj.skpd_dan_unit.index');
    }

    public function loadSkpdDanUnit()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhlpj as a')
            ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd")
            ->where(['a.kd_skpd' => $kd_skpd, 'a.jenis' => '1'])
            ->orderBy('a.tgl_lpj')
            ->orderBy('a.no_lpj')
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            if ($row->status == '1' || $row->status == '2') {
                $btn = "";
            } else {
                $btn = '<a href="' . route("lpj.skpd_dan_unit.edit", Crypt::encrypt($row->no_lpj)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
                $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_lpj . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            }
            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_lpj . '\',\'' . $row->jenis . '\',\'' . $row->kd_skpd . '\');" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak LPJ" style="margin-right:4px"><i class="uil-print"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahSkpdDanUnit()
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

        return view('skpd.lpj.skpd_dan_unit.create')->with($data);
    }

    public function loadLpjSkpdDanUnit(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $no_lpj_unit = $request->no_lpj_unit;

        $no_lpj = array();
        if (!empty($no_lpj_unit)) {
            foreach ($no_lpj_unit as $lpj) {
                $no_lpj[] = $lpj['no_lpj_unit'];
            }
        } else {
            $no_lpj[] = '';
        }

        $data = DB::table('trhlpj_unit as a')
            ->selectRaw("a.*,(SELECT SUM(nilai) FROM trlpj WHERE no_lpj_unit=a.no_lpj) AS nilai,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=a.kd_skpd) AS nm_skpd")
            ->where(['a.status' => '1', 'a.jenis' => '1', 'a.status_validasi' => '1'])
            ->whereRaw("a.no_lpj NOT IN (SELECT no_lpj_unit FROM trlpj WHERE a.no_lpj=no_lpj_unit AND a.kd_skpd=kd_skpd AND (no_lpj <> '' OR kd_bp_skpd <> ''))")
            ->whereRaw("LEFT(a.kd_skpd,17)=LEFT(?,17)", [$kd_skpd])
            ->whereNotIn('no_lpj', $no_lpj)
            ->get();

        return response()->json($data);
    }

    public function simpanSkpdDanUnit(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $list_lpj = array();
            if (!empty($data['detail_lpj'])) {
                foreach ($data['detail_lpj'] as $lpj) {
                    $list_lpj[] = $lpj['no_lpj_unit'];
                }
            } else {
                $list_lpj[] = '';
            }

            $no_lpj = $data['no_lpj'] . "/LPJ/UPGU/" . $data['kd_skpd'] . "/" . tahun_anggaran();

            $cek_lpj = DB::table('trhlpj')
                ->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
                ->count();

            if ($cek_lpj > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhlpj')
                ->insert([
                    'no_lpj' => $no_lpj,
                    'tgl_lpj' => $data['tgl_lpj'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keterangan' => $data['keterangan'],
                    'tgl_awal' => '',
                    'tgl_akhir' => '',
                    'status' => '0',
                    'jenis' => '1',
                ]);

            DB::table('trlpj')
                ->whereIn('no_lpj_unit', $list_lpj)
                ->update([
                    'no_lpj' => $no_lpj,
                    'kd_bp_skpd' => $data['kd_skpd']
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

    public function editSkpdDanUnit($no_lpj)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_lpj = Crypt::decrypt($no_lpj);
        $arr = explode("/", $no_lpj);

        $data = [
            'nomor' => $arr[0],
            'lpj' => DB::table('trhlpj as a')
                ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd")
                ->where(['a.kd_skpd' => $kd_skpd, 'a.jenis' => '1', 'a.no_lpj' => $no_lpj])
                ->first(),
            'detail_lpj' => DB::table('trhlpj as a')
                ->join('trlpj as b', function ($join) {
                    $join->on('a.no_lpj', '=', 'b.no_lpj');
                    $join->on('a.kd_skpd', '=', 'b.kd_bp_skpd');
                })
                ->join('trhlpj_unit as c', function ($join) {
                    $join->on('b.no_lpj_unit', '=', 'c.no_lpj');
                    $join->on('b.kd_skpd', '=', 'c.kd_skpd');
                })
                ->select('a.no_lpj as lpj_global', 'c.*')
                ->selectRaw("(SELECT SUM(nilai) FROM trlpj WHERE no_lpj_unit=c.no_lpj) AS nilai")
                ->where(['a.no_lpj' => $no_lpj, 'a.kd_skpd' => $kd_skpd, 'a.jenis' => '1'])
                ->distinct()
                ->get(),
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
        // dd($data['detail_lpj']);
        return view('skpd.lpj.skpd_dan_unit.edit')->with($data);
    }

    public function updateSkpdDanUnit(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $no_lpj = $data['no_lpj'] . "/LPJ/UPGU/" . $data['kd_skpd'] . "/" . tahun_anggaran();

            $cek_terima = DB::table('trhlpj')->where(['no_lpj' => $no_lpj, 'kd_skpd' => $data['kd_skpd']])->count();
            if ($cek_terima > 0 && $no_lpj != $data['no_lpj_simpan']) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            $list_lpj = array();
            if (!empty($data['detail_lpj'])) {
                foreach ($data['detail_lpj'] as $lpj) {
                    $list_lpj[] = $lpj['no_lpj_unit'];
                }
            } else {
                $list_lpj[] = '';
            }

            DB::table('trhlpj')
                ->where(['no_lpj' => $data['no_lpj_simpan'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            DB::table('trhlpj')
                ->insert([
                    'no_lpj' => $no_lpj,
                    'tgl_lpj' => $data['tgl_lpj'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keterangan' => $data['keterangan'],
                    'tgl_awal' => '',
                    'tgl_akhir' => '',
                    'status' => '0',
                    'jenis' => '1',
                ]);

            DB::table('trlpj')
                ->where(['no_lpj' => $data['no_lpj_simpan'], 'kd_bp_skpd' => $data['kd_skpd']])
                ->update([
                    'no_lpj' => '',
                    'kd_bp_skpd' => ''
                ]);

            DB::table('trlpj')
                ->whereIn('no_lpj_unit', $list_lpj)
                ->update([
                    'no_lpj' => $no_lpj,
                    'kd_bp_skpd' => $data['kd_skpd']
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

    public function hapusSkpdDanUnit(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trlpj')
                ->where(['no_lpj' => $no_lpj, 'kd_bp_skpd' => $kd_skpd])
                ->update([
                    'no_lpj' => '',
                    'kd_bp_skpd' => ''
                ]);

            DB::table('trhlpj')
                ->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
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


    // INPUT LPJ UP/GU SKPD / UNIT
    public function indexSkpdAtauUnit()
    {
        return view('skpd.lpj.skpd_atau_unit.index');
    }

    public function loadSkpdAtauUnit()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhlpj_unit as a')
            ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd")
            ->where(['a.kd_skpd' => $kd_skpd, 'a.jenis' => '1'])
            ->orderBy('a.tgl_lpj')
            ->orderBy('a.no_lpj')
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            if ($row->status == '1' || $row->status == '2') {
                $btn = "";
            } else {
                $btn = '<a href="' . route("lpj.skpd_atau_unit.edit", Crypt::encrypt($row->no_lpj)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
                $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_lpj . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            }
            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_lpj . '\',\'' . $row->jenis . '\',\'' . $row->kd_skpd . '\');" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak LPJ" style="margin-right:4px"><i class="uil-print"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahSkpdAtauUnit()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
        ];

        return view('skpd.lpj.skpd_atau_unit.create')->with($data);
    }

    public function detailSkpdAtauUnit(Request $request)
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

    public function simpanSkpdAtauUnit(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $no_lpj = $data['no_lpj'] . "/LPJ/UPGU/" . $data['kd_skpd'] . "/" . tahun_anggaran();

            $cek_lpj = DB::table('trhlpj_unit')->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])->count();
            if ($cek_lpj > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhlpj_unit')
                ->insert([
                    'no_lpj' => $no_lpj,
                    'tgl_lpj' => $data['tgl_lpj'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keterangan' => $data['keterangan'],
                    'tgl_awal' => $data['tgl_awal'],
                    'tgl_akhir' => $data['tgl_akhir'],
                    'status' => '0',
                    'jenis' => '1',
                ]);

            DB::table('trlpj')
                ->where(['no_lpj_unit' => $no_lpj, 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            if (isset($data['detail_lpj'])) {
                DB::table('trlpj')
                    ->insert(array_map(function ($value) use ($no_lpj, $data) {
                        return [
                            'no_lpj' => '',
                            'kd_skpd' => $value['kd_skpd'],
                            'no_bukti' => $value['no_bukti'],
                            'tgl_lpj' => $data['tgl_lpj'],
                            'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                            'kd_rek6' => $value['kdrek6'],
                            'nm_rek6' => $value['nmrek6'],
                            'nilai' => $value['nilai'],
                            'kd_bp_skpd' => '',
                            'no_lpj_unit' => $no_lpj,
                        ];
                    }, $data['detail_lpj']));
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

    public function editSkpdAtauUnit($no_lpj)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_lpj = Crypt::decrypt($no_lpj);
        $arr = explode("/", $no_lpj);

        $data = [
            'nomor' => $arr[0],
            'lpj' => DB::table('trhlpj_unit as a')
                ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd")
                ->where(['a.kd_skpd' => $kd_skpd, 'a.jenis' => '1', 'a.no_lpj' => $no_lpj])
                ->first(),
            'detail_lpj' => DB::table('trhlpj_unit as a')
                ->join('trlpj as b', function ($join) {
                    $join->on('a.no_lpj', '=', 'b.no_lpj_unit');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('b.*')
                ->where(['a.no_lpj' => $no_lpj, 'a.kd_skpd' => $kd_skpd, 'a.jenis' => '1'])
                ->get(),
            'total_detail' => DB::table('trhlpj_unit as a')
                ->join('trlpj as b', function ($join) {
                    $join->on('a.no_lpj', '=', 'b.no_lpj_unit');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("SUM(b.nilai) as nilai")
                ->where(['a.no_lpj' => $no_lpj, 'a.kd_skpd' => $kd_skpd, 'a.jenis' => '1'])
                ->first(),
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
        ];

        return view('skpd.lpj.skpd_atau_unit.edit')->with($data);
    }

    public function updateSkpdAtauUnit(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $no_lpj = $data['no_lpj'] . "/LPJ/UPGU/" . $data['kd_skpd'] . "/" . tahun_anggaran();

            $cek_terima = DB::table('trhlpj_unit')->where(['no_lpj' => $no_lpj, 'kd_skpd' => $data['kd_skpd']])->count();
            if ($cek_terima > 0 && $no_lpj != $data['no_lpj_simpan']) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhlpj_unit')
                ->where(['no_lpj' => $data['no_lpj_simpan'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            DB::table('trhlpj_unit')
                ->insert([
                    'no_lpj' => $no_lpj,
                    'tgl_lpj' => $data['tgl_lpj'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keterangan' => $data['keterangan'],
                    'tgl_awal' => $data['tgl_awal'],
                    'tgl_akhir' => $data['tgl_akhir'],
                    'status' => '0',
                    'jenis' => '1',
                ]);

            DB::table('trlpj')
                ->where(['no_lpj_unit' => $data['no_lpj_simpan'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            if (isset($data['detail_lpj'])) {
                DB::table('trlpj')
                    ->insert(array_map(function ($value) use ($no_lpj, $data) {
                        return [
                            'no_lpj' => '',
                            'kd_skpd' => $value['kd_skpd'],
                            'no_bukti' => $value['no_bukti'],
                            'tgl_lpj' => $data['tgl_lpj'],
                            'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                            'kd_rek6' => $value['kdrek6'],
                            'nm_rek6' => $value['nmrek6'],
                            'nilai' => $value['nilai'],
                            'kd_bp_skpd' => '',
                            'no_lpj_unit' => $no_lpj,
                        ];
                    }, $data['detail_lpj']));
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

    public function hapusSkpdAtauUnit(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trlpj')
                ->where([
                    'no_lpj_unit' => $no_lpj,
                    'kd_skpd' => $kd_skpd
                ])
                ->delete();

            DB::table('trhlpj_unit')
                ->where([
                    'no_lpj' => $no_lpj,
                    'kd_skpd' => $kd_skpd,
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

    // VALIDASI LPJ UP/GU UNIT
    public function indexValidasiLpj()
    {
        return view('skpd.lpj.validasi_lpj.index');
    }

    public function loadValidasiLpj()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhlpj_unit as a')
            ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd")
            ->where(['a.jenis' => '1'])
            ->whereRaw("left(kd_skpd,17)=left(?,17)", [$kd_skpd])
            ->orderBy('a.tgl_lpj')
            ->orderBy('a.no_lpj')
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            if ($row->status == '2') {
                $btn = "";
            } else {
                $btn = '<a href="' . route("lpj.validasi.edit", ['no_lpj' => Crypt::encrypt($row->no_lpj), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            }
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function editValidasiLpj($no_lpj, $kd_skpd)
    {
        $no_lpj = Crypt::decrypt($no_lpj);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'lpj' => DB::table('trhlpj_unit as a')
                ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd")
                ->where(['a.jenis' => '1', 'a.no_lpj' => $no_lpj, 'a.kd_skpd' => $kd_skpd])
                ->first(),
            'detail_lpj' => DB::table('trhlpj_unit as a')
                ->join('trlpj as b', function ($join) {
                    $join->on('a.no_lpj', '=', 'b.no_lpj_unit');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('b.*')
                ->where(['a.no_lpj' => $no_lpj, 'a.kd_skpd' => $kd_skpd, 'a.jenis' => '1'])
                ->get(),
            'total_detail' => DB::table('trhlpj_unit as a')
                ->join('trlpj as b', function ($join) {
                    $join->on('a.no_lpj', '=', 'b.no_lpj_unit');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("SUM(b.nilai) as nilai")
                ->where(['a.no_lpj' => $no_lpj, 'a.kd_skpd' => $kd_skpd, 'a.jenis' => '1'])
                ->first(),
        ];

        return view('skpd.lpj.validasi_lpj.edit')->with($data);
    }

    public function setujuValidasiLpj(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhlpj_unit')
                ->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
                ->update([
                    'status' => '1',
                    'status_validasi' => '1'
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

    public function batalSetujuValidasiLpj(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            $cek_global = DB::table('trlpj')
                ->where(['no_lpj_unit' => $no_lpj, 'kd_skpd' => $kd_skpd])
                ->where(function ($query) {
                    $query->where('no_lpj', '<>', '')->orWhere('kd_bp_skpd', '<>', '');
                })
                ->count();

            if ($cek_global > 0) {
                DB::rollBack();
                return response()->json([
                    'message' => '2'
                ]);
            }
            DB::table('trhlpj_unit')
                ->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
                ->update([
                    'status' => '0',
                    'status_validasi' => '0'
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
}
