<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KunciJurnalController extends Controller
{
    public function index()
    {
        return view('akuntansi.kunci_jurnal.index');
    }

    public function load()
    {
        $data = DB::select("SELECT '-' as kd_skpd, 'SEMUA SKPD' as nm_skpd, 0 as kunci_jurnal
        UNION ALL
        select kd_skpd,nm_skpd,kunci_jurnal from ms_skpd order by kd_skpd");

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function kunci(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $kunci = $request->kunci;

        DB::beginTransaction();
        try {
            if ($kd_skpd == '-') {
                if ($kunci == 1) {
                    DB::table('ms_skpd')
                        ->update([
                            'kunci_jurnal' => '0'
                        ]);
                } else {
                    DB::table('ms_skpd')
                        ->update([
                            'kunci_jurnal' => '1'
                        ]);
                }
            } else {
                if ($kunci == 1) {
                    DB::table('ms_skpd')
                        ->where(['kd_skpd' => $kd_skpd])
                        ->update([
                            'kunci_jurnal' => '0'
                        ]);
                } else {
                    DB::table('ms_skpd')
                        ->where(['kd_skpd' => $kd_skpd])
                        ->update([
                            'kunci_jurnal' => '1'
                        ]);
                }
            }

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
