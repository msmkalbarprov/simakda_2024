<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Penyusutan Aset</title>
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
            <TD colspan="7" align="center" >{{nama_rek3($rek3)}}</TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td width="5%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Kode  Unit Kerja</td>
                <td width="50%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Nama Unit Kerja</td>
                <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Per 31 Desember {{$thn_ang_1}}</td>
                <td width="10%" align="center" bgcolor="#CCCCCC"  style="font-size:12px">Koreksi</td>
                <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Penyusutan Tahun {{$thn_ang}}</td>
                <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Per 31 Desenber {{$thn_ang}}</td>
                
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
            $tot_sal_lalu = 0;
            $tot_koreksi = 0;
            $tot_penyusutan = 0;
            $tot_sal = 0;
        @endphp
        @foreach($query as $row)
            @php
                $kd_skpd     = $row->kd_skpd;
                $nm_skpd     = $row->nm_skpd;
                $sal_lalu    = $row->sal_lalu;
                $koreksi     = $row->koreksi;
                $penyusutan  = $row->penyusutan;
                $sal         = $row->sal;

                

                $tot_sal_lalu = $tot_sal_lalu+$sal_lalu;
                $tot_koreksi = $tot_koreksi+$koreksi;
                $tot_penyusutan = $tot_penyusutan+$penyusutan;
                $tot_sal = $tot_sal+$sal;
            @endphp
            <tr>
                <td align="left" valign="top" style="font-size:12px">{{$kd_skpd}}</td> 
                <td align="left" style="font-size:12px">{{$nm_skpd}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$sal_lalu < 0 ? '(' . rupiah($sal_lalu * -1) . ')' : rupiah($sal_lalu) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$koreksi < 0 ? '(' . rupiah($koreksi * -1) . ')' : rupiah($koreksi) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$penyusutan < 0 ? '(' . rupiah($penyusutan * -1) . ')' : rupiah($penyusutan) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$sal < 0 ? '(' . rupiah($sal * -1) . ')' : rupiah($sal) }}</td> 
            </tr>
        @endforeach
        <tr>
            <td colspan="2" align="center" valign="top" style="font-size:12px">TOTAL</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_sal_lalu < 0 ? '(' . rupiah($tot_sal_lalu * -1) . ')' : rupiah($tot_sal_lalu) }}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_koreksi < 0 ? '(' . rupiah($tot_koreksi * -1) . ')' : rupiah($tot_koreksi) }}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_penyusutan < 0 ? '(' . rupiah($tot_penyusutan * -1) . ')' : rupiah($tot_penyusutan) }}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_sal < 0 ? '(' . rupiah($tot_sal * -1) . ')' : rupiah($tot_sal) }}</td> 
        </tr>
</body>
</html>