<?php

namespace App\Http\Controllers\SPB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class BosController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'ttd1' => DB::table('ms_ttd')
                ->select('nip', 'nama')
                ->where(['kd_skpd' => $kd_skpd])
                ->whereIn('kode', ['PA', 'BUD'])
                ->get(),
        ];

        return view('bud.spb_bos.index')->with($data);
    }

    public function load()
    {
        $data = DB::select("SELECT a.*,isnull(b.no_spb,0)no_spbs,b.no_spb,b.tgl_spb,b.no_urut
                 FROM trhsp2b a
                 LEFT JOIN trspb b on a.no_sp2b=b.no_sp2b and a.kd_skpd=b.kd_skpd
                 WHERE  a.jenis = ?
                ORDER BY a.tgl_sp2b,a.no_sp2b", ['1']);

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("spb_bos.edit", ['no_sp2b' => Crypt::encrypt($row->no_sp2b), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_spb . '\',\'' . $row->no_sp2b . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_spb . '\',\'' . $row->kd_skpd . '\');" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak LPJ" style="margin-right:4px"><i class="uil-print"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function edit($no_sp2b, $kd_skpd)
    {
        $no_sp2b = Crypt::decrypt($no_sp2b);
        $kd_skpd = Crypt::decrypt($kd_skpd);


        $urut = collect(DB::select("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
        select no_urut nomor,'SPB BOS' ket from trspb where isnumeric(no_urut)=1
        UNION ALL
        select no_urut nomor,'SPB HIBAH' ket from trhspb_hibah where isnumeric(no_urut)=1
        ) z"))->first()->nomor;

        $angka = [1, 2, 3, 4, 5, 6, 7, 8, 9];

        if (in_array($urut, $angka)) {
            $no_spb = '0' . $urut . '/SPB-BOS' . '/' . tahun_anggaran();
        } else {
            $no_spb = $urut . '/SPB-BOS' . '/' . tahun_anggaran();
        }


        $data = [
            'no_spb' => $no_spb,
            'no_urut' => $urut,
            'spb' => collect(DB::select("SELECT a.*,isnull(b.no_spb,0)no_spbs,b.no_spb,b.tgl_spb,b.no_urut
                 FROM trhsp2b a
                 LEFT JOIN trspb b on a.no_sp2b=b.no_sp2b and a.kd_skpd=b.kd_skpd
                 WHERE  a.jenis = ? AND a.no_sp2b =? AND a.kd_skpd=?
                ORDER BY a.tgl_sp2b,a.no_sp2b", ['1', $no_sp2b, $kd_skpd]))->first(),
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'daftar_kegiatan' => DB::select("SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan where a.kd_skpd=? AND left(a.kd_sub_kegiatan,9) in ('1.01.02.1','4.01.04.1') and status_sub_kegiatan='1' GROUP BY a.kd_sub_kegiatan,b.nm_sub_kegiatan order by a.kd_sub_kegiatan", [$kd_skpd]),
            'sp2b' => DB::table('trhsp2b')->where(['no_sp2b' => $no_sp2b, 'kd_skpd' => $kd_skpd])->first(),
            'detail_sp2b' => DB::table('trdsp2b')->where(['no_sp2b' => $no_sp2b, 'kd_skpd' => $kd_skpd])->get(),
        ];

        return view('bud.spb_bos.show')->with($data);
    }

    public function simpan(Request $request)
    {
        $data = $request->data;

        DB::beginTransaction();
        try {
            $cek = DB::table('trspb')->where(['no_spb' => $data['no_spb'], 'kd_skpd' => $data['kd_skpd']])->count();
            if ($cek > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trspb')
                ->where(['no_spb' => $data['no_spb'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            DB::table('trspb')
                ->insert([
                    'no_spb' => $data['no_spb'],
                    'tgl_spb' => $data['tgl_spb'],
                    'no_sp2b' => $data['no_sp2b'],
                    'tgl_sp2b' => $data['tgl_sp2b'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'username' => Auth::user()->nama,
                    'last_update' =>  date('Y-m-d H:i:s'),
                    'no_urut' => $data['no_urut']
                ]);

            DB::table('trhsp2b')
                ->where(['no_sp2b' => $data['no_sp2b']])
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

    public function hapus(Request $request)
    {
        $no_spb = $request->no_spb;
        $no_sp2b = $request->no_sp2b;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trspb')
                ->where(['no_spb' => $no_spb, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trhsp2b')
                ->where(['no_sp2b' => $no_sp2b])
                ->update([
                    'status' => '0'
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

    public function cetak(Request $request)
    {
        $no_spb = $request->no_spb;
        $kd_skpd = $request->kd_skpd;
        $bud = $request->bud;
        $jenis_print = $request->jenis_print;
        $atas = $request->atas;
        $bawah = $request->bawah;
        $kiri = $request->kiri;
        $kanan = $request->kanan;

        $spb = DB::table('trspb')->where(['no_spb' => $no_spb, 'kd_skpd' => $kd_skpd])->first();

        $belanja_pegawai = collect(DB::select("SELECT a.kd_skpd,nm_skpd, a.no_sp2b, left(kd_rek6,4)rek,  sum(nilai)as nilai FROM trhsp2b a INNER JOIN trdsp2b b on a.no_sp2b=b.no_sp2b and a.kd_skpd=b.kd_skpd where a.no_sp2b=? and left(kd_rek6,4)=? group by a.kd_skpd,nm_skpd,a.no_sp2b,left(kd_rek6,4)", [$spb->no_sp2b, '5101']))->first();

        $belanja_barang = collect(DB::select("SELECT a.kd_skpd,nm_skpd, a.no_sp2b, left(kd_rek6,4)rek,  sum(nilai)as nilai FROM trhsp2b a INNER JOIN trdsp2b b on a.no_sp2b=b.no_sp2b and a.kd_skpd=b.kd_skpd where a.no_sp2b=? and left(kd_rek6,4)=? group by a.kd_skpd,nm_skpd,a.no_sp2b,left(kd_rek6,4)", [$spb->no_sp2b, '5102']))->first();

        $belanja_modal = collect(DB::select("SELECT a.kd_skpd,nm_skpd, a.no_sp2b, left(kd_rek6,2)rek,  sum(nilai)as nilai FROM trhsp2b a INNER JOIN trdsp2b b on a.no_sp2b=b.no_sp2b and a.kd_skpd=b.kd_skpd where a.no_sp2b=? and left(kd_rek6,2)=? group by a.kd_skpd,nm_skpd,a.no_sp2b,left(kd_rek6,2)", [$spb->no_sp2b, '52']))->first();

        $kembali = collect(DB::select("SELECT a.kd_skpd,nm_skpd, a.no_sp2b, left(kd_rek6,4)rek,  sum(nilai)as nilai FROM trhsp2b a INNER JOIN trdsp2b b on a.no_sp2b=b.no_sp2b and a.kd_skpd=b.kd_skpd where a.no_sp2b=? and left(kd_rek6,4)=? group by a.kd_skpd,nm_skpd,a.no_sp2b,left(kd_rek6,4)", [$spb->no_sp2b, '1101']))->first();

        $data = [
            'header' => DB::table('config_app')
                ->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')
                ->first(),
            'skpd' => DB::table('ms_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'spb' => $spb,
            'bud' => DB::table('ms_ttd')->where(['nip' => $bud, 'kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'BUD'])->first(),
            'belanja_pegawai' => isset($belanja_pegawai) ? $belanja_pegawai->nilai : 0,
            'belanja_barang' => isset($belanja_barang) ? $belanja_barang->nilai : 0,
            'belanja_modal' => isset($belanja_modal) ? $belanja_modal->nilai : 0,
            'kembali' => isset($kembali) ? $kembali->nilai : 0,
            'daerah' => DB::table('sclient')->select('tgl_rka', 'provinsi', 'kab_kota', 'daerah', 'thn_ang', 'nogub_susun', 'nogub_p1', 'nogub_p2', 'nogub_p3', 'nogub_p4', 'nogub_p5', 'nogub_perubahan', 'nogub_perubahan2', 'nogub_perubahan3', 'nogub_perubahan4', 'nogub_perubahan5')->where('kd_skpd', $kd_skpd)->first()
        ];


        $view = view('bud.spb_bos.cetak.cetakan')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-top', $atas)
                ->setOption('margin-left', $kiri)
                ->setOption('margin-right', $kanan)
                ->setOption('margin-bottom', $bawah);
            return $pdf->stream('laporan.pdf');
        } else {
            return $view;
        }
    }
}
