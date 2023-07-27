<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA SAP SEMESTER</title>
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

<body >
{{-- <body> --}}
    <TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD  width="60%" valign="top" align="right" ></TD>
            <TD width="40%"  align="left" ></TD>
        </TR>
    </TABLE>
    <br/>
    <TABLE style="border-collapse:collapse;font-family:Arial" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
        <tr>
            <td rowspan="3" align="center" style="border-right:hidden">
                <img src="{{asset('template/assets/images/'.$header->logo_pemda_hp) }}"  width="75" height="100" />
            </td>
            <td align="center" style="border-left:hidden;border-bottom:hidden"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td>
        </tr>
        @if($periodebulan=="bulan")
        <tr>
            <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden"><b>LAPORAN REALISASI {{$judul}} APBD DAN PROGNOSIS<BR> {{$bulan2}} BULAN BERIKUTNYA </b>
        </tr>
        @else
        <tr>
            <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden"><b>LAPORAN REALISASI {{tgl_format_oyoy($tanggal1)}} S.D {{tgl_format_oyoy($tanggal2)}} APBD DAN PROGNOSIS<BR> $bulan2 BULAN BERIKUTNYA </b>
        </tr>
        @endif
        <tr>
            <td align="center" style="border-left:hidden;border-top:hidden" ><b>TAHUN ANGGARAN {{$tahun_anggaran}}</b>
        </tr>
    </TABLE>
    @if($skpdunit=="skpd")
        <TABLE style="border-collapse:collapse;font-family:Arial;font-size:12px" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
            <tr>
                <td width="15%" align="left" style="border-right:hidden;border-bottom:hidden">&nbsp;&nbsp; Urusan Pemerintahan </td>
                <td width="85%" align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,1)}} -  {{nama_urusan(left($kd_skpd,1))}} </td>
            </tr>
            <tr>
                <td align="left" style="border-right:hidden;border-bottom:hidden"> &nbsp;&nbsp; Bidang Pemerintahan </td>
                <td align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,4)}} - {{nama_bidang(left($kd_skpd,4))}}</td>
            </tr>
            <tr>
                <td align="left" style="border-right:hidden;border-bottom:hidden"> &nbsp;&nbsp; Unit Organisasi </td>
                <td align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,17)}} - {{nama_org(left($kd_skpd,17))}}</td>
            </tr>
        </TABLE>
    @elseif($skpdunit=="unit")
        <TABLE style="border-collapse:collapse;font-family:Arial;font-size:12px" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
            <tr>
                <td width="15%" align="left" style="border-right:hidden;border-bottom:hidden">&nbsp;&nbsp; Urusan Pemerintahan </td>
                <td width="85%" align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,1)}} -  {{nama_urusan(left($kd_skpd,1))}} </td>
            </tr>
            <tr>
                <td align="left" style="border-right:hidden;border-bottom:hidden"> &nbsp;&nbsp; Bidang Pemerintahan </td>
                <td align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,4)}} - {{nama_bidang(left($kd_skpd,4))}}</td>
            </tr>
            <tr>
                <td align="left" style="border-right:hidden;border-bottom:hidden"> &nbsp;&nbsp; Unit Organisasi </td>
                <td align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,17)}} - {{nama_org(left($kd_skpd,17))}}</td>
            </tr>
            <tr>
                <td align="left" style="border-right:hidden;border-bottom:hidden">&nbsp;&nbsp; Sub Unit Organisasi </td>
                <td align="left" style="border-left:hidden;border-bottom:hidden"> : {{left($kd_skpd,22)}} - {{nama_skpd(left($kd_skpd,22))}}</td>
            </tr>
        </TABLE>
    @else
    @endif
    

    <hr>
 
    {{-- isi --}}
    <table style="border-collapse:collapse;font-family:Arial;font-size:11px" width="100%" align="center" border="1" cellspacing="3" cellpadding="3">
        <thead>
            <tr>
                <td width="7%" align="center" bgcolor="#CCCCCC" ><b>KD REK</b></td>
                <td width="32%" align="center" bgcolor="#CCCCCC" ><b>URAIAN</b></td>
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>JUMLAH ANGGARAN</b></td>
                @if($periodebulan=="bulan")
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>REALISASI <br>S/D<br> {{$judul}}</b></td>
                @elseif($periodebulan=="periode")
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>REALISASI <br>S/D<br> {{tgl_format_oyoy($tanggal1)}} S.D {{tgl_format_oyoy($tanggal2)}}</b></td>
                @else
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>REALISASI</b></td>
                @endif
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>SISA ANGGARAN</b></td>
                <td width="15%" align="center" bgcolor="#CCCCCC" ><b>PROGNOSIS</b></td>
                <td width="7%" align="center" bgcolor="#CCCCCC" ><b>%</b></td>
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" >1</td> 
               <td align="center" bgcolor="#CCCCCC" >2</td> 
               <td align="center" bgcolor="#CCCCCC" >3</td> 
               <td align="center" bgcolor="#CCCCCC" >4</td> 
               <td align="center" bgcolor="#CCCCCC" >5</td> 
               <td align="center" bgcolor="#CCCCCC" >6</td> 
               <td align="center" bgcolor="#CCCCCC" >7</td> 
            </tr>
        </thead>
                
                @foreach ($rincian_pend as $row)
                        @php
                            $kd_sub_kegiatan = $row->kd_sub_kegiatan;
                            $kd_rek = $row->kd_rek;
                            $nm_rek = $row->nm_rek;
                            $nil_ang = $row->anggaran;
                
                            $sd_bulan_ini = $row->sd_bulan_ini;
                            $sisa = $nil_ang - $sd_bulan_ini;
                            $persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
                            $sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
                            $a = $sisa < 0 ? '(' : '';
                            $b = $sisa < 0 ? ')' : '';
                            $leng = strlen($kd_rek);
                        
                        @endphp
                              
                        @if ($leng == 2)
                            <tr>
                                <td align="left" valign="top">{{$kd_sub_kegiatan}}.{{dotrek($kd_rek)}}</td> 
                                <td align="left"  valign="top">{{$nm_rek}}</td> 
                                <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                                <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr>
                        @elseif ($leng == 4)
                            <tr>
                                <td align="left" valign="top">{{$kd_sub_kegiatan}}.{{dotrek($kd_rek)}}</td> 
                                <td align="left"  valign="top">{{$nm_rek}}</td> 
                                <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                                <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr> 
                        @elseif ($leng == 6)
                            <tr>
                                <td align="left" valign="top">{{$kd_sub_kegiatan}}.{{dotrek($kd_rek)}}</td> 
                                <td align="left"  valign="top">{{$nm_rek}}</td> 
                                <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                                <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr>
                        @elseif ($leng == 8)
                            <tr>
                                <td align="left" valign="top">{{$kd_sub_kegiatan}}.{{dotrek($kd_rek)}}</td> 
                                <td align="left"  valign="top">{{$nm_rek}}</td> 
                                <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                                <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr>
                        @elseif ($leng == 12)
                            <tr>
                                <td align="left" valign="top">{{$kd_sub_kegiatan}}.{{dotrek($kd_rek)}}</td> 
                                <td align="left"  valign="top">{{$nm_rek}}</td> 
                                <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                                <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr>
                        @else
                            <tr>
                                <td align="left" valign="top"><b>{{$kd_sub_kegiatan}}.{{dotrek($kd_rek)}}</b></td> 
                                <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                                <td align="right" valign="top"><b>{{rupiah($nil_ang)}}</b></td> 
                                <td align="right" valign="top"><b>{{rupiah($sd_bulan_ini)}}</b></td> 
                                <td align="right" valign="top"><b>{{$a}} {{rupiah($sisa1)}} {{$b}}</b></td> 
                                <td align="right" valign="top"><b>{{$a}} {{rupiah($sisa1)}} {{$b}}</b></td> 
                                <td align="right" valign="top"><b>{{rupiah($persen)}}</b></td> 
                            </tr>
                        @endif   
                        
                @endforeach
                <tr>
                    <td align="left" valign="top">&nbsp;</td> 
                    <td align="left"  valign="top">&nbsp;</td> 
                    <td align="right" valign="top"> </td> 
                    <td align="right" valign="top"> </td> 
                    <td align="right" valign="top"> </td> 
                    <td align="right" valign="top"> </td> 
                    <td align="right" valign="top"> </td> 
                </tr>
                @php
                    $ang_jum_pend = $jum_pend->anggaran;
                    $real_jum_pend = $jum_pend->sd_bulan_ini;
                    $sisa_jp = $ang_jum_pend - $real_jum_pend;
                    $persen_jp = empty($ang_jum_pend) || $ang_jum_pend == 0 ? 0 : $real_jum_pend / $ang_jum_pend * 100;
                    $sisa_jp1 = $sisa_jp < 0 ? $sisa_jp * -1 : $sisa_jp;
                    $ajp = $sisa_jp1 < 0 ? '(' : '';
                    $bjp = $sisa_jp1 < 0 ? ')' : '';

                        if (($ang_jum_pend == 0) || ($ang_jum_pend == '')) {
                            $persen_surplus = 0;
                        } else {
                            $persen_surplus = $real_jum_pend / $ang_jum_pend * 100;
                        }
                @endphp
                <tr>
                    <td align="left" valign="top"></td> 
                    <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($ang_jum_pend)}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($real_jum_pend)}}</b></td> 
                    <td align="right" valign="top"><b>{{$ajp}} {{rupiah($sisa_jp1)}} {{$bjp}}</b></td> 
                    <td align="right" valign="top"><b>{{$ajp}} {{rupiah($sisa_jp1)}} {{$bjp}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($persen_jp)}}</b></td> 
                </tr>
                <tr>
                    <td align="left" valign="top">&nbsp;</td> 
                    <td align="left"  valign="top">&nbsp;</td> 
                    <td align="right" valign="top"> </td> 
                    <td align="right" valign="top"> </td> 
                    <td align="right" valign="top"> </td> 
                    <td align="right" valign="top"> </td> 
                    <td align="right" valign="top"> </td> 
                </tr>

                @php
                    $ang_jum_bel = $jum_bel->anggaran;
                    $real_jum_bel = $jum_bel->sd_bulan_ini;
                    $sisa_jB = $ang_jum_bel - $real_jum_bel;
                    $persen_jB = empty($ang_jum_bel) || $ang_jum_bel == 0 ? 0 : $real_jum_bel / $ang_jum_bel * 100;
                    $sisa_jB1 = $sisa_jB < 0 ? $sisa_jB * -1 : $sisa_jB;
                    $ajB = $sisa_jB1 < 0 ? '(' : '';
                    $bjB = $sisa_jB1 < 0 ? ')' : '';

                        if (($ang_jum_bel == 0) || ($ang_jum_bel == '')) {
                            $persen_surplus = 0;
                        } else {
                            $persen_surplus = $real_jum_bel / $ang_jum_bel * 100;
                        }
                @endphp
                <tr>
                    <td align="left" valign="top"></td> 
                    <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($ang_jum_bel)}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($real_jum_bel)}}</b></td> 
                    <td align="right" valign="top"><b>{{$ajB}} {{rupiah($sisa_jB1)}} {{$bjB}}</b></td> 
                    <td align="right" valign="top"><b>{{$ajB}} {{rupiah($sisa_jB1)}} {{$bjB}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($persen_jB)}}</b></td> 
                </tr>
                <tr>
                    <td align="left" valign="top">&nbsp;</td> 
                    <td align="left"  valign="top">&nbsp;</td> 
                    <td align="right" valign="top"> </td> 
                    <td align="right" valign="top"> </td> 
                    <td align="right" valign="top"> </td> 
                    <td align="right" valign="top"> </td> 
                    <td align="right" valign="top"> </td> 
                </tr>
                @foreach ($rincian_bel as $row)
                        @php
                            $urut = $row->urut;
                            $kd_sub_kegiatan = $row->kd_sub_kegiatan;
                            $kd_sub_kegiatan_potong = substr($row->kd_sub_kegiatan, 0, 15);
                            $kd_rek = $row->kd_rek;
                            $nm_rek = $row->nm_rek;
                            $nil_ang = $row->anggaran;
                            $sd_bulan_ini = $row->sd_bulan_ini;
                            $sisa = $nil_ang - $sd_bulan_ini;
                            $persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
                            $sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
                            $a = $sisa < 0 ? '(' : '';
                            $b = $sisa < 0 ? ')' : '';

                            $leng = strlen($kd_rek);
                        
                        @endphp
                              
                        @if ($urut == 1)
                            <tr>
                                <td align="left" valign="top"><b>{{$kd_sub_kegiatan}}</b></td> 
                                <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                                <td align="right" valign="top"><b>{{rupiah($nil_ang)}}</b></td> 
                                <td align="right" valign="top"><b>{{rupiah($sd_bulan_ini)}}</b></td> 
                                <td align="right" valign="top"><b>{{$a}} {{rupiah($sisa1)}} {{$b}}</b></td> 
                                <td align="right" valign="top"><b>{{$a}} {{rupiah($sisa1)}} {{$b}}</b></td> 
                                <td align="right" valign="top"><b>{{rupiah($persen)}}</b></td> 
                            </tr>
                        @elseif ($urut == 2)
                            <tr>
                                <td align="left" valign="top">{{$kd_sub_kegiatan}}</td> 
                                <td align="left"  valign="top">{{$nm_rek}}</td> 
                                <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                                <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr>
                        @elseif ($urut == 3)
                            <tr>
                                <td align="left" valign="top">{{$kd_sub_kegiatan}}</td> 
                                <td align="left"  valign="top">{{$nm_rek}}</td> 
                                <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                                <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr>
                        @elseif ($urut == 4)
                            <tr>
                                <td align="left" valign="top">{{$kd_sub_kegiatan_potong}}.{{dotrek($kd_rek)}}</td> 
                                <td align="left"  valign="top">{{$nm_rek}}</td> 
                                <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                                <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr> 
                        @elseif ($urut == 5)
                            <tr>
                                <td align="left" valign="top">{{$kd_sub_kegiatan_potong}}.{{dotrek($kd_rek)}}</td> 
                                <td align="left"  valign="top">{{$nm_rek}}</td> 
                                <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                                <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr> 
                        @elseif ($urut == 6)
                            <tr>
                                <td align="left" valign="top">{{$kd_sub_kegiatan_potong}}.{{dotrek($kd_rek)}}</td> 
                                <td align="left"  valign="top">{{$nm_rek}}</td> 
                                <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                                <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr>
                        @elseif ($urut == 7)
                            <tr>
                                <td align="left" valign="top">{{$kd_sub_kegiatan_potong}}.{{dotrek($kd_rek)}}</td> 
                                <td align="left"  valign="top">{{$nm_rek}}</td> 
                                <td align="right" valign="top">{{rupiah($nil_ang)}}</td> 
                                <td align="right" valign="top">{{rupiah($sd_bulan_ini)}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{$a}} {{rupiah($sisa1)}} {{$b}}</td> 
                                <td align="right" valign="top">{{rupiah($persen)}}</td> 
                            </tr> 
                        @else
                            <tr>
                                <td align="left" valign="top"><b>{{$kd_sub_kegiatan}}</b></td> 
                                <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                                <td align="right" valign="top"><b>{{rupiah($nil_ang)}}</b></td> 
                                <td align="right" valign="top"><b>{{rupiah($sd_bulan_ini)}}</b></td> 
                                <td align="right" valign="top"><b>{{$a}} {{rupiah($sisa1)}} {{$b}}</b></td> 
                                <td align="right" valign="top"><b>{{$a}} {{rupiah($sisa1)}} {{$b}}</b></td> 
                                <td align="right" valign="top"><b>{{rupiah($persen)}}</b></td> 
                            </tr>
                        @endif   
                        
                @endforeach
                <tr>
                    <td align="left" valign="top"></td> 
                    <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($ang_jum_bel)}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($real_jum_bel)}}</b></td> 
                    <td align="right" valign="top"><b>{{$ajB}} {{rupiah($sisa1)}} {{$bjB}}</b></td> 
                    <td align="right" valign="top"><b>{{$ajB}} {{rupiah($sisa1)}} {{$bjB}}</b></td> 
                    <td align="right" valign="top"><b>{{rupiah($persen_jB)}}</b></td> 
                </tr>

    </table>
    {{-- isi --}}
    @if ($jenis_ttd !=0)
    <div style="padding-top:20px">
        <table class="table" style="width: 100%;font-size:12px;font-family:Open Sans">
            <tr>
                <td style="font-size:14px;font-family:Open Sans;margin: 2px 0px;text-align: center;" width='50%'>
                    &nbsp;
                </td>
                <td style="font-size:14px;font-family:Open Sans;margin: 2px 0px;text-align: center;" width='50%'>
                    {{ $daerah->daerah }},
                        {{ \Carbon\Carbon::parse($tanggal_ttd)->locale('id')->isoFormat('DD MMMM Y') }}
                </td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;padding-bottom: 50px;text-align: center;">
                </td>
                <td style="font-size:14px;font-family:Open Sans;padding-bottom: 50px;text-align: center;">
                    {{ ucwords(strtolower($tandatangan->jabatan)) }}
                </td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"><b></b></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"><b><u>{{ $tandatangan->nama }}</u></b></td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
            </tr>
            <tr>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
                <td style="font-size:14px;font-family:Open Sans;text-align: center;"></td>
            </tr>

        </table>
    </div>
    @endif
    {{-- tanda tangan --}}
    
</body>

</html>
