<?php

namespace App\Http\Controllers\spd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Exception;
use Knp\Snappy\Pdf as SnappyPdf;
use PDF;

class SPDBelanjaController extends Controller
{
    public function index()
    {
        $data = [
            'ppkd' => DB::table('ms_ttd')->select('nip', 'nama', 'jabatan')->where(['kode' => 'PPKD', 'bidang' => '1'])->get(),
        ];
        return view('penatausahaan.spd.spd_belanja.index')->with($data);
    }

    public function loadData()
    {
        $id = Auth::user()->id;

        $data = DB::table('trhspd as a')->Select(
            'a.*',
            DB::raw("(select TOP 1 nama from ms_ttd b where a.kd_bkeluar=b.nip ) as nama"),
            DB::raw("case when jns_beban='5' then 'BELANJA' else 'PEMBIAYAAN' end AS nm_beban"),
            DB::raw("(select nama from tb_status_angkas where a.jns_ang=status_kunci) as nm_angkas")
        )
            ->whereIn('a.jns_beban', [5, 6])->whereRaw('a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id= ?)', [$id])
            ->groupBy([
                'a.no_spd', 'a.tgl_spd', 'a.kd_skpd', 'a.nm_skpd', 'a.jns_beban',
                'a.no_dpa', 'a.bulan_awal', 'a.bulan_akhir', 'a.kd_bkeluar', 'a.triwulan', 'a.klain',
                'a.username', 'a.tglupdate', 'a.st', 'a.status', 'a.total', 'revisi_ke', 'jns_ang', 'jns_angkas'
            ])
            ->orderBy('no_spd')->orderBy('tgl_spd')->orderBy('kd_skpd')->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="javascript:void(0);" onclick="hapusSPD(\'' . $row->no_spd . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="fas fa-trash-alt"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="cetak(\'' . $row->no_spd . '\');" class="btn btn-success btn-sm"><i class="uil-print"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
        return view('penatausahaan.spd.spd_belanja.index');
    }

    public function create()
    {
        $user = Auth()->user()->nama;
        DB::table('spd_temp')->where(['username' => $user])->Delete();
        $data = [
            'idpage' => uniqid('spd-page-id#', true),
            'jenisblnspd' => DB::table('trkonfig_spd')->select('jenis_spd')->first(),
        ];
        return view('penatausahaan.spd.spd_belanja.create')->with($data);
    }

    public function getSKPD(Request $request)
    {
        $term = $request->term;

        $results = DB::table('ms_skpd')
            ->select('kd_skpd as id', 'nm_skpd as text', 'kd_skpd', 'nm_skpd')
            ->whereRaw("right(kd_skpd, 5) = '.0000'")
            ->when($term, function ($query, $term) {
                $query->where(function ($query) use ($term) {
                    $query->orWhere('kd_skpd', 'like', '%' . $term . '%')
                        ->orWhere('nm_skpd', 'like', '%' . $term . '%');
                });
            })->get();

        return response()->json(['results' => $results]);
    }

    public function getNipSKPD(Request $request)
    {
        $term = $request->term;
        $kd_skpd = $request->kd_skpd;

        $results = DB::table('ms_ttd')
            ->select('nip as id', 'nama as text', 'nip', 'nama', 'jabatan', 'kd_skpd')
            ->whereIn('kode', ['PA', 'KPA'])->where('kd_skpd', $kd_skpd)
            ->when($term, function ($query, $term) {
                $query->where(function ($query) use ($term) {
                    $query->orWhere('nip', 'like', '%' . $term . '%')
                        ->orWhere('nama', 'like', '%' . $term . '%');
                });
            })->get();

        return response()->json(['results' => $results]);
    }

    public function getJenisAng(Request $request)
    {
        $term = $request->term;
        $kd_skpd = $request->kd_skpd;

        $results = DB::table('trskpd as a')
            ->select(DB::raw('DISTINCT(a.jns_ang) as id'), 'b.nama as text', 'b.kode as kode', 'b.nama as nama')
            ->join('tb_status_anggaran as b', function ($join) {
                $join->on('a.jns_ang', '=', 'b.kode');
            })->where(['a.kd_skpd' => $kd_skpd])
            ->when($term, function ($query, $term) {
                $query->where(function ($query) use ($term) {
                    $query->orWhere('nama', 'like', '%' . $term . '%');
                });
            })
            ->get();

        return response()->json(['results' => $results]);
    }

    public function getStatusAng(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $jns_ang = $request->kode;

        $results = statusAngkas($kd_skpd, $jns_ang);

        return response()->json(['results' => $results]);
    }

    public function getSpdBelanja(Request $request)
    {
        $kd_skpd = left($request->kd_skpd, 17);
        $skpd = $request->kd_skpd;
        $jns_ang = $request->jns_ang;
        $tgl = $request->tanggal;
        $bulanAwal = $request->bln_awal;
        $bulanAkhir = $request->bln_akhir;
        $jenis = $request->jenis;
        $revisi = $request->revisi;
        $nomor = $request->nomor;
        $page = $request->page;
        $status_angkas = $request->status_ang;
        $sts_ang = tbStatusAngkas($status_angkas);

        if ($jenis == 5) {
            if ($revisi == 1) {
                $data =  DB::select(
                    "SELECT a.kd_unit, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, 
                            a.kd_rek6 , a.nm_rek6, a.total_ubah AS anggaran, nilai AS nilai, lalu 
                        FROM
                        (
                            SELECT a.kd_skpd AS kd_unit, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_rek6 , b.nm_rek6 , SUM(b.nilai) AS total_ubah, LEFT(a.kd_skpd, 17) kd_skpd 
                                FROM trskpd a
                                INNER JOIN trdrka b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_skpd= b.kd_skpd
                                INNER JOIN ms_sub_kegiatan c ON a.kd_sub_kegiatan= c.kd_sub_kegiatan 
                            WHERE LEFT(b.kd_skpd, 17) = ? AND c.jns_sub_kegiatan= '5' AND b.jns_ang= ? 
                            GROUP BY LEFT(a.kd_skpd, 17), a.kd_skpd, a.kd_program, a.nm_program, b.kd_sub_kegiatan, a.nm_sub_kegiatan, b.kd_rek6, b.nm_rek6 
                        ) a LEFT JOIN 
                        (
                            SELECT kd_sub_kegiatan, b.kd_rek6, LEFT(kd_skpd, 17) kd_skpd, kd_skpd AS kd_unit, SUM($sts_ang) AS nilai 
                                FROM trdskpd_ro b 
                            WHERE (b.bulan BETWEEN ? AND ?) AND LEFT(kd_skpd, 17) = ? 
                            GROUP BY LEFT(kd_skpd, 17), kd_skpd, kd_sub_kegiatan, kd_rek6 
                        ) b ON a.kd_unit= b.kd_unit AND a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_rek6= b.kd_rek6 LEFT JOIN 
                        (
                            SELECT kd_unit AS kd_skpd, kd_sub_kegiatan, kd_rek6, isnull(SUM(a.nilai), 0) AS lalu 
                                FROM trdspd a
                                LEFT JOIN trhspd b ON a.no_spd= b.no_spd 
                            WHERE LEFT(b.kd_skpd, 17) = ?  AND a.no_spd != ? AND b.tgl_spd < ? 
                            GROUP BY kd_unit, kd_sub_kegiatan, kd_rek6 
                        ) c ON a.kd_unit= c.kd_skpd AND a.kd_sub_kegiatan= c.kd_sub_kegiatan AND a.kd_rek6= c.kd_rek6 
                    WHERE a.kd_sub_kegiatan NOT IN(SELECT TOP 0 x.kd_sub_kegiatan FROM trskpd x INNER JOIN ms_sub_kegiatan y ON x.kd_sub_kegiatan= y.kd_sub_kegiatan 
                            WHERE LEFT (x.kd_skpd, 17) = ? AND y.jns_sub_kegiatan IN ('61', '62', '4')) 
                        AND NOT EXISTS (
                            SELECT 1 FROM spd_temp where 
                            left(a.kd_unit, 17) = left(spd_temp.kd_skpd, 17) AND a.kd_sub_kegiatan = spd_temp.kd_sub_kegiatan  
                            AND a.kd_rek6 = spd_temp.kd_rek6 AND spd_temp.nilai_lalu = lalu
                            AND spd_temp.anggaran = anggaran AND spd_temp.bulan_awal = ? 
                            AND spd_temp.bulan_akhir = ? AND spd_temp.jns_ang = ?
                            AND spd_temp.jns_angkas = ? AND spd_temp.jns_beban = ? and spd_temp.page_id = ?
                        )
                    ORDER BY a.kd_unit, a.kd_sub_kegiatan",
                    [$kd_skpd, $jns_ang, $bulanAwal, $bulanAkhir, $kd_skpd, $kd_skpd, $nomor, $tgl, $kd_skpd,
                    $bulanAwal, $bulanAkhir, $jns_ang, $status_angkas, $jenis, $page]
                );
            } else {
                $data = DB::select(
                    "SELECT a.kd_unit, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,
                            a.kd_rek6 , a.nm_rek6, a.total_ubah AS anggaran, nilai - isnull(lalu_tw, 0) AS nilai, lalu 
                        FROM
                        (
                            SELECT a.kd_skpd AS kd_unit, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,
                                    b.kd_rek6, b.nm_rek6, SUM(b.nilai) AS total_ubah, LEFT(a.kd_skpd, 17) kd_skpd 
                                FROM trskpd a
                                INNER JOIN trdrka b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_skpd= b.kd_skpd
                                INNER JOIN ms_sub_kegiatan c ON a.kd_sub_kegiatan= c.kd_sub_kegiatan 
                            WHERE LEFT(b.kd_skpd, 17) = ? AND c.jns_sub_kegiatan= '5' AND b.jns_ang = ? 
                            GROUP BY LEFT(a.kd_skpd, 17), a.kd_skpd, a.kd_program, a.nm_program, b.kd_sub_kegiatan,
                                    a.nm_sub_kegiatan, b.kd_rek6, b.nm_rek6 
                        ) a LEFT JOIN 
                        (
                            SELECT kd_sub_kegiatan, b.kd_rek6, LEFT(kd_skpd, 17) kd_skpd, kd_skpd AS kd_unit, SUM($sts_ang) AS nilai 
                                FROM trdskpd_ro b 
                            WHERE b.bulan >= ? AND b.bulan <= ? AND LEFT(kd_skpd, 17) = ? 
                            GROUP BY LEFT(kd_skpd, 17), kd_skpd, kd_sub_kegiatan, kd_rek6 
                        ) b ON a.kd_unit= b.kd_unit AND a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_rek6= b.kd_rek6 LEFT JOIN 
                        (
                            SELECT kd_unit AS kd_skpd, kd_sub_kegiatan, kd_rek6, isnull(SUM(a.nilai), 0) AS lalu 
                                FROM trdspd a
                                LEFT JOIN trhspd b ON a.no_spd= b.no_spd 
                            WHERE LEFT(b.kd_skpd, 17) = ? AND a.no_spd != ? AND b.tgl_spd < ? 
                            GROUP BY kd_unit, kd_sub_kegiatan, kd_rek6 
                        ) c ON a.kd_unit= c.kd_skpd AND a.kd_sub_kegiatan= c.kd_sub_kegiatan AND a.kd_rek6= c.kd_rek6 LEFT JOIN 
                        (
                            SELECT kd_unit AS kd_skpd, kd_sub_kegiatan, kd_rek6, isnull(SUM(a.nilai), 0) AS lalu_tw 
                                FROM trdspd a
                                LEFT JOIN trhspd b ON a.no_spd= b.no_spd 
                            WHERE LEFT(b.kd_skpd, 17) = ? AND b.bulan_awal= ? AND b.bulan_akhir= ? AND a.no_spd != ? AND b.tgl_spd < ? 
                            GROUP BY kd_unit, kd_sub_kegiatan, kd_rek6 
                        ) d ON a.kd_unit= d.kd_skpd AND a.kd_sub_kegiatan= d.kd_sub_kegiatan AND a.kd_rek6= d.kd_rek6 
                    WHERE a.kd_unit + a.kd_sub_kegiatan + a.kd_rek6 NOT IN
                        (SELECT b.kd_skpd + b.kd_sub_kegiatan + b.kd_rek6 
                            FROM trskpd a
                            INNER JOIN trdrka b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_skpd= b.kd_skpd
                            INNER JOIN ms_sub_kegiatan c ON a.kd_sub_kegiatan= c.kd_sub_kegiatan 
                            WHERE LEFT(b.kd_skpd, 17) = ? AND c.jns_sub_kegiatan= '6' 
                            GROUP BY b.kd_skpd, b.kd_sub_kegiatan, b.kd_rek6 
                        ) 
                        AND NOT EXISTS (
                            SELECT 1 FROM spd_temp where 
                            left(a.kd_unit, 17) = left(spd_temp.kd_skpd, 17) AND spd_temp.nilai_lalu = lalu
                            AND spd_temp.anggaran = anggaran AND a.kd_sub_kegiatan = spd_temp.kd_sub_kegiatan  
                            AND a.kd_rek6 = spd_temp.kd_rek6  
                            AND spd_temp.bulan_awal = ? AND spd_temp.bulan_akhir = ? AND spd_temp.jns_ang = ?
                            AND spd_temp.jns_angkas = ? AND spd_temp.jns_beban = ? and spd_temp.page_id = ?
                        )
                    ORDER BY a.kd_unit, a.kd_sub_kegiatan",
                    [
                        $kd_skpd, $jns_ang, $bulanAwal, $bulanAkhir, $kd_skpd, $kd_skpd, $nomor, $tgl, $kd_skpd,
                        $bulanAwal, $bulanAkhir, $nomor, $tgl, $kd_skpd, $bulanAwal, $bulanAkhir, $jns_ang, $status_angkas, $jenis, $page
                    ]
                );                
            }
            return DataTables::of($data)->addIndexColumn()->make(true);
        } else {
            $data = DB::select(
                "SELECT a.kd_skpd as kd_unit, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, a.kd_rek6, a.nm_rek6,
                        a.total_ubah AS anggaran, (nilai - isnull(lalu_tw, 0)) AS nilai, isnull(lalu, 0) as lalu 
                    FROM
                    (
                        SELECT b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_rek6,
                            b.nm_rek6, SUM(nilai) AS total_ubah, b.kd_skpd 
                            FROM trskpd a
                            INNER JOIN trdrka b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan  AND a.kd_skpd= b.kd_skpd
                            INNER JOIN ms_sub_kegiatan c ON b.kd_sub_kegiatan= c.kd_sub_kegiatan AND b.jns_ang = ? 
                        WHERE b.kd_skpd = ? AND c.jns_sub_kegiatan = '62' 
                        GROUP BY b.kd_rek6, b.nm_rek6, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_skpd 
                    ) a LEFT JOIN 
                    (
                        SELECT kd_rek6, kd_sub_kegiatan, kd_skpd, SUM($sts_ang) AS nilai 
                            FROM trdskpd_ro b 
                        WHERE b.bulan >= ? AND b.bulan <= ? AND kd_skpd = ? 
                        GROUP BY kd_rek6, kd_sub_kegiatan, kd_skpd 
                    ) b ON a.kd_rek6= b.kd_rek6 AND a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_skpd= b.kd_skpd LEFT JOIN 
                    (
                        SELECT a.kd_rek6, kd_sub_kegiatan, SUM(a.nilai) AS lalu 
                            FROM trdspd a
                            LEFT JOIN trhspd b ON a.no_spd= b.no_spd 
                        WHERE b.kd_skpd = ? AND a.no_spd != ? AND b.tgl_spd < ? 
                        GROUP BY a.kd_rek6, kd_sub_kegiatan 
                    ) c ON a.kd_rek6= c.kd_rek6 AND a.kd_sub_kegiatan= c.kd_sub_kegiatan LEFT JOIN 
                    (
                        SELECT kd_unit AS kd_skpd, kd_sub_kegiatan, kd_rek6, isnull(SUM(a.nilai), 0) AS lalu_tw 
                            FROM trdspd a
                            LEFT JOIN trhspd b ON a.no_spd= b.no_spd 
                        WHERE b.kd_skpd = ? AND b.bulan_awal= ? AND b.bulan_akhir= ? AND a.no_spd != ? AND b.tgl_spd< ? 
                        GROUP BY kd_unit, kd_sub_kegiatan, kd_rek6 
                    ) d ON a.kd_rek6= d.kd_rek6 AND a.kd_sub_kegiatan= d.kd_sub_kegiatan 
                    WHERE a.kd_sub_kegiatan NOT IN
                        (
                            SELECT a.kd_sub_kegiatan FROM trskpd a
                            INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan 
                            WHERE a.kd_skpd = ? AND b.jns_sub_kegiatan = '5' 
                        ) AND NOT EXISTS (
                            SELECT * FROM spd_temp where 
                            left(a.kd_skpd, 17) = left(spd_temp.kd_skpd, 17)  AND spd_temp.nilai_lalu = lalu
                            AND isnull(spd_temp.anggaran,0) = anggaran AND a.kd_sub_kegiatan = spd_temp.kd_sub_kegiatan  
                            AND a.kd_rek6 = spd_temp.kd_rek6  
                            AND spd_temp.bulan_awal = ? AND spd_temp.bulan_akhir = ? AND spd_temp.jns_ang = ?
                            AND spd_temp.jns_angkas = ? AND spd_temp.jns_beban = ? and spd_temp.page_id = ?
                        )
                        ORDER BY a.kd_sub_kegiatan",
                [$jns_ang, $skpd, $bulanAwal, $bulanAkhir, $skpd, $skpd, $nomor, $tgl, $skpd, $bulanAwal, $bulanAkhir, $nomor, $tgl, $skpd,
                $bulanAwal, $bulanAkhir, $jns_ang, $status_angkas, $jenis, $page]
            );

            return DataTables::of($data)->addIndexColumn()->make(true);
        }

        
    }

    public function getInsertSpd(Request $request)
    {
        $data = $request->data;
        DB::beginTransaction();

        try {
            DB::table('spd_temp')
                ->insert([
                    'kd_skpd' => $data['kd_skpd'],
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    'kd_rek6' => $data['kd_rek6'],
                    'bulan_awal' => $data['bln_awal'],
                    'bulan_akhir' => $data['bln_akhir'],
                    'nilai' => $data['nilai'],
                    'nilai_lalu' => $data['lalu'],
                    'page_id' => $data['page'],
                    'anggaran' => $data['anggaran'],
                    'jns_ang' => $data['jns_ang'],
                    'jns_angkas' => $data['status_ang'],
                    'jns_beban' => $data['jenis'],
                    'revisi' => $data['revisi'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'username' => Auth::user()->nama
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

    public function getSpdBelanjaTemp(Request $request) 
    {
        $kd_skpd = left($request->kd_skpd, 17);
        $jns_ang = $request->jns_ang;
        $bulanAwal = $request->bln_awal;
        $bulanAkhir = $request->bln_akhir;
        $jenis = $request->jenis;
        $revisi = $request->revisi;
        $page = $request->page;
        $status_angkas = $request->status_ang;

        $data = DB::select(
            "SELECT * FROM spd_temp where 
                left(kd_skpd, 17) = ? AND bulan_awal = ? 
                AND bulan_akhir = ? AND jns_ang = ?
                AND jns_angkas = ? AND jns_beban = ? and revisi = ? and page_id = ?",
            [
                $kd_skpd, $bulanAwal, $bulanAkhir, $jns_ang, $status_angkas, $jenis, $revisi, $page
            ]
        );                
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function getDeleteSpdTemp(Request $request)
    {
        $data = $request->data;
        DB::beginTransaction();

        try {
            DB::table('spd_temp')
                ->where([
                    'kd_skpd' => $data['kd_skpd'],
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    'kd_rek6' => $data['kd_rek6'],
                    'bulan_awal' => $data['bln_awal'],
                    'bulan_akhir' => $data['bln_akhir'],
                    'nilai' => $data['nilai'],
                    'nilai_lalu' => $data['lalu'],
                    'page_id' => $data['page'],
                    'anggaran' => $data['anggaran'],
                    'jns_ang' => $data['jns_ang'],
                    'jns_angkas' => $data['status_ang'],
                    'jns_beban' => $data['jenis'],
                    'revisi' => $data['revisi'],
                    'username' => Auth::user()->nama,
                ])->delete();
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

    public function getInsertAllSpdTemp(Request $request)
    {
        $data = $request->data;
        $kd_skpd = left($data['kd_skpd'], 17);
        $skpd = $data['kd_skpd'];
        $jns_ang = $data['jns_ang'];
        $tgl = $data['tanggal'];
        $bulanAwal = $data['bln_awal'];
        $bulanAkhir = $data['bln_akhir'];
        $jenis = $data['jenis'];
        $revisi = $data['revisi'];
        $nomor = $data['nomor'];
        $page = $data['page'];
        $status_angkas = $data['status_ang'];
        $sts_ang = tbStatusAngkas($status_angkas);
        DB::beginTransaction();

        try {
            if ($jenis == 5) {
                if ($revisi == 1) {
                    $data =  DB::statement(
                        "INSERT spd_temp (kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, nilai, 
                        created_at, jns_ang, jns_angkas, jns_beban, nilai_lalu, anggaran, page_id, revisi, username)
                        SELECT a.kd_unit, a.kd_sub_kegiatan, a.kd_rek6, ?, ?, nilai AS nilai,
                            ?, ?, ?, ?, lalu, a.total_ubah AS anggaran, ?, ?, ?
                            FROM
                            (
                                SELECT a.kd_skpd AS kd_unit, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_rek6 , b.nm_rek6 , SUM(b.nilai) AS total_ubah, LEFT(a.kd_skpd, 17) kd_skpd 
                                    FROM trskpd a
                                    INNER JOIN trdrka b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_skpd= b.kd_skpd
                                    INNER JOIN ms_sub_kegiatan c ON a.kd_sub_kegiatan= c.kd_sub_kegiatan 
                                WHERE LEFT(b.kd_skpd, 17) = ? AND c.jns_sub_kegiatan= '5' AND b.jns_ang= ? 
                                GROUP BY LEFT(a.kd_skpd, 17), a.kd_skpd, a.kd_program, a.nm_program, b.kd_sub_kegiatan, a.nm_sub_kegiatan, b.kd_rek6, b.nm_rek6 
                            ) a LEFT JOIN 
                            (
                                SELECT kd_sub_kegiatan, b.kd_rek6, LEFT(kd_skpd, 17) kd_skpd, kd_skpd AS kd_unit, SUM($sts_ang) AS nilai 
                                    FROM trdskpd_ro b 
                                WHERE (b.bulan BETWEEN ? AND ?) AND LEFT(kd_skpd, 17) = ? 
                                GROUP BY LEFT(kd_skpd, 17), kd_skpd, kd_sub_kegiatan, kd_rek6 
                            ) b ON a.kd_unit= b.kd_unit AND a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_rek6= b.kd_rek6 LEFT JOIN 
                            (
                                SELECT kd_unit AS kd_skpd, kd_sub_kegiatan, kd_rek6, isnull(SUM(a.nilai), 0) AS lalu 
                                    FROM trdspd a
                                    LEFT JOIN trhspd b ON a.no_spd= b.no_spd 
                                WHERE LEFT(b.kd_skpd, 17) = ?  AND a.no_spd != ? AND b.tgl_spd < ? 
                                GROUP BY kd_unit, kd_sub_kegiatan, kd_rek6 
                            ) c ON a.kd_unit= c.kd_skpd AND a.kd_sub_kegiatan= c.kd_sub_kegiatan AND a.kd_rek6= c.kd_rek6 
                        WHERE a.kd_sub_kegiatan NOT IN(SELECT TOP 0 x.kd_sub_kegiatan FROM trskpd x INNER JOIN ms_sub_kegiatan y ON x.kd_sub_kegiatan= y.kd_sub_kegiatan 
                                WHERE LEFT (x.kd_skpd, 17) = ? AND y.jns_sub_kegiatan IN ('61', '62', '4'))
                            AND NOT EXISTS (
                                SELECT 1 FROM spd_temp where 
                                left(a.kd_unit, 17) = left(spd_temp.kd_skpd, 17) AND a.kd_sub_kegiatan = spd_temp.kd_sub_kegiatan  
                                AND a.kd_rek6 = spd_temp.kd_rek6 AND spd_temp.nilai_lalu = lalu
                                AND spd_temp.anggaran = anggaran AND spd_temp.bulan_awal = ? 
                                AND spd_temp.bulan_akhir = ? AND spd_temp.jns_ang = ?
                                AND spd_temp.jns_angkas = ? AND spd_temp.jns_beban = ? and spd_temp.page_id = ?
                            ) 
                        ORDER BY a.kd_unit, a.kd_sub_kegiatan",
                        [
                            $bulanAwal, $bulanAkhir, date('Y-m-d H:i:s'), $jns_ang, $status_angkas,
                            $jenis, $page, $revisi, Auth()->user()->nama,
                            $kd_skpd, $jns_ang, $bulanAwal, $bulanAkhir, $kd_skpd, $kd_skpd, $nomor, $tgl, $kd_skpd,
                            $bulanAwal, $bulanAkhir, $jns_ang, $status_angkas, $jenis, $page
                        ]
                    );
                } else {
                    $data = DB::statement(
                        "INSERT spd_temp (kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, nilai, 
                        created_at, jns_ang, jns_angkas, jns_beban, nilai_lalu, anggaran, page_id, revisi, username)
                        SELECT a.kd_unit, a.kd_sub_kegiatan, a.kd_rek6, ?, ?, nilai - isnull(lalu_tw, 0) AS nilai, 
                        ?, ?, ?, ?, lalu, a.total_ubah AS anggaran, ?, ?
                            FROM
                            (
                                SELECT a.kd_skpd AS kd_unit, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,
                                        b.kd_rek6, b.nm_rek6, SUM(b.nilai) AS total_ubah, LEFT(a.kd_skpd, 17) kd_skpd 
                                    FROM trskpd a
                                    INNER JOIN trdrka b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_skpd= b.kd_skpd
                                    INNER JOIN ms_sub_kegiatan c ON a.kd_sub_kegiatan= c.kd_sub_kegiatan 
                                WHERE LEFT(b.kd_skpd, 17) = ? AND c.jns_sub_kegiatan= '5' AND b.jns_ang = ? 
                                GROUP BY LEFT(a.kd_skpd, 17), a.kd_skpd, a.kd_program, a.nm_program, b.kd_sub_kegiatan,
                                        a.nm_sub_kegiatan, b.kd_rek6, b.nm_rek6 
                            ) a LEFT JOIN 
                            (
                                SELECT kd_sub_kegiatan, b.kd_rek6, LEFT(kd_skpd, 17) kd_skpd, kd_skpd AS kd_unit, SUM($sts_ang) AS nilai 
                                    FROM trdskpd_ro b 
                                WHERE b.bulan >= ? AND b.bulan <= ? AND LEFT(kd_skpd, 17) = ? 
                                GROUP BY LEFT(kd_skpd, 17), kd_skpd, kd_sub_kegiatan, kd_rek6 
                            ) b ON a.kd_unit= b.kd_unit AND a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_rek6= b.kd_rek6 LEFT JOIN 
                            (
                                SELECT kd_unit AS kd_skpd, kd_sub_kegiatan, kd_rek6, isnull(SUM(a.nilai), 0) AS lalu 
                                    FROM trdspd a
                                    LEFT JOIN trhspd b ON a.no_spd= b.no_spd 
                                WHERE LEFT(b.kd_skpd, 17) = ? AND a.no_spd != ? AND b.tgl_spd < ? 
                                GROUP BY kd_unit, kd_sub_kegiatan, kd_rek6 
                            ) c ON a.kd_unit= c.kd_skpd AND a.kd_sub_kegiatan= c.kd_sub_kegiatan AND a.kd_rek6= c.kd_rek6 LEFT JOIN 
                            (
                                SELECT kd_unit AS kd_skpd, kd_sub_kegiatan, kd_rek6, isnull(SUM(a.nilai), 0) AS lalu_tw 
                                    FROM trdspd a
                                    LEFT JOIN trhspd b ON a.no_spd= b.no_spd 
                                WHERE LEFT(b.kd_skpd, 17) = ? AND b.bulan_awal= ? AND b.bulan_akhir= ? AND a.no_spd != ? AND b.tgl_spd < ? 
                                GROUP BY kd_unit, kd_sub_kegiatan, kd_rek6 
                            ) d ON a.kd_unit= d.kd_skpd AND a.kd_sub_kegiatan= d.kd_sub_kegiatan AND a.kd_rek6= d.kd_rek6 
                        WHERE a.kd_unit + a.kd_sub_kegiatan + a.kd_rek6 NOT IN
                            (SELECT b.kd_skpd + b.kd_sub_kegiatan + b.kd_rek6 
                                FROM trskpd a
                                INNER JOIN trdrka b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_skpd= b.kd_skpd
                                INNER JOIN ms_sub_kegiatan c ON a.kd_sub_kegiatan= c.kd_sub_kegiatan 
                                WHERE LEFT(b.kd_skpd, 17) = ? AND c.jns_sub_kegiatan= '6' 
                                GROUP BY b.kd_skpd, b.kd_sub_kegiatan, b.kd_rek6 
                            ) 
                            AND NOT EXISTS (
                                SELECT 1 FROM spd_temp where 
                                left(a.kd_unit, 17) = left(spd_temp.kd_skpd, 17) AND a.kd_sub_kegiatan = spd_temp.kd_sub_kegiatan  
                                AND a.kd_rek6 = spd_temp.kd_rek6 AND spd_temp.nilai_lalu = lalu
                                AND spd_temp.anggaran = anggaran AND spd_temp.bulan_awal = ? 
                                AND spd_temp.bulan_akhir = ? AND spd_temp.jns_ang = ?
                                AND spd_temp.jns_angkas = ? AND spd_temp.jns_beban = ? and spd_temp.page_id = ?
                            )
                        ORDER BY a.kd_unit, a.kd_sub_kegiatan",
                        [
                            $bulanAwal, $bulanAkhir, date('Y-m-d H:i:s'), $jns_ang, $status_angkas,
                            $jenis, $page, $revisi, Auth()->user()->nama,
                            $kd_skpd, $jns_ang, $bulanAwal, $bulanAkhir, $kd_skpd, $kd_skpd, $nomor, $tgl, $kd_skpd,
                            $bulanAwal, $bulanAkhir, $nomor, $tgl, $kd_skpd, $bulanAwal, $bulanAkhir, $jns_ang, $status_angkas, $jenis, $page
                        ]
                    );     
                
                }   
            } else {
                $data = DB::statement(
                    "INSERT spd_temp (kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, nilai, 
                    created_at, jns_ang, jns_angkas, jns_beban, nilai_lalu, anggaran, page_id, revisi, username)
                    SELECT a.kd_skpd as kd_unit, a.kd_sub_kegiatan, a.kd_rek6, ?, ?, (nilai - isnull(lalu_tw, 0)) AS nilai,
                            ?, ?, ?, ?, isnull(lalu, 0) as lalu, a.total_ubah AS anggaran, ?, ?
                        FROM
                        (
                            SELECT b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_rek6,
                                b.nm_rek6, SUM(nilai) AS total_ubah, b.kd_skpd 
                                FROM trskpd a
                                INNER JOIN trdrka b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan  AND a.kd_skpd= b.kd_skpd
                                INNER JOIN ms_sub_kegiatan c ON b.kd_sub_kegiatan= c.kd_sub_kegiatan AND b.jns_ang = ? 
                            WHERE b.kd_skpd = ? AND c.jns_sub_kegiatan = '62' 
                            GROUP BY b.kd_rek6, b.nm_rek6, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_skpd 
                        ) a LEFT JOIN 
                        (
                            SELECT kd_rek6, kd_sub_kegiatan, kd_skpd, SUM($sts_ang) AS nilai 
                                FROM trdskpd_ro b 
                            WHERE b.bulan >= ? AND b.bulan <= ? AND kd_skpd = ? 
                            GROUP BY kd_rek6, kd_sub_kegiatan, kd_skpd 
                        ) b ON a.kd_rek6= b.kd_rek6 AND a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_skpd= b.kd_skpd LEFT JOIN 
                        (
                            SELECT a.kd_rek6, kd_sub_kegiatan, SUM(a.nilai) AS lalu 
                                FROM trdspd a
                                LEFT JOIN trhspd b ON a.no_spd= b.no_spd 
                            WHERE b.kd_skpd = ? AND a.no_spd != ? AND b.tgl_spd < ? 
                            GROUP BY a.kd_rek6, kd_sub_kegiatan 
                        ) c ON a.kd_rek6= c.kd_rek6 AND a.kd_sub_kegiatan= c.kd_sub_kegiatan LEFT JOIN 
                        (
                            SELECT kd_unit AS kd_skpd, kd_sub_kegiatan, kd_rek6, isnull(SUM(a.nilai), 0) AS lalu_tw 
                                FROM trdspd a
                                LEFT JOIN trhspd b ON a.no_spd= b.no_spd 
                            WHERE b.kd_skpd = ? AND b.bulan_awal= ? AND b.bulan_akhir= ? AND a.no_spd != ? AND b.tgl_spd< ? 
                            GROUP BY kd_unit, kd_sub_kegiatan, kd_rek6 
                        ) d ON a.kd_rek6= d.kd_rek6 AND a.kd_sub_kegiatan= d.kd_sub_kegiatan 
                        WHERE a.kd_sub_kegiatan NOT IN
                            (
                                SELECT a.kd_sub_kegiatan FROM trskpd a
                                INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan 
                                WHERE a.kd_skpd = ? AND b.jns_sub_kegiatan = '5' 
                            ) AND NOT EXISTS (
                                SELECT * FROM spd_temp where 
                                left(a.kd_skpd, 17) = left(spd_temp.kd_skpd, 17)  AND spd_temp.nilai_lalu = lalu
                                AND isnull(spd_temp.anggaran,0) = anggaran AND a.kd_sub_kegiatan = spd_temp.kd_sub_kegiatan  
                                AND a.kd_rek6 = spd_temp.kd_rek6  
                                AND spd_temp.bulan_awal = ? AND spd_temp.bulan_akhir = ? AND spd_temp.jns_ang = ?
                                AND spd_temp.jns_angkas = ? AND spd_temp.jns_beban = ? and spd_temp.page_id = ?
                            )
                            ORDER BY a.kd_sub_kegiatan",
                    [
                        $bulanAwal, $bulanAkhir, date('Y-m-d H:i:s'), $jns_ang, $status_angkas,
                        $jenis, $page, $revisi, Auth()->user()->nama,
                        $jns_ang, $skpd, $bulanAwal, $bulanAkhir, $skpd, $skpd, $nomor, $tgl, $skpd, $bulanAwal, $bulanAkhir, $nomor, $tgl, $skpd,
                        $bulanAwal, $bulanAkhir, $jns_ang, $status_angkas, $jenis, $page
                    ]
                );
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

    public function getDeleteAllSpdTemp(Request $request)
    {
        $data = $request->data;
        DB::beginTransaction();
       
        try {
            DB::table('spd_temp')
                ->where([
                    'jns_ang' => $data['jns_ang'],
                    'bulan_awal' => $data['bln_awal'],
                    'bulan_akhir' => $data['bln_akhir'],
                    'jns_beban' => $data['jenis'],
                    'page_id' => $data['page'],
                    'jns_angkas' => $data['status_ang'],
                    'revisi' => $data['revisi'],
                ])->delete();
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

    public function simpanSPP(Request $request)
    {
        $data = $request->data;
        $status_angkas = $data['status_angkas'];
        $sts_ang = tbStatusAngkas($status_angkas);
        $user = Auth::user()->nama;

        DB::beginTransaction();
        try {
            $nospdexplode = explode("/", $data['nomor']);
            $nospd = $nospdexplode[2];
            
            $nomorspd = DB::table('trhspd')->whereRaw("substring(no_spd, 12, 6) = '$nospd'")->count();
            if ($nomorspd > 0) {
                DB::rollBack();
                return response()->json([
                    'message' => '2'
                ]);
            } else {
                if($data['revisi'] == '1'){
                    $revisi = DB::table('trhspd')->selectRaw('max(revisi_ke)+1 as revisi')
                                ->where(['kd_skpd' => $data['skpd'], 'bulan_awal' => $data['bulan_awal'], 'bulan_akhir' => $data['bulan_akhir']])
                                ->first();
                    
                    $nmskpd = DB::table('ms_skpd')->select('nm_skpd')
                                ->where(['kd_skpd' => $data['skpd']])->first();
    
                    DB::table('trhspd')->insert([
                        'no_spd' => $data['nomor'],
                        'tgl_spd' => $data['tanggal'],
                        'kd_skpd' => $data['skpd'],
                        'nm_skpd' => Str::of($nmskpd->nm_skpd)->trim(),
                        'jns_beban' => $data['jenis'],
                        'bulan_awal' => $data['bulan_awal'],
                        'bulan_akhir' => $data['bulan_akhir'],
                        'kd_bkeluar' => $data['nipp'],
                        'klain' => Str::of($data['keterangan'])->trim(),
                        'username' => $user,
                        'tglupdate' => date('Y-m-d H:i:s'),
                        'total' => $data['totalNilai'],
                        'status' => '0',
                        'revisi_ke' => $revisi,
                        'jns_ang' => $data['jenis_anggaran'],
                        'jns_angkas' => $sts_ang,
                    ]);
                    
                    if (isset($data['daftar_spd'])) {
                        DB::table('trdspd')->insert(array_map(function ($value) use ($data) {
                            return [
                                'no_spd' => $value['nomor'],
                                'kd_program' => kd_Program($value['kd_sub_kegiatan'])->kd_program,
                                'nm_program' => kd_Program($value['kd_sub_kegiatan'])->nm_program,
                                'kd_kegiatan' => kd_kegiatan($value['kd_sub_kegiatan'])->kd_kegiatan,
                                'nm_kegiatan' => kd_kegiatan($value['kd_sub_kegiatan'])->nm_kegiatan,
                                'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                                'nm_sub_kegiatan' => kd_kegiatan($value['kd_sub_kegiatan'])->nm_sub_kegiatan,
                                'kd_rek6' => $value['kd_rek6'],
                                'nm_rek6' => kd_kegiatan($value['kd_rek6'])->nm_kegiatan,
                                'nilai' => $value['nilai'],
                                'kd_unit' => $value['kd_skpd'],
                            ];
                        }, $data['daftar_spd']));
                    }
                } else {
                    $nmskpd = DB::table('ms_skpd')->select('nm_skpd')
                                ->where(['kd_skpd' => $data['skpd']])->first();
    
                    DB::table('trhspd')->insert([
                        'no_spd' => $data['nomor'],
                        'tgl_spd' => $data['tanggal'],
                        'kd_skpd' => $data['skpd'],
                        'nm_skpd' => Str::of($nmskpd->nm_skpd)->trim(),
                        'jns_beban' => $data['jenis'],
                        'bulan_awal' => $data['bulan_awal'],
                        'bulan_akhir' => $data['bulan_akhir'],
                        'kd_bkeluar' => $data['nipp'],
                        'klain' => Str::of($data['keterangan'])->trim(),
                        'username' => $user,
                        'tglupdate' => date('Y-m-d H:i:s'),
                        'total' => $data['totalNilai'],
                        'status' => '0',
                        'revisi_ke' => '0',
                        'jns_ang' => $data['jenis_anggaran'],
                    ]);
                    
                    if (isset($data['daftar_spd'])) {
                        DB::table('trdspd')->insert(array_map(function ($value) use ($data) {
                            return [
                                'no_spd' => $data['nomor'],
                                'kd_program' => kd_Program($value['kd_sub_kegiatan']),
                                'nm_program' => nm_Program($value['kd_sub_kegiatan']),
                                'kd_kegiatan' => kd_kegiatan($value['kd_sub_kegiatan']),
                                'nm_kegiatan' => nm_kegiatan($value['kd_sub_kegiatan']),
                                'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                                'nm_sub_kegiatan' => kd_sub_kegiatan($value['kd_sub_kegiatan']),
                                'kd_rek6' => $value['kd_rek6'],
                                'nm_rek6' => kd_rek($value['kd_rek6']),
                                'nilai' => $value['nilai'],
                                'kd_unit' => $value['kd_skpd'],
                            ];
                        }, $data['daftar_spd']));
                    }
                }

                $user = Auth()->user()->nama; 
                DB::table('spd_temp')->where(['username' => $user])->Delete();
                
                DB::commit();
                return response()->json([
                    'message' => '1'
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $nospd = $request->no_spd;
        try {
            DB::beginTransaction();
            DB::table('trhspd')->where(['no_spd' => $nospd])->delete();
            DB::table('trdspd')->where(['no_spd' => $nospd])->delete();
            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function cetakOto(Request $request)
    {
        $nospd = $request->no_spd;
        $nip = $request->nip;
        $tnspd = $request->tnspd;
        $tambahan = $request->tambahan;
        $jenis = $request->jenis;
        $total_ingat = count_ingat();

        $tambahanbln = $tambahan ? "Tambahan" : ""; 
        $konfig = DB::table('trkonfig_spd')->first();
        $jenis = DB::table('trhspd')->where(['no_spd' => $nospd])->first();
        $no_dpa = DB::table('trhrka')->where(['kd_skpd' => $jenis->kd_skpd])->first();
        $kepala_skpd = DB::table('ms_ttd')->where(['nip' => $jenis->kd_bkeluar])->first();
        $total_anggaran = DB::table('trdrka')
			->whereRaw("left(kd_skpd, 17) = left(?, 17) and left(kd_rek6, 1) = ?", [$jenis->kd_skpd, $jenis->jns_beban])
            ->where(['jns_ang' => 'P2'])->sum('nilai');

        $spd_lalu = DB::select(
                "SELECT TOP (1) WITH TIES no_spd, RANK() OVER (PARTITION BY kd_skpd, bulan_awal, bulan_akhir ORDER BY jns_ang DESC) AS ranking
			        FROM trhspd
			        WHERE kd_skpd = ? AND bulan_awal < ? AND bulan_akhir < ? AND jns_ang <= 'P2' and revisi_ke ='2'
			        ORDER BY RANK() OVER (PARTITION BY kd_skpd, bulan_awal, bulan_akhir ORDER BY jns_ang DESC)",
			        [$jenis->kd_skpd, $jenis->bulan_awal, $jenis->bulan_akhir, $jenis->jns_ang]
                );
            return $spd_lalu;
        if (count($spd_lalu) == 0) $spd_lalu = [null];

		$total_spd_lalu = DB::table('trhspd')->wherein('no_spd', $spd_lalu)->sum('total');
        
        $ttd = DB::table('ms_ttd')->where(['nip' => $nip])->first();
        
        $view = view('penatausahaan.spd.spd_belanja.cetak.cetak-otori', array(
            'jenis' => $jenis,
            'nospd' => $nospd,
            'total_ingat' => $total_ingat,
            'konfig' => $konfig,
            'no_dpa' => $no_dpa,
            'data' => $jenis,
            'ttd' => $ttd,
            'total_spd_lalu' => $total_spd_lalu,
            'tambahanbln' => $tambahanbln,
            'total_anggaran' => $total_anggaran,
            'kepala_skpd' => $kepala_skpd,
            'jenis' => $jenis->jns_beban == 5 ? 'Belanja' : 'Pembiayaan',
		));
        if ($jenis == 'pdf') {
            $pdf = PDF::loadHtml($view)->setPaper('a4');
            return $pdf->stream('laporan.pdf');
        }else if ($request->jenis == 'excel') {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=laporan.xls");
			return $view;
        } else {
            return $view;
        }
    }

    public function cetakLamp(Request $request)
    {
        $nospd = $request->no_spd;
        $nip = $request->nip;
        $tnspd = $request->tnspd;
        $tambahan = $request->tambahan;
        $jenis = $request->jenis;

        $konfig = DB::table('trkonfig_spd')->first();
        $jenis = DB::table('trhspd')->where(['no_spd' => $nospd])->first();
        $no_dpa = DB::table('trhrka')->where(['kd_skpd' => $jenis->kd_skpd])->first();
        $kepala_skpd = DB::table('ms_ttd')->where(['nip' => $jenis->kd_bkeluar])->first();
        $total_anggaran = DB::table('trdrka')
			->whereRaw("left(kd_skpd, 17) = left(?, 17) and left(kd_rek6, 1) = ?", [$jenis->kd_skpd, $jenis->jns_beban])
            ->where(['jns_ang' => $jenis->jns_ang])->sum('nilai');
        
        $ttd = DB::table('ms_ttd')->where(['nip' => $nip])->first();

        $spd_lalu = DB::select(
            "SELECT TOP (1) WITH TIES no_spd, RANK() OVER (PARTITION BY kd_skpd, bulan_awal, bulan_akhir ORDER BY jns_ang DESC) AS ranking
                FROM trhspd
                WHERE kd_skpd = ? AND bulan_awal < ? AND bulan_akhir < ? AND jns_ang <= ?
                ORDER BY RANK() OVER (PARTITION BY kd_skpd, bulan_awal, bulan_akhir ORDER BY jns_ang DESC)",
                [$jenis->kd_skpd, $jenis->bulan_awal, $jenis->bulan_akhir, $jenis->jns_ang]
            );
        if (count($spd_lalu) == 0) $spd_lalu = [null];

        $datalamp = DB::select(
			"SELECT * FROM (
				SELECT spd.kd_program as urutan, spd.kd_program AS kode, spd.nm_program AS nama, '' AS kd_rek, '' AS nm_rek, SUM(trdrka.nilai) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, 'program' AS jenis
				FROM (
					SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai, jns_ang
					FROM trhspd
					JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
					WHERE trhspd.no_spd = ?
				) spd
				LEFT JOIN (
					SELECT h.kd_skpd, d.kd_sub_kegiatan, d.kd_rek6, SUM(d.nilai) AS nilai FROM trhspd h
					JOIN trdspd d ON h.no_spd = d.no_spd
					WHERE h.no_spd IN ?
					GROUP BY h.kd_skpd, d.kd_sub_kegiatan, d.kd_rek6
				) spd_lalu
				ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan AND spd.kd_rek6 = spd_lalu.kd_rek6
				JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6 AND trdrka.jns_ang = spd.jns_ang
				GROUP BY spd.kd_program, spd.nm_program, spd.kd_skpd

				UNION ALL

				SELECT spd.kd_program+'.'+spd.kd_kegiatan as urutan,  spd.kd_kegiatan AS kode, spd.nm_kegiatan AS nama, '' AS kd_rek, '' AS nm_rek, SUM(trdrka.nilai) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, 'kegiatan' AS jenis
				FROM (
					SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai, jns_ang
					FROM trhspd
					JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
					WHERE trhspd.no_spd = ?
				) spd
				LEFT JOIN (
					SELECT h.kd_skpd, d.kd_sub_kegiatan, d.kd_rek6, SUM(d.nilai) AS nilai FROM trhspd h
					JOIN trdspd d ON h.no_spd = d.no_spd
					WHERE h.no_spd IN ?
					GROUP BY h.kd_skpd, d.kd_sub_kegiatan, d.kd_rek6
				) spd_lalu
				ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan AND spd.kd_rek6 = spd_lalu.kd_rek6
				JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6 AND trdrka.jns_ang = spd.jns_ang
				GROUP BY spd.kd_kegiatan, spd.nm_kegiatan, spd.kd_skpd, spd.kd_program

				UNION ALL

				SELECT spd.kd_program+'.'+spd.kd_kegiatan+'.'+spd.kd_sub_kegiatan as urutan, spd.kd_sub_kegiatan AS kode, spd.nm_sub_kegiatan AS nama, '' AS kd_rek, '' AS nm_rek, SUM(trdrka.nilai) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, 'sub_kegiatan' AS jenis
				FROM (
					SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai, jns_ang
					FROM trhspd
					JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
					WHERE trhspd.no_spd = ?
				) spd
				LEFT JOIN (
					SELECT h.kd_skpd, d.kd_sub_kegiatan, d.kd_rek6, SUM(d.nilai) AS nilai FROM trhspd h
					JOIN trdspd d ON h.no_spd = d.no_spd
					WHERE h.no_spd IN ?
					GROUP BY h.kd_skpd, d.kd_sub_kegiatan, d.kd_rek6
				) spd_lalu
				ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan AND spd.kd_rek6 = spd_lalu.kd_rek6
				JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6 AND trdrka.jns_ang = spd.jns_ang
				GROUP BY spd.kd_sub_kegiatan, spd.nm_sub_kegiatan, spd.kd_skpd, spd.kd_program, spd.kd_kegiatan

				UNION ALL

				SELECT  spd.kd_program+'.'+spd.kd_kegiatan+'.'+spd.kd_sub_kegiatan+'.'+spd.kd_rek6 as urutan, spd.kd_sub_kegiatan AS kode, spd.nm_sub_kegiatan AS nama, spd.kd_rek6 AS kd_rek, spd.nm_rek6 AS nm_rek, SUM(trdrka.nilai) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, 'rekening' AS jenis
				FROM (
					SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai, jns_ang
					FROM trhspd
					JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
					WHERE trhspd.no_spd = ?
				) spd
				LEFT JOIN (
					SELECT h.kd_skpd, d.kd_sub_kegiatan, d.kd_rek6, SUM(d.nilai) AS nilai FROM trhspd h
					JOIN trdspd d ON h.no_spd = d.no_spd
					WHERE h.no_spd IN ?
					GROUP BY h.kd_skpd, d.kd_sub_kegiatan, d.kd_rek6
				) spd_lalu
				ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan AND spd.kd_rek6 = spd_lalu.kd_rek6
				JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6 AND trdrka.jns_ang = spd.jns_ang
				GROUP BY spd.kd_sub_kegiatan, spd.nm_sub_kegiatan, spd.kd_skpd, spd.kd_rek6, spd.nm_rek6, spd.kd_program, spd.kd_kegiatan
			) spd ORDER BY urutan",
			[
				$jenis->no_spd, $spd_lalu,
				$jenis->no_spd, $spd_lalu,
				$jenis->no_spd, $spd_lalu,
				$jenis->no_spd, $spd_lalu,
			]
		);
        dd($datalamp);
        
        $view = view('penatausahaan.spd.spd_belanja.cetak.cetak-lampiran', array(
            'jenis' => $jenis,
            'nospd' => $nospd,
            'konfig' => $konfig,
            'no_dpa' => $no_dpa,
            'data' => $jenis,
            'ttd' => $ttd,
            // 'datalamp' => $datalamp,
            'total_anggaran' => $total_anggaran,
            'kepala_skpd' => $kepala_skpd,
            'jenis' => $jenis->jns_beban == 5 ? 'Belanja' : 'Pembiayaan',
		));
        if ($jenis == 'pdf') {
            $pdf = PDF::loadHtml($view)->setPaper('a4');
            return $pdf->stream('laporan.pdf');
        }else if ($request->jenis == 'excel') {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=laporan.xls");
			return $view;
        } else {
            return $view;
        }
    }
}
