<?php

namespace App\Http\Controllers\Skpd\Panjar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransaksiPanjarController extends Controller
{
    public function index()
    {
        $cek1 = selisih_angkas();

        $status_ang = $cek1['status_ang'];
        $status_angkas = $cek1['status_angkas'];

        $cek = DB::table('tb_status_angkas')
            ->whereRaw("left(jns_angkas,2)=? and kode=? and status=?", [$status_ang, $status_angkas, '1'])
            ->count();

        $data = [
            'cek' => selisih_angkas(),
            'cek1' => $cek
        ];

        if ($cek  == 0) {
            return view('skpd.transaksi_panjar.index')
                ->with($data)
                ->with('message', 'Jenis Anggaran tidak sama dengan Jenis Anggaran Kas!');
        }

        return view('skpd.transaksi_panjar.index');
    }

    public function load()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $spjbulan = cek_status_spj($kd_skpd);

        $data = DB::table('trhtransout as a')
            ->selectRaw("a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,
                (SELECT COUNT(*) from trlpj z where z.no_bukti = a.no_bukti and z.kd_skpd = a.kd_skpd) ketlpj,
                (CASE WHEN month(a.tgl_bukti)<=? THEN 1 ELSE 0 END ) ketspj", [$spjbulan])
            ->whereRaw("a.panjar = '1' AND a.kd_skpd=?", [$kd_skpd])
            ->orderBy('a.no_bukti')
            ->orderBy('kd_skpd')
            ->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            // $btn = '<a href="' . route("transaksipanjar.edit", ['no_bukti' => Crypt::encrypt($row->no_bukti), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            if ($row->ketlpj == 1 || $row->ketspj == 1 || $row->ketlpj == 2) {
                $btn = "";
            } else {
                $btn = '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_bukti . '\',\'' . $row->no_kas . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            }
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambah()
    {
        $kd_skpd = Auth::user()->kd_skpd;

        $data = [
            'no_urut' => no_urut($kd_skpd),
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'daftar_panjar' => DB::select("SELECT a.no_panjar_lalu, ISNULL(nilai,0) nilai, ISNULL(kembali,0) as kembali
                 FROM (
                SELECT no_panjar_lalu, SUM(nilai) as nilai
                FROM tr_panjar WHERE no_panjar_lalu IN
                (select no_panjar
                From tr_panjar WHERE kd_skpd=?
                AND jns='1' AND status='1')
                AND kd_skpd=?
                GROUP BY no_panjar_lalu) a
                LEFT JOIN(
                SELECT no_panjar, SUM(nilai) as kembali
                 FROM tr_jpanjar WHERE kd_skpd = ?
                AND jns='2'
                GROUP BY no_panjar) b
                ON a.no_panjar_lalu=b.no_panjar
                ORDER BY no_panjar_lalu", [$kd_skpd, $kd_skpd, $kd_skpd])
        ];

        DB::table('tb_transaksi')
            ->where(['kd_skpd' => $kd_skpd, 'username' => Auth::user()->nama])
            ->delete();

        return view('skpd.transaksi_panjar.create')->with($data);
    }

    public function kegiatan(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_panjar = $request->no_panjar;

        $data = DB::select("SELECT a.kd_sub_kegiatan,(SELECT nm_sub_kegiatan FROM ms_sub_kegiatan WHERE kd_sub_kegiatan=a.kd_sub_kegiatan AND kd_skpd=?) as nm_sub_kegiatan  FROM tr_panjar a WHERE  a.no_panjar=? AND a.kd_skpd=?", [$kd_skpd, $no_panjar, $kd_skpd]);

        return response()->json($data);
    }

    public function sp2d(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $beban = $request->beban;

        if ($beban == '1') {
            $data = DB::select("SELECT
                    a.no_sp2d AS no_sp2d,
                    a.tgl_sp2d AS tgl_sp2d
                FROM
                    trhsp2d a
                INNER JOIN trhspp b ON a.no_spp = b.no_spp AND a.kd_skpd = b.kd_skpd
                INNER JOIN trdspp c ON c.no_spp = b.no_spp AND c.kd_skpd = b.kd_skpd
                WHERE
                    left(a.kd_skpd,17) = left(?,17) AND a.jns_spp IN ('1', '2')
                GROUP BY
                a.no_sp2d,
                a.tgl_sp2d", [$kd_skpd]);
        } else {
            $data = DB::select("SELECT a.no_sp2d as no_sp2d, a.tgl_sp2d as tgl_sp2d FROM trhsp2d a INNER JOIN trhspp b on a.no_spp = b.no_spp
                AND a.kd_skpd=b.kd_skpd
                INNER JOIN (SELECT no_spp,kd_skpd, kd_sub_kegiatan FROM trdspp WHERE kd_skpd = ?
                AND kd_sub_kegiatan = ? GROUP BY no_spp,kd_skpd,kd_sub_kegiatan) c
                ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp
                where c.kd_sub_kegiatan = ? AND a.kd_skpd = ? and a.jns_spp = ?", [$kd_skpd, $kd_sub_kegiatan, $kd_sub_kegiatan, $kd_skpd, $beban]);
        }

        return response()->json($data);
    }

    public function rekening(Request $request)
    {
        $no_panjar = $request->no_panjar;
        $no_bukti = $request->no_bukti;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_skpd = $request->kd_skpd;
        $beban = $request->beban;
        $no_sp2d = $request->no_sp2d;

        $status_anggaran = status_anggaran();

        if ($beban == '1') {
            $data = DB::select("SELECT a.kd_rek6,a.nm_rek6,
                        (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti AND c.kd_skpd=d.kd_skpd
                        WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan AND d.kd_skpd=a.kd_skpd  AND c.no_bukti <> ? AND d.panjar = '1'
                        AND d.no_panjar = ?) AS panjar_lalu,
                        (SELECT SUM(nilai) FROM
                        (SELECT SUM (c.nilai) as nilai
                        FROM trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND d.kd_skpd = a.kd_skpd
                        AND c.kd_rek6 = a.kd_rek6
                        AND c.no_bukti <> ?
                        AND d.jns_spp=?
                        UNION ALL
                        SELECT SUM(x.nilai) as nilai FROM trdspp x
                        INNER JOIN trhspp y
                        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                        WHERE x.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND x.kd_skpd = a.kd_skpd
                        AND x.kd_rek6 = a.kd_rek6
                        AND y.jns_spp IN ('3','4','5','6')
                        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t
                        INNER JOIN trhtagih u
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE t.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek6
                        AND u.no_bukti
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd=? )
                        UNION ALL
                        SELECT SUM(v.nilai) as nilai FROM tb_transaksi v
                        WHERE v.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND v.kd_skpd = a.kd_skpd
                        AND v.kd_rek6 = a.kd_rek6
                        )r) as lalu,0 as sp2d,
                        nilai as nilai
                        FROM trdrka a WHERE a.kd_sub_kegiatan= ? AND a.kd_skpd = ? and a.status_aktif='1' and jns_ang=?", [$no_bukti, $no_panjar, $no_bukti, $beban, $kd_skpd, $kd_sub_kegiatan, $kd_skpd, $status_anggaran]);
        } else {
            $data = DB::select("SELECT b.kd_rek6,b.nm_rek6,
                (SELECT ISNULL(SUM(c.nilai),0) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti AND c.kd_skpd=d.kd_skpd
                WHERE c.kd_sub_kegiatan = ? AND d.kd_skpd=?  AND c.no_bukti <> ? AND d.panjar = '1'
                AND d.no_panjar = ?) AS panjar_lalu,
                    (SELECT ISNULL(SUM(c.nilai),0) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti AND c.kd_skpd=d.kd_skpd
                    WHERE c.kd_sub_kegiatan = ? AND
                    d.kd_skpd=?
                    AND c.kd_rek6=b.kd_rek6 AND c.no_bukti <> ? AND d.jns_spp = ? and c.no_sp2d = ?) AS lalu,
                    b.nilai AS sp2d,
                    0 AS nilai
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd
                    INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
                    INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                    INNER JOIN trdrka f ON b.kd_bidang=f.kd_skpd and b.kd_sub_kegiatan=f.kd_sub_kegiatan and b.kd_rek6=f.kd_rek6
                    WHERE d.no_sp2d = ? and b.kd_sub_kegiatan=? and f.status_aktif='1'", [$kd_sub_kegiatan, $kd_skpd, $no_bukti, $no_panjar, $kd_sub_kegiatan, $kd_skpd, $no_bukti, $beban, $no_sp2d, $no_sp2d, $kd_sub_kegiatan]);
        }

        return response()->json($data);
    }

    public function angkasSpd(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_rek6 = $request->kd_rek6;
        $tgl_kas = $request->tgl_kas;
        $beban = $request->beban;
        $no_sp2d = $request->no_sp2d;
        $bulan = date('m', strtotime($tgl_kas));
        $sts_angkas = $request->status_angkas;

        if ($sts_angkas == 'murni') {
            $field_angkas = 'nilai_susun';
        } else if ($sts_angkas == 'murni_geser1') {
            $field_angkas = 'nilai_susun1';
        } else if ($sts_angkas == 'murni_geser2') {
            $field_angkas = 'nilai_susun2';
        } else if ($sts_angkas == 'murni_geser3') {
            $field_angkas = 'nilai_susun3';
        } else if ($sts_angkas == 'murni_geser4') {
            $field_angkas = 'nilai_susun4';
        } else if ($sts_angkas == 'murni_geser5') {
            $field_angkas = 'nilai_susun5';
        } else if ($sts_angkas == 'sempurna1') {
            $field_angkas = 'nilai_sempurna';
        } else if ($sts_angkas == 'sempurna1_geser1') {
            $field_angkas = 'nilai_sempurna11';
        } else if ($sts_angkas == 'sempurna1_geser2') {
            $field_angkas = 'nilai_sempurna12';
        } else if ($sts_angkas == 'sempurna1_geser3') {
            $field_angkas = 'nilai_sempurna13';
        } else if ($sts_angkas == 'sempurna1_geser4') {
            $field_angkas = 'nilai_sempurna14';
        } else if ($sts_angkas == 'sempurna1_geser5') {
            $field_angkas = 'nilai_sempurna15';
        } else if ($sts_angkas == 'sempurna2') {
            $field_angkas = 'nilai_sempurna2';
        } else if ($sts_angkas == 'sempurna2_geser1') {
            $field_angkas = 'nilai_sempurna21';
        } else if ($sts_angkas == 'sempurna2_geser2') {
            $field_angkas = 'nilai_sempurna22';
        } else if ($sts_angkas == 'sempurna2_geser3') {
            $field_angkas = 'nilai_sempurna23';
        } else if ($sts_angkas == 'sempurna2_geser4') {
            $field_angkas = 'nilai_sempurna24';
        } else if ($sts_angkas == 'sempurna2_geser5') {
            $field_angkas = 'nilai_sempurna25';
        } else if ($sts_angkas == 'sempurna3') {
            $field_angkas = 'nilai_sempurna3';
        } else if ($sts_angkas == 'sempurna3_geser1') {
            $field_angkas = 'nilai_sempurna31';
        } else if ($sts_angkas == 'sempurna3_geser2') {
            $field_angkas = 'nilai_sempurna32';
        } else if ($sts_angkas == 'sempurna3_geser3') {
            $field_angkas = 'nilai_sempurna33';
        } else if ($sts_angkas == 'sempurna3_geser4') {
            $field_angkas = 'nilai_sempurna34';
        } else if ($sts_angkas == 'sempurna3_geser5') {
            $field_angkas = 'nilai_sempurna35';
        } else if ($sts_angkas == 'sempurna4') {
            $field_angkas = 'nilai_sempurna4';
        } else if ($sts_angkas == 'sempurna4_geser1') {
            $field_angkas = 'nilai_sempurna41';
        } else if ($sts_angkas == 'sempurna4_geser2') {
            $field_angkas = 'nilai_sempurna42';
        } else if ($sts_angkas == 'sempurna4_geser3') {
            $field_angkas = 'nilai_sempurna43';
        } else if ($sts_angkas == 'sempurna4_geser4') {
            $field_angkas = 'nilai_sempurna44';
        } else if ($sts_angkas == 'sempurna4_geser5') {
            $field_angkas = 'nilai_sempurna45';
        } else if ($sts_angkas == 'sempurna5') {
            $field_angkas = 'nilai_sempurna5';
        } else if ($sts_angkas == 'sempurna5_geser1') {
            $field_angkas = 'nilai_sempurna51';
        } else if ($sts_angkas == 'sempurna5_geser2') {
            $field_angkas = 'nilai_sempurna52';
        } else if ($sts_angkas == 'sempurna5_geser3') {
            $field_angkas = 'nilai_sempurna53';
        } else if ($sts_angkas == 'sempurna5_geser4') {
            $field_angkas = 'nilai_sempurna1';
        } else if ($sts_angkas == 'sempurna5_geser5') {
            $field_angkas = 'nilai_sempurna55';
        } else if ($sts_angkas == 'ubah') {
            $field_angkas = 'nilai_ubah';
        } else if ($sts_angkas == 'ubah1') {
            $field_angkas = 'nilai_ubah1';
        } else if ($sts_angkas == 'ubah2') {
            $field_angkas = 'nilai_ubah2';
        } else if ($sts_angkas == 'ubah3') {
            $field_angkas = 'nilai_ubah3';
        } else if ($sts_angkas == 'ubah4') {
            $field_angkas = 'nilai_ubah4';
        } else {
            $field_angkas = 'nilai_ubah5';
        }

        $status_anggaran = status_anggaran();

        $hasil = DB::table('trhspd')->select(DB::raw("COUNT(*) as spd"))->whereRaw("LEFT(kd_skpd,17) = LEFT(?,17)", [$kd_skpd])->groupBy('bulan_awal', 'bulan_akhir')->first();

        if ($beban == '4' || substr($kd_sub_kegiatan, 5, 10) == '01.1.02.01') {
            $bulan1 = $bulan  + 1;
            $nilai_angkas = collect(DB::select("SELECT  a.kd_sub_kegiatan, SUM(a.$field_angkas) as nilai FROM trdskpd_ro a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan
            where a.kd_skpd = ? and  a.kd_sub_kegiatan = ? and a.kd_rek6=? and (bulan <=?) and jns_ang=? GROUP BY a.kd_sub_kegiatan,a.kd_rek6", [$kd_skpd, $kd_sub_kegiatan, $kd_rek6, $bulan1, $status_anggaran]))->first();
        } else {
            $nilai_angkas = collect(DB::select("SELECT  a.kd_sub_kegiatan, SUM(a.$field_angkas) as nilai FROM trdskpd_ro a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan
            where a.kd_skpd = ? and  a.kd_sub_kegiatan = ? and a.kd_rek6=? and (bulan <=?) and jns_ang=? GROUP BY a.kd_sub_kegiatan,a.kd_rek6", [$kd_skpd, $kd_sub_kegiatan, $kd_rek6, $bulan, $status_anggaran]))->first();
        }


        $revisi1 = collect(DB::select("SELECT max(revisi_ke) as revisi from trhspd where left(kd_skpd,17)=left(?,17) and bulan_akhir='3'", [$kd_skpd]))->first();

        $revisi2 = collect(DB::select("SELECT isnull(max(revisi_ke),0) as revisi from trhspd where left(kd_skpd,17)=left(?,17) and bulan_akhir='6'", [$kd_skpd]))->first();

        $revisi3 = collect(DB::select("SELECT isnull(max(revisi_ke),0) as revisi from trhspd where left(kd_skpd,17)=left(?,17) and bulan_akhir='9'", [$kd_skpd]))->first();

        $revisi4 = collect(DB::select("SELECT isnull(max(revisi_ke),0) as revisi from trhspd where left(kd_skpd,17)=left(?,17) and bulan_akhir='12'", [$kd_skpd]))->first();

        $nilai_spd = collect(DB::select("SELECT sum(nilai)as total_spd from (
                    SELECT
                    'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = ?
                    AND a.kd_sub_kegiatan = ?
                    AND a.kd_rek6 = ?
                    AND b.status = '1'
                    and bulan_akhir='3'
                    and revisi_ke=?
                    UNION ALL
                    SELECT
                    'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = ?
                    AND a.kd_sub_kegiatan = ?
                    AND a.kd_rek6 = ?
                    AND b.status = '1'
                    and bulan_akhir='6'
                    and revisi_ke=?
                    UNION ALL
                    SELECT
                    'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = ?
                    AND a.kd_sub_kegiatan = ?
                    AND a.kd_rek6 = ?
                    AND b.status = '1'
                    and bulan_akhir='9'
                    and revisi_ke=?
                    UNION ALL
                    SELECT
                    'TW4' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = ?
                    AND a.kd_sub_kegiatan = ?
                    AND a.kd_rek6 = ?
                    AND b.status = '1'
                    and bulan_akhir='12'
                    and revisi_ke=?)spd", [$kd_skpd, $kd_sub_kegiatan, $kd_rek6, $revisi1->revisi, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $revisi2->revisi, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $revisi3->revisi, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $revisi4->revisi,]))
            ->first();


        if ($beban == '1') {
            $total_trans = collect(DB::select("SELECT SUM(nilai) total FROM
                                    (
                                    -- tampungan
                                    SELECT SUM(isnull(nilai,0)) as nilai
                                    FROM tb_transaksi
                                    WHERE kd_sub_kegiatan=?
                                    AND kd_skpd=?
                                    AND kd_rek6=?
                                    UNION ALL
                                    -- transaksi UP/GU
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout c
                                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan = ?
                                    AND d.kd_skpd = ?
                                    AND c.kd_rek6 = ?
                                    AND d.jns_spp in ('1')

                                    UNION ALL
                                    -- transaksi UP/GU CMS BANK Belum Validasi
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout_cmsbank c
                                    LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan =?
                                    AND d.kd_skpd = ?
                                    AND c.kd_rek6=?
                                    AND d.jns_spp in ('1')
                                    AND (d.status_validasi='0' OR d.status_validasi is null)

                                    UNION ALL
                                    -- transaksi UP/GU KKPD BANK Belum Validasi
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout_kkpd c
                                    LEFT JOIN trhtransout_kkpd d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan =?
                                    AND d.kd_skpd = ?
                                    AND c.kd_rek6=?
                                    AND d.jns_spp in ('1')
                                    AND (d.status_validasi='0' OR d.status_validasi is null)

                                    UNION ALL
                                    -- transaksi SPP SELAIN UP/GU
                                    SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
                                    INNER JOIN trhspp y
                                    ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                                    WHERE x.kd_sub_kegiatan = ?
                                    AND x.kd_skpd = ?
                                    AND x.kd_rek6 = ?
                                    AND y.jns_spp IN ('3','4','5','6')
                                    AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')

                                    UNION ALL
                                    -- Penagihan yang belum jadi SPP
                                    SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t
                                    INNER JOIN trhtagih u
                                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                    WHERE t.kd_sub_kegiatan =?
                                    AND t.kd_rek6 =?
                                    AND u.kd_skpd = ?
                                    AND u.no_bukti
                                    NOT IN (select no_tagih FROM trhspp WHERE kd_skpd=?)
                                    )r", [$kd_sub_kegiatan, $kd_skpd, $kd_rek6, $kd_sub_kegiatan, $kd_skpd, $kd_rek6, $kd_sub_kegiatan, $kd_skpd, $kd_rek6, $kd_sub_kegiatan, $kd_skpd, $kd_rek6, $kd_sub_kegiatan, $kd_skpd, $kd_rek6, $kd_sub_kegiatan, $kd_rek6, $kd_skpd, $kd_skpd]))->first();
        } else {
            $spp = DB::table('trhsp2d')
                ->select('no_spp')
                ->where(['no_sp2d' => $no_sp2d])
                ->first();
            $no_spp = $spp->no_spp;

            $total_trans = collect(DB::select("SELECT SUM(nilai) total FROM
                                    (
                                    -- tampungan
                                    SELECT SUM(isnull(nilai,0)) as nilai
                                    FROM tb_transaksi
                                    WHERE kd_sub_kegiatan=?
                                    AND kd_skpd=?
                                    AND kd_rek6=?
                                    UNION ALL
                                    -- transaksi UP/GU
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout c
                                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan = ?
                                    AND d.kd_skpd = ?
                                    AND c.kd_rek6 = ?
                                    AND d.jns_spp in ('1')

                                    UNION ALL
                                    -- transaksi UP/GU CMS BANK Belum Validasi
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout_cmsbank c
                                    LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan =?
                                    AND d.kd_skpd = ?
                                    AND c.kd_rek6=?
                                    AND d.jns_spp in ('1')
                                    AND (d.status_validasi='0' OR d.status_validasi is null)

                                    UNION ALL
                                    -- transaksi UP/GU KKPD BANK Belum Validasi
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout_kkpd c
                                    LEFT JOIN trhtransout_kkpd d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan =?
                                    AND d.kd_skpd = ?
                                    AND c.kd_rek6=?
                                    AND d.jns_spp in ('1')
                                    AND (d.status_validasi='0' OR d.status_validasi is null)

                                    UNION ALL
                                    -- transaksi SPP SELAIN UP/GU
                                    SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
                                    INNER JOIN trhspp y
                                    ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                                    WHERE x.kd_sub_kegiatan = ?
                                    AND x.kd_skpd = ?
                                    AND x.kd_rek6 = ?
                                    AND y.jns_spp IN ('3','4','5','6')
                                    AND y.no_spp<>?
                                    AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')

                                    UNION ALL
                                    -- Penagihan yang belum jadi SPP
                                    SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t
                                    INNER JOIN trhtagih u
                                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                    WHERE t.kd_sub_kegiatan =?
                                    AND t.kd_rek6 =?
                                    AND u.kd_skpd = ?
                                    AND u.no_bukti
                                    NOT IN (select no_tagih FROM trhspp WHERE kd_skpd=?)
                                    )r", [$kd_sub_kegiatan, $kd_skpd, $kd_rek6, $kd_sub_kegiatan, $kd_skpd, $kd_rek6, $kd_sub_kegiatan, $kd_skpd, $kd_rek6, $kd_sub_kegiatan, $kd_skpd, $kd_rek6, $kd_sub_kegiatan, $kd_skpd, $kd_rek6, $no_spp, $kd_sub_kegiatan, $kd_rek6, $kd_skpd, $kd_skpd]))
                ->first();
        }

        if ($beban == '1') {
            $sisa_bank = collect(DB::select("SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan
            union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK'
            union all
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd=? and  d.pay='BANK'
            union all
            select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a
            join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where a.kd_skpd=? and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
            union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd=?
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot
                from trspmpot group by no_spm) c on b.no_spm=c.no_spm
                left join
                    (select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd where e.kd_skpd=? and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
                    ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd WHERE pay='BANK' and (panjar not in ('1') or panjar is null)
             union all
            select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a
            join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where a.kd_skpd=? and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
            UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan
            union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK'
            union all
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank
            union all
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1'
            union all
            SELECT a.tgl_kas AS tgl,a.no_panjar AS bku,a.keterangan as ket,a.nilai-isnull(b.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM tr_panjar a
            left join
            (
                select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
                where e.kd_skpd=? and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
             ) b on a.no_panjar=b.no_kas and a.kd_skpd=b.kd_skpd
            where a.pay='BANK' and a.kd_skpd=?
            union all
            select d.tgl_bukti, d.no_bukti,d.ket [ket],sum(e.nilai) [jumlah],'1' [jns],d.kd_skpd [kode] from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
            where e.kd_skpd=? and d.no_sp2d='2704/TU/2023' and d.pay='BANK' group by d.tgl_bukti,d.no_bukti,d.ket,d.kd_skpd
            union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd=?
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd=?
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            ) a
            where  kode=?", [$kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd, $kd_skpd]))->first();
        } else {
            $sisa_bank = collect(DB::select("SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT SUM(a.nilai) AS jumlah,'1' AS jns FROM trdspp a INNER JOIN trhspp b ON a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd INNER JOIN trhspm c ON b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd INNER JOIN trhsp2d d ON c.no_spm=d.no_spm and c.kd_skpd=d.kd_skpd WHERE d.kd_skpd=? and d.no_sp2d=?
            UNION ALL
            select SUM(b.nilai) AS jumlah,'2' AS jns
            from trhtransout a join trdtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where b.kd_skpd=? and a.no_sp2d=?
            UNION ALL
            select isnull(sum(isnull(b.nilai,0)),0) AS jumlah, '2' as jns
            from trhtransout_cmsbank a join trdtransout_cmsbank b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_skpd
            where b.kd_skpd=? and a.no_sp2d=? and (status_validasi='0' OR status_validasi is null)
            UNION ALL
            SELECT isnull(sum(isnull(nilai,0)),0) AS jumlah, '2' as jns
            from tb_transaksi WHERE kd_skpd=? and no_sp2d=?
            ) aa", [$kd_skpd, $no_sp2d, $kd_skpd, $no_sp2d, $kd_skpd, $no_sp2d, $kd_skpd, $no_sp2d]))
                ->first();
        }

        $status_anggaran_selanjutnya = DB::table('tb_status_anggaran as a')
            ->select('a.kode')
            ->join('trhrka as b', 'a.kode', '=', 'b.jns_ang')
            ->whereRaw("b.kd_skpd=? and b.status!=? and a.status_aktif=?", [$kd_skpd, '1', '1'])
            ->first();

        $status_anggaran_selanjutnya = empty($status_anggaran_selanjutnya) ? '' : $status_anggaran_selanjutnya->kode;

        $anggaran_selanjutnya = DB::table('trdrka')
            ->selectRaw("SUM(nilai) as nilai")
            ->where(['jns_ang' => $status_anggaran_selanjutnya, 'kd_skpd' => $kd_skpd, 'kd_sub_kegiatan' => $kd_sub_kegiatan, 'kd_rek6' => $kd_rek6])
            ->first();

        return response()->json([
            'angkas' => $nilai_angkas->nilai,
            'spd' => $nilai_spd->total_spd,
            'transaksi' => $total_trans->total,
            'sisa_bank' => $sisa_bank->terima - $sisa_bank->keluar,
            'status_ang_selanjutnya' => $status_anggaran_selanjutnya,
            'anggaran_selanjutnya' => empty($anggaran_selanjutnya) ? 0 : $anggaran_selanjutnya->nilai
        ]);
    }

    public function sumberDana(Request $request)
    {
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_skpd = $request->kd_skpd;
        $sumber = $request->sumber;
        $kd_rek6 = $request->kd_rek6;
        $no_sp2d = $request->no_sp2d;
        $beban = $request->beban;

        $spp = DB::table('trhsp2d')
            ->select('no_spp')
            ->where(['no_sp2d' => $no_sp2d])
            ->first();

        $no_spp = $spp->no_spp;

        if ($beban == '1') {
            $data = collect(DB::select("SELECT sum(nilai) [total] from (
                select 'tagih' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai] from trhtagih a
                join trdtagih b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where
                b.kd_skpd=? and b.kd_sub_kegiatan=?
                and b.kd_rek=?
                and b.no_bukti not in (select no_tagih from trhspp where kd_skpd=?)
                and b.sumber=?

                union all

                select 'spp' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]
                from trhspp a join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd where
                b.kd_skpd=? and b.kd_sub_kegiatan=? and b.kd_rek6=?
                and jns_spp not in ('1','2') and b.sumber=? and a.no_spp=? AND (a.sp2d_batal<>1 OR a.sp2d_batal IS NULL)
                AND a.no_spp not in (select no_spp from trhsp2d where kd_skpd=? and jns_spp not in ('1','2')

                )

                union all

                select 'sp2d terbit' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]
                from trhspp a join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
                inner join trhsp2d c on a.no_spp=c.no_spp and a.kd_skpd=c.kd_skpd where
                b.kd_skpd=? and b.kd_sub_kegiatan=? and b.kd_rek6=?
                and a.jns_spp not in ('1','2') and b.sumber=? AND (a.sp2d_batal<>1 OR a.sp2d_batal IS NULL)
                and (no_kas_bud='' OR no_kas_bud is null)

                UNION ALL

                select 'sp2d cair not trx cms' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]
                from trhspp a join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
                inner join trhsp2d c on a.no_spp=c.no_spp and a.kd_skpd=c.kd_skpd where
                b.kd_skpd=? and b.kd_sub_kegiatan=? and b.kd_rek6=?
                and b.sumber=? AND (a.sp2d_batal<>1 OR a.sp2d_batal IS NULL)
                and no_kas_bud='1'
                and no_sp2d not in (select no_sp2d from trhtransout_cmsbank where kd_skpd=?
                and (status_validasi='0' OR status_validasi is null) )


                UNION ALL

                select 'sp2d cair not trx' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]
                from trhspp a join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
                inner join trhsp2d c on a.no_spp=c.no_spp and a.kd_skpd=c.kd_skpd where
                b.kd_skpd=? and b.kd_sub_kegiatan=? and b.kd_rek6=?
                and a.jns_spp not in ('1','2')
                and b.sumber=? AND (a.sp2d_batal<>1 OR a.sp2d_batal IS NULL)
                and no_kas_bud='1'
                and no_sp2d not in (select no_sp2d from trhtransout where kd_skpd=?
                and jns_spp not in ('1','2'))

                UNION ALL

                select 'trans' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]
                from trhtransout a join trdtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                where b.kd_skpd=? and b.kd_sub_kegiatan=?
                and b.kd_rek6=? and a.no_bukti not in('') and b.sumber=?

                UNION ALL

                select 'tampungan' [jdl],isnull(sum(isnull(nilai,0)),0) [nilai]
                from tb_transaksi
                where kd_skpd=? and kd_sub_kegiatan=?
                and kd_rek6=? and sumber=?


                ) as gabung", [$kd_skpd, $kd_sub_kegiatan, $kd_rek6, $kd_skpd, $sumber, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $sumber, $no_spp, $kd_skpd, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $sumber, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $sumber, $kd_skpd, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $sumber, $kd_skpd, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $sumber, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $sumber]))->first();
        } else {
            $data = collect(DB::select("SELECT sum(nilai) [total] from (


            select 'tagih' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai] from trhtagih a
            join trdtagih b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            join trdspp c on a.kd_skpd=c.kd_skpd and b.kd_sub_kegiatan=c.kd_sub_kegiatan and b.kd_rek6=c.kd_rek6
            join trhsp2d d on c.no_spp=d.no_spp and c.kd_skpd=d.kd_skpd where
            b.kd_skpd=? and b.kd_sub_kegiatan=?
            and b.kd_rek=? and b.no_bukti not in (select no_tagih from trhspp where
            kd_skpd=? and no_spp=?) and b.sumber=? and d.no_sp2d=?

            union all

            select 'spp' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]
            from trhspp a join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd where
            b.kd_skpd=? and b.kd_sub_kegiatan=? and b.kd_rek6=?
            and jns_spp not in ('1','2') and b.sumber=? and a.no_spp=? AND (a.sp2d_batal<>1 OR a.sp2d_batal IS NULL)
            AND a.no_spp not in (select no_spp from trhsp2d where kd_skpd=? and jns_spp not in ('1','2') and no_sp2d=?)

            union all

            select 'sp2d terbit' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]
            from trhspp a join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
            inner join trhsp2d c on a.no_spp=c.no_spp and a.kd_skpd=c.kd_skpd where
            b.kd_skpd=? and b.kd_sub_kegiatan=? and b.kd_rek6=? and no_sp2d=?
            and a.jns_spp not in ('1','2') and b.sumber=? AND (a.sp2d_batal<>1 OR a.sp2d_batal IS NULL)
            and (no_kas_bud='' OR no_kas_bud is null)

            UNION ALL

            select 'sp2d cair not trx cms' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]
            from trhspp a join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
            inner join trhsp2d c on a.no_spp=c.no_spp and a.kd_skpd=c.kd_skpd where
            b.kd_skpd=? and b.kd_sub_kegiatan=? and b.kd_rek6=? and no_sp2d=?
            and a.jns_spp not in ('1','2') and b.sumber=? AND (a.sp2d_batal<>1 OR a.sp2d_batal IS NULL)
            and no_kas_bud='1' and no_sp2d not in (select no_sp2d from trhtransout_cmsbank where kd_skpd=? and no_sp2d=? and jns_spp not in ('1','2') and
            (status_validasi='0' OR status_validasi is null)
            )


            UNION ALL

            select 'sp2d cair not trx' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]
            from trhspp a join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
            inner join trhsp2d c on a.no_spp=c.no_spp and a.kd_skpd=c.kd_skpd where
            b.kd_skpd=? and b.kd_sub_kegiatan=? and b.kd_rek6=? and no_sp2d=?
            and a.jns_spp not in ('1','2') and b.sumber=? AND (a.sp2d_batal<>1 OR a.sp2d_batal IS NULL)
            and no_kas_bud='1' and no_sp2d not in (select no_sp2d from trhtransout where kd_skpd=? and no_sp2d=? and jns_spp not in ('1','2'))

            UNION ALL

            select 'trans cms' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]
            from trhtransout_cmsbank a join trdtransout_cmsbank b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_skpd
            where b.kd_skpd=? and b.kd_sub_kegiatan=? and a.no_sp2d=?
            and b.kd_rek6=? and a.no_voucher not in('') and b.sumber=? and (status_validasi='0' OR status_validasi is null)

            UNION ALL

            select 'trans' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]
            from trhtransout a join trdtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where b.kd_skpd=? and b.kd_sub_kegiatan=? and a.no_sp2d=?
            and b.kd_rek6=? and a.no_bukti not in('') and b.sumber=?

            UNION ALL

            select 'tampungan' [jdl],isnull(sum(isnull(nilai,0)),0) [nilai]
            from tb_transaksi
            where kd_skpd=? and kd_sub_kegiatan=? and no_sp2d=?
            and kd_rek6=? and sumber=?


            ) as gabung", [$kd_skpd, $kd_sub_kegiatan, $kd_rek6, $kd_skpd, $no_spp, $sumber, $no_sp2d, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $sumber, $no_spp, $kd_skpd, $no_sp2d, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $no_sp2d, $sumber, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $no_sp2d, $sumber, $kd_skpd, $no_sp2d, $kd_skpd, $kd_sub_kegiatan, $kd_rek6, $no_sp2d, $sumber, $kd_skpd, $no_sp2d, $kd_skpd, $kd_sub_kegiatan, $no_sp2d, $kd_rek6, $sumber, $kd_skpd, $kd_sub_kegiatan, $no_sp2d, $kd_rek6, $sumber, $kd_skpd, $kd_sub_kegiatan, $no_sp2d, $kd_rek6, $sumber]))->first();
        }
        return response()->json($data);
    }

    public function sumber(Request $request)
    {
        $tgl_bukti = $request->tgl_bukti;
        $kd_skpd = $request->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $no_sp2d = $request->no_sp2d;
        $beban = $request->beban;
        $kd_rek6 = $request->kd_rek6;
        $jenis_ang = status_anggaran();

        if ($beban == '1') {
            $no_trdrka = $kd_skpd . '.' . $kd_sub_kegiatan . '.' . $kd_rek6;

            $data1 = DB::table('trdpo')
                ->select('sumber as sumber_dana', 'nm_sumber', DB::raw("SUM(total) as nilai"))
                ->where(['no_trdrka' => $no_trdrka, 'jns_ang' => $jenis_ang])
                ->whereNotNull('sumber')
                ->groupBy('sumber', 'nm_sumber');

            $data2 = DB::table('trdpo')
                ->select('sumber as sumber_dana', DB::raw("'Silahkan isi sumber di anggaran' as nm_sumber"), DB::raw("SUM(total) as nilai"))
                ->where(['no_trdrka' => $no_trdrka, 'jns_ang' => $jenis_ang])
                ->where(function ($query) {
                    $query->where('sumber', '')->orWhereNull('sumber');
                })
                ->groupBy('sumber', 'nm_sumber')
                ->union($data1);

            $data = DB::table(DB::raw("({$data2->toSql()}) AS sub"))
                ->mergeBindings($data2)
                ->get();
        } else {
            $data = DB::table('trhspp as a')
                ->join('trdspp as b', function ($join) {
                    $join->on('a.no_spp', '=', 'b.no_spp');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->join('trhsp2d as c', function ($join) {
                    $join->on('a.no_spp', '=', 'c.no_spp');
                    $join->on('a.kd_skpd', '=', 'c.kd_skpd');
                })
                ->where(['c.no_sp2d' => $no_sp2d, 'a.kd_skpd' => $kd_skpd, 'b.kd_sub_kegiatan' => $kd_sub_kegiatan, 'kd_rek6' => $kd_rek6])
                ->groupBy('b.sumber')
                ->select('b.sumber as sumber_dana', DB::raw("SUM(b.nilai) as nilai"), DB::raw("SUM(b.nilai) as nilai_sempurna"), DB::raw("SUM(b.nilai) as nilai_ubah"))
                ->selectRaw("(SELECT nm_sumber_dana1 FROM sumber_dana WHERE kd_sumber_dana1=b.sumber) as nm_sumber")
                ->get();
        }
        return response()->json($data);
    }

    public function simpan(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $cek_panjar = DB::table('trhtransout')
                ->where(['no_bukti' => $data['no_bukti'], 'kd_skpd' => $data['kd_skpd']])
                ->count();

            if ($cek_panjar > 0) {
                return response()->json([
                    'message' => '4'
                ]);
            }

            DB::table('trhtransout')
                ->where(['no_bukti' => $data['no_bukti'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            DB::table('trhtransout')
                ->insert([
                    'no_kas' => $data['no_kas'],
                    'tgl_kas' => $data['tgl_kas'],
                    'no_bukti' => $data['no_bukti'],
                    'tgl_bukti' => $data['tgl_bukti'],
                    'ket' => $data['keterangan'],
                    'username' => Auth::user()->nama,
                    'tgl_update' => date('Y-m-d H:i:s'),
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'total' => $data['total'],
                    'no_tagih' => '',
                    'sts_tagih' => '',
                    'tgl_tagih' => '',
                    'jns_spp' => $data['beban'],
                    'pay' => $data['pembayaran'],
                    'no_kas_pot' => $data['no_kas'],
                    'panjar' => '1',
                    'no_panjar' => $data['no_panjar'],
                    'no_sp2d' => '',
                ]);

            DB::table('trdtransout')
                ->where(['no_bukti' => $data['no_bukti'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            if (isset($data['rincian_rekening'])) {
                DB::table('trdtransout')->insert(array_map(function ($value) use ($data, $kd_skpd) {
                    return [
                        'no_bukti' => $data['no_bukti'],
                        'no_sp2d' => $value['no_sp2d'],
                        'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                        'nm_sub_kegiatan' => $value['nm_sub_kegiatan'],
                        'kd_rek6' => $value['kd_rek6'],
                        'nm_rek6' => $value['nm_rek6'],
                        'nilai' => $value['nilai'],
                        'kd_skpd' => $data['kd_skpd'],
                        'sumber' => $value['sumber'],
                    ];
                }, $data['rincian_rekening']));
            }

            DB::table('tb_transaksi')
                ->where(['kd_skpd' => $kd_skpd, 'username' => Auth::user()->nama])
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

    public function edit($no_panjar, $kd_skpd)
    {
        $no_panjar = Crypt::decrypt($no_panjar);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $panjar = DB::table('tr_jpanjar')
            ->where(['no_kas' => $no_panjar, 'kd_skpd' => $kd_skpd])
            ->first();

        $data = [
            'panjar' => $panjar,
            'load_detail' => collect(DB::select("SELECT no_panjar, nilai,(SELECT no_panjar from tr_panjar where jns = '2' AND no_panjar_lalu = ? AND kd_skpd=?) as no_panjar2,
					(SELECT nilai from tr_panjar where jns = '2' AND no_panjar_lalu = ? AND kd_skpd=? ) as nilai2
					FROM tr_panjar WHERE no_panjar_lalu = ? AND jns = '1' AND kd_skpd=?", [$panjar->no_panjar, $kd_skpd, $panjar->no_panjar, $kd_skpd, $panjar->no_panjar, $kd_skpd]))->first(),
            'load_total' => collect(DB::select("SELECT SUM(a.nilai) as panjar, (SELECT SUM(c.nilai) FROM trdtransout c join trhtransout b on c.no_bukti = b.no_bukti AND c.kd_skpd=b.kd_skpd WHERE b.no_panjar = ? and b.panjar = '1' AND b.kd_skpd=?) as trans FROM tr_panjar a WHERE a.no_panjar_lalu = ? AND a.kd_skpd=? GROUP BY kd_skpd", [$panjar->no_panjar, $kd_skpd, $panjar->no_panjar, $kd_skpd]))->first()
        ];

        return view('skpd.transaksi_panjar.edit')->with($data);
    }

    public function update(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $cek_panjar = collect(DB::select("SELECT count(*) as jumlah FROM (select no_panjar as nomor FROM tr_panjar WHERE kd_skpd = ? UNION ALL SELECT no_kas as nomor FROM tr_jpanjar WHERE kd_skpd = ?)a WHERE a.nomor = ?", [$kd_skpd, $kd_skpd, $data['no_panjar']]))->first();
            if ($cek_panjar->jumlah > 0) {
                return response()->json([
                    'message' => '4'
                ]);
            }

            DB::table('tr_jpanjar')
                ->where(['kd_skpd' => $kd_skpd, 'no_kas' => $data['no_simpan']])
                ->update([
                    'no_kas' => $data['no_panjar'],
                    'tgl_kas' => $data['tgl_panjar'],
                    'keterangan' => $data['keterangan'],
                    'pengguna' => Auth::user()->nama
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
        $no_bukti = $request->no_bukti;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trdtransout')
                ->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])
                ->delete();

            DB::table('trhtransout')
                ->where(['no_bukti' => $no_bukti, 'kd_skpd' => $kd_skpd])
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
