<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Penjelasan LRA & LO Biasa</title>
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
            <TD colspan="7" align="center" >Penjelasan LRA & LO {{$thn_ang}}</TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td rowspan="2" width="5%" align="center" bgcolor="#CCCCCC" style="font-size:12px">No</td>
                <td rowspan="2" width="5%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Unit Kerja</td>
                <td colspan="3" width="25%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Laporan Realisasi Anggaran</td>
                <td rowspan="2" width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Jumlah LRA</td>
                <td colspan="3" width="25%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Laporan Operasional</td>
                <td rowspan="2" width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Jumlah LO</td>
                <td rowspan="2" width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Selisih antara<br>LRA dan LO</td>
                <td rowspan="2" width="10%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Keterangan</td>
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Pendapatan</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Belanja Pegawai</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Barang dan Jasa</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Pendapatan</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Beban Pegawai</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Barang dan Jasa</td> 
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
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">10</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">11</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">12</td> 
            </tr>
        </thead>
        @php
            $jum_pend=0;
            $jum_bel_peg=0;
            $jum_bel_br=0;
            $jum_pend_lo=0;
            $jum_bbn_peg=0;
            $jum_bbn_br=0;
            $tot_lra=0;
            $tot_lo=0;
            $tot_sel=0;
            $no=0;
        @endphp
        @foreach($query as $row)
            @php
                $kode = $row->kd_skpd;
                $nama = $row->nm_skpd;
                $pend = $row->pend;
                $bel_peg = $row->bel_peg;
                $bel_br = $row->bel_br;
                $pend_lo = $row->pend_lo;
                $bbn_peg = $row->bbn_peg;
                $bbn_br = $row->bbn_br;
                $jum_lra= $pend+$bel_peg+$bel_br;
                $jum_lo = $pend_lo+$bbn_peg+$bbn_br;
                $selisih=$jum_lra-$jum_lo;
                $jum_pend=$jum_pend+$pend;
                $jum_bel_peg=$jum_bel_peg+$bel_peg;
                $jum_bel_br=$jum_bel_br+$bel_br;
                $jum_pend_lo=$jum_pend_lo+$pend_lo;
                $jum_bbn_peg=$jum_bbn_peg+$bbn_peg;
                $jum_bbn_br=$jum_bbn_br+$bbn_br;
                $tot_lra=$tot_lra+$jum_lra;
                $tot_lo=$tot_lo+$jum_lo;
                $tot_sel=$tot_sel+$selisih;
                $no=$no+1;
            @endphp
            <tr>
                <td align="center" valign="top" style="font-size:12px">{{$no}}</td> 
                <td align="left" style="font-size:12px">{{$nama}}</td>
                <td align="right" valign="top" style="font-size:12px">{{$pend < 0 ? '(' . rupiah($pend * -1) . ')' : rupiah($pend) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$bel_peg < 0 ? '(' . rupiah($bel_peg * -1) . ')' : rupiah($bel_peg)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$bel_br < 0 ? '(' . rupiah($bel_br * -1) . ')' : rupiah($bel_br)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_lra < 0 ? '(' . rupiah($jum_lra * -1) . ')' : rupiah($jum_lra)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$pend_lo < 0 ? '(' . rupiah($pend_lo * -1) . ')' : rupiah($pend_lo)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$bbn_peg < 0 ? '(' . rupiah($bbn_peg * -1) . ')' : rupiah($bbn_peg)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$bbn_br < 0 ? '(' . rupiah($bbn_br * -1) . ')' : rupiah($bbn_br)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_lo < 0 ? '(' . rupiah($jum_lo * -1) . ')' : rupiah($jum_lo)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$selisih < 0 ? '(' . rupiah($selisih * -1) . ')' : rupiah($selisih)}}</td> 
                <td align="right" valign="top" style="font-size:12px">&nbsp;</td> 
            </tr>
        @endforeach
        <tr>
            <td colspan="2" align="center" valign="top" style="font-size:12px">TOTAL</td> 
            <td align="right" valign="top" style="font-size:12px">{{$jum_pend < 0 ? '(' . rupiah($jum_pend * -1) . ')' : rupiah($jum_pend) }}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$jum_bel_peg < 0 ? '(' . rupiah($jum_bel_peg * -1) . ')' : rupiah($jum_bel_peg)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$jum_bel_br < 0 ? '(' . rupiah($jum_bel_br * -1) . ')' : rupiah($jum_bel_br)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_lra < 0 ? '(' . rupiah($tot_lra * -1) . ')' : rupiah($tot_lra)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$jum_pend_lo < 0 ? '(' . rupiah($jum_pend_lo * -1) . ')' : rupiah($jum_pend_lo)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$jum_bbn_peg < 0 ? '(' . rupiah($jum_bbn_peg * -1) . ')' : rupiah($jum_bbn_peg)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$jum_bbn_br < 0 ? '(' . rupiah($jum_bbn_br * -1) . ')' : rupiah($jum_bbn_br)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_lo < 0 ? '(' . rupiah($tot_lo * -1) . ')' : rupiah($tot_lo)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_sel < 0 ? '(' . rupiah($tot_sel * -1) . ')' : rupiah($tot_sel)}}</td> 
            <td align="right" valign="top" style="font-size:12px">&nbsp;</td> 
        </tr>
</body>
</html>