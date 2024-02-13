<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Neraca Obyek</title>
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

    <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
                <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
            </tr>
            @if($skpdunit =="keseluruhan")

            @elseif($skpdunit=="skpd")
                <TR>
                    <td align="center"><strong>{{nama_org($kd_skpd)}}</strong></td>
                </TR>
            @else
                <TR>
                    <td align="center"><strong>{{nama_skpd($kd_skpd)}}</strong></td>
                </TR>
            @endif
            <TR>
                <td align="center"><strong>NERACA</strong></td>
            </TR>
            <TR>
                <td align="center"><strong>PER {{$nm_bln}} {{$thn_ang}} DAN {{$thn_ang1}} </strong></td>
            </TR>
            
                
    </TABLE>

    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <thead>                       
            <tr>
                <td bgcolor="#CCCCCC" width="5%" align="center"><b>NO</b></td>
                <td bgcolor="#CCCCCC" width="55%" align="center"><b>URAIAN</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>{{$thn_ang}}</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>{{$thn_ang1}}</b></td>                            
            </tr>
                        
        </thead>
        <tfoot>
            <tr>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>                                             
            </tr>
        </tfoot>
                   
            <tr>   
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="5%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="55%" align="center">&nbsp;</td>                            
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%" align="center">&nbsp;</td>
                           
            </tr>
    @php
    $ekuitas = $ekuitas->ekuitas;
    $ekuitas_tanpa_rkppkd = $ekuitas_tanpa_rkppkd->ekuitas_tanpa_rkppkd;
    $ekuitas_lalu = $ekuitas_lalu->ekuitas_lalu;
    $no     = 0;
    if (strlen($bulan)==1) {
            $bulan="0$bulan";
        }else{
            $bulan=$bulan;
        }
    @endphp
    @foreach($map_neraca as $res)
        @php
                $uraian=$res->uraian;
                $normal=$res->normal;
                $seq=$res->seq;
                $bold=$res->bold;
                
                $kode_1=trim($res->kode_1);
                $kode_2=trim($res->kode_2);
                $kode_3=trim($res->kode_3);
                $kode_4=trim($res->kode_4);
                $kode_5=trim($res->kode_5);
                $kode_6=trim($res->kode_6);
                $kode_7=trim($res->kode_7);
                $kode_8=trim($res->kode_8);
                $kode_9=trim($res->kode_9);
                $kode_10=trim($res->kode_10);
                $kode_11=trim($res->kode_11);
                $kode_12=trim($res->kode_12);
                $kode_13=trim($res->kode_13);
                $kode_14=trim($res->kode_14);
                $kode_15=trim($res->kode_15);
                $kecuali=trim($res->kecuali);
                $c_kurangi=$res->c_kurangi;
                $c_tambah=$res->c_tambah;
                if($c_kurangi=="" || $c_kurangi=="xxx"){
                    $c_kurangi="debet-debet";
                }
                $kurangi_1=trim($res->kurangi_1);
                
                if($c_tambah=="" || $c_tambah=="xxx"){
                    $c_tambah="debet-debet";
                }
                $tambah_1=trim($res->tambah_1);

                $nilainya = DB::select("SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
                                    and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and
                                        (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                                        kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                                        kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                                        kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                                        kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                                        kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                                        kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                                        kd_rek6 like '$kode_15%') and kd_rek6 not in ('$kecuali') $skpd_clauses");

                $kurangi = DB::select("SELECT isnull(SUM($c_kurangi),0) AS realisasi from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
                                    and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and
                                        (kd_rek6 like '$kurangi_1%' ) $skpd_clauses");
                $tambah = DB::select("SELECT isnull(SUM($c_tambah),0) AS realisasi from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
                                    and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$bulan' and
                                        (kd_rek6 like '$tambah_1%' ) $skpd_clauses");
        @endphp

            @foreach($nilainya as $row)
                @php
                    $debet=$row->debet;
                    $kredit=$row->kredit;
                @endphp
            @endforeach
            @foreach($kurangi as $rer)
                @php
                    $kurangi=$rer->realisasi;
                @endphp
            @endforeach
            @foreach($tambah as $rir)
                @php
                    $tambah=$rir->realisasi;
                @endphp
            @endforeach

            @php
                    if ($debet=='') $debet=0;
                    if ($kredit=='') $kredit=0;

                    if ($normal==1){
                        $nl_1=($debet-$kredit);
                    }else{
                        $nl_1=($kredit-$debet);             
                    }
                    if ($nl_1=='') $nl_1=0;
                    $nl = ($nl_1-$kurangi);
                    $eka=$ekuitas-$nl+$tambah;
                    $ju_eku= $ekuitas+$kurangi+$tambah;
            

            $nilainya_lalu = DB::select("SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
                                    and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang1 and
                                        (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                                        kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                                        kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                                        kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                                        kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                                        kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                                        kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                                        kd_rek6 like '$kode_15%') and kd_rek6 not in ('$kecuali') $skpd_clauses");  

            $kurangi_lalu = DB::select("SELECT isnull(SUM($c_kurangi),0) AS realisasi from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
                                    and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang1 and
                                        (kd_rek6 like '$kurangi_1%' ) $skpd_clauses"); 

            $tambah_lalu = DB::select("SELECT isnull(SUM($c_tambah),0) AS realisasi from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
                                    and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang1 and
                                        (kd_rek6 like '$tambah_1%' ) $skpd_clauses");      
            @endphp                

            @foreach($nilainya_lalu as $rew)
                @php
                    $debet_lalu=$rew->debet;
                    $kredit_lalu=$rew->kredit;
                @endphp
            @endforeach        
            @foreach($kurangi_lalu as $riw)
                @php
                    $kurangi_lalu=$riw->realisasi;
                @endphp
            @endforeach    
            @foreach($tambah_lalu as $raw)
                @php
                    $tambah_lalu=$raw->realisasi;
                @endphp
            @endforeach
            @php
                if ($debet_lalu=='') $debet_lalu=0;
                if ($kredit_lalu=='') $kredit_lalu=0;

                if ($normal==1){
                    $nl_lalu=$debet_lalu-$kredit_lalu-$kurangi_lalu;
                }else{
                    $nl_lalu=$kredit_lalu-$debet_lalu-$kurangi_lalu;             
                }
                if ($nl_lalu=='') $nl_lalu=0;

                $eka_lalu=$ekuitas_lalu-$nl_lalu+$tambah_lalu;
                $jeku_lalu = $ekuitas_lalu-$nl_lalu;
                $ju_eku_lalu= $ekuitas_lalu+$kurangi_lalu+$tambah_lalu;

                if ($nl < 0){
                    $a="("; $nl=$nl*-1; $b=")";
                }else {
                    $a=""; $b="";
                }
                if ($nl_lalu < 0){
                    $c="("; $nl_lalu=$nl_lalu*-1; $d=")";
                }else {
                    $c=""; $d="";
                }

                if ($ekuitas < 0){
                    $e="("; $ekuitass=$ekuitas*-1; $f=")";
                }else {
                    $e=""; $ekuitass=$ekuitas; $f="";
                }
                if ($ekuitas_lalu < 0){
                    $g="(";  
                    $ekuitas_laluu=$ekuitas_lalu*-1;
                    $h=")";
                }else{
                    $g=""; 
                    $ekuitas_laluu=$ekuitas_lalu; 
                    $h="";
                }
                if ($eka < 0){
                    $i="("; $eka=$eka*-1; $j=")";
                }else {
                    $i=""; $j="";
                }
                if ($eka_lalu < 0){
                    $k="("; $eka_lalu=$eka_lalu*-1; $l=")";
                }else {
                    $k=""; $l="";
                }
                if ($ekuitas_tanpa_rkppkd < 0){
                    $m="("; $ekuitas_tanpa_rkppkd=$ekuitas_tanpa_rkppkd*-1; $n=")";
                }else {
                    $m=""; $n="";
                }
                if ($jeku_lalu < 0){
                    $o="("; $jeku_lalu=$jeku_lalu*-1; $p=")";
                }else {
                    $o=""; $p="";
                }

                $no=$no+1;
            @endphp


            @if ($bold==1)
                <tr>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>{{$uraian}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$a}}{{rupiah($nl)}}{{$b}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$c}}{{rupiah($nl_lalu)}}{{$d}}</td>
                </tr>

            @elseif($bold==3)
                <tr>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$a}}{{rupiah($nl)}}{{$b}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$c}}{{rupiah($nl_lalu)}}{{$d}}</td>
                </tr>
            @elseif($bold==4)
                @if($seq==1585)
                    <tr>
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$e}}{{rupiah($ekuitass)}}{{$f}}</td>
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$g}}{{rupiah($ekuitas_laluu)}}{{$h}}</td>
                    </tr>
                @elseif($seq==1595)
                    <tr>
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                        @if($skpdunit=="keseluruhan")
                            <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$a}}{{rupiah($nl)}}{{$b}}</td>
                            <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$c}}{{rupiah($nl_lalu)}}{{$d}}</td>
                        @else
                            <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{rupiah($kurangi)}}</td>
                            <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{rupiah($kurangi_lalu)}}</td>
                        @endif
                    </tr>
                @else
                    <tr>
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$a}}{{rupiah($nl)}}{{$b}}</td>
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$c}}{{rupiah($nl_lalu)}}{{$d}}</td>
                    </tr>
                @endif
            @elseif($bold==5)
                <tr>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$a}}{{rupiah($nl)}}{{$b}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$c}}{{rupiah($nl_lalu)}}{{$d}}</td>
                </tr>
            @elseif($bold==6)
                <tr>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{{$uraian}}</b></td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$a}}{{rupiah($nl)}}{{$b}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$c}}{{rupiah($nl_lalu)}}{{$d}}</td>
                </tr>
            @elseif($bold==7)
                @if($seq==1600)
                    <tr>
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{{$uraian}}</b></td>
                        @if($skpdunit=="keseluruhan")
                            <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$i}}{{rupiah($eka)}}{{$j}}</td>
                            <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$o}}{{rupiah($jeku_lalu)}}{{$p}}</td>
                        @else
                            <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{rupiah($ju_eku)}}</td>
                            <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{rupiah($ju_eku_lalu)}}</td>
                        @endif
                    </tr>
                @elseif($seq==1605)
                    <tr>
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{{$uraian}}</b></td>
                        @if($skpdunit=="keseluruhan")
                            <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$i}}{{rupiah($eka)}}{{$j}}</td>
                            <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$k}}{{rupiah($eka_lalu)}}{{$l}}</td>
                        @else
                            <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{rupiah($ju_eku)}}</td>
                            <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{rupiah($ju_eku_lalu)}}</td>
                        @endif
                    </tr>
                @else
                    <tr>
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{{$uraian}}</b></td>
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$a}}{{rupiah($nl)}}{{$b}}</td>
                        <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$c}}{{rupiah($nl_lalu)}}{{$d}}</td>
                    </tr>
                @endif
            @elseif($bold==10)
                <tr>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>{{$uraian}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right></td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right></td>
                </tr>
            @else
                <tr>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=15% align=right>{{$a}}{{rupiah($nl)}}{{$b}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=15% align=right>{{$c}}{{rupiah($nl_lalu)}}{{$d}}</td>
                </tr>
            @endif
        

    @endforeach

    
    </TABLE>
    
</body>

</html>
