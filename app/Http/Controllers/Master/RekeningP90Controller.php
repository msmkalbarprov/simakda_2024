<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;

class RekeningP90Controller extends Controller
{
    // AKUN
    public function indexAkun()
    {
        return view('master.rekening_p90.akun.index');
    }

    public function loadAkun()
    {
        $isAdmin = Auth::user()->is_admin;

        $data = DB::table('ms_rek1')
            ->orderBy('kd_rek1')
            ->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) use ($isAdmin) {
            if ($isAdmin == '1') {
                $btn   = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_rek1 . '\',\'' . $row->nm_rek1 . '\');" class="btn btn-warning btn-sm"><i class="uil-edit"></i></a>';
                $btn   .= '<a href="javascript:void(0);" onclick="deleteData(\'' . $row->kd_rek1 . '\');" class="btn btn-danger btn-sm" style="margin-left:4px"><i class="uil-trash"></i></a>';
            } else {
                $btn = '';
            }

            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpanAkun(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->tipe == 'tambah') {
                $cek = DB::table('ms_rek1')
                    ->where(['kd_rek1' => $request->kode_rekening])
                    ->count();

                if ($cek > 0) {
                    return response()->json([
                        'message' => '2'
                    ]);
                }

                DB::table('ms_rek1')
                    ->insert([
                        'kd_rek1' => $request->kode_rekening,
                        'nm_rek1' => $request->nama_rekening,
                    ]);
            } else {
                DB::table('ms_rek1')
                    ->where(['kd_rek1' => $request->kode_rekening])
                    ->update([
                        'nm_rek1' => $request->nama_rekening,
                    ]);
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

    public function hapusAkun(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('ms_rek1')
                ->where(['kd_rek1' => $request->kode_rekening])
                ->delete();

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

    // KELOMPOK
    public function indexKelompok()
    {
        $data = [
            'daftar_akun' => DB::table('ms_rek1')
                ->orderBy('kd_rek1')
                ->get()
        ];
        return view('master.rekening_p90.kelompok.index')->with($data);
    }

    public function loadKelompok()
    {
        $isAdmin = Auth::user()->is_admin;

        $data = DB::table('ms_rek2')
            ->orderBy('kd_rek2')
            ->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) use ($isAdmin) {
            if ($isAdmin == '1') {
                $btn   = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_rek1 . '\',\'' . $row->kd_rek2 . '\',\'' . $row->nm_rek2 . '\');" class="btn btn-warning btn-sm"><i class="uil-edit"></i></a>';
                $btn   .= '<a href="javascript:void(0);" onclick="deleteData(\'' . $row->kd_rek2 . '\');" class="btn btn-danger btn-sm" style="margin-left:4px"><i class="uil-trash"></i></a>';
            } else {
                $btn = '';
            }

            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpanKelompok(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->tipe == 'tambah') {
                $cek = DB::table('ms_rek2')
                    ->where(['kd_rek2' => $request->kode_rekening])
                    ->count();

                if ($cek > 0) {
                    return response()->json([
                        'message' => '2'
                    ]);
                }

                DB::table('ms_rek2')
                    ->insert([
                        'kd_rek2' => $request->kode_rekening,
                        'nm_rek2' => $request->nama_rekening,
                        'kd_rek1' => $request->kode_akun,
                    ]);
            } else {
                DB::table('ms_rek2')
                    ->where(['kd_rek2' => $request->kode_rekening])
                    ->update([
                        'nm_rek2' => $request->nama_rekening,
                        'kd_rek1' => $request->kode_akun,
                    ]);
            }
            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
            ]);
        }
    }

    public function hapusKelompok(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('ms_rek2')
                ->where(['kd_rek2' => $request->kode_rekening])
                ->delete();

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

    // JENIS
    public function indexJenis()
    {
        $data = [
            'daftar_akun' => DB::table('ms_rek2')
                ->orderBy('kd_rek2')
                ->get()
        ];
        return view('master.rekening_p90.jenis.index')->with($data);
    }

    public function loadJenis()
    {
        $isAdmin = Auth::user()->is_admin;

        $data = DB::table('ms_rek3')
            ->orderBy('kd_rek3')
            ->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) use ($isAdmin) {
            if ($isAdmin == '1') {
                $btn   = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_rek2 . '\',\'' . $row->kd_rek3 . '\',\'' . $row->nm_rek3 . '\');" class="btn btn-warning btn-sm"><i class="uil-edit"></i></a>';
                $btn   .= '<a href="javascript:void(0);" onclick="deleteData(\'' . $row->kd_rek3 . '\');" class="btn btn-danger btn-sm" style="margin-left:4px"><i class="uil-trash"></i></a>';
            } else {
                $btn = '';
            }

            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpanJenis(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->tipe == 'tambah') {
                $cek = DB::table('ms_rek3')
                    ->where(['kd_rek3' => $request->kode_rekening])
                    ->count();

                if ($cek > 0) {
                    return response()->json([
                        'message' => '2'
                    ]);
                }

                DB::table('ms_rek3')
                    ->insert([
                        'kd_rek3' => $request->kode_rekening,
                        'nm_rek3' => $request->nama_rekening,
                        'kd_rek2' => $request->kode_akun,
                    ]);
            } else {
                DB::table('ms_rek3')
                    ->where(['kd_rek3' => $request->kode_rekening])
                    ->update([
                        'nm_rek3' => $request->nama_rekening,
                        'kd_rek2' => $request->kode_akun,
                    ]);
            }
            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
            ]);
        }
    }

    public function hapusJenis(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('ms_rek3')
                ->where(['kd_rek3' => $request->kode_rekening])
                ->delete();

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

    // OBJEK
    public function indexObjek()
    {
        $data = [
            'daftar_akun' => DB::table('ms_rek3')
                ->orderBy('kd_rek3')
                ->get()
        ];
        return view('master.rekening_p90.objek.index')->with($data);
    }

    public function loadObjek()
    {
        $isAdmin = Auth::user()->is_admin;

        $data = DB::table('ms_rek4')
            ->orderBy('kd_rek4')
            ->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) use ($isAdmin) {
            if ($isAdmin == '1') {
                $btn   = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_rek3 . '\',\'' . $row->kd_rek4 . '\',\'' . $row->nm_rek4 . '\');" class="btn btn-warning btn-sm"><i class="uil-edit"></i></a>';
                $btn   .= '<a href="javascript:void(0);" onclick="deleteData(\'' . $row->kd_rek4 . '\');" class="btn btn-danger btn-sm" style="margin-left:4px"><i class="uil-trash"></i></a>';
            } else {
                $btn = '';
            }

            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpanObjek(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->tipe == 'tambah') {
                $cek = DB::table('ms_rek4')
                    ->where(['kd_rek4' => $request->kode_rekening])
                    ->count();

                if ($cek > 0) {
                    return response()->json([
                        'message' => '2'
                    ]);
                }

                DB::table('ms_rek4')
                    ->insert([
                        'kd_rek4' => $request->kode_rekening,
                        'nm_rek4' => $request->nama_rekening,
                        'kd_rek3' => $request->kode_akun,
                    ]);
            } else {
                DB::table('ms_rek4')
                    ->where(['kd_rek4' => $request->kode_rekening])
                    ->update([
                        'nm_rek4' => $request->nama_rekening,
                        'kd_rek3' => $request->kode_akun,
                    ]);
            }
            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
            ]);
        }
    }

    public function hapusObjek(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('ms_rek4')
                ->where(['kd_rek4' => $request->kode_rekening])
                ->delete();

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

    // RINCI OBJEK
    public function indexRinciObjek()
    {
        $data = [
            'daftar_akun' => DB::table('ms_rek4')
                ->orderBy('kd_rek4')
                ->get()
        ];
        return view('master.rekening_p90.rinci_objek.index')->with($data);
    }

    public function loadRinciObjek()
    {
        $isAdmin = Auth::user()->is_admin;

        $data = DB::table('ms_rek5')
            ->orderBy('kd_rek5')
            ->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) use ($isAdmin) {
            if ($isAdmin == '1') {
                $btn   = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_rek4 . '\',\'' . $row->kd_rek5 . '\',\'' . $row->nm_rek5 . '\');" class="btn btn-warning btn-sm"><i class="uil-edit"></i></a>';
                $btn   .= '<a href="javascript:void(0);" onclick="deleteData(\'' . $row->kd_rek5 . '\');" class="btn btn-danger btn-sm" style="margin-left:4px"><i class="uil-trash"></i></a>';
            } else {
                $btn = '';
            }

            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpanRinciObjek(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->tipe == 'tambah') {
                $cek = DB::table('ms_rek5')
                    ->where(['kd_rek5' => $request->kode_rekening])
                    ->count();

                if ($cek > 0) {
                    return response()->json([
                        'message' => '2'
                    ]);
                }

                DB::table('ms_rek5')
                    ->insert([
                        'kd_rek5' => $request->kode_rekening,
                        'nm_rek5' => $request->nama_rekening,
                        'kd_rek4' => $request->kode_akun,
                    ]);
            } else {
                DB::table('ms_rek5')
                    ->where(['kd_rek5' => $request->kode_rekening])
                    ->update([
                        'nm_rek5' => $request->nama_rekening,
                        'kd_rek4' => $request->kode_akun,
                    ]);
            }
            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
            ]);
        }
    }

    public function hapusRinciObjek(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('ms_rek5')
                ->where(['kd_rek5' => $request->kode_rekening])
                ->delete();

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

    // SUB RINCI OBJEK
    public function indexSubRinciObjek()
    {
        $data = [
            'daftar_akun' => DB::table('ms_rek5')
                ->orderBy('kd_rek5')
                ->get()
        ];
        return view('master.rekening_p90.sub_rinci_objek.index')->with($data);
    }

    public function loadSubRinciObjek()
    {
        $isAdmin = Auth::user()->is_admin;

        $data = DB::table('ms_rek6')
            ->orderBy('kd_rek6')
            ->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) use ($isAdmin) {
            if ($isAdmin == '1') {
                $btn   = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_rek5 . '\',\'' . $row->kd_rek6 . '\',\'' . $row->nm_rek6 . '\');" class="btn btn-warning btn-sm"><i class="uil-edit"></i></a>';
                $btn   .= '<a href="javascript:void(0);" onclick="deleteData(\'' . $row->kd_rek6 . '\');" class="btn btn-danger btn-sm" style="margin-left:4px"><i class="uil-trash"></i></a>';
            } else {
                $btn = '';
            }

            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpanSubRinciObjek(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->tipe == 'tambah') {
                $cek = DB::table('ms_rek6')
                    ->where(['kd_rek6' => $request->kode_rekening])
                    ->count();

                if ($cek > 0) {
                    return response()->json([
                        'message' => '2'
                    ]);
                }

                DB::table('ms_rek6')
                    ->insert([
                        'kd_rek6' => $request->kode_rekening,
                        'nm_rek6' => $request->nama_rekening,
                        'kd_rek5' => $request->kode_akun,
                    ]);
            } else {
                DB::table('ms_rek6')
                    ->where(['kd_rek6' => $request->kode_rekening])
                    ->update([
                        'nm_rek6' => $request->nama_rekening,
                        'kd_rek5' => $request->kode_akun,
                    ]);
            }
            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
            ]);
        }
    }

    public function hapusSubRinciObjek(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('ms_rek6')
                ->where(['kd_rek6' => $request->kode_rekening])
                ->delete();

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
