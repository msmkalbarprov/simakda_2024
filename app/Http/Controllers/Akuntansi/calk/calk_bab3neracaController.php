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


class calk_bab3neracaController extends Controller
{

    public function calkbab3_neraca_edit(Request $request)
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

        return view('akuntansi.cetakan.calk.bab3.neraca.edit_bab3_neraca')->with($data);
    }

    public function calkbab3_neraca_edit_ket(Request $request)
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

        return view('akuntansi.cetakan.calk.bab3.neraca.edit_ket_bab3_neraca')->with($data);
    }

    public function calkbab3_neraca_edit_tambah(Request $request)
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

        return view('akuntansi.cetakan.calk.bab3.neraca.edit_tambah_bab3_neraca')->with($data);
    }

    public function calkbab3_neraca_edit_akum(Request $request)
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

        return view('akuntansi.cetakan.calk.bab3.neraca.edit_akum_bab3_neraca')->with($data);
    }

    function cetak_calk15(Request $request)
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

        $kode_1 = DB::select("SELECT kd_skpd, kd_rek, nm_rek, realisasi, real_tlalu, (real_tlalu-realisasi)kenaikan, 
                    case when realisasi<>0 then real_tlalu/realisasi*100 else 0 end persen, real_lra ,
                    (realisasi-real_lra)banding,(realisasi-real_tlalu)lekur_lo 
            from( 
                SELECT kd_skpd,kd_rek,nm_rek,sum(realisasi)realisasi,sum(real_tlalu)real_tlalu, 0 real_lra 
                from( 
                    SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,1) as kd_rek,(select nm_rek1 from ms_rek1 where kd_rek1=LEFT(kd_rek6,1)) as nm_rek, sum(debet-kredit) as realisasi, 0 real_tlalu , 0 real_lra 
                    from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                    where YEAR(tgl_voucher)<=$thn_ang and LEFT(kd_rek6,1) IN ('1') 
                    group by kd_skpd, LEFT(kd_rek6,1) 
                    union all 
                    SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,1) as kd_rek,(select nm_rek1 from ms_rek1 where kd_rek1=LEFT(kd_rek6,1)) as nm_rek, 0 realisasi, sum(debet-kredit) as real_tlalu , 0 real_lra 
                    from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                    where YEAR(tgl_voucher)<=$thn_ang_1 and LEFT(kd_rek6,1) IN ('1') 
                    group by kd_skpd, LEFT(kd_rek6,1) 
                    
                    union all

                    SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek6,2)) as nm_rek, sum(debet-kredit) as realisasi, 0 real_tlalu , 0 real_lra 
                    from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                    where YEAR(tgl_voucher)<=$thn_ang and LEFT(kd_rek6,1) IN ('1') 
                    group by kd_skpd, LEFT(kd_rek6,2) 
                    union all 
                    SELECT kd_skpd as kd_skpd, LEFT(kd_rek6,2) as kd_rek,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek6,2)) as nm_rek, 0 realisasi, sum(debet-kredit) as real_tlalu , 0 real_lra 
                    from $trdju a inner join $trhju b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher 
                    where YEAR(tgl_voucher)<=$thn_ang_1 and LEFT(kd_rek6,1) IN ('1') 
                    group by kd_skpd, LEFT(kd_rek6,2) 
                )a where $skpd_clause
                group by kd_skpd,kd_rek,nm_rek 
            )a
            order by kd_rek");
        $kode_11 = DB::select("SELECT *
                    from(
                        select 1 urut, '1.1.1' reff, 'Kas di Bendahara Penerimaan' uraian, 
                        isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                        isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(kd_rek6,6)='110102'  and $skpd_clause
                        union all
                        select 1 urut,'1.1.2' reff, 'Kas di Bendahara Pengeluaran' uraian, 
                        isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                        isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(kd_rek6,6)='110103'  and $skpd_clause
                        union all   
                        select 1 urut,'1.1.3' reff, 'Piutang Pendapatan' uraian, 
                        isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                        isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(kd_rek6,4)in('1103','1104','1105','1106','1107','1108','1109')  and $skpd_clause
                        union all
                        select 1 urut,'1.1.4' reff, 'Piutang Lainnya' uraian, 
                        isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                        isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(kd_rek6,4)='1109'  and $skpd_clause
                        union all
                        select 1 urut,'1.1.5' reff, 'Penyisihan Piutang' uraian, 
                        isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                        isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(kd_rek6,4)='1110'  and $skpd_clause
                        union all
                        select 1 urut,'1.1.6' reff, 'Beban Dibayar Dimuka' uraian, 
                        isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                        isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(kd_rek6,6)in('111101','111102')  and $skpd_clause
                        union all
                        select 1 urut,'1.1.7' reff, 'Persediaan' uraian, 
                        isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                        isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(kd_rek6,4)='1112' and $skpd_clause
                        union all
                        select 1 urut,'1.1.8' reff, 'Kas di BLUD' uraian, 
                        isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                        isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(kd_rek6,6)='110104' and $skpd_clause
                        union all
                        select 2 urut,'' reff, 'Jumlah Aset Lancar' uraian, 
                        isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                        isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(kd_rek6,2)='11' and $skpd_clause
                        union all
                        select 1 urut,'1.1' reff, 'ASET LANCAR' uraian, 
                        isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                        isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                        from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                        where LEFT(kd_rek6,2)='11' and $skpd_clause
                    ) a 
                    ORDER BY urut,reff");
        
        $kode_113 = DB::select("SELECT * FROM(SELECT 1 urut,'1.1.3' reff, 'Piutang Pendapatan' uraian, '' kd_rek,'' ketnya,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,4)in('1103','1104','1105','1106','1107','1108','1109') and $skpd_clause
                union all
                SELECT 2 urut,'1' reff, 'Piutang Pajak Daerah' uraian, '1131' kd_rek,'Piutang pajak kendaraan bermotor disajikan berdasarkan Surat Ketetapan Pajak Daerah yang telah ditetapkan oleh Badan Pengelolaan Keuangan dan Pendapatan Daerah yang belum terbayar sampai dengan tanggal 31 Desember $thn_ang.' ketnya,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,4) IN ('1103') and $skpd_clause
                union all
                SELECT 2 urut,'2' reff, 'Piutang Retribusi Daerah' uraian, '1132' kd_rek,'Piutang retribusi dicatat sebesar nilai nominal, yaitu sebesar nilai rupiah piutang yang belum dilunasi sampai dengan tanggal pelaporan.' ketnya,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,4) IN ('1104')  and $skpd_clause
                union all
                SELECT 2 urut,'3' reff, 'Piutang Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan' uraian, '1133' kd_rek,'' ketnya,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,4) IN ('1105')  and $skpd_clause
                union all
                SELECT 2 urut,'4' reff, 'Piutang Lain-lain PAD yang Sah' uraian, '1134' kd_rek,'Piutang Lain-lain PAD yang Sah merupakan Piutang Denda Pajak, denda Retribusi dan Denda Pengembalian.' ketnya,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,4) IN ('1106') and $skpd_clause
                union all
                SELECT 2 urut,'5' reff, 'Piutang Transfer Pemerintah Pusat' uraian, '1135' kd_rek,'' ketnya,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,4) IN ('1107')  and $skpd_clause
                union all
                SELECT 2 urut,'6' reff, 'Piutang Transfer Antar Daerah' uraian, '1135' kd_rek,'' ketnya,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,4) IN ('1108')  and $skpd_clause)a order by urut, reff");

        $kode_114 = DB::select("SELECT * FROM(
                    SELECT 1 urut,'1.1.4' reff, 'Piutang Lainnya' uraian, 
                    isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                    isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where LEFT(kd_rek6,4)='1109' and $skpd_clause
                    union all
                    SELECT 2 urut,'1' reff, 'Angsuran Rumah Dinas' uraian, 
                    isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                    isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,8)='11090301' and $skpd_clause
                    union all
                    select 2 urut,'2' reff, 'Angsuran Kendaraan Dinas' uraian, 
                    isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                    isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,8) in ('11090302') and $skpd_clause
                    union all
                    select 3 urut,'' reff, 'Jumlah Bagian Lancar Tagihan Penjualan Angsuran' uraian, 
                    isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                    isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,4)='1109' and $skpd_clause
                    )a order by urut,reff");

        $kode_115 = DB::select("SELECT * FROM(
            SELECT 1 urut,'1.1.5' reff, 'Penyisihan Piutang' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where LEFT(kd_rek6,4)='1110' and $skpd_clause
            union all
            SELECT 2 urut,'1' reff, 'Penyisihan Piutang Pajak' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where left(kd_rek6,8) IN ('11100101') and $skpd_clause
            union all
            SELECT 2 urut,'2' reff, 'Penyisihan Piutang Retribusi' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where left(kd_rek6,8)='11100102' and $skpd_clause
            union all
            SELECT 2 urut,'3' reff, 'Penyisihan Piutang Lain-lain PAD yang Sah' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where left(kd_rek6,8)='11100104' and $skpd_clause
            )a order by urut,reff");

        $kode_116 = DB::select("SELECT * FROM(
            SELECT 1 urut,'1.1.6' reff, 'Beban Dibayar Dimuka' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where LEFT(kd_rek6,6)in('111101','111102') and $skpd_clause
            union all
            SELECT 2 urut,'1' reff, 'Asuransi dibayar dimuka' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where left(kd_rek6,6)='111101' and $skpd_clause
            union all
            select 2 urut,'2' reff, 'Sewa gedung' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where left(kd_rek6,6)='111102' and $skpd_clause
            union all
            select 3 urut,'' reff, 'Jumlah Beban dibayar dimuka' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where left(kd_rek6,6)in('111101','111102') and $skpd_clause
            )a order by urut,reff");

        $kode_117 = DB::select("SELECT * FROM(
            SELECT 1 urut,'1.1.7' reff, 'Persediaan' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where LEFT(kd_rek6,4)='1112' and $skpd_clause
            union all
            SELECT 2 urut,'1' reff, 'Persediaan Barang Pakai Habis' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where left(kd_rek6,6)='111201' and $skpd_clause
            union all
            select 2 urut,'2' reff, 'Persediaan Barang Tak Habis Pakai' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where left(kd_rek6,6)='111202' and $skpd_clause
            union all
            select 2 urut,'3' reff, 'Persediaan Barang Bekas Dipakai' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where left(kd_rek6,6)='111203' and $skpd_clause
            union all
            select 3 urut,'' reff, 'Jumlah Persediaan' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where left(kd_rek6,4)='1112' and $skpd_clause
            )a order by urut,reff");

        $kode_12 = DB::select("SELECT '1.2' reff, 'INVESTASI JANGKA PANJANG' uraian, 
                    isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                    isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where LEFT(kd_rek6,2)='12' and $skpd_clause");


        $kode_13 = DB::select("SELECT * from(
            SELECT 1 urut,left(kd_rek6,2)kd_rek , (select nm_rek2 from ms_rek2 where kd_rek2=left(kd_rek6,2)) uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where LEFT(kd_rek6,2)='13' and $skpd_clause
            group by left(kd_rek6,2)
            union all
            select 2 urut, kd_rek3 kd_rek, nm_rek3 uraian ,
            isnull((select sum(debet-kredit) from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(kd_rek6,4)=z.kd_rek3 and $skpd_clause and YEAR(tgl_voucher)<=$thn_ang),0) sal,
            isnull((select sum(debet-kredit) from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(kd_rek6,4)=z.kd_rek3 and $skpd_clause and YEAR(tgl_voucher)<$thn_ang),0) sal_lalu
            from ms_rek3 z
            where left(kd_rek3,2)='13'
            union all
            SELECT 3 urut,'' kd_rek , 'Jumlah Aset Tetap' uraian, 
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where LEFT(kd_rek6,2)='13' and $skpd_clause
            )a order by urut,kd_rek");

        $kode_1301 = DB::select("SELECT * 
            FROM(
                SELECT '1' urut, '1' reff, 'Aset Tetap Tanah Per 31 Desember $thn_ang_1' uraian, isnull(sum(debet-kredit),0) sal, '' ket
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                where left(a.kd_rek6,4)='1301' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                union all
                select '2' urut, '2' reff, 'Mutasi Bertambah' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5201' and year(tgl_voucher)=$thn_ang and $skpd_clause
                    union all
                    select isnull(sum(nilai),0) sal 
                    from isi_neraca_calk 
                    where left(kd_rek,4)='1312' and $skpd_clause
                ) a
                UNION ALL
                select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(sal),0) sal,'' as ket 
                from (
                    select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(debet-kredit),0) sal,'13121' as ket
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5201' and year(tgl_voucher)=$thn_ang and $skpd_clause
                ) a
                union all
                select '2.2' as urut,'2.2' reff,  'Hibah' uraian, 0 sal,'13122' as ket
                union all
                select '2.3' as urut,'2.3' reff, 'Beban' uraian, 0 sal,'13123' as ket
                union all
                select '2.4' as urut,'2.4' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'13124' as ket
                union all
                select '2.5' as urut,'2.5' reff,  'Reklas' uraian, 0 sal,'13125' as ket
                union all
                select  '2.6' as urut,'2.6' reff,  'Revaluasi' uraian, 0 sal,'13126' as ket
                union all
                select '2.7' as urut,'2.7' reff,'Koreksi' uraian, 0 sal,'13127' as ket
                union all
                select '2.8' as urut,'2.8' reff,  'Mutasi Nomenklatur' uraian, 0 sal,'13128' as ket
                union all
                select '3' urut, '3' reff, 'Mutasi Berkurang' uraian, isnull(sum(nilai),0) sal, ''ket 
                from isi_neraca_calk 
                where left(kd_rek,4)='1313' and $skpd_clause
                union all
                select '3.1' as urut,'3.1' reff, 'Hibah' uraian, 0 sal,'13131' as ket
                union all
                select '3.2' as urut,'3.2' reff, 'Penghapusan' uraian, 0 sal,'13132' as ket
                union all
                select  '3.3' as urut,'3.3' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'13133' as ket
                union all
                select  '3.4' as urut,'3.4' reff, 'Reklas' uraian, 0 sal,'13134' as ket
                union all
                select '3.5' as urut,'3.5' reff, 'Revaluasi' uraian, 0 sal,'13135' as ket
                union all
                select '3.6' as urut,'3.6' reff, 'Koreksi' uraian, 0 sal,'13136' as ket
                union all
                select  '3.7' as urut,'3.7' reff, 'Rusak Berat' uraian, 0 sal,'13137' as ket
                union all
                select  '3.8' as urut,'3.8' reff, 'Beban' uraian, 0 sal,'13138' as ket
                union all
                select  '3.9' as urut,'3.9' reff, 'Mutasi Nomenklatur' uraian, 0 sal,'13139' as ket
                union all
                select '5' urut, '5' reff, 'Aset Tetap Tanah Per 31 Desember $thn_ang' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='1301' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                    union all
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5201' and year(tgl_voucher)=$thn_ang and $skpd_clause
                    union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1312' and $skpd_clause
                    union all
                    select isnull(sum(nilai*-1),0) sal from isi_neraca_calk where left(kd_rek,4)='1313' and $skpd_clause
                ) a
            )a order by urut");
    
        $kode_1302 = DB::select("SELECT * FROM(
                SELECT '1' urut, '1' reff, 'Aset Tetap Peralatan dan Mesin Per 31 Desember $thn_ang_1' uraian, isnull(sum(debet-kredit),0) sal, '' ket
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                where left(a.kd_rek6,4)='1302' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                union all
                select '2' urut, '2' reff, 'Mutasi Bertambah' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5202' and year(tgl_voucher)=$thn_ang and $skpd_clause
                    union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1322' and $skpd_clause
                ) a

                UNION ALL
                select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(sal),0) sal,'' as ket 
                from (
                    select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(debet-kredit),0) sal,'13221' as ket
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5202' and year(tgl_voucher)=$thn_ang and $skpd_clause
                ) a
                union all
                select '2.2' as urut,'2.2' reff,  'Hibah' uraian, 0 sal,'13222' as ket
                union all
                select '2.3' as urut,'2.3' reff, 'Beban' uraian, 0 sal,'13223' as ket
                union all
                select '2.4' as urut,'2.4' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'13224' as ket
                union all
                select '2.5' as urut,'2.5' reff,  'Reklas' uraian, 0 sal,'13225' as ket
                union all
                select  '2.6' as urut,'2.6' reff,  'Revaluasi' uraian, 0 sal,'13226' as ket
                union all
                select '2.7' as urut,'2.7' reff, 'Koreksi' uraian, 0 sal,'13227' as ket
                union all
                select '2.8' as urut,'2.8' reff,  'Pengadaan dari Belanja Tidak Terduga' uraian, 0 sal,'13228' as ket
                union all
                select '2.9' as urut,'2.9' reff,  'Mutasi Nomenklatur' uraian, 0 sal,'13229' as ket
                union all
                select '3' urut, '3' reff, 'Mutasi Berkurang' uraian, isnull(sum(nilai),0) sal, ''ket 
                from isi_neraca_calk 
                where left(kd_rek,4)='1323' and $skpd_clause
                union all
                select '3.1' as urut,'3.1' reff, 'Hibah' uraian, 0 sal,'13231' as ket
                union all
                select '3.2' as urut,'3.2' reff, 'Penghapusan' uraian, 0 sal,'13232' as ket
                union all
                select  '3.3' as urut,'3.3' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'13233' as ket
                union all
                select  '3.4' as urut,'3.4' reff, 'Reklas' uraian, 0 sal,'13234' as ket
                union all
                select '3.5' as urut,'3.5' reff, 'Revaluasi' uraian, 0 sal,'13235' as ket
                union all
                select '3.6' as urut,'3.6' reff, 'Koreksi' uraian, 0 sal,'13236' as ket
                union all
                select  '3.7' as urut,'3.7' reff, 'Rusak Berat' uraian, 0 sal,'13237' as ket
                union all
                select  '3.8' as urut,'3.8' reff, 'Beban' uraian, 0 sal,'13238' as ket
                union all
                select  '3.9' as urut,'3.9' reff, 'Ekstracomptable' uraian, 0 sal,'13239' as ket
                union all
                select  '3.9.1' as urut,'3.10' reff, 'Mutasi Nomenklatur' uraian, 0 sal,'132310' as ket
                union all
                select '5' urut, '5' reff, 'Aset Tetap Peralatan dan Mesin Per 31 Desember $thn_ang' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='1302' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                    union all
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5202' and year(tgl_voucher)=$thn_ang and $skpd_clause              
                    union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1322' and $skpd_clause
                    union all
                    select isnull(sum(nilai*-1),0) sal from isi_neraca_calk where left(kd_rek,4)='1323' and $skpd_clause
                ) a
            )a
            order by urut");
    
        $kode_1303 = DB::select("SELECT * FROM(
                SELECT '1' urut, '1' reff, 'Aset Tetap Gedung dan Bangunan Per 31 Desember $thn_ang_1' uraian, isnull(sum(debet-kredit),0) sal, '' ket
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                where left(a.kd_rek6,4)='1303' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                union all
                select '2' urut, '2' reff, 'Mutasi Bertambah' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5203' and year(tgl_voucher)=$thn_ang and $skpd_clause
                    union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1332' and $skpd_clause
                ) a
                UNION ALL
                select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(sal),0) sal,'' as ket 
                from (
                    select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(debet-kredit),0) sal,'13321' as ket
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5203' and year(tgl_voucher)=$thn_ang and $skpd_clause
                ) a
                union all
                select '2.2' as urut,'2.2' reff,  'Hibah' uraian, 0 sal,'13322' as ket
                union all
                select '2.3' as urut,'2.3' reff, 'Beban' uraian, 0 sal,'13323' as ket
                union all
                select '2.4' as urut,'2.4' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'13324' as ket
                union all
                select '2.5' as urut,'2.5' reff,  'Reklas' uraian, 0 sal,'13325' as ket
                union all
                select  '2.6' as urut,'2.6' reff,  'Revaluasi' uraian, 0 sal,'13326' as ket
                union all
                select '2.7' as urut,'2.7' reff,'Koreksi' uraian, 0 sal,'13327' as ket
                union all
                select '2.8' as urut,'2.8' reff,  'Pengadaan dari Belanja Tidak Terduga' uraian, 0 sal,'13328' as ket
                union all
                select '2.9' as urut,'2.9' reff,  'Mutasi Nomenklatur' uraian, 0 sal,'13329' as ket
                union all
                select '3' urut, '3' reff, 'Mutasi Berkurang' uraian, isnull(sum(nilai),0) sal, ''ket 
                from isi_neraca_calk 
                where left(kd_rek,4)='1333' and $skpd_clause
                union all
                select '3.1' as urut,'3.1' reff, 'Hibah' uraian, 0 sal,'13331' as ket
                union all
                select '3.2' as urut,'3.2' reff, 'Penghapusan' uraian, 0 sal,'13332' as ket
                union all
                select  '3.3' as urut,'3.3' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'13333' as ket
                union all
                select  '3.4' as urut,'3.4' reff, 'Reklas' uraian, 0 sal,'13334' as ket
                union all
                select '3.5' as urut,'3.5' reff, 'Revaluasi' uraian, 0 sal,'13335' as ket
                union all
                select '3.6' as urut,'3.6' reff, 'Koreksi' uraian, 0 sal,'13336' as ket
                union all
                select  '3.7' as urut,'3.7' reff, 'Rusak Berat' uraian, 0 sal,'13337' as ket
                union all
                select  '3.8' as urut,'3.8' reff, 'Beban' uraian, 0 sal,'13338' as ket
                union all
                select  '3.9' as urut,'3.9' reff, 'Mutasi Nomenklatur' uraian, 0 sal,'13339' as ket
                union all
                select '5' urut, '5' reff, 'Aset Tetap Gedung dan Bangunan Per 31 Desember $thn_ang' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='1303' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                    union all
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5203' and year(tgl_voucher)=$thn_ang and $skpd_clause                  
                    union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1332' and $skpd_clause
                    union all
                    select isnull(sum(nilai*-1),0) sal from isi_neraca_calk where left(kd_rek,4)='1333' and $skpd_clause
                ) a
            )a
            order by urut");
        
        $kode_1304 = DB::select("SELECT * FROM (
                SELECT '1' urut, '1' reff, 'Aset Tetap Jalan, Irigasi dan Jaringan Per 31 Desember $thn_ang_1' uraian, isnull(sum(debet-kredit),0) sal, '' ket
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                where left(a.kd_rek6,4)='1304' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                union all
                select '2' urut, '2' reff, 'Mutasi Bertambah' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5204' and year(tgl_voucher)=$thn_ang and $skpd_clause
                    union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1342' and $skpd_clause
                ) a
                UNION ALL
                select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(sal),0) sal,'' as ket 
                from (
                    select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(debet-kredit),0) sal,'13421' as ket
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5204' and year(tgl_voucher)=$thn_ang and $skpd_clause
                ) a
                union all
                select '2.2' as urut,'2.2' reff,  'Hibah' uraian, 0 sal,'13422' as ket
                union all
                select '2.3' as urut,'2.3' reff, 'Beban' uraian, 0 sal,'13423' as ket
                union all
                select '2.4' as urut,'2.4' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'13424' as ket
                union all
                select '2.5' as urut,'2.5' reff,  'Reklas' uraian, 0 sal,'13425' as ket
                union all
                select  '2.6' as urut,'2.6' reff,  'Revaluasi' uraian, 0 sal,'13426' as ket
                union all
                select '2.7' as urut,'2.7' reff,'Koreksi' uraian, 0 sal,'13427' as ket
                union all
                select '2.8' as urut,'2.8' reff,  'Mutasi Nomenklatur' uraian, 0 sal,'13428' as ket
                union all
                select '3' urut, '3' reff, 'Mutasi Berkurang' uraian, isnull(sum(nilai),0) sal, ''ket 
                from isi_neraca_calk 
                where left(kd_rek,4)='1343' and $skpd_clause
                union all
                select '3.1' as urut,'3.1' reff, 'Hibah' uraian, 0 sal,'13431' as ket
                union all
                select '3.2' as urut,'3.2' reff, 'Penghapusan' uraian, 0 sal,'13432' as ket
                union all
                select  '3.3' as urut,'3.3' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'13433' as ket
                union all
                select  '3.4' as urut,'3.4' reff, 'Reklas' uraian, 0 sal,'13434' as ket
                union all
                select '3.5' as urut,'3.5' reff, 'Revaluasi' uraian, 0 sal,'13435' as ket
                union all
                select '3.6' as urut,'3.6' reff, 'Koreksi' uraian, 0 sal,'13436' as ket
                union all
                select  '3.7' as urut,'3.7' reff, 'Rusak Berat' uraian, 0 sal,'13437' as ket
                union all
                select  '3.8' as urut,'3.8' reff, 'Beban' uraian, 0 sal,'13438' as ket
                union all
                select  '3.9' as urut,'3.9' reff, 'Mutasi Nomenklatur' uraian, 0 sal,'13439' as ket
                union all
                select '5' urut, '5' reff, 'Aset Tetap Tetap Jalan, Irigasi dan Jaringan Per 31 Desember $thn_ang' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='1304' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                    union all
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5204' and year(tgl_voucher)=$thn_ang and $skpd_clause
                    union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1342' and $skpd_clause
                    union all
                    select isnull(sum(nilai*-1),0) sal from isi_neraca_calk where left(kd_rek,4)='1343' and $skpd_clause
                ) a
            )a
            order by urut");
        
        $kode_1305 = DB::select("SELECT * FROM(
                SELECT '1' urut, '1' reff, 'Aset Tetap Lainnya Per 31 Desember $thn_ang_1' uraian, isnull(sum(debet-kredit),0) sal, '' ket
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                where left(a.kd_rek6,4)='1305' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                union all
                select '2' urut, '2' reff, 'Mutasi Bertambah' uraian, 
                isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet),0) sal 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,6)in('520501','520502','520503','520504','520505','520506','520507','520588','520599','520508') 
                    and year(tgl_voucher)=$thn_ang and $skpd_clause
                    union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1352' and $skpd_clause
                ) a
                UNION ALL
                select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(sal),0) sal,'' as ket 
                from (
                    select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(debet),0) sal,'13521' as ket
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,6)in('520501','520502','520503','520504','520505','520506','520507','520588','520599','520508') and year(tgl_voucher)=$thn_ang and $skpd_clause
                ) a
                union all
                select '2.2' as urut,'2.2' reff,  'Hibah' uraian, 0 sal,'13522' as ket
                union all
                select '2.3' as urut,'2.3' reff, 'Beban' uraian, 0 sal,'13523' as ket
                union all
                select '2.4' as urut,'2.4' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'13524' as ket
                union all
                select '2.5' as urut,'2.5' reff,  'Reklas' uraian, 0 sal,'13525' as ket
                union all
                select  '2.6' as urut,'2.6' reff,  'Revaluasi' uraian, 0 sal,'13526' as ket
                union all
                select '2.7' as urut,'2.7' reff,'Koreksi' uraian, 0 sal,'13527' as ket
                union all
                select '2.8' as urut,'2.8' reff,  'Mutasi Nomenklatur' uraian, 0 sal,'13528' as ket
                union all
                select '3' urut, '3' reff, 'Mutasi Berkurang' uraian, isnull(sum(nilai),0) sal, ''ket 
                from isi_neraca_calk 
                where left(kd_rek,4)='1353' and $skpd_clause
                union all
                select '3.1' as urut,'3.1' reff, 'Hibah' uraian, 0 sal,'13531' as ket
                union all
                select '3.2' as urut,'3.2' reff, 'Penghapusan' uraian, 0 sal,'13532' as ket
                union all
                select  '3.3' as urut,'3.3' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'13533' as ket
                union all
                select  '3.4' as urut,'3.4' reff, 'Reklas' uraian, 0 sal,'13534' as ket
                union all
                select '3.5' as urut,'3.5' reff, 'Revaluasi' uraian, 0 sal,'13535' as ket
                union all
                select '3.6' as urut,'3.6' reff, 'Koreksi' uraian, 0 sal,'13536' as ket
                union all
                select  '3.7' as urut,'3.7' reff, 'Rusak Berat' uraian, 0 sal,'13537' as ket
                union all
                select  '3.8' as urut,'3.8' reff, 'Beban' uraian, 0 sal,'13538' as ket
                union all
                select  '3.9' as urut,'3.9' reff, 'Mutasi Nomenklatur' uraian, 0 sal,'13539' as ket
                union all
                select '5' urut, '5' reff, 'Aset Tetap Lainnya Per 31 Desember $thn_ang' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='1305' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                    union all
                    select isnull(sum(debet-kredit),0) sal 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,6)in('520501','520502','520503','520504','520505','520506','520507','520588','520599','520508') and year(tgl_voucher)=$thn_ang and $skpd_clause
                    union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1352' and $skpd_clause
                    union all
                    select isnull(sum(nilai*-1),0) sal from isi_neraca_calk where left(kd_rek,4)='1353' and $skpd_clause
                ) a
            )a
            order by urut");
        
        $kode_1306 = DB::select("SELECT * FROM(
                SELECT '1' urut, '1' reff, 'Aset Tetap Kontruksi Dalam Pengerjaan Per 31 Desember $thn_ang_1' uraian, isnull(sum(debet-kredit),0) sal, '' ket
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                where left(a.kd_rek6,4)='1306' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                union all
                select '2' urut, '2' reff, 'Mutasi Bertambah' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='' and year(tgl_voucher)=$thn_ang and $skpd_clause and b.reev !='3'
                    union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1362' and $skpd_clause
                ) a
                UNION ALL
                select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(sal),0) sal,'' as ket 
                from (
                    select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(debet-kredit),0) sal,'13621' as ket
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='' and year(tgl_voucher)=$thn_ang and $skpd_clause and b.reev !='3'
                ) a
                union all
                select '2.2' as urut,'2.2' reff,  'Hibah' uraian, 0 sal,'13622' as ket
                union all
                select '2.3' as urut,'2.3' reff, 'Beban' uraian, 0 sal,'13623' as ket
                union all
                select '2.4' as urut,'2.4' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'13624' as ket
                union all
                select '2.5' as urut,'2.5' reff,  'Reklas' uraian, 0 sal,'13625' as ket
                union all
                select  '2.6' as urut,'2.6' reff,  'Revaluasi' uraian, 0 sal,'13626' as ket
                union all
                select '2.7' as urut,'2.7' reff,'Koreksi' uraian, 0 sal,'13627' as ket
                union all
                select '2.8' as urut,'2.8' reff,  'Mutasi Nomenklatur' uraian, 0 sal,'13628' as ket
                union all
                select '3' urut, '3' reff, 'Mutasi Berkurang' uraian, 
                isnull(sum(nilai),0) sal, ''ket from isi_neraca_calk where left(kd_rek,4)='1363' and $skpd_clause
                union all
                select '3.1' as urut,'3.1' reff, 'Hibah' uraian, 0 sal,'13631' as ket
                union all
                select '3.2' as urut,'3.2' reff, 'Penghapusan' uraian, 0 sal,'13632' as ket
                union all
                select  '3.3' as urut,'3.3' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'13633' as ket
                union all
                select  '3.4' as urut,'3.4' reff, 'Reklas' uraian, 0 sal,'13634' as ket
                union all
                select '3.5' as urut,'3.5' reff, 'Revaluasi' uraian, 0 sal,'13635' as ket
                union all
                select '3.6' as urut,'3.6' reff, 'Koreksi' uraian, 0 sal,'13636' as ket
                union all
                select  '3.7' as urut,'3.7' reff, 'Rusak Berat' uraian, 0 sal,'13637' as ket
                union all
                select  '3.8' as urut,'3.8' reff, 'Beban' uraian, 0 sal,'13638' as ket
                union all
                select  '3.9' as urut,'3.9' reff, 'Mutasi Nomenklatur' uraian, 0 sal,'13639' as ket
                union all
                select '5' urut, '5' reff, 'Aset Tetap Kontruksi Dalam Pengerjaan Per 31 Desember $thn_ang' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='1306' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                    union all
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd and b.reev !='3'
                    where left(a.kd_rek6,4)='' and year(tgl_voucher)=$thn_ang and $skpd_clause
                    union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1362' and $skpd_clause
                    union all
                    select isnull(sum(nilai*-1),0) sal from isi_neraca_calk where left(kd_rek,4)='1363' and $skpd_clause
                ) a
            )a
            order by urut");
        
        $kode_1307 = DB::select("SELECT * from(
                SELECT 1 urut,left(kd_rek6,4)kd_rek , (select nm_rek3 from ms_rek3 where kd_rek3=left(kd_rek6,4)) uraian,'' ket, 
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,4)='1307' and $skpd_clause
                group by left(kd_rek6,4)
                union all
                select 2 urut, kd_rek4 kd_rek, nm_rek4 uraian ,right(kd_rek4,1) ket,
                isnull((select sum(debet-kredit) from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(kd_rek6,6)=z.kd_rek4 and $skpd_clause and YEAR(tgl_voucher)<=$thn_ang),0) sal,
                isnull((select sum(debet-kredit) from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(kd_rek6,6)=z.kd_rek4 and $skpd_clause and YEAR(tgl_voucher)<$thn_ang),0) sal_lalu
                from ms_rek4 z
                where left(kd_rek3,4)='1307'
            )a order by urut,kd_rek");

        $kode_15 = DB::select("SELECT * FROM(   
                SELECT 1 urut,left(kd_rek6,2)kd_rek , (select nm_rek2 from ms_rek2 where kd_rek2=left(kd_rek6,2)) uraian,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,2)in('15') and $skpd_clause
                group by left(kd_rek6,2)
                union all
                select 2 urut, kd_rek3 kd_rek, nm_rek3 uraian ,
                isnull((select sum(debet-kredit) from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(kd_rek6,4)=z.kd_rek3 and $skpd_clause and YEAR(tgl_voucher)<=$thn_ang),0) sal,
                isnull((select sum(debet-kredit) from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(kd_rek6,4)=z.kd_rek3 and $skpd_clause and YEAR(tgl_voucher)<$thn_ang),0) sal_lalu
                from ms_rek3 z
                where left(kd_rek3,2)in('15')
                union all
                SELECT 3 urut,'' kd_rek , 'Jumlah Aset Lainnya' uraian,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,2)in('15') and $skpd_clause
                group by left(kd_rek6,2)
            )a order by urut,kd_rek");

        $kode_1501 = DB::select("SELECT * FROM(   
                SELECT 1 urut,left(kd_rek6,4)kd_rek , (select nm_rek3 from ms_rek3 where kd_rek3=left(kd_rek6,4)) uraian,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,4)='1501' and $skpd_clause
                group by left(kd_rek6,4)
                union all
                select 2 urut, kd_rek4 kd_rek, nm_rek4 uraian ,
                isnull((select sum(debet-kredit) from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(kd_rek6,6)=z.kd_rek4 and $skpd_clause and YEAR(tgl_voucher)<=$thn_ang),0) sal,
                isnull((select sum(debet-kredit) from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(kd_rek6,6)=z.kd_rek4 and $skpd_clause and YEAR(tgl_voucher)<$thn_ang),0) sal_lalu
                from ms_rek4 z
                where left(kd_rek4,4)in('1501')
            )a order by urut,kd_rek");

        $kode_1502 = DB::select("SELECT * FROM( 
                SELECT 1 urut,left(kd_rek6,4)kd_rek , (select nm_rek3 from ms_rek3 where kd_rek3=left(kd_rek6,4)) uraian, '' noket,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then debet-kredit else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then debet-kredit else 0 end),0) sal_lalu 
                from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,4)='1502' and $skpd_clause
                group by left(kd_rek6,4)
                union all
                select 2 urut, kd_rek6 kd_rek, nm_rek6 uraian , right(kd_rek6,1) noket,
                isnull((select sum(debet-kredit) from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(kd_rek6,12)=z.kd_rek6 and $skpd_clause and YEAR(tgl_voucher)<=$thn_ang),0) sal,
                isnull((select sum(debet-kredit) from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(kd_rek6,12)=z.kd_rek6 and $skpd_clause and YEAR(tgl_voucher)<$thn_ang),0) sal_lalu
                from ms_rek6 z
                where left(kd_rek6,4)in('1502')
            )a order by urut,kd_rek");

        $kode_1503 = DB::select("SELECT * FROM(
                SELECT '1' urut, '1' reff, ' Aset Tidak Berwujud Lainnya Per 31 Desember $thn_ang_1' uraian, isnull(sum(debet-kredit),0) sal, '' ket
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                where left(kd_rek6,4) in ('1503') and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                union all
                select '2' urut, '2' reff, 'Mutasi Bertambah' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal 
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)='5206' and year(tgl_voucher)=$thn_ang and $skpd_clause
                    union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1532' and $skpd_clause
                ) a
                UNION ALL
                select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, isnull(sum(sal),0) sal,'' as ket 
                from (
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(a.kd_rek6,4)in('5206') and year(tgl_voucher)=$thn_ang and $skpd_clause
                ) a
                union all
                select '2.2' as urut,'2.2' reff,  'Hibah' uraian, 0 sal,'15322' as ket
                union all
                select '2.3' as urut,'2.3' reff, 'Beban' uraian, 0 sal,'15323' as ket
                union all
                select '2.4' as urut,'2.4' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'15324' as ket
                union all
                select '2.5' as urut,'2.5' reff,  'Reklas' uraian, 0 sal,'15325' as ket
                union all
                select  '2.6' as urut,'2.6' reff,  'Revaluasi' uraian, 0 sal,'15326' as ket
                union all
                select '2.7' as urut,'2.7' reff,'Koreksi' uraian, 0 sal,'15327' as ket
                union all
                select '2.8' as urut,'2.8' reff,  'Mutasi Nomenklatur' uraian, 0 sal,'15328' as ket
                union all
                select '3' urut, '3' reff, 'Mutasi Berkurang' uraian, isnull(sum(nilai),0) sal, ''ket 
                from isi_neraca_calk 
                where left(kd_rek,4)='1533' and $skpd_clause
                union all
                select '3.1' as urut,'3.1' reff, 'Hibah' uraian, 0 sal,'15331' as ket
                union all
                select '3.2' as urut,'3.2' reff, 'Penghapusan' uraian, 0 sal,'15332' as ket
                union all
                select  '3.3' as urut,'3.3' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'15333' as ket
                union all
                select  '3.4' as urut,'3.4' reff, 'Reklas' uraian, 0 sal,'15334' as ket
                union all
                select '3.5' as urut,'3.5' reff, 'Revaluasi' uraian, 0 sal,'15335' as ket
                union all
                select '3.6' as urut,'3.6' reff, 'Koreksi' uraian, 0 sal,'15336' as ket
                union all
                select  '3.7' as urut,'3.7' reff, 'Rusak Berat' uraian, 0 sal,'15337' as ket
                union all
                select  '3.8' as urut,'3.8' reff, 'Beban' uraian, 0 sal,'15338' as ket
                union all
                select  '3.9' as urut,'3.9' reff, 'Mutasi Nomenklatur' uraian, 0 sal,'15339' as ket
                union all
                select '5' urut, '5' reff, 'Aset Tidak Berwujud Per 31 Desember $thn_ang' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                where left(kd_rek6,4) in ('1503') and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                union all
                select isnull(sum(debet-kredit),0) sal 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                where left(a.kd_rek6,4)in('5206') and year(tgl_voucher)=$thn_ang and $skpd_clause
                union all
                select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1532' and $skpd_clause
                union all
                select isnull(sum(nilai*-1),0) sal from isi_neraca_calk where left(kd_rek,4)='1533' and $skpd_clause
                                    
                ) a
            )a order by urut");

        $kode_1504 = DB::select("SELECT * FROM(
                SELECT '1' urut, '1' reff, ' Aset Lain Lain Per 31 Desember $thn_ang_1' uraian, isnull(sum(debet-kredit),0) sal, '' ket
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                where left(kd_rek6,4) in ('1504') and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                union all
                select '2' urut, '2' reff, 'Mutasi Bertambah' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    -- select isnull(sum(debet-kredit),0) sal 
                    -- from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    -- where left(a.kd_rek6,4)='5207' and year(tgl_voucher)=$thn_ang and $skpd_clause
                    -- union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1542' and $skpd_clause
                ) a
                union all
                select '2.1' as urut, '2.1' reff, 'Realisasi Belanja Modal' uraian, 0 sal,'' as ket 
                union all
                select '2.2' as urut,'2.2' reff,  'Hibah' uraian, 0 sal,'15422' as ket
                union all
                select '2.3' as urut,'2.3' reff, 'Beban' uraian, 0 sal,'15423' as ket
                union all
                select '2.4' as urut,'2.4' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'15424' as ket
                union all
                select '2.5' as urut,'2.5' reff,  'Reklas' uraian, 0 sal,'15425' as ket
                union all
                select  '2.6' as urut,'2.6' reff,  'Revaluasi' uraian, 0 sal,'15426' as ket
                union all
                select '2.7' as urut,'2.7' reff,'Koreksi' uraian, 0 sal,'15427' as ket
                union all
                select '2.8' as urut,'2.8' reff,  'Mutasi Nomenklatur' uraian, 0 sal,'15428' as ket
                union all
                select '3' urut, '3' reff, 'Mutasi Berkurang' uraian, isnull(sum(nilai),0) sal, ''ket 
                from isi_neraca_calk 
                where left(kd_rek,4)='1543' and $skpd_clause
                union all
                select '3.1' as urut,'3.1' reff, 'Hibah' uraian, 0 sal,'15431' as ket
                union all
                select '3.2' as urut,'3.2' reff, 'Penghapusan' uraian, 0 sal,'15432' as ket
                union all
                select  '3.3' as urut,'3.3' reff, 'Mutasi Antar SKPD' uraian, 0 sal,'15433' as ket
                union all
                select  '3.4' as urut,'3.4' reff, 'Reklas' uraian, 0 sal,'15434' as ket
                union all
                select '3.5' as urut,'3.5' reff, 'Revaluasi' uraian, 0 sal,'15435' as ket
                union all
                select '3.6' as urut,'3.6' reff, 'Koreksi' uraian, 0 sal,'15436' as ket
                union all
                select  '3.7' as urut,'3.7' reff, 'Rusak Berat' uraian, 0 sal,'15437' as ket
                union all
                select  '3.8' as urut,'3.8' reff, 'Beban' uraian, 0 sal,'15438' as ket
                union all
                select  '3.9' as urut,'3.9' reff, 'Mutasi Nomenklatur' uraian, 0 sal,'15439' as ket
                union all
                select '5' urut, '5' reff, 'Aset Lain Lain Per 31 Desember $thn_ang' uraian, isnull(sum(sal),0) sal, '' ket 
                from (
                    select isnull(sum(debet-kredit),0) sal from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    where left(kd_rek6,4) in ('1504') and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                    union all
                    -- select isnull(sum(debet-kredit),0) sal 
                    -- from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                    -- where left(a.kd_rek6,4)='5207' and year(tgl_voucher)=$thn_ang and $skpd_clause
                    -- union all
                    select isnull(sum(nilai),0) sal from isi_neraca_calk where left(kd_rek,4)='1542' and $skpd_clause
                    union all
                    select isnull(sum(nilai*-1),0) sal from isi_neraca_calk where left(kd_rek,4)='1543' and $skpd_clause
                                    
                ) a
            )a order by urut");
        

        $kode_1505 = DB::select("SELECT '-' reff, 'Per 31 Desember $thn_ang_1' uraian,'' ket, sum(debet-kredit) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,4)='1505' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                union all
                select '-' reff, 'Koreksi' uraian,'' ket,  isnull(sum(a.nil_m-a.nil_p),0) sal
                from(
                    select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                    from isi_neraca_calk_baru 
                    where $skpd_clause and kd_rek='51' 
                    group by kd_skpd
                    union all
                    select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                    from isi_neraca_calk_baru 
                    where $skpd_clause and kd_rek='52' 
                    group by kd_skpd
                )a
                union all
                select 'a.' reff, 'Koreksi Bertambah' uraian,'' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,2)='51' and $skpd_clause 
                union all
                select '1)' reff, 'Hibah Masuk' uraian,'5111' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='5111' and $skpd_clause 
                union all
                select '2)' reff, 'Mutasi Masuk Antar SKPD' uraian,'5112' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='5112' and $skpd_clause 
                union all
                select '3)' reff, 'Reklas Antar Akun' uraian,'5113' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='5113' and $skpd_clause 
                union all
                select '4)' reff, 'Koreksi' uraian,'5114' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='5114' and $skpd_clause 
                union all
                select 'b.' reff, 'Koreksi Berkurang' uraian,'' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,2)='52' and $skpd_clause 
                union all
                select '1)' reff, 'Hibah Keluar' uraian,'5211' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='5211' and $skpd_clause 
                union all
                select '2)' reff, 'Mutasi Keluar Antar SKPD' uraian,'5212' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='5212' and $skpd_clause 
                union all
                select '3)' reff, 'Reklas Antar Akun ' uraian,'5213' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='5213' and $skpd_clause 
                union all
                select '4)' reff, 'Koreksi ' uraian,'5214' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='5214' and $skpd_clause 
                union all
                select '5)' reff, 'Penghapusan' uraian, '5215' ket, isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='5215' and $skpd_clause 
                union all
                select '6)' reff, 'Rusak Berat' uraian,'5216' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='5216' and $skpd_clause 
                union all
                select '-' reff, 'Setelah Koreksi' uraian,'' ket,  isnull(sum(sal),0) sal 
                from (
                    select sum(debet-kredit) sal
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,4)='1505' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                    union all
                    select isnull(sum(a.nil_m-a.nil_p),0) sal
                    from(
                        select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                        from isi_neraca_calk_baru 
                        where $skpd_clause and kd_rek='51' 
                        group by kd_skpd
                        union all
                        select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                        from isi_neraca_calk_baru 
                        where $skpd_clause and kd_rek='52' 
                        group by kd_skpd
                    )a
                ) a
                union all
                select '-' reff, 'Penyusutan tahun $thn_ang' uraian,'' ket,  isnull(sum(kredit-debet),0) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,6)='810806' and year(tgl_voucher)=$thn_ang and tgl_real in ('',0) and $skpd_clause
                union all
                select reff,uraian,'' ket, sum(sal)sal 
                from(
                    select '-' reff, 'Per 31 Desember $thn_ang' uraian, sum(kredit-debet) sal
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,6)='810806' and year(tgl_voucher)=$thn_ang and tgl_real in ('',0) and $skpd_clause
                    union all
                    select '-' reff, 'Per 31 Desember $thn_ang' uraian, sum(debet-kredit) sal
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,4)='1505' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                    union all
                    select '-' reff, 'Per 31 Desember $thn_ang' uraian,isnull(sum(a.nil_m-a.nil_p),0) sal
                    from(
                        select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                        from isi_neraca_calk_baru 
                        where $skpd_clause and kd_rek='51' 
                        group by kd_skpd
                        union all
                        select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                        from isi_neraca_calk_baru 
                        where $skpd_clause and kd_rek='52' 
                        group by kd_skpd
                    )a
                )a group by reff,uraian");
        
        $kode_1506 = DB::select("SELECT '-' reff, 'Per 31 Desember $thn_ang_1' uraian,'' ket, sum(debet-kredit) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,4)='1506' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                union all
                select '-' reff, 'Koreksi' uraian,'' ket,  isnull(sum(a.nil_m-a.nil_p),0) sal
                from(
                    select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                    from isi_neraca_calk_baru 
                    where $skpd_clause and kd_rek='61' 
                    group by kd_skpd
                    union all
                    select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                    from isi_neraca_calk_baru 
                    where $skpd_clause and kd_rek='62' 
                    group by kd_skpd
                )a
                union all
                select 'a.' reff, 'Koreksi Bertambah' uraian,'' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,2)='61' and $skpd_clause 
                union all
                select '1)' reff, 'Hibah Masuk' uraian,'6111' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='6111' and $skpd_clause 
                union all
                select '2)' reff, 'Mutasi Masuk Antar SKPD' uraian,'6112' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='6112' and $skpd_clause 
                union all
                select '3)' reff, 'Reklas Antar Akun' uraian,'6113' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='6113' and $skpd_clause 
                union all
                select '4)' reff, 'Koreksi' uraian,'6114' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='6114' and $skpd_clause 
                union all
                select 'b.' reff, 'Koreksi Berkurang' uraian,'' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,2)='62' and $skpd_clause 
                union all
                select '1)' reff, 'Hibah Keluar' uraian,'6211' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='6211' and $skpd_clause 
                union all
                select '2)' reff, 'Mutasi Keluar Antar SKPD' uraian,'6212' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='6212' and $skpd_clause 
                union all
                select '3)' reff, 'Reklas Antar Akun ' uraian,'6213' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='6213' and $skpd_clause 
                union all
                select '4)' reff, 'Koreksi ' uraian,'6214' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='6214' and $skpd_clause 
                union all
                select '5)' reff, 'Penghapusan' uraian, '6215' ket, isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='6215' and $skpd_clause 
                union all
                select '6)' reff, 'Rusak Berat' uraian,'6216' ket,  isnull(sum(nilai),0) sal
                from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                where left(a.kd_rek2,4)='6216' and $skpd_clause 
                union all
                select '-' reff, 'Setelah Koreksi' uraian,'' ket,  isnull(sum(sal),0) sal 
                from (
                    select sum(debet-kredit) sal
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,4)='1506' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                    union all
                    select isnull(sum(a.nil_m-a.nil_p),0) sal
                    from(
                        select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                        from isi_neraca_calk_baru 
                        where $skpd_clause and kd_rek='61' 
                        group by kd_skpd
                        union all
                        select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                        from isi_neraca_calk_baru 
                        where $skpd_clause and kd_rek='62' 
                        group by kd_skpd
                    )a
                ) a
                union all
                select '-' reff, 'Penyusutan tahun $thn_ang' uraian,'' ket,  isnull(sum(kredit-debet),0) sal
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,6)='810807' and year(tgl_voucher)=$thn_ang and tgl_real in ('',0) and $skpd_clause
                union all
                select reff,uraian,'' ket, sum(sal)sal 
                from(
                    select '-' reff, 'Per 31 Desember $thn_ang' uraian, sum(kredit-debet) sal
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,6)='810807' and year(tgl_voucher)=$thn_ang and tgl_real in ('',0) and $skpd_clause
                    union all
                    select '-' reff, 'Per 31 Desember $thn_ang' uraian, sum(debet-kredit) sal
                    from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                    where left(kd_rek6,4)='1506' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                    union all
                    select '-' reff, 'Per 31 Desember $thn_ang' uraian,isnull(sum(a.nil_m-a.nil_p),0) sal
                    from(
                        select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                        from isi_neraca_calk_baru 
                        where $skpd_clause and kd_rek='61' 
                        group by kd_skpd
                        union all
                        select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                        from isi_neraca_calk_baru 
                        where $skpd_clause and kd_rek='62' 
                        group by kd_skpd
                    )a
                )a group by reff,uraian");

        $kode_2 = DB::select("SELECT kd_rek,
            case when LEN(kd_rek)=1 then (select nm_rek1 from ms_rek1 where a.kd_rek=kd_rek1)
                 when LEN(kd_rek)=2 then (select nm_rek2 from ms_rek2 where a.kd_rek=kd_rek2)
                 when LEN(kd_rek)=4 then (select nm_rek3 from ms_rek3 where a.kd_rek=kd_rek3)
                 when LEN(kd_rek)=6 then (select nm_rek4 from ms_rek4 where a.kd_rek=kd_rek4)
                 when LEN(kd_rek)=8 then (select nm_rek5 from ms_rek5 where a.kd_rek=kd_rek5)
                 when LEN(kd_rek)=12 then (select nm_rek6 from ms_rek6 where a.kd_rek=kd_rek6) else '' end nm_rek,
            sal, sal_lalu FROM(
                select left(kd_rek6,1) kd_rek, 
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,1)='2' and $skpd_clause
                group by left(kd_rek6,1)
                union all
                select left(kd_rek6,2) kd_rek,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,1)='2' and $skpd_clause
                group by left(kd_rek6,2)
                union all
                select left(kd_rek6,4) kd_rek, 
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,1)='2' and $skpd_clause
                group by left(kd_rek6,4)
                union all
                select left(kd_rek6,6) kd_rek,
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,1)='2' and $skpd_clause
                group by left(kd_rek6,6)
                union all
                select left(kd_rek6,8) kd_rek, 
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,1)='2' and $skpd_clause
                group by left(kd_rek6,8)
                union all
                select left(kd_rek6,12) kd_rek, 
                isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where LEFT(kd_rek6,1)='2' and $skpd_clause
                group by left(kd_rek6,12)
            )a 
            union all
            select '9' kd_rek,'Jumlah Kewajiban' nm_rek ,
            isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
            isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
            from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
            where LEFT(kd_rek6,1)='2' and $skpd_clause
            group by left(kd_rek6,1)
            order by kd_rek");

        $kode_3 = DB::select("SELECT '3' reff, 'EKUITAS' uraian, sum(sal) sal, sum(sal_lalu) sal_lalu from (
                select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,1) in ('7','8') and $skpd_clause
                union all
                select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where kd_rek6='310101010001' and tabel='1' and reev='0' and $skpd_clause
                union all
                select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where kd_rek6='310101010001' and reev in ('1','2','3') and $skpd_clause
                union all
                select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where kd_rek6='310301010001' and $skpd_clause
            ) a");
        
        $tot_akhir = DB::select("SELECT '' reff, 'TOTAL KEWAJIBAN DAN EKUITAS DANA' uraian, sum(sal) sal, sum(sal_lalu) sal_lalu from (
                select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,1) in ('7','8') and $skpd_clause
                union all
                select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where kd_rek6='310101010001' and tabel='1' and reev='0' and $skpd_clause
                union all
                select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where kd_rek6='310101010001' and reev in ('1','2','3') and $skpd_clause
                union all
                select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where kd_rek6='310301010001' and $skpd_clause
                union all
                select isnull(sum(case when YEAR(tgl_voucher)<='$thn_ang' then kredit-debet else 0 end),0) sal,
                isnull(sum(case when YEAR(tgl_voucher)<'$thn_ang' then kredit-debet else 0 end),0) sal_lalu 
                from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                where left(kd_rek6,1) in ('2') and $skpd_clause
            ) a");

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
        'peraturan'     => $peraturan,
        'permendagri'   => $permendagri,
        'kode_1'         => $kode_1,
        'kode_11'         => $kode_11,
        'kode_113'         => $kode_113,
        'kode_114'         => $kode_114,
        'kode_115'         => $kode_115,
        'kode_116'         => $kode_116,
        'kode_117'         => $kode_117,
        'kode_12'         => $kode_12,
        'kode_13'         => $kode_13,
        'kode_1301'         => $kode_1301,
        'kode_1302'         => $kode_1302,
        'kode_1303'         => $kode_1303,
        'kode_1304'         => $kode_1304,
        'kode_1305'         => $kode_1305,
        'kode_1306'         => $kode_1306,
        'kode_1307'         => $kode_1307,
        'kode_15'         => $kode_15,
        'kode_1501'         => $kode_1501,
        'kode_1502'         => $kode_1502,
        'kode_1503'         => $kode_1503,
        'kode_1504'         => $kode_1504,
        'kode_1505'         => $kode_1505,
        'kode_1506'         => $kode_1506,
        'kode_2'         => $kode_2,
        'kode_3'         => $kode_3,
        'tot_akhir'         => $tot_akhir,
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
    
        $view =  view('akuntansi.cetakan.calk.bab3.neraca.bab3_neraca_index')->with($data);
        
        
        
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

    public function load_calkbab3_neraca(Request $request){
        $kd_skpd = $request->kd_skpd;  
        $bulan   = $request->bulan;  
        $kd_rek   = $request->kd_rek;
        $thn_ang = tahun_anggaran();
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $leng_skpd = strlen($kd_skpd);
        if ($leng_skpd=="17") {
            $nm_skpd = nama_org($kd_skpd);
        }else{
            $nm_skpd = nama_skpd($kd_skpd);
        }
        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran,(select nama from tb_status_anggaran where a.jns_ang=kode)nama from trhrka a where $skpd_clause and status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
        if ($kd_rek=="111" || $kd_rek=="112") {
            $data = DB::select("SELECT '$kd_skpd' kd_skpd, '$nm_skpd' nm_skpd,a.kd_rek, a.nm_rek , isnull((SELECT ket from isi_neraca_calk where kd_rek=a.kd_rek and $skpd_clause ),'')uraian, isnull((SELECT isnull(nilai,0)nilai 
                            from isi_neraca_calk where kd_rek=a.kd_rek and $skpd_clause),0) nilai 
                            from ket_det_neraca_calk a
                            where left(a.kd_rek,3)='$kd_rek'");
            return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
                
                $btn = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->kd_rek . '\',\'' . $row->nm_rek . '\',\'' . $row->uraian . '\',\'' . $row->nilai . '\');" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
                return $btn;
            })->rawColumns(['aksi'])->make(true);
        }else{

        }
    }

    public function simpan_calkbab3_neraca(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $nm_rek             = $request->nm_rek;
        $uraian             = $request->uraian;
        $nilai               = $request->nilai;
        
        $hasil=collect(DB::select("SELECT  count(*) as jumlah FROM $tabel where kd_skpd='$kd_skpd' and kd_rek='$kd_rek'"))->first();

        $jumlah=$hasil->jumlah; 
        
        if($jumlah>0){
            $asg     = DB::update("UPDATE $tabel SET nilai='$nilai', ket='$uraian' where kd_rek='$kd_rek' and kd_skpd='$kd_skpd'");
        } else{
            $asg = DB::insert("INSERT into $tabel (kd_skpd,kd_rek,ket,nilai,kd_rinci) values ('$kd_skpd','$kd_rek', '$uraian',$nilai, '$kd_rek')");
        }
        
        if ( $asg > 0 ){
           echo '1';
        } else {
           echo '0';
        }
    }

    public function load_calkbab3_neraca_ket(Request $request){
        $kd_skpd = $request->kd_skpd;  
        $bulan   = $request->bulan;  
        $kd_rek   = $request->kd_rek;
        $thn_ang = tahun_anggaran();
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $leng_skpd = strlen($kd_skpd);
        if ($leng_skpd=="17") {
            $nm_skpd = nama_org($kd_skpd);
        }else{
            $nm_skpd = nama_skpd($kd_skpd);
        }
        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran,(select nama from tb_status_anggaran where a.jns_ang=kode)nama from trhrka a where $skpd_clause and status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
        if($kd_rek=="1502"){
            $data = DB::select("SELECT '$kd_skpd' kd_skpd, '$nm_skpd' nm_skpd,kd_rek, nm_rek,
                isnull((SELECT REPLACE(convert(varchar(max),ket),'\n',' ')ket FROM isi_neraca_calk where kd_rek=z.kd_rek and kd_skpd='$kd_skpd'),'')ket 
                FROM ket_neraca_calk z WHERE left(kd_rek,4)='$kd_rek'");
        }else{
            $data = DB::select("SELECT '$kd_skpd' kd_skpd, '$nm_skpd' nm_skpd,kd_rek, nm_rek,
                isnull((SELECT REPLACE(convert(varchar(max),ket),'\n',' ')ket FROM isi_neraca_calk where kd_rek='$kd_rek' and kd_skpd='$kd_skpd'),'')ket 
                FROM ket_neraca_calk WHERE kd_rek='$kd_rek'");
        }
            // dd($data);
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->kd_rek . '\',\'' . $row->nm_rek . '\',\'' . $row->ket . '\');" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpan_calkbab3_neraca_ket(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $nm_rek             = $request->nm_rek;
        $ket               = $request->ket;
        
        $hasil=collect(DB::select("SELECT  count(*) as jumlah FROM $tabel where kd_skpd='$kd_skpd' and kd_rek='$kd_rek'"))->first();

        $jumlah=$hasil->jumlah; 
        
        if($jumlah>0){
            $asg     = DB::update("UPDATE $tabel SET ket='$ket', nm_rek='$nm_rek' where kd_rek='$kd_rek' and kd_skpd='$kd_skpd'");
        } else{
            $asg = DB::insert("INSERT into $tabel (kd_skpd,kd_rek,ket) values ('$kd_skpd','$kd_rek', '$ket')");
        }
        
        if ( $asg > 0 ){
           echo '1';
        } else {
           echo '0';
        }
    }

    public function load_calkbab3_neraca_edit_tambah(Request $request)
    {
        $kd_skpd = $request->kd_skpd;  
        $bulan   = $request->bulan;  
        $kd_rek   = $request->kd_rek;
        $thn_ang = tahun_anggaran();
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $leng_skpd = strlen($kd_skpd);
        if ($leng_skpd=="17") {
            $nm_skpd = nama_org($kd_skpd);
        }else{
            $nm_skpd = nama_skpd($kd_skpd);
        }
        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran,(select nama from tb_status_anggaran where a.jns_ang=kode)nama from trhrka a where $skpd_clause and status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
            
            $data = DB::select("SELECT a.*,(select nm_rek from ket_neraca_calk where a.kd_rek=kd_rek)nm_rek,
                    case when len(kd_skpd)=17 then (select nm_org from ms_organisasi where a.kd_skpd=kd_org) 
                        else (select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd) end nm_skpd  
                    from isi_neraca_calk a where $skpd_clause and left(kd_rek,3)='$kd_rek' and nilai is not null");
            // dd($data);
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="hapus(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->kd_rek . '\',\'' . $row->nm_rek . '\',\'' . $row->ket . '\',\'' . $row->nilai . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpan_calkbab3_neraca_edit_tambah(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $ket               = $request->ket;
        $nilai               = $request->nilai;
        
        $hasil=collect(DB::select("SELECT  count(*) as jumlah FROM $tabel where kd_skpd='$kd_skpd' and kd_rek='$kd_rek'"))->first();

        $jumlah=$hasil->jumlah; 
        
        
            $asg = DB::insert("INSERT into $tabel (kd_skpd,kd_rek,ket,nilai) values ('$kd_skpd','$kd_rek', '$ket', '$nilai')");
        
        if ( $asg > 0 ){
           echo '1';
        } else {
           echo '0';
        }
    }

    public function hapus_calkbab3_neraca_edit_tambah(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $ket               = $request->ket;
        $nilai               = $request->nilai;
        
        $hasil=collect(DB::select("SELECT  count(*) as jumlah FROM $tabel where kd_skpd='$kd_skpd' and kd_rek='$kd_rek'"))->first();

        $jumlah=$hasil->jumlah; 
        
        
            $asg = DB::insert("INSERT into $tabel (kd_skpd,kd_rek,ket,nilai) values ('$kd_skpd','$kd_rek', '$ket', '$nilai')");
        
        $query = DB::delete("DELETE from $tabel where kd_skpd='$kd_skpd' and ket = '$ket' and kd_rek='$kd_rek' and nilai= $nilai");
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

    function cari_rek_akum_calk(Request $request)
    {
        $kd_rek = $request->rek1;
        // dd($kd_rek);
        $data   = DB::select("SELECT * from rek_neraca_calk_baru where left(kd_rek,1)='$kd_rek' order by kd_rek");
        return response()->json($data);
    }

    function cari_rek2_akum_calk(Request $request)
    {
        $kd_rek = $request->rek;
        $data   = DB::select("SELECT * from rek2_neraca_calk_baru where left(kd_rek2,2)='$kd_rek' order by kd_rek2");
        return response()->json($data);
    }

    public function load_calkbab3_neraca_edit_akum(Request $request)
    {
        $kd_skpd = $request->kd_skpd;  
        $bulan   = $request->bulan;  
        $kd_rek   = $request->kd_rek;
        $thn_ang = tahun_anggaran();
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $leng_skpd = strlen($kd_skpd);
        if ($leng_skpd=="17") {
            $nm_skpd = nama_org($kd_skpd);
        }else{
            $nm_skpd = nama_skpd($kd_skpd);
        }
        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran,(select nama from tb_status_anggaran where a.jns_ang=kode)nama from trhrka a where $skpd_clause and status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
            
            $data = DB::select("SELECT a.*,case when len(kd_skpd)=17 then (select nm_org from ms_organisasi where a.kd_skpd=kd_org) 
                        else (select nm_skpd from ms_skpd where a.kd_skpd=kd_skpd) end nm_skpd  
                    from isi_neraca_calk_baru a where $skpd_clause and left(kd_rek2,1)='$kd_rek' and nilai is not null");
            // dd($data);
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="hapus(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->kd_rek . '\',\'' . $row->nm_rek . '\',\'' . $row->kd_rek2 . '\',\'' . $row->nm_rek2 . '\',\'' . $row->ket . '\',\'' . $row->nilai . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpan_calkbab3_neraca_edit_akum(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $nm_rek             = nama_rek1_akum_calk($kd_rek);
        $kd_rek2             = $request->kd_rek2;
        $nm_rek2             = nama_rek2_akum_calk($kd_rek2);
        $ket               = $request->ket;
        $nilai               = $request->nilai;
        
        $hasil=collect(DB::select("SELECT  count(*) as jumlah FROM $tabel where kd_skpd='$kd_skpd' and kd_rek='$kd_rek'"))->first();

        $jumlah=$hasil->jumlah; 
        
        
            $asg = DB::insert("INSERT into $tabel (kd_skpd,kd_rek,nm_rek,nilai,kd_rek2,nm_rek2,ket) values ('$kd_skpd','$kd_rek','$nm_rek', '$nilai','$kd_rek2','$nm_rek2', '$ket')");
        
        if ( $asg > 0 ){
           echo '1';
        } else {
           echo '0';
        }
    }

    public function hapus_calkbab3_neraca_edit_akum(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $kd_rek2             = $request->kd_rek2;
        $ket               = $request->ket;
        $nilai               = $request->nilai;
        
        
        $query = DB::delete("DELETE from $tabel where kd_skpd='$kd_skpd' and ket = '$ket' and kd_rek='$kd_rek' and kd_rek2='$kd_rek2' and nilai= $nilai");
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


}
