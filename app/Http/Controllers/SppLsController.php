<?php

namespace App\Http\Controllers;

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
        $query2 = DB::table('trdtagih as t')->select(DB::raw("SUM(t.nilai) as nilai"))->join('trhtagih as u', function ($join) {
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
}
