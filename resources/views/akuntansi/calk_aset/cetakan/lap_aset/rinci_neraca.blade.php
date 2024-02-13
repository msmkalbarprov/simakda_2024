<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Aset Neraca Rinci</title>
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
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Kode Akun</td>
                <td width="20%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Uraian Akun</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">{{$thn_ang_1}}</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">{{$thn_ang}}</td>
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">1</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">2</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">3</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">4</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">5</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">6</td> 
            </tr>
        </thead>
        @php
            $tot_nilai=0;
            $tot_nilai_lalu=0;
        @endphp
        @foreach($query as $row)
            @php
                $jns = $row->jns;
                $kode = $row->kd_unit;
                $nm_skpd = $row->nm_unit;
                $kd_akun = $row->kd_akun;
                $nm_akun = $row->nm_akun;
                $nilai = $row->nilai;
                $nilai_lalu = $row->nilai_lalu;
            @endphp
            @if($jns==1)
                @php
                    $tot_nilai = $tot_nilai+$nilai;
                    $tot_nilai_lalu = $tot_nilai_lalu+$nilai_lalu;
                @endphp
                <tr>
                   <td align="center" valign="top" style="font-size:12px"><b>{{$kode}}</b></td> 
                   <td align="left" style="font-size:12px"><b>{{$nm_skpd}}</b></td> 
                   <td align="left" style="font-size:12px"><b>{{$kd_akun}}</b></td> 
                   <td align="left" style="font-size:12px"><b>{{$nm_akun}}</b></td> 
                   <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($nilai_lalu)}}</b></td> 
                   <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($nilai)}}</b></td> 
                </tr>
            @else
                <tr>
                   <td align="center" valign="top" style="font-size:12px">{{$kode}}</td> 
                   <td align="left" style="font-size:12px">{{$nm_skpd}}</td> 
                   <td align="left" style="font-size:12px">{{$kd_akun}}</td> 
                   <td align="left" style="font-size:12px">{{$nm_akun}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($nilai_lalu)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($nilai)}}</td> 
                </tr>
            @endif
        @endforeach
        @php
            $tot = $query_tot->tot;
            $tot_lalu = $query_tot->tot_lalu;  
        @endphp
        <tr>
            <td colspan="4" align="center" valign="top" style="font-size:12px"><b>TOTAL</b></td> 
            <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_nilai_lalu)}}</b></td> 
            <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_nilai)}}</b></td> 
        </tr>
    </table>
</body>
</html>