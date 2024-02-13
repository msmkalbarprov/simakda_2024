<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Aset Neraca Global</title>
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
        <tr></tr>
        <TR>
            <TD colspan="7" align="center" >{{$namanya}}</TD>
        </TR>
    </TABLE><br/>

    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td width="5%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Kode</td>
                <td width="45%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Nama Unit Kerja</td>
                <td width="25%" bgcolor="#CCCCCC" align="center" style="font-size:12px">{{$thn_ang_1}}</td>
                <td width="25%" bgcolor="#CCCCCC" align="center" style="font-size:12px">{{$thn_ang}}</td>
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">1</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">2</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">3</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">4</td> 
            </tr>
        </thead>
        @php
            $tot_nilai=0;
            $tot_nilai_lalu=0;
        @endphp
        @foreach($query as $row)
            @php
                $kode = $row->kd_unit;
                $nama = $row->nm_skpd;
                $nilai = $row->nilai;
                $nilai_lalu = $row->nilai_lalu;
               
                $tot_nilai = $tot_nilai+$nilai;
                $tot_nilai_lalu = $tot_nilai_lalu+$nilai_lalu;
            @endphp
            <tr>
                <td align="center" valign="top" style="font-size:12px">{{$kode}}</td> 
                <td align="left" style="font-size:12px">{{$nama}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{rupiah($nilai)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{rupiah($nilai_lalu)}}</td> 
            </tr>
        @endforeach
        <tr>
            <td colspan="2" align="center" valign="top" style="font-size:12px"><b>TOTAL</b></td> 
            <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_nilai_lalu)}}</b></td> 
            <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_nilai)}}</b></td> 
        </tr>
    </table>
</body>
</html>