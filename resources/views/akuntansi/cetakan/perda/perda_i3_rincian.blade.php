<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA PERDA I.3 RINCIAN</title>
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
    <table style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD colspan="3" width="100%" valign="top" align="left" >LAMPIRAN I.3 &nbsp;{{ strtoupper($nogub->ket_perda) }}</TD>
        </TR>
        <TR>
            <TD  colspan="3" width="100%" valign="top" align="left" >NOMOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($nogub->ket_perda_no) }}</TD>
        </TR>
        <TR>
            <TD colspan="3" width="100%" valign="top" align="left" >TENTANG &nbsp; {{ strtoupper($nogub->ket_perda_tentang) }}</TD>
        </TR>
    </table>
    <table  style="border-collapse:collapse;font-family:Arial" width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
        <tr>
            <td rowspan="4" align="center" style="border-right:hidden">
                <img src="{{asset('template/assets/images/'.$header->logo_pemda_hp) }}"  width="75" height="100" />
            </td>
            
        </tr>
        <tr>
            <td align="center" style="border-left:hidden;border-bottom:hidden"><strong>PEMERINTAH {{ strtoupper($header->nm_pemda) }}</strong></td>
        </tr>
        <tr>
            <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden" ><strong>RINCIAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, KEGIATAN, SUB KEGIATAN, KELOMPOK, <BR> DAN JENIS PENDAPATAN, BELANJA, DAN PEMBIAYAAN</strong></td>
        </tr>
        <tr>
            <td align="center" style="border-left:hidden;border-top:hidden" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }} </strong></td>
        </tr>
    </table>

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
 
    {{-- isi --}}
    <table style="border-collapse:collapse;font-family:Arial;font-size:11px" width="100%" align="center" border="1" cellspacing="3" cellpadding="3">
        <thead>
            <tr>
                <td rowspan="2" width="7%" align="center" bgcolor="#CCCCCC" ><b>KD REK</b></td>
                <td rowspan="2" width="25%" align="center" bgcolor="#CCCCCC" ><b>URAIAN</b></td>
                <td colspan="2" width="45%" align="center" bgcolor="#CCCCCC" ><b>JUMLAH (Rp.)</b></td>
                <td colspan="2" width="15%" align="center" bgcolor="#CCCCCC" ><b>BERTAMBAH(BERKURANG)</b></td>
                <td rowspan="2" width="30%" align="center" bgcolor="#CCCCCC" ><b>DASAR HUKUM</b></td>
            </tr>
            <tr>
                <td width="13%" align="center" bgcolor="#CCCCCC" ><b>ANGGARAN</b></td>
                <td width="13%" align="center" bgcolor="#CCCCCC" ><b>REALISASI</b></td>
                <td width="13%" align="center" bgcolor="#CCCCCC" ><b>Rp.</b></td>
                <td width="5%" align="center" bgcolor="#CCCCCC" ><b>%</b></td>
            </tr>
            <tr>
               <td align="center" bgcolor="#CCCCCC" >1</td> 
               <td align="center" bgcolor="#CCCCCC" >2</td> 
               <td align="center" bgcolor="#CCCCCC" >3</td> 
               <td align="center" bgcolor="#CCCCCC" >4</td> 
               <td align="center" bgcolor="#CCCCCC" >5=(4-3)</td> 
               <td align="center" bgcolor="#CCCCCC" >6</td> 
               <td align="center" bgcolor="#CCCCCC" >7</td> 
            </tr>
        </thead>
            @php
            	$no=0;
            @endphp
            @foreach ($pend as $row)
                @php
                    $kd_sub_kegiatan = $row->kd_sub_kegiatan;
                    $kd_rek = $row->kd_rek;
                    $nm_rek = $row->nm_rek;
                    $ang_pend = $row->anggaran;
                    $nm_hukum = $row->nm_hukum;
                    $real_pend = $row->sd_bulan_ini;
                    $sisa_pend= $real_pend-$ang_pend;

                    if (($ang_pend == 0) || ($ang_pend == '')) {
                        $per_pend = 0;
                    }else {
                        $per_pend = $real_pend / $ang_pend * 100;
                    }

                    if ($sisa_pend < 0 ) {
                        $as_pend = "(";
                        $sisa_pend = $sisa_pend*-1;
                        $bs_pend = ")";
                    }else {
                        $as_pend = "";
                        $sisa_pend = $sisa_pend;
                        $bs_pend = "";
                    }

                    $leng=strlen($kd_rek);
                    if ($leng=='') {
                        $angnya=$ang_pend;
                    }
                @endphp

                @if($angnya>0)
                    @if($leng==4)
                        <tr>
                            <td align="left" valign="top">{{$kd_sub_kegiatan}}.{{dotrek($kd_rek)}}</td> 
                            <td align="left"  valign="top">{{$nm_rek}}</td> 
                            <td align="right" valign="top">{{rupiah($ang_pend)}}</td> 
                            <td align="right" valign="top">{{rupiah($real_pend)}}</td> 
                            <td align="right" valign="top">{{$as_pend}} {{rupiah($sisa_pend)}} {{$bs_pend}}</td> 
                            <td align="right" valign="top">{{rupiah($per_pend)}}</td> 
                            <td align="left" valign="top">{{$nm_hukum}}</td> 
                        </tr>
                    @else
                        <tr>
                            <td align="left" valign="top"><b>{{$kd_sub_kegiatan}}.{{dotrek($kd_rek)}}</b></td> 
                            <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                            <td align="right" valign="top"><b>{{rupiah($ang_pend)}}</b></td> 
                            <td align="right" valign="top"><b>{{rupiah($real_pend)}}</b></td> 
                            <td align="right" valign="top"><b>{{$as_pend}} {{rupiah($sisa_pend)}} {{$bs_pend}}</b></td> 
                            <td align="right" valign="top"><b>{{rupiah($per_pend)}}</b></td> 
                            <td align="right" valign="top"><b></td> 
                        </tr>
                    @endif
                @else
                @endif
            @endforeach

            @php
                $ang_jpend = $jum_pend->anggaran;
                $real_jpend = $jum_pend->sd_bulan_ini;
                $sisa_jpend=$real_jpend-$ang_jpend;
                if (($ang_jpend == 0) || ($ang_jpend == '')) {
                    $per_jpend = 0;
                }else {
                    $per_jpend = $real_jpend / $ang_jpend * 100;
                }

                if ($sisa_jpend < 0 ) {
                    $as_jpend = "(";
                    $sisa_jpend = $sisa_jpend*-1;
                    $bs_jpend = ")";
                }else {
                    $as_jpend = "";
                    $sisa_jpend = $sisa_jpend;
                    $bs_jpend = "";
                }
            @endphp
            <tr>
                <td align="left" valign="top"></td> 
                <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
                <td align="right" valign="top"><b>{{rupiah($ang_jpend)}}</b></td> 
                <td align="right" valign="top"><b>{{rupiah($real_jpend)}}</b></td> 
                <td align="right" valign="top"><b>{{$as_jpend}} {{rupiah($sisa_jpend)}} {{$bs_jpend}}</b></td> 
                <td align="right" valign="top"><b>{{rupiah($per_jpend)}}</b></td> 
                <td align="right" valign="top"><b></td> 
            </tr>            

            @php
                $ang_jbel = $jum_bel->anggaran;
                $real_jbel = $jum_bel->sd_bulan_ini;
                $sisa_jbel=$real_jbel-$ang_jbel;
                if (($ang_jbel == 0) || ($ang_jbel == '')) {
                    $per_jbel = 0;
                }else {
                    $per_jbel = $real_jbel / $ang_jbel * 100;
                }

                if ($sisa_jbel < 0 ) {
                    $as_jbel = "(";
                    $sisa_jbel = $sisa_jbel*-1;
                    $bs_jbel = ")";
                }else {
                    $as_jbel = "";
                    $sisa_jbel = $sisa_jbel;
                    $bs_jbel = "";
                }
            @endphp
            <tr>
                <td align="left" valign="top"></td> 
                <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
                <td align="right" valign="top"><b>{{rupiah($ang_jbel)}}</b></td> 
                <td align="right" valign="top"><b>{{rupiah($real_jbel)}}</b></td> 
                <td align="right" valign="top"><b>{{$as_jbel}} {{rupiah($sisa_jbel)}} {{$bs_jbel}}</b></td> 
                <td align="right" valign="top"><b>{{rupiah($per_jbel)}}</b></td> 
                <td align="right" valign="top"><b></td> 
            </tr> 

            @foreach ($belanja as $row)
                @php
                    $kd_sub_kegiatan = $row->kd_sub_kegiatan;
                    $kd_rek = $row->kd_rek;
                    $nm_rek = $row->nm_rek;
                    $ang_bel = $row->anggaran;
                    $real_bel = $row->sd_bulan_ini;
                    $sisa_bel= $real_bel-$ang_bel;

                    if (($ang_bel == 0) || ($ang_bel == '')) {
                        $per_bel = 0;
                    }else {
                        $per_bel = $real_bel / $ang_bel * 100;
                    }

                    if ($sisa_bel < 0 ) {
                        $as_bel = "(";
                        $sisa_bel = $sisa_bel*-1;
                        $bs_bel = ")";
                    }else {
                        $as_bel = "";
                        $sisa_bel = $sisa_bel;
                        $bs_bel = "";
                    }

                    $leng_bel=strlen($kd_sub_kegiatan);
                @endphp

                @if($leng_bel==20)
                    <tr>
                        <td align="left" valign="top">{{$kd_sub_kegiatan}}.{{dotrek($kd_rek)}}</td> 
                        <td align="left"  valign="top">{{$nm_rek}}</td> 
                        <td align="right" valign="top">{{rupiah($ang_bel)}}</td> 
                        <td align="right" valign="top">{{rupiah($real_bel)}}</td> 
                        <td align="right" valign="top">{{$as_bel}} {{rupiah($sisa_bel)}} {{$bs_bel}}</td> 
                        <td align="right" valign="top">{{rupiah($per_bel)}}</td> 
                        <td align="right" valign="top"></td> 
                    </tr>
                @else
                    <tr>
                        <td align="left" valign="top"><b>{{$kd_sub_kegiatan}}.{{dotrek($kd_rek)}}</b></td> 
                        <td align="left"  valign="top"><b>{{$nm_rek}}</b></td> 
                        <td align="right" valign="top"><b>{{rupiah($ang_bel)}}</b></td> 
                        <td align="right" valign="top"><b>{{rupiah($real_bel)}}</b></td> 
                        <td align="right" valign="top"><b>{{$as_bel}} {{rupiah($sisa_bel)}} {{$bs_bel}}</b></td> 
                        <td align="right" valign="top"><b>{{rupiah($per_bel)}}</b></td> 
                        <td align="right" valign="top"><b></td> 
                    </tr>
                @endif
            @endforeach 
            <tr>
                <td align="left" valign="top"></td> 
                <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
                <td align="right" valign="top"><b>{{rupiah($ang_jbel)}}</b></td> 
                <td align="right" valign="top"><b>{{rupiah($real_jbel)}}</b></td> 
                <td align="right" valign="top"><b>{{$as_jbel}} {{rupiah($sisa_jbel)}} {{$bs_jbel}}</b></td> 
                <td align="right" valign="top"><b>{{rupiah($per_jbel)}}</b></td> 
                <td align="right" valign="top"><b></td> 
            </tr> 
            @php

                $sisa_surplus= $real_surplus-$ang_surplus;

                if (($ang_surplus == 0) || ($ang_surplus == '')) {
                    $per_surplus = 0;
                }else {
                    $per_surplus = $real_surplus / $ang_surplus * 100;
                }

                if ($ang_surplus < 0 ) {
                    $aa_surplus = "(";
                    $ang_surplus = $ang_surplus*-1;
                    $ba_surplus = ")";
                }else {
                    $aa_surplus = "";
                    $ang_surplus = $ang_surplus;
                    $ba_surplus = "";
                }

                if ($real_surplus < 0 ) {
                    $ar_surplus = "(";
                    $real_surplus = $real_surplus*-1;
                    $br_surplus = ")";
                }else {
                    $ar_surplus = "";
                    $real_surplus = $real_surplus;
                    $br_surplus = "";
                }

                if ($sisa_surplus < 0 ) {
                    $as_surplus = "(";
                    $sisa_surplus = $sisa_surplus*-1;
                    $bs_surplus = ")";
                }else {
                    $as_surplus = "";
                    $sisa_surplus = $sisa_surplus;
                    $bs_surplus = "";
                }
                if ($per_surplus < 0 ) {
                    $ap_surplus = "(";
                    $per_surplus = $per_surplus*-1;
                    $bp_surplus = ")";
                }else {
                    $ap_surplus = "";
                    $per_surplus = $per_surplus;
                    $bp_surplus = "";
                }
            @endphp
            <tr>
                <td align="left" valign="top"></td> 
                <td align="left"  valign="top"><b>SURPLUS/(DEFISIT)</b></td> 
                <td align="right" valign="top"><b>{{$aa_surplus}} {{rupiah($ang_surplus)}} {{$ba_surplus}}</b></td> 
                <td align="right" valign="top"><b>{{$ar_surplus}} {{rupiah($real_surplus)}} {{$br_surplus}}</b></td> 
                <td align="right" valign="top"><b>{{$as_surplus}} {{rupiah($sisa_surplus)}} {{$bs_surplus}}</b></td> 
                <td align="right" valign="top"><b>{{$ap_surplus}} {{rupiah($per_surplus)}} {{$bp_surplus}}</b></td> 
                <td align="right" valign="top"><b></td> 
            </tr>          
    </table>
    {{-- isi --}}
    
    {{-- tanda tangan --}}
    <table style="border-collapse:collapse;font-family:Arial;font-size:12px" width="100%" align="center" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td width="50%" align="center">&nbsp;</td>
            <td width="50%" align="center"></td>
        </tr>
        <tr>
            <td width="50%" align="center">&nbsp;</td>
            <td width="50%" align="center">Pontianak, {{tgl_format_oyoy($tanggal_ttd)}}<br>GUBERNUR KALIMANTAN BARAT<br><br><br><br><br><b><u>SUTARMIDJI</u></b>
            </td>
        </tr>
    </table>
    
</body>

</html>
