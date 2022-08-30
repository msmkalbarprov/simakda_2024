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
        $data = [
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $skpd)->first(),
            'daftar_rekanan' => $result,
            'daftar_penerima' => DB::table('ms_rekening_bank_online')->select('rekening', 'nm_rekening', 'npwp')->where('kd_skpd', $skpd)->orderBy('rekening')->get(),
            'daftar_bank' => DB::table('ms_bank')->select('kode', 'nama')->orderBy('kode')->get(),
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
}
