<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RestitusiController extends Controller
{
    public function index()
    {
        return view('skpd.restitusi.index');
    }

    public function load()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::select("SELECT a.no_sts,a.tgl_sts,a.kd_skpd,a.keterangan as ket,
		a.total, a.kd_sub_kegiatan, RTRIM(b.kd_rek6)as kd_rek6, b.sumber, a.no_cek, (SELECT a.nm_rek6 FROM ms_rek6 a WHERE a.kd_rek6=b.kd_rek6) nm_rek6 FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
		WHERE b.kd_skpd=? AND a.jns_trans=?
		ORDER BY a.tgl_sts,a.no_sts", [$kd_skpd, '3']);

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("restitusi.edit", ['no_sts' => Crypt::encrypt($row->no_sts), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-primary btn-sm"  style="margin-right:4px"><i class="uil-eye"></i></a>';
            if ($row->no_cek == 1) {
                $btn .= "";
            } else {
                $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_sts . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            }
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
            'daftar_akun' => DB::select("SELECT distinct a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek5, a.kd_sub_kegiatan FROM trdrka a left join ms_rek6 b on a.kd_rek6=b.kd_rek6 left join ms_rek5 c on left(a.kd_rek6,8)=c.kd_rek5 where a.kd_skpd = ? and left(a.kd_rek6,1)='4' and a.jns_ang=? order by kd_rek6", [$kd_skpd, $status_ang_pend->jns_ang]),
            'daftar_pengirim' => DB::select("SELECT * from ms_pengirim WHERE LEFT(kd_skpd,17)=LEFT(?,17)
                order by kd_pengirim", [$kd_skpd])
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

    public function edit($no_sts, $kd_skpd)
    {
        $no_sts = Crypt::decrypt($no_sts);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $status_ang_pend = DB::table('trhrka')
            ->select('jns_ang')
            ->where(['kd_skpd' => $kd_skpd, 'status' => '1'])
            ->orderByDesc('tgl_dpa')
            ->first();

        $data = [
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daftar_akun' => DB::select("SELECT distinct a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek5, a.kd_sub_kegiatan FROM trdrka a left join ms_rek6 b on a.kd_rek6=b.kd_rek6 left join ms_rek5 c on left(a.kd_rek6,8)=c.kd_rek5 where a.kd_skpd = ? and left(a.kd_rek6,1)='4' and a.jns_ang=? order by kd_rek6", [$kd_skpd, $status_ang_pend->jns_ang]),
            'daftar_pengirim' => DB::select("SELECT * from ms_pengirim WHERE LEFT(kd_skpd,17)=LEFT(?,17)
                order by kd_pengirim", [$kd_skpd]),
            'data' => collect(DB::select("SELECT a.no_sts,a.tgl_sts,a.kd_skpd,a.keterangan as ket,
		    a.total, a.kd_sub_kegiatan, RTRIM(b.kd_rek6)as kd_rek6, b.sumber, a.no_cek, (SELECT a.nm_rek6 FROM ms_rek6 a WHERE a.kd_rek6=b.kd_rek6)     nm_rek6 FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b. kd_sub_kegiatan
		    WHERE b.kd_skpd=? AND a.jns_trans=? and b.no_sts=?
		    ORDER BY a.tgl_sts,a.no_sts", [$kd_skpd, '3', $no_sts]))->first()

        ];

        return view('skpd.restitusi.edit')->with($data);
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
        $no_terima = $request->no_terima;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::delete("DELETE b from trdkasin_pkd b inner join trhkasin_pkd a
				on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
				where b.kd_skpd=? and b.no_sts=? and a.jns_trans=?", [$kd_skpd, $no_terima, '3']);

            DB::delete("DELETE from trhkasin_pkd where kd_skpd = ? and no_sts=? and jns_trans=?", [$kd_skpd, $no_terima, '3']);

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
