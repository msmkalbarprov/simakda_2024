<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lap. Akumulasi Penyusutan</title>
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
        <tr>
            <td align="center"><strong>{{strtoupper((nama_rek4($rek)))}}</strong></td>
        </tr>                         
        <tr>
            <td align="center"><strong>TAHUN {{$thn_ang}}</strong></td>
        </tr>
        <tr>
            <td align="center"><strong>&nbsp;</strong></td>
        </tr>
    </TABLE><br/>
    <table style="border-collapse:collapse;line-height:1.5em;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td rowspan="2" width="5%" align="center"  bgcolor="#CCCCCC"  ><b>NO</b></td>
            <td rowspan="2" width="13%" align="center"  bgcolor="#CCCCCC"  ><b>KODE SKPD</b></td>
            <td rowspan="2" width="13%" align="center"  bgcolor="#CCCCCC"  ><b>NAMA SKPD</b></td>
            <td rowspan="2" width="13%" align="center"  bgcolor="#CCCCCC"  ><b>PER 31 DESEMBER {{$thn_ang_1}}</b></td>
            <td colspan="4" width="13%" align="center" bgcolor="#CCCCCC"   ><b>KOREKSI BERTAMBAG</b></td>
            <td rowspan="2" width="13%" align="center" bgcolor="#CCCCCC"   ><b>JUMLAH KOREKSI BERTAMBAH</b></td>
            <td colspan="6" width="13%" align="center"  bgcolor="#CCCCCC"  ><b>KOREKSI BERKURANG</b></td>
            <td rowspan="2" width="13%" align="center"  bgcolor="#CCCCCC"  ><b>JUMLAH KOREKSI BERKURANG</b></td>
            <td rowspan="2" width="13%" align="center"  bgcolor="#CCCCCC"  ><b>PENYUSUTAN TAHUN {{$thn_ang}}</b></td>
            <td rowspan="2" width="13%" align="center"  bgcolor="#CCCCCC"  ><b>PER 31 DESEMBER {{$thn_ang}}</b></td>

        </tr>
        <tr>
            <td align="center" bgcolor="#CCCCCC" ><b>HIBAH MASUK</b></td> 
            <td align="center" bgcolor="#CCCCCC" ><b>MUTASI MASUK ANTAR SKPD</b></td> 
            <td align="center" bgcolor="#CCCCCC" ><b>REKLAS ANTAR AKUN</b></td> 
            <td align="center" bgcolor="#CCCCCC" ><b>KOREKSI</b></td> 
            <td align="center" bgcolor="#CCCCCC" ><b>HIBAH KELUAR</b></td> 
            <td align="center" bgcolor="#CCCCCC" ><b>MUTASI KELUAR ANTAR SKPD</b></td> 
            <td align="center" bgcolor="#CCCCCC" ><b>REKLAS ANTAR AKUN</b></td> 
            <td align="center" bgcolor="#CCCCCC" ><b>KOREKSI</b></td>
            <td align="center" bgcolor="#CCCCCC" ><b>PENGHAPUSAN</b></td> 
            <td align="center" bgcolor="#CCCCCC" ><b>RUSAK BERAT</b></td>
        </tr>
        @php
            $no=1;
            $tot_sal                = 0;
            $tot_hibah_tambah       = 0;
            $tot_mutasi_tambah      = 0;
            $tot_reklas_tambah      = 0;
            $tot_koreksi_tambah     = 0;
            $tot_jumlah_tambah      = 0;
            $tot_hibah_kurang       = 0;
            $tot_mutasi_kurang      = 0;
            $tot_reklas_kurang      = 0;
            $tot_koreksi_kurang     = 0;
            $tot_penghapusan_kurang = 0;
            $tot_rusak_kurang       = 0;
            $tot_jumlah_kurang      = 0;
            $tot_penyusutan         = 0;
            $tot_total              = 0;
        @endphp
        @foreach($query as $row)
            @php
                $kode_skpd     = $row->kd_skpd;
                $nama_skpd     = $row->nm_skpd;
                $sal    = $row->sal;
                $hibah_tambah   = $row->hibah_tambah;
                $mutasi_tambah = $row->mutasi_tambah;
                $reklas_tambah     = $row->reklas_tambah;
                $koreksi_tambah   = $row->koreksi_tambah;
                $jumlah_tambah   = $row->jumlah_tambah;
                $hibah_kurang   = $row->hibah_kurang;
                $mutasi_kurang = $row->mutasi_kurang;
                $reklas_kurang     = $row->reklas_kurang;
                $koreksi_kurang   = $row->koreksi_kurang;
                $penghapusan_kurang     = $row->penghapusan_kurang;
                $rusak_kurang    = $row->rusak_kurang;
                $jumlah_kurang   = $row->jumlah_kurang;
                $penyusutan     = $row->penyusutan;
                $total    = $row->total;

                $tot_sal                = $tot_sal+$sal;
                $tot_hibah_tambah       = $tot_hibah_tambah+$hibah_tambah;
                $tot_mutasi_tambah      = $tot_mutasi_tambah+$mutasi_tambah;
                $tot_reklas_tambah      = $tot_reklas_tambah+$reklas_tambah;
                $tot_koreksi_tambah     = $tot_koreksi_tambah+$koreksi_tambah;
                $tot_jumlah_tambah      = $tot_jumlah_tambah+$jumlah_tambah;
                $tot_hibah_kurang       = $tot_hibah_kurang+$hibah_kurang;
                $tot_mutasi_kurang      = $tot_mutasi_kurang+$mutasi_kurang;
                $tot_reklas_kurang      = $tot_reklas_kurang+$reklas_kurang;
                $tot_koreksi_kurang     = $tot_koreksi_kurang+$koreksi_kurang;
                $tot_penghapusan_kurang = $tot_penghapusan_kurang+$penghapusan_kurang;
                $tot_rusak_kurang       = $tot_rusak_kurang+$rusak_kurang;
                $tot_jumlah_kurang      = $tot_jumlah_kurang+$jumlah_kurang;
                $tot_penyusutan         = $tot_penyusutan+$penyusutan;
                $tot_total              = $tot_total+$total;
            @endphp
            <tr>
                <td align="center" valign="top">{{$no++}}</td>
                <td align="center" valign="top">{{$kode_skpd}}</td>
                <td align="left" valign="top">{{$nama_skpd}}</td>
                <td align="right" valign="top">{{$sal < 0 ? '(' . rupiah($sal * -1) . ')' : rupiah($sal) }}</td>
                <td align="right" valign="top">{{$hibah_tambah < 0 ? '(' . rupiah($hibah_tambah * -1) . ')' : rupiah($hibah_tambah) }}</td>
                <td align="right" valign="top">{{$mutasi_tambah < 0 ? '(' . rupiah($mutasi_tambah * -1) . ')' : rupiah($mutasi_tambah) }}</td>
                <td align="right" valign="top">{{$reklas_tambah < 0 ? '(' . rupiah($reklas_tambah * -1) . ')' : rupiah($reklas_tambah) }}</td>
                <td align="right" valign="top">{{$koreksi_tambah < 0 ? '(' . rupiah($koreksi_tambah * -1) . ')' : rupiah($koreksi_tambah) }}</td>
                <td align="right" valign="top">{{$jumlah_tambah < 0 ? '(' . rupiah($jumlah_tambah * -1) . ')' : rupiah($jumlah_tambah) }}</td>
                <td align="right" valign="top">{{$hibah_kurang < 0 ? '(' . rupiah($hibah_kurang * -1) . ')' : rupiah($hibah_kurang) }}</td>
                <td align="right" valign="top">{{$mutasi_kurang < 0 ? '(' . rupiah($mutasi_kurang * -1) . ')' : rupiah($mutasi_kurang) }}</td>
                <td align="right" valign="top">{{$reklas_kurang < 0 ? '(' . rupiah($reklas_kurang * -1) . ')' : rupiah($reklas_kurang) }}</td>
                <td align="right" valign="top">{{$koreksi_kurang < 0 ? '(' . rupiah($koreksi_kurang * -1) . ')' : rupiah($koreksi_kurang) }}</td>
                <td align="right" valign="top">{{$penghapusan_kurang < 0 ? '(' . rupiah($penghapusan_kurang * -1) . ')' : rupiah($penghapusan_kurang) }}</td>
                <td align="right" valign="top">{{$rusak_kurang < 0 ? '(' . rupiah($rusak_kurang * -1) . ')' : rupiah($rusak_kurang) }}</td>
                <td align="right" valign="top">{{$jumlah_kurang < 0 ? '(' . rupiah($jumlah_kurang * -1) . ')' : rupiah($jumlah_kurang) }}</td>
                <td align="right" valign="top">{{$penyusutan < 0 ? '(' . rupiah($penyusutan * -1) . ')' : rupiah($penyusutan) }}</td>
                <td align="right" valign="top">{{$total < 0 ? '(' . rupiah($total * -1) . ')' : rupiah($total) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" align="center" valign="top">TOTAL</td>
            <td align="right" valign="top">{{$tot_sal < 0 ? '(' . rupiah($tot_sal * -1) . ')' : rupiah($tot_sal) }}</td>
            <td align="right" valign="top">{{$tot_hibah_tambah < 0 ? '(' . rupiah($tot_hibah_tambah * -1) . ')' : rupiah($tot_hibah_tambah) }}</td>
            <td align="right" valign="top">{{$tot_mutasi_tambah < 0 ? '(' . rupiah($tot_mutasi_tambah * -1) . ')' : rupiah($tot_mutasi_tambah) }}</td>
            <td align="right" valign="top">{{$tot_reklas_tambah < 0 ? '(' . rupiah($tot_reklas_tambah * -1) . ')' : rupiah($tot_reklas_tambah) }}</td>
            <td align="right" valign="top">{{$tot_koreksi_tambah < 0 ? '(' . rupiah($tot_koreksi_tambah * -1) . ')' : rupiah($tot_koreksi_tambah) }}</td>
            <td align="right" valign="top">{{$tot_jumlah_tambah < 0 ? '(' . rupiah($tot_jumlah_tambah * -1) . ')' : rupiah($tot_jumlah_tambah) }}</td>
            <td align="right" valign="top">{{$tot_hibah_kurang < 0 ? '(' . rupiah($tot_hibah_kurang * -1) . ')' : rupiah($tot_hibah_kurang) }}</td>
            <td align="right" valign="top">{{$tot_mutasi_kurang < 0 ? '(' . rupiah($tot_mutasi_kurang * -1) . ')' : rupiah($tot_mutasi_kurang) }}</td>
            <td align="right" valign="top">{{$tot_reklas_kurang < 0 ? '(' . rupiah($tot_reklas_kurang * -1) . ')' : rupiah($tot_reklas_kurang) }}</td>
            <td align="right" valign="top">{{$tot_koreksi_kurang < 0 ? '(' . rupiah($tot_koreksi_kurang * -1) . ')' : rupiah($tot_koreksi_kurang) }}</td>
            <td align="right" valign="top">{{$tot_penghapusan_kurang < 0 ? '(' . rupiah($tot_penghapusan_kurang * -1) . ')' : rupiah($tot_penghapusan_kurang) }}</td>
            <td align="right" valign="top">{{$tot_rusak_kurang < 0 ? '(' . rupiah($tot_rusak_kurang * -1) . ')' : rupiah($tot_rusak_kurang) }}</td>
            <td align="right" valign="top">{{$tot_jumlah_kurang < 0 ? '(' . rupiah($tot_jumlah_kurang * -1) . ')' : rupiah($tot_jumlah_kurang) }}</td>
            <td align="right" valign="top">{{$tot_penyusutan < 0 ? '(' . rupiah($tot_penyusutan * -1) . ')' : rupiah($tot_penyusutan) }}</td>
            <td align="right" valign="top">{{$tot_total < 0 ? '(' . rupiah($tot_total * -1) . ')' : rupiah($tot_total) }}</td>
        </tr>
    </table>
</body>
</html>