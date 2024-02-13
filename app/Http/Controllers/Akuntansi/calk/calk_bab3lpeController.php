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


class calk_bab3lpeController extends Controller
{

    public function calkbab3_lpe(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $jns_ang = $request->jns_ang;
        $bulan = $request->bulan;
        $kd_rek = $request->kd_rek;
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
            'jns_ang' => $jns_ang,
            'bulan' => $bulan,
            'kd_rek' => $kd_rek
        ];

        return view('akuntansi.cetakan.calk.bab3.lpe.edit_bab3_lpe')->with($data);
    }

    function cetak_calk16(Request $request)
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
        $tanggal = "31 Desember 2023";
        $tempat_tanggal = "Pontianak, 31 Desember 2023";
        $bulan          = 12;
        $thn_ang        = tahun_anggaran();
        $thn_ang_1        = $thn_ang-1;
        $thn_bln        = "$thn_ang$bulan";
        $angg="nilai";
        
        $trdju = "trdju_pkd";
        $trhju = "trhju_pkd";

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

        $ekuitas_awal = collect(DB::select("SELECT '1' reff, 'Ekuitas Awal' uraian, sum(sal) sal from (
                select isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,1) in ('7','8') and $skpd_clause
                union all
                select isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where kd_rek6='310101010001' and tabel='1' and reev='0' and $skpd_clause
                union all
                select isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where kd_rek6='310101010001' and reev in ('1','2','3') and $skpd_clause
            ) a"))->first();

        $surplus_defisit = DB::select("SELECT * FROM(
                SELECT '2' reff, 'Surplus/ Defisit - LO' uraian, isnull(sum(kredit-debet),0) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,1) in ('7','8') and year(tgl_voucher)='$thn_ang' and $skpd_clause
                union all
                SELECT '2.1' reff, 'Surplus/defisit kegiatan operasional' uraian, isnull(sum(case when YEAR(tgl_voucher)='$thn_ang' then kredit-debet else 0 end),0) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,4) in ('7101','7102','7103','7104','7202','7301','7302','7303','8101','8102','8103','8104','8105','8106','8107','8201','8202','8203','8204','8205','8206','8301','8302','8401') and $skpd_clause
                union all
                select '2.2' reff, 'Surplus/defisit kegiatan Non operasional' uraian, 
                isnull(sum(case when YEAR(tgl_voucher)='$thn_ang' then kredit-debet else 0 end),0) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,4) in ('7401','7402','8501','8502') and $skpd_clause
                union all
                select '2.3' reff, 'Pos Luar Biasa' uraian, 
                isnull(sum(case when YEAR(tgl_voucher)='$thn_ang' then kredit-debet else 0 end),0) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,3) in ('','') and $skpd_clause
            )a order by reff");



        $kode_3 = DB::select("SELECT '3.1' reff, 'Koreksi Nilai Persediaan' uraian, isnull(sum(kredit-debet),0) sal, 'Koreksi kesalahan mendasar dari persediaan yang terjadi pada periode' ket,'31431' det
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='2' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            SELECT '3.2' reff, 'Selisih Revaluasi Aset Tetap' uraian, isnull(sum(kredit-debet),0) sal,'Perubahan nilai aset tetap karena revaluasi aset tetap.' ket , '31432' det
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='1' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            SELECT '3.3' reff, 'Lain - lain' uraian, isnull(sum(kredit-debet),0) sal, 'Transaksi yang mempengaruhi perubahan pada Ekuitas' ket,'31433' det
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and year(tgl_voucher)='$thn_ang' and $skpd_clause");

        $rincian_3 = DB::select("SELECT q.reff,q.urain uraian,isnull(q.sal,0) sal from (
            select '314331'reff,'Penyisihan Piutang' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='1' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '314332'reff,'Koreksi Penyusutan' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='2' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '314333'reff,'Hibah Keluar' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='3' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '314334'reff,'Mutasi Masuk Aset OPD' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='4' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '314335'reff,'Mutasi Keluar Aset OPD' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='5' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '314336'reff,'Penghapusan TPTGR' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='6' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '314337'reff,'Perubahan Kode Rekening' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='7' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '314338'reff,'Koreksi Tanah' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='8' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '314339'reff,'Koreksi Utang Belanja' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='9' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143310'reff,'Reklass  Antar Akun' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='10' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143311'reff,'Tagihan Penjualan Angsuran' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='11' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143312'reff,'Penyertaan Modal' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='12' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143313'reff,'Persediaan APBN yang belum' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='13' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143314'reff,'Aset peralatan dan mesin reklas ke persediaan lain-lain' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='14' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143315'reff,'Koreksi Dana Transfer Pemerintah Pusat' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='15' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143316'reff,'Koreksi Gedung dan Bangunan' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='16' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143317'reff,'Koreksi Persediaan' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='17' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            -- select '3143317'reff,'Koreksi Persediaan' urain,isnull(sum(kredit-debet),0) sal
            -- from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            -- where kd_rek6='310101010001' and reev='2' and tgl_real='' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143318'reff,'Koreksi Kas' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='18' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143319'reff,'Extracompatable' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real in('19','20') and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143320'reff,'Koreksi Peralatan Dan Mesin' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='23' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143321'reff,'Koreksi Jaringan Irigasi Jembatan' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='24' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143322'reff,'Koreksi Aset Tetap Lainya' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='26' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143323'reff,'Koreksi Piutang' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='27' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143324'reff,'Koreksi Aset Lain Lain' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='28' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143325'reff,'Pelimpahan Masuk' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='30' and year(tgl_voucher)='$thn_ang' and $skpd_clause
            union all
            select '3143326'reff,'Pelimpahan Keluar' urain,isnull(sum(kredit-debet),0) sal
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where kd_rek6='310101010001' and reev='3' and tgl_real='31' and year(tgl_voucher)='$thn_ang' and $skpd_clause
        ) q
        order by cast(q.reff as int)");

        $ekuitas_akhir = collect(DB::select("SELECT '4' reff, 'Ekuitas Akhir' uraian, sum(sal) sal from (
                select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,1) in ('7','8') and $skpd_clause
                union all
                select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where kd_rek6='310101010001' and tabel='1' and reev='0' and $skpd_clause
                union all
                select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where kd_rek6='310101010001' and reev in ('1','2','3') and $skpd_clause
            ) a"))->first();


        
        
        
        $data = [
        'header'        => DB::table('config_app')->select('nm_pemda', 'nm_badan', 'logo_pemda_hp')->first(),
        'ttd_nih'       => $ttd_nih,
        'kd_skpd'       => $kd_skpd,
        'kd_skpd_edit'  => $kd_skpd,
        'nm_skpd'       => $nm_skpd,
        'lampiran'      => $lampiran,
        'judul'         => $judul,
        'bulan'         => $bulan,
        'jenis'         => $jenis,
        'skpdunit'      => $skpdunit,
        'tempat_tanggal'=> $tempat_tanggal,
        'tanggal'       => $tanggal,
        'ekuitas_awal'       => $ekuitas_awal,
        'surplus_defisit'       => $surplus_defisit,
        'kode_3'       => $kode_3,
        'rincian_3'       => $rincian_3,
        'ekuitas_akhir'       => $ekuitas_akhir,
        'peraturan'     => $peraturan,
        'peraturan'     => $peraturan,
        'permendagri'   => $permendagri,
        'cetak'         => $cetak,
        'spasi'         => $spasi,
        'jns_ang'       => $jns_ang,
        'nm_jns_ang'    => $nm_jns_ang,
        'trdju'         => $trdju,
        'trhju'         => $trhju,
        'thn_ang'       => $thn_ang ,
        'thn_ang_1'     => $thn_ang_1,
        'skpd_clause'   => $skpd_clause  
        ];
    
        $view =  view('akuntansi.cetakan.calk.bab3.lpe.bab3_lpe_index')->with($data);
        
        
        
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

    public function load_calkbab3_lpe(Request $request)
    {
        $kd_skpd = $request->kd_skpd;  
        $bulan   = $request->bulan;  
        $kd_rek   = $request->kd_rek;
        $thn_ang = tahun_anggaran();
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran,(select nama from tb_status_anggaran where a.jns_ang=kode)nama from trhrka a where $skpd_clause and status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
        
        $data = DB::select("SELECT '$kd_skpd' kd_skpd,(select nm_skpd from ms_skpd where kd_skpd='$kd_skpd')nm_skpd, x.kd_rek, x.nm_rek, isnull(y.ket,'')ket FROM (
                SELECT kd_rek, nm_rek FROM ket_neraca_calk WHERE LEFT(kd_rek,4) IN ('3143')
            ) x 
            LEFT JOIN 
            (
                SELECT kd_rek, ket FROM isi_neraca_calk where kd_skpd='5.02.0.00.0.00.02.0000'
            ) y
            on x.kd_rek=y.kd_rek");
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->kd_rek . '\',\'' . $row->nm_rek . '\',\'' . $row->ket . '\');" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpan_calkbab3_lpe(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $nm_rek             = $request->nm_rek;
        $ket               = $request->ket;
        
        $hasil=collect(DB::select("SELECT  count(*) as jumlah FROM $tabel where kd_skpd='$kd_skpd' and kd_rek='$kd_rek'"))->first();

        $jumlah=$hasil->jumlah; 
        
        if($jumlah>0){
            $asg     = DB::update("UPDATE $tabel SET ket='$ket' where kd_rek='$kd_rek' and kd_skpd='$kd_skpd'");
        } else{
            $asg = DB::insert("INSERT into $tabel (kd_skpd,kd_rek,ket) values ('$kd_skpd','$kd_rek','$ket')");
        }
        
        if ( $asg > 0 ){
           echo '1';
        } else {
           echo '0';
        }
    
    }


}
