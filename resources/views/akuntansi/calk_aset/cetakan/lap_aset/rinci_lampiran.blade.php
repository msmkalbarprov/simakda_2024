<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Aset Rinci</title>
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
        <thead>
            <tr>
                <td width="5%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Kode</td>
                <td width="25%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Nama Unit Kerja</td>
                <td width="5%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Kode Akun</td>
                <td width="15%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Uraian Akun</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">{{$thn_ang_1}}</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Tambah</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Kurang</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Pengadaan {{$thn_ang}}</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">Koreksi</td>
                <td width="10%" bgcolor="#CCCCCC" align="center" style="font-size:12px">{{$thn_ang}}</td>
                
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
            </tr>
        </thead>
        @php
            $tot_salwal=0;
            $tot_tbh=0;
            $tot_krg=0;
            $tot_thn=0;
            $tot_kor=0;
            $tot_tot=0;
        @endphp
        @foreach($query as $row)
            @php
                $kode = $row->kd_skpd;
                $nama = $row->nm_skpd;
                $kd_rek = $row->kd_rek3;
                $nm_rek = $row->nm_rek3;
                $sal_awal = $row->sal_awal;
                $tambah = $row->tambah;
                $kurang = $row->kurang;
                $tahun_n = $row->tahun_n;
                $koreksi = $row->koreksi;
                $tot = $sal_awal+$tambah-$kurang+$tahun_n+$koreksi;

                $len =   strlen($kd_rek);
            @endphp
            @if($len=="4")
                <tr>
                   <td align="center" valign="top" style="font-size:12px"><b>{{$kode}}</b></td> 
                   <td align="left" style="font-size:12px"><b>{{$nama}}</b></td> 
                   <td align="left" style="font-size:12px"><b>{{$kd_rek}}</b></td> 
                   <td align="left" style="font-size:12px"><b>{{$nm_rek}}</b></td>
                   <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($sal_awal)}}</b></td> 
                   <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tambah)}}</b></td> 
                   <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($kurang)}}</b></td> 
                   <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tahun_n)}}</b></td> 
                   <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($koreksi)}}</b></td> 
                   <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot)}}</b></td> 
                </tr>
            @else
                <tr>
                   <td align="center" valign="top" style="font-size:12px">{{$kode}}</td> 
                   <td align="left" style="font-size:12px">{{$nama}}</td> 
                   <td align="left" style="font-size:12px">{{$kd_rek}}</td> 
                   <td align="left" style="font-size:12px">{{$nm_rek}}</td>
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($sal_awal)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($tambah)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($kurang)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($tahun_n)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($koreksi)}}</td> 
                   <td align="right" valign="top" style="font-size:12px">{{rupiah($tot)}}</td> 
                </tr>
            @endif
            @if($len=="12")
                @php
                    $tot_salwal=$tot_salwal+$sal_awal;
                    $tot_tbh=$tot_tbh+$tambah;
                    $tot_krg=$tot_krg+$kurang;
                    $tot_thn=$tot_thn+$tahun_n;
                    $tot_kor=$tot_kor+$koreksi;
                    $tot_tot=$tot_tot+$tot;
                @endphp
            @endif
        @endforeach
        <tr>
           <td colspan="4" align="center" valign="top" style="font-size:12px">TOTAL</td> 
           <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_salwal)}}</b></td> 
           <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_tbh)}}</b></td> 
           <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_krg)}}</b></td> 
           <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_thn)}}</b></td> 
           <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_kor)}}</b></td> 
           <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_tot)}}</b></td> 
        </tr>
    </table>
</body>
</html>