<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class SP2BPController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'ttd1' => DB::table('ms_ttd')
                ->select('nip', 'nama')
                ->where(['kd_skpd' => $kd_skpd])
                ->whereIn('kode', ['PA', 'BUD'])
                ->get(),
        ];

        return view('bud.sp2bp_blud.index')->with($data);
    }

    public function load()
    {
        $data = DB::select("SELECT a.*,isnull(b.no_sp2bp,0)no_sp2bps,b.no_sp2bp,b.tgl_sp2bp
                ,(select DISTINCT nm_skpd from ms_skpd where kd_skpd=a.kd_skpd)as nm_skpd
                 FROM trhsp3b_blud a
                 LEFT JOIN trsp2bp b on a.no_sp3b=b.no_sp3bp and a.kd_skpd=b.kd_skpd
                ORDER BY a.tgl_sp3b,a.no_sp3b");

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("sp2bp_blud.edit", ['no_sp3b' => Crypt::encrypt($row->no_sp3b), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';

            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_sp2bp . '\',\'' . $row->no_sp3b . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';

            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_sp2bp . '\',\'' . $row->kd_skpd . '\');" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak LPJ" style="margin-right:4px"><i class="uil-print"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function edit($no_sp3b, $kd_skpd)
    {
        $no_sp3b = Crypt::decrypt($no_sp3b);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'sp2bp' => collect(DB::select("SELECT a.*,isnull(b.no_sp2bp,0)no_sp2bps,b.no_sp2bp,b.tgl_sp2bp
                ,(select DISTINCT nm_skpd from ms_skpd where kd_skpd=a.kd_skpd)as nm_skpd
                 FROM trhsp3b_blud a
                 LEFT JOIN trsp2bp b on a.no_sp3b=b.no_sp3bp and a.kd_skpd=b.kd_skpd WHERE a.no_sp3b=? AND a.kd_skpd=?
                ORDER BY a.tgl_sp3b,a.no_sp3b", [$no_sp3b, $kd_skpd]))->first(),
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'daftar_kegiatan' => DB::select("SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan where a.kd_skpd=? AND left(a.kd_sub_kegiatan,9) in ('1.01.02.1','4.01.04.1') and status_sub_kegiatan='1' GROUP BY a.kd_sub_kegiatan,b.nm_sub_kegiatan order by a.kd_sub_kegiatan", [$kd_skpd]),
            'detail_sp2bp' => DB::select("SELECT (select DISTINCT d.tgl_bukti from trhtransout_blud d left join trdtransout_blud c on c.no_bukti=d.no_bukti and c.kd_skpd=d.kd_skpd where c.no_sp2d=a.no_sp3b and c.kd_skpd=a.kd_skpd and c.kd_sub_kegiatan=a.kd_sub_kegiatan and c.kd_rek6=a.kd_rek6) as tgl_bukti,
            (select DISTINCT d.no_bukti from trhtransout_blud d left join trdtransout_blud c on c.no_bukti=d.no_bukti and c.kd_skpd=d.kd_skpd where c.no_sp2d=a.no_sp3b and c.kd_skpd=a.kd_skpd and c.kd_sub_kegiatan=a.kd_sub_kegiatan and c.kd_rek6=a.kd_rek6) as no_bukti,
            a.kd_skpd, a.no_sp3b,a.kd_sub_kegiatan,(SELECT nm_sub_kegiatan FROM ms_sub_kegiatan WHERE a.kd_sub_kegiatan=kd_sub_kegiatan) as nm_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.nilai,b.tgl_awal,b.tgl_akhir FROM trsp3b_blud a INNER JOIN trhsp3b_blud b ON a.no_sp3b=b.no_sp3b AND a.kd_skpd=b.kd_skpd WHERE a.no_sp3b=? AND a.kd_skpd=? order by tgl_bukti", [$no_sp3b, $kd_skpd])
        ];

        return view('bud.sp2bp_blud.show')->with($data);
    }

    public function simpan(Request $request)
    {
        $data = $request->data;

        DB::beginTransaction();
        try {
            $cek = DB::table('trsp2bp')
                ->where(['no_sp2bp' => $data['no_sp2bp'], 'kd_skpd' => $data['kd_skpd']])
                ->count();

            if ($cek > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trsp2bp')
                ->where(['no_sp2bp' => $data['no_sp2bp'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            DB::table('trsp2bp')
                ->insert([
                    'no_sp2bp' => $data['no_sp2bp'],
                    'tgl_sp2bp' => $data['tgl_sp2bp'],
                    'no_sp3bp' => $data['no_sp3b'],
                    'tgl_sp3bp' => $data['tgl_sp3b'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'username' => Auth::user()->nama,
                    'last_update' =>  date('Y-m-d H:i:s')
                ]);

            DB::table('trhsp3b_blud')
                ->where(['no_sp3b' => $data['no_sp3b']])
                ->update([
                    'status_sp2bp' => '1'
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

    public function hapus(Request $request)
    {
        $no_sp2bp = $request->no_sp2bp;
        $no_sp3b = $request->no_sp3b;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trsp2bp')
                ->where(['no_sp2bp' => $no_sp2bp, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trhsp3b_blud')
                ->where(['no_sp3b' => $no_sp3b, 'kd_skpd' => $kd_skpd])
                ->update([
                    'status_sp2bp' => '0'
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

    public function cetak(Request $request)
    {
        $no_sp2bp = $request->no_sp2bp;
        $kd_skpd = $request->kd_skpd;
        $skpd = Auth::user()->kd_skpd;
        $bud = $request->bud;
        $jenis_print = $request->jenis_print;
        $atas = $request->atas;
        $bawah = $request->bawah;
        $kiri = $request->kiri;
        $kanan = $request->kanan;

        $sp2bp = collect(DB::select("SELECT tgl_sp2bp,no_sp2bp,no_sp3b,tgl_sp3b from trhsp3b_blud a inner join trsp2bp b on a.no_sp3b=b.no_sp3bp and a.kd_skpd=b.kd_skpd where no_sp2bp=?", [$no_sp2bp]))
            ->first();

        $tgl_sp2bp = collect(DB::select("SELECT tgl_sp2bp,no_sp2bp,no_sp3b,tgl_sp3b from trhsp3b_blud a inner join trsp2bp b on a.no_sp3b=b.no_sp3bp and a.kd_skpd=b.kd_skpd where no_sp2bp=?", [$no_sp2bp]))
            ->first()
            ->tgl_sp2bp;

        $saldo_awal = collect(DB::select("SELECT a.kd_skpd,a.nm_skpd,kd_rek6, ISNULL(SUM(b.nilai),0) as rupiah
                                                        from trhtransout_blud a inner join
                                                        trdtransout_blud b on a.no_sp2d=b.no_sp2d
                                                        and a.kd_skpd=b.kd_skpd
                                                        Left JOIN trsp2bp d on a.kd_skpd=d.kd_skpd and a.no_sp2d=d.no_sp3bp
                                                        where a.kd_skpd=?
                                                             and d.tgl_sp2bp<?
                                                        group by a.kd_skpd,a.nm_skpd,kd_rek6", [$kd_skpd, $tgl_sp2bp]))
            ->first();

        $nilai = collect(DB::select("SELECT SUM(sal_awal) as sal_awal, SUM(pendapatan) as pendapatan, SUM(belanja) as belanja FROM (
                SELECT sum(sal_awal) as sal_awal,
                                    case when left(kd_rek6,1)='4' then isnull(sum(rupiah),0) else 0 end as pendapatan,
                                    case when left(kd_rek6,1)='5' then isnull(sum(rupiah),0) else 0 end as belanja
                            from (
                            SELECT a.kd_skpd,a.nm_skpd,kd_rek6,isnull(c.sal_awal,0)as sal_awal, ISNULL(SUM(b.nilai),0) as rupiah
                                                        from trhtransout_blud a inner join
                                                        trdtransout_blud b on a.no_sp2d=b.no_sp2d
                                                        and a.kd_skpd=b.kd_skpd
                                                        Left JOIN ms_saldo_awal_blud c on a.kd_skpd=c.kd_skpd
                                                        Left JOIN trsp2bp d on a.kd_skpd=d.kd_skpd and a.no_sp2d=d.no_sp3bp
                                                        where a.kd_skpd=?
                                                             and d.no_sp2bp=?
                                                        group by a.kd_skpd,a.nm_skpd,kd_rek6,c.sal_awal
                            ) x group by left(kd_rek6,1)
            )Z", [$kd_skpd, $no_sp2bp]))
            ->first();

        $pembiayaan = collect(DB::select("SELECT sum(sal_awal) as sal_awal,
                                    case when left(kd_rek6,2)='61' then isnull(sum(rupiah),0) else 0 end as t_pembiayaan,
                                    case when left(kd_rek6,2)='62' then isnull(sum(rupiah),0) else 0 end as k_pembiayaan
                            from (
                            SELECT a.kd_skpd,a.nm_skpd,kd_rek6,isnull(c.sal_awal,0)as sal_awal, ISNULL(SUM(b.nilai),0) as rupiah
                                                        from trhtransout_blud a inner join
                                                        trdtransout_blud b on a.no_sp2d=b.no_sp2d
                                                        and a.kd_skpd=b.kd_skpd
                                                        Left JOIN ms_saldo_awal_blud c on a.kd_skpd=c.kd_skpd
                                                        Left JOIN trsp2bp d on a.kd_skpd=d.kd_skpd and a.no_sp2d=d.no_sp3bp
                                                        where a.kd_skpd=?
                                                             and d.no_sp2bp=?
                                                        group by a.kd_skpd,a.nm_skpd,kd_rek6,c.sal_awal
                            ) x group by left(kd_rek6,2)", [$kd_skpd, $no_sp2bp]))->first();

        $data = [
            'header' => DB::table('config_app')
                ->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')
                ->first(),
            'skpd' => DB::table('ms_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'sp2bp' => $sp2bp,
            'bud' => DB::table('ms_ttd')->where(['nip' => $bud, 'kd_skpd' => $skpd])->whereIn('kode', ['PA', 'BUD'])->first(),
            'saldo_awal' => isset($saldo_awal) ? $saldo_awal->rupiah : 0,
            // 'belanja_barang' => isset($belanja_barang) ? $belanja_barang->nilai : 0,
            // 'belanja_modal' => isset($belanja_modal) ? $belanja_modal->nilai : 0,
            // 'kembali' => isset($kembali) ? $kembali->nilai : 0,
            'nilai' => $nilai,
            'pembiayaan' => $pembiayaan,
            'daerah' => DB::table('sclient')->select('tgl_rka', 'provinsi', 'kab_kota', 'daerah', 'thn_ang', 'nogub_susun', 'nogub_p1', 'nogub_p2', 'nogub_p3', 'nogub_p4', 'nogub_p5', 'nogub_perubahan', 'nogub_perubahan2', 'nogub_perubahan3', 'nogub_perubahan4', 'nogub_perubahan5')->where('kd_skpd', $kd_skpd)->first()
        ];


        $view = view('bud.sp2bp_blud.cetak.cetakan')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-top', $atas)
                ->setOption('margin-left', $kiri)
                ->setOption('margin-right', $kanan)
                ->setOption('margin-bottom', $bawah);
            return $pdf->stream('laporan.pdf');
        } else {
            return $view;
        }
    }
}
