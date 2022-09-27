<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PencairanSp2dController extends Controller
{
    public function index()
    {
        $data = [
            'cair_sp2d' => DB::table('trhsp2d as a')->join('trduji as b', 'a.no_sp2d', '=', 'b.no_sp2d')->select('a.no_sp2d', 'tgl_sp2d', 'no_spm', 'tgl_spm', 'no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'npwp', 'no_kas', 'no_kas_bud', 'tgl_kas', 'tgl_kas_bud', 'nocek', 'status_bud', 'jenis_beban', 'no_spd', 'no_uji')->orderBy('a.no_sp2d')->orderBy('kd_skpd')->get()
        ];
        return view('penatausahaan.pengeluaran.pencairan_sp2d.index')->with($data);
    }

    public function tampilCair($no_sp2d)
    {
        $sp2d = DB::table('trhsp2d as a')->join('trduji as b', 'a.no_sp2d', '=', 'b.no_sp2d')->select('a.no_sp2d', 'tgl_sp2d', 'no_spm', 'tgl_spm', 'no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'npwp', 'no_kas', 'no_kas_bud', 'tgl_kas', 'tgl_kas_bud', 'nocek', 'status_bud', 'jenis_beban', 'no_spd', 'no_uji', 'a.nilai', 'a.tgl_terima')->orderBy('a.no_sp2d')->orderBy('kd_skpd')->where(['a.no_sp2d' => $no_sp2d])->first();

        $urut1 = DB::table('trhsp2d')->select('no_kas_bud as nomor', DB::raw("'Pencairan SP2D' as ket"), 'kd_skpd')->where(DB::raw("isnumeric(no_kas_bud)"), '1')->where(['status_bud' => '1']);
        $urut = DB::table(DB::raw("({$urut1->toSql()}) as sub"))
            ->select(DB::raw("case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor"))
            ->mergeBindings($urut1)
            ->first();
        $data = [
            'sp2d' => $sp2d,
            'total_spm' => DB::table('trdspp')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $sp2d->no_spp])->first(),
            'total_potongan' => DB::table('trspmpot')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spm' => $sp2d->no_spm])->first(),
            'urut' => $urut->nomor
        ];
        // return $data['sp2d'];
        return view('penatausahaan.pengeluaran.pencairan_sp2d.show')->with($data);
    }

    public function loadRincianSpm(Request $request)
    {
        $no_spp = $request->no_spp;

        $data = DB::table('trdspp')->select('kd_sub_kegiatan', 'nm_sub_kegiatan', 'kd_rek6', 'nm_rek6', 'nilai', 'sisa')->where(['no_spp' => $no_spp])->orderBy('kd_sub_kegiatan')->orderBy('kd_rek6')->get();

        return DataTables::of($data)->addIndexColumn()->make(true);;
        return view('penatausahaan.pengeluaran.sp2d.create');
    }

    public function loadRincianPotongan(Request $request)
    {
        $no_spm = $request->no_spm;

        $data = DB::table('trspmpot')->select('kd_rek6', 'nm_rek6', 'nilai', 'pot')->where(['no_spm' => $no_spm])->orderBy('kd_rek6')->get();

        return DataTables::of($data)->addIndexColumn()->make(true);;
        return view('penatausahaan.pengeluaran.sp2d.create');
    }

    public function cekSimpan(Request $request)
    {
        $no_kas = $request->no_kas;
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::table('trhsp2d')->where(['no_kas_bud' => $no_kas, 'kd_skpd' => $kd_skpd])->count();
        return response()->json($data);
    }

    public function simpanCair(Request $request)
    {
        $no_kas = $request->no_kas;
        $tgl_cair = $request->tgl_cair;
        $nilai = $request->nilai;
        $no_sp2d = $request->no_sp2d;
        $no_advice = $request->no_advice;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $skpd = DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first();

            DB::table('trhsp2d')->where(['no_sp2d' => $no_sp2d])->update([
                'status_bud' => '1',
                'no_kas_bud' => $no_kas,
                'tgl_kas_bud' => $tgl_cair,
                'no_advice' => $no_advice,
            ]);

            DB::table('trhju_pkd')->insert([
                'no_voucher' => $no_kas,
                'tgl_voucher' => $tgl_cair,
                'ket' => $no_sp2d,
                'username' => Auth::user()->nama,
                'tgl_update' => '',
                'kd_skpd' => $kd_skpd,
                'nm_skpd' => $skpd->nm_skpd,
                'kd_unit' => $kd_skpd,
                'total_d' => $nilai,
                'total_k' => $nilai,
                'tabel' => '0'
            ]);

            // $data_tagih = DB::table('trdspp as a')->leftJoin('trhspp as b', 'a.no_spp', '=', 'b.no_spp')->leftJoin('trhspm as c', 'c.no_spp', '=', 'b.no_spp')->leftJoin('trhsp2d as d', 'd.no_spm', '=', 'c.no_spm')->where(['d.no_sp2d' => $no_sp2d])->select('a.no_spp', 'a.kd_skpd', 'a.kd_sub_kegiatan', 'a.kd_rek5', 'a.nilai', 'b.bulan', 'c.no_spm', 'd.no_sp2d', 'b.sts_tagih')->get();
            // $jumlah = 0;
            // foreach ($data_tagih as $tagih) {
            //     $jumlah += $tagih['nilai'];
            // }

            // DB::table('trdju_pkd')->insert([
            //     'no_voucher' => $no_kas,
            //     'kd_rek6' => '1180101',
            //     'nm_rek6' => 'RK SKPD',
            //     'debet' => $jumlah,
            //     'kredit' => '0',
            //     'rk' => 'D',
            //     'urut' => '1',
            //     'pos' => '1',
            //     'kd_unit' => $kd_skpd
            // ]);
            // DB::table('trdju_pkd')->insert([
            //     'no_voucher' => $no_kas,
            //     'kd_rek6' => '1110101',
            //     'nm_rek6' => 'KAS DI KAS DAERAH',
            //     'debet' => '0',
            //     'kredit' => $jumlah,
            //     'rk' => 'K',
            //     'urut' => '2',
            //     'pos' => '1',
            //     'kd_unit' => $kd_skpd
            // ]);
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
