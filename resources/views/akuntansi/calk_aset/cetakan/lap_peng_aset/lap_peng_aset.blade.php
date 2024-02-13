<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Pengadaan Aset</title>
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
            <TD colspan="7" align="center" >PENGADAAN ASET TAHUN ANGGARAN {{$thn_ang}}</TD>
        </TR>
    </TABLE><br/>
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td rowspan="3" bgcolor="#CCCCCC" width="5%" align="center" style="font-size:12px">No</td>
                <td rowspan="3" bgcolor="#CCCCCC" width="5%" align="center" style="font-size:12px">Unit Kerja</td>
                <td colspan="6" bgcolor="#CCCCCC" width="50%" align="center" style="font-size:12px">LRA</td>
                <td rowspan="3" bgcolor="#CCCCCC" width="10%" align="center" style="font-size:12px">Jumlah LRA</td>
                <td colspan="9" bgcolor="#CCCCCC" width="10%" align="center" style="font-size:12px">Neraca</td>
                <td rowspan="3" bgcolor="#CCCCCC" width="10%" align="center" style="font-size:12px">Jumlah Neraca</td>
                <td rowspan="3" bgcolor="#CCCCCC" width="10%" align="center" style="font-size:12px">Selisih antara<br>LRA dan Neraca</td>
                <td rowspan="3" bgcolor="#CCCCCC" width="10%" align="center" style="font-size:12px">Keterangan</td>
            </tr>
            <tr>
               <td colspan="6" bgcolor="#CCCCCC" align="center" style="font-size:12px">Belanja Modal</td> 
               <td colspan="1" bgcolor="#CCCCCC" align="center" style="font-size:12px">Aset Lancar</td> 
               <td colspan="6" bgcolor="#CCCCCC" align="center" style="font-size:12px">Aset Tetap {{$thn_ang}}</td> 
               <td colspan="2" bgcolor="#CCCCCC" align="center" style="font-size:12px">Aset Lainnya</td> 
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Tanah</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Peralatan dan Mesin</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Gedung dan Bangunan</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Jalan Irigasi dan Jaringan</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Aset Tetap Lainnya</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Aset Lainnya</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Persediaan</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Tanah</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Peralatan dan Mesin</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Gedung dan Bangunan</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Jalan Irigasi dan Jaringan</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Aset Tetap Lainnya</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Konstruksi dalam Pengerjaan</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Aset Tak Berwujud</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">Aset Lainnya</td> 
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
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">14</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">15</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">16</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">17</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">18</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">19</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">20</td> 
               <td align="center" bgcolor="#CCCCCC" style="font-size:12px">21</td> 
            </tr>
        </thead>
        @php
            $no = 0;
            $jum_mod_tanah=0;
            $jum_mod_mesin=0;
            $jum_mod_gedung=0;
            $jum_mod_jalan=0;
            $jum_mod_tetap=0;
            $jum_mod_lainnya=0;
            $jum_aset_persediaan=0;
            $jum_aset_tanah=0;
            $jum_aset_gedung=0;
            $jum_aset_mesin=0;
            $jum_aset_jalan=0;
            $jum_aset_tetap=0;
            $jum_aset_kontruksi=0;
            $jum_aset_takwujud=0;
            $jum_aset_lainnya=0;
            $tot_mod=0;
            $tot_aset=0;
            $tot_selisih=0;
        @endphp
        @foreach($query as $row)
            @php
                $kode = $row->kd_skpd;
                $nama = $row->nm_skpd;
                $mod_tanah = $row->mod_tanah;
                $mod_mesin = $row->mod_mesin;
                $mod_gedung = $row->mod_gedung;
                $mod_jalan = $row->mod_jalan;
                $mod_tetap = $row->mod_tetap;
                $mod_lainnya = $row->mod_lainnya;
                $aset_persediaan = $row->aset_persediaan;
                $aset_tanah = $row->aset_tanah;
                $aset_gedung = $row->aset_gedung;
                $aset_mesin = $row->aset_mesin;
                $aset_jalan = $row->aset_jalan;
                $aset_tetap = $row->aset_tetap;
                $aset_kontruksi = $row->aset_kontruksi;
                $aset_takwujud = $row->aset_takwujud;
                $aset_lainnya = $row->aset_lainnya;
                $jum_mod= $mod_tanah+$mod_mesin+$mod_gedung+$mod_jalan+$mod_tetap+$mod_lainnya;
                $jum_aset = $aset_persediaan+$aset_tanah+$aset_gedung+$aset_mesin+$aset_jalan+$aset_tetap+$aset_kontruksi+$aset_takwujud+$aset_lainnya;
                $selisih=$jum_aset-$jum_mod;

                $jum_mod_tanah=$jum_mod_tanah+$mod_tanah;
                $jum_mod_mesin=$jum_mod_mesin+$mod_mesin;
                $jum_mod_gedung=$jum_mod_gedung+$mod_gedung;
                $jum_mod_jalan=$jum_mod_jalan+$mod_jalan;
                $jum_mod_tetap=$jum_mod_tetap+$mod_tetap;
                $jum_mod_lainnya=$jum_mod_lainnya+$mod_lainnya;
                $jum_aset_persediaan=$jum_aset_persediaan+$aset_persediaan;
                $jum_aset_tanah=$jum_aset_tanah+$aset_tanah;
                $jum_aset_gedung=$jum_aset_gedung+$aset_gedung;
                $jum_aset_mesin=$jum_aset_mesin+$aset_mesin;
                $jum_aset_jalan=$jum_aset_jalan+$aset_jalan;
                $jum_aset_tetap=$jum_aset_tetap+$aset_tetap;
                $jum_aset_kontruksi=$jum_aset_kontruksi+$aset_kontruksi;
                $jum_aset_takwujud=$jum_aset_takwujud+$aset_takwujud;
                $jum_aset_lainnya=$jum_aset_lainnya+$aset_lainnya;
                $tot_mod=$tot_mod+$jum_mod;
                $tot_aset=$tot_aset+$jum_aset;
                $tot_selisih=$tot_selisih+$selisih;
                $no=$no+1;
            @endphp
            <tr>
                <td align="center" valign="top" style="font-size:12px">{{$no}}</td> 
                <td align="left" style="font-size:12px">{{$nama}}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$mod_tanah < 0 ? '(' . rupiah($mod_tanah * -1) . ')' : rupiah($mod_tanah) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$mod_mesin < 0 ? '(' . rupiah($mod_mesin * -1) . ')' : rupiah($mod_mesin) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$mod_gedung < 0 ? '(' . rupiah($mod_gedung * -1) . ')' : rupiah($mod_gedung) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$mod_jalan < 0 ? '(' . rupiah($mod_jalan * -1) . ')' : rupiah($mod_jalan) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$mod_tetap < 0 ? '(' . rupiah($mod_tetap * -1) . ')' : rupiah($mod_tetap) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$mod_lainnya < 0 ? '(' . rupiah($mod_lainnya * -1) . ')' : rupiah($mod_lainnya) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_mod < 0 ? '(' . rupiah($jum_mod * -1) . ')' : rupiah($jum_mod) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$aset_persediaan < 0 ? '(' . rupiah($aset_persediaan * -1) . ')' : rupiah($aset_persediaan) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$aset_tanah < 0 ? '(' . rupiah($aset_tanah * -1) . ')' : rupiah($aset_tanah) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$aset_mesin < 0 ? '(' . rupiah($aset_mesin * -1) . ')' : rupiah($aset_mesin) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$aset_gedung < 0 ? '(' . rupiah($aset_gedung * -1) . ')' : rupiah($aset_gedung) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$aset_jalan < 0 ? '(' . rupiah($aset_jalan * -1) . ')' : rupiah($aset_jalan) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$aset_tetap < 0 ? '(' . rupiah($aset_tetap * -1) . ')' : rupiah($aset_tetap) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$aset_kontruksi < 0 ? '(' . rupiah($aset_kontruksi * -1) . ')' : rupiah($aset_kontruksi) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$aset_takwujud < 0 ? '(' . rupiah($aset_takwujud * -1) . ')' : rupiah($aset_takwujud) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$aset_lainnya < 0 ? '(' . rupiah($aset_lainnya * -1) . ')' : rupiah($aset_lainnya) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_aset < 0 ? '(' . rupiah($jum_aset * -1) . ')' : rupiah($jum_aset) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$selisih < 0 ? '(' . rupiah($selisih * -1) . ')' : rupiah($selisih) }}</td> 
                <td align="right" valign="top" style="font-size:12px">&nbsp;</td> 
            </tr>
        @endforeach
        <tr>
            <td colspan="2" align="center" valign="top" style="font-size:12px">TOTAL</td> 
            <td align="right" valign="top" style="font-size:12px">{{$jum_mod_tanah < 0 ? '(' . rupiah($jum_mod_tanah * -1) . ')' : rupiah($jum_mod_tanah) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_mod_mesin < 0 ? '(' . rupiah($jum_mod_mesin * -1) . ')' : rupiah($jum_mod_mesin) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_mod_gedung < 0 ? '(' . rupiah($jum_mod_gedung * -1) . ')' : rupiah($jum_mod_gedung) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_mod_jalan < 0 ? '(' . rupiah($jum_mod_jalan * -1) . ')' : rupiah($jum_mod_jalan) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_mod_tetap < 0 ? '(' . rupiah($jum_mod_tetap * -1) . ')' : rupiah($jum_mod_tetap) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_mod_lainnya < 0 ? '(' . rupiah($jum_mod_lainnya * -1) . ')' : rupiah($jum_mod_lainnya) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$tot_mod < 0 ? '(' . rupiah($tot_mod * -1) . ')' : rupiah($tot_mod) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_aset_persediaan < 0 ? '(' . rupiah($jum_aset_persediaan * -1) . ')' : rupiah($jum_aset_persediaan) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_aset_tanah < 0 ? '(' . rupiah($jum_aset_tanah * -1) . ')' : rupiah($jum_aset_tanah) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_aset_mesin < 0 ? '(' . rupiah($jum_aset_mesin * -1) . ')' : rupiah($jum_aset_mesin) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_aset_gedung < 0 ? '(' . rupiah($jum_aset_gedung * -1) . ')' : rupiah($jum_aset_gedung) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_aset_jalan < 0 ? '(' . rupiah($jum_aset_jalan * -1) . ')' : rupiah($jum_aset_jalan) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_aset_tetap < 0 ? '(' . rupiah($jum_aset_tetap * -1) . ')' : rupiah($jum_aset_tetap) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_aset_kontruksi < 0 ? '(' . rupiah($jum_aset_kontruksi * -1) . ')' : rupiah($jum_aset_kontruksi) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_aset_takwujud < 0 ? '(' . rupiah($jum_aset_takwujud * -1) . ')' : rupiah($jum_aset_takwujud) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$jum_aset_lainnya < 0 ? '(' . rupiah($jum_aset_lainnya * -1) . ')' : rupiah($jum_aset_lainnya) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$tot_aset < 0 ? '(' . rupiah($tot_aset * -1) . ')' : rupiah($tot_aset) }}</td> 
                <td align="right" valign="top" style="font-size:12px">{{$tot_selisih < 0 ? '(' . rupiah($tot_selisih * -1) . ')' : rupiah($tot_selisih) }}</td> 
                <td align="right" valign="top" style="font-size:12px">&nbsp;</td> 
        </tr>
</body>
</html>