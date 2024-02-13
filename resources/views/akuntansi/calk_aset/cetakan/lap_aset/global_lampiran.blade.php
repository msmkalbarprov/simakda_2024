<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Aset Global</title>
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
        @if($rek3=="1112")
            <thead>
                <tr>
                    <td width="5%" align="center" style="font-size:12px">Kode</td>
                    <td width="40%" align="center" style="font-size:12px">Nama Unit Kerja</td>
                    <td width="10%" align="center" style="font-size:12px">{{$thn_ang_1}}</td>
                    <td width="10%" align="center" style="font-size:12px">Tambah</td>
                    <td width="10%" align="center" style="font-size:12px">Kurang</td>
                    <td width="10%" align="center" style="font-size:12px">Pengadaan {{$thn_ang}}</td>
                    <td width="10%" align="center" style="font-size:12px">Koreksi</td>
                    <td width="10%" align="center" style="font-size:12px">{{$thn_ang}}</td>
                    
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
            @php
                $tot_awal=0;
                $tot_tambah=0;
                $tot_kurang=0;
                $tot_tahun_n=0;
                $tot_koreksi=0;
                $tot_total=0;
            @endphp
            @foreach($query as $row)
                @php
                    $kode = $row->kd_skpd;
                    $nama = $row->nm_skpd;
                    $koreksi = $row->koreksi;
                    $sal_awal = $row->sal_awal;
                    $tambah = $row->tambah;
                    $kurang = $row->kurang;
                    $tahun_n = $row->tahun_n;
                    $tot = $sal_awal+$tambah-$kurang+$tahun_n+$koreksi;
                    $tot_awal=$tot_awal+$sal_awal;
                    $tot_tambah=$tot_tambah+$tambah;
                    $tot_kurang=$tot_kurang+$kurang;
                    $tot_tahun_n=$tot_tahun_n+$tahun_n;
                    $tot_koreksi=$tot_koreksi+$koreksi;
                    $tot_total=$tot_total+$tot;
                @endphp
                <tr>
                   <td align="left" valign="top" style="font-size:12px">{{$kode}}</td> 
                   <td align="left" style="font-size:12px">{{$nama}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($sal_awal)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($tambah)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($kurang)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($tahun_n)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($koreksi)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($tot)}}</td> 
                </tr>
            @endforeach
            <tr>
               <td colspan="2" align="center" valign="top" style="font-size:12px">TOTAL</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_awal)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_tambah)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_kurang)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_tahun_n)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_koreksi)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_total)}}</td> 
            </tr>
        @elseif($rek3==1503 || $rek3==1504)
            <thead>
                <tr>
                    <td width="5%" align="center" style="font-size:12px">Kode</td>
                    <td width="40%" align="center" style="font-size:12px">Nama Unit Kerja</td>
                    <td width="10%" align="center" style="font-size:12px">{{$thn_ang_1}}</td>
                    <td width="10%" align="center" style="font-size:12px">Tambah</td>
                    <td width="10%" align="center" style="font-size:12px">Kurang</td>
                    <td width="10%" align="center" style="font-size:12px">Pengadaan {{$thn_ang}}</td>
                    <td width="10%" align="center" style="font-size:12px">{{$thn_ang}}</td>
                    
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
                $tot_awal=0;
                $tot_tambah=0;
                $tot_kurang=0;
                $tot_tahun_n=0;
                $tot_2020=0;
            @endphp
            @foreach($query as $row)
                @php
                    $kode = $row->kd_skpd;
                    $nama = $row->nm_skpd;
                    $sal_awal = $row->sal_awal;
                    $tambah = $row->tambah;
                    $kurang = $row->kurang;
                    $tahun_n = $row->tahun_n;
                    $tahun_      = $row->thn_berjalan;

                    $tot_awal=$tot_awal+$sal_awal;
                    $tot_tambah=$tot_tambah+$tambah;
                    $tot_kurang=$tot_kurang+$kurang;
                    $tot_tahun_n=$tot_tahun_n+$tahun_n;
                    $tot_2020=$tot_2020+$tahun_;
                @endphp
                <tr>
                   <td align="left" valign="top" style="font-size:12px">{{$kode}}</td> 
                   <td align="left" style="font-size:12px">{{$nama}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($sal_awal)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($tambah)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($kurang)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($tahun_n)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($tahun_)}}</td> 
                </tr>
            @endforeach
            <tr>
               <td colspan="2" align="center" valign="top" style="font-size:12px">TOTAL</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_awal)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_tambah)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_kurang)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_tahun_n)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_2020)}}</td> 
            </tr>
        @else
            <thead>
                <tr>
                    <td width="5%" align="center" style="font-size:12px">Kode</td>
                    <td width="40%" align="center" style="font-size:12px">Nama Unit Kerja</td>
                    <td width="10%" align="center" style="font-size:12px">{{$thn_ang_1}}</td>
                    <td width="10%" align="center" style="font-size:12px">Tambah</td>
                    <td width="10%" align="center" style="font-size:12px">Kurang</td>
                    <td width="10%" align="center" style="font-size:12px">Pengadaan {{$thn_ang}}</td>
                    <td width="10%" align="center" style="font-size:12px">{{$thn_ang}}</td>
                    
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
                $tot_awal=0;
                $tot_tambah=0;
                $tot_kurang=0;
                $tot_tahun_n=0;
                $tot_total=0;
            @endphp
            @foreach($query as $row)
                @php
                    $kode = $row->kd_skpd;
                    $nama = $row->nm_skpd;
                    $sal_awal = $row->sal_awal;
                    $tambah = $row->tambah;
                    $kurang = $row->kurang;
                    $tahun_n = $row->tahun_n;
                    $tot = $sal_awal+$tambah-$kurang+$tahun_n;
                    $tot_awal=$tot_awal+$sal_awal;
                    $tot_tambah=$tot_tambah+$tambah;
                    $tot_kurang=$tot_kurang+$kurang;
                    $tot_tahun_n=$tot_tahun_n+$tahun_n;
                    $tot_total=$tot_total+$tot;
                @endphp
                <tr>
                   <td align="left" valign="top" style="font-size:12px">{{$kode}}</td> 
                   <td align="left" style="font-size:12px">{{$nama}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($sal_awal)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($tambah)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($kurang)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($tahun_n)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($tot)}}</td> 
                </tr>
            @endforeach
            <tr>
               <td colspan="2" align="center" valign="top" style="font-size:12px">TOTAL</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_awal)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_tambah)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_kurang)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_tahun_n)}}</td> 
               <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_total)}}</td> 
            </tr>
        @endif
    </table>
</body>
</html>