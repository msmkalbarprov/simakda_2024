<?php

namespace App\Http\Controllers\Skpd\BOS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class Sp2hController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'ttd1' => DB::table('ms_ttd')
                ->select('nip', 'nama')
                ->where(['kd_skpd' => $kd_skpd])
                ->whereIn('kode', ['PA', 'KPA'])
                ->get(),
        ];

        return view('skpd.sp2h.index')->with($data);
    }

    public function load()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = DB::select("SELECT a.* FROM trhsp2h a WHERE a.kd_skpd = ? AND a.jenis = ? ORDER BY a.tgl_sp2h,a.no_sp2h", [$kd_skpd, '1']);

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("sp2h.edit", ['no_sp2h' => Crypt::encrypt($row->no_sp2h), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            if ($row->status == '1' || $row->status == '2') {
                $btn .= "";
            } else {
                $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_sp2h . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            }
            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_sp2h . '\',\'' . $row->jenis . '\',\'' . $row->kd_skpd . '\');" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak LPJ" style="margin-right:4px"><i class="uil-print"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function create()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'no_urut' => no_urut($kd_skpd),
            'daftar_kegiatan' => DB::select("SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan FROM trskpd a INNER JOIN     ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                    where a.kd_skpd=? AND left(a.kd_sub_kegiatan,9) in ('1.01.02.1','4.01.04.1') and status_sub_kegiatan='1' GROUP BY a.kd_sub_kegiatan,b.nm_sub_kegiatan order by a.kd_sub_kegiatan", [$kd_skpd])
        ];

        return view('skpd.sp2h.create')->with($data);
    }

    public function detail(Request $request)
    {
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $kd_skpd = $request->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $satdik = $request->satdik;

        $data = DB::select("SELECT * FROM (
                SELECT b.kd_skpd,b.tgl_bukti,a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.no_bukti,a.nilai,a.kd_skpd as kd_skpd1 FROM trdtransout_blud a inner join trhtransout_blud b on
                   a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
                   WHERE (a.no_bukti+a.kd_sub_kegiatan+a.kd_rek6+a.kd_skpd) NOT IN(SELECT (no_bukti+kd_sub_kegiatan+kd_rek6+kd_skpd) FROM trdsp2h)  AND (b.tgl_bukti BETWEEN ? and ?) and b.kd_satdik=? and b.kd_skpd=? and a.kd_sub_kegiatan=?
                   UNION ALL
                   SELECT kd_skpd,tgl_terima as tgl_bukti,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,no_terima as no_bukti,nilai,kd_skpd as kd_skpd1 FROM tr_terima_bos WHERE (no_terima+kd_sub_kegiatan+kd_rek6+kd_skpd) NOT IN(SELECT (no_bukti+kd_sub_kegiatan+kd_rek6+kd_skpd) FROM trdsp2h) AND kd_skpd=? AND (tgl_terima BETWEEN ? and ?) and kd_satdik=? and left(kd_rek6,1)='4' GROUP BY kd_skpd,tgl_terima,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,no_terima,nilai,kd_skpd
                   UNION ALL
                   SELECT b.kd_skpd,a.tgl_sts,a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.no_sts as no_bukti,a.total,a.kd_skpd as kd_skpd1 FROM trhkasin_pkd_bos a JOIN trdkasin_pkd_bos b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts WHERE (a.no_sts+a.kd_sub_kegiatan+b.kd_rek6+a.kd_skpd) NOT IN(SELECT (no_bukti+kd_sub_kegiatan+kd_rek6+kd_skpd) FROM trdsp2h) AND a.kd_skpd=? and a.kd_sub_kegiatan=? AND (tgl_sts BETWEEN ? and ?) and kd_satdik=? GROUP BY b.kd_skpd,a.tgl_sts,a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.no_sts,a.total,a.kd_skpd
                   )z
                   ORDER BY  kd_skpd,tgl_bukti,kd_sub_kegiatan, kd_rek6, no_bukti", [$tgl_awal, $tgl_akhir, $satdik, $kd_skpd, $kd_sub_kegiatan, $kd_skpd, $tgl_awal, $tgl_akhir, $satdik, $kd_skpd, $kd_sub_kegiatan, $tgl_awal, $tgl_akhir, $satdik]);

        return response()->json($data);
    }

    public function simpan(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $cek = DB::table('trhsp2h')->where(['no_sp2h' => $data['no_sp2h'], 'kd_skpd' => $kd_skpd])->count();
            if ($cek > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhsp2h')
                ->insert([
                    'no_kas' => $data['no_kas'],
                    'no_sp2h' => $data['no_sp2h'],
                    'tgl_sp2h' => $data['tgl_sp2h'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'keterangan' => $data['keterangan'],
                    'jenis' => '1',
                    'status' => '0',
                    'tgl_awal' => $data['tgl_awal'],
                    'tgl_akhir' => $data['tgl_akhir'],
                    'username' => Auth::user()->nama,
                    'last_update' =>  date('Y-m-d H:i:s'),
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    'nm_sub_kegiatan' => $data['nm_sub_kegiatan'],
                    'kd_satdik' => $data['satdik'],
                    'nm_satdik' => $data['nama_satdik'],
                ]);

            $data['detail_sp2h'] = json_decode($data['detail_sp2h'], true);

            $rincian_data = $data['detail_sp2h'];
            $no_sp2h = $data['no_sp2h'];
            $no_kas = $data['no_kas'];

            DB::table('trdsp2h')
                ->where(['no_sp2h' => $no_sp2h, 'kd_skpd' => $kd_skpd])
                ->delete();

            if (isset($rincian_data)) {
                foreach ($rincian_data as $data => $value) {
                    $data = [
                        'no_sp2h' => $no_sp2h,
                        'no_bukti' => $rincian_data[$data]['no_bukti'],
                        'kd_skpd' => $rincian_data[$data]['kd_skpd'],
                        'kd_sub_kegiatan' => $rincian_data[$data]['kd_sub_kegiatan'],
                        'nm_sub_kegiatan' => $rincian_data[$data]['nm_sub_kegiatan'],
                        'kd_rek6' => $rincian_data[$data]['kd_rek6'],
                        'nm_rek6' => $rincian_data[$data]['nm_rek6'],
                        'nilai' => $rincian_data[$data]['nilai'],
                        'no_kas' => $no_kas,
                    ];
                    DB::table('trdsp2h')->insert($data);
                }
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

    public function edit($no_sp2h, $kd_skpd)
    {
        $no_sp2h = Crypt::decrypt($no_sp2h);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'no_urut' => no_urut($kd_skpd),
            'daftar_kegiatan' => DB::select("SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan FROM trskpd a INNER JOIN     ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                    where a.kd_skpd=? AND left(a.kd_sub_kegiatan,9) in ('1.01.02.1','4.01.04.1') and status_sub_kegiatan='1' GROUP BY a.kd_sub_kegiatan,b.nm_sub_kegiatan order by a.kd_sub_kegiatan", [$kd_skpd]),
            'sp2h' => DB::table('trhsp2h')->where(['no_sp2h' => $no_sp2h, 'kd_skpd' => $kd_skpd])->first(),
            'detail_sp2h' => DB::table('trdsp2h')->where(['no_sp2h' => $no_sp2h, 'kd_skpd' => $kd_skpd])->get()
        ];

        return view('skpd.sp2h.edit')->with($data);
    }

    public function update(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $cek = DB::table('trhsp2h')->where(['no_sp2h' => $data['no_sp2h'], 'kd_skpd' => $kd_skpd])->count();
            if ($cek > 0 && $data['no_sp2h'] != $data['no_simpan']) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhsp2h')
                ->where(['no_sp2h' => $data['no_simpan'], 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trhsp2h')
                ->insert([
                    'no_kas' => $data['no_kas'],
                    'no_sp2h' => $data['no_sp2h'],
                    'tgl_sp2h' => $data['tgl_sp2h'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'keterangan' => $data['keterangan'],
                    'jenis' => '1',
                    'status' => '0',
                    'tgl_awal' => $data['tgl_awal'],
                    'tgl_akhir' => $data['tgl_akhir'],
                    'username' => Auth::user()->nama,
                    'last_update' =>  date('Y-m-d H:i:s'),
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    'nm_sub_kegiatan' => $data['nm_sub_kegiatan'],
                    'kd_satdik' => $data['satdik'],
                    'nm_satdik' => $data['nama_satdik'],
                ]);

            $data['detail_sp2h'] = json_decode($data['detail_sp2h'], true);

            $rincian_data = $data['detail_sp2h'];
            $no_sp2h = $data['no_sp2h'];
            $no_kas = $data['no_kas'];

            DB::table('trdsp2h')
                ->where(['no_sp2h' => $data['no_simpan'], 'kd_skpd' => $kd_skpd])
                ->delete();

            if (isset($rincian_data)) {
                foreach ($rincian_data as $data => $value) {
                    $data = [
                        'no_sp2h' => $no_sp2h,
                        'no_bukti' => $rincian_data[$data]['no_bukti'],
                        'kd_skpd' => $rincian_data[$data]['kd_skpd'],
                        'kd_sub_kegiatan' => $rincian_data[$data]['kd_sub_kegiatan'],
                        'nm_sub_kegiatan' => $rincian_data[$data]['nm_sub_kegiatan'],
                        'kd_rek6' => $rincian_data[$data]['kd_rek6'],
                        'nm_rek6' => $rincian_data[$data]['nm_rek6'],
                        'nilai' => $rincian_data[$data]['nilai'],
                        'no_kas' => $no_kas,
                    ];
                    DB::table('trdsp2h')->insert($data);
                }
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

    public function hapus(Request $request)
    {
        $no_sp2h = $request->no_sp2h;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhsp2h')
                ->where(['no_sp2h' => $no_sp2h, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trdsp2h')
                ->where(['no_sp2h' => $no_sp2h, 'kd_skpd' => $kd_skpd])
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

    public function cetak(Request $request)
    {
        $no_sp2h = $request->no_sp2h;
        $kd_skpd = $request->kd_skpd;
        $pa_kpa = $request->pa_kpa;
        $jenis_print = $request->jenis_print;
        $atas = $request->atas;
        $bawah = $request->bawah;
        $kiri = $request->kiri;
        $kanan = $request->kanan;

        $sp2h = collect(DB::select("SELECT a.* FROM trhsp2h a INNER JOIN trdsp2h b ON a.no_sp2h=b.no_sp2h AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd=? AND a.no_sp2h=?", [$kd_skpd, $no_sp2h]))->first();

        $sp2b_pertama = collect(DB::select("SELECT TOP 1 min(b.no_bukti) as no_bukti, a.no_sp2h from trhsp2h a join trdsp2h b on a.kd_skpd=b.kd_skpd and a.no_sp2h=b.no_sp2h WHERE a.kd_skpd=? and a.kd_satdik=? GROUP BY a.no_sp2h", [$kd_skpd, $sp2h->kd_satdik]))->first();

        $tgl_sp2h = $sp2h->tgl_sp2h;
        $satdik = $sp2h->kd_satdik;

        if ($sp2b_pertama->no_sp2h == $no_sp2h) {
            $saldo_awal = DB::table('ms_saldo_awal_bos')
                ->where(['kd_skpd' => $kd_skpd])
                ->first()
                ->nilai;
        } else {
            $saldo_awal_bos = DB::table('ms_saldo_awal_bos')
                ->where(['kd_skpd' => $kd_skpd])
                ->first()
                ->nilai;

            $terima_lalu = collect(DB::select("SELECT sum(nilai)as terima FROM trhsp2h a INNER JOIN trdsp2h b on a.no_sp2h=b.no_sp2h and a.kd_skpd=b.kd_skpd where left(kd_rek6,1)='4' AND a.tgl_sp2h<? and kd_satdik=?", [$tgl_sp2h, $satdik]))->first()->terima;

            $belanja_lalu = collect(DB::select("SELECT sum(nilai)as belanja FROM trhsp2h a INNER JOIN trdsp2h b on a.no_sp2h=b.no_sp2h and a.kd_skpd=b.kd_skpd where left(kd_rek6,1)='5' AND a.tgl_sp2h<? and kd_satdik=?", [$tgl_sp2h, $satdik]))->first()->belanja;

            $kembali_lalu = collect(DB::select("SELECT sum(nilai)as kembali FROM trhsp2h a INNER JOIN trdsp2h b on a.no_sp2h=b.no_sp2h and a.kd_skpd=b.kd_skpd where left(kd_rek6,4)='1101' AND a.tgl_sp2h<? and kd_satdik=?", [$tgl_sp2h, $satdik]))->first()->kembali;

            $saldo_awal = ($saldo_awal_bos + $terima_lalu) - ($belanja_lalu + $kembali_lalu);
        }

        $terima = collect(DB::select("SELECT a.kd_skpd,a.nm_skpd, a.no_sp2h, left(b.kd_rek6,1)rek,  sum(nilai)as terima FROM trhsp2h a INNER JOIN trdsp2h b on a.no_sp2h=b.no_sp2h and a.kd_skpd=b.kd_skpd
                            where a.no_sp2h=? and left(kd_rek6,1)=? and kd_satdik=?
                            group by a.kd_skpd,nm_skpd,a.no_sp2h,left(b.kd_rek6,1)", [$no_sp2h, '4', $satdik]))->first();

        $belanja_pegawai = collect(DB::select("SELECT a.kd_skpd,nm_skpd, a.no_sp2h, left(kd_rek6,4)rek,  sum(nilai)as belanja FROM trhsp2h a INNER JOIN trdsp2h b on a.no_sp2h=b.no_sp2h and a.kd_skpd=b.kd_skpd
                            where a.no_sp2h=? and left(kd_rek6,4)=? and kd_satdik=?
                            group by a.kd_skpd,nm_skpd,a.no_sp2h,left(kd_rek6,4)", [$no_sp2h, '5101', $satdik]))->first();

        $belanja_barang = collect(DB::select("SELECT a.kd_skpd,nm_skpd, a.no_sp2h, left(kd_rek6,4)rek,  sum(nilai)as belanja FROM trhsp2h a INNER JOIN trdsp2h b on a.no_sp2h=b.no_sp2h and a.kd_skpd=b.kd_skpd
                            where a.no_sp2h=? and left(kd_rek6,4)=? and kd_satdik=?
                            group by a.kd_skpd,nm_skpd,a.no_sp2h,left(kd_rek6,4)", [$no_sp2h, '5102', $satdik]))->first();

        $belanja_modal = collect(DB::select("SELECT a.kd_skpd,nm_skpd, a.no_sp2h, left(kd_rek6,2)rek,  sum(nilai)as belanja FROM trhsp2h a INNER JOIN trdsp2h b on a.no_sp2h=b.no_sp2h and a.kd_skpd=b.kd_skpd
                            where a.no_sp2h=? and left(kd_rek6,2)=? and kd_satdik=?
                            group by a.kd_skpd,nm_skpd,a.no_sp2h,left(kd_rek6,2)", [$no_sp2h, '52', $satdik]))->first();

        $pengembalian = collect(DB::select("SELECT a.kd_skpd,a.nm_skpd, a.no_sp2h, left(b.kd_rek6,4)rek,  sum(nilai)as kembali FROM trhsp2h a INNER JOIN trdsp2h b on a.no_sp2h=b.no_sp2h and a.kd_skpd=b.kd_skpd
                            where a.no_sp2h=? and left(kd_rek6,4)=? and kd_satdik=?
                            group by a.kd_skpd,nm_skpd,a.no_sp2h,left(b.kd_rek6,4)", [$no_sp2h, '1101', $satdik]))->first();

        $data = [
            'header' => DB::table('config_app')
                ->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')
                ->first(),
            'skpd' => DB::table('ms_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'daerah' => DB::table('sclient')
                ->select('kab_kota', 'daerah', 'nogub_susun', 'nogub_perubahan', 'nogub_p1', 'nogub_p2', 'nogub_p3', 'nogub_p4', 'nogub_p5', 'nogub_perubahan', 'nogub_perubahan2', 'nogub_perubahan3', 'nogub_perubahan4', 'nogub_perubahan5')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'sp2h' => DB::table('trhsp2h')->where(['no_sp2h' => $no_sp2h, 'kd_skpd' => $kd_skpd])->first(),
            'saldo_awal' => $saldo_awal,
            'terima' => isset($terima) ? $terima->terima : 0,
            'belanja_pegawai' => isset($belanja_pegawai) ? $belanja_pegawai->belanja : 0,
            'belanja_barang' => isset($belanja_barang) ? $belanja_barang->belanja : 0,
            'belanja_modal' => isset($belanja_modal) ? $belanja_modal->belanja : 0,
            'pengembalian' => isset($pengembalian) ? $pengembalian->kembali : 0,
            'dpa' => DB::table('trhrka')->where(['kd_skpd' => $kd_skpd])->first(),
            'dppa' => collect(DB::select("SELECT  TOP 1 no_dpa ,tgl_dpa from (
                                select 1 urut, kd_skpd,tgl_dpa,no_dpa,[status] as [status] from trhrka where kd_skpd=?
                                )z where status=1 order by urut desc", [$kd_skpd]))->first(),
            'data_program' => collect(DB::select("SELECT left(b.kd_sub_kegiatan,7)as prog,left(b.kd_sub_kegiatan,12)as giat,b.kd_sub_kegiatan as subgiat FROM trhsp2h a INNER JOIN trdsp2h b on a.no_sp2h=b.no_sp2h and a.kd_skpd=b.kd_skpd where a.no_sp2h=? and a.kd_skpd=? group by left(b.kd_sub_kegiatan,7),left(b.kd_sub_kegiatan,12),b.kd_sub_kegiatan", [$no_sp2h, $kd_skpd]))->first(),
            'daerah' => DB::table('sclient')
                ->select('kab_kota', 'daerah', 'nogub_susun', 'nogub_perubahan', 'nogub_p1', 'nogub_p2', 'nogub_p3', 'nogub_p4', 'nogub_p5', 'nogub_perubahan', 'nogub_perubahan2', 'nogub_perubahan3', 'nogub_perubahan4', 'nogub_perubahan5')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'pa_kpa' => DB::table('ms_ttd')->where(['nip' => $pa_kpa, 'kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->first(),
            'detail_sp2h' => DB::select("SELECT a.kd_skpd,nm_skpd, a.no_sp2h, kd_rek6, 0 terima, sum(nilai)as belanja FROM trhsp2h a INNER JOIN trdsp2h b on a.no_sp2h=b.no_sp2h and a.kd_skpd=b.kd_skpd where a.no_sp2h=? and a.kd_skpd=? and a.kd_satdik=? group by a.kd_skpd,nm_skpd,a.no_sp2h,kd_rek6", [$no_sp2h, $kd_skpd, $satdik])
        ];


        $view = view('skpd.sp2h.cetak.cetakan')->with($data);

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
