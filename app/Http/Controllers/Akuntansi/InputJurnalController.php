<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use PDF;
use Exception;

class InputJurnalController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'ttd1' => DB::table('ms_ttd')
                ->select('nip', 'nama')
                ->whereIn('kode', ['BUD'])
                ->get(),
        ];

        return view('akuntansi.input_jurnal.index')->with($data);
    }

    public function load()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $tahun = tahun_anggaran();

        $data = DB::select("SELECT * from trhju_pkd  WHERE kd_skpd = ? and year(tgl_voucher)>=? AND tabel=? order by tgl_voucher,no_voucher,kd_skpd", [$kd_skpd, $tahun, '1']);

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("input_jurnal.edit", ['no_voucher' => Crypt::encrypt($row->no_voucher), 'kd_skpd' => Crypt::encrypt($row->kd_skpd)]) . '" class="btn btn-warning btn-sm"  style="margin-right:4px"><i class="uil-edit"></i></a>';

            $btn .= '<a href="javascript:void(0);" onclick="hapus(\'' . $row->no_voucher . '\',\'' . $row->kd_skpd . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function create()
    {
        $data = [
            'daftar_skpd' => DB::select("SELECT kd_skpd,nm_skpd FROM ms_skpd ORDER BY kd_skpd"),
            'daftar_rekening' => DB::select("SELECT kd_rek6,nm_rek6 from ms_rek6 where nm_rek6 like '%uang dana bos%' and left(kd_rek6,1)=?", ['5']),
            'daftar_hibah' => DB::select("SELECT kd_rek6,nm_rek6 FROM ms_rek6 where kd_rek6 in ('730101010001','730102010001','730103010001','730104010001','730104020001','730104030001','730104040001')  order by kd_rek6"),
            'kd_skpd' => Auth::user()->kd_skpd
        ];

        return view('akuntansi.input_jurnal.create')->with($data);
    }

    public function kegiatan(Request $request)
    {
        $jenis = $request->jenis;
        $kd_skpd = $request->kd_skpd;

        $len = strlen($jenis);

        $data = DB::select("SELECT distinct a.kd_sub_kegiatan,a.nm_sub_kegiatan,'' kd_program, '' as nm_program, 0 total FROM trdrka a
                WHERE a.kd_skpd=? and left(a.kd_rek6,$len)=?", [$kd_skpd, $jenis]);

        return response()->json($data);
    }

    public function rekening(Request $request)
    {
        $kd_rek6 = $request->kd_rek6;
        $jenis = $request->jenis;
        $kd_sub_kegiatan = $request->kd_sub_kegiatan;
        $kd_skpd = $request->kd_skpd;

        $len = strlen($jenis);

        $rekening = array();
        if (!empty($kd_rek6)) {
            foreach ($kd_rek6 as $rek) {
                $rekening[] = $rek['kd_rek6'];
            }
        } else {
            $rekening[] = '';
        }

        if ($jenis == '5' || $jenis == '6' || $jenis == '4') {
            $data =
                DB::table('trdrka as a')
                ->join('ms_rek6 as b', 'a.kd_rek6', '=', 'b.kd_rek6')
                ->selectRaw("a.kd_rek6,a.nm_rek6")
                ->where(['a.kd_sub_kegiatan' => $kd_sub_kegiatan, 'a.kd_skpd' => $kd_skpd])
                ->whereNotIn('a.kd_rek6', $rekening)
                ->orderBy('kd_rek6')
                ->get();
        } else if ($jenis == '0') {
            $data = DB::select("SELECT top 1 '000000000000' as kd_rek6,'Perubahan SAL' as nm_rek6 FROM ms_rek6 a order by kd_rek6");
        } else {
            $data =
                DB::table('ms_rek6 as a')
                ->whereRaw("left(kd_rek6,$len)=?", [$jenis])
                ->whereNotIn('a.kd_rek6', $rekening)
                ->orderBy('kd_rek6')
                ->get();
        }

        return response()->json($data);
    }

    public function simpan(Request $request)
    {
        $data = $request->data;

        try {
            DB::beginTransaction();

            $cek = DB::table('trhju_pkd')
                ->where(['no_voucher' => $data['no_voucher'], 'kd_skpd' => $data['kd_skpd']])
                ->count();

            if ($cek > 0) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhju_pkd')
                ->where(['no_voucher' => $data['no_voucher'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            $ket = $data['ket_mutasi1'] . ' ' . $data['nmskpd_mutasi_'] . ' ' . $data['ket_mutasi2'] . ' ' . $data['keterangan'];

            DB::table('trhju_pkd')
                ->insert([
                    'no_voucher' => $data['no_voucher'],
                    'tgl_voucher' => $data['tgl_voucher'],
                    'ket' => $ket,
                    'username' => Auth::user()->nama,
                    'tgl_update' => date('Y-m-d H:i:s'),
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'total_d' => $data['total_debet'],
                    'total_k' => $data['total_kredit'],
                    'tabel' => '1',
                    'reev' => $data['jenis_jurnal'],
                    'tgl_real' => $data['cj_d'],
                    'kd_skpd_mutasi' => $data['skpd_mutasi_'],
                    'nm_skpd_mutasi' => $data['nmskpd_mutasi_'],
                ]);

            DB::table('trdju_pkd')
                ->where(['no_voucher' => $data['no_voucher'], 'kd_unit' => $data['kd_skpd']])
                ->delete();

            $data['detail_rincian'] = json_decode($data['detail_rincian'], true);

            $rincian_data = $data['detail_rincian'];

            $urut = 0;

            $rekening = ["4", "5", "6"];

            foreach ($rincian_data as $input => $value) {
                $data_input = [
                    'no_voucher' => $data['no_voucher'],
                    'kd_sub_kegiatan' => $rincian_data[$input]['kd_sub_kegiatan'],
                    'nm_sub_kegiatan' => $rincian_data[$input]['nm_sub_kegiatan'],
                    'kd_rek6' => $rincian_data[$input]['kd_rek6'],
                    'nm_rek6' => $rincian_data[$input]['nm_rek6'],
                    'debet' => $rincian_data[$input]['debet'],
                    'kredit' => $rincian_data[$input]['kredit'],
                    'rk' => $rincian_data[$input]['rk'],
                    'jns' => $rincian_data[$input]['jns'],
                    'kd_unit' => $data['kd_skpd'],
                    'pos' => $rincian_data[$input]['post'],
                    'hibah' => $rincian_data[$input]['hibah'],
                    'map_real' =>  in_array(substr($rincian_data[$input]['kd_rek6'], 0, 1), $rekening) ? $rincian_data[$input]['kd_rek6'] : '',
                    'urut' => $urut++,
                ];
                DB::table('trdju_pkd')->insert($data_input);
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

    public function edit($no_voucher, $kd_skpd)
    {
        $no_voucher = Crypt::decrypt($no_voucher);
        $kd_skpd = Crypt::decrypt($kd_skpd);

        $data = [
            'daftar_skpd' => DB::select("SELECT kd_skpd,nm_skpd FROM ms_skpd ORDER BY kd_skpd"),
            'daftar_rekening' => DB::select("SELECT kd_rek6,nm_rek6 from ms_rek6 where nm_rek6 like '%uang dana bos%' and left(kd_rek6,1)=?", ['5']),
            'daftar_hibah' => DB::select("SELECT kd_rek6,nm_rek6 FROM ms_rek6 where kd_rek6 in ('730101010001','730102010001','730103010001','730104010001','730104020001','730104030001','730104040001')  order by kd_rek6"),
            'kd_skpd' => Auth::user()->kd_skpd,
            'jurnal' => DB::table('trhju_pkd')
                ->where(['no_voucher' => $no_voucher, 'kd_skpd' => $kd_skpd])
                ->first(),
            'detail_jurnal' => DB::select("SELECT a.no_voucher,b.kd_sub_kegiatan,b.nm_sub_kegiatan,b.kd_rek6,b.map_real,case when rk='D' then b.nm_rek6 else SPACE(4)+b.nm_rek6 end AS nm_rek6,b.debet,b.kredit,b.rk,b.jns,b.pos,b.hibah FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
		    WHERE a.no_voucher=? AND a.kd_skpd =?", [$no_voucher, $kd_skpd]),
            'hibah1' => DB::table('trdju_pkd')
                ->select('hibah')
                ->where(['no_voucher' => $no_voucher, 'kd_unit' => $kd_skpd])
                ->first()
        ];

        return view('akuntansi.input_jurnal.edit')->with($data);
    }

    public function update(Request $request)
    {
        $data = $request->data;

        try {
            DB::beginTransaction();

            $cek = DB::table('trhju_pkd')
                ->where(['no_voucher' => $data['no_voucher'], 'kd_skpd' => $data['kd_skpd']])
                ->count();

            if ($cek > 0 && $data['no_voucher'] != $data['no_tersimpan']) {
                return response()->json([
                    'message' => '2'
                ]);
            }

            DB::table('trhju_pkd')
                ->where(['no_voucher' => $data['no_tersimpan'], 'kd_skpd' => $data['kd_skpd']])
                ->delete();

            $ket = $data['ket_mutasi1'] . ' ' . $data['nmskpd_mutasi_'] . ' ' . $data['ket_mutasi2'] . ' ' . $data['keterangan'];

            DB::table('trhju_pkd')
                ->insert([
                    'no_voucher' => $data['no_voucher'],
                    'tgl_voucher' => $data['tgl_voucher'],
                    'ket' => $ket,
                    'username' => Auth::user()->nama,
                    'tgl_update' => date('Y-m-d H:i:s'),
                    'kd_skpd' => $data['kd_skpd'],
                    'nm_skpd' => $data['nm_skpd'],
                    'total_d' => $data['total_debet'],
                    'total_k' => $data['total_kredit'],
                    'tabel' => '1',
                    'reev' => $data['jenis_jurnal'],
                    'tgl_real' => $data['cj_d'],
                    'kd_skpd_mutasi' => $data['skpd_mutasi_'],
                    'nm_skpd_mutasi' => $data['nmskpd_mutasi_'],
                ]);

            DB::table('trdju_pkd')
                ->where(['no_voucher' => $data['no_tersimpan'], 'kd_unit' => $data['kd_skpd']])
                ->delete();

            $data['detail_rincian'] = json_decode($data['detail_rincian'], true);

            $rincian_data = $data['detail_rincian'];

            $urut = 0;

            $rekening = ["4", "5", "6"];

            foreach ($rincian_data as $input => $value) {
                $data_input = [
                    'no_voucher' => $data['no_voucher'],
                    'kd_sub_kegiatan' => $rincian_data[$input]['kd_sub_kegiatan'],
                    'nm_sub_kegiatan' => $rincian_data[$input]['nm_sub_kegiatan'],
                    'kd_rek6' => $rincian_data[$input]['kd_rek6'],
                    'nm_rek6' => $rincian_data[$input]['nm_rek6'],
                    'debet' => $rincian_data[$input]['debet'],
                    'kredit' => $rincian_data[$input]['kredit'],
                    'rk' => $rincian_data[$input]['rk'],
                    'jns' => $rincian_data[$input]['jns'],
                    'kd_unit' => $data['kd_skpd'],
                    'pos' => $rincian_data[$input]['post'],
                    'hibah' => $rincian_data[$input]['hibah'],
                    'map_real' => in_array(substr($rincian_data[$input]['kd_rek6'], 0, 1), $rekening) ? $rincian_data[$input]['kd_rek6'] : '',
                    'urut' => $urut++,
                ];
                DB::table('trdju_pkd')->insert($data_input);
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

    public function hapus(Request $request)
    {
        $no_voucher = $request->no_voucher;
        $kd_skpd = $request->kd_skpd;

        DB::beginTransaction();
        try {
            DB::delete("DELETE from trdju_pkd where no_voucher=? AND kd_unit=?", [$no_voucher, $kd_skpd]);

            DB::delete("DELETE from trhju_pkd where no_voucher=? AND kd_skpd=?", [$no_voucher, $kd_skpd]);

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
