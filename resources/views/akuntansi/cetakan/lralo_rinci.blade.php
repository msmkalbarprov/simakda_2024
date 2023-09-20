<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rincian Selisih LRA & LO</title>
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

    @if ($periodebulan=="periode")
        <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
                <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
            </tr>
             <TR>
                <td align="center"><strong>SELISIH LRA & LO</strong></td>
            </TR>
            <TR>
                <td align="center"><strong>PERIODE {{tgl_format_oyoy($tgl1)}} s/d {{tgl_format_oyoy($tgl2)}} </strong></td>
            </TR>
    </table>
    @else
        <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
                <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
            </tr>
             <TR>
                <td align="center"><strong>SELISIH LRA & LO</strong></td>
            </TR>
            <TR>
                <td align="center"><strong>PER {{$nm_bln}} {{$thn_ang}} DAN {{$thn_ang1}} </strong></td>
            </TR>
            <tr>
                <td colspan="5" align="justify" style="font-size:15px">
                <br>
                    Kode Rekening &nbsp;&nbsp;&nbsp;: {{$kd_rek6}} - {{nama_rekening($kd_rek6)}} || {{$kd_lo}} - {{nama_rekening($kd_lo)}}
                <br>
                </td>
            </tr>
        </TABLE>
    @endif
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <thead>                       
            <tr> 
                <td bgcolor="#CCCCCC" width="5%" align="center"><b>Kode SKPD</b></td>
                <td bgcolor="#CCCCCC" width="5%" align="center"><b>Nama SKPD</b></td>
                <td bgcolor="#CCCCCC" width="5%" align="center"><b>Kode Rekening LRA</b></td>
                <td bgcolor="#CCCCCC" width="25%" align="center"><b>Nama Rekening LRA</b></td>
                <td bgcolor="#CCCCCC" width="10%" align="center"><b>Realisasi LRA</b></td>
                <td bgcolor="#CCCCCC" width="5%" align="center"><b>Kode Rekening LO</b></td>
                <td bgcolor="#CCCCCC" width="25%" align="center"><b>Nama Rekening LO</b></td>
                <td bgcolor="#CCCCCC" width="10%" align="center"><b>Realisasi LO</b></td>
                <td bgcolor="#CCCCCC" width="10%" align="center"><b>Selisih</b></td>
            </tr>
                        
        </thead>
        <tfoot>
            <tr>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td> 
                <td style="border-top: none;"></td>
                <td style="border-top: none;"></td> 
                <td style="border-top: none;"></td>                                
            </tr>
        </tfoot>
                   
            <tr>   
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="25%" align="center">&nbsp;</td>                            
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="25%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="10%" align="center">&nbsp;</td>
                           
            </tr>
    @php
        $no    = 0;
        $tlra  = 0;
        $tlo   = 0;
    @endphp
    @foreach($query as $res)
        @php
            $kd_skpd    =$res->kd_skpd;
            $kd_rek6     =$res->kd_rek6;
            $kd_lo      =$res->map_lo;
            $lra        =$res->lra;
            $lo         =$res->lo;
            $selisih    =$lra-$lo;

            if ($lra <0) {
                $alra='(';
                $lrares = $lra*-1;
                $blra=')';
            }else{
                $alra='';
                $lrares = $lra;
                $blra='';
            }

            if ($lo <0) {
                $alo='(';
                $lores = $lo*-1;
                $blo=')';
            }else{
                $alo='';
                $lores = $lo;
                $blo='';
            }

            if ($selisih <0) {
                $as='(';
                $selisihres = $selisih*-1;
                $bs=')';
            }else{
                $as='';
                $selisihres = $selisih;
                $bs='';
            }


        @endphp
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="center">{{$kd_skpd}}</td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%">{{nama_skpd($kd_skpd)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="center">{{$kd_rek6}}</td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%">{{nama_rekening($kd_rek6)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"align="right">{{$alra}}{{rupiah($lrares)}}{{$blra}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="center">{{$kd_lo}}</td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%">{{nama_rekening($kd_lo)}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"align="right">{{$alo}}{{rupiah($lores)}}{{$blo}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"align="right">{{$as}}{{rupiah($selisihres)}}{{$bs}}</td>
            </tr>
        @php
           $tlra =$tlra+$lra;
            $tlo = $tlo+$lo;
            
        @endphp

    @endforeach

    @php
        if ($tlra <0) {
            $clra='(';
            $tlrares = $tlra*-1;
            $dlra=')';
        }else{
            $clra='';
            $tlrares = $tlra;
            $dlra='';
        }
        if ($tlo <0) {
            $clo='(';
            $tlores = $tlo*-1;
            $dlo=')';
        }else{
            $clo='';
            $tlores = $tlo;
            $dlo='';
        }

        $tselisih = $tlra-$tlo;
        if ($tselisih <0) {
            $cselisih='(';
            $tselisihres = $tselisih*-1;
            $dselisih=')';
        }else{
            $cselisih='';
            $tselisihres = $tselisih;
            $dselisih='';
        }
    @endphp
            <tr>                        
                <td colspan="3"style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%">TOTAL</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"align="right">{{$clra}}{{rupiah($tlrares)}}{{$dlra}}</td>                        
                <td colspan="3"style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%"></td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"align="right">{{$clo}}{{rupiah($tlores)}}{{$dlo}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"align="right">{{$cselisih}}{{rupiah($tselisihres)}}{{$dselisih}}</td>
            </tr>
    </TABLE>
    
</body>

</html>