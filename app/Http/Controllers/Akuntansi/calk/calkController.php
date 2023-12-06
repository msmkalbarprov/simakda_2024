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
            $aset_tetap = collect(DB::select("SELECT SUM(b.debet-kredit) AS nilai 
                from trhju_calk a inner join trdju_calk b on a.no_voucher=b.no_voucher and b.kd_unit=a.kd_skpd 
                where $skpd_clause left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and
                                (kd_rek6 like '13%') "))->first();
            $aset_lainnya = collect(DB::select("SELECT SUM(b.debet-kredit) AS nilai 
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

    function cetak_calk5(Request $request)
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
        
        $map_lra = DB::select("SELECT * from map_lra_calk order by no");
        $data = [
        'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'ttd_nih'       => $ttd_nih,
        'skpd_clause'   => $skpd_clause,
        'kd_skpd'       => $kd_skpd,
        'nm_skpd'       => $nm_skpd,
        'lampiran'      => $lampiran,
        'judul'         => $judul,
        'jenis'         => $jenis,
        'tempat_tanggal'=> $tempat_tanggal,
        'tanggal'       => $tanggal,
        'thn_ang_1'       => $thn_ang_1,
        'map_lra'      => $map_lra,
        'spasi'         => $spasi,
        'cetak'         => $cetak,
        'thn_ang'       => $thn_ang,
        'jns_ang'       => $jns_ang    
        ];
    
        $view =  view('akuntansi.cetakan.calk.i_lra')->with($data);
        
        
        
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

    function cetak_calk6(Request $request)
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
        
        $map_lo = DB::select("SELECT seq,bold, nor, uraian, isnull(kode_1ja,'-') as kode_1ja, isnull(kode,'-') as kode, isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak
                , isnull(kurangi_1,'-') kurangi_1, isnull(kurangi,'-') kurangi, isnull(c_kurangi,0) as c_kurangi
                FROM map_lo_prov_permen_77
                GROUP BY seq,bold, nor, uraian, isnull(kode_1ja,'-'), isnull(kode,'-'), isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet') ,
                isnull(kurangi_1,'-') , isnull(kurangi,'-') , isnull(c_kurangi,0)
                ORDER BY nor");
        $data = [
        'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'ttd_nih'       => $ttd_nih,
        'skpd_clause'   => $skpd_clause,
        'kd_skpd'       => $kd_skpd,
        'nm_skpd'       => $nm_skpd,
        'lampiran'      => $lampiran,
        'judul'         => $judul,
        'jenis'         => $jenis,
        'tempat_tanggal'=> $tempat_tanggal,
        'tanggal'       => $tanggal,
        'thn_ang_1'       => $thn_ang_1,
        'map_lo'      => $map_lo,
        'spasi'         => $spasi,
        'cetak'         => $cetak,
        'thn_ang'       => $thn_ang,
        'bulan'       => $bulan,
        'jns_ang'       => $jns_ang    
        ];
    
        $view =  view('akuntansi.cetakan.calk.ii_lo')->with($data);
        
        
        
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

    function cetak_calk7(Request $request)
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
        $skpd_clausesun    = "where left(kd_unit,len('$kd_skpd'))='$kd_skpd'";

        $ttd_nih = collect(DB::select("SELECT nama,nip,jabatan,pangkat FROM ms_ttd where $skpd_clause kode IN ('PA','KPA') and nip='$ttd'"))->first();

        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran from trhrka where $skpd_clause status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
        
        $ekuitas = collect(DB::select("SELECT sum(nilai)ekuitas from data_ekuitas($bulan,$thn_ang,$thn_ang_1,'$thn_ang$bulan') $skpd_clausesun"))->first();
        $ekuitas_tanpa_rkppkd = collect(DB::select("SELECT sum(nilai)ekuitas_tanpa_rkppkd from data_ekuitas_tanpa_rkppkd($bulan,$thn_ang,$thn_ang_1,'$thn_ang$bulan') $skpd_clausesun"))->first();
        $ekuitas_lalu = collect(DB::select("SELECT sum(nilai)ekuitas_lalu from data_ekuitas_lalu($bulan,$thn_ang,$thn_ang_1) $skpd_clausesun"))->first();
        $map_neraca = DB::select("SELECT kode, uraian, seq,bold, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3,
            isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7,
                isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
                isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15, isnull(kecuali,'xxx') as kecuali
            FROM map_neraca_permen_77 ORDER BY seq");
        $data = [
        'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'ttd_nih'       => $ttd_nih,
        'skpd_clause'   => $skpd_clause,
        'kd_skpd'       => $kd_skpd,
        'nm_skpd'       => $nm_skpd,
        'lampiran'      => $lampiran,
        'judul'         => $judul,
        'jenis'         => $jenis,
        'tempat_tanggal'=> $tempat_tanggal,
        'tanggal'       => $tanggal,
        'thn_ang_1'       => $thn_ang_1,
        'ekuitas'       => $ekuitas,
        'ekuitas_tanpa_rkppkd'       => $ekuitas_tanpa_rkppkd,
        'ekuitas_lalu'       => $ekuitas_lalu,
        'map_neraca'      => $map_neraca,
        'spasi'         => $spasi,
        'cetak'         => $cetak,
        'thn_ang'       => $thn_ang,
        'bulan'       => $bulan,
        'jns_ang'       => $jns_ang    
        ];
    
        $view =  view('akuntansi.cetakan.calk.iii_neraca')->with($data);
        
        
        
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

    function cetak_calk8(Request $request)
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
        $skpd_clause    = "where left(kd_unit,len('$kd_skpd'))='$kd_skpd'";
        $skpd_clauses    = "and left(b.kd_skpd,len('$kd_skpd'))='$kd_skpd'";

        $skpd_clausis= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' and ";

        $ttd_nih = collect(DB::select("SELECT nama,nip,jabatan,pangkat FROM ms_ttd where $skpd_clausis kode IN ('PA','KPA') and nip='$ttd'"))->first();

        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran from trhrka where $skpd_clausis status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
        
        $ekuitas_awal = collect(DB::select("SELECT sum(nilai) nilai,sum(nilai_lalu) nilai_lalu
                        from(
                        --1 ekuitas_awal
                        select isnull(sum(nilai),0)nilai,0 nilai_lalu from data_ekuitas_lalu($bulan,$thn_ang,$thn_ang_1) $skpd_clause
                        union all
                        --1 ekuitas lalu
                        select 0 nilai, isnull(sum(nilai),0)nilai_lalu from data_real_ekuitas_lalu($bulan,$thn_ang,$thn_ang_1) $skpd_clause
                        )a"))->first();
        // dd($ekuitas_awal);
        $surdef = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                        from(
                        --2 surplus lo
                        select sum(nilai_pen-nilai_bel) nilai,0 nilai_lalu
                        from(
                            select sum(kredit-debet) as nilai_pen,0 nilai_bel from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') $skpd_clauses
                            union all
                            select 0 nilai_pen,sum(debet-kredit) as nilai_bel from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') $skpd_clauses
                            )a
                            union all
                            -- 2 surplus lo lalu
                            select 0 nilai,isnull(sum(nilai_pen-nilai_bel),0) nilai_lalu
                            from(
                            select sum(kredit-debet) as nilai_pen,0 nilai_bel
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') $skpd_clauses
                            union all
                            select 0 nilai_pen,sum(debet-kredit) as nilai_bel
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') $skpd_clauses
                            )a
                        )a"))->first();

        $koreksi = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                        from(
                            --5 nilai lpe 1
                            select isnull(sum(kredit-debet),0) nilai , 0 nilai_lalu
                            from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where  reev='2' and kd_rek6='310101010001' and year(b.tgl_voucher)=$thn_ang and month(b.tgl_voucher)<=$bulan $skpd_clauses
                            union all
                            --5 nilai lpe 1 lalu
                            select 0 nilai,isnull(sum(kredit-debet),0) nilai_lalu
                            from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where  reev='2' and kd_rek6='310101010001' and year(b.tgl_voucher)=$thn_ang_1 $skpd_clauses
                        )a"))->first();

        $selisih = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                        from(
                            --6 nilai dr
                            select isnull(sum(kredit-debet),0) nilai, 0 nilai_lalu
                            from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where  reev='1' and kd_rek6='310101010001' and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan
                            union all
                            --6 nilai dr lalu
                            select 0 nilai, isnull(sum(kredit-debet),0) nilai_lalu
                            from trhju a inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit
                            where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1
                        )a"))->first();

        $lain = collect(DB::select("SELECT sum(nilai)nilai,sum(nilai_lalu)nilai_lalu
                        from(
                            --7 nilai lpe2
                            select isnull(sum(kredit-debet),0) nilai, 0 nilai_lalu
                            from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where  reev='3' and kd_rek6='310101010001' and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan $skpd_clauses
                            union all
                            --7 nilai lpe2 lalu
                            select 0 nilai,isnull(sum(kredit-debet),0) nilai_lalu
                            from trdju a inner join trhju b on a.no_voucher=b.no_voucher and b.kd_skpd=a.kd_unit
                            where  reev='3' and kd_rek6='310101010001' and year(tgl_voucher)=$thn_ang_1 $skpd_clauses
                        )a"))->first();
        $data = [
        'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'ttd_nih'       => $ttd_nih,
        'skpd_clause'   => $skpd_clause,
        'kd_skpd'       => $kd_skpd,
        'nm_skpd'       => $nm_skpd,
        'lampiran'      => $lampiran,
        'judul'         => $judul,
        'jenis'         => $jenis,
        'tempat_tanggal'=> $tempat_tanggal,
        'tanggal'       => $tanggal,
        'thn_ang_1'       => $thn_ang_1,
        'ekuitas_awal'      => $ekuitas_awal->nilai,
        'ekuitas_awal_lalu'      => $ekuitas_awal->nilai_lalu,
        'surdef'            => $surdef->nilai,
        'surdef_lalu'            => $surdef->nilai_lalu,
        'koreksi'           => $koreksi->nilai,
        'koreksi_lalu'           => $koreksi->nilai_lalu,
        'selisih'           => $selisih->nilai,
        'selisih_lalu'           => $selisih->nilai_lalu,
        'lain'              => $lain->nilai,
        'lain_lalu'              => $lain->nilai_lalu,
        'spasi'         => $spasi,
        'cetak'         => $cetak,
        'thn_ang'       => $thn_ang,
        'bulan'       => $bulan,
        'jns_ang'       => $jns_ang    
        ];
    
        $view =  view('akuntansi.cetakan.calk.iv_lpe')->with($data);
        
        
        
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

    function cetak_calk9(Request $request)
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
        $peraturan   = "Peraturan Pemerintah Nomor 71 Tahun 2010";
        $permendagri = "Permendagri Nomor 64 Tahun 2013";
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
        'peraturan'      => $peraturan,
        'permendagri'         => $permendagri,
        'cetak'         => $cetak,
        'spasi'         => $spasi,
        'thn_ang'       => $thn_ang  
        ];
    
        $view =  view('akuntansi.cetakan.calk.bab1_pendahuluan')->with($data);
        
        
        
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

    function cetak_calk10(Request $request)
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
        $angg="nilai";
        
        

        $spasi = "line-height: 1.5em;";
        $peraturan   = "Peraturan Pemerintah Nomor 71 Tahun 2010";
        $permendagri = "Permendagri Nomor 64 Tahun 2013";
        if ($skpdunit=="skpd") {
            $nm_skpd = nama_org($kd_skpd);
        }else{
            $nm_skpd = nama_skpd($kd_skpd);
        }
        $kd_org = substr($kd_skpd, 0, 17);
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $skpd_clause_a= "left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $skpd_clause_a= "left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $unit_clause= "left(kd_unit,len('$kd_skpd'))='$kd_skpd' ";
        $unit_clause_a= "left(a.kd_unit,len('$kd_skpd'))='$kd_skpd' ";

        $ttd_nih = collect(DB::select("SELECT nama,nip,jabatan,pangkat FROM ms_ttd where $skpd_clause and kode IN ('PA','KPA') and nip='$ttd'"))->first();

        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran,(select nama from tb_status_anggaran where a.jns_ang=kode)nama from trhrka a where $skpd_clause and status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;

        if($jns_ang=="U1"){
            $nm_jns_ang = 'PERUBAHAN' ;  
            $v = '1';
        }else if($jns_ang=='U2'){
               $nm_jns_ang = 'PERGESERAH PERUBAHAN I' ;
               $v = '2';
        }else{
               $nm_jns_ang = 'PERGESERAN' ;
               $v = '3';
        }
        

        $isinya = DB::select("SELECT nomor,kode,kode2,bidang,angg_ubah,real,(real-angg_ubah)selisih , case when angg_ubah <>0 then real/angg_ubah*100 else 0 end persen,hambatan
            from(
                select 1 [nomor], kd_org [kode], kd_org [kode2], nm_org [bidang],0 [angg_ubah], 0 [real],0 selisih,0 persen,'' [hambatan] from ms_organisasi where kd_org='$kd_org' 
                union all
                select 2 [nomor], kd_skpd [kode], kd_skpd [kode2],nm_skpd [bidang],0 [angg_ubah], 0 [real],0 selisih,0 persen,'' [hambatan] from ms_skpd where $skpd_clause and kd_skpd<>'4.02.02.02'
                union all
                select 3 [nomor], kode, kode2, bidang, sum(angg_ubah)angg_ubah, sum([real]) [real], selisih, persen, hambatan 
                from (select kd_skpd [kode], kd_skpd [kode2],upper(nm_sub_kegiatan)+' '+nm_skpd [bidang],sum(nilai) [angg_ubah],0 [real],0 selisih,0 persen,'' [hambatan]
                      from trdrka 
                      where $skpd_clause and kd_skpd<>'4.02.02.02' and jns_ang='$jns_ang' and left(kd_rek6,1) = '4'
                      group by kd_skpd,nm_skpd,nm_sub_kegiatan
                      union all
                      select kd_skpd [kode], kd_skpd [kode2],upper(nm_sub_kegiatan)+' '+nm_skpd [bidang],0 [angg_ubah],sum(kredit-debet) [real],
                      0 selisih,0 persen,'' [hambatan]
                      from trdju_pkd a inner join trhju_pkd b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                      where $unit_clause_a and  a.kd_unit<>'4.02.02.02' and left(kd_rek6,1) = '4' and YEAR(b.tgl_voucher)=$thn_ang
                      group by kd_skpd,nm_skpd,nm_sub_kegiatan
                     ) x group by kode, kode2, bidang,selisih, persen, hambatan
                union all
                select 4 [nomor], kode , kode2,'BELANJA' [bidang], sum(angg_ubah)[angg_ubah], sum(real) [real],0 selisih,0 persen,'' [hambatan]
                from (select kd_skpd [kode], kd_skpd [kode2],'BELANJA' [bidang],sum(nilai) [angg_ubah], 0 [real],0 selisih,0 persen,'' [hambatan]
                      from trdrka 
                      where $skpd_clause and left(kd_rek6,1) = '5' and jns_ang='$jns_ang'
                      group by kd_skpd
                      union all
                      select kd_skpd [kode], kd_skpd [kode2],'BELANJA' [bidang],0[angg_ubah],sum(debet-kredit) [real],0 selisih,0 persen,'' [hambatan]
                      from trdju_pkd a inner join trhju_pkd b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                      where $unit_clause_a and a.kd_unit<>'4.02.02.02' and left(kd_rek6,1) = '5' and YEAR(b.tgl_voucher)=$thn_ang
                      group by kd_skpd
                     ) x 
                group by kode, kode2, bidang,selisih, persen, hambatan
                union all
                select 5 [nomor], kode, kode2, bidang, sum(angg_ubah)angg_ubah, sum([real]) [real], selisih, persen, hambatan 
                from (select kd_skpd [kode], kd_skpd [kode2],'BELANJA OPERASI' [bidang],sum(nilai) [angg_ubah], 0 [real],0 selisih,0 persen,'' [hambatan]
                      from trdrka 
                      where $skpd_clause and left(kd_rek6,2) = '51' and jns_ang='$jns_ang'
                      group by kd_skpd
                      union all
                      select kd_skpd [kode], kd_skpd [kode2],'BELANJA OPERASI' [bidang],0[angg_ubah],sum(debet-kredit) [real],0 selisih,0 persen,'' [hambatan]
                      from trdju_pkd a inner join trhju_pkd b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                      where $unit_clause_a and a.kd_unit<>'4.02.02.02' and left(kd_rek6,2) = '51' and YEAR(b.tgl_voucher)=$thn_ang
                      group by kd_skpd
                     ) x 
                group by kode, kode2, bidang,selisih, persen, hambatan
                union all
                select 6 [nomor], kode, kode2, bidang, sum(angg_ubah)angg_ubah, sum([real]) [real], selisih, persen, hambatan 
                from (select kd_skpd [kode], kd_skpd [kode2],'BELANJA MODAL' [bidang],sum(nilai) [angg_ubah], 0 [real],0 selisih,0 persen,'' [hambatan]
                      from trdrka 
                      where $skpd_clause and left(kd_rek6,2) = '52' and jns_ang='$jns_ang'
                      group by kd_skpd
                      union all
                      select kd_skpd [kode], kd_skpd [kode2],'BELANJA MODAL' [bidang],0[angg_ubah],sum(debet-kredit) [real],0 selisih,0 persen,'' [hambatan]
                      from trdju_pkd a inner join trhju_pkd b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                      where $unit_clause_a and a.kd_unit<>'4.02.02.02' and left(kd_rek6,2) = '52' and YEAR(b.tgl_voucher)=$thn_ang
                      group by kd_skpd
                     ) x 
                group by kode, kode2, bidang,selisih, persen, hambatan  
                union all
                select 7 [nomor], kode, kode2, bidang, sum(angg_ubah)angg_ubah, sum([real]) [real], selisih, persen, hambatan 
                    from (select kd_skpd [kode], kd_skpd [kode2],'BELANJA TAK TERDUGA' [bidang],sum(nilai) [angg_ubah], 0 [real],0 selisih,0 persen,'' [hambatan]
                          from trdrka 
                          where $skpd_clause and left(kd_rek6,2) = '53' and jns_ang='$jns_ang'
                          group by kd_skpd
                          union all
                          select kd_skpd [kode], kd_skpd [kode2],'BELANJA TAK TERDUGA' [bidang],0[angg_ubah],sum(debet-kredit) [real],0 selisih,0 persen,'' [hambatan]
                          from trdju_pkd a inner join trhju_pkd b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                          where $unit_clause_a and a.kd_unit<>'4.02.02.02' and left(kd_rek6,2) = '53' and YEAR(b.tgl_voucher)=$thn_ang
                          group by kd_skpd
                         ) x group by kode, kode2, bidang,selisih, persen, hambatan                                                       
                union all
                select 8 [nomor], kode, kode2, bidang, sum(angg_ubah)angg_ubah, sum([real]) [real], selisih, persen, hambatan 
                    from (select kd_skpd [kode], kd_skpd [kode2],'BELANJA TRANSFER' [bidang],sum(nilai) [angg_ubah], 0 [real],0 selisih,0 persen,'' [hambatan]
                          from trdrka 
                          where $skpd_clause and left(kd_rek6,2) = '54' and jns_ang='$jns_ang'
                          group by kd_skpd
                          union all
                          select kd_skpd [kode], kd_skpd [kode2],'BELANJA TRANSFER' [bidang],0[angg_ubah],sum(debet-kredit) [real],0 selisih,0 persen,'' [hambatan]
                          from trdju_pkd a inner join trhju_pkd b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                          where $unit_clause_a and a.kd_unit<>'4.02.02.02' and left(kd_rek6,2) = '54' and YEAR(b.tgl_voucher)=$thn_ang
                          group by kd_skpd
                         ) x group by kode, kode2, bidang,selisih, persen, hambatan 
                union all
                select nomor,concat(prog.kd_skpd,'.',kode)kode,kode kode2,bidang,angg_ubah,real,selisih=[real]-angg_ubah,case when angg_ubah=0  then 0 else ([real]/angg_ubah)*100 end [persen],isnull(e.hambatan,'') [hambatan] 
                    from(SELECT 9 [nomor],kd_skpd,p.kd_program as kode,p.nm_program as bidang,isnull(sum(belanja),0)angg_ubah,isnull(sum(real_bel),0)[real] 
                         from(SELECT a.kd_skpd,a.kd_sub_kegiatan, isnull (sum(nilai),0) belanja,isnull (sum(real_bel),0) real_bel
                              from(SELECT a.kd_skpd,kd_sub_kegiatan,a.kd_rek6,sum(a.nilai) as nilai, 0 as real_bel
                                   FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 
                                    where LEFT(a.kd_rek6,1) ='5'and jns_ang='$jns_ang'
                                    GROUP BY a.kd_skpd,kd_sub_kegiatan,a.kd_rek6
                                    union all
                                    select b.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6, 0 as belanja ,sum(a.debet-a.kredit) as real_bel
                                    from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                    where MONTH(tgl_voucher)<=$bulan and year(b.tgl_voucher)='$thn_ang' and $skpd_clause and LEFT(kd_rek6,1)='5'
                                    group by b.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6
                                   )a
                              group by a.kd_skpd,a.kd_sub_kegiatan
                             )a join ms_program p on p.kd_program=LEFT(a.kd_sub_kegiatan,7)
                         where $skpd_clause_a
                         group by a.kd_skpd,p.kd_program,p.nm_program
                        ) as prog left join calk_babII e on prog.kode=e.kd_program 
                    where angg_ubah>0 
                union all
                select nomor,concat(keg.kd_skpd,'.',kode)kode,kode kode2,bidang,angg_ubah,real,selisih=[real]-angg_ubah,case when angg_ubah=0  then 0 else ([real]/angg_ubah)*100 end [persen],isnull(e.hambatan,'') [hambatan] 
                from(SELECT 10 [nomor],a.kd_skpd,a.kd_sub_kegiatan as kode,(select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=a.kd_sub_kegiatan) bidang, isnull (sum(nilai),0) angg_ubah,isnull (sum(real_bel),0) [real]
                        from(SELECT a.kd_skpd,kd_sub_kegiatan,a.kd_rek6,sum(a.nilai) as nilai, 0 as real_bel
                                FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 
                                where LEFT(a.kd_rek6,1) ='5'and jns_ang='$jns_ang'
                                GROUP BY a.kd_skpd,kd_sub_kegiatan,a.kd_rek6
                                union all
                                select b.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6, 0 as belanja ,sum(a.debet-a.kredit) as real_bel
                                from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                where MONTH(tgl_voucher)<=$bulan and year(b.tgl_voucher)='$thn_ang' and LEFT(kd_rek6,1)='5'
                                group by b.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6
                            )a
                        where  $skpd_clause_a
                        group by a.kd_skpd,a.kd_sub_kegiatan
                    ) as keg left join calk_babII e on keg.kode=e.kd_program and keg.kd_skpd=e.kd_skpd  
                where angg_ubah>0  
            )as gabung order by nomor,kode, kode2");
        
        
        $data = [
        'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'ttd_nih'       => $ttd_nih,
        'kd_skpd'       => $kd_skpd,
        'nm_skpd'       => $nm_skpd,
        'lampiran'      => $lampiran,
        'judul'         => $judul,
        'bulan'         => $bulan,
        'jenis'         => $jenis,
        'skpdunit'      => $skpdunit,
        'tempat_tanggal'=> $tempat_tanggal,
        'tanggal'       => $tanggal,
        'peraturan'     => $peraturan,
        'permendagri'   => $permendagri,
        'isinya'        => $isinya,
        'cetak'         => $cetak,
        'spasi'         => $spasi,
        'jns_ang'         => $jns_ang,
        'nm_jns_ang'         => $nm_jns_ang,
        'thn_ang'       => $thn_ang  
        ];
    
        $view =  view('akuntansi.cetakan.calk.bab2_ikhtisar')->with($data);
        
        
        
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
