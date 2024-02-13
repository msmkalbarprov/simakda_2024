<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Belanja Pegawai dan Barang</title>
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
            <TD colspan="7" align="center" ><b>LAMPIRAN II</TD>
        </TR>
        <TR>
            <TD colspan="7" align="center" ><b>REKAP BELANJA PEGAWAI DAN BELANJA BARANG TAHUN {{$thn_ang}}</b></TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td bgcolor="#CCCCCC" width="5%" align="center">KODE REKENING</td>
            <td bgcolor="#CCCCCC" width="65%" align="center">NAMA UNIT KERJA</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">BELANJA PEGAWAI</td>                        
            <td bgcolor="#CCCCCC" width="15%" align="center">BELANJA BARANG</td>                        
        </tr>
        @php
            $no=0;
        @endphp
        @foreach($query as $row)
            @php
                $kd_skpd=$row->kd_skpd;
                $nm_skpd=$row->nm_skpd;
                $belanja_pegawai=$row->belanja_pegawai;
                $belanja_brg=$row->belanja_brg;
            @endphp
            <tr>
                <td align="center" valign="top">{{$kd_skpd}}</td>
                <td align="left">{{$nm_skpd}}</td>
                <td align="right" valign="top">{{$belanja_pegawai < 0 ? '(' . rupiah($belanja_pegawai * -1) . ')' : rupiah($belanja_pegawai) }}</td> 
                <td align="right" valign="top">{{$belanja_brg < 0 ? '(' . rupiah($belanja_brg * -1) . ')' : rupiah($belanja_brg)}}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>