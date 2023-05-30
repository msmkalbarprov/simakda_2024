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
                <td  align="center" bgcolor="#CCCCCC"><b>Penerimaan</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>Penyetoran</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>Sisa Kas</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>Penyetoran Tahun Lalu</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>Kas Bendahara Tahun Lalu</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>Kas Bendahara Tahun Berjalan</b></td>
                <td  align="center" bgcolor="#CCCCCC"><b>Kas Bendahara Rumus</b></td>
            </tr>
        </thead>

        
        @foreach ($query as $row)
            @php
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $anggaran = $row->anggaran;
                $terima = $row->terima;
                $setor = $row->setor;
                $sisa_kas = $row->sisa_kas;
                $setor_lalu = $row->setor_lalu;
                $kas_ben = $row->kas_ben;
                $kas_ben_lalu = $row->kas_ben_lalu;
                $kas_ben_rumus= $terima-$setor+$setor_lalu;
                
            @endphp
            <tr>
                <td align="center" valign="center">{{$kd_skpd}}</td> 
                <td align="left"  valign="center">{{$nm_skpd}}</td> 
                <td align="right" valign="center">{{rupiah($anggaran)}}</td> 
                <td align="right" valign="center">{{rupiah($terima)}}</td> 
                <td align="right" valign="center">{{rupiah($setor)}}</td> 
                <td align="right" valign="center">{{rupiah($sisa_kas)}}</td> 
                <td align="right" valign="center">{{rupiah($setor_lalu)}}</td>
                <td align="right" valign="center">{{rupiah($kas_ben_lalu)}}</td>  
                <td align="right" valign="center">{{rupiah($kas_ben)}}</td>
                <td align="right" valign="center">{{rupiah($kas_ben_rumus)}}</td> 
            </tr>
            
        @endforeach
        @foreach ($query_jum as $row)
            @php
                $anggaran = $row->total_ang;
                $total_terima = $row->total_terima;
                $total_setor = $row->total_setor;
                $total_sisa_kas = $row->total_sisa_kas;
                $total_setor_lalu = $row->total_setor_lalu;
                $total_kas_ben = $row->total_kas_ben;
                $total_kas_ben_lalu = $row->total_kas_ben_lalu;
                $total_kas_ben_rumus= $total_terima-$total_setor+$total_setor_lalu;
                
            @endphp
            <tr>
                <td align="center" valign="center" colspan="2">TOTAL</td> 
                <td align="right" valign="center">{{rupiah($anggaran)}}</td> 
                <td align="right" valign="center">{{rupiah($total_terima)}}</td> 
                <td align="right" valign="center">{{rupiah($total_setor)}}</td> 
                <td align="right" valign="center">{{rupiah($total_sisa_kas)}}</td> 
                <td align="right" valign="center">{{rupiah($total_setor_lalu)}}</td>
                <td align="right" valign="center">{{rupiah($total_kas_ben_lalu)}}</td> 
                <td align="right" valign="center">{{rupiah($total_kas_ben)}}</td> 
                <td align="right" valign="center">{{rupiah($total_kas_ben_rumus)}}</td> 
            </tr>
            
        @endforeach
        
    </TABLE>

</body>

</html>
