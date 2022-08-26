<?php

namespace App\Http\Controllers;

use App\Http\Requests\PeranRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $data = [
            'daftar_peran' => DB::table('role')->get()
        ];

        return view('master.peran.index')->with($data);
    }

    public function create()
    {
        $data = [
            'daftar_hak_akses' => DB::table('permission')->get()
        ];

        return view('master.peran.create')->with($data);
    }

    public function store(PeranRequest $request)
    {
        $input = $request->validated();
        $data['role'] = $input['role'];
        $data['nama_role'] = $input['nama_role'];
        $hak_akses = $input['hak_akses'];
        $input = array_map('htmlentities', $data);
        $input_akses = array_map('htmlentities', $hak_akses);

        $id = DB::table('role')->insertGetId([
            'role' => $input['role'],
            'nama_role' => $input['nama_role'],
        ]);
        if (isset($input_akses)) {
            DB::table('permission_role')->insert(array_map(function ($value) use ($id) {
                return ['id_permission' => $value, 'id_role' => $id, "username_created" => Auth::user()->nama, "created_at" => date('Y-m-d H:i:s')];
            }, $input_akses));
        }
        return redirect()->route('peran.index');
    }

    public function show($id)
    {
        $peran = DB::table('role')->where('id', $id)->first();
        $data = [
            'daftar_hak_akses' => DB::table('permission_role')
                ->join('permission', 'permission_role.id_permission', '=', 'permission.id')
                ->where('permission_role.id_role', $peran->id)
                ->get()
        ];

        return view('master.peran.show')->with($data);
    }

    public function edit($id)
    {
        $peran = DB::table('role')->where('id', $id)->first();
        $daftar_hak_akses = DB::table('permission_role')
            ->select('permission_role.id_permission')
            ->join('permission', 'permission_role.id_permission', '=', 'permission.id')
            ->where('id_role', $peran->id)
            ->get();
        $array = json_decode(json_encode($daftar_hak_akses), true);
        $array = array_column($array, "id_permission");
        $data = [
            'available_daftar_hak_akses' => json_decode(json_encode(DB::table('permission')->get()), true),
            'peran' => DB::table('role')->where('id', $id)->first(),
            'daftar_hak_akses' => $array,
        ];
        return view('master.peran.edit')->with($data);
    }

    public function update(PeranRequest $request, $id)
    {
        $input = $request->validated();
        $data['role'] = $input['role'];
        $data['nama_role'] = $input['nama_role'];
        $hak_akses = $input['hak_akses'];
        $input = array_map('htmlentities', $data);
        $input_akses = array_map('htmlentities', $hak_akses);
        DB::table('role')->where('id', $id)->update([
            'role' => $input['role'],
            'nama_role' => $input['nama_role'],
        ]);

        $delete = DB::table('permission_role')->where('id_role', $id)->delete();
        if ($delete) {
            if (isset($input_akses)) {
                DB::table('permission_role')->insert(array_map(function ($value) use ($id) {
                    return [
                        'id_permission' => $value,
                        'id_role' => $id,
                        "username_created" => Auth::user()->nama,
                        "created_at" => date('Y-m-d H:i:s')
                    ];
                }, $input_akses));
            }
        }
        return redirect()->route('peran.index');
    }

    public function destroy($id)
    {
        $data['role'] = DB::table('role')->where('id', $id)->delete();
        $data['hak_akses'] = DB::table('permission_role')->where('id_role', $id)->delete();
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
