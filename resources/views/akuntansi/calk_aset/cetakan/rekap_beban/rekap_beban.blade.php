<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Beban</title>
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
            <TD colspan="7" align="center" ><b>REKAP BEBAN TAHUN {{$thn_ang}}</b></TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td bgcolor="#CCCCCC" width="5%" align="center">KODE UNIT KERJA</td>
            <td bgcolor="#CCCCCC" width="65%" align="center">NAMA UNIT KERJA</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">PEGAWAI</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">BARANG</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">JASA</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">PEMELIHARAAN</td>
            <td bgcolor="#CCCCCC" width="15%" align="center">PERJALANAN DINAS</td>
        </tr>
        @php
            $no=0;
            $tot_pegawai = 0;
            $tot_barang = 0;
            $tot_jasa = 0;
            $tot_pemeliharaan = 0;
            $tot_perjalanan_dinas = 0;
        @endphp
        @foreach($query as $row)
            @php
                $kd_skpd=$row->kd_skpd;
                $nm_skpd=$row->nm_skpd;
                $pegawai=$row->pegawai;
                $barang=$row->barang;
                $jasa=$row->jasa;
                $pemeliharaan=$row->pemeliharaan;
                $perjalanan_dinas=$row->perjalanan_dinas;

                $tot_pegawai = $tot_pegawai+$pegawai;
                $tot_barang = $tot_barang+$barang;
                $tot_jasa = $tot_jasa+$jasa;
                $tot_pemeliharaan = $tot_pemeliharaan+$pemeliharaan;
                $tot_perjalanan_dinas = $tot_perjalanan_dinas+$perjalanan_dinas;


            @endphp
            <tr>
                <td align="center" valign="top">{{$kd_skpd}}</td>
                <td align="left">{{$nm_skpd}}</td>
                <td align="right" valign="top">{{$pegawai < 0 ? '(' . rupiah($pegawai * -1) . ')' : rupiah($pegawai) }}</td>
                <td align="right" valign="top">{{$barang < 0 ? '(' . rupiah($barang * -1) . ')' : rupiah($barang) }}</td>
                <td align="right" valign="top">{{$jasa < 0 ? '(' . rupiah($jasa * -1) . ')' : rupiah($jasa) }}</td>
                <td align="right" valign="top">{{$pemeliharaan < 0 ? '(' . rupiah($pemeliharaan * -1) . ')' : rupiah($pemeliharaan) }}</td>
                <td align="right" valign="top">{{$perjalanan_dinas < 0 ? '(' . rupiah($perjalanan_dinas * -1) . ')' : rupiah($perjalanan_dinas) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" align="center" valign="top"><b>TOTAL</b></td>
            <td align="right" valign="top"><b>{{$tot_pegawai < 0 ? '(' . rupiah($tot_pegawai * -1) . ')' : rupiah($tot_pegawai) }}</b></td>
            <td align="right" valign="top"><b>{{$tot_barang < 0 ? '(' . rupiah($tot_barang * -1) . ')' : rupiah($tot_barang) }}</b></td>
            <td align="right" valign="top"><b>{{$tot_jasa < 0 ? '(' . rupiah($tot_jasa * -1) . ')' : rupiah($tot_jasa) }}</b></td>
            <td align="right" valign="top"><b>{{$tot_pemeliharaan < 0 ? '(' . rupiah($tot_pemeliharaan * -1) . ')' : rupiah($tot_pemeliharaan) }}</b></td>
            <td align="right" valign="top"><b>{{$tot_perjalanan_dinas < 0 ? '(' . rupiah($tot_perjalanan_dinas * -1) . ')' : rupiah($tot_perjalanan_dinas) }}</b></td>
        </tr>
    </table>
</body>
</html>