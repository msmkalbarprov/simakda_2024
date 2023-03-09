<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Neraca Saldo</title>
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

<body onload="window.print()">
{{-- <body> --}}

    @if ($bulan=='')
        <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
        <tr>
            <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
        </tr>
         <TR>
            <td align="center"><strong>NERACA SALDO</strong></td>
        </TR>
        <TR>
            <td align="center"><strong>PERIODE {{tgl_format_oyoy($tgl1)}} s/d {{tgl_format_oyoy($tgl2)}} </strong></td>
        </TR>
        @if($skpd=='')
            <tr>
                <td colspan="5" align="justify" style="font-size:12px">
                <br>
                
                <br>
                <br>
                    
                </td>
            </tr>
        @else
            <tr>
                <td colspan="5" align="justify" style="font-size:12px">
                <br>
                SKPD : $skpd - $nm_skpd
                <br>
                <br>
                    
                </td>
            </tr>
        @endif
    </TABLE>
    @else
        <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
                <td align="center"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
            </tr>
             <TR>
                <td align="center"><strong>NERACA SALDO</strong></td>
            </TR>
            <TR>
                <td align="center"><strong>PER {{$nm_bln}} {{$thn_ang}} DAN {{$thn_ang1}} </strong></td>
            </TR>
            @if($skpd=='')
                <tr>
                    <td colspan="5" align="justify" style="font-size:12px">
                    <br>
                    
                    <br>
                    <br>
                        
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="5" align="justify" style="font-size:12px">
                    <br>
                    SKPD : {{$skpd}} - {{nama_skpd($skpd)}}
                    <br>
                    <br>
                        
                    </td>
                </tr>
            @endif
        </TABLE>
    @endif
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <thead>                       
            <tr>
                <td rowspan="2"  bgcolor="#CCCCCC" width="10%" align="center"><b>Kode Rekening</b></td>
                <td rowspan="2" bgcolor="#CCCCCC" width="45%" align="center"><b>Nama Rekening</b></td>
                <td rowspan="2" bgcolor="#CCCCCC" width="15%" align="center"><b>Saldo Awal</b></td>
                <td colspan="2" bgcolor="#CCCCCC" width="40%" align="center"><b>Jumlah</b></td>
                <td rowspan="2" bgcolor="#CCCCCC" width="20%" align="center"><b>Saldo Akhir</b></td>
            </tr>
            <tr>
                <td bgcolor="#CCCCCC" width="25%" align="center"><b>Debit</b></td>
                <td bgcolor="#CCCCCC" width="25%" align="center"><b>Kredit</b></td>     
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
            </tr>
        </tfoot>
                   
            <tr>   
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="5%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="55%" align="center">&nbsp;</td>                            
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="15%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%" align="center">&nbsp;</td>
                <td style="vertical-align:top;border-top: none;border-bottom: none;" width="20%" align="center">&nbsp;</td>
                           
            </tr>
    @php
    $no     = 0;
            $tsaldoawal     = 0;
            $tsaldoakhir    = 0;
            $tdebet         = 0;
            $tkredit        = 0;

    @endphp
    @foreach($query as $res)
        @php
                $kd_rek     =$res->kd_rek;
                $nm_rek     =$res->nm_rek;
                $debet      =$res->debet;
                $kredit     =$res->kredit;
                $saldoawal  =$res->SaldoAwal;
                $saldoakhir =$res->saldoakhir;

                if ($saldoawal <0) {
                $min1='(';
                $saldoawalres = $saldoawal*-1;
                $min2=')';
            }
            if ($saldoawal>=0) {
                $min1='';
                $saldoawalres = $saldoawal;
                $min2='';
            }

            if ($saldoakhir <0) {
                $min3='(';
                $saldoakhirres = $saldoakhir*-1;
                $min4=')';
            }
            if ($saldoakhir >=0){
                $min3='';
                $saldoakhirres = $saldoakhir;
                $min4='';
            }
                $saldoawalres= rupiah($saldoawalres);
                $debetres= rupiah($debet);
                $kreditres= rupiah($kredit);
                $saldoakhirres= rupiah($saldoakhirres);
        @endphp
            <tr>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="center">{{$kd_rek}}</td>                                     
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%">{{$nm_rek}}</td>

                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"align="right">{{$min1}}{{$saldoawalres}}{{$min2}}</td>

                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right">{{$debetres}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right">{{$kreditres}}</td>

                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"align="right">{{$min3}}{{$saldoakhirres}}{{$min4}}</td>
            </tr>
        @php
           $tsaldoawal =$tsaldoawal+$saldoawal;
            $tsaldoakhir = $tsaldoakhir+$saldoakhir;
            $tdebet = $tdebet+$debet;
            $tkredit = $tkredit+$kredit;
            
        @endphp

    @endforeach

    @php
        if ($tsaldoawal <0) {
            $min5='(';
            $tsaldoawalres = $tsaldoawal*-1;
            $min6=')';
        }
        if ($tsaldoawal >=0) {
            $min5='';
            $tsaldoawalres = $tsaldoawal;
            $min6='';
        }
        if ($tsaldoakhir <0) {
            $min7='(';
            $tsaldoakhirres = $tsaldoakhir*-1;
            $min8=')';
        }
        if ($tsaldoakhir >=0){
            $min7='';
            $tsaldoakhirres = $tsaldoakhir;
            $min8='';
        }
        $tsaldoawalres= rupiah($tsaldoawalres);
        $tdebetres= rupiah($tdebet);
        $tkreditres= rupiah($tkredit);
        $tsaldoakhirres= rupiah($tsaldoakhirres);
    @endphp
            <TR>                        
                <td colspan="2"style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="60%">TOTAL</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%"align="right">{{$min5}}{{$tsaldoawalres}}{{$min6}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right">{{$tdebetres}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="15%" align="right">{{$tkreditres}}</td>
                <td style="vertical-align:top;border-top: solid 1px black;border-bottom: none;" width="20%"align="right">{{$min7}}{{$tsaldoakhirres}}{{$min8}}</td>
            </TR>
    </TABLE>
    
</body>

</html>
