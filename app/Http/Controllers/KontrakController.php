<?php

namespace App\Http\Controllers;

use App\Http\Requests\KontrakRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KontrakController extends Controller
{
    public function index()
    {
        return view('master.kontrak.index');
    }

    public function loadData()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('ms_kontrak AS a')->select('a.*')->leftJoin('ms_rekening_bank_online AS b', function ($join) {
            $join->on('a.nm_rekening', '=', 'b.nm_rekening');
            $join->on('a.kd_skpd', '=', 'b.kd_skpd');
        })->where('a.kd_skpd', $kd_skpd)->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("kontrak.show", Crypt::encryptString($row->no_kontrak)) . '" class="btn btn-info btn-sm" style="margin-right:4px"><i class="uil-eye"></i></a>';
            $btn .= '<a href="' . route("kontrak.edit", Crypt::encryptString($row->no_kontrak)) . '" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="deleteData(\'' . $row->no_kontrak . '\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
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
        $id = Crypt::decryptString($id);
        $kd_skpd = Auth::user()->kd_skpd;
        $data_awal = DB::table('ms_kontrak')->where(['no_kontrak' => $id, 'kd_skpd' => $kd_skpd])->first();

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
        $id = Crypt::decryptString($id);
        $kd_skpd = Auth::user()->kd_skpd;
        $data_awal = DB::table('ms_kontrak as a')
            ->join('ms_rekening_bank_online as b', function ($join) {
                $join->on('a.nm_rekening', '=', 'b.nm_rekening');
            })
            ->select('a.*', 'b.rekening')
            ->where(['a.no_kontrak' => $id, 'a.kd_skpd' => $kd_skpd])
            ->first();

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
        $id = Crypt::decryptString($id);
        $input = array_map('htmlentities', $request->validated());
        DB::table('ms_kontrak')->where('no_kontrak', $id)->update([
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
        $kd_skpd = Auth::user()->kd_skpd;
        return $id;
        $data = DB::table('ms_kontrak')->where(['no_kontrak' => $id, 'kd_skpd' => $kd_skpd])->delete();
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

    public function hapus(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $no_kontrak = $request->no_kontrak;
        $data = DB::table('ms_kontrak')->where(['no_kontrak' => $no_kontrak, 'kd_skpd' => $kd_skpd])->delete();
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
