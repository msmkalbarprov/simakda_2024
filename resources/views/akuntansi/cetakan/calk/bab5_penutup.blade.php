<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - Kata Pengantar</title>
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
            <TD align="center" ><b>BAB V. PENUTUP</TD>
        </TR>
    </TABLE><br/>
    <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="10" align=center> 
        <TR>
            <TD align="justify">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Catatan atas Laporan Keuangan {{$nm_skpd}} Provinsi Kalimantan Barat tahun anggaran {{$thn_ang}} ini disusun sebagai tindaklanjut dari Peraturan Pemerintah Nomor 71 Tahun 2010 tentang Standar Akuntansi Pemerintah; Peraturan Menteri Dalam Negeri Nomor 64 Tahun 2013 tentang Penerapan Standar Akuntansi Berbasis Akrual pada Pemerintah Daerah dan Peraturan Gubernur Kalimantan Barat Nomor 15 tahun 2014 tentang  Kebijakan Akuntansi Berbasis Akrual pada Pemerintah Provinsi Kalimantan Barat.<br><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Berdasarkan Peraturan Menteri Dalam Negeri nomor 64 tahun 2013 tentang Penerapan Standar Akuntansi Berbasis Akrual pada Pemerintah Daerah setiap SKPD wajib menyampaikan laporan keuangan akhir tahun terdiri : Laporan Realisasi Anggaran, Neraca, Laporan Operasional, Laporan Perubahan Ekuitas dan Catatan atas Laporan Keuangan.<br><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikian laporan keuangan ini kami susun dengan harapan semoga dapat memenuhi kewajiban akuntabilitas kepada para pihak yang terkait dan semoga dapat menjadi sumber informasi dalam penyusunan laporan keuangan di tingkat Pemerintah Provinsi Kalimantan Barat tahun anggaran {{$thn_ang}}.
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
