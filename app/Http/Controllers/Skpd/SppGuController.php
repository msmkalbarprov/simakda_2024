<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SppGuController extends Controller
{
    public function index()
    {
        return view('skpd.spp_gu.index');
    }

    public function load()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhspp as a')
            ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd")
            ->where(['a.kd_skpd' => $kd_skpd, 'a.jns_spp' => '2'])
            ->orderBy('a.no_spp')
            ->orderBy('a.kd_skpd')
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            if ($row->status == 1) {
                $btn = "";
            } else {
                $btn = '<a href="' . route("spp_gu.edit", ['no_spp' => Crypt::encrypt($row->no_spp), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
                $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_spp . '\',\'' . $row->no_lpj . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            }
            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_spp . '\',\'' . $row->jns_spp . '\',\'' . $row->kd_skpd . '\');" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak LPJ" style="margin-right:4px"><i class="uil-print"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambah()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'daftar_bank' => DB::table('ms_skpd')
                ->selectRaw("bank,replace(replace(npwp,'.',''),'-','')as npwp ,(select nama from ms_bank where kode = bank)as nama_bank,rekening")
                ->where(['kd_skpd' => $kd_skpd])
                ->get(),
            'daftar_rekening' => DB::table('ms_rekening_bank_online')
                ->selectRaw("rekening, nm_rekening,npwp")
                ->where(['kd_skpd' => $kd_skpd])
                ->orderBy('rekening')
                ->get(),
            'daftar_spd' => DB::table('trhspd')
                ->selectRaw("no_spd,tgl_spd,'5' as jenis")
                ->whereRaw("left(kd_skpd,17)=left(?,17) and status=? and jns_beban=?", [$kd_skpd, '1', '5'])
                ->get(),
            'daftar_lpj' => DB::table('trhlpj')
                ->selectRaw("no_lpj,tgl_lpj")
                ->where(['status' => '1', 'jenis' => '1', 'kd_skpd' => $kd_skpd])
                ->whereRaw("no_lpj NOT IN(select ISNULL(no_lpj,'') FROM trhspp WHERE kd_skpd=? AND jns_spp=? and (sp2d_batal<>? or sp2d_batal is null))", [$kd_skpd, '2', '1'])
                ->get(),
            'tanggal_lalu' => DB::table('trhspp')
                ->selectRaw("max(tgl_spp) as tgl_spp")
                ->where(['kd_skpd' => $kd_skpd])
                ->whereRaw("(sp2d_batal is null or sp2d_batal= '0')")
                ->first(),
        ];

        return view('skpd.spp_gu.create')->with($data);
    }

    public function detail(Request $request)
    {
        $no_lpj = $request->no_lpj;
        $no_spp = $request->no_spp;
        $tipe = $request->tipe;
        $kd_skpd = Auth::user()->kd_skpd;

        if ($tipe == 'create') {
            $data = DB::table('trlpj as a')
                ->join('trhlpj as b', function ($join) {
                    $join->on('a.no_lpj', '=', 'b.no_lpj');
                })
                ->selectRaw("a.kd_sub_kegiatan,a.kd_rek6, a.nm_rek6, a.nilai ,a.no_bukti,a.no_lpj, a.kd_skpd as kd_unit,a.kd_bp_skpd as kd_skpd,
        (select DISTINCT sumber from trdtransout where trdtransout.no_bukti=a.no_bukti and trdtransout.kd_skpd=a.kd_skpd and trdtransout.kd_sub_kegiatan=a.kd_sub_kegiatan and trdtransout.kd_rek6=a.kd_rek6)as sumber")
                ->where(['a.kd_bp_skpd' => $kd_skpd, 'a.no_lpj' => $no_lpj])
                ->orderBy('a.no_bukti')
                ->orderBy('a.kd_sub_kegiatan')
                ->orderBy('a.kd_rek6')
                ->get();
        } else if ($tipe == 'edit') {
            $data = DB::table('trhspp as a')
                ->join('trdspp as b', function ($join) {
                    $join->on('a.no_spp', '=', 'b.no_spp');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.kd_sub_kegiatan,b.kd_rek6, b.nm_rek6, b.nilai ,b.no_bukti, b.kd_skpd as kd_unit,b.sumber")
                ->where(['a.kd_skpd' => $kd_skpd, 'a.no_spp' => $no_spp])
                ->orderBy('b.no_bukti')
                ->orderBy('b.kd_sub_kegiatan')
                ->orderBy('b.kd_rek6')
                ->get();
        }

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function nomor(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::table('trhspp')
            ->selectRaw("isnull(max(urut),0)+1 as nilai")
            ->where(['kd_skpd' => $kd_skpd])
            ->first();

        return response()->json($data);
    }

    public function simpan(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $cek = DB::table('trhspp')->where(['no_spp' => $data['no_spp']])->count();
            if ($cek > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhspp')
                ->insert([
                    'no_spp' => $data['no_spp'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keperluan' => $data['keterangan'],
                    'bulan' => '',
                    'no_spd' => $data['no_spd'],
                    'jns_spp' => $data['beban'],
                    'bank' => $data['bank'],
                    'nmrekan' => $data['nm_rekening'],
                    'no_rek' => $data['rekening'],
                    'npwp' => $data['npwp'],
                    'nm_skpd' => $data['nm_skpd'],
                    'tgl_spp' => $data['tgl_spp'],
                    'status' => '0',
                    'username' => Auth::user()->nama,
                    'last_update' => date('Y-m-d H:i:s'),
                    'nilai' => $data['total'],
                    'no_lpj' => $data['no_lpj'],
                    'urut' => $data['no_urut'],
                ]);

            DB::table('trdspp')
                ->where(['no_spp' => $data['no_spp'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            $no_spp = $data['no_spp'];
            $spd = $data['no_spd'];

            $data_lpj = DB::table('trlpj as a')
                ->selectRaw("'$no_spp' no_spp, kd_rek6, nm_rek6, nilai, kd_bp_skpd, (Select nm_skpd from ms_skpd where kd_skpd=a.kd_bp_skpd) as nm_skpd, kd_sub_kegiatan, (Select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=a.kd_sub_kegiatan) as nm_sub_kegiatan, '$spd' as no_spd, no_bukti, kd_skpd, (Select sumber from trdtransout where kd_skpd=a.kd_skpd and kd_sub_kegiatan=a.kd_sub_kegiatan and kd_rek6=a.kd_rek6 and no_bukti=a.no_bukti) as sumber, (Select max(isnull(kd,0))+1 from trdspp where no_spp=?) as rows", [$no_spp])
                ->where(['no_lpj' => $data['no_lpj']]);


            DB::table('trdspp')
                ->insertUsing(['no_spp', 'kd_rek6', 'nm_rek6', 'nilai', 'kd_skpd', 'nm_skpd', 'kd_sub_kegiatan', 'nm_sub_kegiatan', 'no_spd', 'no_bukti', 'kd_bidang', 'sumber', 'kd'], $data_lpj);

            DB::table('trdspp as a')
                ->join('trskpd as b', function ($join) {
                    $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->where(['no_spp' => $data['no_spp']])
                ->update([
                    'a.nm_sub_kegiatan' => DB::raw("b.nm_sub_kegiatan"),
                ]);

            DB::table('trhlpj')
                ->where(['no_lpj' => $data['no_lpj'], 'kd_skpd' => $data['kd_skpd']])
                ->update([
                    'status' => '2'
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

    public function edit($no_spp, $kd_skpd)
    {
        $no_spp = Crypt::decrypt($no_spp);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'daftar_bank' => DB::table('ms_skpd')
                ->selectRaw("bank,replace(replace(npwp,'.',''),'-','')as npwp ,(select nama from ms_bank where kode = bank)as nama_bank,rekening")
                ->where(['kd_skpd' => $kd_skpd])
                ->get(),
            'daftar_rekening' => DB::table('ms_rekening_bank_online')
                ->selectRaw("rekening, nm_rekening,npwp")
                ->where(['kd_skpd' => $kd_skpd])
                ->orderBy('rekening')
                ->get(),
            'tanggal_lalu' => DB::table('trhspp')
                ->selectRaw("max(tgl_spp) as tgl_spp")
                ->where(['kd_skpd' => $kd_skpd])
                ->whereRaw("(sp2d_batal is null or sp2d_batal= '0')")
                ->first(),
            'spp' => DB::table('trhspp as a')
                ->selectRaw("a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) as nm_skpd")
                ->where(['a.no_spp' => $no_spp, 'a.kd_skpd' => $kd_skpd, 'a.jns_spp' => '2'])
                ->first()
        ];

        return view('skpd.spp_gu.edit')->with($data);
    }

    public function update(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhspp')
                ->where(['no_spp' => $data['no_spp'], 'kd_skpd' => $data['kd_skpd']])
                ->update([
                    'tgl_spp' => $data['tgl_spp'],
                    'keperluan' => $data['keterangan'],
                    'username' => Auth::user()->nama,
                    'last_update' => date('Y-m-d H:i:s'),
                    'bank' => $data['bank'],
                    'nmrekan' => $data['nm_rekening'],
                    'no_rek' => $data['rekening'],
                    'npwp' => $data['npwp'],
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
        $no_spp = $request->no_spp;
        $no_lpj = $request->no_lpj;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trdspp')
                ->where([
                    'no_spp' => $no_spp,
                    'kd_skpd' => $kd_skpd
                ])
                ->delete();

            DB::table('trhspp')
                ->where([
                    'no_spp' => $no_spp,
                    'kd_skpd' => $kd_skpd,
                ])
                ->delete();

            DB::table('trhlpj')
                ->where(['no_lpj' => $no_lpj, 'kd_skpd' => $kd_skpd])
                ->update([
                    'status' => '1'
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
