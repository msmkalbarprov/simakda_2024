<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. CALK Aset</title>
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
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD colspan="7" align="center" ><b>PEMERINTAH PROVINSI KALIMANTAN BARAT</TD>
        </TR>
        <TR>
            <TD colspan="7" align="center" ><b>{{strtoupper(nama_rek3($rek))}}</TD>
        </TR>
        <TR>
            <TD colspan="7" align="center" ><b>TAHUN {{$thn_ang}}</b></TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td rowspan="2" bgcolor="#CCCCCC" width="5%" align="center">KODE SKPD</td>
            <td rowspan="2" bgcolor="#CCCCCC" width="35%" align="center">NAMA SKPD</td>
            <td rowspan="2" bgcolor="#CCCCCC" width="10%" align="center">KODE AKUN</td>
            <td rowspan="2" bgcolor="#CCCCCC" width="10%" align="center">NAMA AKUN</td>
            <td colspan="2" bgcolor="#CCCCCC" width="50%" align="center">{{$nm_jenis}}</td>                        
        </tr>
        <tr>
            <td bgcolor="#CCCCCC" width="35%" align="center">PENJELASAN</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">REALISASI</td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC" align="center">1</td>
            <td bgcolor="#CCCCCC" align="center">2</td>
            <td bgcolor="#CCCCCC" align="center">3</td>
            <td bgcolor="#CCCCCC" align="center">4</td>                        
            <td bgcolor="#CCCCCC" align="center">5</td>                        
            <td bgcolor="#CCCCCC" align="center">6</td>                        
        </tr>
        @foreach($query as $row)
            @php
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $kd_rek = $row->kd_rek;
                $nm_rek = $row->nm_rek;
                $penjelasan = $row->penjelasan;
                $nilai = $row->nilai;
            @endphp
            <tr>
                <td align="center" valign="top">{{$kd_skpd}}</td>
                <td align="left" valign="top">{{$nm_skpd}}</td>
                <td align="center" valign="top">{{$kd_rek}}</td>
                <td align="center" valign="top">{{$nm_rek}}</td>
                <td align="justify" valign="top">{{$penjelasan}}</td>
                <td align="right" valign="top">{{$nilai < 0 ? '(' . rupiah($nilai * -1) . ')' : rupiah($nilai) }}</td> 
            </tr>
        @endforeach
    </table>
</body>
</html>