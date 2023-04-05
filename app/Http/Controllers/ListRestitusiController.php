<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class ListRestitusiController extends Controller
{
    public function index()
    {
        return view('bud.list_restitusi.index');
    }

    public function load()
    {
        $data = DB::select("SELECT a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd from trhrestitusi a WHERE a.jns_trans=3 order by a.tgl_kas,no_kas");

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("list_restitusi.edit", ['no_kas' => Crypt::encrypt($row->no_kas), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-primary btn-sm"  style="margin-right:4px"><i class="uil-eye"></i></a>';
            if ($row->kd_bank  == 1) {
                $btn .= "";
            } else {
                $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_kas . '\',\'' . $row->no_sts . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            }
            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_kas . '\',\'' . $row->no_sts . '\',\'' . $row->kd_skpd . '\');" class="btn btn-success btn-sm" style="margin-right:4px"><i class="uil-print"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambah()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $status_ang_pend = DB::table('trhrka')
            ->select('jns_ang')
            ->where(['kd_skpd' => $kd_skpd, 'status' => '1'])
            ->orderByDesc('tgl_dpa')
            ->first();

        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_akun' => DB::select("SELECT distinct a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek5, a.kd_sub_kegiatan FROM trdrka_pend a left join ms_rek6 b on a.kd_rek6=b.kd_rek6 left join ms_rek5 c on left(a.kd_rek6,8)=c.kd_rek5 where a.kd_skpd = ? and left(a.kd_rek6,1)='4' and a.jns_ang=? order by kd_rek6", [$kd_skpd, $status_ang_pend->jns_ang]),
            'daftar_pengirim' => DB::select("SELECT * from ms_pengirim WHERE LEFT(kd_skpd,17)=LEFT(?,17)
                order by cast(kd_pengirim as int)", [$kd_skpd])
        ];

        return view('skpd.restitusi.create')->with($data);
    }

    public function simpan(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        try {
            DB::beginTransaction();

            $total = DB::table('trhkasin_pkd')
                ->where(['no_sts' => $data['no_terima'], 'kd_skpd' => $kd_skpd])
                ->count();

            if ($total > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhkasin_pkd')->insert([
                'no_sts' => $data['no_terima'],
                'tgl_sts' => $data['tgl_terima'],
                'kd_skpd' => $data['kd_skpd'],
                'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                'total' => $data['nilai'],
                'keterangan' => $data['keterangan'],
                'jns_trans' => '3',
                'status' => '1',
                'no_cek' => '1',
                'tgl_kas' => $data['tgl_terima'],
            ]);

            DB::table('trhrestitusi')->insert([
                'no_sts' => $data['no_terima'],
                'tgl_sts' => $data['tgl_terima'],
                'kd_skpd' => $data['kd_skpd'],
                'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                'total' => $data['nilai'],
                'keterangan' => $data['keterangan'],
                'jns_trans' => '3',
                'status' => '1',
                'no_cek' => '1',
                'tgl_kas' => $data['tgl_terima'],
            ]);

            $no_kas = collect(DB::select("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
						select no_kas nomor,'Terima STS' ket from trhkasin_ppkd where isnumeric(no_kas)=1
							UNION ALL
							select no_kas nomor,'Terima STS' ket from trhrestitusi where isnumeric(no_kas)=1
							UNION ALL
							select nomor,'Terima non SP2D' ket from penerimaan_non_sp2d where isnumeric(nomor)=1
							UNION ALL
							select nomor,'keluar non SP2D' ket from pengeluaran_non_sp2d where isnumeric(nomor)=1
							UNION ALL
							select no,'koreksi' ket from trkasout_ppkd where isnumeric(no)=1
								) z"))->first()->nomor;

            DB::table('trhrestitusi')
                ->where(['kd_skpd' => $data['kd_skpd'], 'jns_trans' => '3', 'no_sts' => $data['no_terima']])
                ->update([
                    'no_kas' => $no_kas
                ]);


            DB::table('trdkasin_pkd')->insert([
                'no_sts' => $data['no_terima'],
                'kd_skpd' => $data['kd_skpd'],
                'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                'rupiah' => $data['nilai'],
                'kd_rek6' => $data['rekening'],
                'sumber' => $data['pengirim'],
            ]);

            DB::table('trdrestitusi')->insert([
                'no_sts' => $data['no_terima'],
                'kd_skpd' => $data['kd_skpd'],
                'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                'rupiah' => $data['nilai'],
                'kd_rek6' => $data['rekening'],
                'sumber' => $data['pengirim'],
            ]);

            DB::update("UPDATE a set a.no_kas=b.no_kas
					from trdrestitusi a join trhrestitusi b
					on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
					where a.kd_skpd=? and b.jns_trans=? and a.no_sts=?", [$data['kd_skpd'], '3', $data['no_terima']]);

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

    public function edit($no_kas, $kd_skpd)
    {
        $no_kas = Crypt::decrypt($no_kas);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'rincian' => DB::select("SELECT a.*, (select nm_rek6 from ms_rek6 where kd_rek6 = a.kd_rek6) as nm_rek, b.nm_pengirim from trdrestitusi a left join ms_pengirim b on a.sumber=b.kd_pengirim where a.no_kas = ? order by a.no_kas", [$no_kas]),
            'restitusi' => collect(DB::select("SELECT a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd from trhrestitusi a WHERE a.jns_trans=? AND a.no_kas=? AND a.kd_skpd=?", ['3', $no_kas, $kd_skpd]))->first(),
        ];

        return view('bud.list_restitusi.edit')->with($data);
    }

    public function update(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $total = DB::table('tr_tetap')->where(['no_tetap' => $data['no_tetap'], 'kd_skpd' => $kd_skpd])->count();
            if ($total > 0 && $data['no_tetap'] != $data['no_simpan']) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('tr_tetap')->where(['kd_skpd' => $kd_skpd, 'no_tetap' => $data['no_simpan']])->delete();

            DB::table('tr_tetap')->insert([
                'no_tetap' => $data['no_tetap'],
                'tgl_tetap' => $data['tgl_tetap'],
                'kd_skpd' => $data['kd_skpd'],
                'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                'kd_rek6' => $data['kode_akun'],
                'kd_rek_lo' => $data['kode_rek'],
                'nilai' => $data['nilai'],
                'keterangan' => $data['keterangan'],
                'kanal' => ''
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
        $no_kas = $request->no_kas;
        $no_sts = $request->no_sts;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhkasin_pkd')
                ->where(['no_sts' => $no_sts, 'kd_skpd' => $kd_skpd, 'jns_trans' => '3'])
                ->update([
                    'no_cek' => 0
                ]);

            DB::delete("DELETE from trhrestitusi where no_kas=? and kd_skpd=?", [$no_kas, $kd_skpd]);

            DB::delete("DELETE from trdrestitusi where no_kas=? and kd_skpd=?", [$no_kas, $kd_skpd]);

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
        $no_kas = $request->no_kas;
        $no_sts = $request->no_sts;
        $kd_skpd = $request->kd_skpd;

        $data = [
            'header' => DB::table('config_app')
                ->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')
                ->first(),
            'rincian' => DB::select("SELECT a.*,(SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6 = a.kd_rek6) AS nm_rek6
                    FROM trdkasin_pkd a WHERE no_sts =? AND kd_skpd=?", [$no_sts, $kd_skpd]),
            'restitusi' => collect(DB::select("SELECT a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd,
                (SELECT nama FROM ms_bank WHERE kode = a.kd_bank) AS nm_bank
                FROM trhkasin_pkd a WHERE no_sts = ? AND kd_skpd=?", [$no_sts, $kd_skpd]))->first()
        ];

        $view = view('bud.list_restitusi.cetak')->with($data);

        $pdf = PDF::loadHtml($view)
            ->setPaper('legal');
        return $pdf->stream('laporan.pdf');
    }
}
