<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
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
            ->where(DB::raw('left(kd_rek6,2)'),62)
            ->first()
        ];
        // dd($data);
        return view('home')->with($data);;
    }
    public function coba()
    {
        return view('coba');
    }
}
