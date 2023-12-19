<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\Crypt;

class PelimpahanGUKKPDController extends Controller
{
    public function indexGu()
    {
        return view('skpd.pelimpahan_gu_kkpd.index');
    }

    public function loadDataGu()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::table('tr_setorpelimpahan_bank_cms')
            ->where(['kd_skpd_sumber' => $kd_skpd, 'kkpd' => '1'])
            ->orderBy('tgl_kas')
            ->orderBy(DB::raw("CAST(no_kas as INT)"))
            ->orderBy('kd_skpd')
            ->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            if ($row->status_upload != '1') {
                $btn = '<a href="' . route("pelimpahan_gu_kkpd.edit", Crypt::encryptString($row->no_kas)) . '" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="fa fa-edit"></i></a>';
                $btn .= '<a href="javascript:void(0);" onclick="hapusPelimpahan(' . $row->no_kas . ', \'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
            } else {
                $btn = '';
            }
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahGu()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $skpd = substr($kd_skpd, 0, 17);

        $data = [
            'tujuan_skpd' => DB::table('ms_skpd')
                ->select(DB::raw("SUBSTRING(kd_skpd,1,4)+SUBSTRING(kd_skpd,15,8) as kd_ringkas"), 'kd_skpd', 'nm_skpd', DB::raw("'$kd_skpd' as skpd"))
                ->whereRaw("LEFT(kd_skpd,17) = ?", $skpd)
                ->whereNotIn('kd_skpd', [$kd_skpd])
                ->orderBy('kd_skpd')
                ->get(),
            'tahun_anggaran' => tahun_anggaran(),
            'rekening_bendahara' => DB::table('ms_skpd')
                ->select('rekening')
                ->where(['kd_skpd' => $kd_skpd])
                ->orderBy('kd_skpd')
                ->first(),
            'rekening_tujuan' => DB::table('ms_rekening_bank_online as a')
                ->select('a.rekening', 'a.nm_rekening', 'a.bank', 'a.keterangan', 'a.kd_skpd', 'a.jenis', DB::raw("(SELECT nama FROM ms_bank WHERE kode=a.bank) as nm_bank"))
                ->where(['a.kd_skpd' => $kd_skpd])
                ->orderBy('a.nm_rekening')
                ->get(),
            'sisa_bank' => sisa_bank()
        ];

        return view('skpd.pelimpahan_gu_kkpd.create')->with($data);
    }

    public function noLpj(Request $request)
    {
        $kd_skpd = $request->kd_skpd;

        // $data = DB::table('trhlpj_unit_kkpd')
        //     ->select('no_lpj', 'kd_skpd')
        //     ->selectRaw("(SELECT sum(nilai) from trlpj_kkpd where no_lpj_unit=trhlpj_unit_kkpd.no_lpj and kd_skpd=?) as nilai", [$kd_skpd])
        //     ->where(['kd_skpd' => $kd_skpd])
        //     ->whereRaw("no_lpj not in (select ISNULL(lpj_unit,'') from tr_setorpelimpahan_bank_cms)")
        //     ->get();

        $data = DB::table('trddpt_gabungan as a')
            ->join('trhdpt_gabungan as b', function ($join) {
                $join->on('a.no_dpt', '=', 'b.no_dpt');
                $join->on('a.kd_bp_skpd', '=', 'b.kd_skpd');
            })
            ->select('a.no_dpt_unit', 'a.kd_skpd')
            ->selectRaw("(SELECT sum(nilai) from trddpt where no_dpt=a.no_dpt_unit and kd_skpd=a.kd_skpd) as nilai")
            ->where(['a.kd_skpd' => $kd_skpd])
            ->whereRaw("a.no_dpt_unit not in (select ISNULL(lpj_unit,'') from tr_setorpelimpahan_bank_cms)")
            ->get();

        return response()->json($data);
    }

    public function simpanGu(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $no_urut = no_urut($kd_skpd);

            // SETOR PELIMPAHAN UP

            DB::table('tr_setorpelimpahan_bank_cms')
                ->insert([
                    'no_kas' => $no_urut,
                    'tgl_kas' => $data['tgl_kas'],
                    'no_bukti' => $no_urut,
                    'tgl_bukti' => $data['tgl_kas'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nilai' => $data['nilai'],
                    'keterangan' => $data['keterangan'],
                    'kd_skpd_sumber' => $data['skpd_sumber'],
                    'jenis_spp' => $data['beban'],
                    'rekening_awal' => $data['rekening_bendahara'],
                    'nm_rekening_tujuan' => $data['nama_tujuan'],
                    'rekening_tujuan' => $data['rekening_tujuan'],
                    'bank_tujuan' => $data['bank_tujuan'],
                    'ket_tujuan' => $data['ketcms'],
                    'status_validasi' => '0',
                    'status_upload' => '0',
                    'lpj_unit' => $data['no_dpt'],
                    'kkpd' => '1'
                ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'no_kas' => $no_urut
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
            ]);
        }
    }

    public function editGu($no_kas)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_kas = Crypt::decryptString($no_kas);
        $data_up = DB::table('tr_setorpelimpahan_bank_cms')
            ->where(['kd_skpd_sumber' => $kd_skpd, 'no_kas' => $no_kas, 'kkpd' => '1'])
            ->first();

        $data = [
            'data_up' => $data_up,
            'skpd' => DB::table('ms_skpd')
                ->select('nm_skpd', DB::raw("SUBSTRING(kd_skpd,1,4)+SUBSTRING(kd_skpd,15,8) as kd_ringkas"))
                ->where(['kd_skpd' => $data_up->kd_skpd])
                ->first(),
            'tahun_anggaran' => tahun_anggaran(),
            'rekening_bendahara' => DB::table('ms_skpd')
                ->select('rekening')
                ->where(['kd_skpd' => $kd_skpd])
                ->orderBy('kd_skpd')
                ->first(),
            'rekening_tujuan' => DB::table('ms_rekening_bank_online as a')
                ->select('a.rekening', 'a.nm_rekening', 'a.bank', 'a.keterangan', 'a.kd_skpd', 'a.jenis', DB::raw("(SELECT nama FROM ms_bank WHERE kode=a.bank) as nm_bank"))->where(['a.kd_skpd' => $kd_skpd])
                ->orderBy('a.nm_rekening')
                ->get(),
            'sisa_bank' => sisa_bank()
        ];

        return view('skpd.pelimpahan_gu_kkpd.edit')->with($data);
    }

    public function updateGu(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            // DELETE PELIMPAHAN GU
            DB::table('tr_setorpelimpahan_bank_cms')
                ->where(['kd_skpd' => $data['kd_skpd'], 'no_kas' => $data['no_kas'], 'kkpd' => '1'])
                ->delete();
            // SETOR PELIMPAHAN GU
            DB::table('tr_setorpelimpahan_bank_cms')
                ->insert([
                    'no_kas' => $data['no_kas'],
                    'tgl_kas' => $data['tgl_kas'],
                    'no_bukti' => $data['no_kas'],
                    'tgl_bukti' => $data['tgl_kas'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nilai' => $data['nilai'],
                    'keterangan' => $data['keterangan'],
                    'kd_skpd_sumber' => $data['skpd_sumber'],
                    'jenis_spp' => $data['beban'],
                    'rekening_awal' => $data['rekening_bendahara'],
                    'nm_rekening_tujuan' => $data['nama_tujuan'],
                    'rekening_tujuan' => $data['rekening_tujuan'],
                    'bank_tujuan' => $data['bank_tujuan'],
                    'ket_tujuan' => $data['ketcms'],
                    'status_validasi' => '0',
                    'status_upload' => '0',
                    'lpj_unit' => $data['no_lpj'],
                    'kkpd' => '1',
                ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'no_kas' => $data['no_kas']
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
        $no_kas = $request->no_kas;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('tr_setorpelimpahan_bank_cms')
                ->where(['no_kas' => $no_kas, 'kd_skpd' => $kd_skpd, 'kkpd' => '1'])
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
