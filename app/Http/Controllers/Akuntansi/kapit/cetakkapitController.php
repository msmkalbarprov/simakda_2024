<?php

namespace App\Http\Controllers\Akuntansi\kapit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Static_;
use PDF;
use Knp\Snappy\Pdf as SnappyPdf;

class cetakkapitController extends Controller
{
    public function cetak_kapitalisasi(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $kd_skpd = $request->kd_skpd;  
        $cetakan = $request->cetakan;
        $cetak   = $request->cetak;
        
        // dd($kd_skpd);
        
        $thn_ang    = tahun_anggaran();
        // $thn_ang1   = $lntahunang-1;

        $rincian = DB::select("SELECT * from cetak_kapitalisasi_oyoy ('$kd_skpd') ORDER BY urut");  
             
        $total = collect(DB::select("SELECT sum(ang_peg) ang_peg, sum(real_peg) real_peg, sum(ang_brg) ang_brg,sum(real_brg) real_brg, sum(ang_mod) ang_mod, 
                                            sum(real_mod) real_mod, sum(ang_x) ang_x, sum(real_x) real_x, sum(ang_peng) ang_peng, sum(real_peng) real_peng, sum(nil_kap) nil_kap, 
                                            sum(ang_pengx) ang_pengx, sum(real_pengx) real_pengx, sum(ang_kap) ang_kap, 
                                            sum(tot_kap) tot_kap from cetak_kapitalisasi_brewok ('$kd_skpd') where len(kode)=7 "))->first();
        $total_real = collect(DB::select("SELECT isnull(sum(a.ang_peg),0) as ang_peg,isnull(sum(a.real_peg),0) as real_peg,isnull(sum(a.ang_brg),0) as ang_brg,isnull(sum(a.real_brg),0) as real_brg,isnull(sum(a.ang_mod),0) as ang_mod,isnull(sum(a.real_mod),0) as real_mod,
                    isnull(sum(a.ang_pengx),0) as ang_pengx,isnull(sum(a.real_pengx),0) as real_pengx,isnull(sum(a.ang_kap),0) as ang_kap,isnull(sum(a.nil_kap),0) as nil_kap,isnull(sum(a.tot_kap),0) as tot_kap
                    from
                    (select a.*,a.ang_x+ang_peng as ang_pengx,a.real_x+real_peng as real_pengx,a.ang_peg+a.ang_brg+a.ang_mod+a.ang_peng as ang_kap,
                    a.real_mod+real_brg+a.real_peg+a.nil_kap as tot_kap from (
                    select a.urut,a.kode,a.uraian,a.kd_barang,a.nama_barang,a.klasifikasi,a.ang_peg,a.real_peg,a.ang_brg,a.real_brg,ang_mod,a.real_mod,
                    a.ang_x,a.real_x,a.ang_peng,a.real_peng,a.nil_kap,a.qty,a.sat,a.jen,
                     case when CAST (a.tot_sat_kap as decimal) <500000 then CAST (a.ket as varchar) else '' end as ket
                    from (
                    SELECT kd_sub_kegiatan+kd_rek5_trans+no_lamp as urut, '' as kode, '' as uraian,kd_rek6 as kd_barang, nm_rek6 as nama_barang, nm_rek3 as klasifikasi, 
                    0 AS ang_peg, CAST((tahun_n+nilai)/nullif(jumlah,0) as decimal(20,0)) as tot_sat_kap,
                    SUM(CASE WHEN LEFT(a.kd_rek5_trans, 4) in ('5101') THEN a.tahun_n ELSE 0 END) AS real_peg,
                    0 AS ang_brg,
                    SUM(CASE WHEN LEFT(a.kd_rek5_trans, 4) = '5102' THEN a.tahun_n ELSE 0 END) AS real_brg,
                    0 AS ang_mod,
                    SUM(CASE WHEN LEFT(a.kd_rek5_trans, 2) in ('52') THEN a.tahun_n ELSE 0 END) AS real_mod,
                    0 ang_x,
                    0 real_x,
                    0 ang_peng,
                    0 real_peng,
                    SUM(a.nilai) as nil_kap,
                    jumlah as qty,
                    satuan as sat,
                    harga_satuan,
                    'Y' as jen,'Extracomptable' as ket
                    from trdkapitalisasi a 
                    WHERE a.kd_skpd='$kd_skpd'
                    GROUP BY kd_sub_kegiatan,kd_rek5_trans,no_lamp,nm_rek6,nm_rek3,tahun_n,nilai,jumlah,satuan,harga_satuan,kd_rek6) a) a where left(kd_barang,1)!='8' and ket!='Extracomptable') a"))->first();


        // $daerah = DB::table('sclient')->select('daerah')->where('kd_skpd', $skpd)->first();
            // dd($sus);
        
            $data = [
            'header'         => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'rincian'          => $rincian,
            'total'          => $total,
            'total_real'          => $total_real,
            'kd_skpd'           => $kd_skpd,
            'cetakan'          => $cetakan,
            'thn_ang'     => $thn_ang  
            ];
        // dd($data['ekuitas_awal']->nilai);
            $view =  view('akuntansi.cetakan.kapit.kapit')->with($data);

        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('Umur Piutang.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="Umur Piutang.xls"');
            return $view;
        }
    }
}
