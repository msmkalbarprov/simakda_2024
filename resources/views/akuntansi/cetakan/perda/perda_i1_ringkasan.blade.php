<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LRA PERDA I.1 RINGKASAN</title>
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
            <TD colspan="3" width="100%" valign="top" align="left" >LAMPIRAN I.1 &nbsp;{{ strtoupper($nogub->ket_perda) }}</TD>
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
                <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden" ><strong>REKAPITULASI REALISASI BELANJA DAERAH MENURUT URUSAN PEMERINTAH DAERAH, <BR> ORGANISASI, PROGRAM, DAN KEGIATAN</strong></td>
            </tr>
            <tr>
                <td align="center" style="border-left:hidden;border-top:hidden" ><strong>TAHUN ANGGARAN {{ tahun_anggaran() }} </strong></td>
            </tr>

        </table>

    <hr>
 
    {{-- isi --}}
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="2" cellpadding="2">
        <thead>
            <tr>
                <td rowspan = "3" width="5%" align="center" bgcolor="#CCCCCC" style="font-size:12px">KODE</td>
                <td rowspan = "3" width="20%" align="center" bgcolor="#CCCCCC" style="font-size:12px">URUSAN PEMERINTAH DAERAH</td>
                <td colspan = "4" width="20%" align="center" bgcolor="#CCCCCC" style="font-size:12px">PENDAPATAN</td>
                <td colspan = "6" width="40%" align="center" bgcolor="#CCCCCC" style="font-size:12px">BELANJA</td>
            </tr>
            <tr>
               <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">ANGGARAN SETELAH PERUBAHAN</td> 
               <td align ="center" bgcolor="#CCCCCC" style="font-size:12px">REALISASI</td> 
               <td colspan ="2" align="center" bgcolor="#CCCCCC" style="font-size:12px">BERTAMBAH/KURANG</td> 
               <td rowspan ="2" align="LEFT" bgcolor="#CCCCCC" style="font-size:9px">    
                    7. BLJ PEGAWAI<BR>
                    8. BLJ BARANG JASA<BR>
                    9. BLJ MODAL<BR>
                    10.BLJ BUNGA<BR>
                    11.BLJ SUBSIDI<BR>
                    12.BLJ HIBAH<BR>
                    13.BLJ BANSOS<BR>
                    14.BLJ BAGI HASIL<BR>
                    15.BLJ BANTUAN KEU.<BR>
                    16.BLJ TDK TERDUGA
                </td> 
                <td rowspan ="2" align="center" bgcolor="#CCCCCC" style="font-size:12px">JUMLAH BELANJA</td> 
                <td rowspan ="2" align="LEFT" bgcolor="#CCCCCC" style="font-size:9px">    
                    18.BLJ PEGAWAI<BR>
                    19.BLJ BARANG JASA<BR>
                    20.BLJ MODAL<BR>
                    21.BLJ BUNGA<BR>
                    22.BLJ SUBSIDI<BR>
                    23.BLJ HIBAH<BR>
                    24.BLJ BANSOS<BR>
                    25.BLJ BAGI HASIL<BR>
                    26.BLJ BANTUAN KEU.<BR>
                    27.BLJ TDK TERDUGA
                </td> 
                <td rowspan ="2" align="center" bgcolor="#CCCCCC" style="font-size:12px">JUMLAH BELANJA</td> 
                <td colspan="2" align="center" bgcolor="#CCCCCC" style="font-size:12px">BERTAMBAH/KURANG</td> 
            </tr>
            <tr>
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">RP.</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">RP.</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">RP.</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">%</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">RP.</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">%</td> 
            </tr>
            <tr>
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">1</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">2</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">3</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">4</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">5(4-3)</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">6</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">RP</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">17=(7+sd+16)</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">RP</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">28=(18+sd+27)</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">29=28-17</td> 
                <td align="center" bgcolor="#CCCCCC" style="font-size:12px">30</td> 
            </tr>
        </thead>
            @php
            	$no=0;
            @endphp
                @foreach ($rincian as $row)
                    @php
                       $kode            = $row->kode;
                       $nama            = $row->nama;
                       $ang_pend        = $row->ang_pend;
                       $ang_peg         = $row->ang_peg;
                       $ang_brjs        = $row->ang_brjs;
                       $ang_modal       = $row->ang_modal;
                       $ang_bunga       = $row->ang_bunga;
                       $ang_subsidi     = $row->ang_subsidi;
                       $ang_hibah       = $row->ang_hibah;
                       $ang_bansos      = $row->ang_bansos;
                       $ang_bghasil     = $row->ang_bghasil;
                       $ang_bantuan     = $row->ang_bantuan;
                       $ang_takterduga  = $row->ang_takterduga;
                       $tot_ang         = $row->tot_ang;
                       $bel_pend        = $row->bel_pend;
                       $bel_peg         = $row->bel_peg;
                       $bel_brjs        = $row->bel_brjs;
                       $bel_modal       = $row->bel_modal;
                       $bel_bunga       = $row->bel_bunga;
                       $bel_subsidi     = $row->bel_subsidi;
                       $bel_hibah       = $row->bel_hibah;
                       $bel_bansos      = $row->bel_bansos;
                       $bel_bghasil     = $row->bel_bghasil;
                       $bel_bantuan     = $row->bel_bantuan;
                       $bel_takterduga  = $row->bel_takterduga;
                       $tot_bel         = $row->tot_bel;

                        if (($ang_pend == 0) || ($ang_pend == '')) {
                            $per_pend = 0;
                        }else {
                            $per_pend = $bel_pend / $ang_pend * 100;
                        }

                        if (($tot_ang == 0) || ($tot_ang == '')) {
                            $per_bel = 0;
                        }else {
                            $per_bel = $tot_bel / $tot_ang * 100;
                        }
                        
                    @endphp

                    <tr>
                        <td align="left" valign="top" style="font-size:12px">{{$kode}}</td> 
                        <td align="left"  valign="top" style="font-size:12px">{{strtoupper($nama)}}</td> 
                        <td align="right" valign="top" style="font-size:12px">{{rupiah($ang_pend)}}</td> 
                        <td align="right" valign="top" style="font-size:12px">{{rupiah($bel_pend)}}</td> 
                        <td align="right" valign="top" style="font-size:12px">{{rupiah($bel_pend-$ang_pend)}}</td> 
                        <td align="right" valign="top" style="font-size:12px">{{rupiah($per_pend)}}</td> 
                        <td align="right" valign="top" style="font-size:12px">
                            {{rupiah($ang_peg)}}<br>
                            {{rupiah($ang_brjs)}}<br>
                            {{rupiah($ang_modal)}}<br>
                            {{rupiah($ang_bunga)}}<br>
                            {{rupiah($ang_subsidi)}}<br>
                            {{rupiah($ang_hibah)}}<br>
                            {{rupiah($ang_bansos)}}<br>
                            {{rupiah($ang_bghasil)}}<br>
                            {{rupiah($ang_bantuan)}}<br>
                            {{rupiah($ang_takterduga)}}
                        </td> 
                        <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_ang)}}</td> 
                        <td align="right" valign="top" style="font-size:12px">
                            {{rupiah($bel_peg)}}<br>
                            {{rupiah($bel_brjs)}}<br>
                            {{rupiah($bel_modal)}}<br>
                            {{rupiah($bel_bunga)}}<br>
                            {{rupiah($bel_subsidi)}}<br>
                            {{rupiah($bel_hibah)}}<br>
                            {{rupiah($bel_bansos)}}<br>
                            {{rupiah($bel_bghasil)}}<br>
                            {{rupiah($bel_bantuan)}}<br>
                            {{rupiah($bel_takterduga)}}
                        </td> 
                        <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_bel)}}</td> 
                        <td align="right" valign="top" style="font-size:12px">{{rupiah($tot_bel-$tot_ang)}}</td> 
                        <td align="right" valign="top" style="font-size:12px">{{rupiah($per_bel)}}</td> 
                    </tr>

                              
                @endforeach
                @php
                    $ang_pend2          = $tot->ang_pend;
                    $ang_peg2           = $tot->ang_peg;
                    $ang_brjs2          = $tot->ang_brjs;
                    $ang_modal2         = $tot->ang_modal;
                    $ang_bunga2         = $tot->ang_bunga;
                    $ang_subsidi2       = $tot->ang_subsidi;
                    $ang_hibah2         = $tot->ang_hibah;
                    $ang_bansos2        = $tot->ang_bansos;
                    $ang_bghasil2       = $tot->ang_bghasil;
                    $ang_bantuan2       = $tot->ang_bantuan;
                    $ang_takterduga2    = $tot->ang_takterduga;
                    $tot_ang2           = $tot->tot_ang;
                    $bel_pend2          = $tot->bel_pend;
                    $bel_peg2           = $tot->bel_peg;
                    $bel_brjs2          = $tot->bel_brjs;
                    $bel_modal2         = $tot->bel_modal;
                    $bel_bunga2         = $tot->bel_bunga;
                    $bel_subsidi2       = $tot->bel_subsidi;
                    $bel_hibah2         = $tot->bel_hibah;
                    $bel_bansos2        = $tot->bel_bansos;
                    $bel_bghasil2       = $tot->bel_bghasil;
                    $bel_bantuan2       = $tot->bel_bantuan;
                    $bel_takterduga2    = $tot->bel_takterduga;
                    $tot_bel2           = $tot->tot_bel;

                    if (($ang_pend2 == 0) || ($ang_pend2 == '')) {
                        $per_pend2 = 0;
                    }else {
                        $per_pend2 = $bel_pend2 / $ang_pend2 * 100;
                    }

                    if (($tot_ang2 == 0) || ($tot_ang2 == '')) {
                        $per_bel2 = 0;
                    }else {
                        $per_bel2 = $tot_bel2 / $tot_ang2 * 100;
                    }
                    

                        $per_bel2 = 0;
                    
                @endphp
                <tr>
                    <td align="center" valign="top" style="font-size:12px" colspan="2"><b>TOTAL</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($ang_pend2)}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($bel_pend2)}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($bel_pend2-$ang_pend2)}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($per_pend2)}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>
                        {{rupiah($ang_peg2)}}<br>
                        {{rupiah($ang_brjs2)}}<br>
                        {{rupiah($ang_modal2)}}<br>
                        {{rupiah($ang_bunga2)}}<br>
                        {{rupiah($ang_subsidi2)}}<br>
                        {{rupiah($ang_hibah2)}}<br>
                        {{rupiah($ang_bansos2)}}<br>
                        {{rupiah($ang_bghasil2)}}<br>
                        {{rupiah($ang_bantuan2)}}<br>
                        {{rupiah($ang_takterduga2)}}</b>
                    </td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_ang2)}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>
                        {{rupiah($bel_peg2)}}<br>
                        {{rupiah($bel_brjs2)}}<br>
                        {{rupiah($bel_modal2)}}<br>
                        {{rupiah($bel_bunga2)}}<br>
                        {{rupiah($bel_subsidi2)}}<br>
                        {{rupiah($bel_hibah2)}}<br>
                        {{rupiah($bel_bansos2)}}<br>
                        {{rupiah($bel_bghasil2)}}<br>
                        {{rupiah($bel_bantuan2)}}<br>
                        {{rupiah($bel_takterduga2)}}</b>
                    </td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_bel2)}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($tot_bel2-$tot_ang2)}}</b></td> 
                    <td align="right" valign="top" style="font-size:12px"><b>{{rupiah($per_bel2)}}</b></td> 
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
