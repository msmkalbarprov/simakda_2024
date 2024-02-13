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


class calk_bab2Controller extends Controller
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

    public function calkbab2_hambatan(Request $request)
    {
        $kd_skpd = $request->kd_skpd;
        $jns_ang = $request->jns_ang;
        $bulan = $request->bulan;
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
            'bulan' => $bulan
        ];

        return view('akuntansi.cetakan.calk.bab2.edit_hambatan')->with($data);
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
        $tanggal = "31 Desember 2023";
        $tempat_tanggal = "Pontianak, 31 Desember 2023";
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
            )as gabung order by kode, kode2,nomor");
        
        
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

    public function load_calkbab2(Request $request)
    {
        $kd_skpd = $request->kd_skpd;  
        $bulan   = $request->bulan;
        $thn_ang = tahun_anggaran();
        $skpd_clause= "left(kd_skpd,len('$kd_skpd'))='$kd_skpd' ";
        $sql_ang = collect(DB::select("SELECT top 1 jns_ang as anggaran,(select nama from tb_status_anggaran where a.jns_ang=kode)nama from trhrka a where $skpd_clause and status=1 order by tgl_dpa DESC"))->first();
        $jns_ang = $sql_ang->anggaran;
        $data = DB::select("SELECT kode,kode2,bidang,angg_ubah anggaran, real realisasi,selisih,persen,hambatan
                from(select nomor,(keg.kd_skpd)kode,kode kode2,bidang,angg_ubah,real,selisih=[real]-angg_ubah,case when angg_ubah=0  then 0 else ([real]/angg_ubah)*100 end [persen],isnull(e.hambatan,'') [hambatan] 
                from(SELECT 10 [nomor],a.kd_skpd,a.kd_sub_kegiatan as kode,(select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=a.kd_sub_kegiatan) bidang, isnull (sum(nilai),0) angg_ubah,isnull (sum(real_bel),0) [real]
                        from(SELECT a.kd_skpd,kd_sub_kegiatan,a.kd_rek6,sum(a.nilai) as nilai, 0 as real_bel
                                FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 
                                where LEFT(a.kd_rek6,1) ='5'and jns_ang='$jns_ang'
                                GROUP BY a.kd_skpd,kd_sub_kegiatan,a.kd_rek6
                                union all
                                select b.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6, 0 as belanja ,sum(a.debet-a.kredit) as real_bel
                                from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                                where MONTH(tgl_voucher)<=12 and year(b.tgl_voucher)='$thn_ang' and LEFT(kd_rek6,1)='5'
                                group by b.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6
                            )a
                        where  $skpd_clause
                        group by a.kd_skpd,a.kd_sub_kegiatan
                    ) as keg left join calk_babII e on keg.kode=e.kd_program and keg.kd_skpd=e.kd_skpd  
                where angg_ubah>0  )a
                where persen<75 
                order by kode,kode2");
        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            
            $btn = '<a href="javascript:void(0);" onclick="edit(\'' . $row->kode . '\',\'' . $row->kode2 . '\',\'' . $row->bidang . '\',\'' . $row->anggaran . '\',\'' . $row->realisasi . '\',\'' . $row->selisih . '\',\'' . $row->persen . '\',\'' . $row->hambatan . '\');" class="btn btn-warning btn-sm" style="margin-right:4px"><i class="uil-edit"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function simpan_calkbab2_hambatan(Request $request){
        $kd_skpd            = $request->kd_skpd;
        $tabel              = $request->tabel;
        $kd_sub_kegiatan    = $request->kd_sub_kegiatan;
        $hambatan           = $request->hambatan;
        
        $hasil=collect(DB::select("SELECT  count(*) as jumlah FROM $tabel where kd_program='$kd_sub_kegiatan' and kd_skpd='$kd_skpd'"))->first();

        $jumlah=$hasil->jumlah; 
        
        if($jumlah>0){
            $asg     = DB::update("UPDATE $tabel SET hambatan='$hambatan' where kd_program='$kd_sub_kegiatan'and kd_skpd='$kd_skpd'");
        } else{
            $asg = DB::insert("INSERT into $tabel (kd_skpd,kd_program,hambatan) values ('$kd_skpd','$kd_sub_kegiatan', '$hambatan')");
        }
        
        if ( $asg  ){
           echo '1';
        } else {
           echo '0';
        }
    
    }


}
