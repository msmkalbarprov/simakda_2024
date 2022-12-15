<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{

    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        if(Auth::user()->is_admin==1){
            $data = [
                'data_pendapatan' => DB::table('trdrka')
                    ->select(DB::raw("sum(nilai) as pendapatan"))
                    ->where(['jns_ang' => 'M'])
                    ->where(DB::raw('left(kd_rek6,1)'), 4)
                    ->first(),
                'data_belanja' => DB::table('trdrka')
                    ->select(DB::raw("sum(nilai) as belanja"))
                    ->where(['jns_ang' => 'M'])
                    ->where(DB::raw('left(kd_rek6,1)'), 5)
                    ->first(),
                'data_pem_terima' => DB::table('trdrka')
                    ->select(DB::raw("sum(nilai)as pem_terima"))
                    ->where(['jns_ang' => 'M'])
                    ->where(DB::raw('left(kd_rek6,2)'), 61)
                    ->first(),
                'data_pem_keluar' => DB::table('trdrka')
                    ->select(DB::raw("sum(nilai) as pem_keluar"))
                    ->where(['jns_ang' => 'M'])
                    ->where(DB::raw('left(kd_rek6,2)'), 62)
                    ->first()
            ];
        }else{
            $data = [
                'data_pendapatan' => DB::table('trdrka')
                    ->select(DB::raw("sum(nilai) as pendapatan"))
                    ->where(['jns_ang' => 'M'])
                    ->where(DB::raw('left(kd_rek6,1)'), 4)
                    ->where('kd_skpd',$kd_skpd)
                    ->first(),
                'data_belanja' => DB::table('trdrka')
                    ->select(DB::raw("sum(nilai) as belanja"))
                    ->where(['jns_ang' => 'M'])
                    ->where(DB::raw('left(kd_rek6,1)'), 5)
                    ->where('kd_skpd',$kd_skpd)
                    ->first(),
                'data_pem_terima' => DB::table('trdrka')
                    ->select(DB::raw("sum(nilai)as pem_terima"))
                    ->where(['jns_ang' => 'M'])
                    ->where(DB::raw('left(kd_rek6,2)'), 61)
                    ->where('kd_skpd',$kd_skpd)
                    ->first(),
                'data_pem_keluar' => DB::table('trdrka')
                    ->select(DB::raw("sum(nilai) as pem_keluar"))
                    ->where(['jns_ang' => 'M'])
                    ->where(DB::raw('left(kd_rek6,2)'), 62)
                    ->where('kd_skpd',$kd_skpd)
                    ->first()
            ];
        }
        
        // dd($data);
        return view('home')->with($data);;
    }
    public function coba()
    {
        return view('coba');
    }

    // Ubah SKPD
    public function ubahSkpd($id)
    {
        $id = Crypt::decryptString($id);
        $data = [
            'user' => DB::table('pengguna')->where(['id' => $id])->first(),
            'kd_skpd' => DB::table('ms_skpd')->orderBy('kd_skpd')->get()
        ];

        return view('fungsi.ubah_skpd.index')->with($data);
    }

    public function simpanUbahSkpd(Request $request)
    {
        $data = $request->data;

        DB::beginTransaction();
        try {
            DB::table('pengguna')->where(['id' => $data['id'], 'username' => $data['username']])->update([
                'kd_skpd' => $data['kd_skpd']
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
