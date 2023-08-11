<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ikhtisar</title>
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

    <table  style="border-collapse:collapse;font-family:Arial" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
            <tr>
                <td rowspan="4" align="center" style="border-right:hidden">
                    <img src="{{asset('template/assets/images/'.$header->logo_pemda_hp) }}"  width="75" height="100" />
                </td>
                
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-bottom:hidden"><strong>PEMERINTAH {{ strtoupper($header->nm_pemda) }}</strong></td>
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden" ><strong>IKHTISAR PENCAPAIAN KINERJA KEUANGAN</strong></td>
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-top:hidden" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }} </strong></td>
            </tr>

    </table>
    
    
    <table style="border-collapse:collapse;font-family:Arial;font-size:11px" width="100%" align="center" border="1" cellspacing="3" cellpadding="3">
        <thead>
            <tr>
                <td rowspan="2" width="7%" align="center" bgcolor="#CCCCCC" ><b>KD REK</b></td>
                <td rowspan="2" width="40%" align="center" bgcolor="#CCCCCC" ><b>URAIAN</b></td>
                <td colspan="2" width="45%" align="center" bgcolor="#CCCCCC" ><b>JUMLAH (Rp.)</b></td>
                <td colspan="2" width="15%" align="center" bgcolor="#CCCCCC" ><b>BERTAMBAH(BERKURANG)</b></td>
            </tr>
            <tr>
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>ANGGARAN</b></td>
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>REALISASI</b></td>
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>Rp.</b></td>
                <td width="5%" align="center" bgcolor="#CCCCCC" ><b>%</b></td>
            </tr>
            <tr>
                <td align="center" bgcolor="#CCCCCC" >1</td> 
                <td align="center" bgcolor="#CCCCCC" >2</td> 
                <td align="center" bgcolor="#CCCCCC" >3</td> 
                <td align="center" bgcolor="#CCCCCC" >4</td> 
                <td align="center" bgcolor="#CCCCCC" >5=(4-3)</td> 
                <td align="center" bgcolor="#CCCCCC" >6</td> 
            </tr>
        </thead>
    
        
    @foreach($query as $row)
        @php
            $seq = $row->seq;
            $kd_skpd = $row->kd_skpd;
            $kd_kegiatan = $row->kd_kegiatan;
            $nm_rek = $row->nm_rek;
            $nil_ang = $row->anggaran;
            $sd_bulan_ini = $row->realisasi;
            $sisa=$sd_bulan_ini-$nil_ang;
            if($nil_ang==0 || $nil_ang==""){
                $persen=0;
            }else{
                $persen=$sd_bulan_ini/$nil_ang*100;
            }
            if ($sisa<0){
                $sisa1=$sisa*-1;
                $a="(";
                $b=")";
            }else{
                $sisa1=$sisa;
                $a="";
                $b="";
            }
            $leng=strlen($kd_kegiatan);
        @endphp
        @if($seq==5)
            <tr>
                <td align="left" valign="top"><b>{{$kd_kegiatan}}</b></td> 
                <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                <td align="right" valign="top"></td> 
                <td align="right" valign="top"></td> 
                <td align="right" valign="top"></td> 
                <td align="right" valign="top"></td> 
            </tr>
        @elseif($seq==10)
            <tr>
                <td align="left" valign="top"></td> 
                <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                <td align="right" valign="top"><b>{{rupiah($nil_ang)}}</b></td> 
                <td align="right" valign="top"><b>{{rupiah($sd_bulan_ini)}}</b></td> 
                <td align="right" valign="top"><b>{{$a}} {{rupiah($sisa1)}} {{$b}}</b></td> 
                <td align="right" valign="top"><b>{{rupiah($persen)}}</b></td> 
            </tr>
        @else
            <tr>
                <td align="left" valign="top">{{$kd_kegiatan}}</td> 
                <td align="left"  valign="top">{{$nm_rek}}</td> 
                <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                <td align="right" valign="top">{{rupiah($persen)}}</td> 
            </tr>
        @endif
        
    @endforeach
    
    </TABLE>

    
    
</body>

</html>
