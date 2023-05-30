<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sisa Kas Pengeluaran</title>
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
    
    
    <table style="border-collapse:collapse;font-family:Arial;font-size:11px" width="100%" align="center" border="1" cellspacing="3" cellpadding="3">
        <thead>
            <tr>
                <td  align="center" bgcolor="#CCCCCC"><b>Kode SKPD</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>Nama SKPD</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>Anggaran</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>SP2D</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>SPJ</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>Sisa Kas</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>Contra Pos</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>Kas Bendahara</b></td>
            </tr>
        </thead>

        
        @foreach ($query as $row)
            @php
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $anggaran1 = $row->anggaran;
                $sp2d = $row->sp2d;
                $spj = $row->spj;
                $sisa_kas = $row->sisa_kas;
                $cp = $row->cp;
                $kas_ben = $row->kas_ben;
                
            @endphp
            <tr>
                <td align="center" valign="center">{{$kd_skpd}}</td> 
                <td align="left"  valign="center">{{$nm_skpd}}</td> 
                <td align="right" valign="center">{{rupiah($anggaran1)}}</td> 
                <td align="right" valign="center">{{rupiah($sp2d)}}</td> 
                <td align="right" valign="center">{{rupiah($spj)}}</td> 
                <td align="right" valign="center">{{rupiah($sisa_kas)}}</td> 
                <td align="right" valign="center">{{rupiah($cp)}}</td> 
                <td align="right" valign="center">{{rupiah($kas_ben)}}</td> 
            </tr>
            
        @endforeach
        @foreach ($query_jum as $row)
            @php
                $total_anggaran = $row->total_ang;
                $total_sp2d = $row->total_sp2d;
                $total_spj = $row->total_spj;
                $total_sisa_kas = $row->total_sisa_kas;
                $total_cp = $row->total_cp;
                $total_kas_ben = $row->total_kas_ben;
                
            @endphp
            <tr>
                <td align="center" valign="center" colspan="2">TOTAL</td> 
                <td align="right" valign="center">{{rupiah($total_anggaran)}}</td> 
                <td align="right" valign="center">{{rupiah($total_sp2d)}}</td> 
                <td align="right" valign="center">{{rupiah($total_spj)}}</td> 
                <td align="right" valign="center">{{rupiah($total_sisa_kas)}}</td> 
                <td align="right" valign="center">{{rupiah($total_cp)}}</td> 
                <td align="right" valign="center">{{rupiah($total_kas_ben)}}</td> 
            </tr>
            
        @endforeach
        
    </TABLE>

</body>

</html>
