<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UploadCmsController extends Controller
{
    public function index()
    {
        $data = [
            'sisa_bank' => sisa_bank()
        ];

        return view('skpd.upload_cms.index')->with($data);
    }

    public function loadUpload()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $join1 = DB::table('trdtransout_transfercms as a')->select('a.no_voucher', 'a.kd_skpd', DB::raw("SUM(a.nilai) as bersih"))->where(['a.kd_skpd' => $kd_skpd])->groupBy('a.no_voucher', 'a.kd_skpd');
        $data = DB::table('trhtransout_cmsbank as a')->leftJoin('trdtransout_cmsbank as b', function ($join) {
            $join->on('a.no_voucher', '=', 'b.no_voucher');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->leftJoinSub($join1, 'c', function ($join) {
            $join->on('c.no_voucher', '=', 'a.no_voucher');
            $join->on('c.kd_skpd', '=', 'a.kd_skpd');
        })->select('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'b.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'c.bersih')->selectRaw("CASE WHEN a.jns_spp IN('4','6') THEN (SELECT SUM(x.nilai) as tot_pot FROM trspmpot x INNER JOIN trhsp2d b ON x.kd_skpd=b.kd_skpd AND x.no_spm=b.no_spm INNER JOIN trhtransout_cmsbank c ON b.kd_skpd=c.kd_skpd AND b.no_sp2d=c.no_sp2d WHERE c.kd_skpd=? AND c.no_sp2d=a.no_sp2d AND c.jns_spp IN('4','6')) ELSE 0 END as tot_pot", [$kd_skpd])->where(['a.kd_skpd' => $kd_skpd, 'status_upload' => '0'])->groupBy('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'b.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'c.bersih', 'a.jns_spp')->orderBy(DB::raw("CAST(a.no_voucher as int)"))->orderBy('a.kd_skpd')->get();
        return Datatables::of($data)->addIndexColumn()->make(true);
        return view('skpd.upload_cms.index');
    }

    public function rekeningTransaksi(Request $request)
    {
        $no_voucher = $request->nomor;
        $kd_skpd = $request->kd_skpd;

        $data = DB::table('trhtransout_cmsbank as a')->join('trdtransout_cmsbank as b', function ($join) {
            $join->on('a.no_voucher', '=', 'b.no_voucher');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.no_voucher' => $no_voucher, 'a.kd_skpd' => $kd_skpd])->orderBy('b.kd_sub_kegiatan')->orderBy('b.kd_rek6')->select('b.*', DB::raw("'0' as lalu"), DB::raw("'0' as sp2d"), DB::raw("'0' as anggaran"))->get();

        return DataTables::of($data)->addIndexColumn()->make(true);;
        return view('skpd.upload_cms.index');
    }

    public function rekeningPotongan(Request $request)
    {
        $no_voucher = $request->nomor;
        $kd_skpd = $request->kd_skpd;

        $data = DB::table('trdtrmpot_cmsbank as a')->join('trhtrmpot_cmsbank as b', function ($join) {
            $join->on('a.no_bukti', '=', 'b.no_bukti');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['b.no_voucher' => $no_voucher, 'b.kd_skpd' => $kd_skpd])->select('a.*')->get();

        return DataTables::of($data)->addIndexColumn()->make(true);;
        return view('skpd.upload_cms.index');
    }

    public function rekeningTujuan(Request $request)
    {
        $no_voucher = $request->nomor;
        $kd_skpd = $request->kd_skpd;

        $data = DB::table('trhtransout_cmsbank as a')->join('trdtransout_transfercms as b', function ($join) {
            $join->on('a.no_voucher', '=', 'b.no_voucher');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['b.no_voucher' => $no_voucher, 'b.kd_skpd' => $kd_skpd])->groupBy('b.no_voucher', 'b.tgl_voucher', 'b.rekening_awal', 'b.nm_rekening_tujuan', 'b.rekening_tujuan', 'b.bank_tujuan', 'b.kd_skpd', 'b.nilai')->select('b.no_voucher', 'b.tgl_voucher', 'b.rekening_awal', 'b.nm_rekening_tujuan', 'b.rekening_tujuan', 'b.bank_tujuan', 'b.kd_skpd', 'b.nilai', DB::raw("(SELECT SUM(nilai) FROM trdtransout_transfercms WHERE no_voucher=b.no_voucher AND kd_skpd=b.kd_skpd) as total"))->get();

        return DataTables::of($data)->addIndexColumn()->make(true);;
        return view('skpd.upload_cms.index');
    }
}
