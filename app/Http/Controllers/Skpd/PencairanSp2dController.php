<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PencairanSp2dController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'cair_sp2d' => DB::table('trhsp2d')->where(['status_terima' => '1', 'kd_skpd' => $kd_skpd])->orderBy('no_sp2d')->orderBy('kd_skpd')->get()
        ];
        return view('skpd.pencairan_sp2d.index')->with($data);
    }
}
