<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UplKKPDController extends Controller
{
    public function index()
    {
        $data = [
            'sisa_bank' => sisa_bank_kkpd1()
        ];

        return view('skpd.upl_kkpd.index')->with($data);
    }

    public function loadUpload()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::table('trhtransout_kkpd as a')
            ->leftJoin('trdtransout_kkpd as b', function ($join) {
                $join->on('a.no_voucher', '=', 'b.no_voucher');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->select('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'b.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'a.potongan as bersih')
            ->selectRaw("CASE WHEN a.jns_spp IN('4','6') THEN (SELECT SUM(x.nilai) as tot_pot FROM trspmpot x INNER JOIN trhsp2d b ON x.kd_skpd=b.kd_skpd AND x.no_spm=b.no_spm INNER JOIN trhtransout_kkpd c ON b.kd_skpd=c.kd_skpd AND b.no_sp2d=c.no_sp2d WHERE c.kd_skpd=? AND c.no_sp2d=a.no_sp2d AND c.jns_spp IN('4','6')) ELSE 0 END as tot_pot", [$kd_skpd])
            ->where(['a.kd_skpd' => $kd_skpd, 'status_upload' => '0'])
            ->groupBy('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'b.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'a.potongan', 'a.jns_spp')
            ->orderBy(DB::raw("CAST(a.no_voucher as int)"))
            ->orderBy('a.kd_skpd')
            ->get();

        return Datatables::of($data)->addIndexColumn()->make(true);
    }

    public function draftUpload()
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
            ->selectRaw("a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,a.bank_tujuan,a.ket_tujuan,b.kd_sub_kegiatan,b.nm_sub_kegiatan,c.no_upload,c.no_upload_tgl")
            ->where(['a.kd_skpd' => $kd_skpd, 'a.status_upload' => '1', 'a.status_validasi' => '0'])
            ->groupByRaw("a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,a.bank_tujuan,a.ket_tujuan,b.kd_sub_kegiatan,b.nm_sub_kegiatan,c.no_upload,c.no_upload_tgl")
            ->orderByRaw("cast(c.no_upload as int),cast(a.no_voucher as int),a.kd_skpd")
            ->get();

        return Datatables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="javascript:void(0);" onclick="lihatDataUpload(\'' . $row->no_upload . '\',\'' . $row->total . '\');" class="btn btn-info btn-sm" style="margin-right:4px"><i class="uil-eye"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="batalUpload(\'' . $row->no_upload . '\',\'' . $row->kd_skpd . '\',\'' . $row->no_voucher . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function rekeningTransaksi(Request $request)
    {
        $no_voucher = $request->nomor;
        $kd_skpd = $request->kd_skpd;

        $data = DB::table('trhtransout_kkpd as a')
            ->join('trdtransout_kkpd as b', function ($join) {
                $join->on('a.no_voucher', '=', 'b.no_voucher');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->where(['a.no_voucher' => $no_voucher, 'a.kd_skpd' => $kd_skpd])
            ->orderBy('b.kd_sub_kegiatan')
            ->orderBy('b.kd_rek6')
            ->select('b.*', DB::raw("'0' as lalu"), DB::raw("'0' as sp2d"), DB::raw("'0' as anggaran"))
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);;
    }

    public function rekeningPotongan(Request $request)
    {
        $no_voucher = $request->nomor;
        $kd_skpd = $request->kd_skpd;

        $data = DB::table('trdtrmpot_kkpd as a')
            ->join('trhtrmpot_kkpd as b', function ($join) {
                $join->on('a.no_bukti', '=', 'b.no_bukti');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->select('a.*')
            ->where(['b.no_voucher' => $no_voucher, 'b.kd_skpd' => $kd_skpd])
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);;
    }

    public function rekeningTujuan(Request $request)
    {
        $no_voucher = $request->nomor;
        $kd_skpd = $request->kd_skpd;

        $data = DB::table('trhtransout_cmsbank as a')
            ->join('trdtransout_transfercms as b', function ($join) {
                $join->on('a.no_voucher', '=', 'b.no_voucher');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->where(['b.no_voucher' => $no_voucher, 'b.kd_skpd' => $kd_skpd])
            ->groupBy('b.no_voucher', 'b.tgl_voucher', 'b.rekening_awal', 'b.nm_rekening_tujuan', 'b.rekening_tujuan', 'b.bank_tujuan', 'b.kd_skpd', 'b.nilai')
            ->select('b.no_voucher', 'b.tgl_voucher', 'b.rekening_awal', 'b.nm_rekening_tujuan', 'b.rekening_tujuan', 'b.bank_tujuan', 'b.kd_skpd', 'b.nilai', DB::raw("(SELECT SUM(nilai) FROM trdtransout_transfercms WHERE no_voucher=b.no_voucher AND kd_skpd=b.kd_skpd) as total"))
            ->get();

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function loadTransaksi(Request $request)
    {
        $no_voucher = $request->no_voucher;
        $kd_skpd = Auth::user()->kd_skpd;

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
                $join->on('a.no_voucher', '=', 'b.no_voucher');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->select('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'b.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'a.potongan as tot_pot')
            ->selectRaw("'0' as bersih")
            ->where(['a.kd_skpd' => $kd_skpd, 'status_upload' => '0'])
            ->whereNotIn('a.no_voucher', $no_bukti)
            ->groupBy('a.kd_skpd', 'a.nm_skpd', 'a.no_tgl', 'a.no_voucher', 'a.tgl_voucher', 'a.no_sp2d', 'a.ket', 'a.total', 'a.status_upload', 'a.tgl_upload', 'a.status_validasi', 'a.tgl_validasi', 'a.rekening_awal', 'a.nm_rekening_tujuan', 'a.rekening_tujuan', 'a.bank_tujuan', 'a.ket_tujuan', 'b.kd_sub_kegiatan', 'b.nm_sub_kegiatan', 'a.jns_spp', 'a.potongan')
            ->orderBy(DB::raw("CAST(a.no_voucher as int)"))
            ->orderBy('a.kd_skpd')
            ->get();

        return response()->json($data);
    }

    public function create()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'sisa_bank' => sisa_bank_kkpd1(),
        ];

        return view('skpd.upl_kkpd.create')->with($data);
    }

    public function prosesUpload(Request $request)
    {
        $total_transaksi = $request->total_transaksi;
        $rincian_data = $request->rincian_data;
        $tanggal = date("Y-m-d");
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {

            $nomor = collect(DB::select("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
            select no_upload nomor, 'Urut Upload Pengeluaran cms' ket, kd_skpd from trhupload_kkpd where kd_skpd=?
            )
            z WHERE kd_skpd=?", [$kd_skpd, $kd_skpd]))->first();

            $no_upload = collect(DB::select("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
                    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Pengeluaran cms' ket, a.kd_skpd from trdupload_kkpd a
                    left join trhupload_kkpd b on b.kd_skpd=a.kd_bp and b.no_upload=a.no_upload
                where a.kd_skpd=?
                )
                z WHERE kd_skpd=? AND tanggal=?", [$kd_skpd, $kd_skpd, $tanggal]))
                ->first();

            $no_upload = $no_upload->nomor;

            if (Str::length($no_upload) == '1') {
                $no_upload1 = "00" . $no_upload;
            } elseif (Str::length($no_upload) == '2') {
                $no_upload1 = "0" . $no_upload;
            } elseif (Str::length($no_upload) == '3') {
                $no_upload1 = $no_upload;
            }

            DB::table('trhupload_kkpd')
                ->where(['no_upload' => $nomor->nomor, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trdupload_kkpd')
                ->where(['no_upload' => $nomor->nomor, 'kd_skpd' => $kd_skpd])
                ->delete();

            if (isset($rincian_data)) {
                DB::table('trdupload_kkpd')
                    ->insert(array_map(function ($value) use ($nomor, $no_upload1) {
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
                            'username' => Auth::user()->nama
                        ];
                    }, $rincian_data));
            }

            DB::table('trhupload_kkpd')
                ->insert([
                    'no_upload' => $nomor->nomor,
                    'tgl_upload' => $tanggal,
                    'kd_skpd' => $kd_skpd,
                    'total' => $total_transaksi,
                    'no_upload_tgl' => $no_upload1,
                ]);

            $data1 = DB::table('trhupload_kkpd as a')
                ->leftJoin('trdupload_kkpd as b', function ($join) {
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                    $join->on('a.no_upload', '=', 'b.no_upload');
                })
                ->where(['b.kd_bp' => $kd_skpd, 'a.no_upload' => $nomor->nomor])
                ->select('a.no_upload', 'b.kd_skpd', 'a.tgl_upload', 'b.status_upload', 'b.no_voucher', 'b.kd_bp');

            DB::table('trhtransout_kkpd as c')
                ->joinSub($data1, 'd', function ($join) {
                    $join->on('c.no_voucher', '=', 'd.no_voucher');
                    $join->on('c.kd_skpd', '=', 'd.kd_skpd');
                })
                ->whereRaw('left(c.kd_skpd,17) = left(?,17)', $kd_skpd)
                ->update([
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
                'message' => '0',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function batalUpload(Request $request)
    {
        $no_upload = $request->no_upload;
        $no_voucher = $request->no_voucher;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            $cek = collect(DB::select("SELECT count(*) as jum from trdupload_kkpd where no_upload=? AND kd_skpd=?", [$no_upload, $kd_skpd]))->first();

            if ($cek->jum > 1) {
                DB::delete("DELETE from trdupload_kkpd where no_voucher=? and no_upload=? AND kd_skpd=?", [$no_voucher, $no_upload, $kd_skpd]);

                DB::update("UPDATE
                            trhupload_kkpd
                            SET trhupload_kkpd.total = Table_B.total
                        FROM trhupload_kkpd
                        INNER JOIN (select a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.kd_bp,sum(b.nilai) as total from trhupload_kkpd a left join
                        trdupload_kkpd b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload
                        where b.kd_bp=? and a.no_upload=?
                        group by a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.kd_bp) AS Table_B ON trhupload_kkpd.no_upload = Table_B.no_upload AND trhupload_kkpd.kd_skpd = Table_B.kd_skpd
                        where left(trhupload_kkpd.kd_skpd,17)=left(?,17)", [$kd_skpd, $no_upload, $kd_skpd]);
            } else {
                DB::delete("DELETE from trdupload_kkpd where no_voucher=? and no_upload=? AND kd_skpd=?", [$no_voucher, $no_upload, $kd_skpd]);

                DB::delete("DELETE from trhupload_kkpd where no_upload=? AND kd_skpd=?", [$no_upload, $kd_skpd]);
            }

            DB::update("UPDATE trhtransout_kkpd set status_upload='0', tgl_upload='' where no_voucher=? AND kd_skpd=?", [$no_voucher, $kd_skpd]);

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
        ob_start();
        $no_upload = $request->no_upload;
        $kd_skpd = Auth::user()->kd_skpd;
        $tahun = tahun_anggaran();

        $obskpd = DB::table('ms_skpd')
            ->select('obskpd')
            ->where(['kd_skpd' => $kd_skpd])
            ->first();

        $query1 = DB::table('trhupload_kkpd as a')
            ->join('trdupload_kkpd as b', function ($join) {
                $join->on('a.no_upload', '=', 'b.no_upload');
                $join->on('a.kd_skpd', '=', 'b.kd_bp');
            })
            ->join('trdtransout_kkpd as e', function ($join) {
                $join->on('b.kd_skpd', '=', 'e.kd_skpd');
                $join->on('b.no_voucher', '=', 'e.no_voucher');
            })
            ->join('trhtransout_kkpd as f', function ($join) {
                $join->on('f.kd_skpd', '=', 'e.kd_skpd');
                $join->on('f.no_voucher', '=', 'e.no_voucher');
            })
            ->join('trhsp2d as i', function ($join) {
                $join->on('i.kd_skpd', '=', 'f.kd_skpd');
                $join->on('i.no_sp2d', '=', 'f.no_dpt');
            })
            ->join('trhspp as j', function ($join) {
                $join->on('j.kd_skpd', '=', 'i.kd_skpd');
                $join->on('j.no_spp', '=', 'i.no_spp');
            })
            ->join('trhdpt as g', function ($join) {
                $join->on('g.kd_skpd', '=', 'j.kd_skpd');
                $join->on('g.no_dpt', '=', 'j.no_lpj');
            })
            ->join('trhdpr as h', function ($join) {
                $join->on('h.kd_skpd', '=', 'g.kd_skpd');
                $join->on('h.no_dpr', '=', 'g.no_dpr');
            })
            ->where(['a.kd_skpd' => $kd_skpd, 'a.no_upload' => $no_upload])
            ->selectRaw(
                "a.no_upload,
                a.tgl_upload,
                a.kd_skpd,
                (SELECT obskpd FROM ms_skpd WHERE kd_skpd=b.kd_skpd) as nm_skpd,
                b.rekening_awal,
                '' as nm_rekening_tujuan,
                '' as rekening_tujuan,
                ISNULL(SUM(e.nilai),0) as nilai,
                ISNULL(SUM(f.potongan),0) as potongan,
                a.no_upload+'/' + (select CAST(DATEPART(MONTH, CAST(a.tgl_upload AS DATETIME)) AS VARCHAR(4))) + '/$tahun/' + h.no_kkpd as ket_tujuan,
                b.no_upload_tgl,
                h.no_kkpd"
            )
            ->groupBy('a.no_upload', 'a.tgl_upload', 'a.kd_skpd', 'b.kd_skpd', 'b.rekening_awal', 'b.ket_tujuan', 'e.kd_sub_kegiatan', 'b.no_upload_tgl', 'h.no_kkpd');


        // (REPLACE(b.ket_tujuan, '2023.', RIGHT(e.kd_sub_kegiatan,5)+ '/')) as ket_tujuan,

        $query = DB::table(DB::raw("({$query1->toSql()}) AS sub"))
            ->selectRaw("no_upload,tgl_upload,kd_skpd,nm_skpd,rekening_awal,nm_rekening_tujuan,rekening_tujuan,SUM(nilai-potongan) as nilai,ket_tujuan,no_upload_tgl,no_kkpd")
            ->mergeBindings($query1)
            ->groupByRaw("no_upload,tgl_upload,kd_skpd,nm_skpd,rekening_awal,nm_rekening_tujuan,rekening_tujuan,ket_tujuan,no_upload_tgl,no_kkpd")
            ->get();

        $rekening_tujuan = "0109990000";
        $nm_rekening_tujuan = "R/P SETORAN KKPD PROV";

        foreach ($query as $data) {
            $tgl_upload = $data->tgl_upload;
            $no_upload_tgl = $data->no_upload_tgl;
            $nilai = strval($data->nilai);
            $nilai = str_replace('.00', '', $nilai);

            $result = $data->nm_skpd . ";" . str_replace(" ", "", rtrim($data->rekening_awal)) . ";" . rtrim($nm_rekening_tujuan) . ";" . str_replace(" ", "", rtrim($rekening_tujuan)) . ";" . $nilai . ";" . $data->ket_tujuan . "\n";

            $init_tgl = explode("-", $tgl_upload);
            $tglupl = $init_tgl[2] . $init_tgl[1] . $init_tgl[0];
            $filename = 'OB' . "_" . $obskpd->obskpd . "_" . $tglupl . "_" . $no_upload_tgl;

            echo $result;
            header("Cache-Control: no-cache, no-store");
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $filename . '.csv"');
        }
    }

    public function cetakCsvLuarKalbar(Request $request)
    {
        ob_start();
        $no_upload = $request->no_upload;
        $kd_skpd = Auth::user()->kd_skpd;

        $obskpd = DB::table('ms_skpd')->select('obskpd')->where(['kd_skpd' => $kd_skpd])->first();

        $query1 = DB::table('trhupload_cmsbank as a')->leftJoin('trdupload_cmsbank as b', function ($join) {
            $join->on('a.no_upload', '=', 'b.no_upload');
            $join->on('a.kd_skpd', '=', 'b.kd_bp');
        })->leftJoin('trdtransout_transfercms as c', function ($join) {
            $join->on('b.no_voucher', '=', 'c.no_voucher');
            $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            $join->on('b.tgl_voucher', '=', 'c.tgl_voucher');
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


        $data = DB::table('trhupload_kkpd as a')
            ->leftJoin('trdupload_kkpd as b', function ($join) {
                $join->on('a.kd_skpd', '=', 'b.kd_bp');
                $join->on('a.no_upload', '=', 'b.no_upload');
            })
            ->join('trhtransout_kkpd as d', function ($join) {
                $join->on('b.no_voucher', '=', 'd.no_voucher');
                $join->on('b.kd_skpd', '=', 'd.kd_skpd');
            })
            ->select('d.ket', 'b.kd_skpd', 'b.no_voucher', 'b.tgl_voucher', 'a.no_upload', 'a.tgl_upload', 'a.total', 'b.nilai', 'b.status_upload', 'b.rekening_awal', 'b.nm_rekening_tujuan', 'b.rekening_tujuan', 'b.bank_tujuan', 'b.ket_tujuan', 'd.potongan as pot')
            ->selectRaw("'0' as bersih")
            ->where(['a.kd_skpd' => $kd_skpd, 'a.no_upload' => $no_upload])
            ->groupBy('b.kd_skpd', 'b.no_voucher', 'b.tgl_voucher', 'a.no_upload', 'a.tgl_upload', 'a.total', 'b.nilai', 'b.status_upload', 'b.rekening_awal', 'b.nm_rekening_tujuan', 'b.rekening_tujuan', 'b.bank_tujuan', 'b.ket_tujuan', 'd.ket', 'd.potongan')
            ->orderBy(DB::raw("CAST(a.no_upload as int)"))
            ->orderBy('b.kd_skpd')
            ->get();

        return Datatables::of($data)->addIndexColumn()->make(true);
    }
}
