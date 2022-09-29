<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SppUpController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'data_spp' => DB::table('trhspp')->where(['kd_skpd' => $kd_skpd, 'jns_spp' => '1'])->orderBy('no_spp')->orderBy('kd_skpd')->get()
        ];
        return view('penatausahaan.pengeluaran.spp_up.index')->with($data);
    }

    public function create()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'ketentuan' => DB::table('ms_sk_up')->orderBy('keterangan_up')->first(),
            'skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->where(['kd_skpd' => $kd_skpd])->first(),
            'data_spd' => DB::table('trhspd')->select('no_spd', 'tgl_spd')->where(DB::raw("LEFT(kd_skpd,17)"), DB::raw("LEFT('$kd_skpd',17)"))->where(['status' => '1', 'jns_beban' => '5'])->get(),
            'data_bank' => DB::table('ms_bank')->select('kode', 'nama')->orderBy('kode')->get(),
            'data_rek' => DB::table('ms_rekening_bank_online')->select('rekening', 'nm_rekening', 'npwp')->where(['kd_skpd' => $kd_skpd])->orderBy('rekening')->get(),
            'nilai_up' => DB::table('ms_up')->select('nilai_up as nilai')->where(['kd_skpd' => $kd_skpd])->first(),
            'no_up' => DB::table('trhspp')->select(DB::raw("ISNULL(MAX(urut),0)+1 as nilai"))->where(['kd_skpd' => $kd_skpd])->first(),
            'kd_skpd' => $kd_skpd
        ];

        return view('penatausahaan.pengeluaran.spp_up.create')->with($data);
    }
}
