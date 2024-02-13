<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Beban Penyusutan</title>
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
            <TD colspan="7" align="center" ><b>LAPORAN BEBAN PENYUSUTAN {{$thn_ang}}</b></TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <td width="5%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Kode SKPD</td>
                <td width="50%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Nama SKPD</td>
                <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Peralatan dan mesin</td>
                <td width="10%" align="center" bgcolor="#CCCCCC"  style="font-size:12px">Gedung dan Bangunan</td>
                <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Jalan, Irigasi dan Jaringan</td>
                <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px"> Aset tetap lainnya</td>
                <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px"> Aset lainnya</td>
                <td width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Amortisasi</td>
                
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">1</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">2</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">3</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">4</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">5</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">6</td>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">7</td>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">8</td> 
            </tr>
        </thead>
        @foreach($query as $row)
            @php
                $kode = $row->kd_skpd;
                $nama = $row->nm_skpd;
                $peralatan_mesin = $row->peralatan_mesin;
                $gdg_bangunan = $row->gdg_bangunan;
                $jln_irigas = $row->jln_irigas;
                $atl = $row->atl;
                $aset_lainya = $row->aset_lainya;
                $amortisasi = $row->amortisasi;
            @endphp
            <tr>
                <td align="center" valign="top">{{$kode}}</td>
                <td align="left" valign="top">{{$nama}}</td>
                <td align="right" valign="top">{{$peralatan_mesin < 0 ? '(' . rupiah($peralatan_mesin * -1) . ')' : rupiah($peralatan_mesin) }}</td>
                <td align="right" valign="top">{{$gdg_bangunan < 0 ? '(' . rupiah($gdg_bangunan * -1) . ')' : rupiah($gdg_bangunan) }}</td>
                <td align="right" valign="top">{{$jln_irigas < 0 ? '(' . rupiah($jln_irigas * -1) . ')' : rupiah($jln_irigas) }}</td>
                <td align="right" valign="top">{{$atl < 0 ? '(' . rupiah($atl * -1) . ')' : rupiah($atl) }}</td>
                <td align="right" valign="top">{{$aset_lainya < 0 ? '(' . rupiah($aset_lainya * -1) . ')' : rupiah($aset_lainya) }}</td>
                <td align="right" valign="top">{{$amortisasi < 0 ? '(' . rupiah($amortisasi * -1) . ')' : rupiah($amortisasi) }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>