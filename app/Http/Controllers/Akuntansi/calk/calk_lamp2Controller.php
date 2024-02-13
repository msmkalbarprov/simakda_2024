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


class calk_lamp2Controller extends Controller
{

    public function calklamp2(Request $request)
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

        return view('akuntansi.cetakan.calk.lamp2.edit_lamp2')->with($data);
    }

    function cetak_calk20(Request $request)
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
        $skpd_clause_d= "left(d.kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
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

        $query = DB::select("SELECT 1 kode, 1301 rek, x.uraian, y.tot_belanja_modal, z.tot_neraca, z.tot_neraca-y.tot_belanja_modal selisih, 0 nilai, '' ket, '' kd_rinci 
            FROM 
            (
                SELECT 1 jns, 'Tanah' uraian
            ) x
            INNER JOIN
            (
                SELECT 1 jns, ISNULL(SUM(debet-kredit),0) tot_belanja_modal 
                FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher 
                WHERE $skpd_clause AND YEAR(a.tgl_voucher)='2023'  AND LEFT(b.kd_rek6,4)='5201'
            ) y ON x.jns=y.jns
            INNER JOIN
            (
                select 1 as jns, isnull(sum(nilai),0) tot_neraca from(select kd_rek6,sum(tahun_n+nilai) nilai, CAST((tahun_n+nilai)/nullif(jumlah,0) as decimal(20,0)) as tot_sat_kap from trdkapitalisasi 
                where $skpd_clause and kd_rek3='1301'
                group by kd_rek6,tahun_n,nilai,jumlah) a
            ) z ON x.jns=z.jns
            UNION ALL
            SELECT 2 kode, 1301 rek, '' uraian, 0 tot_belanja_modal, 0 tot_neraca, 0 selisih,  nilai, ket, kd_rinci 
            FROM isi_analisis_calk 
            WHERE $skpd_clause AND kd_rek='131'

            UNION ALL
            SELECT 1 kode, 1302 rek, x.uraian, y.tot_belanja_modal, z.tot_neraca, z.tot_neraca-y.tot_belanja_modal selisih, 0 nilai, '' ket, '' kd_rinci 
            FROM 
            (
                SELECT 1 jns, 'Peralatan dan Mesin' uraian
            ) x
            INNER JOIN
            (
                SELECT 1 jns, ISNULL(SUM(debet-kredit),0) tot_belanja_modal 
                FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher 
                WHERE $skpd_clause AND YEAR(a.tgl_voucher)='2023'  AND a.tgl_real!='20' AND LEFT(b.kd_rek6,4)='5202'
            ) y ON x.jns=y.jns
            INNER JOIN
            (
                select 1 as jns, isnull(sum(nilai),0) tot_neraca 
                from(select kd_rek6,sum(tahun_n+nilai) nilai, CAST((tahun_n+nilai)/nullif(jumlah,0) as decimal(20,0)) as tot_sat_kap from trdkapitalisasi 
                where $skpd_clause and kd_rek3='1302'
                group by kd_rek6,tahun_n,nilai,jumlah) a where tot_sat_kap>=500000
                            
            ) z ON x.jns=z.jns
            UNION ALL
            SELECT 2 kode, 1302 rek, '' uraian, 0 tot_belanja_modal, 0 tot_neraca, 0 selisih,  nilai, ket, kd_rinci 
            FROM isi_analisis_calk 
            WHERE $skpd_clause AND kd_rek='132'

            UNION ALL
            SELECT 1 kode, 1303 rek, x.uraian, y.tot_belanja_modal, z.tot_neraca, z.tot_neraca-y.tot_belanja_modal selisih, 0 nilai, '' ket, '' kd_rinci 
            FROM 
            (
                SELECT 1 jns, 'Gedung dan Bangunan' uraian
            ) x
            INNER JOIN
            (
                SELECT 1 jns, ISNULL(SUM(debet-kredit),0) tot_belanja_modal 
                FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher 
                WHERE $skpd_clause AND YEAR(a.tgl_voucher)='2023' AND LEFT(b.kd_rek6,4)='5203'
            ) y ON x.jns=y.jns
            INNER JOIN
            (
                select 1 as jns, ISNULL(SUM(tot_neraca),0) as tot_neraca 
                from 
                (
                    select 1 as jns, isnull(sum(nilai),0) tot_neraca 
                    from
                    (
                        select kd_rek6,sum(tahun_n+nilai) nilai, CAST((tahun_n+nilai)/nullif(jumlah,0) as decimal(20,0)) as tot_sat_kap 
                        from trdkapitalisasi 
                        where $skpd_clause and kd_rek3='1303'
                        group by kd_rek6,tahun_n,nilai,jumlah
                    ) a 
                    UNION ALL
                    select 1 as jns, ISNULL(SUM(tahun_n),0)+ISNULL(SUM(nilai),0) as tot_neraca 
                    from trdkapitalisasi 
                    where $skpd_clause and kd_rek3='' and kd_rek6 in ('')
                ) a
            ) z ON x.jns=z.jns
            UNION ALL
            SELECT 2 kode, 1303 rek, '' uraian, 0 tot_belanja_modal, 0 tot_neraca, 0 selisih,  nilai, ket, kd_rinci 
            FROM isi_analisis_calk 
            WHERE $skpd_clause AND kd_rek='133'
                            
            UNION ALL
            SELECT 1 kode, 1304 rek, x.uraian, y.tot_belanja_modal, z.tot_neraca, z.tot_neraca-y.tot_belanja_modal selisih, 0 nilai, '' ket, '' kd_rinci 
            FROM 
            (
                SELECT 1 jns, 'Jalan, Irigasi dan Jaringan' uraian
            ) x
            INNER JOIN
            (
                SELECT 1 jns, ISNULL(SUM(debet-kredit),0) tot_belanja_modal 
                FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher 
                WHERE $skpd_clause AND YEAR(a.tgl_voucher)='2023' AND LEFT(b.kd_rek6,4)='5204'
            ) y ON x.jns=y.jns
            INNER JOIN
            (
                select 1 as jns, ISNULL(SUM(tot_neraca),0) as tot_neraca 
                from 
                (
                    select 1 as jns,  isnull(sum(nilai),0) tot_neraca 
                    from
                    (
                        select kd_rek6,sum(tahun_n+nilai) nilai, CAST((tahun_n+nilai)/nullif(jumlah,0) as decimal(20,0)) as tot_sat_kap from trdkapitalisasi 
                        where $skpd_clause and kd_rek3='1304'
                        group by kd_rek6,tahun_n,nilai,jumlah
                    ) a
                    UNION ALL
                    select 1 as jns, ISNULL(SUM(tahun_n),0)+ISNULL(SUM(nilai),0) as tot_neraca 
                    from trdkapitalisasi 
                    where $skpd_clause and kd_rek3='' and kd_rek6 not in ('')
                ) a
            ) z ON x.jns=z.jns
            UNION ALL
            SELECT 2 kode, 1304 rek, '' uraian, 0 tot_belanja_modal, 0 tot_neraca, 0 selisih,  nilai, ket, kd_rinci 
            FROM isi_analisis_calk WHERE $skpd_clause AND kd_rek='134'
                            
                            
            UNION ALL
            SELECT 1 kode, 1305 rek, x.uraian, y.tot_belanja_modal, z.tot_neraca, z.tot_neraca-y.tot_belanja_modal selisih, 0 nilai, '' ket, '' kd_rinci 
            FROM 
            (
                SELECT 1 jns, 'Aset Tetap Lainnya' uraian
            ) x
            INNER JOIN
            (
                SELECT 1 jns, ISNULL(SUM(debet-kredit),0) tot_belanja_modal 
                FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher 
                WHERE $skpd_clause AND YEAR(a.tgl_voucher)='2023' AND LEFT(b.kd_rek6,6) in('520501','520502','520503','520504','520505','520506','520507','520588','520599','520508')
            ) y ON x.jns=y.jns
            INNER JOIN
            (
                select 1 as jns, isnull(sum(nilai),0) tot_neraca 
                from
                (
                    select kd_rek6,sum(tahun_n+nilai) nilai, CAST((tahun_n+nilai)/nullif(jumlah,0) as decimal(20,0)) as tot_sat_kap from trdkapitalisasi 
                    where $skpd_clause and kd_rek3='1305'
                    group by kd_rek6,tahun_n,nilai,jumlah
                ) a 
            ) z ON x.jns=z.jns
            UNION ALL
            SELECT 2 kode, 1305 rek, '' uraian, 0 tot_belanja_modal, 0 tot_neraca, 0 selisih,  nilai, ket, kd_rinci 
            FROM isi_analisis_calk WHERE $skpd_clause AND kd_rek='135'
                            
            UNION ALL
            SELECT 1 kode, 1306 rek, x.uraian, y.tot_belanja_modal, z.tot_neraca, z.tot_neraca-y.tot_belanja_modal selisih, 0 nilai, '' ket, '' kd_rinci 
            FROM 
            (
                SELECT 1 jns, 'Aset Lainnya' uraian
            ) x
            INNER JOIN
            (
                SELECT 1 jns, ISNULL(SUM(debet-kredit),0) tot_belanja_modal 
                FROM $trhju a INNER JOIN $trdju b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher 
                WHERE $skpd_clause AND YEAR(a.tgl_voucher)='2023' AND LEFT(b.kd_rek6,4)='5206'
            ) y ON x.jns=y.jns
            INNER JOIN
            (
                select 1 as jns,  isnull(sum(nilai),0) tot_neraca 
                from
                (
                    select kd_rek6,sum(tahun_n+nilai) nilai, CAST((tahun_n+nilai)/nullif(jumlah,0) as decimal(20,0)) as tot_sat_kap from trdkapitalisasi 
                    where $skpd_clause and left(kd_rek3,2)='15'
                    group by kd_rek6,tahun_n,nilai,jumlah
                ) a 
            ) z ON x.jns=z.jns
            UNION ALL
            SELECT 2 kode, 1306 rek, '' uraian, 0 tot_belanja_modal, 0 tot_neraca, 0 selisih,  nilai, ket, kd_rinci 
            FROM isi_analisis_calk 
            WHERE $skpd_clause AND kd_rek='136'
            ORDER BY rek, kode, kd_rinci");

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
        'query'       => $query,
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
    
        $view =  view('akuntansi.cetakan.calk.lamp2.lamp2_index')->with($data);
        
        
        
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

    public function load_calklamp2(Request $request)
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
        
        $data = DB::select("SELECT kd_skpd,(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) nm_skpd, kd_rek, ket, nilai, kd_rinci FROM isi_analisis_calk a WHERE $skpd_clause AND LEFT(kd_rek,2)='13'");
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {

            $btn = '<a href="javascript:void(0);" onclick="hapus(\'' . $row->kd_skpd . '\',\'' . $row->nm_skpd . '\',\'' . $row->kd_rek . '\',\'' . $row->kd_rinci . '\',\'' . $row->ket . '\',\'' . $row->nilai . '\');" class="btn btn-danger btn-sm" style="margin-right:4px"><i class="uil-trash"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function load_kd_rinci_calklamp2(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $kd_rek   = $request->kd_rek;
        
        $data = DB::select("SELECT case when kd_rinci = (select kd_rinci FROM isi_analisis_calk WHERE kd_skpd='$kd_skpd' and kd_rek='$kd_rek' and kd_rinci=a.kd_rinci)
                        then concat('9',kd_rinci) else kd_rinci end kd_rinci
            from(
                SELECT COUNT(kd_skpd)+1 kd_rinci FROM isi_analisis_calk WHERE kd_skpd='$kd_skpd' and kd_rek='$kd_rek'
            )a");
        return response()->json($data);
    }

    public function cari_jenis(Request $request)
    {
        $data   = DB::select("SELECT '131' kd_rek, 'Tanah' nm_rek
            union all
            select '132' kd_rek, 'Peralatan dan Mesin' nm_rek
            union all
            select '133' kd_rek, 'Gedung dan Bangunan' nm_rek
            union all
            select '134' kd_rek, 'Jalan dan Irigasi' nm_rek
            union all
            select '135' kd_rek, 'Aset Tetap Lainnya' nm_rek
            union all
            select '136' kd_rek, 'Aset Lainnya' nm_rek
            union all
            select '138' kd_rek, 'Persediaan Lain Lain' nm_rek
            union all
            select '139' kd_rek, 'Aset Tetap' nm_rek");
        return response()->json($data);
    }

    public function cari_uraian(Request $request)
    {
        $jenis = $request->jenis;
        $data   = DB::select("SELECT * from uraian_lamp_2 where left(kd_rek,3)='$jenis'");
        return response()->json($data);
    }

    public function hapus_calklamp2(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $kd_rinci             = $request->kd_rinci;
        $ket               = $request->ket;
        $nilai               = $request->nilai;
        
        
        $query = DB::delete("DELETE from $tabel where kd_skpd='$kd_skpd' and kd_rinci = '$kd_rinci' and kd_rek='$kd_rek' and ket='$ket'");
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

    public function simpan_calklamp2(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_rek             = $request->kd_rek;
        $kd_rinci             = $request->kd_rinci;
        $ket               = $request->ket;
        $nilai               = $request->nilai;
        
        
        $asg = DB::insert("INSERT into $tabel (kd_skpd,kd_rek,ket,nilai,kd_rinci) values ('$kd_skpd','$kd_rek','$ket',$nilai,'$kd_rinci')");
        
        if ( $asg > 0 ){
           echo '1';
        } else {
           echo '0';
        }
    
    }


}
