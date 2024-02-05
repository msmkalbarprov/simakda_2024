<?php

namespace App\Http\Controllers\BUD;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CekSpmController extends Controller
{
    public function index()
    {
        $data = [
            'skpd' => DB::table('ms_skpd')
                ->select('kd_skpd', 'nm_skpd')
                ->orderBy('kd_skpd')
                ->get()
        ];
        return view('bud.cek_spm.index')->with($data);
    }

    public function loadData(Request $request)
    {
        $data = DB::table('trhspm as a')
            ->join('trhspp as b', function ($join) {
                $join->on('a.no_spp', '=', 'b.no_spp');
                $join->on('a.kd_skpd', '=', 'b.kd_skpd');
            })
            ->select('a.kd_skpd', 'a.nm_skpd', 'a.no_spm', 'a.no_spp', 'a.status', 'a.jns_spp', 'a.jenis_beban', 'a.jenis_kelengkapan')
            ->selectRaw("(SELECT nm_skpd from ms_skpd where a.kd_skpd=kd_skpd) as nm_skpd")
            ->where(['a.status' => '0', 'a.kd_skpd' => $request->kd_skpd, 'a.jns_spp' => $request->jenis])
            ->whereRaw("a.no_spm NOT IN (select no_spm from trhsp2d where a.kd_skpd=kd_skpd)")
            ->where(function ($query) {
                $query->where('b.sp2d_batal', '!=', '1')->orWhereNull('b.sp2d_batal');
            })
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<a href="javascript:void(0);" style="margin-right:4px" onclick="detail(\'' . $row->no_spm . '\',\'' . $row->no_spp . '\', \'' . $row->jns_spp . '\', \'' . $row->kd_skpd . '\', \'' . $row->nm_skpd . '\', \'' . $row->jenis_beban . '\', \'' . $row->jenis_kelengkapan . '\', \'' . $row->jenis_kelengkapan . '\');" class="btn btn-success btn-sm"><i class="uil-info-circle"></i></a>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function cekData($data)
    {
        return $data == 'false' ? 0 : 1;
    }

    public function cariData(Request $request)
    {
        $data = DB::table('validasi_spm')
            ->where(['no_spm' => $request->no_spm, 'kd_skpd' => $request->kd_skpd])
            ->first();

        return response()->json($data);
    }

    // CLEAR
    public function simpanUp(Request $request)
    {
        $no_spm = $request->no_spm;
        $kd_skpd = $request->kd_skpd;
        $tgl_verifikasi = $request->tgl_verifikasi;
        $keterangan_verifikasi = $request->keterangan_verifikasi;
        $status_verifikasi = $request->status_verifikasi;

        $pengantar_spp_up = $this->cekData($request->pengantar_spp_up);
        $spp_up = $this->cekData($request->spp_up);
        $ringkasan_spp_up = $this->cekData($request->ringkasan_spp_up);
        $rincian_spp_up = $this->cekData($request->rincian_spp_up);
        $pernyataan_pengajuan_up = $this->cekData($request->pernyataan_pengajuan_up);
        $lampiran_spp_up = $this->cekData($request->lampiran_spp_up);
        $salinan_spd_up = $this->cekData($request->salinan_spd_up);
        $rekening_koran_up = $this->cekData($request->rekening_koran_up);
        $keputusan_gubernur_up = $this->cekData($request->keputusan_gubernur_up);

        DB::beginTransaction();

        try {
            $cek = DB::table('validasi_spm')
                ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                ->count();

            if ($cek > 0) {
                DB::table('validasi_spm')
                    ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                    ->update([
                        'tgl_verifikasi' => $tgl_verifikasi,
                        'keterangan_verifikasi' => $keterangan_verifikasi,
                        'user_verifikasi' => Auth::user()->nama,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'status' => $status_verifikasi,
                        'pengantar' => $pengantar_spp_up,
                        'spp' => $spp_up,
                        'ringkasan' => $ringkasan_spp_up,
                        'rincian' => $rincian_spp_up,
                        'pernyataan' => $pernyataan_pengajuan_up,
                        'lampiran' => $lampiran_spp_up,
                        'salinan_spd' => $salinan_spd_up,
                        'rekening_koran_up' => $rekening_koran_up,
                        'kepgub_up' => $keputusan_gubernur_up,
                        'status' => '0'
                    ]);
            } else {
                DB::table('validasi_spm')
                    ->insert([
                        'no_spm' => $no_spm,
                        'kd_skpd' => $kd_skpd,
                        'tgl_verifikasi' => $tgl_verifikasi,
                        'keterangan_verifikasi' => $keterangan_verifikasi,
                        'user_verifikasi' => Auth::user()->nama,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'status' => $status_verifikasi,
                        'pengantar' => $pengantar_spp_up,
                        'spp' => $spp_up,
                        'ringkasan' => $ringkasan_spp_up,
                        'rincian' => $rincian_spp_up,
                        'pernyataan' => $pernyataan_pengajuan_up,
                        'lampiran' => $lampiran_spp_up,
                        'salinan_spd' => $salinan_spd_up,
                        'rekening_koran_up' => $rekening_koran_up,
                        'kepgub_up' => $keputusan_gubernur_up,
                        'status' => '0'
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
                'error' => $e->getMessage()
            ]);
        }
    }

    // CLEAR
    public function simpanGu(Request $request)
    {
        $no_spm = $request->no_spm;
        $kd_skpd = $request->kd_skpd;
        $tgl_verifikasi = $request->tgl_verifikasi;
        $keterangan_verifikasi = $request->keterangan_verifikasi;
        $status_verifikasi = $request->status_verifikasi;

        $pengantar_spp_gu = $this->cekData($request->pengantar_spp_gu);
        $spp_gu = $this->cekData($request->spp_gu);
        $ringkasan_spp_gu = $this->cekData($request->ringkasan_spp_gu);
        $rincian_spp_gu = $this->cekData($request->rincian_spp_gu);
        $pernyataan_pengajuan_gu = $this->cekData($request->pernyataan_pengajuan_gu);
        $lampiran_spp_gu = $this->cekData($request->lampiran_spp_gu);
        $salinan_spd_gu = $this->cekData($request->salinan_spd_gu);
        $lpj_gu = $this->cekData($request->lpj_gu);
        $sptb_gu = $this->cekData($request->sptb_gu);
        $sse_gu = $this->cekData($request->sse_gu);

        DB::beginTransaction();

        try {
            $cek = DB::table('validasi_spm')
                ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                ->count();

            if ($cek > 0) {
                DB::table('validasi_spm')
                    ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                    ->update([
                        'tgl_verifikasi' => $tgl_verifikasi,
                        'keterangan_verifikasi' => $keterangan_verifikasi,
                        'user_verifikasi' => Auth::user()->nama,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'status' => $status_verifikasi,
                        'pengantar' => $pengantar_spp_gu,
                        'spp' => $spp_gu,
                        'ringkasan' => $ringkasan_spp_gu,
                        'rincian' => $rincian_spp_gu,
                        'pernyataan' => $pernyataan_pengajuan_gu,
                        'lampiran' => $lampiran_spp_gu,
                        'salinan_spd' => $salinan_spd_gu,
                        'lpj_gu' => $lpj_gu,
                        'sptb_gu' => $sptb_gu,
                        'sse_gu' => $sse_gu,
                        'status' => '0'
                    ]);
            } else {
                DB::table('validasi_spm')
                    ->insert([
                        'no_spm' => $no_spm,
                        'kd_skpd' => $kd_skpd,
                        'tgl_verifikasi' => $tgl_verifikasi,
                        'keterangan_verifikasi' => $keterangan_verifikasi,
                        'user_verifikasi' => Auth::user()->nama,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'status' => $status_verifikasi,
                        'pengantar' => $pengantar_spp_gu,
                        'spp' => $spp_gu,
                        'ringkasan' => $ringkasan_spp_gu,
                        'rincian' => $rincian_spp_gu,
                        'pernyataan' => $pernyataan_pengajuan_gu,
                        'lampiran' => $lampiran_spp_gu,
                        'salinan_spd' => $salinan_spd_gu,
                        'lpj_gu' => $lpj_gu,
                        'sptb_gu' => $sptb_gu,
                        'sse_gu' => $sse_gu,
                        'status' => '0'
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
                'error' => $e->getMessage()
            ]);
        }
    }

    // CLEAR
    public function simpanTu(Request $request)
    {
        $no_spm = $request->no_spm;
        $kd_skpd = $request->kd_skpd;
        $tgl_verifikasi = $request->tgl_verifikasi;
        $keterangan_verifikasi = $request->keterangan_verifikasi;
        $status_verifikasi = $request->status_verifikasi;

        $pengantar_spp_tu = $this->cekData($request->pengantar_spp_tu);
        $spp_tu = $this->cekData($request->spp_tu);
        $ringkasan_spp_tu = $this->cekData($request->ringkasan_spp_tu);
        $rencana_penggunaan_tu = $this->cekData($request->rencana_penggunaan_tu);
        $pernyataan_pengajuan_tu = $this->cekData($request->pernyataan_pengajuan_tu);
        $lampiran_spp_tu = $this->cekData($request->lampiran_spp_tu);
        $salinan_spd_tu = $this->cekData($request->salinan_spd_tu);
        $jadwal_pelaksanaan_kegiatan_tu = $this->cekData($request->jadwal_pelaksanaan_kegiatan_tu);
        $rekening_koran_tu = $this->cekData($request->rekening_koran_tu);
        $lpj_untuk_tu = $this->cekData($request->lpj_untuk_tu);
        $sptb_tu = $this->cekData($request->sptb_tu);
        $sse_tu = $this->cekData($request->sse_tu);
        $bukti_setor_tu = $this->cekData($request->bukti_setor_tu);
        $dokumen_lain_tu = $this->cekData($request->dokumen_lain_tu);

        DB::beginTransaction();

        try {
            $cek = DB::table('validasi_spm')
                ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                ->count();

            if ($cek > 0) {
                DB::table('validasi_spm')
                    ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                    ->update([
                        'tgl_verifikasi' => $tgl_verifikasi,
                        'keterangan_verifikasi' => $keterangan_verifikasi,
                        'user_verifikasi' => Auth::user()->nama,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'status' => $status_verifikasi,
                        'pengantar' => $pengantar_spp_tu,
                        'spp' => $spp_tu,
                        'ringkasan' => $ringkasan_spp_tu,
                        'rincian' => $rencana_penggunaan_tu,
                        'pernyataan' => $pernyataan_pengajuan_tu,
                        'lampiran' => $lampiran_spp_tu,
                        'salinan_spd' => $salinan_spd_tu,
                        'jadwal_pelaksanaan_tu' => $jadwal_pelaksanaan_kegiatan_tu,
                        'rekening_koran_tu' => $rekening_koran_tu,
                        'lpj_untuk_tu' => $lpj_untuk_tu,
                        'sptb_tu' => $sptb_tu,
                        'sse_tu' => $sse_tu,
                        'bukti_setor_tu' => $bukti_setor_tu,
                        'dokumen_lain_tu' => $dokumen_lain_tu,
                    ]);
            } else {
                DB::table('validasi_spm')
                    ->insert([
                        'no_spm' => $no_spm,
                        'kd_skpd' => $kd_skpd,
                        'tgl_verifikasi' => $tgl_verifikasi,
                        'keterangan_verifikasi' => $keterangan_verifikasi,
                        'user_verifikasi' => Auth::user()->nama,
                        'status' => $status_verifikasi,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'pengantar' => $pengantar_spp_tu,
                        'spp' => $spp_tu,
                        'ringkasan' => $ringkasan_spp_tu,
                        'rincian' => $rencana_penggunaan_tu,
                        'pernyataan' => $pernyataan_pengajuan_tu,
                        'lampiran' => $lampiran_spp_tu,
                        'salinan_spd' => $salinan_spd_tu,
                        'jadwal_pelaksanaan_tu' => $jadwal_pelaksanaan_kegiatan_tu,
                        'rekening_koran_tu' => $rekening_koran_tu,
                        'lpj_untuk_tu' => $lpj_untuk_tu,
                        'sptb_tu' => $sptb_tu,
                        'sse_tu' => $sse_tu,
                        'bukti_setor_tu' => $bukti_setor_tu,
                        'dokumen_lain_tu' => $dokumen_lain_tu,
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
                'error' => $e->getMessage()
            ]);
        }
    }

    // CLEAR
    public function simpanGaji(Request $request)
    {
        $jenis_beban = $request->jenis_beban;
        $jenis_kelengkapan = $request->jenis_kelengkapan;
        $no_spm = $request->no_spm;
        $kd_skpd = $request->kd_skpd;
        $tgl_verifikasi = $request->tgl_verifikasi;
        $keterangan_verifikasi = $request->keterangan_verifikasi;
        $status_verifikasi = $request->status_verifikasi;

        $pengantar_spp_gaji = $this->cekData($request->pengantar_spp_gaji);
        $spp_gaji = $this->cekData($request->spp_gaji);
        $ringkasan_spp_gaji = $this->cekData($request->ringkasan_spp_gaji);
        $rincian_spp_gaji = $this->cekData($request->rincian_spp_gaji);
        $pernyataan_pengajuan_gaji = $this->cekData($request->pernyataan_pengajuan_gaji);
        $lampiran_spp_gaji = $this->cekData($request->lampiran_spp_gaji);
        $salinan_spd_gaji = $this->cekData($request->salinan_spd_gaji);
        $daftar_gaji = $this->cekData($request->daftar_gaji);
        $rekap_gaji_induk = $this->cekData($request->rekap_gaji_induk);
        $rekap_gaji_golongan = $this->cekData($request->rekap_gaji_golongan);
        $sse_gaji = $this->cekData($request->sse_gaji);
        $sk_perubahan_gaji = $this->cekData($request->sk_perubahan_gaji);
        $sk_kenaikan_gaji = $this->cekData($request->sk_kenaikan_gaji);
        $sk_struktural_gaji = $this->cekData($request->sk_struktural_gaji);
        $keputusan_kenaikan_gaji = $this->cekData($request->keputusan_kenaikan_gaji);
        $keputusan_pindah_gaji = $this->cekData($request->keputusan_pindah_gaji);
        $daftar_keluarga_gaji = $this->cekData($request->daftar_keluarga_gaji);
        $pernyataan_tugas_gaji = $this->cekData($request->pernyataan_tugas_gaji);
        $cerai_gaji = $this->cekData($request->cerai_gaji);
        $sk_pengangkatan_gaji = $this->cekData($request->sk_pengangkatan_gaji);
        $sptjm_gaji = $this->cekData($request->sptjm_gaji);
        $sk_mutasi_gaji = $this->cekData($request->sk_mutasi_gaji);
        $skpp_gaji = $this->cekData($request->skpp_gaji);

        DB::beginTransaction();

        try {
            $cek = DB::table('validasi_spm')
                ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                ->count();

            if ($cek > 0) {
                if ($jenis_beban == '1' || ($jenis_beban == '7' && $jenis_kelengkapan == '1')) {
                    DB::table('validasi_spm')
                        ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                        ->update([
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => $status_verifikasi,
                            'pengantar' => $pengantar_spp_gaji,
                            'spp' => $spp_gaji,
                            'ringkasan' => $ringkasan_spp_gaji,
                            'rincian' => $rincian_spp_gaji,
                            'pernyataan' => $pernyataan_pengajuan_gaji,
                            'lampiran' => $lampiran_spp_gaji,
                            'salinan_spd' => $salinan_spd_gaji,
                            'daftar_gaji' => $daftar_gaji,
                            'rekap_gaji_induk' => $rekap_gaji_induk,
                            'rekap_gaji_golongan' => $rekap_gaji_golongan,
                            'sse_gaji' => $sse_gaji,
                            'sk_perubahan_gaji' => $sk_perubahan_gaji,
                            'sk_kenaikan_gaji' => $sk_kenaikan_gaji,
                            'sk_struktural_gaji' => $sk_struktural_gaji,
                            'keputusan_kenaikan_gaji' => $keputusan_kenaikan_gaji,
                            'keputusan_pindah_gaji' => $keputusan_pindah_gaji,
                            'daftar_keluarga_gaji' => $daftar_keluarga_gaji,
                            'pernyataan_tugas_gaji' => $pernyataan_tugas_gaji,
                            'cerai_gaji' => $cerai_gaji,
                            'sk_pengangkatan_gaji' => $sk_pengangkatan_gaji,
                            'sptjm_gaji' => $sptjm_gaji,
                        ]);
                } elseif ($jenis_beban == '7' && $jenis_kelengkapan == '2') {
                    DB::table('validasi_spm')
                        ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                        ->update([
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => $status_verifikasi,
                            'pengantar' => $pengantar_spp_gaji,
                            'spp' => $spp_gaji,
                            'ringkasan' => $ringkasan_spp_gaji,
                            'rincian' => $rincian_spp_gaji,
                            'pernyataan' => $pernyataan_pengajuan_gaji,
                            'lampiran' => $lampiran_spp_gaji,
                            'salinan_spd' => $salinan_spd_gaji,
                            'daftar_gaji' => $daftar_gaji,
                            'rekap_gaji_induk' => $rekap_gaji_induk,
                            'rekap_gaji_golongan' => $rekap_gaji_golongan,
                            'sse_gaji' => $sse_gaji,
                            'daftar_keluarga_gaji' => $daftar_keluarga_gaji,
                            'pernyataan_tugas_gaji' => $pernyataan_tugas_gaji,
                            'sk_pengangkatan_gaji' => $sk_pengangkatan_gaji,
                            'sptjm_gaji' => $sptjm_gaji,
                            'sk_mutasi_gaji' => $sk_mutasi_gaji,
                            'skpp_gaji' => $skpp_gaji,
                        ]);
                }
            } else {
                if ($jenis_beban == '1' || ($jenis_beban == '7' && $jenis_kelengkapan == '1')) {
                    DB::table('validasi_spm')
                        ->insert([
                            'no_spm' => $no_spm,
                            'kd_skpd' => $kd_skpd,
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'status' => $status_verifikasi,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_gaji,
                            'spp' => $spp_gaji,
                            'ringkasan' => $ringkasan_spp_gaji,
                            'rincian' => $rincian_spp_gaji,
                            'pernyataan' => $pernyataan_pengajuan_gaji,
                            'lampiran' => $lampiran_spp_gaji,
                            'salinan_spd' => $salinan_spd_gaji,
                            'daftar_gaji' => $daftar_gaji,
                            'rekap_gaji_induk' => $rekap_gaji_induk,
                            'rekap_gaji_golongan' => $rekap_gaji_golongan,
                            'sse_gaji' => $sse_gaji,
                            'sk_perubahan_gaji' => $sk_perubahan_gaji,
                            'sk_kenaikan_gaji' => $sk_kenaikan_gaji,
                            'sk_struktural_gaji' => $sk_struktural_gaji,
                            'keputusan_kenaikan_gaji' => $keputusan_kenaikan_gaji,
                            'keputusan_pindah_gaji' => $keputusan_pindah_gaji,
                            'daftar_keluarga_gaji' => $daftar_keluarga_gaji,
                            'pernyataan_tugas_gaji' => $pernyataan_tugas_gaji,
                            'cerai_gaji' => $cerai_gaji,
                            'sk_pengangkatan_gaji' => $sk_pengangkatan_gaji,
                            'sptjm_gaji' => $sptjm_gaji,
                        ]);
                } elseif ($jenis_beban == '7' && $jenis_kelengkapan == '2') {
                    DB::table('validasi_spm')
                        ->insert([
                            'no_spm' => $no_spm,
                            'kd_skpd' => $kd_skpd,
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => $status_verifikasi,
                            'pengantar' => $pengantar_spp_gaji,
                            'spp' => $spp_gaji,
                            'ringkasan' => $ringkasan_spp_gaji,
                            'rincian' => $rincian_spp_gaji,
                            'pernyataan' => $pernyataan_pengajuan_gaji,
                            'lampiran' => $lampiran_spp_gaji,
                            'salinan_spd' => $salinan_spd_gaji,
                            'daftar_gaji' => $daftar_gaji,
                            'rekap_gaji_induk' => $rekap_gaji_induk,
                            'rekap_gaji_golongan' => $rekap_gaji_golongan,
                            'sse_gaji' => $sse_gaji,
                            'daftar_keluarga_gaji' => $daftar_keluarga_gaji,
                            'pernyataan_tugas_gaji' => $pernyataan_tugas_gaji,
                            'sk_pengangkatan_gaji' => $sk_pengangkatan_gaji,
                            'sptjm_gaji' => $sptjm_gaji,
                            'sk_mutasi_gaji' => $sk_mutasi_gaji,
                            'skpp_gaji' => $skpp_gaji,
                        ]);
                }
            }

            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
                'error' => $e->getMessage()
            ]);
        }
    }

    // CLEAR
    public function simpanKetiga(Request $request)
    {
        $jenis_beban = $request->jenis_beban;
        $no_spm = $request->no_spm;
        $kd_skpd = $request->kd_skpd;
        $tgl_verifikasi = $request->tgl_verifikasi;
        $keterangan_verifikasi = $request->keterangan_verifikasi;
        $status_verifikasi = $request->status_verifikasi;

        $pengantar_spp_ketiga = $this->cekData($request->pengantar_spp_ketiga);
        $spp_ketiga = $this->cekData($request->spp_ketiga);
        $ringkasan_spp_ketiga = $this->cekData($request->ringkasan_spp_ketiga);
        $rincian_spp_ketiga = $this->cekData($request->rincian_spp_ketiga);
        $pernyataan_ketiga = $this->cekData($request->pernyataan_ketiga);
        $lampiran_spp_ketiga = $this->cekData($request->lampiran_spp_ketiga);
        $proposal_bansos_ketiga = $this->cekData($request->proposal_bansos_ketiga);
        $kepgub_bansos_ketiga = $this->cekData($request->kepgub_bansos_ketiga);
        $nphd_ketiga = $this->cekData($request->nphd_ketiga);
        $kab_ketiga = $this->cekData($request->kab_ketiga);
        $penerima_bansos_ketiga = $this->cekData($request->penerima_bansos_ketiga);
        $penerima_hibah_ketiga = $this->cekData($request->penerima_hibah_ketiga);
        $sptjm_hibah_ketiga = $this->cekData($request->sptjm_hibah_ketiga);
        $sptjm_bansos_ketiga = $this->cekData($request->sptjm_bansos_ketiga);
        $kepgub_bankeu_ketiga = $this->cekData($request->kepgub_bankeu_ketiga);
        $sk_kud_ketiga = $this->cekData($request->sk_kud_ketiga);
        $kepgub_bagihasil_ketiga = $this->cekData($request->kepgub_bagihasil_ketiga);
        $fc_bagihasil_ketiga = $this->cekData($request->fc_bagihasil_ketiga);
        $sptjm_pembiayaan_ketiga = $this->cekData($request->sptjm_pembiayaan_ketiga);
        $syarat_lain_ketiga = $this->cekData($request->syarat_lain_ketiga);

        DB::beginTransaction();

        try {
            $cek = DB::table('validasi_spm')
                ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                ->count();

            if ($cek > 0) {
                if ($jenis_beban == '1') {
                    DB::table('validasi_spm')
                        ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                        ->update([
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'status' => $status_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_ketiga,
                            'spp' => $spp_ketiga,
                            'ringkasan' => $ringkasan_spp_ketiga,
                            'rincian' => $rincian_spp_ketiga,
                            'pernyataan' => $pernyataan_ketiga,
                            'lampiran' => $lampiran_spp_ketiga,
                            'proposal_bansos_ketiga' => $proposal_bansos_ketiga,
                            'nphd_ketiga' => $nphd_ketiga,
                            'kab_ketiga' => $kab_ketiga,
                            'penerima_hibah_ketiga' => $penerima_hibah_ketiga,
                            'sptjm_hibah_ketiga' => $sptjm_hibah_ketiga,
                            'syarat_lain_ketiga' => $syarat_lain_ketiga,
                        ]);
                } elseif ($jenis_beban == '2') {
                    DB::table('validasi_spm')
                        ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                        ->update([
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'status' => $status_verifikasi,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_ketiga,
                            'spp' => $spp_ketiga,
                            'ringkasan' => $ringkasan_spp_ketiga,
                            'rincian' => $rincian_spp_ketiga,
                            'pernyataan' => $pernyataan_ketiga,
                            'lampiran' => $lampiran_spp_ketiga,
                            'proposal_bansos_ketiga' => $proposal_bansos_ketiga,
                            'kepgub_bansos_ketiga' => $kepgub_bansos_ketiga,
                            'kab_ketiga' => $kab_ketiga,
                            'penerima_bansos_ketiga' => $penerima_bansos_ketiga,
                            'sptjm_bansos_ketiga' => $sptjm_bansos_ketiga,
                            'syarat_lain_ketiga' => $syarat_lain_ketiga,
                        ]);
                } elseif ($jenis_beban == '3') {
                    DB::table('validasi_spm')
                        ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                        ->update([
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'status' => $status_verifikasi,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_ketiga,
                            'spp' => $spp_ketiga,
                            'ringkasan' => $ringkasan_spp_ketiga,
                            'rincian' => $rincian_spp_ketiga,
                            'pernyataan' => $pernyataan_ketiga,
                            'lampiran' => $lampiran_spp_ketiga,
                            'kepgub_bankeu_ketiga' => $kepgub_bankeu_ketiga,
                            'sk_kud_ketiga' => $sk_kud_ketiga,
                            'syarat_lain_ketiga' => $syarat_lain_ketiga,
                        ]);
                } elseif ($jenis_beban == '5') {
                    DB::table('validasi_spm')
                        ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                        ->update([
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'status' => $status_verifikasi,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_ketiga,
                            'spp' => $spp_ketiga,
                            'ringkasan' => $ringkasan_spp_ketiga,
                            'rincian' => $rincian_spp_ketiga,
                            'pernyataan' => $pernyataan_ketiga,
                            'lampiran' => $lampiran_spp_ketiga,
                            'kepgub_bagihasil_ketiga' => $kepgub_bagihasil_ketiga,
                            'fc_bagihasil_ketiga' => $fc_bagihasil_ketiga,
                            'syarat_lain_ketiga' => $syarat_lain_ketiga,
                        ]);
                } elseif ($jenis_beban == '8') {
                    DB::table('validasi_spm')
                        ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                        ->update([
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'status' => $status_verifikasi,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_ketiga,
                            'spp' => $spp_ketiga,
                            'ringkasan' => $ringkasan_spp_ketiga,
                            'rincian' => $rincian_spp_ketiga,
                            'pernyataan' => $pernyataan_ketiga,
                            'lampiran' => $lampiran_spp_ketiga,
                            'kab_ketiga' => $kab_ketiga,
                            'sptjm_pembiayaan_ketiga' => $sptjm_pembiayaan_ketiga,
                            'syarat_lain_ketiga' => $syarat_lain_ketiga,
                        ]);
                }
            } else {
                if ($jenis_beban == '1') {
                    DB::table('validasi_spm')
                        ->insert([
                            'no_spm' => $no_spm,
                            'kd_skpd' => $kd_skpd,
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'status' => $status_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_ketiga,
                            'spp' => $spp_ketiga,
                            'ringkasan' => $ringkasan_spp_ketiga,
                            'rincian' => $rincian_spp_ketiga,
                            'pernyataan' => $pernyataan_ketiga,
                            'lampiran' => $lampiran_spp_ketiga,
                            'proposal_bansos_ketiga' => $proposal_bansos_ketiga,
                            'nphd_ketiga' => $nphd_ketiga,
                            'kab_ketiga' => $kab_ketiga,
                            'penerima_hibah_ketiga' => $penerima_hibah_ketiga,
                            'sptjm_hibah_ketiga' => $sptjm_hibah_ketiga,
                            'syarat_lain_ketiga' => $syarat_lain_ketiga,
                        ]);
                } elseif ($jenis_beban == '2') {
                    DB::table('validasi_spm')
                        ->insert([
                            'no_spm' => $no_spm,
                            'kd_skpd' => $kd_skpd,
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'status' => $status_verifikasi,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_ketiga,
                            'spp' => $spp_ketiga,
                            'ringkasan' => $ringkasan_spp_ketiga,
                            'rincian' => $rincian_spp_ketiga,
                            'pernyataan' => $pernyataan_ketiga,
                            'lampiran' => $lampiran_spp_ketiga,
                            'proposal_bansos_ketiga' => $proposal_bansos_ketiga,
                            'kepgub_bansos_ketiga' => $kepgub_bansos_ketiga,
                            'kab_ketiga' => $kab_ketiga,
                            'penerima_bansos_ketiga' => $penerima_bansos_ketiga,
                            'sptjm_bansos_ketiga' => $sptjm_bansos_ketiga,
                            'syarat_lain_ketiga' => $syarat_lain_ketiga,
                        ]);
                } elseif ($jenis_beban == '3') {
                    DB::table('validasi_spm')
                        ->insert([
                            'no_spm' => $no_spm,
                            'kd_skpd' => $kd_skpd,
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'status' => $status_verifikasi,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_ketiga,
                            'spp' => $spp_ketiga,
                            'ringkasan' => $ringkasan_spp_ketiga,
                            'rincian' => $rincian_spp_ketiga,
                            'pernyataan' => $pernyataan_ketiga,
                            'lampiran' => $lampiran_spp_ketiga,
                            'kepgub_bankeu_ketiga' => $kepgub_bankeu_ketiga,
                            'sk_kud_ketiga' => $sk_kud_ketiga,
                            'syarat_lain_ketiga' => $syarat_lain_ketiga,
                        ]);
                } elseif ($jenis_beban == '5') {
                    DB::table('validasi_spm')
                        ->insert([
                            'no_spm' => $no_spm,
                            'kd_skpd' => $kd_skpd,
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'status' => $status_verifikasi,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_ketiga,
                            'spp' => $spp_ketiga,
                            'ringkasan' => $ringkasan_spp_ketiga,
                            'rincian' => $rincian_spp_ketiga,
                            'pernyataan' => $pernyataan_ketiga,
                            'lampiran' => $lampiran_spp_ketiga,
                            'kepgub_bagihasil_ketiga' => $kepgub_bagihasil_ketiga,
                            'fc_bagihasil_ketiga' => $fc_bagihasil_ketiga,
                            'syarat_lain_ketiga' => $syarat_lain_ketiga,
                        ]);
                } elseif ($jenis_beban == '8') {
                    DB::table('validasi_spm')
                        ->insert([
                            'no_spm' => $no_spm,
                            'kd_skpd' => $kd_skpd,
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'status' => $status_verifikasi,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_ketiga,
                            'spp' => $spp_ketiga,
                            'ringkasan' => $ringkasan_spp_ketiga,
                            'rincian' => $rincian_spp_ketiga,
                            'pernyataan' => $pernyataan_ketiga,
                            'lampiran' => $lampiran_spp_ketiga,
                            'kab_ketiga' => $kab_ketiga,
                            'sptjm_pembiayaan_ketiga' => $sptjm_pembiayaan_ketiga,
                            'syarat_lain_ketiga' => $syarat_lain_ketiga,
                        ]);
                }
            }

            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
                'error' => $e->getMessage()
            ]);
        }
    }

    // CLEAR
    public function simpanBarjas(Request $request)
    {
        $jenis_beban = $request->jenis_beban;
        $no_spm = $request->no_spm;
        $kd_skpd = $request->kd_skpd;
        $tgl_verifikasi = $request->tgl_verifikasi;
        $keterangan_verifikasi = $request->keterangan_verifikasi;
        $status_verifikasi = $request->status_verifikasi;

        $pengantar_spp_barjas = $this->cekData($request->pengantar_spp_barjas);
        $spp_barjas = $this->cekData($request->spp_barjas);
        $ringkasan_spp_barjas = $this->cekData($request->ringkasan_spp_barjas);
        $rincian_spp_barjas = $this->cekData($request->rincian_spp_barjas);
        $pernyataan_barjas = $this->cekData($request->pernyataan_barjas);
        $lampiran_spp_barjas = $this->cekData($request->lampiran_spp_barjas);

        $salinan_barjas1 = $this->cekData($request->salinan_barjas1);
        $penerima_barjas1 = $this->cekData($request->penerima_barjas1);
        $absensi_barjas1 = $this->cekData($request->absensi_barjas1);
        $rekap_absensi_barjas1 = $this->cekData($request->rekap_absensi_barjas1);
        $ka_barjas1 = $this->cekData($request->ka_barjas1);
        $sse_barjas1 = $this->cekData($request->sse_barjas1);
        $sts_barjas1 = $this->cekData($request->sts_barjas1);

        $salinan_barjas2 = $this->cekData($request->salinan_barjas2);
        $sk_barjas2 = $this->cekData($request->sk_barjas2);
        $terima_barjas2 = $this->cekData($request->terima_barjas2);
        $ka_barjas2 = $this->cekData($request->ka_barjas2);
        $sse_barjas2 = $this->cekData($request->sse_barjas2);

        $salinan_barjas3 = $this->cekData($request->salinan_barjas3);
        $sk_barjas3 = $this->cekData($request->sk_barjas3);
        $spk_barjas3 = $this->cekData($request->spk_barjas3);
        $terima_barjas3 = $this->cekData($request->terima_barjas3);
        $ka_barjas3 = $this->cekData($request->ka_barjas3);
        $sse_barjas3 = $this->cekData($request->sse_barjas3);
        $sse_pnbp_barjas3 = $this->cekData($request->sse_pnbp_barjas3);

        $salinan_barjas4 = $this->cekData($request->salinan_barjas4);
        $nota_barjas4 = $this->cekData($request->nota_barjas4);
        $kontrak_barjas4 = $this->cekData($request->kontrak_barjas4);
        $kwintansi_barjas4 = $this->cekData($request->kwintansi_barjas4);
        $referensi_barjas4 = $this->cekData($request->referensi_barjas4);
        $npwp_barjas4 = $this->cekData($request->npwp_barjas4);
        $jum_barjas4 = $this->cekData($request->jum_barjas4);
        $jp_barjas4 = $this->cekData($request->jp_barjas4);
        $ringkasan_barjas4 = $this->cekData($request->ringkasan_barjas4);
        $lkp_barjas4 = $this->cekData($request->lkp_barjas4);
        $bap1_barjas4 = $this->cekData($request->bap1_barjas4);
        $bap2_barjas4 = $this->cekData($request->bap2_barjas4);
        $bas_barjas4 = $this->cekData($request->bas_barjas4);
        $bap3_barjas4 = $this->cekData($request->bap3_barjas4);
        $jppa_barjas4 = $this->cekData($request->jppa_barjas4);
        $ffp_barjas4 = $this->cekData($request->ffp_barjas4);
        $sse_barjas4 = $this->cekData($request->sse_barjas4);
        $dokumen_barjas4 = $this->cekData($request->dokumen_barjas4);

        $salinan_barjas5 = $this->cekData($request->salinan_barjas5);
        $ka_barjas5 = $this->cekData($request->ka_barjas5);
        $penerima_barjas5 = $this->cekData($request->penerima_barjas5);
        $fakta_barjas5 = $this->cekData($request->fakta_barjas5);
        $syarat_barjas5 = $this->cekData($request->syarat_barjas5);

        DB::beginTransaction();

        try {
            $cek = DB::table('validasi_spm')
                ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                ->count();

            if ($cek > 0) {
                if ($jenis_beban == '1') {
                    DB::table('validasi_spm')
                        ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                        ->update([
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'status' => $status_verifikasi,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_barjas,
                            'spp' => $spp_barjas,
                            'ringkasan' => $ringkasan_spp_barjas,
                            'rincian' => $rincian_spp_barjas,
                            'pernyataan' => $pernyataan_barjas,
                            'lampiran' => $lampiran_spp_barjas,
                            'salinan_spd' => $salinan_barjas1,
                            'penerima_tpp_barjas' => $penerima_barjas1,
                            'absensi_tpp_barjas' => $absensi_barjas1,
                            'rekap_absensi_tpp_barjas' => $rekap_absensi_barjas1,
                            'ka_tpp_barjas' => $ka_barjas1,
                            'sse_tpp_barjas' => $sse_barjas1,
                            'sts_tpp_barjas' => $sts_barjas1,
                        ]);
                } elseif ($jenis_beban == '2' || $jenis_beban == '3') {
                    DB::table('validasi_spm')
                        ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                        ->update([
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => $status_verifikasi,
                            'pengantar' => $pengantar_spp_barjas,
                            'spp' => $spp_barjas,
                            'ringkasan' => $ringkasan_spp_barjas,
                            'rincian' => $rincian_spp_barjas,
                            'pernyataan' => $pernyataan_barjas,
                            'lampiran' => $lampiran_spp_barjas,
                            'salinan_spd' => $salinan_barjas5,
                            'ka_kdh_barjas' => $ka_barjas5,
                            'penerima_kdh_barjas' => $penerima_barjas5,
                            'fakta_kdh_barjas' => $fakta_barjas5,
                            'syarat_barjas' => $syarat_barjas5,
                        ]);
                } elseif ($jenis_beban == '4') {
                    DB::table('validasi_spm')
                        ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                        ->update([
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => $status_verifikasi,
                            'pengantar' => $pengantar_spp_barjas,
                            'spp' => $spp_barjas,
                            'ringkasan' => $ringkasan_spp_barjas,
                            'rincian' => $rincian_spp_barjas,
                            'pernyataan' => $pernyataan_barjas,
                            'lampiran' => $lampiran_spp_barjas,
                            'salinan_spd' => $salinan_barjas3,
                            'sk_kontrak_barjas' => $sk_barjas3,
                            'spk_kontrak_barjas' => $spk_barjas3,
                            'terima_kontrak_barjas' => $terima_barjas3,
                            'ka_kontrak_barjas' => $ka_barjas3,
                            'sse_kontrak_barjas' => $sse_barjas3,
                            'ssepnbp_kontrak_barjas' => $sse_pnbp_barjas3,
                        ]);
                } elseif ($jenis_beban == '5' || $jenis_beban == '6') {
                    DB::table('validasi_spm')
                        ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                        ->update([
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => $status_verifikasi,
                            'pengantar' => $pengantar_spp_barjas,
                            'spp' => $spp_barjas,
                            'ringkasan' => $ringkasan_spp_barjas,
                            'rincian' => $rincian_spp_barjas,
                            'pernyataan' => $pernyataan_barjas,
                            'lampiran' => $lampiran_spp_barjas,
                            'salinan_spd' => $salinan_barjas4,
                            'nota_jasa_barjas' => $nota_barjas4,
                            'kontrak_jasa_barjas' => $kontrak_barjas4,
                            'kwintansi_jasa_barjas' => $kwintansi_barjas4,
                            'referensi_jasa_barjas' => $referensi_barjas4,
                            'npwp_jasa_barjas' => $npwp_barjas4,
                            'jum_jasa_barjas' => $jum_barjas4,
                            'jp_jasa_barjas' => $jp_barjas4,
                            'ringkasan_jasa_barjas' => $ringkasan_barjas4,
                            'lkp_jasa_barjas' => $lkp_barjas4,
                            'bap1_jasa_barjas' => $bap1_barjas4,
                            'bap2_jasa_barjas' => $bap2_barjas4,
                            'bas_jasa_barjas' => $bas_barjas4,
                            'bap3_jasa_barjas' => $bap3_barjas4,
                            'jppa_jasa_barjas' => $jppa_barjas4,
                            'ffp_jasa_barjas' => $ffp_barjas4,
                            'sse_jasa_barjas' => $sse_barjas4,
                            'dokumen_jasa_barjas' => $dokumen_barjas4,
                        ]);
                } elseif ($jenis_beban == '7') {
                    DB::table('validasi_spm')
                        ->where(['no_spm' => $no_spm, 'kd_skpd' => $kd_skpd])
                        ->update([
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => $status_verifikasi,
                            'pengantar' => $pengantar_spp_barjas,
                            'spp' => $spp_barjas,
                            'ringkasan' => $ringkasan_spp_barjas,
                            'rincian' => $rincian_spp_barjas,
                            'pernyataan' => $pernyataan_barjas,
                            'lampiran' => $lampiran_spp_barjas,
                            'salinan_spd' => $salinan_barjas2,
                            'sk_pns_barjas' => $sk_barjas2,
                            'terima_pns_barjas' => $terima_barjas2,
                            'ka_pns_barjas' => $ka_barjas2,
                            'sse_pns_barjas' => $sse_barjas2,
                        ]);
                }
            } else {
                if ($jenis_beban == '1') {
                    DB::table('validasi_spm')
                        ->insert([
                            'no_spm' => $no_spm,
                            'kd_skpd' => $kd_skpd,
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'status' => $status_verifikasi,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'pengantar' => $pengantar_spp_barjas,
                            'spp' => $spp_barjas,
                            'ringkasan' => $ringkasan_spp_barjas,
                            'rincian' => $rincian_spp_barjas,
                            'pernyataan' => $pernyataan_barjas,
                            'lampiran' => $lampiran_spp_barjas,
                            'salinan_spd' => $salinan_barjas1,
                            'penerima_tpp_barjas' => $penerima_barjas1,
                            'absensi_tpp_barjas' => $absensi_barjas1,
                            'rekap_absensi_tpp_barjas' => $rekap_absensi_barjas1,
                            'ka_tpp_barjas' => $ka_barjas1,
                            'sse_tpp_barjas' => $sse_barjas1,
                            'sts_tpp_barjas' => $sts_barjas1,
                        ]);
                } elseif ($jenis_beban == '2' || $jenis_beban == '3') {
                    DB::table('validasi_spm')
                        ->insert([
                            'no_spm' => $no_spm,
                            'kd_skpd' => $kd_skpd,
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => $status_verifikasi,
                            'pengantar' => $pengantar_spp_barjas,
                            'spp' => $spp_barjas,
                            'ringkasan' => $ringkasan_spp_barjas,
                            'rincian' => $rincian_spp_barjas,
                            'pernyataan' => $pernyataan_barjas,
                            'lampiran' => $lampiran_spp_barjas,
                            'salinan_spd' => $salinan_barjas5,
                            'ka_kdh_barjas' => $ka_barjas5,
                            'penerima_kdh_barjas' => $penerima_barjas5,
                            'fakta_kdh_barjas' => $fakta_barjas5,
                            'syarat_barjas' => $syarat_barjas5,
                        ]);
                } elseif ($jenis_beban == '4') {
                    DB::table('validasi_spm')
                        ->insert([
                            'no_spm' => $no_spm,
                            'kd_skpd' => $kd_skpd,
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => $status_verifikasi,
                            'pengantar' => $pengantar_spp_barjas,
                            'spp' => $spp_barjas,
                            'ringkasan' => $ringkasan_spp_barjas,
                            'rincian' => $rincian_spp_barjas,
                            'pernyataan' => $pernyataan_barjas,
                            'lampiran' => $lampiran_spp_barjas,
                            'salinan_spd' => $salinan_barjas3,
                            'sk_kontrak_barjas' => $sk_barjas3,
                            'spk_kontrak_barjas' => $spk_barjas3,
                            'terima_kontrak_barjas' => $terima_barjas3,
                            'ka_kontrak_barjas' => $ka_barjas3,
                            'sse_kontrak_barjas' => $sse_barjas3,
                            'ssepnbp_kontrak_barjas' => $sse_pnbp_barjas3,
                        ]);
                } elseif ($jenis_beban == '5' || $jenis_beban == '6') {
                    DB::table('validasi_spm')
                        ->insert([
                            'no_spm' => $no_spm,
                            'kd_skpd' => $kd_skpd,
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => $status_verifikasi,
                            'pengantar' => $pengantar_spp_barjas,
                            'spp' => $spp_barjas,
                            'ringkasan' => $ringkasan_spp_barjas,
                            'rincian' => $rincian_spp_barjas,
                            'pernyataan' => $pernyataan_barjas,
                            'lampiran' => $lampiran_spp_barjas,
                            'salinan_spd' => $salinan_barjas4,
                            'nota_jasa_barjas' => $nota_barjas4,
                            'kontrak_jasa_barjas' => $kontrak_barjas4,
                            'kwintansi_jasa_barjas' => $kwintansi_barjas4,
                            'referensi_jasa_barjas' => $referensi_barjas4,
                            'npwp_jasa_barjas' => $npwp_barjas4,
                            'jum_jasa_barjas' => $jum_barjas4,
                            'jp_jasa_barjas' => $jp_barjas4,
                            'ringkasan_jasa_barjas' => $ringkasan_barjas4,
                            'lkp_jasa_barjas' => $lkp_barjas4,
                            'bap1_jasa_barjas' => $bap1_barjas4,
                            'bap2_jasa_barjas' => $bap2_barjas4,
                            'bas_jasa_barjas' => $bas_barjas4,
                            'bap3_jasa_barjas' => $bap3_barjas4,
                            'jppa_jasa_barjas' => $jppa_barjas4,
                            'ffp_jasa_barjas' => $ffp_barjas4,
                            'sse_jasa_barjas' => $sse_barjas4,
                            'dokumen_jasa_barjas' => $dokumen_barjas4,
                        ]);
                } elseif ($jenis_beban == '7') {
                    DB::table('validasi_spm')
                        ->insert([
                            'no_spm' => $no_spm,
                            'kd_skpd' => $kd_skpd,
                            'tgl_verifikasi' => $tgl_verifikasi,
                            'keterangan_verifikasi' => $keterangan_verifikasi,
                            'user_verifikasi' => Auth::user()->nama,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => $status_verifikasi,
                            'pengantar' => $pengantar_spp_barjas,
                            'spp' => $spp_barjas,
                            'ringkasan' => $ringkasan_spp_barjas,
                            'rincian' => $rincian_spp_barjas,
                            'pernyataan' => $pernyataan_barjas,
                            'lampiran' => $lampiran_spp_barjas,
                            'salinan_spd' => $salinan_barjas2,
                            'sk_pns_barjas' => $sk_barjas2,
                            'terima_pns_barjas' => $terima_barjas2,
                            'ka_pns_barjas' => $ka_barjas2,
                            'sse_pns_barjas' => $sse_barjas2,
                        ]);
                }
            }

            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '0',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
