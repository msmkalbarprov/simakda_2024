<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $data = [
            'daftar_pengguna' => DB::table('user')->get()
        ];

        return view('master.user.index')->with($data);
    }

    public function create()
    {
        $data = [
            'daftar_role' => DB::table('role')->get(),
            'daftar_skpd' => DB::table('ms_skpd')->get()
        ];

        return view('master.user.create')->with($data);
    }

    public function store(UserRequest $request)
    {
        $input = array_map('htmlentities', $request->validated());
        DB::table('user')->insert([
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
            'nama' => $input['nama'],
            'kd_skpd' => $input['kd_skpd'],
            'is_admin' => $input['tipe'],
            'status' => $input['status'],
            'role' => $input['peran'],
        ]);

        return redirect()->route('user.index');
    }

    public function show($id)
    {
        $user = DB::table('user')->where('id', $id)->first();
        $data = [
            'user' => $user,
            'role' => DB::table('role')->where('id', $user->role)->first()
        ];

        return view('master.user.show')->with($data);
    }

    public function edit($id)
    {
        $user = DB::table('user')->where('id', $id)->first();
        $data = [
            'user' => $user,
            'daftar_role' => DB::table('role')->get(),
            'daftar_skpd' => DB::table('ms_skpd')->get(),
            'role' => DB::table('role')->where('id', $user->role)->first(),
        ];

        return view('master.user.edit')->with($data);
    }

    public function update(UserRequest $request, $id)
    {
        $input = array_map('htmlentities', $request->validated());
        DB::table('user')->where('id', $id)->update([
            'username' => $input['username'],
            'nama' => $input['nama'],
            'kd_skpd' => $input['kd_skpd'],
            'is_admin' => $input['tipe'],
            'status' => $input['status'],
            'role' => $input['peran'],
        ]);

        return redirect()->route('user.index');
    }

    public function destroy($id)
    {
        DB::table('user')->where('id', $id)->delete();
        return redirect()->route('user.index');
    }
}
