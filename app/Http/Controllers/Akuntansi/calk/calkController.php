<?php

namespace App\Http\Controllers\Akuntansi\calk;

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


class calkController extends Controller
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

        return view('akuntansi.calk.index')->with($data);
    }

    public function cariSkpd(Request $request)
    {
        $type       = Auth::user()->is_admin;
        $jenis      = $request->jenis;
        $kd_skpd    = Auth::user()->kd_skpd;
        $kd_org     = substr($kd_skpd, 0, 17);
        if ($type == '1') {
            if ($jenis == 'skpd') {
                $data   = DB::table('ms_organisasi')->select('kd_org as kd_skpd', 'nm_org as nm_skpd')->orderBy('kd_org')->get();
            } else {
                $data   = DB::table('ms_skpd')->select('kd_skpd', 'nm_skpd')->orderBy('kd_skpd')->get();
            }
        } else {
            if ($jenis == 'skpd') {
                // select kd_org AS kd_skpd, nm_org AS nm_skpd from [ms_skpd] where LEFT(kd_org) = 5.02.0.00.0.00.01)
                $data   = DB::table('ms_organisasi')->where(DB::raw("LEFT(kd_skpd,17)"), '=', $kd_org)->select('kd_org as kd_skpd', 'nm_org as nm_skpd')->get();
            } else {
                $data   = DB::table('ms_skpd')->where(DB::raw("kd_skpd"), '=', $kd_skpd)->select('kd_skpd', 'nm_skpd')->get();
            }
        }
        // dd($kd_skpd);
        return response()->json($data);
    }


    function cariPaKpa(Request $request)
    {
        $kd_skpd    = $request->kd_skpd;
        $leng_skpd  = strlen($kd_skpd);
        if ($leng_skpd=="17") {
            $data       = DB::table('ms_ttd')->where(DB::raw("LEFT(kd_skpd,17)"), '=', $kd_skpd)->whereIn('kode', ['PA', 'KPA'])->orderBy('nip')->orderBy('nama')->get();
        }else{
            $data       = DB::table('ms_ttd')->where(['kd_skpd' => $kd_skpd])->whereIn('kode', ['PA', 'KPA'])->orderBy('nip')->orderBy('nama')->get();
        }
        return response()->json($data);
    }

    function cetak_calk(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $kd_skpd        = $request->kd_skpd;  
        $lampiran       = $request->lampiran;
        $judul          = $request->judul;
        $ttd            = $request->ttd;
        $jenis          = $request->jenis;
        $skpdunit       = $request->skpdunit;
        $cetak          = $request->cetak;
        $tanggal = "29 Desember 2023";
        $tempat_tanggal = "Pontianak, 29 Desember 2023";
        $bulan          = 12;
        $thn_ang        = tahun_anggaran();
        $thn_ang_1        = $thn_ang-1;
        $thn_bln        = "$thn_ang$bulan";

        $spasi = "line-height: 1.5em;";
        if ($skpdunit=="skpd") {
            $nm_skpd = nama_org($kd_skpd);
        }else{
            $nm_skpd = nama_skpd($kd_skpd);
        }
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' and ";
        $unit_clause= "left(kd_unit,len('$kd_skpd'))='$kd_skpd' ";

        $ttd_nih = collect(DB::select("SELECT nama,nip,jabatan,pangkat FROM ms_ttd where $skpd_clause kode IN ('PA','KPA') and nip='$ttd'"))->first();

        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran from trhrka where $skpd_clause status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
        if ($lampiran == "1") {
            $data = [
            'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'ttd_nih'       => $ttd_nih,
            'kd_skpd'       => $kd_skpd,
            'nm_skpd'       => $nm_skpd,
            'lampiran'      => $lampiran,
            'judul'         => $judul,
            'jenis'         => $jenis,
            'tempat_tanggal'=> $tempat_tanggal,
            'spasi'         => $spasi,
            'cetak'       => $cetak,
            'thn_ang'       => $thn_ang  
            ];
        
            $view =  view('akuntansi.cetakan.calk.kata_pengantar')->with($data);
        }else if($lampiran=="2"){
            $data = [
            'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'ttd_nih'       => $ttd_nih,
            'kd_skpd'       => $kd_skpd,
            'nm_skpd'       => $nm_skpd,
            'lampiran'      => $lampiran,
            'judul'         => $judul,
            'jenis'         => $jenis,
            'tempat_tanggal'=> $tempat_tanggal,
            'spasi'         => $spasi,
            'cetak'         => $cetak,
            'thn_ang'       => $thn_ang  
            ];
        
            $view =  view('akuntansi.cetakan.calk.daftar_isi')->with($data);
        }else if($lampiran=="3"){
            $data = [
            'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'ttd_nih'       => $ttd_nih,
            'kd_skpd'       => $kd_skpd,
            'nm_skpd'       => $nm_skpd,
            'lampiran'      => $lampiran,
            'judul'         => $judul,
            'jenis'         => $jenis,
            'tempat_tanggal'=> $tempat_tanggal,
            'spasi'         => $spasi,
            'cetak'         => $cetak,
            'thn_ang'       => $thn_ang  
            ];
        
            $view =  view('akuntansi.cetakan.calk.pernyataan_tanggung_jawab')->with($data);
        }else if($lampiran=="4"){
            //lra
                $jum_pend = collect(DB::select("SELECT SUM(anggaran) as anggaran,SUM(real_spj)as nilai FROM data_realisasi_n_at($bulan,'$jns_ang',$thn_ang) WHERE $skpd_clause left(kd_rek6,1)='4' "))->first();
                $jum_bel = collect(DB::select("SELECT SUM(anggaran) as anggaran,SUM(real_spj)as nilai FROM data_realisasi_n_at($bulan,'$jns_ang',$thn_ang) WHERE $skpd_clause left(kd_rek6,1)='5' "))->first();
            //lo
                $jum_pend_lo = collect(DB::select("SELECT SUM(kredit-debet) as nilai 
                    FROM trdju_calk a inner join trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    WHERE $skpd_clause
                    ( left(kd_rek6,4) in ('7101','7102','7103','7104','7202','7301','7302','7303') or
                      left(kd_rek6,6) in ('720102','720103','720104','720105') or
                      left(kd_rek6,8) in ('72010101','72010102','72010103','72010104')
                    ) 
                    and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan "))->first();
                $jum_beban_lo = collect(DB::select("SELECT SUM(debet-kredit) as nilai 
                    FROM trdju_calk a inner join trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    WHERE $skpd_clause 
                    ( left(kd_rek6,1) in ('8')) 
                    and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan "))->first();
                $jum_surdef_lo = collect(DB::select("SELECT SUM(kredit-debet) as nilai 
                    FROM trdju_calk a inner join trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    WHERE $skpd_clause 
                    ( left(kd_rek6,4) in ('7101','7102','7103','7104','7301','7302','7303','8101','8102','8103','8104','8105','8106','8201','8202','8203','8204','8205','8206','8301','8302','8401') OR
                      left(kd_rek6,5) in ('720102','720102','720104','720105','720202')
                    ) 
                    and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan "))->first();
            //neraca
                $aset = collect(DB::select("SELECT isnull(sum(debet-kredit),0) nilai 
                    from trhju a inner join trdju b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd
                    where $skpd_clause  left(kd_rek6,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan'   AND kd_skpd<>'4.02.02.02'"))->first();
                $aset_lancar = collect(DB::select("SELECT SUM(b.debet-b.kredit) AS nilai 
                    from trhju_calk a inner join trdju_calk b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
                    where $skpd_clause left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and
                                    (kd_rek6 like '1101%' or kd_rek6 like '1102%'  or 
                                    kd_rek6 like '1103%' or kd_rek6 like '1104%'  or 
                                    kd_rek6 like '1105%' or kd_rek6 like '1106%'  or 
                                    kd_rek6 like '1107%' or kd_rek6 like '1108%' or 
                                    kd_rek6 like '1109%' or kd_rek6 like '1110%'or 
                                    kd_rek6 like '1111%' or kd_rek6 like '1112%') "))->first();
                $aset_lancar = collect(DB::select("SELECT SUM(b.debet-kredit) AS nilai 
                    from trhju_calk a inner join trdju_calk b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
                    where $skpd_clause left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and
                                    (kd_rek6 like '13%') "))->first();
                $aset_tetap = collect(DB::select("SELECT SUM(b.debet-kredit) AS nilai 
                    from trhju_calk a inner join trdju_calk b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
                    where $skpd_clause left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and
                                    (kd_rek6 like '150101%' or kd_rek6 like '150102%' or 
                                    kd_rek6 like '1502%' or kd_rek6 like '1503%' or 
                                    kd_rek6 like '1504%' or kd_rek6 like '1505%' or 
                                    kd_rek6 like '1506%') "))->first();
                $kewajiban = collect(DB::select("SELECT SUM(b.kredit-debet) AS nilai 
                    from trhju_calk a inner join trdju_calk b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
                    where $skpd_clause left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and
                                    (kd_rek6 like '2%' ) "))->first();
                $ekuitas_rkppkd = collect(DB::select("SELECT SUM(nilai) as nilai 
                    FROM data_ekuitas_dgn_rkppkd($bulan,$thn_ang,$thn_ang_1,'$thn_ang$bulan') 
                    WHERE $unit_clause "))->first();
                $eku_tang = collect(DB::select("SELECT SUM(nilai) as nilai 
                    FROM data_eku_tang($bulan,$thn_ang,$thn_ang_1,'$thn_ang$bulan') 
                    WHERE $unit_clause "))->first();
                $ekuitas_awal = collect(DB::select("SELECT sum(nilai) nilai,sum(nilai_lalu) nilai_lalu
                        from(
                        --1 ekuitas_awal
                        select isnull(sum(nilai),0)nilai,0 nilai_lalu from data_ekuitas_lalu($bulan,$thn_ang,$thn_ang_1) WHERE $unit_clause
                        union all
                        --1 ekuitas lalu
                        select 0 nilai, isnull(sum(nilai),0)nilai_lalu from data_real_ekuitas_lalu($bulan,$thn_ang,$thn_ang_1) WHERE $unit_clause
                        )a"))->first();
                $surplus_lo3 = collect(DB::select("SELECT SUM(nilai) as nilai FROM data_surplus_lo3($bulan,$thn_ang) WHERE  $unit_clause"))->first();
                $lain = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                        from(
                            --7 nilai lpe2
                            select isnull(sum(kredit-debet),0) nilai, 0 nilai_lalu
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where $skpd_clause reev='3' and kd_rek6='310101010001' and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan 
                            union all
                            --7 nilai lpe2 lalu
                            select 0 nilai,isnull(sum(kredit-debet),0) nilai_lalu
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where $skpd_clause reev='3' and kd_rek6='310101010001' and year(tgl_voucher)=$thn_ang_1 
                        )a"))->first();
                $ekuitas_tanpa_rkppkd = collect(DB::select("SELECT SUM(nilai) as nilai 
                    FROM data_ekuitas_tanpa_rkppkd($bulan,$thn_ang,$thn_ang_1,'$thn_ang$bulan') 
                    WHERE $unit_clause "))->first();
            $data = [
            'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'ttd_nih'       => $ttd_nih,
            'kd_skpd'       => $kd_skpd,
            'nm_skpd'       => $nm_skpd,
            'lampiran'      => $lampiran,
            'judul'         => $judul,
            'jenis'         => $jenis,
            'tempat_tanggal'=> $tempat_tanggal,
            'tanggal'       => $tanggal,
            'jum_pend'      => $jum_pend,
            'jum_bel'       => $jum_bel,
            'jum_pend_lo'   => $jum_pend_lo,
            'jum_beban_lo'  => $jum_beban_lo,
            'jum_surdef_lo' => $jum_surdef_lo,
            'aset'          => $aset,
            'aset_lancar'   => $aset_lancar,
            'aset_tetap'    => $aset_tetap,
            'aset_lainnya'  => $aset_lainnya,
            'kewajiban'     => $kewajiban,
            'ekuitas_rkppkd'=> $ekuitas_rkppkd,
            'eku_tang'      => $eku_tang,
            'ekuitas_awal'  => $ekuitas_awal,
            'surplus_lo3'   => $surplus_lo3,
            'lain'          => $lain,
            'ekuitas_tanpa_rkppkd' => $ekuitas_tanpa_rkppkd,
            'spasi'         => $spasi,
            'cetak'         => $cetak,
            'thn_ang'       => $thn_ang  
            ];
        
            $view =  view('akuntansi.cetakan.calk.ringkasan_lk')->with($data);
        }
        
        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('calk.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="calk.xls"');
            return $view;
        }
    }

    function cetak_calk4(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $kd_skpd        = $request->kd_skpd;  
        $lampiran       = $request->lampiran;
        $judul          = $request->judul;
        $ttd            = $request->ttd;
        $jenis          = $request->jenis;
        $skpdunit       = $request->skpdunit;
        $cetak          = $request->cetak;
        $tanggal = "29 Desember 2023";
        $tempat_tanggal = "Pontianak, 29 Desember 2023";
        $bulan          = 12;
        $thn_ang        = tahun_anggaran();
        $thn_ang_1        = $thn_ang-1;
        $thn_bln        = "$thn_ang$bulan";

        $spasi = "line-height: 1.5em;";
        if ($skpdunit=="skpd") {
            $nm_skpd = nama_org($kd_skpd);
        }else{
            $nm_skpd = nama_skpd($kd_skpd);
        }
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' and ";
        $unit_clause= "left(kd_unit,len('$kd_skpd'))='$kd_skpd' ";

        $ttd_nih = collect(DB::select("SELECT nama,nip,jabatan,pangkat FROM ms_ttd where $skpd_clause kode IN ('PA','KPA') and nip='$ttd'"))->first();

        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran from trhrka where $skpd_clause status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
        
            //lra
                $jum_pend = collect(DB::select("SELECT SUM(anggaran) as anggaran,SUM(real_spj)as nilai FROM data_realisasi_n_at($bulan,'$jns_ang',$thn_ang) WHERE $skpd_clause left(kd_rek6,1)='4' "))->first();
                $jum_bel = collect(DB::select("SELECT SUM(anggaran) as anggaran,SUM(real_spj)as nilai FROM data_realisasi_n_at($bulan,'$jns_ang',$thn_ang) WHERE $skpd_clause left(kd_rek6,1)='5' "))->first();
            //lo
                $jum_pend_lo = collect(DB::select("SELECT SUM(kredit-debet) as nilai 
                    FROM trdju_calk a inner join trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    WHERE $skpd_clause
                    ( left(kd_rek6,4) in ('7101','7102','7103','7104','7202','7301','7302','7303') or
                      left(kd_rek6,6) in ('720102','720103','720104','720105') or
                      left(kd_rek6,8) in ('72010101','72010102','72010103','72010104')
                    ) 
                    and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan "))->first();
                $jum_beban_lo = collect(DB::select("SELECT SUM(debet-kredit) as nilai 
                    FROM trdju_calk a inner join trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    WHERE $skpd_clause 
                    ( left(kd_rek6,1) in ('8')) 
                    and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan "))->first();
                $jum_surdef_lo = collect(DB::select("SELECT SUM(kredit-debet) as nilai 
                    FROM trdju_calk a inner join trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    WHERE $skpd_clause 
                    ( left(kd_rek6,4) in ('7101','7102','7103','7104','7301','7302','7303','8101','8102','8103','8104','8105','8106','8201','8202','8203','8204','8205','8206','8301','8302','8401') OR
                      left(kd_rek6,5) in ('720102','720102','720104','720105','720202')
                    ) 
                    and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan "))->first();
            //neraca
                $aset = collect(DB::select("SELECT isnull(sum(debet-kredit),0) nilai 
                    from trhju a inner join trdju b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd
                    where $skpd_clause  left(kd_rek6,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan'   AND kd_skpd<>'4.02.02.02'"))->first();
                $aset_lancar = collect(DB::select("SELECT SUM(b.debet-b.kredit) AS nilai 
                    from trhju_calk a inner join trdju_calk b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
                    where $skpd_clause left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and
                                    (kd_rek6 like '1101%' or kd_rek6 like '1102%'  or 
                                    kd_rek6 like '1103%' or kd_rek6 like '1104%'  or 
                                    kd_rek6 like '1105%' or kd_rek6 like '1106%'  or 
                                    kd_rek6 like '1107%' or kd_rek6 like '1108%' or 
                                    kd_rek6 like '1109%' or kd_rek6 like '1110%'or 
                                    kd_rek6 like '1111%' or kd_rek6 like '1112%') "))->first();
                $aset_lancar = collect(DB::select("SELECT SUM(b.debet-kredit) AS nilai 
                    from trhju_calk a inner join trdju_calk b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
                    where $skpd_clause left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and
                                    (kd_rek6 like '13%') "))->first();
                $aset_tetap = collect(DB::select("SELECT SUM(b.debet-kredit) AS nilai 
                    from trhju_calk a inner join trdju_calk b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
                    where $skpd_clause left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and
                                    (kd_rek6 like '150101%' or kd_rek6 like '150102%' or 
                                    kd_rek6 like '1502%' or kd_rek6 like '1503%' or 
                                    kd_rek6 like '1504%' or kd_rek6 like '1505%' or 
                                    kd_rek6 like '1506%') "))->first();
                $kewajiban = collect(DB::select("SELECT SUM(b.kredit-debet) AS nilai 
                    from trhju_calk a inner join trdju_calk b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
                    where $skpd_clause left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and
                                    (kd_rek6 like '2%' ) "))->first();
                $ekuitas_rkppkd = collect(DB::select("SELECT SUM(nilai) as nilai 
                    FROM data_ekuitas_dgn_rkppkd($bulan,$thn_ang,$thn_ang_1,'$thn_ang$bulan') 
                    WHERE $unit_clause "))->first();
                $eku_tang = collect(DB::select("SELECT SUM(nilai) as nilai 
                    FROM data_eku_tang($bulan,$thn_ang,$thn_ang_1,'$thn_ang$bulan') 
                    WHERE $unit_clause "))->first();
                $ekuitas_awal = collect(DB::select("SELECT sum(nilai) nilai,sum(nilai_lalu) nilai_lalu
                        from(
                        --1 ekuitas_awal
                        select isnull(sum(nilai),0)nilai,0 nilai_lalu from data_ekuitas_lalu($bulan,$thn_ang,$thn_ang_1) WHERE $unit_clause
                        union all
                        --1 ekuitas lalu
                        select 0 nilai, isnull(sum(nilai),0)nilai_lalu from data_real_ekuitas_lalu($bulan,$thn_ang,$thn_ang_1) WHERE $unit_clause
                        )a"))->first();
                $surplus_lo3 = collect(DB::select("SELECT SUM(nilai) as nilai FROM data_surplus_lo3($bulan,$thn_ang) WHERE  $unit_clause"))->first();
                $lain = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                        from(
                            --7 nilai lpe2
                            select isnull(sum(kredit-debet),0) nilai, 0 nilai_lalu
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where $skpd_clause reev='3' and kd_rek6='310101010001' and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan 
                            union all
                            --7 nilai lpe2 lalu
                            select 0 nilai,isnull(sum(kredit-debet),0) nilai_lalu
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where $skpd_clause reev='3' and kd_rek6='310101010001' and year(tgl_voucher)=$thn_ang_1 
                        )a"))->first();
                $ekuitas_tanpa_rkppkd = collect(DB::select("SELECT SUM(nilai) as nilai 
                    FROM data_ekuitas_tanpa_rkppkd($bulan,$thn_ang,$thn_ang_1,'$thn_ang$bulan') 
                    WHERE $unit_clause "))->first();
            $data = [
            'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
            'ttd_nih'       => $ttd_nih,
            'kd_skpd'       => $kd_skpd,
            'nm_skpd'       => $nm_skpd,
            'lampiran'      => $lampiran,
            'judul'         => $judul,
            'jenis'         => $jenis,
            'tempat_tanggal'=> $tempat_tanggal,
            'tanggal'       => $tanggal,
            'jum_pend'      => $jum_pend,
            'jum_bel'       => $jum_bel,
            'jum_pend_lo'   => $jum_pend_lo,
            'jum_beban_lo'  => $jum_beban_lo,
            'jum_surdef_lo' => $jum_surdef_lo,
            'aset'          => $aset,
            'aset_lancar'   => $aset_lancar,
            'aset_tetap'    => $aset_tetap,
            'aset_lainnya'  => $aset_lainnya,
            'kewajiban'     => $kewajiban,
            'ekuitas_rkppkd'=> $ekuitas_rkppkd,
            'eku_tang'      => $eku_tang,
            'ekuitas_awal'  => $ekuitas_awal,
            'surplus_lo3'   => $surplus_lo3,
            'lain'          => $lain,
            'ekuitas_tanpa_rkppkd' => $ekuitas_tanpa_rkppkd,
            'spasi'         => $spasi,
            'cetak'         => $cetak,
            'thn_ang'       => $thn_ang  
            ];
        
            $view =  view('akuntansi.cetakan.calk.ringkasan_lk')->with($data);
        
        
        
        if ($cetak == '1') {
            return $view;
        } else if ($cetak == '2') {
            $pdf = PDF::loadHtml($view)->setPaper('legal');
            return $pdf->stream('calk.pdf');
        } else {

            header("Cache-Control: no-cache, no-store, must_revalidate");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachement; filename="calk.xls"');
            return $view;
        }
    }


}
