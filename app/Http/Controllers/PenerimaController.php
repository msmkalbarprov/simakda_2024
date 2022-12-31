<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenerimaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class PenerimaController extends Controller
{
    public function index()
    {
        return view('master.penerima.index');
    }

    public function loadData()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('ms_rekening_bank_online')->get();
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("penerima.show_penerima", ['rekening' => Crypt::encryptString($row->rekening), 'kd_skpd' => Crypt::encryptString($row->kd_skpd)]) . '" class="btn btn-info btn-sm" style="margin-right:4px"><i class="uil-eye"></i></a>';
            $btn .= '<a href="' . route("penerima.edit_penerima", ['rekening' => Crypt::encryptString($row->rekening), 'kd_skpd' => Crypt::encryptString($row->kd_skpd)]) . '" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="deleteData(\'' . $row->rekening . '\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
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
            'daftar_bank' => DB::table('ms_bank_online')->get(),
            'daftar_kode_akun' => DB::table('ms_map_billing')->select('kd_map', 'nm_map')->groupBy('nm_map', 'kd_map')->get(),
            'daftar_rekanan' => $result,
        ];

        return view('master.penerima.create')->with($data);
    }

    public function store(Request $request)
    {
        // $input = array_map('htmlentities', $request->validated());
        $input = $request->all();

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
            'nmrekan' => $input['rekanan'],
            'pimpinan' => $input['pimpinan'],
            'alamat' => $input['alamat'],
        ]);

        return redirect()->route('penerima.index');
    }

    public function showPenerima($rekening, $kd_skpd)
    {
        $rekening = Crypt::decryptString($rekening);
        $kd_skpd = Crypt::decryptString($kd_skpd);
        $data_awal = DB::table('ms_rekening_bank_online')->where(['rekening' => $rekening, 'kd_skpd' => $kd_skpd])->first();
        $data = [
            'data_penerima' => DB::table('ms_rekening_bank_online')->where(['rekening' => $rekening, 'kd_skpd' => $kd_skpd])->first(),
            'bank' => DB::table('ms_bank_online')->where('kd_bank', $data_awal->kd_bank)->first(),
            'billing' => DB::table('ms_map_billing')->where('kd_map', $data_awal->kd_map)->where('kd_setor', $data_awal->kd_setor)->first(),
        ];

        return view('master.penerima.show')->with($data);
    }

    public function editPenerima($rekening, $kd_skpd)
    {
        $rekening = Crypt::decryptString($rekening);
        $kd_skpd = Crypt::decryptString($kd_skpd);

        $data_awal = DB::table('ms_rekening_bank_online')->where(['rekening' => $rekening, 'kd_skpd' => $kd_skpd])->first();

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
            'data_penerima' => $data_awal,
            'daftar_rekanan' => $result,
            'daftar_bank' => DB::table('ms_bank_online')->get(),
            'nama_bank' => DB::table('ms_bank_online')->where('kd_bank', $data_awal->kd_bank)->first(),
            'daftar_kode_akun' => DB::table('ms_map_billing')->select('kd_map', 'nm_map')->groupBy('nm_map', 'kd_map')->get(),
        ];

        return view('master.penerima.edit')->with($data);
    }

    public function updatePenerima(Request $request, $rekening, $kd_skpd)
    {
        $rekening = Crypt::decryptString($rekening);
        $kd_skpd = Crypt::decryptString($kd_skpd);

        DB::table('ms_rekening_bank_online')
            ->where(['rekening' => $rekening, 'kd_skpd' => $kd_skpd])
            ->update([
                'kd_bank' => $request['bank'],
                'rekening' => $request['no_rekening_validasi'],
                'nm_rekening' => $request['nm_rekening_validasi'],
                'bank' => $request['cabang'],
                'nm_bank' => $request['nama_cabang'],
                'kd_skpd' => $kd_skpd,
                'jenis' => $request['jenis'],
                'npwp' => $request['npwp_validasi'],
                'nm_wp' => $request['nm_npwp_validasi'],
                'kd_map' => $request['kode_akun'],
                'kd_setor' => $request['kode_setor'],
                'keterangan' => $request['keterangan'],
                'bic' => $request['bic'],
                'nmrekan' => $request['rekanan'],
                'pimpinan' => $request['pimpinan'],
                'alamat' => $request['alamat'],
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
