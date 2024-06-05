<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use PDF;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class BendaharaUmumDaerahController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'bendahara' => DB::table('ms_ttd')
                ->where(['kd_skpd' => $kd_skpd])
                ->whereIn('kode', ['BK', 'BPP'])
                ->orderBy('nip')
                ->orderBy('nama')
                ->get(),
            'pa_kpa' => DB::table('ms_ttd')->whereIn('kode', ['PA', 'KPA'])->orderBy('nama')->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'daftar_skpd' => DB::table('ms_skpd')->orderBy('kd_skpd')->get(),
            'daftar_pengirim' => DB::table('ms_pengirim')
                ->selectRaw("kd_pengirim,nm_pengirim,kd_skpd")
                // ->orderByRaw("cast(kd_pengirim as int)")
                ->orderByRaw("kd_pengirim")
                ->get(),
            'daftar_wilayah' => DB::table('ms_wilayah')->selectRaw("kd_wilayah,nm_wilayah")->orderByRaw("cast(kd_wilayah as int)")->get(),
            'bud' => DB::table('ms_ttd')->select('nip', 'nama', 'jabatan')->whereIn('kode', ['BUD', 'PA'])->get(),
            'daftar_rekening' => DB::table('trdrka')->select('kd_rek6', 'nm_rek6')->groupBy('kd_rek6', 'nm_rek6')->get(),
            'daftar_org' => DB::table('ms_organisasi')
                ->select('kd_org', 'nm_org')
                ->get(),
            'daftar_anggaran' => DB::table('tb_status_anggaran')
                ->where(['status_aktif' => '1'])
                ->get()
        ];
        return view('bud.laporan_bendahara.index')->with($data);
    }

    public function realisasiPendapatan(Request $request)
    {
        $skpd_global = Auth::user()->kd_skpd;
        $pilihan = $request->pilihan;
        $periode = $request->periode;
        $anggaran = $request->anggaran;
        $jenis = $request->jenis;
        $ttd = $request->ttd;
        $tgl_ttd = $request->tgl_ttd;
        $kd_skpd = $request->kd_skpd;
        $kd_unit = $request->kd_unit;
        $jenis_print = $request->jenis_print;
        $pilihan_tanggal = $request->pilihan_tanggal;
        $tanggal = $request->tanggal;

        if ($ttd) {
            $tanda_tangan = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd])->whereIn('kode', ['BUD', 'PA'])->first();
        } else {
            $tanda_tangan = null;
        }

        if ($pilihan == '1' && $pilihan_tanggal == 'bulan') {
            $daftar_realisasi = DB::select("SELECT * FROM penerimaan_kasda_new(?,?) WHERE LEFT(kd_rek,1)='4' AND  len(kd_rek)<=? and left(kd_rek,6)!='410416' ORDER BY urut1,urut2", [$periode, $anggaran, $jenis]);
        } else if ($pilihan == '2' && $pilihan_tanggal == 'bulan') {
            $daftar_realisasi = DB::select("SELECT * FROM penerimaan_kasda_new_skpd(?,?,?) WHERE LEFT(kd_rek,1)='4' AND  len(kd_rek)<=? and left(kd_rek,6)!='410416' ORDER BY urut1,urut2", [$periode, $anggaran, $kd_skpd, $jenis]);
        } else if ($pilihan == '3' && $pilihan_tanggal == 'bulan') {
            $daftar_realisasi  = DB::select("SELECT * FROM penerimaan_kasda_new_unit(?,?,?) WHERE LEFT(kd_rek,1)='4' AND len(kd_rek)<=? and left(kd_rek,6)!='410416' ORDER BY urut1,urut2", [$periode, $anggaran, $kd_unit, $jenis]);
        } else if ($pilihan == '1' && $pilihan_tanggal == 'tanggal') {
            $daftar_realisasi = DB::select("SELECT * FROM penerimaan_kasda_new_tanggal(?,?) WHERE LEFT(kd_rek,1)='4' AND  len(kd_rek)<=? and left(kd_rek,6)!='410416' ORDER BY urut1,urut2", [$tanggal, $anggaran, $jenis]);
        } else if ($pilihan == '2' && $pilihan_tanggal == 'tanggal') {
            $daftar_realisasi = DB::select("SELECT * FROM penerimaan_kasda_new_skpd_tanggal(?,?,?) WHERE LEFT(kd_rek,1)='4' AND  len(kd_rek)<=? and left(kd_rek,6)!='410416' ORDER BY urut1,urut2", [$tanggal, $anggaran, $kd_skpd, $jenis]);
        } else if ($pilihan == '3' && $pilihan_tanggal == 'tanggal') {
            $daftar_realisasi  = DB::select("SELECT * FROM penerimaan_kasda_new_unit_tanggal(?,?,?) WHERE LEFT(kd_rek,1)='4' AND len(kd_rek)<=? and left(kd_rek,6)!='410416' ORDER BY urut1,urut2", [$tanggal, $anggaran, $kd_unit, $jenis]);
        }

        if ($pilihan == '1') {
            $skpd = DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $skpd_global])->first();
        } elseif ($pilihan == '2') {
            $skpd = DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first();
        } elseif ($pilihan == '3') {
            $skpd = DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_unit])->first();
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'tanda_tangan' => $tanda_tangan,
            'daftar_realisasi' => $daftar_realisasi,
            'skpd' => $skpd,
            'tanggal' => $tgl_ttd,
            'periode' => $periode
        ];

        $judul = 'REALISASI_PENDPATAN';

        $view = view('bud.laporan_bendahara.cetak.realisasi_pendapatan')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }
    public function realisasiPendapatan_baru(Request $request)
    {
        $skpd_global = Auth::user()->kd_skpd;
        $pilihan = $request->pilihan;
        $periode = $request->periode;
        $anggaran = $request->anggaran;
        $jenis = $request->jenis;
        $ttd = $request->ttd;
        $tgl_ttd = $request->tgl_ttd;
        $kd_skpd = $request->kd_skpd;
        $kd_unit = $request->kd_unit;
        $jenis_print = $request->jenis_print;

        if ($ttd) {
            $tanda_tangan = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd])->whereIn('kode', ['BUD', 'PA'])->first();
        } else {
            $tanda_tangan = null;
        }

        if ($pilihan == '1') {
            $daftar_realisasi = DB::select("SELECT * FROM penerimaan_kasda_new_blud(?,?) WHERE LEFT(kd_rek,1)='4' AND  len(kd_rek)<=?  ORDER BY urut1,urut2", [$periode, $anggaran, $jenis]);
        } else if ($pilihan == '2') {
            $daftar_realisasi = DB::select("SELECT * FROM penerimaan_kasda_new_blud_skpd(?,?,?) WHERE LEFT(kd_rek,1)='4' AND  len(kd_rek)<=?  ORDER BY urut1,urut2", [$periode, $anggaran, $kd_skpd, $jenis]);
        } else if ($pilihan == '3') {
            $daftar_realisasi  = DB::select("SELECT * FROM penerimaan_kasda_new_blud_unit(?,?,?) WHERE LEFT(kd_rek,1)='4' AND len(kd_rek)<=?  ORDER BY urut1,urut2", [$periode, $anggaran, $kd_unit, $jenis]);
        }

        if ($pilihan == '1') {
            $skpd = DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $skpd_global])->first();
        } elseif ($pilihan == '2') {
            $skpd = DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first();
        } elseif ($pilihan == '3') {
            $skpd = DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_unit])->first();
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'tanda_tangan' => $tanda_tangan,
            'daftar_realisasi' => $daftar_realisasi,
            'skpd' => $skpd,
            'tanggal' => $tgl_ttd,
            'periode' => $periode
        ];

        $judul = 'REALISASI_PENDPATAN';

        $view = view('bud.laporan_bendahara.cetak.realisasi_pendapatan')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }

    public function pembantuPenerimaan(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $spasi = $request->spasi;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        if ($pilihan == '1') {
            $where = "b.tgl_kas=?";
            $where2 = "tanggal = ?";
        } elseif ($pilihan == '2') {
            $where = "b.tgl_kas BETWEEN ? AND ?";
            $where2 = "tanggal BETWEEN ? AND ?";
        }

        $penerimaan1 = DB::table('trdkasin_ppkd as a')
            ->join('trhkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('ms_rek6 as c', function ($join) {
                $join->on('a.kd_rek6', '=', 'c.kd_rek6');
            })->leftJoin('ms_pengirim as d', function ($join) {
                $join->on('a.sumber', '=', 'd.kd_pengirim');
                $join->on('b.kd_skpd', '=', 'd.kd_skpd');
            })->join('ms_skpd as e', function ($join) {
                $join->on('a.kd_skpd', '=', 'e.kd_skpd');
            })
            ->selectRaw("1 as urut,''no_sts,''kd_skpd,e.nm_skpd,''kd_sub_kegiatan,''kd_rek6,b.no_kas,''tgl_kas,ISNULL(d.nm_pengirim, '') nm_pengirim,''nm_rek6,0 rupiah")
            ->where('a.kd_skpd', '!=', '4.02.02.02')
            ->whereRaw("left(a.kd_rek6,4) NOT IN (?,?,?,?) AND a.kd_rek6 NOT IN (?)", ['4101', '4301', '4104', '4201', '420101040001'])
            ->where(function ($query) use ($pilihan, $where, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where, [$periode1, $periode2]);
                }
            })
            ->groupBy('b.no_kas', 'nm_pengirim', 'e.nm_skpd');

        $penerimaan2 = DB::table('trdkasin_ppkd as a')
            ->join('trhkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('ms_rek6 as c', function ($join) {
                $join->on('a.kd_rek6', '=', 'c.kd_rek6');
            })->leftJoin('ms_pengirim as d', function ($join) {
                $join->on('a.sumber', '=', 'd.kd_pengirim');
                $join->on('b.kd_skpd', '=', 'd.kd_skpd');
            })
            ->selectRaw("2 as urut,b.no_sts,a.kd_skpd,'' nm_skpd,a.kd_sub_kegiatan,a.kd_rek6,b.no_kas,b.tgl_kas,'' nm_pengirim,c.nm_rek6,a.rupiah")
            ->where('a.kd_skpd', '!=', '4.02.02.02')
            ->whereRaw("left(a.kd_rek6,4) NOT IN (?,?,?,?) AND a.kd_rek6 NOT IN (?,?,?)", ['4101', '4301', '4104', '4201', '420101040001', '420101040002', '420101040003'])
            ->where(function ($query) use ($pilihan, $where, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where, [$periode1, $periode2]);
                }
            })->unionAll($penerimaan1);

        $penerimaan3 = DB::table('trdkasin_ppkd as a')
            ->join('trhkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('ms_rek6 as c', function ($join) {
                $join->on('a.kd_rek6', '=', 'c.kd_rek6');
            })->leftJoin('ms_pengirim as d', function ($join) {
                $join->on('a.sumber', '=', 'd.kd_pengirim');
                $join->on('b.kd_skpd', '=', 'd.kd_skpd');
            })->join('ms_skpd as e', function ($join) {
                $join->on('a.kd_skpd', '=', 'e.kd_skpd');
            })
            ->selectRaw("1 as urut,''no_sts,''kd_skpd,e.nm_skpd,''kd_sub_kegiatan,''kd_rek6,b.no_kas,''tgl_kas,'' nm_pengirim,''nm_rek6,0 rupiah")
            ->where('a.kd_skpd', '!=', '4.02.02.02')
            ->whereRaw("left(a.kd_rek6,4) IN (?,?,?,?) AND a.kd_rek6 !='410416010001' AND a.kd_rek6 NOT IN (?,?,?)", ['4101', '4301', '4104', '4201', '420101040001', '420101040002', '420101040003'])
            ->where(function ($query) use ($pilihan, $where, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where, [$periode1, $periode2]);
                }
            })
            ->groupBy('b.no_kas', 'e.nm_skpd')->unionAll($penerimaan2);

        $penerimaan4 = DB::table('trdkasin_ppkd as a')
            ->join('trhkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('ms_rek6 as c', function ($join) {
                $join->on('a.kd_rek6', '=', 'c.kd_rek6');
            })->leftJoin('ms_pengirim as d', function ($join) {
                $join->on('a.sumber', '=', 'd.kd_pengirim');
                $join->on('b.kd_skpd', '=', 'd.kd_skpd');
            })
            ->selectRaw("2 as urut,b.no_sts,a.kd_skpd,'' nm_skpd,a.kd_sub_kegiatan,a.kd_rek6,b.no_kas,b.tgl_kas,ISNULL(d.nm_pengirim, '') nm_pengirim,c.nm_rek6,a.rupiah")
            ->where('a.kd_skpd', '!=', '4.02.02.02')
            ->whereRaw("left(a.kd_rek6,4) IN (?,?,?,?) AND a.kd_rek6!='410416010001' AND a.kd_rek6 NOT IN (?,?,?)", ['4101', '4301', '4104', '4201', '420101040001', '420101040002', '420101040003'])
            ->where(function ($query) use ($pilihan, $where, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where, [$periode1, $periode2]);
                }
            })->unionAll($penerimaan3);

        $penerimaan5 = DB::table('trdkasin_ppkd as a')
            ->join('trhkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('ms_rek6 as c', function ($join) {
                $join->on('a.kd_rek6', '=', 'c.kd_rek6');
            })->join('ms_skpd as e', function ($join) {
                $join->on('a.kd_skpd', '=', 'e.kd_skpd');
            })
            ->selectRaw("1 as urut,''no_sts,''kd_skpd,e.nm_skpd,''kd_sub_kegiatan,''kd_rek6,b.no_kas,''tgl_kas,'' nm_pengirim,''nm_rek6,0 rupiah")
            ->where('a.kd_skpd', '=', '4.02.02.02')
            ->where(function ($query) use ($pilihan, $where, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where, [$periode1, $periode2]);
                }
            })
            ->groupBy('b.no_kas', 'e.nm_skpd')->unionAll($penerimaan4);

        $penerimaan6 = DB::table('trdkasin_ppkd as a')
            ->join('trhkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('ms_rek6 as c', function ($join) {
                $join->on('a.kd_rek6', '=', 'c.kd_rek6');
            })
            ->selectRaw("2 as urut,b.no_sts,a.kd_skpd,'' nm_skpd,a.kd_sub_kegiatan,a.kd_rek6,b.no_kas,b.tgl_kas,'' nm_pengirim,b.keterangan nm_rek6,a.rupiah")
            ->where('a.kd_skpd', '=', '4.02.02.02')
            ->where(function ($query) use ($pilihan, $where, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where, [$periode1, $periode2]);
                }
            })->unionAll($penerimaan5);

        $penerimaan7 = DB::table('trkasout_ppkd')
            ->selectRaw("1 AS urut,'' no_sts,'' kd_skpd,nm_skpd,'' kd_sub_kegiatan,'' kd_rek6,[no] as no_kas,'' tgl_kas,'' nm_pengirim,'' nm_rek6,0 rupiah")
            ->where(function ($query) use ($pilihan, $where2, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where2, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where2, [$periode1, $periode2]);
                }
            })->unionAll($penerimaan6);

        $penerimaan8 = DB::table('trkasout_ppkd')
            ->selectRaw("2 AS urut,[no] as no_sts,kd_skpd,'' nm_skpd,kd_sub_kegiatan,kd_rek kd_rek6,[no] no_kas,[tanggal] tgl_kas,'' nm_pengirim,keterangan+' '+nm_rek nm_rek6,nilai as rupiah")
            ->where(function ($query) use ($pilihan, $where2, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where2, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where2, [$periode1, $periode2]);
                }
            })->unionAll($penerimaan7);

        $penerimaan9 = DB::table('penerimaan_non_sp2d')
            ->selectRaw("1 AS urut,'' no_sts,'' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='4.02.02.02') nm_skpd,'' kd_sub_kegiatan,'' kd_rek6,[nomor] AS no_kas,'' tgl_kas,'' nm_pengirim,'' nm_rek6,0 rupiah")
            ->where(function ($query) use ($pilihan, $where2, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where2, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where2, [$periode1, $periode2]);
                }
            })->unionAll($penerimaan8);

        $penerimaan10 = DB::table('penerimaan_non_sp2d')
            ->selectRaw("2 AS urut,'[nomor]' AS no_sts,'3.13.01.17' kd_skpd,'' nm_skpd,'' kd_sub_kegiatan,'' kd_rek6,[nomor] no_kas,[tanggal] tgl_kas,'' nm_pengirim,keterangan nm_rek6,nilai AS rupiah")
            ->where(function ($query) use ($pilihan, $where2, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where2, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where2, [$periode1, $periode2]);
                }
            })->unionAll($penerimaan9);

        $penerimaan11 = DB::table('tkoreksi_penerimaan')
            ->selectRaw("1 AS urut, '' no_sts, '' kd_skpd, (select nm_skpd from ms_skpd where kd_skpd='4.02.02.02') nm_skpd, '' kd_sub_kegiatan, '' kd_rek6, [nomor] AS no_kas,'' tgl_kas, '' nm_pengirim, '' nm_rek6, 0 rupiah")
            ->where(function ($query) use ($pilihan, $where2, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where2, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where2, [$periode1, $periode2]);
                }
            })->unionAll($penerimaan10);

        $penerimaan12 = DB::table('tkoreksi_penerimaan')
            ->selectRaw("2 AS urut, '[nomor]' AS no_sts, '3.13.01.17' kd_skpd, '' nm_skpd, '' kd_sub_kegiatan, '' kd_rek6, [nomor] no_kas,[tanggal] tgl_kas, '' nm_pengirim, keterangan nm_rek6, nilai AS rupiah")
            ->where(function ($query) use ($pilihan, $where2, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where2, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where2, [$periode1, $periode2]);
                }
            })->unionAll($penerimaan11);

        $penerimaan = DB::table(DB::raw("({$penerimaan12->toSql()}) AS sub"))
            ->mergeBindings($penerimaan12)
            ->orderBy('no_kas')
            ->orderBy('urut')
            ->get();

        $total_penerimaan1 = DB::table('trdkasin_ppkd as a')
            ->join('trhkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('ms_rek6 as c', function ($join) {
                $join->on('a.kd_rek6', '=', 'c.kd_rek6');
            })->leftJoin('ms_pengirim as d', function ($join) {
                $join->on('a.sumber', '=', 'd.kd_pengirim');
                $join->on('b.kd_skpd', '=', 'd.kd_skpd');
            })
            ->select(DB::raw("SUM(a.rupiah) as nilai"))
            ->where('a.kd_skpd', '!=', '4.02.02.02')
            ->whereRaw("left(a.kd_rek6,4) NOT IN (?,?,?,?) AND a.kd_rek6 NOT IN (?)", ['4101', '4301', '4104', '4201', '420101040001'])
            ->where(function ($query) use ($pilihan, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw("b.tgl_kas<?", [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw("b.tgl_kas<?", [$periode1]);
                }
            });

        $total_penerimaan2 = DB::table('trdkasin_ppkd as a')
            ->join('trhkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('ms_rek6 as c', function ($join) {
                $join->on('a.kd_rek6', '=', 'c.kd_rek6');
            })->leftJoin('ms_pengirim as d', function ($join) {
                $join->on('a.sumber', '=', 'd.kd_pengirim');
                $join->on('b.kd_skpd', '=', 'd.kd_skpd');
            })
            ->select(DB::raw("SUM(a.rupiah) as nilai"))
            ->where('a.kd_skpd', '!=', '4.02.02.02')
            ->whereRaw("left(a.kd_rek6,4) IN (?,?,?,?) AND a.kd_rek6 !=? AND a.kd_rek6 NOT IN (?)", ['4101', '4301', '4104', '4201', '410416010001', '420101040001'])
            ->where(function ($query) use ($pilihan, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw("b.tgl_kas<?", [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw("b.tgl_kas<?", [$periode1]);
                }
            })->unionAll($total_penerimaan1);

        $total_penerimaan3 = DB::table('trdkasin_ppkd as a')
            ->join('trhkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('ms_rek6 as c', function ($join) {
                $join->on('a.kd_rek6', '=', 'c.kd_rek6');
            })
            ->select(DB::raw("SUM(a.rupiah) as nilai"))
            ->where('a.kd_skpd', '=', '4.02.02.02')
            ->where(function ($query) use ($pilihan, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw("b.tgl_kas<?", [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw("b.tgl_kas<?", [$periode1]);
                }
            })->unionAll($total_penerimaan2);

        $total_penerimaan4 = DB::table('trkasout_ppkd')
            ->select(DB::raw("SUM(nilai) as nilai"))
            ->where(function ($query) use ($pilihan, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw("tanggal<?", [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw("tanggal<?", [$periode1]);
                }
            })
            ->unionAll($total_penerimaan3);

        $total_penerimaan5 = DB::table('penerimaan_non_sp2d')
            ->select(DB::raw("SUM(nilai) as nilai"))
            ->where(function ($query) use ($pilihan, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw("tanggal<?", [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw("tanggal<?", [$periode1]);
                }
            })
            ->unionAll($total_penerimaan4);

        $total_penerimaan6 = DB::table('tkoreksi_penerimaan')
            ->select(DB::raw("SUM(nilai) as nilai"))
            ->where(function ($query) use ($pilihan, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw("tanggal<?", [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw("tanggal<?", [$periode1]);
                }
            })
            ->unionAll($total_penerimaan5);

        $total_penerimaan = DB::table(DB::raw("({$total_penerimaan6->toSql()}) AS sub"))
            ->select(DB::raw("sum(nilai) as nilai"))
            ->mergeBindings($total_penerimaan6)
            ->first();

        if ($ttd) {
            $tanda_tangan = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd])->first();
        } else {
            $tanda_tangan = null;
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal' => $tgl,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_penerimaan' => $penerimaan,
            'total_penerimaan' => $total_penerimaan->nilai,
            'tanda_tangan' => $tanda_tangan
        ];

        $judul = 'BUKU KAS PEMBANTU PENERIMAAN';

        $view = view('bud.laporan_bendahara.cetak.pembantu_penerimaan')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }

    public function bkuTanpaTanggal(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $no_urut = $request->no_urut;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;
        $tahun = tahun_anggaran();

        if ($pilihan == '1') {
            $where = "a.tgl_kas=?";
            $where2 = "a.tgl_kas_bud = ?";
            $where3 = "x.tanggal = ?";
            $where4 = "w.tanggal = ?";
            $where5 = "a.tgl_kas<?";
            $where6 = "a.tgl_kas_bud<?";
            $where7 = "x.tanggal < ?";
            $where8 = "w.tanggal < ?";
            $where9 = "a.tgl_kas < ?";
        } elseif ($pilihan == '2') {
            $where = "a.tgl_kas BETWEEN ? AND ?";
            $where2 = "a.tgl_kas_bud BETWEEN ? AND ?";
            $where3 = "x.tanggal between ? AND ?";
            $where4 = "w.tanggal between ? AND ?";
            $where5 = "a.tgl_kas<?";
            $where6 = "a.tgl_kas_bud<?";
            $where7 = "x.tanggal < ?";
            $where8 = "w.tanggal < ?";
            $where9 = "a.tgl_kas < ?";
        }

        if ($tgl == $tahun . '-01-01') {
            $saldo = DB::table('buku_kas')->selectRaw("'4' kd_rek, 'SALDO AWAL' nama, nilai , 1 jenis");
        }

        $bku1 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->leftJoin('ms_rek3 as c', function ($join) {
                $join->on(DB::raw("left(b.kd_rek6,4)"), '=', 'c.kd_rek3');
            })
            ->selectRaw("LEFT(b.kd_rek6,4) as kd_rek, UPPER(c.nm_rek3) as nama,SUM(rupiah) as nilai, 1 jenis")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?) and  b.kd_rek6 not in (?,?,?,?,?)", ['4', '420101040001', '420101040002', '420101040003', '410416010001', '410409010001'])
            ->where(function ($query) use ($pilihan, $where, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where, [$periode1, $periode2]);
                }
            })
            ->groupByRaw("LEFT(b.kd_rek6,4),c.nm_rek3");

        $bku2 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->leftJoin('ms_rek3 as c', function ($join) {
                $join->on(DB::raw("left(b.kd_rek6,4)"), '=', 'c.kd_rek3');
            })
            ->selectRaw("LEFT(b.kd_rek6,4) as kd_rek, UPPER(c.nm_rek3) as nama,SUM(rupiah) as nilai, 1 jenis")
            ->whereRaw("b.kd_rek6 in (?) and b.sumber<>?", ['410409010001', 'y'])
            ->where(function ($query) use ($pilihan, $where, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where, [$periode1, $periode2]);
                }
            })
            ->groupByRaw("LEFT(b.kd_rek6,4),c.nm_rek3")
            ->unionAll($bku1);

        $bku3 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("LEFT(b.kd_rek6,4) as kd_rek, 'UYHD' as nama,SUM(rupiah) as nilai, 1 jenis")
            ->whereRaw("b.kd_rek6 in (?,?) and a.keterangan like '%(UYHD)%'", ['410409010001', '410412010010'])
            ->where(function ($query) use ($pilihan, $where, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where, [$periode1, $periode2]);
                }
            })
            ->groupByRaw("LEFT(b.kd_rek6,4)")
            ->unionAll($bku2);

        $bku4 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("'414' as kd_rek, 'LAIN-LAIN PENDAPATAN ASLI DAERAH YANG SAH' as nama,SUM(rupiah) as nilai, 1 jenis")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?) and pot_khusus=?", ['5', '1', '3'])
            ->where(function ($query) use ($pilihan, $where, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where, [$periode1, $periode2]);
                }
            })
            ->groupByRaw("a.tgl_kas,LEFT(b.kd_rek6,1)")
            ->unionAll($bku3);

        $bku5 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("510 as kd_rek, 'CONTRA POST' as nama,SUM(rupiah) as nilai, 1 jenis")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?,?) and pot_khusus<>?", ['5', '1', '2', '3'])
            ->where(function ($query) use ($pilihan, $where, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where, [$periode1, $periode2]);
                }
            })
            ->unionAll($bku4);

        $bku6 = DB::table('trhsp2d as a')
            ->join('trhspm as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->join('trdspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })
            ->selectRaw("'5101' kd_rek, 'PENGELUARAN BELANJA GAJI' nama,isnull(SUM(d.nilai), 0) AS nilai, 2 jenis")
            ->whereRaw("a.status_bud = ? AND a.jns_spp = ? AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)", ['1', '4'])
            ->where(function ($query) use ($pilihan, $where2, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where2, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where2, [$periode1, $periode2]);
                }
            })
            ->unionAll($bku5);

        $bku7 = DB::table('trhsp2d as a')
            ->join('trhspm as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->join('trdspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })
            ->selectRaw("'512' kd_rek, 'PENGELUARAN BELANJA NON GAJI' nama,isnull(SUM(d.nilai), 0) AS nilai, 2 jenis")
            ->whereRaw("a.status_bud = ? AND a.jns_spp != ? AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)", ['1', '4'])
            ->where(function ($query) use ($pilihan, $where2, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where2, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where2, [$periode1, $periode2]);
                }
            })
            ->unionAll($bku6);
        // dd($bku7);
        $bku8 = DB::table('pengeluaran_non_sp2d as x')
            ->selectRaw("'513' kd_rek, 'PENGELUARAN NON SP2D' nama,isnull(SUM(x.nilai), 0) AS nilai, 2 jenis")
            ->where(function ($query) use ($pilihan, $where3, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where3, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where3, [$periode1, $periode2]);
                }
            })
            ->unionAll($bku7);

        $bku9 = DB::table('trdrestitusi as b')
            ->join('trhrestitusi as a', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.no_sts', '=', 'b.no_sts');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("'513' kd_rek, 'RESTITUSI' nama,isnull(SUM(b.rupiah), 0) AS nilai, 2 jenis")
            ->where('a.jns_trans', '3')
            ->where(function ($query) use ($pilihan, $where, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where, [$periode1, $periode2]);
                }
            })
            ->unionAll($bku8);

        $bku10 = DB::table('trkasout_ppkd as w')
            ->selectRaw("'514' as kd_rek,'KOREKSI' nama,isnull(SUM(w.nilai),0) as nilai,1 jenis")
            ->where(function ($query) use ($pilihan, $where4, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where4, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where4, [$periode1, $periode2]);
                }
            })
            ->unionAll($bku9);

        $bku11 = DB::table('tkoreksi_penerimaan as w')
            ->selectRaw("'517' as kd_rek,'KOREKSI PENERIMAAN' nama,isnull(SUM(w.nilai),0) as nilai,1 jenis")
            ->where(function ($query) use ($pilihan, $where4, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where4, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where4, [$periode1, $periode2]);
                }
            })
            ->unionAll($bku10);

        $bku12 = DB::table('penerimaan_non_sp2d as w')
            ->selectRaw("'515' AS kd_rek,'DEPOSITO' nama,isnull(SUM(w.nilai), 0) AS nilai,1 jenis")
            ->where('w.jenis', '1')
            ->where(function ($query) use ($pilihan, $where4, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where4, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where4, [$periode1, $periode2]);
                }
            })
            ->unionAll($bku11);

        $bku13 = DB::table('penerimaan_non_sp2d as w')
            ->selectRaw("'516' AS kd_rek,'PENERIMAAN NON PENDAPATAN' nama,isnull(SUM(w.nilai), 0) AS nilai,1 jenis")
            ->where('w.jenis', '2')
            ->where(function ($query) use ($pilihan, $where4, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where4, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where4, [$periode1, $periode2]);
                }
            })
            ->unionAll($bku12);

        $bku14 = DB::table('trkoreksi_pengeluaran as w')
            ->selectRaw("'523' as kd_rek,'KOREKSI PENGELUARAN' nama,isnull(SUM(w.nilai),0) as nilai,2 jenis")
            ->where(function ($query) use ($pilihan, $where4, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->whereRaw($where4, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where4, [$periode1, $periode2]);
                }
            })
            ->unionAll($bku13);

        if (isset($saldo)) {
            $bku15 = $bku14;
        } else {
            $bku15 = $bku14;
        }

        $bku = DB::table(DB::raw("({$bku15->toSql()}) AS sub"))
            ->selectRaw("kd_rek, nama, sum(nilai) nilai, jenis")
            ->mergeBindings($bku15)
            ->groupByRaw("kd_rek, nama, jenis")
            ->orderBy('kd_rek')
            ->get();

        $total_bku1 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->leftJoin('ms_rek3 as c', function ($join) {
                $join->on(DB::raw("left(b.kd_rek6,4)"), '=', 'c.kd_rek3');
            })
            ->selectRaw("a.tgl_kas,LEFT(b.kd_rek6,4) as kd_rek, UPPER(c.nm_rek3) as nama,SUM(rupiah) as nilai, 1 jenis")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?) and b.kd_rek6 not in (?,?,?,?)", ['4', '420101040001', '420101040002', '420101040003', '410416010001'])
            ->where(function ($query) use ($pilihan, $where5, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw($where5, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where5, [$periode1]);
                }
            })
            ->groupByRaw("a.tgl_kas,LEFT(b.kd_rek6,4),c.nm_rek3");

        $total_bku2 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("a.tgl_kas,LEFT(b.kd_rek6,1) as kd_rek, 'CONTRA POST' as nama,SUM(rupiah) as nilai, 1 jenis")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?,?)", ['5', '1', '2'])
            ->where(function ($query) use ($pilihan, $where5, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw($where5, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where5, [$periode1]);
                }
            })
            ->groupByRaw("a.tgl_kas,LEFT(b.kd_rek6,1)")->unionAll($total_bku1);

        $total_bku3 = DB::table('trhsp2d as a')
            ->join('trhspm as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->join('trdspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })
            ->selectRaw("a.tgl_kas_bud, '' kd_rek, 'PENGELUARAN BELANJA NON GAJI' nama,isnull(SUM(d.nilai), 0) AS nilai, 2 jenis")
            ->whereRaw("a.status_bud =? AND  a.jns_spp !=? AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)", ['1', '4'])
            ->where(function ($query) use ($pilihan, $where6, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw($where6, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where6, [$periode1]);
                }
            })
            ->groupByRaw("a.tgl_kas_bud")
            ->unionAll($total_bku2);

        $total_bku4 = DB::table('trhsp2d as a')
            ->join('trhspm as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->join('trdspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })
            ->selectRaw("a.tgl_kas_bud, '' kd_rek, 'PENGELUARAN BELANJA GAJI' nama,isnull(SUM(d.nilai), 0) AS nilai, 2 jenis")
            ->whereRaw("a.status_bud =? AND a.jns_spp =? AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)", ['1', '4'])
            ->where(function ($query) use ($pilihan, $where6, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw($where6, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where6, [$periode1]);
                }
            })
            ->groupByRaw("a.tgl_kas_bud")
            ->unionAll($total_bku3);

        $total_bku5 = DB::table('pengeluaran_non_sp2d as x')
            ->selectRaw("x.tanggal,'' kd_rek, 'PENGELUARAN NON SP2D' nama,isnull(SUM(x.nilai), 0) AS nilai, 2 jenis")
            ->where(function ($query) use ($pilihan, $where7, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw($where7, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where7, [$periode1]);
                }
            })
            ->groupByRaw("x.tanggal")
            ->unionAll($total_bku4);

        $total_bku6 = DB::table('trdrestitusi as b')
            ->join('trhrestitusi as a', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.no_sts', '=', 'b.no_sts');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("a.tgl_kas,'' kd_rek, 'RESTITUSI' nama,isnull(SUM(b.rupiah), 0) AS nilai, 2 jenis")
            ->where('a.jns_trans', '3')
            ->where(function ($query) use ($pilihan, $where9, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw($where9, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where9, [$periode1]);
                }
            })
            ->groupByRaw("a.tgl_kas")
            ->unionAll($total_bku5);

        $total_bku7 = DB::table('trkasout_ppkd as w')
            ->selectRaw("w.tanggal,'' as kd_rek, 'KOREKSI ' nama,isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
            ->where(function ($query) use ($pilihan, $where8, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw($where8, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where8, [$periode1]);
                }
            })
            ->groupByRaw("w.tanggal,w.kd_rek")
            ->unionAll($total_bku6);

        $total_bku8 = DB::table('tkoreksi_penerimaan as w')
            ->selectRaw("w.tanggal,'' as kd_rek, 'KOREKSI PENERIMAAN' nama,isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
            ->where(function ($query) use ($pilihan, $where8, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw($where8, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where8, [$periode1]);
                }
            })
            ->groupByRaw("w.tanggal")
            ->unionAll($total_bku7);

        $total_bku9 = DB::table('trkoreksi_pengeluaran as w')
            ->selectRaw("w.tanggal,'' as kd_rek, 'KOREKSI PENGELUARAN' nama,isnull(SUM(w.nilai), 0) AS nilai, 2 jenis")
            ->where(function ($query) use ($pilihan, $where8, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw($where8, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where8, [$periode1]);
                }
            })
            ->groupByRaw("w.tanggal,w.kd_rek")
            ->unionAll($total_bku8);

        $total_bku10 = DB::table('penerimaan_non_sp2d as w')
            ->selectRaw("w.tanggal,'' as kd_rek, 'DEPOSITO' nama,isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
            ->where('w.jenis', '1')
            ->where(function ($query) use ($pilihan, $where8, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw($where8, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where8, [$periode1]);
                }
            })
            ->groupByRaw("w.tanggal")
            ->unionAll($total_bku9);

        $total_bku11 = DB::table('penerimaan_non_sp2d as w')
            ->selectRaw("w.tanggal,'' as kd_rek, 'PENERIMAAN NON PENDAPATAN' nama,isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
            ->where('w.jenis', '2')
            ->where(function ($query) use ($pilihan, $where8, $tgl, $periode1) {
                if ($pilihan == '1') {
                    $query->whereRaw($where8, [$tgl]);
                } elseif ($pilihan == '2') {
                    $query->whereRaw($where8, [$periode1]);
                }
            })
            ->groupByRaw("w.tanggal")
            ->unionAll($total_bku10);

        $total_bku = DB::table(DB::raw("({$total_bku11->toSql()}) AS sub"))
            ->selectRaw("SUM(CASE WHEN jenis IN('1') THEN nilai ELSE 0 END) as trm_sbl,SUM(CASE WHEN jenis IN('2') THEN nilai ELSE 0 END) as klr_sbl")
            ->mergeBindings($total_bku11)
            ->first();

        $total_saldo_awal = DB::table('buku_kas')->select('nilai')->where(['nomor' => '0'])->first();
        if ($tgl == "2024-01-01") {
            $saldo_awal = 0;
        } else {
            $saldo_awal = $total_saldo_awal->nilai;
        }

        if ($ttd) {
            $tanda_tangan = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd])->first();
        } else {
            $tanda_tangan = null;
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal' => $tgl,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_bku' => $bku,
            'total_bku' => $total_bku,
            'saldo_awal' => $saldo_awal,
            'tanda_tangan' => $tanda_tangan
        ];

        $view = view('bud.laporan_bendahara.cetak.bku_tanpa_tanggal')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } else {
            return $view;
        }
    }

    public function bkuDenganTanggal(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $halaman = $request->halaman;
        $no_urut = $request->no_urut;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        if ($ttd) {
            $tanda_tangan = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd, 'kode' => 'BUD'])->first();
        } else {
            $tanda_tangan = null;
        }

        // $terima1 = DB::table('trhkasin_ppkd as a')
        //     ->join('trdkasin_ppkd as b', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })->leftJoin('ms_rek3 as c', function ($join) {
        //         $join->on(DB::raw("left(b.kd_rek6,3)"), '=', 'c.kd_rek3');
        //     })
        //     ->selectRaw("a.tgl_kas, SUM ( rupiah ) AS nilai,1 jenis")
        //     ->whereRaw("LEFT( b.kd_rek6, 1 ) IN ( ? ) AND a.tgl_kas BETWEEN ? AND ?", ['4', $periode1, $periode2])
        //     ->groupByRaw("a.tgl_kas");

        // $terima2 = DB::table('trhkasin_ppkd as a')
        //     ->join('trdkasin_ppkd as b', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->selectRaw("a.tgl_kas,SUM ( rupiah ) AS nilai,1 jenis")
        //     ->whereRaw("LEFT (b.kd_rek6,1) IN (?,?) AND pot_khusus=? AND a.tgl_kas BETWEEN ? AND ?", ['5', '1', '3', $periode1, $periode2])
        //     ->groupByRaw("a.tgl_kas")
        //     ->unionAll($terima1);

        // $terima3 = DB::table('trhkasin_ppkd as a')
        //     ->join('trdkasin_ppkd as b', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->selectRaw("a.tgl_kas,SUM ( rupiah ) AS nilai,1 jenis")
        //     ->whereRaw("LEFT ( b.kd_rek6, 1 ) IN ( ?,? ) AND pot_khusus <> ? AND a.tgl_kas BETWEEN ? AND ?", ['5', '1', '3', $periode1, $periode2])
        //     ->groupByRaw("a.tgl_kas")
        //     ->unionAll($terima2);

        // $terima4 = DB::table('trkasout_ppkd as w')
        //     ->selectRaw("w.tanggal as tgl_kas, isnull( SUM ( w.nilai ), 0 ) AS nilai, 1 jenis")
        //     ->whereRaw("w.tanggal BETWEEN ? AND ?", [$periode1, $periode2])
        //     ->groupByRaw("w.tanggal")
        //     ->unionAll($terima3);

        // $terima5 = DB::table('penerimaan_non_sp2d as w')
        //     ->selectRaw("w.tanggal as tgl_kas, isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
        //     ->whereRaw("w.tanggal BETWEEN ? AND ? AND w.jenis=?", [$periode1, $periode2, '1'])
        //     ->groupByRaw("w.tanggal")
        //     ->unionAll($terima4);

        // $terima6 = DB::table('penerimaan_non_sp2d as w')
        //     ->selectRaw("w.tanggal as tgl_kas, isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
        //     ->whereRaw("w.tanggal BETWEEN ? AND ? AND w.jenis=?", [$periode1, $periode2, '2'])
        //     ->groupByRaw("w.tanggal")
        //     ->unionAll($terima5);

        // $terima = DB::table(DB::raw("({$terima6->toSql()}) AS a"))
        //     ->selectRaw("tgl_kas, SUM(nilai) nilai")
        //     ->mergeBindings($terima6)
        //     ->groupBy('tgl_kas');

        // $keluar1 = DB::table('trhsp2d as a')
        //     ->join('trhspm as b', function ($join) {
        //         $join->on('a.no_spm', '=', 'b.no_spm');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->join('trhspp as c', function ($join) {
        //         $join->on('b.no_spp', '=', 'c.no_spp');
        //         $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        //     })
        //     ->join('trdspp as d', function ($join) {
        //         $join->on('c.no_spp', '=', 'd.no_spp');
        //         $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        //     })
        //     ->selectRaw("a.tgl_kas_bud tgl_kas,isnull( SUM ( d.nilai ), 0 ) AS nilai, 2 jenis")
        //     ->whereRaw("a.status_bud =? AND a.jns_spp = ? AND ( c.sp2d_batal= 0 OR c.sp2d_batal IS NULL ) AND a.tgl_kas_bud BETWEEN ? AND ?", ['1', '4', $periode1, $periode2])
        //     ->groupByRaw("a.tgl_kas_bud");

        // $keluar2 = DB::table('trhsp2d as a')
        //     ->join('trhspm as b', function ($join) {
        //         $join->on('a.no_spm', '=', 'b.no_spm');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->join('trhspp as c', function ($join) {
        //         $join->on('b.no_spp', '=', 'c.no_spp');
        //         $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        //     })
        //     ->join('trdspp as d', function ($join) {
        //         $join->on('c.no_spp', '=', 'd.no_spp');
        //         $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        //     })
        //     ->selectRaw("a.tgl_kas_bud tgl_kas, isnull( SUM ( d.nilai ), 0 ) AS nilai, 2 jenis")
        //     ->whereRaw("a.status_bud =? AND a.jns_spp != ? AND ( c.sp2d_batal= 0 OR c.sp2d_batal IS NULL ) AND a.tgl_kas_bud BETWEEN ? AND ?", ['1', '4', $periode1, $periode2])
        //     ->groupByRaw("a.tgl_kas_bud")->unionAll($keluar1);

        // $keluar3 = DB::table('pengeluaran_non_sp2d as x')
        //     ->selectRaw("x.tanggal tgl_kas, isnull( SUM ( x.nilai ), 0 ) AS nilai,2 jenis")
        //     ->whereRaw("x.tanggal BETWEEN ? AND ?", [$periode1, $periode2])
        //     ->groupByRaw("x.tanggal")
        //     ->unionAll($keluar2);

        // $keluar4 = DB::table('trkoreksi_pengeluaran as w')
        //     ->selectRaw("w.tanggal tgl_kas, isnull( SUM ( w.nilai ), 0 ) AS nilai,2 jenis")
        //     ->whereRaw("w.tanggal BETWEEN ? AND ?", [$periode1, $periode2])
        //     ->groupByRaw("w.tanggal")
        //     ->unionAll($keluar3);

        // $keluar5 = DB::table('trdrestitusi as b')
        //     ->join('trhrestitusi as a', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.no_sts', '=', 'b.no_sts');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->selectRaw("a.tgl_kas, isnull( SUM ( b.rupiah ), 0 ) AS nilai,2 jenis")
        //     ->whereRaw("a.jns_trans= ? AND a.tgl_kas BETWEEN ? AND ?", ['3', $periode1, $periode2])
        //     ->groupByRaw("a.tgl_kas")->unionAll($keluar4);

        // $keluar = DB::table(DB::raw("({$keluar5->toSql()}) AS b"))
        //     ->selectRaw("tgl_kas, SUM(nilai) nilai")
        //     ->mergeBindings($keluar5)
        //     ->groupBy('tgl_kas');

        // $nilai = DB::table($terima, 'terima')
        //     ->selectRaw("terima.tgl_kas, terima.nilai terima, keluar.nilai keluar")
        //     ->leftJoinSub($keluar, 'keluar', function ($join) {
        //         $join->on('terima.tgl_kas', '=', 'keluar.tgl_kas');
        //     })
        //     ->orderBy('terima.tgl_kas')
        //     ->get();


        $nilai = DB::select("SELECT terima.tgl_kas, terima.nilai terima, keluar.nilai keluar FROM (
				-- mulai terima
                SELECT tgl_kas, SUM(nilai) nilai FROM (
				SELECT a.tgl_kas, SUM ( rupiah ) AS nilai,1 jenis
				FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON  a.no_sts=b.no_sts AND a.no_kas= b.no_kas AND a.kd_skpd= b.kd_skpd
									 LEFT JOIN ms_rek3 c ON LEFT ( b.kd_rek6, 3 ) = c.kd_rek3
				WHERE LEFT( b.kd_rek6, 1 ) IN ( '4' ) AND a.tgl_kas BETWEEN ? AND ? and  b.kd_rek6 not in ('420101040001','420101040002','420101040003','410416010001','410409010001')
				group by a.tgl_kas
				UNION ALL

                SELECT a.tgl_kas ,SUM(rupiah) as nilai, 1 jenis
                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON  a.no_kas=b.no_kas and a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                LEFT JOIN ms_rek3 c ON LEFT(b.kd_rek6,4)=c.kd_rek3
                WHERE b.kd_rek6 in ('410409010001') AND a.tgl_kas BETWEEN ? AND ? and b.sumber<>'y'
                GROUP BY a.tgl_kas

                UNION ALL

				SELECT a.tgl_kas,SUM ( rupiah ) AS nilai,1 jenis
				FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON  a.no_sts=b.no_sts AND a.no_kas= b.no_kas AND a.kd_skpd= b.kd_skpd
				WHERE LEFT (b.kd_rek6,1) IN ('5','1') AND pot_khusus=3 AND a.tgl_kas BETWEEN ? AND ?
				GROUP BY a.tgl_kas
				UNION ALL

				SELECT a.tgl_kas,SUM ( rupiah ) AS nilai,1 jenis
				FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON  a.no_sts=b.no_sts AND a.no_kas= b.no_kas AND a.kd_skpd= b.kd_skpd
				WHERE LEFT ( b.kd_rek6, 1 ) IN ( '5', '1','2' ) AND pot_khusus <> 3 AND a.tgl_kas BETWEEN ? AND ?
				GROUP BY a.tgl_kas
				UNION ALL

				SELECT w.tanggal, isnull( SUM ( w.nilai ), 0 ) AS nilai, 1 jenis
				FROM trkasout_ppkd w
				WHERE w.tanggal BETWEEN ? AND ?
				GROUP BY w.tanggal
				UNION ALL

				SELECT w.tanggal, isnull(SUM(w.nilai), 0) AS nilai, 1 jenis
				FROM penerimaan_non_sp2d w
				WHERE w.tanggal between ? AND ? AND w.jenis='1'
				GROUP BY w.tanggal
				UNION ALL

				SELECT w.tanggal, isnull(SUM(w.nilai), 0) AS nilai, 1 jenis
				FROM penerimaan_non_sp2d w
				WHERE w.tanggal between ? AND ? AND w.jenis='2'
				GROUP BY w.tanggal
                union all

                SELECT a.tgl_kas
                ,SUM(rupiah) as nilai, 1 jenis
                FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas and a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                WHERE b.kd_rek6 in ('410409010001') AND a.tgl_kas between ? AND ? and
                -- b.sumber='y'
                a.keterangan like '%(UYHD)%'
                GROUP BY a.tgl_kas
                UNION ALL
                SELECT

                    w.tanggal as tgl_kas,
                    isnull(SUM(w.nilai),0) as nilai,
                    1 jenis
                FROM
                    tkoreksi_penerimaan w
                WHERE
                    w.tanggal between ? AND ?
                group by w.tanggal
                ) x
				GROUP BY tgl_kas
                -- end terima
                ) terima
				LEFT JOIN
				(SELECT tgl_kas, SUM(nilai) nilai FROM (

				SELECT a.tgl_kas_bud tgl_kas,isnull( SUM ( d.nilai ), 0 ) AS nilai, 2 jenis
					 FROM trhsp2d a INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
									INNER JOIN trhspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
									INNER JOIN trdspp d ON c.no_spp = d.no_spp AND c.kd_skpd = d.kd_skpd
					 WHERE a.status_bud = '1' AND a.jns_spp = '4'
					 AND ( c.sp2d_batal= 0 OR c.sp2d_batal IS NULL ) AND a.tgl_kas_bud BETWEEN ? AND ?
				GROUP BY a.tgl_kas_bud
				UNION ALL

				SELECT a.tgl_kas_bud tgl_kas, isnull( SUM ( d.nilai ), 0 ) AS nilai, 2 jenis
					 FROM trhsp2d a INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
									INNER JOIN trhspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
									INNER JOIN trdspp d ON c.no_spp = d.no_spp AND c.kd_skpd = d.kd_skpd
					 WHERE a.status_bud = '1' AND a.jns_spp != '4' AND ( c.sp2d_batal= 0 OR c.sp2d_batal IS NULL )
					 AND a.tgl_kas_bud BETWEEN ? AND ?
				GROUP BY a.tgl_kas_bud
				UNION ALL

				SELECT x.tanggal tgl_kas, isnull( SUM ( x.nilai ), 0 ) AS nilai,2 jenis
				FROM pengeluaran_non_sp2d x
				WHERE x.tanggal BETWEEN ? AND ?
				GROUP BY x.tanggal
				UNION ALL

				SELECT w.tanggal tgl_kas, isnull( SUM ( w.nilai ), 0 ) AS nilai,2 jenis
				FROM trkoreksi_pengeluaran w
				WHERE w.tanggal BETWEEN ? AND ?
				GROUP BY w.tanggal
				UNION ALL

				SELECT a.tgl_kas, isnull( SUM ( b.rupiah ), 0 ) AS nilai,2 jenis
				FROM trdrestitusi b INNER JOIN trhrestitusi a ON a.kd_skpd= b.kd_skpd AND a.no_kas= b.no_kas AND a.no_sts= b.no_sts
				WHERE a.jns_trans= 3 AND a.tgl_kas BETWEEN ? AND ?
				GROUP BY a.tgl_kas) x
				GROUP BY tgl_kas) keluar on terima.tgl_kas=keluar.tgl_kas
				ORDER BY terima.tgl_kas", [$periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2]);

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_bku' => $nilai,
            'tanda_tangan' => $tanda_tangan
        ];

        $view = view('bud.laporan_bendahara.cetak.bku_dengan_tanggal')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } else {
            return $view;
        }
    }

    public function bkuTanpaBlud(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $halaman = $request->halaman;
        $no_urut = $request->no_urut;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        if ($ttd) {
            $tanda_tangan = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd, 'kode' => 'BUD'])->first();
        } else {
            $tanda_tangan = null;
        }

        $terima1 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->leftJoin('ms_rek3 as c', function ($join) {
                $join->on(DB::raw("left(b.kd_rek6,3)"), '=', 'c.kd_rek3');
            })
            ->selectRaw("a.tgl_kas, SUM ( rupiah ) AS nilai,1 jenis")
            ->whereRaw("LEFT( b.kd_rek6, 1 ) IN ( ? ) AND a.tgl_kas BETWEEN ? AND ? and b.kd_rek6 not in (?,?)", ['4', $periode1, $periode2, '420101040001', '410416010001'])
            ->groupByRaw("a.tgl_kas");

        $terima2 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("a.tgl_kas,SUM ( rupiah ) AS nilai,1 jenis")
            ->whereRaw("LEFT (b.kd_rek6,1) IN (?,?) AND pot_khusus=? AND a.tgl_kas BETWEEN ? AND ?", ['5', '1', '3', $periode1, $periode2])
            ->groupByRaw("a.tgl_kas")
            ->unionAll($terima1);

        $terima3 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("a.tgl_kas,SUM ( rupiah ) AS nilai,1 jenis")
            ->whereRaw("LEFT ( b.kd_rek6, 1 ) IN ( ?,? ) AND pot_khusus <> ? AND a.tgl_kas BETWEEN ? AND ?", ['5', '1', '3', $periode1, $periode2])
            ->groupByRaw("a.tgl_kas")
            ->unionAll($terima2);

        $terima4 = DB::table('trkasout_ppkd as w')
            ->selectRaw("w.tanggal as tgl_kas, isnull( SUM ( w.nilai ), 0 ) AS nilai, 1 jenis")
            ->whereRaw("w.tanggal BETWEEN ? AND ?", [$periode1, $periode2])
            ->groupByRaw("w.tanggal")
            ->unionAll($terima3);

        $terima5 = DB::table('penerimaan_non_sp2d as w')
            ->selectRaw("w.tanggal as tgl_kas, isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
            ->whereRaw("w.tanggal BETWEEN ? AND ? AND w.jenis=?", [$periode1, $periode2, '1'])
            ->groupByRaw("w.tanggal")
            ->unionAll($terima4);

        $terima6 = DB::table('penerimaan_non_sp2d as w')
            ->selectRaw("w.tanggal as tgl_kas, isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
            ->whereRaw("w.tanggal BETWEEN ? AND ? AND w.jenis=?", [$periode1, $periode2, '2'])
            ->groupByRaw("w.tanggal")
            ->unionAll($terima5);

        $terima = DB::table(DB::raw("({$terima6->toSql()}) AS a"))
            ->selectRaw("tgl_kas, SUM(nilai) nilai")
            ->mergeBindings($terima6)
            ->groupBy('tgl_kas');

        $keluar1 = DB::table('trhsp2d as a')
            ->join('trhspm as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->join('trdspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })
            ->selectRaw("a.tgl_kas_bud tgl_kas,isnull( SUM ( d.nilai ), 0 ) AS nilai, 2 jenis")
            ->whereRaw("a.status_bud =? AND a.jns_spp = ? AND ( c.sp2d_batal= 0 OR c.sp2d_batal IS NULL ) AND a.tgl_kas_bud BETWEEN ? AND ?", ['1', '4', $periode1, $periode2])
            ->groupByRaw("a.tgl_kas_bud");

        $keluar2 = DB::table('trhsp2d as a')
            ->join('trhspm as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->join('trdspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })
            ->selectRaw("a.tgl_kas_bud tgl_kas, isnull( SUM ( d.nilai ), 0 ) AS nilai, 2 jenis")
            ->whereRaw("a.status_bud =? AND a.jns_spp != ? AND ( c.sp2d_batal= 0 OR c.sp2d_batal IS NULL ) AND a.tgl_kas_bud BETWEEN ? AND ?", ['1', '4', $periode1, $periode2])
            ->groupByRaw("a.tgl_kas_bud")->unionAll($keluar1);

        $keluar3 = DB::table('pengeluaran_non_sp2d as x')
            ->selectRaw("x.tanggal tgl_kas, isnull( SUM ( x.nilai ), 0 ) AS nilai,2 jenis")
            ->whereRaw("x.tanggal BETWEEN ? AND ?", [$periode1, $periode2])
            ->groupByRaw("x.tanggal")
            ->unionAll($keluar2);

        $keluar4 = DB::table('trkoreksi_pengeluaran as w')
            ->selectRaw("w.tanggal tgl_kas, isnull( SUM ( w.nilai ), 0 ) AS nilai,2 jenis")
            ->whereRaw("w.tanggal BETWEEN ? AND ?", [$periode1, $periode2])
            ->groupByRaw("w.tanggal")
            ->unionAll($keluar3);

        $keluar5 = DB::table('trdrestitusi as b')
            ->join('trhrestitusi as a', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.no_sts', '=', 'b.no_sts');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("a.tgl_kas, isnull( SUM ( b.rupiah ), 0 ) AS nilai,2 jenis")
            ->whereRaw("a.jns_trans= ? AND a.tgl_kas BETWEEN ? AND ?", ['3', $periode1, $periode2])
            ->groupByRaw("a.tgl_kas")->unionAll($keluar4);

        $keluar = DB::table(DB::raw("({$keluar5->toSql()}) AS b"))
            ->selectRaw("tgl_kas, SUM(nilai) nilai")
            ->mergeBindings($keluar5)
            ->groupBy('tgl_kas');

        $nilai = DB::table($terima, 'terima')
            ->selectRaw("terima.tgl_kas, terima.nilai terima, keluar.nilai keluar")
            ->leftJoinSub($keluar, 'keluar', function ($join) {
                $join->on('terima.tgl_kas', '=', 'keluar.tgl_kas');
            })
            ->orderBy('terima.tgl_kas')
            ->get();

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_bku' => $nilai,
            'tanda_tangan' => $tanda_tangan
        ];

        $view = view('bud.laporan_bendahara.cetak.bku_tanpa_blud')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } else {
            return $view;
        }
    }

    public function bkuRincian(Request $request)
    {
        $pilihan = $request->pilihan;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $no_urut = $request->no_urut;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;
        $tahun = tahun_anggaran();

        if ($ttd) {
            $tanda_tangan = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd, 'kode' => 'BUD'])->first();
        } else {
            $tanda_tangan = null;
        }

        if ($tgl == $tahun . '-01-01') {
            // $saldo = DB::table('buku_kas')->selectRaw("'4' kd_rek, 'SALDO AWAL' nama, nilai , 1 jenis");
            $saldo = "select '4' kd_rek, 'SALDO AWAL' nama, nilai , 1 jenis
			from buku_kas
			UNION ALL";
        } else {
            $saldo = "";
        }

        $cek_pengeluaran = DB::table('pengeluaran_non_sp2d')
            ->where(['tanggal' => $tgl])
            ->count();

        if ($cek_pengeluaran > 0) {
            // $keluar_non_sp2d = DB::table('pengeluaran_non_sp2d as x')
            //     ->selectRaw("CAST(nomor as VARCHAR) as no_kas,nomor as urut, '' as uraian,keterangan+'. Rp. ','' kode, 'PENGELUARAN NON SP2D' nm_rek6,0 as terima,isnull(SUM(x.nilai), 0) AS keluar, 2 jenis, isnull(SUM(x.nilai), 0) as netto, ''sp")
            //     ->where(['tanggal' => $tgl])
            //     ->groupBy('nomor', 'keterangan');
            $keluarnonsp2d = "UNION ALL
								SELECT
								CAST(nomor as VARCHAR) as nokas,nomor as urut, keterangan+'. Rp. ','' kode, 'PENGELUARAN NON SP2D' nm_rek6,0 as terima,
								isnull(SUM(x.nilai), 0) AS keluar, 2 jenis, isnull(SUM(x.nilai), 0) as netto, ''sp
								FROM
								pengeluaran_non_sp2d x
								WHERE
								tanggal='$tgl'
								group by nomor,keterangan";
        } else {
            $keluarnonsp2d = "";
        }

        $cek_penerimaan1 = DB::table('penerimaan_non_sp2d')
            ->where(['tanggal' => $tgl, 'jenis' => '1'])
            ->count();

        if ($cek_penerimaan1 != 0) {
            // $masuk_non_sp2d1 = DB::table('penerimaan_non_sp2d as w')
            //     ->selectRaw("CAST(nomor as VARCHAR),nomor as urut,keterangan as uraian,''kode,'Deposito'nm_rek6,isnull(SUM(w.nilai), 0) AS terima,0 as keluar,1 jenis, 0 netto, ''sp")
            //     ->where(['tanggal' => $tgl, 'w.jenis' => '1'])
            //     ->groupBy('nomor', 'keterangan');
            $masuknonsp2d = "UNION ALL
							SELECT
								CAST(nomor as VARCHAR),nomor as urut,keterangan,''kode,'Deposito'nama,
								isnull(SUM(w.nilai), 0) AS terima,0 as keluar,
								1 jenis, 0 netto, ''sp
							FROM
								penerimaan_non_sp2d w
							WHERE
								tanggal='$tgl'
							AND w.jenis='1'
							group by nomor,keterangan";
        } else {
            $masuknonsp2d = "";
        }

        $cek_penerimaan2 = DB::table('penerimaan_non_sp2d')
            ->where(['tanggal' => $tgl, 'jenis' => '2'])
            ->count();

        if ($cek_penerimaan2 > 0) {
            // $masuk_non_sp2d2 = DB::table('penerimaan_non_sp2d as w')
            //     ->selectRaw("CAST(nomor as VARCHAR) as nokas,nomor as urut,keterangan as uraian,'-'kode,'Penerimaan NON SP2D'nm_rek6,isnull(SUM(w.nilai), 0) AS terima,0 as keluar,1 jenis, 0 netto, ''sp")
            //     ->where(['tanggal' => $tgl, 'w.jenis' => '2'])
            //     ->groupBy('nomor', 'keterangan');
            $masuknonsp2d2 = "UNION ALL
							SELECT
								CAST(nomor as VARCHAR) as nokas,nomor as urut,keterangan,'-'kode,'Penerimaan NON SP2D'nama,
								isnull(SUM(w.nilai), 0) AS terima,0 as keluar,
								1 jenis, 0 netto, ''sp
							FROM
								penerimaan_non_sp2d w
							WHERE
								tanggal='$tgl'
							AND w.jenis='2'
							group by nomor,keterangan";
        } else {
            $masuknonsp2d2 = "";
        }

        $bku = DB::select("SELECT * from(

			$saldo


			SELECT a.no_kas,a.no_kas as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6
				,0 as terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp
				FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_sts=b.no_sts AND a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
				WHERE LEFT(b.kd_rek6,1) IN ('4') AND a.tgl_kas = '$tgl' and  b.kd_rek6 not in
				-- ('420101040001','410416010001')
				('420101040001','420101040002','420101040003','410416010001') -- 410409010001 REK INI DIHAPUS DALAM NOT IN
				GROUP BY a.no_kas,keterangan

			UNION ALL
			SELECT '',a.no_kas as urut,keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, c.nm_rek6 as nm_rek6
				,SUM(rupiah) as terima,0 as keluar, 1 jenis, 0 netto, ''as sp
				FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_sts=b.no_sts AND a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
				LEFT JOIN ms_rek6 c ON b.kd_rek6=c.kd_rek6
				WHERE LEFT(b.kd_rek6,1) IN ('4') AND a.tgl_kas = '$tgl' and  b.kd_rek6 not in
				-- ('420101040001','410416010001')
				('420101040001','420101040002','420101040003','410416010001') -- 410409010001 REK INI DIHAPUS DALAM NOT IN
				GROUP BY a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6,c.nm_rek6


				UNION ALL

				-- LAIN-LAIN PENDAPATAN ASLI DAERAH YANG SAH
				SELECT  a.no_kas,a.no_kas as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, ''as nm_rek6
				,0 as terima,0 as keluar, 1 jenis,SUM(rupiah) netto, '' as sp
				FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_sts=b.no_sts AND a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
				WHERE LEFT(b.kd_rek6,1) IN ('5','1') and pot_khusus=3 AND a.tgl_kas = '$tgl'
				GROUP BY a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6
				UNION ALL
				SELECT  '' as nokas,a.no_kas as urut,a.keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, 'Lain-lain PAD yang sah'as nm_rek6
				,SUM(rupiah) as terima,0 as keluar, 1 jenis,0 netto, '' as sp
				FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON  a.no_sts=b.no_sts AND a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
				WHERE LEFT(b.kd_rek6,1) IN ('5','1') and pot_khusus=3 AND a.tgl_kas = '$tgl'
				GROUP BY a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6
				UNION ALL

				-- CONTRA POST
				SELECT  a.no_kas,a.no_kas as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, '' as nama
								,0 as terima,0 as keluar, 1 jenis, SUM(rupiah) netto, '' as sp
								FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON  a.no_sts=b.no_sts AND a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
								WHERE LEFT(b.kd_rek6,1) IN ('5','1','2') and pot_khusus<>3 AND a.tgl_kas = '$tgl'
								group by a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6
				UNION ALL
				SELECT  '' as nokas,a.no_kas as urut,a.keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, 'CONTRA POST' as nama
								,SUM(rupiah) as terima,0 as keluar, 1 jenis, 0 netto, '' as sp
								FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON  a.no_sts=b.no_sts AND a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
								WHERE LEFT(b.kd_rek6,1) IN ('5','1','2') and pot_khusus<>3 AND a.tgl_kas = '$tgl'
								group by a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6
				UNION ALL

				SELECT no_kas_bud AS nokas,a.no_kas_bud as urut,'No.SP2D :'+' '+a.no_sp2d+'<br> '+a.keperluan+'Netto Rp. ' AS ket,
				'' AS kode,'' AS nmrek,0 AS terima,0 AS keluar,2 AS jenis,
				(SUM(b.nilai))-(SELECT ISNULL(SUM(nilai),0) FROM trspmpot WHERE no_spm=a.no_spm) AS netto,
				'' as sp
											FROM trhsp2d a
											INNER JOIN trdspp b ON a.no_spp=b.no_spp
											WHERE a.status_bud = '1' AND a.tgl_kas_bud = '$tgl'
											AND (a.sp2d_batal=0 OR a.sp2d_batal is NULL)
											GROUP BY a.no_sp2d,no_kas_bud,a.keperluan,a.no_spm
				UNION ALL
				SELECT '' AS nokas,a.no_kas_bud AS urut,'' AS ket,(
											b.kd_sub_kegiatan+'.'+b.kd_rek6) AS kode,b.nm_rek6 AS nmrek,0 AS terima,b.nilai AS keluar,2 AS jenis,0 as netto,''as sp
											FROM trdspp b INNER JOIN trhsp2d a ON a.no_spp=b.no_spp WHERE a.status_bud = '1' AND a.tgl_kas_bud = '$tgl'
											AND (a.sp2d_batal=0 OR a.sp2d_batal is NULL)


				$keluarnonsp2d

				UNION ALL
				SELECT
				a.no_kas as nokas,a.no_kas as urut,'RESTITUSI<br>'+keterangan+'. Rp. ','' as kode, '' as nm_rek6,
				0 AS terima,0 keluar, 2 jenis,isnull(SUM(b.rupiah), 0) as netto,''sp
				FROM
				trdrestitusi b inner join trhrestitusi a on a.kd_skpd=b.kd_skpd and a.no_kas=b.no_kas and a.no_sts=b.no_sts
				WHERE a.jns_trans=3 and a.tgl_kas='$tgl'
				group by a.no_kas,keterangan

				UNION ALL
				SELECT
				'' as nokas,a.no_kas as urut,''as ket,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, c.nm_rek6,0 terima,
				isnull(SUM(b.rupiah), 0) AS keluar, 2 jenis,0 netto, ''sp
				FROM
				trdrestitusi b inner join trhrestitusi a on a.kd_skpd=b.kd_skpd and a.no_kas=b.no_kas and a.no_sts=b.no_sts
				left join ms_rek6 c on b.kd_rek6=c.kd_rek6
				WHERE a.jns_trans=3
				and a.tgl_kas='$tgl'
				group by a.no_kas,b.kd_sub_kegiatan,b.kd_rek6,c.nm_rek6

				UNION ALL

				SELECT no, no,'KOREKSI PENERIMAAN<br>'+keterangan as ket,kd_sub_kegiatan+'.'+kd_rek kode,nm_rek,
					isnull(SUM(w.nilai),0) as terima,0 as keluar,
					1 jenis,isnull(SUM(w.nilai),0) as netto,''sp
				FROM
					trkasout_ppkd w
				WHERE
					tanggal='$tgl'
					group by no,keterangan,kd_sub_kegiatan,kd_rek,nm_rek

				UNION ALL
				SELECT no, no,'KOREKSI PENGELUARAN<br>'+keterangan as ket,kd_sub_kegiatan+'.'+kd_rek kode,nm_rek,
					0 as terima,isnull(SUM(w.nilai),0) as keluar,
					2 jenis,isnull(SUM(w.nilai),0) as netto,''sp
				FROM
					trkoreksi_pengeluaran w
				WHERE
					tanggal='$tgl'
					group by no,keterangan,kd_sub_kegiatan,kd_rek,nm_rek

				$masuknonsp2d

				$masuknonsp2d2
				) a
					 order by urut,kode,jenis");

        // $bku1 = DB::table('trhkasin_ppkd as a')
        //     ->join('trdkasin_ppkd as b', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->selectRaw("a.no_kas,a.no_kas as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6,0 as terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp")
        //     ->whereRaw("LEFT(b.kd_rek6,1) IN (?) and  b.kd_rek6 not in (?,?) and a.tgl_kas=?", ['4', '420101040001', '410416010001', $tgl])
        //     ->groupByRaw("a.no_kas,keterangan");

        // $bku2 = DB::table('trhkasin_ppkd as a')
        //     ->join('trdkasin_ppkd as b', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->leftJoin('ms_rek6 as c', function ($join) {
        //         $join->on('b.kd_rek6', '=', 'c.kd_rek6');
        //     })
        //     ->selectRaw("'' as no_kas,a.no_kas as urut,keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, c.nm_rek6 as nm_rek6,SUM(rupiah) as terima,0 as keluar, 1 jenis, 0 netto, ''as sp")
        //     ->whereRaw("LEFT(b.kd_rek6,1) IN (?) and  b.kd_rek6 not in (?,?) and a.tgl_kas=?", ['4', '420101040001', '410416010001', $tgl])
        //     ->groupByRaw("a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6,c.nm_rek6")
        //     ->unionAll($bku1);

        // $bku3 = DB::table('trhkasin_ppkd as a')
        //     ->join('trdkasin_ppkd as b', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->selectRaw("a.no_kas,a.no_kas as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, ''as nm_rek6,0 as terima,0 as keluar, 1 jenis,SUM(rupiah) netto, '' as sp")
        //     ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?) and pot_khusus=? and a.tgl_kas=?", ['5', '1', '3', $tgl])
        //     ->groupByRaw("a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6")
        //     ->unionAll($bku2);

        // $bku4 = DB::table('trhkasin_ppkd as a')
        //     ->join('trdkasin_ppkd as b', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->selectRaw("'' as no_kas,a.no_kas as urut,a.keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, 'Lain-lain PAD yang sah'as nm_rek6,SUM(rupiah) as terima,0 as keluar, 1 jenis,0 netto, '' as sp")
        //     ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?) and pot_khusus=? and a.tgl_kas=?", ['5', '1', '3', $tgl])
        //     ->groupByRaw("a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6")
        //     ->unionAll($bku3);

        // $bku5 = DB::table('trhkasin_ppkd as a')
        //     ->join('trdkasin_ppkd as b', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->selectRaw("a.no_kas,a.no_kas as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6,0 as terima,0 as keluar, 1 jenis, SUM(rupiah) netto, '' as sp")
        //     ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?) and pot_khusus<>? and a.tgl_kas=?", ['5', '1', '3', $tgl])
        //     ->groupByRaw("a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6")
        //     ->unionAll($bku4);

        // $bku6 = DB::table('trhkasin_ppkd as a')
        //     ->join('trdkasin_ppkd as b', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->selectRaw("'' as no_kas,a.no_kas as urut,a.keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, 'CONTRA POST' as nm_rek6,SUM(rupiah) as terima,0 as keluar, 1 jenis, 0 netto, '' as sp")
        //     ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?) and pot_khusus<>? and a.tgl_kas=?", ['5', '1', '3', $tgl])
        //     ->groupByRaw("a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6")
        //     ->unionAll($bku5);

        // $bku7 = DB::table('trhsp2d as a')
        //     ->join('trdspp as b', function ($join) {
        //         $join->on('a.no_spp', '=', 'b.no_spp');
        //     })
        //     ->selectRaw("no_kas_bud AS no_kas,a.no_kas_bud as urut,'No.SP2D :'+' '+a.no_sp2d+'<br> '+a.keperluan+'Netto Rp. ' AS uraian,'' AS kode,'' AS nm_rek6,0 AS terima,0 AS keluar,2 AS jenis,(SUM(b.nilai))-(SELECT ISNULL(SUM(nilai),0) FROM trspmpot WHERE no_spm=a.no_spm) AS netto,'' as sp")
        //     ->whereRaw("a.status_bud = ? AND (a.sp2d_batal=0 OR a.sp2d_batal is NULL) AND a.tgl_kas_bud=?", ['1', $tgl])
        //     ->groupByRaw("a.no_sp2d,no_kas_bud,a.keperluan,a.no_spm")
        //     ->unionAll($bku6);

        // $bku8 = DB::table('trdspp as b')
        //     ->join('trhsp2d as a', function ($join) {
        //         $join->on('a.no_spp', '=', 'b.no_spp');
        //     })
        //     ->selectRaw("'' AS no_kas,a.no_kas_bud AS urut,'' AS uraian,(b.kd_sub_kegiatan+'.'+b.kd_rek6) AS kode,b.nm_rek6 AS nm_rek6,0 AS terima,b.nilai AS keluar,2 AS jenis,0 as netto,''as sp")
        //     ->whereRaw("a.status_bud = ? AND (a.sp2d_batal=0 OR a.sp2d_batal is NULL) AND a.tgl_kas_bud=?", ['1', $tgl])
        //     ->unionAll($bku7);

        // $bku9 = DB::table('trdrestitusi as b')
        //     ->join('trhrestitusi as a', function ($join) {
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.no_sts', '=', 'b.no_sts');
        //     })
        //     ->selectRaw("a.no_kas as no_kas,a.no_kas as urut,'RESTITUSI<br>'+keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6,0 AS terima,0 keluar, 2 jenis,isnull(SUM(b.rupiah), 0) as netto,''sp")
        //     ->whereRaw("a.jns_trans=? and a.tgl_kas=?", ['3', $tgl])
        //     ->groupByRaw("a.no_kas,keterangan")
        //     ->unionAll($bku8);

        // $bku10 = DB::table('trdrestitusi as b')
        //     ->join('trhrestitusi as a', function ($join) {
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.no_sts', '=', 'b.no_sts');
        //     })
        //     ->leftJoin('ms_rek6 as c', 'b.kd_rek6', '=', 'c.kd_rek6')
        //     ->selectRaw("'' as no_kas,a.no_kas as urut,''as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, c.nm_rek6,0 terima,isnull(SUM(b.rupiah), 0) AS keluar, 2 jenis,0 netto, ''sp")
        //     ->whereRaw("a.jns_trans=? and a.tgl_kas=?", ['3', $tgl])
        //     ->groupByRaw("a.no_kas,b.kd_sub_kegiatan,b.kd_rek6,c.nm_rek6")
        //     ->unionAll($bku9);

        // $bku11 = DB::table('trkasout_ppkd as w')
        //     ->selectRaw("no as no_kas, no as urut,'KOREKSI PENERIMAAN<br>'+keterangan as uraian,kd_sub_kegiatan+'.'+kd_rek kode,nm_rek as nm_rek6,isnull(SUM(w.nilai),0) as terima,0 as keluar,1 jenis,isnull(SUM(w.nilai),0) as netto,''sp")
        //     ->whereRaw("tanggal=?", [$tgl])
        //     ->groupByRaw("no,keterangan,kd_sub_kegiatan,kd_rek,nm_rek")
        //     ->unionAll($bku10);

        // $bku12 = DB::table('trkoreksi_pengeluaran as w')
        //     ->selectRaw("no as no_kas, no as urut,'KOREKSI PENGELUARAN<br>'+keterangan as uraian,kd_sub_kegiatan+'.'+kd_rek kode,nm_rek as nm_rek6,0 as terima,isnull(SUM(w.nilai),0) as keluar,2 jenis,isnull(SUM(w.nilai),0) as netto,''sp")
        //     ->whereRaw("tanggal=?", [$tgl])
        //     ->groupByRaw("no,keterangan,kd_sub_kegiatan,kd_rek,nm_rek")
        //     ->unionAll($bku11);

        // if (isset($saldo)) {
        //     $bku13 = $bku12->unionAll($saldo);
        // } else {
        //     $bku13 = $bku12;
        // }

        // if (isset($keluar_non_sp2d)) {
        //     $bku14 = $bku13->unionAll($keluar_non_sp2d);
        // } else {
        //     $bku14 = $bku13;
        // }

        // if (isset($masuk_non_sp2d1)) {
        //     $bku15 = $bku14->unionAll($masuk_non_sp2d1);
        // } else {
        //     $bku15 = $bku14;
        // }

        // if (isset($masuk_non_sp2d2)) {
        //     $bku16 = $bku15->unionAll($masuk_non_sp2d2);
        // } else {
        //     $bku16 = $bku15;
        // }

        // $bku = DB::table(DB::raw("({$bku16->toSql()}) AS sub"))
        //     ->mergeBindings($bku16)
        //     ->orderBy('urut')
        //     ->orderBy('kode')
        //     ->orderBy('jenis')
        //     ->get();

        // $total_bku1 = DB::table('trhkasin_ppkd as a')
        //     ->join('trdkasin_ppkd as b', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->leftJoin('ms_rek3 as c', function ($join) {
        //         $join->on(DB::raw("left(b.kd_rek6,4)"), '=', 'c.kd_rek3');
        //     })
        //     ->selectRaw("a.tgl_kas,LEFT(b.kd_rek6,4) as kd_rek, UPPER(c.nm_rek3) as nama,SUM(rupiah) as nilai, 1 jenis")
        //     ->whereRaw("LEFT(b.kd_rek6,1) IN (?) and b.kd_rek6 not in (?,?) and a.tgl_kas<?", ['4', '420101040001', '410416010001', $tgl])
        //     ->groupByRaw("a.tgl_kas,LEFT(b.kd_rek6,4),c.nm_rek3");

        // $total_bku2 = DB::table('trhkasin_ppkd as a')
        //     ->join('trdkasin_ppkd as b', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->selectRaw("a.tgl_kas,LEFT(b.kd_rek6,1) as kd_rek, 'CONTRA POST' as nama,SUM(rupiah) as nilai, 1 jenis")
        //     ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?) and a.tgl_kas<?", ['5', '1', $tgl])
        //     ->groupByRaw("a.tgl_kas,LEFT(b.kd_rek6,1)")
        //     ->unionAll($total_bku1);

        // $total_bku3 = DB::table('trhsp2d as a')
        //     ->join('trhspm as b', function ($join) {
        //         $join->on('a.no_spm', '=', 'b.no_spm');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->join('trhspp as c', function ($join) {
        //         $join->on('b.no_spp', '=', 'c.no_spp');
        //         $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        //     })
        //     ->join('trdspp as d', function ($join) {
        //         $join->on('c.no_spp', '=', 'd.no_spp');
        //         $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        //     })
        //     ->selectRaw("a.tgl_kas_bud, '' kd_rek, 'PENGELUARAN BELANJA NON GAJI' nama,isnull(SUM(d.nilai), 0) AS nilai, 2 jenis")
        //     ->whereRaw("a.status_bud = ? AND  a.jns_spp != ? AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL) AND a.tgl_kas_bud<?", ['1', '4', $tgl])
        //     ->groupByRaw("a.tgl_kas_bud")
        //     ->unionAll($total_bku2);

        // $total_bku4 = DB::table('trhsp2d as a')
        //     ->join('trhspm as b', function ($join) {
        //         $join->on('a.no_spm', '=', 'b.no_spm');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //     })
        //     ->join('trhspp as c', function ($join) {
        //         $join->on('b.no_spp', '=', 'c.no_spp');
        //         $join->on('b.kd_skpd', '=', 'c.kd_skpd');
        //     })
        //     ->join('trdspp as d', function ($join) {
        //         $join->on('c.no_spp', '=', 'd.no_spp');
        //         $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        //     })
        //     ->selectRaw("a.tgl_kas_bud, '' kd_rek, 'PENGELUARAN BELANJA GAJI' nama,isnull(SUM(d.nilai), 0) AS nilai, 2 jenis")
        //     ->whereRaw("a.status_bud = ? AND  a.jns_spp = ? AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL) AND a.tgl_kas_bud<?", ['1', '4', $tgl])
        //     ->groupByRaw("a.tgl_kas_bud")
        //     ->unionAll($total_bku3);

        // $total_bku5 = DB::table('pengeluaran_non_sp2d as x')
        //     ->selectRaw("x.tanggal,'' kd_rek, 'PENGELUARAN NON SP2D' nama,isnull(SUM(x.nilai), 0) AS nilai, 2 jenis")
        //     ->whereRaw("x.tanggal<?", [$tgl])
        //     ->groupByRaw("x.tanggal")
        //     ->unionAll($total_bku4);

        // $total_bku6 = DB::table('trdrestitusi as b')
        //     ->join('trhrestitusi as a', function ($join) {
        //         $join->on('a.no_kas', '=', 'b.no_kas');
        //         $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        //         $join->on('a.no_sts', '=', 'b.no_sts');
        //     })
        //     ->selectRaw("a.tgl_kas,'' kd_rek, 'RESTITUSI' nama,isnull(SUM(b.rupiah), 0) AS nilai, 2 jenis")
        //     ->whereRaw("a.tgl_kas<?", [$tgl])
        //     ->where('a.jns_trans', '3')
        //     ->groupByRaw("a.tgl_kas")
        //     ->unionAll($total_bku5);

        // $total_bku7 = DB::table('trkasout_ppkd as w')
        //     ->selectRaw("w.tanggal,'' as kd_rek, 'KOREKSI PENERIMAAN' nama,isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
        //     ->whereRaw("w.tanggal<?", [$tgl])
        //     ->groupByRaw("w.tanggal,w.kd_rek")
        //     ->unionAll($total_bku6);

        // $total_bku8 = DB::table('trkoreksi_pengeluaran as w')
        //     ->selectRaw("w.tanggal,'' as kd_rek, 'KOREKSI PENGELUARAN' nama,isnull(SUM(w.nilai), 0) AS nilai, 2 jenis")
        //     ->whereRaw("w.tanggal<?", [$tgl])
        //     ->groupByRaw("w.tanggal,w.kd_rek")
        //     ->unionAll($total_bku7);

        // $total_bku9 = DB::table('penerimaan_non_sp2d as w')
        //     ->selectRaw("w.tanggal,'' as kd_rek, 'DEPOSITO' nama,isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
        //     ->whereRaw("w.tanggal<?", [$tgl])
        //     ->where('w.jenis', '1')
        //     ->groupByRaw("w.tanggal")
        //     ->unionAll($total_bku8);

        // $total_bku10 = DB::table('penerimaan_non_sp2d as w')
        //     ->selectRaw("w.tanggal,'' as kd_rek, 'PENERIMAAN NON SP2D' nama,isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
        //     ->whereRaw("w.tanggal<?", [$tgl])
        //     ->where('w.jenis', '2')
        //     ->groupByRaw("w.tanggal")
        //     ->unionAll($total_bku9);

        // $total_bku = DB::table(DB::raw("({$total_bku10->toSql()}) AS sub"))
        //     ->selectRaw("SUM(CASE WHEN jenis IN('1') THEN nilai ELSE 0 END) as trm_sbl,SUM(CASE WHEN jenis IN('2') THEN nilai ELSE 0 END) as klr_sbl")
        //     ->mergeBindings($total_bku10)
        //     ->first();

        $total_bku = collect(DB::select("SELECT SUM(CASE WHEN jenis IN('1') THEN nilai ELSE 0 END) as trm_sbl,
						SUM(CASE WHEN jenis IN('2') THEN nilai ELSE 0 END) as klr_sbl
			FROM(
				SELECT  a.tgl_kas,LEFT(b.kd_rek6,4) as kd_rek, UPPER(c.nm_rek3) as nama
				,SUM(rupiah) as nilai, 1 jenis
				FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd  and a.no_sts=b.no_sts
				LEFT JOIN ms_rek3 c ON LEFT(b.kd_rek6,4)=c.kd_rek3
				WHERE LEFT(b.kd_rek6,1) IN ('4') AND a.tgl_kas<'$tgl'
                and b.kd_rek6 not in
                -- ('420101040001','410416010001')
                ('420101040001','420101040002','420101040003','410416010001')
                -- 410409010001 REK INI DIHAPUS
				 GROUP BY a.tgl_kas,LEFT(b.kd_rek6,4),c.nm_rek3
				UNION ALL
				SELECT  a.tgl_kas,LEFT(b.kd_rek6,1) as kd_rek, 'CONTRA POST' as nama
				,SUM(rupiah) as nilai, 1 jenis
				FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
				WHERE LEFT(b.kd_rek6,1) IN ('5','1','2') AND a.tgl_kas<'$tgl'
				 GROUP BY a.tgl_kas,LEFT(b.kd_rek6,1)
				UNION ALL
				SELECT
				a.tgl_kas_bud, '' kd_rek, 'PENGELUARAN BELANJA NON GAJI' nama,
							isnull(SUM(d.nilai), 0) AS nilai, 2 jenis
						FROM
							trhsp2d a
						INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
						INNER JOIN trhspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
						INNER JOIN trdspp d ON c.no_spp = d.no_spp 	AND c.kd_skpd = d.kd_skpd
						WHERE a.status_bud = '1' AND  a.jns_spp != '4'
						AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
						AND a.tgl_kas_bud<'$tgl'
				GROUP BY a.tgl_kas_bud
				UNION ALL
				SELECT
				a.tgl_kas_bud, '' kd_rek, 'PENGELUARAN BELANJA GAJI' nama,
							isnull(SUM(d.nilai), 0) AS nilai, 2 jenis
						FROM trhsp2d a
						INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
						INNER JOIN trhspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
						INNER JOIN trdspp d ON c.no_spp = d.no_spp AND c.kd_skpd = d.kd_skpd
						WHERE a.status_bud = '1' AND a.jns_spp = '4'
						AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
						AND a.tgl_kas_bud<'$tgl'
				GROUP BY a.tgl_kas_bud
				UNION ALL
				SELECT
									x.tanggal,'' kd_rek, 'PENGELUARAN NON SP2D' nama,
									isnull(SUM(x.nilai), 0) AS nilai, 2 jenis
								FROM
									pengeluaran_non_sp2d x
								WHERE
									x.tanggal < '$tgl'
				GROUP BY x.tanggal
				UNION ALL
				SELECT
									a.tgl_kas,'' kd_rek, 'RESTITUSI' nama,
									isnull(SUM(b.rupiah), 0) AS nilai, 2 jenis
								FROM
									trdrestitusi b inner join trhrestitusi a on a.kd_skpd=b.kd_skpd and a.no_kas=b.no_kas and a.no_sts=b.no_sts
								WHERE a.jns_trans=3
									AND a.tgl_kas < '$tgl'
				GROUP BY a.tgl_kas
				UNION ALL
				SELECT
									w.tanggal,'' as kd_rek, 'KOREKSI PENERIMAAN' nama,
									isnull(SUM(w.nilai), 0) AS nilai, 1 jenis
								FROM
									trkasout_ppkd w
								WHERE
									w.tanggal < '$tgl'

				GROUP BY w.tanggal,w.kd_rek
				UNION ALL
				SELECT
									w.tanggal,'' as kd_rek, 'KOREKSI PENGELUARAN' nama,
									isnull(SUM(w.nilai), 0) AS nilai, 2 jenis
								FROM
									trkoreksi_pengeluaran w
								WHERE
									w.tanggal < '$tgl'

				GROUP BY w.tanggal,w.kd_rek
				UNION ALL
				SELECT
									w.tanggal,'' as kd_rek, 'DEPOSITO' nama,
									isnull(SUM(w.nilai), 0) AS nilai, 1 jenis
								FROM
									penerimaan_non_sp2d w
								WHERE
									w.tanggal < '$tgl'
								AND w.jenis='1'
				GROUP BY w.tanggal
				UNION ALL
				SELECT
									w.tanggal,'' as kd_rek, 'PENERIMAAN NON SP2D' nama,
									isnull(SUM(w.nilai), 0) AS nilai, 1 jenis
								FROM
									penerimaan_non_sp2d w
								WHERE
									w.tanggal < '$tgl'
								AND w.jenis='2'
				GROUP BY w.tanggal
				) a"))->first();

        $saldo_awal = DB::table('buku_kas')
            ->select('nilai')
            ->where(['nomor' => '0'])
            ->first();

        if ($tgl == '2019-01-01') {
            $saldo_awal = 0;
        } else {
            $saldo_awal = $saldo_awal->nilai;
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'skpd' => DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => '5.02.0.00.0.00.02.0000'])->first(),
            'data_bku' => $bku,
            'tanggal' => $tgl,
            'total_bku' => $total_bku,
            'saldo_awal' => $saldo_awal,
            'tanda_tangan' => $tanda_tangan
        ];

        $view = view('bud.laporan_bendahara.cetak.bku_rincian')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } else {
            return $view;
        }
    }

    public function pajakDaerah(Request $request)
    {
        $req = $request->all();
        $kd_skpd = Auth::user()->kd_skpd;
        $wilayah = $req['wilayah'];

        $data1 = DB::table('ms_wilayah')->select('kd_pengirim')->where(['kd_wilayah' => $wilayah])->get();
        $data3 = '';
        foreach ($data1 as $data) {
            $data3 = $data->kd_pengirim;
        }

        $pkb_all = "('410101010001','410101020001','410101030001','410101010002','410101020002','410101030002','410101010004','410101020004','410101030004','410101050001','410101050002','410101050004','410101080001','410101060001','410101080002','410101060002','410101080004','410101060004','410101130001','410101100001','410101100004','410101120001')";
        // $denda_pkb_all = "('410412010001','410412010002','410412010003','410412010005','410412010006','410412010008','410412010010','410412010012','410412010013','410412010051')";
        $denda_pkb_all = "('410412010001','410412010002','410412010003','410412010005','410412010006','410412010008','410412010010','410412010012','410412010013','410412010051','410412010015','410412010016','410412010017','410412010018','410412010019','410412010020','410412010021','410412010022','410412010023','410412010024','410412010025','410412010026','410412010027','410412010028','410412010029','410412010030','410412010031','410412010032','410412010033','410412010034','410412010035','410412010036','410412010037','410412010038','410412010039','410412010040','410412010041','410412010042','410412010043','410412010044','410412010045','410412010046','410412010047','410412010048','410412010049','410412010050','410412010052','410412010053','410412010054','410412010055','410412010056','410412010057','410412010058','410412010059','410412010060','410412010061','410412010062','410412010063','410412010064','410412010065','410412010066','410412010067','410412010068','410412010069','410412010070')";
        $tgk_pkb_all = "('4110114')";
        $bbn_all = "('4110201','4110202','4110203','4110204','4110205','410102010001','410102020001','410102030001','410102050001','410102060001','410102080001','410102100001','410102120001')";
        $denda_bbn_all = "('410412020001','410412020002','410412020003','410412020005','410412020006','410412020008','410412020010','410412020012','410412020013')";
        $denda_bbn_tka = "('4140704')";
        $pka_all = "('')";
        $bbnka_all = "('4110213')";
        $pap_all = "('410104010001')";
        $denda_pap_all = "('410412040001')";
        $sp3_all = "('430105010001')";
        $pbb_kb_all = "('410103010001','410103040001','410103020001')";
        // $jumlah_all = "('410101010001','410101020001','410101030001','410101010002','410101020002','410101030002','410101010004','410101020004','410101030004','410101050001','410101050002','410101050004','410101080001','410101060001','410101080002','410101060002','410101080004','410101060004','410101130001','410101100001','410101100004','410412010001','410412010002','410412010003','410412010005','410412010006','410412010008','410412010010','410412010012','410412010013','4110114','4110201','4110202','4110203','4110204','4110205','410102010001','410102020001','410102030001','410102050001','410102060001','410102080001','410102100001','410102120001','410412020001','410412020002','410412020003','410412020005','410412020006','410412020008','410412020010','410412020012','410412020013','410101120001','410104010001','4110401','410412040001','430105010001','410103010001','410103040001','410103020001','4140704','410412010051')";
        $jumlah_all = "('410101010001','410101020001','410101030001','410101010002','410101020002','410101030002','410101010004','410101020004','410101030004','410101050001','410101050002','410101050004','410101080001','410101060001','410101080002','410101060002','410101080004','410101060004','410101130001','410101100001','410101100004','410412010001','410412010002','410412010003','410412010005','410412010006','410412010008','410412010010','410412010012','410412010013','4110114','4110201','4110202','4110203','4110204','4110205','410102010001','410102020001','410102030001','410102050001','410102060001','410102080001','410102100001','410102120001','410412020001','410412020002','410412020003','410412020005','410412020006','410412020008','410412020010','410412020012','410412020013','410101120001','410104010001','4110401','410412040001','430105010001','410103010001','410103040001','410103020001','4140704','410412010051','410412010015','410412010016','410412010017','410412010018','410412010019','410412010020','410412010021','410412010022','410412010023','410412010024','410412010025','410412010026','410412010027','410412010028','410412010029','410412010030','410412010031','410412010032','410412010033','410412010034','410412010035','410412010036','410412010037','410412010038','410412010039','410412010040','410412010041','410412010042','410412010043','410412010044','410412010045','410412010046','410412010047','410412010048','410412010049','410412010050','410412010052','410412010053','410412010054','410412010055','410412010056','410412010057','410412010058','410412010059','410412010060','410412010061','410412010062','410412010063','410412010064','410412010065','410412010066','410412010067','410412010068','410412010069','410412010070')";

        if ($req['pilihan'] == '1') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("MONTH(a.tgl_kas)=?", [$req['bulan_perbulan']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $pajak_daerah = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("a.kd_pengirim,a.nm_pengirim,ISNULL(b.pkb,0) pkb, ISNULL(b.denda_pkb,0) denda_pkb, ISNULL(b.tgk_pkb,0) tgk_pkb, ISNULL(b.bbn,0) bbn, ISNULL(b.denda_bbn,0) denda_bbn,ISNULL(b.denda_bbntka,0) denda_bbntka , ISNULL(b.pka,0) pka, ISNULL(b.bbn_ka,0) bbn_ka, ISNULL(b.pap,0) pap, ISNULL(b.denda_pap,0) denda_pap, ISNULL(b.sp3,0) sp3,ISNULL(b.pbb_kb,0) pbb_kb, ISNULL(b.jumlah,0) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? and kd_pengirim NOT IN ('147')", ['4101'])
                // ->orderByRaw("cast(a.kd_pengirim AS int)")
                ->orderByRaw("kd_pengirim")
                ->get();
        } elseif ($req['pilihan'] == '2') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("a.tgl_kas=?", [$req['tgl_kas_pertanggal']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $pajak_daerah = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("a.kd_pengirim,a.nm_pengirim,ISNULL(b.pkb,0) pkb, ISNULL(b.denda_pkb,0) denda_pkb, ISNULL(b.tgk_pkb,0) tgk_pkb, ISNULL(b.bbn,0) bbn, ISNULL(b.denda_bbn,0) denda_bbn,ISNULL(b.denda_bbntka,0) denda_bbntka, ISNULL(b.pka,0) pka, ISNULL(b.bbn_ka,0) bbn_ka, ISNULL(b.pap,0) pap,ISNULL(b.denda_pap,0) denda_pap, ISNULL(b.sp3,0) sp3,ISNULL(b.pbb_kb,0) pbb_kb, ISNULL(b.jumlah,0) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? and kd_pengirim NOT IN ('147')", ['4101'])
                // ->orderByRaw("cast(a.kd_pengirim AS int)")
                ->orderByRaw("kd_pengirim")
                ->get();
        } elseif ($req['pilihan'] == '32') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd ,a.tgl_kas
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("b.sumber=? AND (MONTH(a.tgl_kas)>=? AND MONTH(a.tgl_kas)<=?)", [$req['pengirim'], $req['bulan1_pengirim'], $req['bulan2_pengirim']])
                ->groupByRaw("b.sumber,a.kd_skpd,a.tgl_kas");

            $pajak_daerah = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("a.kd_pengirim,a.nm_pengirim, b.tgl_kas, ISNULL(b.pkb,0) pkb, ISNULL(b.denda_pkb,0) denda_pkb, ISNULL(b.tgk_pkb,0) tgk_pkb, ISNULL(b.bbn,0) bbn, ISNULL(b.denda_bbn,0) denda_bbn,ISNULL(b.denda_bbntka,0) denda_bbntka, ISNULL(b.pka,0) pka, ISNULL(b.bbn_ka,0) bbn_ka, ISNULL(b.pap,0) pap,ISNULL(b.denda_pap,0) denda_pap, ISNULL(b.sp3,0) sp3,ISNULL(b.pbb_kb,0) pbb_kb, ISNULL(b.jumlah,0) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? AND a.kd_pengirim=?", ['4101', $req['pengirim']])
                // ->orderByRaw("cast(a.kd_pengirim AS int)")
                ->orderByRaw("kd_pengirim")
                ->get();
        } elseif ($req['pilihan'] == '31') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("b.sumber=? AND a.tgl_kas=?", [$req['pengirim'], $req['tgl_kas_pengirim']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $pajak_daerah = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("a.kd_pengirim,a.nm_pengirim,ISNULL(b.pkb,0) pkb, ISNULL(b.denda_pkb,0) denda_pkb, ISNULL(b.tgk_pkb,0) tgk_pkb, ISNULL(b.bbn,0) bbn, ISNULL(b.denda_bbn,0) denda_bbn,ISNULL(b.denda_bbntka,0) denda_bbntka, ISNULL(b.pka,0) pka, ISNULL(b.bbn_ka,0) bbn_ka, ISNULL(b.pap,0) pap,ISNULL(b.denda_pap,0) denda_pap, ISNULL(b.sp3,0) sp3,ISNULL(b.pbb_kb,0) pbb_kb, ISNULL(b.jumlah,0) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? AND a.kd_pengirim=?", ['4101', $req['pengirim']])
                // ->orderByRaw("cast(a.kd_pengirim AS int)")
                ->orderByRaw("kd_pengirim")
                ->get();
        } elseif ($req['pilihan'] == '41') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("a.tgl_kas=? AND b.sumber IN ($data3)", [$req['tgl_kas_wilayah']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $pajak_daerah = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("a.kd_pengirim,a.nm_pengirim,ISNULL(b.pkb,0) pkb, ISNULL(b.denda_pkb,0) denda_pkb, ISNULL(b.tgk_pkb,0) tgk_pkb, ISNULL(b.bbn,0) bbn, ISNULL(b.denda_bbn,0) denda_bbn,ISNULL(b.denda_bbntka,0) denda_bbntka, ISNULL(b.pka,0) pka, ISNULL(b.bbn_ka,0) bbn_ka, ISNULL(b.pap,0) pap,ISNULL(b.denda_pap,0) denda_pap, ISNULL(b.sp3,0) sp3,ISNULL(b.pbb_kb,0) pbb_kb, ISNULL(b.jumlah,0) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? AND a.kd_pengirim IN ($data3)", ['4101'])
                // ->orderByRaw("cast(a.kd_pengirim AS int)")
                ->orderByRaw("kd_pengirim")
                ->get();
        } elseif ($req['pilihan'] == '42') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("b.sumber IN ($data3) AND (MONTH(a.tgl_kas)>=? AND MONTH(a.tgl_kas)<=?)", [$req['bulan1_wilayah'], $req['bulan2_wilayah']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $pajak_daerah = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("a.kd_pengirim,a.nm_pengirim,ISNULL(b.pkb,0) pkb, ISNULL(b.denda_pkb,0) denda_pkb, ISNULL(b.tgk_pkb,0) tgk_pkb, ISNULL(b.bbn,0) bbn, ISNULL(b.denda_bbn,0) denda_bbn,ISNULL(b.denda_bbntka,0) denda_bbntka, ISNULL(b.pka,0) pka, ISNULL(b.bbn_ka,0) bbn_ka, ISNULL(b.pap,0) pap,ISNULL(b.denda_pap,0) denda_pap, ISNULL(b.sp3,0) sp3,ISNULL(b.pbb_kb,0) pbb_kb, ISNULL(b.jumlah,0) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? AND a.kd_pengirim IN ($data3)", ['4101'])
                // ->orderByRaw("cast(a.kd_pengirim AS int)")
                ->orderByRaw("kd_pengirim")
                ->get();
        } elseif ($req['pilihan'] == '5') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("MONTH(a.tgl_kas)>=? AND MONTH(a.tgl_kas)<=?", [$req['bulan_rekap1'], $req['bulan_rekap2']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $pajak_daerah = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("a.kd_pengirim,a.nm_pengirim,ISNULL(b.pkb,0) pkb, ISNULL(b.denda_pkb,0) denda_pkb, ISNULL(b.tgk_pkb,0) tgk_pkb, ISNULL(b.bbn,0) bbn, ISNULL(b.denda_bbn,0) denda_bbn,ISNULL(b.denda_bbntka,0) denda_bbntka, ISNULL(b.pka,0) pka, ISNULL(b.bbn_ka,0) bbn_ka, ISNULL(b.pap,0) pap, ISNULL(b.denda_pap,0) denda_pap, ISNULL(b.sp3,0) sp3,ISNULL(b.pbb_kb,0) pbb_kb, ISNULL(b.jumlah,0) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? and kd_pengirim NOT IN ('147')", ['4101'])
                // ->orderByRaw("cast(a.kd_pengirim AS int)")
                ->orderByRaw("kd_pengirim")
                ->get();
        }

        if ($req['pilihan'] == '1') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("MONTH(a.tgl_kas)<=?", [$req['bulan_perbulan']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $total_pajak_sebelumnya = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("SUM(ISNULL(b.pkb,0)) pkb, SUM(ISNULL(b.denda_pkb,0)) denda_pkb, SUM(ISNULL(b.tgk_pkb,0)) tgk_pkb, SUM(ISNULL(b.bbn,0)) bbn, SUM(ISNULL(b.denda_bbn,0)) denda_bbn,SUM(ISNULL(b.denda_bbntka,0)) denda_bbntka, SUM(ISNULL(b.pka,0)) pka, SUM(ISNULL(b.bbn_ka,0)) bbn_ka, SUM(ISNULL(b.pap,0)) pap,sum(ISNULL(b.denda_pap,0)) denda_pap, SUM(ISNULL(b.sp3,0)) sp3,SUM(ISNULL(b.pbb_kb,0)) pbb_kb, SUM(ISNULL(b.jumlah,0)) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? and kd_pengirim NOT IN ('147')", ['4101'])
                ->get();
        } elseif ($req['pilihan'] == '2') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("a.tgl_kas <=?", [$req['tgl_kas_sbl_pertanggal']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $total_pajak_sebelumnya = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("SUM(ISNULL(b.pkb,0)) pkb, SUM(ISNULL(b.denda_pkb,0)) denda_pkb, SUM(ISNULL(b.tgk_pkb,0)) tgk_pkb, SUM(ISNULL(b.bbn,0)) bbn, SUM(ISNULL(b.denda_bbn,0)) denda_bbn,SUM(ISNULL(b.denda_bbntka,0)) denda_bbntka, SUM(ISNULL(b.pka,0)) pka, SUM(ISNULL(b.bbn_ka,0)) bbn_ka, SUM(ISNULL(b.pap,0)) pap,sum(ISNULL(b.denda_pap,0)) denda_pap, SUM(ISNULL(b.sp3,0)) sp3, SUM(ISNULL(b.pbb_kb,0)) pbb_kb, SUM(ISNULL(b.jumlah,0)) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? and kd_pengirim NOT IN ('147')", ['4101'])
                ->get();
        } elseif ($req['pilihan'] == '31') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("b.sumber=? AND  a.tgl_kas<=?", [$req['pengirim'], $req['tgl_kas_sbl_pengirim']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $total_pajak_sebelumnya = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("SUM(ISNULL(b.pkb,0)) pkb, SUM(ISNULL(b.denda_pkb,0)) denda_pkb, SUM(ISNULL(b.tgk_pkb,0)) tgk_pkb, SUM(ISNULL(b.bbn,0)) bbn, SUM(ISNULL(b.denda_bbn,0)) denda_bbn,SUM(ISNULL(b.denda_bbntka,0)) denda_bbntka, SUM(ISNULL(b.pka,0)) pka, SUM(ISNULL(b.bbn_ka,0)) bbn_ka, SUM(ISNULL(b.pap,0)) pap,sum(ISNULL(b.denda_pap,0)) denda_pap, SUM(ISNULL(b.sp3,0)) sp3, SUM(ISNULL(b.pbb_kb,0)) pbb_kb, SUM(ISNULL(b.jumlah,0)) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? and a.kd_pengirim=?", ['4101', $req['pengirim']])
                ->get();
        } elseif ($req['pilihan'] == '32') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("b.sumber=? AND  MONTH(a.tgl_kas)<=?", [$req['pengirim'], $req['bulan2_pengirim']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $total_pajak_sebelumnya = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("SUM(ISNULL(b.pkb,0)) pkb, SUM(ISNULL(b.denda_pkb,0)) denda_pkb, SUM(ISNULL(b.tgk_pkb,0)) tgk_pkb, SUM(ISNULL(b.bbn,0)) bbn, SUM(ISNULL(b.denda_bbn,0)) denda_bbn,SUM(ISNULL(b.denda_bbntka,0)) denda_bbntka, SUM(ISNULL(b.pka,0)) pka, SUM(ISNULL(b.bbn_ka,0)) bbn_ka, SUM(ISNULL(b.pap,0)) pap,sum(ISNULL(b.denda_pap,0)) denda_pap, SUM(ISNULL(b.sp3,0)) sp3, SUM(ISNULL(b.pbb_kb,0)) pbb_kb, SUM(ISNULL(b.jumlah,0)) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? and a.kd_pengirim=?", ['4101', $req['pengirim']])
                ->get();
        } elseif ($req['pilihan'] == '41') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("b.sumber in ($data3) AND  a.tgl_kas<=?", [$req['tgl_kas_sbl_wilayah']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $total_pajak_sebelumnya = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("SUM(ISNULL(b.pkb,0)) pkb, SUM(ISNULL(b.denda_pkb,0)) denda_pkb, SUM(ISNULL(b.tgk_pkb,0)) tgk_pkb, SUM(ISNULL(b.bbn,0)) bbn, SUM(ISNULL(b.denda_bbn,0)) denda_bbn,SUM(ISNULL(b.denda_bbntka,0)) denda_bbntka, SUM(ISNULL(b.pka,0)) pka, SUM(ISNULL(b.bbn_ka,0)) bbn_ka, SUM(ISNULL(b.pap,0)) pap,sum(ISNULL(b.denda_pap,0)) denda_pap, SUM(ISNULL(b.sp3,0)) sp3, SUM(ISNULL(b.pbb_kb,0)) pbb_kb, SUM(ISNULL(b.jumlah,0)) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? AND a.kd_pengirim in ($data3)", ['4101'])
                ->get();
        } elseif ($req['pilihan'] == '42') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("b.sumber in ($data3) AND  MONTH(a.tgl_kas)<=?", [$req['bulan2_wilayah']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $total_pajak_sebelumnya = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("SUM(ISNULL(b.pkb,0)) pkb, SUM(ISNULL(b.denda_pkb,0)) denda_pkb, SUM(ISNULL(b.tgk_pkb,0)) tgk_pkb, SUM(ISNULL(b.bbn,0)) bbn, SUM(ISNULL(b.denda_bbn,0)) denda_bbn,SUM(ISNULL(b.denda_bbntka,0)) denda_bbntka, SUM(ISNULL(b.pka,0)) pka, SUM(ISNULL(b.bbn_ka,0)) bbn_ka, SUM(ISNULL(b.pap,0)) pap,sum(ISNULL(b.denda_pap,0)) denda_pap, SUM(ISNULL(b.sp3,0)) sp3, SUM(ISNULL(b.pbb_kb,0)) pbb_kb, SUM(ISNULL(b.jumlah,0)) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? AND a.kd_pengirim in ($data3)", ['4101'])
                ->get();
        } elseif ($req['pilihan'] == '5') {
            $join1 = DB::table('trhkasin_ppkd as a')
                ->join('trdkasin_ppkd as b', function ($join) {
                    $join->on('a.no_sts', '=', 'b.no_sts');
                    $join->on('a.no_kas', '=', 'b.no_kas');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("b.sumber,a.kd_skpd
                                ,SUM(CASE WHEN b.kd_rek6 IN $pkb_all THEN b.rupiah ELSE 0 END) as pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pkb_all THEN b.rupiah ELSE 0 END) as denda_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $tgk_pkb_all THEN b.rupiah ELSE 0 END) as tgk_pkb
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbn_all THEN b.rupiah ELSE 0 END) as bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_all THEN b.rupiah ELSE 0 END) as denda_bbn
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_bbn_tka THEN b.rupiah ELSE 0 END) as denda_bbntka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pka_all THEN b.rupiah ELSE 0 END) as pka
                                ,SUM(CASE WHEN b.kd_rek6 IN $bbnka_all THEN b.rupiah ELSE 0 END) as bbn_ka
                                ,SUM(CASE WHEN b.kd_rek6 IN $pap_all THEN b.rupiah ELSE 0 END) as pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $denda_pap_all THEN b.rupiah ELSE 0 END) as denda_pap
                                ,SUM(CASE WHEN b.kd_rek6 IN $sp3_all THEN b.rupiah ELSE 0 END) as sp3
                                ,SUM(CASE WHEN b.kd_rek6 IN $pbb_kb_all THEN b.rupiah ELSE 0 END) as pbb_kb
                                ,SUM(CASE WHEN b.kd_rek6 IN $jumlah_all THEN b.rupiah ELSE 0 END) as jumlah")
                ->whereRaw("MONTH(a.tgl_kas)>=? AND MONTH(a.tgl_kas)<=?", [$req['bulan_rekap1'], $req['bulan_rekap2']])
                ->groupByRaw("b.sumber,a.kd_skpd");

            $total_pajak_sebelumnya = DB::table('ms_pengirim as a')
                ->leftJoinSub($join1, 'b', function ($join) {
                    $join->on('a.kd_pengirim', '=', 'b.sumber');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })->selectRaw("SUM(ISNULL(b.pkb,0)) pkb, SUM(ISNULL(b.denda_pkb,0)) denda_pkb, SUM(ISNULL(b.tgk_pkb,0)) tgk_pkb, SUM(ISNULL(b.bbn,0)) bbn, SUM(ISNULL(b.denda_bbn,0)) denda_bbn,SUM(ISNULL(b.denda_bbntka,0)) denda_bbntka, SUM(ISNULL(b.pka,0)) pka, SUM(ISNULL(b.bbn_ka,0)) bbn_ka, SUM(ISNULL(b.pap,0)) pap,sum(ISNULL(b.denda_pap,0)) denda_pap, SUM(ISNULL(b.sp3,0)) sp3, SUM(ISNULL(b.pbb_kb,0)) pbb_kb,SUM(ISNULL(b.jumlah,0)) jumlah")
                ->whereRaw("LEFT(jns_rek,4)=? and kd_pengirim NOT IN ('147')", ['4101'])
                ->get();
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'skpd' => DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'pilihan' => $req['pilihan'],
            'wilayah' => DB::table('ms_wilayah')->select('nm_wilayah')->where(['kd_wilayah' => $req['wilayah']])->first(),
            'data_awal' => $req,
            'pajak_daerah' => $pajak_daerah,
            'total_pajak_sebelumnya' => $total_pajak_sebelumnya
        ];

        $view = view('bud.laporan_bendahara.cetak.pajak_daerah')->with($data);

        if ($req['jenis_print'] == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($req['jenis_print'] == 'layar') {
            return $view;
        }
    }

    public function rekapGaji(Request $request)
    {
        $req = $request->all();

        if ($req['jenis'] == '3') {
            $rekap_gaji1 = DB::table('trhsp2d as a')
                ->selectRaw("a.kd_skpd,a.nm_skpd,a.no_sp2d nomor,a.nilai nilai_sp2d,0 as IWP1,0 AS IWP8,0 AS IWP325,0 AS JKK,0 JKM,0 AS PPH21,0 AS TAPERUM,0 AS HKPG,0 PPNPN1,0 PPNPN4,0 DPRD1,0 DPRD4")
                ->whereRaw("(a.sp2d_batal IS NULL OR a.sp2d_batal !=?)", ['1'])
                ->where(function ($query) use ($req) {
                    if ($req['kd_skpd']) {
                        $query->where('a.kd_skpd', $req['kd_skpd']);
                    }
                })
                ->where(function ($query) use ($req) {
                    if ($req['pilihan'] == '12' || $req['pilihan'] == '22') {
                        $query->where(DB::raw("MONTH(tgl_sp2d)"), $req['bulan']);
                    }
                    if ($req['pilihan'] == '13' || $req['pilihan'] == '23') {
                        $query->whereBetween('tgl_sp2d', [$req['periode1'], $req['periode2']]);
                    }
                })
                ->where(function ($query) use ($req) {
                    if ($req['jenis'] == 3) {
                        $query->whereRaw("a.jns_spp=? AND a.jenis_beban=?", ['6', '4']);
                    }
                })
                ->groupByRaw("a.kd_skpd,a.nm_skpd,a.no_sp2d,a.nilai");

            $rekap_gaji2 = DB::table('trhsp2d as a')
                ->join('trspmpot as b', function ($join) {
                    $join->on('a.no_spm', '=', 'b.no_spm');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("a.kd_skpd,a.nm_skpd,a.no_sp2d nomor,0 nilai_sp2d,SUM(CASE WHEN b.map_pot='21010801000102' THEN b.nilai ELSE 0 END) AS IWP1,SUM(CASE WHEN b.map_pot='21010801000103' THEN b.nilai ELSE 0 END) AS IWP8,SUM(CASE WHEN b.map_pot='21010801000104' THEN b.nilai ELSE 0 END) AS IWP325,SUM(CASE WHEN b.kd_rek6='210103010001' THEN b.nilai ELSE 0 END) AS JKK,SUM(CASE WHEN b.kd_rek6='210104010001' THEN b.nilai ELSE 0 END) AS JKM,SUM(CASE WHEN b.kd_rek6='210105010001' THEN b.nilai ELSE 0 END) AS PPH21,SUM(CASE WHEN b.kd_rek6='' THEN 0 ELSE 0 END) AS TAPERUM,SUM(CASE WHEN b.kd_rek6 in ('210601010007','210601010003','210601010011','210601010009') THEN b.nilai ELSE 0 END) AS HKPG,SUM(CASE WHEN b.map_pot='21010201000104' THEN b.nilai ELSE 0 END) AS PPNPN1,SUM(CASE WHEN b.map_pot='21010201000105' THEN b.nilai ELSE 0 END) AS PPNPN4,SUM(CASE WHEN b.map_pot='21010201000102' THEN b.nilai ELSE 0 END) AS DPRD1,SUM(CASE WHEN b.map_pot='21010201000103' THEN b.nilai ELSE 0 END) AS DPRD4")
                ->whereRaw("(a.sp2d_batal IS NULL OR a.sp2d_batal !=?)", ['1'])
                ->where(function ($query) use ($req) {
                    if ($req['kd_skpd']) {
                        $query->where('a.kd_skpd', $req['kd_skpd']);
                    }
                })
                ->where(function ($query) use ($req) {
                    if ($req['pilihan'] == '12' || $req['pilihan'] == '22') {
                        $query->where(DB::raw("MONTH(tgl_sp2d)"), $req['bulan']);
                    }
                    if ($req['pilihan'] == '13' || $req['pilihan'] == '23') {
                        $query->whereBetween('tgl_sp2d', [$req['periode1'], $req['periode2']]);
                    }
                })
                ->where(function ($query) use ($req) {
                    if ($req['jenis'] == 3) {
                        $query->whereRaw("a.jns_spp=? AND a.jenis_beban=?", ['6', '4']);
                    }
                })
                ->groupByRaw("a.kd_skpd,a.nm_skpd,a.no_sp2d,a.nilai")
                ->unionAll($rekap_gaji1);

            $rekap_gaji = DB::table(DB::raw("({$rekap_gaji2->toSql()}) AS sub"))
                ->selectRaw("kd_skpd,nm_skpd,nomor,sum(nilai_sp2d) nilai_sp2d, sum(IWP1) IWP1, sum(IWP8) IWP8, sum(IWP325) IWP325, sum(JKK) JKK, sum(JKM) JKM, sum(PPH21) PPH21, sum(TAPERUM) TAPERUM, sum(HKPG) HKPG, sum(PPNPN1) PPNPN1, sum(PPNPN4) PPNPN4,sum(DPRD1) DPRD1, sum(DPRD4) DPRD4, sum(IWP1) + sum(IWP8) + sum(IWP325) + sum(JKK) + sum(JKM) + sum(PPH21) + sum(TAPERUM) + sum(HKPG) + sum(PPNPN1) + sum(PPNPN4) + sum(DPRD1) + sum(DPRD4)  as Total")
                ->mergeBindings($rekap_gaji2)
                ->groupByRaw("kd_skpd,nm_skpd,nomor")
                ->orderBy('kd_skpd')
                ->get();
        } else {
            $rekap_gaji1 = DB::table('trhsp2d as a')
                ->selectRaw("a.kd_skpd,a.nm_skpd,a.no_sp2d nomor,a.nilai nilai_sp2d,0 as IWP1,0 AS IWP8,0 AS IWP325,0 AS JKK,0 JKM,0 AS BPJS,0 AS PPH21,0 AS TAPERUM,0 AS HKPG")
                ->whereRaw("(a.sp2d_batal IS NULL OR a.sp2d_batal !=?)", ['1'])
                ->where(function ($query) use ($req) {
                    if ($req['kd_skpd']) {
                        $query->where('a.kd_skpd', $req['kd_skpd']);
                    }
                })
                ->where(function ($query) use ($req) {
                    if ($req['pilihan'] == '12' || $req['pilihan'] == '22') {
                        // $query->where(DB::raw("MONTH(tgl_sp2d)"), $req['bulan'])->where('a.jenis_beban', '1');
                        $query->where(DB::raw("MONTH(tgl_sp2d)"), $req['bulan']);
                    }
                    if ($req['pilihan'] == '13' || $req['pilihan'] == '23') {
                        $query->whereBetween('tgl_sp2d', [$req['periode1'], $req['periode2']]);
                    }
                })
                ->where(function ($query) use ($req) {
                    if ($req['jenis'] == 1) {
                        $query->where('a.jenis_beban', '1')->whereRaw("a.no_sp2d like '%GJ%'");
                    } else if ($req['jenis'] == 2) {
                        $query->whereRaw("a.no_sp2d like '%GJ%'");
                    } else if ($req['jenis'] == 4) {
                        $query->whereRaw("a.jns_spp=? AND a.jenis_beban=?", ['6', '1']);
                    }
                })
                ->groupByRaw("a.kd_skpd,a.nm_skpd,a.no_sp2d,a.nilai");

            $rekap_gaji2 = DB::table('trhsp2d as a')
                ->join('trspmpot as b', function ($join) {
                    $join->on('a.no_spm', '=', 'b.no_spm');
                    $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                })
                ->selectRaw("a.kd_skpd,a.nm_skpd,a.no_sp2d nomor,0 nilai_sp2d,SUM(CASE WHEN b.map_pot='21010801000102' THEN b.nilai ELSE 0 END) AS IWP1,SUM(CASE WHEN b.map_pot='21010801000103' THEN b.nilai ELSE 0 END) AS IWP8,SUM(CASE WHEN b.map_pot='21010801000104' THEN b.nilai ELSE 0 END) AS IWP325,SUM(CASE WHEN b.kd_rek6='210103010001' THEN b.nilai ELSE 0 END) AS JKK,SUM(CASE WHEN b.kd_rek6='210104010001' THEN b.nilai ELSE 0 END) AS JKM,SUM(CASE WHEN b.kd_rek6='210102010001' THEN b.nilai ELSE 0 END) AS BPJS,SUM(CASE WHEN b.kd_rek6='210105010001' THEN b.nilai ELSE 0 END) AS PPH21,SUM(CASE WHEN b.kd_rek6='' THEN 0 ELSE 0 END) AS TAPERUM,SUM(CASE WHEN b.kd_rek6 in ('210601010007','210601010003','210601010011','210601010009') THEN b.nilai ELSE 0 END) AS HKPG")
                ->whereRaw("(a.sp2d_batal IS NULL OR a.sp2d_batal !=?)", ['1'])
                ->where(function ($query) use ($req) {
                    if ($req['kd_skpd']) {
                        $query->where('a.kd_skpd', $req['kd_skpd']);
                    }
                })
                ->where(function ($query) use ($req) {
                    if ($req['pilihan'] == '12' || $req['pilihan'] == '22') {
                        // $query->where(DB::raw("MONTH(tgl_sp2d)"), $req['bulan'])->where('a.jenis_beban', '1');
                        $query->where(DB::raw("MONTH(tgl_sp2d)"), $req['bulan']);
                    }
                    if ($req['pilihan'] == '13' || $req['pilihan'] == '23') {
                        $query->whereBetween('tgl_sp2d', [$req['periode1'], $req['periode2']]);
                    }
                })
                ->where(function ($query) use ($req) {
                    if ($req['jenis'] == 1) {
                        $query->where('a.jenis_beban', '1')->whereRaw("a.no_sp2d like '%GJ%'");
                    } else if ($req['jenis'] == 2) {
                        $query->whereRaw("a.no_sp2d like '%GJ%'");
                    } else if ($req['jenis'] == 4) {
                        $query->whereRaw("a.jns_spp=? AND a.jenis_beban=?", ['6', '1']);
                    }
                })
                ->groupByRaw("a.kd_skpd,a.nm_skpd,a.no_sp2d,a.nilai")
                ->unionAll($rekap_gaji1);

            $rekap_gaji = DB::table(DB::raw("({$rekap_gaji2->toSql()}) AS sub"))
                ->selectRaw("kd_skpd,nm_skpd,nomor,sum(nilai_sp2d) nilai_sp2d, sum(IWP1) IWP1, sum(IWP8) IWP8, sum(IWP325) IWP325, sum(JKK) JKK, sum(JKM) JKM, sum(BPJS) BPJS, sum(PPH21) PPH21, sum(TAPERUM) TAPERUM, sum(HKPG) HKPG, sum(IWP1) + sum(IWP8) + sum(IWP325) + sum(JKK) + sum(JKM) + sum(BPJS) + sum(PPH21) + sum(TAPERUM) + sum(HKPG) as Total")
                ->mergeBindings($rekap_gaji2)
                ->groupByRaw("kd_skpd,nm_skpd,nomor")
                ->orderBy('kd_skpd')
                ->get();
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $req['pilihan'],
            'data_awal' => $req,
            'rekap_gaji' => $rekap_gaji,
            'jenis' => $req['jenis']
        ];

        $view = view('bud.laporan_bendahara.cetak.rekap_gaji')->with($data);

        if ($req['jenis_print'] == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($req['jenis_print'] == 'layar') {
            return $view;
        } elseif ($req['jenis_print'] == 'excel') {
            $judul = 'REKAP GAJI';
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }

    public function rekapBBKasda(Request $request)
    {
        $req = $request->all();
        $kd_skpd  = $request->kd_skpd;
        $kd_rek  = $request->rekening;

        $buku_besar_kasda = DB::select("SELECT tgl_kas, no_kas,keterangan,0 as debet, kredit from (
					select a.kd_skpd, a.tgl_kas, a.no_kas, b.kd_rek6, keterangan+', '+(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) keterangan,0 as debet, rupiah as kredit
					from trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts AND a.no_kas=b.no_kas
					where pot_khusus<>3 AND jns_trans NOT IN ('2') AND b.kd_rek6 = ?
					union all
					select '5.02.0.00.0.00.02.0000' kd_skpd, a.tgl_kas, a.no_kas, '410415030001' kd_rek6, keterangan+', '+(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) keterangan,0 as debet, rupiah as kredit
					from trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts and a.no_kas=b.no_kas
					where jns_trans IN ('5') AND jns_cp='1' AND pot_khusus='3'
					union all
					select '5.02.0.00.0.00.02.0000' kd_skpd, a.tgl_kas, a.no_kas, '4141009' kd_rek6, keterangan+', '+(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) keterangan,0 as debet, rupiah as kredit
					from trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts and a.no_kas=b.no_kas
					where jns_trans IN ('2')
					) a
					where kd_skpd= ? and kd_rek6= ? and tgl_kas between ? AND ?
					order by tgl_kas, no_kas", [$req['rekening'], $req['kd_skpd'], $req['rekening'], $req['periode1'], $req['periode2']]);

        $periode1  = $request->periode1;
        $periode2  = $request->periode2;
        $periode1 = tanggal($periode1);
        $periode2 = tanggal($periode2);

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'skpd'   => DB::table('ms_skpd')->select('nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'rekening'   => DB::table('ms_rek6')->select('kd_rek6', 'nm_rek6')->where(['kd_rek6' => $kd_rek])->first(),
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_awal' => $req,
            'buku_besar_kasda' => $buku_besar_kasda
        ];

        $view = view('bud.laporan_bendahara.cetak.buku_besar_kasda')->with($data);

        if ($req['jenis_print'] == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($req['jenis_print'] == 'layar') {
            return $view;
        }
    }

    public function pembantuPengeluaran(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $spasi = $request->spasi;
        $ttd = $request->ttd;
        $tipe = $request->tipe;
        $jenis_print = $request->jenis_print;

        if ($pilihan == '1') {
            $where = "AND a.tgl_kas_bud=?";
            $where2 = "AND a.tgl_kas_bud < ?";
        } elseif ($pilihan == '2') {
            $where = "a.tgl_kas_bud BETWEEN ? AND ?";
            $where2 = "a.tgl_kas_bud < ?";
        }

        $pengeluaran1 = DB::table('trhsp2d as a')
            ->join('trhspm as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->join('trdspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })
            ->selectRaw("'1' + a.no_sp2d as urut0,1 as urut, a.no_kas_bud as urut2,a.no_kas_bud, a.no_sp2d ,a.tgl_sp2d, a.kd_skpd,a.keperluan, a.jns_spp,a.jenis_beban,a.nmrekan,c.pimpinan, '' kd_sub_kegiatan,''kd_rek6,SUM(d.nilai) nilai, '' no_bukti, count(a.no_sp2d) as jumlah")
            ->whereRaw("a.status_bud=? AND (c.sp2d_batal=? OR c.sp2d_batal is NULL)", ['1', 0])
            ->where(function ($query) use ($tipe) {
                if ($tipe == '0') {
                    $query->where('a.jns_spp', '4');
                } else if ($tipe == '1') {
                    $query->where('a.jns_spp', '!=', '4');
                }
            })
            ->where(function ($query) use ($pilihan, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->where('a.tgl_kas_bud', $tgl);
                } else if ($pilihan == '2') {
                    $query->whereBetween('a.tgl_kas_bud', [$periode1, $periode2]);
                }
            })
            ->groupByRaw("a.no_kas_bud, a.no_sp2d ,a.tgl_sp2d, a.kd_skpd,a.keperluan,a.jns_spp,a.jenis_beban,a.nmrekan,c.pimpinan");

        $pengeluaran2 = DB::table('trhsp2d as a')
            ->join('trhspm as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->join('trdspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })
            ->selectRaw("'1' +a.no_sp2d as urut0,2 urut, '' no_kas_bud, a.no_kas_bud AS urut2, '' no_sp2d, '' tgl_sp2d, a.kd_skpd, '' keperluan, '' jns_spp, '' jenis_beban, '' nmrekan, '' pimpinan, d.kd_sub_kegiatan, d.kd_rek6, d.nilai, d.no_bukti, 0 AS jumlah")
            ->whereRaw("a.status_bud=? AND (c.sp2d_batal=? OR c.sp2d_batal is NULL)", ['1', 0])
            ->where(function ($query) use ($tipe) {
                if ($tipe == '0') {
                    $query->where('a.jns_spp', '4');
                } else if ($tipe == '1') {
                    $query->where('a.jns_spp', '!=', '4');
                }
            })
            ->where(function ($query) use ($pilihan, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->where('a.tgl_kas_bud', $tgl);
                } else if ($pilihan == '2') {
                    $query->whereBetween('a.tgl_kas_bud', [$periode1, $periode2]);
                }
            })
            ->groupByRaw("a.no_kas_bud, a.no_sp2d ,a.tgl_sp2d, a.kd_skpd,a.keperluan, d.kd_sub_kegiatan, d.kd_rek6,d.nilai,d.no_bukti,d.kd")
            ->union($pengeluaran1);

        $pengeluaran = DB::table(DB::raw("({$pengeluaran2->toSql()}) AS sub"))
            ->mergeBindings($pengeluaran2)
            ->orderByRaw("urut0,urut,cast(no_kas_bud as int),kd_sub_kegiatan,kd_rek6")
            ->get();

        $pengeluaran_lalu = DB::table('trhsp2d as a')
            ->join('trhspm as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->join('trdspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('c.kd_skpd', '=', 'd.kd_skpd');
            })
            ->selectRaw("sum(d.nilai) as nilai")
            ->whereRaw("a.status_bud=? AND (c.sp2d_batal=? OR c.sp2d_batal is NULL)", ['1', 0])
            ->where(function ($query) use ($tipe) {
                if ($tipe == '0') {
                    $query->where('a.jns_spp', '4');
                } else if ($tipe == '1') {
                    $query->where('a.jns_spp', '!=', '4');
                }
            })
            ->where(function ($query) use ($pilihan, $tgl, $periode1, $periode2) {
                if ($pilihan == '1') {
                    $query->where('a.tgl_kas_bud', '<', $tgl);
                } else if ($pilihan == '2') {
                    $query->where('a.tgl_kas_bud', '<', $periode1);
                }
            })
            ->first();

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal' => $tgl,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'tipe' => $tipe,
            'data_pengeluaran' => $pengeluaran,
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
            'total_pengeluaran_lalu' => $pengeluaran_lalu->nilai
        ];

        $view = view('bud.laporan_bendahara.cetak.pembantu_pengeluaran')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function retribusi(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $spasi = $request->spasi;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        if ($kd_skpd == '-') {
            $and = '';
            $and1 = '';
        } else {
            $and = "and a.kd_skpd='$kd_skpd'";
            $and1 = "and kd_skpd='$kd_skpd'";
        }

        $retribusi = DB::select("SELECT * from(
					SELECT
						1 as urut,
						''no_sts,
						''kd_skpd,
						f.nm_skpd,
						''kd_sub_kegiatan,
						''kd_rek6,
						b.no_kas,
						''tgl_kas,
						ISNULL(e.nm_pengirim, '') nm_pengirim,
						''nm_rek6,
						0 rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					LEFT JOIN ms_pengirim e ON b.sumber = e.kd_pengirim and e.kd_skpd=b.kd_skpd
					INNER JOIN ms_skpd f ON a.kd_skpd = f.kd_skpd
					WHERE b.tgl_kas=? AND a.kd_skpd !='1.20.15.17' $and  AND LEFT(a.kd_rek6,4) IN ('4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND LEFT(a.kd_rek6,6) NOT IN ('410412','410416') AND a.kd_rek6 NOT IN ('420101040001','420101040002','420101040003')
					and a.sumber<>'y'
					GROUP BY b.no_kas,nm_pengirim, f.nm_skpd
					UNION ALL
					SELECT
						2 as urut,
						b.no_sts,
						a.kd_skpd,
						'' nm_skpd,
						a.kd_sub_kegiatan,
						a.kd_rek6,
						b.no_kas,
						b.tgl_kas,
						'' nm_pengirim,
						c.nm_rek6,
						a.rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					LEFT JOIN ms_pengirim e ON b.sumber = e.kd_pengirim and e.kd_skpd=b.kd_skpd
					WHERE b.tgl_kas=? AND a.kd_skpd !='1.20.15.17' $and AND LEFT(a.kd_rek6,4) IN ('4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND LEFT(a.kd_rek6,6) NOT IN ('410412','410416') AND a.kd_rek6 NOT IN ('420101040001','420101040002','420101040003')
					and a.sumber<>'y'
					UNION ALL
					SELECT
						1 as urut,
						''no_sts,
						''kd_skpd,
						f.nm_skpd,
						''kd_sub_kegiatan,
						''kd_rek6,
						b.no_kas,
						''tgl_kas,
						''nm_pengirim,
						''nm_rek6,
						0 rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					INNER JOIN ms_skpd f ON a.kd_skpd = f.kd_skpd
					WHERE b.tgl_kas=? AND a.kd_skpd ='1.20.15.17' $and AND LEFT(a.kd_rek6,4) IN ('4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND a.kd_rek6 NOT IN ('420101040001','420101040002','420101040003')
					and a.sumber<>'y'
					GROUP BY b.no_kas,f.nm_skpd
					UNION ALL
					SELECT
						2 as urut,
						b.no_sts,
						a.kd_skpd,
						'' nm_skpd,
						a.kd_sub_kegiatan,
						a.kd_rek6,
						b.no_kas,
						b.tgl_kas,
						'' nm_pengirim,
						b.keterangan nm_rek6,
						a.rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas  AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					WHERE b.tgl_kas=? AND a.kd_skpd ='1.20.15.17' $and AND LEFT(a.kd_rek6,4) IN ('4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND a.kd_rek6 NOT IN ('420101040001','420101040002','420101040003')
					and a.sumber<>'y'

					UNION ALL
					SELECT
							1 AS urut,
							'' no_sts,
							'' kd_skpd,
							nm_skpd,
							'' kd_sub_kegiatan,
							'' kd_rek6,
							[no] as no_kas,
							'' tgl_kas,
							'' nm_pengirim,
							'' nm_rek6,
							0 rupiah
						FROM
							trkasout_ppkd
						WHERE
							tanggal = ? AND LEFT(kd_rek,4) IN ('4102','4103','4104','4201','4202') AND LEFT(kd_rek,5) NOT IN ('41407') AND kd_rek NOT IN ('420101040001','420101040002','420101040003') $and1 and [no] not in ('15796')
						UNION ALL
						SELECT
								2 AS urut,
								[no] as no_sts,
								kd_skpd,
								'' nm_skpd,
								''kd_sub_kegiatan,
								kd_rek kd_rek6,
								[no] no_kas,
								[tanggal] tgl_kas,
								'' nm_pengirim,
								keterangan+' '+nm_rek nm_rek6,
								nilai rupiah
							FROM
							trkasout_ppkd
							WHERE
							tanggal = ?
							AND LEFT(kd_rek,4) IN ('4102','4103','4104','4201','4202') AND LEFT(kd_rek,5) NOT IN ('41407') AND kd_rek NOT IN ('420101040001','420101040002','420101040003') $and1 and [no] not in ('15796')
					) a

					order by cast(no_kas as int),urut", [$tgl, $tgl, $tgl, $tgl, $tgl, $tgl]);

        $retribusi_lalu = collect(DB::select("SELECT sum(rupiah) as nilai from(
					SELECT
						1 as urut,
						''no_sts,
						''kd_skpd,
						f.nm_skpd,
						''kd_sub_kegiatan,
						''kd_rek6,
						b.no_kas,
						''tgl_kas,
						ISNULL(e.nm_pengirim, '') nm_pengirim,
						''nm_rek6,
						0 rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					LEFT JOIN ms_pengirim e ON b.sumber = e.kd_pengirim and e.kd_skpd=b.kd_skpd
					INNER JOIN ms_skpd f ON a.kd_skpd = f.kd_skpd
					WHERE b.tgl_kas <=? AND a.kd_skpd !='1.20.15.17' $and  AND LEFT(a.kd_rek6,4) IN ('4102')  and a.sumber<>'y'
					GROUP BY b.no_kas,nm_pengirim, f.nm_skpd
					UNION ALL
					SELECT
						2 as urut,
						b.no_sts,
						a.kd_skpd,
						'' nm_skpd,
						a.kd_sub_kegiatan,
						a.kd_rek6,
						b.no_kas,
						b.tgl_kas,
						'' nm_pengirim,
						c.nm_rek6,
						a.rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					LEFT JOIN ms_pengirim e ON b.sumber = e.kd_pengirim and e.kd_skpd=b.kd_skpd
					WHERE b.tgl_kas<=? AND a.kd_skpd !='1.20.15.17' $and AND LEFT(a.kd_rek6,4) IN ('4102')  and a.sumber<>'y'

					UNION ALL
					SELECT
						1 as urut,
						''no_sts,
						''kd_skpd,
						f.nm_skpd,
						''kd_sub_kegiatan,
						''kd_rek6,
						b.no_kas,
						''tgl_kas,
						''nm_pengirim,
						''nm_rek6,
						0 rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					INNER JOIN ms_skpd f ON a.kd_skpd = f.kd_skpd
					WHERE b.tgl_kas<=? AND a.kd_skpd ='1.20.15.17' $and AND LEFT(a.kd_rek6,4) IN ('4102') and a.sumber<>'y'
					GROUP BY b.no_kas,f.nm_skpd
					UNION ALL
					SELECT
						2 as urut,
						b.no_sts,
						a.kd_skpd,
						'' nm_skpd,
						a.kd_sub_kegiatan,
						a.kd_rek6,
						b.no_kas,
						b.tgl_kas,
						'' nm_pengirim,
						b.keterangan nm_rek6,
						a.rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas  AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					WHERE b.tgl_kas<=? AND a.kd_skpd ='1.20.15.17' $and AND LEFT(a.kd_rek6,4) IN ('4102') and a.sumber<>'y'

					UNION ALL
					SELECT
							1 AS urut,
							'' no_sts,
							'' kd_skpd,
							nm_skpd,
							'' kd_sub_kegiatan,
							'' kd_rek6,
							[no] as no_kas,
							'' tgl_kas,
							'' nm_pengirim,
							'' nm_rek6,
							0 rupiah
						FROM
							trkasout_ppkd
						WHERE
							tanggal <= ? AND LEFT(kd_rek,4) IN ('4102') $and1 and [no] not in ('15796')
						UNION ALL
						SELECT
								2 AS urut,
								[no] as no_sts,
								kd_skpd,
								'' nm_skpd,
								''kd_sub_kegiatan,
								kd_rek kd_rek6,
								[no] no_kas,
								[tanggal] tgl_kas,
								'' nm_pengirim,
								keterangan+' '+nm_rek nm_rek6,
								nilai rupiah
							FROM
							trkasout_ppkd
							WHERE
							tanggal <= ?
							AND LEFT(kd_rek,4) IN ('4102') $and1 and [no] not in ('15796')
					) a", [$tgl, $tgl, $tgl, $tgl, $tgl, $tgl]))->first();

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'tanggal' => $tgl,
            'daftar_retribusi' => $retribusi,
            'total_retribusi_lalu' => $retribusi_lalu->nilai,
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
        ];

        $view = view('bud.laporan_bendahara.cetak.retribusi')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function kartuKendali(Request $request)
    {
        $data = [
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->orderBy('kd_skpd')
                ->get(),
            'jenis_anggaran' => DB::table('tb_status_anggaran')
                ->select('kode', 'nama')
                ->where(['status_aktif' => '1'])
                ->get(),
            'daftar_ttd' => DB::table('ms_ttd')
                ->select('nama', 'nip', 'jabatan', 'kd_skpd')
                ->whereIn('kode', ['PA', 'KPA'])
                ->orderBy('nama')
                ->get(),
        ];

        return view('bud.kartu_kendali.index')->with($data);
    }

    public function kegiatanKartuKendali(Request $request)
    {
        $kd_skpd = $request->kd_skpd;

        $jenis_anggaran = DB::table('trhrka')
            ->select('jns_ang')
            ->where(['kd_skpd' => $kd_skpd, 'status' => '1'])
            ->orderByDesc('tgl_dpa')
            ->first();

        $data = DB::table('trskpd as a')
            ->join('ms_sub_kegiatan as b', function ($join) {
                $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
            })
            ->select('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan')
            ->where(['a.kd_skpd' => $kd_skpd, 'jns_ang' => $jenis_anggaran->jns_ang])
            ->orderBy('a.kd_sub_kegiatan')
            ->get();

        return response()->json($data);
    }

    public function rekeningKartuKendali(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;

        $jenis_anggaran = DB::table('trhrka')
            ->select('jns_ang')
            ->where(['kd_skpd' => $kd_skpd, 'status' => '1'])
            ->orderByDesc('tgl_dpa')
            ->first();

        $data = DB::table('trdrka as a')
            ->join('ms_rek6 as b', function ($join) {
                $join->on('a.kd_rek6', '=', 'b.kd_rek6');
            })
            ->select('a.kd_rek6', 'b.nm_rek6')
            ->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.kd_skpd' => $kd_skpd, 'jns_ang' => $jenis_anggaran->jns_ang])
            ->orderBy('a.kd_rek6')
            ->get();

        return response()->json($data);
    }

    public function cetakKegiatanKartuKendali(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_rek = $request->kd_rek;
        $jns_ang = $request->jns_ang;
        $periode_awal = $request->periode_awal;
        $periode_akhir = $request->periode_akhir;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        $program = substr($kd_sub_kegiatan, 0, 7);
        $kegiatan = substr($kd_sub_kegiatan, 0, 12);

        $data = [
            'header' => DB::table('config_app')
                ->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')
                ->first(),
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'program' => DB::table('trskpd')
                ->select('nm_program', DB::raw("'$program' as kd_program"))
                ->where(['kd_program' => $program])
                ->first(),
            'kegiatan' => DB::table('trskpd')
                ->select('nm_kegiatan', DB::raw("'$kegiatan' as kd_kegiatan"))
                ->where(['kd_kegiatan' => $kegiatan])
                ->first(),
            'sub_kegiatan' => DB::table('trskpd')
                ->select('nm_sub_kegiatan', DB::raw("'$kd_sub_kegiatan' as kd_sub_kegiatan"))
                ->where(['kd_sub_kegiatan' => $kd_sub_kegiatan])
                ->first(),
            'periode_awal' => $periode_awal,
            'periode_akhir' => $periode_akhir,
            'rincian' =>  DB::select("exec kartu_kendali ?,?,?,?,?", array($jns_ang, $kd_skpd, $kd_sub_kegiatan, $periode_awal, $periode_akhir)),
            'jns_ang' => $jns_ang
        ];
        // dd($data['rincian']);
        $view =  view('bud.kartu_kendali.cetak_per_sub_kegiatan')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function cetakRekeningKartuKendali(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_rek = $request->kd_rek;
        $jns_ang = $request->jns_ang;
        $periode_awal = $request->periode_awal;
        $periode_akhir = $request->periode_akhir;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        $kegiatan = substr($kd_sub_kegiatan, 0, 12);

        $data = [
            'nilai_ang' => DB::table('trdrka as a')
                ->selectRaw("sum(nilai)nilai, (select sum(nilai) from trdrka where no_trdrka=a.no_trdrka and jns_ang=?) as nilai_ubah", [$jns_ang])
                ->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.kd_skpd' => $kd_skpd, 'a.kd_rek6' => $kd_rek])
                ->groupBy('no_trdrka')
                ->first(),
            'header' => DB::table('config_app')
                ->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')
                ->first(),
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->where(['kd_skpd' => $kd_skpd])
                ->first(),
            'kegiatan' => DB::table('trskpd')
                ->select('nm_kegiatan', DB::raw("'$kegiatan' as kd_kegiatan"))
                ->where(['kd_kegiatan' => $kegiatan])
                ->first(),
            'sub_kegiatan' => DB::table('trskpd')
                ->select('nm_sub_kegiatan', DB::raw("'$kd_sub_kegiatan' as kd_sub_kegiatan"))
                ->where(['kd_sub_kegiatan' => $kd_sub_kegiatan])
                ->first(),
            'rekening' => DB::table('ms_rek6')
                ->select('nm_rek6', 'kd_rek6')
                ->where(['kd_rek6' => $kd_rek])
                ->first(),
            'periode_awal' => $periode_awal,
            'periode_akhir' => $periode_akhir,
            'rincian' =>  DB::select("exec kartu_kendali_rek ?,?,?,?,?", array($kd_rek, $kd_skpd, $kd_sub_kegiatan, $periode_awal, $periode_akhir)),
            'jns_ang' => $jns_ang
        ];
        // dd($data['rincian']);
        $view = view('bud.kartu_kendali.cetak_per_rekening')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function registerCp(Request $request)
    {
        $skpd_global = Auth::user()->kd_skpd;
        $pilihan = $request->pilihan;
        $tgl1 = $request->tgl1;
        $tgl2 = $request->tgl2;
        $ttd = $request->ttd;
        $kd_skpd = $request->kd_skpd;
        $kd_unit = $request->kd_unit;
        $jenis_print = $request->jenis_print;

        if ($ttd) {
            $tanda_tangan = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd])->whereIn('kode', ['BUD', 'PA'])->first();
        } else {
            $tanda_tangan = null;
        }

        if ($pilihan == '1') {
            $register_cp = DB::select("SELECT a.kd_skpd,a.nm_skpd,isnull(b.total,0) total from ms_skpd a
                                            left join
                                            (SELECT a.kd_skpd,(select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd)nm_skpd,isnull(sum(cp),0) total
                                            from
                                            (SELECT no_kas ,no_sts,tgl_kas, kd_skpd , jns_trans, jns_cp, pot_khusus,keterangan,kd_sub_kegiatan,kd_rek6,
                                            SUM(isnull((case when rtrim(jns_cp) in  ('3','2','1') and (tgl_kas  BETWEEN ? AND ?) then z.nilai else 0 end),0)) AS cp
                                            from ( SELECT d.no_kas,d.no_sts , d.kd_skpd , d.jns_trans, d.jns_cp, pot_khusus,rupiah as nilai,d.tgl_sts,d.tgl_kas,keterangan,d.kd_sub_kegiatan,kd_rek6  from
                                            trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd where
                                            jns_trans in('5','1') AND pot_khusus in('0','1','2') ) z
                                            group by  no_kas ,no_sts,tgl_kas, kd_skpd , jns_trans, jns_cp, pot_khusus,z.keterangan,kd_sub_kegiatan,kd_rek6) a
                                            group by a.kd_skpd) b on a.kd_skpd=b.kd_skpd
                                            order by a.kd_skpd", [$tgl1, $tgl2]);
        } else if ($pilihan == '2') {
            $register_cp = DB::select("SELECT '1' as jenis,no_kas,tgl_kas,kd_skpd,keterangan as keterangan,'' kd_sub_kegiatan, ''kd_rek,nilai FROM(
						SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, nilai, jns_trans,jns_cp,pot_khusus
						FROM (
						SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,SUM(rupiah) as nilai
						FROM trhkasin_ppkd a
						INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan) a
						LEFT JOIN
						(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
						INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
						ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
						WHERE (tgl_kas  BETWEEN ? AND ?)
						AND LEFT(kd_skpd,17)=?

						UNION ALL

						SELECT '2' as jenis,no_kas,tgl_kas,kd_skpd,keterangan as keterangan,kd_sub_kegiatan,kd_rek,nilai FROM(
						SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, kd_sub_kegiatan,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
						FROM (
						SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,b.kd_sub_kegiatan,kd_rek6 as kd_rek, rupiah as nilai
						FROM trhkasin_ppkd a
						INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
						WHERE jns_trans IN ('5','1')) a
						LEFT JOIN
						(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
						INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
						ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
						WHERE (tgl_kas  BETWEEN ? AND ?)
						AND LEFT(kd_skpd,17)=?
						ORDER BY tgl_kas,no_kas,jenis", [$tgl1, $tgl2, $kd_skpd, $tgl1, $tgl2, $kd_skpd]);
        } else if ($pilihan == '3') {
            $register_cp = DB::select("SELECT '1' as jenis,no_kas,tgl_kas,kd_skpd,keterangan as keterangan,'' kd_sub_kegiatan, ''kd_rek,nilai FROM(
						SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, nilai, jns_trans,jns_cp,pot_khusus
						FROM (
						SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,SUM(rupiah) as nilai
						FROM trhkasin_ppkd a
						INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan) a
						LEFT JOIN
						(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
						INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
						ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
						WHERE (tgl_kas  BETWEEN ? AND ?)
						AND LEFT(kd_skpd,22)=?

						UNION ALL

						SELECT '2' as jenis,no_kas,tgl_kas,kd_skpd,keterangan as keterangan,kd_sub_kegiatan,kd_rek,nilai FROM(
						SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, kd_sub_kegiatan,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
						FROM (
						SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,b.kd_sub_kegiatan,kd_rek6 as kd_rek, rupiah as nilai
						FROM trhkasin_ppkd a
						INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
						WHERE jns_trans IN ('5','1')) a
						LEFT JOIN
						(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
						INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
						ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
						WHERE (tgl_kas  BETWEEN ? AND ?)
						AND LEFT(kd_skpd,22)=?
						ORDER BY tgl_kas,no_kas,jenis", [$tgl1, $tgl2, $kd_unit, $tgl1, $tgl2, $kd_unit]);
        } else if ($pilihan == '4') {
            $register_cp = DB::select("SELECT '1' as jenis,no_kas,tgl_kas,kd_skpd,keterangan as keterangan,'' kd_sub_kegiatan, ''kd_rek,nilai FROM(
						SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, nilai, jns_trans,jns_cp,pot_khusus
						FROM (
						SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,SUM(rupiah) as nilai
						FROM trhkasin_ppkd a
						INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan) a
						LEFT JOIN
						(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
						INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
						ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
						WHERE (tgl_kas  BETWEEN ? AND ?)

						UNION ALL

						SELECT '2' as jenis,no_kas,tgl_kas,kd_skpd,keterangan as keterangan,kd_sub_kegiatan,kd_rek,nilai FROM(
						SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, kd_sub_kegiatan,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
						FROM (
						SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,b.kd_sub_kegiatan,kd_rek6 as kd_rek, rupiah as nilai
						FROM trhkasin_ppkd a
						INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
						WHERE jns_trans IN ('5','1')) a
						LEFT JOIN
						(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
						INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
						ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
						WHERE (tgl_kas  BETWEEN ? AND ?)
						ORDER BY tgl_kas,no_kas,jenis", [$tgl1, $tgl2, $tgl1, $tgl2]);
        }

        if ($pilihan == '1') {
            $register_lalu = 0;
        } elseif ($pilihan == '2') {
            $register_lalu = collect(DB::select("SELECT SUM(nilai) as nilai_lalu FROM(
						SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, kd_sub_kegiatan,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
						FROM (
						SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,b.kd_sub_kegiatan,kd_rek6 as kd_rek, rupiah as nilai
						FROM trhkasin_ppkd a
						INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
						WHERE jns_trans IN ('5','1')) a
						LEFT JOIN
						(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
						INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
						ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
						WHERE (tgl_kas <?) AND LEFT(kd_skpd,17)=?", [$tgl1, $kd_skpd]))->first();
        } elseif ($pilihan == '3') {
            $register_lalu = collect(DB::select("SELECT SUM(nilai) as nilai_lalu FROM(
						SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, kd_sub_kegiatan,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
						FROM (
						SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,b.kd_sub_kegiatan,kd_rek6 as kd_rek, rupiah as nilai
						FROM trhkasin_ppkd a
						INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
						WHERE jns_trans IN ('5','1')) a
						LEFT JOIN
						(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
						INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
						ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
						WHERE (tgl_kas <?) AND LEFT(kd_skpd,22)=?", [$tgl1, $kd_unit]))->first();
        } elseif ($pilihan == '4') {
            $register_lalu = collect(DB::select("SELECT SUM(nilai) as nilai_lalu FROM(
						SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, kd_sub_kegiatan,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
						FROM (
						SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,b.kd_sub_kegiatan,kd_rek6 as kd_rek, rupiah as nilai
						FROM trhkasin_ppkd a
						INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
						WHERE jns_trans IN ('5','1')) a
						LEFT JOIN
						(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
						INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
						ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
						WHERE (tgl_kas <?)", [$tgl1]))->first();
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'tanda_tangan' => $tanda_tangan,
            'tanggal1' => $tgl1,
            'tanggal2' => $tgl2,
            'pilihan' => $pilihan,
            'data_register' => $register_cp,
            'total_lalu' => $register_lalu,
        ];

        $judul = 'REGISTER CP';

        $view = view('bud.laporan_bendahara.cetak.register_cp')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }

    public function registerCpRinci(Request $request)
    {
        $skpd_global = Auth::user()->kd_skpd;
        $pilihan = $request->pilihan;
        $tgl1 = $request->tgl1;
        $tgl2 = $request->tgl2;
        $ttd = $request->ttd;
        $kd_skpd = $request->kd_skpd;
        $kd_unit = $request->kd_unit;
        $jenis_print = $request->jenis_print;

        if ($ttd) {
            $tanda_tangan = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd])->whereIn('kode', ['BUD', 'PA'])->first();
        } else {
            $tanda_tangan = null;
        }

        if ($pilihan == '1') {
            $register_cp = DB::select("SELECT a.kd_skpd,a.nm_skpd
                        ,ISNULL(hkpg,0) hkpg
                        ,ISNULL(pot_lain,0) pot_lain
                        ,ISNULL(cp,0) cp
                        ,ISNULL(ls_peg,0) ls_peg
                        ,ISNULL(ls_brng,0) ls_brng
                        ,ISNULL(ls_modal,0) ls_modal
                        ,ISNULL(phl,0) ls_phl
                        ,ISNULL(gu,0) gu
                        ,ISNULL(up_gu_peg,0) up_gu_peg
                        ,ISNULL(up_gu_brng,0) up_gu_brng
                        ,ISNULL(up_gu_modal,0) up_gu_modal
                        ,ISNULL(total,0) total
                        FROM ms_skpd a LEFT JOIN
                    (SELECT kd_skpd
                    ,SUM(CASE  WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '1' THEN nilai ELSE 0 END) AS hkpg
                    ,SUM(CASE  WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '2' THEN nilai ELSE 0 END) AS pot_lain
                    ,SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus NOT IN ('1','2') THEN   nilai ELSE 0 END) AS cp
                    ,SUM(CASE  WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) IN ('5101') THEN nilai ELSE 0 END) AS ls_peg
                    ,SUM(  CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) = '5102' THEN    nilai ELSE 0 END) AS ls_brng
                    ,SUM(   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) not in ('5201','5202','5203','5204','5205','5206','5102','5101') THEN    nilai ELSE 0 END) AS phl
                    ,SUM(  CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai ELSE 0 END) AS ls_modal
                    ,sum(CASE   WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) = '1101' THEN    nilai ELSE 0 END)as gu
                    ,SUM (  CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5101') THEN nilai    ELSE 0 END) AS up_gu_peg
                    ,SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5102') THEN nilai    ELSE 0 END) AS up_gu_brng
                    ,SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai    ELSE 0 END) AS up_gu_modal
                    ,SUM (nilai) AS total
                    FROM
                    (
                    SELECT a.no_kas,a.tgl_kas, a.kd_skpd,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
                    FROM (
                    SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, LEFT(kd_rek6,4) as kd_rek, SUM(rupiah) as nilai FROM trhkasin_ppkd a
                    INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
                    WHERE jns_trans IN ('5','1')
                    GROUP BY a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd,LEFT(kd_rek6,4)) a
                    LEFT JOIN
                    (SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
                    INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
                    WHERE jns_trans IN ('5','1')
                    GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
                    ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
                    WHERE (tgl_kas BETWEEN ? AND ?)
                    GROUP BY a.kd_skpd) b
                    ON a.kd_skpd=b.kd_skpd
                    order by a.kd_skpd", [$tgl1, $tgl2]);
        } else if ($pilihan == '2') {
            $register_cp = DB::select("SELECT '1' as jenis,no_kas,tgl_kas,kd_skpd,keterangan as keterangan,'' kd_sub_kegiatan, ''kd_rek,
						SUM ( CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '1' THEN nilai ELSE 0 END) AS hkpg,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '2' THEN nilai ELSE 0 END) AS pot_lain,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus NOT IN ('1','2') THEN   nilai ELSE 0 END) AS cp,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) IN ('5101') THEN nilai ELSE 0 END) AS ls_peg,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) = '5102' THEN    nilai ELSE 0 END) AS ls_brng,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai ELSE 0 END) AS ls_modal,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5105') THEN nilai ELSE 0 END) AS ls_phl,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) = '1101' THEN    nilai ELSE 0 END) AS up_gu,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5101') THEN nilai    ELSE 0 END) AS tu_peg,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5102') THEN nilai    ELSE 0 END) AS tu_brng,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai    ELSE 0 END) AS tu_modal,
                        SUM (nilai) AS total
							 FROM(

							SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, nilai, jns_trans,jns_cp,pot_khusus,kd_rek
							FROM (
							SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,kd_rek6 as kd_rek, SUM(rupiah) as nilai
							FROM trhkasin_ppkd a
							INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
							WHERE jns_trans IN ('5','1')
							GROUP BY a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd,keterangan,kd_rek6) a
							LEFT JOIN
							(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
							INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
							WHERE jns_trans IN ('5','1')
							GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
							ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
							WHERE (tgl_kas  BETWEEN ? AND ?)
							AND left(kd_skpd,17)=?
							GROUP BY no_kas,tgl_kas,kd_skpd,keterangan

							UNION ALL

							SELECT '2' as jenis,no_kas,tgl_kas,kd_skpd,keterangan as keterangan,kd_sub_kegiatan,kd_rek,
						SUM (    CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '1' THEN nilai ELSE 0 END) AS hkpg,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '2' THEN nilai ELSE 0 END) AS pot_lain,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus NOT IN ('1','2') THEN   nilai ELSE 0 END) AS cp,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) IN ('5101') THEN nilai ELSE 0 END) AS ls_peg,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) = '5102' THEN    nilai ELSE 0 END) AS ls_brng,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai ELSE 0 END) AS ls_modal,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5105') THEN nilai ELSE 0 END) AS ls_phl,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) = '1101' THEN    nilai ELSE 0 END) AS gu,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5101') THEN nilai    ELSE 0 END) AS up_gu_peg,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5102') THEN nilai    ELSE 0 END) AS up_gu_brng,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai    ELSE 0 END) AS up_gu_modal,
                        SUM (nilai) AS total
							 FROM(
							SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, kd_sub_kegiatan,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
							FROM (
							SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,b.kd_sub_kegiatan,kd_rek6 as kd_rek, rupiah as nilai
							FROM trhkasin_ppkd a
							INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
							WHERE jns_trans IN ('5','1')) a
							LEFT JOIN
							(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
							INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
							WHERE jns_trans IN ('5','1')
							GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
							ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
							WHERE (tgl_kas  BETWEEN ? AND ?)
							AND left(kd_skpd,17)=?
							GROUP BY no_kas,tgl_kas,kd_skpd,keterangan,kd_sub_kegiatan,kd_rek
							ORDER BY tgl_kas,no_kas,jenis", [$tgl1, $tgl2, $kd_skpd, $tgl1, $tgl2, $kd_skpd]);
        } else if ($pilihan == '3') {
            $register_cp = DB::select("SELECT '1' as jenis,no_kas,tgl_kas,kd_skpd,keterangan as keterangan,'' kd_sub_kegiatan, ''kd_rek,
						SUM ( CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '1' THEN nilai ELSE 0 END) AS hkpg,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '2' THEN nilai ELSE 0 END) AS pot_lain,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus NOT IN ('1','2') THEN   nilai ELSE 0 END) AS cp,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) IN ('5101') THEN nilai ELSE 0 END) AS ls_peg,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) = '5102' THEN    nilai ELSE 0 END) AS ls_brng,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai ELSE 0 END) AS ls_modal,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5105') THEN nilai ELSE 0 END) AS ls_phl,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) = '1101' THEN    nilai ELSE 0 END) AS up_gu,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5101') THEN nilai    ELSE 0 END) AS tu_peg,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5102') THEN nilai    ELSE 0 END) AS tu_brng,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai    ELSE 0 END) AS tu_modal,
                        SUM (nilai) AS total
							 FROM(

							SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, nilai, jns_trans,jns_cp,pot_khusus,kd_rek
							FROM (
							SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,kd_rek6 as kd_rek, SUM(rupiah) as nilai
							FROM trhkasin_ppkd a
							INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
							WHERE jns_trans IN ('5','1')
							GROUP BY a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd,keterangan,kd_rek6) a
							LEFT JOIN
							(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
							INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
							WHERE jns_trans IN ('5','1')
							GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
							ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
							WHERE (tgl_kas  BETWEEN ? AND ?)
							AND left(kd_skpd,22)=?
							GROUP BY no_kas,tgl_kas,kd_skpd,keterangan

							UNION ALL

							SELECT '2' as jenis,no_kas,tgl_kas,kd_skpd,keterangan as keterangan,kd_sub_kegiatan,kd_rek,
						SUM (    CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '1' THEN nilai ELSE 0 END) AS hkpg,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '2' THEN nilai ELSE 0 END) AS pot_lain,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus NOT IN ('1','2') THEN   nilai ELSE 0 END) AS cp,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) IN ('5101') THEN nilai ELSE 0 END) AS ls_peg,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) = '5102' THEN    nilai ELSE 0 END) AS ls_brng,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai ELSE 0 END) AS ls_modal,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5105') THEN nilai ELSE 0 END) AS ls_phl,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) = '1101' THEN    nilai ELSE 0 END) AS gu,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5101') THEN nilai    ELSE 0 END) AS up_gu_peg,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5102') THEN nilai    ELSE 0 END) AS up_gu_brng,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai    ELSE 0 END) AS up_gu_modal,
                        SUM (nilai) AS total
							 FROM(
							SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, kd_sub_kegiatan,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
							FROM (
							SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,b.kd_sub_kegiatan,kd_rek6 as kd_rek, rupiah as nilai
							FROM trhkasin_ppkd a
							INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
							WHERE jns_trans IN ('5','1')) a
							LEFT JOIN
							(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
							INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
							WHERE jns_trans IN ('5','1')
							GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
							ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
							WHERE (tgl_kas  BETWEEN ? AND ?)
							AND left(kd_skpd,22)=?
							GROUP BY no_kas,tgl_kas,kd_skpd,keterangan,kd_sub_kegiatan,kd_rek
							ORDER BY tgl_kas,no_kas,jenis", [$tgl1, $tgl2, $kd_unit, $tgl1, $tgl2, $kd_unit]);
        } else if ($pilihan == '4') {
            $register_cp = DB::select("SELECT '1' as jenis,no_kas,tgl_kas,kd_skpd,keterangan as keterangan,'' kd_sub_kegiatan, ''kd_rek,
						SUM ( CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '1' THEN nilai ELSE 0 END) AS hkpg,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '2' THEN nilai ELSE 0 END) AS pot_lain,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus NOT IN ('1','2') THEN   nilai ELSE 0 END) AS cp,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) IN ('5101') THEN nilai ELSE 0 END) AS ls_peg,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) = '5102' THEN    nilai ELSE 0 END) AS ls_brng,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai ELSE 0 END) AS ls_modal,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5105') THEN nilai ELSE 0 END) AS ls_phl,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) = '1101' THEN    nilai ELSE 0 END) AS up_gu,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5101') THEN nilai    ELSE 0 END) AS tu_peg,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5102') THEN nilai    ELSE 0 END) AS tu_brng,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai    ELSE 0 END) AS tu_modal,
                        SUM (nilai) AS total
							 FROM(

							SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, nilai, jns_trans,jns_cp,pot_khusus,kd_rek
							FROM (
							SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,kd_rek6 as kd_rek, SUM(rupiah) as nilai
							FROM trhkasin_ppkd a
							INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
							WHERE jns_trans IN ('5','1')
							GROUP BY a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd,keterangan,kd_rek6) a
							LEFT JOIN
							(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
							INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
							WHERE jns_trans IN ('5','1')
							GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
							ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
							WHERE (tgl_kas  BETWEEN ? AND ?)
							GROUP BY no_kas,tgl_kas,kd_skpd,keterangan

							UNION ALL

							SELECT '2' as jenis,no_kas,tgl_kas,kd_skpd,keterangan as keterangan,kd_sub_kegiatan,kd_rek,
						SUM (    CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '1' THEN nilai ELSE 0 END) AS hkpg,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus = '2' THEN nilai ELSE 0 END) AS pot_lain,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '1' AND pot_khusus NOT IN ('1','2') THEN   nilai ELSE 0 END) AS cp,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) IN ('5101') THEN nilai ELSE 0 END) AS ls_peg,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) = '5102' THEN    nilai ELSE 0 END) AS ls_brng,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai ELSE 0 END) AS ls_modal,
                        SUM (   CASE    WHEN jns_trans IN ('1','5') AND jns_cp = '2' AND LEFT(kd_rek,4) in ('5105') THEN nilai ELSE 0 END) AS ls_phl,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) = '1101' THEN    nilai ELSE 0 END) AS gu,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5101') THEN nilai    ELSE 0 END) AS up_gu_peg,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) IN ('5102') THEN nilai    ELSE 0 END) AS up_gu_brng,
                        SUM (   CASE    WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(kd_rek,4) in ('5201','5202','5203','5204','5205','5206') THEN  nilai    ELSE 0 END) AS up_gu_modal,
                        SUM (nilai) AS total
							 FROM(
							SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, kd_sub_kegiatan,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
							FROM (
							SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,b.kd_sub_kegiatan,kd_rek6 as kd_rek, rupiah as nilai
							FROM trhkasin_ppkd a
							INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
							WHERE jns_trans IN ('5','1')) a
							LEFT JOIN
							(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
							INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
							WHERE jns_trans IN ('5','1')
							GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
							ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
							WHERE (tgl_kas  BETWEEN ? AND ?)
							GROUP BY no_kas,tgl_kas,kd_skpd,keterangan,kd_sub_kegiatan,kd_rek
							ORDER BY tgl_kas,no_kas,jenis", [$tgl1, $tgl2, $tgl1, $tgl2]);
        }

        if ($pilihan == '1') {
            $register_lalu = 0;
        } elseif ($pilihan == '2') {
            $register_lalu = collect(DB::select("SELECT SUM(nilai) as nilai_lalu FROM(
						SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, kd_sub_kegiatan,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
						FROM (
						SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,b.kd_sub_kegiatan,kd_rek6 as kd_rek, rupiah as nilai
						FROM trhkasin_ppkd a
						INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
						WHERE jns_trans IN ('5','1')) a
						LEFT JOIN
						(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
						INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
						ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
						WHERE (tgl_kas <?) AND LEFT(kd_skpd,17)=?", [$tgl1, $kd_skpd]))->first();
        } elseif ($pilihan == '3') {
            $register_lalu = collect(DB::select("SELECT SUM(nilai) as nilai_lalu FROM(
						SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, kd_sub_kegiatan,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
						FROM (
						SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,b.kd_sub_kegiatan,kd_rek6 as kd_rek, rupiah as nilai
						FROM trhkasin_ppkd a
						INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
						WHERE jns_trans IN ('5','1')) a
						LEFT JOIN
						(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
						INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
						ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
						WHERE (tgl_kas <?) AND LEFT(kd_skpd,22)=?", [$tgl1, $kd_unit]))->first();
        } elseif ($pilihan == '4') {
            $register_lalu = collect(DB::select("SELECT SUM(nilai) as nilai_lalu FROM(
						SELECT a.no_kas,a.tgl_kas, a.kd_skpd,keterangan, kd_sub_kegiatan,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
						FROM (
						SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, keterangan,b.kd_sub_kegiatan,kd_rek6 as kd_rek, rupiah as nilai
						FROM trhkasin_ppkd a
						INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
						WHERE jns_trans IN ('5','1')) a
						LEFT JOIN
						(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a
						INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						WHERE jns_trans IN ('5','1')
						GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
						ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
						WHERE (tgl_kas <?)", [$tgl1]))->first();
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'tanda_tangan' => $tanda_tangan,
            'tanggal1' => $tgl1,
            'tanggal2' => $tgl2,
            'pilihan' => $pilihan,
            'data_register' => $register_cp,
            'total_lalu' => $register_lalu,
            'skpd' => $kd_skpd,
            'unit' => $kd_unit,
        ];

        $judul = 'REGISTER CP RINCI';

        $view = view('bud.laporan_bendahara.cetak.register_cp_rinci')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }

    public function potonganPajak(Request $request)
    {
        $skpd_global = Auth::user()->kd_skpd;
        $pilihan = $request->pilihan;
        $tgl1 = $request->tgl1;
        $tgl2 = $request->tgl2;
        $ttd = $request->ttd;
        $sp2d = $request->sp2d;
        $belanja = $request->belanja;
        $kd_skpd = $request->kd_skpd;
        $kd_unit = $request->kd_unit;
        $jenis_print = $request->jenis_print;

        if ($ttd) {
            $tanda_tangan = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd])->whereIn('kode', ['BUD', 'PA'])->first();
        } else {
            $tanda_tangan = null;
        }

        if ($sp2d == '0') {
            if ($pilihan == '1') {
                $potongan_pajak = DB::select("SELECT a.kd_skpd, a.nm_skpd, ISNULL(a.nilai,0) as nilai,
					ISNULL(iwp,0) iwp,
					ISNULL(taperum,0) taperum,
					ISNULL(hkpg,0) hkpg,
					ISNULL(pph,0) pph,
					iwp+taperum+hkpg+pph as jumlah_potongan
					FROM
					(SELECT a.kd_skpd, a.nm_skpd, SUM(d.nilai) as nilai FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trhspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
					INNER JOIN trdspp d ON c.no_spp = d.no_spp AND c.kd_skpd = d.kd_skpd
					WHERE (a.jns_spp='4' AND a.jenis_beban='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.status_bud='1'
					GROUP BY a.kd_skpd, a.nm_skpd
					)a LEFT JOIN
					(SELECT a.kd_skpd, a.nm_skpd
					,SUM(CASE WHEN kd_rek6 in ('210108010001') THEN c.nilai ELSE 0 END) AS iwp
					,SUM(CASE WHEN kd_rek6 ='2110501' THEN c.nilai ELSE 0 END) AS taperum
					,SUM(CASE WHEN kd_rek6 ='2110801' THEN c.nilai ELSE 0 END) AS hkpg
					,SUM(CASE WHEN kd_rek6 ='210105010001' THEN c.nilai ELSE 0 END) AS pph
					FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trspmpot c ON b.no_spm = c.no_spm AND b.kd_skpd = c.kd_skpd
					WHERE (a.jns_spp='4' AND a.jenis_beban='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.status_bud='1'
					GROUP BY a.kd_skpd, a.nm_skpd
					) b ON a.kd_skpd=b.kd_skpd", [$tgl1, $tgl2, $tgl1, $tgl2]);
            } else if ($pilihan == '2') {
                $potongan_pajak = DB::select("SELECT a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d, ISNULL(a.nilai,0) as nilai,
					ISNULL(iwp,0) iwp,
					ISNULL(taperum,0) taperum,
					ISNULL(hkpg,0) hkpg,
					ISNULL(pph,0) pph,
					iwp+taperum+hkpg+pph as jumlah_potongan
					FROM
					(SELECT a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d, SUM(d.nilai) as nilai FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trhspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
					INNER JOIN trdspp d ON c.no_spp = d.no_spp AND c.kd_skpd = d.kd_skpd
					WHERE (a.jns_spp='4' AND a.jenis_beban='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND LEFT(a.kd_skpd,17)=? AND a.status_bud='1'
					GROUP BY a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d
					)a LEFT JOIN
					(SELECT a.kd_skpd, a.nm_skpd
					,SUM(CASE WHEN kd_rek6 in ('210108010001') THEN c.nilai ELSE 0 END) AS iwp
					,SUM(CASE WHEN kd_rek6 ='2110501' THEN c.nilai ELSE 0 END) AS taperum
					,SUM(CASE WHEN kd_rek6 ='2110801' THEN c.nilai ELSE 0 END) AS hkpg
					,SUM(CASE WHEN kd_rek6 ='210105010001' THEN c.nilai ELSE 0 END) AS pph
					FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trspmpot c ON b.no_spm = c.no_spm AND b.kd_skpd = c.kd_skpd
					WHERE (a.jns_spp='4' AND a.jenis_beban='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND LEFT(a.kd_skpd,17)=? AND a.status_bud='1'
					GROUP BY a.kd_skpd, a.nm_skpd
					) b ON a.kd_skpd=b.kd_skpd", [$tgl1, $tgl2, $kd_skpd, $tgl1, $tgl2, $kd_skpd]);
            } else if ($pilihan == '3') {
                $potongan_pajak = DB::select("SELECT a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d, ISNULL(a.nilai,0) as nilai,
					ISNULL(iwp,0) iwp,
					ISNULL(taperum,0) taperum,
					ISNULL(hkpg,0) hkpg,
					ISNULL(pph,0) pph,
					iwp+taperum+hkpg+pph as jumlah_potongan
					FROM
					(SELECT a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d, SUM(d.nilai) as nilai FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trhspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
					INNER JOIN trdspp d ON c.no_spp = d.no_spp AND c.kd_skpd = d.kd_skpd
					WHERE (a.jns_spp='4' AND a.jenis_beban='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.kd_skpd=? AND a.status_bud='1'
					GROUP BY a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d
					)a LEFT JOIN
					(SELECT a.kd_skpd, a.nm_skpd
					,SUM(CASE WHEN kd_rek6 in ('210108010001') THEN c.nilai ELSE 0 END) AS iwp
					,SUM(CASE WHEN kd_rek6 ='2110501' THEN c.nilai ELSE 0 END) AS taperum
					,SUM(CASE WHEN kd_rek6 ='2110801' THEN c.nilai ELSE 0 END) AS hkpg
					,SUM(CASE WHEN kd_rek6 ='210105010001' THEN c.nilai ELSE 0 END) AS pph
					FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trspmpot c ON b.no_spm = c.no_spm AND b.kd_skpd = c.kd_skpd
					WHERE (a.jns_spp='4' AND a.jenis_beban='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.kd_skpd=? AND a.status_bud='1'
					GROUP BY a.kd_skpd, a.nm_skpd
					) b ON a.kd_skpd=b.kd_skpd", [$tgl1, $tgl2, $kd_unit, $tgl1, $tgl2, $kd_unit]);
            } else if ($pilihan == '4') {
                $potongan_pajak = DB::select("SELECT a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d, ISNULL(a.nilai,0) as nilai,
					ISNULL(iwp,0) iwp,
					ISNULL(taperum,0) taperum,
					ISNULL(hkpg,0) hkpg,
					ISNULL(pph,0) pph,
					iwp+taperum+hkpg+pph as jumlah_potongan
					FROM
					(SELECT a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d, SUM(d.nilai) as nilai FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trhspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
					INNER JOIN trdspp d ON c.no_spp = d.no_spp AND c.kd_skpd = d.kd_skpd
					WHERE (a.jns_spp='4' AND a.jenis_beban='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.status_bud='1'
					GROUP BY a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d
					)a LEFT JOIN
					(SELECT a.kd_skpd, a.nm_skpd, a.no_sp2d
					,SUM(CASE WHEN kd_rek6 in ('210108010001') THEN c.nilai ELSE 0 END) AS iwp
					,SUM(CASE WHEN kd_rek6 ='2110501' THEN c.nilai ELSE 0 END) AS taperum
					,SUM(CASE WHEN kd_rek6 ='2110801' THEN c.nilai ELSE 0 END) AS hkpg
					,SUM(CASE WHEN kd_rek6 ='210105010001' THEN c.nilai ELSE 0 END) AS pph
					FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trspmpot c ON b.no_spm = c.no_spm AND b.kd_skpd = c.kd_skpd
					WHERE (a.jns_spp='4' AND a.jenis_beban='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.status_bud='1'
					GROUP BY a.kd_skpd, a.nm_skpd, a.no_sp2d
					) b ON a.kd_skpd=b.kd_skpd
					ORDER BY cast(no_kas_bud as int)", [$tgl1, $tgl2, $tgl1, $tgl2]);
            }
        } else {
            if ($pilihan == '1') {
                $potongan_pajak = DB::select("SELECT a.kd_skpd, a.nm_skpd, ISNULL(a.nilai,0) as nilai,
					ISNULL(ppn,0) ppn,
					ISNULL(pph21,0) pph21,
					ISNULL(pph22,0) pph22,
					ISNULL(pph23,0) pph23,
					ISNULL(psl4_a2,0) psl4_a2,
					ISNULL(iwppnpn,0) iwppnpn,
					ISNULL(pot_lain,0) pot_lain,
					ppn+pph21+pph22+pph23+psl4_a2+iwppnpn+pot_lain as jumlah_potongan
					FROM
					(SELECT a.kd_skpd, a.nm_skpd, SUM(d.nilai) as nilai FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trhspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
					INNER JOIN trdspp d ON c.no_spp = d.no_spp AND c.kd_skpd = d.kd_skpd
					WHERE (a.jns_spp!='4' AND a.jenis_beban!='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.status_bud='1'
					GROUP BY a.kd_skpd, a.nm_skpd
					)a LEFT JOIN
					(SELECT a.kd_skpd, a.nm_skpd
					,SUM(CASE WHEN kd_rek6 ='2130301' THEN c.nilai ELSE 0 END) AS ppn
					,SUM(CASE WHEN kd_rek6 ='210105010001' THEN c.nilai ELSE 0 END) AS pph21
					,SUM(CASE WHEN kd_rek6 ='210105020001' THEN c.nilai ELSE 0 END) AS pph22
					,SUM(CASE WHEN kd_rek6 ='210105030001' THEN c.nilai ELSE 0 END) AS pph23
					,SUM(CASE WHEN kd_rek6 ='2130501' THEN c.nilai ELSE 0 END) AS psl4_a2
					,SUM(CASE WHEN map_pot ='21010201000105' THEN c.nilai ELSE 0 END) AS iwppnpn
					,SUM(CASE WHEN kd_rek6 not in ('2130301','210105010001','210105020001','210105030001','2130501','210102010001d') THEN c.nilai ELSE 0 END) AS pot_lain
					FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trspmpot c ON b.no_spm = c.no_spm AND b.kd_skpd = c.kd_skpd
					WHERE (a.jns_spp!='4' AND a.jenis_beban!='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.status_bud='1'
					GROUP BY a.kd_skpd, a.nm_skpd
					) b ON a.kd_skpd=b.kd_skpd", [$tgl1, $tgl2, $tgl1, $tgl2]);
            } else if ($pilihan == '2') {
                $potongan_pajak = DB::select("SELECT a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d,
				    ISNULL(a.nilai,0) as nilai,
					ISNULL(ppn,0) ppn,
					ISNULL(pph21,0) pph21,
					ISNULL(pph22,0) pph22,
					ISNULL(pph23,0) pph23,
					ISNULL(psl4_a2,0) psl4_a2,
					ISNULL(iwppnpn,0) iwppnpn,
					ISNULL(pot_lain,0) pot_lain,
					ppn+pph21+pph22+pph23+psl4_a2+iwppnpn+pot_lain as jumlah_potongan
					FROM
					(SELECT a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d, SUM(d.nilai) as nilai FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trhspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
					INNER JOIN trdspp d ON c.no_spp = d.no_spp AND c.kd_skpd = d.kd_skpd
					WHERE (a.jns_spp!='4' AND a.jenis_beban!='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.status_bud='1' AND LEFT(a.kd_skpd,17)=?
					GROUP BY a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d
					)a LEFT JOIN
					(SELECT a.kd_skpd, a.nm_skpd, a.no_sp2d
					,SUM(CASE WHEN kd_rek6 ='2130301' THEN c.nilai ELSE 0 END) AS ppn
					,SUM(CASE WHEN kd_rek6 ='210105010001' THEN c.nilai ELSE 0 END) AS pph21
					,SUM(CASE WHEN kd_rek6 ='210105020001' THEN c.nilai ELSE 0 END) AS pph22
					,SUM(CASE WHEN kd_rek6 ='210105030001' THEN c.nilai ELSE 0 END) AS pph23
					,SUM(CASE WHEN kd_rek6 ='2130501' THEN c.nilai ELSE 0 END) AS psl4_a2
					,SUM(CASE WHEN map_pot ='21010201000105' THEN c.nilai ELSE 0 END) AS iwppnpn
					,SUM(CASE WHEN kd_rek6 not in ('2130301','210105010001','210105020001','210105030001','2130501','210102010001d') THEN c.nilai ELSE 0 END) AS pot_lain
					FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trspmpot c ON b.no_spm = c.no_spm AND b.kd_skpd = c.kd_skpd
					WHERE (a.jns_spp!='4' AND a.jenis_beban!='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.status_bud='1' AND LEFT(a.kd_skpd,17)=?
					GROUP BY a.kd_skpd, a.nm_skpd,a.no_sp2d
					) b ON a.kd_skpd=b.kd_skpd AND a.no_sp2d=b.no_sp2d
					ORDER BY cast(no_kas_bud as int)", [$tgl1, $tgl2, $kd_skpd, $tgl1, $tgl2, $kd_skpd]);
            } else if ($pilihan == '3') {
                $potongan_pajak = DB::select("SELECT a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d,
				    ISNULL(a.nilai,0) as nilai,
					ISNULL(ppn,0) ppn,
					ISNULL(pph21,0) pph21,
					ISNULL(pph22,0) pph22,
					ISNULL(pph23,0) pph23,
					ISNULL(psl4_a2,0) psl4_a2,
					ISNULL(iwppnpn,0) iwppnpn,
					ISNULL(pot_lain,0) pot_lain,
					ppn+pph21+pph22+pph23+psl4_a2+iwppnpn+pot_lain as jumlah_potongan
					FROM
					(SELECT a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d, SUM(d.nilai) as nilai FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trhspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
					INNER JOIN trdspp d ON c.no_spp = d.no_spp AND c.kd_skpd = d.kd_skpd
					WHERE (a.jns_spp!='4' AND a.jenis_beban!='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.status_bud='1' AND a.kd_skpd=?
					GROUP BY a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d
					)a LEFT JOIN
					(SELECT a.kd_skpd, a.nm_skpd, a.no_sp2d
					,SUM(CASE WHEN kd_rek6 ='2130301' THEN c.nilai ELSE 0 END) AS ppn
					,SUM(CASE WHEN kd_rek6 ='210105010001' THEN c.nilai ELSE 0 END) AS pph21
					,SUM(CASE WHEN kd_rek6 ='210105020001' THEN c.nilai ELSE 0 END) AS pph22
					,SUM(CASE WHEN kd_rek6 ='210105030001' THEN c.nilai ELSE 0 END) AS pph23
					,SUM(CASE WHEN kd_rek6 ='2130501' THEN c.nilai ELSE 0 END) AS psl4_a2
					,SUM(CASE WHEN map_pot ='21010201000105' THEN c.nilai ELSE 0 END) AS iwppnpn
					,SUM(CASE WHEN kd_rek6 not in ('2130301','210105010001','210105020001','210105030001','2130501','210102010001d') THEN c.nilai ELSE 0 END) AS pot_lain
					FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trspmpot c ON b.no_spm = c.no_spm AND b.kd_skpd = c.kd_skpd
					WHERE (a.jns_spp!='4' AND a.jenis_beban!='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.status_bud='1' AND a.kd_skpd=?
					GROUP BY a.kd_skpd, a.nm_skpd,a.no_sp2d
					) b ON a.kd_skpd=b.kd_skpd AND a.no_sp2d=b.no_sp2d
					ORDER BY cast(no_kas_bud as int)", [$tgl1, $tgl2, $kd_unit, $tgl1, $tgl2, $kd_unit]);
            } else if ($pilihan == '4') {
                $potongan_pajak = DB::select("SELECT a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d,
				    ISNULL(a.nilai,0) as nilai,
					ISNULL(ppn,0) ppn,
					ISNULL(pph21,0) pph21,
					ISNULL(pph22,0) pph22,
					ISNULL(pph23,0) pph23,
					ISNULL(psl4_a2,0) psl4_a2,
					ISNULL(iwppnpn,0) iwppnpn,
					ISNULL(pot_lain,0) pot_lain,
					ppn+pph21+pph22+pph23+psl4_a2+iwppnpn+pot_lain as jumlah_potongan
					FROM
					(SELECT a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d, SUM(d.nilai) as nilai FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trhspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
					INNER JOIN trdspp d ON c.no_spp = d.no_spp AND c.kd_skpd = d.kd_skpd
					WHERE (a.jns_spp!='4' AND a.jenis_beban!='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.status_bud='1'
					GROUP BY a.kd_skpd, a.nm_skpd, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d
					)a LEFT JOIN
					(SELECT a.kd_skpd, a.nm_skpd, a.no_sp2d
					,SUM(CASE WHEN kd_rek6 ='2130301' THEN c.nilai ELSE 0 END) AS ppn
					,SUM(CASE WHEN kd_rek6 ='210105010001' THEN c.nilai ELSE 0 END) AS pph21
					,SUM(CASE WHEN kd_rek6 ='210105020001' THEN c.nilai ELSE 0 END) AS pph22
					,SUM(CASE WHEN kd_rek6 ='210105030001' THEN c.nilai ELSE 0 END) AS pph23
					,SUM(CASE WHEN kd_rek6 ='2130501' THEN c.nilai ELSE 0 END) AS psl4_a2
					,SUM(CASE WHEN map_pot ='21010201000105' THEN c.nilai ELSE 0 END) AS iwppnpn
					,SUM(CASE WHEN kd_rek6 not in ('2130301','210105010001','210105020001','210105030001','2130501','210102010001d') THEN c.nilai ELSE 0 END) AS pot_lain
					FROM trhsp2d a
					INNER JOIN trhspm b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
					INNER JOIN trspmpot c ON b.no_spm = c.no_spm AND b.kd_skpd = c.kd_skpd
					WHERE (a.jns_spp!='4' AND a.jenis_beban!='1') AND (a.tgl_kas_bud >= ? AND  a.tgl_kas_bud <= ?) AND a.status_bud='1'
					GROUP BY a.kd_skpd, a.nm_skpd,a.no_sp2d
					) b ON a.kd_skpd=b.kd_skpd AND a.no_sp2d=b.no_sp2d
					ORDER BY cast(no_kas_bud as int)", [$tgl1, $tgl2, $tgl1, $tgl2]);
            }
        }


        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'tanda_tangan' => $tanda_tangan,
            'tanggal1' => $tgl1,
            'tanggal2' => $tgl2,
            'pilihan' => $pilihan,
            'data_potongan' => $potongan_pajak,
            'sp2d' => $sp2d,
            'belanja' => $belanja,
        ];

        $view = view('bud.laporan_bendahara.cetak.potongan_pajak')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function daftarPengeluaran(Request $request)
    {
        $skpd_global = Auth::user()->kd_skpd;
        $pilihan = $request->pilihan;
        $tgl = $request->tgl;
        $ttd = $request->ttd;
        $kd_skpd = $request->kd_skpd;
        $kd_unit = $request->kd_unit;
        $beban = $request->beban;
        $bulan = $request->bulan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $jenis_print = $request->jenis_print;

        if ($ttd) {
            $tanda_tangan = DB::table('ms_ttd')
                ->select('nama', 'nip', 'jabatan', 'pangkat')
                ->where(['nip' => $ttd])
                ->whereIn('kode', ['BUD', 'PA'])
                ->first();
        } else {
            $tanda_tangan = null;
        }

        $data_pengeluaran2 = DB::table('trhsp2d as a')
            ->join('trhspm as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->join('trdspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->selectRaw("2 urut, '' no_kas_bud, a.no_kas_bud as urut2, '' tgl_kas_bud, '' no_sp2d,  '' tgl_sp2d,  a.kd_skpd, '' keperluan, '' nmrekan, '' pimpinan, '' nm_skpd, d.kd_sub_kegiatan,  d.kd_rek6, d.nm_rek6, d.nilai, d.no_bukti")
            // ->whereRaw("a.status_bud=? and month(a.tgl_kas_bud)=?", ['1', $bulan]) permintaan kak eni
            ->whereRaw("a.status_bud=?", ['1'])
            ->where(function ($query) use ($beban) {
                if ($beban == '0') {
                    $query->where('a.jns_spp', '4');
                } else if ($beban == '1') {
                    $query->whereRaw("(a.jns_spp=? or a.jns_spp=?)", ['5', '6']);
                } else if ($beban == '2') {
                    $query->where('a.jns_spp', '1');
                } else if ($beban == '3') {
                    $query->where('a.jns_spp', '3');
                } else if ($beban == '4') {
                    $query->where('a.jns_spp', '2');
                }
            })
            ->where(function ($query) use ($pilihan, $kd_skpd, $kd_unit) {
                if ($pilihan == '2') {
                    $query->whereRaw("LEFT(a.kd_skpd,17)=?", [$kd_skpd]);
                } else if ($pilihan == '3') {
                    $query->where('a.kd_skpd', $kd_unit);
                }
            })
            ->where(function ($query) use ($pilihan, $kd_skpd, $kd_unit, $periode1, $periode2, $bulan) {
                if ($pilihan == '3') {
                    $query->whereRaw("a.tgl_kas_bud between ? and ?", [$periode1, $periode2]);
                } else {
                    $query->whereRaw("month(a.tgl_kas_bud)=?", [$bulan]);
                }
            })
            ->groupByRaw("a.no_kas_bud, a.kd_skpd, a.keperluan, d.kd_sub_kegiatan, d.kd_rek6, d.nm_rek6,d.nilai,d.no_bukti");

        $data_pengeluaran1 = DB::table('trhsp2d as a')
            ->join('trhspm as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->join('trdspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->selectRaw("1 urut, a.no_kas_bud as urut2, a.no_kas_bud, a.tgl_kas_bud, a.no_sp2d, a.tgl_sp2d, a.kd_skpd, a.keperluan, a.nmrekan, c.pimpinan, a.nm_skpd, '' kd_sub_kegiatan, '' kd_rek6, '' nm_rek6, 0 nilai, 0 no_bukti")
            // ->whereRaw("a.status_bud=? and month(a.tgl_kas_bud)=?", ['1', $bulan])
            ->whereRaw("a.status_bud=?", ['1'])
            ->where(function ($query) use ($beban) {
                if ($beban == '0') {
                    $query->where('a.jns_spp', '4');
                } else if ($beban == '1') {
                    $query->whereRaw("(a.jns_spp=? or a.jns_spp=?)", ['5', '6']);
                } else if ($beban == '2') {
                    $query->where('a.jns_spp', '1');
                } else if ($beban == '3') {
                    $query->where('a.jns_spp', '3');
                } else if ($beban == '4') {
                    $query->where('a.jns_spp', '2');
                }
            })
            ->where(function ($query) use ($pilihan, $kd_skpd, $kd_unit) {
                if ($pilihan == '2') {
                    $query->whereRaw("LEFT(a.kd_skpd,17)=?", [$kd_skpd]);
                } else if ($pilihan == '3') {
                    $query->where('a.kd_skpd', $kd_unit);
                }
            })
            ->where(function ($query) use ($pilihan, $kd_skpd, $kd_unit, $periode1, $periode2, $bulan) {
                if ($pilihan == '3') {
                    $query->whereRaw("a.tgl_kas_bud between ? and ?", [$periode1, $periode2]);
                } else {
                    $query->whereRaw("month(a.tgl_kas_bud)=?", [$bulan]);
                }
            })
            ->groupByRaw("a.tgl_kas_bud, a.no_kas_bud, a.no_sp2d, a.tgl_sp2d, a.keperluan, a.nmrekan, c.pimpinan, a.kd_skpd, a.nm_skpd")->unionAll($data_pengeluaran2);

        $total_pengeluaran = DB::table('trhsp2d as a')
            ->join('trhspm as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->join('trdspp as d', function ($join) {
                $join->on('c.no_spp', '=', 'd.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->selectRaw("sum(d.nilai) as nilai")
            // ->whereRaw("a.status_bud=? and month(a.tgl_kas_bud)=?", ['1', $bulan])
            ->whereRaw("a.status_bud=?", ['1'])
            ->where(function ($query) use ($beban) {
                if ($beban == '0') {
                    $query->where('a.jns_spp', '4');
                } else if ($beban == '1') {
                    $query->whereRaw("(a.jns_spp=? or a.jns_spp=?)", ['5', '6']);
                } else if ($beban == '2') {
                    $query->where('a.jns_spp', '1');
                } else if ($beban == '3') {
                    $query->where('a.jns_spp', '3');
                } else if ($beban == '4') {
                    $query->where('a.jns_spp', '2');
                }
            })
            ->where(function ($query) use ($pilihan, $kd_skpd, $kd_unit) {
                if ($pilihan == '2') {
                    $query->whereRaw("LEFT(a.kd_skpd,17)=?", [$kd_skpd]);
                } else if ($pilihan == '3') {
                    $query->where('a.kd_skpd', $kd_unit);
                }
            })
            ->where(function ($query) use ($pilihan, $kd_skpd, $kd_unit, $periode1, $periode2, $bulan) {
                if ($pilihan == '3') {
                    $query->whereRaw("a.tgl_kas_bud between ? and ?", [$periode1, $periode2]);
                } else {
                    $query->whereRaw("month(a.tgl_kas_bud)=?", [$bulan]);
                }
            })
            ->first();

        $pengeluaran = DB::table(DB::raw("({$data_pengeluaran1->toSql()}) AS sub"))
            ->mergeBindings($data_pengeluaran1)
            ->orderBy(DB::raw("CAST(no_kas_bud as int)"))
            ->orderBy('urut')
            ->orderBy('kd_sub_kegiatan')
            ->orderBy('kd_rek6')
            ->get();


        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'tanda_tangan' => $tanda_tangan,
            'pilihan' => $pilihan,
            'data_pengeluaran' => $pengeluaran,
            'bulan' => $bulan,
            'beban' => $beban,
            'total_pengeluaran' => $total_pengeluaran->nilai
        ];

        $view = view('bud.laporan_bendahara.cetak.daftar_pengeluaran')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function daftarPenerimaan(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl1 = $request->tgl1;
        $tgl2 = $request->tgl2;
        $halaman = $request->halaman;
        $spasi = $request->spasi;
        $ttd = $request->ttd;
        $pengirim = $request->pengirim;
        $jenis_print = $request->jenis_print;

        $penerimaan = DB::table('trhkasin_ppkd as a')
            ->leftJoin('ms_pengirim as b', function ($join) {
                $join->on('a.sumber', '=', 'b.kd_pengirim');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->where(['a.sumber' => $pengirim])
            ->where(function ($query) use ($pilihan, $tgl1, $tgl2, $periode1, $periode2) {
                // PILIHAN PER TANGGAL
                if ($pilihan == '1') {
                    $query->whereRaw("(a.tgl_sts >= ? and a.tgl_sts <= ?)", [$tgl1, $tgl2]);
                }
                //PILIHAN PER PERIODE
                elseif ($pilihan == '2') {
                    $query->whereRaw("(month(a.tgl_sts)>=? and month(a.tgl_sts)<=?)", [$periode1, $periode2]);
                }
            })
            ->orderBy('a.tgl_sts')
            ->get();

        $penerimaan_lalu = DB::table('trhkasin_pkd as a')
            ->selectRaw("sum(a.total) as nilai")
            ->where(['a.sumber' => $pengirim])
            ->where(function ($query) use ($pilihan, $tgl1, $periode1) {
                // PILIHAN PER TANGGAL
                if ($pilihan == '1') {
                    $query->whereRaw("a.tgl_sts < ?", [$tgl1]);
                }
                //PILIHAN PER PERIODE
                elseif ($pilihan == '2') {
                    $query->whereRaw("month(a.tgl_sts) < ?", [$periode1]);
                }
            })
            ->first();

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal1' => $tgl1,
            'tanggal2' => $tgl2,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'kd_pengirim' => $pengirim,
            'pengirim' => DB::table('ms_pengirim')
                ->select('nm_pengirim')
                ->where(['kd_pengirim' => $pengirim])
                ->first(),
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_penerimaan' => $penerimaan,
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
            'penerimaan_lalu' => $penerimaan_lalu->nilai,
            'list_pengirim' => [
                '102', '153', '154', '167', '168', '169', '170', '172', '173', '22', '23', '25', '26', '43', '44', '45', '46', '47', '48', '49', '50', '54', '55', '56', '58', '89', '91', '92', '95', '113', '143', '144', '101', '174',
            ],
        ];

        $view = view('bud.laporan_bendahara.cetak.daftar_penerimaan')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function penerimaanNonPendapatan(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $spasi = $request->spasi;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        $penerimaan = DB::table('penerimaan_non_sp2d as a')
            ->whereIn('jenis', ['1', '2'])
            ->where(function ($query) use ($pilihan, $tgl, $periode1, $periode2) {
                // PILIHAN PER TANGGAL
                if ($pilihan == '1') {
                    $query->whereRaw("tanggal=?", [$tgl]);
                }
                //PILIHAN PER PERIODE
                elseif ($pilihan == '2') {
                    $query->whereRaw("tanggal between ? and ?", [$periode1, $periode2]);
                }
            })
            ->get();

        $penerimaan_lalu = DB::table('penerimaan_non_sp2d as a')
            ->selectRaw("sum(nilai) as nilai")
            ->whereIn('jenis', ['1', '2'])
            ->where(function ($query) use ($pilihan, $tgl, $periode1) {
                // PILIHAN PER TANGGAL
                if ($pilihan == '1') {
                    $query->whereRaw("tanggal < ?", [$tgl]);
                }
                //PILIHAN PER PERIODE
                elseif ($pilihan == '2') {
                    $query->whereRaw("tanggal < ?", [$periode1]);
                }
            })
            ->first();

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal' => $tgl,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_penerimaan' => $penerimaan,
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
            'penerimaan_lalu' => $penerimaan_lalu->nilai,
        ];

        $view = view('bud.laporan_bendahara.cetak.penerimaan_non_pendapatan')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function transferDana(Request $request)
    {
        ini_set('max_execution_time', -1);
        $tgl            = $request->tgl;
        $ttd            = $request->ttd;
        $tgl1           = $request->periode1;
        $tgl2           = $request->periode2;
        $jenis_print    = $request->jenis_print;
        $total          = 0;
        $total_potongan = 0;
        $map = DB::table('map_transfer_dana')->orderByRaw("urut")->get();

        foreach ($map as $data) {
            if ($data->kode == 3) {
                $kd_rek         = isset($data->kd_rek) ?  $data->kd_rek : "'-'";
                $panjang        = isset($data->panjang) ? $data->panjang :   0;
                $kd_rek_notin   = isset($data->kd_rek_not_in) ?  $data->kd_rek_not_in : "'-'";
                $panjang_notin  = isset($data->panjang_not_in) ?  $data->panjang_not_in : 0;

                $rincian = DB::table('trdkasin_ppkd as a')
                    ->join('trhkasin_ppkd as b', function ($join) {
                        $join->on('a.no_kas', '=', 'b.no_kas');
                        $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                    })
                    ->selectRaw("isnull(rupiah,0) as rupiah,(select sum(total) from trhkasin_ppkd_pot c where c.no_sts=a.no_sts and c.kd_skpd=a.kd_skpd)as pot")
                    ->whereRaw("left(kd_rek6,$panjang) in ($kd_rek) and left(kd_rek6,$panjang_notin) not in ($kd_rek_notin) and (tgl_kas BETWEEN '$tgl1' and '$tgl2')")
                    ->get();


                foreach ($rincian as $item) {
                    $total          = $total + $item->rupiah;
                    $total_potongan = $total_potongan + $item->pot;
                }
            }
        }

        $data = [
            'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'tanggal'       => $tgl,
            'tanda_tangan'  => DB::table('ms_ttd')->where(['kode' => 'BUD', 'nip' => $ttd])->first(),
            'tgl1'          => $tgl1,
            'tgl2'          => $tgl2,
            'map'           => $map,
            'total_kppn'    => $total,
            'total_pot_kppn' => $total_potongan,
        ];

        $view = view('bud.laporan_bendahara.cetak.transfer_dana')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function restitusi(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $spasi = $request->spasi;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        $restitusi = DB::table('trhrestitusi as a')
            ->join('trdrestitusi as b', function ($join) {
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.no_sts', '=', 'b.no_sts');
            })
            ->selectRaw("b.no_sts no_bukti, a.keterangan, b.kd_skpd, (SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=b.kd_skpd) nm_skpd, a.tgl_sts tgl_bukti, b.kd_rek6, (SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6=b.kd_rek6) nm_rek6, b.rupiah")
            ->where(function ($query) use ($pilihan, $tgl, $periode1, $periode2) {
                // PILIHAN PER TANGGAL
                if ($pilihan == '1') {
                    $query->whereRaw("a.tgl_sts=?", [$tgl]);
                }
                //PILIHAN PER PERIODE
                elseif ($pilihan == '2') {
                    $query->whereRaw("a.tgl_sts between ? and ?", [$periode1, $periode2]);
                }
            })
            ->orderBy('tgl_bukti')
            ->orderBy('kd_rek6')
            ->get();

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal' => $tgl,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_restitusi' => $restitusi,
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
        ];

        $view = view('bud.laporan_bendahara.cetak.restitusi')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function rth(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl = $request->tgl;
        $bulan = $request->bulan;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        if ($pilihan == '1') {
            $rth = DB::select("exec cetak_rth2 ?", array($bulan));
        } elseif ($pilihan == '2') {
            $rth = DB::select("exec cetak_rth_periode2 ?,?", array($periode1, $periode2));
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal' => $tgl,
            'bulan' => $bulan,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_rth' => $rth,
            'total_data' => count($rth),
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
        ];

        $judul = 'RTH';

        $view = view('bud.laporan_bendahara.cetak.rth')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }

    public function pengeluaranNonSp2d(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $spasi = $request->spasi;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        $pengeluaran = DB::table('pengeluaran_non_sp2d as a')
            ->where(function ($query) use ($pilihan, $tgl, $periode1, $periode2) {
                // PILIHAN PER TANGGAL
                if ($pilihan == '1') {
                    $query->whereRaw("tanggal=?", [$tgl]);
                }
                //PILIHAN PER PERIODE
                elseif ($pilihan == '2') {
                    $query->whereRaw("tanggal between ? and ?", [$periode1, $periode2]);
                }
            })
            ->get();

        $pengeluaran_lalu = DB::table('pengeluaran_non_sp2d as a')
            ->selectRaw("sum(nilai) as nilai")
            ->where(function ($query) use ($pilihan, $tgl, $periode1) {
                // PILIHAN PER TANGGAL
                if ($pilihan == '1') {
                    $query->whereRaw("tanggal < ?", [$tgl]);
                }
                //PILIHAN PER PERIODE
                elseif ($pilihan == '2') {
                    $query->whereRaw("tanggal < ?", [$periode1]);
                }
            })
            ->first();

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal' => $tgl,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_pengeluaran' => $pengeluaran,
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
            'pengeluaran_lalu' => $pengeluaran_lalu->nilai,
        ];

        $view = view('bud.laporan_bendahara.cetak.pengeluaran_non_sp2d')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function dth(Request $request)
    {
        $pilihan = $request->pilihan;
        $skpd = $request->skpd;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $bendahara = $request->bendahara;
        $pa_kpa = $request->pa_kpa;
        $spasi = $request->spasi;
        $bulan = $request->bulan;
        $jenis_print = $request->jenis_print;

        // VERSI LAMA
        // if ($pilihan == '1' && $jenis_print == 'keseluruhan') {
        //     $dth = DB::select("SELECT 1 urut, p.no_spm, p.nil_spm nilai, p.no_sp2d, p.nil_sp2d nilai_belanja, '' no_bukti, '' kode_belanja, '' kd_rek6, '' as jenis_pajak,0 as nilai_pot, (select npwp from trhspm WHERE no_spm=p.no_spm) npwp, p.nmrekan as nmrekan, '' ket,p.jns_spp, '' ntpn
        //     FROM (
        //                             SELECT x.kd_skpd, x.no_sp2d, y.no_spm, x.pot, y.nil_spm, y.nil_sp2d, y.jns_spp,y.nmrekan FROM (
        //                             SELECT b.kd_skpd, a.no_sp2d, SUM(b.nilai) pot
        //                             FROM trhstrpot a INNER JOIN trdstrpot b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
        //                             WHERE MONTH(a.tgl_bukti)=?
        //                             GROUP BY b.kd_skpd, a.no_sp2d ) x
        //                             LEFT JOIN
        //                             (
        //                             SELECT d.kd_skpd, d.no_spm, c.nilai nil_spm, d.no_sp2d, d.nilai nil_sp2d, d.jns_spp, c.nmrekan
        //                             FROM trdspp a INNER JOIN trhspp b
        //                             ON a.no_spp = b.no_spp AND a.kd_skpd = b.kd_skpd
        //                             INNER JOIN trhspm c
        //                             ON b.no_spp = c.no_spp AND a.kd_skpd = c.kd_skpd
        //                             INNER JOIN trhsp2d d
        //                             on c.no_spm = d.no_spm AND c.kd_skpd=d.kd_skpd
        //                             WHERE (d.sp2d_batal=0 OR d.sp2d_batal is NULL) and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
        //                             GROUP BY d.kd_skpd,d.no_spm, d.no_sp2d, c.nilai, d.nilai, d.jns_spp,c.nmrekan) y
        //                             ON x.kd_skpd=y.kd_skpd AND x.no_sp2d=y.no_sp2d ) p
        //                             UNION ALL

        //                             SELECT 2 as urut, '' as no_spm,0 as nilai,p.no_sp2d,0 as nilai_belanja,
        //                                                 p.no_bukti, p.kode_belanja,p.kd_rek6,'' as jenis_pajak,p.pot as nilai_pot,p.npwp,
        //                                                 rekanan nmrekan,    case when p.jns_spp='6' or p.jns_spp='5' or  p.jns_spp='4' then p.keperluan else
        //                             'No Set: ' + p.no_bukti end AS ket, p.jns_spp, p.ntpn
        //                             FROM (
        //                             SELECT x.*, y.keperluan FROM (
        //                             SELECT b.kd_skpd, b.no_bukti, a.kd_sub_kegiatan+'.'+b.kd_rek_trans kode_belanja,
        //                                    RTRIM(b.kd_rek6) kd_rek6, a.no_sp2d, b.nilai pot, b.rekanan, b.npwp, b.ntpn, a.jns_spp
        //                             FROM trhstrpot a INNER JOIN trdstrpot b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
        //                             WHERE MONTH(a.tgl_bukti)=? ) x
        //                             LEFT JOIN
        //                             (
        //                             SELECT d.kd_skpd, d.no_spm, c.nilai nil_spm, d.no_sp2d, d.nilai nil_sp2d, d.jns_spp, b.keperluan
        //                             FROM trdspp a INNER JOIN trhspp b
        //                             ON a.no_spp = b.no_spp AND a.kd_skpd = b.kd_skpd
        //                             INNER JOIN trhspm c
        //                             ON b.no_spp = c.no_spp AND a.kd_skpd = c.kd_skpd
        //                             INNER JOIN trhsp2d d
        //                             on c.no_spm = d.no_spm AND c.kd_skpd=d.kd_skpd
        //                             WHERE (d.sp2d_batal=0 OR d.sp2d_batal is NULL) and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
        //                             GROUP BY d.kd_skpd,d.no_spm, d.no_sp2d, c.nilai, d.nilai, d.jns_spp,b.keperluan) y
        //                             ON x.kd_skpd=y.kd_skpd AND x.no_sp2d=y.no_sp2d ) p
        //                             where p.kd_rek6 in ('2110301','2130101','2130201','2130301','2130401','2130501')
        //                             ORDER BY no_sp2d,urut,no_spm,kode_belanja,kd_rek6", [$bulan, $bulan]);
        // } elseif ($pilihan == '2' && $jenis_print == 'keseluruhan') {
        //     $dth = DB::select("SELECT 1 urut, p.no_spm, p.nil_spm nilai, p.no_sp2d, p.nil_sp2d nilai_belanja, '' no_bukti, '' kode_belanja, '' kd_rek6, '' as jenis_pajak,0 as nilai_pot, (select npwp from trhspm WHERE no_spm=p.no_spm) npwp, p.nmrekan as nmrekan, '' ket,p.jns_spp, '' ntpn
        //     FROM (
        //                             SELECT x.kd_skpd, x.no_sp2d, y.no_spm, x.pot, y.nil_spm, y.nil_sp2d, y.jns_spp,y.nmrekan FROM (
        //                             SELECT b.kd_skpd, a.no_sp2d, SUM(b.nilai) pot
        //                             FROM trhstrpot a INNER JOIN trdstrpot b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
        //                             WHERE (a.tgl_bukti>=? and a.tgl_bukti <=?)
        //                             GROUP BY b.kd_skpd, a.no_sp2d ) x
        //                             LEFT JOIN
        //                             (
        //                             SELECT d.kd_skpd, d.no_spm, c.nilai nil_spm, d.no_sp2d, d.nilai nil_sp2d, d.jns_spp, c.nmrekan
        //                             FROM trdspp a INNER JOIN trhspp b
        //                             ON a.no_spp = b.no_spp AND a.kd_skpd = b.kd_skpd
        //                             INNER JOIN trhspm c
        //                             ON b.no_spp = c.no_spp AND a.kd_skpd = c.kd_skpd
        //                             INNER JOIN trhsp2d d
        //                             on c.no_spm = d.no_spm AND c.kd_skpd=d.kd_skpd
        //                             WHERE (d.sp2d_batal=0 OR d.sp2d_batal is NULL) and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
        //                             GROUP BY d.kd_skpd,d.no_spm, d.no_sp2d, c.nilai, d.nilai, d.jns_spp,c.nmrekan) y
        //                             ON x.kd_skpd=y.kd_skpd AND x.no_sp2d=y.no_sp2d ) p
        //                             UNION ALL

        //                             SELECT 2 as urut, '' as no_spm,0 as nilai,p.no_sp2d,0 as nilai_belanja,
        //                                                 p.no_bukti, p.kode_belanja,p.kd_rek6,'' as jenis_pajak,p.pot as nilai_pot,p.npwp,
        //                                                 rekanan nmrekan,    case when p.jns_spp='6' or p.jns_spp='5' or  p.jns_spp='4' then p.keperluan else
        //                             'No Set: ' + p.no_bukti end AS ket, p.jns_spp, p.ntpn
        //                             FROM (
        //                             SELECT x.*, y.keperluan FROM (
        //                             SELECT b.kd_skpd, b.no_bukti, a.kd_sub_kegiatan+'.'+b.kd_rek_trans kode_belanja,
        //                                    RTRIM(b.kd_rek6) kd_rek6, a.no_sp2d, b.nilai pot, b.rekanan, b.npwp, b.ntpn, a.jns_spp
        //                             FROM trhstrpot a INNER JOIN trdstrpot b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
        //                             WHERE (a.tgl_bukti>=? and a.tgl_bukti <=?) ) x
        //                             LEFT JOIN
        //                             (
        //                             SELECT d.kd_skpd, d.no_spm, c.nilai nil_spm, d.no_sp2d, d.nilai nil_sp2d, d.jns_spp, b.keperluan
        //                             FROM trdspp a INNER JOIN trhspp b
        //                             ON a.no_spp = b.no_spp AND a.kd_skpd = b.kd_skpd
        //                             INNER JOIN trhspm c
        //                             ON b.no_spp = c.no_spp AND a.kd_skpd = c.kd_skpd
        //                             INNER JOIN trhsp2d d
        //                             on c.no_spm = d.no_spm AND c.kd_skpd=d.kd_skpd
        //                             WHERE (d.sp2d_batal=0 OR d.sp2d_batal is NULL) and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
        //                             GROUP BY d.kd_skpd,d.no_spm, d.no_sp2d, c.nilai, d.nilai, d.jns_spp,b.keperluan) y
        //                             ON x.kd_skpd=y.kd_skpd AND x.no_sp2d=y.no_sp2d ) p
        //                             where p.kd_rek6 in ('2110301','2130101','2130201','2130301','2130401','2130501')
        //                             ORDER BY no_sp2d,urut,no_spm,kode_belanja,kd_rek6", [$periode1, $periode2, $periode1, $periode2]);
        // } elseif ($pilihan == '1') {
        //     $dth = DB::select("SELECT 1 urut, p.no_spm, p.nil_spm nilai, p.no_sp2d, p.nil_sp2d nilai_belanja, '' no_bukti, '' kode_belanja, '' kd_rek6, '' as jenis_pajak,0 as nilai_pot, (select npwp from trhspm WHERE no_spm=p.no_spm) npwp, p.nmrekan as nmrekan, '' ket,p.jns_spp, '' ntpn
        //     FROM (
        //                             SELECT x.kd_skpd, x.no_sp2d, y.no_spm, x.pot, y.nil_spm, y.nil_sp2d, y.jns_spp,y.nmrekan FROM (
        //                             SELECT b.kd_skpd, a.no_sp2d, SUM(b.nilai) pot
        //                             FROM trhstrpot a INNER JOIN trdstrpot b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
        //                             WHERE MONTH(a.tgl_bukti)=? and b.kd_skpd=?
        //                             GROUP BY b.kd_skpd, a.no_sp2d ) x
        //                             LEFT JOIN
        //                             (
        //                             SELECT d.kd_skpd, d.no_spm, c.nilai nil_spm, d.no_sp2d, d.nilai nil_sp2d, d.jns_spp, c.nmrekan
        //                             FROM trdspp a INNER JOIN trhspp b
        //                             ON a.no_spp = b.no_spp AND a.kd_skpd = b.kd_skpd
        //                             INNER JOIN trhspm c
        //                             ON b.no_spp = c.no_spp AND a.kd_skpd = c.kd_skpd
        //                             INNER JOIN trhsp2d d
        //                             on c.no_spm = d.no_spm AND c.kd_skpd=d.kd_skpd
        //                             WHERE (d.sp2d_batal=0 OR d.sp2d_batal is NULL) and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
        //                             GROUP BY d.kd_skpd,d.no_spm, d.no_sp2d, c.nilai, d.nilai, d.jns_spp,c.nmrekan) y
        //                             ON x.kd_skpd=y.kd_skpd AND x.no_sp2d=y.no_sp2d ) p
        //                             UNION ALL

        //                             SELECT 2 as urut, '' as no_spm,0 as nilai,p.no_sp2d,0 as nilai_belanja,
        //                                                 p.no_bukti, p.kode_belanja,p.kd_rek6,'' as jenis_pajak,p.pot as nilai_pot,p.npwp,
        //                                                 rekanan nmrekan,    case when p.jns_spp='6' or p.jns_spp='5' or  p.jns_spp='4' then p.keperluan else
        //                             'No Set: ' + p.no_bukti end AS ket, p.jns_spp, p.ntpn
        //                             FROM (
        //                             SELECT x.*, y.keperluan FROM (
        //                             SELECT b.kd_skpd, b.no_bukti, a.kd_sub_kegiatan+'.'+b.kd_rek_trans kode_belanja,
        //                                    RTRIM(b.kd_rek6) kd_rek6, a.no_sp2d, b.nilai pot, b.rekanan, b.npwp, b.ntpn, a.jns_spp
        //                             FROM trhstrpot a INNER JOIN trdstrpot b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
        //                             WHERE MONTH(a.tgl_bukti)=? and b.kd_skpd=? ) x
        //                             LEFT JOIN
        //                             (
        //                             SELECT d.kd_skpd, d.no_spm, c.nilai nil_spm, d.no_sp2d, d.nilai nil_sp2d, d.jns_spp, b.keperluan
        //                             FROM trdspp a INNER JOIN trhspp b
        //                             ON a.no_spp = b.no_spp AND a.kd_skpd = b.kd_skpd
        //                             INNER JOIN trhspm c
        //                             ON b.no_spp = c.no_spp AND a.kd_skpd = c.kd_skpd
        //                             INNER JOIN trhsp2d d
        //                             on c.no_spm = d.no_spm AND c.kd_skpd=d.kd_skpd
        //                             WHERE (d.sp2d_batal=0 OR d.sp2d_batal is NULL) and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
        //                             GROUP BY d.kd_skpd,d.no_spm, d.no_sp2d, c.nilai, d.nilai, d.jns_spp,b.keperluan) y
        //                             ON x.kd_skpd=y.kd_skpd AND x.no_sp2d=y.no_sp2d ) p
        //                             where p.kd_rek6 in ('2110301','2130101','2130201','2130301','2130401','2130501')
        //                             ORDER BY no_sp2d,urut,no_spm,kode_belanja,kd_rek6", [$bulan, $skpd, $bulan, $skpd]);
        // } elseif ($pilihan == '2') {
        //     $dth = DB::select("SELECT 1 urut, p.no_spm, p.nil_spm nilai, p.no_sp2d, p.nil_sp2d nilai_belanja, '' no_bukti, '' kode_belanja, '' kd_rek6, '' as jenis_pajak,0 as nilai_pot, (select npwp from trhspm WHERE no_spm=p.no_spm) npwp, p.nmrekan as nmrekan, '' ket,p.jns_spp, '' ntpn
        //     FROM (
        //                             SELECT x.kd_skpd, x.no_sp2d, y.no_spm, x.pot, y.nil_spm, y.nil_sp2d, y.jns_spp,y.nmrekan FROM (
        //                             SELECT b.kd_skpd, a.no_sp2d, SUM(b.nilai) pot
        //                             FROM trhstrpot a INNER JOIN trdstrpot b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
        //                             WHERE (a.tgl_bukti>=? and a.tgl_bukti <=?) and b.kd_skpd=?
        //                             GROUP BY b.kd_skpd, a.no_sp2d ) x
        //                             LEFT JOIN
        //                             (
        //                             SELECT d.kd_skpd, d.no_spm, c.nilai nil_spm, d.no_sp2d, d.nilai nil_sp2d, d.jns_spp, c.nmrekan
        //                             FROM trdspp a INNER JOIN trhspp b
        //                             ON a.no_spp = b.no_spp AND a.kd_skpd = b.kd_skpd
        //                             INNER JOIN trhspm c
        //                             ON b.no_spp = c.no_spp AND a.kd_skpd = c.kd_skpd
        //                             INNER JOIN trhsp2d d
        //                             on c.no_spm = d.no_spm AND c.kd_skpd=d.kd_skpd
        //                             WHERE (d.sp2d_batal=0 OR d.sp2d_batal is NULL) and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
        //                             GROUP BY d.kd_skpd,d.no_spm, d.no_sp2d, c.nilai, d.nilai, d.jns_spp,c.nmrekan) y
        //                             ON x.kd_skpd=y.kd_skpd AND x.no_sp2d=y.no_sp2d ) p
        //                             UNION ALL

        //                             SELECT 2 as urut, '' as no_spm,0 as nilai,p.no_sp2d,0 as nilai_belanja,
        //                                                 p.no_bukti, p.kode_belanja,p.kd_rek6,'' as jenis_pajak,p.pot as nilai_pot,p.npwp,
        //                                                 rekanan nmrekan,    case when p.jns_spp='6' or p.jns_spp='5' or  p.jns_spp='4' then p.keperluan else
        //                             'No Set: ' + p.no_bukti end AS ket, p.jns_spp, p.ntpn
        //                             FROM (
        //                             SELECT x.*, y.keperluan FROM (
        //                             SELECT b.kd_skpd, b.no_bukti, a.kd_sub_kegiatan+'.'+b.kd_rek_trans kode_belanja,
        //                                    RTRIM(b.kd_rek6) kd_rek6, a.no_sp2d, b.nilai pot, b.rekanan, b.npwp, b.ntpn, a.jns_spp
        //                             FROM trhstrpot a INNER JOIN trdstrpot b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
        //                             WHERE (a.tgl_bukti>=? and a.tgl_bukti <=?) and b.kd_skpd=? ) x
        //                             LEFT JOIN
        //                             (
        //                             SELECT d.kd_skpd, d.no_spm, c.nilai nil_spm, d.no_sp2d, d.nilai nil_sp2d, d.jns_spp, b.keperluan
        //                             FROM trdspp a INNER JOIN trhspp b
        //                             ON a.no_spp = b.no_spp AND a.kd_skpd = b.kd_skpd
        //                             INNER JOIN trhspm c
        //                             ON b.no_spp = c.no_spp AND a.kd_skpd = c.kd_skpd
        //                             INNER JOIN trhsp2d d
        //                             on c.no_spm = d.no_spm AND c.kd_skpd=d.kd_skpd
        //                             WHERE (d.sp2d_batal=0 OR d.sp2d_batal is NULL) and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
        //                             GROUP BY d.kd_skpd,d.no_spm, d.no_sp2d, c.nilai, d.nilai, d.jns_spp,b.keperluan) y
        //                             ON x.kd_skpd=y.kd_skpd AND x.no_sp2d=y.no_sp2d ) p
        //                             where p.kd_rek6 in ('2110301','2130101','2130201','2130301','2130401','2130501')
        //                             ORDER BY no_sp2d,urut,no_spm,kode_belanja,kd_rek6", [$periode1, $periode2, $skpd, $periode1, $periode2, $skpd]);
        // }

        if ($pilihan == '1' && $jenis_print == 'keseluruhan') {
            $dth = DB::select("SELECT 1 urut, c.no_spm,c.nilai,a.no_sp2d,x.nil_trans as nilai_belanja,'' no_bukti,'' kode_belanja,
            '' as kd_rek6,'' as jenis_pajak,0 as nilai_pot,'' as npwp,
            '' as nmrekan,z.banyak, ''ket,c.jns_spp, '' ntpn,''ebilling,''keperluan
            FROM trhstrpot a
            INNER JOIN trdstrpot b
            ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
            LEFT JOIN trhsp2d c
            ON left(a.kd_skpd,17)=left(c.kd_skpd,17) AND a.no_sp2d=c.no_sp2d
            LEFT JOIN
            (SELECT b.kd_skpd, a.no_sp2d, SUM(a.nilai) as nil_trans FROM trdtransout a
            INNER JOIN trhtransout b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
            -- WHERE b.kd_skpd= ?
            GROUP BY b.kd_skpd, a.no_sp2d) x
            ON a.kd_skpd=x.kd_skpd AND a.no_sp2d=x.no_sp2d
            LEFT JOIN
            (SELECT b.kd_skpd,b.no_sp2d, COUNT(b.no_sp2d) as banyak
            FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
            WHERE month(b.tgl_bukti)= ?
            AND RTRIM(a.kd_rek6) IN ('210106010001','210105010001','210105020001','210105030001','210109010001' )
            GROUP BY b.kd_skpd,b.no_sp2d)z
            ON a.kd_skpd=z.kd_skpd and a.no_sp2d=z.no_sp2d
            WHERE month(a.tgl_bukti)= ?
            AND b.kd_rek6 IN ('210106010001','210105010001','210105020001','210105030001','210109010001' )
            GROUP BY c.no_spm,c.nilai,a.no_sp2d,x.nil_trans,z.banyak,c.jns_spp
            UNION ALL
            SELECT 2 as urut, '' as no_spm,0 as nilai,b.no_sp2d as no_sp2d,0 as nilai_belanja,
            a.no_bukti, kd_sub_kegiatan+'.'+a.kd_rek_trans as kode_belanja,RTRIM(a.kd_rek6),'' as jenis_pajak,a.nilai as nilai_pot,b.npwp,
            b.nmrekan,0 banyak,
            'No Set: '+a.no_bukti as ket,
            '' jns_spp, a.ntpn,a.ebilling,b.ket as keperluan
            FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
            WHERE month(b.tgl_bukti)= ?
            AND RTRIM(a.kd_rek6) IN ('210106010001','210105010001','210105020001','210105030001','210109010001' )
            ORDER BY no_sp2d,urut,no_spm,kode_belanja,kd_rek6", [$bulan, $bulan]);
        } elseif ($pilihan == '2' && $jenis_print == 'keseluruhan') {
            $dth = DB::select("SELECT 1 urut, c.no_spm,c.nilai,a.no_sp2d,x.nil_trans as nilai_belanja,'' no_bukti,'' kode_belanja,
            '' as kd_rek6,'' as jenis_pajak,0 as nilai_pot,'' as npwp,
            '' as nmrekan,z.banyak, ''ket,c.jns_spp, '' ntpn,''ebilling,''keperluan
            FROM trhstrpot a
            INNER JOIN trdstrpot b
            ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
            LEFT JOIN trhsp2d c
            ON left(a.kd_skpd,17)=left(c.kd_skpd,17) AND a.no_sp2d=c.no_sp2d
            LEFT JOIN
            (SELECT b.kd_skpd, a.no_sp2d, SUM(a.nilai) as nil_trans FROM trdtransout a
            INNER JOIN trhtransout b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
            GROUP BY b.kd_skpd, a.no_sp2d) x
            ON a.kd_skpd=x.kd_skpd AND a.no_sp2d=x.no_sp2d
            LEFT JOIN
            (SELECT b.kd_skpd,b.no_sp2d, COUNT(b.no_sp2d) as banyak
            FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
            WHERE (b.tgl_bukti>=? and b.tgl_bukti <=?)
            AND RTRIM(a.kd_rek6) IN ('210106010001','210105010001','210105020001','210105030001','210109010001' )
            GROUP BY b.kd_skpd,b.no_sp2d)z
            ON a.kd_skpd=z.kd_skpd and a.no_sp2d=z.no_sp2d
            WHERE (a.tgl_bukti>=? and a.tgl_bukti <=?)
            AND b.kd_rek6 IN ('210106010001','210105010001','210105020001','210105030001','210109010001' )
            GROUP BY c.no_spm,c.nilai,a.no_sp2d,x.nil_trans,z.banyak,c.jns_spp
            UNION ALL
            SELECT 2 as urut, '' as no_spm,0 as nilai,b.no_sp2d as no_sp2d,0 as nilai_belanja,
            a.no_bukti, kd_sub_kegiatan+'.'+a.kd_rek_trans as kode_belanja,RTRIM(a.kd_rek6),'' as jenis_pajak,a.nilai as nilai_pot,b.npwp,
            b.nmrekan,0 banyak,
            'No Set: '+a.no_bukti as ket,
            '' jns_spp, a.ntpn,a.ebilling,b.ket as keperluan
            FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
            WHERE (b.tgl_bukti>=? and b.tgl_bukti <=?)
            AND RTRIM(a.kd_rek6) IN ('210106010001','210105010001','210105020001','210105030001','210109010001' )
            ORDER BY no_sp2d,urut,no_spm,kode_belanja,kd_rek6", [$periode1, $periode2, $periode1, $periode2, $periode1, $periode2]);
        } elseif ($pilihan == '1') {
            $dth =
                DB::select("SELECT 1 urut, c.no_spm,c.nilai,a.no_sp2d,x.nil_trans as nilai_belanja,'' no_bukti,'' kode_belanja,
            '' as kd_rek6,'' as jenis_pajak,0 as nilai_pot,'' as npwp,
            '' as nmrekan,z.banyak, ''ket,c.jns_spp, '' ntpn,''ebilling,''keperluan
            FROM trhstrpot a
            INNER JOIN trdstrpot b
            ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
            LEFT JOIN trhsp2d c
            ON left(a.kd_skpd,17)=left(c.kd_skpd,17) AND a.no_sp2d=c.no_sp2d
            LEFT JOIN
            (SELECT b.kd_skpd, a.no_sp2d, SUM(a.nilai) as nil_trans FROM trdtransout a
            INNER JOIN trhtransout b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
            WHERE b.kd_skpd= ?
            GROUP BY b.kd_skpd, a.no_sp2d) x
            ON a.kd_skpd=x.kd_skpd AND a.no_sp2d=x.no_sp2d
            LEFT JOIN
            (SELECT b.kd_skpd,b.no_sp2d, COUNT(b.no_sp2d) as banyak
            FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
            WHERE b.kd_skpd =  ? AND month(b.tgl_bukti)= ?
            AND RTRIM(a.kd_rek6) IN ('210106010001','210105010001','210105020001','210105030001','210109010001' )
            GROUP BY b.kd_skpd,b.no_sp2d)z
            ON a.kd_skpd=z.kd_skpd and a.no_sp2d=z.no_sp2d
            WHERE a.kd_skpd =  ? AND month(a.tgl_bukti)= ?
            AND b.kd_rek6 IN ('210106010001','210105010001','210105020001','210105030001','210109010001' )
            GROUP BY c.no_spm,c.nilai,a.no_sp2d,x.nil_trans,z.banyak,c.jns_spp
            UNION ALL
            SELECT 2 as urut, '' as no_spm,0 as nilai,b.no_sp2d as no_sp2d,0 as nilai_belanja,
            a.no_bukti, kd_sub_kegiatan+'.'+a.kd_rek_trans as kode_belanja,RTRIM(a.kd_rek6),'' as jenis_pajak,a.nilai as nilai_pot,b.npwp,
            b.nmrekan,0 banyak,
            'No Set: '+a.no_bukti as ket,
            '' jns_spp, a.ntpn,a.ebilling,b.ket as keperluan
            FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
            WHERE b.kd_skpd =  ? AND month(b.tgl_bukti)= ?
            AND RTRIM(a.kd_rek6) IN ('210106010001','210105010001','210105020001','210105030001','210109010001' )
            ORDER BY no_sp2d,urut,no_spm,kode_belanja,kd_rek6 ", [$skpd, $skpd, $bulan, $skpd, $bulan, $skpd, $bulan]);
        } elseif ($pilihan == '2') {
            $dth =
                DB::select("SELECT 1 urut, c.no_spm,c.nilai,a.no_sp2d,x.nil_trans as nilai_belanja,'' no_bukti,'' kode_belanja,
            '' as kd_rek6,'' as jenis_pajak,0 as nilai_pot,'' as npwp,
            '' as nmrekan,z.banyak, ''ket,c.jns_spp, '' ntpn,''ebilling,''keperluan
            FROM trhstrpot a
            INNER JOIN trdstrpot b
            ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
            LEFT JOIN trhsp2d c
            ON left(a.kd_skpd,17)=left(c.kd_skpd,17) AND a.no_sp2d=c.no_sp2d
            LEFT JOIN
            (SELECT b.kd_skpd, a.no_sp2d, SUM(a.nilai) as nil_trans FROM trdtransout a
            INNER JOIN trhtransout b ON a.kd_skpd=b.kd_skpd AND a.no_bukti=b.no_bukti
            WHERE b.kd_skpd= ?
            GROUP BY b.kd_skpd, a.no_sp2d) x
            ON a.kd_skpd=x.kd_skpd AND a.no_sp2d=x.no_sp2d
            LEFT JOIN
            (SELECT b.kd_skpd,b.no_sp2d, COUNT(b.no_sp2d) as banyak
            FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
            WHERE b.kd_skpd =  ? AND (b.tgl_bukti>=? and b.tgl_bukti <=?)
            AND RTRIM(a.kd_rek6) IN ('210106010001','210105010001','210105020001','210105030001','210109010001' )
            GROUP BY b.kd_skpd,b.no_sp2d)z
            ON a.kd_skpd=z.kd_skpd and a.no_sp2d=z.no_sp2d
            WHERE a.kd_skpd =  ? AND (a.tgl_bukti>=? and a.tgl_bukti <=?)
            AND b.kd_rek6 IN ('210106010001','210105010001','210105020001','210105030001','210109010001' )
            GROUP BY c.no_spm,c.nilai,a.no_sp2d,x.nil_trans,z.banyak,c.jns_spp
            UNION ALL
            SELECT 2 as urut, '' as no_spm,0 as nilai,b.no_sp2d as no_sp2d,0 as nilai_belanja,
            a.no_bukti, kd_sub_kegiatan+'.'+a.kd_rek_trans as kode_belanja,RTRIM(a.kd_rek6),'' as jenis_pajak,a.nilai as nilai_pot,b.npwp,
            b.nmrekan,0 banyak,
            'No Set: '+a.no_bukti as ket,
            '' jns_spp, a.ntpn,a.ebilling,b.ket as keperluan
            FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
            WHERE b.kd_skpd =  ? AND (b.tgl_bukti>=? and b.tgl_bukti <=?)
            AND RTRIM(a.kd_rek6) IN ('210106010001','210105010001','210105020001','210105030001','210109010001' )
            ORDER BY no_sp2d,urut,no_spm,kode_belanja,kd_rek6 ", [$skpd, $skpd, $periode1, $periode2, $skpd, $periode1, $periode2, $skpd, $periode1, $periode2]);
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal' => $tgl,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'bulan' => $bulan,
            'data_dth' => $dth,
            'bendahara' => DB::table('ms_ttd')
                ->where(['nip' => $bendahara])
                ->first(),
            'pa_kpa' => DB::table('ms_ttd')
                ->where(['nip' => $pa_kpa])
                ->first(),
            'jenis_print' => $jenis_print,
            'skpd' => $skpd
        ];

        $judul = 'DTH';

        $view = view('bud.laporan_bendahara.cetak.dth')->with($data);

        if ($jenis_print == 'pdf' || $jenis_print == 'keseluruhan') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }

    public function koreksiPenerimaan(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $spasi = $request->spasi;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        $koreksi = DB::table('trkasout_ppkd as a')
            ->where(function ($query) use ($pilihan, $tgl, $periode1, $periode2) {
                // PILIHAN PER TANGGAL
                if ($pilihan == '1') {
                    $query->whereRaw("a.tanggal=?", [$tgl]);
                }
                //PILIHAN PER PERIODE
                elseif ($pilihan == '2') {
                    $query->whereRaw("a.tanggal between ? and ?", [$periode1, $periode2]);
                }
            })
            ->orderBy('tanggal')
            ->orderBy('no')
            ->get();

        $koreksi_lalu = DB::table('trkasout_ppkd as a')
            ->selectRaw("sum(a.nilai) as nilai")
            ->where(function ($query) use ($pilihan, $tgl, $periode1, $periode2) {
                // PILIHAN PER TANGGAL
                if ($pilihan == '1') {
                    $query->whereRaw("a.tanggal<?", [$tgl]);
                }
                //PILIHAN PER PERIODE
                elseif ($pilihan == '2') {
                    $query->whereRaw("a.tanggal<?", [$periode1]);
                }
            })
            ->first();

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal' => $tgl,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_koreksi' => $koreksi,
            'koreksi_lalu' => $koreksi_lalu->nilai,
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
        ];

        $view = view('bud.laporan_bendahara.cetak.koreksi_penerimaan')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function harianKasda(Request $request)
    {
        ini_set('max_execution_time', -1);
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $spasi = $request->spasi;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $bulan = $request->bulan;

        if ($tgl == '2022-01-01' || $tgl == '2022-1-1') {
            $saldoawal = "SELECT '2022-01-01' as urut,0 as urut1,0 as kode,'0' as nomor,uraian,nilai as masuk,0 as keluar from buku_kas
		                  UNION ALL";
        } else {
            $saldoawal = "";
        }

        if ($tgl != '2022-01-02' || $tgl != '2022-1-2') {
            $saldoawals = "SELECT '2022-01-01' as urut,0 as urut1,0 as kode,'0' as nomor,uraian,nilai as masuk,0 as keluar from buku_kas
		                  UNION ALL";
        } else {
            $saldoawals = "";
        }

        // simpan di helper harian_kasda

        // TERBARU PERMINTAAN KAK ENI 09/05/2023 PERBULAN ATAU PER PERIODE

        if ($pilihan == '1') {
            // BULAN
            $x = "MONTH(tgl_kas_bud)<'$bulan'";
            $y = "MONTH(a.tgl_kas)<'$bulan'";
            $z = "MONTH(tanggal)<'$bulan'";

            $a = "MONTH(tgl_kas_bud)='$bulan'";
            $b = "MONTH(a.tgl_kas)='$bulan'";
            $c = "MONTH(tanggal)='$bulan'";

            $e = "MONTH(tanggal)<='$bulan'";
        } else {
            // PERIODE
            $x = "tgl_kas_bud<'$periode1'";
            $y = "a.tgl_kas<'$periode1'";
            $z = "tanggal<'$periode1'";

            $a = "tgl_kas_bud BETWEEN '$periode1' AND '$periode2'";
            $b = "a.tgl_kas BETWEEN '$periode1' AND '$periode2'";
            $c = "tanggal BETWEEN '$periode1' AND '$periode2'";

            $e = "tanggal<='$periode1'";
        }

        $kas_kasda_lalu1 = collect(DB::select("SELECT SUM(masuk)as masuk, sum(keluar)as keluar FROM (
			SELECT tgl_kas_bud as urut,
            no_kas_bud as urut1,
            1 as kode,
            no_sp2d as nomor,a.keperluan as uraian,0 as masuk ,sum(b.nilai) as keluar from trhsp2d a inner join trdspp b
            on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd where status_bud=1 and $x
            group by tgl_kas_bud,no_kas_bud,no_sp2d,a.keperluan
            UNION ALL
            $saldoawals
            -- LAIN-LAIN PENDAPATAN ASLI DAERAH YANG SAH
            SELECT a.tgl_kas,a.no_kas,3 as kode,a.no_kas,a.keterangan,SUM(rupiah) as masuk,0 as keluar
            FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas and a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            WHERE LEFT(b.kd_rek6,1) IN ('5','1') and pot_khusus=3  and $y
            GROUP BY a.tgl_kas,a.no_kas,keterangan

            UNION ALL
            -- 4104	LAIN-LAIN PAD YANG SAH
            -- 4102	RETRIBUSI DAERAH
            -- 4103	HASIL PENGELOLAAN KEKAYAAN DAERAH YANG DIPISAHKAN
            -- 4201	PENDAPATAN TRANSFER PEMERINTAH PUSAT
            -- 4301	PENDAPATAN HIBAH
            -- 4101	PAJAK DAERAH
            SELECT a.tgl_kas,a.no_kas,3 as kode,a.no_kas,a.keterangan,SUM(rupiah) as masuk,0 as keluar
                            FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas and a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                            LEFT JOIN ms_rek3 c ON LEFT(b.kd_rek6,4)=c.kd_rek3
                            WHERE LEFT(b.kd_rek6,1) IN ('4') and  b.kd_rek6 not in ('420101040001','420101040002','420101040003','410416010001') and $y
                            GROUP BY a.tgl_kas,a.no_kas,keterangan

            UNION ALL
            -- CP
            SELECT  a.tgl_kas,a.no_kas,2 as kode,a.no_kas,a.keterangan,SUM(rupiah) as masuk,0 as keluar
            FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas and a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            WHERE LEFT(b.kd_rek6,1) IN ('5','1','2') and pot_khusus<>3 and $y
            GROUP BY a.tgl_kas,a.no_kas,keterangan

            UNION ALL
            --PENGELUARAN NON SP2D
            SELECT tanggal,nomor,3,CAST(nomor as VARCHAR),keterangan,0,nilai FROM pengeluaran_non_sp2d x where $z

            UNION ALL
            -- RESTITUSI
            SELECT tgl_kas,a.no_kas,3,a.no_kas,keterangan,0,rupiah
            FROM trdrestitusi b inner join trhrestitusi a on a.kd_skpd=b.kd_skpd and a.no_kas=b.no_kas and a.no_sts=b.no_sts WHERE a.jns_trans=3 and $y

            UNION ALL
            -- KOREKSI
            SELECT tanggal,[no],3,[no],keterangan,nilai,0 FROM	 trkasout_ppkd w where $z

            UNION ALL
            -- KOREKSI PENGELUARAN
            SELECT tanggal,[no],2,[no],keterangan,0,nilai FROM	 trkoreksi_pengeluaran w where $z

            UNION ALL
            -- DEPOSITO
            SELECT tanggal,nomor,3,cast(nomor as VARCHAR),keterangan,nilai,0 FROM penerimaan_non_sp2d w WHERE w.jenis='1' and $z

            UNION ALL
            -- PENERIMAAN NON SP2D
            SELECT tanggal,nomor,3,cast(nomor as VARCHAR),keterangan,nilai,0 FROM penerimaan_non_sp2d w WHERE w.jenis='2' and $z

            UNION ALL
            -- KOREKSI PENERIMAAN
            SELECT tanggal,nomor,3,cast(nomor as VARCHAR),keterangan,nilai,0 FROM tkoreksi_penerimaan w WHERE w.jenis='1' and $z
            )zz
            "))
            ->first();;


        $kas_kasda1 = DB::select("SELECT 'sp2d' as jenis,c.jns_spp,c.jns_beban, tgl_kas_bud as urut,no_kas_bud as urut1, 1 as kode,
            no_sp2d as nomor,a.keperluan as uraian,0 as masuk ,sum(b.nilai) as keluar from trhsp2d a
            inner join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
            inner join trhspp c on a.no_spp=c.no_spp and a.kd_skpd=c.kd_skpd
            where status_bud=1 and $a
            group by tgl_kas_bud,no_kas_bud,no_sp2d,a.keperluan,c.jns_spp,c.jns_beban
            UNION ALL
            $saldoawal
            -- LAIN-LAIN PENDAPATAN ASLI DAERAH YANG SAH
            SELECT 'LLPADYS' as jenis,'' as jns_spp, '' as jns_beban, a.tgl_kas,a.no_kas,3 as kode,a.no_kas,a.keterangan,SUM(rupiah) as masuk,0 as keluar
            FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
            WHERE LEFT(b.kd_rek6,1) IN ('5','1') and pot_khusus=3  and $b
            GROUP BY a.tgl_kas,a.no_kas,keterangan

            UNION ALL
            -- 4104	LAIN-LAIN PAD YANG SAH
            -- 4102	RETRIBUSI DAERAH
            -- 4103	HASIL PENGELOLAAN KEKAYAAN DAERAH YANG DIPISAHKAN
            -- 4201	PENDAPATAN TRANSFER PEMERINTAH PUSAT
            -- 4301	PENDAPATAN HIBAH
            -- 4101	PAJAK DAERAH
            SELECT 'PAD' as jenis,'' as jns_spp, '' as jns_beban, a.tgl_kas,a.no_kas,3 as kode,a.no_kas,a.keterangan,SUM(rupiah) as masuk,0 as keluar
                            FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas and a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                            LEFT JOIN ms_rek3 c ON LEFT(b.kd_rek6,4)=c.kd_rek3
                            WHERE LEFT(b.kd_rek6,1) IN ('4') and  b.kd_rek6 not in ('420101040001','420101040002','420101040003','410416010001') and $b
                            GROUP BY a.tgl_kas,a.no_kas,keterangan

            UNION ALL
            -- CP
            SELECT  'CP' as jenis,'' as jns_spp, '' as jns_beban, a.tgl_kas,a.no_kas,2 as kode,a.no_kas,a.keterangan,SUM(rupiah) as masuk,0 as keluar
            FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas and a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            WHERE LEFT(b.kd_rek6,1) IN ('5','1','2') and pot_khusus<>3 and $b
            GROUP BY a.tgl_kas,a.no_kas,keterangan

            UNION ALL
            --PENGELUARAN NON SP2D
            SELECT 'keluarnonsp2d' as jenis,'' as jns_spp, '' as jns_beban, tanggal,nomor,3,CAST(nomor as VARCHAR),keterangan,0,nilai FROM pengeluaran_non_sp2d x where $c

            UNION ALL
            -- RESTITUSI
            SELECT 'restitusi' as jenis,'' as jns_spp, '' as jns_beban, tgl_kas,a.no_kas,3,a.no_kas,keterangan,0,rupiah
            FROM trdrestitusi b inner join trhrestitusi a on a.kd_skpd=b.kd_skpd and a.no_kas=b.no_kas and a.no_sts=b.no_sts WHERE a.jns_trans=3 and $b

            UNION ALL
            -- KOREKSI
            SELECT 'koreksi' as jenis,'' as jns_spp, '' as jns_beban, tanggal,[no],3,[no],keterangan,nilai,0 FROM	 trkasout_ppkd w where $c

            UNION ALL
            -- KOREKSI PENGELUARAN
            SELECT 'koreksipengeluaran' as jenis,'' as jns_spp, '' as jns_beban, tanggal,[no],2,[no],keterangan,0,nilai FROM	 trkoreksi_pengeluaran w where $c

            UNION ALL
            -- DEPOSITO
            SELECT'deposito' as jenis,'' as jns_spp, '' as jns_beban, tanggal,nomor,3,cast(nomor as VARCHAR),keterangan,nilai,0 FROM penerimaan_non_sp2d w WHERE w.jenis='1' and $c

            UNION ALL
            -- PENERIMAAN NON SP2D
            SELECT 'terimanonsp2d' as jenis,'' as jns_spp, '' as jns_beban, tanggal,nomor,3,cast(nomor as VARCHAR),keterangan,nilai,0 FROM penerimaan_non_sp2d w WHERE w.jenis='2' and $c

            UNION ALL
            -- KOREKSI PENERIMAAN
            SELECT 'koreksiterima' as jenis,'' as jns_spp, '' as jns_beban, tanggal,nomor,3,cast(nomor as VARCHAR),keterangan,nilai,0 FROM tkoreksi_penerimaan w WHERE w.jenis='1' and $c
            ORDER BY urut,urut1");

        $setara_kas = collect(DB::select("SELECT isnull(sum(nilai),0) as nilai FROM pengeluaran_non_sp2d w WHERE w.nomor='43210' and $e"))
            ->first()
            ->nilai;

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'tanggal' => $tgl,
            'data_kasda' => $kas_kasda1,
            'kasda_lalu' => $kas_kasda_lalu1,
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
            'pilihan' => $pilihan,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'bulan' => $bulan,
            'setara_kas' => $setara_kas
        ];

        $judul = 'KAS HARIAN KASDA';

        $view = view('bud.laporan_bendahara.cetak.harian_kasda')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }

    public function uyhd(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $spasi = $request->spasi;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        // '4101', permintaan kak afli tanggal 25 10 2023 atas izin bu elvi

        if ($pilihan == '1') {
            $uyhd = DB::select("SELECT * from(
					SELECT
						1 as urut,
						''no_sts,
						''kd_skpd,
						f.nm_skpd,
						''kd_sub_kegiatan,
						''kd_rek6,
						b.no_kas,
						''tgl_kas,
						ISNULL(e.nm_pengirim, '') nm_pengirim,
						''nm_rek6,
						0 rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					LEFT JOIN ms_pengirim e ON b.sumber = e.kd_pengirim and e.kd_skpd=b.kd_skpd
					INNER JOIN ms_skpd f ON a.kd_skpd = f.kd_skpd
					WHERE b.tgl_kas=? AND a.kd_skpd !='1.20.15.17'  AND LEFT(a.kd_rek6,4) IN ('4101','4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND LEFT(a.kd_rek6,6) NOT IN ('410416') AND a.kd_rek6 NOT IN ('420101040001')
					and b.keterangan like '%(UYHD)%'
					GROUP BY b.no_kas,nm_pengirim, f.nm_skpd

					UNION ALL
					SELECT
						2 as urut,
						b.no_sts,
						a.kd_skpd,
						'' nm_skpd,
						a.kd_sub_kegiatan,
						a.kd_rek6,
						b.no_kas,
						b.tgl_kas,
						'' nm_pengirim,
						c.nm_rek6,
						a.rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					LEFT JOIN ms_pengirim e ON b.sumber = e.kd_pengirim and e.kd_skpd=b.kd_skpd
					WHERE b.tgl_kas=? AND a.kd_skpd !='1.20.15.17' AND LEFT(a.kd_rek6,4) IN ('4101','4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND LEFT(a.kd_rek6,6) NOT IN ('410416') AND a.kd_rek6 NOT IN ('420101040001')
					and b.keterangan like '%(UYHD)%'
					UNION ALL
					SELECT
						1 as urut,
						''no_sts,
						''kd_skpd,
						f.nm_skpd,
						''kd_sub_kegiatan,
						''kd_rek6,
						b.no_kas,
						''tgl_kas,
						''nm_pengirim,
						''nm_rek6,
						0 rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					INNER JOIN ms_skpd f ON a.kd_skpd = f.kd_skpd
					WHERE b.tgl_kas=? AND a.kd_skpd ='1.20.15.17' AND LEFT(a.kd_rek6,4) IN ('4101','4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND a.kd_rek6 NOT IN ('420101040001')
					and b.keterangan like '%(UYHD)%'
					GROUP BY b.no_kas,f.nm_skpd
					UNION ALL
					SELECT
						2 as urut,
						b.no_sts,
						a.kd_skpd,
						'' nm_skpd,
						a.kd_sub_kegiatan,
						a.kd_rek6,
						b.no_kas,
						b.tgl_kas,
						'' nm_pengirim,
						b.keterangan nm_rek6,
						a.rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas  AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					WHERE b.tgl_kas=? AND a.kd_skpd ='1.20.15.17' AND LEFT(a.kd_rek6,4) IN ('4101','4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND a.kd_rek6 NOT IN ('420101040001')
					and b.keterangan like '%(UYHD)%'

					UNION ALL
					SELECT
							1 AS urut,
							'' no_sts,
							'' kd_skpd,
							nm_skpd,
							'' kd_sub_kegiatan,
							'' kd_rek6,
							[no] as no_kas,
							'' tgl_kas,
							'' nm_pengirim,
							'' nm_rek6,
							0 rupiah
						FROM
							trkasout_ppkd
						WHERE
							tanggal = ? AND LEFT(kd_rek,4) IN ('4102','4103','4104','4201','4202') AND LEFT(kd_rek,5) NOT IN ('41407') AND kd_rek NOT IN ('420101040001')
						UNION ALL
						SELECT
								2 AS urut,
								[no] as no_sts,
								kd_skpd,
								'' nm_skpd,
								''kd_sub_kegiatan,
								kd_rek kd_rek6,
								[no] no_kas,
								[tanggal] tgl_kas,
								'' nm_pengirim,
								keterangan+' '+nm_rek nm_rek6,
								nilai rupiah
							FROM
							trkasout_ppkd
							WHERE
							tanggal = ?
							AND LEFT(kd_rek,4) IN ('4102','4103','4104','4201','4202') AND LEFT(kd_rek,5) NOT IN ('41407') AND kd_rek NOT IN ('420101040001')
					) a

					order by cast(no_kas as int),urut", [$tgl, $tgl, $tgl, $tgl, $tgl, $tgl]);
        } elseif ($pilihan == '2') {
            $uyhd = DB::select("SELECT * from(
					SELECT
						1 as urut,
						''no_sts,
						''kd_skpd,
						f.nm_skpd,
						''kd_sub_kegiatan,
						''kd_rek6,
						b.no_kas,
						''tgl_kas,
						ISNULL(e.nm_pengirim, '') nm_pengirim,
						''nm_rek6,
						0 rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					LEFT JOIN ms_pengirim e ON b.sumber = e.kd_pengirim and e.kd_skpd=b.kd_skpd
					INNER JOIN ms_skpd f ON a.kd_skpd = f.kd_skpd
					WHERE b.tgl_kas BETWEEN ? AND ? AND a.kd_skpd !='1.20.15.17'  AND LEFT(a.kd_rek6,4) IN ('4101','4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND LEFT(a.kd_rek6,6) NOT IN ('410416') AND a.kd_rek6 NOT IN ('420101040001')
					and b.keterangan like '%(UYHD)%'
					GROUP BY b.no_kas,nm_pengirim, f.nm_skpd
					UNION ALL
					SELECT
						2 as urut,
						b.no_sts,
						a.kd_skpd,
						'' nm_skpd,
						a.kd_sub_kegiatan,
						a.kd_rek6,
						b.no_kas,
						b.tgl_kas,
						'' nm_pengirim,
						c.nm_rek6,
						a.rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					LEFT JOIN ms_pengirim e ON b.sumber = e.kd_pengirim and e.kd_skpd=b.kd_skpd
					WHERE b.tgl_kas BETWEEN ? AND ? AND a.kd_skpd !='1.20.15.17' AND LEFT(a.kd_rek6,4) IN ('4101','4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND LEFT(a.kd_rek6,6) NOT IN ('410416') AND a.kd_rek6 NOT IN ('420101040001')
					and b.keterangan like '%(UYHD)%'
					UNION ALL
					SELECT
						1 as urut,
						''no_sts,
						''kd_skpd,
						f.nm_skpd,
						''kd_sub_kegiatan,
						''kd_rek6,
						b.no_kas,
						''tgl_kas,
						''nm_pengirim,
						''nm_rek6,
						0 rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					INNER JOIN ms_skpd f ON a.kd_skpd = f.kd_skpd
					WHERE b.tgl_kas BETWEEN ? AND ? AND a.kd_skpd ='1.20.15.17' AND LEFT(a.kd_rek6,4) IN ('4101','4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND a.kd_rek6 NOT IN ('420101040001')
					and b.keterangan like '%(UYHD)%'
					GROUP BY b.no_kas,f.nm_skpd
					UNION ALL
					SELECT
						2 as urut,
						b.no_sts,
						a.kd_skpd,
						'' nm_skpd,
						a.kd_sub_kegiatan,
						a.kd_rek6,
						b.no_kas,
						b.tgl_kas,
						'' nm_pengirim,
						b.keterangan nm_rek6,
						a.rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas  AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					WHERE b.tgl_kas BETWEEN ? AND ? AND a.kd_skpd ='1.20.15.17' AND LEFT(a.kd_rek6,4) IN ('4101','4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND a.kd_rek6 NOT IN ('420101040001')
					and b.keterangan like '%(UYHD)%'

					UNION ALL
					SELECT
							1 AS urut,
							'' no_sts,
							'' kd_skpd,
							nm_skpd,
							'' kd_sub_kegiatan,
							'' kd_rek6,
							[no] as no_kas,
							'' tgl_kas,
							'' nm_pengirim,
							'' nm_rek6,
							0 rupiah
						FROM
							trkasout_ppkd
						WHERE
							tanggal BETWEEN ? AND ? AND LEFT(kd_rek,4) IN ('4102','4103','4104','4201','4202') AND LEFT(kd_rek,5) NOT IN ('41407') AND kd_rek NOT IN ('420101040001','410412010010')
						UNION ALL
						SELECT
								2 AS urut,
								[no] as no_sts,
								kd_skpd,
								'' nm_skpd,
								''kd_sub_kegiatan,
								kd_rek kd_rek6,
								[no] no_kas,
								[tanggal] tgl_kas,
								'' nm_pengirim,
								keterangan+' '+nm_rek nm_rek6,
								nilai rupiah
							FROM
							trkasout_ppkd
							WHERE
							tanggal BETWEEN ? AND ?
							AND LEFT(kd_rek,4) IN ('4102','4103','4104','4201','4202') AND LEFT(kd_rek,5) NOT IN ('41407') AND kd_rek NOT IN ('420101040001','410412010010')
					) a

					order by cast(no_kas as int),urut", [$periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2, $periode1, $periode2,]);
        }

        if ($pilihan == '1') {
            $uyhd_lalu = collect(DB::select("SELECT sum(rupiah) as nilai from(
					SELECT
						1 as urut,
						''no_sts,
						''kd_skpd,
						f.nm_skpd,
						''kd_sub_kegiatan,
						''kd_rek6,
						b.no_kas,
						''tgl_kas,
						ISNULL(e.nm_pengirim, '') nm_pengirim,
						''nm_rek6,
						0 rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					LEFT JOIN ms_pengirim e ON b.sumber = e.kd_pengirim and e.kd_skpd=b.kd_skpd
					INNER JOIN ms_skpd f ON a.kd_skpd = f.kd_skpd
					WHERE b.tgl_kas <=? AND a.kd_skpd !='1.20.15.17'  AND LEFT(a.kd_rek6,4) IN ('4101','4102')
					and b.keterangan like '%(UYHD)%'
					GROUP BY b.no_kas,nm_pengirim, f.nm_skpd
					UNION ALL
					SELECT
						2 as urut,
						b.no_sts,
						a.kd_skpd,
						'' nm_skpd,
						a.kd_sub_kegiatan,
						a.kd_rek6,
						b.no_kas,
						b.tgl_kas,
						'' nm_pengirim,
						c.nm_rek6,
						a.rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					LEFT JOIN ms_pengirim e ON b.sumber = e.kd_pengirim and e.kd_skpd=b.kd_skpd
					WHERE b.tgl_kas<=? AND a.kd_skpd !='1.20.15.17' AND LEFT(a.kd_rek6,4) IN ('4101','4102')
					and b.keterangan like '%(UYHD)%'

					UNION ALL
					SELECT
						1 as urut,
						''no_sts,
						''kd_skpd,
						f.nm_skpd,
						''kd_sub_kegiatan,
						''kd_rek6,
						b.no_kas,
						''tgl_kas,
						''nm_pengirim,
						''nm_rek6,
						0 rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					INNER JOIN ms_skpd f ON a.kd_skpd = f.kd_skpd
					WHERE b.tgl_kas<=? AND a.kd_skpd ='1.20.15.17' AND LEFT(a.kd_rek6,4) IN ('4101','4102')
					and b.keterangan like '%(UYHD)%'
					GROUP BY b.no_kas,f.nm_skpd
					UNION ALL
					SELECT
						2 as urut,
						b.no_sts,
						a.kd_skpd,
						'' nm_skpd,
						a.kd_sub_kegiatan,
						a.kd_rek6,
						b.no_kas,
						b.tgl_kas,
						'' nm_pengirim,
						b.keterangan nm_rek6,
						a.rupiah
					FROM
						trdkasin_ppkd a
					INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas  AND a.kd_skpd=b.kd_skpd
					INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
					WHERE b.tgl_kas<=? AND a.kd_skpd ='1.20.15.17' AND LEFT(a.kd_rek6,4) IN ('4101','4102')
					and b.keterangan like '%(UYHD)%'

					UNION ALL
					SELECT
							1 AS urut,
							'' no_sts,
							'' kd_skpd,
							nm_skpd,
							'' kd_sub_kegiatan,
							'' kd_rek6,
							[no] as no_kas,
							'' tgl_kas,
							'' nm_pengirim,
							'' nm_rek6,
							0 rupiah
						FROM
							trkasout_ppkd
						WHERE
							tanggal <= ? AND LEFT(kd_rek,4) IN ('4102')
						UNION ALL
						SELECT
								2 AS urut,
								[no] as no_sts,
								kd_skpd,
								'' nm_skpd,
								''kd_sub_kegiatan,
								kd_rek kd_rek6,
								[no] no_kas,
								[tanggal] tgl_kas,
								'' nm_pengirim,
								keterangan+' '+nm_rek nm_rek6,
								nilai rupiah
							FROM
							trkasout_ppkd
							WHERE
							tanggal <= ?
							AND LEFT(kd_rek,4) IN ('4102')
					) a", [$tgl, $tgl, $tgl, $tgl, $tgl, $tgl]))->first();
        } elseif ($pilihan == '2') {
            $uyhd_lalu = collect(DB::select("SELECT SUM(rupiah) nilai from(
			SELECT
				1 as urut,
				''no_sts,
				''kd_skpd,
				f.nm_skpd,
				''kd_sub_kegiatan,
				''kd_rek6,
				b.no_kas,
				''tgl_kas,
				ISNULL(e.nm_pengirim, '') nm_pengirim,
				''nm_rek6,
				0 rupiah
			FROM
				trdkasin_ppkd a
			INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
			INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
			LEFT JOIN ms_pengirim e ON b.sumber = e.kd_pengirim and e.kd_skpd=b.kd_skpd
			INNER JOIN ms_skpd f ON a.kd_skpd = f.kd_skpd
			WHERE b.tgl_kas < ? AND a.kd_skpd !='1.20.15.17'  AND LEFT(a.kd_rek6,4) IN ('4101','4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND LEFT(a.kd_rek6,6) NOT IN ('410412','410416') AND a.kd_rek6 NOT IN ('420101040001')
			and b.keterangan like '%(UYHD)%'
			GROUP BY b.no_kas,nm_pengirim, f.nm_skpd
			UNION ALL
			SELECT
				2 as urut,
				b.no_sts,
				a.kd_skpd,
				'' nm_skpd,
				a.kd_sub_kegiatan,
				a.kd_rek6,
				b.no_kas,
				b.tgl_kas,
				'' nm_pengirim,
				c.nm_rek6,
				a.rupiah
			FROM
				trdkasin_ppkd a
			INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
			INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
			LEFT JOIN ms_pengirim e ON b.sumber = e.kd_pengirim and e.kd_skpd=b.kd_skpd
			WHERE b.tgl_kas < ? AND a.kd_skpd !='1.20.15.17' AND LEFT(a.kd_rek6,4) IN ('4101','4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND LEFT(a.kd_rek6,6) NOT IN ('410412','410416') AND a.kd_rek6 NOT IN ('420101040001')
			and b.keterangan like '%(UYHD)%'
			UNION ALL
			SELECT
				1 as urut,
				''no_sts,
				''kd_skpd,
				f.nm_skpd,
				''kd_sub_kegiatan,
				''kd_rek6,
				b.no_kas,
				''tgl_kas,
				''nm_pengirim,
				''nm_rek6,
				0 rupiah
			FROM
				trdkasin_ppkd a
			INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas AND a.kd_skpd=b.kd_skpd
			INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
			INNER JOIN ms_skpd f ON a.kd_skpd = f.kd_skpd
			WHERE b.tgl_kas < ? AND a.kd_skpd ='1.20.15.17' AND LEFT(a.kd_rek6,4) IN ('4101','4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND a.kd_rek6 NOT IN ('420101040001')
			and b.keterangan like '%(UYHD)%'
			GROUP BY b.no_kas,f.nm_skpd
			UNION ALL
			SELECT
				2 as urut,
				b.no_sts,
				a.kd_skpd,
				'' nm_skpd,
				a.kd_sub_kegiatan,
				a.kd_rek6,
				b.no_kas,
				b.tgl_kas,
				'' nm_pengirim,
				b.keterangan nm_rek6,
				a.rupiah
			FROM
				trdkasin_ppkd a
			INNER JOIN trhkasin_ppkd b ON a.no_kas = b.no_kas  AND a.kd_skpd=b.kd_skpd
			INNER JOIN ms_rek6 c ON a.kd_rek6 = c.kd_rek6
			WHERE b.tgl_kas < ? AND a.kd_skpd ='1.20.15.17' AND LEFT(a.kd_rek6,4) IN ('4101','4102','4103','4104','4201','4202') AND LEFT(a.kd_rek6,5) NOT IN ('41407') AND a.kd_rek6 NOT IN ('420101040001')
			and b.keterangan like '%(UYHD)%'

			UNION ALL
			SELECT
					1 AS urut,
					'' no_sts,
					'' kd_skpd,
					nm_skpd,
					'' kd_sub_kegiatan,
					'' kd_rek6,
					[no] as no_kas,
					'' tgl_kas,
					'' nm_pengirim,
					'' nm_rek6,
					0 rupiah
				FROM
					trkasout_ppkd
				WHERE
					tanggal < ? AND LEFT(kd_rek,4) IN ('4102','4103','4104','4201','4202') AND LEFT(kd_rek,5) NOT IN ('41407') AND kd_rek NOT IN ('420101040001','410412010010')
				UNION ALL
				SELECT
						2 AS urut,
						[no] as no_sts,
						kd_skpd,
						'' nm_skpd,
						''kd_sub_kegiatan,
						kd_rek kd_rek6,
						[no] no_kas,
						[tanggal] tgl_kas,
						'' nm_pengirim,
						keterangan+' '+nm_rek nm_rek6,
						nilai rupiah
					FROM
					trkasout_ppkd
					WHERE
					tanggal < ?
					AND LEFT(kd_rek,4) IN ('4102','4103','4104','4201','4202') AND LEFT(kd_rek,5) NOT IN ('41407') AND kd_rek NOT IN ('420101040001','410412010010')
			) a", [$periode1, $periode1, $periode1, $periode1, $periode1, $periode1]))->first();
        }


        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal' => $tgl,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_uyhd' => $uyhd,
            'uyhd_lalu' => $uyhd_lalu->nilai,
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
        ];

        $view = view('bud.laporan_bendahara.cetak.uyhd')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function koreksiPengeluaran(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $spasi = $request->spasi;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        $koreksi = DB::table('trkoreksi_pengeluaran')
            ->where(function ($query) use ($pilihan, $tgl, $periode1, $periode2) {
                // PILIHAN TANGGAL
                if ($pilihan == '1') {
                    $query->where('tanggal', $tgl);
                } elseif ($pilihan == '2') {
                    $query->whereRaw("tanggal between ? and ?", [$periode1, $periode2]);
                }
            })
            ->orderBy('tanggal')
            ->orderBy('no')
            ->get();

        $koreksi_lalu = DB::table('trkoreksi_pengeluaran')
            ->selectRaw("sum(nilai) as nilai")
            ->where(function ($query) use ($pilihan, $tgl, $periode1) {
                // PILIHAN TANGGAL
                if ($pilihan == '1') {
                    $query->where('tanggal', '<', $tgl);
                } elseif ($pilihan == '2') {
                    $query->where('tanggal', '<', $periode1);
                }
            })
            ->first();


        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal' => $tgl,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_koreksi' => $koreksi,
            'koreksi_lalu' => $koreksi_lalu->nilai,
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
        ];

        $view = view('bud.laporan_bendahara.cetak.koreksi_pengeluaran')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function koreksiPenerimaan2(Request $request)
    {
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $tgl = $request->tgl;
        $halaman = $request->halaman;
        $spasi = $request->spasi;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;

        $koreksi = DB::table('tkoreksi_penerimaan')
            ->whereIn('jenis', ['1'])
            ->where(function ($query) use ($pilihan, $tgl, $periode1, $periode2) {
                // PILIHAN TANGGAL
                if ($pilihan == '1') {
                    $query->where('tanggal', $tgl);
                } elseif ($pilihan == '2') {
                    $query->whereRaw("tanggal between ? and ?", [$periode1, $periode2]);
                }
            })
            ->get();

        $koreksi_lalu = DB::table('tkoreksi_penerimaan')
            ->whereIn('jenis', ['1'])
            ->selectRaw("sum(nilai) as nilai")
            ->where(function ($query) use ($pilihan, $tgl, $periode1) {
                // PILIHAN TANGGAL
                if ($pilihan == '1') {
                    $query->where('tanggal', '<', $tgl);
                } elseif ($pilihan == '2') {
                    $query->where('tanggal', '<', $periode1);
                }
            })
            ->first();


        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $pilihan,
            'tanggal' => $tgl,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'data_koreksi' => $koreksi,
            'koreksi_lalu' => $koreksi_lalu->nilai,
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
        ];

        $view = view('bud.laporan_bendahara.cetak.koreksi_penerimaan2')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        }
    }

    public function registerSp2d(Request $request)
    {
        $req = $request->all();
        // dd($req);
        $join1 = DB::table('trdspp')
            ->selectRaw("no_spp, sum(nilai) [nilai]")
            ->groupBy('no_spp');

        $register_sp2d = DB::table('trhspm as a')
            ->join('trhsp2d as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                // $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->joinSub($join1, 'c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
            })
            ->selectRaw("a.kd_skpd,a.nm_skpd,a.no_spm,a.tgl_spm,b.tgl_sp2d,b.no_sp2d,b.keperluan,
				(case when a.jns_spp=1 then c.nilai else 0  end)up,
				(case when a.jns_spp=2 then c.nilai else 0  end)gu,
				(case when a.jns_spp=3 then c.nilai else 0  end)tu,
				(case when a.jns_spp=4 then c.nilai else 0  end)gaji,
				(case when a.jns_spp=6 then c.nilai else 0  end)ls,
                (case when a.jns_spp=5 then c.nilai else 0  end)ph3")
            ->where(function ($query) use ($req) {
                if ($req['pilihan'] == '11' || $req['pilihan'] == '12' || $req['pilihan'] == '13') {
                    $query->whereRaw("(b.sp2d_batal IS NULL  OR b.sp2d_batal !=1)");
                } else {
                    $query->whereRaw("(b.sp2d_batal IS NULL  OR b.sp2d_batal !=1) and a.kd_skpd =?", [$req['kd_skpd']]);
                }
            })
            ->where(function ($query) use ($req) {
                if ($req['status'] == '2') {
                    $query->whereRaw("status_bud=?", ['1']);
                } else if ($req['status'] == '3') {
                    $query->whereRaw("no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)");
                } else if ($req['status'] == '4') {
                    $query->whereRaw("no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji) and status_bud <> 1");
                } else if ($req['status'] == '5') {
                    $query->whereRaw("no_sp2d NOT IN (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)");
                }
            })
            ->where(function ($query) use ($req) {
                if (substr($req['pilihan'], -1) == '2') {
                    if ($req['status'] == '2') {
                        $query->whereRaw("MONTH(tgl_kas_bud)=?", [$req['bulan']]);
                    } else {
                        $query->whereRaw("MONTH(tgl_sp2d)=?", [$req['bulan']]);
                    }
                } elseif (substr($req['pilihan'], -1) == '3') {
                    if ($req['status'] == '2') {
                        $query->whereRaw("( tgl_kas_bud between ? and ?)", [$req['periode1'], $req['periode2']]);
                    } else {
                        $query->whereRaw("( tgl_sp2d between ? and ?)", [$req['periode1'], $req['periode2']]);
                    }
                }
            });
        // // ->where(function ($query) use ($req) {
        // //     if ($req['urutan'] == '1') {
        // //         $query->orderBy('tgl_sp2d')->orderBy('no_sp2d');
        // //     } else if ($req['urutan'] == '2') {
        // //         $query->orderByRaw("CAST(no_kas_bud as int)");
        // //     }
        // // })
        // ->get();

        if ($req['urutan'] == '1') {
            $register_sp2d = DB::table(DB::raw("({$register_sp2d->toSql()}) AS sub"))
                ->mergeBindings($register_sp2d)
                ->orderBy('no_sp2d')
                ->get();
        } else if ($req['urutan'] == '2') {
            $register_sp2d = DB::table(DB::raw("({$register_sp2d->toSql()}) AS sub"))
                ->mergeBindings($register_sp2d)
                ->orderByRaw("CAST(no_kas_bud) as int")
                ->get();
        } else if ($req['urutan'] == '3') {
            $register_sp2d = DB::table(DB::raw("({$register_sp2d->toSql()}) AS sub"))
                ->mergeBindings($register_sp2d)
                ->orderByRaw("CAST(tgl_sp2d as date) asc")
                ->get();
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $req['pilihan'],
            'data_awal' => $req,
            'register_sp2d' => $register_sp2d,
            // 'tanggal' => $req['tglcetak'],
            // 'tanda_tangan' => $req['tglcetak'],
        ];

        $view = view('bud.laporan_bendahara.cetak.register_sp2d')->with($data);

        if ($req['jenis_print'] == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', $req['margin_kiri'])
                ->setOption('margin-right', $req['margin_kanan'])
                ->setOption('margin-top', $req['margin_atas'])
                ->setOption('margin-bottom', $req['margin_bawah']);
            return $pdf->stream('laporan.pdf');
        } elseif ($req['jenis_print'] == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Register SP2D' . '.xls"');
            return $view;
        }
    }

    public function realisasiSp2d(Request $request)
    {
        $req = $request->all();

        if (substr($req['pilihan'], -1) == '2') {
            if ($req['status'] == '2') {
                $where3 = "and MONTH(tgl_kas_bud)=?";
            } else {
                $where3 = "and MONTH(tgl_sp2d)=?";
            }
        } elseif (substr($req['pilihan'], -1) == '3') {
            if ($req['status'] == '2') {
                $where3 = "and ( tgl_kas_bud between ? and ?)";
            } else {
                $where3 = "and ( tgl_sp2d between ? and ?)";
            }
        }

        if (substr($req['pilihan'], -1) == '2') {
            $realisasi_sp2d = DB::select("SELECT a.kd_skpd as kode ,a.nm_skpd as nama
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM ms_skpd a
				LEFT JOIN
				(SELECT a.kd_skpd
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM
				(SELECT a.kd_skpd, a.nm_skpd
				,SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','1') THEN a.nilai ELSE 0 END) AS ang
				FROM trdrka a where a.jns_ang=?
				GROUP BY a.kd_skpd, a.nm_skpd)a
				LEFT JOIN
				(SELECT a.kd_skpd
				,SUM(CASE WHEN LEFT(d.kd_rek6,1) in ('5','1') THEN d.nilai ELSE 0 END) AS bel
				FROM trhsp2d a
				INNER JOIN trhspm b ON a.no_spm=b.no_spm AND a.kd_skpd = b.kd_skpd
				INNER JOIN trhspp c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
				INNER JOIN trdspp d ON c.no_spp=d.no_spp AND c.kd_skpd = d.kd_skpd
				WHERE (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
				and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
				$where3
				GROUP BY a.kd_skpd)b
				ON a.kd_skpd=b.kd_skpd)c
				ON a.kd_skpd=c.kd_skpd
				UNION ALL
				SELECT a.kd_org as kode ,a.nm_org as nama
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM ms_organisasi a
				LEFT JOIN
				(SELECT a.kode
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM
				(SELECT LEFT(a.kd_skpd,17) as kode
				,SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','1') THEN a.nilai ELSE 0 END) AS ang
				FROM trdrka a where a.jns_ang=?
				GROUP BY  LEFT(a.kd_skpd,17))a
				LEFT JOIN
				(SELECT LEFT(a.kd_skpd,17) as kode
				,SUM(CASE WHEN LEFT(d.kd_rek6,1) in ('5','1') THEN d.nilai ELSE 0 END) AS bel
				FROM trhsp2d a
				INNER JOIN trhspm b ON a.no_spm=b.no_spm AND a.kd_skpd = b.kd_skpd
				INNER JOIN trhspp c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
				INNER JOIN trdspp d ON c.no_spp=d.no_spp AND c.kd_skpd = d.kd_skpd
				WHERE (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
				and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
				$where3
				GROUP BY LEFT(a.kd_skpd,17))b
				ON a.kode=b.kode)c
				ON a.kd_org=c.kode

				UNION ALL

				SELECT a.kd_bidang_urusan as kode ,a.nm_bidang_urusan as nama
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM ms_bidang_urusan a
				LEFT JOIN
				(SELECT a.kode
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM
				(SELECT LEFT(a.kd_skpd,4) as kode
				,SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','1') THEN a.nilai ELSE 0 END) AS ang
				FROM trdrka a where a.jns_ang=?
				GROUP BY LEFT(a.kd_skpd,4))a
				LEFT JOIN
				(SELECT LEFT(a.kd_skpd,4) as kode
				,SUM(CASE WHEN LEFT(d.kd_rek6,1) in ('5','1') THEN d.nilai ELSE 0 END) AS bel
				FROM trhsp2d a
				INNER JOIN trhspm b ON a.no_spm=b.no_spm AND a.kd_skpd = b.kd_skpd
				INNER JOIN trhspp c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
				INNER JOIN trdspp d ON c.no_spp=d.no_spp AND c.kd_skpd = d.kd_skpd
				WHERE (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
				and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
				$where3
				GROUP BY LEFT(a.kd_skpd,4))b
				ON a.kode=b.kode)c
				ON a.kd_bidang_urusan=c.kode

				UNION ALL

				SELECT a.kd_urusan as kode ,a.nm_urusan as nama
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM ms_urusan a
				LEFT JOIN
				(SELECT a.kode
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM
				(SELECT LEFT(a.kd_skpd,1) as kode
				,SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','1') THEN a.nilai ELSE 0 END) AS ang
				FROM trdrka a where a.jns_ang=?
				GROUP BY LEFT(a.kd_skpd,1))a
				LEFT JOIN
				(SELECT LEFT(a.kd_skpd,1) as kode
				,SUM(CASE WHEN LEFT(d.kd_rek6,1) in ('5','1') THEN d.nilai ELSE 0 END) AS bel
				FROM trhsp2d a
				INNER JOIN trhspm b ON a.no_spm=b.no_spm AND a.kd_skpd = b.kd_skpd
				INNER JOIN trhspp c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
				INNER JOIN trdspp d ON c.no_spp=d.no_spp AND c.kd_skpd = d.kd_skpd
				WHERE (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
				and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
				$where3
				GROUP BY LEFT(a.kd_skpd,1))b
				ON a.kode=b.kode)c
				ON a.kd_urusan=c.kode
				ORDER BY kode", [$req['anggaran'], $req['bulan'], $req['anggaran'], $req['bulan'], $req['anggaran'], $req['bulan'], $req['anggaran'], $req['bulan']]);
        } elseif (substr($req['pilihan'], -1) == '3') {
            $realisasi_sp2d = DB::select("SELECT a.kd_skpd as kode ,a.nm_skpd as nama
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM ms_skpd a
				LEFT JOIN
				(SELECT a.kd_skpd
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM
				(SELECT a.kd_skpd, a.nm_skpd
				,SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','1') THEN a.nilai ELSE 0 END) AS ang
				FROM trdrka a where a.jns_ang=?
				GROUP BY a.kd_skpd, a.nm_skpd)a
				LEFT JOIN
				(SELECT a.kd_skpd
				,SUM(CASE WHEN LEFT(d.kd_rek6,1) in ('5','1') THEN d.nilai ELSE 0 END) AS bel
				FROM trhsp2d a
				INNER JOIN trhspm b ON a.no_spm=b.no_spm AND a.kd_skpd = b.kd_skpd
				INNER JOIN trhspp c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
				INNER JOIN trdspp d ON c.no_spp=d.no_spp AND c.kd_skpd = d.kd_skpd
				WHERE (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
				and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
				$where3
				GROUP BY a.kd_skpd)b
				ON a.kd_skpd=b.kd_skpd)c
				ON a.kd_skpd=c.kd_skpd
				UNION ALL
				SELECT a.kd_org as kode ,a.nm_org as nama
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM ms_organisasi a
				LEFT JOIN
				(SELECT a.kode
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM
				(SELECT LEFT(a.kd_skpd,17) as kode
				,SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','1') THEN a.nilai ELSE 0 END) AS ang
				FROM trdrka a where a.jns_ang=?
				GROUP BY  LEFT(a.kd_skpd,17))a
				LEFT JOIN
				(SELECT LEFT(a.kd_skpd,17) as kode
				,SUM(CASE WHEN LEFT(d.kd_rek6,1) in ('5','1') THEN d.nilai ELSE 0 END) AS bel
				FROM trhsp2d a
				INNER JOIN trhspm b ON a.no_spm=b.no_spm AND a.kd_skpd = b.kd_skpd
				INNER JOIN trhspp c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
				INNER JOIN trdspp d ON c.no_spp=d.no_spp AND c.kd_skpd = d.kd_skpd
				WHERE (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
				and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
				$where3
				GROUP BY LEFT(a.kd_skpd,17))b
				ON a.kode=b.kode)c
				ON a.kd_org=c.kode

				UNION ALL

				SELECT a.kd_bidang_urusan as kode ,a.nm_bidang_urusan as nama
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM ms_bidang_urusan a
				LEFT JOIN
				(SELECT a.kode
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM
				(SELECT LEFT(a.kd_skpd,4) as kode
				,SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','1') THEN a.nilai ELSE 0 END) AS ang
				FROM trdrka a where a.jns_ang=?
				GROUP BY LEFT(a.kd_skpd,4))a
				LEFT JOIN
				(SELECT LEFT(a.kd_skpd,4) as kode
				,SUM(CASE WHEN LEFT(d.kd_rek6,1) in ('5','1') THEN d.nilai ELSE 0 END) AS bel
				FROM trhsp2d a
				INNER JOIN trhspm b ON a.no_spm=b.no_spm AND a.kd_skpd = b.kd_skpd
				INNER JOIN trhspp c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
				INNER JOIN trdspp d ON c.no_spp=d.no_spp AND c.kd_skpd = d.kd_skpd
				WHERE (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
				and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
				$where3
				GROUP BY LEFT(a.kd_skpd,4))b
				ON a.kode=b.kode)c
				ON a.kd_bidang_urusan=c.kode

				UNION ALL

				SELECT a.kd_urusan as kode ,a.nm_urusan as nama
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM ms_urusan a
				LEFT JOIN
				(SELECT a.kode
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM
				(SELECT LEFT(a.kd_skpd,1) as kode
				,SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','1') THEN a.nilai ELSE 0 END) AS ang
				FROM trdrka a where a.jns_ang=?
				GROUP BY LEFT(a.kd_skpd,1))a
				LEFT JOIN
				(SELECT LEFT(a.kd_skpd,1) as kode
				,SUM(CASE WHEN LEFT(d.kd_rek6,1) in ('5','1') THEN d.nilai ELSE 0 END) AS bel
				FROM trhsp2d a
				INNER JOIN trhspm b ON a.no_spm=b.no_spm AND a.kd_skpd = b.kd_skpd
				INNER JOIN trhspp c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
				INNER JOIN trdspp d ON c.no_spp=d.no_spp AND c.kd_skpd = d.kd_skpd
				WHERE (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
				and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
				$where3
				GROUP BY LEFT(a.kd_skpd,1))b
				ON a.kode=b.kode)c
				ON a.kd_urusan=c.kode
				ORDER BY kode", [$req['anggaran'], $req['periode1'], $req['periode2'], $req['anggaran'], $req['periode1'], $req['periode2'], $req['anggaran'], $req['periode1'], $req['periode2'], $req['anggaran'], $req['periode1'], $req['periode2']]);
        } elseif (substr($req['pilihan'], -1) == '1') {
            $realisasi_sp2d = DB::select("SELECT a.kd_skpd as kode ,a.nm_skpd as nama
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM ms_skpd a
				LEFT JOIN
				(SELECT a.kd_skpd
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM
				(SELECT a.kd_skpd, a.nm_skpd
				,SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','1') THEN a.nilai ELSE 0 END) AS ang
				FROM trdrka a where a.jns_ang=?
				GROUP BY a.kd_skpd, a.nm_skpd)a
				LEFT JOIN
				(SELECT a.kd_skpd
				,SUM(CASE WHEN LEFT(d.kd_rek6,1) in ('5','1') THEN d.nilai ELSE 0 END) AS bel
				FROM trhsp2d a
				INNER JOIN trhspm b ON a.no_spm=b.no_spm AND a.kd_skpd = b.kd_skpd
				INNER JOIN trhspp c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
				INNER JOIN trdspp d ON c.no_spp=d.no_spp AND c.kd_skpd = d.kd_skpd
				WHERE (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
				and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
				GROUP BY a.kd_skpd)b
				ON a.kd_skpd=b.kd_skpd)c
				ON a.kd_skpd=c.kd_skpd
				UNION ALL
				SELECT a.kd_org as kode ,a.nm_org as nama
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM ms_organisasi a
				LEFT JOIN
				(SELECT a.kode
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM
				(SELECT LEFT(a.kd_skpd,17) as kode
				,SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','1') THEN a.nilai ELSE 0 END) AS ang
				FROM trdrka a where a.jns_ang=?
				GROUP BY  LEFT(a.kd_skpd,17))a
				LEFT JOIN
				(SELECT LEFT(a.kd_skpd,17) as kode
				,SUM(CASE WHEN LEFT(d.kd_rek6,1) in ('5','1') THEN d.nilai ELSE 0 END) AS bel
				FROM trhsp2d a
				INNER JOIN trhspm b ON a.no_spm=b.no_spm AND a.kd_skpd = b.kd_skpd
				INNER JOIN trhspp c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
				INNER JOIN trdspp d ON c.no_spp=d.no_spp AND c.kd_skpd = d.kd_skpd
				WHERE (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
				and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
				GROUP BY LEFT(a.kd_skpd,17))b
				ON a.kode=b.kode)c
				ON a.kd_org=c.kode

				UNION ALL

				SELECT a.kd_bidang_urusan as kode ,a.nm_bidang_urusan as nama
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM ms_bidang_urusan a
				LEFT JOIN
				(SELECT a.kode
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM
				(SELECT LEFT(a.kd_skpd,4) as kode
				,SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','1') THEN a.nilai ELSE 0 END) AS ang
				FROM trdrka a where a.jns_ang=?
				GROUP BY LEFT(a.kd_skpd,4))a
				LEFT JOIN
				(SELECT LEFT(a.kd_skpd,4) as kode
				,SUM(CASE WHEN LEFT(d.kd_rek6,1) in ('5','1') THEN d.nilai ELSE 0 END) AS bel
				FROM trhsp2d a
				INNER JOIN trhspm b ON a.no_spm=b.no_spm AND a.kd_skpd = b.kd_skpd
				INNER JOIN trhspp c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
				INNER JOIN trdspp d ON c.no_spp=d.no_spp AND c.kd_skpd = d.kd_skpd
				WHERE (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
				and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
				GROUP BY LEFT(a.kd_skpd,4))b
				ON a.kode=b.kode)c
				ON a.kd_bidang_urusan=c.kode

				UNION ALL

				SELECT a.kd_urusan as kode ,a.nm_urusan as nama
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM ms_urusan a
				LEFT JOIN
				(SELECT a.kode
				,ISNULL(ang,0) as ang
				,ISNULL(bel,0) as bel
				FROM
				(SELECT LEFT(a.kd_skpd,1) as kode
				,SUM(CASE WHEN LEFT(a.kd_rek6,1) in ('5','1') THEN a.nilai ELSE 0 END) AS ang
				FROM trdrka a where a.jns_ang=?
				GROUP BY LEFT(a.kd_skpd,1))a
				LEFT JOIN
				(SELECT LEFT(a.kd_skpd,1) as kode
				,SUM(CASE WHEN LEFT(d.kd_rek6,1) in ('5','1') THEN d.nilai ELSE 0 END) AS bel
				FROM trhsp2d a
				INNER JOIN trhspm b ON a.no_spm=b.no_spm AND a.kd_skpd = b.kd_skpd
				INNER JOIN trhspp c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd
				INNER JOIN trdspp d ON c.no_spp=d.no_spp AND c.kd_skpd = d.kd_skpd
				WHERE (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
				and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
				GROUP BY LEFT(a.kd_skpd,1))b
				ON a.kode=b.kode)c
				ON a.kd_urusan=c.kode
				ORDER BY kode", [$req['anggaran'], $req['anggaran'], $req['anggaran'], $req['anggaran']]);
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $req['pilihan'],
            'data_awal' => $req,
            'register_sp2d' => $realisasi_sp2d,
            // 'tanggal' => $req['tglcetak'],
            // 'tanda_tangan' => $req['tglcetak'],
        ];

        $view = view('bud.laporan_bendahara.cetak.realisasi_sp2d')->with($data);

        if ($req['jenis_print'] == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', $req['margin_kiri'])
                ->setOption('margin-right', $req['margin_kanan'])
                ->setOption('margin-top', $req['margin_atas'])
                ->setOption('margin-bottom', $req['margin_bawah']);
            return $pdf->stream('laporan.pdf');
        } elseif ($req['jenis_print'] == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Register SP2D' . '.xls"');
            return $view;
        }
    }

    public function realisasiSkpdSp2d(Request $request)
    {
        $req = $request->all();

        $realisasi1 = DB::table('trdrka')
            ->selectRaw("kd_skpd,nm_skpd,sum(nilai)
                    as anggaran,0 as realisasi ")
            ->whereRaw("left(kd_rek6,1)='5' and kd_sub_kegiatan NOT IN (?,?,?) and jns_ang=? ", ['1.01.02.1.01.53', '1.01.02.1.02.46', '1.01.02.1.03.52', $req['anggaran']])
            ->where(function ($query) use ($req) {
                if ($req['dengan'] == 'true') {
                    $query->whereRaw("LEFT(kd_rek6,1) in ('5') and right(kd_rek6,7) not in ('9999999','8888888')");
                } elseif ($req['tanpa'] == 'true') {
                    $query->whereRaw("LEFT(kd_rek6,1) in ('5') and right(kd_rek6,7) not in ('9999999','8888888')");
                } elseif ($req['dengan_skpkd'] == 'true') {
                    $query->whereRaw("LEFT(kd_rek6,1) in ('5') and right(kd_rek6,7) not in ('9999999','8888888')");
                }
            })
            ->where(function ($query) use ($req) {
                if ($req['dengan_skpkd'] == 'true') {
                } else {
                    $query->whereRaw("
                        kd_rek6 != (
                        CASE WHEN kd_skpd=? THEN ('540203010001')
                            ELSE ('') END
                        )
                        AND
                        kd_rek6 != (
                                CASE WHEN kd_skpd=? THEN ('530101010001')
                                    ELSE ('') END
                                )
                        AND
                        kd_rek6 != (
                                CASE WHEN kd_skpd=? THEN ('540101020001')
                                    ELSE ('') END
                                )
                        AND
                        kd_rek6 != (
                                CASE WHEN kd_skpd=? THEN ('540101010001')
                                    ELSE ('') END
                        )
                        AND
                        kd_rek6 != (
                                CASE WHEN kd_skpd=? THEN ('540203020001')
                                    ELSE ('') END
                        ) ", ['5.02.0.00.0.00.02.0000', '5.02.0.00.0.00.02.0000', '5.02.0.00.0.00.02.0000', '5.02.0.00.0.00.02.0000', '5.02.0.00.0.00.02.0000']);
                }
            })

            ->groupBy('kd_skpd', 'nm_skpd');

        $realisasi2 = DB::table('trhsp2d as a')
            ->join('trdspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->selectRaw("b.kd_bidang as kd_skpd, (select nm_skpd from ms_skpd where kd_skpd=kd_bidang)as nm_skpd,0 as anggaran,sum(b.nilai) as realisasi")
            ->whereRaw("(c.sp2d_batal=0 OR c.sp2d_batal is NULL)
                    and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)")
            ->where(function ($query) use ($req) {
                if ($req['dengan'] == 'true') {
                    $query->whereRaw("LEFT(b.kd_rek6,1) in (?,?) and right(b.kd_rek6,7) not in (?,?)", ['5', '1', '9999999', '8888888']);
                } elseif ($req['tanpa'] == 'true') {
                    $query->whereRaw("LEFT(b.kd_rek6,1) in (?) and right(b.kd_rek6,7) not in (?,?)", ['5', '9999999', '8888888']);
                } elseif ($req['dengan_skpkd'] == 'true') {
                    $query->whereRaw("LEFT(b.kd_rek6,1) in (?) and right(b.kd_rek6,7) not in (?,?)", ['5', '9999999', '8888888']);
                }
            })
            ->where(function ($query) use ($req) {
                if (substr($req['pilihan'], -1) == '1') {
                    if ($req['status'] == '2') {
                        $query->whereRaw("status_bud=1");
                    }
                } elseif (substr($req['pilihan'], -1) == '2') {
                    if ($req['status'] == '2') {
                        $query->whereRaw("MONTH(tgl_kas_bud)=?", [$req['bulan']]);
                    } else {
                        $query->whereRaw("MONTH(tgl_sp2d)=?", [$req['bulan']]);
                    }
                } elseif (substr($req['pilihan'], -1) == '3') {
                    if ($req['status'] == '2') {
                        $query->whereRaw("( tgl_kas_bud between ? and ?)", [$req['periode1'], $req['periode2']]);
                    } else {
                        $query->whereRaw("( tgl_sp2d between ? and ?)", [$req['periode1'], $req['periode2']]);
                    }
                }
            })
            ->where(function ($query) use ($req) {
                if ($req['dengan_skpkd'] == 'true') {
                } else {
                    $query->whereRaw("
                        b.kd_rek6 != (
                        CASE WHEN c.kd_skpd=? THEN ('540203010001')
                            ELSE ('') END
                        )
                        AND
                        b.kd_rek6 != (
                                CASE WHEN c.kd_skpd=? THEN ('530101010001')
                                    ELSE ('') END
                                )
                        AND
                        b.kd_rek6 != (
                                CASE WHEN c.kd_skpd=? THEN ('540101020001')
                                    ELSE ('') END
                                )
                        AND
                        b.kd_rek6 != (
                                CASE WHEN c.kd_skpd=? THEN ('540101010001')
                                    ELSE ('') END
                        )
                        AND
                        b.kd_rek6 != (
                                CASE WHEN c.kd_skpd=? THEN ('540203020001')
                                    ELSE ('') END
                        )  ", ['5.02.0.00.0.00.02.0000', '5.02.0.00.0.00.02.0000', '5.02.0.00.0.00.02.0000', '5.02.0.00.0.00.02.0000', '5.02.0.00.0.00.02.0000']);
                }
            })

            ->groupBy('b.kd_bidang')
            ->union($realisasi1);

        $realisasi = DB::table(DB::raw("({$realisasi2->toSql()}) AS sub"))
            ->selectRaw("kd_skpd as kode,nm_skpd as nama,sum(anggaran)as ang,sum(realisasi)as bel")
            ->mergeBindings($realisasi2)
            ->groupByRaw("kd_skpd,nm_skpd")
            ->orderBy('kd_skpd')
            ->get();

        $blud_soedarso = DB::table('trdrka')
            ->selectRaw("kd_skpd,nm_skpd,sum(nilai)
                    as anggaran,0 as realisasi ")
            ->whereRaw("left(kd_rek6,1)='5' and right(kd_rek6,7) in ('9999999') and jns_ang=? and kd_skpd=?", [$req['anggaran'], ['1.02.0.00.0.00.01.0005']])
            // ->where(function ($query) use ($req) {
            //     if ($req['dengan'] == 'true') {
            //         $query->whereRaw("LEFT(kd_rek6,1) in ('5')");
            //     } elseif ($req['tanpa'] == 'true') {
            //         $query->whereRaw("LEFT(kd_rek6,1) in ('5') and right(kd_rek6,7) in ('9999999')");
            //     }
            // })
            ->groupBy('kd_skpd', 'nm_skpd')
            ->first();

        $blud_rsj = DB::table('trdrka')
            ->selectRaw("kd_skpd,nm_skpd,sum(nilai)
                    as anggaran,0 as realisasi ")
            ->whereRaw("left(kd_rek6,1)=? and right(kd_rek6,7) in (?) and jns_ang=? and kd_skpd=?", ['5', '9999999', $req['anggaran'], ['1.02.0.00.0.00.03.0000']])
            // ->where(function ($query) use ($req) {
            //     if ($req['dengan'] == 'true') {
            //         $query->whereRaw("LEFT(kd_rek6,1) in ('5')");
            //     } elseif ($req['tanpa'] == 'true') {
            //         $query->whereRaw("LEFT(kd_rek6,1) in ('5') and right(kd_rek6,7) in ('9999999')");
            //     }
            // })
            ->groupBy('kd_skpd', 'nm_skpd')
            ->first();

        $bos_dikbud = DB::table('trdrka')
            ->selectRaw("kd_skpd,nm_skpd,sum(nilai)
                    as anggaran,0 as realisasi ")
            ->whereRaw("jns_ang=? and kd_skpd=? and kd_sub_kegiatan IN (?,?,?)", [$req['anggaran'], '1.01.2.22.0.00.01.0000', '1.01.02.1.01.53', '1.01.02.1.02.46', '1.01.02.1.03.52'])
            // ->where(function ($query) use ($req) {
            //     if ($req['dengan'] == 'true') {
            //         $query->whereRaw("LEFT(kd_rek6,1) in ('5')");
            //     } elseif ($req['tanpa'] == 'true') {
            //         $query->whereRaw("LEFT(kd_rek6,1) in ('5') and right(kd_rek6,7) in ('8888888')");
            //     }
            // })
            ->groupBy('kd_skpd', 'nm_skpd')
            ->first();
        // dd([
        //     $blud_soedarso, $blud_rsj, $bos_dikbud
        // ]);

        $bantuan_keuangan = DB::table('trdrka')
            ->selectRaw("kd_skpd,nm_skpd,sum(nilai)
                    as anggaran,0 as realisasi ")
            ->whereRaw("jns_ang=? and kd_skpd=? and kd_rek6 IN (?,?)", [$req['anggaran'], ['5.02.0.00.0.00.02.0000', '540203010001', '540203020001']])
            ->groupBy('kd_skpd', 'nm_skpd')
            ->first();

        $btt = DB::table('trdrka')
            ->selectRaw("kd_skpd,nm_skpd,sum(nilai)
                    as anggaran,0 as realisasi ")
            ->whereRaw("jns_ang=? and kd_skpd=? and kd_rek6 IN (?)", [$req['anggaran'], ['5.02.0.00.0.00.02.0000', '530101010001']])
            ->groupBy('kd_skpd', 'nm_skpd')
            ->first();

        $bagi_hasil = DB::table('trdrka')
            ->selectRaw("kd_skpd,nm_skpd,sum(nilai)
                    as anggaran,0 as realisasi ")
            ->whereRaw("jns_ang=? and kd_skpd=? and kd_rek6 IN (?,?)", [$req['anggaran'], ['5.02.0.00.0.00.02.0000', '540101020001', '540101010001']])
            ->groupBy('kd_skpd', 'nm_skpd')
            ->first();

        $pembiayaan = DB::table('trdrka')
            ->selectRaw("kd_skpd,nm_skpd,sum(nilai)
                    as anggaran,0 as realisasi ")
            ->whereRaw("jns_ang=? and kd_skpd=? and kd_sub_kegiatan=?", [$req['anggaran'], ['5.02.0.00.0.00.02.0000', '5.02.00.0.00.0062']])
            ->groupBy('kd_skpd', 'nm_skpd')
            ->first();

        $realisasi_pembiayaan = DB::table('trhsp2d as a')
            ->join('trdspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as c', function ($join) {
                $join->on('b.no_spp', '=', 'c.no_spp');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })
            ->selectRaw("sum(b.nilai) as nilai")
            ->whereRaw("(c.sp2d_batal=0 OR c.sp2d_batal is NULL)
                    and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)")
            ->where(['a.jns_spp' => '5', 'a.jenis_beban' => '8'])
            ->first();

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $req['pilihan'],
            'data_awal' => $req,
            'realisasi' => $realisasi,
            'tanda_tangan' => DB::table('ms_ttd')
                ->select('nip', 'nama', 'jabatan', 'pangkat')
                ->where(['nip' => $req['ttd']])
                ->whereIn('kode', ['BUD', 'PA'])
                ->first(),
            'tanggal' => now(),
            'dengan' => $req['dengan'],
            'tanpa' => $req['tanpa'],
            'dengan_skpkd' => $req['dengan_skpkd'],
            'blud_soedarso' => isset($blud_soedarso) ? $blud_soedarso->anggaran : 0,
            'blud_rsj' => isset($blud_rsj) ? $blud_rsj->anggaran : 0,
            'bos_dikbud' => isset($bos_dikbud) ? $bos_dikbud->anggaran : 0,
            'bantuan_keuangan' => isset($bantuan_keuangan) ? $bantuan_keuangan->anggaran : 0,
            'btt' => isset($btt) ? $btt->anggaran : 0,
            'bagi_hasil' => isset($bagi_hasil) ? $bagi_hasil->anggaran : 0,
            'pembiayaan' => isset($pembiayaan) ? $pembiayaan->anggaran : 0,
            'realisasi_pembiayaan' => isset($realisasi_pembiayaan->nilai) ? $realisasi_pembiayaan->nilai : 0,
            'nama_anggaran' => DB::table('tb_status_anggaran')
                ->select('nama')
                ->where(['kode' => $req['anggaran']])
                ->first(),
            'tanggal' => $req['tglcetak'],
            // 'tanda_tangan' => $req['tglcetak'],
        ];

        $view = view('bud.laporan_bendahara.cetak.realisasi_skpd_sp2d')->with($data);
        if ($req['jenis_print'] == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('A4')
                ->setOption('margin-left', $req['margin_kiri'])
                ->setOption('margin-right', $req['margin_kanan'])
                ->setOption('margin-top', $req['margin_atas'])
                ->setOption('margin-bottom', $req['margin_bawah']);
            return $pdf->stream('laporan.pdf');
        } elseif ($req['jenis_print'] == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Register SP2D' . '.xls"');
            return $view;
        }
        // return view('bud.laporan_bendahara.cetak.realisasi_skpd_sp2d')->with($data);
    }

    public function formatBpk(Request $request)
    {
        $req = $request->all();

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'data_awal' => DB::select("SELECT cast(SUBSTRING(no_sp2d,0,(len(no_sp2d)-7)) as int) as urut,b.nm_skpd,
                    (SELECT nm_rek2 from ms_rek2 where left(kd_rek6,2)=kd_rek2)as jenis,
                    (SELECT nm_rek3 from ms_rek3 where left(kd_rek6,4)=kd_rek3)as kelompok,
                    (SELECT nm_rek4 from ms_rek4 where left(kd_rek6,6)=kd_rek4)as objek,
                    (SELECT nm_rek5 from ms_rek5 where left(kd_rek6,8)=kd_rek5)as rincianobjek,
                    nm_rek6 as subrincianobjek,
                    no_sp2d,
                    tgl_sp2d,
                    keperluan,
                    nmrekan,
                    npwp,
                    sum(a.nilai) as nilai_sp2d,
                    (select sum(nilai) from trspmpot c where b.no_spm=c.no_spm and c.kd_rek6='210105010001' and kd_trans=a.kd_rek6)as pph21,
                    (select sum(nilai) from trspmpot c where b.no_spm=c.no_spm and c.kd_rek6='210105020001' and kd_trans=a.kd_rek6)as pph22,
                    (select sum(nilai) from trspmpot c where b.no_spm=c.no_spm and c.kd_rek6='210105030001' and kd_trans=a.kd_rek6)as pph23,
                    (select sum(nilai) from trspmpot c where b.no_spm=c.no_spm and c.kd_rek6='210109010001' and kd_trans=a.kd_rek6)as pph4ayat2,
                    (select sum(nilai) from trspmpot c where b.no_spm=c.no_spm and c.kd_rek6='210106010001' and kd_trans=a.kd_rek6)as ppn
                    from trdspp a INNER JOIN trhsp2d b
                    on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
                    where (sp2d_batal<>1 OR sp2d_batal is null) and status_bud = 1
                    GROUP BY
                    b.nm_skpd,
                    kd_rek6,
                    nm_rek6,
                    no_sp2d,
                    no_spm,
                    tgl_sp2d,
                    keperluan,
                    nmrekan,
                    npwp
                    ORDER BY urut asc"),
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['nip' => $req['ttd']])
                ->whereIn('kode', ['BUD'])
                ->first(),
            // 'tanggal' => $req['tglcetak'],
            // 'tanda_tangan' => $req['tglcetak'],
        ];

        $view = view('bud.laporan_bendahara.cetak.format_bpk')->with($data);
        if ($req['jenis_print'] == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', $req['margin_kiri'])
                ->setOption('margin-right', $req['margin_kanan'])
                ->setOption('margin-top', $req['margin_atas'])
                ->setOption('margin-bottom', $req['margin_bawah']);
            return $pdf->stream('laporan.pdf');
        } elseif ($req['jenis_print'] == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Register SP2D' . '.xls"');
            return $view;
        }
    }

    public function sp2dBatal(Request $request)
    {
        $req = $request->all();

        $join1 = DB::table('trdspp')
            ->selectRaw("no_spp, sum(nilai) [nilai]")
            ->groupBy('no_spp');

        $register_sp2d = DB::table('trhspm as a')
            ->join('trhsp2d as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->join('trhspp as d', function ($join) {
                $join->on('a.no_spp', '=', 'd.no_spp');
                $join->on('a.kd_skpd', '=', 'd.kd_skpd');
            })
            ->joinSub($join1, 'c', function ($join) {
                $join->on('a.no_spp', '=', 'c.no_spp');
            })
            ->selectRaw("a.kd_skpd,a.nm_skpd,a.no_spm,a.tgl_spm,a.no_spp,a.tgl_spp,b.tgl_sp2d,b.no_sp2d,b.keperluan,d.tgl_batal,d.ket_batal,c.nilai")
            ->whereRaw("b.sp2d_batal=?", ['1'])
            ->where(function ($query) use ($req) {
                if ($req['pilihan'] == '11' || $req['pilihan'] == '12' || $req['pilihan'] == '13') {
                } else {
                    $query->whereRaw("a.kd_skpd=?", [$req['kd_skpd']]);
                }
            })
            ->where(function ($query) use ($req) {
                if (substr($req['pilihan'], -1) == '2') {
                    if ($req['status'] == '2') {
                        $query->whereRaw("MONTH(tgl_kas_bud)=?", [$req['bulan']]);
                    } else {
                        $query->whereRaw("MONTH(tgl_sp2d)=?", [$req['bulan']]);
                    }
                } elseif (substr($req['pilihan'], -1) == '3') {
                    if ($req['status'] == '2') {
                        $query->whereRaw("( tgl_kas_bud between ? and ?)", [$req['periode1'], $req['periode2']]);
                    } else {
                        $query->whereRaw("( tgl_sp2d between ? and ?)", [$req['periode1'], $req['periode2']]);
                    }
                }
            })
            ->get();

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $req['pilihan'],
            'data_awal' => $req,
            'register_sp2d' => $register_sp2d,
            // 'tanggal' => $req['tglcetak'],
            // 'tanda_tangan' => $req['tglcetak'],
        ];

        $view = view('bud.laporan_bendahara.cetak.register_sp2d_batal')->with($data);

        if ($req['jenis_print'] == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', $req['margin_kiri'])
                ->setOption('margin-right', $req['margin_kanan'])
                ->setOption('margin-top', $req['margin_atas'])
                ->setOption('margin-bottom', $req['margin_bawah']);
            return $pdf->stream('laporan.pdf');
        } elseif ($req['jenis_print'] == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Register SP2D' . '.xls"');
            return $view;
        }
    }

    public function bkuKasda(Request $request)
    {
        $bulan = $request->bulan;
        $jenis = $request->jenis;
        $jenis_print = $request->jenis_print;

        if ($jenis == '1') {
            $query = DB::select("SELECT kd_skpd,nm_skpd,(SELECT terima-keluar as sisa FROM(select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan where (tunai<>1 OR tunai is null)
            and no_sp2d not in (select no_sp2d from  trhsp2d a
                                INNER JOIN trdspp c on a.no_spp=c.no_spp and a.kd_skpd=c.kd_skpd
                                where a.jns_spp='2' and a.kd_skpd= tr_setorsimpanan.kd_skpd
                                and c.kkpd=1 and a.status=1 and month(a.tgl_kas) <= ?
                                ) and z.kd_skpd=kd_skpd
            union all
            SELECT tgl_sp2d AS tgl,no_sp2d AS bku,keperluan as ket,
            sum(b.nilai)-(select kkpd from ms_up where kd_skpd=a.kd_skpd)as jumlah,
            '1' AS jns,a.kd_skpd AS kode FROM trhsp2d a inner join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd where a.no_sp2d IN (SELECT isnull(no_sp2d,'') FROM up_kkpd) and (b.kkpd!='1' or b.kkpd is null) and a.status='1' and a.jns_spp IN ('1','2') and z.kd_skpd=a.kd_skpd GROUP BY a.tgl_sp2d,a.no_sp2d,a.keperluan,a.kd_skpd
            union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' and z.kd_skpd=kd_skpd
            union all
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and  d.pay='BANK' and z.kd_skpd=c.kd_skpd union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            where jns_trans IN ('5') and bank='BNK' and z.kd_skpd=a.kd_skpd
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            union all

            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout
            a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot
            from trspmpot group by no_spm) c on b.no_spm=c.no_spm
            left join
            (
            select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
            where d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
                ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd
            WHERE pay='BANK' and
            (panjar not in ('1') or panjar is null) AND (kkpd <>'1' or kkpd is null) and z.kd_skpd=a.kd_skpd
            union all
            select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a
            join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where a.pay='BANK' and z.kd_skpd=a.kd_skpd group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
            UNION all
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan where z.kd_skpd=kd_skpd union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' and z.kd_skpd=kd_skpd union all
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank where (kkpd is null or kkpd <>'1') and z.kd_skpd=kd_skpd_sumber union all

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' and z.kd_skpd=kd_skpd union all

            SELECT a.tgl_kas AS tgl,a.no_panjar AS bku,a.keterangan as ket,a.nilai-isnull(b.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM tr_panjar a
                left join
                (
                    select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
                    where  d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
                ) b on a.no_panjar=b.no_kas and a.kd_skpd=b.kd_skpd
                where a.pay='BANK' and z.kd_skpd=a.kd_skpd

                union all
                select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode
                from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and z.kd_skpd=a.kd_skpd
                GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd union all
                select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode
                from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
                where jns_trans IN ('5') and bank='BNK' and z.kd_skpd=a.kd_skpd
                GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                ) a
            where month(tgl)<=?) a) as sisa from ms_skpd z group by kd_skpd,nm_skpd", [$bulan, $bulan]);
        } else {
            $query = DB::select("SELECT kd_skpd,nm_skpd,(
            SELECT sum(terima)-sum(keluar) as lalu FROM(
                SELECT
                    a.nilai as terima,
                    0 as keluar
                    FROM tr_terima a INNER JOIN ms_rek6 b
                    ON a.kd_rek6=b.kd_rek6
                    where month(a.tgl_terima) = ?
                    and zz.kd_skpd=a.kd_skpd

                    UNION ALL
                    SELECT
                    0 as terima,
                    sum(y.rupiah) as keluar
                    FROM trhkasin_pkd x
                    INNER JOIN trdkasin_pkd y ON x.no_sts=y.no_sts AND x.kd_skpd=y.kd_skpd AND x.kd_sub_kegiatan=y.kd_sub_kegiatan
                    -- INNER JOIN ms_rek6 z ON y.kd_rek6=z.kd_rek6
                    where month(x.tgl_sts) = ?
                    and (jns_cp ='' OR jns_cp is null) and jns_trans in ('4','2','3') and zz.kd_skpd=y.kd_skpd
                    GROUP BY x.tgl_sts,x.no_sts,x.keterangan,y.kd_rek6,y.no_terima
                )zz
            )as sisa from ms_skpd zz group by kd_skpd,nm_skpd order by kd_skpd", [$bulan, $bulan]);
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'data' => $query,
            'jenis' => $jenis,
            'bulan' => $bulan
        ];

        $view = view('bud.laporan_bendahara.cetak.bku_kasda')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal');
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="BKU KASDA' . '.xls"');
            return $view;
        }
    }

    public function realisasiKkpd(Request $request)
    {
        ini_set('max_execution_time', -1);
        $tgl = $request->tgl;
        $ttd = $request->ttd;
        $jenis_print = $request->jenis_print;
        $pilihan = $request->pilihan;
        $periode1 = $request->periode1;
        $periode2 = $request->periode2;
        $bulan = $request->bulan;
        $anggaran = $request->anggaran;
        $tipe = $request->tipe;
        // dd($tipe);
        // SP2D

        if ($tipe == 'SP2D') {
            $where = $pilihan == '1' ? "and month(c.tgl_sp2d)='$bulan'" : "and c.tgl_sp2d between '$periode1' and '$periode2'";

            $realisasiKkpd = DB::select("SELECT kd_skpd,nm_skpd,
            (select SUM(nilai) from trdrka where z.kd_skpd=kd_skpd and jns_ang=? and left(kd_rek6,2)=?) as anggaran_barjas,
            (select SUM(nilai) from trdrka where z.kd_skpd=kd_skpd and jns_ang=? and left(kd_rek6,4)=?) as anggaran_modal,
            (select SUM(a.nilai) from trdspp a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhsp2d c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd where z.kd_skpd=a.kd_skpd and c.status_bud=? and left(a.kd_rek6,2)=? and a.kkpd=? $where) as realisasi_barjas,
            (select SUM(a.nilai) from trdspp a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhsp2d c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd where z.kd_skpd=a.kd_skpd and c.status_bud=? and left(a.kd_rek6,4)=? and a.kkpd=? $where) as realisasi_modal
            from ms_skpd z", [$anggaran, '52', $anggaran, '5102', '1', '52', '1', '1', '5102', '1']);
        } elseif ($tipe == 'SPJ') {
            $where = $pilihan == '1' ? "and month(b.tgl_bukti)='$bulan'" : "and b.tgl_bukti between '$periode1' and '$periode2'";

            $realisasiKkpd = DB::select("SELECT kd_skpd,nm_skpd,
            (select SUM(nilai) from trdrka where z.kd_skpd=kd_skpd and jns_ang=? and left(kd_rek6,2)=?) as anggaran_barjas,
            (select SUM(nilai) from trdrka where z.kd_skpd=kd_skpd and jns_ang=? and left(kd_rek6,4)=?) as anggaran_modal,
            (select SUM(a.nilai) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where z.kd_skpd=a.kd_skpd and left(a.kd_rek6,2)=? and b.kkpd=? $where) as realisasi_barjas,
            (select SUM(a.nilai) from trdtransout a inner join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where z.kd_skpd=a.kd_skpd and left(a.kd_rek6,4)=? and b.kkpd=? $where) as realisasi_modal
            from ms_skpd z", [$anggaran, '52', $anggaran, '5102', '52', '1', '5102', '1']);
        }

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'tanggal' => $tgl,
            'tanda_tangan' => DB::table('ms_ttd')
                ->where(['kode' => 'BUD', 'nip' => $ttd])
                ->first(),
            'pilihan' => $pilihan,
            'periode1' => $periode1,
            'periode2' => $periode2,
            'bulan' => $bulan,
            'realisasiKkpd' => $realisasiKkpd
        ];

        $judul = 'REALISASI KKPD';

        $view = view('bud.laporan_bendahara.cetak.realisasi_kkpd')->with($data);

        if ($jenis_print == 'pdf') {
            $pdf = PDF::loadHtml($view)
                ->setPaper('legal')
                ->setOrientation('landscape')
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);
            return $pdf->stream('laporan.pdf');
        } elseif ($jenis_print == 'layar') {
            return $view;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $view;
        }
    }

    // Koreksi Penerimaan Kas
    public function indexKoreksiKas()
    {
        return view('bud.koreksi_penerimaan.index');
    }

    public function loadDataKoreksiKas()
    {
        $data = DB::table('tkoreksi_penerimaan')
            ->orderBy("nomor")
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("koreksi_penerimaan_kas.edit", Crypt::encrypt($row->nomor . '|' . $row->kd_skpd)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->nomor . '|' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahKoreksiKas()
    {
        $data = [
            'daftar_skpd' => DB::table('ms_skpd as a')
                ->orderBy('kd_skpd')
                ->get(),
        ];

        return view('bud.koreksi_penerimaan.create')->with($data);
    }

    public function simpanKoreksiKas(Request $request)
    {
        $data = $request->data;
        $kd_skpd = $data['nm_skpd'];

        DB::beginTransaction();
        try {
            $no_urut = nomor_urut_ppkd();

            $cek_terima = DB::table('tkoreksi_penerimaan')->where(['nomor' => $no_urut, 'kd_skpd' => $kd_skpd])->count();
            if ($cek_terima > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('tkoreksi_penerimaan')
                ->insert([
                    'nomor' => $no_urut,
                    'tanggal' => $data['tgl_kas'],
                    'keterangan' => $data['keterangan'],
                    'nilai' => $data['nilai'],
                    'jenis' => $data['jenis'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd']
                ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'nomor' => $no_urut
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function editKoreksiKas($no)
    {
        $no = Crypt::decrypt($no);
        $id = explode("|", $no);
        $data = [
            'daftar_skpd' => DB::table('ms_skpd as a')
                ->orderBy('kd_skpd')
                ->get(),
            'koreksi' => DB::table('tkoreksi_penerimaan')
                ->where(['nomor' => $id[0], 'kd_skpd' => $id[1]])
                ->first()
        ];

        return view('bud.koreksi_penerimaan.edit')->with($data);
    }

    public function simpanEditKoreksiKas(Request $request)
    {
        $data = $request->data;
        $kd_skpd =  $data['kd_skpd'];

        DB::beginTransaction();
        try {

            DB::table('tkoreksi_penerimaan')
                ->where(['nomor' => $data['no_kas'], 'kd_skpd' => $kd_skpd])
                ->update([
                    'tanggal' => $data['tgl_kas'],
                    'keterangan' => $data['keterangan'],
                    'nilai' => $data['nilai'],
                    'jenis' => $data['jenis'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd']
                ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'nomor' => $data['no_kas']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function hapusKoreksikas(Request $request)
    {
        $no = $request->no;
        $id = explode("|", $no);
        DB::beginTransaction();
        try {
            DB::table('tkoreksi_penerimaan')->where(['nomor' => $id[0], 'kd_skpd' => $id[1]])->delete();

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



    // KOREKSI PENGELUARAN SP2D
    public function indexKoreksi()
    {
        return view('bud.koreksi_pengeluaran.index');
    }

    public function loadDataKoreksi()
    {
        $data = DB::table('trkoreksi_pengeluaran')
            ->orderByRaw("cast(no as int)")
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("koreksi_pengeluaran.edit", Crypt::encrypt($row->no . '|' . $row->kd_skpd)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no . '|' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahKoreksi()
    {
        $data = [
            'daftar_skpd' => DB::table('ms_skpd as a')
                ->orderBy('kd_skpd')
                ->get(),
        ];

        return view('bud.koreksi_pengeluaran.create')->with($data);
    }

    public function jenisKoreksi(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $no_sp2d = $request->no_sp2d;
        $data = DB::table('trhsp2d as b')
            ->join('trdspp as a', function ($join) {
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.no_spp', '=', 'b.no_spp');
            })
            ->selectRaw("a.kd_skpd, a.nm_skpd, a.kd_rek6, a.nm_rek6")
            ->where(['a.kd_skpd' => $kd_skpd, 'b.no_sp2d' => $no_sp2d])
            ->groupByRaw("a.kd_skpd,a.nm_skpd, a.kd_rek6, a.nm_rek6")
            ->get();

        return response()->json($data);
    }

    public function nomorSp2d(Request $request)
    {
        $kd_skpd = $request->kd_skpd;

        $data = DB::table('trhsp2d')
            ->selectRaw("no_sp2d")
            ->where(['kd_skpd' => $kd_skpd])
            ->whereRaw("(sp2d_batal is null OR sp2d_batal =0)")
            ->get();
        return response()->json($data);
    }

    public function simpanKoreksi(Request $request)
    {
        $data = $request->data;
        $kd_skpd =  $data['kd_skpd'];

        DB::beginTransaction();
        try {
            $no_urut = nomor_urut_ppkd();

            $cek_terima = DB::table('trkoreksi_pengeluaran')->where(['no' => $no_urut, 'kd_skpd' => $kd_skpd])->count();
            if ($cek_terima > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trkoreksi_pengeluaran')
                ->insert([
                    'no' => $no_urut,
                    'tanggal' => $data['tgl_kas'],
                    'keterangan' => $data['keterangan'],
                    'nilai' => $data['total'],
                    'no_sp2d' => $data['no_sp2d'],
                    'kd_rek' => $data['jenis'],
                    'nm_rek' => $data['nama_jenis'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'kd_sub_kegiatan' => DB::raw("left('$kd_skpd',4)+'.00.0.00.04'")
                ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'nomor' => $no_urut
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function editKoreksi($no)
    {
        $no = Crypt::decrypt($no);
        $id = explode("|", $no);
        $data = [
            'daftar_skpd' => DB::table('ms_skpd as a')
                ->orderBy('kd_skpd')
                ->get(),
            'koreksi' => DB::table('trkoreksi_pengeluaran')
                ->where(['no' => $id[0], 'kd_skpd' => $id[1]])
                ->first()
        ];

        return view('bud.koreksi_pengeluaran.edit')->with($data);
    }

    public function simpanEditKoreksi(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {

            DB::table('trkoreksi_pengeluaran')
                ->where(['no' => $data['no_kas']])
                ->update([
                    'tanggal' => $data['tgl_kas'],
                    'keterangan' => $data['keterangan'],
                    'nilai' => $data['total'],
                    'no_sp2d' => $data['no_sp2d'],
                    'kd_rek' => $data['jenis'],
                    'nm_rek' => $data['nama_jenis'],
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'kd_sub_kegiatan' => DB::raw("left('$kd_skpd',4)+'.00.0.00.04'")
                ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'nomor' => $data['no_kas']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function hapusKoreksi(Request $request)
    {
        $no = $request->no;
        $id = explode("|", $no);
        DB::beginTransaction();
        try {
            DB::table('trkoreksi_pengeluaran')->where(['no' => $id[0], 'kd_skpd' => $id[1]])->delete();

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


    // PENGELUARAN NON SP2D
    public function indexPengeluaranNonSp2d()
    {
        return view('bud.pengeluaran_non_sp2d.index');
    }

    public function loadDataPengeluaranNonSp2d()
    {
        $data = DB::table('pengeluaran_non_sp2d')
            ->orderByRaw("nomor")
            ->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("non_sp2d.edit", Crypt::encrypt($row->nomor)) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->nomor . '\');" class="btn btn-danger btn-sm" id="delete" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tambahPengeluaranNonSp2d()
    {
        return view('bud.pengeluaran_non_sp2d.create');
    }

    public function simpanPengeluaranNonSp2d(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            $no_urut = nomor_urut_ppkd();

            DB::table('pengeluaran_non_sp2d')
                ->insert([
                    'nomor' => $no_urut,
                    'tanggal' => $data['tgl_kas'],
                    'keterangan' => $data['keterangan'],
                    'nilai' => $data['nilai'],
                ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'nomor' => $no_urut
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function editPengeluaranNonSp2d($nomor)
    {
        $nomor = Crypt::decrypt($nomor);

        $data = [
            'terima' => DB::table('pengeluaran_non_sp2d')
                ->where(['nomor' => $nomor])
                ->first()
        ];

        return view('bud.pengeluaran_non_sp2d.edit')->with($data);
    }

    public function simpanEditPengeluaranNonSp2d(Request $request)
    {
        $data = $request->data;

        DB::beginTransaction();
        try {
            DB::table('pengeluaran_non_sp2d')
                ->where(['nomor' => $data['no_kas']])
                ->update([
                    'tanggal' => $data['tgl_kas'],
                    'keterangan' => $data['keterangan'],
                    'nilai' => $data['nilai'],
                ]);

            DB::commit();
            return response()->json([
                'message' => '1',
                'nomor' => $data['no_kas']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function hapusPengeluaranNonSp2d(Request $request)
    {
        $nomor = $request->nomor;

        DB::beginTransaction();
        try {
            DB::table('pengeluaran_non_sp2d')->where(['nomor' => $nomor])->delete();

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
