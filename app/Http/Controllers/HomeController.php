<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    public function index()
    {
        Cookie::queue(Cookie::forget('simakda_2023_session'));
        Cookie::queue(Cookie::forget('laravel_session'));
        Cookie::queue(Cookie::forget('home_base_session'));

        // return Hash::make('kalbarprov');

        $kd_skpd = Auth::user()->kd_skpd;
        // dd(status_anggaran_dashboard());
        // if (status_anggaran_dashboard() == 0) {
        //     $status_anggaran = 'S';
        // } else {
        //     $status_anggaran = status_anggaran_dashboard();
        // }

        $status = status_anggaran_dashboard();

        $status_anggaran = isset($status) ? $status : 'S';
        // dd($status_anggaran);
        if (Auth::user()->is_admin == 1) {
            $data = [

                'data_pendapatan' => DB::table('trdrka')
                    ->select(DB::raw("isnull(sum(nilai),0) as pendapatan"))
                    ->where(['jns_ang' => $status_anggaran])
                    ->where(DB::raw('left(kd_rek6,1)'), 4)
                    ->first(),
                'data_belanja' => DB::table('trdrka')
                    ->select(DB::raw("isnull(sum(nilai),0) as belanja"))
                    ->where(['jns_ang' => $status_anggaran])
                    ->where(DB::raw('left(kd_rek6,1)'), 5)
                    ->first(),
                'data_pem_terima' => DB::table('trdrka')
                    ->select(DB::raw("isnull(sum(nilai),0)as pem_terima"))
                    ->where(['jns_ang' => $status_anggaran])
                    ->where(DB::raw('left(kd_rek6,2)'), 61)
                    ->first(),
                'data_pem_keluar' => DB::table('trdrka')
                    ->select(DB::raw("isnull(sum(nilai),0) as pem_keluar"))
                    ->where(['jns_ang' => $status_anggaran])
                    ->where(DB::raw('left(kd_rek6,2)'), 62)
                    ->first(),
                'data_penagihan' => DB::table('trhtagih')
                    ->select(DB::raw("isnull(sum(total),0) as penagihan,count(*) as totaltagih"))
                    ->first(),
                'data_spp' => DB::table('trhspp')
                    ->select(DB::raw("isnull(sum(nilai),0) as spp,count(*) as total"))
                    ->whereRaw("sp2d_batal is null OR sp2d_batal <> 1")
                    ->first(),
                'data_spm' => DB::table('trhspm as a')
                    ->select(DB::raw("isnull(sum(b.nilai),0) as spm,count(*) as total"))
                    ->join('trhspp as b', function ($join) {
                        $join->on('a.no_spp', '=', 'b.no_spp');
                        $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                    })
                    ->whereRaw("sp2d_batal is null OR sp2d_batal <> 1")
                    ->first(),
                'data_sp2d' => DB::table('trhsp2d')
                    ->select(DB::raw("isnull(sum(nilai),0) as sp2d,count(*) as total"))
                    ->whereRaw("(sp2d_batal is null OR sp2d_batal <> 1)")
                    ->first()
            ];
        } else {
            $data = [
                'data_pendapatan' => DB::table('trdrka')
                    ->select(DB::raw("isnull(sum(nilai),0) as pendapatan"))
                    ->where(['jns_ang' => $status_anggaran])
                    ->where(DB::raw('left(kd_rek6,1)'), 4)
                    ->where('kd_skpd', $kd_skpd)
                    ->first(),
                'data_belanja' => DB::table('trdrka')
                    ->select(DB::raw("isnull(sum(nilai),0) as belanja"))
                    ->where(['jns_ang' => $status_anggaran])
                    ->where(DB::raw('left(kd_rek6,1)'), 5)
                    ->where('kd_skpd', $kd_skpd)
                    ->first(),
                'data_pem_terima' => DB::table('trdrka')
                    ->select(DB::raw("isnull(sum(nilai),0)as pem_terima"))
                    ->where(['jns_ang' => $status_anggaran])
                    ->where(DB::raw('left(kd_rek6,2)'), 61)
                    ->where('kd_skpd', $kd_skpd)
                    ->first(),
                'data_pem_keluar' => DB::table('trdrka')
                    ->select(DB::raw("isnull(sum(nilai),0) as pem_keluar"))
                    ->where(['jns_ang' => $status_anggaran])
                    ->where(DB::raw('left(kd_rek6,2)'), 62)
                    ->where('kd_skpd', $kd_skpd)
                    ->first(),

                'data_penagihan' => DB::table('trhtagih')
                    ->select(DB::raw("isnull(sum(total),0) as penagihan,count(*) as totaltagih"))
                    ->where('kd_skpd', $kd_skpd)
                    ->first(),
                'data_spp' => DB::table('trhspp')
                    ->select(DB::raw("isnull(sum(nilai),0) as spp,count(*) as total"))
                    ->where('kd_skpd', $kd_skpd)
                    ->whereRaw("sp2d_batal is null OR sp2d_batal <> 1")
                    ->first(),
                'data_spm' => DB::table('trhspm as a')
                    ->select(DB::raw("isnull(sum(b.nilai),0) as spm,count(*) as total"))
                    ->join('trhspp as b', function ($join) {
                        $join->on('a.no_spp', '=', 'b.no_spp');
                        $join->on('a.kd_skpd', '=', 'b.kd_skpd');
                    })
                    ->whereRaw("(sp2d_batal is null OR sp2d_batal <> 1)")
                    ->where('a.kd_skpd', $kd_skpd)
                    ->first(),
                'data_sp2d' => DB::table('trhsp2d')
                    ->select(DB::raw("isnull(sum(nilai),0) as sp2d,count(*) as total"))
                    ->whereRaw("(sp2d_batal is null OR sp2d_batal <> 1)")
                    ->where('kd_skpd', $kd_skpd)
                    ->first()
            ];
        }

        // dd($data);
        return view('home')->with($data);;
    }

    public function cekNtpn()
    {
        $skpd = Auth::user()->kd_skpd;
        $kd_skpd = substr($skpd, 0, 17);

        $perusahaan1 = DB::table('ms_perusahaan')->select('nama as nmrekan', 'pimpinan', 'npwp', 'alamat')->whereRaw('LEFT(kd_skpd,17) = ?', [$kd_skpd])->groupBy('nama', 'pimpinan', 'npwp', 'alamat');
        $perusahaan2 = DB::table('trhspp')->select('nmrekan', 'pimpinan', 'npwp', 'alamat')->whereRaw('LEN(nmrekan)>1')->where('kd_skpd', $skpd)->groupBy('nmrekan', 'pimpinan', 'npwp', 'alamat')->unionAll($perusahaan1);
        $perusahaan3 = DB::table('trhtrmpot_cmsbank')->select('nmrekan', 'pimpinan', 'npwp', 'alamat')->whereRaw('LEN(nmrekan)>1')->where('kd_skpd', $skpd)->groupBy('nmrekan', 'pimpinan', 'npwp', 'alamat')->unionAll($perusahaan2);
        $perusahaan4 = DB::table('trhtrmpot')->select('nmrekan', 'pimpinan', 'npwp', 'alamat')->whereRaw('LEN(nmrekan)>1')->where('kd_skpd', $skpd)->groupBy('nmrekan', 'pimpinan', 'npwp', 'alamat')->unionAll($perusahaan3);
        $result = DB::table(DB::raw("({$perusahaan4->toSql()}) AS sub"))
            ->select("nmrekan", "pimpinan", "npwp", "alamat")
            ->mergeBindings($perusahaan4)
            ->groupBy('nmrekan', 'pimpinan', 'npwp', 'alamat')
            ->orderBy('nmrekan', 'ASC')
            ->orderBy('pimpinan', 'ASC')
            ->orderBy('npwp', 'ASC')
            ->orderBy('alamat', 'ASC')
            ->get();

        $data = [
            'daftar_bank' => DB::table('ms_bank_online')->get(),
            'daftar_kode_akun' => DB::table('ms_map_billing')->select('kd_map', 'nm_map')->groupBy('nm_map', 'kd_map')->get(),
            'daftar_rekanan' => $result,
        ];

        return view('master.cek_ntpn.create')->with($data);
    }

    public function pengumuman()
    {
        $data = [
            'data_pengumuman' => DB::table('ms_pengumuman')
                ->where('aktif', 1)
                ->get()
        ];

        return view('pengumuman.pengumuman')->with($data);;
    }

    public function pengumumanid($id)
    {
        $id = Crypt::decryptString($id);
        $data = [
            'pengumuman_by_id' => DB::table('ms_pengumuman')->where('id', $id)->first()
        ];

        // dd($data['pengumuman_by_id']);
        return view('pengumuman.pengumuman_by_id')->with($data);
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

    // Ubah Password
    public function ubahPassword($id)
    {
        $id = Crypt::decryptString($id);
        $data = [
            'user' => DB::table('pengguna')->where(['id' => $id])->first(),
            'kd_skpd' => DB::table('ms_skpd')->orderBy('kd_skpd')->get()
        ];

        return view('fungsi.ubah_password.index')->with($data);
    }

    public function simpanUbahPassword(Request $request)
    {
        $data = $request->data;

        if ($data['id'] != Auth::user()->id) {
            return response()->json([
                'message' => '4'
            ]);
        }

        $password_lama = DB::table('pengguna')
            ->where(['id' => $data['id']])
            ->first()
            ->password;

        if (!Hash::check($data['password_lama'], $password_lama) && $data['password_lama'] != '') {
            return response()->json([
                'message' => '3'
            ]);
        }

        if ($data['password'] != $data['password2']) {
            return response()->json([
                'message' => '2'
            ]);
        }

        DB::beginTransaction();
        try {
            DB::table('pengguna')->where(['id' => $data['id'], 'username' => $data['username']])->update([
                'password' => Hash::make($data['password'])
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


    public function coba1(Request $request)
    {
        $client = DB::table('oauth_clients')
            ->where('personal_access_client', true)
            ->first();

        $response = Http::asForm()->post(config('app.url') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->input('refresh_token'),
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => '*'
        ]);

        $response['log info'] = Log::info("message");
        return $response->json();
    }
}
