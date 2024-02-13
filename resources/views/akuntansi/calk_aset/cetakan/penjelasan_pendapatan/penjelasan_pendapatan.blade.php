<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Penjelasan Pendapatan</title>
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
            <TD colspan="7" align="center" ><b>PENJELASAN PENDAPATAN</TD>
        </TR>
        <TR>
            <TD colspan="7" align="center" ><b>TAHUN {{$thn_ang}}</b></TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td bgcolor="#CCCCCC" width="5%" align="center">Kode SKPD</td>
            <td bgcolor="#CCCCCC" width="25%" align="center">Nama SKPD</td>
            <td bgcolor="#CCCCCC" width="5%" align="center">Kode Rekening</td>
            <td bgcolor="#CCCCCC" width="20%" align="center">Nama Rekening</td>
            <td bgcolor="#CCCCCC" width="55%" align="center">Keterangan</td>                       
        </tr>
        @php
            $no=0;
        @endphp
        @foreach($query as $row)
            @php
                $kd_skpd=$row->kd_skpd;
                $nm_skpd=$row->nm_skpd;
                $kd_ang=$row->kd_ang;
                $nm_rek=$row->nm_rek;
                $ket1=$row->ket1;
            @endphp
            <tr>
                <td align="center" valign="top">{{$kd_skpd}}</td>
                <td align="left" valign="top">{{$nm_skpd}}</td>                         
                <td align="center" valign="top">{{$kd_ang}}</td>                         
                <td align="left" valign="top">{{$nm_rek}}</td>                         
                <td align="justify" valign="top">{!! $ket1 !!}</td>                         
            </tr>
        @endforeach
    </table>
</body>
</html>