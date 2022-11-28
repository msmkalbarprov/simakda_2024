<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BendaharaUmumDaerahController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'bendahara' => DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd, 'kode' => 'BK'])->orderBy('nip')->orderBy('nama')->get(),
            'pa_kpa' => DB::table('ms_ttd')->whereIn('kode', ['PA', 'KPA'])->orderBy('nama')->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'daftar_skpd' => DB::table('ms_skpd')->orderBy('kd_skpd')->get(),
            'daftar_pengirim' => DB::table('ms_pengirim')->selectRaw("kd_pengirim,nm_pengirim,kd_skpd")->orderByRaw("cast(kd_pengirim as int)")->get(),
            'daftar_wilayah' => DB::table('ms_wilayah')->selectRaw("kd_wilayah,nm_wilayah")->orderByRaw("cast(kd_wilayah as int)")->get(),
            'bud' => DB::table('ms_ttd')->select('nip', 'nama', 'jabatan')->where(['kd_skpd' => $kd_skpd])->whereIn('kode', ['BUD', 'PA'])->get(),
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

        if ($ttd) {
            $tanda_tangan = DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['nip' => $ttd])->whereIn('kode', ['BUD', 'PA'])->first();
        } else {
            $tanda_tangan = null;
        }

        if ($pilihan == '1') {
            $daftar_realisasi = DB::select("SELECT * FROM penerimaan_kasda_new(?,?) WHERE LEFT(kd_rek,1)='4' AND  len(kd_rek)<? and left(kd_rek,6)!='410416' ORDER BY urut1,urut2", [$periode, $anggaran, $jenis]);
        } else if ($pilihan == '2') {
            $daftar_realisasi = DB::select("SELECT * FROM penerimaan_kasda_new_skpd(?,?,?) WHERE LEFT(kd_rek,1)='4' AND  len(kd_rek)<? and left(kd_rek,6)!='410416' ORDER BY urut1,urut2", [$periode, $anggaran, $kd_skpd, $jenis]);
        } else if ($pilihan == '3') {
            $daftar_realisasi  = DB::select("SELECT * FROM penerimaan_kasda_new_unit(?,?,?) WHERE LEFT(kd_rek,1)='4' AND len(kd_rek)<? and left(kd_rek,6)!='410416' ORDER BY urut1,urut2", [$periode, $anggaran, $kd_unit, $jenis]);
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

        return view('bud.laporan_bendahara.cetak.realisasi_pendapatan')->with($data);
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
            ->whereRaw("left(a.kd_rek6,4) NOT IN ('4101','4301','4104','4201') AND a.kd_rek6 NOT IN ('420101040001')")
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
            ->whereRaw("left(a.kd_rek6,4) NOT IN ('4101','4301','4104','4201') AND a.kd_rek6 NOT IN ('420101040001','420101040002','420101040003')")
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
            ->whereRaw("left(a.kd_rek6,4) IN ('4101','4301','4104','4201') AND a.kd_rek6 !='410416010001' AND a.kd_rek6 NOT IN ('420101040001','420101040002','420101040003')")
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
            ->whereRaw("left(a.kd_rek6,4) IN ('4101','4301','4104','4201') AND a.kd_rek6!='410416010001' AND a.kd_rek6 NOT IN ('420101040001','420101040002','420101040003')")
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
            ->whereRaw("left(a.kd_rek6,4) NOT IN ('4101','4301','4104','4201') AND a.kd_rek6 NOT IN ('420101040001')")
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
            ->whereRaw("left(a.kd_rek6,4) IN ('4101','4301','4104','4201') AND a.kd_rek6 !='410416010001' AND a.kd_rek6 NOT IN ('420101040001')")
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

        return view('bud.laporan_bendahara.cetak.pembantu_penerimaan')->with($data);
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
            ->whereRaw("LEFT(b.kd_rek6,1) IN ('4') and  b.kd_rek6 not in ('420101040001','420101040002','420101040003','410416010001','410409010001')")
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
            ->whereRaw("b.kd_rek6 in ('410409010001') and b.sumber<>'y'")
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
            ->whereRaw("b.kd_rek6 in ('410409010001','410412010010') and a.keterangan like '%(UYHD)%'")
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
            ->whereRaw("LEFT(b.kd_rek6,1) IN ('5','1') and pot_khusus=?", ['3'])
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
            ->whereRaw("LEFT(b.kd_rek6,1) IN ('5','1','2') and pot_khusus<>?", ['3'])
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
            $bku15 = $bku14->unionAll($saldo);
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
            ->whereRaw("LEFT(b.kd_rek6,1) IN ('4') and b.kd_rek6 not in ('420101040001','420101040002','420101040003','410416010001')")
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
            ->whereRaw("LEFT(b.kd_rek6,1) IN ('5','1','2')")
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
        if ($tgl == "2021-01-01") {
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
        return view('bud.laporan_bendahara.cetak.bku_tanpa_tanggal')->with($data);
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

        $terima1 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->leftJoin('ms_rek3 as c', function ($join) {
                $join->on(DB::raw("left(b.kd_rek6,3)"), '=', 'c.kd_rek3');
            })
            ->selectRaw("a.tgl_kas, SUM ( rupiah ) AS nilai,1 jenis")
            ->whereRaw("LEFT( b.kd_rek6, 1 ) IN ( ? ) AND a.tgl_kas BETWEEN ? AND ?", ['4', $periode1, $periode2])
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
        return view('bud.laporan_bendahara.cetak.bku_dengan_tanggal')->with($data);
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
            ->whereRaw("LEFT( b.kd_rek6, 1 ) IN ( ? ) AND a.tgl_kas BETWEEN ? AND ? and b.kd_rek6 not in ('420101040001','410416010001')", ['4', $periode1, $periode2])
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
        return view('bud.laporan_bendahara.cetak.bku_tanpa_blud')->with($data);
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
            $saldo = DB::table('buku_kas')->selectRaw("'4' kd_rek, 'SALDO AWAL' nama, nilai , 1 jenis");
        }

        $cek_pengeluaran = DB::table('pengeluaran_non_sp2d')->where(['tanggal' => $tgl])->count();
        if ($cek_pengeluaran > 0) {
            $keluar_non_sp2d = DB::table('pengeluaran_non_sp2d as x')
                ->selectRaw("CAST(nomor as VARCHAR) as no_kas,nomor as urut, '' as uraian,keterangan+'. Rp. ','' kode, 'PENGELUARAN NON SP2D' nm_rek6,0 as terima,isnull(SUM(x.nilai), 0) AS keluar, 2 jenis, isnull(SUM(x.nilai), 0) as netto, ''sp")
                ->where(['tanggal' => $tgl])
                ->groupBy('nomor', 'keterangan');
        }

        $cek_penerimaan1 = DB::table('penerimaan_non_sp2d')->where(['tanggal' => $tgl, 'jenis' => '1'])->count();
        if ($cek_penerimaan1 > 0) {
            $masuk_non_sp2d1 = DB::table('penerimaan_non_sp2d as w')
                ->selectRaw("CAST(nomor as VARCHAR),nomor as urut,keterangan as uraian,''kode,'Deposito'nm_rek6,isnull(SUM(w.nilai), 0) AS terima,0 as keluar,1 jenis, 0 netto, ''sp")
                ->where(['tanggal' => $tgl, 'w.jenis' => '1'])
                ->groupBy('nomor', 'keterangan');
        }

        $cek_penerimaan2 = DB::table('penerimaan_non_sp2d')->where(['tanggal' => $tgl, 'jenis' => '2'])->count();
        if ($cek_penerimaan2 > 0) {
            $masuk_non_sp2d2 = DB::table('penerimaan_non_sp2d as w')
                ->selectRaw("CAST(nomor as VARCHAR) as nokas,nomor as urut,keterangan as uraian,'-'kode,'Penerimaan NON SP2D'nm_rek6,isnull(SUM(w.nilai), 0) AS terima,0 as keluar,1 jenis, 0 netto, ''sp")
                ->where(['tanggal' => $tgl, 'w.jenis' => '2'])
                ->groupBy('nomor', 'keterangan');
        }

        $bku1 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("a.no_kas,a.no_kas as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6,0 as terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?) and  b.kd_rek6 not in ('420101040001','410416010001') and a.tgl_kas=?", ['4', $tgl])
            ->groupByRaw("a.no_kas,keterangan");

        $bku2 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->leftJoin('ms_rek6 as c', function ($join) {
                $join->on('b.kd_rek6', '=', 'c.kd_rek6');
            })
            ->selectRaw("'' as no_kas,a.no_kas as urut,keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, c.nm_rek6 as nm_rek6,SUM(rupiah) as terima,0 as keluar, 1 jenis, 0 netto, ''as sp")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?) and  b.kd_rek6 not in ('420101040001','410416010001') and a.tgl_kas=?", ['4', $tgl])
            ->groupByRaw("a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6,c.nm_rek6")
            ->unionAll($bku1);

        $bku3 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("a.no_kas,a.no_kas as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, ''as nm_rek6,0 as terima,0 as keluar, 1 jenis,SUM(rupiah) netto, '' as sp")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?) and pot_khusus=? and a.tgl_kas=?", ['5', '1', '3', $tgl])
            ->groupByRaw("a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6")
            ->unionAll($bku2);

        $bku4 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("'' as no_kas,a.no_kas as urut,a.keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, 'Lain-lain PAD yang sah'as nm_rek6,SUM(rupiah) as terima,0 as keluar, 1 jenis,0 netto, '' as sp")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?) and pot_khusus=? and a.tgl_kas=?", ['5', '1', '3', $tgl])
            ->groupByRaw("a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6")
            ->unionAll($bku3);

        $bku5 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("a.no_kas,a.no_kas as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6,0 as terima,0 as keluar, 1 jenis, SUM(rupiah) netto, '' as sp")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?) and pot_khusus<>? and a.tgl_kas=?", ['5', '1', '3', $tgl])
            ->groupByRaw("a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6")
            ->unionAll($bku4);

        $bku6 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("'' as no_kas,a.no_kas as urut,a.keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, 'CONTRA POST' as nm_rek6,SUM(rupiah) as terima,0 as keluar, 1 jenis, 0 netto, '' as sp")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?) and pot_khusus<>? and a.tgl_kas=?", ['5', '1', '3', $tgl])
            ->groupByRaw("a.no_kas,keterangan,b.kd_sub_kegiatan,b.kd_rek6")
            ->unionAll($bku5);

        $bku7 = DB::table('trhsp2d as a')
            ->join('trdspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
            })
            ->selectRaw("no_kas_bud AS no_kas,a.no_kas_bud as urut,'No.SP2D :'+' '+a.no_sp2d+'<br> '+a.keperluan+'Netto Rp. ' AS uraian,'' AS kode,'' AS nm_rek6,0 AS terima,0 AS keluar,2 AS jenis,(SUM(b.nilai))-(SELECT ISNULL(SUM(nilai),0) FROM trspmpot WHERE no_spm=a.no_spm) AS netto,'' as sp")
            ->whereRaw("a.status_bud = ? AND (a.sp2d_batal=0 OR a.sp2d_batal is NULL) AND a.tgl_kas_bud=?", ['1', $tgl])
            ->groupByRaw("a.no_sp2d,no_kas_bud,a.keperluan,a.no_spm")
            ->unionAll($bku6);

        $bku8 = DB::table('trdspp as b')
            ->join('trhsp2d as a', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
            })
            ->selectRaw("'' AS no_kas,a.no_kas_bud AS urut,'' AS uraian,(b.kd_sub_kegiatan+'.'+b.kd_rek6) AS kode,b.nm_rek6 AS nm_rek6,0 AS terima,b.nilai AS keluar,2 AS jenis,0 as netto,''as sp")
            ->whereRaw("a.status_bud = ? AND (a.sp2d_batal=0 OR a.sp2d_batal is NULL) AND a.tgl_kas_bud=?", ['1', $tgl])
            ->unionAll($bku7);

        $bku9 = DB::table('trdrestitusi as b')
            ->join('trhrestitusi as a', function ($join) {
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.no_sts', '=', 'b.no_sts');
            })
            ->selectRaw("a.no_kas as no_kas,a.no_kas as urut,'RESTITUSI<br>'+keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6,0 AS terima,0 keluar, 2 jenis,isnull(SUM(b.rupiah), 0) as netto,''sp")
            ->whereRaw("a.jns_trans=? and a.tgl_kas=?", ['3', $tgl])
            ->groupByRaw("a.no_kas,keterangan")
            ->unionAll($bku8);

        $bku10 = DB::table('trdrestitusi as b')
            ->join('trhrestitusi as a', function ($join) {
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.no_sts', '=', 'b.no_sts');
            })
            ->leftJoin('ms_rek6 as c', 'b.kd_rek6', '=', 'c.kd_rek6')
            ->selectRaw("'' as no_kas,a.no_kas as urut,''as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, c.nm_rek6,0 terima,isnull(SUM(b.rupiah), 0) AS keluar, 2 jenis,0 netto, ''sp")
            ->whereRaw("a.jns_trans=? and a.tgl_kas=?", ['3', $tgl])
            ->groupByRaw("a.no_kas,b.kd_sub_kegiatan,b.kd_rek6,c.nm_rek6")
            ->unionAll($bku9);

        $bku11 = DB::table('trkasout_ppkd as w')
            ->selectRaw("no as no_kas, no as urut,'KOREKSI PENERIMAAN<br>'+keterangan as uraian,kd_sub_kegiatan+'.'+kd_rek kode,nm_rek as nm_rek6,isnull(SUM(w.nilai),0) as terima,0 as keluar,1 jenis,isnull(SUM(w.nilai),0) as netto,''sp")
            ->whereRaw("tanggal=?", [$tgl])
            ->groupByRaw("no,keterangan,kd_sub_kegiatan,kd_rek,nm_rek")
            ->unionAll($bku10);

        $bku12 = DB::table('trkoreksi_pengeluaran as w')
            ->selectRaw("no as no_kas, no as urut,'KOREKSI PENGELUARAN<br>'+keterangan as uraian,kd_sub_kegiatan+'.'+kd_rek kode,nm_rek as nm_rek6,0 as terima,isnull(SUM(w.nilai),0) as keluar,2 jenis,isnull(SUM(w.nilai),0) as netto,''sp")
            ->whereRaw("tanggal=?", [$tgl])
            ->groupByRaw("no,keterangan,kd_sub_kegiatan,kd_rek,nm_rek")
            ->unionAll($bku11);

        if (isset($saldo)) {
            $bku13 = $bku12->unionAll($saldo);
        } else {
            $bku13 = $bku12;
        }

        if (isset($keluar_non_sp2d)) {
            $bku14 = $bku13->unionAll($keluar_non_sp2d);
        } else {
            $bku14 = $bku13;
        }

        if (isset($masuk_non_sp2d1)) {
            $bku15 = $bku14->unionAll($masuk_non_sp2d1);
        } else {
            $bku15 = $bku14;
        }

        if (isset($masuk_non_sp2d2)) {
            $bku16 = $bku15->unionAll($masuk_non_sp2d2);
        } else {
            $bku16 = $bku15;
        }

        $bku = DB::table(DB::raw("({$bku16->toSql()}) AS sub"))
            ->mergeBindings($bku16)
            ->orderBy('urut')
            ->orderBy('kode')
            ->orderBy('jenis')
            ->get();

        $total_bku1 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->leftJoin('ms_rek3 as c', function ($join) {
                $join->on(DB::raw("left(b.kd_rek6,4)"), '=', 'c.kd_rek3');
            })
            ->selectRaw("a.tgl_kas,LEFT(b.kd_rek6,4) as kd_rek, UPPER(c.nm_rek3) as nama,SUM(rupiah) as nilai, 1 jenis")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?) and b.kd_rek6 not in ('420101040001','410416010001') and a.tgl_kas<?", ['4', $tgl])
            ->groupByRaw("a.tgl_kas,LEFT(b.kd_rek6,4),c.nm_rek3");

        $total_bku2 = DB::table('trhkasin_ppkd as a')
            ->join('trdkasin_ppkd as b', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("a.tgl_kas,LEFT(b.kd_rek6,1) as kd_rek, 'CONTRA POST' as nama,SUM(rupiah) as nilai, 1 jenis")
            ->whereRaw("LEFT(b.kd_rek6,1) IN (?,?) and a.tgl_kas<?", ['5', '1', $tgl])
            ->groupByRaw("a.tgl_kas,LEFT(b.kd_rek6,1)")
            ->unionAll($total_bku1);

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
            ->whereRaw("a.status_bud = ? AND  a.jns_spp != ? AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL) AND a.tgl_kas_bud<?", ['1', '4', $tgl])
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
            ->whereRaw("a.status_bud = ? AND  a.jns_spp = ? AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL) AND a.tgl_kas_bud<?", ['1', '4', $tgl])
            ->groupByRaw("a.tgl_kas_bud")
            ->unionAll($total_bku3);

        $total_bku5 = DB::table('pengeluaran_non_sp2d as x')
            ->selectRaw("x.tanggal,'' kd_rek, 'PENGELUARAN NON SP2D' nama,isnull(SUM(x.nilai), 0) AS nilai, 2 jenis")
            ->whereRaw("x.tanggal<?", [$tgl])
            ->groupByRaw("x.tanggal")
            ->unionAll($total_bku4);

        $total_bku6 = DB::table('trdrestitusi as b')
            ->join('trhrestitusi as a', function ($join) {
                $join->on('a.no_kas', '=', 'b.no_kas');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                $join->on('a.no_sts', '=', 'b.no_sts');
            })
            ->selectRaw("a.tgl_kas,'' kd_rek, 'RESTITUSI' nama,isnull(SUM(b.rupiah), 0) AS nilai, 2 jenis")
            ->whereRaw("a.tgl_kas<?", [$tgl])
            ->where('a.jns_trans', '3')
            ->groupByRaw("a.tgl_kas")
            ->unionAll($total_bku5);

        $total_bku7 = DB::table('trkasout_ppkd as w')
            ->selectRaw("w.tanggal,'' as kd_rek, 'KOREKSI PENERIMAAN' nama,isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
            ->whereRaw("w.tanggal<?", [$tgl])
            ->groupByRaw("w.tanggal,w.kd_rek")
            ->unionAll($total_bku6);

        $total_bku8 = DB::table('trkoreksi_pengeluaran as w')
            ->selectRaw("w.tanggal,'' as kd_rek, 'KOREKSI PENGELUARAN' nama,isnull(SUM(w.nilai), 0) AS nilai, 2 jenis")
            ->whereRaw("w.tanggal<?", [$tgl])
            ->groupByRaw("w.tanggal,w.kd_rek")
            ->unionAll($total_bku7);

        $total_bku9 = DB::table('penerimaan_non_sp2d as w')
            ->selectRaw("w.tanggal,'' as kd_rek, 'DEPOSITO' nama,isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
            ->whereRaw("w.tanggal<?", [$tgl])
            ->where('w.jenis', '1')
            ->groupByRaw("w.tanggal")
            ->unionAll($total_bku8);

        $total_bku10 = DB::table('penerimaan_non_sp2d as w')
            ->selectRaw("w.tanggal,'' as kd_rek, 'PENERIMAAN NON SP2D' nama,isnull(SUM(w.nilai), 0) AS nilai, 1 jenis")
            ->whereRaw("w.tanggal<?", [$tgl])
            ->where('w.jenis', '2')
            ->groupByRaw("w.tanggal")
            ->unionAll($total_bku9);

        $total_bku = DB::table(DB::raw("({$total_bku10->toSql()}) AS sub"))
            ->selectRaw("SUM(CASE WHEN jenis IN('1') THEN nilai ELSE 0 END) as trm_sbl,SUM(CASE WHEN jenis IN('2') THEN nilai ELSE 0 END) as klr_sbl")
            ->mergeBindings($total_bku10)
            ->first();

        $saldo_awal = DB::table('buku_kas')->select('nilai')->where(['nomor' => '0'])->first();
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
        return view('bud.laporan_bendahara.cetak.bku_rincian')->with($data);
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
        $denda_pkb_all = "('410412010001','410412010002','410412010003','410412010005','410412010006','410412010008','410412010010','410412010012','410412010013')";
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
        $jumlah_all = "('410101010001','410101020001','410101030001','410101010002','410101020002','410101030002','410101010004','410101020004','410101030004','410101050001','410101050002','410101050004','410101080001','410101060001','410101080002','410101060002','410101080004','410101060004','410101130001','410101100001','410101100004','410412010001','410412010002','410412010003','410412010005','410412010006','410412010008','410412010010','410412010012','410412010013','4110114','4110201','4110202','4110203','4110204','4110205','410102010001','410102020001','410102030001','410102050001','410102060001','410102080001','410102100001','410102120001','410412020001','410412020002','410412020003','410412020005','410412020006','410412020008','410412020010','410412020012','410412020013','410101120001','410104010001','4110401','410412040001','430105010001','410103010001','410103040001','410103020001','4140704')";

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
                ->whereRaw("LEFT(jns_rek,4)=?", ['4101'])
                ->orderByRaw("cast(a.kd_pengirim AS int)")
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
                ->whereRaw("LEFT(jns_rek,4)=?", ['4101'])
                ->orderByRaw("cast(a.kd_pengirim AS int)")
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
                ->orderByRaw("cast(a.kd_pengirim AS int)")
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
                ->orderByRaw("cast(a.kd_pengirim AS int)")
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
                ->orderByRaw("cast(a.kd_pengirim AS int)")
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
                ->orderByRaw("cast(a.kd_pengirim AS int)")
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
                ->whereRaw("LEFT(jns_rek,4)=?", ['4101'])
                ->orderByRaw("cast(a.kd_pengirim AS int)")
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
                ->whereRaw("LEFT(jns_rek,4)=?", ['4101'])
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
                ->whereRaw("LEFT(jns_rek,4)=?", ['4101'])
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
                ->whereRaw("LEFT(jns_rek,4)=?", ['4101'])
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

        return view('bud.laporan_bendahara.cetak.pajak_daerah')->with($data);
    }

    public function rekapGaji(Request $request)
    {
        $req = $request->all();

        $rekap_gaji1 = DB::table('trhsp2d as a')
            ->selectRaw("a.kd_skpd,a.nm_skpd,a.no_sp2d nomor,a.nilai nilai_sp2d,0 IWP,0 AS JKK,0 JKM,0 AS BPJS,0 AS PPH21,0 AS TAPERUM,0 AS HKPG")
            ->whereRaw("a.no_sp2d like '%GJ%' and (a.sp2d_batal IS NULL OR a.sp2d_batal !=?)", ['1'])
            ->where(function ($query) use ($req) {
                if ($req['kd_skpd']) {
                    $query->where('a.kd_skpd', $req['kd_skpd']);
                }
            })
            ->where(function ($query) use ($req) {
                if ($req['pilihan'] == '12' || $req['pilihan'] == '22') {
                    $query->where(DB::raw("MONTH(tgl_sp2d)"), $req['bulan'])->where('a.jenis_beban', '1');
                }
                if ($req['pilihan'] == '13' || $req['pilihan'] == '23') {
                    $query->whereBetween('tgl_sp2d', [$req['periode1'], $req['periode2']]);
                }
            })
            ->groupByRaw("a.kd_skpd,a.nm_skpd,a.no_sp2d,a.nilai");

        $rekap_gaji2 = DB::table('trhsp2d as a')
            ->join('trspmpot as b', function ($join) {
                $join->on('a.no_spm', '=', 'b.no_spm');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->selectRaw("a.kd_skpd,a.nm_skpd,a.no_sp2d nomor,0 nilai_sp2d,SUM(CASE WHEN b.kd_rek6='210108010001' THEN b.nilai ELSE 0 END) AS IWP,SUM(CASE WHEN b.kd_rek6='210103010001' THEN b.nilai ELSE 0 END) AS JKK,SUM(CASE WHEN b.kd_rek6='210104010001' THEN b.nilai ELSE 0 END) AS JKM,SUM(CASE WHEN b.kd_rek6='210102010001' THEN b.nilai ELSE 0 END) AS BPJS,SUM(CASE WHEN b.kd_rek6='210105010001' THEN b.nilai ELSE 0 END) AS PPH21,SUM(CASE WHEN b.kd_rek6='' THEN 0 ELSE 0 END) AS TAPERUM,SUM(CASE WHEN b.kd_rek6 in ('210601010007','210601010003','210601010011') THEN b.nilai ELSE 0 END) AS HKPG")
            ->whereRaw("a.no_sp2d like '%GJ%' and (a.sp2d_batal IS NULL OR a.sp2d_batal !=?)", ['1'])
            ->where(function ($query) use ($req) {
                if ($req['kd_skpd']) {
                    $query->where('a.kd_skpd', $req['kd_skpd']);
                }
            })
            ->where(function ($query) use ($req) {
                if ($req['pilihan'] == '12' || $req['pilihan'] == '22') {
                    $query->where(DB::raw("MONTH(tgl_sp2d)"), $req['bulan'])->where('a.jenis_beban', '1');
                }
                if ($req['pilihan'] == '13' || $req['pilihan'] == '23') {
                    $query->whereBetween('tgl_sp2d', [$req['periode1'], $req['periode2']]);
                }
            })
            ->groupByRaw("a.kd_skpd,a.nm_skpd,a.no_sp2d,a.nilai")
            ->unionAll($rekap_gaji1);

        $rekap_gaji = DB::table(DB::raw("({$rekap_gaji2->toSql()}) AS sub"))
            ->selectRaw("kd_skpd,nm_skpd,nomor,sum(nilai_sp2d) nilai_sp2d, sum(IWP) IWP, sum(JKK) JKK, sum(JKM) JKM, sum(BPJS) BPJS, sum(PPH21) PPH21, sum(TAPERUM) TAPERUM, sum(HKPG) HKPG, sum(IWP) + sum(JKK) + sum(JKM) + sum(BPJS) + sum(PPH21) + sum(TAPERUM) + sum(HKPG) as Total")
            ->mergeBindings($rekap_gaji2)
            ->groupByRaw("kd_skpd,nm_skpd,nomor")
            ->orderBy('kd_skpd')
            ->get();

        $data = [
            'header' => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'pilihan' => $req['pilihan'],
            'data_awal' => $req,
            'rekap_gaji' => $rekap_gaji
        ];

        return view('bud.laporan_bendahara.cetak.rekap_gaji')->with($data);
    }
}
