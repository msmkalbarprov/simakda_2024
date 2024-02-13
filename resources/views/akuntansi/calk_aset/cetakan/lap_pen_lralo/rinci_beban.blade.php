<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Penjelasan LRA & LO Rincian Beban</title>
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
            <TD colspan="7" align="center" >Penjelasan LRA & LO Rincian Beban Tahun Anggaran {{$thn_ang}}</TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td rowspan="2" width="2%" align="center" bgcolor="#CCCCCC" style="font-size:12px">No</td>
                <td rowspan="2" width="5%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Kode SKPD</td>
                <td rowspan="2" width="33%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Nama SKPD</td>
                <td colspan="2" width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Barang </td>
                <td colspan="2" width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Jasa</td>
                <td colspan="2" width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Pemeliharaan</td>
                <td colspan="2" width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Perjalanan Dinas</td>
                <td colspan="2" width="15%" align="center" bgcolor="#CCCCCC" style="font-size:12px">Beban Yang Akan Diserahkan</td>
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">LRA</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">LO</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">LRA</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">LO</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">LRA</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">LO</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">LRA</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">LO</td>  
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">LRA</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">LO</td> 
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
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">13</td> 
            </tr>
        </thead>
        @php
            $no=0;
            $tot_lra_brg =0;
            $tot_lo_brg =0;
            $tot_lra_jasa =0;
            $tot_lo_jasa =0;
            $tot_lra_pemeliharaan =0;
            $tot_lo_pemeliharaan =0;
            $tot_lra_prj_dinas =0;
            $tot_lo_prj_dinas =0;
            $tot_lo_serah =0;
            $tot_lra_serah =0;
        @endphp
        @foreach($query as $row)
            @php
               $kode = $row->kd_skpd;
               $nama = $row->nm_skpd;
               $lra_brg = $row->lra_brg;
               $lo_brg = $row->lo_brg;
               $jasa = $row->jasa;
               $lo_jasa = $row->lo_jasa;
               $lra_pemeliharaan = $row->lra_pemeliharaan;
               $lo_pemeliharaan = $row->lo_pemeliharaan;
               $lra_prj_dinas = $row->lra_prj_dinas;
               $lo_prj_dinas = $row->lo_prj_dinas;
               $lra_serah = $row->lra_serah;
               $lo_serah = $row->lo_serah;
               $no=$no+1;


                $tot_lra_brg = $tot_lra_brg+$lra_brg;
                $tot_lo_brg = $tot_lo_brg+$lo_brg;
                $tot_lra_jasa = $tot_lra_jasa+$jasa;
                $tot_lo_jasa = $tot_lo_jasa+$lo_jasa;
                $tot_lra_pemeliharaan = $tot_lra_pemeliharaan+$lra_pemeliharaan;
                $tot_lo_pemeliharaan = $tot_lo_pemeliharaan+$lo_pemeliharaan;
                $tot_lra_prj_dinas = $tot_lra_prj_dinas+$lra_prj_dinas;
                $tot_lo_prj_dinas = $tot_lo_prj_dinas+$lo_prj_dinas;
                $tot_lra_serah = $tot_lra_serah+$lra_serah;
                $tot_lo_serah = $tot_lo_serah+$lo_serah;
            @endphp
            <tr>
                <td align="center" valign="top" style="font-size:12px">{{$no}}</td> 
                <td align="left" style="font-size:12px">{{$kode}}</td>
                <td align="left" style="font-size:12px">{{$nama}}</td>
                <td align="right" valign="top" style="font-size:12px">{{$lra_brg < 0 ? '(' . rupiah($lra_brg * -1) . ')' : rupiah($lra_brg) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$lo_brg < 0 ? '(' . rupiah($lo_brg * -1) . ')' : rupiah($lo_brg)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jasa < 0 ? '(' . rupiah($jasa * -1) . ')' : rupiah($jasa)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$lo_jasa < 0 ? '(' . rupiah($lo_jasa * -1) . ')' : rupiah($lo_jasa)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$lra_pemeliharaan < 0 ? '(' . rupiah($lra_pemeliharaan * -1) . ')' : rupiah($lra_pemeliharaan)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$lo_pemeliharaan < 0 ? '(' . rupiah($lo_pemeliharaan * -1) . ')' : rupiah($lo_pemeliharaan)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$lra_prj_dinas < 0 ? '(' . rupiah($lra_prj_dinas * -1) . ')' : rupiah($lra_prj_dinas)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$lo_prj_dinas < 0 ? '(' . rupiah($lo_prj_dinas * -1) . ')' : rupiah($lo_prj_dinas)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$lra_serah < 0 ? '(' . rupiah($lra_serah * -1) . ')' : rupiah($lra_serah)}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$lo_serah < 0 ? '(' . rupiah($lo_serah * -1) . ')' : rupiah($lo_serah)}}</td> 
            </tr>
        @endforeach
        <tr>
            <td colspan="3" align="center" valign="top" style="font-size:12px"><b>Jumlah</b></td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_lra_brg < 0 ? '(' . rupiah($tot_lra_brg * -1) . ')' : rupiah($tot_lra_brg) }}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_lo_brg < 0 ? '(' . rupiah($tot_lo_brg * -1) . ')' : rupiah($tot_lo_brg)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_lra_jasa < 0 ? '(' . rupiah($tot_lra_jasa * -1) . ')' : rupiah($tot_lra_jasa)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_lo_jasa < 0 ? '(' . rupiah($tot_lo_jasa * -1) . ')' : rupiah($tot_lo_jasa)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_lra_pemeliharaan < 0 ? '(' . rupiah($tot_lra_pemeliharaan * -1) . ')' : rupiah($tot_lra_pemeliharaan)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_lo_pemeliharaan < 0 ? '(' . rupiah($tot_lo_pemeliharaan * -1) . ')' : rupiah($tot_lo_pemeliharaan)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_lra_prj_dinas < 0 ? '(' . rupiah($tot_lra_prj_dinas * -1) . ')' : rupiah($tot_lra_prj_dinas)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_lo_prj_dinas < 0 ? '(' . rupiah($tot_lo_prj_dinas * -1) . ')' : rupiah($tot_lo_prj_dinas)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_lra_serah < 0 ? '(' . rupiah($tot_lra_serah * -1) . ')' : rupiah($tot_lra_serah)}}</td> 
            <td align="right" valign="top" style="font-size:12px">{{$tot_lo_serah < 0 ? '(' . rupiah($tot_lo_serah * -1) . ')' : rupiah($tot_lo_serah)}}</td> 
        </tr>
</body>
</html>