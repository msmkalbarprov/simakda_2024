<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiKKPDController extends Controller
{
    // Verifikasi KKPD
    public function indexValidasi()
    {
        $data = [
            'sisa_bank' => sisa_bank_kkpd()
        ];
        return view('skpd.verifikasi_kkpd.index')->with($data);
    }

    public function loadDataValidasi(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::table('trhtransout_kkpd as a')
            ->leftJoin('trdtransout_kkpd as b', function ($join) {
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.no_voucher', '=', 'b.no_voucher');
            })
            ->select('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'a.status_verifikasi', 'a.tgl_verifikasi')
            ->selectRaw("(CASE WHEN a.jns_spp IN ( '4', '6' ) THEN(SELECT SUM( x.nilai ) tot_pot FROM trspmpot x INNER JOIN trhsp2d b ON x.kd_skpd= b.kd_skpd AND x.no_spm= b.no_spm INNER JOIN trhtransout_kkpd c ON b.kd_skpd= c.kd_skpd AND b.no_sp2d= c.no_sp2d WHERE c.kd_skpd=? AND c.no_sp2d= a.no_sp2d AND c.jns_spp IN ( '4', '6' )) ELSE 0 END) as tot_pot", [$kd_skpd])
            ->where(['a.kd_skpd' => $kd_skpd, 'a.status_verifikasi' => '0'])
            ->groupBy('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'a.jns_spp', 'a.status_verifikasi', 'a.tgl_verifikasi')
            ->orderBy('a.kd_skpd')
            ->orderBy(DB::raw("CAST(a.no_voucher as int)"))
            ->get();

        return Datatables::of($data)->addIndexColumn()->make(true);
    }

    public function createValidasi()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'daftar_transaksi' => DB::table('trhtransout_kkpd as a')
                ->leftJoin('trdtransout_kkpd as b', function ($join) {
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                    $join->on('a.no_voucher', '=', 'b.no_voucher');
                })
                ->select('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot')
                ->selectRaw("(CASE WHEN a.jns_spp IN ( '4', '6' ) THEN(SELECT SUM( x.nilai ) tot_pot FROM trspmpot x INNER JOIN trhsp2d b ON x.kd_skpd= b.kd_skpd AND x.no_spm= b.no_spm INNER JOIN trhtransout_kkpd c ON b.kd_skpd= c.kd_skpd AND b.no_sp2d= c.no_sp2d WHERE c.kd_skpd=? AND c.no_sp2d= a.no_sp2d AND c.jns_spp IN ( '4', '6' )) ELSE 0 END) as tot_pot", [$kd_skpd])
                ->where(['a.kd_skpd' => $kd_skpd, 'status_verifikasi' => '0'])
                ->groupBy('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'a.jns_spp')
                ->orderBy('a.kd_skpd')
                ->orderBy(DB::raw("CAST(a.no_voucher as int)"))
                ->get(),
            'sisa_bank' => sisa_bank_kkpd()
        ];

        return view('skpd.verifikasi_kkpd.create')->with($data);
    }

    public function prosesValidasi(Request $request)
    {
        $rincian_data = $request->rincian_data;
        $tanggal_validasi = $request->tanggal_validasi;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $no_voucher = array();
            if (!empty($rincian_data)) {
                foreach ($rincian_data as $rincian) {
                    $no_voucher[] = $rincian['no_voucher'];
                }
            } else {
                $no_voucher[] = '';
            }

            DB::table('trhtransout_kkpd')
                ->whereIn('no_voucher', $no_voucher)
                ->update([
                    'status_verifikasi' => '1',
                    'tgl_verifikasi' => $tanggal_validasi
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
    // SIMPAN BUAT VALIDASI KKPD
    // public function prosesValidasi(Request $request)
    // {
    //     $rincian_data = $request->rincian_data;
    //     $tanggal_validasi = $request->tanggal_validasi;
    //     $kd_skpd = Auth::user()->kd_skpd;

    //     $nomor1 = DB::table('trvalidasi_kkpd')
    //         ->select('no_validasi as nomor', DB::raw("'Urut Validasi KKPD' as ket"), 'kd_skpd')
    //         ->where(['kd_skpd' => $kd_skpd]);
    //     $nomor = DB::table(DB::raw("({$nomor1->toSql()}) AS sub"))
    //         ->select(DB::raw("case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor"))
    //         ->mergeBindings($nomor1)
    //         ->first();

    //     DB::beginTransaction();
    //     try {
    //         $nomor1 = DB::table('trvalidasi_kkpd')->select('no_validasi as nomor', DB::raw("'Urut Validasi KKPD' as ket"), 'kd_skpd')->where(['kd_skpd' => $kd_skpd]);
    //         $nomor = DB::table(DB::raw("({$nomor1->toSql()}) AS sub"))
    //             ->select(DB::raw("case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor"))
    //             ->mergeBindings($nomor1)
    //             ->first();

    //         $no_validasi = $nomor->nomor;
    //         $no_bku = no_urut($kd_skpd);
    //         $bku = $no_bku - 1;

    //         foreach ($rincian_data as $data => $value) {
    //             $data = [
    //                 'no_voucher' => $rincian_data[$data]['no_voucher'],
    //                 'tgl_voucher' => $rincian_data[$data]['tgl_voucher'],
    //                 'rekening_awal' => $rincian_data[$data]['rekening_awal'],
    //                 'nm_rekening_tujuan' => $rincian_data[$data]['nm_rekening_tujuan'],
    //                 'rekening_tujuan' => $rincian_data[$data]['rekening_tujuan'],
    //                 'bank_tujuan' => $rincian_data[$data]['bank_tujuan'],
    //                 'ket_tujuan' => $rincian_data[$data]['ket_tujuan'],
    //                 'nilai' => $rincian_data[$data]['total'],
    //                 'kd_skpd' => $rincian_data[$data]['kd_skpd'],
    //                 'kd_bp' => $rincian_data[$data]['kd_skpd'],
    //                 'tgl_validasi' => $tanggal_validasi,
    //                 'status_validasi' => '1',
    //                 'no_validasi' => $no_validasi,
    //                 'no_bukti' => ++$bku,
    //             ];
    //             DB::table('trvalidasi_kkpd')->insert($data);
    //         }

    //         $data1 = DB::table('trvalidasi_kkpd as a')->where(['a.kd_skpd' => $kd_skpd, 'a.no_validasi' => $no_validasi])->select('a.no_voucher', 'a.no_bukti', 'a.kd_skpd', 'a.kd_bp', 'a.tgl_validasi', 'a.status_validasi');

    //         DB::table('trhtransout_kkpd as c')->joinSub($data1, 'd', function ($join) {
    //             $join->on('c.no_voucher', '=', 'd.no_voucher');
    //             $join->on('c.kd_skpd', '=', 'd.kd_skpd');
    //         })->whereRaw('left(c.kd_skpd,17) = left(?,17)', $kd_skpd)->update([
    //             'c.status_validasi' => DB::raw("d.status_validasi"),
    //             'c.tgl_validasi' => DB::raw("d.tgl_validasi"),
    //             'c.no_bukti' => DB::raw("d.no_bukti"),
    //             'c.tgl_bukti' => DB::raw("d.tgl_validasi"),
    //         ]);

    //         $data_transout = DB::table('trhtransout_kkpd as a')->leftJoin('trvalidasi_kkpd as b', function ($join) {
    //             $join->on('a.no_voucher', '=', 'b.no_voucher');
    //             $join->on('a.kd_skpd', '=', 'b.kd_skpd');
    //         })->where(['b.no_validasi' => $no_validasi, 'b.kd_skpd' => $kd_skpd])->select('b.no_bukti as no_kas', 'b.tgl_validasi as tgl_kas', 'a.no_bukti', 'a.tgl_bukti', 'a.no_sp2d', 'a.ket', 'b.kd_skpd as username', 'a.tgl_update', 'b.kd_skpd', 'a.nm_skpd', 'a.total', 'a.no_tagih', 'a.sts_tagih', 'a.tgl_tagih', 'a.jns_spp', 'a.pay', 'a.no_kas_pot', 'a.panjar', 'a.no_panjar');

    //         DB::table('trhtransout')->insertUsing(['no_kas', 'tgl_kas', 'no_bukti', 'tgl_bukti', 'no_sp2d', 'ket', 'username', 'tgl_update', 'kd_skpd', 'nm_skpd', 'total', 'no_tagih', 'sts_tagih', 'tgl_tagih', 'jns_spp', 'pay', 'no_kas_pot', 'panjar', 'no_panjar'], $data_transout);

    //         $data_transout1 = DB::table('trhtransout_kkpd as a')->join('trdtransout_kkpd as b', function ($join) {
    //             $join->on('a.no_voucher', '=', 'b.no_voucher');
    //             $join->on('a.kd_skpd', '=', 'b.kd_skpd');
    //         })->leftJoin('trvalidasi_kkpd as c', function ($join) {
    //             $join->on('a.no_voucher', '=', 'c.no_voucher');
    //             $join->on('a.kd_skpd', '=', 'c.kd_skpd');
    //         })->where(['c.no_validasi' => $no_validasi, 'c.kd_skpd' => $kd_skpd])->select('c.no_bukti', 'a.no_sp2d', 'b.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'b.kd_rek6', 'b.nm_rek6', 'b.nilai', 'b.kd_skpd', DB::raw("'' as kunci"), 'b.sumber', 'b.volume', 'b.satuan');

    //         DB::table('trdtransout')->insertUsing(['no_bukti', 'no_sp2d', 'kd_sub_kegiatan', 'nm_sub_kegiatan', 'kd_rek6', 'nm_rek6', 'nilai', 'kd_skpd', 'kunci', 'sumber', 'volume', 'satuan'], $data_transout1);

    //         // POTONGAN
    //         $data_transout2 = DB::table('trhtrmpot_kkpd as a')->join('trhtransout_kkpd as b', function ($join) {
    //             $join->on('a.no_voucher', '=', 'b.no_voucher');
    //             $join->on('a.kd_skpd', '=', 'b.kd_skpd');
    //         })->leftJoin('trvalidasi_kkpd as c', function ($join) {
    //             $join->on('b.no_voucher', '=', 'c.no_voucher');
    //             $join->on('b.kd_skpd', '=', 'c.kd_skpd');
    //         })->where(['c.no_validasi' => $no_validasi, 'b.status_trmpot' => '1', 'c.kd_skpd' => $kd_skpd])->select(DB::raw("CAST(c.no_bukti as int)+1 as no_bukti"), 'c.tgl_validasi as tgl_bukti', 'a.ket', 'a.username', 'a.tgl_update', 'a.kd_skpd', 'a.nm_skpd', 'a.no_sp2d', 'a.nilai', 'a.npwp', 'a.jns_spp', 'a.status', 'a.kd_sub_kegiatan', 'a.nm_sub_kegiatan', 'a.kd_rek6', 'a.nm_rek6', 'a.nmrekan', 'a.pimpinan', 'a.alamat', 'a.ebilling', 'a.rekening_tujuan', 'a.nm_rekening_tujuan', 'c.no_bukti', DB::raw("'BANK' as pay"));

    //         DB::table('trhtrmpot')->insertUsing(['no_bukti', 'tgl_bukti', 'ket', 'username', 'tgl_update', 'kd_skpd', 'nm_skpd', 'no_sp2d', 'nilai', 'npwp', 'jns_spp', 'status', 'kd_sub_kegiatan', 'nm_sub_kegiatan', 'kd_rek6', 'nm_rek6', 'nmrekan', 'pimpinan', 'alamat', 'ebilling', 'rekening_tujuan', 'nm_rekening_tujuan', 'no_kas', 'pay'], $data_transout2);

    //         $data_transout3 = DB::table('trhtrmpot_kkpd as a')->join('trdtrmpot_kkpd as b', function ($join) {
    //             $join->on('a.no_bukti', '=', 'b.no_bukti');
    //             $join->on('a.kd_skpd', '=', 'b.kd_skpd');
    //         })->leftJoin('trhtransout_kkpd as c', function ($join) {
    //             $join->on('a.no_voucher', '=', 'c.no_voucher');
    //             $join->on('a.kd_skpd', '=', 'c.kd_skpd');
    //         })->leftJoin('trvalidasi_kkpd as d', function ($join) {
    //             $join->on('d.no_voucher', '=', 'c.no_voucher');
    //             $join->on('d.kd_skpd', '=', 'c.kd_skpd');
    //         })->where(['d.no_validasi' => $no_validasi, 'c.status_trmpot' => '1', 'd.kd_skpd' => $kd_skpd])->select(DB::raw("CAST(d.no_bukti as int)+1 as no_bukti"), 'b.kd_rek6', 'b.nm_rek6', 'b.nilai', 'b.kd_skpd', 'b.kd_rek_trans', 'b.ebilling', 'b.rekanan', 'b.npwp');

    //         DB::table('trdtrmpot')->insertUsing(['no_bukti', 'kd_rek6', 'nm_rek6', 'nilai', 'kd_skpd', 'kd_rek_trans', 'ebilling', 'rekanan', 'npwp'], $data_transout3);

    //         DB::commit();
    //         return response()->json([
    //             'message' => '1'
    //         ]);
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => '0'
    //         ]);
    //     }
    // }

    public function draftValidasi()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::table('trhtransout_kkpd as a')
            ->leftJoin('trdtransout_kkpd as b', function ($join) {
                $join->on('a.no_voucher', '=', 'b.no_voucher');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->where(['a.kd_skpd' => $kd_skpd, 'a.status_verifikasi' => '1', 'a.status_upload' => '0'])
            ->groupBy('a.kd_skpd', 'a.no_voucher', 'a.tgl_voucher', 'a.ket', 'a.total', 'a.status_verifikasi', 'a.tgl_verifikasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'a.no_bukti')->orderBy(DB::raw("CAST(a.no_bukti as int)"))
            ->orderBy('a.tgl_verifikasi')
            ->orderBy('a.kd_skpd')
            ->select('a.kd_skpd', 'a.no_voucher', 'a.tgl_voucher', 'a.ket', 'a.total', 'a.status_verifikasi', 'a.tgl_verifikasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'a.no_bukti')
            ->get();

        return Datatables::of($data)->addIndexColumn()->make(true);
    }

    public function batalValidasi(Request $request)
    {
        $no_voucher = $request->no_voucher;
        $no_bukti = $request->no_bukti;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhtransout_kkpd')
                ->where(['no_voucher' => $no_voucher, 'kd_skpd' => $kd_skpd])
                ->update([
                    'status_verifikasi' => '0',
                    'tgl_verifikasi' => ''
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

    // HAPUS BATAL VALIDASI KKPD (DISIMPAN)
    // public function batalValidasi(Request $request)
    // {
    //     $no_voucher = $request->no_voucher;
    //     $no_bukti = $request->no_bukti;
    //     $kd_skpd = $request->kd_skpd;

    //     DB::beginTransaction();
    //     try {
    //         $no_bukti1 = strval($no_bukti) + 1;
    //         $spjbulan = cek_status_spj($kd_skpd);
    //         $cek_spj = DB::table('trlpj')->select(DB::raw("COUNT(*) as tot_lpj"))->selectRaw("(SELECT DISTINCT CASE WHEN MONTH(a.tgl_bukti)<=?  THEN 1 ELSE 0 END FROM trhtransout a WHERE  a.panjar = '0' AND a.kd_skpd=? AND a.no_bukti=?) as tot_spj", [$spjbulan, $kd_skpd, $no_bukti])->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->first();

    //         if ($cek_spj->tot_lpj != 0 || $cek_spj->tot_spj != 0) {
    //             return response()->json([
    //                 'message' => '3'
    //             ]);
    //         }

    //         DB::table('trhtransout')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->delete();

    //         DB::table('trdtransout')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])->delete();

    //         DB::table('trvalidasi_kkpd')->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd, 'no_voucher' => $no_voucher])->delete();

    //         DB::table('trhtransout_kkpd')->where(['no_voucher' => $no_voucher, 'kd_skpd' => $kd_skpd])->update([
    //             'status_validasi' => '0',
    //             'tgl_validasi' => '',
    //         ]);

    //         $data_potongan = DB::table('trhtransout_kkpd')->where(['no_voucher' => $no_voucher, 'kd_skpd' => $kd_skpd, 'status_trmpot' => '1'])->count();

    //         if ($data_potongan == '1') {
    //             DB::table('trhtrmpot')->where(['no_bukti' => $no_bukti1, 'kd_skpd' => $kd_skpd])->delete();

    //             DB::table('trdtrmpot')->where(['no_bukti' => $no_bukti1, 'kd_skpd' => $kd_skpd])->delete();
    //         }

    //         DB::commit();
    //         return response()->json([
    //             'message' => '1'
    //         ]);
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => '0'
    //         ]);
    //     }
    // }
}
