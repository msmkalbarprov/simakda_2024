<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - Ringkasan LK</title>
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
            <TD align="center" ><b>RINGKASAN LAPORAN KEUANGAN</TD>
        </TR>
    </TABLE>
    <br/>
    <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="10" align=center> 
        <TR>
            <TD align="justify">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Laporan Keuangan  {{$nm_skpd}} Provinsi Kalimantan Barat Tahun Anggaran {{$thn_ang}}  ini telah disusun dan disajikan sesuai dengan Peraturan Pemerintah Nomor 71 Tahun 2010 tentang Standar Akuntansi Pemerintahan (SAP) dan berdasarkan kaidah-kaidah pengelolaan keuangan di lingkungan Pemerintahan Provinsi Kalimantan Barat. :
                <br>
                <br>
                Laporan Keuangan ini meliputi:
            </TD>
        </TR>
    </TABLE>
    <br>

    <TABLE style="border-collapse:collapse;{{$spasi}}" width="100%" border="0" cellspacing="0" cellpadding="0" align=center> 
        <TR>
            <TD align="left" width="5%"><b>I.</TD>
            <TD align="left"><b>Laporan Realisasi Anggaran</TD>
        </TR>
        <TR>
            <TD align="left" width="5%">&nbsp;</TD>
            <TD align="justify">Laporan Realisasi Anggaran menggambarkan perbandingan antara anggaran dengan realisasinya, yang mencakup unsur-unsur Pendapatan-LRA dan Belanja selama periode 1 Januari sampai dengan {{$tanggal}}</TD>
        </TR>
        <TR>
            @php
                $ang_pend=$jum_pend->anggaran;
                $real_pend=$jum_pend->nilai;
                if ($ang_pend==0 || $ang_pend==""){
                    $persen_pend=0;
                }else{
                    $persen_pend=$real_pend/$ang_pend *100;
                }
                
                $ang_bel=$jum_bel->anggaran;
                $real_bel=$jum_bel->nilai;
                if ($ang_bel==0 || $ang_bel==""){
                    $persen_bel=0;
                }else{
                    $persen_bel=$real_bel/$ang_bel *100;
                }
            @endphp
            <TD align="left" width="5%">&nbsp;</TD>
            <TD align="justify">Realisasi Pendapatan Daerah Tahun Anggaran {{$thn_ang}} ditargetkan sebesar Rp. {{rupiah($jum_pend->anggaran)}} terealisasi sebesar Rp. {{rupiah($jum_pend->nilai)}} atau mencapai {{rupiah($persen_pend)}}% dan Realisasi Belanja Daerah pada Tahun Anggaran {{$thn_ang}} adalah sebesar Rp. {{rupiah($real_bel)}} atau mencapai {{rupiah($persen_bel)}}% dari alokasi anggaran sebesar Rp. {{rupiah($ang_bel)}}</TD>
        </TR>
        <TR>
            <TD align="left" width="5%"><b>&nbsp;</TD>
            <TD align="left">&nbsp;</TD>
        </TR>
        <TR>
            <TD align="left" width="5%"><b>II.</TD>
            <TD align="left"><b>Laporan Operasional</TD>
        </TR>
        <TR>
            <TD align="left" width="5%">&nbsp;</TD>
            <TD align="justify">Laporan Operasional menyajikan berbagai unsur pendapatan-LO, beban, surplus/defisit dari operasi, surplus/defisit dari kegiatan nonoperasional, dan  surplus/defisit-LO, yang diperlukan untuk penyajian yang wajar.</TD>
        </TR>
        <TR>
            <TD align="left" width="5%">&nbsp;</TD>
            <TD align="justify">Laporan Operasional untuk tahun yang berakhir sampai dengan  {{$tanggal}} terdiri dari Pendapatan-LO sebesar {{rupiah($jum_pend_lo->nilai)}}, beban sebesar  {{rupiah($jum_beban_lo->nilai)}} sehingga terdapat Surplus/Defisit Kegiatan Operasional sebesar {{rupiah($jum_surdef_lo->nilai)}}. </TD>
        </TR>
        <TR>
            <TD align="left" width="5%"><b>&nbsp;</TD>
            <TD align="left">&nbsp;</TD>
        </TR>
        <TR>
            <TD align="left" width="5%"><b>III.</TD>
            <TD align="left"><b>Neraca</TD>
        </TR>
        <TR>
            <TD align="left" width="5%">&nbsp;</TD>
            <TD align="justify">Neraca menggambarkan posisi keuangan entitas mengenai aset, kewajiban, dan ekuitas pada {{$tanggal}}</TD>
        </TR>
        <TR>
            <TD align="left" width="5%">&nbsp;</TD>
            <TD align="justify">Nilai Aset per  {{$tanggal}} dicatat dan disajikan sebesar Rp. {{rupiah($aset->nilai)}} yang terdiri dari: Aset Lancar sebesar Rp. {{rupiah($aset_lancar->nilai)}} Aset Tetap sebesar Rp. {{rupiah($aset_tetap->nilai)}} Aset Lainnya sebesar Rp. {{rupiah($aset_lainnya->nilai)}}.</TD>
        </TR>
        <TR>
            <TD align="left" width="5%">&nbsp;</TD>
            <TD align="justify">Nilai Kewajiban sebesar Rp. {{rupiah($kewajiban->nilai)}} dan Nilai Ekuitas sebesar Rp. {{rupiah($ekuitas_rkppkd->nilai)}}, sehingga jumlah kewajiban dan ekuitas adalah sebesar Rp. {{rupiah($kewajiban->nilai+$ekuitas_rkppkd->nilai)}}</TD>
        </TR>
        <TR>
            <TD align="left" width="5%"><b>&nbsp;</TD>
            <TD align="left">&nbsp;</TD>
        </TR>
        <TR>
            <TD align="left" width="5%"><b>IV.</TD>
            <TD align="left"><b>Laporan Perubahan Ekuitas</TD>
        </TR>
        <TR>
            <TD align="left" width="5%">&nbsp;</TD>
            <TD align="justify">Laporan Perubahan Ekuitas menyajikan informasi kenaikan atau penurunan ekuitas tahun pelaporan dibandingkan dengan tahun sebelumnya.</TD>
        </TR>
        <TR>
            <TD align="left" width="5%">&nbsp;</TD>
            <TD align="justify">Ekuitas per 1 Januari {{$thn_ang}} adalah sebesar Rp. {{rupiah($ekuitas_awal->nilai)}} ditambah Defisit-LO sebesar   Rp. {{rupiah($surplus_lo3->nilai)}} kemudian ditambah/dikurangi dengan Lain-lain sebesar Rp. {{rupiah($lain->nilai)}} sehingga Ekuitas Akhir entitas {{$nm_skpd}} Provinsi Kalimantan Barat per  {{$tanggal}} adalah sebesar Rp. {{rupiah($ekuitas_tanpa_rkppkd->nilai)}}.</TD>
        </TR>
        <TR>
            <TD align="left" width="5%"><b>&nbsp;</TD>
            <TD align="left">&nbsp;</TD>
        </TR>
        <TR>
            <TD align="left" width="5%"><b>V.</TD>
            <TD align="left"><b>Catatan atas Laporan Keuangan</TD>
        </TR>
        <TR>
            <TD align="left" width="5%">&nbsp;</TD>
            <TD align="justify">Catatan atas Laporan Keuangan (CaLK) menyajikan informasi tentang penjelasan atau daftar terinci atau analisis atas nilai suatu pos yang disajikan dalam Laporan Realisasi Anggaran, Neraca, Laporan Operasional, dan Laporan Perubahan Ekuitas. Termasuk pula dalam CaLK adalah penyajian informasi yang diharuskan dan dianjurkan oleh Standar Akuntansi Pemerintahan serta pengungkapan-pengungkapan lainnya yang diperlukan untuk penyajian yang wajar atas laporan keuangan.</TD>
        </TR>
        <TR>
            <TD align="left" width="5%">&nbsp;</TD>
            <TD align="justify"><BR>Dalam penyajian Laporan Realisasi Anggaran untuk tahun yang berakhir sampai dengan {{$tanggal}} disusun dan disajikan berdasarkan basis kas. Sedangkan Neraca, Laporan Operasional, dan Laporan Perubahan Ekuitas untuk Tahun {{$thn_ang}} disusun dan disajikan dengan menggunakan basis akrual.
            </TD>
        </TR>
        
    </TABLE></br>
    
</body>

</html>
