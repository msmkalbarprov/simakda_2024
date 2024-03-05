<?php

namespace App\Http\Controllers\Skpd;
use App\Http\Controllers\Controller;
use App\Http\Requests\KKPDRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;

class KKPDController extends Controller
{
    public function index()
    {
        if (Gate::denies('akses')) {
            abort(401);
        }
        return view('master.kkpd.index');
    }

    public function loadData()
    {
        if(Auth::user()->is_admin==2){
            $kd_skpd = Auth::user()->kd_skpd;
            $data = DB::table('ms_kkpd AS a')
                    ->selectRaw("id,left(no_kkpd,5)+'XXXXXXXXXX'+right(no_kkpd,1)as no_kkpd,nm_kkpd,kd_skpd,jenis")
                    ->where('a.kd_skpd', $kd_skpd)
                    ->get();
        }else{
            $data = DB::table('ms_kkpd AS a')
                    ->selectRaw("id,left(no_kkpd,5)+'XXXXXXXXXX'+right(no_kkpd,1)as no_kkpd,nm_kkpd,kd_skpd,jenis")
                    ->get();
        }
        


        
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn    = '<a href="' . route("kkpd.edit", Crypt::encryptString($row->id)) . '" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn   .= '<a href="javascript:void(0);" onclick="deleteData(\'' . Crypt::encryptString($row->id) . '\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function create()
    {
        if (Gate::denies('akses')) {
            abort(401);
        }

        if(Auth::user()->is_admin==2){
            $kd_skpd = Auth::user()->kd_skpd;
            $data = [
                'skpd' => DB::table('ms_skpd')->select('*')->where('kd_skpd', $kd_skpd)->first()
            ];
        }else{
            $data = [
                'skpd' => DB::table('ms_skpd')->select('*')->get()
            ];
        }
        

        return view('master.kkpd.create')->with($data);
    }

    public function cariSkpd()
    {   
        $type       = Auth::user()->is_admin;
        $kd_skpd    = Auth::user()->kd_skpd;

        if ($type=='1'){
                $data   = DB::table('ms_skpd')
                            ->select('kd_skpd', 'nm_skpd')
                            ->orderBy('kd_skpd')
                            ->get();
        }else{
                $data   = DB::table('ms_skpd')
                            ->where('kd_skpd',$kd_skpd)
                            ->select('kd_skpd', 'nm_skpd')
                            ->get();
        }

        return response()->json($data);
    }

    public function store(KKPDRequest $request)
    {   
        
        $input = array_map('htmlentities', $request->validated());
        DB::table('ms_kkpd')->insert([
            'no_kkpd'       => $input['no_kkpd'],
            'nm_kkpd'       => $input['nm_kkpd'],
            'kd_skpd'       => $input['kd_skpd'],
            'jenis'         => $input['jenis']
        ]);

        return redirect()->route('kkpd.index');
    }


    public function edit($id)
    {   
        if (Gate::denies('akses')) {
            abort(401);
        }

        $id = Crypt::decryptString($id);
        $data_awal = DB::table('ms_kkpd')
            ->where(['id' => $id])
            ->first();

        $data = [
            'data_kkpd' => $data_awal,
            'skpd'      => DB::table('ms_skpd')->select('nm_skpd')->where('kd_skpd', $data_awal->kd_skpd)->first(),
        ];

        return view('master.kkpd.edit')->with($data);
    }

    public function update(KKPDRequest $request, $id)
    {   
        $id = Crypt::decryptString($id);
        $input = array_map('htmlentities', $request->validated());
        DB::table('ms_kkpd')->where('id', $id)->update([
            'no_kkpd'           => $input['no_kkpd'],
            'nm_kkpd'           => $input['nm_kkpd'],
            'kd_skpd'           => $input['kd_skpd'],
            'jenis'             => $input['jenis']
        ]);

        return redirect()->route('kkpd.index');
    }



    public function hapus(Request $request)
    {   
        if (Gate::denies('akses')) {
            abort(401);
        }
        $id = $request->id;
        $id = Crypt::decryptString($id);
        
        $data = DB::table('ms_kkpd')->where('id', $id)->delete();
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
