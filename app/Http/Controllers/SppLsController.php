<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SppLsController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'data_spp' => DB::table('trhspp')->where('kd_skpd', $kd_skpd)->whereNotIn('jns_spp', ['1', '2', '3'])->orderByRaw("tgl_spp ASC, no_spp ASC,CAST(urut AS INT) ASC")->get(),
        ];
        return view('penatausahaan.pengeluaran.spp_ls.index')->with($data);
    }

    public function create()
    {
        $skpd = Auth::user()->kd_skpd;
        $kd_skpd = substr($skpd, 0, 17);
        $perusahaan1 = DB::table('ms_perusahaan')->select('nama as nmrekan', 'pimpinan', 'npwp', 'alamat')->whereRaw('LEFT(kd_skpd,17) = ?', [$kd_skpd])->groupBy('nama', 'pimpinan', 'npwp', 'alamat');
        $perusahaan2 = DB::table('trhspp')->select('nmrekan', 'pimpinan', 'npwp', 'alamat')->whereRaw('LEN(nmrekan)>1')->where('kd_skpd', $skpd)->groupBy('nmrekan', 'pimpinan', 'npwp', 'alamat')->unionAll($perusahaan1);
        $perusahaan3 = DB::table('trhtrmpot_cmsbank')->select('nmrekan', 'pimpinan', 'npwp', 'alamat')->whereRaw('LEN(nmrekan)>1')->where('kd_skpd', $skpd)->groupBy('nmrekan', 'pimpinan', 'npwp', 'alamat')->unionAll($perusahaan2);
        $perusahaan4 = DB::table('trhtrmpot')->select('nmrekan', 'pimpinan', 'npwp', 'alamat')->whereRaw('LEN(nmrekan)>1')->where('kd_skpd', $skpd)->groupBy('nmrekan', 'pimpinan', 'npwp', 'alamat')->unionAll($perusahaan3);
        $result = DB::table(DB::raw("({$perusahaan4->toSql()}) AS sub"))
            ->select("nmrekan", "pimpinan", "npwp", "alamat")
            ->mergeBindings($perusahaan4)
            ->groupBy('nmrekan', 'pimpinan', 'npwp', 'alamat')
            ->orderBy('nmrekan', 'ASC')
            ->orderBy('pimpinan', 'ASC')
            ->orderBy('npwp', 'ASC')
            ->orderBy('alamat', 'ASC')
            ->get();
        $data1 = DB::select(DB::raw("SELECT isnull(no_tagih,'') no_tagih from trhspp where kd_skpd='$skpd' and (sp2d_batal is null OR sp2d_batal<>'1') GROUP BY no_tagih"));
        $data2 = json_decode(json_encode($data1), true);
        $data = [
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $skpd)->first(),
            'daftar_rekanan' => $result,
            'daftar_penerima' => DB::table('ms_rekening_bank_online')->select('rekening', 'nm_rekening', 'npwp')->where('kd_skpd', $skpd)->orderBy('rekening')->get(),
            'daftar_bank' => DB::table('ms_bank')->select('kode', 'nama')->orderBy('kode')->get(),
            'daftar_penagihan' => DB::table('trhtagih as a')->select('a.kd_skpd', 'a.no_bukti', 'tgl_bukti', 'a.ket', 'a.kontrak', 'kd_sub_kegiatan', DB::raw('SUM(b.nilai) as total'))->join('trdtagih as b', 'a.no_bukti', '=', 'b.no_bukti')->where('a.kd_skpd', $skpd)->where('a.jns_trs', '1')->whereNotIn('a.no_bukti', $data2)->groupBy('a.kd_skpd', 'a.no_bukti', 'tgl_bukti', 'a.ket', 'a.kontrak', 'kd_sub_kegiatan')->orderBy('a.no_bukti')->get(),
            'data_tgl' => DB::table('trhspp')->selectRaw('MAX(tgl_spp) as tgl_spp')->where('kd_skpd', $skpd)->where(function ($query) {
                $query->where('sp2d_batal', '=', '0')
                    ->orWhereNull('sp2d_batal');
            })->first(),
            'data_opd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where('kd_skpd', $skpd)->first(),
        ];

        return view('penatausahaan.pengeluaran.spp_ls.create')->with($data);
    }

    public function cariJenis(Request $request)
    {
        $beban = $request->beban;
        if ($beban == '3') {
            $data = [
                "id"   => 1,
                "text" => " TU",
            ];
        } elseif ($beban == '4') {
            $data = [
                [
                    "id"   => 1,
                    "text" => " Gaji & Tunjangan"
                ],
                [
                    "id"   => 2,
                    "text" => " Kespeg"
                ],
                [
                    "id"   => 3,
                    "text" => " Uang Makan"
                ],
                [
                    "id"   => 4,
                    "text" => " Upah Pungut"
                ],
                [
                    "id"   => 5,
                    "text" => " Upah Pungut PBB"
                ],
                [
                    "id"   => 6,
                    "text" => " Upah Pungut PBB-KB PKB & BBN-KB"
                ],
                [
                    "id"   => 7,
                    "text" => " Tambahan/Kekurangan Gaji & Tunjangan"
                ],
                [
                    "id"   => 8,
                    "text" => " Tunjangan Transport"
                ],
                [
                    "id"   => 9,
                    "text" => " Tunjangan Lainnya"
                ],
                [
                    "id"   => 10,
                    "text" => " Gaji Anggota DPRD"
                ]
            ];
        } elseif ($beban == '5') {
            $data = [
                [
                    "id"   => 1,
                    "text" => "Hibah berupa uang"
                ],
                [
                    "id"   => 2,
                    "text" => " Bantuan Sosial berupa uang"
                ],
                [
                    "id"   => 3,
                    "text" => " Bantuan Keuangan"
                ],
                [
                    "id"   => 4,
                    "text" => " Subsidi"
                ],
                [
                    "id"   => 5,
                    "text" => " Bagi Hasil"
                ],
                [
                    "id"   => 6,
                    "text" => " Belanja Tidak Terduga"
                ],
                [
                    "id"   => 7,
                    "text" => " Pembayaran kewajiban pemda atas putusan pengadilan, dan rekomendasi APIP dan/atau rekomendasi BPK"
                ],
                [
                    "id"   => 8,
                    "text" => " Pengeluaran Pembiayaan"
                ],
                [
                    "id" => 9,
                    "text" => "Barang yang diserahkan ke masyarakat"
                ]
            ];
        } elseif ($beban == '6') {
            $data = [
                [
                    "id"   => 1,
                    "text" => " Tambahan Penghasilan"
                ],
                [
                    "id"   => 2,
                    "text" => " Operasional KDH/WKDH"
                ],
                [
                    "id"   => 3,
                    "text" => " Operasional DPRD"
                ],
                [
                    "id"   => 4,
                    "text" => " Honor Kontrak"
                ],
                [
                    "id"   => 5,
                    "text" => " Jasa Pelayanan Kesehatan"
                ],
                [
                    "id"   => 6,
                    "text" => " Pihak ketiga"
                ],
                [
                    "id"   => 7,
                    "text" => " Rutin (PNS)"
                ]
            ];
        }

        return response()->json($data);
    }

    public function cariNomorSpd(Request $request)
    {
        $beban = $request->beban;
        $tgl_spp = $request->tgl_spp;
        $kd_skpd = Auth::user()->kd_skpd;
        $skpd = substr($kd_skpd, 0, 17);
        $bulan = date('m', strtotime($tgl_spp));
        if ($beban == '4') {
            $beban = ['5'];
        } elseif ($beban == '5') {
            $beban = ['5', '6'];
        } elseif ($beban == '6') {
            $beban = ['5'];
        }

        $data = DB::table('trhspd')->select('no_spd', 'tgl_spd', 'total')->whereRaw('LEFT(kd_skpd,17) = ?', [$skpd])->where('status', '1')->where('tgl_spd', '<=', $tgl_spp)->where('bulan_awal', '<=', $bulan)->whereIn('jns_beban', $beban)->get();
        return response()->json($data);
    }

    public function cariSubKegiatan(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $spd = $request->spd;
        $skpd = substr($kd_skpd, 18, 4);
        if ($skpd == "0000") {
            $data = DB::table('trdspd as a')->select('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan', 'a.kd_program', 'a.nm_program', 'c.status_keg', DB::raw("(SELECT distinct trskpd.kd_skpd from trskpd where trskpd.kd_sub_kegiatan=a.kd_sub_kegiatan and trskpd.kd_skpd=b.kd_skpd) as bidang"))->distinct()->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trskpd as c', function ($join) {
                $join->on('a.kd_sub_kegiatan', '=', 'c.kd_sub_kegiatan');
                $join->on('b.kd_skpd', '=', 'c.kd_skpd');
            })->where('a.no_spd', $spd)->where('c.status_sub_kegiatan', '1')->where(function ($query) {
                $query->where('c.status_keg', '<>', '0')
                    ->orWhereNull('c.status_keg');
            })->orderBy('a.kd_sub_kegiatan')->get();
        } else {
            $data = DB::table('trdspd as a')->select('a.kd_sub_kegiatan', 'a.nm_sub_kegiatan', 'a.kd_program', 'a.nm_program', 'c.status_keg', 'c.kd_skpd as bidang')->distinct()->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->join('trskpd as c', function ($join) {
                $join->on('a.kd_sub_kegiatan', '=', 'c.kd_sub_kegiatan');
                $join->on(DB::raw("LEFT(b.kd_skpd, 17)"), '=', DB::raw("LEFT(c.kd_skpd, 17)"));
            })->where('a.no_spd', $spd)->where('c.status_sub_kegiatan', '1')->where('c.kd_skpd', $kd_skpd)->orderBy('a.kd_sub_kegiatan')->get();
        }
        return response()->json($data);
    }

    public function cariRekening(Request $request)
    {
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trdrka')->select('kd_rek6', 'nm_rek6')->distinct()->where(['kd_sub_kegiatan' => $kd_sub_kegiatan, 'kd_skpd' => $kd_skpd, 'status_aktif' => '1'])->orderBy('kd_rek6')->get();
        return response()->json($data);
    }

    public function jumlahAnggaranPenyusunan(Request $request)
    {
        $skpd = $request->skpd;
        $kdgiat = $request->kdgiat;
        $kdrek = $request->kdrek;
        $no_spp = $request->no_spp;
        $status_anggaran = DB::table('trhrka')->select('jns_ang')->where(['kd_skpd' => $skpd, 'status' => 1])->orderBy('tgl_dpa', 'DESC')->first();
        $no_bukti1 = DB::select(DB::raw("SELECT no_tagih from trhspp where kd_skpd='$skpd'"));
        $no_bukti = json_decode(json_encode($no_bukti1), true);

        $query1 = DB::table('trdspp as a')->select(DB::raw("SUM(a.nilai) as nilai"))->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.kd_sub_kegiatan' => $kdgiat, 'a.kd_rek6' => $kdrek, 'a.kd_skpd' => $skpd])->where('a.no_spp', '<>', $no_spp)->whereIn('b.jns_spp', ['3', '4', '5', '6'])->where(function ($query) {
            $query->where('b.sp2d_batal', '<>', '1')
                ->orWhereNull('b.sp2d_batal');
        });

        $query2 = DB::table('trdtagih as t')->select(DB::raw("SUM(nilai) as nilai"))->join('trhtagih as u', function ($join) {
            $join->on('t.no_bukti', '=', 'u.no_bukti');
            $join->on('t.kd_skpd', '=', 'u.kd_skpd');
        })->where(['t.kd_sub_kegiatan' => $kdgiat, 't.kd_rek' => $kdrek, 'u.kd_skpd' => $skpd])->whereNotIn('u.no_bukti', $no_bukti)->unionAll($query1);

        $query3 = DB::table('trdtransout as a')->select(DB::raw("SUM(a.nilai) as nilai"))->join('trhtransout as b', function ($join) {
            $join->on('a.no_bukti', '=', 'b.no_bukti');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.kd_sub_kegiatan' => $kdgiat, 'a.kd_rek6' => $kdrek, 'a.kd_skpd' => $skpd])->whereIn('b.jns_spp', ['1', '2'])->unionAll($query2);

        $query4 = DB::table('trdtransout as a')->select(DB::raw("SUM(a.nilai) as nilai"))->join('trhtransout as b', function ($join) {
            $join->on('a.no_bukti', '=', 'b.no_bukti');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.kd_sub_kegiatan' => $kdgiat, 'a.kd_rek6' => $kdrek, 'a.kd_skpd' => $skpd])->whereIn('b.jns_spp', ['4', '6'])->whereIn('panjar', ['3'])->unionAll($query3);

        $query5 = DB::table('trdtransout_cmsbank as a')->select(DB::raw("SUM(a.nilai) as nilai"))->join('trhtransout_cmsbank as b', function ($join) {
            $join->on('a.no_voucher', '=', 'b.no_voucher');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.kd_sub_kegiatan' => $kdgiat, 'a.kd_rek6' => $kdrek, 'a.kd_skpd' => $skpd, 'b.status_validasi' => '0'])->unionAll($query4);

        $result = DB::table(DB::raw("({$query5->toSql()}) AS sub"))
            ->select(DB::raw("SUM(nilai) as rektotal_spp_lalu"))
            ->mergeBindings($query5)
            ->first();

        $rektotal = DB::table('trdrka')->select(DB::raw("SUM(nilai) as rektotal"))->where(['kd_rek6' => $kdrek, 'kd_sub_kegiatan' => $kdgiat, 'jns_ang' => $status_anggaran->jns_ang, 'kd_skpd' => $skpd])->first();

        return response()->json([
            'rektotal' => $rektotal->rektotal,
            'rektotal_lalu' => $result->rektotal_spp_lalu
        ]);
    }

    public function totalSpd(Request $request)
    {
        $skpd = $request->skpd;
        $kdgiat = $request->kdgiat;
        $kdrek = $request->kdrek;
        $no_spp = $request->no_spp;
        $tgl_spd = $request->tgl_spd;
        $tgl_spp = $request->tgl_spp;
        $beban = $request->beban;

        $sql1 = DB::table('trhspd')->selectRaw('MAX(revisi_ke) as revisi')->whereRaw("LEFT(kd_skpd,17) = LEFT('$skpd',17)")->where('bulan_akhir', '3')->where('tgl_spd', '<=', $tgl_spp)->first();
        $revisi1 = $sql1->revisi;
        $sql2 = DB::table('trhspd')->selectRaw('ISNULL(MAX(revisi_ke),0) as revisi')->whereRaw("LEFT(kd_skpd,17) = LEFT('$skpd',17)")->where('bulan_akhir', '6')->where('tgl_spd', '<=', $tgl_spp)->first();
        $revisi2 = $sql2->revisi;
        $sql3 = DB::table('trhspd')->selectRaw('ISNULL(MAX(revisi_ke),0) as revisi')->whereRaw("LEFT(kd_skpd,17) = LEFT('$skpd',17)")->where('bulan_akhir', '9')->where('tgl_spd', '<=', $tgl_spp)->first();
        $revisi3 = $sql3->revisi;
        $sql4 = DB::table('trhspd')->selectRaw('ISNULL(MAX(revisi_ke),0) as revisi')->whereRaw("LEFT(kd_skpd,17) = LEFT('$skpd',17)")->where('bulan_akhir', '12')->where('tgl_spd', '<=', $tgl_spp)->first();
        $revisi4 = $sql4->revisi;

        $spd1 = DB::table('trdspd as a')->select(DB::raw("'TW1' as ket"), DB::raw("ISNULL(SUM(a.nilai),0) as nilai"))->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['a.kd_unit' => $skpd, 'a.kd_sub_kegiatan' => $kdgiat, 'a.kd_rek6' => $kdrek, 'b.status' => '1', 'bulan_akhir' => '3', 'revisi_ke' => $revisi1])->where('tgl_spd', '<=', $tgl_spp)->where('bulan_awal', '<=', DB::raw("MONTH('$tgl_spp')"));
        $spd2 = DB::table('trdspd as a')->select(DB::raw("'TW2' as ket"), DB::raw("ISNULL(SUM(a.nilai),0) as nilai"))->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['a.kd_unit' => $skpd, 'a.kd_sub_kegiatan' => $kdgiat, 'a.kd_rek6' => $kdrek, 'b.status' => '1', 'bulan_akhir' => '6', 'revisi_ke' => $revisi2])->where('tgl_spd', '<=', $tgl_spp)->where('bulan_awal', '<=', DB::raw("MONTH('$tgl_spp')"))->unionAll($spd1);
        $spd3 = DB::table('trdspd as a')->select(DB::raw("'TW3' as ket"), DB::raw("ISNULL(SUM(a.nilai),0) as nilai"))->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['a.kd_unit' => $skpd, 'a.kd_sub_kegiatan' => $kdgiat, 'a.kd_rek6' => $kdrek, 'b.status' => '1', 'bulan_akhir' => '9', 'revisi_ke' => $revisi3])->where('tgl_spd', '<=', $tgl_spp)->where('bulan_awal', '<=', DB::raw("MONTH('$tgl_spp')"))->unionAll($spd2);
        $spd4 = DB::table('trdspd as a')->select(DB::raw("'TW3' as ket"), DB::raw("ISNULL(SUM(a.nilai),0) as nilai"))->join('trhspd as b', 'a.no_spd', '=', 'b.no_spd')->where(['a.kd_unit' => $skpd, 'a.kd_sub_kegiatan' => $kdgiat, 'a.kd_rek6' => $kdrek, 'b.status' => '1', 'bulan_akhir' => '12', 'revisi_ke' => $revisi4])->where('tgl_spd', '<=', $tgl_spp)->where('bulan_awal', '<=', DB::raw("MONTH('$tgl_spp')"))->unionAll($spd3);

        $result = DB::table(DB::raw("({$spd4->toSql()}) AS sub"))
            ->select(DB::raw("SUM(nilai) as total_spd"))
            ->mergeBindings($spd4)
            ->first();
        return response()->json($result);
    }

    public function totalAngkas(Request $request)
    {
        $skpd = $request->skpd;
        $kd_skpd = Auth::user()->kd_skpd;
        $kdgiat = $request->kdgiat;
        $kdrek = $request->kdrek;
        $no_spp = $request->no_spp;
        $nomor_spd = $request->nomor_spd;
        $tgl_spd = $request->tgl_spd;
        $tgl_spp = $request->tgl_spp;
        $bulan = date('m', strtotime($tgl_spp));
        $beban = $request->beban;
        $status_ang = $request->status_ang;
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

        $status_anggaran = DB::table('trhrka')->select('jns_ang')->where(['kd_skpd' => $kd_skpd, 'status' => 1])->orderBy('tgl_dpa', 'DESC')->first();

        $hasil = DB::table('trhspd')->select(DB::raw("COUNT(*) as spd"))->whereRaw("LEFT(kd_skpd,17) = LEFT('$skpd',17)")->groupBy('bulan_awal', 'bulan_akhir')->first();

        if ($beban == '4' || substr($kdgiat, 5, 10) == '01.1.02.01') {
            $bulan1 = $bulan  + 1;
        } else {
            $bulan1 = $bulan;
        }

        $data = DB::table('trdskpd_ro as a')->select('a.kd_sub_kegiatan', 'kd_rek6', DB::raw("SUM(a.$field_angkas) as nilai"))->join('trskpd as b', function ($join) {
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            $join->on('a.kd_sub_kegiatan', '=', 'b.kd_sub_kegiatan');
        })->where(['a.kd_skpd' => $skpd, 'a.kd_sub_kegiatan' => $kdgiat, 'a.kd_rek6' => $kdrek, 'jns_ang' => $status_anggaran->jns_ang])->where('bulan', '<=', $bulan1)->groupBy('a.kd_sub_kegiatan', 'a.kd_rek6')->first();

        return response()->json($data);
    }

    public function realisasiSpd(Request $request)
    {
        $skpd = $request->skpd;
        $kdgiat = $request->kdgiat;
        $kdrek = $request->kdrek;
        $sql = DB::table('trhspp')->select('no_tagih')->where('kd_skpd', $skpd)->get();
        $no_bukti = json_decode(json_encode($sql), true);
        $realisasi1 = DB::table('tb_transaksi')->selectRaw("SUM(ISNULL(nilai,0)) as nilai")->where(['kd_sub_kegiatan' => $kdgiat, 'kd_skpd' => $skpd, 'kd_rek6' => $kdrek]);

        $realisasi2 = DB::table('trdtransout as c')->leftJoin('trhtransout as d', function ($join) {
            $join->on('c.no_bukti', '=', 'd.no_bukti');
            $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        })->selectRaw("SUM(ISNULL(c.nilai,0)) as nilai")->where(['c.kd_sub_kegiatan' => $kdgiat, 'd.kd_skpd' => $skpd, 'c.kd_rek6' => $kdrek])->whereIn('d.jns_spp', ['1'])->unionAll($realisasi1);

        $realisasi3 = DB::table('trdtransout_cmsbank as c')->leftJoin('trhtransout_cmsbank as d', function ($join) {
            $join->on('c.no_voucher', '=', 'd.no_voucher');
            $join->on('c.kd_skpd', '=', 'd.kd_skpd');
        })->selectRaw("SUM(ISNULL(c.nilai,0)) as nilai")->where(['c.kd_sub_kegiatan' => $kdgiat, 'd.kd_skpd' => $skpd, 'c.kd_rek6' => $kdrek])->whereIn('d.jns_spp', ['1'])->where(function ($query) {
            $query->where('d.status_validasi', '=', '0')
                ->orWhereNull('d.status_validasi');
        })->unionAll($realisasi2);

        $realisasi4 = DB::table('trdspp as x')->join('trhspp as y', function ($join) {
            $join->on('x.no_spp', '=', 'y.no_spp');
            $join->on('x.kd_skpd', '=', 'y.kd_skpd');
        })->selectRaw("SUM(ISNULL(x.nilai,0)) as nilai")->where(['x.kd_sub_kegiatan' => $kdgiat, 'x.kd_skpd' => $skpd, 'x.kd_rek6' => $kdrek])->whereIn('y.jns_spp', ['3', '4', '5', '6'])->where(function ($query) {
            $query->where('sp2d_batal', '=', '0')
                ->orWhereNull('sp2d_batal');
        })->unionAll($realisasi3);

        $realisasi5 = DB::table('trdtagih as t')->selectRaw("SUM(ISNULL(t.nilai,0)) as nilai")->join('trhtagih as u', function ($join) {
            $join->on('t.no_bukti', '=', 'u.no_bukti');
            $join->on('t.kd_skpd', '=', 'u.kd_skpd');
        })->where(['t.kd_sub_kegiatan' => $kdgiat, 't.kd_rek' => $kdrek, 'u.kd_skpd' => $skpd])->whereNotIn('u.no_bukti', $no_bukti)->unionAll($realisasi4);

        $result = DB::table(DB::raw("({$realisasi5->toSql()}) AS sub"))
            ->select(DB::raw("SUM(nilai) as total"))
            ->mergeBindings($realisasi5)
            ->first();
        return response()->json($result);
    }

    public function sumberDana(Request $request)
    {
        $kode               = $request->skpd;
        $giat               = $request->kdgiat;
        $rek                = $request->kdrek;
        $status             = DB::table('trhrka')->where(['kd_skpd' => $kode, 'status' => '1'])->orderByDesc('tgl_dpa')->first();
        $status_anggaran    = $status->jns_ang;

        $data = DB::select(DB::raw("SELECT sumber_dana,nilai, isnull( tagihlalu, 0 ) + isnull( tampungan, 0 ) + isnull( spplalu, 0 ) + isnull( upgulalucms, 0 ) + isnull( upgulalu, 0 ) AS lalu FROM (SELECT sumber1 AS sumber_dana, isnull( nsumber1, 0 ) AS nilai,
            (
            SELECT SUM
                ( nilai ) AS nilai
            FROM
                trdtagih t
                INNER JOIN trhtagih u ON t.no_bukti= u.no_bukti
                AND t.kd_skpd= u.kd_skpd
            WHERE
                t.kd_sub_kegiatan = '$giat'
                AND u.kd_skpd = '$kode'
                AND t.kd_rek6 = '$rek'
                AND u.no_bukti NOT IN ( SELECT no_tagih FROM trhspp WHERE kd_skpd = '$kode' )
                AND sumber = z.sumber1
            ) AS tagihlalu,
            (
            SELECT SUM
                ( nilai ) AS nilai
            FROM
                tb_transaksi
            WHERE
                kd_sub_kegiatan = '$giat'
                AND kd_skpd = '$kode'
                AND kd_rek6 = '$rek'
                AND sumber = z.sumber1
            ) AS tampungan,
            (
            SELECT SUM
                ( b.nilai )
            FROM
                trhspp a
                INNER JOIN trdspp b ON a.no_spp= b.no_spp
                AND a.kd_skpd= b.kd_skpd
            WHERE
                b.kd_skpd= '$kode'
                AND b.kd_Sub_kegiatan= '$giat'
                AND b.kd_rek6= '$rek'
                AND sumber = sumber1
                AND ( sp2d_batal <> '1' OR sp2d_batal IS NULL )
                AND jns_spp NOT IN ( '1', '2' )
            ) AS spplalu,
            (
            SELECT SUM
                ( g.nilai )
            FROM
                trhtransout_cmsbank f
                INNER JOIN trdtransout_cmsbank g ON f.no_voucher= g.no_voucher
                AND f.kd_skpd= g.kd_skpd
            WHERE
                g.kd_skpd = '$kode'
                AND g.kd_sub_kegiatan= '$giat'
                AND g.kd_rek6= '$rek'
                AND f.jns_spp IN ( '1' )
                AND ( f.status_validasi= '0' OR f.status_validasi IS NULL )
                AND sumber = z.sumber1
            ) upgulalucms,
            (
            SELECT SUM
                ( g.nilai )
            FROM
                trhtransout f
                INNER JOIN trdtransout g ON f.no_bukti= g.no_bukti
                AND f.kd_skpd= g.kd_skpd
            WHERE
                g.kd_skpd = '$kode'
                AND g.kd_sub_kegiatan= '$giat'
                AND g.kd_rek6= '$rek'
                AND f.jns_spp IN ( '1' )
                AND sumber = z.sumber1
            ) upgulalu
            FROM
                trdrka z
            WHERE
                z.kd_skpd= '$kode'
                AND z.kd_sub_kegiatan= '$giat'
                AND jns_ang = '$status_anggaran'
                AND z.kd_rek6= '$rek' UNION ALL
            SELECT
                sumber2 AS sumber_dana,
                isnull( nsumber2, 0 ) AS nilai,
                (
                SELECT SUM
                    ( nilai ) AS nilai
                FROM
                    trdtagih t
                    INNER JOIN trhtagih u ON t.no_bukti= u.no_bukti
                    AND t.kd_skpd= u.kd_skpd
                WHERE
                    t.kd_sub_kegiatan = '$giat'
                    AND u.kd_skpd = '$kode'
                    AND t.kd_rek6 = '$rek'
                    AND u.no_bukti NOT IN ( SELECT no_tagih FROM trhspp WHERE kd_skpd = '$kode' )
                    AND sumber = z.sumber2
                ) AS tagihlalu,
                (
                SELECT SUM
                    ( nilai ) AS nilai
                FROM
                    tb_transaksi
                WHERE
                    kd_sub_kegiatan = '$giat'
                    AND kd_skpd = '$kode'
                    AND kd_rek6 = '$rek'
                    AND sumber = z.sumber2
                ) AS tampungan,
                (
                SELECT SUM
                    ( u.nilai ) AS nilai
                FROM
                    trhspp t
                    INNER JOIN trdspp u ON t.no_spp= u.no_spp
                    AND t.kd_skpd= u.kd_skpd
                WHERE
                    u.kd_sub_kegiatan = '$giat'
                    AND u.kd_skpd = '$kode'
                    AND u.kd_rek6 = '$rek'
                    AND sumber = z.sumber2
                    AND ( sp2d_batal <> '1' OR sp2d_batal IS NULL )
                    AND jns_spp NOT IN ( '1', '2' )
                ) AS spplalu,
                (
                SELECT SUM
                    ( g.nilai )
                FROM
                    trhtransout_cmsbank f
                    INNER JOIN trdtransout_cmsbank g ON f.no_voucher= g.no_voucher
                    AND f.kd_skpd= g.kd_skpd
                WHERE
                    g.kd_skpd = '$kode'
                    AND g.kd_sub_kegiatan= '$giat'
                    AND g.kd_rek6= '$rek'
                    AND f.jns_spp IN ( '1' )
                    AND ( f.status_validasi= '0' OR f.status_validasi IS NULL )
                    AND sumber = z.sumber2
                ) upgulalucms,
                (
                SELECT SUM
                    ( g.nilai )
                FROM
                    trhtransout f
                    INNER JOIN trdtransout g ON f.no_bukti= g.no_bukti
                    AND f.kd_skpd= g.kd_skpd
                WHERE
                    g.kd_skpd = '$kode'
                    AND g.kd_sub_kegiatan= '$giat'
                    AND g.kd_rek6= '$rek'
                    AND f.jns_spp IN ( '1' )
                    AND sumber = z.sumber2
                ) upgulalu
            FROM
                trdrka z
            WHERE
                z.kd_sub_kegiatan= '$giat'
                AND z.kd_rek6= '$rek'
                AND jns_ang = '$status_anggaran'
                AND z.kd_skpd= '$kode' UNION ALL
            SELECT
                sumber3 AS sumber_dana,
                isnull( nsumber3, 0 ) AS nilai,
                (
                SELECT SUM
                    ( nilai ) AS nilai
                FROM
                    trdtagih t
                    INNER JOIN trhtagih u ON t.no_bukti= u.no_bukti
                    AND t.kd_skpd= u.kd_skpd
                WHERE
                    t.kd_sub_kegiatan = '$giat'
                    AND u.kd_skpd = '$kode'
                    AND t.kd_rek6 = '$rek'
                    AND u.no_bukti NOT IN ( SELECT no_tagih FROM trhspp WHERE kd_skpd = '$kode' )
                    AND sumber = sumber3
                ) AS tagihlalu,
                (
                SELECT SUM
                    ( nilai ) AS nilai
                FROM
                    tb_transaksi
                WHERE
                    kd_sub_kegiatan = '$giat'
                    AND kd_skpd = '$kode'
                    AND kd_rek6 = '$rek'
                    AND sumber = a.sumber3
                ) AS tampungan,
                (
                SELECT SUM
                    ( t.nilai ) AS nilai
                FROM
                    trdspp t
                    INNER JOIN trhspp u ON t.no_spp= u.no_spp
                    AND t.kd_skpd= u.kd_skpd
                WHERE
                    t.kd_sub_kegiatan = '$giat'
                    AND t.kd_skpd = '$kode'
                    AND t.kd_rek6 = '$rek'
                    AND sumber = sumber3
                    AND jns_spp NOT IN ( '1', '2' )
                    AND ( sp2d_batal <> '1' OR sp2d_batal IS NULL )
                ) AS spplalu,
                (
                SELECT SUM
                    ( g.nilai )
                FROM
                    trhtransout_cmsbank f
                    INNER JOIN trdtransout_cmsbank g ON f.no_voucher= g.no_voucher
                    AND f.kd_skpd= g.kd_skpd
                WHERE
                    g.kd_skpd = '$kode'
                    AND g.kd_sub_kegiatan= '$giat'
                    AND g.kd_rek6= '$rek'
                    AND f.jns_spp IN ( '1' )
                    AND ( f.status_validasi= '0' OR f.status_validasi IS NULL )
                    AND sumber = sumber3
                ) upgulalucms,
                (
                SELECT SUM
                    ( g.nilai )
                FROM
                    trhtransout f
                    INNER JOIN trdtransout g ON f.no_bukti= g.no_bukti
                    AND f.kd_skpd= g.kd_skpd
                WHERE
                    g.kd_skpd = '$kode'
                    AND g.kd_sub_kegiatan= '$giat'
                    AND g.kd_rek6= '$rek'
                    AND f.jns_spp IN ( '1' )
                    AND sumber = sumber3
                ) upgulalu
            FROM
                trdrka a
            WHERE
                a.kd_sub_kegiatan= '$giat'
                AND a.kd_rek6= '$rek'
                AND jns_ang = '$status_anggaran'
                AND a.kd_skpd= '$kode' UNION ALL
            SELECT
                sumber4 AS sumber_dana,
                isnull( nsumber4, 0 ) AS nilai,
                (
                SELECT SUM
                    ( nilai ) AS nilai
                FROM
                    trdtagih t
                    INNER JOIN trhtagih u ON t.no_bukti= u.no_bukti
                    AND t.kd_skpd= u.kd_skpd
                WHERE
                    t.kd_sub_kegiatan = '$giat'
                    AND u.kd_skpd = '$kode'
                    AND t.kd_rek6 = '$rek'
                    AND u.no_bukti NOT IN ( SELECT no_tagih FROM trhspp WHERE kd_skpd = '$kode' )
                    AND sumber = sumber4
                ) AS lalu,
                (
                SELECT SUM
                    ( nilai ) AS nilai
                FROM
                    tb_transaksi
                WHERE
                    kd_sub_kegiatan = '$giat'
                    AND kd_skpd = '$kode'
                    AND kd_rek6 = '$rek'
                    AND sumber = a.sumber4
                ) AS tampungan,
                (
                SELECT SUM
                    ( t.nilai ) AS nilai
                FROM
                    trdspp t
                    INNER JOIN trhspp u ON t.no_spp= u.no_spp
                    AND t.kd_skpd= u.kd_skpd
                WHERE
                    t.kd_sub_kegiatan = '$giat'
                    AND t.kd_skpd = '$kode'
                    AND t.kd_rek6 = '$rek'
                    AND jns_spp NOT IN ( '1', '2' )
                    AND sumber = sumber4
                    AND ( sp2d_batal <> '1' OR sp2d_batal IS NULL )
                ) AS lalu,
                (
                SELECT SUM
                    ( g.nilai )
                FROM
                    trhtransout_cmsbank f
                    INNER JOIN trdtransout_cmsbank g ON f.no_voucher= g.no_voucher
                    AND f.kd_skpd= g.kd_skpd
                WHERE
                    g.kd_skpd = '$kode'
                    AND g.kd_sub_kegiatan= '$giat'
                    AND g.kd_rek6= '$rek'
                    AND f.jns_spp IN ( '1' )
                    AND ( f.status_validasi= '0' OR f.status_validasi IS NULL )
                    AND sumber = sumber4
                ) upgulalucms,
                (
                SELECT SUM
                    ( g.nilai )
                FROM
                    trhtransout f
                    INNER JOIN trdtransout g ON f.no_bukti= g.no_bukti
                    AND f.kd_skpd= g.kd_skpd
                WHERE
                    g.kd_skpd = '$kode'
                    AND g.kd_sub_kegiatan= '$giat'
                    AND g.kd_rek6= '$rek'
                    AND f.jns_spp IN ( '1' )
                    AND sumber = sumber4
                ) upgulalu
            FROM
                trdrka a
            WHERE
                a.kd_sub_kegiatan= '$giat'
                AND a.kd_rek6= '$rek'
                AND jns_ang = '$status_anggaran'
                AND a.kd_skpd= '$kode'
            ) z
            WHERE
            z.nilai<>0"));
        return response()->json($data);
    }

    public function cariNoSpp(Request $request)
    {
        $jenis_beban2 = $request->jenis_beban2;
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhspp')->selectRaw("ISNULL(MAX(urut),0)+1 as nilai")->where('kd_skpd', $kd_skpd)->first();
        return response()->json($data);
    }

    public function cekSimpan(Request $request)
    {
        $no_spp = $request->no_spp;
        $data = DB::table('trhspp')->where('no_spp', $no_spp)->count();
        return response()->json($data);
    }

    public function simpanSppLs(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;
        $nama = Auth::user()->nama;
        $cek = DB::table('trhspp')->where('no_spp', $data['no_spp'])->count();
        DB::beginTransaction();
        try {
            if ($cek > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            } else {
                // input trhspp
                DB::table('trhspp')->insert([
                    'no_spp' => $data['no_spp'],
                    'kd_skpd' => $data['kd_skpd'],
                    'keperluan' => $data['keperluan'],
                    'bulan' => $data['bulan'],
                    'no_spd' => $data['nomor_spd'],
                    'jns_spp' => $data['beban'],
                    'jns_beban' => $data['jenis'],
                    'bank' => $data['bank'],
                    'nmrekan' => $data['rekanan'],
                    'no_rek' => $data['rekening'],
                    'npwp' => $data['npwp'],
                    'nm_skpd' => $data['nm_skpd'],
                    'tgl_spp' => $data['tgl_spp'],
                    'status' => '0',
                    'username' => Auth::user()->nama,
                    'nilai' => $data['total'],
                    'kd_sub_kegiatan' => $data['kd_sub_kegiatan'],
                    'nm_sub_kegiatan' => $data['nm_sub_kegiatan'],
                    'kd_program' => $data['kd_program'],
                    'nm_program' => $data['nm_program'],
                    'pimpinan' => $data['pimpinan'],
                    'no_tagih' => $data['no_penagihan'],
                    'tgl_tagih' => $data['tgl_penagihan'],
                    'sts_tagih' => $data['sts_tagih'],
                    'alamat' => $data['alamat'],
                    'kontrak' => $data['no_kontrak'],
                    'lanjut' => $data['lanjut'],
                    'tgl_mulai' => $data['tgl_awal'],
                    'tgl_akhir' => $data['tgl_akhir'],
                    'urut' => $data['no_urut'],
                    'penerima' => $data['nm_penerima'],
                ]);
                // set status trhtagih
                if ($data['no_penagihan']) {
                    DB::table('trhtagih')->where(['no_bukti' => $data['no_penagihan'], 'kd_skpd' => $kd_skpd])->update([
                        'sts_tagih' => '1',
                    ]);
                }
                DB::table('trhspp')->where(['no_spp' => $data['no_spp'], 'kd_skpd' => $kd_skpd])->update([
                    'username' => $nama,
                    'last_update' => date('Y-m-d H:i:s'),
                ]);
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

    public function simpanDetailSppLs(Request $request)
    {
        $data = $request->data;
        $kd_skpd = Auth::user()->kd_skpd;
        $nama = Auth::user()->nama;
        DB::beginTransaction();
        try {
            DB::table('trdspp')->where(['no_spp' => $data['no_spp'], 'kd_skpd' => $kd_skpd])->delete();
            if (isset($data['rincian_rekening'])) {
                DB::table('trdspp')->insert(array_map(function ($value) use ($data, $kd_skpd) {
                    return [
                        'no_spp' => $data['no_spp'],
                        'kd_rek6' => $value['kd_rek6'],
                        'nm_rek6' => $value['nm_rek6'],
                        'nilai' => $value['nilai'],
                        'kd_skpd' => $kd_skpd,
                        'kd_sub_kegiatan' => $value['kd_sub_kegiatan'],
                        'nm_sub_kegiatan' => $data['nm_sub_kegiatan'],
                        'no_spd' => $data['nomor_spd'],
                        'kd_bidang' => $data['bidang'],
                        'sumber' => $value['sumber'],
                        'volume' => $value['volume_output'],
                        'satuan' => $value['satuan_output'],
                    ];
                }, $data['rincian_rekening']));
            }
            DB::table('tb_transaksi')->where(['kd_skpd' => $kd_skpd, 'no_transaksi' => $data['no_spp'], 'username' => $nama])->delete();
            DB::table('trhspp')->where(['kd_skpd' => $kd_skpd, 'no_spp' => $data['no_spp']])->whereNull('username')->update([
                'username'  => $nama,
                'last_update' => date('Y-m-d H:i:s')
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

    public function tampilSppLs($no_spp)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data_sppls = DB::table('trhspp as a')->join('trdspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where('a.no_spp', $no_spp)->select('a.*')->first();
        $data = [
            'sppls' => $data_sppls,
            'tgl_spd' => DB::table('trhspd')->select('tgl_spd')->where('no_spd', $data_sppls->no_spd)->first(),
            'bank' => DB::table('ms_bank')->select('nama')->where('kode', $data_sppls->bank)->first(),
            'detail_spp' => DB::table('trdspp as a')->select('a.*', 'c.nm_sumber_dana1')->join('trhspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('sumber_dana as c', 'a.sumber', '=', 'c.kd_sumber_dana1')->where('a.no_spp', $no_spp)->get(),
        ];
        return view('penatausahaan.pengeluaran.spp_ls.show')->with($data);
    }

    public function editSppLs($no_spp)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $perusahaan1 = DB::table('ms_perusahaan')->select('nama as nmrekan', 'pimpinan', 'npwp', 'alamat')->whereRaw('LEFT(kd_skpd,17) = ?', [$kd_skpd])->groupBy('nama', 'pimpinan', 'npwp', 'alamat');
        $perusahaan2 = DB::table('trhspp')->select('nmrekan', 'pimpinan', 'npwp', 'alamat')->whereRaw('LEN(nmrekan)>1')->where('kd_skpd', $kd_skpd)->groupBy('nmrekan', 'pimpinan', 'npwp', 'alamat')->unionAll($perusahaan1);
        $perusahaan3 = DB::table('trhtrmpot_cmsbank')->select('nmrekan', 'pimpinan', 'npwp', 'alamat')->whereRaw('LEN(nmrekan)>1')->where('kd_skpd', $kd_skpd)->groupBy('nmrekan', 'pimpinan', 'npwp', 'alamat')->unionAll($perusahaan2);
        $perusahaan4 = DB::table('trhtrmpot')->select('nmrekan', 'pimpinan', 'npwp', 'alamat')->whereRaw('LEN(nmrekan)>1')->where('kd_skpd', $kd_skpd)->groupBy('nmrekan', 'pimpinan', 'npwp', 'alamat')->unionAll($perusahaan3);
        $result = DB::table(DB::raw("({$perusahaan4->toSql()}) AS sub"))
            ->select("nmrekan", "pimpinan", "npwp", "alamat")
            ->mergeBindings($perusahaan4)
            ->groupBy('nmrekan', 'pimpinan', 'npwp', 'alamat')
            ->orderBy('nmrekan', 'ASC')
            ->orderBy('pimpinan', 'ASC')
            ->orderBy('npwp', 'ASC')
            ->orderBy('alamat', 'ASC')
            ->get();
        $data1 = DB::select(DB::raw("SELECT isnull(no_tagih,'') no_tagih from trhspp where kd_skpd='$kd_skpd' and (sp2d_batal is null OR sp2d_batal<>'1') GROUP BY no_tagih"));
        $data2 = json_decode(json_encode($data1), true);
        $data = [
            'daftar_rekanan' => $result,
            'sppls' => DB::table('trhspp as a')->join('trdspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->where('a.no_spp', $no_spp)->select('a.*')->first(),
            'daftar_penagihan' => DB::table('trhtagih as a')->select('a.kd_skpd', 'a.no_bukti', 'tgl_bukti', 'a.ket', 'a.kontrak', 'kd_sub_kegiatan', DB::raw('SUM(b.nilai) as total'))->join('trdtagih as b', 'a.no_bukti', '=', 'b.no_bukti')->where('a.kd_skpd', $kd_skpd)->where('a.jns_trs', '1')->whereNotIn('a.no_bukti', $data2)->groupBy('a.kd_skpd', 'a.no_bukti', 'tgl_bukti', 'a.ket', 'a.kontrak', 'kd_sub_kegiatan')->orderBy('a.no_bukti')->get(),
            'data_tgl' => DB::table('trhspp')->selectRaw('MAX(tgl_spp) as tgl_spp')->where('kd_skpd', $kd_skpd)->where(function ($query) {
                $query->where('sp2d_batal', '=', '0')
                    ->orWhereNull('sp2d_batal');
            })->first(),
            'daftar_penerima' => DB::table('ms_rekening_bank_online')->select('rekening', 'nm_rekening', 'npwp')->where('kd_skpd', $kd_skpd)->orderBy('rekening')->get(),
            'daftar_bank' => DB::table('ms_bank')->select('kode', 'nama')->orderBy('kode')->get(),
            'detail_spp' => DB::table('trdspp as a')->select('a.*', 'c.nm_sumber_dana1')->join('trhspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->join('sumber_dana as c', 'a.sumber', '=', 'c.kd_sumber_dana1')->where('a.no_spp', $no_spp)->get(),
        ];
        return view('penatausahaan.pengeluaran.spp_ls.edit')->with($data);
    }

    public function hapusSppLs(Request $request)
    {
        $no_spp = $request->no_spp;
        $kd_skpd = Auth::user()->kd_skpd;

        DB::beginTransaction();
        try {
            DB::table('trhspp')->where(['no_spp' => $no_spp, 'kd_skpd' => $kd_skpd])->delete();
            DB::table('trdspp')->where(['no_spp' => $no_spp, 'kd_skpd' => $kd_skpd])->delete();
            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function cariPenagihanSpp(Request $request)
    {
        $no_bukti = $request->no_bukti;
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trdtagih as a')->join('sumber_dana as b', 'a.sumber', '=', 'b.kd_sumber_dana1')->where(['a.no_bukti' => $no_bukti, 'a.kd_skpd' => $kd_skpd])->get();
        return response()->json($data);
    }
}
