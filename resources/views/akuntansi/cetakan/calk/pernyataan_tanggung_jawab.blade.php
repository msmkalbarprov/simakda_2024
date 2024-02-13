<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - Pernyataan Tanggung Jawab</title>
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
{{-- <body> --}}
    {{-- isi --}}
    <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD align="center" ><b>PERNYATAAN TANGGUNG JAWAB</TD>
        </TR>
    </TABLE><br/>
    <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="1" align=center> 
        <TR>
            <TD align="justify">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Laporan Keuangan {{$nm_skpd}} Provinsi Kalimantan Barat yang terdiri dari: (a) Laporan Realisasi Anggaran, (b) Laporan Operasional, (c) Neraca, (d) Laporan Perubahan Ekuitas, dan (e) Catatan atas Laporan Keuangan Tahun Anggaran {{$thn_ang}} sebagaimana terlampir adalah merupakan tanggung jawab kami.<br><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Laporan Keuangan tersebut telah disusun berdasarkan sistem pengendalian intern yang memadai, dan isinya telah menyajikan informasi pelaksanaan anggaran dan posisi keuangan dan Catatan atas Laporan Keuangan secara layak sesuai dengan Standar Akuntansi Pemerintahan.
            </TD>
        </TR>
    </TABLE></br>
    {{-- tanda tangan --}}
        <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="1" align=center> 
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center">{{$tempat_tanggal}}</TD>
            </TR>                   
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center">{{$ttd_nih->jabatan}}</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center">&nbsp;</TD>
                <TD width="50%" align="center">&nbsp;</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center">&nbsp;</TD>
                <TD width="50%" align="center">&nbsp;</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center">&nbsp;</TD>
                <TD width="50%" align="center">&nbsp;</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center"><b><u>{{$ttd_nih->nama}}</u></b></TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center">{{$ttd_nih->pangkat}}</TD> 
            </TR>                   
            <TR>
                <TD width="50%" align="center"></TD>
                <TD width="50%" align="center">{{$ttd_nih->nip}}</TD> 
            </TR>
        </TABLE>
</body>

</html>
