<?php

namespace App\Http\Controllers\Akuntansi\kapit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;
use PhpParser\ErrorHandler\Collecting;
use PDF;
use Knp\Snappy\Pdf as SnappyPdf;
use Yajra\DataTables\Facades\DataTables;


class kapitController extends Controller
{
    public function index()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $data = [
            'bendahara' => DB::table('ms_ttd')
                ->whereIn('kode', ['1'])
                ->orderBy('nip')
                ->orderBy('nama')
                ->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran()
        ];

        return view('akuntansi.kapit.index')->with($data);
    }

    public function cariSkpd(Request $request)
    {
        $type       = Auth::user()->is_admin;
        // $jenis      = $request->jenis;
        $jenis_skpd = substr(Auth::user()->kd_skpd, 18, 4);
        if ($jenis_skpd=='0000') {
            $jenis  = 'skpd';
        }else{
            $jenis  = 'unit';
        }
        $kd_skpd    = Auth::user()->kd_skpd;
        $kd_org     = substr($kd_skpd, 0, 17);
        if ($type == '1') {
            if ($jenis == 'skpd') {
                $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_org')->get();
            } else {
                $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_skpd')->get();
            }
        } else {
            if ($jenis == 'skpd') {
                // select kd_org AS kd_skpd, nm_org AS nm_skpd from [ms_skpd] where LEFT(kd_org) = 5.02.0.00.0.00.01)
                $data   = DB::table('ms_skpd')->where(DB::raw("LEFT(kd_skpd,17)"), '=', $kd_org)->select(DB::raw("kd_skpd AS kd_skpd"), DB::raw("nm_skpd AS nm_skpd"))->get();
            } else {
                $data   = DB::table('ms_skpd')->where(DB::raw("kd_skpd"), '=', $kd_skpd)->select('kd_skpd', 'nm_skpd')->get();
            }
        }
        // dd($kd_skpd);
        return response()->json($data);
    }

    public function cari_rek_objek(Request $request)
    {
        //jaga jaga jika ini yang di pakai
        // $data           = DB::select("SELECT kd_rek3,nm_rek3 FROM ms_rek3 WHERE (left(kd_rek3,2) in ('11','12') OR left(kd_rek3,1)='2' OR kd_rek3='313') 
        //     UNION 
        //     select '15' as kd_rek3, 'Aset Lainnya' as nm_rek3 union SELECT kd_rek3,nm_rek3 FROM ms_rek3 WHERE left(kd_rek3,2) in ('15')
        //     union 
        //     select '1103-1109' as kd_rek3, 'Keseluruhan Piutang' as nm_rek3
        //     ORDER BY kd_rek3");
        $data = DB::select("SELECT kd_rek3,nm_rek3 FROM ms_rek3 WHERE (left(kd_rek3,2) in ('11','12') OR left(kd_rek3,1)='2' OR kd_rek3='313')
        ORDER BY kd_rek3");
        return response()->json($data);

    }

    public function cari_sub_kegiatan(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $jns_ang = collect(DB::select("SELECT TOP 1 jns_ang from trhrka where kd_skpd=? order by tgl_dpa DESC",[$kd_skpd]))->first();
        $data = DB::select("SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan,a.jns_kegiatan 
                        FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                        where a.kd_skpd=? and a.jns_ang=?",[$kd_skpd,$jns_ang->jns_ang]);
        return response()->json($data);

    }

    //inputan
    public function input_kapitalisasi()
    {
        $kd_skpd = Auth::user()->kd_skpd;
        $jns_ang = collect(DB::select("SELECT TOP 1 jns_ang from trhrka where kd_skpd=? order by tgl_dpa DESC",[$kd_skpd]))->first();
        $sub_kegiatan = DB::select("SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan,a.jns_kegiatan 
                        FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                        where a.kd_skpd=? and a.jns_ang=?",[$kd_skpd,$jns_ang->jns_ang]);
        $no_lamp= collect(DB::select("SELECT CONVERT(varchar(10),jumlah)+'-2023-'+REPLACE(kd_skpd,'.','') as nomor FROM
            (SELECT COUNT(*)+1 as jumlah, kd_skpd FROM(
            SELECT no_lamp,kd_skpd FROM lamp_aset UNION ALL
            SELECT no_lamp,kd_skpd FROM trdkapitalisasi) z
            WHERE kd_skpd=? GROUP BY kd_skpd)y",[$kd_skpd]))->first();
        $data = [
            'bendahara' => DB::table('ms_ttd')
                ->whereIn('kode', ['1'])
                ->orderBy('nip')
                ->orderBy('nama')
                ->get(),
            'data_skpd' => DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd', 'bank', 'rekening', 'npwp')->where('kd_skpd', $kd_skpd)->first(),
            'jns_anggaran' => jenis_anggaran(),
            'jns_anggaran2' => jenis_anggaran(),
            'kd_skpd' => $kd_skpd,
            'sub_kegiatan' => $sub_kegiatan,
            'no_lamp' => $no_lamp
        ];

        return view('akuntansi.kapit.inputan.input')->with($data);
    }
    public function load_input_kapitalisasi(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;  
        $kd_sub_kegiatan   = $request->sub_kegiatan;
        // dd($);
        $data = DB::select("SELECT kd_sub_kegiatan,kd_rek6,nm_rek6,nil_ang,kapitalisasi,jenis,nilai_trans FROM trkapitalisasi where kd_sub_kegiatan='$kd_sub_kegiatan' AND kd_skpd='$kd_skpd' order by kd_rek6 ");
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_sub_kegiatan . '\',\'' . $row->kd_rek6 . '\',\'' . $row->nm_rek6 . '\',\'' . $row->nil_ang . '\',\'' . $row->kapitalisasi . '\',\'' . $row->nilai_trans . '\',\'' . $row->jenis . '\');" class="btn btn-primary btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="rinci(\'' . $row->kd_sub_kegiatan . '\',\'' . $row->kd_rek6 . '\',\'' . $row->nm_rek6 . '\',\'' . $row->nil_ang . '\',\'' . $row->kapitalisasi . '\',\'' . $row->nilai_trans . '\',\'' . $row->jenis . '\');" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-newspaper"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus_tr(\'' . $row->kd_sub_kegiatan . '\',\'' . $row->kd_rek6 . '\',\'' . $row->nm_rek6 . '\',\'' . $row->nil_ang . '\',\'' . $row->kapitalisasi . '\',\'' . $row->nilai_trans . '\',\'' . $row->jenis . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function load_input_kapitalisasi_rinci(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;  
        $kd_sub_kegiatan   = $request->kd_sub_kegiatan;
        $kd_rek6   = $request->kd_rek6;
        $norinci  = $kd_skpd.'.'.$kd_sub_kegiatan.'.'.$kd_rek6;
        $data = DB::select("SELECT a.*, CAST(tahun_n+nilai as decimal(20,0)) as tot_kap, 
                        case when left(a.kd_rek3,1)='1' and a.jumlah>=1 then CAST((tahun_n+nilai)/jumlah as decimal(20,0)) else  CAST((tahun_n+nilai)/1 as decimal(20,0)) end as tot_sat_kap,
                        case when b.kd_rek5 is null then 0 else 1 end jenis 
                        from trdkapitalisasi a left join 
                        (select distinct kd_rek5 from ms_rek6) b 
                        on a.kd_rek5=b.kd_rek5 where no_rinci='$norinci' 
                        order by no_lamp");
        // dd($norinci);
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="edit_rinci(\'' . $row->no_rinci . '\',\'' . $row->kd_sub_kegiatan . '\',\'' . $row->kd_rek5_trans . '\',\'' . $row->no_lamp . '\',\'' . $row->kd_rek3 . '\',\'' . $row->nm_rek3 . '\',\'' . $row->kd_rek5 . '\',\'' . $row->nm_rek5 . '\',\'' . $row->kd_rek6 . '\',\'' . $row->nm_rek6 . '\',\'' . $row->tahun . '\',\'' . $row->merk . '\',\'' . $row->no_polisi . '\',\'' . $row->kd_sub_kegiatan . '\',\'' . $row->fungsi . '\',\'' . $row->hukum . '\',\'' . $row->lokasi . '\',\'' . $row->alamat . '\',\'' . $row->sert . '\',\'' . $row->luas . '\',\'' . $row->satuan . '\',\'' . $row->harga_satuan . '\',\'' . $row->piutang_awal . '\',\'' . $row->piutang_koreksi . '\',\'' . $row->piutang_sudah . '\',\'' . $row->investasi_awal . '\',\'' . $row->sal_awal . '\',\'' . $row->kurang . '\',\'' . $row->tambah . '\',\'' . $row->tahun_n . '\',\'' . $row->akhir . '\',\'' . $row->kondisi_b . '\',\'' . $row->kondisi_rr . '\',\'' . $row->kondisi_rb . '\',\'' . $row->keterangan . '\',\'' . $row->kd_skpd . '\',\'' . $row->jumlah . '\',\'' . $row->kepemilikan . '\',\'' . $row->rincian_beban . '\',\'' . $row->no_polis . '\',\'' . $row->bulan . '\',\'' . $row->nilai . '\',\'' . $row->kapitalisasi . '\',\'' . $row->tot_kap . '\',\'' . $row->tot_sat_kap . '\',\'' . $row->jenis . '\');" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapus_rinci(\'' . $row->no_rinci . '\',\'' . $row->kd_sub_kegiatan . '\',\'' . $row->kd_rek5_trans . '\',\'' . $row->no_lamp . '\',\'' . $row->kd_rek3 . '\',\'' . $row->nm_rek3 . '\',\'' . $row->kd_rek5 . '\',\'' . $row->nm_rek5 . '\',\'' . $row->kd_rek6 . '\',\'' . $row->nm_rek6 . '\',\'' . $row->tahun . '\',\'' . $row->merk . '\',\'' . $row->no_polisi . '\',\'' . $row->kd_sub_kegiatan . '\',\'' . $row->fungsi . '\',\'' . $row->hukum . '\',\'' . $row->lokasi . '\',\'' . $row->alamat . '\',\'' . $row->sert . '\',\'' . $row->luas . '\',\'' . $row->satuan . '\',\'' . $row->harga_satuan . '\',\'' . $row->piutang_awal . '\',\'' . $row->piutang_koreksi . '\',\'' . $row->piutang_sudah . '\',\'' . $row->investasi_awal . '\',\'' . $row->sal_awal . '\',\'' . $row->kurang . '\',\'' . $row->tambah . '\',\'' . $row->tahun_n . '\',\'' . $row->akhir . '\',\'' . $row->kondisi_b . '\',\'' . $row->kondisi_rr . '\',\'' . $row->kondisi_rb . '\',\'' . $row->keterangan . '\',\'' . $row->kd_skpd . '\',\'' . $row->jumlah . '\',\'' . $row->kepemilikan . '\',\'' . $row->rincian_beban . '\',\'' . $row->no_polis . '\',\'' . $row->bulan . '\',\'' . $row->nilai . '\',\'' . $row->kapitalisasi . '\',\'' . $row->tot_kap . '\',\'' . $row->tot_sat_kap . '\',\'' . $row->jenis . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function load_input_kapitalisasi_tot_rinci(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;  
        $kd_sub_kegiatan   = $request->kd_sub_kegiatan;
        $kd_rek6   = $request->kd_rek6;
        $norinci  = $kd_skpd.'.'.$kd_sub_kegiatan.'.'.$kd_rek6;
        $data = DB::select("SELECT sum(tot_rinci)tot_rinci, sum(tot_kapit)tot_kapit, sum(tot_trans)tot_trans, sum(tot_kapit_rek)tot_kapit_rek
            from(
            --tot_rinci & tot_kapit
            SELECT sum(tahun_n) as tot_rinci, SUM(nilai) as tot_kapit ,0 tot_trans, 0 tot_kapit_rek
            from trdkapitalisasi 
            where no_rinci='$norinci' 
            union all
            --tot_trans & tot_kapit_rek
            SELECT 0 tot_rinci,0 tot_kapit,nilai_trans tot_trans,kapitalisasi tot_kapit_rek
            FROM trkapitalisasi 
            where kd_rek6='$kd_rek6' and kd_sub_kegiatan='$kd_sub_kegiatan' AND kd_skpd='$kd_skpd'
            )a");
        // dd($norinci);
        return response()->json($data);
    }

    public function load_no_lamp(Request $request)
    {
        $kd_skpd = Auth::user()->kd_skpd;  
        $kd_sub_kegiatan   = $request->kd_sub_kegiatan;
        $kd_rek6   = $request->kd_rek6;
        $norinci  = $kd_skpd.'.'.$kd_sub_kegiatan.'.'.$kd_rek6;
        $data = DB::select("SELECT 
            case when nomor = (select no_lamp as nomor FROM lamp_aset WHERE kd_skpd = '$kd_skpd' and no_lamp = nomor
            UNION ALL 
            SELECT no_lamp as nomor FROM trdkapitalisasi WHERE kd_skpd = '$kd_skpd' and no_lamp = nomor ) then CONCAT('9',nomor) else nomor end nomor
            FROM(SELECT concat(jumlah,'-',DATEPART(hour, getdate()),'-',DATEPART(MINUTE, getdate()),'-2023-',REPLACE(kd_skpd,'.','')) as nomor   FROM
            (SELECT COUNT(*)+1 as jumlah, kd_skpd FROM(
            SELECT no_lamp,kd_skpd FROM lamp_aset UNION ALL
            SELECT no_lamp,kd_skpd FROM trdkapitalisasi) z
            WHERE kd_skpd='$kd_skpd' GROUP BY kd_skpd)y)a");
        // dd($norinci);
        return response()->json($data);
    }

    public function refresh_simpan_tampungan_kapit(Request $request){
        
        $kd_skpd            = $request->kd_skpd;
        $kd_sub_kegiatan    = $request->kd_sub_kegiatan;
        
        $jns_ang = collect(DB::select("SELECT TOP 1 jns_ang from trhrka where kd_skpd=? order by tgl_dpa DESC",[$kd_skpd]))->first();
        
            // dd($kd_sub_kegiatan);
        $delete = DB::delete("DELETE from trkapitalisasi_tampungan where kd_sub_kegiatan='$kd_sub_kegiatan' and kd_skpd = '$kd_skpd'");
        $insert = DB::insert("INSERT into trkapitalisasi_tampungan select kd_sub_kegiatan,kd_rek6,nm_rek6,sum(nil_ang)nil_ang,0 kapitalisasi,jenis,'$kd_skpd' kd_skpd,sum(nilai_trans)nilai_trans from(SELECT kd_sub_kegiatan,kd_rek6,nm_rek6,0 nil_ang,kapitalisasi,'Y'jenis,0 nilai_trans FROM trkapitalisasi where kd_sub_kegiatan='$kd_sub_kegiatan' AND kd_skpd='$kd_skpd' union select a.kd_sub_kegiatan, a.kd_rek6, a.nm_rek6, a.anggaran nil_ang,0 as kapitalisasi,'Y' as jenis, isnull(b.real_spj,0) as nilai_trans from(select a.kd_skpd, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_rek6, a.nm_rek6, nilai as anggaran from trdrka a WHERE a.kd_skpd='$kd_skpd' AND a.kd_sub_kegiatan IN (SELECT kd_sub_kegiatan FROM trdrka WHERE LEFT (kd_rek6, 1) = '5') AND a.kd_sub_kegiatan='$kd_sub_kegiatan' and a.jns_ang='$jns_ang->jns_ang' group by a.kd_skpd, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_rek6, a.nm_rek6,a.nilai) a left join (select kd_skpd, kd_sub_kegiatan, kd_rek6, sum(real_spj) as real_spj from (select c.kd_skpd, b.kd_sub_kegiatan, b.kd_rek6, sum(b.nilai) as real_spj from trdtransout b inner join trhtransout c on b.no_bukti=c.no_bukti and b.kd_skpd=c.kd_skpd where c.kd_skpd='$kd_skpd' group by c.kd_skpd, b.kd_sub_kegiatan, b.kd_rek6 union all select e.kd_skpd, d.kd_sub_kegiatan, d.kd_rek6, sum(d.rupiah*-1) as real_spj  from trdkasin_pkd d inner join trhkasin_pkd e on d.no_sts=e.no_sts and d.kd_skpd=e.kd_skpd where e.jns_trans=5 and LEFT(d.kd_rek6,1)=5 and e.pot_khusus <>0  and e.kd_skpd='$kd_skpd' group by e.kd_skpd, d.kd_sub_kegiatan, d.kd_rek6 union all select a.kd_skpd, b.kd_sub_kegiatan, a.kd_rek6, sum(a.nilai*-1) as real_spj  from trdinlain a inner join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where pengurang_belanja=1 and a.kd_skpd='$kd_skpd' group by a.kd_skpd, b.kd_sub_kegiatan, a.kd_rek6 union all select a.kd_skpd, a.kd_sub_kegiatan, a.kd_rek6, sum(a.nilai) as real_spj from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$kd_skpd' GROUP BY a.kd_skpd,a.kd_sub_kegiatan, a.kd_rek6 ) b group by kd_skpd, kd_sub_kegiatan, kd_rek6) b on a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_rek6=b.kd_rek6)a group by kd_sub_kegiatan,kd_rek6,nm_rek6,jenis");
        if($insert){
            echo '1';
        }else{
            echo '0';
        }

    }

    public function refresh_simpan_tabel_kapit(Request $request){
        
        $kd_skpd            = $request->kd_skpd;
        $kd_sub_kegiatan    = $request->kd_sub_kegiatan;
        
        $delete = DB::delete("DELETE from trkapitalisasi where kd_sub_kegiatan='$kd_sub_kegiatan' and kd_skpd = '$kd_skpd'");
        $insert = DB::insert("INSERT into trkapitalisasi select * from trkapitalisasi_tampungan where kd_sub_kegiatan='$kd_sub_kegiatan' and kd_skpd = '$kd_skpd'");
            // dd()
        if($insert){
            echo '1';
        }else{
            echo '0';
        }

    }
    public function hitung_kapit_kegiatan(Request $request){
        
        $kd_skpd            = $request->kd_skpd;
        $kd_sub_kegiatan    = $request->kd_sub_kegiatan;
        
        $hitung = DB::statement("exec kapitalisasi_kegiatan_anguz '$kd_skpd','$kd_sub_kegiatan'");
        if($hitung){
            echo '1';
        }else{
            echo '0';
        }

    }

    public function cari_kd_rek6(Request $request)
    {
        $skpd     = Auth::user()->kd_skpd;
        $cgiat    = $request->kd_sub_kegiatan;
        
        $jns_anggaran = collect(DB::select("SELECT TOP 1 jns_ang from trhrka where kd_skpd=? order by tgl_dpa DESC",[$skpd]))->first();
        $jns_ang = $jns_anggaran->jns_ang;
        $data = DB::select("SELECT a.kd_sub_kegiatan, a.kd_rek6, a.nm_rek6, a.anggaran,isnull(c.kapitalisasi,0) as kapitalisasi,'Y' as jenis,a.kd_skpd, isnull(b.real_spj,0) as transaksi 
            from
                (select a.kd_skpd, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_rek6, a.nm_rek6, nilai as anggaran 
                    from trdrka a 
                    WHERE a.kd_skpd='$skpd' and kd_sub_kegiatan='$cgiat' AND LEFT (kd_rek6, 1) = '5' and jns_ang = '$jns_ang'
                    group by a.kd_skpd, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_rek6, a.nm_rek6,a.nilai
                ) a
                left join
                (select kd_skpd, kd_sub_kegiatan, kd_rek6, sum(real_spj) as real_spj 
                    from 
                    (select c.kd_skpd, b.kd_sub_kegiatan, b.kd_rek6, sum(b.nilai) as real_spj 
                        from trdtransout b inner join trhtransout c on b.no_bukti=c.no_bukti and b.kd_skpd=c.kd_skpd 
                        where c.kd_skpd='$skpd'
                        group by c.kd_skpd, b.kd_sub_kegiatan, b.kd_rek6
                        union all
                        select e.kd_skpd, d.kd_sub_kegiatan, d.kd_rek6, sum(d.rupiah*-1) as real_spj 
                        from trdkasin_pkd d inner join trhkasin_pkd e on d.no_sts=e.no_sts and d.kd_skpd=e.kd_skpd
                        where e.jns_trans=5 and LEFT(d.kd_rek6,1)=5 and e.pot_khusus <>0 and e.kd_skpd='$skpd' 
                        group by e.kd_skpd, d.kd_sub_kegiatan, d.kd_rek6
                        union all
                        select a.kd_skpd, b.kd_sub_kegiatan, a.kd_rek6, sum(a.nilai*-1) as real_spj 
                        from trdinlain a inner join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 
                        where pengurang_belanja=1 and a.kd_skpd='$skpd' 
                        group by a.kd_skpd, b.kd_sub_kegiatan, a.kd_rek6 
                    ) b group by kd_skpd, kd_sub_kegiatan, kd_rek6
                ) b on a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_rek6=b.kd_rek6 
                left join trkapitalisasi c on a.kd_skpd=c.kd_skpd and a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_rek6=c.kd_rek6");
        return response()->json($data);
    }

    public function cari_kd_rek3rinci(Request $request)
    {
        $skpd     = Auth::user()->kd_skpd;
        // $rek3    = $request->rek3;
        
        $jns_anggaran = collect(DB::select("SELECT TOP 1 jns_ang from trhrka where kd_skpd=? order by tgl_dpa DESC",[$skpd]))->first();
        $jns_ang = $jns_anggaran->jns_ang;
        $data = DB::select("SELECT kd_rek3,nm_rek3 FROM ms_rek3 WHERE (left(kd_rek3,3)='130' and kd_rek3 !='1307'  or kd_rek3='1503' or kd_rek3='1112'
        --OR kd_rek3='3103' OR left(kd_rek3,2)in('21','22')
            )
                union all
                select  '8' kd_rek3, 'Beban' nm_rek3           
                ORDER BY kd_rek3");
        return response()->json($data);
    }

    public function cari_kd_rek6rinci(Request $request)
    {
        $skpd     = Auth::user()->kd_skpd;
        $kdrek3    = $request->rek3;
        
        $jns_anggaran = collect(DB::select("SELECT TOP 1 jns_ang from trhrka where kd_skpd=? order by tgl_dpa DESC",[$skpd]))->first();
        $jns_ang = $jns_anggaran->jns_ang;
        $data = DB::select("SELECT b.kd_rek5,b.nm_rek5,kd_rek6,nm_rek6 FROM ms_rek6 a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
            WHERE left(a.kd_rek5,4)='$kdrek3' 
            union all
            select * from (
            select b.kd_rek5,b.nm_rek5,kd_rek6,nm_rek6 FROM ms_rek6 a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
            WHERE left(a.kd_rek5,1)='$kdrek3' ) z where kd_rek6 between '810201010001' and '810201010051' or left(kd_rek6,8) between '81020208' and '81020209' or left(kd_rek6,6)='810203' or kd_rek6 in('810103070002','810202010062')");
        return response()->json($data);
    }

    public function input_inputan(Request $request){
        $kd_skpd    = $request->kd_skpd;
        $kd_sub_kegiatan   = $request->kd_sub_kegiatan;
        $kd_rek6    = $request->kd_rek6;
        $nm_rek6    = nama_rekening($kd_rek6);
        $anggaran   = $request->anggaran;
        $kapit   = $request->kapit;
        $trans   = $request->trans;
        $jenis   = $request->jenis;
        $status_input   = $request->status_input;
        if ($status_input=="tambah"){
            $hasil=DB::insert("INSERT into trkapitalisasi (kd_sub_kegiatan, kd_rek6, nm_rek6, nil_ang, kapitalisasi, jenis, kd_skpd, nilai_trans) values '$kd_sub_kegiatan', '$kd_rek6', '$nm_rek6', '$anggaran', '$kapit', '$jenis','$kd_skpd', '$trans'");
            if ($hasil) {
                $kode = "1";
                $pesan = "'Data Tertambah'";
            }else{
                $kode = "0";
                $pesan = "'Data Tidak Tertambah'";
            }
        } else if($status_input=="edit"){
            $hasil=DB::update("UPDATE trkapitalisasi SET nil_ang='$anggaran', kapitalisasi='$kapit', jenis='$jenis', nilai_trans='$trans' where kd_skpd='$kd_skpd' and kd_sub_kegiatan='$kd_sub_kegiatan'and kd_rek6='$kd_rek6'");
            if ($hasil) {
                $kode = "1";
                $pesan = "'Data Tersimpan'";
            }else{
                $kode = "0";
                $pesan = "'Data Tidak Tersimpan'";
            }
        }else{
            $kode = "0";
            $pesan = "'Status Input Tidak Jelas'";
        }

        $msg = array('kode'=>$kode,'pesan'=>$pesan);
        echo json_encode($msg);
        
    }

    public function hapus_tr(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $kd_sub_kegiatan    = $request->kd_sub_kegiatan;
        $kd_rek6            = $request->kd_rek6;
        $jenis              = $request->jenis;
        
        // dd($jenis);

        $hasil = DB::delete("DELETE from trkapitalisasi where kd_sub_kegiatan='$kd_sub_kegiatan' and kd_skpd = '$kd_skpd' and kd_rek6='$kd_rek6' and jenis='$jenis'");

        if ($hasil) {
            $kode = "1";
            $pesan = "Data Terhapus";
        }else{
            $kode = "0";
            $pesan = "Data Tidak Terhapus";
        }
    
        $msg = array('kode'=>$kode,'pesan'=>$pesan);
        echo json_encode($msg);
        
    }

    public function cek_simpan(Request $request){
        $nomor    = $request->no;
        $tabel   = $request->tabel;
        $field    = $request->field;
        $field2    = $request->field2;
        $tabel2   = $request->tabel2;
        $kd_skpd  = Auth::user()->kd_skpd;
        if ($field2==''){
        $hasil=collect(DB::select("SELECT count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' "))->first();
        } else{
        $hasil=collect(DB::select("SELECT count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL SELECT $field2 as nomor FROM $tabel2 WHERE kd_skpd = '$kd_skpd')a WHERE a.nomor = '$nomor'"))->first();
        }
        $jumlah=$hasil->jumlah; 
        
        if($jumlah>0){
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
        } else{
        $msg = array('pesan'=>'0');
        echo json_encode($msg);
        }
    }

    public function simpan_rincian(Request $request){
        
        $tabel   = $request->tabel;
        $lckolom = $request->kolom;
        $lcnilai = $request->nilai;
        $cid     = $request->cid;
        $lcid    = $request->lcid;
        
        $asg = DB::insert("insert into $tabel $lckolom values $lcnilai");
        if($asg){
            echo '2';
        }else{
            echo '0';
        }
    }

    public function update_rincian(Request $request){
        $skpd   = Auth::user()->kd_skpd;
        $tabel  = $request->tabel;
        $cid    = $request->cid;
        $lcid   = $request->lcid;
        $lcid_h = $request->lcid_h;
        
        if (  $lcid <> $lcid_h ) {
            
           $res     = DB::select("select $cid from $tabel where $cid='$lcid' AND kd_skpd='$skpd'");
           if ( count($res)>0 ) {
                echo '1';
                exit();
           } 
        }
        
        $query   = $request->st_query;
        $asg     = DB::update("$query");
        if ( $asg > 0 ){
           echo '2';
        } else {
           echo '0';
        }
    
    }
    
    public function hapus_rincian(Request $request){
        $kd_skpd   = Auth::user()->kd_skpd;
        $nomor  = $request->no;

        $query = DB::delete("delete from trdkapitalisasi where no_lamp='$nomor' and kd_skpd = '$kd_skpd'");
        if ($query) {
            return response()->json([
                'pesan' => '1'
            ]);
        } else {
            return response()->json([
                'pesan' => '0'
            ]);
        }
    
    }

    function hitung_rincian_kapit(Request $request){
        $skpd     = Auth::user()->kd_skpd;
        $giat  = $request->giat;
        $rek  = $request->rek;

        //$asg= $this->db->query("exec kapitalisasi_transfer '$skpd'");
        $asg = DB::statement("exec rincian_kapitalisasi_anguz '$skpd','$giat','$rek'");
        if ($asg) { 
            echo '1';
        }else{
           echo '0';
        }
    }


}
