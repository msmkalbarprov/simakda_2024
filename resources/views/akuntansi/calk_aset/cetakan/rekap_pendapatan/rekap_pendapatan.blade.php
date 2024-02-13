<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Pendapatan</title>
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
            <TD colspan="7" align="center" ><b>LAMPIRAN I</TD>
        </TR>
        <TR>
            <TD colspan="7" align="center" ><b>REKAP PENDAPATAN TAHUN {{$thn_ang}}</b></TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td bgcolor="#CCCCCC" width="5%" align="center">KODE REKENING</td>
            <td bgcolor="#CCCCCC" width="65%" align="center">NAMA UNIT KERJA</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">PENDAPATAN</td>                       
        </tr>
        @php
            $no=0;
        @endphp
        @foreach($query as $row)
            @php
                $kd_skpd=$row->kd_skpd;
                $nm_skpd=$row->nm_skpd;
                $pendapatan=$row->pendapatan;
            @endphp
            <tr>
                <td align="center" valign="top">{{$kd_skpd}}</td>
                <td align="left">{{$nm_skpd}}</td>
                <td align="right" valign="top">{{$pendapatan < 0 ? '(' . rupiah($pendapatan * -1) . ')' : rupiah($pendapatan) }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>