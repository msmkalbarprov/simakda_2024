<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Penjelasan Komulatif</title>
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
            <TD colspan="7" align="center" >PENJELASAN DAMPAK KOMULATIF KEBIJAKAN/KESALAHAN MENDASAR PADA LAPORAN PERUBAHAN EKUITAS TAHUN ANGGARAN {{$thn_ang}}</TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td rowspan="2" width="5%" align="center" bgcolor="#CCCCCC" style="font-size:12px">No</td>
                <td rowspan="2" width="25%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Unit Kerja</td>
                <td colspan="2" width="20%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Koreksi Nilai Persediaan</td>
                <td colspan="2" width="20%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Selisih Revaluasi Aset Tetap</td>
                <td colspan="2" width="20%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Lain - Lain</td>
                <td rowspan="2" width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Jumlah</td>
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Uraian</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Realisasi</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Uraian</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Realisasi</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Uraian</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Realisasi</td> 
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
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">9</td> 
            </tr>
        </thead>
        @php
            $jum_real_sedia=0;
            $jum_real_tetap=0;
            $jum_real_lain=0;
            $total=0;
            $no=0;
        @endphp
        @foreach($query as $row)
            @php
                $kode = $row->kd_skpd;
                $nama = $row->nm_skpd;
                $real_sedia = $row->real_sedia;
                $real_tetap = $row->real_tetap;
                $real_lain = $row->real_lain;
                $tot= $real_sedia+$real_tetap+$real_lain;
                $jum_real_sedia=$jum_real_sedia+$real_sedia;
                $jum_real_tetap=$jum_real_tetap+$real_tetap;
                $jum_real_lain=$jum_real_lain+$real_lain;
                $total=$total+$tot;
                $no=$no+1;
            @endphp
            <tr>
                <td align="center" valign="top" style="font-size:12px">{{$no}}</td>
                <td align="left" style="font-size:12px">{{$nama}}</td>
                <td align="right" valign="top" style="font-size:12px">&nbsp;</td> 
                <td align="right" valign="top" style="font-size:12px">{{$real_sedia < 0 ? '(' . rupiah($real_sedia * -1) . ')' : rupiah($real_sedia) }}</td> 
                               <td align="right" valign="top" style="font-size:12px"></td> 
                <td align="right" valign="top" style="font-size:12px">{{$real_tetap < 0 ? '(' . rupiah($real_tetap * -1) . ')' : rupiah($real_tetap)}}</td> 
                               <td align="right" valign="top" style="font-size:12px"></td> 
                <td align="right" valign="top" style="font-size:12px">{{$real_lain < 0 ? '(' . rupiah($real_lain * -1) . ')' : rupiah($real_lain)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$tot < 0 ? '(' . rupiah($tot * -1) . ')' : rupiah($tot)}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" align="center" valign="top" style="font-size:12px"><b>Jumlah</b></td> 
                               <td align="right" valign="top" style="font-size:12px">&nbsp;</td> 
            <td align="right" valign="top" style="font-size:12px">{{$jum_real_sedia < 0 ? '(' . rupiah($jum_real_sedia * -1) . ')' : rupiah($jum_real_sedia) }}</td> 
                               <td align="right" valign="top" style="font-size:12px"></td> 
            <td align="right" valign="top" style="font-size:12px">{{$jum_real_tetap < 0 ? '(' . rupiah($jum_real_tetap * -1) . ')' : rupiah($jum_real_tetap)}}</td> 
                               <td align="right" valign="top" style="font-size:12px"></td> 
            <td align="right" valign="top" style="font-size:12px">{{$jum_real_lain < 0 ? '(' . rupiah($jum_real_lain * -1) . ')' : rupiah($jum_real_lain)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$total < 0 ? '(' . rupiah($total * -1) . ')' : rupiah($total)}}</td> 
        </tr>
</body>
</html>