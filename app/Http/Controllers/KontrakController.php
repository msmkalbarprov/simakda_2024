<?php

namespace App\Http\Controllers;

use App\Http\Requests\KontrakRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KontrakController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'data_kontrak' => DB::table('ms_kontrak AS a')->select('a.*')->leftJoin('ms_rekening_bank_online AS b', function ($join) {
                $join->on('a.nm_rekening', '=', 'b.nm_rekening');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->where('a.kd_skpd', $kd_skpd)->get()
        ];
        return view('master.kontrak.index')->with($data);
    }

    public function create()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'daftar_bank' => DB::table('ms_bank_online')->get(),
            'daftar_kode_akun' => DB::table('ms_map_billing')->select('kd_map', 'nm_map')->groupBy('nm_map', 'kd_map')->get(),
            'kd_skpd' => $kd_skpd,
            'skpd' => DB::table('ms_skpd')->select('nm_skpd')->where('kd_skpd', $kd_skpd)->first(),
            'daftar_rekening' => DB::table('ms_rekening_bank_online')->where('kd_skpd', $kd_skpd)->get(),
        ];

        return view('master.kontrak.create')->with($data);
    }

    public function store(KontrakRequest $request)
    {
        $input = array_map('htmlentities', $request->validated());
        DB::table('ms_kontrak')->insert([
            'no_kontrak' => str_replace(' ', '', trim($input['no_kontrak'])),
            'nilai' => $input['nilai'],
            'kd_skpd' => $input['kd_skpd'],
            'nm_kerja' => $input['nm_kerja'],
            'tgl_kerja' => $input['tgl_kerja'],
            'nmpel' => $input['nmpel'],
            'nm_rekening' => $input['nm_rekening'],
            'pimpinan' => $input['pimpinan'],
        ]);

        return redirect()->route('kontrak.index');
    }

    public function show($id)
    {
        $data_awal = DB::table('ms_kontrak')->where('id', $id)->first();
        $data = [
            'data' => $data_awal,
            'skpd' => DB::table('ms_skpd')->where('kd_skpd', $data_awal->kd_skpd)->first(),
            'data_rekening' => DB::table('ms_rekening_bank_online')->where('nm_rekening', $data_awal->nm_rekening)->first(),
            'tgl_kerja' => date('d M Y', strtotime($data_awal->tgl_kerja))
        ];
        return view('master.kontrak.show')->with($data);
    }

    public function edit($id)
    {
        $data_awal = DB::table('ms_kontrak')->where('id', $id)->first();
        $data = [
            'data_kontrak' => $data_awal,
            'daftar_rekening' => DB::table('ms_rekening_bank_online')->where('kd_skpd', $data_awal->kd_skpd)->get(),
            'skpd' => DB::table('ms_skpd')->select('nm_skpd')->where('kd_skpd', $data_awal->kd_skpd)->first(),
            'data_rekening' => DB::table('ms_rekening_bank_online')->where('nm_rekening', $data_awal->nm_rekening)->first(),
        ];

        return view('master.kontrak.edit')->with($data);
    }

    public function update(KontrakRequest $request, $id)
    {
        $input = array_map('htmlentities', $request->validated());
        DB::table('ms_kontrak')->where('id', $id)->update([
            'no_kontrak' => str_replace(' ', '', trim($input['no_kontrak'])),
            'nilai' => $input['nilai'],
            'kd_skpd' => $input['kd_skpd'],
            'nm_kerja' => $input['nm_kerja'],
            'tgl_kerja' => $input['tgl_kerja'],
            'nmpel' => $input['nmpel'],
            'nm_rekening' => $input['nm_rekening'],
            'pimpinan' => $input['pimpinan'],
        ]);

        return redirect()->route('kontrak.index');
    }

    public function destroy($id)
    {
        $data = DB::table('ms_kontrak')->where('id', $id)->delete();
        if ($data) {
            return response()->json([
                'message' => '1'
            ]);
        } else {
            return response()->json([
                'message' => '0'
            ]);
        }
    }
}
