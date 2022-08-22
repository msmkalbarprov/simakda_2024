<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        $data = [
            'daftar_hak_akses' => DB::table('permission')->get()
        ];
        return view('master.hak-akses.index')->with($data);
    }
}
