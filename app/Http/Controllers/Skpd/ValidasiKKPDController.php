<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ValidasiKKPDController extends Controller
{
    public function index()
    {
        $data = [
            'sisa_bank' => sisa_bank_kkpd()
        ];
        return view('skpd.validasi_kkpd.index')->with($data);
    }

    public function loadData(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::table('trhtransout_kkpd as a')
            ->leftJoin('trdtransout_kkpd as b', function ($join) {
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.no_voucher', '=', 'b.no_voucher');
            })
            ->leftJoin('trdupload_kkpd as c', function ($join) {
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
                $join->on('a.no_voucher', '=', 'c.no_voucher');
            })
            ->select('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'c.no_upload')
            ->selectRaw("'0' as tot_pot")
            ->where(['a.kd_skpd' => $kd_skpd, 'a.status_verifikasi' => '1', 'a.status_upload' => '1', 'status_validasi' => '0'])
            ->groupBy('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'c.no_upload', 'a.jns_spp')
            ->orderBy('a.kd_skpd')
            ->orderBy(DB::raw("CAST(a.no_voucher as int)"))
            ->get();

        return Datatables::of($data)->addIndexColumn()->make(true);
    }

    public function create()
    {
        $data = [
            'sisa_bank' => sisa_bank_kkpd()
        ];

        return view('skpd.validasi_kkpd.create')->with($data);
    }

    public function loadTransaksi(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_voucher = $request->no_voucher;

        $no_bukti = array();
        if (!empty($no_voucher)) {
            foreach ($no_voucher as $voucher) {
                $no_bukti[] = $voucher['no_voucher'];
            }
        } else {
            $no_bukti[] = '';
        }

        $data = DB::table('trhtransout_kkpd as a')
            ->leftJoin('trdtransout_kkpd as b', function ($join) {
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.no_voucher', '=', 'b.no_voucher');
            })
            ->leftJoin('trdupload_kkpd as c', function ($join) {
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
                $join->on('a.no_voucher', '=', 'c.no_voucher');
            })
            ->select('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'c.no_upload', 'a.potongan as tot_pot')
            ->where(['a.kd_skpd' => $kd_skpd, 'a.status_verifikasi' => '1', 'a.status_upload' => '1', 'status_validasi' => '0'])
            ->whereNotIn('a.no_voucher', $no_bukti)
            ->groupBy('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'c.no_upload', 'a.jns_spp', 'a.potongan')
            ->orderBy('a.kd_skpd')
            ->orderBy(DB::raw("CAST(a.no_voucher as int)"))
            ->get();

        return response()->json($data);
    }

    public function prosesValidasi(Request $request)
    {
        $rincian_data = $request->rincian_data;
        $tanggal_validasi = $request->tanggal_validasi;
        $kd_skpd = Auth::user()->kd_skpd;
        // dd($request->all());
        DB::beginTransaction();
        try {
            $nomor = collect(DB::select("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
            select no_validasi nomor, 'Urut Validasi cms' ket, kd_skpd as kd_skpd from trvalidasi_kkpd where kd_skpd = ?
            )
            z WHERE kd_skpd=?", [$kd_skpd, $kd_skpd]))
                ->first();

            $no_validasi = $nomor->nomor;
            $no_bku = no_urut($kd_skpd);

            $i = 0;

            foreach ($rincian_data as $data => $value) {
                $bku = $no_bku + $i;
                if ($rincian_data[$data]['status_pot'] == 1) {
                    $i = $i + 2;
                } else {
                    $i = $i + 1;
                }
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
                    'no_bukti' => $bku,
                ];
                DB::table('trvalidasi_kkpd')->insert($data);
            }

            DB::update("UPDATE
                            trhtransout_kkpd
                            SET trhtransout_kkpd.status_validasi = Table_B.status_validasi,
		                        trhtransout_kkpd.tgl_validasi = Table_B.tgl_validasi,
                                trhtransout_kkpd.no_bukti = Table_B.no_bukti,
                                trhtransout_kkpd.tgl_bukti = Table_B.tgl_validasi
                        FROM trhtransout_kkpd
                        INNER JOIN (select a.no_voucher,a.no_bukti,a.kd_skpd,a.kd_bp,a.tgl_validasi,a.status_validasi from trvalidasi_kkpd a
                        where a.kd_skpd=? and no_validasi=?) AS Table_B ON trhtransout_kkpd.no_voucher = Table_B.no_voucher AND trhtransout_kkpd.kd_skpd = Table_B.kd_skpd
                        where left(trhtransout_kkpd.kd_skpd,17)=left(?,17)", [$kd_skpd, $no_validasi, $kd_skpd]);

            DB::insert("INSERT INTO trhtransout (no_kas, tgl_kas, no_bukti, tgl_bukti, no_sp2d, ket, username, tgl_update, kd_skpd, nm_skpd, total, no_tagih, sts_tagih, tgl_tagih, jns_spp, pay, no_kas_pot, panjar, no_panjar, kkpd)
                                    SELECT b.no_bukti as no_kas, b.tgl_validasi as tgl_kas, a.no_bukti, a.tgl_bukti, a.no_sp2d, a.ket, b.kd_skpd as username, a.tgl_update, b.kd_skpd, a.nm_skpd, a.total, a.no_tagih, a.sts_tagih, a.tgl_tagih, a.jns_spp, a.pay, a.no_kas_pot, a.panjar, a.no_panjar,'1' kkpd
                                    FROM trhtransout_kkpd a left join trvalidasi_kkpd b on b.no_voucher=a.no_voucher and a.kd_skpd=b.kd_skpd
                                    WHERE b.no_validasi=? and b.kd_skpd=?", [$no_validasi, $kd_skpd]);

            DB::insert("INSERT INTO trdtransout (no_bukti, no_sp2d, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nilai, kd_skpd,kunci, sumber,volume,satuan)
                                            (SELECT c.no_bukti, a.no_sp2d, b.kd_sub_kegiatan, b.nm_sub_kegiatan, b.kd_rek6, b.nm_rek6, b.nilai, b.kd_skpd,''kunci
                                            ,
											b.sumber,b.volume,b.satuan
                                            FROM trhtransout_kkpd a INNER JOIN trdtransout_kkpd b on b.no_voucher=a.no_voucher and a.kd_skpd=b.kd_skpd
                                            LEFT JOIN trvalidasi_kkpd c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd
                                            WHERE c.no_validasi=? and c.kd_skpd=?)", [$no_validasi, $kd_skpd]);

            // POTONGAN
            DB::insert("INSERT INTO trhtrmpot (no_bukti, tgl_bukti, ket, username, tgl_update, kd_skpd, nm_skpd, no_sp2d, nilai, npwp, jns_spp,
                                                status, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nmrekan, pimpinan, alamat, ebilling,
                                                rekening_tujuan, nm_rekening_tujuan, no_kas,pay)
                                                SELECT cast(c.no_bukti as int)+1 as no_bukti, c.tgl_validasi as tgl_bukti, d.ket, d.username, d.tgl_update, d.kd_skpd, d.nm_skpd, d.no_sp2d, d.nilai, d.npwp, d.jns_spp, d.status, d.kd_sub_kegiatan, d.nm_sub_kegiatan, d.kd_rek6, d.nm_rek6, d.nmrekan, d.pimpinan, d.alamat, d.ebilling, d.rekening_tujuan, d.nm_rekening_tujuan, c.no_bukti, 'BANK'
                                                FROM trhtrmpot_kkpd d JOIN trhtransout_kkpd a on d.no_voucher=a.no_voucher and a.kd_skpd=d.kd_skpd
                                                LEFT JOIN trvalidasi_kkpd c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd
                                                WHERE c.no_validasi=? and a.status_trmpot='1' and c.kd_skpd=?", [$no_validasi, $kd_skpd]);

            DB::insert("INSERT INTO trdtrmpot (no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans,ebilling,rekanan,npwp)
                                                    SELECT cast(c.no_bukti as int)+1 as no_bukti, b.kd_rek6, b.nm_rek6, b.nilai, b.kd_skpd, b.kd_rek_trans,b.ebilling,b.rekanan,b.npwp
                                                    FROM trhtrmpot_kkpd d inner join trdtrmpot_kkpd b on b.no_bukti=d.no_bukti and b.kd_skpd=d.kd_skpd
                                                    LEFT JOIN trhtransout_kkpd a on d.no_voucher=a.no_voucher and a.kd_skpd=d.kd_skpd
                                                    LEFT JOIN trvalidasi_kkpd c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd
                                                    WHERE c.no_validasi=? and a.status_trmpot='1' and c.kd_skpd=?", [$no_validasi, $kd_skpd]);

            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function draftValidasi()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::table('trhtransout_kkpd as a')
            ->leftJoin('trdtransout_kkpd as b', function ($join) {
                $join->on('a.no_voucher', '=', 'b.no_voucher');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->leftJoin('trdupload_kkpd as c', function ($join) {
                $join->on('a.no_voucher', '=', 'c.no_voucher');
                $join->on('a.kd_skpd', '=', 'c.kd_skpd');
            })
            ->leftJoin('trvalidasi_kkpd as d', function ($join) {
                $join->on('d.no_voucher', '=', 'c.no_voucher');
                $join->on('d.kd_bp', '=', 'c.kd_bp');
            })
            ->where(['a.kd_skpd' => $kd_skpd, 'a.status_verifikasi' => '1', 'a.status_upload' => '1', 'a.status_validasi' => '1'])
            ->groupBy('a.kd_skpd', 'a.no_voucher', 'a.tgl_voucher', 'a.ket', 'a.total', 'a.status_upload', 'a.status_validasi', 'a.tgl_upload', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'c.no_upload', 'd.no_bukti')
            ->select('a.kd_skpd', 'a.no_voucher', 'a.tgl_voucher', 'a.ket', 'a.total', 'a.status_upload', 'a.status_validasi', 'a.tgl_upload', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'a.status_trmpot', 'c.no_upload', 'd.no_bukti')
            ->orderBy(DB::raw("CAST(d.no_bukti as int)"))
            ->orderBy('a.tgl_validasi')
            ->orderBy('a.kd_skpd')
            ->get();
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

            $cek_spj = DB::table('trlpj')
                ->select(DB::raw("COUNT(*) as tot_lpj"))
                ->selectRaw("(SELECT DISTINCT CASE WHEN MONTH(a.tgl_bukti)<=?  THEN 1 ELSE 0 END FROM trhtransout a WHERE  a.panjar = '0' AND a.kd_skpd=? AND a.no_bukti=?) as tot_spj", [$spjbulan, $kd_skpd, $no_bukti])
                ->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])
                ->first();

            $cek_spj_tu = DB::table('trlpj_tu')
                ->select(DB::raw("COUNT(*) as tot_lpj"))
                ->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])
                ->first();

            if ($cek_spj->tot_lpj != 0 || $cek_spj->tot_spj != 0 || $cek_spj_tu->tot_lpj != 0) {
                return response()->json([
                    'message' => '3'
                ]);
            }

            DB::table('trhtransout')
                ->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trdtransout')
                ->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trvalidasi_kkpd')
                ->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd, 'no_voucher' => $no_voucher])
                ->delete();

            DB::table('trhtransout_kkpd')
                ->where(['no_voucher' => $no_voucher, 'kd_skpd' => $kd_skpd])
                ->update([
                    'status_validasi' => '0',
                    'tgl_validasi' => '',
                ]);

            $data_potongan = DB::table('trhtransout_kkpd')
                ->where(['no_voucher' => $no_voucher, 'kd_skpd' => $kd_skpd, 'status_trmpot' => '1'])
                ->count();

            if ($data_potongan == '1') {
                DB::table('trhtrmpot')
                    ->where(['no_bukti' => $no_bukti1, 'kd_skpd' => $kd_skpd])
                    ->delete();

                DB::table('trdtrmpot')
                    ->where(['no_bukti' => $no_bukti1, 'kd_skpd' => $kd_skpd])
                    ->delete();
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
}
