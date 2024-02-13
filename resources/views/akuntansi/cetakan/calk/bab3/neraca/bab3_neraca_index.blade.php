<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calk - BAB III NERACA</title>
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
    @if($judul==2)  
        <TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                <TR>
                    <TD align="center" ><b>BAB III PENJELASAN POS-POS LAPORAN KEUANGAN</TD>
                </TR>
        </TABLE><br/>
        <table style="border-collapse:collapse;{{$spasi}}" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
                <td align="left" width="5%"><strong>3.1</strong></td>                         
                <td align="left" colspan="2"><strong>Rincian dan Penjelasan masing-masing pos-pos laporan Keuangan SKPD.</strong></td>                         
            </tr>
            <tr>
                <td align="left" width="5%"><strong>&nbsp;</strong></td>                         
                <td align="left" width="10%"><strong>3.1.3</strong></td>                         
                <td align="left"><strong>Penjelasan atas Neraca</strong></td>                         
            </tr>
            <tr>
                <td align="left" width="5%"><strong>&nbsp;</strong></td>                         
                <td align="left" width="10%"><strong>&nbsp;</strong></td>                         
                <td align="left">Komposisi dan Rasio perbandingan Neraca per 31 Desember {{$thn_ang}} dan per 31 Desember {{$thn_ang_1}} dapat dilihat sebagai berikut :</td>                         
            </tr>
        </table><br>
    @else
    @endif
    <table style="{{$spasi}}" width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
        <tr>
            <td align="left" width="2%" rowspan="2"><strong>&nbsp;</strong></td>                         
            <td style="border-top:solid;;border-bottom:solid;" align="center" width="10%" rowspan="2"><strong>Reff</strong></td>                         
            <td colspan ="3" style="border-top:solid;;border-bottom:solid;" align="center" width="30%" rowspan="2"><strong>Penjelasan Neraca</strong></td>
            <td style="border-top:solid;" align="center" width="15%"><strong>Per 31 Desember {{$thn_ang}}</strong></td>
            <td style="border-top:solid;" align="center" width="13%"><strong>Per 31 Desember {{$thn_ang_1}}</strong></td>
        </tr>
        <tr>
            <td align="center" style="border-bottom:solid;"><strong>(Rp)</strong></td>
            <td align="center" style="border-bottom:solid;"><strong>(Rp)</strong></td>
        </tr>
        <tr>
            <td align="left"><strong>&nbsp;</strong></td>                         
            <td align="center"><strong>&nbsp;</strong></td>                         
            <td style="border-top:solid;" colspan ="3"align="center"><strong>&nbsp;</strong></td>
            <td style="border-top:solid;" align="center"><strong>&nbsp;</strong></td>
            <td style="border-top:solid;" align="center"><strong>&nbsp;</strong></td>
        </tr>
        <!-- 1 Aset-->
            @php
                $jum_aset = 0;
                $jum_aset_lalu = 0;
            @endphp
            @foreach($kode_1 as $k1)
                @php
                    $kd_rek = $k1->kd_rek;
                    $nm_rek = $k1->nm_rek;
                    $realisasi = $k1->realisasi;
                    $real_tlalu = $k1->real_tlalu;
                    $leng = strlen($kd_rek);
                @endphp
                @if($leng==1)
                    @php
                        $jum_aset = $realisasi;
                        $jum_aset_lalu = $real_tlalu;
                    @endphp
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{$kd_rek}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$nm_rek}}</strong></td>
                        <td align="right"><strong>{{rupiah($realisasi)}}</strong></td>
                        <td align="right"><strong>{{rupiah($real_tlalu)}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Aset merupakan salah satu pos yang termuat dalam Neraca Pemerintah Provinsi Kalimantan Barat. Nilai Aset Pemerintah Provinsi Kalimantan Barat per 31 Desember {{$thn_ang}} dan per 31 Desember {{$thn_ang_1}} terdiri dari : <br></td>                         
                    </tr>
                @elseif($leng==2)
                    <tr>
                        <td align="left">&nbsp;</td> 
                        <td align="right">&nbsp;</td>
                        <td colspan ="3" align="left">{{dotrek($kd_rek)}} {{$nm_rek}}</td>
                        <td align="right">{{rupiah($realisasi)}}</td>
                        <td align="right">{{rupiah($real_tlalu)}}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td align="left">&nbsp;</td> 
                <td align="right">&nbsp;</td>
                <td colspan ="3" align="left">Jumlah Aset</td>
                <td style="border-top:solid;;border-bottom:solid;" align="right">{{rupiah($jum_aset)}}</td>
                <td style="border-top:solid;;border-bottom:solid;" align="right">{{rupiah($jum_aset_lalu)}}</td>
            </tr>
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->
        <!-- 1.1 Aset Lancar-->
            @foreach($kode_11 as $k11)
                @php
                    $reff = $k11->reff;
                    $uraian = $k11->uraian;
                    $sal = $k11->sal;
                    $sal_lalu = $k11->sal_lalu;
                    $leng = strlen($reff);
                @endphp
                @if($leng==3)
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{$reff}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian}}</strong></td>
                        <td align="right"><strong>{{rupiah($sal)}}</strong></td>
                        <td align="right"><strong>{{rupiah($sal_lalu)}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Aset lancar terdiri dari kas dan setara kas, dan aset yang diharapkan untuk segera direalisasikan dalam waktu 12 (dua belas) bulan sejak tanggal pelaporan. Rincian dan perbandingan saldo Aset Lancar per 31 Desember {{$thn_ang}} dan per 31 Desember {{$thn_ang_1}} terdiri dari : <br></td>                         
                    </tr>
                @elseif($reff=="")
                    <tr>
                        <td align="left">&nbsp;</td> 
                        <td align="right">&nbsp;</td>
                        <td colspan ="3" align="left">{{$reff}} {{$uraian}}</td>
                        <td style="border-top:solid;;border-bottom:solid;" align="right">{{rupiah($sal)}}</td>
                        <td style="border-top:solid;;border-bottom:solid;" align="right">{{rupiah($sal_lalu)}}</td>
                    </tr>
                @else
                    <tr>
                        <td align="left">&nbsp;</td> 
                        <td align="right">&nbsp;</td>
                        <td colspan ="3" align="left">{{$reff}} {{$uraian}}</td>
                        <td align="right">{{rupiah($sal)}}</td>
                        <td align="right">{{rupiah($sal_lalu)}}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->
        <!-- 1.1.1 & 1.1.2 -->
            @foreach($kode_11 as $k11_12)
                @php
                    $reff = $k11_12->reff;
                    $uraian = $k11_12->uraian;
                    $sal = $k11_12->sal;
                    $sal_lalu = $k11_12->sal_lalu;
                    $leng = strlen($reff);
                    $kd_reff = str_replace(".","","$reff")
                @endphp
                @if($reff=="1.1.1" || $reff=="1.1.2")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{$reff}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian}}</strong></td>
                        <td align="right"><strong>{{rupiah($sal)}}</strong></td>
                        <td align="right"><strong>{{rupiah($sal_lalu)}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">{{$uraian}} merupakan pendapatan daerah yang masih berada di tangan Bendahara Penerimaan dan sampai dengan 31 Desember {{$thn_ang}} belum disetorkan ke Kas Daerah.<br></td>                         
                    </tr>
                    @php
                        $ket_11_12 = collect(DB::select("SELECT x.kd_rek, x.nm_rek, y.ket, y.kd_rinci 
                                    FROM (
                                        SELECT kd_rek, nm_rek 
                                        FROM ket_neraca_calk 
                                        WHERE kd_rek='$kd_reff'
                                    ) x 
                                    LEFT JOIN 
                                    (
                                        SELECT kd_rek, ket, kd_rinci 
                                        FROM isi_neraca_calk 
                                        where $skpd_clause
                                    ) y
                                    on x.kd_rek=y.kd_rek
                                    order by kd_rinci"))->first();
                        $ket_1112 = $ket_11_12->ket;            
                    @endphp
                    @if($jenis=="1")
                        <tr>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="justify" colspan="5" bgcolor="yellow">{{$ket_1112}}</td>
                        </tr>
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_ket('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','{{$kd_reff}}')">Edit Keterangan {{$uraian}}</button>
                            </td>                         
                        </tr>
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','{{$kd_reff}}')">Edit Nilai Rinci</button>
                            </td>                         
                        </tr>
                    @else
                        <tr>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="justify" colspan="5" >{{$ket_1112}}</td>                         
                        </tr>
                    @endif
                    @php
                        $det_11_12 = DB::select("SELECT a.kd_rek, a.nm_rek , isnull((SELECT ket from isi_neraca_calk where kd_rek=a.kd_rek and $skpd_clause ),'')uraian, isnull((SELECT isnull(nilai,0)nilai 
                            from isi_neraca_calk where kd_rek=a.kd_rek and $skpd_clause),0) nilai 
                            from ket_det_neraca_calk a
                            where left(a.kd_rek,3)='$kd_reff'");
                        $no_det_11_12 = 1;
                    @endphp
                    @foreach($det_11_12 as $det_1112)
                        @php
                            $kd_rek_det_11_12 = $det_1112->kd_rek;
                            $nm_rek_det_11_12 = $det_1112->nm_rek;
                            $uraian_det_11_12 = $det_1112->uraian;
                            $nilai_det_11_12 = $det_1112->nilai;
                        @endphp
                        <tr>
                            <td align="left">&nbsp;</td>   
                            <td align="left">&nbsp;</td>                         
                            <td valign="top" align="left">{{$no_det_11_12++}}. {{$nm_rek_det_11_12}}</td>
                            <td colspan ="3" align="left">{{$uraian_det_11_12}}</td>
                            <td align="right">{{rupiah($nilai_det_11_12)}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="center"><strong>&nbsp;</strong></td>                         
                        <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                        <td align="center"><strong>&nbsp;</strong></td>
                    </tr>
                @else
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->
        <!-- 1.1.3 -->
            @foreach($kode_113 as $kd_113)
                @php
                    $reff_113 = $kd_113->reff;
                    $uraian_113 = $kd_113->uraian;
                    $kd_rek_113 = $kd_113->kd_rek;
                    $ketnya_113 = $kd_113->ketnya;
                    $sal_113 = $kd_113->sal;
                    $sal_lalu_113 = $kd_113->sal_lalu;
                    if ($sal_113 < 0){
                        $a_113="("; 
                        $real_sal_113=$sal_113*-1; 
                        $b_113=")";
                    }else {
                        $a_113=""; 
                        $real_sal_113=$sal_113; 
                        $b_113="";
                    }
                    
                    if ($sal_lalu_113 < 0){
                        $c_113="("; $real_sal_lalu_113=$sal_lalu_113*-1; $d_113=")";
                    }else {
                        $c_113=""; $real_sal_lalu_113=$sal_lalu_113; $d_113="";
                    }
                @endphp
                @if($reff_113 =="1.1.3")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{$reff_113}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_113}}</strong></td>
                        <td align="right"><strong>{{$a_113}}{{rupiah($real_sal_113)}}{{$b_113}}</strong></td>
                        <td align="right"><strong>{{$c_113}}{{rupiah($real_sal_lalu_113)}}{{$d_113}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Piutang Pendapatan terdiri dari : <br></td>                         
                    </tr>
                @else
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td valign="top" align="right">{{$reff_113}}</td>                         
                        <td colspan ="3" align="left">{{$uraian_113}}</td>
                        <td align="right">{{$a_113}}{{rupiah($real_sal_113)}}{{$b_113}}</td>
                        <td align="right">{{$c_113}}{{rupiah($real_sal_lalu_113)}}{{$d_113}}</td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">{{$ketnya_113}}<br><br></td>                         
                    </tr>
                    @php
                        $ket_113 = collect(DB::select("SELECT x.kd_rek, x.nm_rek, y.ket, y.kd_rinci FROM (
                                            SELECT kd_rek, nm_rek FROM ket_neraca_calk WHERE kd_rek='$kd_rek_113') x 
                                            LEFT JOIN (
                                            SELECT kd_rek, ket, kd_rinci FROM isi_neraca_calk where kd_skpd='$kd_skpd') y
                                            on x.kd_rek=y.kd_rek
                                            order by kd_rinci"))->first();
                        $ket_rinci_113 = $ket_113->ket;
                    @endphp
                    @if($jenis=="1")
                        <tr>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="justify" colspan="5" bgcolor="yellow">{{$ket_rinci_113}}</td>
                        </tr>
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_ket('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','{{$kd_rek_113}}')">Edit Keterangan {{$uraian_113}}</button>
                            </td>                         
                        </tr>
                    @else
                        <tr>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="justify" colspan="5" >{{$ket_rinci_113}}</td>                         
                        </tr>
                    @endif
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->
        <!-- 1.1.4 -->
            @foreach($kode_114 as $kd_114)
                @php
                    $reff_114 = $kd_114->reff;
                    $uraian_114 = $kd_114->uraian;
                    $sal_114 = $kd_114->sal;
                    $sal_lalu_114 = $kd_114->sal_lalu;
                    if ($sal_114 < 0){
                        $a_114="("; 
                        $real_sal_114=$sal_114*-1; 
                        $b_114=")";
                    }else {
                        $a_114=""; 
                        $real_sal_114=$sal_114; 
                        $b_114="";
                    }
                    
                    if ($sal_lalu_114 < 0){
                        $c_114="("; $real_sal_lalu_114=$sal_lalu_114*-1; $d_114=")";
                    }else {
                        $c_114=""; $real_sal_lalu_114=$sal_lalu_114; $d_114="";
                    }                
                @endphp
                @if($reff_114 =="1.1.4")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{$reff_114}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_114}}</strong></td>
                        <td align="right"><strong>{{$a_114}}{{rupiah($real_sal_114)}}{{$b_114}}</strong></td>
                        <td align="right"><strong>{{$c_114}}{{rupiah($real_sal_lalu_114)}}{{$d_114}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Piutang Lainnya adalah Piutang Bagian Lancar Tagihan Penjualan Angsuran terdiri dari Pembayaran Angsuran Rumah Dinas Provinsi Kalimantan Barat dan Angsuran Kendaraan Dinas Provinsi Kalimantan Barat.<br></td>                         
                    </tr>
                @elseif($reff_114=="")
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td align="center">&nbsp;</td>                         
                        <td colspan ="3" align="left">{{$reff_114}} {{$uraian_114}}</td>
                        <td style="border-top:solid;;border-bottom:solid;" align="right">{{$a_114}}{{rupiah($real_sal_114)}}{{$b_114}}</td>
                        <td style="border-top:solid;;border-bottom:solid;" align="right">{{$c_114}}{{rupiah($real_sal_lalu_114)}}{{$d_114}}</td>
                    </tr>
                @else
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td valign="top" align="right">{{$reff_114}}</td>                         
                        <td colspan ="3" align="left">{{$uraian_114}}</td>
                        <td align="right">{{$a_114}}{{rupiah($real_sal_114)}}{{$b_114}}</td>
                        <td align="right">{{$c_114}}{{rupiah($real_sal_lalu_114)}}{{$d_114}}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->
        <!-- 1.1.5 -->
            @foreach($kode_115 as $kd_115)
                @php
                    $reff_115 = $kd_115->reff;
                    $uraian_115 = $kd_115->uraian;
                    $sal_115 = $kd_115->sal;
                    $sal_lalu_115 = $kd_115->sal_lalu;
                    if ($sal_115 < 0){
                        $a_115="("; 
                        $real_sal_115=$sal_115*-1; 
                        $b_115=")";
                    }else {
                        $a_115=""; 
                        $real_sal_115=$sal_115; 
                        $b_115="";
                    }
                    
                    if ($sal_lalu_115 < 0){
                        $c_115="("; $real_sal_lalu_115=$sal_lalu_115*-1; $d_115=")";
                    }else {
                        $c_115=""; $real_sal_lalu_115=$sal_lalu_115; $d_115="";
                    }                
                @endphp
                @if($reff_115 =="1.1.5")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{$reff_115}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_115}}</strong></td>
                        <td align="right"><strong>{{$a_115}}{{rupiah($real_sal_115)}}{{$b_115}}</strong></td>
                        <td align="right"><strong>{{$c_115}}{{rupiah($real_sal_lalu_115)}}{{$d_115}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Penyisihan Piutang Pendapatan terdiri dari penyisihan piutang pajak dan penyisihan piutang retribusi.<br></td>                         
                    </tr>
                @elseif($reff_115=="1")
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td align="center">&nbsp;</td>                         
                        <td colspan ="3" align="left">{{$reff_115}} {{$uraian_115}}</td>
                        <td align="right">{{$a_115}}{{rupiah($real_sal_115)}}{{$b_115}}</td>
                        <td align="right">{{$c_115}}{{rupiah($real_sal_lalu_115)}}{{$d_115}}</td>
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="justify" colspan="5">Penggolongan Kualitas Piutang Pajak dengan ketentuan:</td>                         
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left"width="3%">a.</td>
                        <td align="justify" colspan="4">Kualitas Piutang Lancar; dengan kriteria umur piutang kurang dari 1 tahun; Taksiran Piutang Tak Tertagih 0,5%.</td>                         
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left"width="3%">b.</td>
                        <td align="justify" colspan="4">Kualitas Piutang Kurang Lancar; dengan kriteria umur piutang 1 sampai dengan 2 tahun; Taksiran Piutang Tak Tertagih 10 %.</td>                         
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left"width="3%">c.</td>
                        <td align="justify" colspan="4">Kualitas Piutang Diragukan; dengan kriteria umur piutang 2 sampai dengan 5 tahun; Taksiran Piutang Tak Tertagih 50 %.</td>                         
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left"width="3%">d.</td>
                        <td align="justify" colspan="4">Kualitas Piutang Macet. dengan kriteria umur piutang diatas 5 tahun; Taksiran Piutang Tak Tertagih 100 %.<br></td>                         
                    </tr>
                @elseif($reff_115=="2")
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td valign="top" align="right">{{$reff_115}}</td>                         
                        <td colspan ="3" align="left">{{$uraian_115}}</td>
                        <td align="right">{{$a_115}}{{rupiah($real_sal_115)}}{{$b_115}}</td>
                        <td align="right">{{$c_115}}{{rupiah($real_sal_lalu_115)}}{{$d_115}}</td>
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="justify" colspan="5">Penggolongan Kualitas Piutang Bukan Pajak Khusus untuk objek Retribusi, dapat dipilah berdasarkan karakteristik sebagai berikut:</td>                         
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left"width="3%">a.</td>
                        <td align="justify" colspan="4">Kualitas Lancar, jika umur piutang 0 sampai dengan 1 bulan; Taksiran Piutang Tak Tertagih 0,5%.</td>                         
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left"width="3%">b.</td>
                        <td align="justify" colspan="4">Kualitas Kurang Lancar, jika umur piutang 1 sampai dengan 3 bulan; Taksiran Piutang Tak Tertagih 10 %.</td>                         
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left"width="3%">c.</td>
                        <td align="justify" colspan="4">Kualitas Diragukan, jika umur piutang 3 sampai dengan 12 bulan; Taksiran Piutang Tak Tertagih 50 %.</td>                         
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left"width="3%">d.</td>
                        <td align="justify" colspan="4">Kualitas Macet, jika umur piutang lebih dari 12  bulan; Taksiran Piutang Tak Tertagih 100 %.<br></td>                         
                    </tr>
                @elseif($reff_115=="3")
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td valign="top" align="right">{{$reff_115}}</td>                         
                        <td colspan ="3" align="left">{{$uraian_115}}</td>
                        <td align="right">{{$a_115}}{{rupiah($real_sal_115)}}{{$b_115}}</td>
                        <td align="right">{{$c_115}}{{rupiah($real_sal_lalu_115)}}{{$d_115}}</td>
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="justify" colspan="5">Penggolongan Kualitas Piutang Bukan Pajak Khusus untuk objek Lain-lain PAD yang Sah, dapat dipilah berdasarkan karakteristik sebagai berikut:</td>                         
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left"width="3%">a.</td>
                        <td align="justify" colspan="4">Kualitas Piutang Lancar; dengan kriteria umur piutang kurang dari 1 tahun; Taksiran Piutang Tak Tertagih 0,5%.</td>                         
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left"width="3%">b.</td>
                        <td align="justify" colspan="4">Kualitas Piutang Kurang Lancar; dengan kriteria umur piutang 1 sampai dengan 2 tahun; Taksiran Piutang Tak Tertagih 10 %.</td>                         
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left"width="3%">c.</td>
                        <td align="justify" colspan="4">Kualitas Piutang Diragukan; dengan kriteria umur piutang 2 sampai dengan 5 tahun; Taksiran Piutang Tak Tertagih 50 %.</td>                         
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                        <td align="left"width="3%">d.</td>
                        <td align="justify" colspan="4">Kualitas Piutang Macet. dengan kriteria umur piutang diatas 5 tahun; Taksiran Piutang Tak Tertagih 100 %.<br></td>                         
                    </tr>
                @else
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->
        <!-- 1.1.6 -->
            @foreach($kode_116 as $kd_116)
                @php
                    $reff_116 = $kd_116->reff;
                    $uraian_116 = $kd_116->uraian;
                    $sal_116 = $kd_116->sal;
                    $sal_lalu_116 = $kd_116->sal_lalu;
                    if ($sal_116 < 0){
                        $a_116="("; 
                        $real_sal_116=$sal_116*-1; 
                        $b_116=")";
                    }else {
                        $a_116=""; 
                        $real_sal_116=$sal_116; 
                        $b_116="";
                    }
                    
                    if ($sal_lalu_116 < 0){
                        $c_116="("; $real_sal_lalu_116=$sal_lalu_116*-1; $d_116=")";
                    }else {
                        $c_116=""; $real_sal_lalu_116=$sal_lalu_116; $d_116="";
                    }                
                @endphp
                @if($reff_116 =="1.1.6")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{$reff_116}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_116}}</strong></td>
                        <td align="right"><strong>{{$a_116}}{{rupiah($real_sal_116)}}{{$b_116}}</strong></td>
                        <td align="right"><strong>{{$c_116}}{{rupiah($real_sal_lalu_116)}}{{$d_116}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Piutang Lainnya adalah Piutang Bagian Lancar Tagihan Penjualan Angsuran terdiri dari Pembayaran Angsuran Rumah Dinas Provinsi Kalimantan Barat dan Angsuran Kendaraan Dinas Provinsi Kalimantan Barat.<br></td>                         
                    </tr>
                @elseif($reff_116=="")
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td align="center">&nbsp;</td>                         
                        <td colspan ="3" align="left">{{$reff_116}} {{$uraian_116}}</td>
                        <td style="border-top:solid;;border-bottom:solid;" align="right">{{$a_116}}{{rupiah($real_sal_116)}}{{$b_116}}</td>
                        <td style="border-top:solid;;border-bottom:solid;" align="right">{{$c_116}}{{rupiah($real_sal_lalu_116)}}{{$d_116}}</td>
                    </tr>
                @else
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td valign="top" align="right">{{$reff_116}}</td>                         
                        <td colspan ="3" align="left">{{$uraian_116}}</td>
                        <td align="right">{{$a_116}}{{rupiah($real_sal_116)}}{{$b_116}}</td>
                        <td align="right">{{$c_116}}{{rupiah($real_sal_lalu_116)}}{{$d_116}}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->
        <!-- 1.1.7 -->
            @foreach($kode_117 as $kd_117)
                @php
                    $reff_117 = $kd_117->reff;
                    $uraian_117 = $kd_117->uraian;
                    $sal_117 = $kd_117->sal;
                    $sal_lalu_117 = $kd_117->sal_lalu;
                    if ($sal_117 < 0){
                        $a_117="("; 
                        $real_sal_117=$sal_117*-1; 
                        $b_117=")";
                    }else {
                        $a_117=""; 
                        $real_sal_117=$sal_117; 
                        $b_117="";
                    }
                    
                    if ($sal_lalu_117 < 0){
                        $c_117="("; $real_sal_lalu_117=$sal_lalu_117*-1; $d_117=")";
                    }else {
                        $c_117=""; $real_sal_lalu_117=$sal_lalu_117; $d_117="";
                    }                
                @endphp
                @if($reff_117 =="1.1.7")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{$reff_117}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_117}}</strong></td>
                        <td align="right"><strong>{{$a_117}}{{rupiah($real_sal_117)}}{{$b_117}}</strong></td>
                        <td align="right"><strong>{{$c_117}}{{rupiah($real_sal_lalu_117)}}{{$d_117}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Piutang Lainnya adalah Piutang Bagian Lancar Tagihan Penjualan Angsuran terdiri dari Pembayaran Angsuran Rumah Dinas Provinsi Kalimantan Barat dan Angsuran Kendaraan Dinas Provinsi Kalimantan Barat.<br></td>                         
                    </tr>
                @elseif($reff_117=="")
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td align="center">&nbsp;</td>                         
                        <td colspan ="3" align="left">{{$reff_117}} {{$uraian_117}}</td>
                        <td style="border-top:solid;;border-bottom:solid;" align="right">{{$a_117}}{{rupiah($real_sal_117)}}{{$b_117}}</td>
                        <td style="border-top:solid;;border-bottom:solid;" align="right">{{$c_117}}{{rupiah($real_sal_lalu_117)}}{{$d_117}}</td>
                    </tr>
                @else
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td valign="top" align="right">{{$reff_117}}</td>                         
                        <td colspan ="3" align="left">{{$uraian_117}}</td>
                        <td align="right">{{$a_117}}{{rupiah($real_sal_117)}}{{$b_117}}</td>
                        <td align="right">{{$c_117}}{{rupiah($real_sal_lalu_117)}}{{$d_117}}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.2 -->
            @foreach($kode_12 as $kd_12)
                @php
                    $reff_12 = $kd_12->reff;
                    $uraian_12 = $kd_12->uraian;
                    $sal_12 = $kd_12->sal;
                    $sal_lalu_12 = $kd_12->sal_lalu;
                    if ($sal_12 < 0){
                        $a_12="("; 
                        $real_sal_12=$sal_12*-1; 
                        $b_12=")";
                    }else {
                        $a_12=""; 
                        $real_sal_12=$sal_12; 
                        $b_12="";
                    }
                    
                    if ($sal_lalu_12 < 0){
                        $c_12="("; $real_sal_lalu_12=$sal_lalu_12*-1; $d_12=")";
                    }else {
                        $c_12=""; $real_sal_lalu_12=$sal_lalu_12; $d_12="";
                    }                
                @endphp
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="left"><strong>{{$reff_12}}</strong></td>                         
                    <td colspan ="3" align="left"><strong>{{$uraian_12}}</strong></td>
                    <td align="right"><strong>{{$a_12}}{{rupiah($real_sal_12)}}{{$b_12}}</strong></td>
                    <td align="right"><strong>{{$c_12}}{{rupiah($real_sal_lalu_12)}}{{$d_12}}</strong></td>
                </tr>
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>
                    <td align="left"><strong>&nbsp;</strong></td>
                    <td align="justify" colspan="5">Investasi Jangka Panjang adalah investasi yang dimaksudkan untuk dimiliki lebih dari 12 (dua belas) bulan.<br></td>                         
                </tr>
            
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->


        <!-- 1.3 -->
            @foreach($kode_13 as $kd_13)
                @php
                    $kd_rek_13 = $kd_13->kd_rek;
                    $uraian_13 = $kd_13->uraian;
                    $sal_13 = $kd_13->sal;
                    $sal_lalu_13 = $kd_13->sal_lalu;
                    if ($sal_13 < 0){
                        $a_13="("; 
                        $real_sal_13=$sal_13*-1; 
                        $b_13=")";
                    }else {
                        $a_13=""; 
                        $real_sal_13=$sal_13; 
                        $b_13="";
                    }
                    
                    if ($sal_lalu_13 < 0){
                        $c_13="("; $real_sal_lalu_13=$sal_lalu_13*-1; $d_13=")";
                    }else {
                        $c_13=""; $real_sal_lalu_13=$sal_lalu_13; $d_13="";
                    }                
                @endphp
                @if($kd_rek_13 =="13")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_13)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_13}}</strong></td>
                        <td align="right"><strong>{{$a_13}}{{rupiah($real_sal_13)}}{{$b_13}}</strong></td>
                        <td align="right"><strong>{{$c_13}}{{rupiah($real_sal_lalu_13)}}{{$d_13}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Piutang Lainnya adalah Piutang Bagian Lancar Tagihan Penjualan Angsuran terdiri dari Pembayaran Angsuran Rumah Dinas Provinsi Kalimantan Barat dan Angsuran Kendaraan Dinas Provinsi Kalimantan Barat.<br></td>                         
                    </tr>
                @elseif($kd_rek_13=="")
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td align="center">&nbsp;</td>                         
                        <td colspan ="3" align="left">{{dotrek($kd_rek_13)}} {{$uraian_13}}</td>
                        <td style="border-top:solid;;border-bottom:solid;" align="right">{{$a_13}}{{rupiah($real_sal_13)}}{{$b_13}}</td>
                        <td style="border-top:solid;;border-bottom:solid;" align="right">{{$c_13}}{{rupiah($real_sal_lalu_13)}}{{$d_13}}</td>
                    </tr>
                @else
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td valign="top" align="right"></td>                         
                        <td colspan ="3" align="left">{{dotrek($kd_rek_13)}} {{$uraian_13}}</td>
                        <td align="right">{{$a_13}}{{rupiah($real_sal_13)}}{{$b_13}}</td>
                        <td align="right">{{$c_13}}{{rupiah($real_sal_lalu_13)}}{{$d_13}}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.3.01 -->
            @foreach($kode_13 as $kd_13)
                @php
                    $kd_rek_13 = $kd_13->kd_rek;
                    $uraian_13 = $kd_13->uraian;
                    $sal_13 = $kd_13->sal;
                    $sal_lalu_13 = $kd_13->sal_lalu;
                    if ($sal_13 < 0){
                        $a_13="("; 
                        $real_sal_13=$sal_13*-1; 
                        $b_13=")";
                    }else {
                        $a_13=""; 
                        $real_sal_13=$sal_13; 
                        $b_13="";
                    }
                    
                    if ($sal_lalu_13 < 0){
                        $c_13="("; $real_sal_lalu_13=$sal_lalu_13*-1; $d_13=")";
                    }else {
                        $c_13=""; $real_sal_lalu_13=$sal_lalu_13; $d_13="";
                    }                
                @endphp
                @if($kd_rek_13 =="1301")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_13)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_13}}</strong></td>
                        <td align="right"><strong>{{$a_13}}{{rupiah($real_sal_13)}}{{$b_13}}</strong></td>
                        <td align="right"><strong>{{$c_13}}{{rupiah($real_sal_lalu_13)}}{{$d_13}}</strong></td>
                    </tr>
                    @if($jenis=="1")
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_tambah('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','131')">Edit {{$uraian_13}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                    @foreach($kode_1301 as $kd_1301)
                        @php
                            $reff_1301 = $kd_1301->reff;
                            $uraian_1301 = $kd_1301->uraian;
                            $ket_1301 = $kd_1301->ket;
                            $sal_1301 = $kd_1301->sal;
                            if ($sal_1301 < 0){
                                $a_1301="("; 
                                $real_sal_1301=$sal_1301*-1; 
                                $b_1301=")";
                            }else {
                                $a_1301=""; 
                                $real_sal_1301=$sal_1301; 
                                $b_1301="";
                            }
                        @endphp
                        @if($ket_1301!="" )
                            <tr>
                                <td align="left">&nbsp;</td>                         
                                <td valign="top" align="right"></td>                         
                                <td colspan ="3" align="left">{{$reff_1301}} {{$uraian_1301}}</td>
                                <td align="right"></td>
                                <td align="right"></td>
                            </tr>
                            @php
                                $det_kd_1301 = DB::select("SELECT * from isi_neraca_calk where kd_skpd='$kd_skpd' and kd_rek='$ket_1301'");
                            @endphp
                            @foreach($det_kd_1301 as $det_1301)
                                @php
                                    $kd_skpd_det_1301 = $det_1301->kd_skpd;
                                    $kd_rek_det_1301  = $det_1301->kd_rek;
                                    $ket_det_1301     = $det_1301->ket;
                                    $nilai_det_1301   = $det_1301->nilai;
                                @endphp
                                @if($nilai_det_1301!="")
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" bgcolor="yellow"> {{$ket_det_1301}}</td>
                                        <td align="right" bgcolor="yellow" >{{rupiah($nilai_det_1301)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @else
                                @endif
                            @endforeach
                        @else
                            @if($reff_1301=="2.1")
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right"></td>                         
                                    <td colspan ="3" align="left">{{$reff_1301}} {{$uraian_1301}}</td>
                                    <td align="right">{{$a_1301}}{{rupiah($real_sal_1301)}}{{$b_1301}}</td>
                                    <td align="right"></td>
                                </tr>
                            @else
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right">{{$reff_1301}}</td>                         
                                    <td colspan ="3" align="left">{{$uraian_1301}}</td>
                                    <td align="right">{{$a_1301}}{{rupiah($real_sal_1301)}}{{$b_1301}}</td>
                                    <td align="right"></td>
                                </tr>
                            @endif
                        @endif

                    @endforeach
                @else
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.3.02 -->
            @foreach($kode_13 as $kd_13)
                @php
                    $kd_rek_13 = $kd_13->kd_rek;
                    $uraian_13 = $kd_13->uraian;
                    $sal_13 = $kd_13->sal;
                    $sal_lalu_13 = $kd_13->sal_lalu;
                    if ($sal_13 < 0){
                        $a_13="("; 
                        $real_sal_13=$sal_13*-1; 
                        $b13=")";
                    }else {
                        $a_13=""; 
                        $real_sal_13=$sal_13; 
                        $b_13="";
                    }
                    
                    if ($sal_lalu_13 < 0){
                        $c_13="("; $real_sal_lalu_13=$sal_lalu_13*-1; $d_13=")";
                    }else {
                        $c_13=""; $real_sal_lalu_13=$sal_lalu_13; $d_13="";
                    }                
                @endphp
                @if($kd_rek_13 =="1302")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_13)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_13}}</strong></td>
                        <td align="right"><strong>{{$a_13}}{{rupiah($real_sal_13)}}{{$b_13}}</strong></td>
                        <td align="right"><strong>{{$c_13}}{{rupiah($real_sal_lalu_13)}}{{$d_13}}</strong></td>
                    </tr>
                    @if($jenis=="1")
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_tambah('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','132')">Edit {{$uraian_13}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                    @foreach($kode_1302 as $kd_1302)
                        @php
                            $reff_1302 = $kd_1302->reff;
                            $uraian_1302 = $kd_1302->uraian;
                            $ket_1302 = $kd_1302->ket;
                            $sal_1302 = $kd_1302->sal;
                            if ($sal_1302 < 0){
                                $a_1302="("; 
                                $real_sal_1302=$sal_1302*-1; 
                                $b_1302=")";
                            }else {
                                $a_1302=""; 
                                $real_sal_1302=$sal_1302; 
                                $b_1302="";
                            }
                        @endphp
                        @if($ket_1302!="")
                            <tr>
                                <td align="left">&nbsp;</td>                         
                                <td valign="top" align="right"></td>                         
                                <td colspan ="3" align="left">{{$reff_1302}} {{$uraian_1302}}</td>
                                <td align="right"></td>
                                <td align="right"></td>
                            </tr>
                            @php
                                $det_kd_1302 = DB::select("SELECT * from isi_neraca_calk where kd_skpd='$kd_skpd' and kd_rek='$ket_1302'");
                            @endphp
                            @foreach($det_kd_1302 as $det_1302)
                                @php
                                    $kd_skpd_det_1302 = $det_1302->kd_skpd;
                                    $kd_rek_det_1302  = $det_1302->kd_rek;
                                    $ket_det_1302     = $det_1302->ket;
                                    $nilai_det_1302   = $det_1302->nilai;
                                @endphp
                                @if($nilai_det_1302!="")
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" bgcolor="yellow"> {{$ket_det_1302}}</td>
                                        <td align="right" bgcolor="yellow" >{{rupiah($nilai_det_1302)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @else
                                @endif
                            @endforeach
                        @else
                            @if($reff_1302=="2.1")
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right"></td>                         
                                    <td colspan ="3" align="left">{{$reff_1302}} {{$uraian_1302}}</td>
                                    <td align="right">{{$a_1302}}{{rupiah($real_sal_1302)}}{{$b_1302}}</td>
                                    <td align="right"></td>
                                </tr>
                            @else
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right">{{$reff_1302}}</td>                         
                                    <td colspan ="3" align="left">{{$uraian_1302}}</td>
                                    <td align="right">{{$a_1302}}{{rupiah($real_sal_1302)}}{{$b_1302}}</td>
                                    <td align="right"></td>
                                </tr>
                            @endif
                        @endif

                    @endforeach
                @else
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.3.03 -->
            @foreach($kode_13 as $kd_13)
                @php
                    $kd_rek_13 = $kd_13->kd_rek;
                    $uraian_13 = $kd_13->uraian;
                    $sal_13 = $kd_13->sal;
                    $sal_lalu_13 = $kd_13->sal_lalu;
                    if ($sal_13 < 0){
                        $a_13="("; 
                        $real_sal_13=$sal_13*-1; 
                        $b13=")";
                    }else {
                        $a_13=""; 
                        $real_sal_13=$sal_13; 
                        $b_13="";
                    }
                    
                    if ($sal_lalu_13 < 0){
                        $c_13="("; $real_sal_lalu_13=$sal_lalu_13*-1; $d_13=")";
                    }else {
                        $c_13=""; $real_sal_lalu_13=$sal_lalu_13; $d_13="";
                    }                
                @endphp
                @if($kd_rek_13 =="1303")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_13)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_13}}</strong></td>
                        <td align="right"><strong>{{$a_13}}{{rupiah($real_sal_13)}}{{$b_13}}</strong></td>
                        <td align="right"><strong>{{$c_13}}{{rupiah($real_sal_lalu_13)}}{{$d_13}}</strong></td>
                    </tr>
                    @if($jenis=="1")
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_tambah('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','133')">Edit {{$uraian_13}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                    @foreach($kode_1303 as $kd_1303)
                        @php
                            $reff_1303 = $kd_1303->reff;
                            $uraian_1303 = $kd_1303->uraian;
                            $ket_1303 = $kd_1303->ket;
                            $sal_1303 = $kd_1303->sal;
                            if ($sal_1303 < 0){
                                $a_1303="("; 
                                $real_sal_1303=$sal_1303*-1; 
                                $b_1303=")";
                            }else {
                                $a_1303=""; 
                                $real_sal_1303=$sal_1303; 
                                $b_1303="";
                            }
                        @endphp
                        @if($ket_1303!="")
                            <tr>
                                <td align="left">&nbsp;</td>                         
                                <td valign="top" align="right"></td>                         
                                <td colspan ="3" align="left">{{$reff_1303}} {{$uraian_1303}}</td>
                                <td align="right"></td>
                                <td align="right"></td>
                            </tr>
                            @php
                                $det_kd_1303 = DB::select("SELECT * from isi_neraca_calk where kd_skpd='$kd_skpd' and kd_rek='$ket_1303'");
                            @endphp
                            @foreach($det_kd_1303 as $det_1303)
                                @php
                                    $kd_skpd_det_1303 = $det_1303->kd_skpd;
                                    $kd_rek_det_1303  = $det_1303->kd_rek;
                                    $ket_det_1303     = $det_1303->ket;
                                    $nilai_det_1303   = $det_1303->nilai;
                                @endphp
                                @if($nilai_det_1303!="")
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" bgcolor="yellow"> {{$ket_det_1303}}</td>
                                        <td align="right" bgcolor="yellow" >{{rupiah($nilai_det_1303)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @else
                                @endif
                            @endforeach
                        @else
                            @if($reff_1303=="2.1")
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right"></td>                         
                                    <td colspan ="3" align="left">{{$reff_1303}} {{$uraian_1303}}</td>
                                    <td align="right">{{$a_1303}}{{rupiah($real_sal_1303)}}{{$b_1303}}</td>
                                    <td align="right"></td>
                                </tr>
                            @else
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right">{{$reff_1303}}</td>                         
                                    <td colspan ="3" align="left">{{$uraian_1303}}</td>
                                    <td align="right">{{$a_1303}}{{rupiah($real_sal_1303)}}{{$b_1303}}</td>
                                    <td align="right"></td>
                                </tr>
                            @endif
                        @endif

                    @endforeach
                @else
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.3.04 -->
            @foreach($kode_13 as $kd_13)
                @php
                    $kd_rek_13 = $kd_13->kd_rek;
                    $uraian_13 = $kd_13->uraian;
                    $sal_13 = $kd_13->sal;
                    $sal_lalu_13 = $kd_13->sal_lalu;
                    if ($sal_13 < 0){
                        $a_13="("; 
                        $real_sal_13=$sal_13*-1; 
                        $b13=")";
                    }else {
                        $a_13=""; 
                        $real_sal_13=$sal_13; 
                        $b_13="";
                    }
                    
                    if ($sal_lalu_13 < 0){
                        $c_13="("; $real_sal_lalu_13=$sal_lalu_13*-1; $d_13=")";
                    }else {
                        $c_13=""; $real_sal_lalu_13=$sal_lalu_13; $d_13="";
                    }                
                @endphp
                @if($kd_rek_13 =="1304")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_13)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_13}}</strong></td>
                        <td align="right"><strong>{{$a_13}}{{rupiah($real_sal_13)}}{{$b_13}}</strong></td>
                        <td align="right"><strong>{{$c_13}}{{rupiah($real_sal_lalu_13)}}{{$d_13}}</strong></td>
                    </tr>
                    @if($jenis=="1")
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_tambah('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','134')">Edit {{$uraian_13}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                    @foreach($kode_1304 as $kd_1304)
                        @php
                            $reff_1304 = $kd_1304->reff;
                            $uraian_1304 = $kd_1304->uraian;
                            $ket_1304 = $kd_1304->ket;
                            $sal_1304 = $kd_1304->sal;
                            if ($sal_1304 < 0){
                                $a_1304="("; 
                                $real_sal_1304=$sal_1304*-1; 
                                $b_1304=")";
                            }else {
                                $a_1304=""; 
                                $real_sal_1304=$sal_1304; 
                                $b_1304="";
                            }
                        @endphp
                        @if($ket_1304!="")
                            <tr>
                                <td align="left">&nbsp;</td>                         
                                <td valign="top" align="right"></td>                         
                                <td colspan ="3" align="left">{{$reff_1304}} {{$uraian_1304}}</td>
                                <td align="right"></td>
                                <td align="right"></td>
                            </tr>
                            @php
                                $det_kd_1304 = DB::select("SELECT * from isi_neraca_calk where kd_skpd='$kd_skpd' and kd_rek='$ket_1304'");
                            @endphp
                            @foreach($det_kd_1304 as $det_1304)
                                @php
                                    $kd_skpd_det_1304 = $det_1304->kd_skpd;
                                    $kd_rek_det_1304  = $det_1304->kd_rek;
                                    $ket_det_1304     = $det_1304->ket;
                                    $nilai_det_1304   = $det_1304->nilai;
                                @endphp
                                @if($nilai_det_1304!="")
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" bgcolor="yellow"> {{$ket_det_1304}}</td>
                                        <td align="right" bgcolor="yellow" >{{rupiah($nilai_det_1304)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @else
                                @endif
                            @endforeach
                        @else
                            @if($reff_1304=="2.1")
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right"></td>                         
                                    <td colspan ="3" align="left">{{$reff_1304}} {{$uraian_1304}}</td>
                                    <td align="right">{{$a_1304}}{{rupiah($real_sal_1304)}}{{$b_1304}}</td>
                                    <td align="right"></td>
                                </tr>
                            @else
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right">{{$reff_1304}}</td>                         
                                    <td colspan ="3" align="left">{{$uraian_1304}}</td>
                                    <td align="right">{{$a_1304}}{{rupiah($real_sal_1304)}}{{$b_1304}}</td>
                                    <td align="right"></td>
                                </tr>
                            @endif
                        @endif

                    @endforeach
                @else
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.3.05 -->
            @foreach($kode_13 as $kd_13)
                @php
                    $kd_rek_13 = $kd_13->kd_rek;
                    $uraian_13 = $kd_13->uraian;
                    $sal_13 = $kd_13->sal;
                    $sal_lalu_13 = $kd_13->sal_lalu;
                    if ($sal_13 < 0){
                        $a_13="("; 
                        $real_sal_13=$sal_13*-1; 
                        $b13=")";
                    }else {
                        $a_13=""; 
                        $real_sal_13=$sal_13; 
                        $b_13="";
                    }
                    
                    if ($sal_lalu_13 < 0){
                        $c_13="("; $real_sal_lalu_13=$sal_lalu_13*-1; $d_13=")";
                    }else {
                        $c_13=""; $real_sal_lalu_13=$sal_lalu_13; $d_13="";
                    }                
                @endphp
                @if($kd_rek_13 =="1305")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_13)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_13}}</strong></td>
                        <td align="right"><strong>{{$a_13}}{{rupiah($real_sal_13)}}{{$b_13}}</strong></td>
                        <td align="right"><strong>{{$c_13}}{{rupiah($real_sal_lalu_13)}}{{$d_13}}</strong></td>
                    </tr>
                    @if($jenis=="1")
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_tambah('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','135')">Edit {{$uraian_13}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                    @foreach($kode_1305 as $kd_1305)
                        @php
                            $reff_1305 = $kd_1305->reff;
                            $uraian_1305 = $kd_1305->uraian;
                            $ket_1305 = $kd_1305->ket;
                            $sal_1305 = $kd_1305->sal;
                            if ($sal_1305 < 0){
                                $a_1305="("; 
                                $real_sal_1305=$sal_1305*-1; 
                                $b_1305=")";
                            }else {
                                $a_1305=""; 
                                $real_sal_1305=$sal_1305; 
                                $b_1305="";
                            }
                        @endphp
                        @if($ket_1305!="")
                            <tr>
                                <td align="left">&nbsp;</td>                         
                                <td valign="top" align="right"></td>                         
                                <td colspan ="3" align="left">{{$reff_1305}} {{$uraian_1305}}</td>
                                <td align="right"></td>
                                <td align="right"></td>
                            </tr>
                            @php
                                $det_kd_1305 = DB::select("SELECT * from isi_neraca_calk where kd_skpd='$kd_skpd' and kd_rek='$ket_1305'");
                            @endphp
                            @foreach($det_kd_1305 as $det_1305)
                                @php
                                    $kd_skpd_det_1305 = $det_1305->kd_skpd;
                                    $kd_rek_det_1305  = $det_1305->kd_rek;
                                    $ket_det_1305     = $det_1305->ket;
                                    $nilai_det_1305   = $det_1305->nilai;
                                @endphp
                                @if($nilai_det_1305!="")
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" bgcolor="yellow"> {{$ket_det_1305}}</td>
                                        <td align="right" bgcolor="yellow" >{{rupiah($nilai_det_1305)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @else
                                @endif
                            @endforeach
                        @else
                            @if($reff_1305=="2.1")
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right"></td>                         
                                    <td colspan ="3" align="left">{{$reff_1305}} {{$uraian_1305}}</td>
                                    <td align="right">{{$a_1305}}{{rupiah($real_sal_1305)}}{{$b_1305}}</td>
                                    <td align="right"></td>
                                </tr>
                            @else
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right">{{$reff_1305}}</td>                         
                                    <td colspan ="3" align="left">{{$uraian_1305}}</td>
                                    <td align="right">{{$a_1305}}{{rupiah($real_sal_1305)}}{{$b_1305}}</td>
                                    <td align="right"></td>
                                </tr>
                            @endif
                        @endif

                    @endforeach
                @else
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.3.06 -->
            @foreach($kode_13 as $kd_13)
                @php
                    $kd_rek_13 = $kd_13->kd_rek;
                    $uraian_13 = $kd_13->uraian;
                    $sal_13 = $kd_13->sal;
                    $sal_lalu_13 = $kd_13->sal_lalu;
                    if ($sal_13 < 0){
                        $a_13="("; 
                        $real_sal_13=$sal_13*-1; 
                        $b13=")";
                    }else {
                        $a_13=""; 
                        $real_sal_13=$sal_13; 
                        $b_13="";
                    }
                    
                    if ($sal_lalu_13 < 0){
                        $c_13="("; $real_sal_lalu_13=$sal_lalu_13*-1; $d_13=")";
                    }else {
                        $c_13=""; $real_sal_lalu_13=$sal_lalu_13; $d_13="";
                    }                
                @endphp
                @if($kd_rek_13 =="1306")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_13)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_13}}</strong></td>
                        <td align="right"><strong>{{$a_13}}{{rupiah($real_sal_13)}}{{$b_13}}</strong></td>
                        <td align="right"><strong>{{$c_13}}{{rupiah($real_sal_lalu_13)}}{{$d_13}}</strong></td>
                    </tr>
                    @if($jenis=="1")
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_tambah('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','136')">Edit {{$uraian_13}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                    @foreach($kode_1306 as $kd_1306)
                        @php
                            $reff_1306 = $kd_1306->reff;
                            $uraian_1306 = $kd_1306->uraian;
                            $ket_1306 = $kd_1306->ket;
                            $sal_1306 = $kd_1306->sal;
                            if ($sal_1306 < 0){
                                $a_1306="("; 
                                $real_sal_1306=$sal_1306*-1; 
                                $b_1306=")";
                            }else {
                                $a_1306=""; 
                                $real_sal_1306=$sal_1306; 
                                $b_1306="";
                            }
                        @endphp
                        @if($ket_1306!="")
                            <tr>
                                <td align="left">&nbsp;</td>                         
                                <td valign="top" align="right"></td>                         
                                <td colspan ="3" align="left">{{$reff_1306}} {{$uraian_1306}}</td>
                                <td align="right"></td>
                                <td align="right"></td>
                            </tr>
                            @php
                                $det_kd_1306 = DB::select("SELECT * from isi_neraca_calk where kd_skpd='$kd_skpd' and kd_rek='$ket_1306'");
                            @endphp
                            @foreach($det_kd_1306 as $det_1306)
                                @php
                                    $kd_skpd_det_1306 = $det_1306->kd_skpd;
                                    $kd_rek_det_1306  = $det_1306->kd_rek;
                                    $ket_det_1306     = $det_1306->ket;
                                    $nilai_det_1306   = $det_1306->nilai;
                                @endphp
                                @if($nilai_det_1306!="")
                                    @if($jenis=="1")
                                        <tr>
                                            <td align="left">&nbsp;</td>                         
                                            <td valign="top" align="right"></td>                         
                                            <td colspan ="3" align="left" bgcolor="yellow"> {{$ket_det_1306}}</td>
                                            <td align="right" bgcolor="yellow" >{{rupiah($nilai_det_1306)}}</td>
                                            <td align="right"></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td align="left">&nbsp;</td>                         
                                            <td valign="top" align="right"></td>                         
                                            <td colspan ="3" align="left"> {{$ket_det_1306}}</td>
                                            <td align="right">{{rupiah($nilai_det_1306)}}</td>
                                            <td align="right"></td>
                                        </tr>
                                    @endif
                                @else
                                @endif
                            @endforeach
                        @else
                            @if($reff_1306=="2.1")
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right"></td>                         
                                    <td colspan ="3" align="left">{{$reff_1306}} {{$uraian_1306}}</td>
                                    <td align="right">{{$a_1306}}{{rupiah($real_sal_1306)}}{{$b_1306}}</td>
                                    <td align="right"></td>
                                </tr>
                            @else
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right">{{$reff_1306}}</td>                         
                                    <td colspan ="3" align="left">{{$uraian_1306}}</td>
                                    <td align="right">{{$a_1306}}{{rupiah($real_sal_1306)}}{{$b_1306}}</td>
                                    <td align="right"></td>
                                </tr>
                            @endif
                        @endif

                    @endforeach
                @else
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.3.07 -->
            @foreach($kode_1307 as $kd_1307)
                @php
                    $kd_rek_1307 = $kd_1307->kd_rek;
                    $uraian_1307 = $kd_1307->uraian;
                    $ket_1307 = $kd_1307->ket;
                    $sal_1307 = $kd_1307->sal;
                    $sal_lalu_1307 = $kd_1307->sal_lalu;
                    if ($sal_1307 < 0){
                        $a_1307="("; 
                        $real_sal_1307=$sal_1307*-1; 
                        $b_1307=")";
                    }else {
                        $a_1307=""; 
                        $real_sal_1307=$sal_1307; 
                        $b_1307="";
                    }
                    
                    if ($sal_lalu_1307 < 0){
                        $c_1307="("; $real_sal_lalu_1307=$sal_lalu_1307*-1; $d_1307=")";
                    }else {
                        $c_1307=""; $real_sal_lalu_1307=$sal_lalu_1307; $d_1307="";
                    }                
                @endphp
                @if($ket_1307=="")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_1307)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_1307}}</strong></td>
                        <td align="right"><strong>{{$a_1307}}{{rupiah($real_sal_1307)}}{{$b_1307}}</strong></td>
                        <td align="right"><strong>{{$c_1307}}{{rupiah($real_sal_lalu_1307)}}{{$d_1307}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Akumulasi Penyusutan adalah Penyajian kembali nilai buku aset tetap terdiri dari Akumulasi Penyusutan Peralatan dan Mesin, Akumulasi Penyusutan Gedung dan Bangunan dan Akumulasi Penyusutan Jalan, Irigasi, dan jaringan, rincian penjelasan sebagai berikut :<br></td>                         
                    </tr>
                @else
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td align="right">{{$ket_1307}}</td>                         
                        <td colspan ="3" align="left"> {{$uraian_1307}}</td>
                        <td align="right">{{$a_1307}}{{rupiah($real_sal_1307)}}{{$b_1307}}</td>
                        <td align="right">{{$c_1307}}{{rupiah($real_sal_lalu_1307)}}{{$d_1307}}</td>
                    </tr>
                    @if($jenis=="1")
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_akum('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','{{$ket_1307}}')">Edit {{$uraian_1307}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                    @php
                        $kd_penyu= "81080".$ket_1307;
                        $kd2_1= $ket_1307."1";
                        $kd4_111= $ket_1307."111";
                        $kd4_112= $ket_1307."112";
                        $kd4_113= $ket_1307."113";
                        $kd4_114= $ket_1307."114";
                        $kd2_2= $ket_1307."2";
                        $kd4_211= $ket_1307."211";
                        $kd4_212= $ket_1307."212";
                        $kd4_213= $ket_1307."213";
                        $kd4_214= $ket_1307."214";
                        $kd4_215= $ket_1307."215";
                        $kd4_216= $ket_1307."216";

                        $det_1307 = DB::select("SELECT '-' reff, 'Per 31 Desember $thn_ang_1' uraian,'' ket, sum(debet-kredit) sal
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where left(kd_rek6,6)='$kd_rek_1307' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                            union all
                            select '-' reff, 'Koreksi' uraian,'' ket,  isnull(sum(a.nil_m-a.nil_p),0) sal
                            from(
                                select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                                from isi_neraca_calk_baru 
                                where $skpd_clause and kd_rek='$kd2_1' 
                                group by kd_skpd
                                union all
                                select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                                from isi_neraca_calk_baru 
                                where $skpd_clause and kd_rek='$kd2_2' 
                                group by kd_skpd
                            )a
                            union all
                            select 'a.' reff, 'Koreksi Bertambah' uraian,'' ket,  isnull(sum(nilai),0) sal
                            from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                            where left(a.kd_rek2,2)='$kd2_1' and $skpd_clause 
                            union all
                            select '1)' reff, 'Hibah Masuk' uraian,'$kd4_111' ket,  isnull(sum(nilai),0) sal
                            from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                            where left(a.kd_rek2,4)='$kd4_111' and $skpd_clause 
                            union all
                            select '2)' reff, 'Mutasi Masuk Antar SKPD' uraian,'$kd4_112' ket,  isnull(sum(nilai),0) sal
                            from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                            where left(a.kd_rek2,4)='$kd4_112' and $skpd_clause 
                            union all
                            select '3)' reff, 'Reklas Antar Akun' uraian,'$kd4_113' ket,  isnull(sum(nilai),0) sal
                            from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                            where left(a.kd_rek2,4)='$kd4_113' and $skpd_clause 
                            union all
                            select '4)' reff, 'Koreksi' uraian,'$kd4_114' ket,  isnull(sum(nilai),0) sal
                            from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                            where left(a.kd_rek2,4)='$kd4_114' and $skpd_clause 
                            union all
                            select 'b.' reff, 'Koreksi Berkurang' uraian,'' ket,  isnull(sum(nilai),0) sal
                            from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                            where left(a.kd_rek2,2)='$kd2_2' and $skpd_clause 
                            union all
                            select '1)' reff, 'Hibah Keluar' uraian,'$kd4_211' ket,  isnull(sum(nilai),0) sal
                            from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                            where left(a.kd_rek2,4)='$kd4_211' and $skpd_clause 
                            union all
                            select '2)' reff, 'Mutasi Keluar Antar SKPD' uraian,'$kd4_212' ket,  isnull(sum(nilai),0) sal
                            from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                            where left(a.kd_rek2,4)='$kd4_212' and $skpd_clause 
                            union all
                            select '3)' reff, 'Reklas Antar Akun ' uraian,'$kd4_213' ket,  isnull(sum(nilai),0) sal
                            from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                            where left(a.kd_rek2,4)='$kd4_213' and $skpd_clause 
                            union all
                            select '4)' reff, 'Koreksi ' uraian,'$kd4_214' ket,  isnull(sum(nilai),0) sal
                            from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                            where left(a.kd_rek2,4)='$kd4_214' and $skpd_clause 
                            union all
                            select '5)' reff, 'Penghapusan' uraian, '$kd4_215' ket, isnull(sum(nilai),0) sal
                            from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                            where left(a.kd_rek2,4)='$kd4_215' and $skpd_clause 
                            union all
                            select '6)' reff, 'Rusak Berat' uraian,'$kd4_216' ket,  isnull(sum(nilai),0) sal
                            from isi_neraca_calk_baru a inner join rek2_neraca_calk_baru b on a.kd_rek2=b.kd_rek2 
                            where left(a.kd_rek2,4)='$kd4_216' and $skpd_clause 
                            union all
                            select '-' reff, 'Setelah Koreksi' uraian,'' ket,  isnull(sum(sal),0) sal 
                            from (
                                select sum(debet-kredit) sal
                                from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                where left(kd_rek6,6)='$kd_rek_1307' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                                union all
                                select isnull(sum(a.nil_m-a.nil_p),0) sal
                                from(
                                    select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                                    from isi_neraca_calk_baru 
                                    where $skpd_clause and kd_rek='$kd2_1' 
                                    group by kd_skpd
                                    union all
                                    select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                                    from isi_neraca_calk_baru 
                                    where $skpd_clause and kd_rek='$kd2_2' 
                                    group by kd_skpd
                                )a
                            ) a
                            union all
                            select '-' reff, 'Penyusutan tahun $thn_ang' uraian,'' ket,  isnull(sum(kredit-debet),0) sal
                            from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                            where left(kd_rek6,6)='$kd_penyu' and year(tgl_voucher)=$thn_ang and tgl_real in ('',0) and $skpd_clause
                            union all
                            select reff,uraian,'' ket, sum(sal)sal 
                            from(
                                select '-' reff, 'Per 31 Desember $thn_ang' uraian, sum(kredit-debet) sal
                                from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                where left(kd_rek6,6)='$kd_penyu' and year(tgl_voucher)=$thn_ang and tgl_real in ('',0) and $skpd_clause
                                union all
                                select '-' reff, 'Per 31 Desember $thn_ang' uraian, sum(debet-kredit) sal
                                from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                where left(kd_rek6,6)='$kd_rek_1307' and year(tgl_voucher)<=$thn_ang_1 and $skpd_clause
                                union all
                                select '-' reff, 'Per 31 Desember $thn_ang' uraian,isnull(sum(a.nil_m-a.nil_p),0) sal
                                from(
                                    select kd_skpd, isnull(sum(nilai),0) nil_p, 0 as nil_m 
                                    from isi_neraca_calk_baru 
                                    where $skpd_clause and kd_rek='$kd2_1' 
                                    group by kd_skpd
                                    union all
                                    select kd_skpd, 0 as nil_p,isnull(sum(nilai),0) nil_m 
                                    from isi_neraca_calk_baru 
                                    where $skpd_clause and kd_rek='$kd2_2' 
                                    group by kd_skpd
                                )a
                            )a group by reff,uraian");
                        
                    @endphp
                    @foreach($det_1307 as $dt_1307)
                        @php
                            $reff_det_1307 = $dt_1307->reff;
                            $uraian_det_1307 = $dt_1307->uraian;
                            $ket_det_1307 = $dt_1307->ket;
                            $sal_det_1307 = $dt_1307->sal;
                        @endphp
                        @if($ket_det_1307=="")
                            @if($ket_det_1307=="-")
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td align="right">{{$reff_det_1307}}</td>                         
                                    <td colspan ="3" align="left"> {{$uraian_det_1307}}</td>
                                    <td align="right">{{rupiah($sal_det_1307)}}</td>
                                </tr>
                            @else
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td align="right">&nbsp;</td>                         
                                    <td colspan ="3" align="left">{{$reff_det_1307}} {{$uraian_det_1307}}</td>
                                    <td align="right">{{rupiah($sal_det_1307)}}</td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td align="left">&nbsp;</td>                         
                                <td align="right">&nbsp;</td>                         
                                <td colspan ="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;{{$reff_det_1307}} {{$uraian_det_1307}}</td>
                                <td align="right">{{rupiah($sal_det_1307)}}</td>
                            </tr>
                            @php
                                $keter_det_1307 = DB::select("select * from isi_neraca_calk_baru where $skpd_clause and kd_rek2='$ket_det_1307'");
                            @endphp
                            @foreach($keter_det_1307 as $kete_det_1307)
                                @php
                                    $ket_det_1307 = $kete_det_1307->ket;
                                    $nilai_det_1307 = $kete_det_1307->nilai;
                                @endphp
                                @if($jenis==1)
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" bgcolor="yellow"> {{$ket_det_1307}}</td>
                                        <td align="right" bgcolor="yellow">{{rupiah($nilai_det_1307)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @else
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" > {{$ket_det_1307}}</td>
                                        <td align="right">{{rupiah($nilai_det_1307)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        <!-- -->


        <!-- 1.5 -->
            @foreach($kode_15 as $kd_15)
                @php
                    $kd_rek_15 = $kd_15->kd_rek;
                    $uraian_15 = $kd_15->uraian;
                    $sal_15 = $kd_15->sal;
                    $sal_lalu_15 = $kd_15->sal_lalu;
                    if ($sal_15 < 0){
                        $a_15="("; 
                        $real_sal_15=$sal_15*-1; 
                        $b_15=")";
                    }else {
                        $a_15=""; 
                        $real_sal_15=$sal_15; 
                        $b_15="";
                    }
                    
                    if ($sal_lalu_15 < 0){
                        $c_15="("; $real_sal_lalu_15=$sal_lalu_15*-1; $d_15=")";
                    }else {
                        $c_15=""; $real_sal_lalu_15=$sal_lalu_15; $d_15="";
                    }                
                @endphp
                @if($kd_rek_15 =="15")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_15)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_15}}</strong></td>
                        <td align="right"><strong>{{$a_15}}{{rupiah($real_sal_15)}}{{$b_15}}</strong></td>
                        <td align="right"><strong>{{$c_15}}{{rupiah($real_sal_lalu_15)}}{{$d_15}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Rincian dan perbandingan saldo Aset Lainnya per 31 Desember {{$thn_ang}} dan per 31 Desember {{$thn_ang_1}} terdiri dari :<br></td>                         
                    </tr>
                @elseif($kd_rek_15=="")
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td align="center">&nbsp;</td>                         
                        <td colspan ="3" align="left">{{dotrek($kd_rek_15)}} {{$uraian_15}}</td>
                        <td style="border-top:solid;;border-bottom:solid;" align="right">{{$a_15}}{{rupiah($real_sal_15)}}{{$b_15}}</td>
                        <td style="border-top:solid;;border-bottom:solid;" align="right">{{$c_15}}{{rupiah($real_sal_lalu_15)}}{{$d_15}}</td>
                    </tr>
                @else
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td valign="top" align="right"></td>                         
                        <td colspan ="3" align="left">{{dotrek($kd_rek_15)}} {{$uraian_15}}</td>
                        <td align="right">{{$a_15}}{{rupiah($real_sal_15)}}{{$b_15}}</td>
                        <td align="right">{{$c_15}}{{rupiah($real_sal_lalu_15)}}{{$d_15}}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.5.01 -->
            @php
                $no_1501 = 1;
            @endphp
            @foreach($kode_1501 as $kd_1501)
                @php
                    $kd_rek_1501 = $kd_1501->kd_rek;
                    $uraian_1501 = $kd_1501->uraian;
                    $sal_1501 = $kd_1501->sal;
                    $sal_lalu_1501 = $kd_1501->sal_lalu;
                    if ($sal_1501 < 0){
                        $a_1501="("; 
                        $real_sal_1501=$sal_1501*-1; 
                        $b_1501=")";
                    }else {
                        $a_1501=""; 
                        $real_sal_1501=$sal_1501; 
                        $b_1501="";
                    }
                    
                    if ($sal_lalu_1501 < 0){
                        $c_1501="("; $real_sal_lalu_1501=$sal_lalu_1501*-1; $d_1501=")";
                    }else {
                        $c_1501=""; $real_sal_lalu_1501=$sal_lalu_1501; $d_1501="";
                    }                
                @endphp
                @if($kd_rek_1501 =="1501")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_1501)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_1501}}</strong></td>
                        <td align="right"><strong>{{$a_1501}}{{rupiah($real_sal_1501)}}{{$b_1501}}</strong></td>
                        <td align="right"><strong>{{$c_1501}}{{rupiah($real_sal_lalu_1501)}}{{$d_1501}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Tagihan Jangka Panjang terdiri dari tagihan penjualan angsuran dan tuntutan ganti kerugian daerah<br></td>                         
                    </tr>
                @else
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td valign="top" align="right"></td>                         
                        <td colspan ="3" align="left">{{$no_1501++}} {{$uraian_1501}}</td>
                        <td align="right">{{$a_1501}}{{rupiah($real_sal_1501)}}{{$b_1501}}</td>
                        <td align="right">{{$c_1501}}{{rupiah($real_sal_lalu_1501)}}{{$d_1501}}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->


        <!-- 1.5.02 -->
            @foreach($kode_1502 as $kd_1502)
                @php
                    $reff_1502 = $kd_1502->kd_rek;
                    $uraian_1502 = $kd_1502->uraian;
                    $kd_rek_1502 = $kd_1502->kd_rek;
                    $noket_1502 = $kd_1502->noket;
                    $sal_1502 = $kd_1502->sal;
                    $sal_lalu_1502 = $kd_1502->sal_lalu;
                    if ($sal_1502 < 0){
                        $a_1502="("; 
                        $real_sal_1502=$sal_1502*-1; 
                        $b_1502=")";
                    }else {
                        $a_1502=""; 
                        $real_sal_1502=$sal_1502; 
                        $b_1502="";
                    }
                    
                    if ($sal_lalu_1502 < 0){
                        $c_1502="("; $real_sal_lalu_1502=$sal_lalu_1502*-1; $d_1502=")";
                    }else {
                        $c_1502=""; $real_sal_lalu_1502=$sal_lalu_1502; $d_1502="";
                    }
                @endphp
                @if($reff_1502 =="1502")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($reff_1502)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_1502}}</strong></td>
                        <td align="right"><strong>{{$a_1502}}{{rupiah($real_sal_1502)}}{{$b_1502}}</strong></td>
                        <td align="right"><strong>{{$c_1502}}{{rupiah($real_sal_lalu_1502)}}{{$d_1502}}</strong></td>
                    </tr>
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Kemitraan dengan Pihak Ketiga terdiri dari Sewa, Kerja Sama Pemanfaatan, Bangun Guna Serah/Bangun Serah Guna(BGS/BSG) dan Kerja Sama Penyediaan Infrastruktur<br></td>                         
                    </tr>
                    @if($jenis=="1")
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_ket('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','{{$reff_1502}}')">Edit Keterangan {{$uraian_1502}}</button>
                            </td>                         
                        </tr>
                    @else
                        <tr>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="justify" colspan="5" >{{$ket_rinci_1502}}</td>                         
                        </tr>
                    @endif
                @else
                    <tr>
                        <td align="left">&nbsp;</td>                         
                        <td valign="top" align="right">{{$noket_1502}}</td>                         
                        <td colspan ="3" align="left">{{$uraian_1502}}</td>
                        <td align="right">{{$a_1502}}{{rupiah($real_sal_1502)}}{{$b_1502}}</td>
                        <td align="right">{{$c_1502}}{{rupiah($real_sal_lalu_1502)}}{{$d_1502}}</td>
                    </tr>
                    @php
                        $noket_ket_1502 = "1502".$noket_1502;
                        $ket_1502 = collect(DB::select("SELECT x.kd_rek, x.nm_rek, y.ket, y.kd_rinci FROM (
                                            SELECT kd_rek, nm_rek FROM ket_neraca_calk WHERE kd_rek='$noket_ket_1502') x 
                                            LEFT JOIN (
                                            SELECT kd_rek, ket, kd_rinci FROM isi_neraca_calk where kd_skpd='$kd_skpd') y
                                            on x.kd_rek=y.kd_rek
                                            order by kd_rinci"))->first();
                        $ket_rinci_1502 = $ket_1502->ket;
                    @endphp
                    @if($jenis=="1")
                        <tr>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="justify" colspan="5" bgcolor="yellow">{{$ket_rinci_1502}}</td>                         
                        </tr>
                    @else
                        <tr>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="left"><strong>&nbsp;</strong></td>
                            <td align="justify" colspan="5" >{{$ket_rinci_1502}}</td>                         
                        </tr>
                    @endif
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.5.03 -->
            @foreach($kode_15 as $kd_15)
                @php
                    $kd_rek_15 = $kd_15->kd_rek;
                    $uraian_15 = $kd_15->uraian;
                    $sal_15 = $kd_15->sal;
                    $sal_lalu_15 = $kd_15->sal_lalu;
                    if ($sal_15 < 0){
                        $a_15="("; 
                        $real_sal_15=$sal_15*-1; 
                        $b_15=")";
                    }else {
                        $a_15=""; 
                        $real_sal_15=$sal_15; 
                        $b_15="";
                    }
                    
                    if ($sal_lalu_15 < 0){
                        $c_15="("; $real_sal_lalu_15=$sal_lalu_15*-1; $d_15=")";
                    }else {
                        $c_15=""; $real_sal_lalu_15=$sal_lalu_15; $d_15="";
                    }                
                @endphp
                @if($kd_rek_15 =="1503")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_15)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_15}}</strong></td>
                        <td align="right"><strong>{{$a_15}}{{rupiah($real_sal_15)}}{{$b_15}}</strong></td>
                        <td align="right"><strong>{{$c_15}}{{rupiah($real_sal_lalu_15)}}{{$d_15}}</strong></td>
                    </tr>
                    @if($jenis=="1")
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_tambah('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','153')">Edit {{$uraian_15}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                    @foreach($kode_1503 as $kd_1503)
                        @php
                            $reff_1503 = $kd_1503->reff;
                            $uraian_1503 = $kd_1503->uraian;
                            $ket_1503 = $kd_1503->ket;
                            $sal_1503 = $kd_1503->sal;
                            if ($sal_1503 < 0){
                                $a_1503="("; 
                                $real_sal_1503=$sal_1503*-1; 
                                $b_1503=")";
                            }else {
                                $a_1503=""; 
                                $real_sal_1503=$sal_1503; 
                                $b_1503="";
                            }
                        @endphp
                        @if($ket_1503!="" )
                            <tr>
                                <td align="left">&nbsp;</td>                         
                                <td valign="top" align="right"></td>                         
                                <td colspan ="3" align="left">{{$reff_1503}} {{$uraian_1503}}</td>
                                <td align="right"></td>
                                <td align="right"></td>
                            </tr>
                            @php
                                $det_kd_1503 = DB::select("SELECT * from isi_neraca_calk where kd_skpd='$kd_skpd' and kd_rek='$ket_1503'");
                            @endphp
                            @foreach($det_kd_1503 as $det_1503)
                                @php
                                    $kd_skpd_det_1503 = $det_1503->kd_skpd;
                                    $kd_rek_det_1503  = $det_1503->kd_rek;
                                    $ket_det_1503     = $det_1503->ket;
                                    $nilai_det_1503   = $det_1503->nilai;
                                @endphp
                                @if($nilai_det_1503!="")
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" bgcolor="yellow"> {{$ket_det_1503}}</td>
                                        <td align="right" bgcolor="yellow" >{{rupiah($nilai_det_1503)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @else
                                @endif
                            @endforeach
                        @else
                            @if($reff_1503=="2.1")
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right"></td>                         
                                    <td colspan ="3" align="left">{{$reff_1503}} {{$uraian_1503}}</td>
                                    <td align="right">{{$a_1503}}{{rupiah($real_sal_1503)}}{{$b_1503}}</td>
                                    <td align="right"></td>
                                </tr>
                            @else
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right">{{$reff_1503}}</td>                         
                                    <td colspan ="3" align="left">{{$uraian_1503}}</td>
                                    <td align="right">{{$a_1503}}{{rupiah($real_sal_1503)}}{{$b_1503}}</td>
                                    <td align="right"></td>
                                </tr>
                            @endif
                        @endif

                    @endforeach
                @else
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.5.04 -->
            @foreach($kode_15 as $kd_15)
                @php
                    $kd_rek_15 = $kd_15->kd_rek;
                    $uraian_15 = $kd_15->uraian;
                    $sal_15 = $kd_15->sal;
                    $sal_lalu_15 = $kd_15->sal_lalu;
                    if ($sal_15 < 0){
                        $a_15="("; 
                        $real_sal_15=$sal_15*-1; 
                        $b_15=")";
                    }else {
                        $a_15=""; 
                        $real_sal_15=$sal_15; 
                        $b_15="";
                    }
                    
                    if ($sal_lalu_15 < 0){
                        $c_15="("; $real_sal_lalu_15=$sal_lalu_15*-1; $d_15=")";
                    }else {
                        $c_15=""; $real_sal_lalu_15=$sal_lalu_15; $d_15="";
                    }                
                @endphp
                @if($kd_rek_15 =="1504")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_15)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_15}}</strong></td>
                        <td align="right"><strong>{{$a_15}}{{rupiah($real_sal_15)}}{{$b_15}}</strong></td>
                        <td align="right"><strong>{{$c_15}}{{rupiah($real_sal_lalu_15)}}{{$d_15}}</strong></td>
                    </tr>
                    @if($jenis=="1")
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_tambah('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','154')">Edit {{$uraian_15}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif
                    @foreach($kode_1504 as $kd_1504)
                        @php
                            $reff_1504 = $kd_1504->reff;
                            $uraian_1504 = $kd_1504->uraian;
                            $ket_1504 = $kd_1504->ket;
                            $sal_1504 = $kd_1504->sal;
                            if ($sal_1504 < 0){
                                $a_1504="("; 
                                $real_sal_1504=$sal_1504*-1; 
                                $b_1504=")";
                            }else {
                                $a_1504=""; 
                                $real_sal_1504=$sal_1504; 
                                $b_1504="";
                            }
                        @endphp
                        @if($ket_1504!="" )
                            <tr>
                                <td align="left">&nbsp;</td>                         
                                <td valign="top" align="right"></td>                         
                                <td colspan ="3" align="left">{{$reff_1504}} {{$uraian_1504}}</td>
                                <td align="right"></td>
                                <td align="right"></td>
                            </tr>
                            @php
                                $det_kd_1504 = DB::select("SELECT * from isi_neraca_calk where kd_skpd='$kd_skpd' and kd_rek='$ket_1504'");
                            @endphp
                            @foreach($det_kd_1504 as $det_1504)
                                @php
                                    $kd_skpd_det_1504 = $det_1504->kd_skpd;
                                    $kd_rek_det_1504  = $det_1504->kd_rek;
                                    $ket_det_1504     = $det_1504->ket;
                                    $nilai_det_1504   = $det_1504->nilai;
                                @endphp
                                @if($nilai_det_1504!="")
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" bgcolor="yellow"> {{$ket_det_1504}}</td>
                                        <td align="right" bgcolor="yellow" >{{rupiah($nilai_det_1504)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @else
                                @endif
                            @endforeach
                        @else
                            @if($reff_1504=="2.1")
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right"></td>                         
                                    <td colspan ="3" align="left">{{$reff_1504}} {{$uraian_1504}}</td>
                                    <td align="right">{{$a_1504}}{{rupiah($real_sal_1504)}}{{$b_1504}}</td>
                                    <td align="right"></td>
                                </tr>
                            @else
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td valign="top" align="right">{{$reff_1504}}</td>                         
                                    <td colspan ="3" align="left">{{$uraian_1504}}</td>
                                    <td align="right">{{$a_1504}}{{rupiah($real_sal_1504)}}{{$b_1504}}</td>
                                    <td align="right"></td>
                                </tr>
                            @endif
                        @endif

                    @endforeach
                @else
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.5.05 -->
            @foreach($kode_15 as $kd_15)
                @php
                    $kd_rek_15 = $kd_15->kd_rek;
                    $uraian_15 = $kd_15->uraian;
                    $sal_15 = $kd_15->sal;
                    $sal_lalu_15 = $kd_15->sal_lalu;
                    if ($sal_15 < 0){
                        $a_15="("; 
                        $real_sal_15=$sal_15*-1; 
                        $b_15=")";
                    }else {
                        $a_15=""; 
                        $real_sal_15=$sal_15; 
                        $b_15="";
                    }
                    
                    if ($sal_lalu_15 < 0){
                        $c_15="("; $real_sal_lalu_15=$sal_lalu_15*-1; $d_15=")";
                    }else {
                        $c_15=""; $real_sal_lalu_15=$sal_lalu_15; $d_15="";
                    }                
                @endphp
                @if($kd_rek_15 =="1505")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_15)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_15}}</strong></td>
                        <td align="right"><strong>{{$a_15}}{{rupiah($real_sal_15)}}{{$b_15}}</strong></td>
                        <td align="right"><strong>{{$c_15}}{{rupiah($real_sal_lalu_15)}}{{$d_15}}</strong></td>
                    </tr>
                    @if($jenis=="1")
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_akum('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','5')">Edit {{$uraian_15}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif

                    @foreach($kode_1505 as $kd_1505)
                        @php
                            $reff_1505 = $kd_1505->reff;
                            $uraian_1505 = $kd_1505->uraian;
                            $ket_1505 = $kd_1505->ket;
                            $sal_1505 = $kd_1505->sal;
                        @endphp
                        @if($ket_1505=="")
                            @if($ket_1505=="-")
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td align="right">{{$reff_1505}}</td>                         
                                    <td colspan ="3" align="left"> {{$uraian_1505}}</td>
                                    <td align="right">{{rupiah($sal_1505)}}</td>
                                </tr>
                            @else
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td align="right">&nbsp;</td>                         
                                    <td colspan ="3" align="left">{{$reff_1505}} {{$uraian_1505}}</td>
                                    <td align="right">{{rupiah($sal_1505)}}</td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td align="left">&nbsp;</td>                         
                                <td align="right">&nbsp;</td>                         
                                <td colspan ="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;{{$reff_1505}} {{$uraian_1505}}</td>
                                <td align="right">{{rupiah($sal_1505)}}</td>
                            </tr>
                            @php
                                $keter_1505 = DB::select("select * from isi_neraca_calk_baru where $skpd_clause and kd_rek2='$ket_1505'");
                            @endphp
                            @foreach($keter_1505 as $kete_1505)
                                @php
                                    $ket_1505 = $kete_1505->ket;
                                    $nilai_1505 = $kete_1505->nilai;
                                @endphp
                                @if($jenis==1)
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" bgcolor="yellow"> {{$ket_1505}}</td>
                                        <td align="right" bgcolor="yellow">{{rupiah($nilai_1505)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @else
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" > {{$ket_1505}}</td>
                                        <td align="right">{{rupiah($nilai_1505)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.5.06 -->
            @foreach($kode_15 as $kd_15)
                @php
                    $kd_rek_15 = $kd_15->kd_rek;
                    $uraian_15 = $kd_15->uraian;
                    $sal_15 = $kd_15->sal;
                    $sal_lalu_15 = $kd_15->sal_lalu;
                    if ($sal_15 < 0){
                        $a_15="("; 
                        $real_sal_15=$sal_15*-1; 
                        $b_15=")";
                    }else {
                        $a_15=""; 
                        $real_sal_15=$sal_15; 
                        $b_15="";
                    }
                    
                    if ($sal_lalu_15 < 0){
                        $c_15="("; $real_sal_lalu_15=$sal_lalu_15*-1; $d_15=")";
                    }else {
                        $c_15=""; $real_sal_lalu_15=$sal_lalu_15; $d_15="";
                    }                
                @endphp
                @if($kd_rek_15 =="1506")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_15)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_15}}</strong></td>
                        <td align="right"><strong>{{$a_15}}{{rupiah($real_sal_15)}}{{$b_15}}</strong></td>
                        <td align="right"><strong>{{$c_15}}{{rupiah($real_sal_lalu_15)}}{{$d_15}}</strong></td>
                    </tr>
                    @if($jenis=="1")
                        <tr>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="left"><strong>&nbsp;</strong></td>
                             <td align="justify" colspan="7">
                                <button type="button" href="javascript:void(0);" onclick="edit_akum('{{$kd_skpd_edit}}','{{$jns_ang}}','{{$bulan}}','6')">Edit {{$uraian_15}}</button>
                            </td>                         
                        </tr>
                    @else
                    @endif

                    @foreach($kode_1506 as $kd_1506)
                        @php
                            $reff_1506 = $kd_1506->reff;
                            $uraian_1506 = $kd_1506->uraian;
                            $ket_1506 = $kd_1506->ket;
                            $sal_1506 = $kd_1506->sal;
                        @endphp
                        @if($ket_1506=="")
                            @if($ket_1506=="-")
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td align="right">{{$reff_1506}}</td>                         
                                    <td colspan ="3" align="left"> {{$uraian_1506}}</td>
                                    <td align="right">{{rupiah($sal_1506)}}</td>
                                </tr>
                            @else
                                <tr>
                                    <td align="left">&nbsp;</td>                         
                                    <td align="right">&nbsp;</td>                         
                                    <td colspan ="3" align="left">{{$reff_1506}} {{$uraian_1506}}</td>
                                    <td align="right">{{rupiah($sal_1506)}}</td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td align="left">&nbsp;</td>                         
                                <td align="right">&nbsp;</td>                         
                                <td colspan ="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;{{$reff_1506}} {{$uraian_1506}}</td>
                                <td align="right">{{rupiah($sal_1506)}}</td>
                            </tr>
                            @php
                                $keter_1506 = DB::select("select * from isi_neraca_calk_baru where $skpd_clause and kd_rek2='$ket_1506'");
                            @endphp
                            @foreach($keter_1506 as $kete_1506)
                                @php
                                    $ket_1506 = $kete_1506->ket;
                                    $nilai_1506 = $kete_1506->nilai;
                                @endphp
                                @if($jenis==1)
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" bgcolor="yellow"> {{$ket_1506}}</td>
                                        <td align="right" bgcolor="yellow">{{rupiah($nilai_1506)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @else
                                    <tr>
                                        <td align="left">&nbsp;</td>                         
                                        <td valign="top" align="right"></td>                         
                                        <td colspan ="3" align="left" > {{$ket_1506}}</td>
                                        <td align="right">{{rupiah($nilai_1506)}}</td>
                                        <td align="right"></td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 1.5.07 -->
            @foreach($kode_15 as $kd_15)
                @php
                    $kd_rek_15 = $kd_15->kd_rek;
                    $uraian_15 = $kd_15->uraian;
                    $sal_15 = $kd_15->sal;
                    $sal_lalu_15 = $kd_15->sal_lalu;
                    if ($sal_15 < 0){
                        $a_15="("; 
                        $real_sal_15=$sal_15*-1; 
                        $b_15=")";
                    }else {
                        $a_15=""; 
                        $real_sal_15=$sal_15; 
                        $b_15="";
                    }
                    
                    if ($sal_lalu_15 < 0){
                        $c_15="("; $real_sal_lalu_15=$sal_lalu_15*-1; $d_15=")";
                    }else {
                        $c_15=""; $real_sal_lalu_15=$sal_lalu_15; $d_15="";
                    }                
                @endphp
                @if($kd_rek_15 =="1507")
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_15)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_15}}</strong></td>
                        <td align="right"><strong>{{$a_15}}{{rupiah($real_sal_15)}}{{$b_15}}</strong></td>
                        <td align="right"><strong>{{$c_15}}{{rupiah($real_sal_lalu_15)}}{{$d_15}}</strong></td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 2 -->
            @foreach($kode_2 as $kd_2)
                @php
                    $kd_rek_2 = $kd_2->kd_rek;
                    $uraian_2 = $kd_2->nm_rek;
                    $sal_2 = $kd_2->sal;
                    $sal_lalu_2 = $kd_2->sal_lalu;
                    if ($sal_2 < 0){
                        $a_2="("; 
                        $real_sal_2=$sal_2*-1; 
                        $b_2=")";
                    }else {
                        $a_2=""; 
                        $real_sal_2=$sal_2; 
                        $b_2="";
                    }
                    
                    if ($sal_lalu_2 < 0){
                        $c_2="("; $real_sal_lalu_2=$sal_lalu_2*-1; $d_2=")";
                    }else {
                        $c_2=""; $real_sal_lalu_2=$sal_lalu_2; $d_2="";
                    }             
                    $leng_2 = strlen($kd_rek_2);   
                @endphp
                @if($leng_2 == 1)
                    @if($kd_rek_2 == "9")
                        <tr>
                            <td align="left">&nbsp;</td>                         
                            <td align="center">&nbsp;</td>                         
                            <td colspan ="3" align="left">{{$uraian_2}}</td>
                            <td style="border-top:solid;;border-bottom:solid;" align="right">{{$a_2}}{{rupiah($real_sal_2)}}{{$b_2}}</td>
                            <td style="border-top:solid;;border-bottom:solid;" align="right">{{$c_2}}{{rupiah($real_sal_lalu_2)}}{{$d_2}}</td>
                        </tr>
                    @else
                        <tr>
                            <td align="left"><strong>&nbsp;</strong></td>                         
                            <td align="left"><strong>{{dotrek($kd_rek_2)}}</strong></td>                         
                            <td colspan ="3" align="left"><strong>{{$uraian_2}}</strong></td>
                            <td align="right"><strong>{{$a_2}}{{rupiah($real_sal_2)}}{{$b_2}}</strong></td>
                            <td align="right"><strong>{{$c_2}}{{rupiah($real_sal_lalu_2)}}{{$d_2}}</strong></td>
                        </tr>
                    @endif
                @elseif($leng_2 == 2)
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="right"><strong>{{dotrek($kd_rek_2)}} </strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_2}}</strong></td>
                        <td align="right"><strong>{{$a_2}}{{rupiah($real_sal_2)}}{{$b_2}}</strong></td>
                        <td align="right"><strong>{{$c_2}}{{rupiah($real_sal_lalu_2)}}{{$d_2}}</strong></td>
                    </tr>
                @else
                    <tr>
                        <td align="left">&nbsp;</td>  
                        <td align="right">&nbsp;</td>                         
                        <td align="left">{{dotrek($kd_rek_2)}} </td>                         
                        <td colspan ="2" align="left">{{$uraian_2}}</td>
                        <td align="right">{{$a_2}}{{rupiah($real_sal_2)}}{{$b_2}}</td>
                        <td align="right">{{$c_2}}{{rupiah($real_sal_lalu_2)}}{{$d_2}}</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- 3 -->
            @foreach($kode_3 as $kd_3)
                @php
                    $kd_rek_3 = $kd_3->reff;
                    $uraian_3 = $kd_3->uraian;
                    $sal_3 = $kd_3->sal;
                    $sal_lalu_3 = $kd_3->sal_lalu;
                    if ($sal_3 < 0){
                        $a_3="("; 
                        $real_sal_3=$sal_3*-1; 
                        $b_3=")";
                    }else {
                        $a_3=""; 
                        $real_sal_3=$sal_3; 
                        $b_3="";
                    }
                    
                    if ($sal_lalu_3 < 0){
                        $c_3="("; $real_sal_lalu_3=$sal_lalu_3*-1; $d_3=")";
                    }else {
                        $c_3=""; $real_sal_lalu_3=$sal_lalu_3; $d_3="";
                    }                
                @endphp
                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>                         
                        <td align="left"><strong>{{dotrek($kd_rek_3)}}</strong></td>                         
                        <td colspan ="3" align="left"><strong>{{$uraian_3}}</strong></td>
                        <td align="right"><strong>{{$a_3}}{{rupiah($real_sal_3)}}{{$b_3}}</strong></td>
                        <td align="right"><strong>{{$c_3}}{{rupiah($real_sal_lalu_3)}}{{$d_3}}</strong></td>
                    </tr>

                    <tr>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="left"><strong>&nbsp;</strong></td>
                        <td align="justify" colspan="5">Pada Neraca per 31 Desember {{$thn_ang}} Ekuitas sebesar {{$a_3}}{{rupiah($real_sal_3)}}{{$b_3}} sedangkan pada Neraca per 31 Desember {{$thn_ang_1}} saldo Ekuitas sebesar {{$c_3}}{{rupiah($real_sal_lalu_3)}}{{$d_3}}<br></td>                         
                    </tr>
            @endforeach
            <tr>
                <td align="left"><strong>&nbsp;</strong></td>                         
                <td align="center"><strong>&nbsp;</strong></td>                         
                <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
                <td align="center"><strong>&nbsp;</strong></td>
            </tr>
        <!-- -->

        <!-- Total -->
            @foreach($tot_akhir as $kd_tot_akhir)
                @php
                    $kd_rek_tot_akhir = $kd_tot_akhir->reff;
                    $uraian_tot_akhir = $kd_tot_akhir->uraian;
                    $sal_tot_akhir = $kd_tot_akhir->sal;
                    $sal_lalu_tot_akhir = $kd_tot_akhir->sal_lalu;
                    if ($sal_tot_akhir < 0){
                        $a_tot_akhir="("; 
                        $real_sal_tot_akhir=$sal_tot_akhir*-1; 
                        $b_tot_akhir=")";
                    }else {
                        $a_tot_akhir=""; 
                        $real_sal_tot_akhir=$sal_tot_akhir; 
                        $b_tot_akhir="";
                    }
                    
                    if ($sal_lalu_tot_akhir < 0){
                        $c_tot_akhir="("; $real_sal_lalu_tot_akhir=$sal_lalu_tot_akhir*-1; $d_tot_akhir=")";
                    }else {
                        $c_tot_akhir=""; $real_sal_lalu_tot_akhir=$sal_lalu_tot_akhir; $d_tot_akhir="";
                    }                
                @endphp
                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                       
                    <td colspan ="4" align="left"><strong>{{$uraian_tot_akhir}}</strong></td>
                    <td align="right"><strong>{{$a_tot_akhir}}{{rupiah($real_sal_tot_akhir)}}{{$b_tot_akhir}}</strong></td>
                    <td align="right"><strong>{{$c_tot_akhir}}{{rupiah($real_sal_lalu_tot_akhir)}}{{$d_tot_akhir}}</strong></td>
                </tr>
            @endforeach

                <tr>
                    <td align="left"><strong>&nbsp;</strong></td>                         
                    <td align="center"><strong>&nbsp;</strong></td>                         
                    <td colspan ="3" align="center"><strong>&nbsp;</strong></td>
                    <td align="center"><strong>&nbsp;</strong></td>
                    <td align="center"><strong>&nbsp;</strong></td>
                </tr>
        <!-- -->
    </table>

</body>
</html>
<script type="text/javascript">
    function edit(kd_skpd,jns_ang,bulan,kd_rek) {
        let url             = new URL("{{ route('calk.calkbab3_neraca_edit') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        searchParams.append("kd_rek", kd_rek);
        window.open(url.toString(), "_blank");
    }
    function edit_ket(kd_skpd,jns_ang,bulan,kd_rek) {
        let url             = new URL("{{ route('calk.calkbab3_neraca_edit_ket') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        searchParams.append("kd_rek", kd_rek);
        window.open(url.toString(), "_blank");
    }
    function edit_tambah(kd_skpd,jns_ang,bulan,kd_rek) {
        let url             = new URL("{{ route('calk.calkbab3_neraca_edit_tambah') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        searchParams.append("kd_rek", kd_rek);
        window.open(url.toString(), "_blank");
    }
    function edit_akum(kd_skpd,jns_ang,bulan,kd_rek) {
        let url             = new URL("{{ route('calk.calkbab3_neraca_edit_akum') }}");
        let searchParams    = url.searchParams;
        searchParams.append("kd_skpd", kd_skpd);
        searchParams.append("jns_ang", jns_ang);
        searchParams.append("bulan", bulan);
        searchParams.append("kd_rek", kd_rek);
        window.open(url.toString(), "_blank");
    }
</script>