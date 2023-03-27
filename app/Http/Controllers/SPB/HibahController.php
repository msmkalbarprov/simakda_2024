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

class HibahController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'ttd1' => DB::table('ms_ttd')
                ->select('nip', 'nama')
                ->whereIn('kode', ['BUD'])
                ->get(),
        ];

        return view('bud.spb_hibah.index')->with($data);
    }

    public function load()
    {
        $data = DB::select("SELECT *
                FROM trhspb_hibah a
                ORDER BY a.tgl_spb_hibah,a.no_spb_hibah");

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("spb_hibah.edit", ['no_spb_hibah' => Crypt::encrypt($row->no_spb_hibah), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';

            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_spb_hibah . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';

            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_spb_hibah . '\',\'' . $row->kd_skpd . '\');" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak LPJ" style="margin-right:4px"><i class="uil-print"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function create()
    {
        $urut = collect(DB::select("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
        select no_urut nomor,'SPB BOS' ket from trspb where isnumeric(no_urut)=1
        UNION ALL
        select no_urut nomor,'SPB HIBAH' ket from trhspb_hibah where isnumeric(no_urut)=1
        ) z"))->first()->nomor;

        $angka = [1, 2, 3, 4, 5, 6, 7, 8, 9];

        if (in_array($urut, $angka)) {
            $no_spb = '0' . $urut . '/SPB-HIBAH' . '/' . tahun_anggaran();
        } else {
            $no_spb = $urut . '/SPB-HIBAH' . '/' . tahun_anggaran();
        }

        $data = [
            'daftar_skpd' => DB::select("SELECT kd_skpd,nm_skpd FROM ms_skpd ORDER BY kd_skpd"),
            'daftar_rekening' => DB::select("SELECT kd_rek6,nm_rek6 from ms_rek6 where nm_rek6 like '%uang dana bos%' and left(kd_rek6,1)=?", ['5']),
            'daftar_kategori' => DB::select("SELECT DISTINCT kategori from ms_satdik where kategori<>''"),
            'no_urut' => $urut,
            'no_spb' => $no_spb
        ];

        return view('bud.spb_hibah.create')->with($data);
    }

    public function kegiatan(Request $request)
    {
        $kd_skpd = $request->kd_skpd;

        $data = DB::select("SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                WHERE a.kd_skpd=? AND a.status_sub_kegiatan=? AND left(a.kd_sub_kegiatan,9) in (?,?) GROUP BY a.kd_sub_kegiatan,b.nm_sub_kegiatan", [$kd_skpd, '1', '1.01.02.1', '4.01.04.1']);

        return response()->json($data);
    }

    public function nomor(Request $request)
    {
        $no_sp2h_all = $request->no_sp2h;

        $no_sp2h = array();
        if (!empty($no_sp2h_all)) {
            foreach ($no_sp2h_all as $lpj) {
                $no_sp2h[] = $lpj['no_sp2h'];
            }
        } else {
            $no_sp2h[] = '';
        }

        $data = DB::table('trhsp2h as a')
            ->whereRaw("a.no_sp2h NOT IN (SELECT no_sp2h from trdspb_hibah)")
            ->orderBy('a.tgl_sp2h')
            ->orderBy('a.no_sp2h')
            ->get();

        return response()->json($data);
    }

    public function simpan(Request $request)
    {
        $data = $request->data;

        try {
            DB::beginTransaction();

            $cek = DB::table('trhspb_hibah')
                ->where(['no_spb_hibah' => $data['no_spb'], 'kd_skpd' => $data['kd_skpd']])
                ->count();

            if ($cek > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhspb_hibah')
                ->where(['no_spb_hibah' => $data['no_spb'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            DB::table('trhspb_hibah')
                ->insert([
                    'no_spb_hibah' => $data['no_spb'],
                    'tgl_spb_hibah' => $data['tgl_spb'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'username' => Auth::user()->nama,
                    'last_update' => date('Y-m-d H:i:s'),
                    'tgl_sp2h' => $data['tgl_spb'],
                    'kategori' => $data['kategori'],
                    'gelombang' => $data['gelombang'],
                    'total' => $data['total'],
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    'nm_sub_kegiatan' => $data['nm_sub_kegiatan'],
                    'kd_rek6' => $data['kd_rek6'],
                    'nm_rek6' => $data['nm_rek6'],
                    'tahapan' => $data['tahapan'],
                    'no_urut' => $data['no_urut'],
                ]);

            DB::table('trdspb_hibah')
                ->where(['no_spb_hibah' => $data['no_spb'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            $data['detail_spb'] = json_decode($data['detail_spb'], true);

            if (isset($data['detail_spb'])) {
                DB::table('trdspb_hibah')->insert(array_map(function ($value) use ($data) {
                    return [
                        'no_spb_hibah' => $data['no_spb'],
                        'no_sp2h' => $value['no_sp2h'],
                        'kd_skpd' => $data['kd_skpd'],
                        'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                        'nm_sub_kegiatan' => $data['nm_sub_kegiatan'],
                        'kd_rek6' => $data['kd_rek6'],
                        'nm_rek6' => $data['nm_rek6'],
                        'nilai' => $value['nilai'],
                        'kd_satdik' => $value['kd_satdik'],
                        'nm_satdik' => $value['nm_satdik'],
                    ];
                }, $data['detail_spb']));
            }

            $no_sp2h = [];
            foreach ($data['detail_spb'] as $detail_spb) {
                $no_sp2h[] = $detail_spb['no_sp2h'];
            }

            DB::table('trhsp2h')
                ->where(['kd_skpd' => $data['kd_skpd']])
                ->whereIn('no_sp2h', $no_sp2h)
                ->update([
                    'status' => 1
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

    public function edit($no_spb_hibah, $kd_skpd)
    {
        $no_spb_hibah = Crypt::decrypt($no_spb_hibah);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'daftar_skpd' => DB::select("SELECT kd_skpd,nm_skpd FROM ms_skpd ORDER BY kd_skpd"),
            'daftar_rekening' => DB::select("SELECT kd_rek6,nm_rek6 from ms_rek6 where nm_rek6 like '%uang dana bos%' and left(kd_rek6,1)=?", ['5']),
            'daftar_kategori' => DB::select("SELECT DISTINCT kategori from ms_satdik where kategori<>''"),
            'spb' => DB::table('trhspb_hibah as a')
                ->join('trdspb_hibah as b', function ($join) {
                    $join->on('a.no_spb_hibah', '=', 'b.no_spb_hibah');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('a.*')
                ->where(['a.no_spb_hibah' => $no_spb_hibah, 'a.kd_skpd' => $kd_skpd])
                ->first(),
            'detail_spb' => DB::table('trhspb_hibah as a')
                ->join('trdspb_hibah as b', function ($join) {
                    $join->on('a.no_spb_hibah', '=', 'b.no_spb_hibah');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('b.*')
                ->where(['a.no_spb_hibah' => $no_spb_hibah, 'a.kd_skpd' => $kd_skpd])
                ->get()
        ];

        return view('bud.spb_hibah.edit')->with($data);
    }

    public function update(Request $request)
    {
        $data = $request->data;

        try {
            DB::beginTransaction();

            DB::table('trhspb_hibah')
                ->where(['no_spb_hibah' => $data['no_spb'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            DB::table('trhspb_hibah')
                ->insert([
                    'no_spb_hibah' => $data['no_spb'],
                    'tgl_spb_hibah' => $data['tgl_spb'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'username' => Auth::user()->nama,
                    'last_update' => date('Y-m-d H:i:s'),
                    'tgl_sp2h' => $data['tgl_spb'],
                    'kategori' => $data['kategori'],
                    'gelombang' => $data['gelombang'],
                    'total' => $data['total'],
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    'nm_sub_kegiatan' => $data['nm_sub_kegiatan'],
                    'kd_rek6' => $data['kd_rek6'],
                    'nm_rek6' => $data['nm_rek6'],
                    'tahapan' => $data['tahapan'],
                    'no_urut' => $data['no_urut'],
                ]);

            DB::table('trdspb_hibah')
                ->where(['no_spb_hibah' => $data['no_spb'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            $data['detail_spb'] = json_decode($data['detail_spb'], true);

            if (isset($data['detail_spb'])) {
                DB::table('trdspb_hibah')->insert(array_map(function ($value) use ($data) {
                    return [
                        'no_spb_hibah' => $data['no_spb'],
                        'no_sp2h' => $value['no_sp2h'],
                        'kd_skpd' => $data['kd_skpd'],
                        'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                        'nm_sub_kegiatan' => $data['nm_sub_kegiatan'],
                        'kd_rek6' => $data['kd_rek6'],
                        'nm_rek6' => $data['nm_rek6'],
                        'nilai' => $value['nilai'],
                        'kd_satdik' => $value['kd_satdik'],
                        'nm_satdik' => $value['nm_satdik'],
                    ];
                }, $data['detail_spb']));
            }

            $no_sp2h = [];
            foreach ($data['detail_spb'] as $detail_spb) {
                $no_sp2h[] = $detail_spb['no_sp2h'];
            }

            DB::table('trhsp2h')
                ->where(['kd_skpd' => $data['kd_skpd']])
                ->whereIn('no_sp2h', $no_sp2h)
                ->update([
                    'status' => 1
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
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            $data = DB::table('trhspb_hibah as a')
                ->join('trdspb_hibah as b', function ($join) {
                    $join->on('a.no_spb_hibah', '=', 'b.no_spb_hibah');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('b.no_sp2h')
                ->where(['a.no_spb_hibah' => $no_spb, 'a.kd_skpd' => $kd_skpd])
                ->get()
                ->toArray();

            $no_sp2h = [];
            foreach ($data as $data1) {
                $no_sp2h[] = $data1->no_sp2h;
            }

            DB::table('trhspb_hibah')
                ->where(['no_spb_hibah' => $no_spb, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trdspb_hibah')
                ->where(['no_spb_hibah' => $no_spb, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trhsp2h')
                ->whereIn('no_sp2h', $no_sp2h)
                ->where(['kd_skpd' => $kd_skpd])
                ->update([
                    'status' => 0
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
        $tgl_spb = $request->tgl_spb;
        $kd_skpd = $request->kd_skpd;
        $bud = $request->bud;
        $jenis_print = $request->jenis_print;
        $atas = $request->atas;
        $bawah = $request->bawah;
        $kiri = $request->kiri;
        $kanan = $request->kanan;


        $data = [
            'header' => DB::table('config_app')
                ->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')
                ->first(),
            'skpd' => DB::table('ms_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'bud' => DB::table('ms_ttd')->where(['nip' => $bud])->whereIn('kode', ['BUD'])->first(),
            'daerah' => DB::table('sclient')->select('tgl_rka', 'provinsi', 'kab_kota', 'daerah', 'thn_ang', 'nogub_susun', 'nogub_p1', 'nogub_p2', 'nogub_p3', 'nogub_p4', 'nogub_p5', 'nogub_perubahan', 'nogub_perubahan2', 'nogub_perubahan3', 'nogub_perubahan4', 'nogub_perubahan5')->where('kd_skpd', $kd_skpd)->first(),
            'tanggal' => $tgl_spb,
            'spb' => DB::table('trhspb_hibah as a')
                ->join('trdspb_hibah as b', function ($join) {
                    $join->on('a.no_spb_hibah', '=', 'b.no_spb_hibah');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->select('a.*')
                ->where(['a.no_spb_hibah' => $no_spb, 'a.kd_skpd' => $kd_skpd])
                ->first(),
            'nilai_spb' => collect(DB::select("SELECT a.kd_skpd,a.nm_skpd,a.kategori,ISNULL(SUM(b.nilai),0) as rupiah
                            from trhspb_hibah a inner join
                                     trdspb_hibah b on a.no_spb_hibah=b.no_spb_hibah
                                and a.kd_skpd=b.kd_skpd
                            where a.kd_skpd=? and a.no_spb_hibah=?
                            group by a.kd_skpd,a.nm_skpd,a.kategori", [$kd_skpd, $no_spb]))->first()
        ];


        $view = view('bud.spb_hibah.cetak.cetakan')->with($data);

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
