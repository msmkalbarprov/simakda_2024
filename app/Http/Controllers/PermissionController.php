<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index()
    {
        return view('master.hak-akses.index');
    }

    public function loadData()
    {
        $data = DB::table('akses')->get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }
}
