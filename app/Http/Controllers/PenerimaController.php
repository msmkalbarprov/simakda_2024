<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenerimaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PenerimaController extends Controller
{
    public function index()
    {
        $data = [
            'data_penerima' => DB::table('ms_rekening_bank_online')->get()
        ];

        return view('master.penerima.index')->with($data);
    }

    public function create()
    {
        $data = [
            'daftar_bank' => DB::table('ms_bank_online')->get(),
            'daftar_kode_akun' => DB::table('ms_map_billing')->select('kd_map', 'nm_map')->groupBy('nm_map', 'kd_map')->get(),
        ];

        return view('master.penerima.create')->with($data);
    }

    public function store(PenerimaRequest $request)
    {
        $input = array_map('htmlentities', $request->validated());
        DB::table('ms_rekening_bank_online')->insert([
            'kd_bank' => $input['bank'],
            'rekening' => $input['no_rekening_validasi'],
            'nm_rekening' => $input['nm_rekening_validasi'],
            'bank' => $input['cabang'],
            'nm_bank' => $input['nama_cabang'],
            'kd_skpd' => Auth::user()->kd_skpd,
            'jenis' => $input['jenis'],
            'npwp' => $input['npwp_validasi'],
            'nm_wp' => $input['nm_npwp_validasi'],
            'kd_map' => $input['kode_akun'],
            'kd_setor' => $input['kode_setor'],
            'keterangan' => $input['keterangan'],
            'bic' => $input['bic'],
        ]);

        return redirect()->route('penerima.index');
    }

    public function show($id)
    {
        $data_awal = DB::table('ms_rekening_bank_online')->where('id', $id)->first();
        $data = [
            'data_penerima' => DB::table('ms_rekening_bank_online')->where('id', $id)->first(),
            'bank' => DB::table('ms_bank_online')->where('kd_bank', $data_awal->kd_bank)->first(),
            'billing' => DB::table('ms_map_billing')->where('kd_map', $data_awal->kd_map)->where('kd_setor', $data_awal->kd_setor)->first(),
        ];

        return view('master.penerima.show')->with($data);
    }

    public function edit($id)
    {
        $data_awal = DB::table('ms_rekening_bank_online')->where('id', $id)->first();
        $data = [
            'data_penerima' => DB::table('ms_rekening_bank_online')->where('id', $id)->first(),
            'daftar_bank' => DB::table('ms_bank_online')->get(),
            'nama_bank' => DB::table('ms_bank_online')->where('kd_bank', $data_awal->kd_bank)->first(),
            'daftar_kode_akun' => DB::table('ms_map_billing')->select('kd_map', 'nm_map')->groupBy('nm_map', 'kd_map')->get(),
        ];

        return view('master.penerima.edit')->with($data);
    }

    public function update(PenerimaRequest $request, $id)
    {
        DB::table('ms_rekening_bank_online')->where('id', $id)->update([
            'kd_bank' => $request['bank'],
            'rekening' => $request['no_rekening_validasi'],
            'nm_rekening' => $request['nm_rekening_validasi'],
            'bank' => $request['cabang'],
            'nm_bank' => $request['nama_cabang'],
            'kd_skpd' => session()->get('kd_skpd'),
            'jenis' => $request['jenis'],
            'npwp' => $request['npwp_validasi'],
            'nm_wp' => $request['nm_npwp_validasi'],
            'kd_map' => $request['kode_akun'],
            'kd_setor' => $request['kode_setor'],
            'keterangan' => $request['keterangan'],
            'bic' => $request['bic'],
        ]);

        return redirect()->route('penerima.index');
    }

    public function destroy($id)
    {
        $data = DB::table('ms_rekening_bank_online')->where('id', $id)->delete();
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

    public function cekPenerima(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $kd_skpd = Auth::user()->kd_skpd;
            $rekening = DB::table('ms_rekening_bank_online')->select('nm_rekening')->where('id', $id)->first();
            $data = DB::table('ms_kontrak')->where(['nm_rekening' => $rekening->nm_rekening, 'kd_skpd' => $kd_skpd])->count();
            return response()->json($data);
        }
    }

    public function cabang(Request $request)
    {
        if ($request->ajax()) {
            $bic = $request->bic;
            $data = DB::table('ms_bank')->where('bic', $bic)->get();
            return response()->json($data);
        }
    }

    public function kode_setor(Request $request)
    {
        if ($request->ajax()) {
            $kd_map = $request->kd_map;
            $data = DB::table('ms_map_billing')->where('kd_map', $kd_map)->get();
            return response()->json($data);
        }
    }

    public function coba()
    {
        $data = Http::get('https://simakda.kalbarprov.go.id/simakdaservice_2022/index.php/api/skpd/format/json');
        $data1 = json_decode($data, true);
        $result = [];
        foreach ($data1 as $data) {
            $result[] = $data;
        }
        // dd($result);
        $arraycolumn = array_column($result, 'kd_skpd');
        dd($arraycolumn);
        if (array_search('4.01.0.00.0.00.01.0000', array_column($data1, 'kd_skpd')) !== false) {
            echo 'value is in multidim array';
        } else {
            echo 'value is not in multidim array';
        }
        // dd($data5);
    }
}
