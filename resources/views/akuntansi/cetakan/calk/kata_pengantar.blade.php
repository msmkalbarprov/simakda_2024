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
            <TD align="center" ><b>KATA PENGANTAR</TD>
        </TR>
    </TABLE><br/>
    <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="10" align=center> 
        <TR>
            <TD align="justify">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$nm_skpd}} Provinsi Kalimantan Barat adalah salah satu entitas akuntansi dari Pemerintah Provinsi Kalimantan Barat yang berkewajiban menyelenggarakan akuntansi dan menyampaikan laporan pertanggungjawaban atas pelaksanaan Anggaran Pendapatan dan Belanja Daerah dengan menyusun laporan keuangan berupa Laporan Realisasi Anggaran, Neraca, Laporan Operasional, Laporan Perubahan Ekuitas dan Catatan atas Laporan Keuangan.
                <br>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Penyusunan Laporan Keuangan {{$nm_skpd}} Provinsi Kalimantan Barat disajikan sesuai dengan peraturan pemerintah yang mengatur tentang Standar Akuntansi Pemerintah sebagaimana diamanatkan Undang-undang Nomor 17 tahun 2013 tentang Keuangan Negara, Peraturan Pemerintah Nomor 24 Tahun 2005 yang telah diubah dengan perturan Pemerintah Nomor 71 Tahun 2010 tentang Standar Akuntansi Pemerintahan, Peraturan Pemerintah Nomor 58 Tahun 2005 tentang Pengelolaan Keuangan Daerah dan Peraturan Menteri Dalam Negeri Nomor 13 Tahun 2006 tentang Pedoman Pengelolaan Keuangan Daerah, sebagaimana telah diubah beberapa kali dan terakhir dengan Peraturan Menteri Dalam Negeri Nomor 21 Tahun 2011, dan Peraturan Menteri Dalam Negeri Nomor 64 Tahun 2013 tentang Penerapan Standar Akuntansi Pemerintahan Berbasis Akrual.
                <br>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Laporan Keuangan ini diharapkan dapat memberikan informasi yang berguna kepada para pengguna laporan khususnya sebagai sarana untuk meningkatkan akuntabilitas/ pertanggungjawaban dan transparansi, kami akan terus berupaya untuk dapat menyusun dan menyajikan Laporan Keuangan {{$nm_skpd}} Provinsi Kalimantan Barat yang tepat waktu dan akurat sehingga terwujud tata kelola pemerintahan yang baik (good governance).
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
