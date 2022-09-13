<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SpmController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'data_spm' => DB::table('trhspm as a')->join('trhspp as b', 'a.no_spp', '=', 'b.no_spp')->where(['a.kd_skpd' => $kd_skpd])->select('a.*', DB::raw("ISNULL(b.sp2d_batal,'') as sp2d_batal"), DB::raw("ISNULL(ket_batal,'') as ket_batal"))->orderBy('a.no_spm', 'asc')->orderBy('a.kd_skpd', 'asc')->get(),
        ];
        return view('penatausahaan.pengeluaran.spm.index')->with($data);
    }

    public function create()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $tanggal = date('d');
        $bulan = date('m');
        if ($bulan - 1 == 0) {
            $bulan2 = 1;
        } else {
            $bulan2 = $bulan - 1;
        }
        $data1 = DB::table('trhspm')->select('no_spp')->where(['kd_skpd' => $kd_skpd])->get();
        $data2 = json_decode(json_encode($data1), true);
        $skpd1 = DB::table('trhspj_ppkd')->select('kd_skpd')->where(['bulan' => $bulan2, 'cek' => '1', 'kd_skpd' => $kd_skpd])->get();
        $skpd = json_decode(json_encode($skpd1), true);
        $prov = DB::table('trhspj_ppkd')->select(DB::raw("ISNULL(cek,0) as cek"))->where(['kd_skpd' => $kd_skpd, 'bulan' => $bulan2])->first();
        $cek = $prov->cek;

        if ($cek == '0' || $cek == null || $cek == 0) {
            if ($tanggal < 13) {
                $data_spp1 = DB::table('trhspp')->select('no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'jns_beban', DB::raw("(replace( replace( npwp, '.', '' ), '-', '' )) as npwp"))->where('kd_skpd', $kd_skpd)->where('jns_spp', '!=', '3')->where(function ($query) {
                    $query->where('sp2d_batal', '!=', '1')->orWhereNull('sp2d_batal');
                })->whereNotIn('no_spp', $data2);
                $data_spp2 = DB::table('trhspp')->select('no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'jns_beban', DB::raw("(replace( replace( npwp, '.', '' ), '-', '' )) as npwp"))->where(['kd_skpd' => $kd_skpd, 'jns_spp' => '3', 'sts_setuju' => '1'])->where(function ($query) {
                    $query->where('sp2d_batal', '!=', '1')->orWhereNull('sp2d_batal');
                })->whereNotIn('no_spp', $data2)->unionAll($data_spp1);
                $data_spp = DB::table(DB::raw("({$data_spp2->toSql()}) AS sub"))
                    ->mergeBindings($data_spp2)
                    ->get();
            } else {
                $data_spp = DB::table('trhspp')->select('no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'jns_beban', DB::raw("(replace( replace( npwp, '.', '' ), '-', '' )) as npwp"))->where('kd_skpd', $kd_skpd)->whereIn('jns_spp', ['4', '5', '6'])->where(function ($query) {
                    $query->where('sp2d_batal', '!=', '1')->orWhereNull('sp2d_batal');
                })->whereNotIn('no_spp', $data2)->get();
            }
        } else {
            $data_spp1 = DB::table('trhspp')->select('no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'jns_beban', DB::raw("(replace( replace( npwp, '.', '' ), '-', '' )) as npwp"))->where('kd_skpd', $kd_skpd)->whereIn('jns_spp', ['1', '2'])->where(function ($query) {
                $query->where('sp2d_batal', '!=', '1')->orWhereNull('sp2d_batal');
            })->whereNotIn('no_spp', $data2)->whereIn('kd_skpd', $skpd);
            $data_spp2 = DB::table('trhspp')->select('no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'jns_beban', DB::raw("(replace( replace( npwp, '.', '' ), '-', '' )) as npwp"))->where(['kd_skpd' => $kd_skpd, 'sts_setuju' => '1'])->whereIn('jns_spp', ['3'])->where(function ($query) {
                $query->where('sp2d_batal', '!=', '1')->orWhereNull('sp2d_batal');
            })->whereNotIn('no_spp', $data2)->whereIn('kd_skpd', $skpd)->unionAll($data_spp1);
            $data_spp3 = DB::table('trhspp')->select('no_spp', 'tgl_spp', 'kd_skpd', 'nm_skpd', 'jns_spp', 'keperluan', 'bulan', 'no_spd', 'bank', 'nmrekan', 'no_rek', 'jns_beban', DB::raw("(replace( replace( npwp, '.', '' ), '-', '' )) as npwp"))->where(['kd_skpd' => $kd_skpd])->whereIn('jns_spp', ['4', '5', '6'])->where(function ($query) {
                $query->where('sp2d_batal', '!=', '1')->orWhereNull('sp2d_batal');
            })->whereNotIn('no_spp', $data2)->unionAll($data_spp2);
            $data_spp = DB::table(DB::raw("({$data_spp3->toSql()}) AS sub"))
                ->mergeBindings($data_spp3)
                ->get();
        }
        $data = [
            'data_spp' => $data_spp,
        ];
        return view('penatausahaan.pengeluaran.spm.create')->with($data);
    }

    public function cariJenis(Request $request)
    {
        $beban = $request->beban;
        $jenis = $request->jenis;
        $data = DB::table('ms_jenis_beban')->select('nama', 'jenis')->where(['jns_spp' => $beban, 'jenis' => $jenis])->first();
        return response()->json($data);
    }

    public function cariBank(Request $request)
    {
        $bank = $request->bank;
        $data = DB::table('ms_bank')->select('nama')->where(['kode' => $bank])->first();
        return response()->json($data);
    }

    public function detailSpm(Request $request)
    {
        $no_spp = $request->no_spp;
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trdspp')->select('kd_sub_kegiatan', 'nm_sub_kegiatan', 'kd_rek6', 'nm_rek6', 'nilai', 'sisa', 'no_bukti')->where(['no_spp' => $no_spp, 'kd_skpd' => $kd_skpd])->orderBy('no_bukti')->orderBy('kd_sub_kegiatan')->orderBy('kd_rek6')->get();
        return response()->json($data);
    }

    public function cariNoSpd(Request $request)
    {
        $no_spd = $request->no_spd;
        $data = DB::table('trhspd')->select('tgl_spd')->where(['no_spd' => $no_spd])->first();
        return response()->json($data);
    }

    public function cariNoSpm(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_spm = $request->no_spm;
        $data = DB::table('trhspm')->select(DB::raw("MAX(urut) + 1 as nilai"))->where(['kd_skpd' => $kd_skpd])->where('no_spm', '!=', $no_spm)->first();
        return response()->json($data);
    }

    public function tglSpmLalu(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhspm as a')->join('trhspp as b', function ($join) {
            $join->on('a.no_spp', '=', 'b.no_spp');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where(['a.kd_skpd' => $kd_skpd])->where(function ($query) {
            $query->where('b.sp2d_batal', '=', '0')->orWhereNull('b.sp2d_batal');
        })->select(DB::raw("MAX(a.tgl_spm) as tanggal"))->first();
        return response()->json($data);
    }

    public function simpanSpm(Request $request)
    {
        $no_spm = $request->no_spm;
        $tgl_spm = $request->tgl_spm;
        $no_spp = $request->no_spp;
        $kd_skpd = $request->kd_skpd;
        $nm_skpd = $request->nm_skpd;
        $tgl_spp = $request->tgl_spp;
        $bulan = $request->bulan;
        $no_spd = $request->no_spd;
        $keperluan = $request->keperluan;
        $beban = $request->beban;
        $bank = $request->bank;
        $rekanan = $request->rekanan;
        $rekening = $request->rekening;
        $npwp = $request->npwp;
        $total = $request->total;
        $urut = $request->urut;
        $no_spp = $request->no_spp;
        $jenis = $request->jenis;
        $skpd = Auth::user()->kd_skpd;
        $nama = Auth::user()->nama;

        DB::beginTransaction();
        try {
            $cek = DB::table('trhspm')->where(['no_spp' => $no_spp])->count();
            if ($cek > 0) {
                return response()->json([
                    'message' => '3'
                ]);
            } else {
                $cek1 = DB::table('trhspm')->where(['no_spm' => $no_spm])->count();
                if ($cek1 > 0) {
                    return response()->json([
                        'message' => '1'
                    ]);
                } else {
                    DB::table('trhspm')->insert([
                        'no_spm' => $no_spm,
                        'tgl_spm' => $tgl_spm,
                        'no_spp' => $no_spp,
                        'kd_skpd' => $kd_skpd,
                        'nm_skpd' => $nm_skpd,
                        'tgl_spp' => $tgl_spp,
                        'bulan' => $bulan,
                        'no_spd' => $no_spd,
                        'keperluan' => $keperluan,
                        'jns_spp' => $beban,
                        'jenis_beban' => $jenis,
                        'bank' => $bank,
                        'nmrekan' => $rekanan,
                        'no_rek' => $rekening,
                        'npwp' => $npwp,
                        'nilai' => $total,
                        'urut' => $urut,
                        'status' => '0',
                        'username' => $nama,
                        'last_update' => date('Y-m-d H:i:s')
                    ]);

                    DB::table('trhspp')->where(['no_spp' => $no_spp, 'kd_skpd' => $skpd])->update([
                        'status' => '1'
                    ]);

                    DB::commit();
                    return response()->json([
                        'message' => '2'
                    ]);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }

    public function tambahPotongan($no_spm)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $cari_spm = DB::table('trhspm')->select('no_spp')->where(['no_spm' => $no_spm])->first();
        $data = [
            'daftar_kode_akun' => DB::table('ms_map_billing')->select('kd_map', 'nm_map')->groupBy('nm_map', 'kd_map')->get(),
            'no_spm' => $no_spm,
            'daftar_transaksi' => DB::table('trdspp')->select('kd_rek6', 'nm_rek6')->where(['no_spp' => $cari_spm->no_spp, 'kd_skpd' => $kd_skpd])->groupBy('kd_rek6', 'nm_rek6')->get(),
            'daftar_potongan' => DB::table('ms_pot')->select('kd_rek6', 'map_pot', 'nm_pot as nm_rek6')->groupBy('kd_rek6', 'nm_pot', 'map_pot')->get(),
            'total_pajak' => DB::table('trspmpot')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spm' => $no_spm])->first(),
            'kd_skpd' => $kd_skpd
        ];
        return view('penatausahaan.pengeluaran.spm.tambah_potongan')->with($data);
    }

    public function cariRekPot(Request $request)
    {
        $kode_akun = $request->kode_akun;
        $kode_setor = $request->kode_setor;
        $data = DB::table('ms_pot as a')->join('ms_map_billing as b', 'a.kd_rek6', '=', 'b.kd_rek6')->where(['b.kd_map' => $kode_akun, 'kd_setor' => $kode_setor])->select('a.kd_rek6', 'a.nm_rek6')->groupBy('a.kd_rek6', 'a.nm_rek6')->get();
        return response()->json($data);
    }

    public function loadRincian(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_spm = $request->no_spm;
        $data = DB::table('trspmpot')->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])->whereIn('kd_rek6', ['210105010001', '210105020001', '210105030001', '210109010001', '210106010001'])->get();
        return Datatables::of($data)
            // ->addColumn('hapus', function ($data) {
            //     return '<a href="javascript:void(0);" onclick="hapusPajak(' . $data->no_spm . ',' . $data->kd_rek6 . ',' . $data->nm_rek6 . ',' . $data->idBilling . ',' . $data->nilai . ')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
            // })->rawColumns(['hapus'])
            ->make(true);;
        return view('penatausahaan.pengeluaran.spm.tambah_potongan');
        // return response()->json($data);
    }

    public function hapusRincianPajak(Request $request)
    {
        $no_spm = $request->no_spm;
        $kd_rek6 = $request->kd_rek6;

        DB::beginTransaction();
        try {
            DB::table('trspmpot')->where(['no_spm' => $no_spm, 'kd_rek6' => $kd_rek6])->delete();
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
