<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Penjelasan LO</title>
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
            <TD colspan="7" align="center" >PENJELASAN LAPORAN OPERASIONAL TAHUN ANGGARAN {{$thn_ang}}</TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td rowspan="2" width="5%" bgcolor="#CCCCCC" align="center" style="font-size:12px">No</td>
                <td rowspan="2" width="35%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Unit Kerja</td>
                <td colspan="2" width="25%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Beban Penyusutan</td>
                <td colspan="2" width="25%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Beban Lain-lain</td>
                <td rowspan="2" width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Jumlah</td>
            </tr>
            <tr>
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
            </tr>
        </thead>
        @php
            $jum_bbn_susut=0;
            $jum_bbn_lain=0;
            $total=0;
            $no=0;
        @endphp
        @foreach($query as $row)
            @php
                $kode = $row->kd_skpd;
                $nama = $row->nm_skpd;
                $bbn_susut = $row->bbn_susut;
                $bbn_lain = $row->bbn_lain;
                $tot= $bbn_susut+$bbn_lain;
                $jum_bbn_susut=$jum_bbn_susut+$bbn_susut;
                $jum_bbn_lain=$jum_bbn_lain+$bbn_lain;
                $total=$total+$tot;
                $no=$no+1;
            @endphp
            <tr>
                <td align="center" valign="top" style="font-size:12px">{{$no}}</td>
                <td align="left" style="font-size:12px">{{$nama}}</td>
                <td align="right" valign="top" style="font-size:12px">&nbsp;</td> 
                <td align="right" valign="top" style="font-size:12px">{{$bbn_susut < 0 ? '(' . rupiah($bbn_susut * -1) . ')' : rupiah($bbn_susut) }}</td> 
                               <td align="right" valign="top" style="font-size:12px"></td> 
                <td align="right" valign="top" style="font-size:12px">{{$bbn_lain < 0 ? '(' . rupiah($bbn_lain * -1) . ')' : rupiah($bbn_lain)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$tot < 0 ? '(' . rupiah($tot * -1) . ')' : rupiah($tot)}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" align="center" valign="top" style="font-size:12px"><b>Jumlah</b></td> 
            <td align="right" valign="top" style="font-size:12px">&nbsp;</td> 
            <td align="right" valign="top" style="font-size:12px">{{$jum_bbn_susut < 0 ? '(' . rupiah($jum_bbn_susut * -1) . ')' : rupiah($jum_bbn_susut) }}</td> 
            <td align="right" valign="top" style="font-size:12px"></td> 
            <td align="right" valign="top" style="font-size:12px">{{$jum_bbn_lain < 0 ? '(' . rupiah($jum_bbn_lain * -1) . ')' : rupiah($jum_bbn_lain)}}</td>
            <td align="right" valign="top" style="font-size:12px">{{$total < 0 ? '(' . rupiah($total * -1) . ')' : rupiah($total)}}</td> 
        </tr>
</body>
</html>