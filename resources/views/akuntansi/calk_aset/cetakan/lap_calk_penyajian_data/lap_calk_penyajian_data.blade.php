<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. CALK Penyajian Data</title>
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
            <TD colspan="7" align="center" ><b>{{$judul}}</TD>
        </TR>
        <TR>
            <TD colspan="7" align="center" ><b>TAHUN {{$thn_ang}}</b></TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td rowspan="2" bgcolor="#CCCCCC" width="5%" align="center">KODE SKPD</td>
            <td rowspan="2" bgcolor="#CCCCCC" width="25%" align="center">NAMA SKPD</td>
            <td colspan="2" bgcolor="#CCCCCC" width="20%" align="center">REALISASI TAHUN ANGGARAN {{$thn_ang}}</td>
            <td rowspan="2" bgcolor="#CCCCCC" width="10%" align="center">SELISIH</td>                        
            <td colspan="2" bgcolor="#CCCCCC" width="40%" align="center">KETERANGAN</td>                        
        </tr>
        <tr>
            <td bgcolor="#CCCCCC" width="10%" align="center">BELANJA MODAL</td>
            <td bgcolor="#CCCCCC" width="10%" align="center">NERACA</td>
            <td bgcolor="#CCCCCC" width="10%" align="center">NILAI</td>
            <td bgcolor="#CCCCCC" width="30%" align="center">PENJELASAN</td>
        </tr>
        <tr>
            <td bgcolor="#CCCCCC" align="center">1</td>
            <td bgcolor="#CCCCCC" align="center">2</td>
            <td bgcolor="#CCCCCC" align="center">3</td>
            <td bgcolor="#CCCCCC" align="center">4</td>                        
            <td bgcolor="#CCCCCC" align="center">5</td>                        
            <td bgcolor="#CCCCCC" align="center">6</td>                        
            <td bgcolor="#CCCCCC" align="center">7</td>                        
        </tr>
        @foreach($query as $row)
            @php
                $jns=$row->jns;
                $kd_skpd=$row->kd_skpd;
                $nm_skpd=$row->nm_skpd;
                $tot_belanja_modal=$row->tot_belanja_modal;
                $tot_neraca=$row->tot_neraca;
                $selisih=$row->selisih;
                $nilai=$row->nilai;
                $ket=$row->ket;
                $kd_rinci=$row->kd_rinci;
            @endphp
            @if($jns=="1")
                <tr>
                    <td align="center" valign="top">{{$kd_skpd}}</td>
                    <td align="left" valign="top">{{$nm_skpd}}</td>
                    <td align="right" valign="top">{{$tot_belanja_modal < 0 ? '(' . rupiah($tot_belanja_modal * -1) . ')' : rupiah($tot_belanja_modal) }}</td>
                    <td align="right" valign="top">{{$tot_neraca < 0 ? '(' . rupiah($tot_neraca * -1) . ')' : rupiah($tot_neraca) }}</td>
                    <td align="right" valign="top">{{$selisih < 0 ? '(' . rupiah($selisih * -1) . ')' : rupiah($selisih) }}</td>
                    <td align="right" valign="top">{{$nilai < 0 ? '(' . rupiah($nilai * -1) . ')' : rupiah($nilai) }}</td>
                    <td align="justify" valign="top">{{$ket}}</td>
                </tr>
            @else
                <tr>
                    <td align="center" valign="top"></td>
                    <td align="left" valign="top"></td>                         
                    <td align="right" valign="top"></td>                         
                    <td align="right" valign="top"></td>                         
                    <td align="right" valign="top"></td> 
                    <td align="right" valign="top">{{$nilai < 0 ? '(' . rupiah($nilai * -1) . ')' : rupiah($nilai) }}</td> 
                    <td align="justify" valign="top">{{$ket}}</td>  
                </tr>
            @endif
        @endforeach
    </table>
</body>
</html>