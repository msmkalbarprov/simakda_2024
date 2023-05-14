<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekon BA Neraca</title>
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

<body >
{{-- <body> --}}
    
    <table  style="border-collapse:collapse;font-family:Arial" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
        <tr>
            <td rowspan="4" align="center" style="border-right:hidden; border-bottom: hidden;">
                <img src="{{asset('template/assets/images/'.$header->logo_pemda_hp) }}"  width="75" height="100" />
            </td>
            <td colspan="3" align="center" style="font-size:14px; border-bottom: hidden;"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td>
        </tr>
        <tr>
            <td colspan="3" align="center" style="font-size:16px; border-bottom: hidden;"><strong>BADAN KEUANGAN DAN ASET DAERAH</strong></tr>
        <tr>
            <td colspan="3" align="center" style="font-size:12px; border-bottom: hidden;"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Email: bkad@kalbarprov.go.id Website: bkad.kalbarprov.go.id</strong></tr>
        <tr>
            <td colspan="3" align="center" style="font-size:14px; border-bottom: hidden;"><strong>PONTIANAK</strong></td></tr>
        <tr>
            <td colspan="4" align="right">Kode Pos: 78124 &nbsp; &nbsp;</td>
        </tr>
    </table>
    <hr  valign="top" color="black" size="3px" width="100%">

 
    {{-- isi --}}
    <table style="border-collapse:collapse;font-family: Arial; font-size:12px" width="100%" align="center" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td rowspan="2" align="right" width="10%" height="50">&nbsp;</td>
            <td colspan="3" align="center" style="font-size:14px"><b>Neraca Tahun Anggaran {{$thn_ang}}</b></td>
        </tr>
        <tr>
            <td colspan="5" align="center" style="font-size:14px"><b>Periode {{tgl_format_oyoy($periode1)}} - {{tgl_format_oyoy($periode2)}}</b></td>
        </tr>
        <tr>
            <td colspan="5" align="justify" style="font-size:12px">
                <br>
                SKPD : {{$kd_skpd}} - {{nama_skpd($kd_skpd)}}
                <br>
                <br>
            </td>
        </tr>
    </table>

    <table style="border-collapse:collapse;font-family: Arial; font-size:12px" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <thead>                       
            <tr>
                <td rowspan="2" bgcolor="#CCCCCC" width="5%" align="center"><b>NO</b></td>
                <td colspan="2" bgcolor="#CCCCCC" width="60%" align="center"><b>NERACA TA. {{$thn_ang}}</b></td>
                <td rowspan="2" bgcolor="#CCCCCC" width="30%" align="center"><b>KETERANGAN</b></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC" width="35%" align="center"><b>Klasifikasi</b></td>
                <td bgcolor="#CCCCCC" width="20%" align="center"><b>Nilai</b></td>     
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
    $ekuitas_lalu = $ekuitas_lalu->ekuitas_lalu;
    $no     = 0;
    @endphp
    @foreach($sql as $res)
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

                $nilainya = DB::select("SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
                                    and b.kd_unit=a.kd_skpd where (tgl_voucher between '$periode1' and '$periode2') and
                                        (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                                        kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                                        kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                                        kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                                        kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                                        kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                                        kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                                        kd_rek6 like '$kode_15%') and kd_rek6 not in ('$kecuali') and left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd'");
        @endphp

            @foreach($nilainya as $row)
                @php
                    $debet=$row->debet;
                    $kredit=$row->kredit;
                @endphp
            @endforeach

            @php
                    if ($debet=='') $debet=0;
                    if ($kredit=='') $kredit=0;

                    if ($normal==1){
                        $nl=$debet-$kredit;
                    }else{
                        $nl=$kredit-$debet;             
                    }
                    if ($nl=='') $nl=0;

                    $eka=$nl+$ekuitas;
            

            $nilainya_lalu = DB::select("SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
                                    and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang1 and
                                        (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                                        kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                                        kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                                        kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                                        kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                                        kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                                        kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                                        kd_rek6 like '$kode_15%') and kd_rek6 not in ('$kecuali') and left(a.kd_skpd,len('$kd_skpd'))='$kd_skpd'");        
            @endphp                
            @foreach($nilainya_lalu as $rew)
                @php
                    $debet_lalu=$rew->debet;
                    $kredit_lalu=$rew->kredit;
                @endphp
            @endforeach        

            @php
                    if ($debet_lalu=='') $debet_lalu=0;
                    if ($kredit_lalu=='') $kredit_lalu=0;

                    if ($normal==1){
                        $nl_lalu=$debet_lalu-$kredit_lalu;
                    }else{
                        $nl_lalu=$kredit_lalu-$debet_lalu;             
                    }
                    if ($nl_lalu=='') $nl_lalu=0;

                    $eka_lalu=$nl_lalu+$ekuitas_lalu;

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
                    $e="("; $ekuitas=$ekuitas*-1; $f=")";
                }else {
                    $e=""; $f="";
                }
                if ($ekuitas_lalu < 0){
                    $g="("; $ekuitas_lalu=$ekuitas_lalu*-1; $h=")";
                }else {
                    $g=""; $h="";
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

                $no=$no+1;
            @endphp


            @if ($bold==1)
                @if ($seq==425)
                <tr>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>{{$uraian}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$e}}{{rupiah($ekuitas)}}{{$f}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right></td>
                </tr>
                @elseif($seq==430)
                <tr>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>{{$uraian}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$i}}{{rupiah($eka)}}{{$j}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right></td>
                </tr>
                @else
                <tr>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>{{$uraian}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$a}}{{rupiah($nl)}}{{$b}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right></td>
                </tr>
                @endif
            @elseif($bold==3)
                <tr>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$a}}{{rupiah($nl)}}{{$b}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right></td>
                </tr>
            @elseif($bold==4)
                <tr>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=10% align=center>{{$no}}</td>                                     
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=60%>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$uraian}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right>{{$a}}{{rupiah($nl)}}{{$b}}</td>
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=20% align=right></td>
                </tr>
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
                    <td style=vertical-align:top;border-top: solid 1px black;border-bottom: none; width=15% align=right></td>
                </tr>
            @endif
        

    @endforeach

    
    </table>
    {{-- isi --}}
    
    <div style="padding-top:20px">
        <table style="border-collapse:collapse;font-family: Arial; font-size:14px" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                    <tr><td colspan="4" align="right">&nbsp; &nbsp;</td></tr>
                    <tr><td colspan="4" align="right">&nbsp; &nbsp;</td></tr>
                    <tr><td colspan="4" align="right">Paraf .......................
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                         &nbsp; &nbsp; &nbsp;
                    </td></tr>
                    <tr><td colspan="4" align="right">&nbsp; &nbsp;</td></tr>
                    <tr><td colspan="4" align="right">&nbsp; &nbsp;</td></tr>
                    <tr><td colspan="4" align="right">&nbsp; &nbsp;</td></tr>
                    </table>
    </div>

    {{-- tanda tangan --}}
    
</body>

</html>
