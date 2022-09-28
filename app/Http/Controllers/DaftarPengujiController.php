<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class DaftarPengujiController extends Controller
{
    public function index()
    {
        $data = [
            'daftar_penguji' => DB::table('trhuji as a')->select('a.no_uji', 'a.tgl_uji')->groupBy('a.no_uji', 'a.tgl_uji')->orderBy('a.tgl_uji')->orderBy('a.no_uji')->get(),
            'ttd1' => DB::table('ms_ttd')->select('nama', 'nip', 'jabatan')->where(['kode' => 'BUD'])->groupBy('nama', 'nip', 'jabatan')->get()
        ];

        return view('penatausahaan.pengeluaran.daftar_penguji.index')->with($data);
    }

    public function loadSp2d()
    {
        $data = DB::table('trhsp2d')->whereRaw("no_sp2d NOT IN (SELECT no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)")->where(function ($query) {
            $query->where('sp2d_batal', '')->orWhereNull('sp2d_batal');
        })->where('is_verified', '1')->select('no_sp2d', 'tgl_sp2d', 'no_spm', 'tgl_spm', 'nilai')->get();
        return response()->json($data);
    }

    public function create()
    {
        $data = [
            'daftar_sp2d' => DB::table('trhsp2d')->whereRaw("no_sp2d NOT IN (SELECT no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)")->where(function ($query) {
                $query->where('sp2d_batal', '')->orWhereNull('sp2d_batal');
            })->where('is_verified', '1')->select('no_sp2d', 'tgl_sp2d', 'no_spm', 'tgl_spm', 'nilai')->get()
        ];

        return view('penatausahaan.pengeluaran.daftar_penguji.create')->with($data);
    }

    public function simpanPenguji(Request $request)
    {
        $no_advice = $request->no_advice;
        $tanggal = $request->tanggal;
        $detail_penguji = $request->detail_penguji;


        // return response()->json($no_uji);
        DB::beginTransaction();
        try {
            $no_bukti = DB::table('trhuji')->select(DB::raw("ISNULL(MAX(no_urut),0) as urut"))->first();
            $no_urut = $no_bukti->urut + 1;
            $no_uji = $no_urut . '/AD/' . tahun_anggaran();
            DB::table('trhuji')->insert([
                'no_uji' => $no_uji,
                'tgl_uji' => $tanggal,
                'username' => Auth::user()->nama,
                'tgl_update' => date("Y-m-d H:i:s"),
                'no_urut' => $no_urut
            ]);
            DB::commit();
            return response()->json([
                'message' => '1',
                'no_uji' => $no_uji
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function simpanDetailPenguji(Request $request)
    {
        $nomor_baru = $request->nomor_baru;
        $tanggal = $request->tanggal;
        $detail_penguji = $request->detail_penguji;

        DB::beginTransaction();
        try {
            DB::table('trduji')->where(['no_uji' => $nomor_baru])->delete();
            if (isset($detail_penguji)) {
                DB::table('trduji')->insert(array_map(function ($value) use ($nomor_baru, $tanggal) {
                    return [
                        'no_uji' => $nomor_baru,
                        'tgl_uji' => $tanggal,
                        'no_sp2d' => $value['no_sp2d'],
                    ];
                }, $detail_penguji));
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

    public function editPenguji($no_uji)
    {
        $data = [
            'penguji' => DB::table('trhuji')->where(['no_uji' => $no_uji])->first(),
            'daftar_sp2d' => DB::table('trhsp2d')->whereRaw("no_sp2d NOT IN (SELECT no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)")->where(function ($query) {
                $query->where('sp2d_batal', '')->orWhereNull('sp2d_batal');
            })->where('is_verified', '1')->select('no_sp2d', 'tgl_sp2d', 'no_spm', 'tgl_spm', 'nilai')->get(),
            'rincian_penguji' => DB::table('trduji as a')->join('trhsp2d as b', 'a.no_sp2d', '=', 'b.no_sp2d')->select('no_uji', 'tgl_uji', 'a.no_sp2d', 'b.tgl_sp2d', 'no_spm', 'tgl_spm', 'nilai')->where(['no_uji' => $no_uji])->get()
        ];
        return view('penatausahaan.pengeluaran.daftar_penguji.edit')->with($data);
    }

    public function loadRincianPenguji(Request $request)
    {
        $no_advice = $request->no_advice;
        $data = DB::table('trduji as a')->join('trhsp2d as b', 'a.no_sp2d', '=', 'b.no_sp2d')->select('no_uji', 'tgl_uji', 'a.no_sp2d', 'b.tgl_sp2d', 'no_spm', 'tgl_spm', 'nilai')->where(['no_uji' => $no_advice])->get();
        return DataTables::of($data)->make(true);
        return view('penatausahaan.pengeluaran.daftar_penguji.edit');
    }

    public function hapusRincianPenguji(Request $request)
    {
        $no_uji = $request->no_uji;
        $no_sp2d = $request->no_sp2d;

        DB::beginTransaction();
        try {
            DB::table('trduji')->where(['no_uji' => $no_uji, 'no_sp2d' => $no_sp2d])->delete();
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

    public function tambahRincian(Request $request)
    {
        $no_advice = $request->no_advice;
        $tanggal = $request->tanggal;
        $no_sp2d = $request->no_sp2d;

        DB::beginTransaction();
        try {
            DB::table('trduji')->insert([
                'no_uji' => $no_advice,
                'tgl_uji' => $tanggal,
                'no_sp2d' => $no_sp2d
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

    public function simpanEditPenguji(Request $request)
    {
        $no_advice = $request->no_advice;
        $tanggal = $request->tanggal;
        $detail_penguji = $request->detail_penguji;

        DB::beginTransaction();
        try {
            DB::table('trhuji')->where(['no_uji' => $no_advice])->update([
                'tgl_uji' => $tanggal,
                'username' => Auth::user()->nama,
                'tgl_update' => date("Y-m-d H:i:s")
            ]);
            DB::table('trduji')->where(['no_uji' => $no_advice])->delete();
            if (isset($detail_penguji)) {
                DB::table('trduji')->insert(array_map(function ($value) use ($no_advice, $tanggal) {
                    return [
                        'no_uji' => $no_advice,
                        'tgl_uji' => $tanggal,
                        'no_sp2d' => $value['no_sp2d'],
                    ];
                }, $detail_penguji));
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

    public function hapusPenguji(Request $request)
    {
        $no_uji = $request->no_uji;

        DB::beginTransaction();
        try {
            DB::table('trhuji')->where(['no_uji' => $no_uji])->delete();
            DB::table('trduji')->where(['no_uji' => $no_uji])->delete();
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

    public function cetakPenguji(Request $request)
    {
        $no_uji = $request->no_uji;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        $data1 = DB::table('trhsp2d as a')->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->join('trdspp as z', function ($join) {
            $join->on('b.no_spp', '=', 'z.no_spp');
            $join->on('b.kd_skpd', '=', 'z.kd_skpd');
        })->join('ms_skpd as d', 'a.kd_skpd', '=', 'd.kd_skpd')->groupBy('no_sp2d', 'no_spm', 'tgl_sp2d', 'b.nmrekan', 'b.alamat', 'b.pimpinan', 'a.kd_skpd', 'd.nm_skpd', 'a.jns_spp', 'a.jenis_beban')->select('no_sp2d', 'no_spm', 'tgl_sp2d', 'b.nmrekan', 'b.alamat', 'b.pimpinan', 'a.kd_skpd', 'd.nm_skpd', 'a.jns_spp', 'a.jenis_beban', DB::raw("ISNULL(SUM(z.nilai),0) as kotor"));

        $data2 = DB::table('trspmpot as b')->leftJoinSub($data1, 'a', function ($join) {
            $join->on('b.no_spm', '=', 'a.no_spm');
            $join->on('b.kd_skpd', '=', 'a.kd_skpd');
        })->groupBy('no_sp2d', 'a.no_spm', 'tgl_sp2d', 'a.nmrekan', 'a.alamat', 'a.pimpinan', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_spp', 'a.jenis_beban', 'a.kotor')->select('a.*', DB::raw("ISNULL(SUM(b.nilai),0) as pot"));

        $data2 = DB::table('trhuji as a')->join('trduji as b', 'a.no_uji', '=', 'b.no_uji')->leftJoinSub($data2, 'c', function ($join) {
            $join->on('b.no_sp2d', '=', 'c.no_sp2d');
        })->where(['a.no_uji' => $no_uji])->select('b.no_sp2d', 'c.tgl_sp2d', 'c.nmrekan', 'c.pimpinan', 'c.alamat', 'c.kd_skpd', 'c.nm_skpd', 'c.jns_spp', 'c.jenis_beban', 'c.kotor', 'c.pot')->get();

        $total_kotor = 0;
        $total_pot = 0;
        foreach ($data2 as $total) {
            $total_kotor += $total->kotor;
            $total_pot += $total->pot;
        }

        $data = [
            'ttd' => DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['kode' => 'BUD', 'nip' => $ttd])->first(),
            'tanggal' => DB::table('trhuji')->select('tgl_uji')->where(['no_uji' => $no_uji])->first(),
            'jumlah_detail' => DB::table('trduji as a')->join('trhuji as b', 'a.no_uji', '=', 'b.no_uji')->where(['a.no_uji' => $no_uji])->count(),
            'no_uji' => $no_uji,
            'data_penguji' => $data2,
            'total_kotor' => $total_kotor,
            'total_pot' => $total_pot,
            'jumlah_bersih' => $total_kotor - $total_pot
        ];
        $view = view('penatausahaan.pengeluaran.daftar_penguji.cetak')->with($data);
        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view);
            return $pdf->stream('laporan.pdf');
        } else {
            return $view;
        }
    }
}