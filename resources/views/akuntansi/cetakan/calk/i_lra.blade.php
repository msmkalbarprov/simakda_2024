<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - I.LRA</title>
    <style>
        table {
            border-collapse: collapse
        }

        .t1 {
            font-weight: normal
        }

        #rincian>tbody>tr>td {
            vertical-align: top
        }

        .kanan {
            border-right: 1px solid black
        }

        .kiri {
            border-left: 1px solid black
        }

        .bawah {
            border-bottom: 1px solid black
        }
    </style>
</head>

<body>
{{-- <body> --}}
    {{-- isi --}}
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="1" cellpadding="1" align=center>
        <TR>
            <TD align="center" ><b>I. LAPORAN REALISASI ANGGARAN (LRA)</b></TD>
        </TR>
    </TABLE><br/>
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        
    </TABLE><br/>
    <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="4" align=center> 
        <tr>
            <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
        </tr>
        <tr>
            <td align="center"><strong>{{$nm_skpd}}</strong></td>                         
        </tr>                   
        <tr>
            <td align="center"><strong>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA DAERAH</strong></td>
        </tr>                    
        <tr>
            <td align="center"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN {{$tanggal}} {{$thn_ang}} DAN {{$thn_ang_1}}</strong></td>
        </tr>
        <tr>
            <td align="center">&nbsp;</td>
        </tr>
        <tr>
            <td align="right">(dalam rupiah)</td>
        </tr>
    </TABLE>
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <thead>                       
            <tr>                           
                <td  bgcolor="#CCCCCC" width="45%" align="center"><b>URAIAN</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>ANGGARAN {{$thn_ang}}</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>REALISASI {{$thn_ang}}</b></td>
                <td  bgcolor="#CCCCCC" width="10%" align="center" ><b>%</b></td>   
                <td  bgcolor="#CCCCCC" width="10%" align="center" ><b>REALISASI {{$thn_ang_1}}</b></td>   
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>                           
            </tr>
        </tfoot>
        @foreach($map_lra as $row)
            @php
                $no=$row->no;
                $seq = $row->seq;
                $bold = $row->bold;
                $nama = $row->nama;
                $kode1 = $row->kode1;
                $kode2 = $row->kode2;
                $kode3 = $row->kode3;
                $kode4 = $row->kode4;
                $kode5 = $row->kode5;
                $kode6 = $row->kode6;
                $cetak = $row->cetak;
                $kurangi1 = $row->kurangi1;
                $kurangi2 = $row->kurangi2;
                $kurangi3 = $row->kurangi3;
                $kurangi4 = $row->kurangi4;
                $kurangi5 = $row->kurangi5;
                $kurangi6 = $row->kurangi6;
                $c_kurangi = $row->c_kurangi;
                
                if($cetak==''){
                    $cetak="debet-debet";
                }
                if($kode1==''){
                    $kode1="'X'";
                }
                if($kode2==''){
                    $kode2="'XX'";
                }
                if($kode3==''){
                    $kode3="'XXXx'";
                }
                if($kode4==''){
                    $kode4="'XXXXXx'";
                }
                if($kode5==''){
                    $kode5="'XXXXXXXX'";
                }
                if($kode6==''){
                    $kode6="'XXXXXXXXxxxx'";
                }
                if($c_kurangi==''){
                    $c_kurangi="debet-debet";
                }
                if($kurangi1==''){
                    $kurangi1="'X'";
                }
                if($kurangi2==''){
                    $kurangi2="'XX'";
                }
                if($kurangi3==''){
                    $kurangi3="'XXXx'";
                }
                if($kurangi4==''){
                    $kurangi4="'XXXXXx'";
                }
                if($kurangi5==''){
                    $kurangi5="'XXXXXXXX'";
                }
                if($kurangi6==''){
                    $kurangi6="'XXXXXXXXxxxx'";
                }
                $nilai = collect(DB::select("SELECT sum(ang_plus-ang_min) anggaran,sum(real_plus-real_min)realisasi
                from(SELECT SUM(anggaran) as ang_plus, SUM($cetak) as real_plus,0 ang_min,0 real_min
                FROM (select kd_skpd,kd_rek6,sum(anggaran)anggaran,sum(debet)debet,sum(kredit)kredit
                from( 
                select kd_skpd,kd_rek6,sum(nilai) anggaran, 0 debet, 0 kredit 
                from trdrka 
                where left(kd_rek6,1)in ('4','5','6') and jns_ang='$jns_ang'
                group by kd_skpd,kd_rek6
                union all
                select kd_skpd,kd_rek6, 0 anggaran, sum(debet) debet, sum(kredit) kredit
                from trdju_pkd a inner join trhju_pkd b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where left(kd_rek6,1)in ('4','5','6') and MONTH(tgl_voucher)<=12 and YEAR(tgl_voucher)=2023
                group by kd_skpd,kd_rek6)a
                group by kd_skpd,kd_rek6
                )a
                WHERE $skpd_clause (
                    LEFT(kd_rek6,1) IN ($kode1) or 
                    LEFT(kd_rek6,2) IN ($kode2) or 
                    LEFT(kd_rek6,4) IN ($kode3) or 
                    LEFT(kd_rek6,6) IN ($kode4) or  
                    LEFT(kd_rek6,8) IN ($kode5) or  
                    LEFT(kd_rek6,12) IN ($kode6)
                ) 
                union all
                SELECT 0 ang_plus,0 real_plus, SUM(anggaran) as ang_plus, SUM($c_kurangi) as real_plus
                FROM (select kd_skpd,kd_rek6,sum(anggaran)anggaran,sum(debet)debet,sum(kredit)kredit
                from( 
                select kd_skpd,kd_rek6,sum(nilai) anggaran, 0 debet, 0 kredit 
                from trdrka 
                where left(kd_rek6,1)in ('4','5','6') and jns_ang='u1'
                group by kd_skpd,kd_rek6
                union all
                select kd_skpd,kd_rek6, 0 anggaran, sum(debet) debet, sum(kredit) kredit
                from trdju_pkd a inner join trhju_pkd b on a.kd_unit=b.kd_skpd and a.no_voucher=b.no_voucher
                where left(kd_rek6,1)in ('4','5','6') and MONTH(tgl_voucher)<=12 and YEAR(tgl_voucher)=$thn_ang
                group by kd_skpd,kd_rek6)a
                group by kd_skpd,kd_rek6
                )a
                WHERE $skpd_clause (
                    LEFT(kd_rek6,1) IN ($kurangi1) or 
                    LEFT(kd_rek6,2) IN ($kurangi2) or 
                    LEFT(kd_rek6,4) IN ($kurangi3) or 
                    LEFT(kd_rek6,6) IN ($kurangi4) or  
                    LEFT(kd_rek6,8) IN ($kurangi5) or  
                    LEFT(kd_rek6,12) IN ($kurangi6)
                ) )a"))->first();
                $anggaran = $nilai->anggaran;
                $realisasi = $nilai->realisasi;

                if(($anggaran==0) || ($anggaran=='')){
                    $persen=0;
                } else{
                    $persen = $realisasi/$anggaran*100;
                }
                if ($anggaran < 0){
                    $aa="("; 
                    $anggaran=$anggaran*-1; 
                    $ba=")";}
                else {
                    $aa=""; 
                    $anggaran=$anggaran; 
                    $ba="";
                }
                if ($realisasi < 0){
                    $ar="("; 
                    $realisasi=$realisasi*-1; 
                    $br=")";}
                else {
                    $ar=""; 
                    $realisasi=$realisasi; 
                    $br="";
                }
                if ($persen < 0){
                    $ap="("; 
                    $persen=$persen*-1; 
                    $bp=")";}
                else {
                    $ap=""; 
                    $persen=$persen; 
                    $bp="";
                }
                $nilai_lalu = collect(DB::select("SELECT sum(real_plus-real_min)realisasi_lalu
                from(select sum($cetak) real_plus,0 real_min
                from(select b.kd_skpd,  a.kd_rek6, sum(debet)debet,sum(kredit) kredit
                from simakda_2022.dbo.trdju_calk a inner join simakda_2022.dbo.trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                where year(tgl_voucher)=2022 and left(a.kd_rek6,1) in ('4','5','6') 
                group by kd_skpd, a.kd_rek6)a
                WHERE $skpd_clause (
                    LEFT(kd_rek6,1) IN ($kode1) or 
                    LEFT(kd_rek6,2) IN ($kode2) or 
                    LEFT(kd_rek6,4) IN ($kode3) or 
                    LEFT(kd_rek6,6) IN ($kode4) or  
                    LEFT(kd_rek6,8) IN ($kode5) or  
                    LEFT(kd_rek6,12) IN ($kode6)
                )
                union all
                select 0 real_plus,sum($c_kurangi) real_min
                from(select b.kd_skpd,  a.kd_rek6, sum(debet)debet,sum(kredit) kredit
                from simakda_2022.dbo.trdju_calk a inner join simakda_2022.dbo.trhju_calk b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
                where year(tgl_voucher)=2022 and left(a.kd_rek6,1) in ('4','5','6') 
                group by kd_skpd, a.kd_rek6)a
                WHERE $skpd_clause (
                    LEFT(kd_rek6,1) IN ($kurangi1) or 
                    LEFT(kd_rek6,2) IN ($kurangi2) or 
                    LEFT(kd_rek6,4) IN ($kurangi3) or 
                    LEFT(kd_rek6,6) IN ($kurangi4) or  
                    LEFT(kd_rek6,8) IN ($kurangi5) or  
                    LEFT(kd_rek6,12) IN ($kurangi6)
                ))a"))->first();
                $real_lalu=$nilai_lalu->realisasi_lalu;
                if ($real_lalu < 0){
                    $arl="("; 
                    $real_lalu=$real_lalu*-1; 
                    $brl=")";}
                else {
                    $arl=""; 
                    $real_lalu=$real_lalu; 
                    $brl="";
                }

            @endphp
            @if($bold=='1')
                <tr>          
                    <td align="left"  valign="top"><b>{{$nama}}</b></td> 
                    <td align="right" valign="top"><b>{{$aa}}{{rupiah($anggaran)}}{{$ba}}</b></td> 
                    <td align="right" valign="top"><b>{{$ar}}{{rupiah($realisasi)}}{{$br}}</b></td> 
                    <td align="right" valign="top"><b>{{$ap}}{{rupiah($persen)}}{{$bp}}</b></td>
                    <td align="right" valign="top"><b>{{$arl}}{{rupiah($real_lalu)}}{{$brl}}</b></td>  
                </tr>
            @elseif($bold=='2')
                <tr>          
                    <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</b></td> 
                    <td align="right" valign="top"><b>{{$aa}}{{rupiah($anggaran)}}{{$ba}}</b></td> 
                    <td align="right" valign="top"><b>{{$ar}}{{rupiah($realisasi)}}{{$br}}</b></td> 
                    <td align="right" valign="top"><b>{{$ap}}{{rupiah($persen)}}{{$bp}}</b></td>
                    <td align="right" valign="top"><b>{{$arl}}{{rupiah($real_lalu)}}{{$brl}}</b></td>  
                </tr>
            @elseif($bold=='3')
                <tr>          
                    <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</td> 
                    <td align="right" valign="top">{{$aa}}{{rupiah($anggaran)}}{{$ba}}</td> 
                    <td align="right" valign="top">{{$ar}}{{rupiah($realisasi)}}{{$br}}</td> 
                    <td align="right" valign="top">{{$ap}}{{rupiah($persen)}}{{$bp}}</td>
                    <td align="right" valign="top">{{$arl}}{{rupiah($real_lalu)}}{{$brl}}</td>  
                </tr>
            @elseif($bold=='4')
                <tr>          
                    <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$nama}}</b></td> 
                    <td align="right" valign="top"><b>{{$aa}}{{rupiah($anggaran)}}{{$ba}}</b></td> 
                    <td align="right" valign="top"><b>{{$ar}}{{rupiah($realisasi)}}{{$br}}</b></td> 
                    <td align="right" valign="top"><b>{{$ap}}{{rupiah($persen)}}{{$bp}}</b></td>
                    <td align="right" valign="top"><b>{{$arl}}{{rupiah($real_lalu)}}{{$brl}}</b></td>  
                </tr>
            @elseif($bold=='5')
                <tr>          
                    <td align="right"  valign="top"><b>{{$nama}}</b></td> 
                    <td align="right" valign="top"><b>{{$aa}}{{rupiah($anggaran)}}{{$ba}}</b></td> 
                    <td align="right" valign="top"><b>{{$ar}}{{rupiah($realisasi)}}{{$br}}</b></td> 
                    <td align="right" valign="top"><b>{{$ap}}{{rupiah($persen)}}{{$bp}}</b></td>
                    <td align="right" valign="top"><b>{{$arl}}{{rupiah($real_lalu)}}{{$brl}}</b></td>  
                </tr>
            @elseif($bold=='0')
                <tr>
                    <td style="font-size:14px;font-family:Open Sans" colspan="5">&nbsp;</td>
                </tr>
            @endif

        @endforeach
    
    {{-- tanda tangan --}}
        <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="1" align=center> 
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center">{{$tempat_tanggal}}</TD>
            </TR>                   
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center">{{$ttd_nih->jabatan}}</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center">&nbsp;</TD>
                <TD width="50%" align="center">&nbsp;</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center">&nbsp;</TD>
                <TD width="50%" align="center">&nbsp;</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center">&nbsp;</TD>
                <TD width="50%" align="center">&nbsp;</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center"><b><u>{{$ttd_nih->nama}}</u></b></TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center">{{$ttd_nih->pangkat}}</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center">{{$ttd_nih->nip}}</TD> 
            </TR>                 
        </TABLE>
    
</body>

</html>
