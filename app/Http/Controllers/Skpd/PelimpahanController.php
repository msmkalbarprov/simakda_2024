<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Exception;

class PelimpahanController extends Controller
{
    // PELIMPAHAN UP DARI indexUp
    public function indexUp()
    {
        return view('skpd.setor_potongan.index');
    }

    public function loadDataUp()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhstrpot')->select('no_bukti', 'no_ntpn', 'tgl_bukti', 'no_terima', 'kd_skpd', 'no_sp2d', DB::raw("RTRIM(jns_spp) as jns_spp"), 'nm_skpd', 'nm_sub_kegiatan', 'kd_sub_kegiatan', 'nmrekan', 'pimpinan', 'alamat', 'npwp', 'ket', 'nilai', 'pay')->where(['kd_skpd' => $kd_skpd])->orderBy('no_bukti')->orderBy('kd_skpd')->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("skpd.setor_potongan.edit", $row->no_bukti) . '" class="btn btn-primary btn-sm" style="margin-right:4px"><i class="fa fa-eye"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapusPotongan(' . $row->no_bukti . ');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
        return view('skpd.setor_potongan.index');
    }

    public function tambahUp()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'daftar_terima' => DB::table('trhtrmpot')->where(['kd_skpd' => $kd_skpd, 'status' => '0'])->orderBy('no_bukti')->get(),
            'tahun_anggaran' => tahun_anggaran()
        ];

        return view('skpd.setor_potongan.create')->with($data);
    }

    public function simpanUp(Request $request)
    {
        $id_terima = $request->id_terima;
        $id_setor = $request->id_setor;
        $no_terima = $request->no_terima;
        $kd_rek6 = $request->kd_rek6;
        $nm_rek6 = $request->nm_rek6;
        $ntpn_validasi = $request->ntpn_validasi;
        $kd_skpd = $request->kd_skpd;
        $id_billing_validasi = $request->id_billing_validasi;

        DB::beginTransaction();
        try {
            DB::table('trdtrmpot')->where(['no_bukti' => $no_terima, 'kd_skpd' => $kd_skpd, 'kd_rek6' => $kd_rek6, 'id' => $id_terima])->update([
                'ntpn' => $ntpn_validasi,
                'ebilling' => $id_billing_validasi
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

    public function editUp($no_bukti)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $setor = DB::table('trhstrpot as a')->join('trdstrpot as b', function ($join) {
            $join->on('a.no_bukti', '=', 'b.no_bukti');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.no_bukti' => $no_bukti, 'a.kd_skpd' => $kd_skpd])->select('a.*')->first();
        $data = [
            'data_setor' => $setor,
            'tahun_anggaran' => tahun_anggaran(),
            'total_potongan' => DB::table('trdtrmpot')->select(DB::raw("sum(nilai) as nilai"))->where(['no_bukti' => $setor->no_terima, 'kd_skpd' => $kd_skpd])->first()
        ];

        return view('skpd.setor_potongan.edit')->with($data);
    }

    public function updateUp(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            // TRHSTRPOT
            DB::table('trhstrpot')->where(['kd_skpd' => $kd_skpd, 'no_bukti' => $data['no_bukti']])->update([
                'tgl_bukti' => $data['tgl_bukti'],
                'pay' => $data['pembayaran'],
            ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'no_bukti' => $data['no_bukti']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function hapusUp(Request $request)
    {
        $no_bukti = $request->no_bukti;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trdstrpot')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->delete();

            DB::table('trhstrpot')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->delete();

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
    // Pelimpahan UP Sampai hapusUp

    // PELIMPAHAN GU DARI indexGu
    public function indexGu()
    {
        return view('skpd.setor_potongan.index');
    }

    public function loadDataGu()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhstrpot')->select('no_bukti', 'no_ntpn', 'tgl_bukti', 'no_terima', 'kd_skpd', 'no_sp2d', DB::raw("RTRIM(jns_spp) as jns_spp"), 'nm_skpd', 'nm_sub_kegiatan', 'kd_sub_kegiatan', 'nmrekan', 'pimpinan', 'alamat', 'npwp', 'ket', 'nilai', 'pay')->where(['kd_skpd' => $kd_skpd])->orderBy('no_bukti')->orderBy('kd_skpd')->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("skpd.setor_potongan.edit", $row->no_bukti) . '" class="btn btn-primary btn-sm" style="margin-right:4px"><i class="fa fa-eye"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapusPotongan(' . $row->no_bukti . ');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
        return view('skpd.setor_potongan.index');
    }

    public function tambahGu()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'daftar_terima' => DB::table('trhtrmpot')->where(['kd_skpd' => $kd_skpd, 'status' => '0'])->orderBy('no_bukti')->get(),
            'tahun_anggaran' => tahun_anggaran()
        ];

        return view('skpd.setor_potongan.create')->with($data);
    }

    public function simpanGu(Request $request)
    {
        $id_terima = $request->id_terima;
        $id_setor = $request->id_setor;
        $no_terima = $request->no_terima;
        $kd_rek6 = $request->kd_rek6;
        $nm_rek6 = $request->nm_rek6;
        $ntpn_validasi = $request->ntpn_validasi;
        $kd_skpd = $request->kd_skpd;
        $id_billing_validasi = $request->id_billing_validasi;

        DB::beginTransaction();
        try {
            DB::table('trdtrmpot')->where(['no_bukti' => $no_terima, 'kd_skpd' => $kd_skpd, 'kd_rek6' => $kd_rek6, 'id' => $id_terima])->update([
                'ntpn' => $ntpn_validasi,
                'ebilling' => $id_billing_validasi
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

    public function editGu($no_bukti)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $setor = DB::table('trhstrpot as a')->join('trdstrpot as b', function ($join) {
            $join->on('a.no_bukti', '=', 'b.no_bukti');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.no_bukti' => $no_bukti, 'a.kd_skpd' => $kd_skpd])->select('a.*')->first();
        $data = [
            'data_setor' => $setor,
            'tahun_anggaran' => tahun_anggaran(),
            'total_potongan' => DB::table('trdtrmpot')->select(DB::raw("sum(nilai) as nilai"))->where(['no_bukti' => $setor->no_terima, 'kd_skpd' => $kd_skpd])->first()
        ];

        return view('skpd.setor_potongan.edit')->with($data);
    }

    public function updateGu(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            // TRHSTRPOT
            DB::table('trhstrpot')->where(['kd_skpd' => $kd_skpd, 'no_bukti' => $data['no_bukti']])->update([
                'tgl_bukti' => $data['tgl_bukti'],
                'pay' => $data['pembayaran'],
            ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'no_bukti' => $data['no_bukti']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function hapusGu(Request $request)
    {
        $no_bukti = $request->no_bukti;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trdstrpot')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->delete();

            DB::table('trhstrpot')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->delete();

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
    // Pelimpahan GU Sampai hapusGu

    // Upload UP/GU
    public function upload()
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

    public function draftUpload()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhupload_cmsbank as a')->where(['a.kd_skpd' => $kd_skpd])->orderBy(DB::raw("CAST(a.no_upload as int)"))->orderBy('a.kd_skpd')->get();
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

    public function tambahUpload()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $join1 = DB::table('trdtransout_transfercms as a')->select('a.no_voucher', 'a.kd_skpd', DB::raw("SUM(a.nilai) as bersih"))->where(['a.kd_skpd' => $kd_skpd])->groupBy('a.no_voucher', 'a.kd_skpd');
        $data = [
            'daftar_transaksi' => DB::table('trhtransout_cmsbank as a')->leftJoin('trdtransout_cmsbank as b', function ($join) {
                $join->on('a.no_voucher', '=', 'b.no_voucher');
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'b.kd_skpd'
                );
            })->leftJoinSub($join1, 'c', function ($join) {
                $join->on('c.no_voucher', '=', 'a.no_voucher');
                $join->on(
                    'c.kd_skpd',
                    '=',
                    'a.kd_skpd'
                );
            })->select('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'b.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'c.bersih')->selectRaw("CASE WHEN a.jns_spp IN('4','6') THEN (SELECT SUM(x.nilai) as tot_pot FROM trspmpot x INNER JOIN trhsp2d b ON x.kd_skpd=b.kd_skpd AND x.no_spm=b.no_spm INNER JOIN trhtransout_cmsbank c ON b.kd_skpd=c.kd_skpd AND b.no_sp2d=c.no_sp2d WHERE c.kd_skpd=? AND c.no_sp2d=a.no_sp2d AND c.jns_spp IN('4','6')) ELSE 0 END as tot_pot", [$kd_skpd])->where(['a.kd_skpd' => $kd_skpd, 'status_upload' => '0'])->groupBy('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'b.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'c.bersih', 'a.jns_spp')->orderBy(DB::raw("CAST(a.no_voucher as int)"))->orderBy('a.kd_skpd')->get(),
            'sisa_bank' => sisa_bank()
        ];

        return view('skpd.upload_cms.create')->with($data);
    }

    public function prosesUpload(Request $request)
    {
        $total_transaksi = $request->total_transaksi;
        $rincian_data = $request->rincian_data;
        $tanggal = date("Y-m-d");
        $kd_skpd = Auth::user()->kd_skpd;
        DB::beginTransaction();
        try {
            $nomor1 = DB::table('trhupload_cmsbank')->select('no_upload as nomor', DB::raw("'Urut Upload Pengeluaran cms' as ket"), 'kd_skpd')->where(['kd_skpd' => $kd_skpd]);
            $nomor2 = DB::table('trhupload_cmsbank_panjar')->select('no_upload as nomor', DB::raw("'Urut Upload Panjar Bank cms' as ket"), 'kd_skpd')->where(['kd_skpd' => $kd_skpd])->unionAll($nomor1);
            $nomor3 = DB::table('trhupload_sts_cmsbank')->select('no_upload as nomor', DB::raw("'Urut Upload Penerimaan cms' as ket"), 'kd_skpd')->where(['kd_skpd' => $kd_skpd])->unionAll($nomor2);
            $nomor = DB::table(DB::raw("({$nomor3->toSql()}) AS sub"))
                ->select(DB::raw("case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor"))
                ->mergeBindings($nomor3)
                ->first();

            $no_upload1 = DB::table('trdupload_cmsbank as a')->leftJoin('trhupload_cmsbank as b', function ($join) {
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'b.kd_skpd'
                );
                $join->on('a.no_upload', '=', 'b.no_upload');
            })->select('a.no_upload as nomor', 'b.tgl_upload as tanggal', DB::raw("'Urut Upload Pengeluaran cms' as ket"), 'a.kd_skpd')->where(['a.kd_skpd' => $kd_skpd, 'b.tgl_upload' => $tanggal]);
            $no_upload2 = DB::table('trdupload_cmsbank_panjar as a')->leftJoin('trhupload_cmsbank_panjar as b', function ($join) {
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'b.kd_skpd'
                );
                $join->on('a.no_upload', '=', 'b.no_upload');
            })->select('a.no_upload as nomor', 'b.tgl_upload as tanggal', DB::raw("'Urut Upload Panjar Bank cms' as ket"), 'a.kd_skpd')->where(['a.kd_skpd' => $kd_skpd, 'b.tgl_upload' => $tanggal])->unionAll($no_upload1);
            $no_upload3 = DB::table('trdupload_sts_cmsbank as a')->leftJoin('trhupload_sts_cmsbank as b', function ($join) {
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'b.kd_skpd'
                );
                $join->on('a.no_upload', '=', 'b.no_upload');
            })->select('a.no_upload as nomor', 'b.tgl_upload as tanggal', DB::raw("'Urut Upload Penerimaan cms' as ket"), 'a.kd_skpd')->where(['a.kd_skpd' => $kd_skpd, 'b.tgl_upload' => $tanggal])->unionAll($no_upload2);
            $no_upload = DB::table(DB::raw("({$no_upload3->toSql()}) AS sub"))
                ->select(DB::raw("case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor"))
                ->mergeBindings($no_upload3)
                ->first();
            if (strlen($no_upload->nomor == '1')) {
                $no_upload1 = "00" . $no_upload->nomor;
            } elseif (strlen($no_upload->nomor == '2')) {
                $no_upload1 = "0" . $no_upload->nomor;
            } elseif (strlen($no_upload->nomor == '3')) {
                $no_upload1 = $no_upload->nomor;
            }

            DB::table('trhupload_cmsbank')->where(['no_upload' => $nomor->nomor, 'kd_skpd' => $kd_skpd])->delete();
            DB::table('trdupload_cmsbank')->where(['no_upload' => $nomor->nomor, 'kd_skpd' => $kd_skpd])->delete();

            if (isset($rincian_data)) {
                DB::table('trdupload_cmsbank')->insert(array_map(function ($value) use ($nomor, $no_upload1) {
                    return [
                        'no_voucher' => $value['no_voucher'],
                        'tgl_voucher' => $value['tgl_voucher'],
                        'no_upload' => $nomor->nomor,
                        'rekening_awal' => $value['rekening_awal'],
                        'nm_rekening_tujuan' => $value['nm_rekening_tujuan'],
                        'rekening_tujuan' => $value['rekening_tujuan'],
                        'bank_tujuan' => $value['bank_tujuan'],
                        'ket_tujuan' => $value['ket_tujuan'],
                        'nilai' => $value['nilai_pengeluaran'],
                        'kd_skpd' => $value['kd_skpd'],
                        'kd_bp' => $value['kd_skpd'],
                        'status_upload' => '1',
                        'no_upload_tgl' => $no_upload1,
                    ];
                }, $rincian_data));
            }

            DB::table('trhupload_cmsbank')->insert([
                'no_upload' => $nomor->nomor,
                'tgl_upload' => $tanggal,
                'kd_skpd' => $kd_skpd,
                'total' => $total_transaksi,
                'no_upload_tgl' => $no_upload1,
            ]);

            $data1 = DB::table('trhupload_cmsbank as a')->leftJoin('trdupload_cmsbank as b', function ($join) {
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'b.kd_skpd'
                );
                $join->on('a.no_upload', '=', 'b.no_upload');
            })->where(['b.kd_bp' => $kd_skpd, 'a.no_upload' => $nomor->nomor])->select('a.no_upload', 'b.kd_skpd', 'a.tgl_upload', 'b.status_upload', 'b.no_voucher', 'b.kd_bp');

            $data = DB::table('trhtransout_cmsbank as c')->joinSub($data1, 'd', function ($join) {
                $join->on('c.no_voucher', '=', 'd.no_voucher');
                $join->on(
                    'c.kd_skpd',
                    '=',
                    'd.kd_skpd'
                );
            })->whereRaw('left(c.kd_skpd,17) = left(?,17)', $kd_skpd)->update([
                'c.status_upload' => '1',
                'c.tgl_upload' => date("Y-m-d")
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

    public function batalUpload(Request $request)
    {
        $no_upload = $request->no_upload;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            $data1 = DB::table('trhupload_cmsbank as a')->leftJoin('trdupload_cmsbank as b', function ($join) {
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'b.kd_skpd'
                );
                $join->on('a.no_upload', '=', 'b.no_upload');
            })->where(['b.kd_bp' => $kd_skpd, 'a.no_upload' => $no_upload])->select('a.no_upload', 'b.kd_skpd', 'a.tgl_upload', 'b.status_upload', 'b.no_voucher', 'b.kd_bp', DB::raw("SUM(b.nilai) as total"))->groupBy('a.no_upload', 'b.kd_skpd', 'a.tgl_upload', 'b.status_upload', 'b.no_voucher', 'b.kd_bp');

            $data = DB::table('trhtransout_cmsbank as c')->joinSub($data1, 'd', function ($join) {
                $join->on('c.no_voucher', '=', 'd.no_voucher');
                $join->on(
                    'c.kd_skpd',
                    '=',
                    'd.kd_skpd'
                );
            })->whereRaw('left(c.kd_skpd,17) = left(?,17)', $kd_skpd)->update([
                'c.status_upload' => '0',
                'c.tgl_upload' => ''
            ]);

            DB::table('trdupload_cmsbank')->where(['no_upload' => $no_upload, 'kd_skpd' => $kd_skpd])->delete();
            DB::table('trhupload_cmsbank')->where(['no_upload' => $no_upload, 'kd_skpd' => $kd_skpd])->delete();

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

    public function cetakCsvKalbar(Request $request)
    {
        $no_upload = $request->no_upload;
        $kd_skpd = Auth::user()->kd_skpd;

        $obskpd = DB::table('ms_skpd')->select('obskpd')->where(['kd_skpd' => $kd_skpd])->first();

        $query1 = DB::table('trhupload_cmsbank as a')->leftJoin('trdupload_cmsbank as b', function ($join) {
            $join->on('a.no_upload', '=', 'b.no_upload');
            $join->on('a.kd_skpd', '=', 'b.kd_bp');
        })->leftJoin('trdtransout_transfercms as c', function ($join) {
            $join->on('b.no_voucher', '=', 'c.no_voucher');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            $join->on(
                'b.tgl_voucher',
                '=',
                'c.tgl_voucher'
            );
        })->leftJoin('ms_rekening_bank_online as d', function ($join) {
            $join->on('b.kd_bp', '=', 'd.kd_skpd');
            $join->on(DB::raw("RTRIM(c.rekening_tujuan)"), '=', DB::raw("RTRIM(d.rekening)"));
        })->leftJoin('trdtransout_cmsbank as e', function ($join) {
            $join->on('b.kd_skpd', '=', 'e.kd_skpd');
            $join->on('b.no_voucher', '=', 'e.no_voucher');
        })->leftJoin('ms_bank_online as f', function ($join) {
            $join->on('d.kd_bank', '=', 'f.kd_bank');
            $join->on('d.bic', '=', 'f.bic');
        })->where(['a.kd_skpd' => $kd_skpd, 'a.no_upload' => $no_upload, 'f.bic' => 'PDKBIDJ1'])->select(
            'a.tgl_upload',
            'a.kd_skpd',
            DB::raw("(SELECT obskpd FROM ms_skpd WHERE kd_skpd=b.kd_skpd) as nm_skpd"),
            'b.rekening_awal',
            'c.nm_rekening_tujuan',
            'c.rekening_tujuan',
            'c.nilai',
            DB::raw("(REPLACE(b.ket_tujuan, '2022.', RIGHT(e.kd_sub_kegiatan,5)+ '/')) as ket_tujuan"),
            'b.no_upload_tgl'
        );

        $query = DB::table(DB::raw("({$query1->toSql()}) AS sub"))
            ->select('*')
            ->mergeBindings($query1)
            ->groupBy('tgl_upload', 'kd_skpd', 'nm_skpd', 'rekening_awal', 'nm_rekening_tujuan', 'rekening_tujuan', 'nilai', 'ket_tujuan', 'no_upload_tgl')
            ->get();

        foreach ($query as $data) {
            $tgl_upload = $data->tgl_upload;
            $no_upload_tgl = $data->no_upload_tgl;
            $nilai = strval($data->nilai);
            $nilai = str_replace('.00', '', $nilai);

            $result = $data->nm_skpd . ";" . str_replace(" ", "", rtrim($data->rekening_awal)) . ";" . rtrim($data->nm_rekening_tujuan) . ";" . str_replace(" ", "", rtrim($data->rekening_tujuan)) . ";" . $nilai . ";" . $data->ket_tujuan . "\n";

            $init_tgl = explode("-", $tgl_upload);
            $tglupl = $init_tgl[2] . $init_tgl[1] . $init_tgl[0];
            $filename = 'OB' . "_" . $obskpd->obskpd . "_" . $tglupl . "_" . $no_upload_tgl;

            echo $result;
        }
        header("Cache-Control: no-cache, no-store, must_revalidate");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachement; filename="' . $filename . '.csv"');
    }

    public function cetakCsvLuarKalbar(Request $request)
    {
        $no_upload = $request->no_upload;
        $kd_skpd = Auth::user()->kd_skpd;

        $obskpd = DB::table('ms_skpd')->select('obskpd')->where(['kd_skpd' => $kd_skpd])->first();

        $query1 = DB::table('trhupload_cmsbank as a')->leftJoin('trdupload_cmsbank as b', function ($join) {
            $join->on('a.no_upload', '=', 'b.no_upload');
            $join->on('a.kd_skpd', '=', 'b.kd_bp');
        })->leftJoin('trdtransout_transfercms as c', function ($join) {
            $join->on('b.no_voucher', '=', 'c.no_voucher');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            $join->on(
                'b.tgl_voucher',
                '=',
                'c.tgl_voucher'
            );
        })->leftJoin('ms_rekening_bank_online as d', function ($join) {
            $join->on('b.kd_bp', '=', 'd.kd_skpd');
            $join->on(DB::raw("RTRIM(c.rekening_tujuan)"), '=', DB::raw("RTRIM(d.rekening)"));
        })->leftJoin('trdtransout_cmsbank as e', function ($join) {
            $join->on('b.kd_skpd', '=', 'e.kd_skpd');
            $join->on('b.no_voucher', '=', 'e.no_voucher');
        })->leftJoin('ms_bank_online as f', function ($join) {
            $join->on('d.kd_bank', '=', 'f.kd_bank');
            $join->on('d.bic', '=', 'f.bic');
        })->where(['a.kd_skpd' => $kd_skpd, 'a.no_upload' => $no_upload])->where('f.bic', '<>', 'PDKBIDJ1')->select(
            'a.tgl_upload',
            'a.kd_skpd',
            DB::raw("(SELECT obskpd FROM ms_skpd WHERE kd_skpd=b.kd_skpd) as nm_skpd"),
            'b.rekening_awal',
            'c.nm_rekening_tujuan',
            'c.rekening_tujuan',
            'c.nilai',
            DB::raw("SUBSTRING(b.ket_tujuan + '/' + RIGHT(e.kd_sub_kegiatan,5) + '/' + c.nm_rekening_tujuan,0,30) as ket_tujuan"),
            'b.no_upload_tgl',
            'f.bic'
        );

        $query = DB::table(DB::raw("({$query1->toSql()}) AS sub"))
            ->select('*')
            ->mergeBindings($query1)
            ->groupBy('tgl_upload', 'kd_skpd', 'nm_skpd', 'rekening_awal', 'nm_rekening_tujuan', 'rekening_tujuan', 'nilai', 'ket_tujuan', 'no_upload_tgl', 'bic')
            ->get();

        foreach ($query as $data) {
            $tgl_upload = $data->tgl_upload;
            $no_upload_tgl = $data->no_upload_tgl;
            $nilai = strval($data->nilai);
            $nilai = str_replace('.00', '', $nilai);
            $init_tgl = explode("-", $tgl_upload);
            $tglupl = $init_tgl[2] . $init_tgl[1] . $init_tgl[0];
            $filename = 'SKN' . "_" . $obskpd->obskpd . "_" . $tglupl . "_" . $no_upload_tgl;

            $result = $data->nm_skpd . ";" . str_replace(" ", "", rtrim($data->rekening_awal)) . ";" . rtrim($data->nm_rekening_tujuan) . ";" . rtrim($data->bic) . ";" . str_replace(" ", "", rtrim($data->rekening_tujuan)) . ";" . $nilai . ";" .  'IDR' . ";" . $data->ket_tujuan . ";" . "\n";

            echo $result;
        }
        header("Cache-Control: no-cache, no-store, must_revalidate");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachement; filename="' . $filename . '.csv"');
    }

    public function dataUpload(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_upload = $request->no_upload;

        $join1 = DB::table('trdtransout_transfercms as a')->select('a.no_voucher', 'a.kd_skpd', DB::raw("SUM(a.nilai) as bersih"))->where(['a.kd_skpd' => $kd_skpd])->groupBy('a.no_voucher', 'a.kd_skpd');

        $data = DB::table('trhupload_cmsbank as a')->leftJoin('trdupload_cmsbank as b', function ($join) {
            $join->on('a.kd_skpd', '=', 'b.kd_bp');
            $join->on('a.no_upload', '=', 'b.no_upload');
        })->join('trhtransout_cmsbank as d', function ($join) {
            $join->on('b.no_voucher', '=', 'd.no_voucher');
            $join->on('b.kd_skpd', '=', 'd.kd_skpd');
        })->leftJoinSub($join1, 'c', function ($join) {
            $join->on('c.no_voucher', '=', 'b.no_voucher');
            $join->on('c.kd_skpd', '=', 'b.kd_skpd');
        })->select('d.ket', 'b.kd_skpd', 'b.no_voucher', 'b.tgl_voucher', 'a.no_upload', 'a.tgl_upload', 'a.total', 'b.nilai', 'b.status_upload', 'b.rekening_awal', 'b.nm_rekening_tujuan', 'b.rekening_tujuan', 'b.bank_tujuan', 'b.ket_tujuan', 'c.bersih', DB::raw("b.nilai - c.bersih as pot"))->where(['a.kd_skpd' => $kd_skpd, 'a.no_upload' => $no_upload])->groupBy('b.kd_skpd', 'b.no_voucher', 'b.tgl_voucher', 'a.no_upload', 'a.tgl_upload', 'a.total', 'b.nilai', 'b.status_upload', 'b.rekening_awal', 'b.nm_rekening_tujuan', 'b.rekening_tujuan', 'b.bank_tujuan', 'b.ket_tujuan', 'c.bersih', 'd.ket')->orderBy(DB::raw("CAST(a.no_upload as int)"))->orderBy('b.kd_skpd')->get();
        return Datatables::of($data)->addIndexColumn()->make(true);
        return view('skpd.upload_cms.index');
    }

    // VALIDASI UP/GU
    public function validasi()
    {
        $data = [
            'sisa_bank' => sisa_bank()
        ];
        return view('skpd.validasi_cms.index')->with($data);
    }

    public function loadValidasi(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhtransout_cmsbank as a')->leftJoin('trdtransout_cmsbank as b', function ($join) {
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            $join->on('a.no_voucher', '=', 'b.no_voucher');
        })->leftJoin('trdupload_cmsbank as c', function ($join) {
            $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            $join->on('a.no_voucher', '=', 'c.no_voucher');
        })->select('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'c.no_upload')->selectRaw("(CASE WHEN a.jns_spp IN ( '4', '6' ) THEN(SELECT SUM( x.nilai ) tot_pot FROM trspmpot x INNER JOIN trhsp2d b ON x.kd_skpd= b.kd_skpd AND x.no_spm= b.no_spm INNER JOIN trhtransout_cmsbank c ON b.kd_skpd= c.kd_skpd AND b.no_sp2d= c.no_sp2d WHERE c.kd_skpd=? AND c.no_sp2d= a.no_sp2d AND c.jns_spp IN ( '4', '6' )) ELSE 0 END) as tot_pot", [$kd_skpd])->where(['a.kd_skpd' => $kd_skpd, 'a.status_upload' => '1', 'status_validasi' => '0'])->groupBy('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'c.no_upload', 'a.jns_spp')->orderBy('a.kd_skpd')->orderBy(DB::raw("CAST(a.no_voucher as int)"))->get();
        return Datatables::of($data)->addIndexColumn()->make(true);
        return view('skpd.validasi_cms.index');
    }

    public function tambahValidasi()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'daftar_transaksi' => DB::table('trhtransout_cmsbank as a')->leftJoin('trdtransout_cmsbank as b', function ($join) {
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'b.kd_skpd'
                );
                $join->on('a.no_voucher', '=', 'b.no_voucher');
            })->leftJoin('trdupload_cmsbank as c', function ($join) {
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'c.kd_skpd'
                );
                $join->on('a.no_voucher', '=', 'c.no_voucher');
            })->select('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'c.no_upload')->selectRaw("(CASE WHEN a.jns_spp IN ( '4', '6' ) THEN(SELECT SUM( x.nilai ) tot_pot FROM trspmpot x INNER JOIN trhsp2d b ON x.kd_skpd= b.kd_skpd AND x.no_spm= b.no_spm INNER JOIN trhtransout_cmsbank c ON b.kd_skpd= c.kd_skpd AND b.no_sp2d= c.no_sp2d WHERE c.kd_skpd=? AND c.no_sp2d= a.no_sp2d AND c.jns_spp IN ( '4', '6' )) ELSE 0 END) as tot_pot", [$kd_skpd])->where(['a.kd_skpd' => $kd_skpd, 'a.status_upload' => '1', 'status_validasi' => '0'])->groupBy('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'c.no_upload', 'a.jns_spp')->orderBy('a.kd_skpd')->orderBy(DB::raw("CAST(a.no_voucher as int)"))->get(),
            'sisa_bank' => sisa_bank()
        ];

        return view('skpd.validasi_cms.create')->with($data);
    }

    public function prosesValidasi(Request $request)
    {
        $rincian_data = $request->rincian_data;
        $tanggal_validasi = $request->tanggal_validasi;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $nomor1 = DB::table('trvalidasi_cmsbank')->select('no_validasi as nomor', DB::raw("'Urut Validasi cms' as ket"), 'kd_skpd')->where(['kd_skpd' => $kd_skpd]);
            $nomor2 = DB::table('trvalidasi_cmsbank_panjar')->select('no_validasi as nomor', DB::raw("'Urut Validasi cms' as ket"), 'kd_skpd')->where(['kd_skpd' => $kd_skpd])->unionAll($nomor1);
            $nomor = DB::table(DB::raw("({$nomor2->toSql()}) AS sub"))
                ->select(DB::raw("case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor"))
                ->mergeBindings($nomor2)
                ->first();

            $no_validasi = $nomor->nomor;
            $no_bku = no_urut($kd_skpd);
            $bku = $no_bku - 1;

            foreach ($rincian_data as $data => $value) {
                $data = [
                    'no_voucher' => $rincian_data[$data]['no_voucher'],
                    'tgl_voucher' => $rincian_data[$data]['tgl_voucher'],
                    'no_upload' => $rincian_data[$data]['no_upload'],
                    'rekening_awal' => $rincian_data[$data]['rekening_awal'],
                    'nm_rekening_tujuan' => $rincian_data[$data]['nm_rekening_tujuan'],
                    'rekening_tujuan' => $rincian_data[$data]['rekening_tujuan'],
                    'bank_tujuan' => $rincian_data[$data]['bank_tujuan'],
                    'ket_tujuan' => $rincian_data[$data]['ket_tujuan'],
                    'nilai' => $rincian_data[$data]['total'],
                    'kd_skpd' => $rincian_data[$data]['kd_skpd'],
                    'kd_bp' => $rincian_data[$data]['kd_skpd'],
                    'status_upload' => $rincian_data[$data]['status_upload'],
                    'tgl_validasi' => $tanggal_validasi,
                    'status_validasi' => '1',
                    'no_validasi' => $no_validasi,
                    'no_bukti' => ++$bku,
                ];
                DB::table('trvalidasi_cmsbank')->insert($data);
            }

            $data1 = DB::table('trvalidasi_cmsbank as a')->where(['a.kd_skpd' => $kd_skpd, 'a.no_validasi' => $no_validasi])->select('a.no_voucher', 'a.no_bukti', 'a.kd_skpd', 'a.kd_bp', 'a.tgl_validasi', 'a.status_validasi');

            DB::table('trhtransout_cmsbank as c')->joinSub($data1, 'd', function ($join) {
                $join->on('c.no_voucher', '=', 'd.no_voucher');
                $join->on(
                    'c.kd_skpd',
                    '=',
                    'd.kd_skpd'
                );
            })->whereRaw('left(c.kd_skpd,17) = left(?,17)', $kd_skpd)->update([
                'c.status_validasi' => DB::raw("d.status_validasi"),
                'c.tgl_validasi' => DB::raw("d.tgl_validasi"),
                'c.no_bukti' => DB::raw("d.no_bukti"),
                'c.tgl_bukti' => DB::raw("d.tgl_validasi"),
            ]);

            $data_transout = DB::table('trhtransout_cmsbank as a')->leftJoin('trvalidasi_cmsbank as b', function ($join) {
                $join->on('a.no_voucher', '=', 'b.no_voucher');
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'b.kd_skpd'
                );
            })->where(['b.no_validasi' => $no_validasi, 'b.kd_skpd' => $kd_skpd])->select('b.no_bukti as no_kas', 'b.tgl_validasi as tgl_kas', 'a.no_bukti', 'a.tgl_bukti', 'a.no_sp2d', 'a.ket', 'b.kd_skpd as username', 'a.tgl_update', 'b.kd_skpd', 'a.nm_skpd', 'a.total', 'a.no_tagih', 'a.sts_tagih', 'a.tgl_tagih', 'a.jns_spp', 'a.pay', 'a.no_kas_pot', 'a.panjar', 'a.no_panjar');

            DB::table('trhtransout')->insertUsing(['no_kas', 'tgl_kas', 'no_bukti', 'tgl_bukti', 'no_sp2d', 'ket', 'username', 'tgl_update', 'kd_skpd', 'nm_skpd', 'total', 'no_tagih', 'sts_tagih', 'tgl_tagih', 'jns_spp', 'pay', 'no_kas_pot', 'panjar', 'no_panjar'], $data_transout);

            $data_transout1 = DB::table('trhtransout_cmsbank as a')->join('trdtransout_cmsbank as b', function ($join) {
                $join->on('a.no_voucher', '=', 'b.no_voucher');
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'b.kd_skpd'
                );
            })->leftJoin('trvalidasi_cmsbank as c', function ($join) {
                $join->on('a.no_voucher', '=', 'c.no_voucher');
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'c.kd_skpd'
                );
            })->where(['c.no_validasi' => $no_validasi, 'c.kd_skpd' => $kd_skpd])->select('c.no_bukti', 'a.no_sp2d', 'b.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'b.kd_rek6', 'b.nm_rek6', 'b.nilai', 'b.kd_skpd', DB::raw("'' as kunci"), 'b.sumber', 'b.volume', 'b.satuan');

            DB::table('trdtransout')->insertUsing(['no_bukti', 'no_sp2d', 'kd_sub_kegiatan', 'nm_sub_kegiatan', 'kd_rek6', 'nm_rek6', 'nilai', 'kd_skpd', 'kunci', 'sumber', 'volume', 'satuan'], $data_transout1);

            // POTONGAN
            $data_transout2 = DB::table('trhtrmpot_cmsbank as a')->join('trhtransout_cmsbank as b', function ($join) {
                $join->on('a.no_voucher', '=', 'b.no_voucher');
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'b.kd_skpd'
                );
            })->leftJoin('trvalidasi_cmsbank as c', function ($join) {
                $join->on('b.no_voucher', '=', 'c.no_voucher');
                $join->on(
                    'b.kd_skpd',
                    '=',
                    'c.kd_skpd'
                );
            })->where(['c.no_validasi' => $no_validasi, 'b.status_trmpot' => '1', 'c.kd_skpd' => $kd_skpd])->select(DB::raw("CAST(c.no_bukti as int)+1 as no_bukti"), 'c.tgl_validasi as tgl_bukti', 'a.ket', 'a.username', 'a.tgl_update', 'a.kd_skpd', 'a.nm_skpd', 'a.no_sp2d', 'a.nilai', 'a.npwp', 'a.jns_spp', 'a.status', 'a.kd_sub_kegiatan', 'a.nm_sub_kegiatan', 'a.kd_rek6', 'a.nm_rek6', 'a.nmrekan', 'a.pimpinan', 'a.alamat', 'a.ebilling', 'a.rekening_tujuan', 'a.nm_rekening_tujuan', 'c.no_bukti', DB::raw("'BANK' as pay"));

            DB::table('trhtrmpot')->insertUsing(['no_bukti', 'tgl_bukti', 'ket', 'username', 'tgl_update', 'kd_skpd', 'nm_skpd', 'no_sp2d', 'nilai', 'npwp', 'jns_spp', 'status', 'kd_sub_kegiatan', 'nm_sub_kegiatan', 'kd_rek6', 'nm_rek6', 'nmrekan', 'pimpinan', 'alamat', 'ebilling', 'rekening_tujuan', 'nm_rekening_tujuan', 'no_kas', 'pay'], $data_transout2);

            $data_transout3 = DB::table('trhtrmpot_cmsbank as a')->join('trdtrmpot_cmsbank as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'b.kd_skpd'
                );
            })->leftJoin('trhtransout_cmsbank as c', function ($join) {
                $join->on('a.no_voucher', '=', 'c.no_voucher');
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'c.kd_skpd'
                );
            })->leftJoin('trvalidasi_cmsbank as d', function ($join) {
                $join->on('d.no_voucher', '=', 'c.no_voucher');
                $join->on(
                    'd.kd_skpd',
                    '=',
                    'c.kd_skpd'
                );
            })->where(['d.no_validasi' => $no_validasi, 'c.status_trmpot' => '1', 'd.kd_skpd' => $kd_skpd])->select(DB::raw("CAST(d.no_bukti as int)+1 as no_bukti"), 'b.kd_rek6', 'b.nm_rek6', 'b.nilai', 'b.kd_skpd', 'b.kd_rek_trans', 'b.ebilling', 'b.rekanan', 'b.npwp');

            DB::table('trdtrmpot')->insertUsing(['no_bukti', 'kd_rek6', 'nm_rek6', 'nilai', 'kd_skpd', 'kd_rek_trans', 'ebilling', 'rekanan', 'npwp'], $data_transout3);

            $data_transout4 = DB::table('trdtransout_transfercms as a')->leftJoin('trhtransout_cmsbank as b', function ($join) {
                $join->on('a.no_voucher', '=', 'b.no_voucher');
                $join->on(
                    'a.kd_skpd',
                    '=',
                    'b.kd_skpd'
                );
            })->leftJoin('trvalidasi_cmsbank as c', function ($join) {
                $join->on('b.no_voucher', '=', 'c.no_voucher');
                $join->on(
                    'b.kd_skpd',
                    '=',
                    'c.kd_skpd'
                );
            })->where(['c.no_validasi' => $no_validasi, 'c.kd_skpd' => $kd_skpd])->select('b.no_bukti', 'b.tgl_bukti', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.kd_skpd', 'a.nilai');

            DB::table('trdtransout_transfer')->insertUsing(['no_bukti', 'tgl_bukti', 'rekening_awal', 'nm_rekening_tujuan', 'rekening_tujuan', 'bank_tujuan', 'kd_skpd', 'nilai'], $data_transout4);

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

    public function draftValidasi()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhtransout_cmsbank as a')->leftJoin('trdtransout_cmsbank as b', function ($join) {
            $join->on('a.no_voucher', '=', 'b.no_voucher');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->leftJoin('trdupload_cmsbank as c', function ($join) {
            $join->on('a.no_voucher', '=', 'c.no_voucher');
            $join->on('a.kd_skpd', '=', 'c.kd_skpd');
        })->leftJoin('trvalidasi_cmsbank as d', function ($join) {
            $join->on('d.no_voucher', '=', 'c.no_voucher');
            $join->on('d.kd_bp', '=', 'c.kd_bp');
        })->where(['a.kd_skpd' => $kd_skpd, 'a.status_upload' => '1', 'a.status_validasi' => '1'])->groupBy('a.kd_skpd', 'a.no_voucher', 'a.tgl_voucher', 'a.ket', 'a.total', 'a.status_upload', 'a.status_validasi', 'a.tgl_upload', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'c.no_upload', 'd.no_bukti')->orderBy(DB::raw("CAST(d.no_bukti as int)"))->orderBy('a.tgl_validasi')->orderBy('a.kd_skpd')->select('a.kd_skpd', 'a.no_voucher', 'a.tgl_voucher', 'a.ket', 'a.total', 'a.status_upload', 'a.status_validasi', 'a.tgl_upload', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'c.no_upload', 'd.no_bukti')->get();
        return Datatables::of($data)->addIndexColumn()->make(true);
        return view('skpd.upload_cms.index');
    }

    public function batalValidasi(Request $request)
    {
        $no_voucher = $request->no_voucher;
        $no_bukti = $request->no_bukti;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            $no_bukti1 = strval($no_bukti) + 1;
            $spjbulan = cek_status_spj($kd_skpd);
            $cek_spj = DB::table('trlpj')->select(DB::raw("COUNT(*) as tot_lpj"))->selectRaw("(SELECT DISTINCT CASE WHEN MONTH(a.tgl_bukti)<=?  THEN 1 ELSE 0 END FROM trhtransout a WHERE  a.panjar = '0' AND a.kd_skpd=? AND a.no_bukti=?) as tot_spj", [$spjbulan, $kd_skpd, $no_bukti])->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->first();

            if (
                $cek_spj->tot_lpj != 0 || $cek_spj->tot_spj != 0
            ) {
                return response()->json([
                    'message' => '3'
                ]);
            }

            DB::table('trhtransout')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->delete();

            DB::table('trdtransout')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->delete();

            DB::table('trvalidasi_cmsbank')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd, 'no_voucher' => $no_voucher])->delete();

            DB::table('trhtransout_cmsbank')->where(['no_voucher' => $no_voucher, 'kd_skpd' => $kd_skpd])->update([
                'status_validasi' => '0',
                'tgl_validasi' => '',
            ]);

            $data_potongan = DB::table('trhtransout_cmsbank')->where(['no_voucher' => $no_voucher, 'kd_skpd' => $kd_skpd, 'status_trmpot' => '1'])->count();

            if ($data_potongan == '1') {
                DB::table('trhtrmpot')->where(['no_bukti' => $no_bukti1, 'kd_skpd' => $kd_skpd])->delete();

                DB::table('trdtrmpot')->where(['no_bukti' => $no_bukti1, 'kd_skpd' => $kd_skpd])->delete();
            }

            DB::table('trdtransout_transfer')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->delete();

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
