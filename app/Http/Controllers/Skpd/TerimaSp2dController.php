<?php

namespace App\Http\Controllers\Skpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TerimaSp2dController extends Controller
{
    public function index()
    {
        return view('skpd.terima_sp2d.index');
    }

    public function loadData()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = DB::table('trhsp2d')->where(['kd_skpd' => $kd_skpd, 'status_bud' => '1'])->orderBy('no_sp2d')->orderBy('kd_skpd')->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("terima_sp2d.tampil_sp2d", Crypt::encryptString($row->no_sp2d)) . '" class="btn btn-info btn-sm" style="margin-right:4px"><i class="uil-eye"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function tampilSp2d($no_sp2d)
    {
        $no_sp2d = Crypt::decryptString($no_sp2d);
        $data_sp2d = DB::table('trhsp2d as a')->where(['a.no_sp2d' => $no_sp2d])->select('a.*', DB::raw("(SELECT nmrekan FROM trhspp WHERE no_spp=a.no_spp AND kd_skpd=a.kd_skpd) as nmrekan"), DB::raw("(SELECT pimpinan FROM trhspp WHERE no_spp=a.no_spp AND kd_skpd=a.kd_skpd) as pimpinan"), DB::raw("(SELECT alamat FROM trhspp WHERE no_spp=a.no_spp AND kd_skpd=a.kd_skpd) as alamat"))->first();
        $nilai = DB::table('trdspp')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $data_sp2d->no_spp])->first();
        if (in_array($data_sp2d->jns_spp, ['1', '2', '4', '5'])) {
            $kd_kegi = '';
            $nm_kegi = '';
            $kd_prog = '';
            $nm_prog = '';
        } else {
            $data_kegiatan = DB::table('trdspp as a')->join('trhsp2d as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->where(['b.kd_skpd' => $data_sp2d->kd_skpd, 'no_sp2d' => $no_sp2d])->select('kd_sub_kegiatan')->groupBy('kd_sub_kegiatan')->first();
            $nama_kegiatan = DB::table('trskpd')->select('nm_sub_kegiatan')->where(['kd_sub_kegiatan' => $data_kegiatan->kd_sub_kegiatan])->first();
            $kd_kegi = $data_kegiatan->kd_sub_kegiatan;
            $nm_kegi = $nama_kegiatan->nm_sub_kegiatan;
            $kd_prog = left($kd_kegi, 7);
            $nama_program = DB::table('trskpd')->select('nm_program')->where(['kd_program' => $kd_prog])->first();
            $nm_prog = $nama_program->nm_program;
        }
        $kd_skpd = Auth::user()->kd_skpd;

        $status_anggaran = DB::table('trhrka')->select('jns_ang')->where(['kd_skpd' => $kd_skpd, 'status' => '1'])->orderByDesc('tgl_dpa')->first();
        if ($data_sp2d->jns_spp == '2') {
            $sub_kegiatan = DB::table('trdspp as a')->join('trhspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->where(['a.no_spp' => $data_sp2d->no_spp])->where(DB::raw("LEFT(a.kd_skpd,17)"), DB::raw("LEFT('$data_sp2d->kd_skpd',17)"))->whereNotIn('b.jns_spp', ['1', '2'])->where(DB::raw("LEFT(b.kd_skpd,17)"), DB::raw("LEFT('$data_sp2d->kd_skpd',17)"))->select('a.kd_sub_kegiatan')->get();
            $sub = json_decode(json_encode($sub_kegiatan), true);
            $pagu = DB::table('trdrka')->select(DB::raw("SUM(nilai) as nilai"))->where(['jns_ang' => $status_anggaran->jns_ang])->whereIn('kd_sub_kegiatan', $sub)->first();
        } else {
            $sub_kegiatan = DB::table('trdspp as a')->join('trhspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })->where(['a.no_spp' => $data_sp2d->no_spp, 'a.kd_skpd' => $data_sp2d->kd_skpd, 'b.kd_skpd' => $data_sp2d->kd_skpd])->whereNotIn('b.jns_spp', ['1', '2'])->select('a.kd_sub_kegiatan')->get();
            $sub = json_decode(json_encode($sub_kegiatan), true);
            $pagu = DB::table('trdrka')->select(DB::raw("SUM(nilai) as nilai"))->where(['jns_ang' => $status_anggaran->jns_ang])->whereIn('kd_sub_kegiatan', $sub)->first();
        }
        $sp2d = cair_sp2d($data_sp2d);
        $data = [
            'sp2d' => DB::table('trhsp2d')->where(['no_sp2d' => $no_sp2d])->first(),
            'daerah' => DB::table('sclient')->select('provinsi')->where(['kd_skpd' => $data_sp2d->kd_skpd])->first(),
            'no_sp2d' => $no_sp2d,
            'data_sp2d' => $data_sp2d,
            'nilai' => $nilai->nilai,
            'bank' => DB::table('ms_skpd')->where(['kd_skpd' => $data_sp2d->kd_skpd])->select('bank', 'rekening', 'npwp')->first(),
            'kd_kegi' => $kd_kegi,
            'nm_kegi' => $nm_kegi,
            'kd_prog' => $kd_prog,
            'nm_prog' => $nm_prog,
            'pagu' => $pagu->nilai,
            'bud' => DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['kode' => 'BUD'])->first(),
            'bk' => DB::table('ms_ttd')->select('nama', 'nip', 'jabatan', 'pangkat')->where(['kd_skpd' => $data_sp2d->kd_skpd, 'kode' => 'BK'])->first(),
            'total_kegiatan' => DB::table('trdspp')->select(DB::raw("SUM(nilai) as nilai"))->where(['no_spp' => $data_sp2d->no_spp, 'kd_skpd' => $data_sp2d->kd_skpd])->first(),
            'sub_kegiatan' => $sp2d,
            'potongan1' => DB::table('trspmpot as a')->join('ms_pot as b', 'a.map_pot', '=', 'b.map_pot')->where(['a.no_spm' => $data_sp2d->no_spm, 'kelompok' => '1', 'kd_skpd' => $data_sp2d->kd_skpd])->get(),
            'potongan2' => DB::table('trspmpot as a')->join('ms_pot as b', 'a.map_pot', '=', 'b.map_pot')->where(['a.no_spm' => $data_sp2d->no_spm, 'kelompok' => '2', 'kd_skpd' => $data_sp2d->kd_skpd])->get(),
        ];
        return view('skpd.terima_sp2d.show')->with($data);
    }
}
