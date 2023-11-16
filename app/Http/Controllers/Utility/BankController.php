<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BankController extends Controller
{
    public function index()
    {
        $data = [
            'daftar_bic' => DB::table('ms_bank_online')
                ->select('bic', 'nama_bank')
                ->get()
        ];

        return view('fungsi.bank.index')->with($data);
    }

    public function load()
    {
        $data = DB::table('ms_bank')
            ->orderByRaw("CAST(kode as int) asc")
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                // $btn = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kode . '\');" class="btn btn-warning btn-sm"><i class="uil-edit"></i></a>';
                // return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function nomor()
    {
        $data = DB::table('ms_bank')
            ->selectRaw("ISNULL(MAX(CAST(kode as int)),0)+1 as nomor")
            ->first()
            ->nomor;

        return response()->json($data);
    }

    public function simpan(Request $request)
    {
        DB::beginTransaction();
        try {
            $cek = DB::table('ms_bank')
                ->where(['kode' => $request->kode])
                ->count();

            if ($cek > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            $cek_nama = DB::table('ms_bank')
                ->where(['nama' => $request->nama])
                ->count();

            if ($cek_nama > 0) {
                return response()->json([
                    'message' => '3'
                ]);
            }

            DB::table('ms_bank')
                ->insert([
                    'kode' => $request->kode,
                    'nama' => $request->nama,
                    'bic' => $request->bic,
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
}
